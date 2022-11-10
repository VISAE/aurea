// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Manuales
// --- Modelo Versión 2.25.10c lunes, 5 de abril de 2021
function limpia_saiu55idarchivo(){
	window.document.frmedita.saiu55idorigen.value=0;
	window.document.frmedita.saiu55idarchivo.value=0;
	var da_Archivo=document.getElementById('div_saiu55idarchivo');
	da_Archivo.innerHTML='&nbsp;';
	verboton('beliminasaiu55idarchivo','none');
	//paginarf0000();
	}
function carga_saiu55idarchivo(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	if (window.document.frmedita.ipiel.value==2) {
		document.getElementById('div_96titulo').innerHTML=''+window.document.frmedita.titulo_3053.value+' - Cargar archivo';
		}else{
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3053.value+' - Cargar archivo</h2>';
		}
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivo.php?ref=3055&id='+window.document.frmedita.saiu55id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
function eliminasaiu55idarchivo(){
	var did=window.document.frmedita.saiu55id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_saiu55idarchivo(did.value);
		//paginarf0000();
		}
	}
function guardaf3055(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu53id.value;
	valores[2]=window.document.frmedita.saiu55consec.value;
	valores[3]=window.document.frmedita.saiu55id.value;
	valores[4]=window.document.frmedita.saiu55fecha.value;
	valores[5]=window.document.frmedita.saiu55infoversion.value;
	valores[6]=window.document.frmedita.saiu55formaenlace.value;
	valores[7]=window.document.frmedita.saiu55ruta.value;
	params[0]=window.document.frmedita.saiu53id.value;
	//params[1]=window.document.frmedita.p1_3055.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3055.value;
	params[102]=window.document.frmedita.lppf3055.value;
	xajax_f3055_Guardar(valores, params);
	}
function limpiaf3055(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3055_PintarLlaves(params);
	fecha_AsignarNum('saiu55fecha', iFechaBaseNum);
	window.document.frmedita.saiu55infoversion.value='';
	window.document.frmedita.saiu55formaenlace.value=0;
	window.document.frmedita.saiu55ruta.value='';
	limpia_saiu55idarchivo();
	verboton('banexasaiu55idarchivo','none');
	verboton('belimina3055','none');
	}
function eliminaf3055(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu53id.value;
	params[1]=window.document.frmedita.saiu53id.value;
	params[2]=window.document.frmedita.saiu55consec.value;
	params[3]=window.document.frmedita.saiu55id.value;
	//params[11]=window.document.frmedita.p1_3055.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3055.value;
	params[102]=window.document.frmedita.lppf3055.value;
	if (window.document.frmedita.saiu55id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3055_Eliminar(params);
			}
		}
	}
function revisaf3055(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu53id.value;
	params[2]=window.document.frmedita.saiu55consec.value;
	params[3]=window.document.frmedita.saiu55id.value;
	if ((params[2]!='')){
		xajax_f3055_Traer(params);
		}
	}
function cargadatof3055(llave1){
	window.document.frmedita.saiu55consec.value=String(llave1);
	revisaf3055();
	}
function cargaridf3055(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3055_Traer(params);
	expandepanel(3055,'block',0);
	}
function paginarf3055(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu53id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3055.value;
	params[102]=window.document.frmedita.lppf3055.value;
	//params[103]=window.document.frmedita.bnombre3055.value;
	//params[104]=window.document.frmedita.blistar3055.value;
	document.getElementById('div_f3055detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3055" name="paginaf3055" type="hidden" value="'+params[101]+'" /><input id="lppf3055" name="lppf3055" type="hidden" value="'+params[102]+'" />';
	xajax_f3055_HtmlTabla(params);
	}
function imprime3055(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3055.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3055.value;
	window.document.frmlista.nombrearchivo.value='Manuales';
	window.document.frmlista.submit();
	}
