<?php
  // Iniciar sesión
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // Verificar que sea administrador
  if(!isset($_SESSION["ConsecutivoPerfil"]) || $_SESSION["ConsecutivoPerfil"] != 1)
  {
      header("Location: ../Inicio/IniciarSesion.php");
      exit();
  }

  // Incluir archivos necesarios
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutAdmin.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/InicioController.php';

  // Obtener datos del administrador
  $nombre = $_SESSION["Nombre"];
  $correo = $_SESSION["CorreoElectronico"];
  $perfil = $_SESSION["NombrePerfil"];
  $suscripcion = $_SESSION["TipoSuscripcion"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Admin Shareflix</title>
    <?php ShowCSS(); ?>
</head>

<body>
    <?php ShowMenu(); ?>

    <div class="content-wrapper">
        <div class="container-fluid py-4">
            
            <!-- HEADER -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="dashboard-title">
                        <i class="bi bi-person-circle me-2"></i>Mi Perfil de Administrador
                    </h1>
                    <p class="text-muted">Panel de control de tu cuenta administrativa</p>
                </div>
            </div>

            <?php MostrarMensaje(); ?>

            <div class="row">
                
                <!-- TARJETA DE PERFIL PRINCIPAL -->
                <div class="col-lg-4 mb-4">
                    <div class="content-card">
                        <div class="card-body text-center py-5">
                            
                            <!-- Avatar Admin -->
                            <div class="mx-auto mb-4" style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(255, 140, 66, 0.3);">
                                <i class="bi bi-shield-fill-check" style="font-size: 3.5rem; color: var(--color-dark);"></i>
                            </div>

                            <!-- Nombre -->
                            <h3 style="color: var(--color-text); margin-bottom: 0.5rem;"><?php echo $nombre; ?></h3>
                            
                            <!-- Badge de Admin -->
                            <div class="mb-3">
                                <span class="badge" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); font-size: 1rem; padding: 0.5rem 1.5rem;">
                                    <i class="bi bi-shield-fill-check me-2"></i>Administrador
                                </span>
                            </div>

                            <p class="text-muted mb-4">
                                <i class="bi bi-envelope-fill me-2" style="color: var(--color-primary);"></i>
                                <?php echo $correo; ?>
                            </p>

                            <!-- Acceso Total -->
                            <div class="alert" style="background: rgba(255, 140, 66, 0.1); border: 1px solid rgba(255, 140, 66, 0.3); border-radius: 10px;">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-key-fill" style="font-size: 2rem; color: var(--color-primary); margin-right: 1rem;"></i>
                                    <div class="text-start">
                                        <h6 style="color: var(--color-text); margin-bottom: 0.25rem;">Acceso Total</h6>
                                        <small class="text-muted">Control completo del sistema</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón cerrar sesión -->
                            <form method="POST" class="mt-4">
                                <button type="submit" name="btnCerrarSesion" class="btn btn-primary-shareflix w-100">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- INFORMACIÓN Y PERMISOS -->
                <div class="col-lg-8">
                    
                    <!-- Información Personal -->
                    <div class="content-card mb-4">
                        <div class="card-body p-4">
                            <h5 style="color: var(--color-text); margin-bottom: 1.5rem;">
                                <i class="bi bi-info-circle-fill me-2" style="color: var(--color-primary);"></i>
                                Información Personal
                            </h5>

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 rounded" style="background: rgba(255, 140, 66, 0.05); border: 1px solid rgba(255, 140, 66, 0.1);">
                                        <label class="fw-semibold mb-2" style="color: var(--color-primary);">
                                            <i class="bi bi-person-fill me-2"></i>Nombre Completo
                                        </label>
                                        <p style="color: var(--color-text); margin-bottom: 0;"><?php echo $nombre; ?></p>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 rounded" style="background: rgba(255, 140, 66, 0.05); border: 1px solid rgba(255, 140, 66, 0.1);">
                                        <label class="fw-semibold mb-2" style="color: var(--color-primary);">
                                            <i class="bi bi-envelope-fill me-2"></i>Correo Electrónico
                                        </label>
                                        <p style="color: var(--color-text); margin-bottom: 0;"><?php echo $correo; ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: rgba(255, 140, 66, 0.05); border: 1px solid rgba(255, 140, 66, 0.1);">
                                        <label class="fw-semibold mb-2" style="color: var(--color-primary);">
                                            <i class="bi bi-shield-fill-check me-2"></i>Rol
                                        </label>
                                        <p style="color: var(--color-text); margin-bottom: 0;"><?php echo $perfil; ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: rgba(255, 140, 66, 0.05); border: 1px solid rgba(255, 140, 66, 0.1);">
                                        <label class="fw-semibold mb-2" style="color: var(--color-primary);">
                                            <i class="bi bi-star-fill me-2"></i>Suscripción
                                        </label>
                                        <p style="color: var(--color-text); margin-bottom: 0;">
                                            <?php echo $suscripcion; ?>
                                            <span class="badge badge-success ms-2">
                                                <i class="bi bi-check-circle"></i> Activa
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permisos y Privilegios -->
                    <div class="content-card mb-4">
                        <div class="card-body p-4">
                            <h5 style="color: var(--color-text); margin-bottom: 1.5rem;">
                                <i class="bi bi-key-fill me-2" style="color: var(--color-primary);"></i>
                                Permisos y Privilegios
                            </h5>

                            <div class="row g-3">
                                <!-- Gestión de Contenido -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2);">
                                        <div class="d-flex align-items-start">
                                            <div style="width: 40px; height: 40px; background: rgba(34, 197, 94, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                                <i class="bi bi-film" style="color: #22c55e; font-size: 1.25rem;"></i>
                                            </div>
                                            <div>
                                                <h6 style="color: var(--color-text); margin-bottom: 0.25rem;">
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    Gestión de Contenido
                                                </h6>
                                                <small class="text-muted">Crear, editar y eliminar películas</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gestión de Usuarios -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2);">
                                        <div class="d-flex align-items-start">
                                            <div style="width: 40px; height: 40px; background: rgba(34, 197, 94, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                                <i class="bi bi-people-fill" style="color: #22c55e; font-size: 1.25rem;"></i>
                                            </div>
                                            <div>
                                                <h6 style="color: var(--color-text); margin-bottom: 0.25rem;">
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    Gestión de Usuarios
                                                </h6>
                                                <small class="text-muted">Administrar cuentas y suscripciones</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gestión de Géneros -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2);">
                                        <div class="d-flex align-items-start">
                                            <div style="width: 40px; height: 40px; background: rgba(34, 197, 94, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                                <i class="bi bi-collection-fill" style="color: #22c55e; font-size: 1.25rem;"></i>
                                            </div>
                                            <div>
                                                <h6 style="color: var(--color-text); margin-bottom: 0.25rem;">
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    Gestión de Categorías
                                                </h6>
                                                <small class="text-muted">Organizar contenido del catálogo</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dashboard -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2);">
                                        <div class="d-flex align-items-start">
                                            <div style="width: 40px; height: 40px; background: rgba(34, 197, 94, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                                <i class="bi bi-graph-up-arrow" style="color: #22c55e; font-size: 1.25rem;"></i>
                                            </div>
                                            <div>
                                                <h6 style="color: var(--color-text); margin-bottom: 0.25rem;">
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    Acceso a Estadísticas
                                                </h6>
                                                <small class="text-muted">Ver métricas y reportes</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nota informativa -->
                            <div class="alert mt-4" style="background: rgba(255, 140, 66, 0.1); border: 1px solid rgba(255, 140, 66, 0.2); border-radius: 10px;">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle-fill" style="color: var(--color-primary); font-size: 1.5rem; margin-right: 1rem;"></i>
                                    <div>
                                        <h6 style="color: var(--color-text); margin-bottom: 0.5rem;">Acceso Administrativo</h6>
                                        <p class="text-muted mb-0" style="font-size: 0.9rem;">
                                            Como administrador, tienes control total sobre la plataforma Shareflix. 
                                            Puedes gestionar todo el contenido, usuarios y configuraciones del sistema.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="content-card">
                        <div class="card-body p-4">
                            <h5 style="color: var(--color-text); margin-bottom: 1.5rem;">
                                <i class="bi bi-lightning-fill me-2" style="color: var(--color-primary);"></i>
                                Acciones Rápidas
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="Dashboard.php" class="btn btn-outline-light w-100 text-start" style="padding: 1rem;">
                                        <i class="bi bi-speedometer2 me-2" style="color: var(--color-primary);"></i>
                                        Ver Dashboard
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="GestionContenido.php" class="btn btn-outline-light w-100 text-start" style="padding: 1rem;">
                                        <i class="bi bi-plus-circle me-2" style="color: var(--color-primary);"></i>
                                        Agregar Contenido
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="GestionUsuarios.php" class="btn btn-outline-light w-100 text-start" style="padding: 1rem;">
                                        <i class="bi bi-people me-2" style="color: var(--color-primary);"></i>
                                        Gestionar Usuarios
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <form method="POST" style="margin: 0;">
                                        <button type="submit" name="btnCerrarSesion" class="btn btn-action-delete w-100 text-start" style="padding: 1rem;">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            Cerrar Sesión
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