<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 jueves, 16 de agosto de 2018
--- Modelo Versión 3.0.6b jueves, 21 de marzo de 2024
--- Modelo Versión 3.0.8 lunes, 20 de mayo de 2024
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
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_xajax.php';
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libdatos.php';
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 251;
$audita[1] = false;
$audita[2] = false;
$audita[3] = false;
$audita[4] = true;
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
require $mensajes_todas;
if ($_SESSION['unad_id_tercero'] == 0) {
	die();
}
$_SESSION['u_ultimominuto'] = iminutoavance();
if (isset($_REQUEST['ref']) == 0) {
	echo 'No se ha definido la referencia que hace el llamado.';
	die();
} else {
	$ver_ref = numeros_validar($_REQUEST['ref']);
	if ($ver_ref != $_REQUEST['ref']) {
		die();
	}
}
if (isset($_REQUEST['id']) == 0) {
	echo 'No se ha definido el registro de origen.';
	die();
} else {
	$ver_id = numeros_validar($_REQUEST['id']);
	if ($ver_id != $_REQUEST['id']) {
		die();
	}
}
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
$iPiel=iDefinirPiel($APP, 2);
$bhayref = false;
$bimage = false;
$bMultiDB = false;
switch ($_REQUEST['ref']) {
	default:
		$bHayAnexo = false;
		$sdeclara = 'libs/defanexo' . $_REQUEST['ref'] . '.php';
		if (file_exists($sdeclara)) {
			$bHayAnexo = true;
		} else {
			$sdeclara = $APP->rutacomun . 'libs/defanexo' . $_REQUEST['ref'] . '.php';
			if (file_exists($sdeclara)) {
				$bHayAnexo = true;
			}
		}
		if ($bHayAnexo) {
			include($sdeclara);
		} else {
			echo 'No se ha definido la informaci&oacute;n del anexo ' . $_REQUEST['ref'];
			die();
		}
}
$sSQL = '';
if (isset($_REQUEST['paso']) == 0) {
	//traer el id del archivo y el origen....
	$sSQL = 'SELECT ' . $borigen . ', ' . $bidarchivo . ' FROM ' . $btabla . ' WHERE ' . $bidreg . '=' . $_REQUEST['id'];
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
	//if ($_REQUEST['paso']==10){
	$_REQUEST['unad51consec'] = numeros_validar($_REQUEST['unad51consec']);
	//	}
	//$_REQUEST['unad51nombre']=htmlspecialchars($_REQUEST['unad51nombre']);
	$_REQUEST['unad51detalle'] = htmlspecialchars($_REQUEST['unad51detalle']);
	//$_REQUEST['unad51mime']=htmlspecialchars($_REQUEST['unad51mime']);
	//$_REQUEST['unad51archivo']=htmlspecialchars($_REQUEST['unad51archivo']);
	//if ($_REQUEST['unad51archivo']==''){$sError='Necesita el dato Archivo';}
	//if ($_REQUEST['unad51mime']==''){$sError='Necesita el dato Mime';}
	//if ($_REQUEST['unad51detalle']==''){$sError='Necesita el dato Detalle';}
	//if ($_REQUEST['unad51nombre']==''){$sError='Necesita el dato Nombre';}
	//if ($_REQUEST['unad51id']==''){$sError='Necesita el dato Id';}//CONSECUTIVO
	//if ($_REQUEST['unad51consec']==''){$sError='Necesita el dato Consec';}//CONSECUTIVO
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
		//$prevmime=$_REQUEST['unad51mime'];
		$_REQUEST['unad51nombre'] = $_FILES['unad51archivo']['name'];
		//$tmp_name = $_FILES["unad51archivo"]["tmp_name"];
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
							//case '.': $_REQUEST['unad51mime']=''; break;
						default:
							$ext = '';
					}
					if ((!$bimage) && ($ext != '')) {
						$bsubiendo = true;
					}
					break;
			}
			//echo 'esta cargando un archivo...';
			if (!$bsubiendo) {
				$sError = 'El tipo de archivo que intenta subir no es admitido {' . $_REQUEST['unad51mime'] . '}.';
			}
		} else {
			//verificar que este actualizando el detalle 
			if ($_REQUEST['paso'] == 10) {
				//
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
		//echo $sSQL;
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
						$sSQL = 'UPDATE ' . $btabla . ' SET ' . $borigen . '=' . $_REQUEST['dborigen'] . ', ' . $bidarchivo . '=' . $_REQUEST['unad51id'] . ' WHERE ' . $bidreg . '=' . $_REQUEST['id'];
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
			//si es una imagen redimencionarla....
			/*
			if (($ext=='.jpg')or($ext=='.gif')){
				require($APP->rutacomun.'libs/class.image-resize.php');
				$obj = new img_opt();
				$obj->max_width(800);
				$obj->max_height(800);
				$obj->image_path($final);
				$obj->image_resize();
				}
				*/
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
			}
		}
	} else {
		$_REQUEST['paso'] = 2;
	} //fin de si no hay error.
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	/*	if (!comprobacion){
		$sError='No se puede eliminar';//EXPLICAR LA RAZON
		}*/
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
	/*
	$_REQUEST['unad51consec']='';
	$_REQUEST['unad51id']='';
	$_REQUEST['unad51nombre']='';
	$_REQUEST['unad51detalle']='';
	$_REQUEST['unad51mime']='';
	*/
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
?>
<?php
switch($iPiel){
	case 2:
		require $APP->rutacomun . 'unad_forma2024.php';
		forma_InicioV4($xajax, 'Carga de archivos');
	break;
	default:
?>
<!DOCTYPE html>
<head>
<title>Carga de archivos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/formav2.css?v=2" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css" />
<?php
	break;
	}
	$objForma = new clsHtmlForma($iPiel);
