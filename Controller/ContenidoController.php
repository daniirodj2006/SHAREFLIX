<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/ContenidoModel.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Controller/UtilesController.php';

    // ========================================
    // GESTIÓN DE CONTENIDO (PELÍCULAS)
    // ========================================

    // AGREGAR PELÍCULA (Admin - Formulario)
    if(isset($_POST["btnAgregarContenido"]))
    {
        ValidarSesionAdmin();
        
        $titulo = SanitizarEntrada($_POST["txtTitulo"]);
        $descripcion = SanitizarEntrada($_POST["txtDescripcion"]);
        $duracion = intval($_POST["txtDuracion"]);
        $calificacion = SanitizarEntrada($_POST["txtCalificacion"]);
        $fechaPublicacion = $_POST["txtFechaPublicacion"];
        $trailer = '';
        
        // Subir imagen
        $nombreImagen = '';
        if(isset($_FILES["filePoster"]) && $_FILES["filePoster"]["error"] == 0) {
            $nombreImagen = SubirImagen($_FILES["filePoster"], 'contenido');
        }

        // Agregar contenido
        $resultado = AgregarContenido($titulo, $descripcion, $duracion, $nombreImagen, $trailer, $calificacion, $fechaPublicacion);

        if($resultado['success']) {
            $idContenido = $resultado['idContenido'];
            
            // Agregar géneros
            if(isset($_POST['generos']) && is_array($_POST['generos'])) {
                foreach($_POST['generos'] as $idGenero) {
                    AgregarGeneroContenido($idContenido, intval($idGenero));
                }
            }
            
            // Agregar categorías
            if(isset($_POST['categorias']) && is_array($_POST['categorias'])) {
                foreach($_POST['categorias'] as $idCategoria) {
                    AgregarCategoriaContenido($idContenido, intval($idCategoria));
                }
            }
        }

        $_POST["Mensaje"] = $resultado['mensaje'];
        $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
    }

    // ACTUALIZAR PELÍCULA (Admin - Formulario)
    if(isset($_POST["btnActualizarContenido"]))
    {
        ValidarSesionAdmin();
        
        $idContenido = intval($_POST["idContenido"]);
        $titulo = SanitizarEntrada($_POST["txtTitulo"]);
        $descripcion = SanitizarEntrada($_POST["txtDescripcion"]);
        $duracion = intval($_POST["txtDuracion"]);
        $calificacion = SanitizarEntrada($_POST["txtCalificacion"]);
        $fechaPublicacion = $_POST["txtFechaPublicacion"];
        $trailer = '';
        
        // Manejar imagen (si se subió una nueva)
        $imagenNueva = '';
        if(isset($_FILES['filePoster']) && $_FILES['filePoster']['error'] == 0) {
            $imagenNueva = SubirImagen($_FILES['filePoster'], 'contenido');
        }
        
        // Actualizar contenido
        $resultado = ActualizarContenido($idContenido, $titulo, $descripcion, $duracion, $imagenNueva, $trailer, $calificacion, $fechaPublicacion);
        
        if($resultado['success']) {
            // Actualizar géneros
            EliminarGenerosContenido($idContenido);
            if(isset($_POST['generos']) && is_array($_POST['generos'])) {
                foreach($_POST['generos'] as $idGenero) {
                    AgregarGeneroContenido($idContenido, intval($idGenero));
                }
            }
            
            // Actualizar categorías
            EliminarCategoriasContenido($idContenido);
            if(isset($_POST['categorias']) && is_array($_POST['categorias'])) {
                foreach($_POST['categorias'] as $idCategoria) {
                    AgregarCategoriaContenido($idContenido, intval($idCategoria));
                }
            }
        }
        
        $_POST["Mensaje"] = $resultado['mensaje'];
        $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
        
        header("Location: GestionContenido.php");
        exit();
    }

    // CAMBIAR ESTADO DE PELÍCULA (Admin - Formulario)
    if(isset($_POST["btnCambiarEstadoContenido"]))
    {
        ValidarSesionAdmin();
        
        $idContenido = intval($_POST["idContenido"]);
        $resultado = CambiarEstadoContenido($idContenido);

        $_POST["Mensaje"] = $resultado['mensaje'];
        $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
    }
    // ELIMINAR CONTENIDO PERMANENTEMENTE
