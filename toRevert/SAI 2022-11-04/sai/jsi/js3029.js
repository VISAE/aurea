// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anexos Mesa de ayuda
// --- Modelo Versión 2.25.5 jueves, 13 de agosto de 2020
function limpia_saiu29idarchivo(){
	window.document.frmedita.saiu29idorigen.value=0;
	window.document.frmedita.saiu29idarchivo.value=0;
	var da_Archivo=document.getElementById('div_saiu29idarchivo');
	da_Archivo.innerHTML='&nbsp;';
	verboton('beliminasaiu29idarchivo','none');
	//paginarf0000();
	}
function carga_saiu29idarchivo(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	if (window.document.frmedita.ipiel.value==2) {
		document.getElementById('div_96titulo').innerHTML=''+window.document.frmedita.titulo_3028.value+' - Cargar archivo';
		}else{
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3028.value+' - Cargar archivo</h2>';
		}
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3029&id='+window.document.frmedita.saiu29id.value+'&tabla=_'+window.document.frmedita.saiu28agno.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
function eliminasaiu29idarchivo(){
	var did=window.document.frmedita.saiu29id.value;
	var dagno=window.document.frmedita.saiu28agno.value;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_saiu29idarchivo(did, dagno);
		//paginarf3209();
		}
	}
function guardaf3029(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu28id.value;
	valores[2]=window.document.frmedita.saiu29idanexo.value;
	valores[3]=window.document.frmedita.saiu29consec.value;
	valores[4]=window.document.frmedita.saiu29id.value;
	valores[7]=window.document.frmedita.saiu29detalle.value;
	valores[98]=window.document.frmedita.saiu28agno.value;
	params[0]=window.document.frmedita.saiu28id.value;
	//params[1]=window.document.frmedita.p1_3029.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3029.value;
	params[102]=window.document.frmedita.lppf3029.value;
	xajax_f3029_Guardar(valores, params);
	}
function limpiaf3029(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3029_PintarLlaves(params);
	limpia_saiu29idarchivo();
	verboton('banexasaiu29idarchivo','none');
	window.document.frmedita.saiu29detalle.value='';
	verboton('belimina3029','none');
	}
function eliminaf3029(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28id.value;
	params[1]=window.document.frmedita.saiu28id.value;
	params[2]=window.document.frmedita.saiu29idanexo.value;
	params[3]=window.document.frmedita.saiu29consec.value;
	params[4]=window.document.frmedita.saiu29id.value;
	//params[9]=window.document.frmedita.p1_3029.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3029.value;
	params[102]=window.document.frmedita.lppf3029.value;
	if (window.document.frmedita.saiu29id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3029_Eliminar(params);
			}
		}
	}
function revisaf3029(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu28id.value;
	params[2]=window.document.frmedita.saiu29idanexo.value;
	params[3]=window.document.frmedita.saiu29consec.value;
	params[4]=window.document.frmedita.saiu29id.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	if ((params[2]!='')&&(params[3]!='')){
		xajax_f3029_Traer(params);
		}
	}
function cargadatof3029(llave1, llave2){
	window.document.frmedita.saiu29idanexo.value=String(llave1);
	window.document.frmedita.saiu29consec.value=String(llave2);
	revisaf3029();
	}
function cargaridf3029(llave1){
	var params=new Array();
	params[0]=2;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[103]=llave1;
	xajax_f3029_Traer(params);
	expandepanel(3029,'block',0);
	}
function paginarf3029(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28id.value;
	params[98]=window.document.frmedita.saiu28agno.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3029.value;
	params[102]=window.document.frmedita.lppf3029.value;
	//params[103]=window.document.frmedita.bnombre3029.value;
	//params[104]=window.document.frmedita.blistar3029.value;
	document.getElementById('div_f3029detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3029" name="paginaf3029" type="hidden" value="'+params[101]+'" /><input id="lppf3029" name="lppf3029" type="hidden" value="'+params[102]+'" />';
	xajax_f3029_HtmlTabla(params);
	}
function imprime3029(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3029.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3029.value;
	window.document.frmlista.nombrearchivo.value='Anexos Mesa de ayuda';
	window.document.frmlista.submit();
	}
