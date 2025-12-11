<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/UtilesController.php';
    
    // Validar que sea administrador
    ValidarSesionAdmin();

    function ShowCSS()
    {
        echo '
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
            <title>Shareflix Admin - Panel de Administración</title>
            <meta name="description" content="Panel de administración de Shareflix" />
            
            <!-- CSS -->
            <link rel="stylesheet" href="../css/boxicons.css" />
            <link rel="stylesheet" href="../css/core.css" />
            <link rel="stylesheet" href="../css/shareflix.css" />
            <link rel="stylesheet" href="../css/demo.css" />
            <link rel="stylesheet" href="../css/perfect-scrollbar.css" />
            
            <!-- Font Awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
            
            <!-- Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
            
            <!-- Scripts helpers -->
            <script src="../js/helpers.js"></script>
            <script src="../js/config.js"></script>
        </head>';
    }

    function ShowJS()
    {
        echo '
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <!-- jQuery Validate -->
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        
        <!-- Bootstrap y componentes -->
        <script src="../js/popper.js"></script>
        <script src="../js/bootstrap.js"></script>
        <script src="../js/perfect-scrollbar.js"></script>
        <script src="../js/menu.js"></script>
        <script src="../js/main.js"></script>';
    }

    function ShowMenu()
    {
        $nombre = $_SESSION["Nombre"] ?? "Admin";
        $paginaActual = basename($_SERVER['PHP_SELF']);
        
        echo '
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <a href="Dashboard.php">
                    
                    <h3 class="gradient-text" style="margin-top: 10px;">SHAREFLIX</h3>
                </a>
            </div>

            <!-- Información de Admin -->
            <div style="padding: 1rem; text-align: center; background: rgba(255, 140, 66, 0.1); border-radius: 10px; margin: 1rem;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fas fa-user-shield" style="color: #FFB84D; font-size: 1.2rem;"></i>
                    <span class="badge badge-admin">
                        Administrador
                    </span>
                </div>
                <small style="color: var(--color-text-muted); display: block; margin-top: 5px;">
                    Panel de Control
                </small>
            </div>

            <ul class="sidebar-menu">
                <li class="menu-item">
                    <a href="Dashboard.php" class="menu-link ' . ($paginaActual == 'Dashboard.php' ? 'active' : '') . '">
                        <i class="bi bi-speedometer2 menu-icon"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="menu-item">
                    <a href="GestionContenido.php" class="menu-link ' . ($paginaActual == 'GestionContenido.php' ? 'active' : '') . '">
                        <i class="bi bi-film menu-icon"></i>
                        <span>Contenido</span>
                    </a>
                </li>
                
                <li class="menu-item">
                    <a href="GestionUsuarios.php" class="menu-link ' . ($paginaActual == 'GestionUsuarios.php' ? 'active' : '') . '">
                        <i class="bi bi-people-fill menu-icon"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
              
                
                <hr style="border-color: rgba(255, 140, 66, 0.2); margin: 1rem 0;">
                
              
                
                <li class="menu-item">
                    <a href="../Admin/Perfil.php" class="menu-link">
                        <i class="fas fa-user menu-icon"></i>
                        <span>Mi Perfil</span>
                    </a>
                </li>
                
                
                
                <hr style="border-color: rgba(255, 140, 66, 0.2); margin: 1rem 0;">
                
                <li class="menu-item">
                    <form action="" method="POST" style="margin: 0;">
                        <button type="submit" name="btnCerrarSesion" class="menu-link" style="width: 100%; background: none; border: none; text-align: left; cursor: pointer; padding: 12px 15px;">
                            <i class="bi bi-box-arrow-right menu-icon"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>';
    }

    function MostrarMensaje()
    {
        if(isset($_POST["Mensaje"]))
        {
            $tipoMensaje = isset($_POST["TipoMensaje"]) ? $_POST["TipoMensaje"] : "error";
            $claseAlerta = $tipoMensaje == "success" ? "alert-success" : "alert-error";
            
            echo '
            <div class="alert-shareflix ' . $claseAlerta . '" role="alert" style="margin-bottom: 2rem;">
                <i class="bi bi-' . ($tipoMensaje == "success" ? "check-circle" : "exclamation-circle") . '-fill me-2"></i>
                ' . $_POST["Mensaje"] . '
            </div>';
        }
    }

    // Procesar cierre de sesión
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/InicioController.php';
?>