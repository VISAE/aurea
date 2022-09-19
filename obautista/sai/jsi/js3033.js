// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Palabras claves
// --- Modelo Versión 2.25.4 miércoles, 5 de agosto de 2020
function guardaf3033(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu31id.value;
	valores[2]=window.document.frmedita.saiu33idpalabra.value;
	valores[3]=window.document.frmedita.saiu33id.value;
	valores[4]=window.document.frmedita.saiu33activo.value;
	params[0]=window.document.frmedita.saiu31id.value;
	//params[1]=window.document.frmedita.p1_3033.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3033.value;
	params[102]=window.document.frmedita.lppf3033.value;
	xajax_f3033_Guardar(valores, params);
	}
function limpiaf3033(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3033_PintarLlaves(params);
	window.document.frmedita.saiu33activo.value=1;
	verboton('belimina3033','none');
	}
function eliminaf3033(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu33idpalabra.value;
	params[3]=window.document.frmedita.saiu33id.value;
	//params[6]=window.document.frmedita.p1_3033.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3033.value;
	params[102]=window.document.frmedita.lppf3033.value;
	if (window.document.frmedita.saiu33id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3033_Eliminar(params);
			}
		}
	}
function revisaf3033(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu33idpalabra.value;
	params[3]=window.document.frmedita.saiu33id.value;
	if ((params[2]!='')){
		xajax_f3033_Traer(params);
		}
	}
function cargadatof3033(llave1){
	window.document.frmedita.saiu33idpalabra.value=String(llave1);
	revisaf3033();
	}
function cargaridf3033(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3033_Traer(params);
	expandepanel(3033,'block',0);
	}
function paginarf3033(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3033.value;
	params[102]=window.document.frmedita.lppf3033.value;
	//params[103]=window.document.frmedita.bnombre3033.value;
	//params[104]=window.document.frmedita.blistar3033.value;
	document.getElementById('div_f3033detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3033" name="paginaf3033" type="hidden" value="'+params[101]+'" /><input id="lppf3033" name="lppf3033" type="hidden" value="'+params[102]+'" />';
	xajax_f3033_HtmlTabla(params);
	}
function imprime3033(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3033.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3033.value;
	window.document.frmlista.nombrearchivo.value='Palabras claves';
	window.document.frmlista.submit();
	}
function masuno3033(){
	var params=new Array();
	params[1]=window.document.frmedita.saiu31id.value;
	params[2]=window.document.frmedita.saiu31id.value;
	xajax_f3033_MasUno(params);
	}
function menosuno3033(id3033){
	if (confirm('Se va a retirar la fila {Ref '+id3033+'}, \n¿desea continuar?')){
		var params=new Array();
		params[1]=window.document.frmedita.saiu31id.value;
		params[2]=id3033;
		xajax_f3033_MenosUno(params);
		}
	}
function cuadricula3033(idcampo, id3033, sValor, sValor2=''){
	var params=new Array();
	params[1]=idcampo;
	params[2]=id3033;
	params[3]=sValor;
	params[4]=sValor2;
	xajax_f3033_GuardarCuadro(params);
	}
