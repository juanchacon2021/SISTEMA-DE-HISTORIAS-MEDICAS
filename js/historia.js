function consultar(){
	var datos = new FormData();
	datos.append('accion','consultar');
	enviaAjax(datos);	
}
function destruyeDT(){
	if ($.fn.DataTable.isDataTable("#tablapersonal")) {
            $("#tablapersonal").DataTable().destroy();
    }
}
function crearDT(){
    if (!$.fn.DataTable.isDataTable("#tablapersonal")) {
            $("#tablapersonal").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontró pacientes",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay pacientes registrados",
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
	consultar();
	
// Validaciones
	$("#cedula_historia").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});
	
	$("#cedula_historia").on("keyup",function(){
		validarkeyup(/^[0-9]{7,8}$/,$(this),
		$("#scedula_historia"),"El formato debe ser 12345678 ");
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

	$("#telefono").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});
	
	$("#telefono").on("keyup",function(){
		validarkeyup(/^[0-9]{7,8}$/,$(this),
		$("#stelefono"),"El formato debe ser de 11 numeros");
	});

	$("#ocupacion").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#ocupacion").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#socupacion"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#hda").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#hda").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#shda"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#habtoxico").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#habtoxico").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#shabtoxico"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#alergias").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#alergias").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#salergias"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#quirurgico").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#quirurgico").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#squirurgico"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#transsanguineo").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#transsanguineo").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#stranssanguineo"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#alergias_med").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#alergias_med").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#salergias_med"),"Solo letras  entre 3 y 300 caracteres");
	});
	
	$("#psicosocial").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#psicosocial").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#spsicosocial"),"Solo letras  entre 3 y 300 caracteres");
	});
	
	$("#oidos").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#oidos").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#soidos"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#cabeza_craneo").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#cabeza_craneo").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#scabeza_craneo"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#ojos").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#ojos").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sojos"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#nariz").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#nariz").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#snariz"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#tiroides").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#tiroides").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#stiroides"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#boca_abierta").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#boca_abierta").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sboca_abierta"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#boca_cerrada").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#boca_cerrada").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sboca_cerrada"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#cardiovascular").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#cardiovascular").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#scardiovascular"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#respiratorio").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#respiratorio").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#srespiratorio"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#abdomen").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#abdomen").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sabdomen"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#extremidades").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#extremidades").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sextremidades"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#neurologico").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#neurologico").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sneurologico"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#antec_madre").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#antec_madre").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#santec_madre"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#antec_padre").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#antec_padre").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#santec_padre"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#antec_hermano").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#antec_hermano").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#santec_hermano"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#general").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#general").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sgeneral"),"Solo letras  entre 3 y 300 caracteres");
	});






// Control de los Botones
$("#proceso").on("click",function(){
	if($(this).text()=="INCLUIR"){
		
			var datos = new FormData();
			datos.append('accion','incluir');
			datos.append('cedula_historia',$("#cedula_historia").val());
			datos.append('apellido',$("#apellido").val());
			datos.append('nombre',$("#nombre").val());
			datos.append('fecha_nac',$("#fecha_nac").val());
			datos.append('edad',$("#edad").val());
			datos.append('telefono',$("#telefono").val());
			datos.append('estadocivil',$("#estadocivil").val());
			datos.append('direccion',$("#direccion").val());
			datos.append('ocupacion',$("#ocupacion").val());
			datos.append('hda',$("#hda").val());
			datos.append('habtoxico',$("#habtoxico").val());
			datos.append('alergias',$("#alergias").val());
			datos.append('quirurgico',$("#quirurgico").val());
			datos.append('transsanguineo',$("#transsanguineo").val());
			datos.append('alergias_med',$("#alergias_med").val());
			datos.append('psicosocial',$("#psicosocial").val());
			datos.append('antec_madre',$("#antec_madre").val());
			datos.append('antec_padre',$("#antec_padre").val());
			datos.append('antec_hermano',$("#antec_hermano").val());
			datos.append('cedula_h',$("#cedula_h").val());
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
			 datos.append('extremidades_s',$("#extremidades_s").val());
			 datos.append('neurologicos',$("#neurologicos").val());
			 datos.append('general',$("#general").val());
			enviaAjax(datos);
		
	}
	else if($(this).text()=="MODIFICAR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','incluir');
			datos.append('cedula_historia',$("#cedula_historia").val());
			datos.append('apellido',$("#apellido").val());
			datos.append('nombre',$("#nombre").val());
			datos.append('fecha_nac',$("#fecha_nac").val());
			datos.append('edad',$("#edad").val());
			datos.append('telefono',$("#telefono").val());
			datos.append('estadocivil',$("#estadocivil").val());
			datos.append('direccion',$("#direccion").val());
			datos.append('ocupacion',$("#ocupacion").val());
			datos.append('hda',$("#hda").val());
			datos.append('habtoxico',$("#habtoxico").val());
			datos.append('alergias',$("#alergias").val());
			datos.append('quirurgico',$("#quirurgico").val());
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
			 datos.append('extremidades_s',$("#extremidades_s").val());
			 datos.append('neurologicos',$("#neurologicos").val());
			 datos.append('general',$("#general").val());
			enviaAjax(datos);
		}
	}
	// if($(this).text()=="ELIMINAR"){
	// 	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_historia"),
	// 	$("#scedula_historia"),"El formato debe ser 12345678")==0){
	//     muestraMensaje("La cedula debe coincidir con el formato <br/>"+ 
	// 					"12345678");	
		
	//     }
	// 	else{
	// 		var datos = new FormData();
	// 		datos.append('accion','eliminar');
	// 		datos.append('cedula_historia',$("#cedula_historia").val());
	// 		enviaAjax(datos);
	// 	}
	// }
});
$("#incluir").on("click",function(){
	limpia();
	$("#proceso").text("INCLUIR");
	$("#modal1").modal("show");
});
	
});

