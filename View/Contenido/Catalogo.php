<?php
  // Iniciar sesión solo si no hay una activa
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  if(!isset($_SESSION["ConsecutivoUsuario"]))
  {
      header("Location: ../../Inicio/IniciarSesion.php");
      exit();
  }

  // Incluir archivos necesarios
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutCliente.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/ContenidoController.php';

  $peliculas = ObtenerPeliculasController();
  $generos = ObtenerGenerosController();
  $categorias = ObtenerCategoriasController();
  $favoritos = ObtenerFavoritosUsuarioController($_SESSION["ConsecutivoUsuario"]);
  
  // Crear array con IDs de favoritos para fácil verificación
  $idsFavoritos = array_column($favoritos, 'id_pelicula');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo - Shareflix</title>
    <?php ShowCSS(); ?>
    <style>
        /* Centrar las películas */
        #gridPeliculas {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
        }
        
        .pelicula-item {
            flex: 0 0 auto;
            width: 180px;
        }
        
        .pelicula-imagen {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #1a1a1a;
        }
        
        .pelicula-imagen:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 16px rgba(255, 140, 66, 0.4);
        }
        
        .pelicula-imagen img {
            width: 100%;
            height: 270px;
            object-fit: cover;
            display: block;
        }
        
        .placeholder-poster {
            width: 100%;
            height: 270px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #FF8C42, #FFA94D);
            color: white;
        }
        
        .placeholder-poster i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .placeholder-poster p {
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            padding: 0 10px;
        }
        
        .pelicula-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 50%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding: 15px;
            opacity: 0;
            transition: opacity 0.3s ease;
            gap: 8px;
        }
        
        .pelicula-imagen:hover .pelicula-overlay {
            opacity: 1;
        }
        
        .btn-info-pelicula,
        .btn-favorito,
        .btn-ver-ahora {
            width: 100%;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-ver-ahora {
            background: linear-gradient(135deg, #FF8C42, #FFA94D);
            border: none;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
        }
        
        .btn-ver-ahora:hover {
            background: linear-gradient(135deg, #FFA94D, #FF8C42);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(255, 140, 66, 0.4);
        }
        
        .btn-favorito.active {
            background: #dc3545;
            border-color: #dc3545;
        }
        
        .pelicula-info {
            padding: 10px 5px;
        }
        
        .pelicula-titulo {
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .pelicula-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 5px;
        }
        
        .bg-shareflix {
            background: linear-gradient(135deg, #FF8C42, #FFA94D);
            border: none;
        }
        
        .badge-video {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 140, 66, 0.9);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-shareflix {
            background: linear-gradient(135deg, #FF8C42, #FFA94D);
            border: none;
            color: white;
        }

        .btn-shareflix:hover {
            background: linear-gradient(135deg, #FFA94D, #FF8C42);
            color: white;
        }
    </style>
</head>

<body>
    <?php ShowMenu(); ?>

    <div class="content-wrapper" style="margin-left: 250px; padding: 20px;">
        <div class="container-fluid py-4">
            
            <!-- HEADER CON BÚSQUEDA Y FILTROS -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h2 fw-bold text-white mb-3">
                        <i class="bi bi-film me-2"></i>Catálogo de Películas
                    </h1>
                    
                    <!-- Barra de búsqueda y filtros -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row g-3">
                                
                                <!-- Búsqueda -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="buscarPelicula" 
                                               placeholder="Buscar por título...">
                                    </div>
                                </div>

                                <!-- Filtro por Género -->
                                <div class="col-md-3">
                                    <select class="form-select" id="filtroGenero">
                                        <option value="">Todos los géneros</option>
                                        <?php foreach($generos as $genero): ?>
                                        <option value="<?php echo mb_strtolower($genero['nombre'], 'UTF-8'); ?>">
                                            <?php echo $genero['nombre']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Filtro por Categoría -->
                                <div class="col-md-3">
                                    <select class="form-select" id="filtroCategoria">
                                        <option value="">Todas las categorías</option>
                                        <?php foreach($categorias as $categoria): ?>
                                        <option value="<?php echo mb_strtolower($categoria['nombre'], 'UTF-8'); ?>">
                                            <?php echo $categoria['nombre']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Contador de resultados -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="text-muted mb-0" id="contadorPeliculas">
                            Mostrando <?php echo count($peliculas); ?> películas
                        </p>
                        <a href="MisFavoritos.php" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-heart-fill me-2"></i>Mis Favoritos
                        </a>
                    </div>

                </div>
            </div>

            <!-- GRID DE PELÍCULAS -->
            <div id="gridPeliculas">
                
                <?php foreach($peliculas as $pelicula): ?>
                <div class="pelicula-item" 
                     data-titulo="<?php echo mb_strtolower($pelicula['titulo'], 'UTF-8'); ?>"
                     data-genero="<?php echo mb_strtolower($pelicula['generos'], 'UTF-8'); ?>"
                     data-categoria="<?php echo mb_strtolower($pelicula['categorias'], 'UTF-8'); ?>">
                    
                    <!-- Portada de la película -->
                    <div class="pelicula-imagen">
                        <?php if(!empty($pelicula['imagen_url'])): ?>
                            <img src="<?php echo $pelicula['imagen_url']; ?>" 
                                 alt="<?php echo $pelicula['titulo']; ?>"
                                 class="img-fluid">
                        <?php else: ?>
                            <!-- Placeholder cuando no hay imagen -->
                            <div class="placeholder-poster">
                                <i class="bi bi-film"></i>
                                <p><?php echo $pelicula['titulo']; ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Badge de video disponible -->
                        <?php if(!empty($pelicula['video_archivo'])): ?>
                            <span class="badge-video">
                                <i class="bi bi-play-fill"></i>
                                VIDEO
                            </span>
                        <?php endif; ?>
                        
                        <!-- Overlay al hacer hover -->
                        <div class="pelicula-overlay">
                            <!-- Botón Ver Ahora o Detalles -->
                            <?php if(!empty($pelicula['video_archivo'])): ?>
                                <a href="VerPelicula.php?id=<?php echo $pelicula['id_pelicula']; ?>" 
                                   class="btn btn-sm btn-ver-ahora" 
                                   title="Ver película">
                                    <i class="bi bi-play-fill me-2"></i>Ver Ahora
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-light btn-info-pelicula" 
                                        onclick="verDetalles(<?php echo htmlspecialchars(json_encode($pelicula)); ?>)"
                                        title="Ver detalles">
                                    <i class="bi bi-info-circle me-2"></i>Detalles
                                </button>
                            <?php endif; ?>
                            
                            <!-- Botón de Favoritos -->
                            <?php if(in_array($pelicula['id_pelicula'], $idsFavoritos)): ?>
                                <!-- Ya está en favoritos -->
                                <button class="btn btn-sm btn-danger btn-favorito active" 
                                        data-pelicula-id="<?php echo $pelicula['id_pelicula']; ?>"
                                        onclick="quitarFavorito(<?php echo $pelicula['id_pelicula']; ?>)"
                                        title="Quitar de favoritos">
                                    <i class="bi bi-heart-fill me-2"></i>Quitar
                                </button>
                            <?php else: ?>
                                <!-- No está en favoritos -->
                                <button class="btn btn-sm btn-outline-light btn-favorito" 
                                        data-pelicula-id="<?php echo $pelicula['id_pelicula']; ?>"
                                        onclick="agregarFavorito(<?php echo $pelicula['id_pelicula']; ?>)"
                                        title="Agregar a favoritos">
                                    <i class="bi bi-heart me-2"></i>Favorito
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Info de la película -->
                    <div class="pelicula-info">
                        <h6 class="pelicula-titulo mb-1"><?php echo $pelicula['titulo']; ?></h6>
                        <div class="pelicula-meta">
                            <?php if(!empty($pelicula['generos'])): ?>
                                <span class="badge bg-shareflix"><?php echo explode(', ', $pelicula['generos'])[0]; ?></span>
                            <?php endif; ?>
                            <span class="text-muted small"><?php echo $pelicula['anio']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>

            <!-- Mensaje si no hay resultados -->
            <div class="row" id="noResultados" style="display: none;">
                <div class="col-12 text-center py-5">
                    <i class="bi bi-film display-1 text-muted"></i>
                    <h3 class="text-muted mt-3">No se encontraron películas</h3>
                    <p class="text-muted">Intenta con otros filtros o búsqueda</p>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL PARA VER DETALLES DE PELÍCULA -->
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
                                    <strong>Géneros:</strong> 
                                    <span id="generoDetalles"></span>
                                </p>
                                <p class="mb-1">
                                    <strong>Categorías:</strong> 
                                    <span id="categoriaDetalles"></span>
                                </p>
                                <p class="mb-1">
                                    <strong>Año:</strong> 
                                    <span id="anioDetalles"></span>
                                </p>
                                <p class="mb-1">
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
                    <button type="button" class="btn btn-shareflix" id="btnFavoritoModal">
                        <i class="bi bi-heart me-2"></i>Agregar a Favoritos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php ShowJS(); ?>
    
    <script>
        // ========================================
        // VARIABLES GLOBALES
        // ========================================
        const idUsuario = <?php echo $_SESSION["ConsecutivoUsuario"]; ?>;
        const idRol = <?php echo $_SESSION["ConsecutivoPerfil"]; ?>;
        const limiteGratis = 5;

        console.log('✅ Catálogo cargado');
        console.log('Usuario ID:', idUsuario);
        console.log('Rol ID:', idRol);
        console.log('Límite Gratis:', limiteGratis);

        // ========================================
        // BÚSQUEDA Y FILTROS
        // ========================================
        document.getElementById('buscarPelicula').addEventListener('keyup', filtrarPeliculas);
        document.getElementById('filtroGenero').addEventListener('change', filtrarPeliculas);
        document.getElementById('filtroCategoria').addEventListener('change', filtrarPeliculas);

        function filtrarPeliculas() {
            const busqueda = document.getElementById('buscarPelicula').value.toLowerCase();
            const filtroGenero = document.getElementById('filtroGenero').value.toLowerCase();
            const filtroCategoria = document.getElementById('filtroCategoria').value.toLowerCase();
            
            const peliculas = document.querySelectorAll('.pelicula-item');
            let contador = 0;
            
            peliculas.forEach(pelicula => {
                const titulo = pelicula.getAttribute('data-titulo');
                const genero = pelicula.getAttribute('data-genero');
                const categoria = pelicula.getAttribute('data-categoria');
                
                const cumpleBusqueda = titulo.includes(busqueda);
                const cumpleGenero = !filtroGenero || genero.includes(filtroGenero);
                const cumpleCategoria = !filtroCategoria || categoria.includes(filtroCategoria);
                
                if (cumpleBusqueda && cumpleGenero && cumpleCategoria) {
                    pelicula.style.display = '';
                    contador++;
                } else {
                    pelicula.style.display = 'none';
                }
            });
            
            document.getElementById('contadorPeliculas').textContent = `Mostrando ${contador} películas`;
            document.getElementById('noResultados').style.display = contador === 0 ? 'block' : 'none';
        }

        // ========================================
        // SISTEMA DE FAVORITOS
        // ========================================

        /**
         * AGREGAR A FAVORITOS
         */
        function agregarFavorito(idPelicula) {
            console.log('🎬 Agregando a favoritos:', idPelicula);
            
            // Verificar límite para usuarios gratis
            if (idRol === 2) { // Rol 2 = Gratis
                const favoritosActuales = document.querySelectorAll('.btn-favorito.active').length;
                console.log('Favoritos actuales:', favoritosActuales);
                
                if (favoritosActuales >= limiteGratis) {
                    mostrarAlertaUpgrade();
                    return;
                }
            }
            
            // Crear FormData con los nombres CORRECTOS
            const formData = new FormData();
            formData.append('agregarFavoritoAjax', '1');
            formData.append('idContenido', idPelicula);
            
            console.log('📤 Enviando petición a servidor...');
            
            // Enviar al servidor
            fetch('../../Controller/FavoritoController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('📥 Respuesta recibida, status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('📄 Respuesta RAW:', text);
                
                try {
                    const data = JSON.parse(text);
                    console.log('✅ JSON parseado:', data);
                    
                    if (data.success) {
                        // Cambiar el botón visualmente
                        actualizarBotonFavorito(idPelicula, true);
                        mostrarNotificacion('¡Agregado a favoritos! ', 'success');
                    } else {
                        if (data.limite) {
                            mostrarAlertaUpgrade();
                        } else {
                            mostrarNotificacion(data.mensaje || 'Error al agregar a favoritos', 'error');
                        }
                    }
                } catch (e) {
                    console.error('❌ Error al parsear JSON:', e);
                    console.error('Texto recibido:', text);
                    mostrarNotificacion('Error en la respuesta del servidor', 'error');
                }
            })
            .catch(error => {
                console.error('❌ Error en la petición:', error);
                mostrarNotificacion('Error de conexión', 'error');
            });
        }

        /**
         * QUITAR DE FAVORITOS
         */
        function quitarFavorito(idPelicula) {
            console.log(' Quitando de favoritos:', idPelicula);
            
            if (confirm('¿Quieres quitar esta película de tus favoritos?')) {
                // Crear FormData
                const formData = new FormData();
                formData.append('eliminarFavoritoAjax', '1');
                formData.append('idContenido', idPelicula);
                
                console.log('📤 Enviando petición a servidor...');
                
                // Enviar al servidor
                fetch('../../Controller/FavoritoController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('📥 Respuesta recibida, status:', response.status);
                    return response.text();
                })
                .then(text => {
                    console.log('📄 Respuesta RAW:', text);
                    
                    try {
                        const data = JSON.parse(text);
                        console.log('✅ JSON parseado:', data);
                        
                        if (data.success) {
                            actualizarBotonFavorito(idPelicula, false);
                            mostrarNotificacion('Quitado de favoritos', 'info');
                        } else {
                            mostrarNotificacion(data.mensaje || 'Error al quitar de favoritos', 'error');
                        }
                    } catch (e) {
                        console.error('❌ Error al parsear JSON:', e);
                        mostrarNotificacion('Error en la respuesta del servidor', 'error');
                    }
                })
                .catch(error => {
                    console.error('❌ Error en la petición:', error);
                    mostrarNotificacion('Error de conexión', 'error');
                });
            }
        }

        /**
         * ACTUALIZAR BOTÓN FAVORITO
         */
        function actualizarBotonFavorito(idPelicula, esAgregar) {
            console.log('🔄 Actualizando botón:', idPelicula, esAgregar ? 'AGREGAR' : 'QUITAR');
            
            // Buscar el botón por el data-attribute
            const botones = document.querySelectorAll(`[data-pelicula-id="${idPelicula}"]`);
            
            botones.forEach(btn => {
                if (esAgregar) {
                    // Cambiar a estado "EN FAVORITOS"
                    btn.classList.remove('btn-outline-light');
                    btn.classList.add('btn-danger', 'active');
                    btn.innerHTML = '<i class="bi bi-heart-fill me-2"></i>Quitar';
                    btn.setAttribute('onclick', `quitarFavorito(${idPelicula})`);
                    btn.setAttribute('title', 'Quitar de favoritos');
                } else {
                    // Cambiar a estado "NO EN FAVORITOS"
                    btn.classList.remove('btn-danger', 'active');
                    btn.classList.add('btn-outline-light');
                    btn.innerHTML = '<i class="bi bi-heart me-2"></i>Favorito';
                    btn.setAttribute('onclick', `agregarFavorito(${idPelicula})`);
                    btn.setAttribute('title', 'Agregar a favoritos');
                }
            });
        }

        /**
         * ALERTA DE UPGRADE
         */
        function mostrarAlertaUpgrade() {
            alert('¡Límite Alcanzado!\n\n' +
                  `Has alcanzado el límite de ${limiteGratis} películas favoritas.\n\n` +
                  '¡Actualiza a Premium para agregar favoritos ilimitados! 💎');
        }

        // ========================================
        // VER DETALLES
        // ========================================
        let peliculaActualModal = null;

        function verDetalles(pelicula) {
            console.log('👀 Mostrando detalles:', pelicula);
            peliculaActualModal = pelicula;
            
            // Llenar datos en el modal
            document.getElementById('tituloDetalles').textContent = pelicula.titulo;
            document.getElementById('imagenDetalles').src = pelicula.imagen_url || '';
            document.getElementById('generoDetalles').textContent = pelicula.generos || 'Sin género';
            document.getElementById('categoriaDetalles').textContent = pelicula.categorias || 'Sin categoría';
            document.getElementById('anioDetalles').textContent = pelicula.anio;
            document.getElementById('duracionDetalles').textContent = pelicula.duracion;
            document.getElementById('descripcionDetalles').textContent = pelicula.descripcion || 'Sin descripción disponible';
            
            // Configurar botón de favorito en el modal
            const btnFavorito = document.getElementById('btnFavoritoModal');
            const yaFavorito = document.querySelector(`.btn-favorito.active[data-pelicula-id="${pelicula.id_pelicula}"]`);
            
            if (yaFavorito) {
                btnFavorito.innerHTML = '<i class="bi bi-heart-fill me-2"></i>Quitar de Favoritos';
                btnFavorito.className = 'btn btn-danger';
            } else {
                btnFavorito.innerHTML = '<i class="bi bi-heart me-2"></i>Agregar a Favoritos';
                btnFavorito.className = 'btn btn-shareflix';
            }
            
            // Abrir modal
            const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
            modal.show();
        }

        // Event listener para el botón de favorito en el modal
        document.getElementById('btnFavoritoModal').addEventListener('click', function() {
            if (!peliculaActualModal) return;
            
            const yaFavorito = document.querySelector(`.btn-favorito.active[data-pelicula-id="${peliculaActualModal.id_pelicula}"]`);
            
            if (yaFavorito) {
                quitarFavorito(peliculaActualModal.id_pelicula);
            } else {
                agregarFavorito(peliculaActualModal.id_pelicula);
            }
            
            // Cerrar modal
            const modalElement = document.getElementById('modalDetalles');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        });

        // ========================================
        // NOTIFICACIONES
        // ========================================
        function mostrarNotificacion(mensaje, tipo) {
            let claseAlerta = 'alert-info';
            let icono = 'bi-info-circle';
            
            if (tipo === 'success') {
                claseAlerta = 'alert-success';
                icono = 'bi-check-circle-fill';
            } else if (tipo === 'error') {
                claseAlerta = 'alert-danger';
                icono = 'bi-exclamation-circle-fill';
            }
            
            const notificacion = document.createElement('div');
            notificacion.className = `alert ${claseAlerta} alert-dismissible fade show position-fixed`;
            notificacion.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 280px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);';
            notificacion.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi ${icono} me-2" style="font-size: 1.2rem;"></i>
                    <div class="flex-grow-1">${mensaje}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            document.body.appendChild(notificacion);
            
            setTimeout(() => {
                notificacion.classList.remove('show');
                setTimeout(() => notificacion.remove(), 150);
            }, 3000);
        }
    </script>

</body>
</html>