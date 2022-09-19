// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Temas asociados
// --- Modelo Versión 2.25.4 miércoles, 5 de agosto de 2020
function guardaf3032(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu31id.value;
	valores[2]=window.document.frmedita.saiu32idtema.value;
	valores[3]=window.document.frmedita.saiu32id.value;
	valores[4]=window.document.frmedita.saiu32activo.value;
	valores[5]=window.document.frmedita.saiu32porcentaje.value;
	valores[6]=window.document.frmedita.saiu32porcpalclave.value;
	params[0]=window.document.frmedita.saiu31id.value;
	//params[1]=window.document.frmedita.p1_3032.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3032.value;
	params[102]=window.document.frmedita.lppf3032.value;
	xajax_f3032_Guardar(valores, params);
	}
function limpiaf3032(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3032_PintarLlaves(params);
	window.document.frmedita.saiu32activo.value=1;
	window.document.frmedita.saiu32porcentaje.value='';
	verboton('belimina3032','none');
	}
function eliminaf3032(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu32idtema.value;
	params[3]=window.document.frmedita.saiu32id.value;
	//params[8]=window.document.frmedita.p1_3032.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3032.value;
	params[102]=window.document.frmedita.lppf3032.value;
	if (window.document.frmedita.saiu32id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3032_Eliminar(params);
			}
		}
	}
function revisaf3032(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu32idtema.value;
	params[3]=window.document.frmedita.saiu32id.value;
	if ((params[2]!='')){
		xajax_f3032_Traer(params);
		}
	}
function cargadatof3032(llave1){
	window.document.frmedita.saiu32idtema.value=String(llave1);
	revisaf3032();
	}
function cargaridf3032(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3032_Traer(params);
	expandepanel(3032,'block',0);
	}
function paginarf3032(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3032.value;
	params[102]=window.document.frmedita.lppf3032.value;
	//params[103]=window.document.frmedita.bnombre3032.value;
	//params[104]=window.document.frmedita.blistar3032.value;
	document.getElementById('div_f3032detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3032" name="paginaf3032" type="hidden" value="'+params[101]+'" /><input id="lppf3032" name="lppf3032" type="hidden" value="'+params[102]+'" />';
	xajax_f3032_HtmlTabla(params);
	}
function imprime3032(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3032.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3032.value;
	window.document.frmlista.nombrearchivo.value='Temas asociados';
	window.document.frmlista.submit();
	}
