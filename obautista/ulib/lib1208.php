<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5b lunes, 23 de marzo de 2026
--- 1208 Destinatario
*/
function f1208_NombreTabla($iComplemento, $objDB) {
	$sError = '';
	$iBloque = numeros_validar($iComplemento);
	$sTabla1208 = 'masi08mensajedest_' . $iBloque;
	if (!$objDB->bexistetabla($sTabla1208)) {
		$sError = 'No ha sido posible determinar el origen de los datos.';
	}
	return array($sTabla1208, $sError);
}
function f1208_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 1208)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1208)) {
		$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1208;
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
	$masi08idmensaje = numeros_validar($valores[1]);
	$masi08idtercero = numeros_validar($valores[2]);
	$masi08idfecha = numeros_validar($valores[3]);
	$masi08id = numeros_validar($valores[4], true);
	$masi08idpoblacion = numeros_validar($valores[5]);
	$masi08fechaenvio = numeros_validar($valores[6]);
	$masi08horaenvio = numeros_validar($valores[7]);
	$masi08minenvio = numeros_validar($valores[8]);
	$masi08idsmtp = numeros_validar($valores[9]);
	/*
	if ($masi08idpoblacion == '') {
		$masi08idpoblacion = 0;
	}
	if ($masi08horaenvio == '') {
		$masi08horaenvio = 0;
	}
	if ($masi08minenvio == '') {
		$masi08minenvio = 0;
	}
	if ($masi08idsmtp == '') {
		$masi08idsmtp = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($masi08idsmtp == '') {
		$sError = $ERR['masi08idsmtp'] . $sSepara . $sError;
	}
	if ($masi08minenvio == '') {
		$sError = $ERR['masi08minenvio'] . $sSepara . $sError;
	}
	if ($masi08horaenvio == '') {
		$sError = $ERR['masi08horaenvio'] . $sSepara . $sError;
	}
	if ($masi08idpoblacion == '') {
		$sError = $ERR['masi08idpoblacion'] . $sSepara . $sError;
	}
	/*
	if ($masi08id == '') {
		$sError = $ERR['masi08id'] . $sSepara . $sError;
	}
	*/
	if (!fecha_NumValido($masi08idfecha)) {
		//$masi08idfecha = fecha_DiaMod();
		$sError = $ERR['masi08idfecha'] . $sSepara . $sError;
	}
	if ($masi08idtercero == 0) {
		$sError = $ERR['masi08idtercero'] . $sSepara . $sError;
	}
	if ($masi08idmensaje == '') {
		$sError = $ERR['masi08idmensaje'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($masi08idtercero, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		$bloque = numeros_validar($valores[97]);
		list($sTabla1208, $sError) = f1208_NombreTabla($bloque, $objDB);
	}
	if ($sError == '') {
		if ((int)$masi08id == 0) {
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sTabla1208 . ' WHERE masi08idmensaje=' . $masi08idmensaje . ' AND masi08idtercero="' . $masi08idtercero . '" AND masi08idfecha="' . $masi08idfecha . '"';
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
				$masi08id = tabla_consecutivo($sTabla1208, 'masi08id', '', $objDB);
				if ($masi08id == -1) {
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
			$masi08idsmtp = 0;
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$sCampos1208 = 'masi08idmensaje, masi08idtercero, masi08idfecha, masi08id, masi08idpoblacion, 
			masi08fechaenvio, masi08horaenvio, masi08minenvio, masi08idsmtp';
			$sValores1208 = '' . $masi08idmensaje . ', ' . $masi08idtercero . ', ' . $masi08idfecha . ', ' . $masi08id . ', ' . $masi08idpoblacion . ', 
			' . $masi08fechaenvio . ', ' . $masi08horaenvio . ', ' . $masi08minenvio . ', ' . $masi08idsmtp . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla1208 . ' (' . $sCampos1208 . ') VALUES (' . cadena_codificar($sValores1208) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla1208 . ' (' . $sCampos1208 . ') VALUES (' . $sValores1208 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 1208 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [1208].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $masi08id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo1208[1] = 'masi08idpoblacion';
			$scampo1208[2] = 'masi08fechaenvio';
			$scampo1208[3] = 'masi08idsmtp';
			$svr1208[1] = $masi08idpoblacion;
			$svr1208[2] = $masi08fechaenvio;
			$svr1208[3] = $masi08idsmtp;
			$iNumCampos = 3;
			$sWhere = 'masi08id=' . $masi08id . '';
			//$sWhere = 'masi08idmensaje=' . $masi08idmensaje . ' AND masi08idtercero="' . $masi08idtercero . '" AND masi08idfecha="' . $masi08idfecha . '"';
			$sSQL = 'SELECT * FROM ' . $sTabla1208 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo1208[$k]] != $svr1208[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo1208[$k] . '="' . $svr1208[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sTabla1208 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sTabla1208 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 1208 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Destinatario}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $masi08id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $masi08id, $sDebug);
}
function f1208_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 1208;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1208)) {
		$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1208;
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
	$masi08idmensaje = numeros_validar($aParametros[1]);
	$masi08idtercero = numeros_validar($aParametros[2]);
	$masi08id = numeros_validar($aParametros[4]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1208';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $masi08id . ' LIMIT 0, 1';
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
		list($sTabla1208, $sError) = f1208_NombreTabla($bloque, $objDB);
	}
	if ($sError == '') {
		//acciones previas
		$sWhere = 'masi08id=' . $masi08id . '';
		//$sWhere = 'masi08idmensaje=' . $masi08idmensaje . ' AND masi08idtercero="' . $masi08idtercero . '" AND masi08idfecha="' . $masi08idfecha . '"';
		$sSQL = 'DELETE FROM ' . $sTabla1208 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {1208 Destinatario}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $masi08id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f1208_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1208)) {
		$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1208;
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
	list($sTabla1208, $sLeyenda) = f1208_NombreTabla($bloque, $objDB);
	if ($sLeyenda == '') {
		list($sTabla1205, $sLeyenda) = f1205_NombreTabla($bloque, $objDB);
		list($sTabla1206, $sLeyendaH) = f1206_NombreTabla($bloque, $objDB);
		$sLeyenda = $sLeyenda . $sLeyendaH;
	}
	$sBotones = '<input id="paginaf1208" name="paginaf1208" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf1208" name="lppf1208" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$bAbierta = false;
	$bPuedeRetirar = false;
	$sSQL = 'SELECT masi05estado, masi05idproceso FROM ' . $sTabla1205 . ' WHERE masi05id=' . $masi05id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$bAbierta = f1205_GestionaPoblacion($fila['masi05idproceso'], $fila['masi05estado']);
		if ($fila['masi05estado'] == 0) {
			$bPuedeRetirar = true;
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
	$sTitulos = 'Mensaje, Tercero, Fecha, Id, Poblacion, Fechaenvio, Horaenvio, Minenvio, Smtp';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.masi08idmensaje, T2.unad11razonsocial AS C2_nombre, TB.masi08idfecha, TB.masi08id, T5.masi06consec, 
	TB.masi08fechaenvio, TB.masi08horaenvio, TB.masi08minenvio, TB.masi08idsmtp, TB.masi08idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.masi08idpoblacion';
	$sConsulta = 'FROM ' . $sTabla1208 . ' AS TB, unad11terceros AS T2, ' . $sTabla1206 . ' AS T5 
	WHERE ' . $sSQLadd1 . ' TB.masi08idmensaje=' . $masi05id . ' AND TB.masi08idtercero=T2.unad11id AND TB.masi08idpoblacion=T5.masi06id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY T2.unad11razonsocial, TB.masi08idfecha';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_1208" name="consulta_1208" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_1208" name="titulos_1208" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 1208: ' . $sSQL . '');
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
	$res = $res . '<th colspan="2"><b>' . $ETI['masi08idtercero'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi08idfecha'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi08idpoblacion'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi08fechaenvio'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi08horaenvio'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf1208', $registros, $lineastabla, $pagina, 'paginarf1208()') . '';
	$res = $res . '' . html_lpp('lppf1208', $lineastabla, 'paginarf1208()') . '';
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		$sLink2 = '';
		if (false) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_masi08idtercero_doc = '';
		$et_masi08idtercero_nombre = '';
		if ($filadet['masi08idtercero'] != 0) {
			$et_masi08idtercero_doc = $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo;
			$et_masi08idtercero_nombre = $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo;
		}
		$et_masi08idfecha = '';
		if ($filadet['masi08idfecha'] != 0) {
			$et_masi08idfecha = $sPrefijo . fecha_desdenumero($filadet['masi08idfecha']) . $sSufijo;
		}
		$et_masi08idpoblacion = $sPrefijo . cadena_notildes($filadet['masi06consec']) . $sSufijo;
		$et_masi08fechaenvio = '';
		if ($filadet['masi08fechaenvio'] != 0) {
			$et_masi08fechaenvio = $sPrefijo . fecha_desdenumero($filadet['masi08fechaenvio']) . $sSufijo;
		}
		$et_masi08horaenvio = $sPrefijo . html_TablaHoraMin($filadet['masi08horaenvio'], $filadet['masi08minenvio']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf1208(' . $filadet['masi08id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		if ($bPuedeRetirar) {
			$sLink2 = '<a href="javascript:retirarf1208(' . $filadet['masi08id'] . ')" class="lnkresalte">' . $ETI['lnk_rem'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_masi08idtercero_doc . '</td>';
		$res = $res . '<td>' . $et_masi08idtercero_nombre . '</td>';
		$res = $res . '<td>' . $et_masi08idfecha . '</td>';
		$res = $res . '<td>' . $et_masi08idpoblacion . '</td>';
		$res = $res . '<td>' . $et_masi08fechaenvio . '</td>';
		$res = $res . '<td>' . $et_masi08horaenvio . '</td>';
		$res = $res . '<td align="right">' . $sLink2 . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 1208 Destinatario XAJAX 
function f1208_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $masi08id, $sDebugGuardar) = f1208_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f1208_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f1208detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf1208(' . $masi08id . ')');
		} else {
		*/
		$objResponse->call('limpiaf1208');
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
function f1208_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1208)) {
		$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_1208;
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
		$masi08idmensaje = numeros_validar($aParametros[1]);
		$masi08idtercero = numeros_validar($aParametros[2]);
		$masi08idfecha = $aParametros[3];
		if (($masi08idmensaje != '') && ($masi08idtercero != '') && ($masi08idfecha != '')) {
			$besta = true;
		}
	} else {
		$masi08id = $aParametros[103];
		if ((int)$masi08id != 0) {
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
		list($sTabla1208, $sError) = f1208_NombreTabla($bloque, $objDB);
		if ($sError != '') {
			$besta = false;
		}
	}
	if ($besta) {
		$besta = false;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'masi08idmensaje=' . $masi08idmensaje . ' AND masi08idtercero=' . $masi08idtercero . ' AND masi08idfecha="' . $masi08idfecha . '"';
		} else {
			$sSQLcondi = $sSQLcondi . 'masi08id=' . $masi08id . '';
		}
		$sSQL = 'SELECT * FROM ' . $sTabla1208 . ' WHERE ' . $sSQLcondi;
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
		$masi08idtercero_id = (int)$fila['masi08idtercero'];
		$masi08idtercero_td = $APP->tipo_doc;
		$masi08idtercero_doc = '';
		$masi08idtercero_nombre = '';
		if ($masi08idtercero_id != 0) {
			list($masi08idtercero_nombre, $masi08idtercero_id, $masi08idtercero_td, $masi08idtercero_doc) = html_tercero($masi08idtercero_td, $masi08idtercero_doc, $masi08idtercero_id, 0, $objDB);
		}
		$html_masi08idtercero_llaves = html_DivTerceroV8('masi08idtercero', $masi08idtercero_td, $masi08idtercero_doc, true, $objDB, $objCombos, 2, 'Ingrese el documento');
		$objResponse->assign('masi08idtercero', 'value', $masi08idtercero_id);
		$objResponse->assign('div_masi08idtercero_llaves', 'innerHTML', $html_masi08idtercero_llaves);
		$objResponse->assign('div_masi08idtercero', 'innerHTML', $masi08idtercero_nombre);
		$masi08idfecha_nombre = '';
		$html_masi08idfecha = html_oculto('masi08idfecha', $fila['masi08idfecha'], $masi08idfecha_nombre);
		$objResponse->assign('div_masi08idfecha', 'innerHTML', $html_masi08idfecha);
		$masi08id_nombre = '';
		$html_masi08id = html_oculto('masi08id', $fila['masi08id'], $masi08id_nombre);
		$objResponse->assign('div_masi08id', 'innerHTML', $html_masi08id);
		$objResponse->assign('masi08idpoblacion', 'value', $fila['masi08idpoblacion']);
		$html_masi08fechaenvio = html_oculto('masi08fechaenvio', $fila['masi08fechaenvio'], fecha_desdenumero($fila['masi08fechaenvio']));
		$objResponse->assign('div_masi08fechaenvio', 'innerHTML', $html_masi08fechaenvio);
		$html_masi08horaenvio = html_HoraMin('masi08horaenvio', $fila['masi08horaenvio'], 'masi08minenvio', $fila['masi08minenvio'], true);
		$objResponse->assign('div_masi08horaenvio', 'innerHTML', $html_masi08horaenvio);
		$masi08idsmtp_nombre = '&nbsp;';
		//$masi08idsmtp_nombre = $amasi08idsmtp[$fila['masi08idsmtp']];
		if ($fila['masi08idsmtp'] != 0) {
			list($masi08idsmtp_nombre, $serror_det) = tabla_campoxid('unad69smtp', 'unad69titulo', 'unad69id', $fila['masi08idsmtp'], '{' . $ETI['msg_sindato'] . '}', $objDB);
			$masi08idsmtp_nombre = cadena_LimpiarXAJAX($masi08idsmtp_nombre);
		}
		$html_masi08idsmtp = html_oculto('masi08idsmtp', $fila['masi08idsmtp'], $masi08idsmtp_nombre);
		$objResponse->assign('div_masi08idsmtp', 'innerHTML', $html_masi08idsmtp);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1208', 'block')");
	} else {
		if ($paso == 1) {
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $masi08id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f1208_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f1208_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f1208_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f1208detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1208');
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
function f1208_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f1208_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1208detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1208_PintarLlaves($aParametros)
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
	$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1208)) {
		$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_1208;
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$masi08idtercero = 0;
	$masi08idtercero_rs = '';
	$html_masi08idtercero_llaves = html_DivTerceroV8('masi08idtercero', $APP->tipo_doc, '', false, $objDB, $objCombos, 2, $ETI['ing_doc']);
	$html_masi08idfecha = html_fecha('masi08idfecha', '', true, 'revisaf1208()');
	$html_masi08id = '<input id="masi08id" name="masi08id" type="hidden" value="" />';
	$et_masi08fechaenvio = '00/00/0000';
	$html_masi08fechaenvio = html_oculto('masi08fechaenvio', 0, $et_masi08fechaenvio);
	$html_masi08horaenvio = html_HoraMin('masi08horaenvio', fecha_hora(), 'masi08minenvio', fecha_minuto(), true);
	$html_masi08idsmtp = html_oculto('masi08idsmtp', $_REQUEST['masi08idsmtp'], '&nbsp;');
	$objResponse = new xajaxResponse();
	$objResponse->assign('masi08idtercero', 'value', $masi08idtercero);
	$objResponse->assign('div_masi08idtercero_llaves', 'innerHTML', $html_masi08idtercero_llaves);
	$objResponse->assign('div_masi08idtercero', 'innerHTML', $masi08idtercero_rs);
	$objResponse->assign('div_masi08idfecha', 'innerHTML', $html_masi08idfecha);
	$objResponse->assign('div_masi08id', 'innerHTML', $html_masi08id);
	$objResponse->assign('div_masi08fechaenvio', 'innerHTML', $html_masi08fechaenvio);
	$objResponse->assign('div_masi08horaenvio', 'innerHTML', $html_masi08horaenvio);
	$objResponse->assign('div_masi08idsmtp', 'innerHTML', $html_masi08idsmtp);
	$objResponse->call('$("#masi08idsmtp").chosen()');
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

