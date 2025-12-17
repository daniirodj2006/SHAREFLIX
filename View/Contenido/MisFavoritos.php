<?php
// INICIAR SESIÓN PRIMERO
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION["ConsecutivoUsuario"])) {
    header("Location: ../Inicio/IniciarSesion.php");
    exit();
}

// Incluir archivos necesarios
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutCliente.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/FavoritoController.php';

// Obtener favoritos del usuario
$favoritos = ObtenerFavoritosUsuarioController($_SESSION["ConsecutivoUsuario"]);

// Obtener información del usuario
$tipoSuscripcion = $_SESSION["TipoSuscripcion"] ?? "Gratis";
$nombreUsuario = $_SESSION["Nombre"];

// Contar favoritos
$totalFavoritos = count($favoritos);
$esPremium = ($tipoSuscripcion == "Premium");
$limiteMaximo = $esPremium ? 'Ilimitado' : '5';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos - Shareflix</title>
    <?php ShowCSS(); ?>
    <style>
        /* MISMO ESTILO DEL CATÁLOGO */
        #gridFavoritos {
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
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.3) 50%, transparent 100%);
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

        .btn-favorito {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-favorito:hover {
            background: #c82333;
            border-color: #bd2130;
            transform: scale(1.05);
            color: white;
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

        .badge-favorito {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(220, 53, 69, 0.9);
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

            font-size: 22px;
            font-weight: 600;
            padding: 22px 50px;
            border-radius: 28px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;

            box-shadow: 0 10px 25px rgba(255, 140, 66, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-shareflix:hover {
            background: linear-gradient(135deg, #FFA94D, #FF8C42);
            color: white;

            transform: scale(1.06);
            box-shadow: 0 15px 35px rgba(255, 140, 66, 0.6);
        }

        /* Estadísticas */
        .stats-card {
            background: var(--color-dark-light);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 140, 66, 0.1);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(255, 140, 66, 0.2);
            border-color: rgba(255, 140, 66, 0.3);
        }

        .stats-card-primary {
            background: linear-gradient(135deg, #FF8C42, #FFA94D);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 5rem;
            color: #FF8C42;
            margin-bottom: 20px;
        }

        .text-shareflix {
            color: #FF8C42;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: 0;
                transform: scale(0.8);
            }
        }

        .role-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-premium {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #000;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
        }

        .role-free {
            background: rgba(108, 117, 125, 0.2);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }
    </style>
</head>

<body>
    <?php ShowMenu(); ?>

    <div class="content-wrapper" style="margin-left: 250px; padding: 20px;">
        <div class="container-fluid py-4">

            <!-- HEADER -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h2 fw-bold text-white mb-0">
                            <i class="bi bi-heart-fill me-2 text-danger"></i>Mis Favoritos
                        </h1>
                        <a href="Catalogo.php" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left me-2"></i>Volver al Catálogo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stats-card stats-card-primary">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1 opacity-75 small">Total de Favoritos</p>
                                <h2 class="fw-bold mb-0"><?php echo $totalFavoritos; ?></h2>
                            </div>
                            <i class="bi bi-heart-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div>
                            <p class="text-muted mb-1 small">Límite Disponible</p>
                            <h2 class="fw-bold mb-0 text-shareflix">
                                <?php
                                if ($limiteMaximo == 'Ilimitado') {
                                    echo '<i class="bi bi-infinity"></i> Ilimitado';
                                } else {
                                    echo $totalFavoritos . ' / ' . $limiteMaximo;
                                }
                                ?>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle me-2 text-shareflix" style="font-size: 2rem;"></i>
                            <div>
                                <div class="fw-bold" style="color: var(--color-text);"><?php echo $nombreUsuario; ?></div>
                                <?php if ($esPremium): ?>
                                    <span class="role-badge role-premium">
                                        <i class="bi bi-star-fill"></i> Premium
                                    </span>
                                <?php else: ?>
                                    <span class="role-badge role-free">
                                        Gratis
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de búsqueda -->
            <?php if ($totalFavoritos > 0): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="buscarFavorito"
                                        placeholder="Buscar en mis favoritos...">
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-outline-danger" onclick="eliminarTodosFavoritos()">
                                    <i class="bi bi-trash3 me-2"></i>Eliminar Todos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contador -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0" id="contadorFavoritos">
                        Mostrando <?php echo $totalFavoritos; ?> películas favoritas
                    </p>
                </div>
            <?php endif; ?>

            <!-- GRID DE FAVORITOS - MISMO ESTILO DEL CATÁLOGO -->
            <?php if ($totalFavoritos > 0): ?>
                <div id="gridFavoritos">

                    <?php foreach ($favoritos as $pelicula): ?>
                        <div class="pelicula-item"
                            data-titulo="<?php echo mb_strtolower($pelicula['titulo'], 'UTF-8'); ?>"
                            data-id="<?php echo $pelicula['id_pelicula']; ?>">

                            <!-- Portada de la película -->
                            <div class="pelicula-imagen">
                                <?php if (!empty($pelicula['imagen_url'])): ?>
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

                                <!-- Badge de FAVORITO -->
                                <span class="badge-favorito">
                                    <i class="bi bi-heart-fill"></i>
                                    FAVORITO
                                </span>

                                <!-- Badge de video disponible -->
                                <?php if (!empty($pelicula['video_archivo'])): ?>
                                    <span class="badge-video">
                                        <i class="bi bi-play-fill"></i>
                                        VIDEO
                                    </span>
                                <?php endif; ?>

                                <!-- Overlay al hacer hover -->
                                <div class="pelicula-overlay">
                                    <!-- Botón Ver Ahora o Detalles -->
                                    <?php if (!empty($pelicula['video_archivo'])): ?>
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

                                    <!-- Botón Quitar de Favoritos -->
                                    <button class="btn btn-sm btn-favorito"
                                        data-pelicula-id="<?php echo $pelicula['id_pelicula']; ?>"
                                        onclick="quitarFavorito(<?php echo $pelicula['id_pelicula']; ?>, '<?php echo addslashes($pelicula['titulo']); ?>')"
                                        title="Quitar de favoritos">
                                        <i class="bi bi-heart-slash-fill me-2"></i>Quitar
                                    </button>
                                </div>
                            </div>

                            <!-- Info de la película -->
                            <div class="pelicula-info">
                                <h6 class="pelicula-titulo mb-1"><?php echo $pelicula['titulo']; ?></h6>
                                <div class="pelicula-meta">
                                    <?php
                                    $genero = 'General';
                                    if (!empty($pelicula['generos'])) {
                                        $generosArray = explode(', ', $pelicula['generos']);
                                        $genero = $generosArray[0];
                                    } elseif (!empty($pelicula['genero'])) {
                                        $genero = $pelicula['genero'];
                                    }
                                    ?>
                                    <span class="badge bg-shareflix"><?php echo $genero; ?></span>
                                    <span class="text-muted small"><?php echo $pelicula['anio']; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <!-- Mensaje si no hay resultados -->
                <div class="row" id="noResultados" style="display: none;">
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search display-1 text-muted"></i>
                        <h3 class="text-muted mt-3">No se encontraron favoritos</h3>
                        <p class="text-muted">Intenta con otro término de búsqueda</p>
                    </div>
                </div>

            <?php else: ?>
                <!-- Estado vacío cuando no hay favoritos -->
                <div class="empty-state">
                    <i class="bi bi-heart"></i>
                    <h3 class="text-white mb-3">Aún no tienes favoritos</h3>
                    <p class="text-muted mb-4">
                        Explora el catálogo y agrega tus películas favoritas para verlas aquí.
                    </p>
                    <a href="Catalogo.php" class="btn btn-shareflix btn-lg">
                        <i class="bi bi-film me-2"></i>Explorar Catálogo
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- MODAL PARA VER DETALLES -->
    <div class="modal fade" id="modalDetalles" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="tituloDetalles">Detalles</h5>
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
                                <p class="mb-1"><strong>Duración:</strong> <span id="duracionDetalles"></span> min</p>
                                <p class="mb-1"><strong>Año:</strong> <span id="anioDetalles"></span></p>
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
        console.log(' Mis Favoritos cargado');

        <?php if ($totalFavoritos > 0): ?>
            document.getElementById('buscarFavorito').addEventListener('keyup', function() {
                const busqueda = this.value.toLowerCase();
                const peliculas = document.querySelectorAll('.pelicula-item');
                let contador = 0;

                peliculas.forEach(pelicula => {
                    const titulo = pelicula.getAttribute('data-titulo');
                    if (titulo.includes(busqueda)) {
                        pelicula.style.display = '';
                        contador++;
                    } else {
                        pelicula.style.display = 'none';
                    }
                });

                document.getElementById('contadorFavoritos').textContent = `Mostrando ${contador} películas favoritas`;
                document.getElementById('noResultados').style.display = contador === 0 ? 'block' : 'none';
            });
        <?php endif; ?>

        function quitarFavorito(idPelicula, titulo) {
            if (confirm(`¿Quitar "${titulo}" de favoritos?`)) {
                const formData = new FormData();
                formData.append('eliminarFavoritoAjax', '1');
                formData.append('idContenido', idPelicula);

                fetch('../../Controller/FavoritoController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(text => {
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                const item = document.querySelector(`[data-id="${idPelicula}"]`);
                                if (item) {
                                    item.style.animation = 'fadeOut 0.3s ease';
                                    setTimeout(() => {
                                        item.remove();
                                        const restantes = document.querySelectorAll('.pelicula-item').length;
                                        document.getElementById('contadorFavoritos').textContent = `Mostrando ${restantes} películas favoritas`;
                                        if (restantes === 0) location.reload();
                                    }, 300);
                                }
                                mostrarNotificacion('Quitado de favoritos 💔', 'info');
                            }
                        } catch (e) {
                            console.error('Error:', e);
                        }
                    });
            }
        }

        function eliminarTodosFavoritos() {
            if (confirm('¿Eliminar TODOS los favoritos?\n\nEsta acción no se puede deshacer.')) {
                const formData = new FormData();
                formData.append('eliminarTodosFavoritosAjax', '1');

                fetch('../../Controller/FavoritoController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            mostrarNotificacion('Todos eliminados', 'success');
                            setTimeout(() => location.reload(), 1000);
                        }
                    });
            }
        }

        let peliculaActualModal = null;

        function verDetalles(pelicula) {
            peliculaActualModal = pelicula;
            document.getElementById('tituloDetalles').textContent = pelicula.titulo;
            document.getElementById('imagenDetalles').src = pelicula.imagen_url || '';
            document.getElementById('duracionDetalles').textContent = pelicula.duracion || 'N/A';
            document.getElementById('anioDetalles').textContent = pelicula.anio || 'N/A';
            document.getElementById('descripcionDetalles').textContent = pelicula.descripcion || 'Sin descripción';
            new bootstrap.Modal(document.getElementById('modalDetalles')).show();
        }

        document.getElementById('btnQuitarModal').addEventListener('click', function() {
            if (peliculaActualModal) {
                quitarFavorito(peliculaActualModal.id_pelicula, peliculaActualModal.titulo);
                bootstrap.Modal.getInstance(document.getElementById('modalDetalles')).hide();
            }
        });

        function mostrarNotificacion(mensaje, tipo) {
            const claseAlerta = tipo === 'success' ? 'alert-success' : 'alert-info';
            const icono = tipo === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle';

            const notif = document.createElement('div');
            notif.className = `alert ${claseAlerta} alert-dismissible fade show position-fixed`;
            notif.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 280px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);';
            notif.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi ${icono} me-2" style="font-size: 1.2rem;"></i>
                    <div class="flex-grow-1">${mensaje}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.body.appendChild(notif);
            setTimeout(() => {
                notif.classList.remove('show');
                setTimeout(() => notif.remove(), 150);
            }, 3000);
        }
    </script>

</body>

</html>