
//SECCIÓN 1: BÚSQUEDA DE PELÍCULAS
 

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


 // SECCIÓN 3: AGREGAR A FAVORITOS
 
function agregarFavorito(idPelicula) {
    // Verificar límite para usuarios gratis
    if (idRol === 2) { // Rol 2 = Gratis
        // Contar cuántos favoritos activos tiene
        const favoritosActuales = document.querySelectorAll('.btn-favorito.active').length;
        
        if (favoritosActuales >= limiteGratis) {
            alert('Has alcanzado el límite de ' + limiteGratis + ' películas favoritas.\n\n' +
                  '¡Actualiza a Premium para agregar favoritos ilimitados!');
            return;
        }
    }
    
    // Crear FormData
    const formData = new FormData();
    formData.append('accion', 'agregar');
    formData.append('idPelicula', idPelicula);
    formData.append('idUsuario', idUsuario);
    
    // Enviar al servidor
    fetch('../../Controller/FavoritosController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cambiar el botón visualmente
            const botones = document.querySelectorAll(`[onclick*="agregarFavorito(${idPelicula})"]`);
            botones.forEach(btn => {
                btn.classList.remove('btn-outline-light');
                btn.classList.add('btn-danger', 'active');
                btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
                btn.setAttribute('onclick', `quitarFavorito(${idPelicula})`);
                btn.setAttribute('title', 'Quitar de favoritos');
            });
            
            // Mostrar mensaje
            mostrarNotificacion('¡Agregado a favoritos!', 'success');
        } else {
            alert('Error: ' + data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al agregar a favoritos');
    });
}

 // SECCIÓN 4: QUITAR DE FAVORITOS


function quitarFavorito(idPelicula) {
    if (confirm('¿Quieres quitar esta película de tus favoritos?')) {
        // Crear FormData
        const formData = new FormData();
        formData.append('accion', 'quitar');
        formData.append('idPelicula', idPelicula);
        formData.append('idUsuario', idUsuario);
        
        // Enviar al servidor
        fetch('../../Controller/FavoritosController.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cambiar el botón visualmente
                const botones = document.querySelectorAll(`[onclick*="quitarFavorito(${idPelicula})"]`);
                botones.forEach(btn => {
                    btn.classList.remove('btn-danger', 'active');
                    btn.classList.add('btn-outline-light');
                    btn.innerHTML = '<i class="bi bi-heart"></i>';
                    btn.setAttribute('onclick', `agregarFavorito(${idPelicula})`);
                    btn.setAttribute('title', 'Agregar a favoritos');
                });
                
                // Mostrar mensaje
                mostrarNotificacion('Quitado de favoritos', 'info');
            } else {
                alert('Error: ' + data.mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al quitar de favoritos');
        });
    }
}

 //SECCIÓN 5: VER DETALLES DE PELÍCULA
 
function verDetalles(pelicula) {
    // Llenar los datos en el modal
    document.getElementById('tituloDetalles').textContent = pelicula.titulo;
    document.getElementById('imagenDetalles').src = pelicula.imagen_url;
    document.getElementById('generoDetalles').textContent = pelicula.genero;
    document.getElementById('categoriaDetalles').textContent = pelicula.categoria;
    document.getElementById('anioDetalles').textContent = pelicula.anio;
    document.getElementById('duracionDetalles').textContent = pelicula.duracion;
    document.getElementById('descripcionDetalles').textContent = pelicula.descripcion || 'Sin descripción disponible';
    
    // Configurar botón de favorito en el modal
    const btnFavorito = document.getElementById('btnFavoritoModal');
    
    // Verificar si ya está en favoritos
    const yaFavorito = document.querySelector(`.btn-favorito.active[onclick*="${pelicula.id_pelicula}"]`);
    
    if (yaFavorito) {
        btnFavorito.innerHTML = '<i class="bi bi-heart-fill me-2"></i>Quitar de Favoritos';
        btnFavorito.onclick = function() {
            quitarFavorito(pelicula.id_pelicula);
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('modalDetalles')).hide();
        };
    } else {
        btnFavorito.innerHTML = '<i class="bi bi-heart me-2"></i>Agregar a Favoritos';
        btnFavorito.onclick = function() {
            agregarFavorito(pelicula.id_pelicula);
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('modalDetalles')).hide();
        };
    }
    
    // Abrir el modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    modal.show();
}

 // SECCIÓN 6: NOTIFICACIONES

function mostrarNotificacion(mensaje, tipo) {
    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.className = `alert alert-${tipo === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    notificacion.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
    notificacion.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Agregar al body
    document.body.appendChild(notificacion);
    
    // Auto-remover después de 3 segundos
    setTimeout(() => {
        notificacion.classList.remove('show');
        setTimeout(() => notificacion.remove(), 150);
    }, 3000);
}

