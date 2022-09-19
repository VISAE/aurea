// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Cambios de estado
// --- Modelo Versión 2.25.5 jueves, 13 de agosto de 2020
function guardaf3039(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu28id.value;
	valores[2]=window.document.frmedita.saiu39consec.value;
	valores[3]=window.document.frmedita.saiu39id.value;
	valores[4]=window.document.frmedita.saiu39idetapa.value;
	valores[5]=window.document.frmedita.saiu39idresponsable.value;
	valores[6]=window.document.frmedita.saiu39idestadorigen.value;
	valores[7]=window.document.frmedita.saiu39idestadofin.value;
	valores[8]=window.document.frmedita.saiu39detalle.value;
	valores[9]=window.document.frmedita.saiu39usuario.value;
	valores[10]=window.document.frmedita.saiu39fecha.value;
	valores[11]=window.document.frmedita.saiu39hora.value;
	valores[12]=window.document.frmedita.saiu39minuto.value;
	valores[13]=window.document.frmedita.saiu39correterminos.value;
	valores[14]=window.document.frmedita.saiu39tiempousado.value;
	valores[15]=window.document.frmedita.saiu39tiempocalusado.value;
	valores[98]=window.document.frmedita.saiu28agno.value;
	params[0]=window.document.frmedita.saiu28id.value;
	//params[1]=window.document.frmedita.p1_3039.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3039.value;
	params[102]=window.document.frmedita.lppf3039.value;
	xajax_f3039_Guardar(valores, params);
	}
function limpiaf3039(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3039_PintarLlaves(params);
	window.document.frmedita.saiu39idetapa.value='';
	window.document.frmedita.saiu39idresponsable.value=0;
	window.document.frmedita.saiu39idresponsable_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.saiu39idresponsable_doc.value='';
	document.getElementById('div_saiu39idresponsable').innerHTML='';
	//ter_traerxid('saiu39idresponsable', window.document.frmedita.idusuario.value);
	window.document.frmedita.saiu39idestadorigen.value='';
	window.document.frmedita.saiu39idestadofin.value='';
	window.document.frmedita.saiu39detalle.value='';
	fecha_AsignarNum('saiu39fecha', iFechaBaseNum);
	verboton('belimina3039','none');
	}
function eliminaf3039(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28id.value;
	params[1]=window.document.frmedita.saiu28id.value;
	params[2]=window.document.frmedita.saiu39consec.value;
	params[3]=window.document.frmedita.saiu39id.value;
	//params[17]=window.document.frmedita.p1_3039.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3039.value;
	params[102]=window.document.frmedita.lppf3039.value;
	if (window.document.frmedita.saiu39id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3039_Eliminar(params);
			}
		}
	}
function revisaf3039(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu28id.value;
	params[2]=window.document.frmedita.saiu39consec.value;
	params[3]=window.document.frmedita.saiu39id.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	if ((params[2]!='')){
		xajax_f3039_Traer(params);
		}
	}
function cargadatof3039(llave1){
	window.document.frmedita.saiu39consec.value=String(llave1);
	revisaf3039();
	}
function cargaridf3039(llave1){
	var params=new Array();
	params[0]=2;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[103]=llave1;
	xajax_f3039_Traer(params);
	expandepanel(3039,'block',0);
	}
function paginarf3039(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28id.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3039.value;
	params[102]=window.document.frmedita.lppf3039.value;
	//params[103]=window.document.frmedita.bnombre3039.value;
	//params[104]=window.document.frmedita.blistar3039.value;
	document.getElementById('div_f3039detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3039" name="paginaf3039" type="hidden" value="'+params[101]+'" /><input id="lppf3039" name="lppf3039" type="hidden" value="'+params[102]+'" />';
	xajax_f3039_HtmlTabla(params);
	}
function imprime3039(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3039.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3039.value;
	window.document.frmlista.nombrearchivo.value='Cambios de estado';
	window.document.frmlista.submit();
	}
