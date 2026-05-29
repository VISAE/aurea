<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.16 viernes, 11 de julio de 2025
--- 1203 masi03listas
*/
/** Archivo lib1203.php.
 * Libreria 1203 masi03listas.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date viernes, 11 de julio de 2025
 */
function f1203_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$masi03consec = numeros_validar($datos[1]);
	if ($masi03consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM masi03listas WHERE masi03consec=' . $masi03consec . '';
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
function f1203_Busquedas($aParametros)
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
	$mensajes_1203 = 'lg/lg_1203_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1203)) {
		$mensajes_1203 = 'lg/lg_1203_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1203;
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
	$sTituloModulo = $ETI['titulo_1203'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'masi04idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['masi04idtercero_busca']) == 0) {
				$ETI['masi04idtercero_busca'] = 'Busqueda de Tercero';
			}
			$sTitulo = $ETI['masi04idtercero_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(1203);
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
function f1203_HtmlBusqueda($aParametros)
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
		case 'masi04idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f1203_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_1203 = 'lg/lg_1203_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1203)) {
		$mensajes_1203 = 'lg/lg_1203_es.php';
	}
	require $mensajes_1203;
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
	$sBotones = '<input id="paginaf1203" name="paginaf1203" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf1203" name="lppf1203" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Consec, Id, Nombre, Formaarmado, Proceso, Publica, Detalle';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.masi03consec, TB.masi03id, TB.masi03nombre, T4.masi71nombre, T5.masi72nombre, TB.masi03publica, TB.masi03detalle, TB.masi03formaarmado, TB.masi03idproceso';
	$sConsulta = 'FROM masi03listas AS TB, masi71formaarma AS T4, masi72proceso AS T5 
	WHERE ' . $sSQLadd1 . ' TB.masi03id>0 AND TB.masi03formaarmado=T4.masi71id AND TB.masi03idproceso=T5.masi72id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.masi03consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Totalizando consulta 1203: ' . $sSQLContador . '<br>';
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
	$sErrConsulta = '<input id="consulta_1203" name="consulta_1203" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_1203" name="titulos_1203" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 1203: ' . $sSQL . '<br>';
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
		$sDebug = $sDebug . fecha_microtiempo() . ' Termina la consulta 1203<br>';
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['masi03consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi03nombre'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi03formaarmado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi03idproceso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi03publica'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi03detalle'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf1203', $registros, $lineastabla, $pagina, 'paginarf1203()');
	$res = $res . html_lpp('lppf1203', $lineastabla, 'paginarf1203()');
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
		$et_masi03consec = $sPrefijo . $filadet['masi03consec'] . $sSufijo;
		$et_masi03nombre = $sPrefijo . cadena_notildes($filadet['masi03nombre']) . $sSufijo;
		$et_masi03formaarmado = $sPrefijo . cadena_notildes($filadet['masi71nombre']) . $sSufijo;
		$et_masi03idproceso = $sPrefijo . cadena_notildes($filadet['masi72nombre']) . $sSufijo;
		$et_masi03publica = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['masi03publica'] == 0) {
			$et_masi03publica = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_masi03detalle = $sPrefijo . cadena_notildes($filadet['masi03detalle']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf1203(' . $filadet['masi03id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_masi03consec . '</td>';
		$res = $res . '<td>' . $et_masi03nombre . '</td>';
		$res = $res . '<td>' . $et_masi03formaarmado . '</td>';
		$res = $res . '<td>' . $et_masi03idproceso . '</td>';
		$res = $res . '<td>' . $et_masi03publica . '</td>';
		$res = $res . '<td>' . $et_masi03detalle . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f1203_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f1203_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1203detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1203_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'masi03consec=' . $DATA['masi03consec'] . '';
	} else {
		$sSQLcondi = 'masi03id=' . $DATA['masi03id'] . '';
	}
	$sSQL = 'SELECT * FROM masi03listas WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['masi03consec'] = $fila['masi03consec'];
		$DATA['masi03id'] = $fila['masi03id'];
		$DATA['masi03nombre'] = $fila['masi03nombre'];
		$DATA['masi03formaarmado'] = $fila['masi03formaarmado'];
		$DATA['masi03idproceso'] = $fila['masi03idproceso'];
		$DATA['masi03publica'] = $fila['masi03publica'];
		$DATA['masi03detalle'] = $fila['masi03detalle'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta1203'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f1203_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 1203)
{
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1203 = 'lg/lg_1203_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1203)) {
		$mensajes_1203 = 'lg/lg_1203_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1203;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['masi03consec']) == 0) {
		$DATA['masi03consec'] = '';
	}
	if (isset($DATA['masi03id']) == 0) {
		$DATA['masi03id'] = '';
	}
	if (isset($DATA['masi03nombre']) == 0) {
		$DATA['masi03nombre'] = '';
	}
	if (isset($DATA['masi03formaarmado']) == 0) {
		$DATA['masi03formaarmado'] = 0;
	}
	if (isset($DATA['masi03idproceso']) == 0) {
		$DATA['masi03idproceso'] = 0;
	}
	if (isset($DATA['masi03publica']) == 0) {
		$DATA['masi03publica'] = 0;
	}
	if (isset($DATA['masi03detalle']) == 0) {
		$DATA['masi03detalle'] = '';
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['masi03consec'] = numeros_validar($DATA['masi03consec']);
	$DATA['masi03nombre'] = cadena_Validar(trim($DATA['masi03nombre']));
	$DATA['masi03formaarmado'] = numeros_validar($DATA['masi03formaarmado']);
	$DATA['masi03idproceso'] = numeros_validar($DATA['masi03idproceso']);
	$DATA['masi03publica'] = numeros_validar($DATA['masi03publica']);
	$DATA['masi03detalle'] = cadena_Validar(trim($DATA['masi03detalle']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['masi03formaarmado'] == '') {
		$DATA['masi03formaarmado'] = 0;
	}
	if ($DATA['masi03idproceso'] == '') {
		$DATA['masi03idproceso'] = 0;
	}
	if ($DATA['masi03publica'] == '') {
		$DATA['masi03publica'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		/*
		if ($DATA['masi03detalle'] == '') {
			$sError = $ERR['masi03detalle'] . $sSepara . $sError;
		}
		*/
		if ($DATA['masi03publica'] == '') {
			$sError = $ERR['masi03publica'] . $sSepara . $sError;
		}
		if ($DATA['masi03idproceso'] == '') {
			$sError = $ERR['masi03idproceso'] . $sSepara . $sError;
		}
		if ($DATA['masi03formaarmado'] == '') {
			$sError = $ERR['masi03formaarmado'] . $sSepara . $sError;
		}
		if ($DATA['masi03nombre'] == '') {
			$sError = $ERR['masi03nombre'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'masi03nombre');
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
			if ($DATA['masi03consec'] == '') {
				$DATA['masi03consec'] = tabla_consecutivo('masi03listas', 'masi03consec', '', $objDB);
				if ($DATA['masi03consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'masi03consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['masi03consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM masi03listas WHERE masi03consec=' . $DATA['masi03consec'] . '';
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
			$DATA['masi03id'] = tabla_consecutivo('masi03listas', 'masi03id', '', $objDB);
			if ($DATA['masi03id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		//$masi03detalle = addslashes($DATA['masi03detalle']);
		$masi03detalle = str_replace('"', '\"', $DATA['masi03detalle']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos1203 = 'masi03consec, masi03id, masi03nombre, masi03formaarmado, masi03idproceso, 
			masi03publica, masi03detalle';
			$sValores1203 = '' . $DATA['masi03consec'] . ', ' . $DATA['masi03id'] . ', "' . $DATA['masi03nombre'] . '", ' . $DATA['masi03formaarmado'] . ', ' . $DATA['masi03idproceso'] . ', 
			' . $DATA['masi03publica'] . ', "' . $masi03detalle . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO masi03listas (' . $sCampos1203 . ') VALUES (' . cadena_codificar($sValores1203) . ');';
				$sdetalle = $sCampos1203 . '[' . cadena_codificar($sValores1203) . ']';
			} else {
				$sSQL = 'INSERT INTO masi03listas (' . $sCampos1203 . ') VALUES (' . $sValores1203 . ');';
				$sdetalle = $sCampos1203 . '[' . $sValores1203 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'masi03nombre';
			$scampo[2] = 'masi03formaarmado';
			$scampo[3] = 'masi03idproceso';
			$scampo[4] = 'masi03publica';
			$scampo[5] = 'masi03detalle';
			$sdato[1] = $DATA['masi03nombre'];
			$sdato[2] = $DATA['masi03formaarmado'];
			$sdato[3] = $DATA['masi03idproceso'];
			$sdato[4] = $DATA['masi03publica'];
			$sdato[5] = $masi03detalle;
			$iNumCamposMod = 5;
			$sWhere = 'masi03id=' . $DATA['masi03id'] . '';
			$sSQL = 'SELECT * FROM masi03listas WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE masi03listas SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE masi03listas SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 1203 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [1203] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['masi03id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['masi03id'], $sdetalle, $objDB);
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
function f1203_db_Eliminar($masi03id, $objDB, $bDebug = false)
{
	$iCodModulo = 1203;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1203 = 'lg/lg_1203_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1203)) {
		$mensajes_1203 = 'lg/lg_1203_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1203;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$masi03id = numeros_validar($masi03id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM masi03listas WHERE masi03id=' . $masi03id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $masi03id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM masi04listapartic WHERE masi04idlista=' . $filabase['masi03id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Listas - participantes creados, no es posible eliminar';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1203';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['masi03id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM masi04listapartic WHERE masi04idlista=' . $filabase['masi03id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'masi03id=' . $masi03id . '';
		//$sWhere = 'masi03consec=' . $filabase['masi03consec'] . '';
		$sSQL = 'DELETE FROM masi03listas WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $masi03id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

