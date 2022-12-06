<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.0 martes, 21 de enero de 2020
 */
/** Archivo index.php.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date miércoles, 22 de enero de 2020
 */
if (file_exists('./err_control.php')) {require './err_control.php';}
$bDebug = false;
$sDebug = '';
if (isset($_REQUEST['debug']) != 0) {
	//El debug se apaga en esta pagina, solo se usa en momentos especificios.
	//if ($_REQUEST['debug']==1){$bDebug=true;}
} else {
	$_REQUEST['debug'] = 0;
}
if ($bDebug) {
	$iSegIni = microtime(true);
	$iSegundos = floor($iSegIni);
	$sMili = floor(($iSegIni - $iSegundos) * 1000);
	if ($sMili < 100) {if ($sMili < 10) {$sMili = ':00' . $sMili;} else { $sMili = ':0' . $sMili;}} else { $sMili = ':' . $sMili;}
	$sDebug = $sDebug . '' . date('H:i:s') . $sMili . ' Inicia pagina <br>';
}
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
}
if ($bDebug) {
	//if (!file_exists('./config.php')){echo 'No existe archivo config.php';die();}
	if (!file_exists('./app.php')) {echo 'No existe archivo app.php';die();}
	require './app.php';
	if (!file_exists($APP->rutacomun . 'unad_todas.php')) {echo 'No existe archivo ' . $APP->rutacomun . 'unad_todas.php';die();}
	if (!file_exists($APP->rutacomun . 'libs/clsdbadmin.php')) {echo 'No existe archivo ' . $APP->rutacomun . 'libs/clsdbadmin.php';die();}
	if (!file_exists($APP->rutacomun . 'unad_librerias.php')) {echo 'No existe archivo ' . $APP->rutacomun . 'unad_librerias.php';die();}
	if (!file_exists($APP->rutacomun . 'libhtml.php')) {echo 'No existe archivo ' . $APP->rutacomun . 'libhtml.php';die();}
	if (!file_exists($APP->rutacomun . 'libaurea.php')) {echo 'No existe archivo ' . $APP->rutacomun . 'libaurea.php';die();}
	if (!file_exists($APP->rutacomun . 'unad_xajax.php')) {echo 'No existe archivo ' . $APP->rutacomun . 'unad_xajax.php';die();}
	if (!file_exists($APP->rutacomun . 'unad_login.php')) {echo 'No existe archivo ' . $APP->rutacomun . 'unad_login.php';die();}
	if (!file_exists($APP->rutacomun . 'unad_forma_v2.php')) {echo 'No existe archivo ' . $APP->rutacomun . 'unad_forma_v2.php';die();}
}
mb_internal_encoding('UTF-8');
$bCerrado = false;
$iHTTPS = 1;
//, si lo desea, por favor ingrese a <a href="http://campus.unad.edu.co">http://campus.unad.edu.co</a>
$sMsgCierre = 'Ofrecemos disculpas, en este momento nos encontramos en mantenimiento.';
if (file_exists('./opts.php')) {
	require './opts.php';
	if ($OPT->cerrado == 1) {
		$bCerrado = true;
	}
	$iHTTPS = $OPT->https;
}
if (isset($_SERVER['HTTPS']) == 0) {$_SERVER['HTTPS'] = 'off';}
if ($iHTTPS == 1) {
	$bCambiarProtocolo = false;
	if ($_SERVER['HTTPS'] != 'on') {$bCambiarProtocolo = true;}
	if (isset($_SERVER['HTTP_X_FORWARDED_PORT']) != 0) {
		if ($_SERVER['HTTP_X_FORWARDED_PORT'] == 80) {$bCambiarProtocolo = true;}
	}
	if ($bCambiarProtocolo) {
		$pageURL = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		header('Location:' . $pageURL);
		die();
	} else {
		if ($bDebug) {$sDebug = $sDebug . ' No requiere cambio de puerto. [' . $_SERVER['HTTPS'] . ']<br>';}
	}
} else {
	if ($bDebug) {$sDebug = $sDebug . ' VARIABLE DE ENTORNO HTTPS. [' . $iHTTPS . ']<br>';}
}
require './app.php';
if (isset($APP->server) == 0) {$APP->server = 0;}
require $APP->rutacomun . 'unad_sesion2.php';
$bEnSesion = false;
if ((int) $_SESSION['unad_id_tercero'] > 0) {
	$bEnSesion = true;
	//if ($bDebug){$bEnSesion=false;}
}
if ($bEnSesion) {
	if ($bDebug) {
		require $APP->rutacomun . 'unad_librerias.php';
		$sDebug = $sDebug . fecha_microtiempo() . ' Saltar a accesit de entrada. [' . $_SESSION['unad_id_tercero'] . ' - ' . $_SESSION['unad_id_sesion'] . ']<br>';
		$iSegFin = microtime(true);
		$iSegundos = $iSegFin - $iSegIni;
		echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '<div class="salto1px"></div></div>';
		die();
	}
	header('Location:accesit.php');
	die();
}
if (isset($_REQUEST['paso']) == 0) {$_REQUEST['paso'] = 0;}
if ($_REQUEST['paso'] == 26) {
	$_REQUEST['paso'] = 0;
	if (isset($_REQUEST['idioma']) == 0) {$_REQUEST['idioma'] = '';}
	switch ($_REQUEST['idioma']) {
	case 'es':
	case 'en':
	case 'pt':
		$_SESSION['unad_idioma'] = $_REQUEST['idioma'];
		break;
	}
}
$bPeticionXAJAX = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {if (isset($_POST['xjxfun'])) {$bPeticionXAJAX = true;}}
if (!$bPeticionXAJAX) {$_SESSION['u_ultimominuto'] = (date('W') * 1440) + (date('H') * 60) + date('i');}
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_xajax.php';
require $APP->rutacomun . 'unad_login.php';
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libmovil.php';
$iCodModulo = 0;
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';}
$mensajes_1 = 'lg/lg_1_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_1)) {$mensajes_1 = 'lg/lg_1_es.php';}
require $mensajes_todas;
require $mensajes_1;
$xajax = NULL;
if (isset($_REQUEST['saltos']) == 0) {$_REQUEST['saltos'] = 0;}
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {$objDB->dbPuerto = $APP->dbpuerto;}
if (isset($APP->piel) == 0) {$APP->piel = 1;}
$iPiel = $APP->piel;
if ($bDebug) {
	$sHost = gethostname();
	$sDebug = $sDebug . '' . fecha_microtiempo() . ' Probando conexi&oacute;n con la base de datos <b>' . $APP->dbname . '</b> en <b>' . $APP->dbhost . '</b> HOST ACTUAL <b>' . $sHost . '</b><br>';
}
if (!$objDB->Conectar()) {
	$sMsgCierre = '<br><br>Disculpe las molestias estamos en mantenimiento, <br><br>por favor intente acceder en unos minutos.<br><br>';
	if ($bDebug) {$sDebug = $sDebug . '' . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objDB->serror . '</b><br>';}
	$bCerrado = true;
}
$idEntidad = 0;
if (isset($APP->entidad) != 0) {
	if ($APP->entidad == 1) {$idEntidad = 1;}
}
$bPrimerIngreso = false;
if (isset($_REQUEST['id11']) == 0) {
	$_REQUEST['id11'] = 0;
	$bPrimerIngreso = true;
} else {
	//Verifiquemos que si viene no venga por get, sino por post
	if (isset($_GET['id11']) != 0) {
		// 19 de Enero de 2022, el id NO PUEDE LLEGAR POR GET.
		header('Location:./');
		die();
	}
}
$bChatBot = true;
$sDominio = 'unad.edu.co';
if ($idEntidad == 1) {
	$sDominio = 'unad.us';
	$bChatBot = false;
}
$iHoy = fecha_DiaMod();
if (isset($_POST['base']) != 0) {
	list($sVrSemilla, $sDebugS) = login_IniciarSemilla($sDominio, $objDB, $bDebug);
	?>
<!DOCTYPE html>
<head></head>
<body>
<script language="javascript">
setTimeout('window.document.frmbase.submit()',1);
</script>
<form id="frmbase" name="frmbase" method="post" action="">
<input id="saltos" name="saltos" type="hidden" value="<?php echo $_REQUEST['saltos'] + 1; ?>"/>
</form>
</body>
</html>
<?php
die();
}
$sTabla11 = 'unad11terceros';
//Ver que no tenga una sesion en otro campus.
if ($bPrimerIngreso) {
	//Las variables para verificar el acceso...
	$sIP = sys_traeripreal();
	$sHost = $sIP;
	if (isset($_COOKIE['idPC']) == 0) {
		//Iniciar la semilla y recargar la pagina.
		$bEntra = true;
		if (isset($_POST['base']) == 0) {}
		if ($_REQUEST['saltos'] > 2) {$bEntra = false;}
		if ($bEntra) {
			?>
<!DOCTYPE html>
<head></head>
<body>
<script language="javascript">
function bloqueados(){
	var dalarma=document.getElementById('div_bloueados');
	document.getElementById('div_bloueados').innerHTML='Al parecer las ventanas emergentes estan bloqueadas en su navegador, <br>haga clic en el siguiente boton para <input id="cmdContinuar" name="cmdContinuar" type="submit" value="Continuar" title="Continuar" />';
	}
setTimeout('window.document.frmbase.submit()',10);
setTimeout('bloqueados()',2000);
</script>
<form id="frmbase" name="frmbase" method="post" action="">
<input id="base" name="base" type="hidden" value="<?php echo $iHoy; ?>"/>
<input id="saltos" name="saltos" type="hidden" value="<?php echo $_REQUEST['saltos'] + 1; ?>"/>
<div id="div_bloueados" align="center"></div>
</form>
</body>
</html>
<?php
die();
		}
	}
	if (isset($_COOKIE['idPC']) != 0) {
		$sHost = $_COOKIE['idPC'];
	} else {
		$bCerrado = true;
		$sMsgCierre = 'Su navegador no permite cookies para el sitio ' . $sDominio;
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' No hay posibilidad de acceder a las cookies.<br>';}
	}
	if ($sHost == $sIP) {
		$bCerrado = true;
		$sMsgCierre = 'Su navegador no logro procesar las cookies para el sitio ' . $sDominio;
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' No hay posibilidad de acceder a las cookies.<br>';}
	}
	if (isset($_SERVER['HTTP_USER_AGENT']) != 0) {
		$sNavegador = substr($_SERVER['HTTP_USER_AGENT'], 0, 100);
	} else {
		$sNavegador = substr('Desconocido.' . $sHost, 0, 100);
	}
	$iNumSesiones = 0;
	if ($bCerrado) {
	} else {
		if (isset($_COOKIE['idUsuario']) != 0) {
			list($sRes, $iNumSesiones, $sDebugL) = login_SesionesTercero($sHost, $sNavegador, $sIP, $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugL;
			if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Sesiones del navegador: ' . $iNumSesiones . ' [' . $sRes . ']<br>';}
		}
		//$sDebug=$sDebug.$sDebugL;
		//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' '.$sHost.'<br>';}
	}
	if ($iNumSesiones == 1) {
		$sDestino = 'accesit.php';
		// Listos, iniciarle la sesion...
		$sSQL = 'SELECT unad11id, unad11idmoodle, unad11idioma FROM ' . $sTabla11 . ' WHERE unad11id="' . $sRes . '"';
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Consultando usuario: ' . $sSQL . ' <br>';}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idTercero = $fila['unad11id'];
			$_SESSION['unad_id_sesion'] = 0;
			$_SESSION['u_ipusuario'] = $sIP;
			$_SESSION['unad_agno'] = fecha_agno();
			$_SESSION['unad_geo_lat'] = '';
			$_SESSION['unad_idioma'] = $fila['unad11idioma'];
			$_SESSION['unad_geo_lon'] = '';
			$USER = new stdclass();
			$USER->id = $fila['unad11idmoodle'];
			$_SESSION['USER'] = $USER;
			// Traemos el ultimo id de sesion...
			$sTabla = 'unad71sesion' . fecha_AgnoMes();
			$sSQL = 'SELECT TB.unad71id, TB.unad71latgrados, TB.unad71longrados, TB.unad71latdecimas
			FROM ' . $sTabla . ' AS TB
			WHERE TB.unad71idtercero=' . $fila['unad11id'] . ' AND TB.unad71fechaini=' . $iHoy . ' AND TB.unad71tiempototal=0 AND TB.unad71hostname="' . $sHost . '" AND TB.unad71navegador="' . $sNavegador . '"
			AND ((TB.unad71iporigen="' . $sIP . '") OR (TB.unad71iporigen IN ("190.66.14.194")))
			ORDER BY TB.unad71id DESC';
			if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Sesion.: ' . $sSQL . ' <br>';}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$_SESSION['unad_id_tercero'] = $idTercero;
				$_SESSION['unad_id_sesion'] = $fila['unad71id'];
				$bPasa = false;
				if ($fila['unad71latdecimas'] != '') {
					$bPasa = true;
				} else {
					//Si la persona es sospechosa no puede localizar.
					$sSQL = 'SELECT 1 FROM unae13enrevision WHERE unae13idtercero=' . $_SESSION['unad_id_tercero'] . ' AND unae13estado<>2 LIMIT 0, 1';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) == 0) {
						$bPasa = true;
					}
				}
				if ($bPasa) {
					$_SESSION['unad_geo_lat'] = $fila['unad71latgrados'];
					$_SESSION['unad_geo_lon'] = $fila['unad71longrados'];
				}
			} else {
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Se alista para saltar a accesit<br>';
				$iSegFin = microtime(true);
				$iSegundos = $iSegFin - $iSegIni;
				echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '<div class="salto1px"></div></div>';
				die();
			}
			//Listos... redirigir.
			header('Location:accesit.php');
			die();
		}
	}
}
// Junio 26 de 2020 - Se quitan los rastros evolutivos de la integración con moodle.
function IniciaSesionV2($id11, $objDB, $id01 = 0, $bDebug = false) {
	$verifica = numeros_validar($id11);
	if ($verifica != $id11) {
		//Por alguna razon aun llegan aqui...
		//
		die();
	}
	//Pues, ya paso... que le hacemos....
	$sDebug = '';
	require './app.php';
	if (isset($APP->server) == 0) {$APP->server = 0;}
	$idEntidad = 0;
	if (isset($APP->entidad) != 0) {
		if ($APP->entidad == 1) {$idEntidad = 1;}
	}
	$sDominio = 'unad.edu.co';
	if ($idEntidad == 1) {
		$sDominio = 'unad.us';
	}
	//Recreamos el id pc...
	if (isset($_COOKIE['idPC']) != 0) {
		$sHost = $_COOKIE['idPC'];
		setcookie('idPC', $sHost, time() + (365 * 24 * 60 * 60), '/', '.' . $sDominio);
		setcookie('idUsuario', $id11, time() + (10 * 365 * 24 * 60 * 60), '/', '.' . $sDominio);
	}
	//Terminamos de recrear la cokie.
	if ($id01 != 0) {
		$sTabla = 'aure01login' . date('Ym');
		$aure01fecha = fecha_DiaMod();
		$aure01min = fecha_MinutoMod();
		$sSQL = 'UPDATE ' . $sTabla . ' SET aure01fechaaplica=' . $aure01fecha . ' , aure01minaplica=' . $aure01min . ' WHERE aure01id=' . $id01 . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	//Verificamos si es una ip a la que le tengamos que hacer seguimiento...
	$sDirIP = sys_traeripreal();
	$sSQL = 'SELECT unad90accion FROM unad90controlip WHERE unad90ip="' . $sDirIP . '"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sTabla = 'aure01login' . date('Ym');
		switch ($fila['unad90accion']) {
		case 60: //Hacer seguimiento
			$sDoc = '{' . $id11 . '}';
			$sNombre = '{No encontrado}';
			$sSQL = 'SELECT unad11doc, unad11razonsocial FROM ' . $sTabla11 . ' WHERE unad11id=' . $id11 . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sDoc = $fila['unad11doc'];
				$sNombre = cadena_notildes($fila['unad11razonsocial']);
			}
			list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sTabla, $objDB);
			$sMailSeguridad = 'seguridad.informacion@unad.edu.co';
			require $APP->rutacomun . 'libmail.php';
			$sNomEntidad = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
			$sMsg = '<h1>Aviso de inicio de sesion desde  ' . $sDirIP . '</h1>
			El usuario ' . $sDoc . ' ' . $sNombre . ' ha iniciado sesion desde la IP ' . $sDirIP . '<br>
			<b>Este es un mensaje automatico</b>';
			//Enviar el mensaje.
			$objMail = new clsMail_Unad($objDB);
			$objMail->TraerSMTP($idSMTP);
			$objMail->sAsunto = utf8_encode('Aviso de inicio de sesion desde  ' . $sDirIP . ' ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '');
			$objMail->addCorreo($sMailSeguridad, $sMailSeguridad);
			$objMail->sCuerpo = $sMsg;
			$sError = $objMail->Enviar();
			break;
		}
	}
	//Fin de si toca hacer seguimiento.
	@session_start();
	$_SESSION['unad_id_tercero'] = $id11;
	$sDebugI = login_iniciarsesion($objDB, $bDebug);
}
function f17_Login_Revisa($params) {
	if (!is_array($params)) {$params = json_decode(str_replace('\"', '"', $params), true);}
	if (isset($params[0]) == 0) {$params[0] = '';}
	if (isset($params[1]) == 0) {$params[1] = '';}
	if (isset($params[2]) == 0) {$params[2] = '';}
	if (isset($params[3]) == 0) {$params[3] = 0;}
	if (isset($params[4]) == 0) {$params[4] = '';}
	if (isset($params[5]) == 0) {$params[5] = '';}
	$sUsuario = htmlspecialchars($params[0]);
	$sTipoDoc = '';
	//$sDoc=numeros_validar($params[1]);
	$sDoc = cadena_letras($params[1], '0123456789-');
	$spw = $params[2];
	if ($params[5] != '') {
		$sTipoDoc = $params[4];
		$sDoc = $params[5];
	}
	$respuesta = '';
	$bSaltaAMoodle = false;
	$bDebug = false;
	$sTabla11 = 'unad11personas';
	$sTabla11 = 'unad11terceros';
	$sDebug = '';
	$bExterno = false;
	if ($params[3] == 1) {$bDebug = true;}
	require './app.php';
	if (isset($APP->ws_login) == 0) {$APP->ws_login = '';}
	if (isset($APP->server) == 0) {$APP->server = 0;}
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {$objDB->dbPuerto = $APP->dbpuerto;}
	if ($objDB->conectar()) {
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Conecto la base de datos<br>';}
	} else {
		$respuesta = '' . $objDB->serror . ' <br>Por favor informa al administrador del sistema.';
	}
	if ($respuesta == '') {
		if ($APP->ws_login == '') {
			//Deberia haber conexion local y por tanto la validacion es local.
			if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Inicia el login local<br>';}
			list($respuesta, $sDebugL, $bExterno) = login_validarV2($sDoc, $sUsuario, $spw, $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugL;
		} else {
			//El login se hace sobre campus0a porque no tenemos acceso al validador.
			require $APP->rutacomun . 'nusoap/nusoap.php';
			$sURL = $APP->ws_login;
			$cliente = new nusoap_client($sURL, false);
			$respuesta = $cliente->getError();
			if ($respuesta == '') {
				//echo '<br>Se ha establecido conexi&oacute;n, Enviando datos de logeo...';
				$aRes = $cliente->call('validar', array('sDoc' => $sDoc, 'sUsuario' => $sUsuario, 'sFrase' => $spw, 'sFrase2' => 'UNAD'));
				if ($cliente->fault) {
					$respuesta = 'Respuesta inesperada desde el servidor.<br>Respuesta ' . json_encode($aRes) . '';
				} else {
					//echo '<br>Verificando proceso de recepcion de datos...';
					$respuesta = $cliente->getError();
					if ($respuesta != '') {
						$respuesta = 'Error del servicio: ' . $respuesta;
					}
				}
			}
			if ($respuesta == '') {
				$respuesta = $aRes;
			}
		}
	}
	$objResponse = new xajaxResponse();
	switch ($respuesta) {
	case 'pasa':
		$respuesta = 'Ingresando...';
		$iTipoRespuesta = 2;
		$sError = '';
		//Pueden pasar 2 cosas... se le envia un codigo... o si esta cerrado el login pasa directo...
		if ((int) $sDoc != 0) {
			$sSQL = 'SELECT unad11id, unad11usuario FROM ' . $sTabla11 . ' WHERE unad11doc="' . $sDoc . '"';
		} else {
			$sSQL = 'SELECT unad11id, unad11usuario FROM ' . $sTabla11 . ' WHERE unad11usuario="' . $sUsuario . '"';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		$fila = $objDB->sf($tabla);
		$sUsuario = $fila['unad11usuario'];
		//Averiguar si tiene un perfil diferente a estudiante.
		if ($spw == '3456789012') {
			$bDoble = false;
			$sInfoRastro = ' Superusuario en modo debug';
		} else {
			if ($bExterno) {
				$bDoble = false;
				$sInfoRastro = ' Usuario externo [FLORIDA]';
			} else {
				list($bDoble, $sInfoRastro) = AUREA_RequiereDobleAutenticacionV2($fila['unad11id'], $objDB);
			}
		}
		if ($bDoble) {
			//$objResponse->call('MensajeAlarma("Generando doble autenticaci&oacute;n. Por favor espere un momento...")');
			//enviar el mensaje de autenticación....
			list($aure01codigo, $sError) = AUREA_IniciarLogin($fila['unad11id'], $objDB, '9876', 0, $bDebug);
			if ($sError != '') {
				$respuesta = str_replace('"', '-', $sError);
				$respuesta = str_replace("'", '-', $respuesta);
				$iTipoRespuesta = 0;
			}
		} else {
			//Abril 30 de 2020 se dejan rastros para poder hacer seguimiento.
			seg_rastroV2(17, 1, 0, 0, $fila['unad11id'], 'Se autoriza inicio de sesion SIN DOBLE AUTENTICACION.' . $sInfoRastro, $objDB);
			IniciaSesionV2($fila['unad11id'], $objDB);
			$bSaltaAMoodle = true;
		}
		$objDB->CerrarConexion();
		$objResponse->assign('id11', 'value', $fila['unad11id']);
		$objResponse->call('MensajeAlarmaV2("' . $respuesta . '", ' . $iTipoRespuesta . ')');
		//$objResponse->call('MensajeAlarmaV2("Prueba de acceso...", 0)');
		if ($sError == '') {
			if ($bSaltaAMoodle) {
				$objResponse->call('salta');
				//$objResponse->script('window.setTimeout(salta(), 300);');
			} else {
				if ($bDoble) {
					//Encapsular la clave y ponerla en el campo id12 para que cuando saltemos a moodle podamos hacerlo.
					$id12 = url_encode('aurea' . $spw);
					$objResponse->assign('id12', 'value', $id12);
				}
				$objResponse->call('cambiapagina');
			}
		} else {
			$objResponse->call('mostrarbotones');
		}
		$objResponse->call('expandesector(1)');
		break;
	case 'Servidor no responde':
		//Avisar... no....
		break;
	default:
/*
//Dejar un rastro...
if ((int)$sDoc!=0){
$sSQL='SELECT unad11id, unad11usuario FROM '.$sTabla11.' WHERE unad11doc="'.$sDoc.'"';
}else{
$sSQL='SELECT unad11id, unad11usuario FROM '.$sTabla11.' WHERE unad11usuario="'.$sUsuario.'"';
}
$tabla=$objDB->ejecutasql($sSQL);
 */
		//Presentar la respuesta.
		$respuesta = '' . $respuesta . '';
		$objResponse->call("MensajeAlarmaV2('Respuesta desde el servicio: " . $respuesta . "', 0)");
		$objResponse->assign('txtdoc', 'value', $sDoc);
		$objResponse->call('mostrarbotones');
		$objResponse->call('expandesector(1)');
		if ($bDebug) {
			$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	}
	return $objResponse;
}
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f17_Login_Revisa');
$xajax->processRequest();
if ($bPeticionXAJAX) {
	die(); // Esto hace que las llamadas por xajax terminen aqu?.
}
$sError = '';
$iTipoError = 0;
$iSector = 1;
$bMueveScroll = false;
if (isset($_REQUEST['id12']) == 0) {$_REQUEST['id12'] = '';}
if (isset($_REQUEST['paso']) == 0) {$_REQUEST['paso'] = 0;}
if (isset($_REQUEST['iscroll']) == 0) {$_REQUEST['iscroll'] = 0;}
if (isset($_REQUEST['txtuser']) == 0) {$_REQUEST['txtuser'] = '';}
if (isset($_REQUEST['btipodoc']) == 0) {$_REQUEST['btipodoc'] = '';}
if (isset($_REQUEST['bdoc']) == 0) {$_REQUEST['bdoc'] = '';}
if ($_SESSION['unad_id_tercero'] != 0) {
	$_REQUEST['id11'] = $_SESSION['unad_id_tercero'];
}
//Revisar el usuario
$sMensajeCampus = $ETI['msg_mensajecampus'];
$bConUsuario = false;
$_REQUEST['txtuser'] = trim($_REQUEST['txtuser']);
if ($_REQUEST['txtuser'] != '') {$bConUsuario = true;}
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 0;
	$sUsuario = htmlspecialchars($_REQUEST['txtuser']);
	if ($sUsuario == $_REQUEST['txtuser']) {
		if ($_REQUEST['txtuser'] == '') {
			$sError = $ETI['msg_nousuario'];
		}
	} else {
		$_REQUEST['txtuser'] = '';
		$bConUsuario = false;
		$sError = $ETI['msg_nousuario'];
	}
	if ($sError == '') {
		//Ver que la ip no este bloqueada.
		$sDirIP = sys_traeripreal();
		$sSQL = 'SELECT unad90accion FROM unad90controlip WHERE unad90ip="' . $sDirIP . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			switch ($fila['unad90accion']) {
			case 99: //Bloqueada.
				$sError = $ETI['msg_ipbloqueada'] . $sMensajeCampus;
				$_REQUEST['txtuser'] = '';
				$bConUsuario = false;
				break;
			}
		} else {
			list($bRes, $sDebug) = f190_AddIpV2($sDirIP, $objDB);
		}
	}
	if ($sError == '') {
		$bExisteUsuario = false;
		$sConsultaTercero = 'SELECT unad11idmoodle, unad11id, unad11usuario, unad11tipodoc, unad11doc, unad11idioma FROM unad11terceros WHERE ';
		//Junio 28 de 2019 - En caso de que no se encuentre... busquemoslo por documento...
		$sSQLd = $sConsultaTercero . 'unad11doc="' . $_REQUEST['txtuser'] . '" AND unad11tipodoc="CC"';
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Consultando datos de usuario (Por documento) ' . $sSQLd . '<br>';}
		$tabla = $objDB->ejecutasql($sSQLd);
		if ($objDB->nf($tabla) == 0) {
			$sSQL = $sConsultaTercero . 'unad11usuario="' . $_REQUEST['txtuser'] . '"';
			if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Consultando datos de usuario ' . $sSQL . '<br>';}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$bExisteUsuario = true;
			} else {
				if (!is_numeric($_REQUEST['txtuser'])) {
					//Octubre 15 de 2021 - El usuario puede existir, intenta ingresar con usuario pero su usuario no esta emparejado con el documento
					// Por tanto se intenta sincronizar el usuario desde el servicio web.
					$sUrlServicio = 'http://aurea2.unad.edu.co/servicios/ws_1011.php/' . $_REQUEST['txtuser'] . '/' . md5(fecha_DiaMod() . 'UNAD');
					if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' <b>Revisando servicio de terceros</b>: ' . $sUrlServicio . '<br>';}
					require $APP->rutacomun . 'librest.php';
					$objRest = new UNAD_Rest(2);
					$aRes = $objRest->leer($sUrlServicio);
					sleep(3); //Le damos unos segunditos por el tema de la comunicacion
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$bExisteUsuario = true;
					} else {
						$tabla = $objDB->ejecutasql($sSQLd);
						if ($objDB->nf($tabla) > 0) {
							$bExisteUsuario = true;
						}
					}
				}
			}
		} else {
			$bExisteUsuario = true;
		}
		if (!$bExisteUsuario) {
			//intentamos importarlo en primera instancia.
			if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Intentando importar el usuario<br>';}
			if (is_numeric($_REQUEST['txtuser'])) {
				list($sErrorI, $sDebugI) = unad11_importar_V2($_REQUEST['txtuser'], '', $objDB, $bDebug);
				$sDebug = $sDebug . $sDebugI;
				$sSQL = $sConsultaTercero . 'unad11doc="' . $_REQUEST['txtuser'] . '" AND unad11tipodoc="CC"';
			} else {
				list($sErrorI, $sDebugI) = unad11_importar_V2('', $_REQUEST['txtuser'], $objDB, $bDebug);
				$sDebug = $sDebug . $sDebugI;
				$sSQL = $sConsultaTercero . 'unad11usuario="' . $_REQUEST['txtuser'] . '"';
			}
			if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Reintentando consultar usuario ' . $sSQL . '.<br>';}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) == 0) {
				$sError = $ETI['msg_noexisteusuario'] . $sMensajeCampus;
				$bConUsuario = false;
			} else {
				$bExisteUsuario = true;
			}
		}
		if ($bExisteUsuario) {
			$fila = $objDB->sf($tabla);
			$_SESSION['unad_idioma'] = $fila['unad11idioma'];
			$_REQUEST['txtuser'] = $fila['unad11usuario'];
			$_REQUEST['btipodoc'] = $fila['unad11tipodoc'];
			$_REQUEST['bdoc'] = $fila['unad11doc'];
			//Actualizamos las librerias de idioma para asegurarnos.
			$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
			if (!file_exists($mensajes_todas)) {$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';}
			$mensajes_1 = 'lg/lg_1_' . $_SESSION['unad_idioma'] . '.php';
			if (!file_exists($mensajes_1)) {$mensajes_1 = 'lg/lg_1_es.php';}
			require $mensajes_todas;
			require $mensajes_1;
		}
	}
}
//Revisar el codigo de verificacion
$bSaltaAMoodle = false;
if ($_REQUEST['paso'] == 23) {
	$_REQUEST['paso'] = 2;
	if (isset($_REQUEST['txtcodigo']) == 0) {$_REQUEST['txtcodigo'] = '';}
	$_REQUEST['txtcodigo'] = numeros_validar($_REQUEST['txtcodigo']);
	$idTercero = numeros_validar($_REQUEST['id11']);
	if ($idTercero != $_REQUEST['id11']) {
		//Este personaje esta intentando cosas, asi que de una bloqueamos esa ip..
		//
		die();
	}
	if ($_REQUEST['txtcodigo'] == '') {$sError = $ETI['msg_noingcodigo'];}
	if ($sError == '') {
		$aure01ip = sys_traeripreal();
		$aure01fecha = fecha_DiaMod();
		$sTabla = 'aure01login' . date('Ym');
		$sSQL = 'SELECT aure01id, aure01fecha, aure01min, aure01ip, aure01punto FROM ' . $sTabla . ' WHERE aure01idtercero=' . $idTercero . '  AND aure01codigo="' . $_REQUEST['txtcodigo'] . '" AND aure01fechaaplica<1 ORDER BY aure01id DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de codigo ' . $sSQL . '<br>';}
		if ($objDB->nf($tabla) == 0) {
			$bEspecial = false;
			if ($_REQUEST['txtcodigo'] == '3456789012') {
				//Ver que el usuario sea super administrador.
				$sSQL = 'SELECT 1 FROM unad07usuarios WHERE unad07idperfil=1 AND unad07idtercero=' . $idTercero . ' AND unad07vigente="S"';
				$tabla7 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla7) > 0) {$bEspecial = true;}
			}
			if ($bEspecial) {
				$sSQL = 'SELECT aure01id, aure01fecha, aure01min, aure01ip, aure01punto FROM ' . $sTabla . ' WHERE aure01idtercero=' . $idTercero . ' AND aure01fechaaplica<1 AND aure01fecha=' . $aure01fecha . ' ORDER BY aure01id DESC LIMIT 0,1';
				$tabla = $objDB->ejecutasql($sSQL);
			}
		}
		if ($objDB->nf($tabla) == 0) {
			$sError = $ETI['msg_codigoerrado'];
			//Abril 30 de 2020 se dejan rastros para poder hacer seguimiento.
			seg_rastroV2(17, 0, 0, 0, $idTercero, '<span class="rojo">FALLA DE CODIGO</span>: Codigo incorrecto [IP ' . $aure01ip . '].', $objDB);
		}
	}
	if ($sError == '') {
		$fila = $objDB->sf($tabla);
		$aure01id = $fila['aure01id'];
		if ($fila['aure01fecha'] != $aure01fecha) {
			$sError = $ETI['msg_codigovencido'];
		}
	}
	if ($sError == '') {
		$aure01min = fecha_MinutoMod();
		if ($aure01min < ($fila['aure01min'] + 120)) {
		} else {
			$sError = $ETI['msg_codigovencido'] . '. <!-- ' . ($fila['aure01min'] + 120) . ' - ' . $aure01min . ' -->';
		}
	}
	if ($sError == '') {
		if ($fila['aure01ip'] != $aure01ip) {
			$sError = $ETI['msg_codigonoip'];
			//Abril 30 de 2020 se dejan rastros para poder hacer seguimiento.
			seg_rastroV2(17, 0, 0, 0, $idTercero, '<span class="rojo">FALLA DE CODIGO</span>: Intenta validar codigo de acceso generado en la IP ' . $fila['aure01ip'] . ' usando la IP ' . $aure01ip . '', $objDB);
		}
	}
	if ($sError == '') {
		$sProtocolo = 'http';
		$idEntidad = 0;
		if (isset($APP->entidad) != 0) {
			if ($APP->entidad == 1) {$idEntidad = 1;}
		}
		switch ($idEntidad) {
		case 1:
			break;
		default:
			if (isset($_SERVER['HTTPS']) != 0) {
				if ($_SERVER['HTTPS'] == 'on') {$sProtocolo = 'https';}
			}
			break;
		}
		$aure01punto = $sProtocolo . '://' . $_SERVER['SERVER_NAME'] . formato_UrlLimpia($_SERVER['REQUEST_URI']);
		$bPuntoCorrecto = false;
		if ($fila['aure01punto'] == $aure01punto) {
			$bPuntoCorrecto = true;
		} else {
			//Puede haber un index.php al final...
			$sOrigenLimpio = cadena_Reemplazar($fila['aure01punto'], 'index.php', '');
			if ($sOrigenLimpio == $aure01punto) {$bPuntoCorrecto = true;}
		}
		if (!$bPuntoCorrecto) {
			$sError = $ETI['msg_codigonoorigen'];
			//Junio 17 de 2020 se dejan rastros para poder hacer seguimiento.
			seg_rastroV2(17, 0, 0, 0, $idTercero, '<span class="rojo">FALLA DE CODIGO</span>: Intenta validar codigo de acceso generado en la URL ' . $fila['aure01punto'] . ' usando la URL ' . $aure01punto . '', $objDB);
		}
	}
	if ($sError == '') {
		$unad11idmoodle = 0;
		$sUsuario = '';
		$sSQL = 'SELECT unad11idmoodle, unad11usuario FROM ' . $sTabla11 . ' WHERE unad11id=' . $_REQUEST['id11'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Se saca el usuario y el id de moodle<br>';}
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sUsuario = $fila['unad11usuario'];
			IniciaSesionV2($_REQUEST['id11'], $objDB, $aure01id);
			$bSaltaAMoodle = true;
			if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Se envia orden de salto a moodle<br>';}
		} else {
			$sError = $ETI['msg_notercero'] . ' {Ref ' . $_REQUEST['id11'] . '}';
		}
	}
}
//El acceso por URL
//Si se recibe la url
if (isset($_GET['u']) != 0) {
	//Esta recibiendo una peticion de recuperacion.
	$sURL = url_decode_simple($_GET['u']);
	if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Dato de llegada: ' . $sURL . ' <br>';}
	$aURL = explode('|', $sURL);
	$sSemilla = '';
	if (count($aURL) > 3) {
		$sSemilla = $aURL[3];
	}
	$aure01id = numeros_validar($aURL[0]);
	if ($aure01id != $aURL[0]) {
		$sError = 'No fue posible leer la url de acceso, se ha enviado los datos al administrador del sistema para su revisi&oacute;n.';
	}
	if ($sError == '') {
		$sTabla = 'aure01login' . date('Ym');
		$sSQL = 'SELECT aure01idtercero, aure01fecha, aure01min, aure01codigo, aure01ip, aure01punto
		FROM ' . $sTabla . ' WHERE aure01id=' . $aure01id . ' AND aure01fechaaplica=-1';
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL . ' <br>';}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			$sError = 'No se ha encontrado la solicitud de acceso, es posible que deba volver a generarla.';
		}
	}
	if ($sError == '') {
		$fila = $objDB->sf($tabla);
		if ($fila['aure01codigo'] != $aURL[1]) {
			$sError = 'La solicitud de acceso no concuerda, es posible que deba volver a generarla.';
		}
	}
	if ($sError == '') {
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' El c&oacute;digo coincide <br>';}
		if ($fila['aure01fecha'] != fecha_DiaMod()) {
			$sError = 'La solicitud de acceso esta vencida, debe volver a generarla.';
		}
	}
	if ($sError == '') {
		$aure01min = fecha_MinutoMod();
		if ($aure01min < ($fila['aure01min'] + 120)) {
		} else {
			$sError = 'La solicitud de acceso esta vencida, debe volver a generarla.';
		}
	}
	if ($sError == '') {
		$aure01ip = sys_traeripreal();
		if ($fila['aure01ip'] != $aure01ip) {
			$bPasaXSemilla = false;
			$sInfo = '';
			if ($bDebug) {$sInfo = ' -- Sin semilla';}
			if ($sSemilla != '') {
				if ($bDebug) {$sInfo = ' Semilla ' . $sSemilla;}
				//Verificar la semilla...
				if (isset($_COOKIE['idPC']) != 0) {
					if ($bDebug) {$sInfo = $sInfo . ' - base ' . $_COOKIE['idPC'];}
					if ($sSemilla == $_COOKIE['idPC']) {$bPasaXSemilla = true;}
				}
			}
			if (!$bPasaXSemilla) {
				$sError = 'Esta solicitud de acceso no corresponde a esta IP, debe volver a generarla, esta url solo es v&aacute;lida usando el navegador en que fue generada.' . $sInfo;
			}
		}
	}
	if ($sError == '') {
		$sProtocolo = 'http';
		$idEntidad = 0;
		if (isset($APP->entidad) != 0) {
			if ($APP->entidad == 1) {$idEntidad = 1;}
		}
		switch ($idEntidad) {
		case 1:
			break;
		default:
			if (isset($_SERVER['HTTPS']) != 0) {
				if ($_SERVER['HTTPS'] == 'on') {$sProtocolo = 'https';}
			}
			break;
		}
		$aure01punto = $sProtocolo . '://' . $_SERVER['SERVER_NAME'] . formato_UrlLimpia($_SERVER['REQUEST_URI']);
		if ($fila['aure01punto'] != $aure01punto) {
			$sError = 'Esta solicitud de acceso no corresponde a este URL, debe volver a generarla.';
		}
	}
	if ($sError == '') {
		$_REQUEST['id11'] = $fila['aure01idtercero'];
		$unad11idmoodle = 0;
		$sUsuario = '';
		$sSQL = 'SELECT unad11usuario FROM ' . $sTabla11 . ' WHERE unad11id=' . $_REQUEST['id11'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Se saca el usuario y el id de moodle<br>';}
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sUsuario = $fila['unad11usuario'];
		}
		IniciaSesionV2($_REQUEST['id11'], $objDB, $aure01id);
		$bSaltaAMoodle = true;
		if ($bDebug) {$sDebug = $sDebug . fecha_microtiempo() . ' Se envia orden de salto a moodle<br>';}
	}
}
//Completar los datos del tercero...
$sLinkVideoRecupera = '<a href="https://www.youtube.com/watch?v=E9ljof2l32c" alt="Lista blanca de correo electr&oacute;nico" target="_blank">video</a>';
$sCorreoSoporte = 'soporte.campus@unad.edu.co';
if ($idEntidad == 1) {
	$sCorreoSoporte = 'aluna@unad.us';
}
$sDatosUsuario = '';
$sDatosEnvio = '';
$sCorreoNotifica = '';
if ((int) $_REQUEST['id11'] != 0) {
	$verifica = numeros_validar($_REQUEST['id11']);
	if ($verifica != $_REQUEST['id11']) {
		//Este personaje esta intentando cosas, asi que de una bloqueamos esa ip..
		//
		die();
	} else {
		// 19 de Enero de 2022
		//Llega el id, pero si lo habia consultado o no???
	}
	$sSQL = 'SELECT unad11doc, unad11razonsocial FROM ' . $sTabla11 . ' WHERE unad11id=' . $_REQUEST['id11'] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sDatosUsuario = 'Usuario <b>' . cadena_notildes($fila['unad11razonsocial']) . '</b>';
		list($sCorreoNotifica, $sErrorM, $sDebugM) = AUREA_CorreoNotifica($_REQUEST['id11'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugM;
	}
	if ($sCorreoNotifica == '') {
		$sError = 'No existe una direcci&oacute;n de correo electr&oacute;nico v&aacute;lida.';
	}
}
$sHTMLAplicativos = '';
if ((int) $_SESSION['unad_id_tercero'] != 0) {
	$sAplicativos = AUREA_Aplicativos($_SESSION['unad_id_tercero'], $objDB);
	$sSQL = 'SELECT unad01id, unad01descripcion, unad01ruta FROM unad01sistema WHERE unad01id IN (' . $sAplicativos . ') AND unad01ruta<>"" ORDER BY unad01descripcion';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		if ($sHTMLAplicativos == '') {
			$sHTMLAplicativos = '<b>Aplicativos disponibles plataforma AUREA</b>';
		}
		$sHTMLAplicativos = $sHTMLAplicativos . '<div class="salto5px"></div>
<a href="' . $fila['unad01ruta'] . '" class="lnkresalte" target="_blank">' . cadena_notildes($fila['unad01descripcion']) . '</a>';
	}
}
$modnombre = $ETI['msg_acceder'];
$modsigla = 'Panel';
//$et_menu=html_menu($APP->idsistema, $objDB, $iPiel);
$et_menu = '';
//Lugar para la generacion de cookies...
if (isset($_COOKIE['idPC']) == 0) {
	list($sVrSemilla, $sDebugS) = login_IniciarSemilla($sDominio, $objDB, $bDebug);
}
//Fin de las cookies.
$objDB->CerrarConexion();
//FORMA
//$bConPWA=!$bConUsuario;
$bConPWA = true;
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $modnombre, false, $bConPWA);
//echo $et_menu;
forma_mitad(false);
if (false) {
	?>
<link rel="stylesheet" href="../ulib/unad_estilos.css" type="text/css"/>
<?php
}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<script language="javascript">
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
function MensajeAlarma(sHTML){
	MensajeAlarmaV2(sHTML, 0);
	}
