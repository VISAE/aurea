<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
--- 2959 Evidencias
*/
function f2959_NombreTabla() {
	return 'visa59evidencia';
}
function f2959_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2959)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2959 = 'lg/lg_2959_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2959)) {
		$mensajes_2959 = 'lg/lg_2959_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2959;
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
	$visa59idactividad = numeros_validar($valores[1]);
	$visa59consec = numeros_validar($valores[2]);
	$visa59id = numeros_validar($valores[3], true);
	$visa59titulo = cadena_Validar(trim($valores[4]));
	$visa59tipoarchivo = numeros_validar($valores[7]);
	$visa59descripcion = cadena_Validar(trim($valores[8]));
	$visa59fechacarga = numeros_validar($valores[9]);
	$visa59idusuario = numeros_validar($valores[10]);
	/*
	if ($visa59tipoarchivo == '') {
		$visa59tipoarchivo = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($visa59idusuario == 0) {
		$sError = $ERR['visa59idusuario'] . $sSepara . $sError;
	}
	if ($visa59descripcion == '') {
		$sError = $ERR['visa59descripcion'] . $sSepara . $sError;
	}
	if ($visa59tipoarchivo == '') {
		$sError = $ERR['visa59tipoarchivo'] . $sSepara . $sError;
	}
	if ($visa59titulo == '') {
		$sError = $ERR['visa59titulo'] . $sSepara . $sError;
	}
	/*
	if ($visa59id == '') {
		$sError = $ERR['visa59id'] . $sSepara . $sError;
	}
	*/
	if ($visa59idactividad == '') {
		$sError = $ERR['visa59idactividad'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($visa59idusuario, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$sNomTabla2959 = f2959_NombreTabla();
	if ($sError == '') {
		if ((int)$visa59id == 0) {
			if ((int)$visa59consec == 0) {
				$visa59consec = tabla_consecutivo($sNomTabla2959, 'visa59consec', 'visa59idactividad=' . $visa59idactividad . '', $objDB);
				if ($visa59consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa59consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla2959 . ' WHERE visa59idactividad=' . $visa59idactividad . ' AND visa59consec=' . $visa59consec . '';
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
				$visa59id = tabla_consecutivo($sNomTabla2959, 'visa59id', '', $objDB);
				if ($visa59id == -1) {
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
			$visa59idorigen = 0;
			$visa59idarchivo = 0;
		}
	}
	if ($sError == '') {
		//Si el campo visa59descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$visa59descripcion = str_replace('"', '\"', $visa59descripcion);
		$visa59descripcion = str_replace('"', '\"', $visa59descripcion);
		if ($bInserta) {
			$sCampos2959 = 'visa59idactividad, visa59consec, visa59id, visa59titulo, visa59idorigen, 
			visa59idarchivo, visa59tipoarchivo, visa59descripcion, visa59fechacarga, visa59idusuario';
			$sValores2959 = '' . $visa59idactividad . ', ' . $visa59consec . ', ' . $visa59id . ', "' . $visa59titulo . '", ' . $visa59idorigen . ', 
			' . $visa59idarchivo . ', ' . $visa59tipoarchivo . ', "' . $visa59descripcion . '", ' . $visa59fechacarga . ', ' . $visa59idusuario . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla2959 . ' (' . $sCampos2959 . ') VALUES (' . cadena_codificar($sValores2959) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla2959 . ' (' . $sCampos2959 . ') VALUES (' . $sValores2959 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 2959 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2959].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $visa59id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2959[1] = 'visa59titulo';
			$scampo2959[2] = 'visa59tipoarchivo';
			$scampo2959[3] = 'visa59descripcion';
			$scampo2959[4] = 'visa59fechacarga';
			$scampo2959[5] = 'visa59idusuario';
			$svr2959[1] = $visa59titulo;
			$svr2959[2] = $visa59tipoarchivo;
			$svr2959[3] = $visa59descripcion;
			$svr2959[4] = $visa59fechacarga;
			$svr2959[5] = $visa59idusuario;
			$iNumCampos = 5;
			$sWhere = 'visa59id=' . $visa59id . '';
			//$sWhere = 'visa59idactividad=' . $visa59idactividad . ' AND visa59consec=' . $visa59consec . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla2959 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2959[$k]] != $svr2959[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2959[$k] . '="' . $svr2959[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sNomTabla2959 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sNomTabla2959 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 2959 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Evidencias}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $visa59id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $visa59id, $sDebug);
}
function f2959_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2959;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2959 = 'lg/lg_2959_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2959)) {
		$mensajes_2959 = 'lg/lg_2959_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2959;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$visa59idactividad = numeros_validar($aParametros[1]);
	$visa59consec = numeros_validar($aParametros[2]);
	$visa59id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2959';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $visa59id . ' LIMIT 0, 1';
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
		$sNomTabla2959 = f2959_NombreTabla();
		//acciones previas
		$sWhere = 'visa59id=' . $visa59id . '';
		//$sWhere = 'visa59idactividad=' . $visa59idactividad . ' AND visa59consec=' . $visa59consec . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla2959 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2959 Evidencias}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa59id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2959_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2959 = 'lg/lg_2959_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2959)) {
		$mensajes_2959 = 'lg/lg_2959_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2959;
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
	$visa57id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	$sNomTabla2959 = f2959_NombreTabla();
	$sNomTabla2957 = f2957_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2959" name="paginaf2959" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2959" name="lppf2959" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$bAbierta = false;
	$sSQL = 'SELECT visa57estado FROM ' . $sNomTabla2957 . ' WHERE visa57id=' . $visa57id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['visa57estado'] == 0) {
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
	$sTitulos = 'Actividad, Consec, Titulo, Tipoarchivo, Fechacarga, Usuario';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa59idactividad, TB.visa59consec, TB.visa59titulo, TB.visa59tipoarchivo, TB.visa59fechacarga, 
	T6.unad11razonsocial AS C6_nombre, TB.visa59idusuario, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc';
	$sConsulta = 'FROM ' . $sNomTabla2959 . ' AS TB, unad11terceros AS T6 
	WHERE ' . $sSQLadd1 . ' TB.visa59idactividad=' . $visa57id . ' AND TB.visa59idusuario=T6.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa59consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2959" name="consulta_2959" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2959" name="titulos_2959" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2959: ' . $sSQL . '');
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
	$res = $res . '<th><b>' . $ETI['visa59consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa59titulo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa59tipoarchivo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa59fechacarga'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['visa59idusuario'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2959', $registros, $lineastabla, $pagina, 'paginarf2959()') . '';
	$res = $res . '' . html_lpp('lppf2959', $lineastabla, 'paginarf2959()') . '';
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
		$et_visa59consec = $sPrefijo . $filadet['visa59consec'] . $sSufijo;
		$et_visa59titulo = $sPrefijo . cadena_notildes($filadet['visa59titulo']) . $sSufijo;
		$et_visa59tipoarchivo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa59tipoarchivo'] == 0) {
			$et_visa59tipoarchivo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_visa59fechacarga = '';
		if ($filadet['visa59fechacarga'] != 0) {
			$et_visa59fechacarga = $sPrefijo . fecha_desdenumero($filadet['visa59fechacarga']) . $sSufijo;
		}
		$et_visa59idusuario_doc = '';
		$et_visa59idusuario_nombre = '';
		if ($filadet['visa59idusuario'] != 0) {
			$et_visa59idusuario_doc = $sPrefijo . $filadet['C6_td'] . ' ' . $filadet['C6_doc'] . $sSufijo;
			$et_visa59idusuario_nombre = $sPrefijo . cadena_notildes($filadet['C6_nombre']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2959(' . $filadet['visa59id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa59consec . '</td>';
		$res = $res . '<td>' . $et_visa59titulo . '</td>';
		$res = $res . '<td>' . $et_visa59tipoarchivo . '</td>';
		$res = $res . '<td>' . $et_visa59fechacarga . '</td>';
		$res = $res . '<td>' . $et_visa59idusuario_doc . '</td>';
		$res = $res . '<td>' . $et_visa59idusuario_nombre . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 2959 Evidencias XAJAX 
function elimina_archivo_visa59idarchivo($idPadre, $bDebug = false)
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
		archivo_eliminar('visa59evidencia', 'visa59id', 'visa59idorigen', 'visa59idarchivo', $idPadre, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_visa59idarchivo");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0);");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2959_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $visa59id, $sDebugGuardar) = f2959_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2959_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2959detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2959(' . $visa59id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2959');
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
function f2959_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2959 = 'lg/lg_2959_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2959)) {
		$mensajes_2959 = 'lg/lg_2959_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2959;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$visa59idactividad = numeros_validar($aParametros[1]);
		$visa59consec = numeros_validar($aParametros[2]);
		if (($visa59idactividad != '') && ($visa59consec != '')) {
			$besta = true;
		}
	} else {
		$visa59id = $aParametros[103];
		if ((int)$visa59id != 0) {
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
		$besta = false;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'visa59idactividad=' . $visa59idactividad . ' AND visa59consec=' . $visa59consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'visa59id=' . $visa59id . '';
		}
		$sNomTabla2959 = f2959_NombreTabla();
		$sSQL = 'SELECT * FROM ' . $sNomTabla2959 . ' WHERE ' . $sSQLcondi;
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
		$visa59idusuario_id = (int)$fila['visa59idusuario'];
		$visa59idusuario_td = $APP->tipo_doc;
		$visa59idusuario_doc = '';
		$visa59idusuario_nombre = '';
		if ($visa59idusuario_id != 0) {
			list($visa59idusuario_nombre, $visa59idusuario_id, $visa59idusuario_td, $visa59idusuario_doc) = html_tercero($visa59idusuario_td, $visa59idusuario_doc, $visa59idusuario_id, 0, $objDB);
		}
		$visa59consec_nombre = '';
		$html_visa59consec = html_oculto('visa59consec', $fila['visa59consec'], $visa59consec_nombre);
		$objResponse->assign('div_visa59consec', 'innerHTML', $html_visa59consec);
		$visa59id_nombre = '';
		$html_visa59id = html_oculto('visa59id', $fila['visa59id'], $visa59id_nombre);
		$objResponse->assign('div_visa59id', 'innerHTML', $html_visa59id);
		$objResponse->assign('visa59titulo', 'value', cadena_LimpiarXAJAX($fila['visa59titulo']));
		$objResponse->assign('visa59idorigen', 'value', $fila['visa59idorigen']);
		$idorigen = (int)$fila['visa59idorigen'];
		$objResponse->assign('visa59idarchivo', 'value', $fila['visa59idarchivo']);
		$objResponse->assign('visa59idarchivo_up', 'value', html_lnkupload(2959, $fila['visa59id']));
		$sMuestraAnexar = 'block';
		$sMuestraEliminar = 'none';
		$sHTMLArchivo = html_lnkarchivo($idorigen, (int)$fila['visa59idarchivo']);
		if ((int)$fila['visa59idarchivo'] != 0) {
			$sMuestraEliminar = 'block';
			//Aqui puede poner validaciones al eliminar - Si no se puede eliminar reversar el mostrado.
		}
		$objResponse->assign('div_visa59idarchivo', 'innerHTML', $sHTMLArchivo);
		$objResponse->call("verboton('banexavisa59idarchivo', '".$sMuestraAnexar."')");
		$objResponse->call("verboton('beliminavisa59idarchivo', '".$sMuestraEliminar."')");
		$objResponse->assign('visa59tipoarchivo', 'value', $fila['visa59tipoarchivo']);
		$objResponse->assign('visa59descripcion', 'value', cadena_LimpiarXAJAX($fila['visa59descripcion']));
		$html_visa59fechacarga = html_oculto('visa59fechacarga', $fila['visa59fechacarga'], fecha_desdenumero($fila['visa59fechacarga']));
		$objResponse->assign('div_visa59fechacarga', 'innerHTML', $html_visa59fechacarga);
		$objResponse->assign('visa59idusuario', 'value', $fila['visa59idusuario']);
		$objResponse->assign('visa59idusuario_td', 'value', $visa59idusuario_td);
		$objResponse->assign('visa59idusuario_doc', 'value', $visa59idusuario_doc);
		$objResponse->assign('div_visa59idusuario', 'innerHTML', $visa59idusuario_nombre);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2959', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('visa59consec', 'value', $visa59consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $visa59id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2959_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2959_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2959_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2959detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2959');
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
function f2959_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2959_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2959detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2959_PintarLlaves($aParametros)
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
	$mensajes_2959 = 'lg/lg_2959_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2959)) {
		$mensajes_2959 = 'lg/lg_2959_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_2959;
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$html_visa59consec = '<input id="visa59consec" name="visa59consec" type="text" value="" onchange="revisaf2959()" class="cuatro" />';
	$html_visa59id = '<input id="visa59id" name="visa59id" type="hidden" value="" />';
	$et_visa59fechacarga = '00/00/0000';
	$html_visa59fechacarga = html_oculto('visa59fechacarga', 0, $et_visa59fechacarga);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa59consec', 'innerHTML', $html_visa59consec);
	$objResponse->assign('div_visa59id', 'innerHTML', $html_visa59id);
	$objResponse->assign('div_visa59fechacarga', 'innerHTML', $html_visa59fechacarga);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

