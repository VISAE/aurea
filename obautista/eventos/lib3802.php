<?php
/*
--- © Cristhiam Dario Silva Chavez - UNAD - 2022 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.4b viernes, 21 de octubre de 2022
--- 3802 Jornadas
*/
function f3802_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3802;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3802 = 'lg/lg_3802_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3802)) {
		$mensajes_3802 = 'lg/lg_3802_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3802;
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
	$cipa02idoferta = numeros_validar($valores[1]);
	$cipa02consec = numeros_validar($valores[2]);
	$cipa02id = numeros_validar($valores[3], true);
	$cipa02forma = numeros_validar($valores[4]);
	$cipa02lugar = htmlspecialchars(trim($valores[5]));
	$cipa02link = htmlspecialchars(trim($valores[6]));
	$cipa02fecha = numeros_validar($valores[7]);
	$cipa02horaini = numeros_validar($valores[8]);
	$cipa02minini = numeros_validar($valores[9]);
	$cipa02horafin = numeros_validar($valores[10]);
	$cipa02minfin = numeros_validar($valores[11]);
	$cipa02numinscritos = numeros_validar($valores[12]);
	$cipa02numparticipantes = numeros_validar($valores[13]);
	$cipa02tematica = htmlspecialchars(trim($valores[14]));
	if ($cipa02minini == '') {
		$cipa02minini = 0;
	}
	if ($cipa02minfin == '') {
		$cipa02minfin = 0;
	}
	/*
	if ($cipa02forma == '') {
		$cipa02forma = 0;
	}
	if ($cipa02horaini == '') {
		$cipa02horaini = 0;
	}
	if ($cipa02horafin == '') {
		$cipa02horafin = 0;
	}
	if ($cipa02numinscritos == '') {
		$cipa02numinscritos = 0;
	}
	if ($cipa02numparticipantes == '') {
		$cipa02numparticipantes = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	/*
	if ($cipa02minfin == '') {
		$sError = $ERR['cipa02minfin'] . $sSepara.$sError;
	}
	*/
	if ($cipa02horafin == '') {
		$sError = $ERR['cipa02horafin'] . $sSepara.$sError;
	}
	/*
	if ($cipa02minini == '') {
		$sError = $ERR['cipa02minini'] . $sSepara.$sError;
	}
	*/
	if ($cipa02horaini == '') {
		$sError = $ERR['cipa02horaini'] . $sSepara.$sError;
	}
	if (!fecha_numvalido($cipa02fecha)) {
		//$cipa02fecha = fecha_DiaMod();
		$sError = $ERR['cipa02fecha'] . $sSepara . $sError;
	}
	if ($cipa02forma == 0) {
		if ($cipa02link == '') {
			$sError = $ERR['cipa02link'] . $sSepara.$sError;
		}
	} else {
		if ($cipa02lugar == '') {
			$sError = $ERR['cipa02lugar'] . $sSepara.$sError;
		}
	}
	/*
	if ($cipa02forma == '') {
		$sError = $ERR['cipa02forma'] . $sSepara.$sError;
	}
	*/
	/*
	if ($cipa02id == '') {
		$sError = $ERR['cipa02id'] . $sSepara . $sError;
	}
	*/
	if ($sError == '') {
		//Que la hora final sea mayor la inicial
		$iMinIni = ($cipa02horaini * 60) + $cipa02minini;
		$iMinFin = ($cipa02horafin * 60) + $cipa02minfin;
		if ($iMinFin < $iMinIni) {
			$sError = 'La hora final debe ser superior a la hora inicial.';
		}
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$cipa02id == 0) {
			if ((int)$cipa02consec == 0) {
				$cipa02consec = tabla_consecutivo('cipa02jornada', 'cipa02consec', 'cipa02idoferta=' . $cipa02idoferta . '', $objDB);
				if ($cipa02consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'cipa02consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM cipa02jornada WHERE cipa02idoferta=' . $cipa02idoferta . ' AND cipa02consec=' . $cipa02consec . '';
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
				$cipa02id = tabla_consecutivo('cipa02jornada', 'cipa02id', '', $objDB);
				if ($cipa02id == -1) {
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
			$cipa02numinscritos = 0;
			$cipa02numparticipantes = 0;
			if ($cipa02consec > 5) {
				$sError = 'Se permite un m&aacute;ximo de 5 sesiones.';
			}
		}
	}
	if ($sError == '') {
		//Si el campo cipa02lugar permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cipa02lugar=str_replace('"', '\"', $cipa02lugar);
		$cipa02lugar=str_replace('"', '\"', $cipa02lugar);
		//Si el campo cipa02link permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cipa02link=str_replace('"', '\"', $cipa02link);
		$cipa02link=str_replace('"', '\"', $cipa02link);
		//$cipa02tematica=str_replace('"', '\"', $cipa02tematica);
		$cipa02tematica=str_replace('"', '\"', $cipa02tematica);
		if ($bInserta) {
			$sCampos3802 = 'cipa02idoferta, cipa02consec, cipa02id, cipa02forma, cipa02lugar, 
			cipa02link, cipa02fecha, cipa02horaini, cipa02minini, cipa02horafin, 
			cipa02minfin, cipa02numinscritos, cipa02numparticipantes, cipa02tematica';
			$sValores3802 = '' . $cipa02idoferta . ', ' . $cipa02consec . ', ' . $cipa02id . ', ' . $cipa02forma . ', "' . $cipa02lugar . '", 
			"' . $cipa02link . '", ' . $cipa02fecha . ', ' . $cipa02horaini . ', ' . $cipa02minini . ', ' . $cipa02horafin . ', 
			' . $cipa02minfin . ', ' . $cipa02numinscritos . ', ' . $cipa02numparticipantes . ', "' . $cipa02tematica . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO cipa02jornada (' . $sCampos3802 . ') VALUES (' . cadena_codificar($sValores3802) . ');';
			} else {
				$sSQL = 'INSERT INTO cipa02jornada (' . $sCampos3802 . ') VALUES (' . $sValores3802 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3802 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3802].<!-- ' . $sSQL . ' -->';
			} else {
				f3802_FechaTermina($cipa02idoferta, $objDB, $bDebug);
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $cipa02id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo3802[1] = 'cipa02forma';
			$scampo3802[2] = 'cipa02lugar';
			$scampo3802[3] = 'cipa02link';
			$scampo3802[4] = 'cipa02fecha';
			$scampo3802[5] = 'cipa02horaini';
			$scampo3802[6] = 'cipa02minini';
			$scampo3802[7] = 'cipa02horafin';
			$scampo3802[8] = 'cipa02minfin';
			$scampo3802[9] = 'cipa02tematica';
			$svr3802[1] = $cipa02forma;
			$svr3802[2] = $cipa02lugar;
			$svr3802[3] = $cipa02link;
			$svr3802[4] = $cipa02fecha;
			$svr3802[5] = $cipa02horaini;
			$svr3802[6] = $cipa02minini;
			$svr3802[7] = $cipa02horafin;
			$svr3802[8] = $cipa02minfin;
			$svr3802[9] = $cipa02tematica;
			$iNumCampos = 9;
			$sWhere = 'cipa02id=' . $cipa02id . '';
			//$sWhere = 'cipa02idoferta=' . $cipa02idoferta . ' AND cipa02consec=' . $cipa02consec . '';
			$sSQL = 'SELECT * FROM cipa02jornada WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				$cipa02idoferta = $filaorigen['cipa02idoferta'];
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo3802[$k]] != $svr3802[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo3802[$k] . '="' . $svr3802[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE cipa02jornada SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE cipa02jornada SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3802 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Jornadas}. <!-- ' . $sSQL . ' -->';
				} else {
					f3802_FechaTermina($cipa02idoferta, $objDB, $bDebug);
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $cipa02id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $cipa02id, $sDebug);
}
function f3802_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3802;
	$bAudita[4] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3802 = 'lg/lg_3802_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3802)) {
		$mensajes_3802 = 'lg/lg_3802_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3802;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$cipa02idoferta = numeros_validar($aParametros[1]);
	$cipa02consec = numeros_validar($aParametros[2]);
	$cipa02id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3802';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $cipa02id . ' LIMIT 0, 1';
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
		$sWhere = 'cipa02id=' . $cipa02id . '';
		//$sWhere = 'cipa02idoferta=' . $cipa02idoferta . ' AND cipa02consec=' . $cipa02consec . '';
		$sSQL = 'DELETE FROM cipa02jornada WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {3802 Jornadas}.<!-- ' . $sSQL . ' -->';
		} else {
			f3802_FechaTermina($cipa02idoferta, $objDB, $bDebug);
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cipa02id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f3802_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3802 = 'lg/lg_3802_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3802)) {
		$mensajes_3802 = 'lg/lg_3802_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3802;
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
	/*
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	*/
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	$idTercero = $aParametros[100];
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$cipa01id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		//$aParametros[103] = trim($aParametros[103]);
		//$aParametros[104] = numeros_validar($aParametros[104]);
		$bDetallado = false;
		if ($aParametros[104] == 1) {
			$bDetallado = true;
		}
	}
	$sDebug = '';
	$bAbierta = false;
	$sSQL = 'SELECT cipa01estado FROM cipa01oferta WHERE cipa01id=' . $cipa01id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		switch($fila['cipa01estado']) {
			case 0: // Borrador
			case 1: // Ofertado
			$bAbierta = true;
		}
	}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3802" name="paginaf3802" type="hidden" value="' . $pagina . '"/>
	<input id="lppf3802" name="lppf3802" type="hidden" value="' . $lineastabla . '"/>';
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
		$sBase = strtoupper($bNombre);
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
	$sTitulos = 'Oferta, Consec, Id, Forma, Lugar, Link, Fecha, Horaini, Minini, Horafin, Minfin, Numinscritos, Numparticipantes';
	$sSQL = 'SELECT TB.cipa02consec, TB.cipa02id, TB.cipa02forma, TB.cipa02lugar, TB.cipa02link, TB.cipa02fecha, 
	TB.cipa02horaini, TB.cipa02minini, TB.cipa02horafin, TB.cipa02minfin, TB.cipa02numinscritos, 
	TB.cipa02numparticipantes, TB.cipa02tematica 
	FROM cipa02jornada AS TB 
	WHERE ' . $sSQLadd1 . ' TB.cipa02idoferta=' . $cipa01id . ' ' . $sSQLadd . '
	ORDER BY TB.cipa02consec';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3802" name="consulta_3802" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3802" name="titulos_3802" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3802: ' . $sSQL . '<br>';
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			return array($sErrConsulta . $sBotones, $sDebug);
			}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['cipa02consec'] . '</b></td>
	<td><b>' . $ETI['cipa02forma'] . '</b></td>
	<td><b>' . $ETI['cipa02fecha'] . '</b></td>
	<td><b>' . $ETI['msg_de'] . '</b></td>
	<td><b>' . $ETI['msg_a'] . '</b></td>
	<td><b>' . $ETI['cipa02numinscritos'] . '</b></td>
	<td><b>' . $ETI['cipa02numparticipantes'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3802', $registros, $lineastabla, $pagina, 'paginarf3802()') . '
	' . html_lpp('lppf3802', $lineastabla, 'paginarf3802()') . '
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
		$et_cipa02consec = $sPrefijo . $filadet['cipa02consec'] . $sSufijo;
		$et_cipa02forma = $sPrefijo . $acipa02forma[$filadet['cipa02forma']] . $sSufijo;
		$et_cipa02fecha = '';
		if ($filadet['cipa02fecha'] != 0) {
			$et_cipa02fecha = $sPrefijo . fecha_desdenumero($filadet['cipa02fecha']) . $sSufijo;
		}
		$et_cipa02horaini = html_TablaHoraMin($filadet['cipa02horaini'], $filadet['cipa02minini']);
		$et_cipa02horafin = html_TablaHoraMin($filadet['cipa02horafin'], $filadet['cipa02minfin']);
		$et_cipa02numinscritos = $sPrefijo . $filadet['cipa02numinscritos'] . $sSufijo;
		$et_cipa02numparticipantes = $sPrefijo . $filadet['cipa02numparticipantes'] . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3802(' . $filadet['cipa02id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_cipa02consec . '</td>
		<td>' . $et_cipa02forma . '</td>
		<td>' . $et_cipa02fecha . '</td>
		<td>' . $et_cipa02horaini . '</td>
		<td>' . $et_cipa02horafin . '</td>
		<td>' . $et_cipa02numinscritos . '</td>
		<td>' . $et_cipa02numparticipantes . '</td>
		<td>' . $sLink . '</td>
		</tr>';
		if ($bDetallado) {
			$sPrefijoB = '<b>';
			$sSufijoB = '</b>';
			if ($filadet['cipa02forma'] == 0){
				$et_cipa02link = $ETI['cipa02link'] . ': ' . $sPrefijoB . cadena_notildes($filadet['cipa02link']) . $sSufijoB;
			} else {
				$et_cipa02link = $ETI['cipa02lugar'] . ': ' . $sPrefijoB . cadena_notildes($filadet['cipa02lugar']) . $sSufijoB;
			}
			$et_cipa02tematica = $ETI['cipa02tematica'] . ': ' . $sPrefijoB . cadena_notildes($filadet['cipa02tematica']) . $sSufijoB;
			$res = $res . '<tr' . $sClass . '>
			<td></td>
			<td colspan="7">' . $et_cipa02link . '</td>
			</tr>
			<tr' . $sClass . '>
			<td></td>
			<td colspan="7">' . $et_cipa02tematica . '</td>
			</tr>';
		}
		/* 
	<td><b>' . $ETI['cipa02lugar'] . '</b></td>
	<td><b>' . $ETI['cipa02link'] . '</b></td>
		$et_cipa02lugar = $sPrefijo . cadena_notildes($filadet['cipa02lugar']) . $sSufijo;
		<td>' . $et_cipa02lugar . '</td>
		*/
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 3802 Jornadas XAJAX 
function f3802_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $cipa02id, $sDebugGuardar) = f3802_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f3802_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3802detalle', 'innerHTML', $sdetalle);
		//if ($iAccion == 2) {
			//$objResponse->call('cargaridf3802(' . $cipa02id . ')');
			//} else {
			$objResponse->call('limpiaf3802');
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
function f3802_Traer($aParametros)
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
		$cipa02idoferta = numeros_validar($aParametros[1]);
		$cipa02consec = numeros_validar($aParametros[2]);
		if (($cipa02idoferta != '') && ($cipa02consec != '')) {
			$besta = true;
		}
	} else {
		$cipa02id = $aParametros[103];
		if ((int)$cipa02id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'cipa02idoferta=' . $cipa02idoferta . ' AND cipa02consec=' . $cipa02consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'cipa02id=' . $cipa02id . '';
		}
		$sSQL = 'SELECT * FROM cipa02jornada WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 1);
		$cipa02consec_nombre = '';
		$html_cipa02consec = html_oculto('cipa02consec', $fila['cipa02consec'], $cipa02consec_nombre);
		$objResponse->assign('div_cipa02consec', 'innerHTML', $html_cipa02consec);
		$cipa02id_nombre = '';
		$html_cipa02id = html_oculto('cipa02id', $fila['cipa02id'], $cipa02id_nombre);
		$objResponse->assign('div_cipa02id', 'innerHTML', $html_cipa02id);
		$objResponse->assign('cipa02forma', 'value', $fila['cipa02forma']);
		$objResponse->assign('cipa02lugar', 'value', $fila['cipa02lugar']);
		$objResponse->assign('cipa02link', 'value', $fila['cipa02link']);
		$objResponse->assign('cipa02fecha', 'value', $fila['cipa02fecha']);
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['cipa02fecha'], true);
		$objResponse->assign('cipa02fecha_dia', 'value', $iDia);
		$objResponse->assign('cipa02fecha_mes', 'value', $iMes);
		$objResponse->assign('cipa02fecha_agno', 'value', $iAgno);
		$html_cipa02horaini = html_HoraMin('cipa02horaini', $fila['cipa02horaini'], 'cipa02minini', $fila['cipa02minini'], false);
		$objResponse->assign('div_cipa02horaini', 'innerHTML', $html_cipa02horaini);
		$html_cipa02horafin = html_HoraMin('cipa02horafin', $fila['cipa02horafin'], 'cipa02minfin', $fila['cipa02minfin'], false);
		$objResponse->assign('div_cipa02horafin', 'innerHTML', $html_cipa02horafin);
		$cipa02numinscritos_eti = $fila['cipa02numinscritos'];
		$html_cipa02numinscritos = html_oculto('cipa02numinscritos', $fila['cipa02numinscritos'], $cipa02numinscritos_eti);
		$objResponse->assign('div_cipa02numinscritos', 'innerHTML', $html_cipa02numinscritos);
		$cipa02numparticipantes_eti = $fila['cipa02numparticipantes'];
		$html_cipa02numparticipantes = html_oculto('cipa02numparticipantes', $fila['cipa02numparticipantes'], $cipa02numparticipantes_eti);
		$objResponse->assign('div_cipa02numparticipantes', 'innerHTML', $html_cipa02numparticipantes);
		$objResponse->assign('cipa02tematica', 'value', $fila['cipa02tematica']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3802', 'block')");
		$objResponse->call('forma02()');
	} else {
		if ($paso == 1) {
			$objResponse->assign('cipa02consec', 'value', $cipa02consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $cipa02id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f3802_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f3802_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f3802_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3802detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3802');
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
function f3802_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3802_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3802detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3802_PintarLlaves($aParametros)
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
	$html_cipa02consec = '<input id="cipa02consec" name="cipa02consec" type="text" value="" onchange="revisaf3802()" class="cuatro" />';
	$html_cipa02id = '<input id="cipa02id" name="cipa02id" type="hidden" value="" />';
	$html_cipa02numinscritos = html_oculto('cipa02numinscritos', '');
	$html_cipa02numparticipantes = html_oculto('cipa02numparticipantes', '');
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cipa02consec', 'innerHTML', $html_cipa02consec);
	$objResponse->assign('div_cipa02id', 'innerHTML', $html_cipa02id);
	$objResponse->assign('div_cipa02numinscritos', 'innerHTML', $html_cipa02numinscritos);
	$objResponse->assign('div_cipa02numparticipantes', 'innerHTML', $html_cipa02numparticipantes);
	$objResponse->call('forma02()');
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f3802_FechaTermina($cipa01id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$cipa01fechatermina = 0;
	$cipa01proximafecha = 0;
	$sSQL = 'SELECT cipa02fecha, cipa02id 
	FROM cipa02jornada 
	WHERE cipa02idoferta=' . $cipa01id . ' 
	ORDER BY cipa02fecha DESC';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$cipa02id = $fila['cipa02id'];
		$cipa01fechatermina = $fila['cipa02fecha'];
		$cipa01proximafecha = $cipa01fechatermina;
		$iHoy = fecha_DiaMod();
		//Ahora buscar la proxima fecha -- por si hay mas de una fecha...
		$sSQL = 'SELECT cipa02fecha, cipa02id 
		FROM cipa02jornada 
		WHERE cipa02idoferta=' . $cipa01id . ' AND cipa02id<>' . $cipa02id . ' AND cipa02fecha>=' . $iHoy . '
		ORDER BY cipa02fecha';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$cipa01proximafecha = $fila['cipa02fecha'];
		}
	}
	$sSQL = 'UPDATE cipa01oferta SET cipa01proximafecha=' . $cipa01proximafecha . ', cipa01fechatermina=' . $cipa01fechatermina . ' WHERE cipa01id=' . $cipa01id . '';
	$result = $objDB->ejecutasql($sSQL);
	return array($sError, $sDebug);
}
?>