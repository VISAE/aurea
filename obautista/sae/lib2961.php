<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
--- 2961 Dificultades
*/
function f2961_NombreTabla() {
	return 'visa61dificultad';
}
function f2961_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2961)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2961 = 'lg/lg_2961_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2961)) {
		$mensajes_2961 = 'lg/lg_2961_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2961;
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
	$visa61idactividad = numeros_validar($valores[1]);
	$visa61consec = numeros_validar($valores[2]);
	$visa61id = numeros_validar($valores[3], true);
	$visa61descripcion = cadena_Validar(trim($valores[4]));
	$visa61impacto = numeros_validar($valores[5]);
	$visa61requiereapoyo = numeros_validar($valores[6]);
	$visa61fecharegistro = numeros_validar($valores[7]);
	/*
	if ($visa61impacto == '') {
		$visa61impacto = 0;
	}
	if ($visa61requiereapoyo == '') {
		$visa61requiereapoyo = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa61requiereapoyo == '') {
		$sError = $ERR['visa61requiereapoyo'] . $sSepara . $sError;
	}
	if ($visa61impacto == '') {
		$sError = $ERR['visa61impacto'] . $sSepara . $sError;
	}
	if ($visa61descripcion == '') {
		$sError = $ERR['visa61descripcion'] . $sSepara . $sError;
	}
	/*
	if ($visa61id == '') {
		$sError = $ERR['visa61id'] . $sSepara . $sError;
	}
	*/
	if ($visa61idactividad == '') {
		$sError = $ERR['visa61idactividad'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$sNomTabla2961 = f2961_NombreTabla();
	if ($sError == '') {
		if ((int)$visa61id == 0) {
			if ((int)$visa61consec == 0) {
				$visa61consec = tabla_consecutivo($sNomTabla2961, 'visa61consec', 'visa61idactividad=' . $visa61idactividad . '', $objDB);
				if ($visa61consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa61consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla2961 . ' WHERE visa61idactividad=' . $visa61idactividad . ' AND visa61consec=' . $visa61consec . '';
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
				$visa61id = tabla_consecutivo($sNomTabla2961, 'visa61id', '', $objDB);
				if ($visa61id == -1) {
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
		//Si el campo visa61descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$visa61descripcion = str_replace('"', '\"', $visa61descripcion);
		$visa61descripcion = str_replace('"', '\"', $visa61descripcion);
		if ($bInserta) {
			$sCampos2961 = 'visa61idactividad, visa61consec, visa61id, visa61descripcion, visa61impacto, 
			visa61requiereapoyo, visa61fecharegistro';
			$sValores2961 = '' . $visa61idactividad . ', ' . $visa61consec . ', ' . $visa61id . ', "' . $visa61descripcion . '", ' . $visa61impacto . ', 
			' . $visa61requiereapoyo . ', ' . $visa61fecharegistro . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla2961 . ' (' . $sCampos2961 . ') VALUES (' . cadena_codificar($sValores2961) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla2961 . ' (' . $sCampos2961 . ') VALUES (' . $sValores2961 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2961 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2961].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa61id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2961[1] = 'visa61descripcion';
			$scampo2961[2] = 'visa61impacto';
			$scampo2961[3] = 'visa61requiereapoyo';
			$scampo2961[4] = 'visa61fecharegistro';
			$svr2961[1] = $visa61descripcion;
			$svr2961[2] = $visa61impacto;
			$svr2961[3] = $visa61requiereapoyo;
			$svr2961[4] = $visa61fecharegistro;
			$iNumCampos = 4;
			$sWhere = 'visa61id=' . $visa61id . '';
			//$sWhere = 'visa61idactividad=' . $visa61idactividad . ' AND visa61consec=' . $visa61consec . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla2961 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2961[$k]] != $svr2961[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2961[$k] . '="' . $svr2961[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sNomTabla2961 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sNomTabla2961 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2961 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Dificultades}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa61id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa61id, $sDebug);
}
function f2961_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2961;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2961 = 'lg/lg_2961_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2961)) {
		$mensajes_2961 = 'lg/lg_2961_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2961;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa61idactividad = numeros_validar($aParametros[1]);
	$visa61consec = numeros_validar($aParametros[2]);
	$visa61id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2961';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa61id . ' LIMIT 0, 1';
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
		$sNomTabla2961 = f2961_NombreTabla();
		//acciones previas
		$sWhere = 'visa61id=' . $visa61id . '';
		//$sWhere = 'visa61idactividad=' . $visa61idactividad . ' AND visa61consec=' . $visa61consec . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla2961 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2961 Dificultades}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa61id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2961_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2961 = 'lg/lg_2961_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2961)) {
		$mensajes_2961 = 'lg/lg_2961_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2961;
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
	$sNomTabla2961 = f2961_NombreTabla();
	$sNomTabla2957 = f2957_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2961" name="paginaf2961" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2961" name="lppf2961" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Actividad, Consec, Impacto, Requiereapoyo, Fecharegistro';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa61idactividad, TB.visa61consec, TB.visa61impacto, TB.visa61requiereapoyo, TB.visa61fecharegistro';
	$sConsulta = 'FROM ' . $sNomTabla2961 . ' AS TB 
	WHERE ' . $sSQLadd1 . ' TB.visa61idactividad=' . $visa57id . ' ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa61consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2961" name="consulta_2961" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2961" name="titulos_2961" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2961: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['visa61consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa61impacto'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa61requiereapoyo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa61fecharegistro'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2961', $registros, $lineastabla, $pagina, 'paginarf2961()') . '';
	$res = $res . '' . html_lpp('lppf2961', $lineastabla, 'paginarf2961()') . '';
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
		$et_visa61consec = $sPrefijo . $filadet['visa61consec'] . $sSufijo;
		$et_visa61impacto = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa61impacto'] == 0) {
			$et_visa61impacto = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_visa61requiereapoyo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa61requiereapoyo'] == 0) {
			$et_visa61requiereapoyo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_visa61fecharegistro = '';
		if ($filadet['visa61fecharegistro'] != 0) {
			$et_visa61fecharegistro = $sPrefijo . fecha_desdenumero($filadet['visa61fecharegistro']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2961(' . $filadet['visa61id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa61consec . '</td>';
		$res = $res . '<td>' . $et_visa61impacto . '</td>';
		$res = $res . '<td>' . $et_visa61requiereapoyo . '</td>';
		$res = $res . '<td>' . $et_visa61fecharegistro . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2961 Dificultades XAJAX 
function f2961_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $visa61id, $sDebugGuardar) = f2961_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2961_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2961detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2961(' . $visa61id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2961');
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
function f2961_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2961 = 'lg/lg_2961_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2961)) {
		$mensajes_2961 = 'lg/lg_2961_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2961;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa61idactividad = numeros_validar($aParametros[1]);
		$visa61consec = numeros_validar($aParametros[2]);
		if (($visa61idactividad != '') && ($visa61consec != '')) {
			$besta = true;
		}
	} else {
		$visa61id = $aParametros[103];
		if ((int)$visa61id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'visa61idactividad=' . $visa61idactividad . ' AND visa61consec=' . $visa61consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa61id=' . $visa61id . '';
		}
		$sNomTabla2961 = f2961_NombreTabla();
		$sSQL = 'SELECT * FROM ' . $sNomTabla2961 . ' WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 2);
		$visa61consec_nombre = '';
		$html_visa61consec = html_oculto('visa61consec', $fila['visa61consec'], $visa61consec_nombre);
		$objResponse->assign('div_visa61consec', 'innerHTML', $html_visa61consec);
		$visa61id_nombre = '';
		$html_visa61id = html_oculto('visa61id', $fila['visa61id'], $visa61id_nombre);
		$objResponse->assign('div_visa61id', 'innerHTML', $html_visa61id);
		$objResponse->assign('visa61descripcion', 'value', cadena_LimpiarXAJAX($fila['visa61descripcion']));
		$objResponse->assign('visa61impacto', 'value', $fila['visa61impacto']);
		$objResponse->assign('visa61requiereapoyo', 'value', $fila['visa61requiereapoyo']);
		$html_visa61fecharegistro = html_oculto('visa61fecharegistro', $fila['visa61fecharegistro'], fecha_desdenumero($fila['visa61fecharegistro']));
		$objResponse->assign('div_visa61fecharegistro', 'innerHTML', $html_visa61fecharegistro);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2961', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('visa61consec', 'value', $visa61consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa61id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2961_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2961_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2961_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2961detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2961');
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
function f2961_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2961_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2961detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2961_PintarLlaves($aParametros)
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
	$mensajes_2961 = 'lg/lg_2961_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2961)) {
		$mensajes_2961 = 'lg/lg_2961_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2961;
	$iPiel = iDefinirPiel($APP, 2);
	$html_visa61consec = '<input id="visa61consec" name="visa61consec" type="text" value="" onchange="revisaf2961()" class="cuatro" />';
	$html_visa61id = '<input id="visa61id" name="visa61id" type="hidden" value="" />';
	$et_visa61fecharegistro = '00/00/0000';
	$html_visa61fecharegistro = html_oculto('visa61fecharegistro', 0, $et_visa61fecharegistro);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa61consec', 'innerHTML', $html_visa61consec);
	$objResponse->assign('div_visa61id', 'innerHTML', $html_visa61id);
	$objResponse->assign('div_visa61fecharegistro', 'innerHTML', $html_visa61fecharegistro);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

