angular.module("ClaroAdm").controller("NewMaterialController", function ($scope, toBase64,  $timeout, focus, confirm, $rootScope,$state, YII2Request){
    $scope.init = function(resp) {
        //Pagination
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.rangePagination = 5;
        $scope.maxSize = 10;
        $scope.editImage = false;
        $scope.imageEditable = {};
        $scope.fields = [];
        $scope.area = {};
        //$scope.bigTotalItems = 175;
        //$scope.bigCurrentPage = 1;
        //VARIABLES
        $scope.ddds = [];
        $scope.materials = [];
        $scope.material = {};
        $scope.material.sheets = [];
        $scope.material.ddds_group = [];
        $scope.sheets = [];
        $scope.submitted = false;
        $scope.actions.listDDDs();
    }
    $scope.actions = {
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
        register: function(material){
            console.log("register",material);
            $scope.submitted = true;
            if($scope.register.$valid){
                material.status = 1;
                material.started_at = moment(material.validy.startDate).format('YYYY-MM-DD HH:MM:ss');
                material.expired_at = moment(material.validy.endDate).format("YYYY-MM-DD HH:MM:ss");
                console.log(material);
                if(material.started_at == "Invalid date" || material.expired_at == "Invalid date"){
                    material.validy = "";
                    return;
                }else{
                    console.log("correto",material)
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
            // console.log(material);
            // $scope.submitted = true;
            // if ($scope.register.$valid && material.validy){
            //     material.status = 1;
            //     material.started_at = moment(material.validy.startDate).format('YYYY-MM-DD HH:MM:ss');
            //     material.expired_at = moment(material.validy.endDate).format("YYYY-MM-DD HH:MM:ss");
            //     console.log(material);
            //     if(material.started_at == "Invalid date" || material.expired_at == "Invalid date"){
            //         material.validy = "";
            //         return;
            //     }else{
            //         console.log("correto",material)
            //         $('#modalRegister').modal('hide');
            //         $('#modalDDD').modal('show');
            //         // YII2Request.request('handouts', "POST", material,$rootScope.token,function(resp){
            //         //     console.log("response,", resp);
            //         //     if(resp.status == 201){
            //         //         console.log(resp);
            //         //         material.id = resp.data.id;
            //         //         //swal("Material cadastrado com sucesso!");
            //         //         //$scope.actions.listMaterials($scope.currentPage);
            //         //     }else if(resp.status == 422){
            //         //         console.log("é 422")
            //         //         confirm({text:resp.data[0].message,showCancelButton:true, confirmButtonText:"Ok"},function(){
            //         //             focus(resp.data[0].field);
            //         //         });
            //         //     }else{
            //         //         $scope.material = {};
            //         //         swal("Erro ao cadastrar material")
            //         //     }
            //         // });
            //     }
            // }
        },
        sendSheets:function(material){
            /*$scope.progressB64 = true;
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
            convert(0);*/
        },
        removeSheet: function (index) {
            $("modalSheetDetail").modal("show");
            $scope.material.sheets.splice(index,1);
        },
        uploadFiles: function (files) {
            console.log(files);
            var newFiles = [];
            newFiles = files;
            for (var f in newFiles) {
                newFiles[f].image_map = [];
                newFiles[f].internal_id = Math.random();
                $scope.material.sheets.push(newFiles[f]);
            }
            console.log("material com imagem", $scope.material);
        },
        editImage : function(sheet){
            console.log(sheet);
            $scope.imageEditable = sheet;
            if(sheet.image_map.length > 0);
            $scope.fields = sheet.image_map;
            toBase64(sheet, function (base64) {
                console.log(base64);
                $scope.material.detailImg = base64;
                $scope.editImage = true;

            });
        },
        saveArea: function(areas){
            console.log(areas);
            for(var f in $scope.material.sheets){
                if ($scope.material.sheets[f].internal_id == $scope.imageEditable.internal_id){
                    $scope.material.sheets[f].image_map = areas;
                }
            }
            console.log("scope.material edit area", $scope.material);
            $scope.fields = [];
            $scope.editImage = false;
        },
        onAddArea : function (ev, boxId, areas, area) {
            $scope.fields.push(boxId);
            // $rootScope.sheetSelect.image_map = areas;
            $scope.area = boxId;
            $scope.area.name = "";
            $scope.area.url = "";
            $("#modalArea").modal("show");
            console.log("area added", areas, $scope.fields, area, ev, boxId);
        },
        onRemoveArea: function (ev, boxId, areas, area) {
            for (var t in $scope.fields) {
                if ($scope.fields[t].internal_id == boxId.internal_id) {
                    $scope.fields.splice(t, 1);
                }
            }
        },
        onChangeArea: function (ev, boxId, areas, area) {
            for (var t in $scope.fields) {
                if ($scope.fields[t].internal_id == boxId.internal_id) {
                    $scope.fields[t] = boxId;
                }
            }
            console.log("area que alterou", boxId, areas);
            console.log("areas mudando", $scope.fields);
        },
        confirmArea: function (area) {
            $scope.submitted = true;
            if ($scope.newArea.$valid) {
                area.internal_id = Math.random();
                $("#modalArea").modal("hide");
                console.log(area, $scope.fields);
                for (var i in $scope.fields) {
                    if ($scope.fields[i].areaid == area.areaid) {
                        $scope.fields[i] = area;
                    }
                }
                console.log("confirm alteração", area, $scope.fields);
            }
        },
        getDDD: function(ddd,check){
            console.log(ddd, check);
            if(check){
                $scope.material.ddds_group.push(ddd);
            }else{
                for(var d in $scope.material.ddds_group){
                    if($scope.material.ddds_group[d].id == ddd.id){
                        $scope.material.ddds_group.splice(d,1);
                    }
                }
            }
            console.log($scope.material);
        }
    };
    $scope.init();
})