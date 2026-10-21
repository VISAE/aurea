<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.1 miércoles, 18 de enero de 2023
--- 2825 gthu54hvlaboral
*/
/** Archivo lib2825.php.
 * Libreria 2825 gthu54hvlaboral.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date miércoles, 18 de enero de 2023
 */
function f2825_Busquedas($aParametros)
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
	$mensajes_2825 = 'lg/lg_2825_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2825)) {
		$mensajes_2825 = 'lg/lg_2825_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2825;
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
	}
	$sTitulo = '<h2>' . $ETI['titulo_2825'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f2825_HtmlBusqueda($aParametros)
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
function f2825_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_2800 = 'lg/lg_2800_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2800)) {
		$mensajes_2800 = 'lg/lg_2800_es.php';
	}
	require $mensajes_2800;
	*/
	$mensajes_2825 = 'lg/lg_2825_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2825)) {
		$mensajes_2825 = 'lg/lg_2825_es.php';
	}
	require $mensajes_2825;
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
	for ($k = 103; $k <= 111; $k++) {
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
		$gthu54fecha = $aParametros[103];
		$gthu54condicion = $aParametros[104];
		$gthu54idcargo = $aParametros[105];
		$gthu54idescuela = $aParametros[106];
		$gthu54iddepedencia = $aParametros[107];
		$gthu54vercondicion = $aParametros[108];
		$gthu54vercargo = $aParametros[109];
		$gthu54verescuela = $aParametros[110];
		$gthu54verdepedencia = $aParametros[111];
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
	$sBotones = '<input id="paginaf2825" name="paginaf2825" type="hidden" value="' . $pagina . '"/>
	<input id="lppf2825" name="lppf2825" type="hidden" value="' . $lineastabla . '"/>';
	if (fecha_NumValido($gthu54fecha)) {
	} else {
		$sLeyenda = 'La fecha ingresada no es correcta.';
	}
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
	if (true) {
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
		$sSQLadd = '';
		$sSQLadd1 = '';
		if ($gthu54condicion != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.gthu54condicion=' . $gthu54condicion . ' AND ';
		}
		if ($gthu54idcargo != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.gthu54idcargo=' . $gthu54idcargo . ' AND ';
		}
		if ($gthu54idescuela != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.gthu54idescuela=' . $gthu54idescuela . ' AND ';
		}
		if ($gthu54iddepedencia != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.gthu54iddepedencia=' . $gthu54iddepedencia . ' AND ';
		}
		/*
		if ($gthu54vercondicion != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.gthu54vercondicion=' . $gthu54vercondicion . ' AND ';
		}
		if ($gthu54vercargo != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.gthu54vercargo=' . $gthu54vercargo . ' AND ';
		}
		if ($gthu54verescuela != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.gthu54verescuela=' . $gthu54verescuela . ' AND ';
		}
		if ($gthu54verdepedencia != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.gthu54verdepedencia=' . $gthu54verdepedencia . ' AND ';
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
	$sTitulos = 'Fecha, Condicion, Cargo, Escuela, Depedencia, Vercondicion, Vercargo, Verescuela, Verdepedencia';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCamposBase = '';
	$sTablas = '';
	$sCamoposGrupo = '';
	$sCamposOrden = '';
	if ($gthu54vercondicion == 1) {
		$sCamposBase = 'T52.gthu52nombre, ';
		$sTablas = ', gthu52hvcondicion AS T52';
		$sSQLadd = $sSQLadd . ' AND TB.gthu54condicion=T52.gthu52id ';
		$sCamoposGrupo = 'T52.gthu52nombre';
		$sCamposOrden = 'T52.gthu52nombre';
	}
	if ($gthu54vercargo == 1) {
		$sCamposBase = $sCamposBase. 'T1.gthv01codcargo, T1.gthv01nombre, ';
		$sTablas = $sTablas . ', gthv01cargo AS T1';
		$sSQLadd = $sSQLadd . ' AND TB.gthu54idcargo=T1.gthv01id ';
		if ($sCamoposGrupo != '') {
			$sCamoposGrupo = $sCamoposGrupo . ', ';
		}
		if ($sCamposOrden != '') {
			$sCamposOrden = $sCamposOrden . ', ';
		}	
		$sCamoposGrupo = $sCamoposGrupo . 'T1.gthv01codcargo, T1.gthv01nombre';
		$sCamposOrden = $sCamposOrden . 'T1.gthv01nombre, T1.gthv01codcargo';
	}
	if ($gthu54verescuela == 1) {
		$sCamposBase = $sCamposBase. 'T12.core12nombre, ';
		$sTablas = $sTablas . ', core12escuela AS T12';
		$sSQLadd = $sSQLadd . ' AND TB.gthu54idescuela=T12.core12id ';
		if ($sCamoposGrupo != '') {
			$sCamoposGrupo = $sCamoposGrupo . ', ';
		}
		if ($sCamposOrden != '') {
			$sCamposOrden = $sCamposOrden . ', ';
		}	
		$sCamoposGrupo = $sCamoposGrupo . 'T12.core12nombre';
		$sCamposOrden = $sCamposOrden . 'T12.core12nombre';
	}
	if ($gthu54verdepedencia == 1) {
		$sCamposBase = $sCamposBase. 'T24.unad24nombre, ';
		$sTablas = $sTablas . ', unad24sede AS T24';
		$sSQLadd = $sSQLadd . ' AND TB.gthu54iddepedencia=T24.unad24id ';
		if ($sCamoposGrupo != '') {
			$sCamoposGrupo = $sCamoposGrupo . ', ';
		}
		if ($sCamposOrden != '') {
			$sCamposOrden = $sCamposOrden . ', ';
		}	
		$sCamoposGrupo = $sCamoposGrupo . 'T24.unad24nombre ';
		$sCamposOrden = $sCamposOrden . 'T24.unad24nombre';
	}
	if ($sCamoposGrupo != '') {
		$sCamoposGrupo = ' GROUP BY ' . $sCamoposGrupo;
	}
	if ($sCamposOrden != '') {
		$sCamposOrden = ' ORDER BY ' . $sCamposOrden;
	}
	$sCampos = 'SELECT ' . $sCamposBase . ' COUNT(1) AS Total';
	$sConsulta = 'FROM gthu54hvlaboral AS TB' . $sTablas . ' 
	WHERE ' . $sSQLadd1 . ' gthu54ifechaingreso<=' . $gthu54fecha . ' AND ((gthu54ifechafin=0) OR (gthu54ifechafin>=' . $gthu54fecha . '))
	AND gthu54funcionario=1 
	 ' . $sSQLadd . '
	' . $sCamoposGrupo . '';
	$sOrden = '' . $sCamposOrden . '';
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
	$sErrConsulta = '<input id="consulta_2825" name="consulta_2825" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_2825" name="titulos_2825" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2825: ' . $sSQL . '<br>';
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
	$sTituloTabla = '';
	if ($gthu54vercondicion == 1) {
		$sTituloTabla = '<td><b>' . $ETI['gthu54condicion'] . '</b></td>';
	}
	if ($gthu54vercargo == 1) {
		$sTituloTabla = $sTituloTabla . '<td colspan="2"><b>' . $ETI['gthu54idcargo'] . '</b></td>';
	}
	if ($gthu54verescuela == 1) {
		$sTituloTabla = $sTituloTabla . '<td><b>' . $ETI['gthu54idescuela'] . '</b></td>';
	}
	if ($gthu54verdepedencia == 1) {
		$sTituloTabla = $sTituloTabla . '<td><b>' . $ETI['gthu54iddepedencia'] . '</b></td>';
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>' . $sTituloTabla . '	
	<td><b>' . $ETI['msg_total'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf2825', $registros, $lineastabla, $pagina, 'paginarf2825()') . '
	' . html_lpp('lppf2825', $lineastabla, 'paginarf2825()', 500) . '
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
		if ($bAbierta) {
		}
		$sDatos = '';
		if ($gthu54vercondicion == 1) {
			$sDatos = '<td>' . $sPrefijo . cadena_notildes($filadet['gthu52nombre']) . $sSufijo . '</td>';
		}
		if ($gthu54vercargo == 1) {
			$sDatos = $sDatos . '<td>' . $sPrefijo . cadena_notildes($filadet['gthv01codcargo']) . $sSufijo . '</td>
			<td>' . $sPrefijo . cadena_notildes($filadet['gthv01nombre']) . $sSufijo . '</td>';
		}
		if ($gthu54verescuela == 1) {
			$sDatos = $sDatos . '<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>';
		}
		if ($gthu54verdepedencia == 1) {
			$sDatos = $sDatos . '<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>';
		}
		$res = $res . '<tr' . $sClass . '>
		' . $sDatos . '
		<td colspan="2" align="right">' . $sPrefijo . formato_numero($filadet['Total']) . $sSufijo . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2825_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2825_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2825detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
?>