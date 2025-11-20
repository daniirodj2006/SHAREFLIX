<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UtilesModel.php';


    // CONSULTAR USUARIO POR ID
    
    
    function ConsultarUsuarioPorId($idUsuario)
    {
        try {
            $consulta = "CALL ConsultarUsuario($idUsuario)";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado && mysqli_num_rows($resultado) > 0) {
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
            // Validar que el correo no esté usado por otro usuario
            $consulta = "SELECT COUNT(*) as total FROM usuarioBD 
                        WHERE correo = '$correo' AND idUsuario != $idUsuario";
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);

            if($fila['total'] > 0) {
                return array('success' => false, 'mensaje' => 'El correo ya está en uso por otro usuario');
            }

            // Validar que la cédula no esté usada por otro usuario
            $consulta = "SELECT COUNT(*) as total FROM usuarioBD 
                        WHERE cedula = '$cedula' AND idUsuario != $idUsuario";
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);

            if($fila['total'] > 0) {
                return array('success' => false, 'mensaje' => 'La cédula ya está en uso por otro usuario');
            }

            // Limpiar datos
            $cedula = LimpiarEntrada($cedula);
            $nombre = LimpiarEntrada($nombre);
            $correo = LimpiarEntrada($correo);

            // Llamar al SP
            $consulta = "CALL ActualizarPerfil($idUsuario, '$cedula', '$nombre', '$correo')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                // Actualizar sesión
                if(session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION["Nombre"] = $nombre;
                $_SESSION["CorreoElectronico"] = $correo;

                return array('success' => true, 'mensaje' => 'Perfil actualizado exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al actualizar el perfil');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en ActualizarPerfilUsuario: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

   // ACTUALIZAR USUARIO DESDE ADMIN (puede cambiar contraseña)
function ActualizarUsuarioAdmin($idUsuario, $cedula, $nombre, $correo, $contrasenna = '')
{
    try {
        // Validar que el correo no esté usado por otro usuario
        $consulta = "SELECT COUNT(*) as total FROM usuarioBD 
                    WHERE correo = '$correo' AND idUsuario != $idUsuario";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);

        if($fila['total'] > 0) {
            return array('success' => false, 'mensaje' => 'El correo ya está en uso por otro usuario');
        }

        // Validar que la cédula no esté usada por otro usuario
        $consulta = "SELECT COUNT(*) as total FROM usuarioBD 
                    WHERE cedula = '$cedula' AND idUsuario != $idUsuario";
        $resultado = EjecutarConsulta($consulta);
        $fila = mysqli_fetch_assoc($resultado);

        if($fila['total'] > 0) {
            return array('success' => false, 'mensaje' => 'La cédula ya está en uso por otro usuario');
        }

        // Limpiar datos
        $cedula = LimpiarEntrada($cedula);
        $nombre = LimpiarEntrada($nombre);
        $correo = LimpiarEntrada($correo);
        $contrasenna = LimpiarEntrada($contrasenna);

        // Llamar al SP
        $consulta = "CALL ActualizarUsuario($idUsuario, '$cedula', '$nombre', '$correo', '$contrasenna')";
        $resultado = LlamarProcedimiento($consulta);

        if($resultado && mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_array($resultado);
            return array('success' => true, 'mensaje' => $fila['mensaje']);
        }
        
        return array('success' => false, 'mensaje' => 'Error al actualizar usuario');
    } catch (Exception $e) {
        RegistrarError("Excepción en ActualizarUsuarioAdmin: " . $e->getMessage());
        return array('success' => false, 'mensaje' => 'Error en el servidor: ' . $e->getMessage());
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
            $consulta = "UPDATE usuarioBD 
                        SET activo = NOT activo 
                        WHERE idUsuario = $idUsuario";
            
            $resultado = EjecutarSentencia($consulta);

            if($resultado) {
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

            if($resultado) {
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
            $consulta = "SELECT s.idSuscripcion, s.tipoSuscripcion, s.limiteFavoritos,
                        us.fechaInicio, us.fechaVencimiento, us.estado
                        FROM usuarioSuscripcion us
                        INNER JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
                        WHERE us.idUsuario = $idUsuario AND us.estado = 'Activa'
                        LIMIT 1";
            
            $resultado = EjecutarConsulta($consulta);

            if($resultado && mysqli_num_rows($resultado) > 0) {
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
            
            if($suscripcion && $suscripcion['tipoSuscripcion'] == 'Premium') {
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
            $consulta = "SELECT COUNT(*) as total FROM favoritos WHERE idUsuario = $idUsuario";
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
            $consulta = "SELECT COUNT(*) as total FROM favoritos WHERE idUsuario = $idUsuario";
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);
            $estadisticas['totalFavoritos'] = $fila['total'];

            // Obtener suscripción
            $suscripcion = ObtenerSuscripcionActual($idUsuario);
            $estadisticas['tipoSuscripcion'] = $suscripcion['tipoSuscripcion'] ?? 'Gratis';
            $estadisticas['limiteFavoritos'] = $suscripcion['limiteFavoritos'] ?? 5;
            $estadisticas['fechaVencimiento'] = $suscripcion['fechaVencimiento'] ?? null;

            // Calcular días restantes de suscripción
            if($estadisticas['fechaVencimiento']) {
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
            $consulta = "UPDATE usuarioBD SET activo = 0 WHERE idUsuario = $idUsuario";
            $resultado = EjecutarSentencia($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Usuario desactivado exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al desactivar usuario');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en EliminarUsuario: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ============================================
    // FUNCIONES PARA ESTADÍSTICAS DEL DASHBOARD
    // ============================================

    // CONTAR TOTAL DE USUARIOS
    function ContarTotalUsuarios()
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM usuarioBD";
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
            $consulta = "SELECT COUNT(DISTINCT us.idUsuario) as total 
                        FROM usuarioSuscripcion us
                        INNER JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
                        WHERE us.estado = 'Activa' AND s.tipoSuscripcion = 'Premium'";
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
            $consulta = "SELECT COUNT(DISTINCT us.idUsuario) as total 
                        FROM usuarioSuscripcion us
                        INNER JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
                        WHERE us.estado = 'Activa' AND s.tipoSuscripcion = 'Gratis'";
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
            $consulta = "SELECT COUNT(*) as total FROM usuarioBD WHERE activo = 1";
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
            $consulta = "SELECT COUNT(*) as hoy FROM usuarioBD 
                        WHERE DATE(fechaRegistro) = CURDATE()";
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);
            $hoy = $fila['hoy'];

            // Usuarios de ayer
            $consulta = "SELECT COUNT(*) as ayer FROM usuarioBD 
                        WHERE DATE(fechaRegistro) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);
            $ayer = $fila['ayer'];

            if($ayer > 0) {
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
            $consulta = "SELECT u.idUsuario as ConsecutivoUsuario, u.nombre as Nombre, 
                        u.correo as CorreoElectronico, u.idPerfil as ConsecutivoPerfil,
                        DATE_FORMAT(u.fechaRegistro, '%d/%m/%Y') as fechaRegistro,
                        s.tipoSuscripcion as rol
                        FROM usuarioBD u
                        LEFT JOIN usuarioSuscripcion us ON u.idUsuario = us.idUsuario AND us.estado = 'Activa'
                        LEFT JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
                        ORDER BY u.fechaRegistro DESC
                        LIMIT $limite";
            
            $resultado = EjecutarConsulta($consulta);
            $usuarios = array();

            if($resultado && mysqli_num_rows($resultado) > 0) {
                while($fila = mysqli_fetch_assoc($resultado)) {
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
            $consulta = "SELECT DATE(fechaRegistro) as fecha, COUNT(*) as cantidad
                        FROM usuarioBD
                        WHERE fechaRegistro >= DATE_SUB(CURDATE(), INTERVAL $periodo DAY)
                        GROUP BY DATE(fechaRegistro)
                        ORDER BY fecha ASC";
            
            $resultado = EjecutarConsulta($consulta);
            $datos = array();

            if($resultado && mysqli_num_rows($resultado) > 0) {
                while($fila = mysqli_fetch_assoc($resultado)) {
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
?>
