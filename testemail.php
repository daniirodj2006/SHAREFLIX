<?php
// test_email.php - Guardar en: C:\xampp\htdocs\Shareflix\

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/PHPMailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<h1>Prueba de Envío de Correo - Shareflix</h1>";
echo "<hr>";

// ✅ CONFIGURACIÓN
$correoSalida = "shareflix_cr@outlook.com";
$contrasennaSalida = "pfznsfmbfeorkarz";
$correoDestino = "daniroji20@gmail.com"; // ⚠️ CAMBIA ESTO por tu correo personal

echo "<h3>Configuración:</h3>";
echo "Correo de salida: $correoSalida<br>";
echo "Correo destino: $correoDestino<br>";
echo "<hr>";

$mail = new PHPMailer(true);

try {
    echo "<h3>Intentando enviar correo...</h3>";
    
    // ✅ DEBUG ACTIVADO
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';
    
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = $correoSalida;
    $mail->Password = $contrasennaSalida;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    $mail->CharSet = 'UTF-8';
    
    // Contenido del correo
    $mail->setFrom($correoSalida, 'Shareflix TEST');
    $mail->addAddress($correoDestino);
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de correo - Shareflix';
    $mail->Body = '<h1>¡Correo de prueba!</h1><p>Si ves este mensaje, el sistema de correo funciona correctamente.</p>';
    
    $mail->send();
    
    echo "<hr>";
    echo "<h2 style='color: green;'>✅ ¡Correo enviado exitosamente!</h2>";
    echo "<p>Revisa tu bandeja de entrada en: $correoDestino</p>";
    
} catch (Exception $e) {
    echo "<hr>";
    echo "<h2 style='color: red;'>❌ ERROR al enviar correo</h2>";
    echo "<div style='background: #ffcccc; padding: 15px; border: 2px solid red; margin: 10px 0;'>";
    echo "<strong>Mensaje de error:</strong><br>";
    echo nl2br(htmlspecialchars($e->getMessage()));
    echo "</div>";
    
    echo "<div style='background: #ffffcc; padding: 15px; border: 2px solid orange; margin: 10px 0;'>";
    echo "<strong>Error de PHPMailer:</strong><br>";
    echo nl2br(htmlspecialchars($mail->ErrorInfo));
    echo "</div>";
    
    echo "<div style='background: #e6e6e6; padding: 15px; border: 2px solid gray; margin: 10px 0;'>";
    echo "<strong>Archivo:</strong> " . $e->getFile() . "<br>";
    echo "<strong>Línea:</strong> " . $e->getLine() . "<br>";
    echo "</div>";
}

echo "<hr>";
echo "<h3>Información del servidor PHP:</h3>";
echo "Versión PHP: " . phpversion() . "<br>";
echo "OpenSSL habilitado: " . (extension_loaded('openssl') ? 'SÍ ✅' : 'NO ❌') . "<br>";
echo "Socket habilitado: " . (extension_loaded('sockets') ? 'SÍ ✅' : 'NO ❌') . "<br>";
?>