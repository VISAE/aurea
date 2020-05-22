<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.21.0 viernes, 22 de junio de 2018
---
--- Cambios 22 de mayo de 2020
--- 1. interpretación cualitativa de los campos de país, ciudad, puntaje factores y pruebas
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
require '../config.php';
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
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($iReporte==2301){$bEntra=true;}
if ($iReporte==2350){$bEntra=true;}
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
if ($bEntra){
	$idTercero=$_SESSION['unad_id_tercero'];
	$iCodModulo=2350;
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
	require $mensajes_todas;
	require $mensajes_2301;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t2301.csv';
	$sTituloRpt='consolidado_caracterizacion';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sNomPeraca='{'.$_REQUEST['v3'].'}';
	$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$_REQUEST['v3'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sNomPeraca=$fila['exte02nombre'];
		}
	$bConConsejero=true;
	$sDato=utf8_decode('Consolidado de caracterización '.$sNomPeraca);
	$objplano->AdicionarLinea($sDato);
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
	if (false){
	$acara01inpectiempocondena=array();
	$acara01fam_tipovivienda=array();
	$acara01fam_vivecon=array();
	$acara01fam_numpersgrupofam=array();
	$acara01fam_hijos=array();
	$acara01fam_personasacargo=array();
	$acara01fam_escolaridadpadre=array();
	$acara01fam_escolaridadmadre=array();
	$acara01fam_numhermanos=array();
	$acara01fam_posicionherm=array();
	$acara01acad_tipocolegio=array();
	$acara01acad_modalidadbach=array();
	$acara01acad_ultnivelest=array();
	$acara01acad_tiemposinest=array();
	$acara01campus_energia=array();
	$acara01campus_internetreside=array();
	$acara01campus_usocorreo=array();
	$acara01campus_mediocomunica=array();
	$acara01lab_situacion=array();
	$acara01lab_sector=array();
	$acara01lab_caracterjuri=array();
	$acara01lab_cargo=array();
	$acara01lab_antiguedad=array();
	$acara01lab_tipocontrato=array();
	$acara01lab_rangoingreso=array();
	$acara01lab_tiempoacadem=array();
	$acara01lab_tipoempresa=array();
	$acara01lab_tiempoindepen=array();
	$acara01lab_origendinero=array();
	$acara01bien_interpreta=array();
	$acara01bien_nivelinter=array();
	$acara01bien_niveldanza=array();
	$acara01criteriodesc=array();
	$acara01factorprincipaldesc=array();
	$acara37discapacidades=array();
		}
	$aSys11=array();
	$sTitulo1='Datos personales';
	for ($l=1;$l<=48;$l++){
		$sSubTitulo='';
		if ($l==22){$sSubTitulo='Grupos poblacionales';}
		if ($l==34){$sSubTitulo='Discapacidades';}
		$sTitulo1=$sTitulo1.$cSepara.$sSubTitulo;
		}
	$sBloque1=''.utf8_decode('Tipo Caracterización').$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Estudiante'.$cSepara.'Ultimo Acceso Campus'.$cSepara.'Fecha encuesta'.$cSepara.'Edad'.$cSepara
.'Sexo'.$cSepara.'Pais'.$cSepara.'Departamento'.$cSepara.'Ciudad'.$cSepara.'Direccion'.$cSepara.'Estrato'.$cSepara.'Zona de residencia'.$cSepara
.'Estado civil'.$cSepara.'Nombre del contacto'.$cSepara.'Parentezco del contacto'.$cSepara
.'Zona'.$cSepara.'CEAD'.$cSepara.'Escuela'.$cSepara.'Programa'.$cSepara.'Matricula en convenio'.$cSepara.'Raizal'.$cSepara.'Palenquero'.$cSepara
.'Afrocolombiano'.$cSepara.'Otra comunidad negra'.$cSepara.'ROM'.$cSepara.'Indigena'.$cSepara.'Victima desplazado'.$cSepara
.'Victima ACR'.$cSepara.'Funcionario INPEC'.$cSepara.'Recluso INPEC'.$cSepara.'Tiempo de condena'.$cSepara.'Centro de reclusión'.$cSepara.'Sensorial'.$cSepara.'Fisica'.$cSepara.'Cognitiva'.$cSepara.'Ajustes razonables'.$cSepara.'Ajustes razonables Otra Ayuda'.$cSepara
.'Sensorial v2'.$cSepara.'Intelectual'.$cSepara.'Física v2'.$cSepara.'Mental Psicosocial'.$cSepara.'Sistémica'.$cSepara.'Sistémica Otro'.$cSepara.'Múltiple'.$cSepara.'Múltiple Otro'.$cSepara.'Talento Excepcional'.'';
	$sTitulo2='Datos familiares';
	for ($l=1;$l<=11;$l++){
		$sTitulo2=$sTitulo2.$cSepara;
		}
	$sBloque2=''.$cSepara.utf8_decode('Cuál es su tipo de vivienda actual'.$cSepara.'Con quién vive actualmente'.$cSepara.'Cuántas personas conforman su grupo familiar incluyéndolo a usted'.$cSepara.'Cuántos hijos tiene'.$cSepara.'Cuántas personas tiene a su cargo'.$cSepara.'Usted depende económicamente de alguien'.$cSepara.'Cuál es el máximo nivel de escolaridad de su padre'.$cSepara.'Cuál es el máximo nivel de escolaridad de su madre'.$cSepara.'Cuántos hermanos tiene'.$cSepara.'Cuál es la posición entre sus hermanos'.$cSepara.'Usted tiene familiares estudiando actualmente o que hayan estudiado en la UNAD');
	$sTitulo3=utf8_decode('Datos académicos');
	for ($l=1;$l<=27;$l++){
		$sSubTitulo='';
		if ($l==11){$sSubTitulo=utf8_decode('Con cuáles equipos electrónicos cuenta para acceder al campus virtual de la UNAD');}
		if ($l==22){$sSubTitulo=utf8_decode('La información que consulta la aprende mejor con');}
		$sTitulo3=$sTitulo3.$cSepara.$sSubTitulo;
		}
	$sBloque3=''.$cSepara.utf8_decode('Tipo de colegio donde terminó su bachillerato'.$cSepara.'La modalidad en la que obtuvo su grado de bachiller es'.$cSepara.'Usted ha realizado otros estudios antes de llegar a la UNAD'
.$cSepara.'Cuál fue el último nivel de estudios cursado'.$cSepara.'Cuánto tiempo lleva sin estudiar'.$cSepara.'Obtuvo certificación o diploma de estos estudios'
.$cSepara.'Usted ha tomado cursos virtuales'.$cSepara.'Cuál es la principal razón para elegir el programa académico en el que se matriculó'.$cSepara.'El programa en el que se matriculó representa su primera opción'.$cSepara.'Por favor indique el programa que le hubiera gustado estudiar.'
.$cSepara.'Cuál es la principal razón para estudiar en la UNAD'.$cSepara.'Computador de escritorio'.$cSepara.'Computador portátil'
.$cSepara.'Tableta'.$cSepara.'Teléfono inteligente'.$cSepara.'El lugar donde reside cuenta con servicio de energía eléctrica'
.$cSepara.'El lugar donde reside cuenta con servicio de Internet'.$cSepara.'Ha usado plataformas virtuales con anterioridad'.$cSepara.'Maneja paquetes ofimáticos como Office (Word Excel Powerpoint) o similares'
.$cSepara.'Ha participado en foros virtuales'.$cSepara.'Sabe convertir archivos digitales de un formato a otro'.$cSepara.'Su uso del correo electrónico es'
.$cSepara.'Texto'.$cSepara.'Vídeo'.$cSepara.'Organizadores gráficos'
.$cSepara.'Animaciones'.$cSepara.'Cuál es el medio que más utiliza para comunicarse con amigos. conocidos. familiares o docentes a través de internet');
	$sTitulo4='Datos laborales';
	for ($l=1;$l<=12;$l++){
		$sTitulo4=$sTitulo4.$cSepara;
		}
	$sBloque4=''.$cSepara.utf8_decode('Cuál es su situación laboral actual'.$cSepara.'A qué sector económico pertenece'.$cSepara.'Cuál es el carácter jurídico de la empresa'
.$cSepara.'Cuál es el cargo que ocupa'.$cSepara.'Cuál es su antigüedad en el cargo actual'.$cSepara.'Qué tipo de contrato tiene actualmente'
.$cSepara.'Cuánto suman sus ingresos mensuales'.$cSepara.'Con qué tiempo cuenta para desarrollar las actividades académicas'.$cSepara.'Qué tipo de empresa es'
.$cSepara.'Hace cuánto tiempo constituyó su empresa'.$cSepara.'Debe buscar trabajo para continuar sus estudios en la UNAD'.$cSepara.'De dónde provienen los recursos económicos con los que usted estudiará en la UNAD');
	$sTitulo5='Bienestar';
	for ($l=1;$l<=34;$l++){
		$sSubTitulo='';
		if ($l==1){$sSubTitulo=utf8_decode('Usted practica regularmente alguna de las siguientes actividades deportivas o recreativas');}
		if ($l==9){$sSubTitulo=utf8_decode('Usted practica regularmente alguna de las siguientes actividades artísticas o culturales');}
		if ($l==19){$sSubTitulo=utf8_decode('Sí usted practica danza por favor indique el género');}
		if ($l==23){$sSubTitulo=utf8_decode('Emprendimiento');}
		if ($l==26){$sSubTitulo=utf8_decode('Estilo de vida saludable');}
		if ($l==28){$sSubTitulo=utf8_decode('Proyecto de vida');}
		if ($l==29){$sSubTitulo=utf8_decode('Medio ambiente');}
		if ($l==30){$sSubTitulo=utf8_decode('Cuál de estos hábitos cotidianos realiza usted como una práctica de respeto hacia Medio Ambiente');}
		$sTitulo5=$sTitulo5.$cSepara.$sSubTitulo;
		}
	$sBloque5=''.$cSepara.utf8_decode('Baloncesto'.$cSepara.'Voleibol'.$cSepara.'Futbol sala'.
$cSepara.'Artes marciales'.$cSepara.'Tenis de mesa'.$cSepara.'Ajedrez'.
$cSepara.'Juegos autóctonos'.$cSepara.'Está interesado en hacer parte de un grupo representativo en deportes'.$cSepara.'Especifique a cuál grupo deportivo'.$cSepara.'Teatro'.
$cSepara.'Danza'.$cSepara.'Música'.$cSepara.'Circo'.
$cSepara.'Artes plásticas'.$cSepara.'Cuentería'.$cSepara.'Está interesado en hacer parte de un grupo representativo en artes y cultura'.$cSepara.'Seleccione en cuál'.
$cSepara.'Sí usted interpreta un instrumento musical por favor selecciónelo'.$cSepara.'En escala de 1 a 10 su dominio del instrumento musical es'.$cSepara.'Ritmos modernos (Salsa - Bachata)'.
$cSepara.'Danza clásica'.$cSepara.'Danza contemporánea'.$cSepara.'Danza folklorica colombiana'
.$cSepara.'Cuenta Ud. con una empresa que de respuesta a una necesidad social en su comunidad'.$cSepara.'Qué necesidad cubre'.$cSepara.'En qué temas de emprendimiento le gustaría recibir capacitación'
.$cSepara.'Cuáles cree que son las causas más frecuentes del estrés'.$cSepara.'A través de que estrategias le gustaría conocer el autocuidado'.$cSepara.'Qué temas le gustaría abordar en la UNAD para su crecimiento personal'
.$cSepara.'Cómo define la educación ambiental'.$cSepara.'Ahorras de agua en la ducha y/o al cepillarse'.$cSepara.'Usas bombillas ahorradoras'
.$cSepara.'Desconectas el cargador del celular cuando no esta en uso'.$cSepara.'Apagas las luces que no se requieran');
	$sTitulo6='Psicosocial';
	for ($l=1;$l<=11;$l++){
		$sTitulo6=$sTitulo6.$cSepara;
		}
	$sBloque6=''.$cSepara.utf8_decode('Le cuesta expresar sus emociones con palabras'.$cSepara.'Cómo reacciona ante un cambio imprevisto aparentemente negativo'.$cSepara.'Cuando está estresado o tienes varias preocupaciones ¿cómo lo maneja'
.$cSepara.'Cuando tiene poco tiempo para el desarrollo de sus actividades académicas laborales y familiares ¿cómo lo asume?'.$cSepara.'Con respecto a su actitud frente la vida ¿cómo se describiría?'.$cSepara.'Qué hace cuando presenta alguna dificultad o duda frente a una tarea asignada'
.$cSepara.'Cuando está afrontando una dificultad personal laboral emocional o familiar ¿cuál es su actitud?'.$cSepara.'En términos generales ¿está satisfecho con quién es?'.$cSepara.'Cómo actúa frente a una discusión'
.$cSepara.'Cómo reacciona ante las siguientes situaciones sociales'.$cSepara.'Puntaje');
	$sTitulo7='Competencias';
	for ($l=1;$l<=7;$l++){
		$sTitulo7=$sTitulo7.$cSepara;
		}
	$sBloque7=''.$cSepara.'Competencias digitales'.$cSepara.utf8_decode('Lectura crítica'.$cSepara.'Razonamiento cuantitativo'.$cSepara.'Inglés'.$cSepara.'Biología'.$cSepara.'Física'.$cSepara.'Química');
			//cara01fichadigital='.$cara01fichadigital.', cara01fichalectura='.$cara01fichalectura.', cara01ficharazona='.$cara01ficharazona.', cara01fichaingles='.$cara01fichaingles.', cara01fichabiolog='.$cara01fichabiolog.', cara01fichafisica='.$cara01fichafisica.', cara01fichaquimica='.$cara01fichaquimica.'
	/*
	$sTitulo8='Titulo 8';
	for ($l=1;$l<=20;$l++){
		$sTitulo8=$sTitulo8.$cSepara;
		}
	$sBloque8=''.$cSepara.'Bien_pv_personal'.$cSepara.'Bien_pv_familiar'.$cSepara.'Bien_pv_academ'.$cSepara.'Bien_pv_labora'.$cSepara.'Bien_pv_pareja'.$cSepara
.'Bien_amb'.$cSepara.'Bien_amb_agu'.$cSepara.'Bien_amb_bom'.$cSepara.'Bien_amb_car'.$cSepara.'Bien_amb_info'.$cSepara
.'Bien_amb_temas'.$cSepara.'Psico_costoemocion'.$cSepara.'Psico_reaccionimpre'.$cSepara.'Psico_estres'.$cSepara.'Psico_pocotiempo'.$cSepara
.'Psico_actitudvida'.$cSepara.'Psico_duda'.$cSepara.'Psico_problemapers'.$cSepara.'Psico_satisfaccion'.$cSepara.'Psico_discusiones';
	$sTitulo9='Titulo 9';
	for ($l=1;$l<=9;$l++){
		$sTitulo9=$sTitulo9.$cSepara;
		}
	$sBloque9=''.$cSepara.'Psico_atencion'.$cSepara.'Niveldigital'.$cSepara.'Nivellectura'.$cSepara.'Nivelrazona'.$cSepara.'Nivelingles'.$cSepara
.'TD'.$cSepara.'Doc'.$cSepara.'Consejero'.$cSepara.'Fechainicio';
	*/
	$sWhere='';
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
					$sWhere=$sWhere.' AND TB.cara01idconsejero='.$_SESSION['unad_id_tercero'].' ';
					}
				}else{
				//Puede ver lo suyo....
				$sWhere=$sWhere.' AND TB.cara01idconsejero='.$_SESSION['unad_id_tercero'].' ';
				}
			}
		}else{
		$sWhere=$sWhere.' AND TB.cara01idconsejero='.$_SESSION['unad_id_tercero'].' ';
		$bConConsejero=true;
		}
	if ($_REQUEST['v5']!=''){
		$sWhere=$sWhere.' AND TB.cara01idcead='.$_REQUEST['v5'].' ';
		}else{
		if ($_REQUEST['v4']!=''){
			$sWhere=$sWhere.' AND TB.cara01idzona='.$_REQUEST['v4'].' ';
			}
		}
	$bPorTipo=false;
	if ($_REQUEST['v6']!=''){
		$sWhere=$sWhere.' AND TB.cara01tipocaracterizacion='.$_REQUEST['v6'].' ';
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
			$objplano->AdicionarLinea(utf8_decode('Tipo de caracterización:'.$cSepara.$fila['cara11nombre']));
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
		$sWhere=$sWhere.' AND TB.cara01idtercero=T51.core51idtercero AND T51.core51idconvenio='.$_REQUEST['v8'].' AND T51.core51activo="S"';
		}
		
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
	if ($_REQUEST['v10']!=''){
		$sWhere=$sWhere.' AND TB.cara01idprograma='.$_REQUEST['v10'].' ';
		}else{
		if ($_REQUEST['v9']!=''){
			$sWhere=$sWhere.' AND TB.cara01idescuela='.$_REQUEST['v9'].' ';
			}
		}
	$sSQL='SELECT TB.* 
