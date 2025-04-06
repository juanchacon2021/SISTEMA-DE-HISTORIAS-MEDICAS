function pone(pos, accion) {
    const linea = $(pos).closest('tr');
    const mapeoCampos = {
        "cedula_historia": "cedula_historia",
        "apellido": "apellido",
        "nombre": "nombre",
        "fecha_nac": "fecha_nac",
        "edad": "edad",
        "telefono": "telefono",
        "estadocivil": "estadocivil",
        "direccion": "direccion",
        "ocupacion": "ocupacion",
        "hda": "hda",
        "habtoxico": "habtoxico",
        "alergias": "alergias",
        "alergias_med": "alergias_med",
        "quirurgico": "quirurgico",
        "psicosocial": "psicosocial",
        "transsanguineo": "transsanguineo",
        "antc_padre": "antc_padre",
        "antc_hermano": "antc_hermano",
        "antc_madre": "antc_madre"
    };

    // Cambiar el texto del proceso según la acción
    if (accion === 0) {
        $("#proceso").text("MODIFICAR");
        $("#accion").val("modificar"); // Actualizar el valor del campo oculto
    } else {
        $("#proceso").text("INCLUIR");
        $("#accion").val("incluir"); // Actualizar el valor del campo oculto
    }

    // Iterar sobre el mapeo y asignar valores
    Object.entries(mapeoCampos).forEach(([campo, clase]) => {
        $(`#${campo}`).val($(linea).find(`.${clase}`).text());
    });

    // Mostrar el modal
    $("#modal1").modal("show");
}


//EMOJIS
