// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co - 2014 - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Variante 4 se agrega hora_asignar
// --- Jueves 23 de Junio de 2016 - Se agrega MensajeAlarmaV2.
// --- Lunes 12 de febrero de 2024 - Se traen letias funciones de la forma.

const currentLang = document.getElementById('lang-config')?.dataset?.lang;

const i18n = {
	"es": {
		"downloadLink": "Descargar",
		"waitMessage": "Procesando datos, por favor espere..."
	},
	"en": {
		"downloadLink": "Download",
		"waitMessage": "Processing data, please wait..."
	}
};

function accion_enter(e, accion) {
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla == 13) {
		if (accion != '') { eval(accion); }
	}
}

function archivo_lnk(origen, id, div) {
	let odiv = document.getElementById(div);
	let sres = '&nbsp;';
	if (id != 0) {
		sres = '<a href="verarchivo.php?cont=' + origen + '&id=' + id + '" target="_blank" class="lnkresalte">Descargar</a>';
	}
	odiv.innerHTML = sres;
}

function cadena_reemplazar(sCadena, sMuestra, sReemplazo) {
	let sNueva = '';
	while (sCadena.indexOf(sMuestra) >= 0) {
		sNueva = sCadena.replace(sMuestra, sReemplazo);
		sCadena = sNueva;
	}
	return sCadena;
}

function cambia_color_over(celda) { celda.style.backgroundColor = "#A2F178" }

function cambia_color_out(celda) { celda.style.backgroundColor = "#ffffff" }

function combo_tono(objcombo) {
	sColor = '000000';
	if (objcombo.value != '') { sColor = '000000'; }
	objcombo.style.color = sColor;
}

function limpiapagina() {
	expandesector(98);
	window.document.frmedita.paso.value = -1;
	window.document.frmedita.submit();
}

function enviaguardar() {
	window.document.frmedita.iscroll.value = window.scrollY;
	expandesector(98);
	let dpaso = window.document.frmedita.paso;
	if (dpaso.value == 0) {
		dpaso.value = 10;
	} else {
		dpaso.value = 12;
	}
	window.document.frmedita.submit();
}

function cambiapagina() {
	expandesector(98);
	window.document.frmedita.submit();
}

function cambiapaginaV2() {
	expandesector(98);
	window.document.frmedita.paso.value = 1;
	window.document.frmedita.submit();
}

function expandepanel(codigo, estado, valor) {
	const piel = document.getElementById('ipiel').value;
	let objdiv = document.getElementById('div_p' + codigo);
	let objban = document.getElementById('boculta' + codigo);
	let otroestado = 'none';
	if (piel == 2) {
		let objbt = document.getElementById('btexpande' + codigo);
		let obji = document.getElementById('i_expande' + codigo);
		let titulo = 'Ocultar';
		if (estado == 'none') {
			otroestado = 'block';
			titulo = 'Mostrar';
			valor = 1;
		}
		objdiv.style.display = estado;
		objban.value = valor;
		objbt.title = titulo;
		objbt.content = '';
		objbt.setAttribute('onclick', 'expandepanel(' + codigo + ',\'' + otroestado + '\', 0)');
		obji.classList.toggle('rotate-180');
	} else {
		if (estado == 'none') {
			otroestado = 'block';
		}
		objdiv.style.display = estado;
		objban.value = valor;
		verboton('btrecoge' + codigo, estado);
		verboton('btexpande' + codigo, otroestado);
	}
}

function fecha_ajusta(obj, code) {
	let objfecha = document.getElementById(obj);
	let objd = document.getElementById(obj + '_dia');
	let objm = document.getElementById(obj + '_mes');
	let obja = document.getElementById(obj + '_agno');
	objfecha.value = objd.value + '/' + objm.value + '/' + obja.value;
	let bPasa = true;
	let iResultado = '00/00/0000';
	if (objd.value == '00') { bPasa = false; }
	if (objm.value == '00') { bPasa = false; }
	if (obja.value == '0000') { bPasa = false; }
	if (bPasa) { iResultado = objfecha.value }
	let iVrPrev = document.getElementById(obj + '_prev').value;
	if (iVrPrev != iResultado) {
		document.getElementById(obj + '_prev').value = iResultado;
		if (code != '') { eval(code); }
	}
}

