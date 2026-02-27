<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2942 Configura Anexos
*/
function f2942_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2942)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2942 = 'lg/lg_2942_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2942)) {
		$mensajes_2942 = 'lg/lg_2942_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2942;
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
	$visa42idtipo = numeros_validar($valores[1]);
	$visa42consec = numeros_validar($valores[2]);
	$visa42id = numeros_validar($valores[3], true);
	$visa42titulo = cadena_Validar(trim($valores[4]));
	$visa42descripcion = cadena_Validar(trim($valores[5]));
	$visa42activo = numeros_validar($valores[6]);
	$visa42orden = $valores[7];
	$visa42obligatorio = numeros_validar($valores[8]);
	$visa42tipodocumento = numeros_validar($valores[9]);
	/*
	if ($visa42activo == '') {
		$visa42activo = 0;
	}
	if ($visa42obligatorio == '') {
		$visa42obligatorio = 0;
	}
	if ($visa42tipodocumento == '') {
		$visa42tipodocumento = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa42tipodocumento == '') {
		$sError = $ERR['visa42tipodocumento'] . $sSepara . $sError;
	}
	if ($visa42obligatorio == '') {
		$sError = $ERR['visa42obligatorio'] . $sSepara . $sError;
	}
	if ($visa42orden == '') {
		$sError = $ERR['visa42orden'] . $sSepara . $sError;
	}
	if ($visa42activo == '') {
		$sError = $ERR['visa42activo'] . $sSepara . $sError;
	}
	if ($visa42descripcion == '') {
		$sError = $ERR['visa42descripcion'] . $sSepara . $sError;
	}
	if ($visa42titulo == '') {
		$sError = $ERR['visa42titulo'] . $sSepara . $sError;
	}
	/*
	if ($visa42id == '') {
		$sError = $ERR['visa42id'] . $sSepara . $sError;
	}
	*/
	if ($visa42idtipo == '') {
		$sError = $ERR['visa42idtipo'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$visa42id == 0) {
			if ((int)$visa42consec == 0) {
				$visa42consec = tabla_consecutivo('visa42convanexo', 'visa42consec', 'visa42idtipo=' . $visa42idtipo . '', $objDB);
				if ($visa42consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa42consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM visa42convanexo WHERE visa42idtipo=' . $visa42idtipo . ' AND visa42consec=' . $visa42consec . '';
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
				$visa42id = tabla_consecutivo('visa42convanexo', 'visa42id', '', $objDB);
				if ($visa42id == -1) {
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
			$sCampos2942 = 'visa42idtipo, visa42consec, visa42id, visa42titulo, visa42descripcion, 
			visa42activo, visa42orden, visa42obligatorio, visa42tipodocumento';
			$sValores2942 = '' . $visa42idtipo . ', ' . $visa42consec . ', ' . $visa42id . ', "' . $visa42titulo . '", "' . $visa42descripcion . '", 
			' . $visa42activo . ', "' . $visa42orden . '", ' . $visa42obligatorio . ', ' . $visa42tipodocumento . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa42convanexo (' . $sCampos2942 . ') VALUES (' . cadena_codificar($sValores2942) . ');';
			} else {
				$sSQL = 'INSERT INTO visa42convanexo (' . $sCampos2942 . ') VALUES (' . $sValores2942 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2942 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2942].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa42id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2942[1] = 'visa42titulo';
			$scampo2942[2] = 'visa42descripcion';
			$scampo2942[3] = 'visa42activo';
			$scampo2942[4] = 'visa42orden';
			$scampo2942[5] = 'visa42obligatorio';
			$scampo2942[6] = 'visa42tipodocumento';
			$svr2942[1] = $visa42titulo;
			$svr2942[2] = $visa42descripcion;
			$svr2942[3] = $visa42activo;
			$svr2942[4] = $visa42orden;
			$svr2942[5] = $visa42obligatorio;
			$svr2942[6] = $visa42tipodocumento;
			$iNumCampos = 6;
			$sWhere = 'visa42id=' . $visa42id . '';
			//$sWhere = 'visa42idtipo=' . $visa42idtipo . ' AND visa42consec=' . $visa42consec . '';
			$sSQL = 'SELECT * FROM visa42convanexo WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2942[$k]] != $svr2942[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2942[$k] . '="' . $svr2942[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE visa42convanexo SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE visa42convanexo SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2942 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Configura Anexos}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa42id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa42id, $sDebug);
}
function f2942_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2942;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2942 = 'lg/lg_2942_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2942)) {
		$mensajes_2942 = 'lg/lg_2942_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2942;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa42idtipo = numeros_validar($aParametros[1]);
	$visa42consec = numeros_validar($aParametros[2]);
	$visa42id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2942';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa42id . ' LIMIT 0, 1';
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
		$sWhere = 'visa42id=' . $visa42id . '';
		//$sWhere = 'visa42idtipo=' . $visa42idtipo . ' AND visa42consec=' . $visa42consec . '';
		$sSQL = 'DELETE FROM visa42convanexo WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2942 Configura Anexos}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa42id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2942_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2942 = 'lg/lg_2942_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2942)) {
		$mensajes_2942 = 'lg/lg_2942_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2942;
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
	$visa34id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM visa34convtipo WHERE visa34id=' . $visa34id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2942" name="paginaf2942" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2942" name="lppf2942" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Consec, Id, Titulo, Activo, Obligatorio, Tipodocumento';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa42consec, TB.visa42id, TB.visa42titulo, TB.visa42activo, TB.visa42obligatorio, 
	T6.gedo02nombre, TB.visa42tipodocumento';
	$sConsulta = 'FROM visa42convanexo AS TB, gedo02tipodoc AS T6 
	WHERE ' . $sSQLadd1 . ' TB.visa42tipodocumento=T6.gedo02id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa42consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2942" name="consulta_2942" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2942" name="titulos_2942" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2942: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['visa42consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa42titulo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa42activo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa42obligatorio'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa42tipodocumento'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2942', $registros, $lineastabla, $pagina, 'paginarf2942()') . '';
	$res = $res . '' . html_lpp('lppf2942', $lineastabla, 'paginarf2942()') . '';
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
		$et_visa42consec = $sPrefijo . $filadet['visa42consec'] . $sSufijo;
		$et_visa42titulo = $sPrefijo . cadena_notildes($filadet['visa42titulo']) . $sSufijo;
		$et_visa42activo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa42activo'] == 0) {
			$et_visa42activo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_visa42obligatorio = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa42obligatorio'] == 0) {
			$et_visa42obligatorio = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_visa42tipodocumento = $sPrefijo . cadena_notildes($filadet['gedo02nombre']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2942(' . $filadet['visa42id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa42consec . '</td>';
		$res = $res . '<td>' . $et_visa42titulo . '</td>';
		$res = $res . '<td>' . $et_visa42activo . '</td>';
		$res = $res . '<td>' . $et_visa42obligatorio . '</td>';
		$res = $res . '<td>' . $et_visa42tipodocumento . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2942 Configura Anexos XAJAX 
function f2942_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $visa42id, $sDebugGuardar) = f2942_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2942_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2942detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2942(' . $visa42id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2942');
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
function f2942_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2942 = 'lg/lg_2942_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2942)) {
		$mensajes_2942 = 'lg/lg_2942_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2942;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa42idtipo = numeros_validar($aParametros[1]);
		$visa42consec = numeros_validar($aParametros[2]);
		if (($visa42idtipo != '') && ($visa42consec != '')) {
			$besta = true;
		}
	} else {
		$visa42id = $aParametros[103];
		if ((int)$visa42id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'visa42idtipo=' . $visa42idtipo . ' AND visa42consec=' . $visa42consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa42id=' . $visa42id . '';
		}
		$sSQL = 'SELECT * FROM visa42convanexo WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 2);
		$visa42consec_nombre = '';
		$html_visa42consec = html_oculto('visa42consec', $fila['visa42consec'], $visa42consec_nombre);
		$objResponse->assign('div_visa42consec', 'innerHTML', $html_visa42consec);
		$visa42id_nombre = '';
		$html_visa42id = html_oculto('visa42id', $fila['visa42id'], $visa42id_nombre);
		$objResponse->assign('div_visa42id', 'innerHTML', $html_visa42id);
		$objResponse->assign('visa42titulo', 'value', cadena_LimpiarXAJAX($fila['visa42titulo']));
		$objResponse->assign('visa42descripcion', 'value', cadena_LimpiarXAJAX($fila['visa42descripcion']));
		$objResponse->assign('visa42activo', 'value', $fila['visa42activo']);
		$objResponse->assign('visa42orden', 'value', $fila['visa42orden']);
		$objResponse->assign('visa42obligatorio', 'value', $fila['visa42obligatorio']);
		$objResponse->assign('visa42tipodocumento', 'value', $fila['visa42tipodocumento']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2942', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('visa42consec', 'value', $visa42consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa42id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2942_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2942_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2942_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2942detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2942');
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
function f2942_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2942_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2942detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2942_PintarLlaves($aParametros)
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
	$mensajes_2942 = 'lg/lg_2942_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2942)) {
		$mensajes_2942 = 'lg/lg_2942_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2942;
	$iPiel = iDefinirPiel($APP, 2);
	$html_visa42consec = '<input id="visa42consec" name="visa42consec" type="text" value="" onchange="revisaf2942()" class="cuatro" />';
	$html_visa42id = '<input id="visa42id" name="visa42id" type="hidden" value="" />';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa42consec', 'innerHTML', $html_visa42consec);
	$objResponse->assign('div_visa42id', 'innerHTML', $html_visa42id);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

