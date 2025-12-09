<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UtilesModel.php';

    // ========================================
    // GESTIÓN DE CONTENIDO (PELÍCULAS)
    // ========================================
    // ========================================
    // GESTIÓN DE CONTENIDO (PELÍCULAS)
    // ========================================
    // ========================================
    // GESTIÓN DE CONTENIDO (PELÍCULAS)
    // ========================================

    // CONSULTAR TODO EL CONTENIDO
    function ConsultarContenido()
    {
        try {
            $consulta = "CALL ConsultarContenido()";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en ConsultarContenido: " . $e->getMessage());
            return null;
        }
    }

    // CONSULTAR CONTENIDO POR ID
    function ConsultarContenidoPorId($idContenido)
    {
        try {
            $consulta = "CALL ConsultarContenidoPorId($idContenido)";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en ConsultarContenidoPorId: " . $e->getMessage());
            return null;
        }
    }

    // AGREGAR CONTENIDO
    function AgregarContenido($titulo, $descripcion, $duracion, $imagen, $trailer, $calificacion, $fechaPublicacion)
    {
        try {
            $titulo = LimpiarEntrada($titulo);
            $descripcion = LimpiarEntrada($descripcion);
            $imagen = LimpiarEntrada($imagen);
            $trailer = LimpiarEntrada($trailer);
            $calificacion = LimpiarEntrada($calificacion);
            
            $consulta = "CALL AgregarContenido('$titulo', '$descripcion', $duracion, '$imagen', '$trailer', '$calificacion', '$fechaPublicacion')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado && mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_array($resultado);
                return array('success' => true, 'idContenido' => $fila['idContenido'], 'mensaje' => 'Película agregada exitosamente');
            }
            
            return array('success' => false, 'mensaje' => 'Error al agregar película');
        } catch (Exception $e) {
            RegistrarError("Excepción en AgregarContenido: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ACTUALIZAR CONTENIDO
    function ActualizarContenido($idContenido, $titulo, $descripcion, $duracion, $imagen, $trailer, $calificacion, $fechaPublicacion)
    {
        try {
            $titulo = LimpiarEntrada($titulo);
            $descripcion = LimpiarEntrada($descripcion);
            $imagen = LimpiarEntrada($imagen);
            $trailer = LimpiarEntrada($trailer);
            $calificacion = LimpiarEntrada($calificacion);
            
            $consulta = "CALL ActualizarContenido($idContenido, '$titulo', '$descripcion', $duracion, '$imagen', '$trailer', '$calificacion', '$fechaPublicacion')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado && mysqli_num_rows($resultado) > 0) {
                return array('success' => true, 'mensaje' => 'Película actualizada exitosamente');
            }
            
            return array('success' => false, 'mensaje' => 'Error al actualizar película');
        } catch (Exception $e) {
            RegistrarError("Excepción en ActualizarContenido: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // CAMBIAR ESTADO DE CONTENIDO
    function CambiarEstadoContenido($idContenido)
    {
        try {
            $consulta = "CALL CambiarEstadoContenido($idContenido)";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Estado actualizado exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al cambiar estado');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en CambiarEstadoContenido: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ELIMINAR CONTENIDO PERMANENTEMENTE
function EliminarContenido($idContenido)
{
    try {
        $consulta = "CALL EliminarContenido($idContenido)";
        $resultado = LlamarProcedimiento($consulta);

        if($resultado && mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_array($resultado);
            if($fila['filasAfectadas'] > 0) {
                return array('success' => true, 'mensaje' => 'Película eliminada exitosamente');
            }
        }
        
        return array('success' => false, 'mensaje' => 'No se pudo eliminar la película');
    } catch (Exception $e) {
        RegistrarError("Excepción en EliminarContenido: " . $e->getMessage());
        return array('success' => false, 'mensaje' => 'Error en el servidor');
    }
}

    // BUSCAR CONTENIDO CON FILTROS
    function BuscarContenido($busqueda = '', $idGenero = 0, $idCategoria = 0)
    {
        try {
            $busqueda = LimpiarEntrada($busqueda);
            $consulta = "CALL BuscarContenido('$busqueda', $idGenero, $idCategoria)";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en BuscarContenido: " . $e->getMessage());
            return null;
        }
    }

    // SUBIR IMAGEN DE CONTENIDO
    function SubirImagenContenido($archivo)
    {
        try {
            $directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/img/contenido/';
            
            // Crear directorio si no existe
            CrearDirectorio($directorioDestino);

            // Validar extensión
            if(!ValidarExtensionImagen($archivo['name'])) {
                return array('success' => false, 'mensaje' => 'Formato de imagen no permitido. Use: jpg, jpeg, png, gif, webp');
            }

            // Validar tamaño (5MB)
            if(!ValidarTamanoArchivo($archivo['size'], 5242880)) {
                return array('success' => false, 'mensaje' => 'La imagen no debe superar los 5 MB');
            }

            // Generar nombre único
            $extension = ObtenerExtension($archivo['name']);
            $nombreNuevo = GenerarNombreUnico($extension);
            $rutaCompleta = $directorioDestino . $nombreNuevo;

            // Mover archivo
            if(move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                return array('success' => true, 'nombreArchivo' => $nombreNuevo);
            } else {
                return array('success' => false, 'mensaje' => 'Error al subir la imagen');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en SubirImagenContenido: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ELIMINAR IMAGEN DE CONTENIDO
    function EliminarImagenContenido($nombreArchivo)
    {
        try {
            $rutaArchivo = $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/img/contenido/' . $nombreArchivo;
            
            if(file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
                return true;
            }
            return false;
        } catch (Exception $e) {
            RegistrarError("Excepción en EliminarImagenContenido: " . $e->getMessage());
            return false;
        }
    }

    // ========================================
    // RELACIONES CONTENIDO-GÉNEROS
    // ========================================

    // AGREGAR GÉNERO A CONTENIDO
    function AgregarGeneroContenido($idContenido, $idGenero)
    {
        try {
            $consulta = "CALL AgregarGeneroContenido($idContenido, $idGenero)";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en AgregarGeneroContenido: " . $e->getMessage());
            return false;
        }
    }

    // ELIMINAR TODOS LOS GÉNEROS DE UN CONTENIDO
    function EliminarGenerosContenido($idContenido)
    {
        try {
            $consulta = "CALL EliminarGenerosContenido($idContenido)";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en EliminarGenerosContenido: " . $e->getMessage());
            return false;
        }
    }

    // OBTENER GÉNEROS DE UN CONTENIDO
    function ObtenerGenerosContenido($idContenido)
    {
        try {
            $consulta = "SELECT g.idGenero, g.nombreGenero
                        FROM contenidoGenero cg
                        INNER JOIN genero g ON cg.idGenero = g.idGenero
                        WHERE cg.idContenido = $idContenido";
            return EjecutarConsulta($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en ObtenerGenerosContenido: " . $e->getMessage());
            return null;
        }
    }

    // ========================================
    // RELACIONES CONTENIDO-CATEGORÍAS
    // ========================================

    // AGREGAR CATEGORÍA A CONTENIDO
    function AgregarCategoriaContenido($idContenido, $idCategoria)
    {
        try {
            $consulta = "CALL AgregarCategoriaContenido($idContenido, $idCategoria)";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en AgregarCategoriaContenido: " . $e->getMessage());
            return false;
        }
    }

    // ELIMINAR TODAS LAS CATEGORÍAS DE UN CONTENIDO
    function EliminarCategoriasContenido($idContenido)
    {
        try {
            $consulta = "CALL EliminarCategoriasContenido($idContenido)";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en EliminarCategoriasContenido: " . $e->getMessage());
            return false;
        }
    }

    // OBTENER CATEGORÍAS DE UN CONTENIDO
    function ObtenerCategoriasContenido($idContenido)
    {
        try {
            $consulta = "SELECT c.idCategoria, c.nombreCategoria
                        FROM contenidoCategoria cc
                        INNER JOIN categoria c ON cc.idCategoria = c.idCategoria
                        WHERE cc.idContenido = $idContenido";
            return EjecutarConsulta($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en ObtenerCategoriasContenido: " . $e->getMessage());
            return null;
        }
    }

    // ========================================
    // GESTIÓN DE GÉNEROS
    // ========================================

    // CONSULTAR GÉNEROS
    function ConsultarGeneros()
    {
        try {
            $consulta = "CALL ConsultarGeneros()";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en ConsultarGeneros: " . $e->getMessage());
            return null;
        }
    }

    // AGREGAR GÉNERO
    function AgregarGenero($nombreGenero, $descripcion)
    {
        try {
            $nombreGenero = LimpiarEntrada($nombreGenero);
            $descripcion = LimpiarEntrada($descripcion);

            $consulta = "CALL AgregarGenero('$nombreGenero', '$descripcion')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Género agregado exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al agregar género');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en AgregarGenero: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ACTUALIZAR GÉNERO
    function ActualizarGenero($idGenero, $nombreGenero, $descripcion)
    {
        try {
            $nombreGenero = LimpiarEntrada($nombreGenero);
            $descripcion = LimpiarEntrada($descripcion);

            $consulta = "CALL ActualizarGenero($idGenero, '$nombreGenero', '$descripcion')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Género actualizado exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al actualizar género');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en ActualizarGenero: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ELIMINAR GÉNERO
    function EliminarGenero($idGenero)
    {
        try {
            $consulta = "CALL EliminarGenero($idGenero)";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Género eliminado exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al eliminar género');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en EliminarGenero: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ========================================
    // GESTIÓN DE CATEGORÍAS
    // ========================================

    // CONSULTAR CATEGORÍAS
    function ConsultarCategorias()
    {
        try {
            $consulta = "CALL ConsultarCategorias()";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en ConsultarCategorias: " . $e->getMessage());
            return null;
        }
    }

    // AGREGAR CATEGORÍA
    function AgregarCategoria($nombreCategoria, $descripcion)
    {
        try {
            $nombreCategoria = LimpiarEntrada($nombreCategoria);
            $descripcion = LimpiarEntrada($descripcion);

            $consulta = "CALL AgregarCategoria('$nombreCategoria', '$descripcion')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Categoría agregada exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al agregar categoría');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en AgregarCategoria: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ACTUALIZAR CATEGORÍA
    function ActualizarCategoria($idCategoria, $nombreCategoria, $descripcion)
    {
        try {
            $nombreCategoria = LimpiarEntrada($nombreCategoria);
            $descripcion = LimpiarEntrada($descripcion);

            $consulta = "CALL ActualizarCategoria($idCategoria, '$nombreCategoria', '$descripcion')";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Categoría actualizada exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al actualizar categoría');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en ActualizarCategoria: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ELIMINAR CATEGORÍA
    function EliminarCategoria($idCategoria)
    {
        try {
            $consulta = "CALL EliminarCategoria($idCategoria)";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Categoría eliminada exitosamente');
            } else {
                return array('success' => false, 'mensaje' => 'Error al eliminar categoría');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en EliminarCategoria: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

    // ========================================
    // ESTADÍSTICAS PARA DASHBOARD
    // ========================================

    // CONTAR TOTAL DE PELÍCULAS
    function ContarTotalPeliculas()
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM contenido WHERE activo = 1";
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);
            return $fila['total'];
        } catch (Exception $e) {
            RegistrarError("Excepción en ContarTotalPeliculas: " . $e->getMessage());
            return 0;
        }
    }

    // CONTAR PELÍCULAS NUEVAS ESTE MES
    function ContarPeliculasNuevasMes()
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM contenido 
                        WHERE MONTH(fechaPublicacion) = MONTH(CURDATE()) 
                        AND YEAR(fechaPublicacion) = YEAR(CURDATE())
                        AND activo = 1";
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);
            return $fila['total'];
        } catch (Exception $e) {
            RegistrarError("Excepción en ContarPeliculasNuevasMes: " . $e->getMessage());
            return 0;
        }
    }

    // CONTAR TOTAL DE GÉNEROS
    function ContarTotalGeneros()
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM genero";
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);
            return $fila['total'];
        } catch (Exception $e) {
            RegistrarError("Excepción en ContarTotalGeneros: " . $e->getMessage());
            return 0;
        }
    }

    // CONTAR TOTAL DE CATEGORÍAS
    function ContarTotalCategorias()
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM categoria";
            $resultado = EjecutarConsulta($consulta);
            $fila = mysqli_fetch_assoc($resultado);
            return $fila['total'];
        } catch (Exception $e) {
            RegistrarError("Excepción en ContarTotalCategorias: " . $e->getMessage());
            return 0;
        }
    }

    // OBTENER PELÍCULAS MÁS POPULARES (por favoritos)
    function ObtenerPeliculasPopulares($limite)
    {
        try {
            $consulta = "SELECT c.idContenido, c.titulo, 
                        COUNT(f.idFavorito) as favoritos,
                        COALESCE(GROUP_CONCAT(DISTINCT g.nombreGenero SEPARATOR ', '), 'Sin género') as genero
                        FROM contenido c
                        LEFT JOIN favoritos f ON c.idContenido = f.idContenido
                        LEFT JOIN contenidoGenero cg ON c.idContenido = cg.idContenido
                        LEFT JOIN genero g ON cg.idGenero = g.idGenero
                        WHERE c.activo = 1
                        GROUP BY c.idContenido, c.titulo
                        ORDER BY favoritos DESC
                        LIMIT $limite";
            
            $resultado = EjecutarConsulta($consulta);
            $peliculas = array();

            if($resultado && mysqli_num_rows($resultado) > 0) {
                while($fila = mysqli_fetch_assoc($resultado)) {
                    $peliculas[] = array(
                        'titulo' => $fila['titulo'],
                        'favoritos' => $fila['favoritos'],
                        'genero' => $fila['genero']
                    );
                }
            }

            return $peliculas;
        } catch (Exception $e) {
            RegistrarError("Excepción en ObtenerPeliculasPopulares: " . $e->getMessage());
            return array();
        }
    }

?>
