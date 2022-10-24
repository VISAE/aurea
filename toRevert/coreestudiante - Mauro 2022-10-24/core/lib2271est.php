<?php
/*
--- � Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versi�n 2.28.2 mi�rcoles, 1 de junio de 2022
--- 2271 Homologaciones externas
*/
function f2271_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2271 = 'lg/lg_2271_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2271)) {
		$mensajes_2271 = 'lg/lg_2271_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2271;
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
	/*
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	*/
	$idTercero = $aParametros[100];
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$core01id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		//$aParametros[103] = trim($aParametros[103]);
		//$aParametros[104] = numeros_validar($aParametros[104]);
	}
	$sDebug = '';
	$sLeyenda = '';
	$bAbierta = true;
	$sSQL = 'SELECT core01idtercero, core01idprograma, core01idplandeestudios FROM core01estprograma WHERE core01id=' . $core01id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$core01idtercero = $fila['core01idtercero'];
		$core01idprograma = $fila['core01idprograma'];
		$core01idplandeestudios = $fila['core01idplandeestudios'];
		if ($core01idplandeestudios == -1) {
			//$sLeyenda = 'Este registro no aplica a plan de estudio individual.';
		}
	} else {
		$sLeyenda = 'No se ha encontrado un registro de plan de estudio.';
	}
	$sBotones = '<input id="paginaf2271" name="paginaf2271" type="hidden" value="' . $pagina . '"/>
	<input id="lppf2271" name="lppf2271" type="hidden" value="' . $lineastabla . '"/>';
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
	$sSQLadd = '';
	$sSQLadd1 = '';
	/*
	if ((int)$aParametros[103] != -1) {
		$sSQLadd = $sSQLadd . ' AND TB.campo=' . $aParametros[103];
	}
	if ($aParametros[103] != '') {
		$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[103] . '%"';
	}
	*/
	/*
	if ($bNombre != '') {
		$sBase = strtoupper($bNombre);
		$aNoms=explode(' ', $sBase);
		for ($k=1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'T1.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	$sTitulos = 'Estprograma, Agno, Clasehomol, Consec, Id, Tipohomol, Estado, Fechasolicitud';
	//core01idtercero, core01idprograma, core01idplandeestudios
	$sSQL = 'SELECT TB.core71agno, T3.core65nombre, TB.core71consec, TB.core71id, T6.core66titulo, T7.core70nombre, 
	TB.core71fechasolicitud, TB.core71idclasehomol, TB.core71idtipohomol, TB.core71estado 
	FROM core71homolsolicitud AS TB, core65clasehomologa AS T3, core66tipohomologa AS T6, core70homolestado AS T7 
	WHERE ' . $sSQLadd1 . ' TB.core71idestudiante=' . $core01idtercero . ' AND TB.core71idprograma=' . $core01idprograma . ' AND TB.core71idplanest=' . $core01idplandeestudios . ' AND 
	TB.core71idclasehomol=T3.core65id AND TB.core71idtipohomol=T6.core66id AND TB.core71estado=T7.core70id ' . $sSQLadd . '
	ORDER BY TB.core71fechasolicitud DESC';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2271" name="consulta_2271" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_2271" name="titulos_2271" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2271: ' . $sSQL . '<br>';
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			$sLeyenda = $ETI['msg_nohabilitacion'];
			$sLeyenda = '<div class="salto1px"></div>
			<div class="GrupoCamposAyuda">
			' . $sLeyenda . '
			<div class="salto1px"></div>
			</div>';
			return array($sErrConsulta . $sLeyenda . $sBotones, $sDebug);
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
	<td><b>' . $ETI['core71fechasolicitud'] . '</b></td>
	<td><b>' . $ETI['core71idclasehomol'] . '</b></td>
	<td><b>' . $ETI['core71estado'] . '</b></td>
	<td><b>' . $ETI['core71idtipohomol'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf2271', $registros, $lineastabla, $pagina, 'paginarf2271()') . '
	' . html_lpp('lppf2271', $lineastabla, 'paginarf2271()') . '
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
		$et_core71idclasehomol = $sPrefijo . cadena_notildes($filadet['core65nombre']) . $sSufijo;
		$et_core71consec = $sPrefijo . $filadet['core71consec'] . $sSufijo;
		$et_core71idtipohomol = $sPrefijo . cadena_notildes($filadet['core66titulo']) . $sSufijo;
		$et_core71estado = $sPrefijo . cadena_notildes($filadet['core70nombre']) . $sSufijo;
		$et_core71fechasolicitud = '';
		if ($filadet['core71fechasolicitud'] != 0) {
			$et_core71fechasolicitud = $sPrefijo . fecha_desdenumero($filadet['core71fechasolicitud']) . $sSufijo;
		}
		if ($bAbierta) {
			//$sLink = '<a href="javascript:cargaridf2271(' . $filadet['core71id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_core71fechasolicitud . '</td>
		<td>' . $et_core71idclasehomol . '</td>
		<td>' . $et_core71estado . '</td>
		<td colspan="2">' . $et_core71idtipohomol . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f2271_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2271_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2271detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>
