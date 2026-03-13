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
$iCodModulo = 251;
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
$iPiel=iDefinirPiel($APP, 2);
$sTituloModulo = 'Anexar archivos';
$bhayref = false;
$bimage = false;
$bMultiDB = false;
$idRegistroOrigen = 0;
$bunico = true;
$bUploadVerDetalle = false;
$bUploadVerRuta = false;
$sRaiz = url_decode_simple($ver_u);
$aRaiz = explode('|', $sRaiz);
$iDato = numeros_validar($aRaiz[0]);
$idModuloOrigen = (int)$iDato;
if (isset($aRaiz[1]) != 0) {
	$iDato = numeros_validar($aRaiz[1]);
	$idRegistroOrigen = (int)$iDato;
}
if (($idModuloOrigen == 0) || ($idRegistroOrigen == 0)) {
	die('No se ha definido datos de origen.');
}
switch ($idModuloOrigen) {
	default:
		$bHayAnexo = false;
		$sdeclara = 'libs/defanexo' . $idModuloOrigen . '.php';
		if (file_exists($sdeclara)) {
			$bHayAnexo = true;
		} else {
			$sdeclara = $APP->rutacomun . 'libs/defanexo' . $idModuloOrigen . '.php';
			if (file_exists($sdeclara)) {
				$bHayAnexo = true;
			}
		}
		if ($bHayAnexo) {
			include($sdeclara);
		} else {
			echo 'No se ha definido la informaci&oacute;n del anexo ' . $idModuloOrigen;
			die();
		}
}
$sSQL = '';
if (isset($_REQUEST['paso']) == 0) {
	//traer el id del archivo y el origen....
	$sSQL = 'SELECT ' . $borigen . ', ' . $bidarchivo . ' FROM ' . $btabla . ' WHERE ' . $bidreg . '=' . $idRegistroOrigen;
	$result = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($result) > 0) {
		$fila = $objDB->sf($result);
		$bhayref = true;
		if ($fila[$bidarchivo] != 0) {
			$_REQUEST['dborigen'] = $fila[$borigen];
			$_REQUEST['unad51id'] = $fila[$bidarchivo];
			$_REQUEST['paso'] = 6;
		} else {
			//definir la dborigen.
			$sSQL = 'SELECT unad50id FROM unad50dbalterna WHERE unad50actual="S"';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$fila = $objDB->sf($result);
				$_REQUEST['dborigen'] = $fila['unad50id'];
			}
		}
	}
} else {
	$bhayref = true;
}
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
}
if (!$bhayref) {
	echo 'No es posible hallar el v&iacute;nculo solicitado. ' . $sSQL;
	die();
}
$sError = '';
$iTipoError = 0;
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
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 6)) {
	$bPasa = true;
	if ($_REQUEST['paso'] == 6) {
		$swhere = 'unad51id=' . $_REQUEST['unad51id'] . '';
	} else {
		$swhere = 'unad51consec=' . $_REQUEST['unad51consec'] . '';
		if ((int)$_REQUEST['unad51consec'] == 0) {
			$bPasa = false;
		}
	}
	if ($bPasa) {
		$objArchivos = DBalterna_Traer($_REQUEST['dborigen'], $objDB);
	}
	if ($bPasa) {
		$bExterna = true;
		$sTabla = 'unad51archivos';
		if ($_REQUEST['dborigen'] != 0) {
			if ($objArchivos->bexistetabla('unad50dbalterna')) {
				$sTabla = 'unad51archivos_' . $_REQUEST['dborigen'];
				$bExterna = false;
			}
		}
		$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $swhere;
		$result = $objArchivos->ejecutasql($sSQL);
		if ($objArchivos->nf($result) > 0) {
			$fila = $objArchivos->sf($result);
			$_REQUEST['unad51consec'] = $fila['unad51consec'];
			$_REQUEST['unad51id'] = $fila['unad51id'];
			$_REQUEST['unad51nombre'] = $fila['unad51nombre'];
			$_REQUEST['unad51detalle'] = $fila['unad51detalle'];
			$_REQUEST['unad51mime'] = $fila['unad51mime'];
			//$_REQUEST['unad51archivo']=$fila['unad51archivo'];
			$bcargo = true;
			$_REQUEST['paso'] = 2;
		} else {
			$_REQUEST['paso'] = 0;
		}
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$_REQUEST['unad51consec'] = numeros_validar($_REQUEST['unad51consec']);
	$_REQUEST['unad51detalle'] = htmlspecialchars($_REQUEST['unad51detalle']);
	if ($sError == '') {
		$objArchivos = DBalterna_Traer($_REQUEST['dborigen'], $objDB);
		if ($sError != '') {
			echo $sError;
			die();
		}
	}
	if ($sError == '') {
		$objDB->bxajax = true;
		$bExterna = true;
		$sTabla = 'unad51archivos';
		if ($_REQUEST['dborigen'] != 0) {
			if ($objArchivos->bexistetabla('unad50dbalterna')) {
				$sTabla = 'unad51archivos_' . $_REQUEST['dborigen'];
				$bExterna = false;
			}
		}
		if ($_REQUEST['paso'] == 10) {
			if ($_REQUEST['unad51consec'] == '') {
				$_REQUEST['unad51consec'] = tabla_consecutivo($sTabla, 'unad51consec', '', $objArchivos);
			}
			$sSQL = 'SELECT unad51id FROM ' . $sTabla . ' WHERE unad51consec=' . $_REQUEST['unad51consec'] . '';
			$result = $objArchivos->ejecutasql($sSQL);
			if ($objArchivos->nf($result) != 0) {
				$sError = 'El codigo de archivo ya existe.';
			} else {
				//if (!$objDB->bhaypermiso($_SESSION['unad_id_tercero'],$iCodModulo,2)){$sError='No tiene permisos para insertar';}
			}
		} else {
			//if (!$objDB->bhaypermiso($_SESSION['unad_id_tercero'],$iCodModulo,3)){$sError='No tiene permisos para guardar';}
		}
	}
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
		if ($_REQUEST['paso'] == 10) {
			$_REQUEST['unad51id'] = tabla_consecutivo($sTabla, 'unad51id', '', $objArchivos);
			$scampos = 'unad51consec, unad51id, unad51detalle, unad51fechacreado';
			$svalores = '' . $_REQUEST['unad51consec'] . ', ' . $_REQUEST['unad51id'] . ', "' . $_REQUEST['unad51detalle'] . '", ' . $iHoy . '';
			$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $scampos . ') VALUES (' . $svalores . ');';
			$idAccion = 2;
			$sdetalle = $scampos . ' ¬ ' . $svalores;
			$bPasa = true;
		} else {
			$swhere = 'unad51id=' . $_REQUEST['unad51id'] . '';
			$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $swhere;
			$sdatos = '';
			$scampo[1] = 'unad51detalle';
			$iNumCampos = 1;
			$result = $objArchivos->ejecutasql($sSQL);
			if ($objArchivos->nf($result) > 0) {
				$fila = $objArchivos->sf($result);
				$bsepara = false;
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($fila[$scampo[$k]] != $_REQUEST[$scampo[$k]]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo[$k] . '="' . $_REQUEST[$scampo[$k]] . '"';
						$bPasa = true;
					}
				}
			}
			$sSQL = 'UPDATE ' . $sTabla . ' SET ' . $sdatos . ' WHERE ' . $swhere . ';';
			$idAccion = 3;
			$sdetalle = $sdatos . ' ¬ ' . $swhere;
		}
		if ($bPasa) {
			$result = $objArchivos->ejecutasql($sSQL);
			if ($result == false) {
				$sError = 'Error critico al tratar de guardar, por favor informe al administrador del sistema.<!-- ' . $sSQL . ' -->';
				$_REQUEST['paso'] = 0;
				$bsubiendo = false;
			} else {
				if ($_REQUEST['paso'] == 10) {
					if ($bunico) {
						//actualizamos el id en el registro origen.
						$sSQL = 'UPDATE ' . $btabla . ' SET ' . $borigen . '=' . $_REQUEST['dborigen'] . ', ' . $bidarchivo . '=' . $_REQUEST['unad51id'] . ' WHERE ' . $bidreg . '=' . $idRegistroOrigen;
						$result = $objDB->ejecutasql($sSQL);
						if ($result == false) {
							$sError = $sError . ' ..<!-- ' . $objDB->serror . ' -->';
						}
					}
				}
				if ($audita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $_REQUEST['unad51id'], $sdetalle, $objDB, $bDebug);
				}
				$_REQUEST['paso'] = 2;
			}
		} else {
			$_REQUEST['paso'] = 2;
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
					$sError = 'El nombre del archivo no puede sobreparar los ' . $iTopeLargo . ' caracteres [Encontrados: ' . $iLargoNombre . '].';
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
			if ($_REQUEST['paso'] == 12) {
				$_REQUEST['paso'] = 2;
			} else {
				$_REQUEST['paso'] = 0;
				if ((int)$_REQUEST['unad51id'] != 0) {
					$_REQUEST['paso'] = 2;
				}
			}
		}
	} else {
		$_REQUEST['paso'] = 2;
	} //fin de si no hay error.
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $_SESSION['unad_id_tercero'], $objDB);
		if (!$bDevuelve) {
			$sError = 'No tiene permiso para eliminar';
			$_REQUEST['paso'] = 2;
		} else {
			$sTabla = 'unad51archivos';
			if ($_REQUEST['dborigen'] != 0) {
				if ($objArchivos->bexistetabla('unad50dbalterna')) {
					$sTabla = 'unad51archivos_' . $_REQUEST['dborigen'];
					$bExterna = false;
				}
			}
			$swhere = 'unad51consec=' . $_REQUEST['unad51consec'] . '';
			$sSQL = 'DELETE FROM ' . $sTabla . ' WHERE ' . $swhere . ';';
			$result = $objDB->ejecutasql($sSQL);
			if ($audita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, 0, $swhere, $objDB);
			}
			$_REQUEST['paso'] = -1;
		}
	} else {
		$_REQUEST['paso'] = 2;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['paso'] = 0;
	if ($_REQUEST['unad51id'] != '') {
		$_REQUEST['paso'] = 2;
	}
}
$bConOrigen = false;
$bEntra = false;
if ((int)$_REQUEST['unad51id'] == 0) {
	$bEntra = $bMultiDB;
} else {
	$bConOrigen = true;
}
if ($bConOrigen) {
	if ($bEntra) {
		$objCombos = new clsHtmlCombos();
		$objCombos->nuevo('dborigen', $_REQUEST['dborigen'], false, '');
		$sSQL = 'SELECT unad50id AS id, unad50nombre AS nombre FROM unad50dbalterna WHERE unad50actual="S" ORDER BY unad50frecuente DESC, unad50id';
		$html_dborigen = $objCombos->html($sSQL, $objDB);
	} else {
		$html_dborigen = html_oculto('dborigen', $_REQUEST['dborigen']);
	}
}
$objDB->CerrarConexion();
//FORMA
forma_cabecera_campus($xajax, ['tituloModulo' => $sTituloModulo, 'conMenu' => false, 'conCambioIdioma' => false, 'conModal' => false]);
forma_mitad_campus(['conMenu' => false, 'conAccess' => false, 'conBanner' => false]);
?>
<script language="javascript">
	function limpiapagina() {
		window.document.frmarchivo.paso.value = -1;
		window.document.frmarchivo.submit();
	}

	function subirArchivo() {
		let sError = '';
		let dpaso = window.document.frmarchivo.paso;
		// if (dpaso.value == 0){
			if (window.document.frmarchivo.unad51archivo.files.length == 0){
				sError = 'No ha seleccionado un archivo a subir.';
			}
		// }
		if (sError == ''){
			expandesector(2);
			if (dpaso.value == 0) {
				dpaso.value = 10;
			} else {
				dpaso.value = 12;
			}
			window.document.frmarchivo.submit();
		} else {
			mostrarError(sError, 0);
		}
	}

	function mostrarError(sError, iTipoError = 0) {
		const divError = document.getElementById('div_error');
		const iconError = document.getElementById('icon_error');
		const textError = document.getElementById('text_error');
		// Reiniciar visibilidad
		divError.style.display = 'none';
		// Determinar color según tipo de error
		let color = 'red';
		let containerClass = 'container container--red flex gap-2 align-items-center';
		let iconClass = 'icon-closed text-red';
		if (iTipoError === 1) {
			color = 'green';
			containerClass = 'container container--green flex gap-2 align-items-center';
			iconClass = 'icon-check text-green';
		}
		// Aplicar clases según tipo
		divError.className = containerClass;
		iconError.className = iconClass;
		iconError.setAttribute('aria-hidden', false);
		// Mostrar si hay texto de error
		if (sError && sError.trim() !== '') {
			divError.style.display = 'flex';
			textError.innerHTML = sError;
		}
	}

	function cambiapagina() {
		window.document.frmarchivo.submit();
	}

	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector2').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
	}

	function terminar() {
		let dorigen = window.document.frmarchivo.dborigen;
		let did = window.document.frmarchivo.unad51id;
		let ddet = window.document.frmarchivo.unad51detalle;
		if (did.value != '') {
			window.parent.document.frmedita.div96v1.value = window.document.frmarchivo.dborigen.value;
			window.parent.document.frmedita.div96v2.value = window.document.frmarchivo.unad51id.value;
			window.parent.document.frmedita.div96v3.value = window.document.frmarchivo.unad51detalle.value;
		}
		window.parent.cierraDiv96('<?php echo $idModuloOrigen; ?>');
	}
