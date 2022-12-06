<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
if ($bDebug){
	$iSegIni=microtime(true);
	$iSegundos=floor($iSegIni);
	$sMili=floor(($iSegIni-$iSegundos)*1000);
	if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
	$sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
	}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
$bCerrado=false;
$iHTTPS=1;
if (file_exists('./opts.php')){
	require './opts.php';
	if ($OPT->cerrado==1){$bCerrado=true;}
	$iHTTPS=$OPT->https;
	}
if (isset($_SERVER['HTTPS'])==0){$_SERVER['HTTPS']='off';}
if ($iHTTPS==1){
	if ($_SERVER['HTTPS']!='on'){
		$pageURL='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		header('Location:'.$pageURL);
		die();
		}
	}
require './app.php';
require $APP->rutacomun.'unad_sesion2.php';
$bEnSesion=false;
if ((int)$_SESSION['unad_id_tercero']!=0){
	if ($_SESSION['unad_id_tercero']!=0){
		$bEnSesion=true;
		}
	}
if ($bEnSesion){
	$sUrlTablero='tablero.php';
	if (isset($APP->urltablero)!=0){
		if (file_exists($APP->urltablero)){$sUrlTablero=$APP->urltablero;}
		}
	header('Location:'.$sUrlTablero);
	die();
	}
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
require $APP->rutacomun.'unad_login.php';
require $APP->rutacomun.'libdatos.php';
require $APP->rutacomun.'libaurea.php';
$icodmodulo=0;
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_1='lg/lg_1_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1)){$mensajes_1='lg/lg_1_es.php';}
$mensajes_1011=$APP->rutacomun.'lg/lg_1011_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1011)){$mensajes_1011=$APP->rutacomun.'lg/lg_1011_es.php';}
require $mensajes_todas;
require $mensajes_1;
require $mensajes_1011;
$xajax=NULL;
$idEntidad=0;
if (isset($APP->entidad)!=0){
	if ($APP->entidad==1){$idEntidad=1;}
	}
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
if (isset($_SERVER['HTTPS'])==0){$_SERVER['HTTPS']='off';}
if ($bDebug){
	//$bDebug=false;
	//if (!seg_revisa_permiso(17, 1707, $objDB)){$bDebug=false;}
	}
if ($bDebug){
	$sDebug=$sDebug.''.fecha_microtiempo().' Probando conexi&oacute;n con la base de datos <b>'.$APP->dbname.'</b> en <b>'.$APP->dbhost.'</b><br>';
	}
if (!$objDB->Conectar()){
	$sMsgCierre='<br><br>Disculpe las molestias estamos en mantenimiento, <br><br>por favor intente acceder en unos minutos.<br><br>';
	if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objDB->serror.'</b><br>';}
	$bCerrado=true;
	}
if (!$bCerrado){
	//Ver que no sea una ip tranposa en los ultimos meses.
	$iHoy=fecha_DiaMod();
	$iDiaBase=fecha_NumSumarDias($iHoy, -30);
	$sIP=sys_traeripreal();
	//Primero ver que no sea un IP de la UNAD.
	$sSQL='SELECT 1 FROM unad90controlip WHERE unad90ip="'.$sIP.'" AND unad90accion=90';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)==0){
		$sSQL='SELECT 1 FROM unae13enrevision WHERE unad13iporigen="'.$sIP.'" AND unae13idia>='.$iDiaBase.' AND unae13comunes>9 AND unae13estado IN (0,1) LIMIT 0,1';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){$bCerrado=true;}
		}
	if ($bCerrado){
		$sMsgCierre='<br><br>Respetado(a) Usuario, Con el fin de propender por la seguridad de los datos personales, le solicitamos que se dirija el centro UNAD mas cercano y realice usted mismo la Recuperación de contrase&ntilde;a  en de cualquier equipo destinado para Estudiantes, en caso de no poder desplazarse al Centro a realizar la recuperaci&oacute;n deber&aacute; utilizar una conexi&oacute;n a internet diferente al que est&aacute; usando en este momento.<br><br>
Estamos para garantizar su desarrollo Acad&eacute;mico.<br>
Atentamente PTI<br><br>Ref: '.$sIP.' - '.$iDiaBase.'';
		//Esta funcionalidad no es permitida desde ubicaciones que estan bajo supervisi&oacute;n.<br><br>Para reestablecer la contrase&ntilde;a debe hacerlo desde una ubicaci&oacute;n diferente.
		}
	}
