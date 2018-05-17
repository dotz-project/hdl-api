angular.module("ClaroAdm").controller("DDDController", function($scope,  $timeout, focus, confirm, $rootScope,$state, YII2Request){
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
        $scope.ddds = [];
        $rootScope.user = {};
        $scope.submitted = false;
        console.log($rootScope.token);
        $scope.actions.listDDDs(1);
    }
    $scope.actions = {
        listDDDs: function(page){
            YII2Request.request('ddd-groups?per-page=10&page='+page, "GET", null, $rootScope.token, function(resp){
                console.log("listDDDs", resp);
                $scope.totalItems = resp.headers('X-Pagination-Total-Count');
                $scope.currentPage = resp.headers('x-pagination-current-page');
                //var totalCount = resp.headers.get('x-total-count');
                console.log("totalCount");
                if(resp.status == 200){
                    $scope.ddds = resp.data;
                    for(var d in $scope.ddds){
                        if($scope.ddds[d].status == 1){
                            $scope.ddds[d].statusFlag = true;
                        }else{
                            $scope.ddds[d].statusFlag = false;
                        }
                    }
                    console.log("$scope.ddds", $scope.ddds);
                }
            });
        }
    }
    //
    $scope.methods = {
        registerModal : function(){
            $scope.ddd = {};
            $('#modalRegister').modal('show');
        },
        editModal : function(ddd){
            console.log("ddd",ddd);
            $scope.ddd = ddd;
            $('#modalEdit').modal('show');
        },
        register: function(ddd){
            ddd.status = 1;
            $scope.submitted = true;
            if($scope.register.$valid){
                YII2Request.request('ddd-groups', "POST", ddd,$rootScope.token,function(resp){
                    console.log("response,", resp);
                    if(resp.status == 201){
                        swal("Grupo de DDDs cadastrado com sucesso!");
                        $scope.actions.listDDDs($scope.currentPage);
                        $('#modalRegister').modal('hide');
                    }else if(resp.status == 422){
                        confirm({text:resp.data[0].message,showCancelButton:true, confirmButtonText:"Ok"},function(){
                            focus(resp.data[0].field);
                        })
                    }else{
                        swal("Erro ao cadastrar DDD")
                    }
                });
            }
        },
        edit: function(ddd){
            $scope.submitted = true;
            if(ddd.statusFlag == true){
                ddd.status = 1;
            }else{
                ddd.status = 0;
            }
            if($scope.edit.$valid){
                YII2Request.request('ddd-groups/'+ddd.id,"PATCH", ddd,$rootScope.token,function(resp){
                    if(resp.status == 200){
                        swal("Grupo de DDDs alterado com sucesso");
                        $('#modalEdit').modal('hide');
                    }else if(resp.status == 422){
                        console.log("é 422");
                        confirm({text:resp.data[0].message,showCancelButton:true, confirmButtonText:"Ok"},function(){
                            focus(resp.data[0].field);
                        })
                    }
                })
            }
        },
        delete: function(ddd){
            confirm({text:"Deseja mesmo deletar esse grupo de DDDs?"}, function(){
                YII2Request.request('ddd-groups/'+ddd.id,"DELETE",null,$rootScope.token,function(resp){
                    console.log(resp);
                    if(resp.status == 204){
                        swal("Grupo de DDDs deletado com sucesso");
                        $scope.actions.listDDDs($scope.currentPage);
                    }
                })
            })
        },
        pageChanged: function(page){
            $scope.currentPage = page;
            $scope.actions.listDDDs(page);
        }
    };

    $scope.init();
})