$(document).ready(function() {
    var tabla = $('#tablaBitacora').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: '',
            type: 'POST',
            data: function(d) {
                return {
                    accion: 'consultar',
                    filtro: $('#filtroBitacora').val()
                };
            }
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