function revisauser(){
	var dUsuario=window.document.frmedita.txtuser.value.trim();
	if (dUsuario!=''){
		MensajeAlarmaV2('<?php echo $ETI['msg_verificandousuario']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value=21;
		window.document.frmedita.submit();
		}else{
		MensajeAlarmaV2('<?php echo $ETI['msg_noingusuario']; ?>', 0);
		window.document.frmedita.txtuser.focus();
		}
	}
function revisalogin2(){
	var dUsuario=window.document.frmedita.txtuser.value.trim();
	var dTipoDoc=window.document.frmedita.btipodoc.value.trim();
	var dDoc=window.document.frmedita.bdoc.value.trim();
	var dPas=window.document.frmedita.txtclave2.value.trim();
	if (dDoc!=''){
		if (dPas!=''){
			expandesector(98);
			verboton('cmdIngresa2', 'none');
<?php
if ($bConUsuario) {
	?>
		verboton('cmdCancela', 'none');
<?php
}
?>
		MensajeAlarmaV2('<b><?php echo $ETI['msg_verificando']; ?></b>', 2);
		var params=new Array();
		params[0]=dUsuario;
		params[1]='';
		params[2]=dPas;
		params[3]=window.document.frmedita.debug.value;
		params[4]=dTipoDoc;
		params[5]=dDoc;
		xajax_f17_Login_Revisa(params);
		}else{
		MensajeAlarmaV2('<?php echo $ETI['msg_noingclave']; ?>', 0);
		window.document.frmedita.txtclave2.focus();
		}
	}else{
	MensajeAlarmaV2('<?php echo $ETI['msg_noingusuario']; ?>', 0);
	window.document.frmedita.txtuser.focus();
	}
  }
function mostrarbotones(){
	verboton('cmdIngresa2', 'block');
<?php
if ($bConUsuario) {
	?>
	verboton('cmdCancela', 'block');
<?php
}
?>
	}
function mostrarbotones2(){
	verboton('cmdIngresa', 'block');
	verboton('cmdIngresa2', 'block');
	}
function revisacodigo(){
	expandesector(98);
	window.document.frmedita.paso.value=23;
	window.document.frmedita.submit();
	}
function cancelar(){
	expandesector(98);
	window.document.frmedita.paso.value=0;
	window.document.frmedita.txtuser.value='';
	window.document.frmedita.submit();
	}
function salta(){
	cambiapagina();
	}
function cambiaidioma(sIdioma){
	expandesector(98);
	window.document.frmedita.idioma.value=sIdioma;
	window.document.frmedita.paso.value=26;
	window.document.frmedita.submit()
	}
</script>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" class="login" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="0" />
<input id="id11" name="id11" type="hidden" value="<?php echo $_REQUEST['id11']; ?>" />
<input id="id12" name="id12" type="hidden" value="<?php echo $_REQUEST['id12']; ?>" />
<input id="btipodoc" name="btipodoc" type="hidden" value="<?php echo $_REQUEST['btipodoc']; ?>" />
<input id="bdoc" name="bdoc" type="hidden" value="<?php echo $_REQUEST['bdoc']; ?>" />
<input id="idioma" name="idioma" type="hidden" value="" />
<div id="div_sector1">
<div class="Cuerpo600">
 <h1 class="TituloAzul1"><?php echo $ETI['msg_acceder']; ?></h1>
<?php
$bConEnter = false;
$bConMensajeClave = true;
if ($bCerrado) {
	echo $sMsgCierre;
} else {
	if ($_REQUEST['id11'] == 0) {
		?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_usuario'];
		?>
</label>
<label>
<?php
if ($bConUsuario) {
			$sInfoUsuario = $_REQUEST['txtuser'];
			if ($_REQUEST['txtuser'] == '') {$sInfoUsuario = $_REQUEST['bdoc'];}
			echo html_oculto('txtuser', $_REQUEST['txtuser'], $sInfoUsuario);
			echo '{<a href="javascript:cancelar()">' . $ETI['msg_cancelar'] . '</a>}';
		} else {
			?>
<input id="txtuser" name="txtuser" type="text" value="<?php echo $_REQUEST['txtuser']; ?>" maxlength="30" autocomplete="off" placeholder="<?php echo $ETI['msg_ingdocumento']; ?>" autofocus/>
<?php
}
		?>
</label>
<div class="salto1px"></div>
<?php
$bConEnter = true;
		$sAccion = 'revisauser();';
		if ($bConUsuario) {
			$sAccion = 'revisalogin2();';
			?>
<label class="Label90">
<?php
echo $ETI['msg_clave'];
			?>
</label>
<label>
<input id="txtclave2" name="txtclave2" type="password" value="" maxlength="50" autocomplete="new-password" autofocus />
</label>
<?php
}
		$sTituloBoton = $ETI['msg_btentrar'];
		?>
<div class="salto1px"></div>
<label class="Label90">&nbsp;</label>
<label class="Label60">
<input id="cmdIngresa2" name="cmdIngresa2" type="button" value="<?php echo $sTituloBoton; ?>"  onClick="<?php echo $sAccion; ?>" class="BotonAzul"/>
</label>
<?php
if ($bConUsuario) {
			//<label class="Label60">&nbsp;</label>
			?>
<label class="Label60" style="display:none">
<input id="cmdCancela" name="cmdCancela" type="button" value="<?php echo $ETI['msg_cancelar']; ?>"  onClick="cancelar()" class="BotonAzul"/>
</label>
<?php
}
		?>
<?php
} else {
		?>
<input id="txtuser" name="txtuser" type="hidden" value="<?php echo $_REQUEST['txtuser']; ?>"/>
<?php
if ($bSaltaAMoodle) {
			?>
<div class="GrupoCamposAyuda">
<div class="MarquesinaMedia"><?php echo $ETI['msg_ingresando']; ?></div>
<div class="salto1px"></div>
<div id="div_btsalto" style="display:none">
<?php echo $ETI['msg_haydemora']; ?>
<input id="cmdSalto" name="cmdSalto" type="button" value="<?php echo $ETI['msg_btcontinuar']; ?>"  onClick="cambiapagina()" class="BotonAzul"/>
</div>
</div>
<?php
$bChatBot = false;
			$bConMensajeClave = false;
		} else {
			echo '<div>&nbsp;&nbsp;&nbsp;' . $sDatosUsuario . '</div>';
			//ya hay un id11 pero puede que no este logeado, solo saltando al segundo paso.
			if ($_SESSION['unad_id_tercero'] == 0) {
				?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<label class="L">
<?php
echo $ETI['msg_enviocorreo1'] . '<b>' . formato_CorreoOculto($sCorreoNotifica) . '</b>' . $ETI['msg_enviocorreo2'];
				?>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="salto5px"></div>
<b>
<?php
echo $ETI['msg_ingresecodigo'];
				?>
</b>
<div class="salto1px"></div>
<label class="Label90">&nbsp;
</label>
<label>
<input id="txtcodigo" name="txtcodigo" type="text" value="" maxlength="20" autocomplete="off" placeholder="<?php echo $ETI['msg_codigoacceso']; ?>" autofocus/>
</label>
<div class="salto1px"></div>
<label class="Label90">&nbsp;</label>
<label>
<input id="cmdIngresa3" name="cmdIngresa3" type="button" value="<?php echo $ETI['msg_btentrar']; ?>"  onClick="revisacodigo();" class="BotonAzul"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<label class="L">
<?php
echo $ETI['msg_enviocorreo3'] . $sLinkVideoRecupera . $ETI['msg_enviocorreo3b'] . ' ' . $sCorreoSoporte . '';
				?>
</label>
<div class="salto1px"></div>
</div>
<?php
$bChatBot = false;
				$bConEnter = true;
				$bConMensajeClave = false;
				$sAccion = 'revisacodigo();';
			} else {
				?>
<div class="salto5px"></div>
<?php
echo $sHTMLAplicativos;
				?>
<div class="salto1px"></div>
<?php
}
			//Fin de si no salto a moodle.
		}
	}
	//Fin de si no esta cerrado...
}
?>
<div class="salto1px"></div>
<div id="mensaje"></div>
<div class="salto1px"></div>
<?php
//las banderas para cambio de idioma.
$bConBotones = $bDebug;
$bConEspanol = $bConBotones;
$bConIngles = $bConBotones;
$bConPortugues = $bConBotones;
switch ($_SESSION['unad_idioma']) {
case 'es':
	$bConEspanol = false;
	break;
case 'en':
	$bConIngles = false;
	break;
case 'pt':
	$bConPortugues = false;
	break;
}
?>
<?php
$bPrimerEtiqueta = false;
if ($bConEspanol) {
	?>
<label class="Label90"></label>
<label class="Label60">
<a href="javascript:cambiaidioma('es');"><img src="<?php echo $APP->rutacomun; ?>img/es_co.jpg" title="Espa&ntilde;ol" width="40px" height="25px"/></a>
</label>
<?php
$bPrimerEtiqueta = true;
}
if ($bConIngles) {
	if ($bPrimerEtiqueta) {
		echo '<label class="Label30"></label>';
	} else {
		echo '<label class="Label90"></label>';
	}
	?>
<label class="Label60">
<a href="javascript:cambiaidioma('en');"><img src="<?php echo $APP->rutacomun; ?>img/en_en.jpg" title="English" width="40px" height="25px"/></a>
</label>
<?php
$bPrimerEtiqueta = true;
}
if ($bConPortugues) {
	if ($bPrimerEtiqueta) {
		echo '<label class="Label30"></label>';
	} else {
		echo '<label class="Label90"></label>';
	}
	?>
<label class="Label60">
<a href="javascript:cambiaidioma('pt');"><img src="<?php echo $APP->rutacomun; ?>img/pt_br.jpg" title="Portugu&ecirc;s" width="40px" height="25px"/></a>
</label>
<?php
}
?>
<div class="salto1px"></div>
<?php
$bEntra = !$bCerrado;
if (!$bConMensajeClave) {$bEntra = false;}
if ($bEntra) {
	$sRutaBase = '';
	?>
<ul class="enlacesLogin">
<li><a target="_blank" href="<?php echo $sRutaBase; ?>recuperar.php" ><?php echo $ETI['msg_recuperaclave']; ?></a></li>
<li><a target="_blank" href="<?php echo $sRutaBase; ?>ayuda.php" ><?php echo $ETI['msg_ayudaingreso']; ?></a></li>
</ul>
<?php
}
?>
</div>
</div>

<div id="div_sector98" style="display:none">
<?php
echo '<h2>' . $ETI['msg_acceder'] . '</h2>';
?>
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div>
<?php
//Termina la marquesina
?>
</div>
<?php
if ($sDebug != '') {
	$iSegFin = microtime(true);
	$iSegundos = $iSegFin - $iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '<div class="salto1px"></div></div>';
}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value=""/>
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>"/>
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>"/>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<div class="flotante">
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
//El script que cambia el sector que se muestra
 ?>
<script language="javascript">
<?php
if ($iSector != 1) {
	echo 'expandesector(' . $iSector . ');
';
}
if ($bMueveScroll) {
	echo 'retornacontrol();
';
}
if ($bConEnter) {
	echo 'function siguienteobjeto(){' . $sAccion . '};
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
if ($bSaltaAMoodle) {
	?>
function versalto(){
	document.getElementById('div_btsalto').style.display='block';
	}
<?php
echo 'setTimeout(function(){salta();}, 300);
setTimeout(function(){versalto();}, 1000);
';
}
?>
</script>
<?php
if ($bChatBot) {
	if (file_exists('chatbot.php')) {
		require 'chatbot.php';
	}
}
if ($bConPWA) {
	?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<link rel="stylesheet" href="./pwa/css/sweet.css">
<div id="installContainer" class="hidden"></div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" type="text/css">
<script src="./sw-log.js"></script>
<?php
}
forma_piedepagina(false);
?>
