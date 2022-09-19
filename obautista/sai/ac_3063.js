// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.27.5 viernes, 7 de enero de 2022
$().ready(function(){
//$("#saiu63idmodulo").chosen();
//$("#saiu63tiponotifica").chosen();
//$("#saiu63idzona").chosen();
//$("#saiu63idcentro").chosen();
//$("#saiu63idescuela").chosen();
//$("#saiu63idprograma").chosen();
$("#saiu64idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu64idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu64idtercero_td").val(data[2]);
		$("#saiu64idtercero_doc").val(data[1]);
		ter_muestra('saiu64idtercero', 1);
		}
	});
//$("#saiu65idgrupo").chosen();
});