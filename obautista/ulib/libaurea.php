<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2017 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Friday, September 6, 2019
*/
function AUREA_DatosPersonales($objDB, $bDebug = false)
{
	/*
	list($objCampus, $sDebug) = TraerDBCampus($bDebug);
	if ($objCampus != NULL) {
		$sSQL = 'UPDATE mdl_user SET phone1="" WHERE phone1<>""';
		$result = $objCampus->ejecutasql($sSQL);
		$sSQL = 'UPDATE main_user SET phone1="" WHERE phone1<>""';
		$result = $objCampus->ejecutasql($sSQL);
		$sCondi = 'email<>"" AND ((email NOT LIKE "%@unadvirtual.edu.co") AND (email NOT LIKE "%@unad.edu.co") AND (email NOT LIKE "%@unad.us"))';
		$sSQL = 'UPDATE mdl_user SET email=CONCAT(username, "@unadvirtual.edu.co") WHERE ' . $sCondi;
		$result = $objCampus->ejecutasql($sSQL);
		$sSQL = 'UPDATE main_user SET email=CONCAT(username, "@unadvirtual.edu.co") WHERE ' . $sCondi;
		$result = $objCampus->ejecutasql($sSQL);
		$objCampus->CerrarConexion();
	}
	*/
	//Marzo 18 de 2023 - Pilas con los que consiguen confirmar el correo mas de una vez.
	$sSQL = 'SELECT unad11correonotifica,COUNT(1) AS Total
	FROM unad11terceros
	WHERE unad11fechaconfmail<>0
	GROUP BY unad11correonotifica
	HAVING COUNT(1)>1';
	$tablar = $objDB->ejecutasql($sSQL);
	while ($filar = $objDB->sf($tablar)) {
		$sSQL = 'UPDATE unad11terceros
		SET unad11fechaconfmail=0
		WHERE unad11fechaconfmail<>0 AND unad11correonotifica="' . $filar['unad11correonotifica'] . '"';
		$result = $objDB->ejecutasql($sSQL);
	}
}
function AUREA_ActualizarPerfilMoodle($idTercero, $objDB, $bDebug = false)
{
	list($sError, $idMoodle, $sDebug) = AUREA_ActualizarPerfilMoodleV2($idTercero, $objDB, $bDebug);
	return array($sError, $sDebug);
}
function AUREA_ActualizarPerfilMoodleV2($idTercero, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$idMoodle = 0;
	$bActualizaMainUser = false;
	return array($sError, $idMoodle, $sDebug);
	die();
	//23 de febrero de 2022, se deprecia esta funcion, ya que no usan mas estas llamadas.
	/*
	require __DIR__ . '/app.php';
	$idEntidad=0;
	if (isset($APP->entidad)!=0){
		if ($APP->entidad==1){$idEntidad=1;}
		}
	if ($idEntidad==1){
		//No se actualiza nada..
		return array($sError, $idMoodle, $sDebug);
		die();
		}
	list($objCampus, $sDebug)=TraerDBCampus($bDebug);
	if ($objCampus!=NULL){
		//alistamos los datos 
		$sSQL='SELECT unad11tipodoc, unad11doc, unad11id, unad11usuario, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, unad11telefono, unad11presentacion, unad11pais, unad11ciudaddoc, unad11idcampus 
		FROM unad11terceros 
		WHERE unad11id='.$idTercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		$fila=$objDB->sf($tabla);
		$sDocumento=$fila['unad11doc'];
		$idNumberMoodle=numeros_validar($sDocumento);
		if ($idNumberMoodle!=$sDocumento){
			$idNumberMoodle=$fila['unad11id']*(-1);
			}
		$sCodCiudad=$fila['unad11ciudaddoc'];
		$sNomCiudad='';
		$sCodPais=$fila['unad11pais'];
		$idMoodle=$fila['unad11idcampus'];
		$sUsuario=$fila['unad11usuario'];
		if ($sUsuario==''){
			$sUsuario=$fila['unad11tipodoc'].''.$fila['unad11doc'];
			//Por si las moscas...
			$sSQL='UPDATE unad11terceros SET unad11usuario="'.$sUsuario.'" WHERE unad11id='.$idTercero.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		$sPrefijoPais='CO';
		if ($sCodCiudad!=''){
			$sSQL='SELECT unad20nombre FROM unad20ciudad WHERE unad20codigo="'.$sCodCiudad.'"';
			$tabla20=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla20)>0){
				$fila20=$objDB->sf($tabla20);
				$sNomCiudad=$fila20['unad20nombre'];
				}
			}
		$bEntraPais=false;
		if ($sCodPais!=''){
			if ($sCodPais!='057'){
				$sSQL='SELECT unad18sufijo FROM unad18pais WHERE unad18codigo="'.$sCodPais.'"';
				$tabla20=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla20)>0){
					$fila20=$objDB->sf($tabla20);
					$sPrefijoPais=strtoupper($fila20['unad18sufijo']);
					}
				}
			}
		list($sCorreoUsuario, $sErrorC, $sDebugC, $sCorreoInstitucional)=AUREA_CorreoPrimario($idTercero, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugC;
		$unad11presentacion=(addslashes($fila['unad11presentacion']));
		if ($idMoodle==0){
			$sSQL='SELECT id FROM mdl_user WHERE username="'.$sUsuario.'"';
			$result=$objCampus->ejecutasql($sSQL);
			if ($objCampus->nf($result)>0){
				$filau=$objDB->sf($result);
				$idMoodle=$filau['id'];
				//Actualizar la unad11
				$sSQL='UPDATE unad11terceros SET unad11idmoodle='.$idMoodle.', unad11idncontents='.$idMoodle.', unad11iddatateca='.$idMoodle.', unad11idcampus='.$idMoodle.' WHERE unad11id='.$idTercero.'';
				$result=$objDB->ejecutasql($sSQL);
				}
			}
		if ($idMoodle==0){
			$sSQL='INSERT INTO mdl_user (idnumber, firstname, lastname, country, username, confirmed, policyagreed) VALUES ("'.$idNumberMoodle.'", "Nuevo", "Nuevo", "CO", "'.$sUsuario.'", 1, 1)';
			$result=$objCampus->ejecutasql($sSQL);
			if ($result==false){
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Error al intenter crear el usuario en mdl_user</b> ['.$sSQL.']';}
				}
			//Ahora verificar que exista.
			$sSQL='SELECT id FROM mdl_user WHERE username="'.$sUsuario.'"';
			$result=$objCampus->ejecutasql($sSQL);
			if ($objCampus->nf($result)>0){
				$filau=$objDB->sf($result);
				$idMoodle=$filau['id'];
				//Actualizar la unad11
				$sSQL='UPDATE unad11terceros SET unad11idmoodle='.$idMoodle.', unad11idcampus='.$idMoodle.' WHERE unad11id='.$idTercero.'';
				$result=$objDB->ejecutasql($sSQL);
				//Hacemos la insersión en main_user
				$bActualizaMainUser=true;
				$sSQL='INSERT INTO main_user (id, idnumber, firstname, lastname, country, username, confirmed, policyagreed) VALUES ('.$idMoodle.', "'.$idNumberMoodle.'", "Nuevo", "Nuevo", "CO", "'.$sUsuario.'", 1, 1)';
				$result=$objCampus->ejecutasql($sSQL);
				}
			}
		if ($idMoodle!=0){
			//, email="'.$sCorreoUsuario.'", phone1="'.$fila['unad11telefono'].'"
			$sSQL=utf8_decode('UPDATE mdl_user SET idnumber="'.$idNumberMoodle.'", firstname="'.trim($fila['unad11nombre1'].' '.$fila['unad11nombre2']).'", lastname="'.trim($fila['unad11apellido1'].' '.$fila['unad11apellido2']).'", country="'.$sPrefijoPais.'", city="'.$sNomCiudad.'", description="'.$unad11presentacion.'" WHERE id='.$idMoodle.'');
			$result=$objCampus->ejecutasql($sSQL);
			if ($bDebug){
				$sDebug=$sDebug.fecha_microtiempo().' PERFIL CAMPUS - Consulta de actualizacion: '.$sSQL.'.<br>';
				if ($result==false){
					$sError='Falla al actualizar los perfiles de campus.';
					}
				}
			if ($bActualizaMainUser){
				//, email="'.$sCorreoUsuario.'", phone1="'.$fila['unad11telefono'].'"
				$sSQL=utf8_decode('UPDATE main_user SET idnumber="'.$idNumberMoodle.'", firstname="'.trim($fila['unad11nombre1'].' '.$fila['unad11nombre2']).'", lastname="'.trim($fila['unad11apellido1'].' '.$fila['unad11apellido2']).'", country="'.$sPrefijoPais.'", city="'.$sNomCiudad.'", description="'.$unad11presentacion.'" WHERE id='.$idMoodle.'');
				$result=$objCampus->ejecutasql($sSQL);
				}
			if ($sCorreoInstitucional==''){$sCorreoInstitucional=$sCorreoUsuario;}
			// Marzo 13 de 2019
			//Aqui es actualizar el correo institucional en caso de que exista.
			$sSQL='UPDATE main_user SET email="'.$sCorreoInstitucional.'" WHERE id='.$idMoodle.'';
			$result=$objCampus->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' PERFIL main_user - Consulta de actualizacion: '.$sSQL.'.<br>';}
			//Termina la actualizacion..
			}
		$objCampus->CerrarConexion();
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' PERFIL CAMPUS - No se ha establecido el parametro dbhostcampus en el archivo app.php por tanto no se puede actualizar el perfil del tercero.<br>';}
		}
	return array($sError, $idMoodle, $sDebug);
	*/
}
function AUREA_Aplicativos($idTercero, $objDB)
{
	$sLista = '-99';
	//, unad07fechavence
	$sSQL = 'SELECT T5.unad05aplicativo FROM unad07usuarios AS TB, unad05perfiles AS T5 WHERE TB.unad07idtercero=' . $idTercero . ' AND TB.unad07idperfil=T5.unad05id AND TB.unad07vigente="S" AND T5.unad05aplicativo=-1';
	$tabla07 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla07) > 0) {
		$sSQL = 'SELECT unad01id FROM unad01sistema WHERE unad01publico="S"';
		$tabla07 = $objDB->ejecutasql($sSQL);
		while ($fila07 = $objDB->sf($tabla07)) {
			$sLista = $sLista . ',' . $fila07['unad01id'];
		}
	} else {
		$sSQL = 'SELECT T5.unad05aplicativo FROM unad07usuarios AS TB, unad05perfiles AS T5 WHERE TB.unad07idtercero=' . $idTercero . ' AND TB.unad07idperfil=T5.unad05id AND TB.unad07vigente="S" AND T5.unad05aplicativo>0 GROUP BY T5.unad05aplicativo';
		$tabla07 = $objDB->ejecutasql($sSQL);
		while ($fila07 = $objDB->sf($tabla07)) {
			$sLista = $sLista . ',' . $fila07['unad05aplicativo'];
		}
	}
	return $sLista;
}
function AUREA_ClavePermitidos()
{
	return '*._-¡!+#@()=+';
}
function AUREA_ClaveLimpiar($sValor, $sPermitidos = '')
{
	$sError = '';
	if ($sPermitidos == '') {
		$sPermitidos = AUREA_ClavePermitidos();
	}
	$sValidado = cadena_letrasynumeros($sValor, $sPermitidos);
	if ($sValidado != $sValor) {
		$sError = 'La contrase&ntilde;a contiene caracteres no permitidos.';
	}
	return $sError;
}
function AUREA_ClaveValidaV3($sValor, $idTercero, $objDB, $sPermitidos = '', $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	if (strlen($sValor) < 6) {
		$sError = 'La contrase&ntilde;a debe ser de m&iacute;nimo 6 caracteres.';
	}
	if ($sError == '') {
		if ($sPermitidos == '') {
			$sPermitidos = AUREA_ClavePermitidos();
		}
		$sError = AUREA_ClaveLimpiar($sValor, $sPermitidos);
	}
	if ($sError == '') {
		//Validar que tenga los minimos...
		//una mayuscula.
		$sValidado = cadena_limpiar($sValor, 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ');
		if ($sValidado == '') {
			$sError = 'La contrase&ntilde;a ingresada no contiene may&uacute;sculas';
		}
	}
	if ($sError == '') {
		//una mayuscula.
		$sValidado = cadena_limpiar($sValor, 'abcdefghijklmnñopqrstuvwxyz');
		if ($sValidado == '') {
			$sError = 'La contrase&ntilde;a ingresada no contiene min&uacute;scula';
		}
	}
	if ($sError == '') {
		$sValidado = cadena_limpiar($sValor, '1234567890');
		if ($sValidado == '') {
			$sError = 'La contrase&ntilde;a ingresada no contiene numeros';
		}
	}
	if ($sError == '') {
		$sValidado = cadena_limpiar($sValor, $sPermitidos);
		if ($sValidado == '') {
			$sError = 'La contrase&ntilde;a ingresada no contiene caracteres especiales';
		}
	}
	if ($sError == '') {
		if ($idTercero > 0){
			//Que no este poniendo la misma.
			$sHash = password_hash($sValor, PASSWORD_DEFAULT);
			$sSQL = 'SELECT unad11clave FROM unad11terceros WHERE unad11id=' . $idTercero . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Verificando la clave actual. ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				if (password_verify($sValor, $fila['unad11clave'])) {
					$sError = 'La contrase&ntilde;a ingresada es la misma que usa actualmente. Debe cambiarla.';
				}
			}
		}
	}
	if ($sError == '') {
		if ($idTercero > 0){
			//Ver que no se haya usado recientemente.
			$sSQL = 'SELECT unae10hash, unae10fecha FROM unae10historialclave WHERE unae10idtercero=' . $idTercero . ' ORDER BY unae10fecha DESC LIMIT 0, 3';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Historial de hash ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				//Revisar que las claves historicas no sean las mismas.
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Verficando hash del ' . $fila['unae10fecha'] . '<br>';
				}
				if (password_verify($sValor, $fila['unae10hash'])) {
					$sError = 'La contrase&ntilde;a ingresada ha sido usada recientemente';
				}
			}
		}
	}
	return array($sError, $sDebug);
}
function AUREA_ConfirmarCorreoNotifica($idTercero, $objDB, $sFrase = '', $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$aure01codigo = '';
	$unad11idgrupocorreo = -1;
	$sCorreoDestino = '';
	require __DIR__ . '/app.php';
	$sMes = date('Ym');
	$sTabla = 'aure01login' . $sMes;
	$bexiste = $objDB->bexistetabla($sTabla);
	if (!$bexiste) {
		list($sError, $sDebugT) = AUREA_CrearTabla_aure01login($sMes, $objDB, $bDebug);
	} else {
		list($sError, $sDebugT) = AUREA_RevTabla_aure01login($sMes, $objDB, $bDebug);
	}
	$sCorreoUsuario = '';
	if ($sError == '') {
		$sSQL = 'SELECT unad11correonotifica, unad11correoinstitucional, unad11fechaconfmail, unad11correonotificanuevo 
		FROM unad11terceros 
		WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$bHayCorreo = false;
			if ($fila['unad11fechaconfmail'] == 0) {
				if (correo_VerificarDireccion($fila['unad11correonotifica'])) {
					$bHayCorreo = true;
					$sCorreoUsuario = $fila['unad11correonotifica'];
				}
			} else {
				if (correo_VerificarDireccion($fila['unad11correonotificanuevo'])) {
					$bHayCorreo = true;
					$sCorreoUsuario = $fila['unad11correonotificanuevo'];
				}
			}
		}
		if ($sCorreoUsuario == '') {
			$sError = 'No se ha establecido un correo electr&oacute;nico de notificaciones v&aacute;lido.';
		} else {
			$aCorreo = explode('@', $sCorreoUsuario);
			if (count($aCorreo) > 1) {
				$sDominio = strtolower(trim($aCorreo[1]));
				list($unad11idgrupocorreo, $sDebugG) = AUREA_GrupoDominio($sDominio, $objDB, $bDebug);
			}
		}
	}
	if ($sError == '') {
		list($idSMTP, $sDebugS) = AUREA_SmtpMejorV2($sTabla, $unad11idgrupocorreo, $objDB, $bDebug);
		//Agregar el punto.
		$sProtocolo = 'http';
		$idEntidad = 0;
		if (isset($APP->entidad) != 0) {
			if ($APP->entidad == 1) {
				$idEntidad = 1;
			}
		}
		switch ($idEntidad) {
			case 1:
				break;
			default:
				if (isset($_SERVER['HTTPS']) != 0) {
					if ($_SERVER['HTTPS'] == 'on') {
						$sProtocolo = 'https';
					}
				}
				break;
		}
		$aure01punto = $sProtocolo . '://' . $_SERVER['SERVER_NAME'] . formato_UrlLimpia($_SERVER['REQUEST_URI']);
		$aure01consec = 0;
		$bInserta = true;
		$sSQL = 'SELECT aure01id FROM ' . $sTabla . ' WHERE aure01idtercero=' . $idTercero . ' AND aure01consec=0';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bInserta = false;
			$fila = $objDB->sf($tabla);
			$aure01id = $fila['aure01id'];
		}
		$aure01fecha = fecha_DiaMod();
		$aure01min = fecha_MinutoMod();
		$aure01codigo = md5($aure01fecha . $aure01min . $idTercero . $sTabla);
		$aure01codigo = numeros_validar($aure01codigo);
		$aure01codigo = substr($aure01codigo, 0, 10);
		$aure01ip = sys_traeripreal();
		if ($bInserta) {
			$aure01id = tabla_consecutivo($sTabla, 'aure01id', '', $objDB);
			$scampos = 'aure01idtercero, aure01consec, aure01id, aure01fecha, 
			aure01min, aure01codigo, aure01fechaaplica, aure01minaplica, aure01ip, aure01punto, aure01idsmtp';
			$svalores = '' . $idTercero . ', ' . $aure01consec . ', ' . $aure01id . ', ' . $aure01fecha . ', 
			' . $aure01min . ', "' . $aure01codigo . '", -1, 0, "' . $aure01ip . '", "' . $aure01punto . '", ' . $idSMTP . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $scampos . ') VALUES (' . cadena_codificar($svalores) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $scampos . ') VALUES (' . $svalores . ');';
			}
		} else {
			//actualizar el consecutivo 0
			$sSQL = 'UPDATE ' . $sTabla . ' SET aure01fecha=' . $aure01fecha . ', aure01min=' . $aure01min . ', aure01codigo="' . $aure01codigo . '", aure01fechaaplica=-1, aure01minaplica=0, aure01ip="' . $aure01ip . '", aure01punto="' . $aure01punto . '", aure01idsmtp=' . $idSMTP . ' WHERE aure01id=' . $aure01id . '';
		}
		$result = $objDB->ejecutasql($sSQL);
	}
	if ($sError == '') {
		if (!class_exists('clsMail_Unad')) {
			require $APP->rutacomun . 'libmail.php';
		}
		$idEntidad = 0;
		if (isset($APP->entidad) != 0) {
			if ($APP->entidad == 1) {
				$idEntidad = 1;
			}
		}
		switch ($idEntidad) {
			case 1: // UNAD FLORIDA
				$sNomEntidad = 'UNAD FLORIDA INC';
				break;
			default: // UNAD Colombia
				$sNomEntidad = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
				break;
		}
		//Ahora que se genero el codigo enviarlo al correo.
		//$URL=url_encode(''.$aure01id.'|'.$aure01codigo.'|'.$sFrase);
		/*
		$sMsg = '<h1>C&oacute;digo de confirmaci&oacute;n de correo de notificaciones en ' . $sNomEntidad . '</h1>
		Su c&oacute;digo de confirmaci&oacute;n es:<br>
		<h2>' . $aure01codigo . '</h2><br>
		<br>Este c&oacute;digo estar&aacute; vigente durante todo el d&iacute;a.<br>
		<b>Comedidamente:</b><br>
		Equipo de Soporte T&eacute;cnico.';
		*/
		$sTituloCorreo = 'C&oacute;digo de confirmaci&oacute;n de correo de notificaciones';
		$sMsg = AUREA_HTML_EncabezadoCorreo($sTituloCorreo);
		$sMsg = $sMsg . AUREA_HTML_CodigoConfirma($aure01codigo);
		$sMsg = $sMsg . AUREA_HTML_PieCorreo();
		//Enviar el mensaje.
		$objMail = new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto = cadena_codificar('Confirmación de correo de notificaciones en ' . $sNomEntidad . ' ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '');
		$objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario);
		if ($sError == '') {
			$objMail->sCuerpo = $sMsg;
			$sError = $objMail->Enviar($bDebug);
		}
		if ($sError != '') {
		} else {
			$sCorreoDestino = $sCorreoUsuario;
		}
		//Termina el envio del codigo...
	}
	return array($aure01codigo, $sError, $sDebug, $sCorreoDestino);
}
// Octubre 31 de 2022 - Se crea la versión 2
function AUREA_CorreoNotifica($idTercero, $objDB, $bDebug = false)
{
	list($sCorreoUsuario, $unad11idgrupocorreo, $sError, $sDebug) = AUREA_CorreoNotificaV2($idTercero, $objDB, $bDebug);
	return array($sCorreoUsuario, $sError, $sDebug, $unad11idgrupocorreo);
}
function AUREA_GrupoDominio($sDominio, $objDB, $bDebug = false)
{
	$unad11idgrupocorreo = 0;
	$sDebug = '';
	$sSQL = 'SELECT TB.aure76idgrupo 
	FROM aure76grupodominio AS TB, aure75grupocorreo AS T1 
	WHERE TB.aure76dominio="' . $sDominio . '" AND TB.aure76activo=1 AND TB.aure76idgrupo=T1.aure75id AND T1.aure75activo=1
	ORDER BY T1.aure75orden';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Grupo del dominio</b> Consultando dominio ' . $sSQL . ' <br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$unad11idgrupocorreo = $fila['aure76idgrupo'];
	}
	return array($unad11idgrupocorreo, $sDebug);
}
function AUREA_CorreoNotificaV2($idTercero, $objDB, $bDebug = false)
{
	// Octubre 31 de 2022 - Se considera el grupo correo, que se carga a partir del dominio que tenga el correo del usuario.
	$sError = '';
	$sCorreoUsuario = '';
	$sDebug = '';
	$unad11idgrupocorreo = -1;
	$sSQL = 'SELECT unad11correo, unad11aceptanotificacion, unad11correonotifica, unad11correoinstitucional, unad11fechaconfmail, 
	unad11rolunad, unad11correofuncionario, unad11idgrupocorreo 
	FROM unad11terceros 
	WHERE unad11id=' . $idTercero . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Correo notificaciones</b> Consultando datos ' . $sSQL . ' <br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$unad11idgrupocorreo = $fila['unad11idgrupocorreo'];
		$bHayCorreo = false;
		if ($fila['unad11fechaconfmail'] != 0) {
			//Este proceso es independiente de que acepte notificaciones o no....
			if (correo_VerificarDireccion(trim($fila['unad11correonotifica']))) {
				$bHayCorreo = true;
				$sCorreoUsuario = trim($fila['unad11correonotifica']);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Correo de notificaciones ' . $sCorreoUsuario . ' validado<br>';
				}
			}
		}
		if (!$bHayCorreo) {
			$sBase = trim($fila['unad11correofuncionario']);
			$sOrigen = 'del funcionario';
			if ($fila['unad11rolunad'] == 0) {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Iniciar verificacion<br>';
				}
				list($bPasa, $sDebugV) = correo_VerificarV2(trim($fila['unad11correo']), $bDebug);
				$sDebug = $sDebug . $sDebugV;
				if ($bPasa) {
					//Es estudiante y el correo no esta confirmado.
					$sBase = trim($fila['unad11correo']);
					$sOrigen = 'personal por no tener confirmado el correo de notificaciones.';
				} else {
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' El correo personal no es valido.<br>';
					}
				}
			} else {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Se ignora el correo personal en la primera fase.<br>';
				}
			}
			if (correo_VerificarDireccion($sBase)) {
				$bHayCorreo = true;
				$sCorreoUsuario = $sBase;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Correo de notificaciones ' . $sCorreoUsuario . ' {Correo ' . $sOrigen . '}<br>';
				}
			}
		}
		if (!$bHayCorreo) {
			$sOpcion1 = trim($fila['unad11correo']);
			$sOpcion2 = trim($fila['unad11correoinstitucional']);
			if ($fila['unad11rolunad'] > 0) {
				//$sOpcion1=$fila['unad11correoinstitucional'];
				//$sOpcion2=$fila['unad11correo'];
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Verificando correo ' . $sOpcion1 . ' <br>';
			}
			if (correo_VerificarDireccion($sOpcion1)) {
				$bHayCorreo = true;
				$sCorreoUsuario = $sOpcion1;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Opcion 1 ' . $sOpcion1 . ' validada<br>';
				}
			}
			if (!$bHayCorreo) {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Verificando correo 2 ' . $sOpcion2 . ' <br>';
				}
				if (correo_VerificarDireccion($sOpcion2)) {
					$bHayCorreo = true;
					$sCorreoUsuario = $sOpcion2;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Opcion 2 ' . $sOpcion2 . ' validada<br>';
					}
				}
			}
		}
		//Ahora validamos el grupo de correo.
		if ($unad11idgrupocorreo == -1) {
			if ($bHayCorreo) {
				$aCorreo = explode('@' , $sCorreoUsuario);
				if (count($aCorreo) > 1) {
					$sDominio = strtolower(trim($aCorreo[1]));
					list($unad11idgrupocorreo, $sDebugG) = AUREA_GrupoDominio($sDominio, $objDB, $bDebug = false);
					$sDebug = $sDebug . $sDebugG;
					$sSQL = 'UPDATE unad11terceros SET unad11idgrupocorreo=' . $unad11idgrupocorreo . ' WHERE unad11id=' . $idTercero . '';
					if ($bDebug){
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>GRUPO CORREO</b> Actualizando ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
				}
			}
		}
		if ($bDebug){
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>GRUPO CORREO</b> Grupo resultante ' . $unad11idgrupocorreo . '<br>';
		}
	}
	if ($sCorreoUsuario == '') {
		$sError = 'No se ha establecido un correo electr&oacute;nico v&aacute;lido para el usuario.';
	}
	return array($sCorreoUsuario, $unad11idgrupocorreo, $sError, $sDebug);
}
function AUREA_CorreoPrimario($idTercero, $objDB, $bDebug = false)
{
	$sError = '';
	$sCorreoUsuario = '';
	$sCorreoInstitucional = '';
	$sDebug = '';
	$sSQL = 'SELECT unad11correo, unad11aceptanotificacion, unad11correonotifica, unad11correoinstitucional, unad11fechaconfmail, unad11rolunad, unad11correofuncionario 
	FROM unad11terceros 
	WHERE unad11id=' . $idTercero . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta para los correos ' . $sSQL . ' <br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$bHayCorreo = false;
		if (correo_VerificarDireccion(trim($fila['unad11correofuncionario']))) {
			$bHayCorreo = true;
			$sCorreoUsuario = trim($fila['unad11correofuncionario']);
			$sCorreoInstitucional = $sCorreoUsuario;
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Correo primario ' . $sCorreoUsuario . ' <br>';
			}
		}
		if (!$bHayCorreo) {
			if (correo_VerificarDireccion(trim($fila['unad11correoinstitucional']))) {
				$bHayCorreo = true;
				$sCorreoUsuario = trim($fila['unad11correoinstitucional']);
				$sCorreoInstitucional = $sCorreoUsuario;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Correo primario ' . $sCorreoUsuario . ' <br>';
				}
			}
		}
		if (!$bHayCorreo) {
			if ($fila['unad11fechaconfmail'] != 0) {
				//Este proceso es independiente de que acepte notificaciones o no....
				if (correo_VerificarDireccion(trim($fila['unad11correonotifica']))) {
					$bHayCorreo = true;
					$sCorreoUsuario = trim($fila['unad11correonotifica']);
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Correo de notificaciones ' . $sCorreoUsuario . ' validado<br>';
					}
				}
			}
		}
		if (!$bHayCorreo) {
			$sOpcion1 = trim($fila['unad11correo']);
			$sOpcion2 = trim($fila['unad11correoinstitucional']);
			if ($fila['unad11rolunad'] > 0) {
				//$sOpcion1=$fila['unad11correoinstitucional'];
				//$sOpcion2=$fila['unad11correo'];
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Verificando correo ' . $sOpcion1 . ' <br>';
			}
			if (correo_VerificarDireccion($sOpcion1)) {
				$bHayCorreo = true;
				$sCorreoUsuario = $sOpcion1;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Opcion 1 ' . $sOpcion1 . ' validada<br>';
				}
			}
			if (!$bHayCorreo) {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Verificando correo 2 ' . $sOpcion2 . ' <br>';
				}
				if (correo_VerificarDireccion($sOpcion2)) {
					$bHayCorreo = true;
					$sCorreoUsuario = $sOpcion2;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Opcion 2 ' . $sOpcion2 . ' validada<br>';
					}
				}
			}
		}
	}
	if ($sCorreoUsuario == '') {
		$sError = 'No se ha establecido un correo electr&oacute;nico v&aacute;lido para el usuario.';
	}
	return array($sCorreoUsuario, $sError, $sDebug, $sCorreoInstitucional);
}
/**
 * @date miercoles, 22 de mayo de 2019
 * Esta funcion es similar a AUREA_CorreoNotifica solo que tiene reglas diferentes.
 */
