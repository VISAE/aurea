// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
$().ready(function () {
	$("#aure51idresponsable_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#aure51idresponsable_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#aure51idresponsable_td").val(data[2]);
			$("#aure51idresponsable_doc").val(data[1]);
			ter_muestra('aure51idresponsable', 0);
		}
	});
	$("#aure52idtercero_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#aure52idtercero_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#aure52idtercero_td").val(data[2]);
			$("#aure52idtercero_doc").val(data[1]);
			ter_muestra('aure52idtercero', 1);
		}
	});
	$("#aure58idusuario_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#aure58idusuario_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#aure58idusuario_td").val(data[2]);
			$("#aure58idusuario_doc").val(data[1]);
			ter_muestra('aure58idusuario', 0);
		}
	});
	$("#aure82idtester_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#aure82idtester_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#aure82idtester_td").val(data[2]);
			$("#aure82idtester_doc").val(data[1]);
			ter_muestra('aure82idtester', 0);
		}
	});
});