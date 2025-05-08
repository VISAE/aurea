<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.0.12c lunes, 21 de abril de 2025
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
require $APP->rutacomun . 'libexcel_ss.php';
require $APP->rutacomun . 'vendor/autoload.php';
if ((int)$_SESSION['unad_id_tercero'] == 0) {
	die();
} else {
	$idTercero = numeros_validar($_SESSION['unad_id_tercero']);
	if ($idTercero != $_SESSION['unad_id_tercero']) {
		die();
	}
}
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$_SESSION['u_ultimominuto'] = iminutoavance();
$sError = '';
$iReporte = 0;
$bDebug = false;
if (isset($_REQUEST['clave']) == 0) {
	$_REQUEST['clave'] = '';
}
if (isset($_REQUEST['rdebug']) == 0) {
	$_REQUEST['rdebug'] = 0;
}
$aNombres = array(
	'', '', ''
);
$aTipos = array(
	0, 0, 0
);
$iNumVariables = 2;
for ($k = 3; $k <= $iNumVariables; $k++) {
	if (isset($_REQUEST['v' . $k]) == 0) {
		$_REQUEST['v' . $k] = '';
	} else {
		//Validar las variables.
		if ($aTipos[$k] == 1) {
			$vVr = cadena_Validar($_REQUEST['v' . $k]);
		} else {
			$vVr = numeros_validar($_REQUEST['v' . $k]);
		}
		if ($vVr != $_REQUEST['v' . $k]) {
			$sError = 'No fue posible validar el contenido de la variable ' . $aNombres[$k];
		}
	}
}
$sDebug = '';
if ($sError == '') {
	for ($k = 3; $k <= $iNumVariables; $k++){
		switch($k){
			case 31: //Variable tipo texto
				$iVr = cadena_Validar($_REQUEST['v' . $k]);
				break;
			default:
				$iVr = numeros_validar($_REQUEST['v' . $k]);
				break;
		}
		if ($iVr != $_REQUEST['v' . $k]) {
			$sError = 'No fue posible validar el contenido de la variable ' . $k . '';
			$k = $iNumVariables + 1;
		}
	}
}
if ($sError == '') {
	//Validar permisos.
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	list($bEntra, $sDebugP) = seg_revisa_permisoV3(3073, 6, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No tiene permiso para consultar este reporte [Mod 3073 : 6]';
	}
}
if ($sError == '') {
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$sTituloRpt = 'Reporte';
	$sFormato = 'formato.xlsx';
	if (!file_exists($sFormato)) {
		$sError = 'Formato no encontrado {' . $sFormato . '}';
	}
}
if ($sError == '') {
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$bNombre = $_REQUEST['v3'];
	$bListar = $_REQUEST['v4'];
	$sSubtitulo = '';
	$sDetalle = '';
	$sSQLadd = '';
	$sSQLadd1 = '';
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sCampos = 'SELECT T1.saiu16nombre, TB.saiu73consec, TB.saiu73id, TB.saiu73origenagno, TB.saiu73origenmes, TB.saiu73origenid, TB.saiu73fecha, TB.saiu73hora, TB.saiu73minuto, T10.saiu11nombre, T11.unad11razonsocial AS C11_nombre, T12.bita07nombre, T13.saiu01titulo, T14.saiu02titulo, T15.saiu03titulo, T16.unad23nombre, T17.unad24nombre, T18.unad18nombre, T19.unad19nombre, T20.unad20nombre, T21.core12nombre, T22.core09nombre, T23.exte02nombre, TB.saiu73idpqrs, TB.saiu73detalle, TB.saiu73idorigen, TB.saiu73idarchivo, TB.saiu73fechafin, TB.saiu73horafin, TB.saiu73minutofin, TB.saiu73paramercadeo, T32.unad11razonsocial AS C32_nombre, TB.saiu73tiemprespdias, TB.saiu73tiempresphoras, TB.saiu73tiemprespminutos, TB.saiu73solucion, TB.saiu73idcaso, TB.saiu73respuesta, TB.saiu73idorigenrta, TB.saiu73idarchivorta, TB.saiu73fecharespcaso, TB.saiu73horarespcaso, TB.saiu73minrespcaso, TB.saiu73idunidadcaso, TB.saiu73idequipocaso, TB.saiu73idsupervisorcaso, TB.saiu73idresponsablecaso, TB.saiu73numref, TB.saiu73idcanal, T50.saiu22nombre, TB.saiu73numtelefono, TB.saiu73numorigen, T53.saiu27nombre, TB.saiu73numsesionchat, TB.saiu73idcorreo, TB.saiu73idcorreootro, TB.saiu73correoorigen, TB.saiu73tiporadicado, TB.saiu73estado, TB.saiu73idsolicitante, T11.unad11tipodoc AS C11_td, T11.unad11doc AS C11_doc, TB.saiu73tipointeresado, TB.saiu73clasesolicitud, TB.saiu73tiposolicitud, TB.saiu73temasolicitud, TB.saiu73idzona, TB.saiu73idcentro, TB.saiu73codpais, TB.saiu73coddepto, TB.saiu73codciudad, TB.saiu73idescuela, TB.saiu73idprograma, TB.saiu73idperiodo, TB.saiu73idresponsable, T32.unad11tipodoc AS C32_td, T32.unad11doc AS C32_doc, TB.saiu73idtelefono, TB.saiu73idchat';
	$sConsulta = 'FROM saiu73solusuario AS TB, saiu16tiporadicado AS T1, saiu11estadosol AS T10, unad11terceros AS T11, bita07tiposolicitante AS T12, saiu01claseser AS T13, saiu02tiposol AS T14, saiu03temasol AS T15, unad23zona AS T16, unad24sede AS T17, unad18pais AS T18, unad19depto AS T19, unad20ciudad AS T20, core12escuela AS T21, core09programa AS T22, exte02per_aca AS T23, unad11terceros AS T32, saiu22telefonos AS T50, saiu27chats AS T53 
	WHERE ' . $sSQLadd1 . ' TB.saiu73id>0 AND TB.saiu73tiporadicado=T1.saiu16id AND TB.saiu73estado=T10.saiu11id AND TB.saiu73idsolicitante=T11.unad11id AND TB.saiu73tipointeresado=T12.bita07id AND TB.saiu73clasesolicitud=T13.saiu01id AND TB.saiu73tiposolicitud=T14.saiu02id AND TB.saiu73temasolicitud=T15.saiu03id AND TB.saiu73idzona=T16.unad23id AND TB.saiu73idcentro=T17.unad24id AND TB.saiu73codpais=T18.unad18codigo AND TB.saiu73coddepto=T19.unad19codigo AND TB.saiu73codciudad=T20.unad20codigo AND TB.saiu73idescuela=T21.core12id AND TB.saiu73idprograma=T22.core09id AND TB.saiu73idperiodo=T23.exte02id AND TB.saiu73idresponsable=T32.unad11id AND TB.saiu73idtelefono=T50.saiu22id AND TB.saiu73idchat=T53.saiu27id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.saiu73tiporadicado, TB.saiu73consec';
	$sSQLReporte = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
}
if ($sError == '') {
	$sProtocolo = 'http';	if (isset($_SERVER['HTTPS']) != 0) {
		if ($_SERVER['HTTPS'] == 'on') {
			$sProtocolo = 'https';		}
	}
	$sServerRpt = $sProtocolo . '://' . $_SERVER['SERVER_NAME'];
	// - Quien esta descargando el reporte.
	$sNombreUsuario = '[' . $idTercero . ']';
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_LimpiarTildes($fila['unad11razonsocial']) . ' [' . $idTercero . ']';
	}
	$objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	$objExcel = $objReader->load($sFormato);
	$objExcel->getProperties()->setCreator($sNombreUsuario . ' - http://www.unad.edu.co');
	$objExcel->getProperties()->setLastModifiedBy($sNombreUsuario . ' - http://www.unad.edu.co');
	$objExcel->getProperties()->setTitle($sTituloRpt);
	$objExcel->getProperties()->setSubject($sTituloRpt);
	$objExcel->getProperties()->setDescription('Reporte 3073 del SII 4.0 en ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
	$objHoja = $objExcel->getActiveSheet();
	$objHoja->setTitle(substr($sTituloRpt, 0, 30));
	$objContenedor = $objHoja;
	$sColTope = 'J';
	//Imagen del encabezado
	$sImagenSuperior = $APP->rutacomun . 'imagenes/rpt_cabeza.jpg';
	PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A1');
	if (file_exists($sImagenSuperior)) {
		PHPExcel_Agrega_Dibujo($objContenedor, 'Logo', 'Logo', $sImagenSuperior, '161', 'A1', '0',false, '0');
	}
	$sFechaImpreso = formato_fechalarga(fecha_hoy(), true) . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto());
	PHPExcel_Texto_Tres_Partes($objContenedor, $sColTope . '9', ' ', 'Fecha impresión: ', $sFechaImpreso, 'AmOsUn', true, false, 9, 'Calibri', 'AzOsUn');
	PHPExcel_Alinear_Celda_Derecha($objContenedor, $sColTope . '9');
	$iFila = 12;
	PHPEXCEL_Escribir($objHoja, 0, $iFila, 'Formato de Atención Virtual');
	PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila);
	PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A' . $iFila);
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila, '14', 'Yu Gothic', 'AzOsUn', true, false, false);
	//Espacio para el encabezado
	if ($sSubtitulo != '') {
		$iFila++;
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $sSubtitulo);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A' . $iFila);
		PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila, '12', 'Yu Gothic', 'AmOsUn', true, false, false);
		PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila);
	}
	if ($sDetalle != '') {
		$iFila++;
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $sDetalle);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A' . $iFila);
		PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
		PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila);
	}
	PHPExcel_RellenarCeldas($objContenedor, 'A1:' . $sColTope . $iFila, 'Bl', false);
	$iFila++;
	$iFilaBase = $iFila;
	$aTitulos = array(
		'Col0', 'Col1', 'Col2', 'Col3', 'Col4',
		'Col5', 'Col6', 'Col7', 'Col8', 'Col9'
	);
	$aAnchos = array(
		13, 13, 13, 13, 13,
		13, 13, 13, 13, 13
	);
	for ($k = 0; $k <= 9; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFila, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		$objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, $sColumna . $iFila);
	}
	//PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':B' . $iFila . '');
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFila++;
	$asaiu73tiporadicado = array();
	$asaiu73estado = array();
	$asaiu73tipointeresado = array();
	$asaiu73clasesolicitud = array();
	$asaiu73tiposolicitud = array();
	$asaiu73temasolicitud = array();
	$asaiu73idzona = array();
	$asaiu73idcentro = array();
	$asaiu73idescuela = array();
	$asaiu73idprograma = array();
	$asaiu73idperiodo = array();
	$asaiu73idtelefono = array();
	$asaiu73idchat = array();
	$tabla = $objDB->ejecutasql($sSQLReporte);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQL);
		$iFila++;
	}
	while ($fila = $objDB->sf($tabla)) {
		$et_saiu73tiporadicado = '';
		if ($fila['saiu73tiporadicado'] != 0) {
			if (isset($asaiu73tiporadicado[$fila['saiu73tiporadicado']]) == 0) {
				$sDato = '{' . $fila['saiu73tiporadicado'] . '}';
				$sSQL = 'SELECT saiu16nombre FROM saiu16tiporadicado WHERE saiu16id=' . $fila['saiu73tiporadicado'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['saiu16nombre'];
				}
				$asaiu73tiporadicado[$fila['saiu73tiporadicado']] = $sDato;
			}
			$et_saiu73tiporadicado = $asaiu73tiporadicado[$fila['saiu73tiporadicado']];
		}
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $et_saiu73tiporadicado);
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $fila['saiu73consec']);
		PHPEXCEL_Escribir($objHoja, 2, $iFila, $fila['saiu73origenagno']);
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $fila['saiu73origenmes']);
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $fila['saiu73origenid']);
		PHPEXCEL_Escribir($objHoja, 5, $iFila, $fila['saiu73fecha']);
		PHPEXCEL_Escribir($objHoja, 6, $iFila, $fila['saiu73hora']);
		PHPEXCEL_Escribir($objHoja, 7, $iFila, $fila['saiu73minuto']);
		$et_saiu73estado = '';
		if ($fila['saiu73estado'] != 0) {
			if (isset($asaiu73estado[$fila['saiu73estado']]) == 0) {
				$sDato = '{' . $fila['saiu73estado'] . '}';
				$sSQL = 'SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id=' . $fila['saiu73estado'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['saiu11nombre'];
				}
				$asaiu73estado[$fila['saiu73estado']] = $sDato;
			}
			$et_saiu73estado = $asaiu73estado[$fila['saiu73estado']];
		}
		PHPEXCEL_Escribir($objHoja, 8, $iFila, $et_saiu73estado);
		PHPEXCEL_Escribir($objHoja, 9, $iFila, $fila['saiu73idsolicitante']);
		$et_saiu73tipointeresado = '';
		if ($fila['saiu73tipointeresado'] != 0) {
			if (isset($asaiu73tipointeresado[$fila['saiu73tipointeresado']]) == 0) {
				$sDato = '{' . $fila['saiu73tipointeresado'] . '}';
				$sSQL = 'SELECT bita07nombre FROM bita07tiposolicitante WHERE bita07id=' . $fila['saiu73tipointeresado'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['bita07nombre'];
				}
				$asaiu73tipointeresado[$fila['saiu73tipointeresado']] = $sDato;
			}
			$et_saiu73tipointeresado = $asaiu73tipointeresado[$fila['saiu73tipointeresado']];
		}
		PHPEXCEL_Escribir($objHoja, 10, $iFila, $et_saiu73tipointeresado);
		$et_saiu73clasesolicitud = '';
		if ($fila['saiu73clasesolicitud'] != 0) {
			if (isset($asaiu73clasesolicitud[$fila['saiu73clasesolicitud']]) == 0) {
				$sDato = '{' . $fila['saiu73clasesolicitud'] . '}';
				$sSQL = 'SELECT saiu01titulo FROM saiu01claseser WHERE saiu01id=' . $fila['saiu73clasesolicitud'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['saiu01titulo'];
				}
				$asaiu73clasesolicitud[$fila['saiu73clasesolicitud']] = $sDato;
			}
			$et_saiu73clasesolicitud = $asaiu73clasesolicitud[$fila['saiu73clasesolicitud']];
		}
		PHPEXCEL_Escribir($objHoja, 11, $iFila, $et_saiu73clasesolicitud);
		$et_saiu73tiposolicitud = '';
		if ($fila['saiu73tiposolicitud'] != 0) {
			if (isset($asaiu73tiposolicitud[$fila['saiu73tiposolicitud']]) == 0) {
				$sDato = '{' . $fila['saiu73tiposolicitud'] . '}';
				$sSQL = 'SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id=' . $fila['saiu73tiposolicitud'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['saiu02titulo'];
				}
				$asaiu73tiposolicitud[$fila['saiu73tiposolicitud']] = $sDato;
			}
			$et_saiu73tiposolicitud = $asaiu73tiposolicitud[$fila['saiu73tiposolicitud']];
		}
		PHPEXCEL_Escribir($objHoja, 12, $iFila, $et_saiu73tiposolicitud);
		$et_saiu73temasolicitud = '';
		if ($fila['saiu73temasolicitud'] != 0) {
			if (isset($asaiu73temasolicitud[$fila['saiu73temasolicitud']]) == 0) {
				$sDato = '{' . $fila['saiu73temasolicitud'] . '}';
				$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $fila['saiu73temasolicitud'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['saiu03titulo'];
				}
				$asaiu73temasolicitud[$fila['saiu73temasolicitud']] = $sDato;
			}
			$et_saiu73temasolicitud = $asaiu73temasolicitud[$fila['saiu73temasolicitud']];
		}
		PHPEXCEL_Escribir($objHoja, 13, $iFila, $et_saiu73temasolicitud);
		$et_saiu73idzona = '';
		if ($fila['saiu73idzona'] != 0) {
			if (isset($asaiu73idzona[$fila['saiu73idzona']]) == 0) {
				$sDato = '{' . $fila['saiu73idzona'] . '}';
				$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $fila['saiu73idzona'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['unad23nombre'];
				}
				$asaiu73idzona[$fila['saiu73idzona']] = $sDato;
			}
			$et_saiu73idzona = $asaiu73idzona[$fila['saiu73idzona']];
		}
		PHPEXCEL_Escribir($objHoja, 14, $iFila, $et_saiu73idzona);
		$et_saiu73idcentro = '';
		if ($fila['saiu73idcentro'] != 0) {
			if (isset($asaiu73idcentro[$fila['saiu73idcentro']]) == 0) {
				$sDato = '{' . $fila['saiu73idcentro'] . '}';
				$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $fila['saiu73idcentro'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['unad24nombre'];
				}
				$asaiu73idcentro[$fila['saiu73idcentro']] = $sDato;
			}
			$et_saiu73idcentro = $asaiu73idcentro[$fila['saiu73idcentro']];
		}
		PHPEXCEL_Escribir($objHoja, 15, $iFila, $et_saiu73idcentro);
		PHPEXCEL_Escribir($objHoja, 16, $iFila, $fila['saiu73codpais']);
		PHPEXCEL_Escribir($objHoja, 17, $iFila, $fila['saiu73coddepto']);
		PHPEXCEL_Escribir($objHoja, 18, $iFila, $fila['saiu73codciudad']);
		$et_saiu73idescuela = '';
		if ($fila['saiu73idescuela'] != 0) {
			if (isset($asaiu73idescuela[$fila['saiu73idescuela']]) == 0) {
				$sDato = '{' . $fila['saiu73idescuela'] . '}';
				$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $fila['saiu73idescuela'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['core12nombre'];
				}
				$asaiu73idescuela[$fila['saiu73idescuela']] = $sDato;
			}
			$et_saiu73idescuela = $asaiu73idescuela[$fila['saiu73idescuela']];
		}
		PHPEXCEL_Escribir($objHoja, 19, $iFila, $et_saiu73idescuela);
		$et_saiu73idprograma = '';
		if ($fila['saiu73idprograma'] != 0) {
			if (isset($asaiu73idprograma[$fila['saiu73idprograma']]) == 0) {
				$sDato = '{' . $fila['saiu73idprograma'] . '}';
				$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $fila['saiu73idprograma'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['core09nombre'];
				}
				$asaiu73idprograma[$fila['saiu73idprograma']] = $sDato;
			}
			$et_saiu73idprograma = $asaiu73idprograma[$fila['saiu73idprograma']];
		}
		PHPEXCEL_Escribir($objHoja, 20, $iFila, $et_saiu73idprograma);
		$et_saiu73idperiodo = '';
		if ($fila['saiu73idperiodo'] != 0) {
			if (isset($asaiu73idperiodo[$fila['saiu73idperiodo']]) == 0) {
				$sDato = '{' . $fila['saiu73idperiodo'] . '}';
				$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $fila['saiu73idperiodo'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['exte02nombre'];
				}
				$asaiu73idperiodo[$fila['saiu73idperiodo']] = $sDato;
			}
			$et_saiu73idperiodo = $asaiu73idperiodo[$fila['saiu73idperiodo']];
		}
		PHPEXCEL_Escribir($objHoja, 21, $iFila, $et_saiu73idperiodo);
		PHPEXCEL_Escribir($objHoja, 22, $iFila, $fila['saiu73idpqrs']);
		PHPEXCEL_Escribir($objHoja, 23, $iFila, $fila['saiu73detalle']);
		PHPEXCEL_Escribir($objHoja, 24, $iFila, $fila['saiu73idorigen']);
		PHPEXCEL_Escribir($objHoja, 25, $iFila, $fila['saiu73idarchivo']);
		PHPEXCEL_Escribir($objHoja, 26, $iFila, $fila['saiu73fechafin']);
		PHPEXCEL_Escribir($objHoja, 27, $iFila, $fila['saiu73horafin']);
		PHPEXCEL_Escribir($objHoja, 28, $iFila, $fila['saiu73minutofin']);
		$et_saiu73paramercadeo = 'Si';
		if ($fila['saiu73paramercadeo'] == 0) {
			$et_saiu73paramercadeo = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 29, $iFila, $et_saiu73paramercadeo);
		PHPEXCEL_Escribir($objHoja, 30, $iFila, $fila['saiu73idresponsable']);
		PHPEXCEL_Escribir($objHoja, 31, $iFila, $fila['saiu73tiemprespdias']);
		PHPEXCEL_Escribir($objHoja, 32, $iFila, $fila['saiu73tiempresphoras']);
		PHPEXCEL_Escribir($objHoja, 33, $iFila, $fila['saiu73tiemprespminutos']);
		$et_saiu73solucion = 'Si';
		if ($fila['saiu73solucion'] == 0) {
			$et_saiu73solucion = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 34, $iFila, $et_saiu73solucion);
		PHPEXCEL_Escribir($objHoja, 35, $iFila, $fila['saiu73idcaso']);
		PHPEXCEL_Escribir($objHoja, 36, $iFila, $fila['saiu73respuesta']);
		PHPEXCEL_Escribir($objHoja, 37, $iFila, $fila['saiu73idorigenrta']);
		PHPEXCEL_Escribir($objHoja, 38, $iFila, $fila['saiu73idarchivorta']);
		PHPEXCEL_Escribir($objHoja, 39, $iFila, $fila['saiu73fecharespcaso']);
		PHPEXCEL_Escribir($objHoja, 40, $iFila, $fila['saiu73horarespcaso']);
		PHPEXCEL_Escribir($objHoja, 41, $iFila, $fila['saiu73minrespcaso']);
		PHPEXCEL_Escribir($objHoja, 42, $iFila, $fila['saiu73idunidadcaso']);
		PHPEXCEL_Escribir($objHoja, 43, $iFila, $fila['saiu73idequipocaso']);
		PHPEXCEL_Escribir($objHoja, 44, $iFila, $fila['saiu73idsupervisorcaso']);
		PHPEXCEL_Escribir($objHoja, 45, $iFila, $fila['saiu73idresponsablecaso']);
		PHPEXCEL_Escribir($objHoja, 46, $iFila, $fila['saiu73numref']);
		PHPEXCEL_Escribir($objHoja, 47, $iFila, $fila['saiu73idcanal']);
		$et_saiu73idtelefono = '';
		if ($fila['saiu73idtelefono'] != 0) {
			if (isset($asaiu73idtelefono[$fila['saiu73idtelefono']]) == 0) {
				$sDato = '{' . $fila['saiu73idtelefono'] . '}';
				$sSQL = 'SELECT saiu22nombre FROM saiu22telefonos WHERE saiu22id=' . $fila['saiu73idtelefono'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['saiu22nombre'];
				}
				$asaiu73idtelefono[$fila['saiu73idtelefono']] = $sDato;
			}
			$et_saiu73idtelefono = $asaiu73idtelefono[$fila['saiu73idtelefono']];
		}
		PHPEXCEL_Escribir($objHoja, 48, $iFila, $et_saiu73idtelefono);
		PHPEXCEL_Escribir($objHoja, 49, $iFila, $fila['saiu73numtelefono']);
		PHPEXCEL_Escribir($objHoja, 50, $iFila, $fila['saiu73numorigen']);
		$et_saiu73idchat = '';
		if ($fila['saiu73idchat'] != 0) {
			if (isset($asaiu73idchat[$fila['saiu73idchat']]) == 0) {
				$sDato = '{' . $fila['saiu73idchat'] . '}';
				$sSQL = 'SELECT saiu27nombre FROM saiu27chats WHERE saiu27id=' . $fila['saiu73idchat'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['saiu27nombre'];
				}
				$asaiu73idchat[$fila['saiu73idchat']] = $sDato;
			}
			$et_saiu73idchat = $asaiu73idchat[$fila['saiu73idchat']];
		}
		PHPEXCEL_Escribir($objHoja, 51, $iFila, $et_saiu73idchat);
		PHPEXCEL_Escribir($objHoja, 52, $iFila, $fila['saiu73numsesionchat']);
		$et_saiu73idcorreo = 'Si';
		if ($fila['saiu73idcorreo'] == 0) {
			$et_saiu73idcorreo = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 53, $iFila, $et_saiu73idcorreo);
		PHPEXCEL_Escribir($objHoja, 54, $iFila, $fila['saiu73idcorreootro']);
		PHPEXCEL_Escribir($objHoja, 55, $iFila, $fila['saiu73correoorigen']);
		$iFila++;
	}
	$objDB->CerrarConexion();
	PHPExcel_RellenarCeldas($objContenedor, 'A' . $iFilaBase . ':' . $sColTope . $iFila, 'Bl', true);
	PHPEXCEL_Escribir($objHoja, 0, 1, '');
	PHPExcel_RellenarCeldas($objContenedor, 'A1', 'Bl', true);
	if ($_REQUEST['clave'] != '') {
		/* Bloquear la hoja. */
		$objHoja->getProtection()->setPassword($_REQUEST['clave']);
		$objHoja->getProtection()->setSheet(true);
		$objHoja->getProtection()->setSort(true);
	}
	/* descargar el resultado */
	header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); /* la pagina expira en una fecha pasada */
	header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT'); /* ultima actualizacion ahora cuando la cargamos */
	header('Cache-Control: no-cache, must-revalidate'); /* no guardar en CACHE */
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $sTituloRpt . '.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = new Xlsx($objExcel);
	$objWriter->save('php://output');
	die();
} else {
	echo $sError;
}

