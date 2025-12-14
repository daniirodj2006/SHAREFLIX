<?php
  // Iniciar sesión solo si no hay una activa
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
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/FavoritoController.php';

  // Obtener favoritos del usuario
  $favoritos = ObtenerFavoritosUsuarioController($_SESSION["ConsecutivoUsuario"]);
  
  // Obtener información del usuario
  $ConsecutivoPerfil = $_SESSION["ConsecutivoPerfil"];
  $nombreUsuario = $_SESSION["Nombre"];
  
  // Contar favoritos
  $totalFavoritos = count($favoritos);
  $limiteGratis = 5;
  $esGratis = ($ConsecutivoPerfil == 2);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos - Shareflix</title>
    <?php ShowCSS(); ?>
</head>

<body>
    <?php ShowMenu(); ?>

   <div class="content-wrapper" style="margin-left: 250px; padding: 20px;">
        <div class="container-fluid py-4">
            
            <!-- HEADER -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h2 fw-bold text-white mb-1">
                        <i class="bi bi-heart-fill text-danger me-2"></i>Mis Favoritos
                    </h1>
                    <p class="text-muted mb-0">Tus películas guardadas</p>
                </div>
            </div>

            <!-- INFO DEL USUARIO -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-shareflix-light me-3">
                                            <i class="bi bi-person-fill text-shareflix"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1"><?php echo $nombreUsuario; ?></h5>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <a href="Catalogo.php" class="btn btn-outline-light">
                                        <i class="bi bi-arrow-left me-2"></i>Volver al Catálogo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

            <?php if(count($favoritos) > 0): ?>
                <!-- GRID DE FAVORITOS -->
                <div class="row g-4">
                    
                    <?php foreach($favoritos as $pelicula): ?>
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                        
                        <div class="pelicula-card">
                            <!-- Portada de la película -->
                            <div class="pelicula-imagen">
                                <img src="<?php echo $pelicula['imagen_url']; ?>" 
                                     alt="<?php echo $pelicula['titulo']; ?>"
                                     class="img-fluid">
                                
                                <!-- Overlay al hacer hover -->
                                <div class="pelicula-overlay">
                                    <button class="btn btn-sm btn-light btn-info-pelicula" 
                                            onclick="verDetalles(<?php echo htmlspecialchars(json_encode($pelicula)); ?>)"
                                            title="Ver detalles">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    
                                    <button class="btn btn-sm btn-danger btn-favorito" 
                                            onclick="quitarFavorito(<?php echo $pelicula['id_pelicula']; ?>, '<?php echo $pelicula['titulo']; ?>')"
                                            title="Quitar de favoritos">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Info de la película -->
                            <div class="pelicula-info">
                                <h6 class="pelicula-titulo mb-1"><?php echo $pelicula['titulo']; ?></h6>
                                <div class="pelicula-meta">
                                    <span class="badge bg-shareflix me-1"><?php echo $pelicula['genero'] ?? 'Sin género'; ?></span>
                                    <span class="text-muted small"><?php echo $pelicula['anio']; ?></span>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Agregado: <?php echo date('d/m/Y', strtotime($pelicula['fecha_agregado'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <?php endforeach; ?>

                </div>

            <?php else: ?>
                <!-- MENSAJE CUANDO NO HAY FAVORITOS -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-heart display-1 text-muted mb-3"></i>
                                <h3 class="text-white mb-2">No tienes favoritos aún</h3>
                                <p class="text-muted mb-4">
                                    Explora el catálogo y agrega tus películas favoritas
                                </p>
                                <a href="Catalogo.php" class="btn btn-shareflix btn-lg">
                                    <i class="bi bi-film me-2"></i>Explorar Catálogo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAL PARA VER DETALLES DE PELÍCULA -->
    <!-- ============================================ -->
    <div class="modal fade" id="modalDetalles" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="tituloDetalles">Título de la Película</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="imagenDetalles" src="" alt="Portada" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6 class="text-shareflix mb-2">Información</h6>
                                <p class="mb-1">
                                    <strong>Género:</strong> 
                                    <span class="badge bg-shareflix" id="generoDetalles"></span>
                                </p>
                                <p class="mb-1">
                                    <strong>Categoría:</strong> 
                                    <span class="badge bg-info" id="categoriaDetalles"></span>
                                </p>
                                <p class="mb-1">
                                    <strong>Año:</strong> 
                                    <span id="anioDetalles"></span>
                                </p>
                                <p class="mb-0">
                                    <strong>Duración:</strong> 
                                    <span id="duracionDetalles"></span> minutos
                                </p>
                            </div>
                            
                            <div>
                                <h6 class="text-shareflix mb-2">Sinopsis</h6>
                                <p id="descripcionDetalles" class="text-muted"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" id="btnQuitarModal">
                        <i class="bi bi-trash me-2"></i>Quitar de Favoritos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php ShowJS(); ?>
    <script>
        const ConsecutivoUsuario = <?php echo $_SESSION["ConsecutivoUsuario"]; ?>;
    </script>
    <script src="../js/MisFavoritos.js"></script>

</body>
</html>