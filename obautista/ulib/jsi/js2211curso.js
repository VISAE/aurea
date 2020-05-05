// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Plan de estudios
// --- Modelo Versión 2.25.0 martes, 17 de marzo de 2020
function cod_core11idversionprograma(){
	var dcod=window.document.frmedita.core11idversionprograma_cod.value.trim();
	window.document.frmedita.core11idversionprograma.value=0;
	if (dcod!=''){
		var params=new Array();
		params[0]=dcod;
		params[1]='core11idversionprograma';
		params[2]='div_core11idversionprograma';
		params[9]=window.document.frmedita.debug.value;
		xajax_f2211_Busqueda_core11idversionprograma(params);
		}else{
		document.getElementById('div_core11idversionprograma').innerHTML='';
		}
	}
function guardaf2211(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.unad40id.value;
	valores[2]=window.document.frmedita.core11idversionprograma.value;
	valores[3]=window.document.frmedita.core11id.value;
	valores[4]=window.document.frmedita.core11idlineaprof.value;
	valores[5]=window.document.frmedita.core11idprograma.value;
	params[0]=window.document.frmedita.unad40id.value;
	//params[1]=window.document.frmedita.p1_2211.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf2211.value;
	params[102]=window.document.frmedita.lppf2211.value;
	xajax_f2211_Guardar(valores, params);
	}
function limpiaf2211(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2211_PintarLlaves(params);
	window.document.frmedita.core11idlineaprof.value='';
	window.document.frmedita.core11idprograma.value='';
	verboton('belimina2211','none');
	}
function eliminaf2211(){
	var params=new Array();
	params[0]=window.document.frmedita.unad40id.value;
	params[1]=window.document.frmedita.unad40id.value;
	params[2]=window.document.frmedita.core11idversionprograma.value;
	params[3]=window.document.frmedita.core11id.value;
	//params[7]=window.document.frmedita.p1_2211.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf2211.value;
	params[102]=window.document.frmedita.lppf2211.value;
	if (window.document.frmedita.core11id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f2211_Eliminar(params);
			}
		}
	}
function revisaf2211(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unad40id.value;
	params[2]=window.document.frmedita.core11idversionprograma.value;
	params[3]=window.document.frmedita.core11id.value;
	if ((params[2]!='')){
		xajax_f2211_Traer(params);
		}
	}
function cargadatof2211(llave1){
	window.document.frmedita.core11idversionprograma.value=String(llave1);
	revisaf2211();
	}
function cargaridf2211(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2211_Traer(params);
	expandepanel(2211,'block',0);
	}
function paginarf2211(){
	var params=new Array();
	params[0]=window.document.frmedita.unad40id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf2211.value;
	params[102]=window.document.frmedita.lppf2211.value;
	//params[103]=window.document.frmedita.bnombre2211.value;
	//params[104]=window.document.frmedita.blistar2211.value;
	document.getElementById('div_f2211detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2211" name="paginaf2211" type="hidden" value="'+params[101]+'" /><input id="lppf2211" name="lppf2211" type="hidden" value="'+params[102]+'" />';
	xajax_f2211_HtmlTabla(params);
	}
function imprime2211(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2211.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2211.value;
	window.document.frmlista.nombrearchivo.value='Plan de estudios';
	window.document.frmlista.submit();
	}
