// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Anexos
// --- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
function limpia_saiu04idarchforma(){
	window.document.frmedita.saiu04idorigenforma.value=0;
	window.document.frmedita.saiu04idarchforma.value=0;
	var da_Archforma=document.getElementById('div_saiu04idarchforma');
	da_Archforma.innerHTML='&nbsp;';
	verboton('beliminasaiu04idarchforma','none');
	//paginarf0000();
	}
function carga_saiu04idarchforma(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3003.value+' - Cargar archivo</h2>';
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivo.php?ref=3004&id='+window.document.frmedita.saiu04id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
function eliminasaiu04idarchforma(){
	var did=window.document.frmedita.saiu04id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_saiu04idarchforma(did.value);
		//paginarf0000();
		}
	}
function guardaf3004(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.saiu03id.value;
	valores[2]=window.document.frmedita.saiu04consec.value;
	valores[3]=window.document.frmedita.saiu04id.value;
	valores[4]=window.document.frmedita.saiu04activo.value;
	valores[5]=window.document.frmedita.saiu04orden.value;
	valores[6]=window.document.frmedita.saiu04obligatorio.value;
	valores[7]=window.document.frmedita.saiu04titulo.value;
	valores[8]=window.document.frmedita.saiu04descripcion.value;
	valores[9]=window.document.frmedita.saiu04idtipogd.value;
	valores[12]=window.document.frmedita.saiu04idetapa.value;
	params[0]=window.document.frmedita.saiu03id.value;
	//params[1]=window.document.frmedita.p1_3004.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3004.value;
	params[102]=window.document.frmedita.lppf3004.value;
	xajax_f3004_Guardar(valores, params);
	}
function limpiaf3004(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f3004_PintarLlaves(params);
	window.document.frmedita.saiu04activo.value='S';
	window.document.frmedita.saiu04orden.value='';
	window.document.frmedita.saiu04obligatorio.value='S';
	window.document.frmedita.saiu04titulo.value='';
	window.document.frmedita.saiu04descripcion.value='';
	limpia_saiu04idarchforma();
	verboton('banexasaiu04idarchforma','none');
	window.document.frmedita.saiu04idetapa.value=1;
	verboton('belimina3004','none');
	}
function eliminaf3004(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu03id.value;
	params[1]=window.document.frmedita.saiu03id.value;
	params[2]=window.document.frmedita.saiu04consec.value;
	params[3]=window.document.frmedita.saiu04id.value;
	//params[13]=window.document.frmedita.p1_3004.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3004.value;
	params[102]=window.document.frmedita.lppf3004.value;
	if (window.document.frmedita.saiu04id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f3004_Eliminar(params);
			}
		}
	}
function revisaf3004(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.saiu03id.value;
	params[2]=window.document.frmedita.saiu04consec.value;
	params[3]=window.document.frmedita.saiu04id.value;
	if ((params[2]!='')){
		xajax_f3004_Traer(params);
		}
	}
function cargadatof3004(llave1){
	window.document.frmedita.saiu04consec.value=String(llave1);
	revisaf3004();
	}
function cargaridf3004(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f3004_Traer(params);
	expandepanel(3004,'block',0);
	}
function paginarf3004(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu03id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3004.value;
	params[102]=window.document.frmedita.lppf3004.value;
	//params[103]=window.document.frmedita.bnombre3004.value;
	//params[104]=window.document.frmedita.blistar3004.value;
	document.getElementById('div_f3004detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3004" name="paginaf3004" type="hidden" value="'+params[101]+'" /><input id="lppf3004" name="lppf3004" type="hidden" value="'+params[102]+'" />';
	xajax_f3004_HtmlTabla(params);
	}
function imprime3004(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_3004.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_3004.value;
	window.document.frmlista.nombrearchivo.value='Anexos';
	window.document.frmlista.submit();
	}
