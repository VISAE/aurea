<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 domingo, 29 de noviembre de 2015
--- Modelo Versión 2.12.5 lunes, 14 de marzo de 2016
--- Modelo Versión 2.12.5 miércoles, 23 de marzo de 2016
--- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
--- Modelo Versión 2.16.3 miércoles, 04 de enero de 2017
--- Modelo Versión 2.18.1 martes, 16 de mayo de 2017
--- Modelo Versión 2.22.6b miércoles, 28 de noviembre de 2018
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
	$_REQUEST['debug']=1;
	}
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
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=1916;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_1916='lg/lg_1916_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1916)){$mensajes_1916='lg/lg_1916_es.php';}
require $mensajes_todas;
require $mensajes_1916;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
$idTercero=$_SESSION['unad_id_tercero'];
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=evenencuesta.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
$mensajes_1918='lg/lg_1918_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1918)){$mensajes_1918='lg/lg_1918_es.php';}
$mensajes_1919='lg/lg_1919_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1919)){$mensajes_1919='lg/lg_1919_es.php';}
$mensajes_1921='lg/lg_1921_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1921)){$mensajes_1921='lg/lg_1921_es.php';}
$mensajes_1924='lg/lg_1924_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1924)){$mensajes_1924='lg/lg_1924_es.php';}
$mensajes_1925='lg/lg_1925_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1925)){$mensajes_1925='lg/lg_1925_es.php';}
$mensajes_1932='lg/lg_1932_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1932)){$mensajes_1932='lg/lg_1932_es.php';}
$mensajes_1940='lg/lg_1940_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1940)){$mensajes_1940='lg/lg_1940_es.php';}
require $mensajes_1918;
require $mensajes_1919;
require $mensajes_1921;
require $mensajes_1924;
require $mensajes_1925;
require $mensajes_1932;
require $mensajes_1940;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 1916 even16encuesta
require 'lib1916.php';
// -- 1918 Preguntas
require 'lib1918.php';
// -- 1919 Grupos de preguntas
require 'lib1919.php';
// -- 1921 Encuestas aplicadas
require 'lib1921.php';
// -- 1924 Periodos que aplican
require 'lib1924.php';
// -- 1925 Cursos que aplican
require 'lib1925.php';
// -- 1929 Opciones de respuesta.
require 'lib1929.php';
// -- 1932 Roles que aplican
require 'lib1932.php';
// -- 1940 Propietarios
require 'lib1940.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f1916_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1916_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f1916_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f1916_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f1918_Comboeven18valorcondiciona');
$xajax->register(XAJAX_FUNCTION,'f1918_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1918_Traer');
$xajax->register(XAJAX_FUNCTION,'f1918_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1918_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1918_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'Cargar_even18valorcondiciona');
$xajax->register(XAJAX_FUNCTION,'f1918_AgregarOpcion');
$xajax->register(XAJAX_FUNCTION,'f1918_QuitarOpcion');
$xajax->register(XAJAX_FUNCTION,'f1919_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1919_Traer');
$xajax->register(XAJAX_FUNCTION,'f1919_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1919_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1919_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'TraerBusqueda_even21idcurso');
$xajax->register(XAJAX_FUNCTION,'f1921_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1921_Traer');
$xajax->register(XAJAX_FUNCTION,'f1921_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1921_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1921_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f1921_AbrirEncuesta');
$xajax->register(XAJAX_FUNCTION,'f1921_QuitarEncuesta');
$xajax->register(XAJAX_FUNCTION,'f1921_QuitarEncuestasPendientes');
$xajax->register(XAJAX_FUNCTION,'f1924_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1924_Traer');
$xajax->register(XAJAX_FUNCTION,'f1924_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1924_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1924_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'TraerBusqueda_even25idcurso');
$xajax->register(XAJAX_FUNCTION,'f1925_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1925_Traer');
$xajax->register(XAJAX_FUNCTION,'f1925_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1925_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1925_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f1929_GuardaEtiqueta');
$xajax->register(XAJAX_FUNCTION,'f1929_GuardaDetalle');
$xajax->register(XAJAX_FUNCTION,'f1932_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1932_Traer');
$xajax->register(XAJAX_FUNCTION,'f1932_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1932_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1932_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f1940_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1940_Traer');
$xajax->register(XAJAX_FUNCTION,'f1940_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1940_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1940_PintarLlaves');
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
if (isset($_REQUEST['paginaf1916'])==0){$_REQUEST['paginaf1916']=1;}
if (isset($_REQUEST['lppf1916'])==0){$_REQUEST['lppf1916']=20;}
if (isset($_REQUEST['boculta1916'])==0){$_REQUEST['boculta1916']=0;}
if (isset($_REQUEST['paginaf1918'])==0){$_REQUEST['paginaf1918']=1;}
if (isset($_REQUEST['lppf1918'])==0){$_REQUEST['lppf1918']=10;}
if (isset($_REQUEST['boculta1918'])==0){$_REQUEST['boculta1918']=0;}
if (isset($_REQUEST['paginaf1919'])==0){$_REQUEST['paginaf1919']=1;}
if (isset($_REQUEST['lppf1919'])==0){$_REQUEST['lppf1919']=10;}
if (isset($_REQUEST['boculta1919'])==0){$_REQUEST['boculta1919']=1;}
if (isset($_REQUEST['paginaf1921'])==0){$_REQUEST['paginaf1921']=1;}
if (isset($_REQUEST['lppf1921'])==0){$_REQUEST['lppf1921']=10;}
if (isset($_REQUEST['boculta1921'])==0){$_REQUEST['boculta1921']=0;}
if (isset($_REQUEST['paginaf1924'])==0){$_REQUEST['paginaf1924']=1;}
if (isset($_REQUEST['lppf1924'])==0){$_REQUEST['lppf1924']=20;}
if (isset($_REQUEST['boculta1924'])==0){$_REQUEST['boculta1924']=0;}
if (isset($_REQUEST['paginaf1925'])==0){$_REQUEST['paginaf1925']=1;}
if (isset($_REQUEST['lppf1925'])==0){$_REQUEST['lppf1925']=20;}
if (isset($_REQUEST['boculta1925'])==0){$_REQUEST['boculta1925']=0;}
if (isset($_REQUEST['paginaf1932'])==0){$_REQUEST['paginaf1932']=1;}
if (isset($_REQUEST['lppf1932'])==0){$_REQUEST['lppf1932']=20;}
if (isset($_REQUEST['boculta1932'])==0){$_REQUEST['boculta1932']=0;}
if (isset($_REQUEST['paginaf1940'])==0){$_REQUEST['paginaf1940']=1;}
if (isset($_REQUEST['lppf1940'])==0){$_REQUEST['lppf1940']=20;}
if (isset($_REQUEST['boculta1940'])==0){$_REQUEST['boculta1940']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['even16consec'])==0){$_REQUEST['even16consec']='';}
if (isset($_REQUEST['even16consec_nuevo'])==0){$_REQUEST['even16consec_nuevo']='';}
if (isset($_REQUEST['even16id'])==0){$_REQUEST['even16id']='';}
if (isset($_REQUEST['even16idproceso'])==0){$_REQUEST['even16idproceso']=0;}
if (isset($_REQUEST['even16porperiodo'])==0){$_REQUEST['even16porperiodo']='S';}
if (isset($_REQUEST['even16porcurso'])==0){$_REQUEST['even16porcurso']='S';}
if (isset($_REQUEST['even16porbloqueo'])==0){$_REQUEST['even16porbloqueo']='N';}
if (isset($_REQUEST['even16idbloqueo'])==0){$_REQUEST['even16idbloqueo']='';}
if (isset($_REQUEST['even16encabezado'])==0){$_REQUEST['even16encabezado']='';}
if (isset($_REQUEST['even16idusuario'])==0){$_REQUEST['even16idusuario']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['even16idusuario_td'])==0){$_REQUEST['even16idusuario_td']=$APP->tipo_doc;}
if (isset($_REQUEST['even16idusuario_doc'])==0){$_REQUEST['even16idusuario_doc']='';}
if (isset($_REQUEST['even16fechainicio'])==0){$_REQUEST['even16fechainicio']='';}//{fecha_hoy();}
if (isset($_REQUEST['even16fechafin'])==0){$_REQUEST['even16fechafin']='';}//{fecha_hoy();}
if (isset($_REQUEST['even16publicada'])==0){$_REQUEST['even16publicada']='N';}
if (isset($_REQUEST['even16caracter'])==0){$_REQUEST['even16caracter']=7;}
if (isset($_REQUEST['even16tamanomuestra'])==0){$_REQUEST['even16tamanomuestra']='';}
if (isset($_REQUEST['even16porrol'])==0){$_REQUEST['even16porrol']='N';}
if (isset($_REQUEST['even16tienepropietario'])==0){$_REQUEST['even16tienepropietario']='N';}
if ((int)$_REQUEST['paso']>0){
	//Preguntas
	if (isset($_REQUEST['even18consec'])==0){$_REQUEST['even18consec']='';}
	if (isset($_REQUEST['even18id'])==0){$_REQUEST['even18id']='';}
	if (isset($_REQUEST['even18idgrupo'])==0){$_REQUEST['even18idgrupo']='';}
	if (isset($_REQUEST['even18pregunta'])==0){$_REQUEST['even18pregunta']='';}
	if (isset($_REQUEST['even18tiporespuesta'])==0){$_REQUEST['even18tiporespuesta']='';}
	if (isset($_REQUEST['even18opcional'])==0){$_REQUEST['even18opcional']='N';}
	if (isset($_REQUEST['even18concomentario'])==0){$_REQUEST['even18concomentario']='N';}
	if (isset($_REQUEST['even18rpta0'])==0){$_REQUEST['even18rpta0']='';}
	if (isset($_REQUEST['even18rpta1'])==0){$_REQUEST['even18rpta1']='';}
	if (isset($_REQUEST['even18rpta2'])==0){$_REQUEST['even18rpta2']='';}
	if (isset($_REQUEST['even18rpta3'])==0){$_REQUEST['even18rpta3']='';}
	if (isset($_REQUEST['even18rpta4'])==0){$_REQUEST['even18rpta4']='';}
	if (isset($_REQUEST['even18rpta5'])==0){$_REQUEST['even18rpta5']='';}
	if (isset($_REQUEST['even18rpta6'])==0){$_REQUEST['even18rpta6']='';}
	if (isset($_REQUEST['even18rpta7'])==0){$_REQUEST['even18rpta7']='';}
	if (isset($_REQUEST['even18rpta8'])==0){$_REQUEST['even18rpta8']='';}
	if (isset($_REQUEST['even18rpta9'])==0){$_REQUEST['even18rpta9']='';}
	if (isset($_REQUEST['even18orden'])==0){$_REQUEST['even18orden']=1;}
	if (isset($_REQUEST['even18divergente'])==0){$_REQUEST['even18divergente']='N';}
	if (isset($_REQUEST['even18rptatotal'])==0){$_REQUEST['even18rptatotal']='0';}
	if (isset($_REQUEST['even18idpregcondiciona'])==0){$_REQUEST['even18idpregcondiciona']='0';}
	if (isset($_REQUEST['even18valorcondiciona'])==0){$_REQUEST['even18valorcondiciona']='0';}
	if (isset($_REQUEST['even18url'])==0){$_REQUEST['even18url']='';}
	//Grupos de preguntas
	if (isset($_REQUEST['even19consec'])==0){$_REQUEST['even19consec']='';}
	if (isset($_REQUEST['even19id'])==0){$_REQUEST['even19id']='';}
	if (isset($_REQUEST['even19nombre'])==0){$_REQUEST['even19nombre']='';}
	//Encuestas aplicadas
	if (isset($_REQUEST['even21idtercero'])==0){$_REQUEST['even21idtercero']=0;}//{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['even21idtercero_td'])==0){$_REQUEST['even21idtercero_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['even21idtercero_doc'])==0){$_REQUEST['even21idtercero_doc']='';}
	if (isset($_REQUEST['even21idperaca'])==0){$_REQUEST['even21idperaca']='';}
	if (isset($_REQUEST['even21idcurso'])==0){$_REQUEST['even21idcurso']='';}
	if (isset($_REQUEST['even21idcurso_cod'])==0){$_REQUEST['even21idcurso_cod']='';}
	$even21idcurso_nombre='';
	if (isset($_REQUEST['even21idbloquedo'])==0){$_REQUEST['even21idbloquedo']='';}
	if (isset($_REQUEST['even21id'])==0){$_REQUEST['even21id']='';}
	if (isset($_REQUEST['even21fechapresenta'])==0){$_REQUEST['even21fechapresenta']='';}//{fecha_hoy();}
	if (isset($_REQUEST['even21terminada'])==0){$_REQUEST['even21terminada']='';}
	//Periodos que aplican
	if (isset($_REQUEST['even24idperaca'])==0){$_REQUEST['even24idperaca']='';}
	if (isset($_REQUEST['even24id'])==0){$_REQUEST['even24id']='';}
	if (isset($_REQUEST['even24fechainicial'])==0){$_REQUEST['even24fechainicial']='';}//{fecha_hoy();}
	if (isset($_REQUEST['even24fechafinal'])==0){$_REQUEST['even24fechafinal']='';}//{fecha_hoy();}
	//Cursos que aplican
	if (isset($_REQUEST['even25idcurso'])==0){$_REQUEST['even25idcurso']='';}
	if (isset($_REQUEST['even25idcurso_cod'])==0){$_REQUEST['even25idcurso_cod']='';}
	$even25idcurso_nombre='';
	if (isset($_REQUEST['even25id'])==0){$_REQUEST['even25id']='';}
	if (isset($_REQUEST['even25activo'])==0){$_REQUEST['even25activo']='S';}
	//Roles que aplican
	if (isset($_REQUEST['even32idrol'])==0){$_REQUEST['even32idrol']='';}
	if (isset($_REQUEST['even32id'])==0){$_REQUEST['even32id']='';}
	if (isset($_REQUEST['even32activo'])==0){$_REQUEST['even32activo']='S';}
	//Propietarios
	if (isset($_REQUEST['even40idpropietario'])==0){$_REQUEST['even40idpropietario']=0;}//{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['even40idpropietario_td'])==0){$_REQUEST['even40idpropietario_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['even40idpropietario_doc'])==0){$_REQUEST['even40idpropietario_doc']='';}
	if (isset($_REQUEST['even40id'])==0){$_REQUEST['even40id']='';}
	if (isset($_REQUEST['even40activo'])==0){$_REQUEST['even40activo']='S';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ((int)$_REQUEST['paso']>0){
	//Preguntas
	if (isset($_REQUEST['bnombre1918'])==0){$_REQUEST['bnombre1918']='';}
	//if (isset($_REQUEST['blistar1918'])==0){$_REQUEST['blistar1918']='';}
	//Grupos de preguntas
	if (isset($_REQUEST['bnombre1919'])==0){$_REQUEST['bnombre1919']='';}
	//if (isset($_REQUEST['blistar1919'])==0){$_REQUEST['blistar1919']='';}
	//Encuestas aplicadas
	if (isset($_REQUEST['bnombre1921'])==0){$_REQUEST['bnombre1921']='';}
	if (isset($_REQUEST['bdoc1921'])==0){$_REQUEST['bdoc1921']='';}
	if (isset($_REQUEST['bperaca1921'])==0){$_REQUEST['bperaca1921']='';}
	if (isset($_REQUEST['bcodcurso1921'])==0){$_REQUEST['bcodcurso1921']='';}
	//Periodos que aplican
	if (isset($_REQUEST['bnombre1924'])==0){$_REQUEST['bnombre1924']='';}
	//if (isset($_REQUEST['blistar1924'])==0){$_REQUEST['blistar1924']='';}
	//Cursos que aplican
	if (isset($_REQUEST['bnombre1925'])==0){$_REQUEST['bnombre1925']='';}
	//if (isset($_REQUEST['blistar1925'])==0){$_REQUEST['blistar1925']='';}
	//Roles que aplican
	if (isset($_REQUEST['bnombre1932'])==0){$_REQUEST['bnombre1932']='';}
	//if (isset($_REQUEST['blistar1932'])==0){$_REQUEST['blistar1932']='';}
	//Propietarios
	if (isset($_REQUEST['bnombre1940'])==0){$_REQUEST['bnombre1940']='';}
	//if (isset($_REQUEST['blistar1940'])==0){$_REQUEST['blistar1940']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['even16idusuario_td']=$APP->tipo_doc;
	$_REQUEST['even16idusuario_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='even16consec='.$_REQUEST['even16consec'].'';
		}else{
		$sSQLcondi='even16id='.$_REQUEST['even16id'].'';
		}
	$sSQL='SELECT * FROM even16encuesta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['even16consec']=$fila['even16consec'];
		$_REQUEST['even16id']=$fila['even16id'];
		$_REQUEST['even16idproceso']=$fila['even16idproceso'];
		$_REQUEST['even16porperiodo']=$fila['even16porperiodo'];
		$_REQUEST['even16porcurso']=$fila['even16porcurso'];
		$_REQUEST['even16porbloqueo']=$fila['even16porbloqueo'];
		$_REQUEST['even16idbloqueo']=$fila['even16idbloqueo'];
		$_REQUEST['even16encabezado']=$fila['even16encabezado'];
		$_REQUEST['even16idusuario']=$fila['even16idusuario'];
		$_REQUEST['even16fechainicio']=$fila['even16fechainicio'];
		$_REQUEST['even16fechafin']=$fila['even16fechafin'];
		$_REQUEST['even16publicada']=$fila['even16publicada'];
		$_REQUEST['even16caracter']=$fila['even16caracter'];
		$_REQUEST['even16tamanomuestra']=$fila['even16tamanomuestra'];
		$_REQUEST['even16porrol']=$fila['even16porrol'];
		$_REQUEST['even16tienepropietario']=$fila['even16tienepropietario'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta1916']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Cerrar
$bCerrando=false;
$bModFechaEncuestas=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['even16publicada']='S';
	$bCerrando=true;
	$bModFechaEncuestas=true;
	}
//Abrir
if ($_REQUEST['paso']==17){
	$_REQUEST['paso']=2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if (!seg_revisa_permiso($iCodModulo, 17, $objDB)){
		$sError=$ERR['3'];
		}
	//Otras restricciones para abrir.
	if ($sError==''){
		//$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
		}
	if ($sError!=''){
		$_REQUEST['even16publicada']='S';
		}else{
		$sSQL='UPDATE even16encuesta SET even16publicada="N" WHERE even16id='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['even16id'], 'Despublica la encuesta', $objDB);
		$_REQUEST['even16publicada']='N';
		$bModFechaEncuestas=true;
		$sError='<b>La encuesta ha sido despublicada</b>';
		$iTipoError=1;
		}
	}
$bClonando=false;
$idOrigenClon=0;
if ($_REQUEST['paso']==32){
	$_REQUEST['paso']=10;
	$bClonando=true;
	$idOrigenClon=$_REQUEST['even16id'];
	$_REQUEST['even16consec']=$_REQUEST['even16consec_c'];
	$_REQUEST['even16id']='';
	$_REQUEST['even16idproceso']=$_REQUEST['even16idproceso_c'];
	$_REQUEST['even16porperiodo']=$_REQUEST['even16porperiodo_c'];
	$_REQUEST['even16porcurso']=$_REQUEST['even16porcurso_c'];
	$_REQUEST['even16porbloqueo']=$_REQUEST['even16porbloqueo_c'];
	$_REQUEST['even16idbloqueo']=$_REQUEST['even16idbloqueo_c'];
	$_REQUEST['even16encabezado']=$_REQUEST['even16encabezado_c'];
	$_REQUEST['even16idusuario']=$_REQUEST['even16idusuario_c'];
	$_REQUEST['even16idusuario_td']=$_REQUEST['even16idusuario_c_td'];
	$_REQUEST['even16idusuario_doc']=$_REQUEST['even16idusuario_c_doc'];
	$_REQUEST['even16fechainicio']=$_REQUEST['even16fechainicio_c'];
	$_REQUEST['even16fechafin']=$_REQUEST['even16fechafin_c'];
	$_REQUEST['even16publicada']='N';
	$_REQUEST['even16caracter']=$_REQUEST['even16caracter_c'];
	$_REQUEST['even16tamanomuestra']=$_REQUEST['even16tamanomuestra_c'];
	$_REQUEST['even16porrol']=$_REQUEST['even16porrol_c'];
	$_REQUEST['even16tienepropietario']=$_REQUEST['even16tienepropietario_c'];
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f1916_db_GuardarV2($_REQUEST, $objDB, $bDebug);
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
		}else{
		$bClonando=false;
		}
	}
if ($bClonando){
	//Se esta guardando un clon y no hubo error...
	//$idOrigenClon=0;
	$even16id=$_REQUEST['even16id'];
	//Grupo de preguntas.
	$even19consec=tabla_consecutivo('even19encuestagrupo', 'even19consec', 'even19idencuesta='.$even16id.'', $objDB);
	$even19id=tabla_consecutivo('even19encuestagrupo', 'even19id', '', $objDB);
	$sCampos1919='even19idencuesta, even19consec, even19id, even19nombre';
	$sValores1919='';
	$a19=array();
	$sSQL='SELECT * FROM even19encuestagrupo WHERE even19idencuesta='.$idOrigenClon.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$a19[$fila['even19id']]=$even19id;
		if ($sValores1919!=''){$sValores1919=$sValores1919.', ';}
		$sValores1919=$sValores1919.'('.$even16id.', '.$even19consec.', '.$even19id.', "'.$fila['even19nombre'].'")';
		$even19consec++;
		$even19id++;
		}
	if ($sValores1919!=''){
		$sSQL='INSERT INTO even19encuestagrupo('.$sCampos1919.') VALUES '.$sValores1919.'';
		$result=$objDB->ejecutasql($sSQL);
		}
	//Preguntas.
	$even18consec=tabla_consecutivo('even18encuestapregunta', 'even18consec', 'even18idencuesta='.$even16id.'', $objDB);
	$even18id=tabla_consecutivo('even18encuestapregunta', 'even18id', '', $objDB);
	$even29id=tabla_consecutivo('even29encpregresp', 'even29id', '', $objDB);
	$sCampos1918='even18idencuesta, even18consec, even18id, even18idgrupo, even18pregunta, even18tiporespuesta, even18opcional, even18concomentario, even18rpta0, even18rpta1, even18rpta2, even18rpta3, even18rpta4, even18rpta5, even18rpta6, even18rpta7, even18rpta8, even18rpta9, even18orden, even18divergente, even18rptatotal, even18idpregcondiciona, even18valorcondiciona';
	$sCampos1929='even29idpregunta, even29consec, even29id, even29etiqueta, even29detalle';
	$sValores1918='';
	$sSQL='SELECT * FROM even18encuestapregunta WHERE even18idencuesta='.$idOrigenClon.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$even29idpreguntaPadre=$fila['even18id'];
		$even18idgrupo=0;
		if (isset($a19[$fila['even18idgrupo']])!=0){$even18idgrupo=$a19[$fila['even18idgrupo']];}
		$sValores1918='('.$even16id.', '.$even18consec.', '.$even18id.', '.$even18idgrupo.', "'.$fila['even18pregunta'].'", '.$fila['even18tiporespuesta'].', "'.$fila['even18opcional'].'", "'.$fila['even18concomentario'].'", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '.$fila['even18orden'].', "'.$fila['even18divergente'].'", '.$fila['even18rptatotal'].', '.$fila['even18idpregcondiciona'].', '.$fila['even18valorcondiciona'].')';
		$sSQL='INSERT INTO even18encuestapregunta('.$sCampos1918.') VALUES '.$sValores1918.'';
		$result=$objDB->ejecutasql($sSQL);
		//Los item de la pregunta
		$even29consec=tabla_consecutivo('even29encpregresp', 'even29consec', 'even29idpregunta='.$even18id.'', $objDB);
		$sValores1929='';
		$sSQL='SELECT * FROM even29encpregresp WHERE even29idpregunta='.$even29idpreguntaPadre.'';
		$tabla29=$objDB->ejecutasql($sSQL);
		while($fila29=$objDB->sf($tabla29)){
			if ($sValores1929!=''){$sValores1929=$sValores1929.', ';}
			$sValores1929=$sValores1929.'('.$even18id.', '.$even29consec.', '.$even29id.', "'.$fila29['even29etiqueta'].'", "'.$fila29['even29detalle'].'")';
			$even29consec++;
			$even29id++;
			}
		if ($sValores1929!=''){
			$sSQL='INSERT INTO even29encpregresp('.$sCampos1929.') VALUES '.$sValores1929.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		//Finaliza los item de la pregunta.
		$even18consec++;
		$even18id++;
		}
	if ($sValores1918!=''){
		}
	//Periodos
	f1924_Clonar($even16id, $idOrigenClon, $objDB);
	//Cursos
	if (false){
		f1925_Clonar($even16id, $idOrigenClon, $objDB);
		}
	//Roles.
	f1932_Clonar($even16id, $idOrigenClon, $objDB);
	$sError='Se ha clonado la encuesta.';
	}
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=0;
	$bModFechaEncuestas=true;
	}
