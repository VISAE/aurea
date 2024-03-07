<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 viernes, 21 de febrero de 2020
--- 3006 Anotaciones
*/
function f3006_db_Guardar($iContenedor, $valores, $objDB, $bDebug = false)
{
	$iCodModulo = 3006;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3006)) {
		$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3006;
	$sError = '';
	$sDebug = '';
	$bInserta = false;
	$iAccion = 3;
	if (!is_array($valores)) {
		$valores = json_decode(str_replace('\"', '"', $valores), true);
	}
	$saiu06idsolicitud = numeros_validar($valores[1]);
	$saiu06consec = numeros_validar($valores[2]);
	$saiu06id = numeros_validar($valores[3], true);
	$saiu06anotacion = htmlspecialchars(trim($valores[4]));
	$saiu06visible = htmlspecialchars(trim($valores[5]));
	$saiu06descartada = htmlspecialchars(trim($valores[6]));
	$saiu06idusuario = numeros_validar($valores[9]);
	$saiu06fecha = $valores[10];
	$saiu06hora = numeros_validar($valores[11]);
	$saiu06minuto = numeros_validar($valores[12]);
	//if ($saiu06hora==''){$saiu06hora=0;}
	//if ($saiu06minuto==''){$saiu06minuto=0;}
	$sSepara = ', ';
	if ($saiu06idusuario == 0) {
		$sError = $ERR['saiu06idusuario'] . $sSepara . $sError;
	}
	if ($saiu06descartada == '') {
		$sError = $ERR['saiu06descartada'] . $sSepara . $sError;
	}
	if ($saiu06visible == '') {
		$sError = $ERR['saiu06visible'] . $sSepara . $sError;
	}
	if ($saiu06anotacion == '') {
		$sError = $ERR['saiu06anotacion'] . $sSepara . $sError;
	}
	//if ($saiu06id==''){$sError=$ERR['saiu06id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu06consec==''){$sError=$ERR['saiu06consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu06idsolicitud == '') {
		$sError = $ERR['saiu06idsolicitud'] . $sSepara . $sError;
	}
	if ($sError == '') {
		if ((int)$saiu06id != 0) {
			if ($saiu06idusuario != $_SESSION['unad_id_tercero']) {
				$sError = $ERR['noeditar'];
			}
		}
	}
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($saiu06idusuario, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	if ($sError == '') {
		$sTabla06 = 'saiu06solanotacion' . $iContenedor;
		$bPermiso = false;
		$sSQL='SELECT bita27id FROM bita27equipotrabajo WHERE bita27activo=1 AND bita27idlider=' . $saiu06idusuario . '';
		$tabla= $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0) {
			$bPermiso = true;
		} else {
			$sSQL='SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28activo="S" AND bita28idtercero=' . $saiu06idusuario . '';
			$tabla= $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0) {
				$bPermiso = true;
			}
		}
		if ((int)$saiu06id == 0) {
			if ((int)$saiu06consec == 0) {
				$saiu06consec = tabla_consecutivo($sTabla06, 'saiu06consec', 'saiu06idsolicitud=' . $saiu06idsolicitud . '', $objDB);
				if ($saiu06consec == -1) {
					$sError = $objDB->serror;
				}
			} else {
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT saiu06idsolicitud FROM ' . $sTabla06 . ' WHERE saiu06idsolicitud=' . $saiu06idsolicitud . ' AND saiu06consec=' . $saiu06consec . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					if (seg_revisa_permiso($iCodModulo, 2, $objDB)) {
						$bPermiso = true;
					}
					if (!$bPermiso) {
						$sError = $ERR['2'];
					}
				}
			}
			if ($sError == '') {
				$saiu06id = tabla_consecutivo($sTabla06, 'saiu06id', '', $objDB);
				if ($saiu06id == -1) {
					$sError = $objDB->serror;
				}
				$bInserta = true;
				$iAccion = 2;
			}
		} else {
			if (seg_revisa_permiso($iCodModulo, 3, $objDB)) {
				$bPermiso = true;
			}
			if (!$bPermiso) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$saiu06idorigen = 0;
			$saiu06idarchivo = 0;
			$saiu06fecha = fecha_DiaMod();
			$saiu06hora = fecha_hora();
			$saiu06minuto = fecha_minuto();
		}
	}
	if ($sError == '') {
		//Si el campo saiu06anotacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu06anotacion=str_replace('"', '\"', $saiu06anotacion);
		$saiu06anotacion = str_replace('"', '\"', $saiu06anotacion);
		if ($bInserta) {
			$sCampos3005 = 'saiu06idsolicitud, saiu06consec, saiu06id, saiu06anotacion, saiu06visible, 
			saiu06descartada, saiu06idorigen, saiu06idarchivo, saiu06idusuario, saiu06fecha, 
			saiu06hora, saiu06minuto';
			$sValores3005 = '' . $saiu06idsolicitud . ', ' . $saiu06consec . ', ' . $saiu06id . ', "' . $saiu06anotacion . '", "' . $saiu06visible . '", 
			"' . $saiu06descartada . '", 0, 0, "' . $saiu06idusuario . '", "' . $saiu06fecha . '", 
			' . $saiu06hora . ', ' . $saiu06minuto . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla06 . ' (' . $sCampos3005 . ') VALUES (' . utf8_encode($sValores3005) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla06 . ' (' . $sCampos3005 . ') VALUES (' . $sValores3005 . ');';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' {Anotaciones}.<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu06id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo3006[1] = 'saiu06anotacion';
			$scampo3006[2] = 'saiu06visible';
			$scampo3006[3] = 'saiu06descartada';
			$scampo3006[4] = 'saiu06idusuario';
			$scampo3006[5] = 'saiu06fecha';
			$svr3006[1] = $saiu06anotacion;
			$svr3006[2] = $saiu06visible;
			$svr3006[3] = $saiu06descartada;
			$svr3006[4] = $saiu06idusuario;
			$svr3006[5] = $saiu06fecha;
			$inumcampos = 5;
			$sWhere = 'saiu06id=' . $saiu06id . '';
			//$sWhere='saiu06idsolicitud='.$saiu06idsolicitud.' AND saiu06consec='.$saiu06consec.'';
			$sSQL = 'SELECT * FROM ' . $sTabla06 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bpasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $inumcampos; $k++) {
					if ($filaorigen[$scampo3006[$k]] != $svr3006[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo3006[$k] . '="' . $svr3006[$k] . '"';
						$bpasa = true;
					}
				}
			}
			if ($bpasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sTabla06 . ' SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sTabla06 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Anotaciones}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu06id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $saiu06id, $sDebug);
}
function f3006_db_Eliminar($aParametros, $objDB, $bDebug = false)
{
	$iCodModulo = 3006;
	$bAudita[4] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3006)) {
		$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3006;
	$sError = '';
	$sDebug = '';
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$saiu06idsolicitud = numeros_validar($aParametros[1]);
	$saiu06consec = numeros_validar($aParametros[2]);
	$saiu06id = numeros_validar($aParametros[3]);
	$saiu06idusuario = numeros_validar($aParametros[9]);
	$sTabla06 = 'saiu06solanotacion' . f3000_Contenedor($aParametros[97], $aParametros[98]);
	if ($sError == '') {
		if ($saiu06idusuario != $_SESSION['unad_id_tercero']) {
			$sError = $ERR['noeliminar'];
		}
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3006';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $saiu06id . ' LIMIT 0, 1';
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
		//acciones previas
		$sWhere = 'saiu06id=' . $saiu06id . '';
		//$sWhere='saiu06idsolicitud='.$saiu06idsolicitud.' AND saiu06consec='.$saiu06consec.'';
		$sSQL = 'DELETE FROM ' . $sTabla06 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {3006 Anotaciones}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu06id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f3006_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3006)) {
		$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3006;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[97]) == 0) {
		$aParametros[97] = fecha_agno();
	}
	if (isset($aParametros[98]) == 0) {
		$aParametros[98] = fecha_mes();
	}
	if (isset($aParametros[99]) == 0) {
		$aParametros[99] = false;
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
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$sDebug = '';
	$saiu05id = $aParametros[0];
	$sTabla6 = 'saiu06solanotacion' . f3000_Contenedor($aParametros[97], $aParametros[98]);
	$bVistaUsr = $aParametros[99];
	$idTercero = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = true;
	//$sSQL='SELECT Campo FROM saiu05solicitud WHERE saiu05id='.$saiu05id;
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
	if ($bVistaUsr){$sSQLadd=$sSQLadd.' AND TB.saiu06visible = "S" ' . '';}
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
	$sTitulos = 'Solicitud, Consec, Id, Anotacion, Visible, Descartada, Origen, Archivo, Usuario, Fecha, Hora, Minuto';
	$sSQL = 'SELECT TB.saiu06idsolicitud, TB.saiu06consec, TB.saiu06id, TB.saiu06anotacion, TB.saiu06visible, TB.saiu06descartada, TB.saiu06idorigen, TB.saiu06idarchivo, T9.unad11razonsocial AS C9_nombre, TB.saiu06fecha, TB.saiu06hora, TB.saiu06minuto, TB.saiu06idusuario, T9.unad11tipodoc AS C9_td, T9.unad11doc AS C9_doc 
FROM ' . $sTabla6 . ' AS TB, unad11terceros AS T9 
WHERE ' . $sSQLadd1 . ' TB.saiu06idsolicitud=' . $saiu05id . ' AND TB.saiu06idusuario=T9.unad11id ' . $sSQLadd . '
ORDER BY TB.saiu06consec';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3006" name="consulta_3006" type="hidden" value="' . $sSQLlista . '"/>
<input id="titulos_3006" name="titulos_3006" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3006: ' . $sSQL . '<br>';
		}
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			return array(utf8_encode($sErrConsulta . '<input id="paginaf3006" name="paginaf3006" type="hidden" value="' . $pagina . '"/><input id="lppf3006" name="lppf3006" type="hidden" value="' . $lineastabla . '"/>'), $sDebug);
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
	<td><b>' . $ETI['saiu06consec'] . '</b></td>';
	if ($bVistaUsr) {
		$res = $res . '<td><b>' . $ETI['saiu06anotacion'] . '</b></td>';
	} else {
		$res = $res . '<td colspan="2"><b>' . $ETI['saiu06idusuario'] . '</b></td>';
	}
	$res = $res . '<td><b>' . $ETI['saiu06fecha'] . '</b></td>
	<td><b>' . $ETI['saiu06hora'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3006', $registros, $lineastabla, $pagina, 'paginarf3006()') . '
	' . html_lpp('lppf3006', $lineastabla, 'paginarf3006()') . '
	</td>
	</tr>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = '';
		$sLink = '';
		if (false) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		$et_saiu06consec = $sPrefijo . $filadet['saiu06consec'] . $sSufijo;
		$et_saiu06anotacion = $sPrefijo . cadena_notildes($filadet['saiu06anotacion']) . $sSufijo;
		$et_saiu06visible = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['saiu06visible'] == 'S') {
			$et_saiu06visible = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_saiu06descartada = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['saiu06descartada'] == 'S') {
			$et_saiu06descartada = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_saiu06idarchivo = '';
		if ($filadet['saiu06idarchivo'] != 0) {
			//$et_saiu06idarchivo='<img src="verarchivo.php?cont='.$filadet['saiu06idorigen'].'&id='.$filadet['saiu06idarchivo'].'&maxx=150"/>';
			$et_saiu06idarchivo = html_lnkarchivo((int)$filadet['saiu06idorigen'], (int)$filadet['saiu06idarchivo']);
		}
		$et_saiu06idusuario = $sPrefijo . $filadet['saiu06idusuario'] . $sSufijo;
		$et_saiu06fecha = '';
		if ($filadet['saiu06fecha'] != 0) {
			$et_saiu06fecha = $sPrefijo . fecha_desdenumero($filadet['saiu06fecha']) . $sSufijo;
		}
		$et_saiu06hora = html_TablaHoraMin($filadet['saiu06hora'], $filadet['saiu06minuto']);
		$et_saiu06minuto = $sPrefijo . $filadet['saiu06minuto'] . $sSufijo;
		if ($bAbierta) {
			$lnk = $ETI['lnk_cargar'];
			if ($bVistaUsr) {
				$lnk = $ETI['lnk_consultar'];
			}
			$sLink = '<a href="javascript:cargaridf3006(' . $filadet['saiu06id'] . ')" class="lnkresalte">' . $lnk . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_saiu06consec . '</td>';
		if ($bVistaUsr) {
			$res = $res . '<td>' . mb_strimwidth($et_saiu06anotacion, 0, 20, '...') . '</td>';
		} else {
			$res = $res . '<td>' . $sPrefijo . $filadet['C9_td'] . ' ' . $filadet['C9_doc'] . $sSufijo . '</td>
			<td>' . $sPrefijo . cadena_notildes($filadet['C9_nombre']) . $sSufijo . '</td>';
		}
		$res = $res . '<td>' . $et_saiu06fecha . '</td>
		<td>' . $et_saiu06hora . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f3006_Clonar($iContenedor, $saiu06idsolicitud, $saiu06idsolicitudPadre, $objDB)
{
	$sError = '';
	$sTabla06 = 'saiu06solanotacion_' . $iContenedor;
	$saiu06consec = tabla_consecutivo($sTabla06, 'saiu06consec', 'saiu06idsolicitud=' . $saiu06idsolicitud . '', $objDB);
	if ($saiu06consec == -1) {
		$sError = $objDB->serror;
	}
	$saiu06id = tabla_consecutivo($sTabla06, 'saiu06id', '', $objDB);
	if ($saiu06id == -1) {
		$sError = $objDB->serror;
	}
	if ($sError == '') {
		$sCampos3006 = 'saiu06idsolicitud, saiu06consec, saiu06id, saiu06anotacion, saiu06visible, saiu06descartada, saiu06idorigen, saiu06idarchivo, saiu06idusuario, saiu06fecha, saiu06hora, saiu06minuto';
		$sValores3006 = '';
		$sSQL = 'SELECT * FROM ' . $sTabla06 . ' WHERE saiu06idsolicitud=' . $saiu06idsolicitudPadre . '';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			if ($sValores3006 != '') {
				$sValores3006 = $sValores3006 . ', ';
			}
			$sValores3006 = $sValores3006 . '(' . $saiu06idsolicitud . ', ' . $saiu06consec . ', ' . $saiu06id . ', "' . $fila['saiu06anotacion'] . '", "' . $fila['saiu06visible'] . '", "' . $fila['saiu06descartada'] . '", "' . $fila['saiu06idorigen'] . '", "' . $fila['saiu06idarchivo'] . '", ' . $fila['saiu06idusuario'] . ', "' . $fila['saiu06fecha'] . '", ' . $fila['saiu06hora'] . ', ' . $fila['saiu06minuto'] . ')';
			$saiu06consec++;
			$saiu06id++;
		}
		if ($sValores3006 != '') {
			$sSQL = 'INSERT INTO ' . $sTabla06 . '(' . $sCampos3006 . ') VALUES ' . $sValores3006 . '';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return $sError;
}
// -- 3006 Anotaciones XAJAX 
function elimina_archivo_saiu06idarchivo($idpadre, $iAgno, $iMes)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla06 = 'saiu06solanotacion' . f3000_Contenedor($iAgno, $iMes);
	archivo_eliminar($sTabla06, 'saiu06id', 'saiu06idorigen', 'saiu06idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu06idarchivo");
	return $objResponse;
}
function f3006_Guardar($valores, $aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$bHayDb = false;
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError == '') {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		$iContenedor = f3000_Contenedor($aParametros[97], $aParametros[98]);
		list($sError, $iAccion, $saiu06id, $sDebugGuardar) = f3006_db_Guardar($iContenedor, $valores, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f3006_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3006detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
		//$objResponse->call('cargaridf3006('.$saiu06id.')');
		//}else{
		//$objResponse->call('limpiaf3006');
		$objResponse->call('cargaridf3006(' . $saiu06id . ')');
		//}
		$objResponse->call("MensajeAlarmaV2('" . $ETI['msg_itemguardado'] . "', 1)");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0)");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f3006_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$saiu06idsolicitud = numeros_validar($aParametros[1]);
		$saiu06consec = numeros_validar($aParametros[2]);
		if (($saiu06idsolicitud != '') && ($saiu06consec != '')) {
			$besta = true;
		}
	} else {
		$saiu06id = $aParametros[103];
		if ((int)$saiu06id != 0) {
			$besta = true;
		}
	}
	if ($besta) {
		$besta = false;
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		$iContenedor = f3000_Contenedor($aParametros[97], $aParametros[98]);
		$sTabla06 = 'saiu06solanotacion' . $iContenedor;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'saiu06idsolicitud=' . $saiu06idsolicitud . ' AND saiu06consec=' . $saiu06consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'saiu06id=' . $saiu06id . '';
		}
		$sSQL = 'SELECT * FROM ' . $sTabla06 . ' WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		if (isset($APP->piel) == 0) {
			$APP->piel = 1;
		}
		$iPiel = $APP->piel;
		$saiu06idusuario_id = (int)$fila['saiu06idusuario'];
		$saiu06idusuario_td = $APP->tipo_doc;
		$saiu06idusuario_doc = '';
		$saiu06idusuario_nombre = '';
		if ($saiu06idusuario_id != 0) {
			list($saiu06idusuario_nombre, $saiu06idusuario_id, $saiu06idusuario_td, $saiu06idusuario_doc) = html_tercero($saiu06idusuario_td, $saiu06idusuario_doc, $saiu06idusuario_id, 0, $objDB);
		}
		$saiu06consec_nombre = '';
		$html_saiu06consec = html_oculto('saiu06consec', $fila['saiu06consec'], $saiu06consec_nombre);
		$objResponse->assign('div_saiu06consec', 'innerHTML', $html_saiu06consec);
		$saiu06id_nombre = '';
		$html_saiu06id = html_oculto('saiu06id', $fila['saiu06id'], $saiu06id_nombre);
		$objResponse->assign('div_saiu06id', 'innerHTML', $html_saiu06id);
		$objResponse->assign('saiu06anotacion', 'value', $fila['saiu06anotacion']);
		$objResponse->assign('saiu06visible', 'value', $fila['saiu06visible']);
		$objResponse->assign('saiu06descartada', 'value', $fila['saiu06descartada']);
		$objResponse->assign('saiu06idorigen', 'value', $fila['saiu06idorigen']);
		$idorigen = (int)$fila['saiu06idorigen'];
		$objResponse->assign('saiu06idarchivo', 'value', $fila['saiu06idarchivo']);
		$objResponse->call("verboton('banexasaiu06idarchivo', 'block')");
		$stemp = 'none';
		$stemp2 = html_lnkarchivo($idorigen, (int)$fila['saiu06idarchivo']);
		if ((int)$fila['saiu06idarchivo'] != 0) {
			$stemp = 'block';
		}
		$objResponse->assign('div_saiu06idarchivo', 'innerHTML', $stemp2);
		$objResponse->call("verboton('beliminasaiu06idarchivo','" . $stemp . "')");
		$objResponse->assign('saiu06idusuario', 'value', $fila['saiu06idusuario']);
		$objResponse->assign('saiu06idusuario_td', 'value', $saiu06idusuario_td);
		$objResponse->assign('saiu06idusuario_doc', 'value', $saiu06idusuario_doc);
		$objResponse->assign('div_saiu06idusuario', 'innerHTML', $saiu06idusuario_nombre);
		$objResponse->assign('saiu06fecha', 'value', $fila['saiu06fecha']);
		$objResponse->assign('div_saiu06fecha', 'innerHTML', fecha_desdenumero($fila['saiu06fecha']));
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['saiu06fecha'], true);
		$objResponse->assign('saiu06fecha_dia', 'value', $iDia);
		$objResponse->assign('saiu06fecha_mes', 'value', $iMes);
		$objResponse->assign('saiu06fecha_agno', 'value', $iAgno);
		$html_saiu06hora = html_HoraMin('saiu06hora', $fila['saiu06hora'], 'saiu06minuto', $fila['saiu06minuto'], true);
		$objResponse->assign('div_saiu06hora', 'innerHTML', $html_saiu06hora);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3006','block')");
		$objResponse->assign('div_p3006_Campos', 'style.display', 'block');
	} else {
		if ($paso == 1) {
			$objResponse->assign('saiu06consec', 'value', $saiu06consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $saiu06id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f3006_Eliminar($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$iTipoError = 0;
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
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sError, $sDebugElimina) = f3006_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f3006_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3006detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3006');
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
	$objResponse->call("MensajeAlarmaV2('" . $sError . "', " . $iTipoError . ")");
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	$objDB->CerrarConexion();
	return $objResponse;
}
function f3006_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3006_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3006detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3006_PintarLlaves($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	if (isset($APP->piel) == 0) {
		$APP->piel = 1;
	}
	$iPiel = $APP->piel;
	$html_saiu06consec = '<input id="saiu06consec" name="saiu06consec" type="text" value="" onchange="revisaf3006()" class="cuatro"/>';
	$html_saiu06id = '<input id="saiu06id" name="saiu06id" type="hidden" value=""/>';
	$et_saiu06fecha='00/00/0000';
	$html_saiu06fecha=html_oculto('saiu06fecha', 0, $et_saiu06fecha);
	$html_saiu06hora = html_HoraMin('saiu06hora', fecha_hora(), 'saiu06minuto', fecha_minuto(), true);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu06consec', 'innerHTML', $html_saiu06consec);
	$objResponse->assign('div_saiu06id', 'innerHTML', $html_saiu06id);
	$objResponse->assign('div_saiu06fecha','innerHTML', $html_saiu06fecha);
	$objResponse->assign('div_saiu06hora', 'innerHTML', $html_saiu06hora);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>