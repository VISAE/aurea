<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.16 viernes, 11 de julio de 2025
--- 1204 Listas - participantes
*/
function f1204_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 1204)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1204 = 'lg/lg_1204_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1204)) {
		$mensajes_1204 = 'lg/lg_1204_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1204;
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
	$masi04idlista = numeros_validar($valores[1]);
	$masi04idtercero = numeros_validar($valores[2]);
	$masi04id = numeros_validar($valores[3], true);
	$masi04fechareg = numeros_validar($valores[4]);
	$masi04fecharet = numeros_validar($valores[5]);
	$masi04envio_generales = numeros_validar($valores[6]);
	/*
	if ($masi04envio_generales == '') {
		$masi04envio_generales = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($masi04envio_generales == '') {
		$sError = $ERR['masi04envio_generales'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($masi04fecharet)) {
		//$masi04fecharet = fecha_DiaMod();
		$sError = $ERR['masi04fecharet'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($masi04fechareg)) {
		//$masi04fechareg = fecha_DiaMod();
		$sError = $ERR['masi04fechareg'] . $sSepara . $sError;
	}
	/*
	if ($masi04id == '') {
		$sError = $ERR['masi04id'] . $sSepara . $sError;
	}
	*/
	if ($masi04idtercero == 0) {
		$sError = $ERR['masi04idtercero'] . $sSepara . $sError;
	}
	if ($masi04idlista == '') {
		$sError = $ERR['masi04idlista'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($masi04idtercero, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$masi04id == 0) {
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM masi04listapartic WHERE masi04idlista=' . $masi04idlista . ' AND masi04idtercero="' . $masi04idtercero . '"';
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
				$masi04id = tabla_consecutivo('masi04listapartic', 'masi04id', '', $objDB);
				if ($masi04id == -1) {
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
			$sCampos1204 = 'masi04idlista, masi04idtercero, masi04id, masi04fechareg, masi04fecharet, 
			masi04envio_generales';
			$sValores1204 = '' . $masi04idlista . ', ' . $masi04idtercero . ', ' . $masi04id . ', ' . $masi04fechareg . ', ' . $masi04fecharet . ', 
			' . $masi04envio_generales . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO masi04listapartic (' . $sCampos1204 . ') VALUES (' . cadena_codificar($sValores1204) . ');';
			} else {
				$sSQL = 'INSERT INTO masi04listapartic (' . $sCampos1204 . ') VALUES (' . $sValores1204 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 1204 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [1204].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $masi04id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo1204[1] = 'masi04fechareg';
			$scampo1204[2] = 'masi04fecharet';
			$scampo1204[3] = 'masi04envio_generales';
			$svr1204[1] = $masi04fechareg;
			$svr1204[2] = $masi04fecharet;
			$svr1204[3] = $masi04envio_generales;
			$iNumCampos = 3;
			$sWhere = 'masi04id=' . $masi04id . '';
			//$sWhere = 'masi04idlista=' . $masi04idlista . ' AND masi04idtercero="' . $masi04idtercero . '"';
			$sSQL = 'SELECT * FROM masi04listapartic WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo1204[$k]] != $svr1204[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo1204[$k] . '="' . $svr1204[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE masi04listapartic SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE masi04listapartic SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 1204 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Listas - participantes}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $masi04id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $masi04id, $sDebug);
}
function f1204_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 1204;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1204 = 'lg/lg_1204_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1204)) {
		$mensajes_1204 = 'lg/lg_1204_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1204;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$masi04idlista = numeros_validar($aParametros[1]);
	$masi04idtercero = numeros_validar($aParametros[2]);
	$masi04id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1204';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $masi04id . ' LIMIT 0, 1';
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
		$sWhere = 'masi04id=' . $masi04id . '';
		//$sWhere = 'masi04idlista=' . $masi04idlista . ' AND masi04idtercero="' . $masi04idtercero . '"';
		$sSQL = 'DELETE FROM masi04listapartic WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {1204 Listas - participantes}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $masi04id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f1204_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1204 = 'lg/lg_1204_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1204)) {
		$mensajes_1204 = 'lg/lg_1204_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1204;
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
	$masi03id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM masi03listas WHERE masi03id=' . $masi03id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$sLeyenda = '';
	$sBotones = '<input id="paginaf1204" name="paginaf1204" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf1204" name="lppf1204" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Lista, Tercero, Id, Fechareg, Fecharet, Envio_generales';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.masi04idlista, T2.unad11razonsocial AS C2_nombre, TB.masi04id, TB.masi04fechareg, TB.masi04fecharet, 
	TB.masi04envio_generales, TB.masi04idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc';
	$sConsulta = 'FROM masi04listapartic AS TB, unad11terceros AS T2 
	WHERE ' . $sSQLadd1 . ' TB.masi04idlista=' . $masi03id . ' AND TB.masi04idtercero=T2.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.masi04idtercero';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_1204" name="consulta_1204" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_1204" name="titulos_1204" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 1204: ' . $sSQL . '<br>';
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
	$res = $res . '<th colspan="2"><b>' . $ETI['masi04idtercero'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi04fechareg'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi04fecharet'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi04envio_generales'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf1204', $registros, $lineastabla, $pagina, 'paginarf1204()') . '';
	$res = $res . '' . html_lpp('lppf1204', $lineastabla, 'paginarf1204()') . '';
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
		$et_masi04idtercero_doc = '';
		$et_masi04idtercero_nombre = '';
		if ($filadet['masi04idtercero'] != 0) {
			$et_masi04idtercero_doc = $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo;
			$et_masi04idtercero_nombre = $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo;
		}
		$et_masi04fechareg = '';
		if ($filadet['masi04fechareg'] != 0) {
			$et_masi04fechareg = $sPrefijo . fecha_desdenumero($filadet['masi04fechareg']) . $sSufijo;
		}
		$et_masi04fecharet = '';
		if ($filadet['masi04fecharet'] != 0) {
			$et_masi04fecharet = $sPrefijo . fecha_desdenumero($filadet['masi04fecharet']) . $sSufijo;
		}
		$et_masi04envio_generales = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['masi04envio_generales'] == 0) {
			$et_masi04envio_generales = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf1204(' . $filadet['masi04id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_masi04idtercero_doc . '</td>';
		$res = $res . '<td>' . $et_masi04idtercero_nombre . '</td>';
		$res = $res . '<td>' . $et_masi04fechareg . '</td>';
		$res = $res . '<td>' . $et_masi04fecharet . '</td>';
		$res = $res . '<td>' . $et_masi04envio_generales . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 1204 Listas - participantes XAJAX 
function f1204_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $masi04id, $sDebugGuardar) = f1204_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f1204_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f1204detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf1204(' . $masi04id . ')');
		} else {
		*/
		$objResponse->call('limpiaf1204');
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
function f1204_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_1204 = 'lg/lg_1204_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1204)) {
		$mensajes_1204 = 'lg/lg_1204_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_1204;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$masi04idlista = numeros_validar($aParametros[1]);
		$masi04idtercero = numeros_validar($aParametros[2]);
		if (($masi04idlista != '') && ($masi04idtercero != '')) {
			$besta = true;
		}
	} else {
		$masi04id = $aParametros[103];
		if ((int)$masi04id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'masi04idlista=' . $masi04idlista . ' AND masi04idtercero=' . $masi04idtercero . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'masi04id=' . $masi04id . '';
		}
		$sSQL = 'SELECT * FROM masi04listapartic WHERE ' . $sSQLcondi;
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
		$masi04idtercero_id = (int)$fila['masi04idtercero'];
		$masi04idtercero_td = $APP->tipo_doc;
		$masi04idtercero_doc = '';
		$masi04idtercero_nombre = '';
		if ($masi04idtercero_id != 0) {
			list($masi04idtercero_nombre, $masi04idtercero_id, $masi04idtercero_td, $masi04idtercero_doc) = html_tercero($masi04idtercero_td, $masi04idtercero_doc, $masi04idtercero_id, 0, $objDB);
		}
		$html_masi04idtercero_llaves = html_DivTerceroV8('masi04idtercero', $masi04idtercero_td, $masi04idtercero_doc, true, $objDB, $objCombos, 2, 'Ingrese el documento');
		$objResponse->assign('masi04idtercero', 'value', $masi04idtercero_id);
		$objResponse->assign('div_masi04idtercero_llaves', 'innerHTML', $html_masi04idtercero_llaves);
		$objResponse->assign('div_masi04idtercero', 'innerHTML', $masi04idtercero_nombre);
		$masi04id_nombre = '';
		$html_masi04id = html_oculto('masi04id', $fila['masi04id'], $masi04id_nombre);
		$objResponse->assign('div_masi04id', 'innerHTML', $html_masi04id);
		$objResponse->assign('masi04fechareg', 'value', $fila['masi04fechareg']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['masi04fechareg'], true);
		$objResponse->assign('masi04fechareg_dia', 'value', $iDia);
		$objResponse->assign('masi04fechareg_mes', 'value', $iMes);
		$objResponse->assign('masi04fechareg_agno', 'value', $iAgno);
		$objResponse->assign('masi04fecharet', 'value', $fila['masi04fecharet']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['masi04fecharet'], true);
		$objResponse->assign('masi04fecharet_dia', 'value', $iDia);
		$objResponse->assign('masi04fecharet_mes', 'value', $iMes);
		$objResponse->assign('masi04fecharet_agno', 'value', $iAgno);
		$objResponse->assign('masi04envio_generales', 'value', $fila['masi04envio_generales']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1204', 'block')");
	} else {
		if ($paso == 1) {
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $masi04id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f1204_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f1204_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f1204_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f1204detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1204');
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
function f1204_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f1204_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1204detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1204_PintarLlaves($aParametros)
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
	$mensajes_1204 = 'lg/lg_1204_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1204)) {
		$mensajes_1204 = 'lg/lg_1204_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_1204;
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$masi04idtercero = 0;
	$masi04idtercero_rs = '';
	$html_masi04idtercero_llaves = html_DivTerceroV8('masi04idtercero', $APP->tipo_doc, '', false, $objDB, $objCombos, 2, $ETI['ing_doc']);
	$html_masi04id = '<input id="masi04id" name="masi04id" type="hidden" value="" />';
	$objResponse = new xajaxResponse();
	$objResponse->assign('masi04idtercero', 'value', $masi04idtercero);
	$objResponse->assign('div_masi04idtercero_llaves', 'innerHTML', $html_masi04idtercero_llaves);
	$objResponse->assign('div_masi04idtercero', 'innerHTML', $masi04idtercero_rs);
	$objResponse->assign('div_masi04id', 'innerHTML', $html_masi04id);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

