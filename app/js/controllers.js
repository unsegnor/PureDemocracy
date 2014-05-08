'use strict';

/* Controllers */

angular.module('puredemocracyapp.controllers', [])
        .controller('controladorplantilla', ['$scope', '$http', function($scope, $http) {
                alert("Bienvenido a la Plantilla!");
            }])
        .controller('controladorlogout', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                allamar($http, 'doLogout', null, function(res) {
                    if (res.resultado) {
                        redirect("login.php");
                    }else{
                        redirect("principal.php");
                    }
                });
            }])
        .controller('controladorindex', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login o a principal
                allamar($http, 'checkLogin', null, function(res) {
                    //alert(JSON.stringify(res));
                    if (res.resultado) {
                        redirect("vistas/principal.php");
                    } else {
                        redirect("vistas/login.php");
                    }
                });

            }])
        .controller('controladorprincipal', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                allamar($http, 'checkLogin', null, function(res) {
                    if (!res.resultado) {
                        redirect("login.php");
                    }
                });

            }])
        .controller('controladorlogin', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y a principal
                allamar($http, 'checkLogin', null, function(res) {
                    //alert(JSON.stringify(res));
                    if (res.resultado) {
                        redirect("principal.php");
                    }
                });

                //Inicializamos la estructura login
                $scope.login = {};
                $scope.newuser = {};

                //Definimos función para logear
                $scope.dologin = function(id, pass) {

                    //Intentamos hacer login en el servidor
                    allamar($http, 'doLogin', [id, pass], function(res) {
                        if (res.resultado) {
                            redirect("principal.php");
                        } else {
                            //Si no ha funcionado nos quedamos (se mostrará el mensaje de error)
                        }
                    });
                };

                $scope.registernew = function(nombre, apellidos, email, pass) {

                    allamar($http, 'registrarUsuario', [nombre, apellidos, email, pass], function(res) {

                        if (!res.hayerror) {

                            //Si no hay error logueamos
                            $scope.dologin(email, pass);
                        }

                    });

                };
            }])
        ;