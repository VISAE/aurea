<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
--- 280 Historia de usuario
*/
function f280_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 280;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_280 = 'lg/lg_280_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_280)) {
		$mensajes_280 = 'lg/lg_280_es.php';
	}
	require $mensajes_todas;
	require $mensajes_280;
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
	$aure80idbitacora = numeros_validar($valores[1]);
	$aure80consec = numeros_validar($valores[2]);
	$aure80id = numeros_validar($valores[3], true);
	$aure80momento = numeros_validar($valores[4]);
	$aure80infousuario = htmlspecialchars(trim($valores[5]));
	$aure80prioridad = numeros_validar($valores[6]);
	$aure80semanaest = numeros_validar($valores[7]);
	$aure80diasest = numeros_validar($valores[8]);
	$aure80iteracionasig = numeros_validar($valores[9]);
	$aure80infotecnica = htmlspecialchars(trim($valores[10]));
	$aure80observaciones = htmlspecialchars(trim($valores[11]));
	/*
	if ($aure80momento == '') {
		$aure80momento = 0;
	}
	if ($aure80prioridad == '') {
		$aure80prioridad = 0;
	}
	if ($aure80semanaest == '') {
		$aure80semanaest = 0;
	}
	if ($aure80diasest == '') {
		$aure80diasest = 0;
	}
	if ($aure80iteracionasig == '') {
		$aure80iteracionasig = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($aure80observaciones == '') {
		$sError = $ERR['aure80observaciones'] . $sSepara.$sError;
	}
	if ($aure80infotecnica == '') {
		$sError = $ERR['aure80infotecnica'] . $sSepara.$sError;
	}
	if ($aure80iteracionasig == '') {
		$sError = $ERR['aure80iteracionasig'] . $sSepara.$sError;
	}
	if ($aure80diasest == '') {
		$sError = $ERR['aure80diasest'] . $sSepara.$sError;
	}
	if ($aure80semanaest == '') {
		$sError = $ERR['aure80semanaest'] . $sSepara.$sError;
	}
	if ($aure80prioridad == '') {
		$sError = $ERR['aure80prioridad'] . $sSepara.$sError;
	}
	if ($aure80infousuario == '') {
		$sError = $ERR['aure80infousuario'] . $sSepara.$sError;
	}
	if ($aure80momento == '') {
		$sError = $ERR['aure80momento'] . $sSepara.$sError;
	}
	/*
	if ($aure80id == '') {
		$sError = $ERR['aure80id'] . $sSepara . $sError;
	}
	*/
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$aure80id == 0) {
			if ((int)$aure80consec == 0) {
				$aure80consec = tabla_consecutivo('aure80historiaus', 'aure80consec', 'aure80idbitacora=' . $aure80idbitacora . '', $objDB);
				if ($aure80consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'aure80consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM aure80historiaus WHERE aure80idbitacora=' . $aure80idbitacora . ' AND aure80consec=' . $aure80consec . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve) {
						$sError = $ERR['2'];
					}
				}
			}
			if ($sError == '') {
				$aure80id = tabla_consecutivo('aure80historiaus', 'aure80id', '', $objDB);
				if ($aure80id == -1) {
					$sError = $objDB->serror;
				}
				$bInserta = true;
				$iAccion = 2;
			}
		} else {
			list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
		}
	}
	if ($sError == '') {
		//Si el campo aure80infousuario permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$aure80infousuario=str_replace('"', '\"', $aure80infousuario);
		$aure80infousuario=str_replace('"', '\"', $aure80infousuario);
		//Si el campo aure80infotecnica permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$aure80infotecnica=str_replace('"', '\"', $aure80infotecnica);
		$aure80infotecnica=str_replace('"', '\"', $aure80infotecnica);
		//Si el campo aure80observaciones permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$aure80observaciones=str_replace('"', '\"', $aure80observaciones);
		$aure80observaciones=str_replace('"', '\"', $aure80observaciones);
		if ($bInserta) {
			$sCampos280 = 'aure80idbitacora, aure80consec, aure80id, aure80momento, aure80infousuario, 
			aure80prioridad, aure80semanaest, aure80diasest, aure80iteracionasig, aure80infotecnica, 
			aure80observaciones';
			$sValores280 = '' . $aure80idbitacora . ', ' . $aure80consec . ', ' . $aure80id . ', ' . $aure80momento . ', "' . $aure80infousuario . '", 
			' . $aure80prioridad . ', ' . $aure80semanaest . ', ' . $aure80diasest . ', ' . $aure80iteracionasig . ', "' . $aure80infotecnica . '", 
			"' . $aure80observaciones . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO aure80historiaus (' . $sCampos280 . ') VALUES (' . cadena_codificar($sValores280) . ');';
			} else {
				$sSQL = 'INSERT INTO aure80historiaus (' . $sCampos280 . ') VALUES (' . $sValores280 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 280 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [280].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $aure80id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo280[1] = 'aure80momento';
			$scampo280[2] = 'aure80infousuario';
			$scampo280[3] = 'aure80prioridad';
			$scampo280[4] = 'aure80semanaest';
			$scampo280[5] = 'aure80diasest';
			$scampo280[6] = 'aure80iteracionasig';
			$scampo280[7] = 'aure80infotecnica';
			$scampo280[8] = 'aure80observaciones';
			$svr280[1] = $aure80momento;
			$svr280[2] = $aure80infousuario;
			$svr280[3] = $aure80prioridad;
			$svr280[4] = $aure80semanaest;
			$svr280[5] = $aure80diasest;
			$svr280[6] = $aure80iteracionasig;
			$svr280[7] = $aure80infotecnica;
			$svr280[8] = $aure80observaciones;
			$iNumCampos = 8;
			$sWhere = 'aure80id=' . $aure80id . '';
			//$sWhere = 'aure80idbitacora=' . $aure80idbitacora . ' AND aure80consec=' . $aure80consec . '';
			$sSQL = 'SELECT * FROM aure80historiaus WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo280[$k]] != $svr280[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo280[$k] . '="' . $svr280[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE aure80historiaus SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE aure80historiaus SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 280 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Historia de usuario}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $aure80id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $aure80id, $sDebug);
}
function f280_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 280;
	$bAudita[4] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_280 = 'lg/lg_280_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_280)) {
		$mensajes_280 = 'lg/lg_280_es.php';
	}
	require $mensajes_todas;
	require $mensajes_280;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$aure80idbitacora = numeros_validar($aParametros[1]);
	$aure80consec = numeros_validar($aParametros[2]);
	$aure80id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=280';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $aure80id . ' LIMIT 0, 1';
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
		$sWhere = 'aure80id=' . $aure80id . '';
		//$sWhere = 'aure80idbitacora=' . $aure80idbitacora . ' AND aure80consec=' . $aure80consec . '';
		$sSQL = 'DELETE FROM aure80historiaus WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {280 Historia de usuario}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $aure80id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f280_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_280 = 'lg/lg_280_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_280)) {
		$mensajes_280 = 'lg/lg_280_es.php';
	}
	require $mensajes_todas;
	require $mensajes_280;
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
	$idTercero = $aParametros[100];
	$sDebug = '';
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$aure51id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	$bAbierta = false;
	$sSQL = 'SELECT aure51estado FROM aure51bitacora WHERE aure51id=' . $aure51id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['aure51estado'] != 'S') {
			$bAbierta = true;
		}
	}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf280" name="paginaf280" type="hidden" value="' . $pagina . '"/>
	<input id="lppf280" name="lppf280" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 1);
	/*
	$aEstado=array();
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
		$aNoms=explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'T1.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	$sTitulos = 'Bitacora, Consec, Id, Momento, Infousuario, Prioridad, Semanaest, Diasest, Iteracionasig, Infotecnica, Observaciones';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.aure80idbitacora, TB.aure80consec, TB.aure80id, T4.aure53nombre, TB.aure80infousuario, TB.aure80prioridad, TB.aure80semanaest, TB.aure80diasest, TB.aure80iteracionasig, TB.aure80infotecnica, TB.aure80observaciones, TB.aure80momento';
	$sConsulta = 'FROM aure80historiaus AS TB, aure53hmomento AS T4 
	WHERE ' . $sSQLadd1 . ' TB.aure80idbitacora=' . $aure51id . ' AND TB.aure80momento=T4.aure53id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.aure80consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_280" name="consulta_280" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_280" name="titulos_280" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 280: ' . $sSQL . '<br>';
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
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['aure80consec'] . '</b></td>
	<td><b>' . $ETI['aure80momento'] . '</b></td>
	<td><b>' . $ETI['aure80infousuario'] . '</b></td>
	<td><b>' . $ETI['aure80prioridad'] . '</b></td>
	<td><b>' . $ETI['aure80semanaest'] . '</b></td>
	<td><b>' . $ETI['aure80diasest'] . '</b></td>
	<td><b>' . $ETI['aure80iteracionasig'] . '</b></td>
	<td><b>' . $ETI['aure80infotecnica'] . '</b></td>
	<td><b>' . $ETI['aure80observaciones'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf280', $registros, $lineastabla, $pagina, 'paginarf280()') . '
	' . html_lpp('lppf280', $lineastabla, 'paginarf280()') . '
	</td>
	</tr></thead>';
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
		$et_aure80momento = $sPrefijo . cadena_notildes($filadet['aure53nombre']) . $sSufijo;
		$et_aure80infousuario = $sPrefijo . cadena_notildes($filadet['aure80infousuario']) . $sSufijo;
		$et_aure80prioridad = $sPrefijo . cadena_notildes($filadet['']) . $sSufijo;
		$et_aure80infotecnica = $sPrefijo . cadena_notildes($filadet['aure80infotecnica']) . $sSufijo;
		$et_aure80observaciones = $sPrefijo . cadena_notildes($filadet['aure80observaciones']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf280(' . $filadet['aure80id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_aure80consec . '</td>
		<td>' . $et_aure80momento . '</td>
		<td>' . $et_aure80infousuario . '</td>
		<td>' . $et_aure80prioridad . '</td>
		<td>' . $et_aure80semanaest . '</td>
		<td>' . $et_aure80diasest . '</td>
		<td>' . $et_aure80iteracionasig . '</td>
		<td>' . $et_aure80infotecnica . '</td>
		<td>' . $et_aure80observaciones . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 280 Historia de usuario XAJAX 
function f280_Guardar($valores, $aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
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
	$idTercero = $opts[100];
	if ($sError == '') {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		list($sError, $iAccion, $aure80id, $sDebugGuardar) = f280_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f280_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f280detalle', 'innerHTML', $sdetalle);
		//if ($iAccion == 2) {
			//$objResponse->call('cargaridf280(' . $aure80id . ')');
			//} else {
			$objResponse->call('limpiaf280');
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
function f280_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$aure80idbitacora = numeros_validar($aParametros[1]);
		$aure80consec = numeros_validar($aParametros[2]);
		if (($aure80idbitacora != '') && ($aure80consec != '')) {
			$besta = true;
		}
	} else {
		$aure80id = $aParametros[103];
		if ((int)$aure80id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'aure80idbitacora=' . $aure80idbitacora . ' AND aure80consec=' . $aure80consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'aure80id=' . $aure80id . '';
		}
		$sSQL = 'SELECT * FROM aure80historiaus WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 1);
		$aure80consec_nombre = '';
		$html_aure80consec = html_oculto('aure80consec', $fila['aure80consec'], $aure80consec_nombre);
		$objResponse->assign('div_aure80consec', 'innerHTML', $html_aure80consec);
		$aure80id_nombre = '';
		$html_aure80id = html_oculto('aure80id', $fila['aure80id'], $aure80id_nombre);
		$objResponse->assign('div_aure80id', 'innerHTML', $html_aure80id);
		$objResponse->assign('aure80momento', 'value', $fila['aure80momento']);
		$objResponse->assign('aure80infousuario', 'value', $fila['aure80infousuario']);
		$objResponse->assign('aure80prioridad', 'value', $fila['aure80prioridad']);
		$objResponse->assign('aure80semanaest', 'value', $fila['aure80semanaest']);
		$objResponse->assign('aure80diasest', 'value', $fila['aure80diasest']);
		$objResponse->assign('aure80iteracionasig', 'value', $fila['aure80iteracionasig']);
		$objResponse->assign('aure80infotecnica', 'value', $fila['aure80infotecnica']);
		$objResponse->assign('aure80observaciones', 'value', $fila['aure80observaciones']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina280', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('aure80consec', 'value', $aure80consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $aure80id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f280_Eliminar($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
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
	$idTercero = $opts[100];
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
	list($sError, $sDebugElimina) = f280_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f280_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f280detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf280');
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
function f280_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f280_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f280detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f280_PintarLlaves($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	/*
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	*/
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$iPiel = iDefinirPiel($APP, 1);
	$html_aure80consec = '<input id="aure80consec" name="aure80consec" type="text" value="" onchange="revisaf280()" class="cuatro" />';
	$html_aure80id = '<input id="aure80id" name="aure80id" type="hidden" value="" />';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_aure80consec', 'innerHTML', $html_aure80consec);
	$objResponse->assign('div_aure80id', 'innerHTML', $html_aure80id);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>