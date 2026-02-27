<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.1.5 viernes, 27 de febrero de 2026
*/
/*
/** Archivo para reportes tipo csv 2938.
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
	$mensajes_2938 = 'lg/lg_2938_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2938)) {
		$mensajes_2938 = 'lg/lg_2938_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2938;
	$visa38idtipo_lg = 'Tipo convocatoria';
	$visa38consec_lg = 'Consecutivo';
	$visa38id_lg = 'Ref';
	$visa38nombre_lg = 'Nombre prueba';
	$visa38tipoprueba_lg = 'Tipoprueba';
	$visa38puntajemaximo_lg = 'Puntaje';
	$visa38puntajeaproba_lg = 'Puntajeaproba';
	$visa38activo_lg = 'Activo';
	$visa38idnav_lg = 'Idnav';
	$visa38idmoodle_lg = 'Idmoodle';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	// ----------- Espacio para los parametros.
	$sCondi = 'WHERE visa38idtipo=' . $DATA['visa38idtipo'] . ' AND visa38consec=' . $DATA['visa38consec'] . '';
	// ----------- Fin del bloque de parametros.
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't2938.csv';
	$sTituloRpt = 'visa38convpruebas';
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
	$sDato = cadena_codificar('visa38convpruebas');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$avisa38idtipo = array('');
	/*
	$sSQL = 'SELECT visa34id, visa34nombre FROM visa34convtipo';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa38idtipo[$fila['visa34id']] = cadena_codificar($fila['visa34nombre']);
	}
	*/
	$avisa38activo = array('');
	$avisa38idnav = array('');
	/*
	$sSQL = 'SELECT unad39id, unad39nombre FROM unad39nav';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa38idnav[$fila['unad39id']] = cadena_codificar($fila['unad39nombre']);
	}
	*/
	$sTitulo1 = 'Titulo 1';
	for ($l = 1; $l <= 9; $l++) {
		$sTitulo1 = $sTitulo1 . $cSepara;
	}
	$sBloque1 = '' . $visa38idtipo_lg . $cSepara . $visa38consec_lg . $cSepara . $visa38nombre_lg . $cSepara . $visa38tipoprueba_lg . $cSepara . $visa38puntajemaximo_lg . $cSepara
	 . $visa38puntajeaproba_lg . $cSepara . $visa38activo_lg . $cSepara . $visa38idnav_lg . $cSepara . $visa38idmoodle_lg;
	//$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sCampos = 'SELECT TB.*
	$sConsulta = 'FROM visa38convpruebas 
	' . $sCondi . '';
	$sOrden = '';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_visa38idtipo = '';
		$lin_visa38consec = $cSepara;
		$lin_visa38nombre = $cSepara;
		$lin_visa38tipoprueba = $cSepara;
		$lin_visa38puntajemaximo = $cSepara;
		$lin_visa38puntajeaproba = $cSepara;
		$lin_visa38activo = $cSepara;
		$lin_visa38idnav = $cSepara;
		$lin_visa38idmoodle = $cSepara;
		$i_visa38idtipo = $fila['visa38idtipo'];
		if (isset($avisa38idtipo[$i_visa38idtipo]) == 0) {
			$sSQL = 'SELECT visa34nombre FROM visa34convtipo WHERE visa34id=' . $i_visa38idtipo . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa38idtipo[$i_visa38idtipo] = str_replace($cSepara, $cComplementa, $filae['visa34nombre']);
			} else {
				$avisa38idtipo[$i_visa38idtipo] = '';
			}
		}
		$lin_visa38idtipo = cadena_codificar($avisa38idtipo[$i_visa38idtipo]);
		$lin_visa38consec = $cSepara . $fila['visa38consec'];
		$lin_visa38nombre = $cSepara . str_replace($cSepara, $cComplementa, cadena_codificar($fila['visa38nombre']));
		$lin_visa38tipoprueba = $cSepara . $fila['visa38tipoprueba'];
		$lin_visa38puntajemaximo = $cSepara . $fila['visa38puntajemaximo'];
		$lin_visa38puntajeaproba = $cSepara . $fila['visa38puntajeaproba'];
		$lin_visa38activo = $cSepara . '[' . $fila['visa38activo'] . ']';
		if (isset($avisa38activo[$fila['visa38activo']]) != 0) {
			$lin_visa38activo = $cSepara . cadena_codificar($avisa38activo[$fila['visa38activo']]);
		}
		$i_visa38idnav = $fila['visa38idnav'];
		if (isset($avisa38idnav[$i_visa38idnav]) == 0) {
			$sSQL = 'SELECT unad39nombre FROM unad39nav WHERE unad39id=' . $i_visa38idnav . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa38idnav[$i_visa38idnav] = str_replace($cSepara, $cComplementa, $filae['unad39nombre']);
			} else {
				$avisa38idnav[$i_visa38idnav] = '';
			}
		}
		$lin_visa38idnav = $cSepara . cadena_codificar($avisa38idnav[$i_visa38idnav]);
		$lin_visa38idmoodle = $cSepara . $fila['visa38idmoodle'];
		$sBloque1 = '' . $lin_visa38idtipo . $lin_visa38consec . $lin_visa38nombre . $lin_visa38tipoprueba . $lin_visa38puntajemaximo
		 . $lin_visa38puntajeaproba . $lin_visa38activo . $lin_visa38idnav . $lin_visa38idmoodle;
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
