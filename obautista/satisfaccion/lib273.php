<?php
/*
--- © Omar Augusto Bautista MOra - UNAD - 2022 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- viernes, 7 de octubre de 2022
--- El proposito de esta libreria es validar los códigos de la encuesta
*/

function f273_BuscaCodigo($aParametros) {
    $_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}	
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_273 = 'lg/lg_273_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_273)) {
		$mensajes_273 = 'lg/lg_273_es.php';
	}
	require $mensajes_todas;
	require $mensajes_273;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}

	$sError = '';
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = '';
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = '';
	}
	$aure73tipodoc = cadena_Validar($aParametros[100]);
	$aure73doc = cadena_Validar($aParametros[101]);
	$aure73codigo = cadena_Validar($aParametros[102]);
	$idTercero = 0;
	$iMes = '';
	$idEncuesta = 0;
	$sSepara = ', ';
	$html_encuesta = '';
	if (true) {
		if ($aure73tipodoc == '') {
			$sError = $ERR['aure73tipodoc'] . $sSepara . $sError;
		}
		if ($aure73doc == '') {
			$sError = $ERR['aure73doc'] . $sSepara . $sError;
		}
		if ($aure73codigo == '') {
			$sError = $ERR['aure73codigo'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}

	$objDB->xajax();
	if($sError == '') {
		$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $aure73doc . '" AND unad11tipodoc="' . $aure73tipodoc . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idTercero = $fila['unad11id'];
		} else {
			$sError = 'No se ha encontrado el documento &quot;' . $aure73tipodoc . ' ' . $aure73doc . '&quot;';
		}
	}
	if($sError == '') {
		$sSQL = 'SHOW TABLES LIKE "aure73encuesta%"';
		$tablac = $objDB->ejecutasql($sSQL);
		while ($filac = $objDB->sf($tablac)) {
			$iContenedor = substr($filac[0], 14);
			if ($iContenedor != 0) {
				$sSQL = 'SELECT aure73id FROM ' . $filac[0] . ' WHERE aure73idtercero=' . $idTercero . ' AND aure73codigo="' . $aure73codigo . '"';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$iMes = $iContenedor;
					$idEncuesta = $fila['aure73id'];
				}
			}
		}
		if ($idEncuesta == 0) {
			$sError = 'C&oacute;digo erroneo';
		}
	}
	if($sError == '') {
		$idRespuesta = 0; //! REVISAR RESPUESTA
		list($html_encuesta) = f273_HTMLForm_Encuesta($iMes, $idEncuesta, $idRespuesta, $objDB);
	}
	$objDB->CerrarConexion();
    $objResponse = new xajaxResponse();
	if($sError == '') {
		$objResponse->assign('div_aure73formencuesta', 'innerHTML', $html_encuesta);
		$objResponse->assign('div_aure73formencuesta', 'style.display', 'block');
		$objResponse->assign('div_aure73formcodigo', 'style.display', 'none');
	} else {
		$objResponse->call('muestramensajes("danger", "' . $sError . '")');
	}
	return $objResponse;
}
function f273_EnviaEncuesta($aParametros) {
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_273 = 'lg/lg_273_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_273)) {
		$mensajes_273 = 'lg/lg_273_es.php';
	}
	require $mensajes_todas;
	require $mensajes_273;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$sError = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 0;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = -1;
	}
	if (isset($aParametros[103]) == 0) {
		$aParametros[102] = -1;
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[102] = -1;
	}
	if (isset($aParametros[105]) == 0) {
		$aParametros[102] = -1;
	}
	$iMes = cadena_Validar($aParametros[100]);
	$aure73id = numeros_validar($aParametros[101]);
	$aure73t1_p1 = numeros_validar($aParametros[102]);
	$aure73t1_p2 = numeros_validar($aParametros[103]);
	$aure73t1_p3 = numeros_validar($aParametros[104]);
	$aure73t1_p4 = numeros_validar($aParametros[105]);
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($iMes == '') {
		$sError = $ERR['mes'] . $sSepara . $sError;
	}
	if ($aure73id == '') {
		$sError = $ERR['aure73id'] . $sSepara . $sError;
	}
	if ($aure73t1_p1 == '' || $aure73t1_p1 == -1) {
		$sError = $ERR['aure73t1_p1'] . $sSepara . $sError;
	}
	if ($aure73t1_p2 == '' || $aure73t1_p2 == -1) {
		$sError = $ERR['aure73t1_p2'] . $sSepara . $sError;
	}
	if ($aure73t1_p3 == '' || $aure73t1_p3 == -1) {
		$sError = $ERR['aure73t1_p3'] . $sSepara . $sError;
	}
	if ($aure73t1_p4 == '') {
		$aure73t1_p4 = -1;
	}
	if ($sError == '') {
		$aure73acepta = 1;
		$aure73fecharespuesta = fecha_ArmarNumero();
		$bPasa = false;
		$sTabla = 'aure73encuesta' . $iMes;
		$sHTML = '';
		$sSQL = 'SELECT aure73fecharespuesta FROM ' . $sTabla . ' WHERE aure73id=' . $aure73id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['aure73fecharespuesta'] == 0) {
				$scampo[1] = 'aure73t1_p1';
				$scampo[2] = 'aure73t1_p2';
				$scampo[3] = 'aure73t1_p3';
				$scampo[4] = 'aure73t1_p4';
				$scampo[5] = 'aure73acepta';
				$scampo[6] = 'aure73fecharespuesta';
				$sdato[1] = $aure73t1_p1;
				$sdato[2] = $aure73t1_p2;
				$sdato[3] = $aure73t1_p3;
				$sdato[4] = $aure73t1_p4;
				$sdato[5] = $aure73acepta;
				$sdato[6] = $aure73fecharespuesta;
				$numcmod=6;
				$sWhere = 'aure73id=' . $aure73id . '';
				$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sWhere;
				$sdatos = '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) > 0) {
					$filabase = $objDB->sf($result);
					for ($k = 1; $k <= $numcmod; $k++) {
						if ($filabase[$scampo[$k]] != $sdato[$k]) {
							if ($sdatos != '') {
								$sdatos = $sdatos . ', ';
							}
							$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
							$bPasa = true;
						}
					}
				}
				if ($bPasa) {
					if ($APP->utf8 == 1) {
						$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
					} else {
						$sdetalle = $sdatos . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
					}
				}
				if ($bPasa) {
					$result = $objDB->ejecutasql($sSQL);
					if ($result == false) {
						$sError = $ERR['falla_guardar'];
					} else {
						$sHTML = htmlAlertas('verde', $ETI['aure73gracias']);
					}
				}
			} else {
				$sHTML = htmlAlertas('naranja', $ETI['aure73cerrada']);
			}
		} else {
			$sHTML = htmlAlertas('rojo', $ETI['aure73noexiste']);
		}
	}
	$objDB->CerrarConexion();
    $objResponse = new xajaxResponse();
	if($sError == '') {
		$objResponse->assign('div_aure73formencuesta', 'innerHTML', $sHTML);
		$objResponse->assign('div_aure73formencuesta', 'style.display', 'block');
		$objResponse->assign('div_aure73formcodigo', 'style.display', 'none');
	} else {
		$objResponse->call('muestramensajes("danger", "' . $sError . '")');
	}
	return $objResponse;
}
function f273_EnviaFactorDeserta($aParametros) {
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_273 = 'lg/lg_273_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_273)) {
		$mensajes_273 = 'lg/lg_273_es.php';
	}
	require $mensajes_todas;
	require $mensajes_273;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$bDebug = false;
	$sError = '';
	$sDebug = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 0;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = -1;
	}
	$iMes = cadena_Validar($aParametros[100]);
	$aure73id = numeros_validar($aParametros[101]);
	$aure73t2_p1 = numeros_validar($aParametros[102]);
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($iMes == '') {
		$sError = $ERR['mes'] . $sSepara . $sError;
	}
	if ($aure73id == '') {
		$sError = $ERR['aure73id'] . $sSepara . $sError;
	}
	if ($aure73t2_p1 == '' || $aure73t2_p1 == -1) {
		$sError = $ERR['aure73factordeserta'] . $sSepara . $sError;
	}	
	if ($sError == '') {
		$aure73acepta = 1;
		$aure73fecharespuesta = fecha_ArmarNumero();
		$sFechaHoy = fecha_ArmarNumero();
		$bPasa = false;
		$sTabla = 'aure73encuesta' . $iMes;
		$sHTML = '';
		$sSQL = 'SELECT aure73fecharespuesta FROM ' . $sTabla . ' WHERE aure73id=' . $aure73id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['aure73fecharespuesta'] == 0 || $fila['aure73fecharespuesta'] == $sFechaHoy) {
				$scampo[1] = 'aure73t2_p1';
				$scampo[2] = 'aure73fecharespuesta';
				$sdato[1] = $aure73t2_p1;
				$sdato[2] = $aure73fecharespuesta;
				$numcmod=2;
				$sWhere = 'aure73id=' . $aure73id . '';
				$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sWhere;
				$sdatos = '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) > 0) {
					$filabase = $objDB->sf($result);
					for ($k = 1; $k <= $numcmod; $k++) {
						if ($filabase[$scampo[$k]] != $sdato[$k]) {
							if ($sdatos != '') {
								$sdatos = $sdatos . ', ';
							}
							$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
							$bPasa = true;
						}
					}
				}
				if ($bPasa) {
					if ($APP->utf8 == 1) {
						$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
					} else {
						$sdetalle = $sdatos . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
					}
				}
				if ($bPasa) {
					$result = $objDB->ejecutasql($sSQL);					
					if ($result == false) {
						$sError = $ERR['falla_guardar'];
					} else {
						list($sHTMLEncuesta, $sDebugE) = f273_HTMLForm_EncuestaDesercion($iMes, $aure73id, $aure73t2_p1, $objDB, true, $bDebug);
						$sDebug = $sDebug . $sDebugE;
						$sHTML = $sHTML . $sHTMLEncuesta;
					}
				}
			} else {
				$sHTML = $sHTML . htmlAlertas('naranja', $ETI['aure73cerrada']);
			}
		} else {
			$sHTML = $sHTML . htmlAlertas('rojo', $ETI['aure73noexiste']);
		}
	}
	$objDB->CerrarConexion();
    $objResponse = new xajaxResponse();
	if($sError == '') {
		$objResponse->assign('div_aure73formencuesta', 'innerHTML', $sHTML);
		$objResponse->assign('div_aure73formencuesta', 'style.display', 'block');
		$objResponse->assign('div_aure73formcodigo', 'style.display', 'none');
		$objResponse->script('$("select").addClass("form-control");');
		$objResponse->call('muestramensajes("success", "' . $ETI['aure73factordeserta_g'] . '")');
	} else {
		$objResponse->call('muestramensajes("danger", "' . $sError . '")');
	}
	return $objResponse;
}
function f273_EnviaEncuestaDes($aParametros) {
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_273 = 'lg/lg_273_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_273)) {
		$mensajes_273 = 'lg/lg_273_es.php';
	}
	require $mensajes_todas;
	require $mensajes_273;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$sError = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 0;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = -1;
	}
	if (isset($aParametros[103]) == 0) {
		$aParametros[102] = '';
	}
	$iMes = cadena_Validar($aParametros[100]);
	$aure73id = numeros_validar($aParametros[101]);
	$aure73t2_p2 = numeros_validar($aParametros[102]);
	$aure73t2_comentario = cadena_Validar($aParametros[103]);
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($iMes == '') {
		$sError = $ERR['mes'] . $sSepara . $sError;
	}
	if ($aure73id == '') {
		$sError = $ERR['aure73id'] . $sSepara . $sError;
	}
	if ($aure73t2_p2 == '' || $aure73t2_p2 == -1) {
		$sError = $ERR['aure73t2_p2'] . $sSepara . $sError;
	}	
	// if ($aure73t2_comentario == '') {
	// 	$sError = $ERR['aure73t2_comentario'] . $sSepara . $sError;
	// }
	if ($sError == '') {
		$bPasa = false;
		$sTabla = 'aure73encuesta' . $iMes;
		$sHTML = '';
		$sSQL = 'SELECT aure73t2_p2 FROM ' . $sTabla . ' WHERE aure73id=' . $aure73id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['aure73t2_p2'] == -1) {
				$scampo[1] = 'aure73t2_p2';
				$scampo[2] = 'aure73t2_comentario';
				$sdato[1] = $aure73t2_p2;
				$sdato[2] = $aure73t2_comentario;
				$numcmod=2;
				$sWhere = 'aure73id=' . $aure73id . '';
				$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sWhere;
				$sdatos = '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) > 0) {
					$filabase = $objDB->sf($result);
					for ($k = 1; $k <= $numcmod; $k++) {
						if ($filabase[$scampo[$k]] != $sdato[$k]) {
							if ($sdatos != '') {
								$sdatos = $sdatos . ', ';
							}
							$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
							$bPasa = true;
						}
					}
				}
				if ($bPasa) {
					if ($APP->utf8 == 1) {
						$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
					} else {
						$sdetalle = $sdatos . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
					}
				}
				if ($bPasa) {
					$result = $objDB->ejecutasql($sSQL);					
					if ($result == false) {
						$sError = $ERR['falla_guardar'];
					} else {						
						$sHTML = $sHTML . '<div class="col-sm-12">' . htmlAlertas('azul', 'Se ha guardado la informaci&oacute;n') . '</div>';
					}
				}
			} else {
				$sHTML = $sHTML . htmlAlertas('naranja', $ETI['aure73cerrada']);
			}
		} else {
			$sHTML = $sHTML . htmlAlertas('rojo', $ETI['aure73noexiste']);
		}
	}
	$objDB->CerrarConexion();
    $objResponse = new xajaxResponse();
	if($sError == '') {
		$objResponse->assign('div_adicional', 'innerHTML', $sHTML);
	} else {
		$objResponse->call('muestramensajes("danger", "' . $sError . '")');
	}
	return $objResponse;
}
//Se Traer la encuesta.
function f273_HTMLForm_Encuesta($iMes, $idEncuesta, $idRespuesta, $objDB, $bDebug = false) {
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_273 = 'lg/lg_273_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_273)) {
		$mensajes_273 = 'lg/lg_273_es.php';
	}
	require $mensajes_todas;
	require $mensajes_273;
	$sTabla = 'aure73encuesta' . $iMes;
	$sSQLadd = '';
	$sHTML = '';
	$sDebug = '';
	$sSQLadd = $sSQLadd . ' AND TB.aure73id=' . $idEncuesta . '';
	$sSQL = 'SELECT TB.aure73fecharespuesta, TB.aure73tipoencuesta, T6.aure74nombre, T5.unad11razonsocial, T1.core12nombre, T2.core09nombre, T3.unad23nombre, T4.unad24nombre, TB.aure73t2_p1
	FROM ' . $sTabla . ' AS TB, core12escuela AS T1, core09programa AS T2, unad23zona AS T3, unad24sede AS T4, unad11terceros AS T5, aure74tipoencuesta AS T6
	WHERE TB.aure73idescuela=T1.core12id AND TB.aure73idprograma=T2.core09id AND TB.aure73idzona=T3.unad23id AND TB.aure73idcentro=T4.unad24id AND TB.aure73idtercero=T5.unad11id AND TB.aure73tipoencuesta=aure74id' . $sSQLadd . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando encuesta: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Tipo Encuesta: ' . $fila['aure73tipoencuesta'] . ' - Fecha Cierre: ' . $fila['aure73fecharespuesta'] . '<br>';
		}
		if ($fila['aure73tipoencuesta'] == 1) {
			if ($fila['aure73fecharespuesta'] == 0) {
				$sHTML = $sHTML . '<form id="frmencuesta" name="frmencuesta" method="post" action="" autocomplete="off">
				<input id="iMes" name="iMes" type="hidden" value="' . $iMes . '" />
				<input id="aure73id" name="aure73id" type="hidden" value="' . $idEncuesta . '" />
				<div class="form-group row">
					<div class="col-sm-6">
						<label>' . $ETI['aure73tipoencuesta'] . '</label>
						<p class="font-weight-bold">' . $fila['aure74nombre'] . '</p>
					</div>
					<div class="col-sm-6">
						<label>' . $ETI['aure73razonsocial'] . '</label>
						<p class="font-weight-bold">' . $fila['unad11razonsocial'] . '</p>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label>' . $ETI['aure73idzona'] . '</label>
						<p class="font-weight-bold">' . $fila['unad23nombre'] . '</p>
					</div>
					<div class="col-sm-6">
						<label>' . $ETI['aure73idcentro'] . '</label>
						<p class="font-weight-bold">' . $fila['unad24nombre'] . '</p>
					</div>
				</div>
				
				<div class="form-group row">
					<div class="col-sm-6">
						<label>' . $ETI['aure73idescuela'] . '</label>
						<p class="font-weight-bold">' . $fila['core12nombre'] . '</p>
					</div>
					<div class="col-sm-6">
						<label>' . $ETI['aure73idprograma'] . '</label>
						<p class="font-weight-bold">' . $fila['core09nombre'] . '</p>
					</div>
				</div><hr>';
				$sHTML = $sHTML . '
				<div class="form-group row">
					<div class="col-sm-12">
						<div class="form-group row">
						<div class="col-sm-12">
							<label>' . $ETI['aure73t1_p1'] . $ETI['aure73t1_p1_t'] . '</label>
						</div>
							<div class="col-sm-12">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="aure73t1_p1" id="aure73t1_p1_1" value="1" required>
								<label class="form-check-label" for="aure73t1_p1_1">SI</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="aure73t1_p1" id="aure73t1_p1_2" value="0" required>
								<label class="form-check-label" for="aure73t1_p1_2">NO</label>
							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<div class="form-group row">
						<div class="col-sm-12">
							<label>' . $ETI['aure73t1_p2'] . $ETI['aure73t1_p2_t'] . '</label>
						</div>
							<div class="col-sm-12">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="aure73t1_p2" id="aure73t1_p2_1" value="1" required>
								<label class="form-check-label" for="aure73t1_p2_1">SI</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="aure73t1_p2" id="aure73t1_p2_2" value="0" required>
								<label class="form-check-label" for="aure73t1_p2_2">NO</label>
							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<div class="form-group row">
						<div class="col-sm-12">
							<label>' . $ETI['aure73t1_p3'] . $ETI['aure73t1_p3_t'] . '</strong></label>
						</div>
							<div class="col-sm-12">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="aure73t1_p3" id="aure73t1_p3_1" value="1" onclick="muestracomplemento(\'aure73t1_p4\',1);" required>
								<label class="form-check-label" for="aure73t1_p3_1">SI</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="aure73t1_p3" id="aure73t1_p3_2" value="0" onclick="muestracomplemento(\'aure73t1_p4\',0);" required>
								<label class="form-check-label" for="aure73t1_p3_2">NO</label>
							</div>
							</div>
						</div>
					</div>
				</div>
				<div id="p_aure73t1_p4" class="form-group row" style="display:none;">
					<div class="col-sm-12">
						<div class="form-group row">
						<div class="col-sm-12">
							<label>' . $ETI['aure73t1_p4'] . $ETI['aure73t1_p4_t'] . '</label>
						</div>
							<div class="col-sm-12">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="aure73t1_p4" id="aure73t1_p4_1" value="1">
								<label class="form-check-label" for="aure73t1_p4_1">SI</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="aure73t1_p4" id="aure73t1_p4_2" value="0">
								<label class="form-check-label" for="aure73t1_p4_2">NO</label>
							</div>
							</div>
						</div>
					</div>
				</div>
				<hr>';
				$sHTML = $sHTML . '<input type="button" id="cmdEnviaEncuesta" name="cmdEnviaEncuesta" class="btn btn-aurea px-4 float-right" title="' . $ETI['bt_enviar'] . '" value="' . $ETI['bt_enviar'] . '" onclick="enviaencuesta()">
				</form>';
			} else {
				$sHTML = $sHTML . htmlAlertas('naranja', $ETI['aure73cerrada']);
			}
		} else {
			if ($fila['aure73tipoencuesta'] == 2) {	// encuesta de deserción
				$sFechaHoy = fecha_DiaMod();			
				if ($fila['aure73fecharespuesta'] == 0) {
					$aure73acepta = 1;
					$aure73fecharespuesta = fecha_ArmarNumero();
					$bPasa = false;
					$scampo[1] = 'aure73acepta';
					$scampo[2] = 'aure73t2_p1';
					$scampo[3] = 'aure73fecharespuesta';
					$sdato[1] = $aure73acepta;
					$sdato[2] = $idRespuesta;
					$sdato[3] = $aure73fecharespuesta;
					$numcmod=3;
					$sWhere = 'aure73id=' . $idEncuesta . '';
					$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sWhere;
					$sdatos = '';
					$result = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($result) > 0) {
						$filabase = $objDB->sf($result);
						for ($k = 1; $k <= $numcmod; $k++) {
							if ($filabase[$scampo[$k]] != $sdato[$k]) {
								if ($sdatos != '') {
									$sdatos = $sdatos . ', ';
								}
								$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
								$bPasa = true;
							}
						}
					}
					if ($bPasa) {
						if ($APP->utf8 == 1) {
							$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
							$sSQL = 'UPDATE ' . $sTabla . ' SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
						} else {
							$sdetalle = $sdatos . '[' . $sWhere . ']';
							$sSQL = 'UPDATE ' . $sTabla . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
						}
					}
					if ($bPasa) {
						$result = $objDB->ejecutasql($sSQL);
						if ($result == false) {					
							$sHTML = $sHTML . htmlAlertas('rojo', $ERR['falla_guardar']);
						} else {
							list($sHTMLEncuesta, $sDebugE) = f273_HTMLForm_EncuestaDesercion($iMes, $idEncuesta, $idRespuesta, $objDB, false, true);
							$sDebug = $sDebug . $sDebugE;
							$sHTML = $sHTML . $sHTMLEncuesta;
						}
					}				
				} else {
					if ($fila['aure73fecharespuesta'] == $sFechaHoy) {
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' La encuesta: es de hoy<br>';
						}
						list($sHTMLEncuesta, $sDebugE) = f273_HTMLForm_EncuestaDesercion($iMes, $idEncuesta, $fila['aure73t2_p1'], $objDB, false, $bDebug);
						$sDebug = $sDebug . $sDebugE;
						$sHTML = $sHTML . $sHTMLEncuesta;
					} else {
						$sHTML = $sHTML . htmlAlertas('naranja', $ETI['aure73cerrada']);
					}
				}
			}
		}
	} else {
		$sHTML = $sHTML . htmlAlertas('rojo', $ETI['aure73noexiste']);
	}
	return array($sHTML, $sDebug);
}
function f273_HTMLForm_EncuestaDesercion($iMes, $idEncuesta, $idRespuesta, $objDB, $bCambiaFactor = false, $bDebug = false) {
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_273 = 'lg/lg_273_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_273)) {
		$mensajes_273 = 'lg/lg_273_es.php';
	}
	require $mensajes_todas;
	require $mensajes_273;
	$sTabla = 'aure73encuesta' . $iMes;
	$sSQLadd = '';
	$sHTML = '';
	$sDebug = '';
	$objCombos = new clsHtmlCombos();
	$objCombos->nuevo('aure73factordeserta', $idRespuesta, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'opcion_combo_aure73factordeserta();';
	$sWhere = 'cara15id NOT IN (0, 12, 15)';
	$sSQL = 'SELECT cara15id AS id, cara15nombre AS nombre 
	FROM cara15factordeserta 
	WHERE ' . $sWhere . ' 
	ORDER BY cara15orden';
	$html_aure73factordeserta = $objCombos->html($sSQL, $objDB);
	$sSQLadd = $sSQLadd . ' AND TB.aure73id=' . $idEncuesta . '';
	$sSQL = 'SELECT TB.aure73fecharespuesta, TB.aure73tipoencuesta, TB.aure73t2_p2, T6.aure74nombre, T5.unad11razonsocial, T1.core12nombre, T2.core09nombre, T3.unad23nombre, T4.unad24nombre
	FROM ' . $sTabla . ' AS TB, core12escuela AS T1, core09programa AS T2, unad23zona AS T3, unad24sede AS T4, unad11terceros AS T5, aure74tipoencuesta AS T6
	WHERE TB.aure73idescuela=T1.core12id AND TB.aure73idprograma=T2.core09id AND TB.aure73idzona=T3.unad23id AND TB.aure73idcentro=T4.unad24id AND TB.aure73idtercero=T5.unad11id AND TB.aure73tipoencuesta=aure74id' . $sSQLadd . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando encuesta para HTML: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sPrograma = utf8_encode($fila['core09nombre']);
		$sHTML = $sHTML . '<form id="frmencuesta" name="frmencuesta" method="post" action="" autocomplete="off">
		<input id="iMes" name="iMes" type="hidden" value="' . $iMes . '" />
		<input id="aure73id" name="aure73id" type="hidden" value="' . $idEncuesta . '" />
		<div class="form-group row">
			<div class="col-sm-12">
				<label><strong>' . $fila['unad11razonsocial'] . '</strong></label>
			</div>
			<div class="col-sm-12">
				<label>' . $ETI['aure73vuelveprog'] . $sPrograma . '</label>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-6">
			<label>' . $ETI['aure73factordeserta_t'] . '</label>
			</div>
			<div class="col-sm-6">
			' . $html_aure73factordeserta . '
			</div>
		</div>';
		if ($bCambiaFactor) {			
			$sHTML = $sHTML . htmlAlertas('azul', $ETI['aure73factordeserta_g'] . '<br>' . $ETI['aure73gracias']);
		} else {
			$sHTML = $sHTML . htmlAlertas('verde', $ETI['aure73gracias']);
		}
		if ($fila['aure73t2_p2'] == -1) {
			$sHTML = $sHTML . '<hr><div class="form-group row" id="div_adicional">
				<div class="col-sm-12">
					<label>' . $ETI['aure73t2_p2_t'] . '</label>
				</div>
				<div class="col-sm-12">
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="aure73t2_p2" id="aure73t2_p2_1" value="1" required>
						<label class="form-check-label" for="aure73t2_p2_1">SI</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="aure73t2_p2" id="aure73t2_p2_2" value="0" required>
						<label class="form-check-label" for="aure73t2_p2_2">NO</label>
					</div>
				</div>
				<div class="col-sm-12">
					<label for="aure73t2_comentario">¿Quiere agregar algo?</label>
					<textarea class="form-control" id="aure73t2_comentario" name="aure73t2_comentario" rows="3"></textarea>  
				</div>
				<div class="col-sm-12">
					<input type="button" id="cmdEnviaEncuesta" name="cmdEnviaEncuesta" class="btn btn-aurea px-4 float-right" title="' . $ETI['bt_enviar'] . '" value="' . $ETI['bt_enviar'] . '" onclick="enviaencuestades()">
				</div>
			</div>';
		}
	}
	$sHTML = $sHTML . '</form>';
	return array($sHTML, $sDebug);
}
function f273_HTMLNoRespondeEncuesta($iMes, $idEncuesta, $objDB) {
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_273 = 'lg/lg_273_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_273)) {
		$mensajes_273 = 'lg/lg_273_es.php';
	}
	require $mensajes_todas;
	require $mensajes_273;
	$aure73acepta = 0;
	$aure73fecharespuesta = fecha_ArmarNumero();
	$bPasa = false;
	$sTabla = 'aure73encuesta' . $iMes;
	$sHTML = '';
	$sSQL = 'SELECT aure73tipoencuesta, aure73fecharespuesta FROM ' . $sTabla . ' WHERE aure73id=' . $idEncuesta . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['aure73fecharespuesta'] == 0) {
			if ($fila['aure73tipoencuesta'] == 1) {
				$scampo[1] = 'aure73acepta';
				$scampo[2] = 'aure73fecharespuesta';
				$sdato[1] = $aure73acepta;
				$sdato[2] = $aure73fecharespuesta;
				$numcmod=2;
				$sWhere = 'aure73id=' . $idEncuesta . '';
				$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sWhere;
				$sdatos = '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) > 0) {
					$filabase = $objDB->sf($result);
					for ($k = 1; $k <= $numcmod; $k++) {
						if ($filabase[$scampo[$k]] != $sdato[$k]) {
							if ($sdatos != '') {
								$sdatos = $sdatos . ', ';
							}
							$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
							$bPasa = true;
						}
					}
				}
				if ($bPasa) {
					if ($APP->utf8 == 1) {
						$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
					} else {
						$sdetalle = $sdatos . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
					}
				}
				if ($bPasa) {
					$result = $objDB->ejecutasql($sSQL);
					if ($result == false) {					
						$sHTML = htmlAlertas('rojo', $ERR['falla_guardar']);
					} else {
						$sHTML = htmlAlertas('verde', $ETI['aure73gracias']);
					}
				}
			} else {	// encuesta de deserción
				$sHTML = $sHTML . htmlAlertas('rojo', $ETI['aure73noexiste']);
			}
		} else {
			$sHTML = $sHTML . htmlAlertas('naranja', $ETI['aure73cerrada']);
		}
	} else {
		$sHTML = $sHTML . htmlAlertas('rojo', $ETI['aure73noexiste']);
	}
	return $sHTML;
}
function htmlAlertas($sColor, $sTexto) {
	$sHTML = '';
	$sTipo = '';
	switch($sColor) {
		case 'verde':
			$sTipo = 'success';
			break;
		case 'naranja':
			$sTipo = 'warning';
			break;
		case 'rojo':
			$sTipo = 'danger';
			break;
		default:
			$sTipo = 'info';
	}
	$sHTML = $sHTML . '<div class="alert alert-' . $sTipo . '" role="alert"><strong>' . $sTexto . '</strong></div>';
	return $sHTML;
}