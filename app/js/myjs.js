

function redirect(destino) {

    // similar behavior as clicking on a link
    window.location.href = destino;

}

$(document).ready(function() {

//Hacemos que los checkboxes devuelvan siempre un valor 0 o 1

    //Primero los inicializamos en función del valor que tengan
    $("[type='checkbox']").each(function() {
        $(this).val($(this).prop('checked'));
    });

//Mantenemos la coherencia cambiandolo cada vez que cambien
    $("[type='checkbox']").change(function() {
        $(this).val($(this).prop('checked'));
    });
});


//Función para convertir un objeto en array
function toArray(objeto) {

    var respuesta = $.map(objeto, function(value, index) {
        return [value];
    });

    return respuesta;
}

//Función para llenar a con los datos de b suponiendo que sean objetos y sólo el primer nivel
//los siguientes niveles siguen siendo referencias
//TODO podría ser útil hacer la versión recursiva deepfillwith
function shallowfillwith(a, b) {

    //Recorremos b
    // Handle Object
    if (a instanceof Object && b instanceof Object) {
        for (var attr in b) {
            //Si la propiedad es propia del objeto entonces la copiamos
            if (b.hasOwnProperty(attr)) {
                a[attr] = b[attr];
            }
        }
    }

}

function clone(obj) {
    // Handle the 3 simple types, and null or undefined
    if (null == obj || "object" != typeof obj)
        return obj;

    // Handle Date
    if (obj instanceof Date) {
        var copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }

    // Handle Array
    if (obj instanceof Array) {
        var copy = [];
        for (var i = 0, len = obj.length; i < len; i++) {
            copy[i] = clone(obj[i]);
        }
        return copy;
    }

    // Handle Object
    if (obj instanceof Object) {
        var copy = {};
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr))
                copy[attr] = clone(obj[attr]);
        }
        return copy;
    }

    throw new Error("Unable to copy obj! Its type isn't supported.");
}

function mapear(vector, id_attr) {

    var respuesta = {};

    for (var i in vector) {
        var elemento = vector[i];

        respuesta[elemento[id_attr]] = vector[i];

    }

    return respuesta;
}

//comprueba si está logueado y conduce a login o nologin según el resultado
function checkLogin(servicio, login, nologin) {
    //Comprobar si el usuario tiene sesión y redirigir a login
    allamar(servicio, 'checkLogin', null, function(res) {
        //Si está logueado en la aplicación todo OK
        //alert(JSON.stringify(res));
        if (res.resultado) {
            login();
        } else {
            //Si no está logueado comprobamos si está logueado en facebook
            FB.getLoginStatus(function(response) {
                //alert(JSON.stringify(response));
                if (response.status === 'connected') {

                    //Recogemos los últimos datos del usuario (nombre, apellidos, correo electrónico)
                    FB.api('/me', function(usuario) {

                        allamar(servicio
                                , 'loginfacebook'
                                , [
                                    usuario.first_name
                                            , usuario.last_name
                                            , usuario.email
                                            , response.authResponse.userID
                                            , response.authResponse.accessToken
                                            , response.authResponse.expiresIn
                                            , usuario.verified
                                ]
                                , function(res) {
                                    //Si todo va bien continuamos
                                        login();
                                }, function(res) {
                            //Sino nada
                            nologin();
                        });
                    });
                } else {
                    //Si no está logueado en facebook lo mandamos al login
                    nologin();
                }
            });
        }
    });
}

function checkLoginSimple(servicio) {
    //Comprobar si el usuario tiene sesión y redirigir a login
    allamar(servicio, 'checkLogin', null, function(res) {
        //Si está logueado en la aplicación todo OK
        if (!res.resultado) {
            redirect("login.php");
        }
    });
}

var siguienteposicion = 0;

function reload() {
    $route.reload();
}

function nop(){
    //alert("NOP");
}