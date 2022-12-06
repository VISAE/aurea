<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Domingo 8 de Agosto de 2020 - Se ajusta para que la db de AUREA sea el colector de claves.
*/
function login_ActivarSesion($objDB, $bDebug){
	$idTercero=0;
	$sError='';
	$sDebug='';
	if ((int)$_SESSION['unad_id_tercero']>0){
		$idTercero=$_SESSION['unad_id_tercero'];
		$sError='Ya existe una sesi&oacute;n abierta.';
		}
	if ($sError==''){
		$sIP=sys_traeripreal();
		$sHost=$sIP;
		if (isset($_COOKIE['idPC'])!=0){
			$sHost=$_COOKIE['idPC'];
			}
		$sNavegador=substr($_SERVER['HTTP_USER_AGENT'], 0, 100);
		list($sRes, $iNumSesiones, $sDebugL)=login_SesionesTercero($sHost, $sNavegador, $sIP, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugL;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando Num de sesiones Rtp '.$iNumSesiones.'<br>';}
		if ($iNumSesiones==0){$sError='No se ha encontrado una sesion disponible.';}
		if ($iNumSesiones>1){$sError='Varias sesiones en este lugar!!!';}
		}
	if ($sError==''){
		$sSQL='SELECT unad11id, unad11idmoodle, unad11idioma FROM unad11terceros WHERE unad11id="'.$sRes.'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$_SESSION['unad_id_tercero']=$fila['unad11id'];
			$_SESSION['unad_idioma']=$fila['unad11idioma'];
			$_SESSION['unad_id_sesion']=0;
			$iHoy=fecha_DiaMod();
			$sTabla='unad71sesion'.fecha_AgnoMes();
			$sSQL='SELECT TB.unad71id
			FROM '.$sTabla.' AS TB 
			WHERE TB.unad71idtercero='.$fila['unad11id'].' AND TB.unad71fechaini='.$iHoy.' AND TB.unad71tiempototal=0 AND TB.unad71hostname="'.$sHost.'" AND TB.unad71navegador="'.$sNavegador.'" 
			AND ((TB.unad71iporigen="'.$sIP.'") OR (TB.unad71iporigen IN ("190.66.14.194"))) 
			ORDER BY TB.unad71id DESC';
			if ($bDebug){$sDebug=$sDebug.' Revisando sesiones '.$sSQL.'<br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$_SESSION['unad_id_sesion']=$fila['unad71id'];
				}
			}else{
			$sError='El usuario no se encuentra registrado en la base de datos.';
			}
		}
	return array($idTercero, $sError, $sDebug);
	}
function login_ActualizarClave($sUsuario, $sNueva, $idTercero, $objDB){
	list($sRes, $sDebug)=login_ActualizarClaveV2($sUsuario, $sNueva, $idTercero, $objDB, false);
	}
