angular.module("ClaroAdm").controller("SharedController", function ($scope, toBase64,  $timeout, focus, confirm, $rootScope,$state, YII2Request){
    $scope.init = function(resp) {
        //Pagination
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.rangePagination = 5;
        $scope.maxSize = 10;
        //$scope.bigTotalItems = 175;
        //$scope.bigCurrentPage = 1;
        //VARIABLES

        $rootScope.me.ddd = "11";

        $scope.ddds = [];
        $scope.shares = [];
        $scope.shared = {};
        $scope.sheets = [];
        $scope.submitted = false;
        $scope.actions.listShares(10);
    }
    $scope.actions = {
        listShares: function(page){
            YII2Request.request('share', "GET", null, $rootScope.token, function(resp){
                console.log("totalCount");
                if(resp.status == 200){
                    $scope.shares = resp.data;
                    console.log("shares", shares);
                }
            });
        }
    };
    //
    $scope.methods = {

    };
    $scope.init();
})