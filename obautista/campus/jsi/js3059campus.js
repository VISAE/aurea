// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anexos
// --- Modelo Versión 2.25.10c lunes, 3 de mayo de 2021
function limpia_saiu59idarchivo(){
	window.document.frmedita.saiu59idorigen.value=0;
	window.document.frmedita.saiu59idarchivo.value=0;
	var da_Archivo=document.getElementById('div_saiu59idarchivo');
	da_Archivo.innerHTML='&nbsp;';
	verboton('beliminasaiu59idarchivo','none');
	//paginarf0000();
	}
function carga_saiu59idarchivo(saiu59id){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3047.value+' - Cargar archivo</h2>';
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3059&id='+saiu59id+'&tabla=_'+window.document.frmedita.saiu47agno.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
function eliminasaiu59idarchivo(){
	var did=window.document.frmedita.saiu59id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_saiu59idarchivo(did.value);
		//paginarf0000();
		}
	}
function guardaf3059(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu47id.value;
	valores[2]=window.document.frmedita.saiu59consec.value;
	valores[3]=window.document.frmedita.saiu59id.value;
	valores[4]=window.document.frmedita.saiu59idtipodoc.value;
	valores[5]=window.document.frmedita.saiu59opcional.value;
	valores[6]=window.document.frmedita.saiu59idestado.value;
	valores[9]=window.document.frmedita.saiu59idusuario.value;
	valores[10]=window.document.frmedita.saiu59fecha.value;
	valores[11]=window.document.frmedita.saiu59idrevisa.value;
	valores[12]=window.document.frmedita.saiu59fecharevisa.value;
	params[0]=window.document.frmedita.saiu47id.value;
	//params[1]=window.document.frmedita.p1_3059.value;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3059.value;
	params[102]=window.document.frmedita.lppf3059.value;
	xajax_f3059_Guardar(valores, params);
	}
function limpiaf3059(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3059_PintarLlaves(params);
	window.document.frmedita.saiu59idtipodoc.value='';
	window.document.frmedita.saiu59opcional.value=0;
	limpia_saiu59idarchivo();
	verboton('banexasaiu59idarchivo','none');
	verboton('belimina3059','none');
	}
function eliminaf3059(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu47id.value;
	params[1]=window.document.frmedita.saiu47id.value;
	params[2]=window.document.frmedita.saiu59consec.value;
	params[3]=window.document.frmedita.saiu59id.value;
	//params[14]=window.document.frmedita.p1_3059.value;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3059.value;
	params[102]=window.document.frmedita.lppf3059.value;
	if (window.document.frmedita.saiu59id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3059_Eliminar(params);
			}
		}
	}
function revisaf3059(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu47id.value;
	params[2]=window.document.frmedita.saiu59consec.value;
	params[3]=window.document.frmedita.saiu59id.value;
	if ((params[2]!='')){
		xajax_f3059_Traer(params);
		}
	}
function cargadatof3059(llave1){
	window.document.frmedita.saiu59consec.value=String(llave1);
	revisaf3059();
	}
function cargaridf3059(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3059_Traer(params);
	expandepanel(3059,'block',0);
	}
function paginarf3059(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu47id.value;
	params[98]=window.document.frmedita.saiu47agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3059.value;
	params[102]=window.document.frmedita.lppf3059.value;
	//params[103]=window.document.frmedita.bnombre3059.value;
	//params[104]=window.document.frmedita.blistar3059.value;
	document.getElementById('div_f3059detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3059" name="paginaf3059" type="hidden" value="'+params[101]+'" /><input id="lppf3059" name="lppf3059" type="hidden" value="'+params[102]+'" />';
	xajax_f3059_HtmlTablaCampus(params);
	}
function imprime3059(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3059.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3059.value;
	window.document.frmlista.nombrearchivo.value='Anexos';
	window.document.frmlista.submit();
	}
