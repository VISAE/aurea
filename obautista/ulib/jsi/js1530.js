// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Cambios de responsable
// --- Modelo Versión 2.22.7 viernes, 22 de marzo de 2019
function guardaf1530(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.bita04id.value;
	valores[2]=window.document.frmedita.bita30consec.value;
	valores[3]=window.document.frmedita.bita30id.value;
	valores[4]=window.document.frmedita.bita30idresponsable.value;
	valores[5]=window.document.frmedita.bita30fechafin.value;
	valores[6]=window.document.frmedita.bita30horafin.value;
	valores[7]=window.document.frmedita.bita30nota.value;
	params[0]=window.document.frmedita.bita04id.value;
	//params[1]=window.document.frmedita.p1_1530.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1530.value;
	params[102]=window.document.frmedita.lppf1530.value;
	xajax_f1530_Guardar(valores, params);
	}
function limpiaf1530(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1530_PintarLlaves(params);
	window.document.frmedita.bita30idresponsable.value=0;
	window.document.frmedita.bita30idresponsable_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.bita30idresponsable_doc.value='';
	document.getElementById('div_bita30idresponsable').innerHTML='';
	//ter_traerxid('bita30idresponsable', window.document.frmedita.idusuario.value);
	fecha_AsignarNum('bita30fechafin', iFechaBaseNum);
	window.document.frmedita.bita30nota.value='';
	verboton('belimina1530','none');
	}
function eliminaf1530(){
	var params=new Array();
	params[0]=window.document.frmedita.bita04id.value;
	params[1]=window.document.frmedita.bita04id.value;
	params[2]=window.document.frmedita.bita30consec.value;
	params[3]=window.document.frmedita.bita30id.value;
	//params[9]=window.document.frmedita.p1_1530.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1530.value;
	params[102]=window.document.frmedita.lppf1530.value;
	if (window.document.frmedita.bita30id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1530_Eliminar(params);
			}
		}
	}
function revisaf1530(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.bita04id.value;
	params[2]=window.document.frmedita.bita30consec.value;
	params[3]=window.document.frmedita.bita30id.value;
	if ((params[2]!='')){
		xajax_f1530_Traer(params);
		}
	}
function cargadatof1530(llave1){
	window.document.frmedita.bita30consec.value=String(llave1);
	revisaf1530();
	}
function cargaridf1530(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1530_Traer(params);
	expandepanel(1530,'block',0);
	}
function paginarf1530(){
	var params=new Array();
	params[0]=window.document.frmedita.bita04id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1530.value;
	params[102]=window.document.frmedita.lppf1530.value;
	//params[103]=window.document.frmedita.bnombre1530.value;
	//params[104]=window.document.frmedita.blistar1530.value;
	document.getElementById('div_f1530detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1530" name="paginaf1530" type="hidden" value="'+params[101]+'" /><input id="lppf1530" name="lppf1530" type="hidden" value="'+params[102]+'" />';
	xajax_f1530_HtmlTabla(params);
	}
function imprime1530(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1530.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1530.value;
	window.document.frmlista.nombrearchivo.value='Cambios de responsable';
	window.document.frmlista.submit();
	}
