// JavaScript Document
// --- © Juan David Avellaneda Molina - UNAD - 2026 ---
// --- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Compromisos
// --- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
function paramsf2962() {
	let params = new Array();
	params[0] = window.document.frmedita.visa57id.value;
	//params[1] = window.document.frmedita.p1_2962.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2962.value;
	params[102] = window.document.frmedita.lppf2962.value;
	//params[103] = window.document.frmedita.bnombre2962.value;
	//params[104] = window.document.frmedita.blistar2962.value;
	return params;
}
function guardaf2962() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa57id.value;
	valores[2] = window.document.frmedita.visa62consec.value;
	valores[3] = window.document.frmedita.visa62id.value;
	valores[4] = window.document.frmedita.visa62descripcion.value;
	valores[5] = window.document.frmedita.visa62idresponsable.value;
	valores[6] = window.document.frmedita.visa62fechalimite.value;
	valores[7] = window.document.frmedita.visa62estado.value;
	valores[8] = window.document.frmedita.visa62fechacumple.value;
	valores[9] = window.document.frmedita.visa62observaciones.value;
	valores[10] = window.document.frmedita.visa62fecharegistro.value;
	params = paramsf2962();
	xajax_f2962_Guardar(valores, params);
}
function limpiaf2962() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa57id.value;
	xajax_f2962_PintarLlaves(params);
	window.document.frmedita.visa62descripcion.value = '';
	window.document.frmedita.visa62idresponsable.value = 0;
	window.document.frmedita.visa62idresponsable_td.value = window.document.frmedita.stipodoc.value;
	window.document.frmedita.visa62idresponsable_doc.value = '';
	document.getElementById('div_visa62idresponsable').innerHTML = '';
	//ter_traerxid('visa62idresponsable', window.document.frmedita.idusuario.value);
	fecha_AsignarNum('visa62fechalimite', iFechaBaseNum);
	window.document.frmedita.visa62estado.value = '';
	fecha_AsignarNum('visa62fechacumple', iFechaBaseNum);
	window.document.frmedita.visa62observaciones.value = '';
	verboton('belimina2962', 'none');
}

function eliminaf2962() {
	if (window.document.frmedita.visa62id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Compromisos?', () => {
			params = paramsf2962();
			params[1] = window.document.frmedita.visa57id.value;
			params[2] = window.document.frmedita.visa62consec.value;
			params[3] = window.document.frmedita.visa62id.value;
			//params[12] = window.document.frmedita.p1_2962.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2962_Eliminar(params);
		});
	}
}

function revisaf2962() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa57id.value;
	params[2] = window.document.frmedita.visa62consec.value;
	params[3] = window.document.frmedita.visa62id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2962_Traer(params);
	}
}

function cargadatof2962(llave1) {
	window.document.frmedita.visa62consec.value = String(llave1);
	revisaf2962();
}

function cargaridf2962(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2962_Traer(params);
	expandepanel(2962, 'block', 0);
}

function paginarf2962() {
	params = paramsf2962();
	document.getElementById('div_f2962detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia"><?php echo $ETI['msg_procesando_datos']; ?></div></div><input id="paginaf2962" name="paginaf2962" type="hidden" value="' + params[101] + '" /><input id="lppf2962" name="lppf2962" type="hidden" value="' + params[102] + '" />';
	xajax_f2962_HtmlTabla(params);
}

function imprime2962() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2962.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2962.value;
	window.document.frmlista.nombrearchivo.value = 'Compromisos';
	window.document.frmlista.submit();
}