if ($bModFechaEncuestas){
	$iFecha=(fecha_agno()*10000)+(fecha_mes()*100)+fecha_dia();
	$iMinuto=(fecha_hora()*60)+fecha_minuto();
	$sSQL='UPDATE even00params SET even00encuestafecha='.$iFecha.', even00encuestaminuto='.$iMinuto.' WHERE even00id=1';
	$result=$objDB->ejecutasql($sSQL);
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['even16consec_nuevo']=numeros_validar($_REQUEST['even16consec_nuevo']);
	if ($_REQUEST['even16consec_nuevo']==''){$sError=$ERR['even16consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT even16id FROM even16encuesta WHERE even16consec='.$_REQUEST['even16consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['even16consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE even16encuesta SET even16consec='.$_REQUEST['even16consec_nuevo'].' WHERE even16id='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['even16consec'].' a '.$_REQUEST['even16consec_nuevo'].'';
		$_REQUEST['even16consec']=$_REQUEST['even16consec_nuevo'];
		$_REQUEST['even16consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['even16id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	$_REQUEST['even16consec']=numeros_validar($_REQUEST['even16consec']);
	$_REQUEST['even16id']=numeros_validar($_REQUEST['even16id']);
	if ($sError==''){
		$sSQL='SELECT even18idencuesta FROM even18encuestapregunta WHERE even18idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Preguntas creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT even19idencuesta FROM even19encuestagrupo WHERE even19idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Grupos de preguntas creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1916';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['even16id'].' LIMIT 0, 1';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sError=$filaor['mensaje'];
				if ($filaor['etiqueta']!=''){
					if (isset($ERR[$filaor['etiqueta']])!=0){$sError=$ERR[$filaor['etiqueta']];}
					}
				break;
				}
			}
		}
	if ($sError==''){
		//$sSQL='DELETE FROM even18encuestapregunta WHERE even18idencuesta='.$_REQUEST['even16id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM even19encuestagrupo WHERE even19idencuesta='.$_REQUEST['even16id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sIds='-99';
		$sSQL='SELECT even21id FROM even21encuestaaplica WHERE even21idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['even21id'];
			}
		$sSQL='DELETE FROM even22encuestarpta WHERE even22idaplica IN ('.$sIds.')';
		$tabla=$objDB->ejecutasql($sSQL);
		$sSQL='DELETE FROM even21encuestaaplica WHERE even21idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sSQL='DELETE FROM even24encuestaperaca WHERE even24idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sSQL='DELETE FROM even25encuestacurso WHERE even25idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sSQL='DELETE FROM even32encuestarol WHERE even32idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sSQL='DELETE FROM even40encuestaprop WHERE even40idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='even16id='.$_REQUEST['even16id'];
		//$sWhere='even16consec='.$_REQUEST['even16consec'].'';
		$sSQL='DELETE FROM even16encuesta WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($audita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $_REQUEST['even16id'], $sWhere, $objDB);}
			$_REQUEST['paso']=-1;
			$sError=$ETI['msg_itemeliminado'];
			$iTipoError=1;
			}
		}
	}
