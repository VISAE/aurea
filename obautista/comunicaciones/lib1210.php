<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.0 viernes, 27 de marzo de 2026
--- 1210 masi10formato
*/
/** Archivo lib1210.php.
 * Libreria 1210 masi10formato.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date viernes, 27 de marzo de 2026
 */
function f1210_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$masi10consec = numeros_validar($datos[1]);
	if ($masi10consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sNomTabla1210 = 'masi10formato';
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla1210 . ' WHERE masi10consec=' . $masi10consec . '';
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
function f1210_Busquedas($aParametros)
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
	$mensajes_1210 = 'lg/lg_1210_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1210)) {
		$mensajes_1210 = 'lg/lg_1210_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1210;
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
	$sTituloModulo = $ETI['titulo_1210'];
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
function f1210_HtmlBusqueda($aParametros)
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
function f1210_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_1210 = 'lg/lg_1210_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1210)) {
		$mensajes_1210 = 'lg/lg_1210_es.php';
	}
	require $mensajes_1210;
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
	$sNomTabla1210 = 'masi10formato';
	$sLeyenda = '';
	$sBotones = '<input id="paginaf1210" name="paginaf1210" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf1210" name="lppf1210" type="hidden" value="' . $lineastabla . '"/>';
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
	if ($bnombre != '') {
		$sSQLadd = $sSQLadd . ' AND masi10titulo LIKE "%' . $bnombre . '%"';
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
	$sTitulos = 'Consec, Id, Titulo, Activo';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.masi10consec, TB.masi10id, TB.masi10titulo, TB.masi1oactivo';
	$sConsulta = 'FROM ' . $sNomTabla1210 . ' AS TB 
	WHERE ' . $sSQLadd1 . ' TB.masi10id>0 ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.masi10consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 1210: ' . $sSQLContador . '');
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
	$sErrConsulta = '<input id="consulta_1210" name="consulta_1210" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_1210" name="titulos_1210" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 1210: ' . $sSQL . '');
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
		$sDebug = $sDebug . log_debug('Termina la consulta 1210');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['masi10consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi10titulo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi1oactivo'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf1210', $registros, $lineastabla, $pagina, 'paginarf1210()');
	$res = $res . html_lpp('lppf1210', $lineastabla, 'paginarf1210()');
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
		$et_masi10consec = $sPrefijo . $filadet['masi10consec'] . $sSufijo;
		$et_masi10titulo = $sPrefijo . cadena_notildes($filadet['masi10titulo']) . $sSufijo;
		$et_masi1oactivo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['masi1oactivo'] == 0) {
			$et_masi1oactivo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf1210(' . $filadet['masi10id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_masi10consec . '</td>';
		$res = $res . '<td>' . $et_masi10titulo . '</td>';
		$res = $res . '<td>' . $et_masi1oactivo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f1210_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f1210_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1210detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1210_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 1210)
{
	$iCodModuloAudita = 1210;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1210 = 'lg/lg_1210_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1210)) {
		$mensajes_1210 = 'lg/lg_1210_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1210;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['masi10consec']) == 0) {
		$DATA['masi10consec'] = '';
	}
	if (isset($DATA['masi10id']) == 0) {
		$DATA['masi10id'] = '';
	}
	if (isset($DATA['masi1oactivo']) == 0) {
		$DATA['masi1oactivo'] = 0;
	}
	if (isset($DATA['masi10titulo']) == 0) {
		$DATA['masi10titulo'] = '';
	}
	if (isset($DATA['masi10encabezado']) == 0) {
		$DATA['masi10encabezado'] = '';
	}
	if (isset($DATA['masi10divcuerpo']) == 0) {
		$DATA['masi10divcuerpo'] = '';
	}
	if (isset($DATA['masi10divcodigocorreo']) == 0) {
		$DATA['masi10divcodigocorreo'] = '';
	}
	if (isset($DATA['masi10divcodigoconfirma']) == 0) {
		$DATA['masi10divcodigoconfirma'] = '';
	}
	if (isset($DATA['masi10divcodigorecupera']) == 0) {
		$DATA['masi10divcodigorecupera'] = '';
	}
	if (isset($DATA['masi10divfirma']) == 0) {
		$DATA['masi10divfirma'] = '';
	}
	if (isset($DATA['masi10piedepagina']) == 0) {
		$DATA['masi10piedepagina'] = '';
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['masi10consec'] = numeros_validar($DATA['masi10consec']);
	$DATA['masi1oactivo'] = numeros_validar($DATA['masi1oactivo']);
	$DATA['masi10titulo'] = cadena_Validar(trim($DATA['masi10titulo']));
	$DATA['masi10encabezado'] = cadena_Validar(trim($DATA['masi10encabezado']), true);
	$DATA['masi10divcuerpo'] = cadena_Validar(trim($DATA['masi10divcuerpo']), true);
	$DATA['masi10divcodigocorreo'] = cadena_Validar(trim($DATA['masi10divcodigocorreo']), true);
	$DATA['masi10divcodigoconfirma'] = cadena_Validar(trim($DATA['masi10divcodigoconfirma']), true);
	$DATA['masi10divcodigorecupera'] = cadena_Validar(trim($DATA['masi10divcodigorecupera']), true);
	$DATA['masi10divfirma'] = cadena_Validar(trim($DATA['masi10divfirma']), true);
	$DATA['masi10piedepagina'] = cadena_Validar(trim($DATA['masi10piedepagina']), true);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['masi1oactivo'] == '') {
		$DATA['masi1oactivo'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	/*
	if ($DATA['masi10piedepagina'] == '') {
		$sError = $ERR['masi10piedepagina'] . $sSepara . $sError;
	}
	*/
	/*
	if ($DATA['masi10divfirma'] == '') {
		$sError = $ERR['masi10divfirma'] . $sSepara . $sError;
	}
	*/
	/*
	if ($DATA['masi10divcodigorecupera'] == '') {
		$sError = $ERR['masi10divcodigorecupera'] . $sSepara . $sError;
	}
	*/
	/*
	if ($DATA['masi10divcodigoconfirma'] == '') {
		$sError = $ERR['masi10divcodigoconfirma'] . $sSepara . $sError;
	}
	*/
	/*
	if ($DATA['masi10divcodigocorreo'] == '') {
		$sError = $ERR['masi10divcodigocorreo'] . $sSepara . $sError;
	}
	*/
	/*
	if ($DATA['masi10divcuerpo'] == '') {
		$sError = $ERR['masi10divcuerpo'] . $sSepara . $sError;
	}
	*/
	/*
	if ($DATA['masi10encabezado'] == '') {
		$sError = $ERR['masi10encabezado'] . $sSepara . $sError;
	}
	*/
	if ($DATA['masi10titulo'] == '') {
		$sError = $ERR['masi10titulo'] . $sSepara . $sError;
	}
	if ($DATA['masi1oactivo'] == '') {
		$sError = $ERR['masi1oactivo'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'masi10titulo');
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
	$sNomTabla1210 = 'masi10formato';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['masi10consec'] == '') {
				$DATA['masi10consec'] = tabla_consecutivo($sNomTabla1210, 'masi10consec', '', $objDB);
				if ($DATA['masi10consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'masi10consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['masi10consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla1210 . ' WHERE masi10consec=' . $DATA['masi10consec'] . '';
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
			$DATA['masi10id'] = tabla_consecutivo($sNomTabla1210, 'masi10id', '', $objDB);
			if ($DATA['masi10id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		//$masi10encabezado = addslashes($DATA['masi10encabezado']);
		$masi10encabezado = str_replace('"', '\"', $DATA['masi10encabezado']);
		//$masi10divcuerpo = addslashes($DATA['masi10divcuerpo']);
		$masi10divcuerpo = str_replace('"', '\"', $DATA['masi10divcuerpo']);
		//$masi10divcodigocorreo = addslashes($DATA['masi10divcodigocorreo']);
		$masi10divcodigocorreo = str_replace('"', '\"', $DATA['masi10divcodigocorreo']);
		//$masi10divcodigoconfirma = addslashes($DATA['masi10divcodigoconfirma']);
		$masi10divcodigoconfirma = str_replace('"', '\"', $DATA['masi10divcodigoconfirma']);
		//$masi10divcodigorecupera = addslashes($DATA['masi10divcodigorecupera']);
		$masi10divcodigorecupera = str_replace('"', '\"', $DATA['masi10divcodigorecupera']);
		//$masi10divfirma = addslashes($DATA['masi10divfirma']);
		$masi10divfirma = str_replace('"', '\"', $DATA['masi10divfirma']);
		//$masi10piedepagina = addslashes($DATA['masi10piedepagina']);
		$masi10piedepagina = str_replace('"', '\"', $DATA['masi10piedepagina']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos1210 = 'masi10consec, masi10id, masi1oactivo, masi10titulo, masi10encabezado, 
			masi10divcuerpo, masi10divcodigocorreo, masi10divcodigoconfirma, masi10divcodigorecupera, masi10divfirma, 
			masi10piedepagina';
			$sValores1210 = '' . $DATA['masi10consec'] . ', ' . $DATA['masi10id'] . ', ' . $DATA['masi1oactivo'] . ', "' . $DATA['masi10titulo'] . '", "' . $masi10encabezado . '", 
			"' . $masi10divcuerpo . '", "' . $masi10divcodigocorreo . '", "' . $masi10divcodigoconfirma . '", "' . $masi10divcodigorecupera . '", "' . $masi10divfirma . '", 
			"' . $masi10piedepagina . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla1210 . ' (' . $sCampos1210 . ') VALUES (' . cadena_codificar($sValores1210) . ');';
				$sDetalle = $sCampos1210 . '[' . cadena_codificar($sValores1210) . ']';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla1210 . ' (' . $sCampos1210 . ') VALUES (' . $sValores1210 . ');';
				$sDetalle = $sCampos1210 . '[' . $sValores1210 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'masi1oactivo';
			$sCampo[2] = 'masi10titulo';
			$sCampo[3] = 'masi10encabezado';
			$sCampo[4] = 'masi10divcuerpo';
			$sCampo[5] = 'masi10divcodigocorreo';
			$sCampo[6] = 'masi10divcodigoconfirma';
			$sCampo[7] = 'masi10divcodigorecupera';
			$sCampo[8] = 'masi10divfirma';
			$sCampo[9] = 'masi10piedepagina';
			$sDato[1] = $DATA['masi1oactivo'];
			$sDato[2] = $DATA['masi10titulo'];
			$sDato[3] = $masi10encabezado;
			$sDato[4] = $masi10divcuerpo;
			$sDato[5] = $masi10divcodigocorreo;
			$sDato[6] = $masi10divcodigoconfirma;
			$sDato[7] = $masi10divcodigorecupera;
			$sDato[8] = $masi10divfirma;
			$sDato[9] = $masi10piedepagina;
			$iNumCamposMod = 9;
			$sWhere = 'masi10id=' . $DATA['masi10id'] . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla1210 . ' WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE ' . $sNomTabla1210 . ' SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sNomTabla1210 . ' SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 1210 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [1210] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['masi10id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['masi10id'], $sDetalle, $objDB);
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
function f1210_db_Eliminar($masi10id, $objDB, $bDebug = false)
{
	$iCodModulo = 1210;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1210 = 'lg/lg_1210_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1210)) {
		$mensajes_1210 = 'lg/lg_1210_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1210;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$masi10id = numeros_validar($masi10id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sNomTabla1210 = 'masi10formato';
		$sSQL = 'SELECT * FROM ' . $sNomTabla1210 . ' WHERE masi10id=' . $masi10id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $masi10id . '}';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1210';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['masi10id'] . ' LIMIT 0, 1';
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
		$sWhere = 'masi10id=' . $masi10id . '';
		//$sWhere = 'masi10consec=' . $filabase['masi10consec'] . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla1210 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $masi10id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

