<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.11 martes, 11 de mayo de 2021
*/
/*
/** Archivo para reportes tipo csv 3028.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 11 de mayo de 2021
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
if ($_REQUEST['v6']!=3028){$sError='No se reconoce el medio solicitado';}
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
	$mensajes_3028='lg/lg_3028_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3028)){$mensajes_3028='lg/lg_3028_es.php';}
	require $mensajes_todas;
	require $mensajes_3028;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$saiu23agno=$_REQUEST['v3'];
	$saiu23mes=$_REQUEST['v4'];
	$saiu23estado=$_REQUEST['v5'];
	$saiu23idmedio=$_REQUEST['v6'];
	$saiu23idtema=$_REQUEST['v7'];
	$saiu23idtiposol=$_REQUEST['v8'];
	$sCondi='';
	$asaiu28mes=array();
	$sSQL='SELECT unad17id, unad17nombre FROM unad17mes ORDER BY unad17id';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$asaiu28mes[$fila['unad17id']]=$fila['unad17nombre'];
		}
	$sSubTitulo=' año '.$saiu23agno;
	if ((int)$saiu23mes!=''){
		$sCondi='saiu28mes='.$saiu23mes.'';
		$sSubTitulo=$sSubTitulo.' mes '.$asaiu28mes[$saiu23mes];
		}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t3028.csv';
	$sTituloRpt='sai_mesa_ayuda';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Mesa de ayuda '.$sSubTitulo);
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	//$asaiu28agno=array();
	$asaiu28tiporadicado=array();
	$asaiu28estado=array();
	$asaiu28tipointeresado=array();
	$asaiu28clasesolicitud=array();
	$asaiu28tiposolicitud=array();
	$asaiu28temasolicitud=array();
	$asaiu28idzona=array();
	$asaiu28idcentro=array();
	$asaiu28idescuela=array();
	$asaiu28idprograma=array();
	$asaiu28idperiodo=array();
	//$asaiu28solucion=array();
	//$asaiu28numetapas=array();
	$asaiu28idunidadresp1=array();
	$asaiu28idequiporesp1=array();
	$asaiu28centrotarea1=array();
	$asaiu28idunidadresp2=array();
	$asaiu28idequiporesp2=array();
	$asaiu28centrotarea2=array();
	$asaiu28idunidadresp3=array();
	$asaiu28idequiporesp3=array();
	$asaiu28centrotarea3=array();
	//$asaiu28moduloasociado=array();
	$aSys11=array();
	$sTitulo1='Titulo 1';
	for ($l=1;$l<=20;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	$sBloque1=''.'Mes'.$cSepara.'Dia'.$cSepara.'Tiporadicado'.$cSepara.'Consec'.$cSepara
	.'Hora'.$cSepara.'Minuto'.$cSepara.'Estado'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Solicitante'.$cSepara.'Tipointeresado'.$cSepara
	.'Clasesolicitud'.$cSepara.'Tiposolicitud'.$cSepara.'Temasolicitud'.$cSepara.'Zona'.$cSepara.'Centro'.$cSepara
	.'Codpais'.$cSepara.'Coddepto'.$cSepara.'Codciudad';
	$sTitulo2='Titulo 2';
	for ($l=1;$l<=20;$l++){
		$sTitulo2=$sTitulo2.$cSepara;
		}
	$sBloque2=''.$cSepara.'Escuela'.$cSepara.'Programa'.$cSepara.'Periodo'.$cSepara.'Pqrs'.$cSepara.'Detalle'.$cSepara
	.'Horafin'.$cSepara.'Minutofin'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Responsable'.$cSepara.'Tiemprespdias'.$cSepara.'Tiempresphoras'.$cSepara
	.'Tiemprespminutos'.$cSepara.'Solucion'.$cSepara.'Numetapas'.$cSepara.'Unidadresp1'.$cSepara.'Equiporesp1'.$cSepara
	.'TD'.$cSepara.'Doc'.$cSepara.'Liderrespon1';
	$sTitulo3='Titulo 3';
	for ($l=1;$l<=20;$l++){
		$sTitulo3=$sTitulo3.$cSepara;
		}
	$sBloque3=''.$cSepara.'Tiemprespdias1'.$cSepara.'Tiempresphoras1'.$cSepara.'Centrotarea1'.$cSepara.'Tiempousado1'.$cSepara.'Tiempocalusado1'.$cSepara
	.'Unidadresp2'.$cSepara.'Equiporesp2'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Liderrespon2'.$cSepara.'Tiemprespdias2'.$cSepara.'Tiempresphoras2'.$cSepara
	.'Centrotarea2'.$cSepara.'Tiempousado2'.$cSepara.'Tiempocalusado2'.$cSepara.'Unidadresp3'.$cSepara.'Equiporesp3'.$cSepara
	.'TD'.$cSepara.'Doc'.$cSepara.'Liderrespon3';
	$sTitulo4='Titulo 4';
	for ($l=1;$l<=10;$l++){
		$sTitulo4=$sTitulo4.$cSepara;
		}
	$sBloque4=''.$cSepara.'Tiemprespdias3'.$cSepara.'Tiempresphoras3'.$cSepara.'Centrotarea3'.$cSepara.'Tiempousado3'.$cSepara.'Tiempocalusado3'.$cSepara
	.'TD'.$cSepara.'Doc'.$cSepara.'Supervisor'.$cSepara.'Moduloasociado'.$cSepara.'Etapaactual';
	//$objplano->AdicionarLinea($sTitulo1.$sTitulo2.$sTitulo3.$sTitulo4);
	$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3.$sBloque4);
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$sSQL='SELECT * 
	FROM saiu28mesaayuda_'.$iAgno.' '.$sCondi.'';
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_saiu28mes=$cSepara;
		$lin_saiu28tiporadicado=$cSepara;
		$lin_saiu28consec=$cSepara;
		$lin_saiu28dia=$cSepara;
		$lin_saiu28hora=$cSepara;
		$lin_saiu28minuto=$cSepara;
		$lin_saiu28estado=$cSepara;
		$lin_saiu28idsolicitante=$cSepara.$cSepara.$cSepara;
		$lin_saiu28tipointeresado=$cSepara;
		$lin_saiu28clasesolicitud=$cSepara;
		$lin_saiu28tiposolicitud=$cSepara;
		$lin_saiu28temasolicitud=$cSepara;
		$lin_saiu28idzona=$cSepara;
		$lin_saiu28idcentro=$cSepara;
		$lin_saiu28codpais=$cSepara;
		$lin_saiu28coddepto=$cSepara;
		$lin_saiu28codciudad=$cSepara;
		$lin_saiu28idescuela=$cSepara;
		$lin_saiu28idprograma=$cSepara;
		$lin_saiu28idperiodo=$cSepara;
		$lin_saiu28idpqrs=$cSepara;
		$lin_saiu28detalle=$cSepara;
		$lin_saiu28horafin=$cSepara;
		$lin_saiu28minutofin=$cSepara;
		$lin_saiu28idresponsable=$cSepara.$cSepara.$cSepara;
		$lin_saiu28tiemprespdias=$cSepara;
		$lin_saiu28tiempresphoras=$cSepara;
		$lin_saiu28tiemprespminutos=$cSepara;
		$lin_saiu28solucion=$cSepara;
		$lin_saiu28numetapas=$cSepara;
		$lin_saiu28idunidadresp1=$cSepara;
		$lin_saiu28idequiporesp1=$cSepara;
		$lin_saiu28idliderrespon1=$cSepara.$cSepara.$cSepara;
		$lin_saiu28tiemprespdias1=$cSepara;
		$lin_saiu28tiempresphoras1=$cSepara;
		$lin_saiu28centrotarea1=$cSepara;
		$lin_saiu28tiempousado1=$cSepara;
		$lin_saiu28tiempocalusado1=$cSepara;
		$lin_saiu28idunidadresp2=$cSepara;
		$lin_saiu28idequiporesp2=$cSepara;
		$lin_saiu28idliderrespon2=$cSepara.$cSepara.$cSepara;
		$lin_saiu28tiemprespdias2=$cSepara;
		$lin_saiu28tiempresphoras2=$cSepara;
		$lin_saiu28centrotarea2=$cSepara;
		$lin_saiu28tiempousado2=$cSepara;
		$lin_saiu28tiempocalusado2=$cSepara;
		$lin_saiu28idunidadresp3=$cSepara;
		$lin_saiu28idequiporesp3=$cSepara;
		$lin_saiu28idliderrespon3=$cSepara.$cSepara.$cSepara;
		$lin_saiu28tiemprespdias3=$cSepara;
		$lin_saiu28tiempresphoras3=$cSepara;
		$lin_saiu28centrotarea3=$cSepara;
		$lin_saiu28tiempousado3=$cSepara;
		$lin_saiu28tiempocalusado3=$cSepara;
		$lin_saiu28idsupervisor=$cSepara.$cSepara.$cSepara;
		$lin_saiu28moduloasociado=$cSepara;
		$lin_saiu28etapaactual=$cSepara;
		$lin_saiu28agno='['.$fila['saiu28agno'].']';
		if (isset($asaiu28agno[$fila['saiu28agno']])!=0){
			$lin_saiu28agno=utf8_decode($asaiu28agno[$fila['saiu28agno']]);
			}
		$lin_saiu28mes='['.$fila['saiu28mes'].']';
		if (isset($asaiu28mes[$fila['saiu28mes']])!=0){
			$lin_saiu28mes=utf8_decode($asaiu28mes[$fila['saiu28mes']]);
			}
		$i_saiu28tiporadicado=$fila['saiu28tiporadicado'];
		if (isset($asaiu28tiporadicado[$i_saiu28tiporadicado])==0){
			$sSQL='SELECT saiu16nombre FROM saiu16tiporadicado WHERE saiu16id='.$i_saiu28tiporadicado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28tiporadicado[$i_saiu28tiporadicado]=str_replace($cSepara, $cComplementa, $filae['saiu16nombre']);
				}else{
				$asaiu28tiporadicado[$i_saiu28tiporadicado]='';
				}
			}
		$lin_saiu28tiporadicado=$cSepara.utf8_decode($asaiu28tiporadicado[$i_saiu28tiporadicado]);
		$lin_saiu28consec=$cSepara.$fila['saiu28consec'];
		$lin_saiu28dia=$cSepara.$fila['saiu28dia'];
		$lin_saiu28hora=$cSepara.$fila['saiu28hora'];
		$lin_saiu28minuto=$cSepara.$fila['saiu28minuto'];
		$i_saiu28estado=$fila['saiu28estado'];
		if (isset($asaiu28estado[$i_saiu28estado])==0){
			$sSQL='SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id='.$i_saiu28estado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28estado[$i_saiu28estado]=str_replace($cSepara, $cComplementa, $filae['saiu11nombre']);
				}else{
				$asaiu28estado[$i_saiu28estado]='';
				}
			}
		$lin_saiu28estado=$cSepara.utf8_decode($asaiu28estado[$i_saiu28estado]);
		$iTer=$fila['saiu28idsolicitante'];
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
		$lin_saiu28idsolicitante=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$i_saiu28tipointeresado=$fila['saiu28tipointeresado'];
		if (isset($asaiu28tipointeresado[$i_saiu28tipointeresado])==0){
			$sSQL='SELECT bita07nombre FROM bita07tiposolicitante WHERE bita07id='.$i_saiu28tipointeresado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28tipointeresado[$i_saiu28tipointeresado]=str_replace($cSepara, $cComplementa, $filae['bita07nombre']);
				}else{
				$asaiu28tipointeresado[$i_saiu28tipointeresado]='';
				}
			}
		$lin_saiu28tipointeresado=$cSepara.utf8_decode($asaiu28tipointeresado[$i_saiu28tipointeresado]);
		$i_saiu28clasesolicitud=$fila['saiu28clasesolicitud'];
		if (isset($asaiu28clasesolicitud[$i_saiu28clasesolicitud])==0){
			$sSQL='SELECT saiu01titulo FROM saiu01claseser WHERE saiu01id='.$i_saiu28clasesolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28clasesolicitud[$i_saiu28clasesolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu01titulo']);
				}else{
				$asaiu28clasesolicitud[$i_saiu28clasesolicitud]='';
				}
			}
		$lin_saiu28clasesolicitud=$cSepara.utf8_decode($asaiu28clasesolicitud[$i_saiu28clasesolicitud]);
		$i_saiu28tiposolicitud=$fila['saiu28tiposolicitud'];
		if (isset($asaiu28tiposolicitud[$i_saiu28tiposolicitud])==0){
			$sSQL='SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id='.$i_saiu28tiposolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28tiposolicitud[$i_saiu28tiposolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu02titulo']);
				}else{
				$asaiu28tiposolicitud[$i_saiu28tiposolicitud]='';
				}
			}
		$lin_saiu28tiposolicitud=$cSepara.utf8_decode($asaiu28tiposolicitud[$i_saiu28tiposolicitud]);
		$i_saiu28temasolicitud=$fila['saiu28temasolicitud'];
		if (isset($asaiu28temasolicitud[$i_saiu28temasolicitud])==0){
			$sSQL='SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id='.$i_saiu28temasolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28temasolicitud[$i_saiu28temasolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu03titulo']);
				}else{
				$asaiu28temasolicitud[$i_saiu28temasolicitud]='';
				}
			}
		$lin_saiu28temasolicitud=$cSepara.utf8_decode($asaiu28temasolicitud[$i_saiu28temasolicitud]);
		$i_saiu28idzona=$fila['saiu28idzona'];
		if (isset($asaiu28idzona[$i_saiu28idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_saiu28idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idzona[$i_saiu28idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$asaiu28idzona[$i_saiu28idzona]='';
				}
			}
		$lin_saiu28idzona=$cSepara.utf8_decode($asaiu28idzona[$i_saiu28idzona]);
		$i_saiu28idcentro=$fila['saiu28idcentro'];
		if (isset($asaiu28idcentro[$i_saiu28idcentro])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_saiu28idcentro.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idcentro[$i_saiu28idcentro]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$asaiu28idcentro[$i_saiu28idcentro]='';
				}
			}
		$lin_saiu28idcentro=$cSepara.utf8_decode($asaiu28idcentro[$i_saiu28idcentro]);
		$lin_saiu28codpais=$cSepara.$fila['saiu28codpais'];
		$lin_saiu28coddepto=$cSepara.$fila['saiu28coddepto'];
		$lin_saiu28codciudad=$cSepara.$fila['saiu28codciudad'];
		$i_saiu28idescuela=$fila['saiu28idescuela'];
		if (isset($asaiu28idescuela[$i_saiu28idescuela])==0){
			$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$i_saiu28idescuela.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idescuela[$i_saiu28idescuela]=str_replace($cSepara, $cComplementa, $filae['core12nombre']);
				}else{
				$asaiu28idescuela[$i_saiu28idescuela]='';
				}
			}
		$lin_saiu28idescuela=$cSepara.utf8_decode($asaiu28idescuela[$i_saiu28idescuela]);
		$i_saiu28idprograma=$fila['saiu28idprograma'];
		if (isset($asaiu28idprograma[$i_saiu28idprograma])==0){
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$i_saiu28idprograma.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idprograma[$i_saiu28idprograma]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
				}else{
				$asaiu28idprograma[$i_saiu28idprograma]='';
				}
			}
		$lin_saiu28idprograma=$cSepara.utf8_decode($asaiu28idprograma[$i_saiu28idprograma]);
		$i_saiu28idperiodo=$fila['saiu28idperiodo'];
		if (isset($asaiu28idperiodo[$i_saiu28idperiodo])==0){
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$i_saiu28idperiodo.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idperiodo[$i_saiu28idperiodo]=str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
				}else{
				$asaiu28idperiodo[$i_saiu28idperiodo]='';
				}
			}
		$lin_saiu28idperiodo=$cSepara.utf8_decode($asaiu28idperiodo[$i_saiu28idperiodo]);
		$lin_saiu28idpqrs=$cSepara.$fila['saiu28idpqrs'];
		$lin_saiu28detalle=str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu28detalle']));
		$lin_saiu28detalle=$cSepara.str_replace('
', ' ', $lin_saiu28detalle);
		$lin_saiu28horafin=$cSepara.$fila['saiu28horafin'];
		$lin_saiu28minutofin=$cSepara.$fila['saiu28minutofin'];
		$iTer=$fila['saiu28idresponsable'];
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
		$lin_saiu28idresponsable=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_saiu28tiemprespdias=$cSepara.$fila['saiu28tiemprespdias'];
		$lin_saiu28tiempresphoras=$cSepara.$fila['saiu28tiempresphoras'];
		$lin_saiu28tiemprespminutos=$cSepara.$fila['saiu28tiemprespminutos'];
		$lin_saiu28solucion=$cSepara.'['.$fila['saiu28solucion'].']';
		if (isset($asaiu28solucion[$fila['saiu28solucion']])!=0){
			$lin_saiu28solucion=$cSepara.utf8_decode($asaiu28solucion[$fila['saiu28solucion']]);
			}
		$lin_saiu28numetapas=$cSepara.'['.$fila['saiu28numetapas'].']';
		if (isset($asaiu28numetapas[$fila['saiu28numetapas']])!=0){
			$lin_saiu28numetapas=$cSepara.utf8_decode($asaiu28numetapas[$fila['saiu28numetapas']]);
			}
		$i_saiu28idunidadresp1=$fila['saiu28idunidadresp1'];
		if (isset($asaiu28idunidadresp1[$i_saiu28idunidadresp1])==0){
			$sSQL='SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id='.$i_saiu28idunidadresp1.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idunidadresp1[$i_saiu28idunidadresp1]=str_replace($cSepara, $cComplementa, $filae['unae26nombre']);
				}else{
				$asaiu28idunidadresp1[$i_saiu28idunidadresp1]='';
				}
			}
		$lin_saiu28idunidadresp1=$cSepara.utf8_decode($asaiu28idunidadresp1[$i_saiu28idunidadresp1]);
		$i_saiu28idequiporesp1=$fila['saiu28idequiporesp1'];
		if (isset($asaiu28idequiporesp1[$i_saiu28idequiporesp1])==0){
			$sSQL='SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id='.$i_saiu28idequiporesp1.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idequiporesp1[$i_saiu28idequiporesp1]=str_replace($cSepara, $cComplementa, $filae['bita27nombre']);
				}else{
				$asaiu28idequiporesp1[$i_saiu28idequiporesp1]='';
				}
			}
		$lin_saiu28idequiporesp1=$cSepara.utf8_decode($asaiu28idequiporesp1[$i_saiu28idequiporesp1]);
		$iTer=$fila['saiu28idliderrespon1'];
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
		$lin_saiu28idliderrespon1=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_saiu28tiemprespdias1=$cSepara.$fila['saiu28tiemprespdias1'];
		$lin_saiu28tiempresphoras1=$cSepara.$fila['saiu28tiempresphoras1'];
		$i_saiu28centrotarea1=$fila['saiu28centrotarea1'];
		if (isset($asaiu28centrotarea1[$i_saiu28centrotarea1])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_saiu28centrotarea1.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28centrotarea1[$i_saiu28centrotarea1]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$asaiu28centrotarea1[$i_saiu28centrotarea1]='';
				}
			}
		$lin_saiu28centrotarea1=$cSepara.utf8_decode($asaiu28centrotarea1[$i_saiu28centrotarea1]);
		$lin_saiu28tiempousado1=$cSepara.$fila['saiu28tiempousado1'];
		$lin_saiu28tiempocalusado1=$cSepara.$fila['saiu28tiempocalusado1'];
		$i_saiu28idunidadresp2=$fila['saiu28idunidadresp2'];
		if (isset($asaiu28idunidadresp2[$i_saiu28idunidadresp2])==0){
			$sSQL='SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id='.$i_saiu28idunidadresp2.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idunidadresp2[$i_saiu28idunidadresp2]=str_replace($cSepara, $cComplementa, $filae['unae26nombre']);
				}else{
				$asaiu28idunidadresp2[$i_saiu28idunidadresp2]='';
				}
			}
		$lin_saiu28idunidadresp2=$cSepara.utf8_decode($asaiu28idunidadresp2[$i_saiu28idunidadresp2]);
		$i_saiu28idequiporesp2=$fila['saiu28idequiporesp2'];
		if (isset($asaiu28idequiporesp2[$i_saiu28idequiporesp2])==0){
			$sSQL='SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id='.$i_saiu28idequiporesp2.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idequiporesp2[$i_saiu28idequiporesp2]=str_replace($cSepara, $cComplementa, $filae['bita27nombre']);
				}else{
				$asaiu28idequiporesp2[$i_saiu28idequiporesp2]='';
				}
			}
		$lin_saiu28idequiporesp2=$cSepara.utf8_decode($asaiu28idequiporesp2[$i_saiu28idequiporesp2]);
		$iTer=$fila['saiu28idliderrespon2'];
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
		$lin_saiu28idliderrespon2=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_saiu28tiemprespdias2=$cSepara.$fila['saiu28tiemprespdias2'];
		$lin_saiu28tiempresphoras2=$cSepara.$fila['saiu28tiempresphoras2'];
		$i_saiu28centrotarea2=$fila['saiu28centrotarea2'];
		if (isset($asaiu28centrotarea2[$i_saiu28centrotarea2])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_saiu28centrotarea2.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28centrotarea2[$i_saiu28centrotarea2]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$asaiu28centrotarea2[$i_saiu28centrotarea2]='';
				}
			}
		$lin_saiu28centrotarea2=$cSepara.utf8_decode($asaiu28centrotarea2[$i_saiu28centrotarea2]);
		$lin_saiu28tiempousado2=$cSepara.$fila['saiu28tiempousado2'];
		$lin_saiu28tiempocalusado2=$cSepara.$fila['saiu28tiempocalusado2'];
		$i_saiu28idunidadresp3=$fila['saiu28idunidadresp3'];
		if (isset($asaiu28idunidadresp3[$i_saiu28idunidadresp3])==0){
			$sSQL='SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id='.$i_saiu28idunidadresp3.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idunidadresp3[$i_saiu28idunidadresp3]=str_replace($cSepara, $cComplementa, $filae['unae26nombre']);
				}else{
				$asaiu28idunidadresp3[$i_saiu28idunidadresp3]='';
				}
			}
		$lin_saiu28idunidadresp3=$cSepara.utf8_decode($asaiu28idunidadresp3[$i_saiu28idunidadresp3]);
		$i_saiu28idequiporesp3=$fila['saiu28idequiporesp3'];
		if (isset($asaiu28idequiporesp3[$i_saiu28idequiporesp3])==0){
			$sSQL='SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id='.$i_saiu28idequiporesp3.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28idequiporesp3[$i_saiu28idequiporesp3]=str_replace($cSepara, $cComplementa, $filae['bita27nombre']);
				}else{
				$asaiu28idequiporesp3[$i_saiu28idequiporesp3]='';
				}
			}
		$lin_saiu28idequiporesp3=$cSepara.utf8_decode($asaiu28idequiporesp3[$i_saiu28idequiporesp3]);
		$iTer=$fila['saiu28idliderrespon3'];
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
		$lin_saiu28idliderrespon3=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_saiu28tiemprespdias3=$cSepara.$fila['saiu28tiemprespdias3'];
		$lin_saiu28tiempresphoras3=$cSepara.$fila['saiu28tiempresphoras3'];
		$i_saiu28centrotarea3=$fila['saiu28centrotarea3'];
		if (isset($asaiu28centrotarea3[$i_saiu28centrotarea3])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_saiu28centrotarea3.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu28centrotarea3[$i_saiu28centrotarea3]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$asaiu28centrotarea3[$i_saiu28centrotarea3]='';
				}
			}
		$lin_saiu28centrotarea3=$cSepara.utf8_decode($asaiu28centrotarea3[$i_saiu28centrotarea3]);
		$lin_saiu28tiempousado3=$cSepara.$fila['saiu28tiempousado3'];
		$lin_saiu28tiempocalusado3=$cSepara.$fila['saiu28tiempocalusado3'];
		$iTer=$fila['saiu28idsupervisor'];
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
		$lin_saiu28idsupervisor=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_saiu28moduloasociado=$cSepara.'['.$fila['saiu28moduloasociado'].']';
		if (isset($asaiu28moduloasociado[$fila['saiu28moduloasociado']])!=0){
			$lin_saiu28moduloasociado=$cSepara.utf8_decode($asaiu28moduloasociado[$fila['saiu28moduloasociado']]);
			}
		$lin_saiu28etapaactual=$cSepara.$fila['saiu28etapaactual'];
		$sBloque1=''.$lin_saiu28mes.$lin_saiu28dia.$lin_saiu28tiporadicado.$lin_saiu28consec
		.$lin_saiu28hora.$lin_saiu28minuto.$lin_saiu28estado.$lin_saiu28idsolicitante.$lin_saiu28tipointeresado
		.$lin_saiu28clasesolicitud.$lin_saiu28tiposolicitud.$lin_saiu28temasolicitud.$lin_saiu28idzona.$lin_saiu28idcentro
		.$lin_saiu28codpais.$lin_saiu28coddepto.$lin_saiu28codciudad;
		$sBloque2=''.$lin_saiu28idescuela.$lin_saiu28idprograma.$lin_saiu28idperiodo.$lin_saiu28idpqrs.$lin_saiu28detalle
		.$lin_saiu28horafin.$lin_saiu28minutofin.$lin_saiu28idresponsable.$lin_saiu28tiemprespdias.$lin_saiu28tiempresphoras
		.$lin_saiu28tiemprespminutos.$lin_saiu28solucion.$lin_saiu28numetapas.$lin_saiu28idunidadresp1.$lin_saiu28idequiporesp1
		.$lin_saiu28idliderrespon1;
		$sBloque3=''.$lin_saiu28tiemprespdias1.$lin_saiu28tiempresphoras1.$lin_saiu28centrotarea1.$lin_saiu28tiempousado1.$lin_saiu28tiempocalusado1
		.$lin_saiu28idunidadresp2.$lin_saiu28idequiporesp2.$lin_saiu28idliderrespon2.$lin_saiu28tiemprespdias2.$lin_saiu28tiempresphoras2
		.$lin_saiu28centrotarea2.$lin_saiu28tiempousado2.$lin_saiu28tiempocalusado2.$lin_saiu28idunidadresp3.$lin_saiu28idequiporesp3
		.$lin_saiu28idliderrespon3;
		$sBloque4=''.$lin_saiu28tiemprespdias3.$lin_saiu28tiempresphoras3.$lin_saiu28centrotarea3.$lin_saiu28tiempousado3.$lin_saiu28tiempocalusado3
		.$lin_saiu28idsupervisor.$lin_saiu28moduloasociado.$lin_saiu28etapaactual;
		$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3.$sBloque4);
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