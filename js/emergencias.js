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
                zeroRecords: "No se encontró ninguna Emergencia",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay emergencias registradas",
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
//tt
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
                zeroRecords: "No se encontró ninguna Emergencia",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay emergencias registradas",
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
                zeroRecords: "No se encontró ninguna Emergencia",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay emergencias registradas",
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

	$("#listadodepersonal").on("click",function(){
		$("#modalpersonal").modal("show");
	});

	$("#listadodepacientes").on("click",function(){
		$("#modalpacientes").modal("show");
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
							<a style="color: green; text-decoration: underline;" href="?pagina=pacientes&accion=registrar">aquí</a>`)
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
							<a style="color: green; text-decoration: underline;" href="?pagina=personal&accion=registrar">aqui</a>`)
					.css("color", "red");
			}
		} else {
			// Si el formato es incorrecto, asegúrate de que el mensaje sea negro
			$("#scedula_personal").css("color", "black");
		}
	});

	

	

	$("#motingreso").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#motingreso").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#smotingreso"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#diagnostico_e").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#diagnostico_e").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sdiagnostico_e"),"Solo letras  entre 3 y 300 caracteres");
	});
	$("#tratamientos").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#tratamientos").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#stratamientos"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#procedimiento").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#procedimiento").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sprocedimiento"),"Solo letras  entre 3 y 300 caracteres");
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




