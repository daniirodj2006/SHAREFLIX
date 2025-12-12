<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UtilesModel.php';

// CONSULTAR USUARIO POR ID
function ConsultarUsuarioPorId($idUsuario)
{
    try {
        $consulta = "CALL ConsultarUsuario($idUsuario)";
        $resultado = LlamarProcedimiento($consulta);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            return mysqli_fetch_array($resultado);
        } else {
            return null;
        }
    } catch (Exception $e) {
        RegistrarError("Excepción en ConsultarUsuarioPorId: " . $e->getMessage());
        return null;
    }
}

// ACTUALIZAR PERFIL DE USUARIO
function ActualizarPerfilUsuario($idUsuario, $cedula, $nombre, $correo)
{
    try {
        // Validación de correo único
        $consulta = "CALL ValidarCorreoUnicoPerfil('$correo', $idUsuario)";
        $resultado = LlamarProcedimiento($consulta);
        $fila = mysqli_fetch_assoc($resultado);

        if ($fila['total'] > 0) {
            return array('success' => false, 'mensaje' => 'El correo ya está en uso por otro usuario');
        }

        // Validación de cédula única
        $consulta = "CALL ValidarCedulaUnicaPerfil('$cedula', $idUsuario)";
        $resultado = LlamarProcedimiento($consulta);
        $fila = mysqli_fetch_assoc($resultado);

        if ($fila['total'] > 0) {
            return array('success' => false, 'mensaje' => 'La cédula ya está en uso por otro usuario');
        }

        // Limpiar datos
        $cedula = LimpiarEntrada($cedula);
        $nombre = LimpiarEntrada($nombre);
        $correo = LimpiarEntrada($correo);

        // Actualizar perfil
        $consulta = "CALL ActualizarPerfil($idUsuario, '$cedula', '$nombre', '$correo')";
        $resultado = LlamarProcedimiento($consulta);

        if ($resultado) {
            if (session_status() === PHP_SESSION_NONE) session_start();

            $_SESSION["Nombre"] = $nombre;
            $_SESSION["CorreoElectronico"] = $correo;

            return array('success' => true, 'mensaje' => 'Perfil actualizado exitosamente');
        }

        return array('success' => false, 'mensaje' => 'Error al actualizar el perfil');
    } catch (Exception $e) {
        RegistrarError("Excepción en ActualizarPerfilUsuario: " . $e->getMessage());
        return array('success' => false, 'mensaje' => 'Error en el servidor');
    }
}

// ACTUALIZAR USUARIO DESDE ADMIN (puede cambiar contraseña)

// Revisar si este procedimiento funciona!!!!!!!!!

function ActualizarUsuarioAdmin($idUsuario, $cedula, $nombre, $correo, $contrasenna = '')
{
    try {
        // Validar correo único
        $consulta = "CALL ValidarCorreoUnicoPerfil('$correo', $idUsuario)";
        $resultado = LlamarProcedimiento($consulta);
        $fila = mysqli_fetch_assoc($resultado);

        if ($fila['total'] > 0) {
            return array('success' => false, 'mensaje' => 'El correo ya está en uso por otro usuario');
        }

        // Validar cédula única
        $consulta = "CALL ValidarCedulaUnicaPerfil('$cedula', $idUsuario)";
        $resultado = LlamarProcedimiento($consulta);
        $fila = mysqli_fetch_assoc($resultado);

        if ($fila['total'] > 0) {
            return array('success' => false, 'mensaje' => 'La cédula ya está en uso por otro usuario');
        }

        // Limpiar datos
        $cedula = LimpiarEntrada($cedula);
        $nombre = LimpiarEntrada($nombre);
        $correo = LimpiarEntrada($correo);
        $contrasenna = LimpiarEntrada($contrasenna);

        // Decidir qué SP usar
        if ($contrasenna !== '') {
            // Encriptar contraseña nueva
            $contrasenna = EncriptarContrasenna($contrasenna);

            $consulta = "CALL ActualizarPerfilContrasenna($idUsuario, '$cedula', '$nombre', '$correo', '$contrasenna')";
        } else {
            $consulta = "CALL ActualizarPerfil($idUsuario, '$cedula', '$nombre', '$correo')";
        }

        // Ejecutar SP
        $resultado = LlamarProcedimiento($consulta);

        if ($resultado) {
            // Actualizar sesión
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION["Nombre"] = $nombre;
            $_SESSION["CorreoElectronico"] = $correo;

            return array('success' => true, 'mensaje' => 'Perfil actualizado exitosamente');
        }

        return array('success' => false, 'mensaje' => 'Error al actualizar el perfil');
    } catch (Exception $e) {
        RegistrarError("Excepción en ActualizarPerfilUsuario: " . $e->getMessage());
        return array('success' => false, 'mensaje' => 'Error en el servidor');
    }
}

// CONSULTAR TODOS LOS USUARIOS (Solo Admin)
function ConsultarTodosUsuarios()
{
    try {
        $consulta = "CALL ConsultarUsuarios()";
        return LlamarProcedimiento($consulta);
    } catch (Exception $e) {
        RegistrarError("Excepción en ConsultarTodosUsuarios: " . $e->getMessage());
        return null;
    }
}

