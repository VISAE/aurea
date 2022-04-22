<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 viernes, 22 de junio de 2018
--- Modelo Versión 2.22.0 martes, 26 de junio de 2018
--- Modelo Versión 2.22.1 martes, 3 de julio de 2018
--- Modelo Versión 2.22.2 martes, 17 de julio de 2018
--- Modelo Versión 2.25.0 viernes, 3 de abril de 2020
--- Modelo Versión 2.25.5 domingo, 16 de agosto de 2020
*/
/** Archivo caracterizacion.php.
* Modulo 2301 cara01encuesta.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date viernes, 3 de abril de 2020
* Cambios 12 de junio de 2020
* 1. interpretación a valores cualitativos de los niveles y riesgo de cada una de las fichas evaluadas.
* 2. Visualización de los resultados de la encuesta en el momento en que el estudiante finaliza y da cierre.
* Omar Augusto Bautista Mora - UNAD - 2020
* omar.bautista@unad.edu.co
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$bVerIntro=false;
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
if (isset($_REQUEST['intro'])!=0){$bVerIntro=true;}
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
$iCodModulo=2301;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2301=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2301)){$mensajes_2301=$APP->rutacomun.'lg/lg_2301_es.php';}
$mensajes_2310='lg/lg_2310_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2310)){$mensajes_2310='lg/lg_2310_es.php';}
require $mensajes_todas;
require $mensajes_2301;
require $mensajes_2310;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$sAnchoExpandeContrae=' style="width:62px;"';
//$iPiel=$APP->piel;
$iPiel=1;
if ($bDebug){
	$sDebug=$sDebug.''.fecha_microtiempo().' Probando conexi&oacute;n con la base de datos <b>'.$APP->dbname.'</b> en <b>'.$APP->dbhost.'</b><br>';
	}
if (!$objDB->Conectar()){
	$bCerrado=true;
	if ($bDebug){
		$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objDB->serror.'</b><br>';
		}
	}
$bEstudiante=true;
$bAntiguo=false;
if ($APP->idsistema==23){$bEstudiante=false;}
if (!$bEstudiante){
	list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1, $_SESSION['unad_id_tercero'], $objDB);
	if (!$bDevuelve){
		header('Location:nopermiso.php');
		die();
		}
	}else{
	if ($_SESSION['unad_id_tercero']==0){
		header('Location:nopermiso.php');
		die();
		}
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=caracterizacion.php');
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
$mensajes_2310='lg/lg_2310_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2310)){$mensajes_2310='lg/lg_2310_es.php';}
require $mensajes_2310;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	if ($bDebug){
		$sDebug=$sDebug.fecha_microtiempo().' No se recibe la variable marcador<br>';
		if (isset($_POST['paso'])!=0){
			$sDebug=$sDebug.fecha_microtiempo().' La variable marcador viene por post '.$_POST['paso'].'<br>';
			}
		if (isset($_GET['r'])!=0){
			$sDebug=$sDebug.fecha_microtiempo().' La variable marcador de registro esta vigente '.$_GET['r'].'<br>';
			}
		}
	$_REQUEST['paso']=-1;
	if ($bEstudiante){
		$_REQUEST['paso']=1;
		}
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2301 cara01encuesta
require $APP->rutacomun.'lib2301.php';
// -- 2310 Preguntas de la prueba
require 'lib2310.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'f2301_Combocara01depto');
$xajax->register(XAJAX_FUNCTION,'f2301_Combocara01ciudad');
$xajax->register(XAJAX_FUNCTION,'f2301_Combocara01idcead');
$xajax->register(XAJAX_FUNCTION,'f2301_Busqueda_cara01idconsejero');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f2301_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2301_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2301_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f2301_HtmlBusqueda');
if ($bEstudiante){
	$xajax->register(XAJAX_FUNCTION,'f2310_GuardarRespuesta');
	}else{
	$xajax->register(XAJAX_FUNCTION,'f2301_MarcarConsejero');
	$xajax->register(XAJAX_FUNCTION,'f2301_Combobprograma');
	$xajax->register(XAJAX_FUNCTION,'f2301_Combobcead');
	}
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$sUrlTablero='tablero.php';
$sMensaje='';
if (isset($APP->urltablero)!=0){
	if (file_exists($APP->urltablero)){$sUrlTablero=$APP->urltablero;}
	}
if ($bEstudiante){
	if ($_REQUEST['paso']==21){
		$_REQUEST['paso']=1;
		$idPeraca=0;
		$bForzarNueva=false;
		if (isset($_REQUEST['antiguo'])!=0){
			$idPeraca=$_REQUEST['idPeraca'];
			$bForzarNueva=$_REQUEST['bForzarNueva'];
			$bAntiguo=true;
			}
		list($sError, $sDebugE)=f2301_IniciarEncuesta($idTercero, $idPeraca, $objDB, $bDebug, $bForzarNueva);
		$sDebug=$sDebug.$sDebugE;
		}
	//Verificar que tenga una caracterizacion
	if (isset($_REQUEST['cara01idperaca'])==0){
		$_REQUEST['cara01idperaca']='';
		$sSQL='SELECT core01peracainicial FROM core01estprograma WHERE core01idtercero='.$idTercero.' ORDER BY core01peracainicial DESC';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de verificacion '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$_REQUEST['cara01idperaca']=$fila['core01peracainicial'];
			//Ver si ya tiene una caracterizacion.
			$sSQL='SELECT cara01id, cara01idperaca, cara01tipocaracterizacion, cara01fechaencuesta FROM cara01encuesta WHERE cara01idtercero='.$idTercero.' ORDER BY cara01idperaca DESC';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$sMensaje=$ETI['msg_intro_nuevos'];
				if ($_REQUEST['cara01idperaca']==87){
					$sMensaje=$ETI['msg_intro_antiguos'];
					}
				$objDB->CerrarConexion();
				$bVerIntro=true;
				}else{
				$fila=$objDB->sf($tabla);
				if (isset($_REQUEST['antiguo'])==0){
					$sSQL='SELECT core16peraca FROM core16actamatricula WHERE core16tercero='.$idTercero.' ORDER BY core16id DESC';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de verificacion '.$sSQL.'<br>';}
					$tabla=$objDB->ejecutasql($sSQL);					
					if ($objDB->nf($tabla)>0){
						$fila1=$objDB->sf($tabla);
						$idPeraca=$fila1['core16peraca'];						
						}
					// list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['cara01fechaencuesta'], true);
					// $iAgnoActual=fecha_agno();
					// if($iAgno!=$iAgnoActual){ // cambiar para periodicidad anual
					if ($idPeraca!=$fila['cara01idperaca']){
						$sMensaje=$ETI['msg_intro_antiguos'];
						$_REQUEST['antiguo']='';
						$bForzarNueva=true;
						$bVerIntro=true;
						}
					if ($fila['cara01tipocaracterizacion']==3){$bAntiguo=true;}
					}
				$_REQUEST['cara01idperaca']=$fila['cara01idperaca'];
				}
			}else{
			// No tiene peraca... asi que bueno... todo para aca.
			$et_menu='<li><a href="accesit.php">ACCESIT</a></li><li><a href="'.$sUrlTablero.'">Mis Cursos Virtuales</a></li><li><a href="salir.php">Salir</a></li>';
			$objDB->CerrarConexion();
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2301']);
echo $et_menu;
forma_mitad();
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<script language="javascript">
function irtablero(){
	window.document.frmtablero.submit();
	}
</script>
<?php
?>
<form id="frmtablero" name="frmtablero" method="post" action="<?php echo $sUrlTablero; ?>">
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<div class="GrupoCampos">
<div class="MarquesinaGrande">
Usted no esta considerado para adelantar el proceso de caracterizaci&oacute;n, gracias por su tiempo.
</div>
<div class="salto1px"></div>
<label class="Label300">&nbsp;</label>
<label class="Label130">
<input id="cmdTablero" name="cmdTablero" type="button" value="Mis Cursos" class="BotonAzul" onclick="javascript:irtablero()" />
</label><div class="salto1px"></div>
</div>
</form>
<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
</div>
<?php
			forma_piedepagina();
			die();
			}
		}else{
			$sSQL='SELECT core16peraca FROM core16actamatricula WHERE core16tercero='.$idTercero.' ORDER BY core16id DESC';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de verificacion '.$sSQL.'<br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			if($objDB->nf($tabla)>1){
				$_REQUEST['antiguo']='';
				$bAntiguo=true;
				}
		}
	}else{
	}
if ($bVerIntro){
	$et_menu='<li><a href="accesit.php">ACCESIT</a></li><li><a href="'.$sUrlTablero.'">Mis Cursos Virtuales</a></li><li><a href="salir.php">Salir</a></li>';
	if ($sMensaje==''){
		$sMensaje=$ETI['msg_intro_nuevos'];
		}
	require $APP->rutacomun.'unad_forma_v2.php';
	forma_cabeceraV3($xajax, $ETI['titulo_2301']);
	echo $et_menu;
	forma_mitad();
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<?php
if (isset($_REQUEST['antiguo'])!=0){
?>
<input id="antiguo" name="antiguo" type="hidden" value=""/>
<input id="idPeraca" name="idPeraca" type="hidden" value="<?php echo $idPeraca; ?>"/>
<input id="bForzarNueva" name="bForzarNueva" type="hidden" value="<?php echo $bForzarNueva; ?>"/>
<?php
}
?>
<input id="paso" name="paso" type="hidden" value="21"/>
<div class="GrupoCampos">
<?php
echo $sMensaje;
?>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label130">
<input id="cmdIniciar" name="cmdIniciar" type="submit" value="Iniciar" class="BotonAzul" />
</label>
<div class="salto1px"></div>
</div>
</form>
<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
</div>
<?php
	forma_piedepagina();
	die();
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
if (isset($_REQUEST['paginaf2301'])==0){$_REQUEST['paginaf2301']=1;}
if (isset($_REQUEST['lppf2301'])==0){$_REQUEST['lppf2301']=20;}
if (isset($_REQUEST['boculta2301'])==0){
	if ($bEstudiante){
		$_REQUEST['boculta2301']=0;
		}else{
		$_REQUEST['boculta2301']=1;
		}
	}
if (isset($_REQUEST['paginaf2310'])==0){$_REQUEST['paginaf2310']=1;}
if (isset($_REQUEST['lppf2310'])==0){$_REQUEST['lppf2310']=20;}
if (isset($_REQUEST['boculta101'])==0){$_REQUEST['boculta101']=0;}
if (isset($_REQUEST['boculta102'])==0){$_REQUEST['boculta102']=0;}
if (isset($_REQUEST['boculta103'])==0){$_REQUEST['boculta103']=0;}
if (isset($_REQUEST['boculta104'])==0){$_REQUEST['boculta104']=0;}
if (isset($_REQUEST['boculta105'])==0){$_REQUEST['boculta105']=0;}
if (isset($_REQUEST['boculta106'])==0){$_REQUEST['boculta106']=0;}
if (isset($_REQUEST['boculta107'])==0){$_REQUEST['boculta107']=0;}
if (isset($_REQUEST['boculta108'])==0){$_REQUEST['boculta108']=0;}
if (isset($_REQUEST['boculta109'])==0){$_REQUEST['boculta109']=0;}
if (isset($_REQUEST['boculta110'])==0){$_REQUEST['boculta110']=0;}
if (isset($_REQUEST['boculta111'])==0){$_REQUEST['boculta111']=0;}
if (isset($_REQUEST['boculta112'])==0){$_REQUEST['boculta112']=0;}
if (isset($_REQUEST['boculta113'])==0){$_REQUEST['boculta113']=0;}
if (isset($_REQUEST['bocultaResultados'])==0){$_REQUEST['bocultaResultados']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara01idperaca'])==0){$_REQUEST['cara01idperaca']='';}
if (isset($_REQUEST['cara01idtercero'])==0){
	if ($bEstudiante){
		$_REQUEST['cara01idtercero']=$idTercero;
		}else{
		$_REQUEST['cara01idtercero']=0;
		}
	}
if (isset($_REQUEST['cara01idtercero_td'])==0){$_REQUEST['cara01idtercero_td']=$APP->tipo_doc;}
if (isset($_REQUEST['cara01idtercero_doc'])==0){$_REQUEST['cara01idtercero_doc']='';}
if (isset($_REQUEST['cara01id'])==0){$_REQUEST['cara01id']='';}
if (isset($_REQUEST['cara01completa'])==0){$_REQUEST['cara01completa']='N';}
if (isset($_REQUEST['cara01fichaper'])==0){$_REQUEST['cara01fichaper']=0;}
if (isset($_REQUEST['cara01fichafam'])==0){$_REQUEST['cara01fichafam']=0;}
if (isset($_REQUEST['cara01fichaaca'])==0){$_REQUEST['cara01fichaaca']=0;}
if (isset($_REQUEST['cara01fichalab'])==0){$_REQUEST['cara01fichalab']=0;}
if (isset($_REQUEST['cara01fichabien'])==0){$_REQUEST['cara01fichabien']=0;}
if (isset($_REQUEST['cara01fichapsico'])==0){$_REQUEST['cara01fichapsico']=0;}
if (isset($_REQUEST['cara01fichadigital'])==0){$_REQUEST['cara01fichadigital']=0;}
if (isset($_REQUEST['cara01fichalectura'])==0){$_REQUEST['cara01fichalectura']=0;}
if (isset($_REQUEST['cara01ficharazona'])==0){$_REQUEST['cara01ficharazona']='';}
if (isset($_REQUEST['cara01fichaingles'])==0){$_REQUEST['cara01fichaingles']='';}
if (isset($_REQUEST['cara01fechaencuesta'])==0){$_REQUEST['cara01fechaencuesta']='';}//{fecha_hoy();}
if (isset($_REQUEST['cara01agnos'])==0){$_REQUEST['cara01agnos']=0;}
if (isset($_REQUEST['cara01sexo'])==0){$_REQUEST['cara01sexo']='';}
if (isset($_REQUEST['cara01pais'])==0){$_REQUEST['cara01pais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['cara01depto'])==0){$_REQUEST['cara01depto']='';}
if (isset($_REQUEST['cara01ciudad'])==0){$_REQUEST['cara01ciudad']='';}
if (isset($_REQUEST['cara01nomciudad'])==0){$_REQUEST['cara01nomciudad']='';}
if (isset($_REQUEST['cara01direccion'])==0){$_REQUEST['cara01direccion']='';}
if (isset($_REQUEST['cara01estrato'])==0){$_REQUEST['cara01estrato']='';}
if (isset($_REQUEST['cara01zonares'])==0){$_REQUEST['cara01zonares']='';}
if (isset($_REQUEST['cara01estcivil'])==0){$_REQUEST['cara01estcivil']='';}
if (isset($_REQUEST['cara01nomcontacto'])==0){$_REQUEST['cara01nomcontacto']='';}
if (isset($_REQUEST['cara01parentezcocontacto'])==0){$_REQUEST['cara01parentezcocontacto']='';}
if (isset($_REQUEST['cara01celcontacto'])==0){$_REQUEST['cara01celcontacto']='';}
if (isset($_REQUEST['cara01correocontacto'])==0){$_REQUEST['cara01correocontacto']='';}
if (isset($_REQUEST['cara01idzona'])==0){$_REQUEST['cara01idzona']='';}
if (isset($_REQUEST['cara01idcead'])==0){$_REQUEST['cara01idcead']='';}
if (isset($_REQUEST['cara01matconvenio'])==0){$_REQUEST['cara01matconvenio']='N';}
if (isset($_REQUEST['cara01raizal'])==0){$_REQUEST['cara01raizal']='';}
if (isset($_REQUEST['cara01palenquero'])==0){$_REQUEST['cara01palenquero']='';}
if (isset($_REQUEST['cara01afrocolombiano'])==0){$_REQUEST['cara01afrocolombiano']='';}
if (isset($_REQUEST['cara01otracomunnegras'])==0){$_REQUEST['cara01otracomunnegras']='';}
if (isset($_REQUEST['cara01rom'])==0){$_REQUEST['cara01rom']='';}
if (isset($_REQUEST['cara01indigenas'])==0){$_REQUEST['cara01indigenas']='';}
if (isset($_REQUEST['cara01victimadesplazado'])==0){$_REQUEST['cara01victimadesplazado']='';}
if (isset($_REQUEST['cara01idconfirmadesp'])==0){$_REQUEST['cara01idconfirmadesp']=$idTercero;}
if (isset($_REQUEST['cara01idconfirmadesp_td'])==0){$_REQUEST['cara01idconfirmadesp_td']=$APP->tipo_doc;}
if (isset($_REQUEST['cara01idconfirmadesp_doc'])==0){$_REQUEST['cara01idconfirmadesp_doc']='';}
if (isset($_REQUEST['cara01fechaconfirmadesp'])==0){$_REQUEST['cara01fechaconfirmadesp']='';}//{fecha_hoy();}
if (isset($_REQUEST['cara01victimaacr'])==0){$_REQUEST['cara01victimaacr']='';}
if (isset($_REQUEST['cara01idconfirmacr'])==0){$_REQUEST['cara01idconfirmacr']=$idTercero;}
if (isset($_REQUEST['cara01idconfirmacr_td'])==0){$_REQUEST['cara01idconfirmacr_td']=$APP->tipo_doc;}
if (isset($_REQUEST['cara01idconfirmacr_doc'])==0){$_REQUEST['cara01idconfirmacr_doc']='';}
if (isset($_REQUEST['cara01fechaconfirmacr'])==0){$_REQUEST['cara01fechaconfirmacr']='';}//{fecha_hoy();}
if (isset($_REQUEST['cara01inpecfuncionario'])==0){$_REQUEST['cara01inpecfuncionario']='N';}
if (isset($_REQUEST['cara01inpecrecluso'])==0){$_REQUEST['cara01inpecrecluso']='N';}
if (isset($_REQUEST['cara01inpectiempocondena'])==0){$_REQUEST['cara01inpectiempocondena']='';}
if (isset($_REQUEST['cara01centroreclusion'])==0){$_REQUEST['cara01centroreclusion']='';}
if (isset($_REQUEST['cara01discsensorial'])==0){$_REQUEST['cara01discsensorial']='N';}
if (isset($_REQUEST['cara01discfisica'])==0){$_REQUEST['cara01discfisica']='N';}
if (isset($_REQUEST['cara01disccognitiva'])==0){$_REQUEST['cara01disccognitiva']='N';}
if (isset($_REQUEST['cara01idconfirmadisc'])==0){$_REQUEST['cara01idconfirmadisc']=$idTercero;}
if (isset($_REQUEST['cara01idconfirmadisc_td'])==0){$_REQUEST['cara01idconfirmadisc_td']=$APP->tipo_doc;}
if (isset($_REQUEST['cara01idconfirmadisc_doc'])==0){$_REQUEST['cara01idconfirmadisc_doc']='';}
if (isset($_REQUEST['cara01fechaconfirmadisc'])==0){$_REQUEST['cara01fechaconfirmadisc']='';}//{fecha_hoy();}
if (isset($_REQUEST['cara01fam_tipovivienda'])==0){$_REQUEST['cara01fam_tipovivienda']='';}
if (isset($_REQUEST['cara01fam_vivecon'])==0){$_REQUEST['cara01fam_vivecon']='';}
if (isset($_REQUEST['cara01fam_numpersgrupofam'])==0){$_REQUEST['cara01fam_numpersgrupofam']='';}
if (isset($_REQUEST['cara01fam_hijos'])==0){$_REQUEST['cara01fam_hijos']='';}
if (isset($_REQUEST['cara01fam_personasacargo'])==0){$_REQUEST['cara01fam_personasacargo']='';}
if (isset($_REQUEST['cara01fam_dependeecon'])==0){$_REQUEST['cara01fam_dependeecon']='';}
if (isset($_REQUEST['cara01fam_escolaridadpadre'])==0){$_REQUEST['cara01fam_escolaridadpadre']='';}
if (isset($_REQUEST['cara01fam_escolaridadmadre'])==0){$_REQUEST['cara01fam_escolaridadmadre']='';}
if (isset($_REQUEST['cara01fam_numhermanos'])==0){$_REQUEST['cara01fam_numhermanos']='';}
if (isset($_REQUEST['cara01fam_posicionherm'])==0){$_REQUEST['cara01fam_posicionherm']='';}
if (isset($_REQUEST['cara01fam_familiaunad'])==0){$_REQUEST['cara01fam_familiaunad']='';}
if (isset($_REQUEST['cara01acad_tipocolegio'])==0){$_REQUEST['cara01acad_tipocolegio']='';}
if (isset($_REQUEST['cara01acad_modalidadbach'])==0){$_REQUEST['cara01acad_modalidadbach']='';}
if (isset($_REQUEST['cara01acad_estudioprev'])==0){$_REQUEST['cara01acad_estudioprev']='';}
if (isset($_REQUEST['cara01acad_ultnivelest'])==0){$_REQUEST['cara01acad_ultnivelest']='';}
if (isset($_REQUEST['cara01acad_obtubodiploma'])==0){$_REQUEST['cara01acad_obtubodiploma']='';}
if (isset($_REQUEST['cara01acad_hatomadovirtual'])==0){$_REQUEST['cara01acad_hatomadovirtual']='';}
if (isset($_REQUEST['cara01acad_tiemposinest'])==0){$_REQUEST['cara01acad_tiemposinest']='';}
if (isset($_REQUEST['cara01acad_razonestudio'])==0){$_REQUEST['cara01acad_razonestudio']='';}
if (isset($_REQUEST['cara01acad_primeraopc'])==0){$_REQUEST['cara01acad_primeraopc']='';}
if (isset($_REQUEST['cara01acad_programagusto'])==0){$_REQUEST['cara01acad_programagusto']='';}
if (isset($_REQUEST['cara01acad_razonunad'])==0){$_REQUEST['cara01acad_razonunad']='';}
if (isset($_REQUEST['cara01campus_compescrito'])==0){$_REQUEST['cara01campus_compescrito']='';}
if (isset($_REQUEST['cara01campus_portatil'])==0){$_REQUEST['cara01campus_portatil']='';}
if (isset($_REQUEST['cara01campus_tableta'])==0){$_REQUEST['cara01campus_tableta']='';}
if (isset($_REQUEST['cara01campus_telefono'])==0){$_REQUEST['cara01campus_telefono']='';}
if (isset($_REQUEST['cara01campus_energia'])==0){$_REQUEST['cara01campus_energia']='';}
if (isset($_REQUEST['cara01campus_internetreside'])==0){$_REQUEST['cara01campus_internetreside']='';}
if (isset($_REQUEST['cara01campus_expvirtual'])==0){$_REQUEST['cara01campus_expvirtual']='';}
if (isset($_REQUEST['cara01campus_ofimatica'])==0){$_REQUEST['cara01campus_ofimatica']='';}
if (isset($_REQUEST['cara01campus_foros'])==0){$_REQUEST['cara01campus_foros']='';}
if (isset($_REQUEST['cara01campus_conversiones'])==0){$_REQUEST['cara01campus_conversiones']='';}
if (isset($_REQUEST['cara01campus_usocorreo'])==0){$_REQUEST['cara01campus_usocorreo']='';}
if (isset($_REQUEST['cara01campus_aprendtexto'])==0){$_REQUEST['cara01campus_aprendtexto']='';}
if (isset($_REQUEST['cara01campus_aprendvideo'])==0){$_REQUEST['cara01campus_aprendvideo']='';}
if (isset($_REQUEST['cara01campus_aprendmapas'])==0){$_REQUEST['cara01campus_aprendmapas']='';}
if (isset($_REQUEST['cara01campus_aprendeanima'])==0){$_REQUEST['cara01campus_aprendeanima']='';}
if (isset($_REQUEST['cara01campus_mediocomunica'])==0){$_REQUEST['cara01campus_mediocomunica']='';}
if (isset($_REQUEST['cara01lab_situacion'])==0){$_REQUEST['cara01lab_situacion']='';}
if (isset($_REQUEST['cara01lab_sector'])==0){$_REQUEST['cara01lab_sector']='';}
if (isset($_REQUEST['cara01lab_caracterjuri'])==0){$_REQUEST['cara01lab_caracterjuri']='';}
if (isset($_REQUEST['cara01lab_cargo'])==0){$_REQUEST['cara01lab_cargo']='';}
if (isset($_REQUEST['cara01lab_antiguedad'])==0){$_REQUEST['cara01lab_antiguedad']='';}
if (isset($_REQUEST['cara01lab_tipocontrato'])==0){$_REQUEST['cara01lab_tipocontrato']='';}
if (isset($_REQUEST['cara01lab_rangoingreso'])==0){$_REQUEST['cara01lab_rangoingreso']='';}
if (isset($_REQUEST['cara01lab_tiempoacadem'])==0){$_REQUEST['cara01lab_tiempoacadem']='';}
if (isset($_REQUEST['cara01lab_tipoempresa'])==0){$_REQUEST['cara01lab_tipoempresa']='';}
if (isset($_REQUEST['cara01lab_tiempoindepen'])==0){$_REQUEST['cara01lab_tiempoindepen']='';}
if (isset($_REQUEST['cara01lab_debebusctrab'])==0){$_REQUEST['cara01lab_debebusctrab']='';}
if (isset($_REQUEST['cara01lab_origendinero'])==0){$_REQUEST['cara01lab_origendinero']='';}
if (isset($_REQUEST['cara01bien_baloncesto'])==0){$_REQUEST['cara01bien_baloncesto']='';}
if (isset($_REQUEST['cara01bien_voleibol'])==0){$_REQUEST['cara01bien_voleibol']='';}
if (isset($_REQUEST['cara01bien_futbolsala'])==0){$_REQUEST['cara01bien_futbolsala']='';}
if (isset($_REQUEST['cara01bien_artesmarc'])==0){$_REQUEST['cara01bien_artesmarc']='';}
if (isset($_REQUEST['cara01bien_tenisdemesa'])==0){$_REQUEST['cara01bien_tenisdemesa']='';}
if (isset($_REQUEST['cara01bien_ajedrez'])==0){$_REQUEST['cara01bien_ajedrez']='';}
if (isset($_REQUEST['cara01bien_juegosautoc'])==0){$_REQUEST['cara01bien_juegosautoc']='';}
if (isset($_REQUEST['cara01bien_interesrepdeporte'])==0){$_REQUEST['cara01bien_interesrepdeporte']='';}
if (isset($_REQUEST['cara01bien_deporteint'])==0){$_REQUEST['cara01bien_deporteint']='';}
if (isset($_REQUEST['cara01bien_teatro'])==0){$_REQUEST['cara01bien_teatro']='';}
if (isset($_REQUEST['cara01bien_danza'])==0){$_REQUEST['cara01bien_danza']='';}
if (isset($_REQUEST['cara01bien_musica'])==0){$_REQUEST['cara01bien_musica']='';}
if (isset($_REQUEST['cara01bien_circo'])==0){$_REQUEST['cara01bien_circo']='';}
if (isset($_REQUEST['cara01bien_artplast'])==0){$_REQUEST['cara01bien_artplast']='';}
if (isset($_REQUEST['cara01bien_cuenteria'])==0){$_REQUEST['cara01bien_cuenteria']='';}
if (isset($_REQUEST['cara01bien_interesreparte'])==0){$_REQUEST['cara01bien_interesreparte']='';}
if (isset($_REQUEST['cara01bien_arteint'])==0){$_REQUEST['cara01bien_arteint']='';}
if (isset($_REQUEST['cara01bien_interpreta'])==0){$_REQUEST['cara01bien_interpreta']='';}
if (isset($_REQUEST['cara01bien_nivelinter'])==0){$_REQUEST['cara01bien_nivelinter']='';}
if (isset($_REQUEST['cara01bien_danza_mod'])==0){$_REQUEST['cara01bien_danza_mod']='';}
if (isset($_REQUEST['cara01bien_danza_clas'])==0){$_REQUEST['cara01bien_danza_clas']='';}
if (isset($_REQUEST['cara01bien_danza_cont'])==0){$_REQUEST['cara01bien_danza_cont']='';}
if (isset($_REQUEST['cara01bien_danza_folk'])==0){$_REQUEST['cara01bien_danza_folk']='';}
if (isset($_REQUEST['cara01bien_niveldanza'])==0){$_REQUEST['cara01bien_niveldanza']='';}
if (isset($_REQUEST['cara01bien_emprendedor'])==0){$_REQUEST['cara01bien_emprendedor']='';}
if (isset($_REQUEST['cara01bien_nombreemp'])==0){$_REQUEST['cara01bien_nombreemp']='';}
if (isset($_REQUEST['cara01bien_capacempren'])==0){$_REQUEST['cara01bien_capacempren']='';}
if (isset($_REQUEST['cara01bien_tipocapacita'])==0){$_REQUEST['cara01bien_tipocapacita']='';}
if (isset($_REQUEST['cara01bien_impvidasalud'])==0){$_REQUEST['cara01bien_impvidasalud']='';}
if (isset($_REQUEST['cara01bien_estraautocuid'])==0){$_REQUEST['cara01bien_estraautocuid']='';}
if (isset($_REQUEST['cara01bien_pv_personal'])==0){$_REQUEST['cara01bien_pv_personal']='';}
if (isset($_REQUEST['cara01bien_pv_familiar'])==0){$_REQUEST['cara01bien_pv_familiar']='';}
if (isset($_REQUEST['cara01bien_pv_academ'])==0){$_REQUEST['cara01bien_pv_academ']='';}
if (isset($_REQUEST['cara01bien_pv_labora'])==0){$_REQUEST['cara01bien_pv_labora']='';}
if (isset($_REQUEST['cara01bien_pv_pareja'])==0){$_REQUEST['cara01bien_pv_pareja']='';}
if (isset($_REQUEST['cara01bien_amb'])==0){$_REQUEST['cara01bien_amb']='';}
if (isset($_REQUEST['cara01bien_amb_agu'])==0){$_REQUEST['cara01bien_amb_agu']='';}
if (isset($_REQUEST['cara01bien_amb_bom'])==0){$_REQUEST['cara01bien_amb_bom']='';}
if (isset($_REQUEST['cara01bien_amb_car'])==0){$_REQUEST['cara01bien_amb_car']='';}
if (isset($_REQUEST['cara01bien_amb_info'])==0){$_REQUEST['cara01bien_amb_info']='';}
if (isset($_REQUEST['cara01bien_amb_temas'])==0){$_REQUEST['cara01bien_amb_temas']='';}
if (isset($_REQUEST['cara01psico_costoemocion'])==0){$_REQUEST['cara01psico_costoemocion']='';}
if (isset($_REQUEST['cara01psico_reaccionimpre'])==0){$_REQUEST['cara01psico_reaccionimpre']='';}
if (isset($_REQUEST['cara01psico_estres'])==0){$_REQUEST['cara01psico_estres']='';}
if (isset($_REQUEST['cara01psico_pocotiempo'])==0){$_REQUEST['cara01psico_pocotiempo']='';}
if (isset($_REQUEST['cara01psico_actitudvida'])==0){$_REQUEST['cara01psico_actitudvida']='';}
if (isset($_REQUEST['cara01psico_duda'])==0){$_REQUEST['cara01psico_duda']='';}
if (isset($_REQUEST['cara01psico_problemapers'])==0){$_REQUEST['cara01psico_problemapers']='';}
if (isset($_REQUEST['cara01psico_satisfaccion'])==0){$_REQUEST['cara01psico_satisfaccion']='';}
if (isset($_REQUEST['cara01psico_discusiones'])==0){$_REQUEST['cara01psico_discusiones']='';}
if (isset($_REQUEST['cara01psico_atencion'])==0){$_REQUEST['cara01psico_atencion']='';}
if (isset($_REQUEST['cara01niveldigital'])==0){$_REQUEST['cara01niveldigital']=0;}
if (isset($_REQUEST['cara01nivellectura'])==0){$_REQUEST['cara01nivellectura']=0;}
if (isset($_REQUEST['cara01nivelrazona'])==0){$_REQUEST['cara01nivelrazona']=0;}
if (isset($_REQUEST['cara01nivelingles'])==0){$_REQUEST['cara01nivelingles']=0;}
if (isset($_REQUEST['cara01idconsejero'])==0){$_REQUEST['cara01idconsejero']=$idTercero;}
if (isset($_REQUEST['cara01idconsejero_td'])==0){$_REQUEST['cara01idconsejero_td']=$APP->tipo_doc;}
if (isset($_REQUEST['cara01idconsejero_doc'])==0){$_REQUEST['cara01idconsejero_doc']='';}
if (isset($_REQUEST['cara01fechainicio'])==0){$_REQUEST['cara01fechainicio']='';}//{fecha_hoy();}
if (isset($_REQUEST['cara01telefono1'])==0){$_REQUEST['cara01telefono1']='';}
if (isset($_REQUEST['cara01telefono2'])==0){$_REQUEST['cara01telefono2']='';}
if (isset($_REQUEST['cara01correopersonal'])==0){$_REQUEST['cara01correopersonal']='';}
if (isset($_REQUEST['cara01idprograma'])==0){$_REQUEST['cara01idprograma']=0;}
if (isset($_REQUEST['cara01idescuela'])==0){$_REQUEST['cara01idescuela']=0;}
if (isset($_REQUEST['cara01fichabiolog'])==0){$_REQUEST['cara01fichabiolog']='';}
if (isset($_REQUEST['cara01nivelbiolog'])==0){$_REQUEST['cara01nivelbiolog']=0;}
if (isset($_REQUEST['cara01fichafisica'])==0){$_REQUEST['cara01fichafisica']='';}
if (isset($_REQUEST['cara01nivelfisica'])==0){$_REQUEST['cara01nivelfisica']=0;}
if (isset($_REQUEST['cara01fichaquimica'])==0){$_REQUEST['cara01fichaquimica']=0;}
if (isset($_REQUEST['cara01nivelquimica'])==0){$_REQUEST['cara01nivelquimica']=0;}
if (isset($_REQUEST['cara01tipocaracterizacion'])==0){$_REQUEST['cara01tipocaracterizacion']=0;}
if (isset($_REQUEST['cara01perayuda'])==0){$_REQUEST['cara01perayuda']='';}
if (isset($_REQUEST['cara01perotraayuda'])==0){$_REQUEST['cara01perotraayuda']='';}
if (isset($_REQUEST['cara01discsensorialotra'])==0){$_REQUEST['cara01discsensorialotra']='';}
if (isset($_REQUEST['cara01discfisicaotra'])==0){$_REQUEST['cara01discfisicaotra']='';}
if (isset($_REQUEST['cara01disccognitivaotra'])==0){$_REQUEST['cara01disccognitivaotra']='';}
if (isset($_REQUEST['cara01idcursocatedra'])==0){$_REQUEST['cara01idcursocatedra']=0;}
if (isset($_REQUEST['cara01idgrupocatedra'])==0){$_REQUEST['cara01idgrupocatedra']=0;}
if (isset($_REQUEST['cara01factordescper'])==0){$_REQUEST['cara01factordescper']=0;}
if (isset($_REQUEST['cara01factordescpsico'])==0){$_REQUEST['cara01factordescpsico']=0;}
if (isset($_REQUEST['cara01factordescinsti'])==0){$_REQUEST['cara01factordescinsti']=0;}
if (isset($_REQUEST['cara01factordescacad'])==0){$_REQUEST['cara01factordescacad']=0;}
if (isset($_REQUEST['cara01factordesc'])==0){$_REQUEST['cara01factordesc']=0;}
if (isset($_REQUEST['cara01criteriodesc'])==0){$_REQUEST['cara01criteriodesc']='';}
if (isset($_REQUEST['cara01desertor'])==0){$_REQUEST['cara01desertor']='N';}
if (isset($_REQUEST['cara01factorprincipaldesc'])==0){$_REQUEST['cara01factorprincipaldesc']='';}
if (isset($_REQUEST['cara01psico_puntaje'])==0){$_REQUEST['cara01psico_puntaje']=0;}
if (isset($_REQUEST['cara01discversion'])==0){$_REQUEST['cara01discversion']=2;}
if (isset($_REQUEST['cara01discv2sensorial'])==0){$_REQUEST['cara01discv2sensorial']='';}
if (isset($_REQUEST['cara02discv2intelectura'])==0){$_REQUEST['cara02discv2intelectura']='';}
if (isset($_REQUEST['cara02discv2fisica'])==0){$_REQUEST['cara02discv2fisica']='';}
if (isset($_REQUEST['cara02discv2psico'])==0){$_REQUEST['cara02discv2psico']='';}
if (isset($_REQUEST['cara02discv2sistemica'])==0){$_REQUEST['cara02discv2sistemica']='';}
if (isset($_REQUEST['cara02discv2sistemicaotro'])==0){$_REQUEST['cara02discv2sistemicaotro']='';}
if (isset($_REQUEST['cara02discv2multiple'])==0){$_REQUEST['cara02discv2multiple']='';}
if (isset($_REQUEST['cara02discv2multipleotro'])==0){$_REQUEST['cara02discv2multipleotro']='';}
if (isset($_REQUEST['cara02talentoexcepcional'])==0){$_REQUEST['cara02talentoexcepcional']='';}
if (isset($_REQUEST['cara01discv2tiene'])==0){$_REQUEST['cara01discv2tiene']='';}
if (isset($_REQUEST['cara01discv2trastaprende'])==0){$_REQUEST['cara01discv2trastaprende']='';}
if (isset($_REQUEST['cara01discv2soporteorigen'])==0){$_REQUEST['cara01discv2soporteorigen']=0;}
if (isset($_REQUEST['cara01discv2archivoorigen'])==0){$_REQUEST['cara01discv2archivoorigen']=0;}
if (isset($_REQUEST['cara01discv2trastornos'])==0){$_REQUEST['cara01discv2trastornos']='';}
if (isset($_REQUEST['cara01discv2contalento'])==0){$_REQUEST['cara01discv2contalento']='';}
if (isset($_REQUEST['cara01discv2condicionmedica'])==0){$_REQUEST['cara01discv2condicionmedica']='';}
if (isset($_REQUEST['cara01discv2condmeddet'])==0){$_REQUEST['cara01discv2condmeddet']='';}
if (isset($_REQUEST['cara01discv2pruebacoeficiente'])==0){$_REQUEST['cara01discv2pruebacoeficiente']='';}
if (isset($_REQUEST['cara01sexoversion'])==0){$_REQUEST['cara01sexoversion']=1;}
if (isset($_REQUEST['cara01sexov1identidadgen'])==0){$_REQUEST['cara01sexov1identidadgen']='';}
if (isset($_REQUEST['cara01sexov1orientasexo'])==0){$_REQUEST['cara01sexov1orientasexo']='';}
if (isset($_REQUEST['cara01saber11version'])==0){$_REQUEST['cara01saber11version']=1;}
if (isset($_REQUEST['cara01saber11v1agno'])==0){$_REQUEST['cara01saber11v1agno']='';}
if (isset($_REQUEST['cara01saber11v1numreg'])==0){$_REQUEST['cara01saber11v1numreg']='';}
if (isset($_REQUEST['cara01saber11v1puntglobal'])==0){$_REQUEST['cara01saber11v1puntglobal']=0;}
if (isset($_REQUEST['cara01saber11v1puntleccritica'])==0){$_REQUEST['cara01saber11v1puntleccritica']=0;}
if (isset($_REQUEST['cara01saber11v1puntmatematicas'])==0){$_REQUEST['cara01saber11v1puntmatematicas']=0;}
if (isset($_REQUEST['cara01saber11v1puntsociales'])==0){$_REQUEST['cara01saber11v1puntsociales']=0;}
if (isset($_REQUEST['cara01saber11v1puntciencias'])==0){$_REQUEST['cara01saber11v1puntciencias']=0;}
if (isset($_REQUEST['cara01saber11v1puntingles'])==0){$_REQUEST['cara01saber11v1puntingles']=0;}
if (isset($_REQUEST['cara01bienversion'])==0){$_REQUEST['cara01bienversion']=2;}
if (isset($_REQUEST['cara01bienv2altoren'])==0){$_REQUEST['cara01bienv2altoren']='';}
if (isset($_REQUEST['cara01bienv2atletismo'])==0){$_REQUEST['cara01bienv2atletismo']='';}
if (isset($_REQUEST['cara01bienv2baloncesto'])==0){$_REQUEST['cara01bienv2baloncesto']='';}
if (isset($_REQUEST['cara01bienv2futbol'])==0){$_REQUEST['cara01bienv2futbol']='';}
if (isset($_REQUEST['cara01bienv2gimnasia'])==0){$_REQUEST['cara01bienv2gimnasia']='';}
if (isset($_REQUEST['cara01bienv2natacion'])==0){$_REQUEST['cara01bienv2natacion']='';}
if (isset($_REQUEST['cara01bienv2voleibol'])==0){$_REQUEST['cara01bienv2voleibol']='';}
if (isset($_REQUEST['cara01bienv2tenis'])==0){$_REQUEST['cara01bienv2tenis']='';}
if (isset($_REQUEST['cara01bienv2paralimpico'])==0){$_REQUEST['cara01bienv2paralimpico']='';}
if (isset($_REQUEST['cara01bienv2otrodeporte'])==0){$_REQUEST['cara01bienv2otrodeporte']='';}
if (isset($_REQUEST['cara01bienv2otrodeportedetalle'])==0){$_REQUEST['cara01bienv2otrodeportedetalle']='';}
if (isset($_REQUEST['cara01bienv2activdanza'])==0){$_REQUEST['cara01bienv2activdanza']='';}
if (isset($_REQUEST['cara01bienv2activmusica'])==0){$_REQUEST['cara01bienv2activmusica']='';}
if (isset($_REQUEST['cara01bienv2activteatro'])==0){$_REQUEST['cara01bienv2activteatro']='';}
if (isset($_REQUEST['cara01bienv2activartes'])==0){$_REQUEST['cara01bienv2activartes']='';}
if (isset($_REQUEST['cara01bienv2activliteratura'])==0){$_REQUEST['cara01bienv2activliteratura']='';}
if (isset($_REQUEST['cara01bienv2activculturalotra'])==0){$_REQUEST['cara01bienv2activculturalotra']='';}
if (isset($_REQUEST['cara01bienv2activculturalotradetalle'])==0){$_REQUEST['cara01bienv2activculturalotradetalle']='';}
if (isset($_REQUEST['cara01bienv2evenfestfolc'])==0){$_REQUEST['cara01bienv2evenfestfolc']='';}
if (isset($_REQUEST['cara01bienv2evenexpoarte'])==0){$_REQUEST['cara01bienv2evenexpoarte']='';}
if (isset($_REQUEST['cara01bienv2evenhistarte'])==0){$_REQUEST['cara01bienv2evenhistarte']='';}
if (isset($_REQUEST['cara01bienv2evengalfoto'])==0){$_REQUEST['cara01bienv2evengalfoto']='';}
if (isset($_REQUEST['cara01bienv2evenliteratura'])==0){$_REQUEST['cara01bienv2evenliteratura']='';}
if (isset($_REQUEST['cara01bienv2eventeatro'])==0){$_REQUEST['cara01bienv2eventeatro']='';}
if (isset($_REQUEST['cara01bienv2evencine'])==0){$_REQUEST['cara01bienv2evencine']='';}
if (isset($_REQUEST['cara01bienv2evenculturalotro'])==0){$_REQUEST['cara01bienv2evenculturalotro']='';}
if (isset($_REQUEST['cara01bienv2evenculturalotrodetalle'])==0){$_REQUEST['cara01bienv2evenculturalotrodetalle']='';}
if (isset($_REQUEST['cara01bienv2emprendimiento'])==0){$_REQUEST['cara01bienv2emprendimiento']='';}
if (isset($_REQUEST['cara01bienv2empresa'])==0){$_REQUEST['cara01bienv2empresa']='';}
if (isset($_REQUEST['cara01bienv2emprenrecursos'])==0){$_REQUEST['cara01bienv2emprenrecursos']='';}
if (isset($_REQUEST['cara01bienv2emprenconocim'])==0){$_REQUEST['cara01bienv2emprenconocim']='';}
if (isset($_REQUEST['cara01bienv2emprenplan'])==0){$_REQUEST['cara01bienv2emprenplan']='';}
if (isset($_REQUEST['cara01bienv2emprenejecutar'])==0){$_REQUEST['cara01bienv2emprenejecutar']='';}
if (isset($_REQUEST['cara01bienv2emprenfortconocim'])==0){$_REQUEST['cara01bienv2emprenfortconocim']='';}
if (isset($_REQUEST['cara01bienv2emprenidentproblema'])==0){$_REQUEST['cara01bienv2emprenidentproblema']='';}
if (isset($_REQUEST['cara01bienv2emprenotro'])==0){$_REQUEST['cara01bienv2emprenotro']='';}
if (isset($_REQUEST['cara01bienv2emprenotrodetalle'])==0){$_REQUEST['cara01bienv2emprenotrodetalle']='';}
if (isset($_REQUEST['cara01bienv2emprenmarketing'])==0){$_REQUEST['cara01bienv2emprenmarketing']='';}
if (isset($_REQUEST['cara01bienv2emprenplannegocios'])==0){$_REQUEST['cara01bienv2emprenplannegocios']='';}
if (isset($_REQUEST['cara01bienv2emprenideas'])==0){$_REQUEST['cara01bienv2emprenideas']='';}
if (isset($_REQUEST['cara01bienv2emprencreacion'])==0){$_REQUEST['cara01bienv2emprencreacion']='';}
if (isset($_REQUEST['cara01bienv2saludfacteconom'])==0){$_REQUEST['cara01bienv2saludfacteconom']='';}
if (isset($_REQUEST['cara01bienv2saludpreocupacion'])==0){$_REQUEST['cara01bienv2saludpreocupacion']='';}
if (isset($_REQUEST['cara01bienv2saludconsumosust'])==0){$_REQUEST['cara01bienv2saludconsumosust']='';}
if (isset($_REQUEST['cara01bienv2saludinsomnio'])==0){$_REQUEST['cara01bienv2saludinsomnio']='';}
if (isset($_REQUEST['cara01bienv2saludclimalab'])==0){$_REQUEST['cara01bienv2saludclimalab']='';}
if (isset($_REQUEST['cara01bienv2saludalimenta'])==0){$_REQUEST['cara01bienv2saludalimenta']='';}
if (isset($_REQUEST['cara01bienv2saludemocion'])==0){$_REQUEST['cara01bienv2saludemocion']='';}
if (isset($_REQUEST['cara01bienv2saludestado'])==0){$_REQUEST['cara01bienv2saludestado']='';}
if (isset($_REQUEST['cara01bienv2saludmedita'])==0){$_REQUEST['cara01bienv2saludmedita']='';}
if (isset($_REQUEST['cara01bienv2crecimedusexual'])==0){$_REQUEST['cara01bienv2crecimedusexual']='';}
if (isset($_REQUEST['cara01bienv2crecimcultciudad'])==0){$_REQUEST['cara01bienv2crecimcultciudad']='';}
if (isset($_REQUEST['cara01bienv2crecimrelpareja'])==0){$_REQUEST['cara01bienv2crecimrelpareja']='';}
if (isset($_REQUEST['cara01bienv2crecimrelinterp'])==0){$_REQUEST['cara01bienv2crecimrelinterp']='';}
if (isset($_REQUEST['cara01bienv2crecimdinamicafam'])==0){$_REQUEST['cara01bienv2crecimdinamicafam']='';}
if (isset($_REQUEST['cara01bienv2crecimautoestima'])==0){$_REQUEST['cara01bienv2crecimautoestima']='';}
if (isset($_REQUEST['cara01bienv2creciminclusion'])==0){$_REQUEST['cara01bienv2creciminclusion']='';}
if (isset($_REQUEST['cara01bienv2creciminteliemoc'])==0){$_REQUEST['cara01bienv2creciminteliemoc']='';}
if (isset($_REQUEST['cara01bienv2crecimcultural'])==0){$_REQUEST['cara01bienv2crecimcultural']='';}
if (isset($_REQUEST['cara01bienv2crecimartistico'])==0){$_REQUEST['cara01bienv2crecimartistico']='';}
if (isset($_REQUEST['cara01bienv2crecimdeporte'])==0){$_REQUEST['cara01bienv2crecimdeporte']='';}
if (isset($_REQUEST['cara01bienv2crecimambiente'])==0){$_REQUEST['cara01bienv2crecimambiente']='';}
if (isset($_REQUEST['cara01bienv2crecimhabsocio'])==0){$_REQUEST['cara01bienv2crecimhabsocio']='';}
if (isset($_REQUEST['cara01bienv2ambienbasura'])==0){$_REQUEST['cara01bienv2ambienbasura']='';}
if (isset($_REQUEST['cara01bienv2ambienreutiliza'])==0){$_REQUEST['cara01bienv2ambienreutiliza']='';}
if (isset($_REQUEST['cara01bienv2ambienluces'])==0){$_REQUEST['cara01bienv2ambienluces']='';}
if (isset($_REQUEST['cara01bienv2ambienfrutaverd'])==0){$_REQUEST['cara01bienv2ambienfrutaverd']='';}
if (isset($_REQUEST['cara01bienv2ambienenchufa'])==0){$_REQUEST['cara01bienv2ambienenchufa']='';}
if (isset($_REQUEST['cara01bienv2ambiengrifo'])==0){$_REQUEST['cara01bienv2ambiengrifo']='';}
if (isset($_REQUEST['cara01bienv2ambienbicicleta'])==0){$_REQUEST['cara01bienv2ambienbicicleta']='';}
if (isset($_REQUEST['cara01bienv2ambientranspub'])==0){$_REQUEST['cara01bienv2ambientranspub']='';}
if (isset($_REQUEST['cara01bienv2ambienducha'])==0){$_REQUEST['cara01bienv2ambienducha']='';}
if (isset($_REQUEST['cara01bienv2ambiencaminata'])==0){$_REQUEST['cara01bienv2ambiencaminata']='';}
if (isset($_REQUEST['cara01bienv2ambiensiembra'])==0){$_REQUEST['cara01bienv2ambiensiembra']='';}
if (isset($_REQUEST['cara01bienv2ambienconferencia'])==0){$_REQUEST['cara01bienv2ambienconferencia']='';}
if (isset($_REQUEST['cara01bienv2ambienrecicla'])==0){$_REQUEST['cara01bienv2ambienrecicla']='';}
if (isset($_REQUEST['cara01bienv2ambienotraactiv'])==0){$_REQUEST['cara01bienv2ambienotraactiv']='';}
if (isset($_REQUEST['cara01bienv2ambienotraactivdetalle'])==0){$_REQUEST['cara01bienv2ambienotraactivdetalle']='';}
if (isset($_REQUEST['cara01bienv2ambienreforest'])==0){$_REQUEST['cara01bienv2ambienreforest']='';}
if (isset($_REQUEST['cara01bienv2ambienmovilidad'])==0){$_REQUEST['cara01bienv2ambienmovilidad']='';}
if (isset($_REQUEST['cara01bienv2ambienclimatico'])==0){$_REQUEST['cara01bienv2ambienclimatico']='';}
if (isset($_REQUEST['cara01bienv2ambienecofemin'])==0){$_REQUEST['cara01bienv2ambienecofemin']='';}
if (isset($_REQUEST['cara01bienv2ambienbiodiver'])==0){$_REQUEST['cara01bienv2ambienbiodiver']='';}
if (isset($_REQUEST['cara01bienv2ambienecologia'])==0){$_REQUEST['cara01bienv2ambienecologia']='';}
if (isset($_REQUEST['cara01bienv2ambieneconomia'])==0){$_REQUEST['cara01bienv2ambieneconomia']='';}
if (isset($_REQUEST['cara01bienv2ambienrecnatura'])==0){$_REQUEST['cara01bienv2ambienrecnatura']='';}
if (isset($_REQUEST['cara01bienv2ambienreciclaje'])==0){$_REQUEST['cara01bienv2ambienreciclaje']='';}
if (isset($_REQUEST['cara01bienv2ambienmascota'])==0){$_REQUEST['cara01bienv2ambienmascota']='';}
if (isset($_REQUEST['cara01bienv2ambiencartohum'])==0){$_REQUEST['cara01bienv2ambiencartohum']='';}
if (isset($_REQUEST['cara01bienv2ambienespiritu'])==0){$_REQUEST['cara01bienv2ambienespiritu']='';}
if (isset($_REQUEST['cara01bienv2ambiencarga'])==0){$_REQUEST['cara01bienv2ambiencarga']='';}
if (isset($_REQUEST['cara01bienv2ambienotroenfoq'])==0){$_REQUEST['cara01bienv2ambienotroenfoq']='';}
if (isset($_REQUEST['cara01bienv2ambienotroenfoqdetalle'])==0){$_REQUEST['cara01bienv2ambienotroenfoqdetalle']='';}
if (isset($_REQUEST['cara01fam_madrecabeza'])==0){$_REQUEST['cara01fam_madrecabeza']='';}
if (isset($_REQUEST['cara01acadhatenidorecesos'])==0){$_REQUEST['cara01acadhatenidorecesos']='';}
if (isset($_REQUEST['cara01acadrazonreceso'])==0){$_REQUEST['cara01acadrazonreceso']=0;}
if (isset($_REQUEST['cara01acadrazonrecesodetalle'])==0){$_REQUEST['cara01acadrazonrecesodetalle']='';}
if (isset($_REQUEST['cara01campus_usocorreounad'])==0){$_REQUEST['cara01campus_usocorreounad']=0;}
if (isset($_REQUEST['cara01campus_usocorreounadno'])==0){$_REQUEST['cara01campus_usocorreounadno']=0;}
if (isset($_REQUEST['cara01campus_usocorreounadnodetalle'])==0){$_REQUEST['cara01campus_usocorreounadnodetalle']='';}
if (isset($_REQUEST['cara01campus_medioactivunad'])==0){$_REQUEST['cara01campus_medioactivunad']=0;}
if (isset($_REQUEST['cara01campus_medioactivunaddetalle'])==0){$_REQUEST['cara01campus_medioactivunaddetalle']='';}
if ((int)$_REQUEST['paso']>0){
	//Preguntas de la prueba
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['ficha'])==0){$_REQUEST['ficha']=1;}
if (!$bEstudiante){
	if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
	if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
	if (isset($_REQUEST['bperaca'])==0){$_REQUEST['bperaca']='';}
	if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']=1;}
	if (isset($_REQUEST['bescuela'])==0){$_REQUEST['bescuela']='';}
	if (isset($_REQUEST['bprograma'])==0){$_REQUEST['bprograma']='';}
	if (isset($_REQUEST['bzona'])==0){$_REQUEST['bzona']='';}
	if (isset($_REQUEST['bcead'])==0){$_REQUEST['bcead']='';}
	if (isset($_REQUEST['btipocara'])==0){$_REQUEST['btipocara']='';}
	if (isset($_REQUEST['bpoblacion'])==0){$_REQUEST['bpoblacion']='';}
	if (isset($_REQUEST['bconvenio'])==0){$_REQUEST['bconvenio']='';}
	}
if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Corriendo el paso '.$_REQUEST['paso'].' antes de entrar a leer instrucciones.<br>';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['cara01idtercero_td']=$APP->tipo_doc;
	$_REQUEST['cara01idtercero_doc']='';
	$_REQUEST['cara01idconfirmadesp_td']=$APP->tipo_doc;
	$_REQUEST['cara01idconfirmadesp_doc']='';
	$_REQUEST['cara01idconfirmacr_td']=$APP->tipo_doc;
	$_REQUEST['cara01idconfirmacr_doc']='';
	$_REQUEST['cara01idconfirmadisc_td']=$APP->tipo_doc;
	$_REQUEST['cara01idconfirmadisc_doc']='';
	$_REQUEST['cara01idconsejero_td']=$APP->tipo_doc;
	$_REQUEST['cara01idconsejero_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='cara01idperaca='.$_REQUEST['cara01idperaca'].' AND cara01idtercero="'.$_REQUEST['cara01idtercero'].'"';
		}else{
		$sSQLcondi='cara01id='.$_REQUEST['cara01id'].'';
		}
	$sSQL='SELECT * FROM cara01encuesta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['cara01idperaca']=$fila['cara01idperaca'];
		$_REQUEST['cara01idtercero']=$fila['cara01idtercero'];
		$_REQUEST['cara01id']=$fila['cara01id'];
		$_REQUEST['cara01completa']=$fila['cara01completa'];
		$_REQUEST['cara01fichaper']=$fila['cara01fichaper'];
		$_REQUEST['cara01fichafam']=$fila['cara01fichafam'];
		$_REQUEST['cara01fichaaca']=$fila['cara01fichaaca'];
		$_REQUEST['cara01fichalab']=$fila['cara01fichalab'];
		$_REQUEST['cara01fichabien']=$fila['cara01fichabien'];
		$_REQUEST['cara01fichapsico']=$fila['cara01fichapsico'];
		$_REQUEST['cara01fichadigital']=$fila['cara01fichadigital'];
		$_REQUEST['cara01fichalectura']=$fila['cara01fichalectura'];
		$_REQUEST['cara01ficharazona']=$fila['cara01ficharazona'];
		$_REQUEST['cara01fichaingles']=$fila['cara01fichaingles'];
		$_REQUEST['cara01fechaencuesta']=$fila['cara01fechaencuesta'];
		$_REQUEST['cara01agnos']=$fila['cara01agnos'];
		$_REQUEST['cara01sexo']=$fila['cara01sexo'];
		$_REQUEST['cara01pais']=$fila['cara01pais'];
		$_REQUEST['cara01depto']=$fila['cara01depto'];
		$_REQUEST['cara01ciudad']=$fila['cara01ciudad'];
		$_REQUEST['cara01nomciudad']=$fila['cara01nomciudad'];
		$_REQUEST['cara01direccion']=$fila['cara01direccion'];
		$_REQUEST['cara01estrato']=$fila['cara01estrato'];
		$_REQUEST['cara01zonares']=$fila['cara01zonares'];
		$_REQUEST['cara01estcivil']=$fila['cara01estcivil'];
		$_REQUEST['cara01nomcontacto']=$fila['cara01nomcontacto'];
		$_REQUEST['cara01parentezcocontacto']=$fila['cara01parentezcocontacto'];
		$_REQUEST['cara01celcontacto']=$fila['cara01celcontacto'];
		$_REQUEST['cara01correocontacto']=$fila['cara01correocontacto'];
		$_REQUEST['cara01idzona']=$fila['cara01idzona'];
		$_REQUEST['cara01idcead']=$fila['cara01idcead'];
		$_REQUEST['cara01matconvenio']=$fila['cara01matconvenio'];
		$_REQUEST['cara01raizal']=$fila['cara01raizal'];
		$_REQUEST['cara01palenquero']=$fila['cara01palenquero'];
		$_REQUEST['cara01afrocolombiano']=$fila['cara01afrocolombiano'];
		$_REQUEST['cara01otracomunnegras']=$fila['cara01otracomunnegras'];
		$_REQUEST['cara01rom']=$fila['cara01rom'];
		$_REQUEST['cara01indigenas']=$fila['cara01indigenas'];
		$_REQUEST['cara01victimadesplazado']=$fila['cara01victimadesplazado'];
		$_REQUEST['cara01idconfirmadesp']=$fila['cara01idconfirmadesp'];
		$_REQUEST['cara01fechaconfirmadesp']=$fila['cara01fechaconfirmadesp'];
		$_REQUEST['cara01victimaacr']=$fila['cara01victimaacr'];
		$_REQUEST['cara01idconfirmacr']=$fila['cara01idconfirmacr'];
		$_REQUEST['cara01fechaconfirmacr']=$fila['cara01fechaconfirmacr'];
		$_REQUEST['cara01inpecfuncionario']=$fila['cara01inpecfuncionario'];
		$_REQUEST['cara01inpecrecluso']=$fila['cara01inpecrecluso'];
		$_REQUEST['cara01inpectiempocondena']=$fila['cara01inpectiempocondena'];
		$_REQUEST['cara01centroreclusion']=$fila['cara01centroreclusion'];
		$_REQUEST['cara01discsensorial']=$fila['cara01discsensorial'];
		$_REQUEST['cara01discfisica']=$fila['cara01discfisica'];
		$_REQUEST['cara01disccognitiva']=$fila['cara01disccognitiva'];
		$_REQUEST['cara01idconfirmadisc']=$fila['cara01idconfirmadisc'];
		$_REQUEST['cara01fechaconfirmadisc']=$fila['cara01fechaconfirmadisc'];
		$_REQUEST['cara01fam_tipovivienda']=$fila['cara01fam_tipovivienda'];
		$_REQUEST['cara01fam_vivecon']=$fila['cara01fam_vivecon'];
		$_REQUEST['cara01fam_numpersgrupofam']=$fila['cara01fam_numpersgrupofam'];
		$_REQUEST['cara01fam_hijos']=$fila['cara01fam_hijos'];
		$_REQUEST['cara01fam_personasacargo']=$fila['cara01fam_personasacargo'];
		$_REQUEST['cara01fam_dependeecon']=$fila['cara01fam_dependeecon'];
		$_REQUEST['cara01fam_escolaridadpadre']=$fila['cara01fam_escolaridadpadre'];
		$_REQUEST['cara01fam_escolaridadmadre']=$fila['cara01fam_escolaridadmadre'];
		$_REQUEST['cara01fam_numhermanos']=$fila['cara01fam_numhermanos'];
		$_REQUEST['cara01fam_posicionherm']=$fila['cara01fam_posicionherm'];
		$_REQUEST['cara01fam_familiaunad']=$fila['cara01fam_familiaunad'];
		$_REQUEST['cara01acad_tipocolegio']=$fila['cara01acad_tipocolegio'];
		$_REQUEST['cara01acad_modalidadbach']=$fila['cara01acad_modalidadbach'];
		$_REQUEST['cara01acad_estudioprev']=$fila['cara01acad_estudioprev'];
		$_REQUEST['cara01acad_ultnivelest']=$fila['cara01acad_ultnivelest'];
		$_REQUEST['cara01acad_obtubodiploma']=$fila['cara01acad_obtubodiploma'];
		$_REQUEST['cara01acad_hatomadovirtual']=$fila['cara01acad_hatomadovirtual'];
		$_REQUEST['cara01acad_tiemposinest']=$fila['cara01acad_tiemposinest'];
		$_REQUEST['cara01acad_razonestudio']=$fila['cara01acad_razonestudio'];
		$_REQUEST['cara01acad_primeraopc']=$fila['cara01acad_primeraopc'];
		$_REQUEST['cara01acad_programagusto']=$fila['cara01acad_programagusto'];
		$_REQUEST['cara01acad_razonunad']=$fila['cara01acad_razonunad'];
		$_REQUEST['cara01campus_compescrito']=$fila['cara01campus_compescrito'];
		$_REQUEST['cara01campus_portatil']=$fila['cara01campus_portatil'];
		$_REQUEST['cara01campus_tableta']=$fila['cara01campus_tableta'];
		$_REQUEST['cara01campus_telefono']=$fila['cara01campus_telefono'];
		$_REQUEST['cara01campus_energia']=$fila['cara01campus_energia'];
		$_REQUEST['cara01campus_internetreside']=$fila['cara01campus_internetreside'];
		$_REQUEST['cara01campus_expvirtual']=$fila['cara01campus_expvirtual'];
		$_REQUEST['cara01campus_ofimatica']=$fila['cara01campus_ofimatica'];
		$_REQUEST['cara01campus_foros']=$fila['cara01campus_foros'];
		$_REQUEST['cara01campus_conversiones']=$fila['cara01campus_conversiones'];
		$_REQUEST['cara01campus_usocorreo']=$fila['cara01campus_usocorreo'];
		$_REQUEST['cara01campus_aprendtexto']=$fila['cara01campus_aprendtexto'];
		$_REQUEST['cara01campus_aprendvideo']=$fila['cara01campus_aprendvideo'];
		$_REQUEST['cara01campus_aprendmapas']=$fila['cara01campus_aprendmapas'];
		$_REQUEST['cara01campus_aprendeanima']=$fila['cara01campus_aprendeanima'];
		$_REQUEST['cara01campus_mediocomunica']=$fila['cara01campus_mediocomunica'];
		$_REQUEST['cara01lab_situacion']=$fila['cara01lab_situacion'];
		$_REQUEST['cara01lab_sector']=$fila['cara01lab_sector'];
		$_REQUEST['cara01lab_caracterjuri']=$fila['cara01lab_caracterjuri'];
		$_REQUEST['cara01lab_cargo']=$fila['cara01lab_cargo'];
		$_REQUEST['cara01lab_antiguedad']=$fila['cara01lab_antiguedad'];
		$_REQUEST['cara01lab_tipocontrato']=$fila['cara01lab_tipocontrato'];
		$_REQUEST['cara01lab_rangoingreso']=$fila['cara01lab_rangoingreso'];
		$_REQUEST['cara01lab_tiempoacadem']=$fila['cara01lab_tiempoacadem'];
		$_REQUEST['cara01lab_tipoempresa']=$fila['cara01lab_tipoempresa'];
		$_REQUEST['cara01lab_tiempoindepen']=$fila['cara01lab_tiempoindepen'];
		$_REQUEST['cara01lab_debebusctrab']=$fila['cara01lab_debebusctrab'];
		$_REQUEST['cara01lab_origendinero']=$fila['cara01lab_origendinero'];
		$_REQUEST['cara01bien_baloncesto']=$fila['cara01bien_baloncesto'];
		$_REQUEST['cara01bien_voleibol']=$fila['cara01bien_voleibol'];
		$_REQUEST['cara01bien_futbolsala']=$fila['cara01bien_futbolsala'];
		$_REQUEST['cara01bien_artesmarc']=$fila['cara01bien_artesmarc'];
		$_REQUEST['cara01bien_tenisdemesa']=$fila['cara01bien_tenisdemesa'];
		$_REQUEST['cara01bien_ajedrez']=$fila['cara01bien_ajedrez'];
		$_REQUEST['cara01bien_juegosautoc']=$fila['cara01bien_juegosautoc'];
		$_REQUEST['cara01bien_interesrepdeporte']=$fila['cara01bien_interesrepdeporte'];
		$_REQUEST['cara01bien_deporteint']=$fila['cara01bien_deporteint'];
		$_REQUEST['cara01bien_teatro']=$fila['cara01bien_teatro'];
		$_REQUEST['cara01bien_danza']=$fila['cara01bien_danza'];
		$_REQUEST['cara01bien_musica']=$fila['cara01bien_musica'];
		$_REQUEST['cara01bien_circo']=$fila['cara01bien_circo'];
		$_REQUEST['cara01bien_artplast']=$fila['cara01bien_artplast'];
		$_REQUEST['cara01bien_cuenteria']=$fila['cara01bien_cuenteria'];
		$_REQUEST['cara01bien_interesreparte']=$fila['cara01bien_interesreparte'];
		$_REQUEST['cara01bien_arteint']=$fila['cara01bien_arteint'];
		$_REQUEST['cara01bien_interpreta']=$fila['cara01bien_interpreta'];
		$_REQUEST['cara01bien_nivelinter']=$fila['cara01bien_nivelinter'];
		$_REQUEST['cara01bien_danza_mod']=$fila['cara01bien_danza_mod'];
		$_REQUEST['cara01bien_danza_clas']=$fila['cara01bien_danza_clas'];
		$_REQUEST['cara01bien_danza_cont']=$fila['cara01bien_danza_cont'];
		$_REQUEST['cara01bien_danza_folk']=$fila['cara01bien_danza_folk'];
		$_REQUEST['cara01bien_niveldanza']=$fila['cara01bien_niveldanza'];
		$_REQUEST['cara01bien_emprendedor']=$fila['cara01bien_emprendedor'];
		$_REQUEST['cara01bien_nombreemp']=$fila['cara01bien_nombreemp'];
		$_REQUEST['cara01bien_capacempren']=$fila['cara01bien_capacempren'];
		$_REQUEST['cara01bien_tipocapacita']=$fila['cara01bien_tipocapacita'];
		$_REQUEST['cara01bien_impvidasalud']=$fila['cara01bien_impvidasalud'];
		$_REQUEST['cara01bien_estraautocuid']=$fila['cara01bien_estraautocuid'];
		$_REQUEST['cara01bien_pv_personal']=$fila['cara01bien_pv_personal'];
		$_REQUEST['cara01bien_pv_familiar']=$fila['cara01bien_pv_familiar'];
		$_REQUEST['cara01bien_pv_academ']=$fila['cara01bien_pv_academ'];
		$_REQUEST['cara01bien_pv_labora']=$fila['cara01bien_pv_labora'];
		$_REQUEST['cara01bien_pv_pareja']=$fila['cara01bien_pv_pareja'];
		$_REQUEST['cara01bien_amb']=$fila['cara01bien_amb'];
		$_REQUEST['cara01bien_amb_agu']=$fila['cara01bien_amb_agu'];
		$_REQUEST['cara01bien_amb_bom']=$fila['cara01bien_amb_bom'];
		$_REQUEST['cara01bien_amb_car']=$fila['cara01bien_amb_car'];
		$_REQUEST['cara01bien_amb_info']=$fila['cara01bien_amb_info'];
		$_REQUEST['cara01bien_amb_temas']=$fila['cara01bien_amb_temas'];
		$_REQUEST['cara01psico_costoemocion']=$fila['cara01psico_costoemocion'];
		$_REQUEST['cara01psico_reaccionimpre']=$fila['cara01psico_reaccionimpre'];
		$_REQUEST['cara01psico_estres']=$fila['cara01psico_estres'];
		$_REQUEST['cara01psico_pocotiempo']=$fila['cara01psico_pocotiempo'];
		$_REQUEST['cara01psico_actitudvida']=$fila['cara01psico_actitudvida'];
		$_REQUEST['cara01psico_duda']=$fila['cara01psico_duda'];
		$_REQUEST['cara01psico_problemapers']=$fila['cara01psico_problemapers'];
		$_REQUEST['cara01psico_satisfaccion']=$fila['cara01psico_satisfaccion'];
		$_REQUEST['cara01psico_discusiones']=$fila['cara01psico_discusiones'];
		$_REQUEST['cara01psico_atencion']=$fila['cara01psico_atencion'];
		$_REQUEST['cara01niveldigital']=$fila['cara01niveldigital'];
		$_REQUEST['cara01nivellectura']=$fila['cara01nivellectura'];
		$_REQUEST['cara01nivelrazona']=$fila['cara01nivelrazona'];
		$_REQUEST['cara01nivelingles']=$fila['cara01nivelingles'];
		$_REQUEST['cara01idconsejero']=$fila['cara01idconsejero'];
		$_REQUEST['cara01fechainicio']=$fila['cara01fechainicio'];
		$_REQUEST['cara01telefono1']=$fila['cara01telefono1'];
		$_REQUEST['cara01telefono2']=$fila['cara01telefono2'];
		$_REQUEST['cara01correopersonal']=$fila['cara01correopersonal'];
		$_REQUEST['cara01idprograma']=$fila['cara01idprograma'];
		$_REQUEST['cara01idescuela']=$fila['cara01idescuela'];
		$_REQUEST['cara01fichabiolog']=$fila['cara01fichabiolog'];
		$_REQUEST['cara01nivelbiolog']=$fila['cara01nivelbiolog'];
		$_REQUEST['cara01fichafisica']=$fila['cara01fichafisica'];
		$_REQUEST['cara01nivelfisica']=$fila['cara01nivelfisica'];
		$_REQUEST['cara01fichaquimica']=$fila['cara01fichaquimica'];
		$_REQUEST['cara01nivelquimica']=$fila['cara01nivelquimica'];
		$_REQUEST['cara01tipocaracterizacion']=$fila['cara01tipocaracterizacion'];
		$_REQUEST['cara01perayuda']=$fila['cara01perayuda'];
		$_REQUEST['cara01perotraayuda']=$fila['cara01perotraayuda'];
		$_REQUEST['cara01discsensorialotra']=$fila['cara01discsensorialotra'];
		$_REQUEST['cara01discfisicaotra']=$fila['cara01discfisicaotra'];
		$_REQUEST['cara01disccognitivaotra']=$fila['cara01disccognitivaotra'];
		$_REQUEST['cara01idcursocatedra']=$fila['cara01idcursocatedra'];
		$_REQUEST['cara01idgrupocatedra']=$fila['cara01idgrupocatedra'];
		$_REQUEST['cara01factordescper']=$fila['cara01factordescper'];
		$_REQUEST['cara01factordescpsico']=$fila['cara01factordescpsico'];
		$_REQUEST['cara01factordescinsti']=$fila['cara01factordescinsti'];
		$_REQUEST['cara01factordescacad']=$fila['cara01factordescacad'];
		$_REQUEST['cara01factordesc']=$fila['cara01factordesc'];
		$_REQUEST['cara01criteriodesc']=$fila['cara01criteriodesc'];
		$_REQUEST['cara01desertor']=$fila['cara01desertor'];
		$_REQUEST['cara01factorprincipaldesc']=$fila['cara01factorprincipaldesc'];
		$_REQUEST['cara01psico_puntaje']=$fila['cara01psico_puntaje'];
		$_REQUEST['cara01discversion']=$fila['cara01discversion'];
		$_REQUEST['cara01discv2sensorial']=$fila['cara01discv2sensorial'];
		$_REQUEST['cara02discv2intelectura']=$fila['cara02discv2intelectura'];
		$_REQUEST['cara02discv2fisica']=$fila['cara02discv2fisica'];
		$_REQUEST['cara02discv2psico']=$fila['cara02discv2psico'];
		$_REQUEST['cara02discv2sistemica']=$fila['cara02discv2sistemica'];
		$_REQUEST['cara02discv2sistemicaotro']=$fila['cara02discv2sistemicaotro'];
		$_REQUEST['cara02discv2multiple']=$fila['cara02discv2multiple'];
		$_REQUEST['cara02discv2multipleotro']=$fila['cara02discv2multipleotro'];
		$_REQUEST['cara02talentoexcepcional']=$fila['cara02talentoexcepcional'];
		$_REQUEST['cara01discv2tiene']=$fila['cara01discv2tiene'];
		$_REQUEST['cara01discv2trastaprende']=$fila['cara01discv2trastaprende'];
		$_REQUEST['cara01discv2soporteorigen']=$fila['cara01discv2soporteorigen'];
		$_REQUEST['cara01discv2archivoorigen']=$fila['cara01discv2archivoorigen'];
		$_REQUEST['cara01discv2trastornos']=$fila['cara01discv2trastornos'];
		$_REQUEST['cara01discv2contalento']=$fila['cara01discv2contalento'];
		$_REQUEST['cara01discv2condicionmedica']=$fila['cara01discv2condicionmedica'];
		$_REQUEST['cara01discv2condmeddet']=$fila['cara01discv2condmeddet'];
		$_REQUEST['cara01discv2pruebacoeficiente']=$fila['cara01discv2pruebacoeficiente'];
		$_REQUEST['cara01sexoversion']=$fila['cara01sexoversion'];
		$_REQUEST['cara01sexov1identidadgen']=$fila['cara01sexov1identidadgen'];
		$_REQUEST['cara01sexov1orientasexo']=$fila['cara01sexov1orientasexo'];		
		$_REQUEST['cara01saber11version']=$fila['cara01saber11version'];
		$_REQUEST['cara01saber11v1agno']=$fila['cara01saber11v1agno'];
		$_REQUEST['cara01saber11v1numreg']=$fila['cara01saber11v1numreg'];
		$_REQUEST['cara01saber11v1puntglobal']=$fila['cara01saber11v1puntglobal'];
		$_REQUEST['cara01saber11v1puntleccritica']=$fila['cara01saber11v1puntleccritica'];
		$_REQUEST['cara01saber11v1puntmatematicas']=$fila['cara01saber11v1puntmatematicas'];
		$_REQUEST['cara01saber11v1puntsociales']=$fila['cara01saber11v1puntsociales'];
		$_REQUEST['cara01saber11v1puntciencias']=$fila['cara01saber11v1puntciencias'];
		$_REQUEST['cara01saber11v1puntingles']=$fila['cara01saber11v1puntingles'];		
		$_REQUEST['cara01bienversion']=$fila['cara01bienversion'];
		$_REQUEST['cara01bienv2altoren']=$fila['cara01bienv2altoren'];
		$_REQUEST['cara01bienv2atletismo']=$fila['cara01bienv2atletismo'];
		$_REQUEST['cara01bienv2baloncesto']=$fila['cara01bienv2baloncesto'];
		$_REQUEST['cara01bienv2futbol']=$fila['cara01bienv2futbol'];
		$_REQUEST['cara01bienv2gimnasia']=$fila['cara01bienv2gimnasia'];
		$_REQUEST['cara01bienv2natacion']=$fila['cara01bienv2natacion'];
		$_REQUEST['cara01bienv2voleibol']=$fila['cara01bienv2voleibol'];
		$_REQUEST['cara01bienv2tenis']=$fila['cara01bienv2tenis'];
		$_REQUEST['cara01bienv2paralimpico']=$fila['cara01bienv2paralimpico'];
		$_REQUEST['cara01bienv2otrodeporte']=$fila['cara01bienv2otrodeporte'];
		$_REQUEST['cara01bienv2otrodeportedetalle']=$fila['cara01bienv2otrodeportedetalle'];
		$_REQUEST['cara01bienv2activdanza']=$fila['cara01bienv2activdanza'];
		$_REQUEST['cara01bienv2activmusica']=$fila['cara01bienv2activmusica'];
		$_REQUEST['cara01bienv2activteatro']=$fila['cara01bienv2activteatro'];
		$_REQUEST['cara01bienv2activartes']=$fila['cara01bienv2activartes'];
		$_REQUEST['cara01bienv2activliteratura']=$fila['cara01bienv2activliteratura'];
		$_REQUEST['cara01bienv2activculturalotra']=$fila['cara01bienv2activculturalotra'];
		$_REQUEST['cara01bienv2activculturalotradetalle']=$fila['cara01bienv2activculturalotradetalle'];
		$_REQUEST['cara01bienv2evenfestfolc']=$fila['cara01bienv2evenfestfolc'];
		$_REQUEST['cara01bienv2evenexpoarte']=$fila['cara01bienv2evenexpoarte'];
		$_REQUEST['cara01bienv2evenhistarte']=$fila['cara01bienv2evenhistarte'];
		$_REQUEST['cara01bienv2evengalfoto']=$fila['cara01bienv2evengalfoto'];
		$_REQUEST['cara01bienv2evenliteratura']=$fila['cara01bienv2evenliteratura'];
		$_REQUEST['cara01bienv2eventeatro']=$fila['cara01bienv2eventeatro'];
		$_REQUEST['cara01bienv2evencine']=$fila['cara01bienv2evencine'];
		$_REQUEST['cara01bienv2evenculturalotro']=$fila['cara01bienv2evenculturalotro'];
		$_REQUEST['cara01bienv2evenculturalotrodetalle']=$fila['cara01bienv2evenculturalotrodetalle'];
		$_REQUEST['cara01bienv2emprendimiento']=$fila['cara01bienv2emprendimiento'];
		$_REQUEST['cara01bienv2empresa']=$fila['cara01bienv2empresa'];
		$_REQUEST['cara01bienv2emprenrecursos']=$fila['cara01bienv2emprenrecursos'];
		$_REQUEST['cara01bienv2emprenconocim']=$fila['cara01bienv2emprenconocim'];
		$_REQUEST['cara01bienv2emprenplan']=$fila['cara01bienv2emprenplan'];
		$_REQUEST['cara01bienv2emprenejecutar']=$fila['cara01bienv2emprenejecutar'];
		$_REQUEST['cara01bienv2emprenfortconocim']=$fila['cara01bienv2emprenfortconocim'];
		$_REQUEST['cara01bienv2emprenidentproblema']=$fila['cara01bienv2emprenidentproblema'];
		$_REQUEST['cara01bienv2emprenotro']=$fila['cara01bienv2emprenotro'];
		$_REQUEST['cara01bienv2emprenotrodetalle']=$fila['cara01bienv2emprenotrodetalle'];
		$_REQUEST['cara01bienv2emprenmarketing']=$fila['cara01bienv2emprenmarketing'];
		$_REQUEST['cara01bienv2emprenplannegocios']=$fila['cara01bienv2emprenplannegocios'];
		$_REQUEST['cara01bienv2emprenideas']=$fila['cara01bienv2emprenideas'];
		$_REQUEST['cara01bienv2emprencreacion']=$fila['cara01bienv2emprencreacion'];
		$_REQUEST['cara01bienv2saludfacteconom']=$fila['cara01bienv2saludfacteconom'];
		$_REQUEST['cara01bienv2saludpreocupacion']=$fila['cara01bienv2saludpreocupacion'];
		$_REQUEST['cara01bienv2saludconsumosust']=$fila['cara01bienv2saludconsumosust'];
		$_REQUEST['cara01bienv2saludinsomnio']=$fila['cara01bienv2saludinsomnio'];
		$_REQUEST['cara01bienv2saludclimalab']=$fila['cara01bienv2saludclimalab'];
		$_REQUEST['cara01bienv2saludalimenta']=$fila['cara01bienv2saludalimenta'];
		$_REQUEST['cara01bienv2saludemocion']=$fila['cara01bienv2saludemocion'];
		$_REQUEST['cara01bienv2saludestado']=$fila['cara01bienv2saludestado'];
		$_REQUEST['cara01bienv2saludmedita']=$fila['cara01bienv2saludmedita'];
		$_REQUEST['cara01bienv2crecimedusexual']=$fila['cara01bienv2crecimedusexual'];
		$_REQUEST['cara01bienv2crecimcultciudad']=$fila['cara01bienv2crecimcultciudad'];
		$_REQUEST['cara01bienv2crecimrelpareja']=$fila['cara01bienv2crecimrelpareja'];
		$_REQUEST['cara01bienv2crecimrelinterp']=$fila['cara01bienv2crecimrelinterp'];
		$_REQUEST['cara01bienv2crecimdinamicafam']=$fila['cara01bienv2crecimdinamicafam'];
		$_REQUEST['cara01bienv2crecimautoestima']=$fila['cara01bienv2crecimautoestima'];
		$_REQUEST['cara01bienv2creciminclusion']=$fila['cara01bienv2creciminclusion'];
		$_REQUEST['cara01bienv2creciminteliemoc']=$fila['cara01bienv2creciminteliemoc'];
		$_REQUEST['cara01bienv2crecimcultural']=$fila['cara01bienv2crecimcultural'];
		$_REQUEST['cara01bienv2crecimartistico']=$fila['cara01bienv2crecimartistico'];
		$_REQUEST['cara01bienv2crecimdeporte']=$fila['cara01bienv2crecimdeporte'];
		$_REQUEST['cara01bienv2crecimambiente']=$fila['cara01bienv2crecimambiente'];
		$_REQUEST['cara01bienv2crecimhabsocio']=$fila['cara01bienv2crecimhabsocio'];
		$_REQUEST['cara01bienv2ambienbasura']=$fila['cara01bienv2ambienbasura'];
		$_REQUEST['cara01bienv2ambienreutiliza']=$fila['cara01bienv2ambienreutiliza'];
		$_REQUEST['cara01bienv2ambienluces']=$fila['cara01bienv2ambienluces'];
		$_REQUEST['cara01bienv2ambienfrutaverd']=$fila['cara01bienv2ambienfrutaverd'];
		$_REQUEST['cara01bienv2ambienenchufa']=$fila['cara01bienv2ambienenchufa'];
		$_REQUEST['cara01bienv2ambiengrifo']=$fila['cara01bienv2ambiengrifo'];
		$_REQUEST['cara01bienv2ambienbicicleta']=$fila['cara01bienv2ambienbicicleta'];
		$_REQUEST['cara01bienv2ambientranspub']=$fila['cara01bienv2ambientranspub'];
		$_REQUEST['cara01bienv2ambienducha']=$fila['cara01bienv2ambienducha'];
		$_REQUEST['cara01bienv2ambiencaminata']=$fila['cara01bienv2ambiencaminata'];
		$_REQUEST['cara01bienv2ambiensiembra']=$fila['cara01bienv2ambiensiembra'];
		$_REQUEST['cara01bienv2ambienconferencia']=$fila['cara01bienv2ambienconferencia'];
		$_REQUEST['cara01bienv2ambienrecicla']=$fila['cara01bienv2ambienrecicla'];
		$_REQUEST['cara01bienv2ambienotraactiv']=$fila['cara01bienv2ambienotraactiv'];
		$_REQUEST['cara01bienv2ambienotraactivdetalle']=$fila['cara01bienv2ambienotraactivdetalle'];
		$_REQUEST['cara01bienv2ambienreforest']=$fila['cara01bienv2ambienreforest'];
		$_REQUEST['cara01bienv2ambienmovilidad']=$fila['cara01bienv2ambienmovilidad'];
		$_REQUEST['cara01bienv2ambienclimatico']=$fila['cara01bienv2ambienclimatico'];
		$_REQUEST['cara01bienv2ambienecofemin']=$fila['cara01bienv2ambienecofemin'];
		$_REQUEST['cara01bienv2ambienbiodiver']=$fila['cara01bienv2ambienbiodiver'];
		$_REQUEST['cara01bienv2ambienecologia']=$fila['cara01bienv2ambienecologia'];
		$_REQUEST['cara01bienv2ambieneconomia']=$fila['cara01bienv2ambieneconomia'];
		$_REQUEST['cara01bienv2ambienrecnatura']=$fila['cara01bienv2ambienrecnatura'];
		$_REQUEST['cara01bienv2ambienreciclaje']=$fila['cara01bienv2ambienreciclaje'];
		$_REQUEST['cara01bienv2ambienmascota']=$fila['cara01bienv2ambienmascota'];
		$_REQUEST['cara01bienv2ambiencartohum']=$fila['cara01bienv2ambiencartohum'];
		$_REQUEST['cara01bienv2ambienespiritu']=$fila['cara01bienv2ambienespiritu'];
		$_REQUEST['cara01bienv2ambiencarga']=$fila['cara01bienv2ambiencarga'];
		$_REQUEST['cara01bienv2ambienotroenfoq']=$fila['cara01bienv2ambienotroenfoq'];
		$_REQUEST['cara01bienv2ambienotroenfoqdetalle']=$fila['cara01bienv2ambienotroenfoqdetalle'];
		$_REQUEST['cara01fam_madrecabeza']=$fila['cara01fam_madrecabeza'];
		$_REQUEST['cara01acadhatenidorecesos']=$fila['cara01acadhatenidorecesos'];
		$_REQUEST['cara01acadrazonreceso']=$fila['cara01acadrazonreceso'];
		$_REQUEST['cara01acadrazonrecesodetalle']=$fila['cara01acadrazonrecesodetalle'];
		$_REQUEST['cara01campus_usocorreounad']=$fila['cara01campus_usocorreounad'];
		$_REQUEST['cara01campus_usocorreounadno']=$fila['cara01campus_usocorreounadno'];
		$_REQUEST['cara01campus_usocorreounadnodetalle']=$fila['cara01campus_usocorreounadnodetalle'];
		$_REQUEST['cara01campus_medioactivunad']=$fila['cara01campus_medioactivunad'];
		$_REQUEST['cara01campus_medioactivunaddetalle']=$fila['cara01campus_medioactivunaddetalle'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta2301']=0;
		if (!$bEstudiante){
			$_REQUEST['boculta101']=$fila['cara01fichaper'];
			$_REQUEST['boculta102']=$fila['cara01fichafam'];
			$_REQUEST['boculta103']=$fila['cara01fichaaca'];
			$_REQUEST['boculta104']=$fila['cara01fichalab'];
			$_REQUEST['boculta105']=$fila['cara01fichabien'];
			$_REQUEST['boculta106']=$fila['cara01fichapsico'];
			$_REQUEST['boculta107']=$fila['cara01fichadigital'];
			$_REQUEST['boculta108']=$fila['cara01fichalectura'];
			$_REQUEST['boculta109']=$fila['cara01ficharazona'];
			$_REQUEST['boculta110']=$fila['cara01fichaingles'];
			$_REQUEST['boculta111']=$fila['cara01fichabiolog'];
			$_REQUEST['boculta112']=$fila['cara01fichafisica'];
			$_REQUEST['boculta113']=$fila['cara01fichaquimica'];
			$bDiscapacitado=false;
			if ($_REQUEST['cara01discsensorial']!='N'){$bDiscapacitado=true;}
			if ($_REQUEST['cara01discfisica']!='N'){$bDiscapacitado=true;}
			if ($_REQUEST['cara01disccognitiva']!='N'){$bDiscapacitado=true;}
			if ($_REQUEST['cara01perayuda']!=0){$bDiscapacitado=true;}
			if ($_REQUEST['cara01fechaconfirmadisc']==0){
				if ($bDiscapacitado){$_REQUEST['boculta101']=0;}
				}
			$sSQL='SELECT core01peracainicial FROM core01estprograma WHERE core01idtercero='.$_REQUEST["cara01idtercero"].' AND core01peracainicial='.$_REQUEST["cara01idperaca"].' ORDER BY core01peracainicial DESC';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de verificacion '.$sSQL.'<br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			if($objDB->nf($tabla)==0){
				$_REQUEST['antiguo']='';
				$bAntiguo=true;
				}
			}else{
			}
		$bLimpiaHijos=true;
		if ($_REQUEST['cara01agnos']<=0){
			$sSQL='SELECT unad11fechanace FROM unad11terceros WHERE unad11id ="'.$_REQUEST['cara01idtercero'].'"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				if (fecha_esvalida($fila['unad11fechanace'])){
					$cara01agnos=fecha_edad($fila['unad11fechanace']);
					$_REQUEST['cara01agnos']=$cara01agnos[0];
					$sSQL='UPDATE cara01encuesta SET cara01agnos="'.$_REQUEST['cara01agnos'].'" WHERE cara01id='.$_REQUEST['cara01id'].'';
					$tabla=$objDB->ejecutasql($sSQL);
					}
				}
			}
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['cara01completa']='S';
	$_REQUEST['cara01fechaencuesta']=fecha_DiaMod();
	$bCerrando=true;
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
		$_REQUEST['cara01completa']='S';
		}else{
		$sSQL='UPDATE cara01encuesta SET cara01completa="N" WHERE cara01id='.$_REQUEST['cara01id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['cara01id'], 'Abre Encuesta', $objDB);
		$_REQUEST['cara01completa']='N';
		$sError='<b>El documento ha sido abierto</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
function SiguienteFicha($DATA){
	$iRes=1;
	if ($DATA['ficha']==13){
		$iRes=1;
		}else{
		$iRes=$DATA['ficha']+1;
		if ($iRes==2){if ($DATA['cara01fichafam']==-1){$iRes=7;}}
		if ($iRes==3){if ($DATA['cara01fichaaca']==-1){$iRes=7;}}
		if ($iRes==4){if ($DATA['cara01fichalab']==-1){$iRes=7;}}
		if ($iRes==5){if ($DATA['cara01fichabien']==-1){$iRes=7;}}
		if ($iRes==6){if ($DATA['cara01fichapsico']==-1){$iRes=7;}}
		if ($iRes==7){if ($DATA['cara01fichadigital']==-1){$iRes=8;}}
		if ($iRes==8){if ($DATA['cara01fichalectura']==-1){$iRes=9;}}
		if ($iRes==9){if ($DATA['cara01ficharazona']==-1){$iRes=10;}}
		if ($iRes==10){if ($DATA['cara01fichaingles']==-1){$iRes=11;}}
		if ($iRes==11){if ($DATA['cara01fichabiolog']==-1){$iRes=12;}}
		if ($iRes==12){if ($DATA['cara01fichafisica']==-1){$iRes=13;}}
		if ($iRes==13){if ($DATA['cara01fichaquimica']==-1){$iRes=1;}}
		}
	return $iRes;
	}
function html_2201ContinuarCerrar($id){
	return '<div class="salto1px"></div>
<label class="Label300">&nbsp;</label>
<label class="Label130">
<input id="cmdContinuar'.$id.'" name="cmdContinuar'.$id.'" type="button" value="Continuar" class="BotonAzul" onclick="javascript:enviaguardar()" />
</label>
<label class="Label60">&nbsp;</label>
<label class="Label130">
<input id="cmdCerrar'.$id.'" name="cmdCerrar'.$id.'" type="button" value="Terminar" class="BotonAzul" onclick="javascript:enviacerrar()" />
</label>';
	}
function html_2201Tablero($id){
	return '<div class="salto1px"></div>
<label class="Label300">&nbsp;</label>
<label class="Label130">
<input id="cmdTablero'.$id.'" name="cmdTablero'.$id.'" type="button" value="Mis Cursos" class="BotonAzul" onclick="javascript:irtablero()" />
</label>';
	}
function f2301_NombrePuntaje($sCompetencia,$iValor){
	$sValor='';
	switch($sCompetencia){
		case 'puntaje':
		if($iValor>=24 && $iValor<=30){
			$sValor='Bajo';
			}else{
			if($iValor>=17 && $iValor<=23){
				$sValor='Medio';
				}else{
				if($iValor>=10 && $iValor<=16){
					$sValor='Alto';
					}else{
					$sValor='Sin definir';
					}
				}
			}
		break;
		case 'lectura':
		if($iValor>=0 && $iValor<=40){
			$sValor='Bajo';
			}else{
			if($iValor>=50 && $iValor<=90){
				$sValor='Medio';
				}else{
				if($iValor>=100 && $iValor<=150){
					$sValor='Alto';
					}else{
					$sValor='Sin definir';
					}
				}
			}
		break;
		case 'digital':
		case 'ingles':
		if($iValor>=0 && $iValor<=40){
			$sValor='Bajo';
			}else{
			if($iValor>=50 && $iValor<=80){
				$sValor='Medio';
				}else{
				if($iValor>=90 && $iValor<=120){
					$sValor='Alto';
					}else{
					$sValor='Sin definir';
					}
				}
			}
		break;
		case 'razona':
		case 'biolog':
		case 'fisica':
		case 'quimica':
		if($iValor>=0 && $iValor<=30){
			$sValor='Bajo';
			}else{
			if($iValor>=40 && $iValor<=60){
				$sValor='Medio';
				}else{
				if($iValor>=70 && $iValor<=100){
					$sValor='Alto';
					}else{
					$sValor='Sin definir';
					}
				}
			}
		break;

		}
	return $sValor;
	}
if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Corriendo el paso '.$_REQUEST['paso'].' antes de guardar<br>';}
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f2301_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		if (!$bCerrando){
			$_REQUEST['ficha']=SiguienteFicha($_REQUEST);
			}
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
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f2301_db_Eliminar($_REQUEST['cara01id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//Actualizar las preguntas, por alguna razon ha fallado o es la primer carga...
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=2;
	list($sError, $sDebugP)=f2301_IniciarPreguntas($_REQUEST['cara01id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugP;
	if ($sError==''){
		$sError='Se ha actualizado las preguntas para la prueba';
		$iTipoError=1;
		}
	}
//Confirmar la discapacidad...
if ($_REQUEST['paso']==23){
	require 'lib2301consejero.php';
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f2301_db_GuardarDiscapacidad($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='Se ha ejecutado la confirmacion de los datos de discapacidad';
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['cara01idperaca']='';
	if ($bEstudiante){
		$_REQUEST['cara01idtercero']=$idTercero;
		}else{
		$_REQUEST['cara01idtercero']=0;
		}
	$_REQUEST['cara01idtercero_td']=$APP->tipo_doc;
	$_REQUEST['cara01idtercero_doc']='';
	$_REQUEST['cara01id']='';
	$_REQUEST['cara01completa']='N';
	$_REQUEST['cara01fichaper']=0;
	$_REQUEST['cara01fichafam']=-1;
	$_REQUEST['cara01fichaaca']=-1;
	$_REQUEST['cara01fichalab']=-1;
	$_REQUEST['cara01fichabien']=-1;
	$_REQUEST['cara01fichapsico']=-1;
	$_REQUEST['cara01fichadigital']=-1;
	$_REQUEST['cara01fichalectura']=-1;
	$_REQUEST['cara01ficharazona']=-1;
	$_REQUEST['cara01fichaingles']=-1;
	$_REQUEST['cara01fechaencuesta']='';//fecha_hoy();
	$_REQUEST['cara01agnos']=0;
	$_REQUEST['cara01sexo']='';
	$_REQUEST['cara01pais']=$_SESSION['unad_pais'];
	$_REQUEST['cara01depto']='';
	$_REQUEST['cara01ciudad']='';
	$_REQUEST['cara01nomciudad']='';
	$_REQUEST['cara01direccion']='';
	$_REQUEST['cara01estrato']='';
	$_REQUEST['cara01zonares']='';
	$_REQUEST['cara01estcivil']='';
	$_REQUEST['cara01nomcontacto']='';
	$_REQUEST['cara01parentezcocontacto']='';
	$_REQUEST['cara01celcontacto']='';
	$_REQUEST['cara01correocontacto']='';
	$_REQUEST['cara01idzona']='';
	$_REQUEST['cara01idcead']='';
	$_REQUEST['cara01matconvenio']='N';
	$_REQUEST['cara01raizal']='';
	$_REQUEST['cara01palenquero']='';
	$_REQUEST['cara01afrocolombiano']='';
	$_REQUEST['cara01otracomunnegras']='';
	$_REQUEST['cara01rom']='';
	$_REQUEST['cara01indigenas']=0;
	$_REQUEST['cara01victimadesplazado']='N';
	$_REQUEST['cara01idconfirmadesp']=0;
	$_REQUEST['cara01idconfirmadesp_td']=$APP->tipo_doc;
	$_REQUEST['cara01idconfirmadesp_doc']='';
	$_REQUEST['cara01fechaconfirmadesp']='';//fecha_hoy();
	$_REQUEST['cara01victimaacr']='N';
	$_REQUEST['cara01idconfirmacr']=0;
	$_REQUEST['cara01idconfirmacr_td']=$APP->tipo_doc;
	$_REQUEST['cara01idconfirmacr_doc']='';
	$_REQUEST['cara01fechaconfirmacr']='';//fecha_hoy();
	$_REQUEST['cara01inpecfuncionario']='N';
	$_REQUEST['cara01inpecrecluso']='N';
	$_REQUEST['cara01inpectiempocondena']='';
	$_REQUEST['cara01centroreclusion']=0;
	$_REQUEST['cara01discsensorial']='N';
	$_REQUEST['cara01discfisica']='N';
	$_REQUEST['cara01disccognitiva']='N';
	$_REQUEST['cara01idconfirmadisc']=0;
	$_REQUEST['cara01idconfirmadisc_td']=$APP->tipo_doc;
	$_REQUEST['cara01idconfirmadisc_doc']='';
	$_REQUEST['cara01fechaconfirmadisc']=0;//fecha_hoy();
	$_REQUEST['cara01fam_tipovivienda']='';
	$_REQUEST['cara01fam_vivecon']='';
	$_REQUEST['cara01fam_numpersgrupofam']='';
	$_REQUEST['cara01fam_hijos']='';
	$_REQUEST['cara01fam_personasacargo']='';
	$_REQUEST['cara01fam_dependeecon']='';
	$_REQUEST['cara01fam_escolaridadpadre']='';
	$_REQUEST['cara01fam_escolaridadmadre']='';
	$_REQUEST['cara01fam_numhermanos']='';
	$_REQUEST['cara01fam_posicionherm']='';
	$_REQUEST['cara01fam_familiaunad']='';
	$_REQUEST['cara01acad_tipocolegio']='';
	$_REQUEST['cara01acad_modalidadbach']='';
	$_REQUEST['cara01acad_estudioprev']='';
	$_REQUEST['cara01acad_ultnivelest']='';
	$_REQUEST['cara01acad_obtubodiploma']='';
	$_REQUEST['cara01acad_hatomadovirtual']='';
	$_REQUEST['cara01acad_tiemposinest']='';
	$_REQUEST['cara01acad_razonestudio']='';
	$_REQUEST['cara01acad_primeraopc']='';
	$_REQUEST['cara01acad_programagusto']='';
	$_REQUEST['cara01acad_razonunad']='';
	$_REQUEST['cara01campus_compescrito']='';
	$_REQUEST['cara01campus_portatil']='';
	$_REQUEST['cara01campus_tableta']='';
	$_REQUEST['cara01campus_telefono']='';
	$_REQUEST['cara01campus_energia']='';
	$_REQUEST['cara01campus_internetreside']='';
	$_REQUEST['cara01campus_expvirtual']='';
	$_REQUEST['cara01campus_ofimatica']='';
	$_REQUEST['cara01campus_foros']='';
	$_REQUEST['cara01campus_conversiones']='';
	$_REQUEST['cara01campus_usocorreo']='';
	$_REQUEST['cara01campus_aprendtexto']='';
	$_REQUEST['cara01campus_aprendvideo']='';
	$_REQUEST['cara01campus_aprendmapas']='';
	$_REQUEST['cara01campus_aprendeanima']='';
	$_REQUEST['cara01campus_mediocomunica']='';
	$_REQUEST['cara01lab_situacion']='';
	$_REQUEST['cara01lab_sector']='';
	$_REQUEST['cara01lab_caracterjuri']='';
	$_REQUEST['cara01lab_cargo']='';
	$_REQUEST['cara01lab_antiguedad']='';
	$_REQUEST['cara01lab_tipocontrato']='';
	$_REQUEST['cara01lab_rangoingreso']='';
	$_REQUEST['cara01lab_tiempoacadem']='';
	$_REQUEST['cara01lab_tipoempresa']='';
	$_REQUEST['cara01lab_tiempoindepen']='';
	$_REQUEST['cara01lab_debebusctrab']='';
	$_REQUEST['cara01lab_origendinero']='';
	$_REQUEST['cara01bien_baloncesto']='';
	$_REQUEST['cara01bien_voleibol']='';
	$_REQUEST['cara01bien_futbolsala']='';
	$_REQUEST['cara01bien_artesmarc']='';
	$_REQUEST['cara01bien_tenisdemesa']='';
	$_REQUEST['cara01bien_ajedrez']='';
	$_REQUEST['cara01bien_juegosautoc']='';
	$_REQUEST['cara01bien_interesrepdeporte']='';
	$_REQUEST['cara01bien_deporteint']='';
	$_REQUEST['cara01bien_teatro']='';
	$_REQUEST['cara01bien_danza']='';
	$_REQUEST['cara01bien_musica']='';
	$_REQUEST['cara01bien_circo']='';
	$_REQUEST['cara01bien_artplast']='';
	$_REQUEST['cara01bien_cuenteria']='';
	$_REQUEST['cara01bien_interesreparte']='';
	$_REQUEST['cara01bien_arteint']='';
	$_REQUEST['cara01bien_interpreta']='';
	$_REQUEST['cara01bien_nivelinter']='';
	$_REQUEST['cara01bien_danza_mod']='';
	$_REQUEST['cara01bien_danza_clas']='';
	$_REQUEST['cara01bien_danza_cont']='';
	$_REQUEST['cara01bien_danza_folk']='';
	$_REQUEST['cara01bien_niveldanza']='';
	$_REQUEST['cara01bien_emprendedor']='';
	$_REQUEST['cara01bien_nombreemp']='';
	$_REQUEST['cara01bien_capacempren']='';
	$_REQUEST['cara01bien_tipocapacita']='';
	$_REQUEST['cara01bien_impvidasalud']='';
	$_REQUEST['cara01bien_estraautocuid']='';
	$_REQUEST['cara01bien_pv_personal']='';
	$_REQUEST['cara01bien_pv_familiar']='';
	$_REQUEST['cara01bien_pv_academ']='';
	$_REQUEST['cara01bien_pv_labora']='';
	$_REQUEST['cara01bien_pv_pareja']='';
	$_REQUEST['cara01bien_amb']='';
	$_REQUEST['cara01bien_amb_agu']='';
	$_REQUEST['cara01bien_amb_bom']='';
	$_REQUEST['cara01bien_amb_car']='';
	$_REQUEST['cara01bien_amb_info']='';
	$_REQUEST['cara01bien_amb_temas']='';
	$_REQUEST['cara01psico_costoemocion']='';
	$_REQUEST['cara01psico_reaccionimpre']='';
	$_REQUEST['cara01psico_estres']='';
	$_REQUEST['cara01psico_pocotiempo']='';
	$_REQUEST['cara01psico_actitudvida']='';
	$_REQUEST['cara01psico_duda']='';
	$_REQUEST['cara01psico_problemapers']='';
	$_REQUEST['cara01psico_satisfaccion']='';
	$_REQUEST['cara01psico_discusiones']='';
	$_REQUEST['cara01psico_atencion']='';
	$_REQUEST['cara01niveldigital']='';
	$_REQUEST['cara01nivellectura']='';
	$_REQUEST['cara01nivelrazona']='';
	$_REQUEST['cara01nivelingles']='';
	$_REQUEST['cara01idconsejero']=0;
	$_REQUEST['cara01idconsejero_td']=$APP->tipo_doc;
	$_REQUEST['cara01idconsejero_doc']='';
	$_REQUEST['cara01fechainicio']='';//fecha_hoy();
	$_REQUEST['cara01telefono1']='';
	$_REQUEST['cara01telefono2']='';
	$_REQUEST['cara01correopersonal']='';
	$_REQUEST['cara01idprograma']=0;
	$_REQUEST['cara01idescuela']=0;
	$_REQUEST['cara01fichabiolog']=-1;
	$_REQUEST['cara01nivelbiolog']=0;
	$_REQUEST['cara01fichafisica']=-1;
	$_REQUEST['cara01nivelfisica']=0;
	$_REQUEST['cara01fichaquimica']=-1;
	$_REQUEST['cara01nivelquimica']=0;
	$_REQUEST['cara01tipocaracterizacion']=0;
	$_REQUEST['cara01perayuda']='';
	$_REQUEST['cara01perotraayuda']='';
	$_REQUEST['cara01discsensorialotra']='';
	$_REQUEST['cara01discfisicaotra']='';
	$_REQUEST['cara01disccognitivaotra']='';
	$_REQUEST['cara01idcursocatedra']=0;
	$_REQUEST['cara01idgrupocatedra']=0;
	$_REQUEST['cara01factordescper']=0;
	$_REQUEST['cara01factordescpsico']=0;
	$_REQUEST['cara01factordescinsti']=0;
	$_REQUEST['cara01factordescacad']=0;
	$_REQUEST['cara01factordesc']=0;
	$_REQUEST['cara01criteriodesc']=0;
	$_REQUEST['cara01desertor']='N';
	$_REQUEST['cara01factorprincipaldesc']=0;
	$_REQUEST['cara01psico_puntaje']=0;
	$_REQUEST['cara01discversion']=2;
	$_REQUEST['cara01discv2sensorial']='';
	$_REQUEST['cara02discv2intelectura']='';
	$_REQUEST['cara02discv2fisica']='';
	$_REQUEST['cara02discv2psico']='';
	$_REQUEST['cara02discv2sistemica']='';
	$_REQUEST['cara02discv2sistemicaotro']='';
	$_REQUEST['cara02discv2multiple']='';
	$_REQUEST['cara02discv2multipleotro']='';
	$_REQUEST['cara02talentoexcepcional']='';
	$_REQUEST['cara01discv2tiene']=0;
	$_REQUEST['cara01discv2trastaprende']=0;
	$_REQUEST['cara01discv2soporteorigen']=0;
	$_REQUEST['cara01discv2archivoorigen']=0;
	$_REQUEST['cara01discv2trastornos']=0;
	$_REQUEST['cara01discv2contalento']=0;
	$_REQUEST['cara01discv2condicionmedica']=0;
	$_REQUEST['cara01discv2condmeddet']='';
	$_REQUEST['cara01discv2pruebacoeficiente']=0;
	$_REQUEST['cara01sexoversion']=1;
	$_REQUEST['cara01sexov1identidadgen']='';
	$_REQUEST['cara01sexov1orientasexo']='';	
	$_REQUEST['cara01saber11version']=1;
	$_REQUEST['cara01saber11v1agno']=0;
	$_REQUEST['cara01saber11v1numreg']='';
	$_REQUEST['cara01saber11v1puntglobal']=0;
	$_REQUEST['cara01saber11v1puntleccritica']=0;
	$_REQUEST['cara01saber11v1puntmatematicas']=0;
	$_REQUEST['cara01saber11v1puntsociales']=0;
	$_REQUEST['cara01saber11v1puntciencias']=0;
	$_REQUEST['cara01saber11v1puntingles']=0;	
	$_REQUEST['cara01bienversion']=2;
	$_REQUEST['cara01bienv2altoren']='';
	$_REQUEST['cara01bienv2atletismo']='';
	$_REQUEST['cara01bienv2baloncesto']='';
	$_REQUEST['cara01bienv2futbol']='';
	$_REQUEST['cara01bienv2gimnasia']='';
	$_REQUEST['cara01bienv2natacion']='';
	$_REQUEST['cara01bienv2voleibol']='';
	$_REQUEST['cara01bienv2tenis']='';
	$_REQUEST['cara01bienv2paralimpico']='';
	$_REQUEST['cara01bienv2otrodeporte']='';
	$_REQUEST['cara01bienv2otrodeportedetalle']='';
	$_REQUEST['cara01bienv2activdanza']='';
	$_REQUEST['cara01bienv2activmusica']='';
	$_REQUEST['cara01bienv2activteatro']='';
	$_REQUEST['cara01bienv2activartes']='';
	$_REQUEST['cara01bienv2activliteratura']='';
	$_REQUEST['cara01bienv2activculturalotra']='';
	$_REQUEST['cara01bienv2activculturalotradetalle']='';
	$_REQUEST['cara01bienv2evenfestfolc']='';
	$_REQUEST['cara01bienv2evenexpoarte']='';
	$_REQUEST['cara01bienv2evenhistarte']='';
	$_REQUEST['cara01bienv2evengalfoto']='';
	$_REQUEST['cara01bienv2evenliteratura']='';
	$_REQUEST['cara01bienv2eventeatro']='';
	$_REQUEST['cara01bienv2evencine']='';
	$_REQUEST['cara01bienv2evenculturalotro']='';
	$_REQUEST['cara01bienv2evenculturalotrodetalle']='';
	$_REQUEST['cara01bienv2emprendimiento']='';
	$_REQUEST['cara01bienv2empresa']='';
	$_REQUEST['cara01bienv2emprenrecursos']='';
	$_REQUEST['cara01bienv2emprenconocim']='';
	$_REQUEST['cara01bienv2emprenplan']='';
	$_REQUEST['cara01bienv2emprenejecutar']='';
	$_REQUEST['cara01bienv2emprenfortconocim']='';
	$_REQUEST['cara01bienv2emprenidentproblema']='';
	$_REQUEST['cara01bienv2emprenotro']='';
	$_REQUEST['cara01bienv2emprenotrodetalle']='';
	$_REQUEST['cara01bienv2emprenmarketing']='';
	$_REQUEST['cara01bienv2emprenplannegocios']='';
	$_REQUEST['cara01bienv2emprenideas']='';
	$_REQUEST['cara01bienv2emprencreacion']='';
	$_REQUEST['cara01bienv2saludfacteconom']='';
	$_REQUEST['cara01bienv2saludpreocupacion']='';
	$_REQUEST['cara01bienv2saludconsumosust']='';
	$_REQUEST['cara01bienv2saludinsomnio']='';
	$_REQUEST['cara01bienv2saludclimalab']='';
	$_REQUEST['cara01bienv2saludalimenta']='';
	$_REQUEST['cara01bienv2saludemocion']='';
	$_REQUEST['cara01bienv2saludestado']='';
	$_REQUEST['cara01bienv2saludmedita']='';
	$_REQUEST['cara01bienv2crecimedusexual']='';
	$_REQUEST['cara01bienv2crecimcultciudad']='';
	$_REQUEST['cara01bienv2crecimrelpareja']='';
	$_REQUEST['cara01bienv2crecimrelinterp']='';
	$_REQUEST['cara01bienv2crecimdinamicafam']='';
	$_REQUEST['cara01bienv2crecimautoestima']='';
	$_REQUEST['cara01bienv2creciminclusion']='';
	$_REQUEST['cara01bienv2creciminteliemoc']='';
	$_REQUEST['cara01bienv2crecimcultural']='';
	$_REQUEST['cara01bienv2crecimartistico']='';
	$_REQUEST['cara01bienv2crecimdeporte']='';
	$_REQUEST['cara01bienv2crecimambiente']='';
	$_REQUEST['cara01bienv2crecimhabsocio']='';
	$_REQUEST['cara01bienv2ambienbasura']='';
	$_REQUEST['cara01bienv2ambienreutiliza']='';
	$_REQUEST['cara01bienv2ambienluces']='';
	$_REQUEST['cara01bienv2ambienfrutaverd']='';
	$_REQUEST['cara01bienv2ambienenchufa']='';
	$_REQUEST['cara01bienv2ambiengrifo']='';
	$_REQUEST['cara01bienv2ambienbicicleta']='';
	$_REQUEST['cara01bienv2ambientranspub']='';
	$_REQUEST['cara01bienv2ambienducha']='';
	$_REQUEST['cara01bienv2ambiencaminata']='';
	$_REQUEST['cara01bienv2ambiensiembra']='';
	$_REQUEST['cara01bienv2ambienconferencia']='';
	$_REQUEST['cara01bienv2ambienrecicla']='';
	$_REQUEST['cara01bienv2ambienotraactiv']='';
	$_REQUEST['cara01bienv2ambienotraactivdetalle']='';
	$_REQUEST['cara01bienv2ambienreforest']='';
	$_REQUEST['cara01bienv2ambienmovilidad']='';
	$_REQUEST['cara01bienv2ambienclimatico']='';
	$_REQUEST['cara01bienv2ambienecofemin']='';
	$_REQUEST['cara01bienv2ambienbiodiver']='';
	$_REQUEST['cara01bienv2ambienecologia']='';
	$_REQUEST['cara01bienv2ambieneconomia']='';
	$_REQUEST['cara01bienv2ambienrecnatura']='';
	$_REQUEST['cara01bienv2ambienreciclaje']='';
	$_REQUEST['cara01bienv2ambienmascota']='';
	$_REQUEST['cara01bienv2ambiencartohum']='';
	$_REQUEST['cara01bienv2ambienespiritu']='';
	$_REQUEST['cara01bienv2ambiencarga']='';
	$_REQUEST['cara01bienv2ambienotroenfoq']='';
	$_REQUEST['cara01bienv2ambienotroenfoqdetalle']='';
	$_REQUEST['cara01fam_madrecabeza']='';
	$_REQUEST['cara01acadhatenidorecesos']='';
	$_REQUEST['cara01acadrazonreceso']=0;
	$_REQUEST['cara01acadrazonrecesodetalle']='';
	$_REQUEST['cara01campus_usocorreounad']=0;
	$_REQUEST['cara01campus_usocorreounadno']=0;
	$_REQUEST['cara01campus_usocorreounadnodetalle']='';
	$_REQUEST['cara01campus_medioactivunad']=0;
	$_REQUEST['cara01campus_medioactivunaddetalle']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (!$bEstudiante){
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
//if ($bDevuelve){$seg_6=1;}
	if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
	}
if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
list($cara01idtercero_rs, $_REQUEST['cara01idtercero'], $_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc'])=html_tercero($_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc'], $_REQUEST['cara01idtercero'], 0, $objDB);
$objCombos->nuevo('cara01sexo', $_REQUEST['cara01sexo'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem('M','Hombre');
$objCombos->addItem('F','Mujer');
$objCombos->addItem('I','Intersexual');
$objCombos->sAccion='ajustar_fam_hijos()';
$html_cara01sexo=$objCombos->html('',$objDB);
//$html_cara01sexo=$objCombos->html('SELECT unad22codopcion AS id, unad22nombre AS nombre FROM unad22combos WHERE unad22idmodulo=111 AND unad22consec=1 AND unad22activa="S" ORDER BY unad22orden', $objDB);
$objCombos->nuevo('cara01sexov1identidadgen', $_REQUEST['cara01sexov1identidadgen'], true, '{'.$ETI['msg_seleccione'].'}', 0);
$objCombos->addArreglo($acara01sexov1identidadgen, $icara01sexov1identidadgen);
$html_cara01sexov1identidadgen=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01sexov1orientasexo', $_REQUEST['cara01sexov1orientasexo'], true, '{'.$ETI['msg_seleccione'].'}', 0);
$objCombos->addArreglo($acara01sexov1orientasexo, $icara01sexov1orientasexo);
$html_cara01sexov1orientasexo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01pais', $_REQUEST['cara01pais'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_cara01depto();';
$html_cara01pais=$objCombos->html('SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre', $objDB);
$html_cara01depto=f2301_HTMLComboV2_cara01depto($objDB, $objCombos, $_REQUEST['cara01depto'], $_REQUEST['cara01pais']);
$html_cara01ciudad=f2301_HTMLComboV2_cara01ciudad($objDB, $objCombos, $_REQUEST['cara01ciudad'], $_REQUEST['cara01depto']);
$objCombos->nuevo('cara01estrato', $_REQUEST['cara01estrato'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($aestrato, $iestrato);
$html_cara01estrato=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01zonares', $_REQUEST['cara01zonares'], true);
$objCombos->addItem('U', 'Urbana');
$objCombos->addItem('R', 'Rural');
$html_cara01zonares=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01estcivil', $_REQUEST['cara01estcivil'], true, '{'.$ETI['msg_seleccione'].'}');
$html_cara01estcivil=$objCombos->html('SELECT unad21codigo AS id, unad21nombre AS nombre FROM unad21estadocivil ORDER BY unad21nombre', $objDB);
$objCombos->nuevo('cara01idzona', $_REQUEST['cara01idzona'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_cara01idcead();';
$html_cara01idzona=$objCombos->html('SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre', $objDB);
$html_cara01idcead=f2301_HTMLComboV2_cara01idcead($objDB, $objCombos, $_REQUEST['cara01idcead'], $_REQUEST['cara01idzona']);
$objCombos->nuevo('cara01matconvenio', $_REQUEST['cara01matconvenio'], false);
$objCombos->sino();
$html_cara01matconvenio=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01raizal', $_REQUEST['cara01raizal'], true, '');
$objCombos->sino();
$html_cara01raizal=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01palenquero', $_REQUEST['cara01palenquero'], true, '');
$objCombos->sino();
$html_cara01palenquero=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01afrocolombiano', $_REQUEST['cara01afrocolombiano'], true, '');
$objCombos->sino();
$html_cara01afrocolombiano=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01otracomunnegras', $_REQUEST['cara01otracomunnegras'], true, '');
$objCombos->sino();
$html_cara01otracomunnegras=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01rom', $_REQUEST['cara01rom'], true, '');
$objCombos->sino();
$html_cara01rom=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01indigenas', $_REQUEST['cara01indigenas'], true, 'No', 0);
$html_cara01indigenas=$objCombos->html('SELECT cara02id AS id, cara02nombre AS nombre FROM cara02indigenas ORDER BY cara02nombre', $objDB);
$objCombos->nuevo('cara01victimadesplazado', $_REQUEST['cara01victimadesplazado'], false);
$objCombos->sino();
$html_cara01victimadesplazado=$objCombos->html('', $objDB);
list($cara01idconfirmadesp_rs, $_REQUEST['cara01idconfirmadesp'], $_REQUEST['cara01idconfirmadesp_td'], $_REQUEST['cara01idconfirmadesp_doc'])=html_tercero($_REQUEST['cara01idconfirmadesp_td'], $_REQUEST['cara01idconfirmadesp_doc'], $_REQUEST['cara01idconfirmadesp'], 0, $objDB);
$objCombos->nuevo('cara01victimaacr', $_REQUEST['cara01victimaacr'], false);
$objCombos->sino();
$html_cara01victimaacr=$objCombos->html('', $objDB);
list($cara01idconfirmacr_rs, $_REQUEST['cara01idconfirmacr'], $_REQUEST['cara01idconfirmacr_td'], $_REQUEST['cara01idconfirmacr_doc'])=html_tercero($_REQUEST['cara01idconfirmacr_td'], $_REQUEST['cara01idconfirmacr_doc'], $_REQUEST['cara01idconfirmacr'], 0, $objDB);
$objCombos->nuevo('cara01inpecfuncionario', $_REQUEST['cara01inpecfuncionario'], false);
$objCombos->sino();
$html_cara01inpecfuncionario=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01inpecrecluso', $_REQUEST['cara01inpecrecluso'], false);
$objCombos->sino();
$objCombos->sAccion='verrecluso()';
$html_cara01inpecrecluso=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01inpectiempocondena', $_REQUEST['cara01inpectiempocondena'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(1, 'Menos de un a&ntilde;o');
$objCombos->addItem(3, 'Entre uno y tres a&ntilde;os');
$objCombos->addItem(5, 'Entre cuatro y cinco a&ntilde;os');
$objCombos->addItem(6, 'Mas de cinco a&ntilde;os');
//$objCombos->addArreglo($acara01inpectiempocondena, $icara01inpectiempocondena);
$html_cara01inpectiempocondena=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01centroreclusion', $_REQUEST['cara01centroreclusion'], true, '{'.$ETI['msg_ninguno'].'}', 0);
$html_cara01centroreclusion=$objCombos->html('SELECT cara03id AS id, cara03nombre AS nombre FROM cara03centroreclusion ORDER BY cara03nombre', $objDB);
$objCombos->nuevo('cara01saber11v1agno', $_REQUEST['cara01saber11v1agno'], true, $ETI['msg_seleccione'], 0);
$objCombos->numeros(1980, fecha_agno(), 1);
$objCombos->sAccion='ajustar_saber11v1()';
$html_cara01saber11v1agno=$objCombos->html('', $objDB);
if ($_REQUEST['cara01discversion']==0){
	$objCombos->nuevo('cara01discsensorial', $_REQUEST['cara01discsensorial'], true, $ETI['no'], 'N');
	$objCombos->addArreglo($acara01discsensorial, $icara01discsensorial);
	$objCombos->sAccion='ajustar_discsensorial()';
	$html_cara01discsensorial=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01discfisica', $_REQUEST['cara01discfisica'], true, $ETI['no'], 'N');
	$objCombos->addArreglo($acara01discfisica, $icara01discfisica);
	$objCombos->sAccion='ajustar_discfisica()';
	$html_cara01discfisica=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01disccognitiva', $_REQUEST['cara01disccognitiva'], true, $ETI['no'], 'N');
	$objCombos->addArreglo($acara01disccognitiva, $icara01disccognitiva);
	$objCombos->sAccion='ajustar_disccognitiva()';
	$html_cara01disccognitiva=$objCombos->html('', $objDB);
	}
list($cara01idconfirmadisc_rs, $_REQUEST['cara01idconfirmadisc'], $_REQUEST['cara01idconfirmadisc_td'], $_REQUEST['cara01idconfirmadisc_doc'])=html_tercero($_REQUEST['cara01idconfirmadisc_td'], $_REQUEST['cara01idconfirmadisc_doc'], $_REQUEST['cara01idconfirmadisc'], 0, $objDB);
$objCombos->nuevo('cara01fam_tipovivienda', $_REQUEST['cara01fam_tipovivienda'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($afam_tipovivienda, $ifam_tipovivienda);
$html_cara01fam_tipovivienda=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_vivecon', $_REQUEST['cara01fam_vivecon'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($afam_vivecon, $ifam_vivecon);
$html_cara01fam_vivecon=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_numpersgrupofam', $_REQUEST['cara01fam_numpersgrupofam'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($afam_numpersgrupofam, $ifam_numpersgrupofam);
$html_cara01fam_numpersgrupofam=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_hijos', $_REQUEST['cara01fam_hijos'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($afam_hijos, $ifam_hijos);
$objCombos->sAccion='ajustar_fam_hijos()';
$html_cara01fam_hijos=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_madrecabeza', $_REQUEST['cara01fam_madrecabeza'], true, '');
$objCombos->sino();
$html_cara01fam_madrecabeza=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_personasacargo', $_REQUEST['cara01fam_personasacargo'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($afam_personasacargo, $ifam_personasacargo);
$html_cara01fam_personasacargo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_dependeecon', $_REQUEST['cara01fam_dependeecon'], true, '');
$objCombos->sino();
$html_cara01fam_dependeecon=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_escolaridadpadre', $_REQUEST['cara01fam_escolaridadpadre'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($aescolaridad, $iescolaridad);
$html_cara01fam_escolaridadpadre=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_escolaridadmadre', $_REQUEST['cara01fam_escolaridadmadre'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($aescolaridad, $iescolaridad);
$html_cara01fam_escolaridadmadre=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_numhermanos', $_REQUEST['cara01fam_numhermanos'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($afam_numhermanos, $ifam_numhermanos);
$html_cara01fam_numhermanos=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_posicionherm', $_REQUEST['cara01fam_posicionherm'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($afam_posicionherm, $ifam_posicionherm);
$html_cara01fam_posicionherm=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_familiaunad', $_REQUEST['cara01fam_familiaunad'], true, '');
$objCombos->sino();
$html_cara01fam_familiaunad=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_tipocolegio', $_REQUEST['cara01acad_tipocolegio'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($aacad_tipocolegio, $iacad_tipocolegio);
$html_cara01acad_tipocolegio=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_modalidadbach', $_REQUEST['cara01acad_modalidadbach'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($aacad_modalidadbach, $iacad_modalidadbach);
$html_cara01acad_modalidadbach=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_estudioprev', $_REQUEST['cara01acad_estudioprev'], true, '');
$objCombos->sino();
$html_cara01acad_estudioprev=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_ultnivelest', $_REQUEST['cara01acad_ultnivelest'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($aacad_ultnivelest, $iacad_ultnivelest);
$html_cara01acad_ultnivelest=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_obtubodiploma', $_REQUEST['cara01acad_obtubodiploma'], true, '');
$objCombos->sino();
$html_cara01acad_obtubodiploma=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_hatomadovirtual', $_REQUEST['cara01acad_hatomadovirtual'], true, '');
$objCombos->sino();
$html_cara01acad_hatomadovirtual=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_tiemposinest', $_REQUEST['cara01acad_tiemposinest'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01acad_tiemposinest, $icara01acad_tiemposinest);
$html_cara01acad_tiemposinest=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_razonestudio', $_REQUEST['cara01acad_razonestudio'], true, '{'.$ETI['msg_seleccione'].'}');
$html_cara01acad_razonestudio=$objCombos->html('SELECT cara04id AS id, cara04nombre AS nombre FROM cara04razonestudio ORDER BY cara04orden, cara04nombre', $objDB);
$objCombos->nuevo('cara01acad_primeraopc', $_REQUEST['cara01acad_primeraopc'], true, '');
$objCombos->sino();
$objCombos->sAccion='ajustarprimeraopc()';
$html_cara01acad_primeraopc=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_razonunad', $_REQUEST['cara01acad_razonunad'], true, '{'.$ETI['msg_seleccione'].'}');
$html_cara01acad_razonunad=$objCombos->html('SELECT cara05id AS id, cara05nombre AS nombre FROM cara05razonunad ORDER BY cara05orden, cara05nombre', $objDB);
$objCombos->nuevo('cara01acadhatenidorecesos', $_REQUEST['cara01acadhatenidorecesos'], true, '');
$objCombos->sino();
$objCombos->sAccion='ajustar_acadhatenidorecesos()';
$html_cara01acadhatenidorecesos=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01acadrazonreceso', $_REQUEST['cara01acadrazonreceso'], true, '{'.$ETI['msg_seleccione'].'}', 0);
$objCombos->addArreglo($acara01acadrazonreceso, $icara01acadrazonreceso);
$objCombos->sAccion='ajustar_acadrazonreceso()';
$html_cara01acadrazonreceso=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_compescrito', $_REQUEST['cara01campus_compescrito'], true, '');
$objCombos->sino();
$html_cara01campus_compescrito=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_portatil', $_REQUEST['cara01campus_portatil'], true, '');
$objCombos->sino();
$html_cara01campus_portatil=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_tableta', $_REQUEST['cara01campus_tableta'], true, '');
$objCombos->sino();
$html_cara01campus_tableta=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_telefono', $_REQUEST['cara01campus_telefono'], true, '');
$objCombos->sino();
$html_cara01campus_telefono=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_energia', $_REQUEST['cara01campus_energia'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01campus_energia, $icara01campus_energia);
$html_cara01campus_energia=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_internetreside', $_REQUEST['cara01campus_internetreside'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01campus_internetreside, $icara01campus_internetreside);
$html_cara01campus_internetreside=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_expvirtual', $_REQUEST['cara01campus_expvirtual'], true, '');
$objCombos->sino();
$html_cara01campus_expvirtual=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_ofimatica', $_REQUEST['cara01campus_ofimatica'], true, '');
$objCombos->sino();
$html_cara01campus_ofimatica=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_foros', $_REQUEST['cara01campus_foros'], true, '');
$objCombos->sino();
$html_cara01campus_foros=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_conversiones', $_REQUEST['cara01campus_conversiones'], true, '');
$objCombos->sino();
$html_cara01campus_conversiones=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_usocorreo', $_REQUEST['cara01campus_usocorreo'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01campus_usocorreo, $icara01campus_usocorreo);
$html_cara01campus_usocorreo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_usocorreounad', $_REQUEST['cara01campus_usocorreounad'], true, '{'.$ETI['msg_seleccione'].'}', 0);
$objCombos->addArreglo($acara01campus_usocorreounad, $icara01campus_usocorreounad);
$objCombos->sAccion='ajustar_campus_usocorreounad()';
$html_cara01campus_usocorreounad=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_usocorreounadno', $_REQUEST['cara01campus_usocorreounadno'], true, '{'.$ETI['msg_seleccione'].'}', 0);
$objCombos->addArreglo($acara01campus_usocorreounadno, $icara01campus_usocorreounadno);
$objCombos->sAccion='ajustar_campus_usocorreounadno()';
$html_cara01campus_usocorreounadno=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_aprendtexto', $_REQUEST['cara01campus_aprendtexto'], true, '');
$objCombos->sino();
$html_cara01campus_aprendtexto=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_aprendvideo', $_REQUEST['cara01campus_aprendvideo'], true, '');
$objCombos->sino();
$html_cara01campus_aprendvideo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_aprendmapas', $_REQUEST['cara01campus_aprendmapas'], true, '');
$objCombos->sino();
$html_cara01campus_aprendmapas=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_aprendeanima', $_REQUEST['cara01campus_aprendeanima'], true, '');
$objCombos->sino();
$html_cara01campus_aprendeanima=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_mediocomunica', $_REQUEST['cara01campus_mediocomunica'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01campus_mediocomunica, $icara01campus_mediocomunica);
$html_cara01campus_mediocomunica=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_medioactivunad', $_REQUEST['cara01campus_medioactivunad'], true, '{'.$ETI['msg_seleccione'].'}', 0);
$objCombos->addArreglo($acara01campus_medioactivunad, $icara01campus_medioactivunad);
$objCombos->sAccion='ajustar_campus_medioactivunad()';
$html_cara01campus_medioactivunad=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_situacion', $_REQUEST['cara01lab_situacion'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_situacion, $icara01lab_situacion);
$objCombos->sAccion='ajustarlaboral()';
$html_cara01lab_situacion=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_sector', $_REQUEST['cara01lab_sector'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_sector, $icara01lab_sector);
$html_cara01lab_sector=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_caracterjuri', $_REQUEST['cara01lab_caracterjuri'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_caracterjuri, $icara01lab_caracterjuri);
$html_cara01lab_caracterjuri=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_cargo', $_REQUEST['cara01lab_cargo'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_cargo, $icara01lab_cargo);
$html_cara01lab_cargo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_antiguedad', $_REQUEST['cara01lab_antiguedad'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_antiguedad, $icara01lab_antiguedad);
$html_cara01lab_antiguedad=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_tipocontrato', $_REQUEST['cara01lab_tipocontrato'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_tipocontrato, $icara01lab_tipocontrato);
$html_cara01lab_tipocontrato=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_rangoingreso', $_REQUEST['cara01lab_rangoingreso'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_rangoingreso, $icara01lab_rangoingreso);
$html_cara01lab_rangoingreso=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_tiempoacadem', $_REQUEST['cara01lab_tiempoacadem'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_tiempoacadem, $icara01lab_tiempoacadem);
$html_cara01lab_tiempoacadem=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_tipoempresa', $_REQUEST['cara01lab_tipoempresa'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_tipoempresa, $icara01lab_tipoempresa);
$html_cara01lab_tipoempresa=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_tiempoindepen', $_REQUEST['cara01lab_tiempoindepen'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_tiempoindepen, $icara01lab_tiempoindepen);
$html_cara01lab_tiempoindepen=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_debebusctrab', $_REQUEST['cara01lab_debebusctrab'], true, '');
$objCombos->sino();
$html_cara01lab_debebusctrab=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_origendinero', $_REQUEST['cara01lab_origendinero'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($acara01lab_origendinero, $icara01lab_origendinero);
$html_cara01lab_origendinero=$objCombos->html('', $objDB);
if ($_REQUEST['cara01bienversion']==0){
	$objCombos->nuevo('cara01bien_baloncesto', $_REQUEST['cara01bien_baloncesto'], true, '');
	$objCombos->sino();
	$html_cara01bien_baloncesto=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_voleibol', $_REQUEST['cara01bien_voleibol'], true, '');
	$objCombos->sino();
	$html_cara01bien_voleibol=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_futbolsala', $_REQUEST['cara01bien_futbolsala'], true, '');
	$objCombos->sino();
	$html_cara01bien_futbolsala=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_artesmarc', $_REQUEST['cara01bien_artesmarc'], true, '');
	$objCombos->sino();
	$html_cara01bien_artesmarc=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_tenisdemesa', $_REQUEST['cara01bien_tenisdemesa'], true, '');
	$objCombos->sino();
	$html_cara01bien_tenisdemesa=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_ajedrez', $_REQUEST['cara01bien_ajedrez'], true, '');
	$objCombos->sino();
	$html_cara01bien_ajedrez=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_juegosautoc', $_REQUEST['cara01bien_juegosautoc'], true, '');
	$objCombos->sino();
	$html_cara01bien_juegosautoc=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_interesrepdeporte', $_REQUEST['cara01bien_interesrepdeporte'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustarinteresrepdeporte();';
	$html_cara01bien_interesrepdeporte=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_teatro', $_REQUEST['cara01bien_teatro'], true, '');
	$objCombos->sino();
	$html_cara01bien_teatro=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza', $_REQUEST['cara01bien_danza'], true, '');
	$objCombos->sino();
	$html_cara01bien_danza=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_musica', $_REQUEST['cara01bien_musica'], true, '');
	$objCombos->sino();
	$html_cara01bien_musica=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_circo', $_REQUEST['cara01bien_circo'], true, '');
	$objCombos->sino();
	$html_cara01bien_circo=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_artplast', $_REQUEST['cara01bien_artplast'], true, '');
	$objCombos->sino();
	$html_cara01bien_artplast=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_cuenteria', $_REQUEST['cara01bien_cuenteria'], true, '');
	$objCombos->sino();
	$html_cara01bien_cuenteria=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_interesreparte', $_REQUEST['cara01bien_interesreparte'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustarinteresreparte()';
	$html_cara01bien_interesreparte=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_interpreta', $_REQUEST['cara01bien_interpreta'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addArreglo($acara01bien_interpreta, $icara01bien_interpreta);
	$objCombos->addItem('-1', 'Ninguno de estos');
	$objCombos->sAccion='ajustarnivelinterpreta()';
	$html_cara01bien_interpreta=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_nivelinter', $_REQUEST['cara01bien_nivelinter'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addArreglo($acara01bien_nivelinter, $icara01bien_nivelinter);
	$html_cara01bien_nivelinter=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza_mod', $_REQUEST['cara01bien_danza_mod'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustardanza()';
	$html_cara01bien_danza_mod=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza_clas', $_REQUEST['cara01bien_danza_clas'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustardanza()';
	$html_cara01bien_danza_clas=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza_cont', $_REQUEST['cara01bien_danza_cont'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustardanza()';
	$html_cara01bien_danza_cont=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza_folk', $_REQUEST['cara01bien_danza_folk'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustardanza()';
	$html_cara01bien_danza_folk=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_niveldanza', $_REQUEST['cara01bien_niveldanza'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addArreglo($acara01bien_niveldanza, $icara01bien_niveldanza);
	$html_cara01bien_niveldanza=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_emprendedor', $_REQUEST['cara01bien_emprendedor'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustarnombreemp()';
	$html_cara01bien_emprendedor=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_capacempren', $_REQUEST['cara01bien_capacempren'], true, '{'.$ETI['msg_ninguna'].'}', 0);
	//$objCombos->sino();
	//$objCombos->sAccion='ajustartipocapacita()';
	$objCombos->addArreglo($acara01bien_capacempren, $icara01bien_capacempren);
	$html_cara01bien_capacempren=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_impvidasalud', $_REQUEST['cara01bien_impvidasalud'], true, '');
	$objCombos->addArreglo($acara01bien_impvidasalud, $icara01bien_impvidasalud);
	$html_cara01bien_impvidasalud=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_estraautocuid', $_REQUEST['cara01bien_estraautocuid'], true, '');
	$objCombos->addArreglo($acara01bien_estraautocuid, $icara01bien_estraautocuid);
	$html_cara01bien_estraautocuid=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_personal', $_REQUEST['cara01bien_pv_personal'], true, '');
	$objCombos->addArreglo($acara01bien_pv_personal, $icara01bien_pv_personal);
	$html_cara01bien_pv_personal=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_familiar', $_REQUEST['cara01bien_pv_familiar'], true, '');
	$objCombos->sino();
	$html_cara01bien_pv_familiar=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_academ', $_REQUEST['cara01bien_pv_academ'], true, '');
	$objCombos->sino();
	$html_cara01bien_pv_academ=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_labora', $_REQUEST['cara01bien_pv_labora'], true, '');
	$objCombos->sino();
	$html_cara01bien_pv_labora=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_pareja', $_REQUEST['cara01bien_pv_pareja'], true, '');
	$objCombos->sino();
	$html_cara01bien_pv_pareja=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb', $_REQUEST['cara01bien_amb'], true, '');
	$objCombos->addArreglo($acara01bien_amb, $icara01bien_amb);
	$html_cara01bien_amb=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb_agu', $_REQUEST['cara01bien_amb_agu'], true, '');
	$objCombos->sino();
	$html_cara01bien_amb_agu=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb_bom', $_REQUEST['cara01bien_amb_bom'], true, '');
	$objCombos->sino();
	$html_cara01bien_amb_bom=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb_car', $_REQUEST['cara01bien_amb_car'], true, '');
	$objCombos->sino();
	$html_cara01bien_amb_car=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb_info', $_REQUEST['cara01bien_amb_info'], true, '');
	$objCombos->sino();
	//$objCombos->sAccion='ajustaramb_temas()';
	$html_cara01bien_amb_info=$objCombos->html('', $objDB);
	}
$bEntra=false;
if ($_REQUEST['cara01bienversion']==2){$bEntra=true;}
if ($bEntra){
	$objCombos->nuevo('cara01bienv2altoren', $_REQUEST['cara01bienv2altoren'], true, '');
	$objCombos->sino();
	$html_cara01bienv2altoren=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2atletismo', $_REQUEST['cara01bienv2atletismo'], true, '');
	$objCombos->sino();
	$html_cara01bienv2atletismo=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2baloncesto', $_REQUEST['cara01bienv2baloncesto'], true, '');
	$objCombos->sino();
	$html_cara01bienv2baloncesto=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2futbol', $_REQUEST['cara01bienv2futbol'], true, '');
	$objCombos->sino();
	$html_cara01bienv2futbol=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2gimnasia', $_REQUEST['cara01bienv2gimnasia'], true, '');
	$objCombos->sino();
	$html_cara01bienv2gimnasia=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2natacion', $_REQUEST['cara01bienv2natacion'], true, '');
	$objCombos->sino();
	$html_cara01bienv2natacion=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2voleibol', $_REQUEST['cara01bienv2voleibol'], true, '');
	$objCombos->sino();
	$html_cara01bienv2voleibol=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2tenis', $_REQUEST['cara01bienv2tenis'], true, '');
	$objCombos->sino();
	$html_cara01bienv2tenis=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2paralimpico', $_REQUEST['cara01bienv2paralimpico'], true, '');
	$objCombos->sino();
	$html_cara01bienv2paralimpico=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2otrodeporte', $_REQUEST['cara01bienv2otrodeporte'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustarbienv2otrodeporte()';
	$html_cara01bienv2otrodeporte=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2activdanza', $_REQUEST['cara01bienv2activdanza'], true, '');
	$objCombos->sino();
	$html_cara01bienv2activdanza=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2activmusica', $_REQUEST['cara01bienv2activmusica'], true, '');
	$objCombos->sino();
	$html_cara01bienv2activmusica=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2activteatro', $_REQUEST['cara01bienv2activteatro'], true, '');
	$objCombos->sino();
	$html_cara01bienv2activteatro=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2activartes', $_REQUEST['cara01bienv2activartes'], true, '');
	$objCombos->sino();
	$html_cara01bienv2activartes=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2activliteratura', $_REQUEST['cara01bienv2activliteratura'], true, '');
	$objCombos->sino();
	$html_cara01bienv2activliteratura=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2activculturalotra', $_REQUEST['cara01bienv2activculturalotra'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustarcara01bienv2activculturalotra()';
	$html_cara01bienv2activculturalotra=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2evenfestfolc', $_REQUEST['cara01bienv2evenfestfolc'], true, '');
	$objCombos->sino();
	$html_cara01bienv2evenfestfolc=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2evenexpoarte', $_REQUEST['cara01bienv2evenexpoarte'], true, '');
	$objCombos->sino();
	$html_cara01bienv2evenexpoarte=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2evenhistarte', $_REQUEST['cara01bienv2evenhistarte'], true, '');
	$objCombos->sino();
	$html_cara01bienv2evenhistarte=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2evengalfoto', $_REQUEST['cara01bienv2evengalfoto'], true, '');
	$objCombos->sino();
	$html_cara01bienv2evengalfoto=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2evenliteratura', $_REQUEST['cara01bienv2evenliteratura'], true, '');
	$objCombos->sino();
	$html_cara01bienv2evenliteratura=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2eventeatro', $_REQUEST['cara01bienv2eventeatro'], true, '');
	$objCombos->sino();
	$html_cara01bienv2eventeatro=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2evencine', $_REQUEST['cara01bienv2evencine'], true, '');
	$objCombos->sino();
	$html_cara01bienv2evencine=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2evenculturalotro', $_REQUEST['cara01bienv2evenculturalotro'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustarcara01bienv2evenculturalotro()';
	$html_cara01bienv2evenculturalotro=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprendimiento', $_REQUEST['cara01bienv2emprendimiento'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprendimiento=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2empresa', $_REQUEST['cara01bienv2empresa'], true, '');
	$objCombos->sino();
	$html_cara01bienv2empresa=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenrecursos', $_REQUEST['cara01bienv2emprenrecursos'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprenrecursos=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenconocim', $_REQUEST['cara01bienv2emprenconocim'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprenconocim=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenplan', $_REQUEST['cara01bienv2emprenplan'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprenplan=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenejecutar', $_REQUEST['cara01bienv2emprenejecutar'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprenejecutar=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenfortconocim', $_REQUEST['cara01bienv2emprenfortconocim'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprenfortconocim=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenidentproblema', $_REQUEST['cara01bienv2emprenidentproblema'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprenidentproblema=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenotro', $_REQUEST['cara01bienv2emprenotro'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustarcara01bienv2emprenotro()';
	$html_cara01bienv2emprenotro=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenmarketing', $_REQUEST['cara01bienv2emprenmarketing'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprenmarketing=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenplannegocios', $_REQUEST['cara01bienv2emprenplannegocios'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprenplannegocios=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprenideas', $_REQUEST['cara01bienv2emprenideas'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprenideas=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2emprencreacion', $_REQUEST['cara01bienv2emprencreacion'], true, '');
	$objCombos->sino();
	$html_cara01bienv2emprencreacion=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2saludfacteconom', $_REQUEST['cara01bienv2saludfacteconom'], true, '');
	$objCombos->sino();
	$html_cara01bienv2saludfacteconom=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2saludpreocupacion', $_REQUEST['cara01bienv2saludpreocupacion'], true, '');
	$objCombos->sino();
	$html_cara01bienv2saludpreocupacion=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2saludconsumosust', $_REQUEST['cara01bienv2saludconsumosust'], true, '');
	$objCombos->sino();
	$html_cara01bienv2saludconsumosust=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2saludinsomnio', $_REQUEST['cara01bienv2saludinsomnio'], true, '');
	$objCombos->sino();
	$html_cara01bienv2saludinsomnio=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2saludclimalab', $_REQUEST['cara01bienv2saludclimalab'], true, '');
	$objCombos->sino();
	$html_cara01bienv2saludclimalab=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2saludalimenta', $_REQUEST['cara01bienv2saludalimenta'], true, '');
	$objCombos->sino();
	$html_cara01bienv2saludalimenta=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2saludemocion', $_REQUEST['cara01bienv2saludemocion'], true, '');
	$objCombos->sino();
	$html_cara01bienv2saludemocion=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2saludestado', $_REQUEST['cara01bienv2saludestado'], true, '');
	$objCombos->sino();
	$html_cara01bienv2saludestado=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2saludmedita', $_REQUEST['cara01bienv2saludmedita'], true, '');
	$objCombos->sino();
	$html_cara01bienv2saludmedita=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimedusexual', $_REQUEST['cara01bienv2crecimedusexual'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimedusexual=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimcultciudad', $_REQUEST['cara01bienv2crecimcultciudad'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimcultciudad=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimrelpareja', $_REQUEST['cara01bienv2crecimrelpareja'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimrelpareja=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimrelinterp', $_REQUEST['cara01bienv2crecimrelinterp'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimrelinterp=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimdinamicafam', $_REQUEST['cara01bienv2crecimdinamicafam'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimdinamicafam=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimautoestima', $_REQUEST['cara01bienv2crecimautoestima'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimautoestima=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2creciminclusion', $_REQUEST['cara01bienv2creciminclusion'], true, '');
	$objCombos->sino();
	$html_cara01bienv2creciminclusion=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2creciminteliemoc', $_REQUEST['cara01bienv2creciminteliemoc'], true, '');
	$objCombos->sino();
	$html_cara01bienv2creciminteliemoc=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimcultural', $_REQUEST['cara01bienv2crecimcultural'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimcultural=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimartistico', $_REQUEST['cara01bienv2crecimartistico'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimartistico=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimdeporte', $_REQUEST['cara01bienv2crecimdeporte'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimdeporte=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimambiente', $_REQUEST['cara01bienv2crecimambiente'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimambiente=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2crecimhabsocio', $_REQUEST['cara01bienv2crecimhabsocio'], true, '');
	$objCombos->sino();
	$html_cara01bienv2crecimhabsocio=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienbasura', $_REQUEST['cara01bienv2ambienbasura'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienbasura=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienreutiliza', $_REQUEST['cara01bienv2ambienreutiliza'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienreutiliza=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienluces', $_REQUEST['cara01bienv2ambienluces'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienluces=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienfrutaverd', $_REQUEST['cara01bienv2ambienfrutaverd'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienfrutaverd=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienenchufa', $_REQUEST['cara01bienv2ambienenchufa'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienenchufa=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambiengrifo', $_REQUEST['cara01bienv2ambiengrifo'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambiengrifo=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienbicicleta', $_REQUEST['cara01bienv2ambienbicicleta'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienbicicleta=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambientranspub', $_REQUEST['cara01bienv2ambientranspub'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambientranspub=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienducha', $_REQUEST['cara01bienv2ambienducha'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienducha=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambiencaminata', $_REQUEST['cara01bienv2ambiencaminata'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambiencaminata=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambiensiembra', $_REQUEST['cara01bienv2ambiensiembra'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambiensiembra=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienconferencia', $_REQUEST['cara01bienv2ambienconferencia'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienconferencia=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienrecicla', $_REQUEST['cara01bienv2ambienrecicla'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienrecicla=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienotraactiv', $_REQUEST['cara01bienv2ambienotraactiv'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustarcara01bienv2ambienotraactiv()';
	$html_cara01bienv2ambienotraactiv=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienreforest', $_REQUEST['cara01bienv2ambienreforest'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienreforest=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienmovilidad', $_REQUEST['cara01bienv2ambienmovilidad'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienmovilidad=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienclimatico', $_REQUEST['cara01bienv2ambienclimatico'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienclimatico=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienecofemin', $_REQUEST['cara01bienv2ambienecofemin'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienecofemin=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienbiodiver', $_REQUEST['cara01bienv2ambienbiodiver'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienbiodiver=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienecologia', $_REQUEST['cara01bienv2ambienecologia'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienecologia=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambieneconomia', $_REQUEST['cara01bienv2ambieneconomia'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambieneconomia=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienrecnatura', $_REQUEST['cara01bienv2ambienrecnatura'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienrecnatura=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienreciclaje', $_REQUEST['cara01bienv2ambienreciclaje'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienreciclaje=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienmascota', $_REQUEST['cara01bienv2ambienmascota'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienmascota=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambiencartohum', $_REQUEST['cara01bienv2ambiencartohum'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambiencartohum=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienespiritu', $_REQUEST['cara01bienv2ambienespiritu'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambienespiritu=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambiencarga', $_REQUEST['cara01bienv2ambiencarga'], true, '');
	$objCombos->sino();
	$html_cara01bienv2ambiencarga=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bienv2ambienotroenfoq', $_REQUEST['cara01bienv2ambienotroenfoq'], true, '');
	$objCombos->sino();
	$objCombos->sAccion='ajustarcara01bienv2ambienotroenfoq()';
	$html_cara01bienv2ambienotroenfoq=$objCombos->html('', $objDB);
	}
$objCombos->nuevo('cara01psico_costoemocion', $_REQUEST['cara01psico_costoemocion'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($aCAEN, $iCAEN);
$html_cara01psico_costoemocion=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_reaccionimpre', $_REQUEST['cara01psico_reaccionimpre'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($apsico_reaccionimpre, $ipsico_reaccionimpre);
$html_cara01psico_reaccionimpre=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_estres', $_REQUEST['cara01psico_estres'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($apsico_estres, $ipsico_estres);
$html_cara01psico_estres=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_pocotiempo', $_REQUEST['cara01psico_pocotiempo'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($apsico_pocotiempo, $ipsico_pocotiempo);
$html_cara01psico_pocotiempo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_actitudvida', $_REQUEST['cara01psico_actitudvida'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($apsico_actitudvida, $ipsico_actitudvida);
$html_cara01psico_actitudvida=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_duda', $_REQUEST['cara01psico_duda'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($apsico_duda, $ipsico_duda);
$html_cara01psico_duda=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_problemapers', $_REQUEST['cara01psico_problemapers'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($apsico_problemapers, $ipsico_problemapers);
$html_cara01psico_problemapers=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_satisfaccion', $_REQUEST['cara01psico_satisfaccion'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($apsico_satisfaccion, $ipsico_satisfaccion);
$html_cara01psico_satisfaccion=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_discusiones', $_REQUEST['cara01psico_discusiones'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($apsico_discusiones, $ipsico_discusiones);
$html_cara01psico_discusiones=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_atencion', $_REQUEST['cara01psico_atencion'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addArreglo($apsico_atencion, $ipsico_atencion);
$html_cara01psico_atencion=$objCombos->html('', $objDB);
list($cara01idconsejero_rs, $_REQUEST['cara01idconsejero'], $_REQUEST['cara01idconsejero_td'], $_REQUEST['cara01idconsejero_doc'])=html_tercero($_REQUEST['cara01idconsejero_td'], $_REQUEST['cara01idconsejero_doc'], $_REQUEST['cara01idconsejero'], 0, $objDB);
$objCombos->nuevo('cara01perayuda', $_REQUEST['cara01perayuda'], true, '{'.$ETI['msg_ninguno'].'}', 0);
$iVersion=1;
if ($_REQUEST['cara01discversion']==2){$iVersion=2;}
$sAdd=' cara14version='.$iVersion.' AND cara14activa="S"';
if ($_REQUEST['cara01completa']=='S'){$sAdd='cara14id='.$_REQUEST['cara01perayuda'].'';}
$sSQL='SELECT cara14id AS id, cara14nombre AS nombre FROM cara14ayudaajuste WHERE '.$sAdd.' ORDER BY cara14orden, cara14nombre';
$tabla=$objDB->ejecutasql($sSQL);
while($fila=$objDB->sf($tabla)){
	$objCombos->addItem($fila['id'], $fila['nombre']);
	}
$objCombos->addItem('-1', $ETI['msg_otro']);
$objCombos->sAccion='ajustar_cara01criteriodesc()';
$html_cara01perayuda=$objCombos->html('', $objDB);
$objCombos->nuevo('cara01criteriodesc', $_REQUEST['cara01criteriodesc'], true, '{'.$ETI['msg_seleccione'].'}');
//$objCombos->addArreglo($acara01criteriodesc, $icara01criteriodesc);
$html_cara01criteriodesc=$objCombos->html('', $objDB);
//$objCombos->nuevo('cara01desertor', $_REQUEST['cara01desertor'], false);
//$objCombos->sino();
//$html_cara01desertor=$objCombos->html('', $objDB);
$cara01idprograma_nombre='&nbsp;';
if ($_REQUEST['cara01idprograma']>0){
	list($cara01idprograma_nombre, $sErrorDet)=tabla_campoxid('core09programa','core09nombre','core09id',$_REQUEST['cara01idprograma'],'{'.$ETI['msg_sindato'].'}', $objDB);
	}
$html_cara01idprograma=html_oculto('cara01idprograma', $_REQUEST['cara01idprograma'], $cara01idprograma_nombre);
$cara01idescuela_nombre='&nbsp;';
if ($_REQUEST['cara01idprograma']>0){
	list($cara01idescuela_nombre, $sErrorDet)=tabla_campoxid('core12escuela','core12nombre','core12id',$_REQUEST['cara01idescuela'],'{'.$ETI['msg_sindato'].'}', $objDB);
	}
$html_cara01idescuela=html_oculto('cara01idescuela', $_REQUEST['cara01idescuela'], $cara01idescuela_nombre);
$et_cara01desertor=$ETI['no'];
if ($_REQUEST['cara01desertor']=='S'){$et_cara01desertor=$ETI['si'];}
$html_cara01desertor=html_oculto('cara01desertor', $_REQUEST['cara01desertor'], $et_cara01desertor);
$objCombos->nuevo('cara01factorprincipaldesc', $_REQUEST['cara01factorprincipaldesc'], true, '{'.$ETI['msg_seleccione'].'}');
//$objCombos->addArreglo($acara01factorprincipaldesc, $icara01factorprincipaldesc);
$html_cara01factorprincipaldesc=$objCombos->html('', $objDB);
$bEntra=false;
if ($_REQUEST['cara01discversion']==1){$bEntra=true;}
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
	$sAdd=' AND cara37version='.$_REQUEST['cara01discversion'].' AND cara37activa="S"';
	if ($bEstudiante){
		if ($_REQUEST['cara01completa']=='S'){$sAdd=' AND cara37id='.$_REQUEST['cara01discv2sensorial'].'';}
		}
	$objCombos->nuevo('cara01discv2sensorial', $_REQUEST['cara01discv2sensorial'], true, '{'.$ETI['msg_ninguna'].'}', 0);
	$sSQL='SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=1'.$sAdd.' ORDER BY cara37orden, cara37nombre';
	$html_cara01discv2sensorial=$objCombos->html($sSQL, $objDB);
	if ($bEstudiante){
		if ($_REQUEST['cara01completa']=='S'){$sAdd=' AND cara37id='.$_REQUEST['cara02discv2intelectura'].'';}
		}
	$objCombos->nuevo('cara02discv2intelectura', $_REQUEST['cara02discv2intelectura'], true, '{'.$ETI['msg_ninguna'].'}', 0);
	$sSQL='SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=2'.$sAdd.' ORDER BY cara37orden, cara37nombre';
	$html_cara02discv2intelectura=$objCombos->html($sSQL, $objDB);
	if ($bEstudiante){
		if ($_REQUEST['cara01completa']=='S'){$sAdd=' AND cara37id='.$_REQUEST['cara02discv2fisica'].'';}
		}
	$objCombos->nuevo('cara02discv2fisica', $_REQUEST['cara02discv2fisica'], true, '{'.$ETI['msg_ninguna'].'}', 0);
	$sSQL='SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=3'.$sAdd.' ORDER BY cara37orden, cara37nombre';
	$html_cara02discv2fisica=$objCombos->html($sSQL, $objDB);
	if ($bEstudiante){
		if ($_REQUEST['cara01completa']=='S'){$sAdd=' AND cara37id='.$_REQUEST['cara02discv2psico'].'';}
		}
	$objCombos->nuevo('cara02discv2psico', $_REQUEST['cara02discv2psico'], true, '{'.$ETI['msg_ninguna'].'}', 0);
	$sSQL='SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=4'.$sAdd.' ORDER BY cara37orden, cara37nombre';
	$html_cara02discv2psico=$objCombos->html($sSQL, $objDB);

	$sAdd=' AND cara28version='.$_REQUEST['cara01discversion'].' AND cara38activa="S"';
	if ($bEstudiante){
		if ($_REQUEST['cara01completa']=='S'){$sAdd=' AND cara38id='.$_REQUEST['cara02talentoexcepcional'].'';}
		}
	$objCombos->nuevo('cara02talentoexcepcional', $_REQUEST['cara02talentoexcepcional'], true, '{'.$ETI['msg_ninguno'].'}', 0);
	$sSQL='SELECT cara38id AS id, cara38nombre AS nombre FROM cara38talentos WHERE cara38id>0'.$sAdd.' ORDER BY cara38orden, cara38nombre';
	$html_cara02talentoexcepcional=$objCombos->html($sSQL, $objDB);
	}
if ($_REQUEST['cara01discversion']==1){
	$objCombos->nuevo('cara02discv2sistemica', $_REQUEST['cara02discv2sistemica'], true, ''.$ETI['no'].'', 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($acara02discv2sistemica, $icara02discv2sistemica);
	$html_cara02discv2sistemica=$objCombos->html('', $objDB);
	}
$bEntra=false;
if ($_REQUEST['cara01discversion']==1){$bEntra=true;}
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
	$objCombos->nuevo('cara02discv2multiple', $_REQUEST['cara02discv2multiple'], true, ''.$ETI['no'].'', 0);
	$objCombos->addItem(1, $ETI['si']);
	if ($_REQUEST['cara01discversion']==2){
		$objCombos->sAccion='ajustar_cara02discv2multiple()';
		}
	//$objCombos->addArreglo($acara02discv2multiple, $icara02discv2multiple);
	$html_cara02discv2multiple=$objCombos->html('', $objDB);
	}
	list($cara01idzona_nombre, $sErrorDet)=tabla_campoxid('unad23zona','unad23nombre','unad23id',$_REQUEST['cara01idzona'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_cara01idzona=html_oculto('cara01idzona', $_REQUEST['cara01idzona'], $cara01idzona_nombre);
	list($cara01tipocaracterizacion_nombre, $sErrorDet)=tabla_campoxid('cara11tipocaract','cara11nombre','cara11id',$_REQUEST['cara01tipocaracterizacion'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_cara01tipocaracterizacion=html_oculto('cara01tipocaracterizacion', $_REQUEST['cara01tipocaracterizacion'], $cara01tipocaracterizacion_nombre);
$bEntra=false;
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
	$objCombos->nuevo('cara01discv2tiene', $_REQUEST['cara01discv2tiene'], true, $ETI['no'], 0);
	$objCombos->sAccion='ajustar_cara01discv2tiene()';
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($acara01discv2tiene, $icara01discv2tiene);
	$html_cara01discv2tiene=$objCombos->html('', $objDB);


	$objCombos->nuevo('cara01discv2trastornos', $_REQUEST['cara01discv2trastornos'], true, $ETI['no'], 0);
	//$objCombos->addArreglo($acara01discv2trastornos, $icara01discv2trastornos);
	$objCombos->sAccion='ajustar_cara01discv2trastornos()';
	$objCombos->addItem(1, $ETI['si']);
	$html_cara01discv2trastornos=$objCombos->html('', $objDB);
	$sAdd=' AND cara37version='.$_REQUEST['cara01discversion'].' AND cara37activa="S"';
	if ($bEstudiante){
		if ($_REQUEST['cara01completa']=='S'){$sAdd=' AND cara37id='.$_REQUEST['cara01discv2trastornos'].'';}
		}
	$objCombos->nuevo('cara01discv2trastaprende', $_REQUEST['cara01discv2trastaprende'], true, '{'.$ETI['msg_ninguno'].'}', 0);
	//$objCombos->addArreglo($acara01discv2trastaprende, $icara01discv2trastaprende);
	$sSQL='SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=6'.$sAdd.' ORDER BY cara37orden, cara37nombre';
	$html_cara01discv2trastaprende=$objCombos->html($sSQL, $objDB);

	$objCombos->nuevo('cara01discv2contalento', $_REQUEST['cara01discv2contalento'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($acara01discv2contalento, $icara01discv2contalento);
	$html_cara01discv2contalento=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01discv2condicionmedica', $_REQUEST['cara01discv2condicionmedica'], true, $ETI['no'], 0);
	$objCombos->sAccion='ajustar_cara01discv2condicionmedica()';
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($acara01discv2condicionmedica, $icara01discv2condicionmedica);
	$html_cara01discv2condicionmedica=$objCombos->html('', $objDB);
	$objCombos->nuevo('cara01discv2pruebacoeficiente', $_REQUEST['cara01discv2pruebacoeficiente'], true, $ETI['no'], 0);
	$objCombos->addArreglo($acara01discv2pruebacoeficiente, $icara01discv2pruebacoeficiente);
	$html_cara01discv2pruebacoeficiente=$objCombos->html('', $objDB);
	}else{
	$html_cara01discv2trastaprende=html_oculto('cara01discv2trastaprende', $_REQUEST['cara01discv2trastaprende']);
	}
if ((int)$_REQUEST['paso']==0){
	$idTerceroFuncion=0;
	if ($bEstudiante){$idTerceroFuncion=$idTercero;}
	$html_cara01idperaca=f2301_HTMLComboV2_cara01idperaca($objDB, $objCombos, $_REQUEST['cara01idperaca'], $idTerceroFuncion);
	}else{
	list($cara01idperaca_nombre, $sErrorDet)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id',$_REQUEST['cara01idperaca'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_cara01idperaca=html_oculto('cara01idperaca', $_REQUEST['cara01idperaca'], $cara01idperaca_nombre);
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['cara01completa']=='S'){
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve){$bPuedeAbrir=true;}
		}
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
if (!$bEstudiante){
	$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2301()';
	$objCombos->addItem(1, 'Donde soy consejero');
	$objCombos->addItem(11, 'Encuestas terminadas');
	$objCombos->addItem(12, 'Encuestas incompletas');
	$html_blistar=$objCombos->html('', $objDB);
	$objCombos->nuevo('bperaca', $_REQUEST['bperaca'], true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2301()';
	$sIds='-99';
	$sSQL='SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds=$sIds.','.$fila['cara01idperaca'];
		}
	$sSQL='SELECT exte02id AS id, CONCAT(CASE exte02vigente WHEN "S" THEN "" ELSE "[" END, exte02nombre," {",exte02id,"} ",CASE exte02vigente WHEN "S" THEN "" ELSE " - INACTIVO]" END) AS nombre 
FROM exte02per_aca 
WHERE exte02id IN ('.$sIds.') 
ORDER BY exte02vigente DESC, exte02id DESC';
	$html_bperaca=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{'.$ETI['msg_todas'].'}');
	$objCombos->sAccion='carga_combo_bprograma()';
	$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" ORDER BY core12nombre';
	$html_bescuela=$objCombos->html($sSQL, $objDB);
	$html_bprograma=f2301_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela']);
	$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{'.$ETI['msg_todas'].'}');
	$objCombos->sAccion='carga_combo_bcead()';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
	$html_bzona=$objCombos->html($sSQL, $objDB);
	$html_bcead=f2301_HTMLComboV2_bcead($objDB, $objCombos, $_REQUEST['bcead'], $_REQUEST['bzona']);
	$objCombos->nuevo('btipocara', $_REQUEST['btipocara'], true, '{'.$ETI['msg_todas'].'}');
	$objCombos->sAccion='paginarf2301()';
	$sSQL='SELECT cara11id AS id, cara11nombre AS nombre FROM cara11tipocaract ORDER BY cara11nombre';
	$html_btipocara=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('bpoblacion', $_REQUEST['bpoblacion'], true, '{'.$ETI['msg_todas'].'}');
	$objCombos->sAccion='paginarf2301()';
	$objCombos->addItem(1, 'Con necesidades especiales');
	$objCombos->addItem(2, 'Con necesidades especiales [Por confirmar]');
	$html_bpoblacion=$objCombos->html('', $objDB);
	$objCombos->nuevo('bconvenio', $_REQUEST['bconvenio'], true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2301()';
	$sSQL='SELECT core50id AS id, core50nombre AS nombre FROM core50convenios ORDER BY core50estado DESC, core50nombre';
	$html_bconvenio=$objCombos->html($sSQL, $objDB);
	//$html_blistar=$objCombos->comboSistema(2301, 1, $objDB, 'paginarf2301()');
	}
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=2301;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	}
if (!$bEstudiante){
	//Cargar las tablas de datos
	$aParametros[0]='';//$_REQUEST['p1_2301'];
	$aParametros[100]=$idTercero;
	$aParametros[101]=$_REQUEST['paginaf2301'];
	$aParametros[102]=$_REQUEST['lppf2301'];
	$aParametros[103]=$_REQUEST['bdoc'];
	$aParametros[104]=$_REQUEST['bnombre'];
	$aParametros[105]=$_REQUEST['bperaca'];
	$aParametros[106]=$_REQUEST['blistar'];
	$aParametros[107]=$_REQUEST['bescuela'];
	$aParametros[108]=$_REQUEST['bprograma'];
	$aParametros[109]=$_REQUEST['bzona'];
	$aParametros[110]=$_REQUEST['bcead'];
	$aParametros[111]=$_REQUEST['btipocara'];
	$aParametros[112]=$_REQUEST['bpoblacion'];
	$aParametros[113]=$_REQUEST['bconvenio'];
	list($sTabla2301, $sDebugTabla)=f2301_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
$sTabla2310_7='';
$sTabla2310_8='';
$sTabla2310_9='';
$sTabla2310_10='';
$sTabla2310_11='';
$sTabla2310_12='';
$sTabla2310_13='';
if ($_REQUEST['paso']!=0){
	//Preguntas de la prueba
	$iParaEditar=0;
	if ($bEstudiante){$iParaEditar=1;}
	$aParametros2310[0]=$_REQUEST['cara01id'];
	$aParametros2310[101]=$_REQUEST['paginaf2310'];
	$aParametros2310[102]=$_REQUEST['lppf2310'];
	$aParametros2310[103]=$iParaEditar;
	if ($_REQUEST['cara01fichadigital']!=-1){
		$aParametros2310[100]=1;
		list($sTabla2310_7, $sDebugTabla)=f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		}
	if ($_REQUEST['cara01fichadigital']!=-1){
		$aParametros2310[100]=2;
		list($sTabla2310_8, $sDebugTabla)=f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		}
	if ($_REQUEST['cara01fichadigital']!=-1){
		$aParametros2310[100]=3;
		list($sTabla2310_9, $sDebugTabla)=f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		}
	if ($_REQUEST['cara01fichadigital']!=-1){
		$aParametros2310[100]=4;
		list($sTabla2310_10, $sDebugTabla)=f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		}
	if ($_REQUEST['cara01fichadigital']!=-1){
		$aParametros2310[100]=5;
		list($sTabla2310_11, $sDebugTabla)=f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		}
	if ($_REQUEST['cara01fichadigital']!=-1){
		$aParametros2310[100]=6;
		list($sTabla2310_12, $sDebugTabla)=f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		}
	if ($_REQUEST['cara01fichadigital']!=-1){
		$aParametros2310[100]=7;
		list($sTabla2310_13, $sDebugTabla)=f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		}
	}
if ($bEstudiante){
	$et_menu='<li><a href="accesit.php">ACCESIT</a></li><li><a href="'.$sUrlTablero.'">Mis Cursos Virtuales</a></li><li><a href="salir.php">Salir</a></li>';
	}else{
	$bDebugMenu=false;
	list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
	$sDebug=$sDebug.$sDebugM;
	}
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2301']);
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
if ($bEstudiante){
?>
	if (window.document.frmedita.cara01completa.value!='S'){
		var sEst='none';
		if (codigo==1){sEst='block';}
		document.getElementById('cmdGuardarf').style.display=sEst;
		}
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
		if (idcampo=='cara01idtercero'){
			params[4]='RevisaLlave';
			}
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2301.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2301.value;
		window.document.frmlista.nombrearchivo.value='Encuesta';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	window.document.frmimpp.iformato94.value=window.document.frmedita.iformatoimprime.value;
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
		window.document.frmimpp.action='e2301.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2301.php';
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
	datos[1]=window.document.frmedita.cara01idperaca.value;
	datos[2]=window.document.frmedita.cara01idtercero.value;
	if ((datos[1]!='')&&(datos[2]!='')){
		xajax_f2301_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2){
	window.document.frmedita.cara01idperaca.value=String(llave1);
	window.document.frmedita.cara01idtercero.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf2301(llave1){
	window.document.frmedita.cara01id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_cara01depto(){
	var params=new Array();
	params[0]=window.document.frmedita.cara01pais.value;
	document.getElementById('div_cara01depto').innerHTML='<b>Procesando datos, por favor espere...</b><input id="cara01depto" name="cara01depto" type="hidden" value="" />';
	xajax_f2301_Combocara01depto(params);
	sMuestra='none';
	if (params[0]!='057'){sMuestra='block';}
	document.getElementById('div_ciudad').style.display=sMuestra;
	}
function carga_combo_cara01ciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.cara01depto.value;
	document.getElementById('div_cara01ciudad').innerHTML='<b>Procesando datos, por favor espere...</b><input id="cara01ciudad" name="cara01ciudad" type="hidden" value="" />';
	xajax_f2301_Combocara01ciudad(params);
	}
function carga_combo_cara01idcead(){
	var params=new Array();
	params[0]=window.document.frmedita.cara01idzona.value;
	document.getElementById('div_cara01idcead').innerHTML='<b>Procesando datos, por favor espere...</b><input id="cara01idcead" name="cara01idcead" type="hidden" value="" />';
	xajax_f2301_Combocara01idcead(params);
	}
function limpia_cara01discv2archivoorigen(){
	window.document.frmedita.cara01discv2soporteorigen.value=0;
	window.document.frmedita.cara01discv2archivoorigen.value=0;
	var da_Discv2archivoorigen=document.getElementById('div_cara01discv2archivoorigen');
	da_Discv2archivoorigen.innerHTML='&nbsp;';
	verboton('beliminacara01discv2archivoorigen','none');
	//paginarf0000();
	}
function carga_cara01discv2archivoorigen(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	window.document.frmedita.div96v1.value='';
	window.document.frmedita.div96v2.value='';
	window.document.frmedita.div96v3.value='';
	if (window.document.frmedita.ipiel.value==2) {
		document.getElementById('div_96titulo').innerHTML=''+window.document.frmedita.titulo_2301.value+' - Cargar archivo';
		}else{
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_2301.value+' - Cargar archivo</h2>';
		}
	document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivo.php?ref=2301&id='+window.document.frmedita.cara01id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
	expandesector(96);
	window.scrollTo(0, 150);
	}
<?php
if (!$bEstudiante){
?>
function eliminacara01discv2archivoorigen(){
	var did=window.document.frmedita.cara01id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_cara01discv2archivoorigen(did.value);
		//paginarf0000();
		}
	}
<?php
	}
?>
function paginarf2301(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf2301.value;
	params[102]=window.document.frmedita.lppf2301.value;
	params[103]=window.document.frmedita.bdoc.value;
	params[104]=window.document.frmedita.bnombre.value;
	params[105]=window.document.frmedita.bperaca.value;
	params[106]=window.document.frmedita.blistar.value;
	params[107]=window.document.frmedita.bescuela.value;
	params[108]=window.document.frmedita.bprograma.value;
	params[109]=window.document.frmedita.bzona.value;
	params[110]=window.document.frmedita.bcead.value;
	params[111]=window.document.frmedita.btipocara.value;
	params[112]=window.document.frmedita.bpoblacion.value;
	params[113]=window.document.frmedita.bconvenio.value;
	//document.getElementById('div_f2301detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2301" name="paginaf2301" type="hidden" value="'+params[101]+'" /><input id="lppf2301" name="lppf2301" type="hidden" value="'+params[102]+'" />';
	xajax_f2301_HtmlTabla(params);
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
	document.getElementById("cara01idperaca").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f2301_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='cara01idtercero'){
		ter_traerxid('cara01idtercero', sValor);
		}
	if (sCampo=='cara01idconfirmadesp'){
		ter_traerxid('cara01idconfirmadesp', sValor);
		}
	if (sCampo=='cara01idconfirmacr'){
		ter_traerxid('cara01idconfirmacr', sValor);
		}
	if (sCampo=='cara01idconfirmadisc'){
		ter_traerxid('cara01idconfirmadisc', sValor);
		}
	if (sCampo=='cara01idconsejero'){
		ter_traerxid('cara01idconsejero', sValor);
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
		//var sMensaje='Lo que quiera decir.';
		//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
		//divAyuda.innerHTML=sMensaje;
		divAyuda.style.display='block';
		}
	}
function cierraDiv96(ref){
	var sRetorna=window.document.frmedita.div96v2.value;
	if (ref==0){
		if (sRetorna!=''){
			window.document.frmedita.cara01discv2soporteorigen.value=window.document.frmedita.div96v1.value;
			window.document.frmedita.cara01discv2archivoorigen.value=sRetorna;
			verboton('beliminacara01discv2archivoorigen','block');
			}
		archivo_lnk(window.document.frmedita.cara01discv2soporteorigen.value, window.document.frmedita.cara01discv2archivoorigen.value, 'div_cara01discv2archivoorigen');
		paginarf0();
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
<?php
if ($bEstudiante){
?>
function verficha(num){
	document.getElementById('div_ficha1').style.display='none';
	document.getElementById('div_ficha2').style.display='none';
	document.getElementById('div_ficha3').style.display='none';
	document.getElementById('div_ficha4').style.display='none';
	document.getElementById('div_ficha5').style.display='none';
	document.getElementById('div_ficha6').style.display='none';
	document.getElementById('div_ficha7').style.display='none';
	document.getElementById('div_ficha8').style.display='none';
	document.getElementById('div_ficha9').style.display='none';
	document.getElementById('div_ficha10').style.display='none';
	document.getElementById('div_ficha11').style.display='none';
	document.getElementById('div_ficha12').style.display='none';
	document.getElementById('div_ficha13').style.display='none';
	document.getElementById('div_ficha'+num).style.display='block';
	window.document.frmedita.ficha.value=num;
	}
function irtablero(){
	window.document.frmtablero.submit();
	}
<?php
	if ($_REQUEST['cara01completa']!='S'){
?>
function guardarpregunta(id10, iVr){
	var aValores=new Array();
	aValores[0]=id10;
	aValores[1]=iVr;
	xajax_f2310_GuardarRespuesta(aValores)
	}
<?php
		}
	}
?>
function verrecluso(){
	sMuestra='none';
	if (window.document.frmedita.cara01inpecrecluso.value=='S'){sMuestra='block';}
	document.getElementById('div_recluso').style.display=sMuestra;
	}
function ajustar_saber11v1(){
	sMuestra='none';
	if (window.document.frmedita.cara01saber11v1agno.value>=2016){sMuestra='block';}
	document.getElementById('div_saber11v1').style.display=sMuestra;
	}
function ajustarinteresrepdeporte(){
	sMuestra='none';
	if (window.document.frmedita.cara01bien_interesrepdeporte.value=='S'){sMuestra='block';}
	document.getElementById('label_interesrepdeporte').style.display=sMuestra;
	}
function ajustarinteresreparte(){
	sMuestra='none';
	if (window.document.frmedita.cara01bien_interesreparte.value=='S'){sMuestra='block';}
	document.getElementById('label_interesreparte').style.display=sMuestra;
	}
function ajustarprimeraopc(){
	sMuestra='none';
	if (window.document.frmedita.cara01acad_primeraopc.value=='N'){sMuestra='block';}
	document.getElementById('label_programagusto').style.display=sMuestra;
	}
function ajustarnivelinterpreta(){
	sMuestra='none';
	if (window.document.frmedita.cara01bien_interpreta.value!=''){
		if (window.document.frmedita.cara01bien_interpreta.value!='-1'){
			sMuestra='block';
			}
		}
	document.getElementById('div_bien_nivelinter').style.display=sMuestra;
	}
function ajustardanza(){
	sMuestra='none';
	if (window.document.frmedita.cara01bien_danza_mod.value=='S'){sMuestra='block';}
	if (window.document.frmedita.cara01bien_danza_clas.value=='S'){sMuestra='block';}
	if (window.document.frmedita.cara01bien_danza_cont.value=='S'){sMuestra='block';}
	if (window.document.frmedita.cara01bien_danza_folk.value=='S'){sMuestra='block';}
	document.getElementById('div_bien_niveldanza').style.display=sMuestra;
	}
function ajustarnombreemp(){
	sMuestra='none';
	if (window.document.frmedita.cara01bien_emprendedor.value=='S'){sMuestra='block';}
	document.getElementById('label_nombreemp').style.display=sMuestra;
	}
function ajustartipocapacita(){
	sMuestra='none';
	if (window.document.frmedita.cara01bien_capacempren.value=='S'){sMuestra='block';}
	document.getElementById('lbl_bien_capacempren').style.display=sMuestra;
	}
function ajustaramb_temas(){
	sMuestra='none';
	if (window.document.frmedita.cara01bien_amb_info.value=='S'){sMuestra='block';}
	document.getElementById('lbl_bien_amb_temas').style.display=sMuestra;
	}
function ajustarlaboral(){
	sMuestra1='none';
	sMuestra2='none';
	sMuestra3='none';
	sMuestra4='none';
	sMuestra5='none';
	sMuestra6='none';
	if (window.document.frmedita.cara01lab_situacion.value==1){
		sMuestra1='block';
		sMuestra2='block';
		sMuestra3='block';
		sMuestra6='block';
		}
	if (window.document.frmedita.cara01lab_situacion.value==2){
		sMuestra1='block';
		sMuestra4='block';
		sMuestra6='block';
		}
	if (window.document.frmedita.cara01lab_situacion.value==3){
		sMuestra3='block';
		sMuestra5='block';
		sMuestra6='block';
		}
	if (window.document.frmedita.cara01lab_situacion.value==4){
		sMuestra5='block';
		sMuestra6='block';
		}
	document.getElementById('div_lab1').style.display=sMuestra1;
	document.getElementById('div_lab2').style.display=sMuestra2;
	document.getElementById('div_lab3').style.display=sMuestra3;
	document.getElementById('div_lab4').style.display=sMuestra4;
	document.getElementById('div_lab5').style.display=sMuestra5;
	document.getElementById('div_lab6').style.display=sMuestra6;
	}
function ajustar_cara01criteriodesc(){
	sMuestra1='none';
	if (window.document.frmedita.cara01perayuda.value==-1){sMuestra1='block';}
	if (window.document.frmedita.cara01perayuda.value==11){sMuestra1='block';}
	document.getElementById('lbl_cara01perotraayuda').style.display=sMuestra1;
	}
function ajustar_discsensorial(){
	sMuestra1='none';
	if (window.document.frmedita.cara01discsensorial.value==9){
		sMuestra1='block';
		}
	document.getElementById('lbl_cara01discsensorialotra').style.display=sMuestra1;
	}
function ajustar_discfisica(){
	sMuestra1='none';
	if (window.document.frmedita.cara01discfisica.value==9){
		sMuestra1='block';
		}
	document.getElementById('lbl_cara01discfisicaotra').style.display=sMuestra1;
	}
function ajustar_disccognitiva(){
	sMuestra1='none';
	if (window.document.frmedita.cara01disccognitiva.value==9){
		sMuestra1='block';
		}
	document.getElementById('lbl_cara01disccognitivaotra').style.display=sMuestra1;
	}
function ajustarbienv2otrodeporte(){
	sMuestra1='none';
	if (window.document.frmedita.cara01bienv2otrodeporte.value=='S'){
		sMuestra1='block';
		}
	document.getElementById('label_cara01bienv2otrodeporte').style.display=sMuestra1;
	}
function ajustarcara01bienv2activculturalotra(){
	sMuestra1='none';
	if (window.document.frmedita.cara01bienv2activculturalotra.value=='S'){
		sMuestra1='block';
		}
	document.getElementById('label_cara01bienv2activculturalotra').style.display=sMuestra1;
	}
function ajustarcara01bienv2evenculturalotro(){
	sMuestra1='none';
	if (window.document.frmedita.cara01bienv2evenculturalotro.value=='S'){
		sMuestra1='block';
		}
	document.getElementById('label_cara01bienv2evenculturalotro').style.display=sMuestra1;
	}
function ajustarcara01bienv2emprenotro(){
	sMuestra1='none';
	if (window.document.frmedita.cara01bienv2emprenotro.value=='S'){
		sMuestra1='block';
		}
	document.getElementById('label_cara01bienv2emprenotro').style.display=sMuestra1;
	}
function ajustarcara01bienv2ambienotraactiv(){
	sMuestra1='none';
	if (window.document.frmedita.cara01bienv2ambienotraactiv.value=='S'){
		sMuestra1='block';
		}
	document.getElementById('label_cara01bienv2ambienotraactiv').style.display=sMuestra1;
	}
function ajustarcara01bienv2ambienotroenfoq(){
	sMuestra1='none';
	if (window.document.frmedita.cara01bienv2ambienotroenfoq.value=='S'){
		sMuestra1='block';
		}
	document.getElementById('label_cara01bienv2ambienotroenfoq').style.display=sMuestra1;
	}
function ajustar_fam_hijos(){
	sMuestra1='none';
	if (window.document.frmedita.cara01sexo.value=='F' && window.document.frmedita.cara01fam_hijos.value>=1 && window.document.frmedita.cara01fam_hijos.value<=4){
		sMuestra1='block';
		}
	document.getElementById('div_cara01fam_madrecabeza').style.display=sMuestra1;
	}
function ajustar_acadhatenidorecesos(){
	sMuestra1='none';
	if (window.document.frmedita.cara01acadhatenidorecesos.value=='S'){
		sMuestra1='block';
		}
	document.getElementById('div_cara01acadrazonreceso').style.display=sMuestra1;
	}
function ajustar_acadrazonreceso(){
	sMuestra1='none';
	if (window.document.frmedita.cara01acadrazonreceso.value==10){
		sMuestra1='block';
		}
	document.getElementById('lbl_cara01acadrazonrecesodetalle').style.display=sMuestra1;
	}
function ajustar_campus_usocorreounad(){
	sMuestra1='none';
	if (window.document.frmedita.cara01campus_usocorreounad.value==4){
		sMuestra1='block';
		}
	document.getElementById('div_cara01campus_usocorreounadno').style.display=sMuestra1;
	}
function ajustar_campus_usocorreounadno(){
	sMuestra1='none';
	if (window.document.frmedita.cara01campus_usocorreounadno.value==5){
		sMuestra1='block';
		}
	document.getElementById('lbl_cara01campus_usocorreounadnodetalle').style.display=sMuestra1;
	}
function ajustar_campus_medioactivunad(){
	sMuestra1='none';
	if (window.document.frmedita.cara01campus_medioactivunad.value==6){
		sMuestra1='block';
		}
	document.getElementById('lbl_cara01campus_medioactivunaddetalle').style.display=sMuestra1;
	}
<?php
if (!$bEstudiante){
?>
function actualizapreguntas(){
	expandesector(98);
	window.document.frmedita.paso.value=22;
	window.document.frmedita.submit();
	}
function carga_combo_bprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.bescuela.value;
	xajax_f2301_Combobprograma(params);
	}
function carga_combo_bcead(){
	var params=new Array();
	params[0]=window.document.frmedita.bzona.value;
	xajax_f2301_Combobcead(params);
	}
function soyconsejeroidf2301(cara01id){
	var params=new Array();
	params[0]=cara01id;
	params[1]=<?php echo $idTercero; ?>;
	xajax_f2301_MarcarConsejero(params);
	}
function confirmadisc(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	MensajeAlarmaV2('Confirmando datos...', 2);
	expandesector(98);
	window.document.frmedita.paso.value=23;
	window.document.frmedita.submit();
	}
<?php
	}
if ($_REQUEST['cara01discversion']==2){
?>
function ajustar_cara01discv2tiene(){
	sMuestra1='none';
	if (window.document.frmedita.cara01discv2tiene.value==1){sMuestra1='block';}
	document.getElementById('div_cara01disc').style.display=sMuestra1;
	}
function ajustar_cara02discv2multiple(){
	sMuestra1='none';
	if (window.document.frmedita.cara02discv2multiple.value==1){sMuestra1='block';}
	document.getElementById('lbl_cara02discv2multipleotro').style.display=sMuestra1;
	}
function ajustar_cara01discv2trastornos(){
	sMuestra1='none';
	if (window.document.frmedita.cara01discv2trastornos.value==1){sMuestra1='block';}
	document.getElementById('div_cara01discv2trastornos').style.display=sMuestra1;
	}
function ajustar_cara01discv2condicionmedica(){
	sMuestra1='none';
	if (window.document.frmedita.cara01discv2condicionmedica.value==1){sMuestra1='block';}
	document.getElementById('lbl_cara01discv2condmeddet').style.display=sMuestra1;
	}
<?php
	}
?>
// -->
</script>
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
    tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
  });
</script>
<script type="text/javascript" src="<?php echo $APP->rutacomun; ?>latex/MathJax.js?config=TeX-AMS_HTML-full"></script>
<?php
if ($bEstudiante){
?>
<form id="frmtablero" name="frmtablero" method="post" action="<?php echo $sUrlTablero; ?>">
</form>
<?php
	}
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2301.php" target="_blank">
<input id="r" name="r" type="hidden" value="2301" />
<input id="id2301" name="id2301" type="hidden" value="<?php echo $_REQUEST['cara01id']; ?>" />
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
<?php
$sComplemento='';
//if ($bDebug){$sComplemento='caracterizacion.php?debug=1&r=1';}
?>
<form id="frmedita" name="frmedita" method="post" action="<?php echo $sComplemento; ?>" autocomplete="off">
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
	if ($_REQUEST['cara01completa']!='S'){
		if (!$bEstudiante){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
			}
		}
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		if ($_REQUEST['cara01completa']=='S'){
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
if ($_REQUEST['cara01completa']!='S'){
	if ($bEstudiante){
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
		if ($_REQUEST['paso']>0){
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="Cerrar" value="Cerrar"/>
<?php
			}
		}
	}else{
	if ($_REQUEST['paso']>0){
		if ($bPuedeAbrir){
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Abrir" value="Abrir"/>
<?php
			}
		}
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2301'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<input id="ficha" name="ficha" type="hidden" value="<?php echo $_REQUEST['ficha']; ?>" />
<input id="boculta101" name="boculta101" type="hidden" value="<?php echo $_REQUEST['boculta101']; ?>" />
<input id="boculta102" name="boculta102" type="hidden" value="<?php echo $_REQUEST['boculta102']; ?>" />
<input id="boculta103" name="boculta103" type="hidden" value="<?php echo $_REQUEST['boculta103']; ?>" />
<input id="boculta104" name="boculta104" type="hidden" value="<?php echo $_REQUEST['boculta104']; ?>" />
<input id="boculta105" name="boculta105" type="hidden" value="<?php echo $_REQUEST['boculta105']; ?>" />
<input id="boculta106" name="boculta106" type="hidden" value="<?php echo $_REQUEST['boculta106']; ?>" />
<input id="boculta107" name="boculta107" type="hidden" value="<?php echo $_REQUEST['boculta107']; ?>" />
<input id="boculta108" name="boculta108" type="hidden" value="<?php echo $_REQUEST['boculta108']; ?>" />
<input id="boculta109" name="boculta109" type="hidden" value="<?php echo $_REQUEST['boculta109']; ?>" />
<input id="boculta110" name="boculta110" type="hidden" value="<?php echo $_REQUEST['boculta110']; ?>" />
<input id="boculta111" name="boculta111" type="hidden" value="<?php echo $_REQUEST['boculta111']; ?>" />
<input id="boculta112" name="boculta112" type="hidden" value="<?php echo $_REQUEST['boculta112']; ?>" />
<input id="boculta113" name="boculta113" type="hidden" value="<?php echo $_REQUEST['boculta113']; ?>" />
<input id="boculta2301" name="boculta2301" type="hidden" value="<?php echo $_REQUEST['boculta2301']; ?>" />
<?php
$bGrupo1=true;
$bGrupo2=true;
$bGrupo3=true;
$bGrupo4=true;
$bGrupo5=true;
$bGrupo6=true;
$bGrupo7=true;
$bGrupo8=true;
$bGrupo9=true;
$bGrupo10=true;
$bGrupo11=true;
$bGrupo12=true;
$bGrupo13=true;
//Div para ocultar
$bconexpande=true;
if ($bEstudiante){$bconexpande=false;}
if ($bconexpande){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<label class="Label30">
<input id="btexpande2301" name="btexpande2301" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2301,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2301']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2301" name="btrecoge2301" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2301,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2301']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2301" style="display:<?php if ($_REQUEST['boculta2301']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['cara01idperaca'];
?>
</label>
<label class="Label600">
<?php
echo $html_cara01idperaca;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idtercero" name="cara01idtercero" type="hidden" value="<?php echo $_REQUEST['cara01idtercero']; ?>"/>
<div id="div_cara01idtercero_llaves">
<?php
$bOculto=true;
if ($bEstudiante){
	$bOculto=true;
	}else{
	if ($_REQUEST['paso']!=2){$bOculto=false;}
	}
echo html_DivTerceroV2('cara01idtercero', $_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idtercero" class="L"><?php echo $cara01idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label60">
<?php
echo $ETI['cara01id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('cara01id', $_REQUEST['cara01id']);
?>
</label>
<label class="Label90">
<?php
$et_cara01completa=$ETI['msg_abierto'];
$et_cara01fechaencuesta='&nbsp;';
if ($_REQUEST['cara01completa']=='S'){
	$et_cara01completa=$ETI['msg_cerrado'];
	$et_cara01fechaencuesta=fecha_desdenumero($_REQUEST['cara01fechaencuesta']);
	}
echo html_oculto('cara01completa', $_REQUEST['cara01completa'], $et_cara01completa);
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01fechaencuesta'];
?>
</label>
<div class="Campo220">
<?php
echo html_oculto('cara01fechaencuesta', $_REQUEST['cara01fechaencuesta'], $et_cara01fechaencuesta);
?>
</div>
<div class="salto1px"></div>
</div>

</div>

<div class="salto1px"></div>
<?php
if ($bEstudiante){
	$aTitulo=array('', 'cara01fichaper', 'cara01fichafam', 'cara01fichaaca', 'cara01fichalab', 'cara01fichabien', 'cara01fichapsico', 'cara01fichadigital', 'cara01fichalectura', 'cara01ficharazona', 'cara01fichaingles', 'cara01fichabiolog', 'cara01fichafisica', 'cara01fichaquimica');
	//$sPendiente='<span class="border border-danger">Pendiente</span>';
	//$sHecho='<b>[HECHO]</b>';
	$sPendiente='<img src="'.$APP->rutacomun.'imagenes/mal_mini.png"/>';
	$sHecho='<img src="'.$APP->rutacomun.'imagenes/bien_mini.png"/>';
	$aEstado=array('',$sPendiente,$sPendiente,$sPendiente,$sPendiente,$sPendiente,$sPendiente,$sPendiente,$sPendiente,$sPendiente,$sPendiente,$sPendiente,$sPendiente,$sPendiente);
	for ($k=1;$k<=13;$k++){
		if ($_REQUEST[$aTitulo[$k]]>=0){
			if ($_REQUEST[$aTitulo[$k]]==1){$aEstado[$k]=$sHecho;}
			echo '<label><label class="Label220">'.$ETI[$aTitulo[$k]].'</label><a href="javascript:verficha('.$k.');">'.$aEstado[$k].'</a></label>';
			}
		}
	}
?>
<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']=='S'){
            $_REQUEST['bocultaResultados']=0;
			?>
<?php
// -- Inicia Grupo campos Resultados
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_resultados'];
?>
</label>
<input id="bocultaResultados" name="bocultaResultados" type="hidden" value="<?php echo $_REQUEST['bocultaResultados']; ?>" />
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcelResultados" name="btexcelResultados" type="button" value="Exportar" class="btMiniExcel" onclick="imprimeResultados();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpandeResultados" name="btexpandeResultados" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel('Resultados','block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['bocultaResultados']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecogeResultados" name="btrecogeResultados" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel('Resultados','none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['bocultaResultados']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_pResultados" style="display:<?php if ($_REQUEST['bocultaResultados']==0){echo 'block'; }else{echo 'none';} ?>;">
<input id="cara01psico_puntaje" name="cara01psico_puntaje" type="hidden" value="<?php echo $_REQUEST['cara01psico_puntaje']; ?>" />
<?php
if (false){
?>
<label class="Label220">
<?php
echo $ETI['cara01psico_puntaje'];
?>
</label>
<label class="Label60"><div id="div_cara01psico_puntaje">
<?php
echo html_oculto('cara01psico_puntaje',$_REQUEST['cara01psico_puntaje'], f2301_NombrePuntaje('puntaje',$_REQUEST['cara01psico_puntaje']));
?>
</div></label>
<?php
	}
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
if ($_REQUEST['cara01psico_puntaje']<17){
  echo $ETI['msg_psico_alto'];
  }else{
  if ($_REQUEST['cara01psico_puntaje']<24){
echo $ETI['msg_psico_medio'];
}else{
echo $ETI['msg_psico_bajo'];
}
  }
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichadigital'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01niveldigital',$_REQUEST['cara01niveldigital'], f2301_NombrePuntaje('digital',$_REQUEST['cara01niveldigital']));
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichalectura'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivellectura',$_REQUEST['cara01nivellectura'], f2301_NombrePuntaje('lectura',$_REQUEST['cara01nivellectura']));
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01ficharazona'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelrazona',$_REQUEST['cara01nivelrazona'], f2301_NombrePuntaje('razona',$_REQUEST['cara01nivelrazona']));
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichaingles'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelingles',$_REQUEST['cara01nivelingles'], f2301_NombrePuntaje('ingles',$_REQUEST['cara01nivelingles']));
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichabiolog'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelbiolog',$_REQUEST['cara01nivelbiolog'], f2301_NombrePuntaje('biolog',$_REQUEST['cara01nivelbiolog']));
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichafisica'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelfisica',$_REQUEST['cara01nivelfisica'], f2301_NombrePuntaje('fisica',$_REQUEST['cara01nivelfisica']));
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichaquimica'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelquimica',$_REQUEST['cara01nivelquimica'], f2301_NombrePuntaje('quimica',$_REQUEST['cara01nivelquimica']));
?>
</label>
<div class="salto1px"></div>
</div>
</div>
<?php
// -- Termina Grupo campos Resultados
?>
        <?php }
		}
	}
?>
<?php
if ($bGrupo1){
	$sEstilo='';
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==1){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha1"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande101" name="btexpande101" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(101,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta101']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge101" name="btrecoge101" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(101,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta101']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichaper'];
?>
</label>
<label class="Label130"><div id="div_cara01fichaper">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichaper']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichaper', $_REQUEST['cara01fichaper'], $sMuestra);
?>
</div></label>
<div class="salto1px"></div>
<div id="div_p101" style="display:<?php if ($_REQUEST['boculta101']==0){echo 'block'; }else{echo 'none';} ?>;">

<div class="GrupoCampos450">
<label class="Label60">
<?php
echo $ETI['cara01agnos'];
?>
</label>
<label class="Label60"><div id="div_cara01agnos">
<?php
$et_cara01agnos=$_REQUEST['cara01agnos'];
if ($_REQUEST['cara01agnos']==0){
	$et_cara01agnos='<span class="rojo">Sin Fecha de Nacimiento</span>';
	}
echo html_oculto('cara01agnos', $_REQUEST['cara01agnos'], $et_cara01agnos);
?>
</div></label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['cara01sexo'];
?>
</label>
<label class="Label160">
<?php
echo $html_cara01sexo;
?>
</label>
<?php
if (true){
	//Ejemplo de boton de ayuda
	echo html_BotonAyuda('cara01sexo','Información acerca del sexo');
	//echo html_DivAyudaLocal('botonayudasexoiden');
	}
?>
<div class="salto1px"></div>
<div id='div_ayuda_cara01sexo' class='GrupoCamposAyuda' style='display:none;'>
<?php
echo $ETI['ayuda_cara01sexo'];
?>
</div>
<div class="salto1px"></div>
<label class="Label">
<?php
echo $ETI['cara01sexov1identidadgen'];
?>
</label>
<label class="Label160">
<?php
echo $html_cara01sexov1identidadgen;
?>
</label>
<?php
if (true){
	//Ejemplo de boton de ayuda
	echo html_BotonAyuda('cara01sexov1identidadgen','Información acerca de identidad de género');
	//echo html_DivAyudaLocal('botonayudasexoiden');
	}
?>
<div class="salto1px"></div>
<div id='div_ayuda_cara01sexov1identidadgen' class='GrupoCamposAyuda' style='display:none;'>
<?php
echo $ETI['ayuda_cara01sexov1identidadgen'];
?>
</div>
<div class="salto1px"></div>
<label class="Label">
<?php
echo $ETI['cara01sexov1orientasexo'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01sexov1orientasexo;
?>
</label>
<?php
if (true){
	//Ejemplo de boton de ayuda
	echo html_BotonAyuda('cara01sexov1orientasexo','Información acerca de orientación del sexo');
	//echo html_DivAyudaLocal('botonayudasexoiden');
	}
?>
<div class="salto1px"></div>
<div id='div_ayuda_cara01sexov1orientasexo' class='GrupoCamposAyuda' style='display:none;'>
<?php
echo $ETI['ayuda_cara01sexov1orientasexo'];
?>
</div>
<div class="salto5px"></div>
<label class="Label130">
<?php
echo $ETI['cara01estcivil'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01estcivil;
?>
</label>
<div class="salto1px"></div>
<?php
echo $ETI['msg_grupospobla'];
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01raizal'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01raizal;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01palenquero'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01palenquero;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01rom'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01rom;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01afrocolombiano'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01afrocolombiano;
?>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara01otracomunnegras'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01otracomunnegras;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01indigenas'];
?>
</label>
<label>
<?php
echo $html_cara01indigenas;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['cara01pais'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01pais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01depto'];
?>
</label>
<label class="Label200">
<div id="div_cara01depto">
<?php
echo $html_cara01depto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01ciudad'];
?>
</label>
<label class="Label200">
<div id="div_cara01ciudad">
<?php
echo $html_cara01ciudad;
?>
</div>
</label>
<div class="salto1px"></div>
<?php
$sMuestra='none';
if ($_REQUEST['cara01pais']!='057'){$sMuestra='block';}
?>
<div id="div_ciudad" style="display:<?php echo $sMuestra; ?>">
<label class="L">
<?php
echo $ETI['cara01nomciudad'];
?>

<input id="cara01nomciudad" name="cara01nomciudad" type="text" value="<?php echo $_REQUEST['cara01nomciudad']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01nomciudad']; ?>"/>
</label>
</div>
<label class="L">
<?php
echo $ETI['cara01direccion'];
?>

<input id="cara01direccion" name="cara01direccion" type="text" value="<?php echo $_REQUEST['cara01direccion']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01direccion']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01estrato'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01estrato;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01zonares'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01zonares;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label90">
<?php
echo $ETI['cara01idzona'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01idcead'];
?>
</label>
<label class="Label200">
<div id="div_cara01idcead">
<?php
echo $html_cara01idcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label>
<?php
echo $ETI['cara01matconvenio'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01matconvenio;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label250">
<?php
echo $ETI['cara01victimadesplazado'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01victimadesplazado;
?>
</label>
<div class="salto1px"></div>
<?php
if ($bEstudiante){}
if (true){
?>
<input id="cara01idconfirmadesp" name="cara01idconfirmadesp" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadesp']; ?>"/>
<input id="cara01idconfirmadesp_td" name="cara01idconfirmadesp_td" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadesp_td']; ?>"/>
<input id="cara01idconfirmadesp_doc" name="cara01idconfirmadesp_doc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadesp_doc']; ?>"/>
<input id="cara01fechaconfirmadesp" name="cara01fechaconfirmadesp" type="hidden" value="<?php echo $_REQUEST['cara01fechaconfirmadesp']; ?>"/>
<?php
	}else{
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idconfirmadesp'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idconfirmadesp" name="cara01idconfirmadesp" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadesp']; ?>"/>
<div id="div_cara01idconfirmadesp_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('cara01idconfirmadesp', $_REQUEST['cara01idconfirmadesp_td'], $_REQUEST['cara01idconfirmadesp_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadesp" class="L"><?php echo $cara01idconfirmadesp_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['cara01fechaconfirmadesp'];
?>
</label>
<div class="Campo220">
<?php
echo html_oculto('cara01fechaconfirmadesp', $_REQUEST['cara01fechaconfirmadesp']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<div class="salto1px"></div>
<?php
	}
?>
<label class="Label250" title="Agencia Colombia para la Reintegraci&oacute;n">
<?php
echo $ETI['cara01victimaacr'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01victimaacr;
?>
</label>
<div class="salto1px"></div>
<?php
if ($bEstudiante){}
if (true){
?>
<input id="cara01idconfirmacr" name="cara01idconfirmacr" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmacr']; ?>"/>
<input id="cara01idconfirmacr_td" name="cara01idconfirmacr_td" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmacr_td']; ?>"/>
<input id="cara01idconfirmacr_doc" name="cara01idconfirmacr_doc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmacr_doc']; ?>"/>
<input id="cara01fechaconfirmacr" name="cara01fechaconfirmacr" type="hidden" value="<?php echo $_REQUEST['cara01fechaconfirmacr']; ?>"/>
<?php
	}else{
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idconfirmacr'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idconfirmacr" name="cara01idconfirmacr" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmacr']; ?>"/>
<div id="div_cara01idconfirmacr_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('cara01idconfirmacr', $_REQUEST['cara01idconfirmacr_td'], $_REQUEST['cara01idconfirmacr_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idconfirmacr" class="L"><?php echo $cara01idconfirmacr_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['cara01fechaconfirmacr'];
?>
</label>
<div class="Campo220">
<?php
echo html_oculto('cara01fechaconfirmacr', $_REQUEST['cara01fechaconfirmacr']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<div class="salto1px"></div>
<?php
	}
?>
<label class="Label250">
<?php
echo $ETI['cara01inpecfuncionario'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01inpecfuncionario;
?>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara01inpecrecluso'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01inpecrecluso;
$sMuestra='';
if ($_REQUEST['cara01inpecrecluso']!='S'){
	$sMuestra=' style="display:none"';
	}
?>
</label>
<div id="div_recluso"<?php echo $sMuestra; ?>>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01inpectiempocondena'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01inpectiempocondena;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01centroreclusion'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01centroreclusion;
?>
</label>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_contacto'];
?>
</label>
<div class="salto1px"></div>

<label class="Label160">
<?php
echo $ETI['cara01telefono1'];
?>
</label>
<label>
<input id="cara01telefono1" name="cara01telefono1" type="text" value="<?php echo $_REQUEST['cara01telefono1']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01telefono1']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara01telefono2'];
?>
</label>
<label>
<input id="cara01telefono2" name="cara01telefono2" type="text" value="<?php echo $_REQUEST['cara01telefono2']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01telefono2']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['cara01correopersonal'];
?>
<input id="cara01correopersonal" name="cara01correopersonal" type="text" value="<?php echo $_REQUEST['cara01correopersonal']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01correopersonal']; ?>" class="L"/>
</label>

<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo '<b>'.$ETI['msg_infocontacto'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['cara01nomcontacto'];
?>

<input id="cara01nomcontacto" name="cara01nomcontacto" type="text" value="<?php echo $_REQUEST['cara01nomcontacto']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01nomcontacto']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['cara01parentezcocontacto'];
?>

<input id="cara01parentezcocontacto" name="cara01parentezcocontacto" type="text" value="<?php echo $_REQUEST['cara01parentezcocontacto']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01parentezcocontacto']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['cara01celcontacto'];
?>
<input id="cara01celcontacto" name="cara01celcontacto" type="text" value="<?php echo $_REQUEST['cara01celcontacto']; ?>" maxlength="50" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01celcontacto']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['cara01correocontacto'];
?>
<input id="cara01correocontacto" name="cara01correocontacto" type="text" value="<?php echo $_REQUEST['cara01correocontacto']; ?>" maxlength="50" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01correocontacto']; ?>"/>
</label>

<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:500px">
<?php
echo $ETI['titulo_cara01saber11v1agno'];
?>
</label>
<div class="salto1px"></div>
<label class="Label350">
<?php
echo $ETI['cara01saber11v1agno'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01saber11v1agno;
$sMuestra='';
if ($_REQUEST['cara01saber11v1agno']<2016){
	$sMuestra=' style="display:none"';
	}
?>
</label>
<div id="div_saber11v1"<?php echo $sMuestra; ?>>
<div class="salto1px"></div>
<label class="Label350">
<?php
$sEtiqueta=$ETI['cara01saber11v1numreg'];
echo $sEtiqueta;
?>
</label>
<label class="Label250">
<input id="cara01saber11v1numreg" name="cara01saber11v1numreg" type="text" value="<?php echo $_REQUEST['cara01saber11v1numreg']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$sEtiqueta; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label160">
<b>
<?php
echo $ETI['cara01saber11v1puntglobal'];
?>
</b>
</label>
<label class="Label90">
<input id="cara01saber11v1puntglobal" name="cara01saber11v1puntglobal" type="number" min=0 max=500 value="<?php echo $_REQUEST['cara01saber11v1puntglobal']; ?>"/>
</label>

<label class="Label160">
<?php
echo $ETI['cara01saber11v1puntmatematicas'];
?>
</label>
<label class="Label90">
<input id="cara01saber11v1puntmatematicas" name="cara01saber11v1puntmatematicas" type="number" min=0 max=500 value="<?php echo $_REQUEST['cara01saber11v1puntmatematicas']; ?>"/>
</label>
<label class="Label190">
<?php
echo $ETI['cara01saber11v1puntciencias'];
?>
</label>
<label class="Label90">
<input id="cara01saber11v1puntciencias" name="cara01saber11v1puntciencias" type="number" min=0 max=500 value="<?php echo $_REQUEST['cara01saber11v1puntciencias']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01saber11v1puntleccritica'];
?>
</label>
<label class="Label90">
<input id="cara01saber11v1puntleccritica" name="cara01saber11v1puntleccritica" type="number" min=0 max=500 value="<?php echo $_REQUEST['cara01saber11v1puntleccritica']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['cara01saber11v1puntingles'];
?>
</label>
<label class="Label90">
<input id="cara01saber11v1puntingles" name="cara01saber11v1puntingles" type="number" min=0 max=500 value="<?php echo $_REQUEST['cara01saber11v1puntingles']; ?>"/>
</label>
<label class="Label190">
<?php
echo $ETI['cara01saber11v1puntsociales'];
?>
</label>
<label class="Label90">
<input id="cara01saber11v1puntsociales" name="cara01saber11v1puntsociales" type="number" min=0 max=500 value="<?php echo $_REQUEST['cara01saber11v1puntsociales']; ?>"/>
</label>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:500px">
<?php
if ($_REQUEST['cara01discversion']==2){
	echo $ETI['msg_discapacidades_v2'];
	}else{
	echo $ETI['msg_discapacidades'];
	}
	
?>
</label>
<?php
if ($bEstudiante){
?>
<input id="cara01discversion" name="cara01discversion" type="hidden" value="<?php echo $_REQUEST['cara01discversion']; ?>"/>
<?php
	}else{
?>
<label class="Label250">
<?php
echo $ETI['cara01discversion'];
?>
</label>
<label class="Label30">
<?php
echo html_oculto('cara01discversion', $_REQUEST['cara01discversion']);
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<?php
if ($_REQUEST['cara01discversion']!=0){
?>
<input id="cara01discsensorial" name="cara01discsensorial" type="hidden" value="<?php echo $_REQUEST['cara01discsensorial']; ?>"/>
<input id="cara01discsensorialotra" name="cara01discsensorialotra" type="hidden" value="<?php echo $_REQUEST['cara01discsensorialotra']; ?>"/>
<input id="cara01discfisica" name="cara01discfisica" type="hidden" value="<?php echo $_REQUEST['cara01discfisica']; ?>"/>
<input id="cara01discfisicaotra" name="cara01discfisicaotra" type="hidden" value="<?php echo $_REQUEST['cara01discfisicaotra']; ?>"/>
<input id="cara01disccognitiva" name="cara01disccognitiva" type="hidden" value="<?php echo $_REQUEST['cara01disccognitiva']; ?>"/>
<input id="cara01disccognitivaotra" name="cara01disccognitivaotra" type="hidden" value="<?php echo $_REQUEST['cara01disccognitivaotra']; ?>"/>
<?php
	}else{
?>
<label class="Label130">
<?php
echo $ETI['cara01discsensorial'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discsensorial;
$sEstilo=' style="display:none"';
if ($_REQUEST['cara01discsensorial']==9){$sEstilo='';}
?>
</label>
<label id="lbl_cara01discsensorialotra" class="L"<?php echo $sEstilo; ?>>
<?php
echo $ETI['msg_especifique'];
?>

<input id="cara01discsensorialotra" name="cara01discsensorialotra" type="text" value="<?php echo $_REQUEST['cara01discsensorialotra']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01discsensorialotra']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01discfisica'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discfisica;
$sEstilo=' style="display:none"';
if ($_REQUEST['cara01discfisica']==9){$sEstilo='';}
?>
</label>
<label id="lbl_cara01discfisicaotra" class="L"<?php echo $sEstilo; ?>>
<?php
echo $ETI['msg_especifique'];
?>

<input id="cara01discfisicaotra" name="cara01discfisicaotra" type="text" value="<?php echo $_REQUEST['cara01discfisicaotra']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01discfisicaotra']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01disccognitiva'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01disccognitiva;
$sEstilo=' style="display:none"';
if ($_REQUEST['cara01disccognitiva']==9){$sEstilo='';}
?>
</label>
<label id="lbl_cara01disccognitivaotra" class="L"<?php echo $sEstilo; ?>>
<?php
echo $ETI['msg_especifique'];
?>

<input id="cara01disccognitivaotra" name="cara01disccognitivaotra" type="text" value="<?php echo $_REQUEST['cara01disccognitivaotra']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01disccognitivaotra']; ?>"/>
</label>
<?php
	}
?>

<?php
$bEntra=false;
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
?>
<label class="Label500">
<?php
echo $ETI['cara01discv2tiene'];
?>
</label>
<label>
<?php
echo $html_cara01discv2tiene;
?>
</label>
<?php
	}else{
?>
<input id="cara01discv2tiene" name="cara01discv2tiene" type="hidden" value="<?php echo $_REQUEST['cara01discv2tiene']; ?>"/>
<?php
	}
?>
<?php
$bEntra=false;
if ($_REQUEST['cara01discversion']==1){$bEntra=true;}
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
?>
<div class="salto1px"></div>
<?php
$sEstilo='';
if ($_REQUEST['cara01discversion']==2){
	$sEstilo=' style="display:none"';
	if ($_REQUEST['cara01discv2tiene']!=0){$sEstilo='';}
	}
?>
<div id="div_cara01disc"<?php echo $sEstilo; ?>>
<label class="Label220">
<?php
echo $ETI['cara01discv2sensorial'];
?>
</label>
<label>
<?php
echo $html_cara01discv2sensorial;
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02discv2intelectura'];
?>
</label>
<label>
<?php
echo $html_cara02discv2intelectura;
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02discv2fisica'];
?>
</label>
<label>
<?php
echo $html_cara02discv2fisica;
?>
</label>
<div class="salto1px"></div>
<?php
	if ($_REQUEST['cara01discversion']==1){
		echo '<label class="Label350">'.$ETI['cara02discv2psico'].'</label>';
		}else{
		echo '<label class="Label220">'.$ETI['cara02discv2psico_v2'].'</label>';
		}
?>

<label>
<?php
echo $html_cara02discv2psico;
?>
</label>
<?php
	}else{
?>
<input id="cara01discv2sensorial" name="cara01discv2sensorial" type="hidden" value="<?php echo $_REQUEST['cara01discv2sensorial']; ?>"/>
<input id="cara02discv2intelectura" name="cara02discv2intelectura" type="hidden" value="<?php echo $_REQUEST['cara02discv2intelectura']; ?>"/>
<input id="cara02discv2fisica" name="cara02discv2fisica" type="hidden" value="<?php echo $_REQUEST['cara02discv2fisica']; ?>"/>
<input id="cara02discv2psico" name="cara02discv2psico" type="hidden" value="<?php echo $_REQUEST['cara02discv2psico']; ?>"/>
<?php
	}
$bEntra=false;
if ($_REQUEST['cara01discversion']==1){$bEntra=true;}
if ($bEntra){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02discv2sistemica'];
?>
</label>
<label>
<?php
echo $html_cara02discv2sistemica;
?>
</label>
<label class="L">
<?php
	$sEtiqueta=$ETI['cara02discv2sistemicaotro'];
	echo $sEtiqueta;
?>
<input id="cara02discv2sistemicaotro" name="cara02discv2sistemicaotro" type="text" value="<?php echo $_REQUEST['cara02discv2sistemicaotro']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$sEtiqueta; ?>"/>
</label>
<?php
	}else{
?>
<input id="cara02discv2sistemica" name="cara02discv2sistemica" type="hidden" value="<?php echo $_REQUEST['cara02discv2sistemica']; ?>"/>
<input id="cara02discv2sistemicaotro" name="cara02discv2sistemicaotro" type="hidden" value="<?php echo $_REQUEST['cara02discv2sistemicaotro']; ?>"/>
<?php
	}
$bEntra=false;
if ($_REQUEST['cara01discversion']==1){$bEntra=true;}
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02discv2multiple'];
?>
</label>
<label>
<?php
echo $html_cara02discv2multiple;
?>
</label>
<?php
$sEstilo='';
if ($_REQUEST['cara01discversion']==2){
	$sEstilo=' style="display:none"';
	if ($_REQUEST['cara02discv2multiple']==1){$sEstilo='';}
	}
?>
<label id="lbl_cara02discv2multipleotro" class="L" <?php echo $sEstilo; ?>>
<?php
	$sEtiqueta=$ETI['cara02discv2multipleotro_v2'];
	if ($_REQUEST['cara01discversion']==2){$sEtiqueta=$ETI['cara02discv2multipleotro_v2'];}
	echo $sEtiqueta;
?>
<input id="cara02discv2multipleotro" name="cara02discv2multipleotro" type="text" value="<?php echo $_REQUEST['cara02discv2multipleotro']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$sEtiqueta; ?>"/>
</label>
<?php
	}else{
?>
<input id="cara02discv2multiple" name="cara02discv2multiple" type="hidden" value="<?php echo $_REQUEST['cara02discv2multiple']; ?>"/>
<input id="cara02discv2multipleotro" name="cara02discv2multipleotro" type="hidden" value="<?php echo $_REQUEST['cara02discv2multipleotro']; ?>"/>
<?php
	}
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['cara01perayuda'];
?>
</label>
<label class="Label220">&nbsp;</label>
<label>
<?php
echo $html_cara01perayuda;
$sEstilo=' style="display:none"';
if ($_REQUEST['cara01perayuda']==-1){$sEstilo='';}
if ($_REQUEST['cara01perayuda']==11){$sEstilo='';}
?>
</label>
<label id="lbl_cara01perotraayuda" class="L"<?php echo $sEstilo; ?>>
<?php
echo $ETI['cara01perotraayuda'];
?>

<input id="cara01perotraayuda" name="cara01perotraayuda" type="text" value="<?php echo $_REQUEST['cara01perotraayuda']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01perotraayuda']; ?>"/>
</label>
<div class="salto1px"></div>
<input id="cara01discv2soporteorigen" name="cara01discv2soporteorigen" type="hidden" value="<?php echo $_REQUEST['cara01discv2soporteorigen']; ?>"/>
<input id="cara01discv2archivoorigen" name="cara01discv2archivoorigen" type="hidden" value="<?php echo $_REQUEST['cara01discv2archivoorigen']; ?>"/>
<?php
$sEstilo=' style="display:none"';
if ($_REQUEST['cara01discversion']==2){$sEstilo='';}
?>
<div class="GrupoCampos300"<?php echo $sEstilo; ?>>
<div class="salto1px"></div>
<label class="TituloGrupo">
<?php
echo $ETI['msg_certificaciondisc']
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01discv2archivoorigen" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['cara01discv2soporteorigen'], (int)$_REQUEST['cara01discv2archivoorigen']);
?>
</div>
<label class="Label30">
<input type="button" id="banexacara01discv2archivoorigen" name="banexacara01discv2archivoorigen" value="Anexar" class="btAnexarS" onclick="carga_cara01discv2archivoorigen()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['cara01id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<label class="Label30">
<?php
$sEstilo=' style="display:none"';
if (!$bEstudiante){$sEstilo='';}
?>
<div id="div_boton_cara01discv2archivoorigen"<?php echo $sEstilo; ?>>
<input type="button" id="beliminacara01discv2archivoorigen" name="beliminacara01discv2archivoorigen" value="Eliminar" class="btBorrarS" onclick="eliminacara01discv2archivoorigen()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['cara01discv2archivoorigen']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</div>
</label>
<div class="salto1px"></div>
</div>
<?php
//Termina el div que muestra las incapacidades. div_cara01disc
?>
</div>

<?php
$bEntra=false;
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
?>
<div class="salto1px"></div>
<hr />
<div class="salto1px"></div>
<label class="Label500">
<?php
echo $ETI['cara01discv2trastornos'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discv2trastornos;
?>
</label>
<?php
	}else{
?>
<input id="cara01discv2trastornos" name="cara01discv2trastornos" type="hidden" value="<?php echo $_REQUEST['cara01discv2trastornos']; ?>"/>
<?php
	}
?>
<div class="salto1px"></div>
<?php
$sEstilo=' style="display:none"';
if ($_REQUEST['cara01discversion']==2){
	if ($_REQUEST['cara01discv2trastornos']!=0){$sEstilo='';}
	}
?>
<div id="div_cara01discv2trastornos"<?php echo $sEstilo; ?>>
<label class="Label220">
<?php
echo $ETI['cara01discv2trastaprende'];
?>
</label>
<label>
<?php
echo $html_cara01discv2trastaprende;
?>
</label>
</div>
<div class="salto1px"></div>

<?php
$bEntra=false;
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $AYU['cara01discv2trastornos'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>

<div class="salto1px"></div>
<hr />
<div class="salto1px"></div>
<label class="Label500">
<?php
echo $ETI['cara01discv2contalento'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discv2contalento;
?>
</label>
<?php
	}else{
?>
<input id="cara01discv2contalento" name="cara01discv2contalento" type="hidden" value="<?php echo $_REQUEST['cara01discv2contalento']; ?>"/>
<?php
	}
?>

<div class="salto1px"></div>
<?php
$sEstilo=' style="display:none"';
if ($_REQUEST['cara01discversion']==2){
	if ($_REQUEST['cara01discv2contalento']!=0){$sEstilo='';}
	}
?>
<div id="div_cara01discv2contalento"<?php echo $sEstilo; ?>>
<?php
$bEntra=false;
if ($_REQUEST['cara01discversion']==1){$bEntra=true;}
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02talentoexcepcional'];
?>
</label>
<label>
<?php
echo $html_cara02talentoexcepcional;
?>
</label>
<?php
	}else{
?>
<input id="cara02talentoexcepcional" name="cara02talentoexcepcional" type="hidden" value="<?php echo $_REQUEST['cara02talentoexcepcional']; ?>"/>
<?php
	}
?>
<div class="salto1px"></div>
</div>

<?php
$bEntra=false;
if ($_REQUEST['cara01discversion']==2){$bEntra=true;}
if ($bEntra){
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $AYU['cara01discv2contalento'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<hr />
<div class="salto1px"></div>
<label class="Label500">
<?php
echo $ETI['cara01discv2pruebacoeficiente'];
?>
</label>
<label>
<?php
echo $html_cara01discv2pruebacoeficiente;
?>
</label>

<div class="salto1px"></div>
<label class="Label500">
<?php
echo $ETI['cara01discv2condicionmedica'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discv2condicionmedica;
?>
</label>
<?php
$sEstilo=' style="display:none"';
if ($_REQUEST['cara01discv2condicionmedica']!=0){$sEstilo='';}
?>
<label id="lbl_cara01discv2condmeddet" class="L"<?php echo $sEstilo; ?>>
<?php
echo $ETI['cara01discv2condmeddet'];
?>

<input id="cara01discv2condmeddet" name="cara01discv2condmeddet" type="text" value="<?php echo $_REQUEST['cara01discv2condmeddet']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01discv2condmeddet']; ?>"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $AYU['cara01discv2condicionmedica'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
	}else{
?>
<input id="cara01discv2condicionmedica" name="cara01discv2condicionmedica" type="hidden" value="<?php echo $_REQUEST['cara01discv2condicionmedica']; ?>"/>
<input id="cara01discv2condmeddet" name="cara01discv2condmeddet" type="hidden" value="<?php echo $_REQUEST['cara01discv2condmeddet']; ?>"/>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
$bConConfirmarDiscapacidad=false;
if (!$bEstudiante){
	$bConConfirmarDiscapacidad=true;
	/*
	switch($_REQUEST['cara01discversion']){
		case 0:
		break;
		case 1:
		break;
		case 2:
		break;
		}
	*/
	}
