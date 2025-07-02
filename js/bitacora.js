$(document).ready(function() {
    var tabla = $('#tablaBitacora').DataTable({
        language: {
            url: ''
        },
        processing: true,
        serverSide: false,
        ajax: {
            url: '', 
            type: 'POST',
            data: function(d) {
                return {
                    accion: 'consultar',
                    filtro: $('#filtroBitacora').val()
                };
            },
            dataSrc: 'datos' // Especificar la propiedad que contiene los datos
        },
        columns: [
            { 
                data: 'fecha_hora',
                render: function(data) {
                    return new Date(data).toLocaleString();
                }
            },
            { data: 'usuario_nombre' },
            { data: 'modulo_nombre' },
            { data: 'accion' },
            { data: 'descripcion' }
        ],
        order: [[0, 'desc']]
    });

    $('#btnFiltrar').click(function() {
        tabla.ajax.reload();
    });

    $('#btnRecargar').click(function() {
        $('#filtroBitacora').val('');
        tabla.ajax.reload();
    });

    $('#filtroBitacora').keypress(function(e) {
        if(e.which == 13) {
            tabla.ajax.reload();
        }
    });
});

// NOTIFICACIONES
const ws = new WebSocket('ws://localhost:8080');

ws.onopen = function() {
    console.log('Conectado al WebSocket');
};

ws.onclose = function() {
    console.log('WebSocket cerrado');
};

ws.onerror = function(error) {
    console.error('WebSocket error:', error);
};

// Ejemplo: enviar un mensaje (puedes quitar esto si solo quieres recibir)
function enviarNotificacion(msg) {
    ws.send(msg);
}