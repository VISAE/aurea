// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.23.6 Wednesday, October 9, 2019
$().ready(function(){
$("#plab01idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#plab01idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#plab01idtercero_td").val(data[2]);
		$("#plab01idtercero_doc").val(data[1]);
		ter_muestra('plab01idtercero', 1);
		}
	});
});