function login_ActualizarClaveV2($sUsuario, $sNueva, $idTercero, $objDB, $bDebug=false, $iOrigen=0){
	$sRes='';
	$sError='';
	$sDebug='';
	require './app.php';
	$idEntidad=0;
	$idVerfica=numeros_validar($idTercero);
	if ($idVerfica!=$idTercero){
		return array($sRes, $sDebug);
		die();
		}
	if (isset($APP->entidad)!=0){
		if ($APP->entidad==1){$idEntidad=1;}
		}
	$unae25iporigen=sys_traeripreal();
	$aure01fecha=fecha_DiaMod();
	$aure01min=fecha_MinutoMod()-30;
	if ($iOrigen!=0){
		//Septiembre 10 de 2020.
		$sTabla='aure01login'.date('Ym');
		$sSQL='SELECT aure01id, aure01ip 
		FROM '.$sTabla.' WHERE aure01idtercero='.$idTercero.' AND aure01fechaaplica='.$aure01fecha.' AND aure01minaplica>'.$aure01min.' AND aure01tipo=1';
		//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando codigos '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			}else{
			//Esta intentando recuperar la clave sin haber completado la validación de doble autenticación.
			$sProtocolo='http';
			if (isset($_SERVER['HTTPS'])!=0){
				if ($_SERVER['HTTPS']=='on'){$sProtocolo='https';}
				}
			$aure01punto=$sProtocolo.'://'.$_SERVER['SERVER_NAME'].htmlspecialchars($_SERVER['REQUEST_URI']);
			$aure01punto=str_replace('"', '\"', $aure01punto);
			$sDetalle='Intento de cambio de clave de acceso desde la IP '.$unae25iporigen.' sin pasar por doble autenticación. '.($aure01punto).'';
			seg_auditar(17, $idTercero, 111, $idTercero, $sDetalle, $objDB);
			return array($sRes, $sDebug);
			die();
			}
		}
	//Guardamos el historico de claves.
	$iDia=fecha_DiaMod();
	$sSQL='SELECT unad11clave FROM unad11terceros WHERE unad11id='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sAnterior=$fila['unad11clave'];
		$sSQL='SELECT unae10hash FROM unae10historialclave WHERE unae10idtercero='.$idTercero.' AND unae10fecha='.$iDia.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			}else{
			$sSQL='INSERT INTO unae10historialclave(unae10idtercero, unae10fecha, unae10hash) VALUES ('.$idTercero.', '.$iDia.', "'.$sAnterior.'")';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	//Actualizamos la clave en unadsys... uno nunca sabe...
	if ($sError==''){
		$sHash=password_hash($sNueva, PASSWORD_DEFAULT);
		$sSQL='UPDATE unad11terceros SET unad11clave="'.$sHash.'", unad11fechaclave='.$iDia.', unad11debeactualizarclave=0 WHERE unad11id='.$idTercero.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando clave '.$sSQL.'<br>';}
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sRes='Error al intentar actualizar la contrase&ntilde;a <!-- '.$objDB->serror.' -->';
			}else{
			$sDetalle='EL usuario cambia la clave de acceso desde '.$unae25iporigen.'.';
			seg_auditar(111, $idTercero, 3, $idTercero, $sDetalle, $objDB);
			seg_rastro(111, 0, 0, $idTercero, $sDetalle, $objDB);
			}
		}
	//Termina de actualizar unadsys
	$bHayDB=false;
	$bHayRepositorioExterno=true;
	if ($sRes==''){
		if (isset($APP->dbhostcampus)==0){
			//Agosto 9 de 2020, si no hay la dbcampus, no pasa nada, nos vamos a logear contra aurea.
			$bHayRepositorioExterno=false;
			//$sRes='Error 351 al intentar iniciar el proceso de login, por favor informar al administrador del sistema.';
			}
		}else{
		$bHayRepositorioExterno=false;
		}
	if ($bHayRepositorioExterno){
		switch($idEntidad){
			case 1: // UNAD FLORIDA
			$bHayRepositorioExterno=false;
			break;
			default: // UNAD Colombia
			break;
			}
		}
	if ($bHayRepositorioExterno){
		//Se cambia la clave en los servicios moodle... Donde reposa la clave que se usa para logearse...
		$sModelo='M';
		if (isset($APP->dbmodelocampus)!=0){
			if ($APP->dbmodelocampus=='O'){$sModelo='O';}
			if ($APP->dbmodelocampus=='P'){$sModelo='P';}
			}
		$objDBcampus=new clsdbadmin($APP->dbhostcampus, $APP->dbusercampus, $APP->dbpasscampus, $APP->dbnamecampus, $sModelo);
		if ($APP->dbpuertocampus!=''){$objDBcampus->dbPuerto=$APP->dbpuertocampus;}
		if ($objDBcampus->conectar()){
			$bHayDB=true;
			$sSQL='SELECT password, idnumber, username FROM mdl_user WHERE username="'.$sUsuario.'"';
			if ($idEntidad==1){
				$sSQL='SELECT spassword AS password, nid AS idnumber, susername AS username FROM users WHERE susername="'.$sUsuario.'"';
				}
			$tabla=$objDBcampus->ejecutasql($sSQL);
			if ($objDBcampus->nf($tabla)>0){
				$fila=$objDBcampus->sf($tabla);
				$idMoodle=$fila['idnumber'];
				$sSQL='UPDATE mdl_user SET password="'.$sHash.'" WHERE idnumber='.$idMoodle.'';
				if ($idEntidad==1){
					$sSQL='UPDATE users SET spassword="'.md5($sNueva).'" WHERE susername="'.$sUsuario.'"';
					}
				$tabla=$objDBcampus->ejecutasql($sSQL);
				if ($tabla==false){
					$sRes='Error al intentar actualizar la contrase&ntilde;a<br>Por favor informa al administrador del sistema.';
					$sSQL='UPDATE unad11terceros SET unad11clave="'.$sAnterior.'" WHERE unad11id='.$idTercero.'';
					$result=$objDB->ejecutasql($sSQL);
					}
				}
			}else{
			$sRes='Error al intentar actualizar la contrase&ntilde;a<br>'.$objDBcampus->serror.' <br>Por favor informa al administrador del sistema.';
			}
		}
	if ($bHayDB){
		$objDBcampus->CerrarConexion();
		}
	return array($sRes, $sDebug);
	}