// CAMBIAR ESTADO DE USUARIO (Activar/Inactivar)
function CambiarEstadoUsuario($idUsuario)
{
    try {
        $consulta = "CALL CambiarEstadoUsuario($idUsuario)";
        $resultado = EjecutarSentencia($consulta);

        if ($resultado) {
            return array('success' => true, 'mensaje' => 'Estado actualizado exitosamente');
        } else {
            return array('success' => false, 'mensaje' => 'Error al cambiar estado');
        }
    } catch (Exception $e) {
        RegistrarError("Excepción en CambiarEstadoUsuario: " . $e->getMessage());
        return array('success' => false, 'mensaje' => 'Error en el servidor');
    }
}

// CONSULTAR SUSCRIPCIONES DISPONIBLES
function ConsultarSuscripciones()
{
    try {
        $consulta = "CALL ConsultarSuscripciones()";
        return LlamarProcedimiento($consulta);
    } catch (Exception $e) {
        RegistrarError("Excepción en ConsultarSuscripciones: " . $e->getMessage());
        return null;
    }
}

// CAMBIAR SUSCRIPCIÓN DE USUARIO
function CambiarSuscripcionUsuario($idUsuario, $idSuscripcionNueva)
{
    try {
        $consulta = "CALL CambiarSuscripcion($idUsuario, $idSuscripcionNueva)";
        $resultado = LlamarProcedimiento($consulta);

        if ($resultado) {
            return array('success' => true, 'mensaje' => 'Suscripción actualizada exitosamente');
        } else {
            return array('success' => false, 'mensaje' => 'Error al cambiar suscripción');
        }
    } catch (Exception $e) {
        RegistrarError("Excepción en CambiarSuscripcionUsuario: " . $e->getMessage());
        return array('success' => false, 'mensaje' => 'Error en el servidor');
    }
}

// OBTENER SUSCRIPCIÓN ACTUAL DEL USUARIO
function ObtenerSuscripcionActual($idUsuario)
{
    try {
        $consulta = "CALL ObtenerSuscripcionActual($idUsuario)";
        $resultado = EjecutarConsulta($consulta);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            return mysqli_fetch_array($resultado);
        } else {
            return null;
        }
    } catch (Exception $e) {
        RegistrarError("Excepción en ObtenerSuscripcionActual: " . $e->getMessage());
        return null;
    }
}

// VERIFICAR SI USUARIO ES PREMIUM
function EsUsuarioPremium($idUsuario)
{
    try {
        $suscripcion = ObtenerSuscripcionActual($idUsuario);

        if ($suscripcion && $suscripcion['tipoSuscripcion'] == 'Premium') {
            return true;
        }

        return false;
    } catch (Exception $e) {
        RegistrarError("Excepción en EsUsuarioPremium: " . $e->getMessage());
        return false;
    }
}

// CONTAR FAVORITOS DEL USUARIO
function ContarFavoritosUsuario($idUsuario)
{
    try {
        $consulta = "CALL ContarFavoritosUsuario($idUsuario)";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);

        return $fila['total'];
    } catch (Exception $e) {
        RegistrarError("Excepción en ContarFavoritosUsuario: " . $e->getMessage());
        return 0;
    }
}


// VERIFICAR LÍMITE DE FAVORITOS
function VerificarLimiteFavoritos($idUsuario)
{
    try {
        $suscripcion = ObtenerSuscripcionActual($idUsuario);
        $cantidadFavoritos = ContarFavoritosUsuario($idUsuario);

        $limite = $suscripcion['limiteFavoritos'] ?? 5;

        return array(
            'limite' => $limite,
            'actual' => $cantidadFavoritos,
            'disponible' => $limite - $cantidadFavoritos,
            'puedeAgregar' => $cantidadFavoritos < $limite
        );
    } catch (Exception $e) {
        RegistrarError("Excepción en VerificarLimiteFavoritos: " . $e->getMessage());
        return array(
            'limite' => 5,
            'actual' => 0,
            'disponible' => 5,
            'puedeAgregar' => true
        );
    }
}

// OBTENER ESTADÍSTICAS DEL USUARIO
function ObtenerEstadisticasUsuario($idUsuario)
{
    try {
        $estadisticas = array();

        // Contar favoritos
        $consulta = "CALL ContarFavoritosUsuario($idUsuario)";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);
        $estadisticas['totalFavoritos'] = $fila['total'];

        // Obtener suscripción
        $suscripcion = ObtenerSuscripcionActual($idUsuario);
        $estadisticas['tipoSuscripcion'] = $suscripcion['tipoSuscripcion'] ?? 'Gratis';
        $estadisticas['limiteFavoritos'] = $suscripcion['limiteFavoritos'] ?? 5;
        $estadisticas['fechaVencimiento'] = $suscripcion['fechaVencimiento'] ?? null;

        // Calcular días restantes de suscripción
        if ($estadisticas['fechaVencimiento']) {
            $hoy = new DateTime();
            $vencimiento = new DateTime($estadisticas['fechaVencimiento']);
            $diferencia = $hoy->diff($vencimiento);
            $estadisticas['diasRestantes'] = $diferencia->days;
        } else {
            $estadisticas['diasRestantes'] = 0;
        }

        return $estadisticas;
    } catch (Exception $e) {
        RegistrarError("Excepción en ObtenerEstadisticasUsuario: " . $e->getMessage());
        return null;
    }
}

