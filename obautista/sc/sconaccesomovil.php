<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.0 viernes, 22 de mayo de 2020
*/
/** Archivo sconaccesomovil.php.
* Modulo 2330 unad11terceros.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date viernes, 22 de mayo de 2020
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
	$bDebug=true;
	}
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
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
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
if (isset($APP->https)==0){$APP->https=0;}
if ($APP->https==2){
	$bObliga=false;
	if (isset($_SERVER['HTTPS'])==0){
		$bObliga=true;
		}else{
		if ($_SERVER['HTTPS']!='on'){$bObliga=true;}
		}
	if ($bObliga){
		$pageURL='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		header('Location:'.$pageURL);
		die();
		}
	}
//if (!file_exists('./opts.php')){require './opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
//require $APP->rutacomun.'libdatos.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
require $APP->rutacomun.'libaurea.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=2330;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2330='lg/lg_2330_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2330)){$mensajes_2330='lg/lg_2330_es.php';}
require $mensajes_todas;
require $mensajes_2330;
$xajax=NULL;
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
list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1, $_SESSION['unad_id_tercero'], $objDB);
if (!$bDevuelve){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=sconaccesomovil.php');
		die();
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
if (isset($_REQUEST['deb_doc'])!=0){
	list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
	//$sDebug=$sDebug.$sDebugP;
	if ($bDevuelve){
		$sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="'.$_REQUEST['deb_doc'].'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idTercero=$fila['unad11id'];
			$bOtroUsuario=true;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se verifica la ventana de trabajo para el usuario '.$fila['unad11razonsocial'].'.<br>';}
			}else{
			$sError='No se ha encontrado el documento &quot;'.$_REQUEST['deb_doc'].'&quot;';
			$_REQUEST['deb_doc']='';
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No cuenta con permiso de ingreso como otro usuario [Modulo '.$iCodModulo.' Permiso 1707].<br>';}
		$_REQUEST['deb_doc']='';
		}
	$bDebug=false;
	}else{
	$_REQUEST['deb_doc']='';
	}
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
//PROCESOS DE LA PAGINA
$idEntidad=0;
if (isset($APP->entidad)!=0){
	if ($APP->entidad==1){$idEntidad=1;}
	}
$mensajes_2339='lg/lg_2339_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2339)){$mensajes_2339='lg/lg_2339_es.php';}
$mensajes_111=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_111)){$mensajes_111=$APP->rutacomun.'lg/lg_111_es.php';}
require $mensajes_2339;
require $mensajes_111;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2330 unad11terceros
require $APP->rutacomun.'lib111.php';
require 'lib2330.php';
// -- 2339 Autorizaciones
require 'lib2339.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f2330_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2330_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2330_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f2330_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f2339_Guardar');
$xajax->register(XAJAX_FUNCTION,'f2339_Traer');
$xajax->register(XAJAX_FUNCTION,'f2339_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f2339_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2339_PintarLlaves');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf2330'])==0){$_REQUEST['paginaf2330']=1;}
if (isset($_REQUEST['lppf2330'])==0){$_REQUEST['lppf2330']=20;}
if (isset($_REQUEST['boculta2330'])==0){$_REQUEST['boculta2330']=0;}
if (isset($_REQUEST['paginaf2339'])==0){$_REQUEST['paginaf2339']=1;}
if (isset($_REQUEST['lppf2339'])==0){$_REQUEST['lppf2339']=20;}
if (isset($_REQUEST['boculta2339'])==0){$_REQUEST['boculta2339']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad11tipodoc'])==0){$_REQUEST['unad11tipodoc']='';}
if (isset($_REQUEST['unad11doc'])==0){$_REQUEST['unad11doc']='';}
if (isset($_REQUEST['unad11id'])==0){$_REQUEST['unad11id']='';}
if (isset($_REQUEST['unad11usuario'])==0){$_REQUEST['unad11usuario']='';}
if (isset($_REQUEST['unad11nombre1'])==0){$_REQUEST['unad11nombre1']='';}
if (isset($_REQUEST['unad11nombre2'])==0){$_REQUEST['unad11nombre2']='';}
if (isset($_REQUEST['unad11apellido1'])==0){$_REQUEST['unad11apellido1']='';}
if (isset($_REQUEST['unad11apellido2'])==0){$_REQUEST['unad11apellido2']='';}
if (isset($_REQUEST['unad11genero'])==0){$_REQUEST['unad11genero']='';}
if (isset($_REQUEST['unad11fechanace'])==0){$_REQUEST['unad11fechanace']='';}//{fecha_hoy();}
if (isset($_REQUEST['unad11rh'])==0){$_REQUEST['unad11rh']='';}
if (isset($_REQUEST['unad11ecivil'])==0){$_REQUEST['unad11ecivil']='';}
if (isset($_REQUEST['unad11razonsocial'])==0){$_REQUEST['unad11razonsocial']='';}
if (isset($_REQUEST['unad11direccion'])==0){$_REQUEST['unad11direccion']='';}
if (isset($_REQUEST['unad11telefono'])==0){$_REQUEST['unad11telefono']='';}
if (isset($_REQUEST['unad11correo'])==0){$_REQUEST['unad11correo']='';}
if (isset($_REQUEST['unad11accesomovil'])==0){$_REQUEST['unad11accesomovil']=0;}
if ((int)$_REQUEST['paso']>0){
	//Autorizaciones
	if (isset($_REQUEST['cara39consec'])==0){$_REQUEST['cara39consec']='';}
	if (isset($_REQUEST['cara39id'])==0){$_REQUEST['cara39id']='';}
	if (isset($_REQUEST['cara39idautoriza'])==0){$_REQUEST['cara39idautoriza']=0;}//{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['cara39idautoriza_td'])==0){$_REQUEST['cara39idautoriza_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['cara39idautoriza_doc'])==0){$_REQUEST['cara39idautoriza_doc']='';}
	if (isset($_REQUEST['cara39fechaini'])==0){$_REQUEST['cara39fechaini']='';}//{fecha_hoy();}
	if (isset($_REQUEST['cara39fechafin'])==0){$_REQUEST['cara39fechafin']='';}//{fecha_hoy();}
	if (isset($_REQUEST['cara39estado'])==0){$_REQUEST['cara39estado']='';}
	if (isset($_REQUEST['cara39detalle'])==0){$_REQUEST['cara39detalle']='';}
	if (isset($_REQUEST['cara39notasistema'])==0){$_REQUEST['cara39notasistema']='';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['busuario'])==0){$_REQUEST['busuario']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='S';}
if ((int)$_REQUEST['paso']>0){
	//Autorizaciones
	if (isset($_REQUEST['bnombre2339'])==0){$_REQUEST['bnombre2339']='';}
	//if (isset($_REQUEST['blistar2339'])==0){$_REQUEST['blistar2339']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sSQLcondi='unad11tipodoc="'.$_REQUEST['unad11tipodoc'].'" AND unad11doc="'.$_REQUEST['unad11doc'].'"';
		}else{
		$sSQLcondi='unad11id='.$_REQUEST['unad11id'].'';
		}
	$sSQL='SELECT * FROM unad11terceros WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['unad11tipodoc']=$fila['unad11tipodoc'];
		$_REQUEST['unad11doc']=$fila['unad11doc'];
		$_REQUEST['unad11id']=$fila['unad11id'];
		$_REQUEST['unad11usuario']=$fila['unad11usuario'];
		$_REQUEST['unad11nombre1']=$fila['unad11nombre1'];
		$_REQUEST['unad11nombre2']=$fila['unad11nombre2'];
		$_REQUEST['unad11apellido1']=$fila['unad11apellido1'];
		$_REQUEST['unad11apellido2']=$fila['unad11apellido2'];
		$_REQUEST['unad11genero']=$fila['unad11genero'];
		$_REQUEST['unad11fechanace']=$fila['unad11fechanace'];
		$_REQUEST['unad11rh']=$fila['unad11rh'];
		$_REQUEST['unad11ecivil']=$fila['unad11ecivil'];
		$_REQUEST['unad11razonsocial']=$fila['unad11razonsocial'];
		$_REQUEST['unad11direccion']=$fila['unad11direccion'];
		$_REQUEST['unad11telefono']=$fila['unad11telefono'];
		$_REQUEST['unad11correo']=$fila['unad11correo'];
		$_REQUEST['unad11accesomovil']=$fila['unad11accesomovil'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta2330']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f2330_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['unad11tipodoc']=$APP->tipo_doc;
	$_REQUEST['unad11doc']='';
	$_REQUEST['unad11id']='';
	$_REQUEST['unad11usuario']='';
	$_REQUEST['unad11nombre1']='';
	$_REQUEST['unad11nombre2']='';
	$_REQUEST['unad11apellido1']='';
	$_REQUEST['unad11apellido2']='';
	$_REQUEST['unad11genero']='';
	$_REQUEST['unad11fechanace']='';//fecha_hoy();
	$_REQUEST['unad11rh']='';
	$_REQUEST['unad11ecivil']='';
	$_REQUEST['unad11razonsocial']='';
	$_REQUEST['unad11direccion']='';
	$_REQUEST['unad11telefono']='';
	$_REQUEST['unad11correo']='';
	$_REQUEST['unad11accesomovil']=0;
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['cara39idtercero']='';
	$_REQUEST['cara39consec']='';
	$_REQUEST['cara39id']='';
	$_REQUEST['cara39idautoriza']=$idTercero;
	$_REQUEST['cara39idautoriza_td']=$APP->tipo_doc;
	$_REQUEST['cara39idautoriza_doc']='';
	$_REQUEST['cara39fechaini']=fecha_DiaMod();
	$_REQUEST['cara39fechafin']='';//fecha_hoy();
	$_REQUEST['cara39estado']=0;
	$_REQUEST['cara39detalle']='';
	$_REQUEST['cara39notasistema']='';
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$iAgnoIni=2020;
$iAgnoFin=fecha_agno()+1;
list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve){$seg_6=1;}
//if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
$objCombos->nuevo('unad11genero', $_REQUEST['unad11genero'], true, '{'.$ETI['msg_na'].'}');
$sSQL='SELECT unad22codopcion AS id, unad22nombre AS nombre FROM unad22combos WHERE unad22idmodulo=111 AND unad22consec=1 AND unad22activa="S" ORDER BY unad22orden';
$html_unad11genero=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('unad11rh', $_REQUEST['unad11rh'], true, '{'.$ETI['msg_na'].'}');
$sSQL='SELECT unad22codopcion AS id, unad22nombre AS nombre FROM unad22combos WHERE unad22idmodulo=111 AND unad22consec=2 AND unad22activa="S" ORDER BY unad22orden';
$html_unad11rh=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('unad11ecivil', $_REQUEST['unad11ecivil'], true, '{'.$ETI['msg_na'].'}');
$sSQL='SELECT unad21codigo AS id, unad21nombre AS nombre FROM unad21estadocivil ORDER BY unad21nombre';
$html_unad11ecivil=$objCombos->html($sSQL, $objDB);
$unad11accesomovil_nombre=$ETI['no'];
if ($_REQUEST['unad11accesomovil']!=0){$unad11accesomovil_nombre=$ETI['si'];}
$html_unad11accesomovil=html_oculto('unad11accesomovil', $_REQUEST['unad11accesomovil'], $unad11accesomovil_nombre);
if ((int)$_REQUEST['paso']==0){
	$html_unad11tipodoc=html_tipodocV2('unad11tipodoc', $_REQUEST['unad11tipodoc'], 'RevisaLlave()', false);
	}else{
	$html_unad11tipodoc=html_oculto('unad11tipodoc', $_REQUEST['unad11tipodoc']);
	list($cara39idautoriza_rs, $_REQUEST['cara39idautoriza'], $_REQUEST['cara39idautoriza_td'], $_REQUEST['cara39idautoriza_doc'])=html_tercero($_REQUEST['cara39idautoriza_td'], $_REQUEST['cara39idautoriza_doc'], $_REQUEST['cara39idautoriza'], 0, $objDB);
	$cara39estado_nombre=$ETI['msg_autorizada'];
	if ($_REQUEST['cara39estado']!=0){
		$cara39estado_nombre=$ETI['msg_revocada'];
		}
	$html_cara39estado=html_oculto('cara39estado', $_REQUEST['cara39estado'], $cara39estado_nombre);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2330()';
$objCombos->addItem('S', 'Acceso Permitido');
$objCombos->addItem('N', 'Acceso NO Permitido');
$html_blistar=$objCombos->html('', $objDB);
/*
$objCombos->nuevo('blistar2339', $_REQUEST['blistar2339'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar2339=$objCombos->comboSistema(2339, 1, $objDB, 'paginarf2339()');
*/
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=2330;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
$sCorreoMensajes='';
if ($_REQUEST['paso']>0){
	list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	list($sCorreoMensajes, $sErrorN, $sDebugM)=AUREA_CorreoNotifica($_REQUEST['unad11id'], $objDB, $bDebug);
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_2330'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf2330'];
$aParametros[102]=$_REQUEST['lppf2330'];
$aParametros[103]=$_REQUEST['bdoc'];
$aParametros[104]=$_REQUEST['bnombre'];
$aParametros[105]=$_REQUEST['busuario'];
$aParametros[106]=$_REQUEST['blistar'];
list($sTabla2330, $sDebugTabla)=f2330_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla2339='';
$sInfoSeguridad='';
if ($_REQUEST['paso']!=0){
	//Autorizaciones
	$aParametros2339[0]=$_REQUEST['unad11id'];
	$aParametros2339[100]=$idTercero;
	$aParametros2339[101]=$_REQUEST['paginaf2339'];
	$aParametros2339[102]=$_REQUEST['lppf2339'];
	//$aParametros2339[103]=$_REQUEST['bnombre2339'];
	//$aParametros2339[104]=$_REQUEST['blistar2339'];
	list($sTabla2339, $sDebugTabla)=f2339_TablaDetalleV2($aParametros2339, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Ver si esta reportado
	$sSQL='SELECT unae13idia FROM unae13enrevision WHERE unae13idtercero='.$_REQUEST['unad11id'].' AND unae13estado<>2 ORDER BY unae13idia DESC';
	$tabla=$objDB->ejecutasql($sSQL);
	$iNumSospechas=$objDB->nf($tabla);
	if ($iNumSospechas>0){
		$fila=$objDB->sf($tabla);
		$sInfoSeguridad='<span class="rojo">ALERTA:</span> El usuario <b>presenta '.$iNumSospechas.' accesos bajo supervisi&oacute;n</b>, &uacute;ltimo d&iacute;a supervisado '.fecha_DesdeNumero($fila['unae13idia']);
		}
	//Entregamos datos de matricula.
	if ($sInfoSeguridad!=''){$sInfoSeguridad=$sInfoSeguridad.'<hr>';}
	$sInfoSeguridad=$sInfoSeguridad.'<b>Datos de Matricula</b>';
	$sFinDatos='';
	$sSQL='SELECT TB.core16peraca, TB.core16idcead, T24.unad24nombre, TB.core16idprograma, T9.core09nombre, T2.exte02nombre, TB.core16numcursos, TB.cara16numcreditos 
FROM core16actamatricula AS TB, exte02per_aca AS T2, core09programa AS T9, unad24sede AS T24 
WHERE TB.core16tercero='.$_REQUEST['unad11id'].' AND TB.core16peraca=T2.exte02id AND TB.core16idprograma=T9.core09id AND TB.core16idcead=T24.unad24id
ORDER BY TB.core16peraca DESC';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)==0){
		$sInfoSeguridad=$sInfoSeguridad.'<br> NO REGISTRA MATRICULA';
		}else{
		$sInfoSeguridad=$sInfoSeguridad.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.'Periodo'.'</b></td>
<td><b>'.'Programa'.'</b></td>
<td><b>'.'Centro'.'</b></td>
<td><b>'.'Cursos'.'</b></td>
<td><b>'.'Creditos'.'</b></td>
</tr>';
		$sFinDatos='</table>';
		}
	$tlinea=1;
	while ($fila=$objDB->sf($tabla)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$sPrograma=cadena_notildes($fila['core09nombre']);
		$sCentro=cadena_notildes($fila['unad24nombre']);
		$sInfoSeguridad=$sInfoSeguridad.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($fila['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$sPrograma.$sSufijo.'</b></td>
<td>'.$sPrefijo.$sCentro.$sSufijo.'</b></td>
<td>'.$sPrefijo.$fila['core16numcursos'].$sSufijo.'</b></td>
<td>'.$sPrefijo.$fila['cara16numcreditos'].$sSufijo.'</b></td>
</tr>';
		}
	$sInfoSeguridad=$sInfoSeguridad.$sFinDatos.'<div class="salto1px"></div><b>Caracterizaciones</b>';
	//Revisamos la caraterización
	$sFinDatos='';
	$sSQL='SELECT TB.cara01completa, TB.cara01fechaencuesta, cara01ciudad, TB.cara01campus_internetreside 
FROM cara01encuesta AS TB
WHERE TB.cara01idtercero='.$_REQUEST['unad11id'].' 
ORDER BY TB.cara01fechaencuesta DESC';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)==0){
		$sInfoSeguridad=$sInfoSeguridad.'<br> NO REGISTRA MATRICULA';
		}else{
		$sInfoSeguridad=$sInfoSeguridad.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.'Fecha'.'</b></td>
<td><b>'.'Estado'.'</b></td>
<td><b>'.'Ciudad'.'</b></td>
<td><b>'.'Acceso Internet'.'</b></td>
</tr>';
		$sFinDatos='</table>';
		}
	$tlinea=1;
	while ($fila=$objDB->sf($tabla)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$sCompleta='Abierta';
		if ($fila['cara01completa']=='S'){$sCompleta='Cerrada';}
		$sCiudad='['.$fila['cara01ciudad'].']';
		$sAcceso='[Dato no disponible]';
		$sSQL='SELECT unad20nombre FROM unad20ciudad WHERE unad20codigo="'.$fila['cara01ciudad'].'"';
		$tabla20=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla20)>0){
			$fila20=$objDB->sf($tabla20);
			$sCiudad=cadena_notildes($fila20['unad20nombre']);
			}
		switch($fila['cara01campus_internetreside']){
			case 1: 
			$sAcceso='Permanente (sin interrupciones)';
			$sPrefijo='<span class="verde">';
			$sSufijo='</span>';
			break;
			case 2: 
			$sAcceso='Intermitente (servicio interrumpido)';
			$sPrefijo='<span class="naranja">';
			$sSufijo='</span>';
			break;
			case 3: 
			$sAcceso='No cuenta con el servicio (con servicio disponible 1 o 2 días por semana)';
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			break;
			}
		$sInfoSeguridad=$sInfoSeguridad.'<tr'.$sClass.'>
<td>'.$sPrefijo.fecha_DesdeNumero($fila['cara01fechaencuesta']).$sSufijo.'</td>
<td>'.$sPrefijo.$sCompleta.$sSufijo.'</td>
<td>'.$sPrefijo.$sCiudad.$sSufijo.'</td>
<td>'.$sPrefijo.$sAcceso.$sSufijo.'</td>
</tr>';
		}
	$sInfoSeguridad=$sInfoSeguridad.$sFinDatos;
	}
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2330']);
echo $et_menu;
forma_mitad();
if (false){
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
<!--
function limpiapagina(){
	expandesector(98);
	window.document.frmedita.paso.value=-1;
	window.document.frmedita.submit();
	}
function enviaguardar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	var dpaso=window.document.frmedita.paso;
	if (dpaso.value==0){
		dpaso.value=10;
		}else{
		dpaso.value=12;
		}
	window.document.frmedita.submit();
	}