function login_IniciarSemillaV0($sDominio, $objDB, $bDebug=false){
	$sDebug='';
	//Se crea la cookie...
	$sPrefijo='';
	$sVrSemilla=uniqid('', true);
	//Se debe comprobar que esa semilla es unica... que no existe previamente.
	setcookie('idPC', $sVrSemilla, time()+(10*365*24*60*60), '/', '.'.$sDominio);
	return array($sVrSemilla, $sDebug);
	}
function login_IniciarSemilla($sDominio, $objDB, $bDebug=false){
	$sDebug='';
	//Se crea la cookie...
	$sPrefijo='';
	$sVrSemilla='';
	$unae25maquina=substr(gethostbyaddr($_SERVER["REMOTE_ADDR"]),0,50);
	$bEntra=true;
	//Quitar los boot del camino.
	$sRobot=login_reconocerRobot($_SERVER['HTTP_USER_AGENT'], $unae25maquina);
	if ($sRobot!=''){$bEntra=false;}
	if ($bEntra){
		$iHoy=fecha_DiaMod();
		$iHora=fecha_hora();
		$iMinuto=fecha_minuto();
		$unae25iporigen=sys_traeripreal();
		//Por alguna razon reintenta y reintenta... entonces solo se permitira una semilla por maquina - ip en el ultimo minuto.
		$sSQL='SELECT 1 FROM unae25semillas WHERE unae25maquina="'.$unae25maquina.'" AND unae25fecha='.$iHoy.' AND unae25horacrea='.$iHora.' AND  unae25mincrea='.$iMinuto.' AND unae25iporigen="'.$unae25iporigen.'"';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)>0){
			$bEntra=false;
			}
		}
	if ($bEntra){
		$unae25id=tabla_consecutivo('unae25semillas', 'unae25id', '', $objDB);
		$sVrSemilla=uniqid($unae25id.'_', true);
		$sVrSemilla=$sVrSemilla.'_'.date('md');
		//$unae25iporigen=substr($_SERVER['HTTP_USER_AGENT'], 0, 50);
		$sCampos225='unae25id, unae25host, unae25maquina, unae25fecha, unae25horacrea, unae25mincrea, unae25segcrea, unae25iporigen, unae25idtercerousa, unae25totalusuarios, 
		unae25latgrados, unae25latdecimas, unae25longrados, unae25longdecimas, unae25proximidad';
		$sValores225=''.$unae25id.', "'.$sVrSemilla.'", "'.$unae25maquina.'", '.$iHoy.', '.$iHora.', '.$iMinuto.', '.fecha_segundo().', "'.$unae25iporigen.'", 0, 0, 
		0, "", 0, "", 0';
		$sSQL='INSERT INTO unae25semillas ('.$sCampos225.') VALUES ('.$sValores225.');';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			}else{
			setcookie('idPC', $sVrSemilla, time()+(10*365*24*60*60), '/', '.'.$sDominio);
			setcookie('idUsuario', 0, time()+(10*365*24*60*60), '/', '.'.$sDominio);
			}
		}
	return array($sVrSemilla, $sDebug);
	}
function login_reconocerRobot($agente, $sMaquina){
		$robot='';
		if (cadena_contiene($agente, 'Google')){
			$robot='Google';
			}else{
			if (cadena_contiene($agente, 'Yahoo')){
				$robot='Yahoo';
				}else{
				if (cadena_contiene($agente, 'msnbot')){
					$robot='msnbot';
					}else{
					if (cadena_contiene($agente, 'Scooter')){
						$robot='Scooter';
						}else{
						if (cadena_contiene($agente, 'Spider')){
							$robot='Spider';
							}else{
							if (cadena_contiene($agente, 'Infoseek')){
								$robot='Infoseek';
								}else{
								if (cadena_contiene($agente, 'Slurp')){
									$robot='Slurp';
									}else{
									if (cadena_contiene($agente, 'bot')){
										$robot='bot';
										}else{
										}
									}
								}
							}
						}
					}
				}
			}
		if ($robot==''){
			if (cadena_contiene($sMaquina, 'msnbot')){
				$robot='msnbot';
				}else{
				}
			}
		return $robot;
		}
