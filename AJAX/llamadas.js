function llama(nombre, param) {
    alert("Llamando a " + nombre + " con los parámetros: " + param);


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
    //alert("Todo correcto, resultados: " + resultado);

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
    $("body").append("<div class='alert alert-danger alert-dismissable notificacion'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>Error:</strong> " + mensaje + "</div>");
    //createAutoClosingAlert(".notificacion", 2000);
}

function set(tabla, campo_condicion, valor_condicion, campo, valor) {
    llama('set', [tabla, campo_condicion, valor_condicion, campo, valor]);
}

//Determina que todos los formularios de clase 'ajax' se envíen por ajax y se indique si ha salido bien
$(document).ready(function() {

    //Los formularios clase 'ajax' se envían por ajax
    $('form.ajax').on('submit', function(e) {
        //Evitamos que haga lo que normalmente haría
        e.preventDefault();

        //Recogemos datos
        var url_envio = $(this).attr('action');
        var datos_envio = $(this).serialize();

        postAjax(url_envio, datos_envio);

    });

});