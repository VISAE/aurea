<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5b lunes, 23 de marzo de 2026
--- 1207 Anexo
*/
function f1207_NombreTabla($iComplemento, $objDB) {
	$sError = '';
	$iBloque = numeros_validar($iComplemento);
	$sTabla1207 = 'masi07mensajeanexo_' . $iBloque;
	if (!$objDB->bexistetabla($sTabla1207)) {
		$sError = 'No ha sido posible determinar el origen de los datos.';
	}
	return array($sTabla1207, $sError);
}
function f1207_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 1207)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1207)) {
		$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1207;
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
	$masi07idmensaje = numeros_validar($valores[1]);
	$masi07consec = numeros_validar($valores[2]);
	$masi07id = numeros_validar($valores[3], true);
	$masi07titulo = cadena_Validar(trim($valores[4]));
	/*
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($masi07titulo == '') {
		$sError = $ERR['masi07titulo'] . $sSepara . $sError;
	}
	/*
	if ($masi07id == '') {
		$sError = $ERR['masi07id'] . $sSepara . $sError;
	}
	*/
	if ($masi07idmensaje == '') {
		$sError = $ERR['masi07idmensaje'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		$bloque = numeros_validar($valores[97]);
		list($sTabla1207, $sError) = f1207_NombreTabla($bloque, $objDB);
	}
	if ($sError == '') {
		if ((int)$masi07id == 0) {
			if ((int)$masi07consec == 0) {
				$masi07consec = tabla_consecutivo($sTabla1207, 'masi07consec', 'masi07idmensaje=' . $masi07idmensaje . '', $objDB);
				if ($masi07consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'masi07consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sTabla1207 . ' WHERE masi07idmensaje=' . $masi07idmensaje . ' AND masi07consec=' . $masi07consec . '';
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
				$masi07id = tabla_consecutivo($sTabla1207, 'masi07id', '', $objDB);
				if ($masi07id == -1) {
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
			$masi07idorigen = 0;
			$masi07idarchivo = 0;
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$sCampos1207 = 'masi07idmensaje, masi07consec, masi07id, masi07titulo, masi07idorigen, 
			masi07idarchivo';
			$sValores1207 = '' . $masi07idmensaje . ', ' . $masi07consec . ', ' . $masi07id . ', "' . $masi07titulo . '", ' . $masi07idorigen . ', 
			' . $masi07idarchivo . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla1207 . ' (' . $sCampos1207 . ') VALUES (' . cadena_codificar($sValores1207) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla1207 . ' (' . $sCampos1207 . ') VALUES (' . $sValores1207 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 1207 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [1207].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $masi07id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo1207[1] = 'masi07titulo';
			$svr1207[1] = $masi07titulo;
			$iNumCampos = 1;
			$sWhere = 'masi07id=' . $masi07id . '';
			//$sWhere = 'masi07idmensaje=' . $masi07idmensaje . ' AND masi07consec=' . $masi07consec . '';
			$sSQL = 'SELECT * FROM ' . $sTabla1207 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo1207[$k]] != $svr1207[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo1207[$k] . '="' . $svr1207[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sTabla1207 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sTabla1207 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 1207 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Anexo}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $masi07id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $masi07id, $sDebug);
}
function f1207_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 1207;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1207)) {
		$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1207;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[97]) == 0) {
		$aParametros[97] = '';
	}
	$bloque = numeros_validar($aParametros[97]);
	$masi07idmensaje = numeros_validar($aParametros[1]);
	$masi07consec = numeros_validar($aParametros[2]);
	$masi07id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1207';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $masi07id . ' LIMIT 0, 1';
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
		list($sTabla1207, $sError) = f1207_NombreTabla($bloque, $objDB);
	}
	if ($sError == '') {
		//acciones previas
		$sWhere = 'masi07id=' . $masi07id . '';
		//$sWhere = 'masi07idmensaje=' . $masi07idmensaje . ' AND masi07consec=' . $masi07consec . '';
		$sSQL = 'DELETE FROM ' . $sTabla1207 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {1207 Anexo}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $masi07id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f1207_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1207)) {
		$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1207;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[97]) == 0) {
		$aParametros[97] = '';
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
	$bloque = numeros_validar($aParametros[97]);
	$idTercero = numeros_validar($aParametros[100]);
	$sDebug = '';
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$masi05id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	list($sTabla1207, $sLeyenda) = f1207_NombreTabla($bloque, $objDB);
	if ($sLeyenda == '') {
		list($sTabla1205, $sLeyenda) = f1205_NombreTabla($bloque, $objDB);
	}
	$sBotones = '<input id="paginaf1207" name="paginaf1207" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf1207" name="lppf1207" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$bAbierta = false;
	$sSQL = 'SELECT masi05estado FROM ' . $sTabla1205 . ' WHERE masi05id=' . $masi05id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['masi05estado'] == 0) {
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
	$sTitulos = 'Mensaje, Consec, Id, Titulo, Origen, Archivo';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.masi07idmensaje, TB.masi07consec, TB.masi07id, TB.masi07titulo, TB.masi07idorigen, 
	TB.masi07idarchivo';
	$sConsulta = 'FROM ' . $sTabla1207 . ' AS TB 
	WHERE ' . $sSQLadd1 . ' TB.masi07idmensaje=' . $masi05id . ' ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.masi07consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_1207" name="consulta_1207" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_1207" name="titulos_1207" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 1207: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['masi07consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi07titulo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi07idarchivo'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf1207', $registros, $lineastabla, $pagina, 'paginarf1207()') . '';
	$res = $res . '' . html_lpp('lppf1207', $lineastabla, 'paginarf1207()') . '';
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
		$et_masi07consec = $sPrefijo . $filadet['masi07consec'] . $sSufijo;
		$et_masi07titulo = $sPrefijo . cadena_notildes($filadet['masi07titulo']) . $sSufijo;
		$et_masi07idarchivo = '';
		if ($filadet['masi07idarchivo'] != 0) {
			//$et_masi07idarchivo = '<img src="verarchivo.php?cont=' . $filadet['masi07idorigen'] . '&id=' . $filadet['masi07idarchivo'] . '&maxx=150"/>';
			$et_masi07idarchivo = html_lnkarchivo((int)$filadet['masi07idorigen'], (int)$filadet['masi07idarchivo']);
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf1207(' . $filadet['masi07id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_masi07consec . '</td>';
		$res = $res . '<td>' . $et_masi07titulo . '</td>';
		$res = $res . '<td>' . $et_masi07idarchivo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 1207 Anexo XAJAX 
function elimina_archivo_masi07idarchivo($idPadre, $bloque, $bDebug = false)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sError = '';
	$sDebug = '';
	$bPuedeEliminar = true;
	// Definir las condiciones para que se pueda eliminar y el mensaje de error que se debe presentar
	if ($bPuedeEliminar) {
		list($sTabla1207, $sError) = f1207_NombreTabla($bloque, $objDB);
		archivo_eliminar($sTabla1207, 'masi07id', 'masi07idorigen', 'masi07idarchivo', $idPadre, $objDB);
		$aParametros[0] = $idPadre;
		$aParametros[97] = $bloque;
		list($sDetalle, $sDebugTabla) = f1207_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_masi07idarchivo");
		$objResponse->assign('div_f1207detalle', 'innerHTML', $sDetalle);
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0);");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1207_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $masi07id, $sDebugGuardar) = f1207_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f1207_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f1207detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf1207(' . $masi07id . ')');
		} else {
		*/
		$objResponse->call('limpiaf1207');
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
function f1207_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if (isset($aParametros[97]) == 0) {
		$aParametros[97] = '';
	}
	$bloque = numeros_validar($aParametros[97]);
	if ($paso == 1) {
		$masi07idmensaje = numeros_validar($aParametros[1]);
		$masi07consec = numeros_validar($aParametros[2]);
		if (($masi07idmensaje != '') && ($masi07consec != '')) {
			$besta = true;
		}
	} else {
		$masi07id = $aParametros[103];
		if ((int)$masi07id != 0) {
			$besta = true;
		}
	}
	if ($besta) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		list($sTabla1207, $sError) = f1207_NombreTabla($bloque, $objDB);
		if ($sError != '') {
			$besta = false;
		}
	}
	if ($besta) {
		$besta = false;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'masi07idmensaje=' . $masi07idmensaje . ' AND masi07consec=' . $masi07consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'masi07id=' . $masi07id . '';
		}
		$sSQL = 'SELECT * FROM ' . $sTabla1207 . ' WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 2);
		$masi07consec_nombre = '';
		$html_masi07consec = html_oculto('masi07consec', $fila['masi07consec'], $masi07consec_nombre);
		$objResponse->assign('div_masi07consec', 'innerHTML', $html_masi07consec);
		$masi07id_nombre = '';
		$html_masi07id = html_oculto('masi07id', $fila['masi07id'], $masi07id_nombre);
		$objResponse->assign('div_masi07id', 'innerHTML', $html_masi07id);
		$objResponse->assign('masi07titulo', 'value', cadena_LimpiarXAJAX($fila['masi07titulo']));
		$objResponse->assign('masi07idorigen', 'value', $fila['masi07idorigen']);
		$idorigen = (int)$fila['masi07idorigen'];
		$objResponse->assign('masi07idarchivo', 'value', $fila['masi07idarchivo']);
		$objResponse->assign('masi07idarchivo_up', 'value', html_lnkupload(1207, $fila['masi07id'], '_' . $bloque));
		$sMuestraAnexar = 'block';
		$sMuestraEliminar = 'none';
		$sHTMLArchivo = html_lnkarchivo($idorigen, (int)$fila['masi07idarchivo']);
		if ((int)$fila['masi07idarchivo'] != 0) {
			$sMuestraEliminar = 'block';
			//Aqui puede poner validaciones al eliminar - Si no se puede eliminar reversar el mostrado.
		}
		$objResponse->assign('div_masi07idarchivo', 'innerHTML', $sHTMLArchivo);
		$objResponse->call("verboton('banexamasi07idarchivo', '".$sMuestraAnexar."')");
		$objResponse->call("verboton('beliminamasi07idarchivo', '".$sMuestraEliminar."')");
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1207', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('masi07consec', 'value', $masi07consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $masi07id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f1207_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f1207_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f1207_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f1207detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1207');
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
function f1207_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f1207_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1207detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1207_PintarLlaves($aParametros)
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
	$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1207)) {
		$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_1207;
	$iPiel = iDefinirPiel($APP, 2);
	$html_masi07consec = '<input id="masi07consec" name="masi07consec" type="text" value="" onchange="revisaf1207()" class="cuatro" />';
	$html_masi07id = '<input id="masi07id" name="masi07id" type="hidden" value="" />';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_masi07consec', 'innerHTML', $html_masi07consec);
	$objResponse->assign('div_masi07id', 'innerHTML', $html_masi07id);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

