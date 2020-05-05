// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Fractales
// --- Modelo Versión 2.24.1 viernes, 7 de febrero de 2020
// --- Modelo Versión 2.24.1 lunes, 10 de febrero de 2020
function guardaf227(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.unae26consec.value;
	valores[2]=window.document.frmedita.unae27idzona.value;
	valores[3]=window.document.frmedita.unae27id.value;
	valores[4]=window.document.frmedita.unae27activa.value;
	valores[5]=window.document.frmedita.unae27nombre.value;
	valores[6]=window.document.frmedita.unae27fractal.value;
	valores[7]=window.document.frmedita.unae26unidadpadre.value;
	valores[8]=window.document.frmedita.unae27idresponsable.value;
	valores[9]=window.document.frmedita.unae27tituloresponsable.value;
	valores[10]=window.document.frmedita.unae27orden.value;
	params[0]=window.document.frmedita.unae26consec.value;
	//params[1]=window.document.frmedita.p1_227.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf227.value;
	params[102]=window.document.frmedita.lppf227.value;
	xajax_f227_Guardar(valores, params);
	}
function limpiaf227(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f227_PintarLlaves(params);
	window.document.frmedita.unae27activa.value='S';
	window.document.frmedita.unae27nombre.value='';
	window.document.frmedita.unae27fractal.value='N';
	window.document.frmedita.unae27unidadpadre.value=window.document.frmedita.unae26unidadpadre.value;
	window.document.frmedita.unae27idresponsable.value=0;
	window.document.frmedita.unae27idresponsable_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.unae27idresponsable_doc.value='';
	document.getElementById('div_unae27idresponsable').innerHTML='';
	//ter_traerxid('unae27idresponsable', window.document.frmedita.idusuario.value);
	window.document.frmedita.unae27tituloresponsable.value='';
	window.document.frmedita.unae27orden.value='';
	verboton('belimina227','none');
	}
function eliminaf227(){
	var params=new Array();
	params[0]=window.document.frmedita.unae26consec.value;
	params[1]=window.document.frmedita.unae26consec.value;
	params[2]=window.document.frmedita.unae27idzona.value;
	params[3]=window.document.frmedita.unae27id.value;
	//params[11]=window.document.frmedita.p1_227.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf227.value;
	params[102]=window.document.frmedita.lppf227.value;
	if (window.document.frmedita.unae27id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f227_Eliminar(params);
			}
		}
	}
function revisaf227(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unae26consec.value;
	params[2]=window.document.frmedita.unae27idzona.value;
	params[3]=window.document.frmedita.unae27id.value;
	if ((params[2]!='')){
		xajax_f227_Traer(params);
		}
	}
function cargadatof227(llave1){
	window.document.frmedita.unae27idzona.value=String(llave1);
	revisaf227();
	}
function cargaridf227(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f227_Traer(params);
	expandepanel(227,'block',0);
	}
function paginarf227(){
	var params=new Array();
	params[0]=window.document.frmedita.unae26consec.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf227.value;
	params[102]=window.document.frmedita.lppf227.value;
	//params[103]=window.document.frmedita.bnombre227.value;
	//params[104]=window.document.frmedita.blistar227.value;
	document.getElementById('div_f227detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf227" name="paginaf227" type="hidden" value="'+params[101]+'" /><input id="lppf227" name="lppf227" type="hidden" value="'+params[102]+'" />';
	xajax_f227_HtmlTabla(params);
	}
function imprime227(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_227.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_227.value;
	window.document.frmlista.nombrearchivo.value='Fractales';
	window.document.frmlista.submit();
	}
