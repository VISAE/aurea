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
$mensajes_3073 = $APP->rutacomun . 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3073)) {
	$mensajes_3073 = $APP->rutacomun . 'lg/lg_3073_es.php';
}
require $mensajes_3073;
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
	'', '', '', 'agno', 'estado', 'listar', 'Zona', 'Centro', 'Canal'
);
$aTipos = array(
	0, 0, 0, 0, 0, 0, 0, 0, 0
);
$iNumVariables = 8;
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
	$iAgno = numeros_validar($_REQUEST['v3']);
	$iEstado = numeros_validar($_REQUEST['v4']);
	$iListar = numeros_validar($_REQUEST['v5']);
	$iZona = numeros_validar($_REQUEST['v6']);
	$iCentro = numeros_validar($_REQUEST['v7']);
	$iCanal = numeros_validar($_REQUEST['v8']);
	$bdetalle = cadena_Validar(trim($_REQUEST['v14']));
	$brespuesta = cadena_Validar(trim($_REQUEST['v15']));
	$bfecharadini = numeros_validar($_REQUEST['v16']);
	$bfecharadfin = numeros_validar($_REQUEST['v17']);
	$bfecharptaini = numeros_validar($_REQUEST['v18']);
	$bfecharptafin = numeros_validar($_REQUEST['v19']);
	$idReservado = cadena_Validar($_REQUEST['v20']);
	$sCanal = '';
	$sCanalCorto = '';
	$sSubtitulo = '';
	$sDetalle = '';
	switch ($iCanal) { 
		case 3018:
		$asaiu73solucion=$aSolucion3018;
		break;
		case 3019:
		$asaiu73solucion=$aSolucion3019;
		break;
		case 3020:
		$asaiu73solucion=$aSolucion3020;
		break;
		case 3021:
		case 3073:
		break;
		default:
			$sError = $sError . $ERR['saiu73idcanal'];
	}
	if ($sError == '') {
		$sCanal = cadena_tildes($ETI['canal' . $iCanal]);
		$sCanalCorto = cadena_tildes($ETI['canal' . $iCanal . '_corto']);
		$sTituloRpt = $sTituloRpt . ' ' . $sCanalCorto . ' ' . $iAgno;
	}
}
if ($sError == '') {
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($iEstado !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu73estado=' . $iEstado . '';
	}
	if ($iZona !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu73idzona=' . $iZona . '';
	}
	if ($iCentro !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu73idcentro=' . $iCentro . '';
	}
	if ($iCanal !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu73idcanal=' . $iCanal . '';
	}
	switch ($iListar) {
		case 1:
			$sSQLadd = $sSQLadd . ' AND TB.saiu73idresponsable=' . $idTercero . '';
			break;
		case 2:
			$sSQLadd = $sSQLadd . ' AND TB.saiu73idresponsablecaso=' . $idTercero . '';
			break;
		case 3:
			$aEquipos = array();
			$sEquipos = '';
			$sSQL = 'SELECT bita27id FROM bita27equipotrabajo WHERE bita27activo=1 AND bita27idlider=' . $idTercero . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				while ($fila = $objDB->sf($tabla)) {
					$aEquipos[] = $fila['bita27id'];
				}
			} else {
				$sSQL = 'SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28activo="S" AND bita28idtercero=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					while ($fila = $objDB->sf($tabla)) {
						$aEquipos[] = $fila['bita28idequipotrab'];
					}
				}
			}
			$sEquipos = implode(',', $aEquipos);
			if ($sEquipos != '') {
				$sSQLadd = $sSQLadd . ' AND TB.saiu73idequipocaso IN (' . $sEquipos . ')';
			} else {
				$sSQLadd = $sSQLadd . ' AND TB.saiu73idresponsablecaso=' . $idTercero . '';
			}
			break;
	}
	if ($bdetalle != '') {
		$sDetalle = $sDetalle . ' En la solicitud se incluye las palabras: ' . $bdetalle;
		$sBase = mb_strtoupper($bdetalle);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd1 = $sSQLadd1 . 'TB.saiu73detalle LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	if ($brespuesta != '') {
		$sDetalle = $sDetalle . ' En la respuesta se incluye las palabras: ' . $brespuesta;
		$sBase = mb_strtoupper($brespuesta);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd1 = $sSQLadd1 . 'TB.saiu73respuesta LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	if (fecha_NumValido($bfecharadini)) {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu73fecharad>=' . $bfecharadini . ' AND ';
	}
	if (fecha_NumValido($bfecharadfin)) {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu73fecharad<=' . $bfecharadfin . ' AND ';
	}
	if (fecha_NumValido($bfecharptaini)) {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu73fechafin>=' . $bfecharptaini . ' AND ';
	}
	if (fecha_NumValido($bfecharptafin)) {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu73fechafin<=' . $bfecharptafin . ' AND ';
	}
	$iCodModulo = 3073;
	$bEsReservado = false;
	list($bEsReservado, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 14, $_SESSION['unad_id_tercero'], $objDB);
	if (!$bEsReservado) {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu73tiposolicitud NOT IN (' . $idReservado . ') AND ';
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sCampos = 'SELECT TB.saiu73agno, TB.saiu73mes, TB.saiu73dia, TB.saiu73id, TB.saiu73estado, 
	TB.saiu73hora, TB.saiu73minuto, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial AS nominteresado, 
	TB.saiu73idzona, TB.saiu73idcentro, TB.saiu73idescuela, TB.saiu73idprograma, TB.saiu73solucion, 
	TB.saiu73tiposolicitud, TB.saiu73temasolicitud, TB.saiu73idunidadcaso, TB.saiu73idequipocaso, TB.saiu73idsupervisorcaso, 
	TB.saiu73idresponsablecaso, TB.saiu73fechafin, TB.saiu73horafin, TB.saiu73minutofin, TB.saiu73fecharespcaso, 
	TB.saiu73horarespcaso, TB.saiu73minrespcaso, TB.saiu73evalfecha, TB.saiu73evalacepta, TB.saiu73evalamabilidad, 
	TB.saiu73evalrapidez, TB.saiu73evalclaridad, TB.saiu73evalresolvio, TB.saiu73evalconocimiento, TB.saiu73evalutilidad, 
	TB.saiu73evalsugerencias, TB.saiu73idresponsable, TB.saiu73tipointeresado, TB.saiu73consec, TB.saiu73fecharad';
	$sConsulta = 'FROM saiu73solusuario_' . $iAgno . ' AS TB, unad11terceros AS T11
	WHERE ' . $sSQLadd1 . ' TB.saiu73idsolicitante=T11.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.saiu73estado, TB.saiu73fecharad, TB.saiu73consec';
	$sSQLReporte = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
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
	$objExcel->getProperties()->setDescription('Reporte ' . $sCanal . ' ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
	$objHoja = $objExcel->getActiveSheet();
	$objHoja->setTitle(substr($sTituloRpt, 0, 30));
	$objContenedor = $objHoja;
	$sColTope = 'AF';
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
	PHPEXCEL_Escribir($objHoja, 0, $iFila, $sCanal . ' ' . $iAgno);
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
		cadena_tildes($ETI['saiu73consec']), cadena_tildes($ETI['saiu73fecharad']), cadena_tildes($ETI['saiu73estado']),cadena_tildes($ETI['saiu73fecharespuesta']), cadena_tildes($ETI['saiu73horarespuesta']), 
		cadena_tildes($ETI['saiu73solucion']), cadena_tildes($ETI['saiu73tiposolicitud']), cadena_tildes($ETI['saiu73temasolicitud']), cadena_tildes($ETI['saiu73idunidadcaso']), cadena_tildes($ETI['saiu73idequipocaso']), 
		cadena_tildes($ETI['saiu73idsupervisorcaso']), cadena_tildes($ETI['saiu73idresponsablecaso']), cadena_tildes($ETI['saiu73idsolicitante']), cadena_tildes($ETI['saiu73razonsocial']), cadena_tildes($ETI['saiu73idzona']), 
		cadena_tildes($ETI['saiu73idcentro']), cadena_tildes($ETI['saiu73idescuela']), cadena_tildes($ETI['saiu73idprograma']), cadena_tildes($ETI['saiu73evalfecha']), cadena_tildes($ETI['saiu73evalacepta']), 
		cadena_tildes($ETI['saiu73evalamabilidad']), cadena_tildes($ETI['saiu73evalrapidez']), cadena_tildes($ETI['saiu73evalclaridad']), cadena_tildes($ETI['saiu73evalresolvio']), cadena_tildes($ETI['saiu73evalconocimiento']), 
		cadena_tildes($ETI['saiu73evalutilidad']), cadena_tildes($ETI['saiu73evalsugerencias']), cadena_tildes($ETI['saiu73idresponsable_td']), cadena_tildes($ETI['saiu73idresponsable_doc']), cadena_tildes($ETI['saiu73idresponsable_rs']), 
		cadena_tildes($ETI['saiu73idresponsable_rol']), cadena_tildes($ETI['saiu73tipointeresado'])
	);
	$aAnchos = array(
		13, 15, 13, 15, 15, 
		15, 15, 15, 15, 15, 
		30, 30, 15, 30, 13, 
		13, 13, 13, 15, 13, 
		13, 13, 13, 13, 13, 
		13, 13, 7, 15,	30, 
		13, 13
	);
	for ($k = 0; $k <= 31; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFila, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		$objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, $sColumna . $iFila);
	}
	//PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':B' . $iFila . '');
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFila++;
	$aTerceros[0]['td'] = '';
	$aTerceros[0]['doc'] = '';
	$aTerceros[0]['rs'] = '';
	$aTerceros[0]['rol'] = '';	
	$asaiu73tiporadicado = array();
	$asaiu73estado = array();
	$asaiu73tiposolicitud = array();
	$asaiu73temasolicitud = array();
	$asaiu73idunidadcaso = array();
	$asaiu73idequipocaso = array();
	$asaiu73idsupervisorcaso = array();
	$asaiu73idresponsablecaso = array();
	$asaiu73tipointeresado = array();
	$asaiu73idzona = array();
	$asaiu73idcentro = array();
	$asaiu73idescuela = array();
	$asaiu73idprograma = array();
	$asaiu73idperiodo = array();
	$asaiu73idtelefono = array();
	$asaiu73idchat = array();
	$acalificacion = array('','Deficiente','Malo','Aceptable','Bueno','Excelente');
	$tabla = $objDB->ejecutasql($sSQLReporte);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQL);
		$iFila++;
	}
	while ($fila = $objDB->sf($tabla)) {
		$saiu73fecharad = $ETI['et_estadorad'];
		if ($fila['saiu73estado'] != -1) {				
			$saiu73fecharad = fecha_desdenumero($fila['saiu73fecharad']);
			if ($fila['saiu73fecharad'] == 0) {
				$saiu73fecharad = fecha_armar($fila['saiu73dia'], $fila['saiu73mes'], $fila['saiu73agno']);
			}
		}
		$i_saiu73estado = $fila['saiu73estado'];
		$saiu73fecharesp = fecha_desdenumero($fila['saiu73fechafin']);
		$saiu73horaresp = html_TablaHoraMin($fila['saiu73horafin'], $fila['saiu73minutofin']);
		if (isset($asaiu73estado[$i_saiu73estado]) == 0) {
			$sSQL = 'SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id=' . $i_saiu73estado . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73estado[$i_saiu73estado] = cadena_LimpiarTildes($filae['saiu11nombre']);
			} else {
				$asaiu73estado[$i_saiu73estado] = '';
			}
		}
		$saiu73estado = cadena_tildes($asaiu73estado[$i_saiu73estado]);
		$i_saiu73solucion = $fila['saiu73solucion'];
		$saiu73solucion = cadena_tildes($asaiu73solucion[$i_saiu73solucion]);
		if ($i_saiu73solucion == 3) {
			$saiu73solucion = $ETI['msg_inicaso'];
		}
		$i_saiu73tiposolicitud = $fila['saiu73tiposolicitud'];
		if (isset($asaiu73tiposolicitud[$i_saiu73tiposolicitud]) == 0) {
			$sSQL = 'SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id=' . $i_saiu73tiposolicitud . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73tiposolicitud[$i_saiu73tiposolicitud] = cadena_LimpiarTildes($filae['saiu02titulo']);
			} else {
				$asaiu73tiposolicitud[$i_saiu73tiposolicitud] = '';
			}
		}
		$saiu73tiposolicitud = cadena_tildes($asaiu73tiposolicitud[$i_saiu73tiposolicitud]);
		$i_saiu73temasolicitud = $fila['saiu73temasolicitud'];
		if (isset($asaiu73temasolicitud[$i_saiu73temasolicitud]) == 0) {
			$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $i_saiu73temasolicitud . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73temasolicitud[$i_saiu73temasolicitud] = cadena_LimpiarTildes($filae['saiu03titulo']);
			} else {
				$asaiu73temasolicitud[$i_saiu73temasolicitud] = '';
			}
		}
		$saiu73temasolicitud = ($asaiu73temasolicitud[$i_saiu73temasolicitud]);
		$i_saiu73idunidadcaso = $fila['saiu73idunidadcaso'];
		if (isset($asaiu73idunidadcaso[$i_saiu73idunidadcaso]) == 0) {
			$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $i_saiu73idunidadcaso . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73idunidadcaso[$i_saiu73idunidadcaso] = cadena_LimpiarTildes($filae['unae26nombre']);
			} else {
				$asaiu73idunidadcaso[$i_saiu73idunidadcaso] = '{Ninguna}';
			}
		}
		$saiu73idunidadcaso = ($asaiu73idunidadcaso[$i_saiu73idunidadcaso]);
		$i_saiu73idequipocaso = $fila['saiu73idequipocaso'];
		if (isset($asaiu73idequipocaso[$i_saiu73idequipocaso]) == 0) {
			$sSQL = 'SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id=' . $i_saiu73idequipocaso . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73idequipocaso[$i_saiu73idequipocaso] = cadena_LimpiarTildes($filae['bita27nombre']);
			} else {
				$asaiu73idequipocaso[$i_saiu73idequipocaso] = '{Ninguno}';
			}
		}
		$saiu73idequipocaso = ($asaiu73idequipocaso[$i_saiu73idequipocaso]);
		$i_saiu73idsupervisorcaso = $fila['saiu73idsupervisorcaso'];
		if (isset($asaiu73idsupervisorcaso[$i_saiu73idsupervisorcaso]) == 0) {
			$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu73idsupervisorcaso . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73idsupervisorcaso[$i_saiu73idsupervisorcaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
			} else {
				$asaiu73idsupervisorcaso[$i_saiu73idsupervisorcaso] = '{Ninguno}';
			}
		}
		$saiu73idsupervisorcaso = ($asaiu73idsupervisorcaso[$i_saiu73idsupervisorcaso]);
		$i_saiu73idresponsablecaso = $fila['saiu73idresponsablecaso'];
		if (isset($asaiu73idresponsablecaso[$i_saiu73idresponsablecaso]) == 0) {
			$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu73idresponsablecaso . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73idresponsablecaso[$i_saiu73idresponsablecaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
				//$asaiu73idresponsablecaso[$i_saiu73idresponsablecaso] = cadena_decodificar($filae['unad11razonsocial']);
			} else {
				$asaiu73idresponsablecaso[$i_saiu73idresponsablecaso] = '{Ninguno}';
			}
		}
		$saiu73idresponsablecaso = ($asaiu73idresponsablecaso[$i_saiu73idresponsablecaso]);
		$sDoc = mb_convert_encoding($fila['unad11tipodoc'] . $fila['unad11doc'], 'ISO-8859-1', 'UTF-8');
		$sRazonSocial = mb_convert_encoding($fila['nominteresado'], 'ISO-8859-1', 'UTF-8');
		$i_saiu73idzona = $fila['saiu73idzona'];
		if (isset($asaiu73idzona[$i_saiu73idzona]) == 0) {
			$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_saiu73idzona . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73idzona[$i_saiu73idzona] = $filae['unad23nombre'];
			} else {
				$asaiu73idzona[$i_saiu73idzona] = '{Ninguna}';
			}
		}
		$saiu73idzona = ($asaiu73idzona[$i_saiu73idzona]);
		$i_saiu73idcentro = $fila['saiu73idcentro'];
		if (isset($asaiu73idcentro[$i_saiu73idcentro]) == 0) {
			$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_saiu73idcentro . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73idcentro[$i_saiu73idcentro] = $filae['unad24nombre'];
			} else {
				$asaiu73idcentro[$i_saiu73idcentro] = '{Ninguna}';
			}
		}
		$saiu73idcentro = ($asaiu73idcentro[$i_saiu73idcentro]);
		$i_saiu73idescuela = $fila['saiu73idescuela'];
		if (isset($asaiu73idescuela[$i_saiu73idescuela]) == 0) {
			$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_saiu73idescuela . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73idescuela[$i_saiu73idescuela] = $filae['core12nombre'];
			} else {
				$asaiu73idescuela[$i_saiu73idescuela] = '{Ninguna}';
			}
		}
		$saiu73idescuela = ($asaiu73idescuela[$i_saiu73idescuela]);
		$i_saiu73idprograma = $fila['saiu73idprograma'];
		if (isset($asaiu73idprograma[$i_saiu73idprograma]) == 0) {
			$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_saiu73idprograma . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73idprograma[$i_saiu73idprograma] = $filae['core09nombre'];
			} else {
				$asaiu73idprograma[$i_saiu73idprograma] = '{Ninguno}';
			}
		}
		$saiu73idprograma = ($asaiu73idprograma[$i_saiu73idprograma]);
		$saiu73evalfecha = fecha_desdenumero($fila['saiu73evalfecha']);
		$saiu73evalacepta = '';
		if ($fila['saiu73evalfecha'] > 0) {
			$saiu73evalacepta = 'No';
			if ($fila['saiu73evalacepta'] == 1) {
				$saiu73evalacepta = 'Si';
			}
		}
		$i_saiu73idresponsable = $fila['saiu73idresponsable'];
		if (isset($aTerceros[$i_saiu73idresponsable]['rs']) == 0) {
			$sSQL = 'SELECT unad11tipodoc, unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu73idresponsable . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$aTerceros[$i_saiu73idresponsable]['td'] = $filae['unad11tipodoc'];
				$aTerceros[$i_saiu73idresponsable]['doc'] = $filae['unad11doc'];
				$aTerceros[$i_saiu73idresponsable]['rs'] = $filae['unad11razonsocial'];
				$sRol = '';
				$sSQL = 'SELECT 1 FROM cara13consejeros WHERE cara13idconsejero=' . $i_saiu73idresponsable . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$sRol = 'Consejero';
				}
				if ($sRol == '') {
					$sSQL = 'SELECT TB.plab32idciclo, T31.plab31titulo 
					FROM plab32emonitor AS TB, plab31emonitoresciclo AS T31 
					WHERE TB.plab32idtercero=' . $i_saiu73idresponsable . ' AND TB.plab32idciclo=T31.plab31id 
					ORDER BY T31.plab31fechainicio DESC';
					$tablae = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablae) > 0) {
						$filae = $objDB->sf($tablae);
						$sRol = 'Monitor - ' . $filae['plab31titulo'];
					}
				}
				$aTerceros[$i_saiu73idresponsable]['rol'] = $sRol;
			} else {
				$aTerceros[$i_saiu73idresponsable]['td'] = '';
				$aTerceros[$i_saiu73idresponsable]['doc'] = '';
				$aTerceros[$i_saiu73idresponsable]['rs'] = '{' . $i_saiu73idresponsable . '}';
				$aTerceros[$i_saiu73idresponsable]['rol'] = '';
			}
		}
		$saiu73idresponsable_td = $aTerceros[$i_saiu73idresponsable]['td'];
		$saiu73idresponsable_doc = $aTerceros[$i_saiu73idresponsable]['doc'];
		$saiu73idresponsable_rs = $aTerceros[$i_saiu73idresponsable]['rs'];
		$saiu73idresponsable_rol = $aTerceros[$i_saiu73idresponsable]['rol'];
		$i_saiu73tipointeresado = $fila['saiu73tipointeresado'];
		if (isset($asaiu73tipointeresado[$i_saiu73tipointeresado]) == 0) {
			$sSQL = 'SELECT bita07nombre FROM bita07tiposolicitante WHERE bita07id=' . $i_saiu73tipointeresado . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$asaiu73tipointeresado[$i_saiu73tipointeresado] = $filae['bita07nombre'];
			} else {
				$asaiu73tipointeresado[$i_saiu73tipointeresado] = '{Ninguno}';
			}
		}
		$saiu73tipointeresado = ($asaiu73tipointeresado[$i_saiu73tipointeresado]);
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $fila['saiu73consec']);
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $saiu73fecharad);
		PHPEXCEL_Escribir($objHoja, 2, $iFila, cadena_decodificar($saiu73estado));
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $saiu73fecharesp);
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $saiu73horaresp);
		PHPEXCEL_Escribir($objHoja, 5, $iFila, cadena_decodificar($saiu73solucion));
		PHPEXCEL_Escribir($objHoja, 6, $iFila, cadena_decodificar($saiu73tiposolicitud));
		PHPEXCEL_Escribir($objHoja, 7, $iFila, cadena_decodificar($saiu73temasolicitud));
		PHPEXCEL_Escribir($objHoja, 8, $iFila, cadena_decodificar($saiu73idunidadcaso));
		PHPEXCEL_Escribir($objHoja, 9, $iFila, cadena_decodificar($saiu73idequipocaso));
		PHPEXCEL_Escribir($objHoja, 10, $iFila, $saiu73idsupervisorcaso);
		PHPEXCEL_Escribir($objHoja, 11, $iFila, $saiu73idresponsablecaso);
		PHPEXCEL_Escribir($objHoja, 12, $iFila, $sDoc);
		PHPEXCEL_Escribir($objHoja, 13, $iFila, $sRazonSocial);
		PHPEXCEL_Escribir($objHoja, 14, $iFila, $saiu73idzona);
		PHPEXCEL_Escribir($objHoja, 15, $iFila, $saiu73idcentro);
		PHPEXCEL_Escribir($objHoja, 16, $iFila, $saiu73idescuela);
		PHPEXCEL_Escribir($objHoja, 17, $iFila, $saiu73idprograma);
		PHPEXCEL_Escribir($objHoja, 18, $iFila, $saiu73evalfecha);
		PHPEXCEL_Escribir($objHoja, 19, $iFila, $saiu73evalacepta);
		PHPEXCEL_Escribir($objHoja, 20, $iFila, $acalificacion[$fila['saiu73evalamabilidad']]);
		PHPEXCEL_Escribir($objHoja, 21, $iFila, $acalificacion[$fila['saiu73evalrapidez']]);
		PHPEXCEL_Escribir($objHoja, 22, $iFila, $acalificacion[$fila['saiu73evalclaridad']]);
		PHPEXCEL_Escribir($objHoja, 23, $iFila, $acalificacion[$fila['saiu73evalresolvio']]);
		PHPEXCEL_Escribir($objHoja, 24, $iFila, $acalificacion[$fila['saiu73evalconocimiento']]);
		PHPEXCEL_Escribir($objHoja, 25, $iFila, $acalificacion[$fila['saiu73evalutilidad']]);
		PHPEXCEL_Escribir($objHoja, 26, $iFila, cadena_decodificar($fila['saiu73evalsugerencias']));
		PHPEXCEL_Escribir($objHoja, 27, $iFila, $saiu73idresponsable_td);
		PHPEXCEL_Escribir($objHoja, 28, $iFila, $saiu73idresponsable_doc);
		PHPEXCEL_Escribir($objHoja, 29, $iFila, $saiu73idresponsable_rs);
		PHPEXCEL_Escribir($objHoja, 30, $iFila, $saiu73idresponsable_rol);
		PHPEXCEL_Escribir($objHoja, 31, $iFila, $saiu73tipointeresado);
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

