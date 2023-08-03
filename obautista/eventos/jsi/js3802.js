// JavaScript Document
// --- © Cristhiam Dario Silva Chavez - UNAD - 2022 ---
// --- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
// ---  Jornadas
// --- Modelo Versión 2.28.4b viernes, 21 de octubre de 2022
function paramsf3802() {
	let params = new Array();
	params[0] = window.document.frmedita.cipa01id.value;
	//params[1] = window.document.frmedita.p1_3802.value;
	params[99] = window.document.frmedita.debug.value;
	params[100] = window.document.frmedita.id11.value;
	params[101] = window.document.frmedita.paginaf3802.value;
	params[102] = window.document.frmedita.lppf3802.value;
	//params[103] = window.document.frmedita.bnombre3802.value;
	params[104] = window.document.frmedita.blistar3802.value;
	return params;
}
function guardaf3802() {
	let valores = new Array();
	valores[1] = window.document.frmedita.cipa01id.value;
	valores[2] = window.document.frmedita.cipa02consec.value;
	valores[3] = window.document.frmedita.cipa02id.value;
	valores[4] = window.document.frmedita.cipa02forma.value;
	valores[5] = window.document.frmedita.cipa02lugar.value;
	valores[6] = window.document.frmedita.cipa02link.value;
	valores[7] = window.document.frmedita.cipa02fecha.value;
	valores[8] = window.document.frmedita.cipa02horaini.value;
	valores[9] = window.document.frmedita.cipa02minini.value;
	valores[10] = window.document.frmedita.cipa02horafin.value;
	valores[11] = window.document.frmedita.cipa02minfin.value;
	valores[12] = window.document.frmedita.cipa02numinscritos.value;
	valores[13] = window.document.frmedita.cipa02numparticipantes.value;
	valores[14] = window.document.frmedita.cipa02tematica.value;
	params = paramsf3802();
	xajax_f3802_Guardar(valores, params);
}
function limpiaf3802() {
	let sfbase = window.document.frmedita.shoy.value;
	let iFechaBaseNum = window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	let params = new Array();
	xajax_f3802_PintarLlaves(params);
	window.document.frmedita.cipa02forma.value = 0;
	window.document.frmedita.cipa02lugar.value = '';
	window.document.frmedita.cipa02link.value = '';
	fecha_AsignarNum('cipa02fecha', 0);
	//hora_asignar('cipa02horaini', window.document.frmedita.shora.value);
	window.document.frmedita.cipa02horaini.value = '';
	window.document.frmedita.cipa02horaini_Num.value = '';
	window.document.frmedita.cipa02horaini_Ciclo.value = 'A';
	window.document.frmedita.cipa02minini.value = '';
	//hora_asignar('cipa02horafin', window.document.frmedita.shora.value);
	window.document.frmedita.cipa02horafin.value = '';
	window.document.frmedita.cipa02horafin_Num.value = '';
	window.document.frmedita.cipa02horafin_Ciclo.value = 'A';
	window.document.frmedita.cipa02minfin.value = '';
	window.document.frmedita.cipa02tematica.value = '';
	verboton('belimina3802', 'none');
	forma02();
}
function eliminaf3802() {
	if (window.document.frmedita.cipa02id.value != '') {
		ModalConfirmV2('&iquest;Est&aacute; seguro de eliminar el dato Jornadas?', () => {
			params = paramsf3802();
			params[1] = window.document.frmedita.cipa01id.value;
			params[2] = window.document.frmedita.cipa02consec.value;
			params[3] = window.document.frmedita.cipa02id.value;
			//params[15] = window.document.frmedita.p1_3802.value;
			xajax_f3802_Eliminar(params);
		});
	}
}
function revisaf3802() {
	let params = new Array();
	params[0] = 1;
	params[1] = window.document.frmedita.cipa01id.value;
	params[2] = window.document.frmedita.cipa02consec.value;
	params[3] = window.document.frmedita.cipa02id.value;
	if ((params[2] != '')) {
		xajax_f3802_Traer(params);
	}
}
function cargadatof3802(llave1) {
	window.document.frmedita.cipa02consec.value = String(llave1);
	revisaf3802();
}
function cargaridf3802(llave1) {
	let params = new Array();
	params[0] = 2;
	params[103] = llave1;
	xajax_f3802_Traer(params);
	expandepanel(3802, 'block', 0);
}
function paginarf3802() {
	params = paramsf3802();
	document.getElementById('div_f3802detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3802" name="paginaf3802" type="hidden" value="' + params[101] + '" /><input id="lppf3802" name="lppf3802" type="hidden" value="' + params[102] + '" />';
	xajax_f3802_HtmlTabla(params);
}
function imprime3802() {
	window.document.frmlista.consulta.value = window.document.frmedita.consulta_3802.value;
	window.document.frmlista.titulos.value = window.document.frmedita.titulos_3802.value;
	window.document.frmlista.nombrearchivo.value = 'Jornadas';
	window.document.frmlista.submit();
}
