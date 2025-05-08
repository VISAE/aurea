<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.12c viernes, 11 de abril de 2025
--- 3073 saiu73solusuario
*/
/** Archivo lib3073.php.
 * Libreria 3073 saiu73solusuario.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date viernes, 11 de abril de 2025
 */
function f3073_HTMLComboV2_saiu73tiporadicado($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73tiporadicado', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.saiu16id AS id, TB.saiu16nombre AS nombre 
	FROM saiu16tiporadicado AS TB
	WHERE TB.saiu16id>0
	ORDER BY TB.saiu16nombre';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3073_HTMLComboV2_saiu73idcentro($objDB, $objCombos, $valor, $vrsaiu73idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73idcentro', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrsaiu73idzona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrsaiu73idzona . ' 
		ORDER BY TB.unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3073_HTMLComboV2_saiu73coddepto($objDB, $objCombos, $valor, $vrsaiu73codpais)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73coddepto', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'carga_combo_saiu73codciudad()';
	$sSQL = '';
	if ((int)$vrsaiu73codpais != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad19codigo AS id, TB.unad19nombre AS nombre 
		FROM unad19depto AS TB
		WHERE TB.unad19codpais=' . $vrsaiu73codpais . ' 
		ORDER BY TB.unad19nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3073_HTMLComboV2_saiu73codciudad($objDB, $objCombos, $valor, $vrsaiu73coddepto)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73codciudad', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrsaiu73coddepto != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad20codigo AS id, TB.unad20nombre AS nombre 
		FROM unad20ciudad AS TB
		WHERE TB.unad20coddepto=' . $vrsaiu73coddepto . ' 
		ORDER BY TB.unad20nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3073_HTMLComboV2_saiu73idprograma($objDB, $objCombos, $valor, $vrsaiu73idescuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73idprograma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrsaiu73idescuela != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core09id AS id, TB.core09nombre AS nombre 
		FROM core09programa AS TB
		WHERE TB.core09idescuela=' . $vrsaiu73idescuela . ' 
		ORDER BY TB.core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3073_Combosaiu73idcentro($aParametros)
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
	$html_saiu73idcentro = f3073_HTMLComboV2_saiu73idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu73idcentro', 'innerHTML', $html_saiu73idcentro);
	//$objResponse->call('$("#saiu73idcentro").chosen()');
	return $objResponse;
}
function f3073_Combosaiu73coddepto($aParametros)
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
	$html_saiu73coddepto = f3073_HTMLComboV2_saiu73coddepto($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu73coddepto', 'innerHTML', $html_saiu73coddepto);
	//$objResponse->call('$("#saiu73coddepto").chosen()');
	return $objResponse;
}
function f3073_Combosaiu73codciudad($aParametros)
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
	$html_saiu73codciudad = f3073_HTMLComboV2_saiu73codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu73codciudad', 'innerHTML', $html_saiu73codciudad);
	//$objResponse->call('$("#saiu73codciudad").chosen()');
	return $objResponse;
}
function f3073_Combosaiu73idprograma($aParametros)
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
	$html_saiu73idprograma = f3073_HTMLComboV2_saiu73idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu73idprograma', 'innerHTML', $html_saiu73idprograma);
	//$objResponse->call('$("#saiu73idprograma").chosen()');
	return $objResponse;
}
function elimina_archivo_saiu73idarchivo($idPadre, $bDebug = false)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sError = '';
	$sDebug = '';
	$bPuedeEliminar = true;
	// Definir las condiciones para que se pueda eliminar y el mensaje de error que se debe presentar
	if ($bPuedeEliminar) {
		archivo_eliminar('saiu73solusuario', 'saiu73id', 'saiu73idorigen', 'saiu73idarchivo', $idPadre, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_saiu73idarchivo");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0);");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function elimina_archivo_saiu73idarchivorta($idPadre, $bDebug = false)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sError = '';
	$sDebug = '';
	$bPuedeEliminar = true;
	// Definir las condiciones para que se pueda eliminar y el mensaje de error que se debe presentar
	if ($bPuedeEliminar) {
		archivo_eliminar('saiu73solusuario', 'saiu73id', 'saiu73idorigenrta', 'saiu73idarchivorta', $idPadre, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_saiu73idarchivorta");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0);");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3073_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$saiu73agno = numeros_validar($datos[1]);
	if ($saiu73agno == '') {
		$bHayLlave = false;
	}
	$saiu73mes = numeros_validar($datos[2]);
	if ($saiu73mes == '') {
		$bHayLlave = false;
	}
	$saiu73tiporadicado = numeros_validar($datos[3]);
	if ($saiu73tiporadicado == '') {
		$bHayLlave = false;
	}
	$saiu73consec = numeros_validar($datos[4]);
	if ($saiu73consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM saiu73solusuario WHERE saiu73agno=' . $saiu73agno . ' AND saiu73mes=' . $saiu73mes . ' AND saiu73tiporadicado=' . $saiu73tiporadicado . ' AND saiu73consec=' . $saiu73consec . '';
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
function f3073_Busquedas($aParametros)
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
	$mensajes_3073 = 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3073)) {
		$mensajes_3073 = 'lg/lg_3073_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3073;
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
	$iPiel = iDefinirPiel($APP, 2);
	$sTituloModulo = $ETI['titulo_3073'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'saiu73idsolicitante':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['saiu73idsolicitante_busca']) == 0) {
				$ETI['saiu73idsolicitante_busca'] = 'Busqueda de Solicitante';
			}
			$sTitulo = $ETI['saiu73idsolicitante_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3073);
			break;
		case 'saiu73idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['saiu73idresponsable_busca']) == 0) {
				$ETI['saiu73idresponsable_busca'] = 'Busqueda de Responsable';
			}
			$sTitulo = $ETI['saiu73idresponsable_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3073);
			break;
	}
	$sTitulo = '<h2>' . $sTituloModulo . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f3073_HtmlBusqueda($aParametros)
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
		case 'saiu73idsolicitante':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu73idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f3073_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_3073 = 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3073)) {
		$mensajes_3073 = 'lg/lg_3073_es.php';
	}
	require $mensajes_3073;
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
	$iNumVariables = 102;
	for ($k = 103; $k <= $iNumVariables; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	$idTercero = numeros_validar($aParametros[100]);
	$sDebug = '';
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$pagina = numeros_validar($aParametros[101]);
	$lineastabla = numeros_validar($aParametros[102]);
	//$bNombre = trim($aParametros[103]);
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
	$sBotones = '<input id="paginaf3073" name="paginaf3073" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf3073" name="lppf3073" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	/*
	$aEstado = array();
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['id']] = cadena_notildes($fila['nombre']);
	}
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	/*
	if ($aParametros[104] != '') {
		$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[104] . '%"';
	}
	if ($aParametros[104] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.campo2 LIKE "%' . $aParametros[104] . '%" AND ';
	}
	if ($bNombre != '') {
		$sBase = mb_strtoupper($bNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'TB.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Agno, Mes, Tiporadicado, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Estado, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Pqrs, Detalle, Origen, Archivo, Fechafin, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos, Solucion, Caso, Respuesta, Origenrta, Archivorta, Fecharespcaso, Horarespcaso, Minrespcaso, Unidadcaso, Equipocaso, Supervisorcaso, Responsablecaso, Numref';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.saiu73agno, TB.saiu73mes, T3.saiu16nombre, TB.saiu73consec, TB.saiu73id, TB.saiu73origenagno, TB.saiu73origenmes, TB.saiu73origenid, TB.saiu73dia, TB.saiu73hora, TB.saiu73minuto, T12.saiu11nombre, T13.unad11razonsocial AS C13_nombre, T14.bita07nombre, T15.saiu01titulo, T16.saiu02titulo, T17.saiu03titulo, T18.unad23nombre, T19.unad24nombre, T20.unad18nombre, T21.unad19nombre, T22.unad20nombre, T23.core12nombre, T24.core09nombre, T25.exte02nombre, TB.saiu73idpqrs, TB.saiu73detalle, TB.saiu73idorigen, TB.saiu73idarchivo, TB.saiu73fechafin, TB.saiu73horafin, TB.saiu73minutofin, TB.saiu73paramercadeo, T34.unad11razonsocial AS C34_nombre, TB.saiu73tiemprespdias, TB.saiu73tiempresphoras, TB.saiu73tiemprespminutos, TB.saiu73solucion, TB.saiu73idcaso, TB.saiu73respuesta, TB.saiu73idorigenrta, TB.saiu73idarchivorta, TB.saiu73fecharespcaso, TB.saiu73horarespcaso, TB.saiu73minrespcaso, TB.saiu73idunidadcaso, TB.saiu73idequipocaso, TB.saiu73idsupervisorcaso, TB.saiu73idresponsablecaso, TB.saiu73numref, TB.saiu73tiporadicado, TB.saiu73estado, TB.saiu73idsolicitante, T13.unad11tipodoc AS C13_td, T13.unad11doc AS C13_doc, TB.saiu73tipointeresado, TB.saiu73clasesolicitud, TB.saiu73tiposolicitud, TB.saiu73temasolicitud, TB.saiu73idzona, TB.saiu73idcentro, TB.saiu73codpais, TB.saiu73coddepto, TB.saiu73codciudad, TB.saiu73idescuela, TB.saiu73idprograma, TB.saiu73idperiodo, TB.saiu73idresponsable, T34.unad11tipodoc AS C34_td, T34.unad11doc AS C34_doc';
	$sConsulta = 'FROM saiu73solusuario AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T12, unad11terceros AS T13, bita07tiposolicitante AS T14, saiu01claseser AS T15, saiu02tiposol AS T16, saiu03temasol AS T17, unad23zona AS T18, unad24sede AS T19, unad18pais AS T20, unad19depto AS T21, unad20ciudad AS T22, core12escuela AS T23, core09programa AS T24, exte02per_aca AS T25, unad11terceros AS T34 
	WHERE ' . $sSQLadd1 . ' TB.saiu73id>0 AND TB.saiu73tiporadicado=T3.saiu16id AND TB.saiu73estado=T12.saiu11id AND TB.saiu73idsolicitante=T13.unad11id AND TB.saiu73tipointeresado=T14.bita07id AND TB.saiu73clasesolicitud=T15.saiu01id AND TB.saiu73tiposolicitud=T16.saiu02id AND TB.saiu73temasolicitud=T17.saiu03id AND TB.saiu73idzona=T18.unad23id AND TB.saiu73idcentro=T19.unad24id AND TB.saiu73codpais=T20.unad18codigo AND TB.saiu73coddepto=T21.unad19codigo AND TB.saiu73codciudad=T22.unad20codigo AND TB.saiu73idescuela=T23.core12id AND TB.saiu73idprograma=T24.core09id AND TB.saiu73idperiodo=T25.exte02id AND TB.saiu73idresponsable=T34.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.saiu73agno, TB.saiu73mes, TB.saiu73tiporadicado, TB.saiu73consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		$tabladetalle = $objDB->ejecutasql($sSQLContador);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sSQL = $objDB->sSQLPaginar($sCampos, $sConsulta, $sOrden, $rbase, $lineastabla);
		}
	}
	$sErrConsulta = '<input id="consulta_3073" name="consulta_3073" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_3073" name="titulos_3073" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3073: ' . $sSQL . '<br>';
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		if (!$bGigante) {
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
				$sSQLLimitado = $objDB->sSQLPaginar($sCampos, $sConsulta, $sOrden, $rbase, $lineastabla);
				$tabladetalle = $objDB->ejecutasql($sSQLLimitado);
			}
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['saiu73agno'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73mes'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiporadicado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73origenagno'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73origenmes'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73origenid'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73dia'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73hora'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73estado'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['saiu73idsolicitante'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tipointeresado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73clasesolicitud'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiposolicitud'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73temasolicitud'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idzona'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idcentro'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73codpais'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73coddepto'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73codciudad'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idescuela'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idprograma'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idperiodo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idpqrs'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73detalle'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idarchivo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73fechafin'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73horafin'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73paramercadeo'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['saiu73idresponsable'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiemprespdias'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiempresphoras'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiemprespminutos'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73solucion'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73respuesta'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idarchivorta'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73fecharespcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73horarespcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73minrespcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idunidadcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idequipocaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idsupervisorcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idresponsablecaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73numref'] . '</b></th>';
	$res = $res . '<th class="text-right">';
	$res = $res . html_paginador('paginaf3073', $registros, $lineastabla, $pagina, 'paginarf3073()');
	$res = $res . html_lpp('lppf3073', $lineastabla, 'paginarf3073()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	//$iEscuela = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		/*
		if ($iEscuela != $filadet['escuela']) {
			$iEscuela = $filadet['escuela'];
			$sNomEscuela = '{' . $filadet['escuela'] . '}';
			//list($sNomEscuela) = f2212_NombreEscuela($idEscuela, $objDB);
			$res = $res . '<tr class="fondoazul">';
			$res = $res . '<td colspan="5" align="center">' . $ETI['msg_escuela'] . ' <b>' . $sNomEscuela . '</b></td>';
			$res = $res . '</tr>';
		}
		*/
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
		$et_saiu73agno = '';
		if ($filadet['saiu73agno'] != 0) {
			$et_saiu73agno = $sPrefijo . formato_numero($filadet['saiu73agno']) . $sSufijo;
		}
		$et_saiu73mes = '';
		if ($filadet['saiu73mes'] != 0) {
			$et_saiu73mes = $sPrefijo . formato_numero($filadet['saiu73mes']) . $sSufijo;
		}
		$et_saiu73tiporadicado = $sPrefijo . cadena_notildes($filadet['saiu16nombre']) . $sSufijo;
		$et_saiu73consec = $sPrefijo . $filadet['saiu73consec'] . $sSufijo;
		$et_saiu73origenagno = '';
		if ($filadet['saiu73origenagno'] != 0) {
			$et_saiu73origenagno = $sPrefijo . formato_numero($filadet['saiu73origenagno']) . $sSufijo;
		}
		$et_saiu73origenmes = '';
		if ($filadet['saiu73origenmes'] != 0) {
			$et_saiu73origenmes = $sPrefijo . formato_numero($filadet['saiu73origenmes']) . $sSufijo;
		}
		$et_saiu73origenid = '';
		if ($filadet['saiu73origenid'] != 0) {
			$et_saiu73origenid = $sPrefijo . formato_numero($filadet['saiu73origenid']) . $sSufijo;
		}
		$et_saiu73dia = '';
		if ($filadet['saiu73dia'] != 0) {
			$et_saiu73dia = $sPrefijo . formato_numero($filadet['saiu73dia']) . $sSufijo;
		}
		$et_saiu73hora = $sPrefijo . html_TablaHoraMin($filadet['saiu73hora'], $filadet['saiu73minuto']) . $sSufijo;
		$et_saiu73minuto = '';
		if ($filadet['saiu73minuto'] != 0) {
			$et_saiu73minuto = $sPrefijo . formato_numero($filadet['saiu73minuto']) . $sSufijo;
		}
		$et_saiu73estado = $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo;
		$et_saiu73idsolicitante_doc = '';
		$et_saiu73idsolicitante_nombre = '';
		if ($filadet['saiu73idsolicitante'] != 0) {
			$et_saiu73idsolicitante_doc = $sPrefijo . $filadet['C13_td'] . ' ' . $filadet['C13_doc'] . $sSufijo;
			$et_saiu73idsolicitante_nombre = $sPrefijo . cadena_notildes($filadet['C13_nombre']) . $sSufijo;
		}
		$et_saiu73tipointeresado = $sPrefijo . cadena_notildes($filadet['bita07nombre']) . $sSufijo;
		$et_saiu73clasesolicitud = $sPrefijo . cadena_notildes($filadet['saiu01titulo']) . $sSufijo;
		$et_saiu73tiposolicitud = $sPrefijo . cadena_notildes($filadet['saiu02titulo']) . $sSufijo;
		$et_saiu73temasolicitud = $sPrefijo . cadena_notildes($filadet['saiu03titulo']) . $sSufijo;
		$et_saiu73idzona = $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo;
		$et_saiu73idcentro = $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo;
		$et_saiu73idescuela = $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo;
		$et_saiu73idprograma = $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo;
		$et_saiu73idperiodo = $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo;
		$et_saiu73idpqrs = '';
		if ($filadet['saiu73idpqrs'] != 0) {
			$et_saiu73idpqrs = $sPrefijo . formato_numero($filadet['saiu73idpqrs']) . $sSufijo;
		}
		$et_saiu73detalle = $sPrefijo . cadena_notildes($filadet['saiu73detalle']) . $sSufijo;
		$et_saiu73idarchivo = '';
		if ($filadet['saiu73idarchivo'] != 0) {
			//$et_saiu73idarchivo = '<img src="verarchivo.php?cont=' . $filadet['saiu73idorigen'] . '&id=' . $filadet['saiu73idarchivo'] . '&maxx=150"/>';
			$et_saiu73idarchivo = html_lnkarchivo((int)$filadet['saiu73idorigen'], (int)$filadet['saiu73idarchivo']);
		}
		$et_saiu73fechafin = '';
		if ($filadet['saiu73fechafin'] != 0) {
			$et_saiu73fechafin = $sPrefijo . fecha_desdenumero($filadet['saiu73fechafin']) . $sSufijo;
		}
		$et_saiu73horafin = $sPrefijo . html_TablaHoraMin($filadet['saiu73horafin'], $filadet['saiu73minutofin']) . $sSufijo;
		$et_saiu73minutofin = '';
		if ($filadet['saiu73minutofin'] != 0) {
			$et_saiu73minutofin = $sPrefijo . formato_numero($filadet['saiu73minutofin']) . $sSufijo;
		}
		$et_saiu73paramercadeo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['saiu73paramercadeo'] == 0) {
			$et_saiu73paramercadeo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_saiu73idresponsable_doc = '';
		$et_saiu73idresponsable_nombre = '';
		if ($filadet['saiu73idresponsable'] != 0) {
			$et_saiu73idresponsable_doc = $sPrefijo . $filadet['C34_td'] . ' ' . $filadet['C34_doc'] . $sSufijo;
			$et_saiu73idresponsable_nombre = $sPrefijo . cadena_notildes($filadet['C34_nombre']) . $sSufijo;
		}
		$et_saiu73tiemprespdias = '';
		if ($filadet['saiu73tiemprespdias'] != 0) {
			$et_saiu73tiemprespdias = $sPrefijo . formato_numero($filadet['saiu73tiemprespdias']) . $sSufijo;
		}
		$et_saiu73tiempresphoras = '';
		if ($filadet['saiu73tiempresphoras'] != 0) {
			$et_saiu73tiempresphoras = $sPrefijo . formato_numero($filadet['saiu73tiempresphoras']) . $sSufijo;
		}
		$et_saiu73tiemprespminutos = '';
		if ($filadet['saiu73tiemprespminutos'] != 0) {
			$et_saiu73tiemprespminutos = $sPrefijo . formato_numero($filadet['saiu73tiemprespminutos']) . $sSufijo;
		}
		$et_saiu73solucion = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['saiu73solucion'] == 0) {
			$et_saiu73solucion = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_saiu73idcaso = '';
		if ($filadet['saiu73idcaso'] != 0) {
			$et_saiu73idcaso = $sPrefijo . formato_numero($filadet['saiu73idcaso']) . $sSufijo;
		}
		$et_saiu73respuesta = $sPrefijo . cadena_notildes($filadet['saiu73respuesta']) . $sSufijo;
		$et_saiu73idarchivorta = '';
		if ($filadet['saiu73idarchivorta'] != 0) {
			//$et_saiu73idarchivorta = '<img src="verarchivo.php?cont=' . $filadet['saiu73idorigenrta'] . '&id=' . $filadet['saiu73idarchivorta'] . '&maxx=150"/>';
			$et_saiu73idarchivorta = html_lnkarchivo((int)$filadet['saiu73idorigenrta'], (int)$filadet['saiu73idarchivorta']);
		}
		$et_saiu73fecharespcaso = '';
		if ($filadet['saiu73fecharespcaso'] != 0) {
			$et_saiu73fecharespcaso = $sPrefijo . formato_numero($filadet['saiu73fecharespcaso']) . $sSufijo;
		}
		$et_saiu73horarespcaso = '';
		if ($filadet['saiu73horarespcaso'] != 0) {
			$et_saiu73horarespcaso = $sPrefijo . formato_numero($filadet['saiu73horarespcaso']) . $sSufijo;
		}
		$et_saiu73minrespcaso = '';
		if ($filadet['saiu73minrespcaso'] != 0) {
			$et_saiu73minrespcaso = $sPrefijo . formato_numero($filadet['saiu73minrespcaso']) . $sSufijo;
		}
		$et_saiu73idunidadcaso = '';
		if ($filadet['saiu73idunidadcaso'] != 0) {
			$et_saiu73idunidadcaso = $sPrefijo . formato_numero($filadet['saiu73idunidadcaso']) . $sSufijo;
		}
		$et_saiu73idequipocaso = '';
		if ($filadet['saiu73idequipocaso'] != 0) {
			$et_saiu73idequipocaso = $sPrefijo . formato_numero($filadet['saiu73idequipocaso']) . $sSufijo;
		}
		$et_saiu73idsupervisorcaso = '';
		if ($filadet['saiu73idsupervisorcaso'] != 0) {
			$et_saiu73idsupervisorcaso = $sPrefijo . formato_numero($filadet['saiu73idsupervisorcaso']) . $sSufijo;
		}
		$et_saiu73idresponsablecaso = '';
		if ($filadet['saiu73idresponsablecaso'] != 0) {
			$et_saiu73idresponsablecaso = $sPrefijo . formato_numero($filadet['saiu73idresponsablecaso']) . $sSufijo;
		}
		$et_saiu73numref = $sPrefijo . cadena_notildes($filadet['saiu73numref']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3073(' . $filadet['saiu73id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_saiu73agno . '</td>';
		$res = $res . '<td>' . $et_saiu73mes . '</td>';
		$res = $res . '<td>' . $et_saiu73tiporadicado . '</td>';
		$res = $res . '<td>' . $et_saiu73consec . '</td>';
		$res = $res . '<td>' . $et_saiu73origenagno . '</td>';
		$res = $res . '<td>' . $et_saiu73origenmes . '</td>';
		$res = $res . '<td>' . $et_saiu73origenid . '</td>';
		$res = $res . '<td>' . $et_saiu73dia . '</td>';
		$res = $res . '<td>' . $et_saiu73hora . '</td>';
		$res = $res . '<td>' . $et_saiu73estado . '</td>';
		$res = $res . '<td>' . $et_saiu73idsolicitante_doc . '</td>';
		$res = $res . '<td>' . $et_saiu73idsolicitante_nombre . '</td>';
		$res = $res . '<td>' . $et_saiu73tipointeresado . '</td>';
		$res = $res . '<td>' . $et_saiu73clasesolicitud . '</td>';
		$res = $res . '<td>' . $et_saiu73tiposolicitud . '</td>';
		$res = $res . '<td>' . $et_saiu73temasolicitud . '</td>';
		$res = $res . '<td>' . $et_saiu73idzona . '</td>';
		$res = $res . '<td>' . $et_saiu73idcentro . '</td>';
		$res = $res . '<td>' . $et_saiu73codpais . '</td>';
		$res = $res . '<td>' . $et_saiu73coddepto . '</td>';
		$res = $res . '<td>' . $et_saiu73codciudad . '</td>';
		$res = $res . '<td>' . $et_saiu73idescuela . '</td>';
		$res = $res . '<td>' . $et_saiu73idprograma . '</td>';
		$res = $res . '<td>' . $et_saiu73idperiodo . '</td>';
		$res = $res . '<td>' . $et_saiu73idpqrs . '</td>';
		$res = $res . '<td>' . $et_saiu73detalle . '</td>';
		$res = $res . '<td>' . $et_saiu73idarchivo . '</td>';
		$res = $res . '<td>' . $et_saiu73fechafin . '</td>';
		$res = $res . '<td>' . $et_saiu73horafin . '</td>';
		$res = $res . '<td>' . $et_saiu73paramercadeo . '</td>';
		$res = $res . '<td>' . $et_saiu73idresponsable_doc . '</td>';
		$res = $res . '<td>' . $et_saiu73idresponsable_nombre . '</td>';
		$res = $res . '<td>' . $et_saiu73tiemprespdias . '</td>';
		$res = $res . '<td>' . $et_saiu73tiempresphoras . '</td>';
		$res = $res . '<td>' . $et_saiu73tiemprespminutos . '</td>';
		$res = $res . '<td>' . $et_saiu73solucion . '</td>';
		$res = $res . '<td>' . $et_saiu73idcaso . '</td>';
		$res = $res . '<td>' . $et_saiu73respuesta . '</td>';
		$res = $res . '<td>' . $et_saiu73idarchivorta . '</td>';
		$res = $res . '<td>' . $et_saiu73fecharespcaso . '</td>';
		$res = $res . '<td>' . $et_saiu73horarespcaso . '</td>';
		$res = $res . '<td>' . $et_saiu73minrespcaso . '</td>';
		$res = $res . '<td>' . $et_saiu73idunidadcaso . '</td>';
		$res = $res . '<td>' . $et_saiu73idequipocaso . '</td>';
		$res = $res . '<td>' . $et_saiu73idsupervisorcaso . '</td>';
		$res = $res . '<td>' . $et_saiu73idresponsablecaso . '</td>';
		$res = $res . '<td>' . $et_saiu73numref . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3073_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3073_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3073detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3073_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['saiu73idsolicitante_td'] = $APP->tipo_doc;
	$DATA['saiu73idsolicitante_doc'] = '';
	$DATA['saiu73idresponsable_td'] = $APP->tipo_doc;
	$DATA['saiu73idresponsable_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'saiu73agno=' . $DATA['saiu73agno'] . ' AND saiu73mes=' . $DATA['saiu73mes'] . ' AND saiu73tiporadicado=' . $DATA['saiu73tiporadicado'] . ' AND saiu73consec=' . $DATA['saiu73consec'] . '';
	} else {
		$sSQLcondi = 'saiu73id=' . $DATA['saiu73id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu73solusuario WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['saiu73agno'] = $fila['saiu73agno'];
		$DATA['saiu73mes'] = $fila['saiu73mes'];
		$DATA['saiu73tiporadicado'] = $fila['saiu73tiporadicado'];
		$DATA['saiu73consec'] = $fila['saiu73consec'];
		$DATA['saiu73id'] = $fila['saiu73id'];
		$DATA['saiu73origenagno'] = $fila['saiu73origenagno'];
		$DATA['saiu73origenmes'] = $fila['saiu73origenmes'];
		$DATA['saiu73origenid'] = $fila['saiu73origenid'];
		$DATA['saiu73dia'] = $fila['saiu73dia'];
		$DATA['saiu73hora'] = $fila['saiu73hora'];
		$DATA['saiu73minuto'] = $fila['saiu73minuto'];
		$DATA['saiu73estado'] = $fila['saiu73estado'];
		$DATA['saiu73idsolicitante'] = $fila['saiu73idsolicitante'];
		$DATA['saiu73tipointeresado'] = $fila['saiu73tipointeresado'];
		$DATA['saiu73clasesolicitud'] = $fila['saiu73clasesolicitud'];
		$DATA['saiu73tiposolicitud'] = $fila['saiu73tiposolicitud'];
		$DATA['saiu73temasolicitud'] = $fila['saiu73temasolicitud'];
		$DATA['saiu73idzona'] = $fila['saiu73idzona'];
		$DATA['saiu73idcentro'] = $fila['saiu73idcentro'];
		$DATA['saiu73codpais'] = $fila['saiu73codpais'];
		$DATA['saiu73coddepto'] = $fila['saiu73coddepto'];
		$DATA['saiu73codciudad'] = $fila['saiu73codciudad'];
		$DATA['saiu73idescuela'] = $fila['saiu73idescuela'];
		$DATA['saiu73idprograma'] = $fila['saiu73idprograma'];
		$DATA['saiu73idperiodo'] = $fila['saiu73idperiodo'];
		$DATA['saiu73idpqrs'] = $fila['saiu73idpqrs'];
		$DATA['saiu73detalle'] = $fila['saiu73detalle'];
		$DATA['saiu73idorigen'] = $fila['saiu73idorigen'];
		$DATA['saiu73idarchivo'] = $fila['saiu73idarchivo'];
		$DATA['saiu73fechafin'] = $fila['saiu73fechafin'];
		$DATA['saiu73horafin'] = $fila['saiu73horafin'];
		$DATA['saiu73minutofin'] = $fila['saiu73minutofin'];
		$DATA['saiu73paramercadeo'] = $fila['saiu73paramercadeo'];
		$DATA['saiu73idresponsable'] = $fila['saiu73idresponsable'];
		$DATA['saiu73tiemprespdias'] = $fila['saiu73tiemprespdias'];
		$DATA['saiu73tiempresphoras'] = $fila['saiu73tiempresphoras'];
		$DATA['saiu73tiemprespminutos'] = $fila['saiu73tiemprespminutos'];
		$DATA['saiu73solucion'] = $fila['saiu73solucion'];
		$DATA['saiu73idcaso'] = $fila['saiu73idcaso'];
		$DATA['saiu73respuesta'] = $fila['saiu73respuesta'];
		$DATA['saiu73idorigenrta'] = $fila['saiu73idorigenrta'];
		$DATA['saiu73idarchivorta'] = $fila['saiu73idarchivorta'];
		$DATA['saiu73fecharespcaso'] = $fila['saiu73fecharespcaso'];
		$DATA['saiu73horarespcaso'] = $fila['saiu73horarespcaso'];
		$DATA['saiu73minrespcaso'] = $fila['saiu73minrespcaso'];
		$DATA['saiu73idunidadcaso'] = $fila['saiu73idunidadcaso'];
		$DATA['saiu73idequipocaso'] = $fila['saiu73idequipocaso'];
		$DATA['saiu73idsupervisorcaso'] = $fila['saiu73idsupervisorcaso'];
		$DATA['saiu73idresponsablecaso'] = $fila['saiu73idresponsablecaso'];
		$DATA['saiu73numref'] = $fila['saiu73numref'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta3073'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3073_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 3073)
{
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3073 = 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3073)) {
		$mensajes_3073 = 'lg/lg_3073_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3073;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['saiu73agno']) == 0) {
		$DATA['saiu73agno'] = 0;
	}
	if (isset($DATA['saiu73mes']) == 0) {
		$DATA['saiu73mes'] = 0;
	}
	if (isset($DATA['saiu73tiporadicado']) == 0) {
		$DATA['saiu73tiporadicado'] = 0;
	}
	if (isset($DATA['saiu73consec']) == 0) {
		$DATA['saiu73consec'] = '';
	}
	if (isset($DATA['saiu73id']) == 0) {
		$DATA['saiu73id'] = '';
	}
	if (isset($DATA['saiu73origenid']) == 0) {
		$DATA['saiu73origenid'] = 0;
	}
	if (isset($DATA['saiu73dia']) == 0) {
		$DATA['saiu73dia'] = 0;
	}
	if (isset($DATA['saiu73hora']) == 0) {
		$DATA['saiu73hora'] = 0;
	}
	if (isset($DATA['saiu73minuto']) == 0) {
		$DATA['saiu73minuto'] = 0;
	}
	if (isset($DATA['saiu73estado']) == 0) {
		$DATA['saiu73estado'] = 0;
	}
	if (isset($DATA['saiu73idsolicitante']) == 0) {
		$DATA['saiu73idsolicitante'] = '';
	}
	if (isset($DATA['saiu73tipointeresado']) == 0) {
		$DATA['saiu73tipointeresado'] = 0;
	}
	if (isset($DATA['saiu73tiposolicitud']) == 0) {
		$DATA['saiu73tiposolicitud'] = 0;
	}
	if (isset($DATA['saiu73temasolicitud']) == 0) {
		$DATA['saiu73temasolicitud'] = 0;
	}
	if (isset($DATA['saiu73idzona']) == 0) {
		$DATA['saiu73idzona'] = 0;
	}
	if (isset($DATA['saiu73idcentro']) == 0) {
		$DATA['saiu73idcentro'] = 0;
	}
	if (isset($DATA['saiu73codpais']) == 0) {
		$DATA['saiu73codpais'] = '';
	}
	if (isset($DATA['saiu73coddepto']) == 0) {
		$DATA['saiu73coddepto'] = '';
	}
	if (isset($DATA['saiu73codciudad']) == 0) {
		$DATA['saiu73codciudad'] = '';
	}
	if (isset($DATA['saiu73idescuela']) == 0) {
		$DATA['saiu73idescuela'] = 0;
	}
	if (isset($DATA['saiu73idprograma']) == 0) {
		$DATA['saiu73idprograma'] = 0;
	}
	if (isset($DATA['saiu73idperiodo']) == 0) {
		$DATA['saiu73idperiodo'] = 0;
	}
	if (isset($DATA['saiu73idpqrs']) == 0) {
		$DATA['saiu73idpqrs'] = 0;
	}
	if (isset($DATA['saiu73detalle']) == 0) {
		$DATA['saiu73detalle'] = '';
	}
	if (isset($DATA['saiu73fechafin']) == 0) {
		$DATA['saiu73fechafin'] = 0;
	}
	if (isset($DATA['saiu73horafin']) == 0) {
		$DATA['saiu73horafin'] = 0;
	}
	if (isset($DATA['saiu73minutofin']) == 0) {
		$DATA['saiu73minutofin'] = 0;
	}
	if (isset($DATA['saiu73paramercadeo']) == 0) {
		$DATA['saiu73paramercadeo'] = 0;
	}
	if (isset($DATA['saiu73idresponsable']) == 0) {
		$DATA['saiu73idresponsable'] = '';
	}
	if (isset($DATA['saiu73solucion']) == 0) {
		$DATA['saiu73solucion'] = 0;
	}
	if (isset($DATA['saiu73idcaso']) == 0) {
		$DATA['saiu73idcaso'] = 0;
	}
	if (isset($DATA['saiu73respuesta']) == 0) {
		$DATA['saiu73respuesta'] = '';
	}
	if (isset($DATA['saiu73fecharespcaso']) == 0) {
		$DATA['saiu73fecharespcaso'] = 0;
	}
	if (isset($DATA['saiu73horarespcaso']) == 0) {
		$DATA['saiu73horarespcaso'] = 0;
	}
	if (isset($DATA['saiu73minrespcaso']) == 0) {
		$DATA['saiu73minrespcaso'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['saiu73consec'] = numeros_validar($DATA['saiu73consec']);
	$DATA['saiu73dia'] = numeros_validar($DATA['saiu73dia']);
	$DATA['saiu73hora'] = numeros_validar($DATA['saiu73hora']);
	$DATA['saiu73minuto'] = numeros_validar($DATA['saiu73minuto']);
	$DATA['saiu73idsolicitante'] = numeros_validar($DATA['saiu73idsolicitante']);
	$DATA['saiu73idsolicitante_td'] = cadena_Validar($DATA['saiu73idsolicitante_td']);
	$DATA['saiu73idsolicitante_doc'] = cadena_Validar($DATA['saiu73idsolicitante_doc']);
	$DATA['saiu73tipointeresado'] = numeros_validar($DATA['saiu73tipointeresado']);
	$DATA['saiu73tiposolicitud'] = numeros_validar($DATA['saiu73tiposolicitud']);
	$DATA['saiu73temasolicitud'] = numeros_validar($DATA['saiu73temasolicitud']);
	$DATA['saiu73idzona'] = numeros_validar($DATA['saiu73idzona']);
	$DATA['saiu73idcentro'] = numeros_validar($DATA['saiu73idcentro']);
	$DATA['saiu73codpais'] = cadena_Validar(trim($DATA['saiu73codpais']));
	$DATA['saiu73coddepto'] = cadena_Validar(trim($DATA['saiu73coddepto']));
	$DATA['saiu73codciudad'] = cadena_Validar(trim($DATA['saiu73codciudad']));
	$DATA['saiu73idescuela'] = numeros_validar($DATA['saiu73idescuela']);
	$DATA['saiu73idprograma'] = numeros_validar($DATA['saiu73idprograma']);
	$DATA['saiu73idperiodo'] = numeros_validar($DATA['saiu73idperiodo']);
	$DATA['saiu73detalle'] = cadena_Validar(trim($DATA['saiu73detalle']));
	$DATA['saiu73idorigen'] = numeros_validar($DATA['saiu73idorigen']);
	$DATA['saiu73idarchivo'] = numeros_validar($DATA['saiu73idarchivo']);
	$DATA['saiu73horafin'] = numeros_validar($DATA['saiu73horafin']);
	$DATA['saiu73minutofin'] = numeros_validar($DATA['saiu73minutofin']);
	$DATA['saiu73paramercadeo'] = numeros_validar($DATA['saiu73paramercadeo']);
	$DATA['saiu73idresponsable'] = numeros_validar($DATA['saiu73idresponsable']);
	$DATA['saiu73idresponsable_td'] = cadena_Validar($DATA['saiu73idresponsable_td']);
	$DATA['saiu73idresponsable_doc'] = cadena_Validar($DATA['saiu73idresponsable_doc']);
	$DATA['saiu73solucion'] = numeros_validar($DATA['saiu73solucion']);
	$DATA['saiu73respuesta'] = cadena_Validar(trim($DATA['saiu73respuesta']));
	$DATA['saiu73idorigenrta'] = numeros_validar($DATA['saiu73idorigenrta']);
	$DATA['saiu73idarchivorta'] = numeros_validar($DATA['saiu73idarchivorta']);
	$DATA['saiu73numref'] = cadena_Validar(trim($DATA['saiu73numref']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['saiu73origenid'] == '') {
		$DATA['saiu73origenid'] = 0;
	}
	if ($DATA['saiu73dia'] == '') {
		$DATA['saiu73dia'] = 0;
	}
	if ($DATA['saiu73hora'] == '') {
		$DATA['saiu73hora'] = 0;
	}
	if ($DATA['saiu73minuto'] == '') {
		$DATA['saiu73minuto'] = 0;
	}
	if ($DATA['saiu73estado'] == '') {
		$DATA['saiu73estado'] = 0;
	}
	if ($DATA['saiu73tipointeresado'] == '') {
		$DATA['saiu73tipointeresado'] = 0;
	}
	if ($DATA['saiu73tiposolicitud'] == '') {
		$DATA['saiu73tiposolicitud'] = 0;
	}
	if ($DATA['saiu73temasolicitud'] == '') {
		$DATA['saiu73temasolicitud'] = 0;
	}
	if ($DATA['saiu73idzona'] == '') {
		$DATA['saiu73idzona'] = 0;
	}
	if ($DATA['saiu73idcentro'] == '') {
		$DATA['saiu73idcentro'] = 0;
	}
	if ($DATA['saiu73idescuela'] == '') {
		$DATA['saiu73idescuela'] = 0;
	}
	if ($DATA['saiu73idprograma'] == '') {
		$DATA['saiu73idprograma'] = 0;
	}
	if ($DATA['saiu73idperiodo'] == '') {
		$DATA['saiu73idperiodo'] = 0;
	}
	if ($DATA['saiu73idpqrs'] == '') {
		$DATA['saiu73idpqrs'] = 0;
	}
	if ($DATA['saiu73horafin'] == '') {
		$DATA['saiu73horafin'] = 0;
	}
	if ($DATA['saiu73minutofin'] == '') {
		$DATA['saiu73minutofin'] = 0;
	}
	if ($DATA['saiu73paramercadeo'] == '') {
		$DATA['saiu73paramercadeo'] = 0;
	}
	if ($DATA['saiu73solucion'] == '') {
		$DATA['saiu73solucion'] = 0;
	}
	if ($DATA['saiu73idcaso'] == '') {
		$DATA['saiu73idcaso'] = 0;
	}
	if ($DATA['saiu73fecharespcaso'] == '') {
		$DATA['saiu73fecharespcaso'] = 0;
	}
	if ($DATA['saiu73horarespcaso'] == '') {
		$DATA['saiu73horarespcaso'] = 0;
	}
	if ($DATA['saiu73minrespcaso'] == '') {
		$DATA['saiu73minrespcaso'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		/*
		if ($DATA['saiu73respuesta'] == '') {
			$sError = $ERR['saiu73respuesta'] . $sSepara . $sError;
		}
		*/
		if ($DATA['saiu73solucion'] == '') {
			$sError = $ERR['saiu73solucion'] . $sSepara . $sError;
		}
		if ($DATA['saiu73idresponsable'] == 0) {
			$sError = $ERR['saiu73idresponsable'] . $sSepara . $sError;
		}
		if ($DATA['saiu73paramercadeo'] == '') {
			$sError = $ERR['saiu73paramercadeo'] . $sSepara . $sError;
		}
		if ($DATA['saiu73minutofin'] == '') {
			$sError = $ERR['saiu73minutofin'] . $sSepara . $sError;
		}
		if ($DATA['saiu73horafin'] == '') {
			$sError = $ERR['saiu73horafin'] . $sSepara . $sError;
		}
		if (!fecha_NumValido($DATA['saiu73fechafin'])) {
			//$DATA['saiu73fechafin'] = fecha_DiaMod();
			$sError = $ERR['saiu73fechafin'] . $sSepara . $sError;
		}
		/*
		if ($DATA['saiu73detalle'] == '') {
			$sError = $ERR['saiu73detalle'] . $sSepara . $sError;
		}
		*/
		if ($DATA['saiu73idperiodo'] == '') {
			$sError = $ERR['saiu73idperiodo'] . $sSepara . $sError;
		}
		if ($DATA['saiu73idprograma'] == '') {
			$sError = $ERR['saiu73idprograma'] . $sSepara . $sError;
		}
		if ($DATA['saiu73idescuela'] == '') {
			$sError = $ERR['saiu73idescuela'] . $sSepara . $sError;
		}
		if ($DATA['saiu73codciudad'] == '') {
			$sError = $ERR['saiu73codciudad'] . $sSepara . $sError;
		}
		if ($DATA['saiu73coddepto'] == '') {
			$sError = $ERR['saiu73coddepto'] . $sSepara . $sError;
		}
		if ($DATA['saiu73codpais'] == '') {
			$sError = $ERR['saiu73codpais'] . $sSepara . $sError;
		}
		if ($DATA['saiu73idcentro'] == '') {
			$sError = $ERR['saiu73idcentro'] . $sSepara . $sError;
		}
		if ($DATA['saiu73idzona'] == '') {
			$sError = $ERR['saiu73idzona'] . $sSepara . $sError;
		}
		if ($DATA['saiu73temasolicitud'] == '') {
			$sError = $ERR['saiu73temasolicitud'] . $sSepara . $sError;
		}
		if ($DATA['saiu73tiposolicitud'] == '') {
			$sError = $ERR['saiu73tiposolicitud'] . $sSepara . $sError;
		}
		if ($DATA['saiu73tipointeresado'] == '') {
			$sError = $ERR['saiu73tipointeresado'] . $sSepara . $sError;
		}
		if ($DATA['saiu73idsolicitante'] == 0) {
			$sError = $ERR['saiu73idsolicitante'] . $sSepara . $sError;
		}
		if ($DATA['saiu73minuto'] == '') {
			$sError = $ERR['saiu73minuto'] . $sSepara . $sError;
		}
		if ($DATA['saiu73hora'] == '') {
			$sError = $ERR['saiu73hora'] . $sSepara . $sError;
		}
		if ($DATA['saiu73dia'] == '') {
			$sError = $ERR['saiu73dia'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'saiu73numref');
		$aLargoCampos = array(0, 20);
		for ($k = 1; $k <= 1; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		if ($DATA['saiu73idresponsable_doc'] != '') {
			$sError = tabla_terceros_existe($DATA['saiu73idresponsable_td'], $DATA['saiu73idresponsable_doc'], $objDB, 'El tercero Responsable ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu73idresponsable'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($sError == '') {
		if ($DATA['saiu73idsolicitante_doc'] != '') {
			$sError = tabla_terceros_existe($DATA['saiu73idsolicitante_td'], $DATA['saiu73idsolicitante_doc'], $objDB, 'El tercero Solicitante ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu73idsolicitante'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['saiu73consec'] == '') {
				$DATA['saiu73consec'] = tabla_consecutivo('saiu73solusuario', 'saiu73consec', 'saiu73agno=' . $DATA['saiu73agno'] . ' AND saiu73mes=' . $DATA['saiu73mes'] . ' AND saiu73tiporadicado=' . $DATA['saiu73tiporadicado'] . '', $objDB);
				if ($DATA['saiu73consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'saiu73consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['saiu73consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM saiu73solusuario WHERE saiu73agno=' . $DATA['saiu73agno'] . ' AND saiu73mes=' . $DATA['saiu73mes'] . ' AND saiu73tiporadicado=' . $DATA['saiu73tiporadicado'] . ' AND saiu73consec=' . $DATA['saiu73consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve) {
						$sError = $ERR['2'] . ' [Mod ' . $iCodModulo . ']';
					}
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
			$DATA['saiu73id'] = tabla_consecutivo('saiu73solusuario', 'saiu73id', '', $objDB);
			if ($DATA['saiu73id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['saiu73origenagno'] = 0;
			$DATA['saiu73origenmes'] = 0;
			$DATA['saiu73origenid'] = 0;
			$DATA['saiu73estado'] = 0;
			$DATA['saiu73clasesolicitud'] = 0;
			$DATA['saiu73idpqrs'] = 0;
			$DATA['saiu73idorigen'] = 0;
			$DATA['saiu73idarchivo'] = 0;
			$saiu73fechafin = fecha_DiaMod();
			$DATA['saiu73tiemprespdias'] = 0;
			$DATA['saiu73tiempresphoras'] = 0;
			$DATA['saiu73tiemprespminutos'] = 0;
			$DATA['saiu73idcaso'] = 0;
			$DATA['saiu73idorigenrta'] = 0;
			$DATA['saiu73idarchivorta'] = 0;
			$DATA['saiu73fecharespcaso'] = 0;
			$DATA['saiu73horarespcaso'] = 0;
			$DATA['saiu73minrespcaso'] = 0;
			$DATA['saiu73idunidadcaso'] = 0;
			$DATA['saiu73idequipocaso'] = 0;
			$DATA['saiu73idsupervisorcaso'] = 0;
			$DATA['saiu73idresponsablecaso'] = 0;
			$DATA['saiu73numref'] = '';
		}
	}
	if ($sError == '') {
		//$saiu73detalle = addslashes($DATA['saiu73detalle']);
		$saiu73detalle = str_replace('"', '\"', $DATA['saiu73detalle']);
		//$saiu73respuesta = addslashes($DATA['saiu73respuesta']);
		$saiu73respuesta = str_replace('"', '\"', $DATA['saiu73respuesta']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos3073 = 'saiu73agno, saiu73mes, saiu73tiporadicado, saiu73consec, saiu73id, 
			saiu73origenagno, saiu73origenmes, saiu73origenid, saiu73dia, saiu73hora, 
			saiu73minuto, saiu73estado, saiu73idsolicitante, saiu73tipointeresado, saiu73clasesolicitud, 
			saiu73tiposolicitud, saiu73temasolicitud, saiu73idzona, saiu73idcentro, saiu73codpais, 
			saiu73coddepto, saiu73codciudad, saiu73idescuela, saiu73idprograma, saiu73idperiodo, 
			saiu73idpqrs, saiu73detalle, saiu73idorigen, saiu73idarchivo, saiu73fechafin, 
			saiu73horafin, saiu73minutofin, saiu73paramercadeo, saiu73idresponsable, saiu73tiemprespdias, 
			saiu73tiempresphoras, saiu73tiemprespminutos, saiu73solucion, saiu73idcaso, saiu73respuesta, 
			saiu73idorigenrta, saiu73idarchivorta, saiu73fecharespcaso, saiu73horarespcaso, saiu73minrespcaso, 
			saiu73idunidadcaso, saiu73idequipocaso, saiu73idsupervisorcaso, saiu73idresponsablecaso, saiu73numref';
			$sValores3073 = '' . $DATA['saiu73agno'] . ', ' . $DATA['saiu73mes'] . ', ' . $DATA['saiu73tiporadicado'] . ', ' . $DATA['saiu73consec'] . ', ' . $DATA['saiu73id'] . ', 
			' . $DATA['saiu73origenagno'] . ', ' . $DATA['saiu73origenmes'] . ', ' . $DATA['saiu73origenid'] . ', ' . $DATA['saiu73dia'] . ', ' . $DATA['saiu73hora'] . ', 
			' . $DATA['saiu73minuto'] . ', ' . $DATA['saiu73estado'] . ', ' . $DATA['saiu73idsolicitante'] . ', ' . $DATA['saiu73tipointeresado'] . ', ' . $DATA['saiu73clasesolicitud'] . ', 
			' . $DATA['saiu73tiposolicitud'] . ', ' . $DATA['saiu73temasolicitud'] . ', ' . $DATA['saiu73idzona'] . ', ' . $DATA['saiu73idcentro'] . ', "' . $DATA['saiu73codpais'] . '", 
			"' . $DATA['saiu73coddepto'] . '", "' . $DATA['saiu73codciudad'] . '", ' . $DATA['saiu73idescuela'] . ', ' . $DATA['saiu73idprograma'] . ', ' . $DATA['saiu73idperiodo'] . ', 
			' . $DATA['saiu73idpqrs'] . ', "' . $saiu73detalle . '", ' . $DATA['saiu73idorigen'] . ', ' . $DATA['saiu73idarchivo'] . ', ' . $DATA['saiu73fechafin'] . ', 
			' . $DATA['saiu73horafin'] . ', ' . $DATA['saiu73minutofin'] . ', ' . $DATA['saiu73paramercadeo'] . ', ' . $DATA['saiu73idresponsable'] . ', ' . $DATA['saiu73tiemprespdias'] . ', 
			' . $DATA['saiu73tiempresphoras'] . ', ' . $DATA['saiu73tiemprespminutos'] . ', ' . $DATA['saiu73solucion'] . ', ' . $DATA['saiu73idcaso'] . ', "' . $saiu73respuesta . '", 
			' . $DATA['saiu73idorigenrta'] . ', ' . $DATA['saiu73idarchivorta'] . ', ' . $DATA['saiu73fecharespcaso'] . ', ' . $DATA['saiu73horarespcaso'] . ', ' . $DATA['saiu73minrespcaso'] . ', 
			' . $DATA['saiu73idunidadcaso'] . ', ' . $DATA['saiu73idequipocaso'] . ', ' . $DATA['saiu73idsupervisorcaso'] . ', ' . $DATA['saiu73idresponsablecaso'] . ', "' . $DATA['saiu73numref'] . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO saiu73solusuario (' . $sCampos3073 . ') VALUES (' . cadena_codificar($sValores3073) . ');';
				$sdetalle = $sCampos3073 . '[' . cadena_codificar($sValores3073) . ']';
			} else {
				$sSQL = 'INSERT INTO saiu73solusuario (' . $sCampos3073 . ') VALUES (' . $sValores3073 . ');';
				$sdetalle = $sCampos3073 . '[' . $sValores3073 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'saiu73dia';
			$scampo[2] = 'saiu73hora';
			$scampo[3] = 'saiu73minuto';
			$scampo[4] = 'saiu73idsolicitante';
			$scampo[5] = 'saiu73tipointeresado';
			$scampo[6] = 'saiu73tiposolicitud';
			$scampo[7] = 'saiu73temasolicitud';
			$scampo[8] = 'saiu73idzona';
			$scampo[9] = 'saiu73idcentro';
			$scampo[10] = 'saiu73codpais';
			$scampo[11] = 'saiu73coddepto';
			$scampo[12] = 'saiu73codciudad';
			$scampo[13] = 'saiu73idescuela';
			$scampo[14] = 'saiu73idprograma';
			$scampo[15] = 'saiu73idperiodo';
			$scampo[16] = 'saiu73detalle';
			$scampo[17] = 'saiu73fechafin';
			$scampo[18] = 'saiu73horafin';
			$scampo[19] = 'saiu73minutofin';
			$scampo[20] = 'saiu73paramercadeo';
			$scampo[21] = 'saiu73idresponsable';
			$scampo[22] = 'saiu73solucion';
			$scampo[23] = 'saiu73respuesta';
			$sdato[1] = $DATA['saiu73dia'];
			$sdato[2] = $DATA['saiu73hora'];
			$sdato[3] = $DATA['saiu73minuto'];
			$sdato[4] = $DATA['saiu73idsolicitante'];
			$sdato[5] = $DATA['saiu73tipointeresado'];
			$sdato[6] = $DATA['saiu73tiposolicitud'];
			$sdato[7] = $DATA['saiu73temasolicitud'];
			$sdato[8] = $DATA['saiu73idzona'];
			$sdato[9] = $DATA['saiu73idcentro'];
			$sdato[10] = $DATA['saiu73codpais'];
			$sdato[11] = $DATA['saiu73coddepto'];
			$sdato[12] = $DATA['saiu73codciudad'];
			$sdato[13] = $DATA['saiu73idescuela'];
			$sdato[14] = $DATA['saiu73idprograma'];
			$sdato[15] = $DATA['saiu73idperiodo'];
			$sdato[16] = $saiu73detalle;
			$sdato[17] = $DATA['saiu73fechafin'];
			$sdato[18] = $DATA['saiu73horafin'];
			$sdato[19] = $DATA['saiu73minutofin'];
			$sdato[20] = $DATA['saiu73paramercadeo'];
			$sdato[21] = $DATA['saiu73idresponsable'];
			$sdato[22] = $DATA['saiu73solucion'];
			$sdato[23] = $saiu73respuesta;
			$iNumCamposMod = 23;
			$sWhere = 'saiu73id=' . $DATA['saiu73id'] . '';
			$sSQL = 'SELECT * FROM saiu73solusuario WHERE ' . $sWhere;
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
					$sdetalle = cadena_codificar($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE saiu73solusuario SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE saiu73solusuario SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3073 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3073] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['saiu73id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu73id'], $sdetalle, $objDB);
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
function f3073_db_Eliminar($saiu73id, $objDB, $bDebug = false)
{
	$iCodModulo = 3073;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3073 = 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3073)) {
		$mensajes_3073 = 'lg/lg_3073_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3073;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu73id = numeros_validar($saiu73id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM saiu73solusuario WHERE saiu73id=' . $saiu73id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $saiu73id . '}';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3073';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['saiu73id'] . ' LIMIT 0, 1';
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
		$sWhere = 'saiu73id=' . $saiu73id . '';
		//$sWhere = 'saiu73consec=' . $filabase['saiu73consec'] . ' AND saiu73tiporadicado=' . $filabase['saiu73tiporadicado'] . ' AND saiu73mes=' . $filabase['saiu73mes'] . ' AND saiu73agno=' . $filabase['saiu73agno'] . '';
		$sSQL = 'DELETE FROM saiu73solusuario WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu73id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f3073_TituloBusqueda()
{
	require './app.php';
	$mensajes_3073 = 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3073)) {
		$mensajes_3073 = 'lg/lg_3073_es.php';
	}
	require $mensajes_3073;
	return $ETI['titulo_busca_3073'];
}
function f3073_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3073 = 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3073)) {
		$mensajes_3073 = 'lg/lg_3073_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3073;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b3073nombre" name="b3073nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f3073_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'let sCampo = window.document.frmedita.scampobusca.value;
	let params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b3073nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f3073_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3073 = 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3073)) {
		$mensajes_3073 = 'lg/lg_3073_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3073;
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
	$idTercero = numeros_validar($aParametros[100]);
	$pagina = numeros_validar($aParametros[101]);
	$lineastabla = numeros_validar($aParametros[102]);
	//$bNombre = trim($aParametros[103]);
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
	$sBotones = '<input id="paginaf3073" name="paginaf3073" type="hidden" value="' . $pagina . '" />';
	$sBotones = $sBotones . '<input id="lppf3073" name="lppf3073" type="hidden" value="' . $lineastabla . '" />';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
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
		$sBase = trim(mb_strtoupper($aParametros[104]));
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
	$sTitulos = 'Agno, Mes, Tiporadicado, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Estado, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Pqrs, Detalle, Origen, Archivo, Fechafin, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos, Solucion, Caso, Respuesta, Origenrta, Archivorta, Fecharespcaso, Horarespcaso, Minrespcaso, Unidadcaso, Equipocaso, Supervisorcaso, Responsablecaso, Numref';
	$sCampos = 'SELECT TB.saiu73agno, TB.saiu73mes, T3.saiu16nombre, TB.saiu73consec, TB.saiu73id, TB.saiu73origenagno, TB.saiu73origenmes, TB.saiu73origenid, TB.saiu73dia, TB.saiu73hora, TB.saiu73minuto, T12.saiu11nombre, T13.unad11razonsocial AS C13_nombre, T14.bita07nombre, T15.saiu01titulo, T16.saiu02titulo, T17.saiu03titulo, T18.unad23nombre, T19.unad24nombre, T20.unad18nombre, T21.unad19nombre, T22.unad20nombre, T23.core12nombre, T24.core09nombre, T25.exte02nombre, TB.saiu73idpqrs, TB.saiu73detalle, TB.saiu73idorigen, TB.saiu73idarchivo, TB.saiu73fechafin, TB.saiu73horafin, TB.saiu73minutofin, TB.saiu73paramercadeo, T34.unad11razonsocial AS C34_nombre, TB.saiu73tiemprespdias, TB.saiu73tiempresphoras, TB.saiu73tiemprespminutos, TB.saiu73solucion, TB.saiu73idcaso, TB.saiu73respuesta, TB.saiu73idorigenrta, TB.saiu73idarchivorta, TB.saiu73fecharespcaso, TB.saiu73horarespcaso, TB.saiu73minrespcaso, TB.saiu73idunidadcaso, TB.saiu73idequipocaso, TB.saiu73idsupervisorcaso, TB.saiu73idresponsablecaso, TB.saiu73numref, TB.saiu73tiporadicado, TB.saiu73estado, TB.saiu73idsolicitante, T13.unad11tipodoc AS C13_td, T13.unad11doc AS C13_doc, TB.saiu73tipointeresado, TB.saiu73clasesolicitud, TB.saiu73tiposolicitud, TB.saiu73temasolicitud, TB.saiu73idzona, TB.saiu73idcentro, TB.saiu73codpais, TB.saiu73coddepto, TB.saiu73codciudad, TB.saiu73idescuela, TB.saiu73idprograma, TB.saiu73idperiodo, TB.saiu73idresponsable, T34.unad11tipodoc AS C34_td, T34.unad11doc AS C34_doc';
	$sConsulta = 'FROM saiu73solusuario AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T12, unad11terceros AS T13, bita07tiposolicitante AS T14, saiu01claseser AS T15, saiu02tiposol AS T16, saiu03temasol AS T17, unad23zona AS T18, unad24sede AS T19, unad18pais AS T20, unad19depto AS T21, unad20ciudad AS T22, core12escuela AS T23, core09programa AS T24, exte02per_aca AS T25, unad11terceros AS T34 
	WHERE ' . $sSQLadd1 . ' TB.saiu73tiporadicado=T3.saiu16id AND TB.saiu73estado=T12.saiu11id AND TB.saiu73idsolicitante=T13.unad11id AND TB.saiu73tipointeresado=T14.bita07id AND TB.saiu73clasesolicitud=T15.saiu01id AND TB.saiu73tiposolicitud=T16.saiu02id AND TB.saiu73temasolicitud=T17.saiu03id AND TB.saiu73idzona=T18.unad23id AND TB.saiu73idcentro=T19.unad24id AND TB.saiu73codpais=T20.unad18codigo AND TB.saiu73coddepto=T21.unad19codigo AND TB.saiu73codciudad=T22.unad20codigo AND TB.saiu73idescuela=T23.core12id AND TB.saiu73idprograma=T24.core09id AND TB.saiu73idperiodo=T25.exte02id AND TB.saiu73idresponsable=T34.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.saiu73agno, TB.saiu73mes, TB.saiu73tiporadicado, TB.saiu73consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="' . $sSQLlista . '" />';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="' . $sTitulos . '" />';
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
			$sSQLLimitado = $objDB->sSQLPaginar($sCampos, $sConsulta, $sOrden, $rbase, $lineastabla);
			$tabladetalle = $objDB->ejecutasql($sSQLLimitado);
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="table--primary">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['saiu73agno'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73mes'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiporadicado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73origenagno'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73origenmes'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73origenid'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73dia'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73hora'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73estado'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['saiu73idsolicitante'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tipointeresado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73clasesolicitud'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiposolicitud'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73temasolicitud'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idzona'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idcentro'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73codpais'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73coddepto'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73codciudad'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idescuela'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idprograma'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idperiodo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idpqrs'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73detalle'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idarchivo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73fechafin'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73horafin'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73paramercadeo'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['saiu73idresponsable'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiemprespdias'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiempresphoras'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73tiemprespminutos'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73solucion'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73respuesta'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idarchivorta'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73fecharespcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73horarespcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73minrespcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idunidadcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idequipocaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idsupervisorcaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73idresponsablecaso'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu73numref'] . '</b></th>';
	$res = $res . '<th class="text-right">';
	$res = $res . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()');
	$res = $res . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['saiu73id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_saiu73hora =  $sPrefijo . html_TablaHoraMin($filadet['saiu73hora'], $filadet['saiu73minuto']) . $sSufijo;
		$et_saiu73idarchivo = '';
		if ($filadet['saiu73idarchivo'] != 0) {
			//$et_saiu73idarchivo = '<img src="verarchivo.php?cont=' . $filadet['saiu73idorigen'] . '&id=' . $filadet['saiu73idarchivo'] . '&maxx=150" />';
			$et_saiu73idarchivo = html_lnkarchivo((int)$filadet['saiu73idorigen'], (int)$filadet['saiu73idarchivo']);
		}
		$et_saiu73fechafin = '';
		if ($filadet['saiu73fechafin'] != 0) {
			$et_saiu73fechafin = fecha_desdenumero($filadet['saiu73fechafin']);
		}
		$et_saiu73horafin =  $sPrefijo . html_TablaHoraMin($filadet['saiu73horafin'], $filadet['saiu73minutofin']) . $sSufijo;
		$et_saiu73paramercadeo = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['saiu73paramercadeo'] == 'S') {
			$et_saiu73paramercadeo = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_saiu73solucion = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['saiu73solucion'] == 'S') {
			$et_saiu73solucion = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_saiu73idarchivorta = '';
		if ($filadet['saiu73idarchivorta'] != 0) {
			//$et_saiu73idarchivorta = '<img src="verarchivo.php?cont=' . $filadet['saiu73idorigenrta'] . '&id=' . $filadet['saiu73idarchivorta'] . '&maxx=150" />';
			$et_saiu73idarchivorta = html_lnkarchivo((int)$filadet['saiu73idorigenrta'], (int)$filadet['saiu73idarchivorta']);
		}
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73agno'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73mes'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu16nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73consec'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73origenagno'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73origenmes'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73origenid'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73dia'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $et_saiu73hora . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['C13_td'] . ' ' . $filadet['C13_doc'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['C13_nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['bita07nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu01titulo']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu02titulo']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu03titulo']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73codpais'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73coddepto'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73codciudad'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73idpqrs'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu73detalle']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $et_saiu73idarchivo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $et_saiu73fechafin . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $et_saiu73horafin . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73paramercadeo'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['C34_td'] . ' ' . $filadet['C34_doc'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['C34_nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73tiemprespdias'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73tiempresphoras'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73tiemprespminutos'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73solucion'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73idcaso'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu73respuesta']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $et_saiu73idarchivorta . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73fecharespcaso'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73horarespcaso'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73minrespcaso'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73idunidadcaso'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73idequipocaso'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73idsupervisorcaso'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu73idresponsablecaso'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu73numref']) . $sSufijo . '</td>';
		$res = $res . '<td></td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return cadena_codificar($res);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

