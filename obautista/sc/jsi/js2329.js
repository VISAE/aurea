// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Asistentes
// --- Modelo Versión 2.23.6 Friday, September 20, 2019
function guardaf2329(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.cara28id.value;
	valores[2]=window.document.frmedita.cara29idtercero.value;
	valores[3]=window.document.frmedita.cara29id.value;
	valores[4]=window.document.frmedita.cara29estado.value;
	params[0]=window.document.frmedita.cara28id.value;
	//params[1]=window.document.frmedita.p1_2329.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2329.value;
	params[102]=window.document.frmedita.lppf2329.value;
	xajax_f2329_Guardar(valores, params);
	}
function limpiaf2329(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2329_PintarLlaves(params);
	window.document.frmedita.cara29estado.value='';
	verboton('belimina2329','none');
	}
function eliminaf2329(){
	var params=new Array();
	params[0]=window.document.frmedita.cara28id.value;
	params[1]=window.document.frmedita.cara28id.value;
	params[2]=window.document.frmedita.cara29idtercero.value;
	params[3]=window.document.frmedita.cara29id.value;
	//params[6]=window.document.frmedita.p1_2329.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2329.value;
	params[102]=window.document.frmedita.lppf2329.value;
	if (window.document.frmedita.cara29id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f2329_Eliminar(params);
			}
		}
	}
function revisaf2329(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.cara28id.value;
	params[2]=window.document.frmedita.cara29idtercero.value;
	params[3]=window.document.frmedita.cara29id.value;
	if ((params[2]!='')){
		xajax_f2329_Traer(params);
		}
	}
function cargadatof2329(llave1){
	window.document.frmedita.cara29idtercero.value=String(llave1);
	revisaf2329();
	}
function cargaridf2329(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2329_Traer(params);
	expandepanel(2329,'block',0);
	}
function paginarf2329(){
	var params=new Array();
	params[0]=window.document.frmedita.cara28id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2329.value;
	params[102]=window.document.frmedita.lppf2329.value;
	//params[103]=window.document.frmedita.bnombre2329.value;
	//params[104]=window.document.frmedita.blistar2329.value;
	document.getElementById('div_f2329detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2329" name="paginaf2329" type="hidden" value="'+params[101]+'" /><input id="lppf2329" name="lppf2329" type="hidden" value="'+params[102]+'" />';
	xajax_f2329_HtmlTabla(params);
	}
function imprime2329(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2329.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2329.value;
	window.document.frmlista.nombrearchivo.value='Asistentes';
	window.document.frmlista.submit();
	}
