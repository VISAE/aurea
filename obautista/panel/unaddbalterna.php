<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.2.14 lunes, 19 de enero de 2015
--- Modelo Versión 2.18.1 sábado, 20 de mayo de 2017
--- Modelo Versión 2.22.3 domingo, 12 de agosto de 2018
--- Modelo Versión 2.23.7 Friday, October 18, 2019
*/
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$sDebug = '';
if (isset($_REQUEST['deb_doc']) != 0) {
	if (trim($_REQUEST['deb_doc']) != '') {
		$bDebug = true;
	}
} else {
	$_REQUEST['deb_doc'] = '';
}
if (isset($_REQUEST['debug']) != 0) {
	if ($_REQUEST['debug'] == 1) {
		$bDebug = true;
	}
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
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
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
$iMinVerDB = 7171;
$iCodModulo = 250;
$audita[1] = false;
$audita[2] = true;
$audita[3] = true;
$audita[4] = true;
$audita[5] = false;
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_250='lg/lg_250_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_250)){$mensajes_250='lg/lg_250_es.php';}
require $mensajes_todas;
require $mensajes_250;
$xajax = NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if ($bDebug){
	$sDebug=$sDebug.''.fecha_microtiempo().' Probando conexi&oacute;n con la base de datos <b>'.$APP->dbname.'</b> en <b>'.$APP->dbhost.'</b><br>';
	}
if (!$objDB->Conectar()){
	$bCerrado=true;
	if ($bDebug){
		$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objDB->serror.'</b><br>';
		}
	}
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=unaddbalterna.php');
		die();
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	} else {
	$_REQUEST['debug']=0;
	}
