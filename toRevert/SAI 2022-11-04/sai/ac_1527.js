// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.22.7 miércoles, 20 de marzo de 2019
// --- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
// --- Modelo Versión 2.28.2 viernes, 17 de junio de 2022
$().ready(function () {
	$("#bita27idlider_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#bita27idlider_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#bita27idlider_td").val(data[2]);
			$("#bita27idlider_doc").val(data[1]);
			ter_muestra('bita27idlider', 0);
		}
	});
	$("#bita27propietario_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#bita27propietario_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#bita27propietario_td").val(data[2]);
			$("#bita27propietario_doc").val(data[1]);
			ter_muestra('bita27propietario', 0);
		}
	});
	$("#bita28idtercero_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#bita28idtercero_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#bita28idtercero_td").val(data[2]);
			$("#bita28idtercero_doc").val(data[1]);
			ter_muestra('bita28idtercero', 1);
		}
	});
});