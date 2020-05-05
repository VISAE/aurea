// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Actividades ejecutadas
// --- Modelo Versión 2.23.2 lunes, 10 de junio de 2019
function guardaf215(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.unae14id.value;
	valores[2]=window.document.frmedita.unae15consec.value;
	valores[3]=window.document.frmedita.unae15id.value;
	valores[4]=window.document.frmedita.unae15idaccion.value;
	valores[5]=window.document.frmedita.unae15detalle.value;
	valores[6]=window.document.frmedita.unae15minuto.value;
	params[0]=window.document.frmedita.unae14id.value;
	//params[1]=window.document.frmedita.p1_215.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf215.value;
	params[102]=window.document.frmedita.lppf215.value;
	xajax_f215_Guardar(valores, params);
	}
function limpiaf215(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f215_PintarLlaves(params);
	window.document.frmedita.unae15idaccion.value='';
	window.document.frmedita.unae15detalle.value='';
	window.document.frmedita.unae15minuto.value='';
	verboton('belimina215','none');
	}
function eliminaf215(){
	var params=new Array();
	params[0]=window.document.frmedita.unae14id.value;
	params[1]=window.document.frmedita.unae14id.value;
	params[2]=window.document.frmedita.unae15consec.value;
	params[3]=window.document.frmedita.unae15id.value;
	//params[8]=window.document.frmedita.p1_215.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf215.value;
	params[102]=window.document.frmedita.lppf215.value;
	if (window.document.frmedita.unae15id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f215_Eliminar(params);
			}
		}
	}
function revisaf215(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unae14id.value;
	params[2]=window.document.frmedita.unae15consec.value;
	params[3]=window.document.frmedita.unae15id.value;
	if ((params[2]!='')){
		xajax_f215_Traer(params);
		}
	}
function cargadatof215(llave1){
	window.document.frmedita.unae15consec.value=String(llave1);
	revisaf215();
	}
function cargaridf215(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f215_Traer(params);
	expandepanel(215,'block',0);
	}
function paginarf215(){
	var params=new Array();
	params[0]=window.document.frmedita.unae14id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf215.value;
	params[102]=window.document.frmedita.lppf215.value;
	//params[103]=window.document.frmedita.bnombre215.value;
	//params[104]=window.document.frmedita.blistar215.value;
	document.getElementById('div_f215detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf215" name="paginaf215" type="hidden" value="'+params[101]+'" /><input id="lppf215" name="lppf215" type="hidden" value="'+params[102]+'" />';
	xajax_f215_HtmlTabla(params);
	}
function imprime215(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_215.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_215.value;
	window.document.frmlista.nombrearchivo.value='Actividades ejecutadas';
	window.document.frmlista.submit();
	}
