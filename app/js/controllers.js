'use strict';

/* Controllers */

angular.module('puredemocracyapp.controllers', [])
        .controller('controladorplantilla', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                setMenu([
                    {'nombre': 'Anterior', 'enlace': '#'}
                    , {'nombre': 'Plantilla', 'enlace': '#'}
                ]);
            }])
        .controller('controladornuevogrupo', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                $scope.nuevogrupo = {};
                $scope.nuevogrupo.nombre = "";
                $scope.nuevogrupo.descripcion = "";

                setMenu([
                    {'nombre': 'Grupos', 'enlace': 'todosgrupos.php'}
                    , {'nombre': 'Nuevo', 'enlace': '#'}
                ]);

                $scope.creargrupo = function() {
                    allamar($http, 'addGrupo', [$scope.nuevogrupo.nombre, $scope.nuevogrupo.descripcion], function(res) {
                        //Si no hay error redirigimos a la página del neuvo grupo
                        if (!res.hayerror) {
                            var idgrupo = res.resultado;
                            redirect("infogrupo.php?id=" + idgrupo);
                        }
                    });
                };
            }])
        .controller('controladornuevosubgrupo', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                $scope.cargardatosdegrupo = function(idgrupo) {
                    allamar($http, 'getInfoDeGrupo', [idgrupo], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.infogrupo = res.resultado;

                        setMenu([
                            {'nombre': 'Grupos', 'enlace': 'todosgrupos.php'}
                            , {'nombre': $scope.infogrupo.nombre, 'enlace': 'infogrupo.php?id=' + idgrupo}
                            , {'nombre': 'Nuevo subgrupo', 'enlace': '#'}
                        ]);
                    });
                };

                $scope.nuevogrupo = {};
                $scope.nuevogrupo.nombre = "";
                $scope.nuevogrupo.descripcion = "";

                $scope.creargrupo = function() {
                    allamar($http, 'addSubGrupo', [$scope.idgrupo, $scope.nuevogrupo.nombre, $scope.nuevogrupo.descripcion], function(res) {
                        //Si no hay error redirigimos a la página del neuvo grupo
                        if (!res.hayerror) {
                            var idgrupo = res.resultado;
                            redirect("infogrupo.php?id=" + idgrupo);
                        }
                    });
                };

                $scope.init = function(id) {
                    $scope.idgrupo = id;
                    $scope.cargardatosdegrupo(id);
                };
            }])
        .controller('controladorinfogrupo', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                $scope.cargardatosdegrupo = function(idgrupo) {
                    allamar($http, 'getInfoDeGrupo', [idgrupo], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.infogrupo = res.resultado;

                        setMenu([
                            {'nombre': 'Grupos', 'enlace': 'todosgrupos.php'}
                            , {'nombre': $scope.infogrupo.nombre, 'enlace': 'infogrupo.php?id=' + idgrupo}
                        ]);
                    });
                };

                $scope.cargarinfomiembros = function(idgrupo) {
                    allamar($http, 'getInfoMiembros', [idgrupo], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.miembros = res.resultado;
                    });
                };

                $scope.cargarinformaciondemiembroactual = function(idgrupo) {
                    allamar($http, 'getInfoMiembro', [idgrupo], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.miembroactual = res.resultado;
                    });
                };

                $scope.ingresarengrupo = function() {

                    allamar($http, 'ingresarEnGrupo', [$scope.idgrupo], function(res) {
                        //alert(JSON.stringify(res));
                        //Actualizamos la información del usuario actual
                        $scope.cargarinformaciondemiembroactual($scope.idgrupo);
                        $scope.cargarinfomiembros($scope.idgrupo);
                    });

                };

                $scope.seguirgrupo = function() {

                    allamar($http, 'seguirGrupo', [$scope.idgrupo], function(res) {
                        $scope.cargarinformaciondemiembroactual($scope.idgrupo);
                    });

                };

                $scope.solicitarbaja = function() {

                    allamar($http, 'solicitarBaja', [$scope.idgrupo], function(res) {
                        $scope.cargarinformaciondemiembroactual($scope.idgrupo);
                        $scope.cargarinfomiembros($scope.idgrupo);
                    });
                };

                $scope.cargarsupergrupos = function() {
                    allamar($http, 'getSuperGrupos', [$scope.idgrupo, 1], function(res) {
                        $scope.supergrupos = res.resultado;
                    });
                };

                $scope.cargarsubgrupos = function() {
                    allamar($http, 'getSubGrupos', [$scope.idgrupo, 1], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.subgrupos = res.resultado;
                    });
                };

                $scope.init = function(id) {

                    $scope.idgrupo = id;

                    //Cargamos los datos del grupo
                    $scope.cargardatosdegrupo($scope.idgrupo);
                    //Cargamos los miembros del grupo
                    $scope.cargarinfomiembros($scope.idgrupo);
                    //Cargar información del miembro actual
                    $scope.cargarinformaciondemiembroactual($scope.idgrupo);
                    //Cargar supergrupos
                    $scope.cargarsupergrupos();
                    //Cargar subgrupos
                    $scope.cargarsubgrupos();
                };


            }])
        .controller('controladornuevavotacion', ['$scope', '$http', function($scope, $http) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                $scope.cargardatosdegrupo = function(idgrupo) {
                    allamar($http, 'getInfoDeGrupo', [idgrupo], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.infogrupo = res.resultado;

                        setMenu([
                            {'nombre': 'Grupos', 'enlace': 'todosgrupos.php'}
                            , {'nombre': $scope.infogrupo.nombre, 'enlace': 'infogrupo.php?id=' + idgrupo}
                            , {'nombre': 'Nueva votación', 'enlace': '#'}
                        ]);

                        $scope.nuevaaccion.descripcion = $scope.infogrupo.descripcion;
                    });
                };

                $scope.cargartiposdeacciones = function() {
                    allamar($http, 'getTiposAcciones', null, function(res) {
                        $scope.tiposacciones = res.resultado;
                        //Asignamos el valor por defecto después de cargarlas
                        $scope.nuevaaccion.tipo = $scope.tiposacciones[0];
                    });
                };

                $scope.cargargrupos = function() {
                    allamar($http, 'getGrupos', null, function(res) {
                        $scope.grupos = res.resultado;
                    });
                };

                $scope.cargarsupergrupos = function() {
                    allamar($http, 'getSuperGrupos', [$scope.idgrupo, 1], function(res) {
                        $scope.supergrupos = res.resultado;
                    });
                };

                $scope.cargarVotaciones = function() {
                    allamar($http, 'getVotacionesSNDDeGrupo', [$scope.idgrupo], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.votaciones = res.resultado;
                    });
                };

                $scope.init = function(id) {

                    //Valores por defecto
                    $scope.nuevavotacion = {};
                    $scope.nuevavotacion.tipo = 0;
                    $scope.nuevavotacion.acciones = [];
                    $scope.nuevaaccion = {};

                    //Cargar datos del grupo
                    $scope.idgrupo = id;
                    $scope.cargardatosdegrupo($scope.idgrupo);

                    //Cargamos los grupos
                    $scope.cargargrupos();

                    //Cargar supergrupos
                    $scope.cargarsupergrupos();

                    //Obtenemos los tipos de acciones
                    $scope.cargartiposdeacciones();

                    //Cargamos las votaciones
                    $scope.cargarVotaciones();

                };

                $scope.addAccion = function() {

                    //Comprobamos el tipo de acción
                    var accion = {};
                    accion.id = $scope.nuevaaccion.tipo.idaccionsys;

                    if (accion.id == 1) {
                        //El parámetro es el id del grupo
                        accion.parametro = $scope.nuevaaccion.grupo_in.idgrupo;
                        //Y el texto que vamos a mostrar
                        accion.descripcion = "Unirse al grupo '" + $scope.nuevaaccion.grupo_in.nombre + "'";
                    } else if (accion.id == 2) {
                        //El parámetro es el id del grupo
                        accion.parametro = $scope.nuevaaccion.grupo_out.idgrupo;
                        //Y el texto que vamos a mostrar
                        accion.descripcion = "Abandonar el grupo '" + $scope.nuevaaccion.grupo_out.nombre + "'";
                    } else if (accion.id == 3) {
                        //El parámetro es el id del grupo
                        accion.parametro = $scope.nuevaaccion.nuevadescripcion;
                        //Y el texto que vamos a mostrar
                        accion.descripcion = "Establecer la descripción del grupo como '" + $scope.nuevaaccion.nuevadescripcion + "'";
                    } else if (accion.id == 4) {
                        //El parámetro es el id del grupo
                        accion.parametro = $scope.nuevaaccion.nombrevariable + ";" + $scope.nuevaaccion.valorinicialvariable;
                        //Y el texto que vamos a mostrar
                        accion.descripcion = "Crear la variable de grupo '" + $scope.nuevaaccion.nombrevariable + "' con el valor '" + $scope.nuevaaccion.valorinicialvariable;
                    } else if (accion.id == 5) {
                        //El parámetro es el id del grupo
                        accion.parametro = $scope.nuevaaccion.nombrevariableamodificar;
                        //Y el texto que vamos a mostrar
                        accion.descripcion = "Establecer valor de '" + $scope.nuevaaccion.nombrevariableamodificar + "' a '" + $scope.nuevaaccion.valorvariable + "'";
                    } else if (accion.id == 6) {
                        //El parámetro es el id del grupo
                        accion.parametro = $scope.nuevaaccion.regla;
                        //Y el texto que vamos a mostrar
                        accion.descripcion = "Crear la regla '" + $scope.nuevaaccion.regla + "'";
                    } else if (accion.id == 7) {
                        //El parámetro es el id del grupo
                        accion.parametro = $scope.nuevaaccion.votacionainvalidar.idvotacionsnd;
                        //Y el texto que vamos a mostrar
                        accion.descripcion = "invalidar la votación '" + $scope.nuevaaccion.votacionainvalidar.enunciado + "'";
                    }

                    //Añadimos la acción al array
                    $scope.nuevavotacion.acciones.push(accion);

                    //Reiniciamos los campos
                    $scope.nuevaaccion.nuevadescripcion = "";
                    $scope.nuevaaccion.grupo_out = null;
                    $scope.nuevaaccion.grupo_in = null;

                };

                $scope.crearvotacion = function() {
                    //Creamos el objeto que vamos a enviar con las acciones sin la descripción
                    var acciones_a_enviar = [];
                    var acciones = $scope.nuevavotacion.acciones;
                    for (var i = 0; i < acciones.length; i++) {
                        var aux = {};
                        aux.id = acciones[i].id;
                        aux.parametro = acciones[i].parametro;
                        acciones_a_enviar.push(aux);
                    }


                    allamar($http, 'crearDecisionConAcciones', [$scope.idgrupo, $scope.nuevavotacion.enunciado, acciones_a_enviar], function(res) {
                        //alert(JSON.stringify(res));
                        if (!res.hayerror) {
                            redirect("votaciones.php?id=" + $scope.idgrupo);
                        }
                    });
                };

            }])
        .controller('controladorvotaciones', ['$scope', '$http', '$interval', function($scope, $http, $interval) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                $scope.cargarVotaciones = function() {
                    allamar($http, 'getVotacionesSNDDeGrupoParaUsuarioActual', [$scope.id], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.votaciones = res.resultado;
                    });
                };

                $scope.cargardatosdegrupo = function(idgrupo) {
                    allamar($http, 'getInfoDeGrupo', [idgrupo], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.infogrupo = res.resultado;

                        setMenu([
                            {'nombre': 'Grupos', 'enlace': 'todosgrupos.php'}
                            , {'nombre': $scope.infogrupo.nombre, 'enlace': 'infogrupo.php?id=' + idgrupo}
                            , {'nombre': 'Votaciones', 'enlace': '#'}
                        ]);
                    });
                };

                $scope.votar = function(idvotacion, valor) {
                    allamar($http, 'emitirVoto', [idvotacion, valor], function(res) {
                        //TODO Recargar la información sobre la votación en concreto
                        //De momento recargamos todas las votaciones
                        $scope.cargarVotaciones();
                    });
                };

                $scope.actualizartiempotranscurrido = function() {

                    //Recorremos las votaciones y actualizamos el tiempo transcurrido desde que inició y hasta que le toque otro checktime  
                    var ahora = new Date().getTime();
                    for (var nvotacion in $scope.votaciones) {
                        var votacion = $scope.votaciones[nvotacion];
                        if (votacion.finalizada == 0) {
                            //var f_fin = BDDtoUTC(votacion.checktime);
                            var f_fin = new Date(BDDtoUTCformat(votacion.checktime)).getTime();
                            var restante = f_fin - ahora;
                            restante = restante / 1000;
                            if (restante < 0) {
                                votacion.restante = 0;
                                votacion.horas_restantes = pad(0);
                                votacion.minutos_restantes = pad(0);
                                votacion.segundos_restantes = pad(0);
                            } else {
                                votacion.restante = restante; //En segundos
                                votacion.horas_restantes = pad(Math.floor(restante / 3600));
                                votacion.minutos_restantes = pad(Math.floor((restante % 3600) / 60));
                                votacion.segundos_restantes = pad(Math.floor((restante % 3600) % 60));
                            }
                        }
                    }

                };

                $scope.init = function(id) {
                    $scope.id = id;
                    $scope.cargardatosdegrupo($scope.id);
                    $scope.cargarVotaciones();

                    //Llamamos una vez para inicializar
                    $scope.actualizartiempotranscurrido();

                    //Activamos el interval para actualizar los valores de las votaciones
                    $interval($scope.actualizartiempotranscurrido, 1000);
                };

            }])
        .controller('controladorchatgrupo', ['$scope', '$http', '$interval', '$location', '$anchorScroll', function($scope, $http, $interval, $location, $anchorScroll) {
                //Comprobar si el usuario tiene sesión y redirigir a login
                checkLogin($http, nop, function() {
                    redirect("login.php");
                });

                var refrescando = false;

                $scope.gotoBottom = function() {
                    // set the location.hash to the id of
                    // the element you wish to scroll to.
                    $location.hash('bottom');

                    // call $anchorScroll()
                    $anchorScroll();
                };

                $scope.refreshchat = function() {


                    if (!refrescando) {
                        refrescando = true;

                        //Actualizar fecha de ultima actualización
                        //Si hay mensajes
                        if ($scope.mensajes.length > 0) {
                            $scope.ultimo_mensaje_visto = ($scope.mensajes[$scope.mensajes.length - 1]).idchatgrupo;
                        }
                        allamar($http, 'getChatGrupoNuevoID', [$scope.idgrupo, $scope.ultimo_mensaje_visto], function(res) {
                            //alert(JSON.stringify(res));
                            //Añadir a los mensajes si hay
                            if (res.resultado.length > 0) {
                                $scope.mensajes = $scope.mensajes.concat(res.resultado);
                            }
                            //Después de refrescar nos vamos abajo si así queríamos
                            //if(bajar) {$scope.gotoBottom();}
                            refrescando = false;
                        }, function()
                        { //Si la llamada falla liberamos la función
                            refrescando = false;
                        });
                    }
                };

                $scope.cargardatosdegrupo = function(idgrupo) {
                    allamar($http, 'getInfoDeGrupo', [idgrupo], function(res) {
                        //alert(JSON.stringify(res));
                        $scope.infogrupo = res.resultado;

                        setMenu([
                            {'nombre': 'Grupos', 'enlace': 'todosgrupos.php'}
                            , {'nombre': $scope.infogrupo.nombre, 'enlace': 'infogrupo.php?id=' + idgrupo}
                            , {'nombre': 'Discusión', 'enlace': '#'}
                        ]);
                    });
                };

                $scope.init = function(id) {
                    $scope.idgrupo = id;
                    $scope.ultimo_mensaje_visto = 0;
                    $scope.mensajes = [];
                    $scope.refreshchat();
                    //Después de cargar por primera vez nos vamos abajo
                    setTimeout($scope.gotoBottom, 1000);

                    $interval($scope.refreshchat, 5000);

                    $scope.cargardatosdegrupo($scope.idgrupo);

                };

                $scope.sendmsg = function(mensaje) {
                    if (mensaje.length > 0) {
                        allamar($http, 'nuevoMensajeChatGrupo', [$scope.idgrupo, mensaje], function(res) {

                            $scope.refreshchat();

                            $scope.nuevo_mensaje.texto = "";
                            //Mover a abajo
                            $scope.gotoBottom();
                        });
                    }
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

                setMenu([
                    {'nombre': 'Mis grupos', 'enlace': '#'}
                ]);

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

                setMenu([
                    {'nombre': 'Todos los grupos', 'enlace': '#'}
                ]);
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

                setMenu([
                    {'nombre': 'Perfil de usuario', 'enlace': '#'}
                ]);

            }])
        .controller('controladordetallegrupoold', ['$scope', '$http', '$interval', '$modal', function($scope, $http, $interval, $modal) {
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

                    setMenu([
                        {'nombre': 'Notificaciones', 'enlace': '#'}
                    ]);

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