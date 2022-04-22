// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Periodos que aplican
// --- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
function guardaf1924(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even16id.value;
	valores[2]=window.document.frmedita.even24idperaca.value;
	valores[3]=window.document.frmedita.even24id.value;
	valores[4]=window.document.frmedita.even24fechainicial.value;
	valores[5]=window.document.frmedita.even24fechafinal.value;
	params[0]=window.document.frmedita.even16id.value;
	//params[1]=window.document.frmedita.p1_1924.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1924.value;
	params[102]=window.document.frmedita.lppf1924.value;
	xajax_f1924_Guardar(valores, params);
	}
function limpiaf1924(){
	var sfbase=window.document.frmedita.shoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1924_PintarLlaves(params);
	fecha_asignar('even24fechainicial',sfbase);
	fecha_asignar('even24fechafinal',sfbase);
	verboton('belimina1924','none');
	}
function eliminaf1924(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even24idperaca.value;
	params[3]=window.document.frmedita.even24id.value;
	//params[7]=window.document.frmedita.p1_1924.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1924.value;
	params[102]=window.document.frmedita.lppf1924.value;
	if (window.document.frmedita.even24id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1924_Eliminar(params);
			}
		}
	}
function revisaf1924(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even24idperaca.value;
	params[3]=window.document.frmedita.even24id.value;
	if ((params[2]!='')){
		xajax_f1924_Traer(params);
		}
	}
function cargadatof1924(llave1){
	window.document.frmedita.even24idperaca.value=String(llave1);
	revisaf1924();
	}
function cargaridf1924(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1924_Traer(params);
	expandepanel(1924,'block',0);
	}
function paginarf1924(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1924.value;
	params[102]=window.document.frmedita.lppf1924.value;
	//params[103]=window.document.frmedita.bnombre1924.value;
	//params[104]=window.document.frmedita.blistar1924.value;
	xajax_f1924_HtmlTabla(params);
	}
function imprime1924(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1924.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1924.value;
	window.document.frmlista.nombrearchivo.value='Periodos que aplican';
	window.document.frmlista.submit();
	}
