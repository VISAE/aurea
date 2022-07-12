<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.2 jueves, 26 de mayo de 2022
--- 240 unae24historialcambdoc
*/
/** Archivo lib240.php.
* Libreria 240 unae24historialcambdoc.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 26 de mayo de 2022
*/
function elimina_archivo_unae24idarchivo($idPadre, $bDebug = false) {
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
		archivo_eliminar('unae24historialcambdoc', 'unae24id', 'unae24idorigen', 'unae24idarchivo', $idPadre, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_unae24idarchivo");
	} else {
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0);");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f240_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$unae24idtercero = numeros_validar($datos[1]);
	if ($unae24idtercero == '') {
		$bHayLlave = false;
	}
	$unae24consec = numeros_validar($datos[2]);
	if ($unae24consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM unae24historialcambdoc WHERE unae24idtercero=' . $unae24idtercero . ' AND unae24consec=' . $unae24consec . '';
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
function f240_Busquedas($aParametros)
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
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
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
	$aParametrosB=array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch($sCampo) {
		case 'unae24idtercero':
		require $APP->rutacomun . 'lib111.php';
		$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo = 'Busqueda de terceros';
		$sParams = f111_ParametrosBusqueda();
		$sJavaBusqueda = f111_JavaScriptBusqueda(240);
		break;
		case 'unae24idsolicita':
		require $APP->rutacomun . 'lib111.php';
		$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo = 'Busqueda de terceros';
		$sParams = f111_ParametrosBusqueda();
		$sJavaBusqueda = f111_JavaScriptBusqueda(240);
		break;
		case 'unae24idaprueba':
		require $APP->rutacomun . 'lib111.php';
		$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo = 'Busqueda de terceros';
		$sParams = f111_ParametrosBusqueda();
		$sJavaBusqueda = f111_JavaScriptBusqueda(240);
		break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_240'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f240_HtmlBusqueda($aParametros)
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
	switch($aParametros[100]) {
		case 'unae24idtercero':
		require $APP->rutacomun . 'lib111.php';
		$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'unae24idsolicita':
		require $APP->rutacomun . 'lib111.php';
		$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'unae24idaprueba':
		require $APP->rutacomun . 'lib111.php';
		$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f240_TablaDetalleV2($aParametros, $objDB, $bDebug = false) {
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	//$mensajes_200 = 'lg/lg_200_' . $_SESSION['unad_idioma'] . '.php';
	//if (!file_exists($mensajes_200)) {
		$mensajes_200 = 'lg/lg_200_es.php';
	//}
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';}
	require $mensajes_todas;
	//require $mensajes_200;
	require $mensajes_240;
	if(!is_array($aParametros)) {
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
	/*
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	*/
	$idTercero = $aParametros[100];
	$sDebug = '';
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		//$bNombre=trim($aParametros[103]);
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
	$sBotones = '<input id="paginaf240" name="paginaf240" type="hidden" value="' . $pagina . '"/>
	<input id="lppf240" name="lppf240" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 1);
	/*
	$aEstado=array();
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while($fila = $objDB->sf($tabla)) {
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
	*/
		/*
		if ($bNombre != '') {
			$sBase = strtoupper($bNombre);
			$aNoms=explode(' ', $sBase);
			for ($k=1; $k <= count($aNoms); $k++) {
				$sCadena = $aNoms[$k-1];
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
	$sTitulos = 'Tercero, Consec, Id, Tipodocorigen, Docorigen, Tipodocdestino, Docdestino, Solicita, Fechasol, Horasol, Minsol, Origen, Archivo, Estado, Detalle, Aprueba, Fechaapr, Horaaprueba, Minaprueba, Tiempod, Tiempoh';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	if ($bGigante) {
		$sSQL = 'SELECT COUNT(1) AS Total 
		FROM unae24historialcambdoc AS TB, unad11terceros AS T1, unad11terceros AS T8, unad11terceros AS T16 
		WHERE ' . $sSQLadd1 . ' TB.unae24idtercero=T1.unad11id AND TB.unae24idsolicita=T8.unad11id AND TB.unae24idaprueba=T16.unad11id ' . $sSQLadd . '';
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros-1)/$lineastabla)<($pagina-1)) {
			$pagina=(int)(($registros-1)/$lineastabla) + 1;
		}
		if ($registros>$lineastabla) {
			$rbase = ($pagina-1) * $lineastabla;
			$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
		}
	}
	$sSQL = 'SELECT T1.unad11razonsocial AS C1_nombre, TB.unae24consec, TB.unae24id, TB.unae24tipodocorigen, TB.unae24docorigen, TB.unae24tipodocdestino, TB.unae24docdestino, T8.unad11razonsocial AS C8_nombre, TB.unae24fechasol, TB.unae24horasol, TB.unae24minsol, TB.unae24idorigen, TB.unae24idarchivo, TB.unae24estado, TB.unae24detalle, T16.unad11razonsocial AS C16_nombre, TB.unae24fechaapr, TB.unae24horaaprueba, TB.unae24minaprueba, TB.unae24tiempod, TB.unae24tiempoh, TB.unae24idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, TB.unae24idsolicita, T8.unad11tipodoc AS C8_td, T8.unad11doc AS C8_doc, TB.unae24idaprueba, T16.unad11tipodoc AS C16_td, T16.unad11doc AS C16_doc 
	FROM unae24historialcambdoc AS TB, unad11terceros AS T1, unad11terceros AS T8, unad11terceros AS T16 
	WHERE ' . $sSQLadd1 . ' TB.unae24idtercero=T1.unad11id AND TB.unae24idsolicita=T8.unad11id AND TB.unae24idaprueba=T16.unad11id ' . $sSQLadd . '
	ORDER BY TB.unae24idtercero, TB.unae24consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta = '<input id="consulta_240" name="consulta_240" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_240" name="titulos_240" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 240: ' . $sSQL . $sLimite . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			//if ($registros == 0) {
				//return array($sErrConsulta.$sBotones, $sDebug);
				//}
			if ((($registros-1)/$lineastabla)<($pagina-1)) {
				$pagina=(int)(($registros-1)/$lineastabla) + 1;
			}
			if ($registros>$lineastabla) {
				$rbase = ($pagina-1) * $lineastabla;
				$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
				$tabladetalle = $objDB->ejecutasql($sSQL.$sLimite);
			}
		}
	}
	$res = $sErrConsulta.$sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['unae24fechasol'] . '</b></td>
	<td><b>' . $ETI['unae24horasol'] . '</b></td>
	<td><b>' . $ETI['unae24consec'] . '</b></td>
	<td><b>' . $ETI['unae24estado'] . '</b></td>
	<td><b>' . $ETI['unae24tipodocdestino'] . '</b></td>
	<td><b>' . $ETI['unae24docdestino'] . '</b></td>
	<td align="right">
	'.html_paginador('paginaf240', $registros, $lineastabla, $pagina, 'paginarf240()') . '
	'.html_lpp('lppf240', $lineastabla, 'paginarf240()') . '
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch($filadet['unae24estado']) {
			case 7:
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			break;
		}
		if(($tlinea%2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_unae24idtercero_doc = '';
		$et_unae24idtercero_nombre = '';
		if ($filadet['unae24idtercero'] != 0) {
			$et_unae24idtercero_doc = $sPrefijo.$filadet['C1_td'] . ' ' . $filadet['C1_doc'] . $sSufijo;
			$et_unae24idtercero_nombre = $sPrefijo.cadena_notildes($filadet['C1_nombre']).$sSufijo;
		}
		$et_unae24idsolicita_doc = '';
		$et_unae24idsolicita_nombre = '';
		if ($filadet['unae24idsolicita'] != 0) {
			$et_unae24idsolicita_doc = $sPrefijo.$filadet['C8_td'] . ' ' . $filadet['C8_doc'] . $sSufijo;
			$et_unae24idsolicita_nombre = $sPrefijo.cadena_notildes($filadet['C8_nombre']).$sSufijo;
		}
		$et_unae24fechasol = '';
		if ($filadet['unae24fechasol'] != 0) {
			$et_unae24fechasol = fecha_desdenumero($filadet['unae24fechasol']);
		}
		$et_unae24horasol = html_TablaHoraMin($filadet['unae24horasol'], $filadet['unae24minsol']);
		$et_unae24idarchivo = '';
		if ($filadet['unae24idarchivo'] != 0) {
			//$et_unae24idarchivo = '<img src="verarchivo.php?cont=' . $filadet['unae24idorigen'] . '&id=' . $filadet['unae24idarchivo'] . '&maxx=150"/>';
			$et_unae24idarchivo = html_lnkarchivo((int)$filadet['unae24idorigen'], (int)$filadet['unae24idarchivo']);
		}
		$et_unae24estado = $aunae24estado[$filadet['unae24estado']];
		$et_unae24idaprueba_doc = '';
		$et_unae24idaprueba_nombre = '';
		if ($filadet['unae24idaprueba'] != 0) {
			$et_unae24idaprueba_doc = $sPrefijo.$filadet['C16_td'] . ' ' . $filadet['C16_doc'] . $sSufijo;
			$et_unae24idaprueba_nombre = $sPrefijo.cadena_notildes($filadet['C16_nombre']).$sSufijo;
		}
		$et_unae24fechaapr = '';
		if ($filadet['unae24fechaapr'] != 0) {
			$et_unae24fechaapr = fecha_desdenumero($filadet['unae24fechaapr']);
		}
		$et_unae24horaaprueba = html_TablaHoraMin($filadet['unae24horaaprueba'], $filadet['unae24minaprueba']);
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf240(' . $filadet['unae24id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo.$et_unae24fechasol.$sSufijo . '</td>
		<td>' . $sPrefijo.$et_unae24horasol.$sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['unae24consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo.$et_unae24estado.$sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['unae24tipodocdestino']).$sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['unae24docdestino']).$sSufijo . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f240_HtmlTabla($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts=json_decode(str_replace('\"', '"', $opts), true);
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
	list($sDetalle, $sDebugTabla) = f240_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f240detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f240_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['unae24idtercero_td'] = $APP->tipo_doc;
	$DATA['unae24idtercero_doc'] = '';
	$DATA['unae24idsolicita_td'] = $APP->tipo_doc;
	$DATA['unae24idsolicita_doc'] = '';
	$DATA['unae24idaprueba_td'] = $APP->tipo_doc;
	$DATA['unae24idaprueba_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'unae24idtercero="' . $DATA['unae24idtercero'] . '" AND unae24consec=' . $DATA['unae24consec'] . '';
	} else {
		$sSQLcondi = 'unae24id=' . $DATA['unae24id'] . '';
	}
	$sSQL = 'SELECT * FROM unae24historialcambdoc WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['unae24idtercero'] = $fila['unae24idtercero'];
		$DATA['unae24consec'] = $fila['unae24consec'];
		$DATA['unae24id'] = $fila['unae24id'];
		$DATA['unae24tipodocorigen'] = $fila['unae24tipodocorigen'];
		$DATA['unae24docorigen'] = $fila['unae24docorigen'];
		$DATA['unae24tipodocdestino'] = $fila['unae24tipodocdestino'];
		$DATA['unae24docdestino'] = $fila['unae24docdestino'];
		$DATA['unae24idsolicita'] = $fila['unae24idsolicita'];
		$DATA['unae24fechasol'] = $fila['unae24fechasol'];
		$DATA['unae24horasol'] = $fila['unae24horasol'];
		$DATA['unae24minsol'] = $fila['unae24minsol'];
		$DATA['unae24idorigen'] = $fila['unae24idorigen'];
		$DATA['unae24idarchivo'] = $fila['unae24idarchivo'];
		$DATA['unae24estado'] = $fila['unae24estado'];
		$DATA['unae24detalle'] = $fila['unae24detalle'];
		$DATA['unae24idaprueba'] = $fila['unae24idaprueba'];
		$DATA['unae24fechaapr'] = $fila['unae24fechaapr'];
		$DATA['unae24horaaprueba'] = $fila['unae24horaaprueba'];
		$DATA['unae24minaprueba'] = $fila['unae24minaprueba'];
		$DATA['unae24tiempod'] = $fila['unae24tiempod'];
		$DATA['unae24tiempoh'] = $fila['unae24tiempoh'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta240'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f240_Cerrar($unae24id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f240_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0) {
	$iCodModulo = 240;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
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
	if (isset($DATA['unae24consec']) == 0) {
		$DATA['unae24consec'] = '';
	}
	if (isset($DATA['unae24id']) == 0) {
		$DATA['unae24id'] = '';
	}
	if (isset($DATA['unae24tipodocdestino']) == 0) {
		$DATA['unae24tipodocdestino'] = '';
	}
	if (isset($DATA['unae24docdestino']) == 0) {
		$DATA['unae24docdestino'] = '';
	}
	if (isset($DATA['unae24fechasol']) == 0) {
		$DATA['unae24fechasol'] = '';
	}
	if (isset($DATA['unae24estado']) == 0) {
		$DATA['unae24estado'] = '';
	}
	if (isset($DATA['unae24detalle']) == 0) {
		$DATA['unae24detalle'] = '';
	}
	if (isset($DATA['unae24fechaapr']) == 0) {
		$DATA['unae24fechaapr'] = '';
	}
	*/
	$DATA['unae24consec'] = numeros_validar($DATA['unae24consec']);
	$DATA['unae24tipodocorigen'] = htmlspecialchars(trim($DATA['unae24tipodocorigen']));
	$DATA['unae24docorigen'] = htmlspecialchars(trim($DATA['unae24docorigen']));
	$DATA['unae24tipodocdestino'] = htmlspecialchars(trim($DATA['unae24tipodocdestino']));
	$DATA['unae24docdestino'] = htmlspecialchars(trim($DATA['unae24docdestino']));
	$DATA['unae24horasol'] = numeros_validar($DATA['unae24horasol']);
	$DATA['unae24minsol'] = numeros_validar($DATA['unae24minsol']);
	$DATA['unae24idorigen'] = numeros_validar($DATA['unae24idorigen']);
	$DATA['unae24idarchivo'] = numeros_validar($DATA['unae24idarchivo']);
	$DATA['unae24detalle'] = htmlspecialchars(trim($DATA['unae24detalle']));
	$DATA['unae24horaaprueba'] = numeros_validar($DATA['unae24horaaprueba']);
	$DATA['unae24minaprueba'] = numeros_validar($DATA['unae24minaprueba']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['unae24horasol'] == '') {
		//$DATA['unae24horasol'] = 0;
	//}
	//if ($DATA['unae24minsol'] == '') {
		//$DATA['unae24minsol'] = 0;
	//}
	if ($DATA['unae24estado'] == '') {
		$DATA['unae24estado'] = 0;
	}
	//if ($DATA['unae24horaaprueba'] == '') {
		//$DATA['unae24horaaprueba'] = 0;
	//}
	//if ($DATA['unae24minaprueba'] == '') {
		//$DATA['unae24minaprueba'] = 0;
	//}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	switch ($DATA['unae24estado']) {
		case 0:
		case 3:
		if ($DATA['unae24fechasol'] == 0) {
			$DATA['unae24fechasol'] = fecha_DiaMod();
			$DATA['unae24horasol'] = fecha_hora();
			$DATA['unae24minsol'] = fecha_minuto();
			// $sError = $ERR['unae24fechasol'] . $sSepara.$sError;
			}
		if ($DATA['unae24idsolicita'] == 0) {
			$sError = $ERR['unae24idsolicita'] . $sSepara.$sError;
		}
		if ($DATA['unae24docdestino'] == '') {
			$sError = $ERR['unae24docdestino'] . $sSepara.$sError;
		}
		if ($DATA['unae24tipodocdestino'] == '') {
			$sError = $ERR['unae24tipodocdestino'] . $sSepara.$sError;
		}
		//Fin de las valiaciones NO LLAVE.
		if ($sError != '') {
			$DATA['unae24estado'] = 0;
		}
		$sErrorCerrando = $sError;
		// $sError = '';
		break;
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM unae24historialcambdoc WHERE unae24tipodocdestino="' . $DATA['unae24tipodocdestino'] . '" AND unae24docdestino="' . $DATA['unae24docdestino'] . '" AND unae24idsolicita=' . $DATA['unae24idsolicita'] . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) != 0) {
			$sError = $ERR['unae24solexistente'];
			} else {
			$bDevuelve = true;
			// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['2'];
			}
		}
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unae24idtercero'] == 0) {
		$sError = $ERR['unae24idtercero'];
	}
	// -- Tiene un cerrado.
	if ($DATA['unae24estado'] == 7) {
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando != '') {
			$DATA['unae24estado'] = 0;
			$sErrorCerrando = $sError . ' ' . $sErrorCerrando;
			$sError = '';
		} else {
			$bCerrando = true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['unae24idaprueba_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['unae24idaprueba_td'], $DATA['unae24idaprueba_doc'], $objDB, 'El tercero Aprueba ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['unae24idaprueba'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['unae24idsolicita_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['unae24idsolicita_td'], $DATA['unae24idsolicita_doc'], $objDB, 'El tercero Solicita ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['unae24idsolicita'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['unae24idtercero_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['unae24idtercero_td'], $DATA['unae24idtercero_doc'], $objDB, 'El tercero Tercero ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['unae24idtercero'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['unae24consec'] == '') {
				$DATA['unae24consec'] = tabla_consecutivo('unae24historialcambdoc', 'unae24consec', 'unae24idtercero=' . $DATA['unae24idtercero'] . '', $objDB);
				if ($DATA['unae24consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'unae24consec';
			} else {
				$bDevuelve = true;
				// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['unae24consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM unae24historialcambdoc WHERE unae24idtercero="' . $DATA['unae24idtercero'] . '" AND unae24consec=' . $DATA['unae24consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
					} else {
					$bDevuelve = true;
					// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve) {
						$sError = $ERR['2'];
					}
				}
			}
		} else {
			$bDevuelve = true;
			// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['unae24id'] = tabla_consecutivo('unae24historialcambdoc', 'unae24id', '', $objDB);
			if ($DATA['unae24id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['unae24tipodocorigen'] = $DATA['unae24idsolicita_td'];
			$DATA['unae24docorigen'] = $DATA['unae24idsolicita_doc'];
			//$DATA['unae24idsolicita'] = 0; //$_SESSION['u_idtercero'];
			$DATA['unae24idorigen'] = $DATA['unae24idsolicita'];
			$DATA['unae24idarchivo'] = 0;
			$DATA['unae24estado'] = 0;
			//$DATA['unae24idaprueba'] = 0; //$_SESSION['u_idtercero'];
			$DATA['unae24horaaprueba'] = 0;
			$DATA['unae24minaprueba'] = 0;
			$DATA['unae24tiempod'] = 0;
			$DATA['unae24tiempoh'] = 0;
		}
	}
	if ($sError == '') {
		//$unae24detalle=addslashes($DATA['unae24detalle']);
		$unae24detalle=str_replace('"', '\"', $DATA['unae24detalle']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos240 = 'unae24idtercero, unae24consec, unae24id, unae24tipodocorigen, unae24docorigen, 
			unae24tipodocdestino, unae24docdestino, unae24idsolicita, unae24fechasol, unae24horasol, 
			unae24minsol, unae24idorigen, unae24idarchivo, unae24estado, unae24detalle, 
			unae24idaprueba, unae24fechaapr, unae24horaaprueba, unae24minaprueba, unae24tiempod, 
			unae24tiempoh';
			$sValores240 = '' . $DATA['unae24idtercero'] . ', ' . $DATA['unae24consec'] . ', ' . $DATA['unae24id'] . ', "' . $DATA['unae24tipodocorigen'] . '", "' . $DATA['unae24docorigen'] . '", 
			"' . $DATA['unae24tipodocdestino'] . '", "' . $DATA['unae24docdestino'] . '", ' . $DATA['unae24idsolicita'] . ', ' . $DATA['unae24fechasol'] . ', ' . $DATA['unae24horasol'] . ', 
			' . $DATA['unae24minsol'] . ', ' . $DATA['unae24idorigen'] . ', ' . $DATA['unae24idarchivo'] . ', ' . $DATA['unae24estado'] . ', "' . $unae24detalle . '", 
			' . $DATA['unae24idaprueba'] . ', ' . $DATA['unae24fechaapr'] . ', ' . $DATA['unae24horaaprueba'] . ', ' . $DATA['unae24minaprueba'] . ', ' . $DATA['unae24tiempod'] . ', 
			' . $DATA['unae24tiempoh'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO unae24historialcambdoc (' . $sCampos240 . ') VALUES ('.utf8_encode($sValores240) . ');';
				$sdetalle = $sCampos240 . '[' . utf8_encode($sValores240) . ']';
			} else {
				$sSQL = 'INSERT INTO unae24historialcambdoc (' . $sCampos240 . ') VALUES (' . $sValores240 . ');';
				$sdetalle = $sCampos240 . '[' . $sValores240 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'unae24tipodocdestino';
			$scampo[2] = 'unae24docdestino';
			$scampo[3] = 'unae24fechasol';
			$scampo[4] = 'unae24estado';
			$scampo[5] = 'unae24detalle';
			$scampo[6] = 'unae24fechaapr';
			$sdato[1] = $DATA['unae24tipodocdestino'];
			$sdato[2] = $DATA['unae24docdestino'];
			$sdato[3] = $DATA['unae24fechasol'];
			$sdato[4] = $DATA['unae24estado'];
			$sdato[5] = $unae24detalle;
			$sdato[6] = $DATA['unae24fechaapr'];
			$numcmod=6;
			$sWhere = 'unae24id=' . $DATA['unae24id'] . '';
			$sSQL = 'SELECT * FROM unae24historialcambdoc WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE unae24historialcambdoc SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE unae24historialcambdoc SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 240 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [240] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['unae24id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
				$bCerrando = false;
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['unae24id'], $sdetalle, $objDB);
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
		list($sErrorCerrando, $sDebugCerrar) = f240_Cerrar($DATA['unae24id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugCerrar;
	}
	/*
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' InfoDepura<br>';
	}
	*/
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
}
function f240_db_Eliminar($unae24id, $objDB, $bDebug = false)
{
	$iCodModulo = 240;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$unae24id = numeros_validar($unae24id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM unae24historialcambdoc WHERE unae24id=' . $unae24id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $unae24id . '}';
		}
	}
	if ($sError == '') {
		if (isset($idTercero) == 0) {
			$idTercero = $_SESSION['unad_id_tercero'];
		}
		$bDevuelve = true;
		// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=240';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['unae24id'] . ' LIMIT 0, 1';
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
		$sWhere = 'unae24id=' . $unae24id . '';
		//$sWhere = 'unae24consec=' . $filabase['unae24consec'] . ' AND unae24idtercero="' . $filabase['unae24idtercero'] . '"';
		$sSQL = 'DELETE FROM unae24historialcambdoc WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae24id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f240_TituloBusqueda()
{
	return 'Busqueda de Solicitud de cambio de documento';
}
function f240_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b240nombre" name="b240nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f240_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo = window.document.frmedita.scampobusca.value;
	var params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b240nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f240_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
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
	$sBotones = '<input id="paginaf240" name="paginaf240" type="hidden" value="' . $pagina . '" />
	<input id="lppf240" name="lppf240" type="hidden" value="' . $lineastabla . '" />';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 1);
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ($aParametros[103] != '') {
		//$sSQLadd1 = $sSQLadd1 . 'TB.campo2 LIKE "%' . $aParametros[103] . '%" AND ';
	//}
	//if ($aParametros[103] != '') {
		//$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[103] . '%"';
	//}
	/*
	if ($aParametros[104] != '') {
		$sBase = trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k-1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'T1.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	$sTitulos = 'Tercero, Consec, Id, Tipodocorigen, Docorigen, Tipodocdestino, Docdestino, Solicita, Fechasol, Horasol, Minsol, Origen, Archivo, Estado, Detalle, Aprueba, Fechaapr, Horaaprueba, Minaprueba, Tiempod, Tiempoh';
	$sSQL = 'SELECT T1.unad11razonsocial AS C1_nombre, TB.unae24consec, TB.unae24id, TB.unae24tipodocorigen, TB.unae24docorigen, TB.unae24tipodocdestino, TB.unae24docdestino, T8.unad11razonsocial AS C8_nombre, TB.unae24fechasol, TB.unae24horasol, TB.unae24minsol, TB.unae24idorigen, TB.unae24idarchivo, TB.unae24estado, TB.unae24detalle, T16.unad11razonsocial AS C16_nombre, TB.unae24fechaapr, TB.unae24horaaprueba, TB.unae24minaprueba, TB.unae24tiempod, TB.unae24tiempoh, TB.unae24idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, TB.unae24idsolicita, T8.unad11tipodoc AS C8_td, T8.unad11doc AS C8_doc, TB.unae24idaprueba, T16.unad11tipodoc AS C16_td, T16.unad11doc AS C16_doc 
	FROM unae24historialcambdoc AS TB, unad11terceros AS T1, unad11terceros AS T8, unad11terceros AS T16 
	WHERE ' . $sSQLadd1 . ' TB.unae24idtercero=T1.unad11id AND TB.unae24idsolicita=T8.unad11id AND TB.unae24idaprueba=T16.unad11id ' . $sSQLadd . '
	ORDER BY TB.unae24idtercero, TB.unae24consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta = '<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="' . $sSQLlista . '" />
	<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="' . $sTitulos . '" />';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '" />';
		//$sLeyenda = $sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		//if ($registros == 0) {
			//return array($sErrConsulta.$sBotones, $sDebug);
			//}
		if ((($registros-1)/$lineastabla)<($pagina-1)) {
			$pagina=(int)(($registros-1)/$lineastabla) + 1;
		}
		if ($registros>$lineastabla) {
			$rbase = ($pagina-1) * $lineastabla;
			$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL.$limite);
		}
	}
	$res = $sErrConsulta.$sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td colspan="2"><b>' . $ETI['unae24idtercero'] . '</b></td>
	<td><b>' . $ETI['unae24consec'] . '</b></td>
	<td><b>' . $ETI['unae24tipodocorigen'] . '</b></td>
	<td><b>' . $ETI['unae24docorigen'] . '</b></td>
	<td><b>' . $ETI['unae24tipodocdestino'] . '</b></td>
	<td><b>' . $ETI['unae24docdestino'] . '</b></td>
	<td colspan="2"><b>' . $ETI['unae24idsolicita'] . '</b></td>
	<td><b>' . $ETI['unae24fechasol'] . '</b></td>
	<td><b>' . $ETI['unae24horasol'] . '</b></td>
	<td><b>' . $ETI['unae24idarchivo'] . '</b></td>
	<td><b>' . $ETI['unae24estado'] . '</b></td>
	<td><b>' . $ETI['unae24detalle'] . '</b></td>
	<td colspan="2"><b>' . $ETI['unae24idaprueba'] . '</b></td>
	<td><b>' . $ETI['unae24fechaapr'] . '</b></td>
	<td><b>' . $ETI['unae24horaaprueba'] . '</b></td>
	<td><b>' . $ETI['unae24tiempod'] . '</b></td>
	<td><b>' . $ETI['unae24tiempoh'] . '</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['unae24id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_unae24fechasol = '';
		if ($filadet['unae24fechasol'] != 0) {
			$et_unae24fechasol = fecha_desdenumero($filadet['unae24fechasol']);
		}
		$et_unae24horasol = html_TablaHoraMin($filadet['unae24horasol'], $filadet['unae24minsol']);
		$et_unae24idarchivo = '';
		if ($filadet['unae24idarchivo'] != 0) {
			//$et_unae24idarchivo = '<img src="verarchivo.php?cont=' . $filadet['unae24idorigen'] . '&id=' . $filadet['unae24idarchivo'] . '&maxx=150" />';
			$et_unae24idarchivo = html_lnkarchivo((int)$filadet['unae24idorigen'], (int)$filadet['unae24idarchivo']);
		}
		$et_unae24estado = $ETI['msg_abierto'];
		if ($filadet['unae24estado'] == 7) {
			$et_unae24estado = $ETI['msg_cerrado'];
		}
		$et_unae24fechaapr = '';
		if ($filadet['unae24fechaapr'] != 0) {
			$et_unae24fechaapr = fecha_desdenumero($filadet['unae24fechaapr']);
		}
		$et_unae24horaaprueba = html_TablaHoraMin($filadet['unae24horaaprueba'], $filadet['unae24minaprueba']);
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo.$filadet['C1_td'] . ' ' . $filadet['C1_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['C1_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['unae24consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['unae24tipodocorigen']) . $sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['unae24docorigen']) . $sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['unae24tipodocdestino'] . $sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['unae24docdestino']) . $sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['C8_td'] . ' ' . $filadet['C8_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['C8_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo.$et_unae24fechasol.$sSufijo . '</td>
		<td>' . $sPrefijo.$et_unae24horasol.$sSufijo . '</td>
		<td>' . $et_unae24idarchivo . '</td>
		<td>' . $sPrefijo.$et_unae24estado.$sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['unae24detalle'] . $sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['C16_td'] . ' ' . $filadet['C16_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['C16_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo.$et_unae24fechaapr.$sSufijo . '</td>
		<td>' . $sPrefijo.$et_unae24horaaprueba.$sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['unae24tiempod'] . $sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['unae24tiempoh'] . $sSufijo . '</td>
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