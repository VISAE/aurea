<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 3 de mayo de 2021
--- Modelo Versión 2.28.2 martes, 24 de mayo de 2022
--- Modelo Versión 2.28.2 viernes, 22 de julio de 2022
--- 3047 saiu47tramites
*/

/** Archivo lib3047.php.
 * Libreria 3047 saiu47tramites.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date lunes, 3 de mayo de 2021
 */
function f3047_HTMLComboV2_saiu47agno($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu47agno', $valor, false, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SHOW TABLES LIKE "saiu47tramites%"';
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac = $objDB->ejecutasql($sSQL);
	while ($filac = $objDB->sf($tablac)) {
		$sAgno = substr($filac[0], 15);
		$objCombos->addItem($sAgno, $sAgno);
	}
	$res = $objCombos->html('', $objDB);
	return $res;
}
function f3047_HTMLComboV2_saiu47mes($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$res = html_ComboMes('saiu47mes', $valor, false, 'RevisaLlave();');
	return $res;
}
function f3047_HTMLComboV2_saiu47tiporadicado($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu47tiporadicado', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3047_HTMLComboV2_saiu47tipotramite($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu47tipotramite', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'carga_combo_saiu47t1idmotivo()';
	$sSQL = 'SELECT saiu46id AS id, saiu46nombre AS nombre FROM saiu46tipotramite';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3047_HTMLComboV2_saiu47t1idmotivo($objDB, $objCombos, $valor, $vrsaiu47tipotramite)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu47t1idmotivo', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->iAncho = 280;
	$sSQL = '';
	if ((int)$vrsaiu47tipotramite != 0) {
		$sSQL = 'SELECT saiu50id AS id, saiu50nombre AS nombre FROM saiu50motivotramite WHERE saiu50idtipotram=' . $vrsaiu47tipotramite . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3047_HTMLComboV2_saiu47idgrupotrabajo($objDB, $objCombos, $valor, $vrsaiu47idunidad)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu47idgrupotrabajo', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vrsaiu47idunidad != 0) {
		$sSQL = 'SELECT bita27id AS id, bita27nombre AS nombre FROM bita27equipotrabajo WHERE bita27idunidadfunc=' . $vrsaiu47idunidad . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3047_HTMLComboV2_saiu47t707idcuenta($objDB, $objCombos, $valor, $vrsaiu47t707idbanco)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu47t707idcuenta', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vrsaiu47t707idbanco != 0) {
		$sSQL = 'SELECT fact08id AS id, fact08nombre AS nombre FROM fact08cuenta WHERE fact08banco=' . $vrsaiu47t707idbanco . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3047_Combosaiu47t1idmotivo($aParametros)
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
	$html_saiu47t1idmotivo = f3047_HTMLComboV2_saiu47t1idmotivo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu47t1idmotivo', 'innerHTML', $html_saiu47t1idmotivo);
	//$objResponse->call('$("#saiu47t1idmotivo").chosen()');
	return $objResponse;
}
function f3047_Combosaiu47idgrupotrabajo($aParametros)
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
	$html_saiu47idgrupotrabajo = f3047_HTMLComboV2_saiu47idgrupotrabajo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu47idgrupotrabajo', 'innerHTML', $html_saiu47idgrupotrabajo);
	//$objResponse->call('$("#saiu47idgrupotrabajo").chosen()');
	return $objResponse;
}
function f3047_Combosaiu47t707idcuenta($aParametros)
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
	$html_saiu47t707idcuenta = f3047_HTMLComboV2_saiu47t707idcuenta($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu47t707idcuenta', 'innerHTML', $html_saiu47t707idcuenta);
	//$objResponse->call('$("#saiu47t707idcuenta").chosen()');
	return $objResponse;
}
function f3047_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$saiu47agno = numeros_validar($datos[1]);
	if ($saiu47agno == '') {
		$bHayLlave = false;
	}
	$saiu47mes = numeros_validar($datos[2]);
	if ($saiu47mes == '') {
		$bHayLlave = false;
	}
	$saiu47tiporadicado = numeros_validar($datos[3]);
	if ($saiu47tiporadicado == '') {
		$bHayLlave = false;
	}
	$saiu47tipotramite = numeros_validar($datos[4]);
	if ($saiu47tipotramite == '') {
		$bHayLlave = false;
	}
	$saiu47consec = numeros_validar($datos[5]);
	if ($saiu47consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM saiu47tramites WHERE saiu47agno=' . $saiu47agno . ' AND saiu47mes=' . $saiu47mes . ' AND saiu47tiporadicado=' . $saiu47tiporadicado . ' AND saiu47tipotramite=' . $saiu47tipotramite . ' AND saiu47consec=' . $saiu47consec . '';
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
function f3047_Busquedas($aParametros)
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
	$mensajes_3047 = 'lg/lg_3047_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3047)) {
		$mensajes_3047 = 'lg/lg_3047_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3047;
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
	$iPiel = iDefinirPiel($APP, 1);
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'saiu47idsolicitante':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3047);
			break;
		case 'saiu47idbenefdevol':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3047);
			break;
		case 'saiu47idaprueba':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3047);
			break;
		case 'saiu47idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3047);
			break;
		case 'saiu48idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3047);
			break;
		case 'saiu49idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3047);
			break;
		case 'saiu49usuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3047);
			break;
		case 'saiu59idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3047);
			break;
		case 'saiu59idrevisa':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3047);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_3047'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f3047_HtmlBusqueda($aParametros)
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
		case 'saiu47idsolicitante':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu47idbenefdevol':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu47idaprueba':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu47idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu48idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu49idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu49usuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu59idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu59idrevisa':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f3047_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$mensajes_3047 = 'lg/lg_3047_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3047)) {
		$mensajes_3047 = 'lg/lg_3047_es.php';
	}
	require $mensajes_3047;
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
	$idTercero = $aParametros[100];
	$sDebug = '';
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		$bNombre=trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
	$bEstado = numeros_validar($aParametros[105]);
	$bDoc = $aParametros[106];
	$bTipo = numeros_validar($aParametros[107]);
	$bMotivo = numeros_validar($aParametros[108]);
	}
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM Tabla WHERE Id=' . $sValorId;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3047" name="paginaf3047" type="hidden" value="' . $pagina . '"/>
	<input id="lppf3047" name="lppf3047" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 1);
	$aEstado = array();
	$sSQL = 'SELECT saiu60id, saiu60nombre FROM saiu60estadotramite';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['saiu60id']] = cadena_notildes($fila['saiu60nombre']);
	}
	if (true) {
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
		$sSQLadd = '';
		$sSQLadd1 = '';
	if ($bEstado != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu47estado=' . $bEstado . ' AND ';
	}
	if ($bDoc != '') {
		$sSQLadd = $sSQLadd . ' AND T11.unad11doc LIKE "%' . $bDoc . '%"';
	}
	if ($bMotivo != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu47t1idmotivo=' . $bMotivo . ' AND ';
	} else {
		if ($bTipo != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.saiu47tipotramite=' . $bTipo . ' AND ';
		}
	}
		if ($bNombre != '') {
			$sBase = strtoupper($bNombre);
			$aNoms=explode(' ', $sBase);
			for ($k = 1; $k <= count($aNoms); $k++) {
				$sCadena = $aNoms[$k - 1];
					if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T11.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	}
	//-- Area para saltar comparaciones con los archivos tipo e
	//-- Fin del area no comparada
	$sTitulos = 'Agno, Mes, Tiporadicado, Tipotramite, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Solicitante, Periodo, Escuela, Programa, Zona, Centro, Estado, T1idmotivo, T1vrsolicitado, T1vraprobado, T1vrsaldoafavor, T1vrdevolucion, Benefdevol, Aprueba, Fecha, Hora, Minuto, Detalle';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sBase = '';
	$sSQL = 'SHOW TABLES LIKE "saiu47tramites%"';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		if ($sBase != '') {
			$sBase = $sBase . ' UNION ';
		}
		$sTabla = $fila[0];
		$sBase = $sBase . 'SELECT TB.saiu47agno, TB.saiu47mes, TB.saiu47dia, TB.saiu47consec, TB.saiu47estado, TB.saiu47t1idmotivo, 
		TB.saiu47t1vrsolicitado, TB.saiu47id, TB.saiu47idsolicitante, TB.saiu47tipotramite, T11.unad11razonsocial, T11.unad11tipodoc,
		T11.unad11doc 
		FROM ' . $sTabla . ' AS TB, unad11terceros AS T11 
		WHERE ' . $sSQLadd1 . ' TB.saiu47idsolicitante=T11.unad11id ' . $sSQLadd . '';
	}
	$sSQL = '' . $sBase . '
	ORDER BY saiu47tipotramite, saiu47agno DESC, saiu47mes DESC, saiu47consec DESC';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3047" name="consulta_3047" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3047" name="titulos_3047" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3047: ' . $sSQL . $sLimite . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			//if ($registros == 0) {
				//return array($sErrConsulta . $sBotones, $sDebug);
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
	<td><b>' . $ETI['msg_fecha'] . '</b></td>
	<td><b>' . $ETI['saiu47consec'] . '</b></td>
	<td colspan="2"><b>' . $ETI['saiu47idsolicitante'] . '</b></td>
	<td><b>' . $ETI['saiu47estado'] . '</b></td>
	<td><b>' . $ETI['saiu47t1vrsolicitado'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3047', $registros, $lineastabla, $pagina, 'paginarf3047()') . '
	' . html_lpp('lppf3047', $lineastabla, 'paginarf3047()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['saiu47estado']) {
			case 0: // Borrador
			case 5: // Devuelto.
			case 9: // No procedente.
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span>';
				break;
			case 7: // Procedente.
			case 10: // Procesado.
				$sPrefijo = '<span class="verde">';
				$sSufijo = '</span>';
				break;
			case 1:
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
				break;
		}
		$et_fecha = fecha_Armar($filadet['saiu47dia'], $filadet['saiu47mes'], $filadet['saiu47agno']);
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_saiu47idsolicitante_doc = '';
		$et_saiu47idsolicitante_nombre = '';
		if ($filadet['saiu47idsolicitante'] != 0) {
			$et_saiu47idsolicitante_doc = $sPrefijo . $filadet['unad11tipodoc'] . ' ' . $filadet['unad11doc'] . $sSufijo;
			$et_saiu47idsolicitante_nombre = $sPrefijo . cadena_notildes($filadet['unad11razonsocial']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3047(' . $filadet['saiu47agno'] . ', ' . $filadet['saiu47id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $et_fecha . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu47consec'] . $sSufijo . '</td>
		<td>' . $et_saiu47idsolicitante_doc . '</td>
		<td>' . $et_saiu47idsolicitante_nombre . '</td>
		<td>' . $sPrefijo . $aEstado[$filadet['saiu47estado']] . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['saiu47t1vrsolicitado']) . $sSufijo . '</td>
		<td align="right">' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f3047_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3047_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3047detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3047_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['saiu47idsolicitante_td'] = $APP->tipo_doc;
	$DATA['saiu47idsolicitante_doc'] = '';
	$DATA['saiu47idbenefdevol_td'] = $APP->tipo_doc;
	$DATA['saiu47idbenefdevol_doc'] = '';
	$DATA['saiu47idaprueba_td'] = $APP->tipo_doc;
	$DATA['saiu47idaprueba_doc'] = '';
	$DATA['saiu47idresponsable_td'] = $APP->tipo_doc;
	$DATA['saiu47idresponsable_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'saiu47agno=' . $DATA['saiu47agno'] . ' AND saiu47mes=' . $DATA['saiu47mes'] . ' AND saiu47tiporadicado=' . $DATA['saiu47tiporadicado'] . ' AND saiu47tipotramite=' . $DATA['saiu47tipotramite'] . ' AND saiu47consec=' . $DATA['saiu47consec'] . '';
	} else {
		$sSQLcondi = 'saiu47id=' . $DATA['saiu47id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu47tramites WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['saiu47agno'] = $fila['saiu47agno'];
		$DATA['saiu47mes'] = $fila['saiu47mes'];
		$DATA['saiu47tiporadicado'] = $fila['saiu47tiporadicado'];
		$DATA['saiu47tipotramite'] = $fila['saiu47tipotramite'];
		$DATA['saiu47consec'] = $fila['saiu47consec'];
		$DATA['saiu47id'] = $fila['saiu47id'];
		$DATA['saiu47origenagno'] = $fila['saiu47origenagno'];
		$DATA['saiu47origenmes'] = $fila['saiu47origenmes'];
		$DATA['saiu47origenid'] = $fila['saiu47origenid'];
		$DATA['saiu47dia'] = $fila['saiu47dia'];
		$DATA['saiu47hora'] = $fila['saiu47hora'];
		$DATA['saiu47minuto'] = $fila['saiu47minuto'];
		$DATA['saiu47idsolicitante'] = $fila['saiu47idsolicitante'];
		$DATA['saiu47idperiodo'] = $fila['saiu47idperiodo'];
		$DATA['saiu47idescuela'] = $fila['saiu47idescuela'];
		$DATA['saiu47idprograma'] = $fila['saiu47idprograma'];
		$DATA['saiu47idzona'] = $fila['saiu47idzona'];
		$DATA['saiu47idcentro'] = $fila['saiu47idcentro'];
		$DATA['saiu47estado'] = $fila['saiu47estado'];
		$DATA['saiu47t1idmotivo'] = $fila['saiu47t1idmotivo'];
		$DATA['saiu47t1vrsolicitado'] = $fila['saiu47t1vrsolicitado'];
		$DATA['saiu47t1vraprobado'] = $fila['saiu47t1vraprobado'];
		$DATA['saiu47t1vrsaldoafavor'] = $fila['saiu47t1vrsaldoafavor'];
		$DATA['saiu47t1vrdevolucion'] = $fila['saiu47t1vrdevolucion'];
		$DATA['saiu47idbenefdevol'] = $fila['saiu47idbenefdevol'];
		$DATA['saiu47idaprueba'] = $fila['saiu47idaprueba'];
		$DATA['saiu47fechaaprueba'] = $fila['saiu47fechaaprueba'];
		$DATA['saiu47horaaprueba'] = $fila['saiu47horaaprueba'];
		$DATA['saiu47minutoaprueba'] = $fila['saiu47minutoaprueba'];
		$DATA['saiu47detalle'] = $fila['saiu47detalle'];
		$DATA['saiu47idunidad'] = $fila['saiu47idunidad'];
		$DATA['saiu47idgrupotrabajo'] = $fila['saiu47idgrupotrabajo'];
		$DATA['saiu47idresponsable'] = $fila['saiu47idresponsable'];
		$DATA['saiu47t707fecha'] = $fila['saiu47t707fecha'];
		$DATA['saiu47t707formarecaudo'] = $fila['saiu47t707formarecaudo'];
		$DATA['saiu47t707identidadconv'] = $fila['saiu47t707identidadconv'];
		$DATA['saiu47t707idbanco'] = $fila['saiu47t707idbanco'];
		$DATA['saiu47t707idcuenta'] = $fila['saiu47t707idcuenta'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta3047'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3047_Cerrar($saiu47id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f3047_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3047;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3047 = 'lg/lg_3047_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3047)) {
		$mensajes_3047 = 'lg/lg_3047_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3047;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$bCerrando = false;
	$sErrorCerrando = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu47consec'])==0){$DATA['saiu47consec']='';}
	if (isset($DATA['saiu47id'])==0){$DATA['saiu47id']='';}
	if (isset($DATA['saiu47dia'])==0){$DATA['saiu47dia']='';}
	if (isset($DATA['saiu47hora'])==0){$DATA['saiu47hora']='';}
	if (isset($DATA['saiu47minuto'])==0){$DATA['saiu47minuto']='';}
	if (isset($DATA['saiu47idsolicitante'])==0){$DATA['saiu47idsolicitante']='';}
	if (isset($DATA['saiu47estado'])==0){$DATA['saiu47estado']='';}
	if (isset($DATA['saiu47t1idmotivo'])==0){$DATA['saiu47t1idmotivo']='';}
	if (isset($DATA['saiu47t1vrsolicitado'])==0){$DATA['saiu47t1vrsolicitado']='';}
	if (isset($DATA['saiu47t1vraprobado'])==0){$DATA['saiu47t1vraprobado']='';}
	if (isset($DATA['saiu47t1vrsaldoafavor'])==0){$DATA['saiu47t1vrsaldoafavor']='';}
	if (isset($DATA['saiu47t1vrdevolucion'])==0){$DATA['saiu47t1vrdevolucion']='';}
	if (isset($DATA['saiu47idbenefdevol'])==0){$DATA['saiu47idbenefdevol']='';}
	if (isset($DATA['saiu47idaprueba'])==0){$DATA['saiu47idaprueba']='';}
	if (isset($DATA['saiu47fechaaprueba'])==0){$DATA['saiu47fechaaprueba']='';}
	if (isset($DATA['saiu47horaaprueba'])==0){$DATA['saiu47horaaprueba']='';}
	if (isset($DATA['saiu47minutoaprueba'])==0){$DATA['saiu47minutoaprueba']='';}
	if (isset($DATA['saiu47detalle'])==0){$DATA['saiu47detalle']='';}
	*/
	if (isset($DATA['saiu47idunidad']) == 0) {
		$DATA['saiu47idunidad'] = '';
	}
	if (isset($DATA['saiu47idgrupotrabajo']) == 0) {
		$DATA['saiu47idgrupotrabajo'] = '';
	}
	if (isset($DATA['saiu47idresponsable']) == 0) {
		$DATA['saiu47idresponsable'] = '';
	}
	if (isset($DATA['saiu47t707fecha']) == 0) {
		$DATA['saiu47t707fecha'] = '';
	}
	if (isset($DATA['saiu47t707formarecaudo']) == 0) {
		$DATA['saiu47t707formarecaudo'] = 0;
	}
	if (isset($DATA['saiu47t707identidadconv']) == 0) {
		$DATA['saiu47t707identidadconv'] = 0;
	}
	if (isset($DATA['saiu47t707idbanco']) == 0) {
		$DATA['saiu47t707idbanco'] = 0;
	}
	if (isset($DATA['saiu47t707idcuenta']) == 0) {
		$DATA['saiu47t707idcuenta'] = 0;
	}
	$DATA['saiu47consec'] = numeros_validar($DATA['saiu47consec']);
	$DATA['saiu47dia'] = numeros_validar($DATA['saiu47dia']);
	$DATA['saiu47hora'] = numeros_validar($DATA['saiu47hora']);
	$DATA['saiu47minuto'] = numeros_validar($DATA['saiu47minuto']);
	$DATA['saiu47t1idmotivo'] = numeros_validar($DATA['saiu47t1idmotivo']);
	$DATA['saiu47t1vrsolicitado'] = numeros_validar($DATA['saiu47t1vrsolicitado'], true);
	$DATA['saiu47t1vraprobado'] = numeros_validar($DATA['saiu47t1vraprobado'], true);
	$DATA['saiu47t1vrsaldoafavor'] = numeros_validar($DATA['saiu47t1vrsaldoafavor'], true);
	$DATA['saiu47t1vrdevolucion'] = numeros_validar($DATA['saiu47t1vrdevolucion'], true);
	$DATA['saiu47horaaprueba'] = numeros_validar($DATA['saiu47horaaprueba']);
	$DATA['saiu47minutoaprueba'] = numeros_validar($DATA['saiu47minutoaprueba']);
	$DATA['saiu47detalle'] = htmlspecialchars(trim($DATA['saiu47detalle']));
	$DATA['saiu47idunidad'] = numeros_validar($DATA['saiu47idunidad']);
	$DATA['saiu47idgrupotrabajo'] = numeros_validar($DATA['saiu47idgrupotrabajo']);
	$DATA['saiu47t707formarecaudo'] = numeros_validar($DATA['saiu47t707formarecaudo']);
	$DATA['saiu47t707identidadconv'] = numeros_validar($DATA['saiu47t707identidadconv']);
	$DATA['saiu47t707idbanco'] = numeros_validar($DATA['saiu47t707idbanco']);
	$DATA['saiu47t707idcuenta'] = numeros_validar($DATA['saiu47t707idcuenta']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['saiu47origenid'] == '') {
		$DATA['saiu47origenid'] = 0;
	}
	if ($DATA['saiu47dia'] == '') {
		$DATA['saiu47dia'] = fecha_dia();
	}
	if ($DATA['saiu47hora'] == '') {
		$DATA['saiu47hora'] = fecha_hora();
	}
	if ($DATA['saiu47minuto'] == '') {
		$DATA['saiu47minuto'] = fecha_minuto();
	}
	if ($DATA['saiu47idperiodo'] == '') {
		$DATA['saiu47idperiodo'] = 0;
	}
	if ($DATA['saiu47idescuela'] == '') {
		$DATA['saiu47idescuela'] = 0;
	}
	if ($DATA['saiu47idprograma'] == '') {
		$DATA['saiu47idprograma'] = 0;
	}
	if ($DATA['saiu47idzona'] == '') {
		$DATA['saiu47idzona'] = 0;
	}
	if ($DATA['saiu47idcentro'] == '') {
		$DATA['saiu47idcentro'] = 0;
	}
	if ($DATA['saiu47estado'] == '') {
		$DATA['saiu47estado'] = 0;
	}
	if ($DATA['saiu47t1idmotivo'] == '') {
		$DATA['saiu47t1idmotivo'] = 0;
	}
	if ($DATA['saiu47t1vrsolicitado'] == '') {
		$DATA['saiu47t1vrsolicitado'] = 0;
	}
	if ($DATA['saiu47t1vraprobado'] == '') {
		$DATA['saiu47t1vraprobado'] = 0;
	}
	if ($DATA['saiu47t1vrsaldoafavor'] == '') {
		$DATA['saiu47t1vrsaldoafavor'] = 0;
	}
	if ($DATA['saiu47t1vrdevolucion'] == '') {
		$DATA['saiu47t1vrdevolucion'] = 0;
	}
	if ($DATA['saiu47horaaprueba'] == '') {
		$DATA['saiu47horaaprueba'] = 0;
	}
	if ($DATA['saiu47minutoaprueba'] == '') {
		$DATA['saiu47minutoaprueba'] = 0;
	}
	//if ($DATA['saiu47idunidad'] == '') {
		//$DATA['saiu47idunidad'] = 0;
	//}
	//if ($DATA['saiu47idgrupotrabajo'] == '') {
		//$DATA['saiu47idgrupotrabajo'] = 0;
	//}
	if ($DATA['saiu47fechaaprueba'] == '') {
		$DATA['saiu47fechaaprueba'] = 0;
	}
	if ($DATA['saiu47t707formarecaudo'] == '') {
		$DATA['saiu47t707formarecaudo'] = 0;
	}
	if ($DATA['saiu47t707identidadconv'] == '') {
		$DATA['saiu47t707identidadconv'] = 0;
	}
	if ($DATA['saiu47t707idbanco'] == '') {
		$DATA['saiu47t707idbanco'] = 0;
	}
	if ($DATA['saiu47t707idcuenta'] == '') {
		$DATA['saiu47t707idcuenta'] = 0;
	}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	switch ($DATA['saiu47estado']) {
		case 0:
			switch ($DATA['saiu47tipotramite']) {
				case 1: // Devolucion.
				case 707: // Reconocimiento de recaudo
					if (trim($DATA['saiu47detalle']) == '') {
						$sError = $ERR['saiu47detalle'] . $sSepara . $sError;
					}
					if ((int)$DATA['saiu47t1vrsolicitado'] == 0) {
						$sError = $ERR['saiu47t1vrsolicitado'] . $sSepara . $sError;
					}
					if ((int)$DATA['saiu47t1idmotivo'] == 0) {
						$sError = $ERR['saiu47t1idmotivo'] . $sSepara . $sError;
					}
					if ($DATA['saiu47tipotramite'] == 707){
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Valor de la fecha ' . $DATA['saiu47t707fecha'] . '<br>';
						}
						if (!fecha_NumValido($DATA['saiu47t707fecha'])) {
							$sError = $ERR['saiu47t707fecha'] . $sSepara . $sError;
						}
					}else{
						$DATA['saiu47t707fecha'] = 0;
					}
					break;
				default:
					$sError = 'Tipo de tramite no permitido';
					break;
			}
			break;
	}
	if (false) {
		//if ($DATA['saiu47detalle']==''){$sError=$ERR['saiu47detalle'].$sSepara.$sError;}
		//if ($DATA['saiu47minutoaprueba']==''){$sError=$ERR['saiu47minutoaprueba'].$sSepara.$sError;}
		//if ($DATA['saiu47horaaprueba']==''){$sError=$ERR['saiu47horaaprueba'].$sSepara.$sError;}
		if ($DATA['saiu47fechaaprueba'] == 0) {
			//$DATA['saiu47fechaaprueba']=fecha_DiaMod();
			$sError = $ERR['saiu47fechaaprueba'] . $sSepara . $sError;
		}
		if ($DATA['saiu47idaprueba'] == 0) {
			$sError = $ERR['saiu47idaprueba'] . $sSepara . $sError;
		}
		if ($DATA['saiu47idbenefdevol'] == 0) {
			$sError = $ERR['saiu47idbenefdevol'] . $sSepara . $sError;
		}
		if ($DATA['saiu47t1vrdevolucion'] == '') {
			$sError = $ERR['saiu47t1vrdevolucion'] . $sSepara . $sError;
		}
		if ($DATA['saiu47t1vrsaldoafavor'] == '') {
			$sError = $ERR['saiu47t1vrsaldoafavor'] . $sSepara . $sError;
		}
		if ($DATA['saiu47t1vraprobado'] == '') {
			$sError = $ERR['saiu47t1vraprobado'] . $sSepara . $sError;
		}
		if ($DATA['saiu47t1vrsolicitado'] == '') {
			$sError = $ERR['saiu47t1vrsolicitado'] . $sSepara . $sError;
		}
		if ($DATA['saiu47idsolicitante'] == 0) {
			$sError = $ERR['saiu47idsolicitante'] . $sSepara . $sError;
		}
		if ($DATA['saiu47minuto'] == '') {
			$sError = $ERR['saiu47minuto'] . $sSepara . $sError;
		}
		if ($DATA['saiu47hora'] == '') {
			$sError = $ERR['saiu47hora'] . $sSepara . $sError;
		}
		if ($DATA['saiu47dia'] == '') {
			$sError = $ERR['saiu47dia'] . $sSepara . $sError;
		}
		if ($sError != '') {
			$DATA['saiu47estado'] = 0;
		}
		$sErrorCerrando = $sError;
		$sError = '';
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$sTabla47 = 'saiu47tramites_' . $DATA['saiu47agno'];
		if (!$objDB->bexistetabla($sTabla47)) {
			$sError = 'No ha sido posible acceder al contenedor de datos';
		}
	}
	if ($sError == '') {
		//Mirar que no exista otro tramite en estado borrador.
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT 1 FROM ' . $sTabla47 . ' WHERE saiu47tipotramite=' . $DATA['saiu47tipotramite'] . ' AND saiu47idsolicitante=' . $DATA['saiu47idsolicitante'] . ' AND saiu47estado=0';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sError = 'Ya existe una solicitud previa en estado borrador, no se permite crear nuevas solicitudes hasta tanto no se completen los borradores.';
			}
		}
	}
	// -- Tiene un cerrado. 
	// --- las validaciones del cierre las dejamos en la forma... porque la vandera de cierre no se hace aqui...
	if ($DATA['saiu47estado'] == 1) {
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError . $sErrorCerrando != '') {
			$DATA['saiu47estado'] = 0;
			$sErrorCerrando = $sError . ' ' . $sErrorCerrando;
			$sError = '';
		} else {
			$bCerrando = true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu47idaprueba_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu47idaprueba_td'], $DATA['saiu47idaprueba_doc'], $objDB, 'El tercero Aprueba ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu47idaprueba'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['saiu47idbenefdevol_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu47idbenefdevol_td'], $DATA['saiu47idbenefdevol_doc'], $objDB, 'El tercero Benefdevol ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu47idbenefdevol'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['saiu47idsolicitante_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu47idsolicitante_td'], $DATA['saiu47idsolicitante_doc'], $objDB, 'El tercero Solicitante ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu47idsolicitante'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['saiu47consec'] == '') {
				$DATA['saiu47consec'] = tabla_consecutivo($sTabla47, 'saiu47consec', 'saiu47agno=' . $DATA['saiu47agno'] . ' AND saiu47mes=' . $DATA['saiu47mes'] . ' AND saiu47tiporadicado=' . $DATA['saiu47tiporadicado'] . ' AND saiu47tipotramite=' . $DATA['saiu47tipotramite'] . '', $objDB);
				if ($DATA['saiu47consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'saiu47consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['saiu47consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sTabla47 . ' WHERE saiu47agno=' . $DATA['saiu47agno'] . ' AND saiu47mes=' . $DATA['saiu47mes'] . ' AND saiu47tiporadicado=' . $DATA['saiu47tiporadicado'] . ' AND saiu47tipotramite=' . $DATA['saiu47tipotramite'] . ' AND saiu47consec=' . $DATA['saiu47consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					if ($idTercero != $_SESSION['unad_id_tercero']) {
						list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $_SESSION['unad_id_tercero'], $objDB);
						if (!$bDevuelve) {
							$sError = $ERR['2'] . ' [Mod ' . $iCodModulo . ']';
						}
					}
				}
			}
		} else {
			if ($idTercero != $_SESSION['unad_id_tercero']) {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $_SESSION['unad_id_tercero'], $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['3'];
				}
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$DATA['saiu47idunidad'] = 0;
			$DATA['saiu47idgrupotrabajo'] = 0;
			if ((int)$DATA['saiu47idescuela'] == 0) {
				list($idEscuela, $idPrograma, $idZona, $idCentro, $sDebugP) = f111_ProgramaCentro($DATA['saiu47idsolicitante'], $objDB, $bDebug);
				$DATA['saiu47idescuela'] = $idEscuela;
				$DATA['saiu47idprograma'] = $idPrograma;
				$DATA['saiu47idzona'] = $idZona;
				$DATA['saiu47idcentro'] = $idCentro;
			}
			//Saber a que grupo de trabajo le envio la solicitud.

			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu47id'] = tabla_consecutivo($sTabla47, 'saiu47id', '', $objDB);
			if ($DATA['saiu47id'] == -1) {
				$sError = $objDB->serror;
			}
		}
	}
	if ($sError == '') {
		//Si el campo saiu47detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu47detalle=addslashes($DATA['saiu47detalle']);
		$saiu47detalle = str_replace('"', '\"', $DATA['saiu47detalle']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$DATA['saiu47origenagno'] = 0;
			$DATA['saiu47origenmes'] = 0;
			$DATA['saiu47origenid'] = 0;
			$DATA['saiu47estado'] = 0;
			$sCampos3047 = 'saiu47agno, saiu47mes, saiu47tiporadicado, saiu47tipotramite, saiu47consec, 
			saiu47id, saiu47origenagno, saiu47origenmes, saiu47origenid, saiu47dia, 
			saiu47hora, saiu47minuto, saiu47idsolicitante, saiu47idperiodo, saiu47idescuela, 
			saiu47idprograma, saiu47idzona, saiu47idcentro, saiu47estado, saiu47t1idmotivo, 
			saiu47t1vrsolicitado, saiu47t1vraprobado, saiu47t1vrsaldoafavor, saiu47t1vrdevolucion, saiu47idbenefdevol, 
			saiu47idaprueba, saiu47fechaaprueba, saiu47horaaprueba, saiu47minutoaprueba, saiu47detalle, 
			saiu47idunidad, saiu47idgrupotrabajo, saiu47idresponsable, saiu47t707fecha, saiu47t707formarecaudo, 
			saiu47t707identidadconv, saiu47t707idbanco, saiu47t707idcuenta';
			$sValores3047 = '' . $DATA['saiu47agno'] . ', ' . $DATA['saiu47mes'] . ', ' . $DATA['saiu47tiporadicado'] . ', ' . $DATA['saiu47tipotramite'] . ', ' . $DATA['saiu47consec'] . ', 
			' . $DATA['saiu47id'] . ', ' . $DATA['saiu47origenagno'] . ', ' . $DATA['saiu47origenmes'] . ', ' . $DATA['saiu47origenid'] . ', ' . $DATA['saiu47dia'] . ', 
			' . $DATA['saiu47hora'] . ', ' . $DATA['saiu47minuto'] . ', ' . $DATA['saiu47idsolicitante'] . ', ' . $DATA['saiu47idperiodo'] . ', ' . $DATA['saiu47idescuela'] . ', 
			' . $DATA['saiu47idprograma'] . ', ' . $DATA['saiu47idzona'] . ', ' . $DATA['saiu47idcentro'] . ', "' . $DATA['saiu47estado'] . '", ' . $DATA['saiu47t1idmotivo'] . ', 
			' . $DATA['saiu47t1vrsolicitado'] . ', ' . $DATA['saiu47t1vraprobado'] . ', ' . $DATA['saiu47t1vrsaldoafavor'] . ', ' . $DATA['saiu47t1vrdevolucion'] . ', ' . $DATA['saiu47idbenefdevol'] . ', 
			' . $DATA['saiu47idaprueba'] . ', ' . $DATA['saiu47fechaaprueba'] . ', ' . $DATA['saiu47horaaprueba'] . ', ' . $DATA['saiu47minutoaprueba'] . ', "' . $saiu47detalle . '", 
			' . $DATA['saiu47idunidad'] . ', ' . $DATA['saiu47idgrupotrabajo'] . ', ' . $DATA['saiu47idresponsable'] . ', ' . $DATA['saiu47t707fecha'] . ', ' . $DATA['saiu47t707formarecaudo'] . ', 
			' . $DATA['saiu47t707identidadconv'] . ', ' . $DATA['saiu47t707idbanco'] . ', ' . $DATA['saiu47t707idcuenta'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla47 . ' (' . $sCampos3047 . ') VALUES (' . utf8_encode($sValores3047) . ');';
				$sdetalle = $sCampos3047 . '[' . utf8_encode($sValores3047) . ']';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla47 . ' (' . $sCampos3047 . ') VALUES (' . $sValores3047 . ');';
				$sdetalle = $sCampos3047 . '[' . $sValores3047 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'saiu47dia';
			$scampo[2] = 'saiu47hora';
			$scampo[3] = 'saiu47minuto';
			$scampo[4] = 'saiu47idsolicitante';
			$scampo[5] = 'saiu47detalle';
			//$scampo[5]='saiu47estado';
			$scampo[6] = 'saiu47t1idmotivo';
			$scampo[7] = 'saiu47t1vrsolicitado';
			$scampo[8] = 'saiu47t707fecha';
			$scampo[9] = 'saiu47t707formarecaudo';
			$scampo[10] = 'saiu47t707identidadconv';
			$scampo[11] = 'saiu47t707idbanco';
			$scampo[12] = 'saiu47t707idcuenta';
			$sdato[1] = $DATA['saiu47dia'];
			$sdato[2] = $DATA['saiu47hora'];
			$sdato[3] = $DATA['saiu47minuto'];
			$sdato[4] = $DATA['saiu47idsolicitante'];
			$sdato[5] = $saiu47detalle;
			$sdato[6] = $DATA['saiu47t1idmotivo'];
			$sdato[7] = $DATA['saiu47t1vrsolicitado'];
			$sdato[8] = $DATA['saiu47t707fecha'];
			$sdato[9] = $DATA['saiu47t707formarecaudo'];
			$sdato[10] = $DATA['saiu47t707identidadconv'];
			$sdato[11] = $DATA['saiu47t707idbanco'];
			$sdato[12] = $DATA['saiu47t707idcuenta'];
			$numcmod = 12;
			$sWhere = 'saiu47id=' . $DATA['saiu47id'] . '';
			$sSQL = 'SELECT * FROM ' . $sTabla47 . ' WHERE ' . $sWhere;
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
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla47 . ' SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla47 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3047 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3047] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['saiu47id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
				$bCerrando = false;
			} else {
				list($sErrorC, $sDebugC) = f3047_CargarDocumentos($DATA['saiu47agno'], $DATA['saiu47id'], $objDB, $bDebug);
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu47id'], $sdetalle, $objDB);
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
		$bCerrando = false;
		if ($bQuitarCodigo) {
			if ($sCampoCodigo != '') {
				$DATA[$sCampoCodigo] = '';
			}
		}
	}
	$sInfoCierre = '';
	if ($bCerrando) {
		list($sErrorCerrando, $sDebugCerrar) = f3047_Cerrar($DATA['saiu47id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugCerrar;
	}
	/*
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' InfoDepura<br>';
	}
	*/
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
}
function f3047_db_Eliminar($saiu47agno, $saiu47id, $objDB, $bDebug = false)
{
	$iCodModulo = 3047;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3047 = 'lg/lg_3047_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3047)) {
		$mensajes_3047 = 'lg/lg_3047_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3047;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu47id = numeros_validar($saiu47id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sTabla47 = 'saiu47tramites_' . $saiu47agno . '';
		if (!$objDB->bexistetabla($sTabla47)) {
			$sError = 'No ha sido posible acceder al contenedor de datos';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT * FROM ' . $sTabla47 . ' WHERE saiu47id=' . $saiu47id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $saiu47id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM saiu48anotaciones_' . $saiu47agno . ' WHERE saiu48idtramite=' . $filabase['saiu47id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Anotaciones creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM saiu59tramiteanexo_' . $saiu47agno . ' WHERE saiu59idtramite=' . $filabase['saiu47id'] . ' AND saiu59fecharevisa<>0';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Anexos aprobados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		if (isset($idTercero) == 0) {
			$idTercero = $_SESSION['unad_id_tercero'];
		}
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3047';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['saiu47id'] . ' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM saiu48anotaciones WHERE saiu48idtramite='.$filabase['saiu47id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sSQL = 'DELETE FROM saiu49cambioesttra_' . $saiu47agno . ' WHERE saiu49idtramite=' . $filabase['saiu47id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sSQL = 'DELETE FROM saiu59tramiteanexo_' . $saiu47agno . ' WHERE saiu59idtramite=' . $filabase['saiu47id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'saiu47id=' . $saiu47id . '';
		//$sWhere='saiu47consec='.$filabase['saiu47consec'].' AND saiu47tipotramite='.$filabase['saiu47tipotramite'].' AND saiu47tiporadicado='.$filabase['saiu47tiporadicado'].' AND saiu47mes='.$filabase['saiu47mes'].' AND saiu47agno='.$filabase['saiu47agno'].'';
		$sSQL = 'DELETE FROM ' . $sTabla47 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu47id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f3047_TituloBusqueda()
{
	return 'Busqueda de Tramites';
}
function f3047_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3047 = 'lg/lg_3047_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3047)) {
		$mensajes_3047 = 'lg/lg_3047_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3047;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b3047nombre" name="b3047nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f3047_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo = window.document.frmedita.scampobusca.value;
	var params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b3047nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f3047_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3047 = 'lg/lg_3047_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3047)) {
		$mensajes_3047 = 'lg/lg_3047_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3047;
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
	$idTercero = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM Tabla WHERE Id=' . $sValorId;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3047" name="paginaf3047" type="hidden" value="' . $pagina . '" />
	<input id="lppf3047" name="lppf3047" type="hidden" value="' . $lineastabla . '" />';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 1);
	$sSQLadd = '';
	$sSQLadd1 = '';
	/*
	if ($aParametros[103] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.campo2 LIKE "%' . $aParametros[103] . '%" AND ';
	}
	if ($aParametros[103] != '') {
		$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[103] . '%"';
	}
	if ($aParametros[104] != '') {
		$sBase = trim(strtoupper($aParametros[104]));
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'T1.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	$sTitulos = 'Agno, Mes, Tiporadicado, Tipotramite, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Solicitante, Periodo, Escuela, Programa, Zona, Centro, Estado, T1idmotivo, T1vrsolicitado, T1vraprobado, T1vrsaldoafavor, T1vrdevolucion, Benefdevol, Aprueba, Fechaaprueba, Horaaprueba, Minutoaprueba, Detalle';
	$sSQL = 'SELECT TB.saiu47agno, TB.saiu47mes, T3.saiu16nombre, T4.saiu46nombre, TB.saiu47consec, TB.saiu47id, TB.saiu47origenagno, TB.saiu47origenmes, TB.saiu47origenid, TB.saiu47dia, TB.saiu47hora, TB.saiu47minuto, T13.unad11razonsocial AS C13_nombre, T14.exte02nombre, T15.core12nombre, T16.core09nombre, T17.unad23nombre, T18.unad24nombre, TB.saiu47estado, T20.saiu50nombre, TB.saiu47t1vrsolicitado, TB.saiu47t1vraprobado, TB.saiu47t1vrsaldoafavor, TB.saiu47t1vrdevolucion, T25.unad11razonsocial AS C25_nombre, T26.unad11razonsocial AS C26_nombre, TB.saiu47fechaaprueba, TB.saiu47horaaprueba, TB.saiu47minutoaprueba, TB.saiu47detalle, TB.saiu47tiporadicado, TB.saiu47tipotramite, TB.saiu47idsolicitante, T13.unad11tipodoc AS C13_td, T13.unad11doc AS C13_doc, TB.saiu47idperiodo, TB.saiu47idescuela, TB.saiu47idprograma, TB.saiu47idzona, TB.saiu47idcentro, TB.saiu47t1idmotivo, TB.saiu47idbenefdevol, T25.unad11tipodoc AS C25_td, T25.unad11doc AS C25_doc, TB.saiu47idaprueba, T26.unad11tipodoc AS C26_td, T26.unad11doc AS C26_doc 
	FROM saiu47tramites AS TB, saiu16tiporadicado AS T3, saiu46tipotramite AS T4, unad11terceros AS T13, exte02per_aca AS T14, core12escuela AS T15, core09programa AS T16, unad23zona AS T17, unad24sede AS T18, saiu50motivotramite AS T20, unad11terceros AS T25, unad11terceros AS T26 
	WHERE ' . $sSQLadd1 . ' TB.saiu47tiporadicado=T3.saiu16id AND TB.saiu47tipotramite=T4.saiu46id AND TB.saiu47idsolicitante=T13.unad11id AND TB.saiu47idperiodo=T14.exte02id AND TB.saiu47idescuela=T15.core12id AND TB.saiu47idprograma=T16.core09id AND TB.saiu47idzona=T17.unad23id AND TB.saiu47idcentro=T18.unad24id AND TB.saiu47t1idmotivo=T20.saiu50id AND TB.saiu47idbenefdevol=T25.unad11id AND TB.saiu47idaprueba=T26.unad11id ' . $sSQLadd . '
	ORDER BY TB.saiu47agno, TB.saiu47mes, TB.saiu47tiporadicado, TB.saiu47tipotramite, TB.saiu47consec';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="' . $sSQLlista . '" />
	<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="' . $sTitulos . '" />';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '" />';
		//$sLeyenda = $sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		/*
		if ($registros == 0) {
			return array($sErrConsulta . $sBotones, $sDebug);
		}
		*/
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
	<td><b>' . $ETI['saiu47agno'] . '</b></td>
	<td><b>' . $ETI['saiu47mes'] . '</b></td>
	<td><b>' . $ETI['saiu47tiporadicado'] . '</b></td>
	<td><b>' . $ETI['saiu47tipotramite'] . '</b></td>
	<td><b>' . $ETI['saiu47consec'] . '</b></td>
	<td><b>' . $ETI['saiu47origenagno'] . '</b></td>
	<td><b>' . $ETI['saiu47origenmes'] . '</b></td>
	<td><b>' . $ETI['saiu47origenid'] . '</b></td>
	<td><b>' . $ETI['saiu47dia'] . '</b></td>
	<td><b>' . $ETI['saiu47hora'] . '</b></td>
	<td colspan="2"><b>' . $ETI['saiu47idsolicitante'] . '</b></td>
	<td><b>' . $ETI['saiu47idperiodo'] . '</b></td>
	<td><b>' . $ETI['saiu47idescuela'] . '</b></td>
	<td><b>' . $ETI['saiu47idprograma'] . '</b></td>
	<td><b>' . $ETI['saiu47idzona'] . '</b></td>
	<td><b>' . $ETI['saiu47idcentro'] . '</b></td>
	<td><b>' . $ETI['saiu47estado'] . '</b></td>
	<td><b>' . $ETI['saiu47t1idmotivo'] . '</b></td>
	<td><b>' . $ETI['saiu47t1vrsolicitado'] . '</b></td>
	<td><b>' . $ETI['saiu47t1vraprobado'] . '</b></td>
	<td><b>' . $ETI['saiu47t1vrsaldoafavor'] . '</b></td>
	<td><b>' . $ETI['saiu47t1vrdevolucion'] . '</b></td>
	<td colspan="2"><b>' . $ETI['saiu47idbenefdevol'] . '</b></td>
	<td colspan="2"><b>' . $ETI['saiu47idaprueba'] . '</b></td>
	<td><b>' . $ETI['saiu47fechaaprueba'] . '</b></td>
	<td><b>' . $ETI['saiu47horaaprueba'] . '</b></td>
	<td><b>' . $ETI['saiu47detalle'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['saiu47id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_saiu47hora = html_TablaHoraMin($filadet['saiu47hora'], $filadet['saiu47minuto']);
		$et_saiu47estado = $ETI['msg_abierto'];
		if ($filadet['saiu47estado'] == 'S') {
			$et_saiu47estado = $ETI['msg_cerrado'];
		}
		$et_saiu47fechaaprueba = '';
		if ($filadet['saiu47fechaaprueba'] != 0) {
			$et_saiu47fechaaprueba = fecha_desdenumero($filadet['saiu47fechaaprueba']);
		}
		$et_saiu47horaaprueba = html_TablaHoraMin($filadet['saiu47horaaprueba'], $filadet['saiu47minutoaprueba']);
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . $filadet['saiu47agno'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu47mes'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu16nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu46nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu47consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu47origenagno'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu47origenmes'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu47origenid'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu47dia'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu47hora . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C13_td'] . ' ' . $filadet['C13_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C13_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu47estado . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu50nombre']) . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['saiu47t1vrsolicitado']) . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['saiu47t1vraprobado']) . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['saiu47t1vrsaldoafavor']) . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['saiu47t1vrdevolucion']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C25_td'] . ' ' . $filadet['C25_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C25_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C26_td'] . ' ' . $filadet['C26_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C26_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu47fechaaprueba . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu47horaaprueba . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu47detalle'] . $sSufijo . '</td>
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
function f3047_CargarDocumentos($iAgno, $saiu47id, $objDB, $bDebug = false, $bForzar = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla47 = 'saiu47tramites_' . $iAgno . '';
	$sTabla59 = 'saiu59tramiteanexo_' . $iAgno;
	if (!$objDB->bexistetabla($sTabla47)) {
		$sError = 'No ha sido posible acceder al contenedor de datos';
	} else {
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sTabla59 . ' WHERE saiu59idtramite=' . $saiu47id . ';';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Ya existen documentos cargados.';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu47tipotramite, saiu47idsolicitante FROM ' . $sTabla47 . ' WHERE saiu47id=' . $saiu47id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saiu47tipotramite = $fila['saiu47tipotramite'];
			$saiu59idusuario = $fila['saiu47idsolicitante'];
		} else {
			$sError = 'No se ha encontrado el registro solicitado [Ref ' . $saiu47id . ' A&ntilde;o ' . $iAgno . ']';
		}
	}
	if ($sError == '') {
		$sCampos3059 = 'saiu59idtramite, saiu59consec, saiu59id, saiu59idtipodoc, saiu59opcional, 
		saiu59idestado, saiu59idorigen, saiu59idarchivo, saiu59idusuario, saiu59fecha, 
		saiu59idrevisa, saiu59fecharevisa';
		$saiu59consec = tabla_consecutivo($sTabla59, 'saiu59consec', 'saiu59idtramite=' . $saiu47id . '', $objDB);
		$saiu59id = tabla_consecutivo($sTabla59, 'saiu59id', '', $objDB);
		$sSQL = 'SELECT saiu51id, saiu51opcional 
		FROM saiu51tramitedoc 
		WHERE saiu51idtipotram=' . $saiu47tipotramite . ' AND saiu51vigente=1 AND saiu51proveedor=0 
		ORDER BY saiu51orden';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sValores3059 = '' . $saiu47id . ', ' . $saiu59consec . ', ' . $saiu59id . ', ' . $fila['saiu51id'] . ', ' . $fila['saiu51opcional'] . ', 
			0, 0, 0, ' . $saiu59idusuario . ', 0, 
			0, 0';
			$sSQL = 'INSERT INTO ' . $sTabla59 . ' (' . $sCampos3059 . ') VALUES (' . $sValores3059 . ');';
			$result = $objDB->ejecutasql($sSQL);
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Insertando: ' . $sSQL . '<br>';
			}
			$saiu59consec++;
			$saiu59id++;
		}
	}
	return array($sError, $sDebug);
}
//
function f3047_NormatividadDevoluciones()
{
	$sRes = '';
	return $sRes;
}
//
function f3047_NotificarEvento($saiu47agno, $saiu47id, $objDB, $bDebug = false, $bNotificaFuncionario = false)
{
	$sDebug = '';
	$sError = '';
	$sMensaje = '';
	$sTabla47 = 'saiu47tramites_' . $saiu47agno . '';
	if (!$objDB->bexistetabla($sTabla47)) {
		$sError = 'No ha sido posible acceder al contenedor de datos';
	}
	if ($sError == '') {
		$sSQL = 'SELECT TB.saiu47consec, TB.saiu47tipotramite, TB.saiu47idsolicitante, TB.saiu47estado 
		FROM ' . $sTabla47 . ' AS TB
		WHERE TB.saiu47id=' . $saiu47id;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idSolicitante = $fila['saiu47idsolicitante'];
			$saiu47estado = $fila['saiu47estado'];
			$saiu47tipotramite = $fila['saiu47tipotramite'];
			$bConDetalle = false;
			$bConActa = false;
			$bNotificaFinal = false;
			$sSQL = 'SELECT 1 
			FROM saiu48anotaciones_' . $saiu47agno . ' AS TB, unad11terceros AS T11 
			WHERE TB.saiu48idtramite=' . $saiu47id . ' AND TB.saiu48idusuario=T11.unad11id';
			$tabla48 = $objDB->ejecutasql($sSQL);
			$iNumAnotaciones = $objDB->nf($tabla48);
			$sNombreTipoTramite = '{' . $saiu47tipotramite . '}';
			switch ($saiu47tipotramite) {
				case 1:
					$sNombreTipoTramite = 'Solicitud de devolución de derechos pecuniarios';
					break;
			}
			$sTituloMensaje = 'Notificación de cambio de estado en ' . $sNombreTipoTramite . ' ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '';
			$sCuerpo = 'Estimado usuario:<br><br>
			Le informamos que su <b>' . $sNombreTipoTramite . ' N&deg; ' . $fila['saiu47consec'] . ' del ' . $saiu47agno . '</b>';
			switch ($saiu47estado) {
				case 0:
					$sCuerpo = $sCuerpo . ' se encuentra en estado borrador, esto sucede porque no se han adjuntado los documentos soporte de su solicitud<br><br>';
					if ($iNumAnotaciones > 0) {
						$bConDetalle = true;
					}
					break;
				case 1: //Radicada
				case 3: //En revisión.
					$sCuerpo = $sCuerpo . ' se encuentra en estado Radicada';
					if ($iNumAnotaciones > 0) {
						$bConDetalle = true;
						$sCuerpo = $sCuerpo . ', tiene las siguientes anotaciones:';
					}
					$sCuerpo = $sCuerpo . '<br><br>';
					break;
				case 5: //Devuelto
					$sCuerpo = $sCuerpo . ' ha sido <b>DEVUELTA</b> por favor revise las anotaciones.<br><br>';
					if ($iNumAnotaciones > 0) {
						$bConDetalle = true;
					}
					break;
				case 6: //En estudio
					$sCuerpo = $sCuerpo . ' ha sido <b>aceptada para estudio</b>.<br><br>';
					if ($iNumAnotaciones > 0) {
						$bConDetalle = true;
					}
					break;
				case 7: //Procedente  (debe tener resolucion.)
					$sCuerpo = $sCuerpo . ' ha sido considerada <b>PROCEDENTE</b>.<br><br>';
					if ($iNumAnotaciones > 0) {
						$bConDetalle = true;
					}
					$bConActa = true;
					break;
				case 9: //No procedente
					$sCuerpo = $sCuerpo . ' ha sido considerada <b>NO PROCEDENTE</b>.<br><br>';
					if ($iNumAnotaciones > 0) {
						$bConDetalle = true;
					}
					$bConActa = true;
					break;
				case 99: //ANULADO
					$sCuerpo = $sCuerpo . ' ha sido <b>ANULADA</b>';
					if ($iNumAnotaciones > 0) {
						$bConDetalle = true;
						$sCuerpo = $sCuerpo . ', tiene las siguientes anotaciones:';
					}
					$sCuerpo = $sCuerpo . '<br><br>';
					break;
				default:
					$sError = 'No se requiere notificaci&oacute;n.';
					break;
			}
			//Inicia la notificacion de documentos
			$sLinks = '';
			if ($bConActa) {
				//Solo enviamos los documentos que sean habilitados para notificaciones.
				$sIdsDocumentos = '-99';
				$sSQL = 'SELECT saiu51id FROM saiu51tramitedoc WHERE saiu51visible=1';
				$tabla51 = $objDB->ejecutasql($sSQL);
				while ($fila51 = $objDB->sf($tabla51)) {
					$sIdsDocumentos = $sIdsDocumentos . ',' . $fila51['saiu51id'];
				}
				$sSQL = 'SELECT TB.saiu59idorigen, TB.saiu59idarchivo, T12.saiu60nombre 
				FROM saiu59tramiteanexo AS TB, saiu51tramitedoc AS T12 
				WHERE TB.saiu59idtramite=' . $saiu47id . ' AND TB.saiu59idtipodoc=T12.saiu51id
				ORDER BY TB.saiu59consec';
				$tabla21 = $objDB->ejecutasql($sSQL);
				while ($fila21 = $objDB->sf($tabla21)) {
					$sRaiz = 'https://aurea.unad.edu.co/dp.php';
					$sNombreActoAdmin = cadena_notildes($fila21['saiu60nombre']);
					if ($sLinks != '') {
						$sLinks = $sLinks . '<br>';
					}
					$$sLinks = $sLinks . html_LnkArchivoV2((int)$fila21['saiu59idorigen'], (int)$fila21['saiu59idarchivo'], $sNombreActoAdmin, $sRaiz, '');
				}
			}
			if ($sLinks != '') {
				if ($bNotificaFinal) {
					$sTituloMensaje = 'Notificación ' . $sNombreTipoTramite . ' - ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '';
					$sCuerpo = $sCuerpo . 'Dando cumplimiento a la autorizaci&oacute;n de notificaci&oacute;n electr&oacute;nica 
					impartida al momento de radicar su solicitud, mediante la presente me permito notificar de forma electr&oacute;nica 
					los siguientes documentos ' . $sLinkActo . ', los cuales puede descargar haciendo clic sobre el nombre del mismo, 
					la presente notificaci&oacute;n se hace de acuerdo con lo contemplado en el art&iacute;culo 56 de la Ley 1437 de 2011.<br><br>';
					//if ($corf06fecharesposicion==0){}
					if (false) {
						$sCuerpo = $sCuerpo . 'En atenci&oacute;n de lo anterior, usted cuenta con un t&eacute;rmino de diez (10) d&iacute;as h&aacute;biles 
						para interponer recurso en contra de la misma, en caso de no encontrarse de acuerdo con su contenido<br><br>';
					}
				} else {
					$sCuerpo = $sCuerpo . 'Este tramite tiene los siguientes documentos anexos: <br>' . $sLinks . '<br>';
				}
			}
			//Termina la notificacion de documentos.
			if ($bConDetalle) {
				$sSQL = 'SELECT TB.saiu48fecha, TB.saiu48hora, TB.saiu48minuto, TB.saiu48anotacion, TB.saiu48idusuario, T11.unad11razonsocial 
				FROM saiu48anotaciones_' . $saiu47agno . ' AS TB, unad11terceros AS T11 
				WHERE TB.saiu48idtramite=' . $saiu47id . ' AND TB.saiu48idusuario=T11.unad11id
				ORDER BY TB.saiu48consec';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					$sNomUsuario = ' ' . cadena_notildes($fila['unad11razonsocial']) . ' dice:<br>';
					if ($idSolicitante == $fila['saiu48idusuario']) {
						$sNomUsuario = ' Usted dice:<br>';
					}
					$sCuerpo = $sCuerpo . fecha_desdenumero($fila['saiu48fecha']) . ' ' . html_TablaHoraMin($fila['saiu48hora'], $fila['saiu48minuto']);
					$sCuerpo = $sCuerpo . $sNomUsuario . '' . cadena_notildes(nl2br($fila['saiu48anotacion'])) . '<br>';
					$sCuerpo = $sCuerpo . '<hr>';
				}
				$sCuerpo = $sCuerpo . '<br>';
			}
			switch ($saiu47tipotramite) {
				case 1: //Deevoluciones.
					switch ($saiu47estado) {
						case 0: //Borrador
						case 1: //Radicado
						case 3: //En revision.
						case 5: //Devuelto
							$sCuerpo = $sCuerpo . f3047_NormatividadDevoluciones();
							break;
					}
					break;
			}
		} else {
			$sError = 'No se ha encontrado el registro solicitado.';
		}
	}
	if ($sError == '') {
		list($sCorreoMensajes, $sErrorN, $sDebugM) = AUREA_CorreoNotifica($idSolicitante, $objDB, $bDebug);
		if ($sCorreoMensajes == '') {
			$sError = 'El usuario no registra correo de notificaciones.';
		}
	}
	if ($sError == '') {
		$sCorreoEscuela = '';
		//simulamos un correo de notificaciones solo para que sume el uso.
		$sCuerpo = $sCuerpo . 'Por favor no responder este mensaje, esta es una notificaci&oacute;n del Sistema de Atenci&oacute;n Integral - SII 4.0';
		//.AUREA_NotificaPieDePagina()
		$sCorreoCopia = '';
		if ($bNotificaFuncionario) {
			$sSQL = 'SELECT unad11correofuncionario FROM unad11terceros WHERE unad11id=' . $_SESSION['unad_id_tercero'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sCorreoCopia = $fila['unad11correofuncionario'];
			}
		}
		$sCuerpo = AUREA_HTML_EncabezadoCorreo($sTituloMensaje) . $sCuerpo . AUREA_HTML_PieCorreo();
		$sMes = date('Ym');
		$sTabla = 'aure01login' . $sMes;
		list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
		$objMail = new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto = utf8_encode($sTituloMensaje);
		$sMensaje = 'Se notifica al correo ' . $sCorreoMensajes;
		$objMail->addCorreo($sCorreoMensajes, $sCorreoMensajes);
		if ($sCorreoCopia != '') {
			$objMail->addCorreo($sCorreoCopia, $sCorreoCopia, 'O');
			$sMensaje = $sMensaje . ' con copia a ' . $sCorreoCopia;
		}
		if ($sError == '') {
			$objMail->sCuerpo = $sCuerpo;
			$sError = $objMail->Enviar($bDebug);
			if ($sError != '') {
				$sMensaje = '';
			}
		}
	}
	return array($sError, $sDebug, $sMensaje);
}
//
function f3047_CambiaEstado($iAgno, $saiu47id, $idOrigen, $idDestino, $saiu49detalle, $saiu49usuario, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla47 = 'saiu47tramites_' . $iAgno . '';
	$sTabla49 = 'saiu49cambioesttra_' . $iAgno;
	if (!$objDB->bexistetabla($sTabla47)) {
		$sError = 'No ha sido posible acceder al contenedor de datos';
	} else {
	}
	$bNotificar = false;
	$bNotificaFuncionario = false;
	$sMensaje = '';
	if ($sError == '') {
		$sSQL = 'SELECT saiu47tipotramite, saiu47idsolicitante, saiu47estado, saiu47idresponsable, saiu47idgrupotrabajo 
		FROM ' . $sTabla47 . ' WHERE saiu47id=' . $saiu47id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saiu47tipotramite = $fila['saiu47tipotramite'];
			$saiu49idresponsable = $fila['saiu47idresponsable'];
			$saiu47idsolicitante = $fila['saiu47idsolicitante'];
			if ($saiu49idresponsable == 0) {
				$saiu49idresponsable = $fila['saiu47idsolicitante'];
			}
			if ($fila['saiu47estado'] != $idOrigen) {
				$sError = 'El estado de origen no coincide.';
			}
		} else {
			$sError = 'No se ha encontrado el registro solicitado [Ref ' . $saiu47id . ' A&ntilde;o ' . $iAgno . ']';
		}
	}
	if ($sError == '') {
		$sCampos3049 = 'saiu49idtramite, saiu49consec, saiu49id, saiu49idresponsable, saiu49idestadorigen, 
		saiu49idestadofin, saiu49detalle, saiu49usuario, saiu49fecha, saiu49hora, 
		saiu49minuto, saiu49correterminos, saiu49tiempousado, saiu49tiempocalusado';
		$saiu49fecha = fecha_DiaMod();
		$saiu49hora = fecha_hora();
		$saiu49minuto = fecha_minuto();
		$saiu49correterminos = 0;
		$saiu49tiempousado = 0;
		$saiu49tiempocalusado = 0;
		$saiu49consec = tabla_consecutivo($sTabla49, 'saiu49consec', 'saiu49idtramite=' . $saiu47id . '', $objDB);
		$saiu49id = tabla_consecutivo($sTabla49, 'saiu49id', '', $objDB);
		$sValores3049 = '' . $saiu47id . ', ' . $saiu49consec . ', ' . $saiu49id . ', ' . $saiu49idresponsable . ', ' . $idOrigen . ', 
		' . $idDestino . ', "' . $saiu49detalle . '", "' . $saiu49usuario . '", "' . $saiu49fecha . '", ' . $saiu49hora . ', 
		' . $saiu49minuto . ', ' . $saiu49correterminos . ', ' . $saiu49tiempousado . ', ' . $saiu49tiempocalusado . '';
		$sSQL = 'INSERT INTO ' . $sTabla49 . ' (' . $sCampos3049 . ') VALUES (' . $sValores3049 . ');';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3049 ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'No fue posible registrar el cambio de estado. <br>' . $sSQL;
		} else {
			$sInfoComplemento = '';
			switch ($saiu47tipotramite) {
				case 1: //Solicitudes de devolución
					switch ($idDestino) {
						case 1: //Radicado
						case 5: //Devuelto
							$bNotificar = true;
							if ($saiu47idsolicitante != $_SESSION['unad_id_tercero']) {
								$bNotificaFuncionario = true;
							}
							break;
						case 6: //En estudio
							break;
						case 7: //Procedente  (debe tener resolucion.)
							$sInfoComplemento = ', saiu47idaprueba=' . $_SESSION['unad_id_tercero'] . ', saiu47fechaaprueba=' . fecha_DiaMod() . ', saiu47horaaprueba=' . fecha_hora() . ', saiu47minutoaprueba=' . fecha_minuto() . '';
							break;
						case 9: //No procedente
						case 99: //ANULADO
							if (true) {
								$bNotificar = true;
								if ($saiu47idsolicitante != $_SESSION['unad_id_tercero']) {
									$bNotificaFuncionario = true;
								}
							}
							break;
					}
					break;
			}
			$sSQL = 'UPDATE ' . $sTabla47 . ' SET saiu47estado=' . $idDestino . $sInfoComplemento . ' WHERE saiu47id=' . $saiu47id . '';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	if ($bNotificar) {
		list($sError, $sDebug, $sMensaje) = f3047_NotificarEvento($iAgno, $saiu47id, $objDB, $bDebug, $bNotificaFuncionario);
	}
	return array($sError, $sDebug, $sMensaje);
}
//El filtro de motivo
function f3047_HTMLComboV2_bmotivo($objDB, $objCombos, $valor, $vrsaiu47tipotramite)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('bmotivo', $valor, true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarf3047()';
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vrsaiu47tipotramite != 0) {
		$sSQL = 'SELECT saiu50id AS id, saiu50nombre AS nombre FROM saiu50motivotramite WHERE saiu50idtipotram=' . $vrsaiu47tipotramite . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3047_Combobmotivo($aParametros)
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
	$html_bmotivo = f3047_HTMLComboV2_bmotivo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bmotivo', 'innerHTML', $html_bmotivo);
	$objResponse->call('paginarf3047()');
	//$objResponse->call('$("#bmotivo").chosen()');
	return $objResponse;
}
