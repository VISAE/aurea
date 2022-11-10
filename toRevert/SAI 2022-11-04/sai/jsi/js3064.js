// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Notificados
// --- Modelo Versión 2.27.5 viernes, 7 de enero de 2022
function paramsf3064(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu63id.value;
	//params[1]=window.document.frmedita.p1_3064.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3064.value;
	params[102]=window.document.frmedita.lppf3064.value;
	//params[103]=window.document.frmedita.bnombre3064.value;
	//params[104]=window.document.frmedita.blistar3064.value;
	return params;
	}
function guardaf3064(){
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu63id.value;
	valores[2]=window.document.frmedita.saiu64idtercero.value;
	valores[3]=window.document.frmedita.saiu64id.value;
	valores[4]=window.document.frmedita.saiu64fechaaplicado.value;
	params=paramsf3064();
	xajax_f3064_Guardar(valores, params);
	}
function limpiaf3064(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3064_PintarLlaves(params);
	verboton('belimina3064','none');
	}
function eliminaf3064(){
	params=paramsf3064();
	params[1]=window.document.frmedita.saiu63id.value;
	params[2]=window.document.frmedita.saiu64idtercero.value;
	params[3]=window.document.frmedita.saiu64id.value;
	//params[6]=window.document.frmedita.p1_3064.value;
	if (window.document.frmedita.saiu64id.value!=''){
		ModalConfirm('&iquest;Est&aacute; seguro de eliminar el dato?');
		ModalDialogConfirm(function(confirm){if(confirm){
			xajax_f3064_Eliminar(params);
			}});
		}
	}
function revisaf3064(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu63id.value;
	params[2]=window.document.frmedita.saiu64idtercero.value;
	params[3]=window.document.frmedita.saiu64id.value;
	if ((params[2]!='')){
		xajax_f3064_Traer(params);
		}
	}
function cargadatof3064(llave1){
	window.document.frmedita.saiu64idtercero.value=String(llave1);
	revisaf3064();
	}
function cargaridf3064(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3064_Traer(params);
	expandepanel(3064,'block',0);
	}
function paginarf3064(){
	params=paramsf3064();
	document.getElementById('div_f3064detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3064" name="paginaf3064" type="hidden" value="'+params[101]+'" /><input id="lppf3064" name="lppf3064" type="hidden" value="'+params[102]+'" />';
	xajax_f3064_HtmlTabla(params);
	}
function imprime3064(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3064.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3064.value;
	window.document.frmlista.nombrearchivo.value='Notificados';
	window.document.frmlista.submit();
	}
