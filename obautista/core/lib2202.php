<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Cristhiam Dario Silva Chavez - UNAD - 2019 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.0 miércoles, 27 de junio de 2018
--- Modelo Versión 2.22.7 martes, 5 de marzo de 2019
--- Modelo Versión 2.22.8 jueves, 28 de marzo de 2019
--- Modelo Versión 2.25.0 sábado, 20 de junio de 2020
--- Modelo Versión 2.27.0 viernes, 8 de octubre de 2021
--- 2202 core01estprograma
*/

/** Archivo lib2202.php.
 * Libreria 2202 core01estprograma.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date sábado, 20 de junio de 2020
 */
function f2202_HTMLComboV2_core01idprograma($objDB, $objCombos, $valor, $idEscuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sSQL = '';
	if ((int)$idEscuela != 0){
		$sSQL = 'SELECT core09id AS id, CONCAT(core09nombre, " - ", core09codigo, CASE core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END) AS nombre 
		FROM core09programa
		WHERE core09idescuela=' . $idEscuela . ' AND core09id NOT IN (0, 1, 2, 3, 4, 5, 6, 450)
		ORDER BY core09activo DESC, core09nombre';
		$sCondi = '';
	}
	$objCombos->nuevo('core01idprograma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->iAncho = 390;
	$objCombos->sAccion = 'RevisaLlave();';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2202_HTMLComboV2_core01idplandeestudios($objDB, $objCombos, $valor, $vrcore01idprograma)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core01idplandeestudios', $valor, true, '{' . $ETI['msg_ninguno'] . '}', 0);
	if ((int)$vrcore01idprograma > 0) {
		$objCombos->iAncho = 340;
		$sSQL = 'SELECT core10id, core10consec, core10numregcalificado, core10fechaversion, core10fechavence, core10estado 
		FROM core10programaversion 
		WHERE core10idprograma="' . $vrcore01idprograma . '" AND core10estado IN ("S", "X") 
		ORDER BY core10consec DESC';
		// - N&deg; Res 
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$objCombos->addItem($fila['core10id'], $fila['core10consec'] . ' - N&deg; Res ' . $fila['core10numregcalificado'] . ' - Vigente desde ' . fecha_DesdeNumero($fila['core10fechaversion']) . ' hasta ' . fecha_DesdeNumero($fila['core10fechavence']) . '');
		}
	}
	$res = $objCombos->html('', $objDB);
	return $res;
}
function f2202_HTMLComboV2_core011idcead($objDB, $objCombos, $valor, $vrcore01idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sSQL = '';
	if ((int)$vrcore01idzona != 0) {
		$sSQL = 'SELECT unad24id AS id, CONCAT(CASE unad24activa WHEN "S" THEN "" ELSE "--- " END, unad24nombre) AS nombre 
		FROM unad24sede 
		WHERE unad24idzona=' . $vrcore01idzona . ' 
		ORDER BY unad24activa DESC, unad24nombre';
	}
	$objCombos->nuevo('core011idcead', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2202_Combocore01idprograma($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_core01idprograma = f2202_HTMLComboV2_core01idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core01idprograma', 'innerHTML', $html_core01idprograma);
	$objResponse->call('$("#core01idprograma").chosen()');
	return $objResponse;
}
function f2202_Combocore01idplandeestudios($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_core01idplandeestudios = f2202_HTMLComboV2_core01idplandeestudios($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core01idplandeestudios', 'innerHTML', $html_core01idplandeestudios);
	//$objResponse->call('$("#core01idplandeestudios").chosen()');
	return $objResponse;
}
function f2202_Combocore011idcead($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos('n');
	$html_core011idcead = f2202_HTMLComboV2_core011idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core011idcead', 'innerHTML', $html_core011idcead);
	//$objResponse->call('$("#core011idcead").chosen()');
	return $objResponse;
}
function f2202_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$core01idtercero = numeros_validar($datos[1]);
	if ($core01idtercero == '') {
		$bHayLlave = false;
	}
	$core01idprograma = numeros_validar($datos[2]);
	if ($core01idprograma == '') {
		$bHayLlave = false;
	}
	$core01idplandeestudios = numeros_validar($datos[3]);
	if ($core01idplandeestudios == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM core01estprograma WHERE core01idtercero=' . $core01idtercero . ' AND core01idprograma=' . $core01idprograma . ' AND core01idplandeestudios=' . $core01idplandeestudios . '';
		$res = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($res) == 0) {
			$bHayLlave = false;
		}
		$objDB->CerrarConexion();
		if ($bHayLlave) {
			$objResponse = new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
		}
	}
}
function f2202_Busquedas($aParametros)
{
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2202)) {
		$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2202;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$sCampo = $aParametros[1];
	$sTitulo = ' {' . $sCampo . '}';
	if (isset($aParametros[2]) == 0) {
		$aParametros[2] = 0;
	}
	if (isset($aParametros[3]) == 0) {
		$aParametros[3] = 0;
	}
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'core01idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2202);
			break;
		case 'core01idrevision':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2202);
			break;
		case 'core03idcurso':
			/*
			$bExiste = true;
			if (file_exists('lib140.php')) {
				require 'lib140.php';
			} else {
				$bExiste = false;
			}
			if ($bExiste) {
				if (!function_exists('f0_TablaDetalleBusquedas')) {
					$bExiste = false;
				}
			}
			if ($bExiste) {
				$sTabla = f0_TablaDetalleBusquedas($aParametrosB, $objDB);
				$sTitulo = f0_TituloBusqueda();
				$sParams = f0_ParametrosBusqueda();
				$sJavaBusqueda = f0_JavaScriptBusqueda(2202);
			} else {
				$sTitulo = 'Busquedas';
				$sTabla = '<div class="MarquesinaMedia">No se ha definido la busqueda 0, por favor informe al administrador del sistema.</div>';
			}
			*/
			break;
		case 'core03idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2202);
			break;
		case 'core03idusuarionota75':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2202);
			break;
		case 'core03idusuarionota25':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2202);
			break;
		case 'core03idusuarionotahomo':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2202);
			break;
		case 'core23idtercerosale':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2202);
			break;
		case 'core23idterceroentra':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2202);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_2202'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f2202_HtmlBusqueda($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$sError = '';
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sDetalle = '';
	switch ($aParametros[100]) {
		case 'core01idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'core01idrevision':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'core03idcurso':
			/*
			if (file_exists('lib0.php')) {
				require 'lib0.php';
				$sDetalle = f0_TablaDetalleBusquedas($aParametros, $objDB);
			} else {
				$sDetalle = 'No se encuentra la libreria ' . 'lib0, por favor informe al administrador del sistema.';
			}
			*/
			break;
		case 'core03idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'core03idusuarionota75':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'core03idusuarionota25':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'core03idusuarionotahomo':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'core23idtercerosale':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'core23idterceroentra':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2202_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2202)) {
		$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2202;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	if (isset($aParametros[105]) == 0) {
		$aParametros[105] = '';
	}
	if (isset($aParametros[106]) == 0) {
		$aParametros[106] = '';
	}
	if (isset($aParametros[107]) == 0) {
		$aParametros[107] = '';
	}
	if (isset($aParametros[108]) == 0) {
		$aParametros[108] = '';
	}
	if (isset($aParametros[109]) == 0) {
		$aParametros[109] = '';
	}
	if (isset($aParametros[110]) == 0) {
		$aParametros[110] = '';
	}
	if (isset($aParametros[111]) == 0) {
		$aParametros[111] = '';
	}
	if (isset($aParametros[112]) == 0) {
		$aParametros[112] = '';
	}
	if (isset($aParametros[113]) == 0) {
		$aParametros[113] = '';
	}
	if (isset($aParametros[114]) == 0) {
		$aParametros[114] = '';
	}
	if (isset($aParametros[115]) == 0) {
		$aParametros[115] = '';
	}
	if (isset($aParametros[116]) == 0) {
		$aParametros[116] = '';
	}
	if (isset($aParametros[117]) == 0) {
		$aParametros[117] = '';
	}
	if (isset($aParametros[118]) == 0) {
		$aParametros[118] = '';
	}
	if (isset($aParametros[119]) == 0) {
		$aParametros[119] = '';
	}
	if (isset($aParametros[120]) == 0) {
		$aParametros[120] = '';
	}
	if (isset($aParametros[121]) == 0) {
		$aParametros[121] = '';
	}
	if (isset($aParametros[122]) == 0) {
		$aParametros[122] = '';
	}
	if (isset($aParametros[123]) == 0) {
		$aParametros[123] = '';
	}
	if (isset($aParametros[124]) == 0) {
		$aParametros[124] = '';
	}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero = $aParametros[100];
	$sDebug = '';
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	if (true) {
		//Esta linea es para sincronizar con la $APP->rutacomun.e2202v2.php
		// -- INICIA EL ANALISIS DE FILTROS --
		$sDoc = trim($aParametros[103]);
		$bNombre = trim($aParametros[104]);
		$blistar = $aParametros[105];
		$idZona = $aParametros[106];
		$idCead = $aParametros[107];
		$idEscuela = $aParametros[108];
		$idPrograma = $aParametros[109];
		$bConvenio = numeros_validar($aParametros[110]);
		$idPeriodo = $aParametros[111];
		$bversion = $aParametros[112];
		$idEstado = $aParametros[113];
		$iAvanceMin = numeros_validar($aParametros[114]);
		$iAvanceMax = numeros_validar($aParametros[115]);
		$iContinuidad = numeros_validar($aParametros[116]);
		$iCohorte = numeros_validar($aParametros[117]);
		$iOrigen = 0;
		$bMatricula = $aParametros[118];
		$bSituacion = $aParametros[119];
		$bTipoHomol = $aParametros[120];
		$bNivelForma = $aParametros[121];
		$bOpcGrado = $aParametros[122];
		$bPostGrado = $aParametros[123];
		$bFactorDeserta = $aParametros[124];
		$bAbierta = true;
		//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
		//$tabla=$objDB->ejecutasql($sSQL);
		//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
		$sLeyenda = '';
		if ($sLeyenda != '') {
			$sLeyenda = '<div class="salto1px"></div>
			<div class="GrupoCamposAyuda">
			' . $sLeyenda . '
			<div class="salto1px"></div>
			</div>';
			return array($sLeyenda . '<input id="paginaf2202" name="paginaf2202" type="hidden" value="' . $pagina . '"/><input id="lppf2202" name="lppf2202" type="hidden" value="' . $lineastabla . '"/>', $sDebug);
			die();
		}
		$iHoy = fecha_DiaMod();
		$aPlanEst = array();
		$aPlanEst[-1]['nombre'] = '<b>' . $ETI['msg_noaplica'] . '</b>';
		$aPlanEst[-1]['ini'] = '<span>';
		$aPlanEst[0]['nombre'] = '[' . $ETI['msg_pendiente'] . ']';
		$aPlanEst[0]['ini'] = '<span>';
		$aContinuidad = array();
		$sSQL = 'SELECT core36id, core36nombre FROM core36estadocont';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$aContinuidad[$fila['core36id']] = cadena_notildes($fila['core36nombre']);
		}
		$sSQLadd = '';
		$sSQLadd1 = '';
		//$sCondiEstado = 'TB.core01idestado<>10 AND ';
		//Junio 8 de 2022 - Quitamos la condicion de no mostrar los graduados para poder consultar totalmente un estudiante.
		$sCondiEstado = '';
		if ($bversion != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.core01idplandeestudios=' . $bversion . ' AND ';
		}
		if ($idEstado != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.core01idestado=' . $idEstado . ' AND ';
			$sCondiEstado = '';
		}
		if ($sDoc != '') {
			$sSQLadd = $sSQLadd . ' AND T1.unad11doc LIKE "%' . $sDoc . '%"';
		}
		if ($bNombre != '') {
			$sBase = trim(strtoupper($bNombre));
			$aNoms = explode(' ', $sBase);
			for ($k = 1; $k <= count($aNoms); $k++) {
				$sCadena = $aNoms[$k - 1];
				if ($sCadena != '') {
					$sSQLadd = $sSQLadd . ' AND T1.unad11razonsocial LIKE "%' . $sCadena . '%"';
				}
			}
		}
		if ($idPrograma != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.core01idprograma=' . $idPrograma . ' AND ';
		} else {
			if ($bNivelForma != '') {
				//El filtro de nivel
				switch ($bNivelForma) {
					case '':
						break;
					case '-1':
					case '-2':
					case '-3':
					case '-4':
						$iGrupo = $bNivelForma * (-1);
						$sIdProgramas = '-99';
						$sCondiEscuela = '';
						if ($idEscuela != '') {
							$sCondiEscuela = 'TB.core09idescuela=' . $idEscuela . ' AND ';
						}
						$sSQL = 'SELECT TB.core09id 
						FROM core09programa AS TB, core22nivelprograma AS T22 
						WHERE ' . $sCondiEscuela . ' TB.cara09nivelformacion=T22.core22id AND T22.core22grupo=' . $iGrupo . '';
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Programas base: ' . $sSQL . '<br>';
						}
						$tabla = $objDB->ejecutasql($sSQL);
						while ($fila = $objDB->sf($tabla)) {
							$sIdProgramas = $sIdProgramas . ', ' . $fila['core09id'];
						}
						$sSQLadd1 = $sSQLadd1 . 'TB.core01idprograma IN (' . $sIdProgramas . ') AND ';
						break;
					default:
						$sIdProgramas = '-99';
						$sSQL = 'SELECT core09id FROM core09programa WHERE cara09nivelformacion=' . $bNivelForma . '';
						if ($idEscuela != '') {
							$sSQL = $sSQL . ' AND core09idescuela=' . $idEscuela . '';
						}
						$tabla = $objDB->ejecutasql($sSQL);
						while ($fila = $objDB->sf($tabla)) {
							$sIdProgramas = $sIdProgramas . ', ' . $fila['core09id'];
						}
						$sSQLadd1 = $sSQLadd1 . 'TB.core01idprograma IN (' . $sIdProgramas . ') AND ';
						break;
				}
				//Termina el filtro de nivel
			} else {
				if ($idEscuela != '') {
					$sSQLadd1 = $sSQLadd1 . 'TB.core01idescuela=' . $idEscuela . ' AND ';
				}
			}
		}
		if ($idCead != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.core011idcead=' . $idCead . ' AND ';
		} else {
			if ($idZona != '') {
				$sSQLadd1 = $sSQLadd1 . 'TB.core01idzona=' . $idZona . ' AND ';
			}
		}
		if ($idPeriodo != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.core01peracainicial=' . $idPeriodo . ' AND ';
		}
		if ($iCohorte != '') {
			$sIds2 = '-99';
			$sSQL = 'SELECT exte02id FROM exte02per_aca WHERE exte02idciclo=' . $iCohorte . '';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds2 = $sIds2 . ',' . $fila['exte02id'];
			}
			$sSQLadd1 = $sSQLadd1 . 'TB.core01peracainicial IN (' . $sIds2 . ') AND ';
		}
		switch ($blistar) {
			case 1: // Donde soy consejero
				$sIds = '-99';
				$sSQL = 'SELECT cara01idtercero FROM cara01encuesta WHERE cara01idconsejero=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					$sIds = $sIds . ',' . $fila['cara01idtercero'];
				}
				$sSQLadd1 = $sSQLadd1 . 'TB.core01idtercero IN (' . $sIds . ') AND ';
				break;
			case 2: // Programas donde es lider
				$sIds = '-99';
				$sSQL = 'SELECT core09id FROM core09programa WHERE core09iddirector=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					$sIds = $sIds . ',' . $fila['core09id'];
				}
				$sSQLadd1 = $sSQLadd1 . 'TB.core01idprograma IN (' . $sIds . ') AND ';
				break;
			case 3: // Pendientes de aprobación
				$sSQLadd1 = $sSQLadd1 . 'TB.core01idrevision=0 AND ';
				break;
			case 4: // Aprobados
				$sSQLadd1 = $sSQLadd1 . 'TB.core01idrevision>0 AND ';
				break;
		}
		if ($iAvanceMin != '') {
			if ((int)$iAvanceMin > 100) {
				$iAvanceMin = 100;
			}
			$sSQLadd1 = $sSQLadd1 . 'TB.core01avanceplanest>=' . $iAvanceMin . ' AND ';
		}
		if ($iAvanceMax != '') {
			if ((int)$iAvanceMax > 100) {
				$iAvanceMax = 100;
			}
			$sSQLadd1 = $sSQLadd1 . 'TB.core01avanceplanest<=' . $iAvanceMax . ' AND ';
		}
		if ($iContinuidad != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.core01contestado=' . $iContinuidad . ' AND ';
		}
		switch ($iOrigen) {
			case 1:
				$sSQLadd1 = $sSQLadd1 . 'TB.core01estadopractica>=0 AND TB.core01idestado<>10 AND ';
				$aEstadoPractica = array();
				$sSQL = 'SELECT olab43id, olab43nombre FROM olab43estadopractica WHERE olab43id>=0';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					$aEstadoPractica[$fila['olab43id']] = cadena_notildes($fila['olab43nombre']);
				}
				break;
		}
		//Ahora los convenios
		$sTablaConvenio = '';
		if ($bConvenio != '') {
			$sTablaConvenio = ', core51convenioest AS T51';
			$sSQLadd = $sSQLadd . ' AND TB.core01idtercero=T51.core51idtercero AND T51.core51idconvenio=' . $bConvenio . ' AND T51.core51activo="S"';
		}
		//Septiembre 15 de 2022 - Se agregan nuevos filtros.
		if ($bMatricula != '') {
			//core01cantmatriculas
			switch ($bMatricula) {
				case 'm10':
					$sSQLadd1 = $sSQLadd1 . 'TB.core01cantmatriculas>10 AND ';
					break;
				case 'm20':
					$sSQLadd1 = $sSQLadd1 . 'TB.core01cantmatriculas>20 AND ';
					break;
				case 'm30':
					$sSQLadd1 = $sSQLadd1 . 'TB.core01cantmatriculas>30 AND ';
					break;
				default:
				$sSQLadd1 = $sSQLadd1 . 'TB.core01cantmatriculas=' . $bMatricula . ' AND ';
				break;
			}
		}
		$bRevisaPostAsociado = false;
		if ($bOpcGrado != '') {
			if ($bOpcGrado == 'PENDIENTE'){
				$sSQLadd1 = $sSQLadd1 . '(TB.core01gradoidopcion=0 AND TB.core01idestado IN (0,2,3,7)) AND ';
			} else {
				$sSQLadd1 = $sSQLadd1 . 'TB.core01gradoidopcion=' . $bOpcGrado . ' AND ';
				if ($bOpcGrado == 5){
					$bRevisaPostAsociado = true;
				}
				if ($bOpcGrado == 16){
					$bRevisaPostAsociado = true;
				}
			}
		}
		if ($bRevisaPostAsociado) {
			if ($bPostGrado != ''){
				$sSQLadd1 = $sSQLadd1 . 'TB.core01idgradopostgrado=' . $bPostGrado . ' AND ';
			} else {
			$bRevisaPostAsociado = false;
			}
		}
		$bRevisaHomol = false;
		if ($bTipoHomol != '') {
			$bRevisaHomol = true;
		} else {
			if ($bSituacion != '') {
				$bRevisaHomol = true;
			}
		}
		if ($bRevisaHomol){
			$sIds66 = '-99';
			$sCondi71 = 'core71idclasehomol=' . $bSituacion . '';
			if ($bTipoHomol != '') {
				$sCondi71 = 'core71idtipohomol=' . $bTipoHomol . '';
			}
			if ($idEscuela != '') {
				$sCondi71 = $sCondi71 . ' AND core71idescuela=' . $idEscuela . '';
			}
			$sSQL = 'SELECT core71idestprog 
			FROM core71homolsolicitud 
			WHERE ' . $sCondi71 . ' 
			GROUP BY core71idestprog';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Filtro de homologaciones: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds66 = $sIds66 . ',' . $fila['core71idestprog'];
			}
			$sSQLadd1 = $sSQLadd1 . 'TB.core01id IN (' . $sIds66 . ') AND ';
		}
		if ($bFactorDeserta != '') {
			if ($bFactorDeserta == 'PENDIENTE'){
				$iFactorFinal = 0;
			} else {
				$iFactorFinal = $bFactorDeserta;
			}
			$sSQLadd1 = $sSQLadd1 . '(TB.core01factordeserta=' . $iFactorFinal . ' AND TB.core01idestado IN (3,9)) AND ';
		}
	// -- TERMINA EL ANALISIS DE FILTROS --
	}
	//TB.core01gradoestado<16  Cuando el estado grado pasa a 16 el estudiante pasa a condicion de graduado.
	$sTitulos = 'Tipo Doc,Documento,Estudiante, Programa,CEAD,Estado';
	$registros = 0;
	$bGigante = true;
	$sLimite = '';
	$sTablas = 'core01estprograma AS TB, core09programa AS T2, unad11terceros AS T1, unad24sede AS T5, core02estadoprograma AS T14' . $sTablaConvenio;
	$sRelaciones = $sCondiEstado . 'TB.core01idprograma=T2.core09id AND TB.core01idtercero=T1.unad11id AND TB.core011idcead=T5.unad24id  AND TB.core01idestado=T14.core02id';
	if ($bGigante) {
		$sSQL = 'SELECT COUNT(1) AS Total 
		FROM  ' . $sTablas . '
		WHERE ' . $sSQLadd1 . ' ' . $sRelaciones . ' ' . $sSQLadd . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2202 Tamano tabla: ' . $sSQL . '<br>';
		}
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
		}
	}

	$sSQL = 'SELECT T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, T1.unad11razonsocial AS C1_nombre, T2.core09nombre, 
	T5.unad24nombre, T14.core02nombre, TB.core01id, TB.core01idprograma, TB.core01idplandeestudios, TB.core01peracainicial, 
	TB.core01contestado, TB.core01avanceplanest, TB.core011idcead, TB.core01idestado, T2.core09codigo 
	FROM ' . $sTablas . ' 
	WHERE ' . $sSQLadd1 . ' ' . $sRelaciones . ' ' . $sSQLadd . '
	ORDER BY T2.core09nombre, T5.unad24nombre, T14.core02nombre, TB.core01peracainicial DESC, T1.unad11doc ';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2202" name="consulta_2202" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_2202" name="titulos_2202" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2202: ' . $sSQL . $sLimite . '<br>';
	}
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			//if ($registros==0){
			//return array($sErrConsulta.$sBotones, $sDebug);
			//}
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
				$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
			}
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td colspan="2"><b>' . $ETI['core01idtercero'] . '</b></td>
	<td><b>' . $ETI['core01idplandeestudios'] . '</b></td>
	<td><b>' . $ETI['core01idestado'] . '</b></td>
	<td><b>' . $ETI['msg_inicia'] . '</b></td>
	<td align="center"><b>' . $ETI['msg_continuidad'] . '</b></td>
	<td align="center"><b>' . $ETI['msg_avance'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf2202', $registros, $lineastabla, $pagina, 'paginarf2202()') . '
	' . html_lpp('lppf2202', $lineastabla, 'paginarf2202()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	$idPrograma = -99;
	$idCead = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idPrograma != $filadet['core01idprograma']) {
			$idPrograma = $filadet['core01idprograma'];
			$idCead = -99;
			$res = $res . '<tr class="fondoazul">
			<td colspan="8">' . $ETI['core01idprograma'] . ': <b>' . $filadet['core09codigo'] . ' - ' . cadena_notildes($filadet['core09nombre']) . '</b></td>
			</tr>';
		}
		if ($idCead != $filadet['core011idcead']) {
			$idCead = $filadet['core011idcead'];
			$res = $res . '<tr class="fondoazul">
			<td colspan="8">' . $ETI['core011idcead'] . ': <b>' . cadena_notildes($filadet['unad24nombre']) . '</b></td>
			</tr>';
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = '';
		$sLink = '';
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2202(' . $filadet['core01id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		if (isset($aPlanEst[$filadet['core01idplandeestudios']]['nombre']) == 0) {
			$sPrefPlan = '';
			$sSufPlan = '';
			$sNomPlan = '[' . $filadet['core01idplandeestudios'] . ']';
			$sSQL = 'SELECT core10id, core10consec, core10numregcalificado, core10fechaversion, core10fechavence, core10estado 
			FROM core10programaversion 
			WHERE core10id=' . $filadet['core01idplandeestudios'] . '';
			$tabla10 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla10) > 0) {
				$fila10 = $objDB->sf($tabla10);
				$sPrefPlan = '';
				$sSufPlan = '';
				if ($fila10['core10fechavence'] < $iHoy) {
					$sPrefPlan = '<span class="rojo">';
					$sSufPlan = '</span>';
				} else {
					if ($fila10['core10fechaversion'] <= $iHoy) {
						$sPrefPlan = '<span class="verde">';
						$sSufPlan = '</span>';
					}
				}
				$sNomPlan = $fila10['core10consec'] . ' - N&deg; Res ' . $fila10['core10numregcalificado'];
			}
			$aPlanEst[$filadet['core01idplandeestudios']]['ini'] = $sPrefPlan;
			$aPlanEst[$filadet['core01idplandeestudios']]['nombre'] = $sNomPlan;
		}
		switch ($filadet['core01idestado']) {
			case 7: //Graduando
			case 10: //Grados
				$sPrefPlan = '<span class="verde">';
				$et_Matriculas = '';
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
				break;
			default:
				$sPrefPlan = $aPlanEst[$filadet['core01idplandeestudios']]['ini'];
				$et_Matriculas = $aContinuidad[$filadet['core01contestado']];
				break;
		}
		$sSufPlan = '</span>';
		$et_idplan = $sPrefPlan . $aPlanEst[$filadet['core01idplandeestudios']]['nombre'] . $sSufPlan;
		$et_PeracaIni = $filadet['core01peracainicial'];
		$et_avance = formato_numero($filadet['core01avanceplanest'], 2) . ' %';
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['C1_td'] . ' ' . $filadet['C1_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C1_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_idplan . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_PeracaIni . $sSufijo . '</td>
		<td align="center">' . $sPrefijo . $et_Matriculas . $sSufijo . '</td>
		<td align="center">' . $sPrefijo . $et_avance . $sSufijo . '</td>
		<td align="right">' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f2202_HtmlTabla($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla) = f2202_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2202detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2202_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['core01idtercero_td'] = $APP->tipo_doc;
	$DATA['core01idtercero_doc'] = '';
	$DATA['core01idrevision_td'] = $APP->tipo_doc;
	$DATA['core01idrevision_doc'] = '';
	$DATA['core01gradoidrevisor_td'] = $APP->tipo_doc;
	$DATA['core01gradoidrevisor_doc'] = '';
	$DATA['core01gradodirector_td'] = $APP->tipo_doc;
	$DATA['core01gradodirector_doc'] = '';
	$DATA['core01gradojurado1_td'] = $APP->tipo_doc;
	$DATA['core01gradojurado1_doc'] = '';
	$DATA['core01gradojurado2_td'] = $APP->tipo_doc;
	$DATA['core01gradojurado2_doc'] = '';
	$DATA['core01gradojurado3_td'] = $APP->tipo_doc;
	$DATA['core01gradojurado3_doc'] = '';
	$DATA['core01gradoraiidaprueba_td'] = $APP->tipo_doc;
	$DATA['core01gradoraiidaprueba_doc'] = '';
	$DATA['core01idimporta_td'] = $APP->tipo_doc;
	$DATA['core01idimporta_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'core01idtercero="' . $DATA['core01idtercero'] . '" AND core01idprograma=' . $DATA['core01idprograma'] . '';
	} else {
		$sSQLcondi = 'core01id=' . $DATA['core01id'] . '';
	}
	$sSQL = 'SELECT * FROM core01estprograma WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['core01idtercero'] = $fila['core01idtercero'];
		$DATA['core01idprograma'] = $fila['core01idprograma'];
		$DATA['core01idplandeestudios'] = $fila['core01idplandeestudios'];
		$DATA['core01id'] = $fila['core01id'];
		$DATA['core01idescuela'] = $fila['core01idescuela'];
		$DATA['core01idzona'] = $fila['core01idzona'];
		$DATA['core011idcead'] = $fila['core011idcead'];
		$DATA['core01fechainicio'] = $fila['core01fechainicio'];
		$DATA['core01peracainicial'] = $fila['core01peracainicial'];
		$DATA['core01fechaultmatricula'] = $fila['core01fechaultmatricula'];
		$DATA['core01numcredbasicos'] = $fila['core01numcredbasicos'];
		$DATA['core01numcredespecificos'] = $fila['core01numcredespecificos'];
		$DATA['core01numcredelecgenerales'] = $fila['core01numcredelecgenerales'];
		$DATA['core01numcredelecescuela'] = $fila['core01numcredelecescuela'];
		$DATA['core01numcredelecprograma'] = $fila['core01numcredelecprograma'];
		$DATA['core01numcredeleccomplem'] = $fila['core01numcredeleccomplem'];
		$DATA['core01numcredelectivos'] = $fila['core01numcredelectivos'];
		$DATA['core01idestado'] = $fila['core01idestado'];
		$DATA['core01numcredbasicosaprob'] = $fila['core01numcredbasicosaprob'];
		$DATA['core01numcredespecificosaprob'] = $fila['core01numcredespecificosaprob'];
		$DATA['core01numcredelecgeneralesaprob'] = $fila['core01numcredelecgeneralesaprob'];
		$DATA['core01numcredelecescuelaaprob'] = $fila['core01numcredelecescuelaaprob'];
		$DATA['core01numcredelecprogramaaaprob'] = $fila['core01numcredelecprogramaaaprob'];
		$DATA['core01numcredeleccomplemaprob'] = $fila['core01numcredeleccomplemaprob'];
		$DATA['core01numcredelectivosaprob'] = $fila['core01numcredelectivosaprob'];
		$DATA['core01notaminima'] = $fila['core01notaminima'];
		$DATA['core01notamaxima'] = $fila['core01notamaxima'];
		$DATA['core01fechafinaliza'] = $fila['core01fechafinaliza'];
		$DATA['core01peracafinal'] = $fila['core01peracafinal'];
		$DATA['core01avanceplanest'] = $fila['core01avanceplanest'];
		$DATA['core01idrevision'] = $fila['core01idrevision'];
		$DATA['core01fecharevision'] = $fila['core01fecharevision'];
		$DATA['core01gradoestado'] = $fila['core01gradoestado'];
		$DATA['core01gradoidopcion'] = $fila['core01gradoidopcion'];
		$DATA['core01gradofechainscripcion'] = $fila['core01gradofechainscripcion'];
		$DATA['core01gradoidorigenprop'] = $fila['core01gradoidorigenprop'];
		$DATA['core01gradoidarchivoprop'] = $fila['core01gradoidarchivoprop'];
		$DATA['core01gradoidrevisor'] = $fila['core01gradoidrevisor'];
		$DATA['core01gradodirector'] = $fila['core01gradodirector'];
		$DATA['core01gradojurado1'] = $fila['core01gradojurado1'];
		$DATA['core01gradojurado2'] = $fila['core01gradojurado2'];
		$DATA['core01gradojurado3'] = $fila['core01gradojurado3'];
		$DATA['core01gradoidorigenacta'] = $fila['core01gradoidorigenacta'];
		$DATA['core01gradoidarchivoacta'] = $fila['core01gradoidarchivoacta'];
		$DATA['core01gradonotadocumento'] = $fila['core01gradonotadocumento'];
		$DATA['core01gradonotasustenta'] = $fila['core01gradonotasustenta'];
		$DATA['core01gradonotaproyecto'] = $fila['core01gradonotaproyecto'];
		$DATA['core01gradonotafinal'] = $fila['core01gradonotafinal'];
		$DATA['core01gradofecha'] = $fila['core01gradofecha'];
		$DATA['core01gradonumacta'] = $fila['core01gradonumacta'];
		$DATA['core01gradonumlibroactas'] = $fila['core01gradonumlibroactas'];
		$DATA['core01gradonumfolio'] = $fila['core01gradonumfolio'];
		$DATA['core01gradonumdiploma'] = $fila['core01gradonumdiploma'];
		$DATA['core01gradonumlibrodiplomas'] = $fila['core01gradonumlibrodiplomas'];
		$DATA['core01gradocodigoverifica'] = $fila['core01gradocodigoverifica'];
		$DATA['core01gradourldocfinal'] = $fila['core01gradourldocfinal'];
		$DATA['core01gradoraiidaprueba'] = $fila['core01gradoraiidaprueba'];
		$DATA['core01gradoraifechaaprueba'] = $fila['core01gradoraifechaaprueba'];
		$DATA['core01gradoidcohorte'] = $fila['core01gradoidcohorte'];
		$DATA['core01origenregistro'] = $fila['core01origenregistro'];
		$DATA['core01fechaimportacion'] = $fila['core01fechaimportacion'];
		$DATA['core01idimporta'] = $fila['core01idimporta'];
		$DATA['core01gradotitulodiploma'] = $fila['core01gradotitulodiploma'];
		$DATA['core01gradotituloopcion'] = $fila['core01gradotituloopcion'];
		$DATA['core01contmatriculas'] = $fila['core01contmatriculas'];
		$DATA['core01contciclo1'] = $fila['core01contciclo1'];
		$DATA['core01contciclo2'] = $fila['core01contciclo2'];
		$DATA['core01contciclo3'] = $fila['core01contciclo3'];
		$DATA['core01contciclo4'] = $fila['core01contciclo4'];
		$DATA['core01contciclo5'] = $fila['core01contciclo5'];
		$DATA['core01contciclo6'] = $fila['core01contciclo6'];
		$DATA['core01contciclo7'] = $fila['core01contciclo7'];
		$DATA['core01contciclo8'] = $fila['core01contciclo8'];
		$DATA['core01contciclo9'] = $fila['core01contciclo9'];
		$DATA['core01contciclo10'] = $fila['core01contciclo10'];
		$DATA['core01contciclo11'] = $fila['core01contciclo11'];
		$DATA['core01contciclo12'] = $fila['core01contciclo12'];
		$DATA['core01contciclo13'] = $fila['core01contciclo13'];
		$DATA['core01contciclo14'] = $fila['core01contciclo14'];
		$DATA['core01contciclo15'] = $fila['core01contciclo15'];
		$DATA['core01contciclo16'] = $fila['core01contciclo16'];
		$DATA['core01contciclo17'] = $fila['core01contciclo17'];
		$DATA['core01contciclo18'] = $fila['core01contciclo18'];
		$DATA['core01contciclo19'] = $fila['core01contciclo19'];
		$DATA['core01contciclo20'] = $fila['core01contciclo20'];
		$DATA['core01contestado'] = $fila['core01contestado'];
		$DATA['core01estadopractica'] = $fila['core01estadopractica'];
		$DATA['core01idtipopractica'] = $fila['core01idtipopractica'];
		$DATA['core01idlineaprof'] = $fila['core01idlineaprof'];
		$DATA['core01idcampagna'] = $fila['core01idcampagna'];
		$DATA['core01idasesor'] = $fila['core01idasesor'];
		$DATA['core01idconvenioingresa'] = $fila['core01idconvenioingresa'];
		$DATA['core01fechainscripcion'] = $fila['core01fechainscripcion'];
		$DATA['core01fechaadmision'] = $fila['core01fechaadmision'];
		$DATA['core01condicion'] = $fila['core01condicion'];
		$DATA['core01idconsejero'] = $fila['core01idconsejero'];
		$DATA['core01cantmatriculas'] = $fila['core01cantmatriculas'];
		$DATA['core01cantmatprevias'] = $fila['core01cantmatprevias'];
		$DATA['cara01tieneacompanamento'] = $fila['cara01tieneacompanamento'];
		$DATA['core01factordeserta'] = $fila['core01factordeserta'];
		$DATA['core01oportuno'] = $fila['core01oportuno'];
		$DATA['core01contaprob1'] = $fila['core01contaprob1'];
		$DATA['core01contaprob2'] = $fila['core01contaprob2'];
		$DATA['core01contaprob3'] = $fila['core01contaprob3'];
		$DATA['core01contaprob4'] = $fila['core01contaprob4'];
		$DATA['core01contaprob5'] = $fila['core01contaprob5'];
		$DATA['core01contaprob6'] = $fila['core01contaprob6'];
		$DATA['core01contaprob7'] = $fila['core01contaprob7'];
		$DATA['core01contaprob8'] = $fila['core01contaprob8'];
		$DATA['core01contaprob9'] = $fila['core01contaprob9'];
		$DATA['core01contaprob10'] = $fila['core01contaprob10'];
		$DATA['core01contaprob11'] = $fila['core01contaprob11'];
		$DATA['core01contaprob12'] = $fila['core01contaprob12'];
		$DATA['core01contaprob13'] = $fila['core01contaprob13'];
		$DATA['core01contaprob14'] = $fila['core01contaprob14'];
		$DATA['core01contaprob15'] = $fila['core01contaprob15'];
		$DATA['core01contaprob16'] = $fila['core01contaprob16'];
		$DATA['core01contaprob17'] = $fila['core01contaprob17'];
		$DATA['core01contaprob18'] = $fila['core01contaprob18'];
		$DATA['core01contaprob19'] = $fila['core01contaprob19'];
		$DATA['core01contaprob20'] = $fila['core01contaprob20'];
		$DATA['core01procesar'] = $fila['core01procesar'];
		$DATA['core01semestrerelativo '] = $fila['core01semestrerelativo '];
		$DATA['core01ciclobase'] = $fila['core01ciclobase'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2202'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2202_db_GuardarV2($DATA, $objDB, $bDebug = false)
{
	$iCodModulo = 2202;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2202)) {
		$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2202;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['core01idtercero'])==0){$DATA['core01idtercero']='';}
	if (isset($DATA['core01idprograma'])==0){$DATA['core01idprograma']='';}
	if (isset($DATA['core01idplandeestudios'])==0){$DATA['core01idplandeestudios']='';}
	if (isset($DATA['core01id'])==0){$DATA['core01id']='';}
	if (isset($DATA['core01idescuela'])==0){$DATA['core01idescuela']='';}
	if (isset($DATA['core01idzona'])==0){$DATA['core01idzona']='';}
	if (isset($DATA['core011idcead'])==0){$DATA['core011idcead']='';}
	if (isset($DATA['core01fechainicio'])==0){$DATA['core01fechainicio']='';}
	if (isset($DATA['core01peracainicial'])==0){$DATA['core01peracainicial']='';}
	if (isset($DATA['core01fechaultmatricula'])==0){$DATA['core01fechaultmatricula']='';}
	if (isset($DATA['core01numcredbasicos'])==0){$DATA['core01numcredbasicos']='';}
	if (isset($DATA['core01numcredespecificos'])==0){$DATA['core01numcredespecificos']='';}
	if (isset($DATA['core01numcredelecgenerales'])==0){$DATA['core01numcredelecgenerales']='';}
	if (isset($DATA['core01numcredelecescuela'])==0){$DATA['core01numcredelecescuela']='';}
	if (isset($DATA['core01numcredelecprograma'])==0){$DATA['core01numcredelecprograma']='';}
	if (isset($DATA['core01numcredeleccomplem'])==0){$DATA['core01numcredeleccomplem']='';}
	if (isset($DATA['core01numcredelectivos'])==0){$DATA['core01numcredelectivos']='';}
	if (isset($DATA['core01idestado'])==0){$DATA['core01idestado']='';}
	if (isset($DATA['core01numcredbasicosaprob'])==0){$DATA['core01numcredbasicosaprob']='';}
	if (isset($DATA['core01numcredespecificosaprob'])==0){$DATA['core01numcredespecificosaprob']='';}
	if (isset($DATA['core01numcredelecgeneralesaprob'])==0){$DATA['core01numcredelecgeneralesaprob']='';}
	if (isset($DATA['core01numcredelecescuelaaprob'])==0){$DATA['core01numcredelecescuelaaprob']='';}
	if (isset($DATA['core01numcredelecprogramaaaprob'])==0){$DATA['core01numcredelecprogramaaaprob']='';}
	if (isset($DATA['core01numcredeleccomplemaprob'])==0){$DATA['core01numcredeleccomplemaprob']='';}
	if (isset($DATA['core01numcredelectivosaprob'])==0){$DATA['core01numcredelectivosaprob']='';}
	if (isset($DATA['core01fechafinaliza'])==0){$DATA['core01fechafinaliza']='';}
	if (isset($DATA['core01idrevision'])==0){$DATA['core01idrevision']='';}
	if (isset($DATA['core01fecharevision'])==0){$DATA['core01fecharevision']='';}
	if (isset($DATA['core01gradoidopcion'])==0){$DATA['core01gradoidopcion']='';}
	if (isset($DATA['core01gradofechainscripcion'])==0){$DATA['core01gradofechainscripcion']='';}
	if (isset($DATA['core01gradoidrevisor'])==0){$DATA['core01gradoidrevisor']='';}
	if (isset($DATA['core01gradodirector'])==0){$DATA['core01gradodirector']='';}
	if (isset($DATA['core01gradojurado1'])==0){$DATA['core01gradojurado1']='';}
	if (isset($DATA['core01gradojurado2'])==0){$DATA['core01gradojurado2']='';}
	if (isset($DATA['core01gradojurado3'])==0){$DATA['core01gradojurado3']='';}
	if (isset($DATA['core01gradonotadocumento'])==0){$DATA['core01gradonotadocumento']='';}
	if (isset($DATA['core01gradonotasustenta'])==0){$DATA['core01gradonotasustenta']='';}
	if (isset($DATA['core01gradonotaproyecto'])==0){$DATA['core01gradonotaproyecto']='';}
	if (isset($DATA['core01gradonotafinal'])==0){$DATA['core01gradonotafinal']='';}
	if (isset($DATA['core01gradofecha'])==0){$DATA['core01gradofecha']='';}
	if (isset($DATA['core01gradonumacta'])==0){$DATA['core01gradonumacta']='';}
	if (isset($DATA['core01gradonumlibroactas'])==0){$DATA['core01gradonumlibroactas']='';}
	if (isset($DATA['core01gradonumfolio'])==0){$DATA['core01gradonumfolio']='';}
	if (isset($DATA['core01gradonumdiploma'])==0){$DATA['core01gradonumdiploma']='';}
	if (isset($DATA['core01gradonumlibrodiplomas'])==0){$DATA['core01gradonumlibrodiplomas']='';}
	if (isset($DATA['core01gradourldocfinal'])==0){$DATA['core01gradourldocfinal']='';}
	if (isset($DATA['core01gradoraiidaprueba'])==0){$DATA['core01gradoraiidaprueba']='';}
	if (isset($DATA['core01gradoraifechaaprueba'])==0){$DATA['core01gradoraifechaaprueba']='';}
	if (isset($DATA['core01fechaimportacion'])==0){$DATA['core01fechaimportacion']='';}
	if (isset($DATA['core01gradotitulodiploma'])==0){$DATA['core01gradotitulodiploma']='';}
	if (isset($DATA['core01gradotituloopcion'])==0){$DATA['core01gradotituloopcion']='';}
	if (isset($DATA['core01ciclobase'])==0){$DATA['core01ciclobase']='';}
	*/
	if (isset($DATA['core01idlineaprof2']) == 0) {
		$DATA['core01idlineaprof2'] = '';
	}

	$DATA['core01idprograma'] = numeros_validar($DATA['core01idprograma']);
	$DATA['core01idplandeestudios'] = numeros_validar($DATA['core01idplandeestudios']);
	$DATA['core01idescuela'] = numeros_validar($DATA['core01idescuela']);
	$DATA['core01idzona'] = numeros_validar($DATA['core01idzona']);
	$DATA['core011idcead'] = numeros_validar($DATA['core011idcead']);
	$DATA['core01peracainicial'] = numeros_validar($DATA['core01peracainicial']);
	$DATA['core01fechaultmatricula'] = numeros_validar($DATA['core01fechaultmatricula']);
	$DATA['core01numcredbasicos'] = numeros_validar($DATA['core01numcredbasicos']);
	$DATA['core01numcredespecificos'] = numeros_validar($DATA['core01numcredespecificos']);
	$DATA['core01numcredelecgenerales'] = numeros_validar($DATA['core01numcredelecgenerales']);
	$DATA['core01numcredelecescuela'] = numeros_validar($DATA['core01numcredelecescuela']);
	$DATA['core01numcredelecprograma'] = numeros_validar($DATA['core01numcredelecprograma']);
	$DATA['core01numcredeleccomplem'] = numeros_validar($DATA['core01numcredeleccomplem']);
	$DATA['core01numcredelectivos'] = numeros_validar($DATA['core01numcredelectivos']);
	$DATA['core01idestado'] = numeros_validar($DATA['core01idestado']);
	$DATA['core01idlineaprof'] = numeros_validar($DATA['core01idlineaprof']);
	$DATA['core01idlineaprof2'] = numeros_validar($DATA['core01idlineaprof2']);
	$DATA['core01ciclobase'] = numeros_validar($DATA['core01ciclobase']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['core01idescuela']==''){$DATA['core01idescuela']=0;}
	//if ($DATA['core01idzona']==''){$DATA['core01idzona']=0;}
	//if ($DATA['core011idcead']==''){$DATA['core011idcead']=0;}
	//if ($DATA['core01peracainicial']==''){$DATA['core01peracainicial']=0;}
	if ($DATA['core01fechaultmatricula'] == '') {
		$DATA['core01fechaultmatricula'] = 0;
	}
	if ($DATA['core01idplandeestudios'] == '') {
		$DATA['core01idplandeestudios'] = 0;
	}
	if ($DATA['core01numcredbasicos'] == '') {
		$DATA['core01numcredbasicos'] = 0;
	}
	if ($DATA['core01numcredespecificos'] == '') {
		$DATA['core01numcredespecificos'] = 0;
	}
	if ($DATA['core01numcredelecgenerales'] == '') {
		$DATA['core01numcredelecgenerales'] = 0;
	}
	if ($DATA['core01numcredelecescuela'] == '') {
		$DATA['core01numcredelecescuela'] = 0;
	}
	if ($DATA['core01numcredelecprograma'] == '') {
		$DATA['core01numcredelecprograma'] = 0;
	}
	if ($DATA['core01numcredeleccomplem'] == '') {
		$DATA['core01numcredeleccomplem'] = 0;
	}
	if ($DATA['core01numcredelectivos'] == '') {
		$DATA['core01numcredelectivos'] = 0;
	}
	//if ($DATA['core01idestado']==''){$DATA['core01idestado']=0;}
	if ($DATA['core01numcredbasicosaprob'] == '') {
		$DATA['core01numcredbasicosaprob'] = 0;
	}
	if ($DATA['core01numcredespecificosaprob'] == '') {
		$DATA['core01numcredespecificosaprob'] = 0;
	}
	if ($DATA['core01numcredelecgeneralesaprob'] == '') {
		$DATA['core01numcredelecgeneralesaprob'] = 0;
	}
	if ($DATA['core01numcredelecescuelaaprob'] == '') {
		$DATA['core01numcredelecescuelaaprob'] = 0;
	}
	if ($DATA['core01numcredelecprogramaaaprob'] == '') {
		$DATA['core01numcredelecprogramaaaprob'] = 0;
	}
	if ($DATA['core01numcredeleccomplemaprob'] == '') {
		$DATA['core01numcredeleccomplemaprob'] = 0;
	}
	if ($DATA['core01numcredelectivosaprob'] == '') {
		$DATA['core01numcredelectivosaprob'] = 0;
	}
	if ($DATA['core01peracafinal'] == '') {
		$DATA['core01peracafinal'] = 0;
	}
	if ($DATA['core01avanceplanest'] == '') {
		$DATA['core01avanceplanest'] = 0;
	}
	if ($DATA['core01gradoestado'] == '') {
		$DATA['core01gradoestado'] = 0;
	}
	if ($DATA['core01gradoidopcion'] == '') {
		$DATA['core01gradoidopcion'] = 0;
	}
	if ($DATA['core01idlineaprof'] == '') {
		$DATA['core01idlineaprof'] = -1;
	}
	if ($DATA['core01idlineaprof2'] == '') {
		$DATA['core01idlineaprof2'] = -1;
	}
	if ($DATA['core01ciclobase'] == '') {
		$DATA['core01ciclobase'] = 0;
	}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		if ($DATA['core01fechafinaliza'] == 0) {
			//$DATA['core01fechafinaliza']=fecha_DiaMod();
			//$sError=$ERR['core01fechafinaliza'].$sSepara.$sError;
		}
		if ($DATA['core01idestado'] == '') {
			$sError = $ERR['core01idestado'] . $sSepara . $sError;
		}
		if ($DATA['core01idplandeestudios'] == '') {
			$sError = $ERR['core01idplandeestudios'] . $sSepara . $sError;
		}
		//if ($DATA['core01fechaultmatricula']==''){$sError=$ERR['core01fechaultmatricula'].$sSepara.$sError;}
		if ($DATA['core01peracainicial'] == '') {
			$sError = $ERR['core01peracainicial'] . $sSepara . $sError;
		}
		if ($DATA['core01fechainicio'] == 0) {
			//$DATA['core01fechainicio']=fecha_DiaMod();
			$sError = $ERR['core01fechainicio'] . $sSepara . $sError;
		}
		if ($DATA['core011idcead'] == '') {
			$sError = $ERR['core011idcead'] . $sSepara . $sError;
		}
		if ($DATA['core01idzona'] == '') {
			$sError = $ERR['core01idzona'] . $sSepara . $sError;
		}
		if ($DATA['core01idescuela'] == '') {
			$sError = $ERR['core01idescuela'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['core01idplandeestudios'] == '') {
		$sError = $ERR['core01idplandeestudios'];
	}
	if ($DATA['core01idprograma'] == '') {
		$sError = $ERR['core01idprograma'];
	}
	if ($DATA['core01idtercero'] == 0) {
		$sError = $ERR['core01idtercero'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['core01idtercero_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['core01idtercero_td'], $DATA['core01idtercero_doc'], $objDB, 'El tercero Tercero ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['core01idtercero'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT 1 FROM core01estprograma WHERE core01idtercero="' . $DATA['core01idtercero'] . '" AND core01idprograma=' . $DATA['core01idprograma'] . ' AND core01idplandeestudios=' . $DATA['core01idplandeestudios'] . '';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$sError = $ERR['existe'];
			} else {
				if (!seg_revisa_permiso($iCodModulo, 2, $objDB)) {
					$sError = $ERR['2'];
				}
			}
		} else {
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['core01id'] = tabla_consecutivo('core01estprograma', 'core01id', '', $objDB);
			if ($DATA['core01id'] == -1) {
				$sError = $objDB->serror;
			}
		}
	}
	if ($sError == '') {
		$bpasa = false;
		if ($DATA['paso'] == 10) {
			//$core01fechainicio=fecha_DiaMod();
			$DATA['core01numcredbasicosaprob'] = 0;
			$DATA['core01numcredespecificosaprob'] = 0;
			$DATA['core01numcredelecgeneralesaprob'] = 0;
			$DATA['core01numcredelecescuelaaprob'] = 0;
			$DATA['core01numcredelecprogramaaaprob'] = 0;
			$DATA['core01numcredeleccomplemaprob'] = 0;
			$DATA['core01numcredelectivosaprob'] = 0;
			//$core01fechafinaliza=0;
			$DATA['core01fechafinaliza'] = 0;
			$DATA['core01peracafinal'] = 0;
			$DATA['core01avanceplanest'] = 0;
			$core01fecharevision = 0;
			$DATA['core01gradoestado'] = 0;
			$DATA['core01gradoidopcion'] = 0;
			$DATA['core01fecharevision'] = 0;
			$sCampos2202 = 'core01idtercero, core01idprograma, core01id, core01idescuela, core01idzona, 
			core011idcead, core01fechainicio, core01peracainicial, core01fechaultmatricula, core01idplandeestudios, 
			core01numcredbasicos, core01numcredespecificos, core01numcredelecgenerales, core01numcredelecescuela, core01numcredelecprograma, 
			core01numcredeleccomplem, core01numcredelectivos, core01idestado, core01numcredbasicosaprob, core01numcredespecificosaprob, 
			core01numcredelecgeneralesaprob, core01numcredelecescuelaaprob, core01numcredelecprogramaaaprob, core01numcredeleccomplemaprob, core01numcredelectivosaprob, 
			core01fechafinaliza, core01peracafinal, core01avanceplanest, core01idrevision, core01fecharevision, 
			core01gradoestado, core01gradoidopcion, core01idlineaprof, core01idlineaprof2';
			$sValores2202 = '' . $DATA['core01idtercero'] . ', ' . $DATA['core01idprograma'] . ', ' . $DATA['core01id'] . ', ' . $DATA['core01idescuela'] . ', ' . $DATA['core01idzona'] . ', 
			' . $DATA['core011idcead'] . ', "' . $DATA['core01fechainicio'] . '", ' . $DATA['core01peracainicial'] . ', ' . $DATA['core01fechaultmatricula'] . ', ' . $DATA['core01idplandeestudios'] . ', 
			' . $DATA['core01numcredbasicos'] . ', ' . $DATA['core01numcredespecificos'] . ', ' . $DATA['core01numcredelecgenerales'] . ', ' . $DATA['core01numcredelecescuela'] . ', ' . $DATA['core01numcredelecprograma'] . ', 
			' . $DATA['core01numcredeleccomplem'] . ', ' . $DATA['core01numcredelectivos'] . ', ' . $DATA['core01idestado'] . ', ' . $DATA['core01numcredbasicosaprob'] . ', ' . $DATA['core01numcredespecificosaprob'] . ', 
			' . $DATA['core01numcredelecgeneralesaprob'] . ', ' . $DATA['core01numcredelecescuelaaprob'] . ', ' . $DATA['core01numcredelecprogramaaaprob'] . ', ' . $DATA['core01numcredeleccomplemaprob'] . ', ' . $DATA['core01numcredelectivosaprob'] . ', 
			"' . $DATA['core01fechafinaliza'] . '", ' . $DATA['core01peracafinal'] . ', ' . $DATA['core01avanceplanest'] . ', ' . $DATA['core01idrevision'] . ', "' . $DATA['core01fecharevision'] . '", 
			' . $DATA['core01gradoestado'] . ', ' . $DATA['core01gradoidopcion'] . ', ' . $DATA['core01idlineaprof'] . ', ' . $DATA['core01idlineaprof2'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO core01estprograma (' . $sCampos2202 . ') VALUES (' . utf8_encode($sValores2202) . ');';
				$sdetalle = $sCampos2202 . '[' . utf8_encode($sValores2202) . ']';
			} else {
				$sSQL = 'INSERT INTO core01estprograma (' . $sCampos2202 . ') VALUES (' . $sValores2202 . ');';
				$sdetalle = $sCampos2202 . '[' . $sValores2202 . ']';
			}
			$idAccion = 2;
			$bpasa = true;
		} else {
			$scampo[1] = 'core01idzona';
			$scampo[2] = 'core011idcead';
			$scampo[3] = 'core01fechainicio';
			$scampo[4] = 'core01peracainicial';
			$scampo[5] = 'core01fechaultmatricula';
			$scampo[6] = 'core01idplandeestudios';
			$scampo[7] = 'core01numcredbasicos';
			$scampo[8] = 'core01numcredespecificos';
			$scampo[9] = 'core01numcredelecgenerales';
			$scampo[10] = 'core01numcredelecescuela';
			$scampo[11] = 'core01numcredelecprograma';
			$scampo[12] = 'core01numcredeleccomplem';
			$scampo[13] = 'core01numcredelectivos';
			$scampo[14] = 'core01idestado';
			$scampo[15] = 'core01fechafinaliza';
			$scampo[16] = 'core01idrevision';
			$scampo[17] = 'core01fecharevision';
			$scampo[18] = 'core01idescuela';
			$scampo[19] = 'core01idprograma';
			$scampo[20] = 'core01idlineaprof';
			$scampo[21] = 'core01ciclobase';
			$scampo[22] = 'core01idlineaprof2';
			$sdato[1] = $DATA['core01idzona'];
			$sdato[2] = $DATA['core011idcead'];
			$sdato[3] = $DATA['core01fechainicio'];
			$sdato[4] = $DATA['core01peracainicial'];
			$sdato[5] = $DATA['core01fechaultmatricula'];
			$sdato[6] = $DATA['core01idplandeestudios'];
			$sdato[7] = $DATA['core01numcredbasicos'];
			$sdato[8] = $DATA['core01numcredespecificos'];
			$sdato[9] = $DATA['core01numcredelecgenerales'];
			$sdato[10] = $DATA['core01numcredelecescuela'];
			$sdato[11] = $DATA['core01numcredelecprograma'];
			$sdato[12] = $DATA['core01numcredeleccomplem'];
			$sdato[13] = $DATA['core01numcredelectivos'];
			$sdato[14] = $DATA['core01idestado'];
			$sdato[15] = $DATA['core01fechafinaliza'];
			$sdato[16] = $DATA['core01idrevision'];
			$sdato[17] = $DATA['core01fecharevision'];
			$sdato[18] = $DATA['core01idescuela'];
			$sdato[19] = $DATA['core01idprograma'];
			$sdato[20] = $DATA['core01idlineaprof'];
			$sdato[21] = $DATA['core01ciclobase'];
			$sdato[22] = $DATA['core01idlineaprof2'];
			$numcmod = 22;
			$sWhere = 'core01id=' . $DATA['core01id'] . '';
			$sSQL = 'SELECT * FROM core01estprograma WHERE ' . $sWhere;
			$sdatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $numcmod; $k++) {
						if (isset($filabase[$scampo[$k]]) == 0) {
							$sDebug = $sDebug . fecha_microtiempo() . ' FALLA CODIGO: Falta el campo ' . $k . ' ' . $scampo[$k] . '<br>';
						}
					}
					$bPrimera = false;
				}
				$bsepara = false;
				for ($k = 1; $k <= $numcmod; $k++) {
					if ($filabase[$scampo[$k]] != $sdato[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
						$bpasa = true;
					}
				}
			}
			if ($bpasa) {
				if ($APP->utf8 == 1) {
					$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE core01estprograma SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE core01estprograma SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bpasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 2202 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2202] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['core01id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['core01id'], $sdetalle, $objDB);
				}
				$DATA['paso'] = 2;
			}
		} else {
			$DATA['paso'] = 2;
		}
	} else {
		if ($DATA['paso'] == 10) {
			$DATA['paso'] = 0;
		} else {
			$DATA['paso'] = 2;
		}
		if ($bQuitarCodigo) {
			if ($sCampoCodigo != '') {
				$DATA[$sCampoCodigo] = '';
			}
		}
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2202_db_Eliminar($core01id, $objDB, $bDebug = false)
{
	$iCodModulo = 2202;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2202)) {
		$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2202;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$core01id = numeros_validar($core01id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM core01estprograma WHERE core01id=' . $core01id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $core01id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT core03idestprograma FROM core03plandeestudios WHERE core03idestprograma=' . $filabase['core01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Plan de estudios creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2202';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['core01id'] . ' LIMIT 0, 1';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sError = $filaor['mensaje'];
				if ($filaor['etiqueta'] != '') {
					if (isset($ERR[$filaor['etiqueta']]) != 0) {
						$sError = $ERR[$filaor['etiqueta']];
					}
				}
				break;
			}
		}
	}
	if ($sError == '') {
		//$sSQL='DELETE FROM core03plandeestudios WHERE core03idestprograma='.$filabase['core01id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sSQL = 'DELETE FROM core22gradohistorialest WHERE core22idestprograma=' . $filabase['core01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sSQL = 'DELETE FROM core23gradohistorialactor WHERE core23idestprograma=' . $filabase['core01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'core01id=' . $core01id . '';
		//$sWhere='core01idprograma='.$filabase['core01idprograma'].' AND core01idtercero="'.$filabase['core01idtercero'].'"';
		$sSQL = 'DELETE FROM core01estprograma WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $core01id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f2202_TituloBusqueda()
{
	return 'Busqueda de Estudiantes';
}
function f2202_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2202)) {
		$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2202;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b2202nombre" name="b2202nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f2202_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b2202nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f2202_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2202)) {
		$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2202;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . '<input id="paginaf2202" name="paginaf2202" type="hidden" value="' . $pagina . '"/><input id="lppf2202" name="lppf2202" type="hidden" value="' . $lineastabla . '"/>', $sDebug);
		die();
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[103].'%" AND ';}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos = 'Tercero, Programa, Id, Escuela, Zona, 1idcead, Fechainicio, Peracainicial, Fechaultmatricula, Plandeestudios, Numcredbasicos, Numcredespecificos, Numcredelectivos, Estado, Numcredbasicosaprob, Numcredespecificosaprob, Numcredelectivosaprob, Notaminima, Notamaxima, Fechafinaliza, Peracafinal';
	$sSQL = 'SELECT T1.unad11razonsocial AS C1_nombre, T2.core09nombre, TB.core01id, T4.exte01nombre, T5.unad23nombre, T6.unad24nombre, TB.core01fechainicio, T8.exte02nombre, TB.core01fechaultmatricula, TB.core01idplandeestudios, TB.core01numcredbasicos, TB.core01numcredespecificos, TB.core01numcredelectivos, T14.core02nombre, TB.core01numcredbasicosaprob, TB.core01numcredespecificosaprob, TB.core01numcredelectivosaprob, TB.core01notaminima, TB.core01notamaxima, TB.core01fechafinaliza, TB.core01peracafinal, TB.core01idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, TB.core01idprograma, TB.core01idescuela, TB.core01idzona, TB.core011idcead, TB.core01peracainicial, TB.core01idestado 
	FROM core01estprograma AS TB, unad11terceros AS T1, core09programa AS T2, exte01escuela AS T4, unad23zona AS T5, unad24sede AS T6, exte02per_aca AS T8, core02estadoprograma AS T14 
	WHERE ' . $sSQLadd1 . ' TB.core01idtercero=T1.unad11id AND TB.core01idprograma=T2.core09id AND TB.core01idescuela=T4.exte01id AND TB.core01idzona=T5.unad23id AND TB.core011idcead=T6.unad24id AND TB.core01peracainicial=T8.exte02id AND TB.core01idestado=T14.core02id ' . $sSQLadd . '
	ORDER BY TB.core01idtercero, TB.core01idprograma';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		//if ($registros==0){
		//return array($sErrConsulta.$sBotones, $sDebug);
		//}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td colspan="2"><b>' . $ETI['core01idtercero'] . '</b></td>
	<td><b>' . $ETI['core01idprograma'] . '</b></td>
	<td><b>' . $ETI['core01idescuela'] . '</b></td>
	<td><b>' . $ETI['core01idzona'] . '</b></td>
	<td><b>' . $ETI['core011idcead'] . '</b></td>
	<td><b>' . $ETI['core01fechainicio'] . '</b></td>
	<td><b>' . $ETI['core01peracainicial'] . '</b></td>
	<td><b>' . $ETI['core01fechaultmatricula'] . '</b></td>
	<td><b>' . $ETI['core01idplandeestudios'] . '</b></td>
	<td><b>' . $ETI['core01numcredbasicos'] . '</b></td>
	<td><b>' . $ETI['core01numcredespecificos'] . '</b></td>
	<td><b>' . $ETI['core01numcredelectivos'] . '</b></td>
	<td><b>' . $ETI['core01idestado'] . '</b></td>
	<td><b>' . $ETI['core01numcredbasicosaprob'] . '</b></td>
	<td><b>' . $ETI['core01numcredespecificosaprob'] . '</b></td>
	<td><b>' . $ETI['core01numcredelectivosaprob'] . '</b></td>
	<td><b>' . $ETI['core01notaminima'] . '</b></td>
	<td><b>' . $ETI['core01notamaxima'] . '</b></td>
	<td><b>' . $ETI['core01fechafinaliza'] . '</b></td>
	<td><b>' . $ETI['core01peracafinal'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['core01id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_core01fechainicio = '';
		if ($filadet['core01fechainicio'] != 0) {
			$et_core01fechainicio = fecha_desdenumero($filadet['core01fechainicio']);
		}
		$et_core01fechafinaliza = '';
		if ($filadet['core01fechafinaliza'] != 0) {
			$et_core01fechafinaliza = fecha_desdenumero($filadet['core01fechafinaliza']);
		}
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . $filadet['C1_td'] . ' ' . $filadet['C1_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C1_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['exte01nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_core01fechainicio . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core01fechaultmatricula'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core01idplandeestudios'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core01numcredbasicos'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core01numcredespecificos'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core01numcredelectivos'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core01numcredbasicosaprob'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core01numcredespecificosaprob'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core01numcredelectivosaprob'] . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['core01notaminima']) . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['core01notamaxima']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_core01fechafinaliza . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core01peracafinal'] . $sSufijo . '</td>
		<td></td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f2202_AddExcepcion($core01id, $corf14idcurso, $objDB, $bDebug)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT TB.core01idplandeestudios, TB.core01idtercero, T11.unad11idtablero, TB.core01idprograma, TB.core01fechainicio
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $core01id . ' AND TB.core01idtercero=T11.unad11id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Exepciones: </b> Registro PEI ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idPrograma = $fila['core01idprograma'];
		$idPlan = $fila['core01idplandeestudios'];
		$idTercero = $fila['core01idtercero'];
		$idContenedor = $fila['unad11idtablero'];
		$iFechaInicial = $fila['core01fechainicio'];
	} else {
		$sError = 'Registro de estuidante NO encontrado';
	}
	if ($sError == '') {
		//Ahora alistar el curso
		$sSQL = 'SELECT TB.corf14tipocredito, TB.corf14nivel, T40.unad40numcreditos 
		FROM corf14cursoexcepcion AS TB, unad40curso AS T40 
		WHERE TB.corf14idplanest=' . $idPlan . ' AND TB.corf14idcurso=' . $corf14idcurso . ' AND TB.corf14idcurso=T40.unad40id';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Exepciones: </b>: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila14 = $objDB->sf($tabla);
		} else {
			$sError = 'No se ha encontrado la excepci&oacute;n solicitada.';
		}
	}
	if ($sError == '') {
		$core03formanota = 0;
		$core03estado = 0;
		$sSQL = 'SELECT core03id, core03idequivalente FROM core03plandeestudios_' . $idContenedor . ' WHERE core03idestprograma=' . $core01id . ' AND core03idcurso=' . $corf14idcurso . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			//Ahora si insertarlo en el PEI
			$core03id = tabla_consecutivo('core03plandeestudios_' . $idContenedor . '', 'core03id', '', $objDB);
			//, core03notahomologa, core03fechanotahomologa, core03idusuarionotahomo, core03detallehomologa, 
			$sCampos03 = 'INSERT INTO core03plandeestudios_' . $idContenedor . ' (core03idestprograma, core03idcurso, core03id, core03idtercero, core03idprograma, 
			core03itipocurso, core03obligatorio, core03homologable, core03habilitable, 
			core03porsuficiencia, core03idprerequisito, core03idprerequisito2, core03idprerequisito3, core03idcorequisito, 
			core03numcreditos, core03nivelcurso, core03peracaaprueba, core03nota75, core03fechanota75, 
			core03idusuarionota75, core03nota25, core03fechanota25, core03idusuarionota25, 
			core03idequivalencia, core03idmatricula, 
			core03fechainclusion, core03notafinal, core03formanota, core03estado, core03idequivalente, 
			core03idcursoreemp1, core03idcursoreemp2, core03tieneequivalente) VALUES ';
			$sValores03 = '(' . $core01id . ', ' . $corf14idcurso . ', ' . $core03id . ', ' . $idTercero . ', ' . $idPrograma . ', 
			' . $fila14['corf14tipocredito'] . ', 0, "N", "N", 
			"N", 0, 0, 0, 0, 
			' . $fila14['unad40numcreditos'] . ', ' . $fila14['corf14nivel'] . ', 0, 0, 0, 
			0, 0, 0, 0, 
			0, 0, 
			' . $iFechaInicial . ', 0, ' . $core03formanota . ', ' . $core03estado . ', 0, 
			0, 0, 0)';
			$result = $objDB->ejecutasql($sCampos03 . $sValores03);
			if ($result == false) {
				$sError = 'Falla al intentar agregar el curso al PEI.<br>' . $objDB->serror . '';
			}
		} else {
			//Es posible que ya exista como equivalente y que deba ser transformado.
			$fila = $objDB->sf($tabla);
			$core03id = $fila['core03id'];
			if ($fila['core03idequivalente'] != 0) {
				$sSQL = 'UPDATE core03plandeestudios_' . $idContenedor . ' SET core03itipocurso=' . $fila14['corf14tipocredito'] . ', 
				core03numcreditos=' . $fila14['unad40numcreditos'] . ', core03nivelcurso=' . $fila14['corf14nivel'] . ', 
				core03formanota=' . $core03formanota . ', core03estado=' . $core03estado . ', core03idequivalente=0,
				core03idcursoreemp1=0, core03idcursoreemp2=0, core03tieneequivalente=0 
				WHERE core03id=' . $core03id . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = 'Falla al intentar actualizar el curso en el PEI.<br>' . $objDB->serror . '';
				}
			} else {
				$sError = 'El curso ya hace parte del plan de estudios, no se puede agregar la excepci&oacute;n.';
			}
		}
	}
	if ($sError == '') {
		//Ahora importarle la nota del curso.
		list($sErrorL, $sDebugL) = f2203_ArmarFilaPlan($idContenedor, $core03id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugL;
		list($core01avanceplanest, $sErrorP, $sDebugP) = f2203_TotalizarPlanV2($core01id, $objDB, $bDebug, $idContenedor);
		$sDebug = $sDebug . $sDebugP;
		list($sDebugG) = f2201_ActivarParaTrabajoGrado($core01id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugG;
	}
	return array($sError, $sDebug);
}
function f2202_PuedeEditarPlanEstudios($id01, $objDB, $bDebug = false, $idContenedorTercero = 0)
{
	$bRes = true;
	$bNecesitaImportar = false;
	$iPlanActual = 0;
	$sDebug = '';
	$sSQL = 'SELECT core01idtercero, core01idplandeestudios, core01peracainicial, core01idestado FROM core01estprograma WHERE core01id=' . $id01 . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila01 = $objDB->sf($tabla);
		$iPlanActual = $fila01['core01idplandeestudios'];
		switch ($fila01['core01idestado']) {
			case 0:
			case 1:
			case 2:
			case 3:
				if ($fila01['core01idplandeestudios'] != 0) {
					if ($idContenedorTercero == 0) {
						list($idContenedorTercero, $sErrorCont) = f1011_BloqueTercero($fila01['core01idtercero'], $objDB);
					}
					//Mirar si tiene algun registro ya cargado.
					$sSQL = 'SELECT 1 FROM core03plandeestudios_' . $idContenedorTercero . ' WHERE core03idestprograma=' . $id01 . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) != 0) {
						if ($fila01['core01peracainicial'] < 761) {
							$bNecesitaImportar = true;
						}
					}
				} else {
					if ($fila01['core01peracainicial'] < 761) {
						$bNecesitaImportar = true;
					}
				}
				break;
			default:
				$bRes = false;
				break;
		}
	} else {
		$bRes = false;
	}
	return array($bRes, $bNecesitaImportar, $iPlanActual, $sDebug);
}
