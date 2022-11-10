// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
$().ready(function(){
//$("#saiu28estado").chosen();
//$("#saiu28idchat").chosen();
$("#saiu28idsolicitante_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu28idsolicitante_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu28idsolicitante_td").val(data[2]);
		$("#saiu28idsolicitante_doc").val(data[1]);
		ter_muestra('saiu28idsolicitante', 0);
		}
	});
//$("#saiu28tipointeresado").chosen();
//$("#saiu28clasesolicitud").chosen();
//$("#saiu28tiposolicitud").chosen();
//$("#saiu28temasolicitud").chosen();
//$("#saiu28idzona").chosen();
//$("#saiu28idcentro").chosen();
//$("#saiu28codpais").chosen();
//$("#saiu28coddepto").chosen();
//$("#saiu28codciudad").chosen();
//$("#saiu28idescuela").chosen();
//$("#saiu28idprograma").chosen();
//$("#saiu28idperiodo").chosen();
//$("#saiu28paramercadeo").chosen();
$("#saiu28idresponsable_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu28idresponsable_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu28idresponsable_td").val(data[2]);
		$("#saiu28idresponsable_doc").val(data[1]);
		ter_muestra('saiu28idresponsable', 0);
		}
	});
//$("#saiu28solucion").chosen();
});