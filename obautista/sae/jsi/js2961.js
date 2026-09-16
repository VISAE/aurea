// JavaScript Document
// --- © Juan David Avellaneda Molina - UNAD - 2026 ---
// --- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Dificultades
// --- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
function paramsf2961() {
	let params = new Array();
	params[0] = window.document.frmedita.visa57id.value;
	//params[1] = window.document.frmedita.p1_2961.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2961.value;
	params[102] = window.document.frmedita.lppf2961.value;
	//params[103] = window.document.frmedita.bnombre2961.value;
	//params[104] = window.document.frmedita.blistar2961.value;
	return params;
}
function guardaf2961() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa57id.value;
	valores[2] = window.document.frmedita.visa61consec.value;
	valores[3] = window.document.frmedita.visa61id.value;
	valores[4] = window.document.frmedita.visa61descripcion.value;
	valores[5] = window.document.frmedita.visa61impacto.value;
	valores[6] = window.document.frmedita.visa61requiereapoyo.value;
	valores[7] = window.document.frmedita.visa61fecharegistro.value;
	params = paramsf2961();
	xajax_f2961_Guardar(valores, params);
}
function limpiaf2961() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa57id.value;
	xajax_f2961_PintarLlaves(params);
	window.document.frmedita.visa61descripcion.value = '';
	window.document.frmedita.visa61impacto.value = 1;
	window.document.frmedita.visa61requiereapoyo.value = 1;
	verboton('belimina2961', 'none');
}

function eliminaf2961() {
	if (window.document.frmedita.visa61id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Dificultades?', () => {
			params = paramsf2961();
			params[1] = window.document.frmedita.visa57id.value;
			params[2] = window.document.frmedita.visa61consec.value;
			params[3] = window.document.frmedita.visa61id.value;
			//params[9] = window.document.frmedita.p1_2961.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2961_Eliminar(params);
		});
	}
}

function revisaf2961() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa57id.value;
	params[2] = window.document.frmedita.visa61consec.value;
	params[3] = window.document.frmedita.visa61id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2961_Traer(params);
	}
}

function cargadatof2961(llave1) {
	window.document.frmedita.visa61consec.value = String(llave1);
	revisaf2961();
}

function cargaridf2961(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2961_Traer(params);
	expandepanel(2961, 'block', 0);
}

function paginarf2961() {
	params = paramsf2961();
	document.getElementById('div_f2961detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia"><?php echo $ETI['msg_procesando_datos']; ?></div></div><input id="paginaf2961" name="paginaf2961" type="hidden" value="' + params[101] + '" /><input id="lppf2961" name="lppf2961" type="hidden" value="' + params[102] + '" />';
	xajax_f2961_HtmlTabla(params);
}

function imprime2961() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2961.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2961.value;
	window.document.frmlista.nombrearchivo.value = 'Dificultades';
	window.document.frmlista.submit();
}

