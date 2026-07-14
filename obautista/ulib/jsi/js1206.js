// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Poblacion
// --- Modelo Versión 3.1.5b lunes, 23 de marzo de 2026

function carga_combo_masi06centro() {
	let params = new Array();
	params[0] = window.document.frmedita.masi06zona.value;
	document.getElementById('div_masi06centro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="masi06centro" name="masi06centro" type="hidden" value="" />';
	xajax_f1206_Combomasi06centro(params);
}

function carga_combo_masi06programa() {
	let params = new Array();
	params[0] = window.document.frmedita.masi06escuela.value;
	params[1] = window.document.frmedita.masi06nivelforma.value;
	document.getElementById('div_masi06programa').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="masi06programa" name="masi06programa" type="hidden" value="" />';
	xajax_f1206_Combomasi06programa(params);
}

function carga_combo_masi06curso() {
	let params = new Array();
	params[0] = window.document.frmedita.masi06idperiodo.value;
	params[1] = window.document.frmedita.masi06programa.value;
	params[2] = window.document.frmedita.masi06escuela.value;
	document.getElementById('div_masi06curso').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="masi06curso" name="masi06curso" type="hidden" value="" />';
	xajax_f1206_Combomasi06curso(params);
}
function paramsf1206() {
	let params = new Array();
	params[0] = window.document.frmedita.masi05id.value;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf1206.value;
	params[102] = window.document.frmedita.lppf1206.value;
	//params[103] = window.document.frmedita.bnombre1206.value;
	//params[104] = window.document.frmedita.blistar1206.value;
	return params;
}
function guardaf1206() {
	let valores = new Array();
	valores[1] = window.document.frmedita.masi05id.value;
	valores[2] = window.document.frmedita.masi06consec.value;
	valores[3] = window.document.frmedita.masi06id.value;
	valores[4] = window.document.frmedita.masi06zona.value;
	valores[5] = window.document.frmedita.masi06centro.value;
	valores[6] = window.document.frmedita.masi06escuela.value;
	valores[7] = window.document.frmedita.masi06nivelforma.value;
	valores[8] = window.document.frmedita.masi06programa.value;
	valores[9] = window.document.frmedita.masi06est_condicion.value;
	valores[10] = window.document.frmedita.masi06sexo.value;
	valores[11] = window.document.frmedita.masi06idperiodo.value;
	valores[12] = window.document.frmedita.masi06curso.value;
	valores[13] = window.document.frmedita.masi06docente.value;
	valores[14] = window.document.frmedita.masi06unidadfunc.value;
	valores[15] = window.document.frmedita.masi06agnogrado.value;
	valores[97] = window.document.frmedita.bloque.value;
	params = paramsf1206();
	xajax_f1206_Guardar(valores, params);
}
function limpiaf1206() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.masi05id.value;
	params[97] = window.document.frmedita.bloque.value;
	window.document.frmedita.masi06zona.value = 0;
	window.document.frmedita.masi06centro.value = 0;
	window.document.frmedita.masi06escuela.value = 0;
	window.document.frmedita.masi06nivelforma.value = 0;
	window.document.frmedita.masi06programa.value = 0;
	window.document.frmedita.masi06est_condicion.value = 0;
	window.document.frmedita.masi06sexo.value = 0;
	window.document.frmedita.masi06idperiodo.value = 0;
	window.document.frmedita.masi06curso.value = 0;
	window.document.frmedita.masi06docente.value = 0;
	window.document.frmedita.masi06unidadfunc.value = 0;
	window.document.frmedita.masi06agnogrado.value = 0;
	xajax_f1206_PintarLlaves(params);
	verboton('belimina1206', 'none');
}

function eliminaf1206() {
	if (window.document.frmedita.masi06id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Poblacion?', () => {
			params = paramsf1206();
			params[1] = window.document.frmedita.masi05id.value;
			params[2] = window.document.frmedita.masi06consec.value;
			params[3] = window.document.frmedita.masi06id.value;
			//params[14] = window.document.frmedita.p1_1206.value;
			params[97] = window.document.frmedita.bloque.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f1206_Eliminar(params);
		});
	}
}

function revisaf1206() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.masi05id.value;
	params[2] = window.document.frmedita.masi06consec.value;
	params[3] = window.document.frmedita.masi06id.value;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f1206_Traer(params);
	}
}

function cargadatof1206(llave1) {
	window.document.frmedita.masi06consec.value = String(llave1);
	revisaf1206();
}

function cargaridf1206(llave1) {
	let params = new Array();
	params[0] = 2;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f1206_Traer(params);
	expandepanel(1206, 'block', 0);
}

function paginarf1206() {
	params = paramsf1206();
	document.getElementById('div_f1206detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1206" name="paginaf1206" type="hidden" value="' + params[101] + '" /><input id="lppf1206" name="lppf1206" type="hidden" value="' + params[102] + '" />';
	xajax_f1206_HtmlTabla(params);
}

function imprime1206() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_1206.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_1206.value;
	window.document.frmlista.nombrearchivo.value = 'Poblacion';
	window.document.frmlista.submit();
}

function reversarf1206(masi06id) {
	ModalConfirmV2('&iquest;Est&aacute; seguro reversar el proceso?', () => {
		params = paramsf1206();
		params[1] = window.document.frmedita.masi05id.value;
		params[2] = '';
		params[3] = masi06id;
		params[97] = window.document.frmedita.bloque.value;
		params[99] = window.document.frmedita.debug.value;
		document.getElementById('div_f1206detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1206" name="paginaf1206" type="hidden" value="' + params[101] + '" /><input id="lppf1206" name="lppf1206" type="hidden" value="' + params[102] + '" />';
		xajax_f1206_Reversar(params);
	});
}