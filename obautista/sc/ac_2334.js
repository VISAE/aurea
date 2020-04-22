// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.24.0 Thursday, November 21, 2019
$().ready(function(){
//$("#core16peraca").chosen();
$("#core16tercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#core16tercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#core16tercero_td").val(data[2]);
		$("#core16tercero_doc").val(data[1]);
		ter_muestra('core16tercero', 1);
		}
	});
//$("#core16idprograma").chosen();
//$("#core16idcead").chosen();
//$("#core16idescuela").chosen();
//$("#core16idzona").chosen();
//$("#core16nuevo").chosen();
$("#core16idconsejero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#core16idconsejero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#core16idconsejero_td").val(data[2]);
		$("#core16idconsejero_doc").val(data[1]);
		ter_muestra('core16idconsejero', 0);
		}
	});
//$("#core16estado").chosen();
$("#bperiodo").chosen();
});