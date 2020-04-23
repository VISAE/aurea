<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.23.8 Wednesday, October 30, 2019
*/
/** Archivo para reportes tipo csv 2333.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Wednesday, October 30, 2019
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
$bEntra=true;
$bDebug=false;
if (isset($_REQUEST['clave'])==0){$_REQUEST['clave']='';}
if (isset($_REQUEST['v3'])==0){$_REQUEST['v3']='';}
if (isset($_REQUEST['v4'])==0){$_REQUEST['v4']='';}
if (isset($_REQUEST['v5'])==0){$_REQUEST['v5']='';}
if (isset($_REQUEST['v6'])==0){$_REQUEST['v6']='';}
if (isset($_REQUEST['v7'])==0){$_REQUEST['v7']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
$idPeraca=numeros_validar($_REQUEST['v3']);
if ($idPeraca==''){$sError='No ha ingresado un periodo';}
if ($sError!=''){$bEntra=false;}
function f1011_InfoParaPlano($iTer, $objDB){
	$sTD='';
	$sDoc='';
	$sRazonSocial='';
	$iUltimoAcceso=0;
	$sSQL='SELECT unad11tipodoc, unad11doc, unad11razonsocial, unad11fechaultingreso FROM unad11terceros WHERE unad11id='.$iTer.'';
	$tabla11=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla11)>0){
		$fila11=$objDB->sf($tabla11);
		$sTD=$fila11['unad11tipodoc'];
		$sDoc=$fila11['unad11doc'];
		$sRazonSocial=$fila11['unad11razonsocial'];
		$iUltimoAcceso=$fila11['unad11fechaultingreso'];
		}
	return array($sTD, $sDoc, $sRazonSocial, $iUltimoAcceso);
	}
function f233_NombreRiesgo($iValor){
	$sValor='';
	switch($iValor){
		case 1:
		$sValor='Sin Riesgo';
		break;
		case 2:
		$sValor='Bajo';
		break;
		case 3:
		$sValor='Alto';
		break;
		default:
		$sValor='Sin Definir';
		break;
		}
	return $sValor;
	}
function f233_VacioNoSi($iValor){
	$sValor='';
	switch($iValor){
		case '-1':
		$sValor='';
		break;
		case 0:
		$sValor='No';
		break;
		case 1:
		$sValor='Si';
		break;
		default:
		$sValor='Sin Definir';
		break;
		}
	return $sValor;
	}
function f233_AsisteInduccion($iValor){
    $sValor='';
    switch($iValor){
        case -1:
            $sValor='';
            break;
        case 1:
            $sValor='Presencial';
            break;
        case 2:
            $sValor='Virtual';
            break;
        default:
            $sValor='No';
            break;
        }
    return $sValor;
    }
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
	$mensajes_2333='lg/lg_2333_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2333)){$mensajes_2333='lg/lg_2333_es.php';}
	require $mensajes_todas;
	require $mensajes_2333;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t2333.csv';
	$sTituloRpt='Consolidado_acompanamentos';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Consolidado de acompañamientos');
	$objplano->AdicionarLinea($sDato);
	$sDato='Periodo '.$_REQUEST['v3'];
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	if ($_REQUEST['v5']!=''){
		$sDato='Centro: '.$_REQUEST['v5'];
		}else{
		if ($_REQUEST['v4']!=''){
			$sDato='Zona: '.$_REQUEST['v4'];
			}
		}
	if ($sDato<>''){
		$objplano->AdicionarLinea($sDato);
		}
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$acara01idescuela=array();
	$acara33idperaca=array();
	$acara33idzona=array();
	$acara33idcentro=array();
	$acara23contacto_forma=array();
    $acara01idcursocatedra=array();
    $acara23catedra_avance=array();
    $acara23catedra_acciones=array();
	$aSys11=array();
	$sTitulos1='Datos de la encuesta';
	for ($l=1;$l<=8;$l++){
		$sTitulos1=$sTitulos1.$cSepara;
		}
	$sTitulos2='Acompañamiento inicial';
	for ($l=1;$l<=25;$l++){
		$sTitulos2=$sTitulos2.$cSepara;
		}
	$sTitulos3='Acompañamiento Intermedio';
	for ($l=1;$l<=5;$l++){
		$sTitulos3=$sTitulos3.$cSepara;
		}
	$sTitulos4='Acompañamiento Final';
	for ($l=1;$l<=5;$l++){
		$sTitulos4=$sTitulos4.$cSepara;
		}
	$sTitulos9='Riesgo'.$cSepara.'Consejero';
	$sBloque1='Periodo encuesta'.$cSepara.'Tipo doc'.$cSepara.'Documento'.$cSepara.'Nombres'.$cSepara.'Zona'.$cSepara.'Centro'.$cSepara.'Escuela'.$cSepara.'Programa';
	$sBloque2=$cSepara.'Num'.$cSepara.'Fecha'.$cSepara.'Estado'.$cSepara.'Asiste a Induccion'.$cSepara.'Asiste a Inmersion CV'.$cSepara.'Codigo de la Catedra'.$cSepara.'Situacion de Riesgo'.$cSepara.'Acciones Realizadas'.$cSepara.'Resultados'.$cSepara.'Nivel De Riesgo Prueba De Caracterización'.$cSepara.'Acciones De Intervencion Segun Factores'.$cSepara.'Competencias Digitales Basicas'.$cSepara.'Competencias Cuantitativas'.$cSepara.'Competencias Lecto-escritora'.$cSepara.'Competencias Ingles'.$cSepara.'Nivel De Riesgo Competencias Basicas'.$cSepara.'Competencias Digitales Basicas'.$cSepara.'Competencias Cuantitativas'.$cSepara.'Competencias Lecto-escritora'.$cSepara.'Competencias Ingles'.$cSepara.'Riesgo Final'.$cSepara.'Forma de contacto'.$cSepara.'Observaciones'.$cSepara.'Aplaza'.$cSepara.'Se retira';
	$sBloque3=$cSepara.'Num'.$cSepara.'Fecha'.$cSepara.'Estado'.$cSepara.''.$cSepara;
	$sBloque4=$cSepara.'Num'.$cSepara.'Fecha'.$cSepara.'Estado'.$cSepara.''.$cSepara;
	$sBloque9=$cSepara.'Riesgo'.$cSepara.'Consejero';
	$objplano->AdicionarLinea(utf8_decode($sTitulos1.$sTitulos2.$sTitulos3.$sTitulos4.$sTitulos9));
	$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3.$sBloque4.$sBloque9);
	$sWhere='';
	if ($_REQUEST['v5']!=''){
		$sWhere=$sWhere.' AND TB.cara01idcead='.$_REQUEST['v5'].' ';
		}else{
		if ($_REQUEST['v4']!=''){
			$sWhere=$sWhere.' AND TB.cara01idzona='.$_REQUEST['v4'].' ';
			}
		}
	$sSQL='SELECT TB.* 
FROM cara01encuesta AS TB 
WHERE TB.cara01idperiodoacompana='.$_REQUEST['v3'].''.$sWhere.'';
	if ($bDebug){$objplano->adlinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$lin_cara01idtercero=$cSepara.$cSepara.$cSepara;
		$lin_cara33idperaca=$cSepara;
		$lin_cara33idzona=$cSepara;
		$lin_cara33idcentro=$cSepara;
		$lin_cara01idescuela=$cSepara;
		$lin_cara01idprograma=$cSepara;
		$lin_cara01idconsejero=$cSepara;
		$lin_cara23contacto_forma=$cSepara;
		$lin_cara23contacto_observa=$cSepara;
		$lin_cara23aplaza=$cSepara;
		$lin_cara23seretira=$cSepara;
		$lin_cara23asisteinduccion=$cSepara;
		$lin_cara23asisteinmersioncv=$cSepara;
        $lin_cara01idcursocatedra=$cSepara;
        $lin_cara23catedra_avance=$cSepara;
        $lin_cara23catedra_acciones=$cSepara;
		$iTer=$fila['cara01idtercero'];
		if (isset($aSys11[$iTer]['doc'])==0){
			list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing'])=f1011_InfoParaPlano($iTer, $objDB);
			}
		$lin_cara01idtercero=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$i_cara33idperaca=$fila['cara01idperaca'];
		if (isset($acara33idperaca[$i_cara33idperaca])==0){
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$i_cara33idperaca.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acara33idperaca[$i_cara33idperaca]=str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
				}else{
				$acara33idperaca[$i_cara33idperaca]='';
				}
			}
		$lin_cara33idperaca=utf8_decode($acara33idperaca[$i_cara33idperaca]);
		$i_cara33idzona=$fila['cara01idzona'];
		if (isset($acara33idzona[$i_cara33idzona])==0){
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_cara33idzona.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acara33idzona[$i_cara33idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
				}else{
				$acara33idzona[$i_cara33idzona]='';
				}
			}
		$lin_cara33idzona=$cSepara.utf8_decode($acara33idzona[$i_cara33idzona]);
		$i_cara33idcentro=$fila['cara01idcead'];
		if (isset($acara33idcentro[$i_cara33idcentro])==0){
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_cara33idcentro.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acara33idcentro[$i_cara33idcentro]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$acara33idcentro[$i_cara33idcentro]='';
				}
			}
		$lin_cara33idcentro=$cSepara.utf8_decode($acara33idcentro[$i_cara33idcentro]);
		$i_cara01idescuela=$fila['cara01idescuela'];
		if (isset($acara01idescuela[$i_cara01idescuela])==0){
			$sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$i_cara01idescuela.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acara01idescuela[$i_cara01idescuela]=str_replace($cSepara, $cComplementa, $filae['core12nombre']);
				}else{
				$acara01idescuela[$i_cara01idescuela]='';
				}
			}
		$lin_cara01idescuela=$cSepara.utf8_decode($acara01idescuela[$i_cara01idescuela]);
			$i_cara01idprograma=$fila['cara01idprograma'];
			if (isset($acara01idprograma[$i_cara01idprograma])==0){
				$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$i_cara01idprograma.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01idprograma[$i_cara01idprograma]=str_replace($cSepara, $cComplementa, $filae['core09nombre']);
					}else{
					$acara01idprograma[$i_cara01idprograma]='['.$i_cara01idprograma.']';
					}
				}
			$lin_cara01idprograma=$cSepara.utf8_decode($acara01idprograma[$i_cara01idprograma]);
			if ($fila['cara01idconsejero']==0){
				$lin_cara01idconsejero=$cSepara.'Sin asignar';
				}else{
				$iTer=$fila['cara01idconsejero'];
				if (isset($aSys11[$iTer]['doc'])==0){
					list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing'])=f1011_InfoParaPlano($iTer, $objDB);
					}
				$lin_cara01idconsejero=$cSepara.utf8_decode($aSys11[$iTer]['razon']);
				}
		$lin_cara01factorriesgoacomp=$cSepara.f233_NombreRiesgo($fila['cara01factorriesgoacomp']);
		$sBloque1=$lin_cara33idperaca.$lin_cara01idtercero.$lin_cara33idzona.$lin_cara33idcentro.$lin_cara01idescuela.$lin_cara01idprograma;
		$sBloque9=$lin_cara01factorriesgoacomp.$lin_cara01idconsejero;
		//Bloques 2 a 4 se cargan dinamicamente.
		$sNada=$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.'';
		$iNS1=0;
		$iNS2=0;
		$iNS3=0;
		$aNS1=array();
		$aNS2=array();
		$aNS3=array();
		$sSQL='SELECT * FROM cara23acompanamento WHERE cara23idencuesta='.$fila['cara01id'].' ORDER BY cara23idtipo, cara23consec';
		$tabla23=$objDB->ejecutasql($sSQL);
		while ($fila23=$objDB->sf($tabla23)){
			$sEstado='Borrador';
			if ($fila23['cara23estado']==7){$sEstado='Completo';}
			//$cSepara.$fila23['cara23consec'].
			$sLinea=$cSepara.$fila23['cara23fecha'].$cSepara.$sEstado;
			switch($fila23['cara23idtipo']){
				case 1: // Inicial
				$iNS1++;
				//Los campos de esa linea.
		$i_cara23contacto_forma=$fila23['cara23contacto_forma'];
		if (isset($acara23contacto_forma[$i_cara23contacto_forma])==0){
			$sSQL='SELECT cara27titulo FROM cara27mediocont WHERE cara27id='.$i_cara23contacto_forma.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acara23contacto_forma[$i_cara23contacto_forma]=str_replace($cSepara, $cComplementa, $filae['cara27titulo']);
				}else{
				$acara23contacto_forma[$i_cara23contacto_forma]='';
				}
			}
		$i_cara01idcursocatedra=$fila['cara01idcursocatedra'];
		if (isset($acara01idcursocatedra[$i_cara01idcursocatedra])==0){
            $sSQL='SELECT unad40id AS id, CONCAT(unad40titulo, " - ", unad40nombre) AS nombre FROM unad40curso WHERE unad40id IN ('.$i_cara01idcursocatedra.') ORDER BY unad40titulo';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
                $acara01idcursocatedra[$i_cara01idcursocatedra]=str_replace($cSepara, $cComplementa, $filae['nombre']);
				}else{
                $acara01idcursocatedra[$i_cara01idcursocatedra]='';
				}
			}
		$i_cara23catedra_avance=$fila23['cara23catedra_avance'];
		if (isset($acara23catedra_avance[$i_cara23catedra_avance])==0){
            $sSQL='SELECT cara24titulo FROM cara24avancecatedra WHERE cara24id='.$i_cara23catedra_avance.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
                $acara23catedra_avance[$i_cara23catedra_avance]=str_replace($cSepara, $cComplementa, $filae['cara24titulo']);
				}else{
                $acara23catedra_avance[$i_cara23catedra_avance]='';
				}
			}
		$i_cara23catedra_acciones=$fila23['cara23catedra_acciones'];
		if (isset($acara23catedra_acciones[$i_cara23catedra_acciones])==0){
            $sSQL='SELECT cara24titulo FROM cara24avancecatedra WHERE cara24id='.$i_cara23catedra_avance.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
                $acara23catedra_acciones[$i_cara23catedra_acciones]=str_replace($cSepara, $cComplementa, $filae['nombre']);
				}else{
                $acara23catedra_acciones[$i_cara23catedra_acciones]='';
				}
			}
		$lin_cara23contacto_forma=$cSepara.utf8_decode($acara23contacto_forma[$i_cara23contacto_forma]);
		$lin_cara23contacto_observa=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila23['cara23contacto_observa']), ' '));
		$lin_cara23aplaza=$cSepara.f233_VacioNoSi($fila23['cara23aplaza']);
		$lin_cara23seretira=$cSepara.f233_VacioNoSi($fila23['cara23seretira']);
		$lin_cara23asisteinduccion=$cSepara.f233_AsisteInduccion($fila23['cara23asisteinduccion']);
		$lin_cara23asisteinmersioncv=$cSepara.f233_VacioNoSi($fila23['cara23asisteinmersioncv']);
		$lin_cara01idcursocatedra=$cSepara.utf8_decode($acara01idcursocatedra[$i_cara01idcursocatedra]);
		$lin_cara23catedra_avance=$cSepara.utf8_decode($acara23catedra_avance[$i_cara23catedra_avance]);
		$lin_cara23catedra_acciones=$cSepara.utf8_decode($acara23catedra_acciones[$i_cara23catedra_acciones]);
				$sLinea=$cSepara.$iNS1.$sLinea.$lin_cara23asisteinduccion.$lin_cara23asisteinmersioncv.$lin_cara01idcursocatedra.$lin_cara23catedra_avance.$lin_cara23catedra_acciones.$lin_cara23contacto_forma.$lin_cara23contacto_observa.$lin_cara23aplaza.$lin_cara23seretira;
				$aNS1[$iNS1]=$sLinea;
				break;
				case 2: // Intermedio
				$iNS2++;
				$sLinea=$cSepara.$iNS2.$sLinea.$cSepara.$cSepara;
				$aNS2[$iNS2]=$sLinea;
				break;
				case 3: // Final
				$iNS3++;
				$sLinea=$cSepara.$iNS3.$sLinea.$cSepara.$cSepara;
				$aNS3[$iNS3]=$sLinea;
				break;
				}
			}
		$iFilas=$iNS1;
		if ($iNS2>$iFilas){$iFilas=$iNS2;}
		if ($iNS3>$iFilas){$iFilas=$iNS3;}
		if ($iFilas==0){$iFilas=1;}
		for($k=1;$k<=$iFilas;$k++){
			$sEtiqueta='No registra';
			if ($k>1){$sEtiqueta='NA';}
			$sBloque2=$cSepara.$sEtiqueta.$sNada.$cSepara.$cSepara;
			$sBloque3=$cSepara.$sEtiqueta.$sNada;
			$sBloque4=$cSepara.$sEtiqueta.$sNada;
			if ($iNS1>=$k){$sBloque2=$aNS1[$k];}
			if ($iNS2>=$k){$sBloque3=$aNS2[$k];}
			if ($iNS3>=$k){$sBloque4=$aNS3[$k];}
			$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3.$sBloque4.$sBloque9);
			}
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