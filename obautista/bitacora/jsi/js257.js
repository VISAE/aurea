// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Riesgos
// --- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
function paramsf257() {
	let params = new Array();
	params[0] = window.document.frmedita.aure51id.value;
	//params[1] = window.document.frmedita.p1_257.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf257.value;
	params[102] = window.document.frmedita.lppf257.value;
	//params[103] = window.document.frmedita.bnombre257.value;
	//params[104] = window.document.frmedita.blistar257.value;
	return params;
}
function guardaf257() {
	let valores = new Array();
	valores[1] = window.document.frmedita.aure51id.value;
	valores[2] = window.document.frmedita.aure57idriesgo.value;
	valores[3] = window.document.frmedita.aure57id.value;
	valores[4] = window.document.frmedita.aure57nivelriesgo.value;
	params = paramsf257();
	xajax_f257_Guardar(valores, params);
}
function limpiaf257() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f257_PintarLlaves(params);
	window.document.frmedita.aure57nivelriesgo.value = 0;
	verboton('belimina257', 'none');
}
function eliminaf257() {
	if (window.document.frmedita.aure57id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Riesgos?', () => {
			params = paramsf257();
			params[1] = window.document.frmedita.aure51id.value;
			params[2] = window.document.frmedita.aure57idriesgo.value;
			params[3] = window.document.frmedita.aure57id.value;
			//params[6] = window.document.frmedita.p1_257.value;
			xajax_f257_Eliminar(params);
		});
	}
}
function revisaf257() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.aure51id.value;
	params[2] = window.document.frmedita.aure57idriesgo.value;
	params[3] = window.document.frmedita.aure57id.value;
	if ((params[2] != '')) {
		xajax_f257_Traer(params);
	}
}
function cargadatof257(llave1) {
	window.document.frmedita.aure57idriesgo.value = String(llave1);
	revisaf257();
}
function cargaridf257(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f257_Traer(params);
	expandepanel(257, 'block', 0);
}
function paginarf257() {
	params = paramsf257();
	document.getElementById('div_f257detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf257" name="paginaf257" type="hidden" value="' + params[101] + '" /><input id="lppf257" name="lppf257" type="hidden" value="' + params[102] + '" />';
	xajax_f257_HtmlTabla(params);
}
function imprime257() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_257.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_257.value;
	window.document.frmlista.nombrearchivo.value = 'Riesgos';
	window.document.frmlista.submit();
}
