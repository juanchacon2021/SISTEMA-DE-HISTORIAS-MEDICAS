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

	
	//validar

	$("#cedula_p").on("keyup",function(){
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

	enviaAjax(datos);
}
function carga_pacientes(){
	
	var datos = new FormData();

	datos.append('accion','listadopacientes'); 

	enviaAjax(datos);
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
function colocapersonal(linea){
	$("#cedula_p").val($(linea).find("td:eq(1)").text());
	$("#cedula_personal").val($(linea).find("td:eq(0)").text());
	$("#datosdelpersonal").html("Nombre: "+$(linea).find("td:eq(2)").text()+
	" / Apellido: "+$(linea).find("td:eq(3)").text()+" / Cargo: "+
	$(linea).find("td:eq(4)").text());
}
function colocapacientes(linea){
	$("#cedula_h").val($(linea).find("td:eq(1)").text());
	$("#cedula_historia").val($(linea).find("td:eq(0)").text());
	$("#datosdelpacientes").html("Nombre: "+$(linea).find("td:eq(2)").text()+
	" / Apellido: "+$(linea).find("td:eq(3)").text());
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
	
	linea=$(pos).closest('tr');


	if(accion==0){

		$("#proceso").text("MODIFICAR");
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
	}
	else{
		$("#proceso").text("INCLUIR");
		
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
     console.log(respuesta);
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "consultar") {
		   destruyeDT();	
           $("#resultadoconsulta").html(lee.mensaje);
		   crearDT();
        }
		else if(lee.resultado=='listadopersonal'){
					
			//si el servidor retorno como
			// resultado listadoclientes significa
			// que se obtuvieron datos del json
			// y se colocan esos resultados en la vista
			$('#listadopersonal').html(lee.mensaje);
		}
		else if(lee.resultado=='listadopacientes'){

			$('#listadopacientes').html(lee.mensaje);
		}
		else if (lee.resultado == "incluir") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Inluido'){
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