// JavaScript Document
// --- © Juan David Avellaneda Molina - UNAD - 2026 ---
// --- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Solicitud de apoyo
// --- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
function paramsf2963() {
	let params = new Array();
	params[0] = window.document.frmedita.visa57id.value;
	//params[1] = window.document.frmedita.p1_2963.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2963.value;
	params[102] = window.document.frmedita.lppf2963.value;
	//params[103] = window.document.frmedita.bnombre2963.value;
	//params[104] = window.document.frmedita.blistar2963.value;
	return params;
}
function guardaf2963() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa57id.value;
	valores[2] = window.document.frmedita.visa63consec.value;
	valores[3] = window.document.frmedita.visa63id.value;
	valores[4] = window.document.frmedita.visa63descripcion.value;
	valores[5] = window.document.frmedita.visa63idcolaborador.value;
	valores[6] = window.document.frmedita.visa63estado.value;
	valores[7] = window.document.frmedita.visa63fechasolicitud.value;
	valores[8] = window.document.frmedita.visa63fecharespuesta.value;
	valores[9] = window.document.frmedita.visa63observaciones.value;
	params = paramsf2963();
	xajax_f2963_Guardar(valores, params);
}
function limpiaf2963() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa57id.value;
	xajax_f2963_PintarLlaves(params);
	window.document.frmedita.visa63descripcion.value = '';
	window.document.frmedita.visa63idcolaborador.value = 0;
	window.document.frmedita.visa63idcolaborador_td.value = window.document.frmedita.stipodoc.value;
	window.document.frmedita.visa63idcolaborador_doc.value = '';
	document.getElementById('div_visa63idcolaborador').innerHTML = '';
	//ter_traerxid('visa63idcolaborador', window.document.frmedita.idusuario.value);
	window.document.frmedita.visa63estado.value = '';
	fecha_AsignarNum('visa63fechasolicitud', iFechaBaseNum);
	fecha_AsignarNum('visa63fecharespuesta', iFechaBaseNum);
	window.document.frmedita.visa63observaciones.value = '';
	verboton('belimina2963', 'none');
}

function eliminaf2963() {
	if (window.document.frmedita.visa63id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Solicitud de apoyo?', () => {
			params = paramsf2963();
			params[1] = window.document.frmedita.visa57id.value;
			params[2] = window.document.frmedita.visa63consec.value;
			params[3] = window.document.frmedita.visa63id.value;
			//params[11] = window.document.frmedita.p1_2963.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2963_Eliminar(params);
		});
	}
}

function revisaf2963() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa57id.value;
	params[2] = window.document.frmedita.visa63consec.value;
	params[3] = window.document.frmedita.visa63id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2963_Traer(params);
	}
}

function cargadatof2963(llave1) {
	window.document.frmedita.visa63consec.value = String(llave1);
	revisaf2963();
}

function cargaridf2963(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2963_Traer(params);
	expandepanel(2963, 'block', 0);
}

function paginarf2963() {
	params = paramsf2963();
	document.getElementById('div_f2963detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia"><?php echo $ETI['msg_procesando_datos']; ?></div></div><input id="paginaf2963" name="paginaf2963" type="hidden" value="' + params[101] + '" /><input id="lppf2963" name="lppf2963" type="hidden" value="' + params[102] + '" />';
	xajax_f2963_HtmlTabla(params);
}

function imprime2963() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2963.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2963.value;
	window.document.frmlista.nombrearchivo.value = 'Solicitud de apoyo';
	window.document.frmlista.submit();
}

