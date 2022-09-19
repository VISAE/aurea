// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Grupos a notificar
// --- Modelo Versión 2.27.5 viernes, 7 de enero de 2022
function paramsf3065(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu63id.value;
	//params[1]=window.document.frmedita.p1_3065.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3065.value;
	params[102]=window.document.frmedita.lppf3065.value;
	//params[103]=window.document.frmedita.bnombre3065.value;
	//params[104]=window.document.frmedita.blistar3065.value;
	return params;
	}
function guardaf3065(){
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu63id.value;
	valores[2]=window.document.frmedita.saiu65idgrupo.value;
	valores[3]=window.document.frmedita.saiu65id.value;
	valores[4]=window.document.frmedita.saiu65fechaaplicado.value;
	params=paramsf3065();
	xajax_f3065_Guardar(valores, params);
	}
function limpiaf3065(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3065_PintarLlaves(params);
	verboton('belimina3065','none');
	}
function eliminaf3065(){
	params=paramsf3065();
	params[1]=window.document.frmedita.saiu63id.value;
	params[2]=window.document.frmedita.saiu65idgrupo.value;
	params[3]=window.document.frmedita.saiu65id.value;
	//params[6]=window.document.frmedita.p1_3065.value;
	if (window.document.frmedita.saiu65id.value!=''){
		ModalConfirm('&iquest;Est&aacute; seguro de eliminar el dato?');
		ModalDialogConfirm(function(confirm){if(confirm){
			xajax_f3065_Eliminar(params);
			}});
		}
	}
function revisaf3065(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu63id.value;
	params[2]=window.document.frmedita.saiu65idgrupo.value;
	params[3]=window.document.frmedita.saiu65id.value;
	if ((params[2]!='')){
		xajax_f3065_Traer(params);
		}
	}
function cargadatof3065(llave1){
	window.document.frmedita.saiu65idgrupo.value=String(llave1);
	revisaf3065();
	}
function cargaridf3065(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3065_Traer(params);
	expandepanel(3065,'block',0);
	}
function paginarf3065(){
	params=paramsf3065();
	document.getElementById('div_f3065detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3065" name="paginaf3065" type="hidden" value="'+params[101]+'" /><input id="lppf3065" name="lppf3065" type="hidden" value="'+params[102]+'" />';
	xajax_f3065_HtmlTabla(params);
	}
function imprime3065(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3065.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3065.value;
	window.document.frmlista.nombrearchivo.value='Grupos a notificar';
	window.document.frmlista.submit();
	}
