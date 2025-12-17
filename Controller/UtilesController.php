<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/PHPMailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// CARGAR TEMPLATE DE EMAIL
function CargarTemplateEmail($nombreTemplate, $variables = array())
{
    $rutaTemplate = $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/email_templates/' . $nombreTemplate . '.php';

    if (!file_exists($rutaTemplate)) {
        return null;
    }

    $contenido = file_get_contents($rutaTemplate);

    foreach ($variables as $clave => $valor) {
        $contenido = str_replace('{{' . $clave . '}}', $valor, $contenido);
    }

    return $contenido;
}

// ENVIAR EMAIL CON PHPMAILER
function EnviarCorreo($destinatario, $asunto, $mensaje)
{
    $correoSalida = "drodriguez90518@ufide.ac.cr"; // Correo Institucional personal
    $contrasennaSalida = ""; // Contraseña del correo

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    try {
        // CONFIG SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $correoSalida;
        $mail->Password   = $contrasennaSalida;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // HEADER
        $mail->setFrom($correoSalida, 'Shareflix');
        $mail->addAddress($destinatario);

        // CONTENIDO
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;

        $mail->send();

        return array('success' => true, 'mensaje' => 'Correo enviado exitosamente');
    } catch (Exception $e) {
        return array(
            'success' => false,
            'mensaje' => "Error al enviar correo: " . $mail->ErrorInfo
        );
    }
}

// ENVIAR CORREO DE RECUPERACIÓN DE CONTRASEÑA
function EnviarCorreoRecuperacion($destinatario, $nombreUsuario, $token)
{
    $enlace = "http://localhost/Shareflix/View/Inicio/RestablecerContrasenna.php?token=" . urlencode($token);

    $variables = array(
        'NOMBRE_USUARIO' => $nombreUsuario,
        'ENLACE_RECUPERACION' => $enlace,
        'ANIO' => date('Y')
    );

    $mensaje = CargarTemplateEmail('recuperacion', $variables);

    if (!$mensaje) {
        return array('success' => false, 'mensaje' => 'Error al cargar template de email');
    }

    return EnviarCorreo($destinatario, "Recuperación de Contraseña - Shareflix", $mensaje);
}

// ENVIAR CORREO DE BIENVENIDA
function EnviarCorreoBienvenida($destinatario, $nombreUsuario)
{
    $variables = array(
        'NOMBRE_USUARIO' => $nombreUsuario,
        'ENLACE_LOGIN' => 'http://localhost/Shareflix/View/Inicio/IniciarSesion.php',
        'ANIO' => date('Y')
    );
    
    $mensaje = CargarTemplateEmail('bienvenida', $variables);
    
    if(!$mensaje) {
        return array('success' => false, 'mensaje' => 'Error al cargar template de email');
    }
    
    $asunto = "¡Bienvenido a Shareflix! 🎬";
    
    return EnviarCorreo($destinatario, $asunto, $mensaje, true);
}

// CONSULTAR API DE CÉDULA COSTA RICA
function ConsultarAPICedula($cedula)
{
    try {
        // Limpiar cédula (solo números)
        $cedula = preg_replace('/[^0-9]/', '', $cedula);

        // Validar longitud (debe ser 9 dígitos)
        if(strlen($cedula) != 9) {
            return array(
                'success' => false, 
                'mensaje' => 'La cédula debe tener 9 dígitos'
            );
        }

        // Llamar a la API del TSE de Costa Rica
        $url = "https://apis.gometa.org/cedulas/" . $cedula;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($httpCode == 200 && $response) {
            $data = json_decode($response, true);
            
            if(isset($data['results']) && count($data['results']) > 0) {
                $persona = $data['results'][0];
                
                return array(
                    'success' => true,
                    'nombre' => $persona['firstname'] . ' ' . $persona['lastname'],
                    'cedula' => $cedula
                );
            } else {
                return array(
                    'success' => false,
                    'mensaje' => 'Cédula no encontrada en el sistema del TSE'
                );
            }
        } else {
            return array(
                'success' => false,
                'mensaje' => 'Error al consultar la API. Intenta nuevamente.'
            );
        }
    } catch (Exception $e) {
        return array(
            'success' => false,
            'mensaje' => 'Error en el servidor al consultar la cédula'
        );
    }
}

// VALIDAR SESIÓN ACTIVA
function ValidarSesion()
{
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if(!isset($_SESSION["ConsecutivoUsuario"])) {
        header("Location: ../Inicio/IniciarSesion.php");
        exit;
    }
}

// VALIDAR SESIÓN DE ADMINISTRADOR
function ValidarSesionAdmin()
{
    // SIEMPRE iniciar sesión antes de validar
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    ValidarSesion();
    
    if($_SESSION["ConsecutivoPerfil"] != 1) {
        header("Location: ../Contenido/Catalogo.php");
        exit;
    }
}

// VALIDAR SESIÓN DE CLIENTE
function ValidarSesionCliente()
{
    // SIEMPRE iniciar sesión antes de validar
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    ValidarSesion();
    
    if($_SESSION["ConsecutivoPerfil"] == 1) {
        header("Location: ../Admin/Dashboard.php");
        exit;
    }
}

// SANITIZAR ENTRADA
function SanitizarEntrada($dato)
{
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

// GENERAR SLUG (para URLs amigables)
function GenerarSlug($texto)
{
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    $texto = trim($texto, '-');
    return $texto;
}

// FORMATEAR DURACION (minutos a horas:minutos)
function FormatearDuracion($minutos)
{
    $horas = floor($minutos / 60);
    $mins = $minutos % 60;
    
    if($horas > 0) {
        return $horas . "h " . $mins . "min";
    } else {
        return $minutos . " min";
    }
}

// VALIDAR FORMATO DE EMAIL
function ValidarEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// VALIDAR FUERZA DE CONTRASEÑA
function ValidarFortalezaContrasena($contrasena)
{
    $errores = array();
    
    if(strlen($contrasena) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres";
    }
    
    if(!preg_match('/[A-Z]/', $contrasena)) {
        $errores[] = "Debe contener al menos una letra mayúscula";
    }
    
    if(!preg_match('/[a-z]/', $contrasena)) {
        $errores[] = "Debe contener al menos una letra minúscula";
    }
    
    if(!preg_match('/[0-9]/', $contrasena)) {
        $errores[] = "Debe contener al menos un número";
    }
    
    if(count($errores) > 0) {
        return array('valida' => false, 'errores' => $errores);
    } else {
        return array('valida' => true);
    }
}

// REDIRECCIONAR SEGÚN ROL
function RedireccionarSegunRol($perfil)
{
    // Limpiar el output buffer antes de redirigir
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/Shareflix/View";
    
    if($perfil == 1) {
        // Administrador
        header("Location: $baseUrl/Admin/Dashboard.php");
        exit;
    } else {
        // Cliente  
        header("Location: $baseUrl/Contenido/Catalogo.php");
        exit;
    }
}
?>