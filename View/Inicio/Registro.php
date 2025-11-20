<?php
    // Incluir el controller
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/InicioController.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutExterno.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Shareflix</title>
    <?php ShowCSS(); ?>
</head>

<body>

    <div class="auth-container">
        <div class="auth-card">
            
            <!-- LOGO Y TÍTULO -->
            <div class="auth-header">
                <h1 class="brand-text">SHAREFLIX</h1>
                <h2 class="auth-title">Crear Cuenta</h2>
                <p class="text-muted">Únete a nuestra comunidad</p>
            </div>

            <!-- MOSTRAR MENSAJES -->
            <?php if(isset($_POST["Mensaje"])): ?>
                <div class="alert-shareflix <?php echo $_POST['TipoMensaje'] == 'success' ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo $_POST["Mensaje"]; ?>
                </div>
            <?php endif; ?>

            <!-- FORMULARIO DE REGISTRO -->
            <form method="POST" id="formRegistro">

                <!-- IDENTIFICACIÓN -->
                <div class="mb-3">
                    <label for="txtCedula" class="form-label">
                        <i class="bi bi-card-text me-2"></i>Cédula
                    </label>
                    <div class="d-flex gap-2">
                        <input type="text" 
                               class="form-control-shareflix" 
                               id="txtCedula" 
                               name="txtCedula" 
                               placeholder="1-2345-6789"
                               required
                               style="flex: 1; color: #ffffff !important;">
                        <button type="button" 
                                class="btn-outline-shareflix" 
                                id="btnConsultarCedula"
                                title="Consultar en Registro Civil"
                                style="padding: 12px 20px; border-radius: 10px;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <small class="text-muted" style="display: block; margin-top: 0.5rem;">
                        Formato: 1-2345-6789 (Costa Rica 🇨🇷)
                    </small>
                    <div id="mensajeCedula" class="mt-2"></div>
                </div>

                <!-- NOMBRE COMPLETO -->
                <div class="mb-3">
                    <label for="txtNombre" class="form-label">
                        <i class="bi bi-person-fill me-2"></i>Nombre Completo
                    </label>
                    <input type="text" 
                           class="form-control-shareflix" 
                           id="txtNombre" 
                           name="txtNombre" 
                           placeholder="Tu nombre completo"
                           required
                           style="color: #ffffff !important;">
                </div>

                <!-- CORREO ELECTRÓNICO -->
                <div class="mb-3">
                    <label for="txtCorreo" class="form-label">
                        <i class="bi bi-envelope-fill me-2"></i>Correo Electrónico
                    </label>
                    <input type="email" 
                           class="form-control-shareflix" 
                           id="txtCorreo" 
                           name="txtCorreo" 
                           placeholder="correo@ejemplo.com"
                           required
                           style="color: #ffffff !important;">
                    <div id="mensajeCorreo" class="mt-2"></div>
                </div>

                <!-- CONTRASEÑA -->
                <div class="mb-3">
                    <label for="txtContrasenna" class="form-label">
                        <i class="bi bi-lock-fill me-2"></i>Contraseña
                    </label>
                    <div class="d-flex gap-2">
                        <input type="password" 
                               class="form-control-shareflix" 
                               id="txtContrasenna" 
                               name="txtContrasenna" 
                               placeholder="Mínimo 8 caracteres"
                               required
                               style="flex: 1; color: #ffffff !important;">
                        <button type="button"
                                class="btn-outline-shareflix" 
                                id="btnMostrarContrasenna"
                                style="padding: 12px 20px; border-radius: 10px;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div id="fortalezaContrasenna" class="mt-2"></div>
                </div>

                <!-- CONFIRMAR CONTRASEÑA -->
                <div class="mb-4">
                    <label for="txtConfirmarContrasenna" class="form-label">
                        <i class="bi bi-lock-fill me-2"></i>Confirmar Contraseña
                    </label>
                    <input type="password" 
                           class="form-control-shareflix" 
                           id="txtConfirmarContrasenna" 
                           name="txtConfirmarContrasenna" 
                           placeholder="Repite tu contraseña"
                           required
                           style="color: #ffffff !important;">
                    <div id="mensajeConfirmacion" class="mt-2"></div>
                </div>

                <!-- TÉRMINOS Y CONDICIONES -->
                <div class="mb-4">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" 
                               id="chkTerminos" 
                               required
                               style="width: 18px; height: 18px; cursor: pointer; accent-color: #FF8C42;">
                        <span class="text-muted" style="font-size: 0.9rem;">
                            Acepto los términos y condiciones de uso
                        </span>
                    </label>
                </div>

                <!-- BOTÓN DE REGISTRO -->
                <button type="submit" 
                        name="btnRegistrar" 
                        class="btn-shareflix btn-primary-shareflix w-100 mb-3"
                        id="btnRegistrar">
                    <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta
                </button>

                <!-- ENLACE A LOGIN -->
                <div class="text-center mt-3">
                    <span class="text-muted">
                        ¿Ya tienes cuenta? 
                    </span>
                    <a href="IniciarSesion.php" class="link-shareflix">
                        Inicia Sesión
                    </a>
                </div>

            </form>

      
    <?php ShowJS(); ?>
    <script src="../js/Registro.js"></script>

</body>
</html>