FROM cara01encuesta AS TB'.$sTablaConvenio.' 
WHERE TB.cara01idperaca='.$_REQUEST['v3'].''.$sWhere.' AND TB.cara01completa="S"';
	//$objplano->adlinea($sSQL);
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
			$lin_cara01idtercero=$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].$cSepara.utf8_decode($aSys11[$iTer]['razon']);
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
            if($fila['cara01discversion']==1){
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
				$lin_cara02talentoexcepcional=$cSepara.utf8_decode($acara02talentoexcepcional[$i_cara02talentoexcepcional]);
                }
			$bEntra=true;
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
		//'Tipo Caracterización'.$cSepara.'TD'.$cSepara.'Doc'.$cSepara.'Estudiante'.$cSepara.'Fecha encuesta'.$cSepara.'Edad'.$cSepara.'Sexo'.$cSepara.'Pais'.$cSepara.'Departamento'
			//.$cSepara.'Direccion'.$cSepara.'Estrato'.$cSepara.'Zona'.$cSepara.'Estado civil'.$cSepara.'Nombre del contacto'.$cSepara.'Parentezco del contacto'.$cSepara.'Celular del contacto'.$cSepara.'Correo del contacto'.$cSepara.'Zona'.$cSepara.'CEAD'.$cSepara.'Matricula en convenio'.$cSepara.'Raizal'.$cSepara.'Palenquero'.$cSepara.'Afrocolombiano'.$cSepara.'Otra comunidad negra'.$cSepara.'ROM'.$cSepara.'Indigena'.$cSepara.'Victima desplazado'
		$sBloque1=''.$lin_cara01tipocaracterizacion.$lin_cara01idtercero.$lin_unad11fechaultingreso.$lin_cara01fechaencuesta.$lin_cara01agnos
