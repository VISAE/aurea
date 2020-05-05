// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.15.8 lunes, 24 de octubre de 2016
// --- Modelo Versión 2.22.7 viernes, 22 de marzo de 2019
function limpia_bita05idarchivo(){
	window.document.frmedita.bita05idorigen.value=0;
	window.document.frmedita.bita05idarchivo.value=0;
	var da_Archivo=document.getElementById('div_bita05idarchivo');
	da_Archivo.innerHTML='&nbsp;';
	verboton('beliminabita05idarchivo','none');
	//paginarf0000();
	}
function carga_bita05idarchivo(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_1504.value+' - Cargar archivo</h2>';
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivo.php?ref=1505&id='+window.document.frmedita.bita05id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
function eliminabita05idarchivo(){
	var did=window.document.frmedita.bita05id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_bita05idarchivo(did.value);
		//paginarf0000();
		}
	}
function guardaf1505(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.bita04id.value;
	valores[2]=window.document.frmedita.bita05consec.value;
	valores[3]=window.document.frmedita.bita05id.value;
	valores[4]=window.document.frmedita.bita05fecha.value;
	valores[5]=window.document.frmedita.bita05hora.value;
	valores[6]=window.document.frmedita.bita05minuto.value;
	valores[7]=window.document.frmedita.bita05idtercero.value;
	valores[8]=window.document.frmedita.bita05contenido.value;
	valores[9]=window.document.frmedita.bita05descartada.value;
	valores[10]=window.document.frmedita.bita05visiblesolicitante.value;
	params[0]=window.document.frmedita.bita04id.value;
	//params[1]=window.document.frmedita.p1_1505.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1505.value;
	params[102]=window.document.frmedita.lppf1505.value;
	xajax_f1505_Guardar(valores, params);
	}
function limpiaf1505(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1505_PintarLlaves(params);
	fecha_asignar('bita05fecha', sfbase);
	//hora_asignar('bita05hora', window.document.frmedita.shora.value);
	window.document.frmedita.bita05hora.value='';
	window.document.frmedita.bita05hora_Num.value='';
	window.document.frmedita.bita05hora_Ciclo.value='A';
	window.document.frmedita.bita05minuto.value='';
	window.document.frmedita.bita05idtercero.value=0;
	window.document.frmedita.bita05idtercero_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.bita05idtercero_doc.value='';
	document.getElementById('div_bita05idtercero').innerHTML='';
	ter_traerxid('bita05idtercero', window.document.frmedita.idusuario.value);
	window.document.frmedita.bita05contenido.value='';
	window.document.frmedita.bita05descartada.value='N';
	window.document.frmedita.bita05visiblesolicitante.value='S';
	limpia_bita05idarchivo();
	verboton('banexabita05idarchivo','none');
	verboton('belimina1505','none');
	}
function eliminaf1505(){
	var params=new Array();
	params[0]=window.document.frmedita.bita04id.value;
	params[1]=window.document.frmedita.bita04id.value;
	params[2]=window.document.frmedita.bita05consec.value;
	params[3]=window.document.frmedita.bita05id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1505.value;
	params[102]=window.document.frmedita.lppf1505.value;
	if (window.document.frmedita.bita05id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1505_Eliminar(params);
			}
		}
	}
function revisaf1505(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.bita04id.value;
	params[2]=window.document.frmedita.bita05consec.value;
	params[3]=window.document.frmedita.bita05id.value;
	if ((params[2]!='')){
		xajax_f1505_Traer(params);
		}
	}
function cargadatof1505(llave1){
	window.document.frmedita.bita05consec.value=String(llave1);
	revisaf1505();
	}
function cargaridf1505(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1505_Traer(params);
	expandepanel(1505,'block',0);
	}
function paginarf1505(){
	var params=new Array();
	params[0]=window.document.frmedita.bita04id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1505.value;
	params[102]=window.document.frmedita.lppf1505.value;
	//params[103]=window.document.frmedita.bnombre1505.value;
	//params[104]=window.document.frmedita.blistar1505.value;
	document.getElementById('div_f1505detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1505" name="paginaf1505" type="hidden" value="'+params[101]+'" /><input id="lppf1505" name="lppf1505" type="hidden" value="'+params[102]+'" />';
	xajax_f1505_HtmlTabla(params);
	}
function imprime1505(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1505.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1505.value;
	window.document.frmlista.nombrearchivo.value='Anotaciones';
	window.document.frmlista.submit();
	}