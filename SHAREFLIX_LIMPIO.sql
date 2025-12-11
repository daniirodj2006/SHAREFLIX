-- ========================================
-- SCRIPT DE BASE DE DATOS - SHAREFLIX
-- Versión limpia y optimizada
-- ========================================

-- Crear y usar la base de datos
CREATE DATABASE IF NOT EXISTS `shareflix_bd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shareflix_bd`;

-- Configuración inicial
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ========================================
-- ELIMINAR TABLAS EXISTENTES (en orden correcto)
-- ========================================

DROP TABLE IF EXISTS `favoritos`;
DROP TABLE IF EXISTS `recuperacioncontrasenna`;
DROP TABLE IF EXISTS `usuariosuscripcion`;
DROP TABLE IF EXISTS `contenidocategoria`;
DROP TABLE IF EXISTS `contenidogenero`;
DROP TABLE IF EXISTS `usuariorol`;
DROP TABLE IF EXISTS `contenido`;
DROP TABLE IF EXISTS `categoria`;
DROP TABLE IF EXISTS `genero`;
DROP TABLE IF EXISTS `suscripcion`;
DROP TABLE IF EXISTS `usuariobd`;
DROP TABLE IF EXISTS `rol`;
DROP TABLE IF EXISTS `logerrores`;

-- ========================================
-- TABLA: ROL
-- ========================================