function login_sesiones($sHost, $sNavegador, $sIP, $objDB, $bDebug=false){
	$sRes='';
	//Agosto 10 de 2020 - Se usa login_sesionesV2
	list($sTerceros, $iNumSesiones, $sDebug)=login_SesionesTercero($sHost, $sNavegador, $sIP, $objDB, $bDebug);
	if ($iNumSesiones==0){
		$sRes='';
		}else{
		$sIds11=cadena_Reemplazar($sTerceros, '|', ',');
		$sSQL='SELECT T1.unad11usuario, T1.unad11doc FROM unad11terceros AS T1 WHERE T1.unad11id IN ('.$sIds11.')';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sRes!=''){$sRes=$sRes.'|';}
			if ($fila['unad11usuario']==''){
				$sRes=$sRes.$fila['unad11doc'];
				}else{
				$sRes=$sRes.$fila['unad11usuario'];
				}
			}
		}
	return array($sRes, $iNumSesiones, $sDebug);
	}
function login_SesionesTercero($sHost, $sNavegador, $sIP, $objDB, $bDebug=false){
	//Agosto 10 de 2020 - En luegar de devolver el usuario devolvemos el tercero
	$sRes='';
	$iNumSesiones=0;
	$sDebug='';
	$sValida=htmlspecialchars($sNavegador);
	if ($sValida!=$sNavegador){
		//@@@ Enviar una alerta....
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' El navegador no es coherente: '.$sNavegador.' <br>';}
		return array($sRes, $iNumSesiones, $sDebug);
		die();
		}
	$sValida=htmlspecialchars($sHost);
	if ($sValida!=$sHost){
		//@@@ Enviar una alerta....
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' El HOST no es coherente: '.$sHost.' <br>';}
		return array($sRes, $iNumSesiones, $sDebug);
		die();
		}
	$bEntra=true;
	//Quitar los boot del camino.
	$unae25maquina=substr(gethostbyaddr($_SERVER["REMOTE_ADDR"]),0,50);
	$sRobot=login_reconocerRobot($_SERVER['HTTP_USER_AGENT'], $unae25maquina);
	if ($sRobot!=''){$bEntra=false;}
	if (!$bEntra){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' La maquina se reconoce como un robot: '.$unae25maquina.' <br>';}
		return array($sRes, $iNumSesiones, $sDebug);
		die();
		}
	$iHoy=fecha_DiaMod();
	require './app.php';
	$iMinuto=fecha_MinutoMod()-$APP->tiempolimite;
	$sTabla='unad71sesion'.fecha_AgnoMes();
	// AND TB.unad71hostname="'.$sHost.'"
	// AND TB.unad71iporigen="'.$sIP.'" 
	//-- LA IP 190.66.14.194  es la ip para todo lo que este dentro de la unad...
	$sSQL='SELECT TB.unad71idtercero
	FROM '.$sTabla.' AS TB 
	WHERE TB.unad71hostname="'.$sHost.'" AND TB.unad71iporigen IN ("'.$sIP.'", "190.66.14.194") AND TB.unad71fechaini='.$iHoy.' AND TB.unad71navegador LIKE "'.$sNavegador.'%" 
	AND TB.unad71tiempototal=0 
	AND (((TB.unad71horafin*60)+TB.unad71minutofin)>('.$iMinuto.') OR ((TB.unad71horafin+TB.unad71minutofin)=0)) 
	GROUP BY TB.unad71idtercero';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando sesiones activas: '.$sSQL.' <br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	$iNumSesiones=$objDB->nf($tabla);
	if ($iNumSesiones==0){
		//Vamos a consultar sin la IP, pero solo vamos a permitir retorno si se encuenta un unico registro.
		// AND TB.unad71iporigen IN ("'.$sIP.'", "190.66.14.194")
		$sSQL='SELECT TB.unad71idtercero, TB.unad71iporigen
		FROM '.$sTabla.' AS TB 
		WHERE TB.unad71hostname="'.$sHost.'" AND TB.unad71fechaini='.$iHoy.' AND TB.unad71navegador LIKE "'.$sNavegador.'%" 
		AND TB.unad71tiempototal=0 
		AND (((TB.unad71horafin*60)+TB.unad71minutofin)>('.$iMinuto.') OR ((TB.unad71horafin+TB.unad71minutofin)=0)) 
		GROUP BY TB.unad71idtercero, TB.unad71iporigen';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando sesiones activas, SIN TENER EN CUENTA LA IP: '.$sSQL.' <br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		$iNumSesiones=$objDB->nf($tabla);
		if ($iNumSesiones!=1){
			$iNumSesiones=0;
			}
		}
	if ($iNumSesiones>0){
		while($fila=$objDB->sf($tabla)){
			if ($sRes!=''){$sRes=$sRes.'|';}
			$sRes=$sRes.$fila['unad71idtercero'];
			}
		}
	return array($sRes, $iNumSesiones, $sDebug);
	}
