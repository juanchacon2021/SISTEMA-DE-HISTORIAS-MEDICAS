function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos, 'estadisticas');
}

function consultarCronicos() {
    var datos = new FormData();
    datos.append('accion', 'consultar_cronicos');
    enviaAjax(datos, 'consultarCronicos');
}

function consultarTotalesGenerales() {
    var datos = new FormData();
    datos.append('accion', 'totales_generales');
    enviaAjax(datos, 'totales_generales');
}

let graficoEmergencias = null;

function consultarEmergenciasMes(mes, anio) {
    var datos = new FormData();
    datos.append('accion', 'emergencias_mes');
    datos.append('mes', mes);
    datos.append('anio', anio);
    enviaAjax(datos, 'emergencias_mes');
}

function consultarConsultasMes(mes, anio) {
    var datos = new FormData();
    datos.append('accion', 'consultas_mes');
    datos.append('mes', mes);
    datos.append('anio', anio);
    enviaAjax(datos, 'consultas_mes');
}

function actualizarGrafico() {
    const mes = document.getElementById('selectMes').value;
    const anio = document.getElementById('selectAnio').value;
    consultarEmergenciasMes(mes, anio);
}

function actualizarGraficoConsultas() {
    const mes = document.getElementById('selectMesConsultas').value;
    const anio = document.getElementById('selectAnioConsultas').value;
    consultarConsultasMes(mes, anio);
}
function consultarMesMayorEmergencias() {
    var datos = new FormData();
    datos.append('accion', 'mes_con_mas_emergencias');
    enviaAjax(datos, 'mes_con_mas_emergencias');
}

function consultarMesMayorConsultas() {
    var datos = new FormData();
    datos.append('accion', 'mes_con_mas_consultas');
    enviaAjax(datos, 'mes_con_mas_consultas');
}

function consultarMedicamentosMasUsados() {
    var datos = new FormData();
    datos.append('accion', 'medicamentosMasUsados');
    enviaAjax(datos, 'medicamentosMasUsados');
}

function consultarMedicamentosPorVencer() {
    var datos = new FormData();
    datos.append('accion', 'medicamentosPorVencer');
    enviaAjax(datos, 'medicamentosPorVencer');
}

$(document).ready(function(){
    // Establecer el mes y año actual por defecto
    const mesActual = new Date().getMonth() + 1;
    const anioActual = new Date().getFullYear();
    
    document.getElementById('selectMes').value = mesActual;
    document.getElementById('selectAnio').value = anioActual;
    
    // Configurar el evento del botón
    document.getElementById('btnActualizarGrafico').addEventListener('click', actualizarGrafico);
    document.getElementById('btnActualizarGraficoConsultas').addEventListener('click', actualizarGraficoConsultas);
    
    // Cargar datos iniciales
    consultar();
    consultarCronicos();
    actualizarGrafico(); // Usamos la nueva función que incluye mes y año
    actualizarGraficoConsultas(); // Usamos la nueva función que incluye mes y año
    consultarMesMayorEmergencias();
    consultarMesMayorConsultas();
    consultarMedicamentosMasUsados();
    consultarMedicamentosPorVencer();
    consultarTotalesGenerales();

    const carruselInner = document.getElementById('carrusel-inner');
    const cards = carruselInner.children;
    const cardsToShow = 2;
    let currentIndex = 0;
    const totalCards = cards.length;

   

    function updateCarousel() {
    carruselInner.style.transform = `translateX(-${currentIndex * (cards[0].offsetWidth + 16)}px)`;
    }

    // Botón siguiente
    document.getElementById('nextBtn').onclick = function() {
        currentIndex += 2;
        if (currentIndex > totalCards - cardsToShow) {
            currentIndex = 0; // Vuelve al primer slide
        }
        updateCarousel();
    };

    // Botón anterior
    document.getElementById('prevBtn').onclick = function() {
        currentIndex -= 2;
        if (currentIndex < 0) {
            currentIndex = totalCards - cardsToShow; // Vuelve al último slide visible
        }
        updateCarousel();
    };

    updateCarousel();




        document.querySelectorAll('.indicador-dot').forEach((dot, i) => {
            dot.addEventListener('click', function() {
                // Calcula el índice del primer card de ese slide
                currentIndex = i * cardsToShow;
                updateCarousel();
            });
        });

    function updateIndicadores() {
        const dots = document.querySelectorAll('.indicador-dot');
        // Calcula el índice del slide actual (de 0 a 2 si tienes 3 slides)
        let slideActual = Math.floor(currentIndex / cardsToShow);
        dots.forEach((dot, i) => {
            dot.classList.toggle('activo', i === slideActual);
        });
    }

    // Llama a updateIndicadores() cada vez que cambias de slide:
    function updateCarousel() {
        carruselInner.style.transform = `translateX(-${currentIndex * (cards[0].offsetWidth + 16)}px)`;
        updateIndicadores();
    }

    // Llama una vez al cargar:
    updateIndicadores();











});

