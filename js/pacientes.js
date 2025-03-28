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

$(document).ready(function () {
	consultar();

	// Validaciones
	$("#cedula_historia").on("keypress", function (e) {
		validarkeypress(/^[0-9-\b]*$/, e);
	});

	$("#cedula_historia").on("keyup", function () {
		validarkeyup(/^[0-9]{7,8}$/, $(this),
			$("#scedula_historia"), "El formato debe ser 12345678 ");
	});


	$("#apellido").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#apellido").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#sapellido"), "Solo letras  entre 3 y 30 caracteres");
	});

	$("#nombre").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#nombre").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#snombre"), "Solo letras  entre 3 y 30 caracteres");
	});

	$("#telefono").on("keypress", function (e) {
		validarkeypress(/^[0-9-\b]*$/, e);
	});

	$("#telefono").on("keyup", function () {
		validarkeyup(/^[0-9]{7,11}$/, $(this),
			$("#stelefono"), "El formato debe ser de 11 numeros");
	});

	$("#ocupacion").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#ocupacion").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#socupacion"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#hda").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#hda").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#shda"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#habtoxico").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#habtoxico").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#shabtoxico"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#alergias").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#alergias").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#salergias"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#quirurgico").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#quirurgico").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#squirurgico"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#transsanguineo").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#transsanguineo").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#stranssanguineo"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#alergias_med").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#alergias_med").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#salergias_med"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#psicosocial").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#psicosocial").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#spsicosocial"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#antc_madre").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#antc_madre").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#santc_madre"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#antc_padre").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#antc_padre").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#santc_padre"), "Solo letras  entre 3 y 300 caracteres");
	});

	$("#antc_hermano").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
	});

	$("#antc_hermano").on("keyup", function () {
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$(this), $("#santc_hermano"), "Solo letras  entre 3 y 300 caracteres");
	});
});




