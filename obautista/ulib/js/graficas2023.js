Chart.defaults.set('plugins.datalabels', {
    backgroundColor: "black",
    color: 'white',
    borderRadius: 4,
    anchor: "center",
    padding: {
        top: 2,
        right: 4,
        bottom: 2,
        left: 4
    }
});

const Colors = [
    "#ff6d00", "#2962ff", "#ffd600", "#00b8d4", "#00c853", "#aa00ff", "#d50000", "#00bfa5", "#aeea00",
    "#ffea00", "#2979ff", "#ff9100", "#00e5ff", "#00e676", "#d500f9", "#ff1744", "#1de9b6", "#c6ff00",
    "#ffff00", "#448aff", "#ffab40", "#18ffff", "#69f0ae", "#e040fb", "#ff5252", "#64ffda", "#eeff41",
    "#ffff8d", "#82b1ff", "#ffd180", "#84ffff", "#b9f6ca", "#ea80fc", "#ff8a80", "#a7ffeb", "#f4ff81"
]

var chartPie = null;
var chartBar = null;
var lenghtChartBar = null;

var durationAnimation = null;
var delayAnimation = null;
var displayLegends = null;


function makeChartPie(sNombre, sCanvas, sTituloGrafica, sEtiquetas, sTituloValores, sValores){
    const colorSurface = getComputedStyle(document.querySelector("body")).getPropertyValue("--sys-surface");
    const colorOnSurface = getComputedStyle(document.querySelector("body")).getPropertyValue("--sys-on-surface");
    const colorOutline = getComputedStyle(document.querySelector("body")).getPropertyValue("--sys-outline");
    Chart.defaults.font.size = 16;
    Chart.defaults.color = colorOnSurface;
    Chart.defaults.borderColor = colorOutline;

    if(chartPie){
        //chartPie.destroy();
    }

    sNombre = new Chart(sCanvas, {
        type: 'pie',
        data: {
            labels: sEtiquetas,
            datasets: [{
                label: sTituloValores,
                data: sValores,
                backgroundColor: Colors,
                borderWidth: 3,
                borderColor: colorSurface,
                hoverOffset: 4
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: sTituloGrafica,
                },
                datalabels: {
                    formatter: function(value, context) {
                        const datasetIndex = context.datasetIndex;
                        // Accede a la información del dataset actual
                        const dataset = context.chart.data.datasets[datasetIndex];
                        // Retorna el "label" del dataset como la etiqueta
                        return `${dataset.data}%`;
                    }
                }
            },
            maintainAspectRatio: false,
            animation: {
                duration: durationAnimation,
                delay: delayAnimation
            }
        }
    });
}

function makeChartBar(sNombre, sCanvas, sTituloGrafica, sEtiquetas, aValores1, aValores2){
    const colorSurface = getComputedStyle(document.querySelector("body")).getPropertyValue("--sys-surface");
    const colorOnSurface = getComputedStyle(document.querySelector("body")).getPropertyValue("--sys-on-surface");
    const colorOutline = getComputedStyle(document.querySelector("body")).getPropertyValue("--sys-outline");
    Chart.defaults.font.size = 16;
    Chart.defaults.color = colorOnSurface;
    Chart.defaults.borderColor = colorOutline;

    // if(sNombre){
    //     sNombre.destroy();
    // }

    sNombre = new Chart(sCanvas, {
        type: 'bar',
        data: {
            labels: sEtiquetas,
            datasets: [
                {
                    // label: sTituloValor,
                    // data: sValor,
                    label: "Ejecución",
                    data: aValores2,
                    backgroundColor: Colors[0],
                    borderRadius: 4,
                    minBarThickness: 16,
                    maxBarThickness: 16,
                    categoryPercentage: .99
                },
                {
                    label: "Meta",
                    data: aValores1,
                    backgroundColor: Colors[1],
                    borderRadius: 4,
                    minBarThickness: 16,
                    maxBarThickness: 16,
                    categoryPercentage: .99 
                }
            ]
        },
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: sTituloGrafica,
                },
                legend: {
                    display: displayLegends,
                    position: "bottom",
                },
            },
            scales: {
                x: {
                    beginAtZero: true                    
                },
            },
            animation: {
                duration: durationAnimation,
                delay: delayAnimation
            }
        }
    });
}


