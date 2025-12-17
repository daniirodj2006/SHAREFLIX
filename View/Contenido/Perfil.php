<?php
  // Iniciar sesión
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // Verificar que el usuario esté logueado
  if(!isset($_SESSION["ConsecutivoUsuario"]))
  {
      header("Location: ../Inicio/IniciarSesion.php");
      exit();
  }

  // Incluir archivos necesarios
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutCliente.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/InicioController.php';

  // Obtener datos del usuario
  $nombre = $_SESSION["Nombre"];
  $correo = $_SESSION["CorreoElectronico"];
  $perfil = $_SESSION["NombrePerfil"];
  $suscripcion = $_SESSION["TipoSuscripcion"];
  $limiteFavoritos = $_SESSION["LimiteFavoritos"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Shareflix</title>
    <?php ShowCSS(); ?>
</head>

<body>
    <?php ShowMenu(); ?>

    <div class="content-wrapper" style="margin-left: 250px; padding: 2rem; min-height: 100vh;">
        <div class="container-fluid">
            
            <!-- HEADER -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h2 fw-bold text-white mb-1">
                        <i class="fas fa-user-circle me-2"></i>Mi Perfil
                    </h1>
                    <p class="text-muted">Información de tu cuenta</p>
                </div>
            </div>

            <!-- Mensajes -->
            <?php MostrarMensaje(); ?>

            <div class="row">
                
                <!-- TARJETA DE PERFIL PRINCIPAL -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            
                            <!-- Avatar -->
                            <div class="avatar-circle mx-auto mb-4" style="width: 120px; height: 120px; background: linear-gradient(135deg, #FF8C42, #FFA94D); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user" style="font-size: 3rem; color: white;"></i>
                            </div>

                            <!-- Nombre -->
                            <h3 class="text-white mb-2"><?php echo $nombre; ?></h3>
                            
                            <!-- Badge de suscripción -->
                            <div class="mb-3">
                                <?php if($suscripcion == "Premium"): ?>
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="fas fa-crown me-2"></i>Premium
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="fas fa-user me-2"></i>Gratis
                                    </span>
                                <?php endif; ?>
                            </div>

                            <p class="text-muted mb-4">
                                <i class="fas fa-envelope me-2 text-shareflix"></i>
                                <?php echo $correo; ?>
                            </p>

                            <!-- Estadísticas -->
                            <div class="row text-center mt-4">
                                <div class="col-6">
                                    <div class="bg-dark p-3 rounded">
                                        <h4 class="text-shareflix mb-0"><?php echo $limiteFavoritos; ?></h4>
                                        <small class="text-muted">Límite Favoritos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-dark p-3 rounded">
                                        <h4 class="text-shareflix mb-0"><?php echo $perfil; ?></h4>
                                        <small class="text-muted">Tipo</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón cerrar sesión -->
                            <form method="POST" class="mt-4">
                                <button type="submit" name="btnCerrarSesion" class="btn btn-shareflix w-100">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- INFORMACIÓN DETALLADA -->
                <div class="col-lg-8">
                    
                    <!-- Información Personal -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="text-white mb-4">
                                <i class="fas fa-info-circle me-2 text-shareflix"></i>
                                Información Personal
                            </h5>

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 bg-dark rounded">
                                        <label class="text-shareflix fw-semibold mb-2">
                                            <i class="fas fa-user me-2"></i>Nombre Completo
                                        </label>
                                        <p class="text-white mb-0"><?php echo $nombre; ?></p>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 bg-dark rounded">
                                        <label class="text-shareflix fw-semibold mb-2">
                                            <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                        </label>
                                        <p class="text-white mb-0"><?php echo $correo; ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-dark rounded">
                                        <label class="text-shareflix fw-semibold mb-2">
                                            <i class="fas fa-id-card me-2"></i>Tipo de Cuenta
                                        </label>
                                        <p class="text-white mb-0"><?php echo $perfil; ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-dark rounded">
                                        <label class="text-shareflix fw-semibold mb-2">
                                            <i class="fas fa-star me-2"></i>Suscripción
                                        </label>
                                        <p class="text-white mb-0"><?php echo $suscripcion; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Suscripción -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="text-white mb-4">
                                <i class="fas fa-crown me-2 text-shareflix"></i>
                                Mi Suscripción
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-dark rounded">
                                        <label class="text-shareflix fw-semibold mb-2">
                                            <i class="fas fa-star me-2"></i>Plan Actual
                                        </label>
                                        <p class="text-white mb-0">
                                            <?php echo $suscripcion; ?>
                                            <?php if($suscripcion == "Premium"): ?>
                                                <span class="badge bg-success ms-2">Activo</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-dark rounded">
                                        <label class="text-shareflix fw-semibold mb-2">
                                            <i class="fas fa-heart me-2"></i>Favoritos
                                        </label>
                                        <p class="text-white mb-0">
                                            <?php if($suscripcion == "Premium"): ?>
                                                ✨ Ilimitados
                                            <?php else: ?>
                                                Máximo <?php echo $limiteFavoritos; ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php if($suscripcion != "Premium"): ?>
                          
                            <?php endif; ?>
                        </div>
                    </div>

                  
                  
                                <div class="col-md-6">
                                    <form method="POST" style="margin: 0;">
                                        <button type="submit" name="btnCerrarSesion" class="btn btn-outline-danger w-100">
                                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php ShowJS(); ?>

</body>
</html>