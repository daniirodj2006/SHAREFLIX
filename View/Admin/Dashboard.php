<?php
  // Iniciar sesión solo si no hay una activa
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // Verificar que el usuario sea administrador
  if(!isset($_SESSION["ConsecutivoPerfil"]) || $_SESSION["ConsecutivoPerfil"] != 1) {
      header("Location: ../Inicio/IniciarSesion.php");
      exit();
  }

  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/AdminController.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutAdmin.php';
  
  // Obtener estadísticas del dashboard
  $estadisticas = ObtenerEstadisticasDashboardController();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Shareflix</title>
    <?php ShowCSS(); ?>
</head>

<body>
    <?php ShowMenu(); ?>

       <div class="content-wrapper" style="margin-left: 250px; padding: 20px;">
        <div class="container-fluid py-4">
            
            <!-- HEADER DEL DASHBOARD -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="dashboard-title">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </h1>
                    <p class="text-muted mb-0">
                        Bienvenido/a, <span class="text-shareflix"><?php echo $_SESSION["Nombre"]; ?></span> 
                        <span class="badge bg-shareflix ms-2">
                            <i class="bi bi-calendar3 me-1"></i><?php echo date('d/m/Y'); ?>
                        </span>
                    </p>
                </div>
            </div>

            <?php MostrarMensaje(); ?>

            <!-- TARJETAS DE ESTADÍSTICAS PRINCIPALES -->
            <div class="row g-4 mb-5">
                
                <!-- TOTAL PELÍCULAS -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2 small">Total Películas</p>
                                <h2 class="fw-bold mb-0" style="color: var(--color-primary);">
                                    <?php echo $estadisticas["totalPeliculas"]; ?>
                                </h2>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up"></i> 
                                    <?php echo $estadisticas["peliculasNuevasMes"]; ?> este mes
                                </small>
                            </div>
                            <div class="stats-icon bg-shareflix-light">
                                <i class="bi bi-film text-shareflix"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOTAL USUARIOS -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2 small">Total Usuarios</p>
                                <h2 class="fw-bold mb-0 text-info">
                                    <?php echo $estadisticas["totalUsuarios"]; ?>
                                </h2>
                                <small class="text-muted">
                                    <i class="bi bi-star-fill text-warning"></i> 
                                    <?php echo $estadisticas["usuariosPremium"]; ?> premium
                                </small>
                            </div>
                            <div class="stats-icon bg-info-light">
                                <i class="bi bi-people-fill text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- USUARIOS ACTIVOS -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2 small">Activos Hoy</p>
                                <h2 class="fw-bold mb-0 text-success">
                                    <?php echo $estadisticas["usuariosActivosHoy"]; ?>
                                </h2>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up"></i> 
                                    +<?php echo $estadisticas["porcentajeCrecimiento"]; ?>% vs ayer
                                </small>
                            </div>
                            <div class="stats-icon bg-success-light">
                                <i class="bi bi-person-check-fill text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOTAL GÉNEROS -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2 small">Géneros</p>
                                <h2 class="fw-bold mb-0 text-warning">
                                    <?php echo $estadisticas["totalGeneros"]; ?>
                                </h2>
                                <small class="text-muted">
                                    <i class="bi bi-tags-fill"></i> 
                                    <?php echo $estadisticas["totalCategorias"]; ?> categorías
                                </small>
                            </div>
                            <div class="stats-icon bg-warning-light">
                                <i class="bi bi-collection-fill text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row g-4 mb-5">
                
                <!-- PELÍCULAS MÁS POPULARES -->
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm h-100" style="background: var(--color-dark-light); border-radius: 15px;">
                        <div class="card-header bg-transparent border-bottom" style="border-color: rgba(255, 140, 66, 0.2) !important; padding: 1.5rem;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold" style="color: var(--color-text);">
                                    <i class="bi bi-star-fill text-warning me-2"></i>Películas Más Populares
                                </h5>
                                <a href="GestionContenido.php" class="text-shareflix text-decoration-none small">
                                    Ver todas <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 1.5rem;">
                            <?php if(count($estadisticas["peliculasPopulares"]) > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach($estadisticas["peliculasPopulares"] as $index => $pelicula): ?>
                                    <div class="list-group-item border-0 px-0 py-3" style="background: transparent;">
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-shareflix me-3 fs-6 px-3 py-2"><?php echo ($index + 1); ?></div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold" style="color: var(--color-text);">
                                                    <?php echo $pelicula["titulo"]; ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-heart-fill text-danger"></i> 
                                                    <?php echo $pelicula["favoritos"]; ?> favoritos
                                                </small>
                                            </div>
                                            <span class="badge" style="background: rgba(255, 140, 66, 0.2); color: var(--color-primary);">
                                                <?php echo $pelicula["genero"]; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-film" style="font-size: 3rem; color: var(--color-primary); opacity: 0.3;"></i>
                                    <p class="text-muted mt-3">No hay películas aún</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- USUARIOS RECIENTES -->
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm h-100" style="background: var(--color-dark-light); border-radius: 15px;">
                        <div class="card-header bg-transparent border-bottom" style="border-color: rgba(255, 140, 66, 0.2) !important; padding: 1.5rem;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold" style="color: var(--color-text);">
                                    <i class="bi bi-person-plus-fill text-info me-2"></i>Usuarios Recientes
                                </h5>
                                <a href="GestionUsuarios.php" class="text-shareflix text-decoration-none small">
                                    Ver todos <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 1.5rem;">
                            <?php if(count($estadisticas["usuariosRecientes"]) > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach($estadisticas["usuariosRecientes"] as $usuario): ?>
                                    <div class="list-group-item border-0 px-0 py-3" style="background: transparent;">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-shareflix-light me-3">
                                                <i class="bi bi-person-fill text-shareflix"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold" style="color: var(--color-text);">
                                                    <?php echo $usuario["nombre"]; ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope"></i> 
                                                    <?php echo $usuario["email"]; ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge <?php echo $usuario['ConsecutivoPerfil'] == 3 ? 'badge-free' : 'bg-shareflix'; ?>">
                                                    <?php echo $usuario["rol"]; ?>
                                                </span>
                                                <br>
                                                <small class="text-muted"><?php echo $usuario["fechaRegistro"]; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-people" style="font-size: 3rem; color: var(--color-primary); opacity: 0.3;"></i>
                                    <p class="text-muted mt-3">No hay usuarios registrados</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ACCESOS RÁPIDOS -->
            <div class="row g-4">
                <div class="col-12">
                    <h5 class="fw-bold mb-4" style="color: var(--color-text);">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>Accesos Rápidos
                    </h5>
                </div>
                
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="GestionContenido.php" class="quick-action-card">
                        <div class="quick-action-icon bg-shareflix-light mb-3">
                            <i class="bi bi-plus-circle-fill text-shareflix"></i>
                        </div>
                        <h6 class="mb-0">Agregar Película</h6>
                    </a>
                </div>


                <div class="col-12 col-sm-6 col-md-3">
                    <a href="GestionUsuarios.php" class="quick-action-card">
                        <div class="quick-action-icon bg-warning-light mb-3">
                            <i class="bi bi-people-fill text-warning"></i>
                        </div>
                        <h6 class="mb-0">Ver Usuarios</h6>
                    </a>
                </div>


            </div>

        </div>
    </div>

    <?php ShowJS(); ?>

</body>
</html>