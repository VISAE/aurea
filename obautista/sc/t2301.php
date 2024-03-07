<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.21.0 viernes, 22 de junio de 2018
--- Modelo Version 2.28.1 sabado, 23 de abril de 2022
---
--- Cambios 22 de mayo de 2020
--- 1. interpretacion cualitativa de los campos de pais, ciudad, puntaje factores y pruebas
--- 2. Ajustes en encabezados
--- Omar Augusto Bautista Mora - UNAD - 2020
--- omar.bautista@unad.edu.co
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
$iReporte=0;
function f2301_NomEstrado($sId){
	$res='[Sin dato]';
	switch($sId){
		case 1:$res='Uno';break;
		case 2:$res='Dos';break;
		case 3:$res='Tres';break;
		case 4:$res='Cuatro';break;
		case 5:$res='Cinco';break;
		case 6:$res='Seis';break;
		}
	return $res;
	}
function f2301_NombrePuntaje($sCompetencia,$iValor){
	$sValor='';
	switch($sCompetencia){
		case 'puntaje':
		if($iValor>=24 && $iValor<=30){
			$sValor='Bajo';
		}else{
			if($iValor>=17 && $iValor<=23){
				$sValor='Medio';
			}else{
				if($iValor>=10 && $iValor<=16){
					$sValor='Alto';
				}else{
					$sValor='Sin definir';
				}
			}
		}
		break;
		case 'lectura':
		if($iValor>=0 && $iValor<=40){
			$sValor='Bajo';
		}else{
			if($iValor>=50 && $iValor<=90){
				$sValor='Medio';
			}else{
				if($iValor>=100 && $iValor<=150){
					$sValor='Alto';
				}else{
					$sValor='Sin definir';
				}
			}
		}
		break;
		case 'digital':
		case 'ingles':
		if($iValor>=0 && $iValor<=40){
			$sValor='Bajo';
		}else{
			if($iValor>=50 && $iValor<=80){
				$sValor='Medio';
			}else{
				if($iValor>=90 && $iValor<=120){
					$sValor='Alto';
				}else{
					$sValor='Sin definir';
				}
			}
		}
		break;
		case 'razona':
		case 'biolog':
		case 'fisica':
		case 'quimica':
		if($iValor>=0 && $iValor<=30){
			$sValor='Bajo';
		}else{
			if($iValor>=40 && $iValor<=60){
				$sValor='Medio';
			}else{
				if($iValor>=70 && $iValor<=100){
					$sValor='Alto';
					}else{
					$sValor='Sin definir';
				}
			}
		}
		break;
	}
	return $sValor;
}
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
if (isset($_REQUEST['r'])!=0){$iReporte=numeros_validar($_REQUEST['r']);}
if (isset($_REQUEST['clave'])==0){$_REQUEST['clave']='';}
if (isset($_REQUEST['v3'])==0){$_REQUEST['v3']='';}
if (isset($_REQUEST['v4'])==0){$_REQUEST['v4']='';}
if (isset($_REQUEST['v5'])==0){$_REQUEST['v5']='';}
if (isset($_REQUEST['v6'])==0){$_REQUEST['v6']='';}
if (isset($_REQUEST['v7'])==0){$_REQUEST['v7']=1;}
if (isset($_REQUEST['v8'])==0){$_REQUEST['v8']='';}
if (isset($_REQUEST['v9'])==0){$_REQUEST['v9']='';}
if (isset($_REQUEST['v10'])==0){$_REQUEST['v10']='';}
if (isset($_REQUEST['v11'])==0){$_REQUEST['v11']='';}
if (isset($_REQUEST['v12'])==0){$_REQUEST['v12']='';}
if (isset($_REQUEST['v13'])==0){$_REQUEST['v13']='';}
if (isset($_REQUEST['v14'])==0){$_REQUEST['v14']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
$bEntra=true;
$bDebug=false;
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	$idTercero=$_SESSION['unad_id_tercero'];
	$iCodModulo=2350;
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
	$mensajes_2344=$APP->rutacomun.'lg/lg_2344_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2344)){$mensajes_2344=$APP->rutacomun.'lg/lg_2344_es.php';}
	require $mensajes_todas;
	require $mensajes_2301;
	require $mensajes_2344;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$cara50idperiodo=$_REQUEST['v3'];
	$cara50idzona=$_REQUEST['v4'];
	$cara50idcentro=$_REQUEST['v5'];
	$core50idescuela=$_REQUEST['v9'];
	$core50idprograma=$_REQUEST['v10'];
	$core50tipo=$_REQUEST['v6'];
	$cara50poblacion=$_REQUEST['v7'];
	$cara50periodoacomp=$_REQUEST['v11'];
	$cara50convenio=$_REQUEST['v8'];
	$cara50periodomatricula=$_REQUEST['v12'];
	$cara50tipomatricula=$_REQUEST['v13'];
	$sCondi='';
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t2301.csv';
	$sTituloRpt='consolidado_caracterizacion';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sWhere='';
	$sWhereAdd='';
	$sSQLadd='';
	$sSQLadd1='';
	if ($cara50idperiodo!=''){
		$sTituloRpt=$sTituloRpt.'P'.$cara50idperiodo.'';
		$sNomPeraca='{'.$cara50idperiodo.'}';
		$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$cara50idperiodo.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sNomPeraca=$fila['exte02nombre'];
			}
		$sDato=utf8_decode('Consolidado de caracterizacion periodo: '.$sNomPeraca);
		$objplano->AdicionarLinea($sDato);
		}
	if ($_REQUEST['v7']=='9'){
		//Es un total, tenemos que limitar la zona...
		$bEntra=false;
		if (seg_revisa_permiso($iCodModulo, 12, $objDB)){$bEntra=true;}
		//if (seg_revisa_permiso($iCodModulo, 1710, $objDB)){$bEntra=true;}
		if (!$bEntra){
			if ($_REQUEST['v4']!=''){
				//Verificar que la zona sea la que esta solicitando.
				$sSQL='SELECT cara21idzona FROM cara21lidereszona WHERE cara21idlider='.$idTercero.' AND cara21activo="S" AND cara21idzona='.$_REQUEST['v4'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					//No problema es un zonal y esta consultando su zona.
					}else{
					$sWhere=$sWhere.'TB.cara01idconsejero='.$_SESSION['unad_id_tercero'].' AND ';
					}
				}else{
				//Puede ver lo suyo....
				$sWhere=$sWhere.'TB.cara01idconsejero='.$_SESSION['unad_id_tercero'].' AND ';
				}
			}
		}else{
		$sWhere=$sWhere.'TB.cara01idconsejero='.$_SESSION['unad_id_tercero'].' AND ';
		$bConConsejero=true;
		}
	$bConConsejero=true;
	if ($_REQUEST['v5']!=''){
		$sWhere=$sWhere.'TB.cara01idcead='.$_REQUEST['v5'].' AND ';
		}else{
		if ($_REQUEST['v4']!=''){
			$sWhere=$sWhere.'TB.cara01idzona='.$_REQUEST['v4'].' AND ';
			}
		}
	$bPorTipo=false;
	if ($_REQUEST['v6']!=''){
		$sWhere=$sWhere.'TB.cara01tipocaracterizacion='.$_REQUEST['v6'].' AND ';
		$bPorTipo=true;
		//Definimos de una vez el tipo de bloques.
		for ($k=2;$k<8;$k++){
			$aBloque[$k]=false;
			}
		//Traer el tipo de caracterizacion para ver si tiene alguna pregunta, si no tiene pues se quita el bloque.
		$sSQL='SELECT cara11nombre, cara11fichafamilia, cara11ficha1, cara11ficha2, cara11ficha3, cara11ficha4, cara11ficha5, cara11ficha6, cara11ficha7 FROM cara11tipocaract WHERE cara11id='.$_REQUEST['v6'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$objplano->AdicionarLinea(utf8_decode('Tipo de caracterizacion:'.$cSepara.$fila['cara11nombre']));
			if ($fila['cara11fichafamilia']=='S'){
				for ($k=2;$k<7;$k++){
					$aBloque[$k]=true;
					}
				}
			if ($fila['cara11ficha1']=='S'){$aBloque[7]=true;}
			if ($fila['cara11ficha2']=='S'){$aBloque[7]=true;}
			if ($fila['cara11ficha3']=='S'){$aBloque[7]=true;}
			if ($fila['cara11ficha4']=='S'){$aBloque[7]=true;}
			if ($fila['cara11ficha5']=='S'){$aBloque[7]=true;}
			if ($fila['cara11ficha6']=='S'){$aBloque[7]=true;}
			if ($fila['cara11ficha7']=='S'){$aBloque[7]=true;}
			}
		}
	$sTablaConvenio='';
	if ($_REQUEST['v8']!=''){
		$sTablaConvenio=', core51convenioest AS T51';
		$sWhere=$sWhere.'TB.cara01idtercero=T51.core51idtercero AND T51.core51idconvenio='.$_REQUEST['v8'].' AND T51.core51activo="S" AND ';
		}
	if ($cara50periodoacomp!=''){
		$sTituloRpt=$sTituloRpt.'ACOMP'.$cara50periodoacomp.'';
		$sNomPeraca='{'.$cara50periodoacomp.'}';
		$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$cara50periodoacomp.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sNomPeraca=$fila['exte02nombre'];
			}
		$sDato=utf8_decode('Periodo de acompañamiento: '.$sNomPeraca);
		$objplano->AdicionarLinea($sDato);
		}
	//28 - Abril - 2022 - Se agregaron las variables.
	if ($cara50periodomatricula!=''){
		$sTituloRpt=$sTituloRpt.'MAT'.$cara50periodomatricula.'';
		$sNomPeraca='{'.$cara50periodomatricula.'}';
		$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$cara50periodomatricula.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sNomPeraca=$fila['exte02nombre'];
			}
		$sAddTitulo='';
		$sCondi16='';
		$bTotalMatricula=false;
		switch($cara50tipomatricula){
			case '':
			break;
			case '0':
			$sAddTitulo=' antiguos';
			$sCondi16=' AND core16nuevo=0';
			$sTituloRpt=$sTituloRpt.'ANT';
			break;
			case 1:
			$sAddTitulo=' nuevos';
			$sCondi16=' AND core16nuevo=1';
			$sTituloRpt=$sTituloRpt.'NUEVO';
			$bTotalMatricula=true;
			break;
			case 2:
			$sAddTitulo=' de reintegro';
			$sCondi16=' AND core16nuevo=2';
			$sTituloRpt=$sTituloRpt.'REIN';
			break;
			}
		$sDato=utf8_decode('Estudiantes'.$sAddTitulo.' matriculados en el periodo: '.$sNomPeraca);
		$objplano->AdicionarLinea($sDato);
		//
		$sIds='-99';
		$sSQL='SELECT core16tercero FROM core16actamatricula WHERE core16peraca='.$cara50periodomatricula.$sCondi16.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['core16tercero'];
			}
		if ($bTotalMatricula){
			$sSQLadd1=$sSQLadd1.'TB.cara01idtercero IN ('.$sIds.') AND ';
			}else{
			//Aqui la cosa cambia, porque tenemos que traer solo la ultima encuesta...
			$sIds01='-99';
			$sSQL='SELECT cara01id, cara01idtercero 
			FROM cara01encuesta 
			WHERE cara01idperaca<='.$cara50periodomatricula.' AND cara01idtercero IN ('.$sIds.') AND cara01completa="S"
			ORDER BY cara01idtercero, cara01idperaca';
			$tabla=$objDB->ejecutasql($sSQL);
			$idTercero=-99;
			while ($fila=$objDB->sf($tabla)){
				if ($idTercero!=$fila['cara01idtercero']){
					$sIds01=$sIds01.','.$fila['cara01id'];
					$idTercero=$fila['cara01idtercero'];
					}
				}
			$sSQLadd1=$sSQLadd1.'TB.cara01id IN ('.$sIds01.') AND ';
			}
		}
	if ($_REQUEST['v10']!=''){
		$sWhere=$sWhere.'TB.cara01idprograma='.$_REQUEST['v10'].' AND ';
		}else{
		if ($_REQUEST['v9']!=''){
			$sWhere=$sWhere.'TB.cara01idescuela='.$_REQUEST['v9'].' AND ';
			}
		}
	if ($cara50idperiodo!=''){
		if ($cara50periodoacomp!=''){
			$sWhere=$sWhere.'TB.cara01idperaca='.$cara50idperiodo.' AND TB.cara01idperiodoacompana='.$cara50periodoacomp.' AND ';
		}else{
			$sWhere=''.$sWhere.'TB.cara01idperaca='.$cara50idperiodo.' AND ';
		}
	}else{
		if ($cara50periodoacomp!=''){
			$sWhere=''.$sWhere.'TB.cara01idperiodoacompana='.$cara50periodoacomp.' AND ';
		}
	}
	if ($_REQUEST['v14']!='') {
		$sdatos='';
		$cara50listadoc='';
		$sListaDoc=cadena_limpiar($_REQUEST['v14'],"0123456789\n");
		$cara50listadoc=$cara50listadoc . implode('","', array_filter(explode("\n",$sListaDoc)));
		if ($cara50listadoc!='') {
			$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc IN ("' . $cara50listadoc . '")';
			$tabla=$objDB->ejecutasql($sSQL);
			while ($fila=$objDB->sf($tabla)){
				if ($sdatos != '') {
					$sdatos = $sdatos . ', ';
				}
				$sdatos = $sdatos . $fila['unad11id'];
			}
			if ($sdatos!='') {
				$sWhereAdd = $sWhereAdd . 'cara01idtercero IN (' .$sdatos . ') AND ';
			}
		}
	}	
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	/* Alistar los arreglos para las tablas hijas */
	$acara01idperaca=array();
	$acara01estrato=array();
	$acara01idzona=array();
	$acara01idcead=array();
	$acara01indigenas=array();
	$acara01indigenas[0]='Ninguno';
	$acara01centroreclusion=array();
	$acara01acad_razonestudio=array();
	$acara01acad_razonunad=array();
	$acara01tipocaracterizacion=array();
	$acara01perayuda=array();
	$acara01perayuda[0]='Ninguno';
	$aSys11=array();
	$sTitulo1='Datos personales';
	for ($l=1;$l<=57;$l++){
		$sSubTitulo='';
		if ($l==24){$sSubTitulo='Grupos poblacionales';}
		if ($l==37){$sSubTitulo='Discapacidades V 1.';}
		if ($l==40){$sSubTitulo='Discapacidades V 2.';}
		$sTitulo1=$sTitulo1.$cSepara.$sSubTitulo;
		}
	$sBloque1=''.utf8_decode('Tipo Caracterizacion').$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Estudiante'.$cSepara.'Ultimo Acceso Campus'.$cSepara.'Fecha encuesta'.$cSepara.'Edad'.$cSepara
	.'Sexo'.$cSepara.'Identidad de genero'.$cSepara.'Orientacion sexual'.$cSepara.'Pais'.$cSepara.'Departamento'.$cSepara.'Ciudad'.$cSepara.'Direccion'.$cSepara.'Estrato'.$cSepara.'Zona de residencia'.$cSepara
	.'Estado civil'.$cSepara.'Nombre del contacto'.$cSepara.'Parentezco del contacto'.$cSepara
	.'Zona'.$cSepara.'CEAD'.$cSepara.'Escuela'.$cSepara.'Programa'.$cSepara.'Matricula en convenio'.$cSepara.'Raizal'.$cSepara.'Palenquero'.$cSepara
	.'Afrocolombiano'.$cSepara.'Otra comunidad negra'.$cSepara.'ROM'.$cSepara.'Indigena'.$cSepara.'Campesinado'.$cSepara.'Victima desplazado'.$cSepara
	.'Victima ACR'.$cSepara.'Funcionario INPEC'.$cSepara.'Recluso INPEC'.$cSepara.'Tiempo de condena'.$cSepara.'Centro de reclusion'.$cSepara.'Sensorial'.$cSepara.'Fisica'.$cSepara.'Cognitiva'.$cSepara.'Ajustes razonables'.$cSepara.'Ajustes razonables Otra Ayuda'.$cSepara
	.'Sensorial v2'.$cSepara.'Intelectual'.$cSepara.'Fisica o motora'.$cSepara.'Mental Psicosocial'.$cSepara.'Sistemica'.$cSepara.'Sistemica Otro'.$cSepara.'Multiple'.$cSepara.'Multiple Otro'.$cSepara.'Certificado'.$cSepara.'Tiene Trastorno en el aprendizaje'.$cSepara.'Trastorno especifico en el aprendizaje'.$cSepara.'Talento Excepcional'.$cSepara.'Pruebas para definir el coeficiente intelectual'.$cSepara.'Con condicion medica'.$cSepara.'Cual condicion medica especifica'.'';
	$sTitulo2='Datos familiares';
	for ($l=1;$l<=12;$l++){
		$sTitulo2=$sTitulo2.$cSepara;
		}
	$sBloque2=''.$cSepara.utf8_decode('Cual es su tipo de vivienda actual'.$cSepara.'Con quien vive actualmente'.$cSepara.'Cuantas personas conforman su grupo familiar incluyendolo a usted'.$cSepara.'Cuantos hijos tiene'.$cSepara.'Es usted madre cabeza de hogar'.$cSepara.'Cuantas personas tiene a su cargo'.$cSepara.'Usted depende economicamente de alguien'.$cSepara.'Cual es el maximo nivel de escolaridad de su padre'.$cSepara.'Cual es el maximo nivel de escolaridad de su madre'.$cSepara.'Cuantos hermanos tiene'.$cSepara.'Cual es la posicion entre sus hermanos'.$cSepara.'Usted tiene familiares estudiando actualmente o que hayan estudiado en la UNAD');
	$sTitulo3=utf8_decode('Datos academicos');
	for ($l=1;$l<=35;$l++){
		$sSubTitulo='';
		if ($l==14){$sSubTitulo=utf8_decode('Con cuales equipos electronicos cuenta para acceder al campus virtual de la UNAD');}
		if ($l==28){$sSubTitulo=utf8_decode('La informacion que consulta la aprende mejor con');}
		$sTitulo3=$sTitulo3.$cSepara.$sSubTitulo;
		}
	$sBloque3=''.$cSepara.utf8_decode('Tipo de colegio donde termino su bachillerato'.$cSepara.'La modalidad en la que obtuvo su grado de bachiller es'.$cSepara.'Usted ha realizado otros estudios antes de llegar a la UNAD'
	.$cSepara.'Cual fue el ultimo nivel de estudios cursado'.$cSepara.'Cuanto tiempo lleva sin estudiar'.$cSepara.'Obtuvo certificacion o diploma de estos estudios'
	.$cSepara.'Usted ha tomado cursos virtuales'.$cSepara.'Cual es la principal razon para elegir el programa academico en el que se matriculo'.$cSepara.'El programa en el que se matriculo representa su primera opcion'.$cSepara.'Por favor indique el programa que le hubiera gustado estudiar.'
	.$cSepara.'Cual es la principal razon para estudiar en la UNAD'.$cSepara.'Ha tenido recesos en su proceso formativo'.$cSepara.'La razon del receso academico'.$cSepara.'Otra razon del receso academico'
	.$cSepara.'Computador de escritorio'.$cSepara.'Computador portatil'.$cSepara.'Tableta'.$cSepara.'Telefono inteligente'.$cSepara.'El lugar donde reside cuenta con servicio de energia electrica'
	.$cSepara.'El lugar donde reside cuenta con servicio de Internet'.$cSepara.'Ha usado plataformas virtuales con anterioridad'.$cSepara.'Maneja paquetes ofimaticos como Office (Word Excel Powerpoint) o similares'
	.$cSepara.'Ha participado en foros virtuales'.$cSepara.'Sabe convertir archivos digitales de un formato a otro'.$cSepara.'Su uso del correo electronico es'.$cSepara.'El uso del correo electronico institucional de la UNAD es'.$cSepara.'Indique porque no usa el correo institucional'.$cSepara.'Otra razon porque no usa el correo institucional'
	.$cSepara.'Texto'.$cSepara.'Video'.$cSepara.'Organizadores graficos'
	.$cSepara.'Animaciones'.$cSepara.'Cual es el medio que mas utiliza para comunicarse con amigos. conocidos. familiares o docentes a traves de internet'.$cSepara.'Indique el medio por el cual se ha enterando de las actividades y procesos de la Universidad'.$cSepara.'Otro medio por el cual se ha enterando de las actividades y procesos de la Universidad');
	$sTitulo4='Datos laborales';
	for ($l=1;$l<=12;$l++){
		$sTitulo4=$sTitulo4.$cSepara;
		}
	$sBloque4=''.$cSepara.utf8_decode('Cual es su situacion laboral actual'.$cSepara.'A que sector economico pertenece'.$cSepara.'Cual es el caracter juridico de la empresa'
	.$cSepara.'Cual es el cargo que ocupa'.$cSepara.'Cual es su antigüedad en el cargo actual'.$cSepara.'Que tipo de contrato tiene actualmente'
	.$cSepara.'Cuanto suman sus ingresos mensuales'.$cSepara.'Con que tiempo cuenta para desarrollar las actividades academicas'.$cSepara.'Que tipo de empresa es'
	.$cSepara.'Hace cuanto tiempo constituyo su empresa'.$cSepara.'Debe buscar trabajo para continuar sus estudios en la UNAD'.$cSepara.'De donde provienen los recursos economicos con los que usted estudiara en la UNAD');
	$sTitulo5='Bienestar V 1.';
	for ($l=1;$l<=33;$l++){
		$sSubTitulo='';
		if ($l==1){$sSubTitulo=utf8_decode('Usted practica regularmente alguna de las siguientes actividades deportivas o recreativas');}
		if ($l==9){$sSubTitulo=utf8_decode('Usted practica regularmente alguna de las siguientes actividades artisticas o culturales');}
		if ($l==19){$sSubTitulo=utf8_decode('Si usted practica danza por favor indique el genero');}
		if ($l==23){$sSubTitulo=utf8_decode('Emprendimiento');}
		if ($l==26){$sSubTitulo=utf8_decode('Estilo de vida saludable');}
		if ($l==28){$sSubTitulo=utf8_decode('Proyecto de vida');}
		if ($l==29){$sSubTitulo=utf8_decode('Medio ambiente');}
		if ($l==30){$sSubTitulo=utf8_decode('Cual de estos habitos cotidianos realiza usted como una practica de respeto hacia Medio Ambiente');}
		$sTitulo5=$sTitulo5.$cSepara.$sSubTitulo;
		}
	$sBloque5=''.$cSepara.utf8_decode('Baloncesto'.$cSepara.'Voleibol'.$cSepara.'Futbol sala'.
	$cSepara.'Artes marciales'.$cSepara.'Tenis de mesa'.$cSepara.'Ajedrez'.
	$cSepara.'Juegos autoctonos'.$cSepara.'Esta interesado en hacer parte de un grupo representativo en deportes'.$cSepara.'Especifique a cual grupo deportivo'.$cSepara.'Teatro'.
	$cSepara.'Danza'.$cSepara.'Musica'.$cSepara.'Circo'.
	$cSepara.'Artes plasticas'.$cSepara.'Cuenteria'.$cSepara.'Esta interesado en hacer parte de un grupo representativo en artes y cultura'.$cSepara.'Seleccione en cual'.
	$cSepara.'Si usted interpreta un instrumento musical por favor seleccionelo'.$cSepara.'En escala de 1 a 10 su dominio del instrumento musical es'.$cSepara.'Ritmos modernos (Salsa - Bachata)'.
	$cSepara.'Danza clasica'.$cSepara.'Danza contemporanea'.$cSepara.'Danza folklorica colombiana'
	.$cSepara.'Cuenta Ud. con una empresa que de respuesta a una necesidad social en su comunidad'.$cSepara.'Que necesidad cubre'.$cSepara.'En que temas de emprendimiento le gustaria recibir capacitacion'
	.$cSepara.'Cuales cree que son las causas mas frecuentes del estres'.$cSepara.'A traves de que estrategias le gustaria conocer el autocuidado'.$cSepara.'Que temas le gustaria abordar en la UNAD para su crecimiento personal'
	.$cSepara.'Como define la educacion ambiental'.$cSepara.'Ahorras de agua en la ducha y/o al cepillarse'.$cSepara.'Usas bombillas ahorradoras'
	.$cSepara.'Desconectas el cargador del celular cuando no esta en uso'.$cSepara.'Apagas las luces que no se requieran');
	$sTitulo5=$sTitulo5.$cSepara.'Bienestar V 2.';
	for ($l=1;$l<=93;$l++){
		$sSubTitulo='';
		if ($l==1){$sSubTitulo=utf8_decode('¿Que deporte practica?');}
		if ($l==11){$sSubTitulo=utf8_decode('Arte y Cultura');}
		if ($l==12){$sSubTitulo=utf8_decode('Usted practica regularmente alguna de las siguientes actividades artisticas o culturales');}
		if ($l==18){$sSubTitulo=utf8_decode('A que clase de eventos artisticos y culturales le gustaria asistir');}
		if ($l==27){$sSubTitulo=utf8_decode('Emprendimiento');}
		if ($l==29){$sSubTitulo=utf8_decode('Cual es el estado en que se encuentra su emprendimiento');}
		if ($l==37){$sSubTitulo=utf8_decode('En que temas le gustaria recibir informacion con respecto al emprendimiento');}
		if ($l==41){$sSubTitulo=utf8_decode('Estilo de vida saludable');}
		if ($l==42){$sSubTitulo=utf8_decode('Causas mas frecuentes del estres');}
		if ($l==46){$sSubTitulo=utf8_decode('Estrategias para conocer el autocuidado');}
		if ($l==50){$sSubTitulo=utf8_decode('Crecimiento Personal');}
		if ($l==51){$sSubTitulo=utf8_decode('Temas de interes para su crecimiento personal');}
		if ($l==58){$sSubTitulo=utf8_decode('Le gustaria hacer parte de algun grupo de bienestar');}
		if ($l==63){$sSubTitulo=utf8_decode('Medio ambiente');}
		if ($l==64){$sSubTitulo=utf8_decode('Realiza alguna de estas acciones frente al cuidado del medio ambiente');}
		if ($l==72){$sSubTitulo=utf8_decode('En su tiempo libre ha participado en alguna actividad ambiental');}
		if ($l==78){$sSubTitulo=utf8_decode('Cual tema desde el enfoque ambiental le gustaria conocer o profundizar');}
		$sTitulo5=$sTitulo5.$cSepara.$sSubTitulo;
		}
	$sBloque5=$sBloque5.$cSepara.utf8_decode('Es usted deportista de alto rendimiento o de competencia profesional'.$cSepara.'Atletismo'.$cSepara.'Baloncesto'.$cSepara.'Futbol'.$cSepara.'Gimnasia'.$cSepara.'Natacion'.$cSepara.'Voleibol'.$cSepara.'Tenis'.$cSepara.'Paralimpico'.$cSepara.'Otro deporte'.$cSepara.'Cual deporte'.
	$cSepara.'Danza'.$cSepara.'Musica'.$cSepara.'Teatro (circo)'.$cSepara.'Artes plasticas (pintura, dibujo, escultura, grabado, fotografia, entre otras)'.$cSepara.'Literatura (Poesia, cuenteria, escritura, etc)'.$cSepara.'Otra actividad'.$cSepara.'Cual actividad'.
	$cSepara.'Festivales Folcloricos'.$cSepara.'Exposiciones de Arte'.$cSepara.'Historia del Arte'.$cSepara.'Galeria Fotografica'.$cSepara.'Literatura'.$cSepara.'Teatro'.$cSepara.'Cine'.$cSepara.'Otro evento'.$cSepara.'Cual evento'.
	$cSepara.'Tengo un emprendimiento'.$cSepara.'Tengo una empresa'.
	$cSepara.'Mi emprendimiento se encuentra en marcha, pero busco recursos para avanzar.'.$cSepara.'Mi emprendimiento se encuentra en marcha, pero busco incrementar mis conocimientos para avanzar'.$cSepara.'Tengo una idea para emprender, pero no se como formular el plan de negocio y/o no se como iniciar su ejecucion.'.$cSepara.'Tengo un plan de negocio formulado con objetivos claros, el alcance, los recursos y las actividades, pero no tengo claro como iniciar su ejecucion.'.$cSepara.'No me interesa emprender por ahora, pero me interesa fortalecer mis conocimientos y habilidades.'.$cSepara.'Me interesa emprender, pero no tengo identificado el problema o necesidad en el mercado.'.$cSepara.'Otro estado'.$cSepara.'Cual estado'.
	$cSepara.'Marketing Digital'.$cSepara.'Plan de negocios'.$cSepara.'Como generar ideas de negocio'.$cSepara.'Creacion de empresa desde lo legal'.
	$cSepara.'Factores Economicos'.$cSepara.'Preocupaciones constantes'.$cSepara.'Consumir sustancias psicoactivas o relajantes'.$cSepara.'Complicaciones del Insomnio'.$cSepara.'Clima Laboral'.
	$cSepara.'Alimentacion'.$cSepara.'Autocuidado emocional'.$cSepara.'Estado de Salud'.$cSepara.'Meditacion'.
	$cSepara.'Educacion Sexual'.$cSepara.'Cultura Ciudadana'.$cSepara.'Relacion de Pareja'.$cSepara.'Relaciones Interpersonales'.$cSepara.'Dinamicas Familiares y formacion Integral para los Hijos'.$cSepara.'Autoestima'.$cSepara.'Inclusion y Diversidad'.$cSepara.'Regulacion e Inteligencia Emocional'.
	$cSepara.'Cultural'.$cSepara.'Artistico'.$cSepara.'Deportivo'.$cSepara.'Ambiental'.$cSepara.'Crecimiento Personal (Fortalecer habilidades Socioemocionales)'.
	$cSepara.'Separo la basura.'.$cSepara.'Uso productos que puedan reutilizarse'.$cSepara.'Apago las luces.'.$cSepara.'Consumo frutas y verduras ecologicas.'.$cSepara.'Evito dejar los aparatos enchufados.'.$cSepara.'Cierro los grifos correctamente.'.$cSepara.'Uso bicicleta.'.$cSepara.'Me muevo en transporte publico.'.$cSepara.'Ducha de 5 minutos.'.
	$cSepara.'Caminatas ecologicas'.$cSepara.'Siembra de arboles'.$cSepara.'Conferencias de temas ambientales'.$cSepara.'Campañas de reciclaje'.$cSepara.'Otra actividad'.$cSepara.'Cual actividad'.
	$cSepara.'Reforestacion'.$cSepara.'Movilidad y medio ambiente'.$cSepara.'Cambio Climatico'.$cSepara.'Ecofeminismo'.$cSepara.'Biodiversidad'.$cSepara.'Que es Ecologia'.$cSepara.'Economia Circular'.$cSepara.'Recursos naturales'.$cSepara.'Reciclaje'.$cSepara.'Tenencia responsable de mascotas'.$cSepara.'Cartografia Humana'.$cSepara.'Valor espiritual y religioso de la naturaleza'.$cSepara.'Capacidad de carga del medio ambiente'.$cSepara.'Otro tema'.$cSepara.'Cual tema');
	$sTitulo6='Psicosocial';
	for ($l=1;$l<=11;$l++){
		$sTitulo6=$sTitulo6.$cSepara;
		}
	$sBloque6=''.$cSepara.utf8_decode('Le cuesta expresar sus emociones con palabras'.$cSepara.'Como reacciona ante un cambio imprevisto aparentemente negativo'.$cSepara.'Cuando esta estresado o tienes varias preocupaciones ¿como lo maneja'
	.$cSepara.'Cuando tiene poco tiempo para el desarrollo de sus actividades academicas laborales y familiares ¿como lo asume?'.$cSepara.'Con respecto a su actitud frente la vida ¿como se describiria?'.$cSepara.'Que hace cuando presenta alguna dificultad o duda frente a una tarea asignada'
	.$cSepara.'Cuando esta afrontando una dificultad personal laboral emocional o familiar ¿cual es su actitud?'.$cSepara.'En terminos generales ¿esta satisfecho con quien es?'.$cSepara.'Como actua frente a una discusion'
	.$cSepara.'Como reacciona ante las siguientes situaciones sociales'.$cSepara.'Puntaje');
	$sTitulo7='Competencias';
	for ($l=1;$l<=7;$l++){
		$sTitulo7=$sTitulo7.$cSepara;
		}
	$sBloque7=''.$cSepara.'Competencias digitales'.$cSepara.utf8_decode('Lectura critica'.$cSepara.'Razonamiento cuantitativo'.$cSepara.'Ingles'.$cSepara.'Biologia'.$cSepara.'Fisica'.$cSepara.'Quimica');
		
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	$sTituloConsejero='';
	$sBloqueConsejero='';
	if ($bConConsejero){
		$sTituloConsejero='Consejero';
		$sBloqueConsejero='';
		}
	if ($bPorTipo){
		$sT=$sTitulo1;
		$sB=$sBloque1;
		if ($aBloque[2]){$sT=$sT.$sTitulo2;$sB=$sB.$sBloque2;}
		if ($aBloque[3]){$sT=$sT.$sTitulo3;$sB=$sB.$sBloque3;}
		if ($aBloque[4]){$sT=$sT.$sTitulo4;$sB=$sB.$sBloque4;}
		if ($aBloque[5]){$sT=$sT.$sTitulo5;$sB=$sB.$sBloque5;}
		if ($aBloque[6]){$sT=$sT.$sTitulo6;$sB=$sB.$sBloque6;}
		if ($aBloque[7]){$sT=$sT.$sTitulo7;$sB=$sB.$sBloque7;}
		$objplano->AdicionarLinea($sT.$sTituloConsejero);
		$objplano->AdicionarLinea($sB.$sBloqueConsejero);
		}else{
		$objplano->AdicionarLinea($sTitulo1.$sTitulo2.$sTitulo3.$sTitulo4.$sTitulo5.$sTitulo6.$sTitulo7.$sTituloConsejero);
		$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3.$sBloque4.$sBloque5.$sBloque6.$sBloque7.$sBloqueConsejero);
		}
	$sSQL='SELECT TB.* 
	FROM cara01encuesta AS TB'.$sTablaConvenio.' 
	WHERE '.$sSQLadd1.$sWhere.$sWhereAdd.' TB.cara01completa="S"';
	if ($bDebug){
		//$objplano->AdicionarLinea($sSQL);
	}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		if (true){
		$lin_cara01tipocaracterizacion='';
		$lin_cara01idperaca=$cSepara;
		$lin_cara01idtercero=$cSepara.$cSepara.$cSepara;
		$lin_unad11fechaultingreso=$cSepara;
		$lin_cara01completa=$cSepara;
		$lin_cara01fichaper=$cSepara;
		$lin_cara01fichafam=$cSepara;
		$lin_cara01fichaaca=$cSepara;
		$lin_cara01fichalab=$cSepara;
		$lin_cara01fichabien=$cSepara;
		$lin_cara01fichapsico=$cSepara;
		$lin_cara01fichadigital=$cSepara;
		$lin_cara01fichalectura=$cSepara;
		$lin_cara01ficharazona=$cSepara;
		$lin_cara01fichaingles=$cSepara;
		$lin_cara01fechaencuesta=$cSepara;
		$lin_cara01agnos=$cSepara;
		$lin_cara01sexo=$cSepara;
		$lin_cara01pais=$cSepara;
		$lin_cara01depto=$cSepara;
		$lin_cara01ciudad=$cSepara;
		$lin_cara01nomciudad=$cSepara;
		$lin_cara01direccion=$cSepara;
		$lin_cara01estrato=$cSepara;
		$lin_cara01zonares=$cSepara;
		$lin_cara01estcivil=$cSepara;
		$lin_cara01nomcontacto=$cSepara;
		$lin_cara01parentezcocontacto=$cSepara;
		$lin_cara01celcontacto=$cSepara;
		$lin_cara01correocontacto=$cSepara;
		$lin_cara01idzona=$cSepara;
		$lin_cara01idcead=$cSepara;
		$lin_cara01matconvenio=$cSepara;
		$lin_cara01raizal=$cSepara;
		$lin_cara01palenquero=$cSepara;
		$lin_cara01afrocolombiano=$cSepara;
		$lin_cara01otracomunnegras=$cSepara;
		$lin_cara01rom=$cSepara;
		$lin_cara01indigenas=$cSepara;
		$lin_cara01victimadesplazado=$cSepara;
		$lin_cara01idconfirmadesp=$cSepara.$cSepara.$cSepara;
		$lin_cara01fechaconfirmadesp=$cSepara;
		$lin_cara01victimaacr=$cSepara;
		$lin_cara01idconfirmacr=$cSepara.$cSepara.$cSepara;
		$lin_cara01fechaconfirmacr=$cSepara;
		$lin_cara01inpecfuncionario=$cSepara;
		$lin_cara01inpecrecluso=$cSepara;
		$lin_cara01inpectiempocondena=$cSepara;
		$lin_cara01centroreclusion=$cSepara;
		$lin_cara01discsensorial=$cSepara;
		$lin_cara01discfisica=$cSepara;
		$lin_cara01disccognitiva=$cSepara;
		$lin_cara01idconfirmadisc=$cSepara.$cSepara.$cSepara;
		$lin_cara01fechaconfirmadisc=$cSepara;
		$lin_cara01fam_tipovivienda=$cSepara;
		$lin_cara01fam_vivecon=$cSepara;
		$lin_cara01fam_numpersgrupofam=$cSepara;
		$lin_cara01fam_hijos=$cSepara;
		$lin_cara01fam_personasacargo=$cSepara;
		$lin_cara01fam_dependeecon=$cSepara;
		$lin_cara01fam_escolaridadpadre=$cSepara;
		$lin_cara01fam_escolaridadmadre=$cSepara;
		$lin_cara01fam_numhermanos=$cSepara;
		$lin_cara01fam_posicionherm=$cSepara;
		$lin_cara01fam_familiaunad=$cSepara;
		$lin_cara01acad_tipocolegio=$cSepara;
		$lin_cara01acad_modalidadbach=$cSepara;
		$lin_cara01acad_estudioprev=$cSepara;
		$lin_cara01acad_ultnivelest=$cSepara;
		$lin_cara01acad_obtubodiploma=$cSepara;
		$lin_cara01acad_hatomadovirtual=$cSepara;
		$lin_cara01acad_tiemposinest=$cSepara;
		$lin_cara01acad_razonestudio=$cSepara;
		$lin_cara01acad_primeraopc=$cSepara;
		$lin_cara01acad_programagusto=$cSepara;
		$lin_cara01acad_razonunad=$cSepara;
		$lin_cara01campus_compescrito=$cSepara;
		$lin_cara01campus_portatil=$cSepara;
		$lin_cara01campus_tableta=$cSepara;
		$lin_cara01campus_telefono=$cSepara;
		$lin_cara01campus_energia=$cSepara;
		$lin_cara01campus_internetreside=$cSepara;
		$lin_cara01campus_expvirtual=$cSepara;
		$lin_cara01campus_ofimatica=$cSepara;
		$lin_cara01campus_foros=$cSepara;
		$lin_cara01campus_conversiones=$cSepara;
		$lin_cara01campus_usocorreo=$cSepara;
		$lin_cara01campus_aprendtexto=$cSepara;
		$lin_cara01campus_aprendvideo=$cSepara;
		$lin_cara01campus_aprendmapas=$cSepara;
		$lin_cara01campus_aprendeanima=$cSepara;
		$lin_cara01campus_mediocomunica=$cSepara;
		$lin_cara01lab_situacion=$cSepara;
		$lin_cara01lab_sector=$cSepara;
		$lin_cara01lab_caracterjuri=$cSepara;
		$lin_cara01lab_cargo=$cSepara;
		$lin_cara01lab_antiguedad=$cSepara;
		$lin_cara01lab_tipocontrato=$cSepara;
		$lin_cara01lab_rangoingreso=$cSepara;
		$lin_cara01lab_tiempoacadem=$cSepara;
		$lin_cara01lab_tipoempresa=$cSepara;
		$lin_cara01lab_tiempoindepen=$cSepara;
		$lin_cara01lab_debebusctrab=$cSepara;
		$lin_cara01lab_origendinero=$cSepara;
		$lin_cara01bien_baloncesto=$cSepara;
		$lin_cara01bien_voleibol=$cSepara;
		$lin_cara01bien_futbolsala=$cSepara;
		$lin_cara01bien_artesmarc=$cSepara;
		$lin_cara01bien_tenisdemesa=$cSepara;
		$lin_cara01bien_ajedrez=$cSepara;
		$lin_cara01bien_juegosautoc=$cSepara;
		$lin_cara01bien_interesrepdeporte=$cSepara;
		$lin_cara01bien_deporteint=$cSepara;
		$lin_cara01bien_teatro=$cSepara;
		$lin_cara01bien_danza=$cSepara;
		$lin_cara01bien_musica=$cSepara;
		$lin_cara01bien_circo=$cSepara;
		$lin_cara01bien_artplast=$cSepara;
		$lin_cara01bien_cuenteria=$cSepara;
		$lin_cara01bien_interesreparte=$cSepara;
		$lin_cara01bien_arteint=$cSepara;
		$lin_cara01bien_interpreta=$cSepara;
		$lin_cara01bien_nivelinter=$cSepara;
		$lin_cara01bien_danza_mod=$cSepara;
		$lin_cara01bien_danza_clas=$cSepara;
		$lin_cara01bien_danza_cont=$cSepara;
		$lin_cara01bien_danza_folk=$cSepara;
		$lin_cara01bien_niveldanza=$cSepara;
		$lin_cara01bien_emprendedor=$cSepara;
		$lin_cara01bien_nombreemp=$cSepara;
		$lin_cara01bien_capacempren=$cSepara;
		$lin_cara01bien_tipocapacita=$cSepara;
		$lin_cara01bien_impvidasalud=$cSepara;
		$lin_cara01bien_estraautocuid=$cSepara;
		$lin_cara01bien_pv_personal=$cSepara;
		$lin_cara01bien_pv_familiar=$cSepara;
		$lin_cara01bien_pv_academ=$cSepara;
		$lin_cara01bien_pv_labora=$cSepara;
		$lin_cara01bien_pv_pareja=$cSepara;
		$lin_cara01bien_amb=$cSepara;
		$lin_cara01bien_amb_agu=$cSepara;
		$lin_cara01bien_amb_bom=$cSepara;
		$lin_cara01bien_amb_car=$cSepara;
		$lin_cara01bien_amb_info=$cSepara;
		$lin_cara01bien_amb_temas=$cSepara;
		$lin_cara01psico_costoemocion=$cSepara;
		$lin_cara01psico_reaccionimpre=$cSepara;
		$lin_cara01psico_estres=$cSepara;
		$lin_cara01psico_pocotiempo=$cSepara;
		$lin_cara01psico_actitudvida=$cSepara;
		$lin_cara01psico_duda=$cSepara;
		$lin_cara01psico_problemapers=$cSepara;
		$lin_cara01psico_satisfaccion=$cSepara;
		$lin_cara01psico_discusiones=$cSepara;
		$lin_cara01psico_atencion=$cSepara;
		$lin_cara01niveldigital=$cSepara;
		$lin_cara01nivellectura=$cSepara;
		$lin_cara01nivelrazona=$cSepara;
		$lin_cara01nivelingles=$cSepara;
		$lin_cara01idconsejero=$cSepara;
		$lin_cara01fechainicio=$cSepara;
		$lin_cara01telefono1=$cSepara;
		$lin_cara01telefono2=$cSepara;
		$lin_cara01correopersonal=$cSepara;
		$lin_cara01idprograma=$cSepara;
		$lin_cara01idescuela=$cSepara;
		$lin_cara01fichabiolog=$cSepara;
		$lin_cara01nivelbiolog=$cSepara;
		$lin_cara01fichafisica=$cSepara;
		$lin_cara01nivelfisica=$cSepara;
		$lin_cara01fichaquimica=$cSepara;
		$lin_cara01nivelquimica=$cSepara;
		$lin_cara01perayuda=$cSepara;
		$lin_cara01perotraayuda=$cSepara;
		$lin_cara01discsensorialotra=$cSepara;
		$lin_cara01discfisicaotra=$cSepara;
		$lin_cara01disccognitivaotra=$cSepara;
		$lin_cara01idcursocatedra=$cSepara;
		$lin_cara01idgrupocatedra=$cSepara;
		$lin_cara01factordescper=$cSepara;
		$lin_cara01factordescpsico=$cSepara;
		$lin_cara01factordescinsti=$cSepara;
		$lin_cara01factordescacad=$cSepara;
		$lin_cara01factordesc=$cSepara;
		$lin_cara01criteriodesc=$cSepara;
		$lin_cara01desertor=$cSepara;
		$lin_cara01factorprincipaldesc=$cSepara;
		$lin_cara01psico_puntaje=$cSepara;
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
		$lin_cara44sexov1identidadgen=$cSepara;
		$lin_cara44sexov1orientasexo=$cSepara;
		$lin_cara44campesinado=$cSepara;
		$lin_cara44fam_madrecabeza=$cSepara;
		$lin_cara44acadhatenidorecesos=$cSepara;
		$lin_cara44acadrazonreceso=$cSepara;
		$lin_cara44acadrazonrecesodetalle=$cSepara;
		$lin_cara44campus_usocorreounad=$cSepara;
		$lin_cara44campus_usocorreounadno=$cSepara;
		$lin_cara44campus_usocorreounadnodetalle=$cSepara;
		$lin_cara44campus_medioactivunad=$cSepara;
		$lin_cara44campus_medioactivunaddetalle=$cSepara;
		$lin_cara44bienv2altoren=$cSepara;
		$lin_cara44bienv2atletismo=$cSepara;
		$lin_cara44bienv2baloncesto=$cSepara;
		$lin_cara44bienv2futbol=$cSepara;
		$lin_cara44bienv2gimnasia=$cSepara;
		$lin_cara44bienv2natacion=$cSepara;
		$lin_cara44bienv2voleibol=$cSepara;
		$lin_cara44bienv2tenis=$cSepara;
		$lin_cara44bienv2paralimpico=$cSepara;
		$lin_cara44bienv2otrodeporte=$cSepara;
		$lin_cara44bienv2otrodeportedetalle=$cSepara;
		$lin_cara44bienv2activdanza=$cSepara;
		$lin_cara44bienv2activmusica=$cSepara;
		$lin_cara44bienv2activteatro=$cSepara;
		$lin_cara44bienv2activartes=$cSepara;
		$lin_cara44bienv2activliteratura=$cSepara;
		$lin_cara44bienv2activculturalotra=$cSepara;
		$lin_cara44bienv2activculturalotradetalle=$cSepara;
		$lin_cara44bienv2evenfestfolc=$cSepara;
		$lin_cara44bienv2evenexpoarte=$cSepara;
		$lin_cara44bienv2evenhistarte=$cSepara;
		$lin_cara44bienv2evengalfoto=$cSepara;
		$lin_cara44bienv2evenliteratura=$cSepara;
		$lin_cara44bienv2eventeatro=$cSepara;
		$lin_cara44bienv2evencine=$cSepara;
		$lin_cara44bienv2evenculturalotro=$cSepara;
		$lin_cara44bienv2evenculturalotrodetalle=$cSepara;
		$lin_cara44bienv2emprendimiento=$cSepara;
		$lin_cara44bienv2empresa=$cSepara;
		$lin_cara44bienv2emprenrecursos=$cSepara;
		$lin_cara44bienv2emprenconocim=$cSepara;
		$lin_cara44bienv2emprenplan=$cSepara;
		$lin_cara44bienv2emprenejecutar=$cSepara;
		$lin_cara44bienv2emprenfortconocim=$cSepara;
		$lin_cara44bienv2emprenidentproblema=$cSepara;
		$lin_cara44bienv2emprenotro=$cSepara;
		$lin_cara44bienv2emprenotrodetalle=$cSepara;
		$lin_cara44bienv2emprenmarketing=$cSepara;
		$lin_cara44bienv2emprenplannegocios=$cSepara;
		$lin_cara44bienv2emprenideas=$cSepara;
		$lin_cara44bienv2emprencreacion=$cSepara;
		$lin_cara44bienv2saludfacteconom=$cSepara;
		$lin_cara44bienv2saludpreocupacion=$cSepara;
		$lin_cara44bienv2saludconsumosust=$cSepara;
		$lin_cara44bienv2saludinsomnio=$cSepara;
		$lin_cara44bienv2saludclimalab=$cSepara;
		$lin_cara44bienv2saludalimenta=$cSepara;
		$lin_cara44bienv2saludemocion=$cSepara;
		$lin_cara44bienv2saludestado=$cSepara;
		$lin_cara44bienv2saludmedita=$cSepara;
		$lin_cara44bienv2crecimedusexual=$cSepara;
		$lin_cara44bienv2crecimcultciudad=$cSepara;
		$lin_cara44bienv2crecimrelpareja=$cSepara;
		$lin_cara44bienv2crecimrelinterp=$cSepara;
		$lin_cara44bienv2crecimdinamicafam=$cSepara;
		$lin_cara44bienv2crecimautoestima=$cSepara;
		$lin_cara44bienv2creciminclusion=$cSepara;
		$lin_cara44bienv2creciminteliemoc=$cSepara;
		$lin_cara44bienv2crecimcultural=$cSepara;
		$lin_cara44bienv2crecimartistico=$cSepara;
		$lin_cara44bienv2crecimdeporte=$cSepara;
		$lin_cara44bienv2crecimambiente=$cSepara;
		$lin_cara44bienv2crecimhabsocio=$cSepara;
		$lin_cara44bienv2ambienbasura=$cSepara;
		$lin_cara44bienv2ambienreutiliza=$cSepara;
		$lin_cara44bienv2ambienluces=$cSepara;
		$lin_cara44bienv2ambienfrutaverd=$cSepara;
		$lin_cara44bienv2ambienenchufa=$cSepara;
		$lin_cara44bienv2ambiengrifo=$cSepara;
		$lin_cara44bienv2ambienbicicleta=$cSepara;
		$lin_cara44bienv2ambientranspub=$cSepara;
		$lin_cara44bienv2ambienducha=$cSepara;
		$lin_cara44bienv2ambiencaminata=$cSepara;
		$lin_cara44bienv2ambiensiembra=$cSepara;
		$lin_cara44bienv2ambienconferencia=$cSepara;
		$lin_cara44bienv2ambienrecicla=$cSepara;
		$lin_cara44bienv2ambienotraactiv=$cSepara;
		$lin_cara44bienv2ambienotraactivdetalle=$cSepara;
		$lin_cara44bienv2ambienreforest=$cSepara;
		$lin_cara44bienv2ambienmovilidad=$cSepara;
		$lin_cara44bienv2ambienclimatico=$cSepara;
		$lin_cara44bienv2ambienecofemin=$cSepara;
		$lin_cara44bienv2ambienbiodiver=$cSepara;
		$lin_cara44bienv2ambienecologia=$cSepara;
		$lin_cara44bienv2ambieneconomia=$cSepara;
		$lin_cara44bienv2ambienrecnatura=$cSepara;
		$lin_cara44bienv2ambienreciclaje=$cSepara;
		$lin_cara44bienv2ambienmascota=$cSepara;
		$lin_cara44bienv2ambiencartohum=$cSepara;
		$lin_cara44bienv2ambienespiritu=$cSepara;
		$lin_cara44bienv2ambiencarga=$cSepara;
		$lin_cara44bienv2ambienotroenfoq=$cSepara;
		$lin_cara44bienv2ambienotroenfoqdetalle=$cSepara;
			}
		if (!$bPorTipo){
			//Vamos a tener 7 bloques segun el tipo de caracterizacion.
			for ($k=2;$k<8;$k++){
				$aBloque[$k]=false;
				}
			if ($fila['cara01fichafam']!=-1){$aBloque[2]=true;}
			if ($fila['cara01fichaaca']!=-1){$aBloque[3]=true;}
			if ($fila['cara01fichalab']!=-1){$aBloque[4]=true;}
			if ($fila['cara01fichabien']!=-1){$aBloque[5]=true;}
			if ($fila['cara01fichapsico']!=-1){$aBloque[6]=true;}
			$aBloque[7]=true;
			}
		$sSQL='SELECT TB.*
		FROM cara44encuesta AS TB
		WHERE TB.cara44id='.$fila['cara01id'].'';
		$tabla1=$objDB->ejecutasql($sSQL);
		if ($fila1=$objDB->sf($tabla1)){
			if (isset($acara44sexov1identidadgen[$fila1['cara44sexov1identidadgen']])!=0){
				$lin_cara44sexov1identidadgen=$cSepara.utf8_decode(html_entity_decode($acara44sexov1identidadgen[$fila1['cara44sexov1identidadgen']]));
			}
			if (isset($acara44sexov1orientasexo[$fila1['cara44sexov1orientasexo']])!=0){
				$lin_cara44sexov1orientasexo=$cSepara.utf8_decode(html_entity_decode($acara44sexov1orientasexo[$fila1['cara44sexov1orientasexo']]));
			}
			$lin_cara44campesinado=$cSepara.$fila1['cara44campesinado'];
			if ($aBloque[2]){
				$lin_cara44fam_madrecabeza=$cSepara.$fila1['cara44fam_madrecabeza'];
			}
			if ($aBloque[3]){
				$lin_cara44acadhatenidorecesos=$cSepara.$fila1['cara44fam_madrecabeza'];
				if (isset($acara44acadrazonreceso[$fila1['cara44acadrazonreceso']])!=0){
					$lin_cara44acadrazonreceso=$cSepara.utf8_decode(html_entity_decode($acara44acadrazonreceso[$fila1['cara44acadrazonreceso']]));
				}
				$lin_cara44acadrazonrecesodetalle=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila1['cara44acadrazonrecesodetalle']), ' '));
				if (isset($acara44campus_usocorreounad[$fila1['cara44campus_usocorreounad']])!=0){
					$lin_cara44campus_usocorreounad=$cSepara.utf8_decode(html_entity_decode($acara44campus_usocorreounad[$fila1['cara44campus_usocorreounad']]));
				}
				if (isset($acara44campus_usocorreounadno[$fila1['cara44campus_usocorreounadno']])!=0){
					$lin_cara44campus_usocorreounadno=$cSepara.utf8_decode(html_entity_decode($acara44campus_usocorreounadno[$fila1['cara44campus_usocorreounadno']]));
				}
				$lin_cara44campus_usocorreounadnodetalle=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila1['cara44campus_usocorreounadnodetalle']), ' '));
				if (isset($acara44campus_medioactivunad[$fila1['cara44campus_medioactivunad']])!=0){
					$lin_cara44campus_medioactivunad=$cSepara.utf8_decode(html_entity_decode($acara44campus_medioactivunad[$fila1['cara44campus_medioactivunad']]));
				}
				$lin_cara44campus_medioactivunaddetalle=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila1['cara44campus_medioactivunaddetalle']), ' '));
			}
			if ($aBloque[5]){
				$lin_cara44bienv2altoren=$cSepara.$fila1['cara44bienv2altoren'];
				$lin_cara44bienv2atletismo=$cSepara.$fila1['cara44bienv2atletismo'];
				$lin_cara44bienv2baloncesto=$cSepara.$fila1['cara44bienv2baloncesto'];
				$lin_cara44bienv2futbol=$cSepara.$fila1['cara44bienv2futbol'];
				$lin_cara44bienv2gimnasia=$cSepara.$fila1['cara44bienv2gimnasia'];
				$lin_cara44bienv2natacion=$cSepara.$fila1['cara44bienv2natacion'];
				$lin_cara44bienv2voleibol=$cSepara.$fila1['cara44bienv2voleibol'];
				$lin_cara44bienv2tenis=$cSepara.$fila1['cara44bienv2tenis'];
				$lin_cara44bienv2paralimpico=$cSepara.$fila1['cara44bienv2paralimpico'];
				$lin_cara44bienv2otrodeporte=$cSepara.$fila1['cara44bienv2otrodeporte'];
				$lin_cara44bienv2otrodeportedetalle=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila1['cara44bienv2otrodeportedetalle']), ' '));
				$lin_cara44bienv2activdanza=$cSepara.$fila1['cara44bienv2activdanza'];
				$lin_cara44bienv2activmusica=$cSepara.$fila1['cara44bienv2activmusica'];
				$lin_cara44bienv2activteatro=$cSepara.$fila1['cara44bienv2activteatro'];
				$lin_cara44bienv2activartes=$cSepara.$fila1['cara44bienv2activartes'];
				$lin_cara44bienv2activliteratura=$cSepara.$fila1['cara44bienv2activliteratura'];
				$lin_cara44bienv2activculturalotra=$cSepara.$fila1['cara44bienv2activculturalotra'];
				$lin_cara44bienv2activculturalotradetalle=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila1['cara44bienv2activculturalotradetalle']), ' '));
				$lin_cara44bienv2evenfestfolc=$cSepara.$fila1['cara44bienv2evenfestfolc'];
				$lin_cara44bienv2evenexpoarte=$cSepara.$fila1['cara44bienv2evenexpoarte'];
				$lin_cara44bienv2evenhistarte=$cSepara.$fila1['cara44bienv2evenhistarte'];
				$lin_cara44bienv2evengalfoto=$cSepara.$fila1['cara44bienv2evengalfoto'];
				$lin_cara44bienv2evenliteratura=$cSepara.$fila1['cara44bienv2evenliteratura'];
				$lin_cara44bienv2eventeatro=$cSepara.$fila1['cara44bienv2eventeatro'];
				$lin_cara44bienv2evencine=$cSepara.$fila1['cara44bienv2evencine'];
				$lin_cara44bienv2evenculturalotro=$cSepara.$fila1['cara44bienv2evenculturalotro'];
				$lin_cara44bienv2evenculturalotrodetalle=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila1['cara44bienv2evenculturalotrodetalle']), ' '));
				$lin_cara44bienv2emprendimiento=$cSepara.$fila1['cara44bienv2emprendimiento'];
				$lin_cara44bienv2empresa=$cSepara.$fila1['cara44bienv2empresa'];
				$lin_cara44bienv2emprenrecursos=$cSepara.$fila1['cara44bienv2emprenrecursos'];
				$lin_cara44bienv2emprenconocim=$cSepara.$fila1['cara44bienv2emprenconocim'];
				$lin_cara44bienv2emprenplan=$cSepara.$fila1['cara44bienv2emprenplan'];
				$lin_cara44bienv2emprenejecutar=$cSepara.$fila1['cara44bienv2emprenejecutar'];
				$lin_cara44bienv2emprenfortconocim=$cSepara.$fila1['cara44bienv2emprenfortconocim'];
				$lin_cara44bienv2emprenidentproblema=$cSepara.$fila1['cara44bienv2emprenidentproblema'];
				$lin_cara44bienv2emprenotro=$cSepara.$fila1['cara44bienv2emprenotro'];
				$lin_cara44bienv2emprenotrodetalle=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila1['cara44bienv2emprenotrodetalle']), ' '));
				$lin_cara44bienv2emprenmarketing=$cSepara.$fila1['cara44bienv2emprenmarketing'];
				$lin_cara44bienv2emprenplannegocios=$cSepara.$fila1['cara44bienv2emprenplannegocios'];
				$lin_cara44bienv2emprenideas=$cSepara.$fila1['cara44bienv2emprenideas'];
				$lin_cara44bienv2emprencreacion=$cSepara.$fila1['cara44bienv2emprencreacion'];
				$lin_cara44bienv2saludfacteconom=$cSepara.$fila1['cara44bienv2saludfacteconom'];
				$lin_cara44bienv2saludpreocupacion=$cSepara.$fila1['cara44bienv2saludpreocupacion'];
				$lin_cara44bienv2saludconsumosust=$cSepara.$fila1['cara44bienv2saludconsumosust'];
				$lin_cara44bienv2saludinsomnio=$cSepara.$fila1['cara44bienv2saludinsomnio'];
				$lin_cara44bienv2saludclimalab=$cSepara.$fila1['cara44bienv2saludclimalab'];
				$lin_cara44bienv2saludalimenta=$cSepara.$fila1['cara44bienv2saludalimenta'];
				$lin_cara44bienv2saludemocion=$cSepara.$fila1['cara44bienv2saludemocion'];
				$lin_cara44bienv2saludestado=$cSepara.$fila1['cara44bienv2saludestado'];
				$lin_cara44bienv2saludmedita=$cSepara.$fila1['cara44bienv2saludmedita'];
				$lin_cara44bienv2crecimedusexual=$cSepara.$fila1['cara44bienv2crecimedusexual'];
				$lin_cara44bienv2crecimcultciudad=$cSepara.$fila1['cara44bienv2crecimcultciudad'];
				$lin_cara44bienv2crecimrelpareja=$cSepara.$fila1['cara44bienv2crecimrelpareja'];
				$lin_cara44bienv2crecimrelinterp=$cSepara.$fila1['cara44bienv2crecimrelinterp'];
				$lin_cara44bienv2crecimdinamicafam=$cSepara.$fila1['cara44bienv2crecimdinamicafam'];
				$lin_cara44bienv2crecimautoestima=$cSepara.$fila1['cara44bienv2crecimautoestima'];
				$lin_cara44bienv2creciminclusion=$cSepara.$fila1['cara44bienv2creciminclusion'];
				$lin_cara44bienv2creciminteliemoc=$cSepara.$fila1['cara44bienv2creciminteliemoc'];
				$lin_cara44bienv2crecimcultural=$cSepara.$fila1['cara44bienv2crecimcultural'];
				$lin_cara44bienv2crecimartistico=$cSepara.$fila1['cara44bienv2crecimartistico'];
				$lin_cara44bienv2crecimdeporte=$cSepara.$fila1['cara44bienv2crecimdeporte'];
				$lin_cara44bienv2crecimambiente=$cSepara.$fila1['cara44bienv2crecimambiente'];
				$lin_cara44bienv2crecimhabsocio=$cSepara.$fila1['cara44bienv2crecimhabsocio'];
				$lin_cara44bienv2ambienbasura=$cSepara.$fila1['cara44bienv2ambienbasura'];
				$lin_cara44bienv2ambienreutiliza=$cSepara.$fila1['cara44bienv2ambienreutiliza'];
				$lin_cara44bienv2ambienluces=$cSepara.$fila1['cara44bienv2ambienluces'];
				$lin_cara44bienv2ambienfrutaverd=$cSepara.$fila1['cara44bienv2ambienfrutaverd'];
				$lin_cara44bienv2ambienenchufa=$cSepara.$fila1['cara44bienv2ambienenchufa'];
				$lin_cara44bienv2ambiengrifo=$cSepara.$fila1['cara44bienv2ambiengrifo'];
				$lin_cara44bienv2ambienbicicleta=$cSepara.$fila1['cara44bienv2ambienbicicleta'];
				$lin_cara44bienv2ambientranspub=$cSepara.$fila1['cara44bienv2ambientranspub'];
				$lin_cara44bienv2ambienducha=$cSepara.$fila1['cara44bienv2ambienducha'];
				$lin_cara44bienv2ambiencaminata=$cSepara.$fila1['cara44bienv2ambiencaminata'];
				$lin_cara44bienv2ambiensiembra=$cSepara.$fila1['cara44bienv2ambiensiembra'];
				$lin_cara44bienv2ambienconferencia=$cSepara.$fila1['cara44bienv2ambienconferencia'];
				$lin_cara44bienv2ambienrecicla=$cSepara.$fila1['cara44bienv2ambienrecicla'];
				$lin_cara44bienv2ambienotraactiv=$cSepara.$fila1['cara44bienv2ambienotraactiv'];
				$lin_cara44bienv2ambienotraactivdetalle=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila1['cara44bienv2ambienotraactivdetalle']), ' '));
				$lin_cara44bienv2ambienreforest=$cSepara.$fila1['cara44bienv2ambienreforest'];
				$lin_cara44bienv2ambienmovilidad=$cSepara.$fila1['cara44bienv2ambienmovilidad'];
				$lin_cara44bienv2ambienclimatico=$cSepara.$fila1['cara44bienv2ambienclimatico'];
				$lin_cara44bienv2ambienecofemin=$cSepara.$fila1['cara44bienv2ambienecofemin'];
				$lin_cara44bienv2ambienbiodiver=$cSepara.$fila1['cara44bienv2ambienbiodiver'];
				$lin_cara44bienv2ambienecologia=$cSepara.$fila1['cara44bienv2ambienecologia'];
				$lin_cara44bienv2ambieneconomia=$cSepara.$fila1['cara44bienv2ambieneconomia'];
				$lin_cara44bienv2ambienrecnatura=$cSepara.$fila1['cara44bienv2ambienrecnatura'];
				$lin_cara44bienv2ambienreciclaje=$cSepara.$fila1['cara44bienv2ambienreciclaje'];
				$lin_cara44bienv2ambienmascota=$cSepara.$fila1['cara44bienv2ambienmascota'];
				$lin_cara44bienv2ambiencartohum=$cSepara.$fila1['cara44bienv2ambiencartohum'];
				$lin_cara44bienv2ambienespiritu=$cSepara.$fila1['cara44bienv2ambienespiritu'];
				$lin_cara44bienv2ambiencarga=$cSepara.$fila1['cara44bienv2ambiencarga'];
				$lin_cara44bienv2ambienotroenfoq=$cSepara.$fila1['cara44bienv2ambienotroenfoq'];
				$lin_cara44bienv2ambienotroenfoqdetalle=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila1['cara44bienv2ambienotroenfoqdetalle']), ' '));
			}
		}
		if (true){
			$i_cara01tipocaracterizacion=$fila['cara01tipocaracterizacion'];
			if (isset($acara01tipocaracterizacion[$i_cara01tipocaracterizacion])==0){
				$sSQL='SELECT cara11nombre FROM cara11tipocaract WHERE cara11id='.$i_cara01tipocaracterizacion.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01tipocaracterizacion[$i_cara01tipocaracterizacion]=str_replace($cSepara, $cComplementa, $filae['cara11nombre']);
					}else{
					$acara01tipocaracterizacion[$i_cara01tipocaracterizacion]='';
					}
				}
			$lin_cara01tipocaracterizacion=utf8_decode($acara01tipocaracterizacion[$i_cara01tipocaracterizacion]);
			$iTer=$fila['cara01idtercero'];
			if (isset($aSys11[$iTer]['doc'])==0){
				list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing'])=f1011_InfoParaPlano($iTer, $objDB);
				}
			$lin_cara01idtercero=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.cadena_letrasynumeros(utf8_decode($aSys11[$iTer]['razon']), ' ');
			$lin_unad11fechaultingreso=$cSepara.$aSys11[$iTer]['ult_ing'];
			$lin_cara01fechaencuesta=$cSepara.$fila['cara01fechaencuesta'];
			$lin_cara01agnos=$cSepara.$fila['cara01agnos'];
			$lin_cara01sexo=$cSepara.$fila['cara01sexo'];
			$i_cara01pais=$fila['cara01pais'];
			if (isset($acara01pais['"'.$i_cara01pais.'"'])==0){
				$sSQL='SELECT unad18nombre FROM unad18pais WHERE unad18codigo="'.$i_cara01pais.'"';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01pais['"'.$i_cara01pais.'"']=str_replace($cSepara, $cComplementa, $filae['unad18nombre']);
					}else{
					$acara01pais['"'.$i_cara01pais.'"']='';
					}
				}
			$lin_cara01pais=$cSepara.utf8_decode($acara01pais['"'.$i_cara01pais.'"']);
			$i_cara01depto=$fila['cara01depto'];
			if (isset($acara01depto['"'.$i_cara01depto.'"'])==0){
				$sSQL='SELECT unad19nombre FROM unad19depto WHERE unad19codigo="'.$i_cara01depto.'"';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01depto['"'.$i_cara01depto.'"']=str_replace($cSepara, $cComplementa, $filae['unad19nombre']);
					}else{
					$acara01depto['"'.$i_cara01depto.'"']='';
					}
				}
			$lin_cara01depto=$cSepara.utf8_decode($acara01depto['"'.$i_cara01depto.'"']);
			$i_cara01ciudad=$fila['cara01ciudad'];
			if (isset($acara01ciudad['"'.$i_cara01ciudad.'"'])==0){
				$sSQL='SELECT unad20nombre FROM unad20ciudad WHERE unad20codigo="'.$i_cara01ciudad.'"';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01ciudad['"'.$i_cara01ciudad.'"']=str_replace($cSepara, $cComplementa, $filae['unad20nombre']);
					}else{
					$acara01ciudad['"'.$i_cara01ciudad.'"']='';
					}
				}
			$lin_cara01ciudad=$cSepara.utf8_decode($acara01ciudad['"'.$i_cara01ciudad.'"']);
			$lin_cara01direccion=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila['cara01direccion']), ' '));
			$lin_cara01estrato=$cSepara.f2301_NomEstrado($fila['cara01estrato']);
			if ($fila['cara01zonares']=='U'){$lin_cara01zonares=$cSepara.'Urbana';}
			if ($fila['cara01zonares']=='R'){$lin_cara01zonares=$cSepara.'Rural';}
			$i_cara01estcivil=$fila['cara01estcivil'];
			if (isset($acara01estcivil['"'.$i_cara01estcivil.'"'])==0){
				$sSQL='SELECT unad21nombre FROM unad21estadocivil WHERE unad21codigo="'.$i_cara01estcivil.'"';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01estcivil['"'.$i_cara01estcivil.'"']=str_replace($cSepara, $cComplementa, $filae['unad21nombre']);
					}else{
					$acara01estcivil['"'.$i_cara01estcivil.'"']='['.$i_cara01estcivil.']';
					}
				}
			$lin_cara01estcivil=$cSepara.utf8_decode($acara01estcivil['"'.$i_cara01estcivil.'"']);
			$lin_cara01nomcontacto=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila['cara01nomcontacto'])));
			$lin_cara01parentezcocontacto=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila['cara01parentezcocontacto'])));
			//$lin_cara01celcontacto=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01celcontacto']));
			//$lin_cara01correocontacto=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01correocontacto']));
			$i_cara01idzona=$fila['cara01idzona'];
			if (isset($acara01idzona[$i_cara01idzona])==0){
				$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$i_cara01idzona.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01idzona[$i_cara01idzona]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
					}else{
					$acara01idzona[$i_cara01idzona]='';
					}
				}
			$lin_cara01idzona=$cSepara.utf8_decode($acara01idzona[$i_cara01idzona]);
			$i_cara01idcead=$fila['cara01idcead'];
			if (isset($acara01idcead[$i_cara01idcead])==0){
				$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$i_cara01idcead.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01idcead[$i_cara01idcead]=str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
					}else{
					$acara01idcead[$i_cara01idcead]='';
					}
				}
			$lin_cara01idcead=$cSepara.utf8_decode($acara01idcead[$i_cara01idcead]);
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
			$lin_cara01matconvenio=$cSepara.$fila['cara01matconvenio'];
			$lin_cara01raizal=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01raizal']));
			$lin_cara01palenquero=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01palenquero']));
			$lin_cara01afrocolombiano=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01afrocolombiano']));
			$lin_cara01otracomunnegras=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01otracomunnegras']));
			$lin_cara01rom=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01rom']));
			$i_cara01indigenas=$fila['cara01indigenas'];
			if (isset($acara01indigenas[$i_cara01indigenas])==0){
				$sSQL='SELECT cara02nombre FROM cara02indigenas WHERE cara02id='.$i_cara01indigenas.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01indigenas[$i_cara01indigenas]=str_replace($cSepara, $cComplementa, $filae['cara02nombre']);
					}else{
					$acara01indigenas[$i_cara01indigenas]='';
					}
				}
			$lin_cara01indigenas=$cSepara.utf8_decode($acara01indigenas[$i_cara01indigenas]);
			$lin_cara01victimadesplazado=$cSepara.$fila['cara01victimadesplazado'];
			$lin_cara01victimaacr=$cSepara.$fila['cara01victimaacr'];
			$lin_cara01inpecfuncionario=$cSepara.$fila['cara01inpecfuncionario'];
			$lin_cara01inpecrecluso=$cSepara.$fila['cara01inpecrecluso'];
			if ($fila['cara01inpecrecluso']=='S'){
				$lin_cara01inpectiempocondena=$cSepara.$fila['cara01inpectiempocondena'];
				$i_cara01centroreclusion=$fila['cara01centroreclusion'];
				if (isset($acara01centroreclusion[$i_cara01centroreclusion])==0){
					$sSQL='SELECT cara03nombre FROM cara03centroreclusion WHERE cara03id='.$i_cara01centroreclusion.'';
					$tablae=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablae)>0){
						$filae=$objDB->sf($tablae);
						$acara01centroreclusion[$i_cara01centroreclusion]=str_replace($cSepara, $cComplementa, $filae['cara03nombre']);
						}else{
						$acara01centroreclusion[$i_cara01centroreclusion]='';
						}
					}
				$lin_cara01centroreclusion=$cSepara.utf8_decode($acara01centroreclusion[$i_cara01centroreclusion]);
				}
			$lin_cara01discsensorial=$cSepara.$fila['cara01discsensorial'];
			if (isset($acara01discsensorial[$fila['cara01discsensorial']])!=0){$lin_cara01discsensorial=$cSepara.$acara01discsensorial[$fila['cara01discsensorial']];}
			$lin_cara01discfisica=$cSepara.$fila['cara01discfisica'];
			if (isset($acara01discfisica[$fila['cara01discfisica']])!=0){$lin_cara01discfisica=$cSepara.$acara01discfisica[$fila['cara01discfisica']];}
			$lin_cara01disccognitiva=$cSepara.$fila['cara01disccognitiva'];
			if (isset($acara01disccognitiva[$fila['cara01disccognitiva']])!=0){$lin_cara01disccognitiva=$cSepara.$acara01disccognitiva[$fila['cara01disccognitiva']];}
			$acara01discv2=array($fila['cara01discv2sensorial'],$fila['cara02discv2intelectura'],$fila['cara02discv2fisica'],$fila['cara02discv2psico']);
			foreach($acara01discv2 as $i_id){
				if (isset($acara37discapacidades[$i_id])==0){
					$sSQL='SELECT cara37nombre FROM cara37discapacidades WHERE cara37id='.$i_id.'';
					$tablae=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablae)>0){
						$filae=$objDB->sf($tablae);
						$acara37discapacidades[$i_id]=str_replace($cSepara, $cComplementa, $filae['cara37nombre']);
						}else{
						$acara37discapacidades[$i_id]='';
						}
					}
				}
			$lin_cara01discv2sensorial=$cSepara.utf8_decode($acara37discapacidades[$fila['cara01discv2sensorial']]);
			$lin_cara02discv2intelectura=$cSepara.utf8_decode($acara37discapacidades[$fila['cara02discv2intelectura']]);
			$lin_cara02discv2fisica=$cSepara.utf8_decode($acara37discapacidades[$fila['cara02discv2fisica']]);
			$lin_cara02discv2psico=$cSepara.utf8_decode($acara37discapacidades[$fila['cara02discv2psico']]);
			$lin_cara02discv2sistemica=$cSepara.utf8_decode($acara02discv2sistemica[$fila['cara02discv2sistemica']]);
			$lin_cara02discv2sistemicaotro=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila['cara02discv2sistemicaotro']), ' '));
			$lin_cara02discv2multiple=$cSepara.utf8_decode($acara02discv2multiple[$fila['cara02discv2multiple']]);
			$lin_cara02discv2multipleotro=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila['cara02discv2multipleotro']), ' '));
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
			$lin_cara02talentoexcepcional=$cSepara.utf8_decode(html_entity_decode($acara02talentoexcepcional[$i_cara02talentoexcepcional]));
			$bEntra=true;
			if ($fila['cara01perayuda']==-1){
				$bEntra=false;
				$lin_cara01perayuda=$cSepara.'Otra';
				$lin_cara01perotraayuda=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila['cara01perotraayuda']), ' '));
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
				$lin_cara01perayuda=$cSepara.utf8_decode(html_entity_decode($acara01perayuda[$i_cara01perayuda]));
				}
			$lin_cara01discv2tiene=$cSepara.'['.$fila['cara01discv2tiene'].']';
			if (isset($acara01discv2tiene[$fila['cara01discv2tiene']])!=0){
				$lin_cara01discv2tiene=$cSepara.utf8_decode(html_entity_decode($acara01discv2tiene[$fila['cara01discv2tiene']]));
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
					$lin_cara01discv2trastaprende=$cSepara.utf8_decode(html_entity_decode($acara01discv2trastaprende[$i_cara01discv2trastaprende]));
					}
			$lin_cara01discv2contalento=$cSepara.'No';
			if ($fila['cara01discv2contalento']!=0){
				$lin_cara01discv2contalento=$cSepara.'Si';
				}
			$lin_cara01discv2condicionmedica=$cSepara.'No';
			if ($fila['cara01discv2condicionmedica']!=0){
				$lin_cara01discv2condicionmedica=$cSepara.'Si';
				$lin_cara01discv2condmeddet=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila['cara01discv2condmeddet']), ' '));
				}
			$lin_cara01discv2pruebacoeficiente=$cSepara.'['.$fila['cara01discv2pruebacoeficiente'].']';
			if (isset($acara01discv2pruebacoeficiente[$fila['cara01discv2pruebacoeficiente']])!=0){
				$lin_cara01discv2pruebacoeficiente=$cSepara.utf8_decode(html_entity_decode($acara01discv2pruebacoeficiente[$fila['cara01discv2pruebacoeficiente']]));
				}
			}
		if ($aBloque[2]){
			$lin_cara01fam_tipovivienda=$cSepara.'['.$fila['cara01fam_tipovivienda'].']';
			if (isset($afam_tipovivienda[$fila['cara01fam_tipovivienda']])!=0){
				$lin_cara01fam_tipovivienda=$cSepara.$afam_tipovivienda[$fila['cara01fam_tipovivienda']];
				}
			$lin_cara01fam_vivecon=$cSepara.'['.$fila['cara01fam_vivecon'].']';
			if (isset($afam_vivecon[$fila['cara01fam_vivecon']])!=0){
				$lin_cara01fam_vivecon=$cSepara.utf8_decode($afam_vivecon[$fila['cara01fam_vivecon']]);
				}
			$lin_cara01fam_numpersgrupofam=$cSepara.'['.$fila['cara01fam_numpersgrupofam'].']';
			if (isset($afam_numpersgrupofam[$fila['cara01fam_numpersgrupofam']])!=0){
				$lin_cara01fam_numpersgrupofam=$cSepara.$afam_numpersgrupofam[$fila['cara01fam_numpersgrupofam']];
				}
			$lin_cara01fam_hijos=$cSepara.'['.$fila['cara01fam_hijos'].']';
			if (isset($afam_hijos[$fila['cara01fam_hijos']])!=0){
				$lin_cara01fam_hijos=$cSepara.utf8_decode($afam_hijos[$fila['cara01fam_hijos']]);
				}
			$lin_cara01fam_personasacargo=$cSepara.'['.$fila['cara01fam_personasacargo'].']';
			if (isset($afam_personasacargo[$fila['cara01fam_personasacargo']])!=0){
				$lin_cara01fam_personasacargo=$cSepara.utf8_decode($afam_personasacargo[$fila['cara01fam_personasacargo']]);
				}
			$lin_cara01fam_dependeecon=$cSepara.$fila['cara01fam_dependeecon'];
			$lin_cara01fam_escolaridadpadre=$cSepara.'['.$fila['cara01fam_escolaridadpadre'].']';
			if (isset($aescolaridad[$fila['cara01fam_escolaridadpadre']])!=0){
				$lin_cara01fam_escolaridadpadre=$cSepara.$aescolaridad[$fila['cara01fam_escolaridadpadre']];
				}
			$lin_cara01fam_escolaridadmadre=$cSepara.'['.$fila['cara01fam_escolaridadmadre'].']';
			if (isset($aescolaridad[$fila['cara01fam_escolaridadmadre']])!=0){
				$lin_cara01fam_escolaridadmadre=$cSepara.$aescolaridad[$fila['cara01fam_escolaridadmadre']];
				}
			$lin_cara01fam_numhermanos=$cSepara.'['.$fila['cara01fam_numhermanos'].']';
			if (isset($afam_numhermanos[$fila['cara01fam_numhermanos']])!=0){
				$lin_cara01fam_numhermanos=$cSepara.utf8_decode($afam_numhermanos[$fila['cara01fam_numhermanos']]);
				}
			$lin_cara01fam_posicionherm=$cSepara.'['.$fila['cara01fam_posicionherm'].']';
			if (isset($afam_posicionherm[$fila['cara01fam_posicionherm']])!=0){
				$lin_cara01fam_posicionherm=$cSepara.$afam_posicionherm[$fila['cara01fam_posicionherm']];
				}
			$lin_cara01fam_familiaunad=$cSepara.$fila['cara01fam_familiaunad'];
			}
		if ($aBloque[3]){
			$lin_cara01acad_tipocolegio=$cSepara.'['.$fila['cara01acad_tipocolegio'].']';
			if (isset($aacad_tipocolegio[$fila['cara01acad_tipocolegio']])!=0){
				$lin_cara01acad_tipocolegio=$cSepara.utf8_decode($aacad_tipocolegio[$fila['cara01acad_tipocolegio']]);
				}
			$lin_cara01acad_modalidadbach=$cSepara.'['.$fila['cara01acad_modalidadbach'].']';
			if (isset($aacad_modalidadbach[$fila['cara01acad_modalidadbach']])!=0){
				$lin_cara01acad_modalidadbach=$cSepara.utf8_decode($aacad_modalidadbach[$fila['cara01acad_modalidadbach']]);
				}
			$lin_cara01acad_estudioprev=$cSepara.$fila['cara01acad_estudioprev'];
			$lin_cara01acad_ultnivelest=$cSepara.'['.$fila['cara01acad_ultnivelest'].']';
			if (isset($aacad_ultnivelest[$fila['cara01acad_ultnivelest']])!=0){
				$lin_cara01acad_ultnivelest=$cSepara.utf8_decode($aacad_ultnivelest[$fila['cara01acad_ultnivelest']]);
				}
			$lin_cara01acad_obtubodiploma=$cSepara.$fila['cara01acad_obtubodiploma'];
			$lin_cara01acad_hatomadovirtual=$cSepara.$fila['cara01acad_hatomadovirtual'];
			$lin_cara01acad_tiemposinest=$cSepara.'['.$fila['cara01acad_tiemposinest'].']';
			if (isset($acara01acad_tiemposinest[$fila['cara01acad_tiemposinest']])!=0){
				$lin_cara01acad_tiemposinest=$cSepara.utf8_decode($acara01acad_tiemposinest[$fila['cara01acad_tiemposinest']]);
				}
			$i_cara01acad_razonestudio=$fila['cara01acad_razonestudio'];
			if (isset($acara01acad_razonestudio[$i_cara01acad_razonestudio])==0){
				$sSQL='SELECT cara04nombre FROM cara04razonestudio WHERE cara04id='.$i_cara01acad_razonestudio.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01acad_razonestudio[$i_cara01acad_razonestudio]=str_replace($cSepara, $cComplementa, $filae['cara04nombre']);
					}else{
					$acara01acad_razonestudio[$i_cara01acad_razonestudio]='';
					}
				}
			$lin_cara01acad_razonestudio=$cSepara.utf8_decode($acara01acad_razonestudio[$i_cara01acad_razonestudio]);
			$lin_cara01acad_primeraopc=$cSepara.$fila['cara01acad_primeraopc'];
			$lin_cara01acad_programagusto=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01acad_programagusto']));
			$i_cara01acad_razonunad=$fila['cara01acad_razonunad'];
			if (isset($acara01acad_razonunad[$i_cara01acad_razonunad])==0){
				$sSQL='SELECT cara05nombre FROM cara05razonunad WHERE cara05id='.$i_cara01acad_razonunad.'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$acara01acad_razonunad[$i_cara01acad_razonunad]=str_replace($cSepara, $cComplementa, $filae['cara05nombre']);
					}else{
					$acara01acad_razonunad[$i_cara01acad_razonunad]='';
					}
				}
			$lin_cara01acad_razonunad=$cSepara.utf8_decode($acara01acad_razonunad[$i_cara01acad_razonunad]);
			$lin_cara01campus_compescrito=$cSepara.$fila['cara01campus_compescrito'];
			$lin_cara01campus_portatil=$cSepara.$fila['cara01campus_portatil'];
			$lin_cara01campus_tableta=$cSepara.$fila['cara01campus_tableta'];
			$lin_cara01campus_telefono=$cSepara.$fila['cara01campus_telefono'];
			$lin_cara01campus_energia=$cSepara.'['.$fila['cara01campus_energia'].']';
			if (isset($acara01campus_energia[$fila['cara01campus_energia']])!=0){
				$lin_cara01campus_energia=$cSepara.utf8_decode($acara01campus_energia[$fila['cara01campus_energia']]);
				}
			$lin_cara01campus_internetreside=$cSepara.'['.$fila['cara01campus_internetreside'].']';
			if (isset($acara01campus_internetreside[$fila['cara01campus_internetreside']])!=0){
				$lin_cara01campus_internetreside=$cSepara.utf8_decode($acara01campus_internetreside[$fila['cara01campus_internetreside']]);
				}
			$lin_cara01campus_expvirtual=$cSepara.$fila['cara01campus_expvirtual'];
			$lin_cara01campus_ofimatica=$cSepara.$fila['cara01campus_ofimatica'];
			$lin_cara01campus_foros=$cSepara.$fila['cara01campus_foros'];
			$lin_cara01campus_conversiones=$cSepara.$fila['cara01campus_conversiones'];
			$lin_cara01campus_usocorreo=$cSepara.'['.$fila['cara01campus_usocorreo'].']';
			if (isset($acara01campus_usocorreo[$fila['cara01campus_usocorreo']])!=0){
				$lin_cara01campus_usocorreo=$cSepara.utf8_decode($acara01campus_usocorreo[$fila['cara01campus_usocorreo']]);
				}
			$lin_cara01campus_aprendtexto=$cSepara.$fila['cara01campus_aprendtexto'];
			$lin_cara01campus_aprendvideo=$cSepara.$fila['cara01campus_aprendvideo'];
			$lin_cara01campus_aprendmapas=$cSepara.$fila['cara01campus_aprendmapas'];
			$lin_cara01campus_aprendeanima=$cSepara.$fila['cara01campus_aprendeanima'];
			$lin_cara01campus_mediocomunica=$cSepara.'['.$fila['cara01campus_mediocomunica'].']';
			if (isset($acara01campus_mediocomunica[$fila['cara01campus_mediocomunica']])!=0){
				$lin_cara01campus_mediocomunica=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($acara01campus_mediocomunica[$fila['cara01campus_mediocomunica']]), ' '));
				}
			}
		if ($aBloque[4]){
			//Laboral
			$bBloque1=false;
			$bBloque2=false;
			$bBloque3=false;
			$bBloque4=false;
			$bBloque5=false;
			$bBloque6=false;
			if ($fila['cara01lab_situacion']==1){
				$bBloque1=true;
				$bBloque2=true;
				$bBloque3=true;
				$bBloque6=true;
				}
			if ($fila['cara01lab_situacion']==2){
				$bBloque1=true;
				$bBloque4=true;
				$bBloque6=true;
				}
			if ($fila['cara01lab_situacion']==3){
				$bBloque3=true;
				$bBloque5=true;
				$bBloque6=true;
				}
			if ($fila['cara01lab_situacion']==4){
				$bBloque5=true;
				$bBloque6=true;
				}
			$lin_cara01lab_situacion=$cSepara.'['.$fila['cara01lab_situacion'].']';
			if (isset($acara01lab_situacion[$fila['cara01lab_situacion']])!=0){
				$lin_cara01lab_situacion=$cSepara.utf8_decode($acara01lab_situacion[$fila['cara01lab_situacion']]);
				}
			if ($bBloque1){
				$lin_cara01lab_sector=$cSepara.'['.$fila['cara01lab_sector'].']';
				if (isset($acara01lab_sector[$fila['cara01lab_sector']])!=0){
					$lin_cara01lab_sector=$cSepara.utf8_decode($acara01lab_sector[$fila['cara01lab_sector']]);
					}
				$lin_cara01lab_caracterjuri=$cSepara.'['.$fila['cara01lab_caracterjuri'].']';
				if (isset($acara01lab_caracterjuri[$fila['cara01lab_caracterjuri']])!=0){
					$lin_cara01lab_caracterjuri=$cSepara.utf8_decode($acara01lab_caracterjuri[$fila['cara01lab_caracterjuri']]);
					}
				$lin_cara01lab_cargo=$cSepara.'['.$fila['cara01lab_cargo'].']';
				if (isset($acara01lab_cargo[$fila['cara01lab_cargo']])!=0){
					$lin_cara01lab_cargo=$cSepara.utf8_decode($acara01lab_cargo[$fila['cara01lab_cargo']]);
					}
				$lin_cara01lab_antiguedad=$cSepara.'['.$fila['cara01lab_antiguedad'].']';
				if (isset($acara01lab_antiguedad[$fila['cara01lab_antiguedad']])!=0){
					$lin_cara01lab_antiguedad=$cSepara.utf8_decode($acara01lab_antiguedad[$fila['cara01lab_antiguedad']]);
					}
				}
			if ($bBloque2){
				$lin_cara01lab_tipocontrato=$cSepara.'['.$fila['cara01lab_tipocontrato'].']';
				if (isset($acara01lab_tipocontrato[$fila['cara01lab_tipocontrato']])!=0){
					$lin_cara01lab_tipocontrato=$cSepara.utf8_decode($acara01lab_tipocontrato[$fila['cara01lab_tipocontrato']]);
					}
				}
			if ($bBloque3){
				$lin_cara01lab_rangoingreso=$cSepara.'['.$fila['cara01lab_rangoingreso'].']';
				if (isset($acara01lab_rangoingreso[$fila['cara01lab_rangoingreso']])!=0){
					$lin_cara01lab_rangoingreso=$cSepara.utf8_decode($acara01lab_rangoingreso[$fila['cara01lab_rangoingreso']]);
					}
				}
			$lin_cara01lab_tiempoacadem=$cSepara.'['.$fila['cara01lab_tiempoacadem'].']';
			if (isset($acara01lab_tiempoacadem[$fila['cara01lab_tiempoacadem']])!=0){
				$lin_cara01lab_tiempoacadem=$cSepara.utf8_decode($acara01lab_tiempoacadem[$fila['cara01lab_tiempoacadem']]);
				}
			if ($bBloque4){
				$lin_cara01lab_tipoempresa=$cSepara.'['.$fila['cara01lab_tipoempresa'].']';
				if (isset($acara01lab_tipoempresa[$fila['cara01lab_tipoempresa']])!=0){
					$lin_cara01lab_tipoempresa=$cSepara.utf8_decode($acara01lab_tipoempresa[$fila['cara01lab_tipoempresa']]);
					}
				$lin_cara01lab_tiempoindepen=$cSepara.'['.$fila['cara01lab_tiempoindepen'].']';
				if (isset($acara01lab_tiempoindepen[$fila['cara01lab_tiempoindepen']])!=0){
					$lin_cara01lab_tiempoindepen=$cSepara.utf8_decode($acara01lab_tiempoindepen[$fila['cara01lab_tiempoindepen']]);
					}
				}
			if ($bBloque5){
				$lin_cara01lab_debebusctrab=$cSepara.$fila['cara01lab_debebusctrab'];
				}
			if ($bBloque6){
				$lin_cara01lab_origendinero=$cSepara.'['.$fila['cara01lab_origendinero'].']';
				if (isset($acara01lab_origendinero[$fila['cara01lab_origendinero']])!=0){
					$lin_cara01lab_origendinero=$cSepara.utf8_decode($acara01lab_origendinero[$fila['cara01lab_origendinero']]);
					}
				}
			}
		if ($aBloque[5]){
			//Bienestar
			$lin_cara01bien_baloncesto=$cSepara.$fila['cara01bien_baloncesto'];
			$lin_cara01bien_voleibol=$cSepara.$fila['cara01bien_voleibol'];
			$lin_cara01bien_futbolsala=$cSepara.$fila['cara01bien_futbolsala'];
			$lin_cara01bien_artesmarc=$cSepara.$fila['cara01bien_artesmarc'];
			$lin_cara01bien_tenisdemesa=$cSepara.$fila['cara01bien_tenisdemesa'];
			$lin_cara01bien_ajedrez=$cSepara.$fila['cara01bien_ajedrez'];
			$lin_cara01bien_juegosautoc=$cSepara.$fila['cara01bien_juegosautoc'];
			$lin_cara01bien_interesrepdeporte=$cSepara.$fila['cara01bien_interesrepdeporte'];
			$lin_cara01bien_deporteint=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila['cara01bien_deporteint']), ' '));
			$lin_cara01bien_teatro=$cSepara.$fila['cara01bien_teatro'];
			$lin_cara01bien_danza=$cSepara.$fila['cara01bien_danza'];
			$lin_cara01bien_musica=$cSepara.$fila['cara01bien_musica'];
			$lin_cara01bien_circo=$cSepara.$fila['cara01bien_circo'];
			$lin_cara01bien_artplast=$cSepara.$fila['cara01bien_artplast'];
			$lin_cara01bien_cuenteria=$cSepara.$fila['cara01bien_cuenteria'];
			$lin_cara01bien_interesreparte=$cSepara.$fila['cara01bien_interesreparte'];
			$lin_cara01bien_arteint=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($fila['cara01bien_arteint']), ' '));
			if ($fila['cara01bien_interpreta']==-1){
				$lin_cara01bien_interpreta=$cSepara.'Ninguno';
				}else{
				$lin_cara01bien_interpreta=$cSepara.'['.$fila['cara01bien_interpreta'].']';
				if (isset($acara01bien_interpreta[$fila['cara01bien_interpreta']])!=0){
					$lin_cara01bien_interpreta=$cSepara.$acara01bien_interpreta[$fila['cara01bien_interpreta']];
					}
				$lin_cara01bien_nivelinter=$cSepara.'['.$fila['cara01bien_nivelinter'].']';
				if (isset($acara01bien_nivelinter[$fila['cara01bien_nivelinter']])!=0){
					$lin_cara01bien_nivelinter=$cSepara.$acara01bien_nivelinter[$fila['cara01bien_nivelinter']];
					}
				}
			$lin_cara01bien_danza_mod=$cSepara.$fila['cara01bien_danza_mod'];
			$lin_cara01bien_danza_clas=$cSepara.$fila['cara01bien_danza_clas'];
			$lin_cara01bien_danza_cont=$cSepara.$fila['cara01bien_danza_cont'];
			$lin_cara01bien_danza_folk=$cSepara.$fila['cara01bien_danza_folk'];
			$lin_cara01bien_niveldanza=$cSepara.'['.$fila['cara01bien_niveldanza'].']';
			if (isset($acara01bien_niveldanza[$fila['cara01bien_niveldanza']])!=0){
				$lin_cara01bien_niveldanza=$cSepara.utf8_decode($acara01bien_niveldanza[$fila['cara01bien_niveldanza']]);
				}
			$lin_cara01bien_emprendedor=$cSepara.$fila['cara01bien_emprendedor'];
			$lin_cara01bien_nombreemp=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01bien_nombreemp']));
			$lin_cara01bien_capacempren=$cSepara.$fila['cara01bien_capacempren'];
			if (isset($acara01bien_capacempren[$fila['cara01bien_capacempren']])!=0){
				$lin_cara01bien_capacempren=$cSepara.utf8_decode($acara01bien_capacempren[$fila['cara01bien_capacempren']]);
				}
			$lin_cara01bien_tipocapacita=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01bien_tipocapacita']));
			$lin_cara01bien_impvidasalud=$cSepara.$fila['cara01bien_impvidasalud'];
			if (isset($acara01bien_impvidasalud[$fila['cara01bien_impvidasalud']])!=0){
				$lin_cara01bien_impvidasalud=$cSepara.utf8_decode($acara01bien_impvidasalud[$fila['cara01bien_impvidasalud']]);
				}
			$lin_cara01bien_estraautocuid=$cSepara.$fila['cara01bien_estraautocuid'];
			if (isset($acara01bien_estraautocuid[$fila['cara01bien_estraautocuid']])!=0){
				$lin_cara01bien_estraautocuid=$cSepara.utf8_decode($acara01bien_estraautocuid[$fila['cara01bien_estraautocuid']]);
				}
			$lin_cara01bien_pv_personal=$cSepara.$fila['cara01bien_pv_personal'];
			if (isset($acara01bien_pv_personal[$fila['cara01bien_pv_personal']])!=0){
				$lin_cara01bien_pv_personal=$cSepara.utf8_decode($acara01bien_pv_personal[$fila['cara01bien_pv_personal']]);
				}
			$lin_cara01bien_pv_familiar=$cSepara.$fila['cara01bien_pv_familiar'];
			$lin_cara01bien_pv_academ=$cSepara.$fila['cara01bien_pv_academ'];
			$lin_cara01bien_pv_labora=$cSepara.$fila['cara01bien_pv_labora'];
			$lin_cara01bien_pv_pareja=$cSepara.$fila['cara01bien_pv_pareja'];
			$lin_cara01bien_amb=$cSepara.$fila['cara01bien_amb'];
			if (isset($acara01bien_amb[$fila['cara01bien_amb']])!=0){
				$lin_cara01bien_amb=$cSepara.utf8_decode($acara01bien_amb[$fila['cara01bien_amb']]);
				}
			$lin_cara01bien_amb_agu=$cSepara.$fila['cara01bien_amb_agu'];
			$lin_cara01bien_amb_bom=$cSepara.$fila['cara01bien_amb_bom'];
			$lin_cara01bien_amb_car=$cSepara.$fila['cara01bien_amb_car'];
			$lin_cara01bien_amb_info=$cSepara.$fila['cara01bien_amb_info'];
			$lin_cara01bien_amb_temas=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01bien_amb_temas']));
			}
		/*
			if (isset($a4567[$fila['4567']])!=0){
				$lin_4567=$cSepara.utf8_decode($a4567[$fila['4567']]);
				}
		*/
		if ($aBloque[6]){
			//Psicologia.
			$lin_cara01psico_costoemocion=$cSepara.'['.$fila['cara01psico_costoemocion'].']';
			if (isset($aCAEN[$fila['cara01psico_costoemocion']])!=0){
				$lin_cara01psico_costoemocion=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($aCAEN[$fila['cara01psico_costoemocion']]), ' '));
				}
			$lin_cara01psico_reaccionimpre=$cSepara.'['.$fila['cara01psico_reaccionimpre'].']';
			if (isset($apsico_reaccionimpre[$fila['cara01psico_reaccionimpre']])!=0){
				$lin_cara01psico_reaccionimpre=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($apsico_reaccionimpre[$fila['cara01psico_reaccionimpre']]), ' '));
				}
			$lin_cara01psico_estres=$cSepara.'['.$fila['cara01psico_estres'].']';
			if (isset($apsico_estres[$fila['cara01psico_estres']])!=0){
				$lin_cara01psico_estres=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($apsico_estres[$fila['cara01psico_estres']]), ' '));
				}
			$lin_cara01psico_pocotiempo=$cSepara.'['.$fila['cara01psico_pocotiempo'].']';
			if (isset($apsico_pocotiempo[$fila['cara01psico_pocotiempo']])!=0){
				$lin_cara01psico_pocotiempo=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($apsico_pocotiempo[$fila['cara01psico_pocotiempo']]), ' '));
				}
			$lin_cara01psico_actitudvida=$cSepara.'['.$fila['cara01psico_actitudvida'].']';
			if (isset($apsico_actitudvida[$fila['cara01psico_actitudvida']])!=0){
				$lin_cara01psico_actitudvida=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($apsico_actitudvida[$fila['cara01psico_actitudvida']]), ' '));
				}
			$lin_cara01psico_duda=$cSepara.'['.$fila['cara01psico_duda'].']';
			if (isset($apsico_duda[$fila['cara01psico_duda']])!=0){
				$lin_cara01psico_duda=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($apsico_duda[$fila['cara01psico_duda']]), ' '));
				}
			$lin_cara01psico_problemapers=$cSepara.'['.$fila['cara01psico_problemapers'].']';
			if (isset($apsico_problemapers[$fila['cara01psico_problemapers']])!=0){
				$lin_cara01psico_problemapers=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($apsico_problemapers[$fila['cara01psico_problemapers']]), ' '));
				}
			$lin_cara01psico_satisfaccion=$cSepara.'['.$fila['cara01psico_satisfaccion'].']';
			if (isset($apsico_satisfaccion[$fila['cara01psico_satisfaccion']])!=0){
				$lin_cara01psico_satisfaccion=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($apsico_satisfaccion[$fila['cara01psico_satisfaccion']]), ' '));
				}
			$lin_cara01psico_discusiones=$cSepara.'['.$fila['cara01psico_discusiones'].']';
			if (isset($apsico_discusiones[$fila['cara01psico_discusiones']])!=0){
				$lin_cara01psico_discusiones=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($apsico_discusiones[$fila['cara01psico_discusiones']]), ' '));
				}
			$lin_cara01psico_atencion=$cSepara.'['.$fila['cara01psico_atencion'].']';
			if (isset($apsico_atencion[$fila['cara01psico_atencion']])!=0){
				$lin_cara01psico_atencion=$cSepara.str_replace($cSepara, $cComplementa, cadena_letrasynumeros(utf8_decode($apsico_atencion[$fila['cara01psico_atencion']]), ' '));
				}
			$lin_cara01psico_puntaje=$cSepara.f2301_NombrePuntaje('puntaje',$fila['cara01psico_puntaje']);
			}
		if ($aBloque[7]){
			if ($fila['cara01fichadigital']!=-1){$lin_cara01fichadigital=$cSepara.f2301_NombrePuntaje('digital',$fila['cara01niveldigital']);}
			if ($fila['cara01fichalectura']!=-1){$lin_cara01fichalectura=$cSepara.f2301_NombrePuntaje('lectura',$fila['cara01nivellectura']);}
			if ($fila['cara01ficharazona']!=-1){$lin_cara01ficharazona=$cSepara.f2301_NombrePuntaje('razona',$fila['cara01nivelrazona']);}
			if ($fila['cara01fichaingles']!=-1){$lin_cara01fichaingles=$cSepara.f2301_NombrePuntaje('ingles',$fila['cara01nivelingles']);}
			if ($fila['cara01fichafisica']!=-1){$lin_cara01fichafisica=$cSepara.f2301_NombrePuntaje('fisica',$fila['cara01nivelfisica']);}
			if ($fila['cara01fichaquimica']!=-1){$lin_cara01fichaquimica=$cSepara.f2301_NombrePuntaje('quimica',$fila['cara01nivelquimica']);}
			if ($fila['cara01fichabiolog']!=-1){$lin_cara01fichabiolog=$cSepara.f2301_NombrePuntaje('biolog',$fila['cara01nivelbiolog']);}
			}
		if ($bConConsejero){
			if ($fila['cara01idconsejero']==0){
				$lin_cara01idconsejero=$cSepara.'Sin asignar';
				}else{
			$iTer=$fila['cara01idconsejero'];
			if (isset($aSys11[$iTer]['doc'])==0){
				list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing'])=f1011_InfoParaPlano($iTer, $objDB);
				}
			//$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].
			$lin_cara01idconsejero=$cSepara.utf8_decode($aSys11[$iTer]['razon']);
				}
			}
		if (false){
		$i_cara01idperaca=$fila['cara01idperaca'];
		if (isset($acara01idperaca[$i_cara01idperaca])==0){
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$i_cara01idperaca.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$acara01idperaca[$i_cara01idperaca]=str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
				}else{
				$acara01idperaca[$i_cara01idperaca]='';
				}
			}
		$lin_cara01idperaca=utf8_decode($acara01idperaca[$i_cara01idperaca]);
		$lin_cara01fichaper=$cSepara.$fila['cara01fichaper'];
		$lin_cara01fichafam=$cSepara.$fila['cara01fichafam'];
		$lin_cara01fichaaca=$cSepara.$fila['cara01fichaaca'];
		$lin_cara01fichalab=$cSepara.$fila['cara01fichalab'];
		$lin_cara01fichabien=$cSepara.$fila['cara01fichabien'];
		$lin_cara01fichapsico=$cSepara.$fila['cara01fichapsico'];
		$lin_cara01nomciudad=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01nomciudad']));

		$iTer=$fila['cara01idconfirmadesp'];
		if (isset($aSys11[$iTer]['doc'])==0){
			list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing'])=f1011_InfoParaPlano($iTer, $objDB);
			}
		$lin_cara01idconfirmadesp=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_cara01fechaconfirmadesp=$cSepara.$fila['cara01fechaconfirmadesp'];
		$iTer=$fila['cara01idconfirmacr'];
		if (isset($aSys11[$iTer]['doc'])==0){
			list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing'])=f1011_InfoParaPlano($iTer, $objDB);
			}
		$lin_cara01idconfirmacr=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_cara01fechaconfirmacr=$cSepara.$fila['cara01fechaconfirmacr'];
		$iTer=$fila['cara01idconfirmadisc'];
		if (isset($aSys11[$iTer]['doc'])==0){
			list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing'])=f1011_InfoParaPlano($iTer, $objDB);
			}
		$lin_cara01idconfirmadisc=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
		$lin_cara01fechaconfirmadisc=$cSepara.$fila['cara01fechaconfirmadisc'];


		$lin_cara01niveldigital=$cSepara.$fila['cara01niveldigital'];
		$lin_cara01nivellectura=$cSepara.$fila['cara01nivellectura'];
		$lin_cara01nivelrazona=$cSepara.$fila['cara01nivelrazona'];
		$lin_cara01nivelingles=$cSepara.$fila['cara01nivelingles'];
		$lin_cara01fechainicio=$cSepara.$fila['cara01fechainicio'];
		$lin_cara01telefono1=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01telefono1']));
		$lin_cara01telefono2=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01telefono2']));
		$lin_cara01correopersonal=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01correopersonal']));
		$lin_cara01discsensorialotra=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01discsensorialotra']));
		$lin_cara01discfisicaotra=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01discfisicaotra']));
		$lin_cara01disccognitivaotra=$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['cara01disccognitivaotra']));
		$lin_cara01idcursocatedra=$cSepara.$fila['cara01idcursocatedra'];
		$lin_cara01idgrupocatedra=$cSepara.$fila['cara01idgrupocatedra'];
		$lin_cara01factordescper=$cSepara.$fila['cara01factordescper'];
		$lin_cara01factordescpsico=$cSepara.$fila['cara01factordescpsico'];
		$lin_cara01factordescinsti=$cSepara.$fila['cara01factordescinsti'];
		$lin_cara01factordescacad=$cSepara.$fila['cara01factordescacad'];
		$lin_cara01factordesc=$cSepara.$fila['cara01factordesc'];
		$lin_cara01desertor=$cSepara.$fila['cara01desertor'];
			//Termina el bloque 7
			}
		//'Tipo Caracterizacion'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Estudiante'.$cSepara.'Fecha encuesta'.$cSepara.'Edad'.$cSepara.'Sexo'.$cSepara.'Pais'.$cSepara.'Departamento'
			//.$cSepara.'Direccion'.$cSepara.'Estrato'.$cSepara.'Zona'.$cSepara.'Estado civil'.$cSepara.'Nombre del contacto'.$cSepara.'Parentezco del contacto'.$cSepara.'Celular del contacto'.$cSepara.'Correo del contacto'.$cSepara.'Zona'.$cSepara.'CEAD'.$cSepara.'Matricula en convenio'.$cSepara.'Raizal'.$cSepara.'Palenquero'.$cSepara.'Afrocolombiano'.$cSepara.'Otra comunidad negra'.$cSepara.'ROM'.$cSepara.'Indigena'.$cSepara.'Victima desplazado'
		$sBloque1=''.$lin_cara01tipocaracterizacion.$lin_cara01idtercero.$lin_unad11fechaultingreso.$lin_cara01fechaencuesta.$lin_cara01agnos
		.$lin_cara01sexo.$lin_cara44sexov1identidadgen.$lin_cara44sexov1orientasexo.$lin_cara01pais.$lin_cara01depto.$lin_cara01ciudad.$lin_cara01direccion.$lin_cara01estrato.$lin_cara01zonares.$lin_cara01estcivil.$lin_cara01nomcontacto.$lin_cara01parentezcocontacto
		.$lin_cara01idzona.$lin_cara01idcead.$lin_cara01idescuela.$lin_cara01idprograma.$lin_cara01matconvenio.$lin_cara01raizal.$lin_cara01palenquero
		.$lin_cara01afrocolombiano.$lin_cara01otracomunnegras.$lin_cara01rom.$lin_cara01indigenas.$lin_cara44campesinado.$lin_cara01victimadesplazado.$lin_cara01victimaacr.$lin_cara01inpecfuncionario.$lin_cara01inpecrecluso.$lin_cara01inpectiempocondena.$lin_cara01centroreclusion.$lin_cara01discsensorial
		.$lin_cara01discfisica.$lin_cara01disccognitiva.$lin_cara01perayuda.$lin_cara01perotraayuda.$lin_cara01discv2sensorial.$lin_cara02discv2intelectura.$lin_cara02discv2fisica.$lin_cara02discv2psico.$lin_cara02discv2sistemica.$lin_cara02discv2sistemicaotro.$lin_cara02discv2multiple.$lin_cara02discv2multipleotro.$lin_cara01discv2archivoorigen.$lin_cara01discv2trastornos.$lin_cara01discv2trastaprende.$lin_cara01discv2contalento.$lin_cara01discv2pruebacoeficiente.$lin_cara01discv2condicionmedica.$lin_cara01discv2condmeddet;
		$sBloque2=''.$lin_cara01fam_tipovivienda.$lin_cara01fam_vivecon.$lin_cara01fam_numpersgrupofam.
		$lin_cara01fam_hijos.$lin_cara44fam_madrecabeza.$lin_cara01fam_personasacargo.$lin_cara01fam_dependeecon.
		$lin_cara01fam_escolaridadpadre.$lin_cara01fam_escolaridadmadre.$lin_cara01fam_numhermanos.
		$lin_cara01fam_posicionherm.$lin_cara01fam_familiaunad;
		$sBloque3=''.$lin_cara01acad_tipocolegio.$lin_cara01acad_modalidadbach.$lin_cara01acad_estudioprev.
		$lin_cara01acad_ultnivelest.$lin_cara01acad_tiemposinest.$lin_cara01acad_obtubodiploma
		.$lin_cara01acad_hatomadovirtual.$lin_cara01acad_razonestudio.$lin_cara01acad_primeraopc.
		$lin_cara01acad_programagusto.$lin_cara01acad_razonunad.$lin_cara44acadhatenidorecesos.$lin_cara44acadrazonreceso.$lin_cara44acadrazonrecesodetalle.
		$lin_cara01campus_compescrito.$lin_cara01campus_portatil.$lin_cara01campus_tableta.$lin_cara01campus_telefono.$lin_cara01campus_energia.$lin_cara01campus_internetreside.$lin_cara01campus_expvirtual.$lin_cara01campus_ofimatica.$lin_cara01campus_foros.$lin_cara01campus_conversiones.$lin_cara01campus_usocorreo.$lin_cara44campus_usocorreounad.$lin_cara44campus_usocorreounadno.$lin_cara44campus_usocorreounadnodetalle.$lin_cara01campus_aprendtexto.$lin_cara01campus_aprendvideo.$lin_cara01campus_aprendmapas.$lin_cara01campus_aprendeanima.$lin_cara01campus_mediocomunica.$lin_cara44campus_medioactivunad.$lin_cara44campus_medioactivunaddetalle;
		$sBloque4=''.$lin_cara01lab_situacion.$lin_cara01lab_sector.$lin_cara01lab_caracterjuri.$lin_cara01lab_cargo.$lin_cara01lab_antiguedad.$lin_cara01lab_tipocontrato.$lin_cara01lab_rangoingreso.$lin_cara01lab_tiempoacadem.$lin_cara01lab_tipoempresa.$lin_cara01lab_tiempoindepen.$lin_cara01lab_debebusctrab.$lin_cara01lab_origendinero;
		$sBloque5=''.$lin_cara01bien_baloncesto.$lin_cara01bien_voleibol.$lin_cara01bien_futbolsala.
		$lin_cara01bien_artesmarc.$lin_cara01bien_tenisdemesa.$lin_cara01bien_ajedrez.
		$lin_cara01bien_juegosautoc.$lin_cara01bien_interesrepdeporte.$lin_cara01bien_deporteint.$lin_cara01bien_teatro.
		$lin_cara01bien_danza.$lin_cara01bien_musica.$lin_cara01bien_circo.
		$lin_cara01bien_artplast.$lin_cara01bien_cuenteria.$lin_cara01bien_interesreparte.$lin_cara01bien_arteint.
		$lin_cara01bien_interpreta.$lin_cara01bien_nivelinter.$lin_cara01bien_danza_mod.
		$lin_cara01bien_danza_clas.$lin_cara01bien_danza_cont.$lin_cara01bien_danza_folk.
		$lin_cara01bien_emprendedor.$lin_cara01bien_nombreemp.$lin_cara01bien_capacempren.
		$lin_cara01bien_impvidasalud.$lin_cara01bien_estraautocuid.$lin_cara01bien_pv_personal.
		$lin_cara01bien_amb.$lin_cara01bien_amb_agu.$lin_cara01bien_amb_bom.
		$lin_cara01bien_amb_car.$lin_cara01bien_amb_info.
		$lin_cara44bienv2altoren.$lin_cara44bienv2atletismo.$lin_cara44bienv2baloncesto.$lin_cara44bienv2futbol.$lin_cara44bienv2gimnasia.$lin_cara44bienv2natacion.$lin_cara44bienv2voleibol.$lin_cara44bienv2tenis.$lin_cara44bienv2paralimpico.$lin_cara44bienv2otrodeporte.$lin_cara44bienv2otrodeportedetalle.
		$lin_cara44bienv2activdanza.$lin_cara44bienv2activmusica.$lin_cara44bienv2activteatro.$lin_cara44bienv2activartes.$lin_cara44bienv2activliteratura.$lin_cara44bienv2activculturalotra.$lin_cara44bienv2activculturalotradetalle.$lin_cara44bienv2evenfestfolc.$lin_cara44bienv2evenexpoarte.$lin_cara44bienv2evenhistarte.$lin_cara44bienv2evengalfoto.$lin_cara44bienv2evenliteratura.$lin_cara44bienv2eventeatro.$lin_cara44bienv2evencine.$lin_cara44bienv2evenculturalotro.$lin_cara44bienv2evenculturalotrodetalle.
		$lin_cara44bienv2emprendimiento.$lin_cara44bienv2empresa.$lin_cara44bienv2emprenrecursos.$lin_cara44bienv2emprenconocim.$lin_cara44bienv2emprenplan.$lin_cara44bienv2emprenejecutar.$lin_cara44bienv2emprenfortconocim.$lin_cara44bienv2emprenidentproblema.$lin_cara44bienv2emprenotro.$lin_cara44bienv2emprenotrodetalle.$lin_cara44bienv2emprenmarketing.$lin_cara44bienv2emprenplannegocios.$lin_cara44bienv2emprenideas.$lin_cara44bienv2emprencreacion.
		$lin_cara44bienv2saludfacteconom.$lin_cara44bienv2saludpreocupacion.$lin_cara44bienv2saludconsumosust.$lin_cara44bienv2saludinsomnio.$lin_cara44bienv2saludclimalab.$lin_cara44bienv2saludalimenta.$lin_cara44bienv2saludemocion.$lin_cara44bienv2saludestado.$lin_cara44bienv2saludmedita.
		$lin_cara44bienv2crecimedusexual.$lin_cara44bienv2crecimcultciudad.$lin_cara44bienv2crecimrelpareja.$lin_cara44bienv2crecimrelinterp.$lin_cara44bienv2crecimdinamicafam.$lin_cara44bienv2crecimautoestima.$lin_cara44bienv2creciminclusion.$lin_cara44bienv2creciminteliemoc.$lin_cara44bienv2crecimcultural.$lin_cara44bienv2crecimartistico.$lin_cara44bienv2crecimdeporte.$lin_cara44bienv2crecimambiente.$lin_cara44bienv2crecimhabsocio.
		$lin_cara44bienv2ambienbasura.$lin_cara44bienv2ambienreutiliza.$lin_cara44bienv2ambienluces.$lin_cara44bienv2ambienfrutaverd.$lin_cara44bienv2ambienenchufa.$lin_cara44bienv2ambiengrifo.$lin_cara44bienv2ambienbicicleta.$lin_cara44bienv2ambientranspub.$lin_cara44bienv2ambienducha.$lin_cara44bienv2ambiencaminata.$lin_cara44bienv2ambiensiembra.$lin_cara44bienv2ambienconferencia.$lin_cara44bienv2ambienrecicla.$lin_cara44bienv2ambienotraactiv.$lin_cara44bienv2ambienotraactivdetalle.
		$lin_cara44bienv2ambienreforest.$lin_cara44bienv2ambienmovilidad.$lin_cara44bienv2ambienclimatico.$lin_cara44bienv2ambienecofemin.$lin_cara44bienv2ambienbiodiver.$lin_cara44bienv2ambienecologia.$lin_cara44bienv2ambieneconomia.$lin_cara44bienv2ambienrecnatura.$lin_cara44bienv2ambienreciclaje.$lin_cara44bienv2ambienmascota.$lin_cara44bienv2ambiencartohum.$lin_cara44bienv2ambienespiritu.$lin_cara44bienv2ambiencarga.$lin_cara44bienv2ambienotroenfoq.$lin_cara44bienv2ambienotroenfoqdetalle;
		$sBloque6=$lin_cara01psico_costoemocion.$lin_cara01psico_reaccionimpre.$lin_cara01psico_estres.$lin_cara01psico_pocotiempo
		.$lin_cara01psico_actitudvida.$lin_cara01psico_duda.$lin_cara01psico_problemapers.$lin_cara01psico_satisfaccion.$lin_cara01psico_discusiones.$lin_cara01psico_atencion.$lin_cara01psico_puntaje;
		$sBloque7=$lin_cara01fichadigital.$lin_cara01fichalectura.$lin_cara01ficharazona.$lin_cara01fichaingles.$lin_cara01fichafisica.$lin_cara01fichaquimica.$lin_cara01fichabiolog;
		$sBloqueConsejero='';
		if ($bConConsejero){
			$sBloqueConsejero=$lin_cara01idconsejero;
			}
		if ($bPorTipo){
			$sB=$sBloque1;
			if ($aBloque[2]){$sB=$sB.$sBloque2;}
			if ($aBloque[3]){$sB=$sB.$sBloque3;}
			if ($aBloque[4]){$sB=$sB.$sBloque4;}
			if ($aBloque[5]){$sB=$sB.$sBloque5;}
			if ($aBloque[6]){$sB=$sB.$sBloque6;}
			if ($aBloque[7]){$sB=$sB.$sBloque7;}
			$objplano->AdicionarLinea($sB.$sBloqueConsejero);
			}else{
			$objplano->AdicionarLinea($sBloque1.$sBloque2.$sBloque3.$sBloque4.$sBloque5.$sBloque6.$sBloque7.$sBloqueConsejero);
			}
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