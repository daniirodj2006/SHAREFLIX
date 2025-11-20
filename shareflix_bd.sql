CREATE DATABASE  IF NOT EXISTS `shareflix_bd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `shareflix_bd`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: shareflix_bd
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombreCategoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idCategoria`),
  UNIQUE KEY `nombreCategoria` (`nombreCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Destacadas','Contenido destacado de la plataforma'),(2,'Más Vistas','Contenido más popular'),(3,'Nuevas','Últimas adiciones'),(4,'Top 10','Las 10 más populares'),(5,'Recomendadas','Recomendaciones personalizadas');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contenido`
--

DROP TABLE IF EXISTS `contenido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contenido` (
  `idContenido` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fechaPublicacion` date DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL COMMENT 'Duración en minutos',
  `idTipoContenido` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL COMMENT 'Ruta del poster',
  `trailer` varchar(255) DEFAULT NULL COMMENT 'URL del trailer',
  `calificacionEdad` varchar(10) DEFAULT NULL COMMENT 'ATP, +13, +16, +18',
  `activo` tinyint(1) DEFAULT 1,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idContenido`),
  KEY `idTipoContenido` (`idTipoContenido`),
  CONSTRAINT `contenido_ibfk_1` FOREIGN KEY (`idTipoContenido`) REFERENCES `tipocontenido` (`idTipoContenido`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contenido`
--

LOCK TABLES `contenido` WRITE;
/*!40000 ALTER TABLE `contenido` DISABLE KEYS */;
INSERT INTO `contenido` VALUES (1,'Avatar: El Camino del Agua','Jake Sully y Neytiri han formado una familia y hacen todo lo posible por permanecer juntos.','2022-12-16',192,1,'','','PG-13',1,'2025-11-18 19:23:36'),(2,'Spider-Man: A Través del Universo','Miles Morales regresa para una aventura épica.','2023-06-02',140,1,'','','PG-13',1,'2025-11-18 19:23:36'),(3,'Guardianes de la Galaxia Vol. 3','Peter Quill debe reunir a su equipo para defender el universo.','2023-05-05',150,1,'','','PG-13',1,'2025-11-18 19:23:36'),(4,'El Gato con Botas: El Último Deseo','El Gato descubre que ha consumido ocho de sus nueve vidas.','2022-12-21',102,1,'','','ATP',1,'2025-11-18 19:23:36'),(5,'John Wick 4','John Wick busca un camino para derrotar a la Mesa Alta.','2023-03-24',169,1,'','','+16',1,'2025-11-18 19:23:36');
/*!40000 ALTER TABLE `contenido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contenidocategoria`
--

DROP TABLE IF EXISTS `contenidocategoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contenidocategoria` (
  `idContenidoCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `idContenido` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  PRIMARY KEY (`idContenidoCategoria`),
  UNIQUE KEY `idContenido` (`idContenido`,`idCategoria`),
  KEY `idCategoria` (`idCategoria`),
  CONSTRAINT `contenidocategoria_ibfk_1` FOREIGN KEY (`idContenido`) REFERENCES `contenido` (`idContenido`) ON DELETE CASCADE,
  CONSTRAINT `contenidocategoria_ibfk_2` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contenidocategoria`
--

LOCK TABLES `contenidocategoria` WRITE;
/*!40000 ALTER TABLE `contenidocategoria` DISABLE KEYS */;
INSERT INTO `contenidocategoria` VALUES (1,1,1),(7,1,2),(9,1,4),(2,2,1),(4,2,3),(10,2,4),(3,3,1),(5,3,3),(11,3,4),(8,4,2),(6,5,3);
/*!40000 ALTER TABLE `contenidocategoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contenidogenero`
--

DROP TABLE IF EXISTS `contenidogenero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contenidogenero` (
  `idContenidoGenero` int(11) NOT NULL AUTO_INCREMENT,
  `idContenido` int(11) NOT NULL,
  `idGenero` int(11) NOT NULL,
  PRIMARY KEY (`idContenidoGenero`),
  UNIQUE KEY `idContenido` (`idContenido`,`idGenero`),
  KEY `idGenero` (`idGenero`),
  CONSTRAINT `contenidogenero_ibfk_1` FOREIGN KEY (`idContenido`) REFERENCES `contenido` (`idContenido`) ON DELETE CASCADE,
  CONSTRAINT `contenidogenero_ibfk_2` FOREIGN KEY (`idGenero`) REFERENCES `genero` (`idGenero`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contenidogenero`
--

LOCK TABLES `contenidogenero` WRITE;
/*!40000 ALTER TABLE `contenidogenero` DISABLE KEYS */;
INSERT INTO `contenidogenero` VALUES (1,1,5),(2,1,7),(3,2,1),(4,2,8),(5,3,1),(6,3,5),(8,4,7),(7,4,8),(9,5,1),(10,5,10);
/*!40000 ALTER TABLE `contenidogenero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos`
--

LOCK TABLES `favoritos` WRITE;
/*!40000 ALTER TABLE `favoritos` DISABLE KEYS */;
/*!40000 ALTER TABLE `favoritos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genero`
--

DROP TABLE IF EXISTS `genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genero` (
  `idGenero` int(11) NOT NULL AUTO_INCREMENT,
  `nombreGenero` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idGenero`),
  UNIQUE KEY `nombreGenero` (`nombreGenero`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (1,'Acción','Películas de acción y aventura'),(2,'Comedia','Películas de humor y comedia'),(3,'Drama','Películas dramáticas'),(4,'Terror','Películas de terror y suspense'),(5,'Ciencia Ficción','Películas de ciencia ficción'),(6,'Romance','Películas románticas'),(7,'Aventura','Películas de aventuras'),(8,'Animación','Películas animadas'),(9,'Documental','Documentales educativos'),(10,'Thriller','Películas de suspenso');
/*!40000 ALTER TABLE `genero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logerrores`
--

DROP TABLE IF EXISTS `logerrores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logerrores` (
  `idError` int(11) NOT NULL AUTO_INCREMENT,
  `mensaje` text NOT NULL,
  `fechaError` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idError`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logerrores`
--

LOCK TABLES `logerrores` WRITE;
/*!40000 ALTER TABLE `logerrores` DISABLE KEYS */;
INSERT INTO `logerrores` VALUES (1,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:08'),(2,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:33'),(3,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:35'),(4,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:36'),(5,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:44'),(6,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:50'),(7,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:52'),(8,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:52'),(9,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:53'),(10,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:54'),(11,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:50:57'),(12,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:53:42'),(13,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 18:53:42'),(14,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 19:19:48'),(15,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 19:30:10'),(16,'Excepción en ObtenerUsuariosRecientes: Unknown column \'u.nombre\' in \'field list\'','2025-11-18 19:30:34'),(17,'Excepción en ConsultarFavoritos: PROCEDURE shareflix_bd.ConsultarFavoritos does not exist','2025-11-18 19:33:33'),(18,'Excepción en ConsultarFavoritos: PROCEDURE shareflix_bd.ConsultarFavoritos does not exist','2025-11-18 19:39:03');
/*!40000 ALTER TABLE `logerrores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recuperacioncontrasena`
--

DROP TABLE IF EXISTS `recuperacioncontrasena`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recuperacioncontrasena`
--

LOCK TABLES `recuperacioncontrasena` WRITE;
/*!40000 ALTER TABLE `recuperacioncontrasena` DISABLE KEYS */;
/*!40000 ALTER TABLE `recuperacioncontrasena` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `nombreRol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idRol`),
  UNIQUE KEY `nombreRol` (`nombreRol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Administrador','Acceso completo al sistema'),(2,'Cliente','Usuario regular del sistema');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suscripcion`
--

DROP TABLE IF EXISTS `suscripcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suscripcion` (
  `idSuscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `tipoSuscripcion` enum('Gratis','Premium') NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracionDias` int(11) DEFAULT 30,
  `limiteFavoritos` int(11) DEFAULT 5,
  PRIMARY KEY (`idSuscripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suscripcion`
--

LOCK TABLES `suscripcion` WRITE;
/*!40000 ALTER TABLE `suscripcion` DISABLE KEYS */;
INSERT INTO `suscripcion` VALUES (1,'Gratis',0.00,'Plan gratuito con funcionalidades básicas',30,5),(2,'Premium',9.99,'Plan premium sin límites',30,999999);
/*!40000 ALTER TABLE `suscripcion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipocontenido`
--

DROP TABLE IF EXISTS `tipocontenido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipocontenido` (
  `idTipoContenido` int(11) NOT NULL AUTO_INCREMENT,
  `nombreTipo` varchar(50) NOT NULL,
  PRIMARY KEY (`idTipoContenido`),
  UNIQUE KEY `nombreTipo` (`nombreTipo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipocontenido`
--

LOCK TABLES `tipocontenido` WRITE;
/*!40000 ALTER TABLE `tipocontenido` DISABLE KEYS */;
INSERT INTO `tipocontenido` VALUES (3,'Documental'),(1,'Pelicula'),(2,'Serie');
/*!40000 ALTER TABLE `tipocontenido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuariobd`
--

DROP TABLE IF EXISTS `usuariobd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuariobd`
--

LOCK TABLES `usuariobd` WRITE;
/*!40000 ALTER TABLE `usuariobd` DISABLE KEYS */;
INSERT INTO `usuariobd` VALUES (1,'000000000','Administrador','admin@shareflix.com','admin123','2025-11-16 21:28:51',1),(2,'1-2345-6789','Usuario Test Shareflix','test_1763351800@shareflix.com','test123456','2025-11-16 21:56:40',1),(3,'303290821','GUSTAVO ADOLFO PIEDRA VASQUEZ','daniroji20@Gmail.com','12345678','2025-11-16 21:57:58',1),(4,'303280821','KELEN VIVIANA JIMENEZ HERNANDEZ','daniroji202@Gmail.com','12345678','2025-11-16 22:05:58',1),(5,'118150931','DANIEL ESTEBAN VELASQUEZ MENDEZ','correo@gmail.com','12345678','2025-11-17 15:57:35',1),(6,'901150629','ANDRES ALONSO AGUILAR JIMENEZ','andrew@Gmail.com','12345678','2025-11-17 20:11:18',1);
/*!40000 ALTER TABLE `usuariobd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuariorol`
--

DROP TABLE IF EXISTS `usuariorol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuariorol` (
  `idUsuarioRol` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idRol` int(11) NOT NULL,
  PRIMARY KEY (`idUsuarioRol`),
  UNIQUE KEY `idUsuario` (`idUsuario`,`idRol`),
  KEY `idRol` (`idRol`),
  CONSTRAINT `usuariorol_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariobd` (`idUsuario`),
  CONSTRAINT `usuariorol_ibfk_2` FOREIGN KEY (`idRol`) REFERENCES `rol` (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuariorol`
--

LOCK TABLES `usuariorol` WRITE;
/*!40000 ALTER TABLE `usuariorol` DISABLE KEYS */;
INSERT INTO `usuariorol` VALUES (1,1,1),(2,4,2),(3,5,2),(4,6,2);
/*!40000 ALTER TABLE `usuariorol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuariosuscripcion`
--

DROP TABLE IF EXISTS `usuariosuscripcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuariosuscripcion`
--

LOCK TABLES `usuariosuscripcion` WRITE;
/*!40000 ALTER TABLE `usuariosuscripcion` DISABLE KEYS */;
INSERT INTO `usuariosuscripcion` VALUES (1,4,1,'2025-11-16 22:05:58','2025-12-16 22:05:58','Activa'),(2,5,1,'2025-11-17 15:57:35','2025-12-17 15:57:35','Activa'),(3,6,1,'2025-11-17 20:11:18','2025-12-17 20:11:18','Activa');
/*!40000 ALTER TABLE `usuariosuscripcion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'shareflix_bd'
--
/*!50003 DROP PROCEDURE IF EXISTS `ActualizarCategoria` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarCategoria`(
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ActualizarContenido` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarContenido`(
    IN p_idContenido INT,
    IN p_titulo VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_duracion INT,
    IN p_idTipoContenido INT,
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
        idTipoContenido = p_idTipoContenido,
        imagen = IF(p_imagen != '', p_imagen, imagen),
        trailer = p_trailer,
        calificacionEdad = p_calificacionEdad,
        fechaPublicacion = p_fechaPublicacion
    WHERE idContenido = p_idContenido;
    
    SELECT 'Contenido actualizado exitosamente' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ActualizarGenero` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarGenero`(
    IN p_idGenero INT,
    IN p_nombreGenero VARCHAR(50),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE genero
    SET nombreGenero = p_nombreGenero,
        descripcion = p_descripcion
    WHERE idGenero = p_idGenero;
    
    SELECT 'Género actualizado exitosamente' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AgregarCategoria` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarCategoria`(
    IN p_nombreCategoria VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO categoria (nombreCategoria, descripcion)
    VALUES (p_nombreCategoria, p_descripcion);
    
    SELECT 'Categoría agregada exitosamente' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AgregarCategoriaContenido` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarCategoriaContenido`(
    IN p_idContenido INT,
    IN p_idCategoria INT
)
BEGIN
    INSERT IGNORE INTO contenidoCategoria (idContenido, idCategoria)
    VALUES (p_idContenido, p_idCategoria);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AgregarContenido` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarContenido`(
    IN p_titulo VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_duracion INT,
    IN p_idTipoContenido INT,
    IN p_imagen VARCHAR(255),
    IN p_trailer VARCHAR(255),
    IN p_calificacionEdad VARCHAR(10),
    IN p_fechaPublicacion DATE
)
BEGIN
    INSERT INTO contenido (titulo, descripcion, duracion, idTipoContenido, imagen, trailer, calificacionEdad, fechaPublicacion)
    VALUES (p_titulo, p_descripcion, p_duracion, p_idTipoContenido, p_imagen, p_trailer, p_calificacionEdad, p_fechaPublicacion);
    
    SELECT LAST_INSERT_ID() AS idContenido;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AgregarGenero` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarGenero`(
    IN p_nombreGenero VARCHAR(50),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO genero (nombreGenero, descripcion)
    VALUES (p_nombreGenero, p_descripcion);
    
    SELECT 'Género agregado exitosamente' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AgregarGeneroContenido` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarGeneroContenido`(
    IN p_idContenido INT,
    IN p_idGenero INT
)
BEGIN
    INSERT IGNORE INTO contenidoGenero (idContenido, idGenero)
    VALUES (p_idContenido, p_idGenero);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `BuscarContenido` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `BuscarContenido`(
    IN p_busqueda VARCHAR(200),
    IN p_idTipo INT,
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
        c.calificacionEdad AS CalificacionEdad,
        tc.nombreTipo AS NombreTipo
    FROM contenido c
    INNER JOIN tipoContenido tc ON c.idTipoContenido = tc.idTipoContenido
    LEFT JOIN contenidoGenero cg ON c.idContenido = cg.idContenido
    LEFT JOIN contenidoCategoria cc ON c.idContenido = cc.idContenido
    WHERE c.activo = 1
    AND (p_busqueda = '' OR c.titulo LIKE CONCAT('%', p_busqueda, '%') OR c.descripcion LIKE CONCAT('%', p_busqueda, '%'))
    AND (p_idTipo = 0 OR c.idTipoContenido = p_idTipo)
    AND (p_idGenero = 0 OR cg.idGenero = p_idGenero)
    AND (p_idCategoria = 0 OR cc.idCategoria = p_idCategoria)
    GROUP BY c.idContenido
    ORDER BY c.fechaCreacion DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CambiarEstadoContenido` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `CambiarEstadoContenido`(
    IN p_idContenido INT
)
BEGIN
    UPDATE contenido
    SET activo = NOT activo
    WHERE idContenido = p_idContenido;
    
    SELECT 'Estado actualizado exitosamente' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ConsultarCategorias` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarCategorias`()
BEGIN
    SELECT 
        idCategoria AS ConsecutivoCategoria,
        nombreCategoria AS Nombre,
        descripcion AS Descripcion
    FROM categoria
    ORDER BY nombreCategoria;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ConsultarContenido` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarContenido`()
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
        tc.nombreTipo AS NombreTipo,
        tc.idTipoContenido AS ConsecutivoTipo,
        GROUP_CONCAT(DISTINCT g.nombreGenero SEPARATOR ', ') AS Generos,
        GROUP_CONCAT(DISTINCT cat.nombreCategoria SEPARATOR ', ') AS Categorias
    FROM contenido c
    INNER JOIN tipoContenido tc ON c.idTipoContenido = tc.idTipoContenido
    LEFT JOIN contenidoGenero cg ON c.idContenido = cg.idContenido
    LEFT JOIN genero g ON cg.idGenero = g.idGenero
    LEFT JOIN contenidoCategoria cc ON c.idContenido = cc.idContenido
    LEFT JOIN categoria cat ON cc.idCategoria = cat.idCategoria
    GROUP BY c.idContenido
    ORDER BY c.fechaCreacion DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ConsultarContenidoPorId` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarContenidoPorId`(
    IN p_idContenido INT
)
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
        c.activo,
        tc.idTipoContenido AS ConsecutivoTipo,
        tc.nombreTipo AS NombreTipo
    FROM contenido c
    INNER JOIN tipoContenido tc ON c.idTipoContenido = tc.idTipoContenido
    WHERE c.idContenido = p_idContenido;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ConsultarGeneros` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarGeneros`()
BEGIN
    SELECT 
        idGenero AS ConsecutivoGenero,
        nombreGenero AS Nombre,
        descripcion AS Descripcion
    FROM genero
    ORDER BY nombreGenero;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CrearCuenta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `CrearCuenta`(
    IN p_cedula VARCHAR(20),
    IN p_nombre VARCHAR(100),
    IN p_correo VARCHAR(100),
    IN p_contrasenna VARCHAR(255)
)
BEGIN
    DECLARE v_idUsuario INT;
    
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
    
    -- Retornar éxito
    SELECT 'Usuario registrado exitosamente' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `EliminarCategoria` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarCategoria`(
    IN p_idCategoria INT
)
BEGIN
    DELETE FROM categoria WHERE idCategoria = p_idCategoria;
    SELECT 'Categoría eliminada exitosamente' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `EliminarCategoriasContenido` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarCategoriasContenido`(
    IN p_idContenido INT
)
BEGIN
    DELETE FROM contenidoCategoria WHERE idContenido = p_idContenido;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `EliminarGenero` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarGenero`(
    IN p_idGenero INT
)
BEGIN
    DELETE FROM genero WHERE idGenero = p_idGenero;
    SELECT 'Género eliminado exitosamente' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `EliminarGenerosContenido` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarGenerosContenido`(
    IN p_idContenido INT
)
BEGIN
    DELETE FROM contenidoGenero WHERE idContenido = p_idContenido;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `RegistrarError` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `RegistrarError`(
    IN p_mensaje TEXT
)
BEGIN
    INSERT INTO logErrores (mensaje) VALUES (p_mensaje);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ValidarCuenta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ValidarCuenta`(
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-19  9:07:24
