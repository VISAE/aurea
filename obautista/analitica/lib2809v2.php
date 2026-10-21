<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.14 miércoles, 5 de marzo de 2025
--- 2809 core16actamatricula
*/
/** Archivo lib2809.php.
 * Libreria 2809 core16actamatricula.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date miércoles, 5 de marzo de 2025
 */
function f2809_HTMLComboV2_core16idciclo($objDB, $objCombos, $valor, $vrcore16idagno)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16idciclo', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'carga_combo_core16idbloque()';
	$sSQL = '';
	if ((int)$vrcore16idagno != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unae17id AS id, TB.unae17nombre AS nombre 
		FROM unae17cicloacadem AS TB
		WHERE TB.unae17agno=' . $vrcore16idagno . ' 
		ORDER BY TB.unae17nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2809_HTMLComboV2_core16idbloque($objDB, $objCombos, $valor, $vrcore16idciclo)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16idbloque', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'carga_combo_core16peraca()';
	$sSQL = '';
	if ((int)$vrcore16idciclo != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$unaf15idsemestre = 0;
		$sSQL = 'SELECT unae17periodo FROM unae17cicloacadem WHERE unae17id=' . $vrcore16idciclo . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$unaf15idsemestre = $fila['unae17periodo'];
		}
		$sSQL = 'SELECT TB.unaf15id AS id, TB.unaf15nombre AS nombre 
		FROM unaf15bloqueperiodo AS TB
		WHERE TB.unaf15idsemestre=' . $unaf15idsemestre . ' 
		ORDER BY TB.unaf15nombre';
	} else {
		$sSQL = 'SELECT TB.unaf15id AS id, TB.unaf15nombre AS nombre 
		FROM unaf15bloqueperiodo AS TB
		ORDER BY TB.unaf15activo DESC, TB.unaf15orden';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2809_HTMLComboV2_core16peraca($objDB, $objCombos, $valor, $vrcore16idagno, $vrcore16idciclo, $vrcore16idbloque)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16peraca', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	$objCombos->iAncho = 370;
	$objCombos->sAccion = 'cambiapagina();';
	$sCondi = '';
	if ($vrcore16idciclo != 0) {
		$sCondi = 'exte02idciclo=' . $vrcore16idciclo . ' AND exte02id>0';
	} else {
		if ((int)$vrcore16idagno != 0) {
			$sIds2 = '-99';
			$sSQL = 'SELECT TB.exte02id 
			FROM exte02per_aca AS TB, unae17cicloacadem AS T17 
			WHERE TB.exte02idciclo=T17.unae17id  AND T17.unae17agno=' . $vrcore16idagno . '';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds2 = $sIds2 . ',' . $fila['exte02id'];
			}
			$sCondi = 'core16peraca IN (' . $sIds2 . ')';

		} else {
			$sCondi = 'exte02id=-99';
		}
	}
	if ((int)$vrcore16idbloque != 0) {
		if ($sCondi != '') {
			$sCondi = $sCondi . ' AND ';
		}
		$sCondi = $sCondi . 'exte02bloque=' . $vrcore16idbloque . ' AND exte02id>0';
	}
	if ($sCondi == '') {
		$sCondi = 'exte02id=-99';
	}
	$sSQL = f146_ConsultaCombo($sCondi);
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2809_HTMLComboV2_core16idsnies($objDB, $objCombos, $valor, $vrcore16idescuela, $vrcore16nivelforma)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16idsnies', $valor, true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->iAncho = 370;
	$objCombos->sAccion = 'carga_combo_core16idprograma()';
	$sSQL = '';
	$sDebug = '';
	if ((int)$vrcore16idescuela != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sIds = '-99';
		$sCondi = '';
		switch ($vrcore16nivelforma) {
			case '':
				break;
			default:
				$sCondi = ' AND cara09nivelformacion=' . $vrcore16nivelforma;
				break;
		}
		$sSQL = 'SELECT T9.core09idsnies 
		FROM core09programa AS T9 
		WHERE T9.core09idescuela=' . $vrcore16idescuela . ' AND T9.core09idsnies<>0 ' . $sCondi . ' 
		GROUP BY T9.core09idsnies';
		//$sDebug = $sSQL;
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['core09idsnies'];
		}
		$sSQL = 'SELECT TB.core78id AS id, CONCAT(TB.core78codigoprog, " - ", TB.core78nombre, "") AS nombre 
		FROM core78iesprograma AS TB
		WHERE TB.core78id IN (' . $sIds . ') 
		ORDER BY TB.core78nombre';
	}
	$objCombos->addItem('NOS', 'Sin Codigo SNIES');
	$objCombos->addItem('SNI', 'Todos los SNIES');
	$res = $objCombos->html($sSQL, $objDB) . $sDebug;
	return $res;
}
function f2809_HTMLComboV2_core16idprograma($objDB, $objCombos, $valor, $vrcore16idescuela, $vrcore16nivelforma, $vrcore16idsnies)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16idprograma', $valor, true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->iAncho = 370;
	$objCombos->sAccion = 'carga_combo_core16idplanestudio()';
	$sSQL = '';
	$sCondi = '';
	switch ($vrcore16nivelforma) {
		case '':
			break;
		default:
			$sCondi = ' AND cara09nivelformacion=' . $vrcore16nivelforma;
			break;
	}
	if ((int)$vrcore16idsnies != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]") AS nombre 
		FROM core09programa AS TB
		WHERE TB.core09idsnies=' . $vrcore16idsnies . $sCondi . ' 
		ORDER BY TB.core09nombre';
	} else {
		if ((int)$vrcore16idescuela != 0) {
			$sSQL = 'SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]") AS nombre 
			FROM core09programa AS TB
			WHERE TB.core09idescuela=' . $vrcore16idescuela . $sCondi . ' 
			ORDER BY TB.core09nombre';
		}
	}
	$objCombos->addItem('DET', $ETI['msg_detallar']);
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2809_HTMLComboV2_core16idplanestudio($objDB, $objCombos, $valor, $vrcore16idprograma)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16idplanestudio', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf2809();';
	$sSQL = '';
	if ((int)$vrcore16idprograma != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core10id AS id, TB.core10consec AS nombre 
		FROM core10programaversion AS TB
		WHERE TB.core10idprograma=' . $vrcore16idprograma . ' 
		ORDER BY TB.core10consec';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2809_HTMLComboV2_core16idcead($objDB, $objCombos, $valor, $vrcore16idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16idcead', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf2809();';
	$sSQL = '';
	if ((int)$vrcore16idzona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrcore16idzona . ' 
		ORDER BY TB.unad24nombre';
	}
	$objCombos->addItem('DET', $ETI['msg_detallar']);
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2809_Combocore16idciclo($aParametros)
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
	$html_core16idciclo = f2809_HTMLComboV2_core16idciclo($objDB, $objCombos, '', $aParametros[0]);
	$html_core16idbloque = f2809_HTMLComboV2_core16idbloque($objDB, $objCombos, '', $aParametros[1]);
	$html_core16peraca = f2809_HTMLComboV2_core16peraca($objDB, $objCombos, '', $aParametros[0], $aParametros[1], $aParametros[2]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16idciclo', 'innerHTML', $html_core16idciclo);
	$objResponse->assign('div_core16idbloque', 'innerHTML', $html_core16idbloque);
	$objResponse->assign('div_core16peraca', 'innerHTML', $html_core16peraca);
	$objResponse->call('$("#core16peraca").chosen()');
	$objResponse->call('paginarf2809()');
	return $objResponse;
}
function f2809_Combocore16idbloque($aParametros)
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
	$html_core16idbloque = f2809_HTMLComboV2_core16idbloque($objDB, $objCombos, '', $aParametros[1]);
	$html_core16peraca = f2809_HTMLComboV2_core16peraca($objDB, $objCombos, '', $aParametros[0], $aParametros[1], $aParametros[2]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16idbloque', 'innerHTML', $html_core16idbloque);
	$objResponse->assign('div_core16peraca', 'innerHTML', $html_core16peraca);
	$objResponse->call('$("#core16peraca").chosen()');
	$objResponse->call('paginarf2809()');
	return $objResponse;
}
function f2809_Combocore16peraca($aParametros)
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
	$html_core16peraca = f2809_HTMLComboV2_core16peraca($objDB, $objCombos, '', $aParametros[0], $aParametros[1], $aParametros[2]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16peraca', 'innerHTML', $html_core16peraca);
	$objResponse->call('$("#core16peraca").chosen()');
	$objResponse->call('paginarf2809()');
	return $objResponse;
}
function f2809_Combocore16idsnies($aParametros)
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
	$html_core16idsnies = f2809_HTMLComboV2_core16idsnies($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$html_core16idprograma = f2809_HTMLComboV2_core16idprograma($objDB, $objCombos, '', $aParametros[0], $aParametros[1], '');
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16idsnies', 'innerHTML', $html_core16idsnies);
	$objResponse->assign('div_core16idprograma', 'innerHTML', $html_core16idprograma);
	$objResponse->call('$("#core16idsnies").chosen()');
	$objResponse->call('$("#core16idprograma").chosen()');
	$objResponse->call('paginarf2809()');
	return $objResponse;
}
function f2809_Combocore16idprograma($aParametros)
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
	$html_core16idprograma = f2809_HTMLComboV2_core16idprograma($objDB, $objCombos, '', $aParametros[0], $aParametros[1], $aParametros[2]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16idprograma', 'innerHTML', $html_core16idprograma);
	$objResponse->call('$("#core16idprograma").chosen()');
	$objResponse->call('paginarf2809()');
	return $objResponse;
}
function f2809_Combocore16idplanestudio($aParametros)
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
	$html_core16idplanestudio = f2809_HTMLComboV2_core16idplanestudio($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16idplanestudio', 'innerHTML', $html_core16idplanestudio);
	//$objResponse->call('$("#core16idplanestudio").chosen()');
	$objResponse->call('paginarf2809()');
	return $objResponse;
}
function f2809_Combocore16idcead($aParametros)
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
	$html_core16idcead = f2809_HTMLComboV2_core16idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16idcead', 'innerHTML', $html_core16idcead);
	//$objResponse->call('$("#core16idcead").chosen()');
	$objResponse->call('paginarf2809()');
	return $objResponse;
}
function f2809_Busquedas($aParametros)
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
	$mensajes_2809 = 'lg/lg_2809_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2809)) {
		$mensajes_2809 = 'lg/lg_2809_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2809;
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
	$sTituloModulo = $ETI['titulo_2809'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
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
function f2809_HtmlBusqueda($aParametros)
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
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2809_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_2800 = 'lg/lg_2800_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2800)) {
		$mensajes_2800 = 'lg/lg_2800_es.php';
	}
	require $mensajes_2800;
	*/
	$mensajes_2809 = 'lg/lg_2809_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2809)) {
		$mensajes_2809 = 'lg/lg_2809_es.php';
	}
	require $mensajes_2809;
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
	for ($k = 103; $k <= 114; $k++) {
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
	$core16idagno = $aParametros[103];
	$core16idciclo = $aParametros[104];
	$core16idbloque = $aParametros[105];
	$idPeriodo = $aParametros[106];
	$idEscuela = $aParametros[107];
	$core16nivelforma = $aParametros[108];
	$core16idsnies = $aParametros[109];
	$idPrograma = $aParametros[110];
	$idPlanEst = $aParametros[111];
	$idZona = $aParametros[112];
	$idCead = $aParametros[113];
	$idMatricula = $aParametros[114];
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
	if ((int)$core16idagno == 0) {
		$sLeyenda = 'Debe seleccionar un a&ntilde;o a consultar';
	}
	$sBotones = '<input id="paginaf2809" name="paginaf2809" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2809" name="lppf2809" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	list($bDevuelve, $sDebugP, $seg_6) = seg_revisa_permisoV3(2809, 6, $idTercero, $objDB);
	$iPiel = iDefinirPiel($APP, 2);
	$aTipoMat = array();
	$sSQL = 'SELECT core08id, core08nombre FROM core08tipomatricula';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aTipoMat[$fila['core08id']] = cadena_notildes($fila['core08nombre']);
	}
	$bIncluirSUA = true;
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sSQLaddSUA = '';
	$sEstados = '0,1,2,3';
	$bConEscuela = false;
	$bConPrograma = false;
	$bConZona = false;
	$bConCead = false;
	$idExcelEscuela = "''";
	$idExcelPrograma = "''";
	$idExcelZona = "''";
	$idExcelCentro = "''";
	if ((int)$idPeriodo != 0) {
		$sSQLadd1 = $sSQLadd1 . 'TB.core16peraca=' . $idPeriodo . ' AND ';
		$bIncluirSUA = false;
	} else {
		//Ahora descartar las otra opciones
		$sIds2 = '-99';
		$sCondi = '';
		$sCondi1 = '';
		if ((int)$core16idciclo != 0) {
			$sCondi = ' AND T17.unae17id=' . $core16idciclo . '';
		} else {
			$sCondi = ' AND T17.unae17agno=' . $core16idagno . '';
		}

		if ((int)$core16idbloque != 0) {
			$sCondi1 = 'TB.exte02bloque=' . $core16idbloque . ' AND exte02id>0 AND ';
		}
	
		$sSQL = 'SELECT TB.exte02id 
		FROM exte02per_aca AS TB, unae17cicloacadem AS T17 
		WHERE ' . $sCondi1 . ' TB.exte02idciclo=T17.unae17id ' . $sCondi . '';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds2 = $sIds2 . ',' . $fila['exte02id'];
		}
		$sSQLadd1 = $sSQLadd1 . 'TB.core16peraca IN (' . $sIds2 . ') AND ';
		$sSQLaddSUA = ' AND corf60idperiodo IN (' . $sIds2 . ')';
	}
	//Empezamos limitando el SNIES
	switch ($core16idsnies) {
		case '':
			break;
		case 'NOS': //Sin codigo senies
			$bIncluirSUA = false;
			$sIds9 = '-99';
			$sSQL = 'SELECT core09id 
			FROM core09programa
			WHERE core09idsnies=0';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds9 = $sIds9 . ',' . $fila['core09id'];
			}
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idprograma IN (' . $sIds9 . ') AND ';
			break;
		case 'SNI': //codigos snies
			$bIncluirSUA = false;
			$sIds9 = '-99';
			$sSQL = 'SELECT core09id 
			FROM core09programa
			WHERE core09idsnies<>0';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds9 = $sIds9 . ',' . $fila['core09id'];
			}
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idprograma IN (' . $sIds9 . ') AND ';
			break;
		default:
			$bIncluirSUA = false;
			$sIds9 = '-99';
			$sSQL = 'SELECT core09id 
			FROM core09programa
			WHERE core09idsnies=' . $core16idsnies . '';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds9 = $sIds9 . ',' . $fila['core09id'];
			}
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idprograma IN (' . $sIds9 . ') AND ';
			break;
	}

	$bHayEscuela = false;
	$bHayPrograma = false;
	switch ($idPrograma) {
		case '':
			break;
		case 'DET':
			$bIncluirSUA = false;
			$bConPrograma = true;
			$aPrograma = array();
			break;
		default:
			$bIncluirSUA = false;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idprograma=' . $idPrograma . ' AND ';
			$idExcelPrograma = $idPrograma;
			$bHayPrograma = true;
			break;
	}
	if ($bHayPrograma) {
		if ($idPlanEst != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idplanestudio=' . $idPlanEst . ' AND ';
		}
	} else {
		switch ($idEscuela) {
			case '':
				break;
			case 'DET':
				$bIncluirSUA = false;
				$bConEscuela = true;
				$aEscuela = array();
				$sSQL = 'SELECT core12id, core12sigla, core12nombre FROM core12escuela';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					if ($fila['core12sigla'] != '') {
						$aEscuela[$fila['core12id']] = cadena_notildes($fila['core12sigla']);
					} else {
						$aEscuela[$fila['core12id']] = cadena_notildes($fila['core12nombre']);
					}
				}
				break;
			default:
			$bIncluirSUA = false;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idescuela=' . $idEscuela . ' AND ';
				$idExcelEscuela = $idEscuela;
				$bHayEscuela = true;
				break;
		}
		//Ahora el nivel de formación
		switch ($core16nivelforma) {
			case '':
				break;
			default:
				$bIncluirSUA = false;
				$sIds9 = '-99';
				$sCondi = '';
				if ($bHayEscuela) {
					$sCondi = ' AND core09idescuela=' . $idEscuela . '';
				}
				$sSQL = 'SELECT core09id 
				FROM core09programa
				WHERE cara09nivelformacion=' . $core16nivelforma . $sCondi . '';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					$sIds9 = $sIds9 . ',' . $fila['core09id'];
				}
				$sSQLadd1 = $sSQLadd1 . 'TB.core16idprograma IN (' . $sIds9 . ') AND ';
				break;
		}
	}
	switch ($idZona) {
		case '':
			break;
		case 'DET':
			$bIncluirSUA = false;
			$bConZona = true;
			$aZona = array();
			break;
		default:
			$bIncluirSUA = false;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idzona=' . $idZona . ' AND ';
			$idExcelZona = $idZona;
			break;
	}
	switch ($idCead) {
		case '':
			break;
		case 'DET':
			$bIncluirSUA = false;
			$bConCead = true;
			$aCead = array();
			break;
		default:
			$bIncluirSUA = false;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idcead=' . $idCead . ' AND ';
			$idExcelCentro = $idCead;
			break;
	}
	if ($idMatricula != '') {
		$bIncluirSUA = false;
		$sSQLadd1 = $sSQLadd1 . 'TB.core16nuevo=' . $idMatricula . ' AND ';
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Agno, Ciclo, Bloque, Peraca, Escuela, Nivelforma, Snies, Programa, Planestudio, Zona, Cead';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	// Vamos a calcular el numero de estudiantes únicos
	$sSQL = 'SELECT TB.core16tercero 
	FROM core16actamatricula AS TB 
	WHERE ' . $sSQLadd1 . ' TB.core16tipomatricula IN (' . $sEstados . ') ' . $sSQLadd . ' 
	GROUP BY TB.core16tercero';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Estudiantes individuales ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	$iNumTerceros = $objDB->nf($tabla);
	if ($iNumTerceros > 0) {
		//(&uacute;nicos)
		$sInfo = 'N&deg; de estudiantes : <b>' . formato_numero($iNumTerceros) . '</b>';
		// Vamos a incluir al SUA para los casos donde se pida todo el año...
		if ($bIncluirSUA) {
			$sSQL = 'SELECT corf60idtercero 
			FROM corf60inscripcion
			WHERE corf60estado>0 ' . $sSQLaddSUA . '
			GROUP BY corf60idtercero';			
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Inscritos SUA ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			$iNumTercerosSUA = $objDB->nf($tabla);
			if ($iNumTercerosSUA > 0) {
				$sInfo = $sInfo . ' - N&deg; de estudiantes SUA: <b>' . formato_numero($iNumTercerosSUA) . '</b>';
				// Ahora la union.
				$sSQL = 'SELECT TB.core16tercero 
				FROM core16actamatricula AS TB 
				WHERE ' . $sSQLadd1 . ' TB.core16tipomatricula IN (' . $sEstados . ') ' . $sSQLadd . ' 
				UNION 
				SELECT corf60idtercero AS core16tercero
				FROM corf60inscripcion
				WHERE corf60estado>0 ' . $sSQLaddSUA . '
				GROUP BY core16tercero';
				$tabla = $objDB->ejecutasql($sSQL);
				$iNumTercerosUnicos = $objDB->nf($tabla);
				$sInfo = $sInfo . ' - Total estudiantes: <b>' . formato_numero($iNumTercerosUnicos) . '</b>';
			}
		}
		// --- Ahora Estudiantes - SNIES
		$sSQL = 'SELECT TB.core16tercero, T9.core09idsnies 
		FROM core16actamatricula AS TB, core09programa AS T9 
		WHERE ' . $sSQLadd1 . ' TB.core16tipomatricula IN (' . $sEstados . ') ' . $sSQLadd . ' AND TB.core16idprograma=T9.core09id 
		GROUP BY TB.core16tercero, T9.core09idsnies';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Estudiantes - SNIES ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		$iNumTerceroSNIES = $objDB->nf($tabla);
		if ($iNumTerceroSNIES > 0) {
			$sInfo = $sInfo . '<br>N&deg; de estudiantes - SNIES : <b>' . formato_numero($iNumTerceroSNIES) . '</b>';
		}
		// --- Ahora si mostrar el bloque del salto
		$sLeyenda = html_salto() . '<div class="GrupoCamposAyuda">' . $sInfo . html_salto() . '</div>';
	}
	// Fin de calcular los estudiantes únicos
	$sCamposAdd = '';
	$sOrden = '';
	if ($bConEscuela) {
		$sCamposAdd = ', TB.core16idescuela';
	}
	if ($bConPrograma) {
		$sCamposAdd = $sCamposAdd . ', TB.core16idprograma';
	}
	if ($bConZona) {
		$sCamposAdd = $sCamposAdd . ', TB.core16idzona';
	}
	if ($bConCead) {
		$sCamposAdd = $sCamposAdd . ', TB.core16idcead';
	}
	$sCampos = 'SELECT TB.core16tipomatricula' . $sCamposAdd . ', COUNT(1) AS Total, SUM(cara16numcreditos) AS Cred, SUM(cara16numcreditosext) AS Ext, 
	SUM(cara16numcreditospost) AS Post';
	$sConsulta = 'FROM core16actamatricula AS TB 
	WHERE ' . $sSQLadd1 . ' TB.core16tipomatricula IN (' . $sEstados . ') ' . $sSQLadd . ' 
	GROUP BY TB.core16tipomatricula' . $sCamposAdd . '';
	$sOrden = 'ORDER BY TB.core16tipomatricula' . $sCamposAdd . '';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
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
	$sErrConsulta = '<input id="consulta_2809" name="consulta_2809" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2809" name="titulos_2809" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2809: ' . $sSQL . '<br>';
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
	$sTitulos = '';
	$iCols = 1;
	if ($bConEscuela) {
		$sTitulos = '<th><b>' . $ETI['core16idescuela'] . '</b></th>';
		$iCols++;
	}
	if ($bConPrograma) {
		$sTitulos = $sTitulos . '<th><b>' . $ETI['core16idprograma'] . '</b></th>';
		$iCols++;
	}
	if ($bConZona) {
		$sTitulos = $sTitulos . '<th><b>' . $ETI['core16idzona'] . '</b></th>';
		$iCols++;
	}
	if ($bConCead) {
		$sTitulos = $sTitulos . '<th><b>' . $ETI['core16idcead'] . '</b></th>';
		$iCols++;
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th colspan="' . $iCols . '"></th>';
	$res = $res . '<th class="justify-end" colspan="6">';
	$res = $res . html_paginador('paginaf2809', $registros, $lineastabla, $pagina, 'paginarf2809()');
	$res = $res . html_lpp('lppf2809', $lineastabla, 'paginarf2809()', 200);
	$res = $res . '</th>';
	$res = $res . '</tr>';
	$res = $res . '<tr>';
	$res = $res . '' . $sTitulos . '';
	$res = $res . '<th><b>' . $ETI['core16tipomatricula'] . '</b></th>';
	$res = $res . '<th colspan="2" align="center" title="N&deg; de matriculas"><b>Matriculas</b>.</th>';
	$res = $res . '<th align="right" title="Creditos de pregrado"><b>Preg</b>.</th>';
	$res = $res . '<th align="right" title="Creditos externos (UNAD Florida)"><b>Ext</b>.</th>';
	$res = $res . '<th align="right" title="Creditos de postgrado"><b>Post</b>.</th>';
	$res = $res . '<th align="right"><b>Total</b></th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	//$iEscuela = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		/*
		if ($iEscuela != $filadet['escuela']) {
			$iEscuela = $filadet['escuela'];
			$sNomEscuela = '{' . $filadet['escuela'] . '}';
			//list($sNomEscuela) = f2212_NombreEscuela($idEscuela, $objDB);
			$res = $res . '<tr class="fondoazul">';
			$res = $res . '<td colspan="5" align="center">' . $ETI['msg_escuela'] . ' <b>' . $sNomEscuela . '</b></td>';
			$res = $res . '</tr>';
		}
		*/
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		if (false) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		if ($bAbierta) {
		}
		$sAdicionales = '';

		if ($bConEscuela) {
			$sAdicionales = '<td>' . $sPrefijo . $aEscuela[$filadet['core16idescuela']] . $sSufijo . '</td>';
			$idExcelEscuela = $filadet['core16idescuela'];
		}
		if ($bConPrograma) {
			if (isset($aPrograma[$filadet['core16idprograma']]) == 0) {
				$sSQL = 'SELECT core09nombre, core09codigo FROM core09programa WHERE core09id=' . $filadet['core16idprograma'] . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$filae = $objDB->sf($tablae);
					$aPrograma[$filadet['core16idprograma']] = cadena_notildes($filae['core09nombre']) . ' [' . $filae['core09codigo'] . ']';
				} else {
					$aPrograma[$filadet['core16idprograma']] = '{' . $filadet['core16idprograma'] . '}';
				}
			}
			$sAdicionales = $sAdicionales . '<td>' . $sPrefijo . $aPrograma[$filadet['core16idprograma']] . $sSufijo . '</td>';
			$idExcelPrograma = $filadet['core16idprograma'];
		}
		if ($bConZona) {
			if (isset($aZona[$filadet['core16idzona']]) == 0) {
				$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $filadet['core16idzona'] . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$filae = $objDB->sf($tablae);
					$aZona[$filadet['core16idzona']] = cadena_notildes($filae['unad23nombre']) . '';
				} else {
					$aZona[$filadet['core16idzona']] = '{' . $filadet['core16idzona'] . '}';
				}
			}
			$sAdicionales = $sAdicionales . '<td>' . $sPrefijo . $aZona[$filadet['core16idzona']] . $sSufijo . '</td>';
			$idExcelZona = $filadet['core16idzona'];
		}
		if ($bConCead) {
			if (isset($aCead[$filadet['core16idcead']]) == 0) {
				$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $filadet['core16idcead'] . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$filae = $objDB->sf($tablae);
					$aCead[$filadet['core16idcead']] = cadena_notildes($filae['unad24nombre']) . '';
				} else {
					$aCead[$filadet['core16idcead']] = '{' . $filadet['core16idcead'] . '}';
				}
			}
			$sAdicionales = $sAdicionales . '<td>' . $sPrefijo . $aCead[$filadet['core16idcead']] . $sSufijo . '</td>';
			$idExcelCentro = $filadet['core16idcead'];
		}
		$sBoton = '';
		if ($seg_6 == 1) {
			$sBoton = '<input id="btExcel" name="btExcel" type="button" value="Listado" class="btMiniExcel" onclick="imprimematricula(' . $idExcelEscuela . ', ' . $idExcelPrograma . ', ' . $idExcelZona . ', ' . $idExcelCentro . ');" title="Listado de estudiantes"/>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '' . $sAdicionales . '';
		$res = $res . '<td>' . $sPrefijo . $aTipoMat[$filadet['core16tipomatricula']] . $sSufijo . '</td>';
		$res = $res . '<td align="right">' . $sPrefijo . formato_numero($filadet['Total'], 0) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sBoton . '</td>';
		$res = $res . '<td align="right">' . $sPrefijo . formato_numero($filadet['Cred'], 0) . $sSufijo . '</td>';
		$res = $res . '<td align="right">' . $sPrefijo . formato_numero($filadet['Ext'], 0) . $sSufijo . '</td>';
		$res = $res . '<td align="right">' . $sPrefijo . formato_numero($filadet['Post'], 0) . $sSufijo . '</td>';
		$res = $res . '<td align="right">' . $sPrefijo . formato_numero(($filadet['Cred'] + $filadet['Ext'] + $filadet['Post']), 0) . $sSufijo . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2809_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2809_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2809detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f2809_TraerInfoPeriodo($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
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
	$idPeirodo = $aParametros[1];
	list($sInfo, $sDebugI) = f2809_InfoPeriodo($idPeirodo, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugI;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_InfoPeriodo', 'innerHTML', $sInfo);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2809_InfoPeriodo($idPeriodo, $objDB, $bDebug)
{
	$sInfo = '';
	$sDebug = '';
	require './app.php';
	$mensajes_2809 = 'lg/lg_2809_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2809)) {
		$mensajes_2809 = 'lg/lg_2809_es.php';
	}
	require $mensajes_2809;
	if ((int)$idPeriodo != 0) {
		// Julio 29 de 2024 - Para efectos de los periodos del tipo opciones de grado, solo se traeran aquellos que tengan el cursos
		// 111111 que es continuidad académica.
		$sCondiCurso = '';
		$sSQL = 'SELECT exte02tipoperiodo FROM exte02per_aca WHERE exte02id=' . $idPeriodo . '';
		$tabla02 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla02) > 0) {
			$fila02 = $objDB->sf($tabla02);
			if ($fila02['exte02tipoperiodo'] == 9) {
				$sCondiCurso = ' AND T1.cur_materia LIKE "%111111%"';
			}
		}
		$iTotalRCA = 0;
		$iTotalSII = 0;
		$bConDBRCA = false;
		list($objDBRyC, $sDebugR) = TraerDBRyCV2($bDebug);
		$sDebug = $sDebug . $sDebugR;
		if ($objDBRyC == NULL) {
		} else {
			$bConDBRCA = true;
			$sDocs = '-99';
			// AND T1.cur_edificio<>99  AND TR.estado="A"
			// Julio 29 de 2024 - Se retira la opcion de cursos activos puesto que ahora se evaluan todas las atas
			//  AND TR.estado IN ("A") 
			$sSQL = 'SELECT TR.ins_estudiante 
			FROM registro AS TR, cursos_periodos AS T1 
			WHERE TR.ano=' . $idPeriodo . ' 
			AND TR.ins_curso=T1.consecutivo ' . $sCondiCurso . ' 
			GROUP BY TR.ins_estudiante';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Estudiantes en matricula RyC: ' . $sSQL . '<br>';
			}
			$tabla = $objDBRyC->ejecutasql($sSQL);
			$iTotalRCA = $objDBRyC->nf($tabla);
			if ($bDebug) {
				/*
				while($fila=$objDBRyC->sf($tabla)){
					$sDocs=$sDocs.', "'.$fila['ins_estudiante'].'"';
					}
				*/
			}
		}
		if ($iTotalRCA > 0) {
			$iTotalSII = 0;
			$iTotalAplaza = 0;
			$iTotalCancela = 0;
			$sSQL = 'SELECT core16estado, SUM(1) AS Total 
			FROM core16actamatricula 
			WHERE core16peraca=' . $idPeriodo . ' AND core16estado IN (8,9) AND core16tipomatricula IN (0, 1)
			GROUP BY core16estado';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Informacion de aplazamientos y cancelaciones en SII 4.0: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				switch ($fila['core16estado']) {
					case 8: //Aplazamientos
						$iTotalAplaza = $fila['Total'];
						break;
					case 9: //Cancelaciones
						$iTotalCancela = $fila['Total'];
						break;
					default:
						//$iTotalSII=$iTotalSII+$fila['Total'];
						break;
				}
			}
			$sSQL = 'SELECT core16tercero 
			FROM core16actamatricula 
			WHERE core16peraca=' . $idPeriodo . ' AND core16estado NOT IN (8,9) AND core16tipomatricula IN (0, 1)
			GROUP BY core16tercero';
			$tabla = $objDB->ejecutasql($sSQL);
			$iTotalSII = $objDB->nf($tabla);
			$sInfo = 'Matriculas RCA: <b>' . formato_numero($iTotalRCA) . '</b>, Matriculas SII 4.0: <b>' . formato_numero($iTotalSII) . '</b>';
			$iDiferencia = $iTotalRCA - $iTotalSII;
			if ($iDiferencia <> 0) {
				$sInfo = $sInfo . ', [Diferencia <b>' . formato_numero($iDiferencia) . '</b>]';
			}
			if ($iTotalAplaza > 0) {
				$sInfo = $sInfo . ', Aplazamientos: <b>' . formato_numero($iTotalAplaza) . '</b>';
			}
			if ($iTotalCancela > 0) {
				$sInfo = $sInfo . ', Cancelaciones: <b>' . formato_numero($iTotalCancela) . '</b>';
			}
			if ($idPeriodo > 760) {
				if ($iTotalRCA > ($iTotalSII)) {
					$sInfo = $sInfo . ' <a href="javascript:procesarmatricula()" class="lnkresalte">' . $ETI['lnk_procesamatricula'] . '</a>';
				} else {
				}
				if ($bDebug) {
					$sInfo = $sInfo . ' <a href="javascript:procesarmatricula(1)" class="lnkresalte">Forzar Matricula</a>';
				}
				if ($bDebug) {
					$sReportar = '';
					$sSQL = 'SELECT T11.unad11doc  
					FROM core16actamatricula AS TB, unad11terceros AS T11 
					WHERE TB.core16peraca=' . $idPeriodo . ' AND TB.core16estado NOT IN (8,9) AND TB.core16tipomatricula IN (0, 1) 
					AND TB.core16tercero=T11.unad11id AND T11.unad11tipodoc="CC"';
					$sDebug = $sDebug . fecha_microtiempo() . ' Terceros faltantes: ' . $sSQL . '<br>';
					$tabla = $objDB->ejecutasql($sSQL);
					while ($fila = $objDB->sf($tabla)) {
						$sDocs = $sDocs . ', ' . $fila['unad11doc'] . '';
					}
					$sSQL = 'SELECT TR.ins_estudiante 
					FROM registro AS TR, cursos_periodos AS T1 
					WHERE TR.ano=' . $idPeriodo . ' AND TR.estado IN ("A") AND TR.ins_estudiante NOT IN (' . $sDocs . ')
					AND TR.ins_curso=T1.consecutivo
					GROUP BY TR.ins_estudiante';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Estudiantes faltantes: ' . $sSQL . '<br>';
					}
					$tabla = $objDBRyC->ejecutasql($sSQL);
					while ($fila = $objDBRyC->sf($tabla)) {
						$sReportar = $sReportar . '' . $fila['ins_estudiante'] . ', ';
					}
					$sDebug = $sDebug . fecha_microtiempo() . ' Terceros faltantes: ' . $sReportar . '<br>';
					$sInfo = $sInfo . '<br>Faltantes: ' . $idPeriodo . ': ' . $sReportar . '';
				}
			} else {
			}
		}
		if ($bConDBRCA) {
			$objDBRyC->CerrarConexion();
		}
	}
	return array($sInfo, $sDebug);
}