.$lin_cara01sexo.$lin_cara01pais.$lin_cara01depto.$lin_cara01ciudad.$lin_cara01direccion.$lin_cara01estrato.$lin_cara01zonares.$lin_cara01estcivil.$lin_cara01nomcontacto.$lin_cara01parentezcocontacto
.$lin_cara01idzona.$lin_cara01idcead.$lin_cara01idescuela.$lin_cara01idprograma.$lin_cara01matconvenio.$lin_cara01raizal.$lin_cara01palenquero
.$lin_cara01afrocolombiano.$lin_cara01otracomunnegras.$lin_cara01rom.$lin_cara01indigenas.$lin_cara01victimadesplazado.$lin_cara01victimaacr.$lin_cara01inpecfuncionario.$lin_cara01inpecrecluso.$lin_cara01inpectiempocondena.$lin_cara01centroreclusion.$lin_cara01discsensorial
.$lin_cara01discfisica.$lin_cara01disccognitiva.$lin_cara01perayuda.$lin_cara01perotraayuda.$lin_cara01discv2sensorial.$lin_cara02discv2intelectura.$lin_cara02discv2fisica.$lin_cara02discv2psico.$lin_cara02discv2sistemica.$lin_cara02discv2sistemicaotro.$lin_cara02discv2multiple.$lin_cara02discv2multipleotro.$lin_cara02talentoexcepcional;
		$sBloque2=''.$lin_cara01fam_tipovivienda.$lin_cara01fam_vivecon.$lin_cara01fam_numpersgrupofam.
