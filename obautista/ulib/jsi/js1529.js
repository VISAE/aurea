// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Cambios de estado
// --- Modelo Versión 2.22.7 viernes, 22 de marzo de 2019
function guardaf1529(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.bita04id.value;
	valores[2]=window.document.frmedita.bita29consec.value;
	valores[3]=window.document.frmedita.bita29id.value;
	valores[4]=window.document.frmedita.bita29idestadoorigen.value;
	valores[5]=window.document.frmedita.bita29idestadofin.value;
	valores[6]=window.document.frmedita.bita29idusuario.value;
	valores[7]=window.document.frmedita.bita29fecha.value;
	valores[8]=window.document.frmedita.bita29hora.value;
	params[0]=window.document.frmedita.bita04id.value;
	//params[1]=window.document.frmedita.p1_1529.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1529.value;
	params[102]=window.document.frmedita.lppf1529.value;
	xajax_f1529_Guardar(valores, params);
	}
function limpiaf1529(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1529_PintarLlaves(params);
	window.document.frmedita.bita29idestadoorigen.value='';
	window.document.frmedita.bita29idestadofin.value='';
	window.document.frmedita.bita29idusuario.value=0;
	window.document.frmedita.bita29idusuario_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.bita29idusuario_doc.value='';
	document.getElementById('div_bita29idusuario').innerHTML='';
	//ter_traerxid('bita29idusuario', window.document.frmedita.idusuario.value);
	fecha_AsignarNum('bita29fecha', iFechaBaseNum);
	fecha_AsignarNum('bita29hora', iFechaBaseNum);
	verboton('belimina1529','none');
	}
function eliminaf1529(){
	var params=new Array();
	params[0]=window.document.frmedita.bita04id.value;
	params[1]=window.document.frmedita.bita04id.value;
	params[2]=window.document.frmedita.bita29consec.value;
	params[3]=window.document.frmedita.bita29id.value;
	//params[10]=window.document.frmedita.p1_1529.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1529.value;
	params[102]=window.document.frmedita.lppf1529.value;
	if (window.document.frmedita.bita29id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1529_Eliminar(params);
			}
		}
	}
function revisaf1529(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.bita04id.value;
	params[2]=window.document.frmedita.bita29consec.value;
	params[3]=window.document.frmedita.bita29id.value;
	if ((params[2]!='')){
		xajax_f1529_Traer(params);
		}
	}
function cargadatof1529(llave1){
	window.document.frmedita.bita29consec.value=String(llave1);
	revisaf1529();
	}
function cargaridf1529(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1529_Traer(params);
	expandepanel(1529,'block',0);
	}
function paginarf1529(){
	var params=new Array();
	params[0]=window.document.frmedita.bita04id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1529.value;
	params[102]=window.document.frmedita.lppf1529.value;
	//params[103]=window.document.frmedita.bnombre1529.value;
	//params[104]=window.document.frmedita.blistar1529.value;
	document.getElementById('div_f1529detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1529" name="paginaf1529" type="hidden" value="'+params[101]+'" /><input id="lppf1529" name="lppf1529" type="hidden" value="'+params[102]+'" />';
	xajax_f1529_HtmlTabla(params);
	}
function imprime1529(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1529.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1529.value;
	window.document.frmlista.nombrearchivo.value='Cambios de estado';
	window.document.frmlista.submit();
	}
