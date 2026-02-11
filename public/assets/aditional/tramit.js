$(document).ready(function() {
    const csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';
    // --- SOLUCIÓN AL ERROR TN/3 ---
    if ($.fn.DataTable.isDataTable('#tablaTramites')) {
        $('#tablaTramites').DataTable().destroy();
        $('#tablaTramites').empty(); // Limpia el contenido HTML residual
    }
    $('#tablaTramites').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": `${TRACKING_URL}/getData`, 
            "type": "POST",
            "data": function (d) {
                d[csrfName] = csrfHash; // Token actual
            },
            "dataSrc": function(json) {
                csrfHash = json.csrf_hash; // Actualizamos para la siguiente
                return json.data;
            }
        },
        "columns": [
            { "data": "nombre_tipo" },     // 0: Trámite
            { "data": "estado_tramite" }, // 1: Situación
            { 
                "data": "estado_reg",     // 2: Estado
                "render": function(data, type, row) {
                    let color = (data == 'APROBADO') ? 'success' : (data == 'PENDIENTE' ? 'warning' : 'danger');
                    return `<span class="badge bg-${color}">${data}</span>`;
                }
            },
            { 
                "data": "nombre_completo", // 3: Solicitante
                "render": (data, type, row) => {
                  // Si data es null o vacío, usamos 'Sin nombre', de lo contrario usamos data
                    let nombre = data ? data + '<br>'
                    : '';
                    let tipo = row.tipo_persona ? row.tipo_persona : "---";

                    return `${nombre} <small class="text-muted">${tipo}</small>`;
                }
            },
            { "data": "created_at" },      // 4: Fecha
            { 
                "data": null,              // 5: Proceso
                "orderable": false,
                "render": function(data, type, row) {
                    return generarBotonesAccion(row);
                }
            }
        ]
    });
});

// Función auxiliar para no saturar la configuración del DataTable
function generarBotonesAccion(row) {
    // Usamos el ID de documento para saber si mostrar opciones
    if (!row.id_documento) return `<span class="text-muted">Sin documentos</span>`;

    let html  = `
    <div class="btn-group">
        <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Acciones</button>
        <ul class="dropdown-menu">
            <li><h6 class="dropdown-header text-info">Procesos</h6></li>
            <li><a class="dropdown-item" href="javascript:void(0)" onclick="modalEstado(${row.id_tramite}, 'APROBADO')"><i class="fas fa-check text-success"></i> Aprobar</a></li>
            <li><a class="dropdown-item" href="javascript:void(0)" onclick="modalEstado(${row.id_tramite}, 'RECHAZADO')"><i class="fas fa-times text-danger"></i> Rechazar</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><h6 class="dropdown-header text-info">Documentos Propietario</h6></li>
            <li><a class="dropdown-item" target="_blank" href="${TRACKING_URL}/descargar/${row.id_tramite}/doc_ci"><i class="fas fa-file-pdf"></i> Ver C.I.</a></li>
            <li><a class="dropdown-item" target="_blank" href="${TRACKING_URL}/descargar/${row.id_tramite}/doc_memorial"><i class="fas fa-file-pdf"></i> Ver Memorial</a></li>
            <li><a class="dropdown-item" target="_blank" href="${TRACKING_URL}/descargar/${row.id_tramite}/doc_folio"><i class="fas fa-file-pdf"></i> Ver Folio Real</a></li>
            <li><a class="dropdown-item" target="_blank" href="${TRACKING_URL}/descargar/${row.id_tramite}/doc_plano"><i class="fas fa-file-pdf"></i> Ver Plano</a></li>`;

    // Bloque condicional para TRAMITADOR
    if (row.tipo_persona === 'TRAMITADOR') {
        html += `
            <li><hr class="dropdown-divider"></li>
            <li><h6 class="dropdown-header text-info">Documentos Tramitador</h6></li>
            <li><a class="dropdown-item" target="_blank" href="${TRACKING_URL}/descargar/${row.id_tramite}/doc_ci_tramitador"><i class="fas fa-id-card text-info"></i> C.I. Tramitador</a></li>
            <li><a class="dropdown-item" target="_blank" href="${TRACKING_URL}/descargar/${row.id_tramite}/doc_poder"><i class="fas fa-file-contract text-info"></i> Poder Legal</a></li>`;
    }

    html += `
        </ul>
    </div>`;
    return html;
}

// Función para los botones de filtro superior
function filtrar(termino) {
    const table = $('#tablaTramites').DataTable();
    
    // search() busca en toda la tabla, incluso en el HTML de las celdas
    table.search(termino).draw();
}

function modalEstado(id, estado) {
    // 1. Asignar valores básicos
    $('#input_id_tramite').val(id);
    $('#input_estado_reg').val(estado);
    $('#display_estado').text(estado);

    
    // 3. Opcional: Si tienes el nombre en la fila de la tabla, puedes capturarlo aquí
    // let nombre = $(`#nombre_fila_${id}`).text(); 
    // $('#input_nombre_completo').val(nombre);

    $('#modalCambioEstado').modal('show');
}

$('#formUpdateTramite').on('submit', function(e) {
    e.preventDefault();

    // Usamos FormData por si luego decides subir archivos/imágenes
    let formData = new FormData(this);
    console.log(formData);
    // Agregar el Token CSRF manualmente si no está en el form
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    $.ajax({
        url: `${TRACKING_URL}/updateTramite`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        beforeSend: function() {
            Swal.fire({ title: 'Procesando...', didOpen: () => { Swal.showLoading() } });
        },
        success: function(res) {
            if (res.status === 'success') {
                $('#modalCambioEstado').modal('hide');
                $('#tablaTramites').DataTable().ajax.reload(null, false); // Recarga suave
                Swal.fire('¡Actualizado!', res.message, 'success');
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
        }
    });
});