// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
$().ready(function(){
//$("#saiu03tiposol").chosen();
//$("#saiu03activo").chosen();
//$("#saiu03obligaconf").chosen();
//$("#saiu03numetapas").chosen();
//$("#saiu03idunidadresp1").chosen();
//$("#saiu03idequiporesp1").chosen();
$("#saiu03idliderrespon1_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu03idliderrespon1_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu03idliderrespon1_td").val(data[2]);
		$("#saiu03idliderrespon1_doc").val(data[1]);
		ter_muestra('saiu03idliderrespon1', 0);
		}
	});
//$("#saiu03idunidadresp2").chosen();
//$("#saiu03idequiporesp2").chosen();
$("#saiu03idliderrespon2_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu03idliderrespon2_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu03idliderrespon2_td").val(data[2]);
		$("#saiu03idliderrespon2_doc").val(data[1]);
		ter_muestra('saiu03idliderrespon2', 0);
		}
	});
//$("#saiu03idunidadresp3").chosen();
//$("#saiu03idequiporesp3").chosen();
$("#saiu03idliderrespon3_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu03idliderrespon3_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu03idliderrespon3_td").val(data[2]);
		$("#saiu03idliderrespon3_doc").val(data[1]);
		ter_muestra('saiu03idliderrespon3', 0);
		}
	});
//$("#saiu03otrosusaurios").chosen();
//$("#saiu03consupervisor").chosen();
//$("#saiu03anonimos").chosen();
//$("#saiu03anexoslibres").chosen();
//$("#saiu03moduloasociado").chosen();
//$("#saiu03nivelrespuesta").chosen();
//$("#saiu03consupervisor2").chosen();
//$("#saiu03consupervisor3").chosen();
//$("#saiu03infoprograma").chosen();
//$("#saiu03infoperiodos").chosen();
//$("#saiu03requierepago").chosen();
//$("#saiu03incluyemodelo").chosen();
//$("#saiu04activo").chosen();
//$("#saiu04obligatorio").chosen();
//$("#saiu04idetapa").chosen();
});