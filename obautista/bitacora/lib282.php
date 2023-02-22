<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
--- 282 Pruebas de aceptacion
*/
function f282_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 282;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_282 = 'lg/lg_282_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_282)) {
		$mensajes_282 = 'lg/lg_282_es.php';
	}
	require $mensajes_todas;
	require $mensajes_282;
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
	$aure82idbitacora = numeros_validar($valores[1]);
	$aure82consec = numeros_validar($valores[2]);
	$aure82id = numeros_validar($valores[3], true);
	$aure82condiciones = htmlspecialchars(trim($valores[4]));
	$aure82pasos = htmlspecialchars(trim($valores[5]));
	$aure82asignaperfil = htmlspecialchars(trim($valores[6]));
	$aure82manuales = htmlspecialchars(trim($valores[7]));
	$aure82capacitacion = htmlspecialchars(trim($valores[8]));
	$aure82evaluacion = htmlspecialchars(trim($valores[9]));
	$aure82resultadoesp = htmlspecialchars(trim($valores[10]));
	$aure82idtester = numeros_validar($valores[11]);
	/*
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($aure82idtester == 0) {
		$sError = $ERR['aure82idtester'] . $sSepara . $sError;
	}
	if ($aure82resultadoesp == '') {
		$sError = $ERR['aure82resultadoesp'] . $sSepara.$sError;
	}
	if ($aure82evaluacion == '') {
		$sError = $ERR['aure82evaluacion'] . $sSepara.$sError;
	}
	if ($aure82capacitacion == '') {
		$sError = $ERR['aure82capacitacion'] . $sSepara.$sError;
	}
	if ($aure82manuales == '') {
		$sError = $ERR['aure82manuales'] . $sSepara.$sError;
	}
	if ($aure82asignaperfil == '') {
		$sError = $ERR['aure82asignaperfil'] . $sSepara.$sError;
	}
	if ($aure82pasos == '') {
		$sError = $ERR['aure82pasos'] . $sSepara.$sError;
	}
	if ($aure82condiciones == '') {
		$sError = $ERR['aure82condiciones'] . $sSepara.$sError;
	}
	/*
	if ($aure82id == '') {
		$sError = $ERR['aure82id'] . $sSepara . $sError;
	}
	*/
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($aure82idtester, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$aure82id == 0) {
			if ((int)$aure82consec == 0) {
				$aure82consec = tabla_consecutivo('aure82pruebaac', 'aure82consec', 'aure82idbitacora=' . $aure82idbitacora . '', $objDB);
				if ($aure82consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'aure82consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM aure82pruebaac WHERE aure82idbitacora=' . $aure82idbitacora . ' AND aure82consec=' . $aure82consec . '';
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
				$aure82id = tabla_consecutivo('aure82pruebaac', 'aure82id', '', $objDB);
				if ($aure82id == -1) {
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
		//Si el campo aure82condiciones permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$aure82condiciones=str_replace('"', '\"', $aure82condiciones);
		$aure82condiciones=str_replace('"', '\"', $aure82condiciones);
		//Si el campo aure82pasos permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$aure82pasos=str_replace('"', '\"', $aure82pasos);
		$aure82pasos=str_replace('"', '\"', $aure82pasos);
		//Si el campo aure82resultadoesp permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$aure82resultadoesp=str_replace('"', '\"', $aure82resultadoesp);
		$aure82resultadoesp=str_replace('"', '\"', $aure82resultadoesp);
		if ($bInserta) {
			$sCampos282 = 'aure82idbitacora, aure82consec, aure82id, aure82condiciones, aure82pasos, 
			aure82asignaperfil, aure82manuales, aure82capacitacion, aure82evaluacion, aure82resultadoesp, 
			aure82idtester';
			$sValores282 = '' . $aure82idbitacora . ', ' . $aure82consec . ', ' . $aure82id . ', "' . $aure82condiciones . '", "' . $aure82pasos . '", 
			"' . $aure82asignaperfil . '", "' . $aure82manuales . '", "' . $aure82capacitacion . '", "' . $aure82evaluacion . '", "' . $aure82resultadoesp . '", 
			' . $aure82idtester . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO aure82pruebaac (' . $sCampos282 . ') VALUES (' . cadena_codificar($sValores282) . ');';
			} else {
				$sSQL = 'INSERT INTO aure82pruebaac (' . $sCampos282 . ') VALUES (' . $sValores282 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 282 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [282].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $aure82id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo282[1] = 'aure82condiciones';
			$scampo282[2] = 'aure82pasos';
			$scampo282[3] = 'aure82asignaperfil';
			$scampo282[4] = 'aure82manuales';
			$scampo282[5] = 'aure82capacitacion';
			$scampo282[6] = 'aure82evaluacion';
			$scampo282[7] = 'aure82resultadoesp';
			$scampo282[8] = 'aure82idtester';
			$svr282[1] = $aure82condiciones;
			$svr282[2] = $aure82pasos;
			$svr282[3] = $aure82asignaperfil;
			$svr282[4] = $aure82manuales;
			$svr282[5] = $aure82capacitacion;
			$svr282[6] = $aure82evaluacion;
			$svr282[7] = $aure82resultadoesp;
			$svr282[8] = $aure82idtester;
			$iNumCampos = 8;
			$sWhere = 'aure82id=' . $aure82id . '';
			//$sWhere = 'aure82idbitacora=' . $aure82idbitacora . ' AND aure82consec=' . $aure82consec . '';
			$sSQL = 'SELECT * FROM aure82pruebaac WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo282[$k]] != $svr282[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo282[$k] . '="' . $svr282[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE aure82pruebaac SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE aure82pruebaac SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 282 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Pruebas de aceptacion}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $aure82id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $aure82id, $sDebug);
}
function f282_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 282;
	$bAudita[4] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_282 = 'lg/lg_282_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_282)) {
		$mensajes_282 = 'lg/lg_282_es.php';
	}
	require $mensajes_todas;
	require $mensajes_282;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$aure82idbitacora = numeros_validar($aParametros[1]);
	$aure82consec = numeros_validar($aParametros[2]);
	$aure82id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=282';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $aure82id . ' LIMIT 0, 1';
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
		$sWhere = 'aure82id=' . $aure82id . '';
		//$sWhere = 'aure82idbitacora=' . $aure82idbitacora . ' AND aure82consec=' . $aure82consec . '';
		$sSQL = 'DELETE FROM aure82pruebaac WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {282 Pruebas de aceptacion}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $aure82id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f282_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_282 = 'lg/lg_282_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_282)) {
		$mensajes_282 = 'lg/lg_282_es.php';
	}
	require $mensajes_todas;
	require $mensajes_282;
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
	$sBotones = '<input id="paginaf282" name="paginaf282" type="hidden" value="' . $pagina . '"/>
	<input id="lppf282" name="lppf282" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Bitacora, Consec, Id, Condiciones, Pasos, Asignaperfil, Manuales, Capacitacion, Evaluacion, Resultadoesp, Tester';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.aure82idbitacora, TB.aure82consec, TB.aure82id, TB.aure82condiciones, TB.aure82pasos, TB.aure82asignaperfil, TB.aure82manuales, TB.aure82capacitacion, TB.aure82evaluacion, TB.aure82resultadoesp, T11.unad11razonsocial AS C11_nombre, TB.aure82idtester, T11.unad11tipodoc AS C11_td, T11.unad11doc AS C11_doc';
	$sConsulta = 'FROM aure82pruebaac AS TB, unad11terceros AS T11 
	WHERE ' . $sSQLadd1 . ' TB.aure82idbitacora=' . $aure51id . ' AND TB.aure82idtester=T11.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.aure82consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_282" name="consulta_282" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_282" name="titulos_282" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 282: ' . $sSQL . '<br>';
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
	<td><b>' . $ETI['aure82consec'] . '</b></td>
	<td><b>' . $ETI['aure82condiciones'] . '</b></td>
	<td><b>' . $ETI['aure82pasos'] . '</b></td>
	<td><b>' . $ETI['aure82asignaperfil'] . '</b></td>
	<td><b>' . $ETI['aure82manuales'] . '</b></td>
	<td><b>' . $ETI['aure82capacitacion'] . '</b></td>
	<td><b>' . $ETI['aure82evaluacion'] . '</b></td>
	<td><b>' . $ETI['aure82resultadoesp'] . '</b></td>
	<td colspan="2"><b>' . $ETI['aure82idtester'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf282', $registros, $lineastabla, $pagina, 'paginarf282()') . '
	' . html_lpp('lppf282', $lineastabla, 'paginarf282()') . '
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
		$et_aure82condiciones = $sPrefijo . cadena_notildes($filadet['aure82condiciones']) . $sSufijo;
		$et_aure82pasos = $sPrefijo . cadena_notildes($filadet['aure82pasos']) . $sSufijo;
		$et_aure82asignaperfil = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['aure82asignaperfil'] == 'S') {
			$et_aure82asignaperfil = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_aure82manuales = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['aure82manuales'] == 'S') {
			$et_aure82manuales = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_aure82capacitacion = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['aure82capacitacion'] == 'S') {
			$et_aure82capacitacion = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_aure82evaluacion = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['aure82evaluacion'] == 'S') {
			$et_aure82evaluacion = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_aure82resultadoesp = $sPrefijo . cadena_notildes($filadet['aure82resultadoesp']) . $sSufijo;
		$et_aure82idtester_doc = '';
		$et_aure82idtester_nombre = '';
		if ($filadet['aure82idtester'] != 0) {
			$et_aure82idtester_doc = $sPrefijo . $filadet['C11_td'] . ' ' . $filadet['C11_doc'] . $sSufijo;
			$et_aure82idtester_nombre = $sPrefijo . cadena_notildes($filadet['C11_nombre']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf282(' . $filadet['aure82id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_aure82consec . '</td>
		<td>' . $et_aure82condiciones . '</td>
		<td>' . $et_aure82pasos . '</td>
		<td>' . $et_aure82asignaperfil . '</td>
		<td>' . $et_aure82manuales . '</td>
		<td>' . $et_aure82capacitacion . '</td>
		<td>' . $et_aure82evaluacion . '</td>
		<td>' . $et_aure82resultadoesp . '</td>
		<td>' . $et_aure82idtester_doc . '</td>
		<td>' . $et_aure82idtester_nombre . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 282 Pruebas de aceptacion XAJAX 
function f282_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $aure82id, $sDebugGuardar) = f282_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f282_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f282detalle', 'innerHTML', $sdetalle);
		//if ($iAccion == 2) {
			//$objResponse->call('cargaridf282(' . $aure82id . ')');
			//} else {
			$objResponse->call('limpiaf282');
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
function f282_Traer($aParametros)
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
		$aure82idbitacora = numeros_validar($aParametros[1]);
		$aure82consec = numeros_validar($aParametros[2]);
		if (($aure82idbitacora != '') && ($aure82consec != '')) {
			$besta = true;
		}
	} else {
		$aure82id = $aParametros[103];
		if ((int)$aure82id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'aure82idbitacora=' . $aure82idbitacora . ' AND aure82consec=' . $aure82consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'aure82id=' . $aure82id . '';
		}
		$sSQL = 'SELECT * FROM aure82pruebaac WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 1);
		$aure82idtester_id = (int)$fila['aure82idtester'];
		$aure82idtester_td = $APP->tipo_doc;
		$aure82idtester_doc = '';
		$aure82idtester_nombre = '';
		if ($aure82idtester_id != 0) {
			list($aure82idtester_nombre, $aure82idtester_id, $aure82idtester_td, $aure82idtester_doc) = html_tercero($aure82idtester_td, $aure82idtester_doc, $aure82idtester_id, 0, $objDB);
		}
		$aure82consec_nombre = '';
		$html_aure82consec = html_oculto('aure82consec', $fila['aure82consec'], $aure82consec_nombre);
		$objResponse->assign('div_aure82consec', 'innerHTML', $html_aure82consec);
		$aure82id_nombre = '';
		$html_aure82id = html_oculto('aure82id', $fila['aure82id'], $aure82id_nombre);
		$objResponse->assign('div_aure82id', 'innerHTML', $html_aure82id);
		$objResponse->assign('aure82condiciones', 'value', $fila['aure82condiciones']);
		$objResponse->assign('aure82pasos', 'value', $fila['aure82pasos']);
		$objResponse->assign('aure82asignaperfil', 'value', $fila['aure82asignaperfil']);
		$objResponse->assign('aure82manuales', 'value', $fila['aure82manuales']);
		$objResponse->assign('aure82capacitacion', 'value', $fila['aure82capacitacion']);
		$objResponse->assign('aure82evaluacion', 'value', $fila['aure82evaluacion']);
		$objResponse->assign('aure82resultadoesp', 'value', $fila['aure82resultadoesp']);
		$objResponse->assign('aure82idtester', 'value', $fila['aure82idtester']);
		$objResponse->assign('aure82idtester_td', 'value', $aure82idtester_td);
		$objResponse->assign('aure82idtester_doc', 'value', $aure82idtester_doc);
		$objResponse->assign('div_aure82idtester', 'innerHTML', $aure82idtester_nombre);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina282', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('aure82consec', 'value', $aure82consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $aure82id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f282_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f282_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f282_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f282detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf282');
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
function f282_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f282_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f282detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f282_PintarLlaves($aParametros)
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
	$html_aure82consec = '<input id="aure82consec" name="aure82consec" type="text" value="" onchange="revisaf282()" class="cuatro" />';
	$html_aure82id = '<input id="aure82id" name="aure82id" type="hidden" value="" />';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_aure82consec', 'innerHTML', $html_aure82consec);
	$objResponse->assign('div_aure82id', 'innerHTML', $html_aure82id);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>