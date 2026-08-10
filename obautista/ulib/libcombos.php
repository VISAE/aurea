<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.0 martes, 31 de marzo de 2026
--- En esta libreria vamos a ubicar todas las consultas para combos que tengan complejidades y sean recurrentes
--- se toma com base f226_ConsultaCombo
*/
function f123_ConsultaComboZonas($bSoloEstudiantes = true) {
	$sCondi = 'unad23conestudiantes="S"';
	if (!$bSoloEstudiantes) {
		$sCondi = 'unad23id>0';
	}
	$sRes = 'SELECT unad23id AS id, unad23nombre AS nombre 
	FROM unad23zona 
	WHERE ' . $sCondi . ' 
	ORDER BY unad23conestudiantes DESC, unad23nombre';
	return $sRes;
}
function f1531_ConsultaComboEquipo() {
	$sRes = 'SELECT bita31id AS id, CONCAT(bita31nombre, " [", bita31consec, "]", CASE bita31activo WHEN "N" THEN " [INACTIVO]" ELSE "" END) AS nombre 
	FROM bita31equipo 
	WHERE bita31id>0
	ORDER BY bita31activo DESC, bita31nombre';
	return $sRes;
}
function f2209_ConsultaComboProgramasXEscuela($idEscuela, $sWhere = '', $objDB = NULL)
{
	if ((int)$idEscuela == 0) {
		return '';
	} else {
		return 'SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]") AS nombre 
		FROM core09programa AS TB 
		WHERE ' . $sWhere . ' TB.core09id>0 AND TB.core09idescuela=' . $idEscuela . ' 
		ORDER BY TB.core09nombre';
	}
}
function f2212_ConsultaComboEscuela($bSoloEstudiantes = true) {
	$sCondi = 'core12id>0';
	if ($bSoloEstudiantes) {
		$sCondi = 'core12tieneestudiantes="S"';
	}
	$sRes = 'SELECT core12id AS id, core12nombre AS nombre 
	FROM core12escuela 
	WHERE ' . $sCondi . ' 
	ORDER BY core12orden, core12nombre';
	return $sRes;
}
// Codigos SNIES Propios
function f2278_ConsultaComboSNIESTotal($objDB) {
	$sIds = '-99';
	/*
	//@@@@@ este id debe estar en la tabla de configuracion...
	$idIES = 386;
	$sRes = 'SELECT core78id AS id, CONCAT(core78codigoprog, " - ", core78nombre) AS nombre 
	FROM core78iesprograma AS TB
	WHERE TB.core78id>0 AND core78idies=' . $idIES . '  
	ORDER BY core78nombre';
	*/
	$sRes = 'SELECT core78id AS id, CONCAT(core78codigoprog, " - ", core78nombre) AS nombre 
	FROM core78iesprograma AS TB, core77ies AS T77
	WHERE TB.core78idies=T77.core77id AND T77.core77propio=1  
	ORDER BY core78nombre';

	return $sRes;
}
// Codigos SNIES por escuela
function f2278_ConsultaComboSNIES($idEscuela, $objDB) {
	$sIds = '-99';
	$sSQL = 'SELECT core09idsnies 
	FROM core09programa 
	WHERE core09idsnies>0 AND core09idescuela=' . $idEscuela . '
	GROUP BY core09idsnies';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds = $sIds . ',' . $fila['core09idsnies'];
	}
	$sRes = 'SELECT core78id AS id, CONCAT(core78codigoprog, " - ", core78nombre) AS nombre 
	FROM core78iesprograma AS TB
	WHERE TB.core78id IN (' . $sIds . ')  
	ORDER BY core78nombre';
	return $sRes;
}
// GRADOS
function f2705_ConsultaComboOpcionGrado($idEscuela, $iCategoria = -1) {
	$sCondi = 'core38idescuela=0';
	switch ($iCategoria) {
		case 0:
		case 1:
			$sCondi = $sCondi . ' AND core38vaaproyecto=' . $iCategoria;
			break;
	}
	/*
	$sSQL='SELECT core38id AS id, IF (core38id>10, CONCAT(core38nombre, " {Posgrado}" ), CONCAT(core38nombre, " {Pregrado}")) AS nombre FROM core38opciongrado 
	WHERE core38id>0 ORDER BY core38nombre';
	*/
	$sRes = 'SELECT core38id AS id, CONCAT(core38nombre, " {", T49.core49nombre, "}") AS nombre 
	FROM core38opciongrado AS TB, core49gruponivelforma AS T49 
	WHERE ' . $sCondi . ' AND TB.core38nivelacademico=T49.core49id
	ORDER BY core38nombre';
	return $sRes;
}

//Titulos para combos bloqueados
function f2201_TituloPEI($id01, $objDB, $bConDuracion = true, $bConEstado = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sRes = '&nbsp;';
	$id10 = 0;
	$sSQL = 'SELECT core01idplandeestudios, core01idestado FROM core01estprograma WHERE core01id=' .$id01 . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['core01idplandeestudios'] > 0) {
			$id10 = $fila['core01idplandeestudios'];
		}
	}
	if ((int)$id10 > 0) {
		$sRes = '{' . $id10 . '}';
		$sSQL = 'SELECT core10consec, core10numregcalificado, core10fechaversion, core10fechavence, core10estado 
		FROM core10programaversion 
		WHERE core10id=' . $id10 . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sDuracion = '';
			if ($bConDuracion) {
				$sDuracion = ' - Vigente desde ' . fecha_DesdeNumero($fila['core10fechaversion']) . ' hasta ' . fecha_DesdeNumero($fila['core10fechavence']) . '';
			}
			$sRes = $fila['core10consec'] . ' - N&deg; Res ' . $fila['core10numregcalificado'] . $sDuracion . '';
		}
	} else {
		if ((int)$id10 != 0) {
			$sRes = '[' . $ETI['msg_na'] . ']';
		}
	}
	return $sRes;
}
