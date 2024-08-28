
$(document).ready(function(){
	var datos = new FormData();
		datos.append('accion','obtienefecha');
		enviaAjax(datos);	

// VALIDAR LOS DATOS
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
	
	
	
	
//FIN VALIDAR DATOS



// CONTROL DE LOS BOTONES

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
		datos.append('cedula_historia',$("#cedula_historia").val());
		datos.append('apellido',$("#apellido").val());
		datos.append('nombre',$("#nombre").val());
		datos.append('fecha_nac',$("#fecha_nac").val());
		datos.append('edad',$("#edad").val());
		datos.append('telefono',$("#telefono").val());
		datos.append('estadocivi',$("#estadocivi").val());
		datos.append('direccion',$("#direccion").val());
		datos.append('ocupacion',$("#ocupacion").val());
		datos.append('hda',$("#hda").val());
		datos.append('habtoxico',$("#habtoxico").val());
		datos.append('alergias',$("#alergias").val());
		datos.append('quirurgicos',$("#quirurgicos").val());
		datos.append('transsanguineo',$("#transsanguineo").val());
		datos.append('boca_abierta',$("#boca_abierta").val());
		datos.append('boca_cerrada',$("#boca_cerrada").val());
		datos.append('oidos',$("#oidos").val());
		datos.append('cabeza_craneo',$("#cabeza_craneo").val());
		datos.append('ojos',$("#ojos").val());
		datos.append('nariz',$("#nariz").val());
		datos.append('tiroides',$("#tiroides").val());
		datos.append('cardiovascular',$("#cardiovascular").val());
		datos.append('respiratorio',$("#respiratorio").val());
		datos.append('abdomen',$("#abdomen").val());
		datos.append('extremidades',$("#extremidades").val());
		datos.append('neurologico',$("#neurologico").val());
		// datos.append('antec_madre',$("#antec_madre").val());
		// datos.append('antec_padre',$("#antec_padre").val());
		// datos.append('antec_hermano',$("#antec_hermano").val());
		// datos.append('antec_personal',$("#antec_personal").val());
		
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
		datos.append('direccion',$("#direccion").val());
		datos.append('ocupacion',$("#ocupacion").val());
		datos.append('hda',$("#hda").val());
		datos.append('habtoxico',$("#habtoxico").val());
		datos.append('alergias',$("#alergias").val());
		datos.append('quirurgicos',$("#quirurgicos").val());
		datos.append('transsanguineo',$("#transsanguineo").val());
		datos.append('boca_abierta',$("#boca_abierta").val());
		datos.append('boca_cerrada',$("#boca_cerrada").val());
		datos.append('oidos',$("#oidos").val());
		datos.append('cabeza_craneo',$("#cabeza_craneo").val());
		datos.append('ojos',$("#ojos").val());
		datos.append('nariz',$("#nariz").val());
		datos.append('tiroides',$("#tiroides").val());
		datos.append('cardiovascular',$("#cardiovascular").val());
		datos.append('respiratorio',$("#respiratorio").val());
		datos.append('abdomen',$("#abdomen").val());
		datos.append('extremidades',$("#extremidades").val());
		datos.append('neurologico',$("#neurologico").val());
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

function destruyeDT(){
	if ($.fn.DataTable.isDataTable("#tablapersonas")) {
            $("#tablapersonas").DataTable().destroy();
    }
}
function crearDT(){
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


// VALIDAR ANTES DE ENVIAR
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


function muestraMensaje(mensaje){
	
	$("#contenidodemodal").html(mensaje);
			$("#mostrarmodal").modal("show");
			setTimeout(function() {
					$("#mostrarmodal").modal("hide");
			},5000);
}


function validarkeypress(er,e){
	key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
		e.preventDefault();
    }
}

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
			timeout: 10000,
            success: function(respuesta) {
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
						$("#cedula_historia").val(lee.mensaje[0][10]);
					   $("#apellido").val(lee.mensaje[0][10]);
					   $("#nombre").val(lee.mensaje[0][10]);
					   $("#fecha_nac").val(lee.mensaje[0][10]);
					   $("#edad").val(lee.mensaje[0][10]);
					   $("#telefono").val(lee.mensaje[0][12]);
					   $("#estadocivi").val(lee.mensaje[0][10]);
					   $("#direccion").val(lee.mensaje[0][10]);
					   $("#ocupacion").val(lee.mensaje[0][10]);
					   $("#hda").val(lee.mensaje[0][10]);
					   $("#habtoxico").val(lee.mensaje[0][10]);
					   $("#alergias").val(lee.mensaje[0][10]);
					   $("#quirurgicos").val(lee.mensaje[0][10]);
					   $("#transsanguineo").val(lee.mensaje[0][10]);
					   $("#boca_abierta").val(lee.mensaje[0][10]);
					   $("#boca_cerrada").val(lee.mensaje[0][10]);
					   $("#oidos").val(lee.mensaje[0][10]);
					   $("#cabeza_craneo").val(lee.mensaje[0][10]);
					   $("#ojos").val(lee.mensaje[0][10]);
					   $("#nariz").val(lee.mensaje[0][10]);
					   $("#tiroides").val(lee.mensaje[0][10]);
					   $("#cardiovascular").val(lee.mensaje[0][10]);
					   $("#respiratorio").val(lee.mensaje[0][10]);
					   $("#abdomen").val(lee.mensaje[0][10]);
					   $("#extremidades").val(lee.mensaje[0][10]);
					   $("#neurologico").val(lee.mensaje[0][10]);
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
			  if (status == "timeout") {
				muestraMensaje("Servidor ocupado, intente de nuevo");
			  } else {
				muestraMensaje("ERROR: <br/>" + request + status + err);
			  }
			},
			complete: function () {},
    }); 
}

