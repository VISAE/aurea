<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.1.5 viernes, 27 de febrero de 2026
*/
/*
/** Archivo para reportes tipo csv 2945.
 * Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @date viernes, 27 de febrero de 2026
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
	$mensajes_2945 = 'lg/lg_2945_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2945)) {
		$mensajes_2945 = 'lg/lg_2945_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2945;
	$visa45idinscripcion_lg = 'Idinscripcion';
	$visa45idprueba_lg = 'Prueba';
	$visa45id_lg = 'Ref';
	$visa45puntaje_lg = 'Puntaje';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	// ----------- Espacio para los parametros.
	$sCondi = 'WHERE visa45idinscripcion=' . $DATA['visa45idinscripcion'] . ' AND visa45idprueba=' . $DATA['visa45idprueba'] . '';
	// ----------- Fin del bloque de parametros.
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't2945.csv';
	$sTituloRpt = 'visa45convpruebares';
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
	$sDato = cadena_codificar('visa45convpruebares');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$avisa45idprueba = array('');
	/*
	$sSQL = 'SELECT visa38id, visa38nombre FROM visa38convpruebas';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa45idprueba[$fila['visa38id']] = cadena_codificar($fila['visa38nombre']);
	}
	*/
	$sTitulo1 = 'Titulo 1';
	for ($l = 1; $l <= 3; $l++) {
		$sTitulo1 = $sTitulo1 . $cSepara;
	}
	$sBloque1 = '' . $visa45idinscripcion_lg . $cSepara . $visa45idprueba_lg . $cSepara . $visa45puntaje_lg;
	//$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sCampos = 'SELECT TB.*
	$sConsulta = 'FROM visa45convpruebares 
	' . $sCondi . '';
	$sOrden = '';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_visa45idinscripcion = '';
		$lin_visa45idprueba = $cSepara;
		$lin_visa45puntaje = $cSepara;
		$lin_visa45idinscripcion = $fila['visa45idinscripcion'];
		$i_visa45idprueba = $fila['visa45idprueba'];
		if (isset($avisa45idprueba[$i_visa45idprueba]) == 0) {
			$sSQL = 'SELECT visa38nombre FROM visa38convpruebas WHERE visa38id=' . $i_visa45idprueba . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa45idprueba[$i_visa45idprueba] = str_replace($cSepara, $cComplementa, $filae['visa38nombre']);
			} else {
				$avisa45idprueba[$i_visa45idprueba] = '';
			}
		}
		$lin_visa45idprueba = $cSepara . cadena_codificar($avisa45idprueba[$i_visa45idprueba]);
		$lin_visa45puntaje = $cSepara . $fila['visa45puntaje'];
		$sBloque1 = '' . $lin_visa45idinscripcion . $lin_visa45idprueba . $lin_visa45puntaje;
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
