// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anexos
// --- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023

	function limpia_aure58idarchivo() {
		window.document.frmedita.aure58idorigen.value = 0;
		window.document.frmedita.aure58idarchivo.value = 0;
		let da_Archivo = document.getElementById('div_aure58idarchivo');
		da_Archivo.innerHTML = '&nbsp;';
		verboton('beliminaaure58idarchivo', 'none');
		//paginarf253();
	}

	function carga_aure58idarchivo() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		window.document.frmedita.div96v1.value = '';
		window.document.frmedita.div96v2.value = '';
		window.document.frmedita.div96v3.value = '';
		document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_251.value + ' - Cargar archivo</h2>';
		document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="framearchivo.php?ref=253&id=' + window.document.frmedita.aure58id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}

	function eliminaaure58idarchivo() {
		let did = window.document.frmedita.aure58id;
		ModalConfirmV2('&iquest;Esta seguro de eliminar el archivo?', () => {
			xajax_elimina_archivo_aure58idarchivo(did.value);
			//paginarf253();
		});
	}
function paramsf253() {
	let params = new Array();
	params[0] = window.document.frmedita.aure51id.value;
	//params[1] = window.document.frmedita.p1_253.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf253.value;
	params[102] = window.document.frmedita.lppf253.value;
	//params[103] = window.document.frmedita.bnombre253.value;
	//params[104] = window.document.frmedita.blistar253.value;
	return params;
}
function guardaf253() {
	let valores = new Array();
	valores[1] = window.document.frmedita.aure51id.value;
	valores[2] = window.document.frmedita.aure58consec.value;
	valores[3] = window.document.frmedita.aure58id.value;
	valores[4] = window.document.frmedita.aure58titulo.value;
	valores[7] = window.document.frmedita.aure58idusuario.value;
	valores[8] = window.document.frmedita.aure58fecha.value;
	params = paramsf253();
	xajax_f253_Guardar(valores, params);
}
function limpiaf253() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f253_PintarLlaves(params);
	window.document.frmedita.aure58titulo.value = '';
	limpia_aure58idarchivo();
	verboton('banexaaure58idarchivo', 'none');
	verboton('belimina253', 'none');
}
function eliminaf253() {
	if (window.document.frmedita.aure58id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Anexos?', () => {
			params = paramsf253();
			params[1] = window.document.frmedita.aure51id.value;
			params[2] = window.document.frmedita.aure58consec.value;
			params[3] = window.document.frmedita.aure58id.value;
			//params[10] = window.document.frmedita.p1_253.value;
			xajax_f253_Eliminar(params);
		});
	}
}
function revisaf253() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.aure51id.value;
	params[2] = window.document.frmedita.aure58consec.value;
	params[3] = window.document.frmedita.aure58id.value;
	if ((params[2] != '')) {
		xajax_f253_Traer(params);
	}
}
function cargadatof253(llave1) {
	window.document.frmedita.aure58consec.value = String(llave1);
	revisaf253();
}
function cargaridf253(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f253_Traer(params);
	expandepanel(253, 'block', 0);
}
function paginarf253() {
	params = paramsf253();
	document.getElementById('div_f253detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf253" name="paginaf253" type="hidden" value="' + params[101] + '" /><input id="lppf253" name="lppf253" type="hidden" value="' + params[102] + '" />';
	xajax_f253_HtmlTabla(params);
}
function imprime253() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_253.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_253.value;
	window.document.frmlista.nombrearchivo.value = 'Anexos';
	window.document.frmlista.submit();
}
