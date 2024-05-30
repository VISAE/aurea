<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 lunes, 23 de noviembre de 2015
--- Modelo Versión 2.18.1 viernes, 26 de mayo de 2017
--- Modelo Versión 2.22.3 miércoles, 15 de agosto de 2018
--- Modelo Versión 2.27.5b viernes, 4 de febrero de 2022
--- 107 unad07usuarios
*/
function f107_CodModulo($idApp)
{
	$iRes = 107;
	if ($idApp == 23) {
		$iRes = 2316;
	}
	return $iRes;
}
function f107_HTMLComboV2_unad07idperfil($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('unad07idperfil', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT unad05id AS id, unad05nombre AS nombre FROM unad05perfiles';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f107_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$unad07idperfil = numeros_validar($datos[1]);
	if ($unad07idperfil == '') {
		$bHayLlave = false;
	}
	$unad07idtercero = numeros_validar($datos[2]);
	if ($unad07idtercero == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM unad07usuarios WHERE unad07idperfil=' . $unad07idperfil . ' AND unad07idtercero=' . $unad07idtercero . '';
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
function f107_Busquedas($aParametros)
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
	$mensajes_107 = $APP->rutacomun . 'lg/lg_107_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_107)) {
		$mensajes_107 = $APP->rutacomun . 'lg/lg_107_es.php';
	}
	require $mensajes_todas;
	require $mensajes_107;
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
		case 'unad07idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(107);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_107'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f107_HtmlBusqueda($aParametros)
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
		case 'unad07idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f107_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_107 = $APP->rutacomun . 'lg/lg_107_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_107)) {
		$mensajes_107 = $APP->rutacomun . 'lg/lg_107_es.php';
	}
	require $mensajes_todas;
	require $mensajes_107;
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
	if (isset($aParametros[111]) == 0) {
		$aParametros[111] = '';
	}
	if (isset($aParametros[112]) == 0) {
		$aParametros[112] = '';
	}
	if (isset($aParametros[113]) == 0) {
		$aParametros[113] = '';
	}
	if (isset($aParametros[114]) == 0) {
		$aParametros[114] = '';
	}
	$idTercero = $aParametros[100];
	$sDebug = '';
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		$idAplicacion = $aParametros[103];
		$bDoc = $aParametros[111];
		$bNombre = $aParametros[112];
		$bPerfil = $aParametros[113];
		$bListar = numeros_validar($aParametros[114]);
		if ($lineastabla == 0) {
			$lineastabla = 20;
		}
	}
	$bAbierta = true;
	$sLeyenda = '';
	$sBotones = '<input id="paginaf107" name="paginaf107" type="hidden" value="' . $pagina . '"/>
	<input id="lppf107" name="lppf107" type="hidden" value="' . $lineastabla . '"/>';
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
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	if (true) {
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
		$sSQLadd = '';
		$sSQLadd1 = '';
		if ($bDoc != '') {
			$sSQLadd = $sSQLadd . ' AND T2.unad11doc LIKE "%' . $bDoc . '%"';
		}
		if ($bPerfil != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.unad07idperfil=' . $bPerfil . ' AND ';
		}
		switch ($bListar) {
			case 1:
				$sSQLadd1 = $sSQLadd1 . 'TB.unad07vigente="S" AND ';
				break;
			case 2:
				$sSQLadd1 = $sSQLadd1 . 'TB.unad07vigente="N" AND ';
				break;
		}
		if ($bNombre != '') {
			$sBase = strtoupper($bNombre);
			$aNoms = explode(' ', $sBase);
			for ($k = 1; $k <= count($aNoms); $k++) {
				$sCadena = $aNoms[$k - 1];
				if ($sCadena != '') {
					$sSQLadd = $sSQLadd . ' AND T2.unad11razonsocial LIKE "%' . $sCadena . '%"';
					//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	}
	//-- Area para saltar comparaciones con los archivos tipo e
	//-- Fin del area no comparada
	$sTitulos = 'Perfil,Documento,Tercero,Vigente,Fecha vence';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sWhereApp = '';
	if ($idAplicacion != 1) {
		$sIds = '-99';
		$sSQL = 'SELECT unad05id FROM unad05perfiles WHERE unad05aplicativo=' . $idAplicacion . ' AND unad05delegable="S" AND unad05reservado="N"';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['unad05id'];
		}
		$sWhereApp = 'TB.unad07idperfil IN (' . $sIds . ') AND ';
	}
	$sSQL = 'SELECT T1.unad05nombre, T2.unad11doc AS C2_doc, T2.unad11razonsocial AS C2_nombre, TB.unad07vigente, TB.unad07fechavence, TB.unad07idperfil, TB.unad07idtercero, T2.unad11tipodoc AS C2_td, T1.unad05reservado 
	FROM unad07usuarios AS TB, unad05perfiles AS T1, unad11terceros AS T2 
	WHERE ' . $sWhereApp . '' . $sSQLadd1 . ' TB.unad07idperfil=T1.unad05id  AND TB.unad07idtercero=T2.unad11id ' . $sSQLadd . ' 
	ORDER BY TB.unad07vigente DESC, T2.unad11razonsocial, T1.unad05nombre';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_107" name="consulta_107" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_107" name="titulos_107" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 107: ' . $sSQL . $sLimite . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			//if ($registros==0){
			//return array($sErrConsulta.$sBotones, $sDebug);
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
	<td><b>' . $ETI['unad07idperfil'] . '</b></td>
	<td colspan="2"><b>' . $ETI['unad07idtercero'] . '</b></td>
	<td><b>' . $ETI['unad07vigente'] . '</b></td>
	<td><b>' . $ETI['unad07fechavence'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf107', $registros, $lineastabla, $pagina, 'paginarf107()') . '
	' . html_lpp('lppf107', $lineastabla, 'paginarf107()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		if ($filadet['unad07vigente'] == 'S') {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_unad07vigente = $ETI['no'];
		if ($filadet['unad07vigente'] == 'S') {
			$et_unad07vigente = $ETI['si'];
		}
		$et_unad07fechavence = '';
		if ($filadet['unad07fechavence'] != '00/00/0000') {
			$et_unad07fechavence = $filadet['unad07fechavence'];
		}
		if ($bAbierta) {
			if ($filadet['unad05reservado'] == 'N') {
				$sLink = '<a href="javascript:cargadato(' . $filadet['unad07idperfil'] . "," . $filadet['unad07idtercero'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
			}
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad05nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unad07vigente . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unad07fechavence . $sSufijo . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f107_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f107_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f107detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f107_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['unad07idtercero_td'] = $APP->tipo_doc;
	$DATA['unad07idtercero_doc'] = '';
	$sSQL = 'SELECT * FROM unad07usuarios WHERE unad07idperfil=' . $DATA['unad07idperfil'] . ' AND unad07idtercero="' . $DATA['unad07idtercero'] . '"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['unad07idperfil'] = $fila['unad07idperfil'];
		$DATA['unad07idtercero'] = $fila['unad07idtercero'];
		$DATA['unad07vigente'] = $fila['unad07vigente'];
		$DATA['unad07fechavence'] = $fila['unad07fechavence'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta107'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f107_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$iCodModulo = f107_CodModulo($APP->idsistema);
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_107 = $APP->rutacomun . 'lg/lg_107_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_107)) {
		$mensajes_107 = $APP->rutacomun . 'lg/lg_107_es.php';
	}
	require $mensajes_todas;
	require $mensajes_107;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unad07idperfil'])==0){$DATA['unad07idperfil']='';}
	if (isset($DATA['unad07idtercero'])==0){$DATA['unad07idtercero']='';}
	if (isset($DATA['unad07vigente'])==0){$DATA['unad07vigente']='';}
	if (isset($DATA['unad07fechavence'])==0){$DATA['unad07fechavence']='';}
	*/
	$DATA['unad07idperfil'] = numeros_validar($DATA['unad07idperfil']);
	$DATA['unad07vigente'] = htmlspecialchars($DATA['unad07vigente']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente n�meros}.
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (!fecha_esvalida($DATA['unad07fechavence'])) {
		$DATA['unad07fechavence'] = '00/00/0000';
		//$serror=$ERR['unad07fechavence'];
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unad07vigente'] == '') {
		$sError = $ERR['unad07vigente'];
	}
	if ($DATA['unad07idtercero'] == 0) {
		$sError = $ERR['unad07idtercero'];
	}
	if ($DATA['unad07idperfil'] == '') {
		$sError = $ERR['unad07idperfil'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		$sError = tabla_terceros_existe($DATA['unad07idtercero_td'], $DATA['unad07idtercero_doc'], $objDB, 'El tercero Tercero ');
	}
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($DATA['unad07idtercero'], $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . sInfo;
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT unad07idperfil FROM unad07usuarios WHERE unad07idperfil=' . $DATA['unad07idperfil'] . ' AND unad07idtercero="' . $DATA['unad07idtercero'] . '"';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$sError = $ERR['existe'];
			} else {
				if (!seg_revisa_permiso($iCodModulo, 2, $objDB)) {
					$sError = $ERR['2'];
				}
			}
		} else {
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobaci�n.
		}
	}
	if ($sError == '') {
		$bpasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos107 = 'unad07idperfil, unad07idtercero, unad07vigente, unad07fechavence';
			$sValores107 = '' . $DATA['unad07idperfil'] . ', ' . $DATA['unad07idtercero'] . ', "' . $DATA['unad07vigente'] . '", "' . $DATA['unad07fechavence'] . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO unad07usuarios (' . $sCampos107 . ') VALUES (' . cadena_codificar($sValores107) . ');';
				$sdetalle = $sCampos107 . '[' . cadena_codificar($sValores107) . ']';
			} else {
				$sSQL = 'INSERT INTO unad07usuarios (' . $sCampos107 . ') VALUES (' . $sValores107 . ');';
				$sdetalle = $sCampos107 . '[' . $sValores107 . ']';
			}
			$idaccion = 2;
			$bpasa = true;
		} else {
			$scampo[1] = 'unad07vigente';
			$scampo[2] = 'unad07fechavence';
			$sdato[1] = $DATA['unad07vigente'];
			$sdato[2] = $DATA['unad07fechavence'];
			$numcmod = 2;
			$sWhere = 'unad07idperfil=' . $DATA['unad07idperfil'] . ' AND unad07idtercero="' . $DATA['unad07idtercero'] . '"';
			$sSQL = 'SELECT * FROM unad07usuarios WHERE ' . $sWhere;
			$sdatos = '';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				$bsepara = false;
				for ($k = 1; $k <= $numcmod; $k++) {
					if ($filabase[$scampo[$k]] != $sdato[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
						$bpasa = true;
					}
				}
			}
			if ($bpasa) {
				if ($APP->utf8 == 1) {
					$sdetalle = cadena_codificar($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE unad07usuarios SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE unad07usuarios SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idaccion = 3;
			}
		}
		if ($bpasa) {
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' ..<!-- ' . $sSQL . ' -->';
				$DATA['paso'] = $DATA['paso'] - 10;
			} else {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 107 ' . $sSQL . '<br>';
				}
				if ($bAudita[$idaccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, 0, $sdetalle, $objDB);
				}
				$DATA['paso'] = 2;
			}
		} else {
			$DATA['paso'] = 2;
		}
	} else {
		$DATA['paso'] = $DATA['paso'] - 10;
	}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f107_TituloBusqueda()
{
	return 'Busqueda de Usuarios';
}
function f107_ParametrosBusqueda()
{
	$sParams = '<label class="Label90">Nombre</label><label><input id="b107nombre" name="b107nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
}
function f107_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b107nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}

function frevisa_TablaDetalle($aParametros, $objDB)
{
	list($res, $sDebug) = frevisa_TablaDetalleV2($aParametros, $objDB);
	return $res;
}
function frevisa_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
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
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$aParametros[103] = trim($aParametros[103]);
	$bAbierta = true;
	$sDebug = '';
	$sLeyenda = '';
	if ($aParametros[106] == '') {
		if ($aParametros[103] == '') {
			if ($aParametros[104] == '') {
				$sLeyenda = 'Debe ingresar cuando menos un numero de documento o un codigo de modulo.';
			}
		}
	}
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . '<input id="paginaf108" name="paginaf108" type="hidden" value="' . $pagina . '"/><input id="lppf108" name="lppf108" type="hidden" value="' . $lineastabla . '"/>', $sDebug);
		die();
	}
	$aPermiso = array();
	$sSQL = 'SELECT unad03id, unad03nombre FROM unad03permisos';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aPermiso[$fila['unad03id']] = cadena_notildes($fila['unad03nombre']);
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sSQLadd2 = '';
	if ($aParametros[106] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.unad07idperfil=' . $aParametros[106] . ' AND ';
	}
	if ($aParametros[103] != '') {
		$sSQLadd2 = $sSQLadd2 . ' AND T11.unad11doc LIKE "%' . $aParametros[103] . '%"';
	}
	if ($aParametros[104] != '') {
		$sSQLadd = $sSQLadd . ' AND T1.unad06idmodulo=' . $aParametros[104] . '';
	}
	if ($aParametros[105] != '') {
		$sSQLadd = $sSQLadd . ' AND T1.unad06idpermiso=' . $aParametros[105] . '';
	}
	$sTitulos = 'Perfil, Tercero, Vigente, Fechavence';
	$sSQL = 'SELECT TB.unad07idtercero, TB.unad07idperfil, T1.unad06idmodulo, T1.unad06idpermiso, T3.unad05nombre, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial, T2.unad02nombre 
	FROM unad07usuarios AS TB, unad11terceros AS T11, unad06perfilmodpermiso AS T1, unad05perfiles AS T3, unad02modulos AS T2  
	WHERE ' . $sSQLadd1 . ' TB.unad07vigente="S" 
	AND TB.unad07idtercero=T11.unad11id ' . $sSQLadd2 . ' 
	AND TB.unad07idperfil=T1.unad06idperfil ' . $sSQLadd . ' AND T1.unad06vigente="S" 
	AND TB.unad07idperfil=T3.unad05id 
	AND T1.unad06idmodulo=T2.unad02id
	ORDER BY TB.unad07idperfil, T11.unad11doc, T1.unad06idmodulo, T1.unad06idpermiso';
	$sErrConsulta = '';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf107" name="paginaf107" type="hidden" value="'.$pagina.'"/><input id="lppf107" name="lppf107" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		}
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
	<td colspan="2"><b>Usuario</b></td>
	<td colspan="2"><b>Modulo</b></td>
	<td colspan="2"><b>Permiso</b></td>
	<td align="right">
	' . html_paginador('paginaf108', $registros, $lineastabla, $pagina, 'paginarfrevisa()') . '
	' . html_lpp('lppf108', $lineastabla, 'paginarfrevisa()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	//$idUsuario=-99;
	$idPerfil = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idPerfil != $filadet['unad07idperfil']) {
			$idPerfil = $filadet['unad07idperfil'];
			$sInfoPerfil = $filadet['unad07idperfil'] . ' ' . cadena_notildes($filadet['unad05nombre']);
			$res = $res . '<tr>
			<td colspan="7"><b>' . $sInfoPerfil . '</b></td>
			</tr>';
		}
		/*
		if ($idUsuario!=$filadet['unad07idtercero']){
			$idUsuario=$filadet['unad07idtercero'];
			$sNombreUsuario=$filadet['unad11tipodoc'].$filadet['unad11doc'].' '.cadena_notildes($filadet['unad11razonsocial']);
			$res=$res.'<tr>
			<td colspan="4"><b>'.$sNombreUsuario.'</b></td>
			</tr>';
			}
		*/

		$sprefijo = '';
		$ssufijo = '';
		$sClass = '';
		$sLink = '';
		if (false) {
			$sprefijo = '<b>';
			$ssufijo = '</b>';
		}
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		$sDocUsuario = $filadet['unad11tipodoc'] . $filadet['unad11doc'];
		$sNombreUsuario = cadena_notildes($filadet['unad11razonsocial']);
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sprefijo . $sDocUsuario . $ssufijo . '</td>
		<td>' . $sprefijo . $sNombreUsuario . $ssufijo . '</td>
		<td>' . $sprefijo . $filadet['unad06idmodulo'] . $ssufijo . '</td>
		<td>' . $sprefijo . cadena_notildes($filadet['unad02nombre']) . $ssufijo . '</td>
		<td>' . $sprefijo . $filadet['unad06idpermiso'] . $ssufijo . '</td>
		<td colspan="2">' . $sprefijo . $aPermiso[$filadet['unad06idpermiso']] . $ssufijo . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function frevisa_HtmlTabla($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$bAbierta = true;
	list($sDetalle, $sDebugT) = frevisa_TablaDetalleV2($aParametros, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign("div_frevisadetalle", "innerHTML", $sDetalle . '');
	return $objResponse;
}
function f107_ProcesarArchivo($DATA, $ARCHIVO, $objDB, $bDebug = false)
{
	$iCodModulo = 107;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$sArchivo = $ARCHIVO['archivodatos']['tmp_name'];
	$sVerExcel = 'Excel2007';
	switch ($ARCHIVO['archivodatos']['type']) {
		case 'application/vnd.ms-excel':
			$sVerExcel = 'Excel5';
			break;
		case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			break;
		case '':
		case 'application/download':
			$sExt = strtolower(substr($sArchivo, strlen($sArchivo) - 4));
			switch ($sExt) {
				case '.xls':
					$sVerExcel = 'Excel5';
					break;
				case 'xlsx':
					break;
				default:
					$sError = 'Tipo de archivo no permitido {' . $ARCHIVO['archivodatos']['type'] . ' - ' . $sExt . ' - ' . $sArchivo . '}';
			}
			break;
		default:
			$sError = 'Tipo de archivo no permitido {' . $ARCHIVO['archivodatos']['type'] . '}';
	}
	if ($sError == '') {
		if ($DATA['masidperfil'] == '') {
			$sError = 'No ha seleccionado el perfil al cual se importar&aacute;n los usuarios';
		}
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 2, $objDB)) {
			$sError = $ERR['2'];
		}
	}
	if ($sError == '') {
		require $APP->rutacomun . 'excel/PHPExcel.php';
		require $APP->rutacomun . 'excel/PHPExcel/Writer/Excel2007.php';
		$objReader = PHPExcel_IOFactory::createReader($sVerExcel);
		$objPHPExcel = @$objReader->load($sArchivo);
		if (!is_object(@$objPHPExcel->getActiveSheet())) {
			$sError = 'El archivo se cargo en forma correcta, pero no fue posible leerlo en ' . $sVerExcel;
		}
	}
	if ($sError == '') {
		$iFila = 1;
		$iDatos = 0;
		$iActualizados = 0;
		$iHabilitados = 0;
		$sFecha = '00/00/0000';
		$idPerfil = $DATA['masidperfil'];
		$sDocFallido = '';
		//$sCampos107='';
		//$=tabla_consecutivo('unad07usuarios','', '', $objDB);
		$sDato = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue();
		while ($sDato != '') {
			$iFila++;
			$idTercero = 0;
			//Traer el tercero
			$sDoc = numeros_validar($sDato);
			if ($sDoc != '') {
				$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $sDoc . '" AND unad11tipodoc="CC"';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$idTercero = $fila['unad11id'];
				} else {
					//Tratar de importarlo.
					unad11_importar_V2($sDoc, '', $objDB);
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$idTercero = $fila['unad11id'];
					}
				}
				if ($idTercero == 0) {
					if ($sDocFallido != '') {
						$sDocFallido = $sDocFallido . ', ';
					}
					$sDocFallido = $sDocFallido . $sDoc;
				}
			}
			if ($idTercero > 0) {
				$sSQL = 'SELECT unad07vigente FROM unad07usuarios WHERE unad07idtercero=' . $idTercero . ' AND unad07idperfil=' . $idPerfil . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					if ($fila['unad07vigente'] != 'S') {
						$sSQL = 'UPDATE unad07usuarios SET unad07vigente="S" WHERE unad07idtercero=' . $idTercero . ' AND unad07idperfil=' . $idPerfil . '';
						$tabla = $objDB->ejecutasql($sSQL);
						$iActualizados++;
					}
				} else {
					$sSQL = 'INSERT INTO unad07usuarios(unad07idperfil, unad07idtercero, unad07vigente, unad07fechavence) VALUES (' . $idPerfil . ', ' . $idTercero . ', "S", "' . $sFecha . '")';
					$tabla = $objDB->ejecutasql($sSQL);
					$iDatos++;
				}
			}
			$sDato = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue();
		}
		$sError = 'Registros totales ' . $iDatos;
		$iTipoError = 1;
		if ($iActualizados > 0) {
			$sError = $sError . ' - Registros actualizados ' . $iActualizados;
		}
		if ($sDocFallido != '') {
			if (($iActualizados + $iHabilitados) == 0) {
				$iTipoError = 0;
			}
			$sError = $sError . '<br>Documentos no encontrados: ' . $sDocFallido;
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f107_TablaDetallePorTerceroV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_107 = $APP->rutacomun . 'lg/lg_107_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_107)) {
		$mensajes_107 = $APP->rutacomun . 'lg/lg_107_es.php';
	}
	require $mensajes_todas;
	require $mensajes_107;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	$aParametros[100] = numeros_validar($aParametros[100]);
	if ($aParametros[100] == '') {
		$aParametros[100] = -999;
	}
	if ($aParametros[100] == 0) {
		$aParametros[100] = -999;
	}
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = false;
	$sDebug = '';
	$sSQLadd = '';
	$sBoton = '';
	if ($aParametros[100] > 0) {
		$sBoton = '
		<label></label>
		<label class="Label130">
		<input id="cmdActualizarPerfiles" name="cmdActualizarPerfiles" type="button" class="BotonAzul160" onclick="actualizarperfiles()" value="Actualizar Perfiles" title="Actualizar Perfiles"/>
		</label>
		<div class="salto1px"></div>';
	}
	$sTitulos = 'Perfil, Tercero, Vigente, Fechavence';
	$sSQL = 'SELECT T1.unad05nombre, T2.unad11razonsocial AS C2_nombre, TB.unad07vigente, TB.unad07fechavence, TB.unad07idperfil, TB.unad07idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc 
	FROM unad07usuarios AS TB, unad05perfiles AS T1, unad11terceros AS T2 
	WHERE TB.unad07idperfil=T1.unad05id AND TB.unad07idtercero=T2.unad11id AND TB.unad07idtercero=' . $aParametros[100] . ' ' . $sSQLadd . ' 
	ORDER BY T2.unad11razonsocial, T1.unad05nombre';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_107" name="consulta_107" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_107" name="titulos_107" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			//return cadena_codificar($sErrConsulta.'<input id="paginaf107" name="paginaf107" type="hidden" value="'.$pagina.'"/><input id="lppf107" name="lppf107" type="hidden" value="'.$lineastabla.'"/>');
			//break;
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
		}
	}
	$res = $sErrConsulta . $sBoton . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<tr class="fondoazul">
	<td><b>' . $ETI['unad07idperfil'] . '</b></td>
	<td><b>' . $ETI['unad07vigente'] . '</b></td>
	<td><b>' . $ETI['unad07fechavence'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf107', $registros, $lineastabla, $pagina, 'paginarf107()') . '
	' . html_lpp('lppf107', $lineastabla, 'paginarf107()') . '
	</td>
	</tr>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = '';
		$sLink = '';
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		$et_unad07vigente = 'Si';
		if ($filadet['unad07vigente'] != 'S') {
			$et_unad07vigente = 'No';
			$sPrefijo = '<span class="rojo">';
			$sSufijo = '</span>';
		}
		$et_unad07fechavence = '';
		if ($filadet['unad07fechavence'] != '00/00/0000') {
			$et_unad07fechavence = $filadet['unad07fechavence'];
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargadato(' . "'" . $filadet['unad07idperfil'] . "','" . $filadet['unad07idtercero'] . "'" . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['unad07idperfil'] . ' - ' . cadena_notildes($filadet['unad05nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unad07vigente . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unad07fechavence . $sSufijo . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>';
	return array(cadena_codificar($res), $sDebug);
}
function f107_HtmlTablaPorTercero($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sDetalle, $sDebug) = f107_TablaDetallePorTerceroV2($aParametros, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f107detalle', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f107_TablaDetalleV2Perfil($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_107 = $APP->rutacomun . 'lg/lg_107_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_107)) {
		$mensajes_107 = $APP->rutacomun . 'lg/lg_107_es.php';
	}
	require $mensajes_todas;
	require $mensajes_107;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
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
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$sDebug = '';
	$unad11id = $aParametros[0];
	$idTercero = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$idSistema = $aParametros[103];
	$bAbierta = true;
	//$sSQL='SELECT Campo FROM unad11terceros WHERE unad11id='.$unad11id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sLeyenda = '';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
	}
	/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos = 'Perfil, Vigente, Fecha vence';
	$sSQL = 'SELECT T1.unad05nombre, TB.unad07vigente, TB.unad07fechavence, TB.unad07idtercero, TB.unad07idperfil 
	FROM unad07usuarios AS TB, unad05perfiles AS T1 
	WHERE TB.unad07idtercero=' . $idTercero . ' AND ' . $sSQLadd1 . ' TB.unad07idperfil=T1.unad05id ' . $sSQLadd . '
	ORDER BY TB.unad07vigente DESC, T1.unad05nombre';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_107" name="consulta_107" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_107" name="titulos_107" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 107: ' . $sSQL . '<br>';
		}
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			return array(cadena_codificar($sErrConsulta . '<input id="paginaf107" name="paginaf107" type="hidden" value="' . $pagina . '"/><input id="lppf107" name="lppf107" type="hidden" value="' . $lineastabla . '"/>'), $sDebug);
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
		}
	}
	$res = $sErrConsulta . $sLeyenda . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<tr class="fondoazul">
	<td colspan="2"><b>' . $ETI['unad07idperfil'] . '</b></td>
	<td><b>' . $ETI['unad07vigente'] . '</b></td>
	<td><b>' . $ETI['unad07fechavence'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf107', $registros, $lineastabla, $pagina, 'paginarf107()') . '
	' . html_lpp('lppf107', $lineastabla, 'paginarf107()') . '
	</td>
	</tr>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = '';
		$sLink = '';
		if ($filadet['unad07vigente'] == 'S') {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		$et_unad07idperfil = $sPrefijo . cadena_notildes($filadet['unad05nombre']) . $sSufijo;
		$et_unad07vigente = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['unad07vigente'] == 'S') {
			$et_unad07vigente = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_unad07fechavence = '';
		if ($filadet['unad07fechavence'] != '00/00/0000') {
			$et_unad07fechavence = $sPrefijo . $filadet['unad07fechavence'] . $sSufijo;
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['unad07idperfil'] . $sSufijo . '</td>
		<td>' . $et_unad07idperfil . '</td>
		<td>' . $et_unad07vigente . '</td>
		<td colspan="2">' . $et_unad07fechavence . '</td>
		</tr>';
	}
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f107_HtmlTablaPerfil($aParametros)
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
	list($sDetalle, $sDebugTabla) = f107_TablaDetalleV2Perfil($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f107detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
