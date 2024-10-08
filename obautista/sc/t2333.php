<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.23.8 Wednesday, October 30, 2019
--- Modelo Versión 2.26.3b martes, 29 de junio de 2021 //se toma commo tabla base la matricula, no la caracterizacion.
*/
/** Archivo para reportes tipo csv 2333.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
* @date Wednesday, October 30, 2019
 *
 * Cambios 20 de mayo de 2020
 * 1. Agrega a consulta acompañamiento por Escuela y Programa
 * Omar Augusto Bautista Mora - UNAD - 2020
 * omar.bautista@unad.edu.co
*/
if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalación</b><br>No se ha establecido un archivo de configuración, por favor comuniquese con el administrador del sistema.';
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
if (isset($_REQUEST['v8'])==0){$_REQUEST['v8']='';}
if (isset($_REQUEST['v9'])==0){$_REQUEST['v9']='';}
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
function f233_NivelCompetencia($iValor){
    $sValor='';
    switch($iValor){
        case -1:
        case 2:
        case 4:
            $sValor='';
            break;
        case 1:
            $sValor='Alta';
            break;
        case 3:
            $sValor='Media';
            break;
        case 5:
            $sValor='Baja';
            break;
        default:
            $sValor='Sin definir';
            break;
        }
    return $sValor;
    }
function f233_PorDefinirNoSi($iValor){
	$sValor='';
	switch($iValor){
		case '-1':
		$sValor='Por definir';
		break;
		case 0:
		$sValor='No';
		break;
		case 1:
		$sValor='Si';
		break;
		}
	return $sValor;
	}