// ELIMINAR USUARIO (Solo Admin - Soft Delete)
function EliminarUsuario($idUsuario)
{
    try {
        // No eliminar, solo desactivar
        $consulta = "CALL EliminarUsuario($idUsuario)";
        $resultado = EjecutarSentencia($consulta);

        if ($resultado) {
            return array('success' => true, 'mensaje' => 'Usuario desactivado exitosamente');
        } else {
            return array('success' => false, 'mensaje' => 'Error al desactivar usuario');
        }
    } catch (Exception $e) {
        RegistrarError("Excepción en EliminarUsuario: " . $e->getMessage());
        return array('success' => false, 'mensaje' => 'Error en el servidor');
    }
}

/*
===================================================
==== FUNCIONES PARA ESTADÍSTICAS DEL DASHBOARD ====
===================================================
*/

// CONTAR TOTAL DE USUARIOS
function ContarTotalUsuarios()
{
    try {
        $consulta = "CALL ContarTotalUsuarios()";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);
        return $fila['total'];
    } catch (Exception $e) {
        RegistrarError("Excepción en ContarTotalUsuarios: " . $e->getMessage());
        return 0;
    }
}

// CONTAR USUARIOS PREMIUM
function ContarUsuariosPremium()
{
    try {
        $consulta = "CALL ContarUsuariosPremium()";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);
        return $fila['total'];
    } catch (Exception $e) {
        RegistrarError("Excepción en ContarUsuariosPremium: " . $e->getMessage());
        return 0;
    }
}

// CONTAR USUARIOS GRATIS
function ContarUsuariosGratis()
{
    try {
        $consulta = "CALL ContarUsuariosGratis()";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);
        return $fila['total'];
    } catch (Exception $e) {
        RegistrarError("Excepción en ContarUsuariosGratis: " . $e->getMessage());
        return 0;
    }
}

// CONTAR USUARIOS ACTIVOS HOY
function ContarUsuariosActivosHoy()
{
    try {
        $consulta = "CALL ContarUsuariosActivosHoy()";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);
        return $fila['total'];
    } catch (Exception $e) {
        RegistrarError("Excepción en ContarUsuariosActivosHoy: " . $e->getMessage());
        return 0;
    }
}

// CALCULAR CRECIMIENTO DE USUARIOS
function CalcularCrecimientoUsuarios()
{
    try {
        // Usuarios de hoy
        $consulta = "CALL ContarUsuariosRegistradosHoy()";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);
        $hoy = $fila['hoy'];

        // Usuarios de ayer
        $consulta = "CALL ContarUsuariosRegistradosAyer()";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);
        $ayer = $fila['ayer'];

        if ($ayer > 0) {
            $crecimiento = (($hoy - $ayer) / $ayer) * 100;
            return round($crecimiento, 1);
        } else {
            return $hoy > 0 ? 100 : 0;
        }
    } catch (Exception $e) {
        RegistrarError("Excepción en CalcularCrecimientoUsuarios: " . $e->getMessage());
        return 0;
    }
}

// OBTENER USUARIOS RECIENTES
function ObtenerUsuariosRecientes($limite)
{
    try {
        $consulta = "CALL ObtenerUsuariosRecientes($limite)";
        $resultado = EjecutarConsulta($consulta);
        $usuarios = array();

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $usuarios[] = array(
                    'nombre' => $fila['Nombre'],
                    'email' => $fila['CorreoElectronico'],
                    'fechaRegistro' => $fila['fechaRegistro'],
                    'rol' => $fila['rol'] ?? 'Gratis',
                    'ConsecutivoPerfil' => $fila['ConsecutivoPerfil']
                );
            }
        }

        return $usuarios;
    } catch (Exception $e) {
        RegistrarError("Excepción en ObtenerUsuariosRecientes: " . $e->getMessage());
        return array();
    }
}

// OBTENER DATOS DE ACTIVIDAD DE USUARIOS (para gráfico)
function ObtenerDatosActividadUsuarios($periodo)
{
    try {
        $consulta = "CALL ObtenerDatosActividadUsuarios($periodo)";
        $resultado = EjecutarConsulta($consulta);
        $datos = array();

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $datos[] = array(
                    'fecha' => $fila['fecha'],
                    'cantidad' => $fila['cantidad']
                );
            }
        }

        return $datos;
    } catch (Exception $e) {
        RegistrarError("Excepción en ObtenerDatosActividadUsuarios: " . $e->getMessage());
        return array();
    }
}
