// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anotaciones
// --- Modelo Versión 2.24.1 viernes, 21 de febrero de 2020
function limpia_saiu06idarchivo(){
	window.document.frmedita.saiu06idorigen.value=0;
	window.document.frmedita.saiu06idarchivo.value=0;
	var da_Archivo=document.getElementById('div_saiu06idarchivo');
	da_Archivo.innerHTML='&nbsp;';
	verboton('beliminasaiu06idarchivo','none');
	//paginarf0000();
	}
function carga_saiu06idarchivo(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3005.value+' - Cargar archivo</h2>';
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivo.php?ref=3006&id='+window.document.frmedita.saiu06id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
function eliminasaiu06idarchivo(){
	var did=window.document.frmedita.saiu06id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_saiu06idarchivo(did.value);
		//paginarf0000();
		}
	}
function guardaf3006(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu05id.value;
	valores[2]=window.document.frmedita.saiu06consec.value;
	valores[3]=window.document.frmedita.saiu06id.value;
	valores[4]=window.document.frmedita.saiu06anotacion.value;
	valores[5]=window.document.frmedita.saiu06visible.value;
	valores[6]=window.document.frmedita.saiu06descartada.value;
	valores[9]=window.document.frmedita.saiu06idusuario.value;
	valores[10]=window.document.frmedita.saiu06fecha.value;
	valores[11]=window.document.frmedita.saiu06hora.value;
	valores[12]=window.document.frmedita.saiu06minuto.value;
	params[0]=window.document.frmedita.saiu05id.value;
	params[97]=window.document.frmedita.saiu05agno.value;
	params[98]=window.document.frmedita.saiu05mes.value;
	//params[1]=window.document.frmedita.p1_3006.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3006.value;
	params[102]=window.document.frmedita.lppf3006.value;
	xajax_f3006_Guardar(valores, params);
	}
function limpiaf3006(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3006_PintarLlaves(params);
	window.document.frmedita.saiu06anotacion.value='';
	window.document.frmedita.saiu06visible.value='';
	window.document.frmedita.saiu06descartada.value='';
	limpia_saiu06idarchivo();
	verboton('banexasaiu06idarchivo','none');
	window.document.frmedita.saiu06idusuario.value=0;
	window.document.frmedita.saiu06idusuario_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.saiu06idusuario_doc.value='';
	document.getElementById('div_saiu06idusuario').innerHTML='';
	ter_traerxid('saiu06idusuario', window.document.frmedita.idusuario.value);
	// fecha_AsignarNum('saiu06fecha', iFechaBaseNum);
	verboton('belimina3006','none');
	}
function eliminaf3006(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu05id.value;
	params[1]=window.document.frmedita.saiu05id.value;
	params[2]=window.document.frmedita.saiu06consec.value;
	params[3]=window.document.frmedita.saiu06id.value;
	//params[14]=window.document.frmedita.p1_3006.value;
	params[97]=window.document.frmedita.saiu05agno.value;
	params[98]=window.document.frmedita.saiu05mes.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3006.value;
	params[102]=window.document.frmedita.lppf3006.value;
	if (window.document.frmedita.saiu06id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3006_Eliminar(params);
			}
		}
	}
function revisaf3006(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu05id.value;
	params[2]=window.document.frmedita.saiu06consec.value;
	params[3]=window.document.frmedita.saiu06id.value;
	if ((params[2]!='')){
		xajax_f3006_Traer(params);
		}
	}
function cargadatof3006(llave1){
	window.document.frmedita.saiu06consec.value=String(llave1);
	revisaf3006();
	}
function cargaridf3006(llave1){
	var params=new Array();
	params[0]=2;
	params[97]=window.document.frmedita.saiu05agno.value;
	params[98]=window.document.frmedita.saiu05mes.value;
	params[103]=llave1;
	xajax_f3006_Traer(params);
	expandepanel(3006,'block',0);
	}
function paginarf3006(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu05id.value;
	params[97]=window.document.frmedita.saiu05agno.value;
	params[98]=window.document.frmedita.saiu05mes.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3006.value;
	params[102]=window.document.frmedita.lppf3006.value;
	//params[103]=window.document.frmedita.bnombre3006.value;
	//params[104]=window.document.frmedita.blistar3006.value;
	document.getElementById('div_f3006detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3006" name="paginaf3006" type="hidden" value="'+params[101]+'" /><input id="lppf3006" name="lppf3006" type="hidden" value="'+params[102]+'" />';
	xajax_f3006_HtmlTabla(params);
	}
function imprime3006(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3006.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3006.value;
	window.document.frmlista.nombrearchivo.value='Anotaciones';
	window.document.frmlista.submit();
	}