function fecha_AjustaNum(obj, code) {
	let iValor = 0;
	let iAgno = 0;
	iAgno = document.getElementById(obj + '_agno').value;
	if (iAgno == '') { iAgno = 0; }
	if (iAgno > 0) {
		let iDia = document.getElementById(obj + '_dia').value;
		let iMes = document.getElementById(obj + '_mes').value;
		if (iMes > 0) {
			if (iDia > 0) {
				iValor = (iAgno * 10000) + (iMes * 100) + (iDia * 1);
			}
		}
	}
	let objBase = document.getElementById(obj);
	if (objBase.value != iValor) {
		objBase.value = iValor;
		if (code != '') { eval(code); }
	}
}

function fecha_asignar(obj, vr) {
	let objfecha = document.getElementById(obj);
	let objd = document.getElementById(obj + '_dia');
	let objm = document.getElementById(obj + '_mes');
	let obja = document.getElementById(obj + '_agno');
	let bEntra = true;
	if (objd == null) { bEntra = false; }
	if (bEntra) {
		if (vr.length == 10) { objfecha.value = vr; }
		let et = objfecha.value;
		objd.value = et.substr(0, 2);
		objm.value = et.substr(3, 2);
		obja.value = et.substr(6, 4);
	}
}

function fecha_AsignarNum(obj, vr) {
	let objfecha = document.getElementById(obj);
	let objd = document.getElementById(obj + '_dia');
	let objm = document.getElementById(obj + '_mes');
	let obja = document.getElementById(obj + '_agno');
	objfecha.value = vr;
	let et = objfecha.value;
	objd.value = et.substr(6, 2);
	objm.value = et.substr(4, 2);
	obja.value = et.substr(0, 4);
}

function formatea_moneda(sObj) {
	xajax_formatear_moneda(sObj.id, sObj.value);
}

function hora_ajusta(sObjHora) {
	let objHora = document.getElementById(sObjHora);
	let objHN = document.getElementById(sObjHora + '_Num');
	let objHC = document.getElementById(sObjHora + '_Ciclo');
	iHora = parseInt(objHN.value);
	if (iHora > 11) { iHora = 0; }
	if (objHC.value == 'P') {
		objHora.value = iHora + 12;
	} else {
		objHora.value = iHora;
	}
}

function hora_asignar(obj, vr) {
	document.getElementById(obj).value = vr;
	let hc = 'A';
	if (vr > 11) {
		vr = vr - 12;
		hc = 'P';
	}
	if (vr == 0) { vr = 12; }
	document.getElementById(obj + '_Num').value = vr;
	document.getElementById(obj + '_Ciclo').value = hc;
}

function htmlEspereV2() {
	let sMsgEspere = 'Procesando datos, por favor espere...';
	let sSpinner = '<div class="spinner"></div>';
	let sStyle = 'style="padding-top: 8px; padding-left: 5px;"';
	let sStyle2 = 'style="height: 45px; padding-top: 4px; padding-left: 5px;"';
	let sRes = '<div class="GrupoCamposAyuda" ' + sStyle2 + '><div class="MarquesinaMedia">' + sSpinner + '<div ' + sStyle + '>' + sMsgEspere + '</div></div></div>';
	return sRes;
}

function MensajeAlarmaV2(sHTML, sClase) {
	let divAlarma = document.getElementById('div_alarma');
	if (sHTML == '') {
		sClaseFinal = 'alarma_transparente';
	} else {
		sClaseFinal = 'alarma_roja';
		if (sClase == 'verde') { sClaseFinal = 'alarma_verde'; }
		if (sClase == 1) { sClaseFinal = 'alarma_verde'; }
		if (sClase == 'azul') { sClaseFinal = 'alarma_azul'; }
		if (sClase == 2) { sClaseFinal = 'alarma_azul'; }
	}
	if (sHTML.length > 1000) {
		sHTML = '<div class="divScroll200">' + sHTML + '</div>';
	}
	divAlarma.innerHTML = sHTML;
	divAlarma.className = sClaseFinal;
	let objte = window.document.frmedita.itipoerror;
	if (typeof objte === 'undefined') {
	} else {
		objte.value = sClase;
	}
}

function retornacontrol() {
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
}

function revfoco(objeto) {
	setTimeout(function () {
		objeto.focus();
	}, 10);
}

function verboton(idboton, estado) {
	let objbt = document.getElementById(idboton);
	objbt.style.display = estado;
}

// Nueva función para el modal
function ModalMensajeV2(bodyContent, callback = null, { modalType = 'modal--confirmation', tituloModal = '', iconoModal = '', bodyClass = '', footerContent = '', textoBotonAceptar = '', ariaLabelBotonAceptar = '', iconoBotonAceptar = '', colorBotonAceptar = '', textoBotonCancelar = '', ariaLabelBotonCancelar = '', iconoBotonCancelar = '' } = {}) {
	const modalBody = document.getElementById('modal_body');
	const btnCancelar = document.getElementById('boton_cancelar');
	const btnAceptar = document.getElementById('boton_aceptar');
	// Variables
	let estadoCancelar = 'none';
	// Mostrar el mensaje
	modalBody.innerHTML = bodyContent;
	// Verificar callback
	if (typeof callback === 'function') {
		btnAceptar.onclick = callback;
		estadoCancelar = 'flex';
	}
	// Definir clase del body
	if (bodyClass) {
		modalBody.className = 'modal__body ' + bodyClass;
	}
	// Definir tamaño del modal
	if (modalType) {
		const modal = document.getElementById('modal-type');
		modal.classList.add(modalType);
	}
	// Modificar el titulo del modal
	if (tituloModal) {
		const modalTitle = document.getElementById('modal_title');
		modalTitle.innerHTML = tituloModal;
	}
	// Modificar el icono del modal
	if (iconoModal) {
		const modalIcono = document.getElementById('modal_icon');
		modalIcono.className = iconoModal;
	}
	// Cambiar texto del boton aceptar
	if (textoBotonAceptar) {
		const btnAceptarTexto = document.getElementById('boton_aceptar_title');
		btnAceptarTexto.innerHTML = textoBotonAceptar;
	}
	if (colorBotonAceptar) {
		let colorBoton = '';
		switch (colorBotonAceptar) {
			case 'azul':
				colorBoton = 'btn btn--primary';
				break;
			case 'amarillo':
				colorBoton = 'btn btn--tertiary';
				break;
			case 'rojo':
				colorBoton = 'btn btn--red';
				break;
			case 'verde':
				colorBoton = 'btn btn--green';
				break;
		}
		btnAceptar.className = colorBoton;
	}
	// El footer lo puedo cambiar.
	if (!footerContent) {
		// Visible el boton cancelar
		btnCancelar.style.display = estadoCancelar;
		// Cambiar ariaLabel del boton aceptar
		if (ariaLabelBotonAceptar) {
			btnAceptar.ariaLabel = ariaLabelBotonAceptar;
		}
		// Cambiar icono del boton aceptar
		if (iconoBotonAceptar) {
			const btnAceptarIcono = document.getElementById('modal_aceptar_icon');
			btnAceptarIcono.className = iconoBotonAceptar;
		}
		// Cambiar texto del boton cancelar
		if (textoBotonCancelar) {
			const btnCancelarTexto = document.getElementById('boton_cancelar_title');
			btnCancelarTexto.innerHTML = textoBotonCancelar;
		}
		// Cambiar ariaLabel del boton cancelar
		if (ariaLabelBotonCancelar) {
			const btnCancelar = document.getElementById('boton_cancelar');
			btnCancelar.ariaLabel = ariaLabelBotonCancelar;
		}
		// Cambiar icono del boton cancelar
		if (iconoBotonCancelar) {
			const btnCancelarIcono = document.getElementById('modal_cancelar_icon');
			btnCancelarIcono.className = iconoBotonCancelar;
		}
	} else {
		// Cambiar el footer
		const modalFooter = document.getElementById('modal_footer');
		modalFooter.innerHTML = footerContent;
	}

	openModal();
}

async function imageToPdf({ nombrePdf = 'documento', encabezado = '', texto = '' } = {}) {
	const pdf = new jspdf.jsPDF("p", "mm", "a4");
	const pageWidth = pdf.internal.pageSize.getWidth(); // Ancho de la página
	const imgWidth = 200; // Ancho de la imagen en el PDF
	const maxTextWidth = 190;
	const marginRight = 5;
	const pageHeight = 297; // Altura de una página A4 en mm
	const gap = 5;
	const lineHeight = 6;
	let currentPosition = 10; // Posición inicial en la primera página (margen superior)
	pdf.setFontSize(14);
	pdf.setTextColor(40);
	pdf.setFont('helvetica');
	if (encabezado) {
		const textWidth = pdf.getTextWidth(encabezado); // Ancho del texto
		const x = (pageWidth - textWidth) / 2; // Centrado horizontal
		pdf.text(encabezado, x, currentPosition);
		currentPosition += lineHeight;
	}
	if (texto) {
		// Agregar texto
		const lines = pdf.splitTextToSize(texto, maxTextWidth);
		lines.forEach(line => {
			const textWidth = pdf.getTextWidth(line); // Ancho del texto
			const x = (pageWidth - textWidth) / 2; // Centrado horizontal
			pdf.text(line, x, currentPosition);
			currentPosition += lineHeight;
		});
	}
	// Fecha impresion
	const fechaImpresion = 'Fecha de impresión ' + getCurrentDate();
	pdf.setFontSize(8);
	const textWidth = pdf.getTextWidth(fechaImpresion); // Ancho del texto
	const x = (pageWidth - textWidth) / 2; // Centrado horizontal
	pdf.text(fechaImpresion, x, currentPosition);
	currentPosition += lineHeight;
	//
	const divs = document.querySelectorAll('.pdf-print');
	for (const div of divs) {
		const canvas = await html2canvas(div);
		const canvasWidth = canvas.width; // Ancho del canvas original
		const canvasHeight = canvas.height; // Altura del canvas original
		const pageCanvasHeight = (canvasWidth * pageHeight) / imgWidth; // Altura en el canvas correspondiente a una página
		let positionImg = 0;
		while (positionImg < canvasHeight) {
			// Calcular el espacio restante en la página actual
			const remainingPageHeight = pageHeight - currentPosition;
			// Crear un canvas temporal para dividir la imagen en secciones manejables
			const tempCanvas = document.createElement("canvas");
			tempCanvas.width = canvasWidth;
			tempCanvas.height = Math.min(
				pageCanvasHeight,
				canvasHeight - positionImg, // Resto del contenido del canvas original
				(remainingPageHeight * canvasWidth) / imgWidth // Espacio restante en la página
			);
			// Copiar la parte visible del canvas original
			const tempCtx = tempCanvas.getContext("2d");
			tempCtx.drawImage(
				canvas,
				0,
				positionImg,
				canvasWidth,
				tempCanvas.height,
				0,
				0,
				canvasWidth,
				tempCanvas.height
			);
			const pageImgData = tempCanvas.toDataURL("image/png");
			let tempImgHeight = (tempCanvas.height * imgWidth) / canvasWidth;
			let variation = 0;
			// Ajuste de margen inferior
			positionImg += tempCanvas.height;
			if (positionImg < canvasHeight) {
				variation = 5;
			}
			// Agregar la imagen al PDF en la posición actual
			pdf.addImage(pageImgData, "PNG", marginRight, currentPosition, imgWidth, (tempImgHeight - variation));
			// Actualizar la posición actual
			currentPosition += tempImgHeight + gap;
			// Si el contenido no cabe en la página actual, agregar una nueva página
			if (currentPosition >= pageHeight) {
				pdf.addPage();
				currentPosition = 5; // Reiniciar la posición para la nueva página
			}
		}
	}
	// Descargar el PDF generado
	pdf.save(nombrePdf + ".pdf");
}

function getCurrentDate() {
	// Fecha impresion
	const fechaActual = new Date();
	const diasSemana = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
	const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
	const diaSemana = diasSemana[fechaActual.getDay()];
	const dia = fechaActual.getDate();
	const mes = meses[fechaActual.getMonth()];
	const año = fechaActual.getFullYear();
	let horas = fechaActual.getHours();
	const minutos = fechaActual.getMinutes().toString().padStart(2, "0");
	const ampm = horas >= 12 ? "PM" : "AM";
	horas = horas % 12 || 12; // Convertir a formato 12 horas
	const fechaImpresion = `${diaSemana} ${dia} de ${mes} de ${año} ${horas}:${minutos} ${ampm}`;
	return fechaImpresion;
}

function copiarPortapapeles(button) {
	if (navigator.clipboard) {
		const contenidoCodigo = button.textContent || button.innerText;
		navigator.clipboard.writeText(contenidoCodigo).catch(function (err) {
			console.error('Error al copiar al portapapeles', err);
		});
	} else {
		const seleccion = window.getSelection();
		const rango = document.createRange();
		rango.selectNodeContents(button);
		seleccion.removeAllRanges();
		seleccion.addRange(rango);
		// Copia el contenido al portapapeles
		document.execCommand('copy');
		// Limpia la selección
		seleccion.removeAllRanges();
	}
}