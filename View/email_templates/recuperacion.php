<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #FF8C42 0%, #FFB84D 100%);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #0F0F0F;
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #FF8C42 0%, #FFB84D 100%);
            color: #0F0F0F;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🎬 SHAREFLIX</h1>
        </div>
        <div class='content'>
            <h2>Hola, {{NOMBRE_USUARIO}}</h2>
            <p>Recibimos una solicitud para restablecer tu contraseña en Shareflix.</p>
            <p>Haz clic en el botón de abajo para crear una nueva contraseña:</p>
            <center>
                <a href='{{ENLACE_RECUPERACION}}' class='button'>Restablecer Contraseña</a>
            </center>
            <div class='warning'>
                <strong>⚠️ Importante:</strong>
                <ul>
                    <li>Este enlace expirará en 1 hora</li>
                    <li>Si no solicitaste este cambio, ignora este correo</li>
                    <li>Nunca compartas este enlace con nadie</li>
                </ul>
            </div>
            <p>Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
            <p style='word-break: break-all; color: #FF8C42;'>{{ENLACE_RECUPERACION}}</p>
        </div>
        <div class='footer'>
            <p>&copy; {{ANIO}} Shareflix. Todos los derechos reservados.</p>
            <p>Este es un correo automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>