function login_AplicarAcceso($id11, $objDB, $id01=0, $bDebug=false){
	//echo 'Ingresando '.$id11.' - '.$idMoodle;
	//Pues, ya paso... que le hacemos....
	$sDebug='';
	require './app.php';
	if (isset($APP->server)==0){$APP->server=0;}
	if ($id01!=0){
		$sTabla='aure01login'.date('Ym');
		$aure01fecha=fecha_DiaMod();
		$aure01min=fecha_MinutoMod();
		$sSQL='UPDATE '.$sTabla.' SET aure01fechaaplica='.$aure01fecha.' , aure01minaplica='.$aure01min.', aure01tipo=1 WHERE aure01id='.$id01.'';
		$result=$objDB->ejecutasql($sSQL);
		}
	//Verificamos si es una ip a la que le tengamos que hacer seguimiento...
		$sDirIP=sys_traeripreal();
		$sSQL='SELECT unad90accion FROM unad90controlip WHERE unad90ip="'.$sDirIP.'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sTabla='aure01login'.date('Ym');
			switch($fila['unad90accion']){
				case 60: //Hacer seguimiento
				$sDoc='{'.$id11.'}';
				$sNombre='{No encontrado}';
				$sSQL='SELECT unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id='.$id11.'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sDoc=$fila['unad11doc'];
					$sNombre=cadena_notildes($fila['unad11razonsocial']);
					}
				list($idSMTP, $sDebugS)=AUREA_SmtpMejor($sTabla, $objDB);
				$sMailSeguridad='seguridad.informacion@unad.edu.co';
				require $APP->rutacomun.'libmail.php';
				$sNomEntidad='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
				$sMsg='<h1>Aviso de inicio de sesion desde  '.$sDirIP.'</h1>
El usuario '.$sDoc.' '.$sNombre.' ha iniciado sesion desde la IP '.$sDirIP.'<br>
<b>Este es un mensaje automatico</b>';
				//Enviar el mensaje.
				$objMail=new clsMail_Unad($objDB);
				$objMail->TraerSMTP($idSMTP);
				$objMail->sAsunto=utf8_encode('Aviso de inicio de sesion desde  '.$sDirIP.' '.fecha_hoy().' '.html_TablaHoraMin(fecha_hora(), fecha_minuto()).'');
				$objMail->addCorreo($sMailSeguridad, $sMailSeguridad);
				$objMail->sCuerpo=$sMsg;
				$sError=$objMail->Enviar();
				break;
				}
			}
	}
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aqu?.
	}
$sError='';
$iTipoError=0;
$iSector=1;
$bMueveScroll=false;
if (isset($_REQUEST['id11'])==0){$_REQUEST['id11']=0;}
if (isset($_REQUEST['paso'])==0){$_REQUEST['paso']=0;}
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['txtuser'])==0){$_REQUEST['txtuser']='';}
if (isset($_REQUEST['btipodoc'])==0){$_REQUEST['btipodoc']='';}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
$bCerrarLaCasa=false;
if ($_REQUEST['txtuser']!=''){
	$sUsuario=cadena_letrasynumeros($_REQUEST['txtuser'], '-');
	if ($sUsuario!=$_REQUEST['txtuser']){
		$bCerrarLaCasa=true;
		}
	}
if ($_REQUEST['id11']!=''){
	$sUsuario=numeros_validar($_REQUEST['id11']);
	if ($sUsuario!=$_REQUEST['id11']){
		$bCerrarLaCasa=true;
		}
	}
if ($bCerrarLaCasa){
	$_REQUEST['txtuser']='';
	$_REQUEST['id11']='';
	$_REQUEST['paso']=0;
	$bCerrado=true;
	$sMsgCierre='<br><br>Se han detectado posibles infracciones de seguridad. Se ha informado al administrador del sistema.<br><br>Por favor intente acceder en unos minutos.<br><br>';
	}
