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
                zeroRecords: "No se encontró personal",
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
$(document).ready(function () {
    consultar();

    // VALIDACION DE DATOS
    $("#cedula_personal").on("keypress", function (e) {
        validarkeypress(/^[0-9-\b]*$/, e);
    });

    $("#cedula_personal").on("keyup", function () {
        validarkeyup(/^[0-9]{7,8}$/, $(this), $("#scedula_personal"), "El formato debe ser 12345678 ");
    });

    $("#apellido").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#apellido").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#sapellido"), "Solo letras entre 3 y 30 caracteres");
    });

    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
    });

    $("#correo").on("keypress", function (e) {
        validarkeypress(/^[\w@.-]*$/, e); // Corrected regex for keypress validation
    });

    $("#correo").on("keyup", function () {
        validarkeyup(/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/, $(this), $("#scorreo"), "Formato incorrecto");
    });

    // Validación para el teléfono (ejemplo con formato 04124578987)
    $("#telefono").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e); // Corrected regex for keypress validation
    });

    $("#telefono").on("keyup", function () {
        validarkeyup(/^\d{11}$/, $(this), $("#stelefono"), "Formato del teléfono incorrecto");
    });

    // Validación para la clave (ejemplo con al menos 8 caracteres, una mayúscula, una minúscula y un número)
    $("#clave").on("keypress", function (e) {
        validarkeypress(/^[a-zA-Z0-9]*$/, e); // Corrected regex for keypress validation
    });

    $("#clave").on("keyup", function () {
        validarkeyup(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/, $(this), $("#sclave"), "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número");
    });

    // FIN DE VALIDACION DE DATOS

    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                var datos = new FormData();
                datos.append("accion", "incluir");
                datos.append("cedula_personal", $("#cedula_personal").val());
                datos.append("apellido", $("#apellido").val());
                datos.append("nombre", $("#nombre").val());
                datos.append("correo", $("#correo").val());
                datos.append("telefono", $("#telefono").val());
                datos.append("cargo", $("#cargo").val());
                datos.append("clave", $("#clave").val());
                enviaAjax(datos);
            }
        } else if ($(this).text() == "MODIFICAR") {
            if (validarenvio()) {
                var datos = new FormData();
                datos.append("accion", "modificar");
                datos.append("cedula_personal", $("#cedula_personal").val());
                datos.append("apellido", $("#apellido").val());
                datos.append("nombre", $("#nombre").val());
                datos.append("correo", $("#correo").val());
                datos.append("telefono", $("#telefono").val());
                datos.append("cargo", $("#cargo").val());
                datos.append("clave", $("#clave").val());
                enviaAjax(datos);
            }
        }
        if ($(this).text() == "ELIMINAR") {
            if (
                validarkeyup(/^[0-9]{7,8}$/, $("#cedula_personal"), $("#scedula_personal"), "El formato debe ser 12345678") == 0
            ) {
                muestraMensaje("La cedula debe coincidir con el formato <br/>" + "12345678");
            } else {
                var datos = new FormData();
                datos.append("accion", "eliminar");
                datos.append("cedula_personal", $("#cedula_personal").val());
                enviaAjax(datos);
            }
        }
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#proceso").text("INCLUIR");
        $("#modal1").modal("show");
    });
});

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
	else if(validarkeyup(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/,
		$("#clave"),$("#sclave"),"La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número")==0){
		muestraMensaje("La clave no cumple con el formato");
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

function pone(pos, accion) {
    linea = $(pos).closest('tr');

    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#idclave").hide(); // Oculta el botón con id "idclave"
    } else if (accion == 1) {
        $("#proceso").text("ELIMINAR");
        $("#idclave").hide(); // Muestra el botón con id "idclave"
    } else {
        $("#proceso").text("INCLUIR");
        $("#idclave").show(); // Muestra el botón con id "idclave"
    }

    $("#cedula_personal").val($(linea).find("td:eq(1)").text());
    $("#apellido").val($(linea).find("td:eq(2)").text());
    $("#nombre").val($(linea).find("td:eq(3)").text());
    $("#correo").val($(linea).find("td:eq(4)").text());
    $("#telefono").val($(linea).find("td:eq(5)").text());
    $("#cargo").val($(linea).find("td:eq(6)").text());
    $("#clave").val(accion === 0 || accion === 1 ? '**********' : $(linea).find("td:eq(7)").text());

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
							<button type='button' class='btn btn-success' onclick='pone(this,0)'>
                                <img src='img/lapiz.svg' style='width: 20px'>
                             </button>
							<a class='btn btn-danger' onclick='pone(this,1)'>
                               <img src='img/trash-can-solid.svg' style='width: 20px;'>
                        </a>
						</div>
					</td>
					
					<td>${fila.cedula_personal}</td>
					<td>${fila.apellido}</td>
					<td>${fila.nombre}</td>
					<td>${fila.correo}</td>
					<td>${fila.telefono}</td>
					<td>${fila.cargo}</td>
					
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
	$("#cedula_personal").val("");
	$("#apellido").val("");
	$("#nombre").val("");
	$("#correo").val("");
	$("#telefono").val("");
	$("#cargo").prop("selectedIndex",0);
	$("#clave").val("");
}
