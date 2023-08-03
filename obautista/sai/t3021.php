<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.29.6 lunes, 31 de julio de 2023
*/
/*
/** Archivo para reportes tipo csv 3021.
 * Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date lunes, 31 de julio de 2023
 */

/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/

if (file_exists('./err_control.php')) {
	require './err_control.php';
}
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema . ';
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
$bEntra = true;
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
if ($sError != '') {
	$bEntra = false;
}
if ($bEntra) {
	$sDebug = '';
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$cSepara = ',';
	$cEvita = ';';
	$cComplementa = ' . ';
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
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3021;
	$saiu21agno_lg=cadena_tildes($ETI['saiu21agno']);
	$saiu21mes_lg=cadena_tildes($ETI['saiu21mes']);
	$saiu21tiporadicado_lg=cadena_tildes($ETI['saiu21tiporadicado']);
	$saiu21consec_lg=cadena_tildes($ETI['saiu21consec']);
	$saiu21id_lg=cadena_tildes($ETI['saiu21id']);
	$saiu21origenagno_lg=cadena_tildes($ETI['saiu21origenagno']);
	$saiu21origenmes_lg=cadena_tildes($ETI['saiu21origenmes']);
	$saiu21origenid_lg=cadena_tildes($ETI['saiu21origenid']);
	$saiu21dia_lg=cadena_tildes($ETI['saiu21dia']);
	$saiu21hora_lg=cadena_tildes($ETI['saiu21hora']);
	$saiu21minuto_lg=cadena_tildes($ETI['saiu21minuto']);
	$saiu21estado_lg=cadena_tildes($ETI['saiu21estado']);
	$saiu21idcorreo_lg=cadena_tildes($ETI['saiu21idcorreo']);
	$saiu21idsolicitante_lg=cadena_tildes($ETI['saiu21idsolicitante']);
	$saiu21tipointeresado_lg=cadena_tildes($ETI['saiu21tipointeresado']);
	$saiu21clasesolicitud_lg=cadena_tildes($ETI['saiu21clasesolicitud']);
	$saiu21tiposolicitud_lg=cadena_tildes($ETI['saiu21tiposolicitud']);
	$saiu21temasolicitud_lg=cadena_tildes($ETI['saiu21temasolicitud']);
	$saiu21idzona_lg=cadena_tildes($ETI['saiu21idzona']);
	$saiu21idcentro_lg=cadena_tildes($ETI['saiu21idcentro']);
	$saiu21codpais_lg=cadena_tildes($ETI['saiu21codpais']);
	$saiu21coddepto_lg=cadena_tildes($ETI['saiu21coddepto']);
	$saiu21codciudad_lg=cadena_tildes($ETI['saiu21codciudad']);
	$saiu21idescuela_lg=cadena_tildes($ETI['saiu21idescuela']);
	$saiu21idprograma_lg=cadena_tildes($ETI['saiu21idprograma']);
	$saiu21idperiodo_lg=cadena_tildes($ETI['saiu21idperiodo']);
	$saiu21numorigen_lg=cadena_tildes($ETI['saiu21numorigen']);
	$saiu21idpqrs_lg=cadena_tildes($ETI['saiu21idpqrs']);
	$saiu21detalle_lg=cadena_tildes($ETI['saiu21detalle']);
	$saiu21horafin_lg=cadena_tildes($ETI['saiu21horafin']);
	$saiu21minutofin_lg=cadena_tildes($ETI['saiu21minutofin']);
	$saiu21paramercadeo_lg=cadena_tildes($ETI['saiu21paramercadeo']);
	$saiu21idresponsable_lg=cadena_tildes($ETI['saiu21idresponsable']);
	$saiu21tiemprespdias_lg=cadena_tildes($ETI['saiu21tiemprespdias']);
	$saiu21tiempresphoras_lg=cadena_tildes($ETI['saiu21tiempresphoras']);
	$saiu21tiemprespminutos_lg=cadena_tildes($ETI['saiu21tiemprespminutos']);
	$saiu21solucion_lg=cadena_tildes($ETI['saiu21solucion']);
	$saiu21idcaso_lg=cadena_tildes($ETI['saiu21idcaso']);
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$sCondi = 'WHERE saiu21agno=' . $DATA['saiu21agno'] . ' AND saiu21mes=' . $DATA['saiu21mes'] . ' AND saiu21tiporadicado=' . $DATA['saiu21tiporadicado'] . ' AND saiu21consec=' . $DATA['saiu21consec'] . '';
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano = 't3021.csv';
	$sTituloRpt = 'saiu21directa';
	$sNombrePlanoFinal = $sTituloRpt . '.csv';
	$objplano = new clsPlanos($sPath.$sNombrePlano);
	$idEntidad = Traer_Entidad();
	$sDato = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	if ($idEntidad = 1) {
	$sDato = 'UNAD FLORIDA INC';
	}
	$objplano->AdicionarLinea($sDato);
	$sDato = cadena_codificar('saiu21directa');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$asaiu21tiporadicado = array();
	$asaiu21estado = array();
	$asaiu21idcorreo = array();
	$asaiu21tipointeresado = array();
	$asaiu21clasesolicitud = array();
	$asaiu21tiposolicitud = array();
	$asaiu21temasolicitud = array();
	$asaiu21idzona = array();
	$asaiu21idcentro = array();
	$asaiu21idescuela = array();
	$asaiu21idprograma = array();
	$asaiu21idperiodo = array();
	//$asaiu21paramercadeo = array();
	//$asaiu21solucion = array();
	$aSys11 = array();
	$sTitulo1 = 'Titulo 1';
	for ($l = 1; $l <= 20; $l++) {
		$sTitulo1 = $sTitulo1.$cSepara;
	}
	$sBloque1 = ''.$saiu21agno_lg.$cSepara.$saiu21mes_lg.$cSepara.$saiu21tiporadicado_lg.$cSepara.$saiu21consec_lg.$cSepara.$saiu21origenagno_lg.$cSepara
	.$saiu21origenmes_lg.$cSepara.$saiu21origenid_lg.$cSepara.$saiu21dia_lg.$cSepara.$saiu21hora_lg.$cSepara.$saiu21minuto_lg.$cSepara
	.$saiu21estado_lg.$cSepara.$saiu21idcorreo_lg.$cSepara . 'TD' . $cSepara . 'Doc' . $cSepara . $saiu21idsolicitante_lg.$cSepara.$saiu21tipointeresado_lg.$cSepara.$saiu21clasesolicitud_lg.$cSepara
	.$saiu21tiposolicitud_lg.$cSepara.$saiu21temasolicitud_lg.$cSepara.$saiu21idzona_lg;
	$sTitulo2 = 'Titulo 2';
	for ($l = 1; $l <= 20; $l++) {
		$sTitulo2 = $sTitulo2.$cSepara;
	}
	$sBloque2 = ''.$cSepara.$saiu21idcentro_lg.$cSepara.$saiu21codpais_lg.$cSepara.$saiu21coddepto_lg.$cSepara.$saiu21codciudad_lg.$cSepara.$saiu21idescuela_lg.$cSepara
	.$saiu21idprograma_lg.$cSepara.$saiu21idperiodo_lg.$cSepara.$saiu21numorigen_lg.$cSepara.$saiu21idpqrs_lg.$cSepara.$saiu21detalle_lg.$cSepara
	.$saiu21horafin_lg.$cSepara.$saiu21minutofin_lg.$cSepara.$saiu21paramercadeo_lg.$cSepara . 'TD' . $cSepara . 'Doc' . $cSepara . $saiu21idresponsable_lg.$cSepara.$saiu21tiemprespdias_lg.$cSepara
	.$saiu21tiempresphoras_lg.$cSepara.$saiu21tiemprespminutos_lg.$cSepara.$saiu21solucion_lg;
	$sTitulo3='Titulo 3';
	for ($l = 1; $l <= 1; $l++) {
		$sTitulo3 = $sTitulo3.$cSepara;
	}
	$sBloque3 = ''.$cSepara.$saiu21idcaso_lg;
	//$objplano->AdicionarLinea($sTitulo1.$sTitulo2.$sTitulo3);
	$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3);
	$sSQL = 'SELECT * 
	FROM saiu21directa 
	' . $sCondi . '';
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_saiu21agno = $cSepara;
		$lin_saiu21mes = $cSepara;
		$lin_saiu21tiporadicado = $cSepara;
		$lin_saiu21consec = $cSepara;
		$lin_saiu21origenagno = $cSepara;
		$lin_saiu21origenmes = $cSepara;
		$lin_saiu21origenid = $cSepara;
		$lin_saiu21dia = $cSepara;
		$lin_saiu21hora = $cSepara;
		$lin_saiu21minuto = $cSepara;
		$lin_saiu21estado = $cSepara;
		$lin_saiu21idcorreo = $cSepara;
		$lin_saiu21idsolicitante = $cSepara . $cSepara . $cSepara;
		$lin_saiu21tipointeresado = $cSepara;
		$lin_saiu21clasesolicitud = $cSepara;
		$lin_saiu21tiposolicitud = $cSepara;
		$lin_saiu21temasolicitud = $cSepara;
		$lin_saiu21idzona = $cSepara;
		$lin_saiu21idcentro = $cSepara;
		$lin_saiu21codpais = $cSepara;
		$lin_saiu21coddepto = $cSepara;
		$lin_saiu21codciudad = $cSepara;
		$lin_saiu21idescuela = $cSepara;
		$lin_saiu21idprograma = $cSepara;
		$lin_saiu21idperiodo = $cSepara;
		$lin_saiu21numorigen = $cSepara;
		$lin_saiu21idpqrs = $cSepara;
		$lin_saiu21detalle = $cSepara;
		$lin_saiu21horafin = $cSepara;
		$lin_saiu21minutofin = $cSepara;
		$lin_saiu21paramercadeo = $cSepara;
		$lin_saiu21idresponsable = $cSepara . $cSepara . $cSepara;
		$lin_saiu21tiemprespdias = $cSepara;
		$lin_saiu21tiempresphoras = $cSepara;
		$lin_saiu21tiemprespminutos = $cSepara;
		$lin_saiu21solucion = $cSepara;
		$lin_saiu21idcaso = $cSepara;
		$lin_saiu21agno = $fila['saiu21agno'];
		$lin_saiu21mes = $cSepara . $fila['saiu21mes'];
		$i_saiu21tiporadicado = $fila['saiu21tiporadicado'];
		if (isset($asaiu21tiporadicado[$i_saiu21tiporadicado]) == 0) {
			$sSQL = 'SELECT saiu16nombre FROM saiu16tiporadicado WHERE saiu16id=' . $i_saiu21tiporadicado . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21tiporadicado[$i_saiu21tiporadicado] = str_replace($cSepara, $cComplementa, $filae['saiu16nombre']);
			} else {
				$asaiu21tiporadicado[$i_saiu21tiporadicado] = '';
			}
		}
		$lin_saiu21tiporadicado = $cSepara . cadena_codificar($asaiu21tiporadicado[$i_saiu21tiporadicado]);
		$lin_saiu21consec = $cSepara . $fila['saiu21consec'];
		$lin_saiu21origenagno = $cSepara . $fila['saiu21origenagno'];
		$lin_saiu21origenmes = $cSepara . $fila['saiu21origenmes'];
		$lin_saiu21origenid = $cSepara . $fila['saiu21origenid'];
		$lin_saiu21dia = $cSepara . $fila['saiu21dia'];
		$lin_saiu21hora = $cSepara . $fila['saiu21hora'];
		$lin_saiu21minuto = $cSepara . $fila['saiu21minuto'];
		$i_saiu21estado = $fila['saiu21estado'];
		if (isset($asaiu21estado[$i_saiu21estado]) == 0) {
			$sSQL = 'SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id=' . $i_saiu21estado . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21estado[$i_saiu21estado] = str_replace($cSepara, $cComplementa, $filae['saiu11nombre']);
			} else {
				$asaiu21estado[$i_saiu21estado] = '';
			}
		}
		$lin_saiu21estado = $cSepara . cadena_codificar($asaiu21estado[$i_saiu21estado]);
		$i_saiu21idcorreo = $fila['saiu21idcorreo'];
		if (isset($asaiu21idcorreo[$i_saiu21idcorreo]) == 0) {
			$sSQL = 'SELECT saiu57titulo FROM saiu57correos WHERE saiu57id=' . $i_saiu21idcorreo . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21idcorreo[$i_saiu21idcorreo] = str_replace($cSepara, $cComplementa, $filae['saiu57titulo']);
			} else {
				$asaiu21idcorreo[$i_saiu21idcorreo] = '';
			}
		}
		$lin_saiu21idcorreo = $cSepara . cadena_codificar($asaiu21idcorreo[$i_saiu21idcorreo]);
		$iTer = $fila['saiu21idsolicitante'];
		if (isset($aSys11[$iTer]['doc']) == 0) {
			$sSQL = 'SELECT unad11tipodoc, unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id=' . $iTer . '';
			$tabla11 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11) > 0) {
				$fila11 = $objDB->sf($tabla11);
				$aSys11[$iTer]['td'] = $fila11['unad11tipodoc'];
				$aSys11[$iTer]['doc'] = $fila11['unad11doc'];
				$aSys11[$iTer]['razon'] = $fila11['unad11razonsocial'];
			} else {
				$aSys11[$iTer]['td'] = '';
				$aSys11[$iTer]['doc'] = '[' . $iTer . ']';
				$aSys11[$iTer]['razon'] = '';
			}
		}
		$lin_saiu21idsolicitante = $cSepara . $aSys11[$iTer]['td'] . $cSepara . $aSys11[$iTer]['doc'] . $cSepara . cadena_codificar($aSys11[$iTer]['razon']);
		$i_saiu21tipointeresado = $fila['saiu21tipointeresado'];
		if (isset($asaiu21tipointeresado[$i_saiu21tipointeresado]) == 0) {
			$sSQL = 'SELECT bita07nombre FROM bita07tiposolicitante WHERE bita07id=' . $i_saiu21tipointeresado . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21tipointeresado[$i_saiu21tipointeresado] = str_replace($cSepara, $cComplementa, $filae['bita07nombre']);
			} else {
				$asaiu21tipointeresado[$i_saiu21tipointeresado] = '';
			}
		}
		$lin_saiu21tipointeresado = $cSepara . cadena_codificar($asaiu21tipointeresado[$i_saiu21tipointeresado]);
		$i_saiu21clasesolicitud = $fila['saiu21clasesolicitud'];
		if (isset($asaiu21clasesolicitud[$i_saiu21clasesolicitud]) == 0) {
			$sSQL = 'SELECT saiu01titulo FROM saiu01claseser WHERE saiu01id=' . $i_saiu21clasesolicitud . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21clasesolicitud[$i_saiu21clasesolicitud] = str_replace($cSepara, $cComplementa, $filae['saiu01titulo']);
			} else {
				$asaiu21clasesolicitud[$i_saiu21clasesolicitud] = '';
			}
		}
		$lin_saiu21clasesolicitud = $cSepara . cadena_codificar($asaiu21clasesolicitud[$i_saiu21clasesolicitud]);
		$i_saiu21tiposolicitud = $fila['saiu21tiposolicitud'];
		if (isset($asaiu21tiposolicitud[$i_saiu21tiposolicitud]) == 0) {
			$sSQL = 'SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id=' . $i_saiu21tiposolicitud . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21tiposolicitud[$i_saiu21tiposolicitud] = str_replace($cSepara, $cComplementa, $filae['saiu02titulo']);
			} else {
				$asaiu21tiposolicitud[$i_saiu21tiposolicitud] = '';
			}
		}
		$lin_saiu21tiposolicitud = $cSepara . cadena_codificar($asaiu21tiposolicitud[$i_saiu21tiposolicitud]);
		$i_saiu21temasolicitud = $fila['saiu21temasolicitud'];
		if (isset($asaiu21temasolicitud[$i_saiu21temasolicitud]) == 0) {
			$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $i_saiu21temasolicitud . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21temasolicitud[$i_saiu21temasolicitud] = str_replace($cSepara, $cComplementa, $filae['saiu03titulo']);
			} else {
				$asaiu21temasolicitud[$i_saiu21temasolicitud] = '';
			}
		}
		$lin_saiu21temasolicitud = $cSepara . cadena_codificar($asaiu21temasolicitud[$i_saiu21temasolicitud]);
		$i_saiu21idzona = $fila['saiu21idzona'];
		if (isset($asaiu21idzona[$i_saiu21idzona]) == 0) {
			$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_saiu21idzona . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21idzona[$i_saiu21idzona] = str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
			} else {
				$asaiu21idzona[$i_saiu21idzona] = '';
			}
		}
		$lin_saiu21idzona = $cSepara . cadena_codificar($asaiu21idzona[$i_saiu21idzona]);
		$i_saiu21idcentro = $fila['saiu21idcentro'];
		if (isset($asaiu21idcentro[$i_saiu21idcentro]) == 0) {
			$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_saiu21idcentro . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21idcentro[$i_saiu21idcentro] = str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
			} else {
				$asaiu21idcentro[$i_saiu21idcentro] = '';
			}
		}
		$lin_saiu21idcentro = $cSepara . cadena_codificar($asaiu21idcentro[$i_saiu21idcentro]);
		$lin_saiu21codpais = $cSepara . $fila['saiu21codpais'];
		$lin_saiu21coddepto = $cSepara . $fila['saiu21coddepto'];
		$lin_saiu21codciudad = $cSepara . $fila['saiu21codciudad'];
		$i_saiu21idescuela = $fila['saiu21idescuela'];
		if (isset($asaiu21idescuela[$i_saiu21idescuela]) == 0) {
			$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_saiu21idescuela . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21idescuela[$i_saiu21idescuela] = str_replace($cSepara, $cComplementa, $filae['core12nombre']);
			} else {
				$asaiu21idescuela[$i_saiu21idescuela] = '';
			}
		}
		$lin_saiu21idescuela = $cSepara . cadena_codificar($asaiu21idescuela[$i_saiu21idescuela]);
		$i_saiu21idprograma = $fila['saiu21idprograma'];
		if (isset($asaiu21idprograma[$i_saiu21idprograma]) == 0) {
			$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_saiu21idprograma . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21idprograma[$i_saiu21idprograma] = str_replace($cSepara, $cComplementa, $filae['core09nombre']);
			} else {
				$asaiu21idprograma[$i_saiu21idprograma] = '';
			}
		}
		$lin_saiu21idprograma = $cSepara . cadena_codificar($asaiu21idprograma[$i_saiu21idprograma]);
		$i_saiu21idperiodo = $fila['saiu21idperiodo'];
		if (isset($asaiu21idperiodo[$i_saiu21idperiodo]) == 0) {
			$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $i_saiu21idperiodo . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu21idperiodo[$i_saiu21idperiodo] = str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
			} else {
				$asaiu21idperiodo[$i_saiu21idperiodo] = '';
			}
		}
		$lin_saiu21idperiodo = $cSepara . cadena_codificar($asaiu21idperiodo[$i_saiu21idperiodo]);
		$lin_saiu21numorigen = $cSepara . str_replace($cSepara, $cComplementa, cadena_codificar($fila['saiu21numorigen']));
		$lin_saiu21idpqrs = $cSepara . $fila['saiu21idpqrs'];
		$lin_saiu21detalle = $cSepara . str_replace($cSepara, $cComplementa, cadena_codificar($fila['saiu21detalle']));
		$lin_saiu21horafin = $cSepara . $fila['saiu21horafin'];
		$lin_saiu21minutofin = $cSepara . $fila['saiu21minutofin'];
		$lin_saiu21paramercadeo = $cSepara . '[' . $fila['saiu21paramercadeo'] . ']';
		if (isset($asaiu21paramercadeo[$fila['saiu21paramercadeo']]) != 0) {
			$lin_saiu21paramercadeo = $cSepara . cadena_codificar($asaiu21paramercadeo[$fila['saiu21paramercadeo']]);
		}
		$iTer = $fila['saiu21idresponsable'];
		if (isset($aSys11[$iTer]['doc']) == 0) {
			$sSQL = 'SELECT unad11tipodoc, unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id=' . $iTer . '';
			$tabla11 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11) > 0) {
				$fila11 = $objDB->sf($tabla11);
				$aSys11[$iTer]['td'] = $fila11['unad11tipodoc'];
				$aSys11[$iTer]['doc'] = $fila11['unad11doc'];
				$aSys11[$iTer]['razon'] = $fila11['unad11razonsocial'];
			} else {
				$aSys11[$iTer]['td'] = '';
				$aSys11[$iTer]['doc'] = '[' . $iTer . ']';
				$aSys11[$iTer]['razon'] = '';
			}
		}
		$lin_saiu21idresponsable = $cSepara . $aSys11[$iTer]['td'] . $cSepara . $aSys11[$iTer]['doc'] . $cSepara . cadena_codificar($aSys11[$iTer]['razon']);
		$lin_saiu21tiemprespdias = $cSepara . $fila['saiu21tiemprespdias'];
		$lin_saiu21tiempresphoras = $cSepara . $fila['saiu21tiempresphoras'];
		$lin_saiu21tiemprespminutos = $cSepara . $fila['saiu21tiemprespminutos'];
		$lin_saiu21solucion = $cSepara . '[' . $fila['saiu21solucion'] . ']';
		if (isset($asaiu21solucion[$fila['saiu21solucion']]) != 0) {
			$lin_saiu21solucion = $cSepara . cadena_codificar($asaiu21solucion[$fila['saiu21solucion']]);
		}
		$lin_saiu21idcaso = $cSepara . $fila['saiu21idcaso'];
		$sBloque1 = ''.$lin_saiu21agno.$lin_saiu21mes.$lin_saiu21tiporadicado.$lin_saiu21consec.$lin_saiu21origenagno
		.$lin_saiu21origenmes.$lin_saiu21origenid.$lin_saiu21dia.$lin_saiu21hora.$lin_saiu21minuto
		.$lin_saiu21estado.$lin_saiu21idcorreo.$lin_saiu21idsolicitante.$lin_saiu21tipointeresado.$lin_saiu21clasesolicitud
		.$lin_saiu21tiposolicitud.$lin_saiu21temasolicitud.$lin_saiu21idzona;
		$sBloque2 = ''.$lin_saiu21idcentro.$lin_saiu21codpais.$lin_saiu21coddepto.$lin_saiu21codciudad.$lin_saiu21idescuela
		.$lin_saiu21idprograma.$lin_saiu21idperiodo.$lin_saiu21numorigen.$lin_saiu21idpqrs.$lin_saiu21detalle
		.$lin_saiu21horafin.$lin_saiu21minutofin.$lin_saiu21paramercadeo.$lin_saiu21idresponsable.$lin_saiu21tiemprespdias
		.$lin_saiu21tiempresphoras.$lin_saiu21tiemprespminutos.$lin_saiu21solucion;
		$sBloque3 = ''.$lin_saiu21idcaso;
		$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3);
	}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
} else {
	echo $sError;
}
?>