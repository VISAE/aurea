// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.25.10c jueves, 4 de febrero de 2021
$().ready(function(){
//$("#saiu23agno").chosen();
//$("#saiu23mes").chosen();
//$("#saiu23estado").chosen();
//$("#saiu23idmedio").chosen();
//$("#saiu23idtema").chosen();
//$("#saiu23idtiposol").chosen();
$("#saiu23idsolicitante_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu23idsolicitante_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu23idsolicitante_td").val(data[2]);
		$("#saiu23idsolicitante_doc").val(data[1]);
		ter_muestra('saiu23idsolicitante', 0);
		}
	});
//$("#saiu23idzona").chosen();
//$("#saiu23cead").chosen();
$("#saiu23idresponsable_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu23idresponsable_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu23idresponsable_td").val(data[2]);
		$("#saiu23idresponsable_doc").val(data[1]);
		ter_muestra('saiu23idresponsable', 0);
		}
	});
//$("#saiu23idescuela").chosen();
//$("#saiu23idprograma").chosen();
//$("#saiu23idperiodo").chosen();
//$("#saiu23idcurso").chosen();
});