//Se cambia la pagina del clonar.
$bCambiaClonar=false;
if (($_REQUEST['paso']==31)){
	$_REQUEST['paso']=2;
	$iSector=2;
	$bCambiaClonar=true;
	}
if (($_REQUEST['paso']==50)){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	if (!seg_revisa_permiso(1925, 2, $objDB)){
		$sError=$ERR['2'];
		}
	if ($sError==''){
		list($sError, $iTipoError, $sDebugP)=f1916_ProcesarArchivo($_REQUEST, $_FILES, $objDB, true);
		$sError=$sError.'<br>'.$sDebugP;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['even16consec']='';
	$_REQUEST['even16consec_nuevo']='';
	$_REQUEST['even16id']='';
	$_REQUEST['even16idproceso']=0;
	$_REQUEST['even16porperiodo']='S';
	$_REQUEST['even16porcurso']='S';
	$_REQUEST['even16porbloqueo']='N';
	$_REQUEST['even16idbloqueo']='';
	$_REQUEST['even16encabezado']='';
	$_REQUEST['even16idusuario']=$_SESSION['unad_id_tercero'];
	$_REQUEST['even16idusuario_td']=$APP->tipo_doc;
	$_REQUEST['even16idusuario_doc']='';
	$_REQUEST['even16fechainicio']='';//fecha_hoy();
	$_REQUEST['even16fechafin']='';//fecha_hoy();
	$_REQUEST['even16publicada']='N';
	$_REQUEST['even16caracter']=7;
	$_REQUEST['even16tamanomuestra']='';
	$_REQUEST['even16porrol']='N';
	$_REQUEST['even16tienepropietario']='N';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['even18idencuesta']='';
	$_REQUEST['even18consec']='';
	$_REQUEST['even18id']='';
	$_REQUEST['even18idgrupo']='';
	$_REQUEST['even18pregunta']='';
	$_REQUEST['even18tiporespuesta']='';
	$_REQUEST['even18opcional']='N';
	$_REQUEST['even18concomentario']='N';
	$_REQUEST['even18rpta0']=0;
	$_REQUEST['even18rpta1']=0;
	$_REQUEST['even18rpta2']=0;
	$_REQUEST['even18rpta3']=0;
	$_REQUEST['even18rpta4']=0;
	$_REQUEST['even18rpta5']=0;
	$_REQUEST['even18rpta6']=0;
	$_REQUEST['even18rpta7']=0;
	$_REQUEST['even18rpta8']=0;
	$_REQUEST['even18rpta9']=0;
	$_REQUEST['even18orden']='';
	$_REQUEST['even18divergente']='N';
	$_REQUEST['even18rptatotal']=0;
	$_REQUEST['even18idpregcondiciona']=0;
	$_REQUEST['even18valorcondiciona']=0;
	$_REQUEST['even18url']='';
	$_REQUEST['even19idencuesta']='';
	$_REQUEST['even19consec']='';
	$_REQUEST['even19id']='';
	$_REQUEST['even19nombre']='';
	$_REQUEST['even21idencuesta']='';
	$_REQUEST['even21idtercero']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['even21idtercero_td']=$APP->tipo_doc;
	$_REQUEST['even21idtercero_doc']='';
	$_REQUEST['even21idperaca']='';
	$_REQUEST['even21idcurso']=0;
	$_REQUEST['even21idcurso_cod']='';
	$_REQUEST['even21idbloquedo']='';
	$_REQUEST['even21id']='';
	$_REQUEST['even21fechapresenta']='';//fecha_hoy();
	$_REQUEST['even21terminada']='';
	$_REQUEST['even24idencuesta']='';
	$_REQUEST['even24idperaca']='';
	$_REQUEST['even24id']='';
	$_REQUEST['even24fechainicial']='';//fecha_hoy();
	$_REQUEST['even24fechafinal']='';//fecha_hoy();
	$_REQUEST['even25idencuesta']='';
	$_REQUEST['even25idcurso']=0;
	$_REQUEST['even25idcurso_cod']='';
	$_REQUEST['even25id']='';
	$_REQUEST['even25activo']='S';
	$_REQUEST['even32idencuesta']='';
	$_REQUEST['even32idrol']='';
	$_REQUEST['even32id']='';
	$_REQUEST['even32activo']='S';
	$_REQUEST['even40idencuesta']='';
	$_REQUEST['even40idpropietario']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['even40idpropietario_td']=$APP->tipo_doc;
	$_REQUEST['even40idpropietario_doc']='';
	$_REQUEST['even40id']='';
	$_REQUEST['even40activo']='S';
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$seg_14=0;
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 14, $objDB)){$seg_14=1;}
	}
