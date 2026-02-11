<!DOCTYPE html>
<html lang="es">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tracking G.A.M.C. | Crear usuario</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="I.S.T.Zurita" />
    <meta
        name="description"
        content="Internal System is an application for recording information on G.A.M.C. for trackink proccess" />
    <meta name="keywords" content="clients, tramite, informacion" />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= base_url('assets/css/adminlte.css') ?>" />
    
    <!--end::Required Plugin(AdminLTE)-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body>
    <div class="row">
        <div class="login-logo">
                <img src="<?= base_url('assets/img/logoColcapirua.png') ?>" alt="G.A.M.C." style="margin-top:10px;  max-width:15%; height:auto;">
                <h4><b style="color: darkblue;">formulario de registro de usuario</b></h4>
            </div>
    </div>
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
                    <div class="row">
                        <div class="col-md-12 mb-12">
                            <label class="form-label"><b>Nota: </b></label> El password o contraseña asignada para su cuenta es <b style="color: red;">Admin123.</b><br>
                            Recuerde modicifarla por su seguridad cuando ingrese a sistema.
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('login') ?>" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" id="btnGuardar" class="btn btn-primary">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    
    <!-- /.login-box -->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="<?= base_url('assets/js/adminlte.js') ?>"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    
    <script>
        const LOGIN_URL = '<?= base_url("login") ?>';
        const BASE_URL  = '<?= base_url() ?>';
        // Asegúrate de pasar el hash del token aquí si el script es externo
        const CSRF_HASH = '<?= csrf_hash() ?>'; 
    </script>

    <script src="<?= base_url('assets/aditional/userScript.js') ?>"></script>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
</body>
<!--end::Body-->

</html>