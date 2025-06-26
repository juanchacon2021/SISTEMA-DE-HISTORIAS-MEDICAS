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

function consultarTotalHistorias() {
    var datos = new FormData();
    datos.append('accion', 'total_historias');
    enviaAjax(datos, 'total');
}

function consultarTotalCronicos() {
    var datos = new FormData();
    datos.append('accion', 'total_cronicos');
    enviaAjax(datos, 'total_p');
}

let graficoEmergencias = null;

function consultarTotalemergencias() {
    var datos = new FormData();
    datos.append('accion', 'total_emergencias');
    enviaAjax(datos, 'emergencias');
}

function consultarTotalconsultas() {
    var datos = new FormData();
    datos.append('accion', 'total_consultas');
    enviaAjax(datos, 'consultas');
}

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
    consultarTotalHistorias();
    consultarTotalCronicos();
    consultarTotalemergencias();
    consultarTotalconsultas();
    actualizarGrafico(); // Usamos la nueva función que incluye mes y año
    actualizarGraficoConsultas(); // Usamos la nueva función que incluye mes y año
    consultarMesMayorEmergencias();
    consultarMesMayorConsultas();
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
                                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0'],
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
                        { nombre: 'Niños (0-12)', valor: lee.distribucionEdad.Ninos, color: '#36A2EB' },
                        { nombre: 'Adolescentes (13-17)', valor: lee.distribucionEdad.Adolescentes, color: '#FF6384' },
                        { nombre: 'Adultos (18-64)', valor: lee.distribucionEdad.Adultos, color: '#FFCE56' },
                        { nombre: 'Adultos Mayores (65+)', valor: lee.distribucionEdad.AdultosMayores, color: '#4BC0C0' }
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
                    const backgroundColors = labels.map(() => '#' + Math.floor(Math.random()*16777215).toString(16));

                    const ctx = document.getElementById('graficoCronicos').getContext('2d');
                    // Solución segura para destruir el gráfico anterior si existe
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

                    // Mostrar la patología con más pacientes
                    if (datos.length > 0) {
                        const mayor = datos.reduce((a, b) => a.pacientes > b.pacientes ? a : b);
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
                                borderColor: '#FF6384',
                                backgroundColor: 'rgba(255,99,132,0.2)',
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
                                backgroundColor: 'rgba(54, 162, 235, 0.6)', // Azul
                                borderColor: 'rgba(54, 162, 235, 1)',       // Azul más fuerte
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
				if (tipo === 'total') {
					document.getElementById('totalHistorias').textContent = lee.total;
				}
				if (tipo === 'total_p') {
					document.getElementById('total_cronicos').textContent = lee.total;
				}
                if (tipo === 'emergencias') {
					document.getElementById('total_emergencias').textContent = lee.total;
				}
                if (tipo === 'consultas') {
					document.getElementById('total_consultas').textContent = lee.total;
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