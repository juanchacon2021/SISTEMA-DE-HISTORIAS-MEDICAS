
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




// Control de los Botones
$("#proceso").on("click",function(){
	if($(this).text()=="MODIFICAR"){
		
			var datos = new FormData();
			datos.append('accion','modificar');
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
$("#modificar").on("click",function(){
	limpia();
	$("#proceso").text("MODIFICAR");
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
		$("#proceso").text("MODIFICAR");
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
	$("#boca_abierta").val($(linea).find("td:eq(21)").text());
	$("#boca_cerrada").val($(linea).find("td:eq(22)").text());
	$("#oidos").val($(linea).find("td:eq(23)").text());
	$("#cabeza_craneo").val($(linea).find("td:eq(24)").text());
	$("#ojos").val($(linea).find("td:eq(25)").text());
	$("#nariz").val($(linea).find("td:eq(26)").text());
	$("#tiroides").val($(linea).find("td:eq(27)").text());
	$("#cardiovascular").val($(linea).find("td:eq(28)").text());
	$("#respiratorio").val($(linea).find("td:eq(29)").text());
	$("#abdomen").val($(linea).find("td:eq(30)").text());
	$("#extremidades").val($(linea).find("td:eq(31)").text());
	$("#extremidades_s").val($(linea).find("td:eq(32)").text());
	$("#neurologicos").val($(linea).find("td:eq(33)").text());
	$("#general").val($(linea).find("td:eq(34)").text());
	
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
		  else if (lee.resultado == "modificar") {
			 muestraMensaje(lee.mensaje);
			 if(lee.mensaje=='Registro Modificado'){
				 $("#modal1").modal("hide");
				 consultar();
                setTimeout(function() {
                    window.location.href = '?pagina=pacientes';
                }, 2000);
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
