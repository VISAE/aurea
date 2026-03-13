<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2938 visa38convpruebas
*/
/** Archivo lib2938.php.
 * Libreria 2938 visa38convpruebas.
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @date viernes, 27 de febrero de 2026
 */
function f2938_HTMLComboV2_visa38idtipo($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa38idtipo', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.visa34id AS id, TB.visa34nombre AS nombre 
	FROM visa34convtipo AS TB
	WHERE TB.visa34id>0
	ORDER BY TB.visa34nombre';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2938, $sIdioma
	return $res;
}
function f2938_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$visa38idtipo = numeros_validar($datos[1]);
	if ($visa38idtipo == '') {
		$bHayLlave = false;
	}
	$visa38consec = numeros_validar($datos[2]);
	if ($visa38consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM visa38convpruebas WHERE visa38idtipo=' . $visa38idtipo . ' AND visa38consec=' . $visa38consec . '';
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
function f2938_Busquedas($aParametros)
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
	$mensajes_2938 = 'lg/lg_2938_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2938)) {
		$mensajes_2938 = 'lg/lg_2938_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2938;
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
	$sTituloModulo = $ETI['titulo_2938'];
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
function f2938_HtmlBusqueda($aParametros)
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
function f2938_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_2938 = 'lg/lg_2938_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2938)) {
		$mensajes_2938 = 'lg/lg_2938_es.php';
	}
	require $mensajes_2938;
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
	$iNumVariables = 102;
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
	//$bNombre = trim($aParametros[103]);
	//$bListar = numeros_validar($aParametros[104]);
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
	$sBotones = '<input id="paginaf2938" name="paginaf2938" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2938" name="lppf2938" type="hidden" value="' . $lineastabla . '"/>';
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
	if ($aParametros[104] != '') {
		$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[104] . '%"';
	}
	if ($aParametros[104] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.campo2 LIKE "%' . $aParametros[104] . '%" AND ';
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
	$sTitulos = 'Consec, Id, Tipo, Nombre, Puntajemaximo';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa38consec, TB.visa38id, T3.visa34nombre, TB.visa38nombre, TB.visa38puntajemaximo, TB.visa38idtipo';
	$sConsulta = 'FROM visa38convpruebas AS TB, visa34convtipo AS T3 
	WHERE ' . $sSQLadd1 . ' TB.visa38id>0 AND TB.visa38idtipo=T3.visa34id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa38consec, TB.visa38idtipo';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2938: ' . $sSQLContador . '');
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
	$sErrConsulta = '<input id="consulta_2938" name="consulta_2938" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2938" name="titulos_2938" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2938: ' . $sSQL . '');
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
		$sDebug = $sDebug . log_debug('Termina la consulta 2938');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa38consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa38idtipo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa38nombre'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa38puntajemaximo'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2938', $registros, $lineastabla, $pagina, 'paginarf2938()');
	$res = $res . html_lpp('lppf2938', $lineastabla, 'paginarf2938()');
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
		$et_visa38consec = $sPrefijo . $filadet['visa38consec'] . $sSufijo;
		$et_visa38idtipo = $sPrefijo . cadena_notildes($filadet['visa34nombre']) . $sSufijo;
		$et_visa38nombre = $sPrefijo . cadena_notildes($filadet['visa38nombre']) . $sSufijo;
		$et_visa38puntajemaximo = '';
		if ($filadet['visa38puntajemaximo'] != 0) {
			$et_visa38puntajemaximo = $sPrefijo . formato_numero($filadet['visa38puntajemaximo']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2938(' . $filadet['visa38id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa38consec . '</td>';
		$res = $res . '<td>' . $et_visa38idtipo . '</td>';
		$res = $res . '<td>' . $et_visa38nombre . '</td>';
		$res = $res . '<td>' . $et_visa38puntajemaximo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2938_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2938_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2938detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2938_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'visa38idtipo=' . $DATA['visa38idtipo'] . ' AND visa38consec=' . $DATA['visa38consec'] . '';
	} else {
		$sSQLcondi = 'visa38id=' . $DATA['visa38id'] . '';
	}
	$sSQL = 'SELECT * FROM visa38convpruebas WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['visa38idtipo'] = $fila['visa38idtipo'];
		$DATA['visa38consec'] = $fila['visa38consec'];
		$DATA['visa38id'] = $fila['visa38id'];
		$DATA['visa38nombre'] = $fila['visa38nombre'];
		$DATA['visa38tipoprueba'] = $fila['visa38tipoprueba'];
		$DATA['visa38puntajemaximo'] = $fila['visa38puntajemaximo'];
		$DATA['visa38puntajeaproba'] = $fila['visa38puntajeaproba'];
		$DATA['visa38activo'] = $fila['visa38activo'];
		$DATA['visa38idnav'] = $fila['visa38idnav'];
		$DATA['visa38idmoodle'] = $fila['visa38idmoodle'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2938'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2938_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2938)
{
	$iCodModuloAudita = 2938;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2938 = 'lg/lg_2938_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2938)) {
		$mensajes_2938 = 'lg/lg_2938_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2938;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['visa38idtipo']) == 0) {
		$DATA['visa38idtipo'] = 0;
	}
	if (isset($DATA['visa38consec']) == 0) {
		$DATA['visa38consec'] = '';
	}
	if (isset($DATA['visa38id']) == 0) {
		$DATA['visa38id'] = '';
	}
	if (isset($DATA['visa38nombre']) == 0) {
		$DATA['visa38nombre'] = '';
	}
	if (isset($DATA['visa38tipoprueba']) == 0) {
		$DATA['visa38tipoprueba'] = 0;
	}
	if (isset($DATA['visa38puntajemaximo']) == 0) {
		$DATA['visa38puntajemaximo'] = 0;
	}
	if (isset($DATA['visa38puntajeaproba']) == 0) {
		$DATA['visa38puntajeaproba'] = 0;
	}
	if (isset($DATA['visa38activo']) == 0) {
		$DATA['visa38activo'] = 0;
	}
	if (isset($DATA['visa38idnav']) == 0) {
		$DATA['visa38idnav'] = 0;
	}
	if (isset($DATA['visa38idmoodle']) == 0) {
		$DATA['visa38idmoodle'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['visa38idtipo'] = numeros_validar($DATA['visa38idtipo']);
	$DATA['visa38consec'] = numeros_validar($DATA['visa38consec']);
	$DATA['visa38nombre'] = cadena_Validar(trim($DATA['visa38nombre']));
	$DATA['visa38tipoprueba'] = numeros_validar($DATA['visa38tipoprueba']);
	$DATA['visa38puntajemaximo'] = numeros_validar($DATA['visa38puntajemaximo']);
	$DATA['visa38puntajeaproba'] = numeros_validar($DATA['visa38puntajeaproba']);
	$DATA['visa38activo'] = numeros_validar($DATA['visa38activo']);
	$DATA['visa38idnav'] = numeros_validar($DATA['visa38idnav']);
	$DATA['visa38idmoodle'] = numeros_validar($DATA['visa38idmoodle']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['visa38tipoprueba'] == '') {
		$DATA['visa38tipoprueba'] = 0;
	}
	if ($DATA['visa38puntajemaximo'] == '') {
		$DATA['visa38puntajemaximo'] = 0;
	}
	if ($DATA['visa38puntajeaproba'] == '') {
		$DATA['visa38puntajeaproba'] = 0;
	}
	if ($DATA['visa38activo'] == '') {
		$DATA['visa38activo'] = 0;
	}
	if ($DATA['visa38idnav'] == '') {
		$DATA['visa38idnav'] = 0;
	}
	if ($DATA['visa38idmoodle'] == '') {
		$DATA['visa38idmoodle'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['visa38idmoodle'] == '') {
		$sError = $ERR['visa38idmoodle'] . $sSepara . $sError;
	}
	if ($DATA['visa38idnav'] == '') {
		$sError = $ERR['visa38idnav'] . $sSepara . $sError;
	}
	if ($DATA['visa38activo'] == '') {
		$sError = $ERR['visa38activo'] . $sSepara . $sError;
	}
	if ($DATA['visa38puntajeaproba'] == '') {
		$sError = $ERR['visa38puntajeaproba'] . $sSepara . $sError;
	}
	if ($DATA['visa38puntajemaximo'] == '') {
		$sError = $ERR['visa38puntajemaximo'] . $sSepara . $sError;
	}
	if ($DATA['visa38tipoprueba'] == '') {
		$sError = $ERR['visa38tipoprueba'] . $sSepara . $sError;
	}
	if ($DATA['visa38nombre'] == '') {
		$sError = $ERR['visa38nombre'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'visa38nombre');
		$aLargoCampos = array(0, 50);
		for ($k = 1; $k <= 1; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	if ($DATA['visa38idtipo'] == '') {
		$sError = $ERR['visa38idtipo'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['visa38consec'] == '') {
				$DATA['visa38consec'] = tabla_consecutivo('visa38convpruebas', 'visa38consec', 'visa38idtipo=' . $DATA['visa38idtipo'] . '', $objDB);
				if ($DATA['visa38consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa38consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['visa38consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM visa38convpruebas WHERE visa38idtipo=' . $DATA['visa38idtipo'] . ' AND visa38consec=' . $DATA['visa38consec'] . '';
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
			$DATA['visa38id'] = tabla_consecutivo('visa38convpruebas', 'visa38id', '', $objDB);
			if ($DATA['visa38id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2938 = 'visa38idtipo, visa38consec, visa38id, visa38nombre, visa38tipoprueba, 
			visa38puntajemaximo, visa38puntajeaproba, visa38activo, visa38idnav, visa38idmoodle';
			$sValores2938 = '' . $DATA['visa38idtipo'] . ', ' . $DATA['visa38consec'] . ', ' . $DATA['visa38id'] . ', "' . $DATA['visa38nombre'] . '", ' . $DATA['visa38tipoprueba'] . ', 
			' . $DATA['visa38puntajemaximo'] . ', ' . $DATA['visa38puntajeaproba'] . ', ' . $DATA['visa38activo'] . ', ' . $DATA['visa38idnav'] . ', ' . $DATA['visa38idmoodle'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa38convpruebas (' . $sCampos2938 . ') VALUES (' . cadena_codificar($sValores2938) . ');';
				$sDetalle = $sCampos2938 . '[' . cadena_codificar($sValores2938) . ']';
			} else {
				$sSQL = 'INSERT INTO visa38convpruebas (' . $sCampos2938 . ') VALUES (' . $sValores2938 . ');';
				$sDetalle = $sCampos2938 . '[' . $sValores2938 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'visa38nombre';
			$sCampo[2] = 'visa38tipoprueba';
			$sCampo[3] = 'visa38puntajemaximo';
			$sCampo[4] = 'visa38puntajeaproba';
			$sCampo[5] = 'visa38activo';
			$sCampo[6] = 'visa38idnav';
			$sCampo[7] = 'visa38idmoodle';
			$sDato[1] = $DATA['visa38nombre'];
			$sDato[2] = $DATA['visa38tipoprueba'];
			$sDato[3] = $DATA['visa38puntajemaximo'];
			$sDato[4] = $DATA['visa38puntajeaproba'];
			$sDato[5] = $DATA['visa38activo'];
			$sDato[6] = $DATA['visa38idnav'];
			$sDato[7] = $DATA['visa38idmoodle'];
			$iNumCamposMod = 7;
			$sWhere = 'visa38id=' . $DATA['visa38id'] . '';
			$sSQL = 'SELECT * FROM visa38convpruebas WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE visa38convpruebas SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE visa38convpruebas SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2938 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2938] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['visa38id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['visa38id'], $sDetalle, $objDB);
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
function f2938_db_Eliminar($visa38id, $objDB, $bDebug = false)
{
	$iCodModulo = 2938;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2938 = 'lg/lg_2938_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2938)) {
		$mensajes_2938 = 'lg/lg_2938_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2938;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$visa38id = numeros_validar($visa38id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM visa38convpruebas WHERE visa38id=' . $visa38id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $visa38id . '}';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2938';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['visa38id'] . ' LIMIT 0, 1';
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
		$sWhere = 'visa38id=' . $visa38id . '';
		//$sWhere = 'visa38consec=' . $filabase['visa38consec'] . ' AND visa38idtipo=' . $filabase['visa38idtipo'] . '';
		$sSQL = 'DELETE FROM visa38convpruebas WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa38id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

