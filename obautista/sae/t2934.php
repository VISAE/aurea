<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.1.5 viernes, 27 de febrero de 2026
*/
/*
/** Archivo para reportes tipo csv 2934.
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
	$mensajes_2934 = 'lg/lg_2934_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2934)) {
		$mensajes_2934 = 'lg/lg_2934_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2934;
	$visa34consec_lg = 'Consecutivo';
	$visa34id_lg = 'Ref';
	$visa34nombre_lg = 'Nombre';
	$visa34rolestudiante_lg = 'Rol estudiante';
	$visa34roladministrativo_lg = 'Rol administrativo';
	$visa34rolacademico_lg = 'Rol académico';
	$visa34rolaspirante_lg = 'Rol aspirante';
	$visa34rolegresado_lg = 'Rol egresado';
	$visa34rolexterno_lg = 'Rol externo';
	$visa34grupotipologia_lg = 'Grupo tipología';
	$visa34activo_lg = 'Activo';
	$visa34aplicazona_lg = 'Aplicazona';
	$visa34aplicacentro_lg = 'Aplicacentro';
	$visa34aplicaescuela_lg = 'Aplicaescuela';
	$visa34aplicaprograma_lg = 'Aplicaprograma';
	$bnombre_lg = 'Tipo convocatoria';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	// ----------- Espacio para los parametros.
	$sCondi = 'WHERE visa34consec=' . $DATA['visa34consec'] . '';
	// ----------- Fin del bloque de parametros.
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't2934.csv';
	$sTituloRpt = 'visa34convtipo';
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
	$sDato = cadena_codificar('visa34convtipo');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$avisa34rolestudiante = array('');
	$avisa34roladministrativo = array('');
	$avisa34rolacademico = array('');
	$avisa34rolaspirante = array('');
	$avisa34rolegresado = array('');
	$avisa34rolexterno = array('');
	$avisa34grupotipologia = array('');
	/*
	$sSQL = 'SELECT visa46id, visa46nombre FROM visa46grupotipologia';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa34grupotipologia[$fila['visa46id']] = cadena_codificar($fila['visa46nombre']);
	}
	*/
	$avisa34activo = array('');
	$avisa34aplicazona = array('');
	$avisa34aplicacentro = array('');
	$avisa34aplicaescuela = array('');
	$avisa34aplicaprograma = array('');
	$sTitulo1 = 'Titulo 1';
	for ($l = 1; $l <= 15; $l++) {
		$sTitulo1 = $sTitulo1 . $cSepara;
	}
	$sBloque1 = '' . $visa34consec_lg . $cSepara . $visa34nombre_lg . $cSepara . $visa34rolestudiante_lg . $cSepara . $visa34roladministrativo_lg . $cSepara . $visa34rolacademico_lg . $cSepara
	 . $visa34rolaspirante_lg . $cSepara . $visa34rolegresado_lg . $cSepara . $visa34rolexterno_lg . $cSepara . $visa34grupotipologia_lg . $cSepara . $visa34activo_lg . $cSepara
	 . $visa34aplicazona_lg . $cSepara . $visa34aplicacentro_lg . $cSepara . $visa34aplicaescuela_lg . $cSepara . $visa34aplicaprograma_lg . $cSepara . $bnombre_lg;
	//$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sCampos = 'SELECT TB.*
	$sConsulta = 'FROM visa34convtipo 
	' . $sCondi . '';
	$sOrden = '';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_visa34consec = '';
		$lin_visa34nombre = $cSepara;
		$lin_visa34rolestudiante = $cSepara;
		$lin_visa34roladministrativo = $cSepara;
		$lin_visa34rolacademico = $cSepara;
		$lin_visa34rolaspirante = $cSepara;
		$lin_visa34rolegresado = $cSepara;
		$lin_visa34rolexterno = $cSepara;
		$lin_visa34grupotipologia = $cSepara;
		$lin_visa34activo = $cSepara;
		$lin_visa34aplicazona = $cSepara;
		$lin_visa34aplicacentro = $cSepara;
		$lin_visa34aplicaescuela = $cSepara;
		$lin_visa34aplicaprograma = $cSepara;
		$lin_bnombre = $cSepara;
		$lin_visa34consec = $fila['visa34consec'];
		$lin_visa34nombre = $cSepara . str_replace($cSepara, $cComplementa, cadena_codificar($fila['visa34nombre']));
		$lin_visa34rolestudiante = $cSepara . '[' . $fila['visa34rolestudiante'] . ']';
		if (isset($avisa34rolestudiante[$fila['visa34rolestudiante']]) != 0) {
			$lin_visa34rolestudiante = $cSepara . cadena_codificar($avisa34rolestudiante[$fila['visa34rolestudiante']]);
		}
		$lin_visa34roladministrativo = $cSepara . '[' . $fila['visa34roladministrativo'] . ']';
		if (isset($avisa34roladministrativo[$fila['visa34roladministrativo']]) != 0) {
			$lin_visa34roladministrativo = $cSepara . cadena_codificar($avisa34roladministrativo[$fila['visa34roladministrativo']]);
		}
		$lin_visa34rolacademico = $cSepara . '[' . $fila['visa34rolacademico'] . ']';
		if (isset($avisa34rolacademico[$fila['visa34rolacademico']]) != 0) {
			$lin_visa34rolacademico = $cSepara . cadena_codificar($avisa34rolacademico[$fila['visa34rolacademico']]);
		}
		$lin_visa34rolaspirante = $cSepara . '[' . $fila['visa34rolaspirante'] . ']';
		if (isset($avisa34rolaspirante[$fila['visa34rolaspirante']]) != 0) {
			$lin_visa34rolaspirante = $cSepara . cadena_codificar($avisa34rolaspirante[$fila['visa34rolaspirante']]);
		}
		$lin_visa34rolegresado = $cSepara . '[' . $fila['visa34rolegresado'] . ']';
		if (isset($avisa34rolegresado[$fila['visa34rolegresado']]) != 0) {
			$lin_visa34rolegresado = $cSepara . cadena_codificar($avisa34rolegresado[$fila['visa34rolegresado']]);
		}
		$lin_visa34rolexterno = $cSepara . '[' . $fila['visa34rolexterno'] . ']';
		if (isset($avisa34rolexterno[$fila['visa34rolexterno']]) != 0) {
			$lin_visa34rolexterno = $cSepara . cadena_codificar($avisa34rolexterno[$fila['visa34rolexterno']]);
		}
		$i_visa34grupotipologia = $fila['visa34grupotipologia'];
		if (isset($avisa34grupotipologia[$i_visa34grupotipologia]) == 0) {
			$sSQL = 'SELECT visa46nombre FROM visa46grupotipologia WHERE visa46id=' . $i_visa34grupotipologia . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa34grupotipologia[$i_visa34grupotipologia] = str_replace($cSepara, $cComplementa, $filae['visa46nombre']);
			} else {
				$avisa34grupotipologia[$i_visa34grupotipologia] = '';
			}
		}
		$lin_visa34grupotipologia = $cSepara . cadena_codificar($avisa34grupotipologia[$i_visa34grupotipologia]);
		$lin_visa34activo = $cSepara . '[' . $fila['visa34activo'] . ']';
		if (isset($avisa34activo[$fila['visa34activo']]) != 0) {
			$lin_visa34activo = $cSepara . cadena_codificar($avisa34activo[$fila['visa34activo']]);
		}
		$lin_visa34aplicazona = $cSepara . '[' . $fila['visa34aplicazona'] . ']';
		if (isset($avisa34aplicazona[$fila['visa34aplicazona']]) != 0) {
			$lin_visa34aplicazona = $cSepara . cadena_codificar($avisa34aplicazona[$fila['visa34aplicazona']]);
		}
		$lin_visa34aplicacentro = $cSepara . '[' . $fila['visa34aplicacentro'] . ']';
		if (isset($avisa34aplicacentro[$fila['visa34aplicacentro']]) != 0) {
			$lin_visa34aplicacentro = $cSepara . cadena_codificar($avisa34aplicacentro[$fila['visa34aplicacentro']]);
		}
		$lin_visa34aplicaescuela = $cSepara . '[' . $fila['visa34aplicaescuela'] . ']';
		if (isset($avisa34aplicaescuela[$fila['visa34aplicaescuela']]) != 0) {
			$lin_visa34aplicaescuela = $cSepara . cadena_codificar($avisa34aplicaescuela[$fila['visa34aplicaescuela']]);
		}
		$lin_visa34aplicaprograma = $cSepara . '[' . $fila['visa34aplicaprograma'] . ']';
		if (isset($avisa34aplicaprograma[$fila['visa34aplicaprograma']]) != 0) {
			$lin_visa34aplicaprograma = $cSepara . cadena_codificar($avisa34aplicaprograma[$fila['visa34aplicaprograma']]);
		}
		$lin_bnombre = $cSepara . $fila['bnombre'];
		$sBloque1 = '' . $lin_visa34consec . $lin_visa34nombre . $lin_visa34rolestudiante . $lin_visa34roladministrativo . $lin_visa34rolacademico
		 . $lin_visa34rolaspirante . $lin_visa34rolegresado . $lin_visa34rolexterno . $lin_visa34grupotipologia . $lin_visa34activo
		 . $lin_visa34aplicazona . $lin_visa34aplicacentro . $lin_visa34aplicaescuela . $lin_visa34aplicaprograma . $lin_bnombre;
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