if (!$bConConfirmarDiscapacidad){
?>
<input id="cara01idconfirmadisc" name="cara01idconfirmadisc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadisc']; ?>"/>
<input id="cara01idconfirmadisc_td" name="cara01idconfirmadisc_td" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadisc_td']; ?>"/>
<input id="cara01idconfirmadisc_doc" name="cara01idconfirmadisc_doc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadisc_doc']; ?>"/>
<input id="cara01fechaconfirmadisc" name="cara01fechaconfirmadisc" type="hidden" value="<?php echo $_REQUEST['cara01fechaconfirmadisc']; ?>"/>
<?php
	}else{
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idconfirmadisc'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idconfirmadisc" name="cara01idconfirmadisc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadisc']; ?>"/>
<div id="div_cara01idconfirmadisc_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('cara01idconfirmadisc', $_REQUEST['cara01idconfirmadisc_td'], $_REQUEST['cara01idconfirmadisc_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc" class="L"><?php echo $cara01idconfirmadisc_rs; ?></div>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['cara01fechaconfirmadisc'];
?>
</label>
<label class="Label220">
<?php
echo html_oculto('cara01fechaconfirmadisc', $_REQUEST['cara01fechaconfirmadisc'], formato_FechaLargaDesdeNumero($_REQUEST['cara01fechaconfirmadisc']));
?>
</label>
<?php
if ($_REQUEST['paso']!=0){
?>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label130">
<input id="cmdConfirmarDisc" name="cmdConfirmarDisc" type="button" class="BotonAzul" value="Confirmar" onclick="javascritp:confirmadisc();" title="Confirmar datos de discapacidad" />
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(1);
			}else{
			echo html_2201Tablero(1);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo2){
	$sEstilo='';
	if ($_REQUEST['cara01fichafam']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==2){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha2"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande102" name="btexpande102" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(102,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta102']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge102" name="btrecoge102" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(102,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta102']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichafam'];
?>
</label>
<label class="Label130"><div id="div_cara01fichafam">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichafam']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichafam', $_REQUEST['cara01fichafam'], $sMuestra);
?>
</div></label>
<div class="salto1px"></div>
<div id="div_p102" style="display:<?php if ($_REQUEST['boculta102']==0){echo 'block'; }else{echo 'none';} ?>;">

<label class="Label450">
<?php
echo $ETI['cara01fam_tipovivienda'];
?>
</label>
<label>
<?php
echo $html_cara01fam_tipovivienda;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_vivecon'];
?>
</label>
<label>
<?php
echo $html_cara01fam_vivecon;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_numpersgrupofam'];
?>
</label>
<label>
<?php
echo $html_cara01fam_numpersgrupofam;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_hijos'];
?>
</label>
<label>
<?php
echo $html_cara01fam_hijos;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01sexo']=='F' && $_REQUEST['cara01fam_hijos']>=1 && $_REQUEST['cara01fam_hijos']<=4){
	$sMuestra='';
	}
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01fam_madrecabeza"<?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara01fam_madrecabeza'];
?>
</label>
<label class="Label90">
<?php
echo $html_cara01fam_madrecabeza;
?>
</label>
</div>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_personasacargo'];
?>
</label>
<label>
<?php
echo $html_cara01fam_personasacargo;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_dependeecon'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01fam_dependeecon;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_escolaridadpadre'];
?>
</label>
<label>
<?php
echo $html_cara01fam_escolaridadpadre;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_escolaridadmadre'];
?>
</label>
<label>
<?php
echo $html_cara01fam_escolaridadmadre;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_numhermanos'];
?>
</label>
<label>
<?php
echo $html_cara01fam_numhermanos;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_posicionherm'];
?>
</label>
<label>
<?php
echo $html_cara01fam_posicionherm;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_familiaunad'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01fam_familiaunad;
?>
</label>
<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(2);
			}else{
			echo html_2201Tablero(2);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo3){
	$sEstilo='';
	if ($_REQUEST['cara01fichaaca']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==3){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha3"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande103" name="btexpande103" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(103,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta103']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge103" name="btrecoge103" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(103,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta103']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichaaca'];
?>
</label>
<label class="Label130"><div id="div_cara01fichaaca">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichaaca']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichaaca', $_REQUEST['cara01fichaaca'], $sMuestra);
?>
</div></label>
<div class="salto1px"></div>
<div id="div_p103" style="display:<?php if ($_REQUEST['boculta103']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
if($bAntiguo==false) {
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_tipocolegio'];
?>
</label>
<label>
<?php
echo $html_cara01acad_tipocolegio;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01acad_modalidadbach'];
?>
</label>
<label>
<?php
echo $html_cara01acad_modalidadbach;
?>
</label>
<div class="salto1px"></div>
<?php
}
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_estudioprev'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01acad_estudioprev;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01acad_ultnivelest'];
?>
</label>
<label>
<?php
echo $html_cara01acad_ultnivelest;
?>
</label>
<div class="salto1px"></div>
<?php
if($bAntiguo==false) {
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_tiemposinest'];
?>
</label>
<label>
<?php
echo $html_cara01acad_tiemposinest;
?>
</label>
<div class="salto1px"></div>
<?php
}
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_obtubodiploma'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01acad_obtubodiploma;
?>
</label>
<div class="salto1px"></div>
<?php
if($bAntiguo==false) {
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_hatomadovirtual'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01acad_hatomadovirtual;
?>
</label>
<div class="salto1px"></div>
<?php
}
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_razonestudio'];
?>
</label>
<label>
<?php
echo $html_cara01acad_razonestudio;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01acad_primeraopc'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01acad_primeraopc;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01acad_primeraopc']=='N'){
	$sMuestra='';
	}
