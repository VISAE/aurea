// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.22.3 lunes, 6 de agosto de 2018
$().ready(function(){
$("#cara01idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara01idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara01idtercero_td").val(data[2]);
		$("#cara01idtercero_doc").val(data[1]);
		ter_muestra('cara01idtercero', 1);
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