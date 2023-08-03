// JavaScript Document
// --- © Cristhiam Dario Silva Chavez - UNAD - 2022 ---
// --- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
// ---  Cupos
// --- Modelo Versión 2.28.4b viernes, 21 de octubre de 2022
function paramsf3803() {
	let params = new Array();
	params[0] = window.document.frmedita.cipa01id.value;
	//params[1] = window.document.frmedita.p1_3803.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf3803.value;
	params[102] = window.document.frmedita.lppf3803.value;
	params[103] = window.document.frmedita.bdoc3803.value;
	params[104] = window.document.frmedita.bnombre3803.value;
	params[105] = window.document.frmedita.blistar3803.value;
	return params;
}
function guardaf3803() {
	let valores = new Array();
	valores[1] = window.document.frmedita.cipa01id.value;
	valores[2] = window.document.frmedita.cipa03idinscrito.value;
	valores[3] = window.document.frmedita.cipa03id.value;
	valores[4] = window.document.frmedita.cipa03asistencia.value;
	valores[5] = window.document.frmedita.cipa03jornada_1.value;
	valores[6] = window.document.frmedita.cipa03jornada_2.value;
	valores[7] = window.document.frmedita.cipa03jornada_3.value;
	valores[8] = window.document.frmedita.cipa03jornada_4.value;
	valores[9] = window.document.frmedita.cipa03jornada_5.value;
	valores[10] = window.document.frmedita.cipa03idmatricula.value;
	valores[11] = window.document.frmedita.cipa03valoracion.value;
	valores[12] = window.document.frmedita.cipa03retroalimentacion.value;
	params = paramsf3803();
	xajax_f3803_Guardar(valores, params);
}
function limpiaf3803() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f3803_PintarLlaves(params);
	window.document.frmedita.cipa03valoracion.value = '';
	window.document.frmedita.cipa03retroalimentacion.value = '';
	verboton('belimina3803', 'none');
}
function eliminaf3803() {
	if (window.document.frmedita.cipa03id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Cupos?', () => {
			params = paramsf3803();
			params[1] = window.document.frmedita.cipa01id.value;
			params[2] = window.document.frmedita.cipa03idinscrito.value;
			params[3] = window.document.frmedita.cipa03id.value;
			//params[14] = window.document.frmedita.p1_3803.value;
			xajax_f3803_Eliminar(params);
		});
	}
}
function revisaf3803() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.cipa01id.value;
	params[2] = window.document.frmedita.cipa03idinscrito.value;
	params[3] = window.document.frmedita.cipa03id.value;
	if ((params[2] != '')) {
		xajax_f3803_Traer(params);
	}
}
function cargadatof3803(llave1) {
	window.document.frmedita.cipa03idinscrito.value = String(llave1);
	revisaf3803();
}
function cargaridf3803(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f3803_Traer(params);
	expandepanel(3803, 'block', 0);
}
function paginarf3803() {
	params = paramsf3803();
	document.getElementById('div_f3803detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3803" name="paginaf3803" type="hidden" value="' + params[101] + '" /><input id="lppf3803" name="lppf3803" type="hidden" value="' + params[102] + '" />';
	xajax_f3803_HtmlTabla(params);
}
function imprime3803() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_3803.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_3803.value;
	window.document.frmlista.nombrearchivo.value = 'Cupos';
	window.document.frmlista.submit();
}
