<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.1 jueves, 5 de julio de 2018
--- omar.bautista@unad.edu.co
--- Modelo Versión 3.0.17 martes, 16 de septiembre de 2025
--- 2309 Respuestas
*/
function f2309_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2309)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2309 = 'lg/lg_2309_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2309)) {
		$mensajes_2309 = 'lg/lg_2309_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2309;
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
	$cara09idpregunta = numeros_validar($valores[1]);
	$cara09consec = numeros_validar($valores[2]);
	$cara09id = numeros_validar($valores[3], true);
	$cara09valor = numeros_validar($valores[4]);
	$cara09contenido = cadena_Validar(trim($valores[5]));
	if ($cara09valor == '') {
		$cara09valor = 0;
	}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($cara09contenido == '') {
		$sError = $ERR['cara09contenido'] . $sSepara . $sError;
	}
	if ($cara09valor == '') {
		$sError = $ERR['cara09valor'] . $sSepara . $sError;
	}
	if ($cara09valor > 10) {
		$sError = $ERR['cara09valor_mas'] . $sSepara . $sError;
	} else {
		if ($cara09valor < 0) {
			$sError = $ERR['cara09valor_menos'] . $sSepara . $sError;
		}
	}
	/*
	if ($cara09id == '') {
		$sError = $ERR['cara09id'] . $sSepara . $sError;
	} //CONSECUTIVO
	if ($cara09consec == '') {
		$sError = $ERR['cara09consec'] . $sSepara . $sError;
	} //CONSECUTIVO
	*/
	if ($cara09idpregunta == '') {
		$sError = $ERR['cara09idpregunta'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$cara09id == 0) {
			if ((int)$cara09consec == 0) {
				$cara09consec = tabla_consecutivo('cara09pregrpta', 'cara09consec', 'cara09idpregunta=' . $cara09idpregunta . '', $objDB);
				if ($cara09consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'cara09consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM cara09pregrpta WHERE cara09idpregunta=' . $cara09idpregunta . ' AND cara09consec=' . $cara09consec . '';
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
				$cara09id = tabla_consecutivo('cara09pregrpta', 'cara09id', '', $objDB);
				if ($cara09id == -1) {
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
		//Si el campo cara09contenido permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara09contenido = str_replace('"', '\"', $cara09contenido);
		$cara09contenido = str_replace('"', '\"', $cara09contenido);
		if ($bInserta) {
			$sCampos2309 = 'cara09idpregunta, cara09consec, cara09id, cara09valor, cara09contenido';
			$sValores2309 = '' . $cara09idpregunta . ', ' . $cara09consec . ', ' . $cara09id . ', ' . $cara09valor . ', "' . $cara09contenido . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO cara09pregrpta (' . $sCampos2309 . ') VALUES (' . cadena_codificar($sValores2309) . ');';
			} else {
				$sSQL = 'INSERT INTO cara09pregrpta (' . $sCampos2309 . ') VALUES (' . $sValores2309 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 2309 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2309].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $cara09id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2309[1] = 'cara09valor';
			$scampo2309[2] = 'cara09contenido';
			$svr2309[1] = $cara09valor;
			$svr2309[2] = $cara09contenido;
			$iNumCampos = 2;
			$sWhere = 'cara09id=' . $cara09id . '';
			//$sWhere = 'cara09idpregunta=' . $cara09idpregunta . ' AND cara09consec=' . $cara09consec . '';
			$sSQL = 'SELECT * FROM cara09pregrpta WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo2309[$k]] != $svr2309[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2309[$k] . '="' . $svr2309[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE cara09pregrpta SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE cara09pregrpta SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 2309 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Respuestas}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $cara09id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $cara09id, $sDebug);
}
function f2309_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2309;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2309 = 'lg/lg_2309_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2309)) {
		$mensajes_2309 = 'lg/lg_2309_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2309;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$cara09idpregunta = numeros_validar($aParametros[1]);
	$cara09consec = numeros_validar($aParametros[2]);
	$cara09id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2309';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $cara09id . ' LIMIT 0, 1';
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
		$sWhere = 'cara09id=' . $cara09id . '';
		//$sWhere = 'cara09idpregunta=' . $cara09idpregunta . ' AND cara09consec=' . $cara09consec . '';
		$sSQL = 'DELETE FROM cara09pregrpta WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2309 Respuestas}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara09id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2309_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2309 = 'lg/lg_2309_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2309)) {
		$mensajes_2309 = 'lg/lg_2309_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2309;
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
	$cara08id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM cara08pregunta WHERE cara08id=' . $cara08id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2309" name="paginaf2309" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2309" name="lppf2309" type="hidden" value="' . $lineastabla . '"/>';
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
	if ($params[104] != '') {
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
	$sTitulos = 'Pregunta, Consec, Id, Valor, Contenido';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.cara09idpregunta, TB.cara09consec, TB.cara09id, TB.cara09valor, TB.cara09contenido';
	$sConsulta = 'FROM cara09pregrpta AS TB 
	WHERE ' . $sSQLadd1 . ' TB.cara09idpregunta=' . $cara08id . ' ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.cara09consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2309" name="consulta_2309" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2309" name="titulos_2309" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2309: ' . $sSQL . '<br>';
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
	$res = $res . '<th><b>' . $ETI['cara09consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['cara09contenido'] . '</b></th>';	
	$res = $res . '<th><b>' . $ETI['cara09valor'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf2309', $registros, $lineastabla, $pagina, 'paginarf2309()') . '';
	$res = $res . '' . html_lpp('lppf2309', $lineastabla, 'paginarf2309()') . '';
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		if ($filadet['cara09valor'] != 0) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_cara09consec = $sPrefijo . $filadet['cara09consec'] . $sSufijo;
		$et_cara09valor = $sPrefijo . formato_numero($filadet['cara09valor']) . $sSufijo;
		$et_cara09contenido = $sPrefijo . cadena_notildes($filadet['cara09contenido']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2309(' . $filadet['cara09id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_cara09consec . '</td>';
		$res = $res . '<td>' . $et_cara09contenido . '</td>';
		$res = $res . '<td>' . $et_cara09valor . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2309_Clonar($cara09idpregunta, $cara09idpreguntaPadre, $objdb)
{
	$sError = '';
	$cara09consec = tabla_consecutivo('cara09pregrpta', 'cara09consec', 'cara09idpregunta=' . $cara09idpregunta . '', $objdb);
	if ($cara09consec == -1) {
		$sError = $objdb->serror;
	}
	$cara09id = tabla_consecutivo('cara09pregrpta', 'cara09id', '', $objdb);
	if ($cara09id == -1) {
		$sError = $objdb->serror;
	}
	if ($sError == '') {
		$sCampos2309 = 'cara09idpregunta, cara09consec, cara09id, cara09valor, cara09contenido';
		$sValores2309 = '';
		$sql = 'SELECT * FROM cara09pregrpta WHERE cara09idpregunta=' . $cara09idpreguntaPadre . '';
		$tabla = $objdb->ejecutasql($sql);
		while ($fila = $objdb->sf($tabla)) {
			if ($sValores2309 != '') {
				$sValores2309 = $sValores2309 . ', ';
			}
			$sValores2309 = $sValores2309 . '(' . $cara09idpregunta . ', ' . $cara09consec . ', ' . $cara09id . ', ' . $fila['cara09valor'] . ', "' . $fila['cara09contenido'] . '")';
			$cara09consec++;
			$cara09id++;
		}
		if ($sValores2309 != '') {
			$sql = 'INSERT INTO cara09pregrpta(' . $sCampos2309 . ') VALUES ' . $sValores2309 . '';
			$result = $objdb->ejecutasql($sql);
		}
	}
	return $sError;
}
// -- 2309 Respuestas XAJAX 
function f2309_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $cara09id, $sDebugGuardar) = f2309_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2309_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2309detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf2309(' . $cara09id . ')');
		} else {
		*/
		$objResponse->call('limpiaf2309');
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
function f2309_Traer($aParametros)
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
	if ($paso == 1) {
		$cara09idpregunta = numeros_validar($aParametros[1]);
		$cara09consec = numeros_validar($aParametros[2]);
		if (($cara09idpregunta != '') && ($cara09consec != '')) {
			$besta = true;
		}
	} else {
		$cara09id = $aParametros[103];
		if ((int)$cara09id != 0) {
			$besta = true;
		}
	}
	if ($besta) {
		$besta = false;
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'cara09idpregunta=' . $cara09idpregunta . ' AND cara09consec=' . $cara09consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'cara09id=' . $cara09id . '';
		}
		$sSQL = 'SELECT * FROM cara09pregrpta WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 2);
		$cara09consec_nombre = '';
		$html_cara09consec = html_oculto('cara09consec', $fila['cara09consec'], $cara09consec_nombre);
		$objResponse->assign('div_cara09consec', 'innerHTML', $html_cara09consec);
		$cara09id_nombre = '';
		$html_cara09id = html_oculto('cara09id', $fila['cara09id'], $cara09id_nombre);
		$objResponse->assign('div_cara09id', 'innerHTML', $html_cara09id);
		$objResponse->assign('cara09valor', 'value', $fila['cara09valor']);
		$objResponse->assign('cara09contenido', 'value', cadena_LimpiarXAJAX($fila['cara09contenido']));
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2309', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('cara09consec', 'value', $cara09consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $cara09id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2309_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f2309_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2309_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2309detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2309');
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
function f2309_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2309_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2309detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2309_PintarLlaves($aParametros)
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
	require $mensajes_todas;
	$iPiel = iDefinirPiel($APP, 2);
	$html_cara09consec = '<input id="cara09consec" name="cara09consec" type="text" value="" onchange="revisaf2309()" class="cuatro" />';
	$html_cara09id = '<input id="cara09id" name="cara09id" type="hidden" value="" />';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cara09consec', 'innerHTML', $html_cara09consec);
	$objResponse->assign('div_cara09id', 'innerHTML', $html_cara09id);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

