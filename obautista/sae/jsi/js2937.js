// JavaScript Document
// --- © Omar Augusto Bautista - UNAD - 2026 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// ---  Subtipologias de convocatorias
// --- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
function paramsf2937() {
	let params = new Array();
	params[0] = window.document.frmedita.visa36id.value;
	//params[1] = window.document.frmedita.p1_2937.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2937.value;
	params[102] = window.document.frmedita.lppf2937.value;
	//params[103] = window.document.frmedita.bnombre2937.value;
	//params[104] = window.document.frmedita.blistar2937.value;
	return params;
}
function guardaf2937() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa36id.value;
	valores[2] = window.document.frmedita.visa37id.value;
	valores[3] = window.document.frmedita.visa37nombre.value;
	valores[4] = window.document.frmedita.visa37activo.value;
	params = paramsf2937();
	xajax_f2937_Guardar(valores, params);
}
function limpiaf2937() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa36id.value;
	xajax_f2937_PintarLlaves(params);
	window.document.frmedita.visa37nombre.value = '';
	window.document.frmedita.visa37activo.value = 1;
	verboton('belimina2937', 'none');
}

function eliminaf2937() {
	if (window.document.frmedita.visa37id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Subtipologias de convocatorias?', () => {
			params = paramsf2937();
			params[1] = window.document.frmedita.visa36id.value;
			params[2] = window.document.frmedita.visa37id.value;
			//params[6] = window.document.frmedita.p1_2937.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2937_Eliminar(params);
		});
	}
}

function revisaf2937() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa36id.value;
	params[2] = window.document.frmedita.visa37id.value;
	params[99] = window.document.frmedita.debug.value;
	if () {
		xajax_f2937_Traer(params);
	}
}

function cargadatof2937() {
	revisaf2937();
}

function cargaridf2937(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2937_Traer(params);
	expandepanel(2937, 'block', 0);
}

function paginarf2937() {
	params = paramsf2937();
	document.getElementById('div_f2937detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2937" name="paginaf2937" type="hidden" value="' + params[101] + '" /><input id="lppf2937" name="lppf2937" type="hidden" value="' + params[102] + '" />';
	xajax_f2937_HtmlTabla(params);
}

function imprime2937() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2937.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2937.value;
	window.document.frmlista.nombrearchivo.value = 'Subtipologias de convocatorias';
	window.document.frmlista.submit();
}

