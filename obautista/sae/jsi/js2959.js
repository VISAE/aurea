// JavaScript Document
// --- © Juan David Avellaneda Molina - UNAD - 2026 ---
// --- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Evidencias
// --- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026

	function limpia_visa59idarchivo() {
		window.document.frmedita.visa59idorigen.value = 0;
		window.document.frmedita.visa59idarchivo.value = 0;
		let da_Archivo = document.getElementById('div_visa59idarchivo');
		da_Archivo.innerHTML = '&nbsp;';
		verboton('beliminavisa59idarchivo', 'none');
		//paginarf2959();
	}

	function carga_visa59idarchivo(u) {
		window.document.frmedita.iscroll.value = window.scrollY;
		window.document.frmedita.div96v1.value = '';
		window.document.frmedita.div96v2.value = '';
		window.document.frmedita.div96v3.value = '';
		document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_2957.value + ' - Cargar archivo</h2>';
		document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="upload.php?u=' + u +'" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}

	function eliminavisa59idarchivo() {
		let did = window.document.frmedita.visa59id;
		ModalConfirmV2('&iquest;Esta seguro de eliminar el archivo?', () => {
			xajax_elimina_archivo_visa59idarchivo(did.value);
			//paginarf2959();
		});
	}
function paramsf2959() {
	let params = new Array();
	params[0] = window.document.frmedita.visa57id.value;
	//params[1] = window.document.frmedita.p1_2959.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2959.value;
	params[102] = window.document.frmedita.lppf2959.value;
	//params[103] = window.document.frmedita.bnombre2959.value;
	//params[104] = window.document.frmedita.blistar2959.value;
	return params;
}
function guardaf2959() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa57id.value;
	valores[2] = window.document.frmedita.visa59consec.value;
	valores[3] = window.document.frmedita.visa59id.value;
	valores[4] = window.document.frmedita.visa59titulo.value;
	valores[7] = window.document.frmedita.visa59tipoarchivo.value;
	valores[8] = window.document.frmedita.visa59descripcion.value;
	valores[9] = window.document.frmedita.visa59fechacarga.value;
	valores[10] = window.document.frmedita.visa59idusuario.value;
	params = paramsf2959();
	xajax_f2959_Guardar(valores, params);
}
function limpiaf2959() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa57id.value;
	xajax_f2959_PintarLlaves(params);
	window.document.frmedita.visa59titulo.value = '';
	limpia_visa59idarchivo();
	verboton('banexavisa59idarchivo', 'none');
	window.document.frmedita.visa59tipoarchivo.value = 1;
	window.document.frmedita.visa59descripcion.value = '';
	window.document.frmedita.visa59idusuario.value = 0;
	window.document.frmedita.visa59idusuario_td.value = window.document.frmedita.stipodoc.value;
	window.document.frmedita.visa59idusuario_doc.value = '';
	document.getElementById('div_visa59idusuario').innerHTML = '';
	//ter_traerxid('visa59idusuario', window.document.frmedita.idusuario.value);
	verboton('belimina2959', 'none');
}

function eliminaf2959() {
	if (window.document.frmedita.visa59id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Evidencias?', () => {
			params = paramsf2959();
			params[1] = window.document.frmedita.visa57id.value;
			params[2] = window.document.frmedita.visa59consec.value;
			params[3] = window.document.frmedita.visa59id.value;
			//params[12] = window.document.frmedita.p1_2959.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2959_Eliminar(params);
		});
	}
}

function revisaf2959() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa57id.value;
	params[2] = window.document.frmedita.visa59consec.value;
	params[3] = window.document.frmedita.visa59id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2959_Traer(params);
	}
}

function cargadatof2959(llave1) {
	window.document.frmedita.visa59consec.value = String(llave1);
	revisaf2959();
}

function cargaridf2959(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2959_Traer(params);
	expandepanel(2959, 'block', 0);
}

function paginarf2959() {
	params = paramsf2959();
	document.getElementById('div_f2959detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia"><?php echo $ETI['msg_procesando_datos']; ?></div></div><input id="paginaf2959" name="paginaf2959" type="hidden" value="' + params[101] + '" /><input id="lppf2959" name="lppf2959" type="hidden" value="' + params[102] + '" />';
	xajax_f2959_HtmlTabla(params);
}

function imprime2959() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2959.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2959.value;
	window.document.frmlista.nombrearchivo.value = 'Evidencias';
	window.document.frmlista.submit();
}

