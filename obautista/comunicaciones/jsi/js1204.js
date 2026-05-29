// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Listas - participantes
// --- Modelo Versión 3.0.16 viernes, 11 de julio de 2025
function paramsf1204() {
	let params = new Array();
	params[0] = window.document.frmedita.masi03id.value;
	//params[1] = window.document.frmedita.p1_1204.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf1204.value;
	params[102] = window.document.frmedita.lppf1204.value;
	//params[103] = window.document.frmedita.bnombre1204.value;
	//params[104] = window.document.frmedita.blistar1204.value;
	return params;
}
function guardaf1204() {
	let valores = new Array();
	valores[1] = window.document.frmedita.masi03id.value;
	valores[2] = window.document.frmedita.masi04idtercero.value;
	valores[3] = window.document.frmedita.masi04id.value;
	valores[4] = window.document.frmedita.masi04fechareg.value;
	valores[5] = window.document.frmedita.masi04fecharet.value;
	valores[6] = window.document.frmedita.masi04envio_generales.value;
	params = paramsf1204();
	xajax_f1204_Guardar(valores, params);
}
function limpiaf1204() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.masi03id.value;
	xajax_f1204_PintarLlaves(params);
	fecha_AsignarNum('masi04fechareg', iFechaBaseNum);
	fecha_AsignarNum('masi04fecharet', iFechaBaseNum);
	window.document.frmedita.masi04envio_generales.value = 1;
	verboton('belimina1204', 'none');
}

function eliminaf1204() {
	if (window.document.frmedita.masi04id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Listas - participantes?', () => {
			params = paramsf1204();
			params[1] = window.document.frmedita.masi03id.value;
			params[2] = window.document.frmedita.masi04idtercero.value;
			params[3] = window.document.frmedita.masi04id.value;
			//params[8] = window.document.frmedita.p1_1204.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f1204_Eliminar(params);
		});
	}
}

function revisaf1204() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.masi03id.value;
	params[2] = window.document.frmedita.masi04idtercero.value;
	params[3] = window.document.frmedita.masi04id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f1204_Traer(params);
	}
}

function cargadatof1204(llave1) {
	window.document.frmedita.masi04idtercero.value = String(llave1);
	revisaf1204();
}

function cargaridf1204(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f1204_Traer(params);
	expandepanel(1204, 'block', 0);
}

function paginarf1204() {
	params = paramsf1204();
	document.getElementById('div_f1204detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1204" name="paginaf1204" type="hidden" value="' + params[101] + '" /><input id="lppf1204" name="lppf1204" type="hidden" value="' + params[102] + '" />';
	xajax_f1204_HtmlTabla(params);
}

function imprime1204() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_1204.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_1204.value;
	window.document.frmlista.nombrearchivo.value = 'Listas - participantes';
	window.document.frmlista.submit();
}

