function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos, 'estadisticas');
}

function consultarCronicos() {
    var datos = new FormData();
    datos.append('accion', 'consultar_cronicos');
    enviaAjax(datos, 'cronicos');
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

function consultarEmergenciasMes() {
    var datos = new FormData();
    datos.append('accion', 'emergencias_mes');
    enviaAjax(datos, 'emergencias_mes');
}

function consultarconsultasMes() {
    var datos = new FormData();
    datos.append('accion', 'consultas_mes');
    enviaAjax(datos, 'consultas_mes');
}

$(document).ready(function(){
    consultar();
    consultarCronicos();
	consultarTotalHistorias();
	consultarTotalCronicos();
    consultarTotalemergencias();
    consultarTotalconsultas();
    consultarEmergenciasMes();
    consultarconsultasMes();
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
                }

                if (tipo === "cronicos") {
                    // Gráfico de distribución por padecimiento crónico
					const ctxCronicos = document.getElementById('graficoCronicos').getContext('2d');
					const graficoCronicos = new Chart(ctxCronicos, {
						type: 'pie',
						data: {
							labels: ['Cardiopatía', 'Hipertensión', 'Endocrinometabólico', 'Asmático', 'Renal', 'Mental'],
							datasets: [{
								label: 'Distribución por Padecimiento Crónico',
								data: [
									lee.distribucionCronicos.Cardiopatia, 
									lee.distribucionCronicos.Hipertension, 
									lee.distribucionCronicos.Endocrinometabolico,
									lee.distribucionCronicos.Asmatico, 
									lee.distribucionCronicos.Renal, 
									lee.distribucionCronicos.Mental
								],
								backgroundColor: ['#36A2EB', '#FF6384', '#9966FF', '#FFCE56', '#4BC0C0', '#FF9F40'],
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
                }
                if (tipo === 'emergencias_mes') {
                    const datos = lee.datos;
                
                    // Extraer días y totales
                    const labels = datos.map(d => `Día ${d.dia}`);
                    const valores = datos.map(d => d.total);
                
                    const ctx = document.getElementById('graficolinealemergencias').getContext('2d');
                    const graficoLineaEmergencias = new Chart(ctx, {
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
                                    text: 'Emergencias durante el mes actual'
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
                
                    // Extraer días y totales
                    const labels = datos.map(d => `Día ${d.dia}`);
                    const valores = datos.map(d => d.total);
                
                    const ctx = document.getElementById('graficolinealconsultas').getContext('2d');
                    const graficoLineaEmergencias = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'consultas por Día',
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
                                    text: 'Emergencias durante el mes actual'
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