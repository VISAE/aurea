<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.6 lunes, 31 de julio de 2023
--- 3021 saiu21directa
*/
/** Archivo lib3021.php.
 * Libreria 3021 saiu21directa.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date lunes, 31 de julio de 2023
 */
function f3021_HTMLComboV2_saiu21tiporadicado($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21tiporadicado', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3021_HTMLComboV2_saiu21idcentro($objDB, $objCombos, $valor, $vrsaiu21idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21idcentro', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrsaiu21idzona != 0) {
		$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona=' . $vrsaiu21idzona . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3021_HTMLComboV2_saiu21coddepto($objDB, $objCombos, $valor, $vrsaiu21codpais)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21coddepto', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'carga_combo_saiu21codciudad()';
	$sSQL = '';
	if ((int)$vrsaiu21codpais != 0) {
		$sSQL = 'SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto WHERE unad19codpais=' . $vrsaiu21codpais . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3021_HTMLComboV2_saiu21codciudad($objDB, $objCombos, $valor, $vrsaiu21coddepto)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21codciudad', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrsaiu21coddepto != 0) {
		$sSQL = 'SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad WHERE unad20coddepto=' . $vrsaiu21coddepto . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3021_HTMLComboV2_saiu21idprograma($objDB, $objCombos, $valor, $vrsaiu21idescuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21idprograma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrsaiu21idescuela != 0) {
		$sSQL = 'SELECT core09id AS id, core09nombre AS nombre FROM core09programa WHERE core09idescuela=' . $vrsaiu21idescuela . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3021_Combosaiu21idcentro($aParametros)
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
	$html_saiu21idcentro = f3021_HTMLComboV2_saiu21idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu21idcentro', 'innerHTML', $html_saiu21idcentro);
	//$objResponse->call('$("#saiu21idcentro").chosen()');
	return $objResponse;
}
function f3021_Combosaiu21coddepto($aParametros)
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
	$html_saiu21coddepto = f3021_HTMLComboV2_saiu21coddepto($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu21coddepto', 'innerHTML', $html_saiu21coddepto);
	//$objResponse->call('$("#saiu21coddepto").chosen()');
	return $objResponse;
}
function f3021_Combosaiu21codciudad($aParametros)
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
	$html_saiu21codciudad = f3021_HTMLComboV2_saiu21codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu21codciudad', 'innerHTML', $html_saiu21codciudad);
	//$objResponse->call('$("#saiu21codciudad").chosen()');
	return $objResponse;
}
function f3021_Combosaiu21idprograma($aParametros)
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
	$html_saiu21idprograma = f3021_HTMLComboV2_saiu21idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu21idprograma', 'innerHTML', $html_saiu21idprograma);
	//$objResponse->call('$("#saiu21idprograma").chosen()');
	return $objResponse;
}
function f3021_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$saiu21agno = numeros_validar($datos[1]);
	if ($saiu21agno == '') {
		$bHayLlave = false;
	}
	$saiu21mes = numeros_validar($datos[2]);
	if ($saiu21mes == '') {
		$bHayLlave = false;
	}
	$saiu21tiporadicado = numeros_validar($datos[3]);
	if ($saiu21tiporadicado == '') {
		$bHayLlave = false;
	}
	$saiu21consec = numeros_validar($datos[4]);
	if ($saiu21consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM saiu21directa WHERE saiu21agno=' . $saiu21agno . ' AND saiu21mes=' . $saiu21mes . ' AND saiu21tiporadicado=' . $saiu21tiporadicado . ' AND saiu21consec=' . $saiu21consec . '';
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
function f3021_Busquedas($aParametros)
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
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3021;
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
		case 'saiu21idsolicitante':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['saiu21idsolicitante_busca']) == 0) {
				$ETI['saiu21idsolicitante_busca'] = 'Busqueda de Solicitante';
			}
			$sTitulo = $ETI['saiu21idsolicitante_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3021);
			break;
		case 'saiu21idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['saiu21idresponsable_busca']) == 0) {
				$ETI['saiu21idresponsable_busca'] = 'Busqueda de Responsable';
			}
			$sTitulo = $ETI['saiu21idresponsable_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3021);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_3021'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f3021_HtmlBusqueda($aParametros)
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
		case 'saiu21idsolicitante':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'saiu21idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f3021_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_3021;
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
	for ($k = 103; $k <= 102; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	$idTercero = numeros_validar($aParametros[100]);
	$sDebug = '';
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = numeros_validar($aParametros[101]);
		$lineastabla = numeros_validar($aParametros[102]);
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
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
	$sBotones = '<input id="paginaf3021" name="paginaf3021" type="hidden" value="' . $pagina . '"/>
	<input id="lppf3021" name="lppf3021" type="hidden" value="' . $lineastabla . '"/>';
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
	$aEstado = array();
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
		/*
		if ($aParametros[104] != '') {
			$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[104] . '%"';
		}
		if ($aParametros[104] != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.campo2 LIKE "%' . $aParametros[104] . '%" AND ';
		}
		if ($bNombre != '') {
			$sBase = mb_strtoupper($bNombre);
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
	$sTitulos = 'Agno, Mes, Tiporadicado, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Estado, Correo, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos, Solucion, Caso';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.saiu21agno, TB.saiu21mes, T3.saiu16nombre, TB.saiu21consec, TB.saiu21id, TB.saiu21origenagno, TB.saiu21origenmes, TB.saiu21origenid, TB.saiu21dia, TB.saiu21hora, TB.saiu21minuto, T12.saiu11nombre, T13.saiu57titulo, T14.unad11razonsocial AS C14_nombre, T15.bita07nombre, T16.saiu01titulo, T17.saiu02titulo, T18.saiu03titulo, T19.unad23nombre, T20.unad24nombre, T21.unad18nombre, T22.unad19nombre, T23.unad20nombre, T24.core12nombre, T25.core09nombre, T26.exte02nombre, TB.saiu21numorigen, TB.saiu21idpqrs, TB.saiu21detalle, TB.saiu21horafin, TB.saiu21minutofin, TB.saiu21paramercadeo, T33.unad11razonsocial AS C33_nombre, TB.saiu21tiemprespdias, TB.saiu21tiempresphoras, TB.saiu21tiemprespminutos, TB.saiu21solucion, TB.saiu21idcaso, TB.saiu21tiporadicado, TB.saiu21estado, TB.saiu21idcorreo, TB.saiu21idsolicitante, T14.unad11tipodoc AS C14_td, T14.unad11doc AS C14_doc, TB.saiu21tipointeresado, TB.saiu21clasesolicitud, TB.saiu21tiposolicitud, TB.saiu21temasolicitud, TB.saiu21idzona, TB.saiu21idcentro, TB.saiu21codpais, TB.saiu21coddepto, TB.saiu21codciudad, TB.saiu21idescuela, TB.saiu21idprograma, TB.saiu21idperiodo, TB.saiu21idresponsable, T33.unad11tipodoc AS C33_td, T33.unad11doc AS C33_doc';
	$sConsulta = 'FROM saiu21directa AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T12, saiu57correos AS T13, unad11terceros AS T14, bita07tiposolicitante AS T15, saiu01claseser AS T16, saiu02tiposol AS T17, saiu03temasol AS T18, unad23zona AS T19, unad24sede AS T20, unad18pais AS T21, unad19depto AS T22, unad20ciudad AS T23, core12escuela AS T24, core09programa AS T25, exte02per_aca AS T26, unad11terceros AS T33 
	WHERE ' . $sSQLadd1 . ' TB.saiu21tiporadicado=T3.saiu16id AND TB.saiu21estado=T12.saiu11id AND TB.saiu21idcorreo=T13.saiu57id AND TB.saiu21idsolicitante=T14.unad11id AND TB.saiu21tipointeresado=T15.bita07id AND TB.saiu21clasesolicitud=T16.saiu01id AND TB.saiu21tiposolicitud=T17.saiu02id AND TB.saiu21temasolicitud=T18.saiu03id AND TB.saiu21idzona=T19.unad23id AND TB.saiu21idcentro=T20.unad24id AND TB.saiu21codpais=T21.unad18codigo AND TB.saiu21coddepto=T22.unad19codigo AND TB.saiu21codciudad=T23.unad20codigo AND TB.saiu21idescuela=T24.core12id AND TB.saiu21idprograma=T25.core09id AND TB.saiu21idperiodo=T26.exte02id AND TB.saiu21idresponsable=T33.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.saiu21agno, TB.saiu21mes, TB.saiu21tiporadicado, TB.saiu21consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
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
	$sErrConsulta = '<input id="consulta_3021" name="consulta_3021" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3021" name="titulos_3021" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3021: ' . $sSQL . '<br>';
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
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['saiu21agno'] . '</b></td>
	<td><b>' . $ETI['saiu21mes'] . '</b></td>
	<td><b>' . $ETI['saiu21tiporadicado'] . '</b></td>
	<td><b>' . $ETI['saiu21consec'] . '</b></td>
	<td><b>' . $ETI['saiu21origenagno'] . '</b></td>
	<td><b>' . $ETI['saiu21origenmes'] . '</b></td>
	<td><b>' . $ETI['saiu21origenid'] . '</b></td>
	<td><b>' . $ETI['saiu21dia'] . '</b></td>
	<td><b>' . $ETI['saiu21hora'] . '</b></td>
	<td><b>' . $ETI['saiu21estado'] . '</b></td>
	<td><b>' . $ETI['saiu21idcorreo'] . '</b></td>
	<td colspan="2"><b>' . $ETI['saiu21idsolicitante'] . '</b></td>
	<td><b>' . $ETI['saiu21tipointeresado'] . '</b></td>
	<td><b>' . $ETI['saiu21clasesolicitud'] . '</b></td>
	<td><b>' . $ETI['saiu21tiposolicitud'] . '</b></td>
	<td><b>' . $ETI['saiu21temasolicitud'] . '</b></td>
	<td><b>' . $ETI['saiu21idzona'] . '</b></td>
	<td><b>' . $ETI['saiu21idcentro'] . '</b></td>
	<td><b>' . $ETI['saiu21codpais'] . '</b></td>
	<td><b>' . $ETI['saiu21coddepto'] . '</b></td>
	<td><b>' . $ETI['saiu21codciudad'] . '</b></td>
	<td><b>' . $ETI['saiu21idescuela'] . '</b></td>
	<td><b>' . $ETI['saiu21idprograma'] . '</b></td>
	<td><b>' . $ETI['saiu21idperiodo'] . '</b></td>
	<td><b>' . $ETI['saiu21numorigen'] . '</b></td>
	<td><b>' . $ETI['saiu21idpqrs'] . '</b></td>
	<td><b>' . $ETI['saiu21detalle'] . '</b></td>
	<td><b>' . $ETI['saiu21horafin'] . '</b></td>
	<td><b>' . $ETI['saiu21paramercadeo'] . '</b></td>
	<td colspan="2"><b>' . $ETI['saiu21idresponsable'] . '</b></td>
	<td><b>' . $ETI['saiu21tiemprespdias'] . '</b></td>
	<td><b>' . $ETI['saiu21tiempresphoras'] . '</b></td>
	<td><b>' . $ETI['saiu21tiemprespminutos'] . '</b></td>
	<td><b>' . $ETI['saiu21solucion'] . '</b></td>
	<td><b>' . $ETI['saiu21idcaso'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3021', $registros, $lineastabla, $pagina, 'paginarf3021()') . '
	' . html_lpp('lppf3021', $lineastabla, 'paginarf3021()') . '
	</td>
	</tr></thead>';
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
		$et_saiu21tiporadicado = $sPrefijo . cadena_notildes($filadet['saiu16nombre']) . $sSufijo;
		$et_saiu21consec = $sPrefijo . $filadet['saiu21consec'] . $sSufijo;
		$et_saiu21id = $sPrefijo . $filadet['saiu21id'] . $sSufijo;
		$et_saiu21hora = $sPrefijo . html_TablaHoraMin($filadet['saiu21hora'], $filadet['saiu21minuto']) . $sSufijo;
		$et_saiu21estado = $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo;
		$et_saiu21idcorreo = $sPrefijo . cadena_notildes($filadet['saiu57titulo']) . $sSufijo;
		$et_saiu21idsolicitante_doc = '';
		$et_saiu21idsolicitante_nombre = '';
		if ($filadet['saiu21idsolicitante'] != 0) {
			$et_saiu21idsolicitante_doc = $sPrefijo . $filadet['C14_td'] . ' ' . $filadet['C14_doc'] . $sSufijo;
			$et_saiu21idsolicitante_nombre = $sPrefijo . cadena_notildes($filadet['C14_nombre']) . $sSufijo;
		}
		$et_saiu21tipointeresado = $sPrefijo . cadena_notildes($filadet['bita07nombre']) . $sSufijo;
		$et_saiu21clasesolicitud = $sPrefijo . cadena_notildes($filadet['saiu01titulo']) . $sSufijo;
		$et_saiu21tiposolicitud = $sPrefijo . cadena_notildes($filadet['saiu02titulo']) . $sSufijo;
		$et_saiu21temasolicitud = $sPrefijo . cadena_notildes($filadet['saiu03titulo']) . $sSufijo;
		$et_saiu21idzona = $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo;
		$et_saiu21idcentro = $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo;
		$et_saiu21idescuela = $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo;
		$et_saiu21idprograma = $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo;
		$et_saiu21idperiodo = $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo;
		$et_saiu21numorigen = $sPrefijo . cadena_notildes($filadet['saiu21numorigen']) . $sSufijo;
		$et_saiu21detalle = $sPrefijo . $filadet['saiu21detalle'] . $sSufijo;
		$et_saiu21horafin = $sPrefijo . html_TablaHoraMin($filadet['saiu21horafin'], $filadet['saiu21minutofin']) . $sSufijo;
		$et_saiu21paramercadeo = $sPrefijo . cadena_notildes($filadet['']) . $sSufijo;
		$et_saiu21idresponsable_doc = '';
		$et_saiu21idresponsable_nombre = '';
		if ($filadet['saiu21idresponsable'] != 0) {
			$et_saiu21idresponsable_doc = $sPrefijo . $filadet['C33_td'] . ' ' . $filadet['C33_doc'] . $sSufijo;
			$et_saiu21idresponsable_nombre = $sPrefijo . cadena_notildes($filadet['C33_nombre']) . $sSufijo;
		}
		$et_saiu21solucion = $sPrefijo . cadena_notildes($filadet['']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3021(' . $filadet['saiu21id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['saiu21agno'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21mes'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu16nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21origenagno'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21origenmes'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21origenid'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21dia'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu21hora . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu57titulo']) . $sSufijo . '</td>
		<td>' . $et_saiu21idsolicitante_doc . '</td>
		<td>' . $et_saiu21idsolicitante_nombre . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['bita07nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu01titulo']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu02titulo']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu03titulo']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu21codpais']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu21coddepto']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu21codciudad']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu21numorigen']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21idpqrs'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21detalle'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu21horafin . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21paramercadeo'] . $sSufijo . '</td>
		<td>' . $et_saiu21idresponsable_doc . '</td>
		<td>' . $et_saiu21idresponsable_nombre . '</td>
		<td>' . $sPrefijo . $filadet['saiu21tiemprespdias'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21tiempresphoras'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21tiemprespminutos'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21solucion'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21idcaso'] . $sSufijo . '</td>
		<td align="right">' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3021_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3021_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3021detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3021_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['saiu21idsolicitante_td'] = $APP->tipo_doc;
	$DATA['saiu21idsolicitante_doc'] = '';
	$DATA['saiu21idresponsable_td'] = $APP->tipo_doc;
	$DATA['saiu21idresponsable_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'saiu21agno=' . $DATA['saiu21agno'] . ' AND saiu21mes=' . $DATA['saiu21mes'] . ' AND saiu21tiporadicado=' . $DATA['saiu21tiporadicado'] . ' AND saiu21consec=' . $DATA['saiu21consec'] . '';
	} else {
		$sSQLcondi = 'saiu21id=' . $DATA['saiu21id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu21directa WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['saiu21agno'] = $fila['saiu21agno'];
		$DATA['saiu21mes'] = $fila['saiu21mes'];
		$DATA['saiu21tiporadicado'] = $fila['saiu21tiporadicado'];
		$DATA['saiu21consec'] = $fila['saiu21consec'];
		$DATA['saiu21id'] = $fila['saiu21id'];
		$DATA['saiu21origenagno'] = $fila['saiu21origenagno'];
		$DATA['saiu21origenmes'] = $fila['saiu21origenmes'];
		$DATA['saiu21origenid'] = $fila['saiu21origenid'];
		$DATA['saiu21dia'] = $fila['saiu21dia'];
		$DATA['saiu21hora'] = $fila['saiu21hora'];
		$DATA['saiu21minuto'] = $fila['saiu21minuto'];
		$DATA['saiu21estado'] = $fila['saiu21estado'];
		$DATA['saiu21idcorreo'] = $fila['saiu21idcorreo'];
		$DATA['saiu21idsolicitante'] = $fila['saiu21idsolicitante'];
		$DATA['saiu21tipointeresado'] = $fila['saiu21tipointeresado'];
		$DATA['saiu21clasesolicitud'] = $fila['saiu21clasesolicitud'];
		$DATA['saiu21tiposolicitud'] = $fila['saiu21tiposolicitud'];
		$DATA['saiu21temasolicitud'] = $fila['saiu21temasolicitud'];
		$DATA['saiu21idzona'] = $fila['saiu21idzona'];
		$DATA['saiu21idcentro'] = $fila['saiu21idcentro'];
		$DATA['saiu21codpais'] = $fila['saiu21codpais'];
		$DATA['saiu21coddepto'] = $fila['saiu21coddepto'];
		$DATA['saiu21codciudad'] = $fila['saiu21codciudad'];
		$DATA['saiu21idescuela'] = $fila['saiu21idescuela'];
		$DATA['saiu21idprograma'] = $fila['saiu21idprograma'];
		$DATA['saiu21idperiodo'] = $fila['saiu21idperiodo'];
		$DATA['saiu21numorigen'] = $fila['saiu21numorigen'];
		$DATA['saiu21idpqrs'] = $fila['saiu21idpqrs'];
		$DATA['saiu21detalle'] = $fila['saiu21detalle'];
		$DATA['saiu21horafin'] = $fila['saiu21horafin'];
		$DATA['saiu21minutofin'] = $fila['saiu21minutofin'];
		$DATA['saiu21paramercadeo'] = $fila['saiu21paramercadeo'];
		$DATA['saiu21idresponsable'] = $fila['saiu21idresponsable'];
		$DATA['saiu21tiemprespdias'] = $fila['saiu21tiemprespdias'];
		$DATA['saiu21tiempresphoras'] = $fila['saiu21tiempresphoras'];
		$DATA['saiu21tiemprespminutos'] = $fila['saiu21tiemprespminutos'];
		$DATA['saiu21solucion'] = $fila['saiu21solucion'];
		$DATA['saiu21idcaso'] = $fila['saiu21idcaso'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta3021'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3021_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3021;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3021;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu21agno']) == 0) {
		$DATA['saiu21agno'] = 0;
	}
	if (isset($DATA['saiu21mes']) == 0) {
		$DATA['saiu21mes'] = 0;
	}
	if (isset($DATA['saiu21tiporadicado']) == 0) {
		$DATA['saiu21tiporadicado'] = 0;
	}
	if (isset($DATA['saiu21consec']) == 0) {
		$DATA['saiu21consec'] = '';
	}
	if (isset($DATA['saiu21id']) == 0) {
		$DATA['saiu21id'] = '';
	}
	if (isset($DATA['saiu21origenid']) == 0) {
		$DATA['saiu21origenid'] = 0;
	}
	if (isset($DATA['saiu21dia']) == 0) {
		$DATA['saiu21dia'] = 0;
	}
	if (isset($DATA['saiu21hora']) == 0) {
		$DATA['saiu21hora'] = 0;
	}
	if (isset($DATA['saiu21minuto']) == 0) {
		$DATA['saiu21minuto'] = 0;
	}
	if (isset($DATA['saiu21estado']) == 0) {
		$DATA['saiu21estado'] = 0;
	}
	if (isset($DATA['saiu21idcorreo']) == 0) {
		$DATA['saiu21idcorreo'] = 0;
	}
	if (isset($DATA['saiu21idsolicitante']) == 0) {
		$DATA['saiu21idsolicitante'] = '';
	}
	if (isset($DATA['saiu21tipointeresado']) == 0) {
		$DATA['saiu21tipointeresado'] = 0;
	}
	if (isset($DATA['saiu21tiposolicitud']) == 0) {
		$DATA['saiu21tiposolicitud'] = 0;
	}
	if (isset($DATA['saiu21temasolicitud']) == 0) {
		$DATA['saiu21temasolicitud'] = 0;
	}
	if (isset($DATA['saiu21idzona']) == 0) {
		$DATA['saiu21idzona'] = 0;
	}
	if (isset($DATA['saiu21idcentro']) == 0) {
		$DATA['saiu21idcentro'] = 0;
	}
	if (isset($DATA['saiu21codpais']) == 0) {
		$DATA['saiu21codpais'] = '';
	}
	if (isset($DATA['saiu21coddepto']) == 0) {
		$DATA['saiu21coddepto'] = '';
	}
	if (isset($DATA['saiu21codciudad']) == 0) {
		$DATA['saiu21codciudad'] = '';
	}
	if (isset($DATA['saiu21idescuela']) == 0) {
		$DATA['saiu21idescuela'] = 0;
	}
	if (isset($DATA['saiu21idprograma']) == 0) {
		$DATA['saiu21idprograma'] = 0;
	}
	if (isset($DATA['saiu21idperiodo']) == 0) {
		$DATA['saiu21idperiodo'] = 0;
	}
	if (isset($DATA['saiu21numorigen']) == 0) {
		$DATA['saiu21numorigen'] = '';
	}
	if (isset($DATA['saiu21idpqrs']) == 0) {
		$DATA['saiu21idpqrs'] = 0;
	}
	if (isset($DATA['saiu21detalle']) == 0) {
		$DATA['saiu21detalle'] = '';
	}
	if (isset($DATA['saiu21horafin']) == 0) {
		$DATA['saiu21horafin'] = 0;
	}
	if (isset($DATA['saiu21minutofin']) == 0) {
		$DATA['saiu21minutofin'] = 0;
	}
	if (isset($DATA['saiu21paramercadeo']) == 0) {
		$DATA['saiu21paramercadeo'] = 0;
	}
	if (isset($DATA['saiu21idresponsable']) == 0) {
		$DATA['saiu21idresponsable'] = '';
	}
	if (isset($DATA['saiu21solucion']) == 0) {
		$DATA['saiu21solucion'] = 0;
	}
	if (isset($DATA['saiu21idcaso']) == 0) {
		$DATA['saiu21idcaso'] = 0;
	}
	*/
	$DATA['saiu21consec'] = numeros_validar($DATA['saiu21consec']);
	$DATA['saiu21dia'] = numeros_validar($DATA['saiu21dia']);
	$DATA['saiu21hora'] = numeros_validar($DATA['saiu21hora']);
	$DATA['saiu21minuto'] = numeros_validar($DATA['saiu21minuto']);
	$DATA['saiu21idcorreo'] = numeros_validar($DATA['saiu21idcorreo']);
	$DATA['saiu21tipointeresado'] = numeros_validar($DATA['saiu21tipointeresado']);
	$DATA['saiu21tiposolicitud'] = numeros_validar($DATA['saiu21tiposolicitud']);
	$DATA['saiu21temasolicitud'] = numeros_validar($DATA['saiu21temasolicitud']);
	$DATA['saiu21idzona'] = numeros_validar($DATA['saiu21idzona']);
	$DATA['saiu21idcentro'] = numeros_validar($DATA['saiu21idcentro']);
	$DATA['saiu21codpais'] = htmlspecialchars(trim($DATA['saiu21codpais']));
	$DATA['saiu21coddepto'] = htmlspecialchars(trim($DATA['saiu21coddepto']));
	$DATA['saiu21codciudad'] = htmlspecialchars(trim($DATA['saiu21codciudad']));
	$DATA['saiu21idescuela'] = numeros_validar($DATA['saiu21idescuela']);
	$DATA['saiu21idprograma'] = numeros_validar($DATA['saiu21idprograma']);
	$DATA['saiu21idperiodo'] = numeros_validar($DATA['saiu21idperiodo']);
	$DATA['saiu21numorigen'] = htmlspecialchars(trim($DATA['saiu21numorigen']));
	$DATA['saiu21detalle'] = htmlspecialchars(trim($DATA['saiu21detalle']));
	$DATA['saiu21horafin'] = numeros_validar($DATA['saiu21horafin']);
	$DATA['saiu21minutofin'] = numeros_validar($DATA['saiu21minutofin']);
	$DATA['saiu21paramercadeo'] = numeros_validar($DATA['saiu21paramercadeo']);
	$DATA['saiu21solucion'] = numeros_validar($DATA['saiu21solucion']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['saiu21origenid'] == '') {
		$DATA['saiu21origenid'] = 0;
	}
	if ($DATA['saiu21dia'] == '') {
		$DATA['saiu21dia'] = 0;
	}
	if ($DATA['saiu21hora'] == '') {
		$DATA['saiu21hora'] = 0;
	}
	if ($DATA['saiu21minuto'] == '') {
		$DATA['saiu21minuto'] = 0;
	}
	if ($DATA['saiu21estado'] == '') {
		$DATA['saiu21estado'] = 0;
	}
	if ($DATA['saiu21idcorreo'] == '') {
		$DATA['saiu21idcorreo'] = 0;
	}
	if ($DATA['saiu21tipointeresado'] == '') {
		$DATA['saiu21tipointeresado'] = 0;
	}
	if ($DATA['saiu21tiposolicitud'] == '') {
		$DATA['saiu21tiposolicitud'] = 0;
	}
	if ($DATA['saiu21temasolicitud'] == '') {
		$DATA['saiu21temasolicitud'] = 0;
	}
	if ($DATA['saiu21idzona'] == '') {
		$DATA['saiu21idzona'] = 0;
	}
	if ($DATA['saiu21idcentro'] == '') {
		$DATA['saiu21idcentro'] = 0;
	}
	if ($DATA['saiu21idescuela'] == '') {
		$DATA['saiu21idescuela'] = 0;
	}
	if ($DATA['saiu21idprograma'] == '') {
		$DATA['saiu21idprograma'] = 0;
	}
	if ($DATA['saiu21idperiodo'] == '') {
		$DATA['saiu21idperiodo'] = 0;
	}
	if ($DATA['saiu21idpqrs'] == '') {
		$DATA['saiu21idpqrs'] = 0;
	}
	if ($DATA['saiu21horafin'] == '') {
		$DATA['saiu21horafin'] = 0;
	}
	if ($DATA['saiu21minutofin'] == '') {
		$DATA['saiu21minutofin'] = 0;
	}
	if ($DATA['saiu21paramercadeo'] == '') {
		$DATA['saiu21paramercadeo'] = 0;
	}
	if ($DATA['saiu21solucion'] == '') {
		$DATA['saiu21solucion'] = 0;
	}
	if ($DATA['saiu21idcaso'] == '') {
		$DATA['saiu21idcaso'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		if ($DATA['saiu21solucion'] == '') {
			$sError = $ERR['saiu21solucion'] . $sSepara . $sError;
		}
		if ($DATA['saiu21idresponsable'] == 0) {
			$sError = $ERR['saiu21idresponsable'] . $sSepara . $sError;
		}
		if ($DATA['saiu21paramercadeo'] == '') {
			$sError = $ERR['saiu21paramercadeo'] . $sSepara . $sError;
		}
		if ($DATA['saiu21minutofin'] == '') {
			$sError = $ERR['saiu21minutofin'] . $sSepara . $sError;
		}
		if ($DATA['saiu21horafin'] == '') {
			$sError = $ERR['saiu21horafin'] . $sSepara . $sError;
		}
		/*
		if ($DATA['saiu21detalle'] == '') {
			$sError = $ERR['saiu21detalle'] . $sSepara . $sError;
		}
		*/
		if ($DATA['saiu21numorigen'] == '') {
			$sError = $ERR['saiu21numorigen'] . $sSepara . $sError;
		}
		if ($DATA['saiu21idperiodo'] == '') {
			$sError = $ERR['saiu21idperiodo'] . $sSepara . $sError;
		}
		if ($DATA['saiu21idprograma'] == '') {
			$sError = $ERR['saiu21idprograma'] . $sSepara . $sError;
		}
		if ($DATA['saiu21idescuela'] == '') {
			$sError = $ERR['saiu21idescuela'] . $sSepara . $sError;
		}
		if ($DATA['saiu21codciudad'] == '') {
			$sError = $ERR['saiu21codciudad'] . $sSepara . $sError;
		}
		if ($DATA['saiu21coddepto'] == '') {
			$sError = $ERR['saiu21coddepto'] . $sSepara . $sError;
		}
		if ($DATA['saiu21codpais'] == '') {
			$sError = $ERR['saiu21codpais'] . $sSepara . $sError;
		}
		if ($DATA['saiu21idcentro'] == '') {
			$sError = $ERR['saiu21idcentro'] . $sSepara . $sError;
		}
		if ($DATA['saiu21idzona'] == '') {
			$sError = $ERR['saiu21idzona'] . $sSepara . $sError;
		}
		if ($DATA['saiu21temasolicitud'] == '') {
			$sError = $ERR['saiu21temasolicitud'] . $sSepara . $sError;
		}
		if ($DATA['saiu21tiposolicitud'] == '') {
			$sError = $ERR['saiu21tiposolicitud'] . $sSepara . $sError;
		}
		if ($DATA['saiu21tipointeresado'] == '') {
			$sError = $ERR['saiu21tipointeresado'] . $sSepara . $sError;
		}
		if ($DATA['saiu21idsolicitante'] == 0) {
			$sError = $ERR['saiu21idsolicitante'] . $sSepara . $sError;
		}
		if ($DATA['saiu21idcorreo'] == '') {
			$sError = $ERR['saiu21idcorreo'] . $sSepara . $sError;
		}
		if ($DATA['saiu21minuto'] == '') {
			$sError = $ERR['saiu21minuto'] . $sSepara . $sError;
		}
		if ($DATA['saiu21hora'] == '') {
			$sError = $ERR['saiu21hora'] . $sSepara . $sError;
		}
		if ($DATA['saiu21dia'] == '') {
			$sError = $ERR['saiu21dia'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'saiu21numorigen');
		$aLargoCampos = array(0, 20);
		for ($k = 1; $k <= 1; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu21idresponsable_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu21idresponsable_td'], $DATA['saiu21idresponsable_doc'], $objDB, 'El tercero Responsable ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu21idresponsable'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['saiu21idsolicitante_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu21idsolicitante_td'], $DATA['saiu21idsolicitante_doc'], $objDB, 'El tercero Solicitante ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu21idsolicitante'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['saiu21consec'] == '') {
				$DATA['saiu21consec'] = tabla_consecutivo('saiu21directa', 'saiu21consec', 'saiu21agno=' . $DATA['saiu21agno'] . ' AND saiu21mes=' . $DATA['saiu21mes'] . ' AND saiu21tiporadicado=' . $DATA['saiu21tiporadicado'] . '', $objDB);
				if ($DATA['saiu21consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'saiu21consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['saiu21consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM saiu21directa WHERE saiu21agno=' . $DATA['saiu21agno'] . ' AND saiu21mes=' . $DATA['saiu21mes'] . ' AND saiu21tiporadicado=' . $DATA['saiu21tiporadicado'] . ' AND saiu21consec=' . $DATA['saiu21consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve) {
						$sError = $ERR['2'];
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
			$DATA['saiu21id'] = tabla_consecutivo('saiu21directa', 'saiu21id', '', $objDB);
			if ($DATA['saiu21id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['saiu21origenagno'] = 0;
			$DATA['saiu21origenmes'] = 0;
			$DATA['saiu21origenid'] = 0;
			$DATA['saiu21estado'] = 0;
			$DATA['saiu21clasesolicitud'] = 0;
			$DATA['saiu21idpqrs'] = 0;
			$DATA['saiu21tiemprespdias'] = 0;
			$DATA['saiu21tiempresphoras'] = 0;
			$DATA['saiu21tiemprespminutos'] = 0;
			$DATA['saiu21idcaso'] = 0;
		}
	}
	if ($sError == '') {
		//$saiu21detalle = addslashes($DATA['saiu21detalle']);
		$saiu21detalle = str_replace('"', '\"', $DATA['saiu21detalle']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos3021 = 'saiu21agno, saiu21mes, saiu21tiporadicado, saiu21consec, saiu21id, 
			saiu21origenagno, saiu21origenmes, saiu21origenid, saiu21dia, saiu21hora, 
			saiu21minuto, saiu21estado, saiu21idcorreo, saiu21idsolicitante, saiu21tipointeresado, 
			saiu21clasesolicitud, saiu21tiposolicitud, saiu21temasolicitud, saiu21idzona, saiu21idcentro, 
			saiu21codpais, saiu21coddepto, saiu21codciudad, saiu21idescuela, saiu21idprograma, 
			saiu21idperiodo, saiu21numorigen, saiu21idpqrs, saiu21detalle, saiu21horafin, 
			saiu21minutofin, saiu21paramercadeo, saiu21idresponsable, saiu21tiemprespdias, saiu21tiempresphoras, 
			saiu21tiemprespminutos, saiu21solucion, saiu21idcaso';
			$sValores3021 = '' . $DATA['saiu21agno'] . ', ' . $DATA['saiu21mes'] . ', ' . $DATA['saiu21tiporadicado'] . ', ' . $DATA['saiu21consec'] . ', ' . $DATA['saiu21id'] . ', 
			' . $DATA['saiu21origenagno'] . ', ' . $DATA['saiu21origenmes'] . ', ' . $DATA['saiu21origenid'] . ', ' . $DATA['saiu21dia'] . ', ' . $DATA['saiu21hora'] . ', 
			' . $DATA['saiu21minuto'] . ', ' . $DATA['saiu21estado'] . ', ' . $DATA['saiu21idcorreo'] . ', ' . $DATA['saiu21idsolicitante'] . ', ' . $DATA['saiu21tipointeresado'] . ', 
			' . $DATA['saiu21clasesolicitud'] . ', ' . $DATA['saiu21tiposolicitud'] . ', ' . $DATA['saiu21temasolicitud'] . ', ' . $DATA['saiu21idzona'] . ', ' . $DATA['saiu21idcentro'] . ', 
			"' . $DATA['saiu21codpais'] . '", "' . $DATA['saiu21coddepto'] . '", "' . $DATA['saiu21codciudad'] . '", ' . $DATA['saiu21idescuela'] . ', ' . $DATA['saiu21idprograma'] . ', 
			' . $DATA['saiu21idperiodo'] . ', "' . $DATA['saiu21numorigen'] . '", ' . $DATA['saiu21idpqrs'] . ', "' . $saiu21detalle . '", ' . $DATA['saiu21horafin'] . ', 
			' . $DATA['saiu21minutofin'] . ', ' . $DATA['saiu21paramercadeo'] . ', ' . $DATA['saiu21idresponsable'] . ', ' . $DATA['saiu21tiemprespdias'] . ', ' . $DATA['saiu21tiempresphoras'] . ', 
			' . $DATA['saiu21tiemprespminutos'] . ', ' . $DATA['saiu21solucion'] . ', ' . $DATA['saiu21idcaso'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO saiu21directa (' . $sCampos3021 . ') VALUES (' . cadena_codificar($sValores3021) . ');';
				$sdetalle = $sCampos3021 . '[' . cadena_codificar($sValores3021) . ']';
			} else {
				$sSQL = 'INSERT INTO saiu21directa (' . $sCampos3021 . ') VALUES (' . $sValores3021 . ');';
				$sdetalle = $sCampos3021 . '[' . $sValores3021 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'saiu21dia';
			$scampo[2] = 'saiu21hora';
			$scampo[3] = 'saiu21minuto';
			$scampo[4] = 'saiu21idcorreo';
			$scampo[5] = 'saiu21idsolicitante';
			$scampo[6] = 'saiu21tipointeresado';
			$scampo[7] = 'saiu21tiposolicitud';
			$scampo[8] = 'saiu21temasolicitud';
			$scampo[9] = 'saiu21idzona';
			$scampo[10] = 'saiu21idcentro';
			$scampo[11] = 'saiu21codpais';
			$scampo[12] = 'saiu21coddepto';
			$scampo[13] = 'saiu21codciudad';
			$scampo[14] = 'saiu21idescuela';
			$scampo[15] = 'saiu21idprograma';
			$scampo[16] = 'saiu21idperiodo';
			$scampo[17] = 'saiu21numorigen';
			$scampo[18] = 'saiu21detalle';
			$scampo[19] = 'saiu21horafin';
			$scampo[20] = 'saiu21minutofin';
			$scampo[21] = 'saiu21paramercadeo';
			$scampo[22] = 'saiu21idresponsable';
			$scampo[23] = 'saiu21solucion';
			$sdato[1] = $DATA['saiu21dia'];
			$sdato[2] = $DATA['saiu21hora'];
			$sdato[3] = $DATA['saiu21minuto'];
			$sdato[4] = $DATA['saiu21idcorreo'];
			$sdato[5] = $DATA['saiu21idsolicitante'];
			$sdato[6] = $DATA['saiu21tipointeresado'];
			$sdato[7] = $DATA['saiu21tiposolicitud'];
			$sdato[8] = $DATA['saiu21temasolicitud'];
			$sdato[9] = $DATA['saiu21idzona'];
			$sdato[10] = $DATA['saiu21idcentro'];
			$sdato[11] = $DATA['saiu21codpais'];
			$sdato[12] = $DATA['saiu21coddepto'];
			$sdato[13] = $DATA['saiu21codciudad'];
			$sdato[14] = $DATA['saiu21idescuela'];
			$sdato[15] = $DATA['saiu21idprograma'];
			$sdato[16] = $DATA['saiu21idperiodo'];
			$sdato[17] = $DATA['saiu21numorigen'];
			$sdato[18] = $saiu21detalle;
			$sdato[19] = $DATA['saiu21horafin'];
			$sdato[20] = $DATA['saiu21minutofin'];
			$sdato[21] = $DATA['saiu21paramercadeo'];
			$sdato[22] = $DATA['saiu21idresponsable'];
			$sdato[23] = $DATA['saiu21solucion'];
			$iNumCamposMod = 23;
			$sWhere = 'saiu21id=' . $DATA['saiu21id'] . '';
			$sSQL = 'SELECT * FROM saiu21directa WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE saiu21directa SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE saiu21directa SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3021 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3021] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['saiu21id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu21id'], $sdetalle, $objDB);
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
function f3021_db_Eliminar($saiu21id, $objDB, $bDebug = false)
{
	$iCodModulo = 3021;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3021;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu21id = numeros_validar($saiu21id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM saiu21directa WHERE saiu21id=' . $saiu21id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $saiu21id . '}';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3021';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['saiu21id'] . ' LIMIT 0, 1';
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
		$sWhere = 'saiu21id=' . $saiu21id . '';
		//$sWhere = 'saiu21consec=' . $filabase['saiu21consec'] . ' AND saiu21tiporadicado=' . $filabase['saiu21tiporadicado'] . ' AND saiu21mes=' . $filabase['saiu21mes'] . ' AND saiu21agno=' . $filabase['saiu21agno'] . '';
		$sSQL = 'DELETE FROM saiu21directa WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu21id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f3021_TituloBusqueda()
{
	require './app.php';
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_3021;
	return $ETI['titulo_busca_3021'];
}
function f3021_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3021;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b3021nombre" name="b3021nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f3021_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'let sCampo = window.document.frmedita.scampobusca.value;
	let params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b3021nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f3021_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3021;
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
	$sBotones = '<input id="paginaf3021" name="paginaf3021" type="hidden" value="' . $pagina . '" />
	<input id="lppf3021" name="lppf3021" type="hidden" value="' . $lineastabla . '" />';
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
	$sTitulos = 'Agno, Mes, Tiporadicado, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Estado, Correo, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos, Solucion, Caso';
	$sCampos = 'SELECT TB.saiu21agno, TB.saiu21mes, T3.saiu16nombre, TB.saiu21consec, TB.saiu21id, TB.saiu21origenagno, TB.saiu21origenmes, TB.saiu21origenid, TB.saiu21dia, TB.saiu21hora, TB.saiu21minuto, T12.saiu11nombre, T13.saiu57titulo, T14.unad11razonsocial AS C14_nombre, T15.bita07nombre, T16.saiu01titulo, T17.saiu02titulo, T18.saiu03titulo, T19.unad23nombre, T20.unad24nombre, T21.unad18nombre, T22.unad19nombre, T23.unad20nombre, T24.core12nombre, T25.core09nombre, T26.exte02nombre, TB.saiu21numorigen, TB.saiu21idpqrs, TB.saiu21detalle, TB.saiu21horafin, TB.saiu21minutofin, TB.saiu21paramercadeo, T33.unad11razonsocial AS C33_nombre, TB.saiu21tiemprespdias, TB.saiu21tiempresphoras, TB.saiu21tiemprespminutos, TB.saiu21solucion, TB.saiu21idcaso, TB.saiu21tiporadicado, TB.saiu21estado, TB.saiu21idcorreo, TB.saiu21idsolicitante, T14.unad11tipodoc AS C14_td, T14.unad11doc AS C14_doc, TB.saiu21tipointeresado, TB.saiu21clasesolicitud, TB.saiu21tiposolicitud, TB.saiu21temasolicitud, TB.saiu21idzona, TB.saiu21idcentro, TB.saiu21codpais, TB.saiu21coddepto, TB.saiu21codciudad, TB.saiu21idescuela, TB.saiu21idprograma, TB.saiu21idperiodo, TB.saiu21idresponsable, T33.unad11tipodoc AS C33_td, T33.unad11doc AS C33_doc';
	$sConsulta = 'FROM saiu21directa AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T12, saiu57correos AS T13, unad11terceros AS T14, bita07tiposolicitante AS T15, saiu01claseser AS T16, saiu02tiposol AS T17, saiu03temasol AS T18, unad23zona AS T19, unad24sede AS T20, unad18pais AS T21, unad19depto AS T22, unad20ciudad AS T23, core12escuela AS T24, core09programa AS T25, exte02per_aca AS T26, unad11terceros AS T33 
	WHERE ' . $sSQLadd1 . ' TB.saiu21tiporadicado=T3.saiu16id AND TB.saiu21estado=T12.saiu11id AND TB.saiu21idcorreo=T13.saiu57id AND TB.saiu21idsolicitante=T14.unad11id AND TB.saiu21tipointeresado=T15.bita07id AND TB.saiu21clasesolicitud=T16.saiu01id AND TB.saiu21tiposolicitud=T17.saiu02id AND TB.saiu21temasolicitud=T18.saiu03id AND TB.saiu21idzona=T19.unad23id AND TB.saiu21idcentro=T20.unad24id AND TB.saiu21codpais=T21.unad18codigo AND TB.saiu21coddepto=T22.unad19codigo AND TB.saiu21codciudad=T23.unad20codigo AND TB.saiu21idescuela=T24.core12id AND TB.saiu21idprograma=T25.core09id AND TB.saiu21idperiodo=T26.exte02id AND TB.saiu21idresponsable=T33.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.saiu21agno, TB.saiu21mes, TB.saiu21tiporadicado, TB.saiu21consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
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
			$sSQLLimitado = $objDB->sSQLPaginar($sCampos, $sConsulta, $sOrden, $rbase, $lineastabla);
			$tabladetalle = $objDB->ejecutasql($sSQLLimitado);
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['saiu21agno'] . '</b></td>
	<td><b>' . $ETI['saiu21mes'] . '</b></td>
	<td><b>' . $ETI['saiu21tiporadicado'] . '</b></td>
	<td><b>' . $ETI['saiu21consec'] . '</b></td>
	<td><b>' . $ETI['saiu21origenagno'] . '</b></td>
	<td><b>' . $ETI['saiu21origenmes'] . '</b></td>
	<td><b>' . $ETI['saiu21origenid'] . '</b></td>
	<td><b>' . $ETI['saiu21dia'] . '</b></td>
	<td><b>' . $ETI['saiu21hora'] . '</b></td>
	<td><b>' . $ETI['saiu21estado'] . '</b></td>
	<td><b>' . $ETI['saiu21idcorreo'] . '</b></td>
	<td colspan="2"><b>' . $ETI['saiu21idsolicitante'] . '</b></td>
	<td><b>' . $ETI['saiu21tipointeresado'] . '</b></td>
	<td><b>' . $ETI['saiu21clasesolicitud'] . '</b></td>
	<td><b>' . $ETI['saiu21tiposolicitud'] . '</b></td>
	<td><b>' . $ETI['saiu21temasolicitud'] . '</b></td>
	<td><b>' . $ETI['saiu21idzona'] . '</b></td>
	<td><b>' . $ETI['saiu21idcentro'] . '</b></td>
	<td><b>' . $ETI['saiu21codpais'] . '</b></td>
	<td><b>' . $ETI['saiu21coddepto'] . '</b></td>
	<td><b>' . $ETI['saiu21codciudad'] . '</b></td>
	<td><b>' . $ETI['saiu21idescuela'] . '</b></td>
	<td><b>' . $ETI['saiu21idprograma'] . '</b></td>
	<td><b>' . $ETI['saiu21idperiodo'] . '</b></td>
	<td><b>' . $ETI['saiu21numorigen'] . '</b></td>
	<td><b>' . $ETI['saiu21idpqrs'] . '</b></td>
	<td><b>' . $ETI['saiu21detalle'] . '</b></td>
	<td><b>' . $ETI['saiu21horafin'] . '</b></td>
	<td><b>' . $ETI['saiu21paramercadeo'] . '</b></td>
	<td colspan="2"><b>' . $ETI['saiu21idresponsable'] . '</b></td>
	<td><b>' . $ETI['saiu21tiemprespdias'] . '</b></td>
	<td><b>' . $ETI['saiu21tiempresphoras'] . '</b></td>
	<td><b>' . $ETI['saiu21tiemprespminutos'] . '</b></td>
	<td><b>' . $ETI['saiu21solucion'] . '</b></td>
	<td><b>' . $ETI['saiu21idcaso'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['saiu21id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_saiu21hora =  $sPrefijo . html_TablaHoraMin($filadet['saiu21hora'], $filadet['saiu21minuto']) . $sSufijo;
		$et_saiu21horafin =  $sPrefijo . html_TablaHoraMin($filadet['saiu21horafin'], $filadet['saiu21minutofin']) . $sSufijo;
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . $filadet['saiu21agno'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21mes'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu16nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21origenagno'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21origenmes'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21origenid'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21dia'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu21hora . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu57titulo']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C14_td'] . ' ' . $filadet['C14_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C14_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['bita07nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu01titulo']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu02titulo']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu03titulo']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21codpais'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21coddepto'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21codciudad'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['saiu21numorigen']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21idpqrs'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21detalle'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_saiu21horafin . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21paramercadeo'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C33_td'] . ' ' . $filadet['C33_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C33_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21tiemprespdias'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21tiempresphoras'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21tiemprespminutos'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21solucion'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['saiu21idcaso'] . $sSufijo . '</td>
		<td></td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return cadena_codificar($res);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

