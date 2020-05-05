// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Recursos usados
// --- Modelo Versión 2.24.1 lunes, 24 de febrero de 2020
function guardaf1770(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.unad40id.value;
	valores[2]=window.document.frmedita.ofer70consec.value;
	valores[3]=window.document.frmedita.ofer70id.value;
	valores[4]=window.document.frmedita.ofer70idtiporecurso.value;
	valores[5]=window.document.frmedita.ofer70detalle.value;
	params[0]=window.document.frmedita.unad40id.value;
	//params[1]=window.document.frmedita.p1_1770.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf1770.value;
	params[102]=window.document.frmedita.lppf1770.value;
	xajax_f1770_Guardar(valores, params);
	}
function limpiaf1770(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1770_PintarLlaves(params);
	window.document.frmedita.ofer70idtiporecurso.value='';
	window.document.frmedita.ofer70detalle.value='';
	verboton('belimina1770','none');
	}
function eliminaf1770(){
	var params=new Array();
	params[0]=window.document.frmedita.unad40id.value;
	params[1]=window.document.frmedita.unad40id.value;
	params[2]=window.document.frmedita.ofer70consec.value;
	params[3]=window.document.frmedita.ofer70id.value;
	//params[7]=window.document.frmedita.p1_1770.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf1770.value;
	params[102]=window.document.frmedita.lppf1770.value;
	if (window.document.frmedita.ofer70id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1770_Eliminar(params);
			}
		}
	}
function revisaf1770(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unad40id.value;
	params[2]=window.document.frmedita.ofer70consec.value;
	params[3]=window.document.frmedita.ofer70id.value;
	if ((params[2]!='')){
		xajax_f1770_Traer(params);
		}
	}
function cargadatof1770(llave1){
	window.document.frmedita.ofer70consec.value=String(llave1);
	revisaf1770();
	}
function cargaridf1770(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1770_Traer(params);
	expandepanel(1770,'block',0);
	}
function paginarf1770(){
	var params=new Array();
	params[0]=window.document.frmedita.unad40id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf1770.value;
	params[102]=window.document.frmedita.lppf1770.value;
	//params[103]=window.document.frmedita.bnombre1770.value;
	//params[104]=window.document.frmedita.blistar1770.value;
	document.getElementById('div_f1770detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1770" name="paginaf1770" type="hidden" value="'+params[101]+'" /><input id="lppf1770" name="lppf1770" type="hidden" value="'+params[102]+'" />';
	xajax_f1770_HtmlTabla(params);
	}
function imprime1770(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1770.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1770.value;
	window.document.frmlista.nombrearchivo.value='Recursos usados';
	window.document.frmlista.submit();
	}
