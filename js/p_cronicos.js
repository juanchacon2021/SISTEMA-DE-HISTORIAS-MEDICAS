function consultar(){
	var datos = new FormData();
	datos.append('accion','consultar');
	enviaAjax(datos);	
}

function carga_patologias(){
	
	var datos = new FormData();

	datos.append('accion','listado_patologias'); 

	enviaAjax(datos);
}

function carga_pacientes(){
	
	var datos = new FormData();

	datos.append('accion','listadopacientes'); 

	enviaAjax(datos);
}

function cargar_patologias_paciente(cedula_paciente) {
    var datos = new FormData();
    datos.append('accion', 'obtener_patologias_paciente');
    datos.append('cedula_paciente', cedula_paciente);
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
//tt
function destruyeDT2(){
	if ($.fn.DataTable.isDataTable("#tabla_Patologias")) {
            $("#tabla_Patologias").DataTable().destroy();
    }
	
	// crearDT1()
}
function crearDT2(){
    if (!$.fn.DataTable.isDataTable("#tabla_Patologias")) {
            $("#tabla_Patologias").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontró ninguna Patología",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay patologías registradas",
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
$(document).ready(function(){

	
	consultar();
	carga_pacientes();
	carga_patologias();

	$("#listadodepacientes").on("click",function(){
		$("#modalpacientes").modal("show");
	});

	$("#listado_patologias").on("click",function(){
		$("#modalopatologias").modal("show");
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


	
	//validar

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
	if ($(this).text() == "INCLUIR") {
		 if (validarenvio()) {
			var datos = new FormData();
			datos.append('accion', 'incluir');
			datos.append('cedula_paciente', $("#cedula_paciente").val());

			$("input[name='cod_patologia[]']").each(function() {
				var cod_patologia = $(this).val();
				datos.append('cod_patologia[]', cod_patologia);
				datos.append('tratamiento_' + cod_patologia, $(`textarea[name='tratamiento_${cod_patologia}']`).val());
				datos.append('administracion_t_' + cod_patologia, $(`textarea[name='administracion_t_${cod_patologia}']`).val());
			});

			enviaAjax(datos);
		}
	}
	else if($(this).text()=="MODIFICAR"){
		if (validarenvio()) {
			var datos = new FormData();
			datos.append('accion', 'modificar');
			datos.append('cedula_paciente', $("#cedula_paciente").val());

			// Recoger todas las patologías del formulario
			$("input[name='cod_patologia[]']").each(function() {
				var cod_patologia = $(this).val();
				datos.append('cod_patologia[]', cod_patologia);
				datos.append('tratamiento_' + cod_patologia, $(`textarea[name='tratamiento_${cod_patologia}']`).val());
				datos.append('administracion_t_' + cod_patologia, $(`textarea[name='administracion_t_${cod_patologia}']`).val());
			});

			enviaAjax(datos);
		}
	}
	else if($(this).text()=="CERRAR"){
	
		$("#modal1").modal("hide");
	}

	else {
		var datos = new FormData();
		datos.append('accion', 'eliminar');
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

$("#proceso2").on("click", function() {
    if ($(this).text() == "agregar") {
        // Validar y enviar datos
        if (validarenvio2()) {
            var datos = new FormData();
            datos.append('accion', 'agregar');
            datos.append('cod_patologia', $("#cod_patologia").val());
            datos.append('nombre_patologia', $("#nombre_patologia").val());
            enviaAjax(datos);
        }
    }
		else if($(this).text()=="actualizar"){
		if(validarenvio2()){
			var datos = new FormData();
			datos.append('accion','actualizar');
			datos.append('cod_patologia',$("#cod_patologia").val());
			datos.append('nombre_patologia',$("#nombre_patologia").val());
			enviaAjax(datos);
		}
	}
	else{
		    var datos = new FormData();
			datos.append('accion','descartar');
			datos.append('cod_patologia',$("#cod_patologia").val());
			enviaAjax(datos);
		}



	$("#agregar").on("click",function(){
		limpia();
		$("#proceso2").text("agregar");
		$("#modal2").modal("show");
	});

});

$("#agregar_patologia").on("click", function() {
    var select = $("#select_patologia");
    var cod_patologia = select.val();
    var texto = select.find("option:selected").text();
    var cedula = $("#cedula_paciente").val(); // Obtiene la cédula seleccionada

   if (cod_patologia && $("#pat_" + cod_patologia).length === 0) {
		var html = `
		<div class="mb-3" id="pat_${cod_patologia}">
			<input type="hidden" name="cedula_paciente[]" class="input_cedula_paciente" value="${cedula}">
			<input type="hidden" name="cod_patologia[]" value="${cod_patologia}">
			<label><b>${texto}</b></label>
			<div class="row mb-2">
				<div class="col">
					<label>Tratamiento:</label>
					<textarea class="form-control mb-1" name="tratamiento_${cod_patologia}" placeholder="Tratamiento"></textarea>
				</div>
				<div class="col">
					<label>Administración:</label>
					<textarea class="form-control mb-1" name="administracion_t_${cod_patologia}" placeholder="Administración del tratamiento"></textarea>
				</div>
			</div>
			<button type="button" class="btn btn-danger btn-sm" onclick="$('#pat_${cod_patologia}').remove()">Quitar</button>
		</div>
		`;
		$("#patologias_container").append(html);
	}
});

$("#cedula_paciente").on("change", function() {
    var nuevaCedula = $(this).val();
    $(".input_cedula_paciente").val(nuevaCedula);
});



function validarenvio(){

	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_paciente"),
		$("#scedula_paciente"),"El formato debe ser 12345678")==0){
	    muestraMensaje("La cedula del paciente debe coincidir con el formato <br/>"+ 
						"12345678");	
		return false;					
	} 

	
	return true;
} 

function validarenvio2(){


	function validarObservacion(){
		if(validarkeyup(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
			$("#nombre_patologia"),
			$("#snombre_patologia"),
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
function actualizarCheckboxes() {
    const checkboxes = document.querySelectorAll("#patologia_options input[type='checkbox']");
    const seleccionadas = Array.from(checkboxes)
      .filter(chk => chk.checked)
      .map(chk => chk.value);
    
    document.getElementById("patologia_cronica").value = seleccionadas.join(", ");
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

// Variable global para saber el modo actual
let modoActual = "";

// Modifica la función pone:
function pone(pos, accion) {
    // Configuración inicial común
    $("#cedula_paciente").prop("readonly", false);
    $("#proceso1").prop("cerrar", false);
    $('#bloque_agregar_patologia').show();
    
    const boton_h = document.querySelector('#listadodepacientes');
    const linea = $(pos).closest('tr');
    
    // Mostrar opciones de patología por defecto
    $("#patologia_options").show();

    // Manejo de acciones
    switch (accion) {
        case 0: // MODIFICAR
            modoActual = "modificar";
            $("#proceso").text("MODIFICAR");
            boton_h.style.display = '';
            limpiarm();
            
            $("#cedula_paciente").val($(pos).attr('cedula_paciente'));
            $("#datosdelpacientes").html(
                `Nombre: ${$(pos).attr('nombre')} / Apellido: ${$(pos).attr('apellido')}`
            );
            
            // Desmarcar checkboxes y cargar patologías
            $("#patologia_options input[type='checkbox']").prop('checked', false);
            cargar_patologias_paciente($(pos).attr('cedula_paciente'));
            break;

        case 1: // ELIMINAR
            modoActual = "eliminar";
            $("#proceso").text("ELIMINAR");
            $("#cedula_paciente").prop("readonly", true);
            $("#patologia_options").hide();
            boton_h.style.display = 'none';
            limpiarm();
            
            $("#cedula_paciente").val($(pos).attr('cedula_paciente'));
            $("#datosdelpacientes").html(
                `Nombre: ${$(pos).attr('nombre')} / Apellido: ${$(pos).attr('apellido')}`
            );
            
            cargar_patologias_paciente($(pos).attr('cedula_paciente'));
            $('#bloque_agregar_patologia').hide();
            
            setTimeout(() => {
                $('.bt_quitar').hide();
                $('.tex_o').attr('readonly', true);
            }, 50);
            break;

        case 2: // CERRAR (VER)
            modoActual = "ver";
            $("#proceso").text("CERRAR");
            $("#cedula_paciente").prop("readonly", true);
            $("#patologia_options").hide();
            boton_h.style.display = 'none';
            limpiarm();
            
            $("#cedula_paciente").val($(pos).attr('cedula_paciente'));
            $("#datosdelpacientes").html(
                `Nombre: ${$(pos).attr('nombre')} / Apellido: ${$(pos).attr('apellido')}`
            );
            
            cargar_patologias_paciente($(pos).attr('cedula_paciente'));
            $('#bloque_agregar_patologia').hide();
            
            setTimeout(() => {
                $('.bt_quitar').hide();
                $('.tex_o').attr('readonly', true);
            }, 50);
            break;

        case 3: // INCLUIR
            modoActual = "incluir";
            $("#proceso").text("INCLUIR");
            boton_h.style.display = '';
            $("#patologia_options input[type='checkbox']").prop('checked', false);
            break;

        case 4: // INCLUIR PATOLOGÍA
            $("#cod_patologia").val($(linea).find("td:eq(0)").text());
            $("#nombre_patologia").val($(pos).attr('nombre_patologia'));
			 $("#nombre_patologia").prop("readonly", false);
            $("#proceso2").text("agregar");
            $("#modal2").modal("show");
            break;

        case 5: // ELIMINAR PATOLOGÍA
            $("#cod_patologia").val($(linea).find("td:eq(0)").text());
            $("#nombre_patologia").val($(pos).attr('nombre_patologia'));
            $("#nombre_patologia").prop("readonly", true);
            $("#proceso2").text("Descartar");
            $("#modal2").modal("show");
            break;

        case 6: // MODIFICAR PATOLOGÍA
            $("#cod_patologia").val($(linea).find("td:eq(0)").text());
            $("#nombre_patologia").val($(pos).attr('nombre_patologia'));
            $("#nombre_patologia").prop("readonly", false);
            $("#proceso2").text("Actualizar");
            $("#modal2").modal("show");
            break;
    }

    // Mostrar modal principal para acciones 0-3
    if ([0, 1, 2, 3].includes(accion)) {
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
     //console.log(respuesta);
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "consultar") {
		   destruyeDT();	
		   var html = '';       

		   lee.datos.forEach(function (fila) {
			   html += `<tr>   
							<td>${fila.cedula_paciente}</td>
							<td>${fila.nombre}</td>
							<td>${fila.apellido}</td>
							<td>${fila.patologias}</td>
							<td>
								<div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
									<a type="button" class="btn btn-success" onclick="pone(this,0)"
										cedula_paciente="${fila.cedula_paciente}"
										nombre="${fila.nombre}"
										apellido="${fila.apellido}">
										<img src="img/lapiz.svg" style="width: 20px">
									</a>
									<a type="button" class="btn btn-danger" onclick="pone(this,1)"
										cedula_paciente="${fila.cedula_paciente}"
										nombre="${fila.nombre}"
										apellido="${fila.apellido}">
										<img src="img/basura.svg" style="width: 20px">
									</a>
									<a type="button" class="btn btn-primary" onclick="pone(this,2)"
										cedula_paciente="${fila.cedula_paciente}"					 
										nombre="${fila.nombre}"
										apellido="${fila.apellido}">
										<img src="img/ojo.svg" style="width: 20px">
									</a>
								</div>
							</td>
						</tr>`;
		   });
		   
		   $("#resultadoconsulta").html(html);
		   crearDT();
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
		
		else if(lee.resultado=='listado_patologias'){
			
			destruyeDT2();
			var html = '';
			lee.datos.forEach(function(fila) {
				html += `<tr onclick="colocapacientes(this)">
					<td style="display:none;">${fila.cod_patologia}</td>
					<td>${fila.nombre_patologia}</td>
						<td>
						<div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
							<a type="button" class="btn btn-success" onclick="pone(this,6)"
								nombre_patologia="${fila.nombre_patologia}"
								cod_patologia="${fila.cod_patologia}">
								<img src="img/lapiz.svg" style="width: 20px">
							</a>
							<a type="button" class="btn btn-danger" onclick="pone(this,5)"
								cod_patologia="${fila.cod_patologia}"
								nombre_patologia="${fila.nombre_patologia}">
								<img src="img/basura.svg" style="width: 20px">
							</a>
							
						</div>
					</td>
				</tr>`;
			});
			$("#listado_patologias").html(html);
			crearDT2();

				var $select = $("#select_patologia");
			$select.empty();
			$select.append('<option value="" disabled selected>Seleccione una patologia</option>');
			lee.datos.forEach(function(o) {
				$select.append(
					`<option value="${o.cod_patologia}">${o.nombre_patologia}</option>`
				);
			});
		}
		else if (lee.resultado === "obtener_patologias_paciente") {
			$("#patologias_container").empty();
			lee.datos.forEach(function(pat) {
				var html = `
				<div class="mb-3" id="pat_${pat.cod_patologia}">
					<input type="hidden" name="cedula_paciente[]" class="input_cedula_paciente" value="${pat.cedula_paciente}">
					<input type="hidden" name="cod_patologia[]" value="${pat.cod_patologia}">
					<label><b>${pat.nombre_patologia}</b></label>
					<div class="row mb-2">
						<div class="col">
							<label>Tratamiento:</label>
							<textarea class="form-control mb-1 tex_o" name="tratamiento_${pat.cod_patologia}" 
								placeholder="Tratamiento">${pat.tratamiento || ''}</textarea>
						</div>
						<div class="col">
							<label>Administración:</label>
							<textarea class="form-control mb-1 tex_o" name="administracion_t_${pat.cod_patologia}" 
								placeholder="Administración del tratamiento">${pat.administracion_t || ''}</textarea>
						</div>
					</div>
					${modoActual === "modificar" ? `<button type="button" class="btn btn-danger btn-sm bt_quitar" onclick="$('#pat_${pat.cod_patologia}').remove()">Quitar</button>` : ""}
				</div>
				`;
				$("#patologias_container").append(html);
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
			   carga_patologias();
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
			   carga_patologias();
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
		else if (lee.resultado == "descartar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Eliminado'){
			   $("#modal2").modal("hide");
			   carga_patologias();
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
	$("#cedula_paciente").val("");
	$("#cod_patologia").html("");
	$("#nombre_patologia").val("");

	

}

function limpiarm(){

	const limpia = document.querySelector('#datosdelpacientes');
	$('#patologias_container').empty();
	$('#select_patologia').val('').prop('selectedIndex', 0);
	limpia.textContent = "";
	$("#scedula_paciente").empty("");


}