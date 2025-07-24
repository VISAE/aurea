// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Red de servicio - equipos
// --- Modelo Versión 3.0.16 jueves, 10 de julio de 2025

function carga_combo_saiu75idcentro() {
	let params = new Array();
	params[0] = window.document.frmedita.saiu75idzona.value;
	document.getElementById('div_saiu75idcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu75idcentro" name="saiu75idcentro" type="hidden" value="" />';
	xajax_f3075_Combosaiu75idcentro(params);
}
function paramsf3075() {
	let params = new Array();
	params[0] = window.document.frmedita.saiu74id.value;
	//params[1] = window.document.frmedita.p1_3075.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf3075.value;
	params[102] = window.document.frmedita.lppf3075.value;
	//params[103] = window.document.frmedita.bnombre3075.value;
	//params[104] = window.document.frmedita.blistar3075.value;
	return params;
}
function guardaf3075() {
	let valores = new Array();
	valores[1] = window.document.frmedita.saiu74id.value;
	valores[2] = window.document.frmedita.saiu75idzona.value;
	valores[3] = window.document.frmedita.saiu75idcentro.value;
	valores[4] = window.document.frmedita.saiu75id.value;
	valores[5] = window.document.frmedita.saiu75idequipo.value;
	params = paramsf3075();
	xajax_f3075_Guardar(valores, params);
}
function limpiaf3075() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.saiu74id.value;
	xajax_f3075_PintarLlaves(params);
	window.document.frmedita.saiu75idequipo.value = '';
	verboton('belimina3075', 'none');
}

function eliminaf3075() {
	if (window.document.frmedita.saiu75id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Red de servicio - equipos?', () => {
			params = paramsf3075();
			params[1] = window.document.frmedita.saiu74id.value;
			params[2] = window.document.frmedita.saiu75idzona.value;
			params[3] = window.document.frmedita.saiu75idcentro.value;
			params[4] = window.document.frmedita.saiu75id.value;
			//params[7] = window.document.frmedita.p1_3075.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f3075_Eliminar(params);
		});
	}
}

function revisaf3075() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.saiu74id.value;
	params[2] = window.document.frmedita.saiu75idzona.value;
	params[3] = window.document.frmedita.saiu75idcentro.value;
	params[4] = window.document.frmedita.saiu75id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '') && (params[3] != '')) {
		xajax_f3075_Traer(params);
	}
}

function cargadatof3075(llave1, llave2) {
	window.document.frmedita.saiu75idzona.value = String(llave1);
	window.document.frmedita.saiu75idcentro.value = String(llave2);
	revisaf3075();
}

function cargaridf3075(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f3075_Traer(params);
	expandepanel(3075, 'block', 0);
}

function paginarf3075() {
	params = paramsf3075();
	document.getElementById('div_f3075detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3075" name="paginaf3075" type="hidden" value="' + params[101] + '" /><input id="lppf3075" name="lppf3075" type="hidden" value="' + params[102] + '" />';
	xajax_f3075_HtmlTabla(params);
}

function imprime3075() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_3075.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_3075.value;
	window.document.frmlista.nombrearchivo.value = 'Red de servicio - equipos';
	window.document.frmlista.submit();
}