$objCombos=new clsHtmlCombos('n');
$html_even16porperiodo=html_sino('even16porperiodo', $_REQUEST['even16porperiodo'], false, '', '', 'ajusta_even16porperiodo()');
$html_even16porcurso=html_sino('even16porcurso', $_REQUEST['even16porcurso']);
$html_even16porbloqueo=html_sino('even16porbloqueo', $_REQUEST['even16porbloqueo'], false, '', '', 'ajusta_even16porbloqueo()');
list($even16idusuario_rs, $_REQUEST['even16idusuario'], $_REQUEST['even16idusuario_td'], $_REQUEST['even16idusuario_doc'])=html_tercero($_REQUEST['even16idusuario_td'], $_REQUEST['even16idusuario_doc'], $_REQUEST['even16idusuario'], 0, $objDB);
$html_even16caracter=html_combo('even16caracter', 'even27id', 'even27nombre', 'even27caracterencuesta', '', 'even27id', $_REQUEST['even16caracter'], $objDB, '', false, '{'.$ETI['msg_seleccione'].'}', '');
$objCombos->nuevo('even16porrol', $_REQUEST['even16porrol'], false);
$objCombos->sino();
$html_even16porrol=$objCombos->html('', $objDB);
$objCombos->nuevo('even16tienepropietario', $_REQUEST['even16tienepropietario'], false);
$objCombos->sino();
$html_even16tienepropietario=$objCombos->html('', $objDB);
$bEditable=true;
if ((int)$_REQUEST['paso']==0){
	if ($_REQUEST['even16publicada']=='S'){
		$bEditable=false;
		}else{
		}
	}else{
	if ((int)$_REQUEST['even18id']==0){
		$html_even18idgrupo=html_combo_even18idgrupo($objDB, $_REQUEST['even18idgrupo'], $_REQUEST['even16id']);
		}else{
		list($even18idgrupo_nombre, $sErrorDet)=tabla_campoxid('even19encuestagrupo','even19nombre','even19id',$_REQUEST['even18idgrupo'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_even18idgrupo=html_oculto('even18idgrupo', $_REQUEST['even18idgrupo'], $even18idgrupo_nombre);
		}
	$objCombos->nuevo('even18tiporespuesta', $_REQUEST['even18tiporespuesta'], true, '{'.$ETI['msg_seleccione'].'}');
	if ($seg_14==1){
		$objCombos->addItem(25, 'Redirige');
		}
	$sSQL='SELECT even20id AS id, even20nombre AS nombre FROM even20tiporespuesta ORDER BY even20id';
	$html_even18tiporespuesta=$objCombos->html($sSQL, $objDB);
	$html_even18opcional=html_sino('even18opcional', $_REQUEST['even18opcional']);
	$html_even18concomentario=html_sino('even18concomentario', $_REQUEST['even18concomentario']);
	$html_even18divergente=html_sino('even18divergente', $_REQUEST['even18divergente']);
	$html_even18idpregcondiciona=html_combo_even18idpregcondiciona($objDB, $_REQUEST['even18idpregcondiciona'], $_REQUEST['even16id']);
	$html_even18valorcondiciona=html_combo_even18valorcondiciona($objDB, $_REQUEST['even18valorcondiciona'], $_REQUEST['even18idpregcondiciona']);
	list($even21idtercero_rs, $_REQUEST['even21idtercero'], $_REQUEST['even21idtercero_td'], $_REQUEST['even21idtercero_doc'])=html_tercero($_REQUEST['even21idtercero_td'], $_REQUEST['even21idtercero_doc'], $_REQUEST['even21idtercero'], 0, $objDB);
	if ((int)$_REQUEST['even21id']==0){
		$html_even21idperaca=html_combo_even21idperaca($objDB, $_REQUEST['even21idperaca']);
		}else{
		list($even21idperaca_nombre, $sErrorDet)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id',$_REQUEST['even21idperaca'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_even21idperaca=html_oculto('even21idperaca', $_REQUEST['even21idperaca'], $even21idperaca_nombre);
		}
list($_REQUEST['even21idcurso'], $even21idcurso_nombre)=TraerBusqueda_db_even21idcurso($_REQUEST['even21idcurso_cod'], $objDB);
	if ((int)$_REQUEST['even21id']==0){
		$html_even21idbloquedo=html_combo_even21idbloquedo($objDB, $_REQUEST['even21idbloquedo']);
		}else{
		list($even21idbloquedo_nombre, $sErrorDet)=tabla_campoxid('unad63bloqueo','unad63titulo','unad63id',$_REQUEST['even21idbloquedo'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_even21idbloquedo=html_oculto('even21idbloquedo', $_REQUEST['even21idbloquedo'], $even21idbloquedo_nombre);
		}
	$html_even21terminada=html_sino('even21terminada', $_REQUEST['even21terminada']);
	$html_even25activo=$objCombos->html('', $objDB);
	$html_even32idrol=f1932_HTMLComboV2_even32idrol($objDB, $objCombos, $_REQUEST['even32idrol']);
	$objCombos->nuevo('even32activo', $_REQUEST['even32activo'], false);
	$objCombos->sino();
	$html_even32activo=$objCombos->html('', $objDB);
	list($even40idpropietario_rs, $_REQUEST['even40idpropietario'], $_REQUEST['even40idpropietario_td'], $_REQUEST['even40idpropietario_doc'])=html_tercero($_REQUEST['even40idpropietario_td'], $_REQUEST['even40idpropietario_doc'], $_REQUEST['even40idpropietario'], 0, $objDB);
	$objCombos->nuevo('even40activo', $_REQUEST['even40activo'], false);
	$objCombos->sino();
	$html_even40activo=$objCombos->html('', $objDB);
	}
$bConPeraca=true;
$bConCurso=true;
$bConBloqueo=true;
$bHayPeraca=false;
$bHayCurso=false;
switch($_REQUEST['even16idproceso']){
	case 0:
	if ($_REQUEST['paso']!=0){
		$et_even16porperiodo=$ETI['no'];
		if ($_REQUEST['even16porperiodo']=='S'){
			$et_even16porperiodo=$ETI['si'];
			$bHayPeraca=true;
			}
		$et_even16porcurso=$ETI['no'];
		if ($_REQUEST['even16porcurso']=='S'){
			$et_even16porcurso=$ETI['si'];
			$bHayCurso=true;
			}
		$et_even16porbloqueo=$ETI['si'];
		if ($_REQUEST['even16porbloqueo']!='S'){
			$et_even16porbloqueo=$ETI['no'];
			$bConBloqueo=false;
			}
		$html_even16porperiodo=html_oculto('even16porperiodo', $_REQUEST['even16porperiodo'], $et_even16porperiodo);
		$html_even16porcurso=html_oculto('even16porcurso', $_REQUEST['even16porcurso'], $et_even16porcurso);
		$html_even16porbloqueo=html_oculto('even16porbloqueo', $_REQUEST['even16porbloqueo'], $et_even16porbloqueo);
		}
	break;
	case 1701: //Alumnos de laboratorio
	case 1702: //Profesores de laboratorio
	case 1704: //Alumnos de Salidas de campo
	case 1705: //Profesores de Salidas de campo
	$_REQUEST['even16porperiodo']='S';
	$_REQUEST['even16porcurso']='S';
	$_REQUEST['even16porbloqueo']='N';
	$html_even16porperiodo=html_oculto('even16porperiodo', 'S', $ETI['si']);
	$html_even16porcurso=html_oculto('even16porcurso', 'S', $ETI['si']);
	$bConBloqueo=false;
	$html_even16porbloqueo=html_oculto('even16porbloqueo', 'N', $ETI['no']);
	break;
	}
if ($bEditable){
	$html_even16idproceso=html_combo('even16idproceso', 'even17id', 'even17nombre', 'even17proceso', '', 'even17nombre', $_REQUEST['even16idproceso'], $objDB, 'cambiapagina()', false, '{'.$ETI['msg_seleccione'].'}', '');
	if ($bConBloqueo){
		$html_even16idbloqueo=html_combo('even16idbloqueo', 'unad63id', 'CONCAT(unad63consec, " - ", unad63titulo)', 'unad63bloqueo', '', 'unad63vigente DESC, unad63titulo', $_REQUEST['even16idbloqueo'], $objDB, '', true, '{'.$ETI['msg_seleccione'].'}', '');
		}else{
		$html_even16idbloqueo=html_oculto('even16idbloqueo', '0', '{'.$ETI['msg_ninguno'].'}');
		}
	if ($_REQUEST['paso']!=0){
		if ((int)$_REQUEST['even24id']==0){
			$html_even24idperaca=html_combo_even24idperaca($objDB, $_REQUEST['even24idperaca']);
			}else{
			list($even24idperaca_nombre, $sErrorDet)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id',$_REQUEST['even24idperaca'],'{'.$ETI['msg_sindato'].'}', $objDB);
			$html_even24idperaca=html_oculto('even24idperaca', $_REQUEST['even24idperaca'], $even24idperaca_nombre);
			}
		list($_REQUEST['even25idcurso'], $even25idcurso_nombre)=TraerBusqueda_db_even25idcurso($_REQUEST['even25idcurso_cod'], $objDB);
		$html_even25activo=html_sino('even25activo', $_REQUEST['even25activo']);
		}
	}else{
	list($even16idproceso_nombre, $sErrorDet)=tabla_campoxid('even17proceso','even17nombre','even17id',$_REQUEST['even16idproceso'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_even16idproceso=html_oculto('even16idproceso', $_REQUEST['even16idproceso'], $even16idproceso_nombre);
	list($even16idbloqueo_nombre, $sErrorDet)=tabla_campoxid('unad63bloqueo','unad63titulo','unad63id',$_REQUEST['even16idbloqueo'],'{'.$ETI['msg_ninguno'].'}', $objDB);
	$html_even16idbloqueo=html_oculto('even16idbloqueo', $_REQUEST['even16idbloqueo'], $even16idbloqueo_nombre);
	}
//Datos para clonar
if ($_REQUEST['paso']!=0){
	if (!$bCambiaClonar){
		$_REQUEST['even16idproceso_c']=$_REQUEST['even16idproceso'];
		$_REQUEST['even16tienepropietario_c']=$_REQUEST['even16tienepropietario'];
		$_REQUEST['even16porperiodo_c']=$_REQUEST['even16porperiodo'];
		$_REQUEST['even16porcurso_c']=$_REQUEST['even16porcurso'];
		$_REQUEST['even16porrol_c']=$_REQUEST['even16porrol'];
		$_REQUEST['even16porbloqueo_c']=$_REQUEST['even16porbloqueo'];
		$_REQUEST['even16idbloqueo_c']=$_REQUEST['even16idbloqueo'];
		$_REQUEST['even16caracter_c']=$_REQUEST['even16caracter'];
		$_REQUEST['even16idusuario_c']=$_SESSION['unad_id_tercero'];
		$_REQUEST['even16idusuario_c_td']='CC';
		$_REQUEST['even16idusuario_c_doc']='';
		}
	$html_even16idproceso_c=html_combo('even16idproceso_c', 'even17id', 'even17nombre', 'even17proceso', '', 'even17nombre', $_REQUEST['even16idproceso_c'], $objDB, 'cambiapaginaclona()', false, '{'.$ETI['msg_seleccione'].'}', '');
	$objCombos->nuevo('even16tienepropietario_c', $_REQUEST['even16tienepropietario_c'], false);
	$objCombos->sino();
	$html_even16tienepropietario_c=$objCombos->html('', $objDB);
	$html_even16porperiodo_c=html_sino('even16porperiodo_c', $_REQUEST['even16porperiodo_c'], false, '', '', 'ajusta_even16porperiodo_c()');
	$html_even16porcurso_c=html_sino('even16porcurso_c', $_REQUEST['even16porcurso_c']);
	$objCombos->nuevo('even16porrol_c', $_REQUEST['even16porrol_c'], false);
	$objCombos->sino();
	$html_even16porrol_c=$objCombos->html('', $objDB);
	$html_even16porbloqueo_c=html_sino('even16porbloqueo_c', $_REQUEST['even16porbloqueo_c'], false, '', '', 'ajusta_even16porbloqueo_c()');
	$html_even16idbloqueo_c=html_combo('even16idbloqueo_c', 'unad63id', 'unad63titulo', 'unad63bloqueo', '', 'unad63titulo', $_REQUEST['even16idbloqueo_c'], $objDB, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	$html_even16caracter_c=html_combo('even16caracter_c', 'even27id', 'even27nombre', 'even27caracterencuesta', '', 'even27id', $_REQUEST['even16caracter_c'], $objDB, '', false, '{'.$ETI['msg_seleccione'].'}', '');
	list($even16idusuario_c_rs, $_REQUEST['even16idusuario_c'], $_REQUEST['even16idusuario_c_td'], $_REQUEST['even16idusuario_c_doc'])=html_tercero($_REQUEST['even16idusuario_c_td'], $_REQUEST['even16idusuario_c_doc'], $_REQUEST['even16idusuario_c'], 0, $objDB);
	}
//Alistar datos adicionales

$bPuedeAbrir=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['even16publicada']=='S'){
		//Definir las condiciones que permitirán abrir el registro.
		if (seg_revisa_permiso($iCodModulo, 17, $objDB)){$bPuedeAbrir=true;}
		}
	if ($bHayPeraca){
		$sIds='-99';
		$sSQL='SELECT even24idperaca FROM even24encuestaperaca WHERE even24idencuesta='.$_REQUEST['even16id'];
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['even24idperaca'];
			}
		$html_bperaca1921=html_combo('bperaca1921', 'exte02id', 'exte02nombre', 'exte02per_aca', 'exte02id IN ('.$sIds.')', 'exte02nombre', $_REQUEST['bperaca1921'], $objDB, 'paginarf1921()', true, '{'.$ETI['msg_todos'].'}', '');
		}
	if ($bHayCurso){
		$sIds='-99';
		$sSQL='SELECT even25idcurso FROM even25encuestacurso WHERE even25idencuesta='.$_REQUEST['even16id'];
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['even25idcurso'];
			}
		$html_bcodcurso1921=html_combo('bcodcurso1921', 'unad40id', 'CONCAT(unad40id, " ", unad40nombre)', 'unad40curso', 'unad40id IN ('.$sIds.')', 'unad40id', $_REQUEST['bcodcurso1921'], $objDB, 'paginarf1921()', true, '{'.$ETI['msg_todos'].'}', '');
		}
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$html_blistar=html_combo('blistar', 'even17id', 'even17nombre', 'even17proceso', '', 'even17nombre', $_REQUEST['blistar'], $objDB, 'paginarf1916()', true, '{'.$ETI['msg_todos'].'}', '');
/*
//$html_blistar1918=html_combo('blistar1918', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=1918 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['blistar1918'], $objDB, 'paginarf1918()', true, '{'.$ETI['msg_todos'].'}', '');
//$html_blistar1919=html_combo('blistar1919', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=1919 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['blistar1919'], $objDB, 'paginarf1919()', true, '{'.$ETI['msg_todos'].'}', '');
//$html_blistar1921=html_combo('blistar1921', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=1921 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['blistar1921'], $objDB, 'paginarf1921()', true, '{'.$ETI['msg_todos'].'}', '');
$objCombos->nuevo('blistar1932', $_REQUEST['blistar1932'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar1932=$objCombos->comboSistema(1932, 1, $objDB, 'paginarf1932()');
*/
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($seg_6==1){}
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=1916;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){$seg_5=1;}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_1916'];
$aParametros[101]=$_REQUEST['paginaf1916'];
$aParametros[102]=$_REQUEST['lppf1916'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['blistar'];
list($sTabla1916, $sDebugTabla)=f1916_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla1918='';
$sTabla1919='';
$sTabla1921='';
$sTabla1924='';
$sTabla1925='';
$sTabla1932='';
$sTabla1940='';
if ($_REQUEST['paso']!=0){
	//Preguntas
	$aParametros1918[0]=$_REQUEST['even16id'];
	$aParametros1918[101]=$_REQUEST['paginaf1918'];
	$aParametros1918[102]=$_REQUEST['lppf1918'];
	//$aParametros1918[103]=$_REQUEST['bnombre1918'];
	//$aParametros1918[104]=$_REQUEST['blistar1918'];
	list($sTabla1918, $sDebugTabla)=f1918_TablaDetalleV2($aParametros1918, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Grupos de preguntas
	$aParametros1919[0]=$_REQUEST['even16id'];
	$aParametros1919[101]=$_REQUEST['paginaf1919'];
	$aParametros1919[102]=$_REQUEST['lppf1919'];
	//$aParametros1919[103]=$_REQUEST['bnombre1919'];
	//$aParametros1919[104]=$_REQUEST['blistar1919'];
	list($sTabla1919, $sDebugTabla)=f1919_TablaDetalleV2($aParametros1919, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Encuestas aplicadas
	$aParametros1921[0]=$_REQUEST['even16id'];
	$aParametros1921[101]=$_REQUEST['paginaf1921'];
	$aParametros1921[102]=$_REQUEST['lppf1921'];
	$aParametros1921[103]=$_REQUEST['bdoc1921'];
	$aParametros1921[104]=$_REQUEST['bnombre1921'];
	$aParametros1921[105]=$_REQUEST['bperaca1921'];
	$aParametros1921[106]=$_REQUEST['bcodcurso1921'];
	list($sTabla1921, $sDebugTabla)=f1921_TablaDetalleV2($aParametros1921, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Periodos que aplican
	$aParametros1924[0]=$_REQUEST['even16id'];
	$aParametros1924[101]=$_REQUEST['paginaf1924'];
	$aParametros1924[102]=$_REQUEST['lppf1924'];
	//$aParametros1924[103]=$_REQUEST['bnombre1924'];
	//$aParametros1924[104]=$_REQUEST['blistar1924'];
	list($sTabla1924, $sDebugTabla)=f1924_TablaDetalleV2($aParametros1924, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Cursos que aplican
	$aParametros1925[0]=$_REQUEST['even16id'];
	$aParametros1925[101]=$_REQUEST['paginaf1925'];
	$aParametros1925[102]=$_REQUEST['lppf1925'];
	//$aParametros1925[103]=$_REQUEST['bnombre1925'];
	//$aParametros1925[104]=$_REQUEST['blistar1925'];
	list($sTabla1925, $sDebugTabla)=f1925_TablaDetalleV2($aParametros1925, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Roles que aplican
	$aParametros1932[0]=$_REQUEST['even16id'];
	$aParametros1932[101]=$_REQUEST['paginaf1932'];
	$aParametros1932[102]=$_REQUEST['lppf1932'];
	//$aParametros1932[103]=$_REQUEST['bnombre1932'];
	//$aParametros1932[104]=$_REQUEST['blistar1932'];
	list($sTabla1932, $sDebugTabla)=f1932_TablaDetalleV2($aParametros1932, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Propietarios
	$aParametros1940[0]=$_REQUEST['even16id'];
	$aParametros1940[101]=$_REQUEST['paginaf1940'];
	$aParametros1940[102]=$_REQUEST['lppf1940'];
	//$aParametros1940[103]=$_REQUEST['bnombre1940'];
	//$aParametros1940[104]=$_REQUEST['blistar1940'];
	list($sTabla1940, $sDebugTabla)=f1940_TablaDetalleV2($aParametros1940, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug);
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_1916']);
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
function guardaclonar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=32;
	window.document.frmedita.submit();
	}
function cambiapagina(){
	expandesector(98);
	window.document.frmedita.submit();
	}
function cambiapaginaclona(){
	window.document.frmedita.paso.value=31;
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
	document.getElementById('div_sector93').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	if (window.document.frmedita.even16publicada.value!='S'){
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
		if (illave==40){params[4]='revisaf1940';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		xajax_unad11_Mostrar_v2(params);
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='';
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
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_1916.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_1916.value;
		window.document.frmlista.nombrearchivo.value='Encuesta';
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
	if (window.document.frmedita.seg_6.value==1){
		asignarvariables();
		window.document.frmimpp.action='e1916.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p1916.php';
		window.document.frmimpp.submit();
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
	datos[1]=window.document.frmedita.even16consec.value;
	if ((datos[1]!='')){
		xajax_f1916_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.even16consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf1916(llave1){
	window.document.frmedita.even16id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf1916(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1916.value;
	params[102]=window.document.frmedita.lppf1916.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104]=window.document.frmedita.blistar.value;
	xajax_f1916_HtmlTabla(params);
	}
function enviacerrar(){
	if (confirm('Esta seguro de publicar la encuesta?\nUna vez publicada no se permite modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=16;
		window.document.frmedita.submit();
		}
	}
function enviaabrir(){
	if (confirm('Esta seguro de despublicar la encuesta?')){
		expandesector(98);
		window.document.frmedita.paso.value=17;
		window.document.frmedita.submit();
		}
	}
<?php
if ($_REQUEST['paso']==2){
?>
function f1916_cargamasiva(){
	extensiones_permitidas=new Array(".xls", ".xlsx");
	var sError='';
	var archivo=window.document.frmedita.archivodatos.value;
	if (sError==''){
		if (!archivo){
			sError = "No has seleccionado ning\u00fan archivo";
			}
		}
	if (sError==''){
		//recupero la extensión de este nombre de archivo
		extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
		//compruebo si la extensión está entre las permitidas
		permitida=false;
		for (var i=0; i<extensiones_permitidas.length; i++){
			if (extensiones_permitidas[i] == extension){
				permitida = true;
				break;
				}
			}
	if (!permitida) {
		sError="Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
		}else{
		window.document.frmedita.iscroll.value=window.pageYOffset;
		expandesector(98);
		window.document.frmedita.paso.value=50;
		window.document.frmedita.submit();
		return 1;
		}
	}
	//si estoy aqui es que no se ha podido submitir
	alert (sError);
	return 0;
	}
<?php
	}
?>
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
	document.getElementById("even16consec").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f1916_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='even16idusuario'){
		ter_traerxid('even16idusuario', sValor);
		}
	if (sCampo=='even21idtercero'){
		ter_traerxid('even21idtercero', sValor);
		}
	if (sCampo=='even21idcurso'){
		window.document.frmedita.even21idcurso_cod.value=sValor;
		cod_even21idcurso();
		}
	if (sCampo=='even25idcurso'){
		window.document.frmedita.even25idcurso_cod.value=sValor;
		cod_even25idcurso();
		}
	if (sCampo=='even40idpropietario'){
		ter_traerxid('even40idpropietario', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantener();}
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
function mod_consec(){
	if (confirm("Esta seguro de cambiar el consecutivo?")){
		expandesector(98);
		window.document.frmedita.paso.value=93;
		window.document.frmedita.submit();
		}
	}
function ajusta_even16porperiodo(){
	var sEst='block';
	if (window.document.frmedita.even16porperiodo.value=='S'){
		sEst='none';
		}
	document.getElementById('div_16fechas').style.display=sEst;
	}
function ajusta_even16porbloqueo(){
	var sEst1='block';
	var sEst='none';
	if (window.document.frmedita.even16porbloqueo.value=='S'){
		sEst1='none';
		sEst='block';
		window.document.frmedita.even16porperiodo.value='N';
		window.document.frmedita.even16porcurso.value='N';
		window.document.frmedita.even16porrol.value='N';
		}
	document.getElementById('div_even16porperiodo').style.display=sEst1;
	document.getElementById('div_even16porcurso').style.display=sEst1;
	document.getElementById('div_even16porrol').style.display=sEst1;
	document.getElementById('div_et_even16porperiodo').style.display=sEst;
	document.getElementById('div_et_even16porcurso').style.display=sEst;
	document.getElementById('div_et_even16porrol').style.display=sEst;
	document.getElementById('div_et_even16idbloqueo').style.display=sEst;
	document.getElementById('div_even16idbloqueo').style.display=sEst;
	}
function ajusta_even16porperiodo_c(){
	var sEst='block';
	if (window.document.frmedita.even16porperiodo_c.value=='S'){
		sEst='none';
		}
	document.getElementById('div_16fechas_c').style.display=sEst;
	}
function ajusta_even16porbloqueo_c(){
	var sEst1='block';
	var sEst='none';
	if (window.document.frmedita.even16porbloqueo_c.value=='S'){
		sEst1='none';
		sEst='block';
		window.document.frmedita.even16porperiodo_c.value='N';
		window.document.frmedita.even16porcurso_c.value='N';
		window.document.frmedita.even16porrol_c.value='N';
		}
	document.getElementById('div_even16porperiodo_c').style.display=sEst1;
	document.getElementById('div_even16porcurso_c').style.display=sEst1;
	document.getElementById('div_even16porrol_c').style.display=sEst1;
	document.getElementById('div_et_even16porperiodo_c').style.display=sEst;
	document.getElementById('div_et_even16porcurso_c').style.display=sEst;
	document.getElementById('div_et_even16porrol_c').style.display=sEst;
	document.getElementById('div_et_even16idbloqueo_c').style.display=sEst;
	document.getElementById('div_even16idbloqueo_c').style.display=sEst;
	}
function f1918adicionaropcion(){
	var params=new Array();
	params[1]=window.document.frmedita.even18id.value;
	xajax_f1918_AgregarOpcion(params);
	}
function f1918quitaropcion(id29){
	var params=new Array();
	params[1]=window.document.frmedita.even18id.value;
	params[2]=id29;
	xajax_f1918_QuitarOpcion(params);
	}
function f1929etiqueta(id29, valor){
	var params=new Array();
	params[1]=id29;
	params[2]=valor;
	xajax_f1929_GuardaEtiqueta(params);
	}
function f1929detalle(id29, valor){
	var params=new Array();
	params[1]=id29;
	params[2]=valor;
	xajax_f1929_GuardaDetalle(params);
	}
function abrirf1921(id21){
	var params=new Array();
	params[1]=id21;
	xajax_f1921_AbrirEncuesta(params);
	}
function liberarf1921(id21){
	var params=new Array();
	params[1]=id21;
	xajax_f1921_QuitarEncuesta(params);
	}
function liberarf1921pendientes(){
	var params=new Array();
	params[1]=window.document.frmedita.even16id.value;
	xajax_f1921_QuitarEncuestasPendientes(params);
	}
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="jsi/js1918.js?v=3"></script>
<script language="javascript" src="jsi/js1919.js"></script>
<script language="javascript" src="jsi/js1921.js"></script>
<script language="javascript" src="jsi/js1924.js"></script>
<script language="javascript" src="jsi/js1925.js"></script>
<script language="javascript" src="jsi/js1932.js"></script>
<script language="javascript" src="jsi/js1940.js"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p1916.php" target="_blank">
<input id="r" name="r" type="hidden" value="1916" />
<input id="id1916" name="id1916" type="hidden" value="<?php echo $_REQUEST['even16id']; ?>" />
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
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off" enctype="multipart/form-data">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['even16publicada']!='S'){
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
		if ($_REQUEST['even16publicada']=='S'){
			//$bHayImprimir=true;
			//$sScript='imprimep()';
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
if ($_REQUEST['even16publicada']!='S'){
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
	if ($_REQUEST['paso']>0){
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="<?php echo $ETI['msg_publicar']; ?>" value="<?php echo $ETI['msg_publicar']; ?>"/>
<?php
		}
	}else{
	if ($_REQUEST['paso']>0){
		if ($bPuedeAbrir){
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupNegar" onclick="enviaabrir();" value="Despublicar" title="Despublicar"/>
<?php
			}
		}
	}
if ($_REQUEST['paso']>0){
?>
<input id="cmdClonar" name="cmdClonar" type="button" class="btSupClonar" onclick="expandesector(2);" title="<?php echo $ETI['bt_clonar']; ?>" value="<?php echo $ETI['bt_clonar']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1916'].'</h2>';
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
<input id="boculta1916" name="boculta1916" type="hidden" value="<?php echo $_REQUEST['boculta1916']; ?>" />
<label class="Label30">
<input id="btexpande1916" name="btexpande1916" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1916,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1916']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1916" name="btrecoge1916" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1916,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1916']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1916" style="display:<?php if ($_REQUEST['boculta1916']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label90">
<?php
echo $ETI['even16consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="even16consec" name="even16consec" type="text" value="<?php echo $_REQUEST['even16consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('even16consec', $_REQUEST['even16consec']);
	}
?>
</label>
<label class="Label60">
<?php
echo $ETI['even16id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('even16id', $_REQUEST['even16id']);
?>
</label>
<label class="Label60">
<?php
echo $ETI['even16idproceso'];
?>
</label>
<label>
<?php
echo $html_even16idproceso;
?>
</label>
<label class="Label90">
<?php
echo $ETI['even16publicada'];
?>
</label>
<label class="Label60">
<?php
$et_even16publicada=$ETI['no'];
if ($_REQUEST['even16publicada']=='S'){$et_even16publicada=$ETI['si'];}
echo html_oculto('even16publicada', $_REQUEST['even16publicada'], $et_even16publicada);
?>
</label>
<label class="Label130">
<?php
echo $ETI['even16tienepropietario'];
?>
</label>
<label class="Label130">
<?php
echo $html_even16tienepropietario;
?>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_variables'];
$sEstiloDivPer='block';
$sEstiloDiv16='none';
if ($_REQUEST['even16porbloqueo']=='S'){
	$sEstiloDivPer='none';
	$sEstiloDiv16='block';
	}
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['even16porperiodo'];
?>
</label>
<label class="Label60">
<div id="div_even16porperiodo" style="display:<?php echo $sEstiloDivPer; ?>">
<?php
echo $html_even16porperiodo;
?>
</div>
<div id="div_et_even16porperiodo" style="display:<?php echo $sEstiloDiv16; ?>"><b>NO</b></div>
</label>
<label class="Label90">
<?php
echo $ETI['even16porcurso'];
?>
</label>
<label class="Label60">
<div id="div_even16porcurso" style="display:<?php echo $sEstiloDivPer; ?>">
<?php
echo $html_even16porcurso;
?>
</div>
<div id="div_et_even16porcurso" style="display:<?php echo $sEstiloDiv16; ?>"><b>NO</b></div>
</label>
<label class="Label130">
<?php
echo $ETI['even16porrol'];
?>
</label>
<label class="Label60">
<div id="div_even16porrol" style="display:<?php echo $sEstiloDivPer; ?>">
<?php
echo $html_even16porrol;
?>
</div>
<div id="div_et_even16porrol" style="display:<?php echo $sEstiloDiv16; ?>"><b>NO</b></div>
</label>
<label class="Label130">
<?php
echo $ETI['even16porbloqueo'];
?>
</label>
<label class="Label60">
<?php
echo $html_even16porbloqueo;
?>
</label>
<div class="salto1px"></div>
<label class="Label60">
<div id="div_et_even16idbloqueo" style="display:<?php echo $sEstiloDiv16; ?>">
<?php
echo $ETI['even16idbloqueo'];
?>
</div>
</label>
<label>
<div id="div_even16idbloqueo" style="display:<?php echo $sEstiloDiv16; ?>">
<?php
echo $html_even16idbloqueo;
?>
</div>
</label>
<div class="salto1px"></div>
<?php
$sEstiloDiv16='block';
if ($_REQUEST['even16porperiodo']=='S'){$sEstiloDiv16='none';}
?>
<div id="div_16fechas" style="display:<?php echo $sEstiloDiv16; ?>">
<label class="Label90">
<?php
echo $ETI['even16fechainicio'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even16fechainicio', $_REQUEST['even16fechainicio'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="beven16fechainicio_hoy" name="beven16fechainicio_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('even16fechainicio','<?php echo fecha_hoy(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label90">
<?php
echo $ETI['even16fechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even16fechafin', $_REQUEST['even16fechafin'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="beven16fechafin_hoy" name="beven16fechafin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('even16fechafin','<?php echo fecha_hoy(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['even16caracter'];
?>
</label>
<label class="Label130">
<?php
echo $html_even16caracter;
?>
</label>
<label class="Label160">
<?php
echo $ETI['even16tamanomuestra'];
?>
</label>
<label class="Label130">
<input id="even16tamanomuestra" name="even16tamanomuestra" type="text" value="<?php echo $_REQUEST['even16tamanomuestra']; ?>" class="diez" maxlength="10" placeholder="0"/>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="txtAreaS">
<?php
echo $ETI['even16encabezado'];
?>
<textarea id="even16encabezado" name="even16encabezado" placeholder="<?php echo $ETI['ing_campo'].$ETI['even16encabezado']; ?>"><?php echo $_REQUEST['even16encabezado']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even16idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="even16idusuario" name="even16idusuario" type="hidden" value="<?php echo $_REQUEST['even16idusuario']; ?>"/>
<div id="div_even16idusuario_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('even16idusuario', $_REQUEST['even16idusuario_td'], $_REQUEST['even16idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_even16idusuario" class="L"><?php echo $even16idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 1919 Grupos de preguntas
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1919'];
?>
</label>
<input id="boculta1919" name="boculta1919" type="hidden" value="<?php echo $_REQUEST['boculta1919']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['even16publicada']!='S'){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1919" name="btexcel1919" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1919();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1919" name="btexpande1919" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1919,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1919']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1919" name="btrecoge1919" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1919,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1919']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1919" style="display:<?php if ($_REQUEST['boculta1919']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['even19consec'];
?>
</label>
<label class="Label90"><div id="div_even19consec">
<?php
if ((int)$_REQUEST['even19id']==0){
?>
<input id="even19consec" name="even19consec" type="text" value="<?php echo $_REQUEST['even19consec']; ?>" onchange="revisaf1919()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('even19consec', $_REQUEST['even19consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['even19id'];
?>
</label>
<label class="Label60"><div id="div_even19id">
<?php
	echo html_oculto('even19id', $_REQUEST['even19id']);
?>
</div></label>
<label class="L">
<?php
echo $ETI['even19nombre'];
?>

<input id="even19nombre" name="even19nombre" type="text" value="<?php echo $_REQUEST['even19nombre']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even19nombre']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label30">
<input id="bguarda1919" name="bguarda1919" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1919()" title="<?php echo $ETI['bt_mini_guardar_1919']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1919" name="blimpia1919" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1919()" title="<?php echo $ETI['bt_mini_limpiar_1919']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1919" name="belimina1919" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1919()" title="<?php echo $ETI['bt_mini_eliminar_1919']; ?>" style="display:<?php if ((int)$_REQUEST['even19id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1919
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<!--
<div class="ir_derecha">
Nombre
<input id="bnombre1919" name="bnombre1919" type="text" value="<?php echo $_REQUEST['bnombre1919']; ?>" onchange="paginarf1919()"/>
Listar
<?php
//echo $html_blistar1919;
?>
<div class="salto1px"></div>
</div>
-->
<div id="div_f1919detalle">
<?php
echo $sTabla1919;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1919 Grupos de preguntas
?>
<?php
// -- Inicia Grupo campos 1918 Preguntas
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1918'];
?>
</label>
<input id="boculta1918" name="boculta1918" type="hidden" value="<?php echo $_REQUEST['boculta1918']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['even16publicada']!='S'){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1918" name="btexcel1918" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1918();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1918" name="btexpande1918" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1918,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1918']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1918" name="btrecoge1918" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1918,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1918']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1918" style="display:<?php if ($_REQUEST['boculta1918']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['even18consec'];
?>
</label>
<label class="Label90"><div id="div_even18consec">
<?php
if ((int)$_REQUEST['even18id']==0){
?>
<input id="even18consec" name="even18consec" type="text" value="<?php echo $_REQUEST['even18consec']; ?>" onchange="revisaf1918()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('even18consec', $_REQUEST['even18consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['even18id'];
?>
</label>
<label class="Label60"><div id="div_even18id">
<?php
	echo html_oculto('even18id', $_REQUEST['even18id']);
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['even18idgrupo'];
?>
</label>
<label>
<div id="div_even18idgrupo">
<?php
echo $html_even18idgrupo;
?>
</div>
</label>
<label class="txtAreaS">
<?php
echo $ETI['even18pregunta'];
?>
<textarea id="even18pregunta" name="even18pregunta" placeholder="<?php echo $ETI['ing_campo'].$ETI['even18pregunta']; ?>"><?php echo $_REQUEST['even18pregunta']; ?></textarea>
</label>
<label class="Label160">
<?php
echo $ETI['even18tiporespuesta'];
?>
</label>
<label>
<div id="div_even18tiporespuesta">
<?php
echo $html_even18tiporespuesta;
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['even18orden'];
?>
</label>
<label class="Label90">
<input id="even18orden" name="even18orden" type="text" value="<?php echo $_REQUEST['even18orden']; ?>" class="cuatro" maxlength="10" placeholder="1"/>
</label>
<label class="Label90">
<?php
echo $ETI['even18opcional'];
?>
</label>
<label class="Label60">
<?php
echo $html_even18opcional;
?>
</label>
<label class="Label130">
<?php
echo $ETI['even18concomentario'];
?>
</label>
<label class="Label60">
<?php
echo $html_even18concomentario;
?>
</label>
<label class="L">
<?php
echo $ETI['even18url'];
?>

<input id="even18url" name="even18url" type="text" value="<?php echo $_REQUEST['even18url']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even18url']; ?>"/>
</label>
<label class="Label90">
<?php
echo $ETI['even18divergente'];
?>
</label>
<label class="Label60">
<?php
echo $html_even18divergente;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_condicional'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['even18idpregcondiciona'];
?>
</label>
<label>
<div id="div_even18idpregcondiciona">
<?php
echo $html_even18idpregcondiciona;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['even18valorcondiciona'];
?>
</label>
<label>
<div id="div_even18valorcondiciona">
<?php
echo $html_even18valorcondiciona;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<input id="even18rptatotal" name="even18rptatotal" type="hidden" value="<?php echo $_REQUEST['even18rptatotal']; ?>" />
<input id="even18rpta0" name="even18rpta0" type="hidden" value="<?php echo $_REQUEST['even18rpta0']; ?>" />
<div class="salto1px"></div>
<div id="div_1918Respuestas">
<?php
echo f1918_GrupoRespuestas($_REQUEST['even18id'], $objDB);
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label30">
<input id="bguarda1918" name="bguarda1918" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1918()" title="<?php echo $ETI['bt_mini_guardar_1918']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1918" name="blimpia1918" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1918()" title="<?php echo $ETI['bt_mini_limpiar_1918']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1918" name="belimina1918" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1918()" title="<?php echo $ETI['bt_mini_eliminar_1918']; ?>" style="display:<?php if ((int)$_REQUEST['even18id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1918
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<!--
<div class="ir_derecha">
Nombre
<input id="bnombre1918" name="bnombre1918" type="text" value="<?php echo $_REQUEST['bnombre1918']; ?>" onchange="paginarf1918()"/>
Listar
<?php
//echo $html_blistar1918;
?>
<div class="salto1px"></div>
</div>
-->
<div id="div_f1918detalle">
<?php
echo $sTabla1918;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1918 Preguntas
?>
<?php
// -- Inicia Grupo campos 1921 Encuestas aplicadas
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1921'];
?>
</label>
<input id="boculta1921" name="boculta1921" type="hidden" value="<?php echo $_REQUEST['boculta1921']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if (false){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1921" name="btexcel1921" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1921();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1921" name="btexpande1921" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1921,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1921']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1921" name="btrecoge1921" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1921,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1921']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1921" style="display:<?php if ($_REQUEST['boculta1921']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even21idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="even21idtercero" name="even21idtercero" type="hidden" value="<?php echo $_REQUEST['even21idtercero']; ?>"/>
<div id="div_even21idtercero_llaves">
<?php
$bOculto=true;
if ((int)$_REQUEST['even21id']==0){$bOculto=false;}
echo html_DivTerceroV2('even21idtercero', $_REQUEST['even21idtercero_td'], $_REQUEST['even21idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_even21idtercero" class="L"><?php echo $even21idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['even21idperaca'];
?>
</label>
<label class="Label600">
<div id="div_even21idperaca">
<?php
echo $html_even21idperaca;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even21idcurso'];
?>
</label>
<div class="salto1px"></div>
<input id="even21idcurso" name="even21idcurso" type="hidden" value="<?php echo $_REQUEST['even21idcurso']; ?>"/>
<label class="Label200"><div id="div_even21idcurso_cod">
<?php
$sEstiloBoton='block';
if ((int)$_REQUEST['even21id']==0){
?>
<input id="even21idcurso_cod" name="even21idcurso_cod" type="text" value="<?php echo $_REQUEST['even21idcurso_cod']; ?>" class="veinte" onchange="cod_even21idcurso()" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['even21idcurso']; ?>"/>
<?php
	}else{
	$sEstiloBoton='none';
	echo html_oculto('even21idcurso_cod', $_REQUEST['even21idcurso_cod']);
	}
?>
</div></label>
<label class="Label30">
<input id="beven21idcurso" name="beven21idcurso" type="button" value="<?php echo $ETI['bt_buscar']; ?>" class="btMiniBuscar" onclick="buscarV2016('even21idcurso')" title="<?php echo $ETI['bt_buscar']; ?>" style="display:<?php echo $sEstiloBoton; ?>"/>
</label>
<div class="salto1px"></div>
<div id="div_even21idcurso" class="L"><?php echo $even21idcurso_nombre; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['even21idbloquedo'];
?>
</label>
<label>
<div id="div_even21idbloquedo">
<?php
echo $html_even21idbloquedo;
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['even21id'];
?>
</label>
<label class="Label60"><div id="div_even21id">
<?php
	echo html_oculto('even21id', $_REQUEST['even21id']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['even21fechapresenta'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even21fechapresenta', $_REQUEST['even21fechapresenta']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="beven21fechapresenta_hoy" name="beven21fechapresenta_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('even21fechapresenta','<?php echo fecha_hoy(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['even21terminada'];
?>
</label>
<label class="Label130">
<?php
echo $html_even21terminada;
?>
</label>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label30">
<input id="bguarda1921" name="bguarda1921" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1921()" title="<?php echo $ETI['bt_mini_guardar_1921']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1921" name="blimpia1921" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1921()" title="<?php echo $ETI['bt_mini_limpiar_1921']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1921" name="belimina1921" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1921()" title="<?php echo $ETI['bt_mini_eliminar_1921']; ?>" style="display:<?php if ((int)$_REQUEST['even21id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1921
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div class="ir_derecha">
Documento
<input id="bdoc1921" name="bdoc1921" type="text" value="<?php echo $_REQUEST['bdoc1921']; ?>" onchange="paginarf1921()"/>
Nombre
<input id="bnombre1921" name="bnombre1921" type="text" value="<?php echo $_REQUEST['bnombre1921']; ?>" onchange="paginarf1921()"/>
<?php
if ($bHayPeraca){
?>
<div class="salto1px"></div>
<?php
	echo 'Periodo '.$html_bperaca1921;
	}else{
	echo '<input id="bperaca1921" name="bperaca1921" type="hidden" value="'.$_REQUEST['bperaca1921'].'"/>';
	}
if ($bHayCurso){
?>
<div class="salto1px"></div>
<?php
	echo 'Curso '.$html_bcodcurso1921;
	}else{
	echo '<input id="bcodcurso1921" name="bcodcurso1921" type="hidden" value="'.$_REQUEST['bcodcurso1921'].'"/>';
	}
?>
<div class="salto1px"></div>
</div>
<div id="div_f1921detalle">
<?php
echo $sTabla1921;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1921 Encuestas aplicadas
?>
<?php
// -- Inicia Grupo campos 1924 Periodos que aplican
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1924'];
?>
</label>
<input id="boculta1924" name="boculta1924" type="hidden" value="<?php echo $_REQUEST['boculta1924']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['even16porperiodo']=='S'){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1924" name="btexcel1924" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1924();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1924" name="btexpande1924" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1924,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1924']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1924" name="btrecoge1924" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1924,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1924']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1924" style="display:<?php if ($_REQUEST['boculta1924']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['even24idperaca'];
?>
</label>
<label class="Label500">
<div id="div_even24idperaca">
<?php
echo $html_even24idperaca;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['even24fechainicial'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even24fechainicial', $_REQUEST['even24fechainicial']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<label class="Label130">
<?php
echo $ETI['even24fechafinal'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even24fechafinal', $_REQUEST['even24fechafinal'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<label class="Label60">
<?php
echo $ETI['even24id'];
?>
</label>
<label class="Label60"><div id="div_even24id">
<?php
	echo html_oculto('even24id', $_REQUEST['even24id']);
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label30">
<input id="bguarda1924" name="bguarda1924" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1924()" title="<?php echo $ETI['bt_mini_guardar_1924']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1924" name="blimpia1924" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1924()" title="<?php echo $ETI['bt_mini_limpiar_1924']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1924" name="belimina1924" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1924()" title="<?php echo $ETI['bt_mini_eliminar_1924']; ?>" style="display:<?php if ((int)$_REQUEST['even24id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1924
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<!--
<div class="ir_derecha">
Nombre
<input id="bnombre1924" name="bnombre1924" type="text" value="<?php echo $_REQUEST['bnombre1924']; ?>" onchange="paginarf1924()"/>
Listar
<?php
//echo $html_blistar1924;
?>
<div class="salto1px"></div>
</div>
-->
<div id="div_f1924detalle">
<?php
echo $sTabla1924;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1924 Periodos que aplican
?>
<?php
// -- Inicia Grupo campos 1925 Cursos que aplican
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1925'];
?>
</label>
<input id="boculta1925" name="boculta1925" type="hidden" value="<?php echo $_REQUEST['boculta1925']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['even16porcurso']=='S'){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1925" name="btexcel1925" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1925();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1925" name="btexpande1925" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1925,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1925']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1925" name="btrecoge1925" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1925,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1925']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1925" style="display:<?php if ($_REQUEST['boculta1925']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even25idcurso'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['even25id'];
?>
</label>
<label class="Label60"><div id="div_even25id">
<?php
	echo html_oculto('even25id', $_REQUEST['even25id']);
?>
</div></label>
<div class="salto1px"></div>
<input id="even25idcurso" name="even25idcurso" type="hidden" value="<?php echo $_REQUEST['even25idcurso']; ?>"/>
<label class="Label200"><div id="div_even25idcurso_cod">
<?php
$sEstiloBoton='block';
if ((int)$_REQUEST['even25id']==0){
?>
<input id="even25idcurso_cod" name="even25idcurso_cod" type="text" value="<?php echo $_REQUEST['even25idcurso_cod']; ?>" class="veinte" onchange="cod_even25idcurso()" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['even25idcurso']; ?>"/>
<?php
	}else{
	$sEstiloBoton='none';
	echo html_oculto('even25idcurso_cod', $_REQUEST['even25idcurso_cod']);
	}
?>
</div></label>
<label class="Label30">
<input id="beven25idcurso" name="beven25idcurso" type="button" value="Buscar" class="btMiniBuscar" onclick="buscarV2016('even25idcurso')" title="Buscar" style="display:<?php echo $sEstiloBoton; ?>"/>
</label>
<div class="salto1px"></div>
<div id="div_even25idcurso" class="L"><?php echo $even25idcurso_nombre; ?></div>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even25activo'];
?>
</label>
<label class="Label60">
<?php
echo $html_even25activo;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['msg_plano1925'];
?>
</label>
<div class="salto1px"></div>
<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="100000" />
<label class="Label500">
<input id="archivodatos" name="archivodatos" type="file" />
</label>
<label class="Label130">
<input id="cmdanexar" name="cmdanexar" type="button" class="btSoloAnexar" value="<?php echo $ETI['msg_subir']; ?>" onclick="f1916_cargamasiva()" title="<?php echo $ETI['msg_subir']; ?>"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_infoplano1925'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label30">
<input id="bguarda1925" name="bguarda1925" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1925()" title="<?php echo $ETI['bt_mini_guardar_1925']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1925" name="blimpia1925" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1925()" title="<?php echo $ETI['bt_mini_limpiar_1925']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1925" name="belimina1925" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1925()" title="<?php echo $ETI['bt_mini_eliminar_1925']; ?>" style="display:<?php if ((int)$_REQUEST['even25id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1925
?>
<div class="salto1px"></div>
</div>
<?php
		//Inicia la carga masiva.
?>
<?php
		//Termina la carga masiva
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<!--
<div class="ir_derecha">
Nombre
<input id="bnombre1925" name="bnombre1925" type="text" value="<?php echo $_REQUEST['bnombre1925']; ?>" onchange="paginarf1925()"/>
Listar
<?php
//echo $html_blistar1925;
?>
<div class="salto1px"></div>
</div>
-->
<div id="div_f1925detalle">
<?php
echo $sTabla1925;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1925 Cursos que aplican
?>
<?php
// -- Inicia Grupo campos 1932 Roles que aplican
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1932'];
?>
</label>
<input id="boculta1932" name="boculta1932" type="hidden" value="<?php echo $_REQUEST['boculta1932']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['even16porrol']=='S'){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1932" name="btexcel1932" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1932();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1932" name="btexpande1932" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1932,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1932']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1932" name="btrecoge1932" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1932,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1932']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1932" style="display:<?php if ($_REQUEST['boculta1932']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['even32idrol'];
?>
</label>
<label>
<div id="div_even32idrol">
<?php
echo $html_even32idrol;
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['even32id'];
?>
</label>
<label class="Label60"><div id="div_even32id">
<?php
	echo html_oculto('even32id', $_REQUEST['even32id']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['even32activo'];
?>
</label>
<label class="Label130">
<?php
echo $html_even32activo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label30">
<input id="bguarda1932" name="bguarda1932" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1932()" title="<?php echo $ETI['bt_mini_guardar_1932']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1932" name="blimpia1932" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1932()" title="<?php echo $ETI['bt_mini_limpiar_1932']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1932" name="belimina1932" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1932()" title="<?php echo $ETI['bt_mini_eliminar_1932']; ?>" style="display:<?php if ((int)$_REQUEST['even32id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1932
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f1932detalle">
<?php
echo $sTabla1932;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1932 Roles que aplican
?>
<?php
// -- Inicia Grupo campos 1940 Propietarios
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1940'];
?>
</label>
<input id="boculta1940" name="boculta1940" type="hidden" value="<?php echo $_REQUEST['boculta1940']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1940" name="btexcel1940" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1940();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1940" name="btexpande1940" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1940,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1940']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1940" name="btrecoge1940" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1940,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1940']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1940" style="display:<?php if ($_REQUEST['boculta1940']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even40idpropietario'];
?>
</label>
<div class="salto1px"></div>
<input id="even40idpropietario" name="even40idpropietario" type="hidden" value="<?php echo $_REQUEST['even40idpropietario']; ?>"/>
<div id="div_even40idpropietario_llaves">
<?php
$bOculto=true;
if ((int)$_REQUEST['even40id']==0){$bOculto=false;}
echo html_DivTerceroV2('even40idpropietario', $_REQUEST['even40idpropietario_td'], $_REQUEST['even40idpropietario_doc'], $bOculto, 40, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_even40idpropietario" class="L"><?php echo $even40idpropietario_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<div class="salto5px"></div>
<label class="Label60">
<?php
echo $ETI['even40id'];
?>
</label>
<label class="Label60"><div id="div_even40id">
<?php
	echo html_oculto('even40id', $_REQUEST['even40id']);
?>
</div></label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['even40activo'];
?>
</label>
<label class="Label130">
<?php
echo $html_even40activo;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label30">
<input id="bguarda1940" name="bguarda1940" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1940()" title="<?php echo $ETI['bt_mini_guardar_1940']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1940" name="blimpia1940" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1940()" title="<?php echo $ETI['bt_mini_limpiar_1940']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1940" name="belimina1940" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1940()" title="<?php echo $ETI['bt_mini_eliminar_1940']; ?>" style="display:<?php if ((int)$_REQUEST['even40id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1940
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f1940detalle">
<?php
echo $sTabla1940;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1940 Propietarios
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p1916
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
Encabezado
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf1916()" autocomplete="off"/>
</label>
<label class="Label60">
Listar
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
<div id="div_f1916detalle">
<?php
echo $sTabla1916;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdGuardarc" name="cmdGuardarc" type="button" class="btUpGuardar" onclick="guardaclonar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
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

<?php
if ($_REQUEST['paso']!=0){
?>
<label class="Label90">
<?php
echo $ETI['even16consec'];
?>
</label>
<label class="Label90">
<input id="even16consec_c" name="even16consec_c" type="text" value="" class="cuatro"/>
</label>
<label class="Label60">
<?php
echo $ETI['even16idproceso'];
?>
</label>
<label>
<?php
echo $html_even16idproceso_c;
?>
</label>
<label class="Label60">
<?php
echo $ETI['even16publicada'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('even16publicada_c', 'N', $ETI['no']);
?>
</label>
<label class="Label130">
<?php
echo $ETI['even16tienepropietario'];
?>
</label>
<label class="Label130">
<?php
echo $html_even16tienepropietario_c;
?>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_variables'];
$sEstiloDivPer='block';
$sEstiloDiv16='none';
if ($_REQUEST['even16porbloqueo']=='S'){
	$sEstiloDivPer='none';
	$sEstiloDiv16='block';
	}
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even16porperiodo'];
?>
</label>
<label class="Label60">
<div id="div_even16porperiodo_c" style="display:<?php echo $sEstiloDivPer; ?>">
<?php
echo $html_even16porperiodo_c;
?>
</div>
<div id="div_et_even16porperiodo_c" style="display:<?php echo $sEstiloDiv16; ?>"><b>NO</b></div>
</label>
<label class="Label90">
<?php
echo $ETI['even16porcurso'];
?>
</label>
<label class="Label60">
<div id="div_even16porcurso_c" style="display:<?php echo $sEstiloDivPer; ?>">
<?php
echo $html_even16porcurso_c;
?>
</div>
<div id="div_et_even16porcurso_c" style="display:<?php echo $sEstiloDiv16; ?>"><b>NO</b></div>
</label>
<label class="Label90">
<?php
echo $ETI['even16porrol'];
?>
</label>
<label class="Label60">
<div id="div_even16porrol_c" style="display:<?php echo $sEstiloDivPer; ?>">
<?php
echo $html_even16porrol_c;
?>
</div>
<div id="div_et_even16porrol_c" style="display:<?php echo $sEstiloDiv16; ?>"><b>NO</b></div>
</label>
<label class="Label90">
<?php
echo $ETI['even16porbloqueo'];
?>
</label>
<label class="Label60">
<?php
echo $html_even16porbloqueo_c;
?>
</label>
<label class="Label60">
<div id="div_et_even16idbloqueo_c" style="display:<?php echo $sEstiloDiv16; ?>">
<?php
echo $ETI['even16idbloqueo'];
?>
</div>
</label>
<label>
<div id="div_even16idbloqueo_c" style="display:<?php echo $sEstiloDiv16; ?>">
<?php
echo $html_even16idbloqueo_c;
?>
</div>
</label>
<div class="salto1px"></div>
<?php
$sEstiloDiv16='block';
if ($_REQUEST['even16porperiodo']=='S'){$sEstiloDiv16='none';}
?>
<div id="div_16fechas_c" style="display:<?php echo $sEstiloDiv16; ?>">
<label class="Label90">
<?php
echo $ETI['even16fechainicio'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even16fechainicio_c', $_REQUEST['even16fechainicio'], true);
?>
</div>
<label class="Label90">
<?php
echo $ETI['even16fechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even16fechafin_c', $_REQUEST['even16fechafin'], true);
?>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even16caracter'];
?>
</label>
<label class="Label130">
<?php
echo $html_even16caracter_c;
?>
</label>
<label class="Label130">
<?php
echo $ETI['even16tamanomuestra'];
?>
</label>
<label class="Label130">
<input id="even16tamanomuestra_c" name="even16tamanomuestra_c" type="text" value="<?php echo $_REQUEST['even16tamanomuestra']; ?>" class="diez" maxlength="10" placeholder="0"/>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="txtAreaS">
<?php
echo $ETI['even16encabezado'];
?>
<textarea id="even16encabezado_c" name="even16encabezado_c" placeholder="<?php echo $ETI['ing_campo'].$ETI['even16encabezado']; ?>"><?php echo $_REQUEST['even16encabezado']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even16idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="even16idusuario_c" name="even16idusuario_c" type="hidden" value="<?php echo $_REQUEST['even16idusuario_c']; ?>"/>
<div id="div_even16idusuario_c_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('even16idusuario_c', $_REQUEST['even16idusuario_c_td'], $_REQUEST['even16idusuario_c_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_even16idusuario_c" class="L"><?php echo $even16idusuario_c_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
	}
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
echo $ETI['msg_even16consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['even16consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_even16consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="even16consec_nuevo" name="even16consec_nuevo" type="text" value="<?php echo $_REQUEST['even16consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_1916" name="titulo_1916" type="hidden" value="<?php echo $ETI['titulo_1916']; ?>" />
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
echo '<h2>'.$ETI['titulo_1916'].'</h2>';
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
echo '<h2>'.$ETI['titulo_1916'].'</h2>';
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
if ($_REQUEST['even16publicada']!='S'){
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_1916.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>