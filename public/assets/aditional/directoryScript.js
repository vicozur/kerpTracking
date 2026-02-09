function openForm(data = null) {
    // TRACKING_URL
    var url;
    if (data) {
        console.log(data);
        url = `${TRACKING_URL}/procedureForm/${data}`;
    } else {
        url = `${TRACKING_URL}/procedureForm/`
    }
    //const url = data.directory_id ? `${TRACKING_URL}/clienteForm/${data.directory_id}` : `${TRACKING_URL}/clienteForm/`;
    console.log(url);
    window.location.href = url;
}

$(document).ready(function() {
    const table = $('#tablaTramites').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', className: 'btn btn-success btn-sm', text: 'Excel' },
            { extend: 'pdfHtml5', className: 'btn btn-danger btn-sm', text: 'PDF', orientation: 'landscape' }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        drawCallback: function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
    table.buttons().container().appendTo('#buttons_export');
});

function filtrar(term) {
    $('#tablaTramites').DataTable().search(term).draw();
}

async function editarTramite(id) {
    Swal.fire({ title: 'Cargando...', didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch(`${TRACKING_URL}/getTramite/${id}`);
        const result = await res.json();
        Swal.close();
        
        if(result.status === 'success') {
            const t = result.data;
            $('#id_tramite').val(t.id_tramite);
            $('[name="nombre_completo2"]').val(t.nombre_completo2);
            $('[name="tipo_solicitante"]').val(t.tipo_persona).trigger('change');
            
            if(t.observacion) {
                $('#alert_observacion').removeClass('d-none').addClass('alert-danger');
                $('#text_observacion').text(t.observacion);
            }
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
    } catch(e) { Swal.fire('Error', 'No se pudo cargar los datos', 'error'); }
}