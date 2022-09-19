// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.28.2 viernes, 2 de septiembre de 2022
$().ready(function () {
	$("#unae40idtercero_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#unae40idtercero_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#unae40idtercero_td").val(data[2]);
			$("#unae40idtercero_doc").val(data[1]);
			ter_muestra('unae40idtercero', 1);
		}
	});
	$("#unae40idsolicita_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#unae40idsolicita_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#unae40idsolicita_td").val(data[2]);
			$("#unae40idsolicita_doc").val(data[1]);
			ter_muestra('unae40idsolicita', 0);
		}
	});
	$("#unae40idaprueba_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#unae40idaprueba_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#unae40idaprueba_td").val(data[2]);
			$("#unae40idaprueba_doc").val(data[1]);
			ter_muestra('unae40idaprueba', 0);
		}
	});
});