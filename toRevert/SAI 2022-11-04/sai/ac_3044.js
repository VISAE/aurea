// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.25.10c domingo, 7 de marzo de 2021
$().ready(function(){
$("#saiu44idsolicitante_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu44idsolicitante_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu44idsolicitante_td").val(data[2]);
		$("#saiu44idsolicitante_doc").val(data[1]);
		ter_muestra('saiu44idsolicitante', 0);
		}
	});
//$("#saiu44idtiposol").chosen();
});