?>
</label>
<label id="label_programagusto" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01acad_programagusto'];
?>

<input id="cara01acad_programagusto" name="cara01acad_programagusto" type="text" value="<?php echo $_REQUEST['cara01acad_programagusto']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01acad_programagusto']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01acad_razonunad'];
?>
</label>
<label>
<?php
echo $html_cara01acad_razonunad;
?>
</label>
<div class="salto1px"></div>
<?php
if($bAntiguo==true) {
?>
<label class="Label450">
<?php
echo $ETI['cara01acadhatenidorecesos'];
?>
</label>
<label class="Label90">
<?php
echo $html_cara01acadhatenidorecesos;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01acadhatenidorecesos']=='S'){
	$sMuestra='';
	}
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01acadrazonreceso"<?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara01acadrazonreceso'];
?>
</label>
<label>
<?php
echo $html_cara01acadrazonreceso;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01acadrazonreceso']=='S'){
	$sMuestra='';
	}
?>
</label>
<label id="lbl_cara01acadrazonrecesodetalle" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01acadrazonrecesodetalle'];
?>
<input id="cara01acadrazonrecesodetalle" name="cara01acadrazonrecesodetalle" type="text" value="<?php echo $_REQUEST['cara01acadrazonrecesodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01acadrazonrecesodetalle']; ?>"/>
</label>
</div>
<div class="salto1px"></div>
<?php
}
?>
<?php
echo '<b>'.$ETI['cara01campus'].'</b>';
?>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara01campus_compescrito'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_compescrito;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara01campus_portatil'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_portatil;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01campus_tableta'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_tableta;
?>
</label>
<label class="Label200">
<?php
echo $ETI['cara01campus_telefono'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_telefono;
?>
</label>

<div class="salto1px"></div>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01campus_energia'];
?>
</label>
<label>
<?php
echo $html_cara01campus_energia;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01campus_internetreside'];
?>
</label>
<label>
<?php
echo $html_cara01campus_internetreside;
?>
</label>
<div class="salto1px"></div>
<?php
if($bAntiguo==false) {
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_expvirtual'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01campus_expvirtual;
?>
</label>
<div class="salto1px"></div>
<?php
}
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_ofimatica'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01campus_ofimatica;
?>
</label>
<div class="salto1px"></div>
<?php
if($bAntiguo==false) {
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_foros'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01campus_foros;
?>
</label>
<div class="salto1px"></div>
<?php
}
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_conversiones'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01campus_conversiones;
?>
</label>
<div class="salto1px"></div>
<?php
if($bAntiguo==false) {
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_usocorreo'];
?>
</label>
<label>
<?php
echo $html_cara01campus_usocorreo;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_usocorreounad'];
?>
</label>
<label class="Label90">
<?php
echo $html_cara01campus_usocorreounad;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01campus_usocorreounad']==4){
	$sMuestra='';
	}
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01campus_usocorreounadno"<?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara01campus_usocorreounadno'];
?>
</label>
<label>
<?php
echo $html_cara01campus_usocorreounadno;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01campus_usocorreounadno']==5){
	$sMuestra='';
	}
