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
	$("#cedula_h").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});
	
	$("#cedula_h").on("keyup",function(){
		validarkeyup(/^[0-9]{7,8}$/,$(this),
		$("#scedula_h"),"El formato debe ser 12345678 ");
	});

	$("#cedula_p").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});
	
	$("#cedula_p").on("keyup",function(){
		validarkeyup(/^[0-9]{7,8}$/,$(this),
		$("#scedula_p"),"El formato debe ser 12345678 ");
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

	
	//validar

	$("#cedula_p").on("keyup",function(){
		var cedula = $(this).val();
		var encontro = false;
		$("#listadopersonal tr").on("click", function () {
			colocapersonal($(this));
		});
		if(!encontro){
			$("#datosdelpersonal").html("");
		}
	});

	$("#cedula_h").on("keyup",function(){
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
			datos.append('cod_emergencia',$("#cod_emergencia").val());
			datos.append('horaingreso',$("#horaingreso").val());
			datos.append('fechaingreso',$("#fechaingreso").val());
			datos.append('motingreso',$("#motingreso").val());
			datos.append('diagnostico_e',$("#diagnostico_e").val());
			datos.append('tratamientos',$("#tratamientos").val());
			datos.append('cedula_p',$("#cedula_p").val());
			datos.append('cedula_h',$("#cedula_h").val());
			enviaAjax(datos);
		}
	}
	else if($(this).text()=="MODIFICAR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','modificar');
			datos.append('cod_emergencia',$("#cod_emergencia").val());
			datos.append('horaingreso',$("#horaingreso").val());
			datos.append('fechaingreso',$("#fechaingreso").val());
			datos.append('motingreso',$("#motingreso").val());
			datos.append('diagnostico_e',$("#diagnostico_e").val());
			datos.append('tratamientos',$("#tratamientos").val());
			datos.append('cedula_p',$("#cedula_p").val());
			datos.append('cedula_h',$("#cedula_h").val());
			enviaAjax(datos);
		}
	}
	else if($(this).text()=="CERRAR"){
	
		$("#modal1").modal("hide");
	}

	else{
		    var datos = new FormData();
			datos.append('accion','eliminar');
			datos.append('cod_emergencia',$("#cod_emergencia").val());
			enviaAjax(datos);
		}
	
});
$("#incluir").on("click",function(){
	limpia();
	$("#proceso").text("INCLUIR");
	$("#modal1").modal("show");
});





	
	
});

function carga_personal(){

	var datos = new FormData();

	datos.append('accion','listadopersonal'); 
	// destruyeDT1();
	// crearDT1();

	enviaAjax(datos);
}
function carga_pacientes(){
	
	var datos = new FormData();

	datos.append('accion','listadopacientes'); 

	enviaAjax(datos);
}
function limpiarm(){

	const limpia = document.querySelector('#datosdelpacientes');
	const limpia1 = document.querySelector('#datosdelpersonal');
	limpia.textContent = "";
	limpia1.textContent = "";



}


function validarenvio(){

	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_h"),
		$("#scedula_h"),"El formato debe ser 12345678")==0){
	    muestraMensaje("La cedula del paciente debe coincidir con el formato <br/>"+ 
						"12345678");	
		return false;					
	}

	else if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_p"),
	$("#scedula_p"),"El formato debe ser 12345678")==0){
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
    $("#cedula_p").val(cedula);
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
    $("#cedula_h").val(cedula);
    $("#datosdelpacientes").html(
        `Nombre: ${nombre} / Apellido: ${apellido}`
    );

    // Cierra el modal
    $("#modalpacientes").modal("hide");
}




