<?php
/*
--- © Omar Augusto Bautista MOra - UNAD - 2022 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- lunes, 28 de noviembre de 2022
--- El proposito de esta libreria es redirigir a las opciones de consulta o radicado de PQRS
*/
function f3005_HTMLOpcionInicial($aParametros) {
    $_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
    if (!file_exists($mensajes_3005)) {
        $mensajes_3005 = 'lg/lg_3005_es.php';
    }
    require $mensajes_todas;
    require $mensajes_3005;
	// $objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	// if ($APP->dbpuerto != '') {
	// 	$objDB->dbPuerto = $APP->dbpuerto;
	// }

	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($_SESSION['unad_id_tercero'])==0){$_SESSION['unad_id_tercero']=0;}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]='';}
    $sError = '';
    $sHTML = '';
    $sScript = '';
    $sSepara=', ';
    $enSesion = false;
    $idTercero=$aParametros[100];
    $iOpcion = numeros_validar($aParametros[101]);
    if ($_SESSION['unad_id_tercero'] > 0) {
        if ($idTercero == $_SESSION['unad_id_tercero']) {
            $enSesion = true;
        } else {
            $sError = $ERR['saiu05idtercero'] . $sSepara . $sError;
        }
    }
    if ($iOpcion == '') {
        $sError = $ERR['saiu05opinvalida'] . $sSepara . $sError;
    }
    if($sError == '') {
        if($iOpcion == 1) {
            $sHTML = $sHTML . '<form id="frmcodigo" name="frmcodigo" method="post" action="" autocomplete="off">
            <div class="form-group row">
                <div class="col-sm-12">
                    <label for="saui05numref" class="text-center">' . $ETI['ing_campo'] . $ETI['saiu05refdoc'] . '</label>
                    <input id="saui05numref" name="saui05numref" class="form-control form-control-lg text-center" type="text" value="" maxlength="20" placeholder="' . $ETI['digite'] . '" />
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <input type="button" id="cmdEnviaCodigo" name="cmdEnviaCodigo" class="btn btn-aurea px-4 float-right" title="' . $ETI['bt_consultar'] . '" value="' . $ETI['bt_consultar'] . '" onclick="enviacodigo()">
                </div>
            </div>
        </form>';
        } else if ($iOpcion == 2) {
            if ($enSesion) {
                $sScript = $sScript . 'window.location.href="saipqrs.php";';
            } else {
                $sHTML = $sHTML . '<div class="form-group row">
                    <div class="col-sm-12">
                        <a id="cmdIrACampus" name="cmdIrACampus" class="btn btn-aurea w-50" title="' . $ETI['bt_campus'] . '" href="./login.php">' . $ETI['bt_campus'] . '</a>
                    </div>
                    <div class="col-sm-12">
                        <a id="cmdIrAInscribirse" name="cmdIrAInscribirse" class="btn btn-aurea w-50 mt-2" title="' . $ETI['bt_inscribirse'] . '" href="https://campus0d.unad.edu.co/campus/inscripcion.php">' . $ETI['bt_inscribirse'] . '</a>
                    </div>
                    <div class="col-sm-12">
                        <a id="cmdIrAAnonimo" name="cmdIrAAnonimo" class="btn btn-aurea w-50 mt-2" title="' . $ETI['bt_anonimo'] . '" href="javascript:;" onclick="ingresaanonimo();">' . $ETI['bt_anonimo'] . '</a>
                    </div>
                </div>';
            }
        } else {
            $sError = $ERR['saiu05opinvalida'] . $sSepara . $sError;
        }
	}
    $objResponse = new xajaxResponse();
    if($sError == '') {
        $objResponse->script($sScript);
        $objResponse->assign('div_saiu05rutaspqrs', 'innerHTML', $sHTML);
    } else {
        $objResponse->call('muestramensajes("danger", "' . $sError . '")');
    }
    return $objResponse;
}
function f3005_IngresaAnonimo() {
    $_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
    if (!file_exists($mensajes_3005)) {
        $mensajes_3005 = 'lg/lg_3005_es.php';
    }
    require $mensajes_todas;
    require $mensajes_3005;
	// $objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	// if ($APP->dbpuerto != '') {
	// 	$objDB->dbPuerto = $APP->dbpuerto;
	// }
    $sError='';
    $sScript='';
    if (isset($_SESSION['unad_id_tercero'])==0){$_SESSION['unad_id_tercero']=0;}
    if ($_SESSION['unad_id_tercero']==0){
        $_SESSION['unad_id_tercero']=1;
        $sScript = $sScript . 'window.location.href="saipqrs.php";';
    } else {
        $sError = $sError . $ETI['sesioniniciada'] . '';
    }
    $objResponse = new xajaxResponse();
    if($sError == '') {
        $objResponse->script($sScript);
    } else {
        $objResponse->call('muestramensajes("warning", "' . $sError . '")');
    }
    return $objResponse;
}
function f3005_ConsultaCodigo($aParametros) {
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
    if (!file_exists($mensajes_3005)) {
        $mensajes_3005 = 'lg/lg_3005_es.php';
    }
    require $mensajes_todas;
    require $mensajes_3005;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$sError = '';
	$sHTML = '';
	$sSepara = ' ';
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	$saui05numref = cadena_Validar($aParametros[100], true);
	if (true) {
		if ($saui05numref == '') {
			$sError = $ERR['saiu05refdoc'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	if($sError == '') {
		$aCodigo = explode('-',$saui05numref);
		if (count($aCodigo) < 3) {
			$sError = 'Codigo incorrecto.';
		} else {
			$sTabla05 = 'saiu05solicitud_' . $aCodigo[0];
			$sIdSolicitiud = $aCodigo[1];
			if (!$objDB->bexistetabla($sTabla05)) {
				$sError = 'No ha sido posible acceder al contenedor de datos';
			} else {
				$sSQL = 'SELECT 1 FROM ' . $sTabla05 . ' WHERE saiu05id = ' . $sIdSolicitiud . ' AND saiu05numref = "' . $saui05numref . '"';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$sTablaAnotacion05 = 'saiu06solanotacion_' . $aCodigo[0];
					$sSQL = 'SELECT saiu06consec, saiu06anotacion, saiu06fecha, saiu06hora, saiu06minuto 
					FROM ' . $sTablaAnotacion05 . ' 
					WHERE saiu06idsolicitud = ' . $sIdSolicitiud . ' AND saiu06visible="S"' . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$sHTML = $sHTML . '<table class="table table-striped table-hover"><thead>';
						$sHTML = $sHTML . '<tr>
						<th>Consecutivo</th>
						<th>Anotación</th>
						<th>Fecha</th>						
						</tr></thead><tbody>';
						while($fila = $objDB->sf($tabla)) {
							$sHTML = $sHTML . '<tr>
							<td>' . $fila['saiu06consec'] . '</td>
							<td>' . $fila['saiu06anotacion'] . '</td>
							<td>' . $fila['saiu06fecha'] . '</td>
							</tr>';
						}
						$sHTML = $sHTML . '</tbody></table>
						<div class="form-group row">
							<div class="col-sm-12">
								<input type="button" id="cmdContinuar" name="cmdContinuar" class="btn btn-aurea w-25 mt-2" title="' . $ETI['bt_continuar'] . '" value="' . $ETI['bt_continuar'] . '" onclick="window.location.href=\'index.php\';">
							</div>
						</div>';
					} else {
						$sError = 'No se han registrado anotaciones';
					}
				} else {
					$sError = 'Codigo incorrecto.';
				}
			}
		}
	}
	$objResponse = new xajaxResponse();
    if($sError == '') {
        // $objResponse->script($sScript);
        $objResponse->assign('div_saiu05rutaspqrs', 'innerHTML', $sHTML);
    } else {
        $objResponse->call('muestramensajes("danger", "' . $sError . '")');
    }
    return $objResponse;
}
function f3005_db_Guardar($DATA, $objDB, $bDebug=false){
	$iCodModulo=3005;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3005=$APP->rutacomun.'lg/lg_3005_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3005)){$mensajes_3005=$APP->rutacomun.'lg/lg_3005_es.php';}
	require $mensajes_todas;
	require $mensajes_3005;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu05consec'])==0){$DATA['saiu05consec']='';}
	if (isset($DATA['saiu05id'])==0){$DATA['saiu05id']='';}
	if (isset($DATA['saiu05dia'])==0){$DATA['saiu05dia']='';}
	if (isset($DATA['saiu05idtiposolorigen'])==0){$DATA['saiu05idtiposolorigen']='';}
	if (isset($DATA['saiu05idtemaorigen'])==0){$DATA['saiu05idtemaorigen']='';}
	if (isset($DATA['saiu05idinteresado'])==0){$DATA['saiu05idinteresado']='';}
	if (isset($DATA['saiu05tipointeresado'])==0){$DATA['saiu05tipointeresado']='';}
	if (isset($DATA['saiu05rptaforma'])==0){$DATA['saiu05rptaforma']='';}
	if (isset($DATA['saiu05rptacorreo'])==0){$DATA['saiu05rptacorreo']='';}
	if (isset($DATA['saiu05rptadireccion'])==0){$DATA['saiu05rptadireccion']='';}
	if (isset($DATA['saiu05costogenera'])==0){$DATA['saiu05costogenera']='';}
	if (isset($DATA['saiu05costovalor'])==0){$DATA['saiu05costovalor']='';}
	if (isset($DATA['saiu05detalle'])==0){$DATA['saiu05detalle']='';}
	if (isset($DATA['saiu05idresponsable'])==0){$DATA['saiu05idresponsable']='';}
	if (isset($DATA['saiu05idmoduloproc'])==0){$DATA['saiu05idmoduloproc']='';}
	if (isset($DATA['saiu05identificadormod'])==0){$DATA['saiu05identificadormod']='';}
	if (isset($DATA['saiu05numradicado'])==0){$DATA['saiu05numradicado']='';}
	*/
	$DATA['saiu05consec']=numeros_validar($DATA['saiu05consec']);
	$DATA['saiu05hora']=numeros_validar($DATA['saiu05hora']);
	$DATA['saiu05minuto']=numeros_validar($DATA['saiu05minuto']);
	$DATA['saiu05idtiposolorigen']=numeros_validar($DATA['saiu05idtiposolorigen']);
	$DATA['saiu05idtemaorigen']=numeros_validar($DATA['saiu05idtemaorigen']);
	$DATA['saiu05tipointeresado']=numeros_validar($DATA['saiu05tipointeresado']);
	$DATA['saiu05rptaforma']=numeros_validar($DATA['saiu05rptaforma']);
	$DATA['saiu05rptacorreo']=htmlspecialchars(trim($DATA['saiu05rptacorreo']));
	$DATA['saiu05rptadireccion']=htmlspecialchars(trim($DATA['saiu05rptadireccion']));
	$DATA['saiu05costogenera']=numeros_validar($DATA['saiu05costogenera']);
	$DATA['saiu05costovalor']=numeros_validar($DATA['saiu05costovalor'],true);
	$DATA['saiu05costorefpago']=htmlspecialchars(trim($DATA['saiu05costorefpago']));
	$DATA['saiu05numref']=htmlspecialchars(trim($DATA['saiu05numref']));
	$DATA['saiu05detalle']=htmlspecialchars(trim($DATA['saiu05detalle']));
	$DATA['saiu05infocomplemento']=htmlspecialchars(trim($DATA['saiu05infocomplemento']));
	$DATA['saiu05respuesta']=htmlspecialchars(trim($DATA['saiu05respuesta']));
	$DATA['saiu05idmoduloproc']=numeros_validar($DATA['saiu05idmoduloproc']);
	$DATA['saiu05identificadormod']=numeros_validar($DATA['saiu05identificadormod']);
	$DATA['saiu05numradicado']=numeros_validar($DATA['saiu05numradicado']);
	$DATA['saiu05idcategoria']=numeros_validar($DATA['saiu05idcategoria']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['saiu05origenid']==''){$DATA['saiu05origenid']=0;}
	//if ($DATA['saiu05hora']==''){$DATA['saiu05hora']=0;}
	//if ($DATA['saiu05minuto']==''){$DATA['saiu05minuto']=0;}
	//if ($DATA['saiu05estado']==''){$DATA['saiu05estado']=-1;}
	if ($DATA['saiu05idmedio']==''){$DATA['saiu05idmedio']=0;}
	//if ($DATA['saiu05idtiposolorigen']==''){$DATA['saiu05idtiposolorigen']=0;}
	//if ($DATA['saiu05idtemaorigen']==''){$DATA['saiu05idtemaorigen']=0;}
	//if ($DATA['saiu05tipointeresado']==''){$DATA['saiu05tipointeresado']=0;}
	//if ($DATA['saiu05rptaforma']==''){$DATA['saiu05rptaforma']=0;}
	//if ($DATA['saiu05costogenera']==''){$DATA['saiu05costogenera']=0;}
	//if ($DATA['saiu05costovalor']==''){$DATA['saiu05costovalor']=0;}
	if ($DATA['saiu05idmoduloproc']==''){$DATA['saiu05idmoduloproc']=0;}
	if ($DATA['saiu05identificadormod']==''){$DATA['saiu05identificadormod']=0;}
	//if ($DATA['saiu05numradicado']==''){$DATA['saiu05numradicado']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		/*
		if ($DATA['saiu05numradicado']==''){$sError=$ERR['saiu05numradicado'].$sSepara.$sError;}
		if ($DATA['saiu05identificadormod']==''){$sError=$ERR['saiu05identificadormod'].$sSepara.$sError;}
		if ($DATA['saiu05idmoduloproc']==''){$sError=$ERR['saiu05idmoduloproc'].$sSepara.$sError;}
		//if (!fecha_esvalida($DATA['saiu05fecharespprob'])){
			//$DATA['saiu05fecharespprob']='00/00/0000';
			//$sError=$ERR['saiu05fecharespprob'].$sSepara.$sError;
			//}
		if ($DATA['saiu05idresponsable']==0){$sError=$ERR['saiu05idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu05costovalor']==''){$sError=$ERR['saiu05costovalor'].$sSepara.$sError;}
		if ($DATA['saiu05costogenera']==''){$sError=$ERR['saiu05costogenera'].$sSepara.$sError;}
		if ($DATA['saiu05rptaforma']==''){$sError=$ERR['saiu05rptaforma'].$sSepara.$sError;}
		if ($DATA['saiu05tipointeresado']==''){$sError=$ERR['saiu05tipointeresado'].$sSepara.$sError;}
		if ($DATA['saiu05idinteresado']==0){$sError=$ERR['saiu05idinteresado'].$sSepara.$sError;}
		if ($DATA['saiu05idsolicitante']==0){$sError=$ERR['saiu05idsolicitante'].$sSepara.$sError;}
		if ($DATA['saiu05dia']==0){
			//$DATA['saiu05dia']=fecha_DiaMod();
			$sError=$ERR['saiu05dia'].$sSepara.$sError;
			}
		*/
		if ($DATA['saiu05detalle']==''){$sError=$ERR['saiu05detalle'].$sSepara.$sError;}
		if ($DATA['saiu05idtiposolorigen']==''){
			$sError=$ERR['saiu05idtiposolorigen_2'].$sSepara.$sError;
			}else{
			if ($DATA['saiu05idtemaorigen']==''){$sError=$ERR['saiu05idtemaorigen_2'].$sSepara.$sError;}
			}
		if ($DATA['saiu05rptaforma']==1){
			if (correo_VerificarDireccion($DATA['saiu05rptacorreo'])){
				}else{
				$sError=$ERR['saiu05rptacorreo'].$sSepara.$sError;
				}
			}
		if ($DATA['saiu05rptaforma']==2){
			if ($DATA['saiu05rptadireccion']==''){$sError=$ERR['saiu05rptadireccion'].$sSepara.$sError;}
			}
		if ($DATA['saiu05idmedio']==''){$sError=$ERR['saiu05idmedio'].$sSepara.$sError;}
		if ($DATA['saiu05idcategoria']==''){$sError=$ERR['saiu05idcategoria'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu05idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu05idresponsable_td'], $DATA['saiu05idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu05idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu05idinteresado_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu05idinteresado_td'], $DATA['saiu05idinteresado_doc'], $objDB, 'El tercero Interesado ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu05idinteresado'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu05idsolicitante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu05idsolicitante_td'], $DATA['saiu05idsolicitante_doc'], $objDB, 'El tercero Solicitante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu05idsolicitante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($sError==''){
		if(strlen($DATA['saiu05mes'])==2){
			$sTabla05='saiu05solicitud_'.$DATA['saiu05agno'].$DATA['saiu05mes'];
			}else{
			$sTabla05='saiu05solicitud_'.$DATA['saiu05agno'].'0'.$DATA['saiu05mes'];
			}
		if ($DATA['paso']==10){
			//El codigo no es posible que sea puesto por nadie.
			//$bQuitarCodigo=true;
			$DATA['saiu05consec']=tabla_consecutivo($sTabla05, 'saiu05consec', 'saiu05agno='.$DATA['saiu05agno'].' AND saiu05mes='.$DATA['saiu05mes'].' AND saiu05tiporadicado='.$DATA['saiu05tiporadicado'].'', $objDB);
				if ($DATA['saiu05consec']==-1){$sError=$objDB->serror;}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla05.' WHERE saiu05agno='.$DATA['saiu05agno'].' AND saiu05mes='.$DATA['saiu05mes'].' AND saiu05tiporadicado='.$DATA['saiu05tiporadicado'].' AND saiu05consec='.$DATA['saiu05consec'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					// if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			// if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu05id']=tabla_consecutivo($sTabla05,'saiu05id', '', $objDB);
			if ($DATA['saiu05id']==-1){$sError=$objDB->serror;}
			}
		}
	$bCalularTotales=false;
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu05detalle']=stripslashes($DATA['saiu05detalle']);}
		//Si el campo saiu05detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05detalle=addslashes($DATA['saiu05detalle']);
		$saiu05detalle=str_replace('"', '\"', $DATA['saiu05detalle']);
		if (get_magic_quotes_gpc()==1){$DATA['saiu05infocomplemento']=stripslashes($DATA['saiu05infocomplemento']);}
		//Si el campo saiu05infocomplemento permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05infocomplemento=addslashes($DATA['saiu05infocomplemento']);
		$saiu05infocomplemento=str_replace('"', '\"', $DATA['saiu05infocomplemento']);
		if (get_magic_quotes_gpc()==1){$DATA['saiu05respuesta']=stripslashes($DATA['saiu05respuesta']);}
		//Si el campo saiu05respuesta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05respuesta=addslashes($DATA['saiu05respuesta']);
		$saiu05respuesta=str_replace('"', '\"', $DATA['saiu05respuesta']);
		$idunidad = 0;
		$idgrupotrabajo = 0;
		$idresponsable = 0;
		$sSQL = 'SELECT saiu03idunidadresp1, saiu03idequiporesp1, saiu03idliderrespon1
		FROM saiu03temasol
		WHERE saiu03id = ' . $DATA['saiu05idtemaorigen'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable solicitud ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idunidad = $fila['saiu03idunidadresp1'];
			$idgrupotrabajo = $fila['saiu03idequiporesp1'];
			$idresponsable = $fila['saiu03idliderrespon1'];
			if ($idgrupotrabajo > 0) {
				$sSQL = 'SELECT bita28idtercero
				FROM bita28eqipoparte
				WHERE bita28idequipotrab = ' . $idgrupotrabajo . ' AND bita28activo = "S"' . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$aEquipo = array();
					$sEquipo = '';
					while ($fila = $objDB->sf($tabla)) {
						$aEquipo[] = $fila['bita28idtercero'];
					}
					$sEquipo = implode(',',$aEquipo);
					$sSQL = 'SELECT saiu05idresponsable, COUNT(saiu05id) AS asignaciones
					FROM ' . $sTabla05 . '
					WHERE saiu05idresponsable IN (' . $sEquipo . ')
					GROUP BY saiu05idresponsable
					ORDER BY asignaciones';
					$tabla = $objDB->ejecutasql($sSQL);
					$iResponsables = $objDB->nf($tabla);
					if ($iResponsables >= count($aEquipo)) {
						$fila = $objDB->sf($tabla);
						$idresponsable = $fila['saiu05idresponsable'];
					} else {
						$aResponsables = array();
						while($fila = $objDB->sf($tabla)) {
							$aResponsables[] = $fila['saiu05idresponsable'];
						}
						$aSinAsignar = array_values(array_diff($aEquipo, $aResponsables));
						$idresponsable = $aSinAsignar[0];
					}
				}
			}
		}
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu05agno']=fecha_agno();
			$DATA['saiu05mes']=fecha_mes();
			$DATA['saiu05dia']=fecha_dia();
			$DATA['saiu05origenagno']=0;
			$DATA['saiu05origenmes']=0;
			$DATA['saiu05origenid']=0;
			$DATA['saiu05hora']=fecha_hora();
			$DATA['saiu05minuto']=fecha_minuto();
			$DATA['saiu05estado']=-1; //Guarda Borrador
			//$DATA['saiu05idmedio']=0;
			$DATA['saiu05idtemafin']=0;
			$DATA['saiu05idtiposolfin']=0;
			//$DATA['saiu05idsolicitante']=0; //$_SESSION['u_idtercero'];
			$DATA['saiu05costogenera']=0;
			$DATA['saiu05costorefpago']='';
			$DATA['saiu05prioridad']=0;
			$DATA['saiu05idzona']=0;
			$DATA['saiu05cead']=0;
			$DATA['saiu05numref']=$DATA['saiu05agno'].$DATA['saiu05mes'].'-'.$DATA['saiu05id'].'-'.strtoupper(substr(str_shuffle(md5($DATA['saiu05id'])),0,5));
			$DATA['saiu05idescuela']=0;
			$DATA['saiu05idprograma']=0;
			$DATA['saiu05idperiodo']=0;
			$DATA['saiu05idcurso']=0;
			$DATA['saiu05idgrupo']=0;
			$DATA['saiu05tiemprespdias']=0;
			$DATA['saiu05tiempresphoras']=0;
			$DATA['saiu05fecharespprob']=0; //fecha_hoy();
			$DATA['saiu05numradicado']=0;
			// $DATA['saiu05idcategoria']=0;
			$DATA['saiu05idunidadresp']=$idunidad;
			$DATA['saiu05idequiporesp']=$idgrupotrabajo;
			$DATA['saiu05idresponsable']=$idresponsable;
			$sCampos3005='saiu05agno, saiu05mes, saiu05tiporadicado, saiu05consec, saiu05id, 
saiu05origenagno, saiu05origenmes, saiu05origenid, saiu05dia, saiu05hora, 
saiu05minuto, saiu05estado, saiu05idmedio, saiu05idtiposolorigen, saiu05idtemaorigen, 
saiu05idtemafin, saiu05idtiposolfin, saiu05idsolicitante, saiu05idinteresado, saiu05tipointeresado, 
saiu05rptaforma, saiu05rptacorreo, saiu05rptadireccion, saiu05costogenera, saiu05costovalor, 
saiu05costorefpago, saiu05prioridad, saiu05idzona, saiu05cead, saiu05numref, 
saiu05detalle, saiu05infocomplemento, saiu05idunidadresp, saiu05idequiporesp, saiu05idresponsable, saiu05idescuela, saiu05idprograma, 
saiu05idperiodo, saiu05idcurso, saiu05idgrupo, saiu05tiemprespdias, saiu05tiempresphoras, 
saiu05fecharespprob, saiu05respuesta, saiu05idmoduloproc, saiu05identificadormod, saiu05numradicado, saiu05idcategoria';
			$sValores3005=''.$DATA['saiu05agno'].', '.$DATA['saiu05mes'].', '.$DATA['saiu05tiporadicado'].', '.$DATA['saiu05consec'].', '.$DATA['saiu05id'].', 
'.$DATA['saiu05origenagno'].', '.$DATA['saiu05origenmes'].', '.$DATA['saiu05origenid'].', '.$DATA['saiu05dia'].', '.$DATA['saiu05hora'].', 
'.$DATA['saiu05minuto'].', '.$DATA['saiu05estado'].', '.$DATA['saiu05idmedio'].', '.$DATA['saiu05idtiposolorigen'].', '.$DATA['saiu05idtemaorigen'].', 
'.$DATA['saiu05idtemafin'].', '.$DATA['saiu05idtiposolfin'].', '.$DATA['saiu05idsolicitante'].', '.$DATA['saiu05idinteresado'].', '.$DATA['saiu05tipointeresado'].', 
'.$DATA['saiu05rptaforma'].', "'.$DATA['saiu05rptacorreo'].'", "'.$DATA['saiu05rptadireccion'].'", '.$DATA['saiu05costogenera'].', '.$DATA['saiu05costovalor'].', 
"'.$DATA['saiu05costorefpago'].'", '.$DATA['saiu05prioridad'].', '.$DATA['saiu05idzona'].', '.$DATA['saiu05cead'].', "'.$DATA['saiu05numref'].'", 
"'.$saiu05detalle.'", "'.$saiu05infocomplemento.'", '.$DATA['saiu05idunidadresp'].', '.$DATA['saiu05idequiporesp'].', '.$DATA['saiu05idresponsable'].', '.$DATA['saiu05idescuela'].', '.$DATA['saiu05idprograma'].', 
'.$DATA['saiu05idperiodo'].', '.$DATA['saiu05idcurso'].', '.$DATA['saiu05idgrupo'].', '.$DATA['saiu05tiemprespdias'].', '.$DATA['saiu05tiempresphoras'].', 
"'.$DATA['saiu05fecharespprob'].'", "'.$saiu05respuesta.'", '.$DATA['saiu05idmoduloproc'].', '.$DATA['saiu05identificadormod'].', '.$DATA['saiu05numradicado'].', '.$DATA['saiu05idcategoria'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla05.' ('.$sCampos3005.') VALUES ('.utf8_encode($sValores3005).');';
				$sdetalle=$sCampos3005.'['.utf8_encode($sValores3005).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla05.' ('.$sCampos3005.') VALUES ('.$sValores3005.');';
				$sdetalle=$sCampos3005.'['.$sValores3005.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu05dia';
			$scampo[2]='saiu05idcategoria';
			$scampo[3]='saiu05idtiposolorigen';
			$scampo[4]='saiu05idtemaorigen';
			$scampo[5]='saiu05tipointeresado';
			$scampo[6]='saiu05rptaforma';
			$scampo[7]='saiu05rptacorreo';
			$scampo[8]='saiu05rptadireccion';
			$scampo[9]='saiu05detalle';
			$scampo[10]='saiu05idunidadresp';
			$scampo[11]='saiu05idequiporesp';
			$scampo[12]='saiu05idresponsable';
			$scampo[13]='saiu05estado';
			$sdato[1]=$DATA['saiu05dia'];
			$sdato[2]=$DATA['saiu05idcategoria'];
			$sdato[3]=$DATA['saiu05idtiposolorigen'];
			$sdato[4]=$DATA['saiu05idtemaorigen'];
			$sdato[5]=$DATA['saiu05tipointeresado'];
			$sdato[6]=$DATA['saiu05rptaforma'];
			$sdato[7]=$DATA['saiu05rptacorreo'];
			$sdato[8]=$DATA['saiu05rptadireccion'];
			$sdato[9]=$saiu05detalle;
			$sdato[10]=$idunidad;
			$sdato[11]=$idgrupotrabajo;
			$sdato[12]=$idresponsable;
			$sdato[13]=$DATA['saiu05estado'];
			$numcmod=13;
			$sWhere='saiu05id='.$DATA['saiu05id'].'';
			$sSQL='SELECT * FROM '.$sTabla05.' WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($bDebug&&$bPrimera){
					for ($k=1;$k<=$numcmod;$k++){
						if (isset($filabase[$scampo[$k]])==0){
							$sDebug=$sDebug.fecha_microtiempo().' FALLA CODIGO: Falta el campo '.$k.' '.$scampo[$k].'<br>';
							}
						}
					$bPrimera=false;
					}
				$bsepara=false;
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla05.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla05.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3005] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu05id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				list($sErrorC, $sDebugC) = f3005_CargarDocumentos($DATA['saiu05agno'], $DATA['saiu05mes'], $DATA['saiu05id'], $objDB, $bDebug);
				if ($bDebug){
					$sDebug=$sDebug.fecha_microtiempo().' Guardar 3005 '.$sSQL.'<br>';
					$sDebug=$sDebug.$sDebugC;
				}
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu05id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				$bCalularTotales=true;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		}
	if ($bQuitarCodigo){
		$DATA['saiu05consec']='';
		}
	if ($bCalularTotales){
		list($sErrorT, $sDebugT)=f3000_CalcularTotales($DATA['saiu05idsolicitante'], $DATA['saiu05agno'], (int)$DATA['saiu05mes'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugT;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}