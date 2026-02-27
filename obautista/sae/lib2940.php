<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2940 visa40inscripcion
*/
/** Archivo lib2940.php.
 * Libreria 2940 visa40inscripcion.
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @date viernes, 27 de febrero de 2026
 */
function f2940_HTMLComboV2_visa40idconvocatoria($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa40idconvocatoria', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.visa35id AS id, TB.visa35nombre AS nombre 
	FROM visa35convocatoria AS TB
	WHERE TB.visa35id>0
	ORDER BY TB.visa35nombre';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_HTMLComboV2_visa40idprograma($objDB, $objCombos, $valor, $vrvisa40idescuela)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa40idprograma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrvisa40idescuela != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core09id AS id, TB.core09nombre AS nombre 
		FROM core09programa AS TB
		WHERE TB.core09idescuela=' . $vrvisa40idescuela . ' 
		ORDER BY TB.core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_HTMLComboV2_visa40idcentro($objDB, $objCombos, $valor, $vrvisa40idzona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa40idcentro', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrvisa40idzona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrvisa40idzona . ' 
		ORDER BY TB.unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_HTMLComboV2_visa40idsubtipo($objDB, $objCombos, $valor, $vrvisa40idtipologia)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa40idsubtipo', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrvisa40idtipologia != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.visa37id AS id, TB.visa37nombre AS nombre 
		FROM visa37convsubtipo AS TB
		WHERE TB.visa37idtipologia=' . $vrvisa40idtipologia . ' 
		ORDER BY TB.visa37nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_Combovisa40idprograma($aParametros)
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
	$html_visa40idprograma = f2940_HTMLComboV2_visa40idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa40idprograma', 'innerHTML', $html_visa40idprograma);
	//$objResponse->call('$("#visa40idprograma").chosen()');
	return $objResponse;
}
function f2940_Combovisa40idcentro($aParametros)
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
	$html_visa40idcentro = f2940_HTMLComboV2_visa40idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa40idcentro', 'innerHTML', $html_visa40idcentro);
	//$objResponse->call('$("#visa40idcentro").chosen()');
	return $objResponse;
}
function f2940_Combovisa40idsubtipo($aParametros)
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
	$html_visa40idsubtipo = f2940_HTMLComboV2_visa40idsubtipo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa40idsubtipo', 'innerHTML', $html_visa40idsubtipo);
	//$objResponse->call('$("#visa40idsubtipo").chosen()');
	return $objResponse;
}
function f2940_HTMLComboV2_bsubtipologia($objDB, $objCombos, $valor, $vrbtipologia)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('bsubtipologia', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf2940()';
	$sSQL = '';
	if ((int)$vrbtipologia != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.visa37id AS id, TB.visa37nombre AS nombre 
		FROM visa37convsubtipo AS TB
		WHERE TB.visa37idtipologia=' . $vrbtipologia . ' 
		ORDER BY TB.visa37nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2940, $sIdioma
	return $res;
}
function f2940_Combobsubtipologia($aParametros)
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
	$html_bsubtipologia = f2940_HTMLComboV2_bsubtipologia($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bsubtipologia', 'innerHTML', $html_bsubtipologia);
	//$objResponse->call('$("#bsubtipologia").chosen()');
	$objResponse->call('paginarf2940()');
	return $objResponse;
}
function f2940_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$visa40idconvocatoria = numeros_validar($datos[1]);
	if ($visa40idconvocatoria == '') {
		$bHayLlave = false;
	}
	$visa40idtercero = numeros_validar($datos[2]);
	if ($visa40idtercero == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM visa40inscripcion WHERE visa40idconvocatoria=' . $visa40idconvocatoria . ' AND visa40idtercero=' . $visa40idtercero . '';
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
function f2940_Busquedas($aParametros)
{
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2940 = 'lg/lg_2940_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2940)) {
		$mensajes_2940 = 'lg/lg_2940_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2940;
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
	$sTituloModulo = $ETI['titulo_2940'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'visa40idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa40idtercero_busca']) == 0) {
				$ETI['visa40idtercero_busca'] = 'Busqueda de Tercero';
			}
			$sTitulo = $ETI['visa40idtercero_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2940);
			break;
		case 'visa43usuarioaprueba':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa43usuarioaprueba_busca']) == 0) {
				$ETI['visa43usuarioaprueba_busca'] = 'Busqueda de Usuarioaprueba';
			}
			$sTitulo = $ETI['visa43usuarioaprueba_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2940);
			break;
		case 'visa44usuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa44usuario_busca']) == 0) {
				$ETI['visa44usuario_busca'] = 'Busqueda de Usuario';
			}
			$sTitulo = $ETI['visa44usuario_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2940);
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
function f2940_HtmlBusqueda($aParametros)
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
		case 'visa40idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'visa43usuarioaprueba':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'visa44usuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2940_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_2900 = 'lg/lg_2900_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2900)) {
		$mensajes_2900 = 'lg/lg_2900_es.php';
	}
	require $mensajes_2900;
	*/
	$mensajes_2940 = 'lg/lg_2940_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2940)) {
		$mensajes_2940 = 'lg/lg_2940_es.php';
	}
	require $mensajes_2940;
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
	$iNumVariables = 108;
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
	$bdocumento = cadena_Validar(trim($aParametros[103]));
	$bNombre = cadena_Validar(trim($aParametros[104]));
	$bconvocatoria = numeros_validar($aParametros[105]);
	$bestado = numeros_validar($aParametros[106]);
	$btipologia = numeros_validar($aParametros[107]);
	$bsubtipologia = numeros_validar($aParametros[108]);
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
	$sBotones = '<input id="paginaf2940" name="paginaf2940" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2940" name="lppf2940" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	/*
	$aEstado = array('');
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['id']] = cadena_notildes($fila['nombre']);
	}
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	/*
	if ($bdocumento != '') {
		$sSQLadd = $sSQLadd . ' AND TB.campo103 LIKE "%' . $bdocumento . '%"';
	}
	if ($bnombre != '') {
		$sSQLadd = $sSQLadd . ' AND TB.campo104 LIKE "%' . $bnombre . '%"';
	}
	if ($bconvocatoria != '') {
		$sSQLadd1 = $sSQLadd1 . 'visa40idconvocatoria=' . $bconvocatoria . ' AND ';
	}
	if ($bestado != '') {
		$sSQLadd1 = $sSQLadd1 . 'visa40estado=' . $bestado . ' AND ';
	}
	if ($btipologia != '') {
		$sSQLadd1 = $sSQLadd1 . 'visa40idtipologia=' . $btipologia . ' AND ';
	}
	if ($bsubtipologia != '') {
		$sSQLadd1 = $sSQLadd1 . 'visa40idsubtipo=' . $bsubtipologia . ' AND ';
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
	$sTitulos = 'Id, Convocatoria, Tercero, Estado, Fechainsc';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa40id, T2.visa35nombre, T3.unad11razonsocial AS C3_nombre, TB.visa40estado, TB.visa40fechainsc, TB.visa40idconvocatoria, TB.visa40idtercero, T3.unad11tipodoc AS C3_td, T3.unad11doc AS C3_doc';
	$sConsulta = 'FROM visa40inscripcion AS TB, visa35convocatoria AS T2, unad11terceros AS T3 
	WHERE ' . $sSQLadd1 . ' TB.visa40id>0 AND TB.visa40idconvocatoria=T2.visa35id AND TB.visa40idtercero=T3.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa40idconvocatoria, TB.visa40idtercero';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2940: ' . $sSQLContador . '');
		}
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
	$sErrConsulta = '<input id="consulta_2940" name="consulta_2940" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2940" name="titulos_2940" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2940: ' . $sSQL . '');
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
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Termina la consulta 2940');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa40idconvocatoria'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['visa40idtercero'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa40estado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa40fechainsc'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2940', $registros, $lineastabla, $pagina, 'paginarf2940()');
	$res = $res . html_lpp('lppf2940', $lineastabla, 'paginarf2940()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['visa40estado']) {
			case 7:
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_visa40idconvocatoria = $sPrefijo . cadena_notildes($filadet['visa35nombre']) . $sSufijo;
		$et_visa40idtercero_doc = '';
		$et_visa40idtercero_nombre = '';
		if ($filadet['visa40idtercero'] != 0) {
			$et_visa40idtercero_doc = $sPrefijo . $filadet['C3_td'] . ' ' . $filadet['C3_doc'] . $sSufijo;
			$et_visa40idtercero_nombre = $sPrefijo . cadena_notildes($filadet['C3_nombre']) . $sSufijo;
		}
		$et_visa40estado = $ETI['msg_abierto'];
		if ($filadet['visa40estado'] == 7) {
			$et_visa40estado = $ETI['msg_cerrado'];
		}
		$et_visa40fechainsc = '';
		if ($filadet['visa40fechainsc'] != 0) {
			$et_visa40fechainsc = $sPrefijo . fecha_desdenumero($filadet['visa40fechainsc']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2940(' . $filadet['visa40id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa40idconvocatoria . '</td>';
		$res = $res . '<td>' . $et_visa40idtercero_doc . '</td>';
		$res = $res . '<td>' . $et_visa40idtercero_nombre . '</td>';
		$res = $res . '<td>' . $et_visa40estado . '</td>';
		$res = $res . '<td>' . $et_visa40fechainsc . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2940_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2940_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2940detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2940_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['visa40idtercero_td'] = $APP->tipo_doc;
	$DATA['visa40idtercero_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'visa40idconvocatoria=' . $DATA['visa40idconvocatoria'] . ' AND visa40idtercero="' . $DATA['visa40idtercero'] . '"';
	} else {
		$sSQLcondi = 'visa40id=' . $DATA['visa40id'] . '';
	}
	$sSQL = 'SELECT * FROM visa40inscripcion WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['visa40idconvocatoria'] = $fila['visa40idconvocatoria'];
		$DATA['visa40idtercero'] = $fila['visa40idtercero'];
		$DATA['visa40id'] = $fila['visa40id'];
		$DATA['visa40estado'] = $fila['visa40estado'];
		$DATA['visa40idperiodo'] = $fila['visa40idperiodo'];
		$DATA['visa40idescuela'] = $fila['visa40idescuela'];
		$DATA['visa40idprograma'] = $fila['visa40idprograma'];
		$DATA['visa40idzona'] = $fila['visa40idzona'];
		$DATA['visa40idcentro'] = $fila['visa40idcentro'];
		$DATA['visa40fechainsc'] = $fila['visa40fechainsc'];
		$DATA['visa40fechaadmision'] = $fila['visa40fechaadmision'];
		$DATA['visa40numcupo'] = $fila['visa40numcupo'];
		$DATA['visa40idtipologia'] = $fila['visa40idtipologia'];
		$DATA['visa40idsubtipo'] = $fila['visa40idsubtipo'];
		$DATA['visa40idminuta'] = $fila['visa40idminuta'];
		$DATA['visa40idresolucion'] = $fila['visa40idresolucion'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2940'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2940_Cerrar($visa40id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f2940_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2940)
{
	$iCodModuloAudita = 2940;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2940 = 'lg/lg_2940_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2940)) {
		$mensajes_2940 = 'lg/lg_2940_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2940;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['visa40idconvocatoria']) == 0) {
		$DATA['visa40idconvocatoria'] = 0;
	}
	if (isset($DATA['visa40idtercero']) == 0) {
		$DATA['visa40idtercero'] = 0;
	}
	if (isset($DATA['visa40idtercero_td']) == 0) {
		$DATA['visa40idtercero_td'] = 'CC';
	}
	if (isset($DATA['visa40idtercero_doc']) == 0) {
		$DATA['visa40idtercero_doc'] = '';
	}
	if (isset($DATA['visa40id']) == 0) {
		$DATA['visa40id'] = '';
	}
	if (isset($DATA['visa40estado']) == 0) {
		$DATA['visa40estado'] = '';
	}
	if (isset($DATA['visa40idperiodo']) == 0) {
		$DATA['visa40idperiodo'] = 0;
	}
	if (isset($DATA['visa40idescuela']) == 0) {
		$DATA['visa40idescuela'] = 0;
	}
	if (isset($DATA['visa40idprograma']) == 0) {
		$DATA['visa40idprograma'] = 0;
	}
	if (isset($DATA['visa40idzona']) == 0) {
		$DATA['visa40idzona'] = 0;
	}
	if (isset($DATA['visa40idcentro']) == 0) {
		$DATA['visa40idcentro'] = 0;
	}
	if (isset($DATA['visa40fechainsc']) == 0) {
		$DATA['visa40fechainsc'] = 0;
	}
	if (isset($DATA['visa40fechaadmision']) == 0) {
		$DATA['visa40fechaadmision'] = 0;
	}
	if (isset($DATA['visa40numcupo']) == 0) {
		$DATA['visa40numcupo'] = 0;
	}
	if (isset($DATA['visa40idtipologia']) == 0) {
		$DATA['visa40idtipologia'] = 0;
	}
	if (isset($DATA['visa40idsubtipo']) == 0) {
		$DATA['visa40idsubtipo'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['visa40idconvocatoria'] = numeros_validar($DATA['visa40idconvocatoria']);
	$DATA['visa40idtercero'] = numeros_validar($DATA['visa40idtercero']);
	$DATA['visa40idtercero_td'] = cadena_Validar($DATA['visa40idtercero_td']);
	$DATA['visa40idtercero_doc'] = cadena_Validar($DATA['visa40idtercero_doc']);
	$DATA['visa40idperiodo'] = numeros_validar($DATA['visa40idperiodo']);
	$DATA['visa40idescuela'] = numeros_validar($DATA['visa40idescuela']);
	$DATA['visa40idprograma'] = numeros_validar($DATA['visa40idprograma']);
	$DATA['visa40idzona'] = numeros_validar($DATA['visa40idzona']);
	$DATA['visa40idcentro'] = numeros_validar($DATA['visa40idcentro']);
	$DATA['visa40fechainsc'] = numeros_validar($DATA['visa40fechainsc']);
	$DATA['visa40fechaadmision'] = numeros_validar($DATA['visa40fechaadmision']);
	$DATA['visa40idtipologia'] = numeros_validar($DATA['visa40idtipologia']);
	$DATA['visa40idsubtipo'] = numeros_validar($DATA['visa40idsubtipo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	*/
	if ($DATA['visa40estado'] == '') {
		$DATA['visa40estado'] = 0;
	}
		/*
	if ($DATA['visa40idperiodo'] == '') {
		$DATA['visa40idperiodo'] = 0;
	}
	if ($DATA['visa40idescuela'] == '') {
		$DATA['visa40idescuela'] = 0;
	}
	if ($DATA['visa40idprograma'] == '') {
		$DATA['visa40idprograma'] = 0;
	}
	if ($DATA['visa40idzona'] == '') {
		$DATA['visa40idzona'] = 0;
	}
	if ($DATA['visa40idcentro'] == '') {
		$DATA['visa40idcentro'] = 0;
	}
	if ($DATA['visa40fechainsc'] == '') {
		$DATA['visa40fechainsc'] = 0;
	}
	if ($DATA['visa40fechaadmision'] == '') {
		$DATA['visa40fechaadmision'] = 0;
	}
	if ($DATA['visa40numcupo'] == '') {
		$DATA['visa40numcupo'] = 0;
	}
	if ($DATA['visa40idtipologia'] == '') {
		$DATA['visa40idtipologia'] = 0;
	}
	if ($DATA['visa40idsubtipo'] == '') {
		$DATA['visa40idsubtipo'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['visa40idsubtipo'] == '') {
		$sError = $ERR['visa40idsubtipo'] . $sSepara . $sError;
	}
	if ($DATA['visa40idtipologia'] == '') {
		$sError = $ERR['visa40idtipologia'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa40fechaadmision'])) {
		//$DATA['visa40fechaadmision'] = fecha_DiaMod();
		$sError = $ERR['visa40fechaadmision'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa40fechainsc'])) {
		//$DATA['visa40fechainsc'] = fecha_DiaMod();
		$sError = $ERR['visa40fechainsc'] . $sSepara . $sError;
	}
	if ($DATA['visa40idcentro'] == '') {
		$sError = $ERR['visa40idcentro'] . $sSepara . $sError;
	}
	if ($DATA['visa40idzona'] == '') {
		$sError = $ERR['visa40idzona'] . $sSepara . $sError;
	}
	if ($DATA['visa40idprograma'] == '') {
		$sError = $ERR['visa40idprograma'] . $sSepara . $sError;
	}
	if ($DATA['visa40idescuela'] == '') {
		$sError = $ERR['visa40idescuela'] . $sSepara . $sError;
	}
	if ($DATA['visa40idperiodo'] == '') {
		$sError = $ERR['visa40idperiodo'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['visa40idtercero'] == 0) {
		$sError = $ERR['visa40idtercero'];
	}
	if ($DATA['visa40idconvocatoria'] == '') {
		$sError = $ERR['visa40idconvocatoria'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		if ($DATA['visa40idtercero_doc'] != '') {
			$sError = tabla_terceros_existe($DATA['visa40idtercero_td'], $DATA['visa40idtercero_doc'], $objDB, 'El tercero Tercero ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['visa40idtercero'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT 1 FROM visa40inscripcion WHERE visa40idconvocatoria=' . $DATA['visa40idconvocatoria'] . ' AND visa40idtercero="' . $DATA['visa40idtercero'] . '"';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$sError = $ERR['existe'];
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['2'] . ' [Mod ' . $iCodModulo . ']';
				}
			}
		} else {
			list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'] . ' [Mod ' . $iCodModulo . ']';
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['visa40id'] = tabla_consecutivo('visa40inscripcion', 'visa40id', '', $objDB);
			if ($DATA['visa40id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['visa40estado'] = 0;
			$visa40fechainsc = 0; //fecha_DiaMod();
			$visa40fechaadmision = 0; //fecha_DiaMod();
			$DATA['visa40numcupo'] = 0;
			$DATA['visa40idminuta'] = 0;
			$DATA['visa40idresolucion'] = 0;
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2940 = 'visa40idconvocatoria, visa40idtercero, visa40id, visa40estado, visa40idperiodo, 
			visa40idescuela, visa40idprograma, visa40idzona, visa40idcentro, visa40fechainsc, 
			visa40fechaadmision, visa40numcupo, visa40idtipologia, visa40idsubtipo, visa40idminuta, 
			visa40idresolucion';
			$sValores2940 = '' . $DATA['visa40idconvocatoria'] . ', ' . $DATA['visa40idtercero'] . ', ' . $DATA['visa40id'] . ', ' . $DATA['visa40estado'] . ', ' . $DATA['visa40idperiodo'] . ', 
			' . $DATA['visa40idescuela'] . ', ' . $DATA['visa40idprograma'] . ', ' . $DATA['visa40idzona'] . ', ' . $DATA['visa40idcentro'] . ', ' . $DATA['visa40fechainsc'] . ', 
			' . $DATA['visa40fechaadmision'] . ', ' . $DATA['visa40numcupo'] . ', ' . $DATA['visa40idtipologia'] . ', ' . $DATA['visa40idsubtipo'] . ', ' . $DATA['visa40idminuta'] . ', 
			' . $DATA['visa40idresolucion'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa40inscripcion (' . $sCampos2940 . ') VALUES (' . cadena_codificar($sValores2940) . ');';
				$sDetalle = $sCampos2940 . '[' . cadena_codificar($sValores2940) . ']';
			} else {
				$sSQL = 'INSERT INTO visa40inscripcion (' . $sCampos2940 . ') VALUES (' . $sValores2940 . ');';
				$sDetalle = $sCampos2940 . '[' . $sValores2940 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'visa40idperiodo';
			$sCampo[2] = 'visa40idescuela';
			$sCampo[3] = 'visa40idprograma';
			$sCampo[4] = 'visa40idzona';
			$sCampo[5] = 'visa40idcentro';
			$sCampo[6] = 'visa40fechainsc';
			$sCampo[7] = 'visa40fechaadmision';
			$sCampo[8] = 'visa40idtipologia';
			$sCampo[9] = 'visa40idsubtipo';
			$sDato[1] = $DATA['visa40idperiodo'];
			$sDato[2] = $DATA['visa40idescuela'];
			$sDato[3] = $DATA['visa40idprograma'];
			$sDato[4] = $DATA['visa40idzona'];
			$sDato[5] = $DATA['visa40idcentro'];
			$sDato[6] = $DATA['visa40fechainsc'];
			$sDato[7] = $DATA['visa40fechaadmision'];
			$sDato[8] = $DATA['visa40idtipologia'];
			$sDato[9] = $DATA['visa40idsubtipo'];
			$iNumCamposMod = 9;
			$sWhere = 'visa40id=' . $DATA['visa40id'] . '';
			$sSQL = 'SELECT * FROM visa40inscripcion WHERE ' . $sWhere;
			$sDatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $iNumCamposMod; $k++) {
						if (isset($filabase[$sCampo[$k]]) == 0) {
							$sDebug = $sDebug . log_debug(' FALLA CODIGO: Falta el campo ' . $k . ' ' . $sCampo[$k] . '');
						}
					}
					$bPrimera = false;
				}
				$sSepara = '';
				for ($k = 1; $k <= $iNumCamposMod; $k++) {
					if ($filabase[$sCampo[$k]] != $sDato[$k]) {
						$sDatos = $sDatos . $sSepara . $sCampo[$k] . '="' . $sDato[$k] . '"';
						$bPasa = true;
						$sSepara = ', ';
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sDetalle = cadena_codificar($sDatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE visa40inscripcion SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE visa40inscripcion SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2940 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2940] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['visa40id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['visa40id'], $sDetalle, $objDB);
				}
				//Acciones en el insertar.
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
		$sDebug = $sDebug . log_debug(' InfoDepura');
	}
	*/
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2940_db_Eliminar($visa40id, $objDB, $bDebug = false)
{
	$iCodModulo = 2940;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2940 = 'lg/lg_2940_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2940)) {
		$mensajes_2940 = 'lg/lg_2940_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2940;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$visa40id = numeros_validar($visa40id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM visa40inscripcion WHERE visa40id=' . $visa40id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $visa40id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM visa43inscripdocs WHERE visa43idinscripcion=' . $filabase['visa40id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Anexos creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM visa44anotaciones WHERE visa44idinscripcion=' . $filabase['visa40id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Anotaciones creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM visa45convpruebares WHERE visa45idinscripcion=' . $filabase['visa40id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Resultados pruebas creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		if (isset($idTercero) == 0) {
			$idTercero = $_SESSION['unad_id_tercero'];
		}
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2940';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['visa40id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM visa43inscripdocs WHERE visa43idinscripcion=' . $filabase['visa40id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM visa44anotaciones WHERE visa44idinscripcion=' . $filabase['visa40id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM visa45convpruebares WHERE visa45idinscripcion=' . $filabase['visa40id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'visa40id=' . $visa40id . '';
		//$sWhere = 'visa40idtercero="' . $filabase['visa40idtercero'] . '" AND visa40idconvocatoria=' . $filabase['visa40idconvocatoria'] . '';
		$sSQL = 'DELETE FROM visa40inscripcion WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa40id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

function f2940_CambiaEstado($visa40id, $iEstadoOrigen, $iEstadoDestino, $sDetalle, $idUsuario, $objDB, $bDebug = false)
{
	$iCodModulo = 2940;
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$bNotificar = false;
	$sSQL = 'SELECT visa40estado FROM visa40inscripcion WHERE visa40id=' . $visa40id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		if ($filabase['visa40estado'] != $iEstadoOrigen) {
			$sError = 'El estado de origen no coincide [' . $filabase['visa40estado'] . '].';
		}
	} else {
		$sError = 'No se ha encontrado el registro solicitado [Ref ' . $visa40id . ']';
	}
	if ($sError == '') {
		$sInfoCambio = 'Cambia el estado a ' . $iEstadoDestino;
		$sDatosAdd = '';
		switch ($iEstadoDestino) {
			case 0:
				break;
		}
	}
	if ($sError == '') {
		//Guardar el historial del cambio...
	}
	if ($sError == '') {
		$sSQL = 'UPDATE visa40inscripcion SET visa40estado=' . $iEstadoDestino . '' . $sDatosAdd . ' WHERE visa40id=' . $visa40id . '';
		$result = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['visa40id'], $sInfoCambio, $objDB);
	}
	if ($bNotificar) {
		list($sError, $sDebugN, $sMensaje) = f2940_Notificar($visa40id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugN;
	}
	return array($sError, $sDebug, $sMensaje);
}