</script>
<form id="frmarchivo" name="frmarchivo" method="post" enctype="multipart/form-data" action="">
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input name="unad51consec" type="hidden" id="unad51consec" value="<?php echo $_REQUEST['unad51consec']; ?>" />
<input name="unad51nombre" type="hidden" id="unad51nombre" value="<?php echo $_REQUEST['unad51nombre']; ?>" />
<div id="div_sector1" class="sector">
<div class="main__header">
<a onclick="terminar()" class="main__back-button btn btn--container-lower" aria-label="Regresar a la página anterior">
<i class="icon-navigate-before" aria-hidden="true"></i>
Atrás
</a>
<h1 id="div_titulo">Mis Inscripciones - Cargar Archivos</h1>
</div>
<div class="main__body">
<div class="grid gap-2">
<div class="container container--primary flex gap-2 align-items-center">
<i class="icon-info text-primary" aria-hidden="true"></i>
<div>
<p>Tenga en cuenta que el sistema permite subir <b>m&aacute;ximo <?php echo archivos_MaxSubida(); ?> Megabytes</b>, si intenta subir archivos m&aacute;s grandes el proceso va a fallar.</p>
<p><b>En caso de necesitar subir archivos de mayor tamaño, por favor comuniquese con el administrador del sistema.</b></p>
</div>
</div>
<!-- Controlar errores -->
<div id="div_error" class="container container--green flex gap-2 align-items-center" style="display:none;">
<i id="icon_error" class="icon-check text-green" aria-hidden="false"></i>
<p id="text_error"></p>
</div>
<!-- Fin -->
<div class="container container--lower sm:flex flex-column grid gap-row-2" style="grid-template-columns: 2fr 1fr;">
<div class="grid gap-2 sm:p-0 sm:m-0 pr-4 mr-4 align-start" style="border-right: .125rem solid var(--sys-outline);">
<div class="flex gap-2">
<?php
if ($bConOrigen) {
?>
<div class="w-8">
<label>DB</label>
<strong>
<?php
echo $html_dborigen;
?>
</strong>
</div>
<?php
} else {
?>
<input id="dborigen" name="dborigen" type="hidden" value="<?php echo $_REQUEST['dborigen']; ?>" />
<?php
}
?>
<div>
<label>Ref:</label>
<strong>
<?php
echo html_oculto('unad51id', $_REQUEST['unad51id']);
?>
</strong>
</div>
</div>
<div class="flex sm:wrap gap-column-2 gap-row-1">
<label class="w-8">Archivo</label>
<input name="MAX_FILE_SIZE" type="hidden" id="MAX_FILE_SIZE" value="64200000" />
<input type="file" id="unad51archivo" name="unad51archivo" style="padding-top: 3px;"/>
<button type="button" class="btn btn--primary w-100per" onclick="subirArchivo()">
<i class="icon-upload" aria-hidden="true"></i>
Cargar Archivo
</button>
</div>
<?php 
if ($bUploadVerDetalle) {
?>
<div class="flex gap-2">
<label class="w-8">Detalle</label>
<input type="text" id="unad51nombre" value="<?php echo $_REQUEST['unad51nombre']; ?>">
</div>
<?php 
} else {
?>
<input type="hidden" id="unad51nombre" value="<?php echo $_REQUEST['unad51nombre']; ?>">
<?php 
}
?>
<?php
$sRuta = '';
if ((int)$_REQUEST['unad51id'] != 0) {
	$sRuta = url_encode($_REQUEST['dborigen'] . '|' . $_REQUEST['unad51id']);
}
if ($bUploadVerRuta) {
?>
<div class="flex gap-2">
<label class="w-8">Enlace</label>
<input id="unad51detalle" name="unad51detalle" type="text" value="&lt;img src=&quot;verarchivo.php?u='<?php echo $sRuta; ?>'&quot;/&gt;"/>
</div>
<?php
} else {
?>
<input id="unad51detalle" name="unad51detalle" type="hidden" value=""/>
<?php
}
?>
</div>
<div class="flex flex-column pr-2 gap-2 justify-center align-items-center">
<span>Archivo Subido</span>
<?php
if ($_REQUEST['unad51id'] != '') {
	$besimg = false;
	switch ($_REQUEST['unad51mime']) {
		case "image/bmp":
		case "image/gif":
		case "image/jpeg":
		case "image/jpg":
		case "image/png":
			$besimg = true;
			break;
	}
	if ($besimg) {
		echo '<a href="verarchivo.php?u=' . $sRuta . '" aria-label="Descargar PDF agregar_nombre_img" target="_blank" class="btn btn--tertiary"><i class="icon-open" aria-hidden="false"></i>' . $_REQUEST['unad51nombre'] . '</a>';
		echo '<img class="w-100per rounded-md" src="verarchivo.php?u=' . $sRuta . '" />';
	} else {
		echo '<button type="button" href="verarchivo.php?u=' . $sRuta . '" aria-label="Descargar Archivo agregar_nombre_pdf" target="_blank" class="btn btn--tertiary"><i class="icon-download" aria-hidden="false"></i>' . $_REQUEST['unad51nombre'] . '</button>';
	}
}
?>
</div>
</div>
</div>
</div>
</div>


<div id="div_sector2" class="sector" style="display:none">
<div class="main__body">
<div class="flex gap-2 align-items-center">
<span class="spinner spinner--primary" aria-hidden="true"></span><p><?php echo $ETI['msg_espere']; ?></p>
</div>
</div>
</div>


<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
</form>
<script language="javascript">
	function ajustaralto() {
		let iAlto = 0;
		iAlto = window.document.body.scrollHeight;
		if (iAlto < 100) {
			iAlto = 100;
		}
		iAlto = iAlto + 30;
		if (!window.opera && document.all && document.getElementById) {
			window.parent.document.getElementById('iframe96').style.height = iAlto;
		} else {
			window.parent.document.getElementById('iframe96').style.height = iAlto + 'px';
		}
	}

	setTimeout('ajustaralto()', 1);
	// Ajustar titulo
	window.document.getElementById('div_titulo').innerHTML = window.parent.document.getElementById('div_96titulo').innerHTML;
	//
<?php
if ($sError != '') {
?>	
	mostrarError('<?php echo $sError; ?>', <?php echo $iTipoError; ?>);
<?php
}
?>
</script>
<?php
forma_piedepagina_campus(['conTiempo' => false, 'mostrarFooter' => false, 'conScripts' => false]);
