console.log("holaa");
function consultar(){
	var datos = new FormData();
	datos.append('accion','consultar');
	enviaAjax(datos);	
}
function mostrar(){
	var datos = new FormData();
	datos.append('accion','mostrar');
	enviaAjax(datos);	
}
function destruyeDT(){
	//1 se destruye el datatablet
	if ($.fn.DataTable.isDataTable("#tablapersonal")) {
            $("#tablapersonal").DataTable().destroy();
    }
}
function crearDT(){
	//se crea nuevamente
    if (!$.fn.DataTable.isDataTable("#tablapersonal")) {
            $("#tablapersonal").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontró personal",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay personal registrado",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                  first: "Primera",
                  last: "Última",
                  next: "Siguiente",
                  previous: "Anterior",
                },
              },
              autoWidth: false,
              order: [[1, "asc"]],
            });
    }         
}
$(document).ready(function(){
	
	//ejecuta una consulta a la base de datos para llenar la tabla
	consultar();
	
//VALIDACION DE DATOS		
	
	/*$("#apellido").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#apellido").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sapellido"),"Solo letras  entre 3 y 30 caracteres");
	});
	
	$("#nombre").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#nombre").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#snombre"),"Solo letras  entre 3 y 30 caracteres");
	});
	
	$("#correo").on("keypress",function(e){
		validarkeypress(/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/);
	});
	
	$("#correo").on("keyup",function(){
		validarkeyup(/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/ ,
		$(this),$("#scorreo"),"Formato incorrecto");
	});*/

    $("#cedula_p").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});
	
	$("#cedula_p").on("keyup",function(){
		validarkeyup(/^[0-9]{7,8}$/,$(this),
		$("#scedula_p"),"El formato debe ser 12345678 ");
	});
    $("#cedula_h").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});
	
	$("#cedula_h").on("keyup",function(){
		validarkeyup(/^[0-9]{7,8}$/,$(this),
		$("#scedula_h"),"El formato debe ser 12345678 ");
	});
	
	
	
//FIN DE VALIDACION DE DATOS



//CONTROL DE BOTONES
$("#proceso").on("click",function(){
	if($(this).text()=="INCLUIR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','incluir');
			datos.append('',$("#cedula_personal").val());
			datos.append('apellido',$("#apellido").val());
			datos.append('nombre',$("#nombre").val());
			datos.append('correo',$("#correo").val());
			datos.append('telefono',$("#telefono").val());
			datos.append('cargo',$("#cargo").val());
			enviaAjax(datos);
		}
	}
	else if($(this).text()=="MODIFICAR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','modificar');
			datos.append('cedula_personal',$("#cedula_personal").val());
			datos.append('apellido',$("#apellido").val());
			datos.append('nombre',$("#nombre").val());
			datos.append('correo',$("#correo").val());
			datos.append('telefono',$("#telefono").val());
			datos.append('cargo',$("#cargo").val());
			enviaAjax(datos);
		}
	}
	if($(this).text()=="ELIMINAR"){
		if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_personal"),
		$("#scedula_personal"),"El formato debe ser 12345678")==0){
	    muestraMensaje("La cedula debe coincidir con el formato <br/>"+ 
						"12345678");	
		
	    }
		else{
			var datos = new FormData();
			datos.append('accion','eliminar');
			datos.append('cedula_personal',$("#cedula_personal").val());
			enviaAjax(datos);
		}
	}
});
$("#incluir").on("click",function(){
	limpia();
	$("#proceso").text("INCLUIR");
	$("#modal1").modal("show");
});





	
	
});

//Validación de todos los campos antes del envio
function validarenvio(){
	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_personal"),
		$("#scedula_h"),"El formato debe ser 12345678")==0){
	    muestraMensaje("La cedula debe coincidir con el formato <br/>"+ 
						"12345678");	
		return false;					
	}	
	/*else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$("#apellido"),$("#sapellido"),"Solo letras  entre 3 y 30 caracteres")==0){
		muestraMensaje("apellido <br/>Solo letras  entre 3 y 30 caracteres");
		return false;
	}
	else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$("#nombre"),$("#snombre"),"Solo letras  entre 3 y 30 caracteres")==0){
		muestraMensaje("nombre <br/>Solo letras  entre 3 y 30 caracteres");
		return false;
	}*/
	
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
function validarkeyup(er,etiqueta,etiquetamensaje,
mensaje){
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

//funcion para pasar de la lista a el formulario
function pone(pos,accion){
	
	linea=$(pos).closest('tr');


	if(accion==0){
		$("#proceso").text("MODIFICAR");
	}
	else{
		$("#proceso").text("ELIMINAR");
	}
	$("#horaingreso").val($(linea).find("td:eq(1)").text());
	$("#fechaingreso").val($(linea).find("td:eq(2)").text());
	$("#motingreso").val($(linea).find("td:eq(3)").text());
	$("#diagnostico_e").val($(linea).find("td:eq(4)").text());
	$("#tratamientos").val($(linea).find("td:eq(5)").text());
	$("#cedula_p").val($(linea).find("td:eq(6)").text());
    $("#cedula_h").val($(linea).find("td:eq(7)").text());
	
	$("#modal1").modal("show");
}


//funcion que envia y recibe datos por AJAX
function enviaAjax(datos) {
  $.ajax({
    async: true,
    url: "",
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    beforeSend: function () {},
    timeout: 10000, //tiempo maximo de espera por la respuesta del servidor
    success: function (respuesta) {
    console.log(respuesta);
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "consultar") {
		   destruyeDT();	
           $("#resultadoconsulta").html(lee.mensaje);
		   crearDT();
        }
        else if (lee.resultado == "mostrar") {
            destruyeDT();	
            $("#resultamostrar").html(lee.mensaje);
            crearDT();
         }
		else if (lee.resultado == "incluir") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Inluido'){
			   $("#modal1").modal("hide");
			   consultar();
		   }
        }
		else if (lee.resultado == "modificar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Modificado'){
			   $("#modal1").modal("hide");
			   consultar();
		   }
        }
		else if (lee.resultado == "eliminar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Eliminado'){
			   $("#modal1").modal("hide");
			   consultar();
		   }
        }
		else if (lee.resultado == "error") {
           muestraMensaje(lee.mensaje);
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
      }
    },
    error: function (request, status, err) {
      // si ocurrio un error en la trasmicion
      // o recepcion via ajax entra aca
      // y se muestran los mensaje del error
      if (status == "timeout") {
        //pasa cuando superan los 10000 10 segundos de timeout
        muestraMensaje("Servidor ocupado, intente de nuevo");
      } else {
        //cuando ocurre otro error con ajax
        muestraMensaje("ERROR: <br/>" + request + status + err);
      }
    },
    complete: function () {},
  });
}

function limpia(){
	$("#horaingreso").val("");
	$("#fechaingreso").val("");
	$("#motingreso").val("");
	$("#diagnostico_e").val("");
	$("#tratamientos").val("");
    $("#cedula_p").val("");
	$("#cedula_h").prop("selectedIndex",0);
}
