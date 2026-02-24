/**
 * Función para obtener y mostrar el seguimiento de un trámite
 * @param {number} id_tramite - ID único del trámite
 */
function verSeguimiento(id_tramite) {
    const container = document.getElementById('timeline_content');
    const spanCite = document.getElementById('text_cite');
    
    // 1. Mostrar estado de carga
    container.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Cargando historial...</p>
        </div>`;

    // 2. Realizar la petición al controlador
    fetch(`${TRACKING_URL}/seguimiento/${id_tramite}`)
        .then(response => {
            if (!response.ok) throw new Error('No se pudo recuperar el historial del trámite.');
            return response.json();
        })
        .then(data => {
            if (!data || data.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No se encontraron registros para este trámite.</div>';
                return;
            }

            // Actualizar el CITE en la cabecera del modal
            if (spanCite) spanCite.innerText = data[0].cite_tramite || 'S/N';

            // 3. Construir la línea de tiempo (Timeline)
            let html = '<div class="timeline-container py-2">';
            
            data.forEach((item, index) => {
                const isLast = (index === data.length - 1);
                const colorClass = isLast ? 'success' : 'primary';
                const iconClass = isLast ? 'bi-check-circle-fill' : 'bi-clock-history';
                
                // Formatear fecha (opcional, usa item.created_at directo si prefieres)
                const fecha = new Date(item.created_at).toLocaleString('es-ES', {
                    day: '2-digit', month: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                html += `
                <div class="position-relative ps-4 pb-4 border-start border-2 ${isLast ? 'border-transparent' : 'border-light-subtle'}" style="margin-left: 10px;">
                    <span class="position-absolute translate-middle-x bg-white text-${colorClass}" style="left: 0; top: 0; z-index: 1;">
                        <i class="bi ${iconClass} fs-5"></i>
                    </span>
                    
                    <div class="card shadow-sm border-0 mb-2">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0 text-${colorClass}">${item.cargo || 'Funcionario'}</h6>
                                <span class="badge rounded-pill bg-light text-dark border">${fecha}</span>
                            </div>
                            
                            <p class="mb-2 text-secondary small">
                                <strong>Descripción:</strong> ${item.descripcion || 'Sin observaciones.'}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-${colorClass}-subtle text-${colorClass} border border-${colorClass}">
                                    ${item.estado_tramite}
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-person-circle"></i> ${item.created_user}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>`;
            });

            html += '</div>';
            container.innerHTML = html;

            // 4. Mostrar el modal (Asegúrate de que el ID coincida con tu HTML)
            const modalElement = document.getElementById('modalSeguimiento');
            const myModal = bootstrap.Modal.getOrCreateInstance(modalElement);
            myModal.show();
        })
        .catch(error => {
            console.error("Error:", error);
            container.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        });
}