?>
</label>
<label id="lbl_cara01campus_usocorreounadnodetalle" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01campus_usocorreounadnodetalle'];
?>
<input id="cara01campus_usocorreounadnodetalle" name="cara01campus_usocorreounadnodetalle" type="text" value="<?php echo $_REQUEST['cara01campus_usocorreounadnodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01campus_usocorreounadnodetalle']; ?>"/>
</label>
</div>
<div class="salto1px"></div>
<?php
}
?>
<?php
echo '<b>'.$ETI['cara01campus_aprend'].'</b>';
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01campus_aprendtexto'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_aprendtexto;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01campus_aprendvideo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_aprendvideo;
?>
</label>
<label class="Label200">
<?php
echo $ETI['cara01campus_aprendmapas'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_aprendmapas;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01campus_aprendeanima'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_aprendeanima;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01campus_mediocomunica'];
?>
</label>
<label>
<?php
echo $html_cara01campus_mediocomunica;
?>
</label>
<?php
if($bAntiguo==true) {
?>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01campus_medioactivunad'];
?>
</label>
<label>
<?php
echo $html_cara01campus_medioactivunad;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01campus_medioactivunad']==6){
	$sMuestra='';
	}
?>
</label>
<label id="lbl_cara01campus_medioactivunaddetalle" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01campus_medioactivunaddetalle'];
?>
<input id="cara01campus_medioactivunaddetalle" name="cara01campus_medioactivunaddetalle" type="text" value="<?php echo $_REQUEST['cara01campus_medioactivunaddetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01campus_medioactivunaddetalle']; ?>"/>
</label>
<?php
}
?>
<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(3);
			}else{
			echo html_2201Tablero(3);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo4){
	$sEstilo='';
	if ($_REQUEST['cara01fichalab']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==4){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha4"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande104" name="btexpande104" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(104,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta104']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge104" name="btrecoge104" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(104,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta104']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichalab'];
?>
</label>
<label class="Label130"><div id="div_cara01fichalab">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichalab']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichalab', $_REQUEST['cara01fichalab'], $sMuestra);
?>
</div></label>
<div class="salto1px"></div>
<div id="div_p104" style="display:<?php if ($_REQUEST['boculta104']==0){echo 'block'; }else{echo 'none';} ?>;">

<label class="Label450">
<?php
echo $ETI['cara01lab_situacion'];
?>
</label>
<label>
<?php
echo $html_cara01lab_situacion;
$sMuestra1=' style="display:none"';
$sMuestra2=' style="display:none"';
$sMuestra3=' style="display:none"';
$sMuestra4=' style="display:none"';
$sMuestra5=' style="display:none"';
$sMuestra6=' style="display:none"';
if ($_REQUEST['cara01lab_situacion']==1){
	$sMuestra1='';
	$sMuestra2='';
	$sMuestra3='';
	$sMuestra6='';
	}
if ($_REQUEST['cara01lab_situacion']==2){
	$sMuestra1='';
	$sMuestra4='';
	$sMuestra6='';
	}
if ($_REQUEST['cara01lab_situacion']==3){
	$sMuestra3='';
	$sMuestra5='';
	$sMuestra6='';
	}
if ($_REQUEST['cara01lab_situacion']==4){
	$sMuestra5='';
	$sMuestra6='';
	}
?>
</label>
<div class="salto1px"></div>
<div id="div_lab1"<?php echo $sMuestra1; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_sector'];
?>
</label>
<label>
<?php
echo $html_cara01lab_sector;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_caracterjuri'];
?>
</label>
<label>
<?php
echo $html_cara01lab_caracterjuri;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_cargo'];
?>
</label>
<label>
<?php
echo $html_cara01lab_cargo;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_antiguedad'];
?>
</label>
<label>
<?php
echo $html_cara01lab_antiguedad;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_lab2"<?php echo $sMuestra2; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_tipocontrato'];
?>
</label>
<label>
<?php
echo $html_cara01lab_tipocontrato;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_lab3"<?php echo $sMuestra3; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_rangoingreso'];
?>
</label>
<label>
<?php
echo $html_cara01lab_rangoingreso;
?>
</label>
</div>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_tiempoacadem'];
?>
</label>
<label>
<?php
echo $html_cara01lab_tiempoacadem;
?>
</label>
<div class="salto1px"></div>
<div id="div_lab4"<?php echo $sMuestra4; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_tipoempresa'];
?>
</label>
<label>
<?php
echo $html_cara01lab_tipoempresa;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_tiempoindepen'];
?>
</label>
<label>
<?php
echo $html_cara01lab_tiempoindepen;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_lab5"<?php echo $sMuestra5; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_debebusctrab'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01lab_debebusctrab;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_lab6"<?php echo $sMuestra6; ?>>
<label class="Label450">
<?php
if($bAntiguo==false) {
	echo $ETI['cara01lab_origendinero'];
	} else {
	echo $ETI['cara01lab_origendineroantiguo'];
	}
?>
</label>
<label>
<?php
echo $html_cara01lab_origendinero;
?>
</label>
</div>
<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(4);
			}else{
			echo html_2201Tablero(4);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo5){
	$sEstilo='';
	if ($_REQUEST['cara01fichabien']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==5){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha5"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande105" name="btexpande105" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(105,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta105']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge105" name="btrecoge105" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(105,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta105']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichabien'];
?>
</label>
<label class="Label130"><div id="div_cara01fichabien">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichabien']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichabien', $_REQUEST['cara01fichabien'], $sMuestra);
?>
</div></label>
<div class="salto1px"></div>
<div id="div_p105" style="display:<?php if ($_REQUEST['boculta105']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
$bEntra=false;
if ($_REQUEST['cara01bienversion']==0){$bEntra=true;}
if ($bEntra){
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bien_activ'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bien_baloncesto'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_baloncesto;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bien_voleibol'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_voleibol;
?>
</label>
<label class="Label200">
<?php
echo $ETI['cara01bien_futbolsala'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_futbolsala;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bien_artesmarc'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_artesmarc;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bien_tenisdemesa'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_tenisdemesa;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bien_ajedrez'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_ajedrez;
?>
</label>
<label class="Label200">
<?php
echo $ETI['cara01bien_juegosautoc'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_juegosautoc;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bien_interesrepdeporte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_interesrepdeporte;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bien_interesrepdeporte']=='S'){
	$sMuestra='';
	}
?>
</label>
<label id="label_interesrepdeporte" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_deporteint'];
?>

<input id="cara01bien_deporteint" name="cara01bien_deporteint" type="text" value="<?php echo $_REQUEST['cara01bien_deporteint']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bien_deporteint']; ?>"/>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo ''.$ETI['cara01bien_recr'].'';
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01bien_teatro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_teatro;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_danza'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_musica'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_musica;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_circo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_circo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bien_artplast'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_artplast;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_cuenteria'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_cuenteria;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bien_interesreparte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_interesreparte;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bien_interesreparte']=='S'){
	$sMuestra='';
	}
?>
</label>
<label id="label_interesreparte" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_arteint'];
?>

<input id="cara01bien_arteint" name="cara01bien_arteint" type="text" value="<?php echo $_REQUEST['cara01bien_arteint']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bien_arteint']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_interpreta'];
?>
</label>
<label>
<?php
echo $html_cara01bien_interpreta;
$sMuestra=' style="display:none"';
$bEntra=false;
if ($_REQUEST['cara01bien_interpreta']!=''){
	if ($_REQUEST['cara01bien_interpreta']!=-1){
		$bEntra=true;
		}
	}
if ($bEntra){
	$sMuestra='';
	}
?>
</label>
<div class="salto1px"></div>
<div id="div_bien_nivelinter"<?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara01bien_nivelinter'];
?>
</label>
<label>
<?php
echo $html_cara01bien_nivelinter;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ''.$ETI['cara01bien_danzatipo'].'';
?>
<div class="salto1px"></div>
<label>
<?php
echo $ETI['cara01bien_danza_mod'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza_mod;
?>
</label>
<label>
<?php
echo $ETI['cara01bien_danza_clas'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza_clas;
?>
</label>
<div class="salto1px"></div>
<label>
<?php
echo $ETI['cara01bien_danza_cont'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza_cont;
?>
</label>
<label>
<?php
echo $ETI['cara01bien_danza_folk'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza_folk;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bien_danza_mod']=='S'){$sMuestra='';}
if ($_REQUEST['cara01bien_danza_clas']=='S'){$sMuestra='';}
if ($_REQUEST['cara01bien_danza_cont']=='S'){$sMuestra='';}
if ($_REQUEST['cara01bien_danza_folk']=='S'){$sMuestra='';}
?>
</label>
<div class="salto1px"></div>
<div id="div_bien_niveldanza"<?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara01bien_niveldanza'];
?>
</label>
<label>
<?php
echo $html_cara01bien_niveldanza;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_emprendimiento'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_emprendedor'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_emprendedor;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bien_emprendedor']=='S'){
	$sMuestra='';
	}
?>
</label>
<label class="L" id="label_nombreemp"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_nombreemp'];
?>

<input id="cara01bien_nombreemp" name="cara01bien_nombreemp" type="text" value="<?php echo $_REQUEST['cara01bien_nombreemp']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bien_nombreemp']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_capacempren'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_capacempren;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bien_capacempren']=='S'){
	$sMuestra='';
	}
?>
</label>
<label class="L" id="lbl_bien_capacempren"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_tipocapacita'];
?>

<input id="cara01bien_tipocapacita" name="cara01bien_tipocapacita" type="text" value="<?php echo $_REQUEST['cara01bien_tipocapacita']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bien_tipocapacita']; ?>"/>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_estilodevida'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_impvidasalud'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_impvidasalud;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_estraautocuid'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_estraautocuid;
?>
</label>
<div class="salto1px"></div>
</div>


<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo ''.$ETI['cara01bien_pv'].'';
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_pv_personal'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_personal;
?>
</label>
<div class="salto1px"></div>
</div>

<?php
if (false){
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01bien_pv_familiar'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_familiar;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_pv_academ'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_academ;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_pv_labora'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_labora;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_pv_pareja'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_pareja;
?>
</label>
<?php
	}else{
?>
<input id="cara01bien_pv_familiar" name="cara01bien_pv_familiar" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_familiar']; ?>"/>
<input id="cara01bien_pv_academ" name="cara01bien_pv_academ" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_academ']; ?>"/>
<input id="cara01bien_pv_labora" name="cara01bien_pv_labora" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_labora']; ?>"/>
<input id="cara01bien_pv_pareja" name="cara01bien_pv_pareja" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_pareja']; ?>"/>
<?php
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_medioambiente'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bien_ambitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb_agu'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb_agu;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb_bom'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb_bom;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb_car'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb_car;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb_info'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb_info;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bien_amb_info']=='S'){
	//$sMuestra='';
	}
?>
</label>
<label class="L" id="lbl_bien_amb_temas"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_amb_temas'];
?>

<input id="cara01bien_amb_temas" name="cara01bien_amb_temas" type="text" value="<?php echo $_REQUEST['cara01bien_amb_temas']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bien_amb_temas']; ?>"/>
</label>

<div class="salto1px"></div>
</div>
<?php
}
?>
<?php
$bEntra=false;
if ($_REQUEST['cara01bienversion']==2){$bEntra=true;}
if ($bEntra){
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara01biendeporte'];
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bienv2altoren'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2altoren;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2depitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2atletismo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2atletismo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2baloncesto'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2baloncesto;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2futbol'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2futbol;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2gimnasia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2gimnasia;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2natacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2natacion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2voleibol'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2voleibol;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2tenis'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2tenis;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2paralimpico'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2paralimpico;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2otrodeporte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2otrodeporte;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bienv2otrodeporte']=='S'){
	$sMuestra='';
	}
?>
</label>
<label id="label_cara01bienv2otrodeporte" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bienv2otrodeportedetalle'];
?>
<input id="cara01bienv2otrodeportedetalle" name="cara01bienv2otrodeportedetalle" type="text" value="<?php echo $_REQUEST['cara01bienv2otrodeportedetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bienv2otrodeportedetalle']; ?>"/>
</label>
<div class="salto1px"></div>
</div><!--Cierre grupo campos bienestar deportes-->
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara01biencultura'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2culturaactivitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bienv2activartes'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2activartes;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bienv2activliteratura'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2activliteratura;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2activdanza'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2activdanza;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2activmusica'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2activmusica;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2activteatro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2activteatro;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2activculturalotra'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2activculturalotra;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bienv2activculturalotra']=='S'){
	$sMuestra='';
	}
?>
</label>
<label id="label_cara01bienv2activculturalotra" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bienv2activculturalotradetalle'];
?>
<input id="cara01bienv2activculturalotradetalle" name="cara01bienv2activculturalotradetalle" type="text" value="<?php echo $_REQUEST['cara01bienv2activculturalotradetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bienv2activculturalotradetalle']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2culturaeventitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2evenliteratura'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2evenliteratura;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2eventeatro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2eventeatro;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2evencine'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2evencine;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2evenhistarte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2evenhistarte;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2evenfestfolc'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2evenfestfolc;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2evenexpoarte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2evenexpoarte;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2evengalfoto'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2evengalfoto;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2evenculturalotro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2evenculturalotro;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bienv2evenculturalotro']=='S'){
	$sMuestra='';
	}
