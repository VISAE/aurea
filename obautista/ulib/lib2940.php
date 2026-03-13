<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2940 visa40inscripcion
*/
/** Archivo lib2940.php.
 * Libreria 2940 visa40inscripcion.
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @date viernes, 27 de febrero de 2026
 */
function f2940_HTMLComboV2_visa40idconvocatoria($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa40idconvocatoria', $valor, true, '{' . $ETI['msg_seleccione'] . '}', 0);
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.visa35id AS id, TB.visa35nombre AS nombre 
	FROM visa35convocatoria AS TB
	WHERE TB.visa35id>0
	ORDER BY TB.visa35nombre';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_HTMLComboV2_visa40idprograma($objDB, $objCombos, $valor, $vrvisa40idescuela)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa40idprograma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrvisa40idescuela != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core09id AS id, TB.core09nombre AS nombre 
		FROM core09programa AS TB
		WHERE TB.core09idescuela=' . $vrvisa40idescuela . ' 
		ORDER BY TB.core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_HTMLComboV2_visa40idcentro($objDB, $objCombos, $valor, $vrvisa40idzona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa40idcentro', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrvisa40idzona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrvisa40idzona . ' 
		ORDER BY TB.unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_HTMLComboV2_visa40idtipologia($objDB, $objCombos, $valor, $vrvisa40idconvocatoria)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa40idtipologia', $valor, true, '{' . $ETI['msg_seleccione'] . '}', 0);
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'carga_combo_visa40idsubtipo()';
	$sSQL = '';
	if ((int)$vrvisa40idconvocatoria != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT T36.visa36id AS id, T36.visa36nombre AS nombre 
		FROM visa35convocatoria AS TB, visa34convtipo AS T34, visa36convtipologia AS T36 
		WHERE TB.visa35id=' . $vrvisa40idconvocatoria . ' AND TB.visa35idtipo=T34.visa34id AND T34.visa34grupotipologia=T36.visa36idgrupo
		ORDER BY T36.visa36nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_HTMLComboV2_visa40idsubtipo($objDB, $objCombos, $valor, $vrvisa40idtipologia)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa40idsubtipo', $valor, true, '{' . $ETI['msg_seleccione'] . '}', 0);
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrvisa40idtipologia != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.visa37id AS id, TB.visa37nombre AS nombre 
		FROM visa37convsubtipo AS TB
		WHERE TB.visa37idtipologia=' . $vrvisa40idtipologia . ' 
		ORDER BY TB.visa37nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_Combovisa40idprograma($aParametros)
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
	$html_visa40idprograma = f2940_HTMLComboV2_visa40idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa40idprograma', 'innerHTML', $html_visa40idprograma);
	//$objResponse->call('$("#visa40idprograma").chosen()');
	return $objResponse;
}
function f2940_Combovisa40idcentro($aParametros)
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
	$html_visa40idcentro = f2940_HTMLComboV2_visa40idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa40idcentro', 'innerHTML', $html_visa40idcentro);
	//$objResponse->call('$("#visa40idcentro").chosen()');
	return $objResponse;
}
function f2940_Combovisa40idtipologia($aParametros)
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
	$html_visa40idtipologia = f2940_HTMLComboV2_visa40idtipologia($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa40idtipologia', 'innerHTML', $html_visa40idtipologia);
	$objResponse->call('$("#visa40idtipologia").chosen({width:"100%"})');
	$objResponse->call('carga_combo_visa40idsubtipo()');
	return $objResponse;
}
function f2940_Combovisa40idsubtipo($aParametros)
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
	$html_visa40idsubtipo = f2940_HTMLComboV2_visa40idsubtipo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa40idsubtipo', 'innerHTML', $html_visa40idsubtipo);
	$objResponse->call('$("#visa40idsubtipo").chosen({width:"100%"})');
	return $objResponse;
}
function f2940_HTMLComboV2_btipologia($objDB, $objCombos, $valor, $vrbconvocatoria)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('btipologia', $valor, true, '{' . $ETI['msg_todas'] . '}', 0);
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'carga_combo_bsubtipologia()';
	$sSQL = '';
	if ((int)$vrbconvocatoria != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT T36.visa36id AS id, T36.visa36nombre AS nombre 
		FROM visa35convocatoria AS TB, visa34convtipo AS T34, visa36convtipologia AS T36 
		WHERE TB.visa35id=' . $vrbconvocatoria . ' AND TB.visa35idtipo=T34.visa34id AND T34.visa34grupotipologia=T36.visa36idgrupo
		ORDER BY T36.visa36nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_Combobtipologia($aParametros)
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
	$html_btipologia = f2940_HTMLComboV2_btipologia($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_btipologia', 'innerHTML', $html_btipologia);
	$objResponse->call('$("#btipologia").chosen({width:"100%"})');
	$objResponse->call('carga_combo_bsubtipologia()');
	$objResponse->call('paginarf2940()');
	return $objResponse;
}
function f2940_HTMLComboV2_bsubtipologia($objDB, $objCombos, $valor, $vrbtipologia)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('bsubtipologia', $valor, true, '{' . $ETI['msg_todas'] . '}', 0);
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf2940()';
	$sSQL = '';
	if ((int)$vrbtipologia != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.visa37id AS id, TB.visa37nombre AS nombre 
		FROM visa37convsubtipo AS TB
		WHERE TB.visa37idtipologia=' . $vrbtipologia . ' 
		ORDER BY TB.visa37nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_Combobsubtipologia($aParametros)
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
	$html_bsubtipologia = f2940_HTMLComboV2_bsubtipologia($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bsubtipologia', 'innerHTML', $html_bsubtipologia);
	$objResponse->call('$("#bsubtipologia").chosen({width:"100%"})');
	$objResponse->call('paginarf2940()');
	return $objResponse;
}
function f2940_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$visa40idconvocatoria = numeros_validar($datos[1]);
	if ($visa40idconvocatoria == '') {
		$bHayLlave = false;
	}
	$visa40idtercero = numeros_validar($datos[2]);
	if ($visa40idtercero == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM visa40inscripcion WHERE visa40idconvocatoria=' . $visa40idconvocatoria . ' AND visa40idtercero=' . $visa40idtercero . '';
		$res = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($res) == 0) {
			$bHayLlave = false;
		}
		$objDB->CerrarConexion();
		$objResponse = new xajaxResponse();
		if ($bHayLlave) {
			$objResponse->call('cambiapaginaV2');
		}
		$objResponse->call('carga_combo_visa40idtipologia');		
		return $objResponse;
	}
}
function f2940_Busquedas($aParametros)
{
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2940 = $APP->rutacomun . 'lg/lg_2940_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2940)) {
		$mensajes_2940 = $APP->rutacomun . 'lg/lg_2940_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2940;
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
	$iPiel = iDefinirPiel($APP, 2);
	$sTituloModulo = $ETI['titulo_2940'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'visa40idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa40idtercero_busca']) == 0) {
				$ETI['visa40idtercero_busca'] = 'Busqueda de Tercero';
			}
			$sTitulo = $ETI['visa40idtercero_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2940);
			break;
		case 'visa43usuarioaprueba':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa43usuarioaprueba_busca']) == 0) {
				$ETI['visa43usuarioaprueba_busca'] = 'Busqueda de Usuarioaprueba';
			}
			$sTitulo = $ETI['visa43usuarioaprueba_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2940);
			break;
		case 'visa44usuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa44usuario_busca']) == 0) {
				$ETI['visa44usuario_busca'] = 'Busqueda de Usuario';
			}
			$sTitulo = $ETI['visa44usuario_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2940);
			break;
	}
	$sTitulo = '<h2>' . $sTituloModulo . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f2940_HtmlBusqueda($aParametros)
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
		case 'visa40idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'visa43usuarioaprueba':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'visa44usuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2940_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	$iCodModulo = 2940;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_2900 = $APP->rutacomun . 'lg/lg_2900_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2900)) {
		$mensajes_2900 = $APP->rutacomun . 'lg/lg_2900_es.php';
	}
	require $mensajes_2900;
	*/
	$mensajes_2940 = $APP->rutacomun . 'lg/lg_2940_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2940)) {
		$mensajes_2940 = $APP->rutacomun . 'lg/lg_2940_es.php';
	}
	require $mensajes_2940;
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
	$iNumVariables = 109;
	for ($k = 103; $k <= $iNumVariables; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	$idTercero = numeros_validar($aParametros[100]);
	$sDebug = '';
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$pagina = numeros_validar($aParametros[101]);
	$lineastabla = numeros_validar($aParametros[102]);
	$bdocumento = cadena_Validar(trim($aParametros[103]));
	$bNombre = cadena_Validar(trim($aParametros[104]));
	$bconvocatoria = numeros_validar($aParametros[105]);
	$bestado = numeros_validar($aParametros[106]);
	$btipologia = numeros_validar($aParametros[107]);
	$bsubtipologia = numeros_validar($aParametros[108]);
	$bCampus = numeros_validar($aParametros[109]);
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
	$sBotones = '<input id="paginaf2940" name="paginaf2940" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2940" name="lppf2940" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	/*
	$aEstado = array('');
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['id']] = cadena_notildes($fila['nombre']);
	}
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($bdocumento != '') {
		$sSQLadd = $sSQLadd . ' AND T3.unad11doc LIKE "%' . $bdocumento . '%"';
	}
	if ((int)$bconvocatoria != 0) {
		$sSQLadd1 = $sSQLadd1 . 'TB.visa40idconvocatoria=' . $bconvocatoria . ' AND ';
	}
	if ((int)$bestado != 0) {
		$sSQLadd1 = $sSQLadd1 . 'TB.visa40estado=' . $bestado . ' AND ';
	}
	if ((int)$btipologia != 0) {
		$sSQLadd1 = $sSQLadd1 . 'TB.visa40idtipologia=' . $btipologia . ' AND ';
	}
	if ((int)$bsubtipologia != 0) {
		$sSQLadd1 = $sSQLadd1 . 'TB.visa40idsubtipo=' . $bsubtipologia . ' AND ';
	}
	if ($bNombre != '') {
		$sBase = mb_strtoupper($bNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T3.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'TB.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	if ((int)$bCampus != 0) {
		$sSQLadd1 = $sSQLadd1 . 'TB.visa40idtercero=' . $idTercero . ' AND ';
	}
	$sSQLadd1 = $sSQLadd1 . 'T96.unad96idmodulo=' . $iCodModulo . ' AND ';
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Id, Convocatoria, Tercero, Estado, Fechainsc';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa40id, T2.visa35nombre, T3.unad11razonsocial AS C3_nombre, TB.visa40estado, TB.visa40fechainsc, 
	TB.visa40idconvocatoria, TB.visa40idtercero, T3.unad11tipodoc AS C3_td, T3.unad11doc AS C3_doc, T96.unad96nombre AS estadoins';
	$sConsulta = 'FROM visa40inscripcion AS TB, visa35convocatoria AS T2, unad11terceros AS T3, unad96estado AS T96 
	WHERE ' . $sSQLadd1 . ' TB.visa40id>0 AND TB.visa40idconvocatoria=T2.visa35id AND TB.visa40idtercero=T3.unad11id AND TB.visa40estado=T96.unad96id' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa40idconvocatoria, TB.visa40idtercero';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2940: ' . $sSQLContador . '');
		}
		$tabladetalle = $objDB->ejecutasql($sSQLContador);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sSQL = $objDB->sSQLPaginar($sCampos, $sConsulta, $sOrden, $rbase, $lineastabla);
		}
	}
	$sErrConsulta = '<input id="consulta_2940" name="consulta_2940" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2940" name="titulos_2940" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2940: ' . $sSQL . '');
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			/*
			if ($registros == 0) {
				return array($sErrConsulta . $sBotones, $sDebug);
			}
			*/
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$sSQLLimitado = $objDB->sSQLPaginar($sCampos, $sConsulta, $sOrden, $rbase, $lineastabla);
				$tabladetalle = $objDB->ejecutasql($sSQLLimitado);
			}
		}
	}
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Termina la consulta 2940');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa40idconvocatoria'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['visa40idtercero'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa40estado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa40fechainsc'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2940', $registros, $lineastabla, $pagina, 'paginarf2940()');
	$res = $res . html_lpp('lppf2940', $lineastabla, 'paginarf2940()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['visa40estado']) {
			case 7:
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_visa40idconvocatoria = $sPrefijo . cadena_notildes($filadet['visa35nombre']) . $sSufijo;
		$et_visa40idtercero_doc = '';
		$et_visa40idtercero_nombre = '';
		if ($filadet['visa40idtercero'] != 0) {
			$et_visa40idtercero_doc = $sPrefijo . $filadet['C3_td'] . ' ' . $filadet['C3_doc'] . $sSufijo;
			$et_visa40idtercero_nombre = $sPrefijo . cadena_notildes($filadet['C3_nombre']) . $sSufijo;
		}
		$et_visa40estado = $sPrefijo . $filadet['estadoins'] . $sSufijo;
		$et_visa40fechainsc = '';
		if ($filadet['visa40fechainsc'] != 0) {
			$et_visa40fechainsc = $sPrefijo . fecha_desdenumero($filadet['visa40fechainsc']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2940(' . $filadet['visa40id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa40idconvocatoria . '</td>';
		$res = $res . '<td>' . $et_visa40idtercero_doc . '</td>';
		$res = $res . '<td>' . $et_visa40idtercero_nombre . '</td>';
		$res = $res . '<td>' . $et_visa40estado . '</td>';
		$res = $res . '<td>' . $et_visa40fechainsc . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2940_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2940_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2940detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2940_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['visa40idtercero_td'] = $APP->tipo_doc;
	$DATA['visa40idtercero_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'visa40idconvocatoria=' . $DATA['visa40idconvocatoria'] . ' AND visa40idtercero="' . $DATA['visa40idtercero'] . '"';
	} else {
		$sSQLcondi = 'visa40id=' . $DATA['visa40id'] . '';
	}
	$sSQL = 'SELECT * FROM visa40inscripcion WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['visa40idconvocatoria'] = $fila['visa40idconvocatoria'];
		$DATA['visa40idtercero'] = $fila['visa40idtercero'];
		$DATA['visa40id'] = $fila['visa40id'];
		$DATA['visa40estado'] = $fila['visa40estado'];
		$DATA['visa40idperiodo'] = $fila['visa40idperiodo'];
		$DATA['visa40idescuela'] = $fila['visa40idescuela'];
		$DATA['visa40idprograma'] = $fila['visa40idprograma'];
		$DATA['visa40idzona'] = $fila['visa40idzona'];
		$DATA['visa40idcentro'] = $fila['visa40idcentro'];
		$DATA['visa40fechainsc'] = $fila['visa40fechainsc'];
		$DATA['visa40fechaadmision'] = $fila['visa40fechaadmision'];
		$DATA['visa40numcupo'] = $fila['visa40numcupo'];
		$DATA['visa40idtipologia'] = $fila['visa40idtipologia'];
		$DATA['visa40idsubtipo'] = $fila['visa40idsubtipo'];
		$DATA['visa40idminuta'] = $fila['visa40idminuta'];
		$DATA['visa40idresolucion'] = $fila['visa40idresolucion'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2940'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2940_Cerrar($visa40id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f2940_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2940)
{
	$iCodModuloAudita = 2940;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2940 = $APP->rutacomun . 'lg/lg_2940_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2940)) {
		$mensajes_2940 = $APP->rutacomun . 'lg/lg_2940_es.php';
	}
	$mensajes_2934 = $APP->rutacomun . 'lg/lg_2934_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2934)) {
		$mensajes_2934 = $APP->rutacomun . 'lg/lg_2934_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2940;
	require $mensajes_2934;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$iHoy = fecha_DiaMod();
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['visa40idconvocatoria']) == 0) {
		$DATA['visa40idconvocatoria'] = 0;
	}
	if (isset($DATA['visa40idtercero']) == 0) {
		$DATA['visa40idtercero'] = 0;
	}
	if (isset($DATA['visa40idtercero_td']) == 0) {
		$DATA['visa40idtercero_td'] = 'CC';
	}
	if (isset($DATA['visa40idtercero_doc']) == 0) {
		$DATA['visa40idtercero_doc'] = '';
	}
	if (isset($DATA['visa40id']) == 0) {
		$DATA['visa40id'] = '';
	}
	if (isset($DATA['visa40estado']) == 0) {
		$DATA['visa40estado'] = '';
	}
	if (isset($DATA['visa40idperiodo']) == 0) {
		$DATA['visa40idperiodo'] = 0;
	}
	if (isset($DATA['visa40idescuela']) == 0) {
		$DATA['visa40idescuela'] = 0;
	}
	if (isset($DATA['visa40idprograma']) == 0) {
		$DATA['visa40idprograma'] = 0;
	}
	if (isset($DATA['visa40idzona']) == 0) {
		$DATA['visa40idzona'] = 0;
	}
	if (isset($DATA['visa40idcentro']) == 0) {
		$DATA['visa40idcentro'] = 0;
	}
	if (isset($DATA['visa40fechainsc']) == 0) {
		$DATA['visa40fechainsc'] = 0;
	}
	if (isset($DATA['visa40fechaadmision']) == 0) {
		$DATA['visa40fechaadmision'] = 0;
	}
	if (isset($DATA['visa40numcupo']) == 0) {
		$DATA['visa40numcupo'] = 0;
	}
	if (isset($DATA['visa40idtipologia']) == 0) {
		$DATA['visa40idtipologia'] = 0;
	}
	if (isset($DATA['visa40idsubtipo']) == 0) {
		$DATA['visa40idsubtipo'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['visa40idconvocatoria'] = numeros_validar($DATA['visa40idconvocatoria']);
	$DATA['visa40estado'] = numeros_validar($DATA['visa40estado']);
	$DATA['visa40idtercero'] = numeros_validar($DATA['visa40idtercero']);
	$DATA['visa40idtercero_td'] = cadena_Validar($DATA['visa40idtercero_td']);
	$DATA['visa40idtercero_doc'] = cadena_Validar($DATA['visa40idtercero_doc']);
	$DATA['visa40idperiodo'] = numeros_validar($DATA['visa40idperiodo']);
	$DATA['visa40idescuela'] = numeros_validar($DATA['visa40idescuela']);
	$DATA['visa40idprograma'] = numeros_validar($DATA['visa40idprograma']);
	$DATA['visa40idzona'] = numeros_validar($DATA['visa40idzona']);
	$DATA['visa40idcentro'] = numeros_validar($DATA['visa40idcentro']);
	$DATA['visa40fechainsc'] = numeros_validar($DATA['visa40fechainsc']);
	$DATA['visa40fechaadmision'] = numeros_validar($DATA['visa40fechaadmision']);
	$DATA['visa40idtipologia'] = numeros_validar($DATA['visa40idtipologia']);
	$DATA['visa40idsubtipo'] = numeros_validar($DATA['visa40idsubtipo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	*/
	/*
	if ($DATA['visa40estado'] == '') {
		$DATA['visa40estado'] = 0;
	}
	if ($DATA['visa40idperiodo'] == '') {
		$DATA['visa40idperiodo'] = 0;
	}
	if ($DATA['visa40idescuela'] == '') {
		$DATA['visa40idescuela'] = 0;
	}
	if ($DATA['visa40idprograma'] == '') {
		$DATA['visa40idprograma'] = 0;
	}
	if ($DATA['visa40idzona'] == '') {
		$DATA['visa40idzona'] = 0;
	}
	if ($DATA['visa40idcentro'] == '') {
		$DATA['visa40idcentro'] = 0;
	}
	if ($DATA['visa40fechainsc'] == '') {
		$DATA['visa40fechainsc'] = 0;
	}
	*/
	if ($DATA['visa40fechaadmision'] == '') {
		$DATA['visa40fechaadmision'] = 0;
	}
	/*
	if ($DATA['visa40numcupo'] == '') {
		$DATA['visa40numcupo'] = 0;
	}
	if ($DATA['visa40idtipologia'] == '') {
		$DATA['visa40idtipologia'] = 0;
	}
	if ($DATA['visa40idsubtipo'] == '') {
		$DATA['visa40idsubtipo'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ((int)$DATA['visa40idsubtipo'] == 0) {
		$sError = $ERR['visa40idsubtipo'] . $sSepara . $sError;
	}
	if ((int)$DATA['visa40idtipologia'] == 0) {
		$sError = $ERR['visa40idtipologia'] . $sSepara . $sError;
	}
	/*
	if (!fecha_NumValido($DATA['visa40fechaadmision'])) {
		//$DATA['visa40fechaadmision'] = fecha_DiaMod();
		$sError = $ERR['visa40fechaadmision'] . $sSepara . $sError;
	}
	*/
	if (!fecha_NumValido($DATA['visa40fechainsc'])) {
		//$DATA['visa40fechainsc'] = fecha_DiaMod();
		$sError = $ERR['visa40fechainsc'] . $sSepara . $sError;
	}
	/*
	if ($DATA['visa40idcentro'] == '') {
		$sError = $ERR['visa40idcentro'] . $sSepara . $sError;
	}
	if ($DATA['visa40idzona'] == '') {
		$sError = $ERR['visa40idzona'] . $sSepara . $sError;
	}
	if ($DATA['visa40idprograma'] == '') {
		$sError = $ERR['visa40idprograma'] . $sSepara . $sError;
	}
	if ($DATA['visa40idescuela'] == '') {
		$sError = $ERR['visa40idescuela'] . $sSepara . $sError;
	}
	if ($DATA['visa40idperiodo'] == '') {
		$sError = $ERR['visa40idperiodo'] . $sSepara . $sError;
	}
	*/
	if ($DATA['visa40estado'] == '') {
		$sError = $ERR['visa40estado'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['visa40idtercero'] == 0) {
		$sError = $ERR['visa40idtercero'];
	}
	if ((int)$DATA['visa40idconvocatoria'] == 0) {
		$sError = $ERR['visa40idconvocatoria'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		if ($DATA['visa40idtercero_doc'] != '') {
			$sError = tabla_terceros_existe($DATA['visa40idtercero_td'], $DATA['visa40idtercero_doc'], $objDB, 'El tercero Tercero ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['visa40idtercero'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$visa35idtipo = 0;
	$bEnFechaLimInsc = true;
	$bEnFechaRevDocs = true;
	$bCumpleArchivos = true;
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT 1 FROM visa40inscripcion WHERE visa40idconvocatoria=' . $DATA['visa40idconvocatoria'] . ' AND visa40idtercero="' . $DATA['visa40idtercero'] . '"';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$sError = $ERR['existe'];
			}
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT TB.visa35idtipo, TB.visa35idzona, TB.visa35idcentro, TB.visa35idescuela, TB.visa35idprograma, 
		TB.visa35gruponivel, TB.visa35nivelforma, TB.visa35numcupos, TB.visa35fecha_apertura, TB.visa35fecha_liminscrip, 
		TB.visa35fecha_limrevdoc, TB.visa35fecha_examenes, TB.visa35fecha_seleccion, TB.visa35fecha_ratificacion, TB.visa35fecha_cierra, 
		TB.visa35total_inscritos, TB.visa35total_autorizados, TB.visa35total_presentaex, TB.visa35total_aprobados, TB.visa35total_admitidos, 
		T34.visa34rolestudiante, T34.visa34roladministrativo, T34.visa34rolacademico, T34.visa34rolaspirante, T34.visa34rolegresado, 
		T34.visa34rolexterno, T34.visa34grupotipologia
		FROM visa35convocatoria AS TB, visa34convtipo AS T34
		WHERE TB.visa35idtipo=T34.visa34id AND TB.visa35id=' . $DATA['visa40idconvocatoria'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sSQLadd = '';
			$fila = $objDB->sf($tabla);
			$visa35idtipo = $fila['visa35idtipo'];
			if ($DATA['visa40estado'] == 0) { // Borrador
				if ($iHoy > $fila['visa35fecha_liminscrip']) {
					$bEnFechaLimInsc = false;
					$sError = $ERR['visa40fecha_liminscrip'];
				}
			}
			if ($DATA['visa40estado'] == 3) { // Devolución de documentos
				if ($iHoy > $fila['visa35fecha_limrevdoc']) {
					$sError = $ERR['visa40fecha_limrevdoc'];
				}
			}
			if ($bEnFechaLimInsc) {
				$iCupos = $fila['visa35numcupos'] - $fila['visa35total_inscritos'];
				if ($iCupos <= 0) {
					$sError = $ERR['visa40numcupos'];
				}
			}
			if ($sError == '') {
				$bCumpleRol = false;
				if ((!$bCumpleRol) && ($fila['visa34rolestudiante'] == 1)) {
					$sProgramas = '0';
					$sSQL = 'SELECT core01idtercero, GROUP_CONCAT(core01idprograma) AS programas FROM core01estprograma WHERE core01idestado=0 AND core01idtercero=' . $DATA['visa40idtercero'] . ' GROUP BY core01idtercero';
					$tablaPro = $objDB->ejecutasql($sSQL);
					if ($filaPro = $objDB->sf($tablaPro)) {
						$sProgramas = $filaPro['programas'];
					}
					$sSQLadd = $sSQLadd . ' AND TB.core16tercero=' . $DATA['visa40idtercero'];
					$sSQLadd = $sSQLadd . ' AND TB.core16idprograma IN (' . $sProgramas . ')';
					if ($fila['visa35idzona'] > 0) {
						$sSQLadd = $sSQLadd . ' AND TB.core16idzona=' . $fila['visa35idzona'];
					}
					if ($fila['visa35idcentro'] > 0) {
						$sSQLadd = $sSQLadd . ' AND TB.core16idcead=' . $fila['visa35idcentro'];
					}
					if ($fila['visa35idescuela'] > 0) {
						$sSQLadd = $sSQLadd . ' AND TB.core16idescuela=' . $fila['visa35idescuela'];
					}
					if ($fila['visa35idprograma'] > 0) {
						$sSQLadd = $sSQLadd . ' AND TB.core16idprograma=' . $fila['visa35idprograma'];
					}
					if ($fila['visa35gruponivel'] > 0) {
						$sSQLadd = $sSQLadd . ' AND T22.core22grupo=' . $fila['visa35gruponivel'];
					}
					if ($fila['visa35nivelforma'] > 0) {
						$sSQLadd = $sSQLadd . ' AND T9.cara09nivelformacion=' . $fila['visa35nivelforma'];
					}
					$sSQL = 'SELECT TB.core16peraca, TB.core16idescuela, TB.core16idprograma, TB.core16idzona, TB.core16idcead, 
					T9.cara09nivelformacion, T22.core22grupo
					FROM core16actamatricula AS TB, core09programa AS T9, core22nivelprograma AS T22
					WHERE TB.core16idprograma=T9.core09id AND T9.cara09nivelformacion=T22.core22id' . $sSQLadd . '
					ORDER BY TB.core16peraca DESC';
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' Consulta periodo activo ' . $sSQL . '');
					}
					$tablaRol = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablaRol) > 0) {
						$filaRol = $objDB->sf($tablaRol);
						$DATA['visa40idperiodo'] = $filaRol['core16peraca'];
						$DATA['visa40idescuela'] = $filaRol['core16idescuela'];
						$DATA['visa40idprograma'] = $filaRol['core16idprograma'];
						$DATA['visa40idzona'] = $filaRol['core16idzona'];
						$DATA['visa40idcentro'] = $filaRol['core16idcead'];
						$bCumpleRol = true;
					} else {
						$sError = $ERR['visa34rolactivo'] . $ETI['visa34rolestudiante'] . $sSepara . $sError;
					}
				}
				if ((!$bCumpleRol) && ($fila['visa34rolegresado'] == 1)) {
					$sError = $ERR['visa34rolactivo'] . $ETI['visa34rolegresado'] . $sSepara . $sError;
				}
				if ((!$bCumpleRol) && ($fila['visa34rolacademico'] == 1)) {
					$sError = $ERR['visa34rolactivo'] . $ETI['visa34rolacademico'] . $sSepara . $sError;
				}
				if ((!$bCumpleRol) && ($fila['visa34roladministrativo'] == 1)) {
					$sError = $ERR['visa34rolactivo'] . $ETI['visa34roladministrativo'] . $sSepara . $sError;
				}
				if ((!$bCumpleRol) && ($fila['visa34rolaspirante'] == 1)) {
					$sError = $ERR['visa34rolactivo'] . $ETI['visa34rolaspirante'] . $sSepara . $sError;
				}
				if ((!$bCumpleRol) && ($fila['visa34rolexterno'] == 1)) {
					$sError = $ERR['visa34rolactivo'] . $ETI['visa34rolexterno'] . $sSepara . $sError;
				}
			}
		} else {
			$sError = $ERR['visa40idconvocatoria'];
		}
	}

	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['visa40id'] = tabla_consecutivo('visa40inscripcion', 'visa40id', '', $objDB);
			if ($DATA['visa40id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['visa40estado'] = 0;
			$DATA['visa40fechainsc'] = $iHoy;
			$DATA['visa40fechaadmision'] = 0; //fecha_DiaMod();
			$DATA['visa40numcupo'] = 0;
			$DATA['visa40idminuta'] = 0;
			$DATA['visa40idresolucion'] = 0;
		} else {			
			$sSQL = 'SELECT T42.visa42titulo, T42.visa42obligatorio, T43.visa43idarchivo, T43.visa43fechaaprob 
			FROM visa43inscripdocs AS T43, visa42convanexo AS T42 
			WHERE T43.visa43idinscripcion=' . $DATA['visa40id'] . ' AND T42.visa42idtipo=' . $visa35idtipo . ' AND T43.visa43iddocumento=T42.visa42id';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				if (($fila['visa43idarchivo'] == 0) && ($fila['visa42obligatorio'] == 1)) {
					$sError = $ERR['visa40faltaarchivo'] . $fila['visa42titulo'] . $sSepara . $sError;
					$bCumpleArchivos = false;
				}
			}
			if ($bCumpleArchivos) {
				list($sErrorC, $sDebugC, $sMensaje) = f2940_CambiaEstado($DATA['visa40id'], $DATA['visa40estado'], 1, '', 0, $objDB, $bDebug);
				$sDebug = $sDebug . $sDebugC;
				if ($sErrorC == '') {
					$DATA['visa40estado'] = 1;
					$DATA['visa40fechainsc'] = $iHoy;
				} else {
					$sError = $sError . $sErrorC;
				}
			}
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2940 = 'visa40idconvocatoria, visa40idtercero, visa40id, visa40estado, visa40idperiodo, 
			visa40idescuela, visa40idprograma, visa40idzona, visa40idcentro, visa40fechainsc, 
			visa40fechaadmision, visa40numcupo, visa40idtipologia, visa40idsubtipo, visa40idminuta, 
			visa40idresolucion';
			$sValores2940 = '' . $DATA['visa40idconvocatoria'] . ', ' . $DATA['visa40idtercero'] . ', ' . $DATA['visa40id'] . ', ' . $DATA['visa40estado'] . ', ' . $DATA['visa40idperiodo'] . ', 
			' . $DATA['visa40idescuela'] . ', ' . $DATA['visa40idprograma'] . ', ' . $DATA['visa40idzona'] . ', ' . $DATA['visa40idcentro'] . ', ' . $DATA['visa40fechainsc'] . ', 
			' . $DATA['visa40fechaadmision'] . ', ' . $DATA['visa40numcupo'] . ', ' . $DATA['visa40idtipologia'] . ', ' . $DATA['visa40idsubtipo'] . ', ' . $DATA['visa40idminuta'] . ', 
			' . $DATA['visa40idresolucion'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa40inscripcion (' . $sCampos2940 . ') VALUES (' . cadena_codificar($sValores2940) . ');';
				$sDetalle = $sCampos2940 . '[' . cadena_codificar($sValores2940) . ']';
			} else {
				$sSQL = 'INSERT INTO visa40inscripcion (' . $sCampos2940 . ') VALUES (' . $sValores2940 . ');';
				$sDetalle = $sCampos2940 . '[' . $sValores2940 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'visa40idperiodo';
			$sCampo[2] = 'visa40idescuela';
			$sCampo[3] = 'visa40idprograma';
			$sCampo[4] = 'visa40idzona';
			$sCampo[5] = 'visa40idcentro';
			$sCampo[6] = 'visa40fechainsc';
			$sCampo[7] = 'visa40fechaadmision';
			$sCampo[8] = 'visa40idtipologia';
			$sCampo[9] = 'visa40idsubtipo';
			$sDato[1] = $DATA['visa40idperiodo'];
			$sDato[2] = $DATA['visa40idescuela'];
			$sDato[3] = $DATA['visa40idprograma'];
			$sDato[4] = $DATA['visa40idzona'];
			$sDato[5] = $DATA['visa40idcentro'];
			$sDato[6] = $DATA['visa40fechainsc'];
			$sDato[7] = $DATA['visa40fechaadmision'];
			$sDato[8] = $DATA['visa40idtipologia'];
			$sDato[9] = $DATA['visa40idsubtipo'];
			$iNumCamposMod = 9;
			$sWhere = 'visa40id=' . $DATA['visa40id'] . '';
			$sSQL = 'SELECT * FROM visa40inscripcion WHERE ' . $sWhere;
			$sDatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $iNumCamposMod; $k++) {
						if (isset($filabase[$sCampo[$k]]) == 0) {
							$sDebug = $sDebug . log_debug(' FALLA CODIGO: Falta el campo ' . $k . ' ' . $sCampo[$k] . '');
						}
					}
					$bPrimera = false;
				}
				$sSepara = '';
				for ($k = 1; $k <= $iNumCamposMod; $k++) {
					if ($filabase[$sCampo[$k]] != $sDato[$k]) {
						$sDatos = $sDatos . $sSepara . $sCampo[$k] . '="' . $sDato[$k] . '"';
						$bPasa = true;
						$sSepara = ', ';
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sDetalle = cadena_codificar($sDatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE visa40inscripcion SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE visa40inscripcion SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2940 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2940] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['visa40id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['visa40id'], $sDetalle, $objDB);
				}
				//Acciones en el insertar.
				if ($DATA['paso'] == 10) {
					list($sErrorC, $sDebugC) = f2943_CargarDocumentos($DATA['visa40idconvocatoria'], $DATA['visa40id'], $objDB, $bDebug);
				}
				$DATA['paso'] = 2;
			}
		} else {
			$DATA['paso'] = 2;
		}
	} else {
		if ($DATA['paso'] == 10) {
			$DATA['paso'] = 0;
		} else {
			$DATA['paso'] = 2;
		}
		if ($bQuitarCodigo) {
			if ($sCampoCodigo != '') {
				$DATA[$sCampoCodigo] = '';
			}
		}
	}
	/*
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' InfoDepura');
	}
	*/
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2940_db_Eliminar($visa40id, $objDB, $bDebug = false)
{
	$iCodModulo = 2940;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2940 = $APP->rutacomun . 'lg/lg_2940_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2940)) {
		$mensajes_2940 = $APP->rutacomun . 'lg/lg_2940_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2940;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$visa40id = numeros_validar($visa40id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM visa40inscripcion WHERE visa40id=' . $visa40id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $visa40id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM visa43inscripdocs WHERE visa43idinscripcion=' . $filabase['visa40id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Anexos creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM visa44anotaciones WHERE visa44idinscripcion=' . $filabase['visa40id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Anotaciones creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM visa45convpruebares WHERE visa45idinscripcion=' . $filabase['visa40id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Resultados pruebas creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		if (isset($idTercero) == 0) {
			$idTercero = $_SESSION['unad_id_tercero'];
		}
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2940';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['visa40id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM visa43inscripdocs WHERE visa43idinscripcion=' . $filabase['visa40id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM visa44anotaciones WHERE visa44idinscripcion=' . $filabase['visa40id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM visa45convpruebares WHERE visa45idinscripcion=' . $filabase['visa40id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'visa40id=' . $visa40id . '';
		//$sWhere = 'visa40idtercero="' . $filabase['visa40idtercero'] . '" AND visa40idconvocatoria=' . $filabase['visa40idconvocatoria'] . '';
		$sSQL = 'DELETE FROM visa40inscripcion WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa40id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

function f2940_CambiaEstado($visa40id, $iEstadoOrigen, $iEstadoDestino, $sDetalle, $idUsuario, $objDB, $bDebug = false)
{
	$iCodModulo = 2940;
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$bNotificar = false;
	$sSQL = 'SELECT visa40estado FROM visa40inscripcion WHERE visa40id=' . $visa40id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		if ($filabase['visa40estado'] != $iEstadoOrigen) {
			$sError = 'El estado de origen no coincide [' . $filabase['visa40estado'] . '].';
		}
	} else {
		$sError = 'No se ha encontrado el registro solicitado [Ref ' . $visa40id . ']';
	}
	if ($sError == '') {
		$sInfoCambio = 'Cambia el estado a ' . $iEstadoDestino;
		$sDatosAdd = '';
		switch ($iEstadoDestino) {
			case 0:
				break;
		}
	}
	if ($sError == '') {
		//Guardar el historial del cambio...
	}
	if ($sError == '') {
		$sSQL = 'UPDATE visa40inscripcion SET visa40estado=' . $iEstadoDestino . '' . $sDatosAdd . ' WHERE visa40id=' . $visa40id . '';
		$result = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa40id, $sInfoCambio, $objDB);
	}
	if ($bNotificar) {
		list($sError, $sDebugN, $sMensaje) = f2940_Notificar($visa40id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugN;
	}
	return array($sError, $sDebug, $sMensaje);
}

function f2940_Notificar($visa40id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$iHoy = fecha_DiaMod();
	$idInteresado = 0;
	$sSQL = 'SELECT * 
	FROM visa40inscripcion AS TB
	WHERE TB.visa40id=' . $visa40id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		$idInteresado = $filabase['id_interesado'];
		$visa40estado = $filabase['visa40estado'];
	} else {
		$sError = 'No se ha encontrado el registro solicitado [Ref ' . $visa40id . ']';
	}
	if ($sError == '') {
		$sTituloMensaje = 'Notificación de ... ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '';
		$sCuerpo = 'Estimado usuario:<br><br>';
		switch ($visa40estado) {
			case 0:
				break;
		}
	}
	if ($sError == '') {
		list($sCorreoUsuario, $sErrorN, $sDebugM) = AUREA_CorreoNotifica($idSolicitante, $objDB, $bDebug);
		if ($sCorreoUsuario == '') {
			$sError = 'El usuario no registra correo de notificaciones.';
		}
	}
	if ($sError == '') {
		$sCuerpo = $sCuerpo . AUREA_HTML_NoResponderSII();
		$sCorreoCopia = '';
		$sCuerpo = AUREA_HTML_EncabezadoCorreo($sTituloMensaje) . $sCuerpo . AUREA_HTML_PieCorreo();
		$sMes = date('Ym');
		$sTabla = 'aure01login' . $sMes;
		list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
		$objMail = new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto = cadena_codificar($sTituloMensaje);
		$sMensaje = 'Se notifica al correo ' . $sCorreoUsuario;
		$objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario);
		if ($sCorreoCopia != '') {
			$objMail->addCorreo($sCorreoCopia, $sCorreoCopia, 'O');
			$sMensaje = $sMensaje . ' con copia a ' . $sCorreoCopia;
		}
		if ($sError == '') {
			$objMail->sCuerpo = $sCuerpo;
			$sError = $objMail->Enviar($bDebug);
			if ($sError != '') {
				$sMensaje = '';
			}
		}
	}
	return array($sError, $sDebug, $sMensaje);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f2940_ConsultaUltimaMatricula($idTercero, $objDB, $bDebug = false) {
	$sError = '';
	$sDebug = '';
	$aDatos = array(
		'idperiodo' => 0,
		'idescuela' => 0,
		'idprograma' => 0,
		'idzona' => 0,
		'idcentro' => 0
	);
	if ($idTercero > 0) {
		$sSQL = 'SELECT TB.core16peraca, TB.core16idescuela, TB.core16idprograma, TB.core16idzona, TB.core16idcead
		FROM core16actamatricula AS TB
		WHERE TB.core16tercero=' . $idTercero . '
		ORDER BY TB.core16peraca DESC';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Consulta ultima matricula: ' . $sSQL . '');
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aDatos['idperiodo'] = $fila['core16peraca'];
			$aDatos['idescuela'] = $fila['core16idescuela'];
			$aDatos['idprograma'] = $fila['core16idprograma'];
			$aDatos['idzona'] = $fila['core16idzona'];
			$aDatos['idcentro'] = $fila['core16idcead'];
		} else {
			$sError = $sError . 'No se encuentran datos de matr&iacute;cula.';
		}
	}
	return array($sError, $sDebug, $aDatos);
}
