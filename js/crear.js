
$(document).ready(function(){
	//para obtener la fecha del servidor y calcular la 
	//edad de nacimiento que debe ser mayor a 18 
	var datos = new FormData();
		datos.append('accion','obtienefecha');
		enviaAjax(datos);	
	//fin de colocar fecha en input fecha de nacimiento
//VALIDACION DE DATOS	
	$("#cedula_historia").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});
	
	$("#cedula_historia").on("keyup",function(){
		validarkeyup(/^[0-9]{7,8}$/,$(this),
		$("#scedula_historia"),"El formato debe ser 9999999 ");
		if($("#cedula_historia").val().length > 7){
		  var datos = new FormData();
		    datos.append('accion','consultatr');
			datos.append('cedula_historia',$(this).val());
			enviaAjax(datos,'consultatr');	
		}
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
	
	$("#fecha_nac").on("keyup",function(){
		validarkeyup(/^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/,
		$(this),$("#sfecha_nac"),"Ingrese una fecha valida");
	});
	
	
	
	
//FIN DE VALIDACION DE DATOS



//CONTROL DE BOTONES

$("#incluir").on("click",function(){
	if(validarenvio()){
		var datos = new FormData($("#f")[0]);
		datos.append('accion','incluir');
		if($("#masculino").is(":checked")){
			datos.append('sexof','M');
		}
		else{
			datos.append('sexof','F');
		}
		enviaAjax(datos);
	}
});

$("#incluir").on("click",function(){
	if(validarenvio()){

		var datos = new FormData($("#f")[0]);
		datos.append('accion','incluir');
		datos.append('antec_madre',$("#antec_madre").val());
		datos.append('antec_padre',$("#antec_padre").val());
		datos.append('antec_hermano',$("#antec_hermano").val());
		datos.append('antec_personal',$("#antec_personal").val());
		
		enviaAjax(datos);
		
	}
});

$("#modificar").on("click",function(){
	if(validarenvio()){

		var datos = new FormData();
		datos.append('accion','modificar');
		datos.append('cedula_historia',$("#cedula_historia").val());
		datos.append('apellido',$("#apellido").val());
		datos.append('nombre',$("#nombre").val());
		datos.append('fecha_nac',$("#fecha_nac").val());
		datos.append('edad',$("#edad").val());
		datos.append('telefono',$("#telefono").val());
		datos.append('estadocivi',$("#estadocivi").val());
		datos.append('direccion',$("#apellido").val());
		datos.append('ocupacion',$("#nombre").val());
		datos.append('hda',$("#fecha_nac").val());
		datos.append('habtoxico',$("#edad").val());
		datos.append('alergias',$("#telefono").val());
		datos.append('quirurgicos',$("#estadocivi").val());
		datos.append('transsanguineo',$("#apellido").val());
		datos.append('boca_abierta',$("#nombre").val());
		datos.append('boca_cerrada',$("#fecha_nac").val());
		datos.append('oidos',$("#edad").val());
		datos.append('cabeza_craneo',$("#telefono").val());
		datos.append('ojos',$("#estadocivi").val());
		datos.append('nariz',$("#estadocivi").val());
		datos.append('tiroides',$("#apellido").val());
		datos.append('cardiovascular',$("#nombre").val());
		datos.append('respiratorio',$("#fecha_nac").val());
		datos.append('abdomen',$("#edad").val());
		datos.append('extremidades',$("#telefono").val());
		datos.append('neurologico',$("#estadocivi").val());
		enviaAjax(datos);
		
	}
});

$("#eliminar").on("click",function(){
	
	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_historia"),
		$("#scedula_historia"),"El formato debe ser 9999999")==0){
	    muestraMensaje("La cedula debe coincidir con el formato <br/>"+ 
						"99999999");	
		
	}
	else{	
	    
		var datos = new FormData();
		datos.append('accion','eliminar');
		datos.append('cedula_historia',$("#cedula_historia").val());
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
	if ($.fn.DataTable.isDataTable("#tablapersonas")) {
            $("#tablapersonas").DataTable().destroy();
    }
}
function crearDT(){
	//se crea nuevamente
    if (!$.fn.DataTable.isDataTable("#tablapersonas")) {
            $("#tablapersonas").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron personas",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay personas registradas",
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
	if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$("#apellido"),$("#sapellido"),"Solo letras  entre 3 y 30 caracteres")==0){
		muestraMensaje("apellido <br/>Solo letras  entre 3 y 30 caracteres");
		return false;
	}
	else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$("#nombre"),$("#snombre"),"Solo letras  entre 3 y 30 caracteres")==0){
		muestraMensaje("nombre <br/>Solo letras  entre 3 y 30 caracteres");
		return false;
	}
	else if(validarkeyup(/^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/,
		$("#fecha_nac"),$("#sfecha_nac"),"Ingrese una fecha valida")==0){
		muestraMensaje("Fecha de Nacimiento <br/>Ingrese una fecha valida");
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
	$("#cedula_historia").val($(linea).find("td:eq(0)").text());
	$("#apellido").val($(linea).find("td:eq(1)").text());
	$("#nombre").val($(linea).find("td:eq(2)").text());
	$("#fecha_nac").val($(linea).find("td:eq(3)").text());
	$("#edad").val($(linea).find("td:eq(4)").text());
	$("#telefono").val($(linea).find("td:eq(5)").text());
	$("#estadocivi").val($(linea).find("td:eq(6)").text());
	$("#direccion").val($(linea).find("td:eq(7)").text());
	$("#ocupacion").val($(linea).find("td:eq(8)").text());
	$("#hda").val($(linea).find("td:eq(9)").text());
	$("#habtoxico").val($(linea).find("td:eq(10)").text());
	$("#alergias").val($(linea).find("td:eq(11)").text());
	$("#quirurgicos").val($(linea).find("td:eq(12)").text());
	$("#transsanguineo").val($(linea).find("td:eq(13)").text());
	$("#boca_abierta").val($(linea).find("td:eq(14)").text());
	$("#boca_cerrada").val($(linea).find("td:eq(15)").text());
	$("#oidos").val($(linea).find("td:eq(16)").text());
	$("#cabeza_craneo").val($(linea).find("td:eq(17)").text());
	$("#ojos").val($(linea).find("td:eq(18)").text());
	$("#nariz").val($(linea).find("td:eq(19)").text());
	$("#tiroides").val($(linea).find("td:eq(20)").text());
	
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
					if (lee.resultado == "obtienefecha") {
					  $("#fecha_nac").val(lee.mensaje);
					}
					else if (lee.resultado == "consultar") {
					   destruyeDT();
					   $("#resultadoconsulta").html(lee.mensaje);
					   crearDT();
					   $("#modal1").modal("show");
					}
					else if (lee.resultado == "encontro") {
						$("#cedula_historia").val(lee.mensaje[0][2]);
					   $("#apellido").val(lee.mensaje[0][2]);
					   $("#nombre").val(lee.mensaje[0][3]);
					   $("#fecha_nac").val(lee.mensaje[0][10]);
					   $("#edad").val(lee.mensaje[0][3]);
					   $("#telefono").val(lee.mensaje[0][12]);
					   $("#estadocivi").val(lee.mensaje[0][4]);
					   $("#direccion").val(lee.mensaje[0][2]);
					   $("#ocupacion").val(lee.mensaje[0][2]);
					   $("#hda").val(lee.mensaje[0][3]);
					   $("#habtoxico").val(lee.mensaje[0][4]);
					   $("#alergias").val(lee.mensaje[0][2]);
					   $("#quirurgicos").val(lee.mensaje[0][3]);
					   $("#transsanguineo").val(lee.mensaje[0][2]);
					   $("#boca_abierta").val(lee.mensaje[0][3]);
					   $("#boca_cerrada").val(lee.mensaje[0][4]);
					   $("#oidos").val(lee.mensaje[0][2]);
					   $("#cabeza_craneo").val(lee.mensaje[0][3]);
					   $("#ojos").val(lee.mensaje[0][10]);
					   $("#nariz").val(lee.mensaje[0][3]);
					   $("#tiroides").val(lee.mensaje[0][4]);
					   $("#cardiovascular").val(lee.mensaje[0][2]);
					   $("#respiratorio").val(lee.mensaje[0][3]);
					   $("#abdomen").val(lee.mensaje[0][2]);
					   $("#extremidades").val(lee.mensaje[0][3]);
					   $("#neurologico").val(lee.mensaje[0][4]);
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

function pone(pos,accion){
	
	linea=$(pos).closest('tr');


	if(accion==0){
		$("#proceso").text("Enviar");
	}
	else if (accion==1){
		$("#proceso").text("ELIMINAR");
	}
	else{
		$("#proceso").text("INCLUIR");
	}
	$("#antec_madre").val($(linea).find("td:eq(1)").text());
	$("#antec_padre").val($(linea).find("td:eq(2)").text());
	$("#antec_hermano").val($(linea).find("td:eq(3)").text());
	$("#antec_personal").val($(linea).find("td:eq(4)").text());
	$("#modal1").modal("show");
}

function poneregional(pos,accion){
	
	linea=$(pos).closest('tr');


	if(accion==0){
		$("#proceso2").text("Enviar");
	}
	
	$("#boca_abierta").val($(linea).find("td:eq(1)").text());
	$("#boca_cerrada").val($(linea).find("td:eq(2)").text());
	$("#oidos").val($(linea).find("td:eq(3)").text());
	$("#cabeza_craneo").val($(linea).find("td:eq(4)").text());
	$("#ojos").val($(linea).find("td:eq(5)").text());
	$("#nariz").val($(linea).find("td:eq(6)").text());
	$("#tiroides").val($(linea).find("td:eq(7)").text());
	$("#extremidades").val($(linea).find("td:eq(8)").text());
	$("#modal2").modal("show");
}

function ponegeneral(pos,accion){
	
	linea=$(pos).closest('tr');


	if(accion==0){
		$("#proceso3").text("Enviar");
	}
	
	$("#general").val($(linea).find("td:eq(1)").text());
	$("#modal3").modal("show");
}

function ponesistema(pos,accion){
	
	linea=$(pos).closest('tr');


	if(accion==0){
		$("#proceso4").text("Enviar");
	}
	$("#cardiovascular").val($(linea).find("td:eq(1)").text());
	$("#respiratorio").val($(linea).find("td:eq(2)").text());
	$("#abdomen").val($(linea).find("td:eq(3)").text());
	$("#neurologico").val($(linea).find("td:eq(4)").text());
	$("#modal4").modal("show");
}

function limpia(){
	$("#cedula_historia").val("");
	$("#apellido").val("");
	$("#nombre").val("");
	$("#fecha_nac").val("");
	$("#edad").val("");
	$("#telefono").val("");
	var datos = new FormData();
		datos.append('accion','obtienefecha');
		enviaAjax(datos,'obtienefecha');	
	$("#estadocivi").prop("selectedIndex",0);
}
