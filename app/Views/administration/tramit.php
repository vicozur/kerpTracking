<?php echo $this->extend('template/layout'); ?>
<?php $this->section('content'); ?>
<script>
    const TRACKING_URL = "<?= base_url('tramite') ?>";
    // 🔑 Generamos las variables CSRF de forma segura
    const CI_CSRF_NAME = '<?= csrf_token() ?>';
    const CI_CSRF_HASH = '<?= csrf_hash() ?>';
    // ✅ CORRECCIÓN CLAVE: Define la URL del JSON de idioma aquí.
    const DATATABLES_LANGUAGE_URL = "<?= base_url('assets/datatables/es-ES.json') ?>";
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- Start row container-->

<div class="row mb-4 text-center">
    <?php foreach (['TOTAL' => 'secondary', 'PENDIENTE' => 'warning', 'APROBADO' => 'success', 'RECHAZADO' => 'danger'] as $key => $col): ?>
        <div class="col-md-3">
            <div class="card border-<?= $col ?> shadow-sm">
                <div class="card-body py-3">
                    <small class="text-uppercase fw-bold"><?= $key ?></small>
                    <h3 class="mb-0 text-<?= $col ?>"><?= $stats[$key] ?></h3>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="d-flex justify-content-between mb-3">
    <div class="btn-group">
        <button onclick="filtrar('PENDIENTE')" class="btn btn-sm btn-outline-warning">Pendientes</button>
        <button onclick="filtrar('APROBADO')" class="btn btn-sm btn-outline-success">Aprobados</button>
        <button onclick="filtrar('RECHAZADO')" class="btn btn-sm btn-outline-danger">Rechazados/Observados</button>
        <button onclick="filtrar('')" class="btn btn-sm btn-outline-secondary">Todos</button>
    </div>
    <div id="buttons_export"></div>
</div>

<div class="table-responsive mt-3">
    <table class="table table-hover dt-responsive nowrap w-100" id="tablaTramites">
        <thead class="table-dark">
            <tr>
                <th class="text-center">Trámite</th>
                <th class="text-center">Situación</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Solicitante</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Proceso</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalCambioEstado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formUpdateTramite">
                <div class="modal-header">
                    <h5 class="modal-title">Procesar Trámite: <span id="display_estado"></span></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_tramite" id="input_id_tramite">
                    <input type="hidden" name="estado_reg" id="input_estado_reg">

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Nombre Completo del Solicitante:</label>
                            <input type="text" name="nombre_completo" id="input_nombre_completo" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>CITE de Trámite:</label>
                            <input type="text" name="cite_tramite" class="form-control" placeholder="Ej: CITE-2026-001">
                        </div>
                        <div class="col-md-6 mb-3" id="wrapper_resolucion" >
                            <label>Nro. Resolución:</label>
                            <input type="number" name="num_resolucion" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Observación / Justificación:</label>
                            <textarea name="observacion" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="<?= base_url('assets/aditional/tramit.js') ?>"></script>
<?php $this->endSection(); ?>