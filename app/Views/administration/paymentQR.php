<?php echo $this->extend('template/layout'); ?>

<?php $this->section('content'); ?>

<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="text-center p-5 shadow-sm rounded-4 bg-white" style="max-width: 600px; border-top: 5px solid #ffc107;">

        <div class="mb-4">
            <i class="bi bi-tools text-warning" style="font-size: 5rem;"></i>
        </div>

        <h1 class="display-5 fw-bold text-dark">Sitio en Pausa Técnica</h1>
        <p class="lead text-secondary">
            Estamos realizando mejoras en el motor de búsqueda y la gestión de <b>múltiples imágenes</b> para los trámites.
        </p>

        <div class="progress mb-4" style="height: 10px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 75%"></div>
        </div>

        <p class="text-muted small">
            Estimado usuario, el sistema de <b>Tracking G.A.M.C.</b> volverá a estar disponible en unos minutos.
            Agradecemos su comprensión mientras optimizamos su experiencia.
        </p>

        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
            <a href="<?= base_url('login') ?>" class="btn btn-primary btn-lg px-4 gap-3 shadow">
                <i class="bi bi-arrow-clockwise"></i> Verificar Disponibilidad
            </a>
            <button onclick="window.location.reload();" class="btn btn-outline-secondary btn-lg px-4">
                Recargar
            </button>
        </div>

        <div class="mt-5 opacity-50">
            <img src="<?= base_url('assets/img/logoColcapirua.png') ?>" alt="Logo G.A.M.C." style="max-width: 120px;">
        </div>
    </div>
</div>

<style>
    /* Efecto de flotación suave para el icono */
    .bi-tools {
        display: inline-block;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-15px);
        }

        100% {
            transform: translateY(0px);
        }
    }
</style>

<?php $this->endSection(); ?>