//PROCESOS DE LA PAGINA
//$idEntidad = Traer_Entidad();
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 250 unad50dbalterna
require 'lib250.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f250_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f250_ExisteDato');
$xajax->processRequest();
if ($bPeticionXAJAX) {
	die(); // Esto hace que las llamadas por xajax terminen aquí.
}
$bcargo = false;
$sError = '';
$sErrorCerrando = '';
$iTipoError = 0;
$bLimpiaHijos = false;
$bMueveScroll = false;
$iSector = 1;
$iHoy = fecha_DiaMod();
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf250'])==0){$_REQUEST['paginaf250']=1;}
if (isset($_REQUEST['lppf250'])==0){$_REQUEST['lppf250']=20;}
if (isset($_REQUEST['boculta250'])==0){$_REQUEST['boculta250']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad50consec'])==0){$_REQUEST['unad50consec']='';}
if (isset($_REQUEST['unad50consec_nuevo'])==0){$_REQUEST['unad50consec_nuevo']='';}
if (isset($_REQUEST['unad50id'])==0){$_REQUEST['unad50id']='';}
if (isset($_REQUEST['unad50actual'])==0){$_REQUEST['unad50actual']='N';}
if (isset($_REQUEST['unad50modelo'])==0){$_REQUEST['unad50modelo']='M';}
if (isset($_REQUEST['unad50nombre'])==0){$_REQUEST['unad50nombre']='';}
if (isset($_REQUEST['unad50server'])==0){$_REQUEST['unad50server']='';}
if (isset($_REQUEST['unad50puerto'])==0){$_REQUEST['unad50puerto']='';}
if (isset($_REQUEST['unad50db'])==0){$_REQUEST['unad50db']='';}
if (isset($_REQUEST['unad50usuario'])==0){$_REQUEST['unad50usuario']='';}
if (isset($_REQUEST['unad50pwd'])==0){$_REQUEST['unad50pwd']='';}
if (isset($_REQUEST['unad50frecuente'])==0){$_REQUEST['unad50frecuente']='N';}
if (isset($_REQUEST['unad50hostzona1'])==0){$_REQUEST['unad50hostzona1']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
/*
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
*/
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi='unad50consec='.$_REQUEST['unad50consec'].'';
		} else {
		$sSQLcondi='unad50id='.$_REQUEST['unad50id'].'';
		}
	$sSQL='SELECT * FROM unad50dbalterna WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['unad50consec']=$fila['unad50consec'];
		$_REQUEST['unad50id']=$fila['unad50id'];
		$_REQUEST['unad50actual']=$fila['unad50actual'];
		$_REQUEST['unad50modelo']=$fila['unad50modelo'];
		$_REQUEST['unad50nombre']=$fila['unad50nombre'];
		$_REQUEST['unad50server']=$fila['unad50server'];
		$_REQUEST['unad50puerto']=$fila['unad50puerto'];
		$_REQUEST['unad50db']=$fila['unad50db'];
		$_REQUEST['unad50usuario']=$fila['unad50usuario'];
		$_REQUEST['unad50pwd']=$fila['unad50pwd'];
		$_REQUEST['unad50frecuente']=$fila['unad50frecuente'];
		$_REQUEST['unad50hostzona1']=$fila['unad50hostzona1'];
		if ($_REQUEST['unad50modelo']=='D'){
			$_REQUEST['unad50server']=str_replace('#', '\\', $fila['unad50server']);
			}
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta250']=0;
		$bLimpiaHijos=true;
		} else {
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f250_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['unad50consec_nuevo']=numeros_validar($_REQUEST['unad50consec_nuevo']);
	if ($_REQUEST['unad50consec_nuevo']==''){$sError=$ERR['unad50consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT unad50id FROM unad50dbalterna WHERE unad50consec='.$_REQUEST['unad50consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['unad50consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE unad50dbalterna SET unad50consec='.$_REQUEST['unad50consec_nuevo'].' WHERE unad50id='.$_REQUEST['unad50id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['unad50consec'].' a '.$_REQUEST['unad50consec_nuevo'].'';
		$_REQUEST['unad50consec']=$_REQUEST['unad50consec_nuevo'];
		$_REQUEST['unad50consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['unad50id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		} else {
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	list($sError, $iTipoError, $sDebugElimina)=f250_db_Eliminar($_REQUEST['unad50id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//CREAR LA TABLA PARA ARCHIVOS EN LA BASE DE DATOS ANEXA
if ($_REQUEST['paso']==31){
	$_REQUEST['paso']=2;
	$sSQLcondi=' AND unad50id='.$_REQUEST['unad50id'].'';
	$sSQL='SELECT * FROM unad50dbalterna WHERE unad50id='.$_REQUEST['unad50id'].'';
	$result=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($result)>0){
		$row=$objDB->sf($result);
		$sTabla='unad51archivos';
		$bExterna=true;
		$bTablaLocal=false;
		if ($row['unad50modelo']=='D'){$bTablaLocal=true;}
		if ($row['unad50modelo']=='S'){$bTablaLocal=true;}
		if ($bTablaLocal){
			$bExterna=false;
			$sTabla='unad51archivos_'.$row['unad50id'];
			}
		if ($bExterna){
			$objDBt=new clsdbadmin($row['unad50server'], $row['unad50usuario'], $row['unad50pwd'], $row['unad50db'], $row['unad50modelo']);
			if ($row['unad50puerto']!=''){$objDBt->dbPuerto=$row['unad50puerto'];}
			$objDBt->conectar();
			if ($objDBt->serror==''){
				$sError='Error al intentar conectar con la base de datos de archivos: '.$objDBt->serror;
				}
			} else {
			//echo 'Es la db local';
			$objDBt=$objDB;
			}
		if ($sError==''){
			$tabla=$objDBt->ejecutasql('CREATE TABLE '.$sTabla.' (unad51consec int NOT NULL, unad51id int NULL DEFAULT 0, unad51nombre varchar(100) NULL, unad51detalle varchar(250) NULL, unad51mime varchar(100) NULL, unad51archivo LONGBLOB, unad51fechacreado int NULL DEFAULT 0, unad51fechaupd int NULL DEFAULT 0, unad51fechavista int NULL DEFAULT 0, unad51peso int NULL DEFAULT 0, unad51consultas int NULL DEFAULT 0)');
			if ($tabla==false){
				$sError='Error al intentar crear la tabla para archivos: '.$objDBt->serror.'';
				} else {
				$tabla=$objDBt->ejecutasql('ALTER TABLE '.$sTabla.' ADD PRIMARY KEY(unad51id)');
				$tabla=$objDBt->ejecutasql('ALTER TABLE '.$sTabla.' ADD INDEX unad51archivos_id (unad51consec)');
				$sError='<b>La tabla para archivos ha sido creada con exito.</b>';
				$iTipoError=1;
				}
			}
		}
	}
//Actualizar los pesos de la tabla alterna.
if ($_REQUEST['paso']==32){
	$_REQUEST['paso']=2;
	$sSQL='SELECT * FROM unad50dbalterna WHERE unad50id='.$_REQUEST['unad50id'].'';
	$result=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($result)>0){
		$row=$objDB->sf($result);
		$sTabla='unad51archivos';
		$bExterna=true;
		$bTablaLocal=false;
		if ($row['unad50modelo']=='D'){$bTablaLocal=true;}
		if ($row['unad50modelo']=='S'){$bTablaLocal=true;}
		if ($bTablaLocal){
			$bExterna=false;
			$sTabla='unad51archivos_'.$row['unad50id'];
			}
		if ($bExterna){
			//echo 'Es una db externa';
			$objDBt=new clsdbadmin($row['unad50server'], $row['unad50usuario'], $row['unad50pwd'], $row['unad50db'], $row['unad50modelo']);
			if ($row['unad50puerto']!=''){$objDBt->dbPuerto=$row['unad50puerto'];}
			$objDBt->conectar();
			$sError='Error al intentar conectar con la base de datos de archivos: '.$objDBt->serror;
			} else {
			//echo 'Es la db local';
			$objDBt=$objDB;
			}
		if ($sError==''){
			$bPasa=true;
			$tabla=$objDBt->ejecutasql('ALTER TABLE '.$sTabla.' ADD unad51fechacreado int NULL DEFAULT 0, ADD unad51fechaupd int NULL DEFAULT 0, ADD unad51fechavista int NULL DEFAULT 0, ADD unad51peso int NULL DEFAULT 0');
			$tabla=$objDBt->ejecutasql('ALTER TABLE '.$sTabla.' ADD unad51peso int NULL DEFAULT 0');
			if ($tabla==false){
				$sError='Error al intentar actualizar la tabla de archivos: '.$objDBt->serror.'';
				}
			if ($bPasa){
				if ($bExterna){
					$sSQL='UPDATE '.$sTabla.' SET unad51peso=length(unad51archivo)';
					$tabla=$objDBt->ejecutasql($sSQL);
					} else {
					//Hay que revisar la ruta...
					}
				$sError='<b>Se ha calculado el peso de la tabla para archivos.</b>';
				$iTipoError=1;
				}
			}
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['unad50consec']='';
	$_REQUEST['unad50consec_nuevo']='';
	$_REQUEST['unad50id']='';
	$_REQUEST['unad50actual']='N';
	$_REQUEST['unad50modelo']='M';
	$_REQUEST['unad50nombre']='';
	$_REQUEST['unad50server']='';
	$_REQUEST['unad50puerto']='';
	$_REQUEST['unad50db']='';
	$_REQUEST['unad50usuario']='';
	$_REQUEST['unad50pwd']='';
	$_REQUEST['unad50frecuente']='N';
	$_REQUEST['unad50hostzona1']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$seg_5=0;
$seg_6=0;
$seg_8=0;
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB, $bDebug);
//if ($bDevuelve){$seg_6=1;}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objCombos->nuevo('unad50actual', $_REQUEST['unad50actual'], false);
$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
$html_unad50actual=$objCombos->html('', $objDB);
$objCombos->nuevo('unad50frecuente', $_REQUEST['unad50frecuente'], false);
$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
$html_unad50frecuente=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	$objCombos->nuevo('unad50modelo', $_REQUEST['unad50modelo'], false);
	$objCombos->addItem('M', 'MySQL');
	$objCombos->addItem('O', 'ODBC');
	$objCombos->addItem('D', 'Directorio');
	$objCombos->addItem('S', 'SFTP');
	$html_unad50modelo=$objCombos->html('', $objDB);
	} else {
	$et_unad50modelo='{'.$_REQUEST['unad50modelo'].'}';
	switch($_REQUEST['unad50modelo']){
		case 'M':
		$et_unad50modelo='MySQL';
		break;
		case 'O':
		$et_unad50modelo='ODBC';
		break;
		case 'D':
		$et_unad50modelo='Directorio';
		break;
		case 'S':
		$et_unad50modelo='SFTP';
		break;
		}
	$html_unad50modelo=html_oculto('unad50modelo', $_REQUEST['unad50modelo'], $et_unad50modelo);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf250()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(250, 1, $objDB, 'paginarf250()');
*/
//Permisos adicionales
$html_archivos='';
//if ($seg_6==1){}
if (false) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$sClaseLabel = 'Label90';
	if ($iPiel == 2) {
		$sClaseLabel = 'w-15';
	}
	$csv_separa = '<label class="' . $sClaseLabel . '">' . $ETI['msg_separador'] . '</label><label class="' . $sClaseLabel . '">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
 $iNumFormatosImprime = 0;
$iModeloReporte=250;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){$seg_5=1;}
	switch($_REQUEST['unad50modelo']){
		case 'M':
		unset ($objDBa);
		$objDBa=new clsdbadmin($_REQUEST['unad50server'], $_REQUEST['unad50usuario'], $_REQUEST['unad50pwd'], $_REQUEST['unad50db'], $_REQUEST['unad50modelo']);
		if ($_REQUEST['unad50puerto']!=''){$objDBa->dbPuerto=$_REQUEST['unad50puerto'];}
		$result=$objDBa->ejecutasql('SHOW TABLES');
		if ($result==false){
			$html_archivos='<div class="rojo">'.$objDBa->serror.'</div>';
		} else {
		$bdato=$objDBa->bexistetabla('unad51archivos');
		if ((!$bdato)&&($objDBa->serror=='')){
			$html_archivos='
<label class="Label130"></label>
<label class="Label200">
<input type="button" name="btCreaTabla" value="Crear Tabla" onClick="crear_t35();" class="BotonAzul" title="Crear la tabla para archivos">
</label>';
			} else {
			if ($bdato){
				$tabla=$objDBa->ejecutasql('SELECT COUNT(unad51id) FROM unad51archivos');
				$fila=$objDBa->sf($tabla);
				$sMensaje='Existen <b>'.$fila[0].'</b> registros en la tabla de archivos';
				$bHayPeso=false;
				$sSQL='SELECT SUM(unad51peso) FROM unad51archivos';
				$tabla=$objDBa->ejecutasql($sSQL);
				if ($tabla==false){
					$sMensaje=$sMensaje.' <a href="javascript:actualiza35pesos()" class="lnkresalte">Calcular Tama&ntilde;o</a>';
					} else {
					$fila=$objDBa->sf($tabla);
					$iPeso=$fila[0];
					$sUnidadMedida=' bites';
					$sSobra='';
					if ($iPeso>1024){
						$iPeso=round(($iPeso)/1024,2);
						$sUnidadMedida=' Kilobites';
						if ($iPeso>1024){
							$iPeso=round(($iPeso)/1024,2);
							$sUnidadMedida=' Megabites';
							}
						}
					$sMensaje=$sMensaje.', Peso total: <b>'.formato_numero($iPeso, 2).$sUnidadMedida.'</b> <a href="javascript:actualiza35pesos()" class="lnkresalte">Revisar</a>';
					}
				$html_archivos=$sMensaje;
				} else {
				$html_archivos='<div class="rojo">No fue posible leer la base de datos de archivos :<b>'.$objDBa->serror.'</b></div>';
				}
			}
		}
		break;
		case 'D':
		$html_archivos='<div class="rojo">No hay acceso a: '.$_REQUEST['unad50server'].'</div>';
		$sRuta=$_REQUEST['unad50server'];
		if (file_exists($sRuta)){
			$sTabla='unad51archivos_'.$_REQUEST['unad50id'];
			$html_archivos='<label class="L"><b>Ruta usada: '.$_REQUEST['unad50server'].'</b></label>';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando tabla <b>'.$sTabla.'</b><br>';}
			$bdato=$objDB->bexistetabla($sTabla);
			if (!$bdato){
				$html_archivos=$html_archivos.'
<label class="Label130">&nbsp;</label>
<label class="Label200">
<input type="button" name="btCreaTabla" value="Crear Tabla" onClick="crear_t35();" class="BotonAzul" title="Crear la tabla para archivos">
</label>';
				} else {
				$tabla=$objDB->ejecutasql('SELECT COUNT(unad51id) FROM '.$sTabla);
				$fila=$objDB->sf($tabla);
				$sMensaje='Existen <b>'.$fila[0].'</b> registros en la tabla de archivos';
				$bHayPeso=false;
				$sSQL='SELECT SUM(unad51peso) FROM '.$sTabla;
				$tabla=$objDB->ejecutasql($sSQL);
				if ($tabla==false){
					$sMensaje=$sMensaje.' <a href="javascript:actualiza35pesos()" class="lnkresalte">Calcular Tama&ntilde;o</a>';
					} else {
					$fila=$objDB->sf($tabla);
					$iPeso=$fila[0];
					$sUnidadMedida=' bites';
					$sSobra='';
					if ($iPeso>1024){
						$iPeso=round(($iPeso)/1024,2);
						$sUnidadMedida=' Kilobites';
						if ($iPeso>1024){
							$iPeso=round(($iPeso)/1024,2);
							$sUnidadMedida=' Megabites';
							}
						}
					$sMensaje=$sMensaje.', Peso total: <b>'.formato_numero($iPeso, 2).$sUnidadMedida.'</b> <a href="javascript:actualiza35pesos()" class="lnkresalte">Revisar</a>';
					}
				$html_archivos=$html_archivos.'<label class="L">'.$sMensaje.'</label>';
				}
			} else {
			$html_archivos='<div class="rojo">El directorio "'.$sRuta.'" no se encuentra accesible.</div>';
			}
		break;
		case 'S':
		$html_archivos='<div class="rojo">Sin datos de: '.$_REQUEST['unad50server'].'</div>';
		$sTabla='unad51archivos_'.$_REQUEST['unad50id'];
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando tabla <b>'.$sTabla.'</b><br>';}
		$bdato=$objDB->bexistetabla($sTabla);
		if (!$bdato){
			$html_archivos=$html_archivos.'
<label class="Label130">&nbsp;</label>
<label class="Label200">
<input type="button" name="btCreaTabla" value="Crear Tabla" onClick="crear_t35();" class="BotonAzul" title="Crear la tabla para archivos">
</label>';
			} else {
			$html_archivos='';
			$tabla=$objDB->ejecutasql('SELECT COUNT(unad51id) FROM '.$sTabla);
			$fila=$objDB->sf($tabla);
			$sMensaje='Existen <b>'.$fila[0].'</b> registros en la tabla de archivos';
			$bHayPeso=false;
			$sSQL='SELECT SUM(unad51peso) FROM '.$sTabla;
			$tabla=$objDB->ejecutasql($sSQL);
			if ($tabla==false){
				$sMensaje=$sMensaje.' <a href="javascript:actualiza35pesos()" class="lnkresalte">Calcular Tama&ntilde;o</a>';
				} else {
				$fila=$objDB->sf($tabla);
				$iPeso=$fila[0];
				$sUnidadMedida=' bites';
				$sSobra='';
				if ($iPeso>1024){
					$iPeso=round(($iPeso)/1024,2);
					$sUnidadMedida=' Kilobites';
					if ($iPeso>1024){
						$iPeso=round(($iPeso)/1024,2);
						$sUnidadMedida=' Megabites';
						}
					}
				$sMensaje=$sMensaje.', Peso total: <b>'.formato_numero($iPeso, 2).$sUnidadMedida.'</b> <a href="javascript:actualiza35pesos()" class="lnkresalte">Revisar</a>';
				}
			$html_archivos=$html_archivos.'<label class="L">'.$sMensaje.'</label>';
			}
		break;
		default:
		$html_archivos='<b>No se ha definido un verificador para el modelo: '.$_REQUEST['unad50modelo'].'</b>';
		break;
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_250'];
$aParametros[101]=$_REQUEST['paginaf250'];
$aParametros[102]=$_REQUEST['lppf250'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla250, $sDebugTabla)=f250_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
}
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
$sTituloModulo = $ETI['titulo_250'];
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $sTituloModulo);
echo $et_menu;
forma_mitad();
if (false) {
?>
<link rel="stylesheet" href="../ulib/css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/css/principal.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/unad_estilos2018.css" type="text/css"/>
<?php
	}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<?php
?>
<script language="javascript">
	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector2').style.display = 'none';
		document.getElementById('div_sector93').style.display = 'none';
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector97').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
<?php
switch ($iPiel) {
	case 2:
?>
		document.getElementById('botones_sector1').style.display = 'flex';
		document.getElementById('botones_sector97').style.display = 'none';
		switch (codigo) {
			case 1:
				break;
			case 97:
				document.getElementById('botones_sector1').style.display = 'none';
				document.getElementById('botones_sector' + codigo).style.display = 'flex';
				break;
			default:
				document.getElementById('botones_sector1').style.display = 'none';
				break;
		}
		if (codigo == 1) {
			document.getElementById('nav').removeAttribute('disabled');
		} else {
			document.getElementById('nav').setAttribute('disabled', '');
		}
<?php
		break;
	default:
		if ($bPuedeGuardar && $bBloqueTitulo) {
?>
		let sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		document.getElementById('cmdGuardarf').style.display = sEst;
<?php
		}
		break;
}
?>
	}

function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_250.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_250.value;
		window.document.frmlista.nombrearchivo.value='Base de datos para archivos';
		window.document.frmlista.submit();
		} else {
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
	//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		let sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		// if (sError == '') {
		// 	Agregar validaciones
		// }
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = 'e250.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p250.php';
		window.document.frmimpp.submit();
		} else {
		window.alert("<?php echo $ERR['5']; ?>");
		}
	}

	function verrpt() {
		window.document.frmimprime.submit();
	}

	function eliminadato() {
		ModalConfirmV2('<?php echo $ETI['msg_confirmaeliminar']; ?>', () => {
			ejecuta_eliminadato();
		});
	}

	function ejecuta_eliminadato() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 13;
		window.document.frmedita.submit();
	}

	function RevisaLlave() {
		let datos = new Array();
		datos[1] = window.document.frmedita.unad50consec.value;
	if ((datos[1]!='')){
		xajax_f250_ExisteDato(datos);
		}
	}

	function cargadato(llave1){
	window.document.frmedita.unad50consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf250(llave1){
	window.document.frmedita.unad50id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf250(){
	let params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf250.value;
	params[102]=window.document.frmedita.lppf250.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	xajax_f250_HtmlTabla(params);
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
	function siguienteobjeto() {}
	document.onkeydown = function(e) {
		if (document.all) {
			if (event.keyCode == 13) {
				event.keyCode = 9;
			}
		} else {
			if (e.which == 13) {
				siguienteobjeto();
			}
		}
	}

	function objinicial() {
		document.getElementById("unad50consec").focus();
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		let divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {
		} else {
			verboton('cmdAyuda_' + sCampo, 'none');
			let sMensaje = 'Lo que quiera decir.';
			//if (sCampo == 'sNombreCampo') {
			//sMensaje = 'Mensaje para otro campo.';
			//}
			divAyuda.innerHTML = sMensaje;
			divAyuda.style.display = 'block';
		}
	}

	function cierraDiv96(ref) {
		let sRetorna = window.document.frmedita.div96v2.value;
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}

	function mod_consec() {
		ModalConfirmV2('<?php echo $ETI['msg_confirmamodconsec']; ?>', () => {
			ejecuta_modconsec();
		});
	}

	function ejecuta_modconsec() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 93;
		window.document.frmedita.submit();
	}
function crear_t35(){
	if (confirm("Esta accion creara la tabla para archivos, desea continuar?")){
		expandesector(98);
		window.document.frmedita.paso.value=31;
		window.document.frmedita.submit();
		}
	}
function actualiza35pesos(){
	expandesector(98);
	window.document.frmedita.paso.value=32;
	window.document.frmedita.submit();
	}
// -->
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<form id="frmimpp" name="frmimpp" method="post" action="p250.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="250" />
<input id="id250" name="id250" type="hidden" value="<?php echo $_REQUEST['unad50id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
}
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;" />
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo $iHoy; ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="id11" name="id11" type="hidden" value="<?php echo $idTercero; ?>" />
<input id="ipiel" name="ipiel" type="hidden" value="<?php echo $iPiel; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda', 'btSupAyuda', 'muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ');', $ETI['bt_ayuda']);
	if ($bConEliminar) {
		$objForma->addBoton('cmdEliminar', 'btUpEliminar', 'eliminadato();', $ETI['bt_eliminar']);
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//$sClaseBoton='btEnviarPDF'; //btUpPrint
		//if ($id_rpt!=0){$sScript='verrpt()';}
		}
	}
	if ($bHayImprimir) {
		$objForma->addBoton('cmdImprimir', $sClaseImprime, $sScriptImprime, $ETI['bt_imprimir']);
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$sTituloModulo.'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bConExpande = true;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<input id="boculta250" name="boculta250" type="hidden" value="<?php echo $_REQUEST['boculta250']; ?>" />
<label class="Label30">
<input id="btexpande250" name="btexpande250" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(250,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta250']==0){echo 'none'; } else {echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge250" name="btrecoge250" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(250,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta250']==0){echo 'block'; } else {echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p250" style="display:<?php if ($_REQUEST['boculta250']==0){echo 'block'; } else {echo 'none';} ?>;">
<?php
}
//Mostrar formulario para editar
?>
<label class="Label90">
<?php
echo $ETI['unad50consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="unad50consec" name="unad50consec" type="text" value="<?php echo $_REQUEST['unad50consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	} else {
	echo html_oculto('unad50consec', $_REQUEST['unad50consec']);
	}
