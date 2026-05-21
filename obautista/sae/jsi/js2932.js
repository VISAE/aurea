// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Participantes
// --- Modelo Versión 2.25.10c miércoles, 7 de abril de 2021
// --- Modelo Versión 2.28.1 jueves, 7 de abril de 2022
function paramsf2932() {
	let params = new Array();
	params[0] = window.document.frmedita.plab31id.value;
	//params[1]=window.document.frmedita.p1_2932.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2932.value;
	params[102] = window.document.frmedita.lppf2932.value;
	//params[103]=window.document.frmedita.bnombre2932.value;
	//params[104]=window.document.frmedita.blistar2932.value;
	return params;
}
function guardaf2932() {
	let valores = new Array();
	valores[1] = window.document.frmedita.plab31id.value;
	valores[2] = window.document.frmedita.plab32idtercero.value;
	valores[3] = window.document.frmedita.plab32id.value;
	valores[4] = window.document.frmedita.plab32estado.value;
	valores[5] = window.document.frmedita.plab32fechaingreso.value;
	valores[6] = window.document.frmedita.plab32fechafin.value;
	params = paramsf2932();
	xajax_f2932_Guardar(valores, params);
}
function limpiaf2932() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f2932_PintarLlaves(params);
	window.document.frmedita.plab32estado.value = 1;
	fecha_AsignarNum('plab32fechaingreso', iFechaBaseNum);
	fecha_AsignarNum('plab32fechafin', iFechaBaseNum);
	verboton('belimina2932', 'none');
}
function eliminaf2932() {
	if (window.document.frmedita.plab32id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato?', () => {
			params = paramsf2932();
			params[0] = window.document.frmedita.plab31id.value;
			params[1] = window.document.frmedita.plab31id.value;
			params[2] = window.document.frmedita.plab32idtercero.value;
			params[3] = window.document.frmedita.plab32id.value;
			//params[8]=window.document.frmedita.p1_2932.value;
			xajax_f2932_Eliminar(params);
		});
	}
}
function revisaf2932() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.plab31id.value;
	params[2] = window.document.frmedita.plab32idtercero.value;
	params[3] = window.document.frmedita.plab32id.value;
	if ((params[2] != '')) {
		xajax_f2932_Traer(params);
	}
}
function cargadatof2932(llave1) {
	window.document.frmedita.plab32idtercero.value = String(llave1);
	revisaf2932();
}
function cargaridf2932(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f2932_Traer(params);
	expandepanel(2932, 'block', 0);
}
function paginarf2932() {
	params = paramsf2932();
	document.getElementById('div_f2932detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2932" name="paginaf2932" type="hidden" value="' + params[101] + '" /><input id="lppf2932" name="lppf2932" type="hidden" value="' + params[102] + '" />';
	xajax_f2932_HtmlTabla(params);
}
function imprime2932() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2932.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2932.value;
	window.document.frmlista.nombrearchivo.value = 'Participantes';
	window.document.frmlista.submit();
}
