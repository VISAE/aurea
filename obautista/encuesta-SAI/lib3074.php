<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.15 miércoles, 14 de mayo de 2025
--- 3074 saiu74encuesta
*/
/** Archivo lib3074.php.
 * Libreria 3074 saiu74encuesta.
 * @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
 * @date miércoles, 14 de mayo de 2025
 */
function f3074_GuardarEncuesta($DATA, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3074 = 'lg/lg_3074_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3074)) {
		$mensajes_3074 = 'lg/lg_3074_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3074;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if (isset($DATA['saiu74codmodulo']) == 0) {
		$DATA['saiu74codmodulo'] = '';
	}
	if (isset($DATA['saiu74idreg']) == 0) {
		$DATA['saiu74idreg'] = '';
	}
	if (isset($DATA['saiu74agno']) == 0) {
		$DATA['saiu74agno'] = '';
	}
	if (isset($DATA['saiu74acepta']) == 0) {
		$DATA['saiu74acepta'] = 0;
	}
	if (isset($DATA['saiu74preg1']) == 0) {
		$DATA['saiu74preg1'] = '';
	}
	if (isset($DATA['saiu74preg2']) == 0) {
		$DATA['saiu74preg2'] = '';
	}
	if (isset($DATA['saiu74preg3']) == 0) {
		$DATA['saiu74preg3'] = '';
	}
	if (isset($DATA['saiu74preg4']) == 0) {
		$DATA['saiu74preg4'] = '';
	}
	if (isset($DATA['saiu74preg5']) == 0) {
		$DATA['saiu74preg5'] = '';
	}
	if (isset($DATA['saiu74preg6']) == 0) {
		$DATA['saiu74preg6'] = '';
	}
	if (isset($DATA['saiu74comentario']) == 0) {
		$DATA['saiu74comentario'] = '';
	}
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['saiu74codmodulo'] = numeros_validar($DATA['saiu74codmodulo']);
	$DATA['saiu74idreg'] = numeros_validar($DATA['saiu74idreg']);
	$DATA['saiu74agno'] = numeros_validar($DATA['saiu74agno']);
	$DATA['saiu74acepta'] = numeros_validar($DATA['saiu74acepta']);
	$DATA['saiu74fecharespuesta'] = numeros_validar($DATA['saiu74fecharespuesta']);
	$DATA['saiu74preg1'] = numeros_validar($DATA['saiu74preg1']);
	$DATA['saiu74preg2'] = numeros_validar($DATA['saiu74preg2']);
	$DATA['saiu74preg3'] = numeros_validar($DATA['saiu74preg3']);
	$DATA['saiu74preg4'] = numeros_validar($DATA['saiu74preg4']);
	$DATA['saiu74preg5'] = numeros_validar($DATA['saiu74preg5']);
	$DATA['saiu74preg6'] = numeros_validar($DATA['saiu74preg6']);
	$DATA['saiu74comentario'] = cadena_Validar(trim($DATA['saiu74comentario']));
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	$aPendientes = array();
	$sTabla = '';
	$sCampoIdReg = '';
	$sCampoFecha = '';
	$sCampoCodModulo = '';
	$bEsFAV = false;
	if ($DATA['saiu74idreg'] == '') {
		$sError = $ERR['saiu74idreg'] . $sSepara . $sError;
	}
	if ($DATA['saiu74codmodulo'] == '') {
		$sError = $ERR['saiu74codmodulo'] . $sSepara . $sError;
	}
	if ($DATA['saiu74acepta'] == '') {
		$sError = $ERR['saiu74acepta'] . $sSepara . $sError;
	}
	if ($DATA['saiu74acepta'] == 0) {
		$DATA['saiu74preg1'] = 0;
		$DATA['saiu74preg2'] = 0;
		$DATA['saiu74preg3'] = 0;
		$DATA['saiu74preg4'] = 0;
		$DATA['saiu74preg5'] = 0;
		$DATA['saiu74preg6'] = 0;
	} else {
		if ($DATA['saiu74preg1'] == '') {
			$sError = $ERR['saiu74preg1'] . $sSepara . $sError;
			$aPendientes[] = 1;
		}
		if ($DATA['saiu74preg2'] == '') {
			$sError = $ERR['saiu74preg2'] . $sSepara . $sError;
			$aPendientes[] = 2;
		}
		if ($DATA['saiu74preg3'] == '') {
			$sError = $ERR['saiu74preg3'] . $sSepara . $sError;
			$aPendientes[] = 3;
		}
		if ($DATA['saiu74preg4'] == '') {
			$sError = $ERR['saiu74preg4'] . $sSepara . $sError;
			$aPendientes[] = 4;
		}
		if ($DATA['saiu74preg5'] == '') {
			$sError = $ERR['saiu74preg5'] . $sSepara . $sError;
			$aPendientes[] = 5;
		}
		if ($DATA['saiu74preg6'] == '') {
			$sError = $ERR['saiu74preg6'] . $sSepara . $sError;
			$aPendientes[] = 6;
		}
		if ($sError == '') {
			$aListaCampos = array('', 'saiu74comentario');
			$aLargoCampos = array(0, 250);
			for ($k = 1; $k <= 1; $k++) {
				$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
				if ($iLargoCampo > $aLargoCampos[$k]) {
					$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
				}
			}
		}
	}
	if ($sError == '') {
		switch ($DATA['saiu74codmodulo']) {
			case 3018: // Canales de atención y FAV
			case 3019: 
			case 3020:
			case 3021:
			case 3073:
				$sTabla = 'saiu73solusuario_' . $DATA['saiu74agno'];
				$sCampoIdReg = 'saiu73id';
				$sCampoFecha = 'saiu73evalfecha';
				$sCampoCodModulo = 'saiu73idcanal';
				$bEsFAV = true;
				break;
			default:
				$sTabla = 'saiu74encuesta';
				$sCampoIdReg = 'saiu74idreg';
				$sCampoFecha = 'saiu74fecharespuesta';
				$sCampoCodModulo = 'saiu74codmodulo';
				break;
		}
		$bExiste = $objDB->bexistetabla($sTabla);
		if (!$bExiste) {
			$sError = $ERR['msg_contenedor'] . ' ' . $sTabla . '';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT ' . $sCampoFecha . ' FROM ' . $sTabla . ' WHERE ' . $sCampoIdReg . '=' . $DATA['saiu74idreg'] . ' AND ' . $sCampoCodModulo . '=' . $DATA['saiu74codmodulo'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($bEsFAV) {
				if ($fila[$sCampoFecha] != 0) {
					$sError = $ERR['msg_yaregistrada'];
				}
			} else {
				$sError = $ERR['msg_yaregistrada'];
			}
		} else {
			if ($bEsFAV) {
				$sError = 'No se encuentra el registro solicitado {Ref: ' . $DATA['saiu74idreg'] . '}';
			} else {
				$DATA['saiu74id'] = tabla_consecutivo('saiu74encuesta', 'saiu74id', '', $objDB);
				if ($DATA['saiu74id'] == -1) {
					$sError = $objDB->serror;
				}
			}
		}
	}
	if ($sError == '') {
		$DATA['saiu74fecharespuesta'] = fecha_DiaMod();
		if (!$bEsFAV) {
			$sCampos3074 = 'saiu74codmodulo, saiu74idreg, saiu74id, saiu74agno, saiu74acepta, 
			saiu74fecharespuesta, saiu74preg1, saiu74preg2, saiu74preg3, saiu74preg4, 
			saiu74preg5, saiu74preg6, saiu74comentario';
			$sValores3074 = '' . $DATA['saiu74codmodulo'] . ', ' . $DATA['saiu74idreg'] . ', ' . $DATA['saiu74id'] . ', ' . $DATA['saiu74agno'] . ', ' . $DATA['saiu74acepta'] . ', 
			' . $DATA['saiu74fecharespuesta'] . ', ' . $DATA['saiu74preg1'] . ', ' . $DATA['saiu74preg2'] . ', ' . $DATA['saiu74preg3'] . ', ' . $DATA['saiu74preg4'] . ', 
			' . $DATA['saiu74preg5'] . ', ' . $DATA['saiu74preg6'] . ', "' . $DATA['saiu74comentario'] . '"';
			$sSQL = 'INSERT INTO ' . $sTabla .' (' . $sCampos3074 . ') VALUES (' . $sValores3074 . ');';
		} else {
			$sWhere = 'saiu73id = ' . $DATA['saiu74idreg'];
			$sSQL = 'UPDATE ' . $sTabla . ' SET saiu73evalacepta=' . $DATA['saiu74acepta'] . ', saiu73evalfecha=' . $DATA['saiu74fecharespuesta'] . ', saiu73evalamabilidad=' . $DATA['saiu74preg1'] . ', 
			saiu73evalrapidez=' . $DATA['saiu74preg2'] . ', saiu73evalclaridad=' . $DATA['saiu74preg3'] . ', saiu73evalresolvio=' . $DATA['saiu74preg4'] . ', saiu73evalconocimiento=' . $DATA['saiu74preg5'] . ', 
			saiu73evalutilidad=' . $DATA['saiu74preg6'] . ', saiu73evalsugerencias="' . $DATA['saiu74comentario'] . '" WHERE ' . $sWhere . '';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		// if ($bDebug) {
		// 	$sDebug = $sDebug . '' . fecha_microtiempo() . ' Consulta de cerrado:<br>' . $sSQL . '<br>';
		// }
		if ($tabla == false) {
			$sError = $ERR['msg_registrar'];
		}
	}
	if ($sError == '') {
		$sError = $ETI['msg_registraok'];
		$iTipoError = 1;
		$DATA['paso'] = -1;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $aPendientes, $sError, $iTipoError, $sDebug);
}
function f3074_BuscarEncuesta($saiu74codmodulo, $saiu74idreg, $saiu74agno, $objDB, $bDebug = false) {
	require './app.php';
	$mensajes_3074 = 'lg/lg_3074_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3074)) {
		$mensajes_3074 = 'lg/lg_3074_es.php';
	}
	require $mensajes_3074;
	$sError = '';
	$sDebug = '';
	$sTabla = '';
	$sCampoIdReg = '';
	$sCampoFecha = '';
	$sCampoCodModulo = '';
	$bEsFAV = false;
	$bAbierta = false;
	$saiu74codmodulo = numeros_validar($saiu74codmodulo);
	$saiu74idreg = numeros_validar($saiu74idreg);
	$saiu74agno = numeros_validar($saiu74agno);
	if ($saiu74codmodulo == '') {
		$sError = $sError . $ETI['saiu74codmodulo'];
	}
	if ($saiu74idreg == '') {
		$sError = $sError . $ETI['saiu74idreg'];
	}
	if ($saiu74agno == '') {
		$sError = $sError . $ETI['saiu74agno'];
	}
	if ($sError == '') {
		switch ($saiu74codmodulo) {
			case 3018: // Canales de atención y FAV
			case 3019: 
			case 3020:
			case 3021:
			case 3073:
				$sTabla = 'saiu73solusuario_' . $saiu74agno;
				$sCampoIdReg = 'saiu73id';
				$sCampoFecha = 'saiu73evalfecha';
				$sCampoCodModulo = 'saiu73idcanal';
				$bEsFAV = true;
				break;
			default:
				$sTabla = 'saiu74encuesta';
				$sCampoIdReg = 'saiu74idreg';
				$sCampoFecha = 'saiu74fecharespuesta';
				$sCampoCodModulo = 'saiu74codmodulo';
				break;
		}
		$bExiste = $objDB->bexistetabla($sTabla);
		if (!$bExiste) {
			$sError = $ERR['msg_contenedor'] . ' ' . $sTabla . '';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT ' . $sCampoFecha . ' FROM ' . $sTabla . ' WHERE ' . $sCampoIdReg . '=' . $saiu74idreg . ' AND ' . $sCampoCodModulo . '=' . $saiu74codmodulo . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($bEsFAV) {
				if ($fila[$sCampoFecha] != 0) {
					$sError = $ERR['msg_yaregistrada'];
				} else {
					$bAbierta = true;
				}
			} else {
				$sError = $ERR['msg_yaregistrada'];
			}
		} else {
			if ($bEsFAV) {
				$sError = 'No se encuentra el registro solicitado {Ref: ' . $saiu74idreg . '}';
			} else {
				$bAbierta = true;
			}
		}
	}
	return array($bAbierta, $sError, $sDebug);
}