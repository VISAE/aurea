// JavaScript Document
// --- © Omar Augusto Bautista - UNAD - 2026 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// ---  Configura Anexos
// --- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
function paramsf2942() {
	let params = new Array();
	params[0] = window.document.frmedita.visa34id.value;
	//params[1] = window.document.frmedita.p1_2942.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2942.value;
	params[102] = window.document.frmedita.lppf2942.value;
	//params[103] = window.document.frmedita.bnombre2942.value;
	//params[104] = window.document.frmedita.blistar2942.value;
	return params;
}
function guardaf2942() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa34id.value;
	valores[2] = window.document.frmedita.visa42consec.value;
	valores[3] = window.document.frmedita.visa42id.value;
	valores[4] = window.document.frmedita.visa42titulo.value;
	valores[5] = window.document.frmedita.visa42descripcion.value;
	valores[6] = window.document.frmedita.visa42activo.value;
	valores[7] = window.document.frmedita.visa42orden.value;
	valores[8] = window.document.frmedita.visa42obligatorio.value;
	valores[9] = window.document.frmedita.visa42tipodocumento.value;
	params = paramsf2942();
	xajax_f2942_Guardar(valores, params);
}
function limpiaf2942() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa34id.value;
	xajax_f2942_PintarLlaves(params);
	window.document.frmedita.visa42titulo.value = '';
	window.document.frmedita.visa42descripcion.value = '';
	window.document.frmedita.visa42activo.value = 1;
	window.document.frmedita.visa42orden.value = '';
	window.document.frmedita.visa42obligatorio.value = 1;
	window.document.frmedita.visa42tipodocumento.value = '';
	verboton('belimina2942', 'none');
}

function eliminaf2942() {
	if (window.document.frmedita.visa42id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Configura Anexos?', () => {
			params = paramsf2942();
			params[1] = window.document.frmedita.visa34id.value;
			params[2] = window.document.frmedita.visa42consec.value;
			params[3] = window.document.frmedita.visa42id.value;
			//params[11] = window.document.frmedita.p1_2942.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2942_Eliminar(params);
		});
	}
}

function revisaf2942() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa34id.value;
	params[2] = window.document.frmedita.visa42consec.value;
	params[3] = window.document.frmedita.visa42id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2942_Traer(params);
	}
}

function cargadatof2942(llave1) {
	window.document.frmedita.visa42consec.value = String(llave1);
	revisaf2942();
}

function cargaridf2942(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2942_Traer(params);
	expandepanel(2942, 'block', 0);
}

function paginarf2942() {
	params = paramsf2942();
	document.getElementById('div_f2942detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2942" name="paginaf2942" type="hidden" value="' + params[101] + '" /><input id="lppf2942" name="lppf2942" type="hidden" value="' + params[102] + '" />';
	xajax_f2942_HtmlTabla(params);
}

function imprime2942() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2942.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2942.value;
	window.document.frmlista.nombrearchivo.value = 'Configura Anexos';
	window.document.frmlista.submit();
}

