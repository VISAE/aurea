// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
$().ready(function(){
$("#even21idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even21idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even21idtercero_td").val(data[2]);
		$("#even21idtercero_doc").val(data[1]);
		ter_muestra('even21idtercero', 1);
		}
	});
$("#even21idcurso_cod").autocomplete('ac_140.php', {width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even21idcurso_cod").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even21idcurso_cod").val(data[1]);
		cod_even21idcurso();
		}
	});
$("#even25idcurso_cod").autocomplete('ac_140.php', {width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even25idcurso_cod").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even25idcurso_cod").val(data[1]);
		cod_even25idcurso();
		}
	});
$("#even40idpropietario_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even40idpropietario_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even40idpropietario_td").val(data[2]);
		$("#even40idpropietario_doc").val(data[1]);
		ter_muestra('even40idpropietario', 1);
		}
	});
});