function f233_FactorRiesgo($aFila, $ETI, $cSepara){
    $r1='';
	$r2='';
	$r3='';
	$r4='';
	$iPuntajeSD=0;
	$iPuntajePsico=0;
	$iPuntajeAca=0;
	$iPuntajeEco=0;
	//Variables de igualacion, las validaciones tienen la misma estructura.
	$aCampo=array('cara01zonares', 'cara01fam_dependeecon', 'cara01inpecrecluso', 'cara01indigenas', 'cara01discsensorial', 'cara01discfisica', 'cara01disccognitiva');
	$aValor=array('R', 'S', 'S', 0, 'N', 'N', 'N');
	$aEti=array('msg_r1zona', 'msg_r1depende', 'msg_r1recluso', 'msg_r1indigena', 'msg_r1discsen', 'msg_r1discfis', 'msg_r1disccog');
	$aSuma=array(5, 3, 2, 5, 3, 3, 4);
	$aTipo=array(0,0,0,1,1,1,1);
	$iCampos=7;
	for ($k=0;$k<$iCampos;$k++){
		$bEsta=false;
		if ($aTipo[$k]==0){
			if ($aFila[$aCampo[$k]]==$aValor[$k]){$bEsta=true;}
			}else{
			if ($aFila[$aCampo[$k]]!=$aValor[$k]){$bEsta=true;}
			}
		if ($bEsta){
			$r1=$r1.$ETI[$aEti[$k]].' ('.$aSuma[$k].') ';
			$iPuntajeSD=$iPuntajeSD+$aSuma[$k];
			}
		}
	if ($aFila['cara01victimadesplazado']=='S'){
		if ($aFila['cara01idconfirmadesp']!=0){
			$r1=$r1.$ETI['msg_r1desplazado'].' ';
			$iPuntajeSD=$iPuntajeSD+3;
			}else{
			$r1=$r1.'('.$ETI['msg_r1desplazado'].') ';
			}
		}
	if ($aFila['cara01victimaacr']=='S'){
		if ($aFila['cara01idconfirmacr']!=0){
			$r1=$r1.$ETI['msg_r1acr'].' ';
			$iPuntajeSD=$iPuntajeSD+3;
			}else{
			$r1=$r1.'('.$ETI['msg_r1acr'].') ';
			}
		}
	//Ahora vamos por los psilogicos // Aunque... ya estan en cara01psico_puntaje
	$aCampo=array('cara01acad_tiemposinest', 'cara01acad_primeraopc');
	$aValor=array(6, 'N');
	$aEti=array('msg_r3tiemponoest', 'msg_r3otroprog');
	$aSuma=array(5, 5);
	$aTipo=array(0,0);
	$iCampos=0;
	for ($k=0;$k<$iCampos;$k++){
		$bEsta=false;
		if ($aTipo[$k]==0){
			if ($aFila[$aCampo[$k]]==$aValor[$k]){$bEsta=true;}
			}else{
			if ($aFila[$aCampo[$k]]!=$aValor[$k]){$bEsta=true;}
			}
		if ($bEsta){
			$r2=$r2.$ETI[$aEti[$k]].' ('.$aSuma[$k].') ';
			$iPuntajePsico=$iPuntajePsico+$aSuma[$k];
			}
		}
	//Ahora los academicos (El internet y la electricidad se validan doble vez)
	$aCampo=array('cara01acad_tiemposinest', 'cara01acad_primeraopc', 'cara01campus_energia', 'cara01campus_energia', 'cara01campus_internetreside', 'cara01campus_internetreside', 'cara01campus_ofimatica', 'cara01campus_usocorreo');
	$aValor=array(6, 'N', 2, 3, 2, 3, 'N', 4);
	$aEti=array('msg_r3tiemponoest', 'msg_r3otroprog', 'msg_r3energia', 'msg_r3energia', 'msg_r3internet', 'msg_r3internet', 'msg_r3ofimatica', 'msg_r3nocorreo');
	$aSuma=array(5, 5, 5, 5, 5, 5, 3, 3);
	$aTipo=array(0, 0, 0, 0, 0, 0, 0, 0);
	$iCampos=8;
	for ($k=0;$k<$iCampos;$k++){
		$bEsta=false;
		if ($aTipo[$k]==0){
			if ($aFila[$aCampo[$k]]==$aValor[$k]){$bEsta=true;}
			}else{
			if ($aFila[$aCampo[$k]]!=$aValor[$k]){$bEsta=true;}
			}
		if ($bEsta){
			$r3=$r3.$ETI[$aEti[$k]].' ('.$aSuma[$k].') ';
			$iPuntajeAca=$iPuntajeAca+$aSuma[$k];
			}
		}
	if (($aFila['cara01campus_compescrito']=='N')&&($aFila['cara01campus_portatil']=='N')){
		if (($aFila['cara01campus_tableta']=='N')&&($aFila['cara01campus_telefono']=='N')){
			$r3=$r3.$ETI['msg_r3sinequipo'].' ';
			$iPuntajeAca=$iPuntajeAca+5;
			}else{
			$r3=$r3.$ETI['msg_r3sincomputador'].' ';
			$iPuntajeAca=$iPuntajeAca+4;
			}
		}
	//Los economicos
	$aCampo=array('cara01lab_situacion', 'cara01lab_rangoingreso', 'cara01lab_tiempoacadem');
	$aValor=array(4, 1, 1);
	$aEti=array('msg_r4desempleado', 'msg_r4menos1smm', 'msg_r4pocotiempo');
	$aSuma=array(3, 3, 3);
	$aTipo=array(0, 0, 0);
	$iCampos=3;
	for ($k=0;$k<$iCampos;$k++){
		$bEsta=false;
		if ($aTipo[$k]==0){
			if ($aFila[$aCampo[$k]]==$aValor[$k]){$bEsta=true;}
			}else{
			if ($aFila[$aCampo[$k]]!=$aValor[$k]){$bEsta=true;}
			}
		if ($bEsta){
			$r4=$r4.$ETI[$aEti[$k]].' ('.$aSuma[$k].') ';
			$iPuntajeEco=$iPuntajeEco+$aSuma[$k];
			}
		}
	//Ahora a calcular los factores.
	if ($iPuntajeSD>15){
		$sCpto=f233_NombreRiesgo(3);
		$sCierre='';
		}else{
		if ($iPuntajeSD>4){
			$sCpto=f233_NombreRiesgo(2);
			$sCierre='';
			}else{
			$sCpto=''.f233_NombreRiesgo(1);
			$sCierre='';
			}
		}
	$r1=$cSepara.$sCpto.' ['.$r1.']'.$sCierre;
	if ($iPuntajePsico>15){
		$sCpto=f233_NombreRiesgo(3);
		$sCierre='';
		}else{
		if ($iPuntajePsico>4){
			$sCpto=f233_NombreRiesgo(2);
			$sCierre='';
			}else{
			$sCpto=''.f233_NombreRiesgo(1);
			$sCierre='';
			}
		}
	$r2=$cSepara.$sCpto.' ['.$r2.']'.$sCierre;
	if ($iPuntajeAca>12){
		$sCpto=f233_NombreRiesgo(3);
		$sCierre='';
		}else{
		if ($iPuntajeAca>4){
			$sCpto=f233_NombreRiesgo(2);
			$sCierre='';
			}else{
			$sCpto=''.f233_NombreRiesgo(1);
			$sCierre='';
			}
		}
	$r3=$cSepara.$sCpto.' ['.$r3.']'.$sCierre;
	if ($iPuntajeEco>5){
		$sCpto=f233_NombreRiesgo(3);
		$sCierre='';
		}else{
		if ($iPuntajeEco>3){
			$sCpto=f233_NombreRiesgo(2);
			$sCierre='';
			}else{
			$sCpto=''.f233_NombreRiesgo(1);
			$sCierre='';
			}
		}
	$r4=$cSepara.$sCpto.' ['.$r4.']'.$sCierre;
	return array($r1, $r2, $r3, $r4);
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
	//Etiquetas para titulos.
	$sEti9='Remisión al chat del psicólogo en línea o proyecto de tele psicología.';
	$sEti10='Acompañamiento motivacional del consejero académico, exaltando su desempeño académico.';
	$sEti11='Acompañamiento motivacional del consejero para que mejore su rendimiento académico.';
	$sEti12='Acompañamiento del consejero en la metodología del trabajo académico (Administración del tiempo, hábitos de estudio, estrategias de aprendizajes Independiente y trabajo colaborativo)';
	$sEti13='Se fortalece en el estudiante la ruta de aprendizaje en las aulas virtuales, como parte de las franjas permanentes de Inmersión en Campus Virtual';
	$sEti14='Acompañamiento del consejero para fortalecer el manejo de herramientas digitales desde el e-PAPC o PAPC Extendido.';
	$sEti15='Remisión al centro para orientación personalizada.';
	$sEti16='Remisión a docentes para brindar apoyo, en mediación de tiempo para nivelar su proceso de aprendizaje y presentar sus actividades en los cursos.';
	$sEti17='Remisión a docentes para brindar apoyo, en mediación para mejora de productos de aprendizajes';
	$sEti18='Se orienta al estudiante a que consulte y participe en los espacios de acompañamiento académico sincrónico (web conferencia, Skype, CIPAS, agenda de acompañamiento docente) para resolver sus inquietudes en los cursos en los que presenta dificultades.';
	$sEti19='Se comparte con el estudiante la ruta para establecer comunicación con el docente (correo interno) para dar a conocer su situación y enviar actividad(es) pendiente(s) por entregar.';
	$sEti20='Se logra comunicación con el estudiante, informa que no continúa con el proceso académico. Se le invita a formalizar el proceso de retiro mediante comunicación formal al consejero académico y líder de programa.';
	$sEti21='Participa en el Seminario Itinerante de Educación a Distancia, convocado por el Sistema Nacional de Consejería Académica.';
	$sEti22='Se detecta falta de conectividad a Internet, por lo que se activa servicio de campus off.';
	$sEti23='No se realizó intervención, no hubo contacto efectivo con el estudiante';
		//
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
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=cadena_codificar('Consolidado de acompañamientos');
	$objplano->AdicionarLinea($sDato);
	$lin_exte02per_aca=$_REQUEST['v3'];
	$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$_REQUEST['v3'].'';
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($fila=$objDB->sf($tabla)){
        $lin_exte02per_aca=str_replace($cSepara, $cComplementa, $fila['exte02nombre']);
	    }
	$sDato='Periodo '.$lin_exte02per_aca;
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	if ($_REQUEST['v5']!=''){
	    $lin_unad24sede=$_REQUEST['v5'];
	    $sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$_REQUEST['v5'].'';
	    if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	    $tabla=$objDB->ejecutasql($sSQL);
	    if ($fila=$objDB->sf($tabla)){
	        $lin_unad24sede=str_replace($cSepara, $cComplementa, $fila['unad24nombre']);
	        }
		$sDato='Centro: '.$lin_unad24sede;
		}else{
		if ($_REQUEST['v4']!=''){
		    $lin_unad23zona=$_REQUEST['v4'];
            $sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$_REQUEST['v4'].'';
            if ($bDebug){$objplano->AdicionarLinea($sSQL);}
            $tabla=$objDB->ejecutasql($sSQL);
            if ($fila=$objDB->sf($tabla)){
                $lin_unad23zona=str_replace($cSepara, $cComplementa, $fila['unad23nombre']);
                }
			$sDato='Zona: '.$lin_unad23zona;
			}
		}
	if ($sDato<>''){
		$objplano->AdicionarLinea($sDato);
		}
	$sDato='';
	if ($_REQUEST['v7']!=''){
	    $lin_core09programa=$_REQUEST['v7'];
	    $sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$_REQUEST['v7'].'';
	    if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	    $tabla=$objDB->ejecutasql($sSQL);
	    if ($fila=$objDB->sf($tabla)){
            $lin_core09programa=str_replace($cSepara, $cComplementa, $fila['core09nombre']);
	        }
		$sDato='Programa: '.$lin_core09programa;
		}else{
		if ($_REQUEST['v6']!=''){
		    $lin_core12escuela=$_REQUEST['v6'];
            $sSQL='SELECT core12nombre FROM core12escuela WHERE core12id='.$_REQUEST['v6'].'';
            if ($bDebug){$objplano->AdicionarLinea($sSQL);}
            $tabla=$objDB->ejecutasql($sSQL);
            if ($fila=$objDB->sf($tabla)){
                $lin_core12escuela=str_replace($cSepara, $cComplementa, $fila['core12nombre']);
                }
			$sDato='Escuela: '.$lin_core12escuela;
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
    $acara23catedra_resultados=array();
    $acara01factorprincipaldesc=array();
    $acara01factorprincpermanencia=array();
	$aSys11=array();
	$sTitulos1='Datos de la encuesta';
	for ($l=1;$l<=8;$l++){
		$sTitulos1=$sTitulos1.$cSepara;
		}
	$sTitulos2='Acompañamiento inicial';
	for ($l=1;$l<=37;$l++){
		$sTitulos2=$sTitulos2.$cSepara;
		}
	$sTitulos3='Acompañamiento Intermedio';
	for ($l=1;$l<=42;$l++){
		$sTitulos3=$sTitulos3.$cSepara;
		}
	$sTitulos4='Acompañamiento Final';
	for ($l=1;$l<=14;$l++){
		$sTitulos4=$sTitulos4.$cSepara;
		}
	$sTitulos9='Riesgo'.$cSepara.'Consejero';
	$sBloque1='Periodo encuesta'.$cSepara.'Tipo doc'.$cSepara.'Documento'.$cSepara.'Nombres'.$cSepara.'Zona'.$cSepara.'Centro'.
	$cSepara.'Escuela'.$cSepara.'Programa';
	$sBloque2=$cSepara.'Num'.$cSepara.'Fecha'.$cSepara.'Estado'.$cSepara.'Asiste a Induccion'.$cSepara.'Asiste a Inmersion CV'.
		$cSepara.'Codigo de la Catedra'.$cSepara.'Situacion de Riesgo'.$cSepara.'Acciones Realizadas'.$cSepara.'Resultados'.
		$cSepara.'Nivel de Riesgo en Catedra'.$cSepara.'Factor socio-demográfico'.$cSepara.'Factor psicosocial'.
		$cSepara.'Factor académico'.$cSepara.'Factor socio económico'.$cSepara.'Factores externos'.$cSepara.'Factores internos'.
		$cSepara.'Nivel De Riesgo Prueba De Caracterización'.$cSepara.'Acciones Factor socio-demográfico'.
		$cSepara.'Acciones Factor psicosocial'.$cSepara.'Acciones Factor académico'.$cSepara.'Acciones Factor socio económico'.
		$cSepara.'Competencias Digitales Basicas'.$cSepara.'Competencias Cuantitativas'.$cSepara.'Competencias Lecto-escritora'.
		$cSepara.'Competencias Ingles'.$cSepara.'Nivel De Riesgo Competencias Basicas'.$cSepara.'Participacion En Taller De Competencias Digitales Basicas'.
		$cSepara.'Participacion En Taller De Competencias Cuantitativas'.$cSepara.'Participacion En Taller De Competencias Lecto-escritora'.
		$cSepara.'Participacion En Taller De Competencias Ingles'.$cSepara.'Riesgo Acumulado Momento Inicial'.
		$cSepara.'Forma de contacto'.$cSepara.'Observaciones'.$cSepara.'Aplaza'.$cSepara.'Se retira'.$cSepara.'Factor principal de deserción'.$cSepara;
	$sBloque3=$cSepara.'Num'.$cSepara.'Fecha'.$cSepara.'Estado'.$cSepara.'Riesgos en catedra'.$cSepara.'Acciones realizadas'.
		$cSepara.'Resultados'.$cSepara.'Nivel de Riesgo en Catedra'.$cSepara.'Seguimiento Alerta Anterior'.
		$cSepara.'No. De cursos matriculados'.$cSepara.'No. De cursos sin ingresos'.$cSepara.'No. de Cursos parcialmente reprobados'.
		$cSepara.'% de cursos con riesgo'.$cSepara.'Comunicación con docente'.$cSepara.'Conducto regular'.
		$cSepara.'Revisión de calificaciones'.$cSepara.'Mensaje felicitación'.$cSepara.'Comunicación con e-monitor'.
		$cSepara.'Direccionamiento chat VISAE'.$cSepara.'Contacto con consejero del centro'.
		$cSepara.$sEti9.$cSepara.$sEti10.$cSepara.$sEti11.$cSepara.$sEti12.$cSepara.$sEti13.
		$cSepara.$sEti14.$cSepara.$sEti15.$cSepara.$sEti16.$cSepara.$sEti17.$cSepara.$sEti18.
		$cSepara.$sEti19.$cSepara.$sEti20.$cSepara.$sEti21.$cSepara.$sEti22.$cSepara.$sEti23.
		$cSepara.'Nivel de Riesgo en Cursos'.
		$cSepara.'Medio de contacto efectivo'.$cSepara.'Observaciones'.$cSepara.'Aplaza'.$cSepara.'Se retira'.
		$cSepara.'Factor principal de deserción'.$cSepara.'Riesgo Acumulado Momento Intermedio'.$cSepara;
		//$cSepara.'Nivel de riesgo por acceso a cursos'
	$sBloque4=$cSepara.'Num'.$cSepara.'Fecha'.$cSepara.'Estado'.$cSepara.'Aprobacion Catedra Unadista'.$cSepara.'Numero De Cursos Reprobados'.
		$cSepara.'% De Cursos Reprobados'.$cSepara.'Aplaza'.$cSepara.'Se Retira'.$cSepara.'Permanece'.$cSepara.'Factor Principal De Permanencia'.
		$cSepara.'Factor Principal De Desercion'.$cSepara.'Medio De Contacto Efectivo'.$cSepara.'Observaciones'.$cSepara;
	$sBloque9=$cSepara.'Riesgo'.$cSepara.'Consejero';
	$objplano->AdicionarLinea(cadena_codificar($sTitulos1.$sTitulos2.$sTitulos3.$sTitulos4.$sTitulos9));
	$objplano->AdicionarLinea(cadena_codificar($sBloque1.$sBloque2.$sBloque3.$sBloque4.$sBloque9));
    $idTercero=$_REQUEST['idtercero'];
	$sWhere='';
	$sCondi16='';
	if ($_REQUEST['v5']!=''){
		$sWhere=$sWhere.' AND TB.cara01idcead='.$_REQUEST['v5'].' ';
		$sCondi16=$sCondi16.' AND T16.core16idcead='.$_REQUEST['v5'].' ';
		$sTituloRpt=$sTituloRpt.'_c'.$_REQUEST['v5'].'';
		}else{
		if ($_REQUEST['v4']!=''){
			$sWhere=$sWhere.' AND TB.cara01idzona='.$_REQUEST['v4'].' ';
			$sCondi16=$sCondi16.' AND T16.core16idzona='.$_REQUEST['v4'].' ';
			$sTituloRpt=$sTituloRpt.'_z'.$_REQUEST['v4'].'';
			}
		}
	if ($_REQUEST['v7']!=''){
		$sWhere=$sWhere.' AND TB.cara01idprograma='.$_REQUEST['v7'].' ';
		$sCondi16=$sCondi16.' AND T16.core16idprograma='.$_REQUEST['v7'].' ';
		$sTituloRpt=$sTituloRpt.'_p'.$_REQUEST['v7'].'';
		}else{
		if ($_REQUEST['v6']!=''){
			$sWhere=$sWhere.' AND TB.cara01idescuela='.$_REQUEST['v6'].' ';
			$sCondi16=$sCondi16.' AND T16.core16idescuela='.$_REQUEST['v6'].' ';
			$sTituloRpt=$sTituloRpt.'_e'.$_REQUEST['v6'].'';
			}
		}
    if ($_REQUEST['v9']==1) {
        $sWhere=$sWhere.' AND TB.cara01idconsejero='.$idTercero.' ';
		$sCondi16=$sCondi16.' AND T16.core16idconsejero='.$idTercero.' ';
		$sTituloRpt=$sTituloRpt.'_con'.$idTercero.'';
		}else{
        if ($_REQUEST['v9']==2){
  			$sSQL='SELECT cara21idzona FROM cara21lidereszona WHERE cara21idlider='.$idTercero.'';
            $tabla=$objDB->ejecutasql($sSQL);
            if ($fila=$objDB->sf($tabla)){
                $sWhere=$sWhere.' AND TB.cara01idzona='.$fila['cara21idzona'].' ';
				$sCondi16=$sCondi16.' AND T16.core16idzona='.$fila['cara21idzona'].' ';
				$sTituloRpt=$sTituloRpt.'_z'.$fila['cara21idzona'].'';
				}
            }
        }
	$sId11='-99';
	for ($iEtapa=1;$iEtapa<=2;$iEtapa++){
		if ($iEtapa==1){
		$sSQL='SELECT TB.* 
		FROM cara01encuesta AS TB 
		WHERE TB.cara01idperiodoacompana='.$_REQUEST['v3'].''.$sWhere.'';
		}else{
		$sId01='-99';
		$sId16='-99';
		$sSQL='SELECT T16.core16id, T16.core16tercero
		FROM core16actamatricula AS T16
		WHERE T16.core16peraca='.$_REQUEST['v3'].''.$sCondi16.' AND T16.core16tercero NOT IN ('.$sId11.')';
		if ($bDebug){$objplano->AdicionarLinea('Matricula sin encuesta: '.$sSQL);}
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			//Ver si tiene acompañamientos en el periodo
			$sId16=$sId16.','.$fila['core16tercero'];
			}
		$sSQL='SELECT cara23idencuesta 
		FROM cara23acompanamento 
		WHERE cara23idperiodo='.$_REQUEST['v3'].' AND cara23idtercero IN ('.$sId16.') 
		GROUP BY cara23idencuesta';
		$tabla01=$objDB->ejecutasql($sSQL);
		while($fila01=$objDB->sf($tabla01)){
			$sId01=$sId01.','.$fila01['cara23idencuesta'];
			}
		/*
		//Ahora si la matricula que registra acompañamientos
		$sSQL='SELECT T16.core16tercero
		FROM core16actamatricula AS T16
		WHERE T16.core16id IN ('.$sId16.')';
		if ($bDebug){$objplano->AdicionarLinea('Matricula sin encuesta: '.$sSQL);}
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			//Buscar la ultima encuesta.
			$sSQL='SELECT cara01id 
			FROM cara01encuesta 
			WHERE cara01idtercero='.$fila['core16tercero'].' AND cara01idperaca<='.$_REQUEST['v3'].'
			ORDER BY cara01idperaca DESC';
			$tabla01=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla01)>0){
				$fila01=$objDB->sf($tabla01);
				$sId01=$sId01.','.$fila01['cara01id'];
				}
			}
		*/
		$sSQL='SELECT TB.* 
		FROM cara01encuesta AS TB 
		WHERE TB.cara01id IN ('.$sId01.')';
		}
	if ($bDebug){$objplano->AdicionarLinea($sSQL);}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		if ($iEtapa==1){
			$sId11=$sId11.','.$fila['cara01idtercero'];
			}
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
        $lin_cara23catedra_resultados=$cSepara;
        $lin_cara23catedra_criterio=$cSepara;
        $lin_cara01r1=$cSepara;
        $lin_cara01r2=$cSepara;
        $lin_cara01r3=$cSepara;
        $lin_cara01r4=$cSepara;
        $lin_cara23aler_externo=$cSepara;
        $lin_cara23aler_interno=$cSepara;
        $lin_cara23aler_criterio=$cSepara;
        $lin_cara23aler_sociodem=$cSepara;
        $lin_cara23aler_psico=$cSepara;
        $lin_cara23aler_academ=$cSepara;
        $lin_cara23aler_econom=$cSepara;
        $lin_cara23comp_digital=$cSepara;
        $lin_cara23comp_cuanti=$cSepara;
        $lin_cara23comp_lectora=$cSepara;
        $lin_cara23comp_ingles=$cSepara;
        $lin_cara23comp_criterio=$cSepara;
        $lin_cara23nivela_digital=$cSepara;
        $lin_cara23nivela_cuanti=$cSepara;
        $lin_cara23nivela_lecto=$cSepara;
        $lin_cara23nivela_ingles=$cSepara;
        $lin_cara23factorriesgo=$cSepara;
        $lin_cara01factorprincipaldesc=$cSepara;
        $lin_cara23catedra_segprev=$cSepara;
        $lin_cara23cursos_total=$cSepara;
        $lin_cara23cursos_siningre=$cSepara;
        $lin_cara23cursos_menor200=$cSepara;
        $lin_cara23cursos_porcperdida=$cSepara;
        $lin_cara23cursos_ac1=$cSepara;
        $lin_cara23cursos_ac2=$cSepara;
		$lin_cara23cursos_ac3=$cSepara;
		$lin_cara23cursos_ac4=$cSepara;
		$lin_cara23cursos_ac5=$cSepara;
		$lin_cara23cursos_ac6=$cSepara;
		$lin_cara23cursos_ac7=$cSepara;
		$lin_cara23cursos_ac9=$cSepara;
		$lin_cara23cursos_ac10=$cSepara;
		$lin_cara23cursos_ac11=$cSepara;
		$lin_cara23cursos_ac12=$cSepara;
		$lin_cara23cursos_ac13=$cSepara;
		$lin_cara23cursos_ac14=$cSepara;
		$lin_cara23cursos_ac15=$cSepara;
		$lin_cara23cursos_ac16=$cSepara;
		$lin_cara23cursos_ac17=$cSepara;
		$lin_cara23cursos_ac18=$cSepara;
		$lin_cara23cursos_ac19=$cSepara;
		$lin_cara23cursos_ac20=$cSepara;
		$lin_cara23cursos_ac21=$cSepara;
		$lin_cara23cursos_ac22=$cSepara;
		$lin_cara23cursos_ac23=$cSepara;
        $lin_cara23cursos_criterio=$cSepara;
        $lin_cara23catedra_aprueba=$cSepara;
        $lin_cara23permanece=$cSepara;
        $lin_cara01factorprincpermanencia=$cSepara;
		$iTer=$fila['cara01idtercero'];
		if (isset($aSys11[$iTer]['doc'])==0){
			list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing'])=f1011_InfoParaPlano($iTer, $objDB);
			}
		$lin_cara01idtercero=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.cadena_codificar($aSys11[$iTer]['razon']);
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
		$lin_cara33idperaca=cadena_codificar($acara33idperaca[$i_cara33idperaca]);
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
		$lin_cara33idzona=$cSepara.cadena_codificar($acara33idzona[$i_cara33idzona]);
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
		$lin_cara33idcentro=$cSepara.cadena_codificar($acara33idcentro[$i_cara33idcentro]);
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
		$lin_cara01idescuela=$cSepara.cadena_codificar($acara01idescuela[$i_cara01idescuela]);
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
			$lin_cara01idprograma=$cSepara.cadena_codificar($acara01idprograma[$i_cara01idprograma]);
			if ($fila['cara01idconsejero']==0){
				$lin_cara01idconsejero=$cSepara.'Sin asignar';
				}else{
				$iTer=$fila['cara01idconsejero'];
				if (isset($aSys11[$iTer]['doc'])==0){
					list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing'])=f1011_InfoParaPlano($iTer, $objDB);
					}
				$lin_cara01idconsejero=$cSepara.cadena_codificar($aSys11[$iTer]['razon']);
				}
		$lin_cara01factorriesgoacomp=$cSepara.f233_NombreRiesgo($fila['cara01factorriesgoacomp']);
		$sBloque1=$lin_cara33idperaca.$lin_cara01idtercero.$lin_cara33idzona.$lin_cara33idcentro.$lin_cara01idescuela.$lin_cara01idprograma;
		$sBloque9=$lin_cara01factorriesgoacomp.$lin_cara01idconsejero;
		//Bloques 2 a 4 se cargan dinamicamente.
		$sNada=$cSepara.$cSepara.$cSepara.$cSepara;
		$s5sep=$cSepara.$cSepara.$cSepara.$cSepara.$cSepara;
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
            $sSQL='SELECT cara25titulo FROM cara25accionescat WHERE cara25id='.$i_cara23catedra_acciones.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
                $acara23catedra_acciones[$i_cara23catedra_acciones]=str_replace($cSepara, $cComplementa, $filae['cara25titulo']);
				}else{
                $acara23catedra_acciones[$i_cara23catedra_acciones]='';
				}
			}
		$i_cara23catedra_resultados=$fila23['cara23catedra_resultados'];
		if (isset($acara23catedra_resultados[$i_cara23catedra_resultados])==0){
            $sSQL='SELECT cara26id AS id, cara26titulo AS nombre FROM cara26resultcat WHERE cara26id='.$i_cara23catedra_resultados.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
                $acara23catedra_resultados[$i_cara23catedra_resultados]=str_replace($cSepara, $cComplementa, $filae['nombre']);
				}else{
                $acara23catedra_resultados[$i_cara23catedra_resultados]='';
				}
			}
		$i_cara01factorprincipaldesc=$fila['cara01factorprincipaldesc'];
		if (isset($acara01factorprincipaldesc[$i_cara01factorprincipaldesc])==0){
            $sSQL='SELECT cara15id AS id, cara15nombre AS nombre FROM cara15factordeserta WHERE cara15id='.$i_cara01factorprincipaldesc.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
                $acara01factorprincipaldesc[$i_cara01factorprincipaldesc]=str_replace($cSepara, $cComplementa, $filae['nombre']);
				}else{
                $acara01factorprincipaldesc[$i_cara01factorprincipaldesc]='';
				}
			}
			switch($fila23['cara23idtipo']){
				case 1: // Inicial
				$iNS1++;
				//Los campos de esa linea.
		$i_cara01idcursocatedra=$fila['cara01idcursocatedra'];
		if (isset($acara01idcursocatedra[$i_cara01idcursocatedra])==0){
            $sSQL='SELECT unad40id AS id, CONCAT(unad40titulo, " - ", unad40nombre) AS nombre FROM unad40curso WHERE unad40id='.$i_cara01idcursocatedra.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
                $acara01idcursocatedra[$i_cara01idcursocatedra]=str_replace($cSepara, $cComplementa, $filae['nombre']);
				}else{
                $acara01idcursocatedra[$i_cara01idcursocatedra]='';
				}
			}
		$lin_cara23contacto_forma=$cSepara.cadena_codificar($acara23contacto_forma[$i_cara23contacto_forma]);
		$lin_cara23contacto_observa=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23contacto_observa']), ' '));
		$lin_cara23aplaza=$cSepara.f233_VacioNoSi($fila23['cara23aplaza']);
		$lin_cara23seretira=$cSepara.f233_VacioNoSi($fila23['cara23seretira']);
		$lin_cara23asisteinduccion=$cSepara.f233_AsisteInduccion($fila23['cara23asisteinduccion']);
		$lin_cara23asisteinmersioncv=$cSepara.f233_VacioNoSi($fila23['cara23asisteinmersioncv']);
		$lin_cara01idcursocatedra=$cSepara.cadena_codificar($acara01idcursocatedra[$i_cara01idcursocatedra]);
		$lin_cara23catedra_avance=$cSepara.cadena_codificar($acara23catedra_avance[$i_cara23catedra_avance]);
		$lin_cara23catedra_acciones=$cSepara.cadena_codificar($acara23catedra_acciones[$i_cara23catedra_acciones]);
		$lin_cara23catedra_resultados=$cSepara.cadena_codificar($acara23catedra_resultados[$i_cara23catedra_resultados]);
		$lin_cara23catedra_criterio=$cSepara.f233_NombreRiesgo($fila23['cara23catedra_criterio']);
		list($lin_cara01r1,$lin_cara01r2,$lin_cara01r3,$lin_cara01r4)=f233_FactorRiesgo($fila, $ETI, $cSepara);
		$lin_cara23aler_externo=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23aler_externo']), ' '));
		$lin_cara23aler_interno=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23aler_interno']), ' '));
		$lin_cara23aler_criterio=$cSepara.f233_NombreRiesgo($fila23['cara23aler_criterio']);
		$lin_cara23aler_sociodem=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23aler_sociodem']), ' '));
		$lin_cara23aler_psico=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23aler_psico']), ' '));
		$lin_cara23aler_academ=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23aler_academ']), ' '));
		$lin_cara23aler_econom=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23aler_econom']), ' '));
		$lin_cara23comp_digital=$cSepara.f233_NivelCompetencia($fila23['cara23comp_digital']);
		$lin_cara23comp_cuanti=$cSepara.f233_NivelCompetencia($fila23['cara23comp_cuanti']);
		$lin_cara23comp_lectora=$cSepara.f233_NivelCompetencia($fila23['cara23comp_lectora']);
		$lin_cara23comp_ingles=$cSepara.f233_NivelCompetencia($fila23['cara23comp_ingles']);
		$lin_cara23comp_criterio=$cSepara.f233_NombreRiesgo($fila23['cara23comp_criterio']);
		$lin_cara23nivela_digital=$cSepara.$fila23['cara23nivela_digital'];
		$lin_cara23nivela_cuanti=$cSepara.$fila23['cara23nivela_cuanti'];
		$lin_cara23nivela_lecto=$cSepara.$fila23['cara23nivela_lecto'];
		$lin_cara23nivela_ingles=$cSepara.$fila23['cara23nivela_ingles'];
		$lin_cara23factorriesgo=$cSepara.f233_NombreRiesgo($fila23['cara23factorriesgo']);
		$lin_cara01factorprincipaldesc=$cSepara.cadena_codificar($acara01factorprincipaldesc[$i_cara01factorprincipaldesc]);
				$sLinea=$cSepara.$iNS1.$sLinea.$lin_cara23asisteinduccion.$lin_cara23asisteinmersioncv.$lin_cara01idcursocatedra.
                    $lin_cara23catedra_avance.$lin_cara23catedra_acciones.$lin_cara23catedra_resultados.$lin_cara23catedra_criterio.$lin_cara01r1.$lin_cara01r2.$lin_cara01r3.
                    $lin_cara01r4.$lin_cara23aler_externo.$lin_cara23aler_interno.$lin_cara23aler_criterio.
                    $lin_cara23aler_sociodem.$lin_cara23aler_psico.$lin_cara23aler_academ.$lin_cara23aler_econom.$lin_cara23comp_digital.
                    $lin_cara23comp_cuanti.$lin_cara23comp_lectora.$lin_cara23comp_ingles.$lin_cara23comp_criterio.$lin_cara23nivela_digital.
                    $lin_cara23nivela_cuanti.$lin_cara23nivela_lecto.$lin_cara23nivela_ingles.$lin_cara23factorriesgo.
                    $lin_cara23contacto_forma.$lin_cara23contacto_observa.$lin_cara23aplaza.$lin_cara23seretira.$lin_cara01factorprincipaldesc.$cSepara;
				$aNS1[$iNS1]=$sLinea;
				break;
				case 2: // Intermedio
				$iNS2++;
				// Los campos de esa linea
				$lin_cara23catedra_avance=$cSepara.cadena_codificar($acara23catedra_avance[$i_cara23catedra_avance]);
				$lin_cara23catedra_acciones=$cSepara.cadena_codificar($acara23catedra_acciones[$i_cara23catedra_acciones]);
				$lin_cara23catedra_resultados=$cSepara.cadena_codificar($acara23catedra_resultados[$i_cara23catedra_resultados]);
				$lin_cara23catedra_criterio=$cSepara.f233_NombreRiesgo($fila23['cara23catedra_criterio']);
				$lin_cara23catedra_segprev=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23catedra_segprev']), ' '));
				$lin_cara23cursos_total=$cSepara.$fila23['cara23cursos_total'];
				$lin_cara23cursos_siningre=$cSepara.$fila23['cara23cursos_siningre'];
				$lin_cara23cursos_menor200=$cSepara.$fila23['cara23cursos_menor200'];
				$lin_cara23cursos_porcperdida=$cSepara.$fila23['cara23cursos_porcperdida'];
				if ($fila23['cara23version']==0){
					$lin_cara23cursos_ac1=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac1']);
					$lin_cara23cursos_ac2=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac2']);
					$lin_cara23cursos_ac3=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac3']);
					$lin_cara23cursos_ac4=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac4']);
					$lin_cara23cursos_ac5=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac5']);
					$lin_cara23cursos_ac6=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac6']);
					$lin_cara23cursos_ac7=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac7']);
					}
				if ($fila23['cara23version']==1){
					$lin_cara23cursos_ac9=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac9']);
					$lin_cara23cursos_ac10=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac10']);
					$lin_cara23cursos_ac11=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac11']);
					$lin_cara23cursos_ac12=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac12']);
					$lin_cara23cursos_ac13=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac13']);
					$lin_cara23cursos_ac14=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac14']);
					$lin_cara23cursos_ac15=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac15']);
					$lin_cara23cursos_ac16=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac16']);
					$lin_cara23cursos_ac17=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac17']);
					$lin_cara23cursos_ac18=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac18']);
					$lin_cara23cursos_ac19=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac19']);
					$lin_cara23cursos_ac20=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac20']);
					$lin_cara23cursos_ac21=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac21']);
					$lin_cara23cursos_ac22=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac22']);
					$lin_cara23cursos_ac23=$cSepara.f233_VacioNoSi($fila23['cara23cursos_ac23']);
					}
				$lin_cara23cursos_criterio=$cSepara.f233_NombreRiesgo($fila23['cara23cursos_criterio']);
				$lin_cara23contacto_forma=$cSepara.cadena_codificar($acara23contacto_forma[$i_cara23contacto_forma]);
				$lin_cara23contacto_observa=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23contacto_observa']), ' '));
				$lin_cara23aplaza=$cSepara.f233_VacioNoSi($fila23['cara23aplaza']);
				$lin_cara23seretira=$cSepara.f233_VacioNoSi($fila23['cara23seretira']);
				$lin_cara01factorprincipaldesc=$cSepara.cadena_codificar($acara01factorprincipaldesc[$i_cara01factorprincipaldesc]);
				$lin_cara23factorriesgo=$cSepara.f233_NombreRiesgo($fila23['cara23factorriesgo']);
				$sLinea=$cSepara.$iNS2.$sLinea.$lin_cara23catedra_avance.$lin_cara23catedra_acciones.$lin_cara23catedra_resultados.$lin_cara23catedra_criterio.$lin_cara23catedra_segprev.
				$lin_cara23cursos_total.$lin_cara23cursos_siningre.$lin_cara23cursos_menor200.$lin_cara23cursos_porcperdida.$lin_cara23cursos_ac1.
				$lin_cara23cursos_ac2.$lin_cara23cursos_ac3.$lin_cara23cursos_ac4.$lin_cara23cursos_ac5.$lin_cara23cursos_ac6.$lin_cara23cursos_ac7.
				$lin_cara23cursos_ac9.$lin_cara23cursos_ac10.$lin_cara23cursos_ac11.$lin_cara23cursos_ac12.$lin_cara23cursos_ac13.
				$lin_cara23cursos_ac14.$lin_cara23cursos_ac15.$lin_cara23cursos_ac16.$lin_cara23cursos_ac17.$lin_cara23cursos_ac18.
				$lin_cara23cursos_ac19.$lin_cara23cursos_ac20.$lin_cara23cursos_ac21.$lin_cara23cursos_ac22.$lin_cara23cursos_ac23.
				$lin_cara23cursos_criterio.$lin_cara23contacto_forma.$lin_cara23contacto_observa.$lin_cara23aplaza.$lin_cara23seretira.
				$lin_cara01factorprincipaldesc.$lin_cara23factorriesgo.$cSepara;
				$aNS2[$iNS2]=$sLinea;
				break;
				case 3: // Final
				$iNS3++;
				//Los campos de esa linea.
		$i_cara01factorprincpermanencia=$fila['cara01factorprincpermanencia'];
		if (isset($acara01factorprincpermanencia[$i_cara01factorprincpermanencia])==0){
            $sSQL='SELECT cara35id AS id, cara35nombre AS nombre FROM cara35factorpermanece WHERE cara35id='.$i_cara01factorprincpermanencia.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
                $acara01factorprincpermanencia[$i_cara01factorprincpermanencia]=str_replace($cSepara, $cComplementa, $filae['nombre']);
				}else{
                $acara01factorprincpermanencia[$i_cara01factorprincpermanencia]='';
				}
			}
		$lin_cara23catedra_aprueba=$cSepara.f233_VacioNoSi($fila23['cara23catedra_aprueba']);
		$lin_cara23cursos_menor200=$cSepara.$fila23['cara23cursos_menor200'];
		$lin_cara23cursos_porcperdida=$cSepara.$fila23['cara23cursos_porcperdida'];
		$lin_cara23aplaza=$cSepara.f233_VacioNoSi($fila23['cara23aplaza']);
		$lin_cara23seretira=$cSepara.f233_VacioNoSi($fila23['cara23seretira']);
		$lin_cara23permanece=$cSepara.f233_PorDefinirNoSi($fila23['cara23permanece']);
		$lin_cara01factorprincpermanencia=$cSepara.cadena_codificar($acara01factorprincpermanencia[$i_cara01factorprincpermanencia]);
		$lin_cara01factorprincipaldesc=$cSepara.cadena_codificar($acara01factorprincipaldesc[$i_cara01factorprincipaldesc]);
		$lin_cara23contacto_forma=$cSepara.cadena_codificar($acara23contacto_forma[$i_cara23contacto_forma]);
		$lin_cara23contacto_observa=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(cadena_codificar($fila23['cara23contacto_observa']), ' '));
				$sLinea=$cSepara.$iNS3.$sLinea.$lin_cara23catedra_aprueba.$lin_cara23cursos_menor200.$lin_cara23cursos_porcperdida.$lin_cara23aplaza.
                    $lin_cara23seretira.$lin_cara23permanece.$lin_cara01factorprincpermanencia.$lin_cara01factorprincipaldesc.$lin_cara23contacto_forma.
                    $lin_cara23contacto_observa.$cSepara;
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
			$sBlanco2=$cSepara.$sEtiqueta.$sNada.$s5sep.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara;
			$sBlanco3=$cSepara.$sEtiqueta.$sNada.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$s5sep.$s5sep.$s5sep;
			$sBlanco4=$cSepara.$sEtiqueta.$sNada.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara.$cSepara;
			$sBloque2=$sBlanco2;
			$sBloque3=$sBlanco3;
			$sBloque4=$sBlanco4;
			if ($iNS1>=$k){$sBloque2=$aNS1[$k];}
			if ($iNS2>=$k){$sBloque3=$aNS2[$k];}
			if ($iNS3>=$k){$sBloque4=$aNS3[$k];}
			$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3.$sBloque4.$sBloque9);
			}
		}
		//Fin de recorrer el reporte.
		}
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
	}
?>