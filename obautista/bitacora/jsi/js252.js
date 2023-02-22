// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Participantes
// --- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
function paramsf252() {
	let params = new Array();
	params[0] = window.document.frmedita.aure51id.value;
	//params[1] = window.document.frmedita.p1_252.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf252.value;
	params[102] = window.document.frmedita.lppf252.value;
	//params[103] = window.document.frmedita.bnombre252.value;
	//params[104] = window.document.frmedita.blistar252.value;
	return params;
}
function guardaf252() {
	let valores = new Array();
	valores[1] = window.document.frmedita.aure51id.value;
	valores[2] = window.document.frmedita.aure52idtercero.value;
	valores[3] = window.document.frmedita.aure52id.value;
	valores[4] = window.document.frmedita.aure52activo.value;
	params = paramsf252();
	xajax_f252_Guardar(valores, params);
}
function limpiaf252() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f252_PintarLlaves(params);
	window.document.frmedita.aure52activo.value = 1;
	verboton('belimina252', 'none');
}
function eliminaf252() {
	if (window.document.frmedita.aure52id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Participantes?', () => {
			params = paramsf252();
			params[1] = window.document.frmedita.aure51id.value;
			params[2] = window.document.frmedita.aure52idtercero.value;
			params[3] = window.document.frmedita.aure52id.value;
			//params[6] = window.document.frmedita.p1_252.value;
			xajax_f252_Eliminar(params);
		});
	}
}
function revisaf252() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.aure51id.value;
	params[2] = window.document.frmedita.aure52idtercero.value;
	params[3] = window.document.frmedita.aure52id.value;
	if ((params[2] != '')) {
		xajax_f252_Traer(params);
	}
}
function cargadatof252(llave1) {
	window.document.frmedita.aure52idtercero.value = String(llave1);
	revisaf252();
}
function cargaridf252(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f252_Traer(params);
	expandepanel(252, 'block', 0);
}
function paginarf252() {
	params = paramsf252();
	document.getElementById('div_f252detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf252" name="paginaf252" type="hidden" value="' + params[101] + '" /><input id="lppf252" name="lppf252" type="hidden" value="' + params[102] + '" />';
	xajax_f252_HtmlTabla(params);
}
function imprime252() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_252.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_252.value;
	window.document.frmlista.nombrearchivo.value = 'Participantes';
	window.document.frmlista.submit();
}
