<?php
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  if(!isset($_SESSION["ConsecutivoUsuario"])) {
      header("Location: ../../Inicio/IniciarSesion.php");
      exit();
  }

  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutCliente.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/ContenidoController.php';

  // ==================== DEBUG MODE ====================
  $debug = false; // Cambia a false cuando funcione
  
  if($debug) {
      echo "<div style='background: #1a1a1a; color: #fff; padding: 20px; font-family: monospace;'>";
      echo "<h2 style='color: #FF8C42;'>🔍 DEBUG MODE - VerPelicula.php</h2>";
  }

  // Obtener ID de la película
  $id_pelicula = isset($_GET['id']) ? intval($_GET['id']) : 0;
  
  if($debug) {
      echo "<h3 style='color: #FFA94D;'>1️⃣ ID recibido:</h3>";
      echo "<p>ID desde URL: <strong style='color: #4CAF50;'>" . $id_pelicula . "</strong></p>";
      
      if($id_pelicula == 0) {
          echo "<p style='color: #f44336;'>❌ ERROR: ID es 0 o no válido</p>";
      } else {
          echo "<p style='color: #4CAF50;'>✅ ID válido</p>";
      }
  }
  
  if($id_pelicula == 0 && !$debug) {
      header("Location: Catalogo.php");
      exit();
  }

  // Obtener datos de la película
  if($debug) {
      echo "<h3 style='color: #FFA94D;'>2️⃣ Llamando a ObtenerPeliculaPorIdController($id_pelicula)...</h3>";
  }
  
  $pelicula = ObtenerPeliculaPorIdController($id_pelicula);
  
  if($debug) {
      if($pelicula) {
          echo "<p style='color: #4CAF50;'>✅ Película encontrada</p>";
          echo "<h4>Datos recibidos:</h4>";
          echo "<pre style='background: #2d2d2d; padding: 15px; border-radius: 8px; overflow-x: auto;'>";
          print_r($pelicula);
          echo "</pre>";
      } else {
          echo "<p style='color: #f44336;'>❌ ERROR: No se encontró la película</p>";
          echo "<p>La función ObtenerPeliculaPorIdController() devolvió NULL o FALSE</p>";
      }
  }
  
  if(!$pelicula && !$debug) {
      header("Location: Catalogo.php");
      exit();
  }

  // Construir ruta del video
  $rutaVideo = '';
  $archivoExiste = false;
  
  if($debug) {
      echo "<h3 style='color: #FFA94D;'>3️⃣ Verificación del video:</h3>";
  }
  
  if(!empty($pelicula['VideoArchivo'])) {
      $rutaVideo = '../videos/' . $pelicula['VideoArchivo'];
      $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/videos/' . $pelicula['VideoArchivo'];
      $archivoExiste = file_exists($rutaCompleta);
      
      if($debug) {
          echo "<p><strong>Campo VideoArchivo:</strong> <span style='color: #4CAF50;'>" . $pelicula['VideoArchivo'] . "</span></p>";
          echo "<p><strong>Ruta relativa (para HTML):</strong> <code style='background: #2d2d2d; padding: 2px 8px; border-radius: 4px;'>" . $rutaVideo . "</code></p>";
          echo "<p><strong>Ruta absoluta (servidor):</strong> <code style='background: #2d2d2d; padding: 2px 8px; border-radius: 4px;'>" . $rutaCompleta . "</code></p>";
          
          if($archivoExiste) {
              $tamanioMB = round(filesize($rutaCompleta) / 1024 / 1024, 2);
              echo "<p style='color: #4CAF50;'>✅ El archivo existe (" . $tamanioMB . " MB)</p>";
          } else {
              echo "<p style='color: #f44336;'>❌ ERROR: El archivo NO existe en el servidor</p>";
              echo "<p>Verifica que el archivo fue subido correctamente a la carpeta /Shareflix/View/videos/</p>";
          }
      }
  } else {
      if($debug) {
          echo "<p style='color: #f44336;'>❌ Campo VideoArchivo está vacío o NULL</p>";
          echo "<p>Esta película no tiene un video asociado en la base de datos.</p>";
      }
  }

  if($debug) {
      echo "<h3 style='color: #FFA94D;'>4️⃣ Verificación de la carpeta videos:</h3>";
      $carpetaVideos = $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/videos/';
      
      if(is_dir($carpetaVideos)) {
          echo "<p style='color: #4CAF50;'>✅ La carpeta existe: $carpetaVideos</p>";
          
          // Listar archivos en la carpeta
          $archivos = scandir($carpetaVideos);
          $videosEncontrados = array_filter($archivos, function($archivo) {
              return !in_array($archivo, array('.', '..')) && 
                     preg_match('/\.(mp4|webm|ogg)$/i', $archivo);
          });
          
          if(count($videosEncontrados) > 0) {
              echo "<p>Videos encontrados en la carpeta (" . count($videosEncontrados) . "):</p>";
              echo "<ul style='background: #2d2d2d; padding: 15px; border-radius: 8px;'>";
              foreach($videosEncontrados as $video) {
                  $rutaVideoTemp = $carpetaVideos . $video;
                  $tamanioMB = round(filesize($rutaVideoTemp) / 1024 / 1024, 2);
                  echo "<li>" . $video . " (" . $tamanioMB . " MB)</li>";
              }
              echo "</ul>";
          } else {
              echo "<p style='color: #ff9800;'>⚠️ No hay videos en la carpeta</p>";
          }
      } else {
          echo "<p style='color: #f44336;'>❌ ERROR: La carpeta NO existe</p>";
          echo "<p>Crea la carpeta: <code style='background: #2d2d2d; padding: 2px 8px; border-radius: 4px;'>$carpetaVideos</code></p>";
      }
  }

  if($debug) {
      echo "<h3 style='color: #FFA94D;'>5️⃣ Verificación de la base de datos:</h3>";
      echo "<p>Ejecuta esta consulta en MySQL para ver las películas con video:</p>";
      echo "<pre style='background: #2d2d2d; padding: 15px; border-radius: 8px;'>SELECT ConsecutivoContenido, Titulo, VideoArchivo 
FROM contenido 
WHERE VideoArchivo IS NOT NULL AND VideoArchivo != '';
</pre>";
  }

  if($debug) {
      echo "<h3 style='color: #FFA94D;'>📋 Resumen:</h3>";
      echo "<ul style='background: #2d2d2d; padding: 20px; border-radius: 8px; line-height: 1.8;'>";
      echo "<li>ID de película: " . ($id_pelicula > 0 ? "✅" : "❌") . " $id_pelicula</li>";
      echo "<li>Película encontrada: " . ($pelicula ? "✅" : "❌") . "</li>";
      echo "<li>Campo VideoArchivo: " . (!empty($pelicula['VideoArchivo']) ? "✅ " . $pelicula['VideoArchivo'] : "❌ Vacío") . "</li>";
      echo "<li>Archivo existe: " . ($archivoExiste ? "✅" : "❌") . "</li>";
      echo "</ul>";
      
      echo "<hr style='border-color: #444; margin: 30px 0;'>";
      echo "<p style='color: #ff9800;'>⚠️ <strong>NOTA:</strong> Cuando todo funcione, cambia <code>\$debug = false;</code> en la línea 14</p>";
      echo "</div>";
      
      echo "<hr style='border: 2px solid #FF8C42; margin: 40px 0;'>";
      echo "<h2 style='text-align: center; color: #FF8C42;'>👇 Vista Normal (como se vería sin debug) 👇</h2>";
  }
  
  // ==================== FIN DEBUG ====================
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pelicula['Titulo'] ?? 'Película'); ?> - Shareflix</title>
    <?php ShowCSS(); ?>
    <style>
        body {
            background: #000;
            overflow-x: hidden;
        }
        
        .video-container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            background: #000;
            display: flex;
            flex-direction: column;
        }
        
        .video-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 20px 40px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.8), transparent);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .btn-volver {
            background: linear-gradient(135deg, #FF8C42, #FFA94D);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-volver:hover {
            transform: translateX(-5px);
            color: white;
            box-shadow: 0 4px 12px rgba(255, 140, 66, 0.4);
        }
        
        .video-player {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            padding: 80px 20px 200px;
        }
        
        .video-player video {
            width: 100%;
            max-width: 1400px;
            height: auto;
            max-height: 80vh;
            outline: none;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(255, 140, 66, 0.3);
        }
        
        .video-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 40px;
            background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.7) 50%, transparent 100%);
            color: white;
            z-index: 100;
        }
        
        .video-titulo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }
        
        .video-meta {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .badge-shareflix {
            background: linear-gradient(135deg, #FF8C42, #FFA94D);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .video-descripcion {
            font-size: 1.1rem;
            line-height: 1.8;
            max-width: 900px;
            color: rgba(255,255,255,0.95);
        }
        
        .no-video {
            text-align: center;
            padding: 100px 20px;
            color: white;
        }
        
        .no-video i {
            font-size: 6rem;
            color: #FF8C42;
            margin-bottom: 30px;
        }
        
        .no-video h3 {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .video-controls {
            display: flex;
            gap: 10px;
        }

        .btn-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-control:hover {
            background: rgba(255, 140, 66, 0.3);
            border-color: #FF8C42;
            color: white;
        }
        
        @media (max-width: 768px) {
            .video-titulo {
                font-size: 1.8rem;
            }
            
            .video-header {
                padding: 15px 20px;
            }
            
            .video-info {
                padding: 20px;
            }
            
            .video-player {
                padding: 60px 10px 180px;
            }

            .video-player video {
                max-height: 50vh;
            }
        }
    </style>
</head>

<body>
    <div class="video-container">
        
        <!-- Header con botón volver -->
        <div class="video-header">
            <a href="Catalogo.php" class="btn-volver">
                <i class="bi bi-arrow-left"></i>
                <span>Volver al Catálogo</span>
            </a>
            <div class="video-controls">
                <button class="btn-control" onclick="toggleFullscreen()" title="Pantalla completa">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
                <button class="btn-control" onclick="toggleMute()" title="Silenciar" id="btnMute">
                    <i class="bi bi-volume-up-fill"></i>
                </button>
            </div>
        </div>

        <!-- Reproductor de video -->
        <div class="video-player">
            <?php if(!empty($rutaVideo)): ?>
                <?php 
                // Verificar si el archivo existe
                $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/videos/' . $pelicula['VideoArchivo'];
                if(file_exists($rutaCompleta)): 
                ?>
                
                <!-- Video HTML5 -->
                <video controls autoplay id="videoPlayer">
                    <source src="<?php echo $rutaVideo; ?>" type="video/mp4">
                    <source src="<?php echo $rutaVideo; ?>" type="video/webm">
                    Tu navegador no soporta la reproducción de videos.
                </video>
                
                <?php else: ?>
                    <!-- Archivo de video no encontrado -->
                    <div class="no-video">
                        <i class="bi bi-exclamation-triangle"></i>
                        <h3>Error al cargar el video</h3>
                        <p class="text-muted">El archivo de video no se encuentra en el servidor.</p>
                        <?php if($debug): ?>
                        <p style="color: #ff9800;">Ruta esperada: <?php echo $rutaCompleta; ?></p>
                        <?php endif; ?>
                        <a href="Catalogo.php" class="btn-volver mt-4">
                            <i class="bi bi-arrow-left"></i>
                            <span>Volver al Catálogo</span>
                        </a>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <!-- No hay video disponible -->
                <div class="no-video">
                    <i class="bi bi-camera-video-off"></i>
                    <h3>Video no disponible</h3>
                    <p class="text-muted">Esta película aún no tiene un video cargado.</p>
                    <a href="Catalogo.php" class="btn-volver mt-4">
                        <i class="bi bi-arrow-left"></i>
                        <span>Volver al Catálogo</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Información de la película -->
        <?php if(!empty($rutaVideo) && $archivoExiste): ?>
        <div class="video-info">
            <h1 class="video-titulo"><?php echo htmlspecialchars($pelicula['Titulo']); ?></h1>
            
            <div class="video-meta">
                <span class="badge-shareflix">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?php echo date('Y', strtotime($pelicula['fechaPublicacion'])); ?>
                </span>
                <span class="badge-shareflix">
                    <i class="bi bi-clock me-1"></i>
                    <?php echo $pelicula['Duracion']; ?> min
                </span>
                <?php if(!empty($pelicula['Generos'])): ?>
                    <span class="badge-shareflix">
                        <i class="bi bi-film me-1"></i>
                        <?php echo explode(', ', $pelicula['Generos'])[0]; ?>
                    </span>
                <?php endif; ?>
                <?php if(!empty($pelicula['CalificacionEdad'])): ?>
                    <span class="badge-shareflix">
                        <i class="bi bi-info-circle me-1"></i>
                        <?php echo $pelicula['CalificacionEdad']; ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if(!empty($pelicula['Descripcion'])): ?>
            <p class="video-descripcion"><?php echo htmlspecialchars($pelicula['Descripcion']); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>

    <?php ShowJS(); ?>
    <script>
        const video = document.getElementById('videoPlayer');

        // Función para pantalla completa
        function toggleFullscreen() {
            if (video) {
                if (video.requestFullscreen) {
                    video.requestFullscreen();
                } else if (video.webkitRequestFullscreen) {
                    video.webkitRequestFullscreen();
                } else if (video.msRequestFullscreen) {
                    video.msRequestFullscreen();
                }
            }
        }

        // Función para silenciar/activar audio
        function toggleMute() {
            if (video) {
                video.muted = !video.muted;
                const btnMute = document.getElementById('btnMute');
                if (video.muted) {
                    btnMute.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';
                } else {
                    btnMute.innerHTML = '<i class="bi bi-volume-up-fill"></i>';
                }
            }
        }

        // Registrar visualización
        if (video) {
            video.addEventListener('play', function() {
                console.log('Reproduciendo película ID: <?php echo $id_pelicula; ?>');
            });

            video.addEventListener('timeupdate', function() {
                const progreso = (video.currentTime / video.duration) * 100;
                // Aquí podrías guardar el progreso en la BD si quisieras
            });
        }
    </script>
</body>
</html>

