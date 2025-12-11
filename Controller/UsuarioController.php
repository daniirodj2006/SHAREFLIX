<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UsuarioModel.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/UtilesController.php';

function ObtenerUsuariosController()
{
    try {
        $consulta = "CALL ConsultarUsuarios()";
        $resultado = LlamarProcedimiento($consulta);
        $usuarios = array();

        if($resultado && mysqli_num_rows($resultado) > 0) {
            while($fila = mysqli_fetch_array($resultado)) {
                $usuarios[] = array(
                    'id_usuario' => $fila['ConsecutivoUsuario'],
                    'cedula' => $fila['Identificacion'],
                    'nombre' => $fila['Nombre'],
                    'correo' => $fila['CorreoElectronico'],
                    'fecha_registro' => $fila['fechaRegistro'],
                    'activo' => $fila['activo'],
                    'perfil' => $fila['NombrePerfil'],
                    'suscripcion' => $fila['TipoSuscripcion'],
                    'id_suscripcion' => $fila['ConsecutivoSuscripcion']
                );
            }
        }

        return $usuarios;
    } catch (Exception $e) {
        RegistrarError("Error en ObtenerUsuariosController: " . $e->getMessage());
        return array();
    }
}

// ACTUALIZAR USUARIO (Admin)
if(isset($_POST["btnActualizarUsuario"]))
{
    ValidarSesionAdmin();
    
    $idUsuario = intval($_POST["idUsuario"]);
    $cedula = SanitizarEntrada($_POST["cedula"]);
    $nombre = SanitizarEntrada($_POST["nombre"]);
    $correo = SanitizarEntrada($_POST["correo"]);
    $contrasenna = isset($_POST["contrasenna"]) ? $_POST["contrasenna"] : '';

    // Validar email
    if(!ValidarEmail($correo)) {
        $_POST["Mensaje"] = "El formato del correo no es válido";
        $_POST["TipoMensaje"] = "error";
        return;
    }

    // Actualizar usuario
    $resultado = ActualizarUsuarioAdmin($idUsuario, $cedula, $nombre, $correo, $contrasenna);

    $_POST["Mensaje"] = $resultado['mensaje'];
    $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
}

// CAMBIAR SUSCRIPCIÓN DE USUARIO (Admin)
if(isset($_POST["btnCambiarSuscripcion"]))
{
    ValidarSesionAdmin();
    
    $idUsuario = intval($_POST["idUsuario"]);
    $idSuscripcionNueva = intval($_POST["idSuscripcion"]);

    $resultado = CambiarSuscripcionUsuario($idUsuario, $idSuscripcionNueva);

    $_POST["Mensaje"] = $resultado['mensaje'];
    $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
}

// CAMBIAR ESTADO DE USUARIO (Admin)
if(isset($_POST["btnCambiarEstadoUsuario"]))
{
    ValidarSesionAdmin();
    
    $idUsuario = intval($_POST["idUsuario"]);

    $resultado = CambiarEstadoUsuario($idUsuario);

    $_POST["Mensaje"] = $resultado['mensaje'];
    $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
}

// CONSULTAR USUARIO POR ID (AJAX)
if(isset($_POST["consultarUsuario"]))
{
    ValidarSesion();
    
    $idUsuario = intval($_POST["idUsuario"]);
    $usuario = ConsultarUsuarioPorId($idUsuario);

    if($usuario) {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'usuario' => array(
                'idUsuario' => $usuario['ConsecutivoUsuario'],
                'cedula' => $usuario['Identificacion'],
                'nombre' => $usuario['Nombre'],
                'correo' => $usuario['CorreoElectronico'],
                'fechaRegistro' => $usuario['fechaRegistro'],
                'nombrePerfil' => $usuario['NombrePerfil'],
                'tipoSuscripcion' => $usuario['TipoSuscripcion']
            )
        ));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => false,
            'mensaje' => 'Usuario no encontrado'
        ));
    }
    exit;
}

// CONSULTAR TODOS LOS USUARIOS (AJAX - Admin)
if(isset($_POST["consultarUsuarios"]))
{
    ValidarSesionAdmin();
    
    $resultado = ConsultarTodosUsuarios();
    $usuarios = array();

    if($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_array($resultado)) {
            $usuarios[] = array(
                'idUsuario' => $fila['ConsecutivoUsuario'],
                'cedula' => $fila['Identificacion'],
                'nombre' => $fila['Nombre'],
                'correo' => $fila['CorreoElectronico'],
                'fechaRegistro' => FormatearFechaMostrar($fila['fechaRegistro']),
                'activo' => $fila['activo'],
                'tipoSuscripcion' => $fila['TipoSuscripcion'],
                'idSuscripcion' => $fila['ConsecutivoSuscripcion']
            );
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'usuarios' => $usuarios
    ));
    exit;
}

