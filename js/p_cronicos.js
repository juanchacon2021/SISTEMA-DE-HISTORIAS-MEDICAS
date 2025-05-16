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
$(document).ready(function(){

	
	consultar();
	carga_pacientes();

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


	
	//validar

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
			datos.append('cod_cronico',$("#cod_cronico").val());
			datos.append('patologia_cronica',$("#patologia_cronica").val());
			datos.append('Tratamiento',$("#Tratamiento").val());
			datos.append('admistracion_t',$("#admistracion_t").val());
			datos.append('cedula_h',$("#cedula_h").val());
			enviaAjax(datos);
		}
	}
	else if($(this).text()=="MODIFICAR"){
		if(validarenvio()){
			var datos = new FormData();
			datos.append('accion','modificar');
			datos.append('cod_cronico',$("#cod_cronico").val());
			datos.append('patologia_cronica',$("#patologia_cronica").val());
			datos.append('Tratamiento',$("#Tratamiento").val());
			datos.append('admistracion_t',$("#admistracion_t").val());
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
			datos.append('cod_cronico',$("#cod_cronico").val());
			enviaAjax(datos);
		}
	
});
$("#incluir").on("click",function(){
	limpia();
	$("#proceso").text("INCLUIR");
	$("#modal1").modal("show");
});





	
	
});

function carga_pacientes(){
	
	var datos = new FormData();

	datos.append('accion','listadopacientes'); 

	enviaAjax(datos);
}
function limpiarm(){

	const limpia = document.querySelector('#datosdelpacientes');
	limpia.textContent = "";


}


function validarenvio(){

	if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_h"),
		$("#scedula_h"),"El formato debe ser 12345678")==0){
	    muestraMensaje("La cedula del paciente debe coincidir con el formato <br/>"+ 
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
    $("#cedula_h").val(cedula);
    $("#datosdelpacientes").html(
        `Nombre: ${nombre} / Apellido: ${apellido}`
    );

    // Cierra el modal
    $("#modalpacientes").modal("hide");
}




function pone(pos,accion){
	$("#patologia_cronica").prop("readonly",false);
	$("#Tratamiento").prop("readonly",false);
	$("#admistracion_t").prop("readonly",false);
	$("#cedula_h").prop("readonly",false);
	$("#proceso1").prop("cerrar",false); 
	const boton_h = document.querySelector('#listadodepacientes');
	
	linea=$(pos).closest('tr');
	$("#patologia_options").show();


	if(accion==0){
		$("#patologia_options input[type='checkbox']").prop('checked', false);

		$("#proceso").text("MODIFICAR");
		boton_h.style.display = '';
		limpiarm();
		colocapacientes(linea)
	}
	else if (accion==1){
		$("#patologia_cronica").prop("readonly",true);
		$("#Tratamiento").prop("readonly",true);
		$("#admistracion_t").prop("readonly",true);
		$("#cedula_h").prop("readonly",true);
		$("#proceso").text("ELIMINAR");

		$("#patologia_options").hide();
		boton_h.style.display = 'none';

		limpiarm();
		colocapacientes(linea)

		
	}
	else if (accion==2){
		$("#patologia_cronica").prop("readonly",true);
		$("#Tratamiento").prop("readonly",true);
		$("#admistracion_t").prop("readonly",true);
		$("#cedula_h").prop("readonly",true);
		$("#proceso").text("CERRAR");
		$("#patologia_options").hide();
		
		boton_h.style.display = 'none';


		limpiarm();
		colocapacientes(linea)
		
	}
	else{
		$("#proceso").text("INCLUIR");
		boton_h.style.display = '';
		$("#patologia_options input[type='checkbox']").prop('checked', false);
	}
	$("#cod_cronico").val($(linea).find("td:eq(1)").text());
	$("#patologia_cronica").val( $(pos).attr('patologia_cronica') );
	$("#Tratamiento").val( $(pos).attr('Tratamiento') );
	$("#admistracion_t").val( $(pos).attr('admistracion_t') );
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
							   patologia_cronica="${fila.patologia_cronica}"
							   Tratamiento="${fila.Tratamiento}"
							   admistracion_t="${fila.admistracion_t}"
							   cedula_h="${fila.cedula_h}"
							   nombre="${fila.nombre}"
							   apellido="${fila.apellido}">
							   <img src="img/lapiz.svg" style="width: 20px">
						   </a>
						   <a type="button" class="btn btn-danger" onclick="pone(this,1)"
							   patologia_cronica="${fila.patologia_cronica}"
							   Tratamiento="${fila.Tratamiento}"
							   admistracion_t="${fila.admistracion_t}"
							   cedula_h="${fila.cedula_h}"
							   nombre="${fila.nombre}"
							   apellido="${fila.apellido}">
							   <img src="img/basura.svg" style="width: 20px">
						   </a>
						   <a type="button" class="btn btn-primary" onclick="pone(this,2)"
							   patologia_cronica="${fila.patologia_cronica}"
							   Tratamiento="${fila.Tratamiento}"
							   admistracion_t="${fila.admistracion_t}"
							   cedula_h="${fila.cedula_h}"					 
							   nombre="${fila.nombre}"
							   apellido="${fila.apellido}">
							   <img src="img/ojo.svg" style="width: 20px">
						   </a>
											
						   <!-- Agrega los demás botones aquí -->
					   </div>
				   </td>
				   <td style="display:none;">${fila.cod_cronico}</td>
				   <td>${fila.nombre_h}</td>
				   <td>${fila.apellido_h}</td>
				   <td>${fila.patologia_cronica}</td>
				   <td>${fila.cedula_h}</td>
			   </tr>`;
		   });
		   
		   $("#resultadoconsulta").html(html);
		   crearDT();
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
	$("#cod_cronico").val("");
	$("#patologia_cronica").val("");
	$("#Tratamiento").val("");
	$("#admistracion_t").val("");
	$("#cedula_h").val("");

}