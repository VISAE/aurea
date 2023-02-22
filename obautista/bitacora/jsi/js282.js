// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Pruebas de aceptacion
// --- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
function paramsf282() {
	let params = new Array();
	params[0] = window.document.frmedita.aure51id.value;
	//params[1] = window.document.frmedita.p1_282.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf282.value;
	params[102] = window.document.frmedita.lppf282.value;
	//params[103] = window.document.frmedita.bnombre282.value;
	//params[104] = window.document.frmedita.blistar282.value;
	return params;
}
function guardaf282() {
	let valores = new Array();
	valores[1] = window.document.frmedita.aure51id.value;
	valores[2] = window.document.frmedita.aure82consec.value;
	valores[3] = window.document.frmedita.aure82id.value;
	valores[4] = window.document.frmedita.aure82condiciones.value;
	valores[5] = window.document.frmedita.aure82pasos.value;
	valores[6] = window.document.frmedita.aure82asignaperfil.value;
	valores[7] = window.document.frmedita.aure82manuales.value;
	valores[8] = window.document.frmedita.aure82capacitacion.value;
	valores[9] = window.document.frmedita.aure82evaluacion.value;
	valores[10] = window.document.frmedita.aure82resultadoesp.value;
	valores[11] = window.document.frmedita.aure82idtester.value;
	params = paramsf282();
	xajax_f282_Guardar(valores, params);
}
function limpiaf282() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f282_PintarLlaves(params);
	window.document.frmedita.aure82condiciones.value = '';
	window.document.frmedita.aure82pasos.value = '';
	window.document.frmedita.aure82asignaperfil.value = '';
	window.document.frmedita.aure82manuales.value = '';
	window.document.frmedita.aure82capacitacion.value = '';
	window.document.frmedita.aure82evaluacion.value = '';
	window.document.frmedita.aure82resultadoesp.value = '';
	window.document.frmedita.aure82idtester.value = 0;
	window.document.frmedita.aure82idtester_td.value = window.document.frmedita.stipodoc.value;
	window.document.frmedita.aure82idtester_doc.value = '';
	document.getElementById('div_aure82idtester').innerHTML = '';
	//ter_traerxid('aure82idtester', window.document.frmedita.idusuario.value);
	verboton('belimina282', 'none');
}
function eliminaf282() {
	if (window.document.frmedita.aure82id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Pruebas de aceptacion?', () => {
			params = paramsf282();
			params[1] = window.document.frmedita.aure51id.value;
			params[2] = window.document.frmedita.aure82consec.value;
			params[3] = window.document.frmedita.aure82id.value;
			//params[13] = window.document.frmedita.p1_282.value;
			xajax_f282_Eliminar(params);
		});
	}
}
function revisaf282() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.aure51id.value;
	params[2] = window.document.frmedita.aure82consec.value;
	params[3] = window.document.frmedita.aure82id.value;
	if ((params[2] != '')) {
		xajax_f282_Traer(params);
	}
}
function cargadatof282(llave1) {
	window.document.frmedita.aure82consec.value = String(llave1);
	revisaf282();
}
function cargaridf282(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f282_Traer(params);
	expandepanel(282, 'block', 0);
}
function paginarf282() {
	params = paramsf282();
	document.getElementById('div_f282detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf282" name="paginaf282" type="hidden" value="' + params[101] + '" /><input id="lppf282" name="lppf282" type="hidden" value="' + params[102] + '" />';
	xajax_f282_HtmlTabla(params);
}
function imprime282() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_282.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_282.value;
	window.document.frmlista.nombrearchivo.value = 'Pruebas de aceptacion';
	window.document.frmlista.submit();
}
