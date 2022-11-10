<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.10c jueves, 4 de febrero de 2021
*/
/*
/** Archivo para reportes tipo csv 3082.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 4 de febrero de 2021
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libs/clsplanos.php';
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
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($iReporte==3082){$bEntra=true;}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	$sDebug='';
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
	$mensajes_3082='lg/lg_3082_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3082)){$mensajes_3082='lg/lg_3082_es.php';}
	require $mensajes_todas;
	require $mensajes_3082;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t3082.csv';
	$sTituloRpt='saiu23inventario';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('saiu23inventario');
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	//$asaiu23agno=array();
	$asaiu23mes=array();
	$asaiu23estado=array();
	$asaiu23idmedio=array();
	$asaiu23idtema=array();
	$asaiu23idtiposol=array();
	$asaiu23idzona=array();
	$asaiu23cead=array();
	$asaiu23idescuela=array();
	$asaiu23idprograma=array();
	$asaiu23idperiodo=array();
	$asaiu23idcurso=array();
	$aSys11=array();
	$sTitulos1='Titulo 1';
	for ($l=1;$l<=18;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	$sBloque1=''.'Agno'.$cSepara.'Mes'.$cSepara.'Estado'.$cSepara.'Medio'.$cSepara.'Tema'.$cSepara
.'Tiposol'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Solicitante'.$cSepara.'Zona'.$cSepara.'Cead'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Responsable'.$cSepara
.'Escuela'.$cSepara.'Programa'.$cSepara.'Periodo'.$cSepara.'Curso';
	//$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sSQL='SELECT * 
FROM saiu23inventario 
WHERE 
';
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_saiu23agno=$cSepara;
		$lin_saiu23mes=$cSepara;
		$lin_saiu23estado=$cSepara;
		$lin_saiu23idmedio=$cSepara;
		$lin_saiu23idtema=$cSepara;
		$lin_saiu23idtiposol=$cSepara;
		$lin_saiu23idsolicitante=$cSepara.$cSepara.$cSepara;
		$lin_saiu23idzona=$cSepara;
		$lin_saiu23cead=$cSepara;
		$lin_saiu23idresponsable=$cSepara.$cSepara.$cSepara;
		$lin_saiu23idescuela=$cSepara;
		$lin_saiu23idprograma=$cSepara;
		$lin_saiu23idperiodo=$cSepara;
		$lin_saiu23idcurso=$cSepara;
		$lin_saiu23agno='['.$fila['saiu23agno'].']';
		if (isset($asaiu23agno[$fila['saiu23agno']])!=0){
			$lin_saiu23agno=utf8_decode($asaiu23agno[$fila['saiu23agno']]);
			}
		$i_saiu23mes=$fila['saiu23mes'];
		if (isset($asaiu23mes[$i_saiu23mes])==0){
			$sSQL='SELECT unad17nombre FROM unad17mes WHERE unad17id='.$i_saiu23mes.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23mes[$i_saiu23mes]=str_replace($cSepara, $cComplementa, $filae['unad17nombre']);
				}else{
				$asaiu23mes[$i_saiu23mes]='';
				}
			}
		$lin_saiu23mes=$cSepara.utf8_decode($asaiu23mes[$i_saiu23mes]);
		$i_saiu23estado=$fila['saiu23estado'];
		if (isset($asaiu23estado[$i_saiu23estado])==0){
			$sSQL='SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id='.$i_saiu23estado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23estado[$i_saiu23estado]=str_replace($cSepara, $cComplementa, $filae['saiu11nombre']);
				}else{
				$asaiu23estado[$i_saiu23estado]='';
				}
			}
		$lin_saiu23estado=$cSepara.utf8_decode($asaiu23estado[$i_saiu23estado]);
		$i_saiu23idmedio=$fila['saiu23idmedio'];
		if (isset($asaiu23idmedio[$i_saiu23idmedio])==0){
			$sSQL='SELECT bita01nombre FROM bita01tiposolicitud WHERE bita01id='.$i_saiu23idmedio.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23idmedio[$i_saiu23idmedio]=str_replace($cSepara, $cComplementa, $filae['bita01nombre']);
				}else{
				$asaiu23idmedio[$i_saiu23idmedio]='';
				}
			}
		$lin_saiu23idmedio=$cSepara.utf8_decode($asaiu23idmedio[$i_saiu23idmedio]);
		$i_saiu23idtema=$fila['saiu23idtema'];
		if (isset($asaiu23idtema[$i_saiu23idtema])==0){
			$sSQL='SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id='.$i_saiu23idtema.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23idtema[$i_saiu23idtema]=str_replace($cSepara, $cComplementa, $filae['saiu03titulo']);
				}else{
				$asaiu23idtema[$i_saiu23idtema]='';
				}
			}
		$lin_saiu23idtema=$cSepara.utf8_decode($asaiu23idtema[$i_saiu23idtema]);
		$i_saiu23idtiposol=$fila['saiu23idtiposol'];
		if (isset($asaiu23idtiposol[$i_saiu23idtiposol])==0){
			$sSQL='SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id='.$i_saiu23idtiposol.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23idtiposol[$i_saiu23idtiposol]=str_replace($cSepara, $cComplementa, $filae['saiu02titulo']);
				}else{
				$asaiu23idtiposol[$i_saiu23idtiposol]='';
				}
			}
		$lin_saiu23idtiposol=$cSepara.utf8_decode($asaiu23idtiposol[$i_saiu23idtiposol]);
		$iTer=$fila['saiu23idsolicitante'];
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
				$aSys11[$iTer]['doc']='['.$iTer.']';
				$aSys11[$iTer]['razon']='';
				}
			}
		$lin_saiu23idsolicitante=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$i_saiu23idzona=$fila['saiu23idzona'];
		if (isset($asaiu23idzona[$i_saiu23idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_saiu23idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23idzona[$i_saiu23idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$asaiu23idzona[$i_saiu23idzona]='';
				}
			}
		$lin_saiu23idzona=$cSepara.utf8_decode($asaiu23idzona[$i_saiu23idzona]);
		$i_saiu23cead=$fila['saiu23cead'];
		if (isset($asaiu23cead[$i_saiu23cead])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_saiu23cead.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23cead[$i_saiu23cead]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$asaiu23cead[$i_saiu23cead]='';
				}
			}
		$lin_saiu23cead=$cSepara.utf8_decode($asaiu23cead[$i_saiu23cead]);
		$iTer=$fila['saiu23idresponsable'];
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
				$aSys11[$iTer]['doc']='['.$iTer.']';
				$aSys11[$iTer]['razon']='';
				}
			}
		$lin_saiu23idresponsable=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$i_saiu23idescuela=$fila['saiu23idescuela'];
		if (isset($asaiu23idescuela[$i_saiu23idescuela])==0){
			$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$i_saiu23idescuela.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23idescuela[$i_saiu23idescuela]=str_replace($cSepara, $cComplementa, $filae['core12nombre']);
				}else{
				$asaiu23idescuela[$i_saiu23idescuela]='';
				}
			}
		$lin_saiu23idescuela=$cSepara.utf8_decode($asaiu23idescuela[$i_saiu23idescuela]);
		$i_saiu23idprograma=$fila['saiu23idprograma'];
		if (isset($asaiu23idprograma[$i_saiu23idprograma])==0){
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$i_saiu23idprograma.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23idprograma[$i_saiu23idprograma]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
				}else{
				$asaiu23idprograma[$i_saiu23idprograma]='';
				}
			}
		$lin_saiu23idprograma=$cSepara.utf8_decode($asaiu23idprograma[$i_saiu23idprograma]);
		$i_saiu23idperiodo=$fila['saiu23idperiodo'];
		if (isset($asaiu23idperiodo[$i_saiu23idperiodo])==0){
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$i_saiu23idperiodo.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23idperiodo[$i_saiu23idperiodo]=str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
				}else{
				$asaiu23idperiodo[$i_saiu23idperiodo]='';
				}
			}
		$lin_saiu23idperiodo=$cSepara.utf8_decode($asaiu23idperiodo[$i_saiu23idperiodo]);
		$i_saiu23idcurso=$fila['saiu23idcurso'];
		if (isset($asaiu23idcurso[$i_saiu23idcurso])==0){
			$sSQL='SELECT unad40nombre FROM unad40curso WHERE unad40id='.$i_saiu23idcurso.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu23idcurso[$i_saiu23idcurso]=str_replace($cSepara, $cComplementa, $filae['unad40nombre']);
				}else{
				$asaiu23idcurso[$i_saiu23idcurso]='';
				}
			}
		$lin_saiu23idcurso=$cSepara.utf8_decode($asaiu23idcurso[$i_saiu23idcurso]);
		$sBloque1=''.$lin_saiu23agno.$lin_saiu23mes.$lin_saiu23estado.$lin_saiu23idmedio.$lin_saiu23idtema
.$lin_saiu23idtiposol.$lin_saiu23idsolicitante.$lin_saiu23idzona.$lin_saiu23cead.$lin_saiu23idresponsable
.$lin_saiu23idescuela.$lin_saiu23idprograma.$lin_saiu23idperiodo.$lin_saiu23idcurso;
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