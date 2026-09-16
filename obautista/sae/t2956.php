<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.2.5 martes, 15 de septiembre de 2026
*/
/*
/** Archivo para reportes tipo csv 2956.
 * Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @date martes, 15 de septiembre de 2026
 */

/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/

if (file_exists('./err_control.php')) {
	require './err_control.php';
}
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'libs/clsplanos.php';
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libdatos.php';
if ($_SESSION['unad_id_tercero'] == 0) {
	header('Location:./nopermiso.php');
	die();
} else {
	$idTercero = numeros_validar($_SESSION['unad_id_tercero']);
	if ($idTercero != $_SESSION['unad_id_tercero']) {
		die();
	}
}
$_SESSION['u_ultimominuto'] = iminutoavance();
$sError = '';
$sDebug = '';
$bDebug = false;
if (isset($_REQUEST['clave']) == 0) {
	$_REQUEST['clave'] = '';
}
if (isset($_REQUEST['v3']) == 0) {
	$_REQUEST['v3'] = '';
}
if (isset($_REQUEST['v4']) == 0) {
	$_REQUEST['v4'] = '';
}
if (isset($_REQUEST['v5']) == 0) {
	$_REQUEST['v5'] = '';
}
if (isset($_REQUEST['rdebug']) == 0) {
	$_REQUEST['rdebug'] = 0;
}
if ($sError == '') {
	$idVar3 = numeros_validar($_REQUEST['v3']);
	if ($idVar3 != $_REQUEST['v3']) {
		$sError = 'No es posible iniciar el sistema.';
	}
	$idVar4 = numeros_validar($_REQUEST['v4']);
	if ($idVar4 != $_REQUEST['v4']) {
		$sError = 'No es posible iniciar el sistema.';
	}
	$idVar5 = numeros_validar($_REQUEST['v5']);
	if ($idVar5 != $_REQUEST['v5']) {
		$sError = 'No es posible iniciar el sistema.';
	}
}
if ($sError == '') {
	if ((int)$idVar3 == 0) {
		$sError = 'No se ha determinado el valor';
	}
	if ((int)$idVar4 == 0) {
		$sError = 'No se ha determinado el valor';
	}
	if ((int)$idVar5 == 0) {
		$sError = 'No se ha determinado el valor';
	}
}
if ($sError == '') {
	$sDebug = '';
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$cSepara = ';';
	$cEvita = ',';
	$cComplementa = '.';
	if (isset($_REQUEST['separa']) != 0) {
		if ($_REQUEST['separa'] != ';') {
			$cSepara = ',';
			$cEvita = ';';
		}
	}
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2900 = 'lg/lg_2900_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2900)) {
		$mensajes_2900 = 'lg/lg_2900_es.php';
	}
	require $mensajes_2900;
	*/
	$mensajes_2956 = 'lg/lg_2956_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2956)) {
		$mensajes_2956 = 'lg/lg_2956_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2956;
	$visa56consec_lg = 'Consecutivo';
	$visa56id_lg = 'Ref';
	$visa56fechaini_lg = 'Fecha inicio';
	$visa56fechafin_lg = 'Fecha fin';
	$visa56estado_lg = 'Estado';
	$visa56fechacrea_lg = 'Fechacrea';
	$visa56fechacierre_lg = 'Fecha cierre';
	$visa56observacion_lg = 'Observacion';
	$visa56activo_lg = 'Activo';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	// ----------- Espacio para los parametros.
	$sCondi = 'WHERE visa56consec=' . $_REQUEST['visa56consec'] . '';
	// ----------- Fin del bloque de parametros.
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't2956.csv';
	$sTituloRpt = 'visa56persemanal';
	$sNombrePlanoFinal = $sTituloRpt . '.csv';
	$objplano = new clsPlanos($sPath . $sNombrePlano);
	$idEntidad = Traer_Entidad();
	$sDato = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	switch ($idEntidad) {
		case 1: // Unad Florida
			$sDato = 'UNAD FLORIDA INC';
			break;
		case 2: // Unad UE
			$sDato = 'UNAD UNION EUROPEA';
			break;
	}
	$objplano->AdicionarLinea($sDato);
	$sDato = cadena_codificar('visa56persemanal');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$avisa56estado = array('');
	$avisa56activo = array('');
	$sTitulo1 = 'Titulo 1';
	for ($l = 1; $l <= 8; $l++) {
		$sTitulo1 = $sTitulo1 . $cSepara;
	}
	$sBloque1 = '' . $visa56consec_lg . $cSepara . $visa56fechaini_lg . $cSepara . $visa56fechafin_lg . $cSepara . $visa56estado_lg . $cSepara . $visa56fechacrea_lg . $cSepara
	 . $visa56fechacierre_lg . $cSepara . $visa56observacion_lg . $cSepara . $visa56activo_lg;
	//$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sCampos = 'SELECT TB.*
	$sConsulta = 'FROM visa56persemanal 
	' . $sCondi . '';
	$sOrden = '';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_visa56consec = '';
		$lin_visa56fechaini = $cSepara;
		$lin_visa56fechafin = $cSepara;
		$lin_visa56estado = $cSepara;
		$lin_visa56fechacrea = $cSepara;
		$lin_visa56fechacierre = $cSepara;
		$lin_visa56observacion = $cSepara;
		$lin_visa56activo = $cSepara;
		$lin_visa56consec = $fila['visa56consec'];
		$lin_visa56fechaini = $cSepara . fecha_desdenumero($fila['visa56fechaini']);
		$lin_visa56fechafin = $cSepara . fecha_desdenumero($fila['visa56fechafin']);
		$lin_visa56estado = $cSepara . '[' . $fila['visa56estado'] . ']';
		if (isset($avisa56estado[$fila['visa56estado']]) != 0) {
			$lin_visa56estado = $cSepara . cadena_codificar($avisa56estado[$fila['visa56estado']]);
		}
		$lin_visa56fechacrea = $cSepara . fecha_desdenumero($fila['visa56fechacrea']);
		$lin_visa56fechacierre = $cSepara . fecha_desdenumero($fila['visa56fechacierre']);
		$lin_visa56observacion = $cSepara . str_replace($cSepara, $cComplementa, cadena_QuitarSaltos(cadena_codificar($fila['visa56observacion'])));
		$lin_visa56activo = $cSepara . '[' . $fila['visa56activo'] . ']';
		if (isset($avisa56activo[$fila['visa56activo']]) != 0) {
			$lin_visa56activo = $cSepara . cadena_codificar($avisa56activo[$fila['visa56activo']]);
		}
		$sBloque1 = '' . $lin_visa56consec . $lin_visa56fechaini . $lin_visa56fechafin . $lin_visa56estado . $lin_visa56fechacrea
		 . $lin_visa56fechacierre . $lin_visa56observacion . $lin_visa56activo;
		$objplano->AdicionarLinea($sBloque1);
	}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv; charset=UTF-8');
	header('Content-Length: ' . filesize($sPath . $sNombrePlano));
	header('Content-Disposition: attachment; filename=' . basename($sNombrePlanoFinal));
	readfile($sPath . $sNombrePlano);
} else {
	echo $sError;
}
