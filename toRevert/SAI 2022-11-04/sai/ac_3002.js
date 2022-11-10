// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
$().ready(function(){
//$("#saiu02clasesol").chosen();
//$("#saiu02idunidadresp").chosen();
//$("#saiu02idequiporesp").chosen();
$("#saiu02idliderrespon_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu02idliderrespon_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu02idliderrespon_td").val(data[2]);
		$("#saiu02idliderrespon_doc").val(data[1]);
		ter_muestra('saiu02idliderrespon', 0);
		}
	});
});