$lin_cara01fam_hijos.$lin_cara01fam_personasacargo.$lin_cara01fam_dependeecon.
$lin_cara01fam_escolaridadpadre.$lin_cara01fam_escolaridadmadre.$lin_cara01fam_numhermanos.
$lin_cara01fam_posicionherm.$lin_cara01fam_familiaunad;
		$sBloque3=''.$lin_cara01acad_tipocolegio.$lin_cara01acad_modalidadbach.$lin_cara01acad_estudioprev.
$lin_cara01acad_ultnivelest.$lin_cara01acad_tiemposinest.$lin_cara01acad_obtubodiploma
.$lin_cara01acad_hatomadovirtual.$lin_cara01acad_razonestudio.$lin_cara01acad_primeraopc.
$lin_cara01acad_programagusto.$lin_cara01acad_razonunad.$lin_cara01campus_compescrito.$lin_cara01campus_portatil.$lin_cara01campus_tableta.$lin_cara01campus_telefono.$lin_cara01campus_energia.$lin_cara01campus_internetreside.$lin_cara01campus_expvirtual.$lin_cara01campus_ofimatica.$lin_cara01campus_foros.$lin_cara01campus_conversiones.$lin_cara01campus_usocorreo.$lin_cara01campus_aprendtexto.$lin_cara01campus_aprendvideo.$lin_cara01campus_aprendmapas.$lin_cara01campus_aprendeanima.$lin_cara01campus_mediocomunica;
		$sBloque4=''.$lin_cara01lab_situacion.$lin_cara01lab_sector.$lin_cara01lab_caracterjuri.$lin_cara01lab_cargo.$lin_cara01lab_antiguedad.$lin_cara01lab_tipocontrato.$lin_cara01lab_rangoingreso.$lin_cara01lab_tiempoacadem.$lin_cara01lab_tipoempresa.$lin_cara01lab_tiempoindepen.$lin_cara01lab_debebusctrab.$lin_cara01lab_origendinero;
		/*
.$cSepara.'Cómo define la educación ambiental'.$cSepara.'Ahorras de agua en la ducha y/o al cepillarse'.$cSepara.'Usas bombillas ahorradoras'
.$cSepara.'Desconectas el cargador del celular cuando no esta en uso'.$cSepara.'Apagas las luces que no se requieran'
		*/
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
$lin_cara01bien_amb_car.$lin_cara01bien_amb_info;
		$sBloque6=$lin_cara01psico_costoemocion.$lin_cara01psico_reaccionimpre.$lin_cara01psico_estres.$lin_cara01psico_pocotiempo
.$lin_cara01psico_actitudvida.$lin_cara01psico_duda.$lin_cara01psico_problemapers.$lin_cara01psico_satisfaccion.$lin_cara01psico_discusiones.$lin_cara01psico_atencion.$lin_cara01psico_puntaje;
		$sBloque7=$lin_cara01fichadigital.$lin_cara01fichalectura.$lin_cara01ficharazona.$lin_cara01fichaingles.$lin_cara01fichafisica.$lin_cara01fichaquimica.$lin_cara01fichabiolog;
		/*
		$sBloque7=''.$lin_cara01bien_niveldanza
.$lin_cara01bien_tipocapacita;
		$sBloque8=''.$lin_cara01bien_pv_familiar.$lin_cara01bien_pv_academ.$lin_cara01bien_pv_labora.$lin_cara01bien_pv_pareja
.$lin_cara01bien_amb_temas;
		$sBloque9=''.$lin_cara01niveldigital.$lin_cara01nivellectura.$lin_cara01nivelrazona.$lin_cara01nivelingles
.$lin_cara01idconsejero.$lin_cara01fechainicio;







		*/
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
	}
?>
