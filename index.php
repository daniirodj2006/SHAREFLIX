<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shareflix - Tu plataforma de streaming favorita</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Shareflix Custom CSS -->
    <link rel="stylesheet" href="/Shareflix/View/css/shareflix.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <!-- Navbar -->
        <nav class="navbar-shareflix">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="logo-container">
                        <img src="/Shareflix/View/img/logo.png" alt="Shareflix Logo" class="logo-shareflix" onerror="this.style.display='none'">
                        <span class="brand-text">Shareflix</span>
                    </div>
                    <div>
                        <a href="/Shareflix/View/Inicio/IniciarSesion.php" class="btn-shareflix btn-outline-shareflix">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Content -->
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    Bienvenido a <span class="gradient-text">Shareflix</span>
                </h1>
                <p class="hero-subtitle">
                    Películas, series y documentales ilimitados. Disfruta donde quieras, cuando quieras.
                </p>
                <div class="hero-buttons">
                    <a href="/Shareflix/View/Inicio/Registro.php" class="btn-shareflix btn-primary-shareflix">
                        <i class="fas fa-play-circle"></i> Comenzar Ahora
                    </a>
                    <a href="#info" class="btn-shareflix btn-outline-shareflix">
                        <i class="fas fa-info-circle"></i> Más Información
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <section id="info" style="padding: 5rem 0; background: #1A1A1A;">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div style="font-size: 3rem; color: #FF8C42; margin-bottom: 1rem;">
                        <i class="fas fa-film"></i>
                    </div>
                    <h3 class="gradient-text">Miles de Películas</h3>
                    <p class="text-muted">Accede a un catálogo en constante actualización con los mejores estrenos.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div style="font-size: 3rem; color: #FFB84D; margin-bottom: 1rem;">
                        <i class="fas fa-tv"></i>
                    </div>
                    <h3 class="gradient-text">Series Exclusivas</h3>
                    <p class="text-muted">Disfruta de series completas y documentales fascinantes.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div style="font-size: 3rem; color: #FF8C42; margin-bottom: 1rem;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="gradient-text">Suscripción Premium</h3>
                    <p class="text-muted">Acceso sin publicidad y funciones exclusivas por solo $9.99/mes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section style="padding: 5rem 0; background: #0F0F0F;">
        <div class="container">
            <h2 class="text-center mb-5" style="font-size: 2.5rem;">
                <span class="gradient-text">¿Por qué elegir Shareflix?</span>
            </h2>
            <div class="row align-items-center">
                <div class="col-md-6 mb-4">
                    <h3 style="color: #FF8C42;">🎬 Contenido Ilimitado</h3>
                    <p class="text-muted">Accede a todo nuestro catálogo sin restricciones. Desde clásicos hasta los últimos estrenos.</p>
                    
                    <h3 style="color: #FFB84D; margin-top: 2rem;">📱 Multi-Dispositivo</h3>
                    <p class="text-muted">Disfruta en tu TV, computadora, tablet o smartphone. Donde estés, cuando quieras.</p>
                    
                    <h3 style="color: #FF8C42; margin-top: 2rem;">⭐ Favoritos Personalizados</h3>
                    <p class="text-muted">Guarda tus películas y series favoritas para verlas más tarde.</p>
                </div>
                <div class="col-md-6 text-center">
                    <div style="background: linear-gradient(135deg, rgba(255, 140, 66, 0.2) 0%, rgba(255, 184, 77, 0.2) 100%); padding: 3rem; border-radius: 20px;">
                        <i class="fas fa-play-circle" style="font-size: 8rem; color: #FF8C42;"></i>
                        <h4 class="mt-3 gradient-text">¡Empieza a Ver Ahora!</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section style="padding: 5rem 0; background: linear-gradient(135deg, #FF8C42 0%, #FFB84D 100%);">
        <div class="container text-center">
            <h2 style="font-size: 2.5rem; color: #0F0F0F; font-weight: bold; margin-bottom: 1rem;">
                ¿Listo para empezar?
            </h2>
            <p style="font-size: 1.3rem; color: #0F0F0F; margin-bottom: 2rem;">
                Crea tu cuenta gratis y comienza a disfrutar de Shareflix hoy mismo.
            </p>
            <a href="/Shareflix/View/Inicio/Registro.php" class="btn-shareflix" style="background: #0F0F0F; color: #FFB84D; font-size: 1.2rem;">
                <i class="fas fa-user-plus"></i> Registrarse Gratis
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: #0F0F0F; padding: 3rem 0; border-top: 1px solid rgba(255, 140, 66, 0.2);">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="gradient-text">Shareflix</h5>
                    <p class="text-muted">Tu plataforma de streaming favorita con el mejor contenido.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 style="color: #FF8C42;">Enlaces Rápidos</h6>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="/Shareflix/View/Inicio/IniciarSesion.php" style="color: #CCC; text-decoration: none;">Iniciar Sesión</a></li>
                        <li><a href="/Shareflix/View/Inicio/Registro.php" style="color: #CCC; text-decoration: none;">Registrarse</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 style="color: #FFB84D;">Síguenos</h6>
                    <div class="d-flex gap-3">
                        <a href="#" style="color: #FF8C42; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="color: #FF8C42; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: #FF8C42; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255, 140, 66, 0.2); margin: 2rem 0;">
            <p class="text-center text-muted mb-0">
                © <?php echo date("Y"); ?> Shareflix. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
