function obtenerasync(nombre, param, fcomplete) {
    //alert("Obteniendo de " + nombre + " con los parámetros: " + JSON.stringify(param));


    peticion = new Object();
    peticion.id_funcion = nombre;
    peticion.parametros = param;

    var res = new Object();

    $.ajax({
        async: true
        , url: "../AJAX/entry.php"
        , cache: false
        , data:
                {
                    p: JSON.stringify(peticion)
                }
        , dataType: 'json'
        , timeout: 5000
        , type: 'POST'
        , error: function(xhr, status, error)
        {
            //Mostramos el mensaje
            hayerror(xhr, status, error);

            //Componemos la respuesta
            res.hayerror = true;
            res.errormsg = error;

            //Llamamos a la función que maneja la respuesta con los datos
            fcomplete(res);


        }
        , success: function(resultado, status, xhr)
        {
            //Mostramos el mensaje
            todocorrecto(resultado, status, xhr);

            //Componemos la respuesta
            res = resultado;

            //Llamamos a la función que maneja la respuesta con los datos
            fcomplete(res);

        }
        , complete: completado
    });

}
;

function obtenersync(nombre, param) {
    //alert("Obteniendo de " + nombre + " con los parámetros: " + param);


    peticion = new Object();
    peticion.id_funcion = nombre;
    peticion.parametros = param;

    var res = new Object();

    $.ajax({
        async: false
        , url: "../AJAX/entry.php"
        , cache: false
        , data:
                {
                    p: JSON.stringify(peticion)
                }
        , dataType: 'json'
        , timeout: 2000
        , type: 'POST'
        , error: function(xhr, status, error)
        {
            //Mostramos el mensaje
            hayerror(xhr, status, error);

            //Componemos la respuesta
            res.hayerror = true;
            res.errormsg = error;
        }
        , success: function(resultado, status, xhr)
        {
            //Mostramos el mensaje
            todocorrecto(resultado, status, xhr);
            //Componemos la respuesta
            res = resultado;

        }
        , complete: completado
    });

    return res;
}
;


function llama(nombre, param) {
    //alert("Llamando a " + nombre + " con los parámetros: " + param);


    peticion = new Object();
    peticion.id_funcion = nombre;
    peticion.parametros = param;

    $.ajax({
        async: false
        , url: "../AJAX/entry.php"
        , cache: false
        , data:
                {
                    p: JSON.stringify(peticion)
                }
        , dataType: 'json'
        , timeout: 2000
        , type: 'POST'
        , error: hayerror
        , success: todocorrecto
        , complete: completado
    });
}
;

//Función para enviar los formularios por ajax
function postAjax(direccion, datos) {
    $.ajax({
        url: direccion
        , cache: false
        , data: datos
        , timeout: 5000
        , type: 'POST'
        , error: hayerror
        , success: todocorrecto
        , complete: completado
    });
}

function hayerror(xhr, status, error) {
    //alert("Error " + error);
    notificar_error(error + "Respuesta: " + xhr.responseText);
}

function todocorrecto(resultado, status, xhr) {
    //alert("Todo correcto, resultados: " + JSON.stringify(resultado));

    //Comprobamos errores propios
    if (resultado.hayerror) {
        notificar_error(resultado.errormsg);
    } else {
        notificar("Todo correcto.");
    }
}

function completado(xhr, status) {
    //alert("Completado");
    //notificar("Completado");
}

function createAutoClosingAlert(selector, delay) {
    var alert = $(selector).alert();
    //window.setTimeout(function() { alert.alert('close') }, delay);

    window.setTimeout(function() {
        alert.fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, delay);
}

function notificar(mensaje) {
    $("body").append("<div class='alert alert-success notificacion'>" + mensaje + "</div>");
    createAutoClosingAlert(".notificacion", 2000);
}

function notificar_error(mensaje) {
    $("body").append("<div class='alert alert-danger alert-dismissable notificacion-error'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>Error:</strong> " + mensaje + "</div>");
    //createAutoClosingAlert(".notificacion", 2000);
}

function set(tabla, campo_condicion, valor_condicion, campo, valor) {
    llama('set', [tabla, campo_condicion, valor_condicion, campo, valor]);
}

//Función para efectuar llamadas usando el servicio $http de angular


function allamar(servicio, nombre, parametros, success, error) {

    peticion = new Object();
    peticion.id_funcion = nombre;
    peticion.parametros = parametros;

    //alert("Enviando: " + JSON.stringify(peticion));

    //Usando http
    servicio({
        method: 'POST',
        url: '../AJAX/entry.php',
        params: {
            p: peticion
        },
        timeout: 10000,
        responseType: 'json'

    }).success(function(data, status, headers, config) {
        //alert(JSON.stringify(data));
        todocorrecto(data, status);

        success(data);
    }).error(function(data, status, headers, config) {
        notificar_error("Error." + "Respuesta: " + data);

        error(data);
    });


}