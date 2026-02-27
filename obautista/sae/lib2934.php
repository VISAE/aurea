<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2934 visa34convtipo
*/
/** Archivo lib2934.php.
 * Libreria 2934 visa34convtipo.
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @date viernes, 27 de febrero de 2026
 */
function f2934_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$visa34consec = numeros_validar($datos[1]);
	if ($visa34consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM visa34convtipo WHERE visa34consec=' . $visa34consec . '';
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
function f2934_Busquedas($aParametros)
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
	$mensajes_2934 = 'lg/lg_2934_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2934)) {
		$mensajes_2934 = 'lg/lg_2934_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2934;
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
	$sTituloModulo = $ETI['titulo_2934'];
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
function f2934_HtmlBusqueda($aParametros)
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
function f2934_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_2900 = 'lg/lg_2900_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2900)) {
		$mensajes_2900 = 'lg/lg_2900_es.php';
	}
	require $mensajes_2900;
	*/
	$mensajes_2934 = 'lg/lg_2934_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2934)) {
		$mensajes_2934 = 'lg/lg_2934_es.php';
	}
	require $mensajes_2934;
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
	$iNumVariables = 103;
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
	$bNombre = cadena_Validar(trim($aParametros[103]));
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
	$sBotones = '<input id="paginaf2934" name="paginaf2934" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2934" name="lppf2934" type="hidden" value="' . $lineastabla . '"/>';
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
	/*
	if ($bnombre != '') {
		$sSQLadd = $sSQLadd . ' AND visa34nombre LIKE "%' . $bnombre . '%"';
	}
	if ($bNombre != '') {
		$sBase = mb_strtoupper($bNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'TB.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Consec, Id, Nombre, Grupotipologia, Activo';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa34consec, TB.visa34id, TB.visa34nombre, T4.visa46nombre, TB.visa34activo, TB.visa34grupotipologia';
	$sConsulta = 'FROM visa34convtipo AS TB, visa46grupotipologia AS T4 
	WHERE ' . $sSQLadd1 . ' TB.visa34id>0 AND TB.visa34grupotipologia=T4.visa46id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa34consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2934: ' . $sSQLContador . '');
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
	$sErrConsulta = '<input id="consulta_2934" name="consulta_2934" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2934" name="titulos_2934" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2934: ' . $sSQL . '');
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
		$sDebug = $sDebug . log_debug('Termina la consulta 2934');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa34consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa34nombre'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa34grupotipologia'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa34activo'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2934', $registros, $lineastabla, $pagina, 'paginarf2934()');
	$res = $res . html_lpp('lppf2934', $lineastabla, 'paginarf2934()');
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
		$et_visa34consec = $sPrefijo . $filadet['visa34consec'] . $sSufijo;
		$et_visa34nombre = $sPrefijo . cadena_notildes($filadet['visa34nombre']) . $sSufijo;
		$et_visa34grupotipologia = $sPrefijo . cadena_notildes($filadet['visa46nombre']) . $sSufijo;
		$et_visa34activo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa34activo'] == 0) {
			$et_visa34activo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2934(' . $filadet['visa34id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa34consec . '</td>';
		$res = $res . '<td>' . $et_visa34nombre . '</td>';
		$res = $res . '<td>' . $et_visa34grupotipologia . '</td>';
		$res = $res . '<td>' . $et_visa34activo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2934_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2934_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2934detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2934_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'visa34consec=' . $DATA['visa34consec'] . '';
	} else {
		$sSQLcondi = 'visa34id=' . $DATA['visa34id'] . '';
	}
	$sSQL = 'SELECT * FROM visa34convtipo WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['visa34consec'] = $fila['visa34consec'];
		$DATA['visa34id'] = $fila['visa34id'];
		$DATA['visa34nombre'] = $fila['visa34nombre'];
		$DATA['visa34rolestudiante'] = $fila['visa34rolestudiante'];
		$DATA['visa34roladministrativo'] = $fila['visa34roladministrativo'];
		$DATA['visa34rolacademico'] = $fila['visa34rolacademico'];
		$DATA['visa34rolaspirante'] = $fila['visa34rolaspirante'];
		$DATA['visa34rolegresado'] = $fila['visa34rolegresado'];
		$DATA['visa34rolexterno'] = $fila['visa34rolexterno'];
		$DATA['visa34grupotipologia'] = $fila['visa34grupotipologia'];
		$DATA['visa34activo'] = $fila['visa34activo'];
		$DATA['visa34aplicazona'] = $fila['visa34aplicazona'];
		$DATA['visa34aplicacentro'] = $fila['visa34aplicacentro'];
		$DATA['visa34aplicaescuela'] = $fila['visa34aplicaescuela'];
		$DATA['visa34aplicaprograma'] = $fila['visa34aplicaprograma'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2934'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2934_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2934)
{
	$iCodModuloAudita = 2934;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2934 = 'lg/lg_2934_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2934)) {
		$mensajes_2934 = 'lg/lg_2934_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2934;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['visa34consec']) == 0) {
		$DATA['visa34consec'] = '';
	}
	if (isset($DATA['visa34id']) == 0) {
		$DATA['visa34id'] = '';
	}
	if (isset($DATA['visa34nombre']) == 0) {
		$DATA['visa34nombre'] = '';
	}
	if (isset($DATA['visa34rolestudiante']) == 0) {
		$DATA['visa34rolestudiante'] = 0;
	}
	if (isset($DATA['visa34roladministrativo']) == 0) {
		$DATA['visa34roladministrativo'] = 0;
	}
	if (isset($DATA['visa34rolacademico']) == 0) {
		$DATA['visa34rolacademico'] = 0;
	}
	if (isset($DATA['visa34rolaspirante']) == 0) {
		$DATA['visa34rolaspirante'] = 0;
	}
	if (isset($DATA['visa34rolegresado']) == 0) {
		$DATA['visa34rolegresado'] = 0;
	}
	if (isset($DATA['visa34rolexterno']) == 0) {
		$DATA['visa34rolexterno'] = 0;
	}
	if (isset($DATA['visa34grupotipologia']) == 0) {
		$DATA['visa34grupotipologia'] = 0;
	}
	if (isset($DATA['visa34activo']) == 0) {
		$DATA['visa34activo'] = 0;
	}
	if (isset($DATA['visa34aplicazona']) == 0) {
		$DATA['visa34aplicazona'] = 0;
	}
	if (isset($DATA['visa34aplicacentro']) == 0) {
		$DATA['visa34aplicacentro'] = 0;
	}
	if (isset($DATA['visa34aplicaescuela']) == 0) {
		$DATA['visa34aplicaescuela'] = 0;
	}
	if (isset($DATA['visa34aplicaprograma']) == 0) {
		$DATA['visa34aplicaprograma'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['visa34consec'] = numeros_validar($DATA['visa34consec']);
	$DATA['visa34nombre'] = cadena_Validar(trim($DATA['visa34nombre']));
	$DATA['visa34rolestudiante'] = numeros_validar($DATA['visa34rolestudiante']);
	$DATA['visa34roladministrativo'] = numeros_validar($DATA['visa34roladministrativo']);
	$DATA['visa34rolacademico'] = numeros_validar($DATA['visa34rolacademico']);
	$DATA['visa34rolaspirante'] = numeros_validar($DATA['visa34rolaspirante']);
	$DATA['visa34rolegresado'] = numeros_validar($DATA['visa34rolegresado']);
	$DATA['visa34rolexterno'] = numeros_validar($DATA['visa34rolexterno']);
	$DATA['visa34grupotipologia'] = numeros_validar($DATA['visa34grupotipologia']);
	$DATA['visa34activo'] = numeros_validar($DATA['visa34activo']);
	$DATA['visa34aplicazona'] = numeros_validar($DATA['visa34aplicazona']);
	$DATA['visa34aplicacentro'] = numeros_validar($DATA['visa34aplicacentro']);
	$DATA['visa34aplicaescuela'] = numeros_validar($DATA['visa34aplicaescuela']);
	$DATA['visa34aplicaprograma'] = numeros_validar($DATA['visa34aplicaprograma']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['visa34rolestudiante'] == '') {
		$DATA['visa34rolestudiante'] = 0;
	}
	if ($DATA['visa34roladministrativo'] == '') {
		$DATA['visa34roladministrativo'] = 0;
	}
	if ($DATA['visa34rolacademico'] == '') {
		$DATA['visa34rolacademico'] = 0;
	}
	if ($DATA['visa34rolaspirante'] == '') {
		$DATA['visa34rolaspirante'] = 0;
	}
	if ($DATA['visa34rolegresado'] == '') {
		$DATA['visa34rolegresado'] = 0;
	}
	if ($DATA['visa34rolexterno'] == '') {
		$DATA['visa34rolexterno'] = 0;
	}
	if ($DATA['visa34grupotipologia'] == '') {
		$DATA['visa34grupotipologia'] = 0;
	}
	if ($DATA['visa34activo'] == '') {
		$DATA['visa34activo'] = 0;
	}
	if ($DATA['visa34aplicazona'] == '') {
		$DATA['visa34aplicazona'] = 0;
	}
	if ($DATA['visa34aplicacentro'] == '') {
		$DATA['visa34aplicacentro'] = 0;
	}
	if ($DATA['visa34aplicaescuela'] == '') {
		$DATA['visa34aplicaescuela'] = 0;
	}
	if ($DATA['visa34aplicaprograma'] == '') {
		$DATA['visa34aplicaprograma'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['visa34aplicaprograma'] == '') {
		$sError = $ERR['visa34aplicaprograma'] . $sSepara . $sError;
	}
	if ($DATA['visa34aplicaescuela'] == '') {
		$sError = $ERR['visa34aplicaescuela'] . $sSepara . $sError;
	}
	if ($DATA['visa34aplicacentro'] == '') {
		$sError = $ERR['visa34aplicacentro'] . $sSepara . $sError;
	}
	if ($DATA['visa34aplicazona'] == '') {
		$sError = $ERR['visa34aplicazona'] . $sSepara . $sError;
	}
	if ($DATA['visa34activo'] == '') {
		$sError = $ERR['visa34activo'] . $sSepara . $sError;
	}
	if ($DATA['visa34grupotipologia'] == '') {
		$sError = $ERR['visa34grupotipologia'] . $sSepara . $sError;
	}
	if ($DATA['visa34rolexterno'] == '') {
		$sError = $ERR['visa34rolexterno'] . $sSepara . $sError;
	}
	if ($DATA['visa34rolegresado'] == '') {
		$sError = $ERR['visa34rolegresado'] . $sSepara . $sError;
	}
	if ($DATA['visa34rolaspirante'] == '') {
		$sError = $ERR['visa34rolaspirante'] . $sSepara . $sError;
	}
	if ($DATA['visa34rolacademico'] == '') {
		$sError = $ERR['visa34rolacademico'] . $sSepara . $sError;
	}
	if ($DATA['visa34roladministrativo'] == '') {
		$sError = $ERR['visa34roladministrativo'] . $sSepara . $sError;
	}
	if ($DATA['visa34rolestudiante'] == '') {
		$sError = $ERR['visa34rolestudiante'] . $sSepara . $sError;
	}
	if ($DATA['visa34nombre'] == '') {
		$sError = $ERR['visa34nombre'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'visa34nombre');
		$aLargoCampos = array(0, 100);
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
			if ($DATA['visa34consec'] == '') {
				$DATA['visa34consec'] = tabla_consecutivo('visa34convtipo', 'visa34consec', '', $objDB);
				if ($DATA['visa34consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa34consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['visa34consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM visa34convtipo WHERE visa34consec=' . $DATA['visa34consec'] . '';
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
				$sError = $ERR['3'] . ' [Mod ' . $iCodModulo . ']';
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['visa34id'] = tabla_consecutivo('visa34convtipo', 'visa34id', '', $objDB);
			if ($DATA['visa34id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2934 = 'visa34consec, visa34id, visa34nombre, visa34rolestudiante, visa34roladministrativo, 
			visa34rolacademico, visa34rolaspirante, visa34rolegresado, visa34rolexterno, visa34grupotipologia, 
			visa34activo, visa34aplicazona, visa34aplicacentro, visa34aplicaescuela, visa34aplicaprograma';
			$sValores2934 = '' . $DATA['visa34consec'] . ', ' . $DATA['visa34id'] . ', "' . $DATA['visa34nombre'] . '", ' . $DATA['visa34rolestudiante'] . ', ' . $DATA['visa34roladministrativo'] . ', 
			' . $DATA['visa34rolacademico'] . ', ' . $DATA['visa34rolaspirante'] . ', ' . $DATA['visa34rolegresado'] . ', ' . $DATA['visa34rolexterno'] . ', ' . $DATA['visa34grupotipologia'] . ', 
			' . $DATA['visa34activo'] . ', ' . $DATA['visa34aplicazona'] . ', ' . $DATA['visa34aplicacentro'] . ', ' . $DATA['visa34aplicaescuela'] . ', ' . $DATA['visa34aplicaprograma'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa34convtipo (' . $sCampos2934 . ') VALUES (' . cadena_codificar($sValores2934) . ');';
				$sDetalle = $sCampos2934 . '[' . cadena_codificar($sValores2934) . ']';
			} else {
				$sSQL = 'INSERT INTO visa34convtipo (' . $sCampos2934 . ') VALUES (' . $sValores2934 . ');';
				$sDetalle = $sCampos2934 . '[' . $sValores2934 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'visa34nombre';
			$sCampo[2] = 'visa34rolestudiante';
			$sCampo[3] = 'visa34roladministrativo';
			$sCampo[4] = 'visa34rolacademico';
			$sCampo[5] = 'visa34rolaspirante';
			$sCampo[6] = 'visa34rolegresado';
			$sCampo[7] = 'visa34rolexterno';
			$sCampo[8] = 'visa34grupotipologia';
			$sCampo[9] = 'visa34activo';
			$sCampo[10] = 'visa34aplicazona';
			$sCampo[11] = 'visa34aplicacentro';
			$sCampo[12] = 'visa34aplicaescuela';
			$sCampo[13] = 'visa34aplicaprograma';
			$sDato[1] = $DATA['visa34nombre'];
			$sDato[2] = $DATA['visa34rolestudiante'];
			$sDato[3] = $DATA['visa34roladministrativo'];
			$sDato[4] = $DATA['visa34rolacademico'];
			$sDato[5] = $DATA['visa34rolaspirante'];
			$sDato[6] = $DATA['visa34rolegresado'];
			$sDato[7] = $DATA['visa34rolexterno'];
			$sDato[8] = $DATA['visa34grupotipologia'];
			$sDato[9] = $DATA['visa34activo'];
			$sDato[10] = $DATA['visa34aplicazona'];
			$sDato[11] = $DATA['visa34aplicacentro'];
			$sDato[12] = $DATA['visa34aplicaescuela'];
			$sDato[13] = $DATA['visa34aplicaprograma'];
			$iNumCamposMod = 13;
			$sWhere = 'visa34id=' . $DATA['visa34id'] . '';
			$sSQL = 'SELECT * FROM visa34convtipo WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE visa34convtipo SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE visa34convtipo SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2934 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2934] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['visa34id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['visa34id'], $sDetalle, $objDB);
				}
				//Acciones en el insertar.
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
function f2934_db_Eliminar($visa34id, $objDB, $bDebug = false)
{
	$iCodModulo = 2934;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2934 = 'lg/lg_2934_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2934)) {
		$mensajes_2934 = 'lg/lg_2934_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2934;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$visa34id = numeros_validar($visa34id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM visa34convtipo WHERE visa34id=' . $visa34id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $visa34id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM visa42convanexo WHERE visa42idtipo=' . $filabase['visa34id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Configura Anexos creados, no es posible eliminar';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2934';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['visa34id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM visa42convanexo WHERE visa42idtipo=' . $filabase['visa34id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'visa34id=' . $visa34id . '';
		//$sWhere = 'visa34consec=' . $filabase['visa34consec'] . '';
		$sSQL = 'DELETE FROM visa34convtipo WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa34id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

