// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.25.4 martes, 4 de agosto de 2020
$().ready(function(){
//$("#saiu31idtemageneral").chosen();
//$("#saiu31idunidadresp").chosen();
//$("#saiu31temporal").chosen();
//$("#saiu31cobertura").chosen();
//$("#saiu31entornodeuso").chosen();
//$("#saiu31aplicaaspirante").chosen();
//$("#saiu31aplicaestudiante").chosen();
//$("#saiu31aplicaegresado").chosen();
//$("#saiu31aplicadocentes").chosen();
//$("#saiu31aplicaadministra").chosen();
//$("#saiu31aplicaotros").chosen();
//$("#saiu31aplicanotificacion").chosen();
//$("#saiu31prioridadnotifica").chosen();
$("#saiu31usuario_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu31usuario_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu31usuario_td").val(data[2]);
		$("#saiu31usuario_doc").val(data[1]);
		ter_muestra('saiu31usuario', 0);
		}
	});
$("#saiu31usuarioaprueba_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu31usuarioaprueba_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu31usuarioaprueba_td").val(data[2]);
		$("#saiu31usuarioaprueba_doc").val(data[1]);
		ter_muestra('saiu31usuarioaprueba', 0);
		}
	});
//$("#saiu32idtema").chosen();
//$("#saiu32activo").chosen();
//$("#saiu33idpalabra").chosen();
//$("#saiu33activo").chosen();
//$("#saiu38idestadorigen").chosen();
//$("#saiu38idestadofin").chosen();
$("#saiu38usuario_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#saiu38usuario_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#saiu38usuario_td").val(data[2]);
		$("#saiu38usuario_doc").val(data[1]);
		ter_muestra('saiu38usuario', 0);
		}
	});
//$("#saiu35idzona").chosen();
//$("#saiu35idcentro").chosen();
//$("#saiu35activo").chosen();
//$("#saiu37idbaserel").chosen();
});