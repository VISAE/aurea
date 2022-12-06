// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anexos
// --- Modelo Versión 2.24.1 viernes, 21 de febrero de 2020
function limpia_saiu07idarchivo(){
	window.document.frmedita.saiu07idorigen.value=0;
	window.document.frmedita.saiu07idarchivo.value=0;
	var da_Archivo=document.getElementById('div_saiu07idarchivo');
	da_Archivo.innerHTML='&nbsp;';
	verboton('beliminasaiu07idarchivo','none');
	//paginarf0000();
	}
function carga_saiu07idarchivo(saiu07id){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3005.value+' - Cargar archivo</h2>';
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3007&id='+saiu07id+'&tabla=_'+window.document.frmedita.saiu05agno.value+window.document.frmedita.saiu05mes.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
function eliminasaiu07idarchivo(){
	var did=window.document.frmedita.saiu07id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_saiu07idarchivo(did.value);
		//paginarf0000();
		}
	}
function guardaf3007(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu05id.value;
	valores[2]=window.document.frmedita.saiu07consec.value;
	valores[3]=window.document.frmedita.saiu07id.value;
	valores[4]=window.document.frmedita.saiu07idtipoanexo.value;
	valores[5]=window.document.frmedita.saiu07detalle.value;
	valores[8]=window.document.frmedita.saiu07idusuario.value;
	valores[9]=window.document.frmedita.saiu07fecha.value;
	valores[10]=window.document.frmedita.saiu07hora.value;
	valores[11]=window.document.frmedita.saiu07minuto.value;
	valores[12]=window.document.frmedita.saiu07estado.value;
	valores[13]=window.document.frmedita.saiu07idvalidad.value;
	valores[14]=window.document.frmedita.saiu07fechavalida.value;
	valores[15]=window.document.frmedita.saiu07horavalida.value;
	valores[16]=window.document.frmedita.saiu07minvalida.value;
	params[0]=window.document.frmedita.saiu05id.value;
	//params[1]=window.document.frmedita.p1_3007.value;
	params[97]=window.document.frmedita.saiu05agno.value;
	params[98]=window.document.frmedita.saiu05mes.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3007.value;
	params[102]=window.document.frmedita.lppf3007.value;
	xajax_f3007_Guardar(valores, params);
	}
function limpiaf3007(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3007_PintarLlaves(params);
	window.document.frmedita.saiu07idtipoanexo.value='';
	window.document.frmedita.saiu07detalle.value='';
	limpia_saiu07idarchivo();
	verboton('banexasaiu07idarchivo','none');
	window.document.frmedita.saiu07idusuario.value=0;
	window.document.frmedita.saiu07idusuario_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.saiu07idusuario_doc.value='';
	document.getElementById('div_saiu07idusuario').innerHTML='';
	//ter_traerxid('saiu07idusuario', window.document.frmedita.idusuario.value);
	fecha_AsignarNum('saiu07fecha', iFechaBaseNum);
	window.document.frmedita.saiu07idvalidad.value=0;
	window.document.frmedita.saiu07idvalidad_td.value=window.document.frmedita.stipodoc.value;
	window.document.frmedita.saiu07idvalidad_doc.value='';
	document.getElementById('div_saiu07idvalidad').innerHTML='';
	//ter_traerxid('saiu07idvalidad', window.document.frmedita.idusuario.value);
	fecha_AsignarNum('saiu07fechavalida', iFechaBaseNum);
	verboton('belimina3007','none');
	}
function eliminaf3007(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu05id.value;
	params[1]=window.document.frmedita.saiu05id.value;
	params[2]=window.document.frmedita.saiu07consec.value;
	params[3]=window.document.frmedita.saiu07id.value;
	//params[18]=window.document.frmedita.p1_3007.value;
	params[97]=window.document.frmedita.saiu05agno.value;
	params[98]=window.document.frmedita.saiu05mes.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3007.value;
	params[102]=window.document.frmedita.lppf3007.value;
	if (window.document.frmedita.saiu07id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3007_Eliminar(params);
			}
		}
	}
function revisaf3007(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu05id.value;
	params[2]=window.document.frmedita.saiu07consec.value;
	params[3]=window.document.frmedita.saiu07id.value;
	if ((params[2]!='')){
		xajax_f3007_Traer(params);
		}
	}
function cargadatof3007(llave1){
	window.document.frmedita.saiu07consec.value=String(llave1);
	revisaf3007();
	}
function cargaridf3007(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3007_Traer(params);
	expandepanel(3007,'block',0);
	}
function paginarf3007(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu05id.value;
	params[97]=window.document.frmedita.saiu05agno.value;
	params[98]=window.document.frmedita.saiu05mes.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3007.value;
	params[102]=window.document.frmedita.lppf3007.value;
	//params[103]=window.document.frmedita.bnombre3007.value;
	//params[104]=window.document.frmedita.blistar3007.value;
	document.getElementById('div_f3007detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3007" name="paginaf3007" type="hidden" value="'+params[101]+'" /><input id="lppf3007" name="lppf3007" type="hidden" value="'+params[102]+'" />';
	xajax_f3007_HtmlTabla(params);
	}
function imprime3007(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3007.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3007.value;
	window.document.frmlista.nombrearchivo.value='Anexos';
	window.document.frmlista.submit();
	}
