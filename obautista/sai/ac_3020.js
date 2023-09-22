// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
$().ready(function(){
//$("#saiu20estado").chosen();
//$("#saiu20idcorreo").chosen();
$("#saiu20idsolicitante_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu20idsolicitante_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu20idsolicitante_td").val(data[2]);
		$("#saiu20idsolicitante_doc").val(data[1]);
		ter_muestra('saiu20idsolicitante', 0);
		}
	});
//$("#saiu20tipointeresado").chosen();
//$("#saiu20clasesolicitud").chosen();
//$("#saiu20tiposolicitud").chosen();
//$("#saiu20temasolicitud").chosen();
//$("#saiu20idzona").chosen();
//$("#saiu20idcentro").chosen();
//$("#saiu20codpais").chosen();
//$("#saiu20coddepto").chosen();
//$("#saiu20codciudad").chosen();
//$("#saiu20idescuela").chosen();
//$("#saiu20idprograma").chosen();
//$("#saiu20idperiodo").chosen();
//$("#saiu20paramercadeo").chosen();
$("#saiu20idresponsable_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu20idresponsable_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu20idresponsable_td").val(data[2]);
		$("#saiu20idresponsable_doc").val(data[1]);
		ter_muestra('saiu20idresponsable', 0);
		}
	});
//$("#saiu20solucion").chosen();
});