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
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            color: #0F0F0F;
            margin: 0;
            font-size: 32px;
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
            margin-bottom: 15px;
        }
        .features {
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .features ul {
            list-style: none;
            padding: 0;
        }
        .features li {
            padding: 10px 0;
            color: #333;
        }
        .features li:before {
            content: '✓ ';
            color: #FF8C42;
            font-weight: bold;
            margin-right: 10px;
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
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🎬 SHAREFLIX</h1>
            <p style='color: #0F0F0F; margin-top: 10px; font-size: 18px;'>¡Bienvenido a bordo!</p>
        </div>
        <div class='content'>
            <h2>Hola, {{NOMBRE_USUARIO}} 👋</h2>
            <p>¡Nos emociona tenerte en Shareflix! Tu cuenta ha sido creada exitosamente.</p>
            
            <div class='features'>
                <h3>Con tu cuenta GRATIS puedes:</h3>
                <ul>
                    <li>Explorar todo nuestro catálogo de películas y series</li>
                    <li>Agregar hasta 5 contenidos a tus favoritos</li>
                    <li>Buscar por género, categoría y tipo de contenido</li>
                    <li>Disfrutar de contenido de calidad</li>
                </ul>
            </div>

            <p><strong>💎 ¿Quieres más?</strong> Actualiza a Premium y disfruta de favoritos ilimitados.</p>
            
            <center>
                <a href='{{ENLACE_LOGIN}}' class='button'>Iniciar Sesión Ahora</a>
            </center>

            <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
        </div>
        <div class='footer'>
            <p>&copy; {{ANIO}} Shareflix. Todos los derechos reservados.</p>
            <p>¡Disfruta del mejor contenido!</p>
        </div>
    </div>
</body>
</html>