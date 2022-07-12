// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co - 2014 - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Variante 4 se agrega hora_asignar
// --- Jueves 23 de Junio de 2016 - Se agrega MensajeAlarmaV2.
function accion_enter(e, accion) {
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla == 13) {
		if (accion != '') { eval(accion); }
	}
}
function archivo_lnk(origen, id, div) {
	var odiv = document.getElementById(div);
	var sres = '&nbsp;';
	if (id != 0) {
		sres = '<a href="verarchivo.php?cont=' + origen + '&id=' + id + '" target="_blank" class="lnkresalte">Descargar</a>';
	}
	odiv.innerHTML = sres;
}
function cadena_reemplazar(sCadena, sMuestra, sReemplazo) {
	var sNueva = '';
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
function fecha_ajusta(obj, code) {
	var objfecha = document.getElementById(obj);
	var objd = document.getElementById(obj + '_dia');
	var objm = document.getElementById(obj + '_mes');
	var obja = document.getElementById(obj + '_agno');
	objfecha.value = objd.value + '/' + objm.value + '/' + obja.value;
	var bPasa = true;
	var iResultado = '00/00/0000';
	if (objd.value == '00') { bPasa = false; }
	if (objm.value == '00') { bPasa = false; }
	if (obja.value == '0000') { bPasa = false; }
	if (bPasa) { iResultado = objfecha.value }
	var iVrPrev = document.getElementById(obj + '_prev').value;
	if (iVrPrev != iResultado) {
		document.getElementById(obj + '_prev').value = iResultado;
		if (code != '') { eval(code); }
	}
}
function fecha_AjustaNum(obj, code) {
	var iValor = 0;
	var iAgno = 0;
	iAgno = document.getElementById(obj + '_agno').value;
	if (iAgno == '') { iAgno = 0; }
	if (iAgno > 0) {
		var iDia = document.getElementById(obj + '_dia').value;
		var iMes = document.getElementById(obj + '_mes').value;
		if (iMes > 0) {
			if (iDia > 0) {
				iValor = (iAgno * 10000) + (iMes * 100) + (iDia * 1);
			}
		}
	}
	var objBase = document.getElementById(obj);
	if (objBase.value != iValor) {
		objBase.value = iValor;
		if (code != '') { eval(code); }
	}
}
function fecha_asignar(obj, vr) {
	var objfecha = document.getElementById(obj);
	var objd = document.getElementById(obj + '_dia');
	var objm = document.getElementById(obj + '_mes');
	var obja = document.getElementById(obj + '_agno');
	var bEntra = true;
	if (objd == null) { bEntra = false; }
	if (bEntra) {
		if (vr.length == 10) { objfecha.value = vr; }
		var et = objfecha.value;
		objd.value = et.substr(0, 2);
		objm.value = et.substr(3, 2);
		obja.value = et.substr(6, 4);
	}
}
function fecha_AsignarNum(obj, vr) {
	var objfecha = document.getElementById(obj);
	var objd = document.getElementById(obj + '_dia');
	var objm = document.getElementById(obj + '_mes');
	var obja = document.getElementById(obj + '_agno');
	objfecha.value = vr;
	var et = objfecha.value;
	objd.value = et.substr(6, 2);
	objm.value = et.substr(4, 2);
	obja.value = et.substr(0, 4);
}
function formatea_moneda(sObj) {
	xajax_formatear_moneda(sObj.id, sObj.value);
}
function hora_ajusta(sObjHora) {
	var objHora = document.getElementById(sObjHora);
	var objHN = document.getElementById(sObjHora + '_Num');
	var objHC = document.getElementById(sObjHora + '_Ciclo');
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
	var hc = 'A';
	if (vr > 11) {
		vr = vr - 12;
		hc = 'P';
	}
	if (vr == 0) { vr = 12; }
	document.getElementById(obj + '_Num').value = vr;
	document.getElementById(obj + '_Ciclo').value = hc;
}
function MensajeAlarmaV2(sHTML, sClase) {
	var divAlarma = document.getElementById('div_alarma');
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
	var objte = window.document.frmedita.itipoerror;
	if (typeof objte === 'undefined') {
	} else {
		objte.value = sClase;
	}
}
function ModalMensaje(sHTML) {
	var objCuerpo = document.getElementById('ModalCuerpoAdv');
	jQuery.noConflict();
	jQuery$('#ModalAdvertencia').modal();
	// Cargo el mensaje
	objCuerpo.innerHTML = sHTML;
}
function ModalConfirm(sHTML) {
	var objCuerpo = document.getElementById('ModalCuerpoCon');
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
	var objCuerpo = document.getElementById('ModalCuerpoCon');
	var btnSi = document.getElementById('modal-btn-si');
	jQuery.noConflict();
	jQuery('#ModalConfirm').modal('show');
	btnSi.onclick = callback;
	// Cargo el mensaje
	objCuerpo.innerHTML = sHTML;
	// Focus
	setTimeout(function () {
		$("#modal-btn-si").focus();
	}, 500);
}
