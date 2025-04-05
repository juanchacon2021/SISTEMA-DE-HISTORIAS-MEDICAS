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
	if ($.fn.DataTable.isDataTable("#tablahistorias")) {
            $("#tablahistorias").DataTable().destroy();
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

function limpiarm(){

	const limpia = document.querySelector('#datosdelpacientes');
	const limpia1 = document.querySelector('#datosdelpersonal');
	limpia.textContent = "";
	limpia1.textContent = "";
	


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
	
	$("#psicosocial").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#psicosocial").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#spsicosocial"),"Solo letras  entre 3 y 300 caracteres");
	});
   
	$("#oidos").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#oidos").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#soidos"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#cabeza_craneo").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#cabeza_craneo").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#scabeza_craneo"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#ojos").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#ojos").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sojos"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#nariz").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#nariz").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#snariz"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#boca_abierta").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#boca_abierta").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sboca_abierta"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#boca_cerrada").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#boca_cerrada").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sboca_cerrada"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#cardiovascular").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#cardiovascular").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#scardiovascular"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#respiratorio").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#respiratorio").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#srespiratorio"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#abdomen").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#abdomen").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sabdomen"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#extremidades_s").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#extremidades_s").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sextremidades_s"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#extremidades_r").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#extremidades_r").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sextremidades_r"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#neurologicos").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#neurologicos").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sneurologicos"),"Solo letras  entre 3 y 300 caracteres");
	});

	$("#general").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
   
	$("#general").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#sgeneral"),"Solo letras  entre 3 y 300 caracteres");
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
			datos.append('cod_consulta',$("#cod_consulta").val());
			datos.append('fechaconsulta',$("#fechaconsulta").val());
			datos.append('consulta',$("#consulta").val());
			datos.append('diagnostico',$("#diagnostico").val());
			datos.append('tratamientos',$("#tratamientos").val());
			datos.append('cedula_p',$("#cedula_p").val());
			datos.append('cedula_h',$("#cedula_h").val());
			datos.append('boca_abierta',$("#boca_abierta").val());
			datos.append('boca_cerrada',$("#boca_cerrada").val());
			datos.append('oidos',$("#oidos").val());
			datos.append('cabeza_craneo',$("#cabeza_craneo").val());
			datos.append('ojos',$("#ojos").val());
			datos.append('nariz',$("#nariz").val());
			datos.append('respiratorio',$("#respiratorio").val());
			datos.append('abdomen',$("#abdomen").val());
			datos.append('extremidades_s',$("#extremidades_s").val());
			datos.append('extremidades_r',$("#extremidades_r").val());
			datos.append('neurologicos',$("#neurologicos").val());
			datos.append('general',$("#general").val());
			datos.append('cardiovascular',$("#cardiovascular").val());
			enviaAjax(datos);
			
		}
	}
	else if($(this).text()=="MODIFICAR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','modificar');
			datos.append('cod_consulta',$("#cod_consulta").val());
			datos.append('fechaconsulta',$("#fechaconsulta").val());
			datos.append('consulta',$("#consulta").val());
			datos.append('diagnostico',$("#diagnostico").val());
			datos.append('tratamientos',$("#tratamientos").val());
			datos.append('cedula_p',$("#cedula_p").val());
			datos.append('cedula_h',$("#cedula_h").val());
			datos.append('boca_abierta',$("#boca_abierta").val());
			datos.append('boca_cerrada',$("#boca_cerrada").val());
			datos.append('oidos',$("#oidos").val());
			datos.append('cabeza_craneo',$("#cabeza_craneo").val());
			datos.append('ojos',$("#ojos").val());
			datos.append('nariz',$("#nariz").val());
			datos.append('respiratorio',$("#respiratorio").val());
			datos.append('abdomen',$("#abdomen").val());
			datos.append('extremidades_s',$("#extremidades_s").val());
			datos.append('extremidades_r',$("#extremidades_r").val());
			datos.append('neurologicos',$("#neurologicos").val());
			datos.append('general',$("#general").val());
			datos.append('cardiovascular',$("#cardiovascular").val());
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
	$("#fechaconsulta").prop("readonly",false);
	$("#consulta").prop("readonly",false);
	$("#diagnostico").prop("readonly",false);
	$("#tratamientos").prop("readonly",false);
	$("#cedula_p").prop("readonly",false);
	$("#cedula_h").prop("readonly",false);
	$("#boca_abierta").prop("readonly",false);
	$("#boca_cerrada").prop("readonly",false);
	$("#oidos").prop("readonly",false);
	$("#cabeza_craneo").prop("readonly",false);
	$("#ojos").prop("readonly",false);
	$("#nariz").prop("readonly",false);
	$("#respiratorio").prop("readonly",false);
	$("#abdomen").prop("readonly",false);
	$("#extremidades_r").prop("readonly",false);
	$("#extremidades_s").prop("readonly",false);
	$("#neurologicos").prop("readonly",false);
	$("#general").prop("readonly",false);
	$("#cardiovascular").prop("readonly",false);
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
		$("#fechaconsulta").prop("readonly",true);
		$("#consulta").prop("readonly",true);
		$("#diagnostico").prop("readonly",true);
		$("#tratamientos").prop("readonly",true);
		$("#cedula_p").prop("readonly",true);
		$("#cedula_h").prop("readonly",true);
		$("#boca_abierta").prop("readonly",true);
		$("#boca_cerrada").prop("readonly",true);
		$("#oidos").prop("readonly",true);
		$("#cabeza_craneo").prop("readonly",true);
		$("#ojos").prop("readonly",true);
		$("#nariz").prop("readonly",true);
		$("#respiratorio").prop("readonly",true);
		$("#abdomen").prop("readonly",true);
		$("#extremidades_r").prop("readonly",true);
		$("#extremidades_s").prop("readonly",true);
		$("#neurologicos").prop("readonly",true);
		$("#general").prop("readonly",true);
		$("#cardiovascular").prop("readonly",true);
		$("#proceso").text("ELIMINAR");
		boton_h.style.display = 'none';
		boton_p.style.display = 'none';

		limpiarm();
		colocapacientes(linea)
		colocapersonal(linea)
	}
	else if (accion==2){
		$("#fechaconsulta").prop("readonly",true);
		$("#consulta").prop("readonly",true);
		$("#diagnostico").prop("readonly",true);
		$("#tratamientos").prop("readonly",true);
		$("#cedula_p").prop("readonly",true);
		$("#cedula_h").prop("readonly",true);
		$("#boca_abierta").prop("readonly",true);
		$("#boca_cerrada").prop("readonly",true);
		$("#oidos").prop("readonly",true);
		$("#cabeza_craneo").prop("readonly",true);
		$("#ojos").prop("readonly",true);
		$("#nariz").prop("readonly",true);
		$("#respiratorio").prop("readonly",true);
		$("#abdomen").prop("readonly",true);
		$("#extremidades_r").prop("readonly",true);
		$("#extremidades_s").prop("readonly",true);
		$("#neurologicos").prop("readonly",true);
		$("#general").prop("readonly",true);
		$("#cardiovascular").prop("readonly",true);
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

		limpiarm();
		
	}
	$("#cod_consulta").val($(linea).find("td:eq(1)").text());
	$("#fechaconsulta").val( $(pos).attr('fechaconsulta') );
	$("#consulta").val( $(pos).attr('consulta') );
	$("#diagnostico").val( $(pos).attr('diagnostico') );
	$("#tratamientos").val( $(pos).attr('tratamientos') );
	$("#cedula_p").val( $(pos).attr('cedula_p') );
	$("#cedula_h").val( $(pos).attr('cedula_h') );
	$("#boca_abierta").val( $(pos).attr('boca_abierta') );
	$("#boca_cerrada").val( $(pos).attr('boca_cerrada') );
	$("#oidos").val( $(pos).attr('oidos') );
	$("#cabeza_craneo").val( $(pos).attr('cabeza_craneo') );
	$("#ojos").val( $(pos).attr('ojos') );
	$("#nariz").val( $(pos).attr('nariz') );
	$("#respiratorio").val( $(pos).attr('respiratorio') );
	$("#abdomen").val( $(pos).attr('abdomen') );
	$("#extremidades_r").val( $(pos).attr('extremidades_r') );
	$("#extremidades_s").val( $(pos).attr('extremidades_s') );
	$("#neurologicos").val( $(pos).attr('neurologicos') );
	$("#general").val( $(pos).attr('general') );
	$("#cardiovascular").val( $(pos).attr('cardiovascular') );




	
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
		   var html="";
		   lee.datos.forEach(function (fila) {
			html += `<tr>
				<td>
					<div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
						<a type="button" class="btn btn-success" onclick="pone(this,0)"
							fechaconsulta="${fila.fechaconsulta}"
							consulta="${fila.consulta}"
							diagnostico="${fila.diagnostico}"
							tratamientos="${fila.tratamientos}"
							cedula_p="${fila.cedula_p}"
							cedula_h="${fila.cedula_h}"
							cargo="${fila.cargo}"
							nombre="${fila.nombre}"
							apellido="${fila.apellido}"
							boca_abierta="${fila.boca_abierta}"
							boca_cerrada="${fila.boca_cerrada}"
							oidos="${fila.oidos}"
							cabeza_craneo="${fila.cabeza_craneo}"
							ojos="${fila.ojos}"
							nariz="${fila.nariz}"
							respiratorio="${fila.respiratorio}"
							abdomen="${fila.abdomen}"
							extremidades_r="${fila.extremidades_r}"
							extremidades_s="${fila.extremidades_s}"
							neurologicos="${fila.neurologicos}"
							general="${fila.general}"
							cardiovascular="${fila.cardiovascular}">
							<img src="img/lapiz.svg" style="width: 20px">
						</a>
						<a type="button" class="btn btn-danger" onclick="pone(this,1)"
							fechaconsulta="${fila.fechaconsulta}"
							consulta="${fila.consulta}"
							diagnostico="${fila.diagnostico}"
							tratamientos="${fila.tratamientos}"
							cedula_p="${fila.cedula_p}"
							cedula_h="${fila.cedula_h}"
							cargo="${fila.cargo}"
							nombre="${fila.nombre}"
							apellido="${fila.apellido}"
							boca_abierta="${fila.boca_abierta}"
							boca_cerrada="${fila.boca_cerrada}"
							oidos="${fila.oidos}"
							cabeza_craneo="${fila.cabeza_craneo}"
							ojos="${fila.ojos}"
							nariz="${fila.nariz}"
							respiratorio="${fila.respiratorio}"
							abdomen="${fila.abdomen}"
							extremidades_r="${fila.extremidades_r}"
							extremidades_s="${fila.extremidades_s}"
							neurologicos="${fila.neurologicos}"
							general="${fila.general}"
							cardiovascular="${fila.cardiovascular}">
							<img src="img/basura.svg" style="width: 20px">
						</a>
						<a type="button" class="btn btn-primary" onclick="pone(this,2)"
							fechaconsulta="${fila.fechaconsulta}"
							consulta="${fila.consulta}"
							diagnostico="${fila.diagnostico}"
							tratamientos="${fila.tratamientos}"
							cedula_p="${fila.cedula_p}"
							cedula_h="${fila.cedula_h}"
							cargo="${fila.cargo}"
							nombre="${fila.nombre}"
							apellido="${fila.apellido}"
							boca_abierta="${fila.boca_abierta}"
							boca_cerrada="${fila.boca_cerrada}"
							oidos="${fila.oidos}"
							cabeza_craneo="${fila.cabeza_craneo}"
							ojos="${fila.ojos}"
							nariz="${fila.nariz}"
							respiratorio="${fila.respiratorio}"
							abdomen="${fila.abdomen}"
							extremidades_r="${fila.extremidades_r}"
							extremidades_s="${fila.extremidades_s}"
							neurologicos="${fila.neurologicos}"
							general="${fila.general}"
							cardiovascular="${fila.cardiovascular}">
							<img src="img/ojo.svg" style="width: 20px">
						</a>
						<a class="btn btn-danger" href="vista/fpdf/consultasm.php?cod_consulta=${fila.cod_consulta}" target="_blank">
							<img src="img/descarga.svg" style="width: 20px;">
						</a>
					</div>
				</td>
				<td style="display:none;">${fila.cod_consulta}</td>
				<td>${fila.nombre_h}</td>
				<td>${fila.apellido_h}</td>
				<td>${fila.fechaconsulta}</td>
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

			$('#listadopacientes').html(lee.mensaje);
			crearDT1();
		
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
	$("#cod_consulta").val("");
	$("#fechaconsulta").val("");
	$("#consulta").val("");
	$("#diagnostico").val("");
	$("#tratamientos").val("");
	$("#cedula_p").val("");
	$("#cedula_h").val("");
	$("#boca_abierta").val("");
	$("#boca_cerrada").val("");
	$("#oidos").val("");
	$("#cabeza_craneo").val("");
	$("#ojos").val("");
	$("#nariz").val("");
	$("#respiratorio").val("");
	$("#abdomen").val("");
	$("#extremidades_r").val("");
	$("#extremidades_s").val("");
	$("#neurologicos").val("");
	$("#general").val("");
	$("#cardiovascular").val("");

}

