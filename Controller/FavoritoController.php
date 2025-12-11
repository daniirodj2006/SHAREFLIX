<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/FavoritoModel.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/UtilesController.php';

// ========================================
// GESTIÓN DE FAVORITOS
// ========================================

// AGREGAR A FAVORITOS
if(isset($_POST["btnAgregarFavorito"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $idContenido = intval($_POST["idContenido"]);

    $resultado = AgregarFavorito($idUsuario, $idContenido);

    $_POST["Mensaje"] = $resultado['mensaje'];
    $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
    
    if(isset($resultado['limite']) && $resultado['limite']) {
        $_POST["MostrarUpgrade"] = true;
    }
}

// ELIMINAR DE FAVORITOS
if(isset($_POST["btnEliminarFavorito"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $idContenido = intval($_POST["idContenido"]);

    $resultado = EliminarFavorito($idUsuario, $idContenido);

    $_POST["Mensaje"] = $resultado['mensaje'];
    $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
}

// ========================================
// AJAX - TOGGLE Y GESTIÓN
// ========================================

// TOGGLE FAVORITO (AJAX)
if(isset($_POST["toggleFavorito"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $idContenido = intval($_POST["idContenido"]);

    $resultado = ToggleFavorito($idUsuario, $idContenido);

    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}

// AGREGAR A FAVORITOS (AJAX)
if(isset($_POST["agregarFavoritoAjax"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $idContenido = intval($_POST["idContenido"]);

    $resultado = AgregarFavorito($idUsuario, $idContenido);

    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}

// ELIMINAR DE FAVORITOS (AJAX)
if(isset($_POST["eliminarFavoritoAjax"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $idContenido = intval($_POST["idContenido"]);

    $resultado = EliminarFavorito($idUsuario, $idContenido);

    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}

// ========================================
// AJAX - CONSULTAS
// ========================================

// CONSULTAR FAVORITOS DEL USUARIO (AJAX)
if(isset($_POST["consultarFavoritos"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $resultado = ConsultarFavoritos($idUsuario);
    $favoritos = array();

    if($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_array($resultado)) {
            $favoritos[] = array(
                'idFavorito' => $fila['ConsecutivoFavorito'],
                'idContenido' => $fila['ConsecutivoContenido'],
                'titulo' => $fila['Titulo'],
                'descripcion' => $fila['Descripcion'],
                'duracion' => $fila['Duracion'],
                'imagen' => $fila['Imagen'],
                'calificacionEdad' => $fila['CalificacionEdad'],
                'fechaAgregado' => FormatearFechaMostrar($fila['fechaAgregado'])
            );
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'favoritos' => $favoritos,
        'total' => count($favoritos)
    ));
    exit;
}

// VERIFICAR SI ESTÁ EN FAVORITOS (AJAX)
if(isset($_POST["verificarFavorito"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $idContenido = intval($_POST["idContenido"]);

    $esFavorito = EstaEnFavoritos($idUsuario, $idContenido);

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'esFavorito' => $esFavorito
    ));
    exit;
}

// OBTENER IDS DE FAVORITOS (AJAX)
if(isset($_POST["obtenerIdsFavoritos"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $ids = ObtenerIdsFavoritos($idUsuario);

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'ids' => $ids
    ));
    exit;
}

// CONTAR FAVORITOS (AJAX)
if(isset($_POST["contarFavoritos"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $cantidad = ContarFavoritos($idUsuario);

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'cantidad' => $cantidad
    ));
    exit;
}

// OBTENER ESTADÍSTICAS DE FAVORITOS (AJAX)
if(isset($_POST["obtenerEstadisticasFavoritos"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $estadisticas = ObtenerEstadisticasFavoritos($idUsuario);

    if($estadisticas) {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'estadisticas' => $estadisticas
        ));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => false,
            'mensaje' => 'Error al obtener estadísticas'
        ));
    }
    exit;
}

// CONSULTAR FAVORITOS CON PAGINACIÓN (AJAX)
if(isset($_POST["consultarFavoritosPaginados"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $limite = isset($_POST["limite"]) ? intval($_POST["limite"]) : 12;
    $offset = isset($_POST["offset"]) ? intval($_POST["offset"]) : 0;

    $resultado = ConsultarFavoritosPaginados($idUsuario, $limite, $offset);
    $favoritos = array();

    if($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_array($resultado)) {
            $favoritos[] = array(
                'idFavorito' => $fila['ConsecutivoFavorito'],
                'idContenido' => $fila['ConsecutivoContenido'],
                'titulo' => $fila['Titulo'],
                'descripcion' => $fila['Descripcion'],
                'duracion' => $fila['Duracion'],
                'imagen' => $fila['Imagen'],
                'calificacionEdad' => $fila['CalificacionEdad'],
                'fechaAgregado' => FormatearFechaMostrar($fila['fechaAgregado'])
            );
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'favoritos' => $favoritos,
        'total' => count($favoritos)
    ));
    exit;
}

// ========================================
// GESTIÓN MASIVA
// ========================================

// ELIMINAR TODOS LOS FAVORITOS
if(isset($_POST["btnEliminarTodosFavoritos"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $resultado = EliminarTodosFavoritos($idUsuario);

    $_POST["Mensaje"] = $resultado['mensaje'];
    $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
}

// ELIMINAR TODOS LOS FAVORITOS (AJAX)
if(isset($_POST["eliminarTodosFavoritosAjax"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $resultado = EliminarTodosFavoritos($idUsuario);

    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}

// ========================================
// VALIDACIONES Y BÚSQUEDA
// ========================================

// VALIDAR EXCESO DE FAVORITOS (AJAX)
if(isset($_POST["validarExcesoFavoritos"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $resultado = ValidarExcesoFavoritos($idUsuario);

    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}

// BUSCAR EN FAVORITOS (AJAX)
if(isset($_POST["buscarEnFavoritos"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $termino = SanitizarEntrada($_POST["termino"]);

    $resultado = BuscarEnFavoritos($idUsuario, $termino);
    $favoritos = array();

    if($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_array($resultado)) {
            $favoritos[] = array(
                'idFavorito' => $fila['ConsecutivoFavorito'],
                'idContenido' => $fila['ConsecutivoContenido'],
                'titulo' => $fila['Titulo'],
                'descripcion' => $fila['Descripcion'],
                'duracion' => $fila['Duracion'],
                'imagen' => $fila['Imagen'],
                'calificacionEdad' => $fila['CalificacionEdad'],
                'fechaAgregado' => FormatearFechaMostrar($fila['fechaAgregado'])
            );
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'favoritos' => $favoritos,
        'total' => count($favoritos)
    ));
    exit;
}

// VERIFICAR LÍMITE ANTES DE AGREGAR (AJAX)
if(isset($_POST["verificarLimiteAntes"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $limiteInfo = VerificarLimiteFavoritos($idUsuario);

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'puedeAgregar' => $limiteInfo['puedeAgregar'],
        'limite' => $limiteInfo['limite'],
        'actual' => $limiteInfo['actual'],
        'disponible' => $limiteInfo['disponible'],
        'mensaje' => $limiteInfo['puedeAgregar'] 
            ? 'Puedes agregar más favoritos' 
            : 'Has alcanzado el límite de favoritos. Actualiza a Premium para favoritos ilimitados.'
    ));
    exit;
}

// ========================================
// ADMINISTRACIÓN
// ========================================

// LIMPIAR FAVORITOS DE CONTENIDO INACTIVO
if(isset($_POST["limpiarFavoritosInactivos"]))
{
    ValidarSesionAdmin();
    
    $resultado = LimpiarFavoritosInactivos();

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => $resultado,
        'mensaje' => $resultado 
            ? 'Favoritos inactivos eliminados' 
            : 'Error al limpiar favoritos'
    ));
    exit;
}

// ========================================
// FUNCIÓN PARA OBTENER FAVORITOS (Catálogo)
// ========================================
function ObtenerFavoritosUsuarioController($idUsuario)
{
    $resultado = ConsultarFavoritos($idUsuario);
    $favoritos = array();

    if($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_array($resultado)) {
            $favoritos[] = array(
                'id_favorito' => $fila['ConsecutivoFavorito'],
                'id_pelicula' => $fila['ConsecutivoContenido'],
                'titulo' => $fila['Titulo'],
                'descripcion' => $fila['Descripcion'],
                'duracion' => $fila['Duracion'],
                'imagen_url' => '../img/contenido/' . $fila['Imagen'],
                'calificacion_edad' => $fila['CalificacionEdad'],
                'anio' => date('Y'),
                'fecha_agregado' => $fila['fechaAgregado']
            );
        }
    }

    return $favoritos;
}
?>