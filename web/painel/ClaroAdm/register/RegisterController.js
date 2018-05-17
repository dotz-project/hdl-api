angular.module("ClaroAdm").controller("RegisterController", function ($scope, YII2Request, $rootScope,$state){
    $scope.init = function(resp) {
        $scope.user = {};
        $scope.submitted = false;
        YII2Request.request('ddd-groups', "GET", null, $rootScope.token, function (resp) {
            if (resp.status == 200) {
                console.log(resp);
                //http://plnkr.co/edit/V6RkITHxtwOLiwWJlezI?p=preview
                $scope.ddds = resp.data;
            }
        });
    };
    $scope.actions = {
        
    };
    //
    $scope.methods = {
        register: function(user){
            user.status = 1;
            $scope.submitted = true;
            if($scope.register.$valid){
                YII2Request.request('users', "POST", user,$rootScope.token,function(resp){
                    console.log("response,", resp);
                    if(resp.status == 201){
                        swal("Usuário cadastrado com sucesso!");
                        $state.go('login');
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
        }
    };
    $scope.init();
    //alert("login");s
})