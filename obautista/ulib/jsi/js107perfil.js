// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Perfiles
// --- Modelo Versión 2.24.1 jueves, 30 de enero de 2020
function paginarf107(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf107.value;
	params[102]=window.document.frmedita.lppf107.value;
	//params[103]=window.document.frmedita.bnombre107.value;
	//params[104]=window.document.frmedita.blistar107.value;
	document.getElementById('div_f107detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf107" name="paginaf107" type="hidden" value="'+params[101]+'" /><input id="lppf107" name="lppf107" type="hidden" value="'+params[102]+'" />';
	xajax_f107_HtmlTablaPerfil(params);
	}
