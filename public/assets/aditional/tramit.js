// Función para los botones de filtro superior
function filtrar(termino) {
    const table = $('#tablaTramites').DataTable();
    
    // search() busca en toda la tabla, incluso en el HTML de las celdas
    table.search(termino).draw();
}

$(document).ready(function() {
    // Inicialización de la tabla (asegúrate de que el ID coincida)
    const table = $('#tablaTramites').DataTable({
        responsive: true,
        // ... otras configuraciones que ya tengas
    });
});

function modalEstado(id, nuevoEstado) {
    const config = {
        'APROBADO': { titulo: '¿Aprobar Trámite?', color: '#28a745', msg: 'El trámite pasará a estado aprobado.' },
        'RECHAZADO': { titulo: '¿Rechazar Trámite?', color: '#dc3545', msg: 'Indique el motivo del rechazo:' },
        'EN CURSO': { titulo: 'Observar Trámite', color: '#17a2b8', msg: 'Indique qué documentos o datos debe corregir el usuario:' }
    };

    Swal.fire({
        title: config[nuevoEstado].titulo,
        text: config[nuevoEstado].msg,
        input: 'textarea',
        inputPlaceholder: 'Escriba aquí los detalles...',
        showCancelButton: true,
        confirmButtonColor: config[nuevoEstado].color,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        preConfirm: (obs) => {
            if (!obs && nuevoEstado !== 'APROBADO') {
                Swal.showValidationMessage('Es obligatorio dejar una observación para este estado');
            }
            return obs;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí llamas a tu función AJAX para guardar en BD
            guardarCambioEstado(id, nuevoEstado, result.value);
        }
    });
}

function enviarCambioEstado(id, estado, observacion) {
    const formData = new FormData();
    formData.append('id_tramite', id);
    formData.append('estado_tramite', estado);
    formData.append('observacion', observacion);

    fetch(`${BASE_URL}tramite/update_status`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('¡Actualizado!', data.message, 'success').then(() => {
                location.reload(); // Recarga para ver los cambios en la tabla y contadores
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}