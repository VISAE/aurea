// JavaScript Document
// --- © Omar Augusto Bautista - UNAD - 2026 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
$().ready(function () {
	$("#visa40idtercero_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#visa40idtercero_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#visa40idtercero_td").val(data[2]);
			$("#visa40idtercero_doc").val(data[1]);
			ter_muestra('visa40idtercero', 1);
		}
	});
	$("#visa43usuarioaprueba_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#visa43usuarioaprueba_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#visa43usuarioaprueba_td").val(data[2]);
			$("#visa43usuarioaprueba_doc").val(data[1]);
			ter_muestra('visa43usuarioaprueba', 0);
		}
	});
	$("#visa44usuario_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#visa44usuario_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#visa44usuario_td").val(data[2]);
			$("#visa44usuario_doc").val(data[1]);
			ter_muestra('visa44usuario', 0);
		}
	});
});