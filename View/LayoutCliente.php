<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/UtilesController.php';
    
    // Validar que sea cliente
    ValidarSesionCliente();

  
    // LAYOUT CLIENTE - Con menú lateral de cliente
  

    function ShowCSS()
    {
        echo '
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
            <title>Shareflix - Tu plataforma de streaming</title>
            <meta name="description" content="Disfruta de las mejores películas y series en Shareflix" />
            
            <!-- CSS -->
            <link rel="stylesheet" href="../css/boxicons.css" />
            <link rel="stylesheet" href="../css/core.css" />
            <link rel="stylesheet" href="../css/shareflix.css" />
            <link rel="stylesheet" href="../css/demo.css" />
            <link rel="stylesheet" href="../css/perfect-scrollbar.css" />
            
            <!-- Font Awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
            
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
        $nombre = $_SESSION["Nombre"] ?? "Usuario";
        $tipoSuscripcion = $_SESSION["TipoSuscripcion"] ?? "Gratis";
        $limiteFavoritos = $_SESSION["LimiteFavoritos"] ?? 5;
        
        echo '
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <a href="Catalogo.php">
                    
                    <h3 class="gradient-text" style="margin-top: 10px;">SHAREFLIX</h3>
                </a>
            </div>

            <!-- Información de Suscripción -->
            <div style="padding: 1rem; text-align: center; background: rgba(255, 140, 66, 0.1); border-radius: 10px; margin: 1rem;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    ' . ($tipoSuscripcion == "Premium" ? '<i class="fas fa-crown" style="color: #FFB84D; font-size: 1.2rem;"></i>' : '') . '
                    <span class="badge ' . ($tipoSuscripcion == "Premium" ? "badge-premium" : "badge-free") . '">
                        ' . $tipoSuscripcion . '
                    </span>
                </div>
                <small style="color: var(--color-text-muted); display: block; margin-top: 5px;">
                    ' . ($tipoSuscripcion == "Premium" ? "Favoritos Ilimitados" : "Límite: " . $limiteFavoritos . " favoritos") . '
                </small>
            </div>

            <ul class="sidebar-menu">
                <li class="menu-item">
                    <a href="Catalogo.php" class="menu-link">
                        <i class="fas fa-film menu-icon"></i>
                        <span>Catálogo</span>
                    </a>
                </li>
                
                <li class="menu-item">
                    <a href="MisFavoritos.php" class="menu-link">
                        <i class="fas fa-heart menu-icon"></i>
                        <span>Mis Favoritos</span>
                    </a>
                </li>
                
                <hr style="border-color: rgba(255, 140, 66, 0.2); margin: 1rem 0;">
                
                <li class="menu-item">
                    <a href="Perfil.php" class="menu-link">
                        <i class="fas fa-user menu-icon"></i>
                        <span>Mi Perfil</span>
                    </a>
                </li>
                 
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

    function ShowNav()
    {
        $nombre = $_SESSION["Nombre"] ?? "Usuario";
        
        echo '
        <!-- Navbar -->
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center" 
             style="background: rgba(15, 15, 15, 0.95); backdrop-filter: blur(10px); padding: 1rem 2rem;">
            
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                    <i class="bx bx-menu bx-sm" style="color: var(--color-primary);"></i>
                </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse" style="width: 100%;">
                
                <!-- Barra de búsqueda -->
                <div class="navbar-nav align-items-center" style="flex: 1;">
                    <div class="nav-item d-flex align-items-center" style="width: 100%; max-width: 500px;">
                        <i class="bx bx-search fs-4 lh-0" style="color: var(--color-primary);"></i>
                        <input
                            type="text"
                            id="txtBusquedaGlobal"
                            class="form-control border-0 shadow-none"
                            placeholder="Buscar películas, series..."
                            style="background: rgba(255, 140, 66, 0.1); color: var(--color-text); margin-left: 10px;"
                        />
                    </div>
                </div>

                <!-- Usuario -->
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" 
                           data-bs-toggle="dropdown" style="color: var(--color-text);">
                            <i class="fas fa-user-circle" style="font-size: 1.5rem; color: var(--color-primary);"></i>
                            <span style="margin-left: 10px;">' . $nombre . '</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="background: var(--color-dark-light);">
                            <li>
                                <a class="dropdown-item" href="../Usuarios/Perfil.php" style="color: var(--color-text);">
                                    <i class="fas fa-user me-2" style="color: var(--color-primary);"></i>
                                    <span>Mi Perfil</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../Usuarios/Seguridad.php" style="color: var(--color-text);">
                                    <i class="fas fa-lock me-2" style="color: var(--color-primary);"></i>
                                    <span>Seguridad</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="MisFavoritos.php" style="color: var(--color-text);">
                                    <i class="fas fa-heart me-2" style="color: var(--color-primary);"></i>
                                    <span>Mis Favoritos</span>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown-divider" style="border-color: rgba(255, 140, 66, 0.2);"></div>
                            </li>
                            <li>
                                <form action="" method="POST" style="margin: 0;">
                                    <button type="submit" class="dropdown-item" id="btnCerrarSesion" name="btnCerrarSesion" 
                                            style="color: var(--color-text); background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                        <i class="fas fa-sign-out-alt me-2" style="color: var(--color-primary);"></i>
                                        <span>Cerrar Sesión</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>';
    }

    function ShowFooter()
    {
        echo '
        <footer class="content-footer footer" style="background: var(--color-dark-light); padding: 1.5rem; text-align: center; color: var(--color-text-muted); margin-top: 2rem;">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    © ' . date('Y') . ' <span style="color: var(--color-primary);">Shareflix</span>. Disfruta del mejor contenido.
                </div>
            </div>
        </footer>';
    }

    function MostrarMensaje()
    {
        if(isset($_POST["Mensaje"]))
        {
            $tipoMensaje = isset($_POST["TipoMensaje"]) ? $_POST["TipoMensaje"] : "error";
            $claseAlerta = $tipoMensaje == "success" ? "alert-success" : "alert-error";
            
            echo '
            <div class="alert-shareflix ' . $claseAlerta . '" role="alert" style="margin-bottom: 2rem;">
                ' . $_POST["Mensaje"] . '
            </div>';
        }
    }

    // Procesar cierre de sesión
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/InicioController.php';
?>


