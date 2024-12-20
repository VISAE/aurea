<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Esta libreria es para funciones comunes xajax
--- Modelo Versión 2.18.4 domingo, 16 de julio de 2017 - Se hacen ajustes a UTF8
--- Modelo Versión 2.21.0 lunes, 20 de enero de 2020 - la importacion de usuarios se hace desde la db campus
*/
// -- 18 de Febrero de 714 para formatear campos moneda
function formatear_moneda($id, $value)
{
	if ($value == '') {
		$value = 0;
	}
	$value = numeros_validar($value, true, 2);
	$objResponse = new xajaxResponse();
	$objResponse->assign($id, 'value', formato_numero($value, 2));
	return $objResponse;
}
function login_ingresa($usuario, $clave)
{
	$sError = '';
	require './app.php';
	include $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sError = login_valida_usuario_v3($usuario, $clave, 51, $objDB);
	$objResponse = new xajaxResponse();
	if ($sError == 'pasa') {
		$sbloque41 = '(<a href="salir.php">Salir</a>)</div>';
		$objResponse->assign('login_div', 'innerHTML', $sbloque41);
		$objResponse->assign('login_alerta', 'innerHTML', '');
	} else {
		$objResponse->assign('login_alerta', 'innerHTML', $sError);
	}
	return $objResponse;
}