// function pone(pos,accion){
	
// 	linea=$(pos).closest('tr');


// 	if(accion==0){
// 		$("#proceso").text("Enviar");
// 	}
// 	else if (accion==1){
// 		$("#proceso").text("ELIMINAR");
// 	}
// 	else{
// 		$("#proceso").text("INCLUIR");
// 	}
	
// 	$("#antec_madre").val($(linea).find("td:eq(1)").text());
// 	$("#antec_padre").val($(linea).find("td:eq(2)").text());
// 	$("#antec_hermano").val($(linea).find("td:eq(3)").text());
// 	$("#antec_personal").val($(linea).find("td:eq(4)").text());
// 	$("#cardiovascular").val($(linea).find("td:eq(1)").text());
// 	$("#respiratorio").val($(linea).find("td:eq(2)").text());
// 	$("#abdomen").val($(linea).find("td:eq(3)").text());
// 	$("#neurologico").val($(linea).find("td:eq(4)").text());
// 	$("#modal1").modal("show");
// }

// function poneregional(pos,accion){
	
// 	linea=$(pos).closest('tr');


// 	if(accion==0){
// 		$("#proceso2").text("Enviar");
// 	}
	
// 	$("#boca_abierta").val($(linea).find("td:eq(1)").text());
// 	$("#boca_cerrada").val($(linea).find("td:eq(2)").text());
// 	$("#oidos").val($(linea).find("td:eq(3)").text());
// 	$("#cabeza_craneo").val($(linea).find("td:eq(4)").text());
// 	$("#ojos").val($(linea).find("td:eq(5)").text());
// 	$("#nariz").val($(linea).find("td:eq(6)").text());
// 	$("#tiroides").val($(linea).find("td:eq(7)").text());
// 	$("#extremidades").val($(linea).find("td:eq(8)").text());
	
// 	$("#modal2").modal("show");
// }

// function ponegeneral(pos,accion){
	
// 	linea=$(pos).closest('tr');


// 	if(accion==0){
// 		$("#proceso3").text("Enviar");
// 	}
	
// 	$("#general").val($(linea).find("td:eq(1)").text());
// 	$("#modal3").modal("show");
// }

// function ponesistema(pos,accion){
	
// 	linea=$(pos).closest('tr');


// 	if(accion==0){
// 		$("#proceso4").text("Enviar");
// 	}
// 	$("#cardiovascular").val($(linea).find("td:eq(1)").text());
// 	$("#respiratorio").val($(linea).find("td:eq(2)").text());
// 	$("#abdomen").val($(linea).find("td:eq(3)").text());
// 	$("#neurologico").val($(linea).find("td:eq(4)").text());
// 	$("#modal4").modal("show");
// }

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

$(document).on('click', '.btn-modificar', function() {
    var row = $(this).closest('tr');
    
    // Leer los valores de los atributos data-*
    var estadocivi = row.data('estadocivi');
    var direccion = row.data('direccion');
    var ocupacion = row.data('ocupacion');
    var hda = row.data('hda');
    var habtoxico = row.data('habtoxico');
    var alergias = row.data('alergias');
    var quirurgico = row.data('quirurgico');
    var transsanguineo = row.data('transsanguineo');
    var boca_abierta = row.data('boca_abierta');
    var boca_cerrada = row.data('boca_cerrada');
    var oidos = row.data('oidos');
    var cabeza_craneo = row.data('cabeza_craneo');
    var ojos = row.data('ojos');
    var nariz = row.data('nariz');
    var tiroides = row.data('tiroides');
    var cardiovascular = row.data('cardiovascular');
    var respiratorio = row.data('respiratorio');
    var abdomen = row.data('abdomen');
    var extremidades = row.data('extremidades');
    var neurologico = row.data('neurologico');
    
    // Rellenar los campos del formulario en el modal
    $('#modalModificar input[name="estadocivi"]').val(estadocivi);
    $('#modalModificar input[name="direccion"]').val(direccion);
    $('#modalModificar input[name="ocupacion"]').val(ocupacion);
    $('#modalModificar input[name="hda"]').val(hda);
    $('#modalModificar input[name="habtoxico"]').val(habtoxico);
    $('#modalModificar input[name="alergias"]').val(alergias);
    $('#modalModificar input[name="quirurgico"]').val(quirurgico);
    $('#modalModificar input[name="transsanguineo"]').val(transsanguineo);
    $('#modalModificar input[name="boca_abierta"]').val(boca_abierta);
    $('#modalModificar input[name="boca_cerrada"]').val(boca_cerrada);
    $('#modalModificar input[name="oidos"]').val(oidos);
    $('#modalModificar input[name="cabeza_craneo"]').val(cabeza_craneo);
    $('#modalModificar input[name="ojos"]').val(ojos);
    $('#modalModificar input[name="nariz"]').val(nariz);
    $('#modalModificar input[name="tiroides"]').val(tiroides);
    $('#modalModificar input[name="cardiovascular"]').val(cardiovascular);
    $('#modalModificar input[name="respiratorio"]').val(respiratorio);
    $('#modalModificar input[name="abdomen"]').val(abdomen);
    $('#modalModificar input[name="extremidades"]').val(extremidades);
    $('#modalModificar input[name="neurologico"]').val(neurologico);
    
    // Mostrar el modal
    $('#modalModificar').modal('show');
});