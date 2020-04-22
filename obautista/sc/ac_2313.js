// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.22.3 lunes, 6 de agosto de 2018
$().ready(function(){
$("#cara13idconsejero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara13idconsejero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara13idconsejero_td").val(data[2]);
		$("#cara13idconsejero_doc").val(data[1]);
		ter_muestra('cara13idconsejero', 1);
		}
	});
});