$sCorreoSoporte='soporte.campus@unad.edu.co';
if ($idEntidad==1){$sCorreoSoporte='info@unad.us';}
//No tengo usuario... conseguir el usuario...
//Revisar el usuario
$sMensajeCampus=' por favor comun&iacute;quese con '.$sCorreoSoporte.', no olvide incluir sus datos personales y cu&aacute;l es su vinculo con la universidad, gracias.';
$bConUsuario=false;
//Revisar el documento
if ($_REQUEST['paso']==19){
	//Por defecto dejamos que siga en su solicitud de documento.
	$_REQUEST['paso']=20;
	$sDoc=htmlspecialchars($_REQUEST['txtdoc']);
	if ($sDoc==$_REQUEST['txtdoc']){
		if ($_REQUEST['txtdoc']==''){
			$sError='No ha ingresado un documento.';
			}
		}else{
		$_REQUEST['txtdoc']='';
		$bConUsuario=false;
		$sError='No ha ingresado un documento v&aacute;lido, por favor utilice &uacute;nicamente n&uacute;meros.';
		}
	if ($sError==''){
		//Ver que la ip no este bloqueada.
		$sDirIP=sys_traeripreal();
		$sSQL='SELECT unad90accion FROM unad90controlip WHERE unad90ip="'.$sDirIP.'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			switch($fila['unad90accion']){
				case 99: //Bloqueada.
				$sError='No es posible conectar con el servidor de autenticaci&oacute;n (Error 350),'.$sMensajeCampus;
				$_REQUEST['txtdoc']='';
				$bConUsuario=false;
				break;
				}
			}else{
			list($bRes, $sDebug)=f190_AddIpV2($sDirIP, $objDB);
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad11idmoodle, unad11id, unad11tipodoc, unad11doc, unad11usuario FROM unad11terceros WHERE unad11doc="'.$_REQUEST['txtdoc'].'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			//intentamos importarlo en primera instancia.
			unad11_importar_V2($_REQUEST['txtdoc'], '', $objDB);
			$sSQL='SELECT unad11idmoodle, unad11id, unad11tipodoc, unad11doc, unad11usuario FROM unad11terceros WHERE unad11usuario="'.$_REQUEST['txtdoc'].'"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$sError='Documento no encontrado.<br>Si considera que el documento deber&iacute;a estar registrado,'.$sMensajeCampus;
				$bConUsuario=false;
				}else{
				$fila=$objDB->sf($tabla);
				$_REQUEST['txtuser']=$fila['unad11usuario'];
				$_REQUEST['btipodoc']=$fila['unad11tipodoc'];
				$_REQUEST['bdoc']=$fila['unad11doc'];
				$_REQUEST['id11']=$fila['unad11id'];
				$_REQUEST['paso']=0;
				}
			}else{
			$fila=$objDB->sf($tabla);
			$_REQUEST['txtuser']=$fila['unad11usuario'];
			$_REQUEST['btipodoc']=$fila['unad11tipodoc'];
			$_REQUEST['bdoc']=$fila['unad11doc'];
			$_REQUEST['id11']=$fila['unad11id'];
			$_REQUEST['paso']=0;
			}
		}
	}
//Revisar el usuario
if ($_REQUEST['txtuser']!=''){
	$bConUsuario=true;
	}else{
	if ($_REQUEST['bdoc']!=''){$bConUsuario=true;}
	}
//Verificar el usuario.
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=0;
	$sUsuario=htmlspecialchars($_REQUEST['txtuser']);
	if ($sUsuario==$_REQUEST['txtuser']){
		if ($_REQUEST['txtuser']==''){
			$sError='No ha ingresado un usuario.';
			}
		}else{
		$_REQUEST['txtuser']='';
		$bConUsuario=false;
		$sError='No ha ingresado un usuario.';
		}
	if ($sError==''){
		//Ver que la ip no este bloqueada.
		$sDirIP=sys_traeripreal();
		$sSQL='SELECT unad90accion FROM unad90controlip WHERE unad90ip="'.$sDirIP.'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			switch($fila['unad90accion']){
				case 99: //Bloqueada.
				$sError='No es posible conectar con el servidor de autenticaci&oacute;n (Error 350),'.$sMensajeCampus;
				$_REQUEST['txtuser']='';
				$bConUsuario=false;
				break;
				}
			}else{
			list($bRes, $sDebug)=f190_AddIpV2($sDirIP, $objDB);
			}
		}
	if ($sError==''){
		//@@@ considerar que puede venir un numero de documento en lugar del usuario...
		$sDocFinal=cadena_limpiar($_REQUEST['txtuser'], '01234567890-');
		if ($sDocFinal==$_REQUEST['txtuser']){
			$sSQL='SELECT unad11idmoodle, unad11id FROM unad11terceros WHERE unad11doc="'.$_REQUEST['txtuser'].'"';
			}else{
			$sSQL='SELECT unad11idmoodle, unad11id FROM unad11terceros WHERE unad11usuario="'.$_REQUEST['txtuser'].'"';
			}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			//intentamos importarlo en primera instancia.
			unad11_importar_V2('', $_REQUEST['txtuser'], $objDB);
			$sSQL='SELECT unad11idmoodle, unad11id FROM unad11terceros WHERE unad11usuario="'.$_REQUEST['txtuser'].'"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$sError='Usuario no encontrado.<br>Si considera que su usuario deber&iacute;a estar registrado,'.$sMensajeCampus;
				$bConUsuario=false;
				}else{
				$fila=$objDB->sf($tabla);
				$_REQUEST['id11']=$fila['unad11id'];
				}
			}else{
			$fila=$objDB->sf($tabla);
			$_REQUEST['id11']=$fila['unad11id'];
			}
		}
	}
