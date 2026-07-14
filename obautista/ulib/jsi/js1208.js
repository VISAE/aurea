// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Destinatario
// --- Modelo Versión 3.1.5b lunes, 23 de marzo de 2026
function paramsf1208() {
	let params = new Array();
	params[0] = window.document.frmedita.masi05id.value;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf1208.value;
	params[102] = window.document.frmedita.lppf1208.value;
	//params[103] = window.document.frmedita.bnombre1208.value;
	//params[104] = window.document.frmedita.blistar1208.value;
	return params;
}
function guardaf1208() {
	let valores = new Array();
	valores[1] = window.document.frmedita.masi05id.value;
	valores[2] = window.document.frmedita.masi08idtercero.value;
	valores[3] = window.document.frmedita.masi08idfecha.value;
	valores[4] = window.document.frmedita.masi08id.value;
	valores[5] = window.document.frmedita.masi08idpoblacion.value;
	valores[6] = window.document.frmedita.masi08fechaenvio.value;
	valores[7] = window.document.frmedita.masi08horaenvio.value;
	valores[8] = window.document.frmedita.masi08minenvio.value;
	valores[9] = window.document.frmedita.masi08idsmtp.value;
	valores[97] = window.document.frmedita.bloque.value;
	params = paramsf1208();
	xajax_f1208_Guardar(valores, params);
}
function limpiaf1208() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.masi05id.value;
	params[97] = window.document.frmedita.bloque.value;
	xajax_f1208_PintarLlaves(params);
	window.document.frmedita.masi08idpoblacion.value = '';
	window.document.frmedita.masi08idsmtp.value = 0;
	verboton('belimina1208', 'none');
}

function eliminaf1208() {
	if (window.document.frmedita.masi08id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Destinatario?', () => {
			params = paramsf1208();
			params[1] = window.document.frmedita.masi05id.value;
			params[2] = window.document.frmedita.masi08idtercero.value;
			params[3] = window.document.frmedita.masi08idfecha.value;
			params[4] = window.document.frmedita.masi08id.value;
			params[97] = window.document.frmedita.bloque.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f1208_Eliminar(params);
		});
	}
}

function revisaf1208() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.masi05id.value;
	params[2] = window.document.frmedita.masi08idtercero.value;
	params[3] = window.document.frmedita.masi08idfecha.value;
	params[4] = window.document.frmedita.masi08id.value;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '') && (params[3] != '')) {
		xajax_f1208_Traer(params);
	}
}

function cargadatof1208(llave1, llave2) {
	window.document.frmedita.masi08idtercero.value = String(llave1);
	window.document.frmedita.masi08idfecha.value = String(llave2);
	revisaf1208();
}

function cargaridf1208(llave1) {
	let params = new Array();
	params[0] = 2;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f1208_Traer(params);
	expandepanel(1208, 'block', 0);
}

function paginarf1208() {
	params = paramsf1208();
	document.getElementById('div_f1208detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1208" name="paginaf1208" type="hidden" value="' + params[101] + '" /><input id="lppf1208" name="lppf1208" type="hidden" value="' + params[102] + '" />';
	xajax_f1208_HtmlTabla(params);
}

function imprime1208() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_1208.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_1208.value;
	window.document.frmlista.nombrearchivo.value = 'Destinatario';
	window.document.frmlista.submit();
}

function retirarf1208(masi08id) {
	params = paramsf1208();
	params[1] = window.document.frmedita.masi05id.value;
	params[2] = 0;
	params[3] = 0;
	params[4] = masi08id;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	document.getElementById('div_f1208detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1208" name="paginaf1208" type="hidden" value="' + params[101] + '" /><input id="lppf1208" name="lppf1208" type="hidden" value="' + params[102] + '" />';
	xajax_f1208_Eliminar(params);
}