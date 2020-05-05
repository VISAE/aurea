// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anotaciones
// --- Modelo Versión 2.22.4 miércoles, 29 de agosto de 2018
function limpia_unae07idarchivo(){
	window.document.frmedita.unae07idcont.value=0;
	window.document.frmedita.unae07idarchivo.value=0;
	var da_Archivo=document.getElementById('div_unae07idarchivo');
	da_Archivo.innerHTML='&nbsp;';
	verboton('beliminaunae07idarchivo','none');
	//paginarf0000();
	}
function carga_unae07idarchivo(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_203.value+' - Cargar archivo</h2>';
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivo.php?ref=207&id='+window.document.frmedita.unae07id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
function eliminaunae07idarchivo(){
	var did=window.document.frmedita.unae07id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_unae07idarchivo(did.value);
		//paginarf0000();
		}
	}
function guardaf207(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.unae03id.value;
	valores[2]=window.document.frmedita.unae07consec.value;
	valores[3]=window.document.frmedita.unae07id.value;
	valores[4]=window.document.frmedita.unae07idtercero.value;
	valores[5]=window.document.frmedita.unae07fecha.value;
	valores[6]=window.document.frmedita.unae07minuto.value;
	valores[7]=window.document.frmedita.unae07nota.value;
	params[0]=window.document.frmedita.unae03id.value;
	//params[1]=window.document.frmedita.p1_207.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf207.value;
	params[102]=window.document.frmedita.lppf207.value;
	xajax_f207_Guardar(valores, params);
	}
function limpiaf207(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f207_PintarLlaves(params);
	window.document.frmedita.unae07nota.value='';
	limpia_unae07idarchivo();
	verboton('banexaunae07idarchivo','none');
	verboton('belimina207','none');
	}
function eliminaf207(){
	var params=new Array();
	params[0]=window.document.frmedita.unae03id.value;
	params[1]=window.document.frmedita.unae03id.value;
	params[2]=window.document.frmedita.unae07consec.value;
	params[3]=window.document.frmedita.unae07id.value;
	//params[11]=window.document.frmedita.p1_207.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf207.value;
	params[102]=window.document.frmedita.lppf207.value;
	if (window.document.frmedita.unae07id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f207_Eliminar(params);
			}
		}
	}
function revisaf207(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unae03id.value;
	params[2]=window.document.frmedita.unae07consec.value;
	params[3]=window.document.frmedita.unae07id.value;
	if ((params[2]!='')){
		xajax_f207_Traer(params);
		}
	}
function cargadatof207(llave1){
	window.document.frmedita.unae07consec.value=String(llave1);
	revisaf207();
	}
function cargaridf207(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f207_Traer(params);
	expandepanel(207,'block',0);
	}
function paginarf207(){
	var params=new Array();
	params[0]=window.document.frmedita.unae03id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf207.value;
	params[102]=window.document.frmedita.lppf207.value;
	//params[103]=window.document.frmedita.bnombre207.value;
	//params[104]=window.document.frmedita.blistar207.value;
	document.getElementById('div_f207detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf207" name="paginaf207" type="hidden" value="'+params[101]+'" /><input id="lppf207" name="lppf207" type="hidden" value="'+params[102]+'" />';
	xajax_f207_HtmlTabla(params);
	}
function imprime207(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_207.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_207.value;
	window.document.frmlista.nombrearchivo.value='Anotaciones';
	window.document.frmlista.submit();
	}