?>
<body>
<script language="javascript">
	function limpiapagina() {
		window.document.frmarchivo.paso.value = -1;
		window.document.frmarchivo.submit();
	}

	function enviaguardar() {
		let sError = '';
		let dpaso = window.document.frmarchivo.paso;
		if (dpaso.value == 0){
			if (window.document.frmarchivo.unad51archivo.files.length == 0){
				sError = 'No ha seleccionado un archivo a subir.';
			}
		}
		if (sError == ''){
			expandesector(2);
			if (dpaso.value == 0) {
				dpaso.value = 10;
			} else {
				dpaso.value = 12;
			}
			window.document.frmarchivo.submit();
		} else {
			window.parent.MensajeAlarmaV2(sError, 0);
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
		window.parent.cierraDiv96('<?php echo $_REQUEST['ref']; ?>');
	}
</script>
<form id="frmarchivo" name="frmarchivo" method="post" enctype="multipart/form-data" action="">
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input name="unad51consec" type="hidden" id="unad51consec" value="<?php echo $_REQUEST['unad51consec']; ?>" />
<input name="unad51nombre" type="hidden" id="unad51nombre" value="<?php echo $_REQUEST['unad51nombre']; ?>" />
<div class="salto5px"></div>
<div id="div_sector1">
<div class="areaform">
<div class="areatrabajo">
<?php
if ($bConOrigen) {
?>
<label class="Label60">
DB :
</label>
<label class="Label160">
<?php
echo $html_dborigen;
?>
</label>
<?php
} else {
?>
<input id="dborigen" name="dborigen" type="hidden" value="<?php echo $_REQUEST['dborigen']; ?>" />
<?php
}
?>
<label class="Label60">
Ref :
</label>
<label class="Label130">
<?php
echo html_oculto('unad51id', $_REQUEST['unad51id']);
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
Archivo
</label>
<label class="Label380">
<input name="MAX_FILE_SIZE" type="hidden" id="MAX_FILE_SIZE" value="64200000" />
<input type="file" id="unad51archivo" name="unad51archivo" style="padding-top: 3px;"/>
</label>
<?php
echo $objForma->htmlBotonSolo('enviar', 'btUpAnexar', 'enviaguardar()', $ETI['bt_guardar'], 90);
?>
<label class="Label60">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('terminado', 'botonAprobado', 'terminar()', $ETI['bt_volver'], 90);
?>
<?php
if ($_REQUEST['unad51id'] != '') {
	//mostrar la imagen...
	$sRuta = url_encode($_REQUEST['dborigen'] . '|' . $_REQUEST['unad51id']);
	echo '<div class="salto1px"></div><label class="Label130">Link del archivo:</label><label><input id="unad51detalle" name="unad51detalle" type="text" value="&lt;img src=&quot;verarchivo.php?u=' . $sRuta . '&quot;/&gt;"/></label>';
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="salto1px"></div>
Tenga en cuesta que el sistema permite subir <b>m&aacute;ximo <?php echo archivos_MaxSubida(); ?> Megabytes</b>, si intenta subir archivos mas grandes el proceso va a fallar, en caso de necesitar subir archivos de mayor tama&ntilde;o por favor comuniquese con el administrador del sistema.
<div class="salto1px"></div>
</div>
<label class="L">
Detalle
<input id="unad51detalle" name="unad51detalle" type="text" value="<?php echo $_REQUEST['unad51detalle']; ?>" maxlength="250" class="L" />
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['unad51id'] != '') {
	echo '<hr>' . html_salto();
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
		echo '<img src="verarchivo.php?u=' . $sRuta . '" />';
	} else {
		echo '<a href="verarchivo.php?u=' . $sRuta . '" target="_blank" class="lnkresalte">' . $_REQUEST['unad51nombre'] . '</a>';
	}
}
?>
<div class="salto1px"></div>
</div>
</div>
<?php
// DIV_Sector1 
?>
</div>

<div id="div_sector2" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
echo $objForma->htmlInicioMarco();
echo $objForma->htmlEspere('Por favor espere mientras el sistema carga el archivo.');
echo $objForma->htmlFinMarco();
?>
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
window.parent.MensajeAlarmaV2('<?php echo $sError; ?>', <?php echo $iTipoError; ?>);
setTimeout('ajustaralto()', 1);
</script>
</body>
</html>
