// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anexo
// --- Modelo Versión 3.1.5b lunes, 23 de marzo de 2026

	function limpia_masi07idarchivo() {
		window.document.frmedita.masi07idorigen.value = 0;
		window.document.frmedita.masi07idarchivo.value = 0;
		let da_Archivo = document.getElementById('div_masi07idarchivo');
		da_Archivo.innerHTML = '&nbsp;';
		verboton('beliminamasi07idarchivo', 'none');
		//paginarf1207();
	}

	function carga_masi07idarchivo(u) {
		window.document.frmedita.iscroll.value = window.scrollY;
		window.document.frmedita.div96v1.value = '';
		window.document.frmedita.div96v2.value = '';
		window.document.frmedita.div96v3.value = '';
		document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_1205.value + ' - Cargar archivo</h2>';
		document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="upload.php?u=' + u +'" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}

	function eliminamasi07idarchivo() {
		let did = window.document.frmedita.masi07id;
		let dbloque = window.document.frmedita.bloque;
		ModalConfirmV2('&iquest;Esta seguro de eliminar el archivo?', () => {
			xajax_elimina_archivo_masi07idarchivo(did.value, dbloque.value);
			//paginarf1207();
		});
	}
function paramsf1207() {
	let params = new Array();
	params[0] = window.document.frmedita.masi05id.value;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf1207.value;
	params[102] = window.document.frmedita.lppf1207.value;
	//params[103] = window.document.frmedita.bnombre1207.value;
	//params[104] = window.document.frmedita.blistar1207.value;
	return params;
}
function guardaf1207() {
	let valores = new Array();
	valores[1] = window.document.frmedita.masi05id.value;
	valores[2] = window.document.frmedita.masi07consec.value;
	valores[3] = window.document.frmedita.masi07id.value;
	valores[4] = window.document.frmedita.masi07titulo.value;
	valores[97] = window.document.frmedita.bloque.value;
	params = paramsf1207();
	xajax_f1207_Guardar(valores, params);
}
function limpiaf1207() {
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.masi05id.value;
	params[97] = window.document.frmedita.bloque.value;
	xajax_f1207_PintarLlaves(params);
	window.document.frmedita.masi07titulo.value = '';
	limpia_masi07idarchivo();
	verboton('banexamasi07idarchivo', 'none');
	verboton('belimina1207', 'none');
}

function eliminaf1207() {
	if (window.document.frmedita.masi07id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Anexo?', () => {
			params = paramsf1207();
			params[1] = window.document.frmedita.masi05id.value;
			params[2] = window.document.frmedita.masi07consec.value;
			params[3] = window.document.frmedita.masi07id.value;
			//params[8] = window.document.frmedita.p1_1207.value;
			params[97] = window.document.frmedita.bloque.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f1207_Eliminar(params);
		});
	}
}

function revisaf1207() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.masi05id.value;
	params[2] = window.document.frmedita.masi07consec.value;
	params[3] = window.document.frmedita.masi07id.value;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f1207_Traer(params);
	}
}

function cargadatof1207(llave1) {
	window.document.frmedita.masi07consec.value = String(llave1);
	revisaf1207();
}

function cargaridf1207(llave1) {
	let params = new Array();
	params[0] = 2;
	params[97] = window.document.frmedita.bloque.value;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f1207_Traer(params);
	expandepanel(1207, 'block', 0);
}

function paginarf1207() {
	params = paramsf1207();
	document.getElementById('div_f1207detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1207" name="paginaf1207" type="hidden" value="' + params[101] + '" /><input id="lppf1207" name="lppf1207" type="hidden" value="' + params[102] + '" />';
	xajax_f1207_HtmlTabla(params);
}

function imprime1207() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_1207.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_1207.value;
	window.document.frmlista.nombrearchivo.value = 'Anexo';
	window.document.frmlista.submit();
}

