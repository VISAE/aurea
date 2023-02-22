// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Tareas de ingenieria
// --- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023

	function carga_combo_aure81idtipotarea() {
		let params = new Array();
		params[0] = window.document.frmedita.aure81idbithistoria.value;
		document.getElementById('div_aure81idtipotarea').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="aure81idtipotarea" name="aure81idtipotarea" type="hidden" value="" />';
		xajax_f281_Comboaure81idtipotarea(params);
	}
function paramsf281() {
	let params = new Array();
	params[0] = window.document.frmedita.aure51id.value;
	//params[1] = window.document.frmedita.p1_281.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf281.value;
	params[102] = window.document.frmedita.lppf281.value;
	//params[103] = window.document.frmedita.bnombre281.value;
	//params[104] = window.document.frmedita.blistar281.value;
	return params;
}
function guardaf281() {
	let valores = new Array();
	valores[1] = window.document.frmedita.aure51id.value;
	valores[2] = window.document.frmedita.aure81consec.value;
	valores[3] = window.document.frmedita.aure81id.value;
	valores[4] = window.document.frmedita.aure81idbithistoria.value;
	valores[5] = window.document.frmedita.aure81idtipotarea.value;
	valores[6] = window.document.frmedita.aure81semanasest.value;
	valores[7] = window.document.frmedita.aure81diasest.value;
	valores[8] = window.document.frmedita.aure81fechainicio.value;
	valores[9] = window.document.frmedita.aure81avance.value;
	valores[10] = window.document.frmedita.aure81fechafinal.value;
	valores[11] = window.document.frmedita.aure81descripcion.value;
	params = paramsf281();
	xajax_f281_Guardar(valores, params);
}
function limpiaf281() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f281_PintarLlaves(params);
	window.document.frmedita.aure81idbithistoria.value = '';
	window.document.frmedita.aure81idtipotarea.value = '';
	window.document.frmedita.aure81semanasest.value = '';
	window.document.frmedita.aure81diasest.value = '';
	fecha_AsignarNum('aure81fechainicio', iFechaBaseNum);
	fecha_AsignarNum('aure81fechafinal', iFechaBaseNum);
	window.document.frmedita.aure81descripcion.value = '';
	verboton('belimina281', 'none');
}
function eliminaf281() {
	if (window.document.frmedita.aure81id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Tareas de ingenieria?', () => {
			params = paramsf281();
			params[1] = window.document.frmedita.aure51id.value;
			params[2] = window.document.frmedita.aure81consec.value;
			params[3] = window.document.frmedita.aure81id.value;
			//params[13] = window.document.frmedita.p1_281.value;
			xajax_f281_Eliminar(params);
		});
	}
}
function revisaf281() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.aure51id.value;
	params[2] = window.document.frmedita.aure81consec.value;
	params[3] = window.document.frmedita.aure81id.value;
	if ((params[2] != '')) {
		xajax_f281_Traer(params);
	}
}
function cargadatof281(llave1) {
	window.document.frmedita.aure81consec.value = String(llave1);
	revisaf281();
}
function cargaridf281(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f281_Traer(params);
	expandepanel(281, 'block', 0);
}
function paginarf281() {
	params = paramsf281();
	document.getElementById('div_f281detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf281" name="paginaf281" type="hidden" value="' + params[101] + '" /><input id="lppf281" name="lppf281" type="hidden" value="' + params[102] + '" />';
	xajax_f281_HtmlTabla(params);
}
function imprime281() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_281.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_281.value;
	window.document.frmlista.nombrearchivo.value = 'Tareas de ingenieria';
	window.document.frmlista.submit();
}
