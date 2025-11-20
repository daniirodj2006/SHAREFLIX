// SECCIÓN 1: CONSULTAR API DE CÉDULA


document.addEventListener('DOMContentLoaded', function() {
    const btnConsultar = document.getElementById('btnConsultarCedula');
    const inputCedula = document.getElementById('txtCedula');
    const inputNombre = document.getElementById('txtNombre');
    const mensajeCedula = document.getElementById('mensajeCedula');

    if (btnConsultar) {
        btnConsultar.addEventListener('click', function() {
            const cedula = inputCedula.value.trim();

            if (!cedula) {
                mostrarMensaje(mensajeCedula, 'Por favor ingresa una cédula', 'warning');
                return;
            }

            // Validar formato básico (9 dígitos con o sin guiones)
            const cedulaSinGuiones = cedula.replace(/-/g, '');
            if (cedulaSinGuiones.length !== 9) {
                mostrarMensaje(mensajeCedula, 'La cédula debe tener 9 dígitos', 'danger');
                return;
            }

            // Deshabilitar botón mientras consulta
            btnConsultar.disabled = true;
            btnConsultar.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            // Limpiar nombre anterior
            inputNombre.value = '';

            console.log('Consultando cédula:', cedula);

            // Llamar a la API
            fetch('../../Controller/InicioController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'consultarCedula=1&cedula=' + encodeURIComponent(cedula)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Response text:', text);
                
                try {
                    const data = JSON.parse(text);
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        // Llenar el nombre automáticamente
                        inputNombre.value = data.nombre;
                        mostrarMensaje(mensajeCedula, '✓ Nombre encontrado: ' + data.nombre, 'success');
                    } else {
                        mostrarMensaje(mensajeCedula, '⚠ ' + data.mensaje, 'warning');
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    mostrarMensaje(mensajeCedula, 'Error: La respuesta del servidor no es válida', 'danger');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                mostrarMensaje(mensajeCedula, 'Error de conexión. Verifica tu internet.', 'danger');
            })
            .finally(() => {
                // Rehabilitar botón
                btnConsultar.disabled = false;
                btnConsultar.innerHTML = '<i class="bi bi-search"></i>';
            });
        });
    }
});


//SECCIÓN 2: VALIDAR CORREO DISPONIBLE


document.addEventListener('DOMContentLoaded', function() {
    const inputCorreo = document.getElementById('txtCorreo');
    const mensajeCorreo = document.getElementById('mensajeCorreo');

    if (inputCorreo) {
        inputCorreo.addEventListener('blur', function() {
            const correo = this.value.trim();

            if (!correo) {
                mensajeCorreo.innerHTML = '';
                return;
            }

            // Validar formato básico
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regex.test(correo)) {
                mostrarMensaje(mensajeCorreo, 'Formato de correo inválido', 'danger');
                return;
            }

            // Verificar disponibilidad
            fetch('../../Controller/InicioController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'validarCorreo=1&correo=' + encodeURIComponent(correo)
            })
            .then(response => response.json())
            .then(data => {
                if (data.disponible) {
                    mostrarMensaje(mensajeCorreo, '✓ Correo disponible', 'success');
                } else {
                    mostrarMensaje(mensajeCorreo, '✗ ' + data.mensaje, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});


 //SECCIÓN 3: VERIFICAR FORTALEZA DE CONTRASEÑA


document.addEventListener('DOMContentLoaded', function() {
    const inputContrasenna = document.getElementById('txtContrasenna');
    const fortalezaDiv = document.getElementById('fortalezaContrasenna');

    if (inputContrasenna) {
        inputContrasenna.addEventListener('keyup', function() {
            const contrasenna = this.value;

            if (!contrasenna) {
                fortalezaDiv.innerHTML = '';
                return;
            }

            // Verificar fortaleza
            fetch('../../Controller/InicioController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'verificarContrasena=1&contrasena=' + encodeURIComponent(contrasenna)
            })
            .then(response => response.json())
            .then(data => {
                let color = '';
                let icono = '';

                switch(data.nivel) {
                    case 'debil':
                        color = 'danger';
                        icono = 'x-circle';
                        break;
                    case 'media':
                        color = 'warning';
                        icono = 'exclamation-circle';
                        break;
                    case 'fuerte':
                        color = 'success';
                        icono = 'check-circle';
                        break;
                }

                fortalezaDiv.innerHTML = `
                    <div class="alert alert-${color} py-2 mb-0">
                        <i class="bi bi-${icono} me-2"></i>
                        <small>${data.mensaje}</small>
                    </div>
                `;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});


 //SECCIÓN 4: CONFIRMAR CONTRASEÑA

document.addEventListener('DOMContentLoaded', function() {
    const inputContrasenna = document.getElementById('txtContrasenna');
    const inputConfirmar = document.getElementById('txtConfirmarContrasenna');
    const mensajeConfirmacion = document.getElementById('mensajeConfirmacion');

    if (inputConfirmar) {
        inputConfirmar.addEventListener('keyup', function() {
            const contrasenna = inputContrasenna.value;
            const confirmar = this.value;

            if (!confirmar) {
                mensajeConfirmacion.innerHTML = '';
                return;
            }

            if (contrasenna === confirmar) {
                mostrarMensaje(mensajeConfirmacion, '✓ Las contraseñas coinciden', 'success');
            } else {
                mostrarMensaje(mensajeConfirmacion, '✗ Las contraseñas no coinciden', 'danger');
            }
        });
    }
});


 //SECCIÓN 5: MOSTRAR/OCULTAR CONTRASEÑA

document.addEventListener('DOMContentLoaded', function() {
    const btnMostrar = document.getElementById('btnMostrarContrasenna');
    const inputContrasenna = document.getElementById('txtContrasenna');

    if (btnMostrar) {
        btnMostrar.addEventListener('click', function() {
            if (inputContrasenna.type === 'password') {
                inputContrasenna.type = 'text';
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                inputContrasenna.type = 'password';
                this.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });
    }
});


 //SECCIÓN 6: VALIDACIÓN DEL FORMULARIO

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formRegistro');

    if (form) {
        form.addEventListener('submit', function(e) {
            const contrasenna = document.getElementById('txtContrasenna').value;
            const confirmar = document.getElementById('txtConfirmarContrasenna').value;
            const nombre = document.getElementById('txtNombre').value.trim();

            // Validar que el nombre no esté vacío
            if (!nombre) {
                e.preventDefault();
                alert('Por favor ingresa tu nombre completo');
                return false;
            }

            // Validar que las contraseñas coincidan
            if (contrasenna !== confirmar) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }

            // Validar longitud mínima
            if (contrasenna.length < 8) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 8 caracteres');
                return false;
            }
        });
    }
});


 //FUNCIÓN AUXILIAR: MOSTRAR MENSAJES
 

function mostrarMensaje(elemento, mensaje, tipo) {
    let icono = '';
    switch(tipo) {
        case 'success':
            icono = 'check-circle';
            break;
        case 'danger':
            icono = 'x-circle';
            break;
        case 'warning':
            icono = 'exclamation-triangle';
            break;
    }

    elemento.innerHTML = `
        <div class="alert alert-${tipo} py-2 mb-0">
            <i class="bi bi-${icono} me-2"></i>
            <small>${mensaje}</small>
        </div>
    `;
}

