<?php
  // Iniciar sesión solo si no hay una activa
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

 include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/InicioController.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutExterno.php';
 

  // Procesar el formulario cuando se envía
  if(isset($_POST["btnEnviarLink"]))
  {
      $resultado = EnviarLinkRecuperacionController();
      
      if($resultado != null)
      {
          $_POST["Mensaje"] = $resultado["mensaje"];
          $_POST["TipoMensaje"] = $resultado["tipo"]; // success o error
      }
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Acceso - Shareflix</title>
    <?php ShowCSS(); ?>
</head>

<body>

    <div class="auth-container">
        <div class="auth-card">
            
            <!-- LOGO Y TÍTULO -->
            <div class="auth-header">
                <h1 class="brand-text">SHAREFLIX</h1>
                <h2 class="auth-title">¿Olvidaste tu contraseña?</h2>
                <p class="text-muted">No te preocupes, te ayudaremos a recuperarla</p>
            </div>

            <!-- ICONO ILUSTRATIVO -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center" 
                     style="width: 80px; height: 80px; background: rgba(255, 140, 66, 0.1); border-radius: 50%;">
                    <i class="bi bi-key-fill" style="font-size: 2.5rem; color: var(--color-primary);"></i>
                </div>
            </div>

            <!-- MENSAJES DE RESULTADO -->
            <?php if(isset($_POST["Mensaje"])): ?>
                <div class="alert-shareflix <?php echo $_POST['TipoMensaje'] === 'success' ? 'alert-success' : 'alert-error'; ?>">
                    <i class="bi <?php echo $_POST['TipoMensaje'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'; ?> me-2"></i>
                    <?php echo $_POST["Mensaje"]; ?>
                </div>
            <?php endif; ?>

            <!-- INSTRUCCIONES -->
            <div style="background: rgba(255, 140, 66, 0.05); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border-left: 4px solid var(--color-primary);">
                <small style="color: var(--color-text-muted);">
                    <i class="bi bi-info-circle me-2"></i>
                    Ingresa tu correo electrónico y te enviaremos un link para que puedas crear una nueva contraseña.
                </small>
            </div>

            <!-- FORMULARIO DE RECUPERACIÓN -->
            <form id="formRecuperacion" method="POST" action="">
                
                <!-- CORREO ELECTRÓNICO -->
                <div class="mb-4">
                    <label for="CorreoElectronico" class="form-label">
                        <i class="bi bi-envelope-fill me-2"></i>Correo Electrónico
                    </label>
                    <input 
                        type="email" 
                        class="form-control-shareflix" 
                        id="CorreoElectronico" 
                        name="CorreoElectronico"
                        placeholder="tucorreo@ejemplo.com"
                        required
                        autofocus
                        style="color: #ffffff !important;"
                    />
                    <small class="text-muted" style="display: block; margin-top: 0.5rem;">
                        <i class="bi bi-shield-check me-1"></i>
                        Debe ser el correo con el que te registraste
                    </small>
                </div>

                <!-- BOTÓN ENVIAR LINK -->
                <button 
                    type="submit" 
                    class="btn-shareflix btn-primary-shareflix w-100 mb-3" 
                    id="btnEnviarLink" 
                    name="btnEnviarLink"
                >
                    <i class="bi bi-send-fill me-2"></i>Enviar Link de Recuperación
                </button>

                <!-- SEPARADOR -->
                <hr class="divider-shareflix">

                <!-- LINKS ADICIONALES -->
                <div class="text-center">
                    <p class="mb-2">
                        <span class="text-muted">¿Recordaste tu contraseña?</span>
                        <a href="IniciarSesion.php" class="link-shareflix">
                            Iniciar Sesión <i class="bi bi-arrow-right"></i>
                        </a>
                    </p>
                    <p class="mb-0">
                        <span class="text-muted">¿No tienes cuenta?</span>
                        <a href="Registro.php" class="link-shareflix-secondary">
                            Regístrate gratis
                        </a>
                    </p>
                </div>

            </form>

        </div>

      

    </div>

    <?php ShowJS(); ?>
    <script src="../js/RecuperarAcceso.js"></script>
</body>
</html>