// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anotaciones
// --- Modelo Versión 2.25.5 jueves, 13 de agosto de 2020
function guardaf3030(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu28id.value;
	valores[2]=window.document.frmedita.saiu30consec.value;
	valores[3]=window.document.frmedita.saiu30id.value;
	valores[4]=window.document.frmedita.saiu30visiblealinteresado.value;
	valores[5]=window.document.frmedita.saiu30anotacion.value;
	valores[6]=window.document.frmedita.saiu30idusuario.value;
	valores[7]=window.document.frmedita.saiu30fecha.value;
	valores[8]=window.document.frmedita.saiu30hora.value;
	valores[9]=window.document.frmedita.saiu30minuto.value;
	valores[98]=window.document.frmedita.saiu28agno.value;
	params[0]=window.document.frmedita.saiu28id.value;
	//params[1]=window.document.frmedita.p1_3030.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3030.value;
	params[102]=window.document.frmedita.lppf3030.value;
	xajax_f3030_Guardar(valores, params);
	}
function limpiaf3030(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3030_PintarLlaves(params);
	window.document.frmedita.saiu30visiblealinteresado.value=0;
	window.document.frmedita.saiu30anotacion.value='';
	verboton('belimina3030','none');
	}
function eliminaf3030(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28id.value;
	params[1]=window.document.frmedita.saiu28id.value;
	params[2]=window.document.frmedita.saiu30consec.value;
	params[3]=window.document.frmedita.saiu30id.value;
	//params[11]=window.document.frmedita.p1_3030.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3030.value;
	params[102]=window.document.frmedita.lppf3030.value;
	if (window.document.frmedita.saiu30id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3030_Eliminar(params);
			}
		}
	}
function revisaf3030(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu28id.value;
	params[2]=window.document.frmedita.saiu30consec.value;
	params[3]=window.document.frmedita.saiu30id.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	if ((params[2]!='')){
		xajax_f3030_Traer(params);
		}
	}
function cargadatof3030(llave1){
	window.document.frmedita.saiu30consec.value=String(llave1);
	revisaf3030();
	}
function cargaridf3030(llave1){
	var params=new Array();
	params[0]=2;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[103]=llave1;
	xajax_f3030_Traer(params);
	expandepanel(3030,'block',0);
	}
function paginarf3030(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28id.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3030.value;
	params[102]=window.document.frmedita.lppf3030.value;
	//params[103]=window.document.frmedita.bnombre3030.value;
	//params[104]=window.document.frmedita.blistar3030.value;
	document.getElementById('div_f3030detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3030" name="paginaf3030" type="hidden" value="'+params[101]+'" /><input id="lppf3030" name="lppf3030" type="hidden" value="'+params[102]+'" />';
	xajax_f3030_HtmlTabla(params);
	}
function imprime3030(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3030.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3030.value;
	window.document.frmlista.nombrearchivo.value='Anotaciones';
	window.document.frmlista.submit();
	}
