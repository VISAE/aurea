// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Cobertura
// --- Modelo Versión 2.25.4 miércoles, 5 de agosto de 2020
function carga_combo_saiu35idcentro(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu35idzona.value;
	document.getElementById('div_saiu35idcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu35idcentro" name="saiu35idcentro" type="hidden" value="" />';
	xajax_f3035_Combosaiu35idcentro(params);
	}
function guardaf3035(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu31id.value;
	valores[2]=window.document.frmedita.saiu35idzona.value;
	valores[3]=window.document.frmedita.saiu35idcentro.value;
	valores[4]=window.document.frmedita.saiu35id.value;
	valores[5]=window.document.frmedita.saiu35activo.value;
	params[0]=window.document.frmedita.saiu31id.value;
	//params[1]=window.document.frmedita.p1_3035.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3035.value;
	params[102]=window.document.frmedita.lppf3035.value;
	xajax_f3035_Guardar(valores, params);
	}
function limpiaf3035(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3035_PintarLlaves(params);
	window.document.frmedita.saiu35activo.value=1;
	verboton('belimina3035','none');
	}
function eliminaf3035(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu35idzona.value;
	params[3]=window.document.frmedita.saiu35idcentro.value;
	params[4]=window.document.frmedita.saiu35id.value;
	//params[7]=window.document.frmedita.p1_3035.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3035.value;
	params[102]=window.document.frmedita.lppf3035.value;
	if (window.document.frmedita.saiu35id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3035_Eliminar(params);
			}
		}
	}
function revisaf3035(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu35idzona.value;
	params[3]=window.document.frmedita.saiu35idcentro.value;
	params[4]=window.document.frmedita.saiu35id.value;
	if ((params[2]!='')&&(params[3]!='')){
		xajax_f3035_Traer(params);
		}
	}
function cargadatof3035(llave1, llave2){
	window.document.frmedita.saiu35idzona.value=String(llave1);
	window.document.frmedita.saiu35idcentro.value=String(llave2);
	revisaf3035();
	}
function cargaridf3035(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3035_Traer(params);
	expandepanel(3035,'block',0);
	}
function paginarf3035(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3035.value;
	params[102]=window.document.frmedita.lppf3035.value;
	//params[103]=window.document.frmedita.bnombre3035.value;
	//params[104]=window.document.frmedita.blistar3035.value;
	document.getElementById('div_f3035detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3035" name="paginaf3035" type="hidden" value="'+params[101]+'" /><input id="lppf3035" name="lppf3035" type="hidden" value="'+params[102]+'" />';
	xajax_f3035_HtmlTabla(params);
	}
function imprime3035(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3035.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3035.value;
	window.document.frmlista.nombrearchivo.value='Cobertura';
	window.document.frmlista.submit();
	}
