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
    <div class="container mt-5">
        <h3><b>Registro de Documentaci&oacute;n</b></h3>
        <form id="myForm">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Tipo de solicitante <b style="color: red;">*</b></label>
                    <select name="tipo_solicitante" id="tipo_solicitante" class="form-select" required>
                        <option value="PROPIETARIO">PROPIETARIO</option>
                        <option value="TRAMITADOR">TRAMITADOR</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <input type="hidden" id="id_tramite" name="id_tramite" value="<?= isset($tramite) ? $tramite['id_tramite'] : '' ?>">
                    <label class="form-label">Tipo de tr&aacute;mite <b style="color: red;">*</b></label>
                    <select name="tipo_tramite" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($tipos as $item): ?>
                            <option value="<?= esc($item['id_tipo_tramite']) ?>"
                                <?= isset($directory['id_tipo_tramite']) && $directory['id_tipo_tramite'] == $item['catid_tipo_tramiteegory_id'] ? 'selected' : '' ?>>
                                <?= esc($item['nombre_tramite']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">CI Escaneado (PDF) <b style="color: red;">*</b></label>
                    <input type="file" name="doc_ci" class="form-control" accept=".pdf" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Memorial (PDF) <b style="color: red;">*</b></label>
                    <input type="file" name="doc_memorial" class="form-control" accept=".pdf" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Folio Real (PDF) <b style="color: red;">*</b></label>
                    <input type="file" name="doc_folio" class="form-control" accept=".pdf" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Plano (PDF) <b style="color: red;">*</b></label>
                    <input type="file" name="doc_plano" class="form-control" accept=".pdf" required>
                </div>

                <hr>
                <div id="section_tramitador" style="display: none;">
                    <hr>
                    <h5><b>Tramitador (Opcional)</b></h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Poder de Tramitador</label>
                            <input type="file" name="doc_poder" class="form-control" accept=".pdf">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CI Tramitador</label>
                            <input type="file" name="doc_ci_tramitador" class="form-control" accept=".pdf">
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4 text-end">
                    <a href="<?= site_url('directorio') ?>"
                        class="btn btn-secundary"
                        onclick="return confirm('¿Estás seguro de que deseas cancelar? Se perderán los cambios no guardados.')">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-primary btn-flat float-end">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script src="<?= base_url('assets/aditional/procedure.js') ?>"></script>
<?php $this->endSection(); ?>