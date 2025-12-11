<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UsuarioModel.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/ContenidoModel.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/UtilesController.php';

    // ============================================
    // OBTENER ESTADÍSTICAS DEL DASHBOARD
    // ============================================
    
    function ObtenerEstadisticasDashboardController()
    {
        ValidarSesionAdmin();
        
        // Obtener todas las estadísticas necesarias
        $totalPeliculas = ContarTotalPeliculas();
        $peliculasNuevasMes = ContarPeliculasNuevasMes();
        $totalUsuarios = ContarTotalUsuarios();
        $usuariosPremium = ContarUsuariosPremium();
        $usuariosGratis = ContarUsuariosGratis();
        $usuariosActivosHoy = ContarUsuariosActivosHoy();
        $porcentajeCrecimiento = CalcularCrecimientoUsuarios();
        $totalGeneros = ContarTotalGeneros();
        $totalCategorias = ContarTotalCategorias();
        $peliculasPopulares = ObtenerPeliculasPopulares(5); // Top 5
        $usuariosRecientes = ObtenerUsuariosRecientes(5); // Últimos 5
        
        return array(
            "totalPeliculas" => $totalPeliculas,
            "peliculasNuevasMes" => $peliculasNuevasMes,
            "totalUsuarios" => $totalUsuarios,
            "usuariosPremium" => $usuariosPremium,
            "usuariosGratis" => $usuariosGratis,
            "usuariosActivosHoy" => $usuariosActivosHoy,
            "porcentajeCrecimiento" => $porcentajeCrecimiento,
            "totalGeneros" => $totalGeneros,
            "totalCategorias" => $totalCategorias,
            "peliculasPopulares" => $peliculasPopulares,
            "usuariosRecientes" => $usuariosRecientes
        );
    }

    // ============================================
    // OBTENER DATOS PARA GRÁFICO DE ACTIVIDAD (AJAX)
    // ============================================
    
    if(isset($_POST["obtenerDatosGraficoActividad"]))
    {
        ValidarSesionAdmin();
        
        $periodo = isset($_POST["periodo"]) ? intval($_POST["periodo"]) : 30;
        $datos = ObtenerDatosActividadUsuarios($periodo);
        
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'datos' => $datos
        ));
        exit;
    }

    // ============================================
    // OBTENER ESTADÍSTICAS GENERALES (AJAX)
    // ============================================
    
    if(isset($_POST["obtenerEstadisticasGenerales"]))
    {
        ValidarSesionAdmin();
        
        $estadisticas = ObtenerEstadisticasDashboardController();
        
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'estadisticas' => $estadisticas
        ));
        exit;
    }

?>
