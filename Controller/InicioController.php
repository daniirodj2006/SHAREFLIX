<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/InicioModel.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/UtilesController.php';

 
    // PROCESAR REGISTRO DE USUARIO
    
    
    if(isset($_POST["btnRegistrar"]))
    {
        $cedula = SanitizarEntrada($_POST["txtCedula"]);
        $nombre = SanitizarEntrada($_POST["txtNombre"]);
        $correo = SanitizarEntrada($_POST["txtCorreo"]);
        $contrasenna = $_POST["txtContrasenna"];
        $confirmarContrasenna = $_POST["txtConfirmarContrasenna"];

        // Validar que las contraseñas coincidan
        if($contrasenna !== $confirmarContrasenna) {
            $_POST["Mensaje"] = "Las contraseñas no coinciden";
            $_POST["TipoMensaje"] = "error";
            return;
        }

        // Validar formato de email
        if(!ValidarEmail($correo)) {
            $_POST["Mensaje"] = "El formato del correo electrónico no es válido";
            $_POST["TipoMensaje"] = "error";
            return;
        }

        // Registrar usuario
        $resultado = RegistrarUsuario($cedula, $nombre, $correo, $contrasenna);

        if($resultado['success']) {
            // Enviar correo de bienvenida (opcional, no bloquea registro)
           // EnviarCorreoBienvenida($correo, $nombre);
            
            $_POST["Mensaje"] = "Cuenta creada exitosamente. Ya puedes iniciar sesión.";
            $_POST["TipoMensaje"] = "success";
            
            // Redirigir al login después de 2 segundos
            header("refresh:2;url=IniciarSesion.php");
        } else {
            $_POST["Mensaje"] = $resultado['mensaje'];
            $_POST["TipoMensaje"] = "error";
        }
    }

    // PROCESAR LOGIN
