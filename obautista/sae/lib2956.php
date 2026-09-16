<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 martes, 15 de septiembre de 2026
--- 2956 visa56persemanal
*/
/** Archivo lib2956.php.
 * Libreria 2956 visa56persemanal.
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @date martes, 15 de septiembre de 2026
 */
function f2956_NombreTabla() {
	return 'visa56persemanal';
}
function f2956_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$visa56consec = numeros_validar($datos[1]);
	if ($visa56consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sNomTabla2956 = f2956_NombreTabla();
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2956 . ' WHERE visa56consec=' . $visa56consec . '';
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
function f2956_Busquedas($aParametros)
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
	$mensajes_2956 = 'lg/lg_2956_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2956)) {
		$mensajes_2956 = 'lg/lg_2956_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2956;
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
	$sTituloModulo = $ETI['titulo_2956'];
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
function f2956_HtmlBusqueda($aParametros)
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
function f2956_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_2956 = 'lg/lg_2956_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2956)) {
		$mensajes_2956 = 'lg/lg_2956_es.php';
	}
	require $mensajes_2956;
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
	$sNomTabla2956 = f2956_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2956" name="paginaf2956" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2956" name="lppf2956" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
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
	$sTitulos = 'Id, Fechaini, Fechafin, Fechacierre, Estado, Activo';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa56id, TB.visa56fechaini, TB.visa56fechafin, TB.visa56fechacierre, TB.visa56estado, TB.visa56activo';
	$sConsulta = 'FROM ' . $sNomTabla2956 . ' AS TB 
	WHERE ' . $sSQLadd1 . ' TB.visa56id>0 ' . $sSQLadd . '';
	$sOrden = 'ORDER BY ';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2956: ' . $sSQLContador . '');
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
	$sErrConsulta = '<input id="consulta_2956" name="consulta_2956" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2956" name="titulos_2956" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2956: ' . $sSQL . '');
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
		$sDebug = $sDebug . log_debug('Termina la consulta 2956');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa56fechaini'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa56fechafin'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa56fechacierre'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa56estado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa56activo'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2956', $registros, $lineastabla, $pagina, 'paginarf2956()');
	$res = $res . html_lpp('lppf2956', $lineastabla, 'paginarf2956()');
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
		$et_visa56fechaini = '';
		if ($filadet['visa56fechaini'] != 0) {
			$et_visa56fechaini = $sPrefijo . fecha_desdenumero($filadet['visa56fechaini']) . $sSufijo;
		}
		$et_visa56fechafin = '';
		if ($filadet['visa56fechafin'] != 0) {
			$et_visa56fechafin = $sPrefijo . fecha_desdenumero($filadet['visa56fechafin']) . $sSufijo;
		}
		$et_visa56fechacierre = '';
		if ($filadet['visa56fechacierre'] != 0) {
			$et_visa56fechacierre = $sPrefijo . fecha_desdenumero($filadet['visa56fechacierre']) . $sSufijo;
		}
		$et_visa56estado = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa56estado'] == 0) {
			$et_visa56estado = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_visa56activo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa56activo'] == 0) {
			$et_visa56activo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2956(' . $filadet['visa56id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa56fechaini . '</td>';
		$res = $res . '<td>' . $et_visa56fechafin . '</td>';
		$res = $res . '<td>' . $et_visa56fechacierre . '</td>';
		$res = $res . '<td>' . $et_visa56estado . '</td>';
		$res = $res . '<td>' . $et_visa56activo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2956_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2956_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2956detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2956_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2956)
{
	$iCodModuloAudita = 2956;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2956 = 'lg/lg_2956_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2956)) {
		$mensajes_2956 = 'lg/lg_2956_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2956;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['visa56consec']) == 0) {
		$DATA['visa56consec'] = '';
	}
	if (isset($DATA['visa56id']) == 0) {
		$DATA['visa56id'] = '';
	}
	if (isset($DATA['visa56fechaini']) == 0) {
		$DATA['visa56fechaini'] = 0;
	}
	if (isset($DATA['visa56fechafin']) == 0) {
		$DATA['visa56fechafin'] = 0;
	}
	if (isset($DATA['visa56estado']) == 0) {
		$DATA['visa56estado'] = 0;
	}
	if (isset($DATA['visa56fechacrea']) == 0) {
		$DATA['visa56fechacrea'] = 0;
	}
	if (isset($DATA['visa56fechacierre']) == 0) {
		$DATA['visa56fechacierre'] = 0;
	}
	if (isset($DATA['visa56observacion']) == 0) {
		$DATA['visa56observacion'] = '';
	}
	if (isset($DATA['visa56activo']) == 0) {
		$DATA['visa56activo'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['visa56consec'] = numeros_validar($DATA['visa56consec']);
	$DATA['visa56fechaini'] = numeros_validar($DATA['visa56fechaini']);
	$DATA['visa56fechafin'] = numeros_validar($DATA['visa56fechafin']);
	$DATA['visa56estado'] = numeros_validar($DATA['visa56estado']);
	$DATA['visa56fechacrea'] = numeros_validar($DATA['visa56fechacrea']);
	$DATA['visa56fechacierre'] = numeros_validar($DATA['visa56fechacierre']);
	$DATA['visa56observacion'] = cadena_Validar(trim($DATA['visa56observacion']));
	$DATA['visa56activo'] = numeros_validar($DATA['visa56activo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['visa56fechaini'] == '') {
		$DATA['visa56fechaini'] = 0;
	}
	if ($DATA['visa56fechafin'] == '') {
		$DATA['visa56fechafin'] = 0;
	}
	if ($DATA['visa56estado'] == '') {
		$DATA['visa56estado'] = 0;
	}
	if ($DATA['visa56fechacrea'] == '') {
		$DATA['visa56fechacrea'] = 0;
	}
	if ($DATA['visa56fechacierre'] == '') {
		$DATA['visa56fechacierre'] = 0;
	}
	if ($DATA['visa56activo'] == '') {
		$DATA['visa56activo'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['visa56activo'] == '') {
		$sError = $ERR['visa56activo'] . $sSepara . $sError;
	}
	/*
	if ($DATA['visa56observacion'] == '') {
		$sError = $ERR['visa56observacion'] . $sSepara . $sError;
	}
	*/
	if (!fecha_NumValido($DATA['visa56fechacierre'])) {
		//$DATA['visa56fechacierre'] = fecha_DiaMod();
		$sError = $ERR['visa56fechacierre'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa56fechacrea'])) {
		//$DATA['visa56fechacrea'] = fecha_DiaMod();
		$sError = $ERR['visa56fechacrea'] . $sSepara . $sError;
	}
	if ($DATA['visa56estado'] == '') {
		$sError = $ERR['visa56estado'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa56fechafin'])) {
		//$DATA['visa56fechafin'] = fecha_DiaMod();
		$sError = $ERR['visa56fechafin'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa56fechaini'])) {
		//$DATA['visa56fechaini'] = fecha_DiaMod();
		$sError = $ERR['visa56fechaini'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$sNomTabla2956 = f2956_NombreTabla();
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['visa56consec'] == '') {
				$DATA['visa56consec'] = tabla_consecutivo($sNomTabla2956, 'visa56consec', '', $objDB);
				if ($DATA['visa56consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa56consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['visa56consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla2956 . ' WHERE visa56consec=' . $DATA['visa56consec'] . '';
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
			$DATA['visa56id'] = tabla_consecutivo($sNomTabla2956, 'visa56id', '', $objDB);
			if ($DATA['visa56id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$visa56fechaini = 0; //fecha_DiaMod();
			$visa56fechafin = 0; //fecha_DiaMod();
			$visa56fechacrea = 0; //fecha_DiaMod();
			$visa56fechacierre = 0; //fecha_DiaMod();
		}
	}
	if ($sError == '') {
		//$visa56observacion = addslashes($DATA['visa56observacion']);
		$visa56observacion = str_replace('"', '\"', $DATA['visa56observacion']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2956 = 'visa56consec, visa56id, visa56fechaini, visa56fechafin, visa56estado, 
			visa56fechacrea, visa56fechacierre, visa56observacion, visa56activo';
			$sValores2956 = '' . $DATA['visa56consec'] . ', ' . $DATA['visa56id'] . ', ' . $DATA['visa56fechaini'] . ', ' . $DATA['visa56fechafin'] . ', ' . $DATA['visa56estado'] . ', 
			' . $DATA['visa56fechacrea'] . ', ' . $DATA['visa56fechacierre'] . ', "' . $visa56observacion . '", ' . $DATA['visa56activo'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla2956 . ' (' . $sCampos2956 . ') VALUES (' . cadena_codificar($sValores2956) . ');';
				$sDetalle = $sCampos2956 . '[' . cadena_codificar($sValores2956) . ']';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla2956 . ' (' . $sCampos2956 . ') VALUES (' . $sValores2956 . ');';
				$sDetalle = $sCampos2956 . '[' . $sValores2956 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'visa56fechaini';
			$sCampo[2] = 'visa56fechafin';
			$sCampo[3] = 'visa56estado';
			$sCampo[4] = 'visa56fechacrea';
			$sCampo[5] = 'visa56fechacierre';
			$sCampo[6] = 'visa56observacion';
			$sCampo[7] = 'visa56activo';
			$sDato[1] = $DATA['visa56fechaini'];
			$sDato[2] = $DATA['visa56fechafin'];
			$sDato[3] = $DATA['visa56estado'];
			$sDato[4] = $DATA['visa56fechacrea'];
			$sDato[5] = $DATA['visa56fechacierre'];
			$sDato[6] = $visa56observacion;
			$sDato[7] = $DATA['visa56activo'];
			$iNumCamposMod = 7;
			$sWhere = 'visa56id=' . $DATA['visa56id'] . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla2956 . ' WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE ' . $sNomTabla2956 . ' SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sNomTabla2956 . ' SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2956 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2956] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['visa56id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['visa56id'], $sDetalle, $objDB);
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
function f2956_db_Eliminar($visa56id, $objDB, $bDebug = false)
{
	$iCodModulo = 2956;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2956 = 'lg/lg_2956_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2956)) {
		$mensajes_2956 = 'lg/lg_2956_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2956;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$visa56id = numeros_validar($visa56id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sNomTabla2956 = f2956_NombreTabla();
		$sSQL = 'SELECT * FROM ' . $sNomTabla2956 . ' WHERE visa56id=' . $visa56id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $visa56id . '}';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2956';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['visa56id'] . ' LIMIT 0, 1';
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
		$sWhere = 'visa56id=' . $visa56id . '';
		//$sWhere = 'visa56consec=' . $filabase['visa56consec'] . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla2956 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa56id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

