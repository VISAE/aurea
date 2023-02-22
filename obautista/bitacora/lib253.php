<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
--- 253 Anexos
*/
function f253_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 253;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_253 = 'lg/lg_253_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_253)) {
		$mensajes_253 = 'lg/lg_253_es.php';
	}
	require $mensajes_todas;
	require $mensajes_253;
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
	$aure58idbitacora = numeros_validar($valores[1]);
	$aure58consec = numeros_validar($valores[2]);
	$aure58id = numeros_validar($valores[3], true);
	$aure58titulo = htmlspecialchars(trim($valores[4]));
	$aure58idusuario = numeros_validar($valores[7]);
	$aure58fecha = numeros_validar($valores[8]);
	/*
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($aure58idusuario == 0) {
		$sError = $ERR['aure58idusuario'] . $sSepara . $sError;
	}
	if ($aure58titulo == '') {
		$sError = $ERR['aure58titulo'] . $sSepara.$sError;
	}
	/*
	if ($aure58id == '') {
		$sError = $ERR['aure58id'] . $sSepara . $sError;
	}
	*/
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($aure58idusuario, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$aure58id == 0) {
			if ((int)$aure58consec == 0) {
				$aure58consec = tabla_consecutivo('aure58anexos', 'aure58consec', 'aure58idbitacora=' . $aure58idbitacora . '', $objDB);
				if ($aure58consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'aure58consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM aure58anexos WHERE aure58idbitacora=' . $aure58idbitacora . ' AND aure58consec=' . $aure58consec . '';
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
				$aure58id = tabla_consecutivo('aure58anexos', 'aure58id', '', $objDB);
				if ($aure58id == -1) {
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
			$aure58idorigen = 0;
			$aure58idarchivo = 0;
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$sCampos253 = 'aure58idbitacora, aure58consec, aure58id, aure58titulo, aure58idorigen, 
			aure58idarchivo, aure58idusuario, aure58fecha';
			$sValores253 = '' . $aure58idbitacora . ', ' . $aure58consec . ', ' . $aure58id . ', "' . $aure58titulo . '", ' . $aure58idorigen . ', 
			' . $aure58idarchivo . ', ' . $aure58idusuario . ', ' . $aure58fecha . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO aure58anexos (' . $sCampos253 . ') VALUES (' . cadena_codificar($sValores253) . ');';
			} else {
				$sSQL = 'INSERT INTO aure58anexos (' . $sCampos253 . ') VALUES (' . $sValores253 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 253 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [253].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $aure58id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo253[1] = 'aure58titulo';
			$scampo253[2] = 'aure58fecha';
			$svr253[1] = $aure58titulo;
			$svr253[2] = $aure58fecha;
			$iNumCampos = 2;
			$sWhere = 'aure58id=' . $aure58id . '';
			//$sWhere = 'aure58idbitacora=' . $aure58idbitacora . ' AND aure58consec=' . $aure58consec . '';
			$sSQL = 'SELECT * FROM aure58anexos WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo253[$k]] != $svr253[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo253[$k] . '="' . $svr253[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE aure58anexos SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE aure58anexos SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 253 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Anexos}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $aure58id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $aure58id, $sDebug);
}
function f253_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 253;
	$bAudita[4] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_253 = 'lg/lg_253_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_253)) {
		$mensajes_253 = 'lg/lg_253_es.php';
	}
	require $mensajes_todas;
	require $mensajes_253;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$aure58idbitacora = numeros_validar($aParametros[1]);
	$aure58consec = numeros_validar($aParametros[2]);
	$aure58id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=253';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $aure58id . ' LIMIT 0, 1';
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
		$sWhere = 'aure58id=' . $aure58id . '';
		//$sWhere = 'aure58idbitacora=' . $aure58idbitacora . ' AND aure58consec=' . $aure58consec . '';
		$sSQL = 'DELETE FROM aure58anexos WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {253 Anexos}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $aure58id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f253_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_253 = 'lg/lg_253_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_253)) {
		$mensajes_253 = 'lg/lg_253_es.php';
	}
	require $mensajes_todas;
	require $mensajes_253;
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
	$sBotones = '<input id="paginaf253" name="paginaf253" type="hidden" value="' . $pagina . '"/>
	<input id="lppf253" name="lppf253" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Bitacora, Consec, Id, Titulo, Origen, Archivo, Usuario, Fecha';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.aure58idbitacora, TB.aure58consec, TB.aure58id, TB.aure58titulo, TB.aure58idorigen, TB.aure58idarchivo, T7.unad11razonsocial AS C7_nombre, TB.aure58fecha, TB.aure58idusuario, T7.unad11tipodoc AS C7_td, T7.unad11doc AS C7_doc';
	$sConsulta = 'FROM aure58anexos AS TB, unad11terceros AS T7 
	WHERE ' . $sSQLadd1 . ' TB.aure58idbitacora=' . $aure51id . ' AND TB.aure58idusuario=T7.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.aure58consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_253" name="consulta_253" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_253" name="titulos_253" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 253: ' . $sSQL . '<br>';
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
	<td><b>' . $ETI['aure58consec'] . '</b></td>
	<td><b>' . $ETI['aure58titulo'] . '</b></td>
	<td><b>' . $ETI['aure58idarchivo'] . '</b></td>
	<td colspan="2"><b>' . $ETI['aure58idusuario'] . '</b></td>
	<td><b>' . $ETI['aure58fecha'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf253', $registros, $lineastabla, $pagina, 'paginarf253()') . '
	' . html_lpp('lppf253', $lineastabla, 'paginarf253()') . '
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
		$et_aure58titulo = $sPrefijo . cadena_notildes($filadet['aure58titulo']) . $sSufijo;
		$et_aure58idarchivo = '';
		if ($filadet['aure58idarchivo'] != 0) {
			//$et_aure58idarchivo = '<img src="verarchivo.php?cont=' . $filadet['aure58idorigen'] . '&id=' . $filadet['aure58idarchivo'] . '&maxx=150"/>';
			$et_aure58idarchivo = html_lnkarchivo((int)$filadet['aure58idorigen'], (int)$filadet['aure58idarchivo']);
		}
		$et_aure58idusuario_doc = '';
		$et_aure58idusuario_nombre = '';
		if ($filadet['aure58idusuario'] != 0) {
			$et_aure58idusuario_doc = $sPrefijo . $filadet['C7_td'] . ' ' . $filadet['C7_doc'] . $sSufijo;
			$et_aure58idusuario_nombre = $sPrefijo . cadena_notildes($filadet['C7_nombre']) . $sSufijo;
		}
		$et_aure58fecha = '';
		if ($filadet['aure58fecha'] != 0) {
			$et_aure58fecha = $sPrefijo . fecha_desdenumero($filadet['aure58fecha']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf253(' . $filadet['aure58id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_aure58consec . '</td>
		<td>' . $et_aure58titulo . '</td>
		<td>' . $et_aure58idarchivo . '</td>
		<td>' . $et_aure58idusuario_doc . '</td>
		<td>' . $et_aure58idusuario_nombre . '</td>
		<td>' . $et_aure58fecha . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 253 Anexos XAJAX 
function elimina_archivo_aure58idarchivo($idPadre, $bDebug = false)
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
		archivo_eliminar('aure58anexos', 'aure58id', 'aure58idorigen', 'aure58idarchivo', $idPadre, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_aure58idarchivo");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0);");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f253_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $aure58id, $sDebugGuardar) = f253_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f253_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f253detalle', 'innerHTML', $sdetalle);
		//if ($iAccion == 2) {
			//$objResponse->call('cargaridf253(' . $aure58id . ')');
			//} else {
			$objResponse->call('limpiaf253');
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
function f253_Traer($aParametros)
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
		$aure58idbitacora = numeros_validar($aParametros[1]);
		$aure58consec = numeros_validar($aParametros[2]);
		if (($aure58idbitacora != '') && ($aure58consec != '')) {
			$besta = true;
		}
	} else {
		$aure58id = $aParametros[103];
		if ((int)$aure58id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'aure58idbitacora=' . $aure58idbitacora . ' AND aure58consec=' . $aure58consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'aure58id=' . $aure58id . '';
		}
		$sSQL = 'SELECT * FROM aure58anexos WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 1);
		$aure58idusuario_id = (int)$fila['aure58idusuario'];
		$aure58idusuario_td = $APP->tipo_doc;
		$aure58idusuario_doc = '';
		$aure58idusuario_nombre = '';
		if ($aure58idusuario_id != 0) {
			list($aure58idusuario_nombre, $aure58idusuario_id, $aure58idusuario_td, $aure58idusuario_doc) = html_tercero($aure58idusuario_td, $aure58idusuario_doc, $aure58idusuario_id, 0, $objDB);
		}
		$aure58consec_nombre = '';
		$html_aure58consec = html_oculto('aure58consec', $fila['aure58consec'], $aure58consec_nombre);
		$objResponse->assign('div_aure58consec', 'innerHTML', $html_aure58consec);
		$aure58id_nombre = '';
		$html_aure58id = html_oculto('aure58id', $fila['aure58id'], $aure58id_nombre);
		$objResponse->assign('div_aure58id', 'innerHTML', $html_aure58id);
		$objResponse->assign('aure58titulo', 'value', $fila['aure58titulo']);
		$objResponse->assign('aure58idorigen', 'value', $fila['aure58idorigen']);
		$idorigen = (int)$fila['aure58idorigen'];
		$objResponse->assign('aure58idarchivo', 'value', $fila['aure58idarchivo']);
		$sMuestraAnexar = 'block';
		$sMuestraEliminar = 'none';
		$sHTMLArchivo = html_lnkarchivo($idorigen, (int)$fila['aure58idarchivo']);
		if ((int)$fila['aure58idarchivo'] != 0) {
			$sMuestraEliminar = 'block';
			//Aqui puede poner validaciones al eliminar - Si no se puede eliminar reversar el mostrado.
		}
		$objResponse->assign('div_aure58idarchivo', 'innerHTML', $sHTMLArchivo);
		$objResponse->call("verboton('banexaaure58idarchivo', '".$sMuestraAnexar."')");
		$objResponse->call("verboton('beliminaaure58idarchivo', '".$sMuestraEliminar."')");
		$bOculto = true;
		$html_aure58idusuario_llaves = html_DivTerceroV2('aure58idusuario', $aure58idusuario_td, $aure58idusuario_doc, $bOculto, $aure58idusuario_id, $ETI['ing_doc']);
		$objResponse->assign('aure58idusuario', 'value', $aure58idusuario_id);
		$objResponse->assign('div_aure58idusuario_llaves', 'innerHTML', $html_aure58idusuario_llaves);
		$objResponse->assign('div_aure58idusuario', 'innerHTML', $aure58idusuario_nombre);
		$html_aure58fecha = html_oculto('aure58fecha', $fila['aure58fecha'], fecha_desdenumero($fila['aure58fecha']));
		$objResponse->assign('div_aure58fecha', 'innerHTML', $html_aure58fecha);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina253', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('aure58consec', 'value', $aure58consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $aure58id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f253_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f253_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f253_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f253detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf253');
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
function f253_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f253_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f253detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f253_PintarLlaves($aParametros)
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
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$iPiel = iDefinirPiel($APP, 1);
	$objCombos = new clsHtmlCombos();
	$html_aure58consec = '<input id="aure58consec" name="aure58consec" type="text" value="" onchange="revisaf253()" class="cuatro" />';
	$html_aure58id = '<input id="aure58id" name="aure58id" type="hidden" value="" />';
	list($aure58idusuario_rs, $aure58idusuario, $aure58idusuario_td, $aure58idusuario_doc) = html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_aure58idusuario_llaves = html_DivTerceroV2('aure58idusuario', $aure58idusuario_td, $aure58idusuario_doc, true, 0, $ETI['ing_doc']);
	$et_aure58fecha = '00/00/0000';
	$html_aure58fecha = html_oculto('aure58fecha', 0, $et_aure58fecha);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_aure58consec', 'innerHTML', $html_aure58consec);
	$objResponse->assign('div_aure58id', 'innerHTML', $html_aure58id);
	$objResponse->assign('aure58idusuario', 'value', $aure58idusuario);
	$objResponse->assign('div_aure58idusuario_llaves', 'innerHTML', $html_aure58idusuario_llaves);
	$objResponse->assign('div_aure58idusuario', 'innerHTML', $aure58idusuario_rs);
	$objResponse->assign('div_aure58fecha', 'innerHTML', $html_aure58fecha);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>