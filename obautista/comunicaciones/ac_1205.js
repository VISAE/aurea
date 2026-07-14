// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 3.0.16 viernes, 11 de julio de 2025
$().ready(function () {
	$("#masi04idtercero_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#masi04idtercero_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#masi04idtercero_td").val(data[2]);
			$("#masi04idtercero_doc").val(data[1]);
			ter_muestra('masi04idtercero', 1);
		}
	});
});