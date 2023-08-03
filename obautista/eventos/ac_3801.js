// JavaScript Document
// --- © Cristhiam Dario Silva Chavez - UNAD - 2022 ---
// --- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.28.4b viernes, 21 de octubre de 2022
$().ready(function () {
	$("#cipa01iddocente_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#cipa01iddocente_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#cipa01iddocente_td").val(data[2]);
			$("#cipa01iddocente_doc").val(data[1]);
			ter_muestra('cipa01iddocente', 0);
		}
	});
	$("#cipa01iddocente2_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#cipa01iddocente2_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#cipa01iddocente2_td").val(data[2]);
			$("#cipa01iddocente2_doc").val(data[1]);
			ter_muestra('cipa01iddocente2', 0);
		}
	});
	$("#cipa01iddocente3_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#cipa01iddocente3_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#cipa01iddocente3_td").val(data[2]);
			$("#cipa01iddocente3_doc").val(data[1]);
			ter_muestra('cipa01iddocente3', 0);
		}
	});
	$("#cipa01idmonitor_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#cipa01idmonitor_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#cipa01idmonitor_td").val(data[2]);
			$("#cipa01idmonitor_doc").val(data[1]);
			ter_muestra('cipa01idmonitor', 0);
		}
	});
	$("#cipa01idsupervisor_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#cipa01idsupervisor_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#cipa01idsupervisor_td").val(data[2]);
			$("#cipa01idsupervisor_doc").val(data[1]);
			ter_muestra('cipa01idsupervisor', 0);
		}
	});
	$("#cipa03idinscrito_doc").autocomplete("ac_111.php", { width: 360, matchContains: true, no_result: 'No coincidentes', selectFirst: false });
	$("#cipa03idinscrito_doc").result(function (event, data, formatted) {
		if (data[1] != '') {
			$("#cipa03idinscrito_td").val(data[2]);
			$("#cipa03idinscrito_doc").val(data[1]);
			ter_muestra('cipa03idinscrito', 1);
		}
	});
});