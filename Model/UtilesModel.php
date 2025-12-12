<?php
// Conexión a la base de datos

function Conectar()
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    return mysqli_connect("127.0.0.1", "root", "", "shareflix_bd", 3307);
}

function Desconectar($context)
{
    mysqli_close($context);
}

function SaveError($error)
{
    $context = Desconectar();

    $mensaje = mysqli_real_escape_string($context, $error->getMessage());

    $sentencia = "CALL RegistrarError('$mensaje')";
    $context->query($sentencia);

    CloseConnection($context);
}

// Ejecutar consulta que devuelve resultados
function EjecutarConsulta($consulta)
{
    $enlace = Conectar();
    $resultado = mysqli_query($enlace, $consulta);
    Desconectar($enlace);
    return $resultado;
}

// Ejecutar consulta sin retorno (INSERT, UPDATE, DELETE)
function EjecutarSentencia($sentencia)
{
    $enlace = Conectar();
    $resultado = mysqli_query($enlace, $sentencia);
    Desconectar($enlace);
    return $resultado;
}

// Llamar a un Stored Procedure
function LlamarProcedimiento($procedimiento)
{
    $enlace = Conectar();
    $resultado = mysqli_query($enlace, $procedimiento);
    Desconectar($enlace);
    return $resultado;
}

// Obtener el último ID insertado
function ObtenerUltimoId($enlace)
{
    return mysqli_insert_id($enlace);
}

// Registrar errores en la base de datos
function RegistrarError($mensaje)
{
    try {
        $enlace = Conectar();
        $mensaje = mysqli_real_escape_string($enlace, $mensaje);
        $consulta = "CALL RegistrarError('$mensaje')";
        mysqli_query($enlace, $consulta);
        Desconectar($enlace);
    } catch (Exception $e) {
        error_log("Error al registrar error en BD: " . $e->getMessage());
    }
}

// Limpiar entrada de datos (prevenir inyección SQL)
function LimpiarEntrada($dato)
{
    $enlace = Conectar();
    $datoLimpio = mysqli_real_escape_string($enlace, trim($dato));
    Desconectar($enlace);
    return $datoLimpio;
}

// Validar si existe un registro
function ExisteRegistro($tabla, $campo, $valor)
{
    $enlace = Conectar();
    $valor = mysqli_real_escape_string($enlace, $valor);
    $consulta = "SELECT COUNT(*) as total FROM $tabla WHERE $campo = '$valor'";
    $resultado = mysqli_query($enlace, $consulta);
    $fila = mysqli_fetch_assoc($resultado);
    Desconectar($enlace);

    return $fila['total'] > 0;
}

// Encriptar contraseña (simple - en producción usar password_hash)
function EncriptarContrasenna($contrasenna)
{
    // Por ahora usamos texto plano, pero deberías usar:
    // return password_hash($contrasenna, PASSWORD_DEFAULT);
    return $contrasenna;
}

// Verificar contraseña (simple - en producción usar password_verify)
function VerificarContrasenna($contrasenna, $hash)
{
    // Por ahora comparación directa, pero deberías usar:
    // return password_verify($contrasenna, $hash);
    return $contrasenna === $hash;
}

// Generar token aleatorio (para recuperación de contraseña)
function GenerarToken($longitud = 32)
{
    return bin2hex(random_bytes($longitud));
}

// Formatear fecha para MySQL
function FormatearFechaMySQL($fecha)
{
    return date('Y-m-d H:i:s', strtotime($fecha));
}

// Formatear fecha para mostrar
function FormatearFechaMostrar($fecha)
{
    return date('d/m/Y', strtotime($fecha));
}

// Obtener extensión de archivo
function ObtenerExtension($nombreArchivo)
{
    return strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
}

// Validar extensión de imagen
function ValidarExtensionImagen($nombreArchivo)
{
    $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    $extension = ObtenerExtension($nombreArchivo);
    return in_array($extension, $extensionesPermitidas);
}

// Validar tamaño de archivo (en bytes)
function ValidarTamanoArchivo($tamano, $maxTamano = 5242880) // 5MB por defecto
{
    return $tamano <= $maxTamano;
}

// Generar nombre único para archivo
function GenerarNombreUnico($extension)
{
    return uniqid() . '_' . time() . '.' . $extension;
}

function CrearDirectorio($ruta)
{
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }
}


function EnviarRespuestaJSON($exito, $mensaje, $datos = null)
{
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $exito,
        'mensaje' => $mensaje,
        'datos' => $datos
    ]);
    exit;
}
