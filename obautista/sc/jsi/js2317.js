// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anexos
// --- Modelo Versión 2.22.3 jueves, 16 de agosto de 2018
function limpia_cara17idanexo(){
	window.document.frmedita.cara17idorigen.value=0;
	window.document.frmedita.cara17idanexo.value=0;
	var da_Anexo=document.getElementById('div_cara17idanexo');
	da_Anexo.innerHTML='&nbsp;';
	verboton('beliminacara17idanexo','none');
	//paginarf0000();
	}
function carga_cara17idanexo(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_2308.value+' - Cargar archivo</h2>';
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivo.php?ref=2317&id='+window.document.frmedita.cara17id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
function eliminacara17idanexo(){
	var did=window.document.frmedita.cara17id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_cara17idanexo(did.value);
		//paginarf0000();
		}
	}
function guardaf2317(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.cara08id.value;
	valores[2]=window.document.frmedita.cara17consec.value;
	valores[3]=window.document.frmedita.cara17id.value;
	valores[6]=window.document.frmedita.cara17nombre.value;
	params[0]=window.document.frmedita.cara08id.value;
	//params[1]=window.document.frmedita.p1_2317.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2317.value;
	params[102]=window.document.frmedita.lppf2317.value;
	xajax_f2317_Guardar(valores, params);
	}
function limpiaf2317(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2317_PintarLlaves(params);
	limpia_cara17idanexo();
	verboton('banexacara17idanexo','none');
	window.document.frmedita.cara17nombre.value='';
	verboton('belimina2317','none');
	}
function eliminaf2317(){
	var params=new Array();
	params[0]=window.document.frmedita.cara08id.value;
	params[1]=window.document.frmedita.cara08id.value;
	params[2]=window.document.frmedita.cara17consec.value;
	params[3]=window.document.frmedita.cara17id.value;
	//params[8]=window.document.frmedita.p1_2317.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2317.value;
	params[102]=window.document.frmedita.lppf2317.value;
	if (window.document.frmedita.cara17id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f2317_Eliminar(params);
			}
		}
	}
function revisaf2317(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.cara08id.value;
	params[2]=window.document.frmedita.cara17consec.value;
	params[3]=window.document.frmedita.cara17id.value;
	if ((params[2]!='')){
		xajax_f2317_Traer(params);
		}
	}
function cargadatof2317(llave1){
	window.document.frmedita.cara17consec.value=String(llave1);
	revisaf2317();
	}
function cargaridf2317(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2317_Traer(params);
	expandepanel(2317,'block',0);
	}
function paginarf2317(){
	var params=new Array();
	params[0]=window.document.frmedita.cara08id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2317.value;
	params[102]=window.document.frmedita.lppf2317.value;
	//params[103]=window.document.frmedita.bnombre2317.value;
	//params[104]=window.document.frmedita.blistar2317.value;
	document.getElementById('div_f2317detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2317" name="paginaf2317" type="hidden" value="'+params[101]+'" /><input id="lppf2317" name="lppf2317" type="hidden" value="'+params[102]+'" />';
	xajax_f2317_HtmlTabla(params);
	}
function imprime2317(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2317.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2317.value;
	window.document.frmlista.nombrearchivo.value='Anexos';
	window.document.frmlista.submit();
	}
