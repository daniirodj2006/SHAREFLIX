// ========================================
// SHAREFLIX - CATÁLOGO DE PELÍCULAS
// Sistema de Favoritos + Búsqueda y Filtros
// ========================================

// SECCIÓN 1: BÚSQUEDA DE PELÍCULAS
document.addEventListener('DOMContentLoaded', function() {
    const inputBuscar = document.getElementById('buscarPelicula');
    
    if (inputBuscar) {
        inputBuscar.addEventListener('keyup', function() {
            filtrarPeliculas();
        });
    }
});

// SECCIÓN 2: FILTROS POR GÉNERO Y CATEGORÍA
document.addEventListener('DOMContentLoaded', function() {
    const filtroGenero = document.getElementById('filtroGenero');
    const filtroCategoria = document.getElementById('filtroCategoria');
    
    if (filtroGenero) {
        filtroGenero.addEventListener('change', function() {
            filtrarPeliculas();
        });
    }
    
    if (filtroCategoria) {
        filtroCategoria.addEventListener('change', function() {
            filtrarPeliculas();
        });
    }
});

/**
 * Función principal para filtrar películas
 */
function filtrarPeliculas() {
    const textoBusqueda = document.getElementById('buscarPelicula').value.toLowerCase();
    const generoSeleccionado = document.getElementById('filtroGenero').value;
    const categoriaSeleccionada = document.getElementById('filtroCategoria').value;
    
    const peliculas = document.querySelectorAll('.pelicula-item');
    let peliculasVisibles = 0;
    
    peliculas.forEach(pelicula => {
        const titulo = pelicula.getAttribute('data-titulo');
        const genero = pelicula.getAttribute('data-genero');
        const categoria = pelicula.getAttribute('data-categoria');
        
        // Verificar si cumple con todos los filtros
        let mostrar = true;
        
        // Filtro de búsqueda
        if (textoBusqueda && !titulo.includes(textoBusqueda)) {
            mostrar = false;
        }
        
        // Filtro de género
        if (generoSeleccionado && genero !== generoSeleccionado) {
            mostrar = false;
        }
        
        // Filtro de categoría
        if (categoriaSeleccionada && categoria !== categoriaSeleccionada) {
            mostrar = false;
        }
        
        // Mostrar u ocultar
        if (mostrar) {
            pelicula.style.display = '';
            peliculasVisibles++;
        } else {
            pelicula.style.display = 'none';
        }
    });
    
    // Actualizar contador
    actualizarContador(peliculasVisibles);
    
    // Mostrar mensaje si no hay resultados
    const noResultados = document.getElementById('noResultados');
    if (peliculasVisibles === 0) {
        noResultados.style.display = 'block';
    } else {
        noResultados.style.display = 'none';
    }
}

/**
 * Actualizar el contador de películas
 */
function actualizarContador(cantidad) {
    const contador = document.getElementById('contadorPeliculas');
    if (contador) {
        contador.textContent = `Mostrando ${cantidad} películas`;
    }
}

// ========================================
// SECCIÓN 3: SISTEMA DE FAVORITOS
// ========================================

/**
 * Agregar película a favoritos
 * CORREGIDO: Ahora envía los parámetros correctos al controlador
 */
