// JavaScript Document
// --- © Omar Augusto Bautista Mora - UNAD - 2025
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// ---  Anotaciones FAV
// --- Modelo Versión 1.0 lunes, 24 de noviembre de 2025
function limpia_saiu76idarchivo() {
	window.document.frmedita.saiu76idorigen.value = 0;
	window.document.frmedita.saiu76idarchivo.value = 0;
	let da_Archivo = document.getElementById('div_saiu76idarchivo');
	da_Archivo.innerHTML = '&nbsp;';
	verboton('beliminasaiu76idarchivo', 'none');
	//paginarf0000();
}
function carga_saiu76idarchivo() {
	window.document.frmedita.iscroll.value = window.pageYOffset;
	window.document.frmedita.div96v1.value = '';
	window.document.frmedita.div96v2.value = '';
	window.document.frmedita.div96v3.value = '';
	let saiu76id = window.document.frmedita.saiu76id.value;
	let agno = window.document.frmedita.saiu73agno.value;
	document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_3073.value + ' - Cargar archivo</h2>';
	document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="framearchivodis.php?ref=3076&id=' + saiu76id + '&tabla=_' + agno + '" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
}
function eliminasaiu76idarchivo() {
	let did = window.document.frmedita.saiu76id;
	let agno = window.document.frmedita.saiu73agno.value;
	let mes = window.document.frmedita.saiu73mes.value;
	ModalConfirmV2('Esta seguro de eliminar el archivo?', () => {
		xajax_elimina_archivo_saiu76idarchivo(did.value, agno, mes);
		//paginarf0000();
	});	
}
function guardaf3076() {
	let params = new Array();
	let valores = new Array();
	valores[1] = window.document.frmedita.saiu73id.value;
	valores[2] = window.document.frmedita.saiu76consec.value;
	valores[3] = window.document.frmedita.saiu76id.value;
	valores[4] = window.document.frmedita.saiu76anotacion.value;
	valores[5] = window.document.frmedita.saiu76visible.value;
	valores[9] = window.document.frmedita.saiu76idusuario.value;
	valores[10] = window.document.frmedita.saiu76fecha.value;
	valores[11] = window.document.frmedita.saiu76hora.value;
	valores[12] = window.document.frmedita.saiu76minuto.value;
	params[0] = window.document.frmedita.saiu73id.value;
	params[97] = window.document.frmedita.saiu73agno.value;
	params[98] = window.document.frmedita.saiu73mes.value;
	//params[1]=window.document.frmedita.p1_3076.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf3076.value;
	params[102] = window.document.frmedita.lppf3076.value;
	xajax_f3076_Guardar(valores, params);
}
function limpiaf3076() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f3076_PintarLlaves(params);
	window.document.frmedita.saiu76anotacion.value = '';
	window.document.frmedita.saiu76visible.value = 0;
	limpia_saiu76idarchivo();
	verboton('banexasaiu76idarchivo', 'none');
	window.document.frmedita.saiu76idusuario.value = 0;
	window.document.frmedita.saiu76idusuario_td.value = window.document.frmedita.stipodoc.value;
	window.document.frmedita.saiu76idusuario_doc.value = '';
	document.getElementById('div_saiu76idusuario').innerHTML = '';
	ter_traerxid('saiu76idusuario', window.document.frmedita.idusuario.value);
	// fecha_AsignarNum('saiu76fecha', iFechaBaseNum);
	verboton('belimina3076', 'none');
}
function eliminaf3076() {
	let params = new Array();
	params[0] = window.document.frmedita.saiu73id.value;
	params[1] = window.document.frmedita.saiu73id.value;
	params[2] = window.document.frmedita.saiu76consec.value;
	params[3] = window.document.frmedita.saiu76id.value;
	params[9] = window.document.frmedita.saiu76idusuario.value;
	//params[14]=window.document.frmedita.p1_3076.value;
	params[97] = window.document.frmedita.saiu73agno.value;
	params[98] = window.document.frmedita.saiu73mes.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf3076.value;
	params[102] = window.document.frmedita.lppf3076.value;
	if (window.document.frmedita.saiu76id.value != '') {
		ModalConfirmV2('Esta seguro de eliminar el dato?', () => {
			xajax_f3076_Eliminar(params);
		});		
	}
}
function revisaf3076() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.saiu73id.value;
	params[2] = window.document.frmedita.saiu76consec.value;
	params[3] = window.document.frmedita.saiu76id.value;
	if ((params[2] != '')) {
		xajax_f3076_Traer(params);
	}
}
function cargadatof3076(llave1) {
	window.document.frmedita.saiu76consec.value = String(llave1);
	revisaf3076();
}
function cargaridf3076(llave1) {
	let params = new Array();
	params[0] = 2;
	params[97] = window.document.frmedita.saiu73agno.value;
	params[98] = window.document.frmedita.saiu73mes.value;
	params[103] = llave1;
	xajax_f3076_Traer(params);
	expandepanel(3076, 'block', 0);
}
function paginarf3076() {
	let params = new Array();
	params[0] = window.document.frmedita.saiu73id.value;
	params[97] = window.document.frmedita.saiu73agno.value;
	params[98] = window.document.frmedita.saiu73mes.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf3076.value;
	params[102] = window.document.frmedita.lppf3076.value;
	//params[103]=window.document.frmedita.bnombre3076.value;
	//params[104]=window.document.frmedita.blistar3076.value;
	document.getElementById('div_f3076detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3076" name="paginaf3076" type="hidden" value="' + params[101] + '" /><input id="lppf3076" name="lppf3076" type="hidden" value="' + params[102] + '" />';
	xajax_f3076_HtmlTabla(params);
}
function imprime3076() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_3076.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_3076.value;
	window.document.frmlista.nombrearchivo.value = 'Anotaciones';
	window.document.frmlista.submit();
}
