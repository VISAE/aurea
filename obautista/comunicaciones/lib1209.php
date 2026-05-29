<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.16 viernes, 11 de julio de 2025
--- 1209 masi09firma
*/
/** Archivo lib1209.php.
 * Libreria 1209 masi09firma.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date viernes, 11 de julio de 2025
 */
function f1209_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$masi09consec = numeros_validar($datos[1]);
	if ($masi09consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM masi09firma WHERE masi09consec=' . $masi09consec . '';
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
function f1209_Busquedas($aParametros)
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
	$mensajes_1209 = 'lg/lg_1209_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1209)) {
		$mensajes_1209 = 'lg/lg_1209_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1209;
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
	$sTituloModulo = $ETI['titulo_1209'];
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
function f1209_HtmlBusqueda($aParametros)
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
function f1209_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_1200 = 'lg/lg_1200_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1200)) {
		$mensajes_1200 = 'lg/lg_1200_es.php';
	}
	require $mensajes_1200;
	*/
	$mensajes_1209 = 'lg/lg_1209_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1209)) {
		$mensajes_1209 = 'lg/lg_1209_es.php';
	}
	require $mensajes_1209;
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
	$sBotones = '<input id="paginaf1209" name="paginaf1209" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf1209" name="lppf1209" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Consec, Id, Activa, Nombre, Cuerpo, Unidadfuncional, Escuela, Programa';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.masi09consec, TB.masi09id, TB.masi09activa, TB.masi09nombre, TB.masi09cuerpo, TB.masi09unidadfuncional, TB.masi09idescuela, TB.masi09idprograma';
	$sConsulta = 'FROM masi09firma AS TB 
	WHERE ' . $sSQLadd1 . ' TB.masi09id>0 ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.masi09consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Totalizando consulta 1209: ' . $sSQLContador . '<br>';
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
	$sErrConsulta = '<input id="consulta_1209" name="consulta_1209" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_1209" name="titulos_1209" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 1209: ' . $sSQL . '<br>';
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
		$sDebug = $sDebug . fecha_microtiempo() . ' Termina la consulta 1209<br>';
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['masi09consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi09activa'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi09nombre'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi09cuerpo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi09unidadfuncional'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi09idescuela'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi09idprograma'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf1209', $registros, $lineastabla, $pagina, 'paginarf1209()');
	$res = $res . html_lpp('lppf1209', $lineastabla, 'paginarf1209()');
	$res = $res . '</th>';
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
		$et_masi09consec = $sPrefijo . $filadet['masi09consec'] . $sSufijo;
		$et_masi09activa = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['masi09activa'] == 0) {
			$et_masi09activa = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_masi09nombre = $sPrefijo . cadena_notildes($filadet['masi09nombre']) . $sSufijo;
		$et_masi09cuerpo = $sPrefijo . cadena_notildes($filadet['masi09cuerpo']) . $sSufijo;
		$et_masi09unidadfuncional = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['masi09unidadfuncional'] == 0) {
			$et_masi09unidadfuncional = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_masi09idescuela = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['masi09idescuela'] == 0) {
			$et_masi09idescuela = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_masi09idprograma = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['masi09idprograma'] == 0) {
			$et_masi09idprograma = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf1209(' . $filadet['masi09id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_masi09consec . '</td>';
		$res = $res . '<td>' . $et_masi09activa . '</td>';
		$res = $res . '<td>' . $et_masi09nombre . '</td>';
		$res = $res . '<td>' . $et_masi09cuerpo . '</td>';
		$res = $res . '<td>' . $et_masi09unidadfuncional . '</td>';
		$res = $res . '<td>' . $et_masi09idescuela . '</td>';
		$res = $res . '<td>' . $et_masi09idprograma . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f1209_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f1209_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1209detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1209_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'masi09consec=' . $DATA['masi09consec'] . '';
	} else {
		$sSQLcondi = 'masi09id=' . $DATA['masi09id'] . '';
	}
	$sSQL = 'SELECT * FROM masi09firma WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['masi09consec'] = $fila['masi09consec'];
		$DATA['masi09id'] = $fila['masi09id'];
		$DATA['masi09activa'] = $fila['masi09activa'];
		$DATA['masi09nombre'] = $fila['masi09nombre'];
		$DATA['masi09cuerpo'] = $fila['masi09cuerpo'];
		$DATA['masi09unidadfuncional'] = $fila['masi09unidadfuncional'];
		$DATA['masi09idescuela'] = $fila['masi09idescuela'];
		$DATA['masi09idprograma'] = $fila['masi09idprograma'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta1209'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f1209_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 1209)
{
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1209 = 'lg/lg_1209_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1209)) {
		$mensajes_1209 = 'lg/lg_1209_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1209;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['masi09consec']) == 0) {
		$DATA['masi09consec'] = '';
	}
	if (isset($DATA['masi09id']) == 0) {
		$DATA['masi09id'] = '';
	}
	if (isset($DATA['masi09activa']) == 0) {
		$DATA['masi09activa'] = 0;
	}
	if (isset($DATA['masi09nombre']) == 0) {
		$DATA['masi09nombre'] = '';
	}
	if (isset($DATA['masi09cuerpo']) == 0) {
		$DATA['masi09cuerpo'] = '';
	}
	if (isset($DATA['masi09unidadfuncional']) == 0) {
		$DATA['masi09unidadfuncional'] = 0;
	}
	if (isset($DATA['masi09idescuela']) == 0) {
		$DATA['masi09idescuela'] = 0;
	}
	if (isset($DATA['masi09idprograma']) == 0) {
		$DATA['masi09idprograma'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['masi09consec'] = numeros_validar($DATA['masi09consec']);
	$DATA['masi09activa'] = numeros_validar($DATA['masi09activa']);
	$DATA['masi09nombre'] = cadena_Validar(trim($DATA['masi09nombre']));
	$DATA['masi09cuerpo'] = cadena_Validar(trim($DATA['masi09cuerpo']));
	$DATA['masi09unidadfuncional'] = numeros_validar($DATA['masi09unidadfuncional']);
	$DATA['masi09idescuela'] = numeros_validar($DATA['masi09idescuela']);
	$DATA['masi09idprograma'] = numeros_validar($DATA['masi09idprograma']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['masi09activa'] == '') {
		$DATA['masi09activa'] = 0;
	}
	if ($DATA['masi09unidadfuncional'] == '') {
		$DATA['masi09unidadfuncional'] = 0;
	}
	if ($DATA['masi09idescuela'] == '') {
		$DATA['masi09idescuela'] = 0;
	}
	if ($DATA['masi09idprograma'] == '') {
		$DATA['masi09idprograma'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		if ($DATA['masi09idprograma'] == '') {
			$sError = $ERR['masi09idprograma'] . $sSepara . $sError;
		}
		if ($DATA['masi09idescuela'] == '') {
			$sError = $ERR['masi09idescuela'] . $sSepara . $sError;
		}
		if ($DATA['masi09unidadfuncional'] == '') {
			$sError = $ERR['masi09unidadfuncional'] . $sSepara . $sError;
		}
		/*
		if ($DATA['masi09cuerpo'] == '') {
			$sError = $ERR['masi09cuerpo'] . $sSepara . $sError;
		}
		*/
		if ($DATA['masi09nombre'] == '') {
			$sError = $ERR['masi09nombre'] . $sSepara . $sError;
		}
		if ($DATA['masi09activa'] == '') {
			$sError = $ERR['masi09activa'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'masi09nombre');
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
			if ($DATA['masi09consec'] == '') {
				$DATA['masi09consec'] = tabla_consecutivo('masi09firma', 'masi09consec', '', $objDB);
				if ($DATA['masi09consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'masi09consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['masi09consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM masi09firma WHERE masi09consec=' . $DATA['masi09consec'] . '';
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
			$DATA['masi09id'] = tabla_consecutivo('masi09firma', 'masi09id', '', $objDB);
			if ($DATA['masi09id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		//$masi09cuerpo = addslashes($DATA['masi09cuerpo']);
		$masi09cuerpo = str_replace('"', '\"', $DATA['masi09cuerpo']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos1209 = 'masi09consec, masi09id, masi09activa, masi09nombre, masi09cuerpo, 
			masi09unidadfuncional, masi09idescuela, masi09idprograma';
			$sValores1209 = '' . $DATA['masi09consec'] . ', ' . $DATA['masi09id'] . ', ' . $DATA['masi09activa'] . ', "' . $DATA['masi09nombre'] . '", "' . $masi09cuerpo . '", 
			' . $DATA['masi09unidadfuncional'] . ', ' . $DATA['masi09idescuela'] . ', ' . $DATA['masi09idprograma'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO masi09firma (' . $sCampos1209 . ') VALUES (' . cadena_codificar($sValores1209) . ');';
				$sdetalle = $sCampos1209 . '[' . cadena_codificar($sValores1209) . ']';
			} else {
				$sSQL = 'INSERT INTO masi09firma (' . $sCampos1209 . ') VALUES (' . $sValores1209 . ');';
				$sdetalle = $sCampos1209 . '[' . $sValores1209 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'masi09activa';
			$scampo[2] = 'masi09nombre';
			$scampo[3] = 'masi09cuerpo';
			$scampo[4] = 'masi09unidadfuncional';
			$scampo[5] = 'masi09idescuela';
			$scampo[6] = 'masi09idprograma';
			$sdato[1] = $DATA['masi09activa'];
			$sdato[2] = $DATA['masi09nombre'];
			$sdato[3] = $masi09cuerpo;
			$sdato[4] = $DATA['masi09unidadfuncional'];
			$sdato[5] = $DATA['masi09idescuela'];
			$sdato[6] = $DATA['masi09idprograma'];
			$iNumCamposMod = 6;
			$sWhere = 'masi09id=' . $DATA['masi09id'] . '';
			$sSQL = 'SELECT * FROM masi09firma WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE masi09firma SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE masi09firma SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 1209 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [1209] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['masi09id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['masi09id'], $sdetalle, $objDB);
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
function f1209_db_Eliminar($masi09id, $objDB, $bDebug = false)
{
	$iCodModulo = 1209;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1209 = 'lg/lg_1209_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1209)) {
		$mensajes_1209 = 'lg/lg_1209_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1209;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$masi09id = numeros_validar($masi09id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM masi09firma WHERE masi09id=' . $masi09id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $masi09id . '}';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1209';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['masi09id'] . ' LIMIT 0, 1';
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
		$sWhere = 'masi09id=' . $masi09id . '';
		//$sWhere = 'masi09consec=' . $filabase['masi09consec'] . '';
		$sSQL = 'DELETE FROM masi09firma WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $masi09id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

