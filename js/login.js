$(document).ready(function(){

    //Seccion para mostrar lo enviado en el modal mensaje//
    if($.trim($("#mensajes").text()) != ""){
        muestraMensaje($("#mensajes").html());
    }
    //Fin de seccion de mostrar envio en modal mensaje//        
    
    $("#cedula").on("keypress",function(e){
        validarkeypress(/^[0-9\b]*$/,e);
    });
    
    $("#cedula").on("keyup",function(){
        validarkeyup(/^[0-9]{7,8}$/,$(this),
        $("#scedula"),"La cédula debe tener 7 u 8 dígitos");
    });
    
    $("#clave").on("keypress",function(e){
        validarkeypress(/^[A-Za-z0-9\b]*$/,e);
    });
    
    $("#clave").on("keyup",function(){
        if($(this).val().length > 0) {
            validarkeyup(/^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,}$/,
            $(this),$("#sclave"),"Mínimo 8 caracteres, puede incluir símbolos");
        } else {
            $("#sclave").text("");
        }
    });
    
    // Validación CAPTCHA
    $("#captcha").on("keypress",function(e){
        validarkeypress(/^[A-Za-z0-9\b]*$/,e);
    });
    
    //CONTROL DE BOTONES
    $("#entrar").on("click",function(){
        if(validarenvio()){
            $("#accion").val("entrar");    
            $("#f").submit();
        }
    });
});

function validarenvio(){
    let valido = true;
    
    // Validar cédula
    if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula"),
        $("#scedula"),"La cédula debe tener 7 u 8 dígitos")==0){
        muestraMensaje("La cédula debe tener 7 u 8 dígitos");    
        valido = false;                    
    }    
    
    // Validar contraseña
    else if(validarkeyup(/^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,}$/,
        $("#clave"),$("#sclave"),"Mínimo 8 caracteres, puede incluir símbolos")==0){
        muestraMensaje("Contraseña<br/>Mínimo 8 caracteres, puede incluir símbolos");
        valido = false;
    }
    
    // Validar CAPTCHA
    else if($("#captcha").val().trim().length !== 6) {
        $("#scaptcha").text("El CAPTCHA debe tener exactamente 6 caracteres");
        muestraMensaje("Por favor ingrese el código CAPTCHA correctamente");
        valido = false;
    }
    
    return valido;
}

//Funcion que muestra el modal con un mensaje
function muestraMensaje(mensaje){
    $("#contenidodemodal").html(mensaje);
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
        $("#mostrarmodal").modal("hide");
    },5000);
}

//Función para validar por Keypress
function validarkeypress(er,e){
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
        e.preventDefault();
    }
}

//Función para validar por keyup
function validarkeyup(er,etiqueta,etiquetamensaje,mensaje){
    a = er.test(etiqueta.val());
    if(a){
        etiquetamensaje.text("");
        return 1;
    }
    else{
        etiquetamensaje.text(mensaje);
        return 0;
    }
}