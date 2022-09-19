<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.10c sábado, 8 de mayo de 2021
*/
/*
/** Archivo para reportes tipo csv 3018.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date sábado, 8 de mayo de 2021
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
if ($_REQUEST['v6']!=3018){$sError='No se reconoce el medio solicitado';}
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
	$mensajes_3018='lg/lg_3018_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3018)){$mensajes_3018='lg/lg_3018_es.php';}
	require $mensajes_todas;
	require $mensajes_3018;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$saiu23agno=$_REQUEST['v3'];
	$saiu23mes=$_REQUEST['v4'];
	$saiu23estado=$_REQUEST['v5'];
	$saiu23idmedio=$_REQUEST['v6'];
	$saiu23idtema=$_REQUEST['v7'];
	$saiu23idtiposol=$_REQUEST['v8'];
	$sCondi='';
	$asaiu18mes=array();
	$sSQL='SELECT unad17id, unad17nombre FROM unad17mes ORDER BY unad17id';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$asaiu18mes[$fila['unad17id']]=$fila['unad17nombre'];
		}
	$sSubTitulo=' año '.$saiu23agno;
	if ((int)$saiu23mes!=''){
		$sCondi='saiu18mes='.$saiu23mes.'';
		$sSubTitulo=$sSubTitulo.' mes '.$asaiu18mes[$saiu23mes];
		}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t3018.csv';
	$sTituloRpt='sai_telefonico';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Atención Telefónica'.$sSubTitulo);
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	//$asaiu18agno=array();
	$asaiu18tiporadicado=array();
	$asaiu18estado=array();
	$asaiu18idtelefono=array();
	$asaiu18tipointeresado=array();
	$asaiu18clasesolicitud=array();
	$asaiu18tiposolicitud=array();
	$asaiu18temasolicitud=array();
	$asaiu18idzona=array();
	$asaiu18idcentro=array();
	$asaiu18idescuela=array();
	$asaiu18idprograma=array();
	$asaiu18idperiodo=array();
	//$asaiu18paramercadeo=array();
	//$asaiu18solucion=array();
	$aSys11=array();
	$sTitulo1='Titulo 1';
	for ($l=1;$l<=20;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	$sBloque1=''.'Mes'.$cSepara.'Dia'.$cSepara.'Tiporadicado'.$cSepara.'Consec'.$cSepara
	.'Hora'.$cSepara.'Minuto'.$cSepara.'Estado'.$cSepara.'Telefono'.$cSepara.'Numtelefono'.$cSepara
	.'TD'.$cSepara.'Doc'.$cSepara.'Solicitante'.$cSepara.'Tipointeresado'.$cSepara.'Clasesolicitud'.$cSepara.'Tiposolicitud'.$cSepara.'Temasolicitud'.$cSepara
	.'Zona'.$cSepara.'Centro'.$cSepara.'Codpais';
	$sTitulo2='Titulo 2';
	for ($l=1;$l<=19;$l++){
		$sTitulo2=$sTitulo2.$cSepara;
		}
	$sBloque2=''.$cSepara.'Coddepto'.$cSepara.'Codciudad'.$cSepara.'Escuela'.$cSepara.'Programa'.$cSepara.'Periodo'.$cSepara
	.'Numorigen'.$cSepara.'Pqrs'.$cSepara.'Detalle'.$cSepara.'Horafin'.$cSepara.'Minutofin'.$cSepara
	.'Paramercadeo'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Responsable'.$cSepara.'Tiemprespdias'.$cSepara.'Tiempresphoras'.$cSepara.'Tiemprespminutos'.$cSepara
	.'Solucion'.$cSepara.'Caso';
	//$objplano->AdicionarLinea($sTitulo1.$sTitulo2);
	$objplano->AdicionarLinea($sBloque1.$sBloque2);
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$sSQL='SELECT * 
	FROM saiu18telefonico_'.$iAgno.' '.$sCondi.'';
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_saiu18mes='';
		$lin_saiu18tiporadicado=$cSepara;
		$lin_saiu18consec=$cSepara;
		$lin_saiu18dia=$cSepara;
		$lin_saiu18hora=$cSepara;
		$lin_saiu18minuto=$cSepara;
		$lin_saiu18estado=$cSepara;
		$lin_saiu18idtelefono=$cSepara;
		$lin_saiu18numtelefono=$cSepara;
		$lin_saiu18idsolicitante=$cSepara.$cSepara.$cSepara;
		$lin_saiu18tipointeresado=$cSepara;
		$lin_saiu18clasesolicitud=$cSepara;
		$lin_saiu18tiposolicitud=$cSepara;
		$lin_saiu18temasolicitud=$cSepara;
		$lin_saiu18idzona=$cSepara;
		$lin_saiu18idcentro=$cSepara;
		$lin_saiu18codpais=$cSepara;
		$lin_saiu18coddepto=$cSepara;
		$lin_saiu18codciudad=$cSepara;
		$lin_saiu18idescuela=$cSepara;
		$lin_saiu18idprograma=$cSepara;
		$lin_saiu18idperiodo=$cSepara;
		$lin_saiu18numorigen=$cSepara;
		$lin_saiu18idpqrs=$cSepara;
		$lin_saiu18detalle=$cSepara;
		$lin_saiu18horafin=$cSepara;
		$lin_saiu18minutofin=$cSepara;
		$lin_saiu18paramercadeo=$cSepara;
		$lin_saiu19idresponsable=$cSepara.$cSepara.$cSepara;
		$lin_saiu18tiemprespdias=$cSepara;
		$lin_saiu18tiempresphoras=$cSepara;
		$lin_saiu18tiemprespminutos=$cSepara;
		$lin_saiu18solucion=$cSepara;
		$lin_saiu18idcaso=$cSepara;
		$lin_saiu18mes='['.$fila['saiu18mes'].']';
		if (isset($asaiu18mes[$fila['saiu18mes']])!=0){
			$lin_saiu18mes=utf8_decode($asaiu18mes[$fila['saiu18mes']]);
			}
		$i_saiu18tiporadicado=$fila['saiu18tiporadicado'];
		if (isset($asaiu18tiporadicado[$i_saiu18tiporadicado])==0){
			$sSQL='SELECT saiu16nombre FROM saiu16tiporadicado WHERE saiu16id='.$i_saiu18tiporadicado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18tiporadicado[$i_saiu18tiporadicado]=str_replace($cSepara, $cComplementa, $filae['saiu16nombre']);
				}else{
				$asaiu18tiporadicado[$i_saiu18tiporadicado]='';
				}
			}
		$lin_saiu18tiporadicado=$cSepara.utf8_decode($asaiu18tiporadicado[$i_saiu18tiporadicado]);
		$lin_saiu18consec=$cSepara.$fila['saiu18consec'];
		$lin_saiu18dia=$cSepara.$fila['saiu18dia'];
		$lin_saiu18hora=$cSepara.$fila['saiu18hora'];
		$lin_saiu18minuto=$cSepara.$fila['saiu18minuto'];
		$i_saiu18estado=$fila['saiu18estado'];
		if (isset($asaiu18estado[$i_saiu18estado])==0){
			$sSQL='SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id='.$i_saiu18estado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18estado[$i_saiu18estado]=str_replace($cSepara, $cComplementa, $filae['saiu11nombre']);
				}else{
				$asaiu18estado[$i_saiu18estado]='';
				}
			}
		$lin_saiu18estado=$cSepara.utf8_decode($asaiu18estado[$i_saiu18estado]);
		$i_saiu18idtelefono=$fila['saiu18idtelefono'];
		if (isset($asaiu18idtelefono[$i_saiu18idtelefono])==0){
			$sSQL='SELECT saiu22nombre FROM saiu22telefonos WHERE saiu22id='.$i_saiu18idtelefono.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18idtelefono[$i_saiu18idtelefono]=str_replace($cSepara, $cComplementa, $filae['saiu22nombre']);
				}else{
				$asaiu18idtelefono[$i_saiu18idtelefono]='';
				}
			}
		$lin_saiu18idtelefono=$cSepara.utf8_decode($asaiu18idtelefono[$i_saiu18idtelefono]);
		$lin_saiu18numtelefono=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu18numtelefono']));
		$iTer=$fila['saiu18idsolicitante'];
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
		$lin_saiu18idsolicitante=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$i_saiu18tipointeresado=$fila['saiu18tipointeresado'];
		if (isset($asaiu18tipointeresado[$i_saiu18tipointeresado])==0){
			$sSQL='SELECT bita07nombre FROM bita07tiposolicitante WHERE bita07id='.$i_saiu18tipointeresado.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18tipointeresado[$i_saiu18tipointeresado]=str_replace($cSepara, $cComplementa, $filae['bita07nombre']);
				}else{
				$asaiu18tipointeresado[$i_saiu18tipointeresado]='';
				}
			}
		$lin_saiu18tipointeresado=$cSepara.utf8_decode($asaiu18tipointeresado[$i_saiu18tipointeresado]);
		$i_saiu18clasesolicitud=$fila['saiu18clasesolicitud'];
		if (isset($asaiu18clasesolicitud[$i_saiu18clasesolicitud])==0){
			$sSQL='SELECT saiu01titulo FROM saiu01claseser WHERE saiu01id='.$i_saiu18clasesolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18clasesolicitud[$i_saiu18clasesolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu01titulo']);
				}else{
				$asaiu18clasesolicitud[$i_saiu18clasesolicitud]='';
				}
			}
		$lin_saiu18clasesolicitud=$cSepara.utf8_decode($asaiu18clasesolicitud[$i_saiu18clasesolicitud]);
		$i_saiu18tiposolicitud=$fila['saiu18tiposolicitud'];
		if (isset($asaiu18tiposolicitud[$i_saiu18tiposolicitud])==0){
			$sSQL='SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id='.$i_saiu18tiposolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18tiposolicitud[$i_saiu18tiposolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu02titulo']);
				}else{
				$asaiu18tiposolicitud[$i_saiu18tiposolicitud]='';
				}
			}
		$lin_saiu18tiposolicitud=$cSepara.utf8_decode($asaiu18tiposolicitud[$i_saiu18tiposolicitud]);
		$i_saiu18temasolicitud=$fila['saiu18temasolicitud'];
		if (isset($asaiu18temasolicitud[$i_saiu18temasolicitud])==0){
			$sSQL='SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id='.$i_saiu18temasolicitud.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18temasolicitud[$i_saiu18temasolicitud]=str_replace($cSepara, $cComplementa, $filae['saiu03titulo']);
				}else{
				$asaiu18temasolicitud[$i_saiu18temasolicitud]='';
				}
			}
		$lin_saiu18temasolicitud=$cSepara.utf8_decode($asaiu18temasolicitud[$i_saiu18temasolicitud]);
		$i_saiu18idzona=$fila['saiu18idzona'];
		if (isset($asaiu18idzona[$i_saiu18idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_saiu18idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18idzona[$i_saiu18idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$asaiu18idzona[$i_saiu18idzona]='';
				}
			}
		$lin_saiu18idzona=$cSepara.utf8_decode($asaiu18idzona[$i_saiu18idzona]);
		$i_saiu18idcentro=$fila['saiu18idcentro'];
		if (isset($asaiu18idcentro[$i_saiu18idcentro])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_saiu18idcentro.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18idcentro[$i_saiu18idcentro]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$asaiu18idcentro[$i_saiu18idcentro]='';
				}
			}
		$lin_saiu18idcentro=$cSepara.utf8_decode($asaiu18idcentro[$i_saiu18idcentro]);
		$lin_saiu18codpais=$cSepara.$fila['saiu18codpais'];
		$lin_saiu18coddepto=$cSepara.$fila['saiu18coddepto'];
		$lin_saiu18codciudad=$cSepara.$fila['saiu18codciudad'];
		$i_saiu18idescuela=$fila['saiu18idescuela'];
		if (isset($asaiu18idescuela[$i_saiu18idescuela])==0){
			$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$i_saiu18idescuela.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18idescuela[$i_saiu18idescuela]=str_replace($cSepara, $cComplementa, $filae['core12nombre']);
				}else{
				$asaiu18idescuela[$i_saiu18idescuela]='';
				}
			}
		$lin_saiu18idescuela=$cSepara.utf8_decode($asaiu18idescuela[$i_saiu18idescuela]);
		$i_saiu18idprograma=$fila['saiu18idprograma'];
		if (isset($asaiu18idprograma[$i_saiu18idprograma])==0){
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$i_saiu18idprograma.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18idprograma[$i_saiu18idprograma]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
				}else{
				$asaiu18idprograma[$i_saiu18idprograma]='';
				}
			}
		$lin_saiu18idprograma=$cSepara.utf8_decode($asaiu18idprograma[$i_saiu18idprograma]);
		$i_saiu18idperiodo=$fila['saiu18idperiodo'];
		if (isset($asaiu18idperiodo[$i_saiu18idperiodo])==0){
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$i_saiu18idperiodo.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu18idperiodo[$i_saiu18idperiodo]=str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
				}else{
				$asaiu18idperiodo[$i_saiu18idperiodo]='';
				}
			}
		$lin_saiu18idperiodo=$cSepara.utf8_decode($asaiu18idperiodo[$i_saiu18idperiodo]);
		$lin_saiu18numorigen=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu18numorigen']));
		$lin_saiu18idpqrs=$cSepara.$fila['saiu18idpqrs'];
		$lin_saiu18detalle=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['saiu18detalle']));
		$lin_saiu18horafin=$cSepara.$fila['saiu18horafin'];
		$lin_saiu18minutofin=$cSepara.$fila['saiu18minutofin'];
		$lin_saiu18paramercadeo=$cSepara.'['.$fila['saiu18paramercadeo'].']';
		if (isset($asaiu18paramercadeo[$fila['saiu18paramercadeo']])!=0){
			$lin_saiu18paramercadeo=$cSepara.utf8_decode($asaiu18paramercadeo[$fila['saiu18paramercadeo']]);
			}
		$iTer=$fila['saiu18idresponsable'];
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
		$lin_saiu18tiemprespdias=$cSepara.$fila['saiu18tiemprespdias'];
		$lin_saiu18tiempresphoras=$cSepara.$fila['saiu18tiempresphoras'];
		$lin_saiu18tiemprespminutos=$cSepara.$fila['saiu18tiemprespminutos'];
		$lin_saiu18solucion=$cSepara.'['.$fila['saiu18solucion'].']';
		if (isset($asaiu18solucion[$fila['saiu18solucion']])!=0){
			$lin_saiu18solucion=$cSepara.utf8_decode($asaiu18solucion[$fila['saiu18solucion']]);
			}
		$lin_saiu18idcaso=$cSepara.$fila['saiu18idcaso'];
		$sBloque1=''.$lin_saiu18mes.$lin_saiu18dia.$lin_saiu18tiporadicado.$lin_saiu18consec
		.$lin_saiu18hora.$lin_saiu18minuto.$lin_saiu18estado.$lin_saiu18idtelefono.$lin_saiu18numtelefono
		.$lin_saiu18idsolicitante.$lin_saiu18tipointeresado.$lin_saiu18clasesolicitud.$lin_saiu18tiposolicitud.$lin_saiu18temasolicitud
		.$lin_saiu18idzona.$lin_saiu18idcentro.$lin_saiu18codpais;
		$sBloque2=''.$lin_saiu18coddepto.$lin_saiu18codciudad.$lin_saiu18idescuela.$lin_saiu18idprograma.$lin_saiu18idperiodo
		.$lin_saiu18numorigen.$lin_saiu18idpqrs.$lin_saiu18detalle.$lin_saiu18horafin.$lin_saiu18minutofin
		.$lin_saiu18paramercadeo.$lin_saiu19idresponsable.$lin_saiu18tiemprespdias.$lin_saiu18tiempresphoras.$lin_saiu18tiemprespminutos
		.$lin_saiu18solucion.$lin_saiu18idcaso;
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