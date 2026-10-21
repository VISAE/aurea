<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 13 de agosto de 2018
--- Modelo Versión 2.23.5 Wednesday, August 28, 2019
--- Modelo Versión 2.25.5 lunes, 24 de agosto de 2020
--- 2216 core16actamatricula
*/

/** Archivo lib2216.php.
 * Libreria 2216 core16actamatricula.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date Wednesday, August 28, 2019
 */
function f2216_Combocore16nivelprograma($aParametros)
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
	$html_core16nivelprograma = f2216_HTMLComboV2_core16nivelprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16nivelprograma', 'innerHTML', $html_core16nivelprograma);
	return $objResponse;
}
function f2216_HTMLComboV2_core16nivelprograma($objDB, $objCombos, $valor, $idPrograma)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;

	$objCombos->nuevo('core16nivelprograma', $valor, true, $ETI['msg_seleccione'], 0);
	$sSQL = 'SELECT core09semestres FROM core09programa WHERE core09id=' . $idPrograma . ' AND core09formapromocion=1';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iNumCiclos = $fila['core09semestres'];
		for ($i = 1; $i <= $iNumCiclos; $i++) {
			$objCombos->addItem($i, numeros_enromano($i));
		}
	}
	$res = $objCombos->html('', $objDB);
	return $res;
}
function f2216_HTMLComboV2_core16peracaSinep($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16peraca', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = f146_ConsultaCombo('exte02tipoperiodo=5 AND exte02vigente="S"', $objDB);
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2216_HTMLComboV2_core16peraca($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16peraca', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = f146_ConsultaCombo('', $objDB);
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2216_HTMLComboV2_core16idprograma($objDB, $objCombos, $valor, $idEscuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sCondi = ' WHERE core09idescuela="' . $idEscuela . '" AND core09id>0';
	$objCombos->nuevo('core16idprograma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->iAncho = 400;
	$objCombos->sAccion = 'RevisaLlave();';
	$objCombos->sAccion = 'carga_combo_core16idplanestudio()';
	$sSQL = 'SELECT core09id AS id, CONCAT(core09nombre, "[", core09codigo, "]") AS nombre FROM core09programa' . $sCondi;
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2216_HTMLComboV2_core16idplanestudio($objDB, $objCombos, $valor, $vrcore16idprograma)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	//$sCondi='core10idprograma="'.$vrcore16idprograma.'"';
	//if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('core16idplanestudio', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = '';
	$objCombos = f2210_LlenarComboPlanes($vrcore16idprograma, $objDB, $objCombos);
	$objCombos->iAncho = 360;
	//$sSQL='SELECT core10id AS id, core10consec AS nombre FROM core10programaversion'.$sCondi;
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2216_HTMLComboV2_core16idcead($objDB, $objCombos, $valor, $idZona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sCondi = ' WHERE unad24idzona="' . $idZona . '" AND unad24id>0';
	$objCombos->nuevo('core16idcead', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede' . $sCondi;
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2216_HTMLComboV2_core16origen($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core16origen', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT core17id AS id, core17nombre AS nombre FROM core17origenmatricula';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2216_Combocore16idprograma($aParametros)
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
	$html_core16idprograma = f2216_HTMLComboV2_core16idprograma($objDB, $objCombos, '', $aParametros[0]);
	$html_core16idplanestudio = f2216_HTMLComboV2_core16idplanestudio($objDB, $objCombos, '', 0);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16idprograma', 'innerHTML', $html_core16idprograma);
	$objResponse->call('$("#core16idprograma").chosen()');
	$objResponse->assign('div_core16idplanestudio', 'innerHTML', $html_core16idplanestudio);
	return $objResponse;
}
function f2216_Combocore16idplanestudio($aParametros)
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
	$html_core16idplanestudio = f2216_HTMLComboV2_core16idplanestudio($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16idplanestudio', 'innerHTML', $html_core16idplanestudio);
	//$objResponse->call('$("#core16idplanestudio").chosen()');
	return $objResponse;
}
function f2216_Combocore16idcead($aParametros)
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
	$html_core16idcead = f2216_HTMLComboV2_core16idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core16idcead', 'innerHTML', $html_core16idcead);
	//$objResponse->call('$("#core16idcead").chosen()');
	return $objResponse;
}
function f2216_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$core16peraca = numeros_validar($datos[1]);
	if ($core16peraca == '') {
		$bHayLlave = false;
	}
	$core16tercero = numeros_validar($datos[2]);
	if ($core16tercero == '') {
		$bHayLlave = false;
	}
	$core16idprograma = numeros_validar($datos[3]);
	if ($core16idprograma == '') {
		$bHayLlave = false;
	}
	$core16idplanestudio = numeros_validar($datos[4]);
	if ($core16idplanestudio == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM core16actamatricula WHERE core16peraca=' . $core16peraca . ' AND core16tercero=' . $core16tercero . ' AND core16idprograma=' . $core16idprograma . ' AND core16idplanestudio=' . $core16idplanestudio . '';
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
function f2216_Busquedas($aParametros)
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
	$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2216)) {
		$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2216;
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
		case 'core16tercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2216);
			break;
		case 'core16idconsejero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2216);
			break;
		case 'core04idcurso':
			$bExiste = true;
			if (file_exists('lib140.php')) {
				require 'lib140.php';
			} else {
				$bExiste = false;
			}
			if ($bExiste) {
				if (!function_exists('f140_TablaDetalleBusquedas')) {
					$bExiste = false;
				}
			}
			if ($bExiste) {
				$sTabla = f140_TablaDetalleBusquedas($aParametrosB, $objDB);
				$sTitulo = f140_TituloBusqueda();
				$sParams = f140_ParametrosBusqueda();
				$sJavaBusqueda = f140_JavaScriptBusqueda(2216);
			} else {
				$sTitulo = 'Busquedas';
				$sTabla = '<div class="MarquesinaMedia">No se ha definido la busqueda 140, por favor informe al administrador del sistema.</div>';
			}
			break;
		case 'core04tercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2216);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_2216'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f2216_HtmlBusqueda($aParametros)
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
		case 'core16tercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'core16idconsejero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'core04idcurso':
			if (file_exists('lib140.php')) {
				require 'lib140.php';
				$sDetalle = f140_TablaDetalleBusquedas($aParametros, $objDB);
			} else {
				$sDetalle = 'No se encuentra la libreria ' . 'lib140, por favor informe al administrador del sistema.';
			}
			break;
		case 'core04tercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2216_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2216)) {
		$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_es.php';
	}
	require $mensajes_2216;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[98]) == 0) {
		$aParametros[98] = 0;
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
	$iNumVariables = 115;
	for ($k = 103; $k <= $iNumVariables; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$iTipo = numeros_validar($aParametros[98]);
	$idTercero = $aParametros[100];
	$sDebug = '';
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bListar = $aParametros[104];
	$sDoc = trim($aParametros[105]);
	$bPeriodo = numeros_validar($aParametros[106]);
	$bResultado = $aParametros[113];
	$bUltIngreso = $aParametros[114];
	$bAbierta = true;
	$idPeriodoInicioC2 = Id_PeriodoInicioC2();
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2216" name="paginaf2216" type="hidden" value="' . $pagina . '"/>
	<input id="lppf2216" name="lppf2216" type="hidden" value="' . $lineastabla . '"/>';
	/*
	$aEstado=array();
	$sSQL='SELECT core30id, core30nombre FROM core30estadomatricula';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['core30id']]=cadena_notildes($fila['core30nombre']);
		}
	*/
	$bHayParametros = false;
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sSQLTerceros = '';
	$bConBotonMultimatricula = false;
	$bConTercero = false;
	$bConPrograma = false;
	//$sCondiEstado = 'TB.core16estado<>99 AND ';
	$sCondiEstado = '';
	if ($bPeriodo != '') {
		$bHayParametros = true;
		$sSQLadd1 = $sSQLadd1 . 'TB.core16peraca=' . $bPeriodo . ' AND ';
	}
	if ($aParametros[112] != '') {
		$bHayParametros = true;
		$sSQLadd1 = $sSQLadd1 . 'TB.core16idprograma=' . $aParametros[112] . ' AND ';
	} else {
		if ($aParametros[111] != '') {
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idescuela=' . $aParametros[111] . ' AND ';
		}
	}
	if ($aParametros[110] != '') {
		$bHayParametros = true;
		$sSQLadd1 = $sSQLadd1 . 'TB.core16idcead=' . $aParametros[110] . ' AND ';
	} else {
		if ($aParametros[109] != '') {
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16idzona=' . $aParametros[109] . ' AND ';
		}
	}
	if ($aParametros[107] != '') {
		$bHayParametros = true;
		$sSQLadd1 = $sSQLadd1 . 'TB.core16parametros LIKE "%' . $aParametros[107] . '%" AND ';
	}
	switch ($bListar) {
		case 1: // Estudiantes nuevos
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16nuevo=1 AND ';
			break;
		case 2: // Estudiantes antiguso
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16nuevo=0 AND ';
			break;
		case 6: // Estudiantes de reintegro
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16nuevo=2 AND ';
			break;
		case 3: // Sin procesar matricula
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16procesado=0 AND ';
			break;
		case 4: // Sin procesar caracterizacion
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16proccarac=0 AND ';
			break;
		case 5: // Sin procesar agenda
			$bHayParametros = true;
			switch ($iTipo) {
				case 2:
					$sSQLadd1 = $sSQLadd1 . 'TB.core16procagenda=0 AND ';
					break;
				default:
					$sSQLadd1 = $sSQLadd1 . 'TB.core16procagenda=0 AND TB.core16procesado<>0 AND ';
					break;
			}
			break;
		case 7: //Sin cancelaciones
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.core16nuevo IN (0,1,2) AND ';
			break;
		case 9: // Cancelacion
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.cara16numcancelados>0 AND ';
			break;
		case 10: // Aplazamientos
			$bHayParametros = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.cara16numaplazados>0 AND ';
			break;
		case 21:
			$bHayParametros = true;
			$sSQLTerceros = $sSQLTerceros . ' AND T2.unad11necesidadesp<>""';
			$bConTercero = true;
			break;
		case 22: // Canceladas
			$bHayParametros = true;
			$sCondiEstado = 'TB.core16estado=99 AND ';
			break;
		case 23: // Multimatricula -- Solo si viene un periodo académico.
			if ($bPeriodo != '') {
				$sIds = '-99';
				$sSQL = 'SELECT core16tercero 
				FROM core16actamatricula 
				WHERE core16peraca=' . $bPeriodo . ' AND core16estado<>99 AND core16tipomatricula=' . $iTipo . ' 
				GROUP BY core16tercero 
				HAVING COUNT(1)>1';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					$sIds = $sIds . ',' . $fila['core16tercero'];
				}
				$sSQLadd1 = $sSQLadd1 . 'TB.core16tercero IN (' . $sIds . ') AND ';
				if ($iTipo == 0) {
					if ($sIds != '-99') {
						$bConBotonMultimatricula = true;
					}
				}
			}
			break;
	}
	if ($aParametros[115] != '') {
		$bHayParametros = true;
		$sSQLadd1 = $sSQLadd1 . 'TB.core16tipomatricula=' . $aParametros[115] . ' AND ';
	}
	if ($sDoc != '') {
		$bHayParametros = true;
		$sSQLTerceros = $sSQLTerceros . ' AND T2.unad11doc LIKE "' . $sDoc . '%"';
		$bConTercero = true;
	}
	if ($aParametros[103] != '') {
		$bHayParametros = true;
		$sBase = trim(strtoupper($aParametros[103]));
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLTerceros = $sSQLTerceros . ' AND T2.unad11razonsocial LIKE "%' . $sCadena . '%"';
				$bConTercero = true;
			}
		}
	}
	if ((int)$bUltIngreso != 0) {
		$bHayParametros = true;
		$sSQLTerceros = $sSQLTerceros . ' AND T2.unad11fechaultingreso<=' . $bUltIngreso . '';
		$bConTercero = true;
	}
	switch ($iTipo) {
		case 0: //Campus
		case 1: //Por Guias
		case 5: //Por INPEC.
			$sSQLadd1 = $sSQLadd1 . 'TB.core16tipomatricula IN (0, 1, 5) AND ';
			break;
		default:
			$sSQLadd1 = $sSQLadd1 . 'TB.core16tipomatricula=' . $iTipo . ' AND ';
			break;
	}
	if ($bResultado != '') {
		$bHayParametros = true;
		$sSQLadd1 = $sSQLadd1 . 'TB.core16resultado=' . $bResultado . ' AND ';
	}
	$sTablaConvenio = '';
	if ($aParametros[108] != '') {
		$bHayParametros = true;
		$sTablaConvenio = ', core51convenioest AS T51';
		$sSQLadd1 = $sSQLadd1 . 'TB.core16tercero=T51.core51idtercero AND T51.core51idconvenio=' . $aParametros[108] . ' AND T51.core51activo="S" AND ';
	}
	if (!$bHayParametros) {
		$sLeyenda = 'Por favor ingrese un parametro para mostrar datos.';
	}
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	$sTitulos = 'Documento, Razon social, Programa, Nuevo, N cursos,Fecha Ultimo Acceso Campus';
	$registros = 0;
	$bGigante = false;
	$sLimite = '';
	$sTablasReg = '';
	$sCondiReg = '1';
	if ($bConTercero) {
		$sIds11 = '-99';
		//$sTablasReg = ', unad11terceros AS T2';
		//$sCondiReg = ' TB.core16tercero=T2.unad11id';
		$sSQL = 'SELECT unad11id FROM unad11terceros AS T2 WHERE T2.unad11id>0 ' . $sSQLTerceros . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Consulta filtro de terceros para 2216: ' . $sSQL);
		}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds11 = $sIds11 . ',' . $fila['unad11id'];
		}
		$sSQLadd1 = $sSQLadd1 . 'TB.core16tercero IN (' . $sIds11 . ') AND ';
	}
	$sSQL = 'SELECT COUNT(1) AS Total 
	FROM core16actamatricula AS TB' . $sTablaConvenio . $sTablasReg . ' 
	WHERE ' . $sSQLadd1 . $sCondiEstado . $sCondiReg . '' . $sSQLadd . '';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Calculando total: ' . $sSQL);
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
	//TB.core16numaprobados, 
	$sSQL = 'SELECT STRAIGHT_JOIN T2.unad11doc AS C2_doc, T2.unad11razonsocial AS C2_nombre, T9.core09nombre, TB.core16nuevo, TB.core16numcursos, 
	TB.cara16numcursosext, TB.cara16numcursospost, TB.cara16numcreditos, TB.cara16numcreditosext, TB.cara16numcreditospost, 
	TB.core16id, TB.core16idcead, TB.core16parametros, TB.core16fecharecibido, TB.core16minrecibido, TB.core16procesado, 
	TB.core16promedio, TB.core16origen, TB.core16proccarac, TB.core16procagenda, TB.core16peraca, 
	TB.core16tercero, T2.unad11tipodoc AS C2_td, TB.core16idprograma, TB.core16idescuela, TB.core16idzona, 
	TB.cara16nummatriculaprog, T2.unad11fechaultingreso, TB.core16estado  
	FROM core16actamatricula AS TB' . $sTablaConvenio . ', unad11terceros AS T2, core09programa AS T9 
	WHERE ' . $sSQLadd1 . $sCondiEstado . ' TB.core16tercero=T2.unad11id AND TB.core16idprograma=T9.core09id' . $sSQLadd . '
	ORDER BY TB.core16peraca DESC, TB.core16idcead, TB.core16idprograma, TB.core16tercero';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2216" name="consulta_2216" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_2216" name="titulos_2216" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Consulta 2216: ' . $sSQL . $sLimite);
	}
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		$sLeyenda = $sSQL;
	}
	if ($bConBotonMultimatricula) {
		$sLeyenda = $sLeyenda . html_salto() . '<div class="ir_derecha">';
		$sLeyenda = $sLeyenda . '<label class="Label130">';
		$sLeyenda = $sLeyenda . '<input id="cmdMultiMatricula" name="cmdMultiMatricula" type="button" value="Revisar Matriculas" class="BotonAzul160" onclick="multimatricula()" title="Revisar Matricula en Registro y Control" />';
		$sLeyenda = $sLeyenda . '</label><label class="Label60"></label></div>' . html_salto();
	}
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th colspan="2"><b>' . $ETI['core16tercero'] . '</b></th>';
	$res = $res . '<th colspan="3"><b>' . $ETI['core16numcursos'] . '</b></th>';
	$res = $res . '<th colspan="3"><b>' . $ETI['cara16numcreditos'] . '</b></th>';
	$res = $res . '<th colspan="3" align="right">';
	$res = $res . html_paginador('paginaf2216', $registros, $lineastabla, $pagina, 'paginarf2216()') . '';
	$res = $res . html_lpp('lppf2216', $lineastabla, 'paginarf2216()') . '';
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	$idPeriodo = -1;
	$idPrograma = -1;
	$idCead = -1;
	$iNumColumnas = 11;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idPeriodo != $filadet['core16peraca']) {
			$idPeriodo = $filadet['core16peraca'];
			$idPrograma = -1;
			$idCead = -1;
			$sNomPeraca = '{' . $filadet['core16peraca'] . '}';
			$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $filadet['core16peraca'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sNomPeraca = cadena_notildes($fila['exte02nombre']);
			}
			$res = $res . '<tr class="fondoazul">';
			$res = $res . '<td colspan="' . $iNumColumnas . '">' . $ETI['core16peraca'] . ' <b>' . $sNomPeraca . '</b></td>';
			$res = $res . '</tr>';
		}
		if ($idPrograma != $filadet['core16idprograma']) {
			$idPrograma = $filadet['core16idprograma'];
			$idCead = -1;
			$sNomPrograma = '{' . $filadet['core16idprograma'] . '}';
			$sSQL = 'SELECT core09codigo, core09nombre FROM core09programa WHERE core09id=' . $filadet['core16idprograma'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sNomPrograma = $fila['core09codigo'] . ' ' . cadena_notildes($fila['core09nombre']);
			}
			$res = $res . '<tr class="fondoazul">';
			$res = $res . '<td colspan="' . $iNumColumnas . '">' . $ETI['core16idprograma'] . ' <b>' . $sNomPrograma . '</b></td>';
			$res = $res . '</tr>';
		}
		if ($idCead != $filadet['core16idcead']) {
			$idCead = $filadet['core16idcead'];
			$sNomCEAD = '{' . $filadet['core16idcead'] . '}';
			$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $filadet['core16idcead'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sNomCEAD = cadena_notildes($fila['unad24nombre']);
			}
			$res = $res . '<tr class="fondoazul">';
			$res = $res . '<td colspan="2">' . $ETI['core16idcead'] . ' <b>' . $sNomCEAD . '</b></td>';
			$res = $res . '<td title="N&uacute;mero de cursos de pregrado"><b>Pre.</b></td>';
			$res = $res . '<td title="N&uacute;mero de cursos externos"><b>Ext.</b></td>';
			$res = $res . '<td title="N&uacute;mero de cursos de postgrado"><b>Post.</b></td>';
			$res = $res . '<td title="N&uacute;mero de cr&eacute;ditos de pregrado"><b>Pre.</b></td>';
			$res = $res . '<td title="N&uacute;mero de cr&eacute;ditos externos"><b>Ext.</b></td>';
			$res = $res . '<td title="N&uacute;mero de cr&eacute;ditos de postgrado"><b>Post.</b></td>';
			$res = $res . '<td><b>' . $ETI['core16nuevo'] . '</b></td>';
			$res = $res . '<td><b>N&deg; Mat.</b></td>';
			$res = $res . '<td></td>';
			$res = $res . '</tr>';
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['core16estado']) {
			case 99: // Anulada
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span>';
				break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		/*
		$et_core16fecharecibido='';
		if ($filadet['core16fecharecibido']!=0){
			$et_core16fecharecibido=fecha_desdenumero($filadet['core16fecharecibido']).' '.html_TablaHoraMinDesdeNumero($filadet['core16minrecibido']);
			}
		*/
		$et_core16nuevo = $acore16nuevo[$filadet['core16nuevo']];
		$et_cara16nummatriculaprog = '';
		if ($filadet['cara16nummatriculaprog'] != 0) {
			$et_cara16nummatriculaprog = $filadet['cara16nummatriculaprog'];
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2216(' . $filadet['core16id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		//cara16numcursosext, TB.cara16numcursospost, TB.cara16numcreditos, TB.cara16numcreditosext, TB.cara16numcreditospost
		$et_core16numcursos = '';
		$et_cara16numcursosext = '';
		$et_cara16numcursospost = '';
		$et_cara16numcreditos = '';
		$et_cara16numcreditosext = '';
		$et_cara16numcreditospost = '';
		if ($idPeriodo > $idPeriodoInicioC2) {
			if ($filadet['core16procesado'] <> 0) {
				if ($filadet['core16numcursos'] > 0) {
					$et_core16numcursos = $filadet['core16numcursos'];
					$et_cara16numcreditos = $filadet['cara16numcreditos'];
				}
				if ($filadet['cara16numcursosext'] > 0) {
					$et_cara16numcursosext = $filadet['cara16numcursosext'];
					$et_cara16numcreditosext = $filadet['cara16numcreditosext'];
				}
				if ($filadet['cara16numcursospost'] > 0) {
					$et_cara16numcursospost = $filadet['cara16numcursospost'];
					$et_cara16numcreditospost = $filadet['cara16numcreditospost'];
				}
			}
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo . '</td>';
		$res = $res . '<td align="center">' . $sPrefijo . $et_core16numcursos . $sSufijo . '</td>';
		$res = $res . '<td align="center">' . $sPrefijo . $et_cara16numcursosext . $sSufijo . '</td>';
		$res = $res . '<td align="center">' . $sPrefijo . $et_cara16numcursospost . $sSufijo . '</td>';
		$res = $res . '<td align="center">' . $sPrefijo . $et_cara16numcreditos . $sSufijo . '</td>';
		$res = $res . '<td align="center">' . $sPrefijo . $et_cara16numcreditosext . $sSufijo . '</td>';
		$res = $res . '<td align="center">' . $sPrefijo . $et_cara16numcreditospost . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $et_core16nuevo . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $et_cara16nummatriculaprog . $sSufijo . '</td>';
		$res = $res . '<td aling="rigth">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2216_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2216_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2216detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2216_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['core16tercero_td'] = $APP->tipo_doc;
	$DATA['core16tercero_doc'] = '';
	$DATA['core16idconsejero_td'] = $APP->tipo_doc;
	$DATA['core16idconsejero_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'core16peraca=' . $DATA['core16peraca'] . ' AND core16tercero="' . $DATA['core16tercero'] . '" AND core16idprograma=' . $DATA['core16idprograma'] . '';
	} else {
		$sSQLcondi = 'core16id=' . $DATA['core16id'] . '';
	}
	$sSQL = 'SELECT * FROM core16actamatricula WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['core16peraca'] = $fila['core16peraca'];
		$DATA['core16tercero'] = $fila['core16tercero'];
		$DATA['core16idprograma'] = $fila['core16idprograma'];
		$DATA['core16idplanestudio'] = $fila['core16idplanestudio'];
		$DATA['core16id'] = $fila['core16id'];
		$DATA['core16idcead'] = $fila['core16idcead'];
		$DATA['core16parametros'] = $fila['core16parametros'];
		$DATA['core16idescuela'] = $fila['core16idescuela'];
		$DATA['core16idzona'] = $fila['core16idzona'];
		$DATA['core16fecharecibido'] = $fila['core16fecharecibido'];
		$DATA['core16minrecibido'] = $fila['core16minrecibido'];
		$DATA['core16procesado'] = $fila['core16procesado'];
		$DATA['core16numcursos'] = $fila['core16numcursos'];
		$DATA['core16numaprobados'] = $fila['core16numaprobados'];
		$DATA['core16promedio'] = $fila['core16promedio'];
		$DATA['core16origen'] = $fila['core16origen'];
		$DATA['core16nuevo'] = $fila['core16nuevo'];
		$DATA['core16proccarac'] = $fila['core16proccarac'];
		$DATA['core16procagenda'] = $fila['core16procagenda'];
		$DATA['core16idconsejero'] = $fila['core16idconsejero'];
		$DATA['core16errormatricula'] = $fila['core16errormatricula'];
		$DATA['core16estado'] = $fila['core16estado'];
		$DATA['core16erroragenda'] = $fila['core16erroragenda'];
		$DATA['core16aplazados'] = $fila['core16aplazados'];
		$DATA['core16cancelados'] = $fila['core16cancelados'];
		$DATA['core16fechamatricula'] = $fila['core16fechamatricula'];
		$DATA['core16paraseguimiento'] = $fila['core16paraseguimiento'];
		$DATA['core16tipomatricula'] = $fila['core16tipomatricula'];
		$DATA['core16idconvenio'] = $fila['core16idconvenio'];
		$DATA['core16edad'] = $fila['core16edad'];
		$DATA['core16idcaracterizacion'] = $fila['core16idcaracterizacion'];
		$DATA['cara16numaplazados'] = $fila['cara16numaplazados'];
		$DATA['cara16numcancelados'] = $fila['cara16numcancelados'];
		$DATA['cara16numcreditos'] = $fila['cara16numcreditos'];
		$DATA['cara16credaprobados'] = $fila['cara16credaprobados'];
		$DATA['cara16nummatriculaprog'] = $fila['cara16nummatriculaprog'];
		$DATA['cara16nummatriculatotal'] = $fila['cara16nummatriculatotal'];
		$DATA['cara16numcursosext'] = $fila['cara16numcursosext'];
		$DATA['cara16numcreditosext'] = $fila['cara16numcreditosext'];
		$DATA['cara16numcursospost'] = $fila['cara16numcursospost'];
		$DATA['cara16numcreditospost'] = $fila['cara16numcreditospost'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2216'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2216_db_GuardarV2($DATA, $objDB, $bDebug = false, $iCodModulo = 2216)
{
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2216)) {
		$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2216;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['core16peraca'])==0){$DATA['core16peraca']='';}
	if (isset($DATA['core16tercero'])==0){$DATA['core16tercero']='';}
	if (isset($DATA['core16idprograma'])==0){$DATA['core16idprograma']='';}
	*/
	if (isset($DATA['core16idplanestudio']) == 0) {
		$DATA['core16idplanestudio'] = '';
	}
	/*
	if (isset($DATA['core16id'])==0){$DATA['core16id']='';}
	if (isset($DATA['core16idcead'])==0){$DATA['core16idcead']='';}
	if (isset($DATA['core16parametros'])==0){$DATA['core16parametros']='';}
	if (isset($DATA['core16idescuela'])==0){$DATA['core16idescuela']='';}
	if (isset($DATA['core16idzona'])==0){$DATA['core16idzona']='';}
	if (isset($DATA['core16fecharecibido'])==0){$DATA['core16fecharecibido']='';}
	if (isset($DATA['core16minrecibido'])==0){$DATA['core16minrecibido']='';}
	if (isset($DATA['core16origen'])==0){$DATA['core16origen']='';}
	if (isset($DATA['core16nuevo'])==0){$DATA['core16nuevo']='';}
	if (isset($DATA['core16aplazados'])==0){$DATA['core16aplazados']='';}
	if (isset($DATA['core16cancelados'])==0){$DATA['core16cancelados']='';}
	if (isset($DATA['core16fechamatricula'])==0){$DATA['core16fechamatricula']='';}
	if (isset($DATA['core16paraseguimiento'])==0){$DATA['core16paraseguimiento']='';}
	if (isset($DATA['core16idconvenio'])==0){$DATA['core16idconvenio']='';}
	*/
	$DATA['core16peraca'] = numeros_validar($DATA['core16peraca']);
	$DATA['core16idprograma'] = numeros_validar($DATA['core16idprograma']);
	$DATA['core16idplanestudio'] = numeros_validar($DATA['core16idplanestudio']);
	$DATA['core16idcead'] = numeros_validar($DATA['core16idcead']);
	$DATA['core16parametros'] = htmlspecialchars(trim($DATA['core16parametros']));
	$DATA['core16idescuela'] = numeros_validar($DATA['core16idescuela']);
	$DATA['core16idzona'] = numeros_validar($DATA['core16idzona']);
	$DATA['core16minrecibido'] = numeros_validar($DATA['core16minrecibido']);
	$DATA['core16origen'] = numeros_validar($DATA['core16origen']);
	$DATA['core16nuevo'] = numeros_validar($DATA['core16nuevo']);
	$DATA['core16aplazados'] = htmlspecialchars(trim($DATA['core16aplazados']));
	$DATA['core16cancelados'] = htmlspecialchars(trim($DATA['core16cancelados']));
	$DATA['core16fechamatricula'] = numeros_validar($DATA['core16fechamatricula']);
	$DATA['core16paraseguimiento'] = numeros_validar($DATA['core16paraseguimiento']);
	$DATA['core16idconvenio'] = numeros_validar($DATA['core16idconvenio']);
	$DATA['core16nivelprograma'] = numeros_validar($DATA['core16nivelprograma']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['core16idcead']==''){$DATA['core16idcead']=0;}
	//if ($DATA['core16idescuela']==''){$DATA['core16idescuela']=0;}
	//if ($DATA['core16idzona']==''){$DATA['core16idzona']=0;}
	//if ($DATA['core16minrecibido']==''){$DATA['core16minrecibido']=0;}
	if ($DATA['core16procesado'] == '') {
		$DATA['core16procesado'] = 0;
	}
	if ($DATA['core16numcursos'] == '') {
		$DATA['core16numcursos'] = 0;
	}
	if ($DATA['core16numaprobados'] == '') {
		$DATA['core16numaprobados'] = 0;
	}
	if ($DATA['core16promedio'] == '') {
		$DATA['core16promedio'] = 0;
	}
	if ($DATA['core16origen'] == '') {
		$DATA['core16origen'] = 0;
	}
	//if ($DATA['core16nuevo']==''){$DATA['core16nuevo']=0;}
	if ($DATA['core16proccarac'] == '') {
		$DATA['core16proccarac'] = 0;
	}
	if ($DATA['core16procagenda'] == '') {
		$DATA['core16procagenda'] = 0;
	}
	if ($DATA['core16errormatricula'] == '') {
		$DATA['core16errormatricula'] = 0;
	}
	if ($DATA['core16estado'] == '') {
		$DATA['core16estado'] = 0;
	}
	if ($DATA['core16erroragenda'] == '') {
		$DATA['core16erroragenda'] = 0;
	}
	if ($DATA['core16paraseguimiento'] == '') {
		$DATA['core16paraseguimiento'] = 0;
	}
	if ($DATA['core16tipomatricula'] == '') {
		$DATA['core16tipomatricula'] = 0;
	}
	if ($DATA['core16idconvenio'] == '') {
		$DATA['core16idconvenio'] = 0;
	}
	/*
	if ($DATA['cara16numaplazados']==''){$DATA['cara16numaplazados']=0;}
	if ($DATA['cara16numcancelados']==''){$DATA['cara16numcancelados']=0;}
	if ($DATA['cara16numcreditos']==''){$DATA['cara16numcreditos']=0;}
	if ($DATA['cara16credaprobados']==''){$DATA['cara16credaprobados']=0;}
	if ($DATA['cara16nummatriculaprog']==''){$DATA['cara16nummatriculaprog']=0;}
	if ($DATA['cara16nummatriculatotal']==''){$DATA['cara16nummatriculatotal']=0;}
	if ($DATA['cara16numcursosext']==''){$DATA['cara16numcursosext']=0;}
	if ($DATA['cara16numcreditosext']==''){$DATA['cara16numcreditosext']=0;}
	if ($DATA['cara16numcursospost']==''){$DATA['cara16numcursospost']=0;}
	if ($DATA['cara16numcreditospost']==''){$DATA['cara16numcreditospost']=0;}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		//if ($DATA['core16paraseguimiento']==''){$sError=$ERR['core16paraseguimiento'].$sSepara.$sError;}
		if ($DATA['core16fechamatricula'] == 0) {
			//$DATA['core16fechamatricula']=fecha_DiaMod();
			if ($DATA['core16tipomatricula'] == 0) {
				//$sError=$ERR['core16fechamatricula'].$sSepara.$sError;
			} else {
				//$DATA['core16fechamatricula']=$ERR['core16fecharecibido'];
			}
		}
		// Esto es la ulib, no tengo el idsistema, debo basarlo en la escuela
		if ($DATA['core16idescuela'] == 627002) {
			if ($DATA['core16nivelprograma'] == 0) {
				$sError = $ERR['core16nivelprograma'] . $sSepara . $sError;
			}
		} else {
			$DATA['core16nivelprograma'] = 0;
		}
		//if ($DATA['core16cancelados']==''){$sError=$ERR['core16cancelados'].$sSepara.$sError;}
		//if ($DATA['core16aplazados']==''){$sError=$ERR['core16aplazados'].$sSepara.$sError;}
		/*
		if ($DATA['core16idconsejero'] == 0) {
			$sError = $ERR['core16idconsejero'] . $sSepara . $sError;
		}
		*/
		if ($DATA['core16nuevo'] == '') {
			$sError = $ERR['core16nuevo'] . $sSepara . $sError;
		}
		if ($DATA['core16origen'] == '') {
			$sError = $ERR['core16origen'] . $sSepara . $sError;
		}
		if ($DATA['core16fecharecibido'] == 0) {
			$DATA['core16fecharecibido'] = fecha_DiaMod();
			$DATA['core16minrecibido'] = fecha_MinutoMod();
		}
		/*
		switch ($DATA['core16tipomatricula']){
			case 0: // Campus
			if ($DATA['core16origen']==0){
				if ($DATA['paso']==10){
					$DATA['core16fecharecibido']=fecha_DiaMod();
					$DATA['core16minrecibido']=fecha_MinutoMod();
					}
				} else {
				if ($DATA['core16fecharecibido']==0){
					$sError=$ERR['core16fecharecibido'].$sSepara.$sError;
					if ($DATA['core16minrecibido']==''){$sError=$ERR['core16minrecibido'].' ['.$DATA['core16origen'].']'.$sSepara.$sError;}
					}
				}
			break;
			default:
			if ($DATA['core16fecharecibido']==0){
				$DATA['core16fecharecibido']=fecha_DiaMod();
				$DATA['core16minrecibido']=fecha_MinutoMod();
				}
			break;
			}
			*/
		if ($DATA['core16idzona'] == '') {
			$sError = $ERR['core16idzona'] . $sSepara . $sError;
		}
		if ($DATA['core16idescuela'] == '') {
			$sError = $ERR['core16idescuela'] . $sSepara . $sError;
		}
		if ($DATA['core16idcead'] == '') {
			$sError = $ERR['core16idcead'] . $sSepara . $sError;
		}
		/*
		if ($DATA['core16estado']==0){
			} else {
			if ($DATA['core16parametros']==''){$sError=$ERR['core16parametros'].$sSepara.$sError;}
			}
		*/
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['core16idplanestudio'] == '') {
		$sError = $ERR['core16idplanestudio'] . ' {' . $DATA['core16estado'] . '}';
	}
	/*
	switch($DATA['core16estado']){
		case -2: // Candidato... 
		$DATA['core16idplanestudio']=0;
		break;
		default:
		break;
		}
	*/
	if ($DATA['core16idprograma'] == '') {
		$sError = $ERR['core16idprograma'];
	}
	if ($DATA['core16idprograma'] == 450) {
		$sError = $ERR['core16idprograma'];
	}
	if ($DATA['core16tercero'] == 0) {
		$sError = $ERR['core16tercero'];
	}
	if ($DATA['core16peraca'] == '') {
		$sError = $ERR['core16peraca'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['core16idconsejero_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['core16idconsejero_td'], $DATA['core16idconsejero_doc'], $objDB, 'El tercero Consejero ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['core16idconsejero'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['core16tercero_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['core16tercero_td'], $DATA['core16tercero_doc'], $objDB, 'El tercero Tercero ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['core16tercero'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	//Verificamos que si es una acta nueva este inscrito como estudiante
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Junio 18 de 2025  - Se agrega el plan de estudios -1 que es NO APLICA.
			$sSQL = 'SELECT core01id, core01idestado, core01fechaultmatricula 
			FROM core01estprograma 
			WHERE core01idtercero=' . $DATA['core16tercero'] . ' AND core01idprograma=' . $DATA['core16idprograma'] . ' AND core01idplandeestudios IN (-1, 0, ' . $DATA['core16idplanestudio'] . ')';
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Consultando PEI: ' . $sSQL);
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) != 0) {
				$fila = $objDB->sf($tabla);
				switch ($fila['core01idestado']) {
					case -2: // Candidato... 
						$sError = 'El estudiante no ha sido admitido en el programa, a&uacute;n no es posible hacer matricula en el programa.';
						break;
					case -1: //Admitido, ya puede
					case 0: //Activo
					case 1: //Estudiante externo
						break;
					case 2: // Inactivo
					case 3: // Sin Matricula Por 2 Años o Más
						//cambio de estado esta en la libcore - lo ponemos como activo con la fecha de la matricula...
						$iFechaReg = $DATA['core16fechamatricula'];
						$bCambiaEstado = true;
						if ($iFechaReg > 0) {
							if ($iFechaReg <= $fila['core01fechaultmatricula']) {
								$bCambiaEstado = false;
							}
						}
						if ($bCambiaEstado) {
							$core22idtercero = 0;
							if (isset($_SESSION['unad_id_tercero']) != 0) {
								$core22idtercero = $_SESSION['unad_id_tercero'];
							}
							$core22anotacion = 'Se activa por matricula.';
							list($sErrorE, $sDebugE) = f2222_CambiaEstado($fila['core01id'], $fila['core01idestado'], 0, $core22idtercero, $core22anotacion, $objDB, $iFechaReg, $bDebug);
						} else {
							//$sError='El estudiante se encuentra inactivo en el programa, debe hacer un proceso de reingreso.';
						}
						break;
					case 5: // Abandono
					case 9: // Retirado
						$sError = 'El estudiante se encuentra retirado del programa, debe hacer un proceso de reingreso.';
						break;
					case 7: //Terminado
					case 10: //Graduado
						if ($DATA['core16peraca'] > 0) {
							$sError = 'El estudiante ya concluy&oacute; su plan de estudio.';
						}
						break;
					case 11: // Plan de transicion, pero... pueden estar importando historicos, entonces, si el periodo es inferior al 3 se debe permitir.
						if ($DATA['core16peraca'] > 2) {
							$sError = 'El plan de estudios ya se le aplico plan de transici&oacute;n, no se pemriten nuevas matriculas.';
						}
						break;
					default:
						$sError = 'No ha sido posible establecer la situaci&oacute;n del estudiante en el programa [Err: 2216e Ref ' . $fila['core01idestado'] . '], no es posible iniciar la matricula.';
						break;
				}
			} else {
				$sError = 'No se encuentra registro del estudiante, por favor verifique que el estudiante se encuentre admitido en el programa.';
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT 1 FROM core16actamatricula WHERE core16peraca=' . $DATA['core16peraca'] . ' AND core16tercero="' . $DATA['core16tercero'] . '" AND core16idprograma=' . $DATA['core16idprograma'] . '';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$sError = $ERR['msg_actaexistente'];
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
			$DATA['core16id'] = tabla_consecutivo('core16actamatricula', 'core16id', '', $objDB);
			if ($DATA['core16id'] == -1) {
				$sError = $objDB->serror;
			}
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$core16fecharecibido = fecha_DiaMod();
			$DATA['core16procesado'] = 0;
			$DATA['core16numcursos'] = 0;
			$DATA['core16numaprobados'] = 0;
			$DATA['core16promedio'] = 0;
			$DATA['core16proccarac'] = 0;
			$DATA['core16procagenda'] = 0;
			$DATA['core16errormatricula'] = 0;
			$DATA['core16estado'] = 0;
			$DATA['core16erroragenda'] = 0;
			$sCampos2216 = 'core16peraca, core16tercero, core16idprograma, core16idplanestudio, core16id, 
			core16idcead, core16parametros, core16idescuela, core16idzona, core16fecharecibido, 
			core16minrecibido, core16procesado, core16numcursos, core16numaprobados, core16promedio, 
			core16origen, core16nuevo, core16proccarac, core16procagenda, core16idconsejero, 
			core16errormatricula, core16estado, core16erroragenda, core16aplazados, core16cancelados, 
			core16fechamatricula, core16paraseguimiento, core16tipomatricula, core16idconvenio, core16nivelprograma';
			$sValores2216 = '' . $DATA['core16peraca'] . ', ' . $DATA['core16tercero'] . ', ' . $DATA['core16idprograma'] . ', ' . $DATA['core16idplanestudio'] . ', ' . $DATA['core16id'] . ', 
			' . $DATA['core16idcead'] . ', "' . $DATA['core16parametros'] . '", ' . $DATA['core16idescuela'] . ', ' . $DATA['core16idzona'] . ', "' . $DATA['core16fecharecibido'] . '", 
			' . $DATA['core16minrecibido'] . ', ' . $DATA['core16procesado'] . ', ' . $DATA['core16numcursos'] . ', ' . $DATA['core16numaprobados'] . ', ' . $DATA['core16promedio'] . ', 
			' . $DATA['core16origen'] . ', ' . $DATA['core16nuevo'] . ', ' . $DATA['core16proccarac'] . ', ' . $DATA['core16procagenda'] . ', ' . $DATA['core16idconsejero'] . ', 
			' . $DATA['core16errormatricula'] . ', ' . $DATA['core16estado'] . ', ' . $DATA['core16erroragenda'] . ', "' . $DATA['core16aplazados'] . '", "' . $DATA['core16cancelados'] . '", 
			"' . $DATA['core16fechamatricula'] . '", ' . $DATA['core16paraseguimiento'] . ', ' . $DATA['core16tipomatricula'] . ', ' . $DATA['core16idconvenio'] . ', ' . $DATA['core16nivelprograma'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO core16actamatricula (' . $sCampos2216 . ') VALUES (' . cadena_codificar($sValores2216) . ');';
				$sdetalle = $sCampos2216 . '[' . cadena_codificar($sValores2216) . ']';
			} else {
				$sSQL = 'INSERT INTO core16actamatricula (' . $sCampos2216 . ') VALUES (' . $sValores2216 . ');';
				$sdetalle = $sCampos2216 . '[' . $sValores2216 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'core16idcead';
			$scampo[2] = 'core16parametros';
			$scampo[3] = 'core16idescuela';
			$scampo[4] = 'core16idzona';
			$scampo[5] = 'core16fecharecibido';
			$scampo[6] = 'core16minrecibido';
			$scampo[7] = 'core16nuevo';
			$scampo[8] = 'core16fechamatricula';
			$scampo[9] = 'core16tipomatricula';
			$scampo[10] = 'core16idconvenio';
			$scampo[11] = 'core16aplazados';
			$scampo[12] = 'core16cancelados';
			$sdato[1] = $DATA['core16idcead'];
			$sdato[2] = $DATA['core16parametros'];
			$sdato[3] = $DATA['core16idescuela'];
			$sdato[4] = $DATA['core16idzona'];
			$sdato[5] = $DATA['core16fecharecibido'];
			$sdato[6] = $DATA['core16minrecibido'];
			$sdato[7] = $DATA['core16nuevo'];
			$sdato[8] = $DATA['core16fechamatricula'];
			$sdato[9] = $DATA['core16tipomatricula'];
			$sdato[10] = $DATA['core16idconvenio'];
			$sdato[11] = $DATA['core16aplazados'];
			$sdato[12] = $DATA['core16cancelados'];
			//core16aplazados, core16cancelados
			$iNumCamposMod = 12;
			$sWhere = 'core16id=' . $DATA['core16id'] . '';
			$sSQL = 'SELECT * FROM core16actamatricula WHERE ' . $sWhere;
			$sdatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $iNumCamposMod; $k++) {
						if (isset($filabase[$scampo[$k]]) == 0) {
							$sDebug = $sDebug . log_debug(' FALLA CODIGO: Falta el campo ' . $k . ' ' . $scampo[$k]);
						}
					}
					$bPrimera = false;
				}
				$bsepara = false;
				for ($k = 1; $k <= $iNumCamposMod; $k++) {
					if ($filabase[$scampo[$k]] != $sdato[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sdetalle = cadena_codificar($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE core16actamatricula SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE core16actamatricula SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2216 ' . $sSQL);
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2216] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['core16id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['core16id'], $sdetalle, $objDB);
				}
				// Si viene de escuela permanente, agregar los cursos dependiendo el nivel
				if ($DATA['paso'] == 10 && $DATA['core16idescuela'] == 627002) {
					if ($DATA['core16nivelprograma'] > 0) {
						// Agregar los cursos a la matricula por nivel
						list($sErrorC, $sDebugC) = f2204_CargarCursosXNivel($DATA['core16id'], $objDB, $bDebug);
					}
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
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2216_db_Eliminar($core16id, $objDB, $bDebug = false, $iCodModulo = 2216)
{
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2216)) {
		$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2216;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$core16id = numeros_validar($core16id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT TB.core16id, TB.core16estado, T11.unad11idtablero 
		FROM core16actamatricula AS TB, unad11terceros AS T11 
		WHERE core16id=' . $core16id . ' AND TB.core16tercero=T11.unad11id';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
			$idContTercero = $filabase['unad11idtablero'];
			if ($filabase['core16estado'] == 99) {
				//Eliminando Matricula - Liberarle los cursos.
				$sSQL = 'UPDATE core04matricula_' . $idContTercero . ' SET core04idmatricula=0 WHERE core04idmatricula=' . $filabase['core16id'] . '';
				if ($bDebug) {
					$sDebug = $sDebug . log_debug(' <b>ANULANDO CURSO</b> ' . $sSQL);
				}
				$result = $objDB->ejecutasql($sSQL);
			}
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $core16id . '}';
		}
	}
	if ($sError == '') {
		// Toca resolver esto porque los datos estan en un contenedor.
		$sSQL = 'SELECT 1 
		FROM core04matricula_' . $idContTercero . ' 
		WHERE core04idmatricula=' . $filabase['core16id'] . ' AND core04estado IN (7,8)';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen cursos calificados sobre esta matricula, no es posible eliminar';
		}
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'DELETE FROM core05actividades_' . $idContTercero . ' WHERE core05idmatricula=' . $filabase['core16id'] . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'Falla al intentar eliminar las agendas. Por favor informe al administrador del sistema.';
		}
	}
	if ($sError == '') {
		$sSQL = 'DELETE FROM core04matricula_' . $idContTercero . ' WHERE core04idmatricula=' . $filabase['core16id'] . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'Falla al intentar eliminar la matricula. Por favor informe al administrador del sistema.';
		}
	}
	if ($sError == '') {
		$sWhere = 'core16id=' . $core16id . '';
		$sSQL = 'DELETE FROM core16actamatricula WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar(2216, $_SESSION['unad_id_tercero'], 4, $core16id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f2216_TituloBusqueda()
{
	return 'Busqueda de Actas de matricula';
}
function f2216_ParametrosBusqueda()
{
	$sParams = '<label class="Label90">Nombre</label><label><input id="b2216nombre" name="b2216nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
}
function f2216_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo=window.document.frmedita.scampobusca.value;
	let params = new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b2216nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f2216_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2216)) {
		$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2216;
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
	//$aParametros[103]=numeros_validar($aParametros[103]);
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
		return array($sLeyenda . '<input id="paginaf2216" name="paginaf2216" type="hidden" value="' . $pagina . '"/><input id="lppf2216" name="lppf2216" type="hidden" value="' . $lineastabla . '"/>', $sDebug);
		die();
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[103].'%" AND ';}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($bNombre!=''){
		$sBase=trim(strtoupper($bNombre));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos = 'Peraca, Tercero, Programa, Id, Cead, Parametros, Escuela, Zona, Fecharecibido, Minrecibido, Procesado, Numcursos, Numaprobados, Promedio, Origen, Nuevo, Proccarac, Procagenda, Consejero, Errormatricula, Estado, Erroragenda, Aplazados, Cancelados, Fechamatricula, Paraseguimiento, Tipomatricula, Convenio';
	$sSQL = 'SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, T3.core09nombre, TB.core16id, T5.unad24nombre, 
	TB.core16parametros, T7.core12nombre, T8.unad23nombre, TB.core16fecharecibido, TB.core16minrecibido, TB.core16procesado, 
	TB.core16numcursos, TB.core16numaprobados, TB.core16promedio, T15.core17nombre, TB.core16nuevo, TB.core16proccarac, 
	TB.core16procagenda, T19.unad11razonsocial AS C19_nombre, TB.core16errormatricula, T21.core30nombre, TB.core16erroragenda, 
	TB.core16aplazados, TB.core16cancelados, TB.core16fechamatricula, TB.core16paraseguimiento, T27.core08nombre, T28.core50nombre, 
	TB.core16peraca, TB.core16tercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.core16idprograma, TB.core16idcead, 
	TB.core16idescuela, TB.core16idzona, TB.core16origen, TB.core16idconsejero, T19.unad11tipodoc AS C19_td, 
	T19.unad11doc AS C19_doc, TB.core16estado, TB.core16tipomatricula, TB.core16idconvenio 
	FROM core16actamatricula AS TB, exte02per_aca AS T1, unad11terceros AS T2, core09programa AS T3, unad24sede AS T5, 
	core12escuela AS T7, unad23zona AS T8, core17origenmatricula AS T15, unad11terceros AS T19, core30estadomatricula AS T21, 
	core08tipomatricula AS T27, core50convenios AS T28 
	WHERE ' . $sSQLadd1 . ' TB.core16peraca=T1.exte02id AND TB.core16tercero=T2.unad11id AND TB.core16idprograma=T3.core09id 
	AND TB.core16idcead=T5.unad24id AND TB.core16idescuela=T7.core12id AND TB.core16idzona=T8.unad23id 
	AND TB.core16origen=T15.core17id AND TB.core16idconsejero=T19.unad11id AND TB.core16estado=T21.core30id 
	AND TB.core16tipomatricula=T27.core08id AND TB.core16idconvenio=T28.core50id ' . $sSQLadd . '
	ORDER BY TB.core16peraca, TB.core16tercero, TB.core16idprograma';
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
		if ($registros == 0) {
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf2216" name="paginaf2216" type="hidden" value="'.$pagina.'"/><input id="lppf2216" name="lppf2216" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
		}
	}
	$res = $sErrConsulta . $sLeyenda . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<tr class="fondoazul">
	<td><b>' . $ETI['core16peraca'] . '</b></td>
	<td colspan="2"><b>' . $ETI['core16tercero'] . '</b></td>
	<td><b>' . $ETI['core16idprograma'] . '</b></td>
	<td><b>' . $ETI['core16idcead'] . '</b></td>
	<td><b>' . $ETI['core16parametros'] . '</b></td>
	<td><b>' . $ETI['core16idescuela'] . '</b></td>
	<td><b>' . $ETI['core16idzona'] . '</b></td>
	<td><b>' . $ETI['core16fecharecibido'] . '</b></td>
	<td><b>' . $ETI['core16minrecibido'] . '</b></td>
	<td><b>' . $ETI['core16procesado'] . '</b></td>
	<td><b>' . $ETI['core16numcursos'] . '</b></td>
	<td><b>' . $ETI['core16numaprobados'] . '</b></td>
	<td><b>' . $ETI['core16promedio'] . '</b></td>
	<td><b>' . $ETI['core16origen'] . '</b></td>
	<td><b>' . $ETI['core16nuevo'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['core16id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_core16fecharecibido = '';
		if ($filadet['core16fecharecibido'] != 0) {
			$et_core16fecharecibido = fecha_desdenumero($filadet['core16fecharecibido']);
		}
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['exte03nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core16idcead'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core16parametros']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_core16fecharecibido . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core16minrecibido'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core16procesado'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core16numcursos'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core16numaprobados'] . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['core16promedio']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core16origen'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['core16nuevo'] . $sSufijo . '</td>
		<td></td>
		</tr>';
	}
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return cadena_codificar($res);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
/**
 * Registra o actualiza una matricula recibida por servicios o integraciones.
 * El origen historico es WebService (1); el parametro opcional origen
 * permite identificar un consumidor especifico, como Articulacion (2), sin
 * cambiar el contrato de los demas llamados. Inserta o actualiza
 * core16actamatricula y registra la auditoria correspondiente.
 *
 * @param array $aParametros Datos de periodo, estudiante, programa, cursos y
 *        opcionalmente origen.
 * @param clsdbadmin $objDB Conexion de base de datos operativa.
 * @param bool $bDebug Indica si debe acumular trazas de depuracion.
 * @return array Error y traza de depuracion.
 */
function f2216_RegistrarMatricula($aParametros, $objDB, $bDebug = false)
{
	//Esta funcion es la que recibe el web service de matricula.
	//--- Modelo Versión 2.24.1 martes, 4 de febrero de 2020 - Se agrega aplazamientos y cancelaciones.
	// --- Julio 15 de 2022 - No se permiten mas aplazamientos desde RCONT.
	// Agosto 21 de 2024 - Se bloquea el ingreso de estudiantes que no tengan planes de estudio a programas con registro SNIES cerrado
	$sDebug = '';
	$sError = '';
	//{peraca}/{documento}/{programa}/{cead}/{nuevo}/{cursos}
	if (isset($aParametros['peraca']) == 0) {
		$sError = 'No se ha definido un periodo';
	}
	if (isset($aParametros['documento']) == 0) {
		$sError = 'No se ha definido un documento';
	}
	if (isset($aParametros['programa']) == 0) {
		$sError = 'No se ha definido un programa';
	}
	if (isset($aParametros['cead']) == 0) {
		$sError = 'No se ha definido un cead';
	}
	if (isset($aParametros['nuevo']) == 0) {
		$sError = 'No se ha definido el parametro de nuevo';
	}
	if (isset($aParametros['cursos']) == 0) {
		$sError = 'No se han reportado cursos';
	}
	if (isset($aParametros['aplazados']) == 0) {
		$aParametros['aplazados'] = '';
	}
	if (isset($aParametros['cancelados']) == 0) {
		$aParametros['cancelados'] = '';
	}
	if (isset($aParametros['fechamat']) == 0) {
		$aParametros['fechamat'] = 0;
	}
	// El origen historico de esta entrada es WebService (1). Articulacion
	// puede enviarlo de forma explicita mediante el parametro opcional origen.
	$core16origen = 1;
	$bOrigenExplicito = false;
	if (isset($aParametros['origen']) != 0) {
		$core16origen = numeros_validar($aParametros['origen']);
		$bOrigenExplicito = true;
	}
	$core16tercero = 0;
	$core16idescuela = 0;
	$core16idprograma = 0;
	$core16idplanestudio = 0;
	$core16idzona = 0;
	$core16idcead = 0;
	$core16fechamatricula = $aParametros['fechamat'];
	$idPeriodo = $aParametros['peraca'];
	$idProgramaReclasifica = 0;
	$idPeriodoInicioC2 = Id_PeriodoInicioC2();
	if ($sError == '') {
		//Ver si el peraca existe.
		$sSQL = 'SELECT TB.exte02programaasociado, T9.core09codigo 
		FROM exte02per_aca AS TB, core09programa AS T9 
		WHERE TB.exte02id=' . $idPeriodo . ' AND TB.exte02programaasociado=T9.core09id';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			$sError = 'No se ha encontrado el periodo ' . $aParametros['peraca'] . ' [Al intentar guardar la matricula]';
		} else {
			$fila = $objDB->sf($tabla);
			if ($fila['exte02programaasociado'] != 0) {
				$idProgramaReclasifica = $fila['core09codigo'];
			}
		}
	}
	if ($sError == '') {
		$idPrograma = $aParametros['programa'];
		if ($idProgramaReclasifica != 0) {
			$idPrograma = $idProgramaReclasifica;
		}
		$bValidarPEI = false;
		$sSQL = 'SELECT core09idescuela, core09id, cara09nivelformacion, core09idcondicion 
		FROM core09programa 
		WHERE core09codigo=' . $idPrograma . '';
		//Ver el programa.
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			$sError = 'No se ha encontrado el programa ' . $idPrograma . '';
		} else {
			$fila = $objDB->sf($tabla);
			$core16idprograma = $fila['core09id'];
			$core16idescuela = $fila['core09idescuela'];
			switch ($fila['cara09nivelformacion']) {
				case 4: // Tecnologia
				case 2: // Profesional
				case 3: // Especialización
				case 5: // Maestria
				case 6: // Doctorado
					switch ($fila['core09idcondicion']) {
						case 0: // Sin definir.
							$sError = 'No ha sido posible definir la condici&oacute;n de la oferta para el programa ' . $aParametros['programa'] . ',<br> por favor informe al lider de programa. (Modulo Core - Programas - Programas Acad&eacute;micos)';
							break;
						case 1: // En diseño.
							$sError = 'El programa ' . $aParametros['programa'] . ' no esta disponible para aceptar estudiantes. (Condici&oacute;n del programa: En dise&ntilde;o)';
							break;
						case 11: // Oferta nueva
						case 21: // Oferta Consolidada
							break;
						case 31: // Transicion -- ya fue...
						case 51: // Oferta Cerrada
							if ($idPeriodo > $idPeriodoInicioC2) {
								$bValidarPEI = true;
							}
							break;
						case 71: // Oferta SINEP
						case 72: // Oferta SINEC
						case 81: // Convenio SINEP
						case 82: // Convenio SINEC
						case 99: // Convenios
							break;
						default:
							$sError = 'Se ha detectado un error en la Condici&oacute;n del programa ' . $aParametros['programa'] . ', por favor informe al Lider de programa.';
							break;
					}
					break;
			}
		}
	}
	if ($sError == '') {
		//Ver el cead...
		// 2024-12-06 Se agrega cead 1 que aparece en ryc una "Escuela de postgrados" no existente
		$bVerificaCead = true;
		switch ($aParametros['cead']) {
			case 1: // Escuela de posgrados
			case 86100: // CENED
			case 86201: // Bachillerato a Distancia Bogotá
				$core16idzona = 9;
				$core16idcead = 11;
				$bVerificaCead = false;
				break;
			case 81112: // Puerto Colombia
				$core16idzona = 3;
				$core16idcead = 9;
				$bVerificaCead = false;
				break;
		}
		if ($bVerificaCead) {
			$sSQL = 'SELECT unad24idzona, unad24id FROM unad24sede WHERE unad24codigoryc=' . $aParametros['cead'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) == 0) {
				$sError = 'No se ha encontrado el CEAD ' . $aParametros['cead'] . '';
			} else {
				$fila = $objDB->sf($tabla);
				$core16idzona = $fila['unad24idzona'];
				$core16idcead = $fila['unad24id'];
			}
		}
	}
	if ($sError == '') {
		//Ver el tercero...
		$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $aParametros['documento'] . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			unad11_importar_V2($aParametros['documento'], '', $objDB);
			$tabla = $objDB->ejecutasql($sSQL);
		}
		if ($objDB->nf($tabla) == 0) {
			$sError = 'No se ha encontrado el documento ' . $aParametros['documento'] . '';
		} else {
			$fila = $objDB->sf($tabla);
			$core16tercero = $fila['unad11id'];
		}
	}
	if ($sError == '') {
		if ($bValidarPEI) {
			// No se debe permitir estudiantes nuevos.
			$sSQL = 'SELECT core01idplandeestudios 
			FROM core01estprograma 
			WHERE core01idtercero=' . $core16tercero . ' AND core01idprograma=' . $idPrograma . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$core16idplanestudio = $fila['core01idplandeestudios'];
			} else {
				$sError = 'El programa ' . $idPrograma . ' no permite estudiantes nuevos.';
			}
		}
	}
	if ($sError == '') {
		$core16fecharecibido = fecha_DiaMod();
		$core16minrecibido = fecha_MinutoMod();
		if ($core16idplanestudio == 0) {
			//Agosto 18 de 2020 - Intentamos cargar el plan de estudio de una vez.
			$sSQL = 'SELECT core01idplandeestudios FROM core01estprograma 
			WHERE core01idtercero=' . $core16tercero . ' AND core01idprograma=' . $core16idprograma . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$core16idplanestudio = $fila['core01idplandeestudios'];
			} else {
				//Ver que haya un plan de estudios activo para el programa
				if ($idPeriodo > $idPeriodoInicioC2) {
					$sSQL = 'SELECT core10id 
					FROM core10programaversion 
					WHERE core10idprograma=' . $core16idprograma . ' AND core10fechaversion<=' . $core16fecharecibido . ' 
					AND ((core10fechavence>=' . $core16fecharecibido . ') OR (core10fechavence=0)) 
					ORDER BY core10consec DESC';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$core16idplanestudio = $fila['core10id'];
					} else {
						$sError = 'NO se permiten estudiantes nuevos en el programa ' . $idPrograma . ', no se encuentra un plan de estudio vigente.';
					}
				}
			}
		}
	}
	if ($sError == '') {
		if ($core16idprograma == 450) {
			$sError = 'No es posible hacer matricula sobre el programa 450.';
		}
	}
	if ($sError == '') {
		//Si no existe insertarlo.
		$sCondi = ' AND core16idprograma=' . $core16idprograma . '';
		// AND core16tipomatricula=0
		//Pilas, periodos importados simplemente habia una matricula estudiante - periodo.
		if ($idPeriodo < 761) {
			$sCondi = '';
		}
	$sSQL = 'SELECT core16id, core16idprograma, core16idplanestudio, core16procesado, core16parametros, core16idcead, core16idzona, core16nuevo, core16aplazados, core16cancelados, core16fechamatricula, core16origen 
		FROM core16actamatricula 
		WHERE core16tercero=' . $core16tercero . ' AND core16peraca=' . $aParametros['peraca'] . $sCondi . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			$core16id = tabla_consecutivo('core16actamatricula', 'core16id', '', $objDB);
			$sCampos2216 = 'core16peraca, core16tercero, core16idprograma, core16idplanestudio, core16id, core16idcead, 
			core16parametros, core16idescuela, core16idzona, core16fecharecibido, core16minrecibido, 
			core16procesado, core16numcursos, core16numaprobados, core16promedio, core16origen, 
			core16nuevo, core16aplazados, core16cancelados, core16fechamatricula';
			$sValores2216 = '' . $aParametros['peraca'] . ', ' . $core16tercero . ', ' . $core16idprograma . ', ' . $core16idplanestudio . ', ' . $core16id . ', ' . $core16idcead . ', 
			"' . $aParametros['cursos'] . '", ' . $core16idescuela . ', ' . $core16idzona . ', ' . $core16fecharecibido . ', ' . $core16minrecibido . ', 
			0, 0, 0, 0, ' . $core16origen . ', 
			' . $aParametros['nuevo'] . ', "' . $aParametros['aplazados'] . '", "' . $aParametros['cancelados'] . '", ' . $core16fechamatricula . '';
			$sSQL = 'INSERT INTO core16actamatricula (' . $sCampos2216 . ') VALUES (' . $sValores2216 . ');';
			//$sdetalle=$sCampos2216.'['.$sValores2216.']';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = 'Se ha producido un error al intentar registrar la matricula, por favor intente mas tarde.';
			} else {
				$idUsuarioProceso = Id_Unadito();
				if (isset($_SESSION['unad_id_tercero']) != 0) {
					$idUsuarioProceso = $_SESSION['unad_id_tercero'];
				}
				seg_auditar(2216, $idUsuarioProceso, 2, $core16id, $sValores2216, $objDB);
			}
		} else {
			$fila = $objDB->sf($tabla);
			if ($fila['core16procesado'] == 0) {
				$sOrigen = '';
				if ($bOrigenExplicito && ($fila['core16origen'] != $core16origen)) {
					$sOrigen = ', core16origen=' . $core16origen;
				}
				$sSQL = 'UPDATE core16actamatricula SET core16parametros="' . $aParametros['cursos'] . '", 
				core16aplazados="' . $aParametros['aplazados'] . '", core16cancelados="' . $aParametros['cancelados'] . '", 
				core16idcead=' . $core16idcead . ', core16idzona=' . $core16idzona . ', 
				core16idprograma=' . $core16idprograma . ', core16idplanestudio=' . $core16idplanestudio . ', 
				core16fecharecibido=' . $core16fecharecibido . ', core16minrecibido=' . $core16minrecibido . ', 
				core16nuevo=' . $aParametros['nuevo'] . ', core16fechamatricula=' . $core16fechamatricula . $sOrigen . ' 
				WHERE core16id=' . $fila['core16id'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = 'Se ha producido un error al intentar registrar la matricula, por favor intente mas tarde.';
				} else {
					$idUsuarioProceso = Id_Unadito();
					if (isset($_SESSION['unad_id_tercero']) != 0) {
						$idUsuarioProceso = $_SESSION['unad_id_tercero'];
					}
					seg_auditar(2216, $idUsuarioProceso, 3, $fila['core16id'], $sSQL, $objDB);
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' Insertando la matricula ' . $sSQL);
					}
				}
				//$sError=$sSQL;
			} else {
				//$sError='El registro ya se encuentra procesado.';
				//inicia la edicion.
				$sdatos = '';
				$bDevolverProceso = false;
				if ($fila['core16idcead'] != $core16idcead) {
					$sdatos = 'core16idcead=' . $core16idcead . '';
				}
				if ($fila['core16idprograma'] != $core16idprograma) {
					if ($sdatos != '') {
						$sdatos = $sdatos . ', ';
					}
					$sdatos = 'core16idprograma=' . $core16idprograma . ', core16idplanestudio=' . $core16idplanestudio . '';
				} else {
					if (($fila['core16idplanestudio'] == 0) && ($core16idplanestudio != 0)) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = 'core16idplanestudio=' . $core16idplanestudio . '';
					}
				}
				if ((int)$core16fechamatricula != 0) {
					if ($fila['core16fechamatricula'] != $core16fechamatricula) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . 'core16fechamatricula=' . $core16fechamatricula . '';
					}
				}
				if ($fila['core16parametros'] != $aParametros['cursos']) {
					if ($sdatos != '') {
						$sdatos = $sdatos . ', ';
					}
					$sdatos = $sdatos . 'core16parametros="' . $aParametros['cursos'] . '"';
					$bDevolverProceso = true;
				}
				if ($fila['core16aplazados'] != $aParametros['aplazados']) {
					if ($sdatos != '') {
						$sdatos = $sdatos . ', ';
					}
					$sdatos = $sdatos . 'core16aplazados="' . $aParametros['aplazados'] . '"';
					$bDevolverProceso = true;
				}
				if ($fila['core16cancelados'] != $aParametros['cancelados']) {
					if ($sdatos != '') {
						$sdatos = $sdatos . ', ';
					}
					$sdatos = $sdatos . 'core16cancelados="' . $aParametros['cancelados'] . '"';
					$bDevolverProceso = true;
				}
				if ($fila['core16idzona'] != $core16idzona) {
					if ($sdatos != '') {
						$sdatos = $sdatos . ', ';
					}
					$sdatos = $sdatos . 'core16idzona=' . $core16idzona . '';
				}
				if ($fila['core16nuevo'] != $aParametros['nuevo']) {
					if ($sdatos != '') {
						$sdatos = $sdatos . ', ';
					}
					$sdatos = $sdatos . 'core16nuevo=' . $aParametros['nuevo'] . '';
				}
				if ($bOrigenExplicito && ($fila['core16origen'] != $core16origen)) {
					if ($sdatos != '') {
						$sdatos = $sdatos . ', ';
					}
					$sdatos = $sdatos . 'core16origen=' . $core16origen . '';
				}
				if ($bDevolverProceso) {
					$sdatos = $sdatos . ', core16procesado=0, core16errormatricula=0, core16procagenda=0, core16erroragenda=0';
				}
				if ($sdatos != '') {
					$sSQL = 'UPDATE core16actamatricula SET ' . $sdatos . ' WHERE core16id=' . $fila['core16id'] . '';
					$result = $objDB->ejecutasql($sSQL);
					if ($result == false) {
						$sError = 'Se ha producido un error al intentar registrar la matricula, por favor intente mas tarde.';
					} else {
						$idUsuarioProceso = Id_Unadito();
						if (isset($_SESSION['unad_id_tercero']) != 0) {
							$idUsuarioProceso = $_SESSION['unad_id_tercero'];
						}
						seg_auditar(2216, $idUsuarioProceso, 3, $fila['core16id'], $sSQL, $objDB);
						if ($bDebug) {
							$sDebug = $sDebug . log_debug(' Insertando la matricula ' . $sSQL);
						}
					}
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' Actualizando la matricula ' . $sSQL);
					}
				}
				//completa la edicion
			}
		}
	}
	return array($sError, $sDebug);
}
function f2216_ProcesarMatricula($core16id, $objDB, $objNoConformidad, $bDebug = false)
{
	//14 de Febrero de 2020 - Se incluye la consideración de aplazamientos y cancelaciones.
	//18 de Noviembre de 2020 - Todo lo que sea periodo (Periodo InicioC2) hacia abajo se considera historico.
	//15 de Abril de 2021 - Todo lo que sea del 179 para abajo va al 60 - 40
	//19 de Mayo de 2021 - Se ajusta el numero de creditos en el calculo.
	$sDebug = '';
	$sError = '';
	$iHoy = fecha_DiaMod();
	$core16procesado = 0;
	$core16numcursos = 0;
	$iNumCreditosMatricula = 0;
	$core16errormatricula = 0;
	$core16resultado = 0;
	$cara16numcreditos = 0;
	$cara16numcursosext = 0;
	$cara16numcreditosext = 0;
	$cara16numcursospost = 0;
	$cara16numcreditospost = 0;
	$bExiste = false;
	$idEntidad = Traer_Entidad();
	$idPeriodoInicioC2 = Id_PeriodoInicioC2();
	$sSQL = 'SELECT TB.core16peraca, TB.core16tercero, TB.core16idprograma, TB.core16procesado, TB.core16numcursos, TB.core16nuevo, 
	TB.core16idescuela, TB.core16idzona, TB.core16idcead, TB.core16parametros, TB.core16fechamatricula, TB.core16fecharecibido, 
	TB.core16idcead, TB.core16aplazados, TB.core16cancelados, TB.core16idplanestudio, TB.core16idestprog, TB.cara16numcreditos, 
	TB.cara16numcursosext, TB.cara16numcreditosext, TB.cara16numcursospost, TB.cara16numcreditospost, T22.core22grupo, TB.core16estado 
	FROM core16actamatricula AS TB, core09programa AS T9, core22nivelprograma AS T22 
	WHERE TB.core16id=' . $core16id . ' AND TB.core16tipomatricula IN (0, 1) AND TB.core16idprograma=T9.core09id AND T9.cara09nivelformacion=T22.core22id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila16 = $objDB->sf($tabla);
		$idTercero = $fila16['core16tercero'];
		$idPrograma = $fila16['core16idprograma'];
		$core16idplanestudio = $fila16['core16idplanestudio'];
		$iNuevo = $fila16['core16nuevo'];
		$core16procesado = $fila16['core16procesado'];
		$core16numcursos = $fila16['core16numcursos'];
		$core16peraca = $fila16['core16peraca'];
		if ($fila16['core16fechamatricula'] != 0) {
			$core16fecharecibido = $fila16['core16fechamatricula'];
		} else {
			$core16fecharecibido = $fila16['core16fecharecibido'];
		}
		$core16idcead = $fila16['core16idcead'];
		$iGrupoNivelForma = $fila16['core22grupo'];
		$core16estado = $fila16['core16estado'];
		$core04est_aprob = 3;
		if ($iGrupoNivelForma == 4) {
			$core04est_aprob = 3.5;
		}
		/*
		$cara16numcreditos=$fila16['cara16numcreditos'];
		$cara16numcursosext=$fila16['cara16numcursosext'];
		$cara16numcreditosext=$fila16['cara16numcreditosext'];
		$cara16numcursospost=$fila16['cara16numcursospost'];
		$cara16numcreditospost=$fila16['cara16numcreditospost'];
		*/
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Iniciando proceso de matricula del registro ' . $core16id);
		}
		list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
		$sTabla04 = 'core04matricula_' . $idContTercero;
		$sTabla05 = 'core05actividades_' . $idContTercero;
		//Primero buscamos cursos huerfanos
		$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04idmatricula=' . $core16id . ' 
		WHERE core04tercero=' . $idTercero . ' AND core04idmatricula=0 AND core04peraca=' . $core16peraca . ' 
		AND core04estado<>1';
		$result = $objDB->ejecutasql($sSQL);
		//Termina de buscar cursos huerfanos.
		if ($core16estado == 99) {
			$sError = 'La matricula se encuentra anulada.';
			//Desconectar los cursos de la matricula
			$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04estado=9 WHERE core04idmatricula=' . $core16id . ' AND core04estado IN (0,2,5,7,8,10)';
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' <b>CANCELANDO CURSOS</b> ' . $sSQL);
			}
			$result = $objDB->ejecutasql($sSQL);
		} else {
			$bExiste = true;
		}
		if ($sError == '') {
			$idContPeriodo = f146_Contenedor($core16peraca, $objDB);
			if ($idContPeriodo == 0) {
				$sError = '';
			}
		}
		if ($sError == '') {
			//Actualizar la tabla de terceros con el ultimo dato de matricula.
			list($iNumProcesados, $sError, $sDebugP) = f111_ProcesarMatricula($idTercero, $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugP;
			//Armar la matricula.
			$iCursos = 0;
			$iAplazados = 0;
			$iCancelados = 0;
			if ($fila16['core16parametros'] != '') {
				$aCursos = explode('@', $fila16['core16parametros']);
				$iCursos = count($aCursos);
			}
			if ($fila16['core16aplazados'] != '') {
				$aAplazados = explode('@', $fila16['core16aplazados']);
				$iAplazados = count($aAplazados);
			}
			if ($fila16['core16cancelados'] != '') {
				$aCancelados = explode('@', $fila16['core16cancelados']);
				$iCancelados = count($aCancelados);
			}
			//Limpiar la casa. (LOS CALIFICADO Y REPORTADOS NO LOS TOCAMOS)
			if ($core16peraca > $idPeriodoInicioC2) {
				$sEstadosNo = '5,7,';
			} else {
				$sEstadosNo = '';
			}
			$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04estado=1 WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $core16peraca . ' AND core04idprograma=' . $idPrograma . ' AND core04estado NOT IN (' . $sEstadosNo . '8)';
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Retiramos la matricula: ' . $sSQL);
			}
			$result = $objDB->ejecutasql($sSQL);
			$iPeso1 = 75;
			$iPeso2 = 25;
			if ($idEntidad == 0) {
				if ($core16peraca < 179) {
					$iPeso1 = 60;
					$iPeso2 = 40;
				} else {
					//$aEstructura=f2400_EstructuraCalificaciones($core16peraca, $fila['core04idcurso'], $objDB);
					//$iPuntosPrimerNota=$aEstructura[0]+$aEstructura[1];
					//$iPuntosSegundaNota=$aEstructura[2];
					//$iNumNotas=$aEstructura[3];
					//$iPorcentajeNota1=round(($aEstructura[0]+$aEstructura[1])/5);
				}
			}
			//Diciembre 3 de 2020 - Como puede estar procesado pero no estar calculada la nota final vamos a procesar la nota nota final
			$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04res75=ROUND(core04nota75/' . $iPeso1 . ', 1), core04res25=ROUND(core04nota25/' . $iPeso2 . ', 1) WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $core16peraca . ' AND core04idprograma=' . $idPrograma . ' AND core04estado=8';
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Procesamos notas parciales: ' . $sSQL);
			}
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04resdef=ROUND(((core04res75*' . $iPeso1 . ')+(core04res25*' . $iPeso2 . '))/100, 1) WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $core16peraca . ' AND core04idprograma=' . $idPrograma . ' AND core04estado=8';
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Procesamos notas finales: ' . $sSQL);
			}
			$result = $objDB->ejecutasql($sSQL);
			//Termina de procesar la nota final
			$core16procesado = 0;
			$core16numcursos = 0;
			$cara16numcreditos = 0;
			$cara16numcursosext = 0;
			$cara16numcreditosext = 0;
			$cara16numcursospost = 0;
			$cara16numcreditospost = 0;
			$iEstadoOferta = 0;
			$sIdsCursosMatricula = '-99';
			//Ahora si la matricula.
			for ($k = 1; $k <= $iCursos; $k++) {
				$sCodCurso = $aCursos[$k - 1];
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Analizando curso '.$sCodCurso.'<br>';}
				$sErrorCurso = '';
				$sIdsCursosMatricula = $sIdsCursosMatricula . ',' . $sCodCurso;
				$core04cursoequivalente = 0;
				$sCodRevisa = $sCodCurso;
				//if ($sCodCurso=='700004'){$core04cursoequivalente='700001';}
				if ($core04cursoequivalente != 0) {
					$sCodRevisa = $core04cursoequivalente;
				}
				$idOrigenMatricula = 0; //Curso propio, para cursos externo (UNAD florida) se usa $idOrigenMatricula=2
				if ($core16peraca > $idPeriodoInicioC2) {
					//Ver que este ofertado
					$sSQL = 'SELECT TB.ofer08estadooferta, TB.ofer08estadocampus, TB.ofer08idagenda, TB.ofer08idnav, 
					TB.ofer08idcursonav, TB.ofer08idescuela, T40.unad40modocalifica, T40.unad40tipoexterno, 
					TB.ofer08modocalifica, TB.ofer08tipocalifica 
					FROM ofer08oferta AS TB, unad40curso AS T40
					WHERE TB.ofer08idcurso="' . $sCodRevisa . '" AND TB.ofer08idper_aca=' . $core16peraca . ' AND TB.ofer08cead=0 
					AND TB.ofer08idcurso=T40.unad40id';
					$tabla08 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla08) == 0) {
						//El curso no esta ofertado...
						//Saber si el curso existe, no vaya y sea un error de codigo
						$sSQL = 'SELECT unad40idescuela FROM unad40curso WHERE unad40id="' . $sCodCurso . '"';
						$tabla08 = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla08) == 0) {
							$sErrorCurso = 'No se encuentra el curso con codigo [' . $sCodCurso . ']';
						} else {
							$sErrorCurso = 'El curso ' . $sCodCurso . ' no ha sido ofertado';
							$fila = $objDB->sf($tabla08);
							$idEscuela = $fila['unad40idescuela'];
							//Si existe el curso, poner la no conformidad.
							$sSQL = 'SELECT unae03id FROM unae03noconforme WHERE unae03curso=' . $sCodCurso . ' AND unae03peraca=' . $core16peraca . ' AND unae03idproceso=2216';
							$tabla08 = $objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla08) == 0) {
								//Insertar la no conformidad.
								$objNoConformidad->nuevo(2216);
								$objNoConformidad->unae03peraca = $core16peraca;
								$objNoConformidad->unae03curso = $sCodCurso;
								$objNoConformidad->unae03escuela = $idEscuela;
								$objNoConformidad->unae03idresponsable = 0;
								$objNoConformidad->unae03idautoriza = 0;
								$objNoConformidad->guardar2($objDB, false);
								$sErrorL = $objNoConformidad->sError;
								if ($sErrorL != '') {
									$sErrorCurso = $sErrorCurso . ' - ' . $sErrorL;
								}
							}
						}
					} else {
						//Puede pasar que la oferta este cancelada o que el curso no este acreditado o certificado... (Aunque esto solo afecta la agenda)
						$fila = $objDB->sf($tabla08);
						if ($bDebug) {
							$sDebug = $sDebug . log_debug(' Revisando oferta del curso ' . $sCodRevisa);
						}
						$iEstadoOferta = $fila['ofer08estadocampus'];
						//Mayo 16 de 2023, La configuración de cursos externo se toma desde la oferta y no desde el curso.
						if ($fila['ofer08modocalifica'] == 9) {
							$idOrigenMatricula = 2;
							if ($fila['unad40tipoexterno'] == 2) {
								//Es una certificación se maneja de la misma manera.
								$idOrigenMatricula = 0;
							}
						}
						$ofer08idagenda = 0;
						$ofer08idnav = 0;
						$core04idmoodle = 0;
						switch ($iEstadoOferta) {
							case 10: //Certificado
							case 12: //Acreditado
							case 16: //Excepcion de alistamiento
								$ofer08idagenda = $fila['ofer08idagenda'];
								$ofer08idnav = $fila['ofer08idnav'];
								$core04idmoodle = $fila['ofer08idcursonav'];
								break;
						}
						if ($fila['ofer08estadooferta'] != 1) {
							$idEscuela = $fila['ofer08idescuela'];
							$sErrorCurso = 'La oferta del curso ' . $sCodRevisa . ' esta cancelada.';
							//Si esta cancelada, poner la no conformidad.
							$sSQL = 'SELECT unae03id FROM unae03noconforme WHERE unae03curso=' . $sCodCurso . ' AND unae03peraca=' . $core16peraca . ' AND unae03idproceso=2217';
							$tabla08 = $objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla08) == 0) {
								//Insertar la no conformidad.
								$objNoConformidad->nuevo(2217);
								$objNoConformidad->unae03peraca = $core16peraca;
								$objNoConformidad->unae03curso = $sCodCurso;
								$objNoConformidad->unae03escuela = $idEscuela;
								$objNoConformidad->unae03idresponsable = 0;
								$objNoConformidad->unae03idautoriza = 0;
								$objNoConformidad->guardar2($objDB, false);
								$sErrorL = $objNoConformidad->sError;
								if ($sErrorL != '') {
									$sErrorCurso = $sErrorCurso . ' - ' . $sErrorL;
								}
							}
						}
					}
				} else {
					//ES un historico. no se revisa la oferta.
					$ofer08idagenda = -1;
					$ofer08idnav = -1;
					$core04idmoodle = -1;
					$idOrigenMatricula = 8;
				}
				if ($sErrorCurso == '') {
					$idBase = 0;
					//Agregar el curso a la matricula.
					$sSQL = 'SELECT core04id, core04estado, core04idprograma, core04calificado, core04idtutor, core04idgrupo, core04calificado 
					FROM ' . $sTabla04 . ' 
					WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $core16peraca . ' AND core04idcurso=' . $sCodCurso . '';
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' Vemos si ya existe el registro de matricula: ' . $sSQL);
					}
					$result = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($result) == 0) {
						$core04id = tabla_consecutivo($sTabla04, 'core04id', '', $objDB);
						$sSQL = 'INSERT INTO ' . $sTabla04 . ' (core04peraca, core04tercero, core04idcurso, core04id, core04idrol, 
						core04idaula, core04fechamatricula, core04idcead, core04tienenota, core04idagenda, 
						core04idnav, core04idprograma, core04nuevo, core04cursoequivalente, core04idmoodle, 
						core04idmatricula, core04estado, core04est_aprob) 
						VALUES (' . $core16peraca . ', ' . $idTercero . ', ' . $sCodCurso . ', ' . $core04id . ', 5, 
						0, ' . $core16fecharecibido . ', ' . $core16idcead . ', 1, ' . $ofer08idagenda . ', 
						' . $ofer08idnav . ', ' . $idPrograma . ', ' . $iNuevo . ', ' . $core04cursoequivalente . ', ' . $core04idmoodle . ', 
						' . $core16id . ', ' . $idOrigenMatricula . ', ' . $core04est_aprob . ')';
						//$core04id++;
						if ($bDebug) {
							$sDebug = $sDebug . log_debug(' Insersion del curso ' . $sSQL);
						}
					} else {
						$fila04 = $objDB->sf($result);
						$idBase = $fila04['core04id'];
						$sAdd = ', core04idagenda=' . $ofer08idagenda . ', core04idnav=' . $ofer08idnav . ', core04idmoodle=' . $core04idmoodle . '';
						if ($fila04['core04estado'] == 1) {
							$iEstadoFinal = $idOrigenMatricula;
							// 7 de Junio de 2020 - Puede suceder que el curso se inactivo por alguna razón pero ya esta calificado
							if ($fila04['core04calificado'] == 500) {
								if ($fila04['core04idtutor'] != 0) {
									// Ubicar si el profesor del grupo ya califico para pasarlo al estado que le corresponde...
									$iNumActividades = 0;
									$iReportadas = 0;
									$sSQL = 'SELECT ceca02idestado FROM ceca02actividadtutor_' . $idContPeriodo . ' WHERE ceca02idtutor=' . $fila04['core04idtutor'] . ' AND ceca02idperaca=' . $core16peraca . ' AND ceca02idcurso=' . $sCodCurso . '';
									if ($bDebug) {
										$sDebug = $sDebug . log_debug(' <b>Analizando actividades en el curso</b>: ' . $sSQL);
									}
									$tabla2302 = $objDB->ejecutasql($sSQL);
									while ($fila2302 = $objDB->sf($tabla2302)) {
										$iNumActividades++;
										if ($fila2302['ceca02idestado'] == 8) {
											$iReportadas++;
										}
									}
									if ($iNumActividades > 0) {
										if ($iNumActividades == $iReportadas) {
											$iEstadoFinal = 8;
										}
									}
								}
							}
							$sAdd = $sAdd . ', core04estado=' . $iEstadoFinal;
						}
						if ($fila04['core04idprograma'] != $idPrograma) {
							$sAdd = $sAdd . ', core04idprograma=' . $idPrograma . '';
						}
						if ($core16peraca < 761) {
							if ($fila04['core04calificado'] != 500) {
								$sAdd = $sAdd . ', core04calificado=0';
							}
						}
						$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04nuevo=' . $iNuevo . '' . $sAdd . ' WHERE core04id=' . $idBase . '';
						if ($bDebug) {
							$sDebug = $sDebug . log_debug(' Actualizar el curso ' . $sSQL);
						}
					}
					$result = $objDB->ejecutasql($sSQL);
					if ($core16peraca > $idPeriodoInicioC2) {
					} else {
						//Por si las dudas limpiamos la tabla 05 para que no haya sobras..
						if ($idBase > 0) {
							$sSQL = 'DELETE FROM ' . $sTabla05 . ' WHERE core05idmatricula=' . $idBase . '';
							$result = $objDB->ejecutasql($sSQL);
						}
					}
					if ($result == false) {
						$sErrorCurso = 'Error al intentar insertar el curso ' . $sCodCurso . ' (Por favor informe al administrador del sistema.)<!-- ' . $sSQL . ' --> ';
					} else {
						//$core16numcursos++;
					}
				}
				if ($sErrorCurso != '') {
					if ($sError != '') {
						$sError = $sError . ', ';
					}
					$sError = $sError . $sErrorCurso;
				}
			}
			//Revisar cuantos creditos quedaron matriculados.
			if ($sIdsCursosMatricula != '-99') {
				/*
				$sSQL='SELECT unad40numcreditos FROM unad40curso WHERE unad40id IN ('.$sIdsCursosMatricula.')';
				$tabla40=$objDB->ejecutasql($sSQL);
				while($fila40=$objDB->sf($tabla40)){
					$iNumCreditosMatricula=$iNumCreditosMatricula+$fila40['unad40numcreditos'];
					}
				*/
			}
			//Ahora los aplazados
			for ($k = 1; $k <= $iAplazados; $k++) {
				$sCodCurso = $aAplazados[$k - 1];
				if ($bDebug) {
					$sDebug = $sDebug . log_debug(' Aplazando curso ' . $sCodCurso);
				}
				$sErrorCurso = '';
				$core04cursoequivalente = 0;
				$sCodRevisa = $sCodCurso;
				if ($sCodCurso == '700004') {
					$core04cursoequivalente = '700001';
				}
				if ($core04cursoequivalente != 0) {
					$sCodRevisa = $core04cursoequivalente;
				}
				$ofer08idagenda = 0;
				$ofer08idnav = 0;
				$core04idmoodle = 0;
				$idBase = 0;
				if ($sErrorCurso == '') {
					//Agregar el curso a la matricula.
					$sSQL = 'SELECT core04id FROM ' . $sTabla04 . ' WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $core16peraca . ' AND core04idcurso=' . $sCodCurso . '';
					$result = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($result) == 0) {
						$core04id = tabla_consecutivo($sTabla04, 'core04id', '', $objDB);
						$sSQL = 'INSERT INTO ' . $sTabla04 . ' (core04peraca, core04tercero, core04idcurso, core04id, core04idrol, 
						core04idaula, core04fechamatricula, core04idcead, core04tienenota, core04idagenda, 
						core04idnav, core04idprograma, core04nuevo, core04cursoequivalente, core04idmoodle, 
						core04idmatricula, core04estado, core04resultado) 
						VALUES (' . $core16peraca . ', ' . $idTercero . ', ' . $sCodCurso . ', ' . $core04id . ', 5, 
						0, ' . $core16fecharecibido . ', ' . $core16idcead . ', 0, ' . $ofer08idagenda . ', 
						' . $ofer08idnav . ', ' . $idPrograma . ', ' . $iNuevo . ', ' . $core04cursoequivalente . ', ' . $core04idmoodle . ', 
						' . $core16id . ', 10, 10)';
						//$core04id++;
					} else {
						$fila04 = $objDB->sf($result);
						$idBase = $fila04['core04id'];
						$sAdd = ', core04idagenda=0, core04idnav=0, core04idmoodle=0, core04idtutor=0, core04idgrupo=0, core04idaula=0, core04aplicoagenda=0';
						$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04estado=10, core04resultado=10' . $sAdd . ', core04idprograma=' . $idPrograma . ', core04nuevo=' . $iNuevo . ' WHERE core04id=' . $idBase . '';
					}
					$result = $objDB->ejecutasql($sSQL);
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' Insersion del curso ' . $sSQL);
					}
					if ($result == false) {
						$sErrorCurso = 'Error al intentar insertar el curso ' . $sCodCurso . ' (Por favor informe al administrador del sistema.)<!-- ' . $sSQL . ' --> ';
					} else {
						if ($idBase != 0) {
							//Por si las dudas borrar las actividades.
							$sSQL = 'DELETE FROM ' . $sTabla05 . ' WHERE core05idmatricula=' . $idBase . '';
							$result = $objDB->ejecutasql($sSQL);
						}
					}
				}
				if ($sErrorCurso != '') {
					if ($sError != '') {
						$sError = $sError . ', ';
					}
					$sError = $sError . $sErrorCurso;
				}
			}
			//Ahora los cancelados.
			for ($k = 1; $k <= $iCancelados; $k++) {
				$sCodCurso = $aCancelados[$k - 1];
				if ($bDebug) {
					$sDebug = $sDebug . log_debug(' Aplazando curso ' . $sCodCurso);
				}
				$sErrorCurso = '';
				$core04cursoequivalente = 0;
				$sCodRevisa = $sCodCurso;
				if ($sCodCurso == '700004') {
					$core04cursoequivalente = '700001';
				}
				if ($core04cursoequivalente != 0) {
					$sCodRevisa = $core04cursoequivalente;
				}
				$ofer08idagenda = 0;
				$ofer08idnav = 0;
				$core04idmoodle = 0;
				$idBase = 0;
				if ($sErrorCurso == '') {
					//Agregar el curso a la matricula.
					$sSQL = 'SELECT core04id FROM ' . $sTabla04 . ' WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $core16peraca . ' AND core04idcurso=' . $sCodCurso . '';
					$result = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($result) == 0) {
						$core04id = tabla_consecutivo($sTabla04, 'core04id', '', $objDB);
						$sSQL = 'INSERT INTO ' . $sTabla04 . ' (core04peraca, core04tercero, core04idcurso, core04id, core04idrol, 
						core04idaula, core04fechamatricula, core04idcead, core04tienenota, core04idagenda, 
						core04idnav, core04idprograma, core04nuevo, core04cursoequivalente, core04idmoodle, 
						core04idmatricula, core04estado, core04resultado) 
						VALUES (' . $core16peraca . ', ' . $idTercero . ', ' . $sCodCurso . ', ' . $core04id . ', 5, 
						0, ' . $core16fecharecibido . ', ' . $core16idcead . ', 0, ' . $ofer08idagenda . ', 
						' . $ofer08idnav . ', ' . $idPrograma . ', ' . $iNuevo . ', ' . $core04cursoequivalente . ', ' . $core04idmoodle . ', 
						' . $core16id . ', 9, 9)';
						//$core04id++;
					} else {
						$fila04 = $objDB->sf($result);
						$idBase = $fila04['core04id'];
						$sAdd = ', core04idagenda=0, core04idnav=0, core04idmoodle=0, core04idtutor=0, core04idgrupo=0, core04idaula=0, core04aplicoagenda=0';
						$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04estado=9, core04resultado=9' . $sAdd . ', core04idprograma=' . $idPrograma . ', core04nuevo=' . $iNuevo . ' WHERE core04id=' . $idBase . '';
					}
					$result = $objDB->ejecutasql($sSQL);
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' Insersion del curso ' . $sSQL);
					}
					if ($result == false) {
						$sErrorCurso = 'Error al intentar insertar el curso ' . $sCodCurso . ' (Por favor informe al administrador del sistema.)<!-- ' . $sSQL . ' --> ';
					} else {
						if ($idBase != 0) {
							//Por si las dudas borrar las actividades.
							$sSQL = 'DELETE FROM ' . $sTabla05 . ' WHERE core05idmatricula=' . $idBase . '';
							$result = $objDB->ejecutasql($sSQL);
						}
					}
				}
				if ($sErrorCurso != '') {
					if ($sError != '') {
						$sError = $sError . ', ';
					}
					$sError = $sError . $sErrorCurso;
				}
			}
			//Termina de registrar los cursos.
			if ($sError == '') {
				$core16procesado = $iHoy;
			} else {
				$core16errormatricula = 1;
			}
			//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Registro de estudiante '.$sSQL.'<br>';}
			//Calcular el total de matricula.
			$sSQL = 'SELECT TB.core04idcurso, T40.unad40numcreditos, T40.unad40modocalifica 
			FROM ' . $sTabla04 . ' AS TB, unad40curso AS T40 
			WHERE TB.core04tercero=' . $idTercero . ' AND TB.core04peraca=' . $core16peraca . ' AND TB.core04estado IN (0,2,5,7,8,11,12,13)
			AND TB.core04idcurso=T40.unad40id';
			$tablac = $objDB->ejecutasql($sSQL);
			while ($filac = $objDB->sf($tablac)) {
				if ($filac['unad40modocalifica'] == 9) {
					$cara16numcursosext++;
					$cara16numcreditosext = $cara16numcreditosext + $filac['unad40numcreditos'];
				} else {
					if ($iGrupoNivelForma == 4) {
						$cara16numcursospost++;
						$cara16numcreditospost = $cara16numcreditospost + $filac['unad40numcreditos'];
					} else {
						$core16numcursos++;
						$cara16numcreditos = $cara16numcreditos + $filac['unad40numcreditos'];
					}
				}
			}
			//Se termina el proceso
			$sAdd = '';
			$sEstado = ', core16estado=7';
			if ($core16procesado != 0) {
				if (($core16numcursos + $cara16numcursosext + $cara16numcursospost) == 0) {
					$sAdd = ', core16procagenda=' . $core16procesado . ', core16erroragenda=0';
				}
			}
			if (($core16numcursos + $cara16numcursosext + $cara16numcursospost) == 0) {
				if ($iAplazados > 0) {
					$sAdd = $sAdd . ', core16nuevo=8';
					$sEstado = ', core16estado=8';
					$core16resultado = 10;
				} else {
					if ($iCancelados > 0) {
						$sAdd = $sAdd . ', core16nuevo=9';
						$sEstado = ', core16estado=9';
						$core16resultado = 9;
					}
				}
			}
			//cara16numcreditos='.$iNumCreditosMatricula.', 
			$sSQL = 'UPDATE core16actamatricula SET core16procesado=' . $core16procesado . ', core16numcursos=' . $core16numcursos . ', 
			cara16numcreditos=' . $cara16numcreditos . ', cara16numcursosext=' . $cara16numcursosext . ', 
			cara16numcreditosext=' . $cara16numcreditosext . ', cara16numcursospost=' . $cara16numcursospost . ', 
			cara16numcreditospost=' . $cara16numcreditospost . ', 
			cara16numaplazados=' . $iAplazados . ', cara16numcancelados=' . $iCancelados . ', 
			core16errormatricula=' . $core16errormatricula . $sEstado . $sAdd . ', core16resultado=' . $core16resultado . ' 
			WHERE core16id=' . $core16id . '';
			$result = $objDB->ejecutasql($sSQL);
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Procesar Matricula: Resultado ' . $sSQL);
			}
		}
	} else {
		$sError = 'No ha sido posible encontrar el registro de la matricula.';
	}
	if ($bExiste) {
		//Actualizamos la fecha de la ultima matricula
		if ($core16idplanestudio == 0) {
			$sSQL = 'core01idestado NOT IN (-2, 11, 12, 98, 99)';
		} else {
			$sSQL = 'core01idplandeestudios=' . $core16idplanestudio . '';
		}
		$sSQL = 'SELECT core01id, core01fechaultmatricula, core01idplandeestudios 
		FROM core01estprograma WHERE core01idtercero=' . $idTercero . ' AND core01idprograma=' . $idPrograma . ' AND ' . $sSQL;
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Datos del PLAN DE ESTUDIOS ' . $sSQL);
		}
		$tabla01 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla01) == 0) {
			if ($core16idplanestudio != 0) {
				$sSQL = 'SELECT core01id, core01fechaultmatricula, core01idplandeestudios 
				FROM core01estprograma WHERE core01idtercero=' . $idTercero . ' AND core01idprograma=' . $idPrograma . ' AND core01idestado NOT IN (-2, 11, 12, 98, 99)';
				$tabla01 = $objDB->ejecutasql($sSQL);
			}
		}
		if ($objDB->nf($tabla01) == 0) {
			list($core01id, $sErrorE, $sDebugE) = f2201_IniciarEstudiante($idTercero, $idPrograma, $core16idplanestudio, $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugE;
			$tabla01 = $objDB->ejecutasql($sSQL);
		}
		if ($objDB->nf($tabla01) > 0) {
			$fila01 = $objDB->sf($tabla01);
			$sActualiza = '';
			if ($core16idplanestudio == 0) {
				if ($fila01['core01idplandeestudios'] != 0) {
					$core16idplanestudio = $fila01['core01idplandeestudios'];
					$sActualiza = 'core16idplanestudio=' . $core16idplanestudio . '';
				}
			}
			//Ahora actualizamos la relacion estudiante matricula.
			if ($fila16['core16idestprog'] == 0) {
				if ($sActualiza != '') {
					$sActualiza = $sActualiza . ', ';
				}
				$sActualiza = $sActualiza . 'core16idestprog=' . $fila01['core01id'] . '';
			}
			if ($sActualiza != '') {
				$sSQL = 'UPDATE core16actamatricula SET ' . $sActualiza . ' WHERE core16id=' . $core16id . '';
				if ($bDebug) {
					$sDebug = $sDebug . log_debug(' Actualizando matricula ' . $sSQL);
				}
				$result = $objDB->ejecutasql($sSQL);
			}
			//Ver la fecha de ultima matricula.
			list($iFecha, $sDebugM) = f2201_FechaUltimaMatricula($fila01['core01id'], $objDB, $bDebug);
			list($sErrorM, $sDebugM, $iResultado, $iNumMatriculas) = f2201_AnalizarContinuidad($fila01['core01id'], $objDB, $bDebug);
		}
	}
	return array($core16procesado, $core16numcursos, $sError, $sDebug, $core16resultado);
}
function f2216_IniciarGruposPeriodo($idPeriodo, $objDB, $bDebug = false)
{
	$sDebug = '';
	//Limpiar la tabla temporal.
	$sSQL = 'DELETE FROM core96tmp WHERE core96peraca=' . $idPeriodo . '';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Limpiando tabla temporal: ' . $sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	//Hacer el inseto
	$sSQL = 'INSERT INTO core96tmp (core96doc, core96curso, core96grupo, core96role, core96peraca, core96idtercero)
	SELECT idnumber, SUBSTRING(grupo, 1, LOCATE("_", grupo)-1) AS curso, SUBSTRING(grupo, LOCATE("_", grupo)+1) AS idgrupo, role, peraca, 0 
	FROM sw_edu_enrollment_final 
	WHERE peraca=' . $idPeriodo . ' AND idnumber<>0 AND role IN (3,4,5) AND LOCATE("_", grupo)>0
	GROUP BY idnumber, SUBSTRING(grupo, 1, LOCATE("_", grupo)-1), SUBSTRING(grupo, LOCATE("_", grupo)+1), role, peraca';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Armando tabla temporal: ' . $sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	$sSQL = 'UPDATE core96tmp AS TB, unad11terceros AS T1 SET TB.core96idtercero=T1.unad11id WHERE TB.core96peraca=' . $idPeriodo . ' AND TB.core96idtercero=0 AND TB.core96doc=T1.unad11doc';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Actualizando terceros en la temporal: ' . $sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	//Habilitar el armado de las agendas para los que fallaron. (segundo plano)
	$sSQL = 'UPDATE core16actamatricula SET core16erroragenda=0 WHERE core16peraca=' . $idPeriodo . ' AND core16procagenda=0 AND core16erroragenda<>0';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' habilitando la armada de agendas en segundo plano: ' . $sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	//Revisar los documentos que no han sido importados.
	$sSQL = 'SELECT core96doc FROM core96tmp WHERE core96idtercero=0 GROUP BY core96doc';
	return array($sDebug);
}
/**
 * Crea la configuración inicial de aulas para una oferta académica.
 * Consulta ofer08oferta, core00params y core37aulas; conserva las aulas existentes
 * y registra en unad48cursoaula la fotografía de estudiantes por grupo y sus rangos.
 * La configuración solo se aplica al crear cada aula y no reemplaza rangos existentes.
 * @param int $idPeriodo Periodo de la oferta.
 * @param int $idCurso Curso de la oferta.
 * @param object $objDB Conexión de base de datos operativa.
 * @param bool $bDebug Indica si se genera traza de depuración.
 * @param int $idContPeriodo Contenedor del periodo, conservado por compatibilidad.
 * @return array Error y traza de depuración.
 */