function cambiapagina(){
	expandesector(98);
	window.document.frmedita.submit();
	}
function cambiapaginaV2(){
	expandesector(98);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function expandepanel(codigo,estado,valor){
	var objdiv= document.getElementById('div_p'+codigo);
	var objban= document.getElementById('boculta'+codigo);
	var otroestado='none';
	if (estado=='none'){otroestado='block';}
	objdiv.style.display=estado;
	objban.value=valor;
	verboton('btrecoge'+codigo,estado);
	verboton('btexpande'+codigo,otroestado);
	}
function verboton(idboton,estado){
	var objbt=document.getElementById(idboton);
	objbt.style.display=estado;
	}
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector2').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
<?php
if ($_REQUEST['paso']==2){
?>
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
<?php
	}
?>
	}
function ter_retorna(){
	var sRetorna=window.document.frmedita.div96v2.value;
	if (sRetorna!=''){
		var idcampo=window.document.frmedita.div96campo.value;
		var illave=window.document.frmedita.div96llave.value;
		var did=document.getElementById(idcampo);
		var dtd=document.getElementById(idcampo+'_td');
		var ddoc=document.getElementById(idcampo+'_doc');
		dtd.value=window.document.frmedita.div96v1.value;
		ddoc.value=sRetorna;
		did.value=window.document.frmedita.div96v3.value;
		ter_muestra(idcampo, illave);
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ter_muestra(idcampo, illave){
	var params=new Array();
	params[1]=document.getElementById(idcampo+'_doc').value;
	if (params[1]!=''){
		params[0]=document.getElementById(idcampo+'_td').value;
		params[2]=idcampo;
		params[3]='div_'+idcampo;
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		xajax_unad11_Mostrar_v2(params);
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		//FuncionCuandoNoHayNada
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2330.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2330.value;
		window.document.frmlista.nombrearchivo.value='Acceso a moviles';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	window.document.frmimpp.v3.value=window.document.frmedita.blistar.value;
	//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	var sError='';
	if (window.document.frmedita.seg_6.value!=1){sError="<?php echo $ERR['6']; ?>";}
	//if (sError==''){/*Agregar validaciones*/}
	if (sError==''){
		asignarvariables();
		window.document.frmimpp.action='e2330.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2330.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
		}else{
		window.alert("<?php echo $ERR['5']; ?>");
		}
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")){
		expandesector(98);
		window.document.frmedita.paso.value=13;
		window.document.frmedita.submit();
		}
	}
function RevisaLlave(){
	var datos= new Array();
	datos[1]=window.document.frmedita.unad11tipodoc.value;
	datos[2]=window.document.frmedita.unad11doc.value;
	if ((datos[1]!='')&&(datos[2]!='')){
		xajax_f2330_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2){
	window.document.frmedita.unad11tipodoc.value=String(llave1);
	window.document.frmedita.unad11doc.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf2330(llave1){
	window.document.frmedita.unad11id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf2330(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf2330.value;
	params[102]=window.document.frmedita.lppf2330.value;
	params[103]=window.document.frmedita.bdoc.value;
	params[104]=window.document.frmedita.bnombre.value;
	params[105]=window.document.frmedita.busuario.value;
	params[106]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f2330detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2330" name="paginaf2330" type="hidden" value="'+params[101]+'" /><input id="lppf2330" name="lppf2330" type="hidden" value="'+params[102]+'" />';
	xajax_f2330_HtmlTabla(params);
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
function siguienteobjeto(){}
document.onkeydown=function(e){
	if (document.all){
		if (event.keyCode==13){event.keyCode=9;}
		}else{
		if (e.which==13){siguienteobjeto();}
		}
	}
function objinicial(){
	document.getElementById("unad11tipodoc").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f2330_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='cara39idautoriza'){
		ter_traerxid('cara39idautoriza', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantenerV4();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function AyudaLocal(sCampo){
	var divAyuda=document.getElementById('div_ayuda_'+sCampo);
	if (typeof divAyuda==='undefined'){
		}else{
		verboton('cmdAyuda_'+sCampo, 'none');
		var sMensaje='Lo que quiera decir.';
		//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
		divAyuda.innerHTML=sMensaje;
		divAyuda.style.display='block';
		}
	}
function cierraDiv96(ref){
	var sRetorna=window.document.frmedita.div96v2.value;
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ajustaforma(){
	var dtd=document.getElementById('unad11tipodoc');
	var divnom = document.getElementById('divnombres');
	var divrs = document.getElementById('divrazon');
	var sestado='block';
	var sestado2='none';
		if (dtd.value=="NI"){
			sestado='none';
			sestado2='block';
			}
		divnom.style.display=sestado;
		divrs.style.display=sestado2;
	}
// -->
</script>
<?php
$id2330=0;
if ($_REQUEST['paso']!=0){
	$id2330=$_REQUEST['unad11id'];
?>
<script language="javascript" src="jsi/js2339.js?v=2"></script>
<?php
	}
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2330.php" target="_blank">
<input id="r" name="r" type="hidden" value="2330" />
<input id="id2330" name="id2330" type="hidden" value="<?php echo $id2330; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="id11" name="id11" type="hidden" value="<?php echo $idTercero; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
$bHayImprimir=false;
$sScript='imprimeexcel()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//if ($iNumFormatosImprime>0){
			//$sScript='expandesector(94)';
			//}
		//$sClaseBoton='btEnviarPDF'; //btUpPrint
		//if ($id_rpt!=0){$sScript='verrpt()';}
		}
	}
if ($bHayImprimir){
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2330'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=true;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta2330" name="boculta2330" type="hidden" value="<?php echo $_REQUEST['boculta2330']; ?>" />
<label class="Label30">
<input id="btexpande2330" name="btexpande2330" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2330,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2330']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2330" name="btrecoge2330" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2330,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2330']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2330" style="display:<?php if ($_REQUEST['boculta2330']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['unad11doc'];
?>
</label>
<label class="Label90">
<?php
echo $html_unad11tipodoc;
?>
</label>
<label class="Label220">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="unad11doc" name="unad11doc" type="text" value="<?php echo $_REQUEST['unad11doc']; ?>" maxlength="13" onchange="RevisaLlave()" class="veinte" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11doc']; ?>"/>
<?php
	}else{
	echo html_oculto('unad11doc', $_REQUEST['unad11doc']);
	}
?>
</label>
<label class="Label60">
<?php
echo $ETI['unad11id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('unad11id', $_REQUEST['unad11id'], formato_numero($_REQUEST['unad11id']));
?>
</label>
<input id="unad11usuario" name="unad11usuario" type="hidden" value="<?php echo $_REQUEST['unad11usuario']; ?>"/>
<div class="salto1px"></div>
<div id="divnombres">
<label class="Label130">
<?php
echo $ETI['unad11nombre1'];
?>
</label>
<label>
<input id="unad11nombre1" name="unad11nombre1" type="text" value="<?php echo $_REQUEST['unad11nombre1']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11nombre1']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['unad11nombre2'];
?>
</label>
<label>
<input id="unad11nombre2" name="unad11nombre2" type="text" value="<?php echo $_REQUEST['unad11nombre2']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11nombre2']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11apellido1'];
?>
</label>
<label>
<input id="unad11apellido1" name="unad11apellido1" type="text" value="<?php echo $_REQUEST['unad11apellido1']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11apellido1']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['unad11apellido2'];
?>
</label>
<label>
<input id="unad11apellido2" name="unad11apellido2" type="text" value="<?php echo $_REQUEST['unad11apellido2']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11apellido2']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11genero'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11genero;
?>
</label>
<label class="Label160">
<?php
echo $ETI['unad11fechanace'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('unad11fechanace', $_REQUEST['unad11fechanace'], true, '', 1900, fecha_agno());
?>
</div>
<label class="Label60">
<?php
echo $ETI['unad11rh'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11rh;
?>
</label>
<label class="Label130">
<?php
echo $ETI['unad11ecivil'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11ecivil;
?>
</label>
</div>
<div id="divrazon">
<label class="L">
<?php
echo $ETI['unad11razonsocial'];
?>

<input id="unad11razonsocial" name="unad11razonsocial" type="text" value="<?php echo $_REQUEST['unad11razonsocial']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11razonsocial']; ?>"/>
</label>
</div>
<label class="L">
<?php
echo $ETI['unad11direccion'];
?>

<input id="unad11direccion" name="unad11direccion" type="text" value="<?php echo $_REQUEST['unad11direccion']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11direccion']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['unad11telefono'];
?>
</label>
<label class="Label220">
<input id="unad11telefono" name="unad11telefono" type="text" value="<?php echo $_REQUEST['unad11telefono']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11telefono']; ?>"/>
</label>
<label class="Label60">
<?php
echo $ETI['unad11correo'];
?>
</label>
<label class="Label380">
<input id="unad11correo" name="unad11correo" type="text" value="<?php echo $_REQUEST['unad11correo']; ?>" maxlength="50" class="Label350" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11correo']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['unad11accesomovil'];
?>
</label>
<label>
<div id="div_unad11accesomovil">
<?php
echo $html_unad11accesomovil;
?>
</div>
</label>
<?php
if ($sInfoSeguridad!=''){
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $sInfoSeguridad;
?>
<div class="salto1px"></div>
</div>
<?php
	}
// -- Inicia Grupo campos 2339 Autorizaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2339'];
?>
</label>
<input id="boculta2339" name="boculta2339" type="hidden" value="<?php echo $_REQUEST['boculta2339']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel2339" name="btexcel2339" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2339();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande2339" name="btexpande2339" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2339,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2339']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2339" name="btrecoge2339" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2339,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2339']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p2339" style="display:<?php if ($_REQUEST['boculta2339']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['cara39consec'];
?>
</label>
<label class="Label130"><div id="div_cara39consec">
<?php
if ((int)$_REQUEST['cara39id']==0){
?>
<input id="cara39consec" name="cara39consec" type="text" value="<?php echo $_REQUEST['cara39consec']; ?>" onchange="revisaf2339()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('cara39consec', $_REQUEST['cara39consec'], formato_numero($_REQUEST['cara39consec']));
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['cara39id'];
?>
</label>
<label class="Label60"><div id="div_cara39id">
<?php
	echo html_oculto('cara39id', $_REQUEST['cara39id'], formato_numero($_REQUEST['cara39id']));
?>
</div></label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara39idautoriza'];
?>
</label>
<div class="salto1px"></div>
<input id="cara39idautoriza" name="cara39idautoriza" type="hidden" value="<?php echo $_REQUEST['cara39idautoriza']; ?>"/>
<div id="div_cara39idautoriza_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('cara39idautoriza', $_REQUEST['cara39idautoriza_td'], $_REQUEST['cara39idautoriza_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cara39idautoriza" class="L"><?php echo $cara39idautoriza_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['cara39fechaini'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('cara39fechaini', $_REQUEST['cara39fechaini'], false, '', $iAgnoIni, $iAgnoFin);
?>
</div>
<!--
<label class="Label30">
<input id="bcara39fechaini_hoy" name="bcara39fechaini_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('cara39fechaini','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara39fechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('cara39fechafin', $_REQUEST['cara39fechafin'], true, '', $iAgnoIni, $iAgnoFin);
?>
</div>
<!--
<label class="Label30">
<input id="bcara39fechafin_hoy" name="bcara39fechafin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('cara39fechafin','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara39estado'];
?>
</label>
<label>
<div id="div_cara39estado">
<?php
echo $html_cara39estado;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<label class="txtAreaS">
<?php
echo $ETI['cara39detalle'];
?>
<textarea id="cara39detalle" name="cara39detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara39detalle']; ?>"><?php echo $_REQUEST['cara39detalle']; ?></textarea>
</label>
<div style="display:none">
<label class="txtAreaS">
<?php
echo $ETI['cara39notasistema'];
?>
<?php
echo html_oculto('cara39notasistema', $_REQUEST['cara39notasistema']);
?>
</label>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda2339" name="bguarda2339" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2339()" title="<?php echo $ETI['bt_mini_guardar_2339']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia2339" name="blimpia2339" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2339()" title="<?php echo $ETI['bt_mini_limpiar_2339']; ?>"/>
</label>
<label class="Label30">
<input id="belimina2339" name="belimina2339" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2339()" title="<?php echo $ETI['bt_mini_eliminar_2339']; ?>" style="display:<?php if ((int)$_REQUEST['cara39id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p2339
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f2339detalle">
<?php
echo $sTabla2339;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2339 Autorizaciones
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2330
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>'.$ETI['bloque1'].'</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label130">
Documento
</label>
<label class="Label220">
<input name="bdoc" type="text" id="bdoc" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf2330()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label class="Label220">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2330()" autocomplete="off"/>
</label>
<input id="busuario" name="busuario" type="hidden" value="<?php echo $_REQUEST['busuario']; ?>"/>
<label class="Label60">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label250">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f2330detalle">
<?php
echo $sTabla2330;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_sector2'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_2330" name="titulo_2330" type="hidden" value="<?php echo $ETI['titulo_2330']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector96 -->


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>'.$ETI['titulo_2330'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2330'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div><!-- /Termina la marquesina -->
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
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
<div class="flotante">
<?php
if ($_REQUEST['paso']==2){
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
	}
?>
</div>
<?php
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
echo 'setTimeout(function(){ajustaforma();}, 2);
';
?>
-->
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<?php
if ($_REQUEST['paso']==0){
?>
<script language="javascript">
<!--
$().ready(function(){
//$("#bperiodo").chosen();
});
-->
</script>
<?php
	}
?>
<script language="javascript" src="ac_2330.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>