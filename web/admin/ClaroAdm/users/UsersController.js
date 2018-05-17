angular.module("ClaroAdm").controller("UsersController", function ($scope,  $timeout, focus, confirm, $rootScope,$state, YII2Request){
    $scope.init = function(resp) {
        //Pagination
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.rangePagination = 5;
        $scope.maxSize = 10;
        //$scope.bigTotalItems = 175;
        //$scope.bigCurrentPage = 1;
        //VARIABLES
        $rootScope.users = [];
        $scope.ddds = [];
        $rootScope.user = {};
        $scope.submitted = false;
        console.log($rootScope.token);
        YII2Request.request('ddd-groups', "GET", null, $rootScope.token, function (resp) {
            if (resp.status == 200) {
                console.log(resp);
                //http://plnkr.co/edit/V6RkITHxtwOLiwWJlezI?p=preview
                $scope.ddds = resp.data;
            }
        });
        $scope.actions.listUsers(1);
    }
    $scope.actions = {
        listUsers: function(page){
            YII2Request.request('users?per-page=10&page='+page, "GET", null, $rootScope.token, function(resp){
                console.log("listUsers", resp);
                console.log(resp.headers('x-pagination-current-page'));
                console.log(resp.headers('x-pagination-per-page'));
                console.log(resp.headers('X-Pagination-Total-Count'));
                $scope.totalItems = resp.headers('X-Pagination-Total-Count');
                $scope.currentPage = resp.headers('x-pagination-current-page');
                //var totalCount = resp.headers.get('x-total-count');
                console.log("totalCount");
                if(resp.status == 200){
                    $rootScope.users = resp.data;
                    for(var d in $rootScope.users){
                        if($rootScope.users[d].status == 1){
                            $rootScope.users[d].statusFlag = true;
                        }else{
                            $rootScope.users[d].statusFlag = false;
                        }
                    }
                    console.log("$rootScope.users", $rootScope.users);
                }
            });
        }
    }
    //
    $scope.methods = {
        registerModal : function(){
            $scope.user = {};
            $('#modalRegister').modal('show');
        },
        editModal : function(user){
            console.log("use",user);
            $rootScope.user = user;
            $('#modalEdit').modal('show');
        },
        register: function(user){
            user.status = 1;
            $scope.submitted = true;
            if($scope.register.$valid){
                YII2Request.request('users', "POST", user,$rootScope.token,function(resp){
                    console.log("response,", resp);
                    if(resp.status == 201){
                        swal("Usuário cadastrado com sucesso!");
                        $scope.actions.listUsers($scope.currentPage);
                        $('#modalRegister').modal('hide');
                    }else if(resp.status == 422){
                        console.log("é 422")
                        confirm({text:resp.data[0].message,showCancelButton:true, confirmButtonText:"Ok"},function(){
                            focus(resp.data[0].field);
                        })
                    }else{
                        swal("Erro ao cadastrar usuário")
                    }
                });
            }
        },
        edit: function(user){
            $scope.submitted = true;
            if(user.statusFlag == true){
                user.status = 1;
            }else{
                user.status = 0;
            }
            if($scope.edit.$valid){
                YII2Request.request('users/'+user.id,"PATCH", user,$rootScope.token,function(resp){
                    if(resp.status == 200){
                        swal("Usuário alterado com sucesso");
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
        delete: function(user){
            confirm({text:"Deseja mesmo deletar esse usuário?"}, function(){
                YII2Request.request('users/'+user.id,"DELETE",null,$rootScope.token,function(resp){
                    console.log(resp);
                    if(resp.status == 204){
                        swal("Usuário deletado com sucesso");
                        $scope.actions.listUsers($scope.currentPage);
                    }
                })
            })
        },
        pageChanged: function(page){
            $scope.currentPage = page;
            $scope.actions.listUsers(page);
        }
    };
    $scope.init();
})