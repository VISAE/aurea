// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
$().ready(function(){
//$("#saiu19estado").chosen();
//$("#saiu19idchat").chosen();
$("#saiu19idsolicitante_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu19idsolicitante_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu19idsolicitante_td").val(data[2]);
		$("#saiu19idsolicitante_doc").val(data[1]);
		ter_muestra('saiu19idsolicitante', 0);
		}
	});
//$("#saiu19tipointeresado").chosen();
//$("#saiu19clasesolicitud").chosen();
//$("#saiu19tiposolicitud").chosen();
//$("#saiu19temasolicitud").chosen();
//$("#saiu19idzona").chosen();
//$("#saiu19idcentro").chosen();
//$("#saiu19codpais").chosen();
//$("#saiu19coddepto").chosen();
//$("#saiu19codciudad").chosen();
//$("#saiu19idescuela").chosen();
//$("#saiu19idprograma").chosen();
//$("#saiu19idperiodo").chosen();
//$("#saiu19paramercadeo").chosen();
$("#saiu19idresponsable_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu19idresponsable_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu19idresponsable_td").val(data[2]);
		$("#saiu19idresponsable_doc").val(data[1]);
		ter_muestra('saiu19idresponsable', 0);
		}
	});
//$("#saiu19solucion").chosen();
});