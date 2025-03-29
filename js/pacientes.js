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

    // Función genérica para validaciones
    function agregarValidacion(selector, regex, errorSelector, errorMessage, onInput) {
		$(selector).on("input", onInput);
        $(selector).on("keypress", function (e) {
            // validarkeypress(regex, e); // Comentar para pruebas
        });

        $(selector).on("keyup", function () {
            validarkeyup(regex, $(this), $(errorSelector), errorMessage);
        });
    }

    // Configuración de validaciones
    const validaciones = [
        { selector: "#cedula_historia", regex: /^[0-9]{7,8}$/, errorSelector: "#scedula_historia", errorMessage: "El formato debe ser 12345678" },
        { selector: "#apellido", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#sapellido", errorMessage: "Solo letras entre 3 y 30 caracteres" },
        { selector: "#nombre", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#snombre", errorMessage: "Solo letras entre 3 y 30 caracteres" },
        { 
			selector: "#telefono", 
			regex: /^[0-9]{0,11}$/, 
			errorSelector: "#stelefono", 
			errorMessage: "El formato debe ser de 11 números",
			onInput: function () {
				const valor = $(this).val();
				$(this).val(valor.replace(/[^0-9]/g, ''));
			}
		},
        { selector: "#ocupacion", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#socupacion", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#hda", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#shda", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#habtoxico", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#shabtoxico", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#alergias", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#salergias", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#quirurgico", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#squirurgico", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#transsanguineo", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#stranssanguineo", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#alergias_med", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#salergias_med", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#psicosocial", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#spsicosocial", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#antc_madre", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#santc_madre", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#antc_padre", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#santc_padre", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#antc_hermano", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, errorSelector: "#santc_hermano", errorMessage: "Solo letras entre 3 y 300 caracteres" }
    ];

    // Aplicar validaciones
    validaciones.forEach(({ selector, regex, errorSelector, errorMessage, onInput }) => {
		agregarValidacion(selector, regex, errorSelector, errorMessage, onInput);
	});
});




// Control de los Botones
$("#proceso").on("click", function () {
	if ($(this).text() == "INCLUIR") {
		if (validarenvio()) {
			var datos = new FormData();
			datos.append("accion", "incluir");
			datos.append("cedula_historia", $("#cedula_historia").val());
			datos.append("apellido", $("#apellido").val());
			datos.append("nombre", $("#nombre").val());
			datos.append("fecha_nac", $("#fecha_nac").val());
			datos.append("edad", $("#edad").val());
			datos.append("telefono", $("#telefono").val());
			datos.append("estadocivil", $("#estadocivil").val());
			datos.append("direccion", $("#direccion").val());
			datos.append("ocupacion", $("#ocupacion").val());
			datos.append("hda", $("#hda").val());
			datos.append("habtoxico", $("#habtoxico").val());
			datos.append("alergias", $("#alergias").val());
			datos.append("alergias_med", $("#alergias_med").val());
			datos.append("quirurgico", $("#quirurgico").val());
			datos.append("transsanguineo", $("#transsanguineo").val());
			datos.append("psicosocial", $("#psicosocial").val());
			datos.append("antc_padre", $("#antc_padre").val());
			datos.append("antc_hermano", $("#antc_hermano").val());
			datos.append("antc_madre", $("#antc_madre").val());
			enviaAjax(datos);
		}
	} else if ($(this).text() == "MODIFICAR") {
		if (validarenvio()) {
			var datos = new FormData();
			datos.append("accion", "modificar");
			datos.append("cedula_historia", $("#cedula_historia").val());
			datos.append("apellido", $("#apellido").val());
			datos.append("nombre", $("#nombre").val());
			datos.append("fecha_nac", $("#fecha_nac").val());
			datos.append("edad", $("#edad").val());
			datos.append("telefono", $("#telefono").val());
			datos.append("estadocivil", $("#estadocivil").val());
			datos.append("direccion", $("#direccion").val());
			datos.append("ocupacion", $("#ocupacion").val());
			datos.append("hda", $("#hda").val());
			datos.append("habtoxico", $("#habtoxico").val());
			datos.append("alergias", $("#alergias").val());
			datos.append("alergias_med", $("#alergias_med").val());
			datos.append("quirurgico", $("#quirurgico").val());
			datos.append("transsanguineo", $("#transsanguineo").val());
			datos.append("psicosocial", $("#psicosocial").val());
			datos.append("antc_padre", $("#antc_padre").val());
			datos.append("antc_hermano", $("#antc_hermano").val());
			datos.append("antc_madre", $("#antc_madre").val());
			enviaAjax(datos);
		}
	}
});
$("#incluir").on("click", function () {
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
function pone(pos, accion) {
    const linea = $(pos).closest('tr');
    const mapeoCampos = {
        "cedula_historia": "cedula_historia",
        "apellido": "apellido",
        "nombre": "nombre",
        "fecha_nac": "fecha_nac",
        "edad": "edad",
        "telefono": "telefono",
        "estadocivil": "estadocivil",
        "direccion": "direccion",
        "ocupacion": "ocupacion",
        "hda": "hda",
        "habtoxico": "habtoxico",
        "alergias": "alergias",
        "alergias_med": "alergias_med",
        "quirurgico": "quirurgico",
        "psicosocial": "psicosocial",
        "transsanguineo": "transsanguineo",
        "antc_padre": "antc_padre",
        "antc_hermano": "antc_hermano",
        "antc_madre": "antc_madre"
    };

    // Cambiar el texto del proceso según la acción
    $("#proceso").text(accion === 0 ? "MODIFICAR" : "INCLUIR");

    // Iterar sobre el mapeo y asignar valores
    Object.entries(mapeoCampos).forEach(([campo, clase]) => {
        $(`#${campo}`).val($(linea).find(`.${clase}`).text());
    });

    // Mostrar el modal
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
					<td class="cedula_historia">${fila.cedula_historia}</td>
					<td class="apellido">${fila.apellido}</td>
					<td class="nombre">${fila.nombre}</td>
					<td class="fecha_nac">${fila.fecha_nac}</td>
					<td class="edad">${fila.edad}</td>
					<td class="telefono">${fila.telefono}</td>
					<td class="estadocivil" style="display: none;">${fila.estadocivil}</td>
					<td class="direccion" style="display: none;">${fila.direccion}</td>
					<td class="ocupacion" style="display: none;">${fila.ocupacion}</td>
					<td class="hda" style="display: none;">${fila.hda}</td>
					<td class="habtoxico" style="display: none;">${fila.habtoxico}</td>
					<td class="alergias" style="display: none;">${fila.alergias}</td>
					<td class="alergias_med" style="display: none;">${fila.alergias_med}</td>
					<td class="quirurgico" style="display: none;">${fila.quirurgico}</td>
					<td class="psicosocial" style="display: none;">${fila.psicosocial}</td>
					<td class="transsanguineo" style="display: none;">${fila.transsanguineo}</td>
					<td class="antc_padre" style="display: none;">${fila.antc_padre}</td>
					<td class="antc_hermano" style="display: none;">${fila.antc_hermano}</td>
					<td class="antc_madre" style="display: none;">${fila.antc_madre}</td>
				</tr>`;
			});
			$("#resultadoconsulta").html(html);
			
			 $("#resultadoconsulta").html(lee.mensaje);
			 crearDT();
		  }
		  else if (lee.resultado == "incluir") {
			 muestraMensaje(lee.mensaje);
			 if (lee.mensaje == 'Registro Incluido') {
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


// DESENFOCAR FONDO DEL MODAL
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modal1');

    modal.addEventListener('show.bs.modal', function () {
      document.body.classList.add('blur-background');
    });

    modal.addEventListener('hidden.bs.modal', function () {
      document.body.classList.remove('blur-background');
    });
  });