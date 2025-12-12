<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UtilesModel.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UsuarioModel.php';

// AGREGAR A FAVORITOS
function AgregarFavorito($idUsuario, $idContenido)
{
    try {
        // Verificar límite de favoritos
        $limiteInfo = VerificarLimiteFavoritos($idUsuario);

        if (!$limiteInfo['puedeAgregar']) {
            return array(
                'success' => false,
                'mensaje' => 'Has alcanzado el límite de favoritos (' . $limiteInfo['limite'] . '). Actualiza a Premium para favoritos ilimitados.',
                'limite' => true
            );
        }

        // Verificar que no esté ya en favoritos
        if (EstaEnFavoritos($idUsuario, $idContenido)) {
            return array('success' => false, 'mensaje' => 'Este contenido ya está en tus favoritos');
        }

        // Agregar a favoritos
        $consulta = "CALL AgregarFavorito($idUsuario, $idContenido)";
        $resultado = LlamarProcedimiento($consulta);

        if ($resultado) {
            $fila = mysqli_fetch_array($resultado);

            if ($fila['success'] == 1) {
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

        if ($resultado) {
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

        if ($resultado) {
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
        $consulta = "CALL ObtenerIdsFavoritos($idUsuario)";
        $resultado = EjecutarConsulta($consulta);

        $ids = array();
        if ($resultado) {
            while ($fila = mysqli_fetch_array($resultado)) {
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
        if (EstaEnFavoritos($idUsuario, $idContenido)) {
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
        $consulta = "CALL ContarFavoritos($idUsuario)";
        $resultado = EjecutarConsulta($consulta);

        if ($resultado) {
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
        $consulta = "CALL LimpiarFavoritosInactivos()";
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
        $consulta = "CALL ConsultarFavoritosPaginados($idUsuario, $limite, $offset)";
        return EjecutarConsulta($consulta);
    } catch (Exception $e) {
        RegistrarError("Excepción en ConsultarFavoritosPaginados: " . $e->getMessage());
        return null;
    }
}

// Funciones necesarias para ObtenerEstadisticasFavoritos()
function ObtenerFavoritosPorTipo($idUsuario)
{
    $consulta = "CALL ObtenerFavoritosPorTipo($idUsuario)";
    return LlamarProcedimiento($consulta);
}

function ObtenerUltimoFavoritoAgregado($idUsuario)
{
    $consulta = "CALL ObtenerUltimoFavoritoAgregado($idUsuario)";
    return LlamarProcedimiento($consulta);
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

        // Estadísticas por tipo
        $resultado = ObtenerFavoritosPorTipo($idUsuario);
        $estadisticas['porTipo'] = array();

        if ($resultado) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $estadisticas['porTipo'][$fila['nombreTipo']] = $fila['cantidad'];
            }
        }

        // Último favorito agregado
        $resultado = ObtenerUltimoFavoritoAgregado($idUsuario);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
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
        $consulta = "CALL EliminarTodosFavoritos($idUsuario)";
        $resultado = EjecutarSentencia($consulta);

        if ($resultado) {
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

        if ($limiteInfo['actual'] > $limiteInfo['limite']) {
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

        $consulta = "CALL BuscarEnFavoritos($idUsuario, '$termino')";

        return EjecutarConsulta($consulta);
    } catch (Exception $e) {
        RegistrarError("Excepción en BuscarEnFavoritos: " . $e->getMessage());
        return null;
    }
}
