
 // SECCIÓN 1: CONFIGURACIÓN DE COLORES

const coloresShareflix = {
    naranja: '#E50914',
    naranjaClaro: '#FF6B6B',
    amarillo: '#FFC107',
    info: '#17A2B8',
    success: '#28A745',
    dark: '#212529',
    light: '#F8F9FA',
    gris: '#6C757D'
};

// SECCIÓN 2: GRÁFICO DE ACTIVIDAD DE USUARIOS
 

let chartActividad = null;

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartActividad');
    
    if (ctx) {
        crearGraficoActividad();
        
        // Listener para cambiar el período del gráfico
        const selectPeriodo = document.getElementById('periodoGrafico');
        if (selectPeriodo) {
            selectPeriodo.addEventListener('change', function() {
                actualizarGraficoActividad(this.value);
            });
        }
    }
});

function crearGraficoActividad() {
    const ctx = document.getElementById('chartActividad').getContext('2d');
    
    // Datos de ejemplo - En producción, estos vendrían del servidor
    const labels = obtenerEtiquetasFecha(30);
    const datosUsuarios = generarDatosActividad(30);
    
    chartActividad = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Usuarios Activos',
                    data: datosUsuarios,
                    borderColor: coloresShareflix.naranja,
                    backgroundColor: function(context) {
                        const ctx = context.chart.ctx;
                        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, 'rgba(229, 9, 20, 0.3)');
                        gradient.addColorStop(1, 'rgba(229, 9, 20, 0)');
                        return gradient;
                    },
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: coloresShareflix.naranja,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: coloresShareflix.naranja,
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' usuarios activos';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: coloresShareflix.gris,
                        callback: function(value) {
                            return value;
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: coloresShareflix.gris,
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 7
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            }
        }
    });
}

function actualizarGraficoActividad(dias) {
    if (!chartActividad) return;
    
    // Obtener nuevos datos según el período seleccionado
    const labels = obtenerEtiquetasFecha(parseInt(dias));
    const datos = generarDatosActividad(parseInt(dias));
    
    // Actualizar el gráfico
    chartActividad.data.labels = labels;
    chartActividad.data.datasets[0].data = datos;
    chartActividad.update('active');
}

 //SECCIÓN 3: GRÁFICO DE DISTRIBUCIÓN DE USUARIOS


document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartUsuarios');
    
    if (ctx) {
        crearGraficoUsuarios();
    }
});

function crearGraficoUsuarios() {
    const ctx = document.getElementById('chartUsuarios').getContext('2d');
    
    // Datos de ejemplo - En producción, estos vendrían del PHP
    const usuariosGratis = parseInt(document.querySelector('.badge.bg-warning').nextElementSibling?.textContent || 0);
    const usuariosPremium = parseInt(document.querySelector('.badge.bg-shareflix').nextElementSibling?.textContent || 0);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Usuarios Gratis', 'Usuarios Premium'],
            datasets: [{
                data: [usuariosGratis, usuariosPremium],
                backgroundColor: [
                    coloresShareflix.amarillo,
                    coloresShareflix.naranja
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: coloresShareflix.naranja,
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}


 ///SECCIÓN 4: FUNCIONES AUXILIARES
 

function obtenerEtiquetasFecha(dias) {
    const labels = [];
    const hoy = new Date();
    
    for (let i = dias - 1; i >= 0; i--) {
        const fecha = new Date(hoy);
        fecha.setDate(fecha.getDate() - i);
        
        // Formato: "15 Nov"
        const dia = fecha.getDate();
        const mes = fecha.toLocaleDateString('es-ES', { month: 'short' });
        labels.push(dia + ' ' + mes);
    }
    
    return labels;
}

function generarDatosActividad(dias) {
    const datos = [];
    
    for (let i = 0; i < dias; i++) {
        // Simular actividad con variación
        const base = 50;
        const variacion = Math.random() * 40;
        const tendencia = i * 0.5; // Tendencia ascendente
        datos.push(Math.round(base + variacion + tendencia));
    }
    
    return datos;
}


 //ACTUALIZAR DATOS EN TIEMPO REAL (OPCIONAL)
 

function cargarDatosActividad(dias) {
    fetch(`../Controller/AdminController.php?action=obtenerActividad&dias=${dias}`)
        .then(response => response.json())
        .then(data => {
            if (chartActividad) {
                chartActividad.data.labels = data.labels;
                chartActividad.data.datasets[0].data = data.valores;
                chartActividad.update();
            }
        })
        .catch(error => {
            console.error('Error al cargar datos de actividad:', error);
        });
}


 //SECCIÓN 6: ANIMACIONES DE LAS TARJETAS

document.addEventListener('DOMContentLoaded', function() {
    // Animar los números de las estadísticas
    animarNumeros();
    
    // Agregar efecto hover a las tarjetas de acceso rápido
    const quickActions = document.querySelectorAll('.quick-action-card');
    quickActions.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

/**
 * Anima los números de las tarjetas de estadísticas
 */
function animarNumeros() {
    const numeros = document.querySelectorAll('.stats-card h3');
    
    numeros.forEach(elemento => {
        const valorFinal = parseInt(elemento.textContent);
        let valorActual = 0;
        const incremento = Math.ceil(valorFinal / 30);
        const duracion = 1000; // 1 segundo
        const intervalo = duracion / (valorFinal / incremento);
        
        elemento.textContent = '0';
        
        const animacion = setInterval(() => {
            valorActual += incremento;
            
            if (valorActual >= valorFinal) {
                elemento.textContent = valorFinal;
                clearInterval(animacion);
            } else {
                elemento.textContent = valorActual;
            }
        }, intervalo);
    });
}

