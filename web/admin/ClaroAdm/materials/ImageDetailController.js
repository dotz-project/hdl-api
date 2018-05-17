angular.module("ClaroAdm").controller("ImageDetailController", function ($scope,$stateParams, confirm,$timeout, focus, confirm, $rootScope,$state, YII2Request){
    $scope.init = function(resp){
        $scope.editArea = false;
        $scope.fields = [];
        $scope.area = {};
        $scope.submitted = false;
        $scope.actions.setAreas();
        console.log("material detalhe", $rootScope.sheetSelect)
    };
    $scope.actions = {
        setAreas:function(){
            if (typeof $rootScope.sheetSelect.image_map != "object"){
                $rootScope.sheetSelect.image_map = JSON.parse($rootScope.sheetSelect.image_map);
                if ($rootScope.sheetSelect.image_map[0].link){
                    $rootScope.sheetSelect.image_map = [];
                }
            }
            $scope.fields = $rootScope.sheetSelect.image_map;
            console.log("$scope.fields set areas", $scope.fields);
            $("#result-image").html("");
            $timeout(function() {
                try{
                    for(var k in $scope.fields){
                        $scope.fields[k].areaid = k;
                        if ($scope.fields.length > 0){
                            /*console.log("TEm area pra add");
                            var area = $("<area>");
                            console.log("area que será add",$scope.fields[k]);
                            area.attr({
                                "shape":"rect",
                                "coords":$scope.fields[k].x+","+$scope.fields[k].y+","+$scope.fields[k].width+","+$scope.fields[k].height,
                                "href":$scope.fields[k].url,
                                "title":$scope.fields[k].name,
                                "style":"cursor:pointer; border:2px solid blue"
                            });
                            console.log(area);
                            $(area).appendTo($('#map-area'));
                            $("#map-area").append(area);*/
                            var area = $("<a/>");
                            console.log("area que será add", $scope.fields[k]);
                            area.attr({
                                "href": $scope.fields[k].url,
                                "title": $scope.fields[k].name
                            });
                            area.css({
                                width: $scope.fields[k].width,
                                height: $scope.fields[k].height,
                                position:"absolute",
                                top: $scope.fields[k].y,
                                left: $scope.fields[k].x,
                                cursor:"pointer",
                                border: "2px solid blue"
                            })
                            console.log(area);
                            $('#result-image').append(area);
                        }
                    }
                }catch(e){}
            },1000)
        },
        editImage:function() {
            console.log("$scope.fields", $scope.fields);
            $rootScope.sheetSelect.image_map = $scope.fields;
            if (typeof $rootScope.sheetSelect.image_map == "object"){
                console.log("é objecto");
                $rootScope.sheetSelect.image_map = JSON.stringify($rootScope.sheetSelect.image_map);
            }
            YII2Request.request("handout-sheets/" + $rootScope.sheetSelect.handout_id, "PATCH", $rootScope.sheetSelect, $rootScope.token, function (resp) {
                if (resp.status == 200) {
                    $rootScope.sheetSelect = resp.data;
                    $scope.actions.setAreas();
                }
            });
        }
    };
    //

    $scope.methods = {
        editArea:function(){
            $scope.fields = $rootScope.sheetSelect.image_map;
            console.log("$scope.fields edit area", $scope.fields);
            $scope.editArea = true;
        },
        saveArea:function(){
            $scope.editArea = false;
            $scope.actions.editImage();
        },
        onAddArea : function(ev, boxId, areas, area) {
            $scope.fields.push(boxId);
           // $rootScope.sheetSelect.image_map = areas;
            $scope.area = boxId;
            $scope.area.name = "";
            $scope.area.url = "";
            $("#modalArea").modal("show");
            console.log("area added", areas, $scope.fields, area, ev, boxId);
        },
        onRemoveArea : function(ev, boxId, areas, area) {
            for (var t in $scope.fields) {
                if ($scope.fields[t].internal_id == boxId.internal_id) {
                    $scope.fields.splice(t,1);
                }
            }
        },
        onChangeArea : function(ev, boxId, areas, area) {
            for (var t in $scope.fields){
                if ($scope.fields[t].internal_id == boxId.internal_id){
                    $scope.fields[t] = boxId;
                }
            }
            console.log("area que alterou",boxId, areas);
            console.log("areas mudando", $scope.fields);
        },
        confirmArea : function(area){
            $scope.submitted = true;
            if ($scope.newArea.$valid){
                area.internal_id = Math.random();
                $("#modalArea").modal("hide");
                console.log(area, $scope.fields);
                for (var i in $scope.fields){
                    if($scope.fields[i].areaid == area.areaid){
                        $scope.fields[i] = area;
                    }
                }
                console.log("confirm alteração", area, $scope.fields);
            }
        }
    };
    $scope.init();
})