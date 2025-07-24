<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 - 2005 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 viernes, 21 de febrero de 2020
--- Modelo Versión 3.0.16 jueves, 10 de julio de 2025
--- 3005 saiu05solicitud
*/

/** Archivo lib3005.php.
 * Libreria 3005 saiu05solicitud.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date viernes, 21 de febrero de 2020
 */
function f3005_HTMLComboV2_saiu05tiporadicado($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu05tiporadicado', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_HTMLComboV2_saiu05idtemaorigen($objDB, $objCombos, $valor, $vrsaiu05idtiposolorigen)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu05idtemaorigen', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = '';
	if ((int)$vrsaiu05idtiposolorigen != 0) {
		$objCombos->iAncho = 450;
		$sSQL = 'SELECT saiu03id AS id, saiu03titulo AS nombre 
		FROM saiu03temasol 
		WHERE saiu03tiposol=' . $vrsaiu05idtiposolorigen . ' AND saiu03activo="S"
		ORDER BY saiu03titulo';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_HTMLComboV2_saiu05idcentro($objDB, $objCombos, $valor, $vrsaiu18idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sCondi = 'unad24idzona="' . $vrsaiu18idzona . '"';
	$objCombos->nuevo('saiu05idcentro', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE ' . $sCondi . ' AND unad24id>0';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_HTMLComboV2_saiu05idprograma($objDB, $objCombos, $valor, $vrsaiu05idescuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu05idprograma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->addItem('0', $ETI['msg_na']);
	//$objCombos->iAncho=450;
	$sCondiEscuela = ' AND TB.core09idescuela="' . $vrsaiu05idescuela . '"';
	$sTabla2 = '';
	$sCampos2 = '';
	if ($vrsaiu05idescuela == '') {
		$sCondiEscuela = ' AND TB.core09idescuela=T12.core12id';
		$sTabla2 = ', core12escuela AS T12';
		$sCampos2 = ', " [", T12.core12sigla, "]"';
	}
	$sSQL = 'SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]"' . $sCampos2 . ') AS nombre FROM core09programa AS TB' . $sTabla2 . ' WHERE TB.core09id>0' . $sCondiEscuela;
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_HTMLComboV2_saiu05coddepto($objDB, $objCombos, $valor, $vrsaiu05codpais)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi = 'unad19codpais="' . $vrsaiu05codpais . '"';
	if ($sCondi != '') {
		$sCondi = ' WHERE ' . $sCondi;
	}
	$objCombos->nuevo('saiu05coddepto', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'carga_combo_saiu05codciudad()';
	$sSQL = 'SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto' . $sCondi;
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_HTMLComboV2_saiu05codciudad($objDB, $objCombos, $valor, $vrsaiu05coddepto)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi = 'unad20coddepto="' . $vrsaiu05coddepto . '"';
	if ($sCondi != '') {
		$sCondi = ' WHERE ' . $sCondi;
	}
	$objCombos->nuevo('saiu18codciudad', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$sSQL = 'SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad' . $sCondi;
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_HTMLComboV2_saiu05costogenera($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu05costogenera', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$sSQL = 'SELECT  AS id,  AS nombre FROM ';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_Combosaiu05idtemaorigen($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos('n');
	$html_saiu05idtemaorigen = f3005_HTMLComboV2_saiu05idtemaorigen($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu05idtemaorigen', 'innerHTML', $html_saiu05idtemaorigen);
	$objResponse->call('jQuery("#saiu05idtemaorigen").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	return $objResponse;
}
function f3005_HTMLComboV2_bcentro($objDB, $objCombos, $valor, $vrbzona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('bcentro', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf3005()';
	$sSQL = '';
	if ((int)$vrbzona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrbzona . ' 
		ORDER BY TB.unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_Combobcentro($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_bcentro = f3005_HTMLComboV2_bcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bcentro', 'innerHTML', $html_bcentro);
	//$objResponse->call('$("#bcentro").chosen()');
	$objResponse->call('paginarf3005()');
	return $objResponse;
}
function f3005_HTMLComboV2_bprograma($objDB, $objCombos, $valor, $vrbescuela)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('bprograma', $valor, true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarf3005()';
	$sSQL = '';
	if ((int)$vrbescuela != 0) {
		$objCombos->iAncho = 600;
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL='SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " - ", TB.core09codigo, CASE TB.core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END) AS nombre 
		FROM core09programa AS TB  
		WHERE TB.core09idescuela=' . $vrbescuela .'  
		ORDER BY TB.core09activo DESC, TB.core09nombre';

	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_Combobprograma($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_bprograma = f3005_HTMLComboV2_bprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bprograma', 'innerHTML', $html_bprograma);
	$objResponse->call('$("#bprograma").chosen()');
	$objResponse->call('paginarf3005()');
	return $objResponse;
}
function f3005_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$saiu05agno = numeros_validar($datos[1]);
	if ($saiu05agno == '') {
		$bHayLlave = false;
	}
	$saiu05mes = numeros_validar($datos[2]);
	if ($saiu05mes == '') {
		$bHayLlave = false;
	}
	$saiu05tiporadicado = numeros_validar($datos[3]);
	if ($saiu05tiporadicado == '') {
		$bHayLlave = false;
	}
	$saiu05consec = numeros_validar($datos[4]);
	if ($saiu05consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT saiu05consec FROM saiu05solicitud WHERE saiu05agno=' . $saiu05agno . ' AND saiu05mes=' . $saiu05mes . ' AND saiu05tiporadicado=' . $saiu05tiporadicado . ' AND saiu05consec=' . $saiu05consec . '';
		$res = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($res) == 0) {
			$bHayLlave = false;
		}
		$objDB->CerrarConexion();
		if ($bHayLlave) {
			$objResponse = new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
		}
	}
}
function f3005_Busquedas($aParametros)
{
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$sCampo = $aParametros[1];
	$sTitulo = ' {' . $sCampo . '}';
	if (isset($aParametros[2]) == 0) {
		$aParametros[2] = 0;
	}
	if (isset($aParametros[3]) == 0) {
		$aParametros[3] = 0;
	}
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'saiu05idsolicitante':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3005);
			break;
		case 'saiu05idinteresado':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3005);
			break;
		case 'saiu05idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3005);
			break;
		case 'saiu06idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3005);
			break;
		case 'saiu07idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3005);
			break;
		case 'saiu07idvalidad':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3005);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_3005'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f3005_HtmlBusqueda($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$sError = '';
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sDetalle = '';
	switch ($aParametros[100]) {
		case 'saiu05idsolicitante':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu05idinteresado':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu05idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu06idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu07idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu07idvalidad':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f3005_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	$iNumVariables = 115;
	for ($k = 103; $k <= $iNumVariables; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	$idTercero = numeros_validar($aParametros[100]);
	$sError = '';
	$sDebug = '';
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$pagina = numeros_validar($aParametros[101]);
	$lineastabla = numeros_validar($aParametros[102]);
	$sNombre = cadena_Validar(trim($aParametros[103]));
	$iAgno = numeros_validar($aParametros[104]);
	$iEstado = numeros_validar($aParametros[105]);
	$bListar = numeros_validar($aParametros[106]);
	$bdoc = cadena_Validar(trim($aParametros[107]));
	$btipo = numeros_validar($aParametros[108]);
	$bcategoria = numeros_validar($aParametros[109]);
	$btema = numeros_validar($aParametros[110]);
	$bref = cadena_Validar(trim($aParametros[111]));
	//$bRevBorrador = numeros_validar($aParametros[112]);
	$bzona = numeros_validar($aParametros[112]);
	$bcentro = numeros_validar($aParametros[113]);
	$bescuela = numeros_validar($aParametros[114]);
	$bprograma = numeros_validar($aParametros[115]);
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM Tabla WHERE Id=' . $sValorId;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3073" name="paginaf3005" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf3073" name="lppf3005" type="hidden" value="' . $lineastabla . '"/>';
	if ($iAgno == '') {
		$sLeyenda = $sLeyenda . 'No ha seleccionado un a&ntilde;o a consultar';
	}
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	$aTemas = array();
	$sSQL = 'SELECT saiu03id, saiu03titulo FROM saiu03temasol WHERE saiu03activo="S"' . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		while ($fila = $objDB->sf($tabla)) {
			$aTemas[$fila['saiu03id']] = cadena_notildes($fila['saiu03titulo']);
		}
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($iEstado !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05estado=' . $iEstado . '';
	}
	switch ($bListar) {
		case 1:
			$sSQLadd = $sSQLadd . ' AND TB.saiu05idresponsable=' . $idTercero . '';
			break;
		case 2:
			$aEquipos = array();
			$sEquipos = '';
			$sSQL = 'SELECT bita27id FROM bita27equipotrabajo WHERE bita27activo=1 AND bita27idlider=' . $idTercero . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				while ($fila = $objDB->sf($tabla)) {
					$aEquipos[] = $fila['bita27id'];
				}
			} else {
				$sSQL = 'SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28activo="S" AND bita28idtercero=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					while ($fila = $objDB->sf($tabla)) {
						$aEquipos[] = $fila['bita28idequipotrab'];
					}
				}
			}
			$sEquipos = implode(',', $aEquipos);
			if ($sEquipos != '') {
				$sSQLadd = $sSQLadd . ' AND TB.saiu05idequiporesp IN (' . $sEquipos . ')';
			} else {
				$sSQLadd = $sSQLadd . ' AND TB.saiu05idresponsable=' . $idTercero . '';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Lider o Colaborador: ' . $sSQL . '<br>';
			}
			break;
	}
	if ($bcentro != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu05cead=' . $bcentro . ' AND ';
	} else {
		if ($bzona != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.saiu05idzona=' . $bzona . ' AND ';
		}
	}
	if ($bprograma != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu05idprograma=' . $bprograma . ' AND ';
	} else {
		if ($bescuela != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.saiu05idescuela=' . $bescuela . ' AND ';
		}
	}
	if ($sNombre != '') {
		$sBase = mb_strtoupper($sNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T11.unad11razonsocial LIKE "%' . $sCadena . '%"';
			}
		}
	}
	if ($bdoc !== '') {
		$sSQLadd = $sSQLadd . ' AND T11.unad11doc LIKE "%' . $bdoc . '%"';
	}
	if ($btipo !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05idcategoria=' . $btipo . '';
	}
	if ($bcategoria !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05idtiposolorigen=' . $bcategoria . '';
	}
	if ($btema !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05idtemaorigen=' . $btema . '';
	}
	if ($bref !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05numref="' . $bref . '"';
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Agno, Mes, Dia, Consecutivo, Estado, Hora, Minuto';
	//Las solicitudes no estan en una tabla en contenedores...
	$aTablas = array();
	$iTablas = 0;
	$iNumSolicitudes = 0;
	$tabladetalle = 0;
	$sSQL = 'SELECT saiu15agno, saiu15mes, SUM(saiu15numsolicitudes) AS Solicitudes 
	FROM saiu15historico 
	WHERE saiu15agno=' . $iAgno . ' AND saiu15tiporadicado=1
	GROUP BY saiu15agno, saiu15mes';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Historico: ' . $sSQL . '<br>';
	}
	$tabla15 = $objDB->ejecutasql($sSQL);
	while ($fila15 = $objDB->sf($tabla15)) {
		$iNumSolicitudes = $iNumSolicitudes + $fila15['Solicitudes'];
		if ($fila15['saiu15mes'] < 10) {
			$sContenedor = $fila15['saiu15agno'] . '0' . $fila15['saiu15mes'];
		} else {
			$sContenedor = $fila15['saiu15agno'] . $fila15['saiu15mes'];
		}
		$iTablas++;
		$aTablas[$iTablas] = $sContenedor;
	}
	$registros = $iNumSolicitudes;
	$sLimite = '';
	$sSQL = '';
	for ($k = 1; $k <= $iTablas; $k++) {
		if ($k != 1) {
			$sSQL = $sSQL . ' UNION ';
		}
		$sContenedor = $aTablas[$k];
		$sCampos = 'SELECT TB.saiu05agno, TB.saiu05mes, TB.saiu05dia, TB.saiu05consec, T12.saiu11nombre, 
		TB.saiu05hora, TB.saiu05minuto, TB.saiu05id, TB.saiu05estado, T11.unad11tipodoc, 
		T11.unad11doc, T11.unad11razonsocial, T13.saiu68nombre, TB.saiu05idtemaorigen, TB.saiu05fecharespprob, 
		TB.saiu05tiemprespdias, TB.saiu05origenagno, TB.saiu05origenmes, TB.saiu05raddesphab';
		$sConsulta = 'FROM saiu05solicitud_' . $sContenedor . ' AS TB, saiu11estadosol AS T12, unad11terceros AS T11, saiu68categoria AS T13 
		WHERE TB.saiu05tiporadicado=1 AND ' . $sSQLadd1 . ' TB.saiu05estado=T12.saiu11id AND TB.saiu05idsolicitante=T11.unad11id 
		AND TB.saiu05idcategoria=T13.saiu68id ' . $sSQLadd . '';
		$sSQL = $sSQL . $sCampos . ' ' . $sConsulta;
	}
	if ($sSQL != '') {
		$sOrden = 'ORDER BY saiu05estado, saiu05fecharespprob, saiu05agno, saiu05mes, saiu05dia';
		$sSQL = $sSQL . ' ' . $sOrden;
		$sSQLlista = str_replace("'", "|", $sSQL);
		$sSQLlista = str_replace('"', "|", $sSQLlista);
		$sErrConsulta = '<input id="consulta_3005" name="consulta_3005" type="hidden" value="' . $sSQLlista . '"/>';
		$sErrConsulta = $sErrConsulta . '<input id="titulos_3005" name="titulos_3005" type="hidden" value="' . $sTitulos . '"/>';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3005: ' . $sSQL . '<br>VARIABLES: $iEstado: ' . $iEstado . ' - $bListar: ' . $bListar . ' - $iTablas: ' . $iTablas . '<br>';
		}
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($tabladetalle == false) {
			$registros = 0;
			$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
			//$sLeyenda=$sSQL;
		} else {
			$registros = $objDB->nf($tabladetalle);
			if ($registros == 0) {
				//return array($sErrConsulta . $sBotones, $sDebug);
			}
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
				$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
			}
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['msg_numsolicitud'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05idcategoria'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05idtemaorigen'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05idsolicitante'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05razonsocial'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05fecharad'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05estado'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf3005', $registros, $lineastabla, $pagina, 'paginarf3005()');
	$res = $res . html_lpp('lppf3005', $lineastabla, 'paginarf3005()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	if ($sSQL != '') {
		$tlinea = 1;
		$iPendientes = 0;
		$iHoy = fecha_DiaMod();
		$iDiasLimiteBorrador = 3;
		while ($filadet = $objDB->sf($tabladetalle)) {
			$sPrefijo = '';
			$sSufijo = '';
			$sClass = ' class="resaltetabla"';
			$sLink = '';
			$sTema = '';
			$bMostrar = true;
			if (false) {
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
			}
			if (($tlinea % 2) != 0) {
				$sClass = '';
			}
			$tlinea++;
			$iFechaSol = fecha_ArmarNumero($filadet['saiu05dia'], $filadet['saiu05mes'], $filadet['saiu05agno']);
			$et_NumSol = f3000_NumSolicitud($filadet['saiu05agno'], $filadet['saiu05mes'], $filadet['saiu05consec']);
			$et_saiu05dia = $ETI['et_estadorad'];
			if ($filadet['saiu05estado'] != -1) {				
				$iFechaRad = fecha_NumSumarDias($iFechaSol, $filadet['saiu05raddesphab']);
				$et_saiu05dia = fecha_desdenumero($iFechaRad);
			}
			//$et_saiu05fecharespprob='';
			//if ($filadet['saiu05fecharespprob']!='00/00/0000'){$et_saiu05fecharespprob=$filadet['saiu05fecharespprob'];}
			if ($bAbierta) {
				if ($filadet['saiu05origenagno'] > 0) {
					$filadet['saiu05agno'] = $filadet['saiu05origenagno'];
					$filadet['saiu05mes'] = $filadet['saiu05origenmes'];
				}
				$sLink = '<a href="javascript:cargaridf3005(' . $filadet['saiu05agno'] . ', ' . $filadet['saiu05mes'] . ', ' . $filadet['saiu05id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
			}
			if (isset($aTemas[$filadet['saiu05idtemaorigen']]) != 0) {
				$sTema = $aTemas[$filadet['saiu05idtemaorigen']];
			}
			$bEnProceso = false;
			$bTieneDias = false;
			$saiu05tiemprespdias = $filadet['saiu05tiemprespdias'];
			$saiu05fecharespprob = $filadet['saiu05fecharespprob'];
			switch ($filadet['saiu05estado']) {
				case 0:
				case 2:
					$bEnProceso = true;
				break;
			}
			if ($saiu05tiemprespdias == 0) {
				$sSQL = 'SELECT saiu03tiemprespdias1
				FROM saiu03temasol
				WHERE saiu03id = ' . $filadet['saiu05idtemaorigen'] . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$saiu05tiemprespdias = $fila['saiu03tiemprespdias1'];
					$saiu05fecharespprob = fecha_NumSumarDias($iFechaSol, $saiu05tiemprespdias);
				}
			}
			if ($saiu05tiemprespdias > 0) {
				$bTieneDias = true;
			}
			if ($bEnProceso && $bTieneDias) {
				$iDias = fecha_DiasEntreFechasDesdeNumero($saiu05fecharespprob, $iHoy);
				if ($iDias > $saiu05tiemprespdias) {
					$sPrefijo = '<span style="color:#FF6600"><b>';
					$sSufijo = '</b></span>';
					$iPendientes = $iPendientes + 1;
				}
			}
			if ($bMostrar) {
				$res = $res . '<tr' . $sClass . '>';
				$res = $res . '<td>' . $sPrefijo . $et_NumSol . $sSufijo . '</td>';
				$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu68nombre']) . $sSufijo . '</td>';
				$res = $res . '<td>' . $sPrefijo . $sTema . $sSufijo . '</td>';
				$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['unad11tipodoc']) . cadena_notildes($filadet['unad11doc']) . $sSufijo . '</td>';
				$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['unad11razonsocial']) . $sSufijo . '</td>';
				$res = $res . '<td>' . $sPrefijo . $et_saiu05dia . $sSufijo . '</td>';
				$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo . '</td>';
				$res = $res . '<td>' . $sLink . '</td>';
				$res = $res . '</tr>';
			}
		}
		if ($iPendientes > 0) {
			$sError = $sError . $ETI['msg_alertapendientes'] . $iPendientes . '<br>';
		}
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sError, $sDebug);
}
function f3005_HtmlTabla($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sDetalle, $sErrorT, $sDebugTabla) = f3005_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sError = $sError . $sErrorT;
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3005detalle', 'innerHTML', $sDetalle);
	if ($sError != '') {
		$objResponse->assign('div_alarma', 'innerHTML', $sError);
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3005_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['saiu05idsolicitante_td'] = $APP->tipo_doc;
	$DATA['saiu05idsolicitante_doc'] = '';
	$DATA['saiu05idinteresado_td'] = $APP->tipo_doc;
	$DATA['saiu05idinteresado_doc'] = '';
	$DATA['saiu05idresponsable_td'] = $APP->tipo_doc;
	$DATA['saiu05idresponsable_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'saiu05agno=' . $DATA['saiu05agno'] . ' AND saiu05mes=' . $DATA['saiu05mes'] . ' AND saiu05tiporadicado=' . $DATA['saiu05tiporadicado'] . ' AND saiu05consec=' . $DATA['saiu05consec'] . '';
	} else {
		$sSQLcondi = 'saiu05id=' . $DATA['saiu05id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu05solicitud WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['saiu05agno'] = $fila['saiu05agno'];
		$DATA['saiu05mes'] = $fila['saiu05mes'];
		$DATA['saiu05tiporadicado'] = $fila['saiu05tiporadicado'];
		$DATA['saiu05consec'] = $fila['saiu05consec'];
		$DATA['saiu05id'] = $fila['saiu05id'];
		$DATA['saiu05origenagno'] = $fila['saiu05origenagno'];
		$DATA['saiu05origenmes'] = $fila['saiu05origenmes'];
		$DATA['saiu05origenid'] = $fila['saiu05origenid'];
		$DATA['saiu05dia'] = $fila['saiu05dia'];
		$DATA['saiu05hora'] = $fila['saiu05hora'];
		$DATA['saiu05minuto'] = $fila['saiu05minuto'];
		$DATA['saiu05estado'] = $fila['saiu05estado'];
		$DATA['saiu05idmedio'] = $fila['saiu05idmedio'];
		$DATA['saiu05idtiposolorigen'] = $fila['saiu05idtiposolorigen'];
		$DATA['saiu05idtemaorigen'] = $fila['saiu05idtemaorigen'];
		$DATA['saiu05idtemafin'] = $fila['saiu05idtemafin'];
		$DATA['saiu05idtiposolfin'] = $fila['saiu05idtiposolfin'];
		$DATA['saiu05idsolicitante'] = $fila['saiu05idsolicitante'];
		$DATA['saiu05idinteresado'] = $fila['saiu05idinteresado'];
		$DATA['saiu05tipointeresado'] = $fila['saiu05tipointeresado'];
		$DATA['saiu05rptaforma'] = $fila['saiu05rptaforma'];
		$DATA['saiu05rptacorreo'] = $fila['saiu05rptacorreo'];
		$DATA['saiu05rptadireccion'] = $fila['saiu05rptadireccion'];
		$DATA['saiu05costogenera'] = $fila['saiu05costogenera'];
		$DATA['saiu05costovalor'] = $fila['saiu05costovalor'];
		$DATA['saiu05costorefpago'] = $fila['saiu05costorefpago'];
		$DATA['saiu05prioridad'] = $fila['saiu05prioridad'];
		$DATA['saiu05idzona'] = $fila['saiu05idzona'];
		$DATA['saiu05cead'] = $fila['saiu05cead'];
		$DATA['saiu05numref'] = $fila['saiu05numref'];
		$DATA['saiu05detalle'] = $fila['saiu05detalle'];
		$DATA['saiu05infocomplemento'] = $fila['saiu05infocomplemento'];
		$DATA['saiu05idresponsable'] = $fila['saiu05idresponsable'];
		$DATA['saiu05idescuela'] = $fila['saiu05idescuela'];
		$DATA['saiu05idprograma'] = $fila['saiu05idprograma'];
		$DATA['saiu05idperiodo'] = $fila['saiu05idperiodo'];
		$DATA['saiu05idcurso'] = $fila['saiu05idcurso'];
		$DATA['saiu05idgrupo'] = $fila['saiu05idgrupo'];
		$DATA['saiu05tiemprespdias'] = $fila['saiu05tiemprespdias'];
		$DATA['saiu05tiempresphoras'] = $fila['saiu05tiempresphoras'];
		$DATA['saiu05fecharespprob'] = $fila['saiu05fecharespprob'];
		$DATA['saiu05respuesta'] = $fila['saiu05respuesta'];
		$DATA['saiu05idmoduloproc'] = $fila['saiu05idmoduloproc'];
		$DATA['saiu05identificadormod'] = $fila['saiu05identificadormod'];
		$DATA['saiu05numradicado'] = $fila['saiu05numradicado'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta3005'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3005_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3005;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu05consec'])==0){$DATA['saiu05consec']='';}
	if (isset($DATA['saiu05id'])==0){$DATA['saiu05id']='';}
	if (isset($DATA['saiu05dia'])==0){$DATA['saiu05dia']='';}
	if (isset($DATA['saiu05idtiposolorigen'])==0){$DATA['saiu05idtiposolorigen']='';}
	if (isset($DATA['saiu05idtemaorigen'])==0){$DATA['saiu05idtemaorigen']='';}
	if (isset($DATA['saiu05idinteresado'])==0){$DATA['saiu05idinteresado']='';}
	if (isset($DATA['saiu05tipointeresado'])==0){$DATA['saiu05tipointeresado']='';}
	if (isset($DATA['saiu05rptaforma'])==0){$DATA['saiu05rptaforma']='';}
	if (isset($DATA['saiu05rptacorreo'])==0){$DATA['saiu05rptacorreo']='';}
	if (isset($DATA['saiu05rptadireccion'])==0){$DATA['saiu05rptadireccion']='';}
	if (isset($DATA['saiu05costogenera'])==0){$DATA['saiu05costogenera']='';}
	if (isset($DATA['saiu05costovalor'])==0){$DATA['saiu05costovalor']='';}
	if (isset($DATA['saiu05detalle'])==0){$DATA['saiu05detalle']='';}
	if (isset($DATA['saiu05idresponsable'])==0){$DATA['saiu05idresponsable']='';}
	if (isset($DATA['saiu05idmoduloproc'])==0){$DATA['saiu05idmoduloproc']='';}
	if (isset($DATA['saiu05identificadormod'])==0){$DATA['saiu05identificadormod']='';}
	if (isset($DATA['saiu05numradicado'])==0){$DATA['saiu05numradicado']='';}
	*/
	if (isset($DATA['bCambiaEst']) == 0) {
		$DATA['bCambiaEst'] = 1;
	}
	if (isset($DATA['iCodModulo']) == 0) {
		$iCodModulo = 3005;
	} else {
		$iCodModulo = $DATA['iCodModulo'];
	}
	$DATA['saiu05consec'] = numeros_validar($DATA['saiu05consec']);
	$DATA['saiu05hora'] = numeros_validar($DATA['saiu05hora']);
	$DATA['saiu05minuto'] = numeros_validar($DATA['saiu05minuto']);
	$DATA['saiu05idtiposolorigen'] = numeros_validar($DATA['saiu05idtiposolorigen']);
	$DATA['saiu05idtemaorigen'] = numeros_validar($DATA['saiu05idtemaorigen']);
	$DATA['saiu05tipointeresado'] = numeros_validar($DATA['saiu05tipointeresado']);
	$DATA['saiu05rptaforma'] = numeros_validar($DATA['saiu05rptaforma']);
	$DATA['saiu05rptacorreo'] = cadena_Validar(trim($DATA['saiu05rptacorreo']));
	$DATA['saiu05rptadireccion'] = cadena_Validar(trim($DATA['saiu05rptadireccion']));
	$DATA['saiu05costogenera'] = numeros_validar($DATA['saiu05costogenera']);
	$DATA['saiu05costovalor'] = numeros_validar($DATA['saiu05costovalor'], true);
	$DATA['saiu05costorefpago'] = cadena_Validar(trim($DATA['saiu05costorefpago']));
	$DATA['saiu05numref'] = cadena_Validar(trim($DATA['saiu05numref']));
	$DATA['saiu05detalle'] = cadena_Validar(trim($DATA['saiu05detalle']));
	$DATA['saiu05infocomplemento'] = cadena_Validar(trim($DATA['saiu05infocomplemento']));
	$DATA['saiu05respuesta'] = cadena_Validar(trim($DATA['saiu05respuesta']));
	$DATA['saiu05idmoduloproc'] = numeros_validar($DATA['saiu05idmoduloproc']);
	$DATA['saiu05identificadormod'] = numeros_validar($DATA['saiu05identificadormod']);
	$DATA['saiu05numradicado'] = numeros_validar($DATA['saiu05numradicado']);
	$DATA['saiu05idcategoria'] = numeros_validar($DATA['saiu05idcategoria']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['saiu05origenid'] == '') {
		$DATA['saiu05origenid'] = 0;
	}
	if ($DATA['saiu05idmedio'] == '') {
		$DATA['saiu05idmedio'] = 0;
	}
	if ($DATA['saiu05rptaforma'] == '') {
		$DATA['saiu05rptaforma'] = 0;
	}
	if ($DATA['saiu05idmoduloproc'] == '') {
		$DATA['saiu05idmoduloproc'] = 0;
	}
	if ($DATA['saiu05identificadormod'] == '') {
		$DATA['saiu05identificadormod'] = 0;
	}
	if ($DATA['saiu05agno'] == '') {
		$DATA['saiu05agno'] = 0;
	}
	if ($DATA['saiu05mes'] == '') {
		$DATA['saiu05mes'] = 0;
	}
	if ($DATA['bCambiaEst'] == '') {
		$DATA['bCambiaEst'] = 1;
	}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['saiu05idsolicitante'] == 0) {
		$sError = $ERR['saiu05idsolicitante'] . $sSepara . $sError;
	}
	if ($DATA['saiu05tipointeresado'] == '') {
		$sError = $ERR['saiu05tipointeresado'] . $sSepara . $sError;
	}
	if ($DATA['saiu05detalle'] == '') {
		$sError = $ERR['saiu05detalle'] . $sSepara . $sError;
	}
	if ($DATA['saiu05idtiposolorigen'] == '') {
		$sError = $ERR['saiu05idtiposolorigen_2'] . $sSepara . $sError;
	} else {
		if ($DATA['saiu05idtemaorigen'] == '') {
			$sError = $ERR['saiu05idtemaorigen_2'] . $sSepara . $sError;
		}
	}
	if ($DATA['saiu05rptaforma'] == 1) {
		if (correo_VerificarDireccion($DATA['saiu05rptacorreo'])) {
		} else {
			$sError = $ERR['saiu05rptacorreo'] . $sSepara . $sError;
		}
	}
	if ($DATA['saiu05rptaforma'] == 2) {
		if ($DATA['saiu05rptadireccion'] == '') {
			$sError = $ERR['saiu05rptadireccion'] . $sSepara . $sError;
		}
	}
	if ($DATA['saiu05idmedio'] == '') {
		$sError = $ERR['saiu05idmedio'] . $sSepara . $sError;
	}
	if ($DATA['saiu05idcategoria'] == '') {
		$sError = $ERR['saiu05idcategoria'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	if ($DATA['saiu05rptaforma'] == 0) {
		$DATA['saiu05rptacorreo'] = '';
		$DATA['saiu05rptadireccion'] = '';
	}
	$iFechaHoy = fecha_DiaMod();
	$saiu05agno = fecha_agno();
	$saiu05mes = fecha_mes();
	$saiu05dia = fecha_dia();
	$saiu05idzona = 0;
	$saiu05cead = 0;
	$saiu05idescuela = 0;
	$saiu05idprograma = 0;
	$saiu05tiemprespdias = $DATA['saiu05tiemprespdias'];
	$saiu05tiempresphoras = $DATA['saiu05tiempresphoras'];
	$saiu05fecharespprob = $DATA['saiu05fecharespprob'];
	$saiu05fecharespdef = 0;
	$saiu05horarespdef = 0;
	$saiu05minrespdef = 0;
	$saiu05idunidadresp = $DATA['saiu05idunidadresp'];
	$saiu05idequiporesp = $DATA['saiu05idequiporesp'];
	$saiu05idsupervisor = $DATA['saiu05idsupervisor'];
	$saiu05idresponsable = $DATA['saiu05idresponsable'];
	$sContenedor = fecha_ArmarAgnoMes($DATA['saiu05agno'], $DATA['saiu05mes']);
	$sTabla05 = 'saiu05solicitud' . f3000_Contenedor($DATA['saiu05agno'], $DATA['saiu05mes']);
	$sTabla06 = 'saiu06solanotacion' . f3000_Contenedor($DATA['saiu05agno'], $DATA['saiu05mes']);
	$sTabla07 = 'saiu07anexos' . f3000_Contenedor($DATA['saiu05agno'], $DATA['saiu05mes']);
	$sTabla09 = 'saiu09cambioestado' . f3000_Contenedor($DATA['saiu05agno'], $DATA['saiu05mes']);
	$saiu05estadoorigen = $DATA['saiu05estado'];
	if ($DATA['bCambiaEst'] == 1) {
		switch ($DATA['saiu05estado']) {
			case 0:
				if (!$objDB->bexistetabla($sTabla05)) {
					$sError = $sError . 'No ha sido posible acceder al contenedor de datos';
				} else {
					$sSQL = 'SELECT saiu05estado FROM ' . $sTabla05 . ' WHERE saiu05id=' . $DATA['saiu05id'] . '';
					$result = $objDB->ejecutasql($sSQL);
					if ($fila = $objDB->sf($result)) {
						if ($fila['saiu05estado'] == 0) {
							$DATA['saiu05estado'] = 2;
						} else if ($fila['saiu05estado'] == -1) {
							$saiu05estadoorigen = $fila['saiu05estado'];
						}
					}
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' ESTADO: ' . $sSQL . '<br>';
					}
				}
				break;
			case 2:
				if (!$objDB->bexistetabla($sTabla07)) {
					$sError = $sError . 'No ha sido posible acceder al contenedor de datos de archivos';
				} else {
					$sSQL = 'SELECT saiu07idarchivo, saiu07idvalidad FROM ' . $sTabla07 . ' WHERE saiu07idsolicitud=' . $DATA['saiu05id'] . '';
					$tabla = $objDB->ejecutasql($sSQL);
					while ($fila = $objDB->sf($tabla)) {
						if ($fila['saiu07idarchivo'] != 0 && $fila['saiu07idvalidad'] == 0) {
							$sError = $sError . 'No se han validado todos los anexos';
							break;
						}
					}
				}
				if ($sError == '') {
					list($sErrorR, $sDebugR) = f3005_RevTabla_saiu05solicitud($sContenedor, $objDB);
					$sError = $sError . $sErrorR;
				}
				if ($sError == '') {
					$DATA['saiu05estado'] = 7;
				}
				break;
		}
	}
	if ($DATA['saiu05estado'] == 7) {
		if ($DATA['saiu05respuesta'] == '') {
			$sError = $ERR['saiu05respuesta'] . $sSepara . $sError;
		}
		if ($sError == '') {
			$saiu05fecharespdef = fecha_DiaMod();
			$saiu05horarespdef = fecha_hora();
			$saiu05minrespdef = fecha_minuto();
		}
	} else {
		$DATA['saiu05respuesta'] = '';
	}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu05idresponsable_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu05idresponsable_td'], $DATA['saiu05idresponsable_doc'], $objDB, 'El tercero Responsable ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu05idresponsable'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['saiu05idinteresado_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu05idinteresado_td'], $DATA['saiu05idinteresado_doc'], $objDB, 'El tercero Interesado ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu05idinteresado'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['saiu05idsolicitante_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu05idsolicitante_td'], $DATA['saiu05idsolicitante_doc'], $objDB, 'El tercero Solicitante ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu05idsolicitante'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($sError == '') {
		$bPermiso = false;
		$sSQL = 'SELECT bita27id FROM bita27equipotrabajo WHERE bita27activo=1 AND bita27idlider=' . $_SESSION['unad_id_tercero'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bPermiso = true;
		} else {
			$sSQL = 'SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28activo="S" AND bita28idtercero=' . $_SESSION['unad_id_tercero'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$bPermiso = true;
			}
		}
		if ($DATA['paso'] == 10) {
			//El codigo no es posible que sea puesto por nadie.
			//$bQuitarCodigo=true;
			$DATA['saiu05consec'] = tabla_consecutivo($sTabla05, 'saiu05consec', 'saiu05agno=' . $DATA['saiu05agno'] . ' AND saiu05mes=' . $DATA['saiu05mes'] . ' AND saiu05tiporadicado=' . $DATA['saiu05tiporadicado'] . '', $objDB);
			if ($DATA['saiu05consec'] == -1) {
				$sError = $objDB->serror;
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sTabla05 . ' WHERE saiu05agno=' . $DATA['saiu05agno'] . ' AND saiu05mes=' . $DATA['saiu05mes'] . ' AND saiu05tiporadicado=' . $DATA['saiu05tiporadicado'] . ' AND saiu05consec=' . $DATA['saiu05consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					if (seg_revisa_permiso($iCodModulo, 2, $objDB)) {
						$bPermiso = true;
					}
					if (!$bPermiso) {
						$sError = $ERR['2'];
					}
				}
			}
		} else {
			if (seg_revisa_permiso($iCodModulo, 3, $objDB)) {
				$bPermiso = true;
			}
			if (!$bPermiso) {
				$sError = $ERR['3'];
			}
		}
	}
	$bQuitarCodigo = false;
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu05id'] = tabla_consecutivo($sTabla05, 'saiu05id', '', $objDB);
			if ($DATA['saiu05id'] == -1) {
				$sError = $objDB->serror;
			}
		}
	}
	if ($sError == '') {
		$bEntra = false;
		if ($DATA['saiu05estado'] >= 0) {
			switch ($DATA['paso']) {
				case 10:
				case 12:
					$bEntra = true;
					break;
			}
		}
		if ($bEntra) {
			if (!$objDB->bexistetabla($sTabla06)) {
				$sError = $sError . 'No ha sido posible acceder al contenedor de datos de anotaciones';
			} else {
				$sSQL = 'SELECT saiu06fecha, saiu06idusuario FROM ' . $sTabla06 . ' WHERE saiu06idsolicitud=' . $DATA['saiu05id'] . ' ORDER BY saiu06fecha DESC, saiu06hora DESC, saiu06minuto DESC';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					if ($fila = $objDB->sf($tabla)) {
						if ($fila['saiu06idusuario'] != $_SESSION['unad_id_tercero']) {
							$sError = $sError . $ERR['saiu05anotacion'];
						}
					}
				} else {
					$sError = $sError . $ERR['saiu05anotacion'];
				}
			}
		}
	}
	if ($sError == '') {
		if ($DATA['saiu05estado'] < 0 || $DATA['bCambiaEst'] == 0) {
			list($aParametros, $sError, $iTipoError, $sDebugF) = f3000_ConsultaResponsable($DATA['saiu05idtemaorigen'], $DATA['saiu05idzona'], $DATA['saiu05cead'], $objDB, $bDebug);
			if ($sError == '') {
				$saiu05idunidadresp = $aParametros['idunidad'];
				$saiu05idequiporesp = $aParametros['idequipo'];
				$saiu05idsupervisor = $aParametros['idsupervisor'];
				$saiu05idresponsable = $aParametros['idresponsable'];
				$saiu05tiemprespdias = $aParametros['tiemprespdias'];
				$saiu05tiempresphoras = $aParametros['tiempresphoras'];
				if ($saiu05tiemprespdias > 0) {
					$saiu05fecharespprob = fecha_NumSumarDias($iFechaHoy, $saiu05tiemprespdias);
				}
			}
			if ($bDebug) {
				$sDebug = $sDebug . $sDebugF;
			}
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT core01idzona AS idzona, core011idcead AS idcead, core01idescuela AS idescuela, core01idprograma AS idprograma
		FROM core01estprograma
		WHERE core01idtercero = ' . $DATA['saiu05idsolicitante'] . '
		UNION
		SELECT unad11idzona AS idzona, unad11idcead AS idcead, unad11idescuela AS idescuela, unad11idprograma AS idprograma
		FROM unad11terceros
		WHERE unad11id = ' . $DATA['saiu05idsolicitante'] . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) > 0) {
			$fila = $objDB->sf($result);
			$saiu05idzona = $fila['idzona'];
			$saiu05cead = $fila['idcead'];
			$saiu05idescuela = $fila['idescuela'];
			$saiu05idprograma = $fila['idprograma'];
		}
	}
	if ($sError == '') {
		$bAsignaBorrador = false;
		if ($DATA['saiu05estado'] == 0) {
			if ($saiu05estadoorigen == -1) {
				$bAsignaBorrador = true;
			}
		}
		if ($bAsignaBorrador) {
			$sSQL = '';
			$iFechaSolicitud = fecha_ArmarNumero($DATA['saiu05dia'], $DATA['saiu05mes'], $DATA['saiu05agno']);
			$iDiasBorrador = fecha_DiasEntreFechasDesdeNumero($iFechaSolicitud, $iFechaHoy);
			$iNumDia = fecha_NumDiaSemana($iFechaHoy);
			if ($iNumDia == 0) {
				$iDiasBorrador = $iDiasBorrador + 1;
			}
			if ($iNumDia == 6) {
				$iDiasBorrador = $iDiasBorrador + 2;
			}
			if ($saiu05tiemprespdias == 0) {
				list($aParametros, $sError, $iTipoError, $sDebugF) = f3000_ConsultaResponsable($DATA['saiu05idtemaorigen'], $DATA['saiu05idzona'], $DATA['saiu05cead'], $objDB, $bDebug);
				if ($sError == '') {
					$saiu05idunidadresp = $aParametros['idunidad'];
					$saiu05idequiporesp = $aParametros['idequipo'];
					$saiu05idsupervisor = $aParametros['idsupervisor'];
					$saiu05idresponsable = $aParametros['idresponsable'];
					$saiu05tiemprespdias = $aParametros['tiemprespdias'];
					$saiu05tiempresphoras = $aParametros['tiempresphoras'];
					if ($saiu05tiemprespdias > 0) {
						$saiu05fecharespprob = fecha_NumSumarDias($iFechaHoy, $saiu05tiemprespdias);
					}
				}
				if ($bDebug) {
					$sDebug = $sDebug . $sDebugF;
				}
			}
			if ($sError == '') {
				$sSQL = $sSQL . 'UPDATE ' . $sTabla05 . ' SET ';
				$sSQL = $sSQL . 'saiu05idunidadresp = ' . $saiu05idunidadresp . ', saiu05idequiporesp = ' . $saiu05idequiporesp . ', saiu05idsupervisor = ' . $saiu05idsupervisor . ', saiu05idresponsable = ' . $saiu05idresponsable . ', saiu05tiemprespdias = ' . $saiu05tiemprespdias . ', ';
				$sSQL = $sSQL . 'saiu05tiempresphoras = ' . $saiu05tiempresphoras . ', saiu05fecharespprob = ' . $saiu05fecharespprob . ', saiu05raddesphab = ' . $iDiasBorrador . ' ';
				$sSQL = $sSQL . 'WHERE saiu05id = ' . $DATA['saiu05id'];
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false){
					$sError = $sError . 'Error al asignar solicitud<br>' . $sSQL;
				} else {
					$DATA['saiu05raddesphab'] = $iDiasBorrador;
				}
			}
		}
	}
	$bCalularTotales = false;
	if ($sError == '') {
		$DATA['saiu05detalle'] = stripslashes($DATA['saiu05detalle']);
		//Si el campo saiu05detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05detalle=addslashes($DATA['saiu05detalle']);
		$saiu05detalle = str_replace('"', '\"', $DATA['saiu05detalle']);
		$DATA['saiu05infocomplemento'] = stripslashes($DATA['saiu05infocomplemento']);
		//Si el campo saiu05infocomplemento permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05infocomplemento=addslashes($DATA['saiu05infocomplemento']);
		$saiu05infocomplemento = str_replace('"', '\"', $DATA['saiu05infocomplemento']);
		$DATA['saiu05respuesta'] = stripslashes($DATA['saiu05respuesta']);
		//Si el campo saiu05respuesta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05respuesta=addslashes($DATA['saiu05respuesta']);
		$saiu05respuesta = str_replace('"', '\"', $DATA['saiu05respuesta']);
		$bpasa = false;
		if ($DATA['paso'] == 10) {
			$DATA['saiu05agno'] = $saiu05agno;
			$DATA['saiu05mes'] = $saiu05mes;
			$DATA['saiu05dia'] = $saiu05dia;
			$DATA['saiu05origenagno'] = 0;
			$DATA['saiu05origenmes'] = 0;
			$DATA['saiu05origenid'] = 0;
			$DATA['saiu05hora'] = fecha_hora();
			$DATA['saiu05minuto'] = fecha_minuto();
			$DATA['saiu05estado'] = -1; //Guarda Borrador
			//$DATA['saiu05idmedio']=0;
			$DATA['saiu05idtemafin'] = 0;
			$DATA['saiu05idtiposolfin'] = 0;
			//$DATA['saiu05idsolicitante']=0; //$_SESSION['unad_id_tercero'];
			$DATA['saiu05costogenera'] = 0;
			$DATA['saiu05costorefpago'] = '';
			$DATA['saiu05prioridad'] = 0;
			$DATA['saiu05idzona'] = $saiu05idzona;
			$DATA['saiu05cead'] = $saiu05cead;
			$DATA['saiu05numref'] = $sContenedor . '-' . $DATA['saiu05id'] . '-' . strtoupper(substr(str_shuffle(md5($DATA['saiu05id'])), 0, 5));
			$DATA['saiu05idescuela'] = $saiu05idescuela;
			$DATA['saiu05idprograma'] = $saiu05idprograma;
			$DATA['saiu05idperiodo'] = 0;
			$DATA['saiu05idcurso'] = 0;
			$DATA['saiu05idgrupo'] = 0;
			$DATA['saiu05tiemprespdias'] = $saiu05tiemprespdias;
			$DATA['saiu05tiempresphoras'] = $saiu05tiempresphoras;
			$DATA['saiu05fecharespprob'] = $saiu05fecharespprob; //fecha_hoy();
			$DATA['saiu05numradicado'] = 0;
			// $DATA['saiu05idcategoria']=0;
			$DATA['saiu05idunidadresp'] = $saiu05idunidadresp;
			$DATA['saiu05idequiporesp'] = $saiu05idequiporesp;
			$DATA['saiu05idsupervisor'] = $saiu05idsupervisor;
			$DATA['saiu05idresponsable'] = $saiu05idresponsable;
			$sCampos3005 = 'saiu05agno, saiu05mes, saiu05tiporadicado, saiu05consec, saiu05id, 
saiu05origenagno, saiu05origenmes, saiu05origenid, saiu05dia, saiu05hora, 
saiu05minuto, saiu05estado, saiu05idmedio, saiu05idtiposolorigen, saiu05idtemaorigen, 
saiu05idtemafin, saiu05idtiposolfin, saiu05idsolicitante, saiu05idinteresado, saiu05tipointeresado, 
saiu05rptaforma, saiu05rptacorreo, saiu05rptadireccion, saiu05costogenera, saiu05costovalor, 
saiu05costorefpago, saiu05prioridad, saiu05idzona, saiu05cead, saiu05numref, 
saiu05detalle, saiu05infocomplemento, saiu05idunidadresp, saiu05idequiporesp, saiu05idsupervisor, saiu05idresponsable, saiu05idescuela, saiu05idprograma, 
saiu05idperiodo, saiu05idcurso, saiu05idgrupo, saiu05tiemprespdias, saiu05tiempresphoras, 
saiu05fecharespprob, saiu05respuesta, saiu05idmoduloproc, saiu05identificadormod, saiu05numradicado, saiu05idcategoria';
			$sValores3005 = '' . $DATA['saiu05agno'] . ', ' . $DATA['saiu05mes'] . ', ' . $DATA['saiu05tiporadicado'] . ', ' . $DATA['saiu05consec'] . ', ' . $DATA['saiu05id'] . ', 
' . $DATA['saiu05origenagno'] . ', ' . $DATA['saiu05origenmes'] . ', ' . $DATA['saiu05origenid'] . ', ' . $DATA['saiu05dia'] . ', ' . $DATA['saiu05hora'] . ', 
' . $DATA['saiu05minuto'] . ', ' . $DATA['saiu05estado'] . ', ' . $DATA['saiu05idmedio'] . ', ' . $DATA['saiu05idtiposolorigen'] . ', ' . $DATA['saiu05idtemaorigen'] . ', 
' . $DATA['saiu05idtemafin'] . ', ' . $DATA['saiu05idtiposolfin'] . ', ' . $DATA['saiu05idsolicitante'] . ', ' . $DATA['saiu05idinteresado'] . ', ' . $DATA['saiu05tipointeresado'] . ', 
' . $DATA['saiu05rptaforma'] . ', "' . $DATA['saiu05rptacorreo'] . '", "' . $DATA['saiu05rptadireccion'] . '", ' . $DATA['saiu05costogenera'] . ', ' . $DATA['saiu05costovalor'] . ', 
"' . $DATA['saiu05costorefpago'] . '", ' . $DATA['saiu05prioridad'] . ', ' . $DATA['saiu05idzona'] . ', ' . $DATA['saiu05cead'] . ', "' . $DATA['saiu05numref'] . '", 
"' . $saiu05detalle . '", "' . $saiu05infocomplemento . '", ' . $DATA['saiu05idunidadresp'] . ', ' . $DATA['saiu05idequiporesp'] . ', ' . $DATA['saiu05idsupervisor'] . ', ' . $DATA['saiu05idresponsable'] . ', ' . $DATA['saiu05idescuela'] . ', ' . $DATA['saiu05idprograma'] . ', 
' . $DATA['saiu05idperiodo'] . ', ' . $DATA['saiu05idcurso'] . ', ' . $DATA['saiu05idgrupo'] . ', ' . $DATA['saiu05tiemprespdias'] . ', ' . $DATA['saiu05tiempresphoras'] . ', 
"' . $DATA['saiu05fecharespprob'] . '", "' . $saiu05respuesta . '", ' . $DATA['saiu05idmoduloproc'] . ', ' . $DATA['saiu05identificadormod'] . ', ' . $DATA['saiu05numradicado'] . ', ' . $DATA['saiu05idcategoria'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla05 . ' (' . $sCampos3005 . ') VALUES (' . cadena_codificar($sValores3005) . ');';
				$sdetalle = $sCampos3005 . '[' . cadena_codificar($sValores3005) . ']';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla05 . ' (' . $sCampos3005 . ') VALUES (' . $sValores3005 . ');';
				$sdetalle = $sCampos3005 . '[' . $sValores3005 . ']';
			}
			$idAccion = 2;
			$bpasa = true;
		} else {
			$scampo[1] = 'saiu05dia';
			$scampo[2] = 'saiu05idcategoria';
			$scampo[3] = 'saiu05idtiposolorigen';
			$scampo[4] = 'saiu05idtemaorigen';
			$scampo[5] = 'saiu05tipointeresado';
			$scampo[6] = 'saiu05rptaforma';
			$scampo[7] = 'saiu05rptacorreo';
			$scampo[8] = 'saiu05rptadireccion';
			$scampo[9] = 'saiu05detalle';
			$scampo[10] = 'saiu05idunidadresp';
			$scampo[11] = 'saiu05idequiporesp';
			$scampo[12] = 'saiu05idsupervisor';
			$scampo[13] = 'saiu05idresponsable';
			$scampo[14] = 'saiu05estado';
			$scampo[15] = 'saiu05respuesta';
			$scampo[16] = 'saiu05fecharespdef';
			$scampo[17] = 'saiu05horarespdef';
			$scampo[18] = 'saiu05minrespdef';
			$sdato[1] = $DATA['saiu05dia'];
			$sdato[2] = $DATA['saiu05idcategoria'];
			$sdato[3] = $DATA['saiu05idtiposolorigen'];
			$sdato[4] = $DATA['saiu05idtemaorigen'];
			$sdato[5] = $DATA['saiu05tipointeresado'];
			$sdato[6] = $DATA['saiu05rptaforma'];
			$sdato[7] = $DATA['saiu05rptacorreo'];
			$sdato[8] = $DATA['saiu05rptadireccion'];
			$sdato[9] = $saiu05detalle;
			$sdato[10] = $saiu05idunidadresp;
			$sdato[11] = $saiu05idequiporesp;
			$sdato[12] = $saiu05idsupervisor;
			$sdato[13] = $saiu05idresponsable;
			$sdato[14] = $DATA['saiu05estado'];
			$sdato[15] = $saiu05respuesta;
			$sdato[16] = $saiu05fecharespdef;
			$sdato[17] = $saiu05horarespdef;
			$sdato[18] = $saiu05minrespdef;
			$numcmod = 18;
			$sWhere = 'saiu05id=' . $DATA['saiu05id'] . '';
			$sSQL = 'SELECT * FROM ' . $sTabla05 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $numcmod; $k++) {
						if (isset($filabase[$scampo[$k]]) == 0) {
							$sDebug = $sDebug . fecha_microtiempo() . ' FALLA CODIGO: Falta el campo ' . $k . ' ' . $scampo[$k] . '<br>';
						}
					}
					$bPrimera = false;
				}
				$bsepara = false;
				for ($k = 1; $k <= $numcmod; $k++) {
					if ($filabase[$scampo[$k]] != $sdato[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
						$bpasa = true;
					}
				}
			}
			if ($bpasa) {
				if ($APP->utf8 == 1) {
					$sdetalle = cadena_codificar($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla05 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla05 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bpasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' ACTUALIZACION: ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3005] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['saiu05id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				$DATA['saiu05idunidadresp'] = $saiu05idunidadresp;
				$DATA['saiu05idequiporesp'] = $saiu05idequiporesp;
				$DATA['saiu05idsupervisor'] = $saiu05idsupervisor;
				$DATA['saiu05idresponsable'] = $saiu05idresponsable;
				list($sErrorC, $sDebugC) = f3007_CargarDocumentos($DATA['saiu05agno'], $DATA['saiu05mes'], $DATA['saiu05id'], $objDB, $bDebug);
				if ($DATA['saiu05estado'] != $saiu05estadoorigen) {
					$saiu09consec = tabla_consecutivo($sTabla09, 'saiu09consec', '', $objDB);
					if ($saiu09consec == -1) {
						$sError = $objDB->serror;
					}
					$saiu09id = tabla_consecutivo($sTabla09, 'saiu09id', '', $objDB);
					if ($saiu09id == -1) {
						$sError = $objDB->serror;
					}
					if ($sError == '') {
						$iHoy = fecha_DiaMod();
						$iHora = fecha_hora();
						$iMinuto = fecha_minuto();
						$sCampos3009 = 'saiu09idsolicitud,saiu09consec,saiu09id,saiu09idestadoorigen,saiu09idestadofin,
						saiu09idusuario,saiu09fecha,saiu09hora,saiu09minuto';
						$sValores3009 = $DATA['saiu05id'] . ',' . $saiu09consec . ',' . $saiu09id . ',' . $saiu05estadoorigen . ',' . $DATA['saiu05estado'] . ',
						' . $_SESSION['unad_id_tercero'] . ',' . $iHoy . ',' . $iHora . ',' . $iMinuto . '';
						$sSQL = 'INSERT INTO ' . $sTabla09 . '(' . $sCampos3009 . ') VALUES (' . $sValores3009 . ')';
						$result = $objDB->ejecutasql($sSQL);
						if ($result == false) {
							$sError = $ERR['falla_guardar'] . ' [3009] ..<!-- ' . $sSQL . ' -->';
						}
					}
					switch ($DATA['saiu05estado']) {
						case 0:
						case 7:
							if ($saiu05estadoorigen == -1) {
								list($sMensaje, $sErrorE, $sDebugE) = f3005_EnviaCorreosSolicitud($DATA, $sContenedor, $objDB, $bDebug, true);
							}
							list($sMensaje, $sErrorE, $sDebugE) = f3005_EnviaCorreosSolicitud($DATA, $sContenedor, $objDB, $bDebug);
							$sError = $sError . $sErrorE;
							$sDebug = $sDebug . $sDebugE;
							break;
					}
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3005 ' . $sSQL . '<br>';
					$sDebug = $sDebug . $sDebugC;
				}
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu05id'], $sdetalle, $objDB);
				}
				$DATA['paso'] = 2;
				$bCalularTotales = true;
			}
		} else {
			$DATA['paso'] = 2;
		}
	} else {
		$DATA['paso'] = $DATA['paso'] - 10;
	}
	if ($bQuitarCodigo) {
		$DATA['saiu05consec'] = '';
	}
	if ($bCalularTotales) {
		list($sErrorT, $sDebugT) = f3000_CalcularTotales($DATA['saiu05idsolicitante'], $DATA['saiu05agno'], (int)$DATA['saiu05mes'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugT;
	}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3005_db_Eliminar($iAgno, $iMes, $saiu05id, $objDB, $bDebug = false)
{
	$iCodModulo = 3005;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu05id = numeros_validar($saiu05id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sTabla5 = 'saiu05solicitud' . f3000_Contenedor($iAgno, $iMes);
		$sTabla6 = 'saiu06solanotacion' . f3000_Contenedor($iAgno, $iMes);
		$sTabla7 = 'saiu07anexos' . f3000_Contenedor($iAgno, $iMes);
		$sSQL = 'SELECT * FROM ' . $sTabla5 . ' WHERE saiu05id=' . $saiu05id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $saiu05id . '}';
		}
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3005';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['saiu05id'] . ' LIMIT 0, 1';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sError = $filaor['mensaje'];
				if ($filaor['etiqueta'] != '') {
					if (isset($ERR[$filaor['etiqueta']]) != 0) {
						$sError = $ERR[$filaor['etiqueta']];
					}
				}
				break;
			}
		}
	}
	if ($sError == '') {
		$sSQL = 'DELETE FROM ' . $sTabla6 . ' WHERE saiu06idsolicitud=' . $filabase['saiu05id'] . '';
		$tabla=$objDB->ejecutasql($sSQL);
		$sSQL = 'DELETE FROM ' . $sTabla7 . ' WHERE saiu07idsolicitud=' . $filabase['saiu05id'] . '';
		$tabla=$objDB->ejecutasql($sSQL);
		$sWhere = 'saiu05id=' . $saiu05id . '';
		//$sWhere='saiu05consec='.$filabase['saiu05consec'].' AND saiu05tiporadicado='.$filabase['saiu05tiporadicado'].' AND saiu05mes='.$filabase['saiu05mes'].' AND saiu05agno='.$filabase['saiu05agno'].'';
		$sSQL = 'DELETE FROM ' . $sTabla5 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu05id, $sTabla5 . '-' . $sWhere, $objDB);
			}
			list($sErrorT, $sDebugT) = f3000_CalcularTotales($filabase['saiu05idsolicitante'], $filabase['saiu05agno'], $filabase['saiu05mes'], $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugT;
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f3005_TituloBusqueda()
{
	return 'Busqueda de Solicitudes';
}
function f3005_ParametrosBusqueda()
{
	$sParams = '<label class="Label90">Nombre</label><label><input id="b3005nombre" name="b3005nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
}
function f3005_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3005nombre.value;
xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f3005_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
' . $sLeyenda . '
<div class="salto1px"></div>
</div>';
		return array($sLeyenda . '<input id="paginaf3005" name="paginaf3005" type="hidden" value="' . $pagina . '"/><input id="lppf3005" name="lppf3005" type="hidden" value="' . $lineastabla . '"/>', $sDebug);
		die();
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[103].'%" AND ';}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos = 'Agno, Mes, Tiporadicado, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Estado, Medio, Tiposolorigen, Temaorigen, Temafin, Tiposolfin, Solicitante, Interesado, Tipointeresado, Rptaforma, Rptacorreo, Rptadireccion, Costogenera, Costovalor, Costorefpago, Prioridad, Zona, Cead, Numref, Detalle, Infocomplemento, Responsable, Escuela, Programa, Periodo, Curso, Grupo, Tiemprespdias, Tiempresphoras, Fecharespprob, Respuesta, Moduloproc, Entificadormod, Numradicado';
	$sSQL = 'SELECT TB.saiu05agno, TB.saiu05mes, T3.saiu16nombre, TB.saiu05consec, TB.saiu05id, TB.saiu05origenagno, TB.saiu05origenmes, TB.saiu05origenid, TB.saiu05dia, TB.saiu05hora, TB.saiu05minuto, T12.saiu11nombre, T13.bita01nombre, T14.saiu02titulo, T15.saiu03titulo, T16.saiu03titulo, T17.saiu02titulo, T18.unad11razonsocial AS C18_nombre, T19.unad11razonsocial AS C19_nombre, T20.bita07nombre, T21.saiu12nombre, TB.saiu05rptacorreo, TB.saiu05rptadireccion, TB.saiu05costogenera, TB.saiu05costovalor, TB.saiu05costorefpago, T27.bita03nombre, T28.unad23nombre, T29.unad24nombre, TB.saiu05numref, TB.saiu05detalle, TB.saiu05infocomplemento, T33.unad11razonsocial AS C33_nombre, T34.core12nombre, T35.core09nombre, T36.exte02nombre, T37.unad40nombre, T38.core06consec, TB.saiu05tiemprespdias, TB.saiu05tiempresphoras, TB.saiu05fecharespprob, TB.saiu05respuesta, TB.saiu05idmoduloproc, TB.saiu05identificadormod, TB.saiu05numradicado, TB.saiu05tiporadicado, TB.saiu05estado, TB.saiu05idmedio, TB.saiu05idtiposolorigen, TB.saiu05idtemaorigen, TB.saiu05idtemafin, TB.saiu05idtiposolfin, TB.saiu05idsolicitante, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, TB.saiu05idinteresado, T19.unad11tipodoc AS C19_td, T19.unad11doc AS C19_doc, TB.saiu05tipointeresado, TB.saiu05rptaforma, TB.saiu05prioridad, TB.saiu05idzona, TB.saiu05cead, TB.saiu05idresponsable, T33.unad11tipodoc AS C33_td, T33.unad11doc AS C33_doc, TB.saiu05idescuela, TB.saiu05idprograma, TB.saiu05idperiodo, TB.saiu05idcurso, TB.saiu05idgrupo 
