<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
--- 2958 Resultados
*/
function f2958_NombreTabla() {
	return 'visa58resultado';
}
function f2958_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2958)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2958 = 'lg/lg_2958_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2958)) {
		$mensajes_2958 = 'lg/lg_2958_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2958;
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
	$visa58idactividad = numeros_validar($valores[1]);
	$visa58consec = numeros_validar($valores[2]);
	$visa58id = numeros_validar($valores[3], true);
	$visa58descripcion = cadena_Validar(trim($valores[4]));
	$visa58cumplimiento = numeros_validar($valores[5]);
	$visa58fecharegistro = numeros_validar($valores[6]);
	/*
	if ($visa58cumplimiento == '') {
		$visa58cumplimiento = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa58cumplimiento == '') {
		$sError = $ERR['visa58cumplimiento'] . $sSepara . $sError;
	}
	if ($visa58descripcion == '') {
		$sError = $ERR['visa58descripcion'] . $sSepara . $sError;
	}
	/*
	if ($visa58id == '') {
		$sError = $ERR['visa58id'] . $sSepara . $sError;
	}
	*/
	if ($visa58idactividad == '') {
		$sError = $ERR['visa58idactividad'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$sNomTabla2958 = f2958_NombreTabla();
	if ($sError == '') {
		if ((int)$visa58id == 0) {
			if ((int)$visa58consec == 0) {
				$visa58consec = tabla_consecutivo($sNomTabla2958, 'visa58consec', 'visa58idactividad=' . $visa58idactividad . '', $objDB);
				if ($visa58consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa58consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla2958 . ' WHERE visa58idactividad=' . $visa58idactividad . ' AND visa58consec=' . $visa58consec . '';
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
				$visa58id = tabla_consecutivo($sNomTabla2958, 'visa58id', '', $objDB);
				if ($visa58id == -1) {
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
		//Si el campo visa58descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$visa58descripcion = str_replace('"', '\"', $visa58descripcion);
		$visa58descripcion = str_replace('"', '\"', $visa58descripcion);
		if ($bInserta) {
			$sCampos2958 = 'visa58idactividad, visa58consec, visa58id, visa58descripcion, visa58cumplimiento, 
			visa58fecharegistro';
			$sValores2958 = '' . $visa58idactividad . ', ' . $visa58consec . ', ' . $visa58id . ', "' . $visa58descripcion . '", ' . $visa58cumplimiento . ', 
			' . $visa58fecharegistro . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla2958 . ' (' . $sCampos2958 . ') VALUES (' . cadena_codificar($sValores2958) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla2958 . ' (' . $sCampos2958 . ') VALUES (' . $sValores2958 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2958 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2958].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa58id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2958[1] = 'visa58descripcion';
			$scampo2958[2] = 'visa58cumplimiento';
			$scampo2958[3] = 'visa58fecharegistro';
			$svr2958[1] = $visa58descripcion;
			$svr2958[2] = $visa58cumplimiento;
			$svr2958[3] = $visa58fecharegistro;
			$iNumCampos = 3;
			$sWhere = 'visa58id=' . $visa58id . '';
			//$sWhere = 'visa58idactividad=' . $visa58idactividad . ' AND visa58consec=' . $visa58consec . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla2958 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2958[$k]] != $svr2958[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2958[$k] . '="' . $svr2958[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sNomTabla2958 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sNomTabla2958 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2958 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Resultados}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa58id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa58id, $sDebug);
}
function f2958_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2958;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2958 = 'lg/lg_2958_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2958)) {
		$mensajes_2958 = 'lg/lg_2958_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2958;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa58idactividad = numeros_validar($aParametros[1]);
	$visa58consec = numeros_validar($aParametros[2]);
	$visa58id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2958';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa58id . ' LIMIT 0, 1';
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
		$sNomTabla2958 = f2958_NombreTabla();
		//acciones previas
		$sWhere = 'visa58id=' . $visa58id . '';
		//$sWhere = 'visa58idactividad=' . $visa58idactividad . ' AND visa58consec=' . $visa58consec . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla2958 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2958 Resultados}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa58id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2958_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2958 = 'lg/lg_2958_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2958)) {
		$mensajes_2958 = 'lg/lg_2958_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2958;
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
	$visa57id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	$sNomTabla2958 = f2958_NombreTabla();
	$sNomTabla2957 = f2957_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2958" name="paginaf2958" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2958" name="lppf2958" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$bAbierta = false;
	$sSQL = 'SELECT visa57estado FROM ' . $sNomTabla2957 . ' WHERE visa57id=' . $visa57id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['visa57estado'] == 0) {
			$bAbierta = true;
		}
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
	$sTitulos = 'Actividad, Consec, Cumplimiento, Fecharegistro';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa58idactividad, TB.visa58consec, TB.visa58cumplimiento, TB.visa58fecharegistro';
	$sConsulta = 'FROM ' . $sNomTabla2958 . ' AS TB 
	WHERE ' . $sSQLadd1 . ' TB.visa58idactividad=' . $visa57id . ' ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa58consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2958" name="consulta_2958" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2958" name="titulos_2958" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2958: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['visa58consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa58cumplimiento'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa58fecharegistro'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2958', $registros, $lineastabla, $pagina, 'paginarf2958()') . '';
	$res = $res . '' . html_lpp('lppf2958', $lineastabla, 'paginarf2958()') . '';
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
		$et_visa58consec = $sPrefijo . $filadet['visa58consec'] . $sSufijo;
		$et_visa58cumplimiento = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa58cumplimiento'] == 0) {
			$et_visa58cumplimiento = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_visa58fecharegistro = '';
		if ($filadet['visa58fecharegistro'] != 0) {
			$et_visa58fecharegistro = $sPrefijo . fecha_desdenumero($filadet['visa58fecharegistro']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2958(' . $filadet['visa58id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa58consec . '</td>';
		$res = $res . '<td>' . $et_visa58cumplimiento . '</td>';
		$res = $res . '<td>' . $et_visa58fecharegistro . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2958 Resultados XAJAX 
function f2958_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $visa58id, $sDebugGuardar) = f2958_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2958_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2958detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2958(' . $visa58id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2958');
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
function f2958_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2958 = 'lg/lg_2958_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2958)) {
		$mensajes_2958 = 'lg/lg_2958_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2958;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa58idactividad = numeros_validar($aParametros[1]);
		$visa58consec = numeros_validar($aParametros[2]);
		if (($visa58idactividad != '') && ($visa58consec != '')) {
			$besta = true;
		}
	} else {
		$visa58id = $aParametros[103];
		if ((int)$visa58id != 0) {
			$besta = true;
		}
	}
	if ($besta) {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		$besta = false;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'visa58idactividad=' . $visa58idactividad . ' AND visa58consec=' . $visa58consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa58id=' . $visa58id . '';
		}
		$sNomTabla2958 = f2958_NombreTabla();
		$sSQL = 'SELECT * FROM ' . $sNomTabla2958 . ' WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 2);
		$visa58consec_nombre = '';
		$html_visa58consec = html_oculto('visa58consec', $fila['visa58consec'], $visa58consec_nombre);
		$objResponse->assign('div_visa58consec', 'innerHTML', $html_visa58consec);
		$visa58id_nombre = '';
		$html_visa58id = html_oculto('visa58id', $fila['visa58id'], $visa58id_nombre);
		$objResponse->assign('div_visa58id', 'innerHTML', $html_visa58id);
		$objResponse->assign('visa58descripcion', 'value', cadena_LimpiarXAJAX($fila['visa58descripcion']));
		$objResponse->assign('visa58cumplimiento', 'value', $fila['visa58cumplimiento']);
		$html_visa58fecharegistro = html_oculto('visa58fecharegistro', $fila['visa58fecharegistro'], fecha_desdenumero($fila['visa58fecharegistro']));
		$objResponse->assign('div_visa58fecharegistro', 'innerHTML', $html_visa58fecharegistro);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2958', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('visa58consec', 'value', $visa58consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa58id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2958_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2958_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2958_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2958detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2958');
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
function f2958_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2958_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2958detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2958_PintarLlaves($aParametros)
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
	$mensajes_2958 = 'lg/lg_2958_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2958)) {
		$mensajes_2958 = 'lg/lg_2958_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2958;
	$iPiel = iDefinirPiel($APP, 2);
	$html_visa58consec = '<input id="visa58consec" name="visa58consec" type="text" value="" onchange="revisaf2958()" class="cuatro" />';
	$html_visa58id = '<input id="visa58id" name="visa58id" type="hidden" value="" />';
	$et_visa58fecharegistro = '00/00/0000';
	$html_visa58fecharegistro = html_oculto('visa58fecharegistro', 0, $et_visa58fecharegistro);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa58consec', 'innerHTML', $html_visa58consec);
	$objResponse->assign('div_visa58id', 'innerHTML', $html_visa58id);
	$objResponse->assign('div_visa58fecharegistro', 'innerHTML', $html_visa58fecharegistro);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

