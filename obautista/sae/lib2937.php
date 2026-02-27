<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2937 Subtipologias de convocatorias
*/
function f2937_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2937)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2937 = 'lg/lg_2937_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2937)) {
		$mensajes_2937 = 'lg/lg_2937_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2937;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$bInserta = false;
	$iAccion = 3;
	if (!is_array($valores)) {
		$valores = json_decode(str_replace('\"', '"', $valores), true);
	}
	// -- Se inicia validando todas las posibles entradas de usuario.
	$visa37idtipologia = numeros_validar($valores[1]);
	$visa37id = numeros_validar($valores[2], true);
	$visa37nombre = cadena_Validar(trim($valores[3]));
	$visa37activo = numeros_validar($valores[4]);
	/*
	if ($visa37activo == '') {
		$visa37activo = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa37activo == '') {
		$sError = $ERR['visa37activo'] . $sSepara . $sError;
	}
	if ($visa37nombre == '') {
		$sError = $ERR['visa37nombre'] . $sSepara . $sError;
	}
	/*
	if ($visa37id == '') {
		$sError = $ERR['visa37id'] . $sSepara . $sError;
	}
	*/
	if ($visa37idtipologia == '') {
		$sError = $ERR['visa37idtipologia'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$visa37id == 0) {
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM visa37convsubtipo WHERE visa37idtipologia=' . $visa37idtipologia . '';
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
			if ($sError == '') {
				$visa37id = tabla_consecutivo('visa37convsubtipo', 'visa37id', '', $objDB);
				if ($visa37id == -1) {
					$sError = $objDB->serror;
				}
				$bInserta = true;
				$iAccion = 2;
			}
		} else {
			list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'] . ' [Mod ' . $iCodModulo . ']';
			}
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$sCampos2937 = 'visa37idtipologia, visa37id, visa37nombre, visa37activo';
			$sValores2937 = '' . $visa37idtipologia . ', ' . $visa37id . ', "' . $visa37nombre . '", ' . $visa37activo . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa37convsubtipo (' . $sCampos2937 . ') VALUES (' . cadena_codificar($sValores2937) . ');';
			} else {
				$sSQL = 'INSERT INTO visa37convsubtipo (' . $sCampos2937 . ') VALUES (' . $sValores2937 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2937 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2937].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa37id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2937[1] = 'visa37nombre';
			$scampo2937[2] = 'visa37activo';
			$svr2937[1] = $visa37nombre;
			$svr2937[2] = $visa37activo;
			$iNumCampos = 2;
			$sWhere = 'visa37id=' . $visa37id . '';
			//$sWhere = 'visa37idtipologia=' . $visa37idtipologia . '';
			$sSQL = 'SELECT * FROM visa37convsubtipo WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2937[$k]] != $svr2937[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2937[$k] . '="' . $svr2937[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE visa37convsubtipo SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE visa37convsubtipo SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2937 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Subtipologias de convocatorias}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa37id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa37id, $sDebug);
}
function f2937_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2937;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2937 = 'lg/lg_2937_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2937)) {
		$mensajes_2937 = 'lg/lg_2937_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2937;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa37idtipologia = numeros_validar($aParametros[1]);
	$visa37id = numeros_validar($aParametros[2]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2937';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa37id . ' LIMIT 0, 1';
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
		//acciones previas
		$sWhere = 'visa37id=' . $visa37id . '';
		//$sWhere = 'visa37idtipologia=' . $visa37idtipologia . '';
		$sSQL = 'DELETE FROM visa37convsubtipo WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2937 Subtipologias de convocatorias}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa37id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2937_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2937 = 'lg/lg_2937_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2937)) {
		$mensajes_2937 = 'lg/lg_2937_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2937;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
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
	for ($k = 103; $k <= 102; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	$idTercero = numeros_validar($aParametros[100]);
	$sDebug = '';
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$visa36id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM visa36convtipologia WHERE visa36id=' . $visa36id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2937" name="paginaf2937" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2937" name="lppf2937" type="hidden" value="' . $lineastabla . '"/>';
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
	$sSQLadd = '1';
	$sSQLadd1 = '';
	/*
	if ((int)$aParametros[103] != -1) {
		$sSQLadd = $sSQLadd . ' AND TB.campo=' . $aParametros[103];
	}
	if ($aParametros[103] != '') {
		$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[103] . '%"';
	}
	*/
	/*
	if ($bNombre != '') {
		$sBase = mb_strtoupper($bNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'T1.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	$sTitulos = 'Id, Nombre, Activo';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa37id, TB.visa37nombre, TB.visa37activo';
	$sConsulta = 'FROM visa37convsubtipo AS TB 
	WHERE ' . $sSQLadd1 . '  ' . $sSQLadd . '';
	$sOrden = 'ORDER BY ';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2937" name="consulta_2937" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2937" name="titulos_2937" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2937: ' . $sSQL . '');
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			if ($registros == 0) {
				return array($sErrConsulta . $sBotones, $sDebug);
			}
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
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--secondary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa37nombre'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa37activo'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2937', $registros, $lineastabla, $pagina, 'paginarf2937()') . '';
	$res = $res . '' . html_lpp('lppf2937', $lineastabla, 'paginarf2937()') . '';
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
		$et_visa37nombre = $sPrefijo . cadena_notildes($filadet['visa37nombre']) . $sSufijo;
		$et_visa37activo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa37activo'] == 0) {
			$et_visa37activo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2937(' . $filadet['visa37id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa37nombre . '</td>';
		$res = $res . '<td>' . $et_visa37activo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2937 Subtipologias de convocatorias XAJAX 
function f2937_Guardar($valores, $aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$bHayDb = false;
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	if (isset($opts[100]) == 0) {
		$opts[100] = 0;
	}
	/*
	if (!is_array($valores)) {
		$datos = json_decode(str_replace('\"', '"', $valores), true);
	}
	if (isset($datos[0]) == 0) {
		$datos[0] = '';
	}
	if ($datos[0] == '') {
		$sError = $ERR[''];
	}
	*/
	$idTercero = numeros_validar($opts[100]);
	if ($sError == '') {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		list($sError, $iAccion, $visa37id, $sDebugGuardar) = f2937_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2937_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2937detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2937(' . $visa37id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2937');
		//}
		$objResponse->call("MensajeAlarmaV2('" . $ETI['msg_itemguardado'] . "', 1)");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0)");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2937_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2937 = 'lg/lg_2937_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2937)) {
		$mensajes_2937 = 'lg/lg_2937_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2937;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa37idtipologia = numeros_validar($aParametros[1]);
		if (($visa37idtipologia != '')) {
			$besta = true;
		}
	} else {
		$visa37id = $aParametros[103];
		if ((int)$visa37id != 0) {
			$besta = true;
		}
	}
	if ($besta) {
		$besta = false;
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'visa37idtipologia=' . $visa37idtipologia . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa37id=' . $visa37id . '';
		}
		$sSQL = 'SELECT * FROM visa37convsubtipo WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 2);
		$visa37id_nombre = '';
		$html_visa37id = html_oculto('visa37id', $fila['visa37id'], $visa37id_nombre);
		$objResponse->assign('div_visa37id', 'innerHTML', $html_visa37id);
		$objResponse->assign('visa37nombre', 'value', cadena_LimpiarXAJAX($fila['visa37nombre']));
		$objResponse->assign('visa37activo', 'value', $fila['visa37activo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2937', 'block')");
	} else {
		if ($paso == 1) {
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa37id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2937_Eliminar($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$iTipoError = 0;
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
	if (isset($opts[100]) == 0) {
		$opts[100] = 0;
	}
	$idTercero = numeros_validar($opts[100]);
	/*
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	*/
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sError, $sDebugElimina) = f2937_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2937_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2937detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2937');
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
	$objResponse->call("MensajeAlarmaV2('" . $sError . "', " . $iTipoError . ")");
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	$objDB->CerrarConexion();
	return $objResponse;
}
function f2937_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2937_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2937detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2937_PintarLlaves($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	/*
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	*/
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2937 = 'lg/lg_2937_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2937)) {
		$mensajes_2937 = 'lg/lg_2937_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2937;
	$iPiel = iDefinirPiel($APP, 2);
	$html_visa37id = '<input id="visa37id" name="visa37id" type="hidden" value="" />';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa37id', 'innerHTML', $html_visa37id);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