// Control de los Botones
$("#proceso").on("click", function () {
	if($(this).text()=="INCLUIR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append("accion", accion);
			datos.append("cedula_historia", $("#cedula_historia").val());
			datos.append("nombre", $("#nombre").val());
			datos.append("apellido", $("#apellido").val());
			datos.append("fecha_nac", $("#fecha_nac").val());
			datos.append("edad", $("#edad").val());
			datos.append("telefono", $("#telefono").val());
			datos.append("estadocivil", $("#estadocivil").val());
			datos.append("direccion", $("#direccion").val());
			datos.append("ocupacion", $("#ocupacion").val());
			datos.append("hda", $("#hda").val());
			datos.append("alergias", $("#alergias").val());
			datos.append("alergias_med", $("#alergias_med").val());
			datos.append("transsanguineo", $("#transsanguineo").val());
			datos.append("quirurgico", $("#quirurgico").val());
			datos.append("psicosocial", $("#psicosocial").val());
			datos.append("habtoxico", $("#habtoxico").val());
			datos.append("antc_padre", $("#antc_padre").val());
			datos.append("antc_hermano", $("#antc_hermano").val());
			datos.append("antc_madre", $("#antc_madre").val());
			consultar();
			// Enviar los datos
			enviaAjax(datos);
		}
	}else if($(this).text()=="MODIFICAR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append("accion", accion);
			datos.append("cedula_historia", $("#cedula_historia").val());
			datos.append("nombre", $("#nombre").val());
			datos.append("apellido", $("#apellido").val());
			datos.append("fecha_nac", $("#fecha_nac").val());
			datos.append("edad", $("#edad").val());
			datos.append("telefono", $("#telefono").val());
			datos.append("estadocivil", $("#estadocivil").val());
			datos.append("direccion", $("#direccion").val());
			datos.append("ocupacion", $("#ocupacion").val());
			datos.append("hda", $("#hda").val());
			datos.append("alergias", $("#alergias").val());
			datos.append("alergias_med", $("#alergias_med").val());
			datos.append("transsanguineo", $("#transsanguineo").val());
			datos.append("quirurgico", $("#quirurgico").val());
			datos.append("psicosocial", $("#psicosocial").val());
			datos.append("habtoxico", $("#habtoxico").val());
			datos.append("antc_padre", $("#antc_padre").val());
			datos.append("antc_hermano", $("#antc_hermano").val());
			datos.append("antc_madre", $("#antc_madre").val());
			consultar();
			// Enviar los datos
			enviaAjax(datos);
		}
	}
});
$("#incluir").on("click",function(){
	limpia();
	$("#proceso").text("INCLUIR");
	$("#modal1").modal("show");
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
			},2000);
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
	$("#alergias_med").val($(linea).find("td:eq(13)").text());
	$("#quirurgico").val($(linea).find("td:eq(14)").text());
	$("#psicosocial").val($(linea).find("td:eq(15)").text());
	$("#transsanguineo").val($(linea).find("td:eq(16)").text());
    $("#antc_padre").val($(linea).find("td:eq(17)").text());
	$("#antc_hermano").val($(linea).find("td:eq(18)").text());
	$("#antc_madre").val($(linea).find("td:eq(19)").text());
	
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
			var html = '';
			 
			lee.datos.forEach(function (fila) {
				html += `<tr>
					<td>
						<div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
							<a type="button" class="btn btn-success" onclick="pone(this,0)"
								cedula_historia="${fila.cedula_historia}"
								apellido="${fila.apellido}
								nombre="${fila.nombre}"
								fecha_nac="${fila.fecha_nac}"
								edad="${fila.edad}"
								telefono="${fila.telefono}"
								estadocivil="${fila.estadocivil}"
								direccion="${fila.direccion}"
								ocupacion="${fila.ocupacion}"
								hda="${fila.hda}"
								habtoxico="${fila.habtoxico}"
								alergias="${fila.alergias}"
								alergia_med="${fila.alergia_med}"
								quirurgico="${fila.quirurgico}"
								transsanguineo="${fila.transsanguineo}"
								psicosocial="${fila.psicosocial}"
								antc_padre="${fila.antc_padre}"
								antc_hermano="${fila.antc_hermano}"
								antc_madre="${fila.antc_madre}">
								<img src="img/lapiz.svg" style="width: 20px">
							</a>
							<a class="btn btn-danger" href="vista/fpdf/historia.php?cedula_historia=${fila.cedula_historia}" target="_blank">
								<img src="img/descarga.svg" style="width: 20px;">
							</a>
						</div>
					</td>
					<td>${fila.cedula_historia}</td>
					<td>${fila.apellido}</td>
					<td>${fila.nombre}</td>
					<td>${fila.fecha_nac}</td>
					<td>${fila.edad}</td>
					<td>${fila.telefono}</td>
					<td style="display: none;">${fila.estadocivil}</td>
					<td style="display: none;">${fila.direccion}</td>
					<td style="display: none;">${fila.ocupacion}</td>
					<td style="display: none;">${fila.hda}</td>
					<td style="display: none;">${fila.habtoxico}</td>
					<td style="display: none;">${fila.alergias}</td>
					<td style="display: none;">${fila.alergia_med}</td>
					<td style="display: none;">${fila.quirurgico}</td>
					<td style="display: none;">${fila.psicosocial}</td>
					<td style="display: none;">${fila.transsanguineo}</td>
					<td style="display: none;">${fila.antc_padre}</td>
					<td style="display: none;">${fila.antc_hermano}</td>
					<td style="display: none;">${fila.antc_madre}</td>
				</tr>`;
			});
			
			$("#resultadoconsulta").html(html);
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
		  //pasa cuando superan los 10000 10 segundos de timeout
		  muestraMensaje("Servidor ocupado, intente de nuevo");
		} else {
		  //cuando ocurre otro error con ajax
		  muestraMensaje("ERROR: <br/>" + request + status + err);
		}
	  },
	  complete: function () {},
	});
	function limpia(){
		$("#cedula_historia").val("");
		$("#apellido").val("");
		$("#nombre").val("");
		$("#fecha_nac").val("");
		$("#edad").val("");
		$("#telefono").val("");
		$("#estadocivil").val("");
		$("#direccion").val("");
		$("#ocupacion").val("");
		$("#hda").val("");
		$("#habtoxico").val("");
		$("#alergias").val("");
		$("#alergias_med").val("");
		$("#quirurgico").val("");
		$("#transsanguineo").val("");
		$("#psicosocial").val("");
		$("#antc_padre").val("");
		$("#antc_hermano").val("");
		$("#antc_madre").val("");
	}
  }

document.addEventListener('DOMContentLoaded', function () {
	const fechaNacInput = document.getElementById('fecha_nac');
	const edadInput = document.getElementById('edad');

	fechaNacInput.addEventListener('input', function () {
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

