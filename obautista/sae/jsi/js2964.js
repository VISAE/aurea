// JavaScript Document
// --- © Omar Augusto Bautista Mora - UNAD - 2026 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// ---  Tablero de Gestión Semanal
// --- Modelo Versión 3.2.5 lunes, 28 de septiembre de 2026
function graficobarrasvf2964(aSistemas, aProgramadas, aEjecutadas, aPorceEjecut) {
  const ctx1 = document.getElementById("bar-1").getContext("2d");
  new Chart(ctx1, {
    type: "bar",
    data: {
      labels: aSistemas,
      datasets: [
        {
          label: "Programadas",
          data: aProgramadas,
          backgroundColor: "#0a2540",
          order: 2,
        },
        {
          label: "Ejecutadas",
          data: aEjecutadas,
          backgroundColor: "#28a745",
          order: 2,
        },
        {
          label: "% Ejecución",
          data: aPorceEjecut,
          type: "line",
          borderColor: "#ff7f00",
          backgroundColor: "#ff7f00",
          borderDash: [5, 5],
          yAxisID: "y1",
          order: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: {
        padding: {
          top: 25,
        },
      },
      plugins: {
        legend: {
          position: "bottom",
          align: "center",
          labels: {
            boxWidth: 15,
            padding: 20,
            font: {
              size: 12,
            },
          },
        },
        datalabels: {
          anchor: "end",
          align: "top",
          formatter: (value, context) =>
            context.dataset.type === "line" ? value + "%" : value,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          position: "left",
          grid: { drawBorder: false },
        },
        y1: {
          position: "right",
          min: 0,
          max: 100,
          grid: { drawOnChartArea: false },
        },
      },
    },
    plugins: [ChartDataLabels],
  });
}

function graficodonaf2964(iTotalProgrm, aTotales) {
	const ctx2 = document.getElementById("pie-1").getContext("2d");
	const textoCentrado = {
		id: "textoCentrado",
		beforeDraw: (chart) => {
			if (chart.config.type !== "doughnut") return;
			
			const width = chart.width;
			const height = chart.height;
			const ctx = chart.ctx;
			ctx.restore();

			const chartArea = chart.chartArea;
			const centerX = chartArea.left + (chartArea.width / 2);
			const centerY = chartArea.top + (chartArea.height / 2);

			ctx.textAlign = "center";
			ctx.fillStyle = "var(--sys-on-container)";

			const fontSizeNumber = Math.round(height / 8); 
			ctx.font = `bold ${fontSizeNumber}px sans-serif`;
			ctx.textBaseline = "bottom";
			ctx.fillText(iTotalProgrm, centerX, centerY - 4);

			const fontSizeLabel = Math.round(height / 18); 
			ctx.font = `${fontSizeLabel}px sans-serif`;
			ctx.textBaseline = "top";
			ctx.fillText("Total", centerX, centerY + 6);

			ctx.save();
		}
	};
	new Chart(ctx2, {
		type: "doughnut",
		data: {
			labels: ["Ejecutadas", "En proceso", "Reprogramadas", "No ejecutadas"],
			datasets: [{
				data: aTotales,
				backgroundColor: ["#28a745", "#ffc107", "#6f42c1", "#dc3545"],
				borderWidth: 0
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			radius: "75%", 
			cutout: "75%",
			plugins: {
				legend: {
					position: "bottom",
					labels: {
						boxWidth: 12,
						padding: 15
					}
				},
				datalabels: { display: false }
			}
		},
		plugins: [ChartDataLabels, textoCentrado]
	});
}

function graficobarrashf2964(aSistemas, aCumpleMeta, aColorSistema) {
	const ctx3 = document.getElementById("bar-2").getContext("2d");
	new Chart(ctx3, {
		type: "bar",
		data: {
			labels: aSistemas,
			datasets: [{
				data: aCumpleMeta,
				backgroundColor: aColorSistema,
				borderRadius: 4,
				barThickness: 14
			}]
		},
		options: {
			indexAxis: "y",
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: { display: false },
				datalabels: {
					anchor: "end",
					align: "right",
					formatter: (value) => value + "%"
				}
			},
			scales: {
				x: { 
					min: 0, 
					max: 100, 
					grid: { display: false },
					ticks: { font: { size: 11 } }
				},
				y: { 
					grid: { display: false },
					ticks: { font: { size: 12 } }
				}
			}
		},
		plugins: [ChartDataLabels]
	});
}
