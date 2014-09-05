'use strict';

/* Controllers */

angular.module('puredemocracyapp.controllers', [])
        .controller('controladorplantilla', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });
            }])
        .controller('controladorchatgrupo', ['$scope', '$http', '$interval', function($scope, $http, $interval) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                $scope.refreshchat = function() {
                    allamar($http, 'getChatGrupoNuevo', [$scope.idgrupo, $scope.ultima_actualizacion], function(res) {
                        //alert(JSON.stringify(res));
                        //Actualizar fecha de ultima actualización
                        $scope.ultima_actualizacion = dateToBDD(new Date());
                        //Añadir a los mensajes si hay
                        if (res.resultado.length > 0) {
                            $scope.mensajes = $scope.mensajes.concat(res.resultado);
                        }
                    });
                };

                $scope.init = function(id) {
                    $scope.idgrupo = id;
                    var d = new Date(); // today!
                    var x = 1; // go back 5 days!
                    d.setDate(d.getDate() - x);
                    $scope.ultima_actualizacion = dateToBDD(d);
                    $scope.mensajes = [];
                    $scope.refreshchat();

                    $interval($scope.refreshchat, 10000);
                };

                $scope.sendmsg = function(mensaje) {
                    allamar($http, 'nuevoMensajeChatGrupo', [$scope.idgrupo, mensaje], function(res) {
                        $scope.refreshchat();
                        $scope.nuevo_mensaje.texto = "";
                    });
                };

            }])
        .controller('controladormisgrupos', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                //Cargar los grupos del usuario
                $scope.cargarMisGrupos = function() {
                    allamar($http, 'getGruposDeUsuarioActual', null, function(res) {
                        //alert(JSON.stringify(res));
                        $scope.misGrupos = res.resultado;
                    });
                };

                $scope.cargarMisGrupos();

            }])
        .controller('controladortodosgrupos', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                //Cargar grupos
                $scope.cargarGrupos = function() {
                    allamar($http, 'getGrupos', null, function(res) {
                        //alert(JSON.stringify(res));
                        $scope.grupos = res.resultado;
                    });
                };

                $scope.cargarGrupos();
            }])
        .controller('controladornuevogrupo', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });
            }])
        .controller('controladorperfil', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                $scope.user = {};

                $scope.getUsuarioActual = function() {
                    allamar($http, 'getUsuarioActual', null, function(res) {
                        //alert(JSON.stringify(res));
                        $scope.user = res.resultado[0];
                    });
                };


                $scope.setUsuarioActual = function() {
                    allamar($http, 'setUsuarioActual', [$scope.user], function(res) {

                    });
                };

                $scope.getUsuarioActual();


            }])
        .controller('controladordetallegrupo', ['$scope', '$http', '$interval', '$modal', function($scope, $http, $interval, $modal) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });



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
                    cargarGrupos($scope, $http);
                    $scope.cargarVotaciones();

                    //Llamamos una vez para inicializar
                    $scope.actualizartiempotranscurrido();

                    //Activamos el interval para actualizar los valores de las votaciones
                    $interval($scope.actualizartiempotranscurrido, 1000);
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

                $scope.addSuperGrupo = function(parametro) {


                    if (parametro.idgrupo == null) {
                        //Si no trae parámetro idgrupo es que es un grupo nuevo
                        //alert("Agregar un nuevo grupo llamado " + parametro);

                        allamar($http, 'addNuevoSuperGrupo', [$scope.id, parametro], function(res) {
                            $scope.cargarsupergrupos();
                        });

                    } else {
                        //Sino relacionamos los grupos existentes
                        //alert("Agregar un grupo existente, con id " + parametro.idgrupo);

                        allamar($http, 'hacerSuperGrupo', [$scope.id, parametro.idgrupo], function(res) {
                            $scope.cargarsupergrupos();
                        });

                    }

                    //Recargamos los grupos
                    cargarGrupos($scope, $http);

                };

                $scope.cargarVotaciones = function() {
                    allamar($http, 'getVotacionesSNDDeGrupoParaUsuarioActual', [$scope.id], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.votaciones = res.resultado;
                    });
                };

                $scope.addPregunta = function(enunciado) {
                    allamar($http, 'crearDecision', [$scope.id, enunciado], function(res) {
                        $scope.cargarVotaciones();
                    });

                };

                $scope.votar = function(idvotacion, valor) {

                    //Si el voto es "Depende" pedimos un enunciado que condicione el sentido voto
                    if (false) {//valor == 2) {
                        introducirEnunciado($modal, function(resultado) {
                            $scope.votarDepende(idvotacion, resultado);
                        }, function() {
                            alert("Se cancela el enunciado");
                        }, $scope.id);
                    } else {
                        allamar($http, 'emitirVoto', [idvotacion, valor], function(res) {
                            //TODO Recargar la información sobre la votación en concreto
                            //De momento recargamos todas las votaciones
                            $scope.cargarVotaciones();
                        });
                    }
                };

                $scope.votarDepende = function(idvotacion, enunciado) {
                    allamar($http, 'votarDepende', [idvotacion, enunciado], function(res) {
                        alert(JSON.stringify(res));
                    });
                };



                $scope.actualizartiempotranscurrido = function() {

                    //Recorremos las votaciones y actualizamos el tiempo transcurrido desde que inició y hasta que le toque otro checktime  
                    var ahora = new Date().getTime();
                    for (var nvotacion in $scope.votaciones) {
                        var votacion = $scope.votaciones[nvotacion];
                        //Obtenemos las fechas
                        var f_ini = new Date(votacion.timein).getTime();
                        var f_fin = new Date(votacion.checktime).getTime();

                        //Calculamos los valores máximo y actual
                        var max = f_fin - f_ini;
                        var actual = ahora - f_ini;

                        //Calculamos el porcentaje completado
                        var transcurrido = actual / max;

                        //Anotamos el valor
                        //Si está entre 0 y 1
                        if (transcurrido < 0) {
                            transcurrido = 0;
                        } else if (transcurrido > 1) {
                            transcurrido = 1;
                        }
                        votacion.transcurrido = transcurrido;
                    }

                };

                $scope.activo = function() {

                    //Comprobamos si el grupo debería estar activo para este usuario
                    var respuesta = false;

                    //Si ya se han cargado los datos del grupo vamos
                    if ($scope.grupo != null) {

                        //Está activo si tiene 3 miembros o más y el usuario es uno de ellos
                        if (//$scope.grupo.nmiembros >= 3 && 
                                ($scope.grupo.es_miembro == 1
                                        || $scope.grupo.es_nato == 1)) {
                            respuesta = true;
                        }
                    }
                    return respuesta;
                };
            }])
        .controller('controladorgrupos', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });


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
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

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
                    if (!res.resultado) {
                        redirect("principal.php");
                    }
                });
            }])
        .controller('controladorindex', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login o a principal
                //Si no tenemos login local miramos en facebook y redirigimos según
                checkLogin($http, function() {
                    redirect("principal.php");
                }, function() {
                    redirect("login.php");
                });

            }])
        .controller('controladorprincipal', ['$scope', '$http', '$interval', function($scope, $http, $interval) {


                $scope.cargarVotaciones = function() {
                    //Cargamos las votaciones del usuario actual
                    allamar($http, 'getVotacionesSNDPendientesDeUsuarioActualComoRepresentante', null, function(res) {
                        //alert(JSON.stringify(res)); 
                        $scope.votaciones = res.resultado;
                    });
                };

                $scope.actualizartiempotranscurrido = function() {

                    //Recorremos las votaciones y actualizamos el tiempo transcurrido desde que inició y hasta que le toque otro checktime  
                    var ahora = new Date().getTime();
                    for (var nvotacion in $scope.votaciones) {
                        var votacion = $scope.votaciones[nvotacion];
                        //Obtenemos las fechas
                        var f_ini = new Date(votacion.timein).getTime();
                        var f_fin = new Date(votacion.checktime).getTime();

                        //Calculamos los valores máximo y actual
                        var max = f_fin - f_ini;
                        var actual = ahora - f_ini;

                        //Calculamos el porcentaje completado
                        var transcurrido = actual / max;

                        //Anotamos el valor
                        //Si está entre 0 y 1
                        if (transcurrido < 0) {
                            transcurrido = 0;
                        } else if (transcurrido > 1) {
                            transcurrido = 1;
                        }
                        votacion.transcurrido = transcurrido;
                    }

                };

                $scope.votar = function(idvotacion, valor) {

                    allamar($http, 'emitirVoto', [idvotacion, valor], function(res) {
                        //TODO Recargar la información sobre la votación en concreto
                        //De momento recargamos todas las votaciones
                        $scope.cargarVotaciones();
                    });
                };

                $scope.init = function() {
                    //Cargamos los datos del usuario actual
                    allamar($http, 'getSession', null, function(res) {
                        $scope.session = res.resultado;
                    });

                    $scope.cargarVotaciones();
                    //Llamamos una vez para inicializar
                    $scope.actualizartiempotranscurrido();

                    //Activamos el interval para actualizar los valores de las votaciones
                    $interval($scope.actualizartiempotranscurrido, 1000);
                };

                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, $scope.init, function() {
                    redirect("login.php");
                });
            }])
        .controller('controladorlogin', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, function() {
                    redirect('principal.php');
                }, nop);

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

