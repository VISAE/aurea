// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Respuestas
// --- Modelo Versión 2.22.1 jueves, 5 de julio de 2018
function guardaf2309(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.cara08id.value;
	valores[2]=window.document.frmedita.cara09consec.value;
	valores[3]=window.document.frmedita.cara09id.value;
	valores[4]=window.document.frmedita.cara09valor.value;
	valores[5]=window.document.frmedita.cara09contenido.value;
	params[0]=window.document.frmedita.cara08id.value;
	//params[1]=window.document.frmedita.p1_2309.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2309.value;
	params[102]=window.document.frmedita.lppf2309.value;
	xajax_f2309_Guardar(valores, params);
	}
function limpiaf2309(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2309_PintarLlaves(params);
	window.document.frmedita.cara09valor.value='';
	window.document.frmedita.cara09contenido.value='';
	verboton('belimina2309','none');
	}
function eliminaf2309(){
	var params=new Array();
	params[0]=window.document.frmedita.cara08id.value;
	params[1]=window.document.frmedita.cara08id.value;
	params[2]=window.document.frmedita.cara09consec.value;
	params[3]=window.document.frmedita.cara09id.value;
	//params[7]=window.document.frmedita.p1_2309.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2309.value;
	params[102]=window.document.frmedita.lppf2309.value;
	if (window.document.frmedita.cara09id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f2309_Eliminar(params);
			}
		}
	}
function revisaf2309(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.cara08id.value;
	params[2]=window.document.frmedita.cara09consec.value;
	params[3]=window.document.frmedita.cara09id.value;
	if ((params[2]!='')){
		xajax_f2309_Traer(params);
		}
	}
function cargadatof2309(llave1){
	window.document.frmedita.cara09consec.value=String(llave1);
	revisaf2309();
	}
function cargaridf2309(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2309_Traer(params);
	expandepanel(2309,'block',0);
	}
function paginarf2309(){
	var params=new Array();
	params[0]=window.document.frmedita.cara08id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2309.value;
	params[102]=window.document.frmedita.lppf2309.value;
	//params[103]=window.document.frmedita.bnombre2309.value;
	//params[104]=window.document.frmedita.blistar2309.value;
	document.getElementById('div_f2309detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2309" name="paginaf2309" type="hidden" value="'+params[101]+'" /><input id="lppf2309" name="lppf2309" type="hidden" value="'+params[102]+'" />';
	xajax_f2309_HtmlTabla(params);
	}
function imprime2309(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2309.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2309.value;
	window.document.frmlista.nombrearchivo.value='Respuestas';
	window.document.frmlista.submit();
	}
