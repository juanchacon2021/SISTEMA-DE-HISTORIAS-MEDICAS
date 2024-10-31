
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
                zeroRecords: "No se encontró ningun Examen",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay examenes registrados",
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
			datos.append('cod_examenes',$("#cod_examenes").val());
			datos.append('nombre_examen',$("#nombre_examen").val());
			datos.append('descripcion_examen',$("#descripcion_examen").val());
			datos.append('cedula_h',$("#cedula_h").val());
			enviaAjax(datos);
		}
	}
	
	
});
$("#proceso1").on("click",function(){
	
	if($(this).text()=="INCLUIR REGISTRO"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','incluir1');
			datos.append('cod_registro',$("#cod_registro").val());
			datos.append('fecha_r',$("#fecha_r").val());
			datos.append('cedula_h',$("#cedula_h").val());
			datos.append('cod_examenes1',$("#cod_examenes1").val());
			datos.append('observacion_examen',$("#observacion_examen").val());
			enviaAjax(datos);
		}
	}	
	else if($(this).text()=="MODIFICAR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','modificar');
			datos.append('cod_registro',$("#cod_registro").val());
			datos.append('fecha_r',$("#fecha_r").val());
			datos.append('cedula_h',$("#cedula_h").val());
			datos.append('cod_examenes1',$("#cod_examenes1").val());
			datos.append('observacion_examen',$("#observacion_examen").val());
			enviaAjax(datos);
		}
	}

	else{
		    var datos = new FormData();
			datos.append('accion','eliminar');
			datos.append('cod_registro',$("#cod_registro").val());
			enviaAjax(datos);
		}
	
});
$("#incluir").on("click",function(){
	limpia();
	$("#proceso").text("INCLUIR");
	$("#modal1").modal("show");
});
$("#incluir1").on("click",function(){
	limpia1();
	$("#proceso1").text("INCLUIR REGISTRO");
	$("#modal2").modal("show");
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
		$("#proceso1").text("MODIFICAR");
	
		$("#cod_registro").val($(linea).find("td:eq(1)").text());
		$("#fecha_r").val($(linea).find("td:eq(2)").text());
		$("#observacion_examen").val($(linea).find("td:eq(3)").text());
		$("#cedula_h").val($(linea).find("td:eq(4)").text());
		$("#cod_examenes1").val($(linea).find("td:eq(5)").text());
		$("#modal2").modal("show");
	}
	else if(accion==1){
		$("#proceso1").text("ELIMINAR");
		
		$("#cod_registro").val($(linea).find("td:eq(1)").text());
		$("#fecha_r").val($(linea).find("td:eq(2)").text());
		$("#observacion_examen").val($(linea).find("td:eq(3)").text());
		$("#cedula_h").val($(linea).find("td:eq(4)").text());
		$("#cod_examenes1").val($(linea).find("td:eq(5)").text());
		$("#modal2").modal("show");
	}
	else if(accion==3){
		$("#proceso").text("INCLUIR");
		$("#cod_examenes").val($(linea).find("td:eq(1)").text());
	$("#nombre_examen").val($(linea).find("td:eq(2)").text());
	$("#descripcion_examen").val($(linea).find("td:eq(3)").text());
	$("#cedula_h").val($(linea).find("td:eq(4)").text());
	$("#modal1").modal("show");
	}
	else {
		$("#proceso1").text("INCLUIR REGISTRO");
		$("#cod_registro").val($(linea).find("td:eq(1)").text());
		$("#fecha_r").val($(linea).find("td:eq(2)").text());
		$("#observacion_examen").val($(linea).find("td:eq(3)").text());
		$("#cedula_h").val($(linea).find("td:eq(4)").text());
		$("#cod_examenes1").val($(linea).find("td:eq(5)").text());
		$("#modal2").modal("show");
	}
	
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
		else if (lee.resultado == "incluir1") {
			muestraMensaje(lee.mensaje);
			if(lee.mensaje=='Registro Inluido'){
				$("#modal2").modal("hide");
				consultar();
			}
		 }
		else if (lee.resultado == "modificar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Modificado'){
			   $("#modal2").modal("hide");
			   consultar();
		   }
        }
		else if (lee.resultado == "eliminar") {
           muestraMensaje(lee.mensaje);
		   if(lee.mensaje=='Registro Eliminado'){
			   $("#modal2").modal("hide");
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
	$("#cod_examenes").val("");
	$("#nombre_examen").val("");
	$("#descripcion_examen").val("");
	$("#cedula_h").val("");
}
function limpia1(){
	$("#cod_registro").val("");
	$("#fecha_r").val("");
	$("#cedula_h").val("");
	$("#cod_examenes1").val("");
	$("#observacion_examen").val("");
}