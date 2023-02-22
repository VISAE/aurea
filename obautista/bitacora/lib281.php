<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
--- 281 Tareas de ingenieria
*/
function f281_HTMLComboV2_aure81idtipotarea($objDB, $objCombos, $valor, $vraure81idbithistoria)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('aure81idtipotarea', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vraure81idbithistoria != 0) {
		$sSQL = 'SELECT aure54id AS id, aure54nombre AS nombre FROM aure54tipotarea WHERE aure54id=' . $vraure81idbithistoria . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f281_Comboaure81idtipotarea($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_aure81idtipotarea = f281_HTMLComboV2_aure81idtipotarea($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_aure81idtipotarea', 'innerHTML', $html_aure81idtipotarea);
	//$objResponse->call('$("#aure81idtipotarea").chosen()');
	return $objResponse;
}
function f281_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 281;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_281 = 'lg/lg_281_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_281)) {
		$mensajes_281 = 'lg/lg_281_es.php';
	}
	require $mensajes_todas;
	require $mensajes_281;
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
	$aure81idbitacora = numeros_validar($valores[1]);
	$aure81consec = numeros_validar($valores[2]);
	$aure81id = numeros_validar($valores[3], true);
	$aure81idbithistoria = numeros_validar($valores[4]);
	$aure81idtipotarea = numeros_validar($valores[5]);
	$aure81semanasest = numeros_validar($valores[6]);
	$aure81diasest = numeros_validar($valores[7]);
	$aure81fechainicio = numeros_validar($valores[8]);
	$aure81avance = numeros_validar($valores[9]);
	$aure81fechafinal = numeros_validar($valores[10]);
	$aure81descripcion = htmlspecialchars(trim($valores[11]));
	/*
	if ($aure81idbithistoria == '') {
		$aure81idbithistoria = 0;
	}
	if ($aure81idtipotarea == '') {
		$aure81idtipotarea = 0;
	}
	if ($aure81semanasest == '') {
		$aure81semanasest = 0;
	}
	if ($aure81diasest == '') {
		$aure81diasest = 0;
	}
	if ($aure81avance == '') {
		$aure81avance = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($aure81descripcion == '') {
		$sError = $ERR['aure81descripcion'] . $sSepara.$sError;
	}
	if (!fecha_NumValido($aure81fechafinal)) {
		//$aure81fechafinal = fecha_DiaMod();
		$sError = $ERR['aure81fechafinal'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($aure81fechainicio)) {
		//$aure81fechainicio = fecha_DiaMod();
		$sError = $ERR['aure81fechainicio'] . $sSepara . $sError;
	}
	if ($aure81diasest == '') {
		$sError = $ERR['aure81diasest'] . $sSepara.$sError;
	}
	if ($aure81semanasest == '') {
		$sError = $ERR['aure81semanasest'] . $sSepara.$sError;
	}
	if ($aure81idtipotarea == '') {
		$sError = $ERR['aure81idtipotarea'] . $sSepara.$sError;
	}
	if ($aure81idbithistoria == '') {
		$sError = $ERR['aure81idbithistoria'] . $sSepara.$sError;
	}
	/*
	if ($aure81id == '') {
		$sError = $ERR['aure81id'] . $sSepara . $sError;
	}
	*/
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$aure81id == 0) {
			if ((int)$aure81consec == 0) {
				$aure81consec = tabla_consecutivo('aure81tareaing', 'aure81consec', 'aure81idbitacora=' . $aure81idbitacora . '', $objDB);
				if ($aure81consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'aure81consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM aure81tareaing WHERE aure81idbitacora=' . $aure81idbitacora . ' AND aure81consec=' . $aure81consec . '';
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
				$aure81id = tabla_consecutivo('aure81tareaing', 'aure81id', '', $objDB);
				if ($aure81id == -1) {
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
			$aure81avance = 0;
			/*
			$sSQL = 'SELECT Campo FROM Tabla WHERE Id=' . $sValorId;
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sCampo = $fila['sCampo'];
			}
			*/
			$sError = 'INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos . ';
		}
	}
	if ($sError == '') {
		//Si el campo aure81descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$aure81descripcion=str_replace('"', '\"', $aure81descripcion);
		$aure81descripcion=str_replace('"', '\"', $aure81descripcion);
		if ($bInserta) {
			$sCampos281 = 'aure81idbitacora, aure81consec, aure81id, aure81idbithistoria, aure81idtipotarea, 
			aure81semanasest, aure81diasest, aure81fechainicio, aure81avance, aure81fechafinal, 
			aure81descripcion';
			$sValores281 = '' . $aure81idbitacora . ', ' . $aure81consec . ', ' . $aure81id . ', ' . $aure81idbithistoria . ', ' . $aure81idtipotarea . ', 
			' . $aure81semanasest . ', ' . $aure81diasest . ', ' . $aure81fechainicio . ', ' . $aure81avance . ', ' . $aure81fechafinal . ', 
			"' . $aure81descripcion . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO aure81tareaing (' . $sCampos281 . ') VALUES (' . cadena_codificar($sValores281) . ');';
			} else {
				$sSQL = 'INSERT INTO aure81tareaing (' . $sCampos281 . ') VALUES (' . $sValores281 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 281 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [281].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $aure81id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo281[1] = 'aure81idbithistoria';
			$scampo281[2] = 'aure81idtipotarea';
			$scampo281[3] = 'aure81semanasest';
			$scampo281[4] = 'aure81diasest';
			$scampo281[5] = 'aure81fechainicio';
			$scampo281[6] = 'aure81fechafinal';
			$scampo281[7] = 'aure81descripcion';
			$svr281[1] = $aure81idbithistoria;
			$svr281[2] = $aure81idtipotarea;
			$svr281[3] = $aure81semanasest;
			$svr281[4] = $aure81diasest;
			$svr281[5] = $aure81fechainicio;
			$svr281[6] = $aure81fechafinal;
			$svr281[7] = $aure81descripcion;
			$iNumCampos = 7;
			$sWhere = 'aure81id=' . $aure81id . '';
			//$sWhere = 'aure81idbitacora=' . $aure81idbitacora . ' AND aure81consec=' . $aure81consec . '';
			$sSQL = 'SELECT * FROM aure81tareaing WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo281[$k]] != $svr281[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo281[$k] . '="' . $svr281[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE aure81tareaing SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE aure81tareaing SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 281 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Tareas de ingenieria}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $aure81id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $aure81id, $sDebug);
}
function f281_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 281;
	$bAudita[4] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_281 = 'lg/lg_281_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_281)) {
		$mensajes_281 = 'lg/lg_281_es.php';
	}
	require $mensajes_todas;
	require $mensajes_281;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$aure81idbitacora = numeros_validar($aParametros[1]);
	$aure81consec = numeros_validar($aParametros[2]);
	$aure81id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=281';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $aure81id . ' LIMIT 0, 1';
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
		$sWhere = 'aure81id=' . $aure81id . '';
		//$sWhere = 'aure81idbitacora=' . $aure81idbitacora . ' AND aure81consec=' . $aure81consec . '';
		$sSQL = 'DELETE FROM aure81tareaing WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {281 Tareas de ingenieria}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $aure81id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f281_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_281 = 'lg/lg_281_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_281)) {
		$mensajes_281 = 'lg/lg_281_es.php';
	}
	require $mensajes_todas;
	require $mensajes_281;
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
	$sBotones = '<input id="paginaf281" name="paginaf281" type="hidden" value="' . $pagina . '"/>
	<input id="lppf281" name="lppf281" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Bitacora, Consec, Id, Bithistoria, Tipotarea, Semanasest, Diasest, Fechainicio, Avance, Fechafinal, Descripcion';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.aure81idbitacora, TB.aure81consec, TB.aure81id, T4.aure80consec, T5.aure54nombre, TB.aure81semanasest, TB.aure81diasest, TB.aure81fechainicio, TB.aure81avance, TB.aure81fechafinal, TB.aure81descripcion, TB.aure81idbithistoria, TB.aure81idtipotarea';
	$sConsulta = 'FROM aure81tareaing AS TB, aure80historiaus AS T4, aure54tipotarea AS T5 
	WHERE ' . $sSQLadd1 . ' TB.aure81idbitacora=' . $aure51id . ' AND TB.aure81idbithistoria=T4.aure80id AND TB.aure81idtipotarea=T5.aure54id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.aure81consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_281" name="consulta_281" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_281" name="titulos_281" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 281: ' . $sSQL . '<br>';
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
	<td><b>' . $ETI['aure81consec'] . '</b></td>
	<td><b>' . $ETI['aure81idbithistoria'] . '</b></td>
	<td><b>' . $ETI['aure81idtipotarea'] . '</b></td>
	<td><b>' . $ETI['aure81semanasest'] . '</b></td>
	<td><b>' . $ETI['aure81diasest'] . '</b></td>
	<td><b>' . $ETI['aure81fechainicio'] . '</b></td>
	<td><b>' . $ETI['aure81avance'] . '</b></td>
	<td><b>' . $ETI['aure81fechafinal'] . '</b></td>
	<td><b>' . $ETI['aure81descripcion'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf281', $registros, $lineastabla, $pagina, 'paginarf281()') . '
	' . html_lpp('lppf281', $lineastabla, 'paginarf281()') . '
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
		$et_aure81idbithistoria = $sPrefijo . cadena_notildes($filadet['aure80consec']) . $sSufijo;
		$et_aure81idtipotarea = $sPrefijo . cadena_notildes($filadet['aure54nombre']) . $sSufijo;
		$et_aure81fechainicio = '';
		if ($filadet['aure81fechainicio'] != 0) {
			$et_aure81fechainicio = $sPrefijo . fecha_desdenumero($filadet['aure81fechainicio']) . $sSufijo;
		}
		$et_aure81avance = $sPrefijo . cadena_notildes($filadet['']) . $sSufijo;
		$et_aure81fechafinal = '';
		if ($filadet['aure81fechafinal'] != 0) {
			$et_aure81fechafinal = $sPrefijo . fecha_desdenumero($filadet['aure81fechafinal']) . $sSufijo;
		}
		$et_aure81descripcion = $sPrefijo . cadena_notildes($filadet['aure81descripcion']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf281(' . $filadet['aure81id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_aure81consec . '</td>
		<td>' . $et_aure81idbithistoria . '</td>
		<td>' . $et_aure81idtipotarea . '</td>
		<td>' . $et_aure81semanasest . '</td>
		<td>' . $et_aure81diasest . '</td>
		<td>' . $et_aure81fechainicio . '</td>
		<td>' . $et_aure81avance . '</td>
		<td>' . $et_aure81fechafinal . '</td>
		<td>' . $et_aure81descripcion . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 281 Tareas de ingenieria XAJAX 
function f281_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $aure81id, $sDebugGuardar) = f281_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f281_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f281detalle', 'innerHTML', $sdetalle);
		//if ($iAccion == 2) {
			//$objResponse->call('cargaridf281(' . $aure81id . ')');
			//} else {
			$objResponse->call('limpiaf281');
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
function f281_Traer($aParametros)
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
		$aure81idbitacora = numeros_validar($aParametros[1]);
		$aure81consec = numeros_validar($aParametros[2]);
		if (($aure81idbitacora != '') && ($aure81consec != '')) {
			$besta = true;
		}
	} else {
		$aure81id = $aParametros[103];
		if ((int)$aure81id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'aure81idbitacora=' . $aure81idbitacora . ' AND aure81consec=' . $aure81consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'aure81id=' . $aure81id . '';
		}
		$sSQL = 'SELECT * FROM aure81tareaing WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 1);
		$objCombos = new clsHtmlCombos();
		$aure81consec_nombre = '';
		$html_aure81consec = html_oculto('aure81consec', $fila['aure81consec'], $aure81consec_nombre);
		$objResponse->assign('div_aure81consec', 'innerHTML', $html_aure81consec);
		$aure81id_nombre = '';
		$html_aure81id = html_oculto('aure81id', $fila['aure81id'], $aure81id_nombre);
		$objResponse->assign('div_aure81id', 'innerHTML', $html_aure81id);
		$objResponse->assign('aure81idbithistoria', 'value', $fila['aure81idbithistoria']);
		$html_aure81idtipotarea = f281_HTMLComboV2_aure81idtipotarea($objDB, $objCombos, $fila['aure81idtipotarea'], $fila['aure81idbithistoria']);
		$objResponse->assign('div_aure81idtipotarea', 'innerHTML', $html_aure81idtipotarea);
		$objResponse->assign('aure81semanasest', 'value', $fila['aure81semanasest']);
		$objResponse->assign('aure81diasest', 'value', $fila['aure81diasest']);
		$objResponse->assign('aure81fechainicio', 'value', $fila['aure81fechainicio']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['aure81fechainicio'], true);
		$objResponse->assign('aure81fechainicio_dia', 'value', $iDia);
		$objResponse->assign('aure81fechainicio_mes', 'value', $iMes);
		$objResponse->assign('aure81fechainicio_agno', 'value', $iAgno);
		$aure81avance_nombre = '&nbsp;';
		if ($fila['aure81avance'] != 0) {
			list($aure81avance_nombre, $serror_det) = tabla_campoxid('', '', '', $fila['aure81avance'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		}
		$html_aure81avance = html_oculto('aure81avance', $fila['aure81avance'], $aure81avance_nombre);
		$objResponse->assign('div_aure81avance', 'innerHTML', $html_aure81avance);
		$objResponse->assign('aure81fechafinal', 'value', $fila['aure81fechafinal']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['aure81fechafinal'], true);
		$objResponse->assign('aure81fechafinal_dia', 'value', $iDia);
		$objResponse->assign('aure81fechafinal_mes', 'value', $iMes);
		$objResponse->assign('aure81fechafinal_agno', 'value', $iAgno);
		$objResponse->assign('aure81descripcion', 'value', $fila['aure81descripcion']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina281', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('aure81consec', 'value', $aure81consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $aure81id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f281_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f281_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f281_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f281detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf281');
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
function f281_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f281_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f281detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f281_PintarLlaves($aParametros)
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
	$objCombos = new clsHtmlCombos();
	$html_aure81consec = '<input id="aure81consec" name="aure81consec" type="text" value="" onchange="revisaf281()" class="cuatro" />';
	$html_aure81id = '<input id="aure81id" name="aure81id" type="hidden" value="" />';
	$html_aure81idtipotarea = f281_HTMLComboV2_aure81idtipotarea($objDB, $objCombos, '', '');
	$html_aure81avance = f281_HTMLComboV2_aure81avance($objDB, $objCombos, '');
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_aure81consec', 'innerHTML', $html_aure81consec);
	$objResponse->assign('div_aure81id', 'innerHTML', $html_aure81id);
	$objResponse->assign('div_aure81idtipotarea', 'innerHTML', $html_aure81idtipotarea);
	$objResponse->call('$("#aure81idtipotarea").chosen()');
	$objResponse->assign('div_aure81avance', 'innerHTML', $html_aure81avance);
	$objResponse->call('$("#aure81avance").chosen()');
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>