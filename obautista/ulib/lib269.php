<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Juan David Avellaneda Molina - UNAD - 2025 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.26.3b viernes, 24 de septiembre de 2021
--- Modelo Versión 3.0.15b martes, 17 de junio de 2025
--- 269 aure69versionado
*/

/** Archivo lib269.php.
 * Libreria 269 aure69versionado.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date viernes, 24 de septiembre de 2021
 */
function f269_HTMLComboV2_aure69idmodulo($objDB, $objCombos, $valor, $vraure69idsistema)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('aure69idmodulo', $valor, true, '{' . $ETI['msg_ninguno'] . '}', 0);
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vraure69idsistema != 0) {
		$sSQL = 'SELECT unad02id AS id, CONCAT(unad02nombre, " [", unad02id, "]") AS nombre FROM unad02modulos WHERE unad02idsistema=' . $vraure69idsistema . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f269_HTMLComboV2_aure69idmoduloUsuario($objDB, $objCombos, $valor, $vraure69idsistema, $idTercero)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('aure69idmodulo', $valor, true, '{' . $ETI['msg_todos'] . '}', '');
	$objCombos->sAccion = 'paginarf269()';
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vraure69idsistema != 0) {
		if ((int)$idTercero != 0) {
			list($sModulos, $sDebugM) = f269_ModulosTerceroV2($vraure69idsistema, $idTercero, $objDB);
			$sSQL = 'SELECT unad02id AS id, CONCAT(unad02nombre, " [", unad02id, "]") AS nombre FROM unad02modulos 
			WHERE unad02idsistema=' . $vraure69idsistema . ' AND unad02id IN (' . $sModulos . ')
			ORDER BY unad02nombre';
		}
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f269_Comboaure69idmodulo($aParametros)
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
	$idSistema = (int)$aParametros[0];
	$iMayor = 0;
	$iMenor = 0;
	$iCorreccion = 0;
	$html_aure69idmodulo = f269_HTMLComboV2_aure69idmodulo($objDB, $objCombos, '', $aParametros[0]);
	if ($idSistema != 0) {
		$sSQL = 'SELECT unad01mayor, unad01menor, unad01correccion FROM unad01sistema WHERE unad01id=' . $idSistema . ';';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$iMayor = $fila['unad01mayor'];
			$iMenor = $fila['unad01menor'];
			$iCorreccion = $fila['unad01correccion'];
		}
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_aure69idmodulo', 'innerHTML', $html_aure69idmodulo);
	$objResponse->call('$("#aure69idmodulo").chosen()');
	$objResponse->assign('aure69mayor', 'value', $iMayor);
	$objResponse->assign('aure69menor', 'value', $iMenor);
	$objResponse->assign('aure69correccion', 'value', $iCorreccion);
	return $objResponse;
}
function f269_Comboaure69idmoduloUsuario($aParametros)
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
	$idSistema = (int)$aParametros[0];
	$idTercero = (int)$aParametros[1];
	$html_aure69idmodulo = f269_HTMLComboV2_aure69idmoduloUsuario($objDB, $objCombos, '', $aParametros[0], $idTercero);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_aure69idmodulo', 'innerHTML', $html_aure69idmodulo);
	$objResponse->call('$("#aure69idmodulo").chosen()');
	return $objResponse;
}
function f269_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$aure69consec = numeros_validar($datos[1]);
	if ($aure69consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM aure69versionado WHERE aure69consec=' . $aure69consec . '';
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
function f269_Busquedas($aParametros)
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
	$mensajes_269 = $APP->rutacomun . 'lg/lg_269_' . $sIdioma . '.php';
	if (!file_exists($mensajes_269)) {
		$mensajes_269 = $APP->rutacomun . 'lg/lg_269_es.php';
	}
	require $mensajes_todas;
	require $mensajes_269;
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
	$sTituloModulo = $ETI['titulo_269'];
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
function f269_HtmlBusqueda($aParametros)
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
function f269_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_200 = 'lg/lg_200_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_200)) {
		$mensajes_200 = 'lg/lg_200_es.php';
	}
	require $mensajes_200;
	*/
	$mensajes_269 = $APP->rutacomun . 'lg/lg_269_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_269)) {
		$mensajes_269 = $APP->rutacomun . 'lg/lg_269_es.php';
	}
	require $mensajes_269;
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
	$iNumVariables = 104;
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
	$bNombre = trim($aParametros[103]);
	$bGrupo = numeros_validar($aParametros[104]);
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
	$sBotones = '<input id="paginaf269" name="paginaf269" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf269" name="lppf269" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	/*
	$aEstado = array();
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['id']] = cadena_notildes($fila['nombre']);
	}
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($bGrupo != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.aure69idgrupo=' . $aParametros[104] . ' AND ';
	}
	if ($bNombre != '') {
		$sBase = mb_strtoupper($bNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND TB.aure69detalle LIKE "%' . $sCadena . '%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
			}
		}
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Consec, Id, Sistema, Fecha, Verupd, Mayor, Menor, Correccion, Grupo, Detalle, Publico';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.aure69consec, TB.aure69id, T3.unad01nombre, TB.aure69fecha, TB.aure69verupd, TB.aure69mayor, TB.aure69menor, 
	TB.aure69correccion, T9.aure68nombre, TB.aure69detalle, TB.aure69publico, TB.aure69idsistema, TB.aure69idgrupo';
	$sConsulta = 'FROM aure69versionado AS TB, unad01sistema AS T3, aure68grupoversion AS T9 
	WHERE ' . $sSQLadd1 . ' TB.aure69id>0 AND TB.aure69idsistema=T3.unad01id AND TB.aure69idgrupo=T9.aure68id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.aure69fecha DESC';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Totalizando consulta 269: ' . $sSQLContador . '<br>';
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
	$sErrConsulta = '<input id="consulta_269" name="consulta_269" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_269" name="titulos_269" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 269: ' . $sSQL . '<br>';
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
		$sDebug = $sDebug . fecha_microtiempo() . ' Termina la consulta 269<br>';
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['aure69consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['aure69idsistema'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['aure69fecha'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['aure69verupd'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['msg_version'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['aure69idgrupo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['aure69publico'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf269', $registros, $lineastabla, $pagina, 'paginarf269()');
	$res = $res . html_lpp('lppf269', $lineastabla, 'paginarf269()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
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
		$et_aure69consec = $sPrefijo . $filadet['aure69consec'] . $sSufijo;
		$et_aure69idsistema = $sPrefijo . cadena_notildes($filadet['unad01nombre']) . $sSufijo;
		$et_aure69fecha = '';
		if ($filadet['aure69fecha'] != '00/00/0000') {
			$et_aure69fecha = $filadet['aure69fecha'];
		}
		$et_aure69publico = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['aure69publico'] == 'S') {
			$et_aure69publico = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf269(' . $filadet['aure69id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$sVerSistema = '';
		$sVerSistema = $filadet['aure69mayor'] . '.' . $filadet['aure69menor'] . '.' . $filadet['aure69correccion'];
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_aure69consec . '</td>';
		$res = $res . '<td>' . $et_aure69idsistema . '</td>';
		$res = $res . '<td>' . $sPrefijo . $et_aure69fecha . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['aure69verupd'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $sVerSistema . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['aure68nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $et_aure69publico . $sSufijo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
		if ($filadet['aure69detalle'] != '') {
			$res = $res . '<tr' . $sClass . '>';
			$res = $res . '<td></td>';
			$res = $res . '<td colspan="9">' . $sPrefijo . cadena_notildes($filadet['aure69detalle']) . $sSufijo . '</td>';
			$res = $res . '</tr>';
		}
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f269_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f269_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f269detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f269_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'aure69consec=' . $DATA['aure69consec'] . '';
	} else {
		$sSQLcondi = 'aure69id=' . $DATA['aure69id'] . '';
	}
	$sSQL = 'SELECT * FROM aure69versionado WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['aure69consec'] = $fila['aure69consec'];
		$DATA['aure69id'] = $fila['aure69id'];
		$DATA['aure69idsistema'] = $fila['aure69idsistema'];
		$DATA['aure69fecha'] = $fila['aure69fecha'];
		$DATA['aure69verupd'] = $fila['aure69verupd'];
		$DATA['aure69mayor'] = $fila['aure69mayor'];
		$DATA['aure69menor'] = $fila['aure69menor'];
		$DATA['aure69correccion'] = $fila['aure69correccion'];
		$DATA['aure69idgrupo'] = $fila['aure69idgrupo'];
		$DATA['aure69detalle'] = $fila['aure69detalle'];
		$DATA['aure69publico'] = $fila['aure69publico'];
		$DATA['aure69idmodulo'] = $fila['aure69idmodulo'];
		$DATA['aure69fechavigencia'] = $fila['aure69fechavigencia'];
		$DATA['aure69enlace'] = $fila['aure69enlace'];
		$DATA['aure69campus'] = $fila['aure69campus'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta269'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f269_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 269)
{
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_269 = $APP->rutacomun . 'lg/lg_269_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_269)) {
		$mensajes_269 = $APP->rutacomun . 'lg/lg_269_es.php';
	}
	require $mensajes_todas;
	require $mensajes_269;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['aure69consec'])==0){$DATA['aure69consec']='';}
	if (isset($DATA['aure69id'])==0){$DATA['aure69id']='';}
	if (isset($DATA['aure69idsistema'])==0){$DATA['aure69idsistema']='';}
	if (isset($DATA['aure69fecha'])==0){$DATA['aure69fecha']='';}
	if (isset($DATA['aure69idsistema']) == 0) {
		$DATA['aure69idsistema'] = 0;
	}
	if (isset($DATA['aure69fecha']) == 0) {
		$DATA['aure69fecha'] = '00/00/0000';
	}
	if (isset($DATA['aure69verupd']) == 0) {
		$DATA['aure69verupd'] = 0;
	}
	if (isset($DATA['aure69mayor']) == 0) {
		$DATA['aure69mayor'] = 0;
	}
	if (isset($DATA['aure69menor']) == 0) {
		$DATA['aure69menor'] = 0;
	}
	if (isset($DATA['aure69correccion']) == 0) {
		$DATA['aure69correccion'] = 0;
	}
	if (isset($DATA['aure69idgrupo']) == 0) {
		$DATA['aure69idgrupo'] = 0;
	}
	if (isset($DATA['aure69detalle']) == 0) {
		$DATA['aure69detalle'] = '';
	}
	if (isset($DATA['aure69publico']) == 0) {
		$DATA['aure69publico'] = '';
	}
	if (isset($DATA['aure69idmodulo']) == 0) {
		$DATA['aure69idmodulo'] = 0;
	}
	if (isset($DATA['aure69fechavigencia']) == 0) {
		$DATA['aure69fechavigencia'] = 0;
	}
	if (isset($DATA['aure69enlace']) == 0) {
		$DATA['aure69enlace'] = '';
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['aure69consec'] = numeros_validar($DATA['aure69consec']);
	$DATA['aure69idsistema'] = numeros_validar($DATA['aure69idsistema']);
	$DATA['aure69verupd'] = numeros_validar($DATA['aure69verupd']);
	$DATA['aure69mayor'] = numeros_validar($DATA['aure69mayor']);
	$DATA['aure69menor'] = numeros_validar($DATA['aure69menor']);
	$DATA['aure69correccion'] = numeros_validar($DATA['aure69correccion']);
	$DATA['aure69idgrupo'] = numeros_validar($DATA['aure69idgrupo']);
	$DATA['aure69detalle'] = cadena_Validar(trim($DATA['aure69detalle']));
	$DATA['aure69publico'] = cadena_Validar(trim($DATA['aure69publico']));
	$DATA['aure69idmodulo'] = numeros_validar($DATA['aure69idmodulo']);
	$DATA['aure69enlace'] = cadena_Validar(trim($DATA['aure69enlace']), true);
	$DATA['aure69campus'] = numeros_validar($DATA['aure69campus']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['aure69idsistema'] == '') {
		$DATA['aure69idsistema'] = 0;
	}
	if ($DATA['aure69fecha'] == '') {
		$DATA['aure69fecha'] = 0;
	}
	if ($DATA['aure69verupd'] == '') {
		$DATA['aure69verupd'] = 0;
	}
	if ($DATA['aure69mayor'] == '') {
		$DATA['aure69mayor'] = 0;
	}
	if ($DATA['aure69menor'] == '') {
		$DATA['aure69menor'] = 0;
	}
	if ($DATA['aure69correccion'] == '') {
		$DATA['aure69correccion'] = 0;
	}
	if ($DATA['aure69idgrupo'] == '') {
		$DATA['aure69idgrupo'] = 0;
	}
	if ($DATA['aure69idmodulo'] == '') {
		$DATA['aure69idmodulo'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		if ($DATA['aure69idmodulo'] == '') {
			$sError = $ERR['aure69idmodulo'] . $sSepara . $sError;
		}
		if ($DATA['aure69publico'] == '') {
			$sError = $ERR['aure69publico'] . $sSepara . $sError;
		}
		/*
		if ($DATA['aure69detalle'] == '') {
			$sError = $ERR['aure69detalle'] . $sSepara . $sError;
		}
		*/
		if ($DATA['aure69idgrupo'] == '') {
			$sError = $ERR['aure69idgrupo'] . $sSepara . $sError;
		}
		if ($DATA['aure69correccion'] == '') {
			$sError = $ERR['aure69correccion'] . $sSepara . $sError;
		}
		if ($DATA['aure69menor'] == '') {
			$sError = $ERR['aure69menor'] . $sSepara . $sError;
		}
		if ($DATA['aure69mayor'] == '') {
			$sError = $ERR['aure69mayor'] . $sSepara . $sError;
		}
		if ($DATA['aure69verupd'] == '') {
			$sError = $ERR['aure69verupd'] . $sSepara . $sError;
		}
		if (!fecha_esvalida($DATA['aure69fecha'])) {
			//$DATA['aure69fecha'] = '00/00/0000';
			$sError = $ERR['aure69fecha'] . ' [' . $DATA['aure69fecha'] . ']' . $sSepara . $sError;
		}
		if ($DATA['aure69idsistema'] == '') {
			$sError = $ERR['aure69idsistema'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'aure69enlace');
		$aLargoCampos = array(0, 200);
		for ($k = 1; $k <= 1; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['aure69consec'] == '') {
				$DATA['aure69consec'] = tabla_consecutivo('aure69versionado', 'aure69consec', '', $objDB);
				if ($DATA['aure69consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'aure69consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['aure69consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM aure69versionado WHERE aure69consec=' . $DATA['aure69consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve) {
						$sError = $ERR['2'] . ' [Mod ' . $iCodModulo . ']';
					}
				}
			}
		} else {
			list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['aure69id'] = tabla_consecutivo('aure69versionado', 'aure69id', '', $objDB);
			if ($DATA['aure69id'] == -1) {
				$sError = $objDB->serror;
			}
		}
	}
	if ($sError == '') {
		//$aure69detalle = addslashes($DATA['aure69detalle']);
		$aure69detalle = str_replace('"', '\"', $DATA['aure69detalle']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos269 = 'aure69consec, aure69id, aure69idsistema, aure69fecha, aure69verupd, 
			aure69mayor, aure69menor, aure69correccion, aure69idgrupo, aure69detalle, 
			aure69publico, aure69idmodulo, aure69fechavigencia, aure69enlace, aure69campus';
			$sValores269 = '' . $DATA['aure69consec'] . ', ' . $DATA['aure69id'] . ', ' . $DATA['aure69idsistema'] . ', "' . $DATA['aure69fecha'] . '", ' . $DATA['aure69verupd'] . ', 
			' . $DATA['aure69mayor'] . ', ' . $DATA['aure69menor'] . ', ' . $DATA['aure69correccion'] . ', ' . $DATA['aure69idgrupo'] . ', "' . $aure69detalle . '", 
			"' . $DATA['aure69publico'] . '", ' . $DATA['aure69idmodulo'] . ', "' . $DATA['aure69fechavigencia'] . '", "' . $DATA['aure69enlace'] . '", ' . $DATA['aure69campus'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO aure69versionado (' . $sCampos269 . ') VALUES (' . cadena_codificar($sValores269) . ');';
				$sdetalle = $sCampos269 . '[' . cadena_codificar($sValores269) . ']';
			} else {
				$sSQL = 'INSERT INTO aure69versionado (' . $sCampos269 . ') VALUES (' . $sValores269 . ');';
				$sdetalle = $sCampos269 . '[' . $sValores269 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'aure69idsistema';
			$scampo[2] = 'aure69fecha';
			$scampo[3] = 'aure69verupd';
			$scampo[4] = 'aure69mayor';
			$scampo[5] = 'aure69menor';
			$scampo[6] = 'aure69correccion';
			$scampo[7] = 'aure69idgrupo';
			$scampo[8] = 'aure69detalle';
			$scampo[9] = 'aure69publico';
			$scampo[10] = 'aure69idmodulo';
			$scampo[11] = 'aure69fechavigencia';
			$scampo[12] = 'aure69enlace';
			$scampo[13] = 'aure69campus';
			$sdato[1] = $DATA['aure69idsistema'];
			$sdato[2] = $DATA['aure69fecha'];
			$sdato[3] = $DATA['aure69verupd'];
			$sdato[4] = $DATA['aure69mayor'];
			$sdato[5] = $DATA['aure69menor'];
			$sdato[6] = $DATA['aure69correccion'];
			$sdato[7] = $DATA['aure69idgrupo'];
			$sdato[8] = $aure69detalle;
			$sdato[9] = $DATA['aure69publico'];
			$sdato[10] = $DATA['aure69idmodulo'];
			$sdato[11] = $DATA['aure69fechavigencia'];
			$sdato[12] = $DATA['aure69enlace'];
			$sdato[13] = $DATA['aure69campus'];
			$iNumCamposMod = 13;
			$sWhere = 'aure69id=' . $DATA['aure69id'] . '';
			$sSQL = 'SELECT * FROM aure69versionado WHERE ' . $sWhere;
			$sdatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $iNumCamposMod; $k++) {
						if (isset($filabase[$scampo[$k]]) == 0) {
							$sDebug = $sDebug . fecha_microtiempo() . ' FALLA CODIGO: Falta el campo ' . $k . ' ' . $scampo[$k] . '<br>';
						}
					}
					$bPrimera = false;
				}
				$bsepara = false;
				for ($k = 1; $k <= $iNumCamposMod; $k++) {
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
					$sdetalle = cadena_codificar($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE aure69versionado SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE aure69versionado SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 269 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [269] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['aure69id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['aure69id'], $sdetalle, $objDB);
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
		$sDebug = $sDebug . fecha_microtiempo() . ' InfoDepura<br>';
	}
	*/
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f269_db_Eliminar($aure69id, $objDB, $bDebug = false)
{
	$iCodModulo = 269;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_269 = $APP->rutacomun . 'lg/lg_269_' . $sIdioma . '.php';
	if (!file_exists($mensajes_269)) {
		$mensajes_269 = $APP->rutacomun . 'lg/lg_269_es.php';
	}
	require $mensajes_todas;
	require $mensajes_269;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$aure69id = numeros_validar($aure69id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM aure69versionado WHERE aure69id=' . $aure69id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $aure69id . '}';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=269';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['aure69id'] . ' LIMIT 0, 1';
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
		$sWhere = 'aure69id=' . $aure69id . '';
		//$sWhere = 'aure69consec=' . $filabase['aure69consec'] . '';
		$sSQL = 'DELETE FROM aure69versionado WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $aure69id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f269_TablaDetalleV2Usuario($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_269 = $APP->rutacomun . 'lg/lg_269_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_269)) {
		$mensajes_269 = $APP->rutacomun . 'lg/lg_269_es.php';
	}
	require $mensajes_todas;
	require $mensajes_269;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[98]) == 0) {
		$aParametros[98] = '';
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
	//$aParametros[104]=numeros_validar($aParametros[104]);
	$idSistema = (int)$aParametros[98];
	$idTercero = $aParametros[100];
	$sDebug = '';
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bModulo = $aParametros[103];
	$bGrupo = $aParametros[104];
	$bNombre = '';
	$bAbierta = true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf269" name="paginaf269" type="hidden" value="' . $pagina . '"/>
	<input id="lppf269" name="lppf269" type="hidden" value="' . $lineastabla . '"/>';
	if ($idSistema === '') {
		$sLeyenda = 'No se ha definido la aplicaci&oacute;n a consultar.';
	}
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
	if ($bGrupo != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.aure69idgrupo=' . $aParametros[104] . ' AND ';
	}
	if ($bNombre != '') {
		$sBase = trim(strtoupper($bNombre));
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND TB.aure69detalle LIKE "%' . $sCadena . '%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
			}
		}
	}
	if ($bModulo != '') {
		$sSQLadd1 = $sSQLadd1 . ' TB.aure69idmodulo=' . $bModulo . ' AND ';
	} else {
		if ($idSistema == 0) {
			$sSQLadd1 = $sSQLadd1 . ' TB.aure69campus=1 AND ';
		} else {
			list($sModulos, $sDebugM) = f269_ModulosTerceroV2($idSistema, $idTercero, $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugM;
			$sSQLadd1 = $sSQLadd1 . ' ((TB.aure69idsistema=-12) OR ((TB.aure69idsistema=' . $idSistema . ') AND (TB.aure69idmodulo IN (' . $sModulos . ')))) AND ';
		}
	}
	$sTitulos = 'Consec, Id, Sistema, Fecha, Verupd, Mayor, Menor, Correccion, Grupo, Detalle, Publico';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	if ($bGigante) {
		$sSQL = 'SELECT COUNT(1) AS Total 
		FROM aure69versionado AS TB, unad01sistema AS T3, aure68grupoversion AS T9 
		WHERE ' . $sSQLadd1 . ' TB.aure69idsistema=T3.unad01id AND TB.aure69idgrupo=T9.aure68id ' . $sSQLadd . '';
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
		}
	}
	$sSQL = 'SELECT TB.aure69consec, TB.aure69id, TB.aure69fecha, TB.aure69verupd, TB.aure69mayor, TB.aure69menor, 
	TB.aure69correccion, T9.aure68nombre, TB.aure69detalle, TB.aure69enlace, TB.aure69idsistema, TB.aure69idgrupo, 
	T9.aure68orden 
	FROM aure69versionado AS TB, aure68grupoversion AS T9 
	WHERE ' . $sSQLadd1 . 'TB.aure69publico="S" AND TB.aure69idgrupo=T9.aure68id ' . $sSQLadd . '
	ORDER BY T9.aure68orden, STR_TO_DATE(TB.aure69fecha, "%d/%m/%Y") DESC';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_269" name="consulta_269" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_269" name="titulos_269" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 269: ' . $sSQL . $sLimite . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			if ($registros == 0) {
				$sLeyenda = 'No se han encontrado elementos de ayuda disponibles';
				if ($bModulo != '') {
					$sLeyenda = 'No se han encontrado elementos de ayuda disponibles para el modulo solicitado [' . $bModulo . '].';
				}
				$sLeyenda = '<div class="salto1px"></div>
				<div class="GrupoCamposAyuda">
				' . $sLeyenda . '
				<div class="salto1px"></div>
				</div>';
				return array($sErrConsulta . $sLeyenda . $sBotones, $sDebug);
			}
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
				$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
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
	$res = $res . '<th colspan="2" style="text-align: center;"><b>' . $ETI['msg_novedades'] . '</b></th>';
	$res = $res . '<th class="text-right w-20">';
	$res = $res . html_paginador('paginaf269', $registros, $lineastabla, $pagina, 'paginarf269()');
	$res = $res . html_lpp('lppf269', $lineastabla, 'paginarf269()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	$idGrupo = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idGrupo != $filadet['aure69idgrupo']) {
			$idGrupo = $filadet['aure69idgrupo'];
			$res = $res . '<tr class="fondoazul">';
			$res = $res . '<td colspan="3" align="center">' . $ETI['aure69idgrupo'] . ': <b>' . cadena_notildes($filadet['aure68nombre']) . '</b></td>';
			$res = $res . '</tr>';
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['aure68orden']) {
			case 1:
				$sPrefijo = '<span class="azul">';
				$sSufijo = '</span>';
				break;
			case 6:
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
				break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_aure69fecha = $filadet['aure69fecha'];
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td class="w-15">' . $sPrefijo . $et_aure69fecha . $sSufijo . '</td>';
		$res = $res . '<td colspan="2">' . $sPrefijo . cadena_notildes($filadet['aure69detalle']) . $sSufijo . '</td>';
		$res = $res . '</tr>';
		if ($filadet['aure69enlace'] != '') {
			$res = $res . '<tr' . $sClass . '>';
			$res = $res . '<td></td>';
			$res = $res . '<td colspan="2">' . $ETI['msg_masinfo'] . ' <a href="' . $filadet['aure69enlace'] . '" class="lnkresalte" title="' . $filadet['aure69enlace'] . '" target="_blank">' . $ETI['msg_click'] . '</a></td>';
			$res = $res . '</tr>';
		}
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f269_HtmlTablaUsuario($aParametros)
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
	list($sDetalle, $sDebugTabla) = f269_TablaDetalleV2Usuario($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f269detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
//las tablas de sistema se muestran al inicio en el panel de cada sistema.
function f269_TablaDetalleV2Sistema($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_269 = $APP->rutacomun . 'lg/lg_269_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_269)) {
		$mensajes_269 = $APP->rutacomun . 'lg/lg_269_es.php';
	}
	require $mensajes_todas;
	require $mensajes_269;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[97]) == 0) {
		$aParametros[97] = '';
	}
	if (isset($aParametros[98]) == 0) {
		$aParametros[98] = '';
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
	$bMostrarVacios = true;
	//$aParametros[103]=numeros_validar($aParametros[103]);
	//$aParametros[104]=numeros_validar($aParametros[104]);
	$idSistema = (int)$aParametros[98];
	if ($aParametros[97] == 1) {
		$bMostrarVacios = false;
	}
	$idTercero = $aParametros[100];
	$sDebug = '';
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bModulo = $aParametros[103];
	$bGrupo = $aParametros[104];
	$bNombre = '';
	$bAbierta = true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf269" name="paginaf269" type="hidden" value="' . $pagina . '"/>
	<input id="lppf269" name="lppf269" type="hidden" value="' . $lineastabla . '"/>';
	if ($idSistema == 0) {
		$sLeyenda = 'No se ha definido la aplicaci&oacute;n a consultar.';
	}
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
	if ($bGrupo != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.aure69idgrupo=' . $aParametros[104] . ' AND ';
	}
	if ($bNombre != '') {
		$sBase = trim(strtoupper($bNombre));
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND TB.aure69detalle LIKE "%' . $sCadena . '%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
			}
		}
	}
	if ($bModulo == '') {
		//Esto es necesario cuando se cargan los modulos, no aqui.
		//list($sModulos, $sDebugM)=f269_ModulosTerceroV2($idSistema, $idTercero, $objDB, $bDebug);
		//$sDebug = $sDebug . $sDebugM;
		//$sSQLadd1=$sSQLadd1.' ((TB.aure69idsistema=-12) OR (TB.aure69idsistema='.$idSistema.')) AND ';
	} else {
		$sSQLadd1 = $sSQLadd1 . ' TB.aure69idmodulo=' . $bModulo . ' AND ';
	}
	$sTitulos = 'Consec, Id, Sistema, Fecha, Verupd, Mayor, Menor, Correccion, Grupo, Detalle, Publico';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	if ($bGigante) {
		$sSQL = 'SELECT COUNT(1) AS Total 
		FROM aure69versionado AS TB, unad01sistema AS T3, aure68grupoversion AS T9 
		WHERE ' . $sSQLadd1 . ' TB.aure69idsistema=T3.unad01id AND TB.aure69idgrupo=T9.aure68id ' . $sSQLadd . '';
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
		}
	}
	$sSQL = 'SELECT TB.aure69consec, TB.aure69id, TB.aure69fecha, TB.aure69verupd, TB.aure69mayor, TB.aure69menor, 
	TB.aure69correccion, T9.aure68nombre, TB.aure69detalle, TB.aure69enlace, TB.aure69idsistema, TB.aure69idgrupo, 
	T9.aure68orden 
	FROM aure70verentrega AS T7, aure69versionado AS TB, aure68grupoversion AS T9 
	WHERE T7.aure70idtercero=' . $idTercero . ' AND T7.aure70idsistema IN (-12, ' . $idSistema . ') AND T7.aure70fechavisto=0 AND T7.aure70idversionado=TB.aure69id 
	AND ' . $sSQLadd1 . 'TB.aure69publico="S" AND TB.aure69idgrupo=T9.aure68id ' . $sSQLadd . '
	ORDER BY T9.aure68orden, STR_TO_DATE(TB.aure69fecha, "%d/%m/%Y") DESC';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_269" name="consulta_269" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_269" name="titulos_269" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Mensajes de ayuda no vistos: ' . $sSQL . $sLimite . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			if ($registros == 0) {
				if ($bMostrarVacios) {
					$sLeyenda = $ETI['msg_ayudas'] . ' <a href="unadayudas.php" class="lnkresalte">' . $ETI['lnk_ayudas'] . '</a>';
					if ($bModulo != '') {
						//$sLeyenda='No se han encontrado elementos de ayuda disponibles para el modulo solicitado ['.$bModulo.'].';
					}
					$sLeyenda = '<div class="salto1px"></div>
					<div class="GrupoCamposAyuda">
					' . $sLeyenda . '
					<div class="salto1px"></div>
					</div>';
				} else {
					$sLeyenda = '';
				}
				return array($sErrConsulta . $sLeyenda . $sBotones, $sDebug);
			}
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
				$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
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
	$res = $res . '<th colspan="2" style="text-align: center;"><b>' . $ETI['msg_novedades'] . '</b></th>';
	$res = $res . '<th class="text-right w-20">';
	$res = $res . html_paginador('paginaf269', $registros, $lineastabla, $pagina, 'paginarf269()');
	$res = $res . html_lpp('lppf269', $lineastabla, 'paginarf269()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	$idGrupo = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idGrupo != $filadet['aure69idgrupo']) {
			$idGrupo = $filadet['aure69idgrupo'];
			$res = $res . '<tr class="fondoazul">';
			$res = $res . '<td colspan="3" align="center">' . $ETI['aure69idgrupo'] . ': <b>' . cadena_notildes($filadet['aure68nombre']) . '</b></td>';
			$res = $res . '</tr>';
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['aure68orden']) {
			case 1:
				$sPrefijo = '<span class="azul">';
				$sSufijo = '</span>';
				break;
			case 6:
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
				break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;

		$et_aure69fecha = $filadet['aure69fecha'];
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td class="w-15">' . $sPrefijo . $et_aure69fecha . $sSufijo . '</td>';
		$res = $res . '<td colspan="2">' . $sPrefijo . cadena_notildes($filadet['aure69detalle']) . $sSufijo . '</td>';
		$res = $res . '</tr>';
		if ($filadet['aure69enlace'] != '') {
			$res = $res . '<tr' . $sClass . '>';
			$res = $res . '<td></td>';
			$res = $res . '<td>' . $ETI['msg_masinfo'] . ' <a href="' . $filadet['aure69enlace'] . '" class="lnkresalte" title="' . $filadet['aure69enlace'] . '" target="_blank">' . $ETI['msg_click'] . '</a></td>';
			$res = $res . '<td><a href="javascript:paginarf269(' . $filadet['aure69id'] . ')" class="lnkresalte">' . $ETI['msg_visto'] . '</a></td>';
			$res = $res . '</tr>';
		} else {
			$res = $res . '<tr' . $sClass . '>';
			$res = $res . '<td colspan="2"></td>';
			$res = $res . '<td><a href="javascript:paginarf269(' . $filadet['aure69id'] . ')" class="lnkresalte">' . $ETI['msg_visto'] . '</a></td>';
			$res = $res . '</tr>';
		}
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f269_HtmlTablaSistema($aParametros)
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
	if (isset($aParametros[1]) == 0) {
		$aParametros[1] = '';
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	$idMarcaLectura = (int)$aParametros[1];
	if ($idMarcaLectura != 0) {
		//Esta marcando una ayuda como leida.
		$idTercero = (int)$aParametros[100];
		$sSQL = 'UPDATE aure70verentrega SET aure70fechavisto=' . fecha_DiaMod() . ' WHERE aure70idversionado=' . $idMarcaLectura . ' AND aure70idtercero=' . $idTercero . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	list($sDetalle, $sDebugTabla) = f269_TablaDetalleV2Sistema($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f269detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
