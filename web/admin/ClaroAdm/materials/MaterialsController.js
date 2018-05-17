angular.module("ClaroAdm").controller("MaterialsController", function ($scope, toBase64,  $timeout, focus, confirm, $rootScope,$state, YII2Request){
    $scope.init = function(resp) {
        //Pagination
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.rangePagination = 5;
        $scope.maxSize = 10;
        //$scope.bigTotalItems = 175;
        //$scope.bigCurrentPage = 1;
        //VARIABLES
        $scope.ddds = [];
        $scope.materials = [];
        $scope.material = {};
        $scope.sheets = [];
        $scope.submitted = false;
        $scope.actions.listMaterials(1);
        $scope.actions.listDDDs();
    }
    $scope.actions = {
        listMaterials: function(page){
            YII2Request.request('handouts?per-page=10&page='+page, "GET", null, $rootScope.token, function(resp){
                console.log("listMaterials", resp);
                console.log(resp.headers('x-pagination-current-page'));
                console.log(resp.headers('x-pagination-per-page'));
                console.log(resp.headers('X-Pagination-Total-Count'));
                $scope.totalItems = resp.headers('X-Pagination-Total-Count');
                $scope.currentPage = resp.headers('x-pagination-current-page');
                //var totalCount = resp.headers.get('x-total-count');
                console.log("totalCount");
                if(resp.status == 200){
                    $scope.materials = resp.data;
                    for(var d in $scope.materials){
                        if($scope.materials[d].status == 1){
                            $scope.materials[d].statusFlag = true;
                        }else{
                            $scope.materials[d].statusFlag = false;
                        }
                    }
                    console.log("$scope.materials", $scope.materials);
                }
            });
        },
        listDDDs:function() {
            YII2Request.request('ddd-groups', "GET", null, $rootScope.token, function (resp) {
                if (resp.status == 200) {
                    console.log(resp);
                    //http://plnkr.co/edit/V6RkITHxtwOLiwWJlezI?p=preview
                    $scope.ddds = resp.data;
                }
            });
        },
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
            //startDate: new Date(),
            //endDate: moment("2013-03-04")
        }
    }
    //
    $scope.methods = {
        registerModal : function(){
            $scope.material = {};
            $('#modalRegister').modal('show');
        },
        register: function(material){
            console.log(material);
            $scope.submitted = true;
            if ($scope.register.$valid && material.validy){
                material.status = 1;
                material.started_at = moment(material.validy.startDate).format('YYYY-MM-DD HH:MM:ss');
                material.expired_at = moment(material.validy.endDate).format("YYYY-MM-DD HH:MM:ss");
                console.log(material);
                if(material.started_at == "Invalid date" || material.expired_at == "Invalid date"){
                    material.validy = "";
                    return;
                }else{
                    console.log("correto",material)
                    $('#modalRegister').modal('hide');
                    $('#modalDDD').modal('show');
                    // YII2Request.request('handouts', "POST", material,$rootScope.token,function(resp){
                    //     console.log("response,", resp);
                    //     if(resp.status == 201){
                    //         console.log(resp);
                    //         material.id = resp.data.id;
                    //         //swal("Material cadastrado com sucesso!");
                    //         //$scope.actions.listMaterials($scope.currentPage);
                    //     }else if(resp.status == 422){
                    //         console.log("é 422")
                    //         confirm({text:resp.data[0].message,showCancelButton:true, confirmButtonText:"Ok"},function(){
                    //             focus(resp.data[0].field);
                    //         });
                    //     }else{
                    //         $scope.material = {};
                    //         swal("Erro ao cadastrar material")
                    //     }
                    // });
                }
            }
        },
        saveDDD:function(material){
            console.log("material", material);
            if(material.ddd_group_id){
                $('#modalDDD').modal('hide');
                material.sheets = [];
                $('#modalSheets').modal('show');
            }else{
                swal("Selecione um grupo de DDD");
            }
        },
        uploadFiles:function(files){
            console.log(files);
            var newFiles = [];
            newFiles = files;
            for(var f in newFiles){
                $scope.material.sheets.push(newFiles[f]);
            }
            console.log($scope.material);
        },
        sendSheets:function(material){
            $scope.progressB64 = true;
            function convert(index){
                console.log("atual", material.sheets[index] );
                var base64 = toBase64(material.sheets[index], function(base64) {
                    material.sheets[index] = base64;
                    if (index == material.sheets.length -1){
                        $scope.progressB64 = false;
                        console.log("material", material);
                        YII2Request.request('handouts', "POST", material,$rootScope.token,function(resp){
                        console.log("response,", resp);
                        if(resp.status == 201){
                            console.log(resp);
                            material.id=resp.data.id;
                            var paramDDD = {handout_id:material.id,ddd_group_id:material.ddd_group_id}
                            console.log("material nivel 2", material);
                            YII2Request.request('handout-ddd-groups', "POST",paramDDD, $rootScope.token, function (resp) {
                                console.log("response,", resp);
                                if (resp.status == 201) {
                                    var count = 0
                                    for(var k in material.sheets){
                                        var params = { handout_id: material.id, label:"teste", image_lg:material.sheets[k]};
                                        console.log("params", params);
                                        YII2Request.request("handout-sheets", "POST", params, $rootScope.token, function(resp) {
                                            if(resp.status == 201){
                                                console.log("count",count);
                                                if (count == material.sheets.length - 1){
                                                    swal("material cadastrado com sucesso!");
                                                    $scope.material = {};
                                                    $('#modalSheets').modal('hide');
                                                }else{
                                                    count = count +1;
                                                }
                                            }
                                        })
                                    }
                                }else{
                                    $scope.material = {};
                                    swal("Erro ao cadastrar material")
                                }
                            });
                        }else{
                            $scope.material = {};
                            swal("Erro ao cadastrar material")
                        }
                    });
                    }else{
                        index = index+1;
                        console.log("proximo indice", index);
                        convert(index);
                    }
                });
            }
            convert(0);
        },
        removeSheet: function (index) {
            $("modalSheetDetail").modal("show");
            $scope.material.sheets.splice(index,1);
        },
        editImage : function(sheet){
            console.log(sheet);
            toBase64(sheet, function (base64) {
                console.log(base64);
                $scope.material.detailImg = base64;
                $timeout(function() {
                    $("#modalSheetDetail").modal("show");
                },1000)
            })
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
        }
    };
    $scope.init();
})