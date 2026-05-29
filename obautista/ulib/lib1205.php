<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5b lunes, 23 de marzo de 2026
--- 1205 masi05mensajes
*/
/** Archivo lib1205.php.
 * Libreria 1205 masi05mensajes.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date lunes, 23 de marzo de 2026
 */
function f1205_NombreTabla($iComplemento, $objDB) {
	$sError = '';
	$iBloque = numeros_validar($iComplemento);
	$sTabla1205 = 'masi05mensajes_' . $iBloque;
	if (!$objDB->bexistetabla($sTabla1205)) {
		$sError = 'No ha sido posible determinar el origen de los datos.';
	}
	return array($sTabla1205, $sError);
}
function f1205_HTMLComboV2_masi05idproceso($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('masi05idproceso', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.masi72id AS id, TB.masi72nombre AS nombre 
	FROM masi72proceso AS TB
	WHERE TB.masi72id>0
	ORDER BY TB.masi72nombre';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1205, $sIdioma
	return $res;
}
function f1205_HTMLComboV2_masi05centro($objDB, $objCombos, $valor, $vrmasi05zona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('masi05centro', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrmasi05zona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrmasi05zona . ' 
		ORDER BY TB.unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1205, $sIdioma
	return $res;
}
function f1205_HTMLComboV2_masi05programa($objDB, $objCombos, $valor, $vrmasi05escuela)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('masi05programa', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrmasi05escuela != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [" & TB.core09codigo & "]") AS nombre 
		FROM core09programa AS TB
		WHERE TB.core09idescuela=' . $vrmasi05escuela . ' 
		ORDER BY TB.core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1205, $sIdioma
	return $res;
}
function f1205_Combomasi05centro($aParametros)
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
	$html_masi05centro = f1205_HTMLComboV2_masi05centro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_masi05centro', 'innerHTML', $html_masi05centro);
	//$objResponse->call('$("#masi05centro").chosen()');
	return $objResponse;
}
function f1205_Combomasi05programa($aParametros)
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
	$html_masi05programa = f1205_HTMLComboV2_masi05programa($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_masi05programa', 'innerHTML', $html_masi05programa);
	$objResponse->call('$("#masi05programa").chosen()');
	return $objResponse;
}
function f1205_HTMLComboV2_bcentro($objDB, $objCombos, $valor, $vrbzona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('bcentro', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf1205()';
	$sSQL = '';
	if ((int)$vrbzona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrbzona . ' 
		ORDER BY TB.unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1205, $sIdioma
	return $res;
}
function f1205_Combobcentro($aParametros)
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
	$html_bcentro = f1205_HTMLComboV2_bcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bcentro', 'innerHTML', $html_bcentro);
	//$objResponse->call('$("#bcentro").chosen()');
	$objResponse->call('paginarf1205()');
	return $objResponse;
}
function f1205_HTMLComboV2_bprograma($objDB, $objCombos, $valor, $vrbescuela)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('bprograma', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf1205()';
	$sSQL = '';
	if ((int)$vrbescuela != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core09id AS id, TB.core09nombre AS nombre 
		FROM core09programa AS TB
		WHERE TB.core09idescuela=' . $vrbescuela . ' 
		ORDER BY TB.core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 1205, $sIdioma
	return $res;
}
function f1205_Combobprograma($aParametros)
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
	$html_bprograma = f1205_HTMLComboV2_bprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bprograma', 'innerHTML', $html_bprograma);
	//$objResponse->call('$("#bprograma").chosen()');
	$objResponse->call('paginarf1205()');
	return $objResponse;
}
function f1205_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$masi05idproceso = numeros_validar($datos[1]);
	if ($masi05idproceso == '') {
		$bHayLlave = false;
	}
	$masi05consec = numeros_validar($datos[2]);
	if ($masi05consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		list($sTabla1205, $sErrorT) = f1205_NombreTabla($datos[97], $objDB);
		if (!$objDB->bexistetabla($sTabla)) {
			$bHayLlave = false;
			$sLeyenda = 'No ha sido posible determinar el origen de los datos.';
		}
	}
	if ($bHayLlave) {
		$sSQL = 'SELECT 1 FROM ' . $sTabla1205 . ' WHERE masi05idproceso=' . $masi05idproceso . ' AND masi05consec=' . $masi05consec . '';
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
function f1205_Busquedas($aParametros)
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
	$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1205)) {
		$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1205;
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
	$sTituloModulo = $ETI['titulo_1205'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'masi05idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['masi05idusuario_busca']) == 0) {
				$ETI['masi05idusuario_busca'] = 'Busqueda de Usuario';
			}
			$sTitulo = $ETI['masi05idusuario_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(1205);
			break;
		case 'masi08idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['masi08idtercero_busca']) == 0) {
				$ETI['masi08idtercero_busca'] = 'Busqueda de Tercero';
			}
			$sTitulo = $ETI['masi08idtercero_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(1205);
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
function f1205_HtmlBusqueda($aParametros)
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
		case 'masi05idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'masi08idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f1205_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_1200 = 'lg/lg_1200_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1200)) {
		$mensajes_1200 = 'lg/lg_1200_es.php';
	}
	require $mensajes_1200;
	*/
	$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1205)) {
		$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_es.php';
	}
	require $mensajes_1205;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[97]) == 0) {
		$aParametros[97] = '';
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
	$iNumVariables = 113;
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
	$basunto = cadena_Validar(trim($aParametros[103]));
	$bcuerpo = cadena_Validar(trim($aParametros[104]));
	$bfechainicia = numeros_validar($aParametros[105]);
	$bfechafinal = numeros_validar($aParametros[106]);
	$bunidadfunc = numeros_validar($aParametros[107]);
	$bzona = numeros_validar($aParametros[108]);
	$bcentro = numeros_validar($aParametros[109]);
	$bescuela = numeros_validar($aParametros[110]);
	$bprograma = numeros_validar($aParametros[111]);
	$bcurso = cadena_Validar(trim($aParametros[112]));
	$bproceso = numeros_validar($aParametros[113]);
	/* 
0 - Ninguno
2 - Funcionarios
3 - Contratistas
11 - Aspirantes
12 - Estudiantes
13 - Estudiantes ausentes
17 - Egresados
2209 - Estudiantes del programa
2306 - Acompañamiento académico
2307 - Seguimiento académico
2741 - Postulados a grados
12229 - Convocados
	*/
	$bMultiProceso = false;
	list($sTabla1205, $sLeyenda) = f1205_NombreTabla($aParametros[97], $objDB);
	if ($sLeyenda == '') {
		if ((int)$bproceso == 0) {
			$sLeyenda = 'No se ha definido el proceso a gestionar.';
		}
	}
	$sBotones = '<input id="paginaf1205" name="paginaf1205" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf1205" name="lppf1205" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
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
	$iPiel = iDefinirPiel($APP, 2);
	$amasi05estado = array('');
	$sSQL = 'SELECT unad96id, unad96nombre, unad96etiqueta FROM unad96estado WHERE unad96idmodulo=1205';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$et_estado = cadena_notildes($fila['unad96nombre']);
		if ($sIdioma != 'es') {
			$et_estado = Etiqueta_Valor(1205, $fila['unad96etiqueta'], $sIdioma, $objDB);
		}
		$amasi05estado[$fila['unad96id']] = $et_estado;
	}
	/*
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sTablaAdd = '';
	if (fecha_NumValido($bfechainicia)) {
		$sSQLadd1 = $sSQLadd1 . 'masi05fecha>=' . $bfechainicia . ' AND ';
	}
	if (fecha_NumValido($bfechafinal)) {
		$sSQLadd1 = $sSQLadd1 . 'masi05fecha<=' . $bfechafinal . ' AND ';
	}
	if ($bunidadfunc != '') {
		$sSQLadd1 = $sSQLadd1 . 'masi05unidadfunc=' . $bunidadfunc . ' AND ';
	}
	if ($bcentro != '') {
		$sSQLadd1 = $sSQLadd1 . 'masi05centro=' . $bcentro . ' AND ';
	} else {
		if ($bzona != '') {
			$sSQLadd1 = $sSQLadd1 . 'masi05zona=' . $bzona . ' AND ';
		}
	}
	if ($bprograma != '') {
		$sSQLadd1 = $sSQLadd1 . 'masi05programa=' . $bprograma . ' AND ';
	} else {
		if ($bescuela != '') {
			$sSQLadd1 = $sSQLadd1 . 'masi05escuela=' . $bescuela . ' AND ';
		}
	}
	if ($bcurso != '') {
		$sTablaAdd = ', unad40curso AS T40';
		$sSQLadd = $sSQLadd . ' AND TB.masi05curso=T40.unad40id AND T40.unad40titulo LIKE "%' . $bcurso . '%"';
	}
	if ($bproceso != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.masi05idproceso=' . $bproceso . ' AND ';
	}
	if ($basunto != '') {
		$sBase = mb_strtoupper($basunto);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd1 = $sSQLadd1 . 'TB.masi05asunto LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	if ($bcuerpo != '') {
		$sBase = mb_strtoupper($bcuerpo);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd1 = $sSQLadd1 . 'TB.masi05cuerpo LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Consec, Id, Asunto, Estado, Fecha, Relacion2, Relacion3, Proceso';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.masi05consec, TB.masi05id, TB.masi05asunto, TB.masi05estado, TB.masi05fecha, T14.masi72nombre, TB.masi05idproceso';
	$sConsulta = 'FROM ' . $sTabla1205 . ' AS TB, masi72proceso AS T14' . $sTablaAdd . ' 
	WHERE ' . $sSQLadd1 . ' TB.masi05id>0 AND TB.masi05idproceso=T14.masi72id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.masi05idproceso, TB.masi05consec DESC';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 1205: ' . $sSQLContador . '');
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
	$sErrConsulta = '<input id="consulta_1205" name="consulta_1205" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_1205" name="titulos_1205" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 1205: ' . $sSQL . '');
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
		$sDebug = $sDebug . log_debug('Termina la consulta 1205');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['masi05consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi05asunto'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi05estado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['masi05fecha'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf1205', $registros, $lineastabla, $pagina, 'paginarf1205()');
	$res = $res . html_lpp('lppf1205', $lineastabla, 'paginarf1205()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	$masi05idproceso = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($masi05idproceso != $filadet['masi05idproceso']) {
			$masi05idproceso = $filadet['masi05idproceso'];
			if ($bMultiProceso) {
				$et_masi05idproceso = cadena_notildes($filadet['masi72nombre']);
				$res = $res . '<tr class="fondoazul">';
				$res = $res . '<td colspan="5" align="center">' . $ETI['masi05idproceso'] . ' <b>' . $et_masi05idproceso . '</b></td>';
				$res = $res . '</tr>';
			}
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['masi05estado']) {
			case 7:
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_masi05consec = $sPrefijo . $filadet['masi05consec'] . $sSufijo;
		$et_masi05asunto = $sPrefijo . cadena_notildes($filadet['masi05asunto']) . $sSufijo;
		$et_masi05estado = $amasi05estado[$filadet['masi05estado']];
		$et_masi05fecha = '';
		if ($filadet['masi05fecha'] != 0) {
			$et_masi05fecha = $sPrefijo . fecha_desdenumero($filadet['masi05fecha']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf1205(' . $filadet['masi05id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_masi05consec . '</td>';
		$res = $res . '<td>' . $et_masi05asunto . '</td>';
		$res = $res . '<td>' . $et_masi05estado . '</td>';
		$res = $res . '<td>' . $et_masi05fecha . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f1205_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f1205_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1205detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1205_Cerrar($masi05id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f1205_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 1205)
{
	$iCodModuloAudita = 1205;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1205)) {
		$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1205;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['masi05idproceso']) == 0) {
		$DATA['masi05idproceso'] = 0;
	}
	if (isset($DATA['masi05consec']) == 0) {
		$DATA['masi05consec'] = '';
	}
	if (isset($DATA['masi05id']) == 0) {
		$DATA['masi05id'] = '';
	}
	if (isset($DATA['masi05estado']) == 0) {
		$DATA['masi05estado'] = '';
	}
	if (isset($DATA['masi05asunto']) == 0) {
		$DATA['masi05asunto'] = '';
	}
	if (isset($DATA['masi05cuerpo']) == 0) {
		$DATA['masi05cuerpo'] = '';
	}
	if (isset($DATA['masi05admiterpta']) == 0) {
		$DATA['masi05admiterpta'] = 0;
	}
	if (isset($DATA['masi05correorpta']) == 0) {
		$DATA['masi05correorpta'] = '';
	}
	if (isset($DATA['masi05firma']) == 0) {
		$DATA['masi05firma'] = 0;
	}
	if (isset($DATA['masi05idusuario']) == 0) {
		$DATA['masi05idusuario'] = 0;
	}
	if (isset($DATA['masi05idusuario_td']) == 0) {
		$DATA['masi05idusuario_td'] = 'CC';
	}
	if (isset($DATA['masi05idusuario_doc']) == 0) {
		$DATA['masi05idusuario_doc'] = '';
	}
	if (isset($DATA['masi05fecha']) == 0) {
		$DATA['masi05fecha'] = 0;
	}
	if (isset($DATA['masi05hora']) == 0) {
		$DATA['masi05hora'] = 0;
	}
	if (isset($DATA['masi05min']) == 0) {
		$DATA['masi05min'] = 0;
	}
	if (isset($DATA['masi05unidadfunc']) == 0) {
		$DATA['masi05unidadfunc'] = 0;
	}
	if (isset($DATA['masi05zona']) == 0) {
		$DATA['masi05zona'] = 0;
	}
	if (isset($DATA['masi05centro']) == 0) {
		$DATA['masi05centro'] = 0;
	}
	if (isset($DATA['masi05escuela']) == 0) {
		$DATA['masi05escuela'] = 0;
	}
	if (isset($DATA['masi05programa']) == 0) {
		$DATA['masi05programa'] = 0;
	}
	if (isset($DATA['masi05idperiodo']) == 0) {
		$DATA['masi05idperiodo'] = 0;
	}
	if (isset($DATA['masi05curso']) == 0) {
		$DATA['masi05curso'] = 0;
	}
	if (isset($DATA['masi05docente']) == 0) {
		$DATA['masi05docente'] = 0;
	}
	if (isset($DATA['masi05total_usuarios']) == 0) {
		$DATA['masi05total_usuarios'] = 0;
	}
	if (isset($DATA['masi05total_envios']) == 0) {
		$DATA['masi05total_envios'] = 0;
	}
	if (isset($DATA['masi05tiponotifica']) == 0) {
		$DATA['masi05tiponotifica'] = 0;
	}
	if (isset($DATA['masi05periodicidad']) == 0) {
		$DATA['masi05periodicidad'] = 0;
	}
	if (isset($DATA['masi05idrelacion']) == 0) {
		$DATA['masi05idrelacion'] = 0;
	}
	if (isset($DATA['masi05idrelacion2']) == 0) {
		$DATA['masi05idrelacion2'] = 0;
	}
	if (isset($DATA['masi05idrelacion3']) == 0) {
		$DATA['masi05idrelacion3'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['masi05consec'] = numeros_validar($DATA['masi05consec']);
	$DATA['masi05asunto'] = cadena_Validar(trim($DATA['masi05asunto']));
	$DATA['masi05cuerpo'] = cadena_Validar(trim($DATA['masi05cuerpo']));
	$DATA['masi05admiterpta'] = numeros_validar($DATA['masi05admiterpta']);
	$DATA['masi05correorpta'] = cadena_Validar(trim($DATA['masi05correorpta']));
	$DATA['masi05firma'] = numeros_validar($DATA['masi05firma']);
	$DATA['masi05fecha'] = numeros_validar($DATA['masi05fecha']);
	$DATA['masi05hora'] = numeros_validar($DATA['masi05hora']);
	$DATA['masi05min'] = numeros_validar($DATA['masi05min']);
	$DATA['masi05unidadfunc'] = numeros_validar($DATA['masi05unidadfunc']);
	$DATA['masi05zona'] = numeros_validar($DATA['masi05zona']);
	$DATA['masi05centro'] = numeros_validar($DATA['masi05centro']);
	$DATA['masi05escuela'] = numeros_validar($DATA['masi05escuela']);
	$DATA['masi05programa'] = numeros_validar($DATA['masi05programa']);
	$DATA['masi05periodicidad'] = numeros_validar($DATA['masi05periodicidad']);
	$DATA['masi05idrelacion'] = numeros_validar($DATA['masi05idrelacion']);
	$DATA['masi05idrelacion2'] = numeros_validar($DATA['masi05idrelacion2']);
	$DATA['masi05idrelacion3'] = numeros_validar($DATA['masi05idrelacion3']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	*/
	if ($DATA['masi05estado'] == '') {
		$DATA['masi05estado'] = 0;
	}
		/*
	if ($DATA['masi05admiterpta'] == '') {
		$DATA['masi05admiterpta'] = 0;
	}
	if ($DATA['masi05firma'] == '') {
		$DATA['masi05firma'] = 0;
	}
	if ($DATA['masi05fecha'] == '') {
		$DATA['masi05fecha'] = 0;
	}
	*/
	if ($DATA['masi05hora'] == '') {
		$DATA['masi05hora'] = 0;
	}
	if ($DATA['masi05min'] == '') {
		$DATA['masi05min'] = 0;
	}
	/*
	if ($DATA['masi05unidadfunc'] == '') {
		$DATA['masi05unidadfunc'] = 0;
	}
	if ($DATA['masi05zona'] == '') {
		$DATA['masi05zona'] = 0;
	}
	if ($DATA['masi05centro'] == '') {
		$DATA['masi05centro'] = 0;
	}
	if ($DATA['masi05escuela'] == '') {
		$DATA['masi05escuela'] = 0;
	}
	if ($DATA['masi05programa'] == '') {
		$DATA['masi05programa'] = 0;
	}
	if ($DATA['masi05idperiodo'] == '') {
		$DATA['masi05idperiodo'] = 0;
	}
	if ($DATA['masi05curso'] == '') {
		$DATA['masi05curso'] = 0;
	}
	if ($DATA['masi05docente'] == '') {
		$DATA['masi05docente'] = 0;
	}
	if ($DATA['masi05total_usuarios'] == '') {
		$DATA['masi05total_usuarios'] = 0;
	}
	if ($DATA['masi05total_envios'] == '') {
		$DATA['masi05total_envios'] = 0;
	}
	if ($DATA['masi05tiponotifica'] == '') {
		$DATA['masi05tiponotifica'] = 0;
	}
	if ($DATA['masi05periodicidad'] == '') {
		$DATA['masi05periodicidad'] = 0;
	}
	if ($DATA['masi05idrelacion'] == '') {
		$DATA['masi05idrelacion'] = 0;
	}
	if ($DATA['masi05idrelacion2'] == '') {
		$DATA['masi05idrelacion2'] = 0;
	}
	if ($DATA['masi05idrelacion3'] == '') {
		$DATA['masi05idrelacion3'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['masi05periodicidad'] == '') {
		$sError = $ERR['masi05periodicidad'] . $sSepara . $sError;
	}
	if ($DATA['masi05programa'] == '') {
		$DATA['masi05programa'] = 0;
		//$sError = $ERR['masi05programa'] . $sSepara . $sError;
	}
	if ($DATA['masi05escuela'] == '') {
		$DATA['masi05escuela'] = 0;
		//$sError = $ERR['masi05escuela'] . $sSepara . $sError;
	}
	if ($DATA['masi05centro'] == '') {
		$DATA['masi05centro'] = 0;
		//$sError = $ERR['masi05centro'] . $sSepara . $sError;
	}
	if ($DATA['masi05zona'] == '') {
		$DATA['masi05zona'] = 0;
		//$sError = $ERR['masi05zona'] . $sSepara . $sError;
	}
	if ($DATA['masi05unidadfunc'] == '') {
		$DATA['masi05unidadfunc'] = 0;
		//$sError = $ERR['masi05unidadfunc'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['masi05fecha'])) {
		//$DATA['masi05fecha'] = fecha_DiaMod();
		$sError = $ERR['masi05fecha'] . $sSepara . $sError;
	}
	if ($DATA['masi05idusuario'] == 0) {
		$DATA['masi05idusuario'] = $_SESSION['unad_id_tercero'];
		//$sError = $ERR['masi05idusuario'] . $sSepara . $sError;
	}
	if ($DATA['masi05firma'] == '') {
		$sError = $ERR['masi05firma'] . $sSepara . $sError;
	}
	if (($DATA['masi05correorpta'] == '') && ($DATA['masi05admiterpta'] == 1)) {
		$sError = $ERR['masi05correorpta'] . $sSepara . $sError;
	}
	if ($DATA['masi05admiterpta'] == '') {
		$sError = $ERR['masi05admiterpta'] . $sSepara . $sError;
	}
	/*
	if ($DATA['masi05cuerpo'] == '') {
		$sError = $ERR['masi05cuerpo'] . $sSepara . $sError;
	}
	if ($DATA['masi05asunto'] == '') {
		$sError = $ERR['masi05asunto'] . $sSepara . $sError;
	}
	*/
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'masi05correorpta', 'masi05asunto');
		$aLargoCampos = array(0, 200, 250);
		for ($k = 1; $k <= 2; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		list($sTabla1205, $sError) = f1205_NombreTabla($DATA['bmes'], $objDB);
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['masi05consec'] == '') {
				$DATA['masi05consec'] = tabla_consecutivo($sTabla1205, 'masi05consec', 'masi05idproceso=' . $DATA['masi05idproceso'] . '', $objDB);
				if ($DATA['masi05consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'masi05consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['masi05consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sTabla1205 . ' WHERE masi05idproceso=' . $DATA['masi05idproceso'] . ' AND masi05consec=' . $DATA['masi05consec'] . '';
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
				$sError = $ERR['3'] . ' [Mod ' . $iCodModulo . ']';
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['masi05id'] = tabla_consecutivo($sTabla1205, 'masi05id', '', $objDB);
			if ($DATA['masi05id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['masi05estado'] = 0;
			//$DATA['masi05idusuario'] = 0; //$_SESSION['unad_id_tercero'];
			$DATA['masi05hora'] = 0;
			$DATA['masi05min'] = 0;
			$DATA['masi05idperiodo'] = 0;
			$DATA['masi05curso'] = 0;
			$DATA['masi05docente'] = 0;
			$DATA['masi05total_usuarios'] = 0;
			$DATA['masi05total_envios'] = 0;
			$DATA['masi05tiponotifica'] = 0;
			$DATA['masi05idrelacion'] = 0;
			$DATA['masi05idrelacion2'] = 0;
			$DATA['masi05idrelacion3'] = 0;
		}
	}
	if ($sError == '') {
		//$masi05cuerpo = addslashes($DATA['masi05cuerpo']);
		$masi05cuerpo = str_replace('"', '\"', $DATA['masi05cuerpo']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos1205 = 'masi05idproceso, masi05consec, masi05id, masi05estado, masi05asunto, 
			masi05cuerpo, masi05admiterpta, masi05correorpta, masi05firma, masi05idusuario, 
			masi05fecha, masi05hora, masi05min, masi05unidadfunc, masi05zona, 
			masi05centro, masi05escuela, masi05programa, masi05idperiodo, masi05curso, 
			masi05docente, masi05total_usuarios, masi05total_envios, masi05tiponotifica, masi05periodicidad, 
			masi05idrelacion, masi05idrelacion2, masi05idrelacion3';
			$sValores1205 = '' . $DATA['masi05idproceso'] . ', ' . $DATA['masi05consec'] . ', ' . $DATA['masi05id'] . ', ' . $DATA['masi05estado'] . ', "' . $DATA['masi05asunto'] . '", 
			"' . $masi05cuerpo . '", ' . $DATA['masi05admiterpta'] . ', "' . $DATA['masi05correorpta'] . '", ' . $DATA['masi05firma'] . ', ' . $DATA['masi05idusuario'] . ', 
			' . $DATA['masi05fecha'] . ', ' . $DATA['masi05hora'] . ', ' . $DATA['masi05min'] . ', ' . $DATA['masi05unidadfunc'] . ', ' . $DATA['masi05zona'] . ', 
			' . $DATA['masi05centro'] . ', ' . $DATA['masi05escuela'] . ', ' . $DATA['masi05programa'] . ', ' . $DATA['masi05idperiodo'] . ', ' . $DATA['masi05curso'] . ', 
			' . $DATA['masi05docente'] . ', ' . $DATA['masi05total_usuarios'] . ', ' . $DATA['masi05total_envios'] . ', ' . $DATA['masi05tiponotifica'] . ', ' . $DATA['masi05periodicidad'] . ', 
			' . $DATA['masi05idrelacion'] . ', ' . $DATA['masi05idrelacion2'] . ', ' . $DATA['masi05idrelacion3'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla1205 . ' (' . $sCampos1205 . ') VALUES (' . cadena_codificar($sValores1205) . ');';
				$sDetalle = $sCampos1205 . '[' . cadena_codificar($sValores1205) . ']';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla1205 . ' (' . $sCampos1205 . ') VALUES (' . $sValores1205 . ');';
				$sDetalle = $sCampos1205 . '[' . $sValores1205 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'masi05asunto';
			$sCampo[2] = 'masi05cuerpo';
			$sCampo[3] = 'masi05admiterpta';
			$sCampo[4] = 'masi05correorpta';
			$sCampo[5] = 'masi05firma';
			$sCampo[6] = 'masi05fecha';
			$sCampo[7] = 'masi05hora';
			$sCampo[8] = 'masi05min';
			$sCampo[9] = 'masi05unidadfunc';
			$sCampo[10] = 'masi05zona';
			$sCampo[11] = 'masi05centro';
			$sCampo[12] = 'masi05escuela';
			$sCampo[13] = 'masi05programa';
			$sCampo[14] = 'masi05periodicidad';
			$sCampo[15] = 'masi05idrelacion';
			$sCampo[16] = 'masi05idrelacion2';
			$sCampo[17] = 'masi05idrelacion3';
			$sDato[1] = $DATA['masi05asunto'];
			$sDato[2] = $masi05cuerpo;
			$sDato[3] = $DATA['masi05admiterpta'];
			$sDato[4] = $DATA['masi05correorpta'];
			$sDato[5] = $DATA['masi05firma'];
			$sDato[6] = $DATA['masi05fecha'];
			$sDato[7] = $DATA['masi05hora'];
			$sDato[8] = $DATA['masi05min'];
			$sDato[9] = $DATA['masi05unidadfunc'];
			$sDato[10] = $DATA['masi05zona'];
			$sDato[11] = $DATA['masi05centro'];
			$sDato[12] = $DATA['masi05escuela'];
			$sDato[13] = $DATA['masi05programa'];
			$sDato[14] = $DATA['masi05periodicidad'];
			$sDato[15] = $DATA['masi05idrelacion'];
			$sDato[16] = $DATA['masi05idrelacion2'];
			$sDato[17] = $DATA['masi05idrelacion3'];
			$iNumCamposMod = 17;
			$sWhere = 'masi05id=' . $DATA['masi05id'] . '';
			$sSQL = 'SELECT * FROM ' . $sTabla1205 . ' WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE ' . $sTabla1205 . ' SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla1205 . ' SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 1205 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [1205] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['masi05id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['masi05id'], $sDetalle, $objDB);
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
function f1205_db_Eliminar($masi05id, $bloque, $objDB, $bDebug = false)
{
	$iCodModulo = 1205;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1205)) {
		$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1205;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$masi05id = numeros_validar($masi05id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		list($sTabla1205, $sError) = f1205_NombreTabla($bloque, $objDB);
		list($sTabla1206, $sErrorH) = f1206_NombreTabla($bloque, $objDB);
		list($sTabla1207, $sErrorH) = f1207_NombreTabla($bloque, $objDB);
		list($sTabla1208, $sErrorH) = f1208_NombreTabla($bloque, $objDB);
	}
	if ($sError == '') {
		$sSQL = 'SELECT * FROM ' . $sTabla1205 . ' WHERE masi05id=' . $masi05id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $masi05id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sTabla1206 . ' WHERE masi06idmensaje=' . $filabase['masi05id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Poblacion creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sTabla1207 . ' WHERE masi07idmensaje=' . $filabase['masi05id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Anexo creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sTabla1208 . ' WHERE masi08idmensaje=' . $filabase['masi05id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Destinatario creados, no es posible eliminar';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1205';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['masi05id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM ' . $sTabla1206 . ' WHERE masi06idmensaje=' . $filabase['masi05id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM ' . $sTabla1207 . ' WHERE masi07idmensaje=' . $filabase['masi05id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM ' . $sTabla1208 . ' WHERE masi08idmensaje=' . $filabase['masi05id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'masi05id=' . $masi05id . '';
		//$sWhere = 'masi05consec=' . $filabase['masi05consec'] . ' AND masi05idproceso=' . $filabase['masi05idproceso'] . '';
		$sSQL = 'DELETE FROM ' . $sTabla1205 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $masi05id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

function f1205_CambiaEstado($masi05id, $bloque, $iEstadoOrigen, $iEstadoDestino, $sDetalle, $idUsuario, $objDB, $bDebug = false)
{
	$iCodModulo = 1205;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_' . $sIdioma . '.php';
	if (!file_exists($mensajes_1205)) {
		$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1205;
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$bNotificar = false;
	list($sTabla1205, $sError) = f1205_NombreTabla($bloque, $objDB);
	$sSQL = 'SELECT masi05estado FROM ' . $sTabla1205 . ' WHERE masi05id=' . $masi05id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		if ($filabase['masi05estado'] != $iEstadoOrigen) {
			$sError = 'El estado de origen no coincide [' . $filabase['masi05estado'] . '].';
		}
	} else {
		$sError = $ETI['msg_no_encontrado'] . ' [Ref ' . $masi05id . ']';
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
		$sSQL = 'UPDATE ' . $sTabla1205 . ' SET masi05estado=' . $iEstadoDestino . '' . $sDatosAdd . ' WHERE masi05id=' . $masi05id . '';
		$result = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['masi05id'], $sInfoCambio, $objDB);
	}
	if ($bNotificar) {
		list($sError, $sDebugN, $sMensaje) = f1205_Notificar($masi05id, $bloque, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugN;
	}
	return array($sError, $sDebug, $sMensaje);
}

function f1205_Notificar($masi05id, $bloque, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$iHoy = fecha_DiaMod();
	$idInteresado = 0;
	list($sTabla1205, $sError) = f1205_NombreTabla($bloque, $objDB);
	$sSQL = 'SELECT * 
	FROM ' . $sTabla1205 . ' AS TB
	WHERE TB.masi05id=' . $masi05id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		$idInteresado = $filabase['id_interesado'];
		$masi05estado = $filabase['masi05estado'];
	} else {
		$sError = 'No se ha encontrado el registro solicitado [Ref ' . $masi05id . ']';
	}
	if ($sError == '') {
		$sTituloMensaje = 'Notificación de ... ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '';
		$sCuerpo = 'Estimado usuario:<br><br>';
		switch ($masi05estado) {
			case 0:
				break;
		}
	}
	if ($sError == '') {
		list($sCorreoUsuario, $sErrorN, $sDebugM) = AUREA_CorreoNotifica($idInteresado, $objDB, $bDebug);
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

function f1205_GestionaPoblacion($masi05idproceso, $masi05estado) 
{
	$res = false;
	//Aqui se debe determinar si pueden agregar usuarios manualmente, en principio no para nadie.
	if ($masi05estado == 0) {
		switch ($masi05idproceso) {
			case 0: // - Ninguno
				break;
			case 2: // - Funcionarios
				break;
			case 3: // - Contratistas
				break;
			case 11: // - Aspirantes
				break;
			case 12: // - Estudiantes
				break;
			case 13: // - Estudiantes ausentes
				break;
			case 17: // - Egresados
				break;
			case 2209: // - Estudiantes del programa
				break;
			case 2306: // - Acompañamiento académico
				break;
			case 2307: // - Seguimiento académico
				break;
			case 2741: // - Postulados a grados
				break;
			case 12229: // - Convocados
				break;
		}
	}
	return $res;
}