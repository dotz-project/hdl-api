var ClaroAdm = angular.module("ClaroAdm", ['ui.router', 'angularMoment', 'ui.toggle', 'ngFileUpload', 'daterangepicker', 'ngAreas','ngAnimate', 'ngTouch', 'ngMask', 'ui.calendar', 'ui.bootstrap', 'ui.bootstrap.carousel','oc.lazyLoad', 'yii2-request'])
.config(['$qProvider','$stateProvider','$urlRouterProvider', function($qProvider, $stateProvider, $urlRouterProvider){
     // $qProvider.errorOnUnhandledRejections(false);
     $stateProvider
        .state('login', {
            url:'/login',
            cache: false,
            views:{
                'main':{
                    templateUrl : "./ClaroAdm/login/login.html",
                    controller: 'LoginController'
                }
            },
            resolve: {
                loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            './ClaroAdm/login/LoginController.js' 
                        ]
                    }); // Resolve promise and load before view 
                }]
            }
        })

        .state('newMaterial', {
            url: '/materials/new',
            cache: false,
            views: {
                'header': {
                     templateUrl: "./ClaroAdm/header.html"
                },
                'main': {
                     templateUrl: "./ClaroAdm/materials/newMaterial.html",
                     controller: 'NewMaterialController'
                },
                'footer': {
                     templateUrl: "./ClaroAdm/footer.html"
                }
            },
            resolve: {
                loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                             './ClaroAdm/materials/NewMaterialController.js'
                        ]
                    }); // Resolve promise and load before view 
                }]
            }
        })
       
        .state('imageDetail', {
            url:'/imageDetail',
            cache: false,
            views:{
                'header':{
                    templateUrl : "./ClaroAdm/header.html"
                },
                'main':{
                    templateUrl : "./ClaroAdm/materials/imageDetail.html",
                    controller: 'ImageDetailController'
                },
                'footer':{
                    templateUrl : "./ClaroAdm/footer.html"
                }   
            },
            resolve: {
                loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files:[
                            './ClaroAdm/materials/ImageDetailController.js' 
                        ]
                    });
                }]
            }
        })

        .state('dashboard', {
            url:'/dashboard',
            cache: false,
            views:{
                'header':{
                    templateUrl : "./ClaroAdm/header.html"
                },
                'main':{
                    templateUrl : "./ClaroAdm/dashboard/dashboard.html",
                    controller: 'DashboardController'
                },
                'footer':{
                    templateUrl : "./ClaroAdm/footer.html"
                }   
            },
            resolve: {
                loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            './ClaroAdm/dashboard/DashboardController.js' 
                        ]
                    }); // Resolve promise and load before view 
                }]
            }
        })

        .state('materials', {
            url:'/materials',
            cache: false,
            views:{
                'header':{
                    templateUrl : "./ClaroAdm/header.html"
                },
                'main':{
                    templateUrl : "./ClaroAdm/materials/materials.html",
                    controller: 'MaterialsController'
                },
                'footer':{
                    templateUrl : "./ClaroAdm/footer.html"
                }   
            },
            resolve: {
                loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            './ClaroAdm/materials/MaterialsController.js' 
                        ]
                    }); // Resolve promise and load before view 
                }]
            }
        })
         .state('detailMaterial', {
            url:'/materials/:id',
            cache: false,
            views:{
                'header':{
                    templateUrl : "./ClaroAdm/header.html"
                },
                'main':{
                    templateUrl : "./ClaroAdm/materials/materialDetail.html",
                    controller: 'MaterialDetailController'
                },
                'footer':{
                    templateUrl : "./ClaroAdm/footer.html"
                }   
            },
            resolve: {
                loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            './ClaroAdm/materials/MaterialDetailController.js' 
                        ]
                    }); // Resolve promise and load before view 
                }]
            }
        })
        .state('users', {
            url:'/users',
            cache: false,
            views:{
                'header':{
                    templateUrl : "./ClaroAdm/header.html"
                },
                'main':{
                    templateUrl : "./ClaroAdm/users/users.html",
                    controller: 'UsersController'
                },
                'footer':{
                    templateUrl : "./ClaroAdm/footer.html"
                }   
            },
            resolve: {
                loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            './ClaroAdm/users/UsersController.js' 
                        ]
                    });
                }]
            }
        })
        
        .state('ddd', {
            url:'/ddd',
            cache: false,
            views:{
                'header':{
                    templateUrl : "./ClaroAdm/header.html"
                },
                'main':{
                    templateUrl : "./ClaroAdm/ddd/ddd.html",
                    controller: 'DDDController'
                },
                'footer':{
                    templateUrl : "./ClaroAdm/footer.html"
                }   
            },
            resolve: {
                loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            './ClaroAdm/ddd/DDDController.js' 
                        ]
                    }); // Resolve promise and load before view 
                }]
            }
        });
    $urlRouterProvider.otherwise('/login');
}]);
ClaroAdm.directive("headerTemplate", function(){
  return {
      restrict: 'AE',
      templateUrl: 'ClaroAdm/header.html' 
    };
});
ClaroAdm.directive("footerTemplate", function(){
  return {
      restrict: 'AE',
      templateUrl: 'ClaroAdm/footer.html' 
    };
});
ClaroAdm.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}]);