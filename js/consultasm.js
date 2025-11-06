function consultar(){
	var datos = new FormData();
	datos.append('accion','consultar');
	enviaAjax(datos);	
}
function carga_personal(){

	var datos = new FormData();

	datos.append('accion','listadopersonal'); 

	enviaAjax(datos);
}
function carga_pacientes(){
	
	var datos = new FormData();

	datos.append('accion','listadopacientes'); 

	enviaAjax(datos);
}
function carga_observaciones(){
	
	var datos = new FormData();

	datos.append('accion','listado_observaciones'); 

	enviaAjax(datos);
}

function cargarObservacionesConsulta(cod_consulta) {
    var datos = new FormData();
    datos.append('accion', 'obtener_observaciones_consulta');
    datos.append('cod_consulta', cod_consulta);
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
                zeroRecords: "No se encontró ninguna consulta",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay consultas registradas",
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

function destruyeDT1(){
	if ($.fn.DataTable.isDataTable("#tablahistorias")) {
            $("#tablahistorias").DataTable().destroy();
    }
	console.log('listo');
	// crearDT1()
}

function crearDT1(){
	console.log('listo1');
    if (!$.fn.DataTable.isDataTable("#tablahistorias")) {
            $("#tablahistorias").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontró ninguna paciente",
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
//77
function destruyeDT2(){
	if ($.fn.DataTable.isDataTable("#tabladelpersonal")) {
            $("#tabladelpersonal").DataTable().destroy();
    }
	console.log('listo');
	// crearDT1()
}
function crearDT2(){
	console.log('listo1');
    if (!$.fn.DataTable.isDataTable("#tabladelpersonal")) {
            $("#tabladelpersonal").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontró ninguna personal",
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

function destruyeDT3(){
	if ($.fn.DataTable.isDataTable("#tabla_Observaciones")) {
            $("#tabla_Observaciones").DataTable().destroy();
    }
	
}
function crearDT3(){
	console.log('listo1');
    if (!$.fn.DataTable.isDataTable("#tabla_Observaciones")) {
            $("#tabla_Observaciones").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontró ninguna Observación",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay observaciones registradas",
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
	carga_personal();
	carga_pacientes();
	carga_observaciones();
	

	$("#listadodepersonal").on("click",function(){
		$("#modalpersonal").modal("show");
	});

	$("#listadodepacientes").on("click",function(){
		$("#modalpacientes").modal("show");
	});

	$("#listado_observaciones").on("click",function(){
		$("#modalobservacion").modal("show");
	});
		//Validar
	$("#cedula_paciente").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});
	
	$("#cedula_paciente").on("keyup", function() {
		var $input = $(this);
		var cedula = $input.val();

		// Limita a 8 caracteres
		if (cedula.length > 8) {
			cedula = cedula.slice(0, 8);
			$input.val(cedula);
		}

		if (validarkeyup(/^[0-9]{7,8}$/, $input, $("#scedula_paciente"), "El formato debe ser 12345678")) {
			$("#scedula_paciente").css("color", "black");
			if (cedulaExisteEnListado(cedula)) {
				$("#scedula_paciente").text("✔ Cédula encontrada").css("color", "green");
			} else {
				$("#scedula_paciente")
					.html(`Cédula no está registrada, registrar cédula
							<a style="color: green; text-decoration: underline;" href="/SISTEMA-DE-HISTORIAS-MEDICAS/pacientes&accion=registrar">aquí</a>`)
					.css("color", "red");
			}
		} else {
			// Si el formato es incorrecto, asegúrate de que el mensaje sea negro
			$("#scedula_paciente").css("color", "black");
		}
	});

	$("#cedula_personal").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});


	$("#cedula_personal").on("keyup", function() {
		var $input = $(this);
		var cedula = $input.val();

		// Limita a 8 caracteres
		if (cedula.length > 8) {
			cedula = cedula.slice(0, 8);
			$input.val(cedula);
		}

		if (validarkeyup(/^[0-9]{7,8}$/, $input, $("#scedula_personal"), "El formato debe ser 12345678")) {
			$("#scedula_personal").css("color", "black");
			if (cedulaExisteEnListado2(cedula)) {
				$("#scedula_personal").text("✔ Cédula encontrada").css("color", "green");
			} else {
				$("#scedula_personal")
					.html(`Cédula no está registrada, registrar cédula
							<a style="color: green; text-decoration: underline;" href="/SISTEMA-DE-HISTORIAS-MEDICAS/personal&accion=registrar">aqui</a>`)
					.css("color", "red");
			}
		} else {
			// Si el formato es incorrecto, asegúrate de que el mensaje sea negro
			$("#scedula_personal").css("color", "black");
		}
	});

	$("#consulta").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#consulta").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sconsulta"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#diagnostico").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#diagnostico").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sdiagnostico"),"Solo letras  entre 3 y 300 caracteres");
	});
	$("#tratamientos").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#tratamientos").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#stratamientos"),"Solo letras  entre 3 y 300 caracteres");
	});
	
	//validar

	$("#cedula_personal").on("keyup",function(){
		var cedula = $(this).val();
		var encontro = false;
		$("#listadopersonal tr").each(function(){
			if(cedula == $(this).find("td:eq(1)").text()){
				colocapersonal($(this));
				encontro = true;
			} 
		});
		if(!encontro){
			$("#datosdelpersonal").html("");
		}
	});

	$("#cedula_paciente").on("keyup",function(){
		var cedula = $(this).val();
		var encontro = false;
		$("#listadopacientes tr").each(function(){
			if(cedula == $(this).find("td:eq(1)").text()){
				colocapacientes($(this));
				encontro = true;
			} 
		});
		if(!encontro){
			$("#datosdelpacientes").html("");
		}
	});