?>
</label>
<label class="Label60">
<?php
echo $ETI['unad50id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('unad50id', $_REQUEST['unad50id']);
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad50actual'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad50actual;
?>
</label>
<label class="Label60">
<?php
echo $ETI['unad50modelo'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad50modelo;
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad50frecuente'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad50frecuente;
?>
</label>
<label class="L">
<?php
echo $ETI['unad50nombre'];
?>

<input id="unad50nombre" name="unad50nombre" type="text" value="<?php echo $_REQUEST['unad50nombre']; ?>" maxlength="100" class="L"/>
</label>
<label class="Label60">
<?php
echo $ETI['unad50server'];
?>
</label>
<label>

<input id="unad50server" name="unad50server" type="text" value="<?php echo $_REQUEST['unad50server']; ?>" maxlength="100"/>
</label>
<label class="Label60">
<?php
echo $ETI['unad50puerto'];
?>
</label>
<label class="Label90">
<input id="unad50puerto" name="unad50puerto" type="text" value="<?php echo $_REQUEST['unad50puerto']; ?>" maxlength="10" placeholder="3306" class="cuatro"/>
</label>
<label class="Label60">
<?php
echo $ETI['unad50db'];
?>
</label>
<label>
<input id="unad50db" name="unad50db" type="text" value="<?php echo $_REQUEST['unad50db']; ?>" maxlength="50"/>
</label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['unad50usuario'];
?>
</label>
<label>
<input id="unad50usuario" name="unad50usuario" type="text" value="<?php echo $_REQUEST['unad50usuario']; ?>" maxlength="50"/>
</label>
<label class="Label60">
<?php
echo $ETI['unad50pwd'];
?>
</label>
<label>
<input id="unad50pwd" name="unad50pwd" type="text" value="<?php echo $_REQUEST['unad50pwd']; ?>" maxlength="50"/>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['unad50hostzona1'];
?>

<input id="unad50hostzona1" name="unad50hostzona1" type="text" value="<?php echo $_REQUEST['unad50hostzona1']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad50hostzona1']; ?>"/>
</label>
<div class="salto1px"></div>
<?php
echo $html_archivos;
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p250
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
// CIERRA EL DIV areatrabajo
echo $objForma->htmlFinMarco();
?>
<?php
echo $objForma->htmlInicioMarco($ETI['bloque1']);
?>
<?php
if (false) {
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf250()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
	}
?>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f250detalle">
<?php
echo $sTabla250;
?>
</div>
<?php
// CIERRA EL DIV areatrabajo
?>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda2', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec2', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector2 -->


<div id="div_sector93" style="display:none">
<?php
$objForma=new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
	echo $objForma->htmlInicioMarco();
} else {
	echo $objForma->htmlInicioMarco('', 'div_93titulo');
}
?>
<label class="Label160">
<?php
echo $ETI['msg_unad50consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['unad50consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_unad50consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="unad50consec_nuevo" name="unad50consec_nuevo" type="text" value="<?php echo $_REQUEST['unad50consec_nuevo']; ?>" class="cuatro"/>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div>


<div id="div_sector95" style="display:none">
<?php
echo $objForma->htmlInicioMarco();
echo '<div id="div_95cuerpo"></div>';
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_250" name="titulo_250" type="hidden" value="<?php echo $sTituloModulo; ?>" />
<?php
$objForma=new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda96', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec96', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo, 'div_96titulo');
}
echo $objForma->htmlInicioMarco();
echo '<div id="div_96cuerpo"></div>';
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector96 -->


