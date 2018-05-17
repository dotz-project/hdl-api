angular.module("ClaroAdm").controller("MaterialDetailController", function ($scope,$stateParams, $timeout, focus, confirm, $rootScope,$state, YII2Request){
    $scope.init = function(resp) {
        //Pagination
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.rangePagination = 5;
        $scope.maxSize = 10;
        //$scope.bigTotalItems = 175;
        //$scope.bigCurrentPage = 1;
        //VARIABLES
        $scope.noWrapSlides = false;
        $scope.active = 0;
        var currIndex = 0;
        $scope.myInterval = 4000;
        $scope.material = {};
        $scope.submitted = false;
        $scope.actions.detail();
        $rootScope.sheetSelect="";
        $scope.allDDDs = [];
        //$scope.actions.listMaterials(1);
        //status,created_at,image_map:[{link:"uol.com.br",area:"139,138"}]
        //handout_id, id, image_sm:null, image_lg, label
        //&expand=hangoutSheets
    }
    $scope.alert = function(a){
        alert(a);
    }
    $scope.actions = {
        dateConfig:{
            applyClass: 'btn-danger',
            locale: {
                applyLabel: "Aplicar",
                fromLabel: "De",
                format: "DD/MM/YYYY HH:MM:ss",
                toLabel: "Até",
                cancelLabel: 'Cancelar',
                "daysOfWeek": [
                    "Dom",
                    "Seg",
                    "Ter",
                    "Qua",
                    "Qui",
                    "Sex",
                    "Sab"
                ],
                "monthNames": [
                    "Janeiro",
                    "Fevereiro",
                    "Março",
                    "Abril",
                    "Maio",
                    "Junho",
                    "Julho",
                    "Agosto",
                    "Setembro",
                    "Outubro",
                    "Novembro",
                    "Dezembro"
                ],
                //customRangeLabel: 'Custom range'
            },
            timePicker: true,
            startDate: moment("2013-02-01"),
            endDate: moment("2013-03-04")
        },
        detail:function(){
            console.log("$rootScope.sheet", $rootScope.sheetSelect);
            YII2Request.request("handouts/detail/?id="+$stateParams.id+"?expand=handoutSheets","GET", null, $rootScope.token, function(resp){
                if(resp.status==200){
                    $scope.material = resp.data;
                    $scope.material.validy = {};
                    if($scope.material.status == 1){
                        $scope.material.statusFlag = true;
                    }else{
                        $scope.material.statusFlag = false;
                    }
                    $scope.actions.dddGroup(function(resp){
                        console.log(resp);
                        YII2Request.request("ddd-groups/"+resp.ddd_group_id, "GET", null, $rootScope.token, function(resp){
                            if(resp.status==200){
                                $scope.material.ddd_groups = resp.data;
                                console.log("material", $scope.material);
                            }
                        });
                    });
                    $scope.actions.allDDDGroups();
                }
            });
        },
        dddGroup: function(callback){
            YII2Request.request("handout-ddd-groups/"+$stateParams.id, "GET", null, $rootScope.token, function(resp){
                if(resp.status==200){
                    callback(resp.data);
                }
            });
        },
        allDDDGroups: function(){
            YII2Request.request("ddd-groups", "GET", null, $rootScope.token, function(resp){
                if(resp.status==200){
                    $scope.allDDDs = resp.data;
                }
            });
        }
    };
    //
    $scope.methods = {
        registerModal : function(){
            $scope.material = {};
            $('#modalRegister').modal('show');
        },
        infoModal: function(){
            $('#date').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY HH:mm:ss'
                },
                startDate: moment($scope.material.started_at),
                endDate: moment($scope.material.expired_at)
            }) 
            $scope.actions.dateConfig.startDate = moment($scope.material.started_at);
            $scope.actions.dateConfig.endDate = moment($scope.material.expired_at);
            console.log("material", $scope.material);
            $("#modalEdit").modal("show");
        },
        edit: function(material){
            console.log(material);
            $scope.submitted = true;
            if ($scope.register.$valid && material.validy){
                if(material.statusFlag){
                    material.status = 1;
                }else{
                    material.status = 0;
                }
                material.started_at = moment(material.validy.startDate).format('YYYY-MM-DD HH:MM:ss');
                material.expired_at = moment(material.validy.endDate).format("YYYY-MM-DD HH:MM:ss");
                console.log(material);
                if(material.started_at == "Invalid date" || material.expired_at == "Invalid date"){
                    material.validy = "";
                    return;
                }else{
                    console.log("correto",material)
                    YII2Request.request('handouts', "POST", material,$rootScope.token,function(resp){
                        console.log("response,", resp);
                        if(resp.status == 201){
                            swal("Material editado com sucesso!");
                            $scope.actions.listMaterials($scope.currentPage);
                            $('#modalEdit').modal('hide');
                        }else if(resp.status == 422){
                            console.log("é 422")
                            confirm({text:resp.data[0].message,showCancelButton:true, confirmButtonText:"Ok"},function(){
                                focus(resp.data[0].field);
                            });
                        }else{
                            swal("Erro ao editar material")
                        }
                    });
                }
            }
        },
        delete: function(material){
            confirm({text:"Deseja mesmo deletar esse material?"}, function(){
                YII2Request.request('handouts/'+material.id,"DELETE",null,$rootScope.token,function(resp){
                    console.log(resp);
                    if(resp.status == 204){
                        swal("Material deletado com sucesso");
                        $scope.actions.listMaterials($scope.currentPage);
                    }
                })
            })
        },
        changeStatus: function(material){
            if (material.statusFlag == true) {
                material.status = 1;
            } else {
                material.status = 0;
            }
            YII2Request.request('handouts/' + material.id, "PATCH", material, $rootScope.token, function (resp) {
                if (resp.status != 200) {
                    swal("Erro ao alterar ativação")
                } 
            })
        },
        pageChanged: function(page){
            $scope.currentPage = page;
            $scope.actions.listMaterials(page);
        },
        showImage: function(img){
            console.log("img",img);
            $state.go("imageDetail");
            $rootScope.sheetSelect = img;
            //$("#modalSheet").modal("show");
            //$rootScope.sheetSelect.image_map = JSON.parse($rootScope.sheetSelect.image_map);
        },
        createArea: function(){
            alert("Teste");
            $("#result").hide();
            $("#imageOriginal").show();
            $("#generate").show();
            $("#createArea").hide();
        }
    };
    $scope.init();
})