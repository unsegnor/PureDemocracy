'use strict';

/* Controllers */

angular.module('puredemocracyapp.controllers', [])
        .controller('controladorplantilla', ['$scope', '$http', function($scope, $http) {
                alert("Bienvenido a la Plantilla!");
            }])
        .controller('controladorindex', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login o a principal
                allamar($http, 'checkLogin', null, function(res){
                   //alert(JSON.stringify(res));
                    if(res.resultado){
                        redirect("vistas/principal.php");
                    }else{
                        redirect("vistas/login.php");
                    }
                    
                });

            }])
        .controller('controladorprincipal', ['$scope', '$http', function($scope, $http) {


            }])
        .controller('controladorlogin', ['$scope', '$http', function($scope, $http) {


            }])
        ;