?>
</label>
<label id="label_cara01bienv2evenculturalotro" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bienv2evenculturalotrodetalle'];
?>
<input id="cara01bienv2evenculturalotrodetalle" name="cara01bienv2evenculturalotrodetalle" type="text" value="<?php echo $_REQUEST['cara01bienv2evenculturalotrodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bienv2evenculturalotrodetalle']; ?>"/>
</label>
<div class="salto1px"></div>
</div><!--Cierre grupo campos arte y cultura-->
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['msg_emprendimiento'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2emprensituaitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2emprendimiento'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprendimiento;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2empresa'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2empresa;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2emprenestadoitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bienv2emprenrecursos'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenrecursos;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bienv2emprenconocim'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenconocim;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bienv2emprenplan'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenplan;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bienv2emprenejecutar'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenejecutar;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bienv2emprenfortconocim'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenfortconocim;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bienv2emprenidentproblema'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenidentproblema;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01bienv2emprenotro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenotro;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bienv2emprenotro']=='S'){
	$sMuestra='';
	}
?>
</label>
<label id="label_cara01bienv2emprenotro" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bienv2emprenotrodetalle'];
?>
<input id="cara01bienv2emprenotrodetalle" name="cara01bienv2emprenotrodetalle" type="text" value="<?php echo $_REQUEST['cara01bienv2emprenotrodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bienv2emprenotrodetalle']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2emprentemasitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2emprenmarketing'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenmarketing;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2emprenplannegocios'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenplannegocios;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2emprenideas'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprenideas;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2emprencreacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2emprencreacion;
?>
</label>
<div class="salto1px"></div>
</div><!--Cierre grupo campos bienestar emprendimiento-->
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['msg_estilodevida'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2saludestresitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2saludfacteconom'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2saludfacteconom;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2saludpreocupacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2saludpreocupacion;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2saludconsumosust'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2saludconsumosust;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2saludinsomnio'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2saludinsomnio;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bienv2saludclimalab'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2saludclimalab;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2saludautocuiditem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2saludalimenta'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2saludalimenta;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2saludemocion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2saludemocion;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2saludmedita'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2saludmedita;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2saludestado'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2saludestado;
?>
</label>
<div class="salto1px"></div>
</div><!--Cierre grupo campos bienestar salud-->
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara01biencrecim'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2crecimtemaitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01bienv2crecimedusexual'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimedusexual;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara01bienv2creciminclusion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2creciminclusion;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01bienv2crecimcultciudad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimcultciudad;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara01bienv2crecimrelinterp'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimrelinterp;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01bienv2crecimrelpareja'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimrelpareja;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara01bienv2creciminteliemoc'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2creciminteliemoc;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01bienv2crecimautoestima'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimautoestima;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara01bienv2crecimdinamicafam'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimdinamicafam;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2crecimgrupoitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01bienv2crecimcultural'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimcultural;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara01bienv2crecimartistico'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimartistico;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara01bienv2crecimdeporte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimdeporte;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01bienv2crecimambiente'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimambiente;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara01bienv2crecimhabsocio'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2crecimhabsocio;
?>
</label>
<div class="salto1px"></div>
</div><!--Cierre grupo campos bienestar crecimiento personal-->
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['msg_medioambiente'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2ambienaccionitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienbasura'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienbasura;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienreutiliza'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienreutiliza;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienluces'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienluces;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienenchufa'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienenchufa;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienducha'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienducha;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambiengrifo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambiengrifo;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienbicicleta'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienbicicleta;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambientranspub'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambientranspub;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienfrutaverd'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienfrutaverd;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2ambienactivitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambiencaminata'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambiencaminata;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambiensiembra'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambiensiembra;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienrecicla'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienrecicla;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienconferencia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienconferencia;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01bienv2ambienotraactiv'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienotraactiv;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bienv2ambienotraactiv']=='S'){
	$sMuestra='';
	}
?>
</label>
<label id="label_cara01bienv2ambienotraactiv" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bienv2ambienotraactivdetalle'];
?>
<input id="cara01bienv2ambienotraactivdetalle" name="cara01bienv2ambienotraactivdetalle" type="text" value="<?php echo $_REQUEST['cara01bienv2ambienotraactivdetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bienv2ambienotraactivdetalle']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bienv2ambienenfoqueitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2ambienreforest'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienreforest;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara01bienv2ambienclimatico'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienclimatico;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienmovilidad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienmovilidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2ambienecofemin'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienecofemin;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara01bienv2ambieneconomia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambieneconomia;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienmascota'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienmascota;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2ambienbiodiver'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienbiodiver;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara01bienv2ambienecologia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienecologia;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambiencarga'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambiencarga;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2ambienreciclaje'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienreciclaje;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara01bienv2ambienrecnatura'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienrecnatura;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara01bienv2ambienespiritu'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienespiritu;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bienv2ambiencartohum'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambiencartohum;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara01bienv2ambienotroenfoq'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bienv2ambienotroenfoq;
$sMuestra=' style="display:none"';
if ($_REQUEST['cara01bienv2ambienotroenfoq']=='S'){
	$sMuestra='';
	}
?>
</label>
<label id="label_cara01bienv2ambienotroenfoq" class="L"<?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bienv2ambienotroenfoqdetalle'];
?>
<input id="cara01bienv2ambienotroenfoqdetalle" name="cara01bienv2ambienotroenfoqdetalle" type="text" value="<?php echo $_REQUEST['cara01bienv2ambienotroenfoqdetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara01bienv2ambienotroenfoqdetalle']; ?>"/>
</label>
<div class="salto1px"></div>
</div><!--Cierre grupo campos bienestar medio ambiente-->
<?php
}
?>
<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(5);
			}else{
			echo html_2201Tablero(5);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>

<?php
	}