function f2940_Notificar($visa40id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$iHoy = fecha_DiaMod();
	$idInteresado = 0;
	$sSQL = 'SELECT * 
	FROM visa40inscripcion AS TB
	WHERE TB.visa40id=' . $visa40id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		$idInteresado = $filabase['id_interesado'];
		$visa40estado = $filabase['visa40estado'];
	} else {
		$sError = 'No se ha encontrado el registro solicitado [Ref ' . $visa40id . ']';
	}
	if ($sError == '') {
		$sTituloMensaje = 'Notificación de ... ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '';
		$sCuerpo = 'Estimado usuario:<br><br>';
		switch ($visa40estado) {
			case 0:
				break;
		}
	}
	if ($sError == '') {
		list($sCorreoUsuario, $sErrorN, $sDebugM) = AUREA_CorreoNotifica($idSolicitante, $objDB, $bDebug);
		if ($sCorreoUsuario == '') {
			$sError = 'El usuario no registra correo de notificaciones.';
		}
	}
	if ($sError == '') {
		$sCuerpo = $sCuerpo . AUREA_HTML_NoResponderSII();
		$sCorreoCopia = '';
		$sCuerpo = AUREA_HTML_EncabezadoCorreo($sTituloMensaje) . $sCuerpo . AUREA_HTML_PieCorreo();
		$sMes = date('Ym');
		$sTabla = 'aure01login' . $sMes;
		list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
		$objMail = new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto = cadena_codificar($sTituloMensaje);
		$sMensaje = 'Se notifica al correo ' . $sCorreoUsuario;
		$objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario);
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
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

