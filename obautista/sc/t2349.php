<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.22.6d miércoles, 23 de enero de 2019
*/
/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
require '../config.php';
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libs/clsplanos.php';
require $APP->rutacomun.'lg/lg_2301_es.php';
if ($_SESSION['unad_id_tercero']==0){
	die();
	}
$_SESSION['u_ultimominuto']=iminutoavance();
$sError='';
$iReporte=0;
$bEntra=false;
$bDebug=false;
if (isset($_REQUEST['r'])!=0){$iReporte=numeros_validar($_REQUEST['r']);}
if (isset($_REQUEST['clave'])==0){$_REQUEST['clave']='';}
if (isset($_REQUEST['v3'])==0){$_REQUEST['v3']='';}
if (isset($_REQUEST['v4'])==0){$_REQUEST['v4']='';}
if (isset($_REQUEST['v5'])==0){$_REQUEST['v5']='';}
if (isset($_REQUEST['v6'])==0){$_REQUEST['v6']=1;}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($iReporte==2349){$bEntra=true;}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	$idTercero=$_SESSION['unad_id_tercero'];
	$iCodModulo=2349;
	if ($_REQUEST['rdebug']==1){$bDebug=true;}
	$cSepara=',';
	$cEvita=';';
	$cComplementa='.';
	if (isset($_REQUEST['separa'])!=0){
		if ($_REQUEST['separa']==';'){
			$cSepara=';';
			$cEvita=',';
			}
		}
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2349='lg/lg_2349_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2349)){$mensajes_2349='lg/lg_2349_es.php';}
	require $mensajes_todas;
	require $mensajes_2349;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t2349.csv';
	$sTituloRpt='Necesidades_especiales';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sNomPeraca='{'.$_REQUEST['v3'].'}';
	$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$_REQUEST['v3'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sNomPeraca=$fila['exte02nombre'];
		}
	$bConConsejero=true;
	$sDato=utf8_decode('Necesidades especiales '.$sNomPeraca);
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$acara01idzona=array();
	$acara01idcead=array();
	$acara01perayuda=array();
	$acara01perayuda[0]='Ninguno';
	$sTitulo1='Datos generales';
	for ($l=1;$l<=5;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	$sTitulo1=$sTitulo1.'Discapacidades';
	$sBloque1='Zona'.$cSepara.'Centro'.$cSepara.'Tipo documento'.$cSepara.'Documento'.$cSepara.'Razon social'.$cSepara.'Sensorial'.$cSepara.$cSepara.'Fisica'.$cSepara.$cSepara.'Cognitiva'.$cSepara.$cSepara.'Necesidades especiales'.$cSepara.'Otras necesidades'.$cSepara.'Confirmada';
	//$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sWhere='';
	$sSQL='SELECT TB.cara01idzona, TB.cara01idcead, TB.cara01idtercero, TB.cara01discsensorial, TB.cara01discsensorialotra, TB.cara01discfisica, TB.cara01discfisicaotra, TB.cara01disccognitiva, TB.cara01disccognitivaotra, TB.cara01perayuda, TB.cara01perotraayuda, TB.cara01fechaconfirmadisc 
FROM cara01encuesta AS TB 
WHERE TB.cara01idperaca='.$_REQUEST['v3'].''.$sWhere.' AND TB.cara01completa="S" AND ((TB.cara01discsensorial<>"N") OR (TB.cara01discfisica<>"N") OR (TB.cara01disccognitiva<>"N") OR (TB.cara01perayuda<>0))';
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_cara01idzona='';
		$lin_cara01idtercero=$cSepara.$cSepara.$cSepara;
		$lin_cara01idcead=$cSepara;
		$lin_cara01discsensorial=$cSepara.$cSepara;
		$lin_cara01discfisica=$cSepara.$cSepara;
		$lin_cara01disccognitiva=$cSepara.$cSepara;
		$lin_cara01perayuda=$cSepara;
		$lin_cara01perotraayuda=$cSepara;
		$lin_cara01fechaconfirmadisc=$cSepara.'No';

		$i_cara49idzona=$fila['cara01idzona'];
		if (isset($acara49idzona[$i_cara49idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_cara49idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acara49idzona[$i_cara49idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$acara49idzona[$i_cara49idzona]='';
				}
			}
		$lin_cara01idzona=utf8_decode($acara49idzona[$i_cara49idzona]);
		$i_cara49idcentro=$fila['cara01idcead'];
		if (isset($acara49idcentro[$i_cara49idcentro])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_cara49idcentro.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acara49idcentro[$i_cara49idcentro]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$acara49idcentro[$i_cara49idcentro]='';
				}
			}
		$lin_cara01idcead=$cSepara.utf8_decode($acara49idcentro[$i_cara49idcentro]);
		if (true){
			$iTer=$fila['cara01idtercero'];
			if (isset($aSys11[$iTer]['doc'])==0){
				$sSQL='SELECT unad11tipodoc, unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id='.$iTer.'';
				$tabla11=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla11)>0){
					$fila11=$objDB->sf($tabla11);
					$aSys11[$iTer]['td']=$fila11['unad11tipodoc'];
					$aSys11[$iTer]['doc']=$fila11['unad11doc'];
					$aSys11[$iTer]['razon']=$fila11['unad11razonsocial'];
					}else{
					$aSys11[$iTer]['td']='';
					$aSys11[$iTer]['doc']='';
					$aSys11[$iTer]['razon']='';
					}
				}
			$lin_cara01idtercero=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
			}
		if ($fila['cara01discsensorial']!='N'){
			$lin_cara01discsensorial=$cSepara.$fila['cara01discsensorial'];
			if (isset($acara01discsensorial[$fila['cara01discsensorial']])!=0){$lin_cara01discsensorial=$cSepara.$acara01discsensorial[$fila['cara01discsensorial']];}
			$lin_cara01discsensorial=$lin_cara01discsensorial.$cSepara.utf8_decode($fila['cara01discsensorialotra']);
			}
		if ($fila['cara01discfisica']!='N'){
			$lin_cara01discfisica=$cSepara.$fila['cara01discfisica'];
			if (isset($acara01discfisica[$fila['cara01discfisica']])!=0){$lin_cara01discfisica=$cSepara.$acara01discfisica[$fila['cara01discfisica']];}
			$lin_cara01discfisica=$lin_cara01discfisica.$cSepara.utf8_decode($fila['cara01discfisicaotra']);
			}
		if ($fila['cara01disccognitiva']!='N'){
			$lin_cara01disccognitiva=$cSepara.$fila['cara01disccognitiva'];
			if (isset($acara01disccognitiva[$fila['cara01disccognitiva']])!=0){$lin_cara01disccognitiva=$cSepara.$acara01disccognitiva[$fila['cara01disccognitiva']];}
			$lin_cara01disccognitiva=$lin_cara01disccognitiva.$cSepara.utf8_decode($fila['cara01disccognitivaotra']);
			}
		$bEntra=true;
		if ($fila['cara01perayuda']==0){$bEntra=false;}
		if ($fila['cara01perayuda']==-1){
			$bEntra=false;
			$lin_cara01perayuda=$cSepara.'Otra';
			$lin_cara01perotraayuda=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01perotraayuda']));
			}
		if ($bEntra){
			$i_cara01perayuda=$fila['cara01perayuda'];
			if (isset($acara01perayuda[$i_cara01perayuda])==0){
				$sSQL='SELECT cara14nombre FROM cara14ayudaajuste WHERE cara14id='.$i_cara01perayuda.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01perayuda[$i_cara01perayuda]=str_replace($cSepara, $cComplementa, $filae['cara14nombre']);
					}else{
					$acara01perayuda[$i_cara01perayuda]='';
					}
				}
			$lin_cara01perayuda=$cSepara.utf8_decode($acara01perayuda[$i_cara01perayuda]);
			}
		if ($fila['cara01fechaconfirmadisc']!=0){
			$lin_cara01fechaconfirmadisc=$cSepara.'Si';
			}
		$sBloque1=''.$lin_cara01idzona.$lin_cara01idcead.$lin_cara01idtercero.$lin_cara01discsensorial.$lin_cara01discfisica.$lin_cara01disccognitiva.$lin_cara01perayuda.$lin_cara01perotraayuda.$lin_cara01fechaconfirmadisc;
		$objplano->AdicionarLinea($sBloque1);
		}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
	}
?>