<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.0.15 miércoles, 14 de mayo de 2025
*/
/*
/** Archivo para reportes tipo csv 3074.
 * Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date miércoles, 14 de mayo de 2025
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
require $APP->rutacomun . 'libdatos.php';
if ($_SESSION['unad_id_tercero'] == 0) {
	header('Location:./nopermiso.php');
	die();
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
	$cSepara = ',';
	$cEvita = ';';
	$cComplementa = '.';
	if (isset($_REQUEST['separa']) != 0) {
		if ($_REQUEST['separa'] == ';') {
			$cSepara = ';';
			$cEvita = ',';
		}
	}
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_3000 = 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = 'lg/lg_3000_es.php';
	}
	require $mensajes_3000;
	*/
	$mensajes_3074 = 'lg/lg_3074_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3074)) {
		$mensajes_3074 = 'lg/lg_3074_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3074;
	$saiu74codmodulo_lg = cadena_tildes($ETI['saiu74codmodulo']);
	$saiu74idreg_lg = cadena_tildes($ETI['saiu74idreg']);
	$saiu74id_lg = cadena_tildes($ETI['saiu74id']);
	$saiu74agno_lg = cadena_tildes($ETI['saiu74agno']);
	$saiu74acepta_lg = cadena_tildes($ETI['saiu74acepta']);
	$saiu74fecharespuesta_lg = cadena_tildes($ETI['saiu74fecharespuesta']);
	$saiu74preg1_lg = cadena_tildes($ETI['saiu74preg1']);
	$saiu74preg2_lg = cadena_tildes($ETI['saiu74preg2']);
	$saiu74preg3_lg = cadena_tildes($ETI['saiu74preg3']);
	$saiu74preg4_lg = cadena_tildes($ETI['saiu74preg4']);
	$saiu74preg5_lg = cadena_tildes($ETI['saiu74preg5']);
	$saiu74preg6_lg = cadena_tildes($ETI['saiu74preg6']);
	$saiu74comentario_lg = cadena_tildes($ETI['saiu74comentario']);
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$sCondi = 'WHERE saiu74codmodulo=' . $DATA['saiu74codmodulo'] . ' AND saiu74idreg=' . $DATA['saiu74idreg'] . '';
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't3074.csv';
	$sTituloRpt = 'saiu74encuesta';
	$sNombrePlanoFinal = $sTituloRpt . '.csv';
	$objplano = new clsPlanos($sPath . $sNombrePlano);
	$idEntidad = Traer_Entidad();
	$sDato = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	switch($idEntidad) {
		case 1: // Unad Florida
			$sDato = 'UNAD FLORIDA INC';
			break;
		case 2: // Unad UE
			$sDato = 'UNAD UNION EUROPEA';
			break;
	}
	$objplano->AdicionarLinea($sDato);
	$sDato = cadena_codificar('saiu74encuesta');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	//$asaiu74codmodulo = array();
	//$asaiu74idreg = array();
	//$asaiu74agno = array();
	//$asaiu74acepta = array();
	$sTitulo1='Titulo 1';
	for ($l = 1; $l <= 12; $l++) {
		$sTitulo1 = $sTitulo1.$cSepara;
	}
	$sBloque1 = '' . $saiu74codmodulo_lg.$cSepara . $saiu74idreg_lg.$cSepara . $saiu74agno_lg.$cSepara . $saiu74acepta_lg.$cSepara . $saiu74fecharespuesta_lg.$cSepara
	 . $saiu74preg1_lg.$cSepara . $saiu74preg2_lg.$cSepara . $saiu74preg3_lg.$cSepara . $saiu74preg4_lg.$cSepara . $saiu74preg5_lg.$cSepara
	 . $saiu74preg6_lg.$cSepara . $saiu74comentario_lg;
	//$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sSQL = 'SELECT * 
	FROM saiu74encuesta 
	' . $sCondi . '';
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_saiu74codmodulo = $cSepara;
		$lin_saiu74idreg = $cSepara;
		$lin_saiu74agno = $cSepara;
		$lin_saiu74acepta = $cSepara;
		$lin_saiu74fecharespuesta = $cSepara;
		$lin_saiu74preg1 = $cSepara;
		$lin_saiu74preg2 = $cSepara;
		$lin_saiu74preg3 = $cSepara;
		$lin_saiu74preg4 = $cSepara;
		$lin_saiu74preg5 = $cSepara;
		$lin_saiu74preg6 = $cSepara;
		$lin_saiu74comentario = $cSepara;
		$lin_saiu74codmodulo = '[' . $fila['saiu74codmodulo'] . ']';
		if (isset($asaiu74codmodulo[$fila['saiu74codmodulo']]) != 0) {
			$lin_saiu74codmodulo = cadena_codificar($asaiu74codmodulo[$fila['saiu74codmodulo']]);
		}
		$lin_saiu74idreg = $cSepara . '[' . $fila['saiu74idreg'] . ']';
		if (isset($asaiu74idreg[$fila['saiu74idreg']]) != 0) {
			$lin_saiu74idreg = $cSepara . cadena_codificar($asaiu74idreg[$fila['saiu74idreg']]);
		}
		$lin_saiu74agno = $cSepara . '[' . $fila['saiu74agno'] . ']';
		if (isset($asaiu74agno[$fila['saiu74agno']]) != 0) {
			$lin_saiu74agno = $cSepara . cadena_codificar($asaiu74agno[$fila['saiu74agno']]);
		}
		$lin_saiu74acepta = $cSepara . '[' . $fila['saiu74acepta'] . ']';
		if (isset($asaiu74acepta[$fila['saiu74acepta']]) != 0) {
			$lin_saiu74acepta = $cSepara . cadena_codificar($asaiu74acepta[$fila['saiu74acepta']]);
		}
		$lin_saiu74fecharespuesta = $cSepara . $fila['saiu74fecharespuesta'];
		$lin_saiu74preg1 = $cSepara . $fila['saiu74preg1'];
		$lin_saiu74preg2 = $cSepara . $fila['saiu74preg2'];
		$lin_saiu74preg3 = $cSepara . $fila['saiu74preg3'];
		$lin_saiu74preg4 = $cSepara . $fila['saiu74preg4'];
		$lin_saiu74preg5 = $cSepara . $fila['saiu74preg5'];
		$lin_saiu74preg6 = $cSepara . $fila['saiu74preg6'];
		$lin_saiu74comentario = $cSepara . str_replace($cSepara, $cComplementa, cadena_codificar($fila['saiu74comentario']));
		$sBloque1 = '' . $lin_saiu74codmodulo . $lin_saiu74idreg . $lin_saiu74agno . $lin_saiu74acepta . $lin_saiu74fecharespuesta
		 . $lin_saiu74preg1 . $lin_saiu74preg2 . $lin_saiu74preg3 . $lin_saiu74preg4 . $lin_saiu74preg5
		 . $lin_saiu74preg6 . $lin_saiu74comentario;
		$objplano->AdicionarLinea($sBloque1);
	}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: ' . filesize($sPath . $sNombrePlano));
	header('Content-Disposition: attachment; filename=' . basename($sNombrePlanoFinal));
	readfile($sPath . $sNombrePlano);
} else {
	echo $sError;
}
