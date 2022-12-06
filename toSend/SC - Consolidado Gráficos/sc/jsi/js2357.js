// JavaScript Document
// --- © Omar Augusto Bautista Mora - UNAD - 2022 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// ---  Scripts para la librería gráfica chartjs
// --- Modelo Versión 1.0 lunes, 28 de noviiembre de 2022
function crearGraficof2357(sGrafico, aEtiquetas, sTipo, aGrupoDatos) {
  var ctx = document.getElementById(sGrafico).getContext("2d");
  Chart.pluginService.register({
    beforeDraw: ({ config: { options }, ctx }) => {
      if (options.chartArea?.backgroundColor) {
        ctx.save();
        ctx.fillStyle = options.chartArea.backgroundColor;
        ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        ctx.restore();
      }
    },
  });
  var oGrafico = new Chart(ctx, {
    type: sTipo,
    plugins: [ChartDataLabels],
    data: {
      labels: aEtiquetas,
      datasets: aGrupoDatos,
    },
    options: {
      chartArea: {
        backgroundColor: "rgba(255, 255, 255, 1)",
      },
      scales: {
        xAxes: [{ stacked: true }],
        yAxes: [
          {
            stacked: true,
            ticks: {
              beginAtZero: true,
            },
          },
        ],
      },
      plugins: {
        datalabels: {
          anchor: "end",
          align: "end",
          rotation: function () {
            return sTipo == "bar" ? -90 : 0;
          },
          offset: 2,
        },
      },
      layout: {
        padding: {
          right: function () {
            return sTipo == "horizontalBar" ? 15 : 0;
          },
        },
      },
    },
  });
}
function grupoDatosGraficosf2357( aTitulos, aDatos, aGrupoApilado, aColorFondo, aColorLinea) {
  var i;
  var aObjetos = new Array();
  for (i = 0; i < aTitulos.length; i++) {
    aColorFondo[i] = aColorFondo[i]==undefined?'#3598bc':aColorFondo[i];
    aColorLinea[i] = aColorLinea[i]==undefined?'#a8def8':aColorLinea[i];
    var objeto = {
      label: aTitulos[i],
      stack: "Stack " + aGrupoApilado[i],
      data: aDatos.map((a) => a["grupo" + i]),
      backgroundColor: aColorFondo[i],
      hoverBackgroundColor: aColorFondo[i],
      borderColor: aColorLinea[i],
      borderWidth: 1,
      datalabels: {
        color: "white",
        font: {
          weight: "bold",
        },
        backgroundColor: aColorFondo[i],
        borderColor: aColorLinea[i],
      },
    };
    aObjetos.push(objeto);
  }
  return aObjetos;
}
function pintarGraficosf2357(oJSON) {
  for (var sGrafico in oJSON) {
    document.getElementById("div_f2357detalle_" + sGrafico).innerHTML =
      oJSON[sGrafico].sHTML;
    var aTitulos = oJSON[sGrafico].aConfiguracion.aTitulos;
    var aEtiquetas = oJSON[sGrafico].aEtiquetas;
    if (aEtiquetas.length > 8) {
      aEtiquetas = aEtiquetas.slice(0, 8);
    }
    aEtiquetas = aEtiquetas.map((el, idx) => {
      return divideCadenaf2357(el, 22);
    });
    var aDatos = oJSON[sGrafico].aDatos;
    if (aDatos.length > 8) {
      aDatos = aDatos.slice(0, 8);
    }
    var sTipo = oJSON[sGrafico].aConfiguracion.sTipo;
    var aColorFondo = oJSON[sGrafico].aConfiguracion.colorFondo;
    var aColorLinea = oJSON[sGrafico].aConfiguracion.colorLinea;
    var aGrupoApilado = oJSON[sGrafico].aConfiguracion.grupoApilado;
    var aGrupoDatos = grupoDatosGraficosf2357( aTitulos, aDatos, aGrupoApilado, aColorFondo, aColorLinea);
    crearGraficof2357(sGrafico, aEtiquetas, sTipo, aGrupoDatos);
  }
}
function divideCadenaf2357(sCadena, iAncho) {
  const aSecciones = [];
  const aPalabras = sCadena.split(" ");
  let sCadenaFinal = "";

  for (const sPalabra of aPalabras) {
    if (sPalabra.length > iAncho) {
      aSecciones.push(sCadenaFinal.trim());
      sCadenaFinal = "";
      aSecciones.push(sPalabra.trim());
      continue;
    }

    let sTemp = `${sCadenaFinal} ${sPalabra}`;
    if (sTemp.length > iAncho) {
      aSecciones.push(sCadenaFinal.trim());
      sCadenaFinal = sPalabra;
      continue;
    }

    sCadenaFinal = sTemp;
  }
  aSecciones.push(sCadenaFinal.trim());

  return aSecciones;
}