function agregarFavorito(idPelicula) {
    console.log('Agregando a favoritos:', idPelicula);
    
    // Verificar límite para usuarios gratis
    if (idRol === 2) { // Rol 2 = Gratis
        // Contar cuántos favoritos activos tiene
        const favoritosActuales = document.querySelectorAll('.btn-favorito.active').length;
        
        if (favoritosActuales >= limiteGratis) {
            mostrarAlertaUpgrade();
            return;
        }
    }
    
    // Crear FormData con los nombres CORRECTOS que espera el controlador
    const formData = new FormData();
    formData.append('agregarFavoritoAjax', '1');  // ✅ Nombre correcto
    formData.append('idContenido', idPelicula);    // ✅ Nombre correcto
    
    // Enviar al servidor
    fetch('../../Controller/FavoritoController.php', {  // ✅ Ruta correcta
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data);
        
        if (data.success) {
            // Cambiar el botón visualmente
            actualizarBotonFavorito(idPelicula, true);
            
            // Mostrar mensaje de éxito
            mostrarNotificacion('¡Agregado a favoritos! 💕', 'success');
        } else {
            // Si hay error o límite alcanzado
            if (data.limite) {
                mostrarAlertaUpgrade();
            } else {
                mostrarNotificacion(data.mensaje || 'Error al agregar a favoritos', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        mostrarNotificacion('Hubo un error al agregar a favoritos', 'error');
    });
}

/**
 * Quitar película de favoritos
 * CORREGIDO: Ahora envía los parámetros correctos al controlador
 */
function quitarFavorito(idPelicula) {
    console.log('Quitando de favoritos:', idPelicula);
    
    if (confirm('¿Quieres quitar esta película de tus favoritos?')) {
        // Crear FormData con los nombres CORRECTOS que espera el controlador
        const formData = new FormData();
        formData.append('eliminarFavoritoAjax', '1');  // ✅ Nombre correcto
        formData.append('idContenido', idPelicula);     // ✅ Nombre correcto
        
        // Enviar al servidor
        fetch('../../Controller/FavoritoController.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data);
            
            if (data.success) {
                // Cambiar el botón visualmente
                actualizarBotonFavorito(idPelicula, false);
                
                // Mostrar mensaje
                mostrarNotificacion('Quitado de favoritos', 'info');
            } else {
                mostrarNotificacion(data.mensaje || 'Error al quitar de favoritos', 'error');
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            mostrarNotificacion('Hubo un error al quitar de favoritos', 'error');
        });
    }
}

/**
 * Actualizar visualmente el botón de favorito
 */
function actualizarBotonFavorito(idPelicula, esAgregar) {
    // Buscar todos los botones de esta película
    const selectores = [
        `[onclick*="agregarFavorito(${idPelicula})"]`,
        `[onclick*="quitarFavorito(${idPelicula})"]`
    ];
    
    selectores.forEach(selector => {
        const botones = document.querySelectorAll(selector);
        botones.forEach(btn => {
            if (esAgregar) {
                // Cambiar a estado "EN FAVORITOS"
                btn.classList.remove('btn-outline-light');
                btn.classList.add('btn-danger', 'active');
                btn.innerHTML = '<i class="bi bi-heart-fill me-2"></i>Quitar';
                btn.setAttribute('onclick', `quitarFavorito(${idPelicula})`);
                btn.setAttribute('title', 'Quitar de favoritos');
            } else {
                // Cambiar a estado "NO EN FAVORITOS"
                btn.classList.remove('btn-danger', 'active');
                btn.classList.add('btn-outline-light');
                btn.innerHTML = '<i class="bi bi-heart me-2"></i>Favorito';
                btn.setAttribute('onclick', `agregarFavorito(${idPelicula})`);
                btn.setAttribute('title', 'Agregar a favoritos');
            }
        });
    });
}

/**
 * Mostrar alerta de upgrade a Premium
 */
function mostrarAlertaUpgrade() {
    const mensaje = `
        <div class="text-center">
            <i class="bi bi-heart-fill" style="font-size: 3rem; color: #FF8C42;"></i>
            <h5 class="mt-3 mb-2">¡Límite Alcanzado!</h5>
            <p class="mb-3">Has alcanzado el límite de ${limiteGratis} películas favoritas.</p>
            <p class="text-muted">Actualiza a <strong>Premium</strong> para agregar favoritos ilimitados 💎</p>
        </div>
    `;
    
    // Puedes usar SweetAlert o un modal de Bootstrap
    alert('Has alcanzado el límite de ' + limiteGratis + ' películas favoritas.\n\n' +
          '¡Actualiza a Premium para agregar favoritos ilimitados! 💎');
}

// ========================================
// SECCIÓN 4: VER DETALLES DE PELÍCULA
// ========================================

function verDetalles(pelicula) {
    // Llenar los datos en el modal
    document.getElementById('tituloDetalles').textContent = pelicula.titulo;
    document.getElementById('imagenDetalles').src = pelicula.imagen_url || '';
    document.getElementById('generoDetalles').textContent = pelicula.generos || 'Sin género';
    document.getElementById('categoriaDetalles').textContent = pelicula.categorias || 'Sin categoría';
    document.getElementById('anioDetalles').textContent = pelicula.anio;
    document.getElementById('duracionDetalles').textContent = pelicula.duracion;
    document.getElementById('descripcionDetalles').textContent = pelicula.descripcion || 'Sin descripción disponible';
    
    // Configurar botón de favorito en el modal
    const btnFavorito = document.getElementById('btnFavoritoModal');
    
    // Verificar si ya está en favoritos
    const yaFavorito = document.querySelector(`.btn-favorito.active[onclick*="${pelicula.id_pelicula}"]`);
    
    if (yaFavorito) {
        btnFavorito.innerHTML = '<i class="bi bi-heart-fill me-2"></i>Quitar de Favoritos';
        btnFavorito.className = 'btn btn-danger';
        btnFavorito.onclick = function() {
            quitarFavorito(pelicula.id_pelicula);
            // Cerrar modal
            const modalElement = document.getElementById('modalDetalles');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        };
    } else {
        btnFavorito.innerHTML = '<i class="bi bi-heart me-2"></i>Agregar a Favoritos';
        btnFavorito.className = 'btn btn-shareflix';
        btnFavorito.onclick = function() {
            agregarFavorito(pelicula.id_pelicula);
            // Cerrar modal
            const modalElement = document.getElementById('modalDetalles');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        };
    }
    
    // Abrir el modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    modal.show();
}

// ========================================
// SECCIÓN 5: NOTIFICACIONES
// ========================================

function mostrarNotificacion(mensaje, tipo) {
    // Determinar color según el tipo
    let claseAlerta = 'alert-info';
    let icono = 'bi-info-circle';
    
    if (tipo === 'success') {
        claseAlerta = 'alert-success';
        icono = 'bi-check-circle-fill';
    } else if (tipo === 'error') {
        claseAlerta = 'alert-danger';
        icono = 'bi-exclamation-circle-fill';
    }
    
    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.className = `alert ${claseAlerta} alert-dismissible fade show position-fixed`;
    notificacion.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 280px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);';
    notificacion.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi ${icono} me-2" style="font-size: 1.2rem;"></i>
            <div class="flex-grow-1">${mensaje}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Agregar al body
    document.body.appendChild(notificacion);
    
    // Auto-remover después de 3 segundos
    setTimeout(() => {
        notificacion.classList.remove('show');
        setTimeout(() => notificacion.remove(), 150);
    }, 3000);
}

// ========================================
// LOGS DE DEPURACIÓN
// ========================================
console.log('✅ Catalogo.js cargado correctamente');
console.log('Usuario ID:', typeof idUsuario !== 'undefined' ? idUsuario : 'No definido');
console.log('Rol ID:', typeof idRol !== 'undefined' ? idRol : 'No definido');
console.log('Límite Gratis:', typeof limiteGratis !== 'undefined' ? limiteGratis : 'No definido');