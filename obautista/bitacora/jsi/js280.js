// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Historia de usuario
// --- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
function paramsf280() {
	let params = new Array();
	params[0] = window.document.frmedita.aure51id.value;
	//params[1] = window.document.frmedita.p1_280.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf280.value;
	params[102] = window.document.frmedita.lppf280.value;
	//params[103] = window.document.frmedita.bnombre280.value;
	//params[104] = window.document.frmedita.blistar280.value;
	return params;
}
function guardaf280() {
	let valores = new Array();
	valores[1] = window.document.frmedita.aure51id.value;
	valores[2] = window.document.frmedita.aure80consec.value;
	valores[3] = window.document.frmedita.aure80id.value;
	valores[4] = window.document.frmedita.aure80momento.value;
	valores[5] = window.document.frmedita.aure80infousuario.value;
	valores[6] = window.document.frmedita.aure80prioridad.value;
	valores[7] = window.document.frmedita.aure80semanaest.value;
	valores[8] = window.document.frmedita.aure80diasest.value;
	valores[9] = window.document.frmedita.aure80iteracionasig.value;
	valores[10] = window.document.frmedita.aure80infotecnica.value;
	valores[11] = window.document.frmedita.aure80observaciones.value;
	params = paramsf280();
	xajax_f280_Guardar(valores, params);
}
function limpiaf280() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f280_PintarLlaves(params);
	window.document.frmedita.aure80momento.value = '';
	window.document.frmedita.aure80infousuario.value = '';
	window.document.frmedita.aure80prioridad.value = 0;
	window.document.frmedita.aure80semanaest.value = '';
	window.document.frmedita.aure80diasest.value = '';
	window.document.frmedita.aure80iteracionasig.value = '';
	window.document.frmedita.aure80infotecnica.value = '';
	window.document.frmedita.aure80observaciones.value = '';
	verboton('belimina280', 'none');
}
function eliminaf280() {
	if (window.document.frmedita.aure80id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Historia de usuario?', () => {
			params = paramsf280();
			params[1] = window.document.frmedita.aure51id.value;
			params[2] = window.document.frmedita.aure80consec.value;
			params[3] = window.document.frmedita.aure80id.value;
			//params[13] = window.document.frmedita.p1_280.value;
			xajax_f280_Eliminar(params);
		});
	}
}
function revisaf280() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.aure51id.value;
	params[2] = window.document.frmedita.aure80consec.value;
	params[3] = window.document.frmedita.aure80id.value;
	if ((params[2] != '')) {
		xajax_f280_Traer(params);
	}
}
function cargadatof280(llave1) {
	window.document.frmedita.aure80consec.value = String(llave1);
	revisaf280();
}
function cargaridf280(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f280_Traer(params);
	expandepanel(280, 'block', 0);
}
function paginarf280() {
	params = paramsf280();
	document.getElementById('div_f280detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf280" name="paginaf280" type="hidden" value="' + params[101] + '" /><input id="lppf280" name="lppf280" type="hidden" value="' + params[102] + '" />';
	xajax_f280_HtmlTabla(params);
}
function imprime280() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_280.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_280.value;
	window.document.frmlista.nombrearchivo.value = 'Historia de usuario';
	window.document.frmlista.submit();
}
