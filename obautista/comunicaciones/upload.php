<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2025 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.15 miércoles, 30 de abril de 2025
*/

/** Archivo index.php.
 * Modulo 17 Index.
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date miércoles, 30 de abril de 2025
 */
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$sDebug = '';
if (isset($_REQUEST['debug']) != 0) {
	if ($_REQUEST['debug'] == 1) {
		$bDebug = true;
	}
} else {
	$_REQUEST['debug'] = 0;
}
if ($bDebug) {
	$iSegIni = microtime(true);
	$iSegundos = floor($iSegIni);
	$sMili = floor(($iSegIni - $iSegundos) * 1000);
	if ($sMili < 100) {
		if ($sMili < 10) {
			$sMili = ':00' . $sMili;
		} else {
			$sMili = ':0' . $sMili;
		}
	} else {
		$sMili = ':' . $sMili;
	}
	$sDebug = $sDebug . date('H:i:s') . $sMili . ' Inicia pagina <br>';
}
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun . 'unad_sesion.php';
if (isset($APP->https) == 0) {
	$APP->https = 0;
}
if ($APP->https == 2) {
	$bObliga = false;
	if (isset($_SERVER['HTTPS']) == 0) {
		$bObliga = true;
	} else {
		if ($_SERVER['HTTPS'] != 'on') {
			$bObliga = true;
		}
	}
	if ($bObliga) {
		$pageURL = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		header('Location:' . $pageURL);
		die();
	}
}
/*
if (!file_exists('./opts.php')) {
	require './opts.php';
	if ($OPT->opcion == 1) {
		$bOpcion = true;
	}
}
*/
$bPeticionXAJAX = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['xjxfun'])) {
		$bPeticionXAJAX = true;
	}
}
if (!$bPeticionXAJAX) {
	$_SESSION['u_ultimominuto'] = (date('W') * 1440) + (date('H') * 60) + date('i');
}
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libcomp.php';
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_forma_campus.php';
require $APP->rutacomun . 'unad_xajax.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 8727;
$iCodModulo = 1205;
$iCodModuloConsulta = $iCodModulo;
$sIdioma = AUREA_Idioma();
$audita[1] = false;
$audita[2] = false;
$audita[3] = false;
$audita[4] = true;
$audita[5] = false;
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
require $mensajes_todas;
if ($_SESSION['unad_id_tercero'] == 0) {
	die();
}
$_SESSION['u_ultimominuto'] = iminutoavance();
if (isset($_REQUEST['u']) == 0) {
	echo 'No se ha definido la referencia que hace el llamado.';
	die();
} else {
	$ver_u = cadena_Validar($_REQUEST['u']);
	if ($ver_u != $_REQUEST['u']) {
		die ();
	}
}
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
$bPasa = false;
$sError = '';
$iTipoError = 0;
//definir la dborigen.
$sSQL = 'SELECT unad50id FROM unad50dbalterna WHERE unad50actual="S"';
$result = $objDB->ejecutasql($sSQL);
if ($objDB->nf($result) > 0) {
	$fila = $objDB->sf($result);
	$_REQUEST['dborigen'] = $fila['unad50id'];
	$bPasa = true;
}
if (isset($_REQUEST['dborigen']) == 0) {
	$_REQUEST['dborigen'] = 0;
}
if (isset($_REQUEST['unad51consec']) == 0) {
	$_REQUEST['unad51consec'] = "";
}
if (isset($_REQUEST['unad51id']) == 0) {
	$_REQUEST['unad51id'] = "";
}
if (isset($_REQUEST['unad51mime']) == 0) {
	$_REQUEST['unad51mime'] = "";
}
if (isset($_REQUEST['unad51nombre']) == 0) {
	$_REQUEST['unad51nombre'] = "";
}
if (isset($_REQUEST['unad51detalle']) == 0) {
	$_REQUEST['unad51detalle'] = "";
}
if ($bPasa) {
	$objArchivos = DBalterna_Traer($_REQUEST['dborigen'], $objDB);
	$sTabla = 'unad51archivos';
	if ($_REQUEST['dborigen'] != 0) {
		if ($objArchivos->bexistetabla('unad50dbalterna')) {
			$sTabla = 'unad51archivos_' . $_REQUEST['dborigen'];
		}
	}
	if ($_REQUEST['unad51consec'] == '') {
		$_REQUEST['unad51consec'] = tabla_consecutivo($sTabla, 'unad51consec', '', $objArchivos);
	}
	$sSQL = 'SELECT unad51id FROM ' . $sTabla . ' WHERE unad51consec=' . $_REQUEST['unad51consec'] . '';
	$result = $objArchivos->ejecutasql($sSQL);
	if ($objArchivos->nf($result) != 0) {
		$sError = 'El codigo de archivo ya existe.';
	}
}
if ($sError == '') {
	$bsubiendo = false;
	if ($sError == '') {
		$ext = '';
		$sPrevioNombre = $_REQUEST['unad51nombre'];
		$sPrevioDetalle = $_REQUEST['unad51detalle'];
		$sPrevioMime = $_REQUEST['unad51mime'];
		$_REQUEST['unad51nombre'] = $_FILES['unad51archivo']['name'];
		$_REQUEST['unad51mime'] = $_FILES['unad51archivo']['type'];
		if ($_REQUEST['unad51nombre'] != '') {
			if ($_REQUEST['unad51detalle'] == '') {
				$_REQUEST['unad51detalle'] = $_REQUEST['unad51nombre'];
			}
			if ($_REQUEST['unad51mime'] == "") {
				//NO SE RECONOCE EL MIME.
				$punto = strpos(".", $_REQUEST['unad51nombre']);
				echo $punto;
			}
			switch ($_REQUEST['unad51mime']) {
				case "image/bmp":
					$ext = '.bmp';
					$bsubiendo = true;
					break;
				case "image/gif":
					$ext = '.gif';
					$bsubiendo = true;
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$ext = '.jpg';
					$bsubiendo = true;
					break;
				case "image/tiff":
					$ext = '.tif';
					$bsubiendo = true;
					break;
				case "image/x-png":
				case "image/png":
					$ext = '.png';
					$bsubiendo = true;
					break;
				case "application/pdf":
					$ext = '.pdf';
					if (!$bimage) {
						$bsubiendo = true;
					}
					break;
				case "application/msword":
					$ext = '.doc';
					if (!$bimage) {
						$bsubiendo = true;
					}
					break;
				case "text/plain":
					$ext = '.txt';
					if (!$bimage) {
						$bsubiendo = true;
					}
					break;
				case "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
					$ext = '.docx';
					if (!$bimage) {
						$bsubiendo = true;
					}
					break;
				case "application/excel";
				case "application/vnd.ms-excel";
					$ext = '.xls';
					if (!$bimage) {
						$bsubiendo = true;
					}
					break;
				case "application/vnd.ms-excel.";
				case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
					$ext = '.xlsx';
					if (!$bimage) {
						$bsubiendo = true;
					}
					break;
				case "application/vnd.openxmlformats-officedocument.presentationml.slideshow";
					$ext = '.ppsx';
					if (!$bimage) {
						$bsubiendo = true;
					}
					break;
				case 'audio/mpeg';
					$ext = '.mp3';
					if (!$bimage) {
						$bsubiendo = true;
					}
					break;
				case 'application/x-download';
					$arrext = explode('.', $_FILES['unad51archivo']['name']);
					$ext = '.' . end($arrext);
					switch (strtolower($ext)) {
						case '.bmp':
							$_REQUEST['unad51mime'] = 'image/bmp';
							break;
						case '.gif':
							$_REQUEST['unad51mime'] = 'image/gif';
							break;
						case '.tif':
							$_REQUEST['unad51mime'] = 'image/tiff';
							break;
						case '.jpg':
							$_REQUEST['unad51mime'] = 'image/jpg';
							break;
						case '.png':
							$_REQUEST['unad51mime'] = 'image/png';
							break;
						case '.pdf':
							$_REQUEST['unad51mime'] = 'application/pdf';
							break;
						case '.doc':
							$_REQUEST['unad51mime'] = 'application/msword';
							break;
						case '.txt':
							$_REQUEST['unad51mime'] = 'text/plain';
							break;
						case '.docx':
							$_REQUEST['unad51mime'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
							break;
						case '.xls':
							$_REQUEST['unad51mime'] = 'application/excel';
							break;
						case '.xlsx':
							$_REQUEST['unad51mime'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
							break;
						case '.ppsx':
							$_REQUEST['unad51mime'] = 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';
							break;
						default:
							$ext = '';
					}
					if ((!$bimage) && ($ext != '')) {
						$bsubiendo = true;
					}
					break;
			}
			if (!$bsubiendo) {
				$sError = 'El tipo de archivo que intenta subir no es admitido {' . $_REQUEST['unad51mime'] . '}.';
			}
		} else {
			//verificar que este actualizando el detalle 
			if ($_REQUEST['paso'] == 10) {
				$sError = 'No ha seleccionado un archivo a subir.';
			}
		}
	}
	if ($sError == '') {
		$bPasa = false;
		$sHoy = fecha_hoy();
		$iHoy = fecha_DiaMod();
		$_REQUEST['unad51id'] = tabla_consecutivo($sTabla, 'unad51id', '', $objArchivos);
		$scampos = 'unad51consec, unad51id, unad51detalle, unad51fechacreado';
		$svalores = '' . $_REQUEST['unad51consec'] . ', ' . $_REQUEST['unad51id'] . ', "' . $_REQUEST['unad51detalle'] . '", ' . $iHoy . '';
		$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $scampos . ') VALUES (' . $svalores . ');';
		$idAccion = 2;
		$sdetalle = $scampos . ' ¬ ' . $svalores;
		$result = $objArchivos->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'Error critico al tratar de guardar, por favor informe al administrador del sistema.<!-- ' . $sSQL . ' -->';
			$bsubiendo = false;
		}
		if ($bsubiendo) {
			//guardar el archivo ahora si...
			$tmp_name = $_FILES['unad51archivo']['tmp_name'];
			$fp = fopen($tmp_name, 'rb');
			$iTamano = filesize($tmp_name);
			$tarchivo = fread($fp, $iTamano);
			if ($_REQUEST['dborigen'] == 0) {
				$tarchivo = addslashes($tarchivo);
			} else {
				$sSQL = 'SELECT unad50modelo, unad50server, unad50puerto, unad50db, unad50usuario, unad50pwd, unad50hostzona1 FROM unad50dbalterna WHERE unad50id=' . $_REQUEST['dborigen'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) > 0) {
					$fila = $objDB->sf($result);
					switch ($fila['unad50modelo']) {
						case 'D': //Es un directorio en el sistema de archivos..
							$sRuta = str_replace('#', '\\', $fila['unad50server']);
							if (file_exists($sRuta)) {
							} else {
								$sError = 'No se ha encontrado la ruta de guardado de anexos para el contenedor ' . $_REQUEST['dborigen'] . '';
							}
							if ($sError == '') {
								// Ahora revisar la pnemotecnica y grabar el archivo en retomo.
								list($sFolder1, $sFolder2, $sArchivo) = archivos_Carpetas($_REQUEST['unad51id']);
								$sBase = $sRuta . '/' . $sFolder1;
								if (file_exists($sBase)) {
								} else {
									if (!@mkdir($sBase, 0777)) {
										$sError = 'No fue posible crear el directorio de destino, por favor informe al administrador del sistema.';
									}
								}
							}
							if ($sError == '') {
								//Revisasmo el folder 2
								$sBase = $sRuta . '/' . $sFolder1 . '/' . $sFolder2;
								if (file_exists($sBase)) {
								} else {
									if (!@mkdir($sBase, 0777)) {
										$sError = 'No fue posible crear el subdirectorio de destino, por favor informe al administrador del sistema.';
									}
								}
							}
							if ($sError == '') {
								//Ahora si creamos el archivo...
								$sBase = $sRuta . '/' . $sFolder1 . '/' . $sFolder2 . '/' . $sArchivo;
								$gestor = fopen($sBase, 'w');
								fwrite($gestor, $tarchivo);
								fclose($gestor);
								chmod($sBase, 0777);
							}
							$tarchivo = NULL;
							break;
						case 'S': //SFTP - Requiere la libsftp y las llaves de usuario.
							$sServer = $fila['unad50server'];
							$sPuerto = $fila['unad50puerto'];
							$sRutaRemoto = $fila['unad50db'];
							$sUsuario = $fila['unad50usuario'];
							$sClave = $fila['unad50pwd'];
							if (isset($APP->zona) != 0) {
								if ($APP->zona == 1) {
									$sServer = $fila['unad50hostzona1'];
								}
							}
							require $APP->rutacomun . 'libsftp.php';
							$sLlavePublica = $APP->rutacomun . 'llave_' . $sUsuario . '.pub';
							$sLlavePrivada = $APP->rutacomun . 'llave_' . $sUsuario . '.dat';
							if (!file_exists($sLlavePrivada)) {
								$sError = 'No ha sido posible encontrar la llave privada para este contenedor de datos, por favor informe al administrador del sistema.';
							}
							if ($sError == '') {
								if (!file_exists($sLlavePublica)) {
									$sError = 'No ha sido posible encontrar la llave publica para este contenedor de datos, por favor informe al administrador del sistema.';
								}
							}
							if ($sError == '') {
								if (!function_exists('ssh2_connect')) {
									$sError = 'No es posible establecer conexiones ssh desde este servidor (Servicio ssh2_connect no disponible).<br>Por favor informe al administrador del sistema.';
								}
							}
							if ($sError == '') {
								$objSFTP = new SFTP_Servidor($sServer);
								if ($objSFTP->sError == '') {
									$objSFTP->ConenctarConKey($sUsuario, $sLlavePublica, $sLlavePrivada);
								}
								$sError = $objSFTP->sError;
							}
							if ($sError == '') {
								//Ahora verificar que existan las carpetas remotas.
								list($sFolder1, $sFolder2, $sArchivo) = archivos_Carpetas($_REQUEST['unad51id']);
								$objSFTP->CrearDirectorio($sRutaRemoto . $sFolder1 . '/');
								if ($objSFTP->sError == '') {
									$objSFTP->CrearDirectorio($sRutaRemoto . $sFolder1 . '/' . $sFolder2 . '/');
								}
								$sError = $objSFTP->sError;
							}
							if ($sError == '') {
								$sNomRemoto = $sRutaRemoto . $sFolder1 . '/' . $sFolder2 . '/' . $sArchivo;
								//$objSFTP->SubirDatos($tarchivo, $sNomRemoto);
								$objSFTP->uploadFile($tmp_name, $sNomRemoto);
							}
							$tarchivo = NULL;
							break;
						default: //Deberia ser una base de datos
							$tarchivo = addslashes($tarchivo);
							break;
					}
				}
			}
			fclose($fp);
			if ($sError == '') {
				$iTopeLargo = 90;
				$_REQUEST['unad51nombre'] = cadena_LimpiarNombreArchivo($_REQUEST['unad51nombre']);
				$iLargoNombre = strlen($_REQUEST['unad51nombre']);
				if ($iLargoNombre > $iTopeLargo) {
					//$_REQUEST['unad51nombre'] = substr($_REQUEST['unad51nombre'], 0, $iTopeLargo);
					$sError = 'El nombre del archivo no puede sobrepasar los ' . $iTopeLargo . ' caracteres [Encontrados: ' . $iLargoNombre . '].';
				}
			}
			if ($sError == '') {
				$sSQL = "UPDATE " . $sTabla . " SET unad51nombre='" . $_REQUEST['unad51nombre'] . "', unad51mime='" . $_REQUEST['unad51mime'] . "', unad51archivo='" . $tarchivo . "', unad51fechaupd=" . $iHoy . ", unad51peso=" . $iTamano . " WHERE unad51id=" . $_REQUEST['unad51id'];
				$result = $objArchivos->ejecutasql($sSQL);
				if ($result == false) {
					$sError = 'Se ha producido un error subiendo el contenido del archivo, no fue posible guardar. {' . $iTamano . '}<!-- ' . $objArchivos->serror . ' -->';
					$_REQUEST['unad51nombre'] = $sPrevioNombre;
					$_REQUEST['unad51detalle'] = $sPrevioDetalle;
					$_REQUEST['unad51mime'] = $sPrevioMime;
				} else {
					if ($sError == '') {
						$sError = 'Archivo cargado correctamente.';
						$iTipoError = 1;
					}
				}
			}
		}
	}
}
$objDB->CerrarConexion();
$sRuta = '';
if ($sError == '') {
	if ((int)$_REQUEST['unad51id'] != 0) {
		$sRuta = url_encode($_REQUEST['dborigen'] . '|' . $_REQUEST['unad51id']);
	}
} else {
	$sRuta = $sError;
}
return json_encode(array("sRuta"=>$sRuta));