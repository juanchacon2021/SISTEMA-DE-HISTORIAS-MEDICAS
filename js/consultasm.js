
function consultar(){
	var datos = new FormData();
	datos.append('accion','consultar');
	enviaAjax(datos);	
}
function destruyeDT(){
	//1 se destruye el datatablet
	if ($.fn.DataTable.isDataTable("#tablapersonal")) {
            $("#tablapersonal").DataTable().destroy();
    }
}
function crearDT(){
	//se crea nuevamente
    if (!$.fn.DataTable.isDataTable("#tablapersonal")) {
            $("#tablapersonal").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontró ninguna Consulta Médica",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay consultas médicas registradas",
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
	
	//ejecuta una consulta a la base de datos para llenar la tabla
	consultar();
	
//VALIDACION DE DATOS	
	
	
//FIN DE VALIDACION DE DATOS



//CONTROL DE BOTONES
$("#proceso").on("click",function(){
	if($(this).text()=="INCLUIR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','incluir');
			datos.append('cod_consulta',$("#cod_consulta").val());
			datos.append('fechaconsulta',$("#fechaconsulta").val());
			datos.append('diagnostico',$("#diagnostico").val());
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
			datos.append('cod_consulta',$("#cod_consulta").val());
			datos.append('fechaconsulta',$("#fechaconsulta").val());
			datos.append('diagnostico',$("#diagnostico").val());
			datos.append('tratamientos',$("#tratamientos").val());
			datos.append('cedula_p',$("#cedula_p").val());
			datos.append('cedula_h',$("#cedula_h").val());
			enviaAjax(datos);
		}
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

//Validación de todos los campos antes del envio
function validarenvio(){
	
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
	else if (accion==1){
		$("#proceso").text("ELIMINAR");
	}
	else{
		$("#proceso").text("INCLUIR");
	}
	$("#cod_consulta").val($(linea).find("td:eq(1)").text());
	$("#fechaconsulta").val($(linea).find("td:eq(2)").text());
	$("#diagnostico").val($(linea).find("td:eq(3)").text());
	$("#tratamientos").val($(linea).find("td:eq(4)").text());
	$("#cedula_h").val($(linea).find("td:eq(5)").text());
	$("#cedula_p").val($(linea).find("td:eq(6)").text());
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
      // si ocurrio un error en la trasmicion
      // o recepcion via ajax entra aca
      // y se muestran los mensaje del error
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
}

function limpia(){
	$("#cod_consulta").val("");
	$("#fechaconsulta").val("");
	$("#diagnostico").val("");
	$("#tratamientos").val("");
	$("#cedula_p").val("");
	$("#cedula_h").val("");

}