FROM saiu05solicitud AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T12, bita01tiposolicitud AS T13, saiu02tiposol AS T14, saiu03temasol AS T15, saiu03temasol AS T16, saiu02tiposol AS T17, unad11terceros AS T18, unad11terceros AS T19, bita07tiposolicitante AS T20, saiu12formarespuesta AS T21, bita03prioridad AS T27, unad23zona AS T28, unad24sede AS T29, unad11terceros AS T33, core12escuela AS T34, core09programa AS T35, exte02per_aca AS T36, unad40curso AS T37, core06grupos AS T38 
WHERE ' . $sSQLadd1 . ' TB.saiu05tiporadicado=T3.saiu16id AND TB.saiu05estado=T12.saiu11id AND TB.saiu05idmedio=T13.bita01id AND TB.saiu05idtiposolorigen=T14.saiu02id AND TB.saiu05idtemaorigen=T15.saiu03id AND TB.saiu05idtemafin=T16.saiu03id AND TB.saiu05idtiposolfin=T17.saiu02id AND TB.saiu05idsolicitante=T18.unad11id AND TB.saiu05idinteresado=T19.unad11id AND TB.saiu05tipointeresado=T20.bita07id AND TB.saiu05rptaforma=T21.saiu12id AND TB.saiu05prioridad=T27.bita03id AND TB.saiu05idzona=T28.unad23id AND TB.saiu05cead=T29.unad24id AND TB.saiu05idresponsable=T33.unad11id AND TB.saiu05idescuela=T34.core12id AND TB.saiu05idprograma=T35.core09id AND TB.saiu05idperiodo=T36.exte02id AND TB.saiu05idcurso=T37.unad40id AND TB.saiu05idgrupo=T38.core06id ' . $sSQLadd . '
ORDER BY TB.saiu05agno, TB.saiu05mes, TB.saiu05tiporadicado, TB.saiu05consec';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="' . $sSQLlista . '"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3005" name="paginaf3005" type="hidden" value="'.$pagina.'"/><input id="lppf3005" name="lppf3005" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
		}
	}
	$res = $sErrConsulta . $sLeyenda . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>' . $ETI['saiu05agno'] . '</b></td>
