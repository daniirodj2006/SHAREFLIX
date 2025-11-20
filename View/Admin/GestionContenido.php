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

  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/ContenidoController.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutAdmin.php';

  // Obtener datos
  $contenido = ConsultarContenido();
  $generos = ConsultarGeneros();
  $categorias = ConsultarCategorias();

  // Obtener película por ID si se solicita edición
  $peliculaEditar = null;
  if(isset($_GET['editar'])) {
      $idContenido = intval($_GET['editar']);
      $resultadoPelicula = ConsultarContenidoPorId($idContenido);
      if($resultadoPelicula && mysqli_num_rows($resultadoPelicula) > 0) {
          $peliculaEditar = mysqli_fetch_array($resultadoPelicula);
      }
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Contenido - Shareflix</title>
    <?php ShowCSS(); ?>
</head>

<body>
    <?php ShowMenu(); ?>
    
    <div class="content-wrapper">
        <div class="container-fluid py-4">
            
            <!-- HEADER -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h2 fw-bold text-white mb-1">
                        <i class="bi bi-collection-play me-2"></i>Gestión de Contenido
                    </h1>
                    <p class="text-muted mb-0">Administra películas, géneros y categorías</p>
                </div>
            </div>

            <?php MostrarMensaje(); ?>

            <!-- TABS DE NAVEGACIÓN -->
            <ul class="nav nav-tabs mb-4" id="contentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="peliculas-tab" data-bs-toggle="tab" 
                            data-bs-target="#peliculas" type="button" role="tab">
                        <i class="bi bi-film me-2"></i>Películas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="generos-tab" data-bs-toggle="tab" 
                            data-bs-target="#generos" type="button" role="tab">
                        <i class="bi bi-collection me-2"></i>Géneros
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="categorias-tab" data-bs-toggle="tab" 
                            data-bs-target="#categorias" type="button" role="tab">
                        <i class="bi bi-tags me-2"></i>Categorías
                    </button>
                </li>
            </ul>

            <!-- CONTENIDO DE LOS TABS -->
            <div class="tab-content" id="contentTabsContent">
                
                <!-- ============================================ -->
                <!-- TAB 1: PELÍCULAS -->
                <!-- ============================================ -->
                <div class="tab-pane fade show active" id="peliculas" role="tabpanel">
                    
                    <!-- Barra de acciones -->
                    <div class="content-card mb-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(255, 140, 66, 0.1); border: 1px solid rgba(255, 140, 66, 0.2); color: var(--color-primary);">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" class="form-control-shareflix" id="buscarPelicula" 
                                               placeholder="Buscar película por título...">
                                    </div>
                                </div>
                                <div class="col-md-6 text-end mt-3 mt-md-0">
                                    <button class="btn btn-primary-shareflix" data-bs-toggle="modal" 
                                            data-bs-target="#modalPelicula" onclick="limpiarFormularioPelicula()">
                                        <i class="bi bi-plus-circle me-2"></i>Agregar Película
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de películas -->
                    <div class="content-card">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-shareflix table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Portada</th>
                                            <th>Título</th>
                                            <th>Géneros</th>
                                            <th>Duración</th>
                                            <th>Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($contenido && mysqli_num_rows($contenido) > 0):
                                            while($item = mysqli_fetch_array($contenido)): 
                                        ?>
                                        <tr>
                                            <td class="align-middle"><?php echo $item['ConsecutivoContenido']; ?></td>
                                            <td>
                                                <?php if(!empty($item['Imagen'])): ?>
                                                    <img src="../img/contenido/<?php echo $item['Imagen']; ?>" 
                                                         alt="<?php echo $item['Titulo']; ?>"
                                                         style="width: 50px; height: 75px; object-fit: cover; border-radius: 8px;">
                                                <?php else: ?>
                                                    <div style="width: 50px; height: 75px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="bi bi-film" style="font-size: 1.5rem; color: var(--color-dark);"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <strong style="color: var(--color-text);"><?php echo $item['Titulo']; ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo substr($item['Descripcion'], 0, 50); ?>...</small>
                                            </td>
                                            <td class="align-middle">
                                                <?php 
                                                if(!empty($item['Generos'])): 
                                                    $generos_array = explode(', ', $item['Generos']);
                                                    foreach($generos_array as $genero):
                                                ?>
                                                    <span class="badge me-1" style="background: rgba(255, 140, 66, 0.2); color: var(--color-primary);">
                                                        <?php echo $genero; ?>
                                                    </span>
                                                <?php 
                                                    endforeach;
                                                else:
                                                ?>
                                                    <span class="badge" style="background: rgba(156, 163, 175, 0.2); color: #9ca3af;">
                                                        Sin género
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle"><?php echo $item['Duracion']; ?> min</td>
                                            <td class="align-middle">
                                                <?php if($item['Activo'] == 1): ?>
                                                    <span class="badge badge-success">
                                                        <i class="bi bi-check-circle"></i> Activo
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">
                                                        <i class="bi bi-x-circle"></i> Inactivo
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-sm btn-action-edit me-1" 
                                                        onclick="editarPelicula(<?php echo $item['ConsecutivoContenido']; ?>)"
                                                        title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-action-edit me-1" 
        onclick="cambiarEstado(<?php echo $item['ConsecutivoContenido']; ?>)"
        title="<?php echo $item['Activo'] ? 'Desactivar' : 'Activar'; ?>">
    <i class="bi bi-power"></i>
</button>
<button class="btn btn-sm btn-action-delete" 
        onclick="eliminarPelicula(<?php echo $item['ConsecutivoContenido']; ?>, '<?php echo addslashes($item['Titulo']); ?>')"
        title="Eliminar permanentemente">
    <i class="bi bi-trash"></i>
</button>
                                            </td>
                                        </tr>
                                        <?php 
                                            endwhile;
                                        else:
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="bi bi-film" style="font-size: 3rem; color: var(--color-primary); opacity: 0.3;"></i>
                                                <p class="text-muted mt-3">No hay contenido disponible</p>
                                                <button class="btn btn-primary-shareflix btn-sm" data-bs-toggle="modal" 
                                                        data-bs-target="#modalPelicula" onclick="limpiarFormularioPelicula()">
                                                    <i class="bi bi-plus-circle me-2"></i>Agregar tu primera película
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ============================================ -->
                <!-- TAB 2: GÉNEROS -->
                <!-- ============================================ -->
                <div class="tab-pane fade" id="generos" role="tabpanel">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="content-card">
                                <div class="card-header bg-transparent border-bottom" style="border-color: rgba(255, 140, 66, 0.2) !important; padding: 1.5rem;">
                                    <h5 class="mb-0" style="color: var(--color-text);">
                                        <i class="bi bi-plus-circle me-2"></i>Agregar Género
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <form method="POST">
                                        <input type="hidden" name="idGenero" id="idGenero">
                                        
                                        <div class="mb-3">
                                            <label class="form-label" style="color: var(--color-text-muted);">Nombre del Género</label>
                                            <input type="text" class="form-control-shareflix" name="txtNombreGenero" 
                                                   id="txtNombreGenero" placeholder="Ej: Acción" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label" style="color: var(--color-text-muted);">Descripción</label>
                                            <textarea class="form-control-shareflix" name="txtDescripcionGenero" 
                                                      id="txtDescripcionGenero" rows="3" 
                                                      placeholder="Descripción del género"></textarea>
                                        </div>
                                        
                                        <button type="submit" name="btnAgregarGenero" class="btn btn-primary-shareflix w-100">
                                            <i class="bi bi-save me-2"></i>Guardar Género
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 mt-4 mt-md-0">
                            <div class="content-card">
                                <div class="card-header bg-transparent border-bottom" style="border-color: rgba(255, 140, 66, 0.2) !important; padding: 1.5rem;">
                                    <h5 class="mb-0" style="color: var(--color-text);">
                                        <i class="bi bi-list-ul me-2"></i>Lista de Géneros
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="list-group">
                                        <?php 
                                        if($generos && mysqli_num_rows($generos) > 0):
                                            mysqli_data_seek($generos, 0);
                                            while($genero = mysqli_fetch_array($generos)): 
                                        ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center" style="background: rgba(255, 140, 66, 0.05); border: 1px solid rgba(255, 140, 66, 0.1); margin-bottom: 0.5rem; border-radius: 8px;">
                                            <div>
                                                <h6 class="mb-1" style="color: var(--color-text);">
                                                    <?php echo $genero['Nombre']; ?>
                                                </h6>
                                                <small class="text-muted"><?php echo $genero['Descripcion']; ?></small>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-action-edit me-1" 
                                                        onclick="editarGenero(<?php echo $genero['ConsecutivoGenero']; ?>, '<?php echo addslashes($genero['Nombre']); ?>', '<?php echo addslashes($genero['Descripcion']); ?>')">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-action-delete"
                                                        onclick="eliminarGenero(<?php echo $genero['ConsecutivoGenero']; ?>, '<?php echo addslashes($genero['Nombre']); ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <?php 
                                            endwhile;
                                        else:
                                        ?>
                                        <div class="text-center py-4">
                                            <i class="bi bi-collection" style="font-size: 2rem; color: var(--color-primary); opacity: 0.3;"></i>
                                            <p class="text-muted mt-2">No hay géneros registrados</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ============================================ -->
                <!-- TAB 3: CATEGORÍAS -->
                <!-- ============================================ -->
                <div class="tab-pane fade" id="categorias" role="tabpanel">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="content-card">
                                <div class="card-header bg-transparent border-bottom" style="border-color: rgba(255, 140, 66, 0.2) !important; padding: 1.5rem;">
                                    <h5 class="mb-0" style="color: var(--color-text);">
                                        <i class="bi bi-plus-circle me-2"></i>Agregar Categoría
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <form method="POST">
                                        <input type="hidden" name="idCategoria" id="idCategoria">
                                        
                                        <div class="mb-3">
                                            <label class="form-label" style="color: var(--color-text-muted);">Nombre de la Categoría</label>
                                            <input type="text" class="form-control-shareflix" name="txtNombreCategoria" 
                                                   id="txtNombreCategoria" placeholder="Ej: Estrenos" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label" style="color: var(--color-text-muted);">Descripción</label>
                                            <textarea class="form-control-shareflix" name="txtDescripcionCategoria" 
                                                      id="txtDescripcionCategoria" rows="3" 
                                                      placeholder="Descripción de la categoría"></textarea>
                                        </div>
                                        
                                        <button type="submit" name="btnAgregarCategoria" class="btn btn-primary-shareflix w-100">
                                            <i class="bi bi-save me-2"></i>Guardar Categoría
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 mt-4 mt-md-0">
                            <div class="content-card">
                                <div class="card-header bg-transparent border-bottom" style="border-color: rgba(255, 140, 66, 0.2) !important; padding: 1.5rem;">
                                    <h5 class="mb-0" style="color: var(--color-text);">
                                        <i class="bi bi-list-ul me-2"></i>Lista de Categorías
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="list-group">
                                        <?php 
                                        if($categorias && mysqli_num_rows($categorias) > 0):
                                            mysqli_data_seek($categorias, 0);
                                            while($categoria = mysqli_fetch_array($categorias)): 
                                        ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center" style="background: rgba(255, 140, 66, 0.05); border: 1px solid rgba(255, 140, 66, 0.1); margin-bottom: 0.5rem; border-radius: 8px;">
                                            <div>
                                                <h6 class="mb-1" style="color: var(--color-text);">
                                                    <?php echo $categoria['Nombre']; ?>
                                                </h6>
                                                <small class="text-muted"><?php echo $categoria['Descripcion']; ?></small>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-action-edit me-1"
                                                        onclick="editarCategoria(<?php echo $categoria['ConsecutivoCategoria']; ?>, '<?php echo addslashes($categoria['Nombre']); ?>', '<?php echo addslashes($categoria['Descripcion']); ?>')">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-action-delete"
                                                        onclick="eliminarCategoria(<?php echo $categoria['ConsecutivoCategoria']; ?>, '<?php echo addslashes($categoria['Nombre']); ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <?php 
                                            endwhile;
                                        else:
                                        ?>
                                        <div class="text-center py-4">
                                            <i class="bi bi-tags" style="font-size: 2rem; color: var(--color-primary); opacity: 0.3;"></i>
                                            <p class="text-muted mt-2">No hay categorías registradas</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- MODAL AGREGAR/EDITAR PELÍCULA -->
    <div class="modal fade" id="modalPelicula" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: var(--color-dark-light); border: 1px solid rgba(255, 140, 66, 0.2);">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255, 140, 66, 0.2);">
                    <h5 class="modal-title" style="color: var(--color-text);">
                        <i class="bi bi-film me-2"></i><span id="tituloModal">Agregar Película</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="formPelicula" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="idContenido" name="idContenido">
                        <input type="hidden" id="imagenAnterior" name="imagenAnterior">
                        
                        <div class="row g-3">
                            <!-- Título -->
                            <div class="col-12">
                                <label class="form-label" style="color: var(--color-text-muted);">Título *</label>
                                <input type="text" class="form-control-shareflix" id="txtTitulo" name="txtTitulo" required>
                            </div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <label class="form-label" style="color: var(--color-text-muted);">Descripción</label>
                                <textarea class="form-control-shareflix" id="txtDescripcion" name="txtDescripcion" rows="3"></textarea>
                            </div>

                            <!-- Duración, Calificación y Fecha -->
                            <div class="col-md-4">
                                <label class="form-label" style="color: var(--color-text-muted);">Duración (min) *</label>
                                <input type="number" class="form-control-shareflix" id="txtDuracion" name="txtDuracion" min="1" required>
                            </div>

                     <div class="col-md-4">
    <label class="form-label" style="color: var(--color-text-muted);">Calificación *</label>
    <select class="form-control-shareflix" id="txtCalificacion" name="txtCalificacion" required style="color: #000;">
        <option value="" style="color: #000;">Seleccione...</option>
        <option value="ATP" style="color: #000;">ATP (Todo público)</option>
        <option value="+7" style="color: #000;">+7 (Mayores de 7)</option>
        <option value="+13" style="color: #000;">+13 (Mayores de 13)</option>
        <option value="+16" style="color: #000;">+16 (Mayores de 16)</option>
        <option value="+18" style="color: #000;">+18 (Adultos)</option>
    </select>
</div>

                            <div class="col-md-4">
                                <label class="form-label" style="color: var(--color-text-muted);">Fecha de Publicación *</label>
                                <input type="date" class="form-control-shareflix" id="txtFechaPublicacion" name="txtFechaPublicacion" required>
                            </div>

                            <!-- Géneros -->
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--color-text-muted);">Géneros *</label>
                                <select class="form-control-shareflix" id="selectGeneros" name="generos[]" multiple size="5" required>
                                    <?php 
                                    if($generos):
                                        mysqli_data_seek($generos, 0);
                                        while($genero = mysqli_fetch_array($generos)): 
                                    ?>
                                        <option value="<?php echo $genero['ConsecutivoGenero']; ?>">
                                            <?php echo $genero['Nombre']; ?>
                                        </option>
                                    <?php 
                                        endwhile;
                                    endif;
                                    ?>
                                </select>
                                <small class="text-muted">Mantén presionado Ctrl para seleccionar varios</small>
                            </div>

                            <!-- Categorías -->
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--color-text-muted);">Categorías *</label>
                                <select class="form-control-shareflix" id="selectCategorias" name="categorias[]" multiple size="5" required>
                                    <?php 
                                    if($categorias):
                                        mysqli_data_seek($categorias, 0);
                                        while($categoria = mysqli_fetch_array($categorias)): 
                                    ?>
                                        <option value="<?php echo $categoria['ConsecutivoCategoria']; ?>">
                                            <?php echo $categoria['Nombre']; ?>
                                        </option>
                                    <?php 
                                        endwhile;
                                    endif;
                                    ?>
                                </select>
                                <small class="text-muted">Mantén presionado Ctrl para seleccionar varias</small>
                            </div>

                            <!-- Imagen -->
                            <div class="col-12">
                                <label class="form-label" style="color: var(--color-text-muted);">Imagen del Poster *</label>
                                <input type="file" class="form-control-shareflix" id="filePoster" name="filePoster" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP (Máx. 5MB)</small>
                                
                                <!-- Preview de la imagen -->
                                <div id="previewImagen" class="mt-3" style="display: none;">
                                    <img id="imgPreview" src="" style="max-width: 200px; border-radius: 10px; border: 2px solid var(--color-primary);">
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" name="btnAgregarContenido" id="btnSubmitPelicula" class="btn btn-primary-shareflix w-100">
                                <i class="bi bi-save me-2"></i>Guardar Película
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php ShowJS(); ?>
    
    <script>
    // ===========================================
    // MANTENER PESTAÑA ACTIVA
    // ===========================================
    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            const tab = new bootstrap.Tab(document.querySelector(`#${activeTab}-tab`));
            tab.show();
            localStorage.removeItem('activeTab');
        }
    });

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const activePane = document.querySelector('.tab-pane.active');
            if (activePane) {
                localStorage.setItem('activeTab', activePane.id);
            }
        });
    });

    // ===========================================
    // FUNCIONES DE GÉNEROS
    // ===========================================
    function editarGenero(id, nombre, descripcion) {
        document.getElementById('idGenero').value = id;
        document.getElementById('txtNombreGenero').value = nombre;
        document.getElementById('txtDescripcionGenero').value = descripcion;
        
        const form = document.getElementById('idGenero').closest('form');
        const btn = form.querySelector('button[name="btnAgregarGenero"]');
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Actualizar Género';
        btn.name = 'btnActualizarGenero';
        
        document.getElementById('txtNombreGenero').scrollIntoView({ behavior: 'smooth', block: 'center' });
        document.getElementById('txtNombreGenero').focus();
    }

    function eliminarGenero(id, nombre) {
        if(confirm('¿Estás seguro de eliminar el género "' + nombre + '"?\n\nEsto puede afectar películas asociadas.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="btnEliminarGenero" value="1">
                <input type="hidden" name="idGenero" value="${id}">
            `;
            document.body.appendChild(form);
            localStorage.setItem('activeTab', 'generos');
            form.submit();
        }
    }

    // ===========================================
    // FUNCIONES DE CATEGORÍAS
    // ===========================================
    function editarCategoria(id, nombre, descripcion) {
        document.getElementById('idCategoria').value = id;
        document.getElementById('txtNombreCategoria').value = nombre;
        document.getElementById('txtDescripcionCategoria').value = descripcion;
        
        const form = document.getElementById('idCategoria').closest('form');
        const btn = form.querySelector('button[name="btnAgregarCategoria"]');
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Actualizar Categoría';
        btn.name = 'btnActualizarCategoria';
        
        document.getElementById('txtNombreCategoria').scrollIntoView({ behavior: 'smooth', block: 'center' });
        document.getElementById('txtNombreCategoria').focus();
    }

    function eliminarCategoria(id, nombre) {
        if(confirm('¿Estás seguro de eliminar la categoría "' + nombre + '"?\n\nEsto puede afectar películas asociadas.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="btnEliminarCategoria" value="1">
                <input type="hidden" name="idCategoria" value="${id}">
            `;
            document.body.appendChild(form);
            localStorage.setItem('activeTab', 'categorias');
            form.submit();
        }
    }

    // ===========================================
    // FUNCIONES DE PELÍCULAS
    // ===========================================
    function editarPelicula(id) {
        window.location.href = 'GestionContenido.php?editar=' + id;
    }

    function cambiarEstado(id) {
        if(confirm('¿Está seguro de cambiar el estado de esta película?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="btnCambiarEstadoContenido" value="1">
                <input type="hidden" name="idContenido" value="${id}">
            `;
            document.body.appendChild(form);
            localStorage.setItem('activeTab', 'peliculas');
            form.submit();
        }
    }

    function limpiarFormularioPelicula() {
        document.getElementById('formPelicula').reset();
        document.getElementById('idContenido').value = '';
        document.getElementById('imagenAnterior').value = '';
        document.getElementById('previewImagen').style.display = 'none';
        document.getElementById('tituloModal').textContent = 'Agregar Película';
        document.getElementById('btnSubmitPelicula').innerHTML = '<i class="bi bi-save me-2"></i>Guardar Película';
        document.getElementById('btnSubmitPelicula').name = 'btnAgregarContenido';
    }

    // ===========================================
    // MODAL DE PELÍCULA
    // ===========================================
    const filePoster = document.getElementById('filePoster');
    if(filePoster) {
        filePoster.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imgPreview').src = e.target.result;
                    document.getElementById('previewImagen').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    const modalPelicula = document.getElementById('modalPelicula');
    if(modalPelicula) {
        modalPelicula.addEventListener('hidden.bs.modal', function () {
            limpiarFormularioPelicula();
        });
    }

    // ===========================================
    // BÚSQUEDA DE PELÍCULAS
    // ===========================================
    const buscarPelicula = document.getElementById('buscarPelicula');
    if(buscarPelicula) {
        buscarPelicula.addEventListener('keyup', function() {
            const busqueda = this.value.toLowerCase();
            const filas = document.querySelectorAll('.table-shareflix tbody tr');
            
            filas.forEach(function(fila) {
                const titulo = fila.querySelector('td:nth-child(3)');
                if(titulo) {
                    const textoTitulo = titulo.textContent.toLowerCase();
                    if(textoTitulo.includes(busqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                }
            });
        });
    }

    // ===========================================
    // CARGAR DATOS PARA EDICIÓN
    // ===========================================
 <?php if($peliculaEditar): ?>
    // Obtener géneros y categorías de la película
    <?php 
    // Obtener géneros del contenido
    $generosContenido = ObtenerGenerosContenido($peliculaEditar['ConsecutivoContenido']);
    $generosIds = array();
    if($generosContenido && mysqli_num_rows($generosContenido) > 0) {
        while($gen = mysqli_fetch_array($generosContenido)) {
            $generosIds[] = $gen['idGenero'];
        }
    }
    
    // Obtener categorías del contenido
    $categoriasContenido = ObtenerCategoriasContenido($peliculaEditar['ConsecutivoContenido']);
    $categoriasIds = array();
    if($categoriasContenido && mysqli_num_rows($categoriasContenido) > 0) {
        while($cat = mysqli_fetch_array($categoriasContenido)) {
            $categoriasIds[] = $cat['idCategoria'];
        }
    }
    ?>
    
    document.addEventListener('DOMContentLoaded', function() {
        // Llenar el formulario con los datos
        document.getElementById('idContenido').value = '<?php echo $peliculaEditar['ConsecutivoContenido']; ?>';
        document.getElementById('txtTitulo').value = '<?php echo addslashes($peliculaEditar['Titulo']); ?>';
        document.getElementById('txtDescripcion').value = '<?php echo addslashes($peliculaEditar['Descripcion']); ?>';
        document.getElementById('txtDuracion').value = '<?php echo $peliculaEditar['Duracion']; ?>';
        document.getElementById('txtCalificacion').value = '<?php echo $peliculaEditar['CalificacionEdad']; ?>';
        document.getElementById('txtFechaPublicacion').value = '<?php echo $peliculaEditar['fechaPublicacion']; ?>';
        document.getElementById('imagenAnterior').value = '<?php echo $peliculaEditar['Imagen']; ?>';
        
        // Pre-seleccionar géneros
        const selectGeneros = document.getElementById('selectGeneros');
        const generosSeleccionados = [<?php echo implode(',', $generosIds); ?>];
        for(let i = 0; i < selectGeneros.options.length; i++) {
            if(generosSeleccionados.includes(parseInt(selectGeneros.options[i].value))) {
                selectGeneros.options[i].selected = true;
            }
        }
        
        // Pre-seleccionar categorías
        const selectCategorias = document.getElementById('selectCategorias');
        const categoriasSeleccionadas = [<?php echo implode(',', $categoriasIds); ?>];
        for(let i = 0; i < selectCategorias.options.length; i++) {
            if(categoriasSeleccionadas.includes(parseInt(selectCategorias.options[i].value))) {
                selectCategorias.options[i].selected = true;
            }
        }
        
        // Cambiar título del modal
        document.getElementById('tituloModal').textContent = 'Editar Película';
        
        // Cambiar botón
        const btn = document.getElementById('btnSubmitPelicula');
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Actualizar Película';
        btn.name = 'btnActualizarContenido';
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modalPelicula'));
        modal.show();
        
        // Ir a pestaña de películas
        const tab = new bootstrap.Tab(document.querySelector('#peliculas-tab'));
        tab.show();
    });
<?php endif; ?>
function eliminarPelicula(id, titulo) {
    if(confirm('⚠️ ¿Estás SEGURO de eliminar PERMANENTEMENTE la película "' + titulo + '"?\n\nEsta acción NO se puede deshacer y eliminará:\n- La película\n- Sus géneros\n- Sus categorías\n- De los favoritos de usuarios')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="btnEliminarContenido" value="1">
            <input type="hidden" name="idContenido" value="${id}">
        `;
        document.body.appendChild(form);
        localStorage.setItem('activeTab', 'peliculas');
        form.submit();
    }
}
    </script>

</body>
</html>