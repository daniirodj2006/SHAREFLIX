<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UtilesModel.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UsuarioModel.php';

  


    
    // AGREGAR A FAVORITOS
   
    function AgregarFavorito($idUsuario, $idContenido)
    {
        try {
            // Verificar límite de favoritos
            $limiteInfo = VerificarLimiteFavoritos($idUsuario);

            if(!$limiteInfo['puedeAgregar']) {
                return array(
                    'success' => false, 
                    'mensaje' => 'Has alcanzado el límite de favoritos (' . $limiteInfo['limite'] . '). Actualiza a Premium para favoritos ilimitados.',
                    'limite' => true
                );
            }

            // Verificar que no esté ya en favoritos
            if(EstaEnFavoritos($idUsuario, $idContenido)) {
                return array('success' => false, 'mensaje' => 'Este contenido ya está en tus favoritos');
            }

            // Agregar a favoritos
            $consulta = "CALL AgregarFavorito($idUsuario, $idContenido)";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                $fila = mysqli_fetch_array($resultado);
                
                if($fila['success'] == 1) {
                    return array('success' => true, 'mensaje' => $fila['mensaje']);
                } else {
                    return array('success' => false, 'mensaje' => $fila['mensaje'], 'limite' => true);
                }
            } else {
                return array('success' => false, 'mensaje' => 'Error al agregar a favoritos');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en AgregarFavorito: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

  
    // ELIMINAR DE FAVORITOS
 
    
    function EliminarFavorito($idUsuario, $idContenido)
    {
        try {
            $consulta = "CALL EliminarFavorito($idUsuario, $idContenido)";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Eliminado de favoritos');
            } else {
                return array('success' => false, 'mensaje' => 'Error al eliminar de favoritos');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en EliminarFavorito: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

  
    // CONSULTAR FAVORITOS DEL USUARIO
   
    function ConsultarFavoritos($idUsuario)
    {
        try {
            $consulta = "CALL ConsultarFavoritos($idUsuario)";
            return LlamarProcedimiento($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en ConsultarFavoritos: " . $e->getMessage());
            return null;
        }
    }

    
    // VERIFICAR SI ESTÁ EN FAVORITOS
   
    
    function EstaEnFavoritos($idUsuario, $idContenido)
    {
        try {
            $consulta = "CALL VerificarFavorito($idUsuario, $idContenido)";
            $resultado = LlamarProcedimiento($consulta);

            if($resultado) {
                $fila = mysqli_fetch_array($resultado);
                return $fila['esFavorito'] > 0;
            }
            return false;
        } catch (Exception $e) {
            RegistrarError("Excepción en EstaEnFavoritos: " . $e->getMessage());
            return false;
        }
    }

    // OBTENER TODOS LOS IDS DE FAVORITOS DEL USUARIO

    
    function ObtenerIdsFavoritos($idUsuario)
    {
        try {
            $consulta = "SELECT idContenido FROM favoritos WHERE idUsuario = $idUsuario";
            $resultado = EjecutarConsulta($consulta);

            $ids = array();
            if($resultado) {
                while($fila = mysqli_fetch_array($resultado)) {
                    $ids[] = $fila['idContenido'];
                }
            }
            return $ids;
        } catch (Exception $e) {
            RegistrarError("Excepción en ObtenerIdsFavoritos: " . $e->getMessage());
            return array();
        }
    }


    // TOGGLE FAVORITO (Agregar o Eliminar)
   
    function ToggleFavorito($idUsuario, $idContenido)
    {
        try {
            if(EstaEnFavoritos($idUsuario, $idContenido)) {
                return EliminarFavorito($idUsuario, $idContenido);
            } else {
                return AgregarFavorito($idUsuario, $idContenido);
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en ToggleFavorito: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }


    // CONTAR FAVORITOS DEL USUARIO
  
    function ContarFavoritos($idUsuario)
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM favoritos WHERE idUsuario = $idUsuario";
            $resultado = EjecutarConsulta($consulta);

            if($resultado) {
                $fila = mysqli_fetch_assoc($resultado);
                return $fila['total'];
            }
            return 0;
        } catch (Exception $e) {
            RegistrarError("Excepción en ContarFavoritos: " . $e->getMessage());
            return 0;
        }
    }

  
    // LIMPIAR FAVORITOS ANTIGUOS (Mantenimiento)
 
    
    function LimpiarFavoritosInactivos()
    {
        try {
            // Eliminar favoritos de contenido inactivo
            $consulta = "DELETE f FROM favoritos f
                        INNER JOIN contenido c ON f.idContenido = c.idContenido
                        WHERE c.activo = 0";
            
            return EjecutarSentencia($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en LimpiarFavoritosInactivos: " . $e->getMessage());
            return false;
        }
    }


    // OBTENER FAVORITOS CON PAGINACIÓN
  
    
    function ConsultarFavoritosPaginados($idUsuario, $limite = 12, $offset = 0)
    {
        try {
            $consulta = "SELECT 
                        f.idFavorito AS ConsecutivoFavorito,
                        c.idContenido AS ConsecutivoContenido,
                        c.titulo AS Titulo,
                        c.descripcion AS Descripcion,
                        c.duracion AS Duracion,
                        c.imagen AS Imagen,
                        c.calificacionEdad AS CalificacionEdad,
                        tc.nombreTipo AS NombreTipo,
                        f.fechaAgregado
                        FROM favoritos f
                        INNER JOIN contenido c ON f.idContenido = c.idContenido
                        INNER JOIN tipoContenido tc ON c.idTipoContenido = tc.idTipoContenido
                        WHERE f.idUsuario = $idUsuario
                        AND c.activo = 1
                        ORDER BY f.fechaAgregado DESC
                        LIMIT $limite OFFSET $offset";
            
            return EjecutarConsulta($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en ConsultarFavoritosPaginados: " . $e->getMessage());
            return null;
        }
    }

   
    // OBTENER ESTADÍSTICAS DE FAVORITOS

    function ObtenerEstadisticasFavoritos($idUsuario)
    {
        try {
            $estadisticas = array();

            // Total de favoritos
            $estadisticas['total'] = ContarFavoritos($idUsuario);

            // Límite según suscripción
            $limiteInfo = VerificarLimiteFavoritos($idUsuario);
            $estadisticas['limite'] = $limiteInfo['limite'];
            $estadisticas['disponible'] = $limiteInfo['disponible'];
            $estadisticas['porcentaje'] = ($estadisticas['total'] / $estadisticas['limite']) * 100;

            // Favoritos por tipo
            $consulta = "SELECT tc.nombreTipo, COUNT(*) as cantidad
                        FROM favoritos f
                        INNER JOIN contenido c ON f.idContenido = c.idContenido
                        INNER JOIN tipoContenido tc ON c.idTipoContenido = tc.idTipoContenido
                        WHERE f.idUsuario = $idUsuario
                        GROUP BY tc.nombreTipo";
            
            $resultado = EjecutarConsulta($consulta);
            $estadisticas['porTipo'] = array();
            
            if($resultado) {
                while($fila = mysqli_fetch_array($resultado)) {
                    $estadisticas['porTipo'][$fila['nombreTipo']] = $fila['cantidad'];
                }
            }

            // Último agregado
            $consulta = "SELECT c.titulo, f.fechaAgregado
                        FROM favoritos f
                        INNER JOIN contenido c ON f.idContenido = c.idContenido
                        WHERE f.idUsuario = $idUsuario
                        ORDER BY f.fechaAgregado DESC
                        LIMIT 1";
            
            $resultado = EjecutarConsulta($consulta);
            
            if($resultado && mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_array($resultado);
                $estadisticas['ultimoAgregado'] = array(
                    'titulo' => $fila['titulo'],
                    'fecha' => $fila['fechaAgregado']
                );
            } else {
                $estadisticas['ultimoAgregado'] = null;
            }

            return $estadisticas;
        } catch (Exception $e) {
            RegistrarError("Excepción en ObtenerEstadisticasFavoritos: " . $e->getMessage());
            return null;
        }
    }

    // ELIMINAR TODOS LOS FAVORITOS DEL USUARIO
   
    
    function EliminarTodosFavoritos($idUsuario)
    {
        try {
            $consulta = "DELETE FROM favoritos WHERE idUsuario = $idUsuario";
            $resultado = EjecutarSentencia($consulta);

            if($resultado) {
                return array('success' => true, 'mensaje' => 'Todos los favoritos han sido eliminados');
            } else {
                return array('success' => false, 'mensaje' => 'Error al eliminar favoritos');
            }
        } catch (Exception $e) {
            RegistrarError("Excepción en EliminarTodosFavoritos: " . $e->getMessage());
            return array('success' => false, 'mensaje' => 'Error en el servidor');
        }
    }

   
    // VALIDAR EXCESO DE FAVORITOS (Por si cambió suscripción)
  
    function ValidarExcesoFavoritos($idUsuario)
    {
        try {
            $limiteInfo = VerificarLimiteFavoritos($idUsuario);
            
            if($limiteInfo['actual'] > $limiteInfo['limite']) {
                return array(
                    'exceso' => true,
                    'cantidad' => $limiteInfo['actual'] - $limiteInfo['limite'],
                    'mensaje' => 'Tienes ' . ($limiteInfo['actual'] - $limiteInfo['limite']) . ' favorito(s) de más. Por favor elimina algunos o actualiza tu suscripción.'
                );
            }
            
            return array('exceso' => false);
        } catch (Exception $e) {
            RegistrarError("Excepción en ValidarExcesoFavoritos: " . $e->getMessage());
            return array('exceso' => false);
        }
    }

    // BUSCAR EN FAVORITOS
    
    function BuscarEnFavoritos($idUsuario, $termino)
    {
        try {
            $termino = LimpiarEntrada($termino);
            
            $consulta = "SELECT 
                        f.idFavorito AS ConsecutivoFavorito,
                        c.idContenido AS ConsecutivoContenido,
                        c.titulo AS Titulo,
                        c.descripcion AS Descripcion,
                        c.duracion AS Duracion,
                        c.imagen AS Imagen,
                        c.calificacionEdad AS CalificacionEdad,
                        tc.nombreTipo AS NombreTipo,
                        f.fechaAgregado
                        FROM favoritos f
                        INNER JOIN contenido c ON f.idContenido = c.idContenido
                        INNER JOIN tipoContenido tc ON c.idTipoContenido = tc.idTipoContenido
                        WHERE f.idUsuario = $idUsuario
                        AND c.activo = 1
                        AND (c.titulo LIKE '%$termino%' OR c.descripcion LIKE '%$termino%')
                        ORDER BY f.fechaAgregado DESC";
            
            return EjecutarConsulta($consulta);
        } catch (Exception $e) {
            RegistrarError("Excepción en BuscarEnFavoritos: " . $e->getMessage());
            return null;
        }
    }
?>
```

---

