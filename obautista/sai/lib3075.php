<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.16 jueves, 10 de julio de 2025
--- 3075 Red de servicio - equipos
*/
function f3075_HTMLComboV2_saiu75idzona($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3075 = 'lg/lg_3075_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3075)) {
		$mensajes_3075 = 'lg/lg_3075_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3075;
	$objCombos->nuevo('saiu75idzona', $valor, true, '{' . $ETI['msg_nacional'] . '}', 0);
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'carga_combo_saiu75idcentro()';
	$sSQL = 'SELECT TB.unad23id AS id, TB.unad23nombre AS nombre 
	FROM unad23zona AS TB
	WHERE TB.unad23id>0
	ORDER BY TB.unad23conestudiantes DESC, TB.unad23nombre';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3075_HTMLComboV2_saiu75idcentro($objDB, $objCombos, $valor, $vrsaiu75idzona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3075 = 'lg/lg_3075_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3075)) {
		$mensajes_3075 = 'lg/lg_3075_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3075;
	$sSQL = '';
	$et_combo = '{' . $ETI['msg_na'] . '}';
	if ((int)$vrsaiu75idzona != 0) {
		$et_combo = '{' . $ETI['msg_zonal'] . '}';
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrsaiu75idzona . ' 
		ORDER BY TB.unad24nombre';
	}
	$objCombos->nuevo('saiu75idcentro', $valor, true, $et_combo, 0);
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'revisaf3075()';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3075_Combosaiu75idcentro($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_saiu75idcentro = f3075_HTMLComboV2_saiu75idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu75idcentro', 'innerHTML', $html_saiu75idcentro);
	//$objResponse->call('$("#saiu75idcentro").chosen()');
	return $objResponse;
}
function f3075_HTMLComboV2_saiu75idequipo($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu75idequipo', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = 'SELECT TB.bita27id AS id, TB.bita27nombre AS nombre 
	FROM bita27equipotrabajo AS TB
	WHERE TB.bita27id>0
	ORDER BY TB.bita27nombre';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3075_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 3075)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3075 = 'lg/lg_3075_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3075)) {
		$mensajes_3075 = 'lg/lg_3075_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3075;
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
	$saiu75idred = numeros_validar($valores[1]);
	$saiu75idzona = numeros_validar($valores[2]);
	$saiu75idcentro = numeros_validar($valores[3]);
	$saiu75id = numeros_validar($valores[4], true);
	$saiu75idequipo = numeros_validar($valores[5]);
	/*
	if ($saiu75idequipo == '') {
		$saiu75idequipo = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($saiu75idequipo == '') {
		$sError = $ERR['saiu75idequipo'] . $sSepara . $sError;
	}
	/*
	if ($saiu75id == '') {
		$sError = $ERR['saiu75id'] . $sSepara . $sError;
	}
	*/
	if ($saiu75idcentro == '') {
		$sError = $ERR['saiu75idcentro'] . $sSepara . $sError;
	}
	if ($saiu75idzona == '') {
		$sError = $ERR['saiu75idzona'] . $sSepara . $sError;
	}
	if ($saiu75idred == '') {
		$sError = $ERR['saiu75idred'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$saiu75id == 0) {
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM saiu75redequipo WHERE saiu75idred=' . $saiu75idred . ' AND saiu75idzona=' . $saiu75idzona . ' AND saiu75idcentro=' . $saiu75idcentro . '';
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
				$saiu75id = tabla_consecutivo('saiu75redequipo', 'saiu75id', '', $objDB);
				if ($saiu75id == -1) {
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
			$sCampos3075 = 'saiu75idred, saiu75idzona, saiu75idcentro, saiu75id, saiu75idequipo';
			$sValores3075 = '' . $saiu75idred . ', ' . $saiu75idzona . ', ' . $saiu75idcentro . ', ' . $saiu75id . ', ' . $saiu75idequipo . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO saiu75redequipo (' . $sCampos3075 . ') VALUES (' . cadena_codificar($sValores3075) . ');';
			} else {
				$sSQL = 'INSERT INTO saiu75redequipo (' . $sCampos3075 . ') VALUES (' . $sValores3075 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3075 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3075].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu75id, $sSQL, $objDB);
				}
			}
		} else {
			$iNumCampos = 0;
			$sWhere = 'saiu75id=' . $saiu75id . '';
			//$sWhere = 'saiu75idred=' . $saiu75idred . ' AND saiu75idzona=' . $saiu75idzona . ' AND saiu75idcentro=' . $saiu75idcentro . '';
			$sSQL = 'SELECT * FROM saiu75redequipo WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo3075[$k]] != $svr3075[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo3075[$k] . '="' . $svr3075[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE saiu75redequipo SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE saiu75redequipo SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3075 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Red de servicio - equipos}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu75id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $saiu75id, $sDebug);
}
function f3075_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3075;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3075 = 'lg/lg_3075_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3075)) {
		$mensajes_3075 = 'lg/lg_3075_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3075;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$saiu75idred = numeros_validar($aParametros[1]);
	$saiu75idzona = numeros_validar($aParametros[2]);
	$saiu75idcentro = numeros_validar($aParametros[3]);
	$saiu75id = numeros_validar($aParametros[4]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3075';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $saiu75id . ' LIMIT 0, 1';
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
		$sWhere = 'saiu75id=' . $saiu75id . '';
		//$sWhere = 'saiu75idred=' . $saiu75idred . ' AND saiu75idzona=' . $saiu75idzona . ' AND saiu75idcentro=' . $saiu75idcentro . '';
		$sSQL = 'DELETE FROM saiu75redequipo WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {3075 Red de servicio - equipos}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu75id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f3075_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3075 = 'lg/lg_3075_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3075)) {
		$mensajes_3075 = 'lg/lg_3075_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3075;
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
	$saiu74id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM saiu74reddeservicio WHERE saiu74id=' . $saiu74id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3075" name="paginaf3075" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf3075" name="lppf3075" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Red, Zona, Centro, Id, Equipo';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.saiu75idred, T2.unad23nombre, T3.unad24nombre, TB.saiu75id, T5.bita27nombre, TB.saiu75idzona, TB.saiu75idcentro, TB.saiu75idequipo';
	$sConsulta = 'FROM saiu75redequipo AS TB, unad23zona AS T2, unad24sede AS T3, bita27equipotrabajo AS T5 
	WHERE ' . $sSQLadd1 . ' TB.saiu75idred=' . $saiu74id . ' AND TB.saiu75idzona=T2.unad23id AND TB.saiu75idcentro=T3.unad24id AND TB.saiu75idequipo=T5.bita27id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.saiu75idzona, TB.saiu75idcentro';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3075" name="consulta_3075" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_3075" name="titulos_3075" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3075: ' . $sSQL . '<br>';
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
	$res = $res . '<th><b>' . $ETI['saiu75idzona'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu75idcentro'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu75idequipo'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf3075', $registros, $lineastabla, $pagina, 'paginarf3075()') . '';
	$res = $res . '' . html_lpp('lppf3075', $lineastabla, 'paginarf3075()') . '';
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
		$et_saiu75idzona = $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo;
		$et_saiu75idcentro = $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo;
		$et_saiu75idequipo = $sPrefijo . cadena_notildes($filadet['bita27nombre']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3075(' . $filadet['saiu75id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_saiu75idzona . '</td>';
		$res = $res . '<td>' . $et_saiu75idcentro . '</td>';
		$res = $res . '<td>' . $et_saiu75idequipo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 3075 Red de servicio - equipos XAJAX 
function f3075_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $saiu75id, $sDebugGuardar) = f3075_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f3075_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3075detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf3075(' . $saiu75id . ')');
		} else {
		*/
		$objResponse->call('limpiaf3075');
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
function f3075_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_3075 = 'lg/lg_3075_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3075)) {
		$mensajes_3075 = 'lg/lg_3075_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_3075;
	$sError = '';
	$bDebug = false;
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$saiu75idred = numeros_validar($aParametros[1]);
		$saiu75idzona = numeros_validar($aParametros[2]);
		$saiu75idcentro = numeros_validar($aParametros[3]);
		if (($saiu75idred != '') && ($saiu75idzona != '') && ($saiu75idcentro != '')) {
			$besta = true;
		}
	} else {
		$saiu75id = $aParametros[103];
		if ((int)$saiu75id != 0) {
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
			$sSQLcondi = $sSQLcondi . 'saiu75idred=' . $saiu75idred . ' AND saiu75idzona=' . $saiu75idzona . ' AND saiu75idcentro=' . $saiu75idcentro . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'saiu75id=' . $saiu75id . '';
		}
		$sSQL = 'SELECT * FROM saiu75redequipo WHERE ' . $sSQLcondi;
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
		$saiu75idzona_nombre = '&nbsp;';
		//$saiu75idzona_nombre = $asaiu75idzona[$fila['saiu75idzona']];
		if ($fila['saiu75idzona'] != 0) {
			list($saiu75idzona_nombre, $serror_det) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $fila['saiu75idzona'], '{' . $ETI['msg_sindato'] . '}', $objDB);
			$saiu75idzona_nombre = cadena_LimpiarXAJAX($saiu75idzona_nombre);
		}
		$html_saiu75idzona = html_oculto('saiu75idzona', $fila['saiu75idzona'], $saiu75idzona_nombre);
		$objResponse->assign('div_saiu75idzona', 'innerHTML', $html_saiu75idzona);
		$html_saiu75idcentro = f3075_HTMLComboV2_saiu75idcentro($objDB, $objCombos, $fila['saiu75idcentro'], $fila['saiu75idzona']);
		$objResponse->assign('div_saiu75idcentro', 'innerHTML', $html_saiu75idcentro);
		$saiu75id_nombre = '';
		$html_saiu75id = html_oculto('saiu75id', $fila['saiu75id'], $saiu75id_nombre);
		$objResponse->assign('div_saiu75id', 'innerHTML', $html_saiu75id);
		$saiu75idequipo_nombre = '&nbsp;';
		//$saiu75idequipo_nombre = $asaiu75idequipo[$fila['saiu75idequipo']];
		if ($fila['saiu75idequipo'] != 0) {
			list($saiu75idequipo_nombre, $serror_det) = tabla_campoxid('bita27equipotrabajo', 'bita27nombre', 'bita27id', $fila['saiu75idequipo'], '{' . $ETI['msg_sindato'] . '}', $objDB);
			$saiu75idequipo_nombre = cadena_LimpiarXAJAX($saiu75idequipo_nombre);
		}
		$html_saiu75idequipo = html_oculto('saiu75idequipo', $fila['saiu75idequipo'], $saiu75idequipo_nombre);
		$objResponse->assign('div_saiu75idequipo', 'innerHTML', $html_saiu75idequipo);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3075', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('saiu75idzona', 'value', $saiu75idzona);
			$objResponse->assign('saiu75idcentro', 'value', $saiu75idcentro);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $saiu75id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f3075_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f3075_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f3075_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3075detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3075');
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
function f3075_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3075_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3075detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3075_PintarLlaves($aParametros)
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
	$mensajes_3075 = 'lg/lg_3075_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3075)) {
		$mensajes_3075 = 'lg/lg_3075_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_3075;
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$html_saiu75idzona = f3075_HTMLComboV2_saiu75idzona($objDB, $objCombos, '');
	$html_saiu75idcentro = f3075_HTMLComboV2_saiu75idcentro($objDB, $objCombos, '', '');
	$html_saiu75id = '<input id="saiu75id" name="saiu75id" type="hidden" value="" />';
	$html_saiu75idequipo = f3075_HTMLComboV2_saiu75idequipo($objDB, $objCombos, '');
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu75idzona', 'innerHTML', $html_saiu75idzona);
	$objResponse->call('$("#saiu75idzona").chosen()');
	$objResponse->assign('div_saiu75idcentro', 'innerHTML', $html_saiu75idcentro);
	$objResponse->call('$("#saiu75idcentro").chosen()');
	$objResponse->assign('div_saiu75id', 'innerHTML', $html_saiu75id);
	$objResponse->assign('div_saiu75idequipo', 'innerHTML', $html_saiu75idequipo);
	$objResponse->call('$("#saiu75idequipo").chosen()');
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

