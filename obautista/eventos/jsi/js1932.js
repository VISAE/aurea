// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Roles que aplican
// --- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
function guardaf1932(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even16id.value;
	valores[2]=window.document.frmedita.even32idrol.value;
	valores[3]=window.document.frmedita.even32id.value;
	valores[4]=window.document.frmedita.even32activo.value;
	params[0]=window.document.frmedita.even16id.value;
	//params[1]=window.document.frmedita.p1_1932.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1932.value;
	params[102]=window.document.frmedita.lppf1932.value;
	xajax_f1932_Guardar(valores, params);
	}
function limpiaf1932(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1932_PintarLlaves(params);
	window.document.frmedita.even32activo.value='S';
	verboton('belimina1932','none');
	}
function eliminaf1932(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even32idrol.value;
	params[3]=window.document.frmedita.even32id.value;
	//params[6]=window.document.frmedita.p1_1932.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1932.value;
	params[102]=window.document.frmedita.lppf1932.value;
	if (window.document.frmedita.even32id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1932_Eliminar(params);
			}
		}
	}
function revisaf1932(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even32idrol.value;
	params[3]=window.document.frmedita.even32id.value;
	if ((params[2]!='')){
		xajax_f1932_Traer(params);
		}
	}
function cargadatof1932(llave1){
	window.document.frmedita.even32idrol.value=String(llave1);
	revisaf1932();
	}
function cargaridf1932(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1932_Traer(params);
	expandepanel(1932,'block',0);
	}
function paginarf1932(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1932.value;
	params[102]=window.document.frmedita.lppf1932.value;
	//params[103]=window.document.frmedita.bnombre1932.value;
	//params[104]=window.document.frmedita.blistar1932.value;
	xajax_f1932_HtmlTabla(params);
	}
function imprime1932(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1932.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1932.value;
	window.document.frmlista.nombrearchivo.value='Roles que aplican';
	window.document.frmlista.submit();
	}
