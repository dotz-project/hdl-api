angular.module("ClaroAdm").controller("LoginController", function ($scope, YII2Request, $rootScope,$state){
    if(!localStorage.getItem('events')){
        localStorage.setItem('events', '[]');
    }
    //alert("login");
    $scope.login = {};
    $scope.submitted = false;
    console.log("rota",$rootScope.urlRoute);
    $scope.login = function(login){
        $scope.submitted = true;
        console.log("login request");
        YII2Request.request('users/login',"POST",login,null,function(resp){
            console.log("resposta login",resp);
            if(resp.status == 200){
                localStorage.setItem('token', resp.data);
                $rootScope.token = resp.data;
                console.log("$rootScope.token", $rootScope.token);
                YII2Request.request('users/me',"POST",null, $rootScope.token, function(resp){
                    if(resp.status == 200){
                        $rootScope.me = resp.data;
                        $state.go('materials');
                    }else{
                        swal("Erro ao efetuar login");
                    }
                })
            }else{
                swal(resp.data.message);
            }
        });
    };
})