$("#proceso2").on("click", function() {
    if ($(this).text() == "agregar") {
        // Validar y enviar datos
        if (validarenvio2()) {
            var datos = new FormData();
            datos.append('accion', 'agregar');
            datos.append('cod_observacion', $("#cod_observacion").val());
            datos.append('nom_observaciones', $("#nom_observaciones").val());
            enviaAjax(datos);
        }
    }

		else if($(this).text()=="actualizar"){
		if(validarenvio2()){
			var datos = new FormData();
			datos.append('accion','actualizar');
			datos.append('cod_observacion',$("#cod_observacion").val());
			datos.append('nom_observaciones',$("#nom_observaciones").val());
			enviaAjax(datos);
		}
	}

	else{
		    var datos = new FormData();
			datos.append('accion','descartar');
			datos.append('cod_observacion',$("#cod_observacion").val());
			enviaAjax(datos);
		}

	$("#agregar").on("click",function(){
		limpia();
		$("#proceso2").text("agregar");
		$("#modal2").modal("show");
	});

});


$("#proceso").on("click",function(){
	if($(this).text()=="INCLUIR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','incluir');
			datos.append('cod_consulta',$("#cod_consulta").val());
			datos.append('fechaconsulta',$("#fechaconsulta").val());
			datos.append('Horaconsulta',$("#Horaconsulta").val());
			datos.append('consulta',$("#consulta").val());
			datos.append('diagnostico',$("#diagnostico").val());
			datos.append('tratamientos',$("#tratamientos").val());
			datos.append('cedula_personal',$("#cedula_personal").val());
			datos.append('cedula_paciente',$("#cedula_paciente").val());


			 $("#observaciones_container input[name='cod_observacion[]']").each(function() {
                var cod_observacion = $(this).val();
                var observacion = $(`textarea[name='observacion_${cod_observacion}']`).val();
                datos.append('cod_observacion[]', cod_observacion);
                datos.append('observacion_' + cod_observacion, observacion);
            });

			enviaAjax(datos);
			
		}
	}
	else if($(this).text()=="MODIFICAR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','modificar');
			datos.append('cod_consulta',$("#cod_consulta").val());
			datos.append('fechaconsulta',$("#fechaconsulta").val());
			datos.append('Horaconsulta',$("#Horaconsulta").val());
			datos.append('consulta',$("#consulta").val());
			datos.append('diagnostico',$("#diagnostico").val());
			datos.append('tratamientos',$("#tratamientos").val());
			datos.append('cedula_personal',$("#cedula_personal").val());
			datos.append('cedula_paciente',$("#cedula_paciente").val());

			  // Agrega todas las observaciones dinámicas
            $("#observaciones_container input[name='cod_observacion[]']").each(function() {
                var cod_observacion = $(this).val();
                var observacion = $(`textarea[name='observacion_${cod_observacion}']`).val();
                datos.append('cod_observacion[]', cod_observacion);
                datos.append('observacion_' + cod_observacion, observacion);
            });

			enviaAjax(datos);
		}
	}
	else if($(this).text()=="CERRAR"){
	
		$("#modal1").modal("hide");
	}

	else{
		    var datos = new FormData();
			datos.append('accion','eliminar');
			datos.append('cod_consulta',$("#cod_consulta").val());
			enviaAjax(datos);
		}
	
});
$("#incluir").on("click",function(){
	limpia();
	$("#proceso").text("INCLUIR");
	$("#modal1").modal("show");
});


	
	
});



