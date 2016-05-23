/**
 * Created by xianqiu on 7/03/2016.
 */
angular.module("app",[])
.controller("MyCtrl", function($scope){
    $scope.msg="ddd";
    $scope.reverse=function(){
        return $scope.msg.split("").reverse().join("");
    }
})