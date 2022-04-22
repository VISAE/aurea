// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Cursos que aplican
// --- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
function cod_even25idcurso(){
	var dcod=window.document.frmedita.even25idcurso_cod.value.trim();
	window.document.frmedita.even25idcurso.value='0';
	if (dcod!=''){
		var params=new Array();
		params[0]=dcod;
		params[1]='even25idcurso';
		params[2]='div_even25idcurso';
		params[9]=window.document.frmedita.debug.value;
		xajax_TraerBusqueda_even25idcurso(params);
		}else{
		document.getElementById('div_even25idcurso').innerHTML='';
		}
	}
function guardaf1925(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even16id.value;
	valores[2]=window.document.frmedita.even25idcurso.value;
	valores[3]=window.document.frmedita.even25id.value;
	valores[4]=window.document.frmedita.even25activo.value;
	params[0]=window.document.frmedita.even16id.value;
	//params[1]=window.document.frmedita.p1_1925.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1925.value;
	params[102]=window.document.frmedita.lppf1925.value;
	xajax_f1925_Guardar(valores, params);
	}
function limpiaf1925(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1925_PintarLlaves(params);
	window.document.frmedita.even25activo.value='S';
	verboton('belimina1925','none');
	}
function eliminaf1925(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even25idcurso.value;
	params[3]=window.document.frmedita.even25id.value;
	//params[6]=window.document.frmedita.p1_1925.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1925.value;
	params[102]=window.document.frmedita.lppf1925.value;
	if (window.document.frmedita.even25id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1925_Eliminar(params);
			}
		}
	}
function revisaf1925(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even25idcurso.value;
	params[3]=window.document.frmedita.even25id.value;
	if ((params[2]!='')){
		xajax_f1925_Traer(params);
		}
	}
function cargadatof1925(llave1){
	window.document.frmedita.even25idcurso.value=String(llave1);
	revisaf1925();
	}
function cargaridf1925(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1925_Traer(params);
	expandepanel(1925,'block',0);
	}
function paginarf1925(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1925.value;
	params[102]=window.document.frmedita.lppf1925.value;
	//params[103]=window.document.frmedita.bnombre1925.value;
	//params[104]=window.document.frmedita.blistar1925.value;
	xajax_f1925_HtmlTabla(params);
	}
function imprime1925(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1925.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1925.value;
	window.document.frmlista.nombrearchivo.value='Cursos que aplican';
	window.document.frmlista.submit();
	}
