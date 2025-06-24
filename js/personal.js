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

function agregarCampoTelefono(valor = '') {
    const container = $('#telefonos-container');
    const nuevoInput = $(`
        <div class="input-group mb-2">
            <input class="form-control bg-gray-200 rounded-lg border-white telefono-input" type="text" value="${valor}" />
            <button type="button" class="btn btn-danger btn-remove-phone">-</button>
        </div>
    `);
    container.append(nuevoInput);
    
    // Validación para el nuevo campo de teléfono
    nuevoInput.find('input').on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });

    nuevoInput.find('input').on("keyup", function () {
        validarkeyup(/^\d{11}$/, $(this), $("#stelefono"), "Formato del teléfono incorrecto");
    });
}

$(document).ready(function () {
    consultar();

     const params = new URLSearchParams(window.location.search);
    if (params.get('accion') === 'registrar') {
        limpia(); // Limpia el formulario
        pone(null, 3);       // Abre el modal en modo "INCLUIR"
        // Limpiar la URL
        window.history.replaceState({}, document.title, "?pagina=personal");
    }

    // Manejar clic en botón para agregar teléfono
    $('#btn-add-phone').click(function() {
        agregarCampoTelefono();
    });

    // Manejar clic en botón para eliminar teléfono (delegación de eventos)
    $(document).on('click', '.btn-remove-phone', function() {
        $(this).closest('.input-group').remove();
    });

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

    // Validación inicial para el primer campo de teléfono
    $(".telefono-input").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });

    $(".telefono-input").on("keyup", function () {
        validarkeyup(/^\d{11}$/, $(this), $("#stelefono"), "Formato del teléfono incorrecto");
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
                datos.append("cargo", $("#cargo").val());
                
                // Agregar todos los teléfonos
                $(".telefono-input").each(function(index) {
                    datos.append("telefonos[]", $(this).val());
                });
                
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
                datos.append("cargo", $("#cargo").val());
                
                // Agregar todos los teléfonos
                $(".telefono-input").each(function(index) {
                    datos.append("telefonos[]", $(this).val());
                });
                
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
    
    // Validar que al menos un teléfono esté completo
    let telefonosValidos = false;
    $(".telefono-input").each(function() {
        if(validarkeyup(/^\d{11}$/, $(this), $("#stelefono"), "Formato del teléfono incorrecto") == 1) {
            telefonosValidos = true;
        }
    });
    
    if(!telefonosValidos) {
        muestraMensaje("Debe ingresar al menos un teléfono válido (11 dígitos)");
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

function pone(pos,accion){
    
    linea=$(pos).closest('tr');


    if(accion==0){
        $("#proceso").text("MODIFICAR");
    }
    else if(accion==1){
        $("#proceso").text("ELIMINAR"); 
    }
    else{
        $("#proceso").text("INCLUIR");
    }
    $("#cedula_personal").val($(linea).find("td:eq(1)").text());
    $("#apellido").val($(linea).find("td:eq(2)").text());
    $("#nombre").val($(linea).find("td:eq(3)").text());
    $("#correo").val($(linea).find("td:eq(4)").text());
    $("#cargo").val($(linea).find("td:eq(6)").text());
    
    // Limpiar y cargar teléfonos
    $("#telefonos-container").empty();
    const telefonos = $(linea).find("td:eq(5)").text().split(", ");
    telefonos.forEach(tel => {
        if(tel.trim() !== '') {
            agregarCampoTelefono(tel);
        }
    });
    
    // Si no hay teléfonos, agregar un campo vacío
    if(telefonos.length === 0 || (telefonos.length === 1 && telefonos[0] === '')) {
        agregarCampoTelefono();
    }
    
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
                    <td>${fila.telefonos || 'N/A'}</td>
                    <td>${fila.cargo}</td>
                    
                </tr>`;
            });
            $("#resultadoconsulta").html(html);
            
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
    $("#telefonos-container").empty();
    agregarCampoTelefono(); // Agregar un campo de teléfono vacío
    $("#cargo").prop("selectedIndex",0);
}