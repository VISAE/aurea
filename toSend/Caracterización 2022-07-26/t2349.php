<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.22.6d miércoles, 23 de enero de 2019
--- Modelo Version 2.25.7 lunes, 9 de noviembre de 2020
--- Modelo Version 2.28.1 jueves, 5 de mayo de 2022
*/
/*
/** Archivo para reportes tipo csv 2349.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 5 de mayo de 2022
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
require $APP->rutacomun.'libdatosrpt.php';
if ($_SESSION['unad_id_tercero']==0){
	header('Location:./nopermiso.php');
	die();
	}
$_SESSION['u_ultimominuto']=iminutoavance();
$sError='';
$iReporte=0;
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
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
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
	$mensajes_2301=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2301)){$mensajes_2301=$APP->rutacomun.'lg/lg_2301_es.php';}
	$mensajes_2349='lg/lg_2349_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2349)){$mensajes_2349='lg/lg_2349_es.php';}
	require $mensajes_todas;
	require $mensajes_2301;
	require $mensajes_2349;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$iCodModulo=2349;
	$idTercero=$_SESSION['unad_id_tercero'];
	$idPeriodo=$_REQUEST['v3'];
	$cara49idzona=$_REQUEST['v4'];
	$cara49idcentro=$_REQUEST['v5'];
	$cara49idescuela=$_REQUEST['v6'];
	$cara49idprograma=$_REQUEST['v7'];
	$cara49tipopoblacion=$_REQUEST['v8'];
	$cara49periodomat=$_REQUEST['v9'];
	$sCondi='';
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t2349.csv';
	$sCondiParte1='(TB.cara01discversion=0 AND ((TB.cara01discsensorial<>"N") OR (TB.cara01discfisica<>"N") OR (TB.cara01disccognitiva<>"N") OR (TB.cara01perayuda<>0)))';
	$sCondiParte2='(TB.cara01discversion=2 AND (TB.cara01discv2tiene=1 OR (TB.cara01perayuda<>0)))';
	// $sCondiDiscapacidad=' AND ('.$sCondiParte1.' OR '.$sCondiParte2.')';
	$sCondiDiscapacidad=' AND '.$sCondiParte2.'';
	$sWhere='';
	$sCondi01='';
	$sDetalleReporte='';
	if ((int)$cara49periodomat==0){
		$sTituloRpt='Necesidades_especiales_Carat_'.$idPeriodo.'';
		list($sNomPeriodo, $sDebugP)=f146_TituloPeriodo($idPeriodo, $objDB, $bDebug);
		$sSubTitulo=utf8_decode('Necesidades especiales periodo '.$sNomPeriodo.'');
		//Condiciones...
		$sCondi01='';
		if ((int)$cara49idprograma!=0){
			$sCondi01=' AND cara01idprograma='.$cara49idprograma.'';
			$sNomPrograma='{'.$cara49idprograma.'}';
			$sDetalleReporte='Programa '.$sNomPrograma;
			$sTituloRpt=$sTituloRpt.'_P'.$cara49idprograma.'';
			}else{
			if ((int)$cara49idescuela!=0){
				$sCondi01=' AND cara01idescuela='.$cara49idescuela.'';
				$sNomEscuela='{'.$cara49idescuela.'}';
				$sDetalleReporte='Escuela '.$sNomEscuela;
				$sTituloRpt=$sTituloRpt.'_E'.$cara49idescuela.'';
				}
			}
		if ((int)$cara49idcentro!=0){
			$sCondi01=$sCondi01.' AND cara01idcead='.$cara49idcentro.'';
			$sNomCentro='{'.$cara49idcentro.'}';
			$sDetalleReporte=$sDetalleReporte.' Centro '.$sNomCentro;
			$sTituloRpt=$sTituloRpt.'_C'.$cara49idcentro.'';
			}else{
			if ((int)$cara49idzona!=0){
				$sCondi01=$sCondi01.' AND cara01idzona='.$cara49idzona.'';
				$sNomZona='{'.$cara49idzona.'}';
				$sDetalleReporte=$sDetalleReporte.' Zona '.$sNomZona;
				$sTituloRpt=$sTituloRpt.'_E'.$cara49idzona.'';
				}
			}
		}else{
		$sTituloRpt='Necesidades_especiales_Mat_'.$cara49periodomat.'';
		list($sNomPeriodo, $sDebugP)=f146_TituloPeriodo($cara49periodomat, $objDB, $bDebug);
		$sSubTitulo=utf8_decode('Necesidades especiales matricula '.$sNomPeriodo.'');
		$sCondi16='';
		if ((int)$cara49idprograma!=0){
			$sCondi16=' AND core16idprograma='.$cara49idprograma.'';
			$sNomPrograma='{'.$cara49idprograma.'}';
			$sDetalleReporte='Programa '.$sNomPrograma;
			$sTituloRpt=$sTituloRpt.'_P'.$cara49idprograma.'';
			}else{
			if ((int)$cara49idescuela!=0){
				$sCondi16=' AND core16idescuela='.$cara49idescuela.'';
				$sNomEscuela='{'.$cara49idescuela.'}';
				$sDetalleReporte='Escuela '.$sNomEscuela;
				$sTituloRpt=$sTituloRpt.'_E'.$cara49idescuela.'';
				}
			}
		if ((int)$cara49idcentro!=0){
			$sCondi16=$sCondi16.' AND core16idcead='.$cara49idcentro.'';
			$sNomCentro='{'.$cara49idcentro.'}';
			$sDetalleReporte=$sDetalleReporte.' Centro '.$sNomCentro;
			$sTituloRpt=$sTituloRpt.'_C'.$cara49idcentro.'';
			}else{
			if ((int)$cara49idzona!=0){
				$sCondi16=$sCondi16.' AND core16idzona='.$cara49idzona.'';
				$sNomZona='{'.$cara49idzona.'}';
				$sDetalleReporte=$sDetalleReporte.' Zona '.$sNomZona;
				$sTituloRpt=$sTituloRpt.'_E'.$cara49idzona.'';
				}
			}
		}
	$bConConsejero=true;
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$objplano->AdicionarLinea('');
	$objplano->AdicionarLinea($sSubTitulo);
	if ($sDetalleReporte!=''){
		$objplano->AdicionarLinea($sDetalleReporte);
		}
	/* Alistar los arreglos para las tablas hijas */
	$acara01idzona=array();
	$acara01idcead=array();
	$acara01perayuda=array();
	$acara01perayuda[0]='Ninguno';
	$acara01discv2sensorial=array();
	$acara02discv2intelectura=array();
	$acara02discv2fisica=array();
	$acara02discv2psico=array();
	$acara01discv2sensorial[0]='';
	$acara02discv2intelectura[0]='';
	$acara02discv2fisica[0]='';
	$acara02discv2psico[0]='';
	//$acara02discv2sistemica=array();
	//$acara02discv2multiple=array();
	$acara02talentoexcepcional=array();
	//$acara01discv2tiene=array();
	$acara01discv2trastaprende=array();
	//$acara01discv2trastornos=array();
	//$acara01discv2contalento=array();
	//$acara01discv2condicionmedica=array();
	//$acara01discv2pruebacoeficiente=array();
	$acara01discv2pruebacoeficiente[3]='SI, Resultados dentro de la media de la población';
	$bConVersion1=false;
	$bConVersion2=false;
	if ((int)$cara49periodomat==0){
		$sWhere='WHERE TB.cara01idperaca='.$idPeriodo.' AND  TB.cara01completa="S" '.$sCondiDiscapacidad.''.$sCondi01;
		}else{
		$sIds='-99';
		//Ver todos los terceros que esten en la matricula.
		$sSQL='SELECT core16tercero FROM core16actamatricula WHERE core16peraca='.$cara49periodomat.$sCondi16.'';
		//if (true){$objplano->AdicionarLinea($sSQL);}
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['core16tercero'];
			}
		$sIdsB='-99';
		$sSQL='SELECT TB.cara01id, TB.cara01idtercero, TB.cara01idperaca 
		FROM cara01encuesta AS TB 
		WHERE TB.cara01idtercero IN ('.$sIds.') AND TB.cara01idperaca<='.$cara49periodomat.' AND TB.cara01completa="S" '.$sCondiDiscapacidad.'
		ORDER BY TB.cara01idtercero, (cara01fechaconfirmadisc/cara01fechaconfirmadisc) DESC, TB.cara01idperaca DESC';
		//if (true){$objplano->AdicionarLinea($sSQL);}
		$tabla=$objDB->ejecutasql($sSQL);
		$idTercero=-99;
		while ($fila=$objDB->sf($tabla)){
			if ($idTercero!=$fila['cara01idtercero']){
				$idTercero=$fila['cara01idtercero'];
				$sIdsB=$sIdsB.','.$fila['cara01id'];
				}
			}
		$sWhere='WHERE TB.cara01id IN ('.$sIdsB.')';
		}
	//Termina el bloque de condicionales.
	$sIds01='-99';
	$sSQL='SELECT TB.cara01id 
	FROM cara01encuesta AS TB 
	'.$sWhere.'';
	//if (true){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$sIds01=$sIds01.','.$fila['cara01id'];
		}
	$bConVersion1=false;
	$bConVersion2=true;
	if ($bConVersion1&&$bConVersion2){
		}else{
		$sCondiDiscapacidad='';
		if ($bConVersion1){
			$sCondiDiscapacidad=' AND ('.$sCondiParte1.')';
			}
		if ($bConVersion2){
			$sCondiDiscapacidad=' AND ('.$sCondiParte2.')';
			}
		if ($sCondiDiscapacidad==''){
			$sCondiDiscapacidad=' AND TB.cara01discversion=-99';
			}
		}
	$sTitulo1='Datos generales';
	for ($l=1;$l<=7;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	if ($bConVersion1){
		$sTitulo1=$sTitulo1.'Versión 1';
		for ($l=1;$l<=8;$l++){
			$sTitulo1=$sTitulo1.$cSepara;
			}
		}
	if ($bConVersion2){
		$sTitulo1=$sTitulo1.'Versión 2';
		}
	$objplano->AdicionarLinea(utf8_decode($sTitulo1));
	$aEscuela=array();
	$aEscuela[0]='{Ninguna}';
	$aPrograma=array();
	$aPrograma[0]='{Ninguna}';
	$aPeriodo=array();
	$sSQL='SELECT core12id, core12sigla FROM core12escuela';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEscuela[$fila['core12id']]=$fila['core12sigla'];
		}
	$sBloque1='Zona'.$cSepara.'Centro'.$cSepara.'Escuela'.$cSepara.'Programa'.$cSepara.'Tipo documento'.$cSepara.'Documento'.$cSepara.'Razon social'.
	$cSepara.'Versión'.$cSepara.'Confirmada';
	$sBloque2='';
	$sBloque3='';
	if ($bConVersion1){
		$sBloque2=$cSepara.'Sensorial'.$cSepara.$cSepara.'Física'.$cSepara.$cSepara.'Cognitiva'.$cSepara.$cSepara.'Necesidades especiales'.$cSepara.'Otras necesidades';
		}
	if ($bConVersion2){
		$sBloque3=$cSepara.'Necesidades especiales'.$cSepara.'Otras necesidades'.$cSepara.
		'Sensorial'.$cSepara.'Intelectual'.$cSepara.'Física o motora'.$cSepara.'Psicosocial'.$cSepara.'Sistémica'.$cSepara.'Sistémica Otro'.$cSepara.'Múltiple'.$cSepara.'Múltiple Otro'.$cSepara.
		'Certificado'.$cSepara.'Tiene trastorno en el aprendizaje'.$cSepara.'Trastorno específico en el aprendizaje'.$cSepara.
		'Dominio sobresaliente en un campo específico'.$cSepara.'Pruebas para definir el coeficiente intelectual'.$cSepara.
		'Presenta alguna condición médica especifica'.$cSepara.'Cuál condición médica especifica';
		}
	$sBloque4=$cSepara.'Periodo Encuesta';
	$objplano->AdicionarLinea(utf8_decode($sBloque1.$sBloque2.$sBloque3.$sBloque4));
	$sSQL='SELECT TB.cara01idzona, TB.cara01idcead, TB.cara01idtercero, TB.cara01discsensorial, TB.cara01discsensorialotra, 
	TB.cara01discfisica, TB.cara01discfisicaotra, TB.cara01disccognitiva, TB.cara01disccognitivaotra, TB.cara01perayuda, 
	TB.cara01perotraayuda, TB.cara01fechaconfirmadisc, TB.cara01discversion, TB.cara01discv2sensorial, TB.cara02discv2intelectura, 
	TB.cara02discv2fisica, TB.cara02discv2psico, TB.cara02discv2sistemica, TB.cara02discv2sistemicaotro, TB.cara02discv2multiple, 
	TB.cara02discv2multipleotro, TB.cara02talentoexcepcional, TB.cara01discv2tiene, TB.cara01discv2trastaprende, TB.cara01discv2soporteorigen, 
	TB.cara01discv2archivoorigen, TB.cara01discv2trastornos, TB.cara01discv2contalento, TB.cara01discv2condicionmedica, TB.cara01discv2condmeddet, 
	TB.cara01discv2pruebacoeficiente, TB.cara01idprograma, TB.cara01idescuela, TB.cara01idperaca 
	FROM cara01encuesta AS TB 
	WHERE TB.cara01id IN ('.$sIds01.')';
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	//if (true){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_cara01idzona='';
		$lin_cara01idtercero=$cSepara.$cSepara.$cSepara;
		$lin_cara01idcead=$cSepara;
		$lin_cara01idescuela=$cSepara;
		$lin_cara01idprograma=$cSepara;
		$lin_cara01discversion=$cSepara;
		$lin_cara01fechaconfirmadisc=$cSepara.'No';
		$lin_cara01discsensorial=$cSepara.$cSepara;
		$lin_cara01discfisica=$cSepara.$cSepara;
		$lin_cara01disccognitiva=$cSepara.$cSepara;
		$lin_cara01perayuda=$cSepara;
		$lin_cara01perotraayuda=$cSepara;
		
		$lin_cara01discv2sensorial=$cSepara;
		$lin_cara02discv2intelectura=$cSepara;
		$lin_cara02discv2fisica=$cSepara;
		$lin_cara02discv2psico=$cSepara;
		$lin_cara02discv2sistemica=$cSepara;
		$lin_cara02discv2sistemicaotro=$cSepara;
		$lin_cara02discv2multiple=$cSepara;
		$lin_cara02discv2multipleotro=$cSepara;
		$lin_cara02talentoexcepcional=$cSepara;
		$lin_cara01discv2tiene=$cSepara;
		$lin_cara01discv2trastaprende=$cSepara;
		$lin_cara01discv2trastornos=$cSepara;
		$lin_cara01discv2archivoorigen=$cSepara.'No';
		$lin_cara01discv2contalento=$cSepara;
		$lin_cara01discv2condicionmedica=$cSepara;
		$lin_cara01discv2condmeddet=$cSepara;
		$lin_cara01discv2pruebacoeficiente=$cSepara;

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
		$lin_cara01idescuela=$cSepara.$aEscuela[$fila['cara01idescuela']];
		$i_cara01idprograma=$fila['cara01idprograma'];
		if (isset($aPrograma[$i_cara01idprograma])==0){
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$i_cara01idprograma.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aPrograma[$i_cara01idprograma]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
				}else{
				$aPrograma[$i_cara01idprograma]='';
				}
			}
		$lin_cara01idprograma=$cSepara.utf8_decode($aPrograma[$i_cara01idprograma]);
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
		$lin_cara01discversion=$cSepara.$fila['cara01discversion'];
		if ($fila['cara01fechaconfirmadisc']!=0){
			$lin_cara01fechaconfirmadisc=$cSepara.'Si';
			}
		if ($bConVersion1){
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
			//Fin de la version 1
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
		if ($bConVersion2){
			$i_cara01discv2sensorial=$fila['cara01discv2sensorial'];
			if (isset($acara01discv2sensorial[$i_cara01discv2sensorial])==0){
				$sSQL='SELECT cara37nombre FROM cara37discapacidades WHERE cara37id='.$i_cara01discv2sensorial.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01discv2sensorial[$i_cara01discv2sensorial]=str_replace($cSepara, $cComplementa, $filae['cara37nombre']);
					}else{
					$acara01discv2sensorial[$i_cara01discv2sensorial]='';
					}
				}
			$lin_cara01discv2sensorial=$cSepara.utf8_decode($acara01discv2sensorial[$i_cara01discv2sensorial]);
			$i_cara02discv2intelectura=$fila['cara02discv2intelectura'];
			if (isset($acara02discv2intelectura[$i_cara02discv2intelectura])==0){
				$sSQL='SELECT cara37nombre FROM cara37discapacidades WHERE cara37id='.$i_cara02discv2intelectura.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara02discv2intelectura[$i_cara02discv2intelectura]=str_replace($cSepara, $cComplementa, $filae['cara37nombre']);
					}else{
					$acara02discv2intelectura[$i_cara02discv2intelectura]='';
					}
				}
			$lin_cara02discv2intelectura=$cSepara.utf8_decode($acara02discv2intelectura[$i_cara02discv2intelectura]);
			$i_cara02discv2fisica=$fila['cara02discv2fisica'];
			if (isset($acara02discv2fisica[$i_cara02discv2fisica])==0){
				$sSQL='SELECT cara37nombre FROM cara37discapacidades WHERE cara37id='.$i_cara02discv2fisica.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara02discv2fisica[$i_cara02discv2fisica]=str_replace($cSepara, $cComplementa, $filae['cara37nombre']);
					}else{
					$acara02discv2fisica[$i_cara02discv2fisica]='';
					}
				}
			$lin_cara02discv2fisica=$cSepara.utf8_decode($acara02discv2fisica[$i_cara02discv2fisica]);
			$i_cara02discv2psico=$fila['cara02discv2psico'];
			if (isset($acara02discv2psico[$i_cara02discv2psico])==0){
				$sSQL='SELECT cara37nombre FROM cara37discapacidades WHERE cara37id='.$i_cara02discv2psico.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara02discv2psico[$i_cara02discv2psico]=str_replace($cSepara, $cComplementa, $filae['cara37nombre']);
					}else{
					$acara02discv2psico[$i_cara02discv2psico]='';
					}
				}
			$lin_cara02discv2psico=$cSepara.utf8_decode($acara02discv2psico[$i_cara02discv2psico]);
			$lin_cara02discv2sistemica=$cSepara.'['.$fila['cara02discv2sistemica'].']';
			if (isset($acara02discv2sistemica[$fila['cara02discv2sistemica']])!=0){
				$lin_cara02discv2sistemica=$cSepara.utf8_decode($acara02discv2sistemica[$fila['cara02discv2sistemica']]);
				}
			$lin_cara02discv2sistemicaotro=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara02discv2sistemicaotro']));
			$lin_cara02discv2multiple=$cSepara.'['.$fila['cara02discv2multiple'].']';
			if (isset($acara02discv2multiple[$fila['cara02discv2multiple']])!=0){
				$lin_cara02discv2multiple=$cSepara.utf8_decode($acara02discv2multiple[$fila['cara02discv2multiple']]);
				}
			$lin_cara02discv2multipleotro=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara02discv2multipleotro']));

			if ($fila['cara01discv2archivoorigen']!=0){
				$lin_cara01discv2archivoorigen=$cSepara.'Si';
				}

			$i_cara02talentoexcepcional=$fila['cara02talentoexcepcional'];
			if (isset($acara02talentoexcepcional[$i_cara02talentoexcepcional])==0){
				$sSQL='SELECT cara38nombre FROM cara38talentos WHERE cara38id='.$i_cara02talentoexcepcional.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara02talentoexcepcional[$i_cara02talentoexcepcional]=str_replace($cSepara, $cComplementa, $filae['cara38nombre']);
					}else{
					$acara02talentoexcepcional[$i_cara02talentoexcepcional]='';
					}
				}
			//$lin_cara02talentoexcepcional=$cSepara.utf8_decode($acara02talentoexcepcional[$i_cara02talentoexcepcional]);
			$lin_cara01discv2tiene=$cSepara.'['.$fila['cara01discv2tiene'].']';
			if (isset($acara01discv2tiene[$fila['cara01discv2tiene']])!=0){
				$lin_cara01discv2tiene=$cSepara.utf8_decode($acara01discv2tiene[$fila['cara01discv2tiene']]);
				}
			$lin_cara01discv2trastornos=$cSepara.'No';
			if ($fila['cara01discv2trastornos']!=0){
				$lin_cara01discv2trastornos=$cSepara.'Si';
		$i_cara01discv2trastaprende=$fila['cara01discv2trastaprende'];
		if (isset($acara01discv2trastaprende[$i_cara01discv2trastaprende])==0){
			$sSQL='SELECT cara37nombre FROM cara37discapacidades WHERE cara37id='.$i_cara01discv2trastaprende.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acara01discv2trastaprende[$i_cara01discv2trastaprende]=str_replace($cSepara, $cComplementa, $filae['cara37nombre']);
				}else{
				$acara01discv2trastaprende[$i_cara01discv2trastaprende]='';
				}
			}
			$lin_cara01discv2trastaprende=$cSepara.utf8_decode($acara01discv2trastaprende[$i_cara01discv2trastaprende]);
			}
			$lin_cara01discv2contalento=$cSepara.'No';
			if ($fila['cara01discv2contalento']!=0){
				$lin_cara01discv2contalento=$cSepara.'Si';
				}
			$lin_cara01discv2condicionmedica=$cSepara.'No';
			if ($fila['cara01discv2condicionmedica']!=0){
				$lin_cara01discv2condicionmedica=$cSepara.'Si';
				$lin_cara01discv2condmeddet=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01discv2condmeddet']));
				}
			$lin_cara01discv2pruebacoeficiente=$cSepara.'['.$fila['cara01discv2pruebacoeficiente'].']';
			if (isset($acara01discv2pruebacoeficiente[$fila['cara01discv2pruebacoeficiente']])!=0){
				$lin_cara01discv2pruebacoeficiente=$cSepara.utf8_decode($acara01discv2pruebacoeficiente[$fila['cara01discv2pruebacoeficiente']]);
				}
			}
		$lin_cara01periodo=$cSepara.'';
		if ($fila['cara01idperaca']!=0){
			$idPeriodoEnc=$fila['cara01idperaca'];
			if (isset($aPeriodo[$idPeriodoEnc])==0){
				list($sNomPeriodo, $sDebugP)=f146_TituloPeriodo($idPeriodoEnc, $objDB);
				$aPeriodo[$idPeriodoEnc]=$sNomPeriodo;
				}
			$lin_cara01periodo=$cSepara.$aPeriodo[$idPeriodoEnc];
			}
		$sBloque1=$lin_cara01idzona.$lin_cara01idcead.$lin_cara01idescuela.$lin_cara01idprograma.$lin_cara01idtercero.$lin_cara01discversion.$lin_cara01fechaconfirmadisc;
		$sBloque2='';
		$sBloque3='';
		if ($bConVersion1){
			$sBloque2=$lin_cara01discsensorial.$lin_cara01discfisica.$lin_cara01disccognitiva.$lin_cara01perayuda.$lin_cara01perotraayuda;
			}
		if ($bConVersion2){
			$sBloque3=$lin_cara01perayuda.$lin_cara01perotraayuda.$lin_cara01discv2sensorial.$lin_cara02discv2intelectura.$lin_cara02discv2fisica.$lin_cara02discv2psico.$lin_cara02discv2sistemica
			.$lin_cara02discv2sistemicaotro.$lin_cara02discv2multiple.$lin_cara02discv2multipleotro.$lin_cara01discv2archivoorigen
			.$lin_cara01discv2trastornos.$lin_cara01discv2trastaprende.$lin_cara01discv2contalento.$lin_cara01discv2pruebacoeficiente.$lin_cara01discv2condicionmedica.$lin_cara01discv2condmeddet;
			//.$lin_cara02talentoexcepcional;
			}
		$sBloque4=$lin_cara01periodo;
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