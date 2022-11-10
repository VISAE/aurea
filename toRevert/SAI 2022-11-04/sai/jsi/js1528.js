// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Integrantes
// --- Modelo Versión 2.22.7 miércoles, 20 de marzo de 2019
// --- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
// --- Modelo Versión 2.28.2 viernes, 17 de junio de 2022
function paramsf1528() {
	var params = new Array();
	params[0] = window.document.frmedita.bita27id.value;
	//params[1] = window.document.frmedita.p1_1528.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf1528.value;
	params[102] = window.document.frmedita.lppf1528.value;
	//params[103] = window.document.frmedita.bnombre1528.value;
	//params[104] = window.document.frmedita.blistar1528.value;
	return params;
}
function guardaf1528() {
	var valores = new Array();
	valores[1] = window.document.frmedita.bita27id.value;
	valores[2] = window.document.frmedita.bita28idtercero.value;
	valores[3] = window.document.frmedita.bita28id.value;
	valores[4] = window.document.frmedita.bita28activo.value;
	valores[5] = window.document.frmedita.bita28fechaingreso.value;
	valores[6] = window.document.frmedita.bita28fechasalida.value;
	params = paramsf1528();
	xajax_f1528_Guardar(valores, params);
}
function limpiaf1528() {
	var sfbase = window.document.frmedita.shoy.value;
	var iFechaBaseNum1 = window.document.frmedita.ihoy.value;
	var iFechaBaseNum = 0;
	MensajeAlarmaV2('', 0);
	var params = new Array();
	xajax_f1528_PintarLlaves(params);
	window.document.frmedita.bita28activo.value = 'S';
	fecha_AsignarNum('bita28fechaingreso', iFechaBaseNum1);
	fecha_AsignarNum('bita28fechasalida', iFechaBaseNum);
	verboton('belimina1528', 'none');
}
function eliminaf1528() {
	params = paramsf1528();
	params[0] = window.document.frmedita.bita27id.value;
	params[1] = window.document.frmedita.bita27id.value;
	params[2] = window.document.frmedita.bita28idtercero.value;
	params[3] = window.document.frmedita.bita28id.value;
	//params[8]=window.document.frmedita.p1_1528.value;
	if (window.document.frmedita.bita28id.value != '') {
		ModalConfirm('&iquest;Est&aacute; seguro de eliminar el dato?');
		ModalDialogConfirm(function (confirm) {
			if (confirm) {
				xajax_f1528_Eliminar(params);
			}
		});
	}
}
function revisaf1528() {
	var params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.bita27id.value;
	params[2] = window.document.frmedita.bita28idtercero.value;
	params[3] = window.document.frmedita.bita28id.value;
	if ((params[2] != '')) {
		xajax_f1528_Traer(params);
	}
}
function cargadatof1528(llave1) {
	window.document.frmedita.bita28idtercero.value = String(llave1);
	revisaf1528();
}
function cargaridf1528(llave1) {
	var params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f1528_Traer(params);
	expandepanel(1528, 'block', 0);
}
function paginarf1528() {
	params = paramsf1528();
	document.getElementById('div_f1528detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1528" name="paginaf1528" type="hidden" value="' + params[101] + '" /><input id="lppf1528" name="lppf1528" type="hidden" value="' + params[102] + '" />';
	xajax_f1528_HtmlTabla(params);
}
function imprime1528() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_1528.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_1528.value;
	window.document.frmlista.nombrearchivo.value = 'Integrantes';
	window.document.frmlista.submit();
}
