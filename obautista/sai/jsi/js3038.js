// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Cambios de estado
// --- Modelo Versión 2.25.4 miércoles, 5 de agosto de 2020
function guardaf3038(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu31id.value;
	valores[2]=window.document.frmedita.saiu38consec.value;
	valores[3]=window.document.frmedita.saiu38id.value;
	valores[4]=window.document.frmedita.saiu38idestadorigen.value;
	valores[5]=window.document.frmedita.saiu38idestadofin.value;
	valores[6]=window.document.frmedita.saiu38detalle.value;
	valores[7]=window.document.frmedita.saiu38usuario.value;
	valores[8]=window.document.frmedita.saiu38fecha.value;
	params[0]=window.document.frmedita.saiu31id.value;
	//params[1]=window.document.frmedita.p1_3038.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3038.value;
	params[102]=window.document.frmedita.lppf3038.value;
	xajax_f3038_Guardar(valores, params);
	}
function limpiaf3038(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3038_PintarLlaves(params);
	window.document.frmedita.saiu38idestadorigen.value='';
	window.document.frmedita.saiu38idestadofin.value='';
	window.document.frmedita.saiu38detalle.value='';
	window.document.frmedita.saiu38usuario.value=0;
	window.document.frmedita.saiu38usuario_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.saiu38usuario_doc.value='';
	document.getElementById('div_saiu38usuario').innerHTML='';
	//ter_traerxid('saiu38usuario', window.document.frmedita.idusuario.value);
	fecha_AsignarNum('saiu38fecha', iFechaBaseNum);
	verboton('belimina3038','none');
	}
function eliminaf3038(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu38consec.value;
	params[3]=window.document.frmedita.saiu38id.value;
	//params[10]=window.document.frmedita.p1_3038.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3038.value;
	params[102]=window.document.frmedita.lppf3038.value;
	if (window.document.frmedita.saiu38id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3038_Eliminar(params);
			}
		}
	}
function revisaf3038(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu38consec.value;
	params[3]=window.document.frmedita.saiu38id.value;
	if ((params[2]!='')){
		xajax_f3038_Traer(params);
		}
	}
function cargadatof3038(llave1){
	window.document.frmedita.saiu38consec.value=String(llave1);
	revisaf3038();
	}
function cargaridf3038(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3038_Traer(params);
	expandepanel(3038,'block',0);
	}
function paginarf3038(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3038.value;
	params[102]=window.document.frmedita.lppf3038.value;
	//params[103]=window.document.frmedita.bnombre3038.value;
	//params[104]=window.document.frmedita.blistar3038.value;
	document.getElementById('div_f3038detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3038" name="paginaf3038" type="hidden" value="'+params[101]+'" /><input id="lppf3038" name="lppf3038" type="hidden" value="'+params[102]+'" />';
	xajax_f3038_HtmlTabla(params);
	}
function imprime3038(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3038.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3038.value;
	window.document.frmlista.nombrearchivo.value='Cambios de estado';
	window.document.frmlista.submit();
	}
