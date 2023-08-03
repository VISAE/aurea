<?php
/*
--- © Cristhiam Dario Silva Chavez - UNAD - 2022 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.4b viernes, 21 de octubre de 2022
--- 3801 cipa01oferta
*/
/** Archivo lib3801.php.
 * Libreria 3801 cipa01oferta.
 * @author Cristhiam Dario Silva Chavez - cristhiam.silva@unad.edu.co
 * @date viernes, 21 de octubre de 2022
 */
function f3801_HTMLComboV2_cipa01periodo($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$idEntidad = Traer_Entidad();
	$objCombos->nuevo('cipa01periodo', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->iAncho=600;
	$objCombos->sAccion = 'cambiapagina()';
	$iPeriodoBase = 0;
	if ($idEntidad == 0) {
		$iPeriodoBase = 1140;
	}	
	$sSQL = f146_ConsultaCombo('exte02id>'.$iPeriodoBase);
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3801_HTMLComboV2_cipa01alcance($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01alcance', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$sSQL = 'SELECT cipa13id AS id, cipa13nombre AS nombre FROM cipa13alcance ORDER BY cipa13id';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3801_HTMLComboV2_cipa01clase($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01clase', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$sSQL = 'SELECT cipa14id AS id, cipa14nombre AS nombre FROM cipa14clasecipas ORDER BY cipa14nombre';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3801_HTMLComboV2_cipa01programa($objDB, $objCombos, $valor, $vrcipa01escuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01programa', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	$sSQL = '';
	if ((int)$vrcipa01escuela != 0) {
		$objCombos->iAncho = 400;
		$sSQL = 'SELECT core09id AS id, CONCAT(core09nombre, " [", core09codigo, "]") AS nombre 
		FROM core09programa 
		WHERE core09idescuela=' . $vrcipa01escuela . '
		ORDER BY core09activo DESC, core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3801_HTMLComboV2_cipa01centro($objDB, $objCombos, $valor, $vrcipa01zona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01centro', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vrcipa01zona != 0) {
		$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre 
		FROM unad24sede 
		WHERE unad24idzona=' . $vrcipa01zona . '
		ORDER BY unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3801_HTMLComboV2_cipa01idcurso($objDB, $objCombos, $valor, $vrcipa01periodo)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01idcurso', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vrcipa01periodo != 0) {
		$objCombos->iAncho = 400;
		$sIds = '-99';
		$sSQL = 'SELECT ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca=' . $vrcipa01periodo . ' AND ofer08estadooferta=1';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['ofer08idcurso'];
		}
		$sSQL = 'SELECT unad40id AS id, CONCAT(unad40titulo, " - ", unad40nombre) AS nombre 
		FROM unad40curso 
		WHERE unad40id IN (' . $sIds . ')
		ORDER BY unad40nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3801_Combocipa01programa($aParametros)
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
	$html_cipa01programa = f3801_HTMLComboV2_cipa01programa($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cipa01programa', 'innerHTML', $html_cipa01programa);
	$objResponse->call('$("#cipa01programa").chosen()');
	return $objResponse;
}
function f3801_Combocipa01centro($aParametros)
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
	$html_cipa01centro = f3801_HTMLComboV2_cipa01centro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cipa01centro', 'innerHTML', $html_cipa01centro);
	//$objResponse->call('$("#cipa01centro").chosen()');
	return $objResponse;
}
function f3801_Combocipa01idcurso($aParametros)
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
	$html_cipa01idcurso = f3801_HTMLComboV2_cipa01idcurso($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cipa01idcurso', 'innerHTML', $html_cipa01idcurso);
	$objResponse->call('$("#cipa01idcurso").chosen()');
	return $objResponse;
}
function elimina_archivo_cipa01idarchivoeviden($idPadre, $bDebug = false)
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
		archivo_eliminar('cipa01oferta', 'cipa01id', 'cipa01idorigeneviden', 'cipa01idarchivoeviden', $idPadre, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_cipa01idarchivoeviden");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0);");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3801_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$cipa01periodo = numeros_validar($datos[1]);
	if ($cipa01periodo == '') {
		$bHayLlave = false;
	}
	$cipa01consec = numeros_validar($datos[2]);
	if ($cipa01consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM cipa01oferta WHERE cipa01periodo=' . $cipa01periodo . ' AND cipa01consec=' . $cipa01consec . '';
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
function f3801_Busquedas($aParametros)
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
	$mensajes_3801 = 'lg/lg_3801_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3801)) {
		$mensajes_3801 = 'lg/lg_3801_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3801;
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
		case 'cipa01iddocente':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['cipa01iddocente_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3801);
			break;
		case 'cipa01iddocente2':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['cipa01iddocente2_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3801);
			break;
		case 'cipa01iddocente3':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['cipa01iddocente3_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3801);
			break;
		case 'cipa01idmonitor':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['cipa01idmonitor_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3801);
			break;
		case 'cipa01idsupervisor':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['cipa01idsupervisor_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3801);
			break;
		case 'cipa03idinscrito':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['cipa03idinscrito_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3801);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_3801'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f3801_HtmlBusqueda($aParametros)
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
		case 'cipa01iddocente':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'cipa01iddocente2':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'cipa01iddocente3':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'cipa01idmonitor':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'cipa01idsupervisor':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'cipa03idinscrito':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f3801_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_3800 = 'lg/lg_3800_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3800)) {
		$mensajes_3800 = 'lg/lg_3800_es.php';
	}
	require $mensajes_3800;
	*/
	$mensajes_3801 = 'lg/lg_3801_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3801)) {
		$mensajes_3801 = 'lg/lg_3801_es.php';
	}
	require $mensajes_3801;
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
	for ($k = 103; $k <= 113; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	$idTercero = $aParametros[100];
	$sDebug = '';
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		$bNombre = trim($aParametros[103]);
		$bListar = numeros_validar($aParametros[104]);
		$bPeriodo = numeros_validar($aParametros[105]);
		$bCurso = numeros_validar($aParametros[106]);
		$bEscuela = numeros_validar($aParametros[107]);
		$bPrograma = numeros_validar($aParametros[108]);
		$bZona = numeros_validar($aParametros[109]);
		$bCentro = numeros_validar($aParametros[110]);
		$bClase = numeros_validar($aParametros[111]);
		$bAlcance = numeros_validar($aParametros[112]);
		$bEstado = numeros_validar($aParametros[113]);
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
	$sBotones = '<input id="paginaf3801" name="paginaf3801" type="hidden" value="' . $pagina . '"/>
	<input id="lppf3801" name="lppf3801" type="hidden" value="' . $lineastabla . '"/>';
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
	$aEstado=array();
	$sSQL = 'SELECT cipa11id, cipa11nombre FROM cipa11estado';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['cipa11id']] = cadena_notildes($fila['cipa11nombre']);
	}
	if (true) {
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
		$sSQLadd = '';
		$sSQLadd1 = '';
		/*
		if ($aParametros[104] != '') {
			$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[104] . '%"';
		}
		*/
		switch($bListar){
			case 1:
				$sSQLadd1 = $sSQLadd1 . '((TB.cipa01iddocente='. $idTercero.') OR (TB.cipa01iddocente2='. $idTercero.') OR (TB.cipa01iddocente3='. $idTercero.') OR (TB.cipa01idsupervisor='. $idTercero.')) AND ';
				break;
		}
		if ($bPeriodo != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa01periodo=' . $bPeriodo . ' AND ';
		}
		if ($bCurso != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa01idcurso=' . $bCurso . ' AND ';
		}
		if ($bEscuela != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa01escuela=' . $bEscuela . ' AND ';
		}
		if ($bPrograma != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa01programa=' . $bPrograma . ' AND ';
		}
		if ($bZona != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa01zona=' . $bZona . ' AND ';
		}
		if ($bCentro != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa01centro=' . $bCentro . ' AND ';
		}
		if ($bClase != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa01clase=' . $bClase . ' AND ';
		}
		if ($bAlcance!= '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa01alcance=' . $bAlcance . ' AND ';
		}
		if ($bEstado != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa01estado=' . $bEstado . ' AND ';
		}
		if ($bNombre != '') {
			$sBase = strtoupper($bNombre);
			$aNoms=explode(' ', $sBase);
			for ($k = 1; $k <= count($aNoms); $k++) {
				$sCadena = $aNoms[$k - 1];
					if ($sCadena != '') {
					//$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
					$sSQLadd1 = $sSQLadd1 . 'TB.cipa01nombre LIKE "%' . $sCadena . '%" AND ';
				}
			}
		}
	}
	//-- Area para saltar comparaciones con los archivos tipo e
	//-- Fin del area no comparada
	$sTitulos = 'Periodo, Consec, Id, Nombre, Alcance, Clase, Estado, Escuela, Programa, Zona, Centro, Curso, Tematica, Est_proyectados, Est_asistentes, Docente, Docente2, Docente3, Monitor, Supervisor, Num_valoraciones, Valoracion';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';

	/*
	$sCampos = 'SELECT TB.cipa01consec, TB.cipa01id, TB.cipa01nombre, T11.cipa11nombre, 		 
	T40.unad40titulo, T40.unad40nombre, TB.cipa01periodo, 
	T12.core12nombre, T09.core09nombre, T23.unad23nombre, T24.unad24nombre, TB.cipa01estado, 
	T14.cipa14nombre, T13.cipa13nombre';
	$sConsulta = 'FROM cipa01oferta AS TB, cipa11estado AS T11, unad40curso AS 
	T40, core12escuela AS T12, core09programa AS T09, unad23zona AS T23, unad24sede AS T24, 
	cipa14clasecipas AS T14, cipa13alcance AS T13
	WHERE ' . $sSQLadd1 . ' TB.cipa01estado=T11.cipa11id AND 
	TB.cipa01idcurso=T40.unad40id AND TB.cipa01escuela=T12.core12id AND TB.cipa01programa=T09.core09id 
	AND TB.cipa01zona=T23.unad23id AND TB.cipa01centro=T24.unad24id AND TB.cipa01clase=T14.cipa14id AND 
	TB.cipa01alcance=T13.cipa13id' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.cipa01periodo DESC, TB.cipa01estado, TB.cipa01idcurso, TB.cipa01consec DESC';
	*/

	/*
	$sCampos = 'SELECT T1.exte02nombre, TB.cipa01consec, TB.cipa01id, TB.cipa01nombre, T5.cipa13nombre, T6.cipa14nombre, TB.cipa01estado, T8.core12nombre, T9.core09nombre, T10.unad23nombre, T11.unad24nombre, T12.unad40nombre, TB.cipa01tematica, TB.cipa01est_proyectados, TB.cipa01est_asistentes, T16.unad11razonsocial AS C16_nombre, T17.unad11razonsocial AS C17_nombre, T18.unad11razonsocial AS C18_nombre, T19.unad11razonsocial AS C19_nombre, T20.unad11razonsocial AS C20_nombre, TB.cipa01num_valoraciones, TB.cipa01valoracion, TB.cipa01periodo, TB.cipa01alcance, TB.cipa01clase, TB.cipa01escuela, TB.cipa01programa, TB.cipa01zona, TB.cipa01centro, TB.cipa01idcurso, TB.cipa01iddocente, T16.unad11tipodoc AS C16_td, T16.unad11doc AS C16_doc, TB.cipa01iddocente2, T17.unad11tipodoc AS C17_td, T17.unad11doc AS C17_doc, TB.cipa01iddocente3, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, TB.cipa01idmonitor, T19.unad11tipodoc AS C19_td, T19.unad11doc AS C19_doc, TB.cipa01idsupervisor, T20.unad11tipodoc AS C20_td, T20.unad11doc AS C20_doc';
	$sConsulta = 'FROM cipa01oferta AS TB, exte02per_aca AS T1, cipa13alcance AS T5, cipa14clasecipas AS T6, core12escuela AS T8, core09programa AS T9, unad23zona AS T10, unad24sede AS T11, unad40curso AS T12, unad11terceros AS T16, unad11terceros AS T17, unad11terceros AS T18, unad11terceros AS T19, unad11terceros AS T20 
	WHERE ' . $sSQLadd1 . ' TB.cipa01periodo=T1.exte02id AND TB.cipa01alcance=T5.cipa13id AND TB.cipa01clase=T6.cipa14id AND TB.cipa01escuela=T8.core12id AND TB.cipa01programa=T9.core09id AND TB.cipa01zona=T10.unad23id AND TB.cipa01centro=T11.unad24id AND TB.cipa01idcurso=T12.unad40id AND TB.cipa01iddocente=T16.unad11id AND TB.cipa01iddocente2=T17.unad11id AND TB.cipa01iddocente3=T18.unad11id AND TB.cipa01idmonitor=T19.unad11id AND TB.cipa01idsupervisor=T20.unad11id ' . $sSQLadd . '';
	*/
	$sCampos = 'SELECT TB.cipa01consec, TB.cipa01id, TB.cipa01nombre, TB.cipa01estado, T12.unad40titulo, T12.unad40nombre, 
	TB.cipa01periodo, TB.cipa01idcurso';
	$sConsulta = 'FROM cipa01oferta AS TB, unad40curso AS T12 
	WHERE ' . $sSQLadd1 . ' TB.cipa01idcurso=T12.unad40id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.cipa01periodo DESC, TB.cipa01estado, TB.cipa01idcurso, TB.cipa01consec DESC';
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
	$sErrConsulta = '<input id="consulta_3801" name="consulta_3801" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3801" name="titulos_3801" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3801: ' . $sSQL . '<br>';
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
	//<td><b>' . $ETI['cipa01idcurso'] . '</b></td>
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['cipa01consec'] . '</b></td>
	<td><b>' . $ETI['cipa01nombre'] . '</b></td>
	<td><b>' . $ETI['cipa01estado'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3801', $registros, $lineastabla, $pagina, 'paginarf3801()') . '
	' . html_lpp('lppf3801', $lineastabla, 'paginarf3801()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	$idPeriodo = -99;
	$idCurso = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idPeriodo != $filadet['cipa01periodo']) {
			$idPeriodo = $filadet['cipa01periodo'];
			//	<td><b>' . $ETI['cipa01periodo'] . '</b></td>
			list($sNomPeriodo)= f146_NombrePeriodo($idPeriodo, $objDB, true);
			$res = $res . '<tr class="fondoazul">
			<td colspan="4" align="center">' . $ETI['cipa01periodo'] . ': <b>' . $sNomPeriodo . '</b></td>
			</tr>';
		}
		if ($idCurso != $filadet['cipa01idcurso']) {
			$idCurso = $filadet['cipa01idcurso'];
			if ($idCurso != 0) {
				$res = $res . '<tr class="fondoazul">
				<td colspan="4" align="center">' . $ETI['cipa01idcurso'] . ': <b>' . $filadet['unad40titulo'] . ' - ' . cadena_notildes($filadet['unad40nombre']) . '</b></td>
				</tr>';
			}
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['cipa01estado']) {
			case 1: // Ofertado
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
				break;
			case 7: // Terminado
				$sPrefijo = '<span class="verde">';
				$sSufijo = '</span>';
				break;
			case 8: // Cancelado
			case 9: // Nulo
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span>';
				break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_cipa01estado = $aEstado[$filadet['cipa01estado']];
		/*
		$et_cipa01iddocente_doc = '';
		$et_cipa01iddocente_nombre = '';
		if ($filadet['cipa01iddocente'] != 0) {
			$et_cipa01iddocente_doc = $sPrefijo . $filadet['C16_td'] . ' ' . $filadet['C16_doc'] . $sSufijo;
			$et_cipa01iddocente_nombre = $sPrefijo . cadena_notildes($filadet['C16_nombre']) . $sSufijo;
		}
		$et_cipa01iddocente2_doc = '';
		$et_cipa01iddocente2_nombre = '';
		if ($filadet['cipa01iddocente2'] != 0) {
			$et_cipa01iddocente2_doc = $sPrefijo . $filadet['C17_td'] . ' ' . $filadet['C17_doc'] . $sSufijo;
			$et_cipa01iddocente2_nombre = $sPrefijo . cadena_notildes($filadet['C17_nombre']) . $sSufijo;
		}
		$et_cipa01iddocente3_doc = '';
		$et_cipa01iddocente3_nombre = '';
		if ($filadet['cipa01iddocente3'] != 0) {
			$et_cipa01iddocente3_doc = $sPrefijo . $filadet['C18_td'] . ' ' . $filadet['C18_doc'] . $sSufijo;
			$et_cipa01iddocente3_nombre = $sPrefijo . cadena_notildes($filadet['C18_nombre']) . $sSufijo;
		}
		$et_cipa01idmonitor_doc = '';
		$et_cipa01idmonitor_nombre = '';
		if ($filadet['cipa01idmonitor'] != 0) {
			$et_cipa01idmonitor_doc = $sPrefijo . $filadet['C19_td'] . ' ' . $filadet['C19_doc'] . $sSufijo;
			$et_cipa01idmonitor_nombre = $sPrefijo . cadena_notildes($filadet['C19_nombre']) . $sSufijo;
		}
		$et_cipa01idsupervisor_doc = '';
		$et_cipa01idsupervisor_nombre = '';
		if ($filadet['cipa01idsupervisor'] != 0) {
			$et_cipa01idsupervisor_doc = $sPrefijo . $filadet['C20_td'] . ' ' . $filadet['C20_doc'] . $sSufijo;
			$et_cipa01idsupervisor_nombre = $sPrefijo . cadena_notildes($filadet['C20_nombre']) . $sSufijo;
		}
		*/
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3801(' . $filadet['cipa01id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['cipa01consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['cipa01nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_cipa01estado . $sSufijo . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3801_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3801_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3801detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3801_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['cipa01iddocente_td'] = $APP->tipo_doc;
	$DATA['cipa01iddocente_doc'] = '';
	$DATA['cipa01iddocente2_td'] = $APP->tipo_doc;
	$DATA['cipa01iddocente2_doc'] = '';
	$DATA['cipa01iddocente3_td'] = $APP->tipo_doc;
	$DATA['cipa01iddocente3_doc'] = '';
	$DATA['cipa01idmonitor_td'] = $APP->tipo_doc;
	$DATA['cipa01idmonitor_doc'] = '';
	$DATA['cipa01idsupervisor_td'] = $APP->tipo_doc;
	$DATA['cipa01idsupervisor_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'cipa01periodo=' . $DATA['cipa01periodo'] . ' AND cipa01consec=' . $DATA['cipa01consec'] . '';
	} else {
		$sSQLcondi = 'cipa01id=' . $DATA['cipa01id'] . '';
	}
	$sSQL = 'SELECT * FROM cipa01oferta WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['cipa01periodo'] = $fila['cipa01periodo'];
		$DATA['cipa01consec'] = $fila['cipa01consec'];
		$DATA['cipa01id'] = $fila['cipa01id'];
		$DATA['cipa01nombre'] = $fila['cipa01nombre'];
		$DATA['cipa01alcance'] = $fila['cipa01alcance'];
		$DATA['cipa01clase'] = $fila['cipa01clase'];
		$DATA['cipa01estado'] = $fila['cipa01estado'];
		$DATA['cipa01escuela'] = $fila['cipa01escuela'];
		$DATA['cipa01programa'] = $fila['cipa01programa'];
		$DATA['cipa01zona'] = $fila['cipa01zona'];
		$DATA['cipa01centro'] = $fila['cipa01centro'];
		$DATA['cipa01idcurso'] = $fila['cipa01idcurso'];
		$DATA['cipa01tematica'] = $fila['cipa01tematica'];
		$DATA['cipa01est_proyectados'] = $fila['cipa01est_proyectados'];
		$DATA['cipa01est_asistentes'] = $fila['cipa01est_asistentes'];
		$DATA['cipa01iddocente'] = $fila['cipa01iddocente'];
		$DATA['cipa01iddocente2'] = $fila['cipa01iddocente2'];
		$DATA['cipa01iddocente3'] = $fila['cipa01iddocente3'];
		$DATA['cipa01idmonitor'] = $fila['cipa01idmonitor'];
		$DATA['cipa01idsupervisor'] = $fila['cipa01idsupervisor'];
		$DATA['cipa01num_valoraciones'] = $fila['cipa01num_valoraciones'];
		$DATA['cipa01valoracion'] = $fila['cipa01valoracion'];
		$DATA['cipa01fechatermina'] = $fila['cipa01fechatermina'];
		$DATA['cipa01proximafecha'] = $fila['cipa01proximafecha'];
		$DATA['cipa01horatermina'] = $fila['cipa01horatermina'];
		$DATA['cipa01mintermina'] = $fila['cipa01mintermina'];
		$DATA['cipa01idorigeneviden'] = $fila['cipa01idorigeneviden'];
		$DATA['cipa01idarchivoeviden'] = $fila['cipa01idarchivoeviden'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta3801'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3801_Cerrar($cipa01id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	$sSQL = 'SELECT cipa01periodo, cipa01alcance, cipa01idcurso, cipa01zona, cipa01iddocente 
	FROM cipa01oferta 
	WHERE cipa01id=' . $cipa01id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0){
		$fila = $objDB->sf($tabla);
		$idPeriodo = $fila['cipa01periodo'];
		$idCurso = $fila['cipa01idcurso'];
		$idTutor = $fila['cipa01iddocente'];
		$idZona = $fila['cipa01zona'];
		$bPreinscribir = false;
		$sTablaAdd = '';
		$sCondiAdd = '';
		switch($fila['cipa01alcance']) {
			case 31: // Por curso
				$bPreinscribir = true;
				break;
			case 32: // Por curso - Zona
				$bPreinscribir = true;
				$sTablaAdd = ', core16actamatricula AS T1';
				$sCondiAdd = ' AND TB.core04idmatricula=T1.core16id  AND T1.core16idzona=' . $idZona . '';
				break;
			case 33: // Por curso - tutor.
				$bPreinscribir = true;
				$sCondiAdd = ' AND TB.core04idtutor=' . $idTutor . '';
				break;
		}
		if ($bPreinscribir) {
			// Vemos que exista la tabla contenedora.
			$sTabla03 = 'cipa03cupos_' . $idPeriodo;
			if (!$objDB->bexistetabla($sTabla03)){
				f3803_IniciarContenedor($idPeriodo, $objDB, $bDebug);
			}
			$sCampos3803 = 'cipa03idoferta, cipa03idinscrito, cipa03id, cipa03asistencia, cipa03jornada_1, 
			cipa03jornada_2, cipa03jornada_3, cipa03jornada_4, cipa03jornada_5, cipa03idmatricula, 
			cipa03valoracion, cipa03retroalimentacion';
			$cipa03id = tabla_consecutivo($sTabla03, 'cipa03id', '', $objDB);
			//Recorrer la tabla core04
			$sSQL = $objDB->sSQLListaTablas('core04%');
			$tablac = $objDB->ejecutasql($sSQL);
			while ($filac = $objDB->sf($tablac)) {
				$iContenedor = substr($filac[0], 16);
				if ($iContenedor != 0) {
					$sSQL = 'SELECT TB.core04tercero, TB.core04idmatricula
					FROM core04matricula_' . $iContenedor . ' AS TB' . $sTablaAdd . ' 
					WHERE TB.core04peraca=' . $idPeriodo . ' AND TB.core04idcurso=' . $idCurso . ' AND TB.core04estado NOT IN (1,9,10) 
					' . $sCondiAdd . '';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Consultando: ' . $sSQL . '<br>';
					}
					$tabla = $objDB->ejecutasql($sSQL);
					while ($fila = $objDB->sf($tabla)) {
						$sValores3803 = '' . $cipa01id . ', ' . $fila['core04tercero'] . ', ' . $cipa03id . ', -2, -1, 
						-1, -1, -1, -1, ' . $fila['core04idmatricula'] . ', 
						-1, ""';
						$sSQL = 'INSERT INTO ' . $sTabla03 . ' (' . $sCampos3803 . ') VALUES (' . $sValores3803 . ');';
						$result = $objDB->ejecutasql($sSQL);
						if ($result == false) {
						} else {
							$cipa03id++;
						}
					}
				}
			}
			//Termina de recorrer el contenedor
		}
	}
	return array($sInfo, $sDebug);
}
function f3801_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3801;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3801 = 'lg/lg_3801_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3801)) {
		$mensajes_3801 = 'lg/lg_3801_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3801;
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
	if (isset($DATA['cipa01periodo']) == 0) {
		$DATA['cipa01periodo'] = '';
	}
	if (isset($DATA['cipa01consec']) == 0) {
		$DATA['cipa01consec'] = '';
	}
	if (isset($DATA['cipa01id']) == 0) {
		$DATA['cipa01id'] = '';
	}
	if (isset($DATA['cipa01nombre']) == 0) {
		$DATA['cipa01nombre'] = '';
	}
	if (isset($DATA['cipa01alcance']) == 0) {
		$DATA['cipa01alcance'] = '';
	}
	if (isset($DATA['cipa01clase']) == 0) {
		$DATA['cipa01clase'] = '';
	}
	if (isset($DATA['cipa01estado']) == 0) {
		$DATA['cipa01estado'] = '';
	}
	if (isset($DATA['cipa01escuela']) == 0) {
		$DATA['cipa01escuela'] = '';
	}
	if (isset($DATA['cipa01programa']) == 0) {
		$DATA['cipa01programa'] = '';
	}
	if (isset($DATA['cipa01zona']) == 0) {
		$DATA['cipa01zona'] = '';
	}
	if (isset($DATA['cipa01centro']) == 0) {
		$DATA['cipa01centro'] = '';
	}
	if (isset($DATA['cipa01idcurso']) == 0) {
		$DATA['cipa01idcurso'] = '';
	}
	if (isset($DATA['cipa01tematica']) == 0) {
		$DATA['cipa01tematica'] = '';
	}
	if (isset($DATA['cipa01est_proyectados']) == 0) {
		$DATA['cipa01est_proyectados'] = '';
	}
	if (isset($DATA['cipa01iddocente']) == 0) {
		$DATA['cipa01iddocente'] = '';
	}
	if (isset($DATA['cipa01iddocente2']) == 0) {
		$DATA['cipa01iddocente2'] = '';
	}
	if (isset($DATA['cipa01iddocente3']) == 0) {
		$DATA['cipa01iddocente3'] = '';
	}
	if (isset($DATA['cipa01idmonitor']) == 0) {
		$DATA['cipa01idmonitor'] = '';
	}
	if (isset($DATA['cipa01idsupervisor']) == 0) {
		$DATA['cipa01idsupervisor'] = '';
	}
	*/
	if (isset($DATA['cipa01num_valoraciones']) == 0) {
		$DATA['cipa01num_valoraciones'] = 0;
	}
	if (isset($DATA['cipa01valoracion']) == 0) {
		$DATA['cipa01valoracion'] = '';
	}
	if (isset($DATA['cipa01fechatermina']) == 0) {
		$DATA['cipa01fechatermina'] = 0;
	}
	if (isset($DATA['cipa01proximafecha']) == 0) {
		$DATA['cipa01proximafecha'] = 0;
	}
	if (isset($DATA['cipa01horatermina']) == 0) {
		$DATA['cipa01horatermina'] = 0;
	}
	if (isset($DATA['cipa01mintermina']) == 0) {
		$DATA['cipa01mintermina'] = 0;
	}
	$DATA['cipa01periodo'] = numeros_validar($DATA['cipa01periodo']);
	$DATA['cipa01consec'] = numeros_validar($DATA['cipa01consec']);
	$DATA['cipa01nombre'] = htmlspecialchars(trim($DATA['cipa01nombre']));
	$DATA['cipa01alcance'] = numeros_validar($DATA['cipa01alcance']);
	$DATA['cipa01clase'] = numeros_validar($DATA['cipa01clase']);
	$DATA['cipa01escuela'] = numeros_validar($DATA['cipa01escuela']);
	$DATA['cipa01programa'] = numeros_validar($DATA['cipa01programa']);
	$DATA['cipa01zona'] = numeros_validar($DATA['cipa01zona']);
	$DATA['cipa01centro'] = numeros_validar($DATA['cipa01centro']);
	$DATA['cipa01idcurso'] = numeros_validar($DATA['cipa01idcurso']);
	$DATA['cipa01tematica'] = htmlspecialchars(trim($DATA['cipa01tematica']));
	$DATA['cipa01est_proyectados'] = numeros_validar($DATA['cipa01est_proyectados']);
	$DATA['cipa01idorigeneviden'] = numeros_validar($DATA['cipa01idorigeneviden']);
	$DATA['cipa01idarchivoeviden'] = numeros_validar($DATA['cipa01idarchivoeviden']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['cipa01alcance'] == '') {
		$DATA['cipa01alcance'] = 0;
	}
	if ($DATA['cipa01clase'] == '') {
		$DATA['cipa01clase'] = 0;
	}
	*/
	if ($DATA['cipa01estado'] == '') {
		$DATA['cipa01estado'] = 0;
	}
		/*
	if ($DATA['cipa01escuela'] == '') {
		$DATA['cipa01escuela'] = 0;
	}
	if ($DATA['cipa01programa'] == '') {
		$DATA['cipa01programa'] = 0;
	}
	if ($DATA['cipa01zona'] == '') {
		$DATA['cipa01zona'] = 0;
	}
	if ($DATA['cipa01centro'] == '') {
		$DATA['cipa01centro'] = 0;
	}
	if ($DATA['cipa01idcurso'] == '') {
		$DATA['cipa01idcurso'] = 0;
	}
	if ($DATA['cipa01est_proyectados'] == '') {
		$DATA['cipa01est_proyectados'] = 0;
	}
	if ($DATA['cipa01est_asistentes'] == '') {
		$DATA['cipa01est_asistentes'] = 0;
	}
	if ($DATA['cipa01num_valoraciones'] == '') {
		$DATA['cipa01num_valoraciones'] = 0;
	}
	if ($DATA['cipa01valoracion'] == '') {
		$DATA['cipa01valoracion'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	if ($DATA['cipa01est_proyectados'] == '') {
		$sError = $ERR['cipa01est_proyectados'] . $sSepara . $sError;
	}
	if ($DATA['cipa01clase'] == '') {
		$sError = $ERR['cipa01clase'] . $sSepara . $sError;
	}
	$sSepara = ', ';
	switch ($DATA['cipa01estado']) {
		case 1: // Ofertado
			/*
			if ($DATA['cipa01idsupervisor'] == 0) {
				$sError = $ERR['cipa01idsupervisor'] . $sSepara . $sError;
			}
			if ($DATA['cipa01idmonitor'] == 0) {
				$sError = $ERR['cipa01idmonitor'] . $sSepara . $sError;
			}
			if ($DATA['cipa01iddocente3'] == 0) {
				$sError = $ERR['cipa01iddocente3'] . $sSepara . $sError;
			}
			if ($DATA['cipa01iddocente2'] == 0) {
				$sError = $ERR['cipa01iddocente2'] . $sSepara . $sError;
			}
			if ($DATA['cipa01iddocente'] == 0) {
				$sError = $ERR['cipa01iddocente'] . $sSepara . $sError;
			}
			if ($DATA['cipa01tematica'] == '') {
				$sError = $ERR['cipa01tematica'] . $sSepara . $sError;
			}
			if ($DATA['cipa01alcance'] == '') {
				$sError = $ERR['cipa01alcance'] . $sSepara . $sError;
			}
			if ($DATA['cipa01nombre'] == '') {
				$sError = $ERR['cipa01nombre'] . $sSepara . $sError;
			}
			*/
			$bValidaZona = false;
			$bValidaCentro = false;
			$bValidaEscuela = false;
			$bValidaPrograma = false;
			$bValidaCurso = false;
			$bValidaTutor = false;
			switch($DATA['cipa01alcance']) {
				case 0: //Nacional
					$DATA['cipa01zona'] = 0;
					$DATA['cipa01centro'] = 0;
					break;
				case 1: // Zonal
					$bValidaZona = true;
					break;
				case 2: // Centro
					$bValidaCentro = true;
					break;
				case 11: //	Escuela
					$bValidaEscuela = true;
					break;
				case 12: //	Programa
					$bValidaPrograma = true;
					break;
				case 21: //	Escuela - Zona
					$bValidaZona = true;
					$bValidaEscuela = true;
					break;
				case 22: //	Escuela Programa
					$bValidaPrograma = true;
					break;
				case 31: //	Por curso
					$bValidaCurso = true;
					break;
				case 32: //	Por curso - Zona
					$bValidaCurso = true;
					$bValidaZona = true;
					break;
				case 33: //	Por Curso - Tutor
					$bValidaCurso = true;
					$bValidaTutor = true;
					break;
			}
			if ($bValidaCurso){
				if ((int)$DATA['cipa01idcurso'] == 0) {
					$sError = $ERR['cipa01idcurso'] . $sSepara . $sError;
				}
			}
			if ($bValidaZona){
				if ((int)$DATA['cipa01zona'] == 0) {
					$sError = $ERR['cipa01zona'] . $sSepara . $sError;
				}	
			}
			if ($bValidaCentro) {
				if ((int)$DATA['cipa01centro'] == 0) {
					$sError = $ERR['cipa01centro'] . $sSepara . $sError;
				}	
			}
			if ($bValidaEscuela) {
				if ((int)$DATA['cipa01escuela'] == 0) {
					$sError = $ERR['cipa01escuela'] . $sSepara . $sError;
				}	
			}
			if ($bValidaPrograma) {
				if ((int)$DATA['cipa01programa'] == 0) {
					$sError = $ERR['cipa01programa'] . $sSepara . $sError;
				}	
			}
			if ($sError == ''){
				if ($bValidaTutor) {
					// Debe ser un tutor del curso...
					$sSQL = 'SELECT 1 
					FROM core20asignacion 
					WHERE core20idperaca=' . $DATA['cipa01periodo'] . ' AND core20idcurso=' . $DATA['cipa01idcurso'] . ' AND core20idtutor=' . $DATA['cipa01iddocente'] . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) == 0) {
						$sError = 'El tutor principal no esta registrado como tutor del curso.';
					}
				} else {
					//Minimo debe llevar tutor o monitor....
					if (($DATA['cipa01iddocente'] == 0) && ($DATA['cipa01idmonitor'] == 0)) {
						$sError = $ERR['cipa01iddocente'] . $sSepara . $sError;
					}
				}
			}
			if ($sError == ''){
				//Todo va bien pero ahora revisar que exista al menos una jornada.
				$sSQL = 'SELECT 1 
				FROM cipa02jornada AS TB 
				WHERE TB.cipa02idoferta=' . $DATA['cipa01id'] . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) == 0) {
					$sError = 'Debe agregar una jornada para poder ofertar el CIPAS.';
				}
			}
			//Fin de las valiaciones NO LLAVE.
			if ($sError != '') {
				$DATA['cipa01estado'] = 0;
			}
			$sErrorCerrando = $sError;
			$sError = '';
			break;
		default:
			if ($DATA['cipa01iddocente'] == 0) {
				$DATA['cipa01iddocente'] = $idTercero;
			}
			break;
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'cipa01nombre');
		$aLargoCampos = array(0, 200);
		for ($k = 1; $k <= 1; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	if ($DATA['cipa01periodo'] == '') {
		$sError = $ERR['cipa01periodo'];
	}
	// -- Tiene un cerrado.
	if ($DATA['cipa01estado'] == 1) {
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando != '') {
			$DATA['cipa01estado'] = 0;
			$sErrorCerrando = $sError . ' ' . $sErrorCerrando;
			$sError = '';
		} else {
			$bCerrando = true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['cipa01idsupervisor_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['cipa01idsupervisor_td'], $DATA['cipa01idsupervisor_doc'], $objDB, 'El tercero Supervisor ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cipa01idsupervisor'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['cipa01idmonitor_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['cipa01idmonitor_td'], $DATA['cipa01idmonitor_doc'], $objDB, 'El tercero Monitor ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cipa01idmonitor'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['cipa01iddocente3_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['cipa01iddocente3_td'], $DATA['cipa01iddocente3_doc'], $objDB, 'El tercero Docente3 ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cipa01iddocente3'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['cipa01iddocente2_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['cipa01iddocente2_td'], $DATA['cipa01iddocente2_doc'], $objDB, 'El tercero Docente2 ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cipa01iddocente2'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['cipa01iddocente_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['cipa01iddocente_td'], $DATA['cipa01iddocente_doc'], $objDB, 'El tercero Docente ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cipa01iddocente'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['cipa01consec'] == '') {
				$DATA['cipa01consec'] = tabla_consecutivo('cipa01oferta', 'cipa01consec', 'cipa01periodo=' . $DATA['cipa01periodo'] . '', $objDB);
				if ($DATA['cipa01consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'cipa01consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['cipa01consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM cipa01oferta WHERE cipa01periodo=' . $DATA['cipa01periodo'] . ' AND cipa01consec=' . $DATA['cipa01consec'] . '';
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
			$DATA['cipa01id'] = tabla_consecutivo('cipa01oferta', 'cipa01id', '', $objDB);
			if ($DATA['cipa01id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['cipa01estado'] = 0;
			$DATA['cipa01est_asistentes'] = 0;
			$DATA['cipa01num_valoraciones'] = 0;
			$DATA['cipa01valoracion'] = 0;
			$DATA['cipa01horatermina'] = 0;
			$DATA['cipa01mintermina'] = 0;
			$DATA['cipa01idorigeneviden'] = 0;
			$DATA['cipa01idarchivoeviden'] = 0;
		}
	}
	if ($sError == '') {
		//$cipa01tematica = addslashes($DATA['cipa01tematica']);
		$cipa01tematica = str_replace('"', '\"', $DATA['cipa01tematica']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos3801 = 'cipa01periodo, cipa01consec, cipa01id, cipa01nombre, cipa01alcance, 
			cipa01clase, cipa01estado, cipa01escuela, cipa01programa, cipa01zona, 
			cipa01centro, cipa01idcurso, cipa01tematica, cipa01est_proyectados, cipa01est_asistentes, 
			cipa01iddocente, cipa01iddocente2, cipa01iddocente3, cipa01idmonitor, cipa01idsupervisor, 
			cipa01num_valoraciones, cipa01valoracion, cipa01fechatermina, cipa01proximafecha, cipa01horatermina, 
			cipa01mintermina, cipa01idorigeneviden, cipa01idarchivoeviden';
			$sValores3801 = '' . $DATA['cipa01periodo'] . ', ' . $DATA['cipa01consec'] . ', ' . $DATA['cipa01id'] . ', "' . $DATA['cipa01nombre'] . '", ' . $DATA['cipa01alcance'] . ', 
			' . $DATA['cipa01clase'] . ', ' . $DATA['cipa01estado'] . ', ' . $DATA['cipa01escuela'] . ', ' . $DATA['cipa01programa'] . ', ' . $DATA['cipa01zona'] . ', 
			' . $DATA['cipa01centro'] . ', ' . $DATA['cipa01idcurso'] . ', "' . $cipa01tematica . '", ' . $DATA['cipa01est_proyectados'] . ', ' . $DATA['cipa01est_asistentes'] . ', 
			' . $DATA['cipa01iddocente'] . ', ' . $DATA['cipa01iddocente2'] . ', ' . $DATA['cipa01iddocente3'] . ', ' . $DATA['cipa01idmonitor'] . ', ' . $DATA['cipa01idsupervisor'] . ', 
			' . $DATA['cipa01num_valoraciones'] . ', ' . $DATA['cipa01valoracion'] . ', ' . $DATA['cipa01fechatermina'] . ', ' . $DATA['cipa01proximafecha'] . ', ' . $DATA['cipa01horatermina'] . ', 
			' . $DATA['cipa01mintermina'] . ', ' . $DATA['cipa01idorigeneviden'] . ', ' . $DATA['cipa01idarchivoeviden'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO cipa01oferta (' . $sCampos3801 . ') VALUES (' . cadena_codificar($sValores3801) . ');';
				$sdetalle = $sCampos3801 . '[' . cadena_codificar($sValores3801) . ']';
			} else {
				$sSQL = 'INSERT INTO cipa01oferta (' . $sCampos3801 . ') VALUES (' . $sValores3801 . ');';
				$sdetalle = $sCampos3801 . '[' . $sValores3801 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'cipa01nombre';
			$scampo[2] = 'cipa01estado';
			$scampo[3] = 'cipa01escuela';
			$scampo[4] = 'cipa01programa';
			$scampo[5] = 'cipa01zona';
			$scampo[6] = 'cipa01centro';
			$scampo[7] = 'cipa01idcurso';
			$scampo[8] = 'cipa01tematica';
			$scampo[9] = 'cipa01alcance';
			$scampo[10] = 'cipa01clase';
			//cipa01est_proyectados , cipa01iddocente, cipa01iddocente2, cipa01iddocente3, cipa01idmonitor, cipa01idsupervisor
			$scampo[11] = 'cipa01est_proyectados';
			$scampo[12] = 'cipa01iddocente';
			$scampo[13] = 'cipa01iddocente2';
			$scampo[14] = 'cipa01iddocente3';
			$scampo[15] = 'cipa01idmonitor';
			$scampo[16] = 'cipa01idsupervisor';
			$sdato[1] = $DATA['cipa01nombre'];
			$sdato[2] = $DATA['cipa01estado'];
			$sdato[3] = $DATA['cipa01escuela'];
			$sdato[4] = $DATA['cipa01programa'];
			$sdato[5] = $DATA['cipa01zona'];
			$sdato[6] = $DATA['cipa01centro'];
			$sdato[7] = $DATA['cipa01idcurso'];
			$sdato[8] = $cipa01tematica;
			$sdato[9] = $DATA['cipa01alcance'];
			$sdato[10] = $DATA['cipa01clase'];
			$sdato[11] = $DATA['cipa01est_proyectados'];
			$sdato[12] = $DATA['cipa01iddocente'];
			$sdato[13] = $DATA['cipa01iddocente2'];
			$sdato[14] = $DATA['cipa01iddocente3'];
			$sdato[15] = $DATA['cipa01idmonitor'];
			$sdato[16] = $DATA['cipa01idsupervisor'];
			$iNumCamposMod = 10;
			$sWhere = 'cipa01id=' . $DATA['cipa01id'] . '';
			$sSQL = 'SELECT * FROM cipa01oferta WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE cipa01oferta SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE cipa01oferta SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3801 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3801] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['cipa01id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
				$bCerrando = false;
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['cipa01id'], $sdetalle, $objDB);
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
		list($sErrorCerrando, $sDebugCerrar) = f3801_Cerrar($DATA['cipa01id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugCerrar;
	}
	/*
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' InfoDepura<br>';
	}
	*/
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
}
function f3801_db_Eliminar($cipa01id, $objDB, $bDebug = false)
{
	$iCodModulo = 3801;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3801 = 'lg/lg_3801_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3801)) {
		$mensajes_3801 = 'lg/lg_3801_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3801;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$cipa01id = numeros_validar($cipa01id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM cipa01oferta WHERE cipa01id=' . $cipa01id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $cipa01id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM cipa02jornada WHERE cipa02idoferta=' . $filabase['cipa01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Jornadas creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM cipa03cupos WHERE cipa03idoferta=' . $filabase['cipa01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Cupos creados, no es posible eliminar';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3801';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['cipa01id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM cipa02jornada WHERE cipa02idoferta=' . $filabase['cipa01id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM cipa03cupos WHERE cipa03idoferta=' . $filabase['cipa01id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'cipa01id=' . $cipa01id . '';
		//$sWhere = 'cipa01consec=' . $filabase['cipa01consec'] . ' AND cipa01periodo=' . $filabase['cipa01periodo'] . '';
		$sSQL = 'DELETE FROM cipa01oferta WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cipa01id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f3801_TituloBusqueda()
{
	require './app.php';
	$mensajes_3801 = 'lg/lg_3801_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3801)) {
		$mensajes_3801 = 'lg/lg_3801_es.php';
	}
	require $mensajes_3801;
	return $ETI['titulo_busca_3801'];
}
function f3801_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3801 = 'lg/lg_3801_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3801)) {
		$mensajes_3801 = 'lg/lg_3801_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3801;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b3801nombre" name="b3801nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f3801_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'let sCampo = window.document.frmedita.scampobusca.value;
	let params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b3801nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f3801_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3801 = 'lg/lg_3801_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3801)) {
		$mensajes_3801 = 'lg/lg_3801_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3801;
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
	$sBotones = '<input id="paginaf3801" name="paginaf3801" type="hidden" value="' . $pagina . '" />
	<input id="lppf3801" name="lppf3801" type="hidden" value="' . $lineastabla . '" />';
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
	$sTitulos = 'Periodo, Consec, Id, Nombre, Alcance, Clase, Estado, Escuela, Programa, Zona, Centro, Curso, Tematica, Est_proyectados, Est_asistentes, Docente, Docente2, Docente3, Monitor, Supervisor, Num_valoraciones, Valoracion';
	$sCampos = 'SELECT T1.exte02nombre, TB.cipa01consec, TB.cipa01id, TB.cipa01nombre, T5.cipa13nombre, T6.cipa14nombre, TB.cipa01estado, T8.core12nombre, T9.core09nombre, T10.unad23nombre, T11.unad24nombre, T12.unad40nombre, TB.cipa01tematica, TB.cipa01est_proyectados, TB.cipa01est_asistentes, T16.unad11razonsocial AS C16_nombre, T17.unad11razonsocial AS C17_nombre, T18.unad11razonsocial AS C18_nombre, T19.unad11razonsocial AS C19_nombre, T20.unad11razonsocial AS C20_nombre, TB.cipa01num_valoraciones, TB.cipa01valoracion, TB.cipa01periodo, TB.cipa01alcance, TB.cipa01clase, TB.cipa01escuela, TB.cipa01programa, TB.cipa01zona, TB.cipa01centro, TB.cipa01idcurso, TB.cipa01iddocente, T16.unad11tipodoc AS C16_td, T16.unad11doc AS C16_doc, TB.cipa01iddocente2, T17.unad11tipodoc AS C17_td, T17.unad11doc AS C17_doc, TB.cipa01iddocente3, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, TB.cipa01idmonitor, T19.unad11tipodoc AS C19_td, T19.unad11doc AS C19_doc, TB.cipa01idsupervisor, T20.unad11tipodoc AS C20_td, T20.unad11doc AS C20_doc';
	$sConsulta = 'FROM cipa01oferta AS TB, exte02per_aca AS T1, cipa13alcance AS T5, cipa14clasecipas AS T6, core12escuela AS T8, core09programa AS T9, unad23zona AS T10, unad24sede AS T11, unad40curso AS T12, unad11terceros AS T16, unad11terceros AS T17, unad11terceros AS T18, unad11terceros AS T19, unad11terceros AS T20 
	WHERE ' . $sSQLadd1 . ' TB.cipa01periodo=T1.exte02id AND TB.cipa01alcance=T5.cipa13id AND TB.cipa01clase=T6.cipa14id AND TB.cipa01escuela=T8.core12id AND TB.cipa01programa=T9.core09id AND TB.cipa01zona=T10.unad23id AND TB.cipa01centro=T11.unad24id AND TB.cipa01idcurso=T12.unad40id AND TB.cipa01iddocente=T16.unad11id AND TB.cipa01iddocente2=T17.unad11id AND TB.cipa01iddocente3=T18.unad11id AND TB.cipa01idmonitor=T19.unad11id AND TB.cipa01idsupervisor=T20.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.cipa01periodo, TB.cipa01consec';
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
	<td><b>' . $ETI['cipa01periodo'] . '</b></td>
	<td><b>' . $ETI['cipa01consec'] . '</b></td>
	<td><b>' . $ETI['cipa01nombre'] . '</b></td>
	<td><b>' . $ETI['cipa01alcance'] . '</b></td>
	<td><b>' . $ETI['cipa01clase'] . '</b></td>
	<td><b>' . $ETI['cipa01estado'] . '</b></td>
	<td><b>' . $ETI['cipa01escuela'] . '</b></td>
	<td><b>' . $ETI['cipa01programa'] . '</b></td>
	<td><b>' . $ETI['cipa01zona'] . '</b></td>
	<td><b>' . $ETI['cipa01centro'] . '</b></td>
	<td><b>' . $ETI['cipa01idcurso'] . '</b></td>
	<td><b>' . $ETI['cipa01tematica'] . '</b></td>
	<td><b>' . $ETI['cipa01est_proyectados'] . '</b></td>
	<td><b>' . $ETI['cipa01est_asistentes'] . '</b></td>
	<td colspan="2"><b>' . $ETI['cipa01iddocente'] . '</b></td>
	<td colspan="2"><b>' . $ETI['cipa01iddocente2'] . '</b></td>
	<td colspan="2"><b>' . $ETI['cipa01iddocente3'] . '</b></td>
	<td colspan="2"><b>' . $ETI['cipa01idmonitor'] . '</b></td>
	<td colspan="2"><b>' . $ETI['cipa01idsupervisor'] . '</b></td>
	<td><b>' . $ETI['cipa01num_valoraciones'] . '</b></td>
	<td><b>' . $ETI['cipa01valoracion'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['cipa01id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_cipa01estado = $ETI['msg_abierto'];
		if ($filadet['cipa01estado'] == 7) {
			$et_cipa01estado = $ETI['msg_cerrado'];
		}
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['cipa01consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['cipa01nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['cipa13nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['cipa14nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_cipa01estado . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad40nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['cipa01tematica'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['cipa01est_proyectados'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['cipa01est_asistentes'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C16_td'] . ' ' . $filadet['C16_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C16_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C17_td'] . ' ' . $filadet['C17_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C17_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C18_td'] . ' ' . $filadet['C18_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C18_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C19_td'] . ' ' . $filadet['C19_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C19_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C20_td'] . ' ' . $filadet['C20_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C20_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['cipa01num_valoraciones'] . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['cipa01valoracion']) . $sSufijo . '</td>
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

