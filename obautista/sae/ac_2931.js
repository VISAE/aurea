// JavaScript Document
// --- © Omar Augusto Bautista - UNAD - 2026 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
$().ready(function () {
	$("#plab32idtercero_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#plab32idtercero_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#plab32idtercero_td").val(data[2]);
			$("#plab32idtercero_doc").val(data[1]);
			ter_muestra('plab32idtercero', 0);
		}
	});
	$("#plab33idmonitor_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#plab33idmonitor_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#plab33idmonitor_td").val(data[2]);
			$("#plab33idmonitor_doc").val(data[1]);
			ter_muestra('plab33idmonitor', 0);
		}
	});
});