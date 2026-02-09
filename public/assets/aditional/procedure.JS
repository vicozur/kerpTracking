document.addEventListener('DOMContentLoaded', function () {
    const myForm = document.getElementById('myForm');
    const btnSave = myForm.querySelector('button[type="button"]'); // Seleccionamos tu botón de guardar

    const selectSolicitante = document.getElementById('tipo_solicitante');
    const sectionTramitador = document.getElementById('section_tramitador');
    const inputsTramitador = sectionTramitador.querySelectorAll('input[type="file"]');

    function toggleTramitador() {
        if (selectSolicitante.value === 'TRAMITADOR') {
            sectionTramitador.style.display = 'block';
        } else {
            sectionTramitador.style.display = 'none';
            // Limpiar los archivos si se oculta la sección
            inputsTramitador.forEach(input => input.value = '');
        }
    }

    // Escuchar el cambio
    selectSolicitante.addEventListener('change', toggleTramitador);

    // Ejecutar al cargar por si hay un valor pre-seleccionado
    toggleTramitador();

    btnSave.addEventListener('click', async function (e) {
        
        // 1. Validaciones previas antes de enviar
        if (!validateForm()) return;

        // 2. Configuración de URL y Datos
        const idTramite = document.getElementById('id_tramite').value;
        const isUpdate = idTramite !== "";
        const url = isUpdate 
            ? `${TRACKING_URL}/procedureForm/update/${idTramite}` 
            : `${TRACKING_URL}/procedureForm/create`;

        const formData = new FormData(myForm);
        
        // Agregamos el Token CSRF manualmente si no está en el form
        formData.append(CI_CSRF_NAME, CI_CSRF_HASH);

        // 3. UI: Bloquear botón para evitar doble clic
        btnSave.disabled = true;
        btnSave.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Procesando...`;
        
        // 1. Preguntar al usuario antes de hacer nada
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Usted registrará datos para efectuar la solicitud de tramite en la plataforma", // Ajustado a tu campo 'propietario'
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Sí, registrar!",
            cancelButtonText: "Cancelar"
        }).then(async (result) => {
            // 2. Si el usuario confirma, procedemos con el envío
            if (result.isConfirmed) {
                
                // Mostrar alerta de "Cargando" mientras se suben los archivos BYTEA
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Subiendo documentos al sistema, por favor espere.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const resultJson = await response.json();

                    if (resultJson.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Registrado!',
                            text: 'El trámite ha sido guardado con éxito.',
                        }).then(() => {
                            window.location.href = `${TRACKING_URL}`;
                        });
                    } else {
                        Swal.fire("Error", resultJson.message, "error");
                        resetButton(btnSave);
                    }
                } catch (error) {
                    Swal.fire("Error de conexión", "No se pudo completar la operación.", "error");
                    resetButton(btnSave);
                }
            }
        });



    });

    // --- Funciones de Apoyo ---

    function validateForm() {
        // Validar campos requeridos nativos de HTML5
        if (!myForm.checkValidity()) {
            myForm.reportValidity();
            return false;
        }

        // Validar tamaño de archivos (ejemplo: max 2MB por archivo)
        const inputsFile = myForm.querySelectorAll('input[type="file"]');
        const MAX_SIZE = 2 * 1024 * 1024; // 2MB

        for (let input of inputsFile) {
            if (input.files.length > 0) {
                const file = input.files[0];
                if (file.size > MAX_SIZE) {
                    alert(`El archivo ${file.name} es muy pesado. Máximo 2MB.`);
                    return false;
                }
            }
        }
        return true;
    }

    function resetButton(btn) {
        btn.disabled = false;
        btn.innerHTML = 'Guardar';
    }
});