CREATE TABLE `rol` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `nombreRol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idRol`),
  UNIQUE KEY `nombreRol` (`nombreRol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `rol` VALUES 
(1,'Administrador','Acceso completo al sistema'),
(2,'Cliente','Usuario normal de la plataforma');

-- ========================================
-- TABLA: SUSCRIPCION
-- ========================================

CREATE TABLE `suscripcion` (
  `idSuscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `tipoSuscripcion` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `limiteFavoritos` int(11) DEFAULT 5,
  PRIMARY KEY (`idSuscripcion`),
  UNIQUE KEY `tipoSuscripcion` (`tipoSuscripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `suscripcion` VALUES 
(1,'Gratis',0.00,'Plan gratuito con acceso limitado',5),
(2,'Premium',9.99,'Plan premium con acceso completo',999999),
(3,'VIP',19.99,'Plan VIP con beneficios exclusivos',999999);

-- ========================================
-- TABLA: USUARIOBD
-- ========================================

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuariobd` VALUES 
(1,'111111111','Admin','admin@shareflix.com','1234',1,'2025-11-18 18:53:55'),
(4,'207290643','Daniela Rodriguez','dani@gmail.com','1234',1,'2025-11-19 09:31:45');

-- ========================================
-- TABLA: USUARIOROL
-- ========================================

CREATE TABLE `usuariorol` (
  `idUsuarioRol` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idRol` int(11) NOT NULL,
  PRIMARY KEY (`idUsuarioRol`),
  UNIQUE KEY `idUsuario` (`idUsuario`,`idRol`),
  KEY `idRol` (`idRol`),
  CONSTRAINT `usuariorol_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariobd` (`idUsuario`),
  CONSTRAINT `usuariorol_ibfk_2` FOREIGN KEY (`idRol`) REFERENCES `rol` (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuariorol` VALUES 
(1,1,1),
(3,4,2);

-- ========================================
-- TABLA: USUARIOSUSCRIPCION
-- ========================================

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuariosuscripcion` VALUES 
(1,1,2,'2025-11-18','2026-11-18','Activa','2025-11-18 18:54:42'),
(3,4,1,'2025-11-19','2026-11-19','Activa','2025-11-19 09:31:45');

-- ========================================
-- TABLA: RECUPERACIONCONTRASENNA
-- ========================================

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

-- ========================================
-- TABLA: GENERO
-- ========================================

CREATE TABLE `genero` (
  `idGenero` int(11) NOT NULL AUTO_INCREMENT,
  `nombreGenero` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idGenero`),
  UNIQUE KEY `nombreGenero` (`nombreGenero`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `genero` VALUES 
(1,'Acción','Películas de acción y aventura'),
(2,'Comedia','Películas de comedia'),
(5,'Ciencia Ficción','Películas de ciencia ficción'),
(7,'Fantasía','Películas fantásticas'),
(8,'Animación','Películas animadas'),
(10,'Thriller','Películas de suspenso');

-- ========================================
-- TABLA: CATEGORIA
-- ========================================

CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombreCategoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idCategoria`),
  UNIQUE KEY `nombreCategoria` (`nombreCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categoria` VALUES 
(1,'Destacadas','Contenido destacado de la plataforma.'),
(2,'Más Vistas','Contenido más popular'),
(3,'Nuevas','Últimas adiciones'),
(4,'Top 10','Las 10 más populares'),
(5,'Recomendadas','Recomendaciones personalizadas');

-- ========================================
-- TABLA: CONTENIDO
-- ========================================

CREATE TABLE `contenido` (
  `idContenido` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fechaPublicacion` date DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL COMMENT 'Duración en minutos',
  `imagen` varchar(255) DEFAULT NULL COMMENT 'Ruta del poster',
  `trailer` varchar(255) DEFAULT NULL COMMENT 'URL del trailer',
  `calificacionEdad` varchar(10) DEFAULT NULL COMMENT 'ATP, +13, +16, +18',
  `activo` tinyint(1) DEFAULT 1,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idContenido`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contenido` VALUES 
(1,'Avatar: El Camino del Agua','Jake Sully y Neytiri han formado una familia y hacen todo lo posible por permanecer juntos.','2022-12-16',192,'691e86daabfea_1763608282.jpg','','ATP',1,'2025-11-18 19:23:36'),
(2,'Spider-Man: A Través del Universo','Miles Morales regresa para una aventura épica.','2023-06-02',140,'691e41b6b64a3_1763590582.jpg','','ATP',1,'2025-11-18 19:23:36'),
(3,'Guardianes de la Galaxia Vol. 3','Peter Quill debe reunir a su equipo para defender el universo.','2023-05-05',150,'691e4213d1bf2_1763590675.jpg','','+16',1,'2025-11-18 19:23:36'),
(4,'El Gato con Botas: El Último Deseo','El Gato descubre que ha consumido ocho de sus nueve vidas.','2022-12-21',102,'691e4298861a2_1763590808.jpg','','ATP',1,'2025-11-18 19:23:36'),
(5,'John Wick 4','John Wick busca un camino para derrotar a la Mesa Alta.','2023-03-24',169,'691e42c361c66_1763590851.jpg','','+16',1,'2025-11-18 19:23:36'),
(18,'Película PRUEBA1','Esta es una prueba de inserción directa','2025-11-20',120,'691e7e6ad5edd_1763606122.jpg','','ATP',1,'2025-11-19 20:19:23'),
(26,'Barbie','Película de fantasía y comedia donde Barbie (Margot Robbie) vive en Barbieland, un mundo perfecto y rosa, hasta que empieza a cuestionarse su existencia y a tener pensamientos "raros".','2023-03-05',120,'691e7b9fb54ef_1763605407.jpg','','+13',1,'2025-11-19 20:23:27');

-- ========================================
-- TABLA: CONTENIDOGENERO
-- ========================================

CREATE TABLE `contenidogenero` (
  `idContenidoGenero` int(11) NOT NULL AUTO_INCREMENT,
  `idContenido` int(11) NOT NULL,
  `idGenero` int(11) NOT NULL,
  PRIMARY KEY (`idContenidoGenero`),
  UNIQUE KEY `idContenido` (`idContenido`,`idGenero`),
  KEY `idGenero` (`idGenero`),
  CONSTRAINT `contenidogenero_ibfk_1` FOREIGN KEY (`idContenido`) REFERENCES `contenido` (`idContenido`) ON DELETE CASCADE,
  CONSTRAINT `contenidogenero_ibfk_2` FOREIGN KEY (`idGenero`) REFERENCES `genero` (`idGenero`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contenidogenero` VALUES 
(35,1,1),(36,1,5),(15,2,2),(14,2,7),(13,2,8),(23,3,1),(24,3,5),(19,4,7),(18,4,8),(20,5,1),(21,5,7),(22,5,10),(33,18,5),(32,18,7),(28,26,2),(27,26,7);

-- ========================================
-- TABLA: CONTENIDOCATEGORIA
-- ========================================

CREATE TABLE `contenidocategoria` (
  `idContenidoCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `idContenido` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  PRIMARY KEY (`idContenidoCategoria`),
  UNIQUE KEY `idContenido` (`idContenido`,`idCategoria`),
  KEY `idCategoria` (`idCategoria`),
  CONSTRAINT `contenidocategoria_ibfk_1` FOREIGN KEY (`idContenido`) REFERENCES `contenido` (`idContenido`) ON DELETE CASCADE,
  CONSTRAINT `contenidocategoria_ibfk_2` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contenidocategoria` VALUES 
(28,1,3),(29,1,5),(14,2,1),(15,2,2),(19,3,4),(17,4,3),(18,5,1),(25,18,2),(26,18,5),(21,26,1);

-- ========================================
-- TABLA: FAVORITOS
-- ========================================

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `favoritos` VALUES 
(1,4,2,'2025-11-19 10:19:03'),
(2,4,3,'2025-11-19 10:19:03'),
(3,4,5,'2025-11-19 10:19:03');

-- ========================================
-- TABLA: LOGERRORES
-- ========================================

CREATE TABLE `logerrores` (
  `idError` int(11) NOT NULL AUTO_INCREMENT,
  `mensaje` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idError`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- PROCEDIMIENTOS ALMACENADOS
-- ========================================

-- Restaurar configuración de delimitador
DELIMITER $$

-- PROCEDIMIENTO: AgregarCategoria
DROP PROCEDURE IF EXISTS `AgregarCategoria`$$
CREATE PROCEDURE `AgregarCategoria`(
    IN p_nombreCategoria VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO categoria (nombreCategoria, descripcion)
    VALUES (p_nombreCategoria, p_descripcion);
    SELECT 'Categoría agregada exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: AgregarCategoriaContenido
DROP PROCEDURE IF EXISTS `AgregarCategoriaContenido`$$
CREATE PROCEDURE `AgregarCategoriaContenido`(
    IN p_idContenido INT,
    IN p_idCategoria INT
)
BEGIN
    INSERT INTO contenidoCategoria (idContenido, idCategoria)
    VALUES (p_idContenido, p_idCategoria);
END$$

-- PROCEDIMIENTO: AgregarContenido
DROP PROCEDURE IF EXISTS `AgregarContenido`$$
CREATE PROCEDURE `AgregarContenido`(
    IN p_titulo VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_duracion INT,
    IN p_imagen VARCHAR(255),
    IN p_trailer VARCHAR(255),
    IN p_calificacionEdad VARCHAR(10),
    IN p_fechaPublicacion DATE
)
BEGIN
    INSERT INTO contenido (titulo, descripcion, duracion, imagen, trailer, calificacionEdad, fechaPublicacion)
    VALUES (p_titulo, p_descripcion, p_duracion, p_imagen, p_trailer, p_calificacionEdad, p_fechaPublicacion);
    SELECT LAST_INSERT_ID() AS idContenido, 'Contenido agregado exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: AgregarFavorito
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
    
    SELECT COUNT(*) INTO v_actuales
    FROM favoritos
    WHERE idUsuario = p_idUsuario;
    
    IF v_actuales >= v_limite THEN
        SELECT 'Has alcanzado el límite de favoritos. Actualiza tu suscripción.' AS mensaje, 0 AS success;
    ELSE
        INSERT IGNORE INTO favoritos (idUsuario, idContenido)
        VALUES (p_idUsuario, p_idContenido);
        SELECT 'Agregado a favoritos' AS mensaje, 1 AS success;
    END IF;
END$$

-- PROCEDIMIENTO: AgregarGenero
DROP PROCEDURE IF EXISTS `AgregarGenero`$$
CREATE PROCEDURE `AgregarGenero`(
    IN p_nombreGenero VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO genero (nombreGenero, descripcion)
    VALUES (p_nombreGenero, p_descripcion);
    SELECT 'Género agregado exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: AgregarGeneroContenido
DROP PROCEDURE IF EXISTS `AgregarGeneroContenido`$$
CREATE PROCEDURE `AgregarGeneroContenido`(
    IN p_idContenido INT,
    IN p_idGenero INT
)
BEGIN
    INSERT INTO contenidoGenero (idContenido, idGenero)
    VALUES (p_idContenido, p_idGenero);
END$$

-- PROCEDIMIENTO: ActualizarCategoria
DROP PROCEDURE IF EXISTS `ActualizarCategoria`$$
CREATE PROCEDURE `ActualizarCategoria`(
    IN p_idCategoria INT,
    IN p_nombreCategoria VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE categoria
    SET nombreCategoria = p_nombreCategoria,
        descripcion = p_descripcion
    WHERE idCategoria = p_idCategoria;
    SELECT 'Categoría actualizada exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: ActualizarContenido
DROP PROCEDURE IF EXISTS `ActualizarContenido`$$
CREATE PROCEDURE `ActualizarContenido`(
    IN p_idContenido INT,
    IN p_titulo VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_duracion INT,
    IN p_imagen VARCHAR(255),
    IN p_trailer VARCHAR(255),
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
        calificacionEdad = p_calificacionEdad,
        fechaPublicacion = p_fechaPublicacion
    WHERE idContenido = p_idContenido;
    SELECT 'Contenido actualizado exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: ActualizarGenero
DROP PROCEDURE IF EXISTS `ActualizarGenero`$$
CREATE PROCEDURE `ActualizarGenero`(
    IN p_idGenero INT,
    IN p_nombreGenero VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE genero
    SET nombreGenero = p_nombreGenero,
        descripcion = p_descripcion
    WHERE idGenero = p_idGenero;
    SELECT 'Género actualizado exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: CambiarEstadoContenido
DROP PROCEDURE IF EXISTS `CambiarEstadoContenido`$$
CREATE PROCEDURE `CambiarEstadoContenido`(IN p_idContenido INT)
BEGIN
    UPDATE contenido
    SET activo = NOT activo
    WHERE idContenido = p_idContenido;
    SELECT 'Estado cambiado exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: CambiarEstadoUsuario
DROP PROCEDURE IF EXISTS `CambiarEstadoUsuario`$$
CREATE PROCEDURE `CambiarEstadoUsuario`(IN p_idUsuario INT)
BEGIN
    UPDATE usuarioBD
    SET activo = NOT activo
    WHERE idUsuario = p_idUsuario;
    SELECT 'Estado del usuario cambiado exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: CambiarSuscripcion
DROP PROCEDURE IF EXISTS `CambiarSuscripcion`$$
CREATE PROCEDURE `CambiarSuscripcion`(
    IN p_idUsuario INT,
    IN p_idSuscripcion INT
)
BEGIN
    UPDATE usuarioSuscripcion
    SET estado = 'Cancelada'
    WHERE idUsuario = p_idUsuario AND estado = 'Activa';
    
    INSERT INTO usuarioSuscripcion (idUsuario, idSuscripcion, fechaInicio, fechaVencimiento, estado)
    VALUES (p_idUsuario, p_idSuscripcion, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'Activa');
    
    SELECT 'Suscripción actualizada exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: ConsultarCategorias
DROP PROCEDURE IF EXISTS `ConsultarCategorias`$$
CREATE PROCEDURE `ConsultarCategorias`()
BEGIN
    SELECT 
        idCategoria AS ConsecutivoCategoria,
        nombreCategoria AS Nombre,
        descripcion AS Descripcion
    FROM categoria
    ORDER BY nombreCategoria;
END$$

-- PROCEDIMIENTO: ConsultarContenido
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

-- PROCEDIMIENTO: ConsultarContenidoPorId
DROP PROCEDURE IF EXISTS `ConsultarContenidoPorId`$$
CREATE PROCEDURE `ConsultarContenidoPorId`(IN p_idContenido INT)
BEGIN
    SELECT 
        idContenido AS ConsecutivoContenido,
        titulo AS Titulo,
        descripcion AS Descripcion,
        duracion AS Duracion,
        imagen AS Imagen,
        trailer AS Trailer,
        calificacionEdad AS CalificacionEdad,
        fechaPublicacion,
        activo
    FROM contenido
    WHERE idContenido = p_idContenido;
END$$

-- PROCEDIMIENTO: ConsultarFavoritos
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

-- PROCEDIMIENTO: ConsultarGeneros
DROP PROCEDURE IF EXISTS `ConsultarGeneros`$$
CREATE PROCEDURE `ConsultarGeneros`()
BEGIN
    SELECT 
        idGenero AS ConsecutivoGenero,
        nombreGenero AS Nombre,
        descripcion AS Descripcion
    FROM genero
    ORDER BY nombreGenero;
END$$

-- PROCEDIMIENTO: ConsultarIndicadores
DROP PROCEDURE IF EXISTS `ConsultarIndicadores`$$
CREATE PROCEDURE `ConsultarIndicadores`()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM usuarioBD WHERE activo = 1) AS totalUsuarios,
        (SELECT COUNT(*) FROM contenido WHERE activo = 1) AS totalContenido,
        (SELECT COUNT(*) FROM usuarioSuscripcion WHERE estado = 'Activa' AND idSuscripcion > 1) AS totalPremium,
        (SELECT COUNT(*) FROM favoritos) AS totalFavoritos;
END$$

-- PROCEDIMIENTO: ConsultarSuscripciones
DROP PROCEDURE IF EXISTS `ConsultarSuscripciones`$$
CREATE PROCEDURE `ConsultarSuscripciones`()
BEGIN
    SELECT 
        idSuscripcion AS ConsecutivoSuscripcion,
        tipoSuscripcion,
        precio,
        descripcion,
        limiteFavoritos
    FROM suscripcion
    ORDER BY precio;
END$$

-- PROCEDIMIENTO: ConsultarUsuarios
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

-- PROCEDIMIENTO: EliminarCategoria
DROP PROCEDURE IF EXISTS `EliminarCategoria`$$
CREATE PROCEDURE `EliminarCategoria`(IN p_idCategoria INT)
BEGIN
    DELETE FROM categoria WHERE idCategoria = p_idCategoria;
    SELECT 'Categoría eliminada exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: EliminarCategoriasContenido
DROP PROCEDURE IF EXISTS `EliminarCategoriasContenido`$$
CREATE PROCEDURE `EliminarCategoriasContenido`(IN p_idContenido INT)
BEGIN
    DELETE FROM contenidoCategoria WHERE idContenido = p_idContenido;
END$$

-- PROCEDIMIENTO: EliminarContenido
DROP PROCEDURE IF EXISTS `EliminarContenido`$$
CREATE PROCEDURE `EliminarContenido`(IN p_idContenido INT)
BEGIN
    DELETE FROM contenido WHERE idContenido = p_idContenido;
    SELECT 'Contenido eliminado exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: EliminarFavorito
DROP PROCEDURE IF EXISTS `EliminarFavorito`$$
CREATE PROCEDURE `EliminarFavorito`(
    IN p_idUsuario INT,
    IN p_idContenido INT
)
BEGIN
    DELETE FROM favoritos
    WHERE idUsuario = p_idUsuario AND idContenido = p_idContenido;
    SELECT 'Eliminado de favoritos' AS mensaje, 1 AS success;
END$$

-- PROCEDIMIENTO: EliminarGenero
DROP PROCEDURE IF EXISTS `EliminarGenero`$$
CREATE PROCEDURE `EliminarGenero`(IN p_idGenero INT)
BEGIN
    DELETE FROM genero WHERE idGenero = p_idGenero;
    SELECT 'Género eliminado exitosamente' AS mensaje;
END$$

-- PROCEDIMIENTO: EliminarGenerosContenido
DROP PROCEDURE IF EXISTS `EliminarGenerosContenido`$$
CREATE PROCEDURE `EliminarGenerosContenido`(IN p_idContenido INT)
BEGIN
    DELETE FROM contenidoGenero WHERE idContenido = p_idContenido;
END$$

-- PROCEDIMIENTO: RegistrarError
DROP PROCEDURE IF EXISTS `RegistrarError`$$
CREATE PROCEDURE `RegistrarError`(IN p_mensaje TEXT)
BEGIN
    INSERT INTO logErrores (mensaje) VALUES (p_mensaje);
END$$

-- PROCEDIMIENTO: ValidarCorreo
DROP PROCEDURE IF EXISTS `ValidarCorreo`$$
CREATE PROCEDURE `ValidarCorreo`(IN p_correo VARCHAR(100))
BEGIN
    SELECT 
        idUsuario AS ConsecutivoUsuario,
        nombreUsuario AS Nombre,
        correo AS CorreoElectronico
    FROM usuarioBD
    WHERE correo = p_correo
    LIMIT 1;
END$$

-- PROCEDIMIENTO: ValidarCuenta
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

-- PROCEDIMIENTO: VerificarFavorito
DROP PROCEDURE IF EXISTS `VerificarFavorito`$$
CREATE PROCEDURE `VerificarFavorito`(
    IN p_idUsuario INT,
    IN p_idContenido INT
)
BEGIN
    SELECT COUNT(*) AS esFavorito
    FROM favoritos
    WHERE idUsuario = p_idUsuario AND idContenido = p_idContenido;
END$$

DELIMITER ;

-- ========================================
-- RESTAURAR CONFIGURACIÓN
-- ========================================

SET FOREIGN_KEY_CHECKS=1;

-- ========================================
-- SCRIPT COMPLETADO EXITOSAMENTE
-- ========================================
