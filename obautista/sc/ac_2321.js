// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.22.6d lunes, 21 de enero de 2019
$().ready(function(){
$("#cara21idlider_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara21idlider_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara21idlider_td").val(data[2]);
		$("#cara21idlider_doc").val(data[1]);
		ter_muestra('cara21idlider', 1);
		}
	});
});