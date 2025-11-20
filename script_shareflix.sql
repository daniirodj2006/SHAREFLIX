CREATE DATABASE IF NOT EXISTS `shareflix_bd`;
USE `shareflix_bd`;

 /*---------------------------------------------*/
CREATE TABLE `logerrores` (
  `idError` int(11) NOT NULL AUTO_INCREMENT,
  `mensaje` text NOT NULL,
  `fechaError` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idError`)
) ;

 /*---------------------------------------------*/
CREATE TABLE `usuariobd` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(20) NOT NULL,
  `nombreUsuario` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasenna` varchar(255) NOT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idUsuario`),
  UNIQUE KEY `cedula` (`cedula`),
  UNIQUE KEY `correo` (`correo`)
) ;

INSERT INTO `usuariobd` VALUES 
(1, 000000000,'admin', 'admin2@shareflix.com', 'admin123', NOW(), 1);

 /*---------------------------------------------*/
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
) ;

 /*---------------------------------------------*/
CREATE TABLE `rol` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `nombreRol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idRol`),
  UNIQUE KEY `nombreRol` (`nombreRol`)
);

INSERT INTO `rol` VALUES 
(1, 'Administrador', 'Acceso total al sistema'),
(2, 'Cliente', 'Usuario regular con acceso al catálogo');


 /*---------------------------------------------*/
CREATE TABLE `suscripcion` (
  `idSuscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `tipoSuscripcion` enum('Gratis','Premium') NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracionDias` int(11) DEFAULT 30,
  `limiteFavoritos` int(11) DEFAULT 5,
  PRIMARY KEY (`idSuscripcion`)
) ;

INSERT INTO `suscripcion` VALUES 
(1, 'Gratis', 0.00, 'Acceso básico al catálogo', 365),
(2, 'Premium', 9.99, 'Acceso completo sin publicidad', 30,99999);

 /*---------------------------------------------*/
CREATE TABLE `usuariorol` (
  `idUsuarioRol` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idRol` int(11) NOT NULL,
  PRIMARY KEY (`idUsuarioRol`),
  UNIQUE KEY `idUsuario` (`idUsuario`,`idRol`),
  KEY `idRol` (`idRol`),
  CONSTRAINT `usuariorol_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariobd` (`idUsuario`),
  CONSTRAINT `usuariorol_ibfk_2` FOREIGN KEY (`idRol`) REFERENCES `rol` (`idRol`)
) ;

 INSERT INTO `usuariorol` VALUES (1, 1, 1);
 
 /*---------------------------------------------*/
CREATE TABLE `usuariosuscripcion` (
  `idUsuarioSuscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idSuscripcion` int(11) NOT NULL,
  `fechaInicio` datetime DEFAULT current_timestamp(),
  `fechaVencimiento` datetime DEFAULT NULL,
  `estado` enum('Activa','Inactiva','Vencida') DEFAULT 'Activa',
  PRIMARY KEY (`idUsuarioSuscripcion`),
  KEY `idUsuario` (`idUsuario`),
  KEY `idSuscripcion` (`idSuscripcion`),
  CONSTRAINT `usuariosuscripcion_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariobd` (`idUsuario`),
  CONSTRAINT `usuariosuscripcion_ibfk_2` FOREIGN KEY (`idSuscripcion`) REFERENCES `suscripcion` (`idSuscripcion`)
) ;

INSERT INTO `usuariosuscripcion` VALUES 
(1, 1, 2, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 'Activa', 'Administrador');

 /*---------------------------------------------*/
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
  PRIMARY KEY (`idContenido`),
  KEY `idTipoContenido` (`idTipoContenido`),
 
) ;
INSERT INTO `contenido` VALUES 
(1, 'El Padrino', 'La historia de la familia Corleone', '1972-03-24', 175, 1, 'padrino.jpg', '', '+16', 1),
(2, 'Breaking Bad', 'Un profesor de química se convierte en fabricante de metanfetaminas', '2008-01-20', 50, 2, 'breakingbad.jpg', '', '+18', 1),
(3, 'Nuestro Planeta', 'Serie documental sobre la naturaleza', '2019-04-05', 50, 3, 'planeta.jpg', '', 'ATP', 1);


 /*---------------------------------------------*/
