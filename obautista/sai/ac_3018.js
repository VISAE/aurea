// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.25.3 martes, 14 de julio de 2020
$().ready(function(){
//$("#saiu18tiporadicado").chosen();
//$("#saiu18estado").chosen();
$("#saiu18idsolicitante_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu18idsolicitante_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu18idsolicitante_td").val(data[2]);
		$("#saiu18idsolicitante_doc").val(data[1]);
		ter_muestra('saiu18idsolicitante', 0);
		}
	});
//$("#saiu18tipointeresado").chosen();
//$("#saiu18temasolicitud").chosen();
//$("#saiu05idescuela").chosen();
//$("#saiu05idprograma").chosen();
//$("#saiu05idperiodo").chosen();
$("#saiu05idresponsable_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu05idresponsable_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu05idresponsable_td").val(data[2]);
		$("#saiu05idresponsable_doc").val(data[1]);
		ter_muestra('saiu05idresponsable', 0);
		}
	});
});