function pone(pos,accion){
	$("#horaingreso").prop("readonly",false);
	$("#fechaingreso").prop("readonly",false);
	$("#motingreso").prop("readonly",false);
	$("#diagnostico_e").prop("readonly",false);
	$("#tratamientos").prop("readonly",false);
	$("#cedula_p").prop("readonly",false);
	$("#cedula_h").prop("readonly",false);
	$("#proceso1").prop("cerrar",false); 
	const boton_h = document.querySelector('#listadodepacientes');
	const boton_p = document.querySelector('#listadodepersonal');
	
	linea=$(pos).closest('tr');


	if(accion==0){

		$("#proceso").text("MODIFICAR");
		boton_h.style.display = '';
		boton_p.style.display = '';
		limpiarm();
		colocapacientes(linea)
		colocapersonal(linea)
	}
	else if (accion==1){
		$("#horaingreso").prop("readonly",true);
		$("#fechaingreso").prop("readonly",true);
		$("#motingreso").prop("readonly",true);
		$("#diagnostico_e").prop("readonly",true);
		$("#tratamientos").prop("readonly",true);
		$("#cedula_p").prop("readonly",true);
		$("#cedula_h").prop("readonly",true);
		$("#proceso").text("ELIMINAR");

		
		boton_h.style.display = 'none';
		boton_p.style.display = 'none';

		limpiarm();
		colocapacientes(linea)
		colocapersonal(linea)
		console.log($(pos).attr('cargo'))

		
	}
	else if (accion==2){
		$("#horaingreso").prop("readonly",true);
		$("#fechaingreso").prop("readonly",true);
		$("#motingreso").prop("readonly",true);
		$("#diagnostico_e").prop("readonly",true);
		$("#tratamientos").prop("readonly",true);
		$("#cedula_p").prop("readonly",true);
		$("#cedula_h").prop("readonly",true);
		$("#proceso").text("CERRAR");

		
		boton_h.style.display = 'none';
		boton_p.style.display = 'none';


		limpiarm();
		colocapacientes(linea)
		colocapersonal(linea)
		
	}
	else{
		$("#proceso").text("INCLUIR");
		boton_h.style.display = '';
		boton_p.style.display = '';
		
	}
	$("#cod_emergencia").val($(linea).find("td:eq(1)").text());
	$("#horaingreso").val( $(pos).attr('horaingreso') );
	$("#fechaingreso").val( $(pos).attr('fechaingreso') );
	$("#motingreso").val( $(pos).attr('motingreso') );
	$("#diagnostico_e").val( $(pos).attr('diagnostico_e') );
	$("#tratamientos").val( $(pos).attr('tratamientos') );
	$("#cedula_p").val( $(pos).attr('cedula_p') );
	$("#cedula_h").val( $(pos).attr('cedula_h') );


	
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
				   <td>
					   <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
						   <a type="button" class="btn btn-success" onclick="pone(this,0)"
							   horaingreso="${fila.horaingreso}"
							   fechaingreso="${fila.fechaingreso}"
							   motingreso="${fila.motingreso}"
							   diagnostico_e="${fila.diagnostico_e}"
							   tratamientos="${fila.tratamientos}"
							   cedula_p="${fila.cedula_p}"
							   cedula_h="${fila.cedula_h}"
							   cargo="${fila.cargo}"
							   nombre="${fila.nombre}"
							   apellido="${fila.apellido}">
							   <img src="img/lapiz.svg" style="width: 20px">
						   </a>
						   <a type="button" class="btn btn-danger" onclick="pone(this,1)"
							   horaingreso="${fila.horaingreso}"
							   fechaingreso="${fila.fechaingreso}"
							   motingreso="${fila.motingreso}"
							   diagnostico_e="${fila.diagnostico_e}"
							   tratamientos="${fila.tratamientos}"
							   cedula_p="${fila.cedula_p}"
							   cedula_h="${fila.cedula_h}"
							   cargo="${fila.cargo}"
							   nombre="${fila.nombre}"
							   apellido="${fila.apellido}">
							   <img src="img/basura.svg" style="width: 20px">
						   </a>
						   <a type="button" class="btn btn-primary" onclick="pone(this,2)"
							   horaingreso="${fila.horaingreso}"
							   fechaingreso="${fila.fechaingreso}"
							   motingreso="${fila.motingreso}"
							   diagnostico_e="${fila.diagnostico_e}"
							   tratamientos="${fila.tratamientos}"
							   cedula_p="${fila.cedula_p}"
							   cedula_h="${fila.cedula_h}"
							   cargo="${fila.cargo}"
							   nombre="${fila.nombre}"
							   apellido="${fila.apellido}">
							   <img src="img/ojo.svg" style="width: 20px">
						   </a>
						   
						    <a class="btn btn-danger" href="vista/fpdf/emergencias.php?cod_emergencia=${fila.cod_emergencia}" target="_blank">
								<img src="img/descarga.svg" style="width: 20px;">
							</a>					
						   <!-- Agrega los demás botones aquí -->
					   </div>
				   </td>
				   <td style="display:none;">${fila.cod_emergencia}</td>
				   <td>${fila.nombre_h}</td>
				   <td>${fila.apellido_h}</td>
				   <td>${fila.horaingreso}</td>
				   <td>${fila.fechaingreso}</td>
				   <td>${fila.cedula_h}</td>
				   <td>${fila.nombre}</td>
				   <td>${fila.apellido}</td>
				   <td>${fila.cedula_p}</td>
			   </tr>`;
		   });
		   
		   $("#resultadoconsulta").html(html);
		   crearDT();
        }
		
		else if(lee.resultado=='listadopersonal'){
			destruyeDT2();
			
			$('#listadopersonal').html(lee.mensaje);
			crearDT2();
			
		}
		else if(lee.resultado=='listadopacientes'){
			destruyeDT1();
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
	$("#cod_emergencia").val("");
	$("#horaingreso").val("");
	$("#fechaingreso").val("");
	$("#motingreso").val("");
	$("#diagnostico_e").val("");
	$("#tratamientos").val("");
	$("#cedula_p").val("");
	$("#cedula_h").val("");

}