//Validación de todos los campos antes de eviar
function validarenvio(){
	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_historia"),
		$("#scedula_historia"),"El formato debe ser 12345678")==0){
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
	else if(validarkeyup(/^[0-9]{7,8}$/,$("#telefono"),
		$("#stelefono"),"El formato debe ser de 11 numeros")==0){
	    muestraMensaje("El telefono debe coincidir con el formato <br/>"+ 
						"de 11 numeros");	
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
				window.location.href = '?pagina=pacientes';
			}, 1000);
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
		$("#proceso").text("INCLUIR");
	}
	$("#cedula_historia").val($(linea).find("td:eq(1)").text());
	$("#apellido").val($(linea).find("td:eq(2)").text());
	$("#nombre").val($(linea).find("td:eq(3)").text());
	$("#fecha_nac").val($(linea).find("td:eq(4)").text());
	$("#edad").val($(linea).find("td:eq(5)").text());
	$("#telefono").val($(linea).find("td:eq(6)").text());
	$("#estadocivil").val($(linea).find("td:eq(7)").text());
	$("#direccion").val($(linea).find("td:eq(8)").text());
	$("#ocupacion").val($(linea).find("td:eq(9)").text());
	$("#hda").val($(linea).find("td:eq(10)").text());
	$("#habtoxico").val($(linea).find("td:eq(11)").text());
	$("#alergias").val($(linea).find("td:eq(12)").text());
	$("#quirurgico").val($(linea).find("td:eq(13)").text());
	$("#transsanguineo").val($(linea).find("td:eq(14)").text());
	$("#alergias_med").val($(linea).find("td:eq(15)").text());
	$("#psicosocial").val($(linea).find("td:eq(16)").text());
	$("#antec_madre").val($(linea).find("td:eq(17)").text());
	$("#antec_padre").val($(linea).find("td:eq(18)").text());
	$("#antec_hermano").val($(linea).find("td:eq(19)").text());
	$("#cedula_h").val($(linea).find("td:eq(20)").text());
	 $("#boca_abierta").val($(linea).find("td:eq(15)").text());
	 $("#boca_cerrada").val($(linea).find("td:eq(16)").text());
	 $("#oidos").val($(linea).find("td:eq(17)").text());
	 $("#cabeza_craneo").val($(linea).find("td:eq(18)").text());
	 $("#ojos").val($(linea).find("td:eq(19)").text());
	 $("#nariz").val($(linea).find("td:eq(20)").text());
	 $("#tiroides").val($(linea).find("td:eq(21)").text());
	 $("#cardiovascular").val($(linea).find("td:eq(22)").text());
	 $("#respiratorio").val($(linea).find("td:eq(23)").text());
	 $("#abdomen").val($(linea).find("td:eq(24)").text());
	 $("#extremidades").val($(linea).find("td:eq(25)").text());
	 $("#extremidades_s").val($(linea).find("td:eq(26)").text());
	 $("#neurologicos").val($(linea).find("td:eq(27)").text());
	 $("#general").val($(linea).find("td:eq(28)").text());
	
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
		  else if (lee.resultado == "incluir") {
			 muestraMensaje(lee.mensaje);
			 if(lee.mensaje=='Registro Inluido'){
				 $("#modal1").modal("hide");
				 consultar();
                setTimeout(function() {
                    window.location.href = '?pagina=pacientes';
                }, 2000);
			 }
		  }
		  else if (lee.resultado == "modificar") {
			 muestraMensaje(lee.mensaje);
			 if(lee.mensaje=='Registro Modificado'){
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
		if (status == "timeout") {
		  muestraMensaje("Servidor ocupado, intente de nuevo");
		} else {
		  muestraMensaje("ERROR: <br/>" + request + status + err);
		}
	  },
	  complete: function () {},
	});
  }


document.addEventListener('DOMContentLoaded', function() {
    const fechaNacInput = document.getElementById('fecha_nac');
    const edadInput = document.getElementById('edad');

    fechaNacInput.addEventListener('input', function() {
        const fechaNac = new Date(this.value);
        const hoy = new Date();
        let edad = hoy.getFullYear() - fechaNac.getFullYear();
        const mes = hoy.getMonth() - fechaNac.getMonth();

        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
            edad--;
        }

        edadInput.value = edad;
    });
});