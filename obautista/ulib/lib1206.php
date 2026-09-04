<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5b lunes, 23 de marzo de 2026
--- 1206 Poblacion
*/
function f1206_NombreTabla($iComplemento, $objDB) {
	$sError = '';
	$iBloque = numeros_validar($iComplemento);
	$sTabla1206 = 'masi06mensajepoblacion_' . $iBloque;
	if (!$objDB->bexistetabla($sTabla1206)) {
		$sError = 'No ha sido posible determinar el origen de los datos.';
	}
	return array($sTabla1206, $sError);
}
function f1206_HTMLComboV2_masi06centro($objDB, $objCombos, $valor, $vrmasi06zona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('masi06centro', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrmasi06zona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrmasi06zona . ' 
		ORDER BY TB.unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1206, $sIdioma
	return $res;
}
function f1206_Combomasi06centro($aParametros)
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
	$html_masi06centro = f1206_HTMLComboV2_masi06centro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_masi06centro', 'innerHTML', $html_masi06centro);
	return $objResponse;
}
function f1206_HTMLComboV2_masi06programa($objDB, $objCombos, $valor, $vrmasi06escuela, $vrmasi06nivelforma)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('masi06programa', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	$objCombos->sAccion = 'carga_combo_masi06curso();';
	$objCombos->iAncho = 370;
	$objCombos->bEsCombobox = true;
	$sSQL = '';
	if ((int)$vrmasi06escuela != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sCondi = '';
		if ((int)$vrmasi06nivelforma != 0) {
			$sCondi = ' AND cara09nivelformacion=' . $vrmasi06nivelforma . '';
		}
		$sSQL = 'SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]") AS nombre 
		FROM core09programa AS TB
		WHERE TB.core09idescuela=' . $vrmasi06escuela . $sCondi . ' 
		ORDER BY TB.core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1206, $sIdioma
	return $res;
}
function f1206_Combomasi06programa($aParametros)
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
	$html_masi06programa = f1206_HTMLComboV2_masi06programa($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_masi06programa', 'innerHTML', $html_masi06programa);
	$objResponse->call('createComboboxById("masi06programa")');
	$objResponse->call('carga_combo_masi06curso()');
	return $objResponse;
}
function f1206_HTMLComboV2_masi06curso($objDB, $objCombos, $valor, $vrmasi06idperiodo, $vrmasi06programa, $vrmasi06escuela)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('masi06curso', $valor, true, '{' . $ETI['msg_ninguno'] . '}', 0);
	$objCombos->bEsCombobox = true;
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrmasi06idperiodo != 0) {
		list($sIds) = f140_CursosPeriodo($vrmasi06idperiodo, $objDB);
		//$objCombos->addItem('0', '[Sin Dato]');
		$sCondi = '';
		if ((int)$vrmasi06escuela != 0) {
			$sCondi = ' AND unad40idescuela=' . $vrmasi06escuela . '';
		}
		if ((int)$vrmasi06programa != 0) {
			$sCondi = ' AND unad40idprograma=' . $vrmasi06programa . '';
		}
		$sSQL = 'SELECT TB.unad40id AS id, CONCAT(TB.unad40titulo, " - ", TB.unad40nombre) AS nombre 
		FROM unad40curso AS TB
		WHERE TB.unad40id IN (' . $sIds . ')' . $sCondi . ' 
		ORDER BY TB.unad40nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1206, $sIdioma
	return $res;
}
function f1206_Combomasi06curso($aParametros)
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
	$html_masi06curso = f1206_HTMLComboV2_masi06curso($objDB, $objCombos, '', $aParametros[0], $aParametros[1], $aParametros[2]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_masi06curso', 'innerHTML', $html_masi06curso);
	$objResponse->call('createComboboxById("masi06curso")');
	return $objResponse;
}
function f1206_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 1206)
{
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1206)) {
		$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1206;
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
	$masi06idmensaje = numeros_validar($valores[1]);
	$masi06consec = numeros_validar($valores[2]);
	$masi06id = numeros_validar($valores[3], true);
	$masi06zona = numeros_validar($valores[4]);
	$masi06centro = numeros_validar($valores[5]);
	$masi06escuela = numeros_validar($valores[6]);
	$masi06nivelforma = numeros_validar($valores[7]);
	$masi06programa = numeros_validar($valores[8]);
	$masi06est_condicion = numeros_validar($valores[9]);
	$masi06sexo = numeros_validar($valores[10]);
	$masi06idperiodo = numeros_validar($valores[11]);
	$masi06curso = numeros_validar($valores[12]);
	$masi06docente = numeros_validar($valores[13]);
	$masi06unidadfunc = numeros_validar($valores[14]);
	$masi06agnogrado = numeros_validar($valores[15]);
	$bFiltroVacio = true;
	for ($i=4; $i <=15 ; $i++) { 
		if ((int) $valores[$i] > 0) {
			$bFiltroVacio = false;
		}
	}
	/*
	if ($masi06zona == '') {
		$masi06zona = 0;
	}
	if ($masi06centro == '') {
		$masi06centro = 0;
	}
	if ($masi06escuela == '') {
		$masi06escuela = 0;
	}
	if ($masi06nivelforma == '') {
		$masi06nivelforma = 0;
	}
	if ($masi06programa == '') {
		$masi06programa = 0;
	}
	if ($masi06est_condicion == '') {
		$masi06est_condicion = 0;
	}
	if ($masi06sexo == '') {
		$masi06sexo = 0;
	}
	*/
	if ($masi06idperiodo == '') {
		$masi06idperiodo = 0;
	}
	if ($masi06curso == '') {
		$masi06curso = 0;
	}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($masi06agnogrado == '') {
		$sError = $ERR['masi06agnogrado'] . $sSepara . $sError;
	}
	if ($masi06unidadfunc == '') {
		$sError = $ERR['masi06unidadfunc'] . $sSepara . $sError;
	}
	if ($masi06docente == '') {
		$sError = $ERR['masi06docente'] . $sSepara . $sError;
	}
	if ($masi06curso == '') {
		$sError = $ERR['masi06curso'] . $sSepara . $sError;
	}
	if ($masi06idperiodo == '') {
		$sError = $ERR['masi06idperiodo'] . $sSepara . $sError;
	}
	if ($masi06sexo == '') {
		$sError = $ERR['masi06sexo'] . $sSepara . $sError;
	}
	if ($masi06est_condicion == '') {
		$sError = $ERR['masi06est_condicion'] . $sSepara . $sError;
	}
	if ($masi06programa == '') {
		$sError = $ERR['masi06programa'] . $sSepara . $sError;
	}
	if ($masi06nivelforma == '') {
		$sError = $ERR['masi06nivelforma'] . $sSepara . $sError;
	}
	if ($masi06escuela == '') {
		$sError = $ERR['masi06escuela'] . $sSepara . $sError;
	}
	if ($masi06centro == '') {
		$sError = $ERR['masi06centro'] . $sSepara . $sError;
	}
	if ($masi06zona == '') {
		$sError = $ERR['masi06zona'] . $sSepara . $sError;
	}
	/*
	if ($masi06id == '') {
		$sError = $ERR['masi06id'] . $sSepara . $sError;
	}
	*/
	if ($masi06idmensaje == '') {
		$sError = $ERR['masi06idmensaje'] . $sSepara . $sError;
	}
	if ($bFiltroVacio) {
		$sError = $ERR['msg_bfiltro'] . $sSepara . $sError;
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		$bloque = numeros_validar($valores[97]);
		list($sTabla1205, $sError) = f1205_NombreTabla($bloque, $objDB);
		list($sTabla1206, $sErrorH) = f1206_NombreTabla($bloque, $objDB);
		$sError = $sError . $sErrorH;
	}
	if ($sError == '') {
		$sSQL = 'SELECT masi05idproceso FROM ' . $sTabla1205 . ' WHERE masi05id=' . $masi06idmensaje . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) != 0) {
			$fila = $objDB->sf($result);
			switch($fila['masi05idproceso']) {
				case 0: // - Ninguno
				case 2: // - Funcionarios
				case 3: // - Contratistas
				case 17: // - Egresados
				case 2741: // - Postulados a grados
					break;
				case 11: // - Aspirantes
				case 12: // - Estudiantes
				case 13: // - Estudiantes ausentes
				case 2209: // - Estudiantes del programa
				case 2306: // - Acompañamiento académico
				case 2307: // - Seguimiento académico
				case 12229: // - Convocados
					if ((int)$masi06idperiodo == 0) {
						$sError = $ERR['masi06idperiodo'] . $sSepara . $sError;
					}
					break;
			}
		} else {
			$sError = $ERR['masi06idmensaje'] . $sSepara . $sError;
		}
	}
	if ($sError == '') {
		if ((int)$masi06id == 0) {
			if ((int)$masi06consec == 0) {
				$masi06consec = tabla_consecutivo($sTabla1206, 'masi06consec', 'masi06idmensaje=' . $masi06idmensaje . '', $objDB);
				if ($masi06consec == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'masi06consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sTabla1206 . ' WHERE masi06idmensaje=' . $masi06idmensaje . ' AND masi06consec=' . $masi06consec . '';
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
				$masi06id = tabla_consecutivo($sTabla1206, 'masi06id', '', $objDB);
				if ($masi06id == -1) {
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
			$sCampos1206 = 'masi06idmensaje, masi06consec, masi06id, masi06zona, masi06centro, 
			masi06escuela, masi06nivelforma, masi06programa, masi06est_condicion, masi06sexo, 
			masi06idperiodo, masi06curso, masi06docente, masi06unidadfunc, masi06agnogrado';
			$sValores1206 = '' . $masi06idmensaje . ', ' . $masi06consec . ', ' . $masi06id . ', ' . $masi06zona . ', ' . $masi06centro . ', 
			' . $masi06escuela . ', ' . $masi06nivelforma . ', ' . $masi06programa . ', ' . $masi06est_condicion . ', ' . $masi06sexo . ', 
			' . $masi06idperiodo . ', ' . $masi06curso . ', ' . $masi06docente . ', ' . $masi06unidadfunc . ', ' . $masi06agnogrado . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla1206 . ' (' . $sCampos1206 . ') VALUES (' . cadena_codificar($sValores1206) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla1206 . ' (' . $sCampos1206 . ') VALUES (' . $sValores1206 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Guardar 1206 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [1206].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $masi06id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo1206[1] = 'masi06zona';
			$scampo1206[2] = 'masi06centro';
			$scampo1206[3] = 'masi06escuela';
			$scampo1206[4] = 'masi06nivelforma';
			$scampo1206[5] = 'masi06programa';
			$scampo1206[6] = 'masi06est_condicion';
			$scampo1206[7] = 'masi06sexo';
			$scampo1206[8] = 'masi06idperiodo';
			$scampo1206[9] = 'masi06curso';
			$scampo1206[10] = 'masi06docente';
			$scampo1206[11] = 'masi06unidadfunc';
			$scampo1206[12] = 'masi06agnogrado';
			$svr1206[1] = $masi06zona;
			$svr1206[2] = $masi06centro;
			$svr1206[3] = $masi06escuela;
			$svr1206[4] = $masi06nivelforma;
			$svr1206[5] = $masi06programa;
			$svr1206[6] = $masi06est_condicion;
			$svr1206[7] = $masi06sexo;
			$svr1206[8] = $masi06idperiodo;
			$svr1206[9] = $masi06curso;
			$svr1206[10] = $masi06docente;
			$svr1206[11] = $masi06unidadfunc;
			$svr1206[12] = $masi06agnogrado;
			$iNumCampos = 12;
			$sWhere = 'masi06id=' . $masi06id . '';
			//$sWhere = 'masi06idmensaje=' . $masi06idmensaje . ' AND masi06consec=' . $masi06consec . '';
			$sSQL = 'SELECT * FROM ' . $sTabla1206 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo1206[$k]] != $svr1206[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo1206[$k] . '="' . $svr1206[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sTabla1206 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sTabla1206 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Guardar 1206 ' . $sSQL . '');
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Poblacion}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $masi06id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $masi06id, $sDebug);
}
function f1206_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 1206;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1206)) {
		$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1206;
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
	$masi06idmensaje = numeros_validar($aParametros[1]);
	$masi06consec = numeros_validar($aParametros[2]);
	$masi06id = numeros_validar($aParametros[3]);
	$iCantidad = 0;
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1206';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $masi06id . ' LIMIT 0, 1';
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
		list($sTabla1205, $sError) = f1205_NombreTabla($bloque, $objDB);
		list($sTabla1206, $sErrorH) = f1206_NombreTabla($bloque, $objDB);
		$sError = $sError . $sErrorH;
		list($sTabla1208, $sErrorH) = f1208_NombreTabla($bloque, $objDB);
		$sError = $sError . $sErrorH;
	}
	if ($sError == '') {
		$sSQL = 'SELECT masi06idmensaje FROM ' . $sTabla1206 . ' WHERE masi06id=' . $masi06id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$masi05id = $fila['masi06idmensaje'];
		} else {
			$sError = $ETI['msg_no_encontrado'] . ' [Ref: ' . $masi06id . ']';
		}
	}
	if ($sError == '') {
		//acciones previas
		$sSQL = 'DELETE FROM ' . $sTabla1208 . ' WHERE masi08idmensaje=' . $masi05id . ' AND masi08idpoblacion=' . $masi06id . '';
		$result = $objDB->ejecutasql($sSQL);
		$sSQL = 'SELECT * FROM ' . $sTabla1208 . ' WHERE masi08idmensaje=' . $masi05id . '';
		$tabla08 = $objDB->ejecutasql($sSQL);
		$iCantidad = $objDB->nf($tabla08);
		$sSQL = 'UPDATE ' . $sTabla1205 . ' SET masi05total_usuarios=' . $iCantidad . '';
		$result = $objDB->ejecutasql($sSQL);
		$sWhere = 'masi06id=' . $masi06id . '';
		//$sWhere = 'masi06idmensaje=' . $masi06idmensaje . ' AND masi06consec=' . $masi06consec . '';
		$sSQL = 'DELETE FROM ' . $sTabla1206 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {1206 Poblacion}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $masi06id, $sSQL, $objDB);
			}
		}
	}
	return array($iCantidad, $sError, $sDebug);
}
function f1206_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1206)) {
		$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1206;
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
	$masi05idproceso = 0;
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	}
	list($sTabla1206, $sLeyenda) = f1206_NombreTabla($bloque, $objDB);
	if ($sLeyenda == '') {
		list($sTabla1205, $sLeyenda) = f1205_NombreTabla($bloque, $objDB);
	}
	if ($sLeyenda == '') {
		list($sTabla1208, $sLeyenda) = f1208_NombreTabla($bloque, $objDB);
	}
	$sBotones = '<input id="paginaf1206" name="paginaf1206" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf1206" name="lppf1206" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$bAbierta = false;
	$bAplicaUnidadFuncional = false;
	$bAplicaEscuela = false;
	$bAplicaPrograma = false;
	$bAplicaPeriodo = false;
	$bAplicaCurso = false;
	$bAplicaGrado = false;
	$sSQL = 'SELECT masi05estado, masi05idproceso FROM ' . $sTabla1205 . ' WHERE masi05id=' . $masi05id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$masi05idproceso = $fila['masi05idproceso'];
		if ($fila['masi05estado'] == 0) {
			$bAbierta = true;
		}
	}
	switch ($masi05idproceso) {
		case 0: // - Ninguno
			break;
		case 2: // - Funcionarios
		case 3: // - Contratistas
			$bAplicaUnidadFuncional = true;
			break;
		case 11: // - Aspirantes
		case 2306: // - Acompañamiento académico
		case 2307: // - Seguimiento académico
		case 12229: // - Convocados
			$bAplicaEscuela = true;
			$bAplicaPrograma = true;
			$bAplicaPeriodo = true;
			break;
		case 12: // - Estudiantes
		case 13: // - Estudiantes ausentes
		case 2209: // - Estudiantes del programa
			$bAplicaEscuela = true;
			$bAplicaPrograma = true;
			$bAplicaPeriodo = true;
			$bAplicaCurso = true;
			break;
		case 17: // - Egresados
			$bAplicaEscuela = true;
			$bAplicaPrograma = true;
			$bAplicaGrado = true;
			break;
		case 2741: // - Postulados a grados
			$bAplicaEscuela = true;
			$bAplicaPrograma = true;
			break;
	}
	$iPiel = iDefinirPiel($APP, 2);
	$aEscuela = array('- -');
	$aZona = array('- -');
	$aUnidadFunc = array('- -');
	$sSQL = 'SELECT core12id, core12sigla, core12nombre FROM core12escuela WHERE core12id>0';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEscuela[$fila['core12id']] = cadena_notildes($fila['core12sigla']);
	}
	$sSQL = 'SELECT unad23id, unad23sigla, unad23nombre FROM unad23zona WHERE unad23id>0';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aZona[$fila['unad23id']] = cadena_notildes($fila['unad23sigla']);
	}
	$sSQL = 'SELECT unae26id, unae26nombre FROM unae26unidadesfun WHERE unae26idzona=0 AND unae26id>0';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aUnidadFunc[$fila['unae26id']] = cadena_notildes($fila['unae26nombre']);
	}
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
	$sTitulos = 'Mensaje, Consec, Id, Zona, Centro, Escuela, Nivelforma, Programa, Est_condicion, Sexo, Periodo, Curso';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.masi06idmensaje, TB.masi06consec, TB.masi06id, T5.unad24nombre, T7.core22nombre, 
	T8.core09nombre, TB.masi06est_condicion, TB.masi06sexo, TB.masi06zona, TB.masi06centro, 
	TB.masi06escuela, TB.masi06nivelforma, TB.masi06programa, TB.masi06idperiodo, TB.masi06curso, 
	TB.masi06docente, TB.masi06unidadfunc, TB.masi06agnogrado, T40.unad40nombre, T2.exte02nombre';
	$sConsulta = 'FROM ' . $sTabla1206 . ' AS TB, unad24sede AS T5, core22nivelprograma AS T7, core09programa AS T8, unad40curso AS T40, exte02per_aca AS T2 
	WHERE ' . $sSQLadd1 . ' TB.masi06idmensaje=' . $masi05id . ' AND TB.masi06centro=T5.unad24id 
	AND TB.masi06nivelforma=T7.core22id AND TB.masi06programa=T8.core09id AND TB.masi06curso=T40.unad40id 
	AND TB.masi06idperiodo=T2.exte02id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.masi06consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_1206" name="consulta_1206" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_1206" name="titulos_1206" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 1206: ' . $sSQL . '');
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
	if ($bAplicaUnidadFuncional) {
		$res = $res . '<th><b>' . $ETI['masi06unidadfunc'] . '</b></th>';
	}
	$res = $res . '<th><b>' . $ETI['masi06zona'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi06centro'] . '</b></th>';
	if ($bAplicaEscuela) {
		$res = $res . '<th><b>' . $ETI['masi06escuela'] . '</b></th>';
	}
	if ($bAplicaPrograma) {
		$res = $res . '<th><b>' . $ETI['masi06nivelforma'] . '</b></th>';
		$res = $res . '<th><b>' . $ETI['masi06programa'] . '</b></th>';
	}
	if ($bAplicaPeriodo) {
		$res = $res . '<th><b>' . $ETI['masi06idperiodo'] . '</b></th>';
	}
	if ($bAplicaCurso) {
		$res = $res . '<th><b>' . $ETI['masi06curso'] . '</b></th>';
	}
	if ($bAplicaGrado) {
		$res = $res . '<th><b>' . $ETI['masi06agnogrado'] . '</b></th>';
	}
	$res = $res . '<th><b>' . $ETI['masi06est_condicion'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi06sexo'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . '' . html_paginador('paginaf1206', $registros, $lineastabla, $pagina, 'paginarf1206()') . '';
	$res = $res . '' . html_lpp('lppf1206', $lineastabla, 'paginarf1206()') . '';
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		$sLink2 = '';
		$sLink3 = '';
		if (false) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_masi06unidadfunc = $sPrefijo . $ETI['msg_todas'] . $sSufijo;
		if ($filadet['masi06unidadfunc'] > 0) {
			$et_masi06unidadfunc = $sPrefijo . $aUnidadFunc[$filadet['masi06unidadfunc']] . $sSufijo;
		}
		$et_masi06zona = $sPrefijo . $ETI['msg_todas'] . $sSufijo;
		if ($filadet['masi06zona'] > 0) {
			$et_masi06zona = $sPrefijo . $aZona[$filadet['masi06zona']] . $sSufijo;
		}
		$et_masi06centro = $sPrefijo . $ETI['msg_todos'] . $sSufijo;
		if ($filadet['masi06centro'] > 0) {
			$et_masi06centro = $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo;
		}
		$et_masi06escuela = $sPrefijo . $ETI['msg_todas'] . $sSufijo;
		if ($filadet['masi06escuela'] > 0) {
			$et_masi06escuela = $sPrefijo . $aEscuela[$filadet['masi06escuela']] . $sSufijo;
		}
		$et_masi06nivelforma = $sPrefijo . $ETI['msg_todos'] . $sSufijo;
		if ($filadet['masi06nivelforma'] > 0) {
			$et_masi06nivelforma = $sPrefijo . cadena_notildes($filadet['core22nombre']) . $sSufijo;
		}
		$et_masi06programa = $sPrefijo . $ETI['msg_todos'] . $sSufijo;
		if ($filadet['masi06programa'] > 0) {
			$et_masi06programa = $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo;
		}
		$et_masi06idperiodo = $sPrefijo . $ETI['msg_todos'] . $sSufijo;
		if ($filadet['masi06idperiodo'] > 0) {
			$et_masi06idperiodo = $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo;
		}
		$et_masi06curso = $sPrefijo . $ETI['msg_todos'] . $sSufijo;
		if ($filadet['masi06curso'] > 0) {
			$et_masi06curso = $sPrefijo . cadena_notildes($filadet['unad40nombre']) . $sSufijo;
		}
		$et_masi06est_condicion = $sPrefijo . $ETI['msg_todos'] . $sSufijo;
		if ($filadet['masi06est_condicion'] > 0) {
			$et_masi06est_condicion = $sPrefijo . $amasi06est_condicion[$filadet['masi06est_condicion']] . $sSufijo;
		}
		$et_masi06sexo = $sPrefijo . $ETI['msg_todos'] . $sSufijo;
		if ($filadet['masi06sexo'] > 0) {
			$et_masi06sexo = $sPrefijo . $amasi06sexo[$filadet['masi06sexo']] . $sSufijo;
		}
		$et_masi06agnogrado = $sPrefijo . $ETI['msg_todos'] . $sSufijo;
		if ($filadet['masi06agnogrado'] > 0) {
			$et_masi06agnogrado = $sPrefijo . $filadet['masi06agnogrado'] . $sSufijo;
		}
		$sSQL = 'SELECT 1 FROM ' . $sTabla1208 . ' WHERE masi08idmensaje=' . $masi05id . ' AND masi08idpoblacion=' . $filadet['masi06id'] . '';
		$tabla08 = $objDB->ejecutasql($sSQL);
		$iTotal = $objDB->nf($tabla08);
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf1206(' . $filadet['masi06id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
			$sLink2 = '<a href="javascript:procesarf1206(' . $filadet['masi06id'] . ')" class="lnkresalte">' . $ETI['lnk_procesar'] . '</a>';
			if ($iTotal > 0) {
				$sLink3 = '<a href="javascript:reversarf1206(' . $filadet['masi06id'] . ')" class="lnkresalte">' . $ETI['lnk_reversa'] . ' [' . formato_numero($iTotal) . ']</a>';
			}
		} else {
			$sLink3 = $sPrefijo . formato_numero($iTotal) . $sSufijo;
		}
		$res = $res . '<tr' . $sClass . '>';
		if ($bAplicaUnidadFuncional) {
			$res = $res . '<td>' . $et_masi06unidadfunc . '</td>';
		}
		$res = $res . '<td>' . $et_masi06zona . '</td>';
		$res = $res . '<td>' . $et_masi06centro . '</td>';
		if ($bAplicaEscuela) {
			$res = $res . '<td>' . $et_masi06escuela . '</td>';
		}
		if ($bAplicaPrograma) {
			$res = $res . '<td>' . $et_masi06nivelforma . '</td>';
			$res = $res . '<td>' . $et_masi06programa . '</td>';
		}
		if ($bAplicaPeriodo) {
			$res = $res . '<td>' . $et_masi06idperiodo . '</td>';
		}
		if ($bAplicaCurso) {
			$res = $res . '<td>' . $et_masi06curso . '</td>';
		}
		if ($bAplicaGrado) {
			$res = $res . '<td>' . $et_masi06agnogrado . '</td>';
		}
		$res = $res . '<td>' . $et_masi06est_condicion . '</td>';
		$res = $res . '<td>' . $et_masi06sexo . '</td>';
		$res = $res . '<td align="right">' . $sLink2 . '</td>';
		$res = $res . '<td align="right">' . $sLink3 . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 1206 Poblacion XAJAX 
function f1206_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $masi06id, $sDebugGuardar) = f1206_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f1206_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f1206detalle', 'innerHTML', $sdetalle);
		/*
		if ($iAccion == 2) {
			$objResponse->call('cargaridf1206(' . $masi06id . ')');
		} else {
		*/
		$objResponse->call('limpiaf1206');
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
function f1206_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1206)) {
		$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_1206;
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
		$masi06idmensaje = numeros_validar($aParametros[1]);
		$masi06consec = numeros_validar($aParametros[2]);
		if (($masi06idmensaje != '') && ($masi06consec != '')) {
			$besta = true;
		}
	} else {
		$masi06id = $aParametros[103];
		if ((int)$masi06id != 0) {
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
		list($sTabla1206, $sError) = f1206_NombreTabla($bloque, $objDB);
		if ($sError != '') {
			$besta = false;
		}
	}
	if ($besta) {
		$besta = false;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'masi06idmensaje=' . $masi06idmensaje . ' AND masi06consec=' . $masi06consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'masi06id=' . $masi06id . '';
		}
		$sSQL = 'SELECT * FROM ' . $sTabla1206 . ' WHERE ' . $sSQLcondi;
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
		$masi06consec_nombre = '';
		$html_masi06consec = html_oculto('masi06consec', $fila['masi06consec'], $masi06consec_nombre);
		$objResponse->assign('div_masi06consec', 'innerHTML', $html_masi06consec);
		$masi06id_nombre = '';
		$html_masi06id = html_oculto('masi06id', $fila['masi06id'], $masi06id_nombre);
		$objResponse->assign('div_masi06id', 'innerHTML', $html_masi06id);
		$objResponse->assign('masi06zona', 'value', $fila['masi06zona']);
		$html_masi06centro = f1206_HTMLComboV2_masi06centro($objDB, $objCombos, $fila['masi06centro'], $fila['masi06zona']);
		$objResponse->assign('div_masi06centro', 'innerHTML', $html_masi06centro);
		$objResponse->assign('masi06escuela', 'value', $fila['masi06escuela']);
		$objResponse->assign('masi06nivelforma', 'value', $fila['masi06nivelforma']);
		$html_masi06programa = f1206_HTMLComboV2_masi06programa($objDB, $objCombos, $fila['masi06programa'], $fila['masi06escuela'], $fila['masi06nivelforma']);
		$objResponse->assign('div_masi06programa', 'innerHTML', $html_masi06programa);
		$objResponse->call('createComboboxById("masi06programa")');
		$objResponse->assign('masi06est_condicion', 'value', $fila['masi06est_condicion']);
		$objResponse->assign('masi06sexo', 'value', $fila['masi06sexo']);
		$html_masi06idperiodo = f1206_HTMLComboV2_masi06idperiodo($objDB, $objCombos, $fila['masi06idperiodo']);
		$objResponse->assign('div_masi06idperiodo', 'innerHTML', $html_masi06idperiodo);
		$objResponse->call('createComboboxById("masi06idperiodo")');
		$html_masi06curso = f1206_HTMLComboV2_masi06curso($objDB, $objCombos, $fila['masi06curso'], $fila['masi06idperiodo'], $fila['masi06programa'], $fila['masi06escuela']);
		$objResponse->assign('div_masi06curso', 'innerHTML', $html_masi06curso);
		$objResponse->call('createComboboxById("masi06curso")');
		$objResponse->assign('masi06docente', 'value', $fila['masi06docente']);
		$html_masi06unidadfunc = f1206_HTMLComboV2_masi06unidadfunc($objDB, $objCombos, $fila['masi06unidadfunc']);
		$objResponse->assign('div_masi06unidadfunc', 'innerHTML', $html_masi06unidadfunc);
		$objResponse->call('createComboboxById("masi06unidadfunc")');
		$objResponse->assign('masi06agnogrado', 'value', $fila['masi06agnogrado']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1206', 'block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('masi06consec', 'value', $masi06consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $masi06id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f1206_Eliminar($aParametros)
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
	list($iCantidad, $sError, $sDebugElimina) = f1206_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f1206_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f1206detalle', 'innerHTML', $sDetalle);
		$html_masi05total_usuarios = html_oculto('masi05total_usuarios', $iCantidad);
		$objResponse->assign('div_masi05total_usuarios', 'innerHTML', $html_masi05total_usuarios);
		$objResponse->call('limpiaf1206');
		$objResponse->call('limpiaf1208');
		$objResponse->call('paginarf1208');
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
function f1206_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f1206_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1206detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1206_PintarLlaves($aParametros)
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
	$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1206)) {
		$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_es.php';
	}
	*/
	require $mensajes_todas;
	//require $mensajes_1206;
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();

	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos();
	$html_masi06consec = '<input id="masi06consec" name="masi06consec" type="text" value="" onchange="revisaf1206()" class="cuatro" />';
	$html_masi06id = '<input id="masi06id" name="masi06id" type="hidden" value="" />';
	$html_masi06centro = f1206_HTMLComboV2_masi06centro($objDB, $objCombos, '', '');
	$html_masi06programa = f1206_HTMLComboV2_masi06programa($objDB, $objCombos, '', '', '');
	$html_masi06curso = f1206_HTMLComboV2_masi06curso($objDB, $objCombos, '', '', '', '');
	$html_masi06idperiodo = f1206_HTMLComboV2_masi06idperiodo($objDB, $objCombos, '');
	$html_masi06unidadfunc = f1206_HTMLComboV2_masi06unidadfunc($objDB, $objCombos, '');
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_masi06consec', 'innerHTML', $html_masi06consec);
	$objResponse->assign('div_masi06id', 'innerHTML', $html_masi06id);
	$objResponse->assign('div_masi06centro', 'innerHTML', $html_masi06centro);
	$objResponse->assign('div_masi06programa', 'innerHTML', $html_masi06programa);
	$objResponse->call('createComboboxById("masi06programa")');
	$objResponse->assign('div_masi06curso', 'innerHTML', $html_masi06curso);
	$objResponse->call('createComboboxById("masi06curso")');
	$objResponse->assign('div_masi06idperiodo', 'innerHTML', $html_masi06idperiodo);
	$objResponse->call('createComboboxById("masi06idperiodo")');
	$objResponse->assign('div_masi06unidadfunc', 'innerHTML', $html_masi06unidadfunc);
	$objResponse->call('createComboboxById("masi06unidadfunc")');
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

function f1206_Procesar($masi05id, $bloque, $masi06id, $objDB, $bDebug = false)
{
	$iCodModulo = 1206;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1206)) {
		$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1206;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$iCantidad = 0;
	$masi05id = numeros_validar($masi05id);
	$masi06id = numeros_validar($masi06id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		list($sTabla1205, $sError) = f1205_NombreTabla($bloque, $objDB);
		list($sTabla1206, $sErrorH) = f1206_NombreTabla($bloque, $objDB);
		$sError = $sError . $sErrorH;
		list($sTabla1208, $sErrorH) = f1208_NombreTabla($bloque, $objDB);
		$sError = $sError . $sErrorH;
	}
	if ($sError == '') {
		$sSQL = 'SELECT * FROM ' . $sTabla1206 . ' WHERE masi06id=' . $masi06id . ' AND masi06idmensaje=' . $masi05id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $masi06id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT * FROM ' . $sTabla1205 . ' WHERE masi05id=' . $masi05id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila05 = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro de origen {Ref: ' . $masi05id . '}';
		}
	}
	if ($sError == '') {
		if (isset($idTercero) == 0) {
			$idTercero = $_SESSION['unad_id_tercero'];
		}
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['3'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sError = 'No se ha definido la tarea a ejecutar para el proceso ' . $fila05['masi05idproceso'];
		switch ($fila05['masi05idproceso']) {
			case 0: // - Ninguno
				break;
			case 2: // - Funcionarios
				break;
			case 3: // - Contratistas
				break;
			case 11: // - Aspirantes
				break;
			case 12: // - Estudiantes
				$sError = '';
				$iFecha = $fila05['masi05fecha'];
				$sCondi = '';
				$sCondi1 = '';
				$sTablasAdicionales = '';
				$bConsultaPrograma = false;
				$bConsultaTercero = false;
				$bConsultaCurso = false;
				if ($filabase['masi06zona'] != 0) {
					$sCondi = $sCondi . ' AND TB.core01idzona=' . $filabase['masi06zona'] . '';
				}
				if ($filabase['masi06centro'] != 0) {
					$sCondi = $sCondi . ' AND TB.core011idcead=' . $filabase['masi06centro'] . '';
				}
				if ($filabase['masi06escuela'] != 0) {
					$sCondi = $sCondi . ' AND TB.core01idescuela=' . $filabase['masi06escuela'] . '';
				}
				if ($filabase['masi06nivelforma'] != 0) {
					$bConsultaPrograma = true;
					$sCondi = $sCondi . ' AND T9.cara09nivelformacion=' . $filabase['masi06nivelforma'] . '';
				}
				if ($filabase['masi06programa'] != 0) {
					$bConsultaPrograma = true;
					$sCondi = $sCondi . ' AND TB.core01idprograma=' . $filabase['masi06programa'] . '';
				}
				if ($filabase['masi06idperiodo'] != 0) {
					$sCondi = $sCondi . ' AND TB.core01peracainicial=' . $filabase['masi06idperiodo'] . '';
				}
				if ($filabase['masi06curso'] != 0) {
					$bConsultaCurso = true;
					$sCondi = $sCondi . ' AND T40.unad40id=' . $filabase['masi06curso'] . '';
				}
				/*
				if ($filabase['masi06est_condicion'] != 0) {
					$sCondi = $sCondi . ' AND grad41idescuela=' . $filabase['masi06est_condicion'] . '';
				}
				*/
				if ($filabase['masi06sexo'] != 0) {
					$bConsultaTercero = true;
					$masi06sexo = $amasi06sexo[$filabase['masi06sexo']][0];
					$sCondi = $sCondi . ' AND T11.unad11genero="' . $masi06sexo . '"';
				}
				if ($bConsultaPrograma) {
					$sTablasAdicionales = $sTablasAdicionales . ', core09programa AS T9';
					$sCondi1 = $sCondi1 . 'TB.core01idprograma=T9.core09id AND ';
				}
				if ($bConsultaTercero) {
					$sTablasAdicionales = $sTablasAdicionales . ', unad11terceros AS T11';
					$sCondi1 = $sCondi1 . 'TB.core01idtercero=T11.unad11id AND ';
				}
				if ($bConsultaCurso) {
					$sTablasAdicionales = $sTablasAdicionales . ', unad40curso AS T40';
					$sCondi1 = $sCondi1 . 'TB.core01idprograma=T40.unad40idprograma AND ';
				}
				$sSQL = 'SELECT TB.core01idtercero AS idtercero FROM core01estprograma AS TB' . $sTablasAdicionales . ' 
				WHERE ' . $sCondi1 . ' TB.core01idestado IN (0,7)' . $sCondi . '';
				break;
			case 13: // - Estudiantes ausentes
				break;
			case 17: // - Egresados
				break;
			case 2209: // - Estudiantes del programa
				break;
			case 2306: // - Acompañamiento académico
				break;
			case 2307: // - Seguimiento académico
				break;
			case 2741: // - Postulados a grados
				$sError = '';
				$iFecha = $fila05['masi05fecha'];
				/*
				masi06nivelforma
				*/
				$sCondi = '';
				if ($filabase['masi06programa'] != 0) {
					$sCondi = $sCondi . ' AND grad41idprograma=' . $filabase['masi06programa'] . '';
				} else {
					if ($filabase['masi06escuela'] != 0) {
						$sCondi = $sCondi . ' AND grad41idescuela=' . $filabase['masi06escuela'] . '';
					}
				}
				if ($filabase['masi06centro'] != 0) {
					$sCondi = $sCondi . ' AND grad41prog_idcentro=' . $filabase['masi06centro'] . '';
				} else {
					if ($filabase['masi06zona'] != 0) {
						$sCondi = $sCondi . ' AND grad41prog_idzona=' . $filabase['masi06zona'] . '';
					}
				}
				if ($fila05['masi05idrelacion2'] >= 0) {
					$sCondi = $sCondi . ' AND grad41idestado=' . $fila05['masi05idrelacion2'] . '';
				}
				$sSQL = 'SELECT grad41idtercero AS idtercero FROM grad41postulaciones WHERE grad41idcohorte=' . $fila05['masi05idrelacion']. $sCondi . '';
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('Datos base: ' . $sSQL);
				}
				break;
			case 12229: // - Convocados
				break;
		}
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('Datos base: ' . $sSQL);
		}
	}
	if ($sError == '') {
		$bPrimerDebug = $bDebug;
		$masi08fechaenvio = 0;
		$masi08horaenvio = 0;
		$masi08minenvio = 0;
		$masi08idsmtp = 0;
		$masi08id = tabla_consecutivo($sTabla1208, 'masi08id', '', $objDB);
		$sCampos1208 = 'masi08idmensaje, masi08idtercero, masi08idfecha, masi08id, masi08idpoblacion, 
		masi08fechaenvio, masi08horaenvio, masi08minenvio, masi08idsmtp';
		$tabla11 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla11) == 0) {
			$sError = 'No hay usuarios a ser incluidos en este grupo de poblaci&oacute;n.';
		}
		while ($fila11 = $objDB->sf($tabla11)) {
			$sSQL = 'SELECT 1 FROM ' . $sTabla1208 . ' 
			WHERE masi08idmensaje=' . $masi05id . ' AND masi08idtercero=' . $fila11['idtercero'] . ' AND masi08idfecha=' . $iFecha . '';
			$tabla08 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla08) == 0) {
				$sValores1208 = '' . $masi05id . ', ' . $fila11['idtercero'] . ', ' . $iFecha . ', ' . $masi08id . ', ' . $masi06id . ', 
				' . $masi08fechaenvio . ', ' . $masi08horaenvio . ', ' . $masi08minenvio . ', ' . $masi08idsmtp . '';
				$sSQL = 'INSERT INTO ' . $sTabla1208 . ' (' . $sCampos1208 . ') VALUES (' . $sValores1208 . ');';
				if ($bPrimerDebug) {
					$sDebug = $sDebug . log_debug('Datos base: ' . $sSQL);
					$bPrimerDebug = false;
				}
				$result = $objDB->ejecutasql($sSQL);
				$masi08id++;
				$iCantidad++;
			}
		}
		$sSQL = 'UPDATE ' . $sTabla1205 . ' SET masi05total_usuarios=' . $iCantidad . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	return array($iCantidad, $sError, $iTipoError, $sDebug);
}
// Remover
function f1206_Reversar($aParametros)
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
	if (isset($aParametros[97]) == 0) {
		$aParametros[97] = '';
	}
	$masi05id = numeros_validar($aParametros[1]);
	$masi06id = numeros_validar($aParametros[3]);
	$bloque = numeros_validar($aParametros[97]);
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
	// -- 
	if ($sError == '') {
		list($sTabla1205, $sError) = f1205_NombreTabla($bloque, $objDB);
		list($sTabla1208, $sErrorH) = f1208_NombreTabla($bloque, $objDB);
		$sError = $sError . $sErrorH;
	}
	if ($sError == '') {
		$sSQL = 'DELETE FROM ' . $sTabla1208 . ' WHERE masi08idmensaje=' . $masi05id . ' AND masi08idpoblacion=' . $masi06id . '';
		$result = $objDB->ejecutasql($sSQL);
		$sSQL = 'SELECT * FROM ' . $sTabla1208 . ' WHERE masi08idmensaje=' . $masi05id . '';
		$tabla08 = $objDB->ejecutasql($sSQL);
		$iCantidad = $objDB->nf($tabla08);
		$sSQL = 'UPDATE ' . $sTabla1205 . ' SET masi05total_usuarios=' . $iCantidad . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	// --
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f1206_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f1206detalle', 'innerHTML', $sDetalle);
		$html_masi05total_usuarios = html_oculto('masi05total_usuarios', $iCantidad);
		$objResponse->assign('div_masi05total_usuarios', 'innerHTML', $html_masi05total_usuarios);
		$objResponse->call('limpiaf1208');
		$objResponse->call('paginarf1208');
		$sError = $ETI['msg_procesoterminado'];
		$iTipoError = 1;
	}
	$objResponse->call("MensajeAlarmaV2('" . $sError . "', " . $iTipoError . ")");
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	$objDB->CerrarConexion();
	return $objResponse;
}
function f1206_HTMLComboV2_masi06idperiodo($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('masi06idperiodo', $valor, true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->bEsCombobox = true;
	$objCombos->sAccion = 'carga_combo_masi06curso();';
	//$objCombos->iAncho = 450;
	$sSQL = f146_ConsultaCombo();
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1206, $sIdioma
	return $res;
}
function f1206_HTMLComboV2_masi06unidadfunc($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('masi06unidadfunc', $valor, true, '{' . $ETI['msg_na'] . '}', 0);
	$objCombos->bEsCombobox = true;
	//$objCombos->iAncho = 450;
	$sSQL = f226_ConsultaCombo();
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1206, $sIdioma
	return $res;
}