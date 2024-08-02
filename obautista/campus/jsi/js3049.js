// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Cambios de estado
// --- Modelo Versión 2.25.10c lunes, 3 de mayo de 2021
function guardaf3049(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu47id.value;
	valores[2]=window.document.frmedita.saiu49consec.value;
	valores[3]=window.document.frmedita.saiu49id.value;
	valores[4]=window.document.frmedita.saiu49idresponsable.value;
	valores[5]=window.document.frmedita.saiu49idestadorigen.value;
	valores[6]=window.document.frmedita.saiu49idestadofin.value;
	valores[7]=window.document.frmedita.saiu49detalle.value;
	valores[8]=window.document.frmedita.saiu49usuario.value;
	valores[9]=window.document.frmedita.saiu49fecha.value;
	valores[10]=window.document.frmedita.saiu49hora.value;
	valores[11]=window.document.frmedita.saiu49minuto.value;
	valores[12]=window.document.frmedita.saiu49correterminos.value;
	valores[13]=window.document.frmedita.saiu49tiempousado.value;
	valores[14]=window.document.frmedita.saiu49tiempocalusado.value;
	params[0]=window.document.frmedita.saiu47id.value;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3049.value;
	params[102]=window.document.frmedita.lppf3049.value;
	xajax_f3049_Guardar(valores, params);
	}
function limpiaf3049(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3049_PintarLlaves(params);
	window.document.frmedita.saiu49idresponsable.value=0;
	window.document.frmedita.saiu49idresponsable_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.saiu49idresponsable_doc.value='';
	document.getElementById('div_saiu49idresponsable').innerHTML='';
	//ter_traerxid('saiu49idresponsable', window.document.frmedita.idusuario.value);
	window.document.frmedita.saiu49idestadorigen.value='';
	window.document.frmedita.saiu49idestadofin.value='';
	window.document.frmedita.saiu49detalle.value='';
	verboton('belimina3049','none');
	}
function eliminaf3049(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu47id.value;
	params[1]=window.document.frmedita.saiu47id.value;
	params[2]=window.document.frmedita.saiu49consec.value;
	params[3]=window.document.frmedita.saiu49id.value;
	//params[16]=window.document.frmedita.p1_3049.value;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3049.value;
	params[102]=window.document.frmedita.lppf3049.value;
	if (window.document.frmedita.saiu49id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3049_Eliminar(params);
			}
		}
	}
function revisaf3049(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu47id.value;
	params[2]=window.document.frmedita.saiu49consec.value;
	params[3]=window.document.frmedita.saiu49id.value;
	if ((params[2]!='')){
		xajax_f3049_Traer(params);
		}
	}
function cargadatof3049(llave1){
	window.document.frmedita.saiu49consec.value=String(llave1);
	revisaf3049();
	}
function cargaridf3049(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3049_Traer(params);
	expandepanel(3049,'block',0);
	}
function paginarf3049(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu47id.value;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3049.value;
	params[102]=window.document.frmedita.lppf3049.value;
	//params[103]=window.document.frmedita.bnombre3049.value;
	//params[104]=window.document.frmedita.blistar3049.value;
	document.getElementById('div_f3049detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3049" name="paginaf3049" type="hidden" value="'+params[101]+'" /><input id="lppf3049" name="lppf3049" type="hidden" value="'+params[102]+'" />';
	xajax_f3049_HtmlTabla(params);
	}
function imprime3049(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3049.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3049.value;
	window.document.frmlista.nombrearchivo.value='Cambios de estado';
	window.document.frmlista.submit();
	}
