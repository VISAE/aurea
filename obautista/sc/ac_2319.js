// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.22.3 lunes, 27 de agosto de 2018
// --- Modelo Versión 2.24.1 viernes, 31 de enero de 2020
$().ready(function(){
$("#caraidtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#caraidtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#caraidtercero_td").val(data[2]);
		$("#caraidtercero_doc").val(data[1]);
		ter_muestra('caraidtercero', 0);
		}
	});
$("#bperiodo").chosen();
});