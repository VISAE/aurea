// JavaScript Document
// --- © Juan David Avellaneda Molina - UNAD - 2026 ---
// --- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
$().ready(function () {
	$("#visa59idusuario_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#visa59idusuario_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#visa59idusuario_td").val(data[2]);
			$("#visa59idusuario_doc").val(data[1]);
			ter_muestra('visa59idusuario', 0);
		}
	});
	$("#visa60idusuario_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#visa60idusuario_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#visa60idusuario_td").val(data[2]);
			$("#visa60idusuario_doc").val(data[1]);
			ter_muestra('visa60idusuario', 0);
		}
	});
	$("#visa62idresponsable_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#visa62idresponsable_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#visa62idresponsable_td").val(data[2]);
			$("#visa62idresponsable_doc").val(data[1]);
			ter_muestra('visa62idresponsable', 0);
		}
	});
	$("#visa63idcolaborador_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#visa63idcolaborador_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#visa63idcolaborador_td").val(data[2]);
			$("#visa63idcolaborador_doc").val(data[1]);
			ter_muestra('visa63idcolaborador', 0);
		}
	});
});