function f2216_IniciarAulas($idPeriodo, $idCurso, $objDB, $bDebug = false, $idContPeriodo = 0)
{
	$sError = '';
	$sDebug = '';
	if ($sError == '') {
		$sSQL = 'SELECT T8.ofer08id, T8.ofer08estadooferta, T8.ofer08idnav, T8.ofer08idcursonav, T8.ofer08numestudiantes, T1.unad40titulo, T2.exte02fechaini60, T2.exte02fechafinhabilitacion 
		FROM ofer08oferta AS T8, unad40curso AS T1, exte02per_aca AS T2 
		WHERE T8.ofer08idper_aca=' . $idPeriodo . ' AND T8.ofer08idcurso=' . $idCurso . ' AND T8.ofer08cead=0 AND T8.ofer08idcurso=T1.unad40id AND T8.ofer08idper_aca=T2.exte02id';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Revisando la oferta: ' . $sSQL);
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$ofer08id = $fila['ofer08id'];
			$idNav = $fila['ofer08idnav'];
			$idMoodle = $fila['ofer08idcursonav'];
			$iNumEstudiantesOferta = $fila['ofer08numestudiantes'];
			$sNombreCurso = $fila['unad40titulo'];
			$iFechaInicial = $fila['exte02fechaini60'];
			$iFechaFinal = $fila['exte02fechafinhabilitacion'];
			if ($fila['ofer08estadooferta'] != 1) {
				$sError = 'El curso ' . $idCurso . ' no se encuentra ofertado en el periodo ' . $idPeriodo . '';
			}
		} else {
			$sError = 'No se ha encontrado registro de la oferta del curso ' . $idCurso . ' en el periodo ' . $idPeriodo . '';
		}
	}
	if ($sError == '') {
		if ($idContPeriodo == 0) {
			$idContPeriodo = f146_Contenedor($idPeriodo, $objDB);
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT core00numestaula, core00numestgrupo FROM core00params WHERE core00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Consultando configuración inicial de aulas y grupos: ' . $sSQL);
		}
		$tabla00 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla00) > 0) {
			$fila00 = $objDB->sf($tabla00);
			$iNumEstudiantesAula = (int)$fila00['core00numestaula'];
			$iNumEstudiantesGrupo = (int)$fila00['core00numestgrupo'];
			if (($iNumEstudiantesAula < 1) || ($iNumEstudiantesGrupo < 1)) {
				$sError = 'La configuración de estudiantes por aula y por grupo debe ser mayor que cero.';
			}
		} else {
			$sError = 'No se ha encontrado la configuración general de aulas y grupos.';
		}
	}
	if ($sError == '') {
		$sIdent = f2237_ArregloAulas($objDB, false, true);
		if (!isset($sIdent[1])) {
			$sError = 'No se ha encontrado el aula 1 en el catálogo de aulas.';
		}
	}
	if ($sError == '') {
		$iGruposPorAula = (int)ceil($iNumEstudiantesAula / $iNumEstudiantesGrupo);
		if ($iGruposPorAula < 1) {
			$iGruposPorAula = 1;
		}
		$iNumAulas = (int)ceil($iNumEstudiantesOferta / $iNumEstudiantesAula);
		if ($iNumAulas < 1) {
			$iNumAulas = 1;
		}
		if ($iNumAulas > count($sIdent)) {
			$sError = 'La configuración requiere ' . $iNumAulas . ' aulas, pero el catálogo solo dispone de ' . count($sIdent) . ' aulas tipo 0.';
		}
	}
	if ($sError == '') {
		$unad40fechacreaccion = fecha_DiaMod();
		$unad48id = tabla_consecutivo('unad48cursoaula', 'unad48id', '', $objDB);
		$sCampos148 = 'unad48idcurso, unad48per_aca, unad48consec, unad48id, unad48identificador, unad48numestudiantes, unad48diainicial, unad48idnav, unad48idcursonav, unad48aliasmoodle, unad40fechacreaccion, unad48disponibledesde, unad48disponiblehasta, unad48numestgrupo, unad48grupobase, unad48grupotope';
		ksort($sIdent, SORT_NUMERIC);
		$iAula = 0;
		$iGrupoBase = 1;
		foreach ($sIdent as $iConsecAula => $unad48identificador) {
			$iAula++;
			if ($iAula > $iNumAulas) {
				break;
			}
			$sErrLinea = '';
			$unad48idcursonav = 0;
			if ($iConsecAula == 1) {
				$unad48idcursonav = $idMoodle;
			}
			$unad48numestudiantes = 0;
			$unad48diainicial = 0;
			$unad48idnav = $idNav;
			$unad48aliasmoodle = $sNombreCurso . $unad48identificador . '_' . $idPeriodo;
			$unad48disponibledesde = $iFechaInicial;
			$unad48disponiblehasta = $iFechaFinal;
			$unad48grupotope = $iGrupoBase + $iGruposPorAula - 1;
			$sSQL = 'SELECT unad48id FROM unad48cursoaula WHERE unad48idcurso=' . $idCurso . ' AND unad48per_aca=' . $idPeriodo . ' AND unad48consec=' . $iConsecAula . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Verificando que exista el aula: ' . $sSQL);
		}
			$tabla48 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla48) == 0) {
				$svalores = '' . $idCurso . ', ' . $idPeriodo . ', ' . $iConsecAula . ', ' . $unad48id . ', "' . $unad48identificador . '", ' . $unad48numestudiantes . ', ' . $unad48diainicial . ', ' . $unad48idnav . ', ' . $unad48idcursonav . ', "' . $unad48aliasmoodle . '", ' . $unad40fechacreaccion . ', ' . $unad48disponibledesde . ', ' . $unad48disponiblehasta . ', ' . $iNumEstudiantesGrupo . ', ' . $iGrupoBase . ', ' . $unad48grupotope . '';
				$sSQL = 'INSERT INTO unad48cursoaula (' . $sCampos148 . ') VALUES (' . $svalores . ');';
				if ($bDebug) {
					$sDebug = $sDebug . log_debug(' Insertando el aula: ' . $sSQL);
				}
				$result = $objDB->ejecutasql($sSQL);
				$unad48id++;
			}
			$iGrupoBase = $unad48grupotope + 1;
		}
	}
	return array($sError, $sDebug);
}
function f2216_ArmarGrupo($idTercero, $idPeriodo, $objDB, $bDebug = false, $idContTercero = 0, $idContPeriodo = 0)
{
	$idGrupo = 0;
	$sError = '';
	$sDebug = '';
	if ($sError == '') {
		if ($idContTercero == 0) {
			list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
		}
	}
	if ($sError == '') {
		if ($idContPeriodo == 0) {
			$idContPeriodo = f146_Contenedor($idPeriodo, $objDB);
		}
	}
	if ($sError == '') {
		$bGruposPropios = f102_GestionaGrupos($idPeriodo, $objDB);
		$sTabla04 = 'core04matricula_' . $idContTercero;
		$sTabla06 = 'core06grupos_' . $idContPeriodo;
		//Solo verificamos los matriculados, si algo ha salido de este estado no hay nada que hacer.
		$sSQL = 'SELECT core04id, core04idcurso, core04idaula, core04idgrupo 
		FROM ' . $sTabla04 . ' 
		WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $idPeriodo . ' AND core04idgrupo<1 AND core04estado=0';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' <b>ARMAR GRUPOS</b> - Listando cursos sin grupo ' . $sSQL);
		}
		$tabla04 = $objDB->ejecutasql($sSQL);
		while ($fila04 = $objDB->sf($tabla04)) {
			$idCurso = $fila04['core04idcurso'];
			$id06 = 0;
			$id06aula = 0;
			$bEntra = false;
			if ($bGruposPropios) {
				//Armar grupos.
				list($sGrupo, $sDebugG, $sErrorG) = f2206_UbicarEstudianteEnGrupo($idPeriodo, $idCurso, $idTercero, $objDB, $idContPeriodo, $idContTercero, $bDebug);
				$sDebug = $sDebug . $sDebugG;
				if ($sErrorG != '') {
					$sError = $sErrorG;
					break;
				} else {
					$bEntra = true;
				}
			}
			if ($bEntra) {
				list($idAula, $sErrorAula, $sDebugAula) = f2206_AulaGrupoV2($idPeriodo, $idCurso, $sGrupo, $objDB, $bDebug);
				$sDebug = $sDebug . $sDebugAula;
				if ($sErrorAula != '') {
					$sError = $sErrorAula;
					$bEntra = false;
					break;
				} else {
					$id06aula = $idAula;
				}
			}
			if ($bEntra) {
				//Ya tenemos el grupo y el aula... ahora veamos que existe el grupo.
				$sSQL = 'SELECT core06id, core06idaula, core06idtutor FROM ' . $sTabla06 . ' WHERE core06idcurso=' . $idCurso . ' AND core06consec=' . $sGrupo . ' AND core06peraca=' . $idPeriodo . '';
				$tabla6 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla6) > 0) {
					$fila6 = $objDB->sf($tabla6);
					$id06 = $fila6['core06id'];
					$id06aula = $fila6['core06idaula'];
					$core06idtutor = $fila6['core06idtutor'];
				}
			}
			//Termina la busqueda del grupo ahora asociarlo.
			if ($id06 != 0) {
				$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04idgrupo=' . $id06 . ', core04idaula=' . $id06aula . ', core04idtutor=' . $core06idtutor . ' WHERE core04id=' . $fila04['core04id'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($bDebug) {
					$sDebug = $sDebug . log_debug(' <b>ARMAR GRUPOS</b> - Asignacion del grupo ' . $sSQL);
				}
				//Faltaria totalizar el grupo....
				f2406_TotalizarGrupo($idPeriodo, $id06, $idContPeriodo, $objDB);
			}
		}
	}
	return array($idGrupo, $sError, $sDebug);
}
function f2216_ProcesarAgenda($core16id, $objDB, $bDebug = false, $objDBRyC = NULL, $bForzar = false)
{
	$sDirBase = __DIR__ . '/';
	require $sDirBase . 'app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2216)) {
		$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2216;
	//15 de Abril de 2021 Todo lo que sea de period 179 hacia abajo va al 60 - 40
	$sDebug = '';
	$sError = '';
	$iHoy = fecha_DiaMod();
	$core16procagenda = 0;
	$core16erroragenda = 0;
	$idContTercero = 0;
	$idPeriodoInicioC2 = Id_PeriodoInicioC2();
	$sSQL = 'SELECT TB.core16peraca, TB.core16tercero, TB.core16idprograma, TB.core16procesado, TB.core16procagenda, 
	TB.core16numcursos, TB.core16nuevo, TB.core16idescuela, TB.core16idzona, TB.core16idcead, TB.core16fecharecibido, 
	TB.core16tipomatricula  
	FROM core16actamatricula AS TB
	WHERE TB.core16id=' . $core16id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila16 = $objDB->sf($tabla);
		$idTercero = $fila16['core16tercero'];
		$idPrograma = $fila16['core16idprograma'];
		$core16procagenda = $fila16['core16procagenda'];
		$core16procesado = $fila16['core16procesado'];
		$core16peraca = $fila16['core16peraca'];
		if ($core16peraca > $idPeriodoInicioC2) {
			switch ($fila16['core16tipomatricula']) {
				case 2: //Matricula sin pago
					break;
				default:
					if ($core16procesado == 0) {
						if (!$bForzar) {
							$sError = $ETI['msg_2216_matricula_no_armada'];
						}
					}
					break;
			}
			if ($sError == '') {
				list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
				if ($idContTercero == 0) {
					$sError = $ETI['msg_2216_contenedor_tercero_no_definido'] . ' {Ref: ' . $idTercero . '}, ' . $ETI['msg_2216_informe_admin'];
				}
				if ($sError == '') {
					$idContPeriodo = f146_Contenedor($core16peraca, $objDB);
					if ($idContPeriodo == 0) {
						$sError = $ETI['msg_2216_contenedor_periodo_no_definido'] . ' ' . $core16peraca . ', ' . $ETI['msg_2216_informe_admin'];
					}
				}
			}
			if ($sError == '') {
				//Asegurarnos de armar los grupos.
				$iFormaGrupo = 0;
				if ($iFormaGrupo == 0) {
					//Analisis del problema del cron 29 de Agosto de 2019
					//Al parecer qui no se esta logrando crear el grupo cuando viene por consola.
					list($idGrupo, $sErrCurso, $sDebugG) = f2216_ArmarGrupo($idTercero, $core16peraca, $objDB, $bDebug, $idContTercero, $idContPeriodo);
					//$sError=$sDebugG;
					if ($sErrCurso != '') {
						$sError = $sErrCurso;
					}
					$sDebug = $sDebug . $sDebugG;
				}
			}
			if ($sError == '') {
				$core16procagenda = $iHoy;
				$sCondiAplicada = ' AND TB.core04aplicoagenda=0';
				$sCondiAplicada2 = ' AND core04aplicoagenda=0';
				if ($bForzar) {
					$sCondiAplicada = '';
					$sCondiAplicada2 = '';
				}
				//Armar las agendas.
				$sTabla04 = 'core04matricula_' . $idContTercero;
				$sTabla05 = 'core05actividades_' . $idContTercero;
				$sTabla06 = 'core06grupos_' . $idContPeriodo . '';
				//Septiembre 26 de 2021 - Se agrega un control para evitar grupos que no existan. [core06grupos]
				$sIds06 = '-99';
				$iNumOrigenes = 0;
				$sSQL = 'SELECT core04idgrupo 
				FROM ' . $sTabla04 . ' 
				WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $core16peraca . $sCondiAplicada2 . ' 
				AND core04idgrupo>0 AND core04estado NOT IN (7,8)';
				$tabla04 = $objDB->ejecutasql($sSQL);
				while ($fila04 = $objDB->sf($tabla04)) {
					$sIds06 = $sIds06 . ',' . $fila04['core04idgrupo'];
					$iNumOrigenes++;
				}
				if ($sIds06 != '-99') {
					//Como ya hay grupos asignados vemos cuales son validos
					$sIdsValidos = '-99';
					$iDestinos = 0;
					$sSQL = 'SELECT core06id FROM ' . $sTabla06 . ' WHERE core06id IN (' . $sIds06 . ')';
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' ARMAR AGENDA - Verificando los grupos ' . $sSQL);
					}
					$tabla06 = $objDB->ejecutasql($sSQL);
					while ($fila06 = $objDB->sf($tabla06)) {
						$sIdsValidos = $sIdsValidos . ',' . $fila06['core06id'];
						$iDestinos++;
					}
					if ($iNumOrigenes != $iDestinos) {
						$sIdsRetirados = '-99';
						$sSQL = 'SELECT core04id FROM ' . $sTabla04 . ' 
						WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $core16peraca . $sCondiAplicada2 . ' 
						AND core04idgrupo>0 AND core04idgrupo NOT IN (' . $sIdsValidos . ') AND core04estado NOT IN (7,8)';
						if ($bDebug) {
							$sDebug = $sDebug . log_debug(' ARMAR AGENDA - Retirando grupos fallidos ' . $sSQL);
						}
						$tabla04 = $objDB->ejecutasql($sSQL);
						while ($fila04 = $objDB->sf($tabla04)) {
							$sIdsRetirados = $sIdsRetirados . ',' . $fila04['core04id'];
						}
						if ($sIdsRetirados != '-99') {
							//, core04idnav=0 --- El nav no deberia cambiar, asi que no es lio.
							$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04idaula=0, core04idgrupo=0 WHERE core04id IN (' . $sIdsRetirados . ')';
							$result = $objDB->ejecutasql($sSQL);
						}
						//Retirar cosas adicionales.
					}
				}
				//Fin de la revision de grupos
				$sSQL = 'SELECT TB.core04id, TB.core04idcurso, TB.core04estado, TB.core04aplicoagenda, TB.core04idagenda 
				FROM ' . $sTabla04 . ' AS TB 
				WHERE TB.core04tercero=' . $idTercero . ' AND TB.core04peraca=' . $core16peraca . $sCondiAplicada . '';
				if ($bDebug) {
					$sDebug = $sDebug . log_debug(' ARMAR AGENDA - Listando cursos ' . $sSQL);
				}
				$tabla04 = $objDB->ejecutasql($sSQL);
				while ($fila04 = $objDB->sf($tabla04)) {
					//Mayo 21 de 2020 - Los cursos externos no tienen agenda (tipo UNAD Florida.)
					$bHayCambiosEnAgenda = false;
					$bTieneAgenda = true;
					switch ($fila04['core04estado']) {
						case 1: // No disponible
							$ofer08idagenda = 0;
							$bTieneAgenda = false;
							break;
						case 2: // Curso Externo
							$ofer08idagenda = -98;
							$bTieneAgenda = false;
							break;
						case 9: // Cancelado
							$ofer08idagenda = -96;
							$bTieneAgenda = false;
							break;
						case 10: // Aplazado
							$ofer08idagenda = -97;
							$bTieneAgenda = false;
							break;
					}
					if ($bTieneAgenda) {
						$bEntra = true;
						if ($bForzar) {
							//Puede que no haya nada en la core05 y por eso esta forzando.
							$sSQL = 'SELECT 1 
							FROM ' . $sTabla05 . ' AS TB 
							WHERE TB.core05idmatricula=' . $fila04['core04id'] . '';
							$tabla05 = $objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla05) > 0) {
								$bEntra = false;
								if ($bDebug) {
									$sDebug = $sDebug . log_debug(' ARMAR AGENDA - El curso ' . $fila04['core04idcurso'] . ' ya tiene agenda.');
								}
								//Es posible, han pasado casos donde la agenda asociada esta perdida.
								$bReconstruirEncabezado = false;
								if ($fila04['core04idagenda'] == 0) {
									$bReconstruirEncabezado = true;
								}
								if ($bReconstruirEncabezado) {
									$bReconstruirEncabezado = false;
									$sSQL = 'SELECT ofer08estadooferta, ofer08estadocampus, ofer08idagenda, ofer08idnav, ofer08idcursonav, ofer08idescuela FROM ofer08oferta WHERE ofer08idcurso=' . $fila04['core04idcurso'] . ' AND ofer08idper_aca=' . $core16peraca . ' AND ofer08cead=0';
									$tabla08 = $objDB->ejecutasql($sSQL);
									if ($objDB->nf($tabla08) > 0) {
										$fila08 = $objDB->sf($tabla08);
										$ofer08idagenda = $fila08['ofer08idagenda'];
										$ofer08idnav = $fila08['ofer08idnav'];
										$core04idmoodle = $fila08['ofer08idcursonav'];
										$bReconstruirEncabezado = true;
									}
								}
								if ($bReconstruirEncabezado) {
									$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04idagenda=' . $ofer08idagenda . ', core04idnav=' . $ofer08idnav . ', core04idmoodle=' . $core04idmoodle . ', core04aplicoagenda=' . $core16procagenda . ' WHERE core04id=' . $fila04['core04id'] . '';
									$result = $objDB->ejecutasql($sSQL);
								}
							} else {
								if ($fila04['core04aplicoagenda'] != 0) {
									$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04aplicoagenda=0 WHERE core04id=' . $fila04['core04id'] . '';
									$result = $objDB->ejecutasql($sSQL);
									if ($bDebug) {
										$sDebug = $sDebug . log_debug(' ARMAR AGENDA - <b>Liberando marca de agenda</b> en el curso ' . $fila04['core04idcurso'] . '.');
									}
								}
							}
						}
						if ($bEntra) {
							//Esta función esta en la libdatos
							list($sErrorA, $sErrCurso, $sDebugA) = f2205_ArmarAgendaCursoEstudiante($core16peraca, $idTercero, $fila04['core04idcurso'], $objDB, $idContTercero, $bDebug);
							$sDebug = $sDebug . $sDebugA;
							if ($sErrCurso != '') {
								$sError = $sError . ' Curso ' . $fila04['core04idcurso'] . ': ' . $sErrCurso . ', ';
								$core16procagenda = 0;
								$core16erroragenda = $iHoy;
							}
						}
					} else {
						$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04aplicoagenda=-1, core04idagenda=' . $ofer08idagenda . ', core04idnav=0, 
						core04idmoodle=0, core04idaula=0, core04idgrupo=0, core04idtutor=0 WHERE core04id=' . $fila04['core04id'] . '';
						$result = $objDB->ejecutasql($sSQL);
						//por si las moscas borramos la tabla 5.
						$sSQL = 'DELETE FROM ' . $sTabla05 . ' WHERE core05idmatricula=' . $fila04['core04id'] . '';
						$result = $objDB->ejecutasql($sSQL);
					}
				}
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Registro de estudiante '.$sSQL.'<br>';}
				//Se termina el proceso
				$sSQL = 'UPDATE core16actamatricula SET core16procagenda=' . $core16procagenda . ', core16erroragenda=' . $core16erroragenda . ' WHERE core16id=' . $core16id . '';
				$result = $objDB->ejecutasql($sSQL);
			}
		} else {
			//Traemos el historico...
			$bEntra = false;
			if ($core16procagenda == 0) {
				$bEntra = true;
			}
			if ($bForzar) {
				$bEntra = true;
			}
			if ($bEntra) {
				//VAmos a procesar aqui el historico de notas.
				$bTraeRyC = false;
				if ($objDBRyC == NULL) {
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' IMPORTAR CALIFICACIONES - Cargando DB RCA');
					}
					list($objDBRyC, $sDebugC) = TraerDBRyCV2($bDebug);
					$sDebug = $sDebug . $sDebugC;
					if ($objDBRyC != NULL) {
						$bTraeRyC = true;
					}
				}
				if ($objDBRyC != NULL) {
					$core16procagenda = $iHoy;
					$core16erroragenda = 0;

					list($idContTercero, $sErrorB) = f1011_BloqueTercero($idTercero, $objDB);
					$sTabla04 = 'core04matricula_' . $idContTercero;
					//Solo verificamos los matriculados, si algo ha salido de este estado no hay nada que hacer.
					//, TB.core04nota75, TB.core04fechanota75, TB.core04nota25, TB.core04fechanota25, TB.core04notahabilita, TB.core04fechanotahabilita, TB.core04notasupletorio, TB.core04fechanotasupletorio, TB.core04notafinal, TB.core04resultado
					$sSQL = 'SELECT TB.core04id, TB.core04calificado, TB.core04idcurso, T11.unad11doc, TB.core04resultado, T40.unad40titulo 
					FROM ' . $sTabla04 . ' AS TB, unad11terceros AS T11, unad40curso AS T40 
					WHERE TB.core04tercero=' . $idTercero . ' AND TB.core04peraca=' . $core16peraca . ' AND TB.core04estado=8 
					AND TB.core04tercero=T11.unad11id AND TB.core04idcurso=T40.unad40id';
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' IMPORTAR CALIFICACIONES - Listando cursos ' . $sSQL);
					}
					$tabla04 = $objDB->ejecutasql($sSQL);
					while ($fila04 = $objDB->sf($tabla04)) {
						$bImportarCurso = false;
						if ($bForzar) {
							$bImportarCurso = true;
						} else {
							if ($fila04['core04calificado'] == 0) {
								$bImportarCurso = true;
							} else {
								if ($fila04['core04resultado'] == 0) {
									$bImportarCurso = true;
								}
							}
						}
						if ($bImportarCurso) {
							// --- Revisado Diciembre 2 de 2020
							$core04calificado = 0;
							$core04nota75 = 0;
							$core04fechanota75 = 0;
							$core04nota25 = 0;
							$core04fechanota25 = 0;
							$core04notahabilita = 0;
							$core04fechanotahabilita = 0;
							$core04notasupletorio = 0;
							$core04fechanotasupletorio = 0;
							$core04notafinal = 0;
							$core04resultado = 8;
							$core04est_aprob = 3;
							$core04res75 = 0;
							$core04res25 = 0;
							$core04resdef = 0;
							//Importar el historico.
							// Junio 18 de 2025 - Si el periodo es menor a 0 se importan del SIRA
							$bAplicaSira = false;
							if ($core16peraca < 0) {
								$bAplicaSira = true;
							} else {
								if ($core16peraca < 3) {
									list($iAgnoSira, $sMesesSira) = f2216_InfoPeriodoSira($core16peraca);
									$sSQL = 'SELECT ins_curso    
									FROM mg_total
									WHERE ins_estudiante=' . $fila04['unad11doc'] . ' AND fecha_aa=' . $iAgnoSira . ' AND fecha_mm IN (' . $sMesesSira . ')';
									$tablav = $objDBRyC->ejecutasql($sSQL);
									if ($objDBRyC->nf($tablav) > 0) {
										$bAplicaSira = true;
									}
								}
							}
							if ($bAplicaSira) {
								list($iAgnoSira, $sMesesSira) = f2216_InfoPeriodoSira($core16peraca);
								$sSQLSira = 'SELECT fecha_aa AS Agno, fecha_mm AS Mes, fecha_dd  AS Dia, TB.valor
								FROM mg_total AS TB
								WHERE TB.ins_estudiante=' . $fila04['unad11doc'] . ' AND ins_curso=' . $fila04['unad40titulo'] . ' AND fecha_aa=' . $iAgnoSira . ' AND fecha_mm IN (' . $sMesesSira . ')';
								$sSQL = $sSQLSira;
							} else {
								$sSQL = 'SELECT YEAR(TB.fecha) AS Agno, MONTH(TB.fecha) AS Mes, DAY(TB.fecha) AS Dia, TB.valor, TB.estado
								FROM i_histo_notas AS TB, registro AS TR, cursos_periodos AS TC
								WHERE TB.estudiante=' . $fila04['unad11doc'] . ' AND TB.periodo=' . $core16peraca . ' AND TB.regaca=TR.consecutivo AND TR.ins_curso=TC.consecutivo AND TC.cur_materia=' . $fila04['core04idcurso'] . '';
							}
							if ($bDebug) {
								$sDebug = $sDebug . log_debug(' IMPORTAR CALIFICACIONES - Consultando 75 ' . $sSQL);
							}
							$tabla75 = $objDBRyC->ejecutasql($sSQL);
							if ($tabla75 == false) {
								$sError = $ETI['msg_2216_error_consulta_rca'] . ' ' . $objDBRyC->serror;
							}
							if ($objDBRyC->nf($tabla75) > 0) {
								$fila75 = $objDBRyC->sf($tabla75);
								$iPeso1 = 75;
								$iPeso2 = 25;
								if ($core16peraca < 179) {
									$iPeso1 = 60;
									$iPeso2 = 40;
								}
								$core04res75 = $fila75['valor'];
								$core04nota75 = round($fila75['valor'] * $iPeso1, 0);
								$core04fechanota75 = ($fila75['Agno'] * 10000) + ($fila75['Mes'] * 100) + $fila75['Dia'];
								if ($bAplicaSira) {
									$sSQL = $sSQLSira;
								} else {
									$sTablaHistorico = 'i_histo_notas2';
									switch ($core16peraca) {
										case 3: // 2006-1 - Se habilita en junio 18 de 2025
										case 6: // 
											$sTablaHistorico = 'i_histo_notas';
											break;
									}
									$sSQL = 'SELECT YEAR(TB.fecha) AS Agno, MONTH(TB.fecha) AS Mes, DAY(TB.fecha) AS Dia, TB.valor, TB.estado
									FROM ' . $sTablaHistorico . ' AS TB, registro AS TR, cursos_periodos AS TC
									WHERE TB.estudiante=' . $fila04['unad11doc'] . ' AND TB.periodo=' . $core16peraca . ' AND TB.regaca=TR.consecutivo AND TR.ins_curso=TC.consecutivo AND TC.cur_materia=' . $fila04['core04idcurso'] . '';
								}
								if ($bDebug) {
									$sDebug = $sDebug . log_debug(' IMPORTAR CALIFICACIONES - Consultando 25 ' . $sSQL);
								}
								$tabla25 = $objDBRyC->ejecutasql($sSQL);
								/*
								if ($objDBRyC->nf($tabla25) == 0) {
									if ($core16peraca < 6) {
										//Puede ser un error de importacion, asi que reintentamos.
										$sTablaHistorico = 'i_histo_notas';
									}
									$sSQL = 'SELECT YEAR(TB.fecha) AS Agno, MONTH(TB.fecha) AS Mes, DAY(TB.fecha) AS Dia, TB.valor, TB.estado
									FROM ' . $sTablaHistorico . ' AS TB, registro AS TR, cursos_periodos AS TC
									WHERE TB.estudiante=' . $fila04['unad11doc'] . ' AND TB.periodo=' . $core16peraca . ' AND TB.regaca=TR.consecutivo AND TR.ins_curso=TC.consecutivo AND TC.cur_materia=' . $fila04['core04idcurso'] . '';
									if ($bDebug) {
										$sDebug = $sDebug . log_debug(' IMPORTAR CALIFICACIONES - Igualamos la nota con el 60 ' . $sSQL);
									}
									$tabla25 = $objDBRyC->ejecutasql($sSQL);
								}
								*/
								if ($objDBRyC->nf($tabla25) > 0) {
									$fila25 = $objDBRyC->sf($tabla25);
									$core04res25 = $fila25['valor'];
									$core04nota25 = round($fila25['valor'] * $iPeso2, 0);
									$core04fechanota25 = ($fila25['Agno'] * 10000) + ($fila25['Mes'] * 100) + $fila25['Dia'];
									$core04calificado = 500;
									$core04notafinal = $core04nota75 + $core04nota25;
									if ($core04notafinal > 299) {
										$core04resultado = 7;
									} else {
										if ($core04notafinal == 0) {
											$core04resultado = 14;
										} else {
											if ($core04nota25 == 0) {
												if ($core04nota75 < 26) {
													$core04resultado = 11;
												}
											}
										}
									}
								} else {
									if ($core16peraca < 179) {
										//No existe la nota pero debe ser cerrada.
										$core04res25 = 0;
										$core04nota25 = 0;
										$core04fechanota25 = 0;
										$core04calificado = 500;
										$core04notafinal = $core04nota75 + $core04nota25;
										if ($core04notafinal > 299) {
											$core04resultado = 7;
										} else {
											if ($core04notafinal == 0) {
												$core04resultado = 14;
											} else {
												if ($core04nota25 == 0) {
													if ($core04nota75 < 26) {
														$core04resultado = 11;
													}
												}
											}
										}
										//Termina de cerrar la nota
									} else {
										$core16procagenda = 0;
										$core16erroragenda = $iHoy;
										if ($bDebug) {
											$sDebug = $sDebug . log_debug(' IMPORTAR CALIFICACIONES - <span class="rojo">No se encontro nota del 25</span>');
										}
									}
								}
							} else {
								$core16procagenda = 0;
								$core16erroragenda = $iHoy;
								if ($bDebug) {
									$sDebug = $sDebug . log_debug(' IMPORTAR CALIFICACIONES - <span class="rojo">No se encontro nota del 75</span>');
								}
							}
							if ($core04calificado == 500) {
								$iPeso1 = 75;
								$iPeso2 = 25;
								if ($core16peraca < 179) {
									$iPeso1 = 60;
									$iPeso2 = 40;
								}
								$core04resdef = round((($core04res75 * $iPeso1) + ($core04res25 * $iPeso2)) / 100, 1);
								//Se calculan tambien la estadistica.
								$core04est_5presenta = 0;
								$core04est_70presenta = 0;
								$core04est_25presenta = 0;
								$core04est_75presenta = 0;
								$core04est_100presenta = 0;
								$core04est_5aprobado = 0;
								$core04est_70aprobado = 0;
								$core04est_75aprobado = 0;
								$core04est_25aprobado = 0;
								$core04est_25noaprobado = 0;
								$core04est_75noaprobado = 0;
								$core04est_100noaprobado = 0;
								$core04est_100aprobado = 0;
								switch ($core04resultado) {
									case 11: //Abandono
										$core04est_5presenta = 25;
										$core04est_70presenta = 25;
										$core04est_75presenta = 25;
										$core04est_100presenta = 25;
										$core04est_5aprobado = $core04nota75;
										break;
									case 14: //Inasistente
										break;
									default:
										$core04est_5presenta = 25;
										$core04est_70presenta = 350;
										$core04est_25presenta = 125;
										$core04est_75presenta = 375;
										$core04est_100presenta = 500;
										$iMinimoNota1 = 224;
										$iMinimoNota2 = 74;
										if ($core16peraca < 179) {
											$core04est_70presenta = 275;
											$core04est_25presenta = 200;
											$core04est_75presenta = 300;
											$iMinimoNota1 = 179;
											$iMinimoNota2 = 119;
										}
										if ($core04nota75 < 26) {
											$core04est_5aprobado = $core04nota75;
										} else {
											$core04est_5aprobado = 25;
											$core04est_70aprobado = $core04nota75 - 25;
										}
										if ($core04nota75 > $iMinimoNota1) {
											$core04est_75aprobado = $core04nota75;
										} else {
											$core04est_75noaprobado = $core04nota75;
										}
										if ($core04nota25 > $iMinimoNota2) {
											$core04est_25aprobado = $core04nota25;
										} else {
											$core04est_25noaprobado = $core04nota25;
										}
										if ($core04resultado == 7) {
											$core04est_100aprobado = $core04nota75 + $core04nota25;
										} else {
											$core04est_100noaprobado = $core04nota75 + $core04nota25;
										}
										break;
								}
								// --- Revisado Diciembre 2 de 2020
								$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04calificado=' . $core04calificado . '
								, core04nota75=' . $core04nota75 . ', core04fechanota75=' . $core04fechanota75 . ', 
								core04nota25=' . $core04nota25 . ', core04fechanota25=' . $core04fechanota25 . ', core04notahabilita=' . $core04notahabilita . ', 
								core04fechanotahabilita=' . $core04fechanotahabilita . ', core04notasupletorio=' . $core04notasupletorio . ', core04fechanotasupletorio=' . $core04fechanotasupletorio . ', 
								core04notafinal=' . $core04notafinal . ', core04resultado=' . $core04resultado . ', core04est_aprob=' . $core04est_aprob . ', 
								core04res75=' . $core04res75 . ', core04res25=' . $core04res25 . ', core04resdef=' . $core04resdef . ', core04est_5presenta=' . $core04est_5presenta . '
								, core04est_70presenta=' . $core04est_70presenta . '
								, core04est_25presenta=' . $core04est_25presenta . '
								, core04est_75presenta=' . $core04est_75presenta . '
								, core04est_100presenta=' . $core04est_100presenta . '
								, core04est_5aprobado=' . $core04est_5aprobado . '
								, core04est_70aprobado=' . $core04est_70aprobado . '
								, core04est_75aprobado=' . $core04est_75aprobado . '
								, core04est_25aprobado=' . $core04est_25aprobado . '
								, core04est_25noaprobado=' . $core04est_25noaprobado . '
								, core04est_75noaprobado=' . $core04est_75noaprobado . '
								, core04est_100noaprobado=' . $core04est_100noaprobado . '
								, core04est_100aprobado=' . $core04est_100aprobado . ' 
								WHERE core04id=' . $fila04['core04id'] . '';
								if ($bDebug) {
									$sDebug = $sDebug . log_debug(' IMPORTAR CALIFICACIONES - Actualizando ' . $sSQL);
								}
								$result = $objDB->ejecutasql($sSQL);
							}
						}
					}
					$sSQL = 'UPDATE core16actamatricula SET core16procagenda=' . $core16procagenda . ', core16erroragenda=' . $core16erroragenda . ' WHERE core16id=' . $core16id . '';
					$result = $objDB->ejecutasql($sSQL);
				} else {
					$sError = $ETI['msg_2216_conexion_rca_fallida'];
				}
				if ($bTraeRyC) {
					$objDBRyC->CerrarConexion();
				}
			}
		}
		list($sErrorO, $sDebugO) = f2216_OrdenarMatriculas($idTercero, $objDB, $bDebug, $idContTercero);
		$sDebug = $sDebug . $sDebugO;
	} else {
		$sError = $ETI['msg_2216_matricula_no_encontrada'] . ' {Ref:' . $core16id . '}';
	}
	return array($core16procagenda, $sError, $sDebug);
}
function f2216_OrdenarMatriculas($idTercero, $objDB, $bDebug = false, $idContTercero = 0)
{
	$sDebug = '';
	$sError = '';
	$aMatriculas = array();
	$iNumMat = 0;
	$iNumMatPrograma = 0;
	$iProgramaIni = 0;
	$sSQL = 'SELECT TB.core16id, TB.core16peraca, TB.core16idprograma, TB.cara16nummatriculaprog, TB.cara16nummatriculatotal, 
	TB.core16edad, TB.core16fechamatricula, T11.unad11fechanace 
	FROM core16actamatricula AS TB, unad11terceros AS T11 
	WHERE TB.core16tercero=' . $idTercero . ' AND TB.core16estado NOT IN (99) AND TB.core16tercero=T11.unad11id
	ORDER BY TB.core16peraca , TB.core16idprograma';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$iNumMat++;
		if ($iProgramaIni == 0) {
			$iProgramaIni = $fila['core16idprograma'];
		}
		$aMatriculas[$iNumMat]['id'] = $fila['core16id'];
		$sCambia = '';
		if ($fila['cara16nummatriculatotal'] != $iNumMat) {
			$sCambia = 'cara16nummatriculatotal=' . $iNumMat . '';
		}
		if ($iProgramaIni > 0) {
			if ($iProgramaIni == $fila['core16idprograma']) {
				$iNumMatPrograma++;
				if ($fila['cara16nummatriculaprog'] != $iNumMatPrograma) {
					if ($sCambia != '') {
						$sCambia = $sCambia . ', ';
					}
					$sCambia = $sCambia . 'cara16nummatriculaprog=' . $iNumMatPrograma . '';
				}
			}
		}
		if ($fila['core16fechamatricula'] != 0) {
			list($iEdad, $iMedida) = fecha_edad($fila['unad11fechanace'], fecha_desdenumero($fila['core16fechamatricula']));
			if ($iMedida > 1) {
				$iEdad = 0;
			}
			if ($fila['core16edad'] != $iEdad) {
				if ($sCambia != '') {
					$sCambia = $sCambia . ', ';
				}
				$sCambia = $sCambia . 'core16edad=' . $iEdad . '';
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Ordenando Matriculas: Edad periodo ' . $fila['core16peraca'] . ' ' . $iEdad);
			}
		}
		if ($sCambia != '') {
			$sSQL = 'UPDATE core16actamatricula SET ' . $sCambia . ' WHERE core16id=' . $fila['core16id'] . '';
			$result = $objDB->ejecutasql($sSQL);
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Ordenando Matriculas: ' . $sSQL);
			}
		}
	}
	if ($sError == '') {
		//Faltaria reordenar cuando tiene mas de un programa.

	}
	if ($sError == '') {
		//Ahora ordenar los intentos por curso... no cuentas las habilitaciones. (OJO CON ESE DETALLE)
		if ($idContTercero == 0) {
			list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
		}
		if ($idContTercero != 0) {
			$idCurso = -99;
			$iNumIntento = 0;
			//, TB.core04idmatricula, TB.core04peraca
			$sSQL = 'SELECT TB.core04id, TB.core04idcurso, TB.core04nummatriculaprograma, T16.cara16nummatriculaprog, TB.core04numintento, 
			TB.core04edad, T16.core16edad, TB.core04idplanestudio, T16.core16idplanestudio, TB.core04numcreditos, T40.unad40numcreditos, TB.core04estado
			FROM core04matricula_' . $idContTercero . ' AS TB, core16actamatricula AS T16, unad40curso AS T40
			WHERE TB.core04tercero=' . $idTercero . ' AND TB.core04idmatricula=T16.core16id AND TB.core04idcurso=T40.unad40id
			ORDER BY TB.core04idcurso, TB.core04peraca ';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				if ($idCurso != $fila['core04idcurso']) {
					$idCurso = $fila['core04idcurso'];
					$iNumIntento = 1;
				} else {
					$iNumIntento++;
				}
				$sCambia = '';
				if ($fila['core04numintento'] != $iNumIntento) {
					$sCambia = 'core04numintento=' . $iNumIntento . '';
				}
				if ($fila['cara16nummatriculaprog'] > 0) {
					if ($fila['core04nummatriculaprograma'] != $fila['cara16nummatriculaprog']) {
						if ($sCambia != '') {
							$sCambia = $sCambia . ', ';
						}
						$sCambia = $sCambia . 'core04nummatriculaprograma=' . $fila['cara16nummatriculaprog'] . '';
					}
				}
				if ($fila['core16edad'] > 0) {
					if ($fila['core04edad'] != $fila['core16edad']) {
						if ($sCambia != '') {
							$sCambia = $sCambia . ', ';
						}
						$sCambia = $sCambia . 'core04edad=' . $fila['core16edad'] . '';
					}
				}
				if ($fila['core16idplanestudio'] > 0) {
					if ($fila['core04idplanestudio'] != $fila['core16idplanestudio']) {
						if ($sCambia != '') {
							$sCambia = $sCambia . ', ';
						}
						$sCambia = $sCambia . 'core04idplanestudio=' . $fila['core16idplanestudio'] . '';
					}
				}
				$bEntra = true;
				switch ($fila['core04estado']) {
					case 1:	// No disponible	
					case 9: // Cancelado
					case 10: // Aplazado
						$iCreditos = 0;
						break;
					default:
						$iCreditos = $fila['unad40numcreditos'];
						break;
				}
				if ($bEntra) {
					if ($fila['core04numcreditos'] != $iCreditos) {
						if ($sCambia != '') {
							$sCambia = $sCambia . ', ';
						}
						$sCambia = $sCambia . 'core04numcreditos=' . $iCreditos . '';
					}
				}
				if ($sCambia != '') {
					$sSQL = 'UPDATE core04matricula_' . $idContTercero . ' SET ' . $sCambia . ' WHERE core04id=' . $fila['core04id'] . '';
					$result = $objDB->ejecutasql($sSQL);
					if ($bDebug) {
						$sDebug = $sDebug . log_debug(' Ajustando Num de intento: ' . $sSQL);
					}
				}
			}
		}
	}
	return array($sError, $sDebug);
}
function f2216_AplicarPromedios($core16id, $objDB, $bDebug = false)
{
	$sDebug = '';
	$sError = '';
	$iHoy = fecha_DiaMod();
	$core16aplicada = 0;
	$core16resultado = 0;
	$core16abandono = -1;
	$core16idestprog = 0;
	$sSQL = 'SELECT core16peraca, core16tercero, core16idprograma, core16aplicada, core16numcursos, cara16nummatriculaprog, 
	cara16nummatriculatotal, core16total_in, core16total_ab, core16resultado, cara16numcreditos, cara16numcursosext, 
	cara16numcursospost, cara16numcreditosext, cara16numcreditospost, T22.core22grupo, TB.core16estado, TB.core16abandono, 
	TB.core16idestprog
	FROM core16actamatricula AS TB, core09programa AS T9, core22nivelprograma AS T22
	WHERE core16id=' . $core16id . ' AND TB.core16idprograma=T9.core09id AND T9.cara09nivelformacion=T22.core22id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila16 = $objDB->sf($tabla);
		$idTercero = $fila16['core16tercero'];
		$idPrograma = $fila16['core16idprograma'];
		$idPeriodo = $fila16['core16peraca'];
		$core16idestprog = $fila16['core16idestprog'];
		list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
		if ($fila16['cara16nummatriculatotal'] == 0) {
			list($sErrorO, $sDebugO) = f2216_OrdenarMatriculas($idTercero, $objDB, $bDebug, $idContTercero);
			$sDebug = $sDebug . $sDebugO;
		}
		//Ahora si vamos a traer los cursos que se tienen.
		//$iTotalCreditos=$fila16['cara16numcreditos'];//, cara16numcreditosext, cara16numcreditospost
		$iGrupoNivelForma = $fila16['core22grupo'];
		$core16estado = $fila16['core16estado'];
		$core16numcursos = 0;
		$cara16numcursospost = 0;
		$iNumCalificados = 0;
		$iNumReportados = 0;
		$core16numaprobados = 0;
		$iBasePromedio = 0;
		$cara16numcreditos = 0;
		$cara16credaprobados = 0;
		$cara16numcursosext = 0;
		$cara16numcreditosext = 0;
		$cara16numcreditospost = 0;
		$cara16numaplazados = 0;
		$cara16numcancelados = 0;
		$core16promedio = 0;
		$core16total_in = 0;
		$core16total_ab = 0;
		$core04idmatricula = 0;
		$core04id = 0;
		$sCondiSalida = '';
		if ($core16estado == 99) {
			$sCondiSalida = ' AND TB.core04tercero=-99';
			//Matricula ANULADA - Liberarle los cursos.
			$sSQL = 'UPDATE core04matricula_' . $idContTercero . ' SET core04estado=9 WHERE core04idmatricula=' . $core16id . ' AND core04estado IN (0,2,5,7,8,10)';
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' <b>QUITANDO CURSO</b> ' . $sSQL);
			}
			$result = $objDB->ejecutasql($sSQL);
		}
		// Primero verificamos que no haya falla de reporte. 
		// Es decir cursos que ya fueron cerrados, pero que internamente solo 
		// aparecen como calificados.
		$sIds04 = '-99';
		$sSQL = 'SELECT TB.core04idcurso
		FROM core04matricula_' . $idContTercero . ' AS TB, ofer08oferta AS T8
		WHERE TB.core04tercero=' . $idTercero . $sCondiSalida . ' AND TB.core04idmatricula IN (0, ' . $core16id . ') AND TB.core04peraca=' . $idPeriodo . ' 
		AND TB.core04estado=7 AND TB.core04idcurso=T8.ofer08idcurso AND TB.core04peraca=T8.ofer08idper_aca AND T8.ofer08cead=0
		AND T8.ofer08c2fechacierre<>0';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds04 = $sIds04 . ',' . $fila['core04idcurso'];
		}
		if ($sIds04 != '-99') {
			$sSQL = 'SHOW TABLES LIKE "core04%"';
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' <b>Recaulculando Totales de Matricula Por Curso</b>: Lista de contenedores: ' . $sSQL);
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				//Recorrer los contenedores cargando la data.
				$iContenedor = substr($fila[0], 16);
				if ($iContenedor != 0) {
					$sTabla04 = $fila[0];
					$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04estado=8 WHERE core04peraca=' . $idPeriodo . ' AND core04idcurso IN (' . $sIds04 . ') AND core04estado=7';
					$result = $objDB->ejecutasql($sSQL);
				}
			}
		}
		//Ahora si iniciamos el proceso.
		$iTotalCursos = 0;
		$sSQL = 'SELECT TB.core04id, TB.core04idmatricula, TB.core04idcurso, T40.unad40numcreditos, TB.core04estado, 
		TB.core04est_aprob, TB.core04nota75, TB.core04nota25, TB.core04notafinal, TB.core04resdef, TB.core04resultado
		FROM core04matricula_' . $idContTercero . ' AS TB, unad40curso AS T40
		WHERE TB.core04tercero=' . $idTercero . $sCondiSalida . ' AND TB.core04idmatricula IN (0, ' . $core16id . ') AND TB.core04peraca=' . $idPeriodo . ' 
		AND TB.core04estado<>1 AND TB.core04idcurso=T40.unad40id';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('<b>Promedios</b> Info Base: ' . $sSQL);
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$bRevResultado = false;
			switch ($fila['core04estado']) {
				case 1: //No disponible.
					break;
				case 2: //Matricula externa
					$cara16numcursosext++;
					$cara16numcreditosext = $cara16numcreditosext + $fila['unad40numcreditos'];
					$bRevResultado = true;
					$iNumReportados++;
					break;
				case 9: // Cancelado
					$cara16numcancelados++;
					break;
				case 10: // Aplazado
					$cara16numaplazados++;
					break;
				case 7: //Calificado
				case 8: //Reportado
					if ($iGrupoNivelForma == 4) {
						$cara16numcursospost++;
						$cara16numcreditospost = $cara16numcreditospost + $fila['unad40numcreditos'];
					} else {
						$core16numcursos++;
						$cara16numcreditos = $cara16numcreditos + $fila['unad40numcreditos'];
					}
					$iNumCalificados++;
					if ($fila['core04estado'] == 8) {
						$iNumReportados++;
					}
					$bRevResultado = true;
					break;
				default:
					if ($iGrupoNivelForma == 4) {
						$cara16numcursospost++;
						$cara16numcreditospost = $cara16numcreditospost + $fila['unad40numcreditos'];
					} else {
						$core16numcursos++;
						$cara16numcreditos = $cara16numcreditos + $fila['unad40numcreditos'];
					}
					break;
			}
			if ($bRevResultado) {
				switch ($fila['core04resultado']) {
					case 7: //Aprobado
						$iBasePromedio = $iBasePromedio + $fila['core04resdef'];
						$core16numaprobados++;
						$cara16credaprobados = $cara16credaprobados + $fila['unad40numcreditos'];
						break;
					case 8: //Reprobado
						$iBasePromedio = $iBasePromedio + $fila['core04resdef'];
						break;
					case 11: //Abandono
						$core16total_ab++;
						break;
					case 14: //Inasistentes
						$iBasePromedio = $iBasePromedio + $fila['core04resdef'];
						$core16total_in++;
						break;
				}
			}
			if ($fila['core04idmatricula'] == 0) {
				//Actualizamos el id de la matricula, esto fue por unos casos que sucedieron entre 2018 y 2019
				$sSQL = 'UPDATE core04matricula_' . $idContTercero . ' SET core04idmatricula=' . $core16id . ' WHERE core04id=' . $fila['core04id'] . '';
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('<b>Promedios</b> Actualizando id de la 04 ' . $sSQL);
				}
				$result = $objDB->ejecutasql($sSQL);
			}
		}
		if (($core16numcursos + $cara16numcursosext + $cara16numcursospost) == 0) {
			$core16aplicada = $iHoy;
			if ($cara16numcancelados > 0) {
				$core16resultado = 9;
			} else {
				if ($cara16numaplazados > 0) {
					$core16resultado = 10;
				}
			}
		} else {
			$iTotalCursos = ($core16numcursos + $cara16numcursosext + $cara16numcursospost);
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('<b>Promedios</b> Valorando insuficiencias y abandonos ' . ($core16total_in + $core16total_ab) . '/' . $iTotalCursos);
			}
			if ($iTotalCursos == ($core16total_in + $core16total_ab)) {
				$core16aplicada = $iHoy;
				if ($core16total_ab == 0) {
					$core16resultado = 14;
				} else {
					$core16resultado = 11;
				}
			}
			if ($iNumCalificados > 0) {
				$iNumCursos = $iNumCalificados + $cara16numcursosext;
				$core16promedio = round($iBasePromedio / $iNumCursos, 2);
			}
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('<b>Promedios</b> Valorando cursos reportados ' . $iNumReportados . '/' . $iTotalCursos);
			}
			if ($iTotalCursos == $iNumReportados) {
				$core16aplicada = $iHoy;
			} else {
				if ($bDebug) {
					$sDebug = $sDebug . log_debug('<b>Promedios</b> HAY CURSOS SIN CIERRE');
				}
			}
			if (($core16resultado == 0) && ($iNumCalificados > 0)) {
				$dMinimo = 3;
				if ($core16promedio < $dMinimo) {
					if ($core16numaprobados > 0) {
						$core16resultado = 16; //Bajo desempeño
					} else {
						$core16resultado = 15; //Insuficiente
					}
				} else {
					//Paso el minimo... lo primero que vemos es que no sea alguien que tenga accesos sospechosos.
					$iFechaBase = 0;
					$iFechaTope = 30000101;
					$sSQL = 'SELECT exte02fechaini60, exte02fechafin40 FROM exte02per_aca WHERE exte02id=' . $idPeriodo . '';
					$tabla01 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla01) > 0) {
						$fila01 = $objDB->sf($tabla01);
						if ($fila01['exte02fechaini60'] > 0) {
							$iFechaBase = $fila01['exte02fechaini60'];
						}
						if ($fila01['exte02fechafin40'] > 0) {
							$iFechaTope = $fila01['exte02fechafin40'];
						}
					}
					$sSQL = 'SELECT unae13idia FROM unae13enrevision WHERE unae13idtercero=' . $idTercero . ' AND unae13idia>=' . $iFechaBase . ' AND unae13idia<=' . $iFechaTope . '';
					$tabla13 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla13) > 0) {
						$core16resultado = 18; //Con sospechas
					} else {
						$core16resultado = 17; //Aceptable
						$iTotalCreditos = $cara16numcreditos + $cara16numcreditospost + $cara16numcreditosext;
						$dAltos = $dMinimo + (1 / 2);
						if ($bDebug) {
							$sDebug = $sDebug . log_debug('<b>Promedios</b> Promedio ' . $core16promedio . ' [Meta superior ' . $dAltos . '] [Creditos ' . $iTotalCreditos . ']');
						}
						if ($core16promedio >= $dAltos) {
							$core16resultado = 19; //Superior
							if ($iTotalCreditos > 11) {
								$dAltos = $dMinimo + (3 / 2);
								if ($bDebug) {
									$sDebug = $sDebug . log_debug('<b>Promedios</b> Promedio ' . $core16promedio . ' [Meta muy superior ' . $dAltos . ']');
								}
								if ($core16promedio >= $dAltos) {
									$core16resultado = 20; //Muy Superior
								}
							}
						}
					}
				}
			}
		}
		switch ($core16resultado) {
			case 0:
				break;
			case 11:
			case 14:
				$core16abandono = 1;
				break;
			default:
				$core16abandono = 0;
				break;
		}
		$sSQL = 'UPDATE core16actamatricula SET core16numcursos=' . $iTotalCursos . ', core16numaprobados=' . $core16numaprobados . ', 
		cara16numcreditos=' . $cara16numcreditos . ', cara16credaprobados=' . $cara16credaprobados . ', 
		cara16numcursosext=' . $cara16numcursosext . ', cara16numcreditosext=' . $cara16numcreditosext . ', 
		cara16numcancelados=' . $cara16numcancelados . ', cara16numaplazados=' . $cara16numaplazados . ', 
		core16promedio=' . $core16promedio . ', core16aplicada=' . $core16aplicada . ', 
		core16total_in=' . $core16total_in . ', core16total_ab=' . $core16total_ab . ', core16resultado=' . $core16resultado . ', 
		cara16numcursospost=' . $cara16numcursospost . ', cara16numcreditospost=' . $cara16numcreditospost . ', 
		core16abandono=' . $core16abandono . ' 
		WHERE core16id=' . $core16id . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('<b>Promedios</b> ' . $sSQL);
		}
		$result = $objDB->ejecutasql($sSQL);
		//Ahora totalizar las matriculas nulas.
		if ($core16idestprog > 0) {
			$sSQL = 'SELECT 1 FROM core16actamatricula WHERE core16abandono=1 AND core16idestprog=' . $core16idestprog . '';
			$tabla = $objDB->ejecutasql($sSQL);
			$core01nummat_abandono = $objDB->nf($tabla);
			$sSQL = 'UPDATE core01estprograma SET core01nummat_abandono=' . $core01nummat_abandono . ' WHERE core01id=' . $core16idestprog . '';
			$result = $objDB->ejecutasql($sSQL);
		}
	} else {
			$sError = $ETI['msg_2216_matricula_no_encontrada'] . ' {Ref:' . $core16id . '}';
	}
	return array($core16aplicada, $sError, $sDebug, $core16resultado);
}
function f2216_Procesar($core16id, $objDB, $bDebug = false, $iCompleta = 0)
{
	//A pesar de que se pensaba procesar todo en una sola, se toma la decision de separarlo.
	//Nov 18 de 2020 - Se procesa caracterizacion solo de periodo inicial en adelante..
	$sDebug = '';
	$sError = '';
	$iHoy = fecha_DiaMod();
	$core16procesado = 0;
	$core16proccarac = 0;
	$core16procagenda = 0;
	$core16numcursos = 0;
	$iPeriodoBase = Id_PeriodoInicioC2();
	$sSQL = 'SELECT core16peraca, core16tercero, core16idprograma, core16procesado, core16proccarac, core16procagenda, 
	core16numcursos, core16nuevo, core16idescuela, core16idzona, core16idcead, core16fecharecibido, core16idplanestudio  
	FROM core16actamatricula 
	WHERE core16id=' . $core16id . '';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' <b>f2216_Procesar</b> Consultando matricula ' . $sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila16 = $objDB->sf($tabla);
		$idTercero = $fila16['core16tercero'];
		$idPrograma = $fila16['core16idprograma'];
		$idPeriodo = $fila16['core16peraca'];
		$core16procesado = $fila16['core16procesado'];
		$core16proccarac = $fila16['core16proccarac'];
		$core16procagenda = $fila16['core16procagenda'];
		$core16numcursos = $fila16['core16numcursos'];
		$core16peraca = $fila16['core16peraca'];
		$core16idplanestudio = $fila16['core16idplanestudio'];
		//Son 3 bloques.... Armar la caracterizacion, la matricula y la agenda.
		// Caracterizacion.
		//Ver que el estudiante este registrado
		/*
		11	Transición
		12	Cambio de Programa
		98	Candidato Desinteresado
		99	Inadmitido		*/
		$sSQL = 'SELECT 1 FROM core01estprograma WHERE core01idtercero=' . $fila16['core16tercero'] . ' AND core01idprograma=' . $fila16['core16idprograma'] . ' AND core01idestado NOT IN (-2, 11, 12, 98, 99)';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' <b>f2216_Procesar</b> Registro de estudiante ' . $sSQL);
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			//Esta función esta en la libcore.php
			list($core01id, $sErrorE, $sDebugE) = f2201_IniciarEstudiante($idTercero, $idPrograma, $core16idplanestudio, $objDB, $bDebug);
		}
		if ($core16peraca > $iPeriodoBase) {
			$bEntra = false;
			if ($core16proccarac == 0) {
				$bEntra = true;
			}
			if ($iCompleta != 0) {
				$bEntra = true;
			}
			if ($bEntra) {
				//Iniciamos la caracterizacion...
				$bForzarNueva = false;
				if ($idPeriodo == 1940) {
					$bForzarNueva = true;
				}
				list($sErrorE, $sDebugE) = f2301_IniciarEncuesta($idTercero, $idPeriodo, $objDB, $bDebug, $bForzarNueva);
				$sDebug = $sDebug . $sDebugE;
				if ($sErrorE == '') {
					$core16proccarac = $iHoy;
				}
			}
		} else {
			$core16proccarac = $iHoy;
		}
		if ($iCompleta != 0) {
			$objNoConformidad = new clsT203(2216);
			//Matricula.
			list($core16procesado, $core16numcursos, $sErrorM, $sDebugM) = f2216_ProcesarMatricula($core16id, $objDB, $objNoConformidad, $bDebug);
			$sError = $sError . $sErrorM;
			$sDebug = $sDebug . $sDebugM;
			list($core16procagenda, $sErrorM, $sDebugM) = f2216_ProcesarAgenda($core16id, $objDB, $bDebug);
			$sError = $sError . $sErrorM;
			$sDebug = $sDebug . $sDebugM;
		}
		//Marcar los cambios...
		$sSQL = 'UPDATE core16actamatricula SET core16procesado=' . $core16procesado . ', core16proccarac=' . $core16proccarac . ', 
		core16procagenda=' . $core16procagenda . ', 
		core16numcursos=' . $core16numcursos . ' WHERE core16id=' . $core16id . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	return array($core16procesado, $core16proccarac, $core16procagenda, $core16numcursos, $sError, $sDebug);
}
class cls2216Total
{
	var $idPrograma = 0;
	var $idCurso = 0;
	var $idCead = 0;
	var $iNuevos = 0;
	var $iAntiguos = 0;
	function sumarNuevo($iNuevos)
	{
		$this->iNuevos = $this->iNuevos + $iNuevos;
	}
	function sumarAntiguo($iAntiguos)
	{
		$this->iAntiguos = $this->iAntiguos + $iAntiguos;
	}
	function __construct($idPrograma, $idCurso, $idCead)
	{
		$this->idPrograma = $idPrograma;
		$this->idCurso = $idCurso;
		$this->idCead = $idCead;
	}
}
function f2216_TotalizarPeraca($idPeriodo, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	list($sError, $sDebug) = f2216_TotalEstadisticaMatriculaXCurso($idPeriodo, $objDB, $bDebug);
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' <span class="rojo">Debe cambiar el llamado a la funci&oacute;n f2216_TotalizarPeraca por f2216_TotalEstadisticaMatriculaXCurso</span>');
	}
	return array($sError, $sDebug);
}
function f2216_TotalEstadisticaMatriculaXCurso($idPeriodo, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	return array($sError, $sDebug);
}
function f2216_TotalEstadisticaMatriculaXCursoV2($idPeriodo, $objDB, $bDebug = false)
{
	list($sError, $sDebug) = f2216_TotalizarMatriculaXCursoV3($idPeriodo, 0, $objDB, $bDebug);
	return array($sError, $sDebug);
}
/*
 * Reconstruye los totales de matricula de core07 desde los contenedores core04.
 * @param int $idPeriodo Periodo academico.
 * @param int $idCurso Curso; cero procesa todo el periodo.
 * @param object $objDB Conexion local.
 * @param bool $bDebug Muestra operaciones principales y un contenedor de referencia.
 * @return array Error y trazas.
 * Modificada: 2026-08-17. Se ampliaron las trazas finales y se limito el detalle repetitivo.
 */
