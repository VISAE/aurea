<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
--- 2963 Solicitud de apoyo
*/
function f2963_NombreTabla() {
	return 'visa63solicitaapoyo';
}
function f2963_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2963)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2963 = 'lg/lg_2963_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2963)) {
		$mensajes_2963 = 'lg/lg_2963_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2963;
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
	$visa63idactividad = numeros_validar($valores[1]);
	$visa63consec = numeros_validar($valores[2]);
	$visa63id = numeros_validar($valores[3], true);
	$visa63descripcion = cadena_Validar(trim($valores[4]));
	$visa63idcolaborador = numeros_validar($valores[5]);
	$visa63estado = $valores[6];
	$visa63fechasolicitud = numeros_validar($valores[7]);
	$visa63fecharespuesta = numeros_validar($valores[8]);
	$visa63observaciones = cadena_Validar(trim($valores[9]));
	/*
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa63observaciones == '') {
		$sError = $ERR['visa63observaciones'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($visa63fecharespuesta)) {
		//$visa63fecharespuesta = fecha_DiaMod();
		$sError = $ERR['visa63fecharespuesta'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($visa63fechasolicitud)) {
		//$visa63fechasolicitud = fecha_DiaMod();
		$sError = $ERR['visa63fechasolicitud'] . $sSepara . $sError;
	}
	if ($visa63estado == '') {
		$sError = $ERR['visa63estado'] . $sSepara . $sError;
	}
	if ($visa63idcolaborador == 0) {
		$sError = $ERR['visa63idcolaborador'] . $sSepara . $sError;
	}
	if ($visa63descripcion == '') {
		$sError = $ERR['visa63descripcion'] . $sSepara . $sError;
	}
	/*
	if ($visa63id == '') {
		$sError = $ERR['visa63id'] . $sSepara . $sError;
	}
	*/
	if ($visa63idactividad == '') {
		$sError = $ERR['visa63idactividad'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($visa63idcolaborador, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$sNomTabla2963 = f2963_NombreTabla();
	if ($sError == '') {
		if ((int)$visa63id == 0) {
			if ((int)$visa63consec == 0) {
				$visa63consec = tabla_consecutivo($sNomTabla2963, 'visa63consec', 'visa63idactividad=' . $visa63idactividad . '', $objDB);
				if ($visa63consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa63consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla2963 . ' WHERE visa63idactividad=' . $visa63idactividad . ' AND visa63consec=' . $visa63consec . '';
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
				$visa63id = tabla_consecutivo($sNomTabla2963, 'visa63id', '', $objDB);
				if ($visa63id == -1) {
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
		//Si el campo visa63descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$visa63descripcion = str_replace('"', '\"', $visa63descripcion);
		$visa63descripcion = str_replace('"', '\"', $visa63descripcion);
		//Si el campo visa63observaciones permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$visa63observaciones = str_replace('"', '\"', $visa63observaciones);
		$visa63observaciones = str_replace('"', '\"', $visa63observaciones);
		if ($bInserta) {
			$sCampos2963 = 'visa63idactividad, visa63consec, visa63id, visa63descripcion, visa63idcolaborador, 
			visa63estado, visa63fechasolicitud, visa63fecharespuesta, visa63observaciones';
			$sValores2963 = '' . $visa63idactividad . ', ' . $visa63consec . ', ' . $visa63id . ', "' . $visa63descripcion . '", ' . $visa63idcolaborador . ', 
			' . $visa63estado . ', ' . $visa63fechasolicitud . ', ' . $visa63fecharespuesta . ', "' . $visa63observaciones . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla2963 . ' (' . $sCampos2963 . ') VALUES (' . cadena_codificar($sValores2963) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla2963 . ' (' . $sCampos2963 . ') VALUES (' . $sValores2963 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2963 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2963].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa63id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2963[1] = 'visa63descripcion';
			$scampo2963[2] = 'visa63idcolaborador';
			$scampo2963[3] = 'visa63estado';
			$scampo2963[4] = 'visa63fechasolicitud';
			$scampo2963[5] = 'visa63fecharespuesta';
			$scampo2963[6] = 'visa63observaciones';
			$svr2963[1] = $visa63descripcion;
			$svr2963[2] = $visa63idcolaborador;
			$svr2963[3] = $visa63estado;
			$svr2963[4] = $visa63fechasolicitud;
			$svr2963[5] = $visa63fecharespuesta;
			$svr2963[6] = $visa63observaciones;
			$iNumCampos = 6;
			$sWhere = 'visa63id=' . $visa63id . '';
			//$sWhere = 'visa63idactividad=' . $visa63idactividad . ' AND visa63consec=' . $visa63consec . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla2963 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2963[$k]] != $svr2963[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2963[$k] . '="' . $svr2963[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sNomTabla2963 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sNomTabla2963 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2963 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Solicitud de apoyo}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa63id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa63id, $sDebug);
}
function f2963_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2963;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2963 = 'lg/lg_2963_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2963)) {
		$mensajes_2963 = 'lg/lg_2963_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2963;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa63idactividad = numeros_validar($aParametros[1]);
	$visa63consec = numeros_validar($aParametros[2]);
	$visa63id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2963';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa63id . ' LIMIT 0, 1';
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
		$sNomTabla2963 = f2963_NombreTabla();
		//acciones previas
		$sWhere = 'visa63id=' . $visa63id . '';
		//$sWhere = 'visa63idactividad=' . $visa63idactividad . ' AND visa63consec=' . $visa63consec . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla2963 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2963 Solicitud de apoyo}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa63id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2963_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2963 = 'lg/lg_2963_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2963)) {
		$mensajes_2963 = 'lg/lg_2963_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2963;
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
	$sNomTabla2963 = f2963_NombreTabla();
	$sNomTabla2957 = f2957_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2963" name="paginaf2963" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2963" name="lppf2963" type="hidden" value="' . $lineastabla . '"/>';
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
	$avisa63estado = array('');
	$sSQL = 'SELECT unad96id, unad96nombre, unad96etiqueta FROM unad96estado WHERE unad96idmodulo=2957';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$et_estado = cadena_notildes($fila['unad96nombre']);
		if ($sIdioma != 'es') {
			$et_estado = Etiqueta_Valor(2957, $fila['unad96etiqueta'], $sIdioma, $objDB);
		}
		$avisa63estado[$fila['unad96id']] = $et_estado;
	}
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
	$sTitulos = 'Actividad, Consec, Colaborador, Estado, Fechasolicitud';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa63idactividad, TB.visa63consec, T3.unad11razonsocial AS C3_nombre, TB.visa63estado, TB.visa63fechasolicitud, TB.visa63idcolaborador, T3.unad11tipodoc AS C3_td, T3.unad11doc AS C3_doc';
	$sConsulta = 'FROM ' . $sNomTabla2963 . ' AS TB, unad11terceros AS T3 
	WHERE ' . $sSQLadd1 . ' TB.visa63idactividad=' . $visa57id . ' AND TB.visa63idcolaborador=T3.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa63consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2963" name="consulta_2963" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2963" name="titulos_2963" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2963: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['visa63consec'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['visa63idcolaborador'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa63estado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa63fechasolicitud'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2963', $registros, $lineastabla, $pagina, 'paginarf2963()') . '';
	$res = $res . '' . html_lpp('lppf2963', $lineastabla, 'paginarf2963()') . '';
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
		$et_visa63consec = $sPrefijo . $filadet['visa63consec'] . $sSufijo;
		$et_visa63idcolaborador_doc = '';
		$et_visa63idcolaborador_nombre = '';
		if ($filadet['visa63idcolaborador'] != 0) {
			$et_visa63idcolaborador_doc = $sPrefijo . $filadet['C3_td'] . ' ' . $filadet['C3_doc'] . $sSufijo;
			$et_visa63idcolaborador_nombre = $sPrefijo . cadena_notildes($filadet['C3_nombre']) . $sSufijo;
		}
		$et_visa63estado = $avisa63estado[$filadet['visa63estado']];
		$et_visa63fechasolicitud = '';
		if ($filadet['visa63fechasolicitud'] != 0) {
			$et_visa63fechasolicitud = $sPrefijo . fecha_desdenumero($filadet['visa63fechasolicitud']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2963(' . $filadet['visa63id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa63consec . '</td>';
		$res = $res . '<td>' . $et_visa63idcolaborador_doc . '</td>';
		$res = $res . '<td>' . $et_visa63idcolaborador_nombre . '</td>';
		$res = $res . '<td>' . $et_visa63estado . '</td>';
		$res = $res . '<td>' . $et_visa63fechasolicitud . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2963 Solicitud de apoyo XAJAX 
function f2963_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $visa63id, $sDebugGuardar) = f2963_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2963_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2963detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2963(' . $visa63id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2963');
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
function f2963_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2963 = 'lg/lg_2963_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2963)) {
		$mensajes_2963 = 'lg/lg_2963_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2963;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa63idactividad = numeros_validar($aParametros[1]);
		$visa63consec = numeros_validar($aParametros[2]);
		if (($visa63idactividad != '') && ($visa63consec != '')) {
			$besta = true;
		}
	} else {
		$visa63id = $aParametros[103];
		if ((int)$visa63id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'visa63idactividad=' . $visa63idactividad . ' AND visa63consec=' . $visa63consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa63id=' . $visa63id . '';
		}
		$sNomTabla2963 = f2963_NombreTabla();
		$sSQL = 'SELECT * FROM ' . $sNomTabla2963 . ' WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 2);
		$objCombos = new clsHtmlCombos();
		$visa63idcolaborador_id = (int)$fila['visa63idcolaborador'];
		$visa63idcolaborador_td = $APP->tipo_doc;
		$visa63idcolaborador_doc = '';
		$visa63idcolaborador_nombre = '';
		if ($visa63idcolaborador_id != 0) {
			list($visa63idcolaborador_nombre, $visa63idcolaborador_id, $visa63idcolaborador_td, $visa63idcolaborador_doc) = html_tercero($visa63idcolaborador_td, $visa63idcolaborador_doc, $visa63idcolaborador_id, 0, $objDB);
		}
		$visa63consec_nombre = '';
		$html_visa63consec = html_oculto('visa63consec', $fila['visa63consec'], $visa63consec_nombre);
		$objResponse->assign('div_visa63consec', 'innerHTML', $html_visa63consec);
		$visa63id_nombre = '';
		$html_visa63id = html_oculto('visa63id', $fila['visa63id'], $visa63id_nombre);
		$objResponse->assign('div_visa63id', 'innerHTML', $html_visa63id);
		$objResponse->assign('visa63descripcion', 'value', cadena_LimpiarXAJAX($fila['visa63descripcion']));
		$objResponse->assign('visa63idcolaborador', 'value', $fila['visa63idcolaborador']);
		$objResponse->assign('visa63idcolaborador_td', 'value', $visa63idcolaborador_td);
		$objResponse->assign('visa63idcolaborador_doc', 'value', $visa63idcolaborador_doc);
		$objResponse->assign('div_visa63idcolaborador', 'innerHTML', $visa63idcolaborador_nombre);
		$objResponse->assign('visa63estado', 'value', $fila['visa63estado']);
		$objResponse->assign('visa63fechasolicitud', 'value', $fila['visa63fechasolicitud']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['visa63fechasolicitud'], true);
		$objResponse->assign('visa63fechasolicitud_dia', 'value', $iDia);
		$objResponse->assign('visa63fechasolicitud_mes', 'value', $iMes);
		$objResponse->assign('visa63fechasolicitud_agno', 'value', $iAgno);
		$objResponse->assign('visa63fecharespuesta', 'value', $fila['visa63fecharespuesta']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['visa63fecharespuesta'], true);
		$objResponse->assign('visa63fecharespuesta_dia', 'value', $iDia);
		$objResponse->assign('visa63fecharespuesta_mes', 'value', $iMes);
		$objResponse->assign('visa63fecharespuesta_agno', 'value', $iAgno);
		$objResponse->assign('visa63observaciones', 'value', cadena_LimpiarXAJAX($fila['visa63observaciones']));
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2963', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('visa63consec', 'value', $visa63consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa63id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2963_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2963_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2963_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2963detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2963');
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
function f2963_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2963_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2963detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2963_PintarLlaves($aParametros)
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
	$mensajes_2963 = 'lg/lg_2963_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2963)) {
		$mensajes_2963 = 'lg/lg_2963_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2963;
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$html_visa63consec = '<input id="visa63consec" name="visa63consec" type="text" value="" onchange="revisaf2963()" class="cuatro" />';
	$html_visa63id = '<input id="visa63id" name="visa63id" type="hidden" value="" />';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa63consec', 'innerHTML', $html_visa63consec);
	$objResponse->assign('div_visa63id', 'innerHTML', $html_visa63id);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

