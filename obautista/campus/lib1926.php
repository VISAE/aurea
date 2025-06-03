<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.5 martes, 15 de marzo de 2016
--- Modelo Versión 2.30.1b jueves, 21 de septiembre de 2023 --- Se revisan los role para incorporar los de matricula manual.
--- 1926 
*/
function f1926_HTMLComboV2_even21depto($objDB, $objCombos, $valor, $vreven21pais)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('even21depto', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_even21ciudad()';
	if ($vreven21pais != '057') {
		$objCombos->addItem('-', '{Otro}');
	}
	$sSQL = '';
	if ($vreven21pais !== '') {
		//$objCombos->iAncho=450;
		$sSQL = 'SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto WHERE unad19codpais="' . $vreven21pais . '"';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f1926_texto_noencuesta()
{
	require './app.php';
	$mensajes_1926 = $APP->rutacomun . 'lg/lg_1926_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1926)) {
		$mensajes_1926 = $APP->rutacomun . 'lg/lg_1926_es.php';
	}
	require $mensajes_1926;
	$sUrlTablero = 'tablero.php';
	if (isset($APP->urltablero) != 0) {
		if (file_exists($APP->urltablero)) {
			$sUrlTablero = $APP->urltablero;
		}
	}
	if ($APP->idsistema == 7) {
		$sUrlTablero = 'index.php';
	}
	return $ETI['msg_noencuesta'] . ' <a href="' . $sUrlTablero . '">' . $ETI['msg_noencuesta_link'] . '</a>';
}
function html_combo_even21depto($objDB, $valor, $vreven21pais)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sSQL = '';
	if (trim($vreven21pais) != '') {
		$sSQL = 'SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto WHERE unad19codpais="' . $vreven21pais . '"';
	}
	$objCombos = new clsHtmlCombos('');
	$objCombos->nuevo('even21depto', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_even21ciudad()';
	if ($vreven21pais != '057') {
		$objCombos->addItem('-', '{Otro}');
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function html_combo_even21ciudad($objDB, $valor, $vreven21depto)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sSQL = '';
	$objCombos = new clsHtmlCombos('');
	switch ($vreven21depto) {
		case '':
		case '-':
			break;
		default:
			$sSQL = 'SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad WHERE unad20coddepto="' . $vreven21depto . '"';
			break;
	}
	$objCombos->nuevo('even21ciudad', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'guardar_even21ciudad()';
	if (substr($vreven21depto, 0, 3) != '057') {
		if ($vreven21depto != '') {
			$objCombos->addItem('-', '{Otro}');
		}
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function html_combo_even21idcead($objDB, $valor, $vreven21idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sSQL = '';
	if ($vreven21idzona != '') {
		$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona="' . $vreven21idzona . '"';
	}
	//$res=html_combo('even21idcead', 'unad24id', 'unad24nombre', 'unad24sede', $scondi, 'unad24nombre', $valor, $objDB, 'guardar_even21idcead()', true, '{'.$ETI['msg_seleccione'].'}', '');
	$objCombos = new clsHtmlCombos('');
	$objCombos->nuevo('even21idcead', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'guardar_even21idcead()';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function TraerBusqueda_db_even21idcurso($sCodigo, $objDB)
{
	$sRespuesta = '';
	$id = 0;
	$sCodigo = htmlspecialchars(trim($sCodigo));
	if ($sCodigo != '') {
		$sSQL = 'SELECT unad40id, unad40nombre, unad40id FROM unad40curso WHERE unad40id="' . $sCodigo . '"';
		$res = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($res) != 0) {
			$fila = $objDB->sf($res);
			$sRespuesta = '<b>' . $fila['unad40id'] . ' ' . cadena_notildes($fila['unad40nombre']) . '</b>';
			$id = $fila['unad40id'];
		}
		if ($sRespuesta == '') {
			$sRespuesta = '<span class="rojo">{' . $sCodigo . ' No encontrado}</span>';
		}
	}
	return array($id, cadena_codificar($sRespuesta));
}
function TraerBusqueda_even21idcurso($aParametros)
{
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$respuesta = '';
	$scodigo = $aParametros[0];
	$bxajax = true;
	if (isset($aParametros[3]) != 0) {
		if ($aParametros[3] == 1) {
			$bxajax = false;
		}
	}
	$id = 0;
	if ($scodigo != '') {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		list($id, $respuesta) = TraerBusqueda_db_even21idcurso($scodigo, $objDB);
	}
	$objid = $aParametros[1];
	$sdiv = $aParametros[2];
	$objResponse = new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $respuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id > 0) {
		$objResponse->call('RevisaLlave');
	}
	return $objResponse;
}
function Cargar_even21depto($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$bDebug = false;
	$sDebug = '';
	if (isset($aParametros[9]) != 0) {
		if ($aParametros[9] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	//Guardar la el pais
	$sSQL = 'UPDATE even21encuestaaplica SET even21pais="' . $aParametros[0] . '", even21depto="", even21ciudad="" WHERE even21id=' . $aParametros[1] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL;
	}
	$html_even21depto = html_combo_even21depto($objDB, '', $aParametros[0]);
	$html_even21ciudad = html_combo_even21ciudad($objDB, '', '');
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_even21depto', 'innerHTML', $html_even21depto);
	$objResponse->assign('div_even21ciudad', 'innerHTML', $html_even21ciudad);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function Cargar_even21ciudad($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$bDebug = false;
	$sDebug = '';
	if (isset($aParametros[9]) != 0) {
		if ($aParametros[9] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	//Guardar la el pais
	$sSQL = 'UPDATE even21encuestaaplica SET even21depto="' . $aParametros[0] . '", even21ciudad="" WHERE even21id=' . $aParametros[1] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL;
	}
	$html_even21ciudad = html_combo_even21ciudad($objDB, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_even21ciudad', 'innerHTML', $html_even21ciudad);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1621_Guardar_even21ciudad($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$bDebug = false;
	$sDebug = '';
	if (isset($aParametros[9]) != 0) {
		if ($aParametros[9] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sSQL = 'UPDATE even21encuestaaplica SET even21ciudad="' . $aParametros[0] . '" WHERE even21id=' . $aParametros[1] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL;
	}
	$objDB->CerrarConexion();
	if ($bDebug) {
		$objResponse = new xajaxResponse();
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		return $objResponse;
	}
}
function f1621_Guardar_even21fechanace($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$bDebug = false;
	$sDebug = '';
	if (isset($aParametros[9]) != 0) {
		if ($aParametros[9] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sFecha = '00/00/0000';
	$iAgnos = 0;
	if (fecha_esvalida($aParametros[0])) {
		$sFecha = $aParametros[0];
		list($iAgnos, $iMedida) = fecha_edad($sFecha);
	}
	$sSQL = 'UPDATE even21encuestaaplica SET even21fechanace="' . $sFecha . '", even21agnos=' . $iAgnos . ' WHERE even21id=' . $aParametros[1] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL;
	}
	$objDB->CerrarConexion();
	if ($bDebug) {
		$objResponse = new xajaxResponse();
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		return $objResponse;
	}
}
function Cargar_even21idcead($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$bDebug = false;
	$sDebug = '';
	if (isset($aParametros[9]) != 0) {
		if ($aParametros[9] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sSQL = 'UPDATE even21encuestaaplica SET even21idzona="' . $aParametros[0] . '" WHERE even21id=' . $aParametros[1] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL;
	}
	$html_even21idcead = html_combo_even21idcead($objDB, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_even21idcead', 'innerHTML', $html_even21idcead);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1621_Guardar_even21idcead($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$bDebug = false;
	$sDebug = '';
	if (isset($aParametros[9]) != 0) {
		if ($aParametros[9] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sSQL = 'UPDATE even21encuestaaplica SET even21idcead="' . $aParametros[0] . '" WHERE even21id=' . $aParametros[1] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL;
	}
	$objDB->CerrarConexion();
	if ($bDebug) {
		$objResponse = new xajaxResponse();
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		return $objResponse;
	}
}
function f1621_Guardar_even21perfil($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$bDebug = false;
	$sDebug = '';
	if (isset($aParametros[9]) != 0) {
		if ($aParametros[9] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sSQL = 'UPDATE even21encuestaaplica SET even21perfil="' . $aParametros[0] . '" WHERE even21id=' . $aParametros[1] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL;
	}
	$objDB->CerrarConexion();
	if ($bDebug) {
		$objResponse = new xajaxResponse();
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		return $objResponse;
	}
}
function f1621_Guardar_even21idprograma($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$bDebug = false;
	$sDebug = '';
	if (isset($aParametros[9]) != 0) {
		if ($aParametros[9] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sSQL = 'UPDATE even21encuestaaplica SET even21idprograma="' . $aParametros[0] . '" WHERE even21id=' . $aParametros[1] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL;
	}
	$objDB->CerrarConexion();
	if ($bDebug) {
		$objResponse = new xajaxResponse();
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		return $objResponse;
	}
}
function f1926_ExisteDato() {}
function f1926_TablaDetalle($aParametros, $objDB) {}
function f1926_HtmlTabla($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sDetalle = f1926_TablaDetalle($aParametros, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1926detalle', 'innerHTML', $sDetalle);
	return $objResponse;
}
// -- Espacio para incluir funciones xajax personalizadas.
function f1926_ArmarTabla22($idEncuesta, $even21id, $objDB, $iIntento = 1)
{
	$bRes = false;
	if ($iIntento < 4) {
		//En caso de que se hagan mas de 3 intentos hay un error grave...';
		$bRes = true;
		$campos22 = 'INSERT INTO even22encuestarpta (even22idaplica, even22idpregunta, even22id, even22tiporespuesta, even22opcional, 
		even22irespuesta, even22nota, even22relevante, even22rpta0, even22rpta1, 
		even22rpta2, even22rpta3, even22rpta4, even22rpta5, even22rpta6, 
		even22rpta7, even22rpta8, even22rpta9, even22divergente, even22idpregcondiciona, 
		even22valorcondiciona) VALUES ';
		$svalores22 = '';
		$even22id = tabla_consecutivo('even22encuestarpta', 'even22id', '', $objDB);
		//Traer las respuestas.
		$sSQL18 = 'SELECT even18id, even18tiporespuesta, even18opcional, even18divergente, even18idpregcondiciona, even18valorcondiciona 
		FROM even18encuestapregunta 
		WHERE even18idencuesta=' . $idEncuesta . '';
		$tabla18 = $objDB->ejecutasql($sSQL18);
		while ($fila18 = $objDB->sf($tabla18)) {
			if ($svalores22 != '') {
				$svalores22 = $svalores22 . ', ';
			}
			$svalores22 = $svalores22 . '(' . $even21id . ', ' . $fila18['even18id'] . ', ' . $even22id . ', ' . $fila18['even18tiporespuesta'] . ', "' . $fila18['even18opcional'] . '", 
			-1, "", "N", 0, 0, 
			0, 0, 0, 0, 0, 
			0, 0, 0, "' . $fila18['even18divergente'] . '", ' . $fila18['even18idpregcondiciona'] . ', 
			' . $fila18['even18valorcondiciona'] . ')';
			$even22id++;
		}
		if ($svalores22 != '') {
			$tabla = $objDB->ejecutasql($campos22 . $svalores22);
			if ($tabla == false) {
				$iIntento++;
				$bRes = f1926_ArmarTabla22($idEncuesta, $even21id, $objDB, $iIntento);
			}
		}
	}
	return $bRes;
}
function f1926_CargarEncuestas($idTercero, $objDB, $bDebug = false)
{
	//Ver que encuestas estas activas.
	$sError = '';
	$sDebug = '';
	$even21pais = '';
	$even21depto = '';
	$even21ciudad = '';
	$even21fechanace = '';
	$even21agnos = 0;
	$even21perfil = 0;
	$even21idzona = 0;
	$even21idcead = 0;
	$even21idprograma = 0;
	$sObligatoria = 'S';
	$sHoy = fecha_hoy();
	$idTablero = 0;
	$sSQL = 'SELECT unad11pais, unad11deptodoc, unad11ciudaddoc, unad11idtablero, unad11fechanace 
	FROM unad11terceros 
	WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$even21pais = $fila['unad11pais'];
		$even21depto = $fila['unad11deptodoc'];
		$even21ciudad = $fila['unad11ciudaddoc'];
		$idTablero = $fila['unad11idtablero'];
		$even21fechanace = $fila['unad11fechanace'];
		if (fecha_esvalida($even21fechanace)) {
			list($even21agnos, $iMedida) = fecha_edad($even21fechanace);
			if ($iMedida != 1) {
				$even21agnos = 0;
			}
		}
	} else {
		$sError = 'No se ha encontrado el tercero ref ' . $idTercero . '';
	}
	$sTabla04 = 'core04matricula_' . $idTablero;
	if ($sError == '') {
		//Solo pedimos los procesos 0 (Abierta) y 1703 (Encuesta Docente) por que los demas cada uno lo maneja.
		$sSQL = 'SELECT even16consec, even16id, even16idproceso, even16porperiodo, even16porcurso, even16porbloqueo, 
		even16fechainicio, even16fechafin, even16caracter, even16idbloqueo, even16porrol 
		FROM even16encuesta 
		WHERE even16idproceso IN (0, 1703) AND even16publicada="S"';
		$tabla16 = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Se han encontrado ' . $objDB->nf($tabla16) . ' encuestas publicadas<br>';
		}
		while ($fila16 = $objDB->sf($tabla16)) {
			//Si aplica para la encuesta insertarla.
			$iNumRoles = -1;
			$idBloqueo = 0;
			$idEncuesta = $fila16['even16id'];
			$sObligatoria = 'S';
			if ($fila16['even16caracter'] == 0) {
				$sObligatoria = 'N';
			}
			$sRoles = '';
			if ($fila16['even16porrol'] == 'S') {
				$iNumRoles = 0;
				$aRoles = array();
				$sSQL = 'SELECT unad58id, unad58nombre FROM unad58rolmoodle';
				$tabla58 = $objDB->ejecutasql($sSQL);
				while ($fila58 = $objDB->sf($tabla58)) {
					$aRoles[$fila58['unad58id']] = 0;
				}
				$sRoles = '-99';
				$sSQL = 'SELECT even32idrol FROM even32encuestarol WHERE even32idencuesta=' . $idEncuesta . ' AND even32activo="S"';
				$tabla32 = $objDB->ejecutasql($sSQL);
				while ($fila32 = $objDB->sf($tabla32)) {
					$aRoles[$fila32['even32idrol']] = 1;
					$sRoles = $sRoles . ',' . $fila32['even32idrol'];
					$iNumRoles++;
				}
				if ($bDebug) {
					$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Roles para la encuesta ' . $idEncuesta . ' ' . $sRoles . '<br>';
				}
			}
			//Caracter 3 es una muestra.
			$bPasa = true;
			$iCiclos = 0;
			$aCiclo = array();
			switch ($fila16['even16idproceso']) {
				case 0: //Encuestas generales.
				case 1703: //Evaluación docente, se maneja como si fuera la general.
					//Ver si es por bloqueo.
					if ($fila16['even16porbloqueo'] == 'S') {
						$idBloqueo = $fila16['even16idbloqueo'];
						if ($bDebug) {
							$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16consec'] . ' va por el bloqueo ' . $idBloqueo . '<br>';
						}
						//Ver si el bloqueo es por que existe o porque no existe.
						$bPasa = false;
						$sSQL = 'SELECT unad63tiporesultado, unad63origendatos FROM unad63bloqueo WHERE unad63id=' . $idBloqueo . '';
						$tabla63 = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla63) > 0) {
							$fila63 = $objDB->sf($tabla63);
							if ($bDebug) {
								$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16consec'] . ' bloqueo ' . $idBloqueo . ' encontrado - se procede a revisar pertenencia [Tipo resultado esperado ' . $fila63['unad63tiporesultado'] . ']<br>';
							}
							if ($fila63['unad63origendatos'] == 1) {
								$sSQL = 'SELECT unad65id FROM unad65bloqueados WHERE unad65idbloqueo=' . $idBloqueo . ' AND unad65idtercero=' . $idTercero . '';
								$tabla65 = $objDB->ejecutasql($sSQL);
								if ($objDB->nf($tabla65) > 0) {
									//ya esta...
									if ($fila63['unad63tiporesultado'] == 1) {
										$bPasa = true;
										$bBloqueoQuitar = true;
									} else {
										if ($bDebug) {
											$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16consec'] . ' bloqueo ' . $idBloqueo . ' el tercero ya esta en el bloqueo<br>';
										}
									}
								} else {
									if ($fila63['unad63tiporesultado'] == 0) {
										$bPasa = true;
										$bBloqueoInsertar = true;
									} else {
										if ($bDebug) {
											$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16consec'] . ' bloqueo ' . $idBloqueo . ' el tercero NO PERTENECE<br>';
										}
									}
								}
							} else {
								//@@ tiene otro origen de datos.
								if ($bDebug) {
									$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16consec'] . ' bloqueo ' . $idBloqueo . ' <span class="rojo">EL BLOQUEO TIENE UN ORIGEN DE DATOS QUE NO ES UNADSYS - NO SE HA IMPLENTADO.</span><br>';
								}
							}
						}
						if ($bPasa) {
							$iCiclos = 1;
							$aCiclo[1]['idPeriodo'] = 0;
							$aCiclo[1]['idcurso'] = 0;
							$aCiclo[1]['idbloqueo'] = $idBloqueo;
							$aCiclo[1]['perfil'] = 0;
							if ($bDebug) {
								$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16consec'] . ' <b>DEBE SER APLICADA AL TERCERO</b><br>';
							}
						}
					} else {
						//No es por bloqueo... ver si es por peraca y por curso...
						if ($fila16['even16porperiodo'] == 'S') {
							if ($bDebug) {
								$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16id'] . ' va por periodo<br>';
							}
							$sSQL = 'SELECT even24idperaca FROM even24encuestaperaca WHERE even24idencuesta=' . $idEncuesta . ' AND STR_TO_DATE(even24fechainicial, "%d/%m/%Y")<=STR_TO_DATE("' . $sHoy . '", "%d/%m/%Y") AND STR_TO_DATE(even24fechafinal, "%d/%m/%Y")>=STR_TO_DATE("' . $sHoy . '", "%d/%m/%Y")';
							$tabla24 = $objDB->ejecutasql($sSQL);
							while ($fila24 = $objDB->sf($tabla24)) {
								$idPeriodo = $fila24['even24idperaca'];
								if ($bDebug) {
									$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16id'] . ' aplica para el periodo ' . $idPeriodo . '<br>';
								}
								if ($fila16['even16porcurso'] == 'S') {
									//Primero vamos a cargar los cursos del tercero.
									$bRevisaEstudiante = false;
									$bRevisaTutor = false;
									$bRevisaDirector = false;
									$idPerfil = 9;
									if ($iNumRoles > 0) {
										if ($aRoles[3] == 1) {
											$bRevisaDirector = true;
										}
										if ($aRoles[4] == 1) {
											$bRevisaTutor = true;
										}
										if ($aRoles[5] == 1) {
											$bRevisaEstudiante = true;
										}
									} else {
										if ($iNumRoles == -1) {
											$bRevisaTutor = true;
											$bRevisaDirector = true;
											$bRevisaEstudiante = true;
										}
									}
									$sCursosTercero = '-99';
									$idPerfil = 9;
									$aPerfilCurso = array();
									//@@@@ Se puede mejorar el rol segun sea el curso.
									if ($bRevisaEstudiante) {
										if ($idTablero != 0) {
											$idPerfil = 0; //Aqui cambia...
											$sSQL = 'SELECT core04idcurso FROM core04matricula_' . $idTablero . ' WHERE core04peraca=' . $idPeriodo . ' AND core04tercero=' . $idTercero . ' AND core04estado NOT IN (1,9,10)';
											$result = $objDB->ejecutasql($sSQL);
											while ($fila47 = $objDB->sf($result)) {
												$aPerfilCurso[$fila47['core04idcurso']] = 0;
												$sCursosTercero = $sCursosTercero . ',' . $fila47['core04idcurso'];
											}
										}
									}
									if ($bRevisaTutor) {
										$sSQL = 'SELECT core20idcurso FROM core20asignacion WHERE core20idtutor=' . $idTercero . ' AND core20idperaca=' . $idPeriodo . ' AND core20numestudiantes>0';
										$result = $objDB->ejecutasql($sSQL);
										while ($fila47 = $objDB->sf($result)) {
											$aPerfilCurso[$fila47['core20idcurso']] = 1;
											$sCursosTercero = $sCursosTercero . ',' . $fila47['core20idcurso'];
										}
									}
									if ($bRevisaDirector) {
										$sSQL = 'SELECT ofer08idcurso FROM ofer08oferta WHERE ofer08idacomanamento=' . $idTercero . ' AND ofer08idper_aca=' . $idPeriodo . ' AND ofer08cead=0';
										$result = $objDB->ejecutasql($sSQL);
										while ($fila47 = $objDB->sf($result)) {
											$aPerfilCurso[$fila47['ofer08idcurso']] = 3;
											$sCursosTercero = $sCursosTercero . ',' . $fila47['ofer08idcurso'];
										}
									}
									if ($iNumRoles > 0) {
										// --- Por cada rol verificar si esta por amtricula directa.
										$sSQL = 'SELECT TB.ofer08idcurso, T38.ofer38idrol 
										FROM ofer08oferta AS TB, ofer38matricula AS T38
										WHERE TB.ofer08idper_aca=' . $idPeriodo . ' 
										AND TB.ofer08id=T38.ofer38idoferta 
										AND T38.ofer38idtercero=' . $idTercero . ' AND T38.ofer38activo="S"
										AND T38.ofer38idrol IN (' . $sRoles . ')';
										$result = $objDB->ejecutasql($sSQL);
										while ($fila38 = $objDB->sf($result)) {
											$aPerfilCurso[$fila38['ofer08idcurso']] = $fila38['ofer38idrol'];
											$sCursosTercero = $sCursosTercero . ',' . $fila38['ofer08idcurso'];
										}
									}
									if ($bDebug) {
										$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Cursos del tercero ' . $sCursosTercero . '<br>';
									}
									//Empieza por curso perido...
									if ($sCursosTercero != '-99') {
										$sSQL = 'SELECT even25idcurso FROM even25encuestacurso WHERE even25idencuesta=' . $idEncuesta . ' AND even25activo="S" AND even25idcurso IN (' . $sCursosTercero . ')';
										$tabla25 = $objDB->ejecutasql($sSQL);
										while ($fila25 = $objDB->sf($tabla25)) {
											$iCiclos++;
											$idPerfilAplica = $aPerfilCurso[$fila25['even25idcurso']];
											$aCiclo[$iCiclos]['idPeriodo'] = $idPeriodo;
											$aCiclo[$iCiclos]['idcurso'] = $fila25['even25idcurso'];
											$aCiclo[$iCiclos]['idbloqueo'] = 0;
											$aCiclo[$iCiclos]['perfil'] = $idPerfilAplica;
											if ($bDebug) {
												$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16id'] . ' aplica para el periodo ' . $idPeriodo . ' y curso ' . $fila25['even25idcurso'] . '<br>';
											}
										}
									}
									//Termina si es por curso periodo
								} else {
									//Es solo por periodo... pero puede aplicar por roles...
									$bAplicaEncuesta = false;
									$bRevisaEstudiante = false;
									$bRevisaTutor = false;
									$bRevisaDirector = false;
									$idPerfil = 9;
									if ($iNumRoles > 0) {
										if ($aRoles[3] == 1) {
											$bRevisaDirector = true;
										}
										if ($aRoles[4] == 1) {
											$bRevisaTutor = true;
										}
										if ($aRoles[5] == 1) {
											$bRevisaEstudiante = true;
										}
									} else {
										if ($iNumRoles == -1) {
											$bRevisaTutor = true;
											$bRevisaDirector = true;
											$bRevisaEstudiante = true;
										}
									}
									if ($bRevisaEstudiante) {
										$sSQL = 'SELECT 1 FROM ' . $sTabla04 . ' WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $idPeriodo . ' AND core04estado NOT IN (1, 9, 10) LIMIT 0,1';
										$result = $objDB->ejecutasql($sSQL);
										if ($objDB->nf($result) > 0) {
											$bAplicaEncuesta = true;
											$idPerfil = 0; //Cambia no son los mismos de moodle.
										}
									}
									if (!$bAplicaEncuesta) {
										if ($bRevisaTutor) {
											$sSQL = 'SELECT 1 FROM core20asignacion WHERE core20idtutor=' . $idTercero . ' AND core20idperaca=' . $idPeriodo . ' AND core20numestudiantes>0 LIMIT 0,1';
											$result = $objDB->ejecutasql($sSQL);
											if ($objDB->nf($result) > 0) {
												$bAplicaEncuesta = true;
												$idPerfil = 1; // 4 para 1
											}
										}
									}
									if (!$bAplicaEncuesta) {
										if ($bRevisaDirector) {
											$sSQL = 'SELECT 1 FROM ofer08oferta WHERE ofer08idacomanamento=' . $idTercero . ' AND ofer08idper_aca=' . $idPeriodo . ' AND ofer08cead=0 LIMIT 0,1';
											$result = $objDB->ejecutasql($sSQL);
											if ($objDB->nf($result) > 0) {
												$bAplicaEncuesta = true;
												$idPerfil = 3;
											}
										}
									}
									if ($bAplicaEncuesta) {
										if ($bDebug) {
											$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16id'] . ' aplica para el periodo ' . $idPeriodo . ' TODOS LOS CURSOS<br>';
										}
										$iCiclos++;
										$aCiclo[$iCiclos]['idPeriodo'] = $idPeriodo;
										$aCiclo[$iCiclos]['idcurso'] = 0;
										$aCiclo[$iCiclos]['idbloqueo'] = 0;
										$aCiclo[$iCiclos]['perfil'] = $idPerfil;
									}
								}
							}
						} else {
							//por curso sin peraca...
							if ($fila16['even16porcurso'] == 'S') {
								//Igual toca considerar que sean periodos activos.
								$sIdsActivos = '-99';
								$sSQL = 'SELECT exte02id FROM exte02per_aca WHERE exte02vigente="S"';
								$tabla2 = $objDB->ejecutasql($sSQL);
								while ($fila2 = $objDB->sf($tabla2)) {
									$sIdsActivos = $sIdsActivos . ',' . $fila2['exte02id'];
								}
								$bRevisaTutor = false;
								$bRevisaDirector = false;
								$bRevisaEstudiante = false;
								$idPerfil = 0;
								if ($iNumRoles > 0) {
									if ($aRoles[3] == 1) {
										$bRevisaDirector = true;
									}
									if ($aRoles[4] == 1) {
										$bRevisaTutor = true;
									}
									if ($aRoles[5] == 1) {
										$bRevisaEstudiante = true;
									}
								} else {
									if ($iNumRoles == -1) {
										$bRevisaTutor = true;
										$bRevisaDirector = true;
										$bRevisaEstudiante = true;
									}
								}
								$sSQL = 'SELECT even25idcurso FROM even25encuestacurso WHERE even25idencuesta=' . $idEncuesta . ' AND even25activo="S"';
								$tabla25 = $objDB->ejecutasql($sSQL);
								//echo 'Es por curso y sin peraca '.$sSQL.'<br>';
								while ($fila25 = $objDB->sf($tabla25)) {
									$bTieneCurso = false;
									if ($bRevisaEstudiante) {
										$sSQL = 'SELECT 1 FROM ' . $sTabla04 . ' WHERE core04tercero=' . $idTercero . ' AND core04idcurso=' . $fila25['even25idcurso'] . ' AND core04estado NOT IN (1, 9, 10)';
										$result = $objDB->ejecutasql($sSQL);
										if ($objDB->nf($result) > 0) {
											$bTieneCurso = true;
											$idPerfil = 5;
										}
									}
									if (!$bTieneCurso) {
										if ($bRevisaTutor) {
											$sSQL = 'SELECT 1 FROM core20asignacion WHERE core20idtutor=' . $idTercero . ' AND core20idperaca IN (' . $sIdsActivos . ') AND core20idcurso=' . $fila25['even25idcurso'] . ' AND core20numestudiantes>0 LIMIT 0,1';
											$result = $objDB->ejecutasql($sSQL);
											if ($objDB->nf($result) > 0) {
												$bTieneCurso = true;
												$idPerfil = 4;
											}
										}
									}
									if (!$bTieneCurso) {
										if ($bRevisaDirector) {
											$sSQL = 'SELECT 1 FROM ofer08oferta WHERE ofer08idacomanamento=' . $idTercero . ' AND ofer08idper_aca IN (' . $sIdsActivos . ') AND ofer08idcurso=' . $fila25['even25idcurso'] . ' AND ofer08cead=0 LIMIT 0,1';
											$result = $objDB->ejecutasql($sSQL);
											if ($objDB->nf($result) > 0) {
												$bTieneCurso = true;
												$idPerfil = 3;
											}
										}
									}
									if ($bTieneCurso) {
										$iCiclos++;
										$aCiclo[$iCiclos]['idPeriodo'] = 0;
										$aCiclo[$iCiclos]['idcurso'] = $fila25['even25idcurso'];
										$aCiclo[$iCiclos]['idbloqueo'] = 0;
										$aCiclo[$iCiclos]['perfil'] = $idPerfil;
									}
								}
							}
							//Fin de si es por curso.
						}
						//Sin de si es o no por periodo.
					}
					break;
				default:
					$bPasa = false;
					break;
			}
			if ($bPasa) {
				//Traer la info complementaria de zona y sede....
				$sSQL = 'SELECT core01idprograma, core01idescuela, core01idzona, core011idcead 
				FROM core01estprograma 
				WHERE core01idtercero=' . $idTercero . ' 
				ORDER BY core01fechainicio DESC, core01id DESC';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$even21idzona = $fila['core01idzona'];
					$even21idcead = $fila['core011idcead'];
					$even21idprograma = $fila['core01idprograma'];
				} else {
					//Buscarlo en las encuestas anteriores
					$sSQL = 'SELECT even21idzona, even21idcead, even21idprograma 
					FROM even21encuestaaplica 
					WHERE even21idtercero=' . $idTercero . ' 
					ORDER BY even21terminada DESC, even21id DESC 
					LIMIT 0,1';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$even21idzona = $fila['even21idzona'];
						$even21idcead = $fila['even21idcead'];
						$even21idprograma = $fila['even21idprograma'];
					}
				}
				if ($bDebug) {
					$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Iniciando la insersion de la encuesta [Ciclos: ' . $iCiclos . ']<br>';
				}
				$even21id = tabla_consecutivo('even21encuestaaplica', 'even21id', '', $objDB);
				//Insertar los ciclos solicitados.
				for ($k = 1; $k <= $iCiclos; $k++) {
					$idPeriodo = $aCiclo[$k]['idPeriodo'];
					$idCurso = $aCiclo[$k]['idcurso'];
					$idBloqueo = $aCiclo[$k]['idbloqueo'];
					$even21perfil = $aCiclo[$k]['perfil'];
					//Ver que no este antes de insertarla.
					$sSQL = 'SELECT even21id 
					FROM even21encuestaaplica 
					WHERE even21idencuesta=' . $idEncuesta . ' AND even21idtercero=' . $idTercero . ' AND even21idperaca=' . $idPeriodo . ' AND even21idcurso=' . $idCurso . ' AND even21idbloquedo=' . $idBloqueo . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) == 0) {
						$sSQL21 = 'INSERT INTO even21encuestaaplica (even21idencuesta, even21idtercero, even21idperaca, even21idcurso, even21idbloquedo, 
						even21id, even21fechapresenta, even21terminada, even21pais, even21depto, 
						even21ciudad, even21fechanace, even21agnos, even21perfil, even21idzona, 
						even21idcead, even21idprograma, even21obligatoria) 
						VALUES (' . $idEncuesta . ', ' . $idTercero . ', ' . $idPeriodo . ', ' . $idCurso . ', ' . $idBloqueo . ', 
						' . $even21id . ', "00/00/0000", "N", "' . $even21pais . '", "' . $even21depto . '", 
						"' . $even21ciudad . '", "' . $even21fechanace . '", ' . $even21agnos . ', ' . $even21perfil . ', ' . $even21idzona . ', 
						' . $even21idcead . ', ' . $even21idprograma . ', "' . $sObligatoria . '")';
						$tabla = $objDB->ejecutasql($sSQL21);
						f1926_ArmarTabla22($idEncuesta, $even21id, $objDB);
						$even21id++;
					}
					//Termina el ciclo.
				}
			}
		}
	}
	return $sDebug;
}
function f1926_Siguiente($idTercero, $objDB)
{
	$res = 0;
	$idAplica = 0;
	$sSQL = 'SELECT even21id, even21idencuesta 
	FROM even21encuestaaplica 
	WHERE even21idtercero=' . $idTercero . ' AND even21terminada="N" 
	ORDER BY even21obligatoria DESC, even21id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$res = $fila['even21idencuesta'];
		$idAplica = $fila['even21id'];
	}
	return array($res, $idAplica);
}
function f1926_Html_RespuestasV2($id21, $objDB, $iPregCondi = 0, $iOpcionCondi = 0, $bResaltarPendientes = false, $bDebug = false)
{
	require './app.php';
	$iPiel = iDefinirPiel($APP, 2);
	$sRes = '';
	$sDebug = '';
	$bPrincipal = true;
	$bPorResponder = true;
	$sTipoEncuesta = '';
	$sCondi = ' AND TB.even22idpregcondiciona=0';
	if ($iPregCondi != 0) {
		$bPrincipal = false;
		$sCondi = ' AND TB.even22idpregcondiciona=' . $iPregCondi . ' AND TB.even22valorcondiciona=' . $iOpcionCondi . '';
	}
	$sSQL = 'SELECT TB.even22id, TB.even22idpregunta, T18.even18pregunta, TB.even22tiporespuesta, TB.even22opcional, 
	TB.even22irespuesta, T18.even18concomentario, TB.even22nota, TB.even22divergente, TB.even22rpta1, 
	TB.even22rpta2, TB.even22rpta3, TB.even22rpta4, TB.even22rpta5, TB.even22rpta6, 
	TB.even22rpta7, TB.even22rpta8, TB.even22rpta9, T18.even18id
	FROM even22encuestarpta AS TB, even18encuestapregunta AS T18
	WHERE TB.even22idaplica=' . $id21 . $sCondi . ' AND TB.even22idpregunta=T18.even18id
	ORDER BY T18.even18orden, T18.even18consec';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Buscando preguntas ' . $sSQL . ' Divergente: ' . $iPregCondi . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($iPregCondi == 0) {
		//(Abril 10 de 2016) - Verificar que no sea una encuesta vacia
		if ($objDB->nf($tabla) == 0) {
			$sSQL21 = $sSQL;
			$sSQL = 'SELECT even21idencuesta
			FROM even21encuestaaplica
			WHERE even21id=' . $id21;
			$tabla21 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla21) > 0) {
				$fila21 = $objDB->sf($tabla21);
				$idEncuesta = $fila21['even21idencuesta'];
				f1926_ArmarTabla22($idEncuesta, $id21, $objDB);
				$tabla = $objDB->ejecutasql($sSQL21);
			}
		}
	}
	if ($bPrincipal) {
		$sSQL = 'SELECT TB.even21idencuesta, TB.even21terminada, TB.even21obligatoria, T1.even16idproceso
			FROM even21encuestaaplica AS TB, even16encuesta AS T1 
			WHERE TB.even21id=' . $id21 . ' AND TB.even21idencuesta=T1.even16id';
		$tabla21 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla21) > 0) {
			$fila21 = $objDB->sf($tabla21);
			if ($fila21['even21terminada'] == 'S') {
				$bPorResponder = false;
			}
			switch ($fila21['even16idproceso']) {
				case 1703:
					$sSQL = 'SELECT even17nombre FROM even17proceso WHERE even17id=' . $fila21['even16idproceso'] . '';
					$tabla17 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla17) > 0) {
						$fila17 = $objDB->sf($tabla17);
						$sTipoEncuesta = 'Encuesta ' . $fila17['even17nombre'];
					}
					break;
				default:
					break;
			}
		}
		$sRes = $sRes . '<div class="container-encuesta">';
		$sRes = $sRes . '<b>' . $sTipoEncuesta . '</b>';
		$sRes = $sRes . '<div class="encuesta-form">';
	}
	while ($fila = $objDB->sf($tabla)) {
		$sPregunta = '';
		$iDiverge = 0;
		$bRespuestaPendiente = true;
		if ($fila['even22divergente'] == 'S') {
			$iDiverge = 1;
		}
		switch ($fila['even22tiporespuesta']) {
			case 0: // SI - NO
				$sPregunta = $sPregunta . '<div class="group-radio">';
				$sValor = '';
				if ($fila['even22irespuesta'] == 0) {
					$sValor = 'N';
					$bRespuestaPendiente = false;
				}
				if ($fila['even22irespuesta'] == 1) {
					$sValor = 'S';
					$bRespuestaPendiente = false;
				}
				$sPregunta = $sPregunta . html_RadioV2('preg_' . $fila['even22id'], $sValor, 'S|N', 'Si|No', 'selrpta(' . $fila['even22id'] . ',this.value, 0, ' . $iDiverge . ')');
				$sPregunta = $sPregunta . '</div>';
				break;
			case 1: //Múltiple Opción unica respuesta.
				$sPregunta = $sPregunta . '<div class="group-radio">';
				$sValor = $fila['even22irespuesta'];
				if ($sValor != '-1') {
					$bRespuestaPendiente = false;
				}
				$sValores = '';
				$sEtiquetas = '';
				$sSQL = 'SELECT even29consec, even29etiqueta, even29detalle FROM even29encpregresp WHERE even29idpregunta=' . $fila['even22idpregunta'] . ' ORDER BY even29consec';
				$tabla29 = $objDB->ejecutasql($sSQL);
				$iFila = 0;
				while ($fila29 = $objDB->sf($tabla29)) {
					$sSepara = '|';
					if ($iFila == 0) {
						$sSepara = '';
					}
					$sValores = $sValores . $sSepara . $fila29['even29consec'];
					$sEtiquetas = $sEtiquetas . $sSepara . cadena_notildes($fila29['even29etiqueta']);
					$iFila++;
				}
				$sPregunta = $sPregunta . html_RadioV2('preg_' . $fila['even22id'], $sValor, $sValores, $sEtiquetas, 'selrpta(' . $fila['even22id'] . ',this.value, 1, ' . $iDiverge . ', ' . $fila['even18id'] . ')');
				$sPregunta = $sPregunta . '</div>';
				break;
			case 2: //Selección Múltiple
				$sPregunta = $sPregunta . '<div class="group-check">';
				$bRespuestaPendiente = false;
				$sValor = $fila['even22irespuesta'];
				$sValores = '';
				$sEtiquetas = '';
				$sSQL = 'SELECT even29consec, even29etiqueta, even29detalle FROM even29encpregresp WHERE even29idpregunta=' . $fila['even22idpregunta'] . ' ORDER BY even29consec';
				$tabla29 = $objDB->ejecutasql($sSQL);
				while ($fila29 = $objDB->sf($tabla29)) {
					$bMarcado = false;
					if ($fila['even22rpta' . $fila29['even29consec']] != 0) {
						$bMarcado = true;
					}
					$sPregunta = $sPregunta . html_checkV2('preg_' . $fila['even22id'] . '_' . $fila29['even29consec'], cadena_notildes($fila29['even29etiqueta']), $fila29['even29consec'], $bMarcado, 'marcaropcion(' . $fila['even22id'] . ',' . $fila29['even29consec'] . ',this.checked)');
				}
				$sPregunta = $sPregunta . '</div>';
				break;
			case 3: //Respuesta abierta
				if ($fila['even22nota'] != '') {
					$bRespuestaPendiente = false;
				}
				$sPregunta = $sPregunta . '<input id="rpta_' . $fila['even22id'] . '" name="rpta_' . $fila['even22id'] . '" type="text" value="' . $fila['even22nota'] . '" onchange="rptabierta(' . $fila['even22id'] . ', this.value)" style="width:98%;" placeholder="Ingrese aqu&iacute; su respuesta"/>';
				break;
		}
		if ($iDiverge) {
			list($sPreguntaDiv, $sDebugR) = f1926_Html_RespuestasV2($id21, $objDB, $fila['even22idpregunta'], $fila['even22irespuesta'], $bResaltarPendientes, $bDebug);
			$sDebug = $sDebug . $sDebugR;
			$sPregunta = $sPregunta . $sPreguntaDiv;
		}
		$sClassRespondida = '';
		if (!$bRespuestaPendiente) {
			$sClassRespondida = ' fill';
		} else {
			if ($bResaltarPendientes) {
				$sClassRespondida = ' error';
			}
		}
		$sRes = $sRes . '<div id="val_' . $fila['even18id'] . '" class="container-pregunta' . $sClassRespondida . '">';
		$sRes = $sRes . '<div class="pregunta">';
		$sRes = $sRes . '<p>' . cadena_notildes($fila['even18pregunta']) . '</p>';
		$sRes = $sRes . $sPregunta;
		$sRes = $sRes . '</div></div>';
	}
	if ($bPrincipal) {
		if ($bPorResponder) {
			$objForma = new clsHtmlForma($iPiel);
			$sRes = $sRes . $objForma->htmlBotonSolo('cmdTermina', 'botonAprobado', 'terminar()', 'Terminar encuesta');
		}
		$sRes = $sRes . '</div></div></table>';
	}
	return array($sRes, $sDebug);
}
function f1926_Html_Respuestas($id21, $objDB, $iPregCondi = 0, $iOpcionCondi = 0, $bResaltarPendientes = false)
{
	$res = '';
	$bPrincipal = true;
	$sCondi = ' AND TB.even22idpregcondiciona=0';
	if ($iPregCondi != 0) {
		$bPrincipal = false;
		$sCondi = ' AND TB.even22idpregcondiciona=' . $iPregCondi . ' AND TB.even22valorcondiciona=' . $iOpcionCondi . '';
	}
	$sSQL = 'SELECT TB.even22id, TB.even22idpregunta, T18.even18pregunta, TB.even22tiporespuesta, TB.even22opcional, TB.even22irespuesta, T18.even18concomentario, TB.even22nota, TB.even22divergente, TB.even22rpta1, TB.even22rpta2, TB.even22rpta3, TB.even22rpta4, TB.even22rpta5, TB.even22rpta6, TB.even22rpta7, TB.even22rpta8, TB.even22rpta9
	FROM even22encuestarpta AS TB, even18encuestapregunta AS T18
	WHERE TB.even22idaplica=' . $id21 . $sCondi . ' AND TB.even22idpregunta=T18.even18id
	ORDER BY T18.even18orden, T18.even18consec';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($iPregCondi == 0) {
		//(Abril 10 de 2016) - Verificar que no sea una encuesta vacia
		if ($objDB->nf($tabla) == 0) {
			$sSQL21 = $sSQL;
			$sSQL = 'SELECT even21idencuesta FROM even21encuestaaplica WHERE even21id=' . $id21;
			$tabla21 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla21) > 0) {
				$fila21 = $objDB->sf($tabla21);
				$idEncuesta = $fila21['even21idencuesta'];
				f1926_ArmarTabla22($idEncuesta, $id21, $objDB);
				$tabla = $objDB->ejecutasql($sSQL21);
			}
		}
	}
	if ($bPrincipal) {
		$sMarcaPendiente = '';
		if ($bResaltarPendientes) {
			$sMarcaPendiente = '<td style="width:20px;"></td>';
		}
		$res = '
		<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
		<tr><td style="min-width:220px;"></td><td style="min-width:260px;"></td>' . $sMarcaPendiente . '</tr>';
	}
	$tlinea = 0;
	while ($fila = $objDB->sf($tabla)) {
		$sClass = '';
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		$res = $res . '<tr' . $sClass . '>
		<td class="celda">' . cadena_notildes($fila['even18pregunta']) . '</td>
		<td>';
		$iDiverge = 0;
		$bRespuestaPendiente = true;
		if ($fila['even22divergente'] == 'S') {
			$iDiverge = 1;
		}
		switch ($fila['even22tiporespuesta']) {
			case 0: // SI - NO
				$sValor = '';
				if ($fila['even22irespuesta'] == 0) {
					$sValor = 'N';
					$bRespuestaPendiente = false;
				}
				if ($fila['even22irespuesta'] == 1) {
					$sValor = 'S';
					$bRespuestaPendiente = false;
				}
				//$res=$res.html_sino('preg_'.$fila['even18id'], $sValor, true, 'Seleccione');
				$res = $res . html_Radio('preg_' . $fila['even22id'], $sValor, 'S|N', 'Si|No', 'selrpta(' . $fila['even22id'] . ',this.value, 0, ' . $iDiverge . ')');
				break;
			case 1: //Múltiple Opción unica respuesta.
				$sValor = $fila['even22irespuesta'];
				if ($sValor != '-1') {
					$bRespuestaPendiente = false;
				}
				$sValores = '';
				$sEtiquetas = '';
				$sSQL = 'SELECT even29consec, even29etiqueta, even29detalle FROM even29encpregresp WHERE even29idpregunta=' . $fila['even22idpregunta'] . ' ORDER BY even29consec';
				$tabla29 = $objDB->ejecutasql($sSQL);
				$iFila = 0;
				while ($fila29 = $objDB->sf($tabla29)) {
					$sSepara = '|';
					if ($iFila == 0) {
						$sSepara = '';
					}
					$sValores = $sValores . $sSepara . $fila29['even29consec'];
					$sEtiquetas = $sEtiquetas . $sSepara . cadena_notildes($fila29['even29etiqueta']);
					$iFila++;
				}
				$res = $res . html_Radio('preg_' . $fila['even22id'], $sValor, $sValores, $sEtiquetas, 'selrpta(' . $fila['even22id'] . ',this.value, 1, ' . $iDiverge . ')');
				break;
			case 2: //Selección Múltiple
				$bRespuestaPendiente = false;
				$sValor = $fila['even22irespuesta'];
				$sValores = '';
				$sEtiquetas = '';
				$sSQL = 'SELECT even29consec, even29etiqueta, even29detalle FROM even29encpregresp WHERE even29idpregunta=' . $fila['even22idpregunta'] . ' ORDER BY even29consec';
				$tabla29 = $objDB->ejecutasql($sSQL);
				while ($fila29 = $objDB->sf($tabla29)) {
					$bMarcado = false;
					if ($fila['even22rpta' . $fila29['even29consec']] != 0) {
						$bMarcado = true;
					}
					$res = $res . html_check('preg_' . $fila['even22id'] . '_' . $fila29['even29consec'], cadena_notildes($fila29['even29etiqueta']), $fila29['even29consec'], $bMarcado, 'marcaropcion(' . $fila['even22id'] . ',' . $fila29['even29consec'] . ',this.checked)');
				}
				break;
			case 3: //Respuesta abierta
				//$bRespuestaPendiente=false;
				if ($fila['even22nota'] != '') {
					$bRespuestaPendiente = false;
				}
				$res = $res . '<input id="rpta_' . $fila['even22id'] . '" name="rpta_' . $fila['even22id'] . '" type="text" value="' . $fila['even22nota'] . '" onchange="rptabierta(' . $fila['even22id'] . ', this.value)" style="width:98%;" placeholder="Ingrese aqu&iacute; su respuesta"/>';
				break;
		}
		$sMarcaPendiente = '';
		if ($bResaltarPendientes) {
			if ($bRespuestaPendiente) {
				$sMarcaBase = ' style="background-color:#FF0000"';
			} else {
				$sMarcaBase = ' style="background-color:#003300"';
			}
			$sMarcaPendiente = '<td id="bd_' . $fila['even22id'] . '"' . $sMarcaBase . '></td>';
		}
		$res = $res . '</td>' . $sMarcaPendiente . '</tr>';
		if ($iDiverge) {
			$res = $res . f1926_Html_Respuestas($id21, $objDB, $fila['even22idpregunta'], $fila['even22irespuesta']);
		}
	}
	if ($bPrincipal) {
		$res = $res . '</table>';
	}
	return $res;
}
function f1926_CargarCuerpo($aParametros)
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
	$html_cuerpo = f1926_Html_Respuestas($aParametros[0], $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_respuestas', 'innerHTML', $html_cuerpo);
	return $objResponse;
}
function f1926_GuardaRptaV2($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$sError = '';
	$sDebug = '';
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$id22 = numeros_validar($aParametros[1]);
	$sRpta = htmlspecialchars(trim($aParametros[2]));
	$itipo = numeros_validar($aParametros[3]);
	$iDiverge = numeros_validar($aParametros[4]);
	$id21 = numeros_validar($aParametros[5]);
	$id18 = numeros_validar($aParametros[6]);
	$bDebug = numeros_validar($aParametros[9]);
	if ($id22 == '') {
		$id22 = 0;
	}
	if ($id22 > 0) {
		$bHayRespuesta = false;
		$ivalor = -1;
		switch ($itipo) {
			case 0: // SI - NO
				if ($sRpta == 'N') {
					$ivalor = 0;
					$bHayRespuesta = true;
				}
				if ($sRpta == 'S') {
					$ivalor = 1;
					$bHayRespuesta = true;
				}
				break;
			case 1: //Múltiple Opción unica respuesta.
				$ivalor = $sRpta;
				$bHayRespuesta = true;
				break;
		}
		$sSQL = 'UPDATE even22encuestarpta SET even22irespuesta=' . $ivalor . ' WHERE even22id=' . $id22;
		$tabla = $objDB->ejecutasql($sSQL);
		$objResponse = new xajaxResponse();
		if ($iDiverge == 1) {
			//Nov 1 de 2019 - La pregunta que condiciona viene con el id en que fue generado en la encuesta....
			$id18 = 0;
			$sSQL = 'SELECT even22idpregunta FROM even22encuestarpta WHERE even22id=' . $id22 . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$id18 = $fila['even22idpregunta'];
			}
			//Marcar todas las repuestas divergentes como no resueltas.
			$sSQL = 'UPDATE even22encuestarpta SET even22irespuesta=-1, even22rpta1=0, even22rpta2=0, even22rpta3=0, even22rpta4=0, even22rpta5=0, even22rpta6=0, even22rpta7=0, even22rpta8=0, even22rpta9=0 WHERE even22idaplica=' . $id21 . ' AND even22idpregcondiciona=' . $id18 . ' AND even22valorcondiciona<>' . $ivalor . '';
			$tabla = $objDB->ejecutasql($sSQL);
			//Mostrar el cuerpo nuevamente.
			list($html_cuerpo, $sDebugR) = f1926_Html_RespuestasV2($id21, $objDB, 0, 0, false, $bDebug);
			$sDebug = $sDebug . $sDebugR;
			$objResponse->assign('div_respuestas', 'innerHTML', $html_cuerpo);
			// $objResponse->call('mueveencuesta()');
			//$objResponse->assign('alarma', 'innerHTML', $sSQL);
		} else {
			//Ver si hubo una respuesta entonces poner en verde ultima columna
			$sClass = 'container-pregunta';
			if ($bHayRespuesta) {
				$sClass = 'container-pregunta fill';
			}
			$objResponse->assign('val_' . $id18, "className", $sClass);
		}
		if ($bDebug) {
			$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
		return $objResponse;
	}
}
function f1926_GuardaRpta($aParametros)
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
	$id22 = numeros_validar($aParametros[1]);
	$sRpta = htmlspecialchars(trim($aParametros[2]));
	$itipo = numeros_validar($aParametros[3]);
	$iDiverge = numeros_validar($aParametros[4]);
	$id21 = numeros_validar($aParametros[5]);
	if ($id22 == '') {
		$id22 = 0;
	}
	if ($id22 > 0) {
		$bHayRespuesta = false;
		$ivalor = -1;
		switch ($itipo) {
			case 0: // SI - NO
				if ($sRpta == 'N') {
					$ivalor = 0;
					$bHayRespuesta = true;
				}
				if ($sRpta == 'S') {
					$ivalor = 1;
					$bHayRespuesta = true;
				}
				break;
			case 1: //Múltiple Opción unica respuesta.
				$ivalor = $sRpta;
				$bHayRespuesta = true;
				break;
		}
		$sSQL = 'UPDATE even22encuestarpta SET even22irespuesta=' . $ivalor . ' WHERE even22id=' . $id22;
		$tabla = $objDB->ejecutasql($sSQL);
		$objResponse = new xajaxResponse();
		if ($iDiverge == 1) {
			//Nov 1 de 2019 - La pregunta que condiciona viene con el id en que fue generado en la encuesta....
			$id18 = 0;
			$sSQL = 'SELECT even22idpregunta FROM even22encuestarpta WHERE even22id=' . $id22 . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$id18 = $fila['even22idpregunta'];
			}
			//Marcar todas las repuestas divergentes como no resueltas.
			$sSQL = 'UPDATE even22encuestarpta SET even22irespuesta=-1, even22rpta1=0, even22rpta2=0, even22rpta3=0, even22rpta4=0, even22rpta5=0, even22rpta6=0, even22rpta7=0, even22rpta8=0, even22rpta9=0 WHERE even22idaplica=' . $id21 . ' AND even22idpregcondiciona=' . $id18 . ' AND even22valorcondiciona<>' . $ivalor . '';
			$tabla = $objDB->ejecutasql($sSQL);
			//Mostrar el cuerpo nuevamente.
			//$html_cuerpo=f1926_Html_Respuestas($id21, $objDB);
			//$objResponse->assign('div_respuestas', 'innerHTML', $html_cuerpo);
			$objResponse->call('mueveencuesta()');
			//$objResponse->assign('alarma', 'innerHTML', $sSQL);
		} else {
			//Ver si hubo una respuesta entonces poner en verde ultima columna
			if ($bHayRespuesta) {
				$objResponse->assign('bd_', '', '');
				$objResponse->assign('bd_' . $id22 . '', "style.background", "#003300");
			} else {
				$objResponse->assign('bd_' . $id22 . '', "style.background", "#FF0000");
			}
		}
		return $objResponse;
	}
}
function f1926_MarcarOpcion($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$sError = '';
	$id22 = numeros_validar($aParametros[1]);
	$iConsec = numeros_validar($aParametros[2]);
	$iValor = numeros_validar($aParametros[3]);
	if ($id22 == '') {
		$id22 = 0;
	}
	if ($id22 > 0) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'UPDATE even22encuestarpta SET even22rpta' . $iConsec . '=' . $iValor . ' WHERE even22id=' . $id22;
		$tabla = $objDB->ejecutasql($sSQL);
		$objDB->CerrarConexion();
		$objResponse = new xajaxResponse();
		$objResponse->assign('bd_' . $id22 . '', "style.background", "#003300");
		//$objResponse->assign('bd_'.$id22.'', 'innerHTML', 'si');
		return $objResponse;
	}
}
function f1926_GuadaAbierta($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$sError = '';
	$id22 = numeros_validar($aParametros[1]);
	$sValor = htmlspecialchars($aParametros[2]);
	if ($id22 == '') {
		$id22 = 0;
	}
	if ($id22 > 0) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		// if (get_magic_quotes_gpc() == 1) {
		// 	$sValor = stripslashes($sValor);
		// }
		$even16encabezado = str_replace('&quot;', '"', $sValor);
		$sSQL = 'UPDATE even22encuestarpta SET even22nota="' . $sValor . '" WHERE even22id=' . $id22;
		$tabla = $objDB->ejecutasql($sSQL);
		$objDB->CerrarConexion();
	}
}
function f1926_EncuestasPendientes($idTercero, $objDB)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1926 = 'lg/lg_1926_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1926)) {
		$mensajes_1926 = 'lg/lg_1926_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1926;
	$res = '';
	$sSQL = 'SELECT TB.even21id, TB.even21idencuesta, TB.even21obligatoria, T1.even16encabezado 
	FROM even21encuestaaplica AS TB, even16encuesta AS T1 
	WHERE TB.even21idtercero=' . $idTercero . ' AND TB.even21terminada="N" AND TB.even21idencuesta=T1.even16id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$res = '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
		<tr style="background-color:#00314A;color:#FFFFFF">
		<td colspan="2" align="center"><div class="MarquesinaMedia" style="margin:2px 10px;">' . $ETI['msg_pendientes'] . '</div></td>
		</tr>';
		$tlinea = 1;
		while ($fila = $objDB->sf($tabla)) {
			$sClass = '';
			$sLink = '';
			if (($tlinea % 2) == 0) {
				$sClass = ' class="resaltetabla"';
			}
			$tlinea++;
			$sLink = '<a href="javascript:resencuesta(' . $fila['even21id'] . ')" class="lnktablero">' . $ETI['msg_responder'] . '</a>';
			$res = $res . '<tr' . $sClass . '>
			<td>' . cadena_notildes($fila['even16encabezado']) . '</td>
			<td>' . $sLink . '</td>
			</tr>';
		}
		$res = $res . '</table>';
	}
	return $res;
}
function f1926_EncuestasPendientesV2($idTercero, $objDB)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1926 = 'lg/lg_1926_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1926)) {
		$mensajes_1926 = 'lg/lg_1926_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1926;
	$res = '';
	$iPiel = iDefinirPiel($APP);
	$sSQL = 'SELECT TB.even21id, TB.even21idencuesta, TB.even21obligatoria, T1.even16encabezado, TB.even21idcurso, T1.even16idproceso
	FROM even21encuestaaplica AS TB, even16encuesta AS T1 
	WHERE TB.even21idtercero=' . $idTercero . ' AND TB.even21terminada="N" AND TB.even21idencuesta=T1.even16id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$sClaseTabla = 'table--primary';
		if ($iPiel == 1) {
			$sClaseTabla = 'tablaapp';
		}
		$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
		$res = $res . '<thead class="fondoazul">';
		$res = $res . '<th colspan="2" align="center">' . $ETI['msg_pendientes'] . '</th>';
		$res = $res . '</tr></thead><tbody>';
		$tlinea = 1;
		while ($fila = $objDB->sf($tabla)) {
			$sClass = '';
			$sLink = '';
			if (($tlinea % 2) == 0) {
				$sClass = ' class="resaltetabla"';
			}
			$tlinea++;
			$sTituloCurso = '';
			$sTexto = cadena_notildes($fila['even16encabezado']);
			if ($fila['even21idcurso'] != 0) {
				list($sTituloCurso, $sCodigoCurso) = f140_NombreCurso($fila['even21idcurso'], $objDB, true);
				switch ($fila['even16idproceso']) {
					case 1703:
						$sProceso = '{' . $fila['even16idproceso'] . '}';
						$sSQL = 'SELECT even17nombre FROM even17proceso WHERE even17id=' . $fila['even16idproceso'] . '';
						$tabla17 = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla17) > 0) {
							$fila17 = $objDB->sf($tabla17);
							$sProceso = $fila17['even17nombre'];
						}
						$sTexto = 'Encuesta ' . $sProceso . ' Curso: ' . $sCodigoCurso . ' - ' . $sTituloCurso;
						break;
					default:
						$sTexto = $sTituloCurso;
						break;
				}
			}
			$sLink = '<a type="button" class="btn-primary" onclick="javascript:resencuesta(' . $fila['even21id'] . ')">' . $ETI['msg_responder'] . '</a>';
			$res = $res . '<tr' . $sClass . '>';
			$res = $res . '<td>' . $sTexto . '</td>';
			$res = $res . '<td>' . $sLink . '</td>';
			$res = $res . '</tr>';
		}
		$res = $res . '</tbody></table>';
		$res = $res . '<div class="salto5px"></div>';
	}
	return $res;
}
function f1926_EncuestaRapida($idTercero, $objDB, $bDebug = false)
{
	//Esta es una version reducida de f1926_CargarEncuestas
	$sDebug = '';
	$idEncuesta = 0;
	$sCuerpoPregunta = '';
	$sUrlSi = '';
	$sHoy = fecha_hoy();
	$idTablero = 0;
	//Por defecto se es -1 y si se requiere el dato se pasa a 1 o a 0
	$iEsEstudiante = -1;
	$iEsTutor = -1;
	$iEsDirector = -1;
	$sSQL = 'SELECT unad11idtablero FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idTablero = $fila['unad11idtablero'];
	} else {
		$sError = 'No se ha encontrado el tercero ref ' . $idTercero . '';
	}
	//Solo pedimos los procesos 17 (Encuesta rapida)
	$sSQL = 'SELECT even16consec, even16id, even16porperiodo, even16porcurso, even16porbloqueo, even16fechainicio, even16fechafin, even16caracter, even16idbloqueo, even16porrol 
	FROM even16encuesta 
	WHERE even16idproceso=17 AND even16publicada="S"
	ORDER BY even16consec DESC';
	$tabla16 = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Se han encontrado ' . $objDB->nf($tabla16) . ' encuestas publicadas<br>';
	}
	while ($fila16 = $objDB->sf($tabla16)) {
		//Primero descartamos que ya exista una.
		$bPasa = true;
		$sSQL = 'SELECT even21id FROM even21encuestaaplica WHERE even21idencuesta=' . $fila16['even16id'] . ' AND even21idtercero=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) != 0) {
			$bPasa = false;
		}
		if ($bPasa) {
			if ($fila16['even16porrol'] == 'S') {
				$sSQL = 'SELECT even32idrol 
				FROM even32encuestarol 
				WHERE even32idencuesta=' . $fila16['even16id'] . ' AND even32activo="S" AND even32idrol IN (3,4,5)';
				$tabla32 = $objDB->ejecutasql($sSQL);
				while ($fila32 = $objDB->sf($tabla32)) {
					//@@@@ De acuerdo a los roles que hayan determinar si aplican a ono.
				}
			}
		}
		if ($bPasa) {
			$sIds02 = '';
			if ($fila16['even16porperiodo'] == 'S') {
				$bPasa = false;
				$sIds02 = '-99';
				if ($bDebug) {
					$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta ' . $fila16['even16id'] . ' va por periodo<br>';
				}
				$sSQL = 'SELECT even24idperaca FROM even24encuestaperaca WHERE even24idencuesta=' . $fila16['even16id'] . ' AND STR_TO_DATE(even24fechainicial, "%d/%m/%Y")<=STR_TO_DATE("' . $sHoy . '", "%d/%m/%Y") AND STR_TO_DATE(even24fechafinal, "%d/%m/%Y")>=STR_TO_DATE("' . $sHoy . '", "%d/%m/%Y")';
				$tabla24 = $objDB->ejecutasql($sSQL);
				while ($fila24 = $objDB->sf($tabla24)) {
					$sIds02 = $sIds02 . ',' . $fila24['even24idperaca'];
				}
				//Solo si se confirma que esta en esos periodos pasa...
				//Esto aplica para estudiantes... 
				$sSQL = 'SELECT core04idcurso FROM core04matricula_' . $idTablero . ' WHERE core04peraca IN (' . $sIds02 . ') AND core04tercero=' . $idTercero . ' AND core04estado NOT IN (1,9,10) LIMIT 0,1';
				if ($bDebug) {
					$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Revisando si la encuesta ' . $fila16['even16id'] . ' aplica al usuario ' . $sSQL . '<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$bPasa = true;
				}
			}
		}
		if ($bPasa) {
			$idEncuesta = $fila16['even16id'];
			//Sacar la pregunta.
			$sSQL = 'SELECT even18pregunta, even18url FROM even18encuestapregunta WHERE even18idencuesta=' . $idEncuesta . ' ORDER BY even18consec';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sCuerpoPregunta = cadena_notildes($fila['even18pregunta']);
				$sUrlSi = $fila['even18url'];
			} else {
				$idEncuesta = 0;
			}
		}
		if ($bPasa) {
			break;
		}
	}
	return array($idEncuesta, $sCuerpoPregunta, $sUrlSi, $sDebug);
}
function f1926_RespuestaRapida($idTercero, $idEncuesta, $sRespuesta, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$idPeriodo = 0;
	$idCurso = 0;
	$idBloqueo = 0;
	$even21fechapresenta = fecha_hoy();
	$even21pais = '057';
	$even21depto = '';
	$even21ciudad = '';
	$even21fechanace = '';
	$even21agnos = 0;
	$even21perfil = 0;
	$even21idzona = 0;
	$even21idcead = 0;
	$even21idprograma = 0;
	$sSQL = 'SELECT * 
	FROM even21encuestaaplica 
	WHERE even21idtercero=' . $idTercero . ' 
	ORDER BY even21terminada DESC, even21id DESC 
	LIMIT 0,1';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$even21pais = $fila['even21pais'];
		$even21depto = $fila['even21depto'];
		$even21ciudad = $fila['even21ciudad'];
		$even21fechanace = $fila['even21fechanace'];
		$even21agnos = $fila['even21agnos'];
		$even21perfil = $fila['even21perfil'];
		$even21idzona = $fila['even21idzona'];
		$even21idcead = $fila['even21idcead'];
		$even21idprograma = $fila['even21idprograma'];
	}
	$even21id = tabla_consecutivo('even21encuestaaplica', 'even21id', '', $objDB);
	$sSQL21 = 'INSERT INTO even21encuestaaplica (even21idencuesta, even21idtercero, even21idperaca, even21idcurso, even21idbloquedo, 
	even21id, even21fechapresenta, even21terminada, even21pais, even21depto, 
	even21ciudad, even21fechanace, even21agnos, even21perfil, 
	even21idzona, even21idcead, even21idprograma, even21obligatoria) 
	VALUES (' . $idEncuesta . ', ' . $idTercero . ', ' . $idPeriodo . ', ' . $idCurso . ', ' . $idBloqueo . ', 
	' . $even21id . ', "' . $even21fechapresenta . '", "S", "' . $even21pais . '", "' . $even21depto . '", 
	"' . $even21ciudad . '", "' . $even21fechanace . '", ' . $even21agnos . ', ' . $even21perfil . ', 
	' . $even21idzona . ', ' . $even21idcead . ', ' . $even21idprograma . ', "N")';
	if ($bDebug) {
		$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta rapida, encabezado: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL21);
	if ($tabla == false) {
	} else {
		$campos22 = 'INSERT INTO even22encuestarpta (even22idaplica, even22idpregunta, even22id, even22tiporespuesta, even22opcional, 
		even22irespuesta, even22nota, even22relevante, even22rpta0, even22rpta1, 
		even22rpta2, even22rpta3, even22rpta4, even22rpta5, even22rpta6, 
		even22rpta7, even22rpta8, even22rpta9, even22divergente, even22idpregcondiciona, 
		even22valorcondiciona) VALUES ';
		$svalores22 = '';
		$even22id = tabla_consecutivo('even22encuestarpta', 'even22id', '', $objDB);
		//Traer las respuestas.
		$sSQL18 = 'SELECT even18id, even18tiporespuesta, even18opcional, even18divergente, even18idpregcondiciona, even18valorcondiciona 
		FROM even18encuestapregunta 
		WHERE even18idencuesta=' . $idEncuesta . '';
		$tabla18 = $objDB->ejecutasql($sSQL18);
		while ($fila18 = $objDB->sf($tabla18)) {
			if ($svalores22 != '') {
				$svalores22 = $svalores22 . ', ';
			}
			$svalores22 = $svalores22 . '(' . $even21id . ', ' . $fila18['even18id'] . ', ' . $even22id . ', ' . $fila18['even18tiporespuesta'] . ', "' . $fila18['even18opcional'] . '", 
			' . $sRespuesta . ', "", "N", 0, 0, 
			0, 0, 0, 0, 0, 
			0, 0, 0, "' . $fila18['even18divergente'] . '", ' . $fila18['even18idpregcondiciona'] . ', 
			' . $fila18['even18valorcondiciona'] . ')';
			$even22id++;
		}
		if ($svalores22 != '') {
			$tabla = $objDB->ejecutasql($campos22 . $svalores22);
			if ($bDebug) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' LIB1926 - Encuesta rapida, Detalle: ' . $campos22 . $svalores22 . '<br>';
			}
		}
	}
	return array($sError, $sDebug);
}

function f2238_Existe($idTercero, $idRol, $idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$bRes = false;
	$sDebug = '';
	return array($bRes, $sDebug);
}