function cargarEnunciadosDeGrupo(servicioScope, servicioHttp, idgrupo) {
    allamar(servicioHttp, 'getEnunciadosDeGrupo', [idgrupo], function(res) {
        servicioScope.enunciados = res.resultado;
    });
}
;

function introducirEnunciado(modal, success, error, idgrupo) {
    var modalInstance = modal.open({
        templateUrl: 'introducirenunciado.php',
        controller: controladorintroducirenunciado,
        resolve: {idgrupo: function() {
                return idgrupo;
            }}
    });
    modalInstance.result.then(function(resultado) {
        success(resultado);
    }, function() {
        error();
    });
}
;

function controladorintroducirenunciado($scope, $http, $modalInstance, idgrupo) {

    $scope.idgrupo = idgrupo;

    $scope.grupos_y = [{"enunciados": [{"nombre": ""}]}];

    cargarEnunciadosDeGrupo($scope, $http, idgrupo);

    $scope.addGrupoY = function() {
        $scope.grupos_y.push({"enunciados": [{"nombre": ""}]});
    };

    $scope.addEnunciado = function(grupo_y) {
        //alert(JSON.stringify(grupo_y));
        grupo_y.enunciados.push({'nombre': ''});
    };

    $scope.ok = function() {
        $modalInstance.close($scope.grupos_y);
    };

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    };
}
;

function pruebafuncion() {
    alert("Prueba");
}