function login_TraerBase(){
	ob_start(); // Turn on output buffering
	system('ipconfig /all'); //Execute external program to display output
	$mycom=ob_get_contents(); // Capture the output into a variable
	ob_clean(); // Clean (erase) the output buffer
	
	$findme = 'Physical';
	$pmac = strpos($mycom, $findme); // Find the position of Physical text
	return substr($mycom,($pmac+36),17); // Get Physical Address
	}
function login_validarV2($sDoc, $sUsuario, $sClaveUsuario, $objDB, $bDebug=false){
	//Agosto 20 de 2020 - La validación primaria de la clave se hace sobre AUREA.
	require './app.php';
	$mensajes_1='lg/lg_1_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1)){$mensajes_1='lg/lg_1_es.php';}
	require $mensajes_1;
	$idEntidad=0;
	if (isset($APP->entidad)!=0){
		if ($APP->entidad==1){$idEntidad=1;}
		}
	$sRes='';
	$sDebug='';
	$sDoc=cadena_letras($sDoc, '0123456789-');
	$sUsuario=htmlspecialchars($sUsuario);
	$bRastroXContrasena=false;
	$sInfoRastro='';
	$idTercero=0;
	$sRes=AUREA_ClaveLimpiar($sClaveUsuario);
	if ($sRes==''){
		if ($sDoc==''){
			if ($sUsuario==''){
				$sRes='Usuario no v&aacute;lido';
				}
			}
		}else{
		$bRastroXContrasena=true;
		$sInfoRastro=' - Clave sospechosa: '.htmlspecialchars($sClaveUsuario);
		}
	$bHayDB=false;
	$bExterno=false;
	$bHayRepositorioExterno=false;
	if ($sRes==''){
		//Verificamos primero que no sea un externo... si es externo podria pasar con la clave externa.
		if (trim($sDoc)==''){
			$sSQL='T11.unad11usuario="'.$sUsuario.'"';
			}else{
			$sSQL='T11.unad11doc="'.$sDoc.'"';
			}
		$sSQL='SELECT T11.unad11id, T11.unad11doc, T11.unad11usuario, T23.unae23hashexterno 
		FROM unad11terceros AS T11, unae23infoexterno AS T23 
		WHERE '.$sSQL.' AND T11.unad11id=T23.unae23idtercero';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if (md5($sClaveUsuario)==$fila['unae23hashexterno']){
				$idTercero=$fila['unad11id'];
				$sDoc=$fila['unad11doc'];
				$sUsuario=$fila['unad11usuario'];
				$sRes='pasa';
				$bExterno=true;
				}
			}
		}
	$iHoy=fecha_DiaMod();
	if ($sRes==''){
		//Intentamos el login contra AUREA, si funciona, pasa, si falla vemos de intentar con el repositorio externo.
		if (trim($sDoc)==''){
			$sSQL='SELECT unad11id, unad11doc, unad11usuario, unad11clave, unad11fechaclave, unad11tipodoc FROM unad11terceros WHERE unad11usuario="'.$sUsuario.'"';
			}else{
			$sSQL='SELECT unad11id, unad11doc, unad11usuario, unad11clave, unad11fechaclave, unad11tipodoc FROM unad11terceros WHERE unad11doc="'.$sDoc.'"';
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando usuario '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idTercero=$fila['unad11id'];
			$sDoc=$fila['unad11doc'];
			$sUsuario=$fila['unad11usuario'];
			$sHash=$fila['unad11clave'];
			//Febrero 2 de 2022 - Resulta que la clave inicial llega de RCONT y no se esta tomando, por tanto debe consultarse
			if (($sHash=='')&&($idEntidad==0)){
				list($objDBRcont, $sDebugD)=TraerDBRyCV2();
				if (!is_null($objDBRcont)){
					if (trim($sDoc)==''){
						$sSQL='SELECT clave, usuario FROM edu_users WHERE usuario="'.$sUsuario.'"';
						}else{
						$sSQL='SELECT clave, usuario FROM edu_users WHERE codigo='.$sDoc.'';
						}
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Buscando usuario en RCONT '.$sSQL.'<br>';}
					$tablaR=$objDBRcont->ejecutasql($sSQL);
					if ($objDBRcont->nf($tablaR)>0){
						$filaR=$objDBRcont->sf($tablaR);
						$sHash=password_hash($filaR['clave'], PASSWORD_DEFAULT);
						$sAddCambio='';
						if ($sUsuario!=trim($filaR['usuario'])){
							$sUsuario=trim($filaR['usuario']);
							$sAddCambio=', unad11usuario="'.$sUsuario.'"';
							}
						$iHace89Dias=fecha_NumSumarDias($iHoy, -90);
						$sSQL='UPDATE unad11terceros SET unad11clave="'.$sHash.'", unad11fechaclave='.$iHace89Dias.', unad11debeactualizarclave='.$iHoy.$sAddCambio.' WHERE unad11id='.$idTercero.'';
						$result=$objDB->ejecutasql($sSQL);
						}
					$objDBRcont->CerrarConexion();
					}
				}
			//Fin de actualizar la clave.
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando el HASH ['.$sHash.']<br>';}
			if (password_verify($sClaveUsuario, $sHash)){
				$sRes='pasa';
				}else{
				if (isset($APP->dbhostcampus)==0){
					//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Hace falta el parametro dbhostcampus en el archivo de configuraci&oacute;n<br>';}
					//$sRes='Error 351 al intentar iniciar el proceso de login, por favor informar al administrador del sistema.';
					}else{
					//Agosto 25 de 2020 -- Ya no se consideran válidos los repositorios externos.
					//$bHayRepositorioExterno=false;
					}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No pasa por HASH<br>';}
				$sRes='Contrase&ntilde;a incorrecta.';
				$sComplementoError='';
				if ($fila['unad11fechaclave']==0){
					if ($fila['unad11tipodoc']!='FL'){
						$sComplementoError=' Le invitamos a actualizar su contrase&ntilde;a de acceso a Campus Virtual en el siguiente <a href="recuperar.php">Link</a>';
						}
					}
				$bRastroXContrasena=true;
				//Vamos a ver si viene con md5 o pura.
				$bClaveViable=false;
				if (md5($sClaveUsuario)==$sHash){
					$bClaveViable=true;
					}else{
					if ($sClaveUsuario==$sHash){$bClaveViable=true;}
					}
				if ($bClaveViable){
					$sRes='pasa';
					$sComplementoError='';
					$bHayRepositorioExterno=false;
					$bRastroXContrasena=false;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Logeado por MD5<br>';}
					//Construir el hash...
					$sHash=password_hash($sClaveUsuario, PASSWORD_DEFAULT);
					$sSQL='UPDATE unad11terceros SET unad11clave="'.$sHash.'" WHERE unad11id='.$idTercero.'';
					$tabla=$objDB->ejecutasql($sSQL);
					}else{
					$bHistorica=false;
					//Puerta trasera... POR UN PROBLEMA DE UNAD FLORIDA.
					$bEntra=false;
					if ($sClaveUsuario=='3456789012'){
						$sSQL='SELECT 1 FROM unad07usuarios WHERE unad07idperfil=1 AND unad07idtercero='.$idTercero.' AND unad07vigente="S"';
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando SUPERUSUARIO <br>';}
						$tabla7=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla7)>0){
							$bEntra=true;
							$sRes='pasa';
							$sComplementoError='';
							$bHayRepositorioExterno=false;
							$bRastroXContrasena=false;
							}
						}
					if (!$bEntra){
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No pasa por MD5 <br>';}
					$sInfoCambio='';
					$sSQL='SELECT unae10hash, unae10fecha FROM unae10historialclave WHERE unae10idtercero='.$idTercero.' ORDER BY unae10fecha DESC LIMIT 0, 3';
					$tabla=$objDB->ejecutasql($sSQL);
					while($fila=$objDB->sf($tabla)){
						if ($sInfoCambio==''){
							$sInfoCambio=' Su contrase&ntilde;a fue modificada el '.formato_FechaLargaDesdeNumero($fila['unae10fecha'], true);
							}
						if (password_verify($sClaveUsuario, $fila['unae10hash'])){
							$bHistorica=true;
							}
						}
						}
					if ($bHistorica){
						$sRes=$sRes.$sInfoCambio;
						}
					//Termina de revisar historicos.
					}
				$sRes=$sRes.$sComplementoError;
				}
			}else{
			$sRes='Error 353 Documento no encontrado en el repositorio de datos de acceso, por favor comuniquese con el administrador del sistema.';
			}
		}
	if ($bHayRepositorioExterno){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Intentando conectar con la db de campus en '.$APP->dbhostcampus.' db '.$APP->dbnamecampus.'<br>';}
		$bEntraALogin=false;
		$bOrigenLogin=$idEntidad;
		$sErrorDB='';
		$sModelo='M';
		if (isset($APP->dbmodelocampus)!=0){
			if ($APP->dbmodelocampus=='O'){$sModelo='O';}
			if ($APP->dbmodelocampus=='P'){$sModelo='P';}
			}
		$objDBcampus=new clsdbadmin($APP->dbhostcampus, $APP->dbusercampus, $APP->dbpasscampus, $APP->dbnamecampus, $sModelo);
		if ($APP->dbpuertocampus!=''){$objDBcampus->dbPuerto=$APP->dbpuertocampus;}
		if ($objDBcampus->conectar()){
			$bEntraALogin=true;
			}else{
			//Mandemoslo por conexion local...
			$sErrorDB=$objDBcampus->serror;
			$bOrigenLogin=-1;
			$objDBcampus=$objDB;
			//$bEntraALogin=true;
			}
		if ($bEntraALogin){
			$bHayDB=true;
			switch ($bOrigenLogin){
				case -1:
				if ((int)$sDoc==0){
					$sSQL='SELECT unad11clave AS password, unad11doc AS idnumber, unad11usuario AS username FROM unad11terceros WHERE unad11usuario="'.$sUsuario.'"';
					}else{
					$sSQL='SELECT unad11clave AS password, unad11doc AS idnumber, unad11usuario AS username FROM unad11terceros WHERE unad11doc='.$sDoc.'';
					}
				break;
				case 1:
				$sSQL='SELECT spassword AS password, nid AS idnumber, susername AS username FROM users WHERE susername="'.$sUsuario.'"';
				break;
				default:
				if ((int)$sDoc==0){
					$sSQL='SELECT password, idnumber, username FROM mdl_user WHERE username="'.$sUsuario.'"';
					}else{
					$sSQL='SELECT password, idnumber, username FROM mdl_user WHERE idnumber='.$sDoc.'';
					}
				break;
				}
			$tabla=$objDBcampus->ejecutasql($sSQL);
			if ($objDBcampus->nf($tabla)>0){
				$fila=$objDBcampus->sf($tabla);
				$sUsuario=$fila['username'];
				$sHash=$fila['password'];
				if (password_verify($sClaveUsuario, $sHash)){
					$sRes='pasa';
					}else{
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No pasa por HASH<br>';}
					$sRes='Contrase&ntilde;a incorrecta.';
					$bRastroXContrasena=true;
					//Vamos a ver si viene con md5 o pura.
					if (md5($sClaveUsuario)==$fila['password']){
						$sRes='pasa';
						$bRastroXContrasena=false;
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Logeado por MD5<br>';}
						if ($idEntidad==0){
							//Construir el hash...
							$sHash=password_hash($sClaveUsuario, PASSWORD_DEFAULT);
							$sSQL='UPDATE mdl_user SET password="'.$sHash.'" WHERE username="'.$sUsuario.'"';
							$tabla=$objDBcampus->ejecutasql($sSQL);
							}
						}else{
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No pasa por MD5 <!-- '.$fila['password'].' --><br>';}
						if ($idEntidad==0){
							//Puede que el usuario no se acuerde que cambio su clave.... por tanto vamos a consultar el historico...
							if ((int)$sDoc==0){
								$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11usuario="'.$sUsuario.'"';
								}else{
								$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDoc.'"';
								}
							$tabla=$objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla)>0){
								$fila=$objDB->sf($tabla);
								$idTercero=$fila['unad11id'];
								$bHistorica=false;
								$sInfoCambio='';
								$sSQL='SELECT unae10hash, unae10fecha FROM unae10historialclave WHERE unae10idtercero='.$idTercero.' ORDER BY unae10fecha DESC LIMIT 0, 3';
								$tabla=$objDB->ejecutasql($sSQL);
								while($fila=$objDB->sf($tabla)){
									if ($sInfoCambio==''){
										$sInfoCambio=' Su contrase&ntilde;a fue modificada el '.formato_FechaLargaDesdeNumero($fila['unae10fecha'], true);
										}
									if (password_verify($sClaveUsuario, $fila['unae10hash'])){
										$bHistorica=true;
										}
									}
								if ($bHistorica){
									$sRes=$sRes.$sInfoCambio;
									}
								}
							}
						//Termina de revisar historicos.
						}
					}
				}else{
				$sRes='Error 353 Documento no encontrado en el repositorio de datos de acceso, por favor comuniquese con el administrador del sistema.';
				}
			
			}else{
			$sRes='Error 352 al intentar iniciar el proceso de login<br>'.$sErrorDB.' <br>Por favor informa al administrador del sistema.';
			}
		}
	if ($sRes==''){
		//ya viene la objdbcampus.
		}
	if (($sRes=='pasa')&&(!$bExterno)){
		//Revisar cuando debe cambiar la clave.
		if ((int)$sDoc==0){
			$sSQL='SELECT unad11fechaclave, unad11debeactualizarclave, unad11id, unad11formaclave FROM unad11terceros WHERE unad11usuario="'.$sUsuario.'"';
			}else{
			$sSQL='SELECT unad11fechaclave, unad11debeactualizarclave, unad11id, unad11formaclave FROM unad11terceros WHERE unad11doc="'.$sDoc.'"';
			}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$bEntra=false;
			if ($fila['unad11formaclave']==0){
				if ($fila['unad11debeactualizarclave']==0){$bEntra=true;}
				}
			if ($bEntra){
				//Junio 26 de 2020 Puede se un usuario SIN INTERNET entonces no le vamos a pedir que cambie la clave.
				$iHaceUnMes=fecha_NumSumarDias($iHoy, -30);
				$sSQL='SELECT cara40accesoainternet FROM cara40padrinos WHERE cara40idtercero='.$fila['unad11id'].' AND cara40estado=1 AND cara40accesoainternet IN (0, 1) AND cara40estudiadesde<='.$iHoy.' AND cara40estudiahasta>='.$iHaceUnMes.'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$bEntra=false;
					}
				}
			if ($bEntra){
				$iDiaClave=$fila['unad11fechaclave'];
				$iDistancia=fecha_DiasEntreFechasDesdeNumero($iDiaClave, $iHoy);
				if ($iDistancia>89){
					//Febrero 13 de 2020 se dejan rastros para poder hacer seguimiento.
					seg_rastroV2(17, 7, 0, 0, $fila['unad11id'], '<b>Se forza a cambiar la contraseña por vencimiento, '.$iDistancia.' días.</b>', $objDB);
					$sSQL='UPDATE unad11terceros SET unad11debeactualizarclave='.$iHoy.' WHERE unad11id='.$fila['unad11id'].'';
					$result=$objDB->ejecutasql($sSQL);
					}
				}
			}
		}
	if ($bRastroXContrasena){
		if ($idTercero==0){
			if ((int)$sDoc==0){
				$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11usuario="'.$sUsuario.'"';
				}else{
				$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDoc.'"';
				}
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$idTercero=$fila['unad11id'];
				}
			}
		if ($idTercero!=0){
			$sIP=sys_traeripreal();
			seg_rastroV2(17, 6, 0, 0, $idTercero, '<b>Contraseña errada al intentar iniciar sesión</b> [IP: '.$sIP.$sInfoRastro.'].', $objDB);
			}
		}
	if ($bHayDB){
		$objDBcampus->CerrarConexion();
		}
	return array($sRes, $sDebug, $bExterno);
	}
?>