<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2936 visa36convtipologia
*/
/** Archivo lib2936.php.
 * Libreria 2936 visa36convtipologia.
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @date viernes, 27 de febrero de 2026
 */
function f2936_HTMLComboV2_visa36idgrupo($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa36idgrupo', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.visa46id AS id, TB.visa46nombre AS nombre 
	FROM visa46grupotipologia AS TB
	WHERE TB.visa46id>0
	ORDER BY TB.visa46nombre';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2936, $sIdioma
	return $res;
}
function f2936_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$visa36idgrupo = numeros_validar($datos[1]);
	if ($visa36idgrupo == '') {
		$bHayLlave = false;
	}
	$visa36consec = numeros_validar($datos[2]);
	if ($visa36consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM visa36convtipologia WHERE visa36idgrupo=' . $visa36idgrupo . ' AND visa36consec=' . $visa36consec . '';
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
function f2936_Busquedas($aParametros)
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
	$mensajes_2936 = 'lg/lg_2936_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2936)) {
		$mensajes_2936 = 'lg/lg_2936_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2936;
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
	$sTituloModulo = $ETI['titulo_2936'];
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
function f2936_HtmlBusqueda($aParametros)
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
function f2936_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_2936 = 'lg/lg_2936_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2936)) {
		$mensajes_2936 = 'lg/lg_2936_es.php';
	}
	require $mensajes_2936;
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
	$sBotones = '<input id="paginaf2936" name="paginaf2936" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2936" name="lppf2936" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Consec, Id, Grupo, Nombre';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa36consec, TB.visa36id, T3.visa46nombre, TB.visa36nombre, TB.visa36idgrupo';
	$sConsulta = 'FROM visa36convtipologia AS TB, visa46grupotipologia AS T3 
	WHERE ' . $sSQLadd1 . ' TB.visa36id>0 AND TB.visa36idgrupo=T3.visa46id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa36consec, TB.visa36idgrupo';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2936: ' . $sSQLContador . '');
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
	$sErrConsulta = '<input id="consulta_2936" name="consulta_2936" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2936" name="titulos_2936" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2936: ' . $sSQL . '');
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
		$sDebug = $sDebug . log_debug('Termina la consulta 2936');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa36consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa36idgrupo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa36nombre'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2936', $registros, $lineastabla, $pagina, 'paginarf2936()');
	$res = $res . html_lpp('lppf2936', $lineastabla, 'paginarf2936()');
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
		$et_visa36consec = $sPrefijo . $filadet['visa36consec'] . $sSufijo;
		$et_visa36idgrupo = $sPrefijo . cadena_notildes($filadet['visa46nombre']) . $sSufijo;
		$et_visa36nombre = $sPrefijo . cadena_notildes($filadet['visa36nombre']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2936(' . $filadet['visa36id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa36consec . '</td>';
		$res = $res . '<td>' . $et_visa36idgrupo . '</td>';
		$res = $res . '<td>' . $et_visa36nombre . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2936_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2936_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2936detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2936_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'visa36idgrupo=' . $DATA['visa36idgrupo'] . ' AND visa36consec=' . $DATA['visa36consec'] . '';
	} else {
		$sSQLcondi = 'visa36id=' . $DATA['visa36id'] . '';
	}
	$sSQL = 'SELECT * FROM visa36convtipologia WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['visa36idgrupo'] = $fila['visa36idgrupo'];
		$DATA['visa36consec'] = $fila['visa36consec'];
		$DATA['visa36id'] = $fila['visa36id'];
		$DATA['visa36nombre'] = $fila['visa36nombre'];
		$DATA['visa36activo'] = $fila['visa36activo'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2936'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2936_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2936)
{
	$iCodModuloAudita = 2936;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2936 = 'lg/lg_2936_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2936)) {
		$mensajes_2936 = 'lg/lg_2936_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2936;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['visa36idgrupo']) == 0) {
		$DATA['visa36idgrupo'] = 0;
	}
	if (isset($DATA['visa36consec']) == 0) {
		$DATA['visa36consec'] = '';
	}
	if (isset($DATA['visa36id']) == 0) {
		$DATA['visa36id'] = '';
	}
	if (isset($DATA['visa36nombre']) == 0) {
		$DATA['visa36nombre'] = '';
	}
	if (isset($DATA['visa36activo']) == 0) {
		$DATA['visa36activo'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['visa36idgrupo'] = numeros_validar($DATA['visa36idgrupo']);
	$DATA['visa36consec'] = numeros_validar($DATA['visa36consec']);
	$DATA['visa36nombre'] = cadena_Validar(trim($DATA['visa36nombre']));
	$DATA['visa36activo'] = numeros_validar($DATA['visa36activo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['visa36activo'] == '') {
		$DATA['visa36activo'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['visa36activo'] == '') {
		$sError = $ERR['visa36activo'] . $sSepara . $sError;
	}
	if ($DATA['visa36nombre'] == '') {
		$sError = $ERR['visa36nombre'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'visa36nombre');
		$aLargoCampos = array(0, 50);
		for ($k = 1; $k <= 1; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	if ($DATA['visa36idgrupo'] == '') {
		$sError = $ERR['visa36idgrupo'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['visa36consec'] == '') {
				$DATA['visa36consec'] = tabla_consecutivo('visa36convtipologia', 'visa36consec', 'visa36idgrupo=' . $DATA['visa36idgrupo'] . '', $objDB);
				if ($DATA['visa36consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa36consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['visa36consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM visa36convtipologia WHERE visa36idgrupo=' . $DATA['visa36idgrupo'] . ' AND visa36consec=' . $DATA['visa36consec'] . '';
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
			$DATA['visa36id'] = tabla_consecutivo('visa36convtipologia', 'visa36id', '', $objDB);
			if ($DATA['visa36id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2936 = 'visa36idgrupo, visa36consec, visa36id, visa36nombre, visa36activo';
			$sValores2936 = '' . $DATA['visa36idgrupo'] . ', ' . $DATA['visa36consec'] . ', ' . $DATA['visa36id'] . ', "' . $DATA['visa36nombre'] . '", ' . $DATA['visa36activo'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa36convtipologia (' . $sCampos2936 . ') VALUES (' . cadena_codificar($sValores2936) . ');';
				$sDetalle = $sCampos2936 . '[' . cadena_codificar($sValores2936) . ']';
			} else {
				$sSQL = 'INSERT INTO visa36convtipologia (' . $sCampos2936 . ') VALUES (' . $sValores2936 . ');';
				$sDetalle = $sCampos2936 . '[' . $sValores2936 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'visa36nombre';
			$sCampo[2] = 'visa36activo';
			$sDato[1] = $DATA['visa36nombre'];
			$sDato[2] = $DATA['visa36activo'];
			$iNumCamposMod = 2;
			$sWhere = 'visa36id=' . $DATA['visa36id'] . '';
			$sSQL = 'SELECT * FROM visa36convtipologia WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE visa36convtipologia SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE visa36convtipologia SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2936 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2936] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['visa36id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['visa36id'], $sDetalle, $objDB);
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
function f2936_db_Eliminar($visa36id, $objDB, $bDebug = false)
{
	$iCodModulo = 2936;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2936 = 'lg/lg_2936_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2936)) {
		$mensajes_2936 = 'lg/lg_2936_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2936;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$visa36id = numeros_validar($visa36id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM visa36convtipologia WHERE visa36id=' . $visa36id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $visa36id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM visa37convsubtipo WHERE visa37idtipologia=' . $filabase['visa36id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Subtipologias de convocatorias creados, no es posible eliminar';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2936';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['visa36id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM visa37convsubtipo WHERE visa37idtipologia=' . $filabase['visa36id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'visa36id=' . $visa36id . '';
		//$sWhere = 'visa36consec=' . $filabase['visa36consec'] . ' AND visa36idgrupo=' . $filabase['visa36idgrupo'] . '';
		$sSQL = 'DELETE FROM visa36convtipologia WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa36id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

