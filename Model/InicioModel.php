<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UtilesModel.php';

   
    // REGISTRO DE USUARIO
   
    
    // REGISTRO DE USUARIO - VERSIÓN CON DEBUG
   
    function RegistrarUsuario($cedula, $nombre, $correo, $contrasenna)
{
    try {
        // Validar que no exista el correo
        if(ExisteRegistro('usuarioBD', 'correo', $correo)) {
            return array('success' => false, 'mensaje' => 'El correo ya está registrado');
        }

        // Validar que no exista la cédula
        if(ExisteRegistro('usuarioBD', 'cedula', $cedula)) {
            return array('success' => false, 'mensaje' => 'La cédula ya está registrada');
        }

        // Limpiar datos
        $cedula = LimpiarEntrada($cedula);
        $nombre = LimpiarEntrada($nombre);
        $correo = LimpiarEntrada($correo);
        $contrasenna = EncriptarContrasenna($contrasenna);

        // Llamar al Stored Procedure
        $consulta = "CALL CrearCuenta('$cedula', '$nombre', '$correo', '$contrasenna')";
        $resultado = LlamarProcedimiento($consulta);

        if($resultado) {
            return array('success' => true, 'mensaje' => 'Usuario registrado exitosamente');
        } else {
            RegistrarError("Error al registrar usuario: $correo");
            return array('success' => false, 'mensaje' => 'Error al registrar el usuario');
        }
    } catch (Exception $e) {
        RegistrarError("Excepción en RegistrarUsuario: " . $e->getMessage());
        return array('success' => false, 'mensaje' => 'Error en el servidor');
    }
}

    // LOGIN DE USUARIO
   
    
    function IniciarSesion($correo, $contrasenna)
    {
        try {
            // Limpiar datos
            $correo = LimpiarEntrada($correo);
            $contrasenna = EncriptarContrasenna($contrasenna);

            // Llamar al Stored Procedure
            $consulta = "CALL ValidarCuenta('$correo', '$contrasenna')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado && mysqli_num_rows($resultado) > 0) {
                $datos = mysqli_fetch_array($resultado);
                
                // Iniciar sesión
                if(session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION["ConsecutivoUsuario"] = $datos["ConsecutivoUsuario"];
                $_SESSION["Nombre"] = $datos["Nombre"];
                $_SESSION["CorreoElectronico"] = $datos["CorreoElectronico"];
                $_SESSION["ConsecutivoPerfil"] = $datos["ConsecutivoPerfil"];
                $_SESSION["NombrePerfil"] = $datos["NombrePerfil"];
                $_SESSION["TipoSuscripcion"] = $datos["TipoSuscripcion"] ?? 'Gratis';
                $_SESSION["LimiteFavoritos"] = $datos["LimiteFavoritos"] ?? 5;

                return array('success' => true, 'perfil' => $datos["ConsecutivoPerfil"]);
            } else {
                return array('success' => false, 'mensaje' => 'Credenciales incorrectas');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en IniciarSesion: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    
    // VALIDAR CORREO (Para recuperación)
   function ValidarCorreoRecuperacion($correo) 
    {
        try {
            $correo = LimpiarEntrada($correo);

            $consulta = "CALL ValidarCorreo('$correo')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado && mysqli_num_rows($resultado) > 0) {
                return mysqli_fetch_array($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en ValidarCorreoRecuperacion: " . $e->getMessage());
            return null;
        }
    }

    // CREAR TOKEN DE RECUPERACIÓN
    
    function CrearTokenRecuperacion($idUsuario)
    {
        try {
            $token = GenerarToken();
            $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $consulta = "INSERT INTO recuperacionContrasena (idUsuario, token, fechaExpiracion) 
                        VALUES ($idUsuario, '$token', '$fechaExpiracion')";
            
            $resultado = EjecutarSentencia($consulta);

            if($resultado) {
                return $token;
            } else {
                return null;
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en CrearTokenRecuperacion: " . $e->getMessage());
            return null;
        }
    }

 
    // VALIDAR TOKEN DE RECUPERACIÓN
   
    
    function ValidarTokenRecuperacion($token)
    {
        try {
            $token = LimpiarEntrada($token);

            $consulta = "SELECT r.idRecuperacion, r.idUsuario, u.correo, u.nombreUsuario
                        FROM recuperacionContrasena r
                        INNER JOIN usuarioBD u ON r.idUsuario = u.idUsuario
                        WHERE r.token = '$token' 
                        AND r.usado = 0 
                        AND r.fechaExpiracion > NOW()
                        LIMIT 1";
            
            $resultado = EjecutarConsulta($consulta);

            if($resultado && mysqli_num_rows($resultado) > 0) {
                return mysqli_fetch_array($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en ValidarTokenRecuperacion: " . $e->getMessage());
            return null;
        }
    }

   
    // MARCAR TOKEN COMO USADO

    
    function MarcarTokenUsado($idRecuperacion)
    {
        try {
            $consulta = "UPDATE recuperacionContrasena 
                        SET usado = 1 
                        WHERE idRecuperacion = $idRecuperacion";
            
            return EjecutarSentencia($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en MarcarTokenUsado: " . $e->getMessage());
            return false;
        }
    }


    // ACTUALIZAR CONTRASEÑA
 
    function ActualizarContrasenna($idUsuario, $nuevaContrasenna)
    {
        try {
            $nuevaContrasenna = EncriptarContrasenna($nuevaContrasenna);

            $consulta = "CALL ActualizarContrasenna($idUsuario, '$nuevaContrasenna')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Contraseña actualizada exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al actualizar la contraseña');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en ActualizarContrasenna: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

  
    // CAMBIAR CONTRASEÑA (Usuario logueado)
    
    
    function CambiarContrasenna($idUsuario, $contrasenaActual, $nuevaContrasenna)
    {
        try {
            // Verificar contraseña actual
            $contrasenaActual = EncriptarContrasenna($contrasenaActual);
            
            $consulta = "SELECT COUNT(*) as total 
                        FROM usuarioBD 
                        WHERE idUsuario = $idUsuario 
                        AND contrasenna = '$contrasenaActual'";
            
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);

            if($fila['total'] == 0) {
                return array('success' => false, 'mensaje' => 'La contraseña actual es incorrecta');
            }

            // Actualizar contraseña
            return ActualizarContrasenna($idUsuario, $nuevaContrasenna);
        } catch (Exception $e) {
            RegistrarError("Excepción en CambiarContrasenna: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }


    // CERRAR SESIÓN
    
    
    function CerrarSesion()
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        session_unset();
        session_destroy();
        
        header("Location: ../Inicio/IniciarSesion.php");
        exit;
    }


    // CONSULTAR INDICADORES (Dashboard Admin)
  
    
    function ConsultarIndicadores()
    {
        try {
            $consulta = "CALL ConsultarIndicadores()";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en ConsultarIndicadores: " . $e->getMessage());
            return null;
        }
    }

    

   
    // VALIDAR SI ES ADMINISTRADOR
    
    
    function ValidarAdmin()
    {
        ValidarSesion();
        
        if($_SESSION["ConsecutivoPerfil"] != 1) {
            header("Location: ../Cliente/Catalogo.php");
            exit;
        }
    }

    // VALIDAR SI ES CLIENTE
 
    
    function ValidarCliente()
    {
        ValidarSesion();
        
        if($_SESSION["ConsecutivoPerfil"] == 1) {
            header("Location: ../Admin/Dashboard.php");
            exit;
        }
    }

?>
