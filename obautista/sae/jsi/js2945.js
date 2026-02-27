// JavaScript Document
// --- © Omar Augusto Bautista - UNAD - 2026 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// ---  Resultados pruebas
// --- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
function paramsf2945() {
	let params = new Array();
	params[0] = window.document.frmedita.visa40id.value;
	//params[1] = window.document.frmedita.p1_2945.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2945.value;
	params[102] = window.document.frmedita.lppf2945.value;
	//params[103] = window.document.frmedita.bnombre2945.value;
	//params[104] = window.document.frmedita.blistar2945.value;
	return params;
}
function guardaf2945() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa40id.value;
	valores[2] = window.document.frmedita.visa45idprueba.value;
	valores[3] = window.document.frmedita.visa45id.value;
	valores[4] = window.document.frmedita.visa45puntaje.value;
	params = paramsf2945();
	xajax_f2945_Guardar(valores, params);
}
function limpiaf2945() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa40id.value;
	xajax_f2945_PintarLlaves(params);
	window.document.frmedita.visa45puntaje.value = '';
	verboton('belimina2945', 'none');
}

function eliminaf2945() {
	if (window.document.frmedita.visa45id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Resultados pruebas?', () => {
			params = paramsf2945();
			params[1] = window.document.frmedita.visa40id.value;
			params[2] = window.document.frmedita.visa45idprueba.value;
			params[3] = window.document.frmedita.visa45id.value;
			//params[6] = window.document.frmedita.p1_2945.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2945_Eliminar(params);
		});
	}
}

function revisaf2945() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa40id.value;
	params[2] = window.document.frmedita.visa45idprueba.value;
	params[3] = window.document.frmedita.visa45id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2945_Traer(params);
	}
}

function cargadatof2945(llave1) {
	window.document.frmedita.visa45idprueba.value = String(llave1);
	revisaf2945();
}

function cargaridf2945(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2945_Traer(params);
	expandepanel(2945, 'block', 0);
}

function paginarf2945() {
	params = paramsf2945();
	document.getElementById('div_f2945detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2945" name="paginaf2945" type="hidden" value="' + params[101] + '" /><input id="lppf2945" name="lppf2945" type="hidden" value="' + params[102] + '" />';
	xajax_f2945_HtmlTabla(params);
}

function imprime2945() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2945.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2945.value;
	window.document.frmlista.nombrearchivo.value = 'Resultados pruebas';
	window.document.frmlista.submit();
}

