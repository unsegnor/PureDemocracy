'use strict';

/* Controllers */

angular.module('puredemocracyapp.controllers', [])
        .controller('controladorplantilla', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http);
            }])
        .controller('controladordetallegrupo', ['$scope', '$http', function($scope, $http) {
                checkLogin($http);

                //Cargar la información del grupo
                $scope.cargarGrupo = function(id) {

                    allamar($http, 'getDetalleDeGrupo', [id], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.grupo = res.resultado;
                    });

                };

                $scope.init = function(id) {
                    $scope.id = id;
                    $scope.cargarGrupo(id);
                    $scope.cargarsubgrupos();
                    $scope.cargarsupergrupos();
                    cargarGrupos($scope,$http);
                    $scope.cargarVotaciones();
                };

                $scope.solicitarIngreso = function() {

                    allamar($http, 'solicitarIngresoEnGrupo', [$scope.id], function(res) {
                        $scope.cargarGrupo($scope.id);
                    });

                };

                $scope.solicitarBaja = function() {

                    if (confirm("Se dispone a darse de baja en el grupo actual.")) {
                        allamar($http, 'solicitarBaja', [$scope.id], function(res) {
                            $scope.cargarGrupo($scope.id);
                        });
                    }

                };

                $scope.cargarsubgrupos = function() {

                    allamar($http, 'getSubGrupos', [$scope.id, 1], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.subgrupos = res.resultado;
                    });

                };

                $scope.cargarsupergrupos = function() {

                    allamar($http, 'getSuperGrupos', [$scope.id, 1], function(res) {
                        $scope.supergrupos = res.resultado;
                    });
                };

                $scope.addSubGrupo = function(nombre) {

                    allamar($http, 'addSubGrupo', [$scope.id, nombre], function(res) {
                        $scope.cargarsubgrupos();
                    });
                };
                
                $scope.addSuperGrupo = function(parametro){
                    
                    
                    if(parametro.idgrupo == null){
                        //Si no trae parámetro idgrupo es que es un grupo nuevo
                        //alert("Agregar un nuevo grupo llamado " + parametro);
                        
                        allamar($http, 'addNuevoSuperGrupo', [$scope.id, parametro], function(res){
                           $scope.cargarsupergrupos(); 
                        });
                        
                    }else{
                        //Sino relacionamos los grupos existentes
                        //alert("Agregar un grupo existente, con id " + parametro.idgrupo);
                        
                        allamar($http, 'hacerSuperGrupo', [$scope.id, parametro.idgrupo], function(res){
                           $scope.cargarsupergrupos();
                        });
                        
                    }
                    
                    //Recargamos los grupos
                    cargarGrupos($scope, $http);
                    
                };
                
                $scope.cargarVotaciones = function(){
                  
                    allamar($http, 'getVotacionesSNDDeGrupo', [$scope.id], function(res){
                       //alert(JSON.stringify(res));
                       $scope.votaciones = res.resultado;
                    });
                };
                
                $scope.addPregunta = function(enunciado){
                    allamar($http, 'addPregunta', [$scope.id, enunciado], function(res){
                       $scope.cargarVotaciones() ;
                    });
                    
                };
                
                $scope.votar = function(idvotacion, valor){
                  allamar($http, 'emitirVoto', [idvotacion, valor], function(res){
                     //TODO Recargar la información sobre la votación en concreto
                     
                  });  
                };
            }])
        .controller('controladorgrupos', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http);

                //Cargar grupos
                $scope.cargarGrupos = function() {

                    allamar($http, 'getGrupos', null, function(res) {
                        //alert(JSON.stringify(res));
                        $scope.grupos = res.resultado;

                    });

                };

                //Cargar los grupos del usuario
                $scope.cargarMisGrupos = function() {

                    allamar($http, 'getGruposDeUsuarioActual', null, function(res) {
                        //alert(JSON.stringify(res));
                        $scope.misGrupos = res.resultado;
                    });
                };

                $scope.cargarMisGrupos();

                $scope.cargarGrupos();

                //Añadir grupos
                $scope.addGrupo = function(nombre) {

                    allamar($http, 'addGrupo', [nombre], function(res) {

                        $scope.cargarGrupos();

                    });

                };



            }])
        .controller('controladorobjetivos', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http);

                $scope.getObjetivos = function() {
                    allamar($http, 'getObjetivosConInfo', null, function(res) {
                        //alert(JSON.stringify(res));
                        $scope.objetivos = res.resultado;
                    });
                };

                //Traemos las propuestas
                $scope.getObjetivos();

                $scope.addObjetivo = function(objetivo) {

                    allamar($http, 'addObjetivo', [objetivo.descripcion], function(res) {
                        //Recargar las propuestas
                        $scope.getObjetivos();
                    });

                };

                $scope.onSelect = function(item, algo, label) {

                    var idobjetivo = item.idobjetivo;

                    //Redireccionamos al detalle de la propuesta seleccionada
                    redirect("detalleobjetivo.php?id=" + idobjetivo);
                };

                $scope.votar = function(objetivo, valor) {
                    //alert("Votando");
                    allamar($http, 'votarAprobacionObjetivo', [objetivo.idobjetivo, valor], function(res) {
                        //Recargar las propuestas
                        $scope.getObjetivos();
                    });
                };
            }])
        .controller('controladorlogout', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                allamar($http, 'doLogout', null, function(res) {
                    if (res.resultado) {
                        redirect("login.php");
                    } else {
                        redirect("principal.php");
                    }
                });
            }])
        .controller('controladorindex', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login o a principal
                allamar($http, 'checkLogin', null, function(res) {
                    //alert(JSON.stringify(res));
                    if (res.resultado) {
                        redirect("principal.php");
                    } else {
                        redirect("login.php");
                    }
                });

            }])
        .controller('controladorprincipal', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http);

                //Cargamos los datos del usuario actual
                allamar($http, 'getSession', null, function(res) {
                    $scope.session = res.resultado;
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
                        //alert(JSON.stringify(res));
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


function cargarGrupos(servicioScope, servicioHttp) {
    //Cargar grupos

        allamar(servicioHttp, 'getGrupos', null, function(res) {
            //alert(JSON.stringify(res));
            servicioScope.grupos = res.resultado;

        });

   
}
;