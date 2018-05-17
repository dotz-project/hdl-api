angular.module("yii2-request",[]).service("YII2Request", ["$http",function($http) {
    var service = this;
    service.req = {
        headers: {
            'Content-Type': 'application/json'
        }
    };
    service.request = function (url,method,params,token,callback) {
        service.req.url =  localStorage.getItem('urlApi')+url;
        service.req.method = method;
        console.log("token yii", token)
        if(params != null){
            console.log("params")
            service.req.data = params;
        }
        if(token != null){
            service.req.headers["Authorization"] = token;
        }
        $http(service.req).then(function (data) {
         
            if (typeof callback == 'function') {
                callback(data);
            }
        }, function(data){
            console.log(data);
            if (typeof callback == 'function') {
                callback(data);
            }
        });
    };
}])