//Tener listo el correo de confirmacion.
//Enviar el codigo de acceso.
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=2;
	list($aure01codigo, $sError, $sDebug)=AUREA_IniciarLogin($_REQUEST['id11'], $objDB, '', 1, $bDebug);
	if ($sError==''){
		list($sCorreoNotifica, $sErrorM, $sDebugM)=AUREA_CorreoNotifica($_REQUEST['id11'], $objDB, $bDebug);
		seg_rastro(17, 0, 0, $_REQUEST['id11'], 'Se ha enviado un codigo para cambio de clave a '.$sCorreoNotifica, $objDB);
		$sError='Se ha enviado un codigo verificaci&oacute;n a su correo.';
		$iTipoError=2;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Revisar el codigo de verificacion
if ($_REQUEST['paso']==23){
	$_REQUEST['paso']=2;
	if (isset($_REQUEST['txtcodigo'])==0){$_REQUEST['txtcodigo']='';}
	$_REQUEST['txtcodigo']=numeros_validar($_REQUEST['txtcodigo']);
	if ($_REQUEST['txtcodigo']==''){$sError='No ha ingresado el c&oacute;digo de acceso';}
	if ($sError==''){
		$sTabla='aure01login'.date('Ym');
		$aure01fecha=fecha_DiaMod();
		$aure01min=fecha_MinutoMod();
		$sProtocolo='http';
		if (isset($_SERVER['HTTPS'])!=0){
			if ($_SERVER['HTTPS']=='on'){$sProtocolo='https';}
			}
		$aure01punto=$sProtocolo.'://'.$_SERVER['SERVER_NAME'].formato_UrlLimpia($_SERVER['REQUEST_URI']);
		$aure01ip=sys_traeripreal();
		$sSQL='SELECT aure01id, aure01fecha, aure01min, aure01ip, aure01punto FROM '.$sTabla.' WHERE aure01idtercero='.$_REQUEST['id11'].'  AND aure01codigo="'.$_REQUEST['txtcodigo'].'" AND aure01fechaaplica<1';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de codigo '.$sSQL.'<br>';}
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$aure01id=$fila['aure01id'];
			if ($fila['aure01fecha']!=$aure01fecha){
				//Ver si es del dia anterior...
				$iDiaAnterior=fecha_NumSumarDias($aure01fecha, -1);
				if ($fila['aure01fecha']!=$iDiaAnterior){
					$sError='La solicitud de acceso esta vencida, debe volver a generarla.';
					}
				}else{
				/*
				if ($aure01min<($fila['aure01min']+120)){
					}else{
					$sError='La solicitud de acceso esta vencida, debe volver a generarla.. <!-- '.($fila['aure01min']+120).' - '.$aure01min.' -->';
					}
				*/
				}
			if ($sError==''){
				if ($fila['aure01ip']==$aure01ip){
					if ($fila['aure01punto']==$aure01punto){
						$unad11idmoodle=0;
						$sUsuario='';
						$sSQL='SELECT unad11idmoodle, unad11tipodoc, unad11doc, unad11usuario FROM unad11terceros WHERE unad11id='.$_REQUEST['id11'].'';
						$tabla=$objDB->ejecutasql($sSQL);
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se saca el usuario y el id de moodle<br>';}
						if ($objDB->nf($tabla)>0){
							$fila=$objDB->sf($tabla);
							$_REQUEST['paso']=3;
							$_REQUEST['txtuser']=$fila['unad11usuario'];
							$_REQUEST['btipodoc']=$fila['unad11tipodoc'];
							$_REQUEST['bdoc']=$fila['unad11doc'];
							login_AplicarAcceso($_REQUEST['id11'], $objDB, $aure01id, $bDebug);
							}else{
							$sError='No ha sido posible encontrar informaci&oacute;n de inicio de sesion {Ref '.$_REQUEST['id11'].'}';
							}
						}else{
						//Esta tratando de entrar desde otra url....
						$sError='Esta solicitud de acceso no corresponde a este URL, debe volver a generarla.';
						}
					}else{
					//Esta tratando de entrar desde otra ip....
					$sError='Esta solicitud de acceso no corresponde a esta IP, debe volver a generarla.';
					}
				}
			}else{
			$sError='c&oacute;digo incorrecto.';
			}
		//Si hubo error dejar el rastro...
		if ($sError!=''){
			list($res, $sDebugR)=seg_rastro(17, 0, 0, $_REQUEST['id11'], 'Error al validar codigo de confirmacion [Ingreso directo] '.$sError, $objDB);
			$sDebug=$sDebug.$sDebugR;
			}
		}
	}
