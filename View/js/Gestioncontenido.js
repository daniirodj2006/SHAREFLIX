
 //SECCIÓN 1: PELÍCULAS

// Limpiar el formulario de película
function limpiarFormularioPelicula() {
    document.getElementById('formPelicula').reset();
    document.getElementById('idPelicula').value = '';
    document.getElementById('tituloModal').innerHTML = '<i class="bi bi-film me-2"></i>Agregar Película';
}

// Editar película (cargar datos en el modal)
function editarPelicula(pelicula) {
    document.getElementById('idPelicula').value = pelicula.id_pelicula;
    document.getElementById('titulo').value = pelicula.titulo;
    document.getElementById('anio').value = pelicula.anio;
    document.getElementById('idGeneroSelect').value = pelicula.id_genero;
    document.getElementById('idCategoriaSelect').value = pelicula.id_categoria;
    document.getElementById('duracion').value = pelicula.duracion;
    document.getElementById('imagenUrl').value = pelicula.imagen_url;
    document.getElementById('descripcion').value = pelicula.descripcion || '';
    
    document.getElementById('tituloModal').innerHTML = '<i class="bi bi-pencil me-2"></i>Editar Película';
    
    // Abrir el modal
    const modal = new bootstrap.Modal(document.getElementById('modalPelicula'));
    modal.show();
}

// Guardar película (crear o actualizar)
function guardarPelicula() {
    const form = document.getElementById('formPelicula');
    
    // Validar formulario
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const idPelicula = document.getElementById('idPelicula').value;
    const accion = idPelicula ? 'actualizar' : 'crear';
    
    // Crear FormData con los datos
    const formData = new FormData(form);
    formData.append('accion', accion);
    
    // Enviar al servidor
    fetch('../../Controller/ContenidoController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.mensaje);
            location.reload(); // Recargar la página para ver los cambios
        } else {
            alert('Error: ' + data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al guardar la película');
    });
}

// Eliminar película
function eliminarPelicula(id, titulo) {
    if (confirm('¿Estás segura de eliminar la película "' + titulo + '"?')) {
        const formData = new FormData();
        formData.append('accion', 'eliminar');
        formData.append('idPelicula', id);
        
        fetch('../../Controller/ContenidoController.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.mensaje);
                location.reload();
            } else {
                alert('Error: ' + data.mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al eliminar la película');
        });
    }
}

// Buscar película
document.addEventListener('DOMContentLoaded', function() {
    const inputBuscar = document.getElementById('buscarPelicula');
    
    if (inputBuscar) {
        inputBuscar.addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaPeliculas tbody tr');
            
            filas.forEach(fila => {
                const titulo = fila.cells[1].textContent.toLowerCase();
                
                if (titulo.includes(filtro)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    }
});


 //SECCIÓN 2: GÉNEROS
 
// Guardar género
document.addEventListener('DOMContentLoaded', function() {
    const formGenero = document.getElementById('formGenero');
    
    if (formGenero) {
        formGenero.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const idGenero = document.getElementById('idGenero').value;
            const accion = idGenero ? 'actualizarGenero' : 'crearGenero';
            
            const formData = new FormData(this);
            formData.append('accion', accion);
            
            fetch('../../Controller/ContenidoController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.mensaje);
                    location.reload();
                } else {
                    alert('Error: ' + data.mensaje);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al guardar el género');
            });
        });
    }
});

// Editar género
function editarGenero(genero) {
    document.getElementById('idGenero').value = genero.id_genero;
    document.getElementById('nombreGenero').value = genero.nombre;
    document.getElementById('descripcionGenero').value = genero.descripcion || '';
    
    // Scroll al formulario
    document.getElementById('nombreGenero').focus();
}

// Eliminar género
function eliminarGenero(id, nombre) {
    if (confirm('¿Estás segura de eliminar el género "' + nombre + '"?\n\nNota: No se eliminará si hay películas asociadas.')) {
        const formData = new FormData();
        formData.append('accion', 'eliminarGenero');
        formData.append('idGenero', id);
        
        fetch('../../Controller/ContenidoController.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.mensaje);
                location.reload();
            } else {
                alert('Error: ' + data.mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al eliminar el género');
        });
    }
}


 //SECCIÓN 3: CATEGORÍAS
 

// Guardar categoría
document.addEventListener('DOMContentLoaded', function() {
    const formCategoria = document.getElementById('formCategoria');
    
    if (formCategoria) {
        formCategoria.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const idCategoria = document.getElementById('idCategoria').value;
            const accion = idCategoria ? 'actualizarCategoria' : 'crearCategoria';
            
            const formData = new FormData(this);
            formData.append('accion', accion);
            
            fetch('../../Controller/ContenidoController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.mensaje);
                    location.reload();
                } else {
                    alert('Error: ' + data.mensaje);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al guardar la categoría');
            });
        });
    }
});

// Editar categoría
function editarCategoria(categoria) {
    document.getElementById('idCategoria').value = categoria.id_categoria;
    document.getElementById('nombreCategoria').value = categoria.nombre;
    document.getElementById('descripcionCategoria').value = categoria.descripcion || '';
    
    // Scroll al formulario
    document.getElementById('nombreCategoria').focus();
}

// Eliminar categoría
function eliminarCategoria(id, nombre) {
    if (confirm('¿Estás segura de eliminar la categoría "' + nombre + '"?\n\nNota: No se eliminará si hay películas asociadas.')) {
        const formData = new FormData();
        formData.append('accion', 'eliminarCategoria');
        formData.append('idCategoria', id);
        
        fetch('../../Controller/ContenidoController.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.mensaje);
                location.reload();
            } else {
                alert('Error: ' + data.mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al eliminar la categoría');
        });
    }
}

