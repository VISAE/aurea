<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
--- 283 Tarjetas CRC
*/
function f283_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 283;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_283 = 'lg/lg_283_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_283)) {
		$mensajes_283 = 'lg/lg_283_es.php';
	}
	require $mensajes_todas;
	require $mensajes_283;
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
	$aure83idbitacora = numeros_validar($valores[1]);
	$aure83consec = numeros_validar($valores[2]);
	$aure83id = numeros_validar($valores[3], true);
	$aure83idbithistoria = numeros_validar($valores[4]);
	$aure83idtarea = numeros_validar($valores[5]);
	$aure83vigente = htmlspecialchars(trim($valores[6]));
	$aure83nombreclase = htmlspecialchars(trim($valores[7]));
	$aure83responsabilidades = htmlspecialchars(trim($valores[8]));
	$aure83colaboradores = htmlspecialchars(trim($valores[9]));
	$aure83idtabla = numeros_validar($valores[10]);
	/*
	if ($aure83idbithistoria == '') {
		$aure83idbithistoria = 0;
	}
	if ($aure83idtarea == '') {
		$aure83idtarea = 0;
	}
	if ($aure83idtabla == '') {
		$aure83idtabla = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($aure83idtabla == '') {
		$sError = $ERR['aure83idtabla'] . $sSepara.$sError;
	}
	if ($aure83colaboradores == '') {
		$sError = $ERR['aure83colaboradores'] . $sSepara.$sError;
	}
	if ($aure83responsabilidades == '') {
		$sError = $ERR['aure83responsabilidades'] . $sSepara.$sError;
	}
	if ($aure83nombreclase == '') {
		$sError = $ERR['aure83nombreclase'] . $sSepara.$sError;
	}
	if ($aure83vigente == '') {
		$sError = $ERR['aure83vigente'] . $sSepara.$sError;
	}
	if ($aure83idtarea == '') {
		$sError = $ERR['aure83idtarea'] . $sSepara.$sError;
	}
	if ($aure83idbithistoria == '') {
		$sError = $ERR['aure83idbithistoria'] . $sSepara.$sError;
	}
	/*
	if ($aure83id == '') {
		$sError = $ERR['aure83id'] . $sSepara . $sError;
	}
	*/
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$aure83id == 0) {
			if ((int)$aure83consec == 0) {
				$aure83consec = tabla_consecutivo('aure83tarjetacrc', 'aure83consec', 'aure83idbitacora=' . $aure83idbitacora . '', $objDB);
				if ($aure83consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'aure83consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM aure83tarjetacrc WHERE aure83idbitacora=' . $aure83idbitacora . ' AND aure83consec=' . $aure83consec . '';
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
				$aure83id = tabla_consecutivo('aure83tarjetacrc', 'aure83id', '', $objDB);
				if ($aure83id == -1) {
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
		//Si el campo aure83responsabilidades permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$aure83responsabilidades=str_replace('"', '\"', $aure83responsabilidades);
		$aure83responsabilidades=str_replace('"', '\"', $aure83responsabilidades);
		//Si el campo aure83colaboradores permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$aure83colaboradores=str_replace('"', '\"', $aure83colaboradores);
		$aure83colaboradores=str_replace('"', '\"', $aure83colaboradores);
		if ($bInserta) {
			$sCampos283 = 'aure83idbitacora, aure83consec, aure83id, aure83idbithistoria, aure83idtarea, 
			aure83vigente, aure83nombreclase, aure83responsabilidades, aure83colaboradores, aure83idtabla';
			$sValores283 = '' . $aure83idbitacora . ', ' . $aure83consec . ', ' . $aure83id . ', ' . $aure83idbithistoria . ', ' . $aure83idtarea . ', 
			"' . $aure83vigente . '", "' . $aure83nombreclase . '", "' . $aure83responsabilidades . '", "' . $aure83colaboradores . '", ' . $aure83idtabla . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO aure83tarjetacrc (' . $sCampos283 . ') VALUES (' . cadena_codificar($sValores283) . ');';
			} else {
				$sSQL = 'INSERT INTO aure83tarjetacrc (' . $sCampos283 . ') VALUES (' . $sValores283 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 283 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [283].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $aure83id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo283[1] = 'aure83idbithistoria';
			$scampo283[2] = 'aure83idtarea';
			$scampo283[3] = 'aure83vigente';
			$scampo283[4] = 'aure83nombreclase';
			$scampo283[5] = 'aure83responsabilidades';
			$scampo283[6] = 'aure83colaboradores';
			$scampo283[7] = 'aure83idtabla';
			$svr283[1] = $aure83idbithistoria;
			$svr283[2] = $aure83idtarea;
			$svr283[3] = $aure83vigente;
			$svr283[4] = $aure83nombreclase;
			$svr283[5] = $aure83responsabilidades;
			$svr283[6] = $aure83colaboradores;
			$svr283[7] = $aure83idtabla;
			$iNumCampos = 7;
			$sWhere = 'aure83id=' . $aure83id . '';
			//$sWhere = 'aure83idbitacora=' . $aure83idbitacora . ' AND aure83consec=' . $aure83consec . '';
			$sSQL = 'SELECT * FROM aure83tarjetacrc WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo283[$k]] != $svr283[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo283[$k] . '="' . $svr283[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE aure83tarjetacrc SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE aure83tarjetacrc SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 283 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Tarjetas CRC}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $aure83id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $aure83id, $sDebug);
}
function f283_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 283;
	$bAudita[4] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_283 = 'lg/lg_283_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_283)) {
		$mensajes_283 = 'lg/lg_283_es.php';
	}
	require $mensajes_todas;
	require $mensajes_283;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$aure83idbitacora = numeros_validar($aParametros[1]);
	$aure83consec = numeros_validar($aParametros[2]);
	$aure83id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=283';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $aure83id . ' LIMIT 0, 1';
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
		$sWhere = 'aure83id=' . $aure83id . '';
		//$sWhere = 'aure83idbitacora=' . $aure83idbitacora . ' AND aure83consec=' . $aure83consec . '';
		$sSQL = 'DELETE FROM aure83tarjetacrc WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {283 Tarjetas CRC}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $aure83id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f283_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_283 = 'lg/lg_283_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_283)) {
		$mensajes_283 = 'lg/lg_283_es.php';
	}
	require $mensajes_todas;
	require $mensajes_283;
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
	$sBotones = '<input id="paginaf283" name="paginaf283" type="hidden" value="' . $pagina . '"/>
	<input id="lppf283" name="lppf283" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Bitacora, Consec, Id, Bithistoria, Tarea, Vigente, Nombreclase, Responsabilidades, Colaboradores, Tabla';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.aure83idbitacora, TB.aure83consec, TB.aure83id, T4.aure80consec, T5.aure81consec, TB.aure83vigente, TB.aure83nombreclase, TB.aure83responsabilidades, TB.aure83colaboradores, TB.aure83idtabla, TB.aure83idbithistoria, TB.aure83idtarea';
	$sConsulta = 'FROM aure83tarjetacrc AS TB, aure80historiaus AS T4, aure81tareaing AS T5 
	WHERE ' . $sSQLadd1 . ' TB.aure83idbitacora=' . $aure51id . ' AND TB.aure83idbithistoria=T4.aure80id AND TB.aure83idtarea=T5.aure81id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.aure83consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_283" name="consulta_283" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_283" name="titulos_283" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 283: ' . $sSQL . '<br>';
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
	<td><b>' . $ETI['aure83consec'] . '</b></td>
	<td><b>' . $ETI['aure83idbithistoria'] . '</b></td>
	<td><b>' . $ETI['aure83idtarea'] . '</b></td>
	<td><b>' . $ETI['aure83vigente'] . '</b></td>
	<td><b>' . $ETI['aure83nombreclase'] . '</b></td>
	<td><b>' . $ETI['aure83responsabilidades'] . '</b></td>
	<td><b>' . $ETI['aure83colaboradores'] . '</b></td>
	<td><b>' . $ETI['aure83idtabla'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf283', $registros, $lineastabla, $pagina, 'paginarf283()') . '
	' . html_lpp('lppf283', $lineastabla, 'paginarf283()') . '
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
		$et_aure83idbithistoria = $sPrefijo . cadena_notildes($filadet['aure80consec']) . $sSufijo;
		$et_aure83idtarea = $sPrefijo . cadena_notildes($filadet['aure81consec']) . $sSufijo;
		$et_aure83vigente = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['aure83vigente'] == 'S') {
			$et_aure83vigente = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_aure83nombreclase = $sPrefijo . cadena_notildes($filadet['aure83nombreclase']) . $sSufijo;
		$et_aure83responsabilidades = $sPrefijo . cadena_notildes($filadet['aure83responsabilidades']) . $sSufijo;
		$et_aure83colaboradores = $sPrefijo . cadena_notildes($filadet['aure83colaboradores']) . $sSufijo;
		$et_aure83idtabla = $sPrefijo . cadena_notildes($filadet['']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf283(' . $filadet['aure83id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_aure83consec . '</td>
		<td>' . $et_aure83idbithistoria . '</td>
		<td>' . $et_aure83idtarea . '</td>
		<td>' . $et_aure83vigente . '</td>
		<td>' . $et_aure83nombreclase . '</td>
		<td>' . $et_aure83responsabilidades . '</td>
		<td>' . $et_aure83colaboradores . '</td>
		<td>' . $et_aure83idtabla . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 283 Tarjetas CRC XAJAX 
function f283_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $aure83id, $sDebugGuardar) = f283_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f283_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f283detalle', 'innerHTML', $sdetalle);
		//if ($iAccion == 2) {
			//$objResponse->call('cargaridf283(' . $aure83id . ')');
			//} else {
			$objResponse->call('limpiaf283');
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
function f283_Traer($aParametros)
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
		$aure83idbitacora = numeros_validar($aParametros[1]);
		$aure83consec = numeros_validar($aParametros[2]);
		if (($aure83idbitacora != '') && ($aure83consec != '')) {
			$besta = true;
		}
	} else {
		$aure83id = $aParametros[103];
		if ((int)$aure83id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'aure83idbitacora=' . $aure83idbitacora . ' AND aure83consec=' . $aure83consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'aure83id=' . $aure83id . '';
		}
		$sSQL = 'SELECT * FROM aure83tarjetacrc WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 1);
		$aure83consec_nombre = '';
		$html_aure83consec = html_oculto('aure83consec', $fila['aure83consec'], $aure83consec_nombre);
		$objResponse->assign('div_aure83consec', 'innerHTML', $html_aure83consec);
		$aure83id_nombre = '';
		$html_aure83id = html_oculto('aure83id', $fila['aure83id'], $aure83id_nombre);
		$objResponse->assign('div_aure83id', 'innerHTML', $html_aure83id);
		$objResponse->assign('aure83idbithistoria', 'value', $fila['aure83idbithistoria']);
		$objResponse->assign('aure83idtarea', 'value', $fila['aure83idtarea']);
		$objResponse->assign('aure83vigente', 'value', $fila['aure83vigente']);
		$objResponse->assign('aure83nombreclase', 'value', $fila['aure83nombreclase']);
		$objResponse->assign('aure83responsabilidades', 'value', $fila['aure83responsabilidades']);
		$objResponse->assign('aure83colaboradores', 'value', $fila['aure83colaboradores']);
		$objResponse->assign('aure83idtabla', 'value', $fila['aure83idtabla']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina283', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('aure83consec', 'value', $aure83consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $aure83id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f283_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f283_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f283_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f283detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf283');
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
function f283_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f283_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f283detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f283_PintarLlaves($aParametros)
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
	$html_aure83consec = '<input id="aure83consec" name="aure83consec" type="text" value="" onchange="revisaf283()" class="cuatro" />';
	$html_aure83id = '<input id="aure83id" name="aure83id" type="hidden" value="" />';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_aure83consec', 'innerHTML', $html_aure83consec);
	$objResponse->assign('div_aure83id', 'innerHTML', $html_aure83id);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>