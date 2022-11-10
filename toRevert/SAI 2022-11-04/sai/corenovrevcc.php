<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10b martes, 2 de febrero de 2021
*/
/** Archivo corenovedadmat.php.
* Modulo 12206 corf06novedad.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date martes, 2 de febrero de 2021
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
require $APP->rutacomun.'libdatos.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
require $APP->rutacomun.'libaurea.php';
require $APP->rutacomun.'libmail.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=12246;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_12206=$APP->rutacomun.'lg/lg_12206_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_12206)){$mensajes_12206=$APP->rutacomun.'lg/lg_12206_es.php';}
require $mensajes_todas;
require $mensajes_12206;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$sAnchoExpandeContrae=' style="width:62px;"';
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
		header('Location:noticia.php?ret=corenovedadmat.php');
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
$mensajes_12207=$APP->rutacomun.'lg/lg_12207_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_12207)){$mensajes_12207=$APP->rutacomun.'lg/lg_12207_es.php';}
$mensajes_12208=$APP->rutacomun.'lg/lg_12208_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_12208)){$mensajes_12208=$APP->rutacomun.'lg/lg_12208_es.php';}
$mensajes_12215=$APP->rutacomun.'lg/lg_12215_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_12215)){$mensajes_12215=$APP->rutacomun.'lg/lg_12215_es.php';}
require $mensajes_12207;
require $mensajes_12208;
require $mensajes_12215;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
}
// -- 12206 corf06novedad
require $APP->rutacomun.'lib12206.php';
// -- 12207 Cursos
require $APP->rutacomun.'lib12207.php';
// -- 12208 Anotaciones
require $APP->rutacomun.'lib12208.php';
// -- 12215 Cambios de estado
require $APP->rutacomun.'lib12215.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'f12206_Combocorf06idperiodo');
$xajax->register(XAJAX_FUNCTION,'f12206_Combocorf06idescuela');
$xajax->register(XAJAX_FUNCTION,'f12206_Combocorf06idprograma');
$xajax->register(XAJAX_FUNCTION,'f12206_Combocorf06idcentro');
$xajax->register(XAJAX_FUNCTION,'f12206_Combocorf06idcentrodest');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f12206_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f12206_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f12206_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f12206_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f12207_Guardar');
$xajax->register(XAJAX_FUNCTION,'f12207_Traer');
$xajax->register(XAJAX_FUNCTION,'f12207_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f12207_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f12207_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_corf08idarchivoanexo');
$xajax->register(XAJAX_FUNCTION,'f12208_Guardar');
$xajax->register(XAJAX_FUNCTION,'f12208_Traer');
$xajax->register(XAJAX_FUNCTION,'f12208_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f12208_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f12208_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f12206_Combobprograma');
$xajax->register(XAJAX_FUNCTION,'f12206_Combobcentro');
$xajax->register(XAJAX_FUNCTION,'f12215_HtmlTabla');
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
if (isset($_REQUEST['paginaf12206'])==0){$_REQUEST['paginaf12206']=1;}
if (isset($_REQUEST['lppf12206'])==0){$_REQUEST['lppf12206']=20;}
if (isset($_REQUEST['boculta12206'])==0){$_REQUEST['boculta12206']=0;}
if (isset($_REQUEST['paginaf12207'])==0){$_REQUEST['paginaf12207']=1;}
if (isset($_REQUEST['lppf12207'])==0){$_REQUEST['lppf12207']=20;}
if (isset($_REQUEST['boculta12207'])==0){$_REQUEST['boculta12207']=0;}
if (isset($_REQUEST['paginaf12208'])==0){$_REQUEST['paginaf12208']=1;}
if (isset($_REQUEST['lppf12208'])==0){$_REQUEST['lppf12208']=20;}
if (isset($_REQUEST['boculta12208'])==0){$_REQUEST['boculta12208']=0;}
if (isset($_REQUEST['paginaf12215'])==0){$_REQUEST['paginaf12215']=1;}
if (isset($_REQUEST['lppf12215'])==0){$_REQUEST['lppf12215']=20;}
if (isset($_REQUEST['boculta12215'])==0){$_REQUEST['boculta12215']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['corf06tiponov'])==0){$_REQUEST['corf06tiponov']='';}
if (isset($_REQUEST['corf06consec'])==0){$_REQUEST['corf06consec']='';}
if (isset($_REQUEST['corf06consec_nuevo'])==0){$_REQUEST['corf06consec_nuevo']='';}
if (isset($_REQUEST['corf06id'])==0){$_REQUEST['corf06id']='';}
if (isset($_REQUEST['corf06estado'])==0){$_REQUEST['corf06estado']=0;}
if (isset($_REQUEST['corf06idestudiante'])==0){$_REQUEST['corf06idestudiante']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['corf06idestudiante_td'])==0){$_REQUEST['corf06idestudiante_td']=$APP->tipo_doc;}
if (isset($_REQUEST['corf06idestudiante_doc'])==0){$_REQUEST['corf06idestudiante_doc']='';}
if (isset($_REQUEST['corf06idperiodo'])==0){$_REQUEST['corf06idperiodo']='';}
if (isset($_REQUEST['corf06idescuela'])==0){$_REQUEST['corf06idescuela']='';}
if (isset($_REQUEST['corf06idprograma'])==0){$_REQUEST['corf06idprograma']='';}
if (isset($_REQUEST['corf06fecha'])==0){$_REQUEST['corf06fecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['corf06hora'])==0){$_REQUEST['corf06hora']=fecha_hora();}
if (isset($_REQUEST['corf06min'])==0){$_REQUEST['corf06min']=fecha_minuto();}
if (isset($_REQUEST['corf06autoriza1'])==0){$_REQUEST['corf06autoriza1']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['corf06autoriza1_td'])==0){$_REQUEST['corf06autoriza1_td']=$APP->tipo_doc;}
if (isset($_REQUEST['corf06autoriza1_doc'])==0){$_REQUEST['corf06autoriza1_doc']='';}
if (isset($_REQUEST['corf06autoriza2'])==0){$_REQUEST['corf06autoriza2']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['corf06autoriza2_td'])==0){$_REQUEST['corf06autoriza2_td']=$APP->tipo_doc;}
if (isset($_REQUEST['corf06autoriza2_doc'])==0){$_REQUEST['corf06autoriza2_doc']='';}
if (isset($_REQUEST['corf06fechaplica'])==0){$_REQUEST['corf06fechaplica']='';}//{fecha_hoy();}
if (isset($_REQUEST['corf06horaaplica'])==0){$_REQUEST['corf06horaaplica']=fecha_hora();}
if (isset($_REQUEST['corf06minaplica'])==0){$_REQUEST['corf06minaplica']=fecha_minuto();}
if (isset($_REQUEST['corf06idsesion'])==0){$_REQUEST['corf06idsesion']='';}
if (isset($_REQUEST['corf06idzona'])==0){$_REQUEST['corf06idzona']='';}
if (isset($_REQUEST['corf06idcentro'])==0){$_REQUEST['corf06idcentro']='';}
if (isset($_REQUEST['corf06idzonadest'])==0){$_REQUEST['corf06idzonadest']='';}
if (isset($_REQUEST['corf06idcentrodest'])==0){$_REQUEST['corf06idcentrodest']='';}
if (isset($_REQUEST['corf06fechaultatencion'])==0){$_REQUEST['corf06fechaultatencion']='';}
if (isset($_REQUEST['corf06fechacierre'])==0){$_REQUEST['corf06fechacierre']='';}
if (isset($_REQUEST['corf06totaldias'])==0){$_REQUEST['corf06totaldias']='';}
if (isset($_REQUEST['corf06idestprograma'])==0){$_REQUEST['corf06idestprograma']='';}
if (isset($_REQUEST['corf06idactoadmin'])==0){$_REQUEST['corf06idactoadmin']=0;}
if ((int)$_REQUEST['paso']>0){
	//Cursos
	if (isset($_REQUEST['corf07idcurso'])==0){$_REQUEST['corf07idcurso']='';}
	if (isset($_REQUEST['corf07id'])==0){$_REQUEST['corf07id']='';}
	if (isset($_REQUEST['corf07tipo'])==0){$_REQUEST['corf07tipo']='';}
	//Anotaciones
	if (isset($_REQUEST['corf08consec'])==0){$_REQUEST['corf08consec']='';}
	if (isset($_REQUEST['corf08id'])==0){$_REQUEST['corf08id']='';}
	if (isset($_REQUEST['corf08fecha'])==0){$_REQUEST['corf08fecha']='';}//{fecha_hoy();}
	if (isset($_REQUEST['corf08hora'])==0){$_REQUEST['corf08hora']='';}
	if (isset($_REQUEST['corf08min'])==0){$_REQUEST['corf08min']='';}
	if (isset($_REQUEST['corf08nota'])==0){$_REQUEST['corf08nota']='';}
	if (isset($_REQUEST['corf08nota_b'])==0){$_REQUEST['corf08nota_b']='';}
	if (isset($_REQUEST['corf08nota_c'])==0){$_REQUEST['corf08nota_c']='';}
	if (isset($_REQUEST['corf08idorigenanexo'])==0){$_REQUEST['corf08idorigenanexo']=0;}
	if (isset($_REQUEST['corf08idarchivoanexo'])==0){$_REQUEST['corf08idarchivoanexo']=0;}
	}
// Espacio para inicializar otras variables
$bTraerEntorno=false;
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']=6;}
if (isset($_REQUEST['bperiodo'])==0){$_REQUEST['bperiodo']='';}
if (isset($_REQUEST['bescuela'])==0){$_REQUEST['bescuela']='';$bTraerEntorno=true;}
if (isset($_REQUEST['blistar2'])==0){$_REQUEST['blistar2']=1;}
if (isset($_REQUEST['bprograma'])==0){$_REQUEST['bprograma']='';}
if (isset($_REQUEST['bzona'])==0){$_REQUEST['bzona']='';}
if (isset($_REQUEST['bcentro'])==0){$_REQUEST['bcentro']='';}
if ($bTraerEntorno){
	$sSQL='SELECT * FROM unad95entorno WHERE unad95id='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['unad95escuela']!=0){$_REQUEST['bescuela']=$fila['unad95escuela'];}
		if ($fila['unad95programa']!=0){$_REQUEST['bprograma']=$fila['unad95programa'];}
		if ($fila['unad95zona']!=0){$_REQUEST['bzona']=$fila['unad95zona'];}
		if ($fila['unad95centro']!=0){$_REQUEST['bcentro']=$fila['unad95centro'];}
	}
}
if ((int)$_REQUEST['paso']>0){
	//Cursos
	if (isset($_REQUEST['bnombre12207'])==0){$_REQUEST['bnombre12207']='';}
	//if (isset($_REQUEST['blistar12207'])==0){$_REQUEST['blistar12207']='';}
	//Anotaciones
	if (isset($_REQUEST['bnombre12208'])==0){$_REQUEST['bnombre12208']='';}
	//if (isset($_REQUEST['blistar12208'])==0){$_REQUEST['blistar12208']='';}
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['corf06idestudiante_td']=$APP->tipo_doc;
	$_REQUEST['corf06idestudiante_doc']='';
	$_REQUEST['corf06autoriza1_td']=$APP->tipo_doc;
	$_REQUEST['corf06autoriza1_doc']='';
	$_REQUEST['corf06autoriza2_td']=$APP->tipo_doc;
	$_REQUEST['corf06autoriza2_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='corf06tiponov='.$_REQUEST['corf06tiponov'].' AND corf06consec='.$_REQUEST['corf06consec'].'';
	}else{
		$sSQLcondi='corf06id='.$_REQUEST['corf06id'].'';
	}
	$sSQL='SELECT * FROM corf06novedad WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['corf06tiponov']=$fila['corf06tiponov'];
		$_REQUEST['corf06consec']=$fila['corf06consec'];
		$_REQUEST['corf06id']=$fila['corf06id'];
		$_REQUEST['corf06estado']=$fila['corf06estado'];
		$_REQUEST['corf06idestudiante']=$fila['corf06idestudiante'];
		$_REQUEST['corf06idperiodo']=$fila['corf06idperiodo'];
		$_REQUEST['corf06idescuela']=$fila['corf06idescuela'];
		$_REQUEST['corf06idprograma']=$fila['corf06idprograma'];
		$_REQUEST['corf06fecha']=$fila['corf06fecha'];
		$_REQUEST['corf06hora']=$fila['corf06hora'];
		$_REQUEST['corf06min']=$fila['corf06min'];
		$_REQUEST['corf06autoriza1']=$fila['corf06autoriza1'];
		$_REQUEST['corf06autoriza2']=$fila['corf06autoriza2'];
		$_REQUEST['corf06fechaplica']=$fila['corf06fechaplica'];
		$_REQUEST['corf06horaaplica']=$fila['corf06horaaplica'];
		$_REQUEST['corf06minaplica']=$fila['corf06minaplica'];
		$_REQUEST['corf06idsesion']=$fila['corf06idsesion'];
		$_REQUEST['corf06idzona']=$fila['corf06idzona'];
		$_REQUEST['corf06idcentro']=$fila['corf06idcentro'];
		$_REQUEST['corf06idzonadest']=$fila['corf06idzonadest'];
		$_REQUEST['corf06idcentrodest']=$fila['corf06idcentrodest'];
		$_REQUEST['corf06fechaultatencion']=$fila['corf06fechaultatencion'];
		$_REQUEST['corf06fechacierre']=$fila['corf06fechacierre'];
		$_REQUEST['corf06totaldias']=$fila['corf06totaldias'];
		$_REQUEST['corf06idestprograma']=$fila['corf06idestprograma'];
		$_REQUEST['corf06idactoadmin']=$fila['corf06idactoadmin'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta12206']=0;
		$bLimpiaHijos=true;
		if ($_REQUEST['corf06tiponov']!=6){
			$sError='La novedad socilitada no es un cambio de centro.';
			$_REQUEST['paso']=-1;
		}
	}else{
		$_REQUEST['paso']=0;
	}
}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['corf06estado']=1;
	$bCerrando=true;
	}
//Abrir
if ($_REQUEST['paso']==17){
	$_REQUEST['paso']=2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if (!seg_revisa_permiso($iCodModulo, 14, $objDB)){
		$sError=$ERR['3'];
		}
	//Otras restricciones para abrir.
	if ($sError==''){
		//$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
		}
	if ($sError!=''){
		//$_REQUEST['corf06estado']=7;
		}else{
		$sSQL='UPDATE corf06novedad SET corf06estado=0 WHERE corf06id='.$_REQUEST['corf06id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['corf06id'], 'Abre Novedades de matricula', $objDB);
		$_REQUEST['corf06estado']=0;
		$sError='<b>La solicitud ha sido abierta</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f12206_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		if ($sErrorCerrando!=''){
			$iTipoError=0;
			$sError='<b>'.$ETI['msg_itemguardado'].'</b><br>'.$sErrorCerrando;
			}
		if ($bCerrando){
			$sError='<b>'.$ETI['msg_itemcerrado'].'</b>';
			}
		}
	}
if ($bCerrando){
	//acciones del cerrado
	}
// Pasa a estudio.
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	list($sError, $sDebugC, $sMensajeNotifica)=f12206_CambiaEstado($_REQUEST['corf06id'], 3, $objDB, $bDebug);
	if ($sError==''){
		$_REQUEST['corf06estado']=3;
		$sError='Proceso completo '.$sMensajeNotifica;
		$iTipoError=1;
		}
	}
// Negado.
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=2;
	if ($sError==''){
		//Insertamos la anotación primero.
		$valores[1]=$_REQUEST['corf06id'];
		$valores[2]='';
		$valores[3]='';
		$valores[4]=0;
		$valores[5]=0;
		$valores[6]=0;
		$valores[7]=$_REQUEST['corf08nota_b'];
		list($sError, $iAccion, $corf08id, $sDebug)=f12208_db_Guardar($valores, $objDB, $bDebug);
		}
	if ($sError==''){
		list($sError, $sDebugC, $sMensajeNotifica)=f12206_CambiaEstado($_REQUEST['corf06id'], 10, $objDB, $bDebug);
		}
	if ($sError==''){
		$_REQUEST['corf06estado']=10;
		$sError='Proceso completo '.$sMensajeNotifica;

		$iTipoError=1;
		}
	}
// Devolución.
if ($_REQUEST['paso']==26){
	$_REQUEST['paso']=2;
	if ($sError==''){
		//Insertamos la anotación primero.
		$valores[1]=$_REQUEST['corf06id'];
		$valores[2]='';
		$valores[3]='';
		$valores[4]=0;
		$valores[5]=0;
		$valores[6]=0;
		$valores[7]=$_REQUEST['corf08nota_b'];
		list($sError, $iAccion, $corf08id, $sDebug)=f12208_db_Guardar($valores, $objDB, $bDebug);
		}
	if ($sError==''){
		list($sError, $sDebugC, $sMensajeNotifica)=f12206_CambiaEstado($_REQUEST['corf06id'], 2, $objDB, $bDebug);
		}
	if ($sError==''){
		$_REQUEST['corf06estado']=2;
		$sError='Proceso completo '.$sMensajeNotifica;
		$iTipoError=1;
		}
	}
//Notificar un evento.
if ($_REQUEST['paso']==23){
	$_REQUEST['paso']=2;
	list($sError, $sDebugN, $sMensajeNotifica)=f12206_NotificarEvento($_REQUEST['corf06id'], $objDB, $bDebug);
	if ($sError==''){
		$sError='Proceso completo '.$sMensajeNotifica;
		$iTipoError=1;
		}
	}
// Solicitar reposición
if ($_REQUEST['paso']==24){
	$_REQUEST['paso']=2;
	if ($sError==''){
		//Insertamos la anotación primero.
		$valores[1]=$_REQUEST['corf06id'];
		$valores[2]='';
		$valores[3]='';
		$valores[4]=0;
		$valores[5]=0;
		$valores[6]=0;
		$valores[7]=$_REQUEST['corf08nota_c'];
		list($sError, $iAccion, $corf08id, $sDebug)=f12208_db_Guardar($valores, $objDB, $bDebug);
		}
	if ($sError==''){
		list($sError, $sDebugC, $sMensajeNotifica)=f12206_CambiaEstado($_REQUEST['corf06id'], 4, $objDB, $bDebug);
		}
	if ($sError==''){
		$_REQUEST['corf06estado']=4;
		$sError='Proceso completo '.$sMensajeNotifica;
		$iTipoError=1;
		}
	}
if ($_REQUEST['paso']==25){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	if ($sError==''){
		list($sError, $sDebugC, $sMensajeNotifica)=f12206_CambiaEstado($_REQUEST['corf06id'], 7, $objDB, $bDebug);
		if ($sError==''){
			if ((int)$_REQUEST['corf06fechaplica']==0){
				$corf06fechaplica=fecha_DiaMod();
				$corf06horaaplica=fecha_hora();
				$corf06minaplica=fecha_minuto();
				$_REQUEST['corf06fechaplica']=$corf06fechaplica;
				$_REQUEST['corf06horaaplica']=$corf06horaaplica;
				$_REQUEST['corf06minaplica']=$corf06minaplica;
				$sSQL='UPDATE corf06novedad SET corf06fechaplica='.$corf06fechaplica.', corf06horaaplica='.$corf06horaaplica.', corf06minaplica='.$corf06minaplica.' WHERE corf06id='.$_REQUEST['corf06id'].'';
				$result=$objDB->ejecutasql($sSQL);
				//Aplicación real del cambio de centro
				$sSQL='UPDATE core01estprograma SET core01idzona='.$_REQUEST['corf06idzonadest'].', core011idcead='.$_REQUEST['corf06idcentrodest'].' WHERE core01id='.$_REQUEST['corf06idestprograma'].'';
				$result=$objDB->ejecutasql($sSQL);
				}
			}
		}
	if ($sError==''){
		$_REQUEST['corf06estado']=7;
		$sError='Proceso completo '.$sMensajeNotifica;
		$iTipoError=1;
		}
	}
//Anular la solicitud.
if ($_REQUEST['paso']==41){
	$_REQUEST['paso']=2;
	if ($sError==''){
		//Insertamos la anotación primero.
		$valores[1]=$_REQUEST['corf06id'];
		$valores[2]='';
		$valores[3]='';
		$valores[4]=0;
		$valores[5]=0;
		$valores[6]=0;
		$valores[7]=$_REQUEST['corf08nota_b'];
		list($sError, $iAccion, $corf08id, $sDebug)=f12208_db_Guardar($valores, $objDB, $bDebug);
		}
	if ($sError==''){
		list($sError, $sDebugC, $sMensajeNotifica)=f12206_CambiaEstado($_REQUEST['corf06id'], 8, $objDB, $bDebug);
		}
	if ($sError==''){
		$_REQUEST['corf06estado']=8;
		$sError='Proceso completo '.$sMensajeNotifica;
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['corf06consec_nuevo']=numeros_validar($_REQUEST['corf06consec_nuevo']);
	if ($_REQUEST['corf06consec_nuevo']==''){$sError=$ERR['corf06consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT corf06id FROM corf06novedad WHERE corf06consec='.$_REQUEST['corf06consec_nuevo'].' AND corf06tiponov='.$_REQUEST['corf06tiponov'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['corf06consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE corf06novedad SET corf06consec='.$_REQUEST['corf06consec_nuevo'].' WHERE corf06id='.$_REQUEST['corf06id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['corf06consec'].' a '.$_REQUEST['corf06consec_nuevo'].'';
		$_REQUEST['corf06consec']=$_REQUEST['corf06consec_nuevo'];
		$_REQUEST['corf06consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['corf06id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f12206_db_Eliminar($_REQUEST['corf06id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['corf06tiponov']=6;
	$_REQUEST['corf06consec']='';
	$_REQUEST['corf06consec_nuevo']='';
	$_REQUEST['corf06id']='';
	$_REQUEST['corf06estado']=0;
	$_REQUEST['corf06idestudiante']=0;//$idTercero;
	$_REQUEST['corf06idestudiante_td']=$APP->tipo_doc;
	$_REQUEST['corf06idestudiante_doc']='';
	$_REQUEST['corf06idperiodo']='';
	$_REQUEST['corf06idescuela']='';
	$_REQUEST['corf06idprograma']='';
	$_REQUEST['corf06fecha']='';//fecha_hoy();
	$_REQUEST['corf06hora']=fecha_hora();
	$_REQUEST['corf06min']=fecha_minuto();
	$_REQUEST['corf06autoriza1']=0;
	$_REQUEST['corf06autoriza1_td']=$APP->tipo_doc;
	$_REQUEST['corf06autoriza1_doc']='';
	$_REQUEST['corf06autoriza2']=0;
	$_REQUEST['corf06autoriza2_td']=$APP->tipo_doc;
	$_REQUEST['corf06autoriza2_doc']='';
	$_REQUEST['corf06fechaplica']='';//fecha_hoy();
	$_REQUEST['corf06horaaplica']=0;
	$_REQUEST['corf06minaplica']=0;
	$_REQUEST['corf06idsesion']=0;
	$_REQUEST['corf06idzona']='';
	$_REQUEST['corf06idcentro']='';
	$_REQUEST['corf06idzonadest']='';
	$_REQUEST['corf06idcentrodest']='';
	$_REQUEST['corf06fechaultatencion']=0;
	$_REQUEST['corf06fechacierre']=0;
	$_REQUEST['corf06totaldias']=0;
	$_REQUEST['corf06idestprograma']=0;
	$_REQUEST['corf06idactoadmin']=0;
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['corf07idnovedad']='';
	$_REQUEST['corf07idcurso']='';
	$_REQUEST['corf07id']='';
	$_REQUEST['corf07tipo']=0;
	$_REQUEST['corf08idnovedad']='';
	$_REQUEST['corf08consec']='';
	$_REQUEST['corf08id']='';
	$_REQUEST['corf08fecha']='';//fecha_hoy();
	$_REQUEST['corf08hora']='';
	$_REQUEST['corf08min']='';
	$_REQUEST['corf08nota']='';
	$_REQUEST['corf08nota_b']='';
	$_REQUEST['corf08nota_c']='';
	$_REQUEST['corf08idorigenanexo']=0;
	$_REQUEST['corf08idarchivoanexo']=0;
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$bAgregarCursos=false;
$bPuedeEditar=true;
$bConZona=false;
$bConPeriodo=true;
$bParaReposicion=false;
$bAplicarCambio=false;
$et_corf06idactoadmin='&nbsp;';
switch($_REQUEST['corf06estado']){
	case 1:
	$bPuedeEditar=false;
	switch($_REQUEST['corf06tiponov']){
		case 6:
		$bAplicarCambio=true;
		break;
		}
	break;
	case 7:
	case 8:
	$bPuedeEditar=false;
	break;
	case 9:
	$bPuedeEditar=false;
	$bParaReposicion=true;
	break;
	case 4: //En recurso de reposicion.
	$bPuedeEditar=false;
	break;
	default:
	if ((int)$_REQUEST['paso']==0){
		}else{
		$bAgregarCursos=true;
		}
	break;
	}
if ($_REQUEST['corf06tiponov']==6){
	$bConPeriodo=false;
	$bConZona=true;
	}
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
$seg_14=0;
$bVerActividad=false;
list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve){$seg_6=1;}
//if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
list($corf06idestudiante_rs, $_REQUEST['corf06idestudiante'], $_REQUEST['corf06idestudiante_td'], $_REQUEST['corf06idestudiante_doc'])=html_tercero($_REQUEST['corf06idestudiante_td'], $_REQUEST['corf06idestudiante_doc'], $_REQUEST['corf06idestudiante'], 0, $objDB);
$et_corf06estado=$acorf06estado[$_REQUEST['corf06estado']];
if ($_REQUEST['corf06autoriza1']!=0){
	list($corf06autoriza1_rs, $_REQUEST['corf06autoriza1'], $_REQUEST['corf06autoriza1_td'], $_REQUEST['corf06autoriza1_doc'])=html_tercero($_REQUEST['corf06autoriza1_td'], $_REQUEST['corf06autoriza1_doc'], $_REQUEST['corf06autoriza1'], 0, $objDB);
	}
if ($_REQUEST['corf06autoriza2']!=0){
	list($corf06autoriza2_rs, $_REQUEST['corf06autoriza2'], $_REQUEST['corf06autoriza2_td'], $_REQUEST['corf06autoriza2_doc'])=html_tercero($_REQUEST['corf06autoriza2_td'], $_REQUEST['corf06autoriza2_doc'], $_REQUEST['corf06autoriza2'], 0, $objDB);
	}

list($corf06idzona_nombre, $sErrorDet)=tabla_campoxid('unad23zona','unad23nombre','unad23id',$_REQUEST['corf06idzona'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_corf06idzona=html_oculto('corf06idzona', $_REQUEST['corf06idzona'], $corf06idzona_nombre);
list($corf06idcentro_nombre, $sErrorDet)=tabla_campoxid('unad24sede','unad24nombre','unad24id',$_REQUEST['corf06idcentro'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_corf06idcentro=html_oculto('corf06idcentro', $_REQUEST['corf06idcentro'], $corf06idcentro_nombre);

if ($bConZona){
	list($corf06idzonadest_nombre, $sErrorDet)=tabla_campoxid('unad23zona','unad23nombre','unad23id',$_REQUEST['corf06idzonadest'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_corf06idzonadest=html_oculto('corf06idzonadest', $_REQUEST['corf06idzonadest'], $corf06idzonadest_nombre);
	list($corf06idcentrodest_nombre, $sErrorDet)=tabla_campoxid('unad24sede','unad24nombre','unad24id',$_REQUEST['corf06idcentrodest'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_corf06idcentrodest=html_oculto('corf06idcentrodest', $_REQUEST['corf06idcentrodest'], $corf06idcentrodest_nombre);
}
list($corf06tiponov_nombre, $sErrorDet)=tabla_campoxid('corf09novedadtipo','corf09nombre','corf09id',$_REQUEST['corf06tiponov'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_corf06tiponov=html_oculto('corf06tiponov', $_REQUEST['corf06tiponov'], $corf06tiponov_nombre);
if ((int)$_REQUEST['paso']==0){
	$html_corf06idperiodo=f12206_HTMLComboV2_corf06idperiodo($objDB, $objCombos, $_REQUEST['corf06idperiodo'], $_REQUEST['corf06idestudiante']);
	$html_corf06idescuela=f12206_HTMLComboV2_corf06idescuela($objDB, $objCombos, $_REQUEST['corf06idescuela'], $_REQUEST['corf06idperiodo'], $_REQUEST['corf06idestudiante']);
	$html_corf06idprograma=f12206_HTMLComboV2_corf06idprograma($objDB, $objCombos, $_REQUEST['corf06idprograma'], $_REQUEST['corf06idescuela'], $_REQUEST['corf06idperiodo'], $_REQUEST['corf06idestudiante']);
	}else{
	if ((int)$_REQUEST['corf06idperiodo']!=0){}
	if ($bConPeriodo){
		list($corf06idperiodo_nombre, $sErrorDet)=tabla_campoxid('exte02per_aca','CONCAT(exte02nombre, " [", exte02id, "]")','exte02id',$_REQUEST['corf06idperiodo'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_corf06idperiodo=html_oculto('corf06idperiodo', $_REQUEST['corf06idperiodo'], $corf06idperiodo_nombre);
		}
	list($corf06idescuela_nombre, $sErrorDet)=tabla_campoxid('core12escuela','core12nombre','core12id',$_REQUEST['corf06idescuela'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_corf06idescuela=html_oculto('corf06idescuela', $_REQUEST['corf06idescuela'], $corf06idescuela_nombre);
	list($corf06idprograma_nombre, $sErrorDet)=tabla_campoxid('core09programa','CONCAT(core09codigo, " - ", core09nombre)','core09id',$_REQUEST['corf06idprograma'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_corf06idprograma=html_oculto('corf06idprograma', $_REQUEST['corf06idprograma'], $corf06idprograma_nombre);
	if ($bAgregarCursos){
		$html_corf07idcurso=f12207_HTMLComboV2_corf07idcurso($objDB, $objCombos, $_REQUEST['corf07idcurso'], $_REQUEST['corf06idestudiante'], $_REQUEST['corf06idperiodo']);
		$objCombos->nuevo('corf07tipo', $_REQUEST['corf07tipo'], true, '{'.$ETI['msg_seleccione'].'}', '');
		switch($_REQUEST['corf06tiponov']){
			case 7:
			$objCombos->addItem(2, $acorf07tipo[2]);
			break;
			default:
			$objCombos->addArreglo($acorf07tipo, $icorf07tipo);
			break;
			}
		$html_corf07tipo=$objCombos->html('', $objDB);
		}
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
$bPuedeRechazar=false;
$bPuedeAnular=false;
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bperiodo', $_REQUEST['bperiodo'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->iAncho=600;
$objCombos->sAccion='paginarf12206()';
$sSQL=f146_ConsultaCombo('(exte02id>760 OR exte02id=0)', $objDB);
$html_bperiodo=$objCombos->html($sSQL, $objDB);

$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='carga_combo_bprograma();';
$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" ORDER BY core12nombre';
$html_bescuela=$objCombos->html($sSQL, $objDB);
$html_bprograma=f12206_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela']);
$objCombos->nuevo('blistar2', $_REQUEST['blistar2'], true, '{'.$ETI['msg_todas'].'}');
$sSQL='SELECT corf10id AS id, corf10nombre AS nombre FROM corf10estadonovedad WHERE corf10id IN (1,7,9) ORDER BY corf10id';
$objCombos->sAccion='paginarf12206()';
$html_blistar2=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_bcentro();';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_bzona=$objCombos->html($sSQL, $objDB);
$html_bcentro=f12206_HTMLComboV2_bcentro($objDB, $objCombos, $_REQUEST['bcentro'], $_REQUEST['bzona']);
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=12206;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	if ($_REQUEST['corf06idactoadmin']!=0){
		$et_corf06idactoadmin='{'.$_REQUEST['corf06idactoadmin'].'}';
		$sSQL='SELECT corf21consec, corf21titulo FROM corf21actosadmin WHERE corf21id='.$_REQUEST['corf06idactoadmin'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$et_corf06idactoadmin=$fila['corf21consec'].' '.cadena_notildes($fila['corf21titulo']);
			}
		}
	switch($_REQUEST['corf06tiponov']){
		case 7:
		$bVerActividad=true;
		break;
		}
	if ($_REQUEST['corf06estado']==0){
		switch($_REQUEST['corf06tiponov']){
			case 7: //Aplazamientos extemporaneos
			$bDevuelve=false;
			list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 14, $idTercero, $objDB);
			if ($bDevuelve){
				$bPuedeAnular=true;
				}else{
				//Revisar permisos por escuela.
				list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1701, $idTercero, $objDB);
				if ($bDevuelve){
					list($idEscuela, $idZona, $sDebugE)=f2212_EscuelaPerteneceV2($idTercero, $_REQUEST['corf06idescuela'], $objDB, $bDebug, false);
					$sDebug=$sDebug.$sDebugE;
					if ($idEscuela==$_REQUEST['corf06idescuela']){$bPuedeAnular=true;}
					}
				}
			if (!$bPuedeAnular){
				//Revisar permisos por centro
				list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1708, $idTercero, $objDB);
				if ($bDevuelve){
					list($idCentro, $idZona, $sDebugE)=f124_CentroPertenece($idTercero, $_REQUEST['corf06idcentro'], $objDB, $bDebug, false);
					if ($idCentro==$_REQUEST['corf06idcentro']){
						$bPuedeAnular=true;
						}
					}
				}
			if (!$bPuedeAbrir){
				//Revisar permisos por zona
				list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1710, $idTercero, $objDB);
				if ($bDevuelve){
					list($idZona, $sDebugE)=f123_ZonaPertenece($idTercero, $_REQUEST['corf06idzona'], $objDB, $bDebug);
					$sDebug=$sDebug.$sDebugE;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>REVISANDO ZONA</b> ['.$idZona.' - '.$_REQUEST['corf06idzona'].']<br>';}
					if ($idZona==$_REQUEST['corf06idzona']){
						$bPuedeAnular=true;
						}
					}
				}
			break;
			}
		}
	if ($_REQUEST['corf06estado']==1){
		switch($_REQUEST['corf06tiponov']){
			case 7: //Aplazamientos extemporaneos
			$bDevuelve=false;
			list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 14, $idTercero, $objDB);
			if ($bDevuelve){
				$seg_14=1;
				$bPuedeAbrir=true;
				$bPuedeRechazar=true;
				}
			if (!$bPuedeAbrir){
				//Revisar permisos por escuela.
				list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1701, $idTercero, $objDB);
				if ($bDevuelve){
					list($idEscuela, $idZona, $sDebugE)=f2212_EscuelaPerteneceV2($idTercero, $_REQUEST['corf06idescuela'], $objDB, $bDebug, false);
					$sDebug=$sDebug.$sDebugE;
					if ($idEscuela==$_REQUEST['corf06idescuela']){
						$bPuedeAbrir=true;
						$bPuedeRechazar=true;
						}
					}
				}
			if (!$bPuedeAbrir){
				//Revisar permisos por centro
				list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1708, $idTercero, $objDB);
				if ($bDevuelve){
					list($idCentro, $idZona, $sDebugE)=f124_CentroPertenece($idTercero, $_REQUEST['corf06idcentro'], $objDB, $bDebug, false);
					if ($idCentro==$_REQUEST['corf06idcentro']){
						$bPuedeAbrir=true;
						$bPuedeRechazar=true;
						}
					}
				}
			if (!$bPuedeAbrir){
				//Revisar permisos por zona
				list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1710, $idTercero, $objDB);
				if ($bDevuelve){
					list($idZona, $sDebugE)=f123_ZonaPertenece($idTercero, $_REQUEST['corf06idzona'], $objDB, $bDebug);
					$sDebug=$sDebug.$sDebugE;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>REVISANDO ZONA</b> ['.$idZona.' - '.$_REQUEST['corf06idzona'].']<br>';}
					if ($idZona==$_REQUEST['corf06idzona']){
						$bPuedeAbrir=true;
						$bPuedeRechazar=true;
						}
					}
				}
			break;
			}
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_12206'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf12206'];
$aParametros[102]=$_REQUEST['lppf12206'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['blistar'];
$aParametros[105]=$_REQUEST['bdoc'];
$aParametros[106]=$_REQUEST['bperiodo'];
$aParametros[107]=$_REQUEST['bescuela'];
$aParametros[108]=$_REQUEST['blistar2'];
$aParametros[109]=$_REQUEST['bprograma'];
$aParametros[110]=$_REQUEST['bzona'];
$aParametros[111]=$_REQUEST['bcentro'];
list($sTabla12206, $sDebugTabla)=f12206_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla12207='';
$sTabla12208='';
$sTabla12215='';
if ($_REQUEST['paso']!=0){
	//Cursos
	$aParametros12207[0]=$_REQUEST['corf06id'];
	$aParametros12207[100]=$idTercero;
	$aParametros12207[101]=$_REQUEST['paginaf12207'];
	$aParametros12207[102]=$_REQUEST['lppf12207'];
	//$aParametros12207[103]=$_REQUEST['bnombre12207'];
	//$aParametros12207[104]=$_REQUEST['blistar12207'];
	list($sTabla12207, $sDebugTabla)=f12207_TablaDetalleV2($aParametros12207, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Anotaciones
	$aParametros12208[0]=$_REQUEST['corf06id'];
	$aParametros12208[100]=$idTercero;
	$aParametros12208[101]=$_REQUEST['paginaf12208'];
	$aParametros12208[102]=$_REQUEST['lppf12208'];
	//$aParametros12208[103]=$_REQUEST['bnombre12208'];
	//$aParametros12208[104]=$_REQUEST['blistar12208'];
	list($sTabla12208, $sDebugTabla)=f12208_TablaDetalleV2($aParametros12208, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Cambios de estado
	$aParametros12215[0]=$_REQUEST['corf06id'];
	$aParametros12215[100]=$idTercero;
	$aParametros12215[101]=$_REQUEST['paginaf12215'];
	$aParametros12215[102]=$_REQUEST['lppf12215'];
	//$aParametros12215[103]=$_REQUEST['bnombre12215'];
	//$aParametros12215[104]=$_REQUEST['blistar12215'];
	list($sTabla12215, $sDebugTabla)=f12215_TablaDetalleV2($aParametros12215, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_12246']);
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
	document.getElementById('div_sector3').style.display='none';
	document.getElementById('div_sector93').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	if (window.document.frmedita.corf06estado.value==0){
		var sEst='none';
		if (codigo==1){sEst='block';}
		document.getElementById('cmdGuardarf').style.display=sEst;
		}
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
		if (illave==1){
			params[4]='carga_combo_corf06idperiodo()';
			params[5]='carga_combo_corf06idperiodo()';
			}
		//if (illave==1){params[5]='carga_combo_corf06idperiodo()';}
		xajax_unad11_Mostrar_v2(params);
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		if (illave==1){
			carga_combo_corf06idperiodo();
			}
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		params[4]='carga_combo_corf06idperiodo()';
		params[5]='carga_combo_corf06idperiodo()';
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_12206.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_12206.value;
		window.document.frmlista.nombrearchivo.value='Novedades de matricula';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
	//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	var sError='';
	if (window.document.frmedita.seg_6.value!=1){sError="<?php echo $ERR['6']; ?>";}
	//if (sError==''){/*Agregar validaciones*/}
	if (sError==''){
		asignarvariables();
		window.document.frmimpp.action='<?php echo $APP->rutacomun; ?>e12206.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='<?php echo $APP->rutacomun; ?>p12206.php';
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
	datos[1]=window.document.frmedita.corf06tiponov.value;
	datos[2]=window.document.frmedita.corf06consec.value;
	if ((datos[1]!='')&&(datos[2]!='')){
		xajax_f12206_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2){
	window.document.frmedita.corf06tiponov.value=String(llave1);
	window.document.frmedita.corf06consec.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf12206(llave1){
	window.document.frmedita.corf06id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_corf06idperiodo(){
	var params=new Array();
	params[0]=window.document.frmedita.corf06idestudiante.value;
	document.getElementById('div_corf06idperiodo').innerHTML='<b>Procesando datos, por favor espere...</b><input id="corf06idperiodo" name="corf06idperiodo" type="hidden" value="" />';
	document.getElementById('div_corf06idescuela').innerHTML='<b>Procesando datos, por favor espere...</b><input id="corf06idescuela" name="corf06idescuela" type="hidden" value="" />';
	document.getElementById('div_corf06idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="corf06idprograma" name="corf06idprograma" type="hidden" value="" />';
	xajax_f12206_Combocorf06idperiodo(params);
	}
function carga_combo_corf06idescuela(){
	var params=new Array();
	params[0]=window.document.frmedita.corf06idperiodo.value;
	params[1]=window.document.frmedita.corf06idestudiante.value;
	document.getElementById('div_corf06idescuela').innerHTML='<b>Procesando datos, por favor espere...</b><input id="corf06idescuela" name="corf06idescuela" type="hidden" value="" />';
	document.getElementById('div_corf06idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="corf06idprograma" name="corf06idprograma" type="hidden" value="" />';
	xajax_f12206_Combocorf06idescuela(params);
	}
function carga_combo_corf06idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.corf06idescuela.value;
	params[1]=window.document.frmedita.corf06idperiodo.value;
	params[2]=window.document.frmedita.corf06idestudiante.value;
	document.getElementById('div_corf06idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="corf06idprograma" name="corf06idprograma" type="hidden" value="" />';
	xajax_f12206_Combocorf06idprograma(params);
	}
function carga_combo_corf06idcentro(){
	var params=new Array();
	params[0]=window.document.frmedita.corf06idzona.value;
	document.getElementById('div_corf06idcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="corf06idcentro" name="corf06idcentro" type="hidden" value="" />';
	xajax_f12206_Combocorf06idcentro(params);
	}
function carga_combo_corf06idcentrodest(){
	var params=new Array();
	params[0]=window.document.frmedita.corf06idzonadest.value;
	document.getElementById('div_corf06idcentrodest').innerHTML='<b>Procesando datos, por favor espere...</b><input id="corf06idcentrodest" name="corf06idcentrodest" type="hidden" value="" />';
	xajax_f12206_Combocorf06idcentrodest(params);
	}
function paginarf12206(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf12206.value;
	params[102]=window.document.frmedita.lppf12206.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104]=window.document.frmedita.blistar.value;
	params[105]=window.document.frmedita.bdoc.value;
	params[106]=window.document.frmedita.bperiodo.value;
	params[107]=window.document.frmedita.bescuela.value;
	params[108]=window.document.frmedita.blistar2.value;
	params[109]=window.document.frmedita.bprograma.value;
	params[110]=window.document.frmedita.bzona.value;
	params[111]=window.document.frmedita.bcentro.value;
	document.getElementById('div_f12206detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf12206" name="paginaf12206" type="hidden" value="'+params[101]+'" /><input id="lppf12206" name="lppf12206" type="hidden" value="'+params[102]+'" />';
	xajax_f12206_HtmlTabla(params);
	}
function enviacerrar(){
	if (confirm('Esta seguro de cerrar el registro?\nluego de cerrado no se permite modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=16;
		window.document.frmedita.submit();
		}
	}
function enviaabrir(){
	if (confirm('Esta seguro de abrir el registro?\nesto le permite volver a modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=17;
		window.document.frmedita.submit();
		}
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
	document.getElementById("corf06tiponov").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f12206_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='corf06idestudiante'){
		ter_traerxid('corf06idestudiante', sValor);
		}
	if (sCampo=='corf06autoriza1'){
		ter_traerxid('corf06autoriza1', sValor);
		}
	if (sCampo=='corf06autoriza2'){
		ter_traerxid('corf06autoriza2', sValor);
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
	if (ref==12208){
		if (sRetorna!=''){
			window.document.frmedita.corf08idorigenanexo.value=window.document.frmedita.div96v1.value;
			window.document.frmedita.corf08idarchivoanexo.value=sRetorna;
			verboton('beliminacorf08idarchivoanexo','block');
			}
		archivo_lnk(window.document.frmedita.corf08idorigenanexo.value, window.document.frmedita.corf08idarchivoanexo.value, 'div_corf08idarchivoanexo');
		paginarf12208();
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function mod_consec(){
	if (confirm("Esta seguro de cambiar el consecutivo?")){
		expandesector(98);
		window.document.frmedita.paso.value=93;
		window.document.frmedita.submit();
		}
	}
function carga_combo_bprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.bescuela.value;
	document.getElementById('div_bprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="bprograma" name="bprograma" type="hidden" value="" />';
	xajax_f12206_Combobprograma(params);
	}
function carga_combo_bcentro(){
	var params=new Array();
	params[0]=window.document.frmedita.bzona.value;
	document.getElementById('div_bcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="bcentro" name="bcentro" type="hidden" value="" />';
	xajax_f12206_Combobcentro(params);
	}
<?php
if ($bVerActividad){
?>
function veractividadest(){
	window.document.frmae.submit();
	}
<?php
	}
if ($bPuedeRechazar){
?>
function pasaanegar(){
	document.getElementById('div_titulo_sector2').innerHTML='<h2><?php echo $ETI['titulo_sector2']; ?></h2';
	document.getElementById('btn_negar').style.display='block';
	document.getElementById('btn_devolver').style.display='none';
	expandesector(2);
	}
function pasaadevolver(){
	document.getElementById('div_titulo_sector2').innerHTML='<h2><?php echo $ETI['titulo_sector2devolver']; ?></h2';
	document.getElementById('btn_negar').style.display='none';
	document.getElementById('btn_devolver').style.display='block';
	expandesector(2);
	}
function aestudio(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	if (confirm("La novedad pasa a estudio, esta seguro?")){
		expandesector(98);
		window.document.frmedita.paso.value=21;
		window.document.frmedita.submit();
		}
	}
function confirmanegar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	if (window.document.frmedita.corf08nota_b.value.trim()!=''){
		expandesector(98);
		window.document.frmedita.paso.value=22;
		window.document.frmedita.submit();
		}else{
		window.alert('Por favor ingrese el motivo del rechazo.');
		}
	}
function confirmadevolver(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	if (window.document.frmedita.corf08nota_b.value.trim()!=''){
		expandesector(98);
		window.document.frmedita.paso.value=26;
		window.document.frmedita.submit();
		}else{
		window.alert('Por favor ingrese el motivo de la devolucion.');
		}
	}
<?php
	}
// Anular la solicitud.
if ($bPuedeAnular){
?>
function pasaanular(){
	document.getElementById('div_titulo_sector2').innerHTML='<h2><?php echo $ETI['titulo_sector2']; ?></h2';
	expandesector(2);
	}
function confirmaanular(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	if (window.document.frmedita.corf08nota_b.value.trim()!=''){
		expandesector(98);
		window.document.frmedita.paso.value=41;
		window.document.frmedita.submit();
		}else{
		window.alert('Por favor ingrese el motivo por el que anula.');
		}
	}
<?php
	}
//La reposición	
if ($bParaReposicion){
?>
function confirmareposicion(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	if (window.document.frmedita.corf08nota_c.value.trim()!=''){
		expandesector(98);
		window.document.frmedita.paso.value=24;
		window.document.frmedita.submit();
		}else{
		window.alert('Por favor ingrese el motivo del proceso.');
		}
	}
<?php
	}
if ($bAplicarCambio){
?>
function aplicarcambiocentro(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	if (confirm("Confirma que se aplica el cambio de centro?")){
		expandesector(98);
		window.document.frmedita.paso.value=25;
		window.document.frmedita.submit();
		}
	}
<?php
	}
?>
function notificanota(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	if (confirm("Se va a enviar notificar al estudiante, desea continuar?")){
		expandesector(98);
		window.document.frmedita.paso.value=23;
		window.document.frmedita.submit();
		}
	}
function estadocuenta(){
	window.document.frmestadocuenta.fact07idtercero.value = window.document.frmedita.corf06idestudiante.value;
	window.document.frmestadocuenta.submit();
}
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js12207.js?v=2"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js12208.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js12215.js"></script>
<?php
	}
if ($bVerActividad){
?>
<form id="frmae" name="frmae" method="post" action="../c2/cecarevestudiante.php" target="_blank">
<input id="core05tercero" name="core05tercero" type="hidden" value="<?php echo $_REQUEST['corf06idestudiante']; ?>"/>
<input id="core05peraca" name="core05peraca" type="hidden" value="<?php echo $_REQUEST['corf06idperiodo']; ?>"/>
<input id="core05idcurso" name="core05idcurso" type="hidden" value="-1"/>
<input id="paso" name="paso" type="hidden" value="0" />
</form>
<?php
	}
if ($_REQUEST['paso']!=0){
?>
<form id="frmestadocuenta" name="frmestadocuenta" method="post" action="../facturacion/factestcuenta.php" target="_blank">
<input id="fact07idtercero" name="fact07idtercero" type="hidden" value="" />
<input id="paso" name="paso" type="hidden" value="0" />
</form>
<form id="frmimpp" name="frmimpp" method="post" action="<?php echo $APP->rutacomun; ?>p12206.php" target="_blank">
<input id="r" name="r" type="hidden" value="12206" />
<input id="id12206" name="id12206" type="hidden" value="<?php echo $_REQUEST['corf06id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
	}
?>
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
<input id="ipiel" name="ipiel" type="hidden" value="<?php echo $iPiel; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['corf06estado']==0){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
		}
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		if ($_REQUEST['corf06estado']=='S'){
			//$bHayImprimir=true;
			//$sScript='imprimep()';
			//if ($iNumFormatosImprime>0){
				//$sScript='expandesector(94)';
				//}
			//$sClaseBoton='btEnviarPDF'; //btUpPrint
			//if ($id_rpt!=0){$sScript='verrpt()';}
			}
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
if ($_REQUEST['corf06estado']==0){
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
	if ($_REQUEST['paso']>0){
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="Cerrar" value="Cerrar"/>
<?php
		}
	}else{
	if ($_REQUEST['paso']>0){
		if ($bPuedeAbrir){
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Devolver a borrador" value="Abrir"/>
<?php
			}
		}
	}
if (false){
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_12246'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bConExpande=true;
if ($bConExpande){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<input id="boculta12206" name="boculta12206" type="hidden" value="<?php echo $_REQUEST['boculta12206']; ?>" />
<label class="Label30">
<input id="btexpande12206" name="btexpande12206" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(12206,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta12206']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge12206" name="btrecoge12206" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(12206,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta12206']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p12206" style="display:<?php if ($_REQUEST['boculta12206']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label60">
<?php
echo $ETI['corf06tiponov'];
?>
</label>
<label>
<?php
echo $html_corf06tiponov;
?>
</label>
<label class="Label130">
<?php
echo $ETI['corf06consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="corf06consec" name="corf06consec" type="text" value="<?php echo $_REQUEST['corf06consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('corf06consec', $_REQUEST['corf06consec'], formato_numero($_REQUEST['corf06consec']));
	}
?>
</label>
<?php
/*
if ($seg_8==1){
	$objForma=new clsHtmlForma($iPiel);
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
	}
*/
?>
<label class="Label60">
<?php
echo $ETI['corf06id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('corf06id', $_REQUEST['corf06id'], formato_numero($_REQUEST['corf06id']));
?>
</label>
<label>
<?php
echo html_oculto('corf06estado', $_REQUEST['corf06estado'], $et_corf06estado);
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['corf06idestudiante'];
?>
</label>
<div class="salto1px"></div>
<input id="corf06idestudiante" name="corf06idestudiante" type="hidden" value="<?php echo $_REQUEST['corf06idestudiante']; ?>"/>
<div id="div_corf06idestudiante_llaves">
<?php
$bOculto=false;
if ($_REQUEST['paso']!=0){$bOculto=true;}
echo html_DivTerceroV2('corf06idestudiante', $_REQUEST['corf06idestudiante_td'], $_REQUEST['corf06idestudiante_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_corf06idestudiante" class="L"><?php echo $corf06idestudiante_rs; ?></div>
<?php
if (true) {
?>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label160">
<input id="cmdEstadoCuenta" name="cmdEstadoCuenta" type="button" class="BotonAzul160" value="Estado de cuenta" onclick="javascript:estadocuenta()" title="Ir al estado de cuenta" />
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<div class="salto5px"></div>
<?php
if ((int)$_REQUEST['corf06idperiodo']!=0){}
if ($bConPeriodo){
?>
<label class="Label90">
<?php
echo $ETI['corf06idperiodo'];
?>
</label>
<label class="Label380">
<div id="div_corf06idperiodo">
<?php
echo $html_corf06idperiodo;
?>
</div>
</label>
<div class="salto1px"></div>
<?php
	}else{
?>
<input id="corf06idperiodo" name="corf06idperiodo" type="hidden" value="<?php echo $_REQUEST['corf06idperiodo']; ?>"/>
<?php
	}
?>
<label class="Label90">
<?php
echo $ETI['corf06idescuela'];
?>
</label>
<label class="Label380">
<div id="div_corf06idescuela">
<?php
echo $html_corf06idescuela;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['corf06idprograma'];
?>
</label>
<label class="Label380">
<div id="div_corf06idprograma">
<?php
echo $html_corf06idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<?php
if ($_REQUEST['corf06estado'] == 1) {
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante</b>: Recuerde que antes de aplicar el cambio de centro debe hacer los siguientes pasos:<br>
1 - Revisar el estado de cuenta.<br>
2 - Diligencia el paz y salvo.<br>
3 - Validar la documentaci&oacute;n.
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<div class="salto5px"></div>
<label class="Label60">
<?php
echo $ETI['corf06fecha'];
?>
</label>
<div class="Campo220">
<?php
if ($bPuedeEditar){
	echo html_FechaEnNumero('corf06fecha', $_REQUEST['corf06fecha']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
	}else{
	echo html_oculto('corf06fecha', $_REQUEST['corf06fecha'], fecha_desdenumero($_REQUEST['corf06fecha']));
	}
?>
</div>
<div class="campo_HoraMin" id="div_corf06hora">
<?php
echo html_HoraMin('corf06hora', $_REQUEST['corf06hora'], 'corf06min', $_REQUEST['corf06min'], !$bPuedeEditar);
?>
</div>
<?php
if ($_REQUEST['corf06estado']==7){
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['corf06fechaplica'];
?>
</label>
<label class="Label220">
<div id="div_corf06fechaplica">
<?php
echo html_oculto('corf06fechaplica', $_REQUEST['corf06fechaplica'], fecha_desdenumero($_REQUEST['corf06fechaplica'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="campo_HoraMin" id="div_corf06horaaplica">
<?php
echo html_HoraMin('corf06horaaplica', $_REQUEST['corf06horaaplica'], 'corf06minaplica', $_REQUEST['corf06minaplica'], true);
?>
</div>
<?php
	}else{
?>
<input id="corf06fechaplica" name="corf06fechaplica" type="hidden" value="<?php echo $_REQUEST['corf06fechaplica']; ?>"/>
<input id="corf06horaaplica" name="corf06horaaplica" type="hidden" value="<?php echo $_REQUEST['corf06horaaplica']; ?>"/>
<input id="corf06minaplica" name="corf06minaplica" type="hidden" value="<?php echo $_REQUEST['corf06minaplica']; ?>"/>
<?php
	}
$bAgregarSalto=false;
?>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['corf06idactoadmin'];
?>
</label>
<label>
<?php
echo html_oculto('corf06idactoadmin', $_REQUEST['corf06idactoadmin'], $et_corf06idactoadmin);
?>
<div class="salto1px"></div>
</div>
<?php
//Ahora los datos de quienes autorizaron.
if ($_REQUEST['corf06autoriza1']!=0){
	$bAgregarSalto=true;
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['corf06autoriza1'];
?>
</label>
<div class="salto1px"></div>
<input id="corf06autoriza1" name="corf06autoriza1" type="hidden" value="<?php echo $_REQUEST['corf06autoriza1']; ?>"/>
<div id="div_corf06autoriza1_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('corf06autoriza1', $_REQUEST['corf06autoriza1_td'], $_REQUEST['corf06autoriza1_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_corf06autoriza1" class="L"><?php echo $corf06autoriza1_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
	}else{
?>
<input id="corf06autoriza1" name="corf06autoriza1" type="hidden" value="<?php echo $_REQUEST['corf06autoriza1']; ?>"/>
<input id="corf06autoriza1_td" name="corf06autoriza1_td" type="hidden" value="<?php echo $_REQUEST['corf06autoriza1_td']; ?>"/>
<input id="corf06autoriza1_doc" name="corf06autoriza1_doc" type="hidden" value="<?php echo $_REQUEST['corf06autoriza1_doc']; ?>"/>
<?php
	}
if ($_REQUEST['corf06autoriza2']!=0){
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['corf06autoriza2'];
?>
</label>
<div class="salto1px"></div>
<input id="corf06autoriza2" name="corf06autoriza2" type="hidden" value="<?php echo $_REQUEST['corf06autoriza2']; ?>"/>
<div id="div_corf06autoriza2_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('corf06autoriza2', $_REQUEST['corf06autoriza2_td'], $_REQUEST['corf06autoriza2_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_corf06autoriza2" class="L"><?php echo $corf06autoriza2_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
	}else{
?>
<input id="corf06autoriza2" name="corf06autoriza2" type="hidden" value="<?php echo $_REQUEST['corf06autoriza2']; ?>"/>
<input id="corf06autoriza2_td" name="corf06autoriza2_td" type="hidden" value="<?php echo $_REQUEST['corf06autoriza2_td']; ?>"/>
<input id="corf06autoriza2_doc" name="corf06autoriza2_doc" type="hidden" value="<?php echo $_REQUEST['corf06autoriza2_doc']; ?>"/>
<?php
	}
?>
<input id="corf06idsesion" name="corf06idsesion" type="hidden" value="<?php echo $_REQUEST['corf06idsesion']; ?>"/>
<?php
if ($bAgregarSalto){echo html_salto();}
?>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['msg_origen'];
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['corf06idzona'];
?>
</label>
<label class="Label380">
<?php
echo $html_corf06idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['corf06idcentro'];
?>
</label>
<label class="Label380">
<div id="div_corf06idcentro">
<?php
echo $html_corf06idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<?php
if ($bConZona){
?>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['msg_destino'];
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['corf06idzonadest'];
?>
</label>
<label class="Label380">
<?php
echo $html_corf06idzonadest;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['corf06idcentrodest'];
?>
</label>
<label class="Label380">
<div id="div_corf06idcentrodest">
<?php
echo $html_corf06idcentrodest;
?>
</div>
</label>
<?php
if ($bAplicarCambio){
?>
<div class="salto1px"></div>
<label class="Label90"></label>
<label class="Label130">
<input id="CmdAplicaCentro" name="CmdAplicaCentro" type="button" class="BotonAzul" value="Aplicar" onclick="aplicarcambiocentro()" title="Apliar cambio de centro"/>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
	}else{
?>
<input id="corf06idzona" name="corf06idzona" type="hidden" value="<?php echo $_REQUEST['corf06idzona']; ?>"/>
<input id="corf06idcentro" name="corf06idcentro" type="hidden" value="<?php echo $_REQUEST['corf06idcentro']; ?>"/>
<input id="corf06idzonadest" name="corf06idzonadest" type="hidden" value="<?php echo $_REQUEST['corf06idzonadest']; ?>"/>
<input id="corf06idcentrodest" name="corf06idcentrodest" type="hidden" value="<?php echo $_REQUEST['corf06idcentrodest']; ?>"/>
<?php
	}
?>
<input id="corf06fechaultatencion" name="corf06fechaultatencion" type="hidden" value="<?php echo $_REQUEST['corf06fechaultatencion']; ?>"/>
<input id="corf06fechacierre" name="corf06fechacierre" type="hidden" value="<?php echo $_REQUEST['corf06fechacierre']; ?>"/>
<input id="corf06totaldias" name="corf06totaldias" type="hidden" value="<?php echo $_REQUEST['corf06totaldias']; ?>"/>
<input id="corf06idestprograma" name="corf06idestprograma" type="hidden" value="<?php echo $_REQUEST['corf06idestprograma']; ?>"/>
<?php
// -- Inicia Grupo campos 12207 Cursos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_12207'];
?>
</label>
<?php
if ($bVerActividad){
?>
<label class="Label130">
<input id="cmdVerHistorial" name="cmdVerHistorial" type="button" class="BotonAzul" value="Ver Historial" onclick="javascript:veractividadest()" title="Ver historial del estudiante">
</label>
<?php
	}
?>
<input id="boculta12207" name="boculta12207" type="hidden" value="<?php echo $_REQUEST['boculta12207']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if ($bAgregarCursos){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel12207" name="btexcel12207" type="button" value="Exportar" class="btMiniExcel" onclick="imprime12207();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande12207" name="btexpande12207" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(12207,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta12207']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge12207" name="btrecoge12207" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(12207,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta12207']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p12207" style="display:<?php if ($_REQUEST['boculta12207']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['corf07idcurso'];
?>
</label>
<label class="Label600">
<div id="div_corf07idcurso">
<?php
echo $html_corf07idcurso;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['corf07tipo'];
?>
</label>
<label>
<?php
echo $html_corf07tipo;
?>
</label>
<label class="Label60">
<?php
echo $ETI['corf07id'];
?>
</label>
<label class="Label60"><div id="div_corf07id">
<?php
	echo html_oculto('corf07id', $_REQUEST['corf07id'], formato_numero($_REQUEST['corf07id']));
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda12207" name="bguarda12207" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf12207()" title="<?php echo $ETI['bt_mini_guardar_12207']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia12207" name="blimpia12207" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf12207()" title="<?php echo $ETI['bt_mini_limpiar_12207']; ?>"/>
</label>
<label class="Label30">
<input id="belimina12207" name="belimina12207" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf12207()" title="<?php echo $ETI['bt_mini_eliminar_12207']; ?>" style="display:<?php if ((int)$_REQUEST['corf07id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p12207
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f12207detalle">
<?php
echo $sTabla12207;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 12207 Cursos
?>
<?php
// -- Inicia Grupo campos 12208 Anotaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_12208'];
?>
</label>
<input id="boculta12208" name="boculta12208" type="hidden" value="<?php echo $_REQUEST['boculta12208']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel12208" name="btexcel12208" type="button" value="Exportar" class="btMiniExcel" onclick="imprime12208();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande12208" name="btexpande12208" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(12208,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta12208']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge12208" name="btrecoge12208" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(12208,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta12208']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p12208" style="display:<?php if ($_REQUEST['boculta12208']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['corf08consec'];
?>
</label>
<label class="Label130"><div id="div_corf08consec">
<?php
if ((int)$_REQUEST['corf08id']==0){
?>
<input id="corf08consec" name="corf08consec" type="text" value="<?php echo $_REQUEST['corf08consec']; ?>" onchange="revisaf12208()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('corf08consec', $_REQUEST['corf08consec'], formato_numero($_REQUEST['corf08consec']));
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['corf08id'];
?>
</label>
<label class="Label60"><div id="div_corf08id">
<?php
	echo html_oculto('corf08id', $_REQUEST['corf08id'], formato_numero($_REQUEST['corf08id']));
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['corf08fecha'];
?>
</label>
<label class="Label220">
<div id="div_corf08fecha">
<?php
echo html_oculto('corf08fecha', $_REQUEST['corf08fecha'], fecha_desdenumero($_REQUEST['corf08fecha'], '&nbsp;')); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="campo_HoraMin" id="div_corf08hora">
<?php
echo html_HoraMin('corf08hora', $_REQUEST['corf08hora'], 'corf08min', $_REQUEST['corf08min'], true);
?>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="txtAreaS">
<?php
echo $ETI['corf08nota'];
?>
<textarea id="corf08nota" name="corf08nota" placeholder="<?php echo $ETI['ing_campo'].$ETI['corf08nota']; ?>"><?php echo $_REQUEST['corf08nota']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>

<input id="corf08idorigenanexo" name="corf08idorigenanexo" type="hidden" value="<?php echo $_REQUEST['corf08idorigenanexo']; ?>"/>
<input id="corf08idarchivoanexo" name="corf08idarchivoanexo" type="hidden" value="<?php echo $_REQUEST['corf08idarchivoanexo']; ?>"/>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_corf08idarchivoanexo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['corf08idorigenanexo'], (int)$_REQUEST['corf08idarchivoanexo']);
?>
</div>
<label class="Label30">
<input type="button" id="banexacorf08idarchivoanexo" name="banexacorf08idarchivoanexo" value="Anexar" class="btAnexarS" onclick="carga_corf08idarchivoanexo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['corf08id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<label class="Label30">
<input type="button" id="beliminacorf08idarchivoanexo" name="beliminacorf08idarchivoanexo" value="Eliminar" class="btBorrarS" onclick="eliminacorf08idarchivoanexo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['corf08idarchivoanexo']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda12208" name="bguarda12208" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf12208()" title="<?php echo $ETI['bt_mini_guardar_12208']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia12208" name="blimpia12208" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf12208()" title="<?php echo $ETI['bt_mini_limpiar_12208']; ?>"/>
</label>
<label class="Label30">
<input id="belimina12208" name="belimina12208" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf12208()" title="<?php echo $ETI['bt_mini_eliminar_12208']; ?>" style="display:<?php if ((int)$_REQUEST['corf08id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p12208
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f12208detalle">
<?php
echo $sTabla12208;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 12208 Anotaciones
?>
<?php
//Ahora los cambios de estado
if ($bPuedeRechazar){
?>
<div class="salto1px"></div>
<label></label>
<label class="Label130">
<input id="CmdDevolver" name="CmdDevolver" type="button" class="BotonAzul" value="Devolver" onclick="pasaadevolver()" title="Devolver la solicitud"/>
</label>
<label class="Label130"></label>
<label class="Label130">
<input id="CmdParaEstudio" name="CmdParaEstudio" type="button" class="BotonAzul" value="A estudio" onclick="aestudio()" title="Poner para estudio"/>
</label>
<?php
if (false){
?>
<label class="Label130"></label>
<label class="Label130">
<input id="CmdNoProcede" name="CmdNoProcede" type="button" class="BotonAzul" value="No procede" onclick="pasaanegar()" title="La solicitud no es procedente"/>
</label>
<?php
		}
	}
//Anulaciones
if ($bPuedeAnular){
?>
<div class="salto1px"></div>
<label></label>
<label class="Label130">
<input id="CmdParaAnular" name="CmdParaAnular" type="button" class="BotonAzul" value="Anular" onclick="pasaanular()" title="Anular la solicitud"/>
</label>
<?php
	}
if ($bParaReposicion){
?>
<div class="salto1px"></div>
<label></label>
<label class="Label130">
<input id="CmdReposicion" name="CmdReposicion" type="button" class="BotonAzul" value="Reposici&oacute;n" onclick="expandesector(3)" title="Recibir Reposici&oacute;n"/>
</label>
<?php
	}
// -- Inicia Grupo campos 12215 Cambios de estado
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_12215'];
?>
</label>
<input id="boculta12215" name="boculta12215" type="hidden" value="<?php echo $_REQUEST['boculta12215']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="salto1px"></div>
<?php
?>
<div id="div_f12215detalle">
<?php
echo $sTabla12215;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 12215 Cambios de estado
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p12206
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
<label class="Label90">
<?php
echo $ETI['corf06idperiodo'];
?>
</label>
<label class="Label600">
<?php
echo $html_bperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['corf06idescuela'];
?>
</label>
<label class="Label600">
<?php
echo $html_bescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['corf06idprograma'];
?>
</label>
<label>
<div id="div_bprograma">
<?php
echo $html_bprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['corf06idzona'];
?>
</label>
<label>
<?php
echo $html_bzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['corf06idcentro'];
?>
</label>
<label>
<div id="div_bcentro">
<?php
echo $html_bcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bdoc'];
?>
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf12206()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf12206()" autocomplete="off"/>
</label>
<div class="salto1px"></div>
<input id="blistar" name="blistar" type="hidden" value="<?php echo $_REQUEST['blistar']; ?>"/>
<label class="Label90">
<?php
echo $ETI['msg_listar'];
?>
</label>
<label>
<?php
echo $html_blistar2;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f12206detalle">
<?php
echo $sTabla12206;
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
<div class="titulosI" id="div_titulo_sector2">
<?php
echo '<h2>'.$ETI['titulo_sector2'].'</h2>';
?>
</div>
</div>
<div id="areaform">
<div id="areatrabajo">
<label class="txtAreaL">
<?php
echo $ETI['corf08nota'];
?>
<textarea id="corf08nota_b" name="corf08nota_b" placeholder="<?php echo $ETI['ing_campo'].$ETI['corf08nota']; ?>"><?php echo $_REQUEST['corf08nota_b']; ?></textarea>
</label>
<?php
if ($bPuedeRechazar){
?>
<div class="salto1px"></div>
<label></label>
<label class="Label130" id="btn_devolver">
<input id="CmdDevolver2" name="CmdDevolver2" type="button" class="BotonAzul" value="Devolver" onclick="confirmadevolver()" title="Devolver Solicitud"/>
</label>
<label class="Label160" id="btn_negar">
<input id="CmdNegar2" name="CmdNegar2" type="button" class="BotonAzul160" value="No Procedente" onclick="confirmanegar()" title="No Procedente"/>
</label>
<label class="Label130"></label>
<label class="Label130">
<input id="CmdCancelar" name="CmdCancelar" type="button" class="BotonAzul" value="Volver" onclick="expandesector(1)" title="Volver"/>
</label>
<?php
	}
//Anular
if ($bPuedeAnular){
?>
<div class="salto1px"></div>
<label></label>
<label class="Label130" id="btn_devolver">
<input id="CmdAnular2" name="CmdAnular2" type="button" class="BotonAzul" value="Anular" onclick="confirmaanular()" title="Anular Solicitud"/>
</label>
<label class="Label130"></label>
<label class="Label130">
<input id="CmdCancelar" name="CmdCancelar" type="button" class="BotonAzul" value="Volver" onclick="expandesector(1)" title="Volver"/>
</label>
<?php
	}
?>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector3" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda3" name="cmdAyuda3" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec3" name="cmdVolverSec3" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_reposicion'].'</h2>';
?>
</div>
</div>
<div id="areaform">
<div id="areatrabajo">
<label class="txtAreaL">
<?php
echo $ETI['corf08nota'];
?>
<textarea id="corf08nota_c" name="corf08nota_c" placeholder="<?php echo $ETI['ing_campo'].$ETI['corf08nota']; ?>"><?php echo $_REQUEST['corf08nota_c']; ?></textarea>
</label>
<?php
if ($bParaReposicion){
?>
<div class="salto1px"></div>
<label></label>
<label class="Label130">
<input id="CmdReposicion" name="CmdReposicion" type="button" class="BotonAzul" value="Recibir" onclick="confirmareposicion()" title="Recibir reposici&oacute;n"/>
</label>
<label class="Label130"></label>
<label class="Label130">
<input id="CmdCancelarR" name="CmdCancelarR" type="button" class="BotonAzul" value="Volver" onclick="expandesector(1)" title="Volver"/>
</label>
<?php
	}
// -- Inicia Grupo campos 12215 Cambios de estado
?>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->

<div id="div_sector93" style="display:none">
<?php
$objForma=new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda('.$iCodModulo.');', $ETI['bt_ayuda']);
$objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'expandesector(1);', $ETI['bt_volver']);
echo $objForma->htmlTitulo(''.$ETI['titulo_sector93'].'', $iCodModulo);
echo $objForma->htmlInicioMarco();
?>
<label class="Label160">
<?php
echo $ETI['msg_corf06consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['corf06consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_corf06consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="corf06consec_nuevo" name="corf06consec_nuevo" type="text" value="<?php echo $_REQUEST['corf06consec_nuevo']; ?>" class="cuatro"/>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector93 -->


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
<input id="titulo_12206" name="titulo_12206" type="hidden" value="<?php echo $ETI['titulo_12246']; ?>" />
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
echo '<h2>'.$ETI['titulo_12246'].'</h2>';
?>
</div>
</div>
<div id="areaform">
<div id="areatrabajo">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /DIV_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_12246'].'</h2>';
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
if ($_REQUEST['corf06estado']==0){
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
?>
-->
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript">
$().ready(function(){
$("#bperiodo").chosen();
$("#bprograma").chosen();
});
</script>
<script language="javascript" src="ac_12206.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>