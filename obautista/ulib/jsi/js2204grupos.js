// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Matricula
// --- Modelo Versión 2.23.1 lunes, 22 de abril de 2019
function cod_core04idcurso(){
	var dcod=window.document.frmedita.core04idcurso_cod.value.trim();
	window.document.frmedita.core04idcurso.value=0;
	if (dcod!=''){
		var params=new Array();
		params[0]=dcod;
		params[1]='core04idcurso';
		params[2]='div_core04idcurso';
		params[9]=window.document.frmedita.debug.value;
		xajax_f2204_Busqueda_core04idcurso(params);
		}else{
		document.getElementById('div_core04idcurso').innerHTML='';
		}
	}
function guardaf2204(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.core06id.value;
	valores[2]=window.document.frmedita.core04peraca.value;
	valores[3]=window.document.frmedita.core04tercero.value;
	valores[4]=window.document.frmedita.core04idcurso.value;
	valores[5]=window.document.frmedita.core04id.value;
	valores[6]=window.document.frmedita.core04idaula.value;
	valores[7]=window.document.frmedita.core04idrol.value;
	valores[8]=window.document.frmedita.core04idnav.value;
	valores[9]=window.document.frmedita.core04estadoengrupo.value;
	params[0]=window.document.frmedita.core06id.value;
	//params[1]=window.document.frmedita.p1_2204.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2204.value;
	params[102]=window.document.frmedita.lppf2204.value;
	xajax_f2204_Guardar(valores, params);
	}
function limpiaf2204(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2204_PintarLlaves(params);
	window.document.frmedita.core04idaula.value='';
	window.document.frmedita.core04idrol.value='';
	window.document.frmedita.core04idnav.value='';
	verboton('belimina2204','none');
	}
function eliminaf2204(){
	var params=new Array();
	params[0]=window.document.frmedita.core06id.value;
	params[1]=window.document.frmedita.core06id.value;
	params[2]=window.document.frmedita.core04peraca.value;
	params[3]=window.document.frmedita.core04tercero.value;
	params[4]=window.document.frmedita.core04idcurso.value;
	params[5]=window.document.frmedita.core04id.value;
	//params[11]=window.document.frmedita.p1_2204.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2204.value;
	params[102]=window.document.frmedita.lppf2204.value;
	if (window.document.frmedita.core04id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f2204_Eliminar(params);
			}
		}
	}
function revisaf2204(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.core06id.value;
	params[2]=window.document.frmedita.core04peraca.value;
	params[3]=window.document.frmedita.core04tercero.value;
	params[4]=window.document.frmedita.core04idcurso.value;
	params[5]=window.document.frmedita.core04id.value;
	if ((params[2]!='')&&(params[3]!='')&&(params[4]!='')){
		xajax_f2204_Traer(params);
		}
	}
function cargadatof2204(llave1, llave2, llave3){
	window.document.frmedita.core04peraca.value=String(llave1);
	window.document.frmedita.core04tercero.value=String(llave2);
	window.document.frmedita.core04idcurso.value=String(llave3);
	revisaf2204();
	}
function cargaridf2204(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2204_Traer(params);
	expandepanel(2204,'block',0);
	}
function paginarf2204(){
	var params=new Array();
	params[0]=window.document.frmedita.core06id.value;
	params[1]=window.document.frmedita.core06peraca.value;
	params[2]=window.document.frmedita.core06idcurso.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2204.value;
	params[102]=window.document.frmedita.lppf2204.value;
	//params[103]=window.document.frmedita.bnombre2204.value;
	//params[104]=window.document.frmedita.blistar2204.value;
	document.getElementById('div_f2204detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2204" name="paginaf2204" type="hidden" value="'+params[101]+'" /><input id="lppf2204" name="lppf2204" type="hidden" value="'+params[102]+'" />';
	xajax_f2204_HtmlTabla(params);
	}
function imprime2204(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2204.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2204.value;
	window.document.frmlista.nombrearchivo.value='Matricula';
	window.document.frmlista.submit();
	}
