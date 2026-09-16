// JavaScript Document
// --- © Juan David Avellaneda Molina - UNAD - 2026 ---
// --- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 3.2.5 martes, 15 de septiembre de 2026
$().ready(function () {
	$("#visa55idresponsable_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#visa55idresponsable_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#visa55idresponsable_td").val(data[2]);
			$("#visa55idresponsable_doc").val(data[1]);
			ter_muestra('visa55idresponsable', 0);
		}
	});
});