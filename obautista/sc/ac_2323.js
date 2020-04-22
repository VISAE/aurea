// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.23.5 Monday, September 9, 2019
$().ready(function(){
$("#cara23idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara23idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara23idtercero_td").val(data[2]);
		$("#cara23idtercero_doc").val(data[1]);
		ter_muestra('cara23idtercero', 0);
		}
	});
//$("#cara23idencuesta").chosen();
//$("#cara23idtipo").chosen();
//$("#cara23asisteinduccion").chosen();
//$("#cara23asisteinmersioncv").chosen();
//$("#cara23catedra_skype").chosen();
//$("#cara23catedra_bler1").chosen();
//$("#cara23catedra_bler2").chosen();
//$("#cara23catedra_webconf").chosen();
//$("#cara23catedra_avance").chosen();
//$("#cara23catedra_acciones").chosen();
//$("#cara23catedra_resultados").chosen();
//$("#cara23aler_criterio").chosen();
//$("#cara23comp_digital").chosen();
//$("#cara23comp_cuanti").chosen();
//$("#cara23comp_lectora").chosen();
//$("#cara23comp_ingles").chosen();
//$("#cara23comp_criterio").chosen();
//$("#cara23nivela_digital").chosen();
//$("#cara23nivela_cuanti").chosen();
//$("#cara23nivela_lecto").chosen();
//$("#cara23nivela_ingles").chosen();
//$("#cara23nivela_exito").chosen();
//$("#cara23contacto_efectivo").chosen();
//$("#cara23contacto_forma").chosen();
//$("#cara23aplaza").chosen();
//$("#cara23seretira").chosen();
//$("#cara23factorriesgo").chosen();
$("#cara23zonal_idlider_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#cara23zonal_idlider_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#cara23zonal_idlider_td").val(data[2]);
		$("#cara23zonal_idlider_doc").val(data[1]);
		ter_muestra('cara23zonal_idlider', 0);
		}
	});
$("#bperaca").chosen();
});