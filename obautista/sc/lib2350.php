<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Omar Augusto Bautista - UNAD - 2020 - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 jueves, 21 de junio de 2018
--- Modelo Versión 2.28.1 jueves, 28 de abril de 2022
--- Modelo Versión 3.2.5c miércoles, 29 de julio de 2026
--- 2350 cara50consolidado
*/

/** Archivo lib2350.php.
 * Libreria 2350 cara50consolidado.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date Wednesday, June 21, 2018
 *
 * Cambios 21 de mayo de 2020
 * 1. Adición de función f2350_HTMLComboV2_core50idprograma
 * 2. Adición de función f2350_Combocore50idprograma
 */
function f2350_NombreTabla() {
	return 'cara50consolidado';
}
function f2350_HTMLComboV2_cara50idperaca($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara50idperaca', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	// $objCombos->sAccion = 'RevisaLlave();';
	//Solo los peracas donde haya encuestas.
	$sIds = '-99';
	$sSQL = 'SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds = $sIds . ',' . $fila['cara01idperaca'];
	}
	$sSQL = f146_ConsultaCombo('exte02id IN (' . $sIds . ')');
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2350_HTMLComboV2_cara50periodoacomp($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara50periodoacomp', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	// $objCombos->sAccion = 'RevisaLlave();';
	//Solo los peracas donde haya encuestas.
	$sIds = '-99';
	$sSQL = 'SELECT cara01idperiodoacompana FROM cara01encuesta GROUP BY cara01idperiodoacompana';
	$tabla = $objDB->ejecutasql($sSQL);
	while($fila = $objDB->sf($tabla)){
		$sIds = $sIds . ',' . $fila['cara01idperiodoacompana'];
	}
	$sSQL = f146_ConsultaCombo('exte02id IN (' . $sIds . ')');
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2350_HTMLComboV2_cara50convenio($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara50convenio', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	$objCombos->sAccion='paginarf2216()';
	$sSQL = 'SELECT core50id AS id, core50nombre AS nombre FROM core50convenios ORDER BY core50estado DESC, core50nombre';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2350_HTMLComboV2_core50idtipo($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core50idtipo', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	// $objCombos->sAccion='paginarf2216()';
	$sSQL = 'SELECT cara11id AS id, cara11nombre AS nombre FROM cara11tipocaract ORDER BY cara11nombre';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2350_HTMLComboV2_cara50idzona($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara50idzona', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	//$objCombos->bEsCombobox = true;
	$objCombos->sAccion = 'carga_combo_cara50idcentro()';
	$sSQL = 'SELECT TB.unad23id AS id, TB.unad23nombre AS nombre 
	FROM unad23zona AS TB
	WHERE TB.unad23id>0
	ORDER BY TB.unad23nombre';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2350, $sIdioma
	return $res;
}
function f2350_HTMLComboV2_cara50idcentro($objDB, $objCombos, $valor, $vrcara50idzona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara50idcentro', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	$sSQL = '';
	if ((int)$vrcara50idzona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrcara50idzona . ' 
		ORDER BY TB.unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2350, $sIdioma
	return $res;
}
function f2350_HTMLComboV2_core50idprograma($objDB, $objCombos, $valor, $vrcore50idescuela)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('core50idprograma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	$sSQL = '';
	if ((int)$vrcore50idescuela != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core09id AS id, TB.core09nombre AS nombre 
		FROM core09programa AS TB
		WHERE TB.core09idescuela=' . $vrcore50idescuela . ' 
		ORDER BY TB.core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2350, $sIdioma
	return $res;
}
function f2350_Combocara50idcentro($aParametros)
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
	$html_cara50idcentro = f2350_HTMLComboV2_cara50idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cara50idcentro', 'innerHTML', $html_cara50idcentro);
	$objResponse->call('createComboboxById("cara50idcentro")');
	return $objResponse;
}
function f2350_Combocore50idprograma($aParametros)
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
	$html_core50idprograma = f2350_HTMLComboV2_core50idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_core50idprograma', 'innerHTML', $html_core50idprograma);
	$objResponse->call('createComboboxById("core50idprograma")');
	return $objResponse;
}
function f2350_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$cara50idperaca = numeros_validar($datos[1]);
	if ($cara50idperaca == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sNomTabla2350 = f2350_NombreTabla();
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2350 . ' WHERE cara50idperaca=' . $cara50idperaca . '';
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
function f2350_Busquedas($aParametros)
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
	$mensajes_2350 = 'lg/lg_2350_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2350)) {
		$mensajes_2350 = 'lg/lg_2350_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2350;
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
	$sTituloModulo = $ETI['titulo_2350'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
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
function f2350_HtmlBusqueda($aParametros)
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
function f2350_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_2300 = 'lg/lg_2300_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2300)) {
		$mensajes_2300 = 'lg/lg_2300_es.php';
	}
	require $mensajes_2300;
	*/
	$mensajes_2350 = 'lg/lg_2350_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2350)) {
		$mensajes_2350 = 'lg/lg_2350_es.php';
	}
	require $mensajes_2350;
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
	$sNomTabla2350 = f2350_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2350" name="paginaf2350" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2350" name="lppf2350" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Zona, Centro, Escuela, Programa, Tipo, Poblacion, Periodoacomp, Convenio, Periodomatricula, Tipomatricula, Listadoc';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT T1.unad23nombre, T2.unad24nombre, T3.core12nombre, T4.core09nombre, TB.core50idtipo, TB.cara50poblacion, T7.exte02nombre, T8.core50nombre, T9.exte02nombre, TB.cara50tipomatricula, TB.cara50listadoc, TB.cara50idzona, TB.cara50idcentro, TB.core50idescuela, TB.core50idprograma, TB.cara50periodoacomp, TB.cara50convenio, TB.cara50periodomatricula';
	$sConsulta = 'FROM ' . $sNomTabla2350 . ' AS TB, unad23zona AS T1, unad24sede AS T2, core12escuela AS T3, core09programa AS T4, exte02per_aca AS T7, core50convenios AS T8, exte02per_aca AS T9 
	WHERE ' . $sSQLadd1 . ' TB.cara50idzona=T1.unad23id AND TB.cara50idcentro=T2.unad24id AND TB.core50idescuela=T3.core12id AND TB.core50idprograma=T4.core09id AND TB.cara50periodoacomp=T7.exte02id AND TB.cara50convenio=T8.core50id AND TB.cara50periodomatricula=T9.exte02id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY ';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2350: ' . $sSQLContador . '');
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
	$sErrConsulta = '<input id="consulta_2350" name="consulta_2350" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2350" name="titulos_2350" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2350: ' . $sSQL . '');
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
		$sDebug = $sDebug . log_debug('Termina la consulta 2350');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['core50idzona'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['core50idcentro'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['core50idescuela'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['core50idprograma'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['core50idtipo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['cara50poblacion'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['msg_periodoacomp'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['cara50convenio'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['cara50periodomatricula'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['cara50tipomatricula'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['cara50listadoc'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2350', $registros, $lineastabla, $pagina, 'paginarf2350()');
	$res = $res . html_lpp('lppf2350', $lineastabla, 'paginarf2350()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
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
		$et_cara50idzona = $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo;
		$et_cara50idcentro = $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo;
		$et_core50idescuela = $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo;
		$et_core50idprograma = $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo;
		$et_core50idtipo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['core50idtipo'] == 0) {
			$et_core50idtipo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_cara50poblacion = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['cara50poblacion'] == 0) {
			$et_cara50poblacion = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_cara50periodoacomp = $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo;
		$et_cara50convenio = $sPrefijo . cadena_notildes($filadet['core50nombre']) . $sSufijo;
		$et_cara50periodomatricula = $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo;
		$et_cara50tipomatricula = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['cara50tipomatricula'] == 0) {
			$et_cara50tipomatricula = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_cara50listadoc = $sPrefijo . cadena_notildes($filadet['cara50listadoc']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargadato('."'".$filadet['cara50idperaca']."'" . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_cara50idzona . '</td>';
		$res = $res . '<td>' . $et_cara50idcentro . '</td>';
		$res = $res . '<td>' . $et_core50idescuela . '</td>';
		$res = $res . '<td>' . $et_core50idprograma . '</td>';
		$res = $res . '<td>' . $et_core50idtipo . '</td>';
		$res = $res . '<td>' . $et_cara50poblacion . '</td>';
		$res = $res . '<td>' . $et_cara50periodoacomp . '</td>';
		$res = $res . '<td>' . $et_cara50convenio . '</td>';
		$res = $res . '<td>' . $et_cara50periodomatricula . '</td>';
		$res = $res . '<td>' . $et_cara50tipomatricula . '</td>';
		$res = $res . '<td>' . $et_cara50listadoc . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2350_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2350_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2350detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

