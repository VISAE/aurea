<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 3 de mayo de 2021
--- 3049 Cambios de estado
*/
function f3049_db_Guardar($valores, $objDB, $bDebug = false)
{
	$iCodModulo = 3049;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3049 = $APP->rutacomun . 'lg/lg_3049_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3049)) {
		$mensajes_3049 = $APP->rutacomun . 'lg/lg_3049_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3049;
	$sError = '';
	$sDebug = '';
	$bInserta = false;
	$iAccion = 3;
	if (!is_array($valores)) {
		$valores = json_decode(str_replace('\"', '"', $valores), true);
	}
	$saiu49idtramite = numeros_validar($valores[1]);
	$saiu49consec = numeros_validar($valores[2]);
	$saiu49id = numeros_validar($valores[3], true);
	$saiu49idresponsable = numeros_validar($valores[4]);
	$saiu49idestadorigen = numeros_validar($valores[5]);
	$saiu49idestadofin = numeros_validar($valores[6]);
	$saiu49detalle = htmlspecialchars(trim($valores[7]));
	$saiu49usuario = numeros_validar($valores[8]);
	$saiu49fecha = $valores[9];
	$saiu49hora = numeros_validar($valores[10]);
	$saiu49minuto = numeros_validar($valores[11]);
	$saiu49correterminos = numeros_validar($valores[12]);
	$saiu49tiempousado = numeros_validar($valores[13]);
	$saiu49tiempocalusado = numeros_validar($valores[14]);
	//if ($saiu49idestadorigen==''){$saiu49idestadorigen=0;}
	//if ($saiu49idestadofin==''){$saiu49idestadofin=0;}
	//if ($saiu49hora==''){$saiu49hora=0;}
	//if ($saiu49minuto==''){$saiu49minuto=0;}
	//if ($saiu49correterminos==''){$saiu49correterminos=0;}
	//if ($saiu49tiempousado==''){$saiu49tiempousado=0;}
	//if ($saiu49tiempocalusado==''){$saiu49tiempocalusado=0;}
	$sSepara = ', ';
	if ($saiu49minuto == '') {
		$sError = $ERR['saiu49minuto'] . $sSepara . $sError;
	}
	if ($saiu49hora == '') {
		$sError = $ERR['saiu49hora'] . $sSepara . $sError;
	}
	if ($saiu49usuario == 0) {
		$sError = $ERR['saiu49usuario'] . $sSepara . $sError;
	}
	if ($saiu49detalle == '') {
		$sError = $ERR['saiu49detalle'] . $sSepara . $sError;
	}
	if ($saiu49idestadofin == '') {
		$sError = $ERR['saiu49idestadofin'] . $sSepara . $sError;
	}
	if ($saiu49idestadorigen == '') {
		$sError = $ERR['saiu49idestadorigen'] . $sSepara . $sError;
	}
	if ($saiu49idresponsable == 0) {
		$sError = $ERR['saiu49idresponsable'] . $sSepara . $sError;
	}
	//if ($saiu49id==''){$sError=$ERR['saiu49id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu49consec==''){$sError=$ERR['saiu49consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu49idtramite == '') {
		$sError = $ERR['saiu49idtramite'] . $sSepara . $sError;
	}
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($saiu49usuario, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($saiu49idresponsable, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	if ($sError == '') {
		if ((int)$saiu49id == 0) {
			if ((int)$saiu49consec == 0) {
				$saiu49consec = tabla_consecutivo('saiu49cambioesttra', 'saiu49consec', 'saiu49idtramite=' . $saiu49idtramite . '', $objDB);
				if ($saiu49consec == -1) {
					$sError = $objDB->serror;
				}
			} else {
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT saiu49idtramite FROM saiu49cambioesttra WHERE saiu49idtramite=' . $saiu49idtramite . ' AND saiu49consec=' . $saiu49consec . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)) {
						$sError = $ERR['2'];
					}
				}
			}
			if ($sError == '') {
				$saiu49id = tabla_consecutivo('saiu49cambioesttra', 'saiu49id', '', $objDB);
				if ($saiu49id == -1) {
					$sError = $objDB->serror;
				}
				$bInserta = true;
				$iAccion = 2;
			}
		} else {
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$saiu49correterminos = 0;
			$saiu49tiempousado = 0;
			$saiu49tiempocalusado = 0;
			//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
			//$tabla=$objDB->ejecutasql($sSQL);
			//if ($objDB->nf($tabla)>0){
			//$fila=$objDB->sf($tabla);
			//$sCampo=$fila['sCampo'];
			//}
			$sError = 'INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
		}
	}
	if ($sError == '') {
		//Si el campo saiu49detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu49detalle=str_replace('"', '\"', $saiu49detalle);
		$saiu49detalle = str_replace('"', '\"', $saiu49detalle);
		if ($bInserta) {
			$sCampos3049 = 'saiu49idtramite, saiu49consec, saiu49id, saiu49idresponsable, saiu49idestadorigen, 
			saiu49idestadofin, saiu49detalle, saiu49usuario, saiu49fecha, saiu49hora, 
			saiu49minuto, saiu49correterminos, saiu49tiempousado, saiu49tiempocalusado';
			$sValores3049 = '' . $saiu49idtramite . ', ' . $saiu49consec . ', ' . $saiu49id . ', "' . $saiu49idresponsable . '", ' . $saiu49idestadorigen . ', 
			' . $saiu49idestadofin . ', "' . $saiu49detalle . '", "' . $saiu49usuario . '", "' . $saiu49fecha . '", ' . $saiu49hora . ', 
			' . $saiu49minuto . ', ' . $saiu49correterminos . ', ' . $saiu49tiempousado . ', ' . $saiu49tiempocalusado . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO saiu49cambioesttra (' . $sCampos3049 . ') VALUES (' . cadena_codificar($sValores3049) . ');';
			} else {
				$sSQL = 'INSERT INTO saiu49cambioesttra (' . $sCampos3049 . ') VALUES (' . $sValores3049 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3049 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3049].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu49id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo3049[1] = 'saiu49idresponsable';
			$scampo3049[2] = 'saiu49idestadorigen';
			$scampo3049[3] = 'saiu49idestadofin';
			$scampo3049[4] = 'saiu49detalle';
			$scampo3049[5] = 'saiu49fecha';
			$svr3049[1] = $saiu49idresponsable;
			$svr3049[2] = $saiu49idestadorigen;
			$svr3049[3] = $saiu49idestadofin;
			$svr3049[4] = $saiu49detalle;
			$svr3049[5] = $saiu49fecha;
			$inumcampos = 5;
			$sWhere = 'saiu49id=' . $saiu49id . '';
			//$sWhere='saiu49idtramite='.$saiu49idtramite.' AND saiu49consec='.$saiu49consec.'';
			$sSQL = 'SELECT * FROM saiu49cambioesttra WHERE ' . $sWhere;
			$sdatos = '';
			$bpasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $inumcampos; $k++) {
					if ($filaorigen[$scampo3049[$k]] != $svr3049[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo3049[$k] . '="' . $svr3049[$k] . '"';
						$bpasa = true;
					}
				}
			}
			if ($bpasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE saiu49cambioesttra SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE saiu49cambioesttra SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Cambios de estado}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu49id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $saiu49id, $sDebug);
}
function f3049_db_Eliminar($aParametros, $objDB, $bDebug = false)
{
	$iCodModulo = 3049;
	$bAudita[4] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3049 = $APP->rutacomun . 'lg/lg_3049_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3049)) {
		$mensajes_3049 = $APP->rutacomun . 'lg/lg_3049_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3049;
	$sError = '';
	$sDebug = '';
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$saiu49idtramite = numeros_validar($aParametros[1]);
	$saiu49consec = numeros_validar($aParametros[2]);
	$saiu49id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3049';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $saiu49id . ' LIMIT 0, 1';
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
		$sWhere = 'saiu49id=' . $saiu49id . '';
		//$sWhere='saiu49idtramite='.$saiu49idtramite.' AND saiu49consec='.$saiu49consec.'';
		$sSQL = 'DELETE FROM saiu49cambioesttra WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {3049 Cambios de estado}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu49id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f3049_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3049 = $APP->rutacomun . 'lg/lg_3049_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3049)) {
		$mensajes_3049 = $APP->rutacomun . 'lg/lg_3049_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3049;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[98]) == 0) {
		$aParametros[98] = 0;
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
	$idTercero = $aParametros[100];
	$sDebug = '';
	$saiu47id = $aParametros[0];
	$iAgno = $aParametros[98];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = false;
	$sLeyenda = '';
	$sTabla47 = 'saiu47tramites_' . $iAgno;
	$sTabla49 = 'saiu49cambioesttra_' . $iAgno;
	if (!$objDB->bexistetabla($sTabla47)) {
		$sLeyenda = 'No ha sido posible acceder al contenedor de datos';
	} else {
		/*
		$sSQL='SELECT saiu47estado FROM saiu47tramites WHERE saiu47id='.$saiu47id;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['saiu47estado']!='S'){$bAbierta=true;}
			}
		*/
	}
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . '<input id="paginaf3049" name="paginaf3049" type="hidden" value="' . $pagina . '"/><input id="lppf3049" name="lppf3049" type="hidden" value="' . $lineastabla . '"/>', $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	$aEstado = array();
	$sSQL = 'SELECT saiu60id, saiu60nombre FROM saiu60estadotramite';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['saiu60id']] = cadena_notildes($fila['saiu60nombre']);
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
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
	$sTitulos = 'Tramite, Consec, Id, Responsable, Estadorigen, Estadofin, Detalle, Usuario, Fecha, Hora, Minuto, Correterminos, Tiempousado, Tiempocalusado';
	$sSQL = 'SELECT TB.saiu49consec, TB.saiu49id, TB.saiu49detalle, T8.unad11razonsocial AS C8_nombre, TB.saiu49fecha, TB.saiu49hora, 
	TB.saiu49minuto, TB.saiu49idresponsable, TB.saiu49idestadorigen, TB.saiu49idestadofin, TB.saiu49usuario 
	FROM ' . $sTabla49 . ' AS TB, unad11terceros AS T8 
	WHERE ' . $sSQLadd1 . ' TB.saiu49idtramite=' . $saiu47id . ' AND TB.saiu49usuario=T8.unad11id ' . $sSQLadd . '
	ORDER BY TB.saiu49consec DESC';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3049" name="consulta_3049" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3049" name="titulos_3049" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3049: ' . $sSQL . '<br>';
		}
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			return array(cadena_codificar($sErrConsulta . '<input id="paginaf3049" name="paginaf3049" type="hidden" value="' . $pagina . '"/><input id="lppf3049" name="lppf3049" type="hidden" value="' . $lineastabla . '"/>'), $sDebug);
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
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['saiu49consec'] . '</b></td>
	<td><b>' . $ETI['saiu49idestadorigen'] . '</b></td>
	<td><b>' . $ETI['saiu49idestadofin'] . '</b></td>
	<td><b>' . $ETI['saiu49usuario'] . '</b></td>
	<td><b>' . $ETI['saiu49fecha'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3049', $registros, $lineastabla, $pagina, 'paginarf3049()') . '
	' . html_lpp('lppf3049', $lineastabla, 'paginarf3049()') . '
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
		$et_saiu49consec = $sPrefijo . $filadet['saiu49consec'] . $sSufijo;
		$et_saiu49idestadorigen = $sPrefijo . $aEstado[$filadet['saiu49idestadorigen']] . $sSufijo;
		$et_saiu49idestadofin = $sPrefijo . $aEstado[$filadet['saiu49idestadofin']] . $sSufijo;
		$et_saiu49usuario_nombre = '';
		if ($filadet['saiu49usuario'] != 0) {
			$et_saiu49usuario_nombre = $sPrefijo . cadena_notildes($filadet['C8_nombre']) . $sSufijo;
		}
		$et_saiu49fecha = '';
		$et_saiu49hora = '';
		if ($filadet['saiu49fecha'] != 0) {
			$et_saiu49fecha = $sPrefijo . fecha_desdenumero($filadet['saiu49fecha']) . $sSufijo;
			$et_saiu49hora = $sPrefijo . html_TablaHoraMin($filadet['saiu49hora'], $filadet['saiu49minuto']) . $sSufijo;
			//$et_saiu49minuto=$sPrefijo.$filadet['saiu49minuto'].$sSufijo;
		}
		if ($bAbierta) {
			//$sLink='<a href="javascript:cargaridf3049('.$filadet['saiu49id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_saiu49consec . '</td>
		<td>' . $et_saiu49idestadorigen . '</td>
		<td>' . $et_saiu49idestadofin . '</td>
		<td>' . $et_saiu49usuario_nombre . '</td>
		<td>' . $et_saiu49fecha . '</td>
		<td>' . $et_saiu49hora . '</td>
		</tr>';
		if ($filadet['saiu49detalle'] != '') {
			$et_saiu49detalle = $sPrefijo . cadena_notildes($filadet['saiu49detalle']) . $sSufijo;
			$res = $res . '<tr' . $sClass . '>
			<td colspan="2"></td>
			<td colspan="4">' . $et_saiu49detalle . '</td>
			</tr>';
		}
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 3049 Cambios de estado XAJAX 
function f3049_Guardar($valores, $aParametros)
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
		list($sError, $iAccion, $saiu49id, $sDebugGuardar) = f3049_db_Guardar($valores, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f3049_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3049detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
		//$objResponse->call('cargaridf3049('.$saiu49id.')');
		//}else{
		$objResponse->call('limpiaf3049');
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
function f3049_Traer($aParametros)
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
		$saiu49idtramite = numeros_validar($aParametros[1]);
		$saiu49consec = numeros_validar($aParametros[2]);
		if (($saiu49idtramite != '') && ($saiu49consec != '')) {
			$besta = true;
		}
	} else {
		$saiu49id = $aParametros[103];
		if ((int)$saiu49id != 0) {
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
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'saiu49idtramite=' . $saiu49idtramite . ' AND saiu49consec=' . $saiu49consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'saiu49id=' . $saiu49id . '';
		}
		$sSQL = 'SELECT * FROM saiu49cambioesttra WHERE ' . $sSQLcondi;
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
		$saiu49idresponsable_id = (int)$fila['saiu49idresponsable'];
		$saiu49idresponsable_td = $APP->tipo_doc;
		$saiu49idresponsable_doc = '';
		$saiu49idresponsable_nombre = '';
		if ($saiu49idresponsable_id != 0) {
			list($saiu49idresponsable_nombre, $saiu49idresponsable_id, $saiu49idresponsable_td, $saiu49idresponsable_doc) = html_tercero($saiu49idresponsable_td, $saiu49idresponsable_doc, $saiu49idresponsable_id, 0, $objDB);
		}
		$saiu49usuario_id = (int)$fila['saiu49usuario'];
		$saiu49usuario_td = $APP->tipo_doc;
		$saiu49usuario_doc = '';
		$saiu49usuario_nombre = '';
		if ($saiu49usuario_id != 0) {
			list($saiu49usuario_nombre, $saiu49usuario_id, $saiu49usuario_td, $saiu49usuario_doc) = html_tercero($saiu49usuario_td, $saiu49usuario_doc, $saiu49usuario_id, 0, $objDB);
		}
		$saiu49consec_nombre = '';
		$html_saiu49consec = html_oculto('saiu49consec', $fila['saiu49consec'], $saiu49consec_nombre);
		$objResponse->assign('div_saiu49consec', 'innerHTML', $html_saiu49consec);
		$saiu49id_nombre = '';
		$html_saiu49id = html_oculto('saiu49id', $fila['saiu49id'], $saiu49id_nombre);
		$objResponse->assign('div_saiu49id', 'innerHTML', $html_saiu49id);
		$objResponse->assign('saiu49idresponsable', 'value', $fila['saiu49idresponsable']);
		$objResponse->assign('saiu49idresponsable_td', 'value', $saiu49idresponsable_td);
		$objResponse->assign('saiu49idresponsable_doc', 'value', $saiu49idresponsable_doc);
		$objResponse->assign('div_saiu49idresponsable', 'innerHTML', $saiu49idresponsable_nombre);
		$objResponse->assign('saiu49idestadorigen', 'value', $fila['saiu49idestadorigen']);
		$objResponse->assign('saiu49idestadofin', 'value', $fila['saiu49idestadofin']);
		$objResponse->assign('saiu49detalle', 'value', $fila['saiu49detalle']);
		$bOculto = true;
		$html_saiu49usuario_llaves = html_DivTerceroV2('saiu49usuario', $saiu49usuario_td, $saiu49usuario_doc, $bOculto, $saiu49usuario_id, $ETI['ing_doc']);
		$objResponse->assign('saiu49usuario', 'value', $saiu49usuario_id);
		$objResponse->assign('div_saiu49usuario_llaves', 'innerHTML', $html_saiu49usuario_llaves);
		$objResponse->assign('div_saiu49usuario', 'innerHTML', $saiu49usuario_nombre);
		$html_saiu49fecha = html_oculto('saiu49fecha', $fila['saiu49fecha'], fecha_desdenumero($fila['saiu49fecha']));
		$objResponse->assign('div_saiu49fecha', 'innerHTML', $html_saiu49fecha);
		$html_saiu49hora = html_HoraMin('saiu49hora', $fila['saiu49hora'], 'saiu49minuto', $fila['saiu49minuto'], true);
		$objResponse->assign('div_saiu49hora', 'innerHTML', $html_saiu49hora);
		list($saiu49correterminos_nombre, $serror_det) = tabla_campoxid('', '', '', $fila['saiu49correterminos'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		$html_saiu49correterminos = html_oculto('saiu49correterminos', $fila['saiu49correterminos'], $saiu49correterminos_nombre);
		$objResponse->assign('div_saiu49correterminos', 'innerHTML', $html_saiu49correterminos);
		$saiu49tiempousado_eti = $fila['saiu49tiempousado'];
		$html_saiu49tiempousado = html_oculto('saiu49tiempousado', $fila['saiu49tiempousado'], $saiu49tiempousado_eti);
		$objResponse->assign('div_saiu49tiempousado', 'innerHTML', $html_saiu49tiempousado);
		$saiu49tiempocalusado_eti = $fila['saiu49tiempocalusado'];
		$html_saiu49tiempocalusado = html_oculto('saiu49tiempocalusado', $fila['saiu49tiempocalusado'], $saiu49tiempocalusado_eti);
		$objResponse->assign('div_saiu49tiempocalusado', 'innerHTML', $html_saiu49tiempocalusado);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3049','block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('saiu49consec', 'value', $saiu49consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $saiu49id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f3049_Eliminar($aParametros)
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
	list($sError, $sDebugElimina) = f3049_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f3049_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3049detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3049');
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
function f3049_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3049_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3049detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3049_PintarLlaves($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
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
	require $mensajes_todas;
	if (isset($APP->piel) == 0) {
		$APP->piel = 1;
	}
	$iPiel = $APP->piel;
	$objCombos = new clsHtmlCombos();
	$html_saiu49consec = '<input id="saiu49consec" name="saiu49consec" type="text" value="" onchange="revisaf3049()" class="cuatro"/>';
	$html_saiu49id = '<input id="saiu49id" name="saiu49id" type="hidden" value=""/>';
	list($saiu49usuario_rs, $saiu49usuario, $saiu49usuario_td, $saiu49usuario_doc) = html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_saiu49usuario_llaves = html_DivTerceroV2('saiu49usuario', $saiu49usuario_td, $saiu49usuario_doc, true, 0, $ETI['ing_doc']);
	$et_saiu49fecha = '00/00/0000';
	$html_saiu49fecha = html_oculto('saiu49fecha', 0, $et_saiu49fecha);
	$html_saiu49hora = html_HoraMin('saiu49hora', fecha_hora(), 'saiu49minuto', fecha_minuto(), true);
	//$html_saiu49correterminos = f3049_HTMLComboV2_saiu49correterminos($objDB, $objCombos, '');
	$html_saiu49tiempousado = html_oculto('saiu49tiempousado', '');
	$html_saiu49tiempocalusado = html_oculto('saiu49tiempocalusado', '');
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu49consec', 'innerHTML', $html_saiu49consec);
	$objResponse->assign('div_saiu49id', 'innerHTML', $html_saiu49id);
	$objResponse->assign('saiu49usuario', 'value', $saiu49usuario);
	$objResponse->assign('div_saiu49usuario_llaves', 'innerHTML', $html_saiu49usuario_llaves);
	$objResponse->assign('div_saiu49usuario', 'innerHTML', $saiu49usuario_rs);
	$objResponse->assign('div_saiu49fecha', 'innerHTML', $html_saiu49fecha);
	$objResponse->assign('div_saiu49hora', 'innerHTML', $html_saiu49hora);
	//$objResponse->assign('div_saiu49correterminos', 'innerHTML', $html_saiu49correterminos);
	$objResponse->call('$("#saiu49correterminos").chosen()');
	$objResponse->assign('div_saiu49tiempousado', 'innerHTML', $html_saiu49tiempousado);
	$objResponse->assign('div_saiu49tiempocalusado', 'innerHTML', $html_saiu49tiempocalusado);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