$("#proceso").on("click",function(){
	if($(this).text()=="INCLUIR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','incluir');

			datos.append('horaingreso',$("#horaingreso").val());
			datos.append('fechaingreso',$("#fechaingreso").val());
			datos.append('motingreso',$("#motingreso").val());
			datos.append('diagnostico_e',$("#diagnostico_e").val());
			datos.append('tratamientos',$("#tratamientos").val());
			datos.append('procedimiento',$("#procedimiento").val());
			datos.append('cedula_personal',$("#cedula_personal").val());
			datos.append('cedula_paciente',$("#cedula_paciente").val());
			enviaAjax(datos);
		}
	}
	else if($(this).text()=="MODIFICAR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','modificar');

			datos.append('horaingreso',$("#horaingreso").val());
			datos.append('fechaingreso',$("#fechaingreso").val());
			datos.append('motingreso',$("#motingreso").val());
			datos.append('diagnostico_e',$("#diagnostico_e").val());
			datos.append('tratamientos',$("#tratamientos").val());
			datos.append('procedimiento',$("#procedimiento").val());
			datos.append('cedula_personal',$("#cedula_personal").val());
			datos.append('cedula_paciente',$("#cedula_paciente").val());

			datos.append('old_cedula_paciente', $("#old_cedula_paciente").val());
			datos.append('old_cedula_personal', $("#old_cedula_personal").val());
			datos.append('old_fechaingreso', $("#old_fechaingreso").val());
			datos.append('old_horaingreso', $("#old_horaingreso").val());
			enviaAjax(datos);
		}
	}
	else if($(this).text()=="CERRAR"){
	
		$("#modal1").modal("hide");
	}

	else{
		    var datos = new FormData();
			datos.append('accion','eliminar');
			datos.append('horaingreso', $("#horaingreso").val());
			datos.append('fechaingreso', $("#fechaingreso").val());
			datos.append('cedula_personal', $("#cedula_personal").val());
			datos.append('cedula_paciente', $("#cedula_paciente").val());
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
    const readonly = accion === 1 || accion === 2; // Solo editable para modificar (acción 0)
    
    $("#horaingreso").prop("readonly", readonly);
    $("#fechaingreso").prop("readonly", readonly);
    $("#motingreso").prop("readonly", readonly);
    $("#diagnostico_e").prop("readonly", readonly);
    $("#tratamientos").prop("readonly", readonly);
    $("#procedimiento").prop("readonly", readonly);
    $("#cedula_personal").prop("readonly", readonly);
    $("#cedula_paciente").prop("readonly", readonly);
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
            
            // Valores antiguos para posible comparación
            $("#old_cedula_paciente").val($(pos).attr('cedula_paciente'));
            $("#old_cedula_personal").val($(pos).attr('cedula_personal'));
            $("#old_fechaingreso").val($(pos).attr('fechaingreso'));
            $("#old_horaingreso").val($(pos).attr('horaingreso'));
            break;

        case 1: // ELIMINAR
            $("#proceso").text("ELIMINAR");
            boton_h.style.display = 'none';
            boton_p.style.display = 'none';
            limpiarm();
            console.log($(pos).attr('cargo'));
            
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
            break;
		case 3:
			  $("#proceso").text("INCLUIR");
            boton_h.style.display = '';
            boton_p.style.display = '';
			break;	

        default: // INCLUIR (acción no especificada)
          console.error("Acción no reconocida:", accion);
            alert("Error: Acción no válida");
            
    }

    // Establecer valores en los campos del formulario
    $("#horaingreso").val($(pos).attr('horaingreso'));
    $("#fechaingreso").val($(pos).attr('fechaingreso'));
    $("#motingreso").val($(pos).attr('motingreso'));
    $("#diagnostico_e").val($(pos).attr('diagnostico_e'));
    $("#tratamientos").val($(pos).attr('tratamientos'));
    $("#procedimiento").val($(pos).attr('procedimiento'));
    $("#cedula_personal").val($(pos).attr('cedula_personal'));
    $("#cedula_paciente").val($(pos).attr('cedula_paciente'));

    // Mostrar el modal
    $("#modal1").modal("show");
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
     //console.log(respuesta);
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "consultar") {
		   destruyeDT();	
		   var html = '';       

		   lee.datos.forEach(function (fila) {
			   html += `<tr>

				   <td>${fila.nombre_h}</td>
				   <td>${fila.apellido_h}</td>
				   <td>${fila.horaingreso}</td>
				   <td>${fila.fechaingreso}</td>
				   <td>${fila.cedula_paciente}</td>
				   <td>${fila.nombre}</td>
				   <td>${fila.apellido}</td>
				   <td>${fila.cedula_personal}</td>

				   <td>
					   <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
						   <a type="button" class="btn btn-success modificar" onclick="pone(this,0)"
							   horaingreso="${fila.horaingreso}"
							   fechaingreso="${fila.fechaingreso}"
							   motingreso="${fila.motingreso}"
							   diagnostico_e="${fila.diagnostico_e}"
							   tratamientos="${fila.tratamientos}"
							   procedimiento="${fila.procedimiento}"
							   cedula_personal="${fila.cedula_personal}"
							   cedula_paciente="${fila.cedula_paciente}"
							   cargo="${fila.cargo}"
							   nombre="${fila.nombre}"
							   apellido="${fila.apellido}"
							   nombre_h="${fila.nombre_h}"
							   apellido_h="${fila.apellido_h}">
							   <img src="img/lapiz.svg" style="width: 20px">
						   </a>
						   <a type="button" class="btn btn-danger eliminar" onclick="pone(this,1)"
							   horaingreso="${fila.horaingreso}"
							   fechaingreso="${fila.fechaingreso}"
							   motingreso="${fila.motingreso}"
							   diagnostico_e="${fila.diagnostico_e}"
							   tratamientos="${fila.tratamientos}"
							   procedimiento="${fila.procedimiento}"
							   cedula_personal="${fila.cedula_personal}"
							   cedula_paciente="${fila.cedula_paciente}"
							   cargo="${fila.cargo}"
							   nombre="${fila.nombre}"
							   apellido="${fila.apellido}"
							   nombre_h="${fila.nombre_h}"
							   apellido_h="${fila.apellido_h}">
							   <img src="img/basura.svg" style="width: 20px">
						   </a>
						   <a type="button" class="btn btn-primary ver" onclick="pone(this,2)"
							   horaingreso="${fila.horaingreso}"
							   fechaingreso="${fila.fechaingreso}"
							   motingreso="${fila.motingreso}"
							   diagnostico_e="${fila.diagnostico_e}"
							   tratamientos="${fila.tratamientos}"
							   procedimiento="${fila.procedimiento}"
							   cedula_personal="${fila.cedula_personal}"
							   cedula_paciente="${fila.cedula_paciente}"
							   cargo="${fila.cargo}"
							   nombre="${fila.nombre}"
							   apellido="${fila.apellido}"
							   nombre_h="${fila.nombre_h}"
							   apellido_h="${fila.apellido_h}">
							   <img src="img/ojo.svg" style="width: 20px">
						   </a>
						   
						    <a class="btn btn-danger descargar" 
								href="vista/fpdf/emergencias.php?cedula_paciente=${fila.cedula_paciente}&cedula_personal=${fila.cedula_personal}&fechaingreso=${fila.fechaingreso}&horaingreso=${fila.horaingreso}"  
								target="_blank">
									<img src="img/descarga.svg" style="width: 20px;">
							</a>				
						   <!-- Agrega los demás botones aquí -->
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
		else if (lee.resultado == "incluir") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Incluido'){
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
	
	$("#horaingreso").val("");
	$("#fechaingreso").val("");
	$("#motingreso").val("");
	$("#diagnostico_e").val("");
	$("#tratamientos").val("");
	$("#procedimiento").val("");
	$("#cedula_personal").val("");
	$("#cedula_paciente").val("");



}
function limpiarm(){
	const limpia = document.querySelector('#datosdelpacientes');
	const limpia1 = document.querySelector('#datosdelpersonal');
	limpia.textContent = "";
	limpia1.textContent = "";
	$("#shoraingreso").empty("");
	$("#sfechaingreso").empty("");
	$("#smotingreso").empty("");
	$("#sdiagnostico_e").empty("");
	$("#stratamientos").empty("");
	$("#sprocedimiento").empty("");
	$("#cedula_paciente").empty("");
	$("#scedula_personal").empty("");
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

$("#btnTutorial").on("click", function() {
    introJs().setOptions({
        steps: [
            {
                element: document.querySelector('.botonverde'),
                intro: 'Haz click aquí para registrar una nueva emergencia.'
            },
            {
                element: document.querySelector('.botonrojo'),
                intro: 'Haz click aquí para volver al panel principal.'
            },
            {
                element: document.querySelector('.modificar'),
                intro: 'Haz click aqui para modificar la informacion de la emergencia'
            },
            {
                element: document.querySelector('.eliminar'),
                intro: 'Haz click aqui para eliminar la informacion de la emergencia'
            },
            {
                element: document.querySelector('.ver'),
                intro: 'Haz click aqui para ver la informacion de la emergencia'
            },
            {
                element: document.querySelector('.descargar'),
                intro: 'Haz click aqui para descargar en PDF la informacion de la emergencia'
            }
        ],
        showProgress: true,
        exitOnOverlayClick: true,
        showBullets: false,
        nextLabel: 'Siguiente',
        prevLabel: 'Anterior',
        skipLabel: 'X',
        doneLabel: 'Finalizar'
    }).oncomplete(function() {
        localStorage.setItem('tutorialPacientesVisto', 'true');
    }).onexit(function() {
        localStorage.setItem('tutorialPacientesVisto', 'true');
    }).start();
});