if ($bGrupo6){
	$sEstilo='';
	if ($_REQUEST['cara01fichapsico']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==6){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha6"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande106" name="btexpande106" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(106,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta106']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge106" name="btrecoge106" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(106,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta106']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichapsico'];
?>
</label>
<label class="Label130"><div id="div_cara01fichapsico">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichapsico']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichapsico', $_REQUEST['cara01fichapsico'], $sMuestra);
?>
</div></label>
<?php
$bMuestra=false;
if ($_REQUEST['cara01completa']=='S'){$bMuestra=true;}
if ($bMuestra){
?>
<label class="Label200">
<?php
echo $ETI['cara01psico_puntaje'];
?>
</label>
<label class="Label130"><div id="div_cara01psico_puntaje">
<?php
//echo html_oculto('cara01psico_puntaje', $_REQUEST['cara01psico_puntaje']);
echo html_oculto('cara01psico_puntaje', $_REQUEST['cara01psico_puntaje'], f2301_NombrePuntaje('puntaje',$_REQUEST['cara01psico_puntaje']));
?>
</div></label>
<?php
	}else{
?>
<input id="cara01psico_puntaje" name="cara01psico_puntaje" type="hidden" value="<?php echo $_REQUEST['cara01psico_puntaje']; ?>"/>
<?php
	}
?>
<div class="salto1px"></div>
<div id="div_p106" style="display:<?php if ($_REQUEST['boculta106']==0){echo 'block'; }else{echo 'none';} ?>;">

<?php
if ($_REQUEST['cara01completa']=='S'){
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
if ($_REQUEST['cara01psico_puntaje']<17){
	echo $ETI['msg_psico_alto'];
	}else{
	if ($_REQUEST['cara01psico_puntaje']<24){
		echo $ETI['msg_psico_medio'];
		}else{
		echo $ETI['msg_psico_bajo'];
		}
	}
?>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_costoemocion'];
?>
</label>
<label>
<?php
echo $html_cara01psico_costoemocion;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_reaccionimpre'];
?>
</label>
<label>
<?php
echo $html_cara01psico_reaccionimpre;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_estres'];
?>
</label>
<label>
<?php
echo $html_cara01psico_estres;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_pocotiempo'];
?>
</label>
<label>
<?php
echo $html_cara01psico_pocotiempo;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_actitudvida'];
?>
</label>
<label>
<?php
echo $html_cara01psico_actitudvida;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_duda'];
?>
</label>
<label>
<?php
echo $html_cara01psico_duda;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_problemapers'];
?>
</label>
<label>
<?php
echo $html_cara01psico_problemapers;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_satisfaccion'];
?>
</label>
<label>
<?php
echo $html_cara01psico_satisfaccion;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_discusiones'];
?>
</label>
<label>
<?php
echo $html_cara01psico_discusiones;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_atencion'];
?>
</label>
<label>
<?php
echo $html_cara01psico_atencion;
?>
</label>
<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(6);
			}else{
			echo html_2201Tablero(6);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
//Competencias digitales...
if ($bGrupo7){
	$sEstilo='';
	if ($_REQUEST['cara01fichadigital']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==7){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha7"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande107" name="btexpande107" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(107,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta107']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge107" name="btrecoge107" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(107,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta107']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichadigital'];
?>
</label>
<label class="Label130"><div id="div_cara01fichadigital">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichadigital']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichadigital', $_REQUEST['cara01fichadigital'], $sMuestra);
?>
</div></label>
<?php
if ($bEstudiante){
?>
<input id="cara01niveldigital" name="cara01niveldigital" type="hidden" value="<?php echo $_REQUEST['cara01niveldigital']; ?>"/>
<?php
	}else{
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01niveldigital', f2301_NombrePuntaje('digital',$_REQUEST['cara01niveldigital']));
echo html_oculto('cara01niveldigital', $_REQUEST['cara01niveldigital'], f2301_NombrePuntaje('digital',$_REQUEST['cara01niveldigital']));
?>
</label>
<?php
if ($_REQUEST['cara01completa']!='S'){
?>
<label class="Label30">
<input id="brevisadv" name="brevisadv" type="button" value="Actualizar" class="btMiniActualizar" onclick="actualizapreguntas()" title="Actualizar preguntas"/>
</label>
<?php
		}
	}
?>
<div class="salto1px"></div>
<div id="div_p107" style="display:<?php if ($_REQUEST['boculta107']==0){echo 'block'; }else{echo 'none';} ?>;">
<div id="div_f2310detalle_7">
<?php
echo $sTabla2310_7;
?>
</div>

<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(7);
			}else{
			echo html_2201Tablero(7);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo8){
	$sEstilo='';
	if ($_REQUEST['cara01fichalectura']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==8){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha8"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande108" name="btexpande108" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(108,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta108']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge108" name="btrecoge108" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(108,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta108']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichalectura'];
?>
</label>
<label class="Label130"><div id="div_cara01fichalectura">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichalectura']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichalectura', $_REQUEST['cara01fichalectura'], $sMuestra);
?>
</div></label>
<?php
if ($bEstudiante){
?>
<input id="cara01nivellectura" name="cara01nivellectura" type="hidden" value="<?php echo $_REQUEST['cara01nivellectura']; ?>"/>
<?php
	}else{
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('cara01nivellectura', $_REQUEST['cara01nivellectura'], f2301_NombrePuntaje('lectura',$_REQUEST['cara01nivellectura']));
//echo html_oculto('cara01nivellectura', $_REQUEST['cara01nivellectura']);
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<div id="div_p108" style="display:<?php if ($_REQUEST['boculta108']==0){echo 'block'; }else{echo 'none';} ?>;">
<div id="div_f2310detalle_8">
<?php
echo $sTabla2310_8;
?>
</div>

<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(8);
			}else{
			echo html_2201Tablero(8);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo9){
	$sEstilo='';
	if ($_REQUEST['cara01ficharazona']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==9){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha9"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande109" name="btexpande109" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(109,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta109']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge109" name="btrecoge109" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(109,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta109']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01ficharazona'];
?>
</label>
<label class="Label130"><div id="div_cara01ficharazona">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01ficharazona']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01ficharazona', $_REQUEST['cara01ficharazona'], $sMuestra);
?>
</div></label>
<?php
if ($bEstudiante){
?>
<input id="cara01nivelrazona" name="cara01nivelrazona" type="hidden" value="<?php echo $_REQUEST['cara01nivelrazona']; ?>"/>
<?php
	}else{
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelrazona', $_REQUEST['cara01nivelrazona']);
echo html_oculto('cara01nivelrazona', $_REQUEST['cara01nivelrazona'], f2301_NombrePuntaje('razona',$_REQUEST['cara01nivelrazona']));
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<div id="div_p109" style="display:<?php if ($_REQUEST['boculta109']==0){echo 'block'; }else{echo 'none';} ?>;">
<div id="div_f2310detalle_9">
<?php
echo $sTabla2310_9;
?>
</div>

<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(9);
			}else{
			echo html_2201Tablero(9);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo10){
	$sEstilo='';
	if ($_REQUEST['cara01fichaingles']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==10){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha10"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande110" name="btexpande110" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(110,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta110']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge110" name="btrecoge110" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(110,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta110']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichaingles'];
?>
</label>
<label class="Label130"><div id="div_cara01fichaingles">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichaingles']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichaingles', $_REQUEST['cara01fichaingles'], $sMuestra);
?>
</div></label>
<?php
if ($bEstudiante){
?>
<input id="cara01nivelingles" name="cara01nivelingles" type="hidden" value="<?php echo $_REQUEST['cara01nivelingles']; ?>"/>
<?php
	}else{
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelingles', $_REQUEST['cara01nivelingles']);
echo html_oculto('cara01nivelingles', $_REQUEST['cara01nivelingles'], f2301_NombrePuntaje('ingles',$_REQUEST['cara01nivelingles']));
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<div id="div_p110" style="display:<?php if ($_REQUEST['boculta110']==0){echo 'block'; }else{echo 'none';} ?>;">
<div id="div_f2310detalle_10">
<?php
echo $sTabla2310_10;
?>
</div>

<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(10);
			}else{
			echo html_2201Tablero(10);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo11){
	$sEstilo='';
	if ($_REQUEST['cara01fichabiolog']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==11){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha11"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande111" name="btexpande111" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(111,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta111']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge111" name="btrecoge111" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(111,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta111']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichabiolog'];
?>
</label>
<label class="Label130"><div id="div_cara01fichabiolog">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichabiolog']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichabiolog', $_REQUEST['cara01fichabiolog'], $sMuestra);
?>
</div></label>
<?php
if ($bEstudiante){
?>
<input id="cara01nivelbiolog" name="cara01nivelbiolog" type="hidden" value="<?php echo $_REQUEST['cara01nivelbiolog']; ?>"/>
<?php
	}else{
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelbiolog', $_REQUEST['cara01nivelbiolog']);
echo html_oculto('cara01nivelbiolog', $_REQUEST['cara01nivelbiolog'], f2301_NombrePuntaje('biolog',$_REQUEST['cara01nivelbiolog']));
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<div id="div_p111" style="display:<?php if ($_REQUEST['boculta111']==0){echo 'block'; }else{echo 'none';} ?>;">
<div id="div_f2310detalle_11">
<?php
echo $sTabla2310_11;
?>
</div>

<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(11);
			}else{
			echo html_2201Tablero(11);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo12){
	$sEstilo='';
	if ($_REQUEST['cara01fichafisica']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==12){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha12"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande112" name="btexpande112" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(112,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta112']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge112" name="btrecoge112" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(112,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta112']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichafisica'];
?>
</label>
<label class="Label130"><div id="div_cara01fichafisica">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichafisica']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichafisica', $_REQUEST['cara01fichafisica'], $sMuestra);
?>
</div></label>
<?php
if ($bEstudiante){
?>
<input id="cara01nivelfisica" name="cara01nivelfisica" type="hidden" value="<?php echo $_REQUEST['cara01nivelfisica']; ?>"/>
<?php
	}else{
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelfisica', $_REQUEST['cara01nivelfisica']);
echo html_oculto('cara01nivelfisica', $_REQUEST['cara01nivelfisica'], f2301_NombrePuntaje('fisica',$_REQUEST['cara01nivelfisica']));
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<div id="div_p112" style="display:<?php if ($_REQUEST['boculta112']==0){echo 'block'; }else{echo 'none';} ?>;">
<div id="div_f2310detalle_12">
<?php
echo $sTabla2310_12;
?>
</div>

<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(12);
			}else{
			echo html_2201Tablero(12);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if ($bGrupo13){
	$sEstilo='';
	if ($_REQUEST['cara01fichaquimica']==-1){$sEstilo=' style="display:none"';}
	if ($bEstudiante){
		$sEstilo=' style="display:none"';
		if ($_REQUEST['ficha']==13){$sEstilo='';}
		}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha13"<?php echo $sEstilo; ?>>
<?php
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande113" name="btexpande113" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(113,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta113']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge113" name="btrecoge113" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(113,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta113']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<?php
	}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichaquimica'];
?>
</label>
<label class="Label130"><div id="div_cara01fichaquimica">
<?php
$sMuestra='&nbsp;';
if ($_REQUEST['cara01fichaquimica']==1){$sMuestra='COMPLETA';}
echo html_oculto('cara01fichaquimica', $_REQUEST['cara01fichaquimica'], $sMuestra);
?>
</div></label>
<?php
if ($bEstudiante){
?>
<input id="cara01nivelquimica" name="cara01nivelquimica" type="hidden" value="<?php echo $_REQUEST['cara01nivelquimica']; ?>"/>
<?php
	}else{
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelquimica', $_REQUEST['cara01nivelquimica']);
echo html_oculto('cara01nivelquimica', $_REQUEST['cara01nivelquimica'], f2301_NombrePuntaje('quimica',$_REQUEST['cara01nivelquimica']));
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<div id="div_p113" style="display:<?php if ($_REQUEST['boculta113']==0){echo 'block'; }else{echo 'none';} ?>;">
<div id="div_f2310detalle_13">
<?php
echo $sTabla2310_13;
?>
</div>
<?php
if ($bEstudiante){
	if ($_REQUEST['paso']==2){
		if ($_REQUEST['cara01completa']!='S'){
			echo html_2201ContinuarCerrar(13);
			}else{
			echo html_2201Tablero(13);
			}
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
if (!$bEstudiante){
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idconsejero'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idconsejero" name="cara01idconsejero" type="hidden" value="<?php echo $_REQUEST['cara01idconsejero']; ?>"/>
<div id="div_cara01idconsejero_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('cara01idconsejero', $_REQUEST['cara01idconsejero_td'], $_REQUEST['cara01idconsejero_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idconsejero" class="L"><?php echo $cara01idconsejero_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01idcursocatedra'];
?>
</label>
<label class="Label130"><div id="div_cara01idcursocatedra">
<?php
echo html_oculto('cara01idcursocatedra', $_REQUEST['cara01idcursocatedra']);
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['cara01idgrupocatedra'];
?>
</label>
<label class="Label30"><div id="div_cara01idgrupocatedra">
<?php
echo html_oculto('cara01idgrupocatedra', $_REQUEST['cara01idgrupocatedra']);
?>
</div></label>
<div class="salto1px"></div>
</div>


<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['cara01fechainicio'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('cara01fechainicio', $_REQUEST['cara01fechainicio']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bcara01fechainicio_hoy" name="bcara01fechainicio_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('cara01fechainicio','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01idescuela'];
?>
</label>
<label class="Label350"><div id="div_cara01idescuela">
<?php
echo $html_cara01idescuela;
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01idprograma'];
?>
</label>
<label class="Label350"><div id="div_cara01idprograma">
<?php
echo $html_cara01idprograma;
?>
</div></label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara01tipocaracterizacion'];
?>
</label>
<label class="Label300">
<?php
echo $html_cara01tipocaracterizacion;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}else{
?>
<input id="cara01idconsejero" name="cara01idconsejero" type="hidden" value="<?php echo $_REQUEST['cara01idconsejero']; ?>"/>
<input id="cara01idconsejero_td" name="cara01idconsejero_td" type="hidden" value="<?php echo $_REQUEST['cara01idconsejero_td']; ?>"/>
<input id="cara01idconsejero_doc" name="cara01idconsejero_doc" type="hidden" value="<?php echo $_REQUEST['cara01idconsejero_doc']; ?>"/>
<input id="cara01fechainicio" name="cara01fechainicio" type="hidden" value="<?php echo $_REQUEST['cara01fechainicio']; ?>"/>
<input id="cara01idprograma" name="cara01idprograma" type="hidden" value="<?php echo $_REQUEST['cara01idprograma']; ?>"/>
<input id="cara01idescuela" name="cara01idescuela" type="hidden" value="<?php echo $_REQUEST['cara01idescuela']; ?>"/>
<input id="cara01tipocaracterizacion" name="cara01tipocaracterizacion" type="hidden" value="<?php echo $_REQUEST['cara01tipocaracterizacion']; ?>"/>
<input id="cara01idcursocatedra" name="cara01idcursocatedra" type="hidden" value="<?php echo $_REQUEST['cara01idcursocatedra']; ?>"/>
<input id="cara01idgrupocatedra" name="cara01idgrupocatedra" type="hidden" value="<?php echo $_REQUEST['cara01idgrupocatedra']; ?>"/>
<?php
	}
$sEstilo=' style="display:none"';
if (!$bEstudiante){$sEstilo='';}
?>
<div class="salto1px"></div>

<div class="GrupoCampos"<?php echo $sEstilo; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['msg_factores'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01factordescper'];
?>
</label>
<label class="Label30"><div id="div_cara01factordescper">
<?php
echo html_oculto('cara01factordescper', $_REQUEST['cara01factordescper']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['cara01factordescpsico'];
?>
</label>
<label class="Label30"><div id="div_cara01factordescpsico">
<?php
echo html_oculto('cara01factordescpsico', $_REQUEST['cara01factordescpsico']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['cara01factordescinsti'];
?>
</label>
<label class="Label30"><div id="div_cara01factordescinsti">
<?php
echo html_oculto('cara01factordescinsti', $_REQUEST['cara01factordescinsti']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['cara01factordescacad'];
?>
</label>
<label class="Label30"><div id="div_cara01factordescacad">
<?php
echo html_oculto('cara01factordescacad', $_REQUEST['cara01factordescacad']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['cara01factordesc'];
?>
</label>
<label class="Label30"><div id="div_cara01factordesc">
<?php
echo html_oculto('cara01factordesc', $_REQUEST['cara01factordesc']);
?>
</div></label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara01criteriodesc'];
?>
</label>
<label class="Label220">
<?php
echo $html_cara01criteriodesc;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01desertor'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01desertor;
?>
</label>
<?php
if ($_REQUEST['cara01desertor']=='S'){
?>
<label class="Label130">
<?php
echo $ETI['cara01factorprincipaldesc'];
?>
</label>
<label>
<?php
echo $html_cara01factorprincipaldesc;
?>
</label>
<?php
	}else{
?>
<input id="cara01factorprincipaldesc" name="cara01factorprincipaldesc" type="hidden" value="<?php echo $_REQUEST['cara01factorprincipaldesc']; ?>"/>
<?php
	}
?>
<div class="salto1px">
</div>

<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2301
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
if (!$bEstudiante){
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
Documento
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf2301()" autocomplete="off"/>
</label>
<label class="Label90">
Nombre
</label>
<label class="Label250">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2301()" autocomplete="off"/>
</label>
<label class="Label60">
Listar
</label>
<label class="Label200">
<?php
echo $html_blistar;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Periodo
</label>
<label class="Label130">
<?php
echo $html_bperaca;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Escuela
</label>
<label class="Label600">
<?php
echo $html_bescuela;
?>
</label>
<label class="Label60">
Tipo
</label>
<label>
<?php
echo $html_btipocara;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Programa
</label>
<label class="Label130">
<div id="div_bprograma">
<?php
echo $html_bprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
Zona
</label>
<label class="Label350">
<?php
echo $html_bzona;
?>
</label>
<label class="Label90">
Poblaci&oacute;n
</label>
<label class="Label130">
<?php
echo $html_bpoblacion;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
CEAD
</label>
<label class="Label130">
<div id="div_bcead">
<?php
echo $html_bcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
Convenio
</label>
<label class="Label130">
<?php
echo $html_bconvenio;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f2301detalle">
<?php
echo $sTabla2301;
?>
</div>
<?php
	//Termina el modo estudiante...
	}
?>
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
<input id="titulo_2301" name="titulo_2301" type="hidden" value="<?php echo $ETI['titulo_2301']; ?>" />
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
echo '<h2>'.$ETI['titulo_2301'].'</h2>';
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
echo '<h2>'.$ETI['titulo_2301'].'</h2>';
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
if ($bEstudiante){
	if ($_REQUEST['cara01completa']!='S'){
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
		}
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
<?php
if (!$bEstudiante){
?>
<script language="javascript">
<!--
$().ready(function(){
<?php
if ($_REQUEST['paso']==0){
?>
$("#cara01idperaca").chosen();
<?php
	}
?>
$("#bperaca").chosen();
});
-->
</script>
<?php
	}
?>
<script language="javascript" src="ac_2301.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>