function validarenvio(){

	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_paciente"),
		$("#scedula_paciente"),"El formato debe ser 12345678")==0){
	    muestraMensaje("La cedula del paciente debe coincidir con el formato <br/>"+ 
						"12345678");	
		return false;					
	}

	else if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_personal"),
	$("#scedula_personal"),"El formato debe ser 12345678")==0){
	muestraMensaje("La cedula del personal debe coincidir con el formato <br/>"+ 
					"12345678");	
	return false;					
	}

	
	return true;
}

function validarenvio2(){


	function validarObservacion(){
		if(validarkeyup(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$("#nom_observaciones"),
			$("#snom_observaciones"),
			"Debe ingresar una observación válida (3-30 letras o números)") == 0){
			muestraMensaje("Debe ingresar una observación válida (3-30 letras o números)");
			return false;
		}
		return true;
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

function colocapersonal(linea) {
    // Obtiene los datos de la fila seleccionada
    const cedula = $(linea).find("td:eq(1)").text();
    const nombre = $(linea).find("td:eq(2)").text();
    const apellido = $(linea).find("td:eq(3)").text();
    const cargo = $(linea).find("td:eq(4)").text();

    // Coloca los datos en los campos correspondientes
    $("#cedula_personal").val(cedula);
    $("#datosdelpersonal").html(
        `Nombre: ${nombre} / Apellido: ${apellido} / Cargo: ${cargo}`
    );

    // Cierra el modal
    $("#modalpersonal").modal("hide");
}

function colocapacientes(linea) {
    // Obtiene los datos de la fila seleccionada
    const cedula = $(linea).find("td:eq(1)").text();
    const nombre = $(linea).find("td:eq(2)").text();
    const apellido = $(linea).find("td:eq(3)").text();

    // Coloca los datos en los campos correspondientes
    $("#cedula_paciente").val(cedula);
    $("#datosdelpacientes").html(
        `Nombre: ${nombre} / Apellido: ${apellido}`
    );

    // Cierra el modal
    $("#modalpacientes").modal("hide");
}


$("#agregar_observacion").on("click", function() {
    var select = $("#select_observacion");
    var cod_observacion = select.val();
    var texto = select.find("option:selected").text();

    if (cod_observacion && $("#obs_" + cod_observacion).length === 0) {
        var html = `
        <div class="mb-3" id="obs_${cod_observacion}">
            <label><b>${texto}</b></label>
            <textarea class="form-control mb-1" name="observacion_${cod_observacion}" placeholder="Detalle de la observación"></textarea>
            <button type="button" class="btn btn-danger btn-sm" onclick="$('#obs_${cod_observacion}').remove()">Quitar</button>
            <input type="hidden" name="cod_observacion[]" value="${cod_observacion}">
        </div>
        `;
        $("#observaciones_container").append(html);
    }
});

function cedulaExisteEnListado(cedula) {
    var existe = false;
    $("#listadopacientes tr").each(function() {
        // La cédula está en la segunda columna (td:eq(1))
        if ($(this).find("td:eq(1)").text() === cedula) {
            existe = true;
            return false; // Detiene el each
        }
    });
    return existe;
}

function cedulaExisteEnListado2(cedula) {
    var existe = false;
    $("#listadopersonal tr").each(function() {
        // La cédula está en la segunda columna (td:eq(1))
        if ($(this).find("td:eq(1)").text() === cedula) {
            existe = true;
            return false; // Detiene el each
        }
    });
    return existe;
}

function pone(pos, accion) {
    // Habilitar/deshabilitar campos según la acción
    const readonly = accion === 1 || accion === 2; // Solo editable para modificar (acción 0) e incluir (acción 3)
    
    $("#fechaconsulta").prop("readonly", readonly);
    $("#Horaconsulta").prop("readonly", readonly);
    $("#consulta").prop("readonly", readonly);
    $("#diagnostico").prop("readonly", readonly);
    $("#tratamientos").prop("readonly", readonly);
    $("#cedula_personal").prop("readonly", readonly);
    $("#cedula_paciente").prop("readonly", readonly);
    $("#nom_observaciones").prop("readonly", readonly);
    $("#proceso1").prop("cerrar", false);

    // Elementos del DOM
    const boton_h = document.querySelector('#listadodepacientes');
    const boton_p = document.querySelector('#listadodepersonal');
    const linea = $(pos).closest('tr');

    // Configuración según el tipo de acción
    switch (accion) {
        case 0: // MODIFICAR
            $("#proceso").text("MODIFICAR");
            boton_h.style.display = '';
            boton_p.style.display = '';
            limpiarm();
            
            // Datos del paciente
            $("#cedula_paciente").val($(pos).attr('cedula_paciente'));
            $("#datosdelpacientes").html(
                `Nombre: ${$(pos).attr('nombre_h')} / Apellido: ${$(pos).attr('apellido_h')}`
            );

            // Datos del personal
            $("#cedula_personal").val($(pos).attr('cedula_personal'));
            $("#datosdelpersonal").html(
                `Nombre: ${$(pos).attr('nombre')} / Apellido: ${$(pos).attr('apellido')} / Cargo: ${$(pos).attr('cargo')}`
            );
            
            // Cargar observaciones
            cargarObservacionesConsulta($(pos).attr('cod_consulta'));
            $('#bloque_agregar_observacion').show();
            break;

        case 1: // ELIMINAR
            $("#proceso").text("ELIMINAR");
            boton_h.style.display = 'none';
            boton_p.style.display = 'none';
            limpiarm();
            
            // Datos del paciente
            $("#cedula_paciente").val($(pos).attr('cedula_paciente'));
            $("#datosdelpacientes").html(
                `Nombre: ${$(pos).attr('nombre_h')} / Apellido: ${$(pos).attr('apellido_h')}`
            );

            // Datos del personal
            $("#cedula_personal").val($(pos).attr('cedula_personal'));
            $("#datosdelpersonal").html(
                `Nombre: ${$(pos).attr('nombre')} / Apellido: ${$(pos).attr('apellido')} / Cargo: ${$(pos).attr('cargo')}`
            );

            // Cargar observaciones y ocultar controles
            cargarObservacionesConsulta($(pos).attr('cod_consulta'));
            $('#bloque_agregar_observacion').hide();
            setTimeout(function() {  
                $('.bt_quitar').hide();        
                $('.tex_o').attr('readonly', true);
            }, 50);
            break;

        case 2: // CERRAR
            $("#proceso").text("CERRAR");
            boton_h.style.display = 'none';
            boton_p.style.display = 'none';
            limpiarm();
            
            // Datos del paciente
            $("#cedula_paciente").val($(pos).attr('cedula_paciente'));
            $("#datosdelpacientes").html(
                `Nombre: ${$(pos).attr('nombre_h')} / Apellido: ${$(pos).attr('apellido_h')}`
            );

            // Datos del personal
            $("#cedula_personal").val($(pos).attr('cedula_personal'));
            $("#datosdelpersonal").html(
                `Nombre: ${$(pos).attr('nombre')} / Apellido: ${$(pos).attr('apellido')} / Cargo: ${$(pos).attr('cargo')}`
            );

            // Cargar observaciones y ocultar controles
            cargarObservacionesConsulta($(pos).attr('cod_consulta'));
            $('#bloque_agregar_observacion').hide();
            setTimeout(function() {  
                $('.bt_quitar').hide();        
                $('.tex_o').attr('readonly', true);
            }, 50);
            break;

        case 3: // INCLUIR
            $("#proceso").text("INCLUIR");
            boton_h.style.display = '';
            boton_p.style.display = '';
            limpiarm();
			 $('#bloque_agregar_observacion').show();
            break;

        case 4: // AGREGAR OBSERVACIÓN
            $("#cod_observacion").val($(linea).find("td:eq(0)").text());
            $("#nom_observaciones").val($(pos).attr('nom_observaciones'));
            $("#proceso2").text("agregar");
            $("#modal2").modal("show");
            break;

        case 5: // DESCARTAR OBSERVACIÓN
            $("#cod_observacion").val($(linea).find("td:eq(0)").text());
            $("#nom_observaciones").val($(pos).attr('nom_observaciones'));
            $("#nom_observaciones").prop("readonly", true);
            $("#proceso2").text("descartar");
            $("#modal2").modal("show");
            break;

        case 6: // ACTUALIZAR OBSERVACIÓN
            $("#cod_observacion").val($(linea).find("td:eq(0)").text());
            $("#nom_observaciones").val($(pos).attr('nom_observaciones'));
            $("#proceso2").text("actualizar");
            $("#modal2").modal("show");
            break;

        default:
            console.error("Acción no reconocida:", accion);
            alert("Error: Acción no válida");
    }

    // Establecer valores en los campos del formulario para acciones 0-3
    if ([0, 1, 2, 3].includes(accion)) {
        $("#cod_consulta").val($(linea).find("td:eq(0)").text());
        $("#fechaconsulta").val($(pos).attr('fechaconsulta'));
        $("#Horaconsulta").val($(pos).attr('Horaconsulta'));
        $("#consulta").val($(pos).attr('consulta'));
        $("#diagnostico").val($(pos).attr('diagnostico'));
        $("#tratamientos").val($(pos).attr('tratamientos'));
        $("#cedula_personal").val($(pos).attr('cedula_personal'));
        $("#cedula_paciente").val($(pos).attr('cedula_paciente'));
        $("#modal1").modal("show");
    }
}



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
    timeout: 10000, 
    success: function (respuesta) {
     console.log(respuesta);
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "consultar") {
		   destruyeDT();	
		   var html="";
		   lee.datos.forEach(function (fila) {
			html += `<tr>

				<td style="display:none;">${fila.cod_consulta}</td>
				<td>${fila.nombre_h}</td>
				<td>${fila.apellido_h}</td>
				<td>${fila.cedula_paciente}</td>
				<td>${fila.fechaconsulta}</td>
				<td>${fila.Horaconsulta}</td>
				<td>${fila.nombre}</td>
				<td>${fila.apellido}</td>
				<td>${fila.cedula_personal}</td>

				<td>
					<div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
						<a type="button" class="btn btn-success" onclick="pone(this,0)"
							fechaconsulta="${fila.fechaconsulta}"
							Horaconsulta="${fila.Horaconsulta}"
							consulta="${fila.consulta}"
							diagnostico="${fila.diagnostico}"
							tratamientos="${fila.tratamientos}"
							cedula_personal="${fila.cedula_personal}"
							cedula_paciente="${fila.cedula_paciente}"
							cargo="${fila.cargo}"
							nombre="${fila.nombre}"
							apellido="${fila.apellido}"
							nombre_h="${fila.nombre_h}"
							apellido_h="${fila.apellido_h}"
							cod_consulta="${fila.cod_consulta}">
							<img src="img/lapiz.svg" style="width: 20px">
						</a>
						<a type="button" class="btn btn-danger" onclick="pone(this,1)"
							fechaconsulta="${fila.fechaconsulta}"
							Horaconsulta="${fila.Horaconsulta}"
							consulta="${fila.consulta}"
							diagnostico="${fila.diagnostico}"
							tratamientos="${fila.tratamientos}"
							cedula_personal="${fila.cedula_personal}"
							cedula_paciente="${fila.cedula_paciente}"
							cargo="${fila.cargo}"
							nombre="${fila.nombre}"
							apellido="${fila.apellido}"
							nombre_h="${fila.nombre_h}"
							apellido_h="${fila.apellido_h}"
							cod_consulta="${fila.cod_consulta}">
							<img src="img/basura.svg" style="width: 20px">
						</a>
						<a type="button" class="btn btn-primary" onclick="pone(this,2)"
							fechaconsulta="${fila.fechaconsulta}"
							Horaconsulta="${fila.Horaconsulta}"
							consulta="${fila.consulta}"
							diagnostico="${fila.diagnostico}"
							tratamientos="${fila.tratamientos}"
							cedula_personal="${fila.cedula_personal}"
							cedula_paciente="${fila.cedula_paciente}"
							cargo="${fila.cargo}"
							nombre="${fila.nombre}"
							apellido="${fila.apellido}"
							nombre_h="${fila.nombre_h}"
							apellido_h="${fila.apellido_h}"
							cod_consulta="${fila.cod_consulta}">
							<img src="img/ojo.svg" style="width: 20px">
						</a>
						<a class="btn btn-danger" href="vista/fpdf/consultasm.php?cod_consulta=${fila.cod_consulta}" target="_blank">
							<img src="img/descarga.svg" style="width: 20px;">
						</a>
					</div>
				</td>

			</tr>`;
		});
		
		$("#resultadoconsulta").html(html);
		crearDT();
        }
		else if(lee.resultado=='listadopersonal'){
			destruyeDT2();
			var html = '';
			lee.datos.forEach(function(fila) {
				html += `<tr onclick="colocapersonal(this)">
					<td style="display:none;">${fila.cedula_personal}</td>
					<td>${fila.cedula_personal}</td>
					<td>${fila.nombre}</td>
					<td>${fila.apellido}</td>
					<td>${fila.cargo}</td>
				</tr>`;
			});
			$("#listadopersonal").html(html);
			crearDT2();	
			
		}
		else if(lee.resultado=='listadopacientes'){
			
			destruyeDT1();
			var html = '';
			lee.datos.forEach(function(fila) {
				html += `<tr onclick="colocapacientes(this)">
					<td style="display:none;">${fila.cedula_paciente}</td>
					<td>${fila.cedula_paciente}</td>
					<td>${fila.nombre}</td>
					<td>${fila.apellido}</td>
				</tr>`;
			});
			$("#listadopacientes").html(html);
			crearDT1();
		}
		else if(lee.resultado=='listado_observaciones'){
			
			destruyeDT3();
			var html = '';
			lee.datos.forEach(function(fila) {
				html += `<tr onclick="colocapacientes(this)">
					<td style="display:none;">${fila.cod_observacion}</td>
					<td>${fila.nom_observaciones}</td>
						<td>
						<div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
							<a type="button" class="btn btn-success" onclick="pone(this,6)"
								nom_observaciones="${fila.nom_observaciones}"
								cod_observacion="${fila.cod_observacion}">
								<img src="img/lapiz.svg" style="width: 20px">
							</a>
							<a type="button" class="btn btn-danger" onclick="pone(this,5)"
								cod_observacion="${fila.cod_observacion}"
								nom_observaciones="${fila.nom_observaciones}">
								<img src="img/basura.svg" style="width: 20px">
							</a>
							
						</div>
					</td>
				</tr>`;
			});
			$("#listado_observaciones").html(html);
			crearDT3();

			var $select = $("#select_observacion");
			$select.empty();
			$select.append('<option value="" disabled selected>Seleccione una observación</option>');
			lee.datos.forEach(function(o) {
				$select.append(
					`<option value="${o.cod_observacion}">${o.nom_observaciones}</option>`
				);
			});
		}
		else if (lee.resultado == "incluir") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Incluido'){
			   $("#modal1").modal("hide");
			   consultar();
		   }
        }
		else if (lee.resultado == "agregar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Incluido'){
			   $("#modal2").modal("hide");
			   carga_observaciones();
		   }
        }
		else if (lee.resultado == "modificar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Modificado'){
			   $("#modal1").modal("hide");
			   consultar();
		   }
        }
		else if (lee.resultado == "actualizar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Modificado'){
			   $("#modal2").modal("hide");
			   carga_observaciones();
		   }   
        }
		else if (lee.resultado == "eliminar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Eliminado'){
			   $("#modal1").modal("hide");
			   consultar();
		   }
        }
		else if (lee.resultado === "obtener_observaciones_consulta") {
			$("#observaciones_container").empty();
			lee.datos.forEach(function(obs) {
				var html = `
				<div class="mb-3" id="obs_${obs.cod_observacion}">
					<label><b>${obs.nom_observaciones}</b></label>
					<textarea class="form-control mb-1 tex_o " name="observacion_${obs.cod_observacion}" placeholder="Detalle de la observación">${obs.observacion}</textarea>	
					<button type="button" class="btn btn-danger btn-sm bt_quitar" onclick="$('#obs_${obs.cod_observacion}').remove()">Quitar</button>			
					<input type="hidden" name="cod_observacion[]" value="${obs.cod_observacion}">
				</div>
				`;
				
				$("#observaciones_container").append(html);
			});
		}
		else if (lee.resultado == "descartar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Eliminado'){
			   $("#modal2").modal("hide");
			   carga_observaciones();
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

function limpia(){
	$("#cod_consulta").val("");
	$("#fechaconsulta").val("");
	$("#Horaconsulta").val("");
	$("#consulta").val("");
	$("#diagnostico").val("");
	$("#tratamientos").val("");
	$("#cedula_personal").val("");
	$("#cedula_paciente").val("");
	$("#cod_observacion").html("");
	$("#nom_observaciones").val("");
}
function limpiarm(){

	const limpia = document.querySelector('#datosdelpacientes');
	const limpia1 = document.querySelector('#datosdelpersonal');
	limpia.textContent = "";
	limpia1.textContent = "";
	$("#observaciones_container").empty();
    $("#select_observacion").val("");


}


// NOTIFICACIONES
const ws = new WebSocket('ws://localhost:8080');

ws.onopen = function() {
    console.log('Conectado al WebSocket');
};

ws.onclose = function() {
    console.log('WebSocket cerrado');
};

ws.onerror = function(error) {
    console.error('WebSocket error:', error);
};

// Ejemplo: enviar un mensaje (puedes quitar esto si solo quieres recibir)
function enviarNotificacion(msg) {
    ws.send(msg);
}