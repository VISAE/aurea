// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.24.1 domingo, 23 de febrero de 2020
$().ready(function(){
$("#saiu05idsolicitante_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu05idsolicitante_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu05idsolicitante_td").val(data[2]);
		$("#saiu05idsolicitante_doc").val(data[1]);
		ter_muestra('saiu05idsolicitante', 0);
		}
	});
$("#saiu05idinteresado_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu05idinteresado_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu05idinteresado_td").val(data[2]);
		$("#saiu05idinteresado_doc").val(data[1]);
		ter_muestra('saiu05idinteresado', 0);
		}
	});
$("#saiu05idresponsable_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu05idresponsable_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu05idresponsable_td").val(data[2]);
		$("#saiu05idresponsable_doc").val(data[1]);
		ter_muestra('saiu05idresponsable', 0);
		}
	});
$("#saiu06idusuario_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu06idusuario_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu06idusuario_td").val(data[2]);
		$("#saiu06idusuario_doc").val(data[1]);
		ter_muestra('saiu06idusuario', 0);
		}
	});
//$("#saiu07idtipoanexo").chosen();
$("#saiu07idusuario_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu07idusuario_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu07idusuario_td").val(data[2]);
		$("#saiu07idusuario_doc").val(data[1]);
		ter_muestra('saiu07idusuario', 0);
		}
	});
//$("#saiu07estado").chosen();
$("#saiu07idvalidad_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu07idvalidad_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu07idvalidad_td").val(data[2]);
		$("#saiu07idvalidad_doc").val(data[1]);
		ter_muestra('saiu07idvalidad', 0);
		}
	});
});