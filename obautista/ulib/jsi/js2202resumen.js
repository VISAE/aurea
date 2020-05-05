// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Resumen de estudiantes
// --- Modelo Versión 2.23.6 Friday, October 11, 2019
function guardaf2202(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.core09id.value;
	valores[2]=window.document.frmedita.core01idtercero.value;
	valores[3]=window.document.frmedita.core01id.value;
	valores[4]=window.document.frmedita.core01idestado.value;
	params[0]=window.document.frmedita.core09id.value;
	//params[1]=window.document.frmedita.p1_2202.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2202.value;
	params[102]=window.document.frmedita.lppf2202.value;
	xajax_f2202_Guardar(valores, params);
	}
function limpiaf2202(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2202_PintarLlaves(params);
	window.document.frmedita.core01idestado.value='';
	verboton('belimina2202','none');
	}
function eliminaf2202(){
	var params=new Array();
	params[0]=window.document.frmedita.core09id.value;
	params[1]=window.document.frmedita.core09id.value;
	params[2]=window.document.frmedita.core01idtercero.value;
	params[3]=window.document.frmedita.core01id.value;
	//params[6]=window.document.frmedita.p1_2202.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2202.value;
	params[102]=window.document.frmedita.lppf2202.value;
	if (window.document.frmedita.core01id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f2202_Eliminar(params);
			}
		}
	}
function revisaf2202(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.core09id.value;
	params[2]=window.document.frmedita.core01idtercero.value;
	params[3]=window.document.frmedita.core01id.value;
	if ((params[2]!='')){
		xajax_f2202_Traer(params);
		}
	}
function cargadatof2202(llave1){
	window.document.frmedita.core01idtercero.value=String(llave1);
	revisaf2202();
	}
function cargaridf2202(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2202_Traer(params);
	expandepanel(2202,'block',0);
	}
function paginarf2202(){
	var params=new Array();
	params[0]=window.document.frmedita.core09id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2202.value;
	params[102]=window.document.frmedita.lppf2202.value;
	//params[103]=window.document.frmedita.bnombre2202.value;
	//params[104]=window.document.frmedita.blistar2202.value;
	document.getElementById('div_f2202detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2202" name="paginaf2202" type="hidden" value="'+params[101]+'" /><input id="lppf2202" name="lppf2202" type="hidden" value="'+params[102]+'" />';
	xajax_f2202_HtmlTabla(params);
	}
function imprime2202(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2202.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2202.value;
	window.document.frmlista.nombrearchivo.value='Resumen de estudiantes';
	window.document.frmlista.submit();
	}
