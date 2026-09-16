<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
--- 2962 Compromisos
*/
function f2962_NombreTabla() {
	return 'visa62compromiso';
}
function f2962_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2962)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2962 = 'lg/lg_2962_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2962)) {
		$mensajes_2962 = 'lg/lg_2962_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2962;
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
	$visa62idactividad = numeros_validar($valores[1]);
	$visa62consec = numeros_validar($valores[2]);
	$visa62id = numeros_validar($valores[3], true);
	$visa62descripcion = cadena_Validar(trim($valores[4]));
	$visa62idresponsable = numeros_validar($valores[5]);
	$visa62fechalimite = numeros_validar($valores[6]);
	$visa62estado = $valores[7];
	$visa62fechacumple = numeros_validar($valores[8]);
	$visa62observaciones = cadena_Validar(trim($valores[9]));
	$visa62fecharegistro = numeros_validar($valores[10]);
	/*
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa62observaciones == '') {
		$sError = $ERR['visa62observaciones'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($visa62fechacumple)) {
		//$visa62fechacumple = fecha_DiaMod();
		$sError = $ERR['visa62fechacumple'] . $sSepara . $sError;
	}
	if ($visa62estado == '') {
		$sError = $ERR['visa62estado'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($visa62fechalimite)) {
		//$visa62fechalimite = fecha_DiaMod();
		$sError = $ERR['visa62fechalimite'] . $sSepara . $sError;
	}
	if ($visa62idresponsable == 0) {
		$sError = $ERR['visa62idresponsable'] . $sSepara . $sError;
	}
	if ($visa62descripcion == '') {
		$sError = $ERR['visa62descripcion'] . $sSepara . $sError;
	}
	/*
	if ($visa62id == '') {
		$sError = $ERR['visa62id'] . $sSepara . $sError;
	}
	*/
	if ($visa62idactividad == '') {
		$sError = $ERR['visa62idactividad'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($visa62idresponsable, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$sNomTabla2962 = f2962_NombreTabla();
	if ($sError == '') {
		if ((int)$visa62id == 0) {
			if ((int)$visa62consec == 0) {
				$visa62consec = tabla_consecutivo($sNomTabla2962, 'visa62consec', 'visa62idactividad=' . $visa62idactividad . '', $objDB);
				if ($visa62consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa62consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla2962 . ' WHERE visa62idactividad=' . $visa62idactividad . ' AND visa62consec=' . $visa62consec . '';
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
				$visa62id = tabla_consecutivo($sNomTabla2962, 'visa62id', '', $objDB);
				if ($visa62id == -1) {
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
		//Si el campo visa62descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$visa62descripcion = str_replace('"', '\"', $visa62descripcion);
		$visa62descripcion = str_replace('"', '\"', $visa62descripcion);
		//Si el campo visa62observaciones permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$visa62observaciones = str_replace('"', '\"', $visa62observaciones);
		$visa62observaciones = str_replace('"', '\"', $visa62observaciones);
		if ($bInserta) {
			$sCampos2962 = 'visa62idactividad, visa62consec, visa62id, visa62descripcion, visa62idresponsable, 
			visa62fechalimite, visa62estado, visa62fechacumple, visa62observaciones, visa62fecharegistro';
			$sValores2962 = '' . $visa62idactividad . ', ' . $visa62consec . ', ' . $visa62id . ', "' . $visa62descripcion . '", ' . $visa62idresponsable . ', 
			' . $visa62fechalimite . ', ' . $visa62estado . ', ' . $visa62fechacumple . ', "' . $visa62observaciones . '", ' . $visa62fecharegistro . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla2962 . ' (' . $sCampos2962 . ') VALUES (' . cadena_codificar($sValores2962) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla2962 . ' (' . $sCampos2962 . ') VALUES (' . $sValores2962 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2962 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2962].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa62id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2962[1] = 'visa62descripcion';
			$scampo2962[2] = 'visa62idresponsable';
			$scampo2962[3] = 'visa62fechalimite';
			$scampo2962[4] = 'visa62estado';
			$scampo2962[5] = 'visa62fechacumple';
			$scampo2962[6] = 'visa62observaciones';
			$scampo2962[7] = 'visa62fecharegistro';
			$svr2962[1] = $visa62descripcion;
			$svr2962[2] = $visa62idresponsable;
			$svr2962[3] = $visa62fechalimite;
			$svr2962[4] = $visa62estado;
			$svr2962[5] = $visa62fechacumple;
			$svr2962[6] = $visa62observaciones;
			$svr2962[7] = $visa62fecharegistro;
			$iNumCampos = 7;
			$sWhere = 'visa62id=' . $visa62id . '';
			//$sWhere = 'visa62idactividad=' . $visa62idactividad . ' AND visa62consec=' . $visa62consec . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla2962 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2962[$k]] != $svr2962[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2962[$k] . '="' . $svr2962[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sNomTabla2962 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sNomTabla2962 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2962 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Compromisos}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa62id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa62id, $sDebug);
}
function f2962_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2962;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2962 = 'lg/lg_2962_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2962)) {
		$mensajes_2962 = 'lg/lg_2962_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2962;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa62idactividad = numeros_validar($aParametros[1]);
	$visa62consec = numeros_validar($aParametros[2]);
	$visa62id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2962';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa62id . ' LIMIT 0, 1';
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
		$sNomTabla2962 = f2962_NombreTabla();
		//acciones previas
		$sWhere = 'visa62id=' . $visa62id . '';
		//$sWhere = 'visa62idactividad=' . $visa62idactividad . ' AND visa62consec=' . $visa62consec . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla2962 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2962 Compromisos}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa62id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2962_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2962 = 'lg/lg_2962_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2962)) {
		$mensajes_2962 = 'lg/lg_2962_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2962;
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
	$sNomTabla2962 = f2962_NombreTabla();
	$sNomTabla2957 = f2957_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2962" name="paginaf2962" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2962" name="lppf2962" type="hidden" value="' . $lineastabla . '"/>';
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
	$avisa62estado = array('');
	$sSQL = 'SELECT unad96id, unad96nombre, unad96etiqueta FROM unad96estado WHERE unad96idmodulo=2957';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$et_estado = cadena_notildes($fila['unad96nombre']);
		if ($sIdioma != 'es') {
			$et_estado = Etiqueta_Valor(2957, $fila['unad96etiqueta'], $sIdioma, $objDB);
		}
		$avisa62estado[$fila['unad96id']] = $et_estado;
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
	$sTitulos = 'Actividad, Consec, Descripcion, Responsable, Fechalimite, Estado';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa62idactividad, TB.visa62consec, TB.visa62descripcion, T4.unad11razonsocial AS C4_nombre, TB.visa62fechalimite, 
	TB.visa62estado, TB.visa62idresponsable, T4.unad11tipodoc AS C4_td, T4.unad11doc AS C4_doc';
	$sConsulta = 'FROM ' . $sNomTabla2962 . ' AS TB, unad11terceros AS T4 
	WHERE ' . $sSQLadd1 . ' TB.visa62idactividad=' . $visa57id . ' AND TB.visa62idresponsable=T4.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa62consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2962" name="consulta_2962" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2962" name="titulos_2962" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2962: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['visa62consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa62descripcion'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['visa62idresponsable'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa62fechalimite'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa62estado'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2962', $registros, $lineastabla, $pagina, 'paginarf2962()') . '';
	$res = $res . '' . html_lpp('lppf2962', $lineastabla, 'paginarf2962()') . '';
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
		$et_visa62consec = $sPrefijo . $filadet['visa62consec'] . $sSufijo;
		$et_visa62descripcion = $sPrefijo . cadena_notildes($filadet['visa62descripcion']) . $sSufijo;
		$et_visa62idresponsable_doc = '';
		$et_visa62idresponsable_nombre = '';
		if ($filadet['visa62idresponsable'] != 0) {
			$et_visa62idresponsable_doc = $sPrefijo . $filadet['C4_td'] . ' ' . $filadet['C4_doc'] . $sSufijo;
			$et_visa62idresponsable_nombre = $sPrefijo . cadena_notildes($filadet['C4_nombre']) . $sSufijo;
		}
		$et_visa62fechalimite = '';
		if ($filadet['visa62fechalimite'] != 0) {
			$et_visa62fechalimite = $sPrefijo . fecha_desdenumero($filadet['visa62fechalimite']) . $sSufijo;
		}
		$et_visa62estado = $avisa62estado[$filadet['visa62estado']];
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2962(' . $filadet['visa62id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa62consec . '</td>';
		$res = $res . '<td>' . $et_visa62descripcion . '</td>';
		$res = $res . '<td>' . $et_visa62idresponsable_doc . '</td>';
		$res = $res . '<td>' . $et_visa62idresponsable_nombre . '</td>';
		$res = $res . '<td>' . $et_visa62fechalimite . '</td>';
		$res = $res . '<td>' . $et_visa62estado . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2962 Compromisos XAJAX 
function f2962_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $visa62id, $sDebugGuardar) = f2962_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2962_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2962detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2962(' . $visa62id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2962');
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
function f2962_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2962 = 'lg/lg_2962_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2962)) {
		$mensajes_2962 = 'lg/lg_2962_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2962;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa62idactividad = numeros_validar($aParametros[1]);
		$visa62consec = numeros_validar($aParametros[2]);
		if (($visa62idactividad != '') && ($visa62consec != '')) {
			$besta = true;
		}
	} else {
		$visa62id = $aParametros[103];
		if ((int)$visa62id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'visa62idactividad=' . $visa62idactividad . ' AND visa62consec=' . $visa62consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa62id=' . $visa62id . '';
		}
		$sNomTabla2962 = f2962_NombreTabla();
		$sSQL = 'SELECT * FROM ' . $sNomTabla2962 . ' WHERE ' . $sSQLcondi;
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
		$visa62idresponsable_id = (int)$fila['visa62idresponsable'];
		$visa62idresponsable_td = $APP->tipo_doc;
		$visa62idresponsable_doc = '';
		$visa62idresponsable_nombre = '';
		if ($visa62idresponsable_id != 0) {
			list($visa62idresponsable_nombre, $visa62idresponsable_id, $visa62idresponsable_td, $visa62idresponsable_doc) = html_tercero($visa62idresponsable_td, $visa62idresponsable_doc, $visa62idresponsable_id, 0, $objDB);
		}
		$visa62consec_nombre = '';
		$html_visa62consec = html_oculto('visa62consec', $fila['visa62consec'], $visa62consec_nombre);
		$objResponse->assign('div_visa62consec', 'innerHTML', $html_visa62consec);
		$visa62id_nombre = '';
		$html_visa62id = html_oculto('visa62id', $fila['visa62id'], $visa62id_nombre);
		$objResponse->assign('div_visa62id', 'innerHTML', $html_visa62id);
		$objResponse->assign('visa62descripcion', 'value', cadena_LimpiarXAJAX($fila['visa62descripcion']));
		$objResponse->assign('visa62idresponsable', 'value', $fila['visa62idresponsable']);
		$objResponse->assign('visa62idresponsable_td', 'value', $visa62idresponsable_td);
		$objResponse->assign('visa62idresponsable_doc', 'value', $visa62idresponsable_doc);
		$objResponse->assign('div_visa62idresponsable', 'innerHTML', $visa62idresponsable_nombre);
		$objResponse->assign('visa62fechalimite', 'value', $fila['visa62fechalimite']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['visa62fechalimite'], true);
		$objResponse->assign('visa62fechalimite_dia', 'value', $iDia);
		$objResponse->assign('visa62fechalimite_mes', 'value', $iMes);
		$objResponse->assign('visa62fechalimite_agno', 'value', $iAgno);
		$objResponse->assign('visa62estado', 'value', $fila['visa62estado']);
		$objResponse->assign('visa62fechacumple', 'value', $fila['visa62fechacumple']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['visa62fechacumple'], true);
		$objResponse->assign('visa62fechacumple_dia', 'value', $iDia);
		$objResponse->assign('visa62fechacumple_mes', 'value', $iMes);
		$objResponse->assign('visa62fechacumple_agno', 'value', $iAgno);
		$objResponse->assign('visa62observaciones', 'value', cadena_LimpiarXAJAX($fila['visa62observaciones']));
		$html_visa62fecharegistro = html_oculto('visa62fecharegistro', $fila['visa62fecharegistro'], fecha_desdenumero($fila['visa62fecharegistro']));
		$objResponse->assign('div_visa62fecharegistro', 'innerHTML', $html_visa62fecharegistro);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2962', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('visa62consec', 'value', $visa62consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa62id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2962_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2962_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2962_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2962detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2962');
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
function f2962_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2962_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2962detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2962_PintarLlaves($aParametros)
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
	$mensajes_2962 = 'lg/lg_2962_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2962)) {
		$mensajes_2962 = 'lg/lg_2962_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2962;
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$html_visa62consec = '<input id="visa62consec" name="visa62consec" type="text" value="" onchange="revisaf2962()" class="cuatro" />';
	$html_visa62id = '<input id="visa62id" name="visa62id" type="hidden" value="" />';
	$et_visa62fecharegistro = '00/00/0000';
	$html_visa62fecharegistro = html_oculto('visa62fecharegistro', 0, $et_visa62fecharegistro);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa62consec', 'innerHTML', $html_visa62consec);
	$objResponse->assign('div_visa62id', 'innerHTML', $html_visa62id);
	$objResponse->assign('div_visa62fecharegistro', 'innerHTML', $html_visa62fecharegistro);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

