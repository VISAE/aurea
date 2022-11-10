// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Temas relativos
// --- Modelo Versión 2.25.4 martes, 4 de agosto de 2020
function guardaf3037(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu31id.value;
	valores[2]=window.document.frmedita.saiu37idbaserel.value;
	valores[3]=window.document.frmedita.saiu37id.value;
	valores[4]=window.document.frmedita.saiu37porcentaje.value;
	valores[5]=window.document.frmedita.saiu37porcpalclave.value;
	params[0]=window.document.frmedita.saiu31id.value;
	//params[1]=window.document.frmedita.p1_3037.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3037.value;
	params[102]=window.document.frmedita.lppf3037.value;
	xajax_f3037_Guardar(valores, params);
	}
function limpiaf3037(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3037_PintarLlaves(params);
	window.document.frmedita.saiu37porcentaje.value='';
	verboton('belimina3037','none');
	}
function eliminaf3037(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu37idbaserel.value;
	params[3]=window.document.frmedita.saiu37id.value;
	//params[7]=window.document.frmedita.p1_3037.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3037.value;
	params[102]=window.document.frmedita.lppf3037.value;
	if (window.document.frmedita.saiu37id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3037_Eliminar(params);
			}
		}
	}
function revisaf3037(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu37idbaserel.value;
	params[3]=window.document.frmedita.saiu37id.value;
	if ((params[2]!='')){
		xajax_f3037_Traer(params);
		}
	}
function cargadatof3037(llave1){
	window.document.frmedita.saiu37idbaserel.value=String(llave1);
	revisaf3037();
	}
function cargaridf3037(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3037_Traer(params);
	expandepanel(3037,'block',0);
	}
function paginarf3037(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3037.value;
	params[102]=window.document.frmedita.lppf3037.value;
	//params[103]=window.document.frmedita.bnombre3037.value;
	//params[104]=window.document.frmedita.blistar3037.value;
	document.getElementById('div_f3037detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3037" name="paginaf3037" type="hidden" value="'+params[101]+'" /><input id="lppf3037" name="lppf3037" type="hidden" value="'+params[102]+'" />';
	xajax_f3037_HtmlTabla(params);
	}
function imprime3037(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3037.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3037.value;
	window.document.frmlista.nombrearchivo.value='Temas relativos';
	window.document.frmlista.submit();
	}
function masuno3037(){
	var params=new Array();
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu31id.value;
	xajax_f3037_MasUno(params);
	}
function menosuno3037(id3037){
	if (confirm('Se va a retirar la fila {Ref '+id3037+'}, \n¿desea continuar?')){
		var params=new Array();
		params[1]=window.document.frmedita.saiu31id.value;
		params[2]=id3037;
		xajax_f3037_MenosUno(params);
		}
	}
function cuadricula3037(idcampo, id3037, sValor, sValor2=''){
	var params=new Array();
	params[1]=idcampo;
	params[2]=id3037;
	params[3]=sValor;
	params[4]=sValor2;
	xajax_f3037_GuardarCuadro(params);
	}
