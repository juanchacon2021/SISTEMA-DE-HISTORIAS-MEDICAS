function pone(pos, accion) {
    $("#modal1").modal("show");
}


function muestraMensaje(mensaje){
	$("#contenidodemodal").html(mensaje);
			$("#mostrarmodal").modal("show");
			setTimeout(function() {
					$("#mostrarmodal").modal("hide");
			},2000);
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

document.getElementById('formPlanificacion').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita el envío del formulario por defecto

    const contenido = document.getElementById('contenido').value.trim();
    if (!contenido) {
        muestraMensaje("El campo contenido es obligatorio.", "error");
        return;
    }

    const formData = new FormData(this);

    fetch('controlador/planificacion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.resultado === "incluir") {
            muestraMensaje(data.mensaje, "success");
            document.getElementById('formPlanificacion').reset();
        } else {
            muestraMensaje(data.mensaje, "error");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        muestraMensaje("Ocurrió un error al procesar la solicitud.", "error");
    });
});

function muestraMensaje(mensaje, tipo = "error") {
    const mensajeDiv = document.getElementById('mensaje');
    mensajeDiv.innerHTML = `<div class="alert alert-${tipo === "success" ? "success" : "danger"}">${mensaje}</div>`;
}