document.getElementById('btnDescargarPDF').addEventListener('click', function(e) {
    // Emergencias
    const mesEmergencia = document.getElementById('selectMes').value;
    const anioEmergencia = document.getElementById('selectAnio').value;
    // Consultas
    const mesConsulta = document.getElementById('selectMesConsultas').value;
    const anioConsulta = document.getElementById('selectAnioConsultas').value;

    // Arma el enlace con ambos pares de datos
    this.href = `vista/fpdf/estadistica.php?mes_emergencia=${mesEmergencia}&anio_emergencia=${anioEmergencia}&mes_consulta=${mesConsulta}&anio_consulta=${anioConsulta}`;
    // El navegador abrirá el PDF en una nueva pestaña por el target="_blank"
});

function enviaAjax(datos, tipo) {
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta);

                if (tipo === "estadisticas") {
                    // Gráfico de distribución por edad
                    const ctxEdad = document.getElementById('graficoEdad').getContext('2d');
                    const graficoEdad = new Chart(ctxEdad, {
                        type: 'pie',
                        data: {
                            labels: ['Niños (0-12)', 'Adolescentes (13-17)', 'Adultos (18-64)', 'Adultos Mayores (65+)'],
                            datasets: [{
                                label: 'Distribución por Edad',
                                data: [lee.distribucionEdad.Ninos, lee.distribucionEdad.Adolescentes, 
                                       lee.distribucionEdad.Adultos, lee.distribucionEdad.AdultosMayores],
                                backgroundColor: ['#F8C8C8', '#E34234', '#DC143C', '#800020'],
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    });

                         // Calcular el rango con mayor cantidad y su color
                    const rangos = [
                        { nombre: 'Niños (0-12)', valor: lee.distribucionEdad.Ninos, color: '#F8C8C8' },
                        { nombre: 'Adolescentes (13-17)', valor: lee.distribucionEdad.Adolescentes, color: '#E34234' },
                        { nombre: 'Adultos (18-64)', valor: lee.distribucionEdad.Adultos, color: '#DC143C' },
                        { nombre: 'Adultos Mayores (65+)', valor: lee.distribucionEdad.AdultosMayores, color: '#800020' }
                    ];
                    const mayor = rangos.reduce((a, b) => a.valor > b.valor ? a : b);
                    
                    // Mostrarlo en el elemento de la página con el color
                    document.getElementById('rango_mayor').innerHTML =
                        `El rango con más registros es: 
                        <strong>${mayor.nombre} </strong>
                        <span style="display:inline-block;width:18px;height:18px;vertical-align:middle;background:${mayor.color};border-radius:4px;margin-right:6px;"></span>
                        con <strong>${mayor.valor}</strong> registros.`;

                 }

                if (tipo === "consultarCronicos") {
                    const datos = lee.datos;
                    const labels = datos.map(d => d.nombre_patologia);
                    const valores = datos.map(d => d.pacientes);
                
                    // Paleta de 10 colores fijos + 1 para "Otros"
                    const baseColors = [
                        '#F8C8C8', // Rosa antiguo (rojo pálido)
                        '#F08080', // Coral claro (rojo cálido)
                        '#FA8072', // Rojo salmón (cálido y terroso)
                        '#E34234', // Bermellón (vibrante anaranjado)
                        '#D22B2B', // Rojo cadmio (clásico intenso)
                        '#DC143C', // Carmesí (profundo azulado)
                        '#BB0A1E', // Rojo sangre (oscuro dramático)
                        '#800020', // Burdeos (vino elegante)
                        '#9E2B27', // Rojo óxido (terroso apagado)
                        '#5E1916'  // Rojo sombra (casi marrón)
                    ];
                    // Si hay "Otros", agrégale un color especial al final
                    let backgroundColors = baseColors.slice(0, 10);
                    if (labels.length > 10) {
                        backgroundColors.push('#9E9E9E'); // Gris para "Otros"
                    } else if (labels.length === 11) {
                        // Si justo hay 11 (10 + "Otros"), también agrega el color extra
                        backgroundColors.push('#9E9E9E');
                    } else {
                        backgroundColors = baseColors.slice(0, labels.length);
                    }
                
                    const ctx = document.getElementById('graficoCronicos').getContext('2d');
                    if (window.graficoCronicos && typeof window.graficoCronicos.destroy === "function") {
                        window.graficoCronicos.destroy();
                    }
                    window.graficoCronicos = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: valores,
                                backgroundColor: backgroundColors,
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    });
                
                    // Mostrar la patología con más pacientes (excluyendo "Otros")
                    if (datos.length > 0) {
                        // Filtra "Otros" si existe
                        const datosSinOtros = datos.filter(d => d.nombre_patologia !== 'Otros');
                        const mayor = datosSinOtros.reduce((a, b) => a.pacientes > b.pacientes ? a : b, datosSinOtros[0]);
                        document.getElementById('cronico_mayor').innerHTML =
                            `La patología con más pacientes es: <strong>${mayor.nombre_patologia}</strong> con <strong>${mayor.pacientes}</strong> registros.`;
                    }
            }
                if (tipo === 'emergencias_mes') {
                    const datos = lee.datos;
                    
                    // Extraer días y totales
                    const labels = datos.map(d => `Día ${d.dia}`);
                    const valores = datos.map(d => d.total);
                    
                    const ctx = document.getElementById('graficolinealemergencias').getContext('2d');
                    
                    // Destruir el gráfico anterior si existe
                    if (graficoEmergencias) {
                        graficoEmergencias.destroy();
                    }
                    
                    graficoEmergencias = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Emergencias por Día',
                                data: valores,
                                borderColor: '#DC143C', // Rojo carmesí (más vibrante)
                                backgroundColor: 'rgba(220, 20, 60, 0.15)', // 15% de opacidad para mejor legibilidad
                                fill: true,
                                tension: 0.3,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'top' },
                                title: {
                                    display: true,
                                    text: `Emergencias durante ${getNombreMes(lee.mes)} ${lee.anio}`
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
                if (tipo === 'consultas_mes') {
                    const datos = lee.datos;
                    const labels = datos.map(d => `Día ${d.dia}`);
                    const valores = datos.map(d => d.total);

                    // Destruir el gráfico anterior si existe
                    if (window.graficoConsultas) {
                        window.graficoConsultas.destroy();
                    }

                    const ctx = document.getElementById('graficolinealconsultas').getContext('2d');
                    window.graficoConsultas = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Consultas por Día',
                                data: valores,
                                backgroundColor: 'rgba(175, 2, 45, 0.5)',   // Burdeos (#800020) al 50% opacidad
                                borderColor: 'rgb(117, 15, 10)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'top' },
                                title: {
                                    display: true,
                                    text: `Consultas durante ${getNombreMes(lee.mes)} ${lee.anio}`
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
                if (tipo === 'totales_generales') {
                    document.getElementById('totalHistorias').textContent = lee.totales.total_historias;
                    document.getElementById('total_cronicos').textContent = lee.totales.total_cronicos;
                    document.getElementById('total_emergencias').textContent = lee.totales.total_emergencias;
                    document.getElementById('total_consultas').textContent = lee.totales.total_consultas;
                    document.getElementById('total_lotes').textContent = lee.totales.cantidad_lotes_con_existencia;
                    document.getElementById('total_medicamentos').textContent = lee.totales.total_de_medicamentos;
                }
                if (tipo === 'mes_con_mas_emergencias') {
                    if (lee.mes && lee.anio) {
                        document.getElementById('mes_mayor_emergencias').innerHTML =
                            `El mes con más emergencias históricamente fue <strong>${getNombreMes(lee.mes)} ${lee.anio}</strong> con <strong>${lee.total}</strong> emergencias.`;
                    } else {
                        document.getElementById('mes_mayor_emergencias').innerHTML =
                            "No hay datos de emergencias.";
                    }
                }
                if (tipo === 'mes_con_mas_consultas') {
                    if (lee.mes && lee.anio) {
                        document.getElementById('mes_mayor_consultas').innerHTML =
                            `El mes con más consultas históricamente fue <strong>${getNombreMes(lee.mes)} ${lee.anio}</strong> con <strong>${lee.total}</strong> consultas.`;
                    } else {
                        document.getElementById('mes_mayor_consultas').innerHTML =
                            "No hay datos de consultas.";
                    }
                }
                if (tipo === "medicamentosPorVencer") {
                    const datos = lee.datos;
                    // Etiqueta: nombre + fecha de vencimiento
                    const labels = datos.map(d => `Lote: ${d.cod_lote} -(${d.fecha_vencimiento})`);
                    const valores = datos.map(d => parseInt(d.cantidad));
                   const backgroundColors = [
                        '#F8C8C8', // Rosa antiguo (rojo pálido suave)
                        '#F08080', // Coral claro (rojo cálido luminoso)
                        '#FA8072', // Rojo salmón (cálido y terroso)
                        '#E34234', // Bermellón (intenso con toque naranja)
                        '#D22B2B', // Rojo cadmio (clásico y vibrante)
                        '#DC143C', // Carmesí (profundo con matiz azulado)
                        '#BB0A1E', // Rojo sangre (oscuro y dramático)
                        '#800020', // Burdeos (elegante rojo vino)
                        '#9E2B27', // Rojo óxido (terroso y apagado)
                        '#5E1916'  // Rojo sombra (casi marrón, muy oscuro)
                    ].slice(0, labels.length);

                    const ctx = document.getElementById('graficoMedicamentosPorVencer').getContext('2d');
                    if (window.graficoMedicamentosPorVencer && typeof window.graficoMedicamentosPorVencer.destroy === "function") {
                        window.graficoMedicamentosPorVencer.destroy();
                    }
                    window.graficoMedicamentosPorVencer = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Cantidad Disponible',
                                data: valores,
                                backgroundColor: backgroundColors,
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: {
                                    display: true,
                                    text: 'Medicamentos por vencer (más próximos)'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            }
                        }
                    });

                    if (datos.length > 0) {
                        const proximo = datos[0];
                        document.getElementById('lote_por_expirar').innerHTML =
                            `El medicamento más próximo a vencer es: <strong>${proximo.medicamento}</strong> 
                            (Lote: <strong>${proximo.cod_lote}</strong>) 
                            el <strong>${proximo.fecha_vencimiento}</strong> 
                            con <strong>${proximo.cantidad}</strong> unidades disponibles.`;
                    } else {
                        document.getElementById('lote_por_expirar').innerHTML =
                            "No hay lotes próximos a vencer.";
                    }
                }
                if (tipo === "medicamentosMasUsados") {
                    const datos = lee.datos;
                    const labels = datos.map(d => d.medicamento);
                    const valores = datos.map(d => parseInt(d.cantidad_total));
                    // Paleta de 10 colores fijos
                  const backgroundColors = [
                    '#F8C8C8', // Rosa antiguo (rojo pálido suave)
                    '#F08080', // Coral claro (rojo cálido luminoso)
                    '#FA8072', // Rojo salmón (cálido y terroso)
                    '#E34234', // Bermellón (intenso con toque naranja)
                    '#D22B2B', // Rojo cadmio (clásico y vibrante)
                    '#DC143C', // Carmesí (profundo con matiz azulado)
                    '#BB0A1E', // Rojo sangre (oscuro y dramático)
                    '#800020', // Burdeos (elegante rojo vino)
                    '#9E2B27', // Rojo óxido (terroso y apagado)
                    '#5E1916'  // Rojo sombra (casi marrón, muy oscuro)
                ];

                    const ctx = document.getElementById('graficoMedicamentos').getContext('2d');
                    if (window.graficoMedicamentos && typeof window.graficoMedicamentos.destroy === "function") {
                        window.graficoMedicamentos.destroy();
                    }
                    window.graficoMedicamentos = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Cantidad Total Sacada',
                                data: valores,
                                backgroundColor: backgroundColors.slice(0, labels.length), // Solo los necesarios
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: {
                                    display: true,
                                    text: 'Medicamentos más usados'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            }
                        }
                    });

                    if (datos.length > 0) {
                        const masUsado = datos[0];
                        document.getElementById('Medicamento_mas_usado').innerHTML =
                            `El medicamento más usado es: <strong>${masUsado.medicamento}</strong> con <strong>${masUsado.cantidad_total}</strong> unidades entregadas.`;
                    } else {
                        document.getElementById('Medicamento_mas_usado').innerHTML =
                            "No hay datos de medicamentos usados.";
                    }
                }
            } catch (e) {
                alert("Error en JSON " + e.name);
                console.error("Respuesta mal formada:", respuesta);
                console.error("Error al parsear JSON:", e);
            }
        },
        error: function(request, status, err) {
            alert("ERROR: " + request + status + err);
        }
    });
}
function getNombreMes(numeroMes) {
    const meses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    return meses[numeroMes - 1];
}