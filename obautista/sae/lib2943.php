<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2943 Anexos
*/
function f2943_HTMLComboV2_visa43iddocumento($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa43iddocumento', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'revisaf2943()';
	$sSQL = 'SELECT TB.visa42id AS id, TB.visa42nombre AS nombre 
	FROM visa42convanexo AS TB
	WHERE TB.visa42id>0
	ORDER BY TB.visa42nombre';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2943, $sIdioma
	return $res;
}
function f2943_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2943)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2943 = 'lg/lg_2943_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2943)) {
		$mensajes_2943 = 'lg/lg_2943_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2943;
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
	$visa43idinscripcion = numeros_validar($valores[1]);
	$visa43iddocumento = numeros_validar($valores[2]);
	$visa43id = numeros_validar($valores[3], true);
	$visa43fechaaprob = numeros_validar($valores[6]);
	$visa43usuarioaprueba = numeros_validar($valores[7]);
	/*
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa43usuarioaprueba == 0) {
		$sError = $ERR['visa43usuarioaprueba'] . $sSepara . $sError;
	}
	/*
	if ($visa43id == '') {
		$sError = $ERR['visa43id'] . $sSepara . $sError;
	}
	*/
	if ($visa43idinscripcion == '') {
		$sError = $ERR['visa43idinscripcion'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($visa43usuarioaprueba, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$visa43id == 0) {
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM visa43inscripdocs WHERE visa43idinscripcion=' . $visa43idinscripcion . ' AND visa43iddocumento=' . $visa43iddocumento . '';
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
				$visa43id = tabla_consecutivo('visa43inscripdocs', 'visa43id', '', $objDB);
				if ($visa43id == -1) {
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
			$visa43iddocumento = 0;
			$visa43idorigen = 0;
			$visa43idarchivo = 0;
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
		if ($bInserta) {
			$sCampos2943 = 'visa43idinscripcion, visa43iddocumento, visa43id, visa43idorigen, visa43idarchivo, 
			visa43fechaaprob, visa43usuarioaprueba';
			$sValores2943 = '' . $visa43idinscripcion . ', ' . $visa43iddocumento . ', ' . $visa43id . ', ' . $visa43idorigen . ', ' . $visa43idarchivo . ', 
			' . $visa43fechaaprob . ', ' . $visa43usuarioaprueba . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa43inscripdocs (' . $sCampos2943 . ') VALUES (' . cadena_codificar($sValores2943) . ');';
			} else {
				$sSQL = 'INSERT INTO visa43inscripdocs (' . $sCampos2943 . ') VALUES (' . $sValores2943 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2943 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2943].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa43id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2943[1] = 'visa43fechaaprob';
			$svr2943[1] = $visa43fechaaprob;
			$iNumCampos = 1;
			$sWhere = 'visa43id=' . $visa43id . '';
			//$sWhere = 'visa43idinscripcion=' . $visa43idinscripcion . ' AND visa43iddocumento=' . $visa43iddocumento . '';
			$sSQL = 'SELECT * FROM visa43inscripdocs WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2943[$k]] != $svr2943[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2943[$k] . '="' . $svr2943[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE visa43inscripdocs SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE visa43inscripdocs SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2943 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Anexos}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa43id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa43id, $sDebug);
}
function f2943_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2943;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2943 = 'lg/lg_2943_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2943)) {
		$mensajes_2943 = 'lg/lg_2943_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2943;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa43idinscripcion = numeros_validar($aParametros[1]);
	$visa43iddocumento = numeros_validar($aParametros[2]);
	$visa43id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2943';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa43id . ' LIMIT 0, 1';
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
		$sWhere = 'visa43id=' . $visa43id . '';
		//$sWhere = 'visa43idinscripcion=' . $visa43idinscripcion . ' AND visa43iddocumento=' . $visa43iddocumento . '';
		$sSQL = 'DELETE FROM visa43inscripdocs WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2943 Anexos}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa43id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2943_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2943 = 'lg/lg_2943_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2943)) {
		$mensajes_2943 = 'lg/lg_2943_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2943;
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
	$sBotones = '<input id="paginaf2943" name="paginaf2943" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2943" name="lppf2943" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Id, Documento, Fechaaprob';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa43id, T2.visa42nombre, TB.visa43fechaaprob, TB.visa43iddocumento';
	$sConsulta = 'FROM visa43inscripdocs AS TB, visa42convanexo AS T2 
	WHERE ' . $sSQLadd1 . ' TB.visa43iddocumento=T2.visa42id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa43iddocumento';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2943" name="consulta_2943" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2943" name="titulos_2943" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2943: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['visa43iddocumento'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa43fechaaprob'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2943', $registros, $lineastabla, $pagina, 'paginarf2943()') . '';
	$res = $res . '' . html_lpp('lppf2943', $lineastabla, 'paginarf2943()') . '';
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
		$et_visa43iddocumento = $sPrefijo . cadena_notildes($filadet['visa42nombre']) . $sSufijo;
		$et_visa43fechaaprob = '';
		if ($filadet['visa43fechaaprob'] != 0) {
			$et_visa43fechaaprob = $sPrefijo . fecha_desdenumero($filadet['visa43fechaaprob']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2943(' . $filadet['visa43id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa43iddocumento . '</td>';
		$res = $res . '<td>' . $et_visa43fechaaprob . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2943 Anexos XAJAX 
function elimina_archivo_visa43idarchivo($idPadre, $bDebug = false)
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
		archivo_eliminar('visa43inscripdocs', 'visa43id', 'visa43idorigen', 'visa43idarchivo', $idPadre, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_visa43idarchivo");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0);");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2943_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $visa43id, $sDebugGuardar) = f2943_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2943_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2943detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2943(' . $visa43id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2943');
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
function f2943_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2943 = 'lg/lg_2943_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2943)) {
		$mensajes_2943 = 'lg/lg_2943_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2943;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa43idinscripcion = numeros_validar($aParametros[1]);
		$visa43iddocumento = numeros_validar($aParametros[2]);
		if (($visa43idinscripcion != '') && ($visa43iddocumento != '')) {
			$besta = true;
		}
	} else {
		$visa43id = $aParametros[103];
		if ((int)$visa43id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'visa43idinscripcion=' . $visa43idinscripcion . ' AND visa43iddocumento=' . $visa43iddocumento . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa43id=' . $visa43id . '';
		}
		$sSQL = 'SELECT * FROM visa43inscripdocs WHERE ' . $sSQLcondi;
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
		$visa43usuarioaprueba_id = (int)$fila['visa43usuarioaprueba'];
		$visa43usuarioaprueba_td = $APP->tipo_doc;
		$visa43usuarioaprueba_doc = '';
		$visa43usuarioaprueba_nombre = '';
		if ($visa43usuarioaprueba_id != 0) {
			list($visa43usuarioaprueba_nombre, $visa43usuarioaprueba_id, $visa43usuarioaprueba_td, $visa43usuarioaprueba_doc) = html_tercero($visa43usuarioaprueba_td, $visa43usuarioaprueba_doc, $visa43usuarioaprueba_id, 0, $objDB);
		}
		$visa43iddocumento_nombre = '&nbsp;';
		//$visa43iddocumento_nombre = $avisa43iddocumento[$fila['visa43iddocumento']];
		if ($fila['visa43iddocumento'] != 0) {
			list($visa43iddocumento_nombre, $serror_det) = tabla_campoxid('visa42convanexo', 'visa42nombre', 'visa42id', $fila['visa43iddocumento'], '{' . $ETI['msg_sindato'] . '}', $objDB);
			$visa43iddocumento_nombre = cadena_LimpiarXAJAX($visa43iddocumento_nombre);
		}
		$html_visa43iddocumento = html_oculto('visa43iddocumento', $fila['visa43iddocumento'], $visa43iddocumento_nombre);
		$objResponse->assign('div_visa43iddocumento', 'innerHTML', $html_visa43iddocumento);
		$visa43id_nombre = '';
		$html_visa43id = html_oculto('visa43id', $fila['visa43id'], $visa43id_nombre);
		$objResponse->assign('div_visa43id', 'innerHTML', $html_visa43id);
		$objResponse->assign('visa43idorigen', 'value', $fila['visa43idorigen']);
		$idorigen = (int)$fila['visa43idorigen'];
		$objResponse->assign('visa43idarchivo', 'value', $fila['visa43idarchivo']);
		$objResponse->assign('visa43idarchivo_up', 'value', html_lnkupload(2943, $fila['visa43id']));
		$sMuestraAnexar = 'block';
		$sMuestraEliminar = 'none';
		$sHTMLArchivo = html_lnkarchivo($idorigen, (int)$fila['visa43idarchivo']);
		if ((int)$fila['visa43idarchivo'] != 0) {
			$sMuestraEliminar = 'block';
			//Aqui puede poner validaciones al eliminar - Si no se puede eliminar reversar el mostrado.
		}
		$objResponse->assign('div_visa43idarchivo', 'innerHTML', $sHTMLArchivo);
		$objResponse->call("verboton('banexavisa43idarchivo', '".$sMuestraAnexar."')");
		$objResponse->call("verboton('beliminavisa43idarchivo', '".$sMuestraEliminar."')");
		$html_visa43fechaaprob = html_oculto('visa43fechaaprob', $fila['visa43fechaaprob'], fecha_desdenumero($fila['visa43fechaaprob']));
		$objResponse->assign('div_visa43fechaaprob', 'innerHTML', $html_visa43fechaaprob);
		$bOculto = true;
		$html_visa43usuarioaprueba_llaves = html_DivTerceroV8('visa43usuarioaprueba', $visa43usuarioaprueba_td, $visa43usuarioaprueba_doc, $bOculto, $objDB, $objCombos, $visa43usuarioaprueba_id, $ETI['ing_doc']);
		$objResponse->assign('visa43usuarioaprueba', 'value', $visa43usuarioaprueba_id);
		$objResponse->assign('div_visa43usuarioaprueba_llaves', 'innerHTML', $html_visa43usuarioaprueba_llaves);
		$objResponse->assign('div_visa43usuarioaprueba', 'innerHTML', $visa43usuarioaprueba_nombre);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2943', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('visa43iddocumento', 'value', $visa43iddocumento);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa43id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2943_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2943_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2943_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2943detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2943');
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
function f2943_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2943_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2943detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2943_PintarLlaves($aParametros)
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
	$mensajes_2943 = 'lg/lg_2943_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2943)) {
		$mensajes_2943 = 'lg/lg_2943_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2943;
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$html_visa43iddocumento = f2943_HTMLComboV2_visa43iddocumento($objDB, $objCombos, '');
	//$et_visa43iddocumento = $avisa43iddocumento[0];
	//$html_visa43iddocumento = html_oculto('visa43iddocumento', 0, $et_visa43iddocumento);
	$html_visa43id = '<input id="visa43id" name="visa43id" type="hidden" value="" />';
	$et_visa43fechaaprob = '00/00/0000';
	$html_visa43fechaaprob = html_oculto('visa43fechaaprob', 0, $et_visa43fechaaprob);
	list($visa43usuarioaprueba_rs, $visa43usuarioaprueba, $visa43usuarioaprueba_td, $visa43usuarioaprueba_doc) = html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_visa43usuarioaprueba_llaves = html_DivTerceroV8('visa43usuarioaprueba', $visa43usuarioaprueba_td, $visa43usuarioaprueba_doc, true, $objDB, $objCombos, 0, $ETI['ing_doc']);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa43iddocumento', 'innerHTML', $html_visa43iddocumento);
	$objResponse->call('$("#visa43iddocumento").chosen()');
	$objResponse->assign('div_visa43id', 'innerHTML', $html_visa43id);
	$objResponse->assign('div_visa43fechaaprob', 'innerHTML', $html_visa43fechaaprob);
	$objResponse->assign('visa43usuarioaprueba', 'value', $visa43usuarioaprueba);
	$objResponse->assign('div_visa43usuarioaprueba_llaves', 'innerHTML', $html_visa43usuarioaprueba_llaves);
	$objResponse->assign('div_visa43usuarioaprueba', 'innerHTML', $visa43usuarioaprueba_rs);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

