// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 3.0.16 jueves, 10 de julio de 2025
$().ready(function () {
	$("#saiu74idadministrador_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#saiu74idadministrador_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#saiu74idadministrador_td").val(data[2]);
			$("#saiu74idadministrador_doc").val(data[1]);
			ter_muestra('saiu74idadministrador', 0);
		}
	});
});