
$(document).ready(function(){
	//para obtener la fecha del servidor y calcular la 
	//edad de nacimiento que debe ser mayor a 18 
	var datos = new FormData();
		enviaAjax(datos);	
	//fin de colocar fecha en input fecha de nacimiento
//VALIDACION DE DATOS	
		$("#cedula_personal").on("keypress",function(e){
			validarkeypress(/^[0-9-\b]*$/,e);
		});

		$("#cedula_personal").on("keyup",function(){
			validarkeyup(/^[0-9]{7,8}$/,$(this),
			$("#scedula_personal"),"El formato debe ser 12345678 ");
		});


		$("#apellido").on("keypress",function(e){
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
		});



	
//FIN DE VALIDACION DE DATOS



//CONTROL DE BOTONES

$("#incluir").on("click",function(){
	if(validarenvio()){
		var datos = new FormData($("#f")[0]);
		datos.append('accion','incluir');
		enviaAjax(datos);
	}
});
$("#modificar").on("click",function(){
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
});

$("#eliminar").on("click",function(){
	
	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_personal"),
		$("#scedula_personal"),"El formato debe ser 12345678")==0){
	    muestraMensaje("La cedula_personal debe coincidir con el formato <br/>"+ 
						"12345678");	
		
	}
	else{	
	    
		var datos = new FormData();
		datos.append('accion','eliminar');
		datos.append('cedula_personal',$("#cedula_personal").val());
		enviaAjax(datos);
	}
	
});

$("#consultar").on("click",function(){
	var datos = new FormData();
	datos.append('accion','consultar');
	enviaAjax(datos);
});
//FIN DE CONTROL DE BOTONES	


	
	
});

//funcion para enlazar al DataTablet
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
                zeroRecords: "No se encontraron personals",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay personals registradas",
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


//Validación de todos los campos antes del envio
function validarenvio(){
	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_personal"),
		$("#scedula_personal"),"El formato debe ser 12345678")==0){
	    muestraMensaje("La cedula debe coincidir con el formato <br/>"+ 
						"12345678");	
		return false;					
	}	
	else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$("#apellido"),$("#sapellido"),"Solo letras  entre 3 y 30 caracteres")==0){
		muestraMensaje("apellido <br/>Solo letras  entre 3 y 30 caracteres");
		return false;
	}
	else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$("#nombre"),$("#snombre"),"Solo letras  entre 3 y 30 caracteres")==0){
		muestraMensaje("nombre <br/>Solo letras  entre 3 y 30 caracteres");
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
function coloca(linea){
	$("#cedula_personal").val($(linea).find("td:eq(1)").text());
	$("#apellido").val($(linea).find("td:eq(2)").text());
	$("#nombre").val($(linea).find("td:eq(3)").text());
	$("#correo").val($(linea).find("td:eq(4)").text());
	$("#telefono").val($(linea).find("td:eq(5)").text());
	$("#cargo").val($(linea).find("td:eq(6)").text());
	
}

//funcion que envia y recibe datos por AJAX
function enviaAjax(datos){
	 
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
            success: function(respuesta) {//si resulto exitosa la transmision
			console.log(respuesta);
				try {
					var lee = JSON.parse(respuesta);
					if (lee.resultado == "consultar") {
					   destruyeDT();
					   $("#resultadoconsulta").html(lee.mensaje);
					   crearDT();
					   $("#modal1").modal("show");
					}
					else if (lee.resultado == "encontro") {
					   $("#apellido").val(lee.mensaje[0][2]);
					   $("#nombre").val(lee.mensaje[0][3]);
					   $("#correo").val(lee.mensaje[0][4]);
					   $("#telefono").val(lee.mensaje[0][5]);	
						$("#cargo").val(lee.mensaje[0][6]);
					}
					else if (lee.resultado == "incluir" || 
					lee.resultado == "modificar" || 
					lee.resultado == "eliminar") {
					   muestraMensaje(lee.mensaje);
					   limpia();
					}
					else if (lee.resultado == "error") {
					   muestraMensaje(lee.mensaje);
					}
					else if (e) {
						alert("Error en JSON " + e.name);
					  }
			  } 
			   catch {
				console.log("todo fino")
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
	$("#cedula_personal").val("");
	$("#apellido").val("");
	$("#nombre").val("");
	$("#correo").val("");
	$("#telefono").val("");
	$("#cargo").prop("selectedIndex",0);
}