<div id="div_sector97" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda97', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec97', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo, 'div_97titulo');
}
echo $objForma->htmlInicioMarco();
?>
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<?php
$objForma=new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
echo $objForma->htmlInicioMarco();
echo $objForma->htmlEspere($ETI['msg_espere']);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector98 -->


<?php
if ($sDebug != '') {
	$iSegFin = microtime(true);
	if (isset($iSegIni) == 0) {
		$iSegIni = $iSegFin;
	}
	$iSegundos = $iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '<div class="salto1px"></div></div>';
}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value="" />
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>" />
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>" />
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
</form>
</div><!-- /DIV_interna -->
<div class="flotante">
<?php
echo $objForma->htmlBotonSolo('cmdGuardarf', 'btSoloGuardar', 'enviaguardar()', $ETI['bt_guardar']);
?>
</div>
<?php
//list($sFolder1, $sFolder2, $sArchivo)=archivos_Carpetas(123456789);
//$sError=$sFolder1.' '.$sFolder2.' '.$sArchivo;
echo html_DivAlarmaV2($sError, $iTipoError);
//El script que cambia el sector que se muestra
?>

<script language="javascript">
<!--
<?php
if ($iSector!=1){
	echo 'setTimeout(function(){expandesector('.$iSector.');}, 10);
';
	}
if ($bMueveScroll){
	echo 'setTimeout(function(){retornacontrol();}, 2);
';
	}
?>
-->
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js?ver=2"></script>
<?php
forma_piedepagina();