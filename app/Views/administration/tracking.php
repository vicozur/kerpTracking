<?php echo $this->extend('template/layout'); ?>
<?php $this->section('content'); ?>
<script>
    const TRACKING_URL = "<?= base_url('tracking') ?>";
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
<div class="row">
    <div>
        <button class="btn btn-primary btn-flat float-end" onclick="openForm()"><b>Nuevo tramite</b></button>
    </div>
    <hr>
</div>

<div class="row mb-4 text-center">
    <?php foreach (['TOTAL' => 'secondary', 'PENDIENTE' => 'warning', 'EN CURSO' => 'info', 'APROBADO' => 'success', 'OBSERVADOS' => 'danger'] as $key => $col): ?>
        <div class="col-md-2">
            <div class="card border-<?= $col ?> shadow-sm">
                <div class="card-body py-2">
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
        <button onclick="filtrar('fa-exclamation-circle')" class="btn btn-sm btn-outline-danger">Observados</button>
        <button onclick="filtrar('')" class="btn btn-sm btn-outline-secondary">Todos</button>
    </div>
    <div id="buttons_export"></div>
</div>
<div class="table-responsive mt-3">
    <table class="table table-hover dt-responsive nowrap w-100" id="tablaTramites">  
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Trámite</th>
                <th>Estado</th>
                <th>Solicitante</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tramites as $tr): ?>
                <tr>
                    <td><?= $tr['id_tramite'] ?></td>
                    <td><strong><?= esc($tr['nombre_tipo']) ?></strong></td>
                    <td>
                        <span class="badge bg-<?= ($tr['estado_tramite'] == 'PENDIENTE' ? 'warning' : ($tr['estado_tramite'] == 'APROBADO' ? 'success' : 'info')) ?>">
                            <?= $tr['estado_tramite'] ?>
                        </span>
                        <?php if (!empty($tr['observacion'])): ?>
                            <i class="fas fa-exclamation-circle text-danger ms-1" data-bs-toggle="tooltip" title="<?= esc($tr['observacion']) ?>"></i>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($tr['nombre_completo']) ?> <br><small class="text-muted"><?= $tr['tipo_persona'] ?></small></td>
                    <td><?= date('d/m/Y', strtotime($tr['created_at'])) ?></td>
                    <td>
                        <button onclick="editarTramite(<?= $tr['id_tramite'] ?>)" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                        <a href="<?= base_url("tracking/descargar/{$tr['id_tramite']}/doc_ci") ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script src="<?= base_url('assets/aditional/directoryScript.js') ?>"></script>
<?php $this->endSection(); ?>