function AUREA_CorreoRecupera($idTercero, $objDB, $bDebug = false)
{
	$sError = '';
	$sCorreoUsuario = '';
	$sDebug = '';
	$sSQL = 'SELECT unad11correo, unad11aceptanotificacion, unad11correonotifica, unad11correoinstitucional, unad11fechaconfmail, unad11rolunad, unad11correofuncionario 
	FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta para el correo de notificaciones ' . $sSQL . ' <br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$bHayCorreo = false;
		if ($fila['unad11fechaconfmail'] != 0) {
			//Este proceso es independiente de que acepte notificaciones o no....
			if (correo_VerificarDireccion(trim($fila['unad11correonotifica']))) {
				$bHayCorreo = true;
				$sCorreoUsuario = trim($fila['unad11correonotifica']);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Correo de notificaciones ' . $sCorreoUsuario . ' validado<br>';
				}
			}
		}
		if (!$bHayCorreo) {
			if (correo_VerificarDireccion(trim($fila['unad11correofuncionario']))) {
				$bHayCorreo = true;
				$sCorreoUsuario = trim($fila['unad11correofuncionario']);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Correo de notificaciones ' . $sCorreoUsuario . ' {Correo del funcionario}<br>';
				}
			}
		}
		if (!$bHayCorreo) {
			$sOpcion1 = trim($fila['unad11correo']);
			$sOpcion2 = trim($fila['unad11correoinstitucional']);
			if ($fila['unad11rolunad'] > 0) {
				//$sOpcion1=$fila['unad11correoinstitucional'];
				//$sOpcion2=$fila['unad11correo'];
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Verificando correo ' . $sOpcion1 . ' <br>';
			}
			if (correo_VerificarDireccion($sOpcion1)) {
				$bHayCorreo = true;
				$sCorreoUsuario = $sOpcion1;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Opcion 1 ' . $sOpcion1 . ' validada<br>';
				}
			}
			if (!$bHayCorreo) {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Verificando correo 2 ' . $sOpcion2 . ' <br>';
				}
				if (correo_VerificarDireccion($sOpcion2)) {
					$bHayCorreo = true;
					$sCorreoUsuario = $sOpcion2;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Opcion 2 ' . $sOpcion2 . ' validada<br>';
					}
				}
			}
		}
	}
	if ($sCorreoUsuario == '') {
		$sError = 'No se ha establecido un correo electr&oacute;nico v&aacute;lido para el usuario.';
	}
	return array($sCorreoUsuario, $sError, $sDebug);
}
function AUREA_CrearTabla_aure01login($sMes, $objDB, $bDebug = false)
{
	// Diciembre 10 de 2022 - Se agrega el id de origen que se usará para firmar documentos.
	$sError = '';
	$sDebug = '';
	$sTabla = 'aure01login' . $sMes;
	$bExiste = $objDB->bexistetabla($sTabla);
	if (!$bExiste) {
		$sSQL = "CREATE TABLE " . $sTabla . " (aure01idtercero int NOT NULL, aure01consec int NOT NULL, aure01id int NULL DEFAULT 0, 
		aure01fecha int NULL DEFAULT 0, aure01min int NULL DEFAULT 0, aure01codigo varchar(20) NULL, 
		aure01fechaaplica int NULL DEFAULT 0, aure01minaplica int NULL DEFAULT 0, aure01ip varchar(50) NULL, 
		aure01punto varchar(100) NULL, aure01idsmtp int NULL DEFAULT 0, aure01tipo int NULL DEFAULT 0,
		aure01idorigen int NULL DEFAULT 0)";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'No es posible iniciar el codigo de acceso para  ' . $sMes;
		} else {
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(aure01id)";
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX aure01login_id(aure01idtercero, aure01consec)";
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX aure01login_fecha(aure01fecha)";
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX aure01login_smtp(aure01idsmtp)";
			$result = $objDB->ejecutasql($sSQL);
			//la tabla hija...
			list($sErrorH, $sDebugH) = AUREA_CrearTabla_aure02intentos($sMes, $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugH;
		}
	}
	return array($sError, $sDebug);
}
// LA aure02intentos
function AUREA_CrearTabla_aure02intentos($sMes, $objDB, $bDebug = false)
{
	// Diciembre 10 de 2022 - Se agrega el id de origen que se usará para firmar documentos.
	$sError = '';
	$sDebug = '';
	$sTabla = 'aure02intentos' . $sMes;
	$bExiste = $objDB->bexistetabla($sTabla);
	if (!$bExiste) {
		$sSQL = "CREATE TABLE " . $sTabla . " (aure02idtercero int NOT NULL, aure02fecha int NOT NULL, aure02consec int NOT NULL, 
		aure02id int NOT NULL DEFAULT 0, aure02hora int NOT NULL DEFAULT 0, aure02min int NOT NULL DEFAULT 0, 
		aure02seg int NOT NULL DEFAULT 0, aure02ip varchar(50) NULL, aure02punto varchar(100) NULL)";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'No es posible iniciar el codigo de acceso para  ' . $sMes;
		} else {
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(aure02id)";
			$result = $objDB->ejecutasql($sSQL);
			$sSQL=$objDB->sSQLCrearIndice($sTabla, 'aure02intentos_id', 'aure02idtercero, aure02fecha, aure02consec', true);
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
//
function AUREA_CrearTabla_aure73encuesta($sMes, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla = 'aure73encuesta' . $sMes;
	$bExiste = $objDB->bexistetabla($sTabla);
	if (!$bExiste) {
		$sSQL = "CREATE TABLE " . $sTabla . " (aure73idtercero int NOT NULL, aure73consec int NOT NULL, aure73id int NULL DEFAULT 0, 
		aure73tipoencuesta int NULL DEFAULT 0, aure73idmodulo int NULL DEFAULT 0, aure73idtabla int NULL DEFAULT 0, 
		aure73idregistro int NULL DEFAULT 0, aure73fechagenera int NULL DEFAULT 0, aure73tipointeresado int NULL DEFAULT 0, 
		aure73idzona int NULL DEFAULT 0, aure73idcentro int NULL DEFAULT 0, aure73idescuela int NULL DEFAULT 0, 
		aure73idprograma int NULL DEFAULT 0, aure73idperiodo int NULL DEFAULT 0, aure73edad int NULL DEFAULT 0, 
		aure73codigo varchar(10) NULL, aure73acepta int NULL DEFAULT -1, aure73t1_p1 int NULL DEFAULT -1, 
		aure73t1_p2 int NULL DEFAULT -1, aure73t1_p3 int NULL DEFAULT -1, aure73t1_p4 int NULL DEFAULT -1, 
		aure73t1_p5 int NULL DEFAULT -1, aure73t2_p1 int NULL DEFAULT -1, aure73t2_p2 int NULL DEFAULT -1, 
		aure73t2_comentario varchar(250) NULL, aure73fecharespuesta int NULL DEFAULT 0)";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'No es posible iniciar el contenedor de encuesta para  ' . $sMes;
		} else {
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(aure73id)";
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_id', 'aure73idtercero, aure73consec', true);
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_tipo', 'aure73tipoencuesta');
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_modulo', 'aure73idmodulo, aure73idtabla, aure73idregistro');
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_fecha', 'aure73fechagenera');
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_interesado', 'aure73tipointeresado');
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_zona', 'aure73idzona');
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_centro', 'aure73idcentro');
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_escuela', 'aure73idescuela');
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_programa', 'aure73idprograma');
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = $objDB->sSQLCrearIndice($sTabla, 'aure73encuesta_periodo', 'aure73idperiodo');
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
//
function AUREA_RevTabla_aure01login($sMes, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla = 'aure01login' . $sMes;
	$sSQL = "SELECT aure01tipo FROM " . $sTabla . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla . " ADD aure01tipo int NULL DEFAULT 0";
		$result = $objDB->ejecutasql($sSQL);
	}
	$sSQL = "SELECT aure01idorigen FROM " . $sTabla . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla . " ADD aure01idorigen int NULL DEFAULT 0";
		$result = $objDB->ejecutasql($sSQL);
	}
	list($sErrorH, $sDebugH) = AUREA_CrearTabla_aure02intentos($sMes, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugH;
	return array($sError, $sDebug);
}
//Funciones html_de notificaciones
function AUREA_HTML_EncabezadoCorreo($sTituloCorreo)
{
	require __DIR__ . '/app.php';
	$idEntidad = 0;
	if (isset($APP->entidad) != 0) {
		switch ($APP->entidad) {
			case 1: // Unad Florida
			case 2: // Union Europea
			$idEntidad = $APP->entidad;
			break;
		}
	}
	$sProtocolo = 'https://';
	$sDominio = 'unad.edu.co';
	$sNomEntidad = 'Universidad Nacional Abierta y a Distancia - UNAD';
	$sRutaImg = 'https://datateca.unad.edu.co/img/';
	$sImgCabeza = 'correo2022/unad-acreditada-v.png';
	$sImgCabeza2 = 'correo2022/unad-acreditada.png';
	switch ($idEntidad) {
		case 1: // Unad Florida
			$sDominio = 'unad.us';
			$sNomEntidad = 'UNAD - Florida';
			$sRutaImg = 'https://datateca.unad.edu.co/img/fl/';
			$sImgCabeza = 'correo2022/unad-florida-v.png';
			$sImgCabeza2 = 'correo2022/unad-florida.png';
			break;
		case 2: // Union Europea
			$sProtocolo = 'http://';
			$sDominio = 'www.unad-ue.es';
			$sNomEntidad = 'UNAD - Union Europea';
			$sRutaImg = 'http://campus.unad-ue.es/img/';
			$sImgCabeza = 'correo/unad-ue-v.png';
			$sImgCabeza2 = 'correo/unad-ue.png';
			break;
	}
	$sRes = '<!DOCTYPE html>
	<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns="http://www.w3.org/148/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="format-detection" content="telephone=no" />
	<title>' . $sNomEntidad . '</title>
	<style type="text/css">
	html {margin: 0;padding: 0;}
	body,#bodyTable,
	#bodyCell {height: 100% !important;margin: 0;padding: 0;width: 100% !important;}
	table {border-collapse: collapse;}
	table[id=bodyTable] {width: 100% !important;margin: auto;max-width: 600px !important;font-weight: normal;}
	table,
	td {mso-table-lspace: 0pt;mso-table-rspace: 0pt;}
	img {-ms-interpolation-mode: bicubic;outline: none;text-decoration: none;}
	body,table,td,p,a,li,
	blockquote {-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-weight: normal !important;}
	body,
	#bodyTable {background-color: #E1E1E1;}
	#emailBody {background-color: #e5e5e5;}
	.text-black {color: #000000 !important;}
	.text-white {color: #e5e5e5 !important;}
	.text-blue {color: #01385f !important;}
	.text-red {color: #ed3833 !important;}
	.hidden-lg {display: none !important;mso-hide: all !important;}
	@media only screen and (max-width: 599px) {
	body {width: 100% !important;min-width: 100% !important;}
	table[id="emailBody"] {width: 100% !important;}
	.hidden {display: none;}
	.hidden-sm {display: none !important;}
	.hidden-lg {display: table !important;overflow: visible !important;max-height: inherit !important;}
	.hidden-lg-inline {display: inline-block !important;overflow: visible !important;max-height: inherit !important;}
	.textbody {padding: 47px 38px !important;font-size: 14px !important;line-height: 18px !important;width: 100%;}
	.textbody #personalizacion {font-size: 18px !important;}
	.text-center {text-align: center !important;height: auto !important;}
	.text-right {text-align: right !important;}
	.text-left {text-align: left !important;}
	.text-justify {text-align: justify !important;}
	.margin-auto {margin-left: auto !important;margin-right: auto !important;}
	.center-image {margin: 0 auto !important;text-align: center !important;}
	.right-image {margin-right: 0 !important;text-align: right !important;}
	.left-image {margin-left: 0 !important;text-align: left !important;}
	.block {display: block !important;margin: 0 auto !important;}
	.block-auto {display: block !important;height: auto !important;margin-left: auto !important;margin-right: auto !important;float: none !important;}
	.height-auto {height: auto !important;}
	.scale-95 {width: 95% !important;}
	.scale-90 {width: 90% !important;}
	.scale-85 {width: 85% !important;}
	.p-5 {padding: 5% !important;}
	.p-10 {padding: 10% !important;}
	.p-15 {padding: 15% !important;}
	.p-20 {padding: 20% !important;}
	.p-30 {padding: 30% !important;}
	.p-40 {padding: 40% !important;}
	.p-50 {padding: 50% !important;}
	.pv-5 {padding-top: 5% !important;padding-bottom: 5% !important;}
	.pv-10 {padding-top: 10% !important;padding-bottom: 10% !important;}
	.pv-20 {padding-top: 20% !important;padding-bottom: 20% !important;}
	.pv-30 {padding-top: 30% !important;padding-bottom: 30% !important;}
	.pv-40 {padding-top: 40% !important;padding-bottom: 40% !important;}
	.pv-50 {padding-top: 50% !important;padding-bottom: 50% !important;}
	.pt-0 {padding-top: 0% !important;}
	.pt-5 {padding-top: 5% !important;}
	.pt-10 {padding-top: 10% !important;}
	.pt-20 {padding-top: 20% !important;}
	.pt-30 {padding-top: 30% !important;}
	.pt-40 {padding-top: 40% !important;}
	.pb-0 {padding-bottom: 0% !important;}
	.pb-5 {padding-bottom: 5% !important;}
	.pb-10 {padding-bottom: 10% !important;}
	.pb-20 {padding-bottom: 20% !important;}
	.pb-30 {padding-bottom: 30% !important;}
	.pb-40 {padding-bottom: 40% !important;}
	.pl-0 {padding-left: 0% !important;}
	.pl-5 {padding-left: 5% !important;}
	.pl-10 {padding-left: 10% !important;}
	.pl-20 {padding-left: 20% !important;}
	.pl-30 {padding-left: 30% !important;}
	.pl-40 {padding-left: 40% !important;}
	.pr-0 {padding-right: 0% !important;}
	.pr-5 {padding-right: 5% !important;}
	.pr-10 {padding-right: 10% !important;}
	.pr-20 {padding-right: 20% !important;}
	.pr-30 {padding-right: 30% !important;}
	.pr-40 {padding-left: 40% !important;}
	.ph-5 {padding-right: 5% !important;padding-left: 5% !important;}
	.ph-10 {padding-right: 10% !important;padding-left: 10% !important;}
	.ph-20 {padding-right: 20% !important;padding-left: 20% !important;}
	.ph-30 {padding-right: 30% !important;padding-left: 30% !important;}
	.ph-40 {padding-right: 40% !important;padding-left: 40% !important;}
	.mt-0 {margin-top: 0% !important;}
	.mt-5 {margin-top: 5% !important;}
	.mt-10 {margin-top: 10% !important;}
	.mt-20 {margin-top: 20% !important;}
	.mt-30 {margin-top: 30% !important;}
	.mt-40 {margin-top: 40% !important;}
	.mb-0 {margin-bottom: 0% !important;}
	.mb-5 {margin-bottom: 5% !important;}
	.mb-10 {margin-bottom: 10% !important;}
	.mb-20 {margin-bottom: 20% !important;}
	.mb-30 {margin-bottom: 30% !important;}
	.mb-40 {margin-bottom: 40% !important;}
	.col-sm-auto {width: auto !important;}
	.col-sm-0 {width: 2.33333333% !important;}
	.col-sm-1 {width: 8.33333333% !important;}
	.col-sm-2 {width: 16.66666667% !important;}
	.col-sm-3 {width: 25% !important;}
	.col-sm-4 {width: 33.33333333% !important;}
	.col-sm-5 {width: 41.66666667% !important;}
	.col-sm-6 {width: 50% !important;}
	.col-sm-7 {width: 58.33333333% !important;}
	.col-sm-8 {width: 66.66666667% !important;}
	.col-sm-9 {width: 75% !important;}
	.col-sm-10 {width: 83.333333% !important;}
	.col-sm-11 {width: 91.666667% !important;}
	.col-sm-12 {width: 100% !important;}
	.border-none {border: 0 !important;}
	@media only screen and (max-width: 400px) {
	.scale-sm-95 {width: 95% !important;}
	.scale-sm-90 {width: 90% !important;}
	.scale-sm-85 {width: 85% !important;}.height-px {height: 108px !important;}}} 
	@media only screen and (max-width: 470px) {}
	</style>
	</head>
	<body bgcolor="#e5e5e5" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
	<center style="background-color:#e5e5e5;">
	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important; background-color:#e5e5e5;">
	<tr>
	<td align="center" valign="top" id="bodyCell">
	<table bgcolor="#e5e5e5" border="0" cellpadding="0" cellspacing="0" width="600" id="emailBody">
	<tr>
	<td align="center">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
	<tr>
	<td align="center" valign="top">
	<table class="hidden-lg" border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;" bgcolor="#ffffff">
	<tbody>
	<tr>
	<td class="hidden-lg col-sm-12 text-center" align="center" style="text-align:center; margin: 15px 0 15px 0;">
	<a target="_blank" href="' . $sProtocolo . $sDominio . '/"><img width="197" src="' . $sRutaImg . $sImgCabeza . '"  alt="UNAD"></a>
	</td>
	</tr>
	</tbody>
	</table>
	<table border="0" class="hidden-sm" cellpadding="30" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tbody>
	<tr>
	<td valign="bottom" class="hidden-sm" align="center" style="width: 600px;">
	<a target="_blank" href="' . $sProtocolo . $sDominio . '/"><img class="hidden-sm" width="540" src="' . $sRutaImg . $sImgCabeza2 . '"  alt="UNAD"></a>
	</td>
	</tr>
	</tbody>
	</table>
	<table border="0" cellpadding="10" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tbody>
	<tr>
	<td align="center" bgcolor="#005883" style="font-size:22px;">
	<font face="Arial, Helvetica, sans-serif" color="#ffffff">' . $sTituloCorreo . '</font>
	</td>
	</tr>
	</tbody>
	</table>';
	return $sRes;
}
// Codigo de confirmacion del correo de notificaciones.
function AUREA_HTML_CodigoConfirma($sCodigo)
{
	$sRes = '';
	$sRutaBase = __DIR__ . '/';
	require $sRutaBase . 'app.php';
	$sRutaComun = $sRutaBase . $APP->rutacomun;
	$sIdioma = 'es';
	if (isset($_SESSION['unad_idioma']) != 0) {
		$sIdioma = $_SESSION['unad_idioma'];
	}
	$mensajes_17 = $sRutaComun . 'lg/lg_17_' . $sIdioma . '.php';
	if (!file_exists($mensajes_17)) {
		$mensajes_17 = $sRutaComun . 'lg/lg_17_es.php';
	}
	require $mensajes_17;
	$sRutaImg = 'https://datateca.unad.edu.co/img/';
	$idEntidad = Traer_Entidad();
	switch ($idEntidad) {
		case 1:
			$sRutaImg = 'https://datateca.unad.edu.co/img/fl/';
			break;
		case 2: // UNION EUROPEA
			$sRutaImg = 'https://campus.unad-ue.es/img/correo/';
			break;
	}
	$sNumImage = '';
	$sLinea = substr($sCodigo, 3, 1);
	switch ($sLinea) {
		case '0':
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
			$sNumImage = '_' . $sLinea;
			break;
	}
	$sInfoCentro = '<font face="Arial, Helvetica, sans-serif" color="#333333" size="12">
	<p style="padding: 10px 20px; background: #F0B429;">' . $sCodigo . '</p>
	</font>';
	$sRes = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tbody>
	<tr>
	<td align="right" bgcolor="#F1F1F1" valign="bottom" width="268" height="290" class="hidden-sm">
	<img class="text-center" style="max-width: 100%; display: block;" width="260" src="' . $sRutaImg . 'correo2022/estudiante' . $sNumImage . '.jpg">
	</td>
	<td bgcolor="#F1F1F1">
	<font face="Arial, Helvetica, sans-serif" color="#333333">
	<p>&nbsp;Su c&oacute;digo de confirmaci&oacute;n es:</p>
	</font>' . $sInfoCentro . '

	<font face="Arial, Helvetica, sans-serif" color="#333333">
	<p>Este c&oacute;digo estar&aacute; vigente durante todo el d&iacute;a.<br>
	<b>Comedidamente:</b><br>
	Equipo de Soporte T&eacute;cnico.</p>
	</font>
	</td>
	</tr>
	</tbody>
	</table>';
	return $sRes;
}
// Cuerpo del correo de codigo de envio
function AUREA_HTML_CodigoCorreo($sCodigo, $sURL)
{
	$sRutaBase = __DIR__ . '/';
	require $sRutaBase . 'app.php';
	$sRutaComun = $sRutaBase . $APP->rutacomun;
	$mensajes_17 = $sRutaComun . 'lg/lg_17_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_17)) {
		$mensajes_17 = $sRutaComun . 'lg/lg_17_es.php';
	}
	require $mensajes_17;
	$sRutaImg = 'https://datateca.unad.edu.co/img/correo2022/';
	$idEntidad = Traer_Entidad();
	switch ($idEntidad) {
		case 1:
			$sRutaImg = 'https://datateca.unad.edu.co/img/fl/correo2022/';
			break;
		case 2: // UNION EUROPEA
			$sRutaImg = 'http://campus.unad-ue.es/img/correo/';
			break;
	}
	$sNumImage = '';
	$sLinea = substr($sCodigo, 3, 1);
	switch ($sLinea) {
		case '0':
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
			$sNumImage = '_' . $sLinea;
			break;
	}
	$sRes = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tbody>
	<tr>
	<td align="right" bgcolor="#F1F1F1" valign="bottom" width="268" height="290" class="hidden-sm">
	<img class="text-center" style="max-width: 100%; display: block;" width="260" src="' . $sRutaImg . 'estudiante' . $sNumImage . '.jpg">
	</td>
	<td align="center" bgcolor="#F1F1F1">
	<font face="Arial, Helvetica, sans-serif" color="#333333">
	<p>' . $ETI['mail_login_parte2'] . ':</p>
	</font>
	<font face="Arial, Helvetica, sans-serif" color="#333333" size="12">
	<p style="padding: 10px 20px; background: #F0B429;">' . $sCodigo . '</p>
	</font>

	<font face="Arial, Helvetica, sans-serif" color="#333333">
	<p><div style="word-wrap: break-word; width: 100%; max-width: 330px; padding: 0 10px;">' . $ETI['mail_login_parte3'] . ':
	<a style="color: #005883;" href="' . $sURL . '" target="_blank">' . $sURL . '</a>
	</div></p>
	</font>
	</td>
	</tr>
	</tbody>
	</table>';
	return $sRes;
}
//
function AUREA_HTML_CodigoRecupera($sCabeza, $sCodigo, $aure01punto, $URL, $sPie)
{
	$sRes = '';
	$sRutaBase = __DIR__ . '/';
	require $sRutaBase . 'app.php';
	$sRutaComun = $sRutaBase . $APP->rutacomun;
	$sIdioma = 'es';
	if (isset($_SESSION['unad_idioma']) != 0) {
		$sIdioma = $_SESSION['unad_idioma'];
	}
	$mensajes_17 = $sRutaComun . 'lg/lg_17_' . $sIdioma . '.php';
	if (!file_exists($mensajes_17)) {
		$mensajes_17 = $sRutaComun . 'lg/lg_17_es.php';
	}
	require $mensajes_17;
	$sRutaImg = 'https://datateca.unad.edu.co/img/';
	$idEntidad = Traer_Entidad();
	switch ($idEntidad) {
		case 1:
			$sRutaImg = 'https://datateca.unad.edu.co/img/fl/';
			break;
	}
	$sNumImage = '';
	$sLinea = substr($sCodigo, 3, 1);
	switch ($sLinea) {
		case '0':
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
			$sNumImage = '_' . $sLinea;
			break;
	}
	$sInfoCentro = '<font face="Arial, Helvetica, sans-serif" color="#333333" size="12">
	<p style="padding: 10px 20px; background: #F0B429;">' . $sCodigo . '</p>
	</font>';
	if ($aure01punto != '') {
		$sInfoCentro = '<font face="Arial, Helvetica, sans-serif" color="#333333">
		<p><div style="word-wrap: break-word; width: 100%; max-width: 330px; padding: 0 10px;"><a style="color: #005883;" href="' . $aure01punto . '?u=' . $URL . '" target="_blank">' . $URL . '</a>
		</div></p>
		</font>';
	}
	$sRes = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tbody>
	<tr>
	<td align="right" bgcolor="#F1F1F1" valign="bottom" width="268" height="290" class="hidden-sm">
	<img class="text-center" style="max-width: 100%; display: block;" width="260" src="' . $sRutaImg . 'correo2022/estudiante' . $sNumImage . '.jpg">
	</td>
	<td bgcolor="#F1F1F1">
	<font face="Arial, Helvetica, sans-serif" color="#333333">
	<p>' . $sCabeza . '</p>
	</font>' . $sInfoCentro . '

	<font face="Arial, Helvetica, sans-serif" color="#333333">
	<p>' . $sPie . '</p>
	</font>
	</td>
	</tr>
	</tbody>
	</table>';
	return $sRes;
}
// Correo de encuesta de publica.
function AUREA_HTML_CuerpoCorreoEncuesta($sCodigo, $idImagen, $sURL, $iFechaServicio)
{
	$sRutaBase = __DIR__ . '/';
	require $sRutaBase . 'app.php';
	$sRutaComun = $sRutaBase . $APP->rutacomun;
	if (isset($_SESSION['unad_idioma']) == 0) {
		$_SESSION['unad_idioma'] = 'es';
	}
	$mensajes_17 = $sRutaComun . 'lg/lg_17_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_17)) {
		$mensajes_17 = $sRutaComun . 'lg/lg_17_es.php';
	}
	require $mensajes_17;
	$sRutaImg = 'https://datateca.unad.edu.co/img/';
	$sURLDestino = 'https://aurea.unad.edu.co/satisfaccion/';
	$idEntidad = Traer_Entidad();
	switch ($idEntidad) {
		case 1:
			$sRutaImg = 'https://datateca.unad.edu.co/img/fl/';
			break;
		case 2: // UNION EUROPEA
			$sRutaImg = 'https://campus.unad-ue.es/img/correo/';
			break;
	}
	$sNumImage = '';
	switch ($idImagen) {
		case '0':
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
			$sNumImage = '_' . $idImagen;
			break;
	}
	$sFechaLarga = formato_FechaLargaDesdeNumero($iFechaServicio, true);
	$sRes = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tbody>
	<tr>
	<td align="right" bgcolor="#F1F1F1" valign="bottom" width="268" height="290" class="hidden-sm">
	<img class="text-center" style="max-width: 100%; display: block;" width="260" src="' . $sRutaImg . 'correo2022/estudiante' . $sNumImage . '.jpg">
	</td>
	<td align="center" bgcolor="#F1F1F1">';
	$sRes = $sRes . '<font face="Arial, Helvetica, sans-serif" color="#333333">
	<p>' . $ETI['mail_enc_parte1'] . $sFechaLarga . $ETI['mail_enc_parte2'] . '</p>
	</font>
	<table border="0" cellpadding="10" cellspacing="0" width="80%" style="width: 80%; max-width: 80%; min-width: 80%;">
	<tbody>
	<tr>
	<td align="center" bgcolor="#F0B429" style="font-size:22px;">
	<font face="Arial, Helvetica, sans-serif" color="#005883">
	<a style="padding: 10px 20px; color: #005883; font-size: 12px; text-decoration: none; word-wrap: break-word;" target="_blank"
	href="' . $sURLDestino . '?u=' . $sURL . '">
	<span style="font-size: 24px;">RESPONDER</span>
	</a>
	</font>
	</td>
	</tr>
	<tr>
	<td height="5">
	</td>
	</tr>
	</tbody>
	</table>

	<table border="0" cellpadding="10" cellspacing="0" width="60%" style="width: 60%; max-width: 60%; min-width: 60%;">
	<tbody>
	<tr>
	<td align="center" bgcolor="#005883" style="font-size:14px;">
	<font face="Arial, Helvetica, sans-serif" color="#ffffff">
	<a style="padding: 10px 20px; color: #ffffff; font-size: 12px; text-decoration: none; word-wrap: break-word;" target="_blank"
	href="' . $sURLDestino . '?n=' . $sURL . '">
	Si no desea responder, por favor haga clic aqu&iacute;
	</a>
	</font>
	</td>
	</tr>
	<tr>
	<td height="5">
	</td>
	</tr>
	</tbody>
	</table>

	<font face="Arial, Helvetica, sans-serif">
	<p>
	En caso de que no pueda acceder desde este correo, por favor ingrese a<br>
	<a style="padding: 10px 20px; color: #005883; word-wrap: break-word;" target="_blank"
	href="' . $sURLDestino . '">' . $sURLDestino . '
	</a><br>
	e ingrese su n&uacute;mero de documento y el c&oacute;digo <b>' . $sCodigo . '</b>
	</p>
	<br>
	</font>';
	$sRes = $sRes . '</td>
	</tr>
	</tbody>
	</table>';
	return $sRes;
}
// Correo de desercion
function AUREA_HTML_CuerpoCorreoDesercion($sCodigo, $idImagen, $sMes, $aure73id, $iFechaServicio, $objDB)
{
	$sRutaBase = __DIR__ . '/';
	require $sRutaBase . 'app.php';
	$sRutaComun = $sRutaBase . $APP->rutacomun;
	if (isset($_SESSION['unad_idioma']) == 0) {
		$_SESSION['unad_idioma'] = 'es';
	}
	$mensajes_17 = $sRutaComun . 'lg/lg_17_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_17)) {
		$mensajes_17 = $sRutaComun . 'lg/lg_17_es.php';
	}
	require $mensajes_17;
	$sRutaImg = 'https://datateca.unad.edu.co/img/';
	$sURLDestino = 'https://aurea.unad.edu.co/satisfaccion/';
	$idEntidad = Traer_Entidad();
	switch ($idEntidad) {
		case 1:
			$sRutaImg = 'https://datateca.unad.edu.co/img/fl/';
			break;
		case 2: // UNION EUROPEA
			$sRutaImg = 'https://campus.unad-ue.es/img/correo/';
			break;
	}
	$sNumImage = '';
	switch ($idImagen) {
		case '0':
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
			$sNumImage = '_' . $idImagen;
			break;
	}
	$sFechaLarga = formato_FechaLargaDesdeNumero($iFechaServicio, true);
	$sRes = '';
	/*
	$sRes = '<table border="0" cellpadding="30" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tbody>
	<tr>
	<td align="center" bgcolor="#F1F1F1">
	<font face="Arial, Helvetica, sans-serif" color="#333333" size="4">
	<p>Queremos conocer porqu&eacute; no continuas con nosotros, por favor selecciona una de las siguentes opciones:</p>
	</font>
	</td>
	</tr>
	</tbody>
	</table>';
	*/
	$sRes = $sRes . '<font face="Arial, Helvetica, sans-serif" color="#333333">
	<table border="0" cellpadding="20" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<span style="font-size:18px;">
	Apreciado estudiante, para la UNAD es muy importante conocer las razones por las cuales no continu&oacute; su proceso formativo con nosotros.<br><br>
	Por lo anterior le solicitamos seleccionar la alternativa que explica sus razones.
	</span>
	<tbody>
	<tr>
	<td align="center">
	<table border="0" cellpadding="0" cellspacing="0" width="80%" style="width: 80%; max-width: 80%; min-width: 80%;">';
	$sCuerpo = '';
	$iFilas = 12;
	$aValor = array(
		0, 1, 3, 4, 5, 6,
		7, 9, 10, 13, 14,
		2, 11
	);
	$aIconos = array(
		'', 'no.png', 'time.png', 'student.png', 'quality.png', 'support.png',
		'cursor.png', 'program.png', 'university.png', 'wifi.png', 'money.png',
		'break.png', 'clear.png'
	);
	$aEtiquetas = array(
		'', 'No Adaptaci&oacute;n a la Modalidad de estudio', 'Administraci&oacute;n del tiempo y/o h&aacute;bitos de estudio', 'Prefiere la modalidad presencial', 'No Recibi&oacute; servicio de Calidad', 'No Recibi&oacute; Soporte t&eacute;cnico y administrativo',
		'Poco dominio de herramientas ofim&aacute;ticas', 'El programa acad&eacute;mico no responde a sus expectativas', 'Se encuentra estudiando en otra universidad', 'Dificultades en conectividad', 'Factor econ&oacute;mico',
		'Dificultades personales y/o familiares', 'Prefiero no responder'
	);
	//Octubre 28 de 2022, en lugar de que vaya fijo el listado, se toma de la tabla cara15
	$iFilas = 0;
	$sSQL = 'SELECT cara15id, cara15textoencuesta, cara15imagenencuesta
	FROM cara15factordeserta
	WHERE cara15vaaencuesta=1 AND cara15activa="S"
	ORDER BY cara15ordenencuesta, cara15textoencuesta';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$iFilas++;
		$aValor[$iFilas] = $fila['cara15id'];
		$aIconos[$iFilas] = $fila['cara15imagenencuesta'];
		$aEtiquetas[$iFilas] = cadena_notildes($fila['cara15textoencuesta']);
	}
	for ($k = 1; $k <= $iFilas; $k++) {
		if ($sCuerpo != '') {
			$sCuerpo = $sCuerpo . '<tr><td colspan="2"><hr></td></tr>';
		}
		$URL = url_encode('' . $sMes . '|' . $aure73id . '|' . md5($iFechaServicio) . '|' . $aValor[$k]);
		$sURL = '' . $URL . '';
		$sCuerpo = $sCuerpo . '<tr style="padding:5px 0 5px 0 !important">
		<td width="80" align="center" valign="baseline">
		<a href="' . $sURLDestino . '?u=' . $sURL . '"><img src="' . $sRutaImg . 'ico-encuestas/' . $aIconos[$k] . '"  width="50"></a>
		</td>
		<td valign="middle">
		<a style="text-decoration: none; padding: 30px 0; color: #333333;font-size:16px;" target="_blank" href="' . $sURLDestino . '?u=' . $sURL . '">
		' . $aEtiquetas[$k] . '
		</a>
		</td>
		</tr>';
	}
	$sRes = $sRes . $sCuerpo;
	$sRes = $sRes . '</table>
	<font face="Arial, Helvetica, sans-serif">
	<p>
		En caso de que no pueda acceder desde este correo, por favor ingrese a<br>
		<a style="padding: 10px 20px; color: #005883; word-wrap: break-word;" target="_blank"
			href="' . $sURLDestino . '">' . $sURLDestino . '
		</a><br>
		e ingrese su n&uacute;mero de documento y el c&oacute;digo <b>' . $sCodigo . '</b>
	</p>
	<br>
	</font>	
	</td>
	</tr>
	</tbody>
	</table>
	</font>';
	return $sRes;
}
//Firma del codigo de correo.
function AUREA_HTML_FirmaDocumento($sCodigo, $idModulo, $iSubProceso, $idRegistro, $objDB)
{
	$sRes = '';
	$sRutaBase = __DIR__ . '/';
	require $sRutaBase . 'app.php';
	$sRutaComun = $sRutaBase . $APP->rutacomun;
	$sIdioma = 'es';
	if (isset($_SESSION['unad_idioma']) != 0) {
		$sIdioma = $_SESSION['unad_idioma'];
	}
	$mensajes_17 = $sRutaComun . 'lg/lg_17_' . $sIdioma . '.php';
	if (!file_exists($mensajes_17)) {
		$mensajes_17 = $sRutaComun . 'lg/lg_17_es.php';
	}
	require $mensajes_17;
	$sRutaImg = 'https://datateca.unad.edu.co/img/';
	$idEntidad = Traer_Entidad();
	switch ($idEntidad) {
		case 1:
			$sRutaImg = 'https://datateca.unad.edu.co/img/fl/';
			break;
	}
	$sNumImage = '';
	$sLinea = substr($sCodigo, 3, 1);
	switch ($sLinea) {
		case '0':
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
			$sNumImage = '_' . $sLinea;
			break;
	}
	$sDetalleProceso = '';
	switch($idModulo) {
		case 508:
			$sSQL = 'SELECT T3.ppto03codigo, TB.ppto08consec, TB.ppto08objeto, TB.ppto08vrtotal 
			FROM ppto08soldisponib AS TB, ppto03vigencia AS T3 
			WHERE TB.ppto08id=' . $idRegistro . ' AND TB.ppto08vigencia=T3.ppto03id';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sNomSubProceso = '{' . $iSubProceso . '}';
				switch($iSubProceso){
					case 11:
						$sNomSubProceso = 'firmando';
						break;
				}
				$sDetalleProceso = '<p><b>Usted esta ' . $sNomSubProceso . ' la solicitud de disponiblidad presupuestal N&deg; ' . formato_numero($fila['ppto08consec']) . ' de la vigencia ' . cadena_notildes($fila['ppto03codigo']) . '</b><br>
				con objeto: ' . cadena_notildes($fila['ppto08objeto']) . '<br>
				por valor de: <b>' . numeros_enletras($fila['ppto08vrtotal']) . '</b> (' . formato_moneda($fila['ppto08vrtotal']) . ')</p>';
			}
			break;
	}
	$sInfoCentro = '<font face="Arial, Helvetica, sans-serif" color="#333333" size="12">
	<p style="padding: 10px 20px; background: #F0B429;">' . $sCodigo . '</p>
	</font>';
	$sRes = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tbody>
	<tr>
	<td align="right" bgcolor="#F1F1F1" valign="bottom" width="268" height="290" class="hidden-sm">
	<img class="text-center" style="max-width: 100%; display: block;" width="260" src="' . $sRutaImg . 'correo2022/estudiante' . $sNumImage . '.jpg">
	</td>
	<td bgcolor="#F1F1F1">
	<font face="Arial, Helvetica, sans-serif" color="#333333">' . $sDetalleProceso . '
	<p>&nbsp;Su c&oacute;digo de confirmaci&oacute;n es:</p>
	</font>' . $sInfoCentro . '

	<font face="Arial, Helvetica, sans-serif" color="#333333">
	<p>Este c&oacute;digo estar&aacute; vigente durante todo el d&iacute;a.</p>
	</font>
	</td>
	</tr>
	</tbody>
	</table>';
	return $sRes;
}
//
function AUREA_HTML_NoResponder()
{
	return 'Por favor no responder este mensaje, esta es una notificaci&oacute;n del Sistema de Atenci&oacute;n Integral - SII<br>';
}
//
function AUREA_HTML_PieCorreo()
{
	$sIdioma = 'es';
	if (isset($_SESSION['unad_idioma']) != 0) {
		$sIdioma = $_SESSION['unad_idioma'];
	}
	$sRutaBase = __DIR__ . '/';
	require './app.php';
	$sRutaComun = $APP->rutacomun;
	$mensajes_17 = $sRutaComun . 'lg/lg_17_' . $sIdioma . '.php';
	if (!file_exists($mensajes_17)) {
		$mensajes_17 = $sRutaComun . 'lg/lg_17_es.php';
	}
	require $mensajes_17;
	$idEntidad = Traer_Entidad();
	$sProtocolo = 'https://';
	$sDominio = 'unad.edu.co';
	$sNomEntidad = 'Universidad Nacional Abierta y a Distancia - UNAD';
	$sRutaImg = 'https://datateca.unad.edu.co/img/';
	switch ($idEntidad) {
		case 1:
			$sDominio = 'unad.us';
			$sNomEntidad = 'UNAD - Florida';
			$sRutaImg = 'https://datateca.unad.edu.co/img/fl/';
			$sDato1 = 'UNAD Florida (License # 2900) is an online university licensed by the Commission for
			Independent Education, Florida Department of Education. Additional information regarding
			this institution may be obtained by contacting the Commission at:
			325 West Gaines St. Suite 1414
			Tallahassee, FL, 32399
			Phone Number : (850) 245-3200
			call free: (888) 224-6684 <a style="color: #005883;" href="http://www.fldoe.org/policy/cie">www.fldoe.org/policy/cie</a>';
			$sDato2 = '';
			break;
		case 2: // Union Europea
			$sDominio = 'unad-ue.es';
			$sNomEntidad = 'UNAD - Union Europea';
			$sRutaImg = 'https://datateca.unad.edu.co/img/ue/';
			$sDato1 = 'UNAD Union Europea ';
			$sDato2 = '';
			break;
		default:
			$sDato1 = 'Instituci&oacute;n de Educaci&oacute;n Superior sujeta a inspecci&oacute;n y vigilancia por el Ministerio de Educaci&oacute;n Nacional de Colombia - IES 2102';
			$sDato2 = 'En Bogot&aacute; D.C. (Colombia) Tel: <a style="color: #005883;" href="tel:+576013443700">(+57)(601)344 3700</a>
			<br>
			L&iacute;nea gratuita nacional: <a style="color: #005883;">01 8000 115223</a>';
			break;
	}
	$sRes = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tr>
	<td bgcolor="#F0B429" height="10"></td>
	</tr>
	</table>

	<table border="0" cellpadding="10" cellspacing="0" width="100%" style="width: 100%; max-width: 100%; min-width: 100%;">
	<tr>
	<td align="center">
	<font face="Arial, Helvetica, sans-serif" color="#333333">
	<p>
	<strong>' . $sNomEntidad . '</strong>
	<br>' . $sDato1 . '
	</p>
	<p>' . $sDato2 . '</p>
	</font>
	<a target="_blank" href="' . $sProtocolo . $sDominio . '/">
	</a>
	</td>
	</tr>
	</table>

	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</center>
	</body>
	</html>';
	return $sRes;
}
//Fin de las html de notificaciones.
function AUREA_IniciarLogin($idTercero, $objDB, $sFrase = '', $iUso = 0, $bDebug = false)
{
	//$iUso=2 desde soporte, $iUso=3 desde chatbox
	//El uso 0 es para iniciar sesion , el 1 es para recuperar contraseña. y 2 para recuperar contrase;a desde soporte.
	$sError = '';
	$sDebug = '';
	$aure01codigo = '';
	$bDesdeSoporte = false;
	$bDesdeChatBox = false;
	switch ($iUso) {
		case 3:
			$bDesdeChatBox = true;
			$iUso = 1;
			break;
		case 2:
			$bDesdeSoporte = true;
			$iUso = 1;
			break;
	}
	$sInfoRastro = 'Se inicia envio de codigo de acceso';
	$sIdioma = 'es';
	if (isset($_SESSION['unad_idioma']) != 0) {
		$sIdioma = $_SESSION['unad_idioma'];
	}
	$sRutaBase = __DIR__ . '/';
	require $sRutaBase . 'app.php';
	$sRutaComun = $sRutaBase . $APP->rutacomun;
	$mensajes_17 = $sRutaComun . 'lg/lg_17_' . $sIdioma . '.php';
	if (!file_exists($mensajes_17)) {
		$mensajes_17 = $sRutaComun . 'lg/lg_17_es.php';
	}
	require $mensajes_17;
	$sMes = date('Ym');
	$sTabla = 'aure01login' . $sMes;
	$bexiste = $objDB->bexistetabla($sTabla);
	if ($objDB->dbmodelo == 'M') {
		if (!$bexiste) {
			list($sError, $sDebugT) = AUREA_CrearTabla_aure01login($sMes, $objDB, $bDebug);
		} else {
			list($sError, $sDebugT) = AUREA_RevTabla_aure01login($sMes, $objDB, $bDebug);
		}
	}
	$idEntidad = Traer_Entidad();
	switch ($idEntidad) {
		case 1: // UNAD FLORIDA
			$sNomEntidad = 'UNAD FLORIDA INC';
			$sMailSeguridad = 'aluna@unad.us';
			$sURLCampus = 'http://unad.us/campus/';
			break;
		case 2: // UNAD UNION EUROPEA
			$sNomEntidad = 'UNAD UNION EUROPEA';
			$sMailSeguridad = 'informacion@unad-ue.es';
			$sURLCampus = 'http://campus.unad-ue.es/';
			break;
		default: // UNAD Colombia
			$sNomEntidad = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
			$sMailSeguridad = 'soporte.campus@unad.edu.co';
			$sURLCampus = 'https://campus0c.unad.edu.co/campus/';
			break;
	}
	$sCorreoUsuario = '';
	$iCodigoRastro = 0;
	$sCodSMTP = '';
	if ($sError == '') {
		list($sCorreoUsuario, $idGrupoCorreo, $sError, $sDebugM) = AUREA_CorreoNotificaV2($idTercero, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugM;
	}
	if ($sError == '') {
		list($idSMTP, $sDebugS) = AUREA_SmtpMejorV2($sTabla, $idGrupoCorreo, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugS;
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' SMTP: ' . $idSMTP . ' Grupo: ' . $idGrupoCorreo . '<br>';
		}
		//Agregar el punto.
		$sProtocolo = 'http';
		switch ($idEntidad) {
			case 1:
				break;
			default:
				if (isset($_SERVER['HTTPS']) != 0) {
					if ($_SERVER['HTTPS'] == 'on') {
						$sProtocolo = 'https';
					}
				}
				break;
		}
		$aure01punto = $sProtocolo . '://' . $_SERVER['SERVER_NAME'] . formato_UrlLimpia($_SERVER['REQUEST_URI']);
		$aure01consec = tabla_consecutivo($sTabla, 'aure01consec', 'aure01idtercero=' . $idTercero . '', $objDB);
		$aure01fecha = fecha_DiaMod();
		$aure01min = fecha_MinutoMod();
		$aure01codigo = md5($aure01fecha . $aure01min . $idTercero . $sTabla);
		$aure01codigo = numeros_validar($aure01codigo);
		$aure01codigo = substr($aure01codigo, 0, 10);
		$aure01ip = sys_traeripreal();
		$aure01id = tabla_consecutivo($sTabla, 'aure01id', '', $objDB);
		$scampos = 'aure01idtercero, aure01consec, aure01id, aure01fecha, 
		aure01min, aure01codigo, aure01fechaaplica, aure01minaplica, aure01ip, aure01punto, aure01idsmtp';
		$svalores = '' . $idTercero . ', ' . $aure01consec . ', ' . $aure01id . ', ' . $aure01fecha . ', 
		' . $aure01min . ', "' . $aure01codigo . '", -1, 0, "' . $aure01ip . '", "' . $aure01punto . '", ' . $idSMTP . '';
		$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $scampos . ') VALUES (' . $svalores . ');';
		$result = $objDB->ejecutasql($sSQL);
		//Septiembre 3 de 2021 - Se agrega un control de fallos.
		if ($result == false) {
			//Fallo el registro del codigo, hacemos un reintento, pero refrescando el id.
			$aure01id = tabla_consecutivo($sTabla, 'aure01id', '', $objDB);
			$svalores = '' . $idTercero . ', ' . $aure01consec . ', ' . $aure01id . ', ' . $aure01fecha . ', 
			' . $aure01min . ', "' . $aure01codigo . '", -1, 0, "' . $aure01ip . '", "' . $aure01punto . '", ' . $idSMTP . '';
			$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $scampos . ') VALUES (' . $svalores . ');';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				//No, esto fallo definitivamente.
				$sError = $ERR['falla_codigo'];
			}
		}
	}
	if ($sError == '') {
		//Ahora que se genero el codigo enviarlo al correo.
		if (!class_exists('clsMail_Unad')) {
			require $sRutaComun . 'libmail.php';
		}
		if ($iUso == 0) {
			$sInfoRastro = 'Se inicia envio de codigo de acceso al correo ' . $sCorreoUsuario . ' desde la IP ' . $aure01ip . '';
			$sTituloCorreo = $ETI['mail_login_titulo'] . ' ' . $sNomEntidad . '';
			$sHost = '';
			if (isset($_COOKIE['idPC']) != 0) {
				$sHost = '|' . $_COOKIE['idPC'];
			}
			$URL = url_encode('' . $aure01id . '|' . $aure01codigo . '|' . $sFrase . $sHost);
			$sURL = $aure01punto . '?u=' . $URL . '';
			$sMsg = AUREA_HTML_EncabezadoCorreo($sTituloCorreo);
			$sMsg = $sMsg . AUREA_HTML_CodigoCorreo($aure01codigo, $sURL);
			$sMsg = $sMsg . AUREA_HTML_PieCorreo();
		} else {
			//Recuperar la clave...
			$sAdvertencia = $ETI['mail_pass_parte1'] . ' ' . $sMailSeguridad . '';
			$sInfoRastro = 'Se envia codigo para CAMBIO DE CLAVE al correo ' . $sCorreoUsuario . '';
			$sTituloCorreo = $ETI['mail_pass_parte3'] . ' ' . $sNomEntidad . '';
			//$sMsgCuerpo='<h1>'.$ETI['mail_pass_parte3'].' '.$sNomEntidad.'</h1>';
			$sCabeza = '';
			$URL = '';
			$aure01punto = '';
			$sMsgCuerpo = '';
			$sPie = '';
			$sVencimiento = $ETI['mail_pass_vence'];
			$iCodigoRastro = 3;
			if (($bDesdeSoporte) || ($bDesdeChatBox)) {
				$sInfoRastro = 'Se envia codigo para REESTABLECER CLAVE al correo ' . $sCorreoUsuario . '';
				$aure01punto = $sURLCampus . 'recuperar.php';
				$sVencimiento = $ETI['mail_pass_vence12'];
				if ($aure01min > 719) {
					$sVencimiento = $ETI['mail_pass_vencemn'];
				}
				$URL = url_encode('' . $aure01id . '|' . $aure01codigo . '|97531|' . $sFrase);
				if ($bDesdeSoporte) {
					$sAdvertencia = $ETI['mail_pass_parte4'];
				} else {
					$sAdvertencia = $ETI['mail_pass_parte5'];
					$sInfoRastro = $sInfoRastro . ' [ChatBot]';
				}
				//$sMsgCuerpo=$sMsgCuerpo.$ETI['mail_pass_parte6'].': <a href="'.$aure01punto.'?u='.$URL.'">'.$aure01punto.'?u='.$URL.'</a><br>';
				$sCabeza = $ETI['mail_pass_parte6'] . '';
			} else {
				//$sMsgCuerpo=$sMsgCuerpo.$ETI['mail_pass_parte7'].'<br><h2>'.$aure01codigo.'</h2><br>';
				$sCabeza = $ETI['mail_pass_parte7'] . '';
			}
			$sPie = '' . $ETI['mail_pass_parte8'] . ' ' . $sVencimiento . '<br>';
			$sPie = $sPie . '' . $sAdvertencia . ' - ' . $ETI['mail_pass_parte9'] . ': ' . $aure01id . ' - ' . date('Ym') . '<br>' . $ETI['mail_pass_parte10'] . '';
			$sMsg = AUREA_HTML_EncabezadoCorreo($ETI['mail_pass_parte3'] . ' ' . $sNomEntidad . '');
			$sMsg = $sMsg . AUREA_HTML_CodigoRecupera($sCabeza, $aure01codigo, $aure01punto, $URL, $sPie);
			$sMsg = $sMsg . AUREA_HTML_PieCorreo();
		}
		//Enviar el mensaje.
		$objMail = new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto = cadena_tildes($sTituloCorreo) . ' ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '';
		$objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario);
		if ($sError == '') {
			$objMail->sCuerpo = $sMsg;
			list($sError, $sDebugM, $sCodSMTP) = $objMail->EnviarV2($bDebug);
			$sDebug = $sDebug . $sDebugM;
			if ($sError == '') {
				$sInfoRastro = $sInfoRastro . ' [SMTP: ' . $sCodSMTP . ']';
				if ($bDesdeSoporte) {
					list($bRes, $sDebugR) = seg_rastro(17, $iCodigoRastro, 0, $_SESSION['unad_id_tercero'], $sInfoRastro, $objDB, $bDebug, $idTercero);
				} else {
					list($bRes, $sDebugR) = seg_rastro(17, $iCodigoRastro, 0, $idTercero, $sInfoRastro, $objDB, $bDebug, $idTercero);
				}
				$sDebug = $sDebug . $sDebugR;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Se envia correo a ' . $sCorreoUsuario . '<br>';
				}
			}
		}
		if ($sError != '') {
		} else {
		}
		//Termina el envio del codigo...
	}
	return array($aure01codigo, $sError, $sDebug, $sCodSMTP);
}
function AUREA_NotificaPieDePagina()
{
	$idEntidad = Traer_Entidad();
	switch ($idEntidad) {
		case 1: // Unad Florida
			$sRes = 'Comedidamente:<br>';
			$sRes = $sRes . '<img src="http://datateca.unad.edu.co/unad_fl.png" alt="UNAD FLORIDA INC" width="191" height="79" />';
			break;
		default:
			$sRes = 'Comedidamente:<br>';
			$sRes = $sRes . '<img src="http://datateca.unad.edu.co/unad_40.png" alt="Universidad Nacional Abierta y a Distancia - UNAD" width="191" height="79" />';
			break;
	}
	return $sRes;
}
function AUREA_RequiereDobleAutenticacion($idTercero, $objDB)
{
	list($bRes, $sInfoRastro) = AUREA_RequiereDobleAutenticacionV2($idTercero, $objDB);
	return $bRes;
}
function AUREA_RequiereDobleAutenticacionV2($idTercero, $objDB)
{
	require './app.php';
	if (isset($APP->idserver) != 0) {
		if ($APP->idserver == 1) {
			$bDepurador = false;
			switch ($idTercero) {
				case 4:
				case 6: //
				$bDepurador = true;
			}
			if ($bDepurador) {
				$bRes = true;
				$sInfoRastro = 'Modo depurador';
				return array($bRes, $sInfoRastro);
			}
		}
	}
	$bRes = false;
	$bConAlumnos = false;
	$sInfoRastro = '';
	//Primero ver si esta habilitado el servicio.
	$sSQL = 'SELECT unad88loginmail, unad88doblelogest FROM unad88opciones WHERE unad88id=1';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['unad88loginmail'] == 'S') {
			$bRes = true;
			if ($fila['unad88doblelogest'] == 'S') {
				$bConAlumnos = true;
			}
		} else {
			$sInfoRastro = ' [Servicio Desactivado]';
		}
	}
	if ($bRes) {
		$bPermite = false;
		$bNiega = false;
		$sSQL = 'SELECT T9.core09excluirdobleaut 
		FROM core01estprograma AS TB, core09programa AS T9 
		WHERE TB.core01idtercero=' . $idTercero . ' AND TB.core01idprograma=T9.core09id
		GROUP BY T9.core09excluirdobleaut';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			if ($fila['core09excluirdobleaut'] == 0) {
				$bNiega = true;
			} else {
				$bPermite = true;
			}
		}
		if ($bPermite) {
			if (!$bNiega) {
				$bRes = false;
			}
		}
	}
	if ($bRes) {
		$bPermite = false;
		$bNiega = false;
		$sSQL = 'SELECT T2.exte02saltardobleaut 
		FROM core16actamatricula AS TB, exte02per_aca AS T2 
		WHERE TB.core16tercero=' . $idTercero . ' AND TB.core16peraca=T2.exte02id
		GROUP BY T2.exte02saltardobleaut';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			if ($fila['exte02saltardobleaut'] == 0) {
				$bNiega = true;
			} else {
				$bPermite = true;
			}
		}
		if ($bPermite) {
			if (!$bNiega) {
				$bRes = false;
			}
		}
	}
	if ($bRes) {
		//Ahora descartamos que sea un usuario especial
		$iDia = fecha_DiaMod();
		$sSQL = 'SELECT cara40accesoainternet, cara40consec FROM cara40padrinos WHERE cara40idtercero=' . $idTercero . ' AND cara40estado=1 AND cara40estudiadesde<=' . $iDia . ' AND cara40estudiahasta>=' . $iDia . ' ORDER BY cara40estudiahasta DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			//Es un usuairo que depende de otros y como no tiene internet no se le aplican las restricciones de seguridad.
			switch ($fila['cara40accesoainternet']) {
				case 0: // Sin Acceso a Internet
				case 1: // Acceso Limitado
					$bRes = false;
					$sTipoAcceso = 'sin acceso a internet';
					if ($fila['cara40accesoainternet'] == 1) {
						$sTipoAcceso = 'con acceso a internet limitado';
					}
					$sInfoRastro = ' [Usuario ' . $sTipoAcceso . ' - Modulo Padrinos - Consecutivo: ' . $fila['cara40consec'] . ']';
					break;
			}
		}
	}
	if ($bRes) {
		$sSQL = 'SELECT unad11exluirdobleaut, unad11fechaconfmail, unad11correonotifica, unad11correofuncionario, unad11fechaclave FROM unad11terceros WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['unad11exluirdobleaut'] == 'S') {
				$bRes = false;
				$sInfoRastro = ' [Usuario Excludio - Modulo Usuarios Especiales]';
			}
			if ($bRes) {
				if ($fila['unad11fechaconfmail'] == 0) {
					if (trim($fila['unad11correofuncionario']) == '') {
						$bRes = false;
						$sInfoRastro = ' [Usuario sin correo confirmado]';
						if ($fila['unad11fechaclave'] != $iDia) {
							//Febrero 13 de 2020 se dejan rastros para poder hacer seguimiento.
							seg_rastroV2(17, 7, 0, 0, $idTercero, 'Se forza a cambiar la contraseña por falta de confirmación del correo de notificaciones.', $objDB);
							//Se modifica esta restricción  porque los usaurios nuevos no han confirmado correo y cambiaraon la contraseña el mismo dia...
							$sSQL = 'UPDATE unad11terceros SET unad11debeactualizarclave=1 WHERE unad11id=' . $idTercero . '';
							$result = $objDB->ejecutasql($sSQL);
						}
					}
				} else {
					//Bloquear los correos que sean restringidos...
					list($sErrorC, $sDebugC) = AUREA_ValidaCuentaCorreo($fila['unad11correonotifica'], $objDB);
					if ($sErrorC != '') {
						$bRes = false;
						//Febrero 13 de 2020 se dejan rastros para poder hacer seguimiento.
						seg_rastroV2(17, 7, 0, 0, $idTercero, 'Se forza a cambiar la contraseña por error en la validación del correo de notificaciones.', $objDB);
						$sSQL = 'UPDATE unad11terceros SET unad11fechaconfmail=0, unad11debeactualizarclave=1 WHERE unad11id=' . $idTercero . '';
						$result = $objDB->ejecutasql($sSQL);
					}
				}
			}
		}
	}
	if ($bRes) {
		if (!$bConAlumnos) {
			//Saber si la persona tiene un rol dentro del sistema
			$sSQL = 'SELECT 1 FROM unad07usuarios WHERE unad07idtercero=' . $idTercero . ' AND unad07vigente="S"';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) == 0) {
				//Es un alumno....
				$bRes = false;
				$sInfoRastro = ' [Servicio Inactivo para Estudiantes]';
			}
		}
	}
	return array($bRes, $sInfoRastro);
}
function AUREA_SmtpMejor($sTabla, $objDB, $bDebug = false)
{
	list($idSMTP, $sDebug) = AUREA_SmtpMejorV2($sTabla, -1, $objDB, $bDebug);
	return array($idSMTP, $sDebug);
}
function AUREA_SmtpMejorV2($sTabla, $idGrupo, $objDB, $bDebug = false)
{
	//Valor por defecto
	$idSMTP = 2;
	$sDebug = '';
	$aLista = array();
	$iTotal = 0;
	//Cargar el listado de SMTPS.
	$sCondiGrupo = '';
	if ($idGrupo > 0){
		$sCondiGrupo = ' AND T1.unad69idgrupo=' . $idGrupo . '';
	}
	$sIds = '-99';
	$sSQL = 'SELECT TB.unad89idsmtp 
	FROM unad89loginsmtp AS TB, unad69smtp AS T1 
	WHERE TB.unad89activo="S" AND TB.unad89idopciones=1 AND TB.unad89idsmtp=T1.unad69id AND T1.unad69confirmado="S"' . $sCondiGrupo;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>MEJOR SMTP</b> Correos Disponibles: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$iTotal++;
		$aLista[$iTotal]['cod'] = $fila['unad89idsmtp'];
		$aLista[$iTotal]['uso'] = 0;
		$sIds = $sIds . ',' . $fila['unad89idsmtp'];
	}
	if ($iTotal > 1) {
		//Ver que tanto uso ha tenido cada smtp
		$aure01fecha = fecha_DiaMod();
		$sSQL = 'SELECT aure01idsmtp, COUNT(aure01id) AS Total 
		FROM ' . $sTabla . ' 
		WHERE aure01fecha=' . $aure01fecha . ' AND aure01idsmtp IN (' . $sIds . ')
		GROUP BY aure01idsmtp';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>MEJOR SMTP</b> Uso del correo: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			for ($k = 1; $k <= $iTotal; $k++) {
				if ($aLista[$k]['cod'] == $fila['aure01idsmtp']) {
					$aLista[$k]['uso'] = $fila['Total'];
					$k = $iTotal + 1;
				}
			}
		}
		$iMenor = 999999;
		for ($k = 1; $k <= $iTotal; $k++) {
			if ($aLista[$k]['uso'] < $iMenor) {
				$iMenor = $aLista[$k]['uso'];
				$idSMTP = $aLista[$k]['cod'];
			}
		}
	} else {
		if ($iTotal == 1) {
			$idSMTP = $aLista[1]['cod'];
		}
	}
	return array($idSMTP, $sDebug);
}
function AUREA_ValidaCuentaCorreo($sCorreo, $objDB, $bDebug = false)
{
	//--- Friday, September 6, 2019
	$sRutaBase = __DIR__ . '/';
	require $sRutaBase . 'app.php';
	$sRutaComun = $sRutaBase . $APP->rutacomun;
	$mensajes_todas = $sRutaComun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $sRutaComun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$sDebug = '';
	if (!correo_VerificarDireccion($sCorreo)) {
		$sError = $ERR['correo_formato'];
	} else {
		//Verificar que no sea un dominio bloqueado.
		list($ssup, $smedio, $sDominio) = cadena_partir(ltrim($sCorreo), '@', '');
		$sSQL = 'SELECT 1 FROM unae21dominiosrestr WHERE unae21dominio="' . $sDominio . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = $ERR['correo_noenvio'];
		}
	}
	return array($sError, $sDebug);
}
// --------------- ENCUESTAS PUBLICAS (DE SATISFACCION - DESERCION)
function AUREA_IniciarEncuestaPublica($idTercero, $iTipoEncuesta, $idModulo, $idTabla, $idRegistro, $objDB, $bDebug = false, $sCopiaA = '')
{
	//
	$sError = '';
	$sDebug = '';
	$aure73codigo = '';
	$sMes = date('Ym');
	$aure73id = 0;
	$bExisteEncuesta = false;
	switch ($iTipoEncuesta) {
		case 1: // Satisfacción
			$sNomTipoEncuesta = 'Satisfacción';
			break;
		case 2: // Deserción
			$sNomTipoEncuesta = 'Deserción';
			break;
		default:
			$sError = 'Tipo de encuenta no reconocida [' . $iTipoEncuesta . '].';
			break;
	}
	if ($sError == '') {
		$sInfoRastro = 'Se genera encuesta de ' . $sNomTipoEncuesta;
		$sIdioma = 'es';
		if (isset($_SESSION['unad_idioma']) != 0) {
			$sIdioma = $_SESSION['unad_idioma'];
		}
		$sRutaBase = __DIR__ . '/';
		require $sRutaBase . 'app.php';
		$sRutaComun = $sRutaBase . $APP->rutacomun;
		$mensajes_17 = $sRutaComun . 'lg/lg_17_' . $sIdioma . '.php';
		if (!file_exists($mensajes_17)) {
			$mensajes_17 = $sRutaComun . 'lg/lg_17_es.php';
		}
		require $mensajes_17;
		$sTabla = 'aure73encuesta' . $sMes;
		$bexiste = $objDB->bexistetabla($sTabla);
		if (!$bexiste) {
			list($sError, $sDebugT) = AUREA_CrearTabla_aure73encuesta($sMes, $objDB, $bDebug);
		} else {
			//list($sError, $sDebugT)=AUREA_RevTabla_aure73encuesta($sMes, $objDB, $bDebug);
		}
		$idEntidad = Traer_Entidad();
		switch ($idEntidad) {
			case 1: // UNAD FLORIDA
				$sNomEntidad = 'UNAD FLORIDA INC';
				$sMailSeguridad = 'aluna@unad.us';
				$sURLCampus = 'http://unad.us/campus/';
				$sURLEncuestas = 'http://unad.us/aurea/';
				break;
			default: // UNAD Colombia
				$sNomEntidad = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
				$sMailSeguridad = 'soporte.campus@unad.edu.co';
				$sURLCampus = 'https://campus0c.unad.edu.co/campus/';
				$sURLEncuestas = 'https://aurea.unad.edu.co/satisfaccion/';
				break;
		}
	}
	if ($sError == '') {
		$sCorreoUsuario = '';
		$iCodigoRastro = 0;
		list($sCorreoUsuario, $sError, $sDebugM, $idGrupoCorreo) = AUREA_CorreoNotifica($idTercero, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugM;
	}
	if ($sError == '') {
		//Iniciar los datos que vamos a precargar.
		$aure73tipointeresado = 6; // Externo
		$aure73idzona = 0;
		$aure73idcentro = 0;
		$aure73idescuela = 0;
		$aure73idprograma = 0;
		$aure73idperiodo = 0;
		$aure73edad = 0;
		$sNomPrograma = '';
		if ($idRegistro != 0) {
			switch ($idModulo) {
				case 2202: // Estudiantes - Encuesta de deserción
					$aure73tipointeresado = 1;
					$sSQL = 'SELECT TB.core01idescuela, TB.core01idprograma, TB.core01idzona, TB.core011idcead, TB.core01peracainicial, 
					TB.core01desc_cont_encuesta, TB.core01desc_id_encuesta, T9.core09nombre, TB.core01desc_fecharesp 
					FROM core01estprograma AS TB, core09programa AS T9 
					WHERE TB.core01id=' . $idRegistro . ' AND TB.core01idprograma=T9.core09id';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$aure73idzona = $fila['core01idzona'];
						$aure73idcentro = $fila['core011idcead'];
						$aure73idescuela = $fila['core01idescuela'];
						$aure73idprograma = $fila['core01idprograma'];
						$aure73idperiodo = $fila['core01peracainicial'];
						$sNomPrograma = $fila['core09nombre'];
						if ($fila['core01desc_fecharesp'] != 0) {
							$sError = 'La encuesta ya se encuentra respondida.';
						}
						if ($fila['core01desc_id_encuesta'] != 0) {
							$bExisteEncuesta = true;
							$sMes = $fila['core01desc_cont_encuesta'];
							$aure73id = $fila['core01desc_id_encuesta'];
							$sTabla = 'aure73encuesta' . $sMes;
							$sSQL = 'SELECT aure73codigo, aure73fechagenera FROM aure73encuesta' . $sMes . ' WHERE aure73id=' . $aure73id . '';
							$tabla = $objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla) > 0) {
								$fila = $objDB->sf($tabla);
								$aure73codigo = $fila['aure73codigo'];
								$aure73fechagenera = $fila['aure73fechagenera'];
								$idImagenCorreo = substr(numeros_validar($aure73codigo), 3, 1);
								if ($idImagenCorreo === '') {
									$idImagenCorreo = 1;
								}
							}
						}
					} else {
						$sError = 'No se ha encontrado el registro de referencia.';
					}
					break;
				case 3018: // Solicitudes telefonicas...
					$sSQL = 'SELECT saiu18idescuela, saiu18idprograma, saiu18idzona, saiu18idcentro, saiu18idperiodo, saiu18tipointeresado 
					FROM saiu18telefonico_' . $idTabla . ' 
					WHERE saiu18id=' . $idRegistro . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$aure73idzona = $fila['saiu18idzona'];
						$aure73idcentro = $fila['saiu18idcentro'];
						$aure73idescuela = $fila['saiu18idescuela'];
						$aure73idprograma = $fila['saiu18idprograma'];
						$aure73idperiodo = $fila['saiu18idperiodo'];
						$aure73tipointeresado = $fila['saiu18tipointeresado'];
					}
					break;
			}
		}
		// Calcular la edad.
		$sSQL = 'SELECT unad11fechanace FROM unad11terceros WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sFechaNace = $fila['unad11fechanace'];
			if (fecha_esvalida($sFechaNace)) {
				list($aure73edad, $iMedida) = fecha_edad($sFechaNace);
				if ($iMedida != 1) {
					$aure73edad = 0;
				}
			}
		}
		//Iniciar los valores por defecto.
		$aure73acepta = -1;
		$aure73t1_p1 = -1;
		$aure73t1_p2 = -1;
		$aure73t1_p3 = -1;
		$aure73t1_p4 = -1;
		$aure73t1_p5 = -1;
		$aure73t2_p1 = -1;
		$aure73t2_comentario = '';
		$aure73fecharespuesta = 0;
	}
	if ($sError == '') {
		$sTablaLogin = 'aure01login' . $sMes;
		list($idSMTP, $sDebugS) = AUREA_SmtpMejorV2($sTablaLogin, $idGrupoCorreo, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugS;
		//Agregar el punto.
		$sProtocolo = 'http';
		switch ($idEntidad) {
			case 1:
				break;
			default:
				if (isset($_SERVER['HTTPS']) != 0) {
					if ($_SERVER['HTTPS'] == 'on') {
						$sProtocolo = 'https';
					}
				}
				break;
		}
		if (!$bExisteEncuesta) {
			$aure73consec = tabla_consecutivo($sTabla, 'aure73consec', 'aure73idtercero=' . $idTercero . '', $objDB);
			$aure73fechagenera = fecha_DiaMod();
			$aure73codigo = md5($aure73fechagenera . $idTercero . $sTabla . $aure73consec);
			$idImagenCorreo = substr(numeros_validar($aure73codigo), 3, 1);
			if ($idImagenCorreo === '') {
				$idImagenCorreo = 1;
			}
			$aure73codigo = substr($aure73codigo, 0, 10);
			$aure73id = tabla_consecutivo($sTabla, 'aure73id', '', $objDB);
			$sCampos273 = 'aure73idtercero, aure73consec, aure73id, aure73tipoencuesta, aure73idmodulo, 
			aure73idtabla, aure73idregistro, aure73fechagenera, aure73tipointeresado, aure73idzona, 
			aure73idcentro, aure73idescuela, aure73idprograma, aure73idperiodo, aure73edad, 
			aure73codigo, aure73acepta, aure73t1_p1, aure73t1_p2, aure73t1_p3, 
			aure73t1_p4, aure73t1_p5, aure73t2_p1, aure73t2_comentario, aure73fecharespuesta';
			$sValores273 = '' . $idTercero . ', ' . $aure73consec . ', ' . $aure73id . ', ' . $iTipoEncuesta . ', ' . $idModulo . ', 
			' . $idTabla . ', ' . $idRegistro . ', ' . $aure73fechagenera . ', ' . $aure73tipointeresado . ', ' . $aure73idzona . ', 
			' . $aure73idcentro . ', ' . $aure73idescuela . ', ' . $aure73idprograma . ', ' . $aure73idperiodo . ', ' . $aure73edad . ', 
			"' . $aure73codigo . '", ' . $aure73acepta . ', ' . $aure73t1_p1 . ', ' . $aure73t1_p2 . ', ' . $aure73t1_p3 . ', 
			' . $aure73t1_p4 . ', ' . $aure73t1_p5 . ', ' . $aure73t2_p1 . ', "' . $aure73t2_comentario . '", ' . $aure73fecharespuesta . '';
			$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $sCampos273 . ') VALUES (' . $sValores273 . ');';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				//Fallo el registro del codigo, hacemos un reintento, pero refrescando el id.
				$aure73id = tabla_consecutivo($sTabla, 'aure73id', '', $objDB);
				$sValores273 = '' . $idTercero . ', ' . $aure73consec . ', ' . $aure73id . ', ' . $iTipoEncuesta . ', ' . $idModulo . ', 
				' . $idTabla . ', ' . $idRegistro . ', ' . $aure73fechagenera . ', ' . $aure73tipointeresado . ', ' . $aure73idzona . ', 
				' . $aure73idcentro . ', ' . $aure73idescuela . ', ' . $aure73idprograma . ', ' . $aure73idperiodo . ', ' . $aure73edad . ', 
				"' . $aure73codigo . '", ' . $aure73acepta . ', ' . $aure73t1_p1 . ', ' . $aure73t1_p2 . ', ' . $aure73t1_p3 . ', 
				' . $aure73t1_p4 . ', ' . $aure73t1_p5 . ', ' . $aure73t2_p1 . ', "' . $aure73t2_comentario . '", ' . $aure73fecharespuesta . '';
				$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $sCampos273 . ') VALUES (' . $sValores273 . ');';
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					//No, esto fallo definitivamente.
					$sError = $ERR['falla_codigo'];
				}
			}
		}
	}
	if ($sError == '') {
		//Ahora que se genero el codigo enviarlo al correo.
		if (!class_exists('clsMail_Unad')) {
			require $APP->rutacomun . 'libmail.php';
		}
		$sInfoRastro = 'Se inicia envio de codigo de encuesta al correo ' . $sCorreoUsuario . '';
		$sTituloCorreo = $ETI['mail_enc_titulo'] . ' ' . $sNomEntidad . '';
		$URL = url_encode('' . $sMes . '|' . $aure73id . '|' . md5($aure73fechagenera));
		$sURL = '' . $URL . '';
		switch ($iTipoEncuesta) {
			case 1: // Satisfaccion
				$sMsg = AUREA_HTML_EncabezadoCorreo($sTituloCorreo);
				$sMsg = $sMsg . AUREA_HTML_CuerpoCorreoEncuesta($aure73codigo, $idImagenCorreo, $sURL, $aure73fechagenera);
				break;
			case 2: // Deserción.

				$sTituloCorreo = $ETI['mail_enc_titulo_2'] . ' ' . $sNomPrograma . ' ' . $ETI['mail_enc_titulo_2b'] . ' ' . $sNomEntidad . '';
				//$sMsg=AUREA_HTML_EncabezadoCorreo($sTituloCorreo);
				$sMsg = AUREA_HTML_EncabezadoCorreo($ETI['mail_enc_titulo_2'] . ' ' . $sNomPrograma . ' ' . $ETI['mail_enc_titulo_2b']);
				$sMsg = $sMsg . AUREA_HTML_CuerpoCorreoDesercion($aure73codigo, $idImagenCorreo, $sMes, $aure73id, $aure73fechagenera, $objDB);
				break;
		}
		$sMsg = $sMsg . AUREA_HTML_PieCorreo();
		//Enviar el mensaje.
		$objMail = new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto = cadena_tildes($sTituloCorreo) . ' ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '';
		$objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario);
		if ($sCopiaA != '') {
			list($bPasa, $sDebugV) = correo_VerificarV2($sCopiaA);
			if ($bPasa) {
				$objMail->addCorreo($sCopiaA, $sCopiaA, 'O');
			}
		}
		if ($sError == '') {
			$objMail->sCuerpo = $sMsg;
			list($sError, $sDebugM) = $objMail->EnviarV2($bDebug);
			$sDebug = $sDebug . $sDebugM;
			if ($sError == '') {
				$idTerceroRastro = $idTercero;
				if (isset($_SESSION['unad_id_tercero']) != 0) {
					if ($_SESSION['unad_id_tercero'] != 0) {
						$idTerceroRastro = $_SESSION['unad_id_tercero'];
					}
				}
				list($bRes, $sDebugR) = seg_rastro(17, $iCodigoRastro, 0, $idTerceroRastro, $sInfoRastro, $objDB, $bDebug, $idTercero);
				$sDebug = $sDebug . $sDebugR;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Se envia correo a ' . $sCorreoUsuario . '<br>';
				}
			}
		}
		if ($sError != '') {
		} else {
		}
		//Termina el envio del codigo...
	}
	return array($aure73codigo, $sError, $sDebug, $sMes, $aure73id);
}
// ------------ FIRMA DE DOCUMENTOS
function AUREA_IniciarFirma($idTercero, $idModulo, $iSubProceso, $idRegistro, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$aure01codigo = '';
	$unad11idgrupocorreo = -1;
	require __DIR__ . '/app.php';
	$sMes = date('Ym');
	$sTabla = 'aure01login' . $sMes;
	$bexiste = $objDB->bexistetabla($sTabla);
	if (!$bexiste) {
		list($sError, $sDebugT) = AUREA_CrearTabla_aure01login($sMes, $objDB, $bDebug);
	} else {
		list($sError, $sDebugT) = AUREA_RevTabla_aure01login($sMes, $objDB, $bDebug);
	}
	$sCorreoUsuario = '';
	$sSubProceso = '';
	switch ($idModulo) {
		case 508:
			$sNomDocumento = 'Solicitud de disponibilidad presupuestal';
			switch($iSubProceso) {
				case 11:
					$sSubProceso = 'Aval oficina solicitante.';
					break;
			}
			break;
		default:
			$sError = 'Proceso no reconocido [' . $idModulo . ']';
			break;
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad11correoinstitucional 
		FROM unad11terceros 
		WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if (correo_VerificarDireccion($fila['unad11correoinstitucional'])) {
				$sCorreoUsuario = $fila['unad11correoinstitucional'];
			}
		}
		if ($sCorreoUsuario == '') {
			$sError = 'No se ha establecido un correo electr&oacute;nico de v&aacute;lido.';
		} else {
			$aCorreo = explode('@', $sCorreoUsuario);
			if (count($aCorreo) > 1) {
				$sDominio = strtolower(trim($aCorreo[1]));
				list($unad11idgrupocorreo, $sDebugG) = AUREA_GrupoDominio($sDominio, $objDB, $bDebug);
			}
		}
	}
	if ($sError == '') {
		list($idSMTP, $sDebugS) = AUREA_SmtpMejorV2($sTabla, $unad11idgrupocorreo, $objDB, $bDebug);
		//Agregar el punto.
		$sProtocolo = 'http';
		$idEntidad = 0;
		if (isset($APP->entidad) != 0) {
			if ($APP->entidad == 1) {
				$idEntidad = 1;
			}
		}
		switch ($idEntidad) {
			case 1:
				break;
			default:
				if (isset($_SERVER['HTTPS']) != 0) {
					if ($_SERVER['HTTPS'] == 'on') {
						$sProtocolo = 'https';
					}
				}
				break;
		}
		$aure01punto = $sProtocolo . '://' . $_SERVER['SERVER_NAME'] . formato_UrlLimpia($_SERVER['REQUEST_URI']);
		$bInserta = true;
		$aure01fecha = fecha_DiaMod();
		$sSQL = 'SELECT aure01id, aure01codigo 
		FROM ' . $sTabla . ' 
		WHERE aure01idtercero=' . $idTercero . ' AND aure01fecha=' . $aure01fecha . ' AND aure01tipo=' . $idModulo . ' 
		AND aure01idorigen=' . $idRegistro . ' AND aure01fechaaplica=-1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bInserta = false;
			$fila = $objDB->sf($tabla);
			$aure01id = $fila['aure01id'];
			$aure01codigo = $fila['aure01codigo'];
		}
		$aure01min = fecha_MinutoMod();
		$aure01ip = sys_traeripreal();
		if ($bInserta) {
			$aure01codigo = md5($aure01fecha . $aure01min . $idTercero . $sTabla);
			$aure01codigo = numeros_validar($aure01codigo);
			$aure01codigo = substr($aure01codigo, 0, 6);
			$aure01consec = tabla_consecutivo($sTabla, 'aure01consec', 'aure01idtercero=' . $idTercero . '', $objDB);
			$aure01id = tabla_consecutivo($sTabla, 'aure01id', '', $objDB);
			$scampos = 'aure01idtercero, aure01consec, aure01id, aure01fecha, aure01min, 
			aure01codigo, aure01fechaaplica, aure01minaplica, aure01ip, aure01punto, 
			aure01idsmtp, aure01tipo, aure01idorigen';
			$svalores = '' . $idTercero . ', ' . $aure01consec . ', ' . $aure01id . ', ' . $aure01fecha . ', ' . $aure01min . ', 
			"' . $aure01codigo . '", -1, 0, "' . $aure01ip . '", "' . $aure01punto . '", 
			' . $idSMTP . ', ' . $idModulo . ', ' . $idRegistro . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $scampos . ') VALUES (' . cadena_codificar($svalores) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $scampos . ') VALUES (' . $svalores . ');';
			}
		} else {
			//actualizar el consecutivo 0
			$sSQL = 'UPDATE ' . $sTabla . ' SET aure01min=' . $aure01min . ', aure01ip="' . $aure01ip . '", aure01punto="' . $aure01punto . '", aure01idsmtp=' . $idSMTP . ' WHERE aure01id=' . $aure01id . '';
		}
		$result = $objDB->ejecutasql($sSQL);
	}
	if ($sError == '') {
		$idEntidad = 0;
		if (isset($APP->entidad) != 0) {
			if ($APP->entidad == 1) {
				$idEntidad = 1;
			}
		}
		switch ($idEntidad) {
			case 1: // UNAD FLORIDA
				$sNomEntidad = 'UNAD FLORIDA INC';
				break;
			default: // UNAD Colombia
				$sNomEntidad = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
				break;
		}
		$sTituloCorreo = 'Firma de documento ' . $sNomDocumento;
		$sMsg = AUREA_HTML_EncabezadoCorreo($sTituloCorreo);
		$sMsg = $sMsg . AUREA_HTML_FirmaDocumento($aure01codigo, $idModulo, $iSubProceso, $idRegistro, $objDB);
		$sMsg = $sMsg . AUREA_HTML_PieCorreo();
		//Enviar el mensaje.
		$objMail = new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto = cadena_codificar($sTituloCorreo . ' ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '');
		$objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario);
		//$objMail->addCorreo('angel.avellaneda@unad.edu.co', 'angel.avellaneda@unad.edu.co');
		if ($sError == '') {
			$objMail->sCuerpo = $sMsg;
			$sError = $objMail->Enviar($bDebug);
		}
		if ($sError != '') {
		} else {
		}
		//Termina el envio del codigo...
	}
	return array($aure01codigo, $sError, $sDebug);
}

