/**
 * Created by xianqiu on 7/03/2016.
 */
angular.module('app',[])
    .value('realname','ZACK')//can change
    .constant('http','google.com')//can't change
    .factory('data',function(){//most common way in development
        return{
            msg:'How are you',
            setMsg:function(){
                this.msg='I am not good';
            }
        }
    })
    .service('user',function(){
        this.firstname='Chuck';
        this.lastname='Norris';
        this.getName=function(){
            return this.firstname+this.lastname;
        }
    })
    .controller('MyCtrl',function($scope,realname,http,data,user){
        $scope.msg='HELLO';
        $scope.realname=realname;//call value realname
        $scope.http=http;//call constant http
        $scope.data=data;
        data.setMsg();
        $scope.fullname=user.getName();
    })