if(isset($_POST["btnEliminarContenido"]))
{
    ValidarSesionAdmin();
    
    $idContenido = intval($_POST["idContenido"]);
    $resultado = EliminarContenido($idContenido);

    $_POST["Mensaje"] = $resultado['mensaje'];
    $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
}

    // CAMBIAR ESTADO CON AJAX (Admin - AJAX)
    if(isset($_POST["cambiarEstadoContenidoAjax"]))
    {
        ValidarSesionAdmin();
        
        $idContenido = intval($_POST["idContenido"]);
        $resultado = CambiarEstadoContenido($idContenido);

        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

    // CONSULTAR TODO EL CONTENIDO (AJAX - Usado en varias vistas)
    if(isset($_POST["consultarContenido"]))
    {
        $resultado = ConsultarContenido();
        $contenido = array();

        if($resultado && mysqli_num_rows($resultado) > 0) {
            while($fila = mysqli_fetch_array($resultado)) {
                $contenido[] = array(
                    'idContenido' => $fila['ConsecutivoContenido'],
                    'titulo' => $fila['Titulo'],
                    'descripcion' => $fila['Descripcion'],
                    'duracion' => $fila['Duracion'],
                    'imagen' => $fila['Imagen'],
                    'trailer' => $fila['Trailer'],
                    'calificacionEdad' => $fila['CalificacionEdad'],
                    'fechaPublicacion' => $fila['fechaPublicacion'],
                    'activo' => $fila['Activo'],
                    'generos' => $fila['Generos'],
                    'categorias' => $fila['Categorias']
                );
            }
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'contenido' => $contenido
        ));
        exit;
    }

    // CONSULTAR CONTENIDO POR ID (AJAX - Usado para detalles de película)
    if(isset($_POST["consultarContenidoPorId"]))
    {
        $idContenido = intval($_POST["idContenido"]);
        $resultado = ConsultarContenidoPorId($idContenido);

        if($resultado && mysqli_num_rows($resultado) > 0) {
            $contenido = mysqli_fetch_array($resultado);
            
            // Obtener géneros del contenido
            $generosResultado = ObtenerGenerosContenido($idContenido);
            $generos = array();
            if($generosResultado) {
                while($genero = mysqli_fetch_array($generosResultado)) {
                    $generos[] = array(
                        'idGenero' => $genero['idGenero'],
                        'nombre' => $genero['nombreGenero']
                    );
                }
            }
            
            // Obtener categorías del contenido
            $categoriasResultado = ObtenerCategoriasContenido($idContenido);
            $categorias = array();
            if($categoriasResultado) {
                while($categoria = mysqli_fetch_array($categoriasResultado)) {
                    $categorias[] = array(
                        'idCategoria' => $categoria['idCategoria'],
                        'nombre' => $categoria['nombreCategoria']
                    );
                }
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => true,
                'contenido' => array(
                    'idContenido' => $contenido['ConsecutivoContenido'],
                    'titulo' => $contenido['Titulo'],
                    'descripcion' => $contenido['Descripcion'],
                    'duracion' => $contenido['Duracion'],
                    'imagen' => $contenido['Imagen'],
                    'trailer' => $contenido['Trailer'],
                    'calificacionEdad' => $contenido['CalificacionEdad'],
                    'fechaPublicacion' => $contenido['fechaPublicacion'],
                    'activo' => $contenido['activo'],
                    'generos' => $generos,
                    'categorias' => $categorias
                )
            ));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => false,
                'mensaje' => 'Contenido no encontrado'
            ));
        }
        exit;
    }

    // BUSCAR CONTENIDO CON FILTROS (AJAX - Usado en catálogo de clientes)
    if(isset($_POST["buscarContenido"]))
    {
        $busqueda = isset($_POST["busqueda"]) ? SanitizarEntrada($_POST["busqueda"]) : '';
        $idGenero = isset($_POST["idGenero"]) ? intval($_POST["idGenero"]) : 0;
        $idCategoria = isset($_POST["idCategoria"]) ? intval($_POST["idCategoria"]) : 0;

        $resultado = BuscarContenido($busqueda, $idGenero, $idCategoria);
        $contenido = array();

        if($resultado && mysqli_num_rows($resultado) > 0) {
            while($fila = mysqli_fetch_array($resultado)) {
                $contenido[] = array(
                    'idContenido' => $fila['ConsecutivoContenido'],
                    'titulo' => $fila['Titulo'],
                    'descripcion' => $fila['Descripcion'],
                    'duracion' => $fila['Duracion'],
                    'imagen' => $fila['Imagen'],
                    'calificacionEdad' => $fila['CalificacionEdad']
                );
            }
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'contenido' => $contenido,
            'total' => count($contenido)
        ));
        exit;
    }

    // ========================================
    // GESTIÓN DE GÉNEROS
    // ========================================

    // AGREGAR GÉNERO (Admin - Formulario)
    if(isset($_POST["btnAgregarGenero"]))
    {
        ValidarSesionAdmin();
        
        $nombreGenero = SanitizarEntrada($_POST["txtNombreGenero"]);
        $descripcion = SanitizarEntrada($_POST["txtDescripcionGenero"]);

        $resultado = AgregarGenero($nombreGenero, $descripcion);

        $_POST["Mensaje"] = $resultado['mensaje'];
        $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
    }

    // ACTUALIZAR GÉNERO (Admin - Formulario)
    if(isset($_POST["btnActualizarGenero"]))
    {
        ValidarSesionAdmin();
        
        $idGenero = intval($_POST["idGenero"]);
        $nombreGenero = SanitizarEntrada($_POST["txtNombreGenero"]);
        $descripcion = SanitizarEntrada($_POST["txtDescripcionGenero"]);

        $resultado = ActualizarGenero($idGenero, $nombreGenero, $descripcion);

        $_POST["Mensaje"] = $resultado['mensaje'];
        $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
    }

    // ELIMINAR GÉNERO (Admin - Formulario)
    if(isset($_POST["btnEliminarGenero"]))
    {
        ValidarSesionAdmin();
        
        $idGenero = intval($_POST["idGenero"]);
        $resultado = EliminarGenero($idGenero);

        $_POST["Mensaje"] = $resultado['mensaje'];
        $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
    }

    // CONSULTAR GÉNEROS (AJAX - Usado en catálogo y filtros)
    if(isset($_POST["consultarGeneros"]))
    {
        $resultado = ConsultarGeneros();
        $generos = array();

        if($resultado && mysqli_num_rows($resultado) > 0) {
            while($fila = mysqli_fetch_array($resultado)) {
                $generos[] = array(
                    'idGenero' => $fila['ConsecutivoGenero'],
                    'nombre' => $fila['Nombre'],
                    'descripcion' => $fila['Descripcion']
                );
            }
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'generos' => $generos
        ));
        exit;
    }

    // AGREGAR GÉNERO (AJAX)
    if(isset($_POST["agregarGeneroAjax"]))
    {
        ValidarSesionAdmin();
        
        $nombreGenero = SanitizarEntrada($_POST["nombreGenero"]);
        $descripcion = SanitizarEntrada($_POST["descripcion"]);

        $resultado = AgregarGenero($nombreGenero, $descripcion);

        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

    // ACTUALIZAR GÉNERO (AJAX)
    if(isset($_POST["actualizarGeneroAjax"]))
    {
        ValidarSesionAdmin();
        
        $idGenero = intval($_POST["idGenero"]);
        $nombreGenero = SanitizarEntrada($_POST["nombreGenero"]);
        $descripcion = SanitizarEntrada($_POST["descripcion"]);

        $resultado = ActualizarGenero($idGenero, $nombreGenero, $descripcion);

        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

    // ELIMINAR GÉNERO (AJAX)
    if(isset($_POST["eliminarGeneroAjax"]))
    {
        ValidarSesionAdmin();
        
        $idGenero = intval($_POST["idGenero"]);
        $resultado = EliminarGenero($idGenero);

        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

    // ========================================
    // GESTIÓN DE CATEGORÍAS
    // ========================================

    // AGREGAR CATEGORÍA (Admin - Formulario)
    if(isset($_POST["btnAgregarCategoria"]))
    {
        ValidarSesionAdmin();
        
        $nombreCategoria = SanitizarEntrada($_POST["txtNombreCategoria"]);
        $descripcion = SanitizarEntrada($_POST["txtDescripcionCategoria"]);

        $resultado = AgregarCategoria($nombreCategoria, $descripcion);

        $_POST["Mensaje"] = $resultado['mensaje'];
        $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
    }

    // ACTUALIZAR CATEGORÍA (Admin - Formulario)
    if(isset($_POST["btnActualizarCategoria"]))
    {
        ValidarSesionAdmin();
        
        $idCategoria = intval($_POST["idCategoria"]);
        $nombreCategoria = SanitizarEntrada($_POST["txtNombreCategoria"]);
        $descripcion = SanitizarEntrada($_POST["txtDescripcionCategoria"]);

        $resultado = ActualizarCategoria($idCategoria, $nombreCategoria, $descripcion);

        $_POST["Mensaje"] = $resultado['mensaje'];
        $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
    }

    // ELIMINAR CATEGORÍA (Admin - Formulario)
    if(isset($_POST["btnEliminarCategoria"]))
    {
        ValidarSesionAdmin();
        
        $idCategoria = intval($_POST["idCategoria"]);
        $resultado = EliminarCategoria($idCategoria);

        $_POST["Mensaje"] = $resultado['mensaje'];
        $_POST["TipoMensaje"] = $resultado['success'] ? "success" : "error";
    }

    // CONSULTAR CATEGORÍAS (AJAX - Usado en catálogo y filtros)
    if(isset($_POST["consultarCategorias"]))
    {
        $resultado = ConsultarCategorias();
        $categorias = array();

        if($resultado && mysqli_num_rows($resultado) > 0) {
            while($fila = mysqli_fetch_array($resultado)) {
                $categorias[] = array(
                    'idCategoria' => $fila['ConsecutivoCategoria'],
                    'nombre' => $fila['Nombre'],
                    'descripcion' => $fila['Descripcion']
                );
            }
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'categorias' => $categorias
        ));
        exit;
    }

    // AGREGAR CATEGORÍA (AJAX)
    if(isset($_POST["agregarCategoriaAjax"]))
    {
        ValidarSesionAdmin();
        
        $nombreCategoria = SanitizarEntrada($_POST["nombreCategoria"]);
        $descripcion = SanitizarEntrada($_POST["descripcion"]);

        $resultado = AgregarCategoria($nombreCategoria, $descripcion);

        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

    // ACTUALIZAR CATEGORÍA (AJAX)
    if(isset($_POST["actualizarCategoriaAjax"]))
    {
        ValidarSesionAdmin();
        
        $idCategoria = intval($_POST["idCategoria"]);
        $nombreCategoria = SanitizarEntrada($_POST["nombreCategoria"]);
        $descripcion = SanitizarEntrada($_POST["descripcion"]);

        $resultado = ActualizarCategoria($idCategoria, $nombreCategoria, $descripcion);

        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

    // ELIMINAR CATEGORÍA (AJAX)
    if(isset($_POST["eliminarCategoriaAjax"]))
    {
        ValidarSesionAdmin();
        
        $idCategoria = intval($_POST["idCategoria"]);
        $resultado = EliminarCategoria($idCategoria);

        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

    // ========================================
    // FUNCIONES AUXILIARES
    // ========================================

    // SUBIR IMAGEN
    function SubirImagen($archivo, $carpeta = 'contenido')
    {
        try {
            if(!isset($archivo) || $archivo['error'] !== 0) {
                return '';
            }

            // Validar tamaño (máximo 5MB)
            $tamanoMaximo = 5 * 1024 * 1024;
            if($archivo['size'] > $tamanoMaximo) {
                return '';
            }

            // Validar tipo de archivo
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $tipoMime = finfo_file($finfo, $archivo['tmp_name']);
            finfo_close($finfo);

            if(!in_array($tipoMime, $tiposPermitidos)) {
                return '';
            }

            // Obtener extensión
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            
            // Generar nombre único
            $nombreArchivo = uniqid() . '_' . time() . '.' . $extension;
            
            // Ruta de destino
            $rutaDestino = $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/img/' . $carpeta . '/' . $nombreArchivo;
            
            // Crear directorio si no existe
            $directorio = $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/View/img/' . $carpeta;
            if(!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }

            // Mover archivo
            if(move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                return $nombreArchivo;
            }

            return '';
        } catch (Exception $e) {
            RegistrarError("Error en SubirImagen: " . $e->getMessage());
            return '';
        }
    }

    // ========================================
    // FUNCIONES PARA CATÁLOGO (Cliente)
    // ========================================

    // OBTENER TODAS LAS PELÍCULAS ACTIVAS
    function ObtenerPeliculasController()
    {
        $resultado = ConsultarContenido();
        $contenido = array();

        if($resultado && mysqli_num_rows($resultado) > 0) {
            while($fila = mysqli_fetch_array($resultado)) {
                if(isset($fila['Activo']) && $fila['Activo'] == 1) {
                    $contenido[] = array(
                        'id_pelicula' => $fila['ConsecutivoContenido'],
                        'titulo' => $fila['Titulo'],
                        'descripcion' => $fila['Descripcion'],
                        'duracion' => $fila['Duracion'],
                        'imagen_url' => !empty($fila['Imagen']) ? '../img/contenido/' . $fila['Imagen'] : '',
                        'trailer' => isset($fila['Trailer']) ? $fila['Trailer'] : '',
                        'anio' => isset($fila['fechaPublicacion']) ? date('Y', strtotime($fila['fechaPublicacion'])) : date('Y'),
                        'generos' => isset($fila['Generos']) ? $fila['Generos'] : '',
                        'categorias' => isset($fila['Categorias']) ? $fila['Categorias'] : '',
                        'calificacion_edad' => isset($fila['CalificacionEdad']) ? $fila['CalificacionEdad'] : 'ATP'
                    );
                }
            }
        }

        return $contenido;
    }

    // OBTENER TODOS LOS GÉNEROS
    function ObtenerGenerosController()
    {
        $resultado = ConsultarGeneros();
        $generos = array();

        if($resultado && mysqli_num_rows($resultado) > 0) {
            while($fila = mysqli_fetch_array($resultado)) {
                $generos[] = array(
                    'id_genero' => $fila['ConsecutivoGenero'],
                    'nombre' => $fila['Nombre']
                );
            }
        }

        return $generos;
    }

    // OBTENER TODAS LAS CATEGORÍAS
    function ObtenerCategoriasController()
    {
        $resultado = ConsultarCategorias();
        $categorias = array();

        if($resultado && mysqli_num_rows($resultado) > 0) {
            while($fila = mysqli_fetch_array($resultado)) {
                $categorias[] = array(
                    'id_categoria' => $fila['ConsecutivoCategoria'],
                    'nombre' => $fila['Nombre']
                );
            }
        }

        return $categorias;
    }

    // OBTENER FAVORITOS DEL USUARIO
    function ObtenerFavoritosUsuarioController($idUsuario)
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/FavoritoModel.php';
        
        if(function_exists('ConsultarFavoritosUsuario')) {
            $resultado = ConsultarFavoritosUsuario($idUsuario);
            $favoritos = array();
            
            if($resultado && mysqli_num_rows($resultado) > 0) {
                while($fila = mysqli_fetch_array($resultado)) {
                    $favoritos[] = array(
                        'id_pelicula' => $fila['idContenido']
                    );
                }
            }
            
            return $favoritos;
        }
        
        return array();
    }
?>