// JavaScript Document
// --- © Omar Augusto Bautista - UNAD - 2026 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// ---  Anexos
// --- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026

	function limpia_visa43idarchivo() {
		window.document.frmedita.visa43idorigen.value = 0;
		window.document.frmedita.visa43idarchivo.value = 0;
		let da_Archivo = document.getElementById('div_visa43idarchivo');
		da_Archivo.innerHTML = '&nbsp;';
		verboton('beliminavisa43idarchivo', 'none');
		//paginarf2943();
	}

	function carga_visa43idarchivo(u) {
		window.document.frmedita.iscroll.value = window.scrollY;
		window.document.frmedita.div96v1.value = '';
		window.document.frmedita.div96v2.value = '';
		window.document.frmedita.div96v3.value = '';
		document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_2940.value + ' - Cargar archivo</h2>';
		document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="upload.php?u=' + u +'" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}

	function eliminavisa43idarchivo() {
		let did = window.document.frmedita.visa43id;
		ModalConfirmV2('&iquest;Esta seguro de eliminar el archivo?', () => {
			xajax_elimina_archivo_visa43idarchivo(did.value);
			//paginarf2943();
		});
	}
function paramsf2943() {
	let params = new Array();
	params[0] = window.document.frmedita.visa40id.value;
	//params[1] = window.document.frmedita.p1_2943.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2943.value;
	params[102] = window.document.frmedita.lppf2943.value;
	//params[103] = window.document.frmedita.bnombre2943.value;
	//params[104] = window.document.frmedita.blistar2943.value;
	return params;
}
function guardaf2943() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa40id.value;
	valores[2] = window.document.frmedita.visa43iddocumento.value;
	valores[3] = window.document.frmedita.visa43id.value;
	valores[6] = window.document.frmedita.visa43fechaaprob.value;
	valores[7] = window.document.frmedita.visa43usuarioaprueba.value;
	params = paramsf2943();
	xajax_f2943_Guardar(valores, params);
}
function limpiaf2943() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa40id.value;
	xajax_f2943_PintarLlaves(params);
	limpia_visa43idarchivo();
	verboton('banexavisa43idarchivo', 'none');
	verboton('belimina2943', 'none');
}

function eliminaf2943() {
	if (window.document.frmedita.visa43id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Anexos?', () => {
			params = paramsf2943();
			params[1] = window.document.frmedita.visa40id.value;
			params[2] = window.document.frmedita.visa43iddocumento.value;
			params[3] = window.document.frmedita.visa43id.value;
			//params[9] = window.document.frmedita.p1_2943.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2943_Eliminar(params);
		});
	}
}

function revisaf2943() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa40id.value;
	params[2] = window.document.frmedita.visa43iddocumento.value;
	params[3] = window.document.frmedita.visa43id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2943_Traer(params);
	}
}

function cargadatof2943(llave1) {
	window.document.frmedita.visa43iddocumento.value = String(llave1);
	revisaf2943();
}

function cargaridf2943(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2943_Traer(params);
	expandepanel(2943, 'block', 0);
}

function paginarf2943() {
	params = paramsf2943();
	document.getElementById('div_f2943detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2943" name="paginaf2943" type="hidden" value="' + params[101] + '" /><input id="lppf2943" name="lppf2943" type="hidden" value="' + params[102] + '" />';
	xajax_f2943_HtmlTabla(params);
}

function imprime2943() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2943.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2943.value;
	window.document.frmlista.nombrearchivo.value = 'Anexos';
	window.document.frmlista.submit();
}

