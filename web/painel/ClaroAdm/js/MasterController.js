ClaroAdm.controller("MasterController", function($scope, $rootScope, $state){
 	var urlNav = window.location.href;
    if(urlNav.indexOf("localhost") > -1){
        $rootScope.urlRoute = "http://localhost:8000/apiv1/";
        localStorage.setItem('urlApi', $rootScope.urlRoute);
    }else{
		$rootScope.urlRoute = "http://api.cms-claro.homolog.mkmtecnologia.com.br/apiv1/";
        localStorage.setItem('urlApi', $rootScope.urlRoute);
    }
    $rootScope.token =  localStorage.getItem('token');
    $rootScope.me = {};
    $rootScope.logout = function(){
    	localStorage.removeItem("token");
    	$state.go("login");
    }
});
ClaroAdm.factory('focus', function($timeout, $window) {
	return function(name) {
	    $timeout(function() {
	    	console.log("name",name);
		    var element = window.document.getElementsByName(name)[0];
		    console.log(element);
		    if(element){
		    	element.focus();
		    }
		    
	    });
	};
})
ClaroAdm.factory('confirm', function(){
	return function(params,callback){
		if(!params.title){
			params.title = "Atenção"
		}
		if(!params.text){
			params.text = "Confirma?"
		}
		if(!params.type){
			params.type = "warning"
		}
		if(!params.showCancelshowCancelButton){
			params.showCancelButton = false
		}
		if(!params.confirmButtonText){
			params.confirmButtonText = "Sim"
		}
		swal(
			{
			    title: params.title,
			    text: params.text,
			    type: params.type,
			    showCancelButton: params.showCancelButton,
			    confirmButtonColor: "#DD6B55",
			    confirmButtonText: params.confirmButtonText,
			    closeOnConfirm: true
			},
			function(isConfirm){
				if(typeof callback == 'function'){
					callback();
				}
				
			}
		);
	}
})
ClaroAdm.factory('toBase64', function() {
	return function getBase64(file, callback) {
		var reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = function () {
			callback(reader.result);
		};
		reader.onerror = function (error) {
			console.log('Error: ', error);
		};
	}
})
