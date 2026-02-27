<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2944 Anotaciones
*/
function f2944_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2944)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2944 = 'lg/lg_2944_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2944)) {
		$mensajes_2944 = 'lg/lg_2944_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2944;
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
	$visa44idinscripcion = numeros_validar($valores[1]);
	$visa44consec = numeros_validar($valores[2]);
	$visa44id = numeros_validar($valores[3], true);
	$visa44alcance = numeros_validar($valores[4]);
	$visa44nota = cadena_Validar(trim($valores[5]));
	$visa44usuario = numeros_validar($valores[6]);
	$visa44fecha = numeros_validar($valores[7]);
	$visa44hora = numeros_validar($valores[8]);
	$visa44minuto = numeros_validar($valores[9]);
	/*
	if ($visa44alcance == '') {
		$visa44alcance = 0;
	}
	if ($visa44hora == '') {
		$visa44hora = 0;
	}
	if ($visa44minuto == '') {
		$visa44minuto = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa44minuto == '') {
		$sError = $ERR['visa44minuto'] . $sSepara . $sError;
	}
	if ($visa44hora == '') {
		$sError = $ERR['visa44hora'] . $sSepara . $sError;
	}
	if ($visa44usuario == 0) {
		$sError = $ERR['visa44usuario'] . $sSepara . $sError;
	}
	if ($visa44nota == '') {
		$sError = $ERR['visa44nota'] . $sSepara . $sError;
	}
	if ($visa44alcance == '') {
		$sError = $ERR['visa44alcance'] . $sSepara . $sError;
	}
	/*
	if ($visa44id == '') {
		$sError = $ERR['visa44id'] . $sSepara . $sError;
	}
	*/
	if ($visa44idinscripcion == '') {
		$sError = $ERR['visa44idinscripcion'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($visa44usuario, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$visa44id == 0) {
			if ((int)$visa44consec == 0) {
				$visa44consec = tabla_consecutivo('visa44anotaciones', 'visa44consec', 'visa44idinscripcion=' . $visa44idinscripcion . '', $objDB);
				if ($visa44consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa44consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM visa44anotaciones WHERE visa44idinscripcion=' . $visa44idinscripcion . ' AND visa44consec=' . $visa44consec . '';
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
				$visa44id = tabla_consecutivo('visa44anotaciones', 'visa44id', '', $objDB);
				if ($visa44id == -1) {
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
		//Si el campo visa44nota permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$visa44nota = str_replace('"', '\"', $visa44nota);
		$visa44nota = str_replace('"', '\"', $visa44nota);
		if ($bInserta) {
			$sCampos2944 = 'visa44idinscripcion, visa44consec, visa44id, visa44alcance, visa44nota, 
			visa44usuario, visa44fecha, visa44hora, visa44minuto';
			$sValores2944 = '' . $visa44idinscripcion . ', ' . $visa44consec . ', ' . $visa44id . ', ' . $visa44alcance . ', "' . $visa44nota . '", 
			' . $visa44usuario . ', ' . $visa44fecha . ', ' . $visa44hora . ', ' . $visa44minuto . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa44anotaciones (' . $sCampos2944 . ') VALUES (' . cadena_codificar($sValores2944) . ');';
			} else {
				$sSQL = 'INSERT INTO visa44anotaciones (' . $sCampos2944 . ') VALUES (' . $sValores2944 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2944 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2944].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa44id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2944[1] = 'visa44alcance';
			$scampo2944[2] = 'visa44nota';
			$scampo2944[3] = 'visa44fecha';
			$svr2944[1] = $visa44alcance;
			$svr2944[2] = $visa44nota;
			$svr2944[3] = $visa44fecha;
			$iNumCampos = 3;
			$sWhere = 'visa44id=' . $visa44id . '';
			//$sWhere = 'visa44idinscripcion=' . $visa44idinscripcion . ' AND visa44consec=' . $visa44consec . '';
			$sSQL = 'SELECT * FROM visa44anotaciones WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2944[$k]] != $svr2944[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2944[$k] . '="' . $svr2944[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE visa44anotaciones SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE visa44anotaciones SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2944 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Anotaciones}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa44id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa44id, $sDebug);
}
function f2944_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2944;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2944 = 'lg/lg_2944_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2944)) {
		$mensajes_2944 = 'lg/lg_2944_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2944;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa44idinscripcion = numeros_validar($aParametros[1]);
	$visa44consec = numeros_validar($aParametros[2]);
	$visa44id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2944';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa44id . ' LIMIT 0, 1';
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
		$sWhere = 'visa44id=' . $visa44id . '';
		//$sWhere = 'visa44idinscripcion=' . $visa44idinscripcion . ' AND visa44consec=' . $visa44consec . '';
		$sSQL = 'DELETE FROM visa44anotaciones WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2944 Anotaciones}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa44id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2944_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2944 = 'lg/lg_2944_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2944)) {
		$mensajes_2944 = 'lg/lg_2944_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2944;
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
	$visa40id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	$bAbierta = false;
	$sSQL = 'SELECT visa40estado FROM visa40inscripcion WHERE visa40id=' . $visa40id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['visa40estado'] != 'S') {
			$bAbierta = true;
		}
	}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2944" name="paginaf2944" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2944" name="lppf2944" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Consec, Id, Usuario, Fecha, Alcance';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa44consec, TB.visa44id, T3.unad11razonsocial AS C3_nombre, TB.visa44fecha, TB.visa44alcance, TB.visa44usuario, T3.unad11tipodoc AS C3_td, T3.unad11doc AS C3_doc';
	$sConsulta = 'FROM visa44anotaciones AS TB, unad11terceros AS T3 
	WHERE ' . $sSQLadd1 . ' TB.visa44usuario=T3.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa44consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2944" name="consulta_2944" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2944" name="titulos_2944" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2944: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['visa44consec'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['visa44usuario'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa44fecha'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa44alcance'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2944', $registros, $lineastabla, $pagina, 'paginarf2944()') . '';
	$res = $res . '' . html_lpp('lppf2944', $lineastabla, 'paginarf2944()') . '';
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
		$et_visa44consec = $sPrefijo . $filadet['visa44consec'] . $sSufijo;
		$et_visa44usuario_doc = '';
		$et_visa44usuario_nombre = '';
		if ($filadet['visa44usuario'] != 0) {
			$et_visa44usuario_doc = $sPrefijo . $filadet['C3_td'] . ' ' . $filadet['C3_doc'] . $sSufijo;
			$et_visa44usuario_nombre = $sPrefijo . cadena_notildes($filadet['C3_nombre']) . $sSufijo;
		}
		$et_visa44fecha = '';
		if ($filadet['visa44fecha'] != 0) {
			$et_visa44fecha = $sPrefijo . fecha_desdenumero($filadet['visa44fecha']) . $sSufijo;
		}
		$et_visa44alcance = $sPrefijo . formato_numero($filadet['visa44alcance']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2944(' . $filadet['visa44id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa44consec . '</td>';
		$res = $res . '<td>' . $et_visa44usuario_doc . '</td>';
		$res = $res . '<td>' . $et_visa44usuario_nombre . '</td>';
		$res = $res . '<td>' . $et_visa44fecha . '</td>';
		$res = $res . '<td>' . $et_visa44alcance . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2944 Anotaciones XAJAX 
function f2944_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $visa44id, $sDebugGuardar) = f2944_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2944_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2944detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2944(' . $visa44id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2944');
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
function f2944_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2944 = 'lg/lg_2944_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2944)) {
		$mensajes_2944 = 'lg/lg_2944_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2944;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa44idinscripcion = numeros_validar($aParametros[1]);
		$visa44consec = numeros_validar($aParametros[2]);
		if (($visa44idinscripcion != '') && ($visa44consec != '')) {
			$besta = true;
		}
	} else {
		$visa44id = $aParametros[103];
		if ((int)$visa44id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'visa44idinscripcion=' . $visa44idinscripcion . ' AND visa44consec=' . $visa44consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa44id=' . $visa44id . '';
		}
		$sSQL = 'SELECT * FROM visa44anotaciones WHERE ' . $sSQLcondi;
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
		$visa44usuario_id = (int)$fila['visa44usuario'];
		$visa44usuario_td = $APP->tipo_doc;
		$visa44usuario_doc = '';
		$visa44usuario_nombre = '';
		if ($visa44usuario_id != 0) {
			list($visa44usuario_nombre, $visa44usuario_id, $visa44usuario_td, $visa44usuario_doc) = html_tercero($visa44usuario_td, $visa44usuario_doc, $visa44usuario_id, 0, $objDB);
		}
		$visa44consec_nombre = '';
		$html_visa44consec = html_oculto('visa44consec', $fila['visa44consec'], $visa44consec_nombre);
		$objResponse->assign('div_visa44consec', 'innerHTML', $html_visa44consec);
		$visa44id_nombre = '';
		$html_visa44id = html_oculto('visa44id', $fila['visa44id'], $visa44id_nombre);
		$objResponse->assign('div_visa44id', 'innerHTML', $html_visa44id);
		$objResponse->assign('visa44alcance', 'value', $fila['visa44alcance']);
		$objResponse->assign('visa44nota', 'value', cadena_LimpiarXAJAX($fila['visa44nota']));
		$bOculto = true;
		$html_visa44usuario_llaves = html_DivTerceroV8('visa44usuario', $visa44usuario_td, $visa44usuario_doc, $bOculto, $objDB, $objCombos, $visa44usuario_id, $ETI['ing_doc']);
		$objResponse->assign('visa44usuario', 'value', $visa44usuario_id);
		$objResponse->assign('div_visa44usuario_llaves', 'innerHTML', $html_visa44usuario_llaves);
		$objResponse->assign('div_visa44usuario', 'innerHTML', $visa44usuario_nombre);
		$html_visa44fecha = html_oculto('visa44fecha', $fila['visa44fecha'], fecha_desdenumero($fila['visa44fecha']));
		$objResponse->assign('div_visa44fecha', 'innerHTML', $html_visa44fecha);
		$html_visa44hora = html_HoraMin('visa44hora', $fila['visa44hora'], 'visa44minuto', $fila['visa44minuto'], true);
		$objResponse->assign('div_visa44hora', 'innerHTML', $html_visa44hora);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2944', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('visa44consec', 'value', $visa44consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa44id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2944_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2944_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2944_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2944detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2944');
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
function f2944_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2944_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2944detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2944_PintarLlaves($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	/*
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	*/
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2944 = 'lg/lg_2944_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2944)) {
		$mensajes_2944 = 'lg/lg_2944_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2944;
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$html_visa44consec = '<input id="visa44consec" name="visa44consec" type="text" value="" onchange="revisaf2944()" class="cuatro" />';
	$html_visa44id = '<input id="visa44id" name="visa44id" type="hidden" value="" />';
	list($visa44usuario_rs, $visa44usuario, $visa44usuario_td, $visa44usuario_doc) = html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_visa44usuario_llaves = html_DivTerceroV8('visa44usuario', $visa44usuario_td, $visa44usuario_doc, true, $objDB, $objCombos, 0, $ETI['ing_doc']);
	$et_visa44fecha = '00/00/0000';
	$html_visa44fecha = html_oculto('visa44fecha', 0, $et_visa44fecha);
	$html_visa44hora = html_HoraMin('visa44hora', fecha_hora(), 'visa44minuto', fecha_minuto(), true);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa44consec', 'innerHTML', $html_visa44consec);
	$objResponse->assign('div_visa44id', 'innerHTML', $html_visa44id);
	$objResponse->assign('visa44usuario', 'value', $visa44usuario);
	$objResponse->assign('div_visa44usuario_llaves', 'innerHTML', $html_visa44usuario_llaves);
	$objResponse->assign('div_visa44usuario', 'innerHTML', $visa44usuario_rs);
	$objResponse->assign('div_visa44fecha', 'innerHTML', $html_visa44fecha);
	$objResponse->assign('div_visa44hora', 'innerHTML', $html_visa44hora);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

