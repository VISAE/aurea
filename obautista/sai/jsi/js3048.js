// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anotaciones
// --- Modelo Versión 2.25.10c lunes, 3 de mayo de 2021
function guardaf3048(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu47id.value;
	valores[2]=window.document.frmedita.saiu48consec.value;
	valores[3]=window.document.frmedita.saiu48id.value;
	valores[4]=window.document.frmedita.saiu48visiblealinteresado.value;
	valores[5]=window.document.frmedita.saiu48anotacion.value;
	valores[6]=window.document.frmedita.saiu48idusuario.value;
	valores[7]=window.document.frmedita.saiu48fecha.value;
	valores[8]=window.document.frmedita.saiu48hora.value;
	valores[9]=window.document.frmedita.saiu48minuto.value;
	valores[98]=window.document.frmedita.saiu47agno.value;
	params[0]=window.document.frmedita.saiu47id.value;
	//params[1]=window.document.frmedita.p1_3048.value;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3048.value;
	params[102]=window.document.frmedita.lppf3048.value;
	xajax_f3048_Guardar(valores, params);
	}
function limpiaf3048(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3048_PintarLlaves(params);
	window.document.frmedita.saiu48visiblealinteresado.value=0;
	window.document.frmedita.saiu48anotacion.value='';
	window.document.frmedita.saiu48idusuario.value=0;
	window.document.frmedita.saiu48idusuario_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.saiu48idusuario_doc.value='';
	document.getElementById('div_saiu48idusuario').innerHTML='';
	//ter_traerxid('saiu48idusuario', window.document.frmedita.idusuario.value);
	verboton('belimina3048','none');
	}
function eliminaf3048(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu47id.value;
	params[1]=window.document.frmedita.saiu47id.value;
	params[2]=window.document.frmedita.saiu48consec.value;
	params[3]=window.document.frmedita.saiu48id.value;
	//params[11]=window.document.frmedita.p1_3048.value;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3048.value;
	params[102]=window.document.frmedita.lppf3048.value;
	if (window.document.frmedita.saiu48id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3048_Eliminar(params);
			}
		}
	}
function revisaf3048(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu47id.value;
	params[2]=window.document.frmedita.saiu48consec.value;
	params[3]=window.document.frmedita.saiu48id.value;
	if ((params[2]!='')){
		params[98]=window.document.frmedita.saiu47agno.value;
		xajax_f3048_Traer(params);
		}
	}
function cargadatof3048(llave1){
	window.document.frmedita.saiu48consec.value=String(llave1);
	revisaf3048();
	}
function cargaridf3048(llave1){
	var params=new Array();
	params[0]=2;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[103]=llave1;
	xajax_f3048_Traer(params);
	expandepanel(3048,'block',0);
	}
function paginarf3048(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu47id.value;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3048.value;
	params[102]=window.document.frmedita.lppf3048.value;
	//params[103]=window.document.frmedita.bnombre3048.value;
	//params[104]=window.document.frmedita.blistar3048.value;
	document.getElementById('div_f3048detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3048" name="paginaf3048" type="hidden" value="'+params[101]+'" /><input id="lppf3048" name="lppf3048" type="hidden" value="'+params[102]+'" />';
	xajax_f3048_HtmlTabla(params);
	}
function imprime3048(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3048.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3048.value;
	window.document.frmlista.nombrearchivo.value='Anotaciones';
	window.document.frmlista.submit();
	}
