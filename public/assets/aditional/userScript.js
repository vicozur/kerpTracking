const ciInput = document.getElementById('ci');
const btnGuardar = document.getElementById('btnGuardar');

ciInput.addEventListener('blur', function() {
    const ci = this.value;
    if (ci.length < 5) return; // No validar si está casi vacío

    fetch('<?= base_url("user/checkCI") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'ci=' + ci + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            // Poner el campo en rojo
            ciInput.classList.add('is-invalid');
            btnGuardar.disabled = true; // Bloquear guardado
            
            // Crear o mostrar mensaje de error
            let errorDiv = document.getElementById('ci-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'ci-error';
                errorDiv.className = 'invalid-feedback';
                errorDiv.innerText = data.message;
                ciInput.parentNode.appendChild(errorDiv);
            }
        } else {
            // Quitar el error si el CI es nuevo o corregido
            ciInput.classList.remove('is-invalid');
            ciInput.classList.add('is-valid');
            btnGuardar.disabled = false;
            const errorDiv = document.getElementById('ci-error');
            if (errorDiv) errorDiv.remove();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const userForm = document.getElementById('userForm');
    const ciInput = document.getElementById('ci');
    const feedback = document.getElementById('ci-feedback');

    // 1. Pre-validación mientras escribe (opcional pero recomendado)
    ciInput.addEventListener('blur', function() {
        validarCI(this.value);
    });

    // 2. CAPTURA DEL SUBMIT (La verdadera pre-validación)
    userForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Detenemos el envío de inmediato

        const ciValue = ciInput.value;

        // Validar CI antes de proceder
        const existe = await validarCI(ciValue);

        if (existe) {
            Swal.fire('Error', 'No puedes guardar: El CI ya existe en el sistema.', 'error');
            return; // Detenemos la ejecución aquí
        }

        // Si el CI es válido, procedemos al guardado real por AJAX
        enviarFormulario();
    });

    // Función que consulta al servidor
    async function validarCI(ci) {
        if (ci.length < 3) return false;

        const csrfName = 'csrf_test_name'; // Verifica si este es el nombre en tu Config/App.php
        const csrfHash = document.querySelector('input[name="' + csrfName + '"]').value;

        const formData = new FormData();
        formData.append('ci', ci);
        formData.append(csrfName, csrfHash);

        formData.append('ci', ci);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        try {
            const response = await fetch('<?= base_url("user/checkCI") ?>', {
                method: 'POST',
                body: formData,
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            });
            const data = await response.json();

            if (data.exists) {
                ciInput.style.borderColor = "red";
                feedback.innerText = data.message;
                feedback.style.display = "block";
                return true; // Sí existe
            } else {
                ciInput.style.borderColor = "green";
                feedback.style.display = "none";
                return false; // No existe
            }
        } catch (error) {
            console.error("Error validando:", error);
            return false;
        }
    }

    function enviarFormulario() {
        const formData = new FormData(userForm);

        fetch(userForm.action, {
            method: "POST",
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" },
        }).then((res) => {
            if (!res.ok) throw new Error("Error en el servidor");
            return res.json();
        }).then((data) => {
            console.log("Respuesta servidor:", data); // Mira esto en la consola F12
            // IMPORTANTE: Verifica si tu controlador manda 'success' o 'SUCCESS'
            if (data.status === "success" || data.success === true) {
                Swal.fire({
                    title: "¡Guardado!",
                    text: data.message,
                    icon: "success",
                    confirmButtonText: "Ir al Login",
                }).then((result) => {
                    // Forzamos la redirección
                    window.location.href = LOGIN_URL;
                });
            } else {
                Swal.fire("Error", data.message || "Error desconocido", "error");
            }
        })
        .catch((error) => {
            console.error("Error en fetch:", error);
            Swal.fire(
                "Error Critico",
                "No se pudo procesar la solicitud",
                "error",
            );
        });
    }
    

});