//El acceso por URL
//Si se recibe la url
if (isset($_GET['u'])!=0){
	//Esta recibiendo una peticion de recuperacion.
	$bPasa=true;
	$sURL=url_decode_simple($_GET['u']);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' '.$sURL.' <br>';}
	$aURL=explode('|', $sURL);
	$sTabla='aure01login'.date('Ym');
	$sSQL='SELECT aure01id, aure01idtercero, aure01fecha, aure01min, aure01codigo, aure01ip, aure01punto 
FROM '.$sTabla.' WHERE aure01id='.$aURL[0].' AND aure01fechaaplica=-1';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' '.$sSQL.' <br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$aure01id=$fila['aure01id'];
		if ($fila['aure01codigo']==$aURL[1]){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' El c&oacute;digo coincide <br>';}
			if ($fila['aure01fecha']!=fecha_DiaMod()){
				$sError='La solicitud de acceso esta vencida, debe volver a generarla.';
				$bPasa=false;
				}else{
				$sProtocolo='http';
				if (isset($_SERVER['HTTPS'])!=0){
					if ($_SERVER['HTTPS']=='on'){$sProtocolo='https';}
					}
				$aure01punto=$sProtocolo.'://'.$_SERVER['SERVER_NAME'].formato_UrlLimpia($_SERVER['REQUEST_URI']);
				$aure01ip=sys_traeripreal();
				$aure01min=fecha_MinutoMod();
				if ($aure01min<($fila['aure01min']+720)){
					$_REQUEST['id11']=$fila['aure01idtercero'];
					$_REQUEST['txtuser']='';
					$sSQL='SELECT unad11idmoodle, unad11tipodoc, unad11doc, unad11usuario FROM unad11terceros WHERE unad11id='.$_REQUEST['id11'].'';
					$tabla=$objDB->ejecutasql($sSQL);
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se saca el usuario y el id de moodle<br>';}
					if ($objDB->nf($tabla)>0){
						$fila=$objDB->sf($tabla);
						$_REQUEST['txtuser']=$fila['unad11usuario'];
						$_REQUEST['btipodoc']=$fila['unad11tipodoc'];
						$_REQUEST['bdoc']=$fila['unad11doc'];
						$_REQUEST['paso']=3;
						login_AplicarAcceso($_REQUEST['id11'], $objDB, $aure01id, $bDebug);
						$bConUsuario=true;
						//Vamos a hacer un redir para evitar la url...
?>
<!DOCTYPE html>
<head></head>
<body>
<script language="javascript">
<!--
function continuar(){
	window.document.frmlogin.submit();
	}
setTimeout('window.document.frmedita.submit()',1);
-->
</script>
<form id="frmedita" name="frmedita" method="post" action="recuperar.php">
<input id="paso" name="paso" type="hidden" value="3" />
<input id="id11" name="id11" type="hidden" value="<?php echo $_REQUEST['id11']; ?>" />
<input id="txtuser" name="txtuser" type="hidden" value="<?php echo $fila['unad11usuario']; ?>" />
<input id="btipodoc" name="btipodoc" type="hidden" value="<?php echo $_REQUEST['btipodoc']; ?>" />
<input id="bdoc" name="bdoc" type="hidden" value="<?php echo $_REQUEST['bdoc']; ?>" />
</form>
<div id="div_bloueados" align="center"></div>
</body>
</html>
<?php
						die();
						}
					}else{
					$sError='La solicitud de acceso esta vencida, debe volver a generarla.';
					}
				}
			}else{
			$sError='La solicitud de acceso no concuerda, es posible que deba volver a generarla.';
			}
		}else{
		$sError='No se ha encontrado la solicitud de acceso, es posible que deba volver a generarla.';
		}
	//Si hubo error dejar el rastro...
	if ($sError!=''){
		seg_rastro(17, 0, 0, $_REQUEST['id11'], 'Error al validar codigo de confirmacion [Acceso URL] '.$sError, $objDB);
		}
	}
