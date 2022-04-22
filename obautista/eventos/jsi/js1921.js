// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Encuestas aplicadas
// --- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
function cod_even21idcurso(){
	var dcod=window.document.frmedita.even21idcurso_cod.value.trim();
	window.document.frmedita.even21idcurso.value='0';
	if (dcod!=''){
		var params=new Array();
		params[0]=dcod;
		params[1]='even21idcurso';
		params[2]='div_even21idcurso';
		params[9]=window.document.frmedita.debug.value;
		xajax_TraerBusqueda_even21idcurso(params);
		}else{
		document.getElementById('div_even21idcurso').innerHTML='';
		}
	}
function guardaf1921(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even16id.value;
	valores[2]=window.document.frmedita.even21idtercero.value;
	valores[3]=window.document.frmedita.even21idperaca.value;
	valores[4]=window.document.frmedita.even21idcurso.value;
	valores[5]=window.document.frmedita.even21idbloquedo.value;
	valores[6]=window.document.frmedita.even21id.value;
	valores[7]=window.document.frmedita.even21fechapresenta.value;
	valores[8]=window.document.frmedita.even21terminada.value;
	params[0]=window.document.frmedita.even16id.value;
	//params[1]=window.document.frmedita.p1_1921.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1921.value;
	params[102]=window.document.frmedita.lppf1921.value;
	xajax_f1921_Guardar(valores, params);
	}
function limpiaf1921(){
	var sfbase=window.document.frmedita.shoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1921_PintarLlaves(params);
	fecha_asignar('even21fechapresenta',sfbase);
	window.document.frmedita.even21terminada.value='';
	verboton('belimina1921','none');
	}
function eliminaf1921(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even21idtercero.value;
	params[3]=window.document.frmedita.even21idperaca.value;
	params[4]=window.document.frmedita.even21idcurso.value;
	params[5]=window.document.frmedita.even21idbloquedo.value;
	params[6]=window.document.frmedita.even21id.value;
	//params[10]=window.document.frmedita.p1_1921.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1921.value;
	params[102]=window.document.frmedita.lppf1921.value;
	if (window.document.frmedita.even21id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1921_Eliminar(params);
			}
		}
	}
function revisaf1921(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even16id.value;
	params[2]=window.document.frmedita.even21idtercero.value;
	params[3]=window.document.frmedita.even21idperaca.value;
	params[4]=window.document.frmedita.even21idcurso.value;
	params[5]=window.document.frmedita.even21idbloquedo.value;
	params[6]=window.document.frmedita.even21id.value;
	if ((params[2]!='')&&(params[3]!='')&&(params[4]!='')&&(params[5]!='')){
		xajax_f1921_Traer(params);
		}
	}
function cargadatof1921(llave1, llave2, llave3, llave4){
	window.document.frmedita.even21idtercero.value=String(llave1);
	window.document.frmedita.even21idperaca.value=String(llave2);
	window.document.frmedita.even21idcurso.value=String(llave3);
	window.document.frmedita.even21idbloquedo.value=String(llave4);
	revisaf1921();
	}
function cargaridf1921(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1921_Traer(params);
	expandepanel(1921,'block',0);
	}
function paginarf1921(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1921.value;
	params[102]=window.document.frmedita.lppf1921.value;
	params[103]=window.document.frmedita.bdoc1921.value;
	params[104]=window.document.frmedita.bnombre1921.value;
	params[105]=window.document.frmedita.bperaca1921.value;
	params[106]=window.document.frmedita.bcodcurso1921.value;
	xajax_f1921_HtmlTabla(params);
	}
function imprime1921(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1921.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1921.value;
	window.document.frmlista.nombrearchivo.value='Encuestas aplicadas';
	window.document.frmlista.submit();
	}
