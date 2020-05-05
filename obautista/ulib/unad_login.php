<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function login_ActualizarClave($sUsuario, $sNueva, $idTercero, $objDB){
	$sRes='';
	require './app.php';
	$idEntidad=0;
	if (isset($APP->entidad)!=0){
		if ($APP->entidad==1){$idEntidad=1;}
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
	$sHash=password_hash($sNueva, PASSWORD_DEFAULT);
	$sSQL='UPDATE unad11terceros SET unad11clave="'.$sHash.'", unad11fechaclave='.$iDia.', unad11debeactualizarclave=0 WHERE unad11id='.$idTercero.'';
	$result=$objDB->ejecutasql($sSQL);
	//Termina de actualizar unadsys
	$bHayDB=false;
	if ($sRes==''){
		if (isset($APP->dbhostcampus)==0){
			$sRes='Error 351 al intentar iniciar el proceso de login, por favor informar al administrador del sistema.';
			}
		}
	if ($sRes==''){
		switch($idEntidad){
			case 1: // UNAD FLORIDA
			break;
			default: // UNAD Colombia
			break;
			}
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
					}else{
					//Auditar.
					$sDetalle='EL usuario cambia la clave de acceso.';
					seg_auditar(111, $idTercero, 3, $idTercero, $sDetalle, $objDB);
					seg_rastro(111, 0, 0, $idTercero, $sDetalle, $objDB);
					}
				}
			}else{
			$sRes='Error al intentar actualizar la contrase&ntilde;a<br>'.$objDBcampus->serror.' <br>Por favor informa al administrador del sistema.';
			}
		}
	if ($bHayDB){
		$objDBcampus->CerrarConexion();
		}
	return $sRes;
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
function login_sesiones($sHost, $sNavegador, $sIP, $objDB, $bDebug=false){
	$sRes='';
	$iNumSesiones=0;
	$sDebug='';
	$sValida=htmlspecialchars($sNavegador);
	if ($sValida!=$sNavegador){
		//@@@ Enviar una alerta....
		return array($sRes, $iNumSesiones, $sDebug);
		die();
		}
	$sValida=htmlspecialchars($sHost);
	if ($sValida!=$sHost){
		//@@@ Enviar una alerta....
		return array($sRes, $iNumSesiones, $sDebug);
		die();
		}
	$bEntra=true;
	//Quitar los boot del camino.
	$unae25maquina=substr(gethostbyaddr($_SERVER["REMOTE_ADDR"]),0,50);
	$sRobot=login_reconocerRobot($_SERVER['HTTP_USER_AGENT'], $unae25maquina);
	if ($sRobot!=''){$bEntra=false;}
	if (!$bEntra){
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
	if ($bDebug){
		$sDebug=$sSQL;
		}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$sIds11='-99';
		while($fila=$objDB->sf($tabla)){
			$sIds11=$sIds11.','.$fila['unad71idtercero'];
			}
		$sSQL='SELECT T1.unad11usuario FROM unad11terceros AS T1 WHERE T1.unad11id IN ('.$sIds11.')';
		$tabla=$objDB->ejecutasql($sSQL);
		}
	while($fila=$objDB->sf($tabla)){
		if ($sRes!=''){$sRes=$sRes.'|';}
		$sRes=$sRes.$fila['unad11usuario'];
		$iNumSesiones++;
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
function login_validarV2($sDoc, $sUsuario, $spw, $objDB, $bDebug=false){
	require './app.php';
	$idEntidad=0;
	if (isset($APP->entidad)!=0){
		if ($APP->entidad==1){$idEntidad=1;}
		}
	$sRes='';
	$sDebug='';
	$sDoc=numeros_validar($sDoc);
	$sUsuario=htmlspecialchars($sUsuario);
	$bRastroXContrasena=false;
	$idTercero=0;
	/*
	//Aqui no esta dando el documento...., con el documento toca buscar el usuario moodle y luego si validar sobre la db moodle.
	$sSQL='SELECT unad11usuario FROM unad11terceros WHERE unad11doc="'.$sDoc.'"';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sUsuario=$fila['unad11usuario'];
		}else{
		$sRes='Documento no encontrado o contrase&ntilde;a incorrecta..';
		}
	*/
	if ($sRes==''){
		if ($sDoc==''){
			if ($sUsuario==''){
				$sRes='Usuario no v&aacute;lido';
				}
			}
		}
	$bHayDB=false;
	if ($sRes==''){
		if (isset($APP->dbhostcampus)==0){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Hace falta el parametro dbhostcampus en el archivo de configuraci&oacute;n<br>';}
			$sRes='Error 351 al intentar iniciar el proceso de login, por favor informar al administrador del sistema.';
			}
		}
	if ($sRes==''){
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
				if (password_verify($spw, $sHash)){
					$sRes='pasa';
					}else{
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No pasa por HASH<br>';}
					$sRes='Contrase&ntilde;a incorrecta.';
					$bRastroXContrasena=true;
					//Vamos a ver si viene con md5 o pura.
					if (md5($spw)==$fila['password']){
						$sRes='pasa';
						$bRastroXContrasena=false;
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Logeado por MD5<br>';}
						if ($idEntidad==0){
							//Construir el hash...
							$sHash=password_hash($spw, PASSWORD_DEFAULT);
							$sSQL='UPDATE mdl_user SET password="'.$sHash.'" WHERE username="'.$sUsuario.'"';
							$tabla=$objDBcampus->ejecutasql($sSQL);
							}
						}else{
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No pasa por MD5 <!-- '.$fila['password'].' --><br>';}
						if ($idEntidad==0){
							//Consultar si es la clave de RCA..., en dicho caso... armar el hash....
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
									if (password_verify($spw, $fila['unae10hash'])){
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
	if ($sRes=='pasa'){
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
				$iHoy=fecha_DiaMod();
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
			seg_rastroV2(17, 6, 0, 0, $idTercero, '<b>Contraseña errada al intentar iniciar sesión.</b>', $objDB);
			}
		}
	if ($bHayDB){
		$objDBcampus->CerrarConexion();
		}
	return array($sRes, $sDebug);
	}
?>