$sPermitidos=$ETI['permitidos'];
//Aplicar el cambio real de la clave...
if ($_REQUEST['paso']==24){
	$_REQUEST['paso']=3;
	$sBase=$_REQUEST['txtnuevaclave'];
	$_REQUEST['txtnuevaclave']=htmlspecialchars($_REQUEST['txtnuevaclave']);
	if ($sBase!=$_REQUEST['txtnuevaclave']){
		$sError='El valor ingresado contiene caracteres no permitidos, por favor intente con otra clave.';
		}
	if ($sError==''){
		if ($_REQUEST['txtnuevaclave']!=$_REQUEST['txtnuevaclave2']){
			$sError='La confirmaci&oacute;n no corresponde con la contrase&ntilde;a';
			}
		}
	if ($sError==''){
		$sError=AUREA_ClaveValidaV2($_REQUEST['txtnuevaclave'], $_REQUEST['id11'], $objDB, $sPermitidos);
		}
	if ($sError==''){
		list($sError, $sDebugC)=login_ActualizarClaveV2($_REQUEST['txtuser'], $_REQUEST['txtnuevaclave'], $_REQUEST['id11'], $objDB, $bDebug, 1);
		$sDebug=$sDebug.$sDebugC;
		if ($sError==''){
			$sError='Se ha actualizado exitosamente la contrase&ntilde;a.';
			$iTipoError=1;
			$_REQUEST['paso']=4;
			}
		}
	if ($sError!=''){
		if ($iTipoError!=1){
			seg_rastro(17, 0, 0, $_REQUEST['id11'], 'Error al intentar modificar la clave: '.$sError, $objDB);
			}
		}
	}
//El acceso por URL
//Completar los datos del tercero...
$sDatosUsuario='';
$sDatosEnvio='';
$sCorreoNotifica='';
$bPuedeRecuperar=false;
if ($bConUsuario){
	list($sCorreoNotifica, $sErrorM, $sDebugM)=AUREA_CorreoNotifica($_REQUEST['id11'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugM;
	if ($sCorreoNotifica==''){
		$sError='No existe una direcci&oacute;n de correo electr&oacute;nico v&aacute;lida.';
		}else{
		$bPuedeRecuperar=true;
		}
	}
$modnombre='Recuperar contrase&ntilde;a';
$objDB->CerrarConexion();
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $modnombre);
forma_mitad();
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<script language="javascript">
<!--
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	}
function cambiapagina(){
	window.document.frmedita.submit();
	}
function verboton(idboton,estado){
	document.getElementById(idboton).style.display=estado;
	}
function pordocumento(){
	expandesector(98);
	window.document.frmedita.paso.value=20;
	window.document.frmedita.submit();
	}
function revisauser(){
	var dUsuario=window.document.frmedita.txtuser.value.trim();
	if (dUsuario!=''){
		MensajeAlarmaV2('Verificando usuario...', 2);
		expandesector(98);
		window.document.frmedita.paso.value=21;
		window.document.frmedita.submit();
		}else{
		MensajeAlarmaV2('Necesita ingresar un usuario', 0);
		window.document.frmedita.txtuser.focus();
		}
	}
function revisadoc(){
	var dDoc=window.document.frmedita.txtdoc.value.trim();
	if (dDoc!=''){
		MensajeAlarmaV2('Verificando documento...', 2);
		expandesector(98);
		window.document.frmedita.paso.value=19;
		window.document.frmedita.submit();
		}else{
		MensajeAlarmaV2('Necesita ingresar un documento', 0);
		window.document.frmedita.txtdoc.focus();
		}
	}
function enviarmail(){
	expandesector(98);
	window.document.frmedita.paso.value=22;
	window.document.frmedita.submit();
	}
function revisacodigo(){
	expandesector(98);
	window.document.frmedita.paso.value=23;
	window.document.frmedita.submit();
	}
