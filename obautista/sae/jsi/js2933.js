// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Acceso a cursos
// --- Modelo Versión 2.28.1 jueves, 7 de abril de 2022
function carga_combo_plab33idcurso() {
	let params = new Array();
	params[0] = window.document.frmedita.plab33idperiodo.value;
	document.getElementById('div_plab33idcurso').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="plab33idcurso" name="plab33idcurso" type="hidden" value="" />';
	xajax_f2933_Comboplab33idcurso(params);
}
function paramsf2933() {
	let params = new Array();
	params[0] = window.document.frmedita.plab31id.value;
	//params[1]=window.document.frmedita.p1_2933.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2933.value;
	params[102] = window.document.frmedita.lppf2933.value;
	params[103] = window.document.frmedita.bnombre2933.value;
	//params[104]=window.document.frmedita.blistar2933.value;
	return params;
}
function guardaf2933() {
	let valores = new Array();
	valores[1] = window.document.frmedita.plab31id.value;
	valores[2] = window.document.frmedita.plab33idmonitor.value;
	valores[3] = window.document.frmedita.plab33idperiodo.value;
	valores[4] = window.document.frmedita.plab33idcurso.value;
	valores[5] = window.document.frmedita.plab33id.value;
	valores[6] = window.document.frmedita.plab33activo.value;
	params = paramsf2933();
	xajax_f2933_Guardar(valores, params);
}
function limpiaf2933() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f2933_PintarLlaves(params);
	window.document.frmedita.plab33activo.value = 1;
	verboton('belimina2933', 'none');
}
function eliminaf2933() {
	if (window.document.frmedita.plab33id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato?', () => {
			params = paramsf2933();
			params[1] = window.document.frmedita.plab31id.value;
			params[2] = window.document.frmedita.plab33idmonitor.value;
			params[3] = window.document.frmedita.plab33idperiodo.value;
			params[4] = window.document.frmedita.plab33idcurso.value;
			params[5] = window.document.frmedita.plab33id.value;
			//params[8]=window.document.frmedita.p1_2933.value;
			xajax_f2933_Eliminar(params);
		});
	}
}
function revisaf2933() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.plab31id.value;
	params[2] = window.document.frmedita.plab33idmonitor.value;
	params[3] = window.document.frmedita.plab33idperiodo.value;
	params[4] = window.document.frmedita.plab33idcurso.value;
	params[5] = window.document.frmedita.plab33id.value;
	if ((params[2] != '') && (params[3] != '') && (params[4] != '')) {
		xajax_f2933_Traer(params);
	}
}
function cargadatof2933(llave1, llave2, llave3) {
	window.document.frmedita.plab33idmonitor.value = String(llave1);
	window.document.frmedita.plab33idperiodo.value = String(llave2);
	window.document.frmedita.plab33idcurso.value = String(llave3);
	revisaf2933();
}
function cargaridf2933(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f2933_Traer(params);
	expandepanel(2933, 'block', 0);
}
function paginarf2933() {
	params = paramsf2933();
	document.getElementById('div_f2933detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2933" name="paginaf2933" type="hidden" value="' + params[101] + '" /><input id="lppf2933" name="lppf2933" type="hidden" value="' + params[102] + '" />';
	xajax_f2933_HtmlTabla(params);
}
function imprime2933() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2933.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2933.value;
	window.document.frmlista.nombrearchivo.value = 'Acceso a cursos';
	window.document.frmlista.submit();
}