CREATE TABLE `genero` (
  `idGenero` int(11) NOT NULL AUTO_INCREMENT,
  `nombreGenero` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idGenero`),
  UNIQUE KEY `nombreGenero` (`nombreGenero`)
) ;

INSERT INTO `genero` VALUES 
(1, 'Accion', 'Películas de acción y aventura'),
(2, 'Drama', 'Historias dramáticas'),
(3, 'Comedia', 'Contenido cómico'),
(4, 'Terror', 'Películas de terror y suspenso'),
(5, 'Ciencia Ficcion', 'Sci-Fi y futurista'),
(6, 'Documental', 'Documentales educativos');


CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombreCategoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idCategoria`),
  UNIQUE KEY `nombreCategoria` (`nombreCategoria`)
) ;

INSERT INTO `categoria` VALUES 
(1, 'Mas Vistas', 'Contenido más popular'),
(2, 'Destacadas', 'Recomendaciones del editor'),
(3, 'Recien Agregadas', 'Últimas incorporaciones');


 /*---------------------------------------------*/
CREATE TABLE `contenidocategoria` (
  `idContenidoCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `idContenido` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  PRIMARY KEY (`idContenidoCategoria`),
  UNIQUE KEY `idContenido` (`idContenido`,`idCategoria`),
  KEY `idCategoria` (`idCategoria`),
  CONSTRAINT `contenidocategoria_ibfk_1` FOREIGN KEY (`idContenido`) REFERENCES `contenido` (`idContenido`) ON DELETE CASCADE,
  CONSTRAINT `contenidocategoria_ibfk_2` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`)
) ;

INSERT INTO `contenidocategoria` VALUES 
(1, 1, 2),
(2, 2, 1),
(3, 3, 3);
 /*---------------------------------------------*/
 
 CREATE TABLE `contenidogenero` (
  `idContenidoGenero` int(11) NOT NULL AUTO_INCREMENT,
  `idContenido` int(11) NOT NULL,
  `idGenero` int(11) NOT NULL,
  PRIMARY KEY (`idContenidoGenero`),
  UNIQUE KEY `idContenido` (`idContenido`,`idGenero`),
  KEY `idGenero` (`idGenero`),
  CONSTRAINT `contenidogenero_ibfk_1` FOREIGN KEY (`idContenido`) REFERENCES `contenido` (`idContenido`) ON DELETE CASCADE,
  CONSTRAINT `contenidogenero_ibfk_2` FOREIGN KEY (`idGenero`) REFERENCES `genero` (`idGenero`)
) ;


INSERT INTO `contenidogenero` VALUES 
(1, 1, 2),
(2, 2, 2),
(3, 3, 6);
 /*---------------------------------------------*/
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
);

INSERT INTO `favoritos` VALUES 
(1, 2, 2),
(2, 3, 2),
(3, 4, 6);


 /*---------------------------------------------*/
-- -------- STORED PROCEDR4ES-----------------
 /*---------------------------------------------*/
 
 DELIMITER $$

