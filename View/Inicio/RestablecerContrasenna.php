<?php
  // Iniciar sesión solo si no hay una activa
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/InicioController.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutExterno.php';


  // Verificar si hay un token en la URL
  $token = isset($_GET["token"]) ? $_GET["token"] : "";
  $tokenValido = false;
  $mensajeError = "";

  // Validar el token
  if($token != "")
  {
      $validacion = ValidarTokenRecuperacionController($token);
      $tokenValido = $validacion["valido"];
      $mensajeError = $validacion["mensaje"];
  }
  else
  {
      $mensajeError = "No se proporcionó un token de recuperación válido.";
  }

  // Procesar el formulario cuando se envía
  if(isset($_POST["btnRestablecerContrasenna"]))
  {
      $resultado = RestablecerContrasennaController();
      
      if($resultado != null)
      {
          $_POST["Mensaje"] = $resultado["mensaje"];
          $_POST["TipoMensaje"] = $resultado["tipo"];
          
          // Si fue exitoso, limpiar el token para que no se pueda reutilizar
          if($resultado["tipo"] === "success")
          {
              $tokenValido = false;
          }
      }
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Shareflix</title>
    <?php ShowCSS(); ?>
</head>

<body>

    <div class="auth-container">
        <div class="auth-card">
            
            <!-- LOGO Y TÍTULO -->
            <div class="auth-header">
                <h1 class="brand-text">SHAREFLIX</h1>
                <h2 class="auth-title">Crear Nueva Contraseña</h2>
                <p class="text-muted">Elige una contraseña segura para tu cuenta</p>
            </div>

            <?php if($tokenValido): ?>
                <!-- SI EL TOKEN ES VÁLIDO, MOSTRAR FORMULARIO -->
                
                <!-- ICONO ILUSTRATIVO -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px; background: rgba(40, 167, 69, 0.1); border-radius: 50%;">
                        <i class="bi bi-shield-check" style="font-size: 2.5rem; color: #28a745;"></i>
                    </div>
                </div>

                <!-- MENSAJES DE RESULTADO -->
                <?php if(isset($_POST["Mensaje"])): ?>
                    <div class="alert-shareflix <?php echo $_POST['TipoMensaje'] === 'success' ? 'alert-success' : 'alert-error'; ?>">
                        <i class="bi <?php echo $_POST['TipoMensaje'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'; ?> me-2"></i>
                        <?php echo $_POST["Mensaje"]; ?>
                    </div>
                    
                    <?php if($_POST["TipoMensaje"] === "success"): ?>
                        <!-- Botón para ir al login -->
                        <div class="text-center mb-3">
                            <a href="IniciarSesion.php" class="btn-shareflix btn-primary-shareflix w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Ir a Iniciar Sesión
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- FORMULARIO DE RESTABLECIMIENTO -->
                <?php if(!isset($_POST["TipoMensaje"]) || $_POST["TipoMensaje"] !== "success"): ?>
                <form id="formRestablecer" method="POST" action="">
                    
                    <!-- TOKEN OCULTO -->
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <!-- NUEVA CONTRASEÑA -->
                    <div class="mb-3">
                        <label for="ContrasenaNueva" class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>Nueva Contraseña
                        </label>
                        <div class="d-flex gap-2">
                            <input 
                                type="password" 
                                class="form-control-shareflix" 
                                id="ContrasenaNueva" 
                                name="ContrasenaNueva"
                                placeholder="Mínimo 6 caracteres"
                                minlength="6"
                                required
                                autofocus
                                style="flex: 1; color: #ffffff !important;"
                            />
                            <button 
                                type="button"
                                class="btn-outline-shareflix" 
                                onclick="togglePassword('ContrasenaNueva', 'iconNueva')"
                                style="padding: 12px 20px; border-radius: 10px;"
                            >
                                <i class="bi bi-eye" id="iconNueva"></i>
                            </button>
                        </div>
                        <small class="text-muted" style="display: block; margin-top: 0.5rem;">
                            <i class="bi bi-info-circle me-1"></i>Usa al menos 6 caracteres
                        </small>
                    </div>

                    <!-- CONFIRMAR CONTRASEÑA -->
                    <div class="mb-4">
                        <label for="ContrasenaConfirmar" class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>Confirmar Contraseña
                        </label>
                        <div class="d-flex gap-2">
                            <input 
                                type="password" 
                                class="form-control-shareflix" 
                                id="ContrasenaConfirmar" 
                                name="ContrasenaConfirmar"
                                placeholder="Repite la contraseña"
                                minlength="6"
                                required
                                style="flex: 1; color: #ffffff !important;"
                            />
                            <button 
                                type="button"
                                class="btn-outline-shareflix" 
                                onclick="togglePassword('ContrasenaConfirmar', 'iconConfirmar')"
                                style="padding: 12px 20px; border-radius: 10px;"
                            >
                                <i class="bi bi-eye" id="iconConfirmar"></i>
                            </button>
                        </div>
                        <div id="mensajeCoincidencia" class="mt-2"></div>
                    </div>

                    <!-- INDICADOR DE FORTALEZA -->
                    <div class="mb-4">
                        <label class="form-label" style="font-size: 0.9rem;">Fortaleza de la contraseña:</label>
                        <div style="height: 8px; background: rgba(255, 255, 255, 0.1); border-radius: 4px; overflow: hidden;">
                            <div id="barraFortaleza" style="height: 100%; width: 0%; transition: width 0.3s, background 0.3s;"></div>
                        </div>
                        <small id="textoFortaleza" class="text-muted" style="display: block; margin-top: 0.5rem;"></small>
                    </div>

                    <!-- BOTÓN RESTABLECER -->
                    <button 
                        type="submit" 
                        class="btn-shareflix btn-primary-shareflix w-100 mb-3" 
                        id="btnRestablecerContrasenna" 
                        name="btnRestablecerContrasenna"
                    >
                        <i class="bi bi-check-circle-fill me-2"></i>Restablecer Contraseña
                    </button>

                </form>
                <?php endif; ?>

            <?php else: ?>
                <!-- SI EL TOKEN NO ES VÁLIDO, MOSTRAR ERROR -->
                
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px; background: rgba(220, 53, 69, 0.1); border-radius: 50%;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 2.5rem; color: #dc3545;"></i>
                    </div>
                </div>

                <div class="alert-shareflix alert-error">
                    <h5 style="margin-bottom: 0.5rem;">
                        <i class="bi bi-x-circle-fill me-2"></i>Link Inválido o Expirado
                    </h5>
                    <p style="margin-bottom: 0;"><?php echo htmlspecialchars($mensajeError); ?></p>
                </div>

                <div style="background: rgba(255, 140, 66, 0.05); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                    <p class="text-muted" style="margin-bottom: 0; font-size: 0.9rem;">
                        Este link de recuperación no es válido o ya expiró. Los links de recuperación solo son válidos por 1 hora por seguridad.
                    </p>
                </div>

                <a href="RecuperarAcceso.php" class="btn-shareflix btn-primary-shareflix w-100">
                    <i class="bi bi-arrow-clockwise me-2"></i>Solicitar Nuevo Link
                </a>

            <?php endif; ?>

        </div>

        <!-- FOOTER -->
        <div class="text-center mt-4">
            <p class="text-muted" style="font-size: 0.9rem;">
                <a href="IniciarSesion.php" class="link-back">
                    <i class="bi bi-arrow-left me-1"></i>Volver a Iniciar Sesión
                </a>
            </p>
        </div>

    </div>

    <?php ShowJS(); ?>
    <script src="../js/RecuperarAcceso.js"></script>

    <script>
    // Función para mostrar/ocultar contraseña
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // Verificar coincidencia de contraseñas
    document.addEventListener('DOMContentLoaded', function() {
        const nueva = document.getElementById('ContrasenaNueva');
        const confirmar = document.getElementById('ContrasenaConfirmar');
        const mensaje = document.getElementById('mensajeCoincidencia');
        
        function verificarCoincidencia() {
            if(confirmar.value.length > 0) {
                if(nueva.value === confirmar.value) {
                    mensaje.innerHTML = '<small class="text-success">✓ Las contraseñas coinciden</small>';
                } else {
                    mensaje.innerHTML = '<small style="color: #dc3545;">✗ Las contraseñas no coinciden</small>';
                }
            } else {
                mensaje.innerHTML = '';
            }
        }
        
        nueva.addEventListener('keyup', verificarCoincidencia);
        confirmar.addEventListener('keyup', verificarCoincidencia);
        
        // Indicador de fortaleza
        nueva.addEventListener('keyup', function() {
            const valor = this.value;
            const barra = document.getElementById('barraFortaleza');
            const texto = document.getElementById('textoFortaleza');
            let fortaleza = 0;
            let mensaje = '';
            let color = '';
            
            if(valor.length >= 6) fortaleza++;
            if(valor.length >= 8) fortaleza++;
            if(/[a-z]/.test(valor) && /[A-Z]/.test(valor)) fortaleza++;
            if(/[0-9]/.test(valor)) fortaleza++;
            if(/[^a-zA-Z0-9]/.test(valor)) fortaleza++;
            
            const porcentaje = (fortaleza / 5) * 100;
            
            if(fortaleza <= 2) {
                color = '#dc3545';
                mensaje = 'Débil';
            } else if(fortaleza <= 3) {
                color = '#ffc107';
                mensaje = 'Media';
            } else {
                color = '#28a745';
                mensaje = 'Fuerte';
            }
            
            barra.style.width = porcentaje + '%';
            barra.style.background = color;
            texto.textContent = mensaje;
            texto.style.color = color;
        });
    });
    </script>
</body>
</html>