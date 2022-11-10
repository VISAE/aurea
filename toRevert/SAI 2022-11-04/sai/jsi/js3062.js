// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Notificados
// --- Modelo Versión 2.25.10c jueves, 6 de mayo de 2021
function carga_combo_saiu62idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu62idescuela.value;
	document.getElementById('div_saiu62idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu62idprograma" name="saiu62idprograma" type="hidden" value="" />';
	xajax_f3062_Combosaiu62idprograma(params);
	}
function carga_combo_saiu62idcentro(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu62idzona.value;
	document.getElementById('div_saiu62idcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu62idcentro" name="saiu62idcentro" type="hidden" value="" />';
	xajax_f3062_Combosaiu62idcentro(params);
	}
function guardaf3062(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu61id.value;
	valores[2]=window.document.frmedita.saiu62idtercero.value;
	valores[3]=window.document.frmedita.saiu62id.value;
	valores[4]=window.document.frmedita.saiu62idperiodo.value;
	valores[5]=window.document.frmedita.saiu62idescuela.value;
	valores[6]=window.document.frmedita.saiu62idprograma.value;
	valores[7]=window.document.frmedita.saiu62idzona.value;
	valores[8]=window.document.frmedita.saiu62idcentro.value;
	valores[9]=window.document.frmedita.saiu62estado.value;
	valores[10]=window.document.frmedita.saiu62fecha.value;
	valores[11]=window.document.frmedita.saiu62fhora.value;
	valores[12]=window.document.frmedita.saiu62min.value;
	valores[13]=window.document.frmedita.saiu62mailenviado.value;
	params[0]=window.document.frmedita.saiu61id.value;
	//params[1]=window.document.frmedita.p1_3062.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3062.value;
	params[102]=window.document.frmedita.lppf3062.value;
	xajax_f3062_Guardar(valores, params);
	}
function limpiaf3062(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3062_PintarLlaves(params);
	window.document.frmedita.saiu62idperiodo.value='';
	window.document.frmedita.saiu62idescuela.value='';
	window.document.frmedita.saiu62idprograma.value='';
	window.document.frmedita.saiu62idzona.value='';
	window.document.frmedita.saiu62idcentro.value='';
	window.document.frmedita.saiu62estado.value='';
	fecha_AsignarNum('saiu62fecha', iFechaBaseNum);
	//hora_asignar('saiu62fhora', window.document.frmedita.shora.value);
	window.document.frmedita.saiu62fhora.value='';
	window.document.frmedita.saiu62fhora_Num.value='';
	window.document.frmedita.saiu62fhora_Ciclo.value='A';
	window.document.frmedita.saiu62min.value='';
	window.document.frmedita.saiu62mailenviado.value=0;
	verboton('belimina3062','none');
	}
function eliminaf3062(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu61id.value;
	params[1]=window.document.frmedita.saiu61id.value;
	params[2]=window.document.frmedita.saiu62idtercero.value;
	params[3]=window.document.frmedita.saiu62id.value;
	//params[15]=window.document.frmedita.p1_3062.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3062.value;
	params[102]=window.document.frmedita.lppf3062.value;
	if (window.document.frmedita.saiu62id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3062_Eliminar(params);
			}
		}
	}
function revisaf3062(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu61id.value;
	params[2]=window.document.frmedita.saiu62idtercero.value;
	params[3]=window.document.frmedita.saiu62id.value;
	if ((params[2]!='')){
		xajax_f3062_Traer(params);
		}
	}
function cargadatof3062(llave1){
	window.document.frmedita.saiu62idtercero.value=String(llave1);
	revisaf3062();
	}
function cargaridf3062(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3062_Traer(params);
	expandepanel(3062,'block',0);
	}
function paginarf3062(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu61id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3062.value;
	params[102]=window.document.frmedita.lppf3062.value;
	//params[103]=window.document.frmedita.bnombre3062.value;
	//params[104]=window.document.frmedita.blistar3062.value;
	document.getElementById('div_f3062detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3062" name="paginaf3062" type="hidden" value="'+params[101]+'" /><input id="lppf3062" name="lppf3062" type="hidden" value="'+params[102]+'" />';
	xajax_f3062_HtmlTabla(params);
	}
function imprime3062(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3062.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3062.value;
	window.document.frmlista.nombrearchivo.value='Notificados';
	window.document.frmlista.submit();
	}
