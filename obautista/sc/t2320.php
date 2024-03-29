<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.22.3 lunes, 27 de agosto de 2018
--- Modelo Version 2.25.0 miércoles, 15 de abril de 2020
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
if (isset($_REQUEST['v6'])==0){$_REQUEST['v6']='';}
if (isset($_REQUEST['v7'])==0){$_REQUEST['v7']='';}
if (isset($_REQUEST['v8'])==0){$_REQUEST['v8']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($iReporte==2320){$bEntra=true;}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
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
	require $mensajes_todas;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t2320.csv';
	$sTituloRpt='AvanceDetallado';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	//$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	//$objplano->AdicionarLinea($sDato);
	//$sDato=utf8_decode('cara20reporteacceso');
	//$objplano->AdicionarLinea($sDato);
	//$sDato='';
	//$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$acara20tipo=array();
	$acara20peraca=array();
	$acara20idescuela=array();
	$acara20poblacion=array();
	/*
	$sTitulos1='Titulo 1';
	for ($l=1;$l<=4;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	*/
	$sAdd='';
	$sAdd2='';
	$sAddTitulo='';
	if ($_REQUEST['v5']!=''){
		$sAdd=' AND T2.cara01idescuela='.$_REQUEST['v5'].'';
		$sAdd2=' AND TB.core16idescuela='.$_REQUEST['v5'].'';
		}
	if ($_REQUEST['v6']==1){
		$sAdd=$sAdd.' AND T2.cara01idconsejero='.$_SESSION['unad_id_tercero'].' ';
		$sAdd2=$sAdd2.' AND TB.core16idconsejero='.$_SESSION['unad_id_tercero'].' ';
		}
	if ($_REQUEST['v8']!=''){
		$sAdd=$sAdd.' AND T2.cara01idcead='.$_REQUEST['v8'].'';
		$sAdd2=$sAdd2.' AND TB.core16idcead='.$_REQUEST['v8'].'';
		}else{
		if ($_REQUEST['v7']!=''){
			$sAdd=$sAdd.' AND T2.cara01idzona='.$_REQUEST['v7'].'';
			$sAdd2=$sAdd2.' AND TB.core16idzona='.$_REQUEST['v7'].'';
			}
		}
	$bConCorreos=false;
	switch($_REQUEST['v3']){
		case 0: //Todas las encuestas.
		$objplano->AdicionarLinea('Encuestas en el periodo '.$_REQUEST['v4']);
		$sSQL='SELECT T1.unad11doc, T1.unad11razonsocial, T2.cara01completa 
FROM cara01encuesta AS T2, unad11terceros AS T1 
WHERE T2.cara01idperaca='.$_REQUEST['v4'].' '.$sAdd.' AND T2.cara01idtercero=T1.unad11id
ORDER BY T1.unad11doc
';
		$sAddTitulo=$cSepara.'Estado';
		break;
		case 1: //Caracterizados.
		$objplano->AdicionarLinea('Encuestas completas en el periodo '.$_REQUEST['v4']);
		$sSQL='SELECT T1.unad11doc, T1.unad11razonsocial 
FROM cara01encuesta AS T2, unad11terceros AS T1 
WHERE T2.cara01idperaca='.$_REQUEST['v4'].' AND T2.cara01completa="S" '.$sAdd.' AND T2.cara01idtercero=T1.unad11id
ORDER BY T1.unad11doc
';
		break;
		case 2: //Pendientes
		$objplano->AdicionarLinea('Encuestas incompletas en el periodo '.$_REQUEST['v4']);
		$sSQL='SELECT T1.unad11doc, T1.unad11razonsocial 
FROM cara01encuesta AS T2, unad11terceros AS T1 
WHERE T2.cara01idperaca='.$_REQUEST['v4'].' AND T2.cara01completa<>"S" '.$sAdd.' AND T2.cara01idtercero=T1.unad11id
ORDER BY T1.unad11doc
';
		break;
		case 3: //No ingresan
		$sAddTitulo=$cSepara.'Condicion'.$cSepara.'Ultimo Ingreso Campus';
		//Mirar el permiso de consulta de correos personales.
		if (seg_revisa_permiso(111, 1, $objDB)){
			$bConCorreos=true;
			$sAddTitulo=$sAddTitulo.$cSepara.'Correo personal'.$cSepara.'Acepta notificaciones'.$cSepara.'Correo notificaciones';
			}
		$objplano->AdicionarLinea('Estudiantes que no han ingresado en el periodo '.$_REQUEST['v4']);
		if ($_REQUEST['v6']!=9){
			//$objplano->AdicionarLinea('No se ha habilitado la consulta de asignados.');
			//$_REQUEST['v4']=-99;
			}
		$sIds='-99';
		//Ahora si los que estan matriculados.
		$sSQL='SELECT T1.unad11doc, T1.unad11razonsocial, TB.core16nuevo, T1.unad11fechaultingreso, T1.unad11correo, T1.unad11aceptanotificacion, T1.unad11correonotifica 
FROM core16actamatricula AS TB, unad11terceros AS T1 
WHERE TB.core16peraca='.$_REQUEST['v4'].$sAdd2.' AND TB.core16tercero NOT IN (
SELECT T2.cara01idtercero FROM cara01encuesta AS T2 WHERE T2.cara01completa="S"
) AND TB.core16tercero=T1.unad11id
ORDER BY T1.unad11doc';
		}
	$sBloque1=''.'Documento'.$cSepara.'Nombres'.$sAddTitulo;
	//$objplano->AdicionarLinea($sTitulo1)
	$objplano->AdicionarLinea($sBloque1);
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_doc=$fila['unad11doc'].$cSepara;
		$lin_estado=$fila['unad11razonsocial'];
		$lin_ulting='';
		$lin_4='';
		if ($_REQUEST['v3']==0){
			$lin_ulting=$cSepara.'Completa';
			if ($fila['cara01completa']!='S'){$lin_ulting=$cSepara.'Incompleta';}
			}
		if ($_REQUEST['v3']==3){
			$sNuevo='Antiguo';
			if ($fila['core16nuevo']==1){$sNuevo='Nuevo';}
			$lin_ulting=$cSepara.$sNuevo.$cSepara.$fila['unad11fechaultingreso'];
			}
		if ($bConCorreos){
			$lin_4=$cSepara.$fila['unad11correo'].$cSepara.$fila['unad11aceptanotificacion'].$cSepara.$fila['unad11correonotifica'];
			}
		$sBloque1=''.$lin_doc.$lin_estado.$lin_ulting.$lin_4;
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