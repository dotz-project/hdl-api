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

        $rootScope.me.ddd = "11";

        $scope.ddds = [];
        $scope.materials = [];
        $scope.material = {};
        $scope.sheets = [];
        $scope.shared = {};
        $scope.submitted = false;
        $scope.actions.listMaterials(10);
    }
    $scope.actions = {
        listMaterials: function(page){
            YII2Request.request('handouts/all/?ddd='+$rootScope.me.ddd+'per-page='+page+'&page=1', "GET", null, $rootScope.token, function(resp){
                console.log("totalCount");
                if(resp.status == 200){
                    $scope.materials = resp.data;
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
        pageChanged: function(page){
            $scope.currentPage = page;
            $scope.actions.listMaterials(page);
        },
        modalShare: function(material){
            $scope.shared = {};
            $scope.shared.handout_id = material.id;
            $("modalShare").modal("show");
        },
        confirmShare: function(shared){
            shared.link = $rootScope.me.ddd+"/"+shared.handout_id;
            YII2Request.request('share/send', "POST", shared, $rootScope.token, function(resp){
                console.log("totalCount");
                if(resp.status == 201){
                    swal("material compartilhado com sucesso");
                }
            });
        }
    };
    $scope.init();
})