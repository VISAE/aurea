// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.25.10c jueves, 6 de mayo de 2021
$().ready(function(){
//$("#saiu61vigente").chosen();
//$("#saiu61idunidad").chosen();
$("#saiu62idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu62idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu62idtercero_td").val(data[2]);
		$("#saiu62idtercero_doc").val(data[1]);
		ter_muestra('saiu62idtercero', 1);
		}
	});
//$("#saiu62idperiodo").chosen();
//$("#saiu62idescuela").chosen();
//$("#saiu62idprograma").chosen();
//$("#saiu62idzona").chosen();
//$("#saiu62idcentro").chosen();
//$("#saiu62estado").chosen();
//$("#saiu62mailenviado").chosen();
});