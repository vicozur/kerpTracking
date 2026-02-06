<?php echo $this->extend('template/layout'); ?>
<?php $this->section('content'); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form id="userForm" action="<?= base_url('user/save') ?>" method="POST" enctype="multipart/form-data">

                <?= csrf_field() ?>

                <input type="hidden" id="user_id" name="user_id">

                <div class="row">
                    <div class="col-md-12 mb-3"> <label class="form-label">Tipo de Usuario <span class="text-danger">(*)</span></label>
                        <select name="tipo" id="tipo" class="form-control">
                            <option value="PROPIETARIO">PROPIETARIO</option>
                            <option value="REPRESENTANTE">REPRESENTANTE LEGAL</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Carnet de Identidad <span class="text-danger">(*)</span></label>
                        <input type="text" class="form-control" name="ci" id="ci" required autocomplete="off">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Extensión <span class="text-danger">(*)</span></label>
                        <select name="ext" id="ext" class="form-control">
                            <option value="CB">COCHABAMBA</option>
                            <option value="LPZ">LA PAZ</option>
                            <option value="OR">ORURO</option>
                            <option value="SCZ">SANTA CRUZ</option>
                            <option value="SC">SUCRE</option>
                            <option value="TRJ">TARIJA</option>
                            <option value="BEN">BENI</option>
                            <option value="PAN">PANDO</option>
                            <option value="POT">POTOSI</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombres <span class="text-danger">(*)</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellidos <span class="text-danger">(*)</span></label>
                        <input type="text" class="form-control" name="lastname" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Correo <span class="text-danger">(*)</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Teléfono <span class="text-danger">(*)</span></label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('usuarios') ?>" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" id="btnGuardar" class="btn btn-primary">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/aditional/userScript.js') ?>"></script>

<?php $this->endSection(); ?>