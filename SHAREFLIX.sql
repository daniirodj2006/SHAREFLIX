
CREATE DATABASE IF NOT EXISTS shareflix_bd;
USE shareflix_bd;

-- ========================================
-- SECCIÓN 1: ELIMINACIÓN DE TABLAS
-- ========================================

DROP TABLE IF EXISTS `logerrores`;
DROP TABLE IF EXISTS `recuperacioncontrasenna`;
DROP TABLE IF EXISTS `recuperacioncontrasena`;
DROP TABLE IF EXISTS `favoritos`;
DROP TABLE IF EXISTS `usuariosuscripcion`;
DROP TABLE IF EXISTS `usuariorol`;
DROP TABLE IF EXISTS `contenidogenero`;
DROP TABLE IF EXISTS `contenidocategoria`;
DROP TABLE IF EXISTS `contenido`;
DROP TABLE IF EXISTS `genero`;
DROP TABLE IF EXISTS `categoria`;
DROP TABLE IF EXISTS `tipocontenido`;
DROP TABLE IF EXISTS `suscripcion`;
DROP TABLE IF EXISTS `rol`;
DROP TABLE IF EXISTS `usuariobd`;

-- ========================================
-- SECCIÓN 2: CREACIÓN DE TABLAS
-- ========================================

-- Tabla: usuariobd
CREATE TABLE `usuariobd` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(20) NOT NULL,
  `nombreUsuario` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasenna` varchar(255) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idUsuario`),
  UNIQUE KEY `cedula` (`cedula`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: rol
CREATE TABLE `rol` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `nombreRol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idRol`),
  UNIQUE KEY `nombreRol` (`nombreRol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: suscripcion
CREATE TABLE `suscripcion` (
  `idSuscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `tipoSuscripcion` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `limiteFavoritos` int(11) DEFAULT 5,
  PRIMARY KEY (`idSuscripcion`),
  UNIQUE KEY `tipoSuscripcion` (`tipoSuscripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: tipocontenido
CREATE TABLE `tipocontenido` (
  `idTipoContenido` int(11) NOT NULL AUTO_INCREMENT,
  `nombreTipo` varchar(50) NOT NULL,
  PRIMARY KEY (`idTipoContenido`),
  UNIQUE KEY `nombreTipo` (`nombreTipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: categoria
CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombreCategoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idCategoria`),
  UNIQUE KEY `nombreCategoria` (`nombreCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: genero
CREATE TABLE `genero` (
  `idGenero` int(11) NOT NULL AUTO_INCREMENT,
  `nombreGenero` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idGenero`),
  UNIQUE KEY `nombreGenero` (`nombreGenero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: contenido
CREATE TABLE `contenido` (
  `idContenido` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fechaPublicacion` date DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL COMMENT 'Duración en minutos',
  `imagen` varchar(255) DEFAULT NULL COMMENT 'Ruta del poster',
  `trailer` varchar(255) DEFAULT NULL COMMENT 'URL del trailer',
  `video_archivo` varchar(255) DEFAULT NULL COMMENT 'Nombre del archivo de video',
  `calificacionEdad` varchar(10) DEFAULT NULL COMMENT 'ATP, +13, +16, +18',
  `activo` tinyint(1) DEFAULT 1,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idContenido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: usuariorol
CREATE TABLE `usuariorol` (
  `idUsuarioRol` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idRol` int(11) NOT NULL,
  PRIMARY KEY (`idUsuarioRol`),
  UNIQUE KEY `idUsuario` (`idUsuario`,`idRol`),
  KEY `idRol` (`idRol`),
  CONSTRAINT `usuariorol_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariobd` (`idUsuario`),
  CONSTRAINT `usuariorol_ibfk_2` FOREIGN KEY (`idRol`) REFERENCES `rol` (`idRol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: usuariosuscripcion
CREATE TABLE `usuariosuscripcion` (
  `idUsuarioSuscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idSuscripcion` int(11) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaVencimiento` date NOT NULL,
  `estado` enum('Activa','Vencida','Cancelada') DEFAULT 'Activa',
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idUsuarioSuscripcion`),
  KEY `idUsuario` (`idUsuario`),
  KEY `idSuscripcion` (`idSuscripcion`),
  CONSTRAINT `usuariosuscripcion_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariobd` (`idUsuario`),
  CONSTRAINT `usuariosuscripcion_ibfk_2` FOREIGN KEY (`idSuscripcion`) REFERENCES `suscripcion` (`idSuscripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: contenidocategoria
CREATE TABLE `contenidocategoria` (
  `idContenidoCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `idContenido` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  PRIMARY KEY (`idContenidoCategoria`),
  UNIQUE KEY `idContenido` (`idContenido`,`idCategoria`),
  KEY `idCategoria` (`idCategoria`),
  CONSTRAINT `contenidocategoria_ibfk_1` FOREIGN KEY (`idContenido`) REFERENCES `contenido` (`idContenido`) ON DELETE CASCADE,
  CONSTRAINT `contenidocategoria_ibfk_2` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: contenidogenero
CREATE TABLE `contenidogenero` (
  `idContenidoGenero` int(11) NOT NULL AUTO_INCREMENT,
  `idContenido` int(11) NOT NULL,
  `idGenero` int(11) NOT NULL,
  PRIMARY KEY (`idContenidoGenero`),
  UNIQUE KEY `idContenido` (`idContenido`,`idGenero`),
  KEY `idGenero` (`idGenero`),
  CONSTRAINT `contenidogenero_ibfk_1` FOREIGN KEY (`idContenido`) REFERENCES `contenido` (`idContenido`) ON DELETE CASCADE,
  CONSTRAINT `contenidogenero_ibfk_2` FOREIGN KEY (`idGenero`) REFERENCES `genero` (`idGenero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: favoritos
CREATE TABLE `favoritos` (
  `idFavorito` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idContenido` int(11) NOT NULL,
  `fechaAgregado` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idFavorito`),
  UNIQUE KEY `idUsuario` (`idUsuario`,`idContenido`),
  KEY `idContenido` (`idContenido`),
  CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariobd` (`idUsuario`),
  CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`idContenido`) REFERENCES `contenido` (`idContenido`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: recuperacioncontrasena
CREATE TABLE `recuperacioncontrasena` (
  `idRecuperacion` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  `fechaExpiracion` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`idRecuperacion`),
  UNIQUE KEY `token` (`token`),
  KEY `idUsuario` (`idUsuario`),
  CONSTRAINT `recuperacioncontrasena_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariobd` (`idUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: recuperacioncontrasenna
CREATE TABLE `recuperacioncontrasenna` (
  `idRecuperacion` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  `fechaExpiracion` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`idRecuperacion`),
  UNIQUE KEY `token` (`token`),
  KEY `idUsuario` (`idUsuario`),
  CONSTRAINT `recuperacioncontrasenna_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariobd` (`idUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: logerrores
CREATE TABLE `logerrores` (
  `idError` int(11) NOT NULL AUTO_INCREMENT,
  `mensaje` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idError`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- SECCIÓN 3: INSERCIÓN DE DATOS
-- ========================================

-- Datos para: rol
INSERT INTO `rol` VALUES 
(1,'Administrador','Acceso completo al sistema'),
(2,'Cliente','Usuario normal de la plataforma');

-- Datos para: suscripcion
INSERT INTO `suscripcion` VALUES 
(1,'Gratis',0.00,'Plan gratuito con acceso limitado',5),
(2,'Premium',9.99,'Plan premium con acceso completo',999999),
(3,'VIP',19.99,'Plan VIP con beneficios exclusivos',999999);

-- Datos para: tipocontenido
INSERT INTO `tipocontenido` VALUES 
(3,'Documental'),
(1,'Pelicula'),
(2,'Serie');

-- Datos para: categoria
INSERT INTO `categoria` VALUES 
(1,'Destacadas','Contenido destacado de la plataforma.'),
(2,'Más Vistas','Contenido más popular'),
(3,'Nuevas','Últimas adiciones'),
(4,'Top 10','Las 10 más populares'),
(5,'Recomendadas','Recomendaciones personalizadas'),
(7,'Estrenos','Estrenos');

-- Datos para: genero
INSERT INTO `genero` VALUES 
(1,'Acción','Películas de acción y aventura'),
(2,'Comedia','Películas de comedia'),
(5,'Ciencia Ficción','Películas de ciencia ficción'),
(7,'Fantasía','Películas fantásticas'),
(8,'Animación','Películas animadas'),
(10,'Thriller','Películas de suspenso'),
(11,'Musical','Musical');

-- Datos para: usuariobd
INSERT INTO `usuariobd` VALUES 
(1,'111111111','Admin','admin@shareflix.com','1234',1,'2025-11-18 18:53:55'),
(4,'207290643','Daniela Rodriguez','dani@gmail.com','1234',1,'2025-11-19 09:31:45'),
(6,'305390518','DANIELA RODRIGUEZ JIMENEZ','daniroji20@Gmail.com','12345678',1,'2025-12-11 20:28:01'),
(7,'303280821','KELEN VIVIANA JIMENEZ HERNANDEZ','daniroji202@Gmail.com','12345678',1,'2025-12-11 20:28:45'),
(8,'107110946','RONALL ADOLFO RODRIGUEZ MURILLO','rodrir20@gmail.com','12345678',1,'2025-12-11 20:29:42'),
(9,'118150931','DANIEL ESTEBAN VELASQUEZ MENDEZ','develasmen@gmail.com','12345678',1,'2025-12-11 20:30:20'),
(10,'901150498','JASON LORIA CHAVES','correo@gmail.com','12345678',1,'2025-12-11 20:31:01'),
(11,'208360632','ANTONY BARRANTES BOGANTES','antony@gmail.com','12345678',1,'2025-12-16 21:40:26');

-- Datos para: usuariorol
INSERT INTO `usuariorol` VALUES 
(1,1,1),
(3,4,2),
(4,6,2),
(5,7,2),
(6,8,2),
(7,9,2),
(8,10,2),
(9,11,2);

-- Datos para: usuariosuscripcion
INSERT INTO `usuariosuscripcion` VALUES 
(1,1,2,'2025-11-18','2026-11-18','Activa','2025-11-18 18:54:42'),
(3,4,1,'2025-11-19','2026-11-19','Activa','2025-11-19 09:31:45'),
(4,6,1,'2025-12-11','2026-01-10','Activa','2025-12-11 20:28:01'),
(5,7,1,'2025-12-11','2026-01-10','Cancelada','2025-12-11 20:28:45'),
(6,8,1,'2025-12-11','2026-01-10','Activa','2025-12-11 20:29:42'),
(7,9,1,'2025-12-11','2026-01-10','Cancelada','2025-12-11 20:30:20'),
(8,10,1,'2025-12-11','2026-01-10','Cancelada','2025-12-11 20:31:01'),
(9,9,2,'2025-12-11','2026-12-11','Activa','2025-12-11 20:41:27'),
(10,10,2,'2025-12-11','2026-12-11','Activa','2025-12-11 20:41:31'),
(11,11,1,'2025-12-16','2026-01-15','Activa','2025-12-16 21:40:26'),
(12,7,2,'2025-12-16','2026-12-16','Activa','2025-12-16 21:46:12');

-- Datos para: contenido
INSERT INTO `contenido` VALUES 
(1,'Avatar: El Camino del Agua','Jake Sully y Neytiri han formado una familia y hacen todo lo posible por permanecer juntos.','2022-12-16',192,'691e86daabfea_1763608282.jpg','',NULL,'ATP',1,'2025-11-18 19:23:36'),
(2,'Spider-Man: A Través del Universo','Miles Morales regresa para una aventura épica.','2023-06-02',140,'691e41b6b64a3_1763590582.jpg','',NULL,'ATP',1,'2025-11-18 19:23:36'),
(3,'Guardianes de la Galaxia Vol. 3','Peter Quill debe reunir a su equipo para defender el universo.','2023-05-05',150,'691e4213d1bf2_1763590675.jpg','',NULL,'+16',1,'2025-11-18 19:23:36'),
(4,'El Gato con Botas: El Último Deseo','El Gato descubre que ha consumido ocho de sus nueve vidas.','2022-12-21',102,'691e4298861a2_1763590808.jpg','',NULL,'ATP',1,'2025-11-18 19:23:36'),
(5,'John Wick 4','John Wick busca un camino para derrotar a la Mesa Alta.','2023-03-24',169,'691e42c361c66_1763590851.jpg','',NULL,'+16',1,'2025-11-18 19:23:36'),
(18,'Película PRUEBA1','Esta es una prueba de inserción directa','2025-11-20',120,'691e7e6ad5edd_1763606122.jpg','',NULL,'ATP',1,'2025-11-19 20:19:23'),
(26,'Barbie','Película de fantasía y comedia donde Barbie (Margot Robbie) vive en Barbieland, un mundo perfecto y rosa, hasta que empieza a cuestionarse su existencia y a tener pensamientos "raros".','2023-03-05',120,'691e7b9fb54ef_1763605407.jpg','',NULL,'+13',1,'2025-11-19 20:23:27'),
(28,'Wicked','Wicked es una película musical que cuenta la historia no contada de las brujas de Oz, mostrando la amistad entre Elphaba y Glinda antes de convertirse en la Bruja Mala del Oeste y la Bruja Buena','2025-06-04',120,'693b80bf92f86_1765507263.jpg','','694219e95b9f6_1765939689.mp4','ATP',1,'2025-12-11 20:41:03'),
(29,'TITANIC','Narra la historia de amor entre Jack y Rose a bordo del famoso transatlántico Titanic. Su romance se desarrolla mientras ocurre una de las tragedias marítimas más conocidas de la historia.','1997-12-16',180,'69420af307b4a_1765935859.png','',NULL,'+13',1,'2025-12-16 19:44:19'),
(30,'COCO','Miguel, un niño amante de la música, viaja accidentalmente al Mundo de los Muertos. En su aventura descubre la importancia de la familia y de recordar a quienes ya no están.','2017-06-16',120,'69420b4e731a8_1765935950.jpg','',NULL,'ATP',1,'2025-12-16 19:45:50'),
(31,'Jurrassic Park','Un parque temático con dinosaurios clonados se convierte en una pesadilla cuando el sistema de seguridad falla. Los visitantes deberán luchar por sobrevivir frente a criaturas prehistóricas fuera de control.','1993-02-16',130,'69420bad264cc_1765936045.jpg','',NULL,'ATP',1,'2025-12-16 19:47:25'),
(32,'Interestellar','Un grupo de astronautas viaja a través de un agujero de gusano en busca de un nuevo hogar para la humanidad. El tiempo y el amor juegan un papel clave en su misión.','2025-12-03',180,'69420c061c4da_1765936134.jpg','',NULL,'+7',1,'2025-12-16 19:48:54'),
(35,'Forrest Gump','Un hombre con gran corazón participa involuntariamente en momentos históricos importantes. Su vida demuestra que la bondad puede dejar huella en el mundo.','2025-12-03',150,'69420c54b2903_1765936212.jpg','',NULL,'+13',1,'2025-12-16 19:50:12'),
(37,'High School Musical','Un estudiante estrella del baloncesto y una chica apasionada por el canto descubren su amor por la música. Ambos desafían las expectativas de su escuela al intentar participar juntos en el musical escolar.','2025-12-04',120,'69420cb44bed5_1765936308.jpg','',NULL,'ATP',1,'2025-12-16 19:51:48'),
(39,'Mean girls','Cady Heron ingresa a una escuela secundaria y se ve envuelta en el mundo de las chicas populares conocidas como "Las Plásticas". Pronto aprende las consecuencias de encajar, manipular y perder su verdadera identidad.','2025-12-24',120,'694217a2862ad_1765939106.jpg','','694217a286d35_1765939106.mp4','ATP',1,'2025-12-16 20:26:29');

-- Datos para: contenidocategoria
INSERT INTO `contenidocategoria` VALUES 
(28,1,3),
(29,1,5),
(14,2,1),
(15,2,2),
(19,3,4),
(17,4,3),
(18,5,1),
(25,18,2),
(26,18,5),
(21,26,1),
(47,28,3),
(34,29,2),
(35,30,1),
(36,30,3),
(37,31,1),
(38,32,5),
(41,35,5),
(43,37,2),
(48,39,1);

-- Datos para: contenidogenero
INSERT INTO `contenidogenero` VALUES 
(35,1,1),
(36,1,5),
(15,2,2),
(14,2,7),
(13,2,8),
(23,3,1),
(24,3,5),
(19,4,7),
(18,4,8),
(20,5,1),
(21,5,7),
(22,5,10),
(33,18,5),
(32,18,7),
(28,26,2),
(27,26,7),
(53,28,7),
(39,29,1),
(40,30,8),
(41,30,11),
(42,31,1),
(43,31,5),
(44,32,5),
(47,35,2),
(49,37,11),
(54,39,2);

-- Datos para: favoritos
INSERT INTO `favoritos` VALUES 
(1,4,2,'2025-11-19 10:19:03'),
(2,4,3,'2025-11-19 10:19:03'),
(3,4,5,'2025-11-19 10:19:03');

-- ========================================
-- SECCIÓN 4: PROCEDIMIENTOS ALMACENADOS
-- ========================================

DELIMITER $$

-- Procedimiento: CrearCuenta
DROP PROCEDURE IF EXISTS `CrearCuenta`$$
CREATE PROCEDURE `CrearCuenta`(
    IN p_cedula VARCHAR(20),
    IN p_nombre VARCHAR(100),
    IN p_correo VARCHAR(100),
    IN p_contrasenna VARCHAR(255)
)
BEGIN
    DECLARE v_idUsuario INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error al crear la cuenta' AS mensaje, 0 AS success;
    END;
    
    START TRANSACTION;
    INSERT INTO usuarioBD (cedula, nombreUsuario, correo, contrasenna, fechaRegistro, activo)
    VALUES (p_cedula, p_nombre, p_correo, p_contrasenna, NOW(), 1);
    
    SET v_idUsuario = LAST_INSERT_ID();
    INSERT INTO usuarioRol (idUsuario, idRol) VALUES (v_idUsuario, 2);
    INSERT INTO usuarioSuscripcion (idUsuario, idSuscripcion, fechaInicio, fechaVencimiento, estado)
    VALUES (v_idUsuario, 1, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 'Activa');
    
    COMMIT;
    SELECT 'Usuario registrado exitosamente' AS mensaje, 1 AS success;
END$$

-- Procedimiento: ValidarCuenta
DROP PROCEDURE IF EXISTS `ValidarCuenta`$$
CREATE PROCEDURE `ValidarCuenta`(
    IN p_correo VARCHAR(100),
    IN p_contrasenna VARCHAR(255)
)
BEGIN
    SELECT 
        u.idUsuario AS ConsecutivoUsuario,
        u.nombreUsuario AS Nombre,
        u.correo AS CorreoElectronico,
        r.idRol AS ConsecutivoPerfil,
        r.nombreRol AS NombrePerfil,
        COALESCE(s.tipoSuscripcion, 'Gratis') AS TipoSuscripcion,
        COALESCE(s.limiteFavoritos, 5) AS LimiteFavoritos
    FROM usuarioBD u
    INNER JOIN usuarioRol ur ON u.idUsuario = ur.idUsuario
    INNER JOIN rol r ON ur.idRol = r.idRol
    LEFT JOIN usuarioSuscripcion us ON u.idUsuario = us.idUsuario AND us.estado = 'Activa'
    LEFT JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
    WHERE u.correo = p_correo 
      AND u.contrasenna = p_contrasenna 
      AND u.activo = 1
    LIMIT 1;
END$$

-- Procedimiento: ConsultarContenido
DROP PROCEDURE IF EXISTS `ConsultarContenido`$$
CREATE PROCEDURE `ConsultarContenido`()
BEGIN
    SELECT 
        c.idContenido AS ConsecutivoContenido,
        c.titulo AS Titulo,
        c.descripcion AS Descripcion,
        c.duracion AS Duracion,
        c.imagen AS Imagen,
        c.trailer AS Trailer,
        c.video_archivo AS VideoArchivo,
        c.calificacionEdad AS CalificacionEdad,
        c.fechaPublicacion,
        c.activo AS Activo,
        GROUP_CONCAT(DISTINCT g.nombreGenero ORDER BY g.nombreGenero SEPARATOR ', ') AS Generos,
        GROUP_CONCAT(DISTINCT cat.nombreCategoria ORDER BY cat.nombreCategoria SEPARATOR ', ') AS Categorias
    FROM contenido c
    LEFT JOIN contenidoGenero cg ON c.idContenido = cg.idContenido
    LEFT JOIN genero g ON cg.idGenero = g.idGenero
    LEFT JOIN contenidoCategoria cc ON c.idContenido = cc.idContenido
    LEFT JOIN categoria cat ON cc.idCategoria = cat.idCategoria
    GROUP BY c.idContenido
    ORDER BY c.fechaCreacion DESC;
END$$

-- Procedimiento: AgregarContenido
DROP PROCEDURE IF EXISTS `AgregarContenido`$$
CREATE PROCEDURE `AgregarContenido`(
    IN p_titulo VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_duracion INT,
    IN p_imagen VARCHAR(255),
    IN p_trailer VARCHAR(255),
    IN p_video_archivo VARCHAR(255),
    IN p_calificacionEdad VARCHAR(10),
    IN p_fechaPublicacion DATE
)
BEGIN
    INSERT INTO contenido (titulo, descripcion, duracion, imagen, trailer, video_archivo, calificacionEdad, fechaPublicacion)
    VALUES (p_titulo, p_descripcion, p_duracion, p_imagen, p_trailer, p_video_archivo, p_calificacionEdad, p_fechaPublicacion);
    SELECT LAST_INSERT_ID() AS idContenido, 'Contenido agregado exitosamente' AS mensaje;
END$$

-- Procedimiento: ActualizarContenido
DROP PROCEDURE IF EXISTS `ActualizarContenido`$$
CREATE PROCEDURE `ActualizarContenido`(
    IN p_idContenido INT,
    IN p_titulo VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_duracion INT,
    IN p_imagen VARCHAR(255),
    IN p_trailer VARCHAR(255),
    IN p_video_archivo VARCHAR(255),
    IN p_calificacionEdad VARCHAR(10),
    IN p_fechaPublicacion DATE
)
BEGIN
    UPDATE contenido
    SET titulo = p_titulo,
        descripcion = p_descripcion,
        duracion = p_duracion,
        imagen = IF(p_imagen != '', p_imagen, imagen),
        trailer = p_trailer,
        video_archivo = IF(p_video_archivo != '', p_video_archivo, video_archivo),
        calificacionEdad = p_calificacionEdad,
        fechaPublicacion = p_fechaPublicacion
    WHERE idContenido = p_idContenido;
    SELECT 'Contenido actualizado exitosamente' AS mensaje;
END$$

-- Procedimiento: EliminarContenido
DROP PROCEDURE IF EXISTS `EliminarContenido`$$
CREATE PROCEDURE `EliminarContenido`(IN p_idContenido INT)
BEGIN
    DELETE FROM contenido WHERE idContenido = p_idContenido;
    SELECT 'Contenido eliminado exitosamente' AS mensaje;
END$$

-- Procedimiento: CambiarEstadoContenido
DROP PROCEDURE IF EXISTS `CambiarEstadoContenido`$$
CREATE PROCEDURE `CambiarEstadoContenido`(IN p_idContenido INT)
BEGIN
    UPDATE contenido SET activo = NOT activo WHERE idContenido = p_idContenido;
    SELECT 'Estado cambiado exitosamente' AS mensaje;
END$$

-- Procedimiento: ConsultarGeneros
DROP PROCEDURE IF EXISTS `ConsultarGeneros`$$
CREATE PROCEDURE `ConsultarGeneros`()
BEGIN
    SELECT idGenero AS ConsecutivoGenero, nombreGenero AS Nombre, descripcion AS Descripcion
    FROM genero ORDER BY nombreGenero;
END$$

-- Procedimiento: AgregarGenero
DROP PROCEDURE IF EXISTS `AgregarGenero`$$
CREATE PROCEDURE `AgregarGenero`(
    IN p_nombreGenero VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO genero (nombreGenero, descripcion) VALUES (p_nombreGenero, p_descripcion);
    SELECT 'Género agregado exitosamente' AS mensaje;
END$$

-- Procedimiento: ActualizarGenero
DROP PROCEDURE IF EXISTS `ActualizarGenero`$$
CREATE PROCEDURE `ActualizarGenero`(
    IN p_idGenero INT,
    IN p_nombreGenero VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE genero SET nombreGenero = p_nombreGenero, descripcion = p_descripcion
    WHERE idGenero = p_idGenero;
    SELECT 'Género actualizado exitosamente' AS mensaje;
END$$

-- Procedimiento: EliminarGenero
DROP PROCEDURE IF EXISTS `EliminarGenero`$$
CREATE PROCEDURE `EliminarGenero`(IN p_idGenero INT)
BEGIN
    DELETE FROM genero WHERE idGenero = p_idGenero;
    SELECT 'Género eliminado exitosamente' AS mensaje;
END$$

-- Procedimiento: ConsultarCategorias
DROP PROCEDURE IF EXISTS `ConsultarCategorias`$$
CREATE PROCEDURE `ConsultarCategorias`()
BEGIN
    SELECT idCategoria AS ConsecutivoCategoria, nombreCategoria AS Nombre, descripcion AS Descripcion
    FROM categoria ORDER BY nombreCategoria;
END$$

-- Procedimiento: AgregarCategoria
DROP PROCEDURE IF EXISTS `AgregarCategoria`$$
CREATE PROCEDURE `AgregarCategoria`(
    IN p_nombreCategoria VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO categoria (nombreCategoria, descripcion) VALUES (p_nombreCategoria, p_descripcion);
    SELECT 'Categoría agregada exitosamente' AS mensaje;
END$$

-- Procedimiento: ActualizarCategoria
DROP PROCEDURE IF EXISTS `ActualizarCategoria`$$
CREATE PROCEDURE `ActualizarCategoria`(
    IN p_idCategoria INT,
    IN p_nombreCategoria VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE categoria SET nombreCategoria = p_nombreCategoria, descripcion = p_descripcion
    WHERE idCategoria = p_idCategoria;
    SELECT 'Categoría actualizada exitosamente' AS mensaje;
END$$

-- Procedimiento: EliminarCategoria
DROP PROCEDURE IF EXISTS `EliminarCategoria`$$
CREATE PROCEDURE `EliminarCategoria`(IN p_idCategoria INT)
BEGIN
    DELETE FROM categoria WHERE idCategoria = p_idCategoria;
    SELECT 'Categoría eliminada exitosamente' AS mensaje;
END$$

-- Procedimiento: AgregarGeneroContenido
DROP PROCEDURE IF EXISTS `AgregarGeneroContenido`$$
CREATE PROCEDURE `AgregarGeneroContenido`(
    IN p_idContenido INT,
    IN p_idGenero INT
)
BEGIN
    INSERT INTO contenidoGenero (idContenido, idGenero) VALUES (p_idContenido, p_idGenero);
END$$

-- Procedimiento: EliminarGenerosContenido
DROP PROCEDURE IF EXISTS `EliminarGenerosContenido`$$
CREATE PROCEDURE `EliminarGenerosContenido`(IN p_idContenido INT)
BEGIN
    DELETE FROM contenidoGenero WHERE idContenido = p_idContenido;
END$$

-- Procedimiento: AgregarCategoriaContenido
DROP PROCEDURE IF EXISTS `AgregarCategoriaContenido`$$
CREATE PROCEDURE `AgregarCategoriaContenido`(
    IN p_idContenido INT,
    IN p_idCategoria INT
)
BEGIN
    INSERT INTO contenidoCategoria (idContenido, idCategoria) VALUES (p_idContenido, p_idCategoria);
END$$

-- Procedimiento: EliminarCategoriasContenido
DROP PROCEDURE IF EXISTS `EliminarCategoriasContenido`$$
CREATE PROCEDURE `EliminarCategoriasContenido`(IN p_idContenido INT)
BEGIN
    DELETE FROM contenidoCategoria WHERE idContenido = p_idContenido;
END$$

-- Procedimiento: ConsultarUsuarios
DROP PROCEDURE IF EXISTS `ConsultarUsuarios`$$
CREATE PROCEDURE `ConsultarUsuarios`()
BEGIN
    SELECT 
        u.idUsuario AS ConsecutivoUsuario,
        u.cedula AS Identificacion,
        u.nombreUsuario AS Nombre,
        u.correo AS CorreoElectronico,
        u.fechaRegistro,
        u.activo,
        r.nombreRol AS NombrePerfil,
        COALESCE(s.tipoSuscripcion, 'Gratis') AS TipoSuscripcion,
        COALESCE(us.idSuscripcion, 1) AS ConsecutivoSuscripcion
    FROM usuarioBD u
    LEFT JOIN usuarioRol ur ON u.idUsuario = ur.idUsuario
    LEFT JOIN rol r ON ur.idRol = r.idRol
    LEFT JOIN usuarioSuscripcion us ON u.idUsuario = us.idUsuario AND us.estado = 'Activa'
    LEFT JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
    ORDER BY u.fechaRegistro DESC;
END$$

-- Procedimiento: ActualizarUsuario
DROP PROCEDURE IF EXISTS `ActualizarUsuario`$$
CREATE PROCEDURE `ActualizarUsuario`(
    IN p_idUsuario INT,
    IN p_cedula VARCHAR(20),
    IN p_nombre VARCHAR(100),
    IN p_correo VARCHAR(100),
    IN p_contrasenna VARCHAR(255)
)
BEGIN
    IF p_contrasenna != '' THEN
        UPDATE usuarioBD SET cedula = p_cedula, nombreUsuario = p_nombre, 
               correo = p_correo, contrasenna = p_contrasenna
        WHERE idUsuario = p_idUsuario;
    ELSE
        UPDATE usuarioBD SET cedula = p_cedula, nombreUsuario = p_nombre, correo = p_correo
        WHERE idUsuario = p_idUsuario;
    END IF;
    SELECT 'Usuario actualizado exitosamente' AS mensaje;
END$$

-- Procedimiento: CambiarEstadoUsuario
DROP PROCEDURE IF EXISTS `CambiarEstadoUsuario`$$
CREATE PROCEDURE `CambiarEstadoUsuario`(IN p_idUsuario INT)
BEGIN
    UPDATE usuarioBD SET activo = NOT activo WHERE idUsuario = p_idUsuario;
    SELECT 'Estado actualizado exitosamente' AS mensaje;
END$$

-- Procedimiento: ConsultarSuscripciones
DROP PROCEDURE IF EXISTS `ConsultarSuscripciones`$$
CREATE PROCEDURE `ConsultarSuscripciones`()
BEGIN
    SELECT idSuscripcion AS ConsecutivoSuscripcion, tipoSuscripcion, 
           precio, descripcion, limiteFavoritos
    FROM suscripcion ORDER BY precio;
END$$

-- Procedimiento: CambiarSuscripcion
DROP PROCEDURE IF EXISTS `CambiarSuscripcion`$$
CREATE PROCEDURE `CambiarSuscripcion`(
    IN p_idUsuario INT,
    IN p_idSuscripcion INT
)
BEGIN
    UPDATE usuarioSuscripcion SET estado = 'Cancelada'
    WHERE idUsuario = p_idUsuario AND estado = 'Activa';
    
    INSERT INTO usuarioSuscripcion (idUsuario, idSuscripcion, fechaInicio, fechaVencimiento, estado)
    VALUES (p_idUsuario, p_idSuscripcion, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'Activa');
    
    SELECT 'Suscripción actualizada exitosamente' AS mensaje;
END$$

-- Procedimiento: ConsultarFavoritos
DROP PROCEDURE IF EXISTS `ConsultarFavoritos`$$
CREATE PROCEDURE `ConsultarFavoritos`(IN p_idUsuario INT)
BEGIN
    SELECT 
        f.idFavorito AS ConsecutivoFavorito,
        c.idContenido AS ConsecutivoContenido,
        c.titulo AS Titulo,
        c.descripcion AS Descripcion,
        c.duracion AS Duracion,
        c.imagen AS Imagen,
        c.calificacionEdad AS CalificacionEdad,
        f.fechaAgregado
    FROM favoritos f
    INNER JOIN contenido c ON f.idContenido = c.idContenido
    WHERE f.idUsuario = p_idUsuario
    ORDER BY f.fechaAgregado DESC;
END$$

-- Procedimiento: AgregarFavorito
DROP PROCEDURE IF EXISTS `AgregarFavorito`$$
CREATE PROCEDURE `AgregarFavorito`(
    IN p_idUsuario INT,
    IN p_idContenido INT
)
BEGIN
    DECLARE v_limite INT;
    DECLARE v_actuales INT;
    
    SELECT COALESCE(s.limiteFavoritos, 5) INTO v_limite
    FROM usuarioBD u
    LEFT JOIN usuarioSuscripcion us ON u.idUsuario = us.idUsuario AND us.estado = 'Activa'
    LEFT JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
    WHERE u.idUsuario = p_idUsuario;
    
    SELECT COUNT(*) INTO v_actuales FROM favoritos WHERE idUsuario = p_idUsuario;
    
    IF v_actuales >= v_limite THEN
        SELECT 'Has alcanzado el límite de favoritos. Actualiza tu suscripción.' AS mensaje, 0 AS success;
    ELSE
        INSERT IGNORE INTO favoritos (idUsuario, idContenido) VALUES (p_idUsuario, p_idContenido);
        SELECT 'Agregado a favoritos' AS mensaje, 1 AS success;
    END IF;
END$$

-- Procedimiento: EliminarFavorito
DROP PROCEDURE IF EXISTS `EliminarFavorito`$$
CREATE PROCEDURE `EliminarFavorito`(
    IN p_idUsuario INT,
    IN p_idContenido INT
)
BEGIN
    DELETE FROM favoritos WHERE idUsuario = p_idUsuario AND idContenido = p_idContenido;
    SELECT 'Eliminado de favoritos' AS mensaje, 1 AS success;
END$$

-- Procedimiento: VerificarFavorito
DROP PROCEDURE IF EXISTS `VerificarFavorito`$$
CREATE PROCEDURE `VerificarFavorito`(
    IN p_idUsuario INT,
    IN p_idContenido INT
)
BEGIN
    SELECT COUNT(*) AS esFavorito
    FROM favoritos WHERE idUsuario = p_idUsuario AND idContenido = p_idContenido;
END$$

-- Procedimiento: BuscarContenido
DROP PROCEDURE IF EXISTS `BuscarContenido`$$
CREATE PROCEDURE `BuscarContenido`(
    IN p_busqueda VARCHAR(200),
    IN p_idGenero INT,
    IN p_idCategoria INT
)
BEGIN
    SELECT DISTINCT
        c.idContenido AS ConsecutivoContenido,
        c.titulo AS Titulo,
        c.descripcion AS Descripcion,
        c.duracion AS Duracion,
        c.imagen AS Imagen,
        c.calificacionEdad AS CalificacionEdad
    FROM contenido c
    LEFT JOIN contenidoGenero cg ON c.idContenido = cg.idContenido
    LEFT JOIN contenidoCategoria cc ON c.idContenido = cc.idContenido
    WHERE c.activo = 1
      AND (p_busqueda = '' OR c.titulo LIKE CONCAT('%', p_busqueda, '%') OR c.descripcion LIKE CONCAT('%', p_busqueda, '%'))
      AND (p_idGenero = 0 OR cg.idGenero = p_idGenero)
      AND (p_idCategoria = 0 OR cc.idCategoria = p_idCategoria)
    GROUP BY c.idContenido
    ORDER BY c.fechaCreacion DESC;
END$$

-- Procedimiento: ConsultarContenidoPorId
DROP PROCEDURE IF EXISTS `ConsultarContenidoPorId`$$
CREATE PROCEDURE `ConsultarContenidoPorId`(IN p_idContenido INT)
BEGIN
    SELECT 
        c.idContenido AS ConsecutivoContenido,
        c.titulo AS Titulo,
        c.descripcion AS Descripcion,
        c.duracion AS Duracion,
        c.imagen AS Imagen,
        c.trailer AS Trailer,
        c.video_archivo AS VideoArchivo,
        c.calificacionEdad AS CalificacionEdad,
        c.fechaPublicacion,
        c.activo,
        GROUP_CONCAT(DISTINCT g.nombreGenero ORDER BY g.nombreGenero SEPARATOR ', ') AS Generos,
        GROUP_CONCAT(DISTINCT cat.nombreCategoria ORDER BY cat.nombreCategoria SEPARATOR ', ') AS Categorias
    FROM contenido c
    LEFT JOIN contenidoGenero cg ON c.idContenido = cg.idContenido
    LEFT JOIN genero g ON cg.idGenero = g.idGenero
    LEFT JOIN contenidoCategoria cc ON c.idContenido = cc.idContenido
    LEFT JOIN categoria cat ON cc.idCategoria = cat.idCategoria
    WHERE c.idContenido = p_idContenido
    GROUP BY c.idContenido;
END$$

-- Procedimiento: ConsultarIndicadores
DROP PROCEDURE IF EXISTS `ConsultarIndicadores`$$
CREATE PROCEDURE `ConsultarIndicadores`()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM usuarioBD WHERE activo = 1) AS totalUsuarios,
        (SELECT COUNT(*) FROM contenido WHERE activo = 1) AS totalContenido,
        (SELECT COUNT(*) FROM usuarioSuscripcion WHERE estado = 'Activa' AND idSuscripcion > 1) AS totalPremium,
        (SELECT COUNT(*) FROM favoritos) AS totalFavoritos;
END$$

-- Procedimiento: ActualizarPerfil
DROP PROCEDURE IF EXISTS `ActualizarPerfil`$$
CREATE PROCEDURE `ActualizarPerfil`(
    IN p_idUsuario INT,
    IN p_cedula VARCHAR(20),
    IN p_nombre VARCHAR(100),
    IN p_correo VARCHAR(100)
)
BEGIN
    UPDATE usuarioBD SET cedula = p_cedula, nombreUsuario = p_nombre, correo = p_correo
    WHERE idUsuario = p_idUsuario;
    SELECT 'Perfil actualizado exitosamente' AS mensaje;
END$$

-- Procedimiento: ActualizarContrasenna
DROP PROCEDURE IF EXISTS `ActualizarContrasenna`$$
CREATE PROCEDURE `ActualizarContrasenna`(
    IN p_idUsuario INT,
    IN p_contrasenna VARCHAR(255)
)
BEGIN
    UPDATE usuarioBD SET contrasenna = p_contrasenna WHERE idUsuario = p_idUsuario;
    SELECT 'Contraseña actualizada exitosamente' AS mensaje;
END$$

-- Procedimiento: ValidarCorreo
DROP PROCEDURE IF EXISTS `ValidarCorreo`$$
CREATE PROCEDURE `ValidarCorreo`(IN p_correo VARCHAR(100))
BEGIN
    SELECT idUsuario AS ConsecutivoUsuario, nombreUsuario AS Nombre, correo AS CorreoElectronico
    FROM usuarioBD WHERE correo = p_correo LIMIT 1;
END$$

-- Procedimiento: CrearTokenRecuperacion
DROP PROCEDURE IF EXISTS `CrearTokenRecuperacion`$$
CREATE PROCEDURE `CrearTokenRecuperacion`(
    IN p_idUsuario INT,
    IN p_token VARCHAR(255),
    IN p_fechaExpiracion DATETIME
)
BEGIN
    INSERT INTO recuperacioncontrasenna (idUsuario, token, fechaExpiracion, usado)
    VALUES (p_idUsuario, p_token, p_fechaExpiracion, 0);
    SELECT 'Token de recuperación creado correctamente' AS mensaje;
END$$

-- Procedimiento: ValidarTokenRecuperacion
DROP PROCEDURE IF EXISTS `ValidarTokenRecuperacion`$$
CREATE PROCEDURE `ValidarTokenRecuperacion`(IN p_token VARCHAR(255))
BEGIN
    SELECT r.idRecuperacion, r.idUsuario, u.correo, u.nombreUsuario
    FROM recuperacioncontrasenna r
    INNER JOIN usuariobd u ON r.idUsuario = u.idUsuario
    WHERE r.token = p_token AND r.usado = 0 AND r.fechaExpiracion > NOW()
    LIMIT 1;
END$$

-- Procedimiento: MarcarTokenUsado
DROP PROCEDURE IF EXISTS `MarcarTokenUsado`$$
CREATE PROCEDURE `MarcarTokenUsado`(IN p_idRecuperacion INT)
BEGIN
    UPDATE recuperacioncontrasenna SET usado = 1
    WHERE idRecuperacion = p_idRecuperacion AND usado = 0;
    SELECT 'Token marcado como usado correctamente' AS mensaje;
END$$

-- Procedimiento: RegistrarError
DROP PROCEDURE IF EXISTS `RegistrarError`$$
CREATE PROCEDURE `RegistrarError`(IN p_mensaje TEXT)
BEGIN
    INSERT INTO logErrores (mensaje) VALUES (p_mensaje);
END$$

-- Procedimientos adicionales de estadísticas
DROP PROCEDURE IF EXISTS `ContarTotalUsuarios`$$
CREATE PROCEDURE `ContarTotalUsuarios`()
BEGIN
    SELECT COUNT(*) AS total FROM usuarioBD;
END$$

DROP PROCEDURE IF EXISTS `ContarTotalPeliculas`$$
CREATE PROCEDURE `ContarTotalPeliculas`()
BEGIN
    SELECT COUNT(*) AS total FROM contenido WHERE activo = 1;
END$$

DROP PROCEDURE IF EXISTS `ContarTotalGeneros`$$
CREATE PROCEDURE `ContarTotalGeneros`()
BEGIN
    SELECT COUNT(*) AS total FROM genero;
END$$

DROP PROCEDURE IF EXISTS `ContarTotalCategorias`$$
CREATE PROCEDURE `ContarTotalCategorias`()
BEGIN
    SELECT COUNT(*) AS total FROM categoria;
END$$

DROP PROCEDURE IF EXISTS `ContarUsuariosRegistradosHoy`$$
CREATE PROCEDURE `ContarUsuariosRegistradosHoy`()
BEGIN
    SELECT COUNT(*) AS hoy FROM usuarioBD WHERE DATE(fechaRegistro) = CURDATE();
END$$

DROP PROCEDURE IF EXISTS `ContarUsuariosActivosHoy`$$
CREATE PROCEDURE `ContarUsuariosActivosHoy`()
BEGIN
    SELECT COUNT(*) AS total FROM usuarioBD WHERE activo = 1;
END$$

DROP PROCEDURE IF EXISTS `ContarPeliculasNuevasMes`$$
CREATE PROCEDURE `ContarPeliculasNuevasMes`()
BEGIN
    SELECT COUNT(*) AS total FROM contenido
    WHERE MONTH(fechaPublicacion) = MONTH(CURDATE())
      AND YEAR(fechaPublicacion) = YEAR(CURDATE())
      AND activo = 1;
END$$

DROP PROCEDURE IF EXISTS `ContarUsuariosPremium`$$
CREATE PROCEDURE `ContarUsuariosPremium`()
BEGIN
    SELECT COUNT(DISTINCT us.idUsuario) AS total
    FROM usuarioSuscripcion us
    INNER JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
    WHERE us.estado = 'Activa' AND s.tipoSuscripcion = 'Premium';
END$$

DROP PROCEDURE IF EXISTS `ContarUsuariosGratis`$$
CREATE PROCEDURE `ContarUsuariosGratis`()
BEGIN
    SELECT COUNT(DISTINCT us.idUsuario) AS total
    FROM usuarioSuscripcion us
    INNER JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
    WHERE us.estado = 'Activa' AND s.tipoSuscripcion = 'Gratis';
END$$

DROP PROCEDURE IF EXISTS `ObtenerUsuariosRecientes`$$
CREATE PROCEDURE `ObtenerUsuariosRecientes`(IN p_limite INT)
BEGIN
    SELECT 
        u.idUsuario AS ConsecutivoUsuario,
        u.nombreUsuario AS Nombre,
        u.correo AS CorreoElectronico,
        ur.idRol AS ConsecutivoPerfil,
        r.nombreRol AS rol,
        DATE_FORMAT(u.fechaRegistro, '%d/%m/%Y') AS fechaRegistro
    FROM usuariobd u
    LEFT JOIN usuariorol ur ON u.idUsuario = ur.idUsuario
    LEFT JOIN rol r ON ur.idRol = r.idRol
    ORDER BY u.fechaRegistro DESC
    LIMIT p_limite;
END$$

DROP PROCEDURE IF EXISTS `ObtenerPeliculasPopulares`$$
CREATE PROCEDURE `ObtenerPeliculasPopulares`(IN p_limite INT)
BEGIN
    SELECT 
        c.idContenido,
        c.titulo,
        COUNT(f.idFavorito) AS favoritos,
        COALESCE(GROUP_CONCAT(DISTINCT g.nombreGenero SEPARATOR ', '), 'Sin género') AS genero
    FROM contenido c
    LEFT JOIN favoritos f ON c.idContenido = f.idContenido
    LEFT JOIN contenidoGenero cg ON c.idContenido = cg.idContenido
    LEFT JOIN genero g ON cg.idGenero = g.idGenero
    WHERE c.activo = 1
    GROUP BY c.idContenido, c.titulo
    ORDER BY favoritos DESC
    LIMIT p_limite;
END$$

DROP PROCEDURE IF EXISTS `ContarFavoritosUsuario`$$
CREATE PROCEDURE `ContarFavoritosUsuario`(IN p_idUsuario INT)
BEGIN
    SELECT COUNT(*) AS total FROM favoritos WHERE idUsuario = p_idUsuario;
END$$

DROP PROCEDURE IF EXISTS `ObtenerSuscripcionActual`$$
CREATE PROCEDURE `ObtenerSuscripcionActual`(IN p_idUsuario INT)
BEGIN
    SELECT 
        s.idSuscripcion,
        s.tipoSuscripcion,
        s.limiteFavoritos,
        us.fechaInicio,
        us.fechaVencimiento,
        us.estado
    FROM usuarioSuscripcion us
    INNER JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
    WHERE us.idUsuario = p_idUsuario AND us.estado = 'Activa'
    LIMIT 1;
END$$

DROP PROCEDURE IF EXISTS `ValidarContrasennaActual`$$
CREATE PROCEDURE `ValidarContrasennaActual`(
    IN p_idUsuario INT,
    IN p_contrasenna VARCHAR(255)
)
BEGIN
    SELECT COUNT(*) AS total FROM usuarioBD
    WHERE idUsuario = p_idUsuario AND contrasenna = p_contrasenna;
END$$

DROP PROCEDURE IF EXISTS `ValidarCorreoUnicoPerfil`$$
CREATE PROCEDURE `ValidarCorreoUnicoPerfil`(
    IN p_correo VARCHAR(100),
    IN p_idUsuario INT
)
BEGIN
    SELECT COUNT(*) AS total FROM usuarioBD
    WHERE correo = p_correo AND idUsuario != p_idUsuario;
END$$

DROP PROCEDURE IF EXISTS `ValidarCedulaUnicaPerfil`$$
CREATE PROCEDURE `ValidarCedulaUnicaPerfil`(
    IN p_cedula VARCHAR(20),
    IN p_idUsuario INT
)
BEGIN
    SELECT COUNT(*) AS total FROM usuarioBD
    WHERE cedula = p_cedula AND idUsuario != p_idUsuario;
END$$

DROP PROCEDURE IF EXISTS `BuscarEnFavoritos`$$
CREATE PROCEDURE `BuscarEnFavoritos`(
    IN p_idUsuario INT,
    IN p_busqueda VARCHAR(200)
)
BEGIN
    SELECT 
        c.idContenido,
        c.titulo,
        c.descripcion,
        c.imagen,
        c.calificacionEdad
    FROM favoritos f
    INNER JOIN contenido c ON f.idContenido = c.idContenido
    WHERE f.idUsuario = p_idUsuario
      AND (c.titulo LIKE CONCAT('%', p_busqueda, '%') OR c.descripcion LIKE CONCAT('%', p_busqueda, '%'))
    ORDER BY f.fechaAgregado DESC;
END$$

DROP PROCEDURE IF EXISTS `ConsultarFavoritosPaginados`$$
CREATE PROCEDURE `ConsultarFavoritosPaginados`(
    IN p_idUsuario INT,
    IN p_limite INT,
    IN p_offset INT
)
BEGIN
    SELECT 
        c.idContenido,
        c.titulo,
        c.imagen,
        c.calificacionEdad,
        f.fechaAgregado
    FROM favoritos f
    INNER JOIN contenido c ON f.idContenido = c.idContenido
    WHERE f.idUsuario = p_idUsuario
    ORDER BY f.fechaAgregado DESC
    LIMIT p_limite OFFSET p_offset;
END$$

DROP PROCEDURE IF EXISTS `LimpiarFavoritosInactivos`$$
CREATE PROCEDURE `LimpiarFavoritosInactivos`()
BEGIN
    DELETE f FROM favoritos f
    INNER JOIN contenido c ON f.idContenido = c.idContenido
    WHERE c.activo = 0;
    SELECT ROW_COUNT() AS eliminados;
END$$

DROP PROCEDURE IF EXISTS `ObtenerIdsFavoritos`$$
CREATE PROCEDURE `ObtenerIdsFavoritos`(IN p_idUsuario INT)
BEGIN
    SELECT idContenido FROM favoritos WHERE idUsuario = p_idUsuario;
END$$

DROP PROCEDURE IF EXISTS `ObtenerFavoritosPorTipo`$$
CREATE PROCEDURE `ObtenerFavoritosPorTipo`(
    IN p_idUsuario INT,
    IN p_idCategoria INT
)
BEGIN
    SELECT 
        c.idContenido,
        c.titulo,
        c.imagen,
        c.calificacionEdad
    FROM favoritos f
    INNER JOIN contenido c ON f.idContenido = c.idContenido
    INNER JOIN contenidocategoria cc ON c.idContenido = cc.idContenido
    WHERE f.idUsuario = p_idUsuario AND cc.idCategoria = p_idCategoria
    ORDER BY f.fechaAgregado DESC;
END$$

DELIMITER ;

