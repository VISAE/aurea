<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.1 sábado, 23 de abril de 2022
*/
/** Archivo caracterizacion2.php.
* Modulo 2344 cara44encuesta.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date sábado, 23 de abril de 2022
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
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=2344;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
//$mensajes_2300='lg/lg_2300_'.$_SESSION['unad_idioma'].'.php';
//if (!file_exists($mensajes_2300)){$mensajes_2300='lg/lg_2300_es.php';}
$mensajes_2344='lg/lg_2344_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2344)){$mensajes_2344='lg/lg_2344_es.php';}
require $mensajes_todas;
//require $mensajes_2300;
require $mensajes_2344;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
$iPiel=iDefinirPiel($APP, 1);
$sAnchoExpandeContrae=' style="width:62px;"';
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
		header('Location:noticia.php?ret=caracterizacion2.php');
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
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2344 cara44encuesta
require 'lib2344.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f2344_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2344_ExisteDato');
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
$iHoy=fecha_DiaMod();
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf2344'])==0){$_REQUEST['paginaf2344']=1;}
if (isset($_REQUEST['lppf2344'])==0){$_REQUEST['lppf2344']=20;}
if (isset($_REQUEST['boculta2344'])==0){$_REQUEST['boculta2344']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara44id'])==0){$_REQUEST['cara44id']='';}
if (isset($_REQUEST['cara44idcara'])==0){$_REQUEST['cara44idcara']='';}
if (isset($_REQUEST['cara44campesinado'])==0){$_REQUEST['cara44campesinado']='';}
if (isset($_REQUEST['cara44vacunacovid19'])==0){$_REQUEST['cara44vacunacovid19']='';}
if (isset($_REQUEST['cara44sexoversion'])==0){$_REQUEST['cara44sexoversion']='';}
if (isset($_REQUEST['cara44sexov1identidadgen'])==0){$_REQUEST['cara44sexov1identidadgen']='';}
if (isset($_REQUEST['cara44sexov1orientasexo'])==0){$_REQUEST['cara44sexov1orientasexo']='';}
if (isset($_REQUEST['cara44bienversion'])==0){$_REQUEST['cara44bienversion']='';}
if (isset($_REQUEST['cara44bienv2altoren'])==0){$_REQUEST['cara44bienv2altoren']='';}
if (isset($_REQUEST['cara44bienv2atletismo'])==0){$_REQUEST['cara44bienv2atletismo']='';}
if (isset($_REQUEST['cara44bienv2baloncesto'])==0){$_REQUEST['cara44bienv2baloncesto']='';}
if (isset($_REQUEST['cara44bienv2futbol'])==0){$_REQUEST['cara44bienv2futbol']='';}
if (isset($_REQUEST['cara44bienv2gimnasia'])==0){$_REQUEST['cara44bienv2gimnasia']='';}
if (isset($_REQUEST['cara44bienv2natacion'])==0){$_REQUEST['cara44bienv2natacion']='';}
if (isset($_REQUEST['cara44bienv2voleibol'])==0){$_REQUEST['cara44bienv2voleibol']='';}
if (isset($_REQUEST['cara44bienv2tenis'])==0){$_REQUEST['cara44bienv2tenis']='';}
if (isset($_REQUEST['cara44bienv2paralimpico'])==0){$_REQUEST['cara44bienv2paralimpico']='';}
if (isset($_REQUEST['cara44bienv2otrodeporte'])==0){$_REQUEST['cara44bienv2otrodeporte']='';}
if (isset($_REQUEST['cara44bienv2otrodeportedetalle'])==0){$_REQUEST['cara44bienv2otrodeportedetalle']='';}
if (isset($_REQUEST['cara44bienv2activdanza'])==0){$_REQUEST['cara44bienv2activdanza']='';}
if (isset($_REQUEST['cara44bienv2activmusica'])==0){$_REQUEST['cara44bienv2activmusica']='';}
if (isset($_REQUEST['cara44bienv2activteatro'])==0){$_REQUEST['cara44bienv2activteatro']='';}
if (isset($_REQUEST['cara44bienv2activartes'])==0){$_REQUEST['cara44bienv2activartes']='';}
if (isset($_REQUEST['cara44bienv2activliteratura'])==0){$_REQUEST['cara44bienv2activliteratura']='';}
if (isset($_REQUEST['cara44bienv2activculturalotra'])==0){$_REQUEST['cara44bienv2activculturalotra']='';}
if (isset($_REQUEST['cara44bienv2activculturalotradetalle'])==0){$_REQUEST['cara44bienv2activculturalotradetalle']='';}
if (isset($_REQUEST['cara44bienv2evenfestfolc'])==0){$_REQUEST['cara44bienv2evenfestfolc']='';}
if (isset($_REQUEST['cara44bienv2evenexpoarte'])==0){$_REQUEST['cara44bienv2evenexpoarte']='';}
if (isset($_REQUEST['cara44bienv2evenhistarte'])==0){$_REQUEST['cara44bienv2evenhistarte']='';}
if (isset($_REQUEST['cara44bienv2evengalfoto'])==0){$_REQUEST['cara44bienv2evengalfoto']='';}
if (isset($_REQUEST['cara44bienv2evenliteratura'])==0){$_REQUEST['cara44bienv2evenliteratura']='';}
if (isset($_REQUEST['cara44bienv2eventeatro'])==0){$_REQUEST['cara44bienv2eventeatro']='';}
if (isset($_REQUEST['cara44bienv2evencine'])==0){$_REQUEST['cara44bienv2evencine']='';}
if (isset($_REQUEST['cara44bienv2evenculturalotro'])==0){$_REQUEST['cara44bienv2evenculturalotro']='';}
if (isset($_REQUEST['cara44bienv2evenculturalotrodetalle'])==0){$_REQUEST['cara44bienv2evenculturalotrodetalle']='';}
if (isset($_REQUEST['cara44bienv2emprendimiento'])==0){$_REQUEST['cara44bienv2emprendimiento']='';}
if (isset($_REQUEST['cara44bienv2empresa'])==0){$_REQUEST['cara44bienv2empresa']='';}
if (isset($_REQUEST['cara44bienv2emprenrecursos'])==0){$_REQUEST['cara44bienv2emprenrecursos']='';}
if (isset($_REQUEST['cara44bienv2emprenconocim'])==0){$_REQUEST['cara44bienv2emprenconocim']='';}
if (isset($_REQUEST['cara44bienv2emprenplan'])==0){$_REQUEST['cara44bienv2emprenplan']='';}
if (isset($_REQUEST['cara44bienv2emprenejecutar'])==0){$_REQUEST['cara44bienv2emprenejecutar']='';}
if (isset($_REQUEST['cara44bienv2emprenfortconocim'])==0){$_REQUEST['cara44bienv2emprenfortconocim']='';}
if (isset($_REQUEST['cara44bienv2emprenidentproblema'])==0){$_REQUEST['cara44bienv2emprenidentproblema']='';}
if (isset($_REQUEST['cara44bienv2emprenotro'])==0){$_REQUEST['cara44bienv2emprenotro']='';}
if (isset($_REQUEST['cara44bienv2emprenotrodetalle'])==0){$_REQUEST['cara44bienv2emprenotrodetalle']='';}
if (isset($_REQUEST['cara44bienv2emprenmarketing'])==0){$_REQUEST['cara44bienv2emprenmarketing']='';}
if (isset($_REQUEST['cara44bienv2emprenplannegocios'])==0){$_REQUEST['cara44bienv2emprenplannegocios']='';}
if (isset($_REQUEST['cara44bienv2emprenideas'])==0){$_REQUEST['cara44bienv2emprenideas']='';}
if (isset($_REQUEST['cara44bienv2emprencreacion'])==0){$_REQUEST['cara44bienv2emprencreacion']='';}
if (isset($_REQUEST['cara44bienv2saludfacteconom'])==0){$_REQUEST['cara44bienv2saludfacteconom']='';}
if (isset($_REQUEST['cara44bienv2saludpreocupacion'])==0){$_REQUEST['cara44bienv2saludpreocupacion']='';}
if (isset($_REQUEST['cara44bienv2saludconsumosust'])==0){$_REQUEST['cara44bienv2saludconsumosust']='';}
if (isset($_REQUEST['cara44bienv2saludinsomnio'])==0){$_REQUEST['cara44bienv2saludinsomnio']='';}
if (isset($_REQUEST['cara44bienv2saludclimalab'])==0){$_REQUEST['cara44bienv2saludclimalab']='';}
if (isset($_REQUEST['cara44bienv2saludalimenta'])==0){$_REQUEST['cara44bienv2saludalimenta']='';}
if (isset($_REQUEST['cara44bienv2saludemocion'])==0){$_REQUEST['cara44bienv2saludemocion']='';}
if (isset($_REQUEST['cara44bienv2saludestado'])==0){$_REQUEST['cara44bienv2saludestado']='';}
if (isset($_REQUEST['cara44bienv2saludmedita'])==0){$_REQUEST['cara44bienv2saludmedita']='';}
if (isset($_REQUEST['cara44bienv2crecimedusexual'])==0){$_REQUEST['cara44bienv2crecimedusexual']='';}
if (isset($_REQUEST['cara44bienv2crecimcultciudad'])==0){$_REQUEST['cara44bienv2crecimcultciudad']='';}
if (isset($_REQUEST['cara44bienv2crecimrelpareja'])==0){$_REQUEST['cara44bienv2crecimrelpareja']='';}
if (isset($_REQUEST['cara44bienv2crecimrelinterp'])==0){$_REQUEST['cara44bienv2crecimrelinterp']='';}
if (isset($_REQUEST['cara44bienv2crecimdinamicafam'])==0){$_REQUEST['cara44bienv2crecimdinamicafam']='';}
if (isset($_REQUEST['cara44bienv2crecimautoestima'])==0){$_REQUEST['cara44bienv2crecimautoestima']='';}
if (isset($_REQUEST['cara44bienv2creciminclusion'])==0){$_REQUEST['cara44bienv2creciminclusion']='';}
if (isset($_REQUEST['cara44bienv2creciminteliemoc'])==0){$_REQUEST['cara44bienv2creciminteliemoc']='';}
if (isset($_REQUEST['cara44bienv2crecimcultural'])==0){$_REQUEST['cara44bienv2crecimcultural']='';}
if (isset($_REQUEST['cara44bienv2crecimartistico'])==0){$_REQUEST['cara44bienv2crecimartistico']='';}
if (isset($_REQUEST['cara44bienv2crecimdeporte'])==0){$_REQUEST['cara44bienv2crecimdeporte']='';}
if (isset($_REQUEST['cara44bienv2crecimambiente'])==0){$_REQUEST['cara44bienv2crecimambiente']='';}
if (isset($_REQUEST['cara44bienv2crecimhabsocio'])==0){$_REQUEST['cara44bienv2crecimhabsocio']='';}
if (isset($_REQUEST['cara44bienv2ambienbasura'])==0){$_REQUEST['cara44bienv2ambienbasura']='';}
if (isset($_REQUEST['cara44bienv2ambienreutiliza'])==0){$_REQUEST['cara44bienv2ambienreutiliza']='';}
if (isset($_REQUEST['cara44bienv2ambienluces'])==0){$_REQUEST['cara44bienv2ambienluces']='';}
if (isset($_REQUEST['cara44bienv2ambienfrutaverd'])==0){$_REQUEST['cara44bienv2ambienfrutaverd']='';}
if (isset($_REQUEST['cara44bienv2ambienenchufa'])==0){$_REQUEST['cara44bienv2ambienenchufa']='';}
if (isset($_REQUEST['cara44bienv2ambiengrifo'])==0){$_REQUEST['cara44bienv2ambiengrifo']='';}
if (isset($_REQUEST['cara44bienv2ambienbicicleta'])==0){$_REQUEST['cara44bienv2ambienbicicleta']='';}
if (isset($_REQUEST['cara44bienv2ambientranspub'])==0){$_REQUEST['cara44bienv2ambientranspub']='';}
if (isset($_REQUEST['cara44bienv2ambienducha'])==0){$_REQUEST['cara44bienv2ambienducha']='';}
if (isset($_REQUEST['cara44bienv2ambiencaminata'])==0){$_REQUEST['cara44bienv2ambiencaminata']='';}
if (isset($_REQUEST['cara44bienv2ambiensiembra'])==0){$_REQUEST['cara44bienv2ambiensiembra']='';}
if (isset($_REQUEST['cara44bienv2ambienconferencia'])==0){$_REQUEST['cara44bienv2ambienconferencia']='';}
if (isset($_REQUEST['cara44bienv2ambienrecicla'])==0){$_REQUEST['cara44bienv2ambienrecicla']='';}
if (isset($_REQUEST['cara44bienv2ambienotraactiv'])==0){$_REQUEST['cara44bienv2ambienotraactiv']='';}
if (isset($_REQUEST['cara44bienv2ambienotraactivdetalle'])==0){$_REQUEST['cara44bienv2ambienotraactivdetalle']='';}
if (isset($_REQUEST['cara44bienv2ambienreforest'])==0){$_REQUEST['cara44bienv2ambienreforest']='';}
if (isset($_REQUEST['cara44bienv2ambienmovilidad'])==0){$_REQUEST['cara44bienv2ambienmovilidad']='';}
if (isset($_REQUEST['cara44bienv2ambienclimatico'])==0){$_REQUEST['cara44bienv2ambienclimatico']='';}
if (isset($_REQUEST['cara44bienv2ambienecofemin'])==0){$_REQUEST['cara44bienv2ambienecofemin']='';}
if (isset($_REQUEST['cara44bienv2ambienbiodiver'])==0){$_REQUEST['cara44bienv2ambienbiodiver']='';}
if (isset($_REQUEST['cara44bienv2ambienecologia'])==0){$_REQUEST['cara44bienv2ambienecologia']='';}
if (isset($_REQUEST['cara44bienv2ambieneconomia'])==0){$_REQUEST['cara44bienv2ambieneconomia']='';}
if (isset($_REQUEST['cara44bienv2ambienrecnatura'])==0){$_REQUEST['cara44bienv2ambienrecnatura']='';}
if (isset($_REQUEST['cara44bienv2ambienreciclaje'])==0){$_REQUEST['cara44bienv2ambienreciclaje']='';}
if (isset($_REQUEST['cara44bienv2ambienmascota'])==0){$_REQUEST['cara44bienv2ambienmascota']='';}
if (isset($_REQUEST['cara44bienv2ambiencartohum'])==0){$_REQUEST['cara44bienv2ambiencartohum']='';}
if (isset($_REQUEST['cara44bienv2ambienespiritu'])==0){$_REQUEST['cara44bienv2ambienespiritu']='';}
if (isset($_REQUEST['cara44bienv2ambiencarga'])==0){$_REQUEST['cara44bienv2ambiencarga']='';}
if (isset($_REQUEST['cara44bienv2ambienotroenfoq'])==0){$_REQUEST['cara44bienv2ambienotroenfoq']='';}
if (isset($_REQUEST['cara44bienv2ambienotroenfoqdetalle'])==0){$_REQUEST['cara44bienv2ambienotroenfoqdetalle']='';}
if (isset($_REQUEST['cara44fam_madrecabeza'])==0){$_REQUEST['cara44fam_madrecabeza']='';}
if (isset($_REQUEST['cara44acadhatenidorecesos'])==0){$_REQUEST['cara44acadhatenidorecesos']='';}
if (isset($_REQUEST['cara44acadrazonreceso'])==0){$_REQUEST['cara44acadrazonreceso']='';}
if (isset($_REQUEST['cara44acadrazonrecesodetalle'])==0){$_REQUEST['cara44acadrazonrecesodetalle']='';}
if (isset($_REQUEST['cara44campus_usocorreounad'])==0){$_REQUEST['cara44campus_usocorreounad']='';}
if (isset($_REQUEST['cara44campus_usocorreounadno'])==0){$_REQUEST['cara44campus_usocorreounadno']='';}
if (isset($_REQUEST['cara44campus_usocorreounadnodetalle'])==0){$_REQUEST['cara44campus_usocorreounadnodetalle']='';}
if (isset($_REQUEST['cara44campus_medioactivunad'])==0){$_REQUEST['cara44campus_medioactivunad']='';}
if (isset($_REQUEST['cara44campus_medioactivunaddetalle'])==0){$_REQUEST['cara44campus_medioactivunaddetalle']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=';';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sSQLcondi='cara44id='.$_REQUEST['cara44id'].'';
		}else{
		$sSQLcondi='cara44id='.$_REQUEST['cara44id'].'';
		}
	$sSQL='SELECT * FROM cara44encuesta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['cara44id']=$fila['cara44id'];
		$_REQUEST['cara44idcara']=$fila['cara44idcara'];
		$_REQUEST['cara44campesinado']=$fila['cara44campesinado'];
		$_REQUEST['cara44vacunacovid19']=$fila['cara44vacunacovid19'];
		$_REQUEST['cara44sexoversion']=$fila['cara44sexoversion'];
		$_REQUEST['cara44sexov1identidadgen']=$fila['cara44sexov1identidadgen'];
		$_REQUEST['cara44sexov1orientasexo']=$fila['cara44sexov1orientasexo'];
		$_REQUEST['cara44bienversion']=$fila['cara44bienversion'];
		$_REQUEST['cara44bienv2altoren']=$fila['cara44bienv2altoren'];
		$_REQUEST['cara44bienv2atletismo']=$fila['cara44bienv2atletismo'];
		$_REQUEST['cara44bienv2baloncesto']=$fila['cara44bienv2baloncesto'];
		$_REQUEST['cara44bienv2futbol']=$fila['cara44bienv2futbol'];
		$_REQUEST['cara44bienv2gimnasia']=$fila['cara44bienv2gimnasia'];
		$_REQUEST['cara44bienv2natacion']=$fila['cara44bienv2natacion'];
		$_REQUEST['cara44bienv2voleibol']=$fila['cara44bienv2voleibol'];
		$_REQUEST['cara44bienv2tenis']=$fila['cara44bienv2tenis'];
		$_REQUEST['cara44bienv2paralimpico']=$fila['cara44bienv2paralimpico'];
		$_REQUEST['cara44bienv2otrodeporte']=$fila['cara44bienv2otrodeporte'];
		$_REQUEST['cara44bienv2otrodeportedetalle']=$fila['cara44bienv2otrodeportedetalle'];
		$_REQUEST['cara44bienv2activdanza']=$fila['cara44bienv2activdanza'];
		$_REQUEST['cara44bienv2activmusica']=$fila['cara44bienv2activmusica'];
		$_REQUEST['cara44bienv2activteatro']=$fila['cara44bienv2activteatro'];
		$_REQUEST['cara44bienv2activartes']=$fila['cara44bienv2activartes'];
		$_REQUEST['cara44bienv2activliteratura']=$fila['cara44bienv2activliteratura'];
		$_REQUEST['cara44bienv2activculturalotra']=$fila['cara44bienv2activculturalotra'];
		$_REQUEST['cara44bienv2activculturalotradetalle']=$fila['cara44bienv2activculturalotradetalle'];
		$_REQUEST['cara44bienv2evenfestfolc']=$fila['cara44bienv2evenfestfolc'];
		$_REQUEST['cara44bienv2evenexpoarte']=$fila['cara44bienv2evenexpoarte'];
		$_REQUEST['cara44bienv2evenhistarte']=$fila['cara44bienv2evenhistarte'];
		$_REQUEST['cara44bienv2evengalfoto']=$fila['cara44bienv2evengalfoto'];
		$_REQUEST['cara44bienv2evenliteratura']=$fila['cara44bienv2evenliteratura'];
		$_REQUEST['cara44bienv2eventeatro']=$fila['cara44bienv2eventeatro'];
		$_REQUEST['cara44bienv2evencine']=$fila['cara44bienv2evencine'];
		$_REQUEST['cara44bienv2evenculturalotro']=$fila['cara44bienv2evenculturalotro'];
		$_REQUEST['cara44bienv2evenculturalotrodetalle']=$fila['cara44bienv2evenculturalotrodetalle'];
		$_REQUEST['cara44bienv2emprendimiento']=$fila['cara44bienv2emprendimiento'];
		$_REQUEST['cara44bienv2empresa']=$fila['cara44bienv2empresa'];
		$_REQUEST['cara44bienv2emprenrecursos']=$fila['cara44bienv2emprenrecursos'];
		$_REQUEST['cara44bienv2emprenconocim']=$fila['cara44bienv2emprenconocim'];
		$_REQUEST['cara44bienv2emprenplan']=$fila['cara44bienv2emprenplan'];
		$_REQUEST['cara44bienv2emprenejecutar']=$fila['cara44bienv2emprenejecutar'];
		$_REQUEST['cara44bienv2emprenfortconocim']=$fila['cara44bienv2emprenfortconocim'];
		$_REQUEST['cara44bienv2emprenidentproblema']=$fila['cara44bienv2emprenidentproblema'];
		$_REQUEST['cara44bienv2emprenotro']=$fila['cara44bienv2emprenotro'];
		$_REQUEST['cara44bienv2emprenotrodetalle']=$fila['cara44bienv2emprenotrodetalle'];
		$_REQUEST['cara44bienv2emprenmarketing']=$fila['cara44bienv2emprenmarketing'];
		$_REQUEST['cara44bienv2emprenplannegocios']=$fila['cara44bienv2emprenplannegocios'];
		$_REQUEST['cara44bienv2emprenideas']=$fila['cara44bienv2emprenideas'];
		$_REQUEST['cara44bienv2emprencreacion']=$fila['cara44bienv2emprencreacion'];
		$_REQUEST['cara44bienv2saludfacteconom']=$fila['cara44bienv2saludfacteconom'];
		$_REQUEST['cara44bienv2saludpreocupacion']=$fila['cara44bienv2saludpreocupacion'];
		$_REQUEST['cara44bienv2saludconsumosust']=$fila['cara44bienv2saludconsumosust'];
		$_REQUEST['cara44bienv2saludinsomnio']=$fila['cara44bienv2saludinsomnio'];
		$_REQUEST['cara44bienv2saludclimalab']=$fila['cara44bienv2saludclimalab'];
		$_REQUEST['cara44bienv2saludalimenta']=$fila['cara44bienv2saludalimenta'];
		$_REQUEST['cara44bienv2saludemocion']=$fila['cara44bienv2saludemocion'];
		$_REQUEST['cara44bienv2saludestado']=$fila['cara44bienv2saludestado'];
		$_REQUEST['cara44bienv2saludmedita']=$fila['cara44bienv2saludmedita'];
		$_REQUEST['cara44bienv2crecimedusexual']=$fila['cara44bienv2crecimedusexual'];
		$_REQUEST['cara44bienv2crecimcultciudad']=$fila['cara44bienv2crecimcultciudad'];
		$_REQUEST['cara44bienv2crecimrelpareja']=$fila['cara44bienv2crecimrelpareja'];
		$_REQUEST['cara44bienv2crecimrelinterp']=$fila['cara44bienv2crecimrelinterp'];
		$_REQUEST['cara44bienv2crecimdinamicafam']=$fila['cara44bienv2crecimdinamicafam'];
		$_REQUEST['cara44bienv2crecimautoestima']=$fila['cara44bienv2crecimautoestima'];
		$_REQUEST['cara44bienv2creciminclusion']=$fila['cara44bienv2creciminclusion'];
		$_REQUEST['cara44bienv2creciminteliemoc']=$fila['cara44bienv2creciminteliemoc'];
		$_REQUEST['cara44bienv2crecimcultural']=$fila['cara44bienv2crecimcultural'];
		$_REQUEST['cara44bienv2crecimartistico']=$fila['cara44bienv2crecimartistico'];
		$_REQUEST['cara44bienv2crecimdeporte']=$fila['cara44bienv2crecimdeporte'];
		$_REQUEST['cara44bienv2crecimambiente']=$fila['cara44bienv2crecimambiente'];
		$_REQUEST['cara44bienv2crecimhabsocio']=$fila['cara44bienv2crecimhabsocio'];
		$_REQUEST['cara44bienv2ambienbasura']=$fila['cara44bienv2ambienbasura'];
		$_REQUEST['cara44bienv2ambienreutiliza']=$fila['cara44bienv2ambienreutiliza'];
		$_REQUEST['cara44bienv2ambienluces']=$fila['cara44bienv2ambienluces'];
		$_REQUEST['cara44bienv2ambienfrutaverd']=$fila['cara44bienv2ambienfrutaverd'];
		$_REQUEST['cara44bienv2ambienenchufa']=$fila['cara44bienv2ambienenchufa'];
		$_REQUEST['cara44bienv2ambiengrifo']=$fila['cara44bienv2ambiengrifo'];
		$_REQUEST['cara44bienv2ambienbicicleta']=$fila['cara44bienv2ambienbicicleta'];
		$_REQUEST['cara44bienv2ambientranspub']=$fila['cara44bienv2ambientranspub'];
		$_REQUEST['cara44bienv2ambienducha']=$fila['cara44bienv2ambienducha'];
		$_REQUEST['cara44bienv2ambiencaminata']=$fila['cara44bienv2ambiencaminata'];
		$_REQUEST['cara44bienv2ambiensiembra']=$fila['cara44bienv2ambiensiembra'];
		$_REQUEST['cara44bienv2ambienconferencia']=$fila['cara44bienv2ambienconferencia'];
		$_REQUEST['cara44bienv2ambienrecicla']=$fila['cara44bienv2ambienrecicla'];
		$_REQUEST['cara44bienv2ambienotraactiv']=$fila['cara44bienv2ambienotraactiv'];
		$_REQUEST['cara44bienv2ambienotraactivdetalle']=$fila['cara44bienv2ambienotraactivdetalle'];
		$_REQUEST['cara44bienv2ambienreforest']=$fila['cara44bienv2ambienreforest'];
		$_REQUEST['cara44bienv2ambienmovilidad']=$fila['cara44bienv2ambienmovilidad'];
		$_REQUEST['cara44bienv2ambienclimatico']=$fila['cara44bienv2ambienclimatico'];
		$_REQUEST['cara44bienv2ambienecofemin']=$fila['cara44bienv2ambienecofemin'];
		$_REQUEST['cara44bienv2ambienbiodiver']=$fila['cara44bienv2ambienbiodiver'];
		$_REQUEST['cara44bienv2ambienecologia']=$fila['cara44bienv2ambienecologia'];
		$_REQUEST['cara44bienv2ambieneconomia']=$fila['cara44bienv2ambieneconomia'];
		$_REQUEST['cara44bienv2ambienrecnatura']=$fila['cara44bienv2ambienrecnatura'];
		$_REQUEST['cara44bienv2ambienreciclaje']=$fila['cara44bienv2ambienreciclaje'];
		$_REQUEST['cara44bienv2ambienmascota']=$fila['cara44bienv2ambienmascota'];
		$_REQUEST['cara44bienv2ambiencartohum']=$fila['cara44bienv2ambiencartohum'];
		$_REQUEST['cara44bienv2ambienespiritu']=$fila['cara44bienv2ambienespiritu'];
		$_REQUEST['cara44bienv2ambiencarga']=$fila['cara44bienv2ambiencarga'];
		$_REQUEST['cara44bienv2ambienotroenfoq']=$fila['cara44bienv2ambienotroenfoq'];
		$_REQUEST['cara44bienv2ambienotroenfoqdetalle']=$fila['cara44bienv2ambienotroenfoqdetalle'];
		$_REQUEST['cara44fam_madrecabeza']=$fila['cara44fam_madrecabeza'];
		$_REQUEST['cara44acadhatenidorecesos']=$fila['cara44acadhatenidorecesos'];
		$_REQUEST['cara44acadrazonreceso']=$fila['cara44acadrazonreceso'];
		$_REQUEST['cara44acadrazonrecesodetalle']=$fila['cara44acadrazonrecesodetalle'];
		$_REQUEST['cara44campus_usocorreounad']=$fila['cara44campus_usocorreounad'];
		$_REQUEST['cara44campus_usocorreounadno']=$fila['cara44campus_usocorreounadno'];
		$_REQUEST['cara44campus_usocorreounadnodetalle']=$fila['cara44campus_usocorreounadnodetalle'];
		$_REQUEST['cara44campus_medioactivunad']=$fila['cara44campus_medioactivunad'];
		$_REQUEST['cara44campus_medioactivunaddetalle']=$fila['cara44campus_medioactivunaddetalle'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta2344']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f2344_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f2344_db_Eliminar($_REQUEST['cara44id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['cara44id']='';
	$_REQUEST['cara44idcara']='';
	$_REQUEST['cara44campesinado']='';
	$_REQUEST['cara44vacunacovid19']='';
	$_REQUEST['cara44sexoversion']='';
	$_REQUEST['cara44sexov1identidadgen']=0;
	$_REQUEST['cara44sexov1orientasexo']=0;
	$_REQUEST['cara44bienversion']='';
	$_REQUEST['cara44bienv2altoren']='';
	$_REQUEST['cara44bienv2atletismo']='';
	$_REQUEST['cara44bienv2baloncesto']='';
	$_REQUEST['cara44bienv2futbol']='';
	$_REQUEST['cara44bienv2gimnasia']='';
	$_REQUEST['cara44bienv2natacion']='';
	$_REQUEST['cara44bienv2voleibol']='';
	$_REQUEST['cara44bienv2tenis']='';
	$_REQUEST['cara44bienv2paralimpico']='';
	$_REQUEST['cara44bienv2otrodeporte']='';
	$_REQUEST['cara44bienv2otrodeportedetalle']='';
	$_REQUEST['cara44bienv2activdanza']='';
	$_REQUEST['cara44bienv2activmusica']='';
	$_REQUEST['cara44bienv2activteatro']='';
	$_REQUEST['cara44bienv2activartes']='';
	$_REQUEST['cara44bienv2activliteratura']='';
	$_REQUEST['cara44bienv2activculturalotra']='';
	$_REQUEST['cara44bienv2activculturalotradetalle']='';
	$_REQUEST['cara44bienv2evenfestfolc']='';
	$_REQUEST['cara44bienv2evenexpoarte']='';
	$_REQUEST['cara44bienv2evenhistarte']='';
	$_REQUEST['cara44bienv2evengalfoto']='';
	$_REQUEST['cara44bienv2evenliteratura']='';
	$_REQUEST['cara44bienv2eventeatro']='';
	$_REQUEST['cara44bienv2evencine']='';
	$_REQUEST['cara44bienv2evenculturalotro']='';
	$_REQUEST['cara44bienv2evenculturalotrodetalle']='';
	$_REQUEST['cara44bienv2emprendimiento']='';
	$_REQUEST['cara44bienv2empresa']='';
	$_REQUEST['cara44bienv2emprenrecursos']='';
	$_REQUEST['cara44bienv2emprenconocim']='';
	$_REQUEST['cara44bienv2emprenplan']='';
	$_REQUEST['cara44bienv2emprenejecutar']='';
	$_REQUEST['cara44bienv2emprenfortconocim']='';
	$_REQUEST['cara44bienv2emprenidentproblema']='';
	$_REQUEST['cara44bienv2emprenotro']='';
	$_REQUEST['cara44bienv2emprenotrodetalle']='';
	$_REQUEST['cara44bienv2emprenmarketing']='';
	$_REQUEST['cara44bienv2emprenplannegocios']='';
	$_REQUEST['cara44bienv2emprenideas']='';
	$_REQUEST['cara44bienv2emprencreacion']='';
	$_REQUEST['cara44bienv2saludfacteconom']='';
	$_REQUEST['cara44bienv2saludpreocupacion']='';
	$_REQUEST['cara44bienv2saludconsumosust']='';
	$_REQUEST['cara44bienv2saludinsomnio']='';
	$_REQUEST['cara44bienv2saludclimalab']='';
	$_REQUEST['cara44bienv2saludalimenta']='';
	$_REQUEST['cara44bienv2saludemocion']='';
	$_REQUEST['cara44bienv2saludestado']='';
	$_REQUEST['cara44bienv2saludmedita']='';
	$_REQUEST['cara44bienv2crecimedusexual']='';
	$_REQUEST['cara44bienv2crecimcultciudad']='';
	$_REQUEST['cara44bienv2crecimrelpareja']='';
	$_REQUEST['cara44bienv2crecimrelinterp']='';
	$_REQUEST['cara44bienv2crecimdinamicafam']='';
	$_REQUEST['cara44bienv2crecimautoestima']='';
	$_REQUEST['cara44bienv2creciminclusion']='';
	$_REQUEST['cara44bienv2creciminteliemoc']='';
	$_REQUEST['cara44bienv2crecimcultural']='';
	$_REQUEST['cara44bienv2crecimartistico']='';
	$_REQUEST['cara44bienv2crecimdeporte']='';
	$_REQUEST['cara44bienv2crecimambiente']='';
	$_REQUEST['cara44bienv2crecimhabsocio']='';
	$_REQUEST['cara44bienv2ambienbasura']='';
	$_REQUEST['cara44bienv2ambienreutiliza']='';
	$_REQUEST['cara44bienv2ambienluces']='';
	$_REQUEST['cara44bienv2ambienfrutaverd']='';
	$_REQUEST['cara44bienv2ambienenchufa']='';
	$_REQUEST['cara44bienv2ambiengrifo']='';
	$_REQUEST['cara44bienv2ambienbicicleta']='';
	$_REQUEST['cara44bienv2ambientranspub']='';
	$_REQUEST['cara44bienv2ambienducha']='';
	$_REQUEST['cara44bienv2ambiencaminata']='';
	$_REQUEST['cara44bienv2ambiensiembra']='';
	$_REQUEST['cara44bienv2ambienconferencia']='';
	$_REQUEST['cara44bienv2ambienrecicla']='';
	$_REQUEST['cara44bienv2ambienotraactiv']='';
	$_REQUEST['cara44bienv2ambienotraactivdetalle']='';
	$_REQUEST['cara44bienv2ambienreforest']='';
	$_REQUEST['cara44bienv2ambienmovilidad']='';
	$_REQUEST['cara44bienv2ambienclimatico']='';
	$_REQUEST['cara44bienv2ambienecofemin']='';
	$_REQUEST['cara44bienv2ambienbiodiver']='';
	$_REQUEST['cara44bienv2ambienecologia']='';
	$_REQUEST['cara44bienv2ambieneconomia']='';
	$_REQUEST['cara44bienv2ambienrecnatura']='';
	$_REQUEST['cara44bienv2ambienreciclaje']='';
	$_REQUEST['cara44bienv2ambienmascota']='';
	$_REQUEST['cara44bienv2ambiencartohum']='';
	$_REQUEST['cara44bienv2ambienespiritu']='';
	$_REQUEST['cara44bienv2ambiencarga']='';
	$_REQUEST['cara44bienv2ambienotroenfoq']='';
	$_REQUEST['cara44bienv2ambienotroenfoqdetalle']='';
	$_REQUEST['cara44fam_madrecabeza']='';
	$_REQUEST['cara44acadhatenidorecesos']='';
	$_REQUEST['cara44acadrazonreceso']=0;
	$_REQUEST['cara44acadrazonrecesodetalle']='';
	$_REQUEST['cara44campus_usocorreounad']=0;
	$_REQUEST['cara44campus_usocorreounadno']=0;
	$_REQUEST['cara44campus_usocorreounadnodetalle']='';
	$_REQUEST['cara44campus_medioactivunad']=0;
	$_REQUEST['cara44campus_medioactivunaddetalle']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5=0;
$seg_6=0;
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
//if ($bDevuelve){$seg_6=1;}
//if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objCombos->nuevo('cara44campesinado', $_REQUEST['cara44campesinado'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44campesinado=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44vacunacovid19', $_REQUEST['cara44vacunacovid19'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44vacunacovid19=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44sexov1identidadgen', $_REQUEST['cara44sexov1identidadgen'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($acara44sexov1identidadgen, $icara44sexov1identidadgen);
$html_cara44sexov1identidadgen=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44sexov1orientasexo', $_REQUEST['cara44sexov1orientasexo'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($acara44sexov1orientasexo, $icara44sexov1orientasexo);
$html_cara44sexov1orientasexo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2altoren', $_REQUEST['cara44bienv2altoren'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2altoren=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2atletismo', $_REQUEST['cara44bienv2atletismo'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2atletismo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2baloncesto', $_REQUEST['cara44bienv2baloncesto'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2baloncesto=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2futbol', $_REQUEST['cara44bienv2futbol'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2futbol=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2gimnasia', $_REQUEST['cara44bienv2gimnasia'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2gimnasia=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2natacion', $_REQUEST['cara44bienv2natacion'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2natacion=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2voleibol', $_REQUEST['cara44bienv2voleibol'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2voleibol=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2tenis', $_REQUEST['cara44bienv2tenis'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2tenis=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2paralimpico', $_REQUEST['cara44bienv2paralimpico'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2paralimpico=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2otrodeporte', $_REQUEST['cara44bienv2otrodeporte'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2otrodeporte=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2activdanza', $_REQUEST['cara44bienv2activdanza'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2activdanza=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2activmusica', $_REQUEST['cara44bienv2activmusica'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2activmusica=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2activteatro', $_REQUEST['cara44bienv2activteatro'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2activteatro=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2activartes', $_REQUEST['cara44bienv2activartes'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2activartes=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2activliteratura', $_REQUEST['cara44bienv2activliteratura'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2activliteratura=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2activculturalotra', $_REQUEST['cara44bienv2activculturalotra'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2activculturalotra=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2evenfestfolc', $_REQUEST['cara44bienv2evenfestfolc'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2evenfestfolc=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2evenexpoarte', $_REQUEST['cara44bienv2evenexpoarte'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2evenexpoarte=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2evenhistarte', $_REQUEST['cara44bienv2evenhistarte'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2evenhistarte=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2evengalfoto', $_REQUEST['cara44bienv2evengalfoto'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2evengalfoto=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2evenliteratura', $_REQUEST['cara44bienv2evenliteratura'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2evenliteratura=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2eventeatro', $_REQUEST['cara44bienv2eventeatro'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2eventeatro=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2evencine', $_REQUEST['cara44bienv2evencine'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2evencine=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2evenculturalotro', $_REQUEST['cara44bienv2evenculturalotro'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2evenculturalotro=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprendimiento', $_REQUEST['cara44bienv2emprendimiento'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprendimiento=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2empresa', $_REQUEST['cara44bienv2empresa'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2empresa=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenrecursos', $_REQUEST['cara44bienv2emprenrecursos'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenrecursos=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenconocim', $_REQUEST['cara44bienv2emprenconocim'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenconocim=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenplan', $_REQUEST['cara44bienv2emprenplan'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenplan=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenejecutar', $_REQUEST['cara44bienv2emprenejecutar'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenejecutar=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenfortconocim', $_REQUEST['cara44bienv2emprenfortconocim'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenfortconocim=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenidentproblema', $_REQUEST['cara44bienv2emprenidentproblema'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenidentproblema=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenotro', $_REQUEST['cara44bienv2emprenotro'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenotro=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenmarketing', $_REQUEST['cara44bienv2emprenmarketing'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenmarketing=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenplannegocios', $_REQUEST['cara44bienv2emprenplannegocios'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenplannegocios=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprenideas', $_REQUEST['cara44bienv2emprenideas'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprenideas=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2emprencreacion', $_REQUEST['cara44bienv2emprencreacion'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2emprencreacion=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2saludfacteconom', $_REQUEST['cara44bienv2saludfacteconom'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2saludfacteconom=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2saludpreocupacion', $_REQUEST['cara44bienv2saludpreocupacion'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2saludpreocupacion=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2saludconsumosust', $_REQUEST['cara44bienv2saludconsumosust'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2saludconsumosust=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2saludinsomnio', $_REQUEST['cara44bienv2saludinsomnio'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2saludinsomnio=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2saludclimalab', $_REQUEST['cara44bienv2saludclimalab'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2saludclimalab=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2saludalimenta', $_REQUEST['cara44bienv2saludalimenta'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2saludalimenta=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2saludemocion', $_REQUEST['cara44bienv2saludemocion'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2saludemocion=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2saludestado', $_REQUEST['cara44bienv2saludestado'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2saludestado=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2saludmedita', $_REQUEST['cara44bienv2saludmedita'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2saludmedita=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimedusexual', $_REQUEST['cara44bienv2crecimedusexual'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimedusexual=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimcultciudad', $_REQUEST['cara44bienv2crecimcultciudad'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimcultciudad=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimrelpareja', $_REQUEST['cara44bienv2crecimrelpareja'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimrelpareja=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimrelinterp', $_REQUEST['cara44bienv2crecimrelinterp'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimrelinterp=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimdinamicafam', $_REQUEST['cara44bienv2crecimdinamicafam'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimdinamicafam=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimautoestima', $_REQUEST['cara44bienv2crecimautoestima'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimautoestima=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2creciminclusion', $_REQUEST['cara44bienv2creciminclusion'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2creciminclusion=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2creciminteliemoc', $_REQUEST['cara44bienv2creciminteliemoc'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2creciminteliemoc=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimcultural', $_REQUEST['cara44bienv2crecimcultural'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimcultural=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimartistico', $_REQUEST['cara44bienv2crecimartistico'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimartistico=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimdeporte', $_REQUEST['cara44bienv2crecimdeporte'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimdeporte=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimambiente', $_REQUEST['cara44bienv2crecimambiente'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimambiente=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2crecimhabsocio', $_REQUEST['cara44bienv2crecimhabsocio'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2crecimhabsocio=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienbasura', $_REQUEST['cara44bienv2ambienbasura'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienbasura=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienreutiliza', $_REQUEST['cara44bienv2ambienreutiliza'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienreutiliza=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienluces', $_REQUEST['cara44bienv2ambienluces'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienluces=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienfrutaverd', $_REQUEST['cara44bienv2ambienfrutaverd'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienfrutaverd=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienenchufa', $_REQUEST['cara44bienv2ambienenchufa'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienenchufa=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambiengrifo', $_REQUEST['cara44bienv2ambiengrifo'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambiengrifo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienbicicleta', $_REQUEST['cara44bienv2ambienbicicleta'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienbicicleta=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambientranspub', $_REQUEST['cara44bienv2ambientranspub'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambientranspub=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienducha', $_REQUEST['cara44bienv2ambienducha'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienducha=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambiencaminata', $_REQUEST['cara44bienv2ambiencaminata'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambiencaminata=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambiensiembra', $_REQUEST['cara44bienv2ambiensiembra'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambiensiembra=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienconferencia', $_REQUEST['cara44bienv2ambienconferencia'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienconferencia=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienrecicla', $_REQUEST['cara44bienv2ambienrecicla'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienrecicla=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienotraactiv', $_REQUEST['cara44bienv2ambienotraactiv'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienotraactiv=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienreforest', $_REQUEST['cara44bienv2ambienreforest'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienreforest=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienmovilidad', $_REQUEST['cara44bienv2ambienmovilidad'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienmovilidad=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienclimatico', $_REQUEST['cara44bienv2ambienclimatico'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienclimatico=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienecofemin', $_REQUEST['cara44bienv2ambienecofemin'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienecofemin=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienbiodiver', $_REQUEST['cara44bienv2ambienbiodiver'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienbiodiver=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienecologia', $_REQUEST['cara44bienv2ambienecologia'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienecologia=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambieneconomia', $_REQUEST['cara44bienv2ambieneconomia'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambieneconomia=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienrecnatura', $_REQUEST['cara44bienv2ambienrecnatura'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienrecnatura=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienreciclaje', $_REQUEST['cara44bienv2ambienreciclaje'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienreciclaje=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienmascota', $_REQUEST['cara44bienv2ambienmascota'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienmascota=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambiencartohum', $_REQUEST['cara44bienv2ambiencartohum'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambiencartohum=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienespiritu', $_REQUEST['cara44bienv2ambienespiritu'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienespiritu=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambiencarga', $_REQUEST['cara44bienv2ambiencarga'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambiencarga=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44bienv2ambienotroenfoq', $_REQUEST['cara44bienv2ambienotroenfoq'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44bienv2ambienotroenfoq=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44fam_madrecabeza', $_REQUEST['cara44fam_madrecabeza'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44fam_madrecabeza=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44acadhatenidorecesos', $_REQUEST['cara44acadhatenidorecesos'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_cara44acadhatenidorecesos=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44acadrazonreceso', $_REQUEST['cara44acadrazonreceso'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($acara44acadrazonreceso, $icara44acadrazonreceso);
$html_cara44acadrazonreceso=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44campus_usocorreounad', $_REQUEST['cara44campus_usocorreounad'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($acara44campus_usocorreounad, $icara44campus_usocorreounad);
$html_cara44campus_usocorreounad=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44campus_usocorreounadno', $_REQUEST['cara44campus_usocorreounadno'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($acara44campus_usocorreounadno, $icara44campus_usocorreounadno);
$html_cara44campus_usocorreounadno=$objCombos->html('', $objDB);
$objCombos->nuevo('cara44campus_medioactivunad', $_REQUEST['cara44campus_medioactivunad'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($acara44campus_medioactivunad, $icara44campus_medioactivunad);
$html_cara44campus_medioactivunad=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	$html_cara44idcara=f2344_HTMLComboV2_cara44idcara($objDB, $objCombos, $_REQUEST['cara44idcara']);
	}else{
	$cara44idcara_nombre='&nbsp;';
	if ((int)$_REQUEST['cara44idcara']!=0){
		list($cara44idcara_nombre, $sErrorDet)=tabla_campoxid('cara01encuesta','cara01id','cara01id',$_REQUEST['cara44idcara'],'{'.$ETI['msg_sindato'].'}', $objDB);
		}
	$html_cara44idcara=html_oculto('cara44idcara', $_REQUEST['cara44idcara'], $cara44idcara_nombre);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2344()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(2344, 1, $objDB, 'paginarf2344()');
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
$iModeloReporte=2344;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_2344'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf2344'];
$aParametros[102]=$_REQUEST['lppf2344'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla2344, $sDebugTabla)=f2344_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2344']);
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
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2344.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2344.value;
		window.document.frmlista.nombrearchivo.value='Encuesta de caracterización';
		window.document.frmlista.submit();
		}else{
		ModalMensaje("<?php echo $ERR['6']; ?>");
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
		window.document.frmimpp.action='e2344.php';
		window.document.frmimpp.submit();
		}else{
		ModalMensaje(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2344.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
		}else{
		ModalMensaje("<?php echo $ERR['5']; ?>");
		}
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	ModalConfirm('<?php echo $ETI['msg_confirmaeliminar']; ?>');
	ModalDialogConfirm(function(confirm){if(confirm){ejecuta_eliminadato();}});
	}
function ejecuta_eliminadato(){
	MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
	expandesector(98);
	window.document.frmedita.paso.value=13;
	window.document.frmedita.submit();
	}
function RevisaLlave(){
	var datos= new Array();
	datos[2]=window.document.frmedita.cara44idcara.value;
	if ((datos[2]!='')){
		xajax_f2344_ExisteDato(datos);
		}
	}
function cargadato(llave2){
	window.document.frmedita.cara44idcara.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf2344(llave1){
	window.document.frmedita.cara44id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf2344(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf2344.value;
	params[102]=window.document.frmedita.lppf2344.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f2344detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2344" name="paginaf2344" type="hidden" value="'+params[101]+'" /><input id="lppf2344" name="lppf2344" type="hidden" value="'+params[102]+'" />';
	xajax_f2344_HtmlTabla(params);
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
	document.getElementById("cara44id").focus();
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
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
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2344.php" target="_blank">
<input id="r" name="r" type="hidden" value="2344" />
<input id="id2344" name="id2344" type="hidden" value="<?php echo $_REQUEST['cara44id']; ?>" />
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
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo $iHoy; ?>" />
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
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
	}
$bHayImprimir=false;
$sScript='imprimelista()';
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
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
if (false){
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2344'].'</h2>';
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
<input id="boculta2344" name="boculta2344" type="hidden" value="<?php echo $_REQUEST['boculta2344']; ?>" />
<label class="Label30">
<input id="btexpande2344" name="btexpande2344" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2344,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2344']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2344" name="btrecoge2344" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2344,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2344']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2344" style="display:<?php if ($_REQUEST['boculta2344']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label60">
<?php
echo $ETI['cara44id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('cara44id', $_REQUEST['cara44id'], formato_numero($_REQUEST['cara44id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44idcara'];
?>
</label>
<label>
<?php
echo $html_cara44idcara;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44campesinado'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44campesinado;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44vacunacovid19'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44vacunacovid19;
?>
</label>
<input id="cara44sexoversion" name="cara44sexoversion" type="hidden" value="<?php echo $_REQUEST['cara44sexoversion']; ?>"/>
<label class="Label130">
<?php
echo $ETI['cara44sexov1identidadgen'];
?>
</label>
<label>
<?php
echo $html_cara44sexov1identidadgen;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44sexov1orientasexo'];
?>
</label>
<label>
<?php
echo $html_cara44sexov1orientasexo;
?>
</label>
<input id="cara44bienversion" name="cara44bienversion" type="hidden" value="<?php echo $_REQUEST['cara44bienversion']; ?>"/>
<label class="Label130">
<?php
echo $ETI['cara44bienv2altoren'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2altoren;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2atletismo'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2atletismo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2baloncesto'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2baloncesto;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2futbol'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2futbol;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2gimnasia'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2gimnasia;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2natacion'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2natacion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2voleibol'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2voleibol;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2tenis'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2tenis;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2paralimpico'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2paralimpico;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2otrodeporte'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2otrodeporte;
?>
</label>
<label class="L">
<?php
echo $ETI['cara44bienv2otrodeportedetalle'];
?>

<input id="cara44bienv2otrodeportedetalle" name="cara44bienv2otrodeportedetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2otrodeportedetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara44bienv2otrodeportedetalle']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activdanza'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2activdanza;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activmusica'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2activmusica;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activteatro'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2activteatro;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activartes'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2activartes;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activliteratura'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2activliteratura;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activculturalotra'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2activculturalotra;
?>
</label>
<label class="L">
<?php
echo $ETI['cara44bienv2activculturalotradetalle'];
?>

<input id="cara44bienv2activculturalotradetalle" name="cara44bienv2activculturalotradetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2activculturalotradetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara44bienv2activculturalotradetalle']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenfestfolc'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2evenfestfolc;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenexpoarte'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2evenexpoarte;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenhistarte'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2evenhistarte;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evengalfoto'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2evengalfoto;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenliteratura'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2evenliteratura;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2eventeatro'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2eventeatro;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evencine'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2evencine;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenculturalotro'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2evenculturalotro;
?>
</label>
<label class="L">
<?php
echo $ETI['cara44bienv2evenculturalotrodetalle'];
?>

<input id="cara44bienv2evenculturalotrodetalle" name="cara44bienv2evenculturalotrodetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2evenculturalotrodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara44bienv2evenculturalotrodetalle']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprendimiento'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprendimiento;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2empresa'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2empresa;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenrecursos'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenrecursos;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenconocim'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenconocim;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenplan'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenplan;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenejecutar'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenejecutar;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenfortconocim'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenfortconocim;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenidentproblema'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenidentproblema;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenotro'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenotro;
?>
</label>
<label class="L">
<?php
echo $ETI['cara44bienv2emprenotrodetalle'];
?>

<input id="cara44bienv2emprenotrodetalle" name="cara44bienv2emprenotrodetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2emprenotrodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara44bienv2emprenotrodetalle']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenmarketing'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenmarketing;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenplannegocios'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenplannegocios;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprenideas'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprenideas;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2emprencreacion'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2emprencreacion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludfacteconom'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2saludfacteconom;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludpreocupacion'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2saludpreocupacion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludconsumosust'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2saludconsumosust;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludinsomnio'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2saludinsomnio;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludclimalab'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2saludclimalab;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludalimenta'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2saludalimenta;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludemocion'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2saludemocion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludestado'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2saludestado;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludmedita'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2saludmedita;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimedusexual'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimedusexual;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimcultciudad'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimcultciudad;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimrelpareja'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimrelpareja;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimrelinterp'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimrelinterp;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimdinamicafam'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimdinamicafam;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimautoestima'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimautoestima;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2creciminclusion'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2creciminclusion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2creciminteliemoc'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2creciminteliemoc;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimcultural'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimcultural;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimartistico'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimartistico;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimdeporte'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimdeporte;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimambiente'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimambiente;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2crecimhabsocio'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2crecimhabsocio;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienbasura'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienbasura;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienreutiliza'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienreutiliza;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienluces'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienluces;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienfrutaverd'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienfrutaverd;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienenchufa'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienenchufa;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambiengrifo'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambiengrifo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienbicicleta'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienbicicleta;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambientranspub'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambientranspub;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienducha'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienducha;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambiencaminata'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambiencaminata;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambiensiembra'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambiensiembra;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienconferencia'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienconferencia;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienrecicla'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienrecicla;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienotraactiv'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienotraactiv;
?>
</label>
<label class="L">
<?php
echo $ETI['cara44bienv2ambienotraactivdetalle'];
?>

<input id="cara44bienv2ambienotraactivdetalle" name="cara44bienv2ambienotraactivdetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2ambienotraactivdetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara44bienv2ambienotraactivdetalle']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienreforest'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienreforest;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienmovilidad'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienmovilidad;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienclimatico'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienclimatico;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienecofemin'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienecofemin;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienbiodiver'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienbiodiver;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienecologia'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienecologia;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambieneconomia'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambieneconomia;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienrecnatura'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienrecnatura;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienreciclaje'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienreciclaje;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienmascota'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienmascota;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambiencartohum'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambiencartohum;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienespiritu'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienespiritu;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambiencarga'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambiencarga;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienotroenfoq'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44bienv2ambienotroenfoq;
?>
</label>
<label class="L">
<?php
echo $ETI['cara44bienv2ambienotroenfoqdetalle'];
?>

<input id="cara44bienv2ambienotroenfoqdetalle" name="cara44bienv2ambienotroenfoqdetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2ambienotroenfoqdetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara44bienv2ambienotroenfoqdetalle']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara44fam_madrecabeza'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44fam_madrecabeza;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44acadhatenidorecesos'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara44acadhatenidorecesos;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44acadrazonreceso'];
?>
</label>
<label>
<?php
echo $html_cara44acadrazonreceso;
?>
</label>
<label class="L">
<?php
echo $ETI['cara44acadrazonrecesodetalle'];
?>

<input id="cara44acadrazonrecesodetalle" name="cara44acadrazonrecesodetalle" type="text" value="<?php echo $_REQUEST['cara44acadrazonrecesodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara44acadrazonrecesodetalle']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara44campus_usocorreounad'];
?>
</label>
<label>
<?php
echo $html_cara44campus_usocorreounad;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44campus_usocorreounadno'];
?>
</label>
<label>
<?php
echo $html_cara44campus_usocorreounadno;
?>
</label>
<label class="L">
<?php
echo $ETI['cara44campus_usocorreounadnodetalle'];
?>

<input id="cara44campus_usocorreounadnodetalle" name="cara44campus_usocorreounadnodetalle" type="text" value="<?php echo $_REQUEST['cara44campus_usocorreounadnodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara44campus_usocorreounadnodetalle']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara44campus_medioactivunad'];
?>
</label>
<label>
<?php
echo $html_cara44campus_medioactivunad;
?>
</label>
<label class="L">
<?php
echo $ETI['cara44campus_medioactivunaddetalle'];
?>

<input id="cara44campus_medioactivunaddetalle" name="cara44campus_medioactivunaddetalle" type="text" value="<?php echo $_REQUEST['cara44campus_medioactivunaddetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara44campus_medioactivunaddetalle']; ?>"/>
</label>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p2344
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
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2344()" autocomplete="off"/>
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
echo ' '.$csv_separa;
?>
<div id="div_f2344detalle">
<?php
echo $sTabla2344;
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
<input id="titulo_2344" name="titulo_2344" type="hidden" value="<?php echo $ETI['titulo_2344']; ?>" />
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


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2344'].'</h2>';
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
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
?>
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
	//El script que cambia el sector que se muestra
?>

<script language="javascript">
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
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<?php
if ($_REQUEST['paso']==0){
?>
<script language="javascript">
$().ready(function(){
//$("#bperiodo").chosen();
});
</script>
<?php
	}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>