// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.22.0 martes, 26 de junio de 2018
$().ready(function(){
$("#cara01idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara01idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara01idtercero_td").val(data[2]);
		$("#cara01idtercero_doc").val(data[1]);
		ter_muestra('cara01idtercero', 1);
		}
	});
$("#cara01idconfirmadesp_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara01idconfirmadesp_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara01idconfirmadesp_td").val(data[2]);
		$("#cara01idconfirmadesp_doc").val(data[1]);
		ter_muestra('cara01idconfirmadesp', 0);
		}
	});
$("#cara01idconfirmacr_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara01idconfirmacr_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara01idconfirmacr_td").val(data[2]);
		$("#cara01idconfirmacr_doc").val(data[1]);
		ter_muestra('cara01idconfirmacr', 0);
		}
	});
$("#cara01idconfirmadisc_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara01idconfirmadisc_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara01idconfirmadisc_td").val(data[2]);
		$("#cara01idconfirmadisc_doc").val(data[1]);
		ter_muestra('cara01idconfirmadisc', 0);
		}
	});
$("#cara01idconsejero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara01idconsejero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara01idconsejero_td").val(data[2]);
		$("#cara01idconsejero_doc").val(data[1]);
		ter_muestra('cara01idconsejero', 0);
		}
	});
});