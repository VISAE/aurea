// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2017 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Propietarios
// --- Modelo Versión 2.16.3 miércoles, 04 de enero de 2017
function guardaf1940(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even16id.value;
	valores[2]=window.document.frmedita.even40idpropietario.value;
	valores[3]=window.document.frmedita.even40id.value;
	valores[4]=window.document.frmedita.even40activo.value;
	params[0]=window.document.frmedita.even16id.value;
	//params[1]=window.document.frmedita.p1_1940.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1940.value;
	params[102]=window.document.frmedita.lppf1940.value;
	xajax_f1940_Guardar(valores, params);
	}
function limpiaf1940(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1940_PintarLlaves(params);
	window.document.frmedita.even40activo.value='S';
	verboton('belimina1940','none');
	}
function eliminaf1940(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even40idpropietario.value;
	params[3]=window.document.frmedita.even40id.value;
	//params[6]=window.document.frmedita.p1_1940.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1940.value;
	params[102]=window.document.frmedita.lppf1940.value;
	if (window.document.frmedita.even40id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1940_Eliminar(params);
			}
		}
	}
function revisaf1940(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even40idpropietario.value;
	params[3]=window.document.frmedita.even40id.value;
	if ((params[2]!=0)){
		xajax_f1940_Traer(params);
		}
	}
function cargadatof1940(llave1){
	window.document.frmedita.even40idpropietario.value=String(llave1);
	revisaf1940();
	}
function cargaridf1940(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1940_Traer(params);
	expandepanel(1940,'block',0);
	}
function paginarf1940(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1940.value;
	params[102]=window.document.frmedita.lppf1940.value;
	//params[103]=window.document.frmedita.bnombre1940.value;
	//params[104]=window.document.frmedita.blistar1940.value;
	xajax_f1940_HtmlTabla(params);
	}
function imprime1940(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1940.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1940.value;
	window.document.frmlista.nombrearchivo.value='Propietarios';
	window.document.frmlista.submit();
	}
