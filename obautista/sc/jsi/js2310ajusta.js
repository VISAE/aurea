// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Preguntas de la prueba
// --- Modelo Versión 2.22.4 lunes, 3 de septiembre de 2018
function guardaf2310(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.cara01id.value;
	valores[2]=window.document.frmedita.cara10idbloque.value;
	valores[3]=window.document.frmedita.cara10consec.value;
	valores[4]=window.document.frmedita.cara10id.value;
	valores[5]=window.document.frmedita.cara10idpregunta.value;
	valores[6]=window.document.frmedita.cara10idrpta.value;
	valores[7]=window.document.frmedita.cara10puntaje.value;
	valores[8]=window.document.frmedita.cara10nivelpregunta.value;
	params[0]=window.document.frmedita.cara01id.value;
	//params[1]=window.document.frmedita.p1_2310.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2310.value;
	params[102]=window.document.frmedita.lppf2310.value;
	xajax_f2310_Guardar(valores, params);
	}
function limpiaf2310(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2310_PintarLlaves(params);
	window.document.frmedita.cara10idpregunta.value='';
	window.document.frmedita.cara10nivelpregunta.value='';
	verboton('belimina2310','none');
	}
function quitaridf2310(id){
	var params=new Array();
	params[0]=window.document.frmedita.cara01id.value;
	params[4]=id;
	//params[10]=window.document.frmedita.p1_2310.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2310.value;
	params[102]=window.document.frmedita.lppf2310.value;
	params[104]=window.document.frmedita.blistar2310.value;
	xajax_f2310_Quitar(params);
	}
function revisaf2310(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.cara01id.value;
	params[2]=window.document.frmedita.cara10idbloque.value;
	params[3]=window.document.frmedita.cara10consec.value;
	params[4]=window.document.frmedita.cara10id.value;
	if ((params[2]!='')&&(params[3]!='')){
		xajax_f2310_Traer(params);
		}
	}
function cargadatof2310(llave1, llave2){
	window.document.frmedita.cara10idbloque.value=String(llave1);
	window.document.frmedita.cara10consec.value=String(llave2);
	revisaf2310();
	}
function cargaridf2310(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2310_Traer(params);
	expandepanel(2310,'block',0);
	}
function paginarf2310(){
	var params=new Array();
	params[0]=window.document.frmedita.cara01id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2310.value;
	params[102]=window.document.frmedita.lppf2310.value;
	//params[103]=window.document.frmedita.bnombre2310.value;
	params[104]=window.document.frmedita.blistar2310.value;
	//document.getElementById('div_f2310detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2310" name="paginaf2310" type="hidden" value="'+params[101]+'" /><input id="lppf2310" name="lppf2310" type="hidden" value="'+params[102]+'" />';
	xajax_f2310_HtmlTablaAjusta(params);
	}
function imprime2310(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2310.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2310.value;
	window.document.frmlista.nombrearchivo.value='Preguntas de la prueba';
	window.document.frmlista.submit();
	}