//Abril 12 de 2015
function Moneda_Sumar($id, $value, $suma, $bResta = false, $bMayorQueCero = false)
{
	$value = numeros_validar($value, true);
	$suma = numeros_validar($suma, true);
	if ($value == '') {
		$value = 0;
	}
	if ($suma == '') {
		$suma = 0;
	}
	if ($bResta) {
		$value = $value - $suma;
	} else {
		$value = $value + $suma;
	}
	if ($bMayorQueCero) {
		if ($value < 0) {
			$value = 0;
		}
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign($id, 'value', formato_numero($value, 2));
	return $objResponse;
}
//Mayro 29 de 2015
function Moneda_TotalizarOculto($aParametros)
{
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$value = numeros_validar($aParametros[2], true);
	$suma = numeros_validar($aParametros[3], true);
	if ($value == '') {
		$value = 0;
	}
	if ($suma == '') {
		$suma = 0;
	}
	switch ($aParametros[1]) {
		case '+':
			$value = round($value + $suma, 2);
			break;
		case '-':
			$value = round($value - $suma, 2);
			break;
		case '*':
		case 'x':
			$value = round($value * $suma, 2);
			break;
		case 'd':
			if ($suma == 0) {
				$value = 0;
			} else {
				$value = round($value / $suma, 2);
			}
			break;
		case '%':
			$value = round($value * $suma / 100, 2);
			break;
	}
	$sCampoDestino = $aParametros[0];
	$html_data = html_oculto($sCampoDestino, $value, formato_moneda($value));
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_' . $sCampoDestino, 'innerHTML', $html_data);
	return $objResponse;
}

function sesion_abandona()
{
	return sesion_abandona_V2();
}
function sesion_abandona_maidi($aDatos)
{
	return sesion_abandona_V2(false, true, $aDatos);
}
function sesion_abandona_V2($bDebug = false, $bConCorreo = false, $aDatos = NULL)
{
	if (isset($_SESSION['unad_id_tercero']) == 0) {
		$_SESSION['unad_id_tercero'] = 0;
	}
	$ifalta = -1;
	$sMsg = '';
	$sDebug = '';
	if ($_SESSION['unad_id_tercero'] != 0) {
		require './app.php';
		//la funcion original esta en todas.php, se saca el minuto de la semana.
		$iavance = (date("W") * 1440) + (date("H") * 60) + date("i");
		if (isset($_SESSION['unad_tiempo_sesion']) == 0) {
			$_SESSION['unad_tiempo_sesion'] = 20;
		}
		if (isset($APP->tiempolimite) != 0) {
			if ($APP->tiempolimite > $_SESSION['unad_tiempo_sesion']) {
				$_SESSION['unad_tiempo_sesion'] = $APP->tiempolimite;
			}
		}
		$itiempomax = $_SESSION['unad_tiempo_sesion'];
		$ibase = -$itiempomax;
		if (isset($_SESSION['u_ultimominuto']) != 0) {
			$ibase = $_SESSION['u_ultimominuto'];
		}
		$ifalta = ($ibase + $itiempomax) - $iavance;
		if ($ifalta > 0) {
			//si falta menos de un minuto mover la sesion... 
			//Esto no mueve variables de sesion, solo informa a la db que hasta esta hora se mantuvo el usuario.
			$sDebug = sesion_actualizar_v2(NULL, $bDebug);
			//marcar el tiempo que falta...
			if ($ifalta < 4) {
				$sScript = ', <a href="javascript:mantener_sesion();" class="resalte"><span style="color:#FFFF99">o haga clic aqu&iacute; para mantenerse en el sistema</span></a>';
				$sMsg = $ifalta . ' minutos para cerrar la sesi&oacute;n' . $sScript;
			}
		}
	} else {
		$ifalta = 1;
	}
	if ($ifalta < 1) {
		if (isset($_SESSION['unad_id_sesion']) != 0) {
			if ((int)$_SESSION['unad_id_sesion'] != 0) {
				$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
				if ($APP->dbpuerto != '') {
					$objDB->dbPuerto = $APP->dbpuerto;
				}
				login_cerrarsesion_v2($_SESSION['unad_id_sesion'], $objDB);
				$objDB->CerrarConexion();
			}
		}
		$objResponse = new xajaxResponse();
		/*
		$objResponse->call("MensajeAlarmaV2(Se ha cerrado la sesi&oacute;n', 2)");
		$objResponse->assign('div_tiempo','innerHTML', '');
		*/
		$objResponse->redirect('salir.php');
		return $objResponse;
	} else {
		//$sMsg='a ver.. '.$ifalta;
		$objResponse = new xajaxResponse();
		if ($sMsg != '') {
			$objResponse->call("MensajeAlarmaV2('" . $sMsg . "', 2)");
		}
		$objResponse->assign('div_tiempo', 'innerHTML', 'tl=' . $ifalta . '' . $sDebug);
		return $objResponse;
	}
}
//Cambiamos la forma como se hace el llamado al salir, vamos a llamar un javascript y ya.
function Sesion_Abandona_V2022()
{
	if (isset($_SESSION['unad_id_tercero']) == 0) {
		$_SESSION['unad_id_tercero'] = 0;
	}
	$ifalta = -1;
	$sMsg = '';
	$sDebug = '';
	if ($_SESSION['unad_id_tercero'] != 0) {
		require './app.php';
		//la funcion original esta en todas.php, se saca el minuto de la semana.
		$iavance = (date("W") * 1440) + (date("H") * 60) + date("i");
		if (isset($_SESSION['unad_tiempo_sesion']) == 0) {
			$_SESSION['unad_tiempo_sesion'] = 20;
		}
		if (isset($APP->tiempolimite) != 0) {
			if ($APP->tiempolimite > $_SESSION['unad_tiempo_sesion']) {
				$_SESSION['unad_tiempo_sesion'] = $APP->tiempolimite;
			}
		}
		$itiempomax = $_SESSION['unad_tiempo_sesion'];
		//$itiempomax = 3; // -- Esta linea es para cuando estamo probando.
		$ibase = -$itiempomax;
		if (isset($_SESSION['u_ultimominuto']) != 0) {
			$ibase = $_SESSION['u_ultimominuto'];
		}
		$ifalta = ($ibase + $itiempomax) - $iavance;
		if ($ifalta > 0) {
			//si falta menos de un minuto mover la sesion... 
			//Esto no mueve variables de sesion, solo informa a la db que hasta esta hora se mantuvo el usuario.
			$sDebug = sesion_actualizar_v2(NULL, false);
			//marcar el tiempo que falta...
			if ($ifalta < 4) {
				$sScript = ', <a href="javascript:mantener_sesion();" class="resalte"><span style="color:#FFFF99">o haga clic aqu&iacute; para mantenerse en el sistema</span></a>';
				$sMsg = $ifalta . ' minutos para cerrar la sesi&oacute;n' . $sScript;
			}
		}
	}
	if ($ifalta < 1) {
		if (isset($_SESSION['unad_id_sesion']) != 0) {
			if ((int)$_SESSION['unad_id_sesion'] != 0) {
				$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
				if ($APP->dbpuerto != '') {
					$objDB->dbPuerto = $APP->dbpuerto;
				}
				login_cerrarsesion_v2($_SESSION['unad_id_sesion'], $objDB);
				$objDB->CerrarConexion();
			}
		}
		$objResponse = new xajaxResponse();
		$objResponse->call('salir()');
		return $objResponse;
	} else {
		$objResponse = new xajaxResponse();
		if ($sMsg != '') {
			$objResponse->call("MensajeAlarmaV2('" . $sMsg . "', 2)");
		}
		$objResponse->assign('div_tiempo', 'innerHTML', 'tl=' . $ifalta . '' . $sDebug);
		return $objResponse;
	}
}
// Marzo 6 de 2015 en lugar de sacar de la pagina de login.
function sesion_retomar($std, $sid, $spw)
{
	$sid = htmlspecialchars($sid);
	require './app.php';
	if (isset($_SESSION['u_doctercero']) == 0) {
		$_SESSION['u_doctercero'] = '';
	}
	if ($_SESSION['u_doctercero'] == $sid) {
		$respuesta = 'pasa';
	} else {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		if (isset($APP->idsistema) == 0) {
			$APP->idsistema = 10;
		}
		$respuesta = login_validar_v3($std, $sid, $spw, $APP->idsistema, $objDB);
	}
	$objResponse = new xajaxResponse();
	if ($respuesta == 'pasa') {
		$objResponse->call('expandesector(1)');
		$objResponse->assign('ensesion', 'value', '1');
		$objResponse->assign('alarma', 'innerHTML', '');
		$objResponse->assign('txtclave', 'value', '');
		$objResponse->call('mantener_sesion()');
	} else {
		$objResponse->assign('alarma', 'innerHTML', $respuesta);
	}
	return $objResponse;
}
function sesion_retomarV4($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$std = $datos[1];
	$sid = htmlspecialchars($datos[2]);
	$spw = $datos[3];
	$idSistema = $datos[4];
	require './app.php';
	if (isset($_SESSION['u_doctercero']) == 0) {
		$_SESSION['u_doctercero'] = '';
	}
	if ($_SESSION['u_doctercero'] == $sid) {
		$respuesta = 'pasa';
	} else {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$respuesta = login_validar_v3($std, $sid, $spw, $idSistema, $objDB);
	}
	$objResponse = new xajaxResponse();
	if ($respuesta == 'pasa') {
		$_SESSION['u_ultimominuto'] = iminutoavance();
		$objResponse->assign('ensesion', 'value', '1');
		$objResponse->assign('txtclave', 'value', '');
		$objResponse->assign('div_tiempo', 'innerHTML', '');
		$objResponse->call('expandesector(1)');
		$objResponse->call('MensajeAlarmaV2("", 0)');
	} else {
		$objResponse->call('MensajeAlarmaV2("' . $respuesta . '", 0)');
	}
	return $objResponse;
}
// Enero 31 de 2015 
function sesion_mantener()
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$objResponse = new xajaxResponse();
	$objResponse->assign('alarma', 'innerHTML', '');
	$objResponse->assign('div_tiempo', 'innerHTML', '');
	return $objResponse;
}
function sesion_mantenerV4()
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$objResponse = new xajaxResponse();
	$objResponse->call('MensajeAlarmaV2("", 0)');
	$objResponse->assign('div_tiempo', 'innerHTML', '');
	return $objResponse;
}
// Junio 8 de 2015 se delega a la sys11_Mostrar_v2
function unad11_mostrar($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	//if ($tipodoc!='CE'){$doc=solonumeros($doc);}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$tipodoc = $aParametros[0];
	$doc = $aParametros[1];
	$objid = $aParametros[2];
	$sdiv = $aParametros[3];
	$respuesta = '';
	$id = 0;
	if ($doc != '') {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		list($respuesta, $id) = tabla_terceros_info($tipodoc, $doc, $objDB);
		if ($respuesta == '') {
			//IMPORTACION AUTOMATICA DE TERCEROS - MARZO 19 DE 2014
			// Ver si esta en la tabla mdl_user
			// -- Junion 10 de 2014 - Se agrega el campo unad11idncontents
			if ($tipodoc == 'CC') {
				unad11_importar_V2($doc, '', $objDB);
				list($respuesta, $id) = tabla_terceros_info($tipodoc, $doc, $objDB);
			}
			//-- FIN DE LA IMPORTACION AUTOMATICA
			if ($respuesta == '') {
				$respuesta = '<font class="rojo">{' . $tipodoc . ' ' . $doc . ' No encontrado}</font>';
			}
		}
		$objDB->CerrarConexion();
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $respuesta);
	$objResponse->assign($objid . '_doc', 'value', $doc);
	$objResponse->assign($objid, 'value', $id);
	return $objResponse;
}
function unad11_importar_V2($sDoc, $sUserCampus, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sRutaApp = './app.php';
	if (!file_exists($sRutaApp)) {
		$sDirBase = __DIR__ . '/';
		$sRutaApp = $sDirBase . 'app.php';
	}
	require $sRutaApp;
	$idEntidad = 0;
	if (isset($APP->entidad) != 0) {
		if ($APP->entidad == 1) {
			$idEntidad = 1;
		}
	}
	//Febrero 24 de 2023 --- Se ajusta para evitar inyeccion de codiogo por el documento
	$sVerifica = cadena_Validar($sDoc);
	if ($sVerifica != $sDoc) {
		sleep(5);
		return array($sError, $sDebug);
		die();
	}
	//Enero 11 de 2023 - Si intentan importar un documento que ya existe aunque sea de otro tipo se detiene el proceso.
	if ($sDoc != '') {
		$sSQL = 'SELECT 1 FROM unad11terceros WHERE unad11doc="' . $sDoc . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			return array($sError, $sDebug);
			die();
		}
	}

	$idRolUnad = 0;
	$bDBExterna = false;
	$bExiste = false;
	$bInterCambio = false;
	$iVersionOrigen = 1;
	$unad11genero = '';
	$unad11fechanace = '00/00/0000';
	$unad11direccion = '.';
	$unad01estrato = 0;
	$unad11fechadoc = 0;
	$unad11idrca = 0;
	$unad11fecharca = 0;
	$iHoy = fecha_DiaMod();
	$sSQL = 'SET CHARACTER SET utf8';
	$tabla = $objDB->ejecutasql($sSQL);
	switch ($idEntidad) {
		case 1: //UNAD Florida.
			if (isset($APP->dbhostcampus) != 0) {
				$sModelo = 'M';
				if (isset($APP->dbmodelocampus) != 0) {
					if ($APP->dbmodelocampus == 'O') {
						$sModelo = 'O';
					}
					if ($APP->dbmodelocampus == 'P') {
						$sModelo = 'P';
					}
				}
				$objDBOrigen = new clsdbadmin($APP->dbhostcampus, $APP->dbusercampus, $APP->dbpasscampus, $APP->dbnamecampus, $sModelo);
				if ($APP->dbpuertocampus != '') {
					$objDBOrigen->dbPuerto = $APP->dbpuertocampus;
				}
				$bDBExterna = true;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Conectando con base de datos externa [' . $APP->dbnamecampus . ']<br>';
				}
				if ($sDoc != '') {
					//$sWhere='idnumber="'.$sDoc.'"';
					$sWhere = 'nid="' . $sDoc . '"';
				} else {
					//$sWhere='username="'.$sUserCampus.'"';
					$sWhere = 'susername="' . $sUserCampus . '"';
				}
				$sSQL = 'SELECT nid AS id, susername AS username, nid AS idnumber, sname AS firstname, snlast AS lastname, semail AS email, 
			spassword AS password, sphone AS phone1 
			FROM users WHERE ' . $sWhere;
			} else {
				$objDBOrigen = $objDB;
				$sSQL = 'SELECT 1 FROM unad11terceros WHERE unad11id=-9999';
			}
			break;
		default: //UNAD Colombia.
			if (isset($APP->dbhostryc) != 0) {
				$objDBOrigen = new clsdbadmin($APP->dbhostryc, $APP->dbuserryc, $APP->dbpassryc, $APP->dbnameryc);
				if ($APP->dbpuertoryc != '') {
					$objDBOrigen->dbPuerto = $APP->dbpuertoryc;
				}
				if ($objDBOrigen->Conectar()) {
					$bDBExterna = true;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Conectando con base de datos externa [' . $APP->dbnamecampus . ']<br>';
					}
				} else {
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Error al intentar conectar datos externa [' . $objDBOrigen->serror . ']<br>';
					}
				}
			}
			if ($bDBExterna) {
				if ($sDoc != '') {
					//$sWhere='idnumber="'.$sDoc.'"';
					$sWhere = 'documento="' . $sDoc . '"';
				} else {
					//$sWhere='username="'.$sUserCampus.'"';
					$sWhere = 'usuario="' . $sUserCampus . '"';
				}
				//$sSQL='SELECT id, username, idnumber, firstname, lastname, email, password, phone1 FROM mdl_user WHERE '.$sWhere.'';
				/*
			$sSQL='SELECT 0 AS id, usuario AS username, documento AS idnumber, nombres AS firstname, apellidos AS lastname, correo_e AS email, 
			clave AS password, telefono AS phone1, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, 
			fecha_expedicion, sexo, estrato, estado_civil, YEAR(fecha_nacimiento) AS Agno_Nace, MONTH(fecha_nacimiento) AS Mes_Nace, 
			DAY(fecha_nacimiento) AS Dia_Nace, YEAR(fecha_expedicion) AS Agno_Doc, MONTH(fecha_expedicion) AS Mes_Doc, 
			DAY(fecha_expedicion) AS Dia_Doc 
			FROM usuarios_sii WHERE '.$sWhere.'';
			*/
				$sSQL = 'SELECT 0 AS id, usuario AS username, documento AS idnumber, nombres AS firstname, apellidos AS lastname, correo_e AS email, 
			clave AS password, telefono AS phone1 
			FROM usuarios_sii WHERE ' . $sWhere . '';
				$iVersionOrigen = 2;
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando DB Origen '.$sSQL.'<br>';}
			} else {
				$objDBOrigen = $objDB;
				$sSQL = 'SELECT 1 FROM unad11terceros WHERE unad11id=-9999';
			}
			break;
	}
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de origen: ' . $sSQL . '<br>';
	}
	$tabla = $objDBOrigen->ejecutasql($sSQL);
	if ($objDBOrigen->nf($tabla) == 0) {
		if ($iVersionOrigen == 2) {
			if ($sDoc != '') {
				//Septiembre 23 de 2021 - No le han creado el usuario...
				$sSQL = 'SELECT 0 AS id, CONCAT("CC", documento) AS username, documento AS idnumber, primer_nombre AS firstname, 
				primer_apellido AS lastname, correo1 AS email, "" AS password, telefono_movil AS phone1 
				FROM preinscripcion WHERE documento=' . $sDoc . ' ORDER BY fecha_grab DESC';
				$tabla = $objDBOrigen->ejecutasql($sSQL);
			}
		}
	}
	if ($objDBOrigen->nf($tabla) > 0) {
		$fila = $objDBOrigen->sf($tabla);
		$idMoodle = $fila['id'];
		$idUsuario = $fila['username'];
		$sDoc = $fila['idnumber'];
		$nombres = substr($fila['firstname'], 0, 30);
		$sNombre2 = '';
		$apellidos = substr($fila['lastname'], 0, 30);
		$sApellido2 = '';
		$correo = substr($fila['email'], 0, 50);
		$sTelefono = $fila['phone1'];
		$sClave = $fila['password'];
		if ($iVersionOrigen == 2) {
			$sSQL = 'SELECT primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, genero, estrato, direccion_res, paiso, 
			YEAR(fecna) AS Agno_Nace, MONTH(fecna) AS Mes_Nace, DAY(fecna) AS Dia_Nace, fechaexpedicion  
			FROM preinscripcion WHERE documento=' . $sDoc . ' ORDER BY fecha_grab DESC';
			//fecha_nacimiento, fecha_expedicion, ,, estado_civil
			//Agno_Nace, Mes_Nace, Dia_Nace, Agno_Doc, Mes_Doc, Dia_Doc
			$tabla2 = $objDBOrigen->ejecutasql($sSQL);
			if ($objDBOrigen->nf($tabla2) > 0) {
				$fila2 = $objDBOrigen->sf($tabla2);
				$nombres = substr($fila2['primer_nombre'], 0, 30);
				$sNombre2 = substr($fila2['segundo_nombre'], 0, 30);
				$apellidos = substr($fila2['primer_apellido'], 0, 30);
				$sApellido2 = substr($fila2['segundo_apellido'], 0, 30);
				$unad11genero = $fila2['genero'];
				$unad01estrato = $fila2['estrato'];
				$unad11direccion = cadena_reemplazar($fila2['direccion_res'], '"', '');
				$unad11idrca = $sDoc;
				$unad11fecharca = $iHoy;
				$unad11fechanace = fecha_armar($fila2['Dia_Nace'], $fila2['Mes_Nace'], $fila2['Agno_Nace']);
				//$unad11fechadoc=fecha_ArmarNumero($fila['Dia_Doc'], $fila['Mes_Doc'], $fila['Agno_Doc']);
			}
		}
		$razonsocial = substr(trim($nombres . ' ' . $sNombre2) . ' ' . trim($apellidos . ' ' . $sApellido2), 0, 100);
		$bExiste = true;
		$sSQLConsulta = $sSQL;
		//Verificamos si el usuario existe entonces es cuestion de que pudo haber actualizado el documento.
		$sSQL = 'SELECT unad11id, unad11doc FROM unad11terceros WHERE unad11usuario="' . $idUsuario . '"';
		$tabla11 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla11) > 0) {
			$fila11 = $objDB->sf($tabla11);
			$sError = 'El usuario ' . $idUsuario . ' ya existe.';
			if ($fila11['unad11doc'] != $sDoc) {
				$sWhere = 'Se cambia el documento  de ' . $fila11['unad11doc'] . ' a ' . $sDoc . ' con base en ' . $sSQLConsulta . ' desde ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				if (isset($_SESSION['unad_id_tercero']) == 0) {
					$_SESSION['unad_id_tercero'] = 0;
				}
				// Se le cambia el documento.
				$sSQL = 'UPDATE unad11terceros SET unad11doc="' . $sDoc . '" WHERE unad11id=' . $fila11['unad11id'];
				$tabla11 = $objDB->ejecutasql($sSQL);
				seg_auditar(111, $_SESSION['unad_id_tercero'], 3, $fila11['unad11id'], $sWhere, $objDB);
			}
		}
	}
	if (!$bExiste) {
		//Puede que este en la tabla de intercambio..
		$sSQL = 'SELECT * FROM cort01infopersona WHERE cort01numerodoc="' . $sDoc . '" AND cort01tipodoc=1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idUsuario = 'CC' . $fila['cort01numerodoc'];
			$sDoc = $fila['cort01numerodoc'];
			$idMoodle = 0;
			$nombres = substr($fila['cort01primernom'], 0, 30);
			$sNombre2 = substr($fila['cort01segundonom'], 0, 30);;
			$apellidos = substr($fila['cort01primerapellido'], 0, 30);
			$sApellido2 = substr($fila['cort01segundoapellido'], 0, 30);
			$razonsocial = substr($fila['cort01primernom'] . ' ' . $fila['cort01segundonom'] . ' ' . $fila['cort01primerapellido'] . ' ' . $fila['cort01segundoapellido'], 0, 100);
			$correo = substr($fila['cort01correopersonal'], 0, 50);
			$sTelefono = $fila['cort01telefono'];
			$bExiste = true;
			$idRolUnad = 0;
			$sClave = '';
			$bInterCambio = true;
			$inter_tipo = $fila['cort01tipodoc'];
			$inter_id = $fila['cort01sii_id'];
			$inter_proceso = $fila['cort01sii_fechaproceso'];
			//cort01tipodoc, cort01numerodoc, cort01primernom, cort01segundonom, cort01primerapellido, cort01segundoapellido, cort01direccion, cort01telefono, cort01correopersonal, cort01fechanace, cort01fechacrea, cort01fechaactualiza
		}
	}
	if (!$bExiste) {
		$sError = 'No se ha encontrado el documento ' . $sDoc . '.';
	}
	if ($sError == '') {
		$scampos = 'unad11tipodoc, unad11doc, unad11id, unad11pais, unad11usuario, 
		unad11dv, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, 
		unad11genero, unad11fechanace, unad11rh, unad11ecivil, unad11razonsocial, 
		unad11direccion, unad11telefono, unad11correo, unad11sitioweb, unad11nacionalidad, 
		unad11deptoorigen, unad11ciudadorigen, unad11deptodoc, unad11ciudaddoc, unad11clave, 
		unad11idmoodle, unad11idncontents, unad11iddatateca, unad11idcampus, unad11claveapps, 
		unad11fechacrea, unad11mincrea, unad11rolunad, unad11fechaclave, unad11idrca, 
		unad11fecharca, unad01estrato, unad11fechadoc';
		$sSQL = 'SELECT MAX(unad11id) FROM unad11terceros';
		$tablaid = $objDB->ejecutasql($sSQL);
		$filaid = $objDB->sf($tablaid);
		$id11 = $filaid[0] + 1;
		$idMoodle=$id11;
		$unad11fechacrea = $iHoy;
		$unad11mincrea = fecha_MinutoMod();
		$sPais = '057';
		if ($idEntidad == 1) {
			$sPais = '001';
		}
		$svalores = '"CC", "' . $sDoc . '", ' . $id11 . ', "' . $sPais . '", "' . $idUsuario . '", 
		"", "' . $nombres . '", "' . $sNombre2 . '", "' . $apellidos . '", "' . $sApellido2 . '", 
		"' . $unad11genero . '", "' . $unad11fechanace . '", "", "", "' . $razonsocial . '", 
		".", "' . $sTelefono . '", "' . $correo . '", "", "' . $sPais . '", 
		"", "", "", "", "' . $sClave . '", 
		' . $idMoodle . ', ' . $idMoodle . ', ' . $idMoodle . ', ' . $idMoodle . ', "", 
		' . $unad11fechacrea . ', ' . $unad11mincrea . ', ' . $idRolUnad . ', ' . $unad11fechacrea . ', "' . $unad11idrca . '", 
		' . $unad11fecharca . ', ' . $unad01estrato . ', ' . $unad11fechadoc . '';
		$sSQL = 'INSERT INTO unad11terceros (' . $scampos . ') VALUES (' . $svalores . ');';
		$res = $objDB->ejecutasql($sSQL);
		if ($res == false) {
			//Fallo la insersion, posiblemente los nombres vienen mal...
			$nombres = cadena_letrasynumeros(substr($nombres, 0, 30));
			$apellidos = cadena_letrasynumeros(substr($apellidos, 0, 30));
			$razonsocial = $nombres . ' ' . $apellidos;
			$svalores = '"CC", "' . $sDoc . '", ' . $id11 . ', "' . $sPais . '", "' . $idUsuario . '", 
			"", "' . $nombres . '", "", "' . $apellidos . '", "", 
			"' . $unad11genero . '", "' . $unad11fechanace . '", "", "", "' . $razonsocial . '", 
			"' . $unad11direccion . '", "' . $sTelefono . '", "' . $correo . '", "", "' . $sPais . '", 
			"", "", "", "", "' . $sClave . '", 
			' . $idMoodle . ', ' . $idMoodle . ', ' . $idMoodle . ', ' . $idMoodle . ', "", 
			' . $unad11fechacrea . ', ' . $unad11mincrea . ', ' . $idRolUnad . ', ' . $unad11fechacrea . ', "' . $unad11idrca . '", 
			' . $unad11fecharca . ', ' . $unad01estrato . ', ' . $unad11fechadoc . '';
			$sSQL = 'INSERT INTO unad11terceros (' . $scampos . ') VALUES (' . $svalores . ');';
			$res = $objDB->ejecutasql($sSQL);
		}
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de insersion: ' . $sSQL . '<br>';
		}
		if ($bInterCambio) {
			//$inter_tipo=$fila['cort01tipodoc'];
			//$inter_id=$fila['cort01sii_id'];
			//$inter_proceso=$fila['cort01sii_fechaproceso'];
			$sSQL = 'UPDATE cort01infopersona SET cort01sii_id=' . $id11 . ', cort01sii_fechaproceso=' . $inter_proceso . ' WHERE cort01numerodoc="' . $sDoc . '" AND cort01tipodoc=' . $inter_tipo . '';
			$tabla = $objDB->ejecutasql($sSQL);
		}
	}
	if ($bDBExterna) {
		$objDBOrigen->CerrarConexion();
	}
	return array($sError, $sDebug);
}
function unad11_Mostrar_v2($aParametros)
{
	//Septiembre 9 de 2020 - Se agrega el parametro especial incialmente para incluir necesidades especiales
	$_SESSION['u_ultimominuto'] = iminutoavance();
	//if ($tipodoc!='CE'){$doc=solonumeros($doc);}
	$respuesta = '';
	$id = 0;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$tipodoc = $aParametros[0];
	$doc = trim($aParametros[1]);
	$objid = $aParametros[2];
	$sdiv = $aParametros[3];
	$bXajax = true;
	if (isset($aParametros[4]) == 0) {
		$aParametros[4] = '';
	}
	if (isset($aParametros[5]) == 0) {
		$aParametros[5] = '';
	}
	if (isset($aParametros[6]) == 0) {
		$aParametros[6] = 0;
	}
	if ($doc != '') {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$iEspecial = (int)$aParametros[6];
		list($respuesta, $id, $tipodoc, $doc) = html_tercero($tipodoc, $doc, 0, 1, $objDB, $iEspecial);
		if ($respuesta == '') {
			//IMPORTACION AUTOMATICA DE TERCEROS - MARZO 19 DE 2014
			// Ver si esta en la tabla mdl_user
			if ($tipodoc == 'CC') {
				unad11_importar_V2($doc, '', $objDB);
				list($respuesta, $id) = tabla_terceros_info($tipodoc, $doc, $objDB);
			}
			//-- FIN DE LA IMPORTACION AUTOMATICA
			if ($respuesta == '') {
				$respuesta = '<font class="rojo">{' . $tipodoc . ' ' . $doc . ' No encontrado}</font>';
			}
		}
	} else {
		$respuesta = '&nbsp;';
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', cadena_codificar($respuesta));
	$objResponse->assign($objid . '_doc', 'value', $doc);
	$objResponse->assign($objid, 'value', $id);
	if ($id == 0) {
		if ($aParametros[5] != '') {
			$objResponse->call($aParametros[5]);
		}
	} else {
		if ($aParametros[4] != '') {
			$objResponse->call($aParametros[4]);
		}
	}
	return $objResponse;
}
function unad11_TraerXid($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$respuesta = '';
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$idtercero = $aParametros[0];
	$tipodoc = 'CC';
	$doc = '';
	$objid = $aParametros[1];
	// El parametro 4 es la funciona que se llamará si esta el tercero y la 5 la se llamara en caso de que no.
	if (isset($aParametros[4]) == 0) {
		$aParametros[4] = '';
	}
	if (isset($aParametros[5]) == 0) {
		$aParametros[5] = '';
	}
	if (isset($aParametros[6]) == 0) {
		$aParametros[6] = 0;
	}
	if ((int)$idtercero != 0) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		list($id, $tipodoc, $doc, $respuesta) = tabla_terceros_traer($idtercero, '', '', $objDB);
		if ($respuesta == '') {
			$idtercero = 0;
			$tipodoc = $APP->tipo_doc;
			$respuesta = '<font class="rojo">{Ref: ' . $idtercero . ' No encontrado}</font>';
		}
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign($objid, 'value', $idtercero);
	$objResponse->assign($objid . '_td', 'value', $tipodoc);
	$objResponse->assign($objid . '_doc', 'value', $doc);
	$objResponse->assign('div_' . $objid, 'innerHTML', $respuesta);
	if ($id == 0) {
		if ($aParametros[5] != '') {
			$objResponse->call($aParametros[5]);
		}
	} else {
		if ($aParametros[4] != '') {
			$objResponse->call($aParametros[4]);
		}
	}
	return $objResponse;
}
function unad11_ActualizarOculto($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$respuesta = '';
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$idtercero = $aParametros[0];
	$tipodoc = 'CC';
	$doc = '';
	$objid = $aParametros[1];
	if (isset($aParametros[6]) == 0) {
		$aParametros[6] = 0;
	}
	if ((int)$idtercero != 0) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		list($id, $tipodoc, $doc, $respuesta) = tabla_terceros_traer($idtercero, '', '', $objDB);
		if ($respuesta == '') {
			$idtercero = 0;
			$tipodoc = $APP->tipo_doc;
			$respuesta = '<font class="rojo">{Ref: ' . $idtercero . ' No encontrado}</font>';
		}
	}
	$sRes = html_oculto($objid . '_td', $tipodoc) . ' ' . html_oculto($doc . '_doc', $doc);
	$sRes = '<label class="Label350">' . $sRes . '</label>';
	$objResponse = new xajaxResponse();
	$objResponse->assign($objid, 'value', $idtercero);
	$objResponse->assign('div_' . $objid . '_llaves', 'innerHTML', $sRes);
	$objResponse->assign('div_' . $objid, 'innerHTML', $respuesta);
	return $objResponse;
}
function usuario_OpcionGuardar($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (isset($datos[2]) == 0) {
		$datos[2] = 0;
	}
	if (isset($datos[3]) == 0) {
		$datos[3] = '';
	}
	if (isset($datos[4]) == 0) {
		$datos[4] = '';
	}
	$cod_modulo = $datos[0];
	$cod_opcion = $datos[1];
	$ivr = $datos[2];
	$svr = $datos[3];
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sSQLinsert = '';
	$sSQL = 'SELECT unad59svalor, unad59ivalor 
	FROM unad59params 
	WHERE unad59idtercero=' . $_SESSION['unad_id_tercero'] . ' AND unad59idmodulo=' . $cod_modulo . ' AND unad59idpreferencia=' . $cod_opcion . '';
	$result = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($result) > 0) {
		$fila = $objDB->sf($result);
		if (!(($fila['unad59svalor'] == $svr) && ($fila['unad59ivalor'] == $ivr))) {
			$sSQLinsert = 'UPDATE unad59params SET unad59svalor="' . $svr . '", unad59ivalor=' . $ivr . ' WHERE unad59idtercero=' . $_SESSION['unad_id_tercero'] . ' AND unad59idmodulo=' . $cod_modulo . ' AND unad59idpreferencia=' . $cod_opcion . ';';
		}
	} else {
		$sSQLinsert = 'INSERT INTO unad59params (unad59idtercero, unad59idmodulo, unad59idpreferencia, unad59svalor, unad59ivalor) VALUES (' . $_SESSION['unad_id_tercero'] . ', ' . $cod_modulo . ', ' . $cod_opcion . ', "' . $svr . '", ' . $ivr . ');';
	}
	if ($sSQLinsert != '') {
		$hhhh = $objDB->ejecutasql($sSQLinsert);
	}
	if ($datos[4] != '') {
		//Hacer el llamado a la funcion javascript
		$objResponse = new xajaxResponse();
		$objResponse->call($datos[4]);
		return $objResponse;
	}
}

