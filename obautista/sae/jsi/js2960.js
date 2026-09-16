// JavaScript Document
// --- © Juan David Avellaneda Molina - UNAD - 2026 ---
// --- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Reprogramación
// --- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
function paramsf2960() {
	let params = new Array();
	params[0] = window.document.frmedita.visa57id.value;
	//params[1] = window.document.frmedita.p1_2960.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf2960.value;
	params[102] = window.document.frmedita.lppf2960.value;
	//params[103] = window.document.frmedita.bnombre2960.value;
	//params[104] = window.document.frmedita.blistar2960.value;
	return params;
}
function guardaf2960() {
	let valores = new Array();
	valores[1] = window.document.frmedita.visa57id.value;
	valores[2] = window.document.frmedita.visa60consec.value;
	valores[3] = window.document.frmedita.visa60id.value;
	valores[4] = window.document.frmedita.visa60fechareproini.value;
	valores[5] = window.document.frmedita.visa60fechareprofin.value;
	valores[6] = window.document.frmedita.visa60motivo.value;
	valores[7] = window.document.frmedita.visa60fecharegistro.value;
	valores[8] = window.document.frmedita.visa60idusuario.value;
	params = paramsf2960();
	xajax_f2960_Guardar(valores, params);
}
function limpiaf2960() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	params[1] = window.document.frmedita.visa57id.value;
	xajax_f2960_PintarLlaves(params);
	fecha_AsignarNum('visa60fechareproini', iFechaBaseNum);
	fecha_AsignarNum('visa60fechareprofin', iFechaBaseNum);
	window.document.frmedita.visa60motivo.value = '';
	window.document.frmedita.visa60idusuario.value = 0;
	window.document.frmedita.visa60idusuario_td.value = window.document.frmedita.stipodoc.value;
	window.document.frmedita.visa60idusuario_doc.value = '';
	document.getElementById('div_visa60idusuario').innerHTML = '';
	//ter_traerxid('visa60idusuario', window.document.frmedita.idusuario.value);
	verboton('belimina2960', 'none');
}

function eliminaf2960() {
	if (window.document.frmedita.visa60id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Reprogramación?', () => {
			params = paramsf2960();
			params[1] = window.document.frmedita.visa57id.value;
			params[2] = window.document.frmedita.visa60consec.value;
			params[3] = window.document.frmedita.visa60id.value;
			//params[10] = window.document.frmedita.p1_2960.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2960_Eliminar(params);
		});
	}
}

function revisaf2960() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.visa57id.value;
	params[2] = window.document.frmedita.visa60consec.value;
	params[3] = window.document.frmedita.visa60id.value;
	params[99] = window.document.frmedita.debug.value;
	if ((params[2] != '')) {
		xajax_f2960_Traer(params);
	}
}

function cargadatof2960(llave1) {
	window.document.frmedita.visa60consec.value = String(llave1);
	revisaf2960();
}

function cargaridf2960(llave1) {
	let params = new Array();
	params[0] = 2;
	params[99] = window.document.frmedita.debug.value;
	params[103] = llave1;
	xajax_f2960_Traer(params);
	expandepanel(2960, 'block', 0);
}

function paginarf2960() {
	params = paramsf2960();
	document.getElementById('div_f2960detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia"><?php echo $ETI['msg_procesando_datos']; ?></div></div><input id="paginaf2960" name="paginaf2960" type="hidden" value="' + params[101] + '" /><input id="lppf2960" name="lppf2960" type="hidden" value="' + params[102] + '" />';
	xajax_f2960_HtmlTabla(params);
}

function imprime2960() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_2960.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_2960.value;
	window.document.frmlista.nombrearchivo.value = 'Reprogramación';
	window.document.frmlista.submit();
}

