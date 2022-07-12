// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.28.2 jueves, 26 de mayo de 2022
$().ready(function() {
$("#unae24idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#unae24idtercero_doc").result(function(event, data, formatted) {
	if (data[1] != '') {
		$("#unae24idtercero_td").val(data[2]);
		$("#unae24idtercero_doc").val(data[1]);
		ter_muestra('unae24idtercero', 1);
	}
});
//$("#unae24tipodocdestino").chosen();
$("#unae24idsolicita_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#unae24idsolicita_doc").result(function(event, data, formatted) {
	if (data[1] != '') {
		$("#unae24idsolicita_td").val(data[2]);
		$("#unae24idsolicita_doc").val(data[1]);
		ter_muestra('unae24idsolicita', 0);
	}
});
$("#unae24idaprueba_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#unae24idaprueba_doc").result(function(event, data, formatted) {
	if (data[1] != '') {
		$("#unae24idaprueba_td").val(data[2]);
		$("#unae24idaprueba_doc").val(data[1]);
		ter_muestra('unae24idaprueba', 0);
	}
});
});