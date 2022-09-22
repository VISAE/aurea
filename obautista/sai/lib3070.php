<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.4 lunes, 19 de septiembre de 2022
--- 3070 saiu70responsabletrami
*/
/** Archivo lib3070.php.
 * Libreria 3070 saiu70responsabletrami.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date lunes, 19 de septiembre de 2022
 */
function f3070_HTMLComboV2_saiu70idtipotramite($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu70idtipotramite', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'carga_combo_saiu70numpaso()';
	$sSQL = 'SELECT saiu46id AS id, saiu46nombre AS nombre FROM saiu46tipotramite';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3070_HTMLComboV2_saiu70numpaso($objDB, $objCombos, $valor, $vrsaiu70idtipotramite)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$iPasos = 1;
	$objCombos->nuevo('saiu70numpaso', $valor, true, '-');
	//$objCombos->iAncho=450;
	// $objCombos->sAccion = 'RevisaLlave();';
	switch($vrsaiu70idtipotramite){
		case 1: // Solicitud de devolucion.
			$iPasos = 3;
			break;
	}
	for ($k = 1; $k <= $iPasos; $k++) {
		$objCombos->addItem($k, $k);
	}
	$res = $objCombos->html('', $objDB);
	return $res;
}
function f3070_HTMLComboV2_saiu70idzona($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu70idzona', $valor, true, '{' . $ETI['msg_todas'] . '}', 0);
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'carga_combo_saiu70idcentro()';
	$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes = "S"';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3070_HTMLComboV2_saiu70idcentro($objDB, $objCombos, $valor, $vrsaiu70idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu70idcentro', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho=450;
	// $objCombos->sAccion = 'RevisaLlave();';
	$sSQL = '';
	if ((int)$vrsaiu70idzona != 0) {
		$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona=' . $vrsaiu70idzona . ' AND unad24activa = "S" ' . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3070_HTMLComboV2_saiu70idescuela($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu70idescuela', $valor, true, '{' . $ETI['msg_todas'] . '}', 0);
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'carga_combo_saiu70idprograma()';
	$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes = "S"';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3070_HTMLComboV2_saiu70idprograma($objDB, $objCombos, $valor, $vrsaiu70idescuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu70idprograma', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho=450;
	// $objCombos->sAccion = 'RevisaLlave();';
	$sSQL = '';
	if ((int)$vrsaiu70idescuela != 0) {
		$sSQL = 'SELECT core09id AS id, CONCAT(core09nombre, " [", core09codigo, "]", CASE core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END) AS nombre 
		FROM core09programa 
		WHERE core09idescuela=' . $vrsaiu70idescuela . ' AND  core09id>0
		ORDER BY core09nombre' . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3070_HTMLComboV2_saiu70idgrupotrabajo($objDB, $objCombos, $valor, $vrsaiu70idunidad)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu70idgrupotrabajo', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'carga_combo_saiu70idresponsable()';
	$sSQL = '';
	if ((int)$vrsaiu70idunidad != 0) {
		$sSQL = 'SELECT bita27id AS id, bita27nombre AS nombre FROM bita27equipotrabajo WHERE bita27idunidadfunc=' . $vrsaiu70idunidad . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3070_HTMLComboV2_saiu70idresponsable($objDB, $objCombos, $valor, $vrsaiu70idgrupotrabajo)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu70idresponsable', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vrsaiu70idgrupotrabajo != 0) {
		$sSQL = 'SELECT TB.bita28id AS id, T2.unad11razonsocial AS nombre
		FROM bita28eqipoparte AS TB, unad11terceros AS T2 
		WHERE  TB.bita28idequipotrab=' . $vrsaiu70idgrupotrabajo . ' AND TB.bita28idtercero=T2.unad11id AND TB.bita28activo="S"
		ORDER BY T2.unad11razonsocial';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3070_Combosaiu70numpaso($aParametros)
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
	$html_saiu70numpaso = f3070_HTMLComboV2_saiu70numpaso($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu70numpaso', 'innerHTML', $html_saiu70numpaso);
	//$objResponse->call('$("#saiu70numpaso").chosen()');
	return $objResponse;
}
function f3070_Combosaiu70idcentro($aParametros)
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
	$html_saiu70idcentro = f3070_HTMLComboV2_saiu70idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu70idcentro', 'innerHTML', $html_saiu70idcentro);
	//$objResponse->call('$("#saiu70idcentro").chosen()');
	return $objResponse;
}
function f3070_Combosaiu70idprograma($aParametros)
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
	$html_saiu70idprograma = f3070_HTMLComboV2_saiu70idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu70idprograma', 'innerHTML', $html_saiu70idprograma);
	$objResponse->call('$("#saiu70idprograma").chosen({width:"450px"})');
	return $objResponse;
}
function f3070_Combosaiu70idgrupotrabajo($aParametros)
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
	$html_saiu70idgrupotrabajo = f3070_HTMLComboV2_saiu70idgrupotrabajo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu70idgrupotrabajo', 'innerHTML', $html_saiu70idgrupotrabajo);
	$objResponse->call('$("#saiu70idgrupotrabajo").chosen({width:"450px"})');
	return $objResponse;
}
function f3070_Combosaiu70idresponsable($aParametros)
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
	$html_saiu70idresponsable = f3070_HTMLComboV2_saiu70idresponsable($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu70idresponsable', 'innerHTML', $html_saiu70idresponsable);
	//$objResponse->call('$("#saiu70idresponsable").chosen()');
	return $objResponse;
}
function f3070_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$saiu70idtipotramite = numeros_validar($datos[1]);
	if ($saiu70idtipotramite == '') {
		$bHayLlave = false;
	}
	$saiu70numpaso = numeros_validar($datos[2]);
	if ($saiu70numpaso == '') {
		$bHayLlave = false;
	}
	$saiu70idzona = numeros_validar($datos[3]);
	if ($saiu70idzona == '') {
		$bHayLlave = false;
	}
	$saiu70idcentro = numeros_validar($datos[4]);
	if ($saiu70idcentro == '') {
		$bHayLlave = false;
	}
	$saiu70idescuela = numeros_validar($datos[5]);
	if ($saiu70idescuela == '') {
		$bHayLlave = false;
	}
	$saiu70idprograma = numeros_validar($datos[6]);
	if ($saiu70idprograma == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM saiu70responsabletrami WHERE saiu70idtipotramite=' . $saiu70idtipotramite . ' AND saiu70numpaso=' . $saiu70numpaso . ' AND saiu70idzona=' . $saiu70idzona . ' AND saiu70idcentro=' . $saiu70idcentro . ' AND saiu70idescuela=' . $saiu70idescuela . ' AND saiu70idprograma=' . $saiu70idprograma . '';
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
function f3070_Busquedas($aParametros)
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
	$mensajes_3070 = 'lg/lg_3070_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3070)) {
		$mensajes_3070 = 'lg/lg_3070_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3070;
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
	}
	$sTitulo = '<h2>' . $ETI['titulo_3070'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f3070_HtmlBusqueda($aParametros)
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
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f3070_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_3000 = 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = 'lg/lg_3000_es.php';
	}
	require $mensajes_3000;
	*/
	$mensajes_3070 = 'lg/lg_3070_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3070)) {
		$mensajes_3070 = 'lg/lg_3070_es.php';
	}
	require $mensajes_3070;
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
	$sDebug = '';
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		$btipotramite = numeros_validar($aParametros[103]);
		$bnumpaso = numeros_validar($aParametros[104]);
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
	$sBotones = '<input id="paginaf3070" name="paginaf3070" type="hidden" value="' . $pagina . '"/>
	<input id="lppf3070" name="lppf3070" type="hidden" value="' . $lineastabla . '"/>';
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
	/*
	$aEstado=array();
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['id']] = cadena_notildes($fila['nombre']);
	}
	*/
	if (true) {
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
		$sSQLadd = '';
		$sSQLadd1 = '';
		if ($btipotramite != '') {
			$sSQLadd = $sSQLadd . ' AND TB.saiu70idtipotramite = ' . $btipotramite . '';
		}
		if ($bnumpaso != '') {
			$sSQLadd = $sSQLadd . ' AND TB.saiu70numpaso = ' . $bnumpaso . '';
		}
		/*
		if ($aParametros[104] != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.campo2 LIKE "%' . $aParametros[104] . '%" AND ';
		}
		if ($bNombre != '') {
			$sBase = strtoupper($bNombre);
			$aNoms=explode(' ', $sBase);
			for ($k = 1; $k <= count($aNoms); $k++) {
				$sCadena = $aNoms[$k - 1];
					if ($sCadena != '') {
					$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
					//$sSQLadd1 = $sSQLadd1 . 'T1.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
				}
			}
		}
		*/
	}
	//-- Area para saltar comparaciones con los archivos tipo e
	//-- Fin del area no comparada
	$sTitulos = 'Tipotramite, Numpaso, Zona, Centro, Escuela, Programa, Id, Activo, Unidad, Grupotrabajo, Responsable';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	if ($bGigante) {
		$sSQL = 'SELECT COUNT(1) AS Total 
		FROM saiu70responsabletrami AS TB, saiu46tipotramite AS T1, unae26unidadesfun AS T9, bita27equipotrabajo AS T10, unad11terceros AS T11 
		WHERE ' . $sSQLadd1 . ' TB.saiu70idtipotramite=T1.saiu46id AND TB.saiu70idunidad=T9.unae26id AND TB.saiu70idgrupotrabajo=T10.bita27id AND TB.saiu70idresponsable=T11.unad11id ' . $sSQLadd . '';
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
	$sSQL = 'SELECT T1.saiu46nombre, TB.saiu70numpaso, TB.saiu70id, TB.saiu70activo, T9.unae26nombre, T10.bita27nombre, T11.unad11razonsocial, TB.saiu70idtipotramite, TB.saiu70idzona, TB.saiu70idcentro, TB.saiu70idescuela, TB.saiu70idprograma, TB.saiu70idunidad, TB.saiu70idgrupotrabajo, TB.saiu70idresponsable 
	FROM saiu70responsabletrami AS TB, saiu46tipotramite AS T1, unae26unidadesfun AS T9, bita27equipotrabajo AS T10, unad11terceros AS T11 
	WHERE ' . $sSQLadd1 . ' TB.saiu70idtipotramite=T1.saiu46id AND TB.saiu70idunidad=T9.unae26id AND TB.saiu70idgrupotrabajo=T10.bita27id AND TB.saiu70idresponsable=T11.unad11id ' . $sSQLadd . '
	ORDER BY TB.saiu70idtipotramite, TB.saiu70numpaso, TB.saiu70idzona, TB.saiu70idcentro, TB.saiu70idescuela, TB.saiu70idprograma';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3070" name="consulta_3070" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3070" name="titulos_3070" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3070: ' . $sSQL . $sLimite . '<br>';
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
	<td><b>' . $ETI['saiu70idzona'] . '</b></td>
	<td><b>' . $ETI['saiu70idcentro'] . '</b></td>
	<td><b>' . $ETI['saiu70idescuela'] . '</b></td>
	<td><b>' . $ETI['saiu70idprograma'] . '</b></td>
	<td><b>' . $ETI['saiu70idunidad'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3070', $registros, $lineastabla, $pagina, 'paginarf3070()') . '
	' . html_lpp('lppf3070', $lineastabla, 'paginarf3070()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	$asaiu70idzona = array();
	$asaiu70idcentro = array();
	$asaiu70idescuela = array();
	$asaiu70idprograma = array();
	$idtipotramite = -1;
	$numpaso = -1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idtipotramite != $filadet['saiu70idtipotramite'] || $numpaso != $filadet['saiu70numpaso']) {
			$idtipotramite = $filadet['saiu70idtipotramite'];
			$numpaso = $filadet['saiu70numpaso'];
			$res = $res . '<tr class="fondoazul">
			<td colspan="6">' . $ETI['saiu70idtipotramite'] . ': <b>' . cadena_notildes($filadet['saiu46nombre']) . '</b> - ' . $ETI['saiu70numpaso'] . ': <b>' . $filadet['saiu70numpaso'] . '</b></td>
			</tr>';
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';		
		if (false) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_saiu70activo = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['saiu70activo'] == 'S') {
			$et_saiu70activo = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		if ($filadet['saiu70idzona'] == 0) {
			$et_saiu70idzona = $ETI['msg_todas'];
		} else {
			$i_saiu70idzona=$filadet['saiu70idzona'];
			if (isset($asaiu70idzona[$i_saiu70idzona])==0){
				$sSQL='SELECT unad23sigla FROM unad23zona WHERE unad23id='.$i_saiu70idzona.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$asaiu70idzona[$i_saiu70idzona]=$filae['unad23sigla'];
					}else{
					$asaiu70idzona[$i_saiu70idzona]='';
					}
				}
			$et_saiu70idzona = cadena_notildes($asaiu70idzona[$i_saiu70idzona]);
		}
		if ($filadet['saiu70idcentro'] == 0) {
			$et_saiu70idcentro = $ETI['msg_todos'];
		} else {
			$i_saiu70idcentro=$filadet['saiu70idcentro'];
			if (isset($asaiu70idcentro[$i_saiu70idcentro])==0){
				$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_saiu70idcentro.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$asaiu70idcentro[$i_saiu70idcentro]=$filae['unad24nombre'];
					}else{
					$asaiu70idcentro[$i_saiu70idcentro]='';
					}
				}
			$et_saiu70idcentro = cadena_notildes($asaiu70idcentro[$i_saiu70idcentro]);
		}
		if ($filadet['saiu70idescuela'] == 0) {
			$et_saiu70idescuela = $ETI['msg_todas'];
		} else {
			$i_saiu70idescuela=$filadet['saiu70idescuela'];
			if (isset($asaiu70idescuela[$i_saiu70idescuela])==0){
				$sSQL='SELECT core12sigla FROM core12escuela WHERE core12id='.$i_saiu70idescuela.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$asaiu70idescuela[$i_saiu70idescuela]=$filae['core12sigla'];
					}else{
					$asaiu70idescuela[$i_saiu70idescuela]='';
					}
				}
			$et_saiu70idescuela = cadena_notildes($asaiu70idescuela[$i_saiu70idescuela]);
		}
		if ($filadet['saiu70idprograma'] == 0) {
			$et_saiu70idprograma = $ETI['msg_todos'];
		} else {
			$i_saiu70idprograma=$filadet['saiu70idprograma'];
			if (isset($asaiu70idprograma[$i_saiu70idprograma])==0){
				$sSQL='SELECT CONCAT(core09nombre, " [", core09codigo, "]", CASE core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END) AS nombre 
				FROM core09programa 
				WHERE core09id='.$i_saiu70idprograma.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$asaiu70idprograma[$i_saiu70idprograma]=$filae['nombre'];
					}else{
					$asaiu70idprograma[$i_saiu70idprograma]='['.$i_saiu70idprograma.']';
					}
				}
			$et_saiu70idprograma = cadena_notildes($asaiu70idprograma[$i_saiu70idprograma]);
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3070(' . $filadet['saiu70id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $et_saiu70idzona . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu70idcentro . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu70idescuela . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu70idprograma . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unae26nombre']) . $sSufijo . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f3070_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3070_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3070detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3070_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'saiu70idtipotramite=' . $DATA['saiu70idtipotramite'] . ' AND saiu70numpaso=' . $DATA['saiu70numpaso'] . ' AND saiu70idzona=' . $DATA['saiu70idzona'] . ' AND saiu70idcentro=' . $DATA['saiu70idcentro'] . ' AND saiu70idescuela=' . $DATA['saiu70idescuela'] . ' AND saiu70idprograma=' . $DATA['saiu70idprograma'] . '';
	} else {
		$sSQLcondi = 'saiu70id=' . $DATA['saiu70id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu70responsabletrami WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['saiu70idtipotramite'] = $fila['saiu70idtipotramite'];
		$DATA['saiu70numpaso'] = $fila['saiu70numpaso'];
		$DATA['saiu70idzona'] = $fila['saiu70idzona'];
		$DATA['saiu70idcentro'] = $fila['saiu70idcentro'];
		$DATA['saiu70idescuela'] = $fila['saiu70idescuela'];
		$DATA['saiu70idprograma'] = $fila['saiu70idprograma'];
		$DATA['saiu70id'] = $fila['saiu70id'];
		$DATA['saiu70activo'] = $fila['saiu70activo'];
		$DATA['saiu70idunidad'] = $fila['saiu70idunidad'];
		$DATA['saiu70idgrupotrabajo'] = $fila['saiu70idgrupotrabajo'];
		$DATA['saiu70idresponsable'] = $fila['saiu70idresponsable'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta3070'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3070_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3070;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3070 = 'lg/lg_3070_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3070)) {
		$mensajes_3070 = 'lg/lg_3070_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3070;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu70idtipotramite']) == 0) {
		$DATA['saiu70idtipotramite'] = '';
	}
	if (isset($DATA['saiu70numpaso']) == 0) {
		$DATA['saiu70numpaso'] = '';
	}
	if (isset($DATA['saiu70idzona']) == 0) {
		$DATA['saiu70idzona'] = '';
	}
	if (isset($DATA['saiu70idcentro']) == 0) {
		$DATA['saiu70idcentro'] = '';
	}
	if (isset($DATA['saiu70idescuela']) == 0) {
		$DATA['saiu70idescuela'] = '';
	}
	if (isset($DATA['saiu70idprograma']) == 0) {
		$DATA['saiu70idprograma'] = '';
	}
	if (isset($DATA['saiu70id']) == 0) {
		$DATA['saiu70id'] = '';
	}
	if (isset($DATA['saiu70activo']) == 0) {
		$DATA['saiu70activo'] = '';
	}
	if (isset($DATA['saiu70idunidad']) == 0) {
		$DATA['saiu70idunidad'] = '';
	}
	if (isset($DATA['saiu70idgrupotrabajo']) == 0) {
		$DATA['saiu70idgrupotrabajo'] = '';
	}
	if (isset($DATA['saiu70idresponsable']) == 0) {
		$DATA['saiu70idresponsable'] = '';
	}
	*/
	$DATA['saiu70idtipotramite'] = numeros_validar($DATA['saiu70idtipotramite']);
	$DATA['saiu70numpaso'] = numeros_validar($DATA['saiu70numpaso']);
	$DATA['saiu70idzona'] = numeros_validar($DATA['saiu70idzona']);
	$DATA['saiu70idcentro'] = numeros_validar($DATA['saiu70idcentro']);
	$DATA['saiu70idescuela'] = numeros_validar($DATA['saiu70idescuela']);
	$DATA['saiu70idprograma'] = numeros_validar($DATA['saiu70idprograma']);
	$DATA['saiu70activo'] = numeros_validar($DATA['saiu70activo']);
	$DATA['saiu70idunidad'] = numeros_validar($DATA['saiu70idunidad']);
	$DATA['saiu70idgrupotrabajo'] = numeros_validar($DATA['saiu70idgrupotrabajo']);
	$DATA['saiu70idresponsable'] = numeros_validar($DATA['saiu70idresponsable']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['saiu70activo'] == '') {
		$DATA['saiu70activo'] = 0;
	}
	if ($DATA['saiu70idunidad'] == '') {
		$DATA['saiu70idunidad'] = 0;
	}
	if ($DATA['saiu70idgrupotrabajo'] == '') {
		$DATA['saiu70idgrupotrabajo'] = 0;
	}
	if ($DATA['saiu70idresponsable'] == '') {
		$DATA['saiu70idresponsable'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		if ($DATA['saiu70idresponsable'] == '') {
			$sError = $ERR['saiu70idresponsable'] . $sSepara . $sError;
		}
		if ($DATA['saiu70idgrupotrabajo'] == '') {
			$sError = $ERR['saiu70idgrupotrabajo'] . $sSepara . $sError;
		}
		if ($DATA['saiu70idunidad'] == '') {
			$sError = $ERR['saiu70idunidad'] . $sSepara . $sError;
		}
		if ($DATA['saiu70activo'] == '') {
			$sError = $ERR['saiu70activo'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu70idprograma'] == '') {
		$sError = $ERR['saiu70idprograma'];
	}
	if ($DATA['saiu70idescuela'] == '') {
		$sError = $ERR['saiu70idescuela'];
	}
	if ($DATA['saiu70idcentro'] == '') {
		$sError = $ERR['saiu70idcentro'];
	}
	if ($DATA['saiu70idzona'] == '') {
		$sError = $ERR['saiu70idzona'];
	}
	if ($DATA['saiu70numpaso'] == '') {
		$sError = $ERR['saiu70numpaso'];
	}
	if ($DATA['saiu70idtipotramite'] == '') {
		$sError = $ERR['saiu70idtipotramite'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT 1 FROM saiu70responsabletrami WHERE saiu70idtipotramite=' . $DATA['saiu70idtipotramite'] . ' AND saiu70numpaso=' . $DATA['saiu70numpaso'] . ' AND saiu70idzona=' . $DATA['saiu70idzona'] . ' AND saiu70idcentro=' . $DATA['saiu70idcentro'] . ' AND saiu70idescuela=' . $DATA['saiu70idescuela'] . ' AND saiu70idprograma=' . $DATA['saiu70idprograma'] . '';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$sError = $ERR['existe'];
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['2'];
				}
			}
		} else {
			list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu70id'] = tabla_consecutivo('saiu70responsabletrami', 'saiu70id', '', $objDB);
			if ($DATA['saiu70id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos3070 = 'saiu70idtipotramite, saiu70numpaso, saiu70idzona, saiu70idcentro, saiu70idescuela, 
			saiu70idprograma, saiu70id, saiu70activo, saiu70idunidad, saiu70idgrupotrabajo, 
			saiu70idresponsable';
			$sValores3070 = '' . $DATA['saiu70idtipotramite'] . ', ' . $DATA['saiu70numpaso'] . ', ' . $DATA['saiu70idzona'] . ', ' . $DATA['saiu70idcentro'] . ', ' . $DATA['saiu70idescuela'] . ', 
			' . $DATA['saiu70idprograma'] . ', ' . $DATA['saiu70id'] . ', ' . $DATA['saiu70activo'] . ', ' . $DATA['saiu70idunidad'] . ', ' . $DATA['saiu70idgrupotrabajo'] . ', 
			' . $DATA['saiu70idresponsable'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO saiu70responsabletrami (' . $sCampos3070 . ') VALUES (' . utf8_encode($sValores3070) . ');';
				$sdetalle = $sCampos3070 . '[' . utf8_encode($sValores3070) . ']';
			} else {
				$sSQL = 'INSERT INTO saiu70responsabletrami (' . $sCampos3070 . ') VALUES (' . $sValores3070 . ');';
				$sdetalle = $sCampos3070 . '[' . $sValores3070 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'saiu70idtipotramite';
			$scampo[2] = 'saiu70numpaso';
			$scampo[3] = 'saiu70idzona';
			$scampo[4] = 'saiu70idcentro';
			$scampo[5] = 'saiu70idescuela';
			$scampo[6] = 'saiu70idprograma';
			$scampo[7] = 'saiu70activo';
			$scampo[8] = 'saiu70idunidad';
			$scampo[9] = 'saiu70idgrupotrabajo';
			$scampo[10] = 'saiu70idresponsable';
			$sdato[1] = $DATA['saiu70idtipotramite'];
			$sdato[2] = $DATA['saiu70numpaso'];
			$sdato[3] = $DATA['saiu70idzona'];
			$sdato[4] = $DATA['saiu70idcentro'];
			$sdato[5] = $DATA['saiu70idescuela'];
			$sdato[6] = $DATA['saiu70idprograma'];
			$sdato[7] = $DATA['saiu70activo'];
			$sdato[8] = $DATA['saiu70idunidad'];
			$sdato[9] = $DATA['saiu70idgrupotrabajo'];
			$sdato[10] = $DATA['saiu70idresponsable'];
			$iNumCamposMod = 10;
			$sWhere = 'saiu70id=' . $DATA['saiu70id'] . '';
			$sSQL = 'SELECT * FROM saiu70responsabletrami WHERE ' . $sWhere;
			$sdatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $iNumCamposMod; $k++) {
						if (isset($filabase[$scampo[$k]]) == 0) {
							$sDebug = $sDebug . fecha_microtiempo() . ' FALLA CODIGO: Falta el campo ' . $k . ' ' . $scampo[$k] . '<br>';
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
					$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE saiu70responsabletrami SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE saiu70responsabletrami SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3070 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3070] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['saiu70id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu70id'], $sdetalle, $objDB);
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
	/*
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' InfoDepura<br>';
	}
	*/
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3070_db_Eliminar($saiu70id, $objDB, $bDebug = false)
{
	$iCodModulo = 3070;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3070 = 'lg/lg_3070_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3070)) {
		$mensajes_3070 = 'lg/lg_3070_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3070;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu70id = numeros_validar($saiu70id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM saiu70responsabletrami WHERE saiu70id=' . $saiu70id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $saiu70id . '}';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3070';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['saiu70id'] . ' LIMIT 0, 1';
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
		$sWhere = 'saiu70id=' . $saiu70id . '';
		//$sWhere = 'saiu70idprograma=' . $filabase['saiu70idprograma'] . ' AND saiu70idescuela=' . $filabase['saiu70idescuela'] . ' AND saiu70idcentro=' . $filabase['saiu70idcentro'] . ' AND saiu70idzona=' . $filabase['saiu70idzona'] . ' AND saiu70numpaso=' . $filabase['saiu70numpaso'] . ' AND saiu70idtipotramite=' . $filabase['saiu70idtipotramite'] . '';
		$sSQL = 'DELETE FROM saiu70responsabletrami WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu70id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f3070_TituloBusqueda()
{
	require './app.php';
	$mensajes_3070 = 'lg/lg_3070_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3070)) {
		$mensajes_3070 = 'lg/lg_3070_es.php';
	}
	require $mensajes_3070;
	return $ETI['titulo_busca_3070'];
}
function f3070_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3070 = 'lg/lg_3070_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3070)) {
		$mensajes_3070 = 'lg/lg_3070_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3070;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b3070nombre" name="b3070nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f3070_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'let sCampo = window.document.frmedita.scampobusca.value;
	let params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b3070nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f3070_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3070 = 'lg/lg_3070_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3070)) {
		$mensajes_3070 = 'lg/lg_3070_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3070;
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
	//$bNombre=trim($aParametros[103]);
	//$bListar = numeros_validar($aParametros[104]);
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
	$sBotones = '<input id="paginaf3070" name="paginaf3070" type="hidden" value="' . $pagina . '" />
	<input id="lppf3070" name="lppf3070" type="hidden" value="' . $lineastabla . '" />';
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
	$sTitulos = 'Tipotramite, Numpaso, Zona, Centro, Escuela, Programa, Id, Activo, Unidad, Grupotrabajo, Responsable';
	$sSQL = 'SELECT T1.saiu46nombre, TB.saiu70numpaso, T3.unad23nombre, T4.unad24nombre, T5.core12nombre, T6.core09nombre, TB.saiu70id, TB.saiu70activo, T9.unae26nombre, T10.bita27nombre, T11.unad11razonsocial, TB.saiu70idtipotramite, TB.saiu70idzona, TB.saiu70idcentro, TB.saiu70idescuela, TB.saiu70idprograma, TB.saiu70idunidad, TB.saiu70idgrupotrabajo, TB.saiu70idresponsable 
	FROM saiu70responsabletrami AS TB, saiu46tipotramite AS T1, unad23zona AS T3, unad24sede AS T4, core12escuela AS T5, core09programa AS T6, unae26unidadesfun AS T9, bita27equipotrabajo AS T10, unad11terceros AS T11 
	WHERE ' . $sSQLadd1 . ' TB.saiu70idtipotramite=T1.saiu46id AND TB.saiu70idzona=T3.unad23id AND TB.saiu70idcentro=T4.unad24id AND TB.saiu70idescuela=T5.core12id AND TB.saiu70idprograma=T6.core09id AND TB.saiu70idunidad=T9.unae26id AND TB.saiu70idgrupotrabajo=T10.bita27id AND TB.saiu70idresponsable=T11.unad11id ' . $sSQLadd . '
	ORDER BY TB.saiu70idtipotramite, TB.saiu70numpaso, TB.saiu70idzona, TB.saiu70idcentro, TB.saiu70idescuela, TB.saiu70idprograma';
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
	<td><b>' . $ETI['saiu70idtipotramite'] . '</b></td>
	<td><b>' . $ETI['saiu70numpaso'] . '</b></td>
	<td><b>' . $ETI['saiu70idzona'] . '</b></td>
	<td><b>' . $ETI['saiu70idcentro'] . '</b></td>
	<td><b>' . $ETI['saiu70idescuela'] . '</b></td>
	<td><b>' . $ETI['saiu70idprograma'] . '</b></td>
	<td><b>' . $ETI['saiu70activo'] . '</b></td>
	<td><b>' . $ETI['saiu70idunidad'] . '</b></td>
	<td><b>' . $ETI['saiu70idgrupotrabajo'] . '</b></td>
	<td><b>' . $ETI['saiu70idresponsable'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['saiu70id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_saiu70activo = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['saiu70activo'] == 'S') {
			$et_saiu70activo = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu46nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu70numpaso'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu70activo'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unae26nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['bita27nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad11razonsocial']) . $sSufijo . '</td>
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
?>