// PROCESAR LOGIN
if(isset($_POST["btnIniciarSesion"]))
{
    $correo = SanitizarEntrada($_POST["txtCorreo"]);
    $contrasenna = $_POST["txtContrasenna"];

    $resultado = IniciarSesion($correo, $contrasenna);

    // ✅ Verificar si el login fue exitoso revisando las sesiones
    if(isset($_SESSION["ConsecutivoUsuario"]) && !empty($_SESSION["ConsecutivoUsuario"])) {
        // ✅ Login exitoso, redirigir
        RedireccionarSegunRol($_SESSION["ConsecutivoPerfil"]);
        exit; // Asegurar que se detenga aquí
    } else {
        // ❌ Login fallido, mostrar mensaje
        $_POST["Mensaje"] = isset($resultado['mensaje']) ? $resultado['mensaje'] : 'Credenciales incorrectas';
        $_POST["TipoMensaje"] = "error";
    }
}

 
    // PROCESAR SOLICITUD DE RECUPERACIÓN
    
    
    if(isset($_POST["btnRecuperar"]))
    {
        $correo = SanitizarEntrada($_POST["txtCorreo"]);

        // Validar que el correo exista
        $usuario = ValidarCorreoRecuperacion($correo);

        if($usuario) {
            // Crear token de recuperación
            $token = CrearTokenRecuperacion($usuario['ConsecutivoUsuario']);

            if($token) {
                // Enviar correo con el enlace
                $resultado = EnviarCorreoRecuperacion($correo, $usuario['Nombre'], $token);

                if($resultado['success']) {
                    $_POST["Mensaje"] = "Se ha enviado un correo con las instrucciones para recuperar tu contraseña.";
                    $_POST["TipoMensaje"] = "success";
                } else {
                    $_POST["Mensaje"] = "Error al enviar el correo. Intenta nuevamente.";
                    $_POST["TipoMensaje"] = "error";
                }
            } else {
                $_POST["Mensaje"] = "Error al generar el token. Intenta nuevamente.";
                $_POST["TipoMensaje"] = "error";
            }
        } else {
            $_POST["Mensaje"] = "El correo electrónico no está registrado en el sistema.";
            $_POST["TipoMensaje"] = "error";
        }
    }

  
    // PROCESAR CAMBIO DE CONTRASEÑA CON TOKEN
 
    
    if(isset($_POST["btnCambiarContrasenna"]))
    {
        $token = SanitizarEntrada($_POST["token"]);
        $nuevaContrasenna = $_POST["txtNuevaContrasenna"];
        $confirmarContrasenna = $_POST["txtConfirmarContrasenna"];

        // Validar que las contraseñas coincidan
        if($nuevaContrasenna !== $confirmarContrasenna) {
            $_POST["Mensaje"] = "Las contraseñas no coinciden";
            $_POST["TipoMensaje"] = "error";
            return;
        }

        // Validar el token
        $datosRecuperacion = ValidarTokenRecuperacion($token);

        if($datosRecuperacion) {
            // Actualizar contraseña
            $resultado = ActualizarContrasenna($datosRecuperacion['idUsuario'], $nuevaContrasenna);

            if($resultado['success']) {
                // Marcar token como usado
                MarcarTokenUsado($datosRecuperacion['idRecuperacion']);

                $_POST["Mensaje"] = "Contraseña actualizada exitosamente. Ya puedes iniciar sesión.";
                $_POST["TipoMensaje"] = "success";
                
                // Redirigir al login después de 2 segundos
                header("refresh:2;url=IniciarSesion.php");
            } else {
                $_POST["Mensaje"] = $resultado['mensaje'];
                $_POST["TipoMensaje"] = "error";
            }
        } else {
            $_POST["Mensaje"] = "El enlace de recuperación es inválido o ha expirado. Solicita uno nuevo.";
            $_POST["TipoMensaje"] = "error";
        }
    }

    
    // PROCESAR CAMBIO DE CONTRASEÑA (Usuario logueado)
    
    
    if(isset($_POST["btnCambiarContrasennaUsuario"]))
    {
        ValidarSesion();
        
        $idUsuario = $_SESSION["ConsecutivoUsuario"];
        $contrasenaActual = $_POST["txtContrasenaActual"];
        $nuevaContrasenna = $_POST["txtNuevaContrasenna"];
        $confirmarContrasenna = $_POST["txtConfirmarContrasenna"];

        // Validar que las contraseñas coincidan
        if($nuevaContrasenna !== $confirmarContrasenna) {
            $_POST["Mensaje"] = "Las contraseñas nuevas no coinciden";
            $_POST["TipoMensaje"] = "error";
            return;
        }

        // Validar que la nueva contraseña sea diferente a la actual
        if($contrasenaActual === $nuevaContrasenna) {
            $_POST["Mensaje"] = "La nueva contraseña debe ser diferente a la actual";
            $_POST["TipoMensaje"] = "error";
            return;
        }

        // Cambiar contraseña
        $resultado = CambiarContrasenna($idUsuario, $contrasenaActual, $nuevaContrasenna);

        if($resultado['success']) {
            $_POST["Mensaje"] = "Contraseña actualizada exitosamente";
            $_POST["TipoMensaje"] = "success";
        } else {
            $_POST["Mensaje"] = $resultado['mensaje'];
            $_POST["TipoMensaje"] = "error";
        }
    }

  
    // CERRAR SESIÓN
   
    
    if(isset($_POST["btnCerrarSesion"]) || isset($_GET["cerrarSesion"]))
    {
        CerrarSesion();
    }

    // CONSULTAR API DE CÉDULA (AJAX)

    if(isset($_POST["consultarCedula"]))
    {
        $cedula = SanitizarEntrada($_POST["cedula"]);
        $resultado = ConsultarAPICedula($cedula);
        
        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

 
    // OBTENER INDICADORES PARA DASHBOARD (AJAX)
 
    
    if(isset($_POST["consultarIndicadores"]))
    {
        ValidarSesionAdmin();
        
        $resultado = ConsultarIndicadores();
        
        if($resultado && mysqli_num_rows($resultado) > 0) {
            $datos = mysqli_fetch_array($resultado);
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => true,
                'totalUsuarios' => $datos['totalUsuarios'],
                'totalContenido' => $datos['totalContenido'],
                'totalPremium' => $datos['totalPremium'],
                'totalFavoritos' => $datos['totalFavoritos']
            ));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => false,
                'mensaje' => 'Error al consultar indicadores'
            ));
        }
        exit;
    }

    // VALIDAR DISPONIBILIDAD DE CORREO (AJAX)
   
    
    if(isset($_POST["validarCorreo"]))
    {
        $correo = SanitizarEntrada($_POST["correo"]);
        
        $existe = ExisteRegistro('usuarioBD', 'correo', $correo);
        
        header('Content-Type: application/json');
        echo json_encode(array(
            'disponible' => !$existe,
            'mensaje' => $existe ? 'Este correo ya está registrado' : 'Correo disponible'
        ));
        exit;
    }

   
    // VALIDAR DISPONIBILIDAD DE CÉDULA (AJAX)
  
    
    if(isset($_POST["validarCedulaRegistro"]))
    {
        $cedula = SanitizarEntrada($_POST["cedula"]);
        
        $existe = ExisteRegistro('usuarioBD', 'cedula', $cedula);
        
        header('Content-Type: application/json');
        echo json_encode(array(
            'disponible' => !$existe,
            'mensaje' => $existe ? 'Esta cédula ya está registrada' : 'Cédula disponible'
        ));
        exit;
    }

    // VERIFICAR FUERZA DE CONTRASEÑA (AJAX)
 
    
    if(isset($_POST["verificarContrasena"]))
    {
        $contrasena = $_POST["contrasena"];
        
        $resultado = ValidarFortalezaContrasena($contrasena);
        
        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }
?>
