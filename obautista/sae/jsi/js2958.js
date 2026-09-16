// JavaScript Document
// --- © Juan David Avellaneda Molina - UNAD - 2026 ---
// --- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Resultados
// --- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
function paramsf2958() {
	let params = new Array();
	params[0] = window.document.frmedita.visa57id.value;
	//params[1] = window.document.frmedita.p1_2958.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2958.value;
	params[102] = window.document.frmedita.lppf2958.value;
	//params[103] = window.document.frmedita.bnombre2958.value;
	//params[104] = window.document.frmedita.blistar2958.value;
	return params;
}
function guardaf2958() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa57id.value;
	valores[2] = window.document.frmedita.visa58consec.value;
	valores[3] = window.document.frmedita.visa58id.value;
	valores[4] = window.document.frmedita.visa58descripcion.value;
	valores[5] = window.document.frmedita.visa58cumplimiento.value;
	valores[6] = window.document.frmedita.visa58fecharegistro.value;
	params = paramsf2958();
	xajax_f2958_Guardar(valores, params);
}
function limpiaf2958() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa57id.value;
	xajax_f2958_PintarLlaves(params);
	window.document.frmedita.visa58descripcion.value = '';
	window.document.frmedita.visa58cumplimiento.value = 1;
	verboton('belimina2958', 'none');
}

function eliminaf2958() {
	if (window.document.frmedita.visa58id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Resultados?', () => {
			params = paramsf2958();
			params[1] = window.document.frmedita.visa57id.value;
			params[2] = window.document.frmedita.visa58consec.value;
			params[3] = window.document.frmedita.visa58id.value;
			//params[8] = window.document.frmedita.p1_2958.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2958_Eliminar(params);
		});
	}
}

function revisaf2958() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa57id.value;
	params[2] = window.document.frmedita.visa58consec.value;
	params[3] = window.document.frmedita.visa58id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2958_Traer(params);
	}
}

function cargadatof2958(llave1) {
	window.document.frmedita.visa58consec.value = String(llave1);
	revisaf2958();
}

function cargaridf2958(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2958_Traer(params);
	expandepanel(2958, 'block', 0);
}

function paginarf2958() {
	params = paramsf2958();
	document.getElementById('div_f2958detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia"><?php echo $ETI['msg_procesando_datos']; ?></div></div><input id="paginaf2958" name="paginaf2958" type="hidden" value="' + params[101] + '" /><input id="lppf2958" name="lppf2958" type="hidden" value="' + params[102] + '" />';
	xajax_f2958_HtmlTabla(params);
}

function imprime2958() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2958.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2958.value;
	window.document.frmlista.nombrearchivo.value = 'Resultados';
	window.document.frmlista.submit();
}