function cambiaclave(){
	expandesector(98);
	window.document.frmedita.paso.value=24;
	window.document.frmedita.submit();
	}
function cancelar(){
	expandesector(98);
	window.document.frmedita.paso.value=0;
	window.document.frmedita.txtuser.value='';
	window.document.frmedita.btipodoc.value='';
	window.document.frmedita.bdoc.value='';
	window.document.frmedita.submit();
	}
function volver(){
	window.document.frmvolver.submit();
	}
<?php
if ($_REQUEST['paso']==3){
?>
function verocultar(){
	var objC1=document.getElementById('txtnuevaclave');
	var objC2=document.getElementById('txtnuevaclave2');
	if (objC1.type==='password'){
		objC1.type='text';
		objC2.type='text';
		}else{
		objC1.type='password';
		objC2.type='password';
		}
	}
<?php
	}
?>
// -->
</script>
<div id="interna">
<form id="frmvolver" name="frmvolver" method="post" action="./">
</form>
<form id="frmedita" name="frmedita" method="post" action="" class="login" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="id11" name="id11" type="hidden" value="<?php echo $_REQUEST['id11']; ?>" />
<input id="btipodoc" name="btipodoc" type="hidden" value="<?php echo $_REQUEST['btipodoc']; ?>" />
<input id="bdoc" name="bdoc" type="hidden" value="<?php echo $_REQUEST['bdoc']; ?>" />
<div id="div_sector1">
<div class="Cuerpo600">
<h1 class="TituloAzul1">
<?php
echo $ETI['msg_recuperar'];
?>
</h1>
<?php
$sBloque2='';
$bConEnter=false;
if ($bCerrado){
	echo $sMsgCierre;
	}else{

?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
if ($bConUsuario){
	if ($_REQUEST['paso']==0){
		echo $ETI['msg_envio1'].' <b>'.formato_CorreoOculto($sCorreoNotifica).'</b> '.$ETI['msg_envio2'].' ('.$sCorreoSoporte.').';
		/*
		$sBloque2='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
En caso de que no pueda acceder a su correo o que la direcci&oacute;n de correo este errada, deber&aacute; comunicarse con el equipo de soporte t&eacute;cnico (soporte.campus@unad.edu.co).
<div class="salto1px"></div>
</div>';
		*/
		}
	if ($_REQUEST['paso']==2){
		echo $ETI['msg_envio3'].' <b>'.formato_CorreoOculto($sCorreoNotifica).'</b> '.$ETI['msg_envio4'].'';
		}
	if ($_REQUEST['paso']==3){
		echo $ETI['msg_envio5'].' <b>&nbsp;'.$sPermitidos.'</b>&nbsp;)<br> ['.$ETI['msg_caracteres'].'] ';
		}
	}else{
	if ($_REQUEST['paso']==20){
		echo $ETI['msg_infopordocumento'];
		}else{
		$sCorreoSoporte='soporte.campus@unad.edu.co';
		if ($idEntidad==0){
			echo $ETI['msg_inforecuperardoc'];
			}else{
			$sCorreoSoporte='support@unad.us';
			}
		echo $ETI['msg_inforecuperar'].$sCorreoSoporte.$ETI['msg_inforecuperar2'];
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label90">
<?php
if ($_REQUEST['paso']==20){
	echo $ETI['msg_documento'].':';
	}else{
	echo $ETI['msg_usuario'].': ';
	}
?>
</label>
<label>
<?php
if ($bConUsuario){
	$sInfoUsuario=$_REQUEST['bdoc'];
	echo html_oculto('txtuser', $_REQUEST['txtuser'], $sInfoUsuario);
	echo '{<a href="javascript:cancelar()">'.$ETI['msg_cancelar'].'</a>}';
	}else{
	if ($_REQUEST['paso']==20){
?>
<input id="txtdoc" name="txtdoc" type="text" value="<?php echo $_REQUEST['txtuser'] ?>" maxlength="20" autocomplete="off" placeholder="
Documento" autofocus/>
<input id="txtuser" name="txtuser" type="hidden" value="<?php echo $_REQUEST['txtuser'] ?>"/>
<?php
		}else{
?>
<input id="txtuser" name="txtuser" type="text" value="<?php echo $_REQUEST['txtuser'] ?>" maxlength="20" autocomplete="off" placeholder="Usuario" autofocus/>
<?php
		}
	}
?>
</label>
<div class="salto1px"></div>
<?php
$bEntra=false;
if ($_REQUEST['paso']==0){$bEntra=true;}
if ($_REQUEST['paso']==20){$bEntra=true;}
if ($bEntra){
	$bConEnter=true;
	$sAccion='revisauser();';
	$sTituloBoton='Consultar';
	if ($_REQUEST['paso']==20){
		$sAccion='revisadoc();';
		}
	if ($bConUsuario){
		$sAccion='enviarmail();';
		$sTituloBoton='Enviar c&oacute;digo';
		if (!$bPuedeRecuperar){
			$sAccion='cancelar()';
			$sTituloBoton='Volver';
			}
		}
?>
<div class="salto1px"></div>
<label class="Label90">&nbsp;</label>
<label class="Label60">
<input id="cmdIngresa2" name="cmdIngresa2" type="button" value="<?php echo $sTituloBoton; ?>"  onClick="<?php echo $sAccion; ?>" class="BotonAzul160"/>
</label>
<?php
	}
if ($bConUsuario){
?>
<label class="Label60" style="display:none">
<input id="cmdCancela" name="cmdCancela" type="button" value="Cancelar"  onClick="cancelar()" class="BotonAzul"/>
</label>
<?php
	}
	if ($_REQUEST['paso']==2){
?>
<div class="salto5px"></div>
<label class="L">
<?php
echo $ETI['msg_ingresecodigo2'];
?>
<input id="txtcodigo" name="txtcodigo" type="text" value="" maxlength="20" autocomplete="off" class="L" placeholder="<?php echo $ETI['msg_codigoverifica']; ?>" autofocus/>
</label>
<div class="salto1px"></div>
<label class="Label90">&nbsp;</label>
<label>
<input id="cmdIngresa3" name="cmdIngresa3" type="button" value="Entrar"  onClick="revisacodigo();" class="BotonAzul"/>
</label>
<?php
		}
	if ($_REQUEST['paso']==3){
	//Ya paso el codigo de acceso ahora hay que cambiar la clave...
?>
<div class="salto5px"></div>
<label class="Label90">
<?php
echo $ETI['msg_ingclave'];
?>
</label>
<label>
<input id="txtnuevaclave" name="txtnuevaclave" type="password" value="" maxlength="50" autocomplete="new-password" autofocus/>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_confirmar'];
?>
</label>
<label>
<input id="txtnuevaclave2" name="txtnuevaclave2" type="password" value="" maxlength="50" autocomplete="new-password" autofocus/>
</label>
<div class="salto1px"></div>
<label class="Label90"></label>
<label class="Label30">
<input type="checkbox" onClick="verocultar()">
</label>
<label class="Label320">
<?php
echo $ETI['msg_mostrarclave'];
?>
</label>
<div class="salto1px"></div>
<label class="Label90">&nbsp;</label>
<label>
<input id="cmdIngresa4" name="cmdIngresa4" type="button" value="Continuar"  onClick="cambiaclave();" class="BotonAzul"/>
</label>
<?php
		}
	if ($_REQUEST['paso']==4){
?>
<div class="salto5px"></div>
<label class="Label90">&nbsp;</label>
<label>
<input id="cmdIngresa5" name="cmdIngresa5" type="button" value="Volver"  onClick="volver();" class="BotonAzul"/>
</label>
<?php
		}
	//Fin de si no esta cerrado...
	}
echo $sBloque2;
?>
<div class="salto1px"></div>
<div id="mensaje"></div>
<div class="salto1px"></div>
</div>
</div><!-- /DIV_Sector1 -->


<div id="div_sector98" style="display:none">
<?php
echo '<h2>Recuperar contrase&ntilde;a de Campus Virtual</h2>';
?>
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div><!-- /Termina la marquesina -->
</div><!-- /DIV_Sector98 -->
<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value=""/>
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>"/>
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>"/>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
?>
<script language="javascript">
<!--
<?php
if ($iSector!=1){
	echo 'expandesector('.$iSector.');
';
	}
if ($bConEnter){
	echo 'function siguienteobjeto(){'.$sAccion.'};
';
?>
document.onkeydown=function(e){
	if (document.all){
		if (event.keyCode==13){siguienteobjeto();}
		}else{
		if (e.which==13){siguienteobjeto();}
		}
	}
<?php
	}
?>
-->
</script>
<?php
forma_piedepagina();
?>
