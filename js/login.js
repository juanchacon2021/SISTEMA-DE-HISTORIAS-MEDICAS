$(document).ready(function(){

    //Seccion para mostrar lo enviado en el modal mensaje//
    if($.trim($("#mensajes").text()) != ""){
        muestraMensaje($("#mensajes").html());
    }
    //Fin de seccion de mostrar envio en modal mensaje//        
        
    $("#email").on("keypress",function(e){
        validarkeypress(/^[A-Za-z0-9@.\-\b]*$/,e);
    });
    
    $("#email").on("keyup",function(){
        validarkeyup(/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/,$(this),
        $("#semail"),"Ingrese un email válido");
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
    
    //CONTROL DE BOTONES
    $("#entrar").on("click",function(){
        if(validarenvio()){
            $("#accion").val("entrar");    
            $("#f").submit();
        }
    });
});

//Validación de todos los campos antes del envio
function validarenvio(){
    if(validarkeyup(/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/,$("#email"),
        $("#semail"),"Ingrese un email válido")==0){
        muestraMensaje("El email debe ser válido");    
        return false;                    
    }    
    else if(validarkeyup(/^[A-Za-z0-9]{7,15}$/,
        $("#clave"),$("#sclave"),"Solo letras y numeros entre 7 y 15 caracteres")==0){
        muestraMensaje("Contraseña<br/>Solo letras y numeros entre 7 y 15 caracteres");
        return false;
    }
    
    return true;
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