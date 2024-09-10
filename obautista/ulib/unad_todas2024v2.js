// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co - 2014 - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Variante 4 se agrega hora_asignar
// --- Jueves 23 de Junio de 2016 - Se agrega MensajeAlarmaV2.
// --- Lunes 12 de febrero de 2024 - Se traen letias funciones de la forma.
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

function ModalMensaje(sHTML) {
	ModalMensajeV2(sHTML);
}

// Funcion depreciada
function ModalConfirm(sHTML) {
	let objCuerpo = document.getElementById('ModalCuerpoCon');
	jQuery.noConflict();
	jQuery('#ModalConfirm').modal('show');
	// Cargo el mensaje
	objCuerpo.innerHTML = sHTML;
	// Focus
	setTimeout(function () {
		jQuery("#modal-btn-si").focus();
	}, 500);
}

function ModalConfirmV2(sHTML, callback) {
	ModalMensajeV2(sHTML, callback);
}

// Nueva función para el modal
function ModalMensajeV2(texto, callback = null, { tituloModal = '', botonAceptar = '', botonCancelar = '' } = {}) {
	const modalBody = document.getElementById('modal__body');
	const btnCancelar = document.getElementById('boton__cancelar');
	// Cargo el mensaje
	modalBody.innerHTML = texto;
	// Verificar si debo usar o no callback
	let estadoCancelar = 'none';
	if (typeof callback === 'function') {
		// Aca incruste el callback
		const btnAceptar = document.getElementById('boton__aceptar');
		btnAceptar.onclick = callback;
		// Mostrar el boton cancelar
		estadoCancelar = 'flex';
	} else {
		modal.onclick = () => {
			modal.style.display = 'none';
		};
	}
	// Definir el estado del boton cancelar
	btnCancelar.style.display = estadoCancelar;
	// Modificar el titulo del modal
	if (tituloModal) {
		const modalTitle = document.getElementById('modal__title');
		modalTitle.innerHTML = tituloModal;
	}
	// Cambiar texto del boton aceptar
	if (botonAceptar) {
		const btnAceptarTitulo = document.getElementById('boton__aceptar__title');
		btnAceptarTitulo.innerHTML = botonAceptar;
	}
	if (botonCancelar) {
		const btnCancelarTitulo = document.getElementById('boton__cancelar__title');
		btnCancelarTitulo.innerHTML = botonCancelar;
	}
	//
	abrirModal();
}

