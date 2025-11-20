<?php
  // Iniciar sesión solo si no hay una activa
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // Verificar que el usuario sea administrador
  if(!isset($_SESSION["ConsecutivoPerfil"]) || $_SESSION["ConsecutivoPerfil"] != 1)
  {
      header("Location: ../Inicio/IniciarSesion.php");
      exit();
  }
  
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/UsuarioController.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/LayoutAdmin.php';

  // Obtener lista de usuarios
  $usuarios = ObtenerUsuariosController();
  
  // Contar usuarios por tipo de suscripción
  $totalUsuarios = count($usuarios);
  $usuariosGratis = 0;
  $usuariosPremium = 0;
  
  foreach($usuarios as $usuario) {
      if($usuario['suscripcion'] == 'Gratis') $usuariosGratis++;
      if($usuario['suscripcion'] == 'Premium') $usuariosPremium++;
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Shareflix</title>
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
                        <i class="bi bi-people-fill me-2"></i>Gestión de Usuarios
                    </h1>
                    <p class="text-muted mb-0">Administra los usuarios y sus suscripciones en la plataforma</p>
                </div>
            </div>

            <?php MostrarMensaje(); ?>

            <!-- TARJETAS DE ESTADÍSTICAS -->
            <div class="row g-4 mb-4">
                
                <!-- Total Usuarios -->
                <div class="col-12 col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stats-content">
                            <p class="stats-label">Total Usuarios</p>
                            <h3 class="stats-value"><?php echo $totalUsuarios; ?></h3>
                        </div>
                    </div>
                </div>

                <!-- Usuarios Gratis -->
                <div class="col-12 col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(255, 193, 7, 0.1);">
                            <i class="bi bi-person" style="color: #ffc107;"></i>
                        </div>
                        <div class="stats-content">
                            <p class="stats-label">Usuarios Gratis</p>
                            <h3 class="stats-value"><?php echo $usuariosGratis; ?></h3>
                        </div>
                    </div>
                </div>

                <!-- Usuarios Premium -->
                <div class="col-12 col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div class="stats-content">
                            <p class="stats-label">Usuarios Premium</p>
                            <h3 class="stats-value"><?php echo $usuariosPremium; ?></h3>
                        </div>
                    </div>
                </div>

            </div>

            <!-- BARRA DE BÚSQUEDA Y FILTROS -->
            <div class="content-card mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" style="background: rgba(255, 140, 66, 0.1); border: 1px solid rgba(255, 140, 66, 0.2); color: var(--color-primary);">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control-shareflix" id="buscarUsuario" 
                                       placeholder="Buscar por nombre o correo...">
                            </div>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="filtroSuscripcion" id="todos" value="todos" checked>
                                <label class="btn btn-outline-light" for="todos">
                                    <i class="bi bi-people me-1"></i>Todos
                                </label>

                                <input type="radio" class="btn-check" name="filtroSuscripcion" id="gratis" value="Gratis">
                                <label class="btn btn-outline-warning" for="gratis">
                                    <i class="bi bi-person me-1"></i>Gratis
                                </label>

                                <input type="radio" class="btn-check" name="filtroSuscripcion" id="premium" value="Premium">
                                <label class="btn btn-outline-danger" for="premium">
                                    <i class="bi bi-star-fill me-1"></i>Premium
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLA DE USUARIOS -->
            <div class="content-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0" style="color: var(--color-text);">
                            <i class="bi bi-list-ul me-2"></i>Lista de Usuarios
                        </h5>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-shareflix table-hover" id="tablaUsuarios">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Suscripción</th>
                                    <th>Estado</th>
                                    <th>Fecha Registro</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(count($usuarios) > 0):
                                    foreach($usuarios as $usuario): 
                                ?>
                                <tr data-suscripcion="<?php echo $usuario['suscripcion']; ?>" 
                                    data-nombre="<?php echo strtolower($usuario['nombre']); ?>"
                                    data-correo="<?php echo strtolower($usuario['correo']); ?>">
                                    <td class="align-middle"><?php echo $usuario['id_usuario']; ?></td>
                                    <td class="align-middle"><?php echo $usuario['cedula']; ?></td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div style="width: 35px; height: 35px; border-radius: 50%; background: rgba(255, 140, 66, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                <i class="bi bi-person-fill" style="color: var(--color-primary);"></i>
                                            </div>
                                            <strong style="color: var(--color-text);"><?php echo $usuario['nombre']; ?></strong>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <i class="bi bi-envelope me-1"></i>
                                        <?php echo $usuario['correo']; ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if($usuario['suscripcion'] == 'Gratis'): ?>
                                            <span class="badge" style="background: rgba(255, 193, 7, 0.2); color: #ffc107;">
                                                <i class="bi bi-person me-1"></i>Gratis
                                            </span>
                                        <?php else: ?>
                                            <span class="badge" style="background: rgba(255, 140, 66, 0.2); color: var(--color-primary);">
                                                <i class="bi bi-star-fill me-1"></i>Premium
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if($usuario['activo'] == 1): ?>
                                            <span class="badge badge-success">
                                                <i class="bi bi-check-circle"></i> Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">
                                                <i class="bi bi-x-circle"></i> Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?>
                                        </small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <!-- BOTÓN EDITAR -->
                                        <button class="btn btn-sm btn-action-edit me-1" 
                                                onclick='editarUsuario(<?php echo json_encode(array(
                                                    "id" => $usuario["id_usuario"],
                                                    "cedula" => $usuario["cedula"],
                                                    "nombre" => $usuario["nombre"],
                                                    "correo" => $usuario["correo"]
                                                )); ?>)'
                                                title="Editar usuario">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        
                                        <!-- BOTÓN CAMBIAR SUSCRIPCIÓN -->
                                        <?php if($usuario['suscripcion'] == 'Gratis'): ?>
                                            <button class="btn btn-sm btn-primary-shareflix me-1" 
                                                    onclick="cambiarSuscripcion(<?php echo $usuario['id_usuario']; ?>, 2, '<?php echo addslashes($usuario['nombre']); ?>')"
                                                    title="Cambiar a Premium">
                                                <i class="bi bi-arrow-up-circle"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm" 
                                                    style="background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3);"
                                                    onclick="cambiarSuscripcion(<?php echo $usuario['id_usuario']; ?>, 1, '<?php echo addslashes($usuario['nombre']); ?>')"
                                                    title="Cambiar a Gratis">
                                                <i class="bi bi-arrow-down-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <!-- BOTÓN CAMBIAR ESTADO -->
                                        <button class="btn btn-sm btn-action-delete" 
                                                onclick="cambiarEstado(<?php echo $usuario['id_usuario']; ?>)"
                                                title="Cambiar estado">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach;
                                else:
                                ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-people" style="font-size: 3rem; color: var(--color-primary); opacity: 0.3;"></i>
                                        <p class="text-muted mt-3">No hay usuarios registrados</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL EDITAR USUARIO -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background: var(--color-dark-light); border: 1px solid rgba(255, 140, 66, 0.2);">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255, 140, 66, 0.2);">
                    <h5 class="modal-title" style="color: var(--color-text);">
                        <i class="bi bi-pencil-square me-2"></i>Editar Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="formEditarUsuario" method="POST">
                        <input type="hidden" id="editIdUsuario" name="idUsuario">
                        
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--color-text-muted);">Cédula</label>
                            <input type="text" class="form-control-shareflix" id="editCedula" name="cedula" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="color: var(--color-text-muted);">Nombre Completo</label>
                            <input type="text" class="form-control-shareflix" id="editNombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="color: var(--color-text-muted);">Correo Electrónico</label>
                            <input type="email" class="form-control-shareflix" id="editCorreo" name="correo" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="color: var(--color-text-muted);">Nueva Contraseña (Opcional)</label>
                            <input type="password" class="form-control-shareflix" id="editContrasenna" name="contrasenna" 
                                   placeholder="Dejar en blanco para no cambiar">
                            <small class="text-muted">Solo completa si deseas cambiar la contraseña</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="btnActualizarUsuario" class="btn btn-primary-shareflix">
                                <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php ShowJS(); ?>
    
    <script>
        // Búsqueda en tiempo real
        document.getElementById('buscarUsuario').addEventListener('keyup', function() {
            filtrarUsuarios();
        });

        // Filtros de suscripción
        document.querySelectorAll('input[name="filtroSuscripcion"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                filtrarUsuarios();
            });
        });

        function filtrarUsuarios() {
            const busqueda = document.getElementById('buscarUsuario').value.toLowerCase();
            const filtroSuscripcion = document.querySelector('input[name="filtroSuscripcion"]:checked').value;
            const filas = document.querySelectorAll('#tablaUsuarios tbody tr');

            filas.forEach(function(fila) {
                const nombre = fila.getAttribute('data-nombre') || '';
                const correo = fila.getAttribute('data-correo') || '';
                const suscripcion = fila.getAttribute('data-suscripcion') || '';

                const coincideBusqueda = nombre.includes(busqueda) || correo.includes(busqueda);
                const coincideSuscripcion = filtroSuscripcion === 'todos' || suscripcion === filtroSuscripcion;

                if (coincideBusqueda && coincideSuscripcion) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        function editarUsuario(usuario) {
            // Rellenar el formulario con los datos del usuario
            document.getElementById('editIdUsuario').value = usuario.id;
            document.getElementById('editCedula').value = usuario.cedula;
            document.getElementById('editNombre').value = usuario.nombre;
            document.getElementById('editCorreo').value = usuario.correo;
            document.getElementById('editContrasenna').value = '';
            
            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
            modal.show();
        }

        function cambiarSuscripcion(idUsuario, idSuscripcion, nombreUsuario) {
            const tipoNuevo = idSuscripcion == 2 ? 'Premium' : 'Gratis';
            
            if(confirm(`¿Cambiar a ${nombreUsuario} a suscripción ${tipoNuevo}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="btnCambiarSuscripcion" value="1">
                    <input type="hidden" name="idUsuario" value="${idUsuario}">
                    <input type="hidden" name="idSuscripcion" value="${idSuscripcion}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function cambiarEstado(idUsuario) {
            if(confirm('¿Cambiar el estado de este usuario?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="btnCambiarEstadoUsuario" value="1">
                    <input type="hidden" name="idUsuario" value="${idUsuario}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

</body>
</html>