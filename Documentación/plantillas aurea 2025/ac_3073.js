// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 3.0.12c viernes, 11 de abril de 2025
$().ready(function () {
	$("#saiu73idsolicitante_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#saiu73idsolicitante_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#saiu73idsolicitante_td").val(data[2]);
			$("#saiu73idsolicitante_doc").val(data[1]);
			ter_muestra('saiu73idsolicitante', 0);
		}
	});
	$("#saiu73idresponsable_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#saiu73idresponsable_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#saiu73idresponsable_td").val(data[2]);
			$("#saiu73idresponsable_doc").val(data[1]);
			ter_muestra('saiu73idresponsable', 0);
		}
	});
});