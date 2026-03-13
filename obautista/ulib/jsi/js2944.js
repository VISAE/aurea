// JavaScript Document
// --- © Omar Augusto Bautista - UNAD - 2026 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// ---  Anotaciones
// --- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
function paramsf2944() {
	let params = new Array();
	params[0] = window.document.frmedita.visa40id.value;
	//params[1] = window.document.frmedita.p1_2944.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2944.value;
	params[102] = window.document.frmedita.lppf2944.value;
	//params[103] = window.document.frmedita.bnombre2944.value;
	//params[104] = window.document.frmedita.blistar2944.value;
	return params;
}
function guardaf2944() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa40id.value;
	valores[2] = window.document.frmedita.visa44consec.value;
	valores[3] = window.document.frmedita.visa44id.value;
	valores[4] = window.document.frmedita.visa44alcance.value;
	valores[5] = window.document.frmedita.visa44nota.value;
	valores[6] = window.document.frmedita.visa44usuario.value;
	valores[7] = window.document.frmedita.visa44fecha.value;
	valores[8] = window.document.frmedita.visa44hora.value;
	valores[9] = window.document.frmedita.visa44minuto.value;
	params = paramsf2944();
	xajax_f2944_Guardar(valores, params);
}
function limpiaf2944() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa40id.value;
	xajax_f2944_PintarLlaves(params);
	window.document.frmedita.visa44alcance.value = '';
	window.document.frmedita.visa44nota.value = '';
	verboton('belimina2944', 'none');
}

function eliminaf2944() {
	if (window.document.frmedita.visa44id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Anotaciones?', () => {
			params = paramsf2944();
			params[1] = window.document.frmedita.visa40id.value;
			params[2] = window.document.frmedita.visa44consec.value;
			params[3] = window.document.frmedita.visa44id.value;
			//params[11] = window.document.frmedita.p1_2944.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2944_Eliminar(params);
		});
	}
}

function revisaf2944() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa40id.value;
	params[2] = window.document.frmedita.visa44consec.value;
	params[3] = window.document.frmedita.visa44id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2944_Traer(params);
	}
}

function cargadatof2944(llave1) {
	window.document.frmedita.visa44consec.value = String(llave1);
	revisaf2944();
}

function cargaridf2944(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2944_Traer(params);
	expandepanel(2944, 'block', 0);
}

function paginarf2944() {
	params = paramsf2944();
	document.getElementById('div_f2944detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2944" name="paginaf2944" type="hidden" value="' + params[101] + '" /><input id="lppf2944" name="lppf2944" type="hidden" value="' + params[102] + '" />';
	xajax_f2944_HtmlTabla(params);
}

function imprime2944() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2944.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2944.value;
	window.document.frmlista.nombrearchivo.value = 'Anotaciones';
	window.document.frmlista.submit();
}