-- ------------CREAR CUENTA-------
DROP PROCEDURE IF EXISTS CrearCuenta$$
CREATE PROCEDURE CrearCuenta(
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
    
    -- Insertar el nuevo usuario
    INSERT INTO usuarioBD (
        cedula, 
        nombreUsuario, 
        correo, 
        contrasenna, 
        fechaRegistro, 
        activo
    )
    VALUES (
        p_cedula, 
        p_nombre, 
        p_correo, 
        p_contrasenna, 
        NOW(), 
        1
    );
    
    -- Obtener ID del usuario creado
    SET v_idUsuario = LAST_INSERT_ID();
    
    -- Asignar rol de Cliente (idRol = 2)
    INSERT INTO usuarioRol (idUsuario, idRol)
    VALUES (v_idUsuario, 2);
    
    -- Asignar suscripción gratuita (idSuscripcion = 1)
    INSERT INTO usuarioSuscripcion (idUsuario, idSuscripcion, fechaInicio, fechaVencimiento, estado)
    VALUES (v_idUsuario, 1, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 'Activa');
    
    COMMIT;
    
    -- Retornar éxito
    SELECT 'Usuario registrado exitosamente' AS mensaje, 1 AS success;
END$$

-- ----------VALIDAR CUENTA-------
DROP PROCEDURE IF EXISTS ValidarCuenta$$
CREATE PROCEDURE ValidarCuenta(
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

-- ============================================
-- UTILIDADES
-- ============================================
DROP PROCEDURE IF EXISTS RegistrarError$$
CREATE PROCEDURE RegistrarError(IN p_mensaje TEXT)
BEGIN
    INSERT INTO logErrores (mensaje) VALUES (p_mensaje);
END$$

-- ============================================
-- MÓDULO DE USUARIOS
-- ============================================
DROP PROCEDURE IF EXISTS ConsultarUsuarios$$
CREATE PROCEDURE ConsultarUsuarios()
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
    INNER JOIN usuarioRol ur ON u.idUsuario = ur.idUsuario
    INNER JOIN rol r ON ur.idRol = r.idRol
    LEFT JOIN usuarioSuscripcion us ON u.idUsuario = us.idUsuario AND us.estado = 'Activa'
    LEFT JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
    WHERE r.idRol = 2
    ORDER BY u.fechaRegistro DESC;
END$$

DROP PROCEDURE IF EXISTS ActualizarUsuario$$
CREATE PROCEDURE ActualizarUsuario(
    IN p_idUsuario INT,
    IN p_cedula VARCHAR(20),
    IN p_nombre VARCHAR(100),
    IN p_correo VARCHAR(100),
    IN p_contrasenna VARCHAR(255)
)
BEGIN
    -- Si la contraseña no está vacía, actualizar todo incluyendo contraseña
    IF p_contrasenna != '' THEN
        UPDATE usuarioBD
        SET cedula = p_cedula,
            nombreUsuario = p_nombre,
            correo = p_correo,
            contrasenna = p_contrasenna
        WHERE idUsuario = p_idUsuario;
    ELSE
        -- Si la contraseña está vacía, no actualizar contraseña
        UPDATE usuarioBD
        SET cedula = p_cedula,
            nombreUsuario = p_nombre,
            correo = p_correo
        WHERE idUsuario = p_idUsuario;
    END IF;
    
    SELECT 'Usuario actualizado exitosamente' AS mensaje;
END$$

-- ============================================
-- MÓDULO DE RECUPERACIÓN DE CONTRASEÑA
-- ============================================
DROP PROCEDURE IF EXISTS ValidarCorreo$$
CREATE PROCEDURE ValidarCorreo(IN p_correo VARCHAR(100))
BEGIN
    SELECT 
        idUsuario AS ConsecutivoUsuario,
        nombreUsuario AS Nombre,
        correo AS CorreoElectronico
    FROM usuarioBD
    WHERE correo = p_correo AND activo = 1
    LIMIT 1;
END$$

DROP PROCEDURE IF EXISTS ActualizarContrasenna$$
CREATE PROCEDURE ActualizarContrasenna(
    IN p_idUsuario INT,
    IN p_contrasenna VARCHAR(255)
)
BEGIN
    UPDATE usuarioBD 
    SET contrasenna = p_contrasenna
    WHERE idUsuario = p_idUsuario;
    
    SELECT 'Contraseña actualizada exitosamente' AS mensaje;
END$$

-- ============================================
-- MÓDULO DE SUSCRIPCIONES
-- ============================================
DROP PROCEDURE IF EXISTS CambiarSuscripcion$$
CREATE PROCEDURE CambiarSuscripcion(
    IN p_idUsuario INT,
    IN p_idSuscripcionNueva INT
)
BEGIN
    -- Desactivar suscripción actual
    UPDATE usuarioSuscripcion
    SET estado = 'Inactiva'
    WHERE idUsuario = p_idUsuario AND estado = 'Activa';
    
    -- Crear nueva suscripción
    INSERT INTO usuarioSuscripcion (idUsuario, idSuscripcion, fechaInicio, fechaVencimiento, estado)
    VALUES (p_idUsuario, p_idSuscripcionNueva, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 'Activa');
    
    SELECT 'Suscripción actualizada exitosamente' AS mensaje;
END$$

DROP PROCEDURE IF EXISTS ConsultarSuscripciones$$
CREATE PROCEDURE ConsultarSuscripciones()
BEGIN
    SELECT 
        idSuscripcion AS ConsecutivoSuscripcion,
        tipoSuscripcion,
        precio,
        descripcion,
        limiteFavoritos
    FROM suscripcion;
END$$

-- ============================================
-- MÓDULO DE GÉNEROS
-- ============================================
DROP PROCEDURE IF EXISTS ConsultarGeneros$$
CREATE PROCEDURE ConsultarGeneros()
BEGIN
    SELECT idGenero AS ConsecutivoGenero, nombreGenero AS Nombre, descripcion AS Descripcion
    FROM genero 
    ORDER BY nombreGenero;
END$$

DROP PROCEDURE IF EXISTS AgregarGenero$$
CREATE PROCEDURE AgregarGenero(IN p_nombreGenero VARCHAR(50), IN p_descripcion TEXT)
BEGIN
    INSERT INTO genero (nombreGenero, descripcion) VALUES (p_nombreGenero, p_descripcion);
    SELECT 'Género agregado exitosamente' AS mensaje;
END$$

DROP PROCEDURE IF EXISTS ActualizarGenero$$
CREATE PROCEDURE ActualizarGenero(IN p_idGenero INT, IN p_nombreGenero VARCHAR(50), IN p_descripcion TEXT)
BEGIN
    UPDATE genero 
    SET nombreGenero = p_nombreGenero, descripcion = p_descripcion 
    WHERE idGenero = p_idGenero;
    SELECT 'Género actualizado exitosamente' AS mensaje;
END$$

DROP PROCEDURE IF EXISTS EliminarGenero$$
CREATE PROCEDURE EliminarGenero(IN p_idGenero INT)
BEGIN
    DELETE FROM genero WHERE idGenero = p_idGenero;
    SELECT 'Género eliminado exitosamente' AS mensaje;
END$$

-- ============================================
-- MÓDULO DE CATEGORÍAS
-- ============================================
DROP PROCEDURE IF EXISTS ConsultarCategorias$$
CREATE PROCEDURE ConsultarCategorias()
BEGIN
    SELECT idCategoria AS ConsecutivoCategoria, nombreCategoria AS Nombre, descripcion AS Descripcion
    FROM categoria 
    ORDER BY nombreCategoria;
END$$

DROP PROCEDURE IF EXISTS AgregarCategoria$$
CREATE PROCEDURE AgregarCategoria(IN p_nombreCategoria VARCHAR(100), IN p_descripcion TEXT)
BEGIN
    INSERT INTO categoria (nombreCategoria, descripcion) VALUES (p_nombreCategoria, p_descripcion);
    SELECT 'Categoría agregada exitosamente' AS mensaje;
END$$

DROP PROCEDURE IF EXISTS ActualizarCategoria$$
CREATE PROCEDURE ActualizarCategoria(IN p_idCategoria INT, IN p_nombreCategoria VARCHAR(100), IN p_descripcion TEXT)
BEGIN
    UPDATE categoria 
    SET nombreCategoria = p_nombreCategoria, descripcion = p_descripcion 
    WHERE idCategoria = p_idCategoria;
    SELECT 'Categoría actualizada exitosamente' AS mensaje;
END$$

DROP PROCEDURE IF EXISTS EliminarCategoria$$
CREATE PROCEDURE EliminarCategoria(IN p_idCategoria INT)
BEGIN
    DELETE FROM categoria WHERE idCategoria = p_idCategoria;
    SELECT 'Categoría eliminada exitosamente' AS mensaje;
END$$

-- ============================================
-- MÓDULO DE CONTENIDO
-- ============================================
DROP PROCEDURE IF EXISTS ConsultarContenido$$
CREATE PROCEDURE ConsultarContenido()
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
        c.fechaCreacion,
        GROUP_CONCAT(DISTINCT g.nombreGenero SEPARATOR ', ') AS Generos,
        GROUP_CONCAT(DISTINCT cat.nombreCategoria SEPARATOR ', ') AS Categorias
    FROM contenido c
    LEFT JOIN contenidoGenero cg ON c.idContenido = cg.idContenido
    LEFT JOIN genero g ON cg.idGenero = g.idGenero
    LEFT JOIN contenidoCategoria cc ON c.idContenido = cc.idContenido
    LEFT JOIN categoria cat ON cc.idCategoria = cat.idCategoria
    GROUP BY c.idContenido
    ORDER BY c.fechaCreacion DESC;
END$$

DROP PROCEDURE IF EXISTS ConsultarContenidoPorId$$
CREATE PROCEDURE ConsultarContenidoPorId(IN p_idContenido INT)
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
        c.activo
    FROM contenido c
    WHERE c.idContenido = p_idContenido;
END$$



DROP PROCEDURE IF EXISTS AgregarContenido$$
CREATE PROCEDURE AgregarContenido(
    IN p_titulo VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_duracion INT,
    IN p_imagen VARCHAR(255),
    IN p_trailer VARCHAR(255),
    IN p_calificacion VARCHAR(10),
    IN p_fechaPublicacion DATE
)
BEGIN
    INSERT INTO contenido (
        titulo, 
        descripcion, 
        duracion, 
        imagen, 
        trailer, 
        calificacionEdad, 
        fechaPublicacion,
        activo
    ) VALUES (
        p_titulo,
        p_descripcion,
        p_duracion,
        p_imagen,
        p_trailer,
        p_calificacion,
        p_fechaPublicacion,
        1
    );
    
    SELECT LAST_INSERT_ID() as idContenido;
END$$



DROP PROCEDURE IF EXISTS ActualizarContenido$$
CREATE PROCEDURE ActualizarContenido(
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

DROP PROCEDURE IF EXISTS CambiarEstadoContenido$$
CREATE PROCEDURE CambiarEstadoContenido(IN p_idContenido INT)
BEGIN
    UPDATE contenido SET activo = NOT activo WHERE idContenido = p_idContenido;
    SELECT 'Estado actualizado exitosamente' AS mensaje;
END$$

DROP PROCEDURE IF EXISTS BuscarContenido$$
CREATE PROCEDURE BuscarContenido(
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

DROP PROCEDURE IF EXISTS AgregarGeneroContenido$$
CREATE PROCEDURE AgregarGeneroContenido(IN p_idContenido INT, IN p_idGenero INT)
BEGIN
    INSERT IGNORE INTO contenidoGenero (idContenido, idGenero) VALUES (p_idContenido, p_idGenero);
END$$

DROP PROCEDURE IF EXISTS EliminarGenerosContenido$$
CREATE PROCEDURE EliminarGenerosContenido(IN p_idContenido INT)
BEGIN
    DELETE FROM contenidoGenero WHERE idContenido = p_idContenido;
END$$

DROP PROCEDURE IF EXISTS AgregarCategoriaContenido$$
CREATE PROCEDURE AgregarCategoriaContenido(IN p_idContenido INT, IN p_idCategoria INT)
BEGIN
    INSERT IGNORE INTO contenidoCategoria (idContenido, idCategoria) VALUES (p_idContenido, p_idCategoria);
END$$

DROP PROCEDURE IF EXISTS EliminarCategoriasContenido$$
CREATE PROCEDURE EliminarCategoriasContenido(IN p_idContenido INT)
BEGIN
    DELETE FROM contenidoCategoria WHERE idContenido = p_idContenido;
END$$


DROP PROCEDURE IF EXISTS EliminarContenido$$
CREATE PROCEDURE EliminarContenido(
    IN p_idContenido INT
)
BEGIN
    -- Primero eliminar las relaciones con géneros
    DELETE FROM contenidoGenero WHERE idContenido = p_idContenido;
    
    -- Eliminar las relaciones con categorías
    DELETE FROM contenidoCategoria WHERE idContenido = p_idContenido;
    
    -- Eliminar de favoritos
    DELETE FROM favoritos WHERE idContenido = p_idContenido;
    
    -- Finalmente eliminar el contenido
    DELETE FROM contenido WHERE idContenido = p_idContenido;
    
    SELECT ROW_COUNT() as filasAfectadas;
END$$


-- ============================================
-- MÓDULO DE FAVORITOS
-- ============================================
DROP PROCEDURE IF EXISTS AgregarFavorito$$
CREATE PROCEDURE AgregarFavorito(
    IN p_idUsuario INT,
    IN p_idContenido INT
)
BEGIN
    DECLARE v_limite INT;
    DECLARE v_cantidad INT;
    
    SELECT COALESCE(s.limiteFavoritos, 5) INTO v_limite
    FROM usuarioSuscripcion us
    INNER JOIN suscripcion s ON us.idSuscripcion = s.idSuscripcion
    WHERE us.idUsuario = p_idUsuario AND us.estado = 'Activa'
    LIMIT 1;
    
    IF v_limite IS NULL THEN
        SET v_limite = 5;
    END IF;
    
    SELECT COUNT(*) INTO v_cantidad FROM favoritos WHERE idUsuario = p_idUsuario;
    
    IF v_cantidad < v_limite THEN
        INSERT IGNORE INTO favoritos (idUsuario, idContenido) VALUES (p_idUsuario, p_idContenido);
        SELECT 'Agregado a favoritos' AS mensaje, 1 AS success;
    ELSE
        SELECT 'Has alcanzado el límite de favoritos' AS mensaje, 0 AS success;
    END IF;
END$$

DROP PROCEDURE IF EXISTS EliminarFavorito$$
CREATE PROCEDURE EliminarFavorito(
    IN p_idUsuario INT,
    IN p_idContenido INT
)
BEGIN
    DELETE FROM favoritos WHERE idUsuario = p_idUsuario AND idContenido = p_idContenido;
    SELECT 'Eliminado de favoritos' AS mensaje;
END$$

DROP PROCEDURE IF EXISTS ConsultarFavoritos$$
CREATE PROCEDURE ConsultarFavoritos(IN p_idUsuario INT)
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
    WHERE f.idUsuario = p_idUsuario AND c.activo = 1
    ORDER BY f.fechaAgregado DESC;
END$$

DROP PROCEDURE IF EXISTS VerificarFavorito$$
CREATE PROCEDURE VerificarFavorito(
    IN p_idUsuario INT,
    IN p_idContenido INT
)
BEGIN
    SELECT COUNT(*) AS esFavorito
    FROM favoritos
    WHERE idUsuario = p_idUsuario AND idContenido = p_idContenido;
END$$

-- ============================================
-- MÓDULO DE DASHBOARD
-- ============================================
DROP PROCEDURE IF EXISTS ConsultarIndicadores$$
CREATE PROCEDURE ConsultarIndicadores()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM usuarioBD WHERE activo = 1) AS totalUsuarios,
        (SELECT COUNT(*) FROM contenido WHERE activo = 1) AS totalContenido,
        (SELECT COUNT(*) FROM usuarioSuscripcion WHERE estado = 'Activa' AND idSuscripcion = 2) AS totalPremium,
        (SELECT COUNT(*) FROM favoritos) AS totalFavoritos;
END$$

DELIMITER ;