// CONSULTAR SUSCRIPCIONES DISPONIBLES (AJAX)
if(isset($_POST["consultarSuscripciones"]))
{
    ValidarSesionAdmin();
    
    $resultado = ConsultarSuscripciones();
    $suscripciones = array();

    if($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_array($resultado)) {
            $suscripciones[] = array(
                'idSuscripcion' => $fila['ConsecutivoSuscripcion'],
                'tipoSuscripcion' => $fila['tipoSuscripcion'],
                'precio' => $fila['precio'],
                'descripcion' => $fila['descripcion'],
                'limiteFavoritos' => $fila['limiteFavoritos']
            );
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'suscripciones' => $suscripciones
    ));
    exit;
}

// OBTENER SUSCRIPCIÓN ACTUAL (AJAX)
if(isset($_POST["obtenerSuscripcionActual"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $suscripcion = ObtenerSuscripcionActual($idUsuario);

    if($suscripcion) {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'suscripcion' => array(
                'idSuscripcion' => $suscripcion['idSuscripcion'],
                'tipoSuscripcion' => $suscripcion['tipoSuscripcion'],
                'limiteFavoritos' => $suscripcion['limiteFavoritos'],
                'fechaInicio' => FormatearFechaMostrar($suscripcion['fechaInicio']),
                'fechaVencimiento' => FormatearFechaMostrar($suscripcion['fechaVencimiento']),
                'estado' => $suscripcion['estado']
            )
        ));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => false,
            'mensaje' => 'No se encontró suscripción activa'
        ));
    }
    exit;
}

// VERIFICAR SI ES PREMIUM (AJAX)
if(isset($_POST["verificarPremium"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $esPremium = EsUsuarioPremium($idUsuario);

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'esPremium' => $esPremium
    ));
    exit;
}

// OBTENER ESTADÍSTICAS DEL USUARIO (AJAX)
if(isset($_POST["obtenerEstadisticasUsuario"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $estadisticas = ObtenerEstadisticasUsuario($idUsuario);

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

// VERIFICAR LÍMITE DE FAVORITOS (AJAX)
if(isset($_POST["verificarLimiteFavoritos"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $limiteInfo = VerificarLimiteFavoritos($idUsuario);

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'limite' => $limiteInfo['limite'],
        'actual' => $limiteInfo['actual'],
        'disponible' => $limiteInfo['disponible'],
        'puedeAgregar' => $limiteInfo['puedeAgregar']
    ));
    exit;
}

// CAMBIAR SUSCRIPCIÓN CON AJAX (Admin)
if(isset($_POST["cambiarSuscripcionAjax"]))
{
    ValidarSesionAdmin();
    
    $idUsuario = intval($_POST["idUsuario"]);
    $idSuscripcionNueva = intval($_POST["idSuscripcion"]);

    $resultado = CambiarSuscripcionUsuario($idUsuario, $idSuscripcionNueva);

    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}

// CAMBIAR ESTADO CON AJAX (Admin)
if(isset($_POST["cambiarEstadoAjax"]))
{
    ValidarSesionAdmin();
    
    $idUsuario = intval($_POST["idUsuario"]);
    $resultado = CambiarEstadoUsuario($idUsuario);

    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}

// ELIMINAR USUARIO (Admin)
if(isset($_POST["btnEliminarUsuario"]))
{
    ValidarSesionAdmin();
    
    $idUsuario = intval($_POST["idUsuario"]);
    $resultado = EliminarUsuario($idUsuario);

    $_POST["Mensaje"] = $resultado['mensaje'];
    $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
}

// BUSCAR USUARIOS (AJAX - Admin)
if(isset($_POST["buscarUsuarios"]))
{
    ValidarSesionAdmin();
    
    $termino = SanitizarEntrada($_POST["termino"]);
    
    $resultado = ConsultarTodosUsuarios();
    $usuarios = array();

    if($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_array($resultado)) {
            // Filtrar por término de búsqueda
            if(empty($termino) || 
               stripos($fila['Nombre'], $termino) !== false || 
               stripos($fila['CorreoElectronico'], $termino) !== false ||
               stripos($fila['Identificacion'], $termino) !== false) {
                
                $usuarios[] = array(
                    'idUsuario' => $fila['ConsecutivoUsuario'],
                    'cedula' => $fila['Identificacion'],
                    'nombre' => $fila['Nombre'],
                    'correo' => $fila['CorreoElectronico'],
                    'fechaRegistro' => FormatearFechaMostrar($fila['fechaRegistro']),
                    'activo' => $fila['activo'],
                    'tipoSuscripcion' => $fila['TipoSuscripcion'],
                    'idSuscripcion' => $fila['ConsecutivoSuscripcion']
                );
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'usuarios' => $usuarios,
        'total' => count($usuarios)
    ));
    exit;
}

// CONTAR FAVORITOS DEL USUARIO (AJAX)
if(isset($_POST["contarFavoritosUsuario"]))
{
    ValidarSesion();
    
    $idUsuario = $_SESSION["ConsecutivoUsuario"];
    $cantidad = ContarFavoritosUsuario($idUsuario);

    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'cantidad' => $cantidad
    ));
    exit;
}
?>