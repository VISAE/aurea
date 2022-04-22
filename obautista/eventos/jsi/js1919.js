// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Grupos de preguntas
// --- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
function guardaf1919(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even16id.value;
	valores[2]=window.document.frmedita.even19consec.value;
	valores[3]=window.document.frmedita.even19id.value;
	valores[4]=window.document.frmedita.even19nombre.value;
	params[0]=window.document.frmedita.even16id.value;
	//params[1]=window.document.frmedita.p1_1919.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1919.value;
	params[102]=window.document.frmedita.lppf1919.value;
	xajax_f1919_Guardar(valores, params);
	}
function limpiaf1919(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1919_PintarLlaves(params);
	window.document.frmedita.even19nombre.value='';
	verboton('belimina1919','none');
	}
function eliminaf1919(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even19consec.value;
	params[3]=window.document.frmedita.even19id.value;
	//params[6]=window.document.frmedita.p1_1919.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1919.value;
	params[102]=window.document.frmedita.lppf1919.value;
	if (window.document.frmedita.even19id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1919_Eliminar(params);
			}
		}
	}
function revisaf1919(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even19consec.value;
	params[3]=window.document.frmedita.even19id.value;
	if ((params[2]!='')){
		xajax_f1919_Traer(params);
		}
	}
function cargadatof1919(llave1){
	window.document.frmedita.even19consec.value=String(llave1);
	revisaf1919();
	}
function cargaridf1919(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1919_Traer(params);
	expandepanel(1919,'block',0);
	}
function paginarf1919(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1919.value;
	params[102]=window.document.frmedita.lppf1919.value;
	//params[103]=window.document.frmedita.bnombre1919.value;
	//params[104]=window.document.frmedita.blistar1919.value;
	xajax_f1919_HtmlTabla(params);
	}
function imprime1919(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1919.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1919.value;
	window.document.frmlista.nombrearchivo.value='Grupos de preguntas';
	window.document.frmlista.submit();
	}
