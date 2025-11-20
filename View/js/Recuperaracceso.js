

 //SECCIÓN 1: VALIDACIÓN FORMULARIO RECUPERAR

document.addEventListener('DOMContentLoaded', function() {
    const formRecuperacion = document.getElementById('formRecuperacion');
    
    if (formRecuperacion) {
        // Validar al enviar el formulario de recuperación
        formRecuperacion.addEventListener('submit', function(event) {
            const email = document.getElementById('CorreoElectronico').value.trim();
            
            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                event.preventDefault();
                mostrarError('Por favor ingresa un correo electrónico válido');
                return false;
            }
            
            // Cambiar texto del botón mientras se procesa
            const btnEnviar = document.getElementById('btnEnviarLink');
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
        });
    }
});


 // SECCIÓN 2: VALIDACIÓN FORMULARIO RESTABLECER


document.addEventListener('DOMContentLoaded', function() {
    const formRestablecer = document.getElementById('formRestablecer');
    
    if (formRestablecer) {
        const inputNueva = document.getElementById('ContrasenaNueva');
        const inputConfirmar = document.getElementById('ContrasenaConfirmar');
        
        // Verificar fortaleza de la contraseña al escribir
        if (inputNueva) {
            inputNueva.addEventListener('input', function() {
                verificarFortalezaContrasena(this.value);
            });
        }
        
        // Verificar coincidencia al escribir en confirmar
        if (inputConfirmar) {
            inputConfirmar.addEventListener('input', verificarCoincidencia);
        }
        
        // Validar al enviar el formulario
        formRestablecer.addEventListener('submit', function(event) {
            const nueva = inputNueva.value;
            const confirmar = inputConfirmar.value;
            
            let errores = [];
            
            // Validar longitud mínima
            if (nueva.length < 6) {
                errores.push('La contraseña debe tener al menos 6 caracteres');
            }
            
            // Validar coincidencia
            if (nueva !== confirmar) {
                errores.push('Las contraseñas no coinciden');
            }
            
            // Si hay errores, prevenir el envío
            if (errores.length > 0) {
                event.preventDefault();
                mostrarError(errores.join('<br>'));
                return false;
            }
            
            // Cambiar texto del botón mientras se procesa
            const btnRestablecer = document.getElementById('btnRestablecerContrasenna');
            btnRestablecer.disabled = true;
            btnRestablecer.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
        });
    }
});

 //SECCIÓN 3: VERIFICAR COINCIDENCIA DE CONTRASEÑAS

function verificarCoincidencia() {
    const inputNueva = document.getElementById('ContrasenaNueva');
    const inputConfirmar = document.getElementById('ContrasenaConfirmar');
    const mensajeDiv = document.getElementById('mensajeCoincidencia');
    
    if (!inputNueva || !inputConfirmar || !mensajeDiv) return;
    
    const nueva = inputNueva.value;
    const confirmar = inputConfirmar.value;
    
    // Solo validar si el campo de confirmar no está vacío
    if (confirmar.length === 0) {
        mensajeDiv.innerHTML = '';
        inputConfirmar.classList.remove('is-valid', 'is-invalid');
        return;
    }
    
    if (nueva === confirmar) {
        // Contraseñas coinciden
        mensajeDiv.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i><span class="text-success">Las contraseñas coinciden</span>';
        inputConfirmar.classList.remove('is-invalid');
        inputConfirmar.classList.add('is-valid');
    } else {
        // Contraseñas NO coinciden
        mensajeDiv.innerHTML = '<i class="bi bi-x-circle-fill text-danger me-1"></i><span class="text-danger">Las contraseñas no coinciden</span>';
        inputConfirmar.classList.remove('is-valid');
        inputConfirmar.classList.add('is-invalid');
    }
}


 // SECCIÓN 4: VERIFICAR FORTALEZA DE CONTRASEÑA
 

function verificarFortalezaContrasena(password) {
    const barra = document.getElementById('barraFortaleza');
    const texto = document.getElementById('textoFortaleza');
    
    if (!barra || !texto) return;
    
    // Calcular puntuación de fortaleza
    let puntuacion = 0;
    let feedback = [];
    
    // Longitud
    if (password.length >= 6) puntuacion += 20;
    if (password.length >= 8) puntuacion += 20;
    if (password.length >= 10) puntuacion += 10;
    
    // Contiene números
    if (/\d/.test(password)) {
        puntuacion += 20;
        feedback.push('números');
    }
    
    // Contiene minúsculas
    if (/[a-z]/.test(password)) {
        puntuacion += 10;
    }
    
    // Contiene mayúsculas
    if (/[A-Z]/.test(password)) {
        puntuacion += 15;
        feedback.push('mayúsculas');
    }
    
    // Contiene caracteres especiales
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
        puntuacion += 15;
        feedback.push('caracteres especiales');
    }
    
    // Determinar nivel de fortaleza
    let nivel = '';
    let colorClass = '';
    let mensaje = '';
    
    if (password.length === 0) {
        barra.style.width = '0%';
        texto.innerHTML = '';
        return;
    }
    
    if (puntuacion < 40) {
        nivel = 'Débil';
        colorClass = 'bg-danger';
        mensaje = '<span class="text-danger">Contraseña débil. Agrega más caracteres.</span>';
    } else if (puntuacion < 60) {
        nivel = 'Regular';
        colorClass = 'bg-warning';
        mensaje = '<span class="text-warning">Contraseña regular. Puedes mejorarla.</span>';
    } else if (puntuacion < 80) {
        nivel = 'Buena';
        colorClass = 'bg-info';
        mensaje = '<span class="text-info">Contraseña buena. Casi perfecta.</span>';
    } else {
        nivel = 'Excelente';
        colorClass = 'bg-success';
        mensaje = '<span class="text-success">Contraseña excelente y segura.</span>';
    }
    
    // Actualizar barra de progreso
    barra.style.width = puntuacion + '%';
    barra.className = 'progress-bar ' + colorClass;
    
    // Actualizar texto
    let textoCompleto = mensaje;
    if (feedback.length > 0) {
        textoCompleto += ' <small>(Incluye: ' + feedback.join(', ') + ')</small>';
    }
    texto.innerHTML = textoCompleto;
}


 // SECCIÓN 5: FUNCIÓN PARA MOSTRAR ERRORES
 
function mostrarError(mensaje) {
    // Buscar si ya existe un alert de error
    let alertDiv = document.getElementById('alertError');
    
    // Si no existe, crearlo
    if (!alertDiv) {
        alertDiv = document.createElement('div');
        alertDiv.id = 'alertError';
        
        // Insertar al inicio del primer formulario que encuentre
        const form = document.querySelector('form');
        if (form) {
            form.insertBefore(alertDiv, form.firstChild);
        }
    }
    
    // Configurar el contenido del alert
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Scroll hacia arriba para ver el error
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Auto-remover después de 5 segundos
    setTimeout(function() {
        if (alertDiv && alertDiv.parentNode) {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 150);
        }
    }, 5000);
}

 // SECCIÓN 6: VALIDACIÓN DE EMAIL EN TIEMPO REAL

 

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('CorreoElectronico');
    
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email.length > 0) {
                if (emailRegex.test(email)) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    }
});

