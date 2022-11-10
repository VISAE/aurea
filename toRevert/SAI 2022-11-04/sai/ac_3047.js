// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.25.10c lunes, 3 de mayo de 2021
$().ready(function(){
//$("#saiu47tiporadicado").chosen();
//$("#saiu47tipotramite").chosen();
$("#saiu47idsolicitante_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu47idsolicitante_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu47idsolicitante_td").val(data[2]);
		$("#saiu47idsolicitante_doc").val(data[1]);
		ter_muestra('saiu47idsolicitante', 0);
		}
	});
//$("#saiu47idperiodo").chosen();
//$("#saiu47idescuela").chosen();
//$("#saiu47idprograma").chosen();
//$("#saiu47idzona").chosen();
//$("#saiu47idcentro").chosen();
//$("#saiu47t1idmotivo").chosen();
$("#saiu47idbenefdevol_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu47idbenefdevol_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu47idbenefdevol_td").val(data[2]);
		$("#saiu47idbenefdevol_doc").val(data[1]);
		ter_muestra('saiu47idbenefdevol', 0);
		}
	});
$("#saiu47idaprueba_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu47idaprueba_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu47idaprueba_td").val(data[2]);
		$("#saiu47idaprueba_doc").val(data[1]);
		ter_muestra('saiu47idaprueba', 0);
		}
	});
//$("#saiu48visiblealinteresado").chosen();
$("#saiu48idusuario_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu48idusuario_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu48idusuario_td").val(data[2]);
		$("#saiu48idusuario_doc").val(data[1]);
		ter_muestra('saiu48idusuario', 0);
		}
	});
$("#saiu49idresponsable_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu49idresponsable_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu49idresponsable_td").val(data[2]);
		$("#saiu49idresponsable_doc").val(data[1]);
		ter_muestra('saiu49idresponsable', 0);
		}
	});
//$("#saiu49idestadorigen").chosen();
//$("#saiu49idestadofin").chosen();
$("#saiu49usuario_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu49usuario_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu49usuario_td").val(data[2]);
		$("#saiu49usuario_doc").val(data[1]);
		ter_muestra('saiu49usuario', 0);
		}
	});
//$("#saiu49correterminos").chosen();
//$("#saiu59idtipodoc").chosen();
//$("#saiu59opcional").chosen();
//$("#saiu59idestado").chosen();
$("#saiu59idusuario_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu59idusuario_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu59idusuario_td").val(data[2]);
		$("#saiu59idusuario_doc").val(data[1]);
		ter_muestra('saiu59idusuario', 0);
		}
	});
$("#saiu59idrevisa_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu59idrevisa_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu59idrevisa_td").val(data[2]);
		$("#saiu59idrevisa_doc").val(data[1]);
		ter_muestra('saiu59idrevisa', 0);
		}
	});
});