function f2202_EnviarEncuestaDesercion($objDB, $bDebug = false, $sCorreoCopia = '') {
	$sError = '';
	$sDebug = '';
	$iEnvios = 0;
	// --- 
	$sIds = '-99';
	$sSQL = 'SELECT TB.core01idtercero
	FROM core01estprograma AS TB, core09programa AS T9 
	WHERE TB.core01idestado IN (2, 3, 9) AND TB.core01desc_id_encuesta=0  AND TB.core01peracainicial>470
	AND TB.core01idprograma=T9.core09id AND T9.core09aplicacontinuidad=1 
	GROUP BY TB.core01idtercero';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)){
		$sIds = $sIds . ',' . $fila['core01idtercero'];
	}
	$sSQL = 'SELECT TB.core01idtercero
	FROM core01estprograma AS TB, core09programa AS T9 
	WHERE TB.core01idtercero IN (' . $sIds . ') AND TB.core01idprograma=T9.core09id AND T9.core09aplicacontinuidad=1
	GROUP BY TB.core01idtercero
	HAVING SUM(1)>1';
	$sIds = '-99';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>ENCUESTAS DESERCION</b>: Alistando tercero con mas de un PEI ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)){
		$sIds = $sIds . ',' . $fila['core01idtercero'];
	}
	if ($sIds != '-99') {
		$sSQL = 'UPDATE core01estprograma SET core01desc_id_encuesta=-1
		WHERE core01idestado IN (2, 3, 9) AND core01desc_id_encuesta=0 
		AND core01idtercero IN (' . $sIds . ')';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>ENCUESTAS DESERCION</b>: Retirando terceros con mas de un PEI ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
	}
	//Ahora los que nos reporten que murieron
	$sSQL = 'UPDATE core01estprograma SET core01desc_id_encuesta=-99
	WHERE core01factordeserta=15 AND core01desc_id_encuesta=0 ';
	$result = $objDB->ejecutasql($sSQL);
	/* 
	2	Inactivo
	3	Sin Matricula Por 2 Años o Más
	9	Retirado -- SE EVITA LOS CASOS DE MUERTE DEL ESTUDIANTE.
	*/
	// Los convenios de generacion E de 2019 y 2020
	// , core51convenioest AS T51  ----- AND TB.core01idtercero=T51.core51idtercero AND T51.core51idconvenio IN (1, 2, 3, 4, 5) AND T51.core51activo="S"
	list($objDBGrados, $sDebug) = TraerDBGrados();
	$sSQL = 'SELECT TB.core01id, TB.core01idtercero, T11.unad11doc 
	FROM core01estprograma AS TB, core09programa AS T9, unad11terceros AS T11
	WHERE TB.core01idestado IN (2, 3, 9) AND TB.core01desc_id_encuesta=0  AND TB.core01peracainicial>470 
	AND TB.core01idprograma=T9.core09id AND T9.core09aplicacontinuidad=1 
	AND TB.core01idtercero=T11.unad11id
	ORDER BY TB.core01peracainicial, T11.unad11doc
	LIMIT 0, 200';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>ENCUESTAS DESERCION</b>: Datos a procesar ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)){
		$bPosibleGraduado = false;
		$sSQL = 'SELECT 1 FROM graduados WHERE documento="' . $fila['unad11doc'] . '"';
		$tablag = $objDBGrados->ejecutasql($sSQL);
		if ($objDBGrados->nf($tablag) > 0) {
			$bPosibleGraduado = true;
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>ENCUESTAS DESERCION</b>: Posible Graduado ' . $fila['unad11doc'] . '<br>';
			}
		}
		if ($bPosibleGraduado) {
			$sSQL = 'UPDATE core01estprograma SET core01desc_id_encuesta=-2 WHERE core01id=' . $fila['core01id'] . '';
			$result = $objDB->ejecutasql($sSQL);
		} else {
			list($aure73codigo, $sError, $sDebugE, $aure73idtabla, $aure73id) = AUREA_IniciarEncuestaPublica($fila['core01idtercero'], 2, 2202, 22, $fila['core01id'], $objDB, false, $sCorreoCopia);
			$sDebug = $sDebug . $sDebugE;
			if ($sError == '') {
				$sSQL = 'UPDATE core01estprograma SET core01desc_cont_encuesta=' . $aure73idtabla . ', core01desc_id_encuesta=' . $aure73id . ' WHERE core01id=' . $fila['core01id'] . '';
				$result = $objDB->ejecutasql($sSQL);
				$iEnvios++;
				if ($iEnvios > 4) {
					$sCorreoCopia = '';
				}
			}
		}
	}
	$objDBGrados->CerrarConexion();
	return array($iEnvios, $sError, $sDebug);
}