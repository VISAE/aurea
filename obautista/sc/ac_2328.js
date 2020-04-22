// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.23.6 Friday, September 20, 2019
$().ready(function(){
$("#cara28idresponsable_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara28idresponsable_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara28idresponsable_td").val(data[2]);
		$("#cara28idresponsable_doc").val(data[1]);
		ter_muestra('cara28idresponsable', 0);
		}
	});
$("#cara29idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara29idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara29idtercero_td").val(data[2]);
		$("#cara29idtercero_doc").val(data[1]);
		ter_muestra('cara29idtercero', 1);
		}
	});
});