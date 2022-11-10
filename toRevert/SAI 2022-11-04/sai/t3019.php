<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.10c sábado, 8 de mayo de 2021
*/
/*
/** Archivo para reportes tipo csv 3019.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date sábado, 8 de mayo de 2021
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
	header('Location:./nopermiso.php');
	die();
	}
$_SESSION['u_ultimominuto']=iminutoavance();
$sError='';
$bEntra=true;
$bDebug=false;
if (isset($_REQUEST['clave'])==0){$_REQUEST['clave']='';}
if (isset($_REQUEST['v3'])==0){$_REQUEST['v3']='';}
if (isset($_REQUEST['v4'])==0){$_REQUEST['v4']='';}
if (isset($_REQUEST['v5'])==0){$_REQUEST['v5']='';}
if (isset($_REQUEST['v6'])==0){$_REQUEST['v6']='';}
if (isset($_REQUEST['v7'])==0){$_REQUEST['v7']='';}
if (isset($_REQUEST['v8'])==0){$_REQUEST['v8']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($_REQUEST['v6']!=3019){$sError='No se reconoce el medio solicitado';}
if ($sError==''){
	if ((int)$_REQUEST['v3']==0){
		$sError='No se ha definido el a&ntilde;o a consultar';
		}else{
		$iAgno=$_REQUEST['v3'];
		}
	}
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
	$mensajes_3019='lg/lg_3019_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3019)){$mensajes_3019='lg/lg_3019_es.php';}
	require $mensajes_todas;
	require $mensajes_3019;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$saiu23agno=$_REQUEST['v3'];
	$saiu23mes=$_REQUEST['v4'];
	$saiu23estado=$_REQUEST['v5'];
	$saiu23idmedio=$_REQUEST['v6'];
	$saiu23idtema=$_REQUEST['v7'];
	$saiu23idtiposol=$_REQUEST['v8'];
	$sCondi='';
	$asaiu19mes=array();
	$sSQL='SELECT unad17id, unad17nombre FROM unad17mes ORDER BY unad17id';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$asaiu19mes[$fila['unad17id']]=$fila['unad17nombre'];
		}
	$sSubTitulo=' año '.$saiu23agno;
	if ((int)$saiu23mes!=''){
		$sCondi='saiu19mes='.$saiu23mes.'';
		$sSubTitulo=$sSubTitulo.' mes '.$asaiu19mes[$saiu23mes];
		}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t3019.csv';
	$sTituloRpt='sai_chat';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Sesiones de chat '.$sSubTitulo);
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	//$asaiu19agno=array();
	$asaiu19tiporadicado=array();
	$asaiu19estado=array();
	$asaiu19tipointeresado=array();
	$asaiu19clasesolicitud=array();
	$asaiu19tiposolicitud=array();
	$asaiu19temasolicitud=array();
	$asaiu19idzona=array();
	$asaiu19idcentro=array();
	$asaiu19idescuela=array();
	$asaiu19idprograma=array();
	$asaiu19idperiodo=array();
	//$asaiu19paramercadeo=array();
	//$asaiu19solucion=array();
	$aSys11=array();
	$sTitulo1='Titulo 1';
	for ($l=1;$l<=20;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	$sBloque1=''.'Mes'.$cSepara.'Dia'.$cSepara.'Tiporadicado'.$cSepara.'Consec'.$cSepara
	.'Hora'.$cSepara.'Minuto'.$cSepara.'Estado'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Solicitante'.$cSepara
	.'Tipointeresado'.$cSepara.'Clasesolicitud'.$cSepara.'Tiposolicitud'.$cSepara.'Temasolicitud'.$cSepara.'Zona'.$cSepara
	.'Centro'.$cSepara.'Codpais'.$cSepara.'Coddepto';
	$sTitulo2='Titulo 2';
	for ($l=1;$l<=18;$l++){
		$sTitulo2=$sTitulo2.$cSepara;
		}
	$sBloque2=''.$cSepara.'Codciudad'.$cSepara.'Escuela'.$cSepara.'Programa'.$cSepara.'Periodo'.$cSepara.'Numorigen'.$cSepara
	.'Pqrs'.$cSepara.'Detalle'.$cSepara.'Horafin'.$cSepara.'Minutofin'.$cSepara.'Paramercadeo'.$cSepara
	.'TD'.$cSepara.'Doc'.$cSepara.'Responsable'.$cSepara.'Tiemprespdias'.$cSepara.'Tiempresphoras'.$cSepara.'Tiemprespminutos'.$cSepara.'Solucion'.$cSepara
	.'Caso';
	//$objplano->AdicionarLinea($sTitulo1.$sTitulo2);
	$objplano->AdicionarLinea($sBloque1.$sBloque2);
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$sSQL='SELECT * 
	FROM saiu19chat_'.$iAgno.' '.$sCondi.'';
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_saiu19mes=$cSepara;
		$lin_saiu19tiporadicado=$cSepara;
		$lin_saiu19consec=$cSepara;
		$lin_saiu19dia=$cSepara;
		$lin_saiu19hora=$cSepara;
		$lin_saiu19minuto=$cSepara;
		$lin_saiu19estado=$cSepara;
		$lin_saiu19idsolicitante=$cSepara.$cSepara.$cSepara;
		$lin_saiu19tipointeresado=$cSepara;
		$lin_saiu19clasesolicitud=$cSepara;
		$lin_saiu19tiposolicitud=$cSepara;
		$lin_saiu19temasolicitud=$cSepara;
		$lin_saiu19idzona=$cSepara;
		$lin_saiu19idcentro=$cSepara;
		$lin_saiu19codpais=$cSepara;
		$lin_saiu19coddepto=$cSepara;
		$lin_saiu19codciudad=$cSepara;
		$lin_saiu19idescuela=$cSepara;
		$lin_saiu19idprograma=$cSepara;
		$lin_saiu19idperiodo=$cSepara;
		$lin_saiu19numorigen=$cSepara;
		$lin_saiu19idpqrs=$cSepara;
		$lin_saiu19detalle=$cSepara;
		$lin_saiu19horafin=$cSepara;
		$lin_saiu19minutofin=$cSepara;
		$lin_saiu19paramercadeo=$cSepara;
		$lin_saiu19idresponsable=$cSepara.$cSepara.$cSepara;
		$lin_saiu19tiemprespdias=$cSepara;
		$lin_saiu19tiempresphoras=$cSepara;
		$lin_saiu19tiemprespminutos=$cSepara;
		$lin_saiu19solucion=$cSepara;
		$lin_saiu19idcaso=$cSepara;
		$lin_saiu19mes='['.$fila['saiu19mes'].']';
		if (isset($asaiu19mes[$fila['saiu19mes']])!=0){
			$lin_saiu19mes=utf8_decode($asaiu19mes[$fila['saiu19mes']]);
			}
		$i_saiu19tiporadicado=$fila['saiu19tiporadicado'];
		if (isset($asaiu19tiporadicado[$i_saiu19tiporadicado])==0){
			$sSQL='SELECT saiu16nombre FROM saiu16tiporadicado WHERE saiu16id='.$i_saiu19tiporadicado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19tiporadicado[$i_saiu19tiporadicado]=str_replace($cSepara, $cComplementa, $filae['saiu16nombre']);
				}else{
				$asaiu19tiporadicado[$i_saiu19tiporadicado]='';
				}
			}
		$lin_saiu19tiporadicado=$cSepara.utf8_decode($asaiu19tiporadicado[$i_saiu19tiporadicado]);
		$lin_saiu19consec=$cSepara.$fila['saiu19consec'];
		$lin_saiu19dia=$cSepara.$fila['saiu19dia'];
		$lin_saiu19hora=$cSepara.$fila['saiu19hora'];
		$lin_saiu19minuto=$cSepara.$fila['saiu19minuto'];
		$i_saiu19estado=$fila['saiu19estado'];
		if (isset($asaiu19estado[$i_saiu19estado])==0){
			$sSQL='SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id='.$i_saiu19estado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19estado[$i_saiu19estado]=str_replace($cSepara, $cComplementa, $filae['saiu11nombre']);
				}else{
				$asaiu19estado[$i_saiu19estado]='';
				}
			}
		$lin_saiu19estado=$cSepara.utf8_decode($asaiu19estado[$i_saiu19estado]);
		$iTer=$fila['saiu19idsolicitante'];
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
		$lin_saiu19idsolicitante=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$i_saiu19tipointeresado=$fila['saiu19tipointeresado'];
		if (isset($asaiu19tipointeresado[$i_saiu19tipointeresado])==0){
			$sSQL='SELECT bita07nombre FROM bita07tiposolicitante WHERE bita07id='.$i_saiu19tipointeresado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19tipointeresado[$i_saiu19tipointeresado]=str_replace($cSepara, $cComplementa, $filae['bita07nombre']);
				}else{
				$asaiu19tipointeresado[$i_saiu19tipointeresado]='';
				}
			}
		$lin_saiu19tipointeresado=$cSepara.utf8_decode($asaiu19tipointeresado[$i_saiu19tipointeresado]);
		$i_saiu19clasesolicitud=$fila['saiu19clasesolicitud'];
		if (isset($asaiu19clasesolicitud[$i_saiu19clasesolicitud])==0){
			$sSQL='SELECT saiu01titulo FROM saiu01claseser WHERE saiu01id='.$i_saiu19clasesolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19clasesolicitud[$i_saiu19clasesolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu01titulo']);
				}else{
				$asaiu19clasesolicitud[$i_saiu19clasesolicitud]='';
				}
			}
		$lin_saiu19clasesolicitud=$cSepara.utf8_decode($asaiu19clasesolicitud[$i_saiu19clasesolicitud]);
		$i_saiu19tiposolicitud=$fila['saiu19tiposolicitud'];
		if (isset($asaiu19tiposolicitud[$i_saiu19tiposolicitud])==0){
			$sSQL='SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id='.$i_saiu19tiposolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19tiposolicitud[$i_saiu19tiposolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu02titulo']);
				}else{
				$asaiu19tiposolicitud[$i_saiu19tiposolicitud]='';
				}
			}
		$lin_saiu19tiposolicitud=$cSepara.utf8_decode($asaiu19tiposolicitud[$i_saiu19tiposolicitud]);
		$i_saiu19temasolicitud=$fila['saiu19temasolicitud'];
		if (isset($asaiu19temasolicitud[$i_saiu19temasolicitud])==0){
			$sSQL='SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id='.$i_saiu19temasolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19temasolicitud[$i_saiu19temasolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu03titulo']);
				}else{
				$asaiu19temasolicitud[$i_saiu19temasolicitud]='';
				}
			}
		$lin_saiu19temasolicitud=$cSepara.utf8_decode($asaiu19temasolicitud[$i_saiu19temasolicitud]);
		$i_saiu19idzona=$fila['saiu19idzona'];
		if (isset($asaiu19idzona[$i_saiu19idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_saiu19idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19idzona[$i_saiu19idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$asaiu19idzona[$i_saiu19idzona]='';
				}
			}
		$lin_saiu19idzona=$cSepara.utf8_decode($asaiu19idzona[$i_saiu19idzona]);
		$i_saiu19idcentro=$fila['saiu19idcentro'];
		if (isset($asaiu19idcentro[$i_saiu19idcentro])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_saiu19idcentro.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19idcentro[$i_saiu19idcentro]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$asaiu19idcentro[$i_saiu19idcentro]='';
				}
			}
		$lin_saiu19idcentro=$cSepara.utf8_decode($asaiu19idcentro[$i_saiu19idcentro]);
		$lin_saiu19codpais=$cSepara.$fila['saiu19codpais'];
		$lin_saiu19coddepto=$cSepara.$fila['saiu19coddepto'];
		$lin_saiu19codciudad=$cSepara.$fila['saiu19codciudad'];
		$i_saiu19idescuela=$fila['saiu19idescuela'];
		if (isset($asaiu19idescuela[$i_saiu19idescuela])==0){
			$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$i_saiu19idescuela.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19idescuela[$i_saiu19idescuela]=str_replace($cSepara, $cComplementa, $filae['core12nombre']);
				}else{
				$asaiu19idescuela[$i_saiu19idescuela]='';
				}
			}
		$lin_saiu19idescuela=$cSepara.utf8_decode($asaiu19idescuela[$i_saiu19idescuela]);
		$i_saiu19idprograma=$fila['saiu19idprograma'];
		if (isset($asaiu19idprograma[$i_saiu19idprograma])==0){
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$i_saiu19idprograma.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19idprograma[$i_saiu19idprograma]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
				}else{
				$asaiu19idprograma[$i_saiu19idprograma]='';
				}
			}
		$lin_saiu19idprograma=$cSepara.utf8_decode($asaiu19idprograma[$i_saiu19idprograma]);
		$i_saiu19idperiodo=$fila['saiu19idperiodo'];
		if (isset($asaiu19idperiodo[$i_saiu19idperiodo])==0){
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$i_saiu19idperiodo.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu19idperiodo[$i_saiu19idperiodo]=str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
				}else{
				$asaiu19idperiodo[$i_saiu19idperiodo]='';
				}
			}
		$lin_saiu19idperiodo=$cSepara.utf8_decode($asaiu19idperiodo[$i_saiu19idperiodo]);
		$lin_saiu19numorigen=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu19numorigen']));
		$lin_saiu19idpqrs=$cSepara.$fila['saiu19idpqrs'];
		$lin_saiu19detalle=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu19detalle']));
		$lin_saiu19horafin=$cSepara.$fila['saiu19horafin'];
		$lin_saiu19minutofin=$cSepara.$fila['saiu19minutofin'];
		$lin_saiu19paramercadeo=$cSepara.'['.$fila['saiu19paramercadeo'].']';
		if (isset($asaiu19paramercadeo[$fila['saiu19paramercadeo']])!=0){
			$lin_saiu19paramercadeo=$cSepara.utf8_decode($asaiu19paramercadeo[$fila['saiu19paramercadeo']]);
			}
		$iTer=$fila['saiu19idresponsable'];
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
		$lin_saiu19idresponsable=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_saiu19tiemprespdias=$cSepara.$fila['saiu19tiemprespdias'];
		$lin_saiu19tiempresphoras=$cSepara.$fila['saiu19tiempresphoras'];
		$lin_saiu19tiemprespminutos=$cSepara.$fila['saiu19tiemprespminutos'];
		$lin_saiu19solucion=$cSepara.'['.$fila['saiu19solucion'].']';
		if (isset($asaiu19solucion[$fila['saiu19solucion']])!=0){
			$lin_saiu19solucion=$cSepara.utf8_decode($asaiu19solucion[$fila['saiu19solucion']]);
			}
		$lin_saiu19idcaso=$cSepara.$fila['saiu19idcaso'];
		$sBloque1=''.$lin_saiu19mes.$lin_saiu19dia.$lin_saiu19tiporadicado.$lin_saiu19consec
		.$lin_saiu19hora.$lin_saiu19minuto.$lin_saiu19estado.$lin_saiu19idsolicitante
		.$lin_saiu19tipointeresado.$lin_saiu19clasesolicitud.$lin_saiu19tiposolicitud.$lin_saiu19temasolicitud.$lin_saiu19idzona
		.$lin_saiu19idcentro.$lin_saiu19codpais.$lin_saiu19coddepto;
		$sBloque2=''.$lin_saiu19codciudad.$lin_saiu19idescuela.$lin_saiu19idprograma.$lin_saiu19idperiodo.$lin_saiu19numorigen
		.$lin_saiu19idpqrs.$lin_saiu19detalle.$lin_saiu19horafin.$lin_saiu19minutofin.$lin_saiu19paramercadeo
		.$lin_saiu19idresponsable.$lin_saiu19tiemprespdias.$lin_saiu19tiempresphoras.$lin_saiu19tiemprespminutos.$lin_saiu19solucion
		.$lin_saiu19idcaso;
		$objplano->AdicionarLinea($sBloque1.$sBloque2);
		}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
	}else{
	echo $sError;
	}
?>