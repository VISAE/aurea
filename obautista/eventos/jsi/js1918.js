// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
// --- Modelo Versión 2.22.6b miércoles, 28 de noviembre de 2018
function carga_combo_even18valorcondiciona(){
	var params=new Array();
	params[0]=window.document.frmedita.even18idpregcondiciona.value;
	xajax_f1918_Comboeven18valorcondiciona(params);
	}
function guardaf1918(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even16id.value;
	valores[2]=window.document.frmedita.even18consec.value;
	valores[3]=window.document.frmedita.even18id.value;
	valores[4]=window.document.frmedita.even18idgrupo.value;
	valores[5]=window.document.frmedita.even18pregunta.value;
	valores[6]=window.document.frmedita.even18tiporespuesta.value;
	valores[7]=window.document.frmedita.even18opcional.value;
	valores[8]=window.document.frmedita.even18concomentario.value;
	/*
	valores[9]=window.document.frmedita.even18rpta0.value;
	valores[10]=window.document.frmedita.even18rpta1.value;
	valores[11]=window.document.frmedita.even18rpta2.value;
	valores[12]=window.document.frmedita.even18rpta3.value;
	valores[13]=window.document.frmedita.even18rpta4.value;
	valores[14]=window.document.frmedita.even18rpta5.value;
	valores[15]=window.document.frmedita.even18rpta6.value;
	valores[16]=window.document.frmedita.even18rpta7.value;
	valores[17]=window.document.frmedita.even18rpta8.value;
	valores[18]=window.document.frmedita.even18rpta9.value;
	*/
	valores[19]=window.document.frmedita.even18orden.value;
	valores[20]=window.document.frmedita.even18divergente.value;
	valores[21]=window.document.frmedita.even18rptatotal.value;
	valores[22]=window.document.frmedita.even18idpregcondiciona.value;
	valores[23]=window.document.frmedita.even18valorcondiciona.value;
	valores[24]=window.document.frmedita.even18url.value;
	params[0]=window.document.frmedita.even16id.value;
	//params[1]=window.document.frmedita.p1_1918.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1918.value;
	params[102]=window.document.frmedita.lppf1918.value;
	xajax_f1918_Guardar(valores, params);
	}
function limpiaf1918(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	params[1]=window.document.frmedita.even16id.value;
	xajax_f1918_PintarLlaves(params);
	window.document.frmedita.even18pregunta.value='';
	window.document.frmedita.even18opcional.value='N';
	window.document.frmedita.even18concomentario.value='N';
	window.document.frmedita.even18orden.value='';
	window.document.frmedita.even18divergente.value='N';
	window.document.frmedita.even18url.value='';
	verboton('belimina1918','none');
	}
function eliminaf1918(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even18consec.value;
	params[3]=window.document.frmedita.even18id.value;
	//params[20]=window.document.frmedita.p1_1918.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1918.value;
	params[102]=window.document.frmedita.lppf1918.value;
	if (window.document.frmedita.even18id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1918_Eliminar(params);
			}
		}
	}
function revisaf1918(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even18consec.value;
	params[3]=window.document.frmedita.even18id.value;
	if ((params[2]!='')){
		xajax_f1918_Traer(params);
		}
	}
function cargadatof1918(llave1){
	window.document.frmedita.even18consec.value=String(llave1);
	revisaf1918();
	}
function cargaridf1918(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1918_Traer(params);
	expandepanel(1918,'block',0);
	}
function paginarf1918(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1918.value;
	params[102]=window.document.frmedita.lppf1918.value;
	//params[103]=window.document.frmedita.bnombre1918.value;
	//params[104]=window.document.frmedita.blistar1918.value;
	xajax_f1918_HtmlTabla(params);
	}
function imprime1918(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1918.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1918.value;
	window.document.frmlista.nombrearchivo.value='Preguntas';
	window.document.frmlista.submit();
	}
