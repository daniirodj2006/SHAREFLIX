

function quitarFavorito(idPelicula, titulo) {
    if (confirm('¿Estás segura de quitar "' + titulo + '" de tus favoritos?')) {
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
                // Recargar la página para actualizar la lista
                location.reload();
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


function verDetalles(pelicula) {
    // Llenar los datos en el modal
    document.getElementById('tituloDetalles').textContent = pelicula.titulo;
    document.getElementById('imagenDetalles').src = pelicula.imagen_url;
    document.getElementById('generoDetalles').textContent = pelicula.genero;
    document.getElementById('categoriaDetalles').textContent = pelicula.categoria;
    document.getElementById('anioDetalles').textContent = pelicula.anio;
    document.getElementById('duracionDetalles').textContent = pelicula.duracion;
    document.getElementById('descripcionDetalles').textContent = pelicula.descripcion || 'Sin descripción disponible';
    
    // Configurar botón de quitar en el modal
    const btnQuitar = document.getElementById('btnQuitarModal');
    btnQuitar.onclick = function() {
        quitarFavorito(pelicula.id_pelicula, pelicula.titulo);
        // Cerrar modal
        bootstrap.Modal.getInstance(document.getElementById('modalDetalles')).hide();
    };
    
    // Abrir el modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    modal.show();
}
