// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Oferta
// --- Modelo Versión 2.22.5 martes, 13 de noviembre de 2018
function guardaf1707(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.unad40id.value;
	valores[2]=window.document.frmedita.ofer08idper_aca.value;
	valores[3]=window.document.frmedita.ofer08cead.value;
	valores[4]=window.document.frmedita.ofer08id.value;
	valores[5]=window.document.frmedita.ofer08estadooferta.value;
	valores[6]=window.document.frmedita.ofer08numestudiantes.value;
	valores[7]=window.document.frmedita.ofer08fechaoferta.value;
	valores[8]=window.document.frmedita.ofer08estadocampus.value;
	params[0]=window.document.frmedita.unad40id.value;
	//params[1]=window.document.frmedita.p1_1707.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1707.value;
	params[102]=window.document.frmedita.lppf1707.value;
	xajax_f1707_Guardar(valores, params);
	}
function limpiaf1707(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1707_PintarLlaves(params);
	window.document.frmedita.ofer08numestudiantes.value='';
	verboton('belimina1707','none');
	}
function eliminaf1707(){
	var params=new Array();
	params[0]=window.document.frmedita.unad40id.value;
	params[1]=window.document.frmedita.unad40id.value;
	params[2]=window.document.frmedita.ofer08idper_aca.value;
	params[3]=window.document.frmedita.ofer08cead.value;
	params[4]=window.document.frmedita.ofer08id.value;
	//params[10]=window.document.frmedita.p1_1707.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1707.value;
	params[102]=window.document.frmedita.lppf1707.value;
	if (window.document.frmedita.ofer08id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1707_Eliminar(params);
			}
		}
	}
function revisaf1707(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unad40id.value;
	params[2]=window.document.frmedita.ofer08idper_aca.value;
	params[3]=window.document.frmedita.ofer08cead.value;
	params[4]=window.document.frmedita.ofer08id.value;
	if ((params[2]!='')&&(params[3]!='')){
		xajax_f1707_Traer(params);
		}
	}
function cargadatof1707(llave1, llave2){
	window.document.frmedita.ofer08idper_aca.value=String(llave1);
	window.document.frmedita.ofer08cead.value=String(llave2);
	revisaf1707();
	}
function cargaridf1707(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1707_Traer(params);
	expandepanel(1707,'block',0);
	}
function paginarf1707(){
	var params=new Array();
	params[0]=window.document.frmedita.unad40id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1707.value;
	params[102]=window.document.frmedita.lppf1707.value;
	//params[103]=window.document.frmedita.bnombre1707.value;
	//params[104]=window.document.frmedita.blistar1707.value;
	document.getElementById('div_f1707detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1707" name="paginaf1707" type="hidden" value="'+params[101]+'" /><input id="lppf1707" name="lppf1707" type="hidden" value="'+params[102]+'" />';
	xajax_f1707_HtmlTabla(params);
	}
function imprime1707(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1707.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1707.value;
	window.document.frmlista.nombrearchivo.value='Oferta';
	window.document.frmlista.submit();
	}