function f2216_TotalizarMatriculaXCursoV3($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	//Se ejecuta cuando se procesa la matricula.
	// En modo debug se detalla un solo contenedor para evitar repetir cuatro consultas
	// por cada tabla core04 del periodo.
	$sError = '';
	$sDebug = '';
	//Junio 7 de 2020 -- Se deben consider los Estados de la matricula @@@@
	$aData = array();
	$iData = 0;
	//Borramos la temporal
	$sCondiCurso = '';
	$sCondi07 = '';
	$sCondi04 = '';
	if ((int)$idCurso != 0) {
		$sCondiCurso = ' AND core97idcurso=' . $idCurso . '';
		$sCondi07 = ' AND core07idcurso=' . $idCurso . '';
		$sCondi04 = ' AND core04idcurso=' . $idCurso . '';
	}
	$sSQL = 'DELETE FROM core97matriculaest_t WHERE core97idperaca=' . $idPeriodo . '' . $sCondiCurso;
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Total Periodo: Limpiando temporal inicial core97: ' . $sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	//Alistamos los contenedores.
	$sSQL = 'SHOW TABLES LIKE "core04%"';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' <b>Recaulculando Totales de Matricula Por Curso</b>: Lista de contenedores: ' . $sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	$bPrimerDebug = $bDebug;
	while ($fila = $objDB->sf($tabla)) {
		//Recorrer los contenedores cargando la data.
		$iContenedor = substr($fila[0], 16);
		if ($iContenedor != 0) {
			$sTabla = $fila[0];
			//Antiguos
			$sSQL = 'INSERT INTO core97matriculaest_t (core97idperaca, core97idprograma, core97idcurso, core97idcead, core97contenedor, 
			core97condicion, core97numestudiantes, core97numnuevos, core97numaplaza, core97numcancela)
			SELECT ' . $idPeriodo . ' AS PERIODO, core04idprograma AS IDPROGRAMA, core04idcurso AS IDCURSO, core04idcead AS IDCEAD, "' . $sTabla . '" AS CONTENEDOR
			, 0 AS CONDI, COUNT(1) AS NUMEST, 0 AS NUMNUEVOS, 0 AS NUMAPLAZA, 0 AS NUMCANCELA 
			FROM ' . $sTabla . ' WHERE core04peraca=' . $idPeriodo . $sCondi04 . ' AND core04estado NOT IN (1, 9, 10) AND core04nuevo=0 
			GROUP BY core04idprograma, core04idcurso, core04idcead';
			if ($bPrimerDebug) {
				$sDebug = $sDebug . log_debug(' Total Periodo: Insertando Antiguos: ' . $sSQL);
			}
			$tabla04 = $objDB->ejecutasql($sSQL);
			//Nuevos
			$sSQL = 'INSERT INTO core97matriculaest_t (core97idperaca, core97idprograma, core97idcurso, core97idcead, core97contenedor, 
			core97condicion, core97numestudiantes, core97numnuevos, core97numaplaza, core97numcancela)
			SELECT ' . $idPeriodo . ' AS PERIODO, core04idprograma AS IDPROGRAMA, core04idcurso AS IDCURSO, core04idcead AS IDCEAD, "' . $sTabla . '" AS CONTENEDOR
			, 1 AS CONDI, 0 AS NUMEST, COUNT(1) AS NUMNUEVOS, 0 AS NUMAPLAZA, 0 AS NUMCANCELA 
			FROM ' . $sTabla . ' WHERE core04peraca=' . $idPeriodo . $sCondi04 . ' AND core04estado NOT IN (1, 9, 10) AND core04nuevo<>0 
			GROUP BY core04idprograma, core04idcurso, core04idcead';
			if ($bPrimerDebug) {
				$sDebug = $sDebug . log_debug(' Total Periodo: Insertando Nuevos: ' . $sSQL);
			}
			$tabla04 = $objDB->ejecutasql($sSQL);
			//Ahora los cancelados 9
			$sSQL = 'INSERT INTO core97matriculaest_t (core97idperaca, core97idprograma, core97idcurso, core97idcead, core97contenedor, 
			core97condicion, core97numestudiantes, core97numnuevos, core97numaplaza, core97numcancela)
			SELECT ' . $idPeriodo . ' AS PERIODO, core04idprograma AS IDPROGRAMA, core04idcurso AS IDCURSO, core04idcead AS IDCEAD, "' . $sTabla . '" AS CONTENEDOR
			, 9 AS CONDI, 0 AS NUMEST, 0 AS NUMNUEVOS, 0 AS NUMAPLAZA, COUNT(1) AS NUMCANCELA 
			FROM ' . $sTabla . ' WHERE core04peraca=' . $idPeriodo . $sCondi04 . ' AND core04estado=9 
			GROUP BY core04idprograma, core04idcurso, core04idcead';
			if ($bPrimerDebug) {
				$sDebug = $sDebug . log_debug(' Total Periodo: Insertando Cancelados: ' . $sSQL);
			}
			$tabla04 = $objDB->ejecutasql($sSQL);
			//Ahora los aplazados 10
			$sSQL = 'INSERT INTO core97matriculaest_t (core97idperaca, core97idprograma, core97idcurso, core97idcead, core97contenedor, 
			core97condicion, core97numestudiantes, core97numnuevos, core97numaplaza, core97numcancela)
			SELECT ' . $idPeriodo . ' AS PERIODO, core04idprograma AS IDPROGRAMA, core04idcurso AS IDCURSO, core04idcead AS IDCEAD, "' . $sTabla . '" AS CONTENEDOR
			, 10 AS CONDI, 0 AS NUMEST, 0 AS NUMNUEVOS, COUNT(1) AS NUMAPLAZA, 0 AS NUMCANCELA 
			FROM ' . $sTabla . ' WHERE core04peraca=' . $idPeriodo . $sCondi04 . ' AND core04estado=10 
			GROUP BY core04idprograma, core04idcurso, core04idcead';
			if ($bPrimerDebug) {
				$sDebug = $sDebug . log_debug(' Total Periodo: Insertando Aplazados: ' . $sSQL);
			}
			$tabla04 = $objDB->ejecutasql($sSQL);
			$bPrimerDebug = false;
		}
	}
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Total Periodo: Termina de armar el totalizador');
	}
	// Ahora guardar los datos.
	$sSQL = 'DELETE FROM core07matriculaest WHERE core07idperaca=' . $idPeriodo . '' . $sCondi07;
	$result = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Total Periodo: Limpia el totalizador: ' . $sSQL);
	}
	$core07id = tabla_consecutivo('core07matriculaest', 'core07id', '', $objDB);
	if ($core07id == -1) {
		$sError = $objDB->serror;
	} else {
		$sSQL = 'SELECT ' . $idPeriodo . ' AS core07idperaca, core97idprograma AS core07idprograma, core97idcurso AS core07idcurso, core97idcead AS core07idcead, 0 AS core07idzona, 
		0 AS core07idescuela, SUM(core97numestudiantes) AS core07numestudiantes, SUM(core97numnuevos) AS core07numnuevos, SUM(core97numaplaza) AS core07numaplaza, SUM(core97numcancela) AS core07numcancela 
		FROM core97matriculaest_t WHERE core97idperaca=' . $idPeriodo . $sCondiCurso . ' 
		GROUP BY core97idprograma, core97idcurso, core97idcead';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Total Periodo: Consultando datos para construir totalizador core07: ' . $sSQL);
		}
		$tabla07 = $objDB->ejecutasql($sSQL);
		$sCampos07 = 'core07id, core07idperaca, core07idprograma, core07idcurso, core07idcead, core07idzona, 
		core07idescuela, core07numestudiantes, core07numnuevos, core07numaplaza, core07numcancela';
		while ($fila07 = $objDB->sf($tabla07)) {
			$sValores07 = '' . $core07id . ', ' . $fila07['core07idperaca'] . ', ' . $fila07['core07idprograma'] . ', ' . $fila07['core07idcurso'] . ', ' . $fila07['core07idcead'] . ', ' . $fila07['core07idzona'] . ', 
			' . $fila07['core07idescuela'] . ', ' . $fila07['core07numestudiantes'] . ', ' . $fila07['core07numnuevos'] . ', ' . $fila07['core07numaplaza'] . ', ' . $fila07['core07numcancela'] . '';
			$sSQL07 = 'INSERT INTO core07matriculaest (' . $sCampos07 . ') VALUES (' . $sValores07 . ')';
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Total Periodo: Insertando totalizador core07: ' . $sSQL07);
			}
			$result = $objDB->ejecutasql($sSQL07);
			if ($result == false) {
				$sError = $objDB->serror;
				break;
			}
			$core07id++;
		}
	}
	//Actualizar el dato de zona
	$sSQL = 'UPDATE core07matriculaest AS TB, unad24sede AS T2 SET TB.core07idzona=T2.unad24idzona WHERE TB.core07idperaca=' . $idPeriodo . ' AND TB.core07idcead=T2.unad24id';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Total Periodo: Actualizando zona en core07: ' . $sSQL);
	}
	$result = $objDB->ejecutasql($sSQL);
	//Actualizar el dato de escuela.
	$sSQL = 'UPDATE core07matriculaest AS TB, core09programa AS T2 SET TB.core07idescuela=T2.core09idescuela WHERE TB.core07idperaca=' . $idPeriodo . ' AND TB.core07idprograma=T2.core09id';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Total Periodo: Actualizando escuela en core07: ' . $sSQL);
	}
	$result = $objDB->ejecutasql($sSQL);
	//Optimizar la tabla.
	//$sSQL='OPTIMIZE TABLE core07matriculaest';
	//$result=$objDB->ejecutasql($sSQL);
	//Limpio la temporal...
	$sSQL = 'DELETE FROM core97matriculaest_t WHERE core97idperaca=' . $idPeriodo . '' . $sCondiCurso;
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Total Periodo: Limpiando temporal core97: ' . $sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Total Periodo: Termina de construir los totales');
	}
	return array($sError, $sDebug);
}
function f2216_ActualizarPuntajesAgenda($objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SHOW TABLES LIKE "core04%"';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' Total Periodo: Lista de contenedores: ' . $sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$iContenedor = substr($fila[0], 16);
		//Recorrer los contenedores cargando la data.
		$sSQL = 'UPDATE core05actividades_' . $iContenedor . ' AS TB, core04matricula_' . $iContenedor . ' AS T1, ofer06agendaactividad AS T6, ofer02cursofase AS T2
		SET TB.core05puntaje75=T6.ofer06peso, TB.core05puntaje25=0 
		WHERE TB.core05idmatricula=T1.core04id 
		AND T1.core04idagenda=T6.ofer06idagenda AND TB.core05puntaje75<>T6.ofer06peso 
		AND TB.core05idfase=T6.ofer06idfase 
		AND TB.core05idunidad=T6.ofer06idunidad AND TB.core05idactividad=T6.ofer06idactividad 
		AND TB.core05idfase=T2.ofer02id AND T2.ofer02fechabase=0';
		$result = $objDB->ejecutasql($sSQL);
		$sSQL = 'UPDATE core05actividades_' . $iContenedor . ' AS TB, core04matricula_' . $iContenedor . ' AS T1, ofer06agendaactividad AS T6, ofer02cursofase AS T2
		SET TB.core05puntaje75=0, TB.core05puntaje25=T6.ofer06peso 
		WHERE TB.core05idmatricula=T1.core04id 
		AND T1.core04idagenda=T6.ofer06idagenda AND TB.core05puntaje25<>T6.ofer06peso 
		AND TB.core05idfase=T6.ofer06idfase 
		AND TB.core05idunidad=T6.ofer06idunidad AND TB.core05idactividad=T6.ofer06idactividad 
		AND TB.core05idfase=T2.ofer02id AND T2.ofer02fechabase IN (3, 4)';
		$result = $objDB->ejecutasql($sSQL);
	}
	return array($sError, $sDebug);
}
function f2216_RevisarAgendas()
{
	$sSQL = 'SELECT TB.core05peraca, TB.core05tercero, TB.core05idcurso, SUM(TB.core05puntaje75+TB.core05puntaje25) AS Puntaje
	FROM core05actividades_1 AS TB 
	GROUP BY TB.core05peraca, TB.core05tercero, TB.core05idcurso
	HAVING SUM(TB.core05puntaje75+TB.core05puntaje25)<>500';
}
function f2216_ActualizarTutores($objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	//Septiembre 14 de 2020, se retira esta funci&oacute;n, era muy pesada y causaba muchas demoras, se deja como f2216_ActualizarTutoresV1 para registro historico, pero no debe ser usada.
	return array($sError, $sDebug);
}
function f2216_ActualizarTutoresV1($objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	//Empezamos limpiando la tabla de totales.
	$iSegIni = microtime(true);
	$sPeraca = -99;
	$sSQL = 'SELECT exte02id FROM exte02per_aca WHERE exte02vigente="S" AND exte02contgrupos<>0';
	$tabla2 = $objDB->ejecutasql($sSQL);
	while ($fila2 = $objDB->sf($tabla2)) {
		$sPeraca = $sPeraca . ',' . $fila2['exte02id'];
	}
	$sSQL = 'DELETE FROM ceca92avancegrupo WHERE ceca92peraca IN (' . $sPeraca . ')';
	$result = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		list($sDebugT, $iSegIni) = debug_Cronometrado(' Borrando totales: ' . $sSQL . '', $iSegIni);
		$sDebug = $sDebug . $sDebugT;
	}
	$sSQL = 'SHOW TABLES LIKE "core04%"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		list($sDebugT, $iSegIni) = debug_Cronometrado('Total Periodo: Lista de contenedores: ' . $sSQL . '', $iSegIni);
		$sDebug = $sDebug . $sDebugT;
	}
	while ($fila = $objDB->sf($tabla)) {
		$iContenedor = substr($fila[0], 16);
		//Recorrer los contenedores.
		//Actualizamos los grupos.
		$sSQL = 'UPDATE core05actividades_' . $iContenedor . ' AS TB, core04matricula_' . $iContenedor . ' AS T4 
		SET TB.core05idgrupo=T4.core04idgrupo
		WHERE TB.core05idmatricula=T4.core04id AND TB.core05idgrupo<>T4.core04idgrupo';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			list($sDebugT, $iSegIni) = debug_Cronometrado('Actualizando grupos contenedor ' . $iContenedor . ': ' . $sSQL . '', $iSegIni);
			$sDebug = $sDebug . $sDebugT;
		}
		//Actualizar tutores.
		//obtenemos la lista de peracas que tiene el grupo
		$sIds = -99;
		$sSQL = 'SELECT core04peraca FROM core04matricula_' . $iContenedor . ' GROUP BY core04peraca';
		$tabla4 = $objDB->ejecutasql($sSQL);
		while ($fila4 = $objDB->sf($tabla4)) {
			$sIds = $sIds . ',' . $fila4['core04peraca'];
		}
		if ($bDebug) {
			list($sDebugT, $iSegIni) = debug_Cronometrado('Listado de peracas contenedor ' . $iContenedor . ': ' . $sSQL . '', $iSegIni);
			$sDebug = $sDebug . $sDebugT;
		}
		$sSQL = 'SELECT exte02id, exte02contgrupos FROM exte02per_aca WHERE exte02id IN (' . $sIds . ') AND exte02vigente="S" AND exte02contgrupos<>0';
		$tabla2 = $objDB->ejecutasql($sSQL);
		while ($fila2 = $objDB->sf($tabla2)) {
			$sSQL = 'UPDATE core05actividades_' . $iContenedor . ' AS TB, core06grupos_' . $fila2['exte02contgrupos'] . ' AS T6 
			SET TB.core05idtutor=T6.core06idtutor
			WHERE TB.core05peraca=' . $fila2['exte02id'] . ' AND TB.core05idgrupo<>0 
			AND TB.core05idgrupo=T6.core06id AND TB.core05idtutor<>T6.core06idtutor';
			$result = $objDB->ejecutasql($sSQL);
			if ($bDebug) {
				list($sDebugT, $iSegIni) = debug_Cronometrado('Actualizando tutores contenedor ' . $iContenedor . ' periodo ' . $fila2['exte02id'] . ': ' . $sSQL . '', $iSegIni);
				$sDebug = $sDebug . $sDebugT;
			}
		}
		//Armar la tabla de totales.
		$sSQL = 'INSERT INTO ceca92avancegrupo(ceca92idgrupo, ceca92contenedor, ceca92idcurso, ceca92peraca, ceca92idtutor, ceca92numest, ceca92est_0, ceca92est_1, ceca92est_3, ceca92est_5, ceca92est_7) 
		SELECT TB.core05idgrupo, ' . $iContenedor . ', TB.core05idcurso, TB.core05peraca, TB.core05idtutor, COUNT(DISTINCT(TB.core05tercero)), 0, 0, 0, 0, 0
		FROM core05actividades_' . $iContenedor . ' AS TB 
		GROUP BY TB.core05idgrupo, TB.core05idcurso, TB.core05peraca, TB.core05idtutor';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			list($sDebugT, $iSegIni) = debug_Cronometrado('Insertando totales contenedor ' . $iContenedor . ': ' . $sSQL . '', $iSegIni);
			$sDebug = $sDebug . $sDebugT;
		}
		$sSQL = 'UPDATE ceca92avancegrupo AS T92
		SET T92.ceca92est_0=(SELECT COUNT(TB.core05estado)
		FROM core05actividades_' . $iContenedor . ' AS TB
		WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=0 AND TB.core05peraca=T92.ceca92peraca)
		WHERE T92.ceca92contenedor=' . $iContenedor . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			list($sDebugT, $iSegIni) = debug_Cronometrado('Actualizando actividades 0 contenedor ' . $iContenedor . ': ' . $sSQL . '', $iSegIni);
			$sDebug = $sDebug . $sDebugT;
		}
		$sSQL = 'UPDATE ceca92avancegrupo AS T92
		SET T92.ceca92est_1=(SELECT COUNT(TB.core05estado)
		FROM core05actividades_' . $iContenedor . ' AS TB
		WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=1 AND TB.core05peraca=T92.ceca92peraca)
		WHERE T92.ceca92contenedor=' . $iContenedor . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			list($sDebugT, $iSegIni) = debug_Cronometrado('Actualizando actividades 1 contenedor ' . $iContenedor . ': ' . $sSQL . '', $iSegIni);
			$sDebug = $sDebug . $sDebugT;
		}
		$sSQL = 'UPDATE ceca92avancegrupo AS T92
		SET T92.ceca92est_3=(SELECT COUNT(TB.core05estado)
		FROM core05actividades_' . $iContenedor . ' AS TB
		WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=3 AND TB.core05peraca=T92.ceca92peraca)
		WHERE T92.ceca92contenedor=' . $iContenedor . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			list($sDebugT, $iSegIni) = debug_Cronometrado('Actualizando actividades 3 contenedor ' . $iContenedor . ': ' . $sSQL . '', $iSegIni);
			$sDebug = $sDebug . $sDebugT;
		}
		$sSQL = 'UPDATE ceca92avancegrupo AS T92
		SET T92.ceca92est_5=(SELECT COUNT(TB.core05estado)
		FROM core05actividades_' . $iContenedor . ' AS TB
		WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=5 AND TB.core05peraca=T92.ceca92peraca)
		WHERE T92.ceca92contenedor=' . $iContenedor . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			list($sDebugT, $iSegIni) = debug_Cronometrado('Actualizando actividades 5 contenedor ' . $iContenedor . ': ' . $sSQL . '', $iSegIni);
			$sDebug = $sDebug . $sDebugT;
		}
		$sSQL = 'UPDATE ceca92avancegrupo AS T92
		SET T92.ceca92est_7=(SELECT COUNT(TB.core05estado)
		FROM core05actividades_' . $iContenedor . ' AS TB
		WHERE TB.core05idgrupo=T92.ceca92idgrupo AND TB.core05estado=7 AND TB.core05peraca=T92.ceca92peraca)
		WHERE T92.ceca92contenedor=' . $iContenedor . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			list($sDebugT, $iSegIni) = debug_Cronometrado('Actualizando actividades 7 contenedor ' . $iContenedor . ': ' . $sSQL . '', $iSegIni);
			$sDebug = $sDebug . $sDebugT;
		}
	}
	$sSQL = 'OPTIMIZE TABLE ceca92avancegrupo';
	$result = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		list($sDebugT, $iSegIni) = debug_Cronometrado('Optimizando totales: ' . $sSQL . '', $iSegIni);
		$sDebug = $sDebug . $sDebugT;
	}
	//Al final poner los totales en cada grupo.
	$sSQL = 'SELECT exte02id, exte02contgrupos FROM exte02per_aca WHERE exte02id IN (' . $sPeraca . ')';
	$tabla2 = $objDB->ejecutasql($sSQL);
	while ($fila2 = $objDB->sf($tabla2)) {
		$sSQL = 'UPDATE core06grupos_' . $fila2['exte02contgrupos'] . ' AS TB 
		SET TB.core06numinscritos=(SELECT COALESCE(SUM(T2.ceca92numest), 0) 
		FROM ceca92avancegrupo AS T2
		WHERE T2.ceca92idgrupo=TB.core06id AND T2.ceca92peraca=' . $fila2['exte02id'] . ')
		WHERE TB.core06peraca=' . $fila2['exte02id'] . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			list($sDebugT, $iSegIni) = debug_Cronometrado('Totalizando grupos periodo ' . $fila2['exte02id'] . ': ' . $sSQL . '', $iSegIni);
			$sDebug = $sDebug . $sDebugT;
		}
	}
	return array($sError, $sDebug);
}
function f_InciarActividades()
{
	$sSQL = 'UPDATE core05actividades_1 AS TB
	SET TB.core05estado=1
	WHERE TB.core05estado=0 AND TB.core05fechaapertura<=20180911';
}
