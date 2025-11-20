<?php
// ✅ 1. OUTPUT BUFFERING (PRIMERO DE TODO)
ob_start();

// ✅ 2. INICIAR SESIÓN
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ✅ 3. Incluir el controlador
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/InicioController.php';

// ✅ 4. Incluir el layout
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutExterno.php';
?>

<!DOCTYPE html>
<html lang="es">
  
  <?php ShowCSS(); ?>

  <body>
    <div class="auth-container">
      <div class="auth-card">
        
        <!-- Logo y Título -->
        <div class="auth-header">
           <h1 class="brand-text">SHAREFLIX</h1>
          <h2 class="auth-title">Iniciar Sesión</h2>
          <p class="text-muted">Bienvenido de nuevo a Shareflix</p>
        </div>

        <!-- Mensajes -->
        <?php MostrarMensaje(); ?>

        <!-- Formulario -->
        <form id="formInicioSesion" method="POST" action="">
          
          <div class="mb-3">
            <label for="txtCorreo" class="form-label">
              <i class="fas fa-envelope"></i> Correo Electrónico
            </label>
            <input 
              type="email" 
              class="form-control form-control-shareflix" 
              id="txtCorreo" 
              name="txtCorreo" 
              placeholder="tu@correo.com"
              required
            />
          </div>

          <div class="mb-3">
            <label for="txtContrasenna" class="form-label">
              <i class="fas fa-lock"></i> Contraseña
            </label>
            <input 
              type="password" 
              class="form-control form-control-shareflix" 
              id="txtContrasenna" 
              name="txtContrasenna" 
              placeholder="••••••••"
              required
            />
          </div>

          <div class="d-flex justify-content-end mb-3">
            <a href="RecuperarAcceso.php" class="link-shareflix-secondary">
              ¿Olvidaste tu contraseña?
            </a>
          </div>

          <button 
            type="submit" 
            class="btn-shareflix btn-primary-shareflix w-100" 
            id="btnIniciarSesion" 
            name="btnIniciarSesion"
          >
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
          </button>

        </form>

        <hr class="divider-shareflix">

        <p class="text-center text-muted">
          ¿No tienes cuenta?
          <a href="Registro.php" class="link-shareflix">
            Regístrate gratis
          </a>
        </p>

      </div>
    </div>

    <?php ShowJS(); ?>
    <script src="../js/InicioSesion.js"></script>
  
  </body>
</html>

<?php
// ✅ 5. ENVIAR EL BUFFER AL FINAL
ob_end_flush();
?>