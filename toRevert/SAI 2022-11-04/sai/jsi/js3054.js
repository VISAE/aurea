// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Perfiles
// --- Modelo Versión 2.25.10c lunes, 5 de abril de 2021
function guardaf3054(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu53id.value;
	valores[2]=window.document.frmedita.saiu54idperfil.value;
	valores[3]=window.document.frmedita.saiu54id.value;
	valores[4]=window.document.frmedita.saiu54vigente.value;
	params[0]=window.document.frmedita.saiu53id.value;
	//params[1]=window.document.frmedita.p1_3054.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3054.value;
	params[102]=window.document.frmedita.lppf3054.value;
	xajax_f3054_Guardar(valores, params);
	}
function limpiaf3054(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3054_PintarLlaves(params);
	window.document.frmedita.saiu54vigente.value=1;
	verboton('belimina3054','none');
	}
function eliminaf3054(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu53id.value;
	params[1]=window.document.frmedita.saiu53id.value;
	params[2]=window.document.frmedita.saiu54idperfil.value;
	params[3]=window.document.frmedita.saiu54id.value;
	//params[6]=window.document.frmedita.p1_3054.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3054.value;
	params[102]=window.document.frmedita.lppf3054.value;
	if (window.document.frmedita.saiu54id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3054_Eliminar(params);
			}
		}
	}
function revisaf3054(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu53id.value;
	params[2]=window.document.frmedita.saiu54idperfil.value;
	params[3]=window.document.frmedita.saiu54id.value;
	if ((params[2]!='')){
		xajax_f3054_Traer(params);
		}
	}
function cargadatof3054(llave1){
	window.document.frmedita.saiu54idperfil.value=String(llave1);
	revisaf3054();
	}
function cargaridf3054(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3054_Traer(params);
	expandepanel(3054,'block',0);
	}
function paginarf3054(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu53id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3054.value;
	params[102]=window.document.frmedita.lppf3054.value;
	//params[103]=window.document.frmedita.bnombre3054.value;
	//params[104]=window.document.frmedita.blistar3054.value;
	document.getElementById('div_f3054detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3054" name="paginaf3054" type="hidden" value="'+params[101]+'" /><input id="lppf3054" name="lppf3054" type="hidden" value="'+params[102]+'" />';
	xajax_f3054_HtmlTabla(params);
	}
function imprime3054(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3054.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3054.value;
	window.document.frmlista.nombrearchivo.value='Perfiles';
	window.document.frmlista.submit();
	}