<td><b>' . $ETI['saiu05mes'] . '</b></td>
<td><b>' . $ETI['saiu05tiporadicado'] . '</b></td>
<td><b>' . $ETI['saiu05consec'] . '</b></td>
<td><b>' . $ETI['saiu05origenagno'] . '</b></td>
<td><b>' . $ETI['saiu05origenmes'] . '</b></td>
<td><b>' . $ETI['saiu05origenid'] . '</b></td>
<td><b>' . $ETI['saiu05dia'] . '</b></td>
<td><b>' . $ETI['saiu05hora'] . '</b></td>
<td><b>' . $ETI['saiu05estado'] . '</b></td>
<td><b>' . $ETI['saiu05idmedio'] . '</b></td>
<td><b>' . $ETI['saiu05idtiposolorigen'] . '</b></td>
<td><b>' . $ETI['saiu05idtemaorigen'] . '</b></td>
<td><b>' . $ETI['saiu05idtemafin'] . '</b></td>
<td><b>' . $ETI['saiu05idtiposolfin'] . '</b></td>
<td colspan="2"><b>' . $ETI['saiu05idsolicitante'] . '</b></td>
<td colspan="2"><b>' . $ETI['saiu05idinteresado'] . '</b></td>
<td><b>' . $ETI['saiu05tipointeresado'] . '</b></td>
<td><b>' . $ETI['saiu05rptaforma'] . '</b></td>
<td><b>' . $ETI['saiu05rptacorreo'] . '</b></td>
<td><b>' . $ETI['saiu05rptadireccion'] . '</b></td>
<td><b>' . $ETI['saiu05costogenera'] . '</b></td>
<td><b>' . $ETI['saiu05costovalor'] . '</b></td>
<td><b>' . $ETI['saiu05costorefpago'] . '</b></td>
<td><b>' . $ETI['saiu05prioridad'] . '</b></td>
<td><b>' . $ETI['saiu05idzona'] . '</b></td>
<td><b>' . $ETI['saiu05cead'] . '</b></td>
<td><b>' . $ETI['saiu05numref'] . '</b></td>
<td><b>' . $ETI['saiu05detalle'] . '</b></td>
<td><b>' . $ETI['saiu05infocomplemento'] . '</b></td>
<td colspan="2"><b>' . $ETI['saiu05idresponsable'] . '</b></td>
<td><b>' . $ETI['saiu05idescuela'] . '</b></td>
<td><b>' . $ETI['saiu05idprograma'] . '</b></td>
<td><b>' . $ETI['saiu05idperiodo'] . '</b></td>
<td><b>' . $ETI['saiu05idcurso'] . '</b></td>
<td><b>' . $ETI['saiu05idgrupo'] . '</b></td>
<td><b>' . $ETI['saiu05tiemprespdias'] . '</b></td>
<td><b>' . $ETI['saiu05tiempresphoras'] . '</b></td>
<td><b>' . $ETI['saiu05fecharespprob'] . '</b></td>
<td><b>' . $ETI['saiu05respuesta'] . '</b></td>
<td><b>' . $ETI['saiu05idmoduloproc'] . '</b></td>
<td><b>' . $ETI['saiu05identificadormod'] . '</b></td>
<td><b>' . $ETI['saiu05numradicado'] . '</b></td>
<td align="right">
' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
</td>
</tr>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['saiu05id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_saiu05dia = '';
		if ($filadet['saiu05dia'] != 0) {
			$et_saiu05dia = fecha_desdenumero($filadet['saiu05dia']);
		}
		$et_saiu05hora = html_TablaHoraMin($filadet['saiu05hora'], $filadet['saiu05minuto']);
		$et_saiu05fecharespprob = '';
		if ($filadet['saiu05fecharespprob'] != '00/00/0000') {
			$et_saiu05fecharespprob = $filadet['saiu05fecharespprob'];
		}
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>' . $sPrefijo . $filadet['saiu05agno'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05mes'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu16nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05consec'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05origenagno'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05origenmes'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05origenid'] . $sSufijo . '</td>
<td>' . $sPrefijo . $et_saiu05dia . $sSufijo . '</td>
<td>' . $sPrefijo . $et_saiu05hora . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['bita01nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu02titulo']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu03titulo']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu03titulo']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu02titulo']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['C18_td'] . ' ' . $filadet['C18_doc'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['C18_nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['C19_td'] . ' ' . $filadet['C19_doc'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['C19_nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['bita07nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu12nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu05rptacorreo']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu05rptadireccion']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05costogenera'] . $sSufijo . '</td>
<td align="right">' . $sPrefijo . formato_moneda($filadet['saiu05costovalor']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu05costorefpago']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['bita03nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['saiu05numref']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05detalle'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05infocomplemento'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['C33_td'] . ' ' . $filadet['C33_doc'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['C33_nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['unad40nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['core06consec']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05tiemprespdias'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05tiempresphoras'] . $sSufijo . '</td>
<td>' . $sPrefijo . $et_saiu05fecharespprob . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05respuesta'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05idmoduloproc'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05identificadormod'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['saiu05numradicado'] . $sSufijo . '</td>
<td></td>
</tr>';
	}
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return cadena_codificar($res);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f3005_RevTabla_saiu05solicitud($sContenedor, $objDB, $bDebug = false)
{
	list($sError, $sDebug) = f3005_RevisarTabla($sContenedor, $objDB, $bDebug);
	return array($sError, $sDebug);
}
function f3005_BuscaCodigoEncuesta($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}

	$sError = '';
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	$saui05numref = trim(cadena_Validar($aParametros[100], true));
	$saiu05id = 0;
	$sSepara = ', ';
	$html_encuesta = '';
	$sContenedor = '';
	if (true) {
		if ($saui05numref == '') {
			$sError = $ERR['saiu05codigo'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}

	// $objDB->xajax();
	if ($sError == '') {
		$idRespuesta = -99; //! REVISAR RESPUESTA
		$aCodigo = explode('-', $saui05numref);
		if (count($aCodigo) < 3) {
			$sError = $ERR['saui05numref'];
		} else {
			$sContenedor = $aCodigo[0];
			$sTabla05 = 'saiu05solicitud_' . $sContenedor;
			$saiu05id = $aCodigo[1];
			if (!$objDB->bexistetabla($sTabla05)) {
				$sError = $ERR['contenedor'];
			} else {
				$sSQL = 'SELECT saiu05evalfecha FROM ' . $sTabla05 . ' WHERE saiu05id = ' . $saiu05id . ' AND saiu05numref = "' . $saui05numref . '"';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					if ($fila['saiu05evalfecha'] == 0) {
						list($sErrorR, $sDebugR) = f3005_RevTabla_saiu05solicitud($sContenedor, $objDB);
						$sError = $sError . $sErrorR;
					}
				} else {
					$sError = $ERR['saui05numref'];
				}
			}
		}
	}
	if ($sError == '') {
		list($html_encuesta, $sError) = f3005_HTMLForm_Encuesta($sContenedor, $saiu05id, $idRespuesta, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		$objResponse->assign('div_saiu05formencuesta', 'innerHTML', $html_encuesta);
		$objResponse->assign('div_saiu05formencuesta', 'style.display', 'block');
		$objResponse->assign('div_saiu05formcodigo', 'style.display', 'none');
		$objResponse->script('$("select").addClass("form-control");');
	} else {
		$objResponse->call('muestramensajes("danger", "' . $sError . '")');
	}
	return $objResponse;
}
function f3005_HTMLForm_Encuesta($sContenedor, $saiu05id, $idRespuesta, $objDB)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$sTabla05 = 'saiu05solicitud_' . $sContenedor;
	$sSQLadd = '';
	$sHTML = '';
	$sError = '';
	$bModoCarga = false;
	if ($idRespuesta == -99) {
		$bModoCarga = true;
	}
	//$sSQLadd = $sSQLadd . ' AND ';
	$sSQL = 'SELECT TB.saiu05evalfecha, T5.unad11razonsocial, T1.core12nombre, 
	T2.core09nombre, T3.unad23nombre, T4.unad24nombre
	FROM ' . $sTabla05 . ' AS TB, core12escuela AS T1, core09programa AS T2, 
	unad23zona AS T3, unad24sede AS T4, unad11terceros AS T5
	WHERE TB.saiu05id=' . $saiu05id . ' AND TB.saiu05idescuela=T1.core12id AND TB.saiu05idprograma=T2.core09id 
	AND TB.saiu05idzona=T3.unad23id AND TB.saiu05cead=T4.unad24id AND TB.saiu05idsolicitante=T5.unad11id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['saiu05evalfecha'] == 0) {
			$aPreguntas = array(
				['saiu05evalamabilidad', 'saiu05evalamabmotivo'], ['saiu05evalrapidez', 'saiu05evalrapidmotivo'], ['saiu05evalclaridad', 'saiu05evalcalridmotivo'],
				['saiu05evalresolvio', 'saiu05evalsugerencias'], ['saiu05evalconocimiento', 'saiu05evalconocmotivo'], ['saiu05evalutilidad', 'saiu05evalutilmotivo']
			);
			$sHTML = $sHTML . '<form id="frmencuesta" name="frmencuesta" method="post" action="" autocomplete="off">
			<input id="sContenedor" name="sContenedor" type="hidden" value="' . $sContenedor . '" />
			<input id="saiu05id" name="saiu05id" type="hidden" value="' . $saiu05id . '" />
			<div class="form-group row">
				<div class="col-sm-6">
					<label>' . $ETI['texto_solicitante'] . '</label>
				</div>
				<div class="col-sm-6">
					<label>' . $ETI['saiu05razonsocial'] . '</label>
					<p class="font-weight-bold">' . $fila['unad11razonsocial'] . '</p>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-6">
					<label>' . $ETI['saiu05idzona'] . '</label>
					<p class="font-weight-bold">' . $fila['unad23nombre'] . '</p>
				</div>
				<div class="col-sm-6">
					<label>' . $ETI['saiu05idcentro'] . '</label>
					<p class="font-weight-bold">' . $fila['unad24nombre'] . '</p>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-sm-6">
					<label>' . $ETI['saiu05idescuela'] . '</label>
					<p class="font-weight-bold">' . $fila['core12nombre'] . '</p>
				</div>
				<div class="col-sm-6">
					<label>' . $ETI['saiu05idprograma'] . '</label>
					<p class="font-weight-bold">' . $fila['core09nombre'] . '</p>
				</div>
			</div><hr>';
			$sHTML = $sHTML . '
			<div class="text-center">
				<p>
				<strong>' . $ETI['texto_encuesta'] . '</strong>
				</p>
			</div>
			<div class="table-responsive">
				<table class="table table-sm table-hover">
					<thead>
						<tr>
						<th scope="col"></th>
						<th scope="col">' . $ETI['valor5'] . '</th>
						<th scope="col">' . $ETI['valor4'] . '</th>
						<th scope="col">' . $ETI['valor3'] . '</th>
						<th scope="col">' . $ETI['valor2'] . '</th>
						<th scope="col">' . $ETI['valor1'] . '</th>
						</tr>
					</thead>
					<tbody>';
			foreach ($aPreguntas as $aPregunta) {
				$sHTML = $sHTML . '
				<tr>
					<th scope="row">' . $ETI[$aPregunta[0]] . '
						<a class="badge badge-info float-right" data-toggle="collapse" href="#' . $aPregunta[1] . '" role="button" aria-expanded="false" aria-controls="' . $aPregunta[1] . '">
							' . $ETI['bt_motivo'] . '
						</a>					
					</th>';
				for ($i = 5; $i > 0; $i--) {
					$sId = $aPregunta[0] . $i;
					$sHTML = $sHTML . '
					<td class="text-center">
						<div class="custom-control custom-radio">
							<input class="custom-control-input" type="radio" name="' . $aPregunta[0] . '" id="' . $sId . '" value="' . $i . '" required />
							<label class="custom-control-label" for="' . $sId . '"></label>
						</div>
					</td>';
				}
				$sHTML = $sHTML . '
				</tr>
				<tr class="collapse" id="' . $aPregunta[1] . '">
					<td colspan="6">
						<input class="form-control form-control-sm" type="text" name="' . $aPregunta[1] . '" placeholder="' . $ETI['motivo'] . '">
					</td>
				</tr>';
			}
			$sHTML = $sHTML . '
					</tbody>
				</table>
			</div>
			<hr>';
			$sHTML = $sHTML . '<input type="button" id="cmdEnviaEncuesta" name="cmdEnviaEncuesta" class="btn btn-aurea px-4 float-right" title="' . $ETI['bt_enviar'] . '" value="' . $ETI['bt_enviar'] . '" onclick="enviaencuesta()">
			</form>';
		} else {
			$sHTML = $sHTML . htmlAlertas('naranja', $ETI['saiu05cerrada']);
		}
	} else {
		$sHTML = $sHTML . htmlAlertas('rojo', $ETI['saiu05noexiste']);
	}
	return array($sHTML, $sError);
}
function f3005_GuardaEncuesta($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$sError = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 0;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 0;
	}
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = 0;
	}
	if (isset($aParametros[105]) == 0) {
		$aParametros[105] = '';
	}
	if (isset($aParametros[106]) == 0) {
		$aParametros[106] = 0;
	}
	if (isset($aParametros[107]) == 0) {
		$aParametros[107] = '';
	}
	if (isset($aParametros[108]) == 0) {
		$aParametros[108] = 0;
	}
	if (isset($aParametros[109]) == 0) {
		$aParametros[109] = '';
	}
	if (isset($aParametros[110]) == 0) {
		$aParametros[110] = 0;
	}
	if (isset($aParametros[111]) == 0) {
		$aParametros[111] = '';
	}
	if (isset($aParametros[112]) == 0) {
		$aParametros[112] = 0;
	}
	if (isset($aParametros[113]) == 0) {
		$aParametros[113] = '';
	}
	if (isset($aParametros[114]) == 0) {
		$aParametros[114] = 0;
	}
	if (isset($aParametros[115]) == 0) {
		$aParametros[115] = '';
	}
	$sContenedor = cadena_Validar($aParametros[100]);
	$saiu05id = numeros_validar($aParametros[101]);
	$saiu05evalamabilidad = numeros_validar($aParametros[102]);
	$saiu05evalamabmotivo = cadena_Validar($aParametros[103]);
	$saiu05evalrapidez = numeros_validar($aParametros[104]);
	$saiu05evalrapidmotivo = cadena_Validar($aParametros[105]);
	$saiu05evalclaridad = numeros_validar($aParametros[106]);
	$saiu05evalcalridmotivo = cadena_Validar($aParametros[107]);
	$saiu05evalresolvio = numeros_validar($aParametros[108]);
	$saiu05evalsugerencias = cadena_Validar($aParametros[109]);
	$saiu05evalconocimiento = numeros_validar($aParametros[110]);
	$saiu05evalconocmotivo = cadena_Validar($aParametros[111]);
	$saiu05evalutilidad = numeros_validar($aParametros[112]);
	$saiu05evalutilmotivo = cadena_Validar($aParametros[113]);
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($sContenedor == '') {
		$sError = $ERR['fecha'] . $sSepara . $sError;
	}
	if ($saiu05id == '') {
		$sError = $ERR['saiu05id'] . $sSepara . $sError;
	}
	if ($saiu05evalamabilidad == '' || $saiu05evalamabilidad == 0) {
		$sError = $ERR['saiu05evalamabilidad'] . $sSepara . $sError;
	}
	if ($saiu05evalrapidez == '' || $saiu05evalrapidez == 0) {
		$sError = $ERR['saiu05evalrapidez'] . $sSepara . $sError;
	}
	if ($saiu05evalclaridad == '' || $saiu05evalclaridad == 0) {
		$sError = $ERR['saiu05evalclaridad'] . $sSepara . $sError;
	}
	if ($saiu05evalresolvio == '' || $saiu05evalresolvio == 0) {
		$sError = $ERR['saiu05evalresolvio'] . $sSepara . $sError;
	}
	if ($saiu05evalconocimiento == '' || $saiu05evalconocimiento == 0) {
		$sError = $ERR['saiu05evalconocimiento'] . $sSepara . $sError;
	}
	if ($saiu05evalutilidad == '' || $saiu05evalutilidad == 0) {
		$sError = $ERR['saiu05evalutilidad'] . $sSepara . $sError;
	}

	if ($sError == '') {
		$saiu05evalacepta = 1;
		$saiu05evalfecha = fecha_ArmarNumero();
		$bPasa = false;
		$sTabla05 = 'saiu05solicitud_' . $sContenedor;
		$sHTML = '';
		$sSQL = 'SELECT saiu05evalfecha FROM ' . $sTabla05 . ' WHERE saiu05id=' . $saiu05id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['saiu05evalfecha'] == 0) {
				$scampo[1] = 'saiu05evalamabilidad';
				$scampo[2] = 'saiu05evalamabmotivo';
				$scampo[3] = 'saiu05evalrapidez';
				$scampo[4] = 'saiu05evalrapidmotivo';
				$scampo[5] = 'saiu05evalclaridad';
				$scampo[6] = 'saiu05evalcalridmotivo';
				$scampo[7] = 'saiu05evalresolvio';
				$scampo[8] = 'saiu05evalsugerencias';
				$scampo[9] = 'saiu05evalconocimiento';
				$scampo[10] = 'saiu05evalconocmotivo';
				$scampo[11] = 'saiu05evalutilidad';
				$scampo[12] = 'saiu05evalutilmotivo';
				$scampo[13] = 'saiu05evalacepta';
				$scampo[14] = 'saiu05evalfecha';
				$sdato[1] = $saiu05evalamabilidad;
				$sdato[2] = $saiu05evalamabmotivo;
				$sdato[3] = $saiu05evalrapidez;
				$sdato[4] = $saiu05evalrapidmotivo;
				$sdato[5] = $saiu05evalclaridad;
				$sdato[6] = $saiu05evalcalridmotivo;
				$sdato[7] = $saiu05evalresolvio;
				$sdato[8] = $saiu05evalsugerencias;
				$sdato[9] = $saiu05evalconocimiento;
				$sdato[10] = $saiu05evalconocmotivo;
				$sdato[11] = $saiu05evalutilidad;
				$sdato[12] = $saiu05evalutilmotivo;
				$sdato[13] = $saiu05evalacepta;
				$sdato[14] = $saiu05evalfecha;
				$numcmod = 14;
				$sWhere = 'saiu05id=' . $saiu05id . '';
				$sSQL = 'SELECT * FROM ' . $sTabla05 . ' WHERE ' . $sWhere;
				$sdatos = '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) > 0) {
					$filabase = $objDB->sf($result);
					for ($k = 1; $k <= $numcmod; $k++) {
						if ($filabase[$scampo[$k]] != $sdato[$k]) {
							if ($sdatos != '') {
								$sdatos = $sdatos . ', ';
							}
							$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
							$bPasa = true;
						}
					}
				}
				if ($bPasa) {
					if ($APP->utf8 == 1) {
						$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla05 . ' SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
					} else {
						$sdetalle = $sdatos . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla05 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
					}
				}
				if ($bPasa) {
					$result = $objDB->ejecutasql($sSQL);
					if ($result == false) {
						$sError = $ERR['falla_guardar'];
					} else {
						$sHTML = htmlAlertas('verde', $ETI['saiu05gracias']);
					}
				}
			} else {
				$sHTML = htmlAlertas('naranja', $ETI['saiu05cerrada']);
			}
		} else {
			$sHTML = htmlAlertas('rojo', $ETI['saiu05noexiste']);
		}
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		$objResponse->assign('div_saiu05formencuesta', 'innerHTML', $sHTML);
		$objResponse->assign('div_saiu05formencuesta', 'style.display', 'block');
		$objResponse->assign('div_saiu05formcodigo', 'style.display', 'none');
	} else {
		$objResponse->call('muestramensajes("danger", "' . $sError . '")');
	}
	return $objResponse;
}
function f3005_HTMLNoRespondeEncuesta($sContenedor, $saiu05id, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$saiu05evalacepta = 0;
	$saiu05evalfecha = fecha_ArmarNumero();
	$bPasa = false;
	$sTabla05 = 'saiu05solicitud_' . $sContenedor;
	$sHTML = '';
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT saiu05evalfecha FROM ' . $sTabla05 . ' WHERE saiu05id=' . $saiu05id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['saiu05evalfecha'] == 0) {
			$scampo[1] = 'saiu05evalacepta';
			$scampo[2] = 'saiu05evalfecha';
			$sdato[1] = $saiu05evalacepta;
			$sdato[2] = $saiu05evalfecha;
			$numcmod = 2;
			$sWhere = 'saiu05id=' . $saiu05id . '';
			$sSQL = 'SELECT * FROM ' . $sTabla05 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				for ($k = 1; $k <= $numcmod; $k++) {
					if ($filabase[$scampo[$k]] != $sdato[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla05 . ' SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla05 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
			}
			if ($bPasa) {
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sHTML = htmlAlertas('rojo', $ERR['falla_guardar']);
				} else {
					$sHTML = htmlAlertas('verde', $ETI['saiu05gracias']);
				}
			}
		} else {
			$sHTML = $sHTML . htmlAlertas('naranja', $ETI['saiu05cerrada']);
		}
	} else {
		$sHTML = $sHTML . htmlAlertas('rojo', $ETI['saiu05noexiste']);
	}
	return $sHTML;
}
function f3005_EnviaCorreosSolicitud($DATA, $sContenedor, $objDB, $bDebug = false, $bResponsable = false, $bForzar = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$bEntra = false;
	$idTercero = 0;
	$sCorreoDestino = '';
	if ($DATA['saiu05rptaforma'] == 1) {
		$bEntra = true;
	} else {
		$bEntra = $bForzar;
	}
	if ($bEntra) {
		$bEntra = false;
		if (isset($DATA['saiu05idsolicitante']) != 0) {
			$idTercero = numeros_validar($DATA['saiu05idsolicitante']);
			if ($idTercero == $DATA['saiu05idsolicitante']) {
				if ((int)$idTercero != 0) {
					$bEntra = true;
					if (isset($DATA['saiu05rptacorreo']) != 0) {
						$sCorreoDestino = $DATA['saiu05rptacorreo'];
					}
				}
			}
		}
	}
	if ($bResponsable) {
		$bEntra = false;
		$sCorreoDestino = '';
		if (isset($DATA['saiu05idsupervisor']) != 0) {
			$idTercero = numeros_validar($DATA['saiu05idsupervisor']);
			if ((int)$idTercero != 0) {
				$bEntra = true;
			}
		}
		if ($bEntra) {
			if (isset($DATA['saiu05idresponsable']) != 0) {
				$saiu05idresponsable = numeros_validar($DATA['saiu05idresponsable']);
				if ((int)$saiu05idresponsable != 0) {
					$idTercero = $saiu05idresponsable;
				}
			}
		}
	}
	if ($bEntra) {
		list($bCorreoValido, $sDebugC) = correo_VerificarV2($sCorreoDestino);
		if ($bCorreoValido) {
			$sCorreoMensajes = $sCorreoDestino;
		} else {
			list($sCorreoMensajes, $unad11idgrupocorreo, $sError, $sDebugN) = AUREA_CorreoNotificaV2($idTercero, $objDB, $bDebug);
			if ($sError == '') {
				$bCorreoValido = true;
			}
		}
		if ($bCorreoValido) {
			list($sErrorR, $sDebugR) = f3005_RevTabla_saiu05solicitud($sContenedor, $objDB, $bDebug);
			$sError = $sError . $sErrorR;
			$sDebug = $sDebug . $sDebugR;
			if ($sError == '') {
				$sNomEntidad = '';
				$sMailSeguridad = '';
				$sURLCampus = '';
				$sURLEncuestas = '';
				$idEntidad = Traer_Entidad();
				switch ($idEntidad) {
					case 1: // UNAD FLORIDA
						$sNomEntidad = 'UNAD FLORIDA INC';
						$sMailSeguridad = 'aluna@unad.us';
						$sURLCampus = 'http://unad.us/campus/';
						$sURLEncuestas = 'http://unad.us/aurea/';
						break;
					default: // UNAD Colombia
						$sNomEntidad = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
						$sMailSeguridad = 'soporte.campus@unad.edu.co';
						$sURLCampus = 'https://campus0c.unad.edu.co/campus/';
						$sURLEncuestas = 'https://aurea.unad.edu.co/satisfaccion/';
						break;
				}
				$sCorreoCopia = '';
				$iFechaServicio = fecha_ArmarNumero($DATA['saiu05dia'],$DATA['saiu05mes'],$DATA['saiu05agno']);
				$sFechaLarga = formato_FechaLargaDesdeNumero($iFechaServicio, true);
				$sRutaImg = 'https://datateca.unad.edu.co/img/';
				$sURLDestino = 'https://aurea.unad.edu.co/sai';
				$URL = url_encode('' . $DATA['saiu05numref']);
				$sURLDestinoEnc = 'https://aurea.unad.edu.co/encuesta';
				$sURL = '' . $URL . '';
				$sConRespuesta = '';
				$sMes = date('Ym');
				$sTabla = 'aure01login' . $sMes;
				list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
				$objMail = new clsMail_Unad($objDB);
				$objMail->TraerSMTP($idSMTP);
				list($unad11razonsocial, $sErrorDet) = tabla_campoxid('unad11terceros', 'unad11razonsocial', 'unad11id', $idTercero, '{' . 'An&oacute;nimo' . '}', $objDB);
				if ($bResponsable) {
					$sTituloMensaje = $ETI['mail_asig_titulo'] . ' ' . $sNomEntidad . '';
					$sCuerpo = 'Cordial saludo.<br>
					Estimado(a) <b>' . $unad11razonsocial . '</b><br><br>
					El Sistema de Atenci&oacute;n Integral (SAI) le informa que le ha sido asignada una PQRS radicada el d&iacute;a ' . $sFechaLarga . '; 
					con referencia de consulta: <span style="color: rgb(255, 0, 0); font-size: 16px;"><strong>' . $DATA['saiu05numref'] . '</strong></span>.<br><br>
					Le invitamos a ingresar al m&oacute;dulo de Peticiones, Quejas, Reclamos y Sugerencias para iniciar el tr&aacute;mite de la solicitud.<br><br>';
				} else {
					if ($DATA['saiu05estado'] == 0) {
						$sTituloMensaje = $ETI['mail_solic_titulo'] . ' ' . $sNomEntidad . '';
						$sConRespuesta = $sConRespuesta . ' ';
					} else if ($DATA['saiu05estado'] == 7) {
						$sTituloMensaje = $ETI['mail_resp_titulo'] . ' ' . $sNomEntidad . '';
						$sConRespuesta = $sConRespuesta . ' la respuesta a ';
					}
					$sCuerpo = 'Cordial saludo.<br>
					Estimado(a) <b>' . $unad11razonsocial . '</b><br><br>
					Para la universidad Nacional Abierta y a Distancia - UNAD es muy importante atender sus solicitudes. 
					Acorde con lo anterior le informamos que' . $sConRespuesta . 'su solicitud radicada el día ' . $sFechaLarga . '; 
					puede ser consultada en el siguiente enlace:<br><a href="' . $sURLDestino . '" target="_blank">' . $sURLDestino . '</a><br>
					usando el código de radicado: <span style="color: rgb(255, 0, 0); font-size: 16px;"><strong>' . $DATA['saiu05numref'] . '</strong></span><br><br>';
					if ($DATA['saiu05estado'] == 7) {
						$sCuerpo = $sCuerpo . '<hr><p style="padding:0 5px;">' . $ETI['mail_enc'] . '</p>

				<table border="0" cellpadding="10" cellspacing="0" width="80%" style="width: 80%; max-width: 80%; min-width: 80%;">
					<tbody>
						<tr>
							<td align="center" bgcolor="#F0B429" style="font-size:22px;">
								<font face="Arial, Helvetica, sans-serif" color="#005883">
									<a style="padding: 10px 20px; color: #005883; font-size: 12px; text-decoration: none; word-wrap: break-word;" target="_blank"
									href="' . $sURLDestinoEnc . '?u=' . $sURL . '">
										<span style="font-size: 24px;">RESPONDER</span>
									</a>
								</font>
							</td>
						</tr>
						<tr>
							<td height="5">
							</td>
						</tr>
					</tbody>
				</table>

				<table border="0" cellpadding="10" cellspacing="0" width="60%" style="width: 60%; max-width: 60%; min-width: 60%;">
					<tbody>
						<tr>
							<td align="center" bgcolor="#005883" style="font-size:14px;">
								<font face="Arial, Helvetica, sans-serif" color="#ffffff">
									<a style="padding: 10px 20px; color: #ffffff; font-size: 12px; text-decoration: none; word-wrap: break-word;" target="_blank"
									href="' . $sURLDestinoEnc . '?n=' . $sURL . '">
										Si no desea responder, por favor haga clic aqu&iacute;
									</a>
								</font>
							</td>
						</tr>
						<tr>
							<td height="5">
							</td>
						</tr>
					</tbody>
				</table>

				<font face="Arial, Helvetica, sans-serif">
					<p>
						En caso de que no pueda acceder desde este correo, por favor ingrese a<br>
						<a style="padding: 10px 20px; color: #005883; word-wrap: break-word;" target="_blank"
							href="' . $sURLDestinoEnc . '">' . $sURLDestinoEnc . '
						</a><br>
						digite su n&uacute;mero de documento <br> y el c&oacute;digo <b>' . $DATA['saiu05numref'] . '</b>
					</p>
					<br>
				</font>';
					}
				}
				$sCuerpo = $sCuerpo . 'Cordialmente,<br>
				<b>Sistema de Atención Integral - SAI</b><br>';
				$sCuerpo = AUREA_HTML_EncabezadoCorreo($sTituloMensaje) . $sCuerpo . AUREA_HTML_NoResponder() . AUREA_NotificaPieDePagina() . AUREA_HTML_PieCorreo();
				$objMail->sAsunto = cadena_codificar($sTituloMensaje);
				$sMensaje = 'Se notifica al correo ' . $sCorreoMensajes;
				$objMail->addCorreo($sCorreoMensajes, $sCorreoMensajes);
				if ($sCorreoCopia != '') {
					$objMail->addCorreo($sCorreoCopia, $sCorreoCopia, 'O');
					$sMensaje = $sMensaje . ' con copia a ' . $sCorreoCopia;
				}
				if ($sError == '') {
					$objMail->sCuerpo = $sCuerpo;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Enviando respuesta de solicitud a : ' . $sCorreoMensajes . '<br>';
					}
					list($sErrorM, $sDebugM) = $objMail->EnviarV2($bDebug);
					$sError = $sError . $sErrorM;
					$sDebug = $sDebug . $sDebugM;
					if ($sError != '') {
						$sMensaje = $ERR['mail_resp_error'];
					}
				}
			}
		} else {
			$sError = 'No se ha definido un correo electr&oacute;nico v&aacute;lidado para notificar el evento.';
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Noficando Radicaci&oacute;n de PQRS</b>: No aplica para notificar.<br>';
		}
	}
	return array($sMensaje, $sError, $sDebug);
}
function elimina_archivo_saiu05idarchivo($idpadre, $iAgno, $iMes)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla05 = 'saiu05solicitud' . f3000_Contenedor($iAgno, $iMes);
	archivo_eliminar($sTabla05, 'saiu05id', 'saiu05idorigen', 'saiu05idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu05idarchivo");
	return $objResponse;
}
function f3005_ActualizarAtiende($DATA, $objDB, $bDebug = false, $idTercero)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$sTabla05 = 'saiu05solicitud' . f3000_Contenedor($DATA['saiu05agno'], $DATA['saiu05mes']);
	$sResultado = '';
	$sError = '';
	$sDebug = '';
	$iTipoError = 0;
	if (!$objDB->bexistetabla($sTabla05)) {
		$sError = $sError . $ERR['contenedor'];
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu05idzona, saiu05cead, saiu05idtemaorigen FROM ' . $sTabla05 . ' WHERE saiu05id=' . $DATA['saiu05id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			list($aParametros, $sError, $iTipoError, $sDebugF) = f3000_ConsultaResponsable($fila['saiu05idtemaorigen'], $fila['saiu05idzona'], $fila['saiu05cead'], $objDB, $bDebug);
			if ($bDebug) {
				$sDebug = $sDebug . $sDebugF;
			}
		} else {
			$sError = $sError . $ETI['saiu05noexiste'];
		}
	}
	if ($sError == '') {
		$DATA['saiu05idunidadresp'] = $aParametros['idunidad'];
		$DATA['saiu05idequiporesp'] = $aParametros['idequipo'];
		$DATA['saiu05idsupervisor'] = $aParametros['idsupervisor'];
		$DATA['saiu05idresponsable'] = $aParametros['idresponsable'];
		$DATA['saiu05tiemprespdias'] = $aParametros['tiemprespdias'];
		$DATA['saiu05tiempresphoras'] = $aParametros['tiempresphoras'];
		if ($DATA['saiu05tiemprespdias'] > 0) {
			$iFechaBase = fecha_DiaMod();
			$DATA['saiu05fecharespprob'] = fecha_NumSumarDias($iFechaBase, $DATA['saiu05tiemprespdias']);
		}
		$sSQL = 'UPDATE ' . $sTabla05 . ' SET saiu05idunidadresp=' . $DATA['saiu05idunidadresp'] . ', saiu05idequiporesp=' . $DATA['saiu05idequiporesp'] . ', saiu05idsupervisor=' . $DATA['saiu05idsupervisor'];
		$sSQL = $sSQL . ', saiu05idresponsable=' . $DATA['saiu05idresponsable'] . ', saiu05tiemprespdias=' . $DATA['saiu05tiemprespdias'] . ', saiu05tiempresphoras=' . $DATA['saiu05tiempresphoras'] . ', saiu05fecharespprob=' . $DATA['saiu05fecharespprob'];
		$sSQL = $sSQL . ' WHERE saiu05id=' . $DATA['saiu05id'] . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false){
			$sError = $ERR['reasigna'];
		} else {
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
			$iTipoError = 1;
		}
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3005_HTMLComboV2_btema($objDB, $objCombos, $valor, $vrbtipo)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('btema', $valor, true, '{' . $ETI['msg_todos'] . '}');
	$sSQL = '';
	if ((int)$vrbtipo != 0) {
		$objCombos->sAccion = 'paginarf3005()';
		$objCombos->iAncho = 450;
		$sCondi = 'saiu03tiposol="' . $vrbtipo . '"';
		if ($sCondi != '') {
			$sCondi = ' WHERE ' . $sCondi;
		}
		$sSQL = 'SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol' . $sCondi;
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3005_Combobtema($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos('n');
	$html_btema = f3005_HTMLComboV2_btema($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_btema', 'innerHTML', $html_btema);
	$objResponse->call('jQuery("#btema").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	$objResponse->call('paginarf3005');
	return $objResponse;
}
function f3005_db_EliminarBorradores($aParametros)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	$iTipoError = 0;
	$bDebug = false;
	$sDebug = '';
	$iCodModulo = 3005;
	$bAudita[4] = true;
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	$iAgno = numeros_validar($aParametros[104]);
	$iHoy = fecha_DiaMod();
	$aTablas = array();
	$iTablas = 0;
	$iNumSolicitudes = 0;
	$iDiasLimiteBorrador = 3;
	$bExistenBorradores = false;
	$sMensaje = '';
	$iCantBorradores = 0;
	if ($iAgno == '') {
		$sError = $sError . $ERR['saiu05agno'];
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu15agno, saiu15mes
		FROM saiu15historico 
		WHERE saiu15agno=' . $iAgno . ' AND saiu15tiporadicado=1
		GROUP BY saiu15agno, saiu15mes';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Historico: ' . $sSQL . '<br>';
		}
		$tabla15 = $objDB->ejecutasql($sSQL);
		while ($fila15 = $objDB->sf($tabla15)) {
			if ($fila15['saiu15mes'] < 10) {
				$sContenedor = $fila15['saiu15agno'] . '0' . $fila15['saiu15mes'];
			} else {
				$sContenedor = $fila15['saiu15agno'] . $fila15['saiu15mes'];
			}
			$iTablas++;
			$aTablas[$iTablas] = $sContenedor;
		}

		$sSQL = '';
		for ($k = 1; $k <= $iTablas; $k++) {
			$sContenedor = $aTablas[$k];
			$sTabla5 = 'saiu05solicitud_' . $sContenedor;
			$sTabla6 = 'saiu06solanotacion_' . $sContenedor;
			$sTabla7 = 'saiu07anexos_' . $sContenedor;
			$sSQL = 'SELECT saiu05id, saiu05agno, saiu05mes, saiu05dia, saiu05idsolicitante
			FROM ' . $sTabla5 . ' AS TB
			WHERE TB.saiu05tiporadicado=1 AND TB.saiu05estado=-1';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Tabla con borradores: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);		
			if ($objDB->nf($tabla) > 0) {
				$sIdsBorrador = '';
				$sIdsSolicitante = '';
				$saiu05dia = 0;
				$saiu05mes = 0;
				$saiu05agno = 0;
				while ($fila = $objDB->sf($tabla)) {
					$saiu05dia = $fila['saiu05dia'];
					$saiu05mes = $fila['saiu05mes'];
					$saiu05agno = $fila['saiu05agno'];
					$iFechaBase = fecha_ArmarNumero($saiu05dia, $saiu05mes, $saiu05agno);
					$iDias = fecha_DiasEntreFechasDesdeNumero($iFechaBase, $iHoy);
					if ($iDias > $iDiasLimiteBorrador) {
						if ($sIdsBorrador != '') {
							$sIdsBorrador = $sIdsBorrador . ',';
							$sIdsSolicitante = $sIdsSolicitante . ',';
						}
						$sIdsBorrador = $sIdsBorrador . $fila['saiu05id'];
						$sIdsSolicitante = $sIdsSolicitante . $fila['saiu05idsolicitante'];
						$iCantBorradores = $iCantBorradores + 1;
					}
				}
				if ($sIdsBorrador != '') {
					$sSQL = 'DELETE FROM ' . $sTabla6 . ' WHERE saiu06idsolicitud IN (' . $sIdsBorrador . ')';
					$tabla=$objDB->ejecutasql($sSQL);
					$sSQL = 'DELETE FROM ' . $sTabla7 . ' WHERE saiu07idsolicitud IN (' . $sIdsBorrador . ')';
					$tabla=$objDB->ejecutasql($sSQL);
					$sWhere = 'saiu05id IN (' . $sIdsBorrador . ')';
					//$sWhere='saiu05consec='.$filabase['saiu05consec'].' AND saiu05tiporadicado='.$filabase['saiu05tiporadicado'].' AND saiu05mes='.$filabase['saiu05mes'].' AND saiu05agno='.$filabase['saiu05agno'].'';
					$sSQL = 'DELETE FROM ' . $sTabla5 . ' WHERE ' . $sWhere . ';';
					$result = $objDB->ejecutasql($sSQL);
					if ($result == false) {
						$sError = $sError . $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' --><br>';
					} else {						
						list($sErrorT, $sDebugT) = f3005_CalcularTotales($sIdsSolicitante, $saiu05agno, $saiu05mes, $bAudita[4], $sIdsBorrador, $objDB, $bDebug);
						$sDebug = $sDebug . $sDebugT;
						$sMensaje = $ETI['msg_resborradorok'];
					}
				}
			} 
		}
		if ($sMensaje != '') {
			$sMensaje = $sMensaje . $iCantBorradores . '<br>';
			$iTipoError = 1;
		} else {
			$sMensaje = $sMensaje . $ETI['msg_resborradorno'] . '<br>';
		}
	}
	$sError = $sError . $sMensaje;
	list($sDetalle, $sErrorT, $sDebugTabla) = f3005_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sError = $sError . $sErrorT;
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("MensajeAlarmaV2('" . $sError . "', " . $iTipoError . ")");
	$objResponse->assign('div_f3005detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3005_CalcularTotales($sIdsSolicitante, $iAgno, $iMes, $bAudita4, $sIds, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$iCodModulo = 3005;
	$iMes = (int)$iMes;
	$sTabla = 'saiu05solicitud' . f3000_Contenedor($iAgno, $iMes);
	$sSQL = 'UPDATE saiu15historico SET saiu15numsolicitudes=0, saiu15numsupervisiones=0 WHERE saiu15idinteresado IN (' . $sIdsSolicitante . ') AND saiu15agno=' . $iAgno . ' AND saiu15mes=' . $iMes . '';
	$result = $objDB->ejecutasql($sSQL);
	$iSolicitados = 0;
	$iResponsable = 0;
	$sSQL = 'SELECT saiu05idsolicitante, saiu05tiporadicado, COUNT(saiu05id) AS Total FROM ' . $sTabla . ' WHERE saiu05idsolicitante IN (' . $sIdsSolicitante . ') GROUP BY saiu05tiporadicado';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando el mes: ' . $sSQL . '<br>';
	}
	$tabla5 = $objDB->ejecutasql($sSQL);
	while ($fila5 = $objDB->sf($tabla5)) {
		$iSolicitados = $fila5['Total'];
		$sSQL = 'SELECT saiu15id FROM saiu15historico WHERE saiu15idinteresado=' . $fila5['saiu05idsolicitante'] . ' AND saiu15agno=' . $iAgno . ' AND saiu15mes=' . $iMes . ' AND saiu15tiporadicado=' . $fila5['saiu05tiporadicado'] . '';
		$tabla15 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla15) == 0) {
			$saiu15id = tabla_consecutivo('saiu15historico', 'saiu15id', '', $objDB);
			$sSQL = 'INSERT INTO saiu15historico (saiu15idinteresado, saiu15agno, saiu15mes, saiu15tiporadicado, saiu15id, saiu15numsolicitudes, saiu15numsupervisiones) VALUES (' . $fila5['saiu05idsolicitante'] . ', ' . $iAgno . ', ' . $iMes . ', ' . $fila5['saiu05tiporadicado'] . ', ' . $saiu15id . ', ' . $iSolicitados . ', ' . $iResponsable . ')';
		} else {
			$fila15 = $objDB->sf($tabla15);
			$sSQL = 'UPDATE saiu15historico SET saiu15numsolicitudes=' . $iSolicitados . ', saiu15numsupervisiones=' . $iResponsable . ' WHERE saiu15id=' . $fila15['saiu15id'] . '';
		}
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Actualiza el historico: ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($bAudita4) {
			$aIds = explode(',', $sIds);
			foreach($aIds as $saiu05id) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu05id, $sTabla . '- saiu05estado = -1', $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
//
