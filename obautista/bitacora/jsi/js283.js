// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Tarjetas CRC
// --- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
function paramsf283() {
	let params = new Array();
	params[0] = window.document.frmedita.aure51id.value;
	//params[1] = window.document.frmedita.p1_283.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf283.value;
	params[102] = window.document.frmedita.lppf283.value;
	//params[103] = window.document.frmedita.bnombre283.value;
	//params[104] = window.document.frmedita.blistar283.value;
	return params;
}
function guardaf283() {
	let valores = new Array();
	valores[1] = window.document.frmedita.aure51id.value;
	valores[2] = window.document.frmedita.aure83consec.value;
	valores[3] = window.document.frmedita.aure83id.value;
	valores[4] = window.document.frmedita.aure83idbithistoria.value;
	valores[5] = window.document.frmedita.aure83idtarea.value;
	valores[6] = window.document.frmedita.aure83vigente.value;
	valores[7] = window.document.frmedita.aure83nombreclase.value;
	valores[8] = window.document.frmedita.aure83responsabilidades.value;
	valores[9] = window.document.frmedita.aure83colaboradores.value;
	valores[10] = window.document.frmedita.aure83idtabla.value;
	params = paramsf283();
	xajax_f283_Guardar(valores, params);
}
function limpiaf283() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f283_PintarLlaves(params);
	window.document.frmedita.aure83idbithistoria.value = '';
	window.document.frmedita.aure83idtarea.value = '';
	window.document.frmedita.aure83vigente.value = 'S';
	window.document.frmedita.aure83nombreclase.value = '';
	window.document.frmedita.aure83responsabilidades.value = '';
	window.document.frmedita.aure83colaboradores.value = '';
	window.document.frmedita.aure83idtabla.value = 0;
	verboton('belimina283', 'none');
}
function eliminaf283() {
	if (window.document.frmedita.aure83id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Tarjetas CRC?', () => {
			params = paramsf283();
			params[1] = window.document.frmedita.aure51id.value;
			params[2] = window.document.frmedita.aure83consec.value;
			params[3] = window.document.frmedita.aure83id.value;
			//params[12] = window.document.frmedita.p1_283.value;
			xajax_f283_Eliminar(params);
		});
	}
}
function revisaf283() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.aure51id.value;
	params[2] = window.document.frmedita.aure83consec.value;
	params[3] = window.document.frmedita.aure83id.value;
	if ((params[2] != '')) {
		xajax_f283_Traer(params);
	}
}
function cargadatof283(llave1) {
	window.document.frmedita.aure83consec.value = String(llave1);
	revisaf283();
}
function cargaridf283(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f283_Traer(params);
	expandepanel(283, 'block', 0);
}
function paginarf283() {
	params = paramsf283();
	document.getElementById('div_f283detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf283" name="paginaf283" type="hidden" value="' + params[101] + '" /><input id="lppf283" name="lppf283" type="hidden" value="' + params[102] + '" />';
	xajax_f283_HtmlTabla(params);
}
function imprime283() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_283.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_283.value;
	window.document.frmlista.nombrearchivo.value = 'Tarjetas CRC';
	window.document.frmlista.submit();
}
