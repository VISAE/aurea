<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.11 lunes, 10 de mayo de 2021
*/
/*
/** Archivo para reportes tipo csv 3020.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date lunes, 10 de mayo de 2021
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
if (isset($_REQUEST['v9'])==0){$_REQUEST['v9']='';}
if (isset($_REQUEST['v10'])==0){$_REQUEST['v10']='';}
if (isset($_REQUEST['v11'])==0){$_REQUEST['v11']='';}
if (isset($_REQUEST['v12'])==0){$_REQUEST['v12']='';}
if (isset($_REQUEST['v13'])==0){$_REQUEST['v13']='';}
if (isset($_REQUEST['v14'])==0){$_REQUEST['v14']='';}
if (isset($_REQUEST['v15'])==0){$_REQUEST['v15']='';}
if (isset($_REQUEST['v16'])==0){$_REQUEST['v16']='';}
if (isset($_REQUEST['v17'])==0){$_REQUEST['v17']='';}
if (isset($_REQUEST['v18'])==0){$_REQUEST['v18']='';}
if (isset($_REQUEST['v19'])==0){$_REQUEST['v19']='';}
if (isset($_REQUEST['v20'])==0){$_REQUEST['v20']='';}
if (isset($_REQUEST['v21'])==0){$_REQUEST['v21']='';}
if (isset($_REQUEST['v22'])==0){$_REQUEST['v22']='';}
if (isset($_REQUEST['v23'])==0){$_REQUEST['v23']='';}
if (isset($_REQUEST['v24'])==0){$_REQUEST['v24']='';}
if (isset($_REQUEST['v25'])==0){$_REQUEST['v25']='';}
if (isset($_REQUEST['v26'])==0){$_REQUEST['v26']='';}
if (isset($_REQUEST['v27'])==0){$_REQUEST['v27']='';}
if (isset($_REQUEST['v28'])==0){$_REQUEST['v28']='';}
if (isset($_REQUEST['v29'])==0){$_REQUEST['v29']='';}
if (isset($_REQUEST['v30'])==0){$_REQUEST['v30']='';}
if (isset($_REQUEST['v31'])==0){$_REQUEST['v31']='';}
if (isset($_REQUEST['v32'])==0){$_REQUEST['v32']='';}
if (isset($_REQUEST['v33'])==0){$_REQUEST['v33']='';}
if (isset($_REQUEST['v34'])==0){$_REQUEST['v34']='';}
if (isset($_REQUEST['v35'])==0){$_REQUEST['v35']='';}
if (isset($_REQUEST['v36'])==0){$_REQUEST['v36']='';}
if (isset($_REQUEST['v37'])==0){$_REQUEST['v37']='';}
if (isset($_REQUEST['v38'])==0){$_REQUEST['v38']='';}
if (isset($_REQUEST['v39'])==0){$_REQUEST['v39']='';}
if (isset($_REQUEST['v40'])==0){$_REQUEST['v40']='';}
if (isset($_REQUEST['v41'])==0){$_REQUEST['v41']='';}
if (isset($_REQUEST['v42'])==0){$_REQUEST['v42']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($_REQUEST['v6']!=3020){$sError='No se reconoce el medio solicitado';}
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
	$mensajes_3020='lg/lg_3020_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3020)){$mensajes_3020='lg/lg_3020_es.php';}
	require $mensajes_todas;
	require $mensajes_3020;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$saiu23agno=$_REQUEST['v3'];
	$saiu23mes=$_REQUEST['v4'];
	$saiu23estado=$_REQUEST['v5'];
	$saiu23idmedio=$_REQUEST['v6'];
	$saiu23idtema=$_REQUEST['v7'];
	$saiu23idtiposol=$_REQUEST['v8'];
	$sCondi='';
	$sSubTitulo=' año '.$saiu23agno;
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t3020.csv';
	$sTituloRpt='sai_correo';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Registro de correos '.$sSubTitulo);
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$asaiu20mes=array();
	$sSQL='SELECT unad17id, unad17nombre FROM unad17mes ORDER BY unad17id';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$asaiu20mes[$fila['unad17id']]=$fila['unad17nombre'];
		}
	$asaiu20tiporadicado=array();
	$asaiu20estado=array();
	$asaiu20idcorreo=array();
	$asaiu20tipointeresado=array();
	$asaiu20clasesolicitud=array();
	$asaiu20tiposolicitud=array();
	$asaiu20temasolicitud=array();
	$asaiu20idzona=array();
	$asaiu20idcentro=array();
	$asaiu20idescuela=array();
	$asaiu20idprograma=array();
	$asaiu20idperiodo=array();
	//$asaiu20paramercadeo=array();
	//$asaiu20solucion=array();
	$aSys11=array();
	$sTitulo1='Titulo 1';
	for ($l=1;$l<=20;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	$sBloque1=''.'Mes'.$cSepara.'Dia'.$cSepara.'Hora'.$cSepara.'Minuto'.$cSepara.'Tiporadicado'.$cSepara.'Consec'.$cSepara.'Origenagno'.$cSepara
	.'Origenmes'.$cSepara.'Origenid'.$cSepara
	.'Estado'.$cSepara.'Correo'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Solicitante'.$cSepara.'Tipointeresado'.$cSepara.'Clasesolicitud'.$cSepara
	.'Tiposolicitud'.$cSepara.'Temasolicitud'.$cSepara.'Zona';
	$sTitulo2='Titulo 2';
	for ($l=1;$l<=20;$l++){
		$sTitulo2=$sTitulo2.$cSepara;
		}
	$sBloque2=''.$cSepara.'Centro'.$cSepara.'Codpais'.$cSepara.'Coddepto'.$cSepara.'Codciudad'.$cSepara.'Escuela'.$cSepara
	.'Programa'.$cSepara.'Periodo'.$cSepara.'Numorigen'.$cSepara.'Pqrs'.$cSepara.'Detalle'.$cSepara
	.'Horafin'.$cSepara.'Minutofin'.$cSepara.'Paramercadeo'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Responsable'.$cSepara.'Tiemprespdias'.$cSepara
	.'Tiempresphoras'.$cSepara.'Tiemprespminutos'.$cSepara.'Solucion';
	$sTitulo3='Titulo 3';
	for ($l=1;$l<=3;$l++){
		$sTitulo3=$sTitulo3.$cSepara;
		}
	$sBloque3=''.$cSepara.'Caso'.$cSepara.'Numcorreo'.$cSepara.'Correoorigen';
	//$objplano->AdicionarLinea($sTitulo1.$sTitulo2.$sTitulo3);
	$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3);
	$sSQL='SELECT * 
	FROM saiu20correo_'.$iAgno.' 
	'.$sCondi.'';
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_saiu20mes=$cSepara;
		$lin_saiu20tiporadicado=$cSepara;
		$lin_saiu20consec=$cSepara;
		$lin_saiu20origenagno=$cSepara;
		$lin_saiu20origenmes=$cSepara;
		$lin_saiu20origenid=$cSepara;
		$lin_saiu20dia=$cSepara;
		$lin_saiu20hora=$cSepara;
		$lin_saiu20minuto=$cSepara;
		$lin_saiu20estado=$cSepara;
		$lin_saiu20idcorreo=$cSepara;
		$lin_saiu20idsolicitante=$cSepara.$cSepara.$cSepara;
		$lin_saiu20tipointeresado=$cSepara;
		$lin_saiu20clasesolicitud=$cSepara;
		$lin_saiu20tiposolicitud=$cSepara;
		$lin_saiu20temasolicitud=$cSepara;
		$lin_saiu20idzona=$cSepara;
		$lin_saiu20idcentro=$cSepara;
		$lin_saiu20codpais=$cSepara;
		$lin_saiu20coddepto=$cSepara;
		$lin_saiu20codciudad=$cSepara;
		$lin_saiu20idescuela=$cSepara;
		$lin_saiu20idprograma=$cSepara;
		$lin_saiu20idperiodo=$cSepara;
		$lin_saiu20numorigen=$cSepara;
		$lin_saiu20idpqrs=$cSepara;
		$lin_saiu20detalle=$cSepara;
		$lin_saiu20horafin=$cSepara;
		$lin_saiu20minutofin=$cSepara;
		$lin_saiu20paramercadeo=$cSepara;
		$lin_saiu20idresponsable=$cSepara.$cSepara.$cSepara;
		$lin_saiu20tiemprespdias=$cSepara;
		$lin_saiu20tiempresphoras=$cSepara;
		$lin_saiu20tiemprespminutos=$cSepara;
		$lin_saiu20solucion=$cSepara;
		$lin_saiu20idcaso=$cSepara;
		$lin_saiu20numcorreo=$cSepara;
		$lin_saiu20correoorigen=$cSepara;
		$lin_saiu20agno=$fila['saiu20agno'];
		$lin_saiu20mes=$cSepara.$fila['saiu20mes'];
		$i_saiu20tiporadicado=$fila['saiu20tiporadicado'];
		if (isset($asaiu20tiporadicado[$i_saiu20tiporadicado])==0){
			$sSQL='SELECT saiu16nombre FROM saiu16tiporadicado WHERE saiu16id='.$i_saiu20tiporadicado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20tiporadicado[$i_saiu20tiporadicado]=str_replace($cSepara, $cComplementa, $filae['saiu16nombre']);
				}else{
				$asaiu20tiporadicado[$i_saiu20tiporadicado]='';
				}
			}
		$lin_saiu20tiporadicado=$cSepara.utf8_decode($asaiu20tiporadicado[$i_saiu20tiporadicado]);
		$lin_saiu20consec=$cSepara.$fila['saiu20consec'];
		$lin_saiu20origenagno=$cSepara.$fila['saiu20origenagno'];
		$lin_saiu20origenmes=$cSepara.$fila['saiu20origenmes'];
		$lin_saiu20origenid=$cSepara.$fila['saiu20origenid'];
		$lin_saiu20dia=$cSepara.$fila['saiu20dia'];
		$lin_saiu20hora=$cSepara.$fila['saiu20hora'];
		$lin_saiu20minuto=$cSepara.$fila['saiu20minuto'];
		$i_saiu20estado=$fila['saiu20estado'];
		if (isset($asaiu20estado[$i_saiu20estado])==0){
			$sSQL='SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id='.$i_saiu20estado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20estado[$i_saiu20estado]=str_replace($cSepara, $cComplementa, $filae['saiu11nombre']);
				}else{
				$asaiu20estado[$i_saiu20estado]='';
				}
			}
		$lin_saiu20estado=$cSepara.utf8_decode($asaiu20estado[$i_saiu20estado]);
		$i_saiu20idcorreo=$fila['saiu20idcorreo'];
		if (isset($asaiu20idcorreo[$i_saiu20idcorreo])==0){
			$sSQL='SELECT saiu57titulo FROM saiu57correos WHERE saiu57id='.$i_saiu20idcorreo.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20idcorreo[$i_saiu20idcorreo]=str_replace($cSepara, $cComplementa, $filae['saiu57titulo']);
				}else{
				$asaiu20idcorreo[$i_saiu20idcorreo]='';
				}
			}
		$lin_saiu20idcorreo=$cSepara.utf8_decode($asaiu20idcorreo[$i_saiu20idcorreo]);
		$iTer=$fila['saiu20idsolicitante'];
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
		$lin_saiu20idsolicitante=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$i_saiu20tipointeresado=$fila['saiu20tipointeresado'];
		if (isset($asaiu20tipointeresado[$i_saiu20tipointeresado])==0){
			$sSQL='SELECT bita07nombre FROM bita07tiposolicitante WHERE bita07id='.$i_saiu20tipointeresado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20tipointeresado[$i_saiu20tipointeresado]=str_replace($cSepara, $cComplementa, $filae['bita07nombre']);
				}else{
				$asaiu20tipointeresado[$i_saiu20tipointeresado]='';
				}
			}
		$lin_saiu20tipointeresado=$cSepara.utf8_decode($asaiu20tipointeresado[$i_saiu20tipointeresado]);
		$i_saiu20clasesolicitud=$fila['saiu20clasesolicitud'];
		if (isset($asaiu20clasesolicitud[$i_saiu20clasesolicitud])==0){
			$sSQL='SELECT saiu01titulo FROM saiu01claseser WHERE saiu01id='.$i_saiu20clasesolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20clasesolicitud[$i_saiu20clasesolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu01titulo']);
				}else{
				$asaiu20clasesolicitud[$i_saiu20clasesolicitud]='';
				}
			}
		$lin_saiu20clasesolicitud=$cSepara.utf8_decode($asaiu20clasesolicitud[$i_saiu20clasesolicitud]);
		$i_saiu20tiposolicitud=$fila['saiu20tiposolicitud'];
		if (isset($asaiu20tiposolicitud[$i_saiu20tiposolicitud])==0){
			$sSQL='SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id='.$i_saiu20tiposolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20tiposolicitud[$i_saiu20tiposolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu02titulo']);
				}else{
				$asaiu20tiposolicitud[$i_saiu20tiposolicitud]='';
				}
			}
		$lin_saiu20tiposolicitud=$cSepara.utf8_decode($asaiu20tiposolicitud[$i_saiu20tiposolicitud]);
		$i_saiu20temasolicitud=$fila['saiu20temasolicitud'];
		if (isset($asaiu20temasolicitud[$i_saiu20temasolicitud])==0){
			$sSQL='SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id='.$i_saiu20temasolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20temasolicitud[$i_saiu20temasolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu03titulo']);
				}else{
				$asaiu20temasolicitud[$i_saiu20temasolicitud]='';
				}
			}
		$lin_saiu20temasolicitud=$cSepara.utf8_decode($asaiu20temasolicitud[$i_saiu20temasolicitud]);
		$i_saiu20idzona=$fila['saiu20idzona'];
		if (isset($asaiu20idzona[$i_saiu20idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_saiu20idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20idzona[$i_saiu20idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$asaiu20idzona[$i_saiu20idzona]='';
				}
			}
		$lin_saiu20idzona=$cSepara.utf8_decode($asaiu20idzona[$i_saiu20idzona]);
		$i_saiu20idcentro=$fila['saiu20idcentro'];
		if (isset($asaiu20idcentro[$i_saiu20idcentro])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_saiu20idcentro.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20idcentro[$i_saiu20idcentro]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$asaiu20idcentro[$i_saiu20idcentro]='';
				}
			}
		$lin_saiu20idcentro=$cSepara.utf8_decode($asaiu20idcentro[$i_saiu20idcentro]);
		$lin_saiu20codpais=$cSepara.$fila['saiu20codpais'];
		$lin_saiu20coddepto=$cSepara.$fila['saiu20coddepto'];
		$lin_saiu20codciudad=$cSepara.$fila['saiu20codciudad'];
		$i_saiu20idescuela=$fila['saiu20idescuela'];
		if (isset($asaiu20idescuela[$i_saiu20idescuela])==0){
			$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$i_saiu20idescuela.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20idescuela[$i_saiu20idescuela]=str_replace($cSepara, $cComplementa, $filae['core12nombre']);
				}else{
				$asaiu20idescuela[$i_saiu20idescuela]='';
				}
			}
		$lin_saiu20idescuela=$cSepara.utf8_decode($asaiu20idescuela[$i_saiu20idescuela]);
		$i_saiu20idprograma=$fila['saiu20idprograma'];
		if (isset($asaiu20idprograma[$i_saiu20idprograma])==0){
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$i_saiu20idprograma.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20idprograma[$i_saiu20idprograma]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
				}else{
				$asaiu20idprograma[$i_saiu20idprograma]='';
				}
			}
		$lin_saiu20idprograma=$cSepara.utf8_decode($asaiu20idprograma[$i_saiu20idprograma]);
		$i_saiu20idperiodo=$fila['saiu20idperiodo'];
		if (isset($asaiu20idperiodo[$i_saiu20idperiodo])==0){
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$i_saiu20idperiodo.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu20idperiodo[$i_saiu20idperiodo]=str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
				}else{
				$asaiu20idperiodo[$i_saiu20idperiodo]='';
				}
			}
		$lin_saiu20idperiodo=$cSepara.utf8_decode($asaiu20idperiodo[$i_saiu20idperiodo]);
		$lin_saiu20numorigen=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu20numorigen']));
		$lin_saiu20idpqrs=$cSepara.$fila['saiu20idpqrs'];
		$lin_saiu20detalle=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu20detalle']));
		$lin_saiu20horafin=$cSepara.$fila['saiu20horafin'];
		$lin_saiu20minutofin=$cSepara.$fila['saiu20minutofin'];
		$lin_saiu20paramercadeo=$cSepara.'['.$fila['saiu20paramercadeo'].']';
		if (isset($asaiu20paramercadeo[$fila['saiu20paramercadeo']])!=0){
			$lin_saiu20paramercadeo=$cSepara.utf8_decode($asaiu20paramercadeo[$fila['saiu20paramercadeo']]);
			}
		$iTer=$fila['saiu20idresponsable'];
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
		$lin_saiu20idresponsable=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_saiu20tiemprespdias=$cSepara.$fila['saiu20tiemprespdias'];
		$lin_saiu20tiempresphoras=$cSepara.$fila['saiu20tiempresphoras'];
		$lin_saiu20tiemprespminutos=$cSepara.$fila['saiu20tiemprespminutos'];
		$lin_saiu20solucion=$cSepara.'['.$fila['saiu20solucion'].']';
		if (isset($asaiu20solucion[$fila['saiu20solucion']])!=0){
			$lin_saiu20solucion=$cSepara.utf8_decode($asaiu20solucion[$fila['saiu20solucion']]);
			}
		$lin_saiu20idcaso=$cSepara.$fila['saiu20idcaso'];
		$lin_saiu20numcorreo=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu20numcorreo']));
		$lin_saiu20correoorigen=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu20correoorigen']));
		$sBloque1=''.$lin_saiu20agno.$lin_saiu20mes.$lin_saiu20tiporadicado.$lin_saiu20consec.$lin_saiu20origenagno
		.$lin_saiu20origenmes.$lin_saiu20origenid.$lin_saiu20dia.$lin_saiu20hora.$lin_saiu20minuto
		.$lin_saiu20estado.$lin_saiu20idcorreo.$lin_saiu20idsolicitante.$lin_saiu20tipointeresado.$lin_saiu20clasesolicitud
		.$lin_saiu20tiposolicitud.$lin_saiu20temasolicitud.$lin_saiu20idzona;
		$sBloque2=''.$lin_saiu20idcentro.$lin_saiu20codpais.$lin_saiu20coddepto.$lin_saiu20codciudad.$lin_saiu20idescuela
		.$lin_saiu20idprograma.$lin_saiu20idperiodo.$lin_saiu20numorigen.$lin_saiu20idpqrs.$lin_saiu20detalle
		.$lin_saiu20horafin.$lin_saiu20minutofin.$lin_saiu20paramercadeo.$lin_saiu20idresponsable.$lin_saiu20tiemprespdias
		.$lin_saiu20tiempresphoras.$lin_saiu20tiemprespminutos.$lin_saiu20solucion;
		$sBloque3=''.$lin_saiu20idcaso.$lin_saiu20numcorreo.$lin_saiu20correoorigen;
		$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3);
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