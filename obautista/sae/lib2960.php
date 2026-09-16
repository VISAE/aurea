<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
--- 2960 Reprogramación
*/
function f2960_NombreTabla() {
	return 'visa60reprograma';
}
function f2960_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2960)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2960 = 'lg/lg_2960_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2960)) {
		$mensajes_2960 = 'lg/lg_2960_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2960;
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
	$visa60idactividad = numeros_validar($valores[1]);
	$visa60consec = numeros_validar($valores[2]);
	$visa60id = numeros_validar($valores[3], true);
	$visa60fechareproini = numeros_validar($valores[4]);
	$visa60fechareprofin = numeros_validar($valores[5]);
	$visa60motivo = cadena_Validar(trim($valores[6]));
	$visa60fecharegistro = numeros_validar($valores[7]);
	$visa60idusuario = numeros_validar($valores[8]);
	/*
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa60idusuario == 0) {
		$sError = $ERR['visa60idusuario'] . $sSepara . $sError;
	}
	if ($visa60motivo == '') {
		$sError = $ERR['visa60motivo'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($visa60fechareprofin)) {
		//$visa60fechareprofin = fecha_DiaMod();
		$sError = $ERR['visa60fechareprofin'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($visa60fechareproini)) {
		//$visa60fechareproini = fecha_DiaMod();
		$sError = $ERR['visa60fechareproini'] . $sSepara . $sError;
	}
	/*
	if ($visa60id == '') {
		$sError = $ERR['visa60id'] . $sSepara . $sError;
	}
	*/
	if ($visa60idactividad == '') {
		$sError = $ERR['visa60idactividad'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($visa60idusuario, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$sNomTabla2960 = f2960_NombreTabla();
	if ($sError == '') {
		if ((int)$visa60id == 0) {
			if ((int)$visa60consec == 0) {
				$visa60consec = tabla_consecutivo($sNomTabla2960, 'visa60consec', 'visa60idactividad=' . $visa60idactividad . '', $objDB);
				if ($visa60consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa60consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla2960 . ' WHERE visa60idactividad=' . $visa60idactividad . ' AND visa60consec=' . $visa60consec . '';
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
				$visa60id = tabla_consecutivo($sNomTabla2960, 'visa60id', '', $objDB);
				if ($visa60id == -1) {
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
		//Si el campo visa60motivo permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$visa60motivo = str_replace('"', '\"', $visa60motivo);
		$visa60motivo = str_replace('"', '\"', $visa60motivo);
		if ($bInserta) {
			$sCampos2960 = 'visa60idactividad, visa60consec, visa60id, visa60fechareproini, visa60fechareprofin, 
			visa60motivo, visa60fecharegistro, visa60idusuario';
			$sValores2960 = '' . $visa60idactividad . ', ' . $visa60consec . ', ' . $visa60id . ', ' . $visa60fechareproini . ', ' . $visa60fechareprofin . ', 
			"' . $visa60motivo . '", ' . $visa60fecharegistro . ', ' . $visa60idusuario . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla2960 . ' (' . $sCampos2960 . ') VALUES (' . cadena_codificar($sValores2960) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla2960 . ' (' . $sCampos2960 . ') VALUES (' . $sValores2960 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2960 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2960].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa60id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2960[1] = 'visa60fechareproini';
			$scampo2960[2] = 'visa60fechareprofin';
			$scampo2960[3] = 'visa60motivo';
			$scampo2960[4] = 'visa60fecharegistro';
			$scampo2960[5] = 'visa60idusuario';
			$svr2960[1] = $visa60fechareproini;
			$svr2960[2] = $visa60fechareprofin;
			$svr2960[3] = $visa60motivo;
			$svr2960[4] = $visa60fecharegistro;
			$svr2960[5] = $visa60idusuario;
			$iNumCampos = 5;
			$sWhere = 'visa60id=' . $visa60id . '';
			//$sWhere = 'visa60idactividad=' . $visa60idactividad . ' AND visa60consec=' . $visa60consec . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla2960 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2960[$k]] != $svr2960[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2960[$k] . '="' . $svr2960[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sNomTabla2960 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sNomTabla2960 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2960 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Reprogramación}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa60id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa60id, $sDebug);
}
function f2960_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2960;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2960 = 'lg/lg_2960_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2960)) {
		$mensajes_2960 = 'lg/lg_2960_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2960;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa60idactividad = numeros_validar($aParametros[1]);
	$visa60consec = numeros_validar($aParametros[2]);
	$visa60id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2960';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa60id . ' LIMIT 0, 1';
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
		$sNomTabla2960 = f2960_NombreTabla();
		//acciones previas
		$sWhere = 'visa60id=' . $visa60id . '';
		//$sWhere = 'visa60idactividad=' . $visa60idactividad . ' AND visa60consec=' . $visa60consec . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla2960 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2960 Reprogramación}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa60id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2960_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2960 = 'lg/lg_2960_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2960)) {
		$mensajes_2960 = 'lg/lg_2960_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2960;
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
	$sNomTabla2960 = f2960_NombreTabla();
	$sNomTabla2957 = f2957_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2960" name="paginaf2960" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2960" name="lppf2960" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Actividad, Consec, Motivo, Fechareproini, Fechareprofin, Usuario';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa60idactividad, TB.visa60consec, TB.visa60motivo, TB.visa60fechareproini, TB.visa60fechareprofin, 
	T6.unad11razonsocial AS C6_nombre, TB.visa60idusuario, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc';
	$sConsulta = 'FROM ' . $sNomTabla2960 . ' AS TB, unad11terceros AS T6 
	WHERE ' . $sSQLadd1 . ' TB.visa60idactividad=' . $visa57id . ' AND TB.visa60idusuario=T6.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa60consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2960" name="consulta_2960" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2960" name="titulos_2960" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2960: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['visa60consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa60motivo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa60fechareproini'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa60fechareprofin'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['visa60idusuario'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2960', $registros, $lineastabla, $pagina, 'paginarf2960()') . '';
	$res = $res . '' . html_lpp('lppf2960', $lineastabla, 'paginarf2960()') . '';
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
		$et_visa60consec = $sPrefijo . $filadet['visa60consec'] . $sSufijo;
		$et_visa60motivo = $sPrefijo . cadena_notildes($filadet['visa60motivo']) . $sSufijo;
		$et_visa60fechareproini = '';
		if ($filadet['visa60fechareproini'] != 0) {
			$et_visa60fechareproini = $sPrefijo . fecha_desdenumero($filadet['visa60fechareproini']) . $sSufijo;
		}
		$et_visa60fechareprofin = '';
		if ($filadet['visa60fechareprofin'] != 0) {
			$et_visa60fechareprofin = $sPrefijo . fecha_desdenumero($filadet['visa60fechareprofin']) . $sSufijo;
		}
		$et_visa60idusuario_doc = '';
		$et_visa60idusuario_nombre = '';
		if ($filadet['visa60idusuario'] != 0) {
			$et_visa60idusuario_doc = $sPrefijo . $filadet['C6_td'] . ' ' . $filadet['C6_doc'] . $sSufijo;
			$et_visa60idusuario_nombre = $sPrefijo . cadena_notildes($filadet['C6_nombre']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2960(' . $filadet['visa60id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa60consec . '</td>';
		$res = $res . '<td>' . $et_visa60motivo . '</td>';
		$res = $res . '<td>' . $et_visa60fechareproini . '</td>';
		$res = $res . '<td>' . $et_visa60fechareprofin . '</td>';
		$res = $res . '<td>' . $et_visa60idusuario_doc . '</td>';
		$res = $res . '<td>' . $et_visa60idusuario_nombre . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2960 Reprogramación XAJAX 
function f2960_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $visa60id, $sDebugGuardar) = f2960_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2960_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2960detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2960(' . $visa60id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2960');
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
function f2960_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2960 = 'lg/lg_2960_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2960)) {
		$mensajes_2960 = 'lg/lg_2960_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2960;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa60idactividad = numeros_validar($aParametros[1]);
		$visa60consec = numeros_validar($aParametros[2]);
		if (($visa60idactividad != '') && ($visa60consec != '')) {
			$besta = true;
		}
	} else {
		$visa60id = $aParametros[103];
		if ((int)$visa60id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'visa60idactividad=' . $visa60idactividad . ' AND visa60consec=' . $visa60consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa60id=' . $visa60id . '';
		}
		$sNomTabla2960 = f2960_NombreTabla();
		$sSQL = 'SELECT * FROM ' . $sNomTabla2960 . ' WHERE ' . $sSQLcondi;
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
		$visa60idusuario_id = (int)$fila['visa60idusuario'];
		$visa60idusuario_td = $APP->tipo_doc;
		$visa60idusuario_doc = '';
		$visa60idusuario_nombre = '';
		if ($visa60idusuario_id != 0) {
			list($visa60idusuario_nombre, $visa60idusuario_id, $visa60idusuario_td, $visa60idusuario_doc) = html_tercero($visa60idusuario_td, $visa60idusuario_doc, $visa60idusuario_id, 0, $objDB);
		}
		$visa60consec_nombre = '';
		$html_visa60consec = html_oculto('visa60consec', $fila['visa60consec'], $visa60consec_nombre);
		$objResponse->assign('div_visa60consec', 'innerHTML', $html_visa60consec);
		$visa60id_nombre = '';
		$html_visa60id = html_oculto('visa60id', $fila['visa60id'], $visa60id_nombre);
		$objResponse->assign('div_visa60id', 'innerHTML', $html_visa60id);
		$objResponse->assign('visa60fechareproini', 'value', $fila['visa60fechareproini']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['visa60fechareproini'], true);
		$objResponse->assign('visa60fechareproini_dia', 'value', $iDia);
		$objResponse->assign('visa60fechareproini_mes', 'value', $iMes);
		$objResponse->assign('visa60fechareproini_agno', 'value', $iAgno);
		$objResponse->assign('visa60fechareprofin', 'value', $fila['visa60fechareprofin']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['visa60fechareprofin'], true);
		$objResponse->assign('visa60fechareprofin_dia', 'value', $iDia);
		$objResponse->assign('visa60fechareprofin_mes', 'value', $iMes);
		$objResponse->assign('visa60fechareprofin_agno', 'value', $iAgno);
		$objResponse->assign('visa60motivo', 'value', cadena_LimpiarXAJAX($fila['visa60motivo']));
		$html_visa60fecharegistro = html_oculto('visa60fecharegistro', $fila['visa60fecharegistro'], fecha_desdenumero($fila['visa60fecharegistro']));
		$objResponse->assign('div_visa60fecharegistro', 'innerHTML', $html_visa60fecharegistro);
		$objResponse->assign('visa60idusuario', 'value', $fila['visa60idusuario']);
		$objResponse->assign('visa60idusuario_td', 'value', $visa60idusuario_td);
		$objResponse->assign('visa60idusuario_doc', 'value', $visa60idusuario_doc);
		$objResponse->assign('div_visa60idusuario', 'innerHTML', $visa60idusuario_nombre);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2960', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('visa60consec', 'value', $visa60consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa60id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2960_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2960_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2960_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2960detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2960');
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
function f2960_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2960_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2960detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2960_PintarLlaves($aParametros)
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
	$mensajes_2960 = 'lg/lg_2960_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2960)) {
		$mensajes_2960 = 'lg/lg_2960_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2960;
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$html_visa60consec = '<input id="visa60consec" name="visa60consec" type="text" value="" onchange="revisaf2960()" class="cuatro" />';
	$html_visa60id = '<input id="visa60id" name="visa60id" type="hidden" value="" />';
	$et_visa60fecharegistro = '00/00/0000';
	$html_visa60fecharegistro = html_oculto('visa60fecharegistro', 0, $et_visa60fecharegistro);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa60consec', 'innerHTML', $html_visa60consec);
	$objResponse->assign('div_visa60id', 'innerHTML', $html_visa60id);
	$objResponse->assign('div_visa60fecharegistro', 'innerHTML', $html_visa60fecharegistro);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

