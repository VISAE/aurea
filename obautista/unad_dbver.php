<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Inicia Lunes, 5 de enero de 2025
--- Esta página se encarga de mantener actualizado los script de las bases de datos.
*/
$err_level = error_reporting(E_ALL);
error_reporting($err_level);
ini_set("display_errors", 1);
//ini_set("error_log", "/var/www/aurea_www/campus/panel/log.htm"); // unad florida
ini_set("error_log", "/var/www/panel/log.htm"); // campus colombia
set_time_limit(0);

require './app.php';
if (isset($APP->dbhost)==0){
	echo 'No se ha definido el servidor de base de datos';
	die();
	}
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'forma_dark.php';
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->dbmodelo) == 0){
	$APP->dbmodelo = 'M';
}
$versionejecutable = 8863;
$procesos=0;
$suspende=0;
$error=0;
$sError = '';
$xajax = null;
//FORMA
encabezado($xajax, 'UPD');
cuerpo();
//
echo 'Iniciando proceso de revision de la base de datos <b>[DB : '.$APP->dbname.']</b><br>';
$sSQL=$objDB->sSQLListaTablas('unad00config');
$result=$objDB->ejecutasql($sSQL);
$cant=$objDB->nf($result);
if ($cant<1){
	echo 'Debe ejecutar el script inicial<br>';
	die();		
} else {
	$sSQL="SELECT unad00valor FROM unad00config WHERE unad00codigo='dbversion';";
	$result=$objDB->ejecutasql($sSQL);
	$row=$objDB->sf($result);
	$dbversion=$row['unad00valor'];
	$bbloquea=false;
	if ($dbversion<8000){$bbloquea=true;}
	if ($dbversion>9000){$bbloquea=true;}
	if ($bbloquea){
		echo '<br>Debe ejecutar el script que corresponda a la version {'.$dbversion.'}...';
		die();		
	}
}
$sSQL = '';	
echo "Version Actual de la base de datos ".$dbversion.'<br>';
echo '<ul style="margin-top: 10px;">';
if (true){
	$u01="INSERT INTO unad01sistema (unad01id, unad01nombre, unad01descripcion, unad01publico, unad01instalado, unad01mayor, unad01menor, unad01correccion) VALUES ";
	$u03="INSERT INTO unad03permisos (unad03id, unad03nombre) VALUES ";
	$u04="INSERT INTO unad04modulopermisos (unad04idmodulo, unad04idpermiso, unad04vigente) VALUES ";
	$u05="INSERT INTO unad05perfiles (unad05id, unad05nombre) VALUES ";
	$u06="INSERT INTO unad06perfilmodpermiso (unad06idperfil, unad06idmodulo, unad06idpermiso, unad06vigente) VALUES ";
	$u08="INSERT INTO unad08grupomenu (unad08id, unad08nombre, unad08pagina, unad08titulo, unad08nombre_en, unad08nombre_pt) VALUES ";
	$u09="INSERT INTO unad09modulomenu (unad09idmodulo, unad09consec, unad09nombre, unad09pagina, unad09grupo, unad09orden, unad09movil, unad09nombre_en, unad09nombre_pt) VALUES ";
	$u22="INSERT INTO unad22combos (unad22idmodulo, unad22consec, unad22codopcion, unad22nombre, unad22orden, unad22activa) VALUES ";
	$u60='INSERT INTO unad60preferencias (unad60idmodulo, unad60codigo, unad60nombre, unad60tipo) VALUES ';
	$unad70='INSERT INTO unad70bloqueoelimina (unad70idtabla, unad70idtablabloquea, unad70origennomtabla, unad70origenidtabla, unad70origencamporev, unad70mensaje, unad70etiqueta) VALUES ';
	}
while ($dbversion<$versionejecutable){
$sSQL='';
if (($dbversion>8000)&&($dbversion<8101)){
	if ($dbversion==8001){$sSQL="agregamodulo|4539|45|Convocatorias (SUEV)|1|2|3|4|5|6|8";}
	if ($dbversion==8002){$sSQL=$u09."(4539, 1, 'Convocatorias', 'emprconvocatoria.php', 1, 4539, 'S', '', '')";}
	if ($dbversion==8003){$sSQL="CREATE TABLE empr70estadoconvoca (empr70id int NOT NULL, empr70nombre varchar(50) NULL)";}
	if ($dbversion==8004){$sSQL="ALTER TABLE empr70estadoconvoca ADD PRIMARY KEY(empr70id)";}
	if ($dbversion==8005){$sSQL="INSERT INTO empr70estadoconvoca (empr70id, empr70nombre) VALUES (0, 'Borrador'), (3, 'En desarrollo'), (4, 'En inscripciones'), (5, 'Cerrada'), (7, 'Terminada'), (9, 'Anulada')";}
	if ($dbversion==8006){$sSQL="add_campos|empr04emprendimiento|empr04idconvocatoria int NOT NULL DEFAULT 0";}
	if ($dbversion==8007){$sSQL="INSERT INTO empr39convocatoria (empr39consec, empr39id, empr39estado, empr39titulo, empr39fechainicio, empr39fechacierre, empr39detalle) VALUES (0, 0, 9, '{Ninguna}', 0, 0, '')";}
	if ($dbversion==8008){$sSQL="add_campos|plan44metamat|plan44ejec_nuevos int NOT NULL DEFAULT 0|plan44ejec_ant int NOT NULL DEFAULT 0";}
	if ($dbversion==8009){$sSQL="agregamodulo|2832|28|Cursos solicitados x programa|1|5|6";}
	if ($dbversion==8010){$sSQL=$u09."(2832, 1, 'Cursos solicitados por programa', 'rptsolcursoprog.php', 1701, 2832, 'S', '', '')";}
	if ($dbversion==8011){$sSQL="agregamodulo|4760|22|Cargue masivo de insc. MOOC|1|2|5|6";}
	if ($dbversion==8012){$sSQL=$u09."(4760, 1, 'Cargue masivo de inscripciones MOOC', 'corecargueinscmooc.php', 2201, 4760, 'S', '', '')";}
	if ($dbversion==8013){$sSQL="add_campos|ofes01preoferta|ofes01idareaconoce int NOT NULL DEFAULT 0|ofes01idcertificador int NOT NULL DEFAULT 0";}
	if ($dbversion==8014){$sSQL="add_campos|prac12rutaestudiante|prac12c31_codigo int NOT NULL DEFAULT 0|prac12c31_estado int NOT NULL DEFAULT 0|prac12c32_codigo int NOT NULL DEFAULT 0|prac12c32_estado int NOT NULL DEFAULT 0|prac12c33_codigo int NOT NULL DEFAULT 0|prac12c33_estado int NOT NULL DEFAULT 0|prac12c34_codigo int NOT NULL DEFAULT 0|prac12c34_estado int NOT NULL DEFAULT 0|prac12c35_codigo int NOT NULL DEFAULT 0|prac12c35_estado int NOT NULL DEFAULT 0|prac12c36_codigo int NOT NULL DEFAULT 0|prac12c36_estado int NOT NULL DEFAULT 0|prac12c37_codigo int NOT NULL DEFAULT 0|prac12c37_estado int NOT NULL DEFAULT 0|prac12c38_codigo int NOT NULL DEFAULT 0|prac12c38_estado int NOT NULL DEFAULT 0|prac12c39_codigo int NOT NULL DEFAULT 0|prac12c39_estado int NOT NULL DEFAULT 0|prac12c40_codigo int NOT NULL DEFAULT 0|prac12c40_estado int NOT NULL DEFAULT 0";}
	if ($dbversion==8015){$sSQL="add_campos|gcmo10valoracion|gcmo10detalle Text NULL|gcmo10idorigen int NOT NULL DEFAULT 0|gcmo10idarchivo int NOT NULL DEFAULT 0";}
	if ($dbversion==8016){$sSQL="ALTER TABLE gcmo11valorpta DROP COLUMN gcmo11detalle";}
	if ($dbversion==8017){$sSQL="ALTER TABLE gcmo11valorpta DROP COLUMN gcmo11idorigen";}
	if ($dbversion==8018){$sSQL="ALTER TABLE gcmo11valorpta DROP COLUMN gcmo11idarchivo";}
	if ($dbversion==8019){$sSQL="add_campos|gcmo11valorpta|gcmo11gestionado int NOT NULL DEFAULT 0|gcmo11fechagestionado int NOT NULL DEFAULT 0";}
	if ($dbversion==8020){$sSQL="agregamodulo|4729|22|Ruta SISSU Individual|1|2|3|4|5|6|8";}
	if ($dbversion==8021){$sSQL=$u09."(4729, 1, 'Ruta SISSU Individual', 'corerutasisuindividual.php', 7, 4729, 'S', '', '')";}
	if ($dbversion==8022){$sSQL=$u04."(2266, 10, 'S')";}
	if ($dbversion==8023){$sSQL="CREATE TABLE olab64simulgrupo (olab64idsimulador int NOT NULL, olab64idasigna int NOT NULL, olab64numgrupo int NOT NULL, olab64id int NOT NULL DEFAULT 0, olab64correogrupo varchar(50) NULL, olab64numcupo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8024){$sSQL="ALTER TABLE olab64simulgrupo ADD PRIMARY KEY(olab64id)";}
	if ($dbversion==8025){$sSQL=$objDB->sSQLCrearIndice('olab64simulgrupo', 'olab64simulgrupo_id', 'olab64idsimulador, olab64idasigna, olab64numgrupo', true);}
	if ($dbversion==8026){$sSQL=$objDB->sSQLCrearIndice('olab64simulgrupo', 'olab64simulgrupo_padre', 'olab64idsimulador');}
	if ($dbversion==8027){$sSQL="add_campos|olab61simuladorasigna|olab61numaula int NOT NULL DEFAULT 0";}
	if ($dbversion==8028){$sSQL=$objDB->sSQLEliminarIndice('olab61simuladorasigna', 'olab61simuladorasigna_id');}
	if ($dbversion==8029){$sSQL=$objDB->sSQLCrearIndice('olab61simuladorasigna', 'olab61simuladorasigna_id', 'olab61idsimulador, olab61idtanda, olab61idperiodo, olab61curso, olab61numaula', true);}
	if ($dbversion==8030){$sSQL="add_campos|ppto73arbolconfig|ppto73nivel11 int NOT NULL DEFAULT 0|ppto73nivel12 int NOT NULL DEFAULT 0";}
	if ($dbversion==8031){$sSQL="add_campos|core00params|core00idperfil_zona_admin int NOT NULL DEFAULT 0";}
	if ($dbversion==8032){$sSQL="CREATE TABLE repo33rptotal (repo33idprograma int NOT NULL, repo33idcohorte int NOT NULL, repo33tiporeg int NOT NULL, repo33zona int NOT NULL, repo33centro int NOT NULL, repo33id int NOT NULL DEFAULT 0, repo33coh_rete int NOT NULL DEFAULT 0, repo33coh_perm int NOT NULL DEFAULT 0, repo33coh_grad int NOT NULL DEFAULT 0, repo33ingresan int NOT NULL DEFAULT 0, repo33rete_mat int NOT NULL DEFAULT 0, repo33per_cont int NOT NULL DEFAULT 0, repo33per_reing int NOT NULL DEFAULT 0, repo33per_inter int NOT NULL DEFAULT 0, repo33per_cp int NOT NULL DEFAULT 0, repo33per_egre int NOT NULL DEFAULT 0, repo33per_grado int NOT NULL DEFAULT 0, repo33grad_grado int NOT NULL DEFAULT 0, repo33fechaproceso int NOT NULL DEFAULT 0)";}
	if ($dbversion==8033){$sSQL="ALTER TABLE repo33rptotal ADD PRIMARY KEY(repo33id)";}
	if ($dbversion==8034){$sSQL=$objDB->sSQLCrearIndice('repo33rptotal', 'repo33rptotal_id', 'repo33idprograma, repo33idcohorte, repo33tiporeg, repo33zona, repo33centro', true);}
	if ($dbversion==8035){$sSQL="agregamodulo|2833|28|Indicadores RPG|1|5|6";}
	if ($dbversion==8036){$sSQL=$u09."(2833, 1, 'Indicadores RPG', 'datoindicador.php', 2801, 2833, 'S', '', '')";}
	if ($dbversion==8037){$sSQL=$objDB->sSQLCrearIndice('core09programa', 'core09programa_snies', 'core09idsnies');}
	if ($dbversion==8038){$sSQL="add_campos|gedo15expdoc|gedo15estado int NOT NULL DEFAULT 0";}
	if ($dbversion==8039){$sSQL=$u01."(51, 'MONITOREO', 'Sistema de Monitoreo de Desempeño y Aprendizaje', 'N', 'S', 1, 0, 0)";}
	if ($dbversion==8040){$sSQL="INSERT INTO plan04proyecto (plan04numero, plan04id, plan04macroproy, plan04nombre, plan04indicador, plan04objetivo, plan04metageneral, plan04idunidadfunc, plan04idresponsable, plan04idadmin) VALUES (0, 0, 0, '{Ninguno}', '', '', '', 0, 0, 0)";}
	if ($dbversion==8041){$sSQL="add_campos|core71homolsolicitud|core71vs_idcurso int NOT NULL DEFAULT 0|core71vs_idacepta int NOT NULL DEFAULT 0|core71vs_fechaacepta int NOT NULL DEFAULT 0|core71vs_idnav int NOT NULL DEFAULT 0|core71vs_idmoodle int NOT NULL DEFAULT 0|core71vs_fechaasigna int NOT NULL DEFAULT 0|core71vs_iddirector int NOT NULL DEFAULT 0|core71vs_fechaalista int NOT NULL DEFAULT 0|core71vs_idevaluador int NOT NULL DEFAULT 0|core71vs_fechaevalua int NOT NULL DEFAULT 0|core71vs_medio int NOT NULL DEFAULT 0|core71vs_fechaapertura int NOT NULL DEFAULT 0|core71vs_horaapertura int NOT NULL DEFAULT 0|core71vs_minapertura int NOT NULL DEFAULT 0|core71vs_fechacierre int NOT NULL DEFAULT 0|core71vs_horacierre int NOT NULL DEFAULT 0|core71vs_mincierre int NOT NULL DEFAULT 0|core71vs_c2fechacertifica int NOT NULL DEFAULT 0|core71vs_c2horacerfica int NOT NULL DEFAULT 0|core71vs_c2mincertifica int NOT NULL DEFAULT 0|core71vs_calificacion int NOT NULL DEFAULT 0|core71vs_notaprobatoria Decimal(15,2) NULL DEFAULT 0|core71vs_notafinal Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==8042){$sSQL="INSERT INTO core66tipohomologa (core66consec, core66id, core66clase, core66activa, core66general, core66titulo, core66detalle, core66instruccionesalumno) VALUES (-4, -4, 0, 0, 0, 'Validación de Suficiencia por Competencias', 'Corresponde a Validación de Suficiencia por Competencias', '')";}
	if ($dbversion==8043){$sSQL=$unad70."(3601,3653,'gcmo10valoracion','','gcmo10idproyecto','El dato esta incluido en Valoracion de Cumplimiento', '')";}
	if ($dbversion==8044){$sSQL=$unad70."(110,3653,'gcmo10valoracion','','gcmo10idvigencia','El dato esta incluido en Valoracion de Cumplimiento', '')";}
	if ($dbversion==8045){$sSQL=$unad70."(3708,3653,'gcmo10valoracion','gcmo10id','gcmo10idrubrica','El dato esta incluido en Valoracion de Cumplimiento', '')";}
	if ($dbversion==8046){$sSQL="agregamodulo|3653|36|Valoracion de Cumplimiento|1|2|3|4";}
	if ($dbversion==8047){$sSQL=$u09."(3653, 1, 'Valoracion de Cumplimiento', 'planvaloracion.php', 11, 3653, 'S', '', '')";}
	if ($dbversion==8048){$sSQL=$unad70."(3709,3654,'gcmo11valorpta','','gcmo11idrubpregunta','El dato esta incluido en Valoracion - Respuestas', '')";}
	if ($dbversion==8049){$sSQL="agregamodulo|3654|36|Valoracion - Respuestas|1|2|3|4";}
	if ($dbversion==8050){$sSQL="INSERT INTO ofer61tipoagenda (ofer61id, ofer61nombre, ofer61bloqueafechaini) VALUES (5, 'Doctorado [60-40]', 1)";}
	if ($dbversion==8051){$sSQL="DELETE FROM saiu60estadotramite";}
	if ($dbversion==8052){$sSQL="INSERT INTO saiu60estadotramite (saiu60id, saiu60nombre, saiu60tipo1, saiu60tipo707) VALUES 
		(0, 'Borrador', 1, 1), 
		(1, 'Radicado', 2, 2), 
		(3, 'Aprobación de documentos', 3, 0), 
		(5, 'Devuelta', 4, 3), 
		(4, 'En concepto Jurídico', 5, 0),
		(6, 'En concepto técnico y análisis financiero', 6, 0), 
		(7, 'En proyección de resolución-acto administrativo', 15, 0), 
		(8, 'En aprobación de la GAF', 25, 0),
		(9, 'No procedente', 7, 5), 
		(10, 'Solicitud resuelta', 70, 6), 
		(11, 'En firma de la GAF', 45, 0),
		(12, 'En proceso de pago', 50, 0),
		(21, 'En proyección de respuesta', 12, 0),
		(31, 'En ajustes RCONT', 9, 0),
		(36, 'En ajustes de resolución-acto administrativo', 20, 0),
		(41, 'Numeración SGENERAL', 30, 0),
		(46, 'En firma de la VISAE', 40, 0),
		(99, 'Anulado', 99, 99)";}
	if ($dbversion==8053){$sSQL="add_campos|ofer16fechabase|ofer16nombredoc varchar(50)|ofer16aplicadoc int NOT NULL DEFAULT 0";}
	if ($dbversion==8054){$sSQL="UPDATE ofer16fechabase SET ofer16nombredoc='Aplicación y creación 60%', ofer16aplicadoc=1 WHERE ofer16id=0";}
	if ($dbversion==8055){$sSQL="UPDATE ofer16fechabase SET ofer16nombredoc='Consolidación y transferencia 40%', ofer16aplicadoc=1 WHERE ofer16id=3";}
	if ($dbversion==8056){$sSQL="add_campos|prac12rutaestudiante|prac12c41_codigo int NOT NULL DEFAULT 0|prac12c41_estado int NOT NULL DEFAULT 0|prac12c42_codigo int NOT NULL DEFAULT 0|prac12c42_estado int NOT NULL DEFAULT 0|prac12c43_codigo int NOT NULL DEFAULT 0|prac12c43_estado int NOT NULL DEFAULT 0|prac12c44_codigo int NOT NULL DEFAULT 0|prac12c44_estado int NOT NULL DEFAULT 0|prac12c45_codigo int NOT NULL DEFAULT 0|prac12c45_estado int NOT NULL DEFAULT 0|prac12c46_codigo int NOT NULL DEFAULT 0|prac12c46_estado int NOT NULL DEFAULT 0|prac12c47_codigo int NOT NULL DEFAULT 0|prac12c47_estado int NOT NULL DEFAULT 0|prac12c48_codigo int NOT NULL DEFAULT 0|prac12c48_estado int NOT NULL DEFAULT 0|prac12c49_codigo int NOT NULL DEFAULT 0|prac12c49_estado int NOT NULL DEFAULT 0|prac12c50_codigo int NOT NULL DEFAULT 0|prac12c50_estado int NOT NULL DEFAULT 0";}
	if ($dbversion==8057){$sSQL="INSERT INTO core79tipohomolcursodest (core79idtipohomol, core79idplanest, core79idcurso, core79numopcion, core79id, 
		core79idprogramadest, core79idprogramaorigen, core79idplanorigen, core79idcursoorigen, core79formacalificacion, 
		core79calificacion, core79idcursoorigen2, core79idcursoorigen3, core79competenciahomol, core79detalle, 
		core79comparaorigen2, core79comparaorigen3, core79idcursoorigen4, core79comparaorigen4, core79toleranciacred) 
		VALUES (0, 0, 0, 1, -1,
		0,0,0,0,0,
		0,0,0,'','',
		0,0,0,0,0)";}
	if ($dbversion==8058){$sSQL="add_campos|unae40historialcambdoc|unae40detalleest text NULL";}
	if ($dbversion==8059){$sSQL="add_campos|exte02per_aca|exte02fechaini60doc int NOT NULL DEFAULT 0|exte02fechafin60doc int NOT NULL DEFAULT 0|exte02fechaini40doc int NOT NULL DEFAULT 0|exte02fechafin40doc int NOT NULL DEFAULT 0";}
	if ($dbversion==8060){$sSQL="CREATE TABLE gedo23categoriadoc (gedo23consec int NOT NULL, gedo23id int NOT NULL DEFAULT 0, gedo23nombre varchar(100) NULL, gedo23activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8061){$sSQL="ALTER TABLE gedo23categoriadoc ADD PRIMARY KEY(gedo23id)";}
	if ($dbversion==8062){$sSQL=$objDB->sSQLCrearIndice('gedo23categoriadoc', 'gedo23categoriadoc_id', 'gedo23consec', true);}
	if ($dbversion==8063){$sSQL="agregamodulo|2623|26|Categoria documental|1|2|3|4|5|6|8";}
	if ($dbversion==8064){$sSQL=$u09."(2623, 1, 'Categoria documental', 'gedocategoriadoc.php', 1, 2623, 'S', '', '')";}
	if ($dbversion==8065){$sSQL="CREATE TABLE gedo24subcategoriadoc (gedo24idcategoria int NOT NULL, gedo24consec int NOT NULL, gedo24id int NOT NULL DEFAULT 0, gedo24nombre varchar(100) NULL)";}
	if ($dbversion==8066){$sSQL="ALTER TABLE gedo24subcategoriadoc ADD PRIMARY KEY(gedo24id)";}
	if ($dbversion==8067){$sSQL=$objDB->sSQLCrearIndice('gedo24subcategoriadoc', 'gedo24subcategoriadoc_id', 'gedo24idcategoria, gedo24consec', true);}
	if ($dbversion==8068){$sSQL=$objDB->sSQLCrearIndice('gedo24subcategoriadoc', 'gedo24subcategoriadoc_padre', 'gedo24idcategoria');}
	if ($dbversion==8069){$sSQL="agregamodulo|2624|26|Subcategoria documental|1|2|3|4|5|6|8";}
	if ($dbversion==8070){$sSQL="add_campos|gedo15expdoc|gedo15fechadoc int NOT NULL DEFAULT 0|gedo15fechavence int NOT NULL DEFAULT 0|gedo15idrevisor int NOT NULL DEFAULT 0|gedo15fechaaprobacion int NOT NULL DEFAULT 0|gedo15md1_vigencia int NOT NULL DEFAULT 0|gedo15md1_numregistro int NOT NULL DEFAULT 0|gedo15md1_promglobal int NOT NULL DEFAULT 0|gedo15md1_lectura int NOT NULL DEFAULT 0|gedo15md1_comunicacion int NOT NULL DEFAULT 0|gedo15md1_competencias int NOT NULL DEFAULT 0|gedo15md1_razonamiento int NOT NULL DEFAULT 0|gedo15md1_ingles int NOT NULL DEFAULT 0";}
	if ($dbversion==8071){$sSQL="CREATE TABLE gedo25metadatos (gedo25id int NOT NULL, gedo25nombre varchar(100) NULL)";}
	if ($dbversion==8072){$sSQL="ALTER TABLE gedo25metadatos ADD PRIMARY KEY(gedo25id)";}
	if ($dbversion==8073){$sSQL="INSERT INTO gedo25metadatos (gedo25id, gedo25nombre) VALUES (1, 'Prueba Saber 11')";}
	if ($dbversion==8074){$sSQL="add_campos|gedo02tipodoc|gedo02tipometadato int NOT NULL DEFAULT 0";}
	// Tabla para pendientes en postulados
	if ($dbversion==8075){$sSQL="CREATE TABLE grad53pendientes (grad53idpostulacion int NOT NULL, grad53consec int NOT NULL, grad53id int NOT NULL DEFAULT 0, grad53tipopendiente int NOT NULL DEFAULT 0, grad53tienependiente int NOT NULL DEFAULT 0, grad53fechanovedad int NOT NULL DEFAULT 0, grad53terceronovedad int NOT NULL DEFAULT 0, grad53detalle Text NULL)";}
	if ($dbversion==8076){$sSQL="ALTER TABLE grad53pendientes ADD PRIMARY KEY(grad53id)";}
	if ($dbversion==8077){$sSQL=$objDB->sSQLCrearIndice('grad53pendientes', 'grad53pendientes_id', 'grad53idpostulacion, grad53consec', true);}
	if ($dbversion==8078){$sSQL=$objDB->sSQLCrearIndice('grad53pendientes', 'grad53pendientes_padre', 'grad53idpostulacion');}
	if ($dbversion==8079){$sSQL="agregamodulo|2753|27|Postulados - Pendientes|1|2|3|4";}
	if ($dbversion==8080){$sSQL="agregamodulo|2444|24|Pendientes [Paz y salvos]|1|2|3|4|5|6|12|1707";}
	if ($dbversion==8081){$sSQL=$u09."(2444, 1, 'Pendientes [Paz y salvos]', 'cecapyspendiente.php', 1, 2444, 'S', '', '')";}
	if ($dbversion==8082){$sSQL="add_campos|ceca43pysnota|ceca43tipo int NOT NULL DEFAULT 0|ceca43cierredetalle Text NULL|ceca43cierreidusuario int NOT NULL DEFAULT 0|ceca43cierrefecha int NOT NULL DEFAULT 0|ceca43cierrehora int NOT NULL DEFAULT 0|ceca43cierremin int NOT NULL DEFAULT 0|ceca41idtercero int NOT NULL DEFAULT 0";}

	if ($dbversion==8083){$sSQL="CREATE TABLE ceca58tipopendiente (ceca58consec int NOT NULL, ceca58id int NOT NULL DEFAULT 0, ceca58activo int NOT NULL DEFAULT 0, ceca58orden int NOT NULL DEFAULT 0, ceca58nombre varchar(100) NULL)";}
	if ($dbversion==8084){$sSQL="ALTER TABLE ceca58tipopendiente ADD PRIMARY KEY(ceca58id)";}
	if ($dbversion==8085){$sSQL=$objDB->sSQLCrearIndice('ceca58tipopendiente', 'ceca58tipopendiente_id', 'ceca58consec', true);}
	if ($dbversion==8086){$sSQL="agregamodulo|2458|24|Tipos de pendientes|1|2|3|4|5|6|8";}
	if ($dbversion==8087){$sSQL=$u09."(2458, 1, 'Tipos de pendientes', 'cecatipopendiente.php', 2, 2458, 'S', '', '')";}
	if ($dbversion==8088){$sSQL="INSERT INTO ceca58tipopendiente (ceca58consec, ceca58id, ceca58activo, ceca58orden, ceca58nombre) VALUES (0, 0, 0, 0, '{Ninguno}')";}
	if ($dbversion==8089){$sSQL="add_campos|ofer08oferta|ofer08pys_id int NOT NULL DEFAULT 0|ofer08pys_autoriza int NOT NULL DEFAULT 0";}
	if ($dbversion==8090){$sSQL="agregamodulo|4127|41|Mantenimiento|1|3";}
	if ($dbversion==8091){$sSQL=$u09."(4127, 1, 'Mantenimiento', 'cttcprocmant.php', 7, 4127, 'S', '', '')";}
	if ($dbversion==8092){$sSQL="agregamodulo|3651|36|Metas por zona - escuela|1|5|6|1701|1707|1710";}
	if ($dbversion==8093){$sSQL=$u09."(3651, 1, 'Metas por zona - escuela', 'planmatzonaesc.php', 2201, 3651, 'S', '', '')";}
	if ($dbversion==8094){$sSQL="mod_quitar|3631";}
	if ($dbversion==8095){$sSQL="CREATE TABLE teso33planpago (teso33vigencia int NOT NULL, teso33consec int NOT NULL, teso33version int NOT NULL, teso33id int NOT NULL DEFAULT 0, teso33estado int NOT NULL DEFAULT 0, teso33origen int NOT NULL DEFAULT 0, teso33idrp int NOT NULL DEFAULT 0, teso33idprocesocctc int NOT NULL DEFAULT 0, teso33idresol int NOT NULL DEFAULT 0, teso33idbenef int NOT NULL DEFAULT 0, teso33forma int NOT NULL DEFAULT 0, teso33valor Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==8096){$sSQL="ALTER TABLE teso33planpago ADD PRIMARY KEY(teso33id)";}
	if ($dbversion==8097){$sSQL=$objDB->sSQLCrearIndice('teso33planpago', 'teso33planpago_id', 'teso33vigencia, teso33consec, teso33version', true);}
	if ($dbversion==8098){$sSQL="agregamodulo|533|5|Plan de pagos|1|2|3|4|5|6";}
	if ($dbversion==8099){$sSQL=$u09."(533, 1, 'Plan de pagos', 'pptoplanpago.php', 801, 533, 'S', '', '')";}
	if ($dbversion==8100){$sSQL="CREATE TABLE teso34planpagodet (teso34idplanpago int NOT NULL, teso34numero int NOT NULL, teso34id int NOT NULL DEFAULT 0, teso34fechapac int NOT NULL DEFAULT 0, teso34estado int NOT NULL DEFAULT 0, teso34anticipo int NOT NULL DEFAULT 0, teso34porcentaje Decimal(15,2) NULL DEFAULT 0, teso34valorbase Decimal(15,2) NULL DEFAULT 0, teso34valoriva Decimal(15,2) NULL DEFAULT 0, teso34valortotal Decimal(15,2) NULL DEFAULT 0, teso34vrejec Decimal(15,2) NULL DEFAULT 0, teso34fecharadicado int NOT NULL DEFAULT 0, teso34fechaautoriza int NOT NULL DEFAULT 0, teso34vrordenpago Decimal(15,2) NULL DEFAULT 0, teso34vrpago Decimal(15,2) NULL DEFAULT 0, teso34idorden int NOT NULL DEFAULT 0, teso34idegreso int NOT NULL DEFAULT 0)";}
}
if (($dbversion>8100)&&($dbversion<8201)){
	if ($dbversion==8101){$sSQL="ALTER TABLE teso34planpagodet ADD PRIMARY KEY(teso34id)";}
	if ($dbversion==8102){$sSQL=$objDB->sSQLCrearIndice('teso34planpagodet', 'teso34planpagodet_id', 'teso34idplanpago, teso34numero', true);}
	if ($dbversion==8103){$sSQL=$objDB->sSQLCrearIndice('teso34planpagodet', 'teso34planpagodet_padre', 'teso34idplanpago');}
	if ($dbversion==8104){$sSQL="agregamodulo|534|5|Proyección de pagos|1|2|3|4|5|6|8";}
	if ($dbversion==8105){$sSQL="CREATE TABLE teso73estadoplanpago (teso73id int NOT NULL, teso73nombre varchar(50) NULL)";}
	if ($dbversion==8106){$sSQL="ALTER TABLE teso73estadoplanpago ADD PRIMARY KEY(teso73id)";}
	if ($dbversion==8107){$sSQL="CREATE TABLE teso74estadoproypago (teso74id int NOT NULL, teso74nombre varchar(50) NULL)";}
	if ($dbversion==8108){$sSQL="ALTER TABLE teso74estadoproypago ADD PRIMARY KEY(teso74id)";}
	if ($dbversion==8109){$sSQL="INSERT INTO teso73estadoplanpago (teso73id, teso73nombre) VALUES (0,'Proyectado'), (3,'En ejecución'), (4,'Histórico Parcial'), (7,'Finalizado')";}
	if ($dbversion==8110){$sSQL="INSERT INTO teso74estadoproypago (teso74id, teso74nombre) VALUES (0,'Proyectado'), (1,'Devuelto'), (3,'Radicado'), (5,'Autorizado'), (7,'Pagado'), (9,'Anulado')";}
	//8111 - 8114 queda libre.
	if ($dbversion==8115){$sSQL="add_campos|cttc07proceso|cttc07planpago int NOT NULL DEFAULT 0";}

	if ($dbversion==8116){$sSQL="CREATE TABLE cttc00params (cttc00id int NOT NULL, cttc00perfil_coordunidad int NOT NULL DEFAULT 0, cttc00perfil_supervisor int NOT NULL DEFAULT 0, cttc00perfil_interventor int NOT NULL DEFAULT 0, cttc00perfil_contratista int NOT NULL DEFAULT 0, cttc00perfil_avales int NOT NULL DEFAULT 0)";}
	if ($dbversion==8117){$sSQL="ALTER TABLE cttc00params ADD PRIMARY KEY(cttc00id)";}
	if ($dbversion==8118){$sSQL="agregamodulo|4100|41|Parametros|1|3|5|6";}
	if ($dbversion==8119){$sSQL=$u09."(4100, 1, 'Parametros', 'cttcparams.php', 2, 4100, 'S', '', '')";}
	if ($dbversion==8120){$sSQL="INSERT INTO cttc00params (cttc00id, cttc00perfil_coordunidad, cttc00perfil_supervisor, cttc00perfil_interventor, cttc00perfil_contratista, cttc00perfil_avales) VALUES (1, 0, 0, 0, 0, 0)";}
	if ($dbversion==8121){$sSQL="agregamodulo|4128|41|Trasferencias|1|3|4|5|6|10|12|1707";}
	if ($dbversion==8122){$sSQL=$u09."(4128, 1, 'Trasferencias', 'cttctransferencia.php', 4101, 4128, 'S', '', '')";}
	if ($dbversion==8123){$sSQL="CREATE TABLE cttc28procesotras (cttc28idproceso int NOT NULL, cttc28consec int NOT NULL, cttc28id int NOT NULL DEFAULT 0, cttc28usuario int NOT NULL DEFAULT 0, cttc28respdestino int NOT NULL DEFAULT 0, cttc28responsable int NOT NULL DEFAULT 0, cttc28nota Text NULL, cttc28fechainicia int NOT NULL DEFAULT 0, cttc28horainicia int NOT NULL DEFAULT 0, cttc28mininicia int NOT NULL DEFAULT 0, cttc28estado int NOT NULL DEFAULT 0, cttc28fechaprocesa int NOT NULL DEFAULT 0, cttc28horaprocesa int NOT NULL DEFAULT 0, cttc28minprocesa int NOT NULL DEFAULT 0, cttc28dias int NOT NULL DEFAULT 0, cttc28minutos int NOT NULL DEFAULT 0)";}
	if ($dbversion==8124){$sSQL="ALTER TABLE cttc28procesotras ADD PRIMARY KEY(cttc28id)";}
	if ($dbversion==8125){$sSQL=$objDB->sSQLCrearIndice('cttc28procesotras', 'cttc28procesotras_id', 'cttc28idproceso, cttc28consec', true);}
	if ($dbversion==8126){$sSQL=$objDB->sSQLCrearIndice('cttc28procesotras', 'cttc28procesotras_padre', 'cttc28idproceso');}
	if ($dbversion==8127){$sSQL=$objDB->sSQLCrearIndice('cttc28procesotras', 'cttc28procesotras_idresponsable', 'cttc28responsable');}
	if ($dbversion==8128){$sSQL=$objDB->sSQLCrearIndice('cttc28procesotras', 'cttc28procesotras_estado', 'cttc28estado');}
	if ($dbversion==8129){$sSQL="add_campos|cttc28procesotras|cttc28notarpta Text NULL";}
	if ($dbversion==8130){$sSQL=$u01."(99, 'Docs UI/UX', 'Documentación UI/UX', 'S', 'S', 1, 0, 0)";}

	if ($dbversion==8131){$sSQL=$u08."(5101, 'Monitoreo', 'gm.php?id=5101', 'Monitoreo', 'Monitoring', 'Monitoramento')";}
	if ($dbversion==8132){$sSQL="CREATE TABLE moni01competencias (moni01consec int NOT NULL, moni01id int NOT NULL DEFAULT 0, moni01nombre varchar(250) NULL, moni01activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8133){$sSQL="ALTER TABLE moni01competencias ADD PRIMARY KEY(moni01id)";}
	if ($dbversion==8134){$sSQL=$objDB->sSQLCrearIndice('moni01competencias', 'moni01competencias_id', 'moni01consec', true);}
	if ($dbversion==8135){$sSQL="agregamodulo|5101|51|Competencias|1|2|3|4|5|6|8";}
	if ($dbversion==8136){$sSQL=$u09."(5101, 1, 'Competencias', 'monicompetencias.php', 1, 5101, 'S', '', '')";}
	if ($dbversion==8137){$sSQL="agregamodulo|5102|51|Plan de estudios [2210]|1|2|3|4|5|6|8";}
	if ($dbversion==8138){$sSQL=$u09."(5102, 1, 'Plan de estudios', 'moniplanest.php', 1, 5102, 'S', '', '')";}
	if ($dbversion==8139){$sSQL="CREATE TABLE moni03nucleos (moni03idplan int NOT NULL, moni03idcompetencia int NOT NULL, moni03consec int NOT NULL, moni03id int NOT NULL DEFAULT 0, moni03nombre varchar(100) NULL, moni03detalle Text NULL, moni03activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8140){$sSQL="ALTER TABLE moni03nucleos ADD PRIMARY KEY(moni03id)";}
	if ($dbversion==8141){$sSQL=$objDB->sSQLCrearIndice('moni03nucleos', 'moni03nucleos_id', 'moni03idplan, moni03idcompetencia, moni03consec', true);}
	if ($dbversion==8142){$sSQL=$objDB->sSQLCrearIndice('moni03nucleos', 'moni03nucleos_padre', 'moni03idplan');}
	if ($dbversion==8143){$sSQL="agregamodulo|5103|51|Núcleos problemicos|1|2|3|4|5|6|8";}
	if ($dbversion==8144){$sSQL="CREATE TABLE moni04perfilegresado (moni04idplan int NOT NULL, moni04consec int NOT NULL, moni04id int NOT NULL DEFAULT 0, moni04detalle Text NULL, moni04orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==8145){$sSQL="ALTER TABLE moni04perfilegresado ADD PRIMARY KEY(moni04id)";}
	if ($dbversion==8146){$sSQL=$objDB->sSQLCrearIndice('moni04perfilegresado', 'moni04perfilegresado_id', 'moni04idplan, moni04consec', true);}
	if ($dbversion==8147){$sSQL=$objDB->sSQLCrearIndice('moni04perfilegresado', 'moni04perfilegresado_padre', 'moni04idplan');}
	if ($dbversion==8148){$sSQL="agregamodulo|5104|51|Perfil del egresado|1|2|3|4|5|6|8";}
	if ($dbversion==8149){$sSQL="CREATE TABLE moni05resultadoprograma (moni05idplan int NOT NULL, moni05consec int NOT NULL, moni05id int NOT NULL DEFAULT 0)";}
	if ($dbversion==8150){$sSQL="ALTER TABLE moni05resultadoprograma ADD PRIMARY KEY(moni05id)";}
	if ($dbversion==8151){$sSQL=$objDB->sSQLCrearIndice('moni05resultadoprograma', 'moni05resultadoprograma_id', 'moni05idplan, moni05consec', true);}
	if ($dbversion==8152){$sSQL=$objDB->sSQLCrearIndice('moni05resultadoprograma', 'moni05resultadoprograma_padre', 'moni05idplan');}
	if ($dbversion==8153){$sSQL="agregamodulo|5105|51|Resultado del aprendizaje del programa|1|5|6";}
	if ($dbversion==8154){$sSQL="agregamodulo|5110|51|Resultado del aprendizaje del programa por periodo|1|5|6";}
	if ($dbversion==8155){$sSQL=$u09."(5110, 1, 'Resultado del aprendizaje del programa por periodo', 'monirapperiodo.php', 11, 5110, 'S', '', '')";}
	if ($dbversion==8156){$sSQL="agregamodulo|5111|51|Resultado del aprendizaje del programa por estudiante|1|5|6";}
	if ($dbversion==8157){$sSQL=$u09."(5111, 1, 'Resultado del aprendizaje del programa por estudiante', 'monirapestudiante.php', 11, 5111, 'S', '', '')";}
	if ($dbversion==8158){$sSQL="CREATE TABLE docu01categorias (docu01consec int NOT NULL, docu01id int NOT NULL DEFAULT 0, docu01nombre varchar(50) NULL, docu01icono int NOT NULL DEFAULT 0, docu01activo int NOT NULL DEFAULT 0, docu01orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==8159){$sSQL="ALTER TABLE docu01categorias ADD PRIMARY KEY(docu01id)";}
	if ($dbversion==8160){$sSQL=$objDB->sSQLCrearIndice('docu01categorias', 'docu01categorias_id', 'docu01consec', true);}
	if ($dbversion==8161){$sSQL="agregamodulo|9901|99|Categorias|1|2|3|4|5|6|8";}
	if ($dbversion==8162){$sSQL=$u09."(9901, 1, 'Categorias', 'docucategorias.php', 2, 9901, 'S', '', '')";}
	if ($dbversion==8163){$sSQL="CREATE TABLE docu02subcategorias (docu02idcategoria int NOT NULL, docu02consec int NOT NULL, docu02id int NOT NULL DEFAULT 0, docu02nombre varchar(50) NULL, docu02descripcion varchar(200) NULL, docu02activo int NOT NULL DEFAULT 0, docu02orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==8164){$sSQL="ALTER TABLE docu02subcategorias ADD PRIMARY KEY(docu02id)";}
	if ($dbversion==8165){$sSQL=$objDB->sSQLCrearIndice('docu02subcategorias', 'docu02subcategorias_id', 'docu02idcategoria, docu02consec', true);}
	if ($dbversion==8166){$sSQL=$objDB->sSQLCrearIndice('docu02subcategorias', 'docu02subcategorias_padre', 'docu02idcategoria');}
	if ($dbversion==8167){$sSQL="agregamodulo|9902|99|Subcategorias|1|2|3|4|5|6|8";}
	if ($dbversion==8168){$sSQL="CREATE TABLE docu03temas (docu03idcategoria int NOT NULL, docu03idsubcategoria int NOT NULL, docu03consec int NOT NULL, docu03id int NOT NULL DEFAULT 0, docu03nombre varchar(50) NULL, docu03descripcion varchar(200) NULL, docu03activo int NOT NULL DEFAULT 0, docu03orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==8169){$sSQL="ALTER TABLE docu03temas ADD PRIMARY KEY(docu03id)";}
	if ($dbversion==8170){$sSQL=$objDB->sSQLCrearIndice('docu03temas', 'docu03temas_id', 'docu03idcategoria, docu03idsubcategoria, docu03consec', true);}
	if ($dbversion==8171){$sSQL="agregamodulo|9903|99|Temas|1|2|3|4|5|6|8";}
	if ($dbversion==8172){$sSQL=$u09."(9903, 1, 'Temas', 'docutemas.php', 1, 9903, 'S', '', '')";}
	if ($dbversion==8173){$sSQL="add_campos|docu03temas|docu03tipotabla int NOT NULL DEFAULT 0|docu03tipodato int NOT NULL DEFAULT 0|docu03datos Text NULL|docu03prefijos Text NULL";}	
	if ($dbversion==8174){$sSQL="agregamodulo|4129|41|Unidades funcionales (CTTC)|1|2|3|4|5|6|12";}
	if ($dbversion==8175){$sSQL=$u09."(4129, 1, 'Unidades funcionales', 'cttcunidadf.php', 1, 4129, 'S', '', '')";}
	if ($dbversion==8176){$sSQL="CREATE TABLE cttc30unidadcttc (cttc30idunidad int NOT NULL, cttc30consec int NOT NULL, cttc30id int NOT NULL DEFAULT 0, cttc30idtercero int NOT NULL DEFAULT 0, cttc30titulo varchar(100) NULL, cttc30idzona int NOT NULL DEFAULT 0, cttc30idcentro int NOT NULL DEFAULT 0, cttc30fechaing int NOT NULL DEFAULT 0, cttc30fecharet int NOT NULL DEFAULT 0)";}
	if ($dbversion==8177){$sSQL="ALTER TABLE cttc30unidadcttc ADD PRIMARY KEY(cttc30idunidad, cttc30consec)";}
	if ($dbversion==8178){$sSQL=$objDB->sSQLCrearIndice('cttc30unidadcttc', 'cttc30unidadcttc_padre', 'cttc30idunidad');}
	if ($dbversion==8179){$sSQL="agregamodulo|4130|41|Unidades fun - Admin cttc|1|2|3|4|5|6";}
	if ($dbversion==8180){$sSQL="add_campos|cttc00params|cttc00perfil_unidadcoord int NOT NULL DEFAULT 0";}	
	if ($dbversion==8181){$sSQL="INSERT INTO cttc74estadoagenda (cttc74id, cttc74nombre) VALUES (2, 'Revisado')";}
	if ($dbversion==8182){$sSQL="ALTER TABLE cttc09procnota ADD cttc09idorigen int NOT NULL DEFAULT 0, ADD cttc09idarchivo int NOT NULL DEFAULT 0";}
	if ($dbversion==8183){$sSQL="add_campos|unad24sede|unad24ccor_admin int NOT NULL DEFAULT 0";}
	if ($dbversion==8184){$sSQL="agregamodulo|1611|16|Sedes|1|3|5|6|10|12";}
	if ($dbversion==8185){$sSQL=$u09."(1611, 1, 'Sedes', 'ccorsede.php', 1, 1611, 'S', '', '')";}
	if ($dbversion==8186){$sSQL="agregamodulo|1622|16|Sedes - Funcionarios corresp.|1|2|3|4|5|6";}
	if ($dbversion==8187){$sSQL=$u08."(2106, 'Simuladores', 'gm.php?id=2106', 'Simuladores', '', '')";}
	if ($dbversion==8188){$sSQL="add_campos|gedo02tipodoc|gedo02idcategoria int NOT NULL DEFAULT 0| gedo02subcategoria int NOT NULL DEFAULT 0";}
	// -- 8189 - 8191 -- Quedan libres
	if ($dbversion==8192){$sSQL="agregamodulo|2625|26|Comunicaciones salientes|1|2|3|4|5|6|8|1707|1708";}
	if ($dbversion==8193){$sSQL=$u09."(2625, 1, 'Comunicaciones salientes', 'gedosalidas.php', 2602, 2625, 'S', '', '')";}
	// -- 8194 - 8197 -- Quedan libres
	if ($dbversion==8198){$sSQL="agregamodulo|2626|26|Comunicacion sal - anexos|1|2|3|4|5|6|8";}
	// -- 8199 - 8202 -- Quedan libres
}
if (($dbversion>8200)&&($dbversion<8301)){
	if ($dbversion==8203){$sSQL="agregamodulo|2627|26|Comunicacion sal - anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==8204){$sSQL=$u08."(2602, 'Comunicaciones', 'gm.php?id=2602', 'Communications', '', 'Comunicações')";}
	if ($dbversion==8205){$sSQL="add_campos|gedo15expdoc|geco15idorigen int NOT NULL DEFAULT 0|gedo15idarchivo int NOT NULL DEFAULT 0";}
	if ($dbversion==8206){$sSQL="add_campos|unad24sede|unad24ccor_forma int NOT NULL DEFAULT 0";}
	if ($dbversion==8207){$sSQL="ALTER TABLE gedo15expdoc MODIFY COLUMN gedo15md1_numregistro VARCHAR(30) NOT NULL DEFAULT ''";}
	if ($dbversion==8208){$sSQL="agregamodulo|268|28|Tamaño de las bases de datos|1|5|6";}
	if ($dbversion==8209){$sSQL=$u09."(268, 1, 'Tamaño de las bases de datos', 'aureadbpeso.php', 11, 268, 'S', '', '')";}

	if ($dbversion==8210){$sSQL="mod_quitar|5101";}
	if ($dbversion==8211){$sSQL="agregamodulo|5101|51|Competencias|1|2|3|4|5|6|8";}
	if ($dbversion==8212){$sSQL="DROP TABLE moni01competencias";}
	if ($dbversion==8213){$sSQL="CREATE TABLE moni01competencias (moni01idplan int NOT NULL DEFAULT 0, moni01consec int NOT NULL, moni01id int NOT NULL DEFAULT 0, moni01descripcion varchar(250) NULL, moni01activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8214){$sSQL="ALTER TABLE moni01competencias ADD PRIMARY KEY(moni01id)";}
	if ($dbversion==8215){$sSQL=$objDB->sSQLCrearIndice('moni01competencias', 'moni01competencias_id', 'moni01consec', true);}
	if ($dbversion==8216){$sSQL=$objDB->sSQLCrearIndice('moni01competencias', 'moni01competencias_padre', 'moni01idplan');}
	if ($dbversion==8217){$sSQL="agregamodulo|5101|51|Competencias|1|2|3|4|5|6|8";}
	if ($dbversion==8218){$sSQL="DROP TABLE moni03nucleos";}
	if ($dbversion==8219){$sSQL="CREATE TABLE moni03nucleos (moni03idplan int NOT NULL, moni03consec int NOT NULL, moni03id int NOT NULL DEFAULT 0, moni03nombre varchar(100) NULL, moni03detalle Text NULL, moni03activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8220){$sSQL="ALTER TABLE moni03nucleos ADD PRIMARY KEY(moni03id)";}
	if ($dbversion==8221){$sSQL=$objDB->sSQLCrearIndice('moni03nucleos', 'moni03nucleos_id', 'moni03idplan, moni03consec', true);}
	if ($dbversion==8222){$sSQL=$objDB->sSQLCrearIndice('moni03nucleos', 'moni03nucleos_padre', 'moni03idplan');}
	if ($dbversion==8223){$sSQL="CREATE TABLE moni06compxnucleo (moni06idplan int NOT NULL, moni06idcompetencia int NOT NULL, moni06idnucleo int NOT NULL, moni06id int NOT NULL DEFAULT 0)";}
	if ($dbversion==8224){$sSQL="ALTER TABLE moni06compxnucleo ADD PRIMARY KEY(moni06id)";}
	if ($dbversion==8225){$sSQL=$objDB->sSQLCrearIndice('moni06compxnucleo', 'moni06compxnucleo_id', 'moni06idplan, moni06idcompetencia, moni06idnucleo', true);}
	if ($dbversion==8226){$sSQL=$objDB->sSQLCrearIndice('moni06compxnucleo', 'moni06compxnucleo_padre', 'moni06idplan');}
	if ($dbversion==8227){$sSQL="agregamodulo|5106|51|Competencias por nucleo|1|2|3|4|5|6|8";}
	if ($dbversion==8228){$sSQL="add_campos|moni05resultadoprograma|moni05descripcion Text NULL";}

	//if ($dbversion==8229){$sSQL="add_campos|core11plandeestudio|core11compgeneral int NOT NULL DEFAULT 0|core11compespecifico int NOT NULL DEFAULT 0";}	
	if ($dbversion==8230){$sSQL="agregamodulo|2669|26|Sedes [124]|1";}
	if ($dbversion==8231){$sSQL=$u09."(2669, 1, 'Sedes', 'ccorsede.php', 1, 2669, 'S', '', '')";}
	if ($dbversion==8232){$sSQL="DROP TABLE gedo25salidas";}
	if ($dbversion==8233){$sSQL="DROP TABLE gedo26salidadoc";}
	if ($dbversion==8234){$sSQL="DROP TABLE gedo27salidanota";}
	if ($dbversion==8235){$sSQL="CREATE TABLE gedo25salidas (gedo25zona int NOT NULL, gedo25idsede int NOT NULL, gedo25idoficina int NOT NULL, gedo25vigencia int NOT NULL, gedo25tipodoc int NOT NULL, gedo25consec int NOT NULL, gedo25id int NOT NULL DEFAULT 0, gedo25idpropietario int NOT NULL DEFAULT 0, gedo25fechadoc int NOT NULL DEFAULT 0, gedo25estado int NOT NULL DEFAULT 0, gedo25asunto Text NULL, gedo25origenunidad int NOT NULL DEFAULT 0, gedo25origenescuela int NOT NULL DEFAULT 0, gedo25fechaunidad int NOT NULL DEFAULT 0, gedo25consecunidad int NOT NULL DEFAULT 0, gedo25consecsede int NOT NULL DEFAULT 0, gedo25etiqueta varchar(50) NULL, gedo25subtipo int NOT NULL DEFAULT 0, gedo25destinounidad int NOT NULL DEFAULT 0, gedo25destinoescuela int NOT NULL DEFAULT 0, gedo25fechaenvio int NOT NULL DEFAULT 0, gedo25pruebadeentrega int NOT NULL DEFAULT 0, gedo25visadousuario int NOT NULL DEFAULT 0, gedo25visadofecha int NOT NULL DEFAULT 0, gedo25medioentrega int NOT NULL DEFAULT 0, gedo25transportador int NOT NULL DEFAULT 0, gedo25transp_nombre varchar(100) NULL, gedo25numguia varchar(20) NULL, gedo25transp_tipodeserv int NOT NULL DEFAULT 0, gedo25transp_formapaquete int NOT NULL DEFAULT 0, gedo25fecharecibido int NOT NULL DEFAULT 0, gedo25novedad int NOT NULL DEFAULT 0, gedo25novedadrecibido Text NULL, gedo25idorigenprueba int NOT NULL DEFAULT 0, gedo25idarchivoprueba int NOT NULL DEFAULT 0, gedo25recibo_id int NOT NULL DEFAULT 0)";}
	if ($dbversion==8236){$sSQL="ALTER TABLE gedo25salidas ADD PRIMARY KEY(gedo25id)";}
	if ($dbversion==8237){$sSQL=$objDB->sSQLCrearIndice('gedo25salidas', 'gedo25salidas_id', 'gedo25zona, gedo25idsede, gedo25idoficina, gedo25vigencia, gedo25tipodoc, gedo25consec', true);}
	if ($dbversion==8238){$sSQL="CREATE TABLE gedo71estadosalida (gedo71id int NOT NULL, gedo71nombre varchar(50) NULL)";}
	if ($dbversion==8239){$sSQL="ALTER TABLE gedo71estadosalida ADD PRIMARY KEY(gedo71id)";}
	if ($dbversion==8240){$sSQL="CREATE TABLE gedo26salidadoc (gedo26idsalida int NOT NULL, gedo26identrada int NOT NULL, gedo26consec int NOT NULL, gedo26id int NOT NULL DEFAULT 0, gedo26descripcion varchar(200) NULL, gedo26idorigen int NOT NULL DEFAULT 0, gedo26idarchivo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8241){$sSQL="ALTER TABLE gedo26salidadoc ADD PRIMARY KEY(gedo26id)";}
	if ($dbversion==8242){$sSQL=$objDB->sSQLCrearIndice('gedo26salidadoc', 'gedo26salidadoc_id', 'gedo26idsalida, gedo26identrada, gedo26consec', true);}
	if ($dbversion==8243){$sSQL=$objDB->sSQLCrearIndice('gedo26salidadoc', 'gedo26salidadoc_padre', 'gedo26idsalida');}
	if ($dbversion==8244){$sSQL="CREATE TABLE gedo27salidanota (gedo27idsalida int NOT NULL, gedo27identrada int NOT NULL, gedo27consec int NOT NULL, gedo27id int NOT NULL DEFAULT 0, gedo27anotacion Text NULL, gedo27idusuario int NOT NULL DEFAULT 0, gedo27fecha int NOT NULL DEFAULT 0)";}
	if ($dbversion==8245){$sSQL="ALTER TABLE gedo27salidanota ADD PRIMARY KEY(gedo27id)";}
	if ($dbversion==8246){$sSQL=$objDB->sSQLCrearIndice('gedo27salidanota', 'gedo27salidanota_id', 'gedo27idsalida, gedo27identrada, gedo27consec', true);}
	if ($dbversion==8247){$sSQL=$objDB->sSQLCrearIndice('gedo27salidanota', 'gedo27salidanota_padre', 'gedo27idsalida');}
	if ($dbversion==8248){$sSQL="CREATE TABLE gedo28salidapaquete (gedo28idsalida int NOT NULL, gedo28identrada int NOT NULL, gedo28consec int NOT NULL, gedo28id int NOT NULL DEFAULT 0, gedo28transp_peso int NOT NULL DEFAULT 0, gedo28transp_alto int NOT NULL DEFAULT 0, gedo28transp_ancho int NOT NULL DEFAULT 0, gedo28transp_largo int NOT NULL DEFAULT 0, gedo28notasalida Text NULL, gedo28notaentrada Text NULL)";}
	if ($dbversion==8249){$sSQL="ALTER TABLE gedo28salidapaquete ADD PRIMARY KEY(gedo28id)";}
	if ($dbversion==8250){$sSQL=$objDB->sSQLCrearIndice('gedo28salidapaquete', 'gedo28salidapaquete_id', 'gedo28idsalida, gedo28identrada, gedo28consec', true);}
	if ($dbversion==8251){$sSQL=$objDB->sSQLCrearIndice('gedo28salidapaquete', 'gedo28salidapaquete_padre', 'gedo28idsalida');}
	if ($dbversion==8252){$sSQL="agregamodulo|2628|26|Comunicacion sal - paquete|1|2|3|4|5|6";}
	if ($dbversion==8253){$sSQL="CREATE TABLE gedo72subtiposal (gedo72id int NOT NULL, gedo72nombre varchar(50) NULL)";}
	if ($dbversion==8254){$sSQL="ALTER TABLE gedo72subtiposal ADD PRIMARY KEY(gedo72id)";}
	if ($dbversion==8255){$sSQL="INSERT INTO gedo71estadosalida (gedo71id, gedo71nombre) VALUES (0, 'Borrador'), (3, 'Firmado'), (7, 'Enviado'), (9, 'Anulado'), (11, 'Recibido'), (13, 'Devuelto')";}
	if ($dbversion==8256){$sSQL="INSERT INTO gedo72subtiposal (gedo72id, gedo72nombre) VALUES (0, 'Interna'), (1, 'Externo'), (2, 'Externo Internacional')";}
		
	if ($dbversion==8257){$sSQL="CREATE TABLE olab65simulexp (olab65idsimulador int NOT NULL, olab65consec int NOT NULL, olab65id int NOT NULL DEFAULT 0, olab65nombre varchar(100) NULL, olab65identificador varchar(20) NULL, olab65grupoexp int NOT NULL DEFAULT 0, olab65aplicacomputador int NOT NULL DEFAULT 0, olab65aplicatablet int NOT NULL DEFAULT 0)";}
	if ($dbversion==8258){$sSQL="ALTER TABLE olab65simulexp ADD PRIMARY KEY(olab65id)";}
	if ($dbversion==8259){$sSQL=$objDB->sSQLCrearIndice('olab65simulexp', 'olab65simulexp_id', 'olab65idsimulador, olab65consec', true);}
	if ($dbversion==8260){$sSQL=$objDB->sSQLCrearIndice('olab65simulexp', 'olab65simulexp_padre', 'olab65idsimulador');}
	if ($dbversion==8261){$sSQL="agregamodulo|2165|21|Simuladores - Experimentos|1|2|3|4|5|6";}
	if ($dbversion==8262){$sSQL="CREATE TABLE olab66simulgrupoexp (olab66idsimulador int NOT NULL, olab66consec int NOT NULL, olab66id int NOT NULL DEFAULT 0, olab66activo int NOT NULL DEFAULT 0, olab66orden int NOT NULL DEFAULT 0, olab66nombre varchar(50) NULL)";}
	if ($dbversion==8263){$sSQL="ALTER TABLE olab66simulgrupoexp ADD PRIMARY KEY(olab66id)";}
	if ($dbversion==8264){$sSQL=$objDB->sSQLCrearIndice('olab66simulgrupoexp', 'olab66simulgrupoexp_id', 'olab66idsimulador, olab66consec', true);}
	if ($dbversion==8265){$sSQL=$objDB->sSQLCrearIndice('olab66simulgrupoexp', 'olab66simulgrupoexp_padre', 'olab66idsimulador');}
	if ($dbversion==8266){$sSQL="agregamodulo|2166|21|Simuladores - Grupos de exp|1|2|3|4|5|6";}
	if ($dbversion==8267){$sSQL="CREATE TABLE olab67simulcursoexp (olab67idsimulador int NOT NULL, olab67idcurso int NOT NULL, olab67idexp int NOT NULL, olab67id int NOT NULL DEFAULT 0, olab67activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8268){$sSQL="ALTER TABLE olab67simulcursoexp ADD PRIMARY KEY(olab67id)";}
	if ($dbversion==8269){$sSQL=$objDB->sSQLCrearIndice('olab67simulcursoexp', 'olab67simulcursoexp_id', 'olab67idsimulador, olab67idcurso, olab67idexp', true);}
	if ($dbversion==8270){$sSQL=$objDB->sSQLCrearIndice('olab67simulcursoexp', 'olab67simulcursoexp_padre', 'olab67idsimulador');}
	if ($dbversion==8271){$sSQL="agregamodulo|2167|21|Simuladores - Experimentos curso|1|2|3|4|5|6";}
	if ($dbversion==8272){$sSQL="CREATE TABLE olab68simulusuario (olab68idsimulador int NOT NULL, olab68idtercero int NOT NULL, olab68idescuela int NOT NULL, olab68idprograma int NOT NULL, olab68id int NOT NULL DEFAULT 0, olab68idrol int NOT NULL DEFAULT 0)";}
	if ($dbversion==8273){$sSQL="ALTER TABLE olab68simulusuario ADD PRIMARY KEY(olab68id)";}
	if ($dbversion==8274){$sSQL=$objDB->sSQLCrearIndice('olab68simulusuario', 'olab68simulusuario_id', 'olab68idsimulador, olab68idtercero, olab68idescuela, olab68idprograma', true);}
	if ($dbversion==8275){$sSQL=$objDB->sSQLCrearIndice('olab68simulusuario', 'olab68simulusuario_padre', 'olab68idsimulador');}
	if ($dbversion==8276){$sSQL="agregamodulo|2168|21|Simuladores - Usuarios globales|1|2|3|4|5|6";}
	if ($dbversion==8277){$sSQL="ALTER TABLE moni03nucleos CHANGE COLUMN moni03nombre moni03orden INT NOT NULL DEFAULT 0";}	
	if ($dbversion==8278){$sSQL=$u09."(2161, 1, 'Gestión de cupos', 'simulcupos.php', 2106, 2161, 'S', '', '')";}

	if ($dbversion==8279){$sSQL="CREATE TABLE olab69simullicgrupo (olab69idasigna int NOT NULL, olab69idgrupo int NOT NULL, olab69id int NOT NULL DEFAULT 0, olab69idsimulador int NOT NULL DEFAULT 0, olab69numgrupo int NOT NULL DEFAULT 0, olab69idtanda int NOT NULL DEFAULT 0, olab69numcupo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8280){$sSQL="ALTER TABLE olab69simullicgrupo ADD PRIMARY KEY(olab69id)";}
	if ($dbversion==8281){$sSQL=$objDB->sSQLCrearIndice('olab69simullicgrupo', 'olab69simullicgrupo_id', 'olab69idasigna, olab69idgrupo', true);}
	if ($dbversion==8282){$sSQL=$objDB->sSQLCrearIndice('olab69simullicgrupo', 'olab69simullicgrupo_padre', 'olab69idasigna');}
	if ($dbversion==8283){$sSQL="agregamodulo|6169|21|Cupos por grupo|1|2|3|4|5|6";}
	if ($dbversion==8284){$sSQL="add_campos|olab61simuladorasigna|olab61licenciasaplica int NOT NULL DEFAULT 0";}
	if ($dbversion==8285){$sSQL="add_campos|olab62simuladorcupo|olab62fechaprocesa int NOT NULL DEFAULT 0";}
	if ($dbversion==8286){$sSQL="add_campos|olab69simullicgrupo|olab69fechaprocesa int NOT NULL DEFAULT 0";}
	if ($dbversion==8287){$sSQL="add_campos|olab68simulusuario|olab68fechaprocesa int NOT NULL DEFAULT 0";}
	
	if ($dbversion==8288){$sSQL="CREATE TABLE olab70simulexp (olab70idsimulador int NOT NULL, olab70idtercero int NOT NULL, olab70idexperimento int NOT NULL, olab70idcupo int NOT NULL, olab70idgrupo int NOT NULL, olab70idglobal int NOT NULL, olab70id int NOT NULL DEFAULT 0, olab70idrol int NOT NULL DEFAULT 0, olab70fechaacceso int NOT NULL DEFAULT 0, olab70numaccesos int NOT NULL DEFAULT 0, olab70fechultacceso int NOT NULL DEFAULT 0)";}
	if ($dbversion==8289){$sSQL="ALTER TABLE olab70simulexp ADD PRIMARY KEY(olab70id)";}
	if ($dbversion==8290){$sSQL=$objDB->sSQLCrearIndice('olab70simulexp', 'olab70simulexp_id', 'olab70idsimulador, olab70idtercero, olab70idexperimento, olab70idcupo, olab70idgrupo, olab70idglobal', true);}
	if ($dbversion==8291){$sSQL="agregamodulo|6170|21|Usuarios por experimento|1|5|6";}
	if ($dbversion==8292){$sSQL=$u09."(6170, 1, 'Usuarios por experimento', 'rptexpuser.php', 2106, 6170, 'S', '', '')";}
	if ($dbversion==8293){$sSQL="add_campos|comp12procesocompra|comp12version int NOT NULL DEFAULT 0";}
	if ($dbversion==8294){$sSQL=$objDB->sSQLEliminarIndice('comp12procesocompra', 'comp12procesocompra_id');}
	if ($dbversion==8295){$sSQL=$objDB->sSQLCrearIndice('comp12procesocompra', 'comp12procesocompra_id', 'comp12vigenciappt, comp12consec, comp12version', true);}

	if ($dbversion==8296){$sSQL=$objDB->sSQLEliminarIndice('comp13procesoprod', 'comp13procesoprod_id');}
	if ($dbversion==8297){$sSQL="add_campos|comp13procesoprod|comp13idconsolidado int NOT NULL DEFAULT 0";}
	if ($dbversion==8298){$sSQL=$objDB->sSQLCrearIndice('comp13procesoprod', 'comp13procesoprod_id', 'comp13idproceso, comp13idproducto, comp13zona, comp13centro, comp13escuela, comp13programa, comp13idconsolidado', true);}

	if ($dbversion==8299){$sSQL="INSERT INTO (comp10consec,comp10id,comp10estado,comp10vigenciappt,comp10detalle,comp10idunidadfunc,comp10idfuncionario,comp10fecha) VALUES (0,0,0,0,'',0,0,0)";}	
	if ($dbversion==8300){$sSQL="ALTER TABLE core11plandeestudio DROP COLUMN core11compgeneral";}
	}
if (($dbversion>8300)&&($dbversion<8401)){
	if ($dbversion==8301){$sSQL="ALTER TABLE core11plandeestudio DROP COLUMN core11compespecifico";}
	if ($dbversion==8302){$sSQL="ALTER TABLE moni05resultadoprograma DROP INDEX moni05resultadoprograma_id";}
	if ($dbversion==8303){$sSQL="add_campos|moni05resultadoprograma|moni05idcompetencia int NOT NULL";}
	if ($dbversion==8304){$sSQL=$objDB->sSQLCrearIndice('moni05resultadoprograma', 'moni05resultadoprograma_id', 'moni05idplan, moni05idcompetencia, moni05consec', true);}
	if ($dbversion==8305){$sSQL="CREATE TABLE moni07cursoscompgen (moni07idplan int NOT NULL, moni07idcurso int NOT NULL, moni07idcompgen int NOT NULL, moni07id int NOT NULL DEFAULT 0)";}
	if ($dbversion==8306){$sSQL="ALTER TABLE moni07cursoscompgen ADD PRIMARY KEY(moni07id)";}
	if ($dbversion==8307){$sSQL=$objDB->sSQLCrearIndice('moni07cursoscompgen', 'moni07cursoscompgen_id', 'moni07idplan, moni07idcurso, moni07idcompgen', true);}
	if ($dbversion==8308){$sSQL=$objDB->sSQLCrearIndice('moni07cursoscompgen', 'moni07cursoscompgen_padre', 'moni07idplan');}
	if ($dbversion==8309){$sSQL="agregamodulo|5107|51|Cursos por competencia generica|1|2|3|4|5|6|8";}
	if ($dbversion==8310){$sSQL="CREATE TABLE moni08cursosrap (moni08idplan int NOT NULL, moni08idcurso int NOT NULL, moni08idrap int NOT NULL, moni08id int NOT NULL DEFAULT 0)";}
	if ($dbversion==8311){$sSQL="ALTER TABLE moni08cursosrap ADD PRIMARY KEY(moni08id)";}
	if ($dbversion==8312){$sSQL=$objDB->sSQLCrearIndice('moni08cursosrap', 'moni08cursosrap_id', 'moni08idplan, moni08idcurso, moni08idrap', true);}
	if ($dbversion==8313){$sSQL=$objDB->sSQLCrearIndice('moni08cursosrap', 'moni08cursosrap_padre', 'moni08idplan');}
	if ($dbversion==8314){$sSQL="agregamodulo|5108|51|Cursos por resultado de aprendizaje del programa|1|2|3|4|5|6|8";}

	if ($dbversion==8315){$sSQL="add_campos|unae26unidadesfun|unae26nombresecop varchar(100) NULL";}
	if ($dbversion==8316){$sSQL="UPDATE unae26unidadesfun SET unae26nombresecop=unae26nombre";}

	if ($dbversion==8317){$sSQL="add_campos|gedo25salidas|gedo25dest_entidad varchar(250) NULL|gedo25dest_dependencia varchar(250) NULL|gedo25dest_destinatario varchar(250) NULL";}
	if ($dbversion==8318){$sSQL="add_campos|gedo25salidas|gedo25formadocumento int NOT NULL DEFAULT 0|gedo25proyectadopor int NOT NULL DEFAULT 0|gedo25aprobadopor int NOT NULL DEFAULT 0|gedo25correosalida varchar(100) NULL";}
	if ($dbversion==8319){$sSQL="INSERT INTO comp16estadocompra (comp16id, comp16nombre) VALUES (9, 'Anulado')";}
	if ($dbversion==8320){$sSQL="add_campos|gedo25salidas|gedo25destino_usuario int NOT NULL DEFAULT 0|gedo25docorigen int NOT NULL DEFAULT 0|gedo25docarchivo int NOT NULL DEFAULT 0|gedo25codigoverifica varchar(20) NULL";}

	if ($dbversion==8321){$sSQL="INSERT INTO gedo71estadosalida (gedo71id, gedo71nombre) VALUES (5, 'Radicado')";}
	if ($dbversion==8322){$sSQL="INSERT INTO saiu60estadotramite (saiu60id, saiu60nombre, saiu60tipo1, saiu60tipo707) VALUES (98, 'Abandono', 98, 98)";}
	if ($dbversion==8323){$sSQL=$u01."(52, 'SSAM 2', 'Sistema de Seguimiento a Acciones de Mejora V2', 'S', 'S', 1, 0, 0)";}
	if ($dbversion==8324){$sSQL="CREATE TABLE ssam01sistemas (ssam01consec int NOT NULL, ssam01id int NOT NULL DEFAULT 0, ssam01nombre varchar(100) NULL, ssam01codigo varchar(10) NULL, ssam01orden int NOT NULL DEFAULT 0, ssam01activo int NOT NULL DEFAULT 0, ssam01aplicable int NOT NULL DEFAULT 0, ssam01numanexos int NOT NULL DEFAULT 0)";}
	if ($dbversion==8325){$sSQL="ALTER TABLE ssam01sistemas ADD PRIMARY KEY(ssam01id)";}
	if ($dbversion==8326){$sSQL=$objDB->sSQLCrearIndice('ssam01sistemas', 'ssam01sistemas_id', 'ssam01consec', true);}
	if ($dbversion==8327){$sSQL="agregamodulo|5201|52|Sistemas asociados|1|2|3|4|5|6|8";}
	if ($dbversion==8328){$sSQL=$u09."(5201, 1, 'Sistemas asociados', 'ssamsistemas.php', 2, 5201, 'S', '', '')";}
	if ($dbversion==8329){$sSQL=$unad70."(5201,5202,'ssam02origenes','ssam02id','ssam02idsistema','El dato esta incluido en Origenes de formulación', '')";}
	if ($dbversion==8330){$sSQL="CREATE TABLE ssam02origenes (ssam02consec int NOT NULL, ssam02id int NOT NULL DEFAULT 0, ssam02idsistema int NOT NULL DEFAULT 0, ssam02nombre varchar(200) NULL, ssam02activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8331){$sSQL="ALTER TABLE ssam02origenes ADD PRIMARY KEY(ssam02id)";}
	if ($dbversion==8332){$sSQL=$objDB->sSQLCrearIndice('ssam02origenes', 'ssam02origenes_id', 'ssam02consec', true);}
	if ($dbversion==8333){$sSQL="agregamodulo|5202|52|Origenes de formulación|1|2|3|4|5|6|8";}
	if ($dbversion==8334){$sSQL=$u09."(5202, 1, 'Origenes de formulación', 'ssamorigenes.php', 2, 5202, 'S', '', '')";}
	if ($dbversion==8335){$sSQL="CREATE TABLE ssam03fuentes (ssam03consec int NOT NULL, ssam03id int NOT NULL DEFAULT 0, ssam03nombre varchar(100) NULL, ssam03descripcion Text NULL, ssam03activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8336){$sSQL="ALTER TABLE ssam03fuentes ADD PRIMARY KEY(ssam03id)";}
	if ($dbversion==8337){$sSQL=$objDB->sSQLCrearIndice('ssam03fuentes', 'ssam03fuentes_id', 'ssam03consec', true);}
	if ($dbversion==8338){$sSQL="agregamodulo|5203|52|Fuentes de hallazgos|1|2|3|4|5|6|8";}
	if ($dbversion==8339){$sSQL=$u09."(5203, 1, 'Fuentes de hallazgos', 'ssamfuentes.php', 2, 5203, 'S', '', '')";}
	if ($dbversion==8340){$sSQL="CREATE TABLE ssam04hallazgos (ssam04consec int NOT NULL, ssam04id int NOT NULL DEFAULT 0, ssam04nombre varchar(100) NULL, ssam04descripcion Text NULL, ssam04reqanalisis int NOT NULL DEFAULT 0, ssam04reqcorrecion int NOT NULL DEFAULT 0)";}
	if ($dbversion==8341){$sSQL="ALTER TABLE ssam04hallazgos ADD PRIMARY KEY(ssam04id)";}
	if ($dbversion==8342){$sSQL=$objDB->sSQLCrearIndice('ssam04hallazgos', 'ssam04hallazgos_id', 'ssam04consec', true);}
	if ($dbversion==8343){$sSQL="agregamodulo|5204|52|Tipos de hallazgos|1|2|3|4|5|6|8";}
	if ($dbversion==8344){$sSQL=$u09."(5204, 1, 'Tipos de hallazgos', 'ssamhallazgos.php', 2, 5204, 'S', '', '')";}
	if ($dbversion==8345){$sSQL=$unad70."(5203,5205,'ssam05hallazgosfuentes','','ssam05idfuente','El dato esta incluido en Fuentes que aplican', '')";}
	if ($dbversion==8346){$sSQL="CREATE TABLE ssam05hallazgosfuentes (ssam05idhallazgo int NOT NULL, ssam05idfuente int NOT NULL, ssam05id int NOT NULL DEFAULT 0, ssam05activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8347){$sSQL="ALTER TABLE ssam05hallazgosfuentes ADD PRIMARY KEY(ssam05idhallazgo, ssam05idfuente)";}
	if ($dbversion==8348){$sSQL=$objDB->sSQLCrearIndice('ssam05hallazgosfuentes', 'ssam05hallazgosfuentes_padre', 'ssam05idhallazgo');}
	if ($dbversion==8349){$sSQL="agregamodulo|5205|52|Fuentes que aplican|1|2|3|4|5|6";}
	if ($dbversion==8350){$sSQL="CREATE TABLE ssam06tipoaccion (ssam06consec int NOT NULL, ssam06id int NOT NULL DEFAULT 0, ssam06nombre varchar(100) NULL, ssam06descripcion Text NULL, ssam06activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8351){$sSQL="ALTER TABLE ssam06tipoaccion ADD PRIMARY KEY(ssam06id)";}
	if ($dbversion==8352){$sSQL=$objDB->sSQLCrearIndice('ssam06tipoaccion', 'ssam06tipoaccion_id', 'ssam06consec', true);}
	if ($dbversion==8353){$sSQL="agregamodulo|5206|52|Tipos de acciones|1|2|3|4|5|6|8";}
	if ($dbversion==8354){$sSQL=$u09."(5206, 1, 'Tipos de acciones', 'ssamtipoaccion.php', 2, 5206, 'S', '', '')";}
	if ($dbversion==8355){$sSQL=$unad70."(5204,5207,'ssam07accionhallazgo','','ssam07idhallazgo','El dato esta incluido en Hallazgos que aplican', '')";}
	if ($dbversion==8356){$sSQL="CREATE TABLE ssam07accionhallazgo (ssam07idaccion int NOT NULL, ssam07idhallazgo int NOT NULL, ssam07id int NOT NULL DEFAULT 0, ssam07obligatorio int NOT NULL DEFAULT 0, ssam07activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8357){$sSQL="ALTER TABLE ssam07accionhallazgo ADD PRIMARY KEY(ssam07id)";}
	if ($dbversion==8358){$sSQL=$objDB->sSQLCrearIndice('ssam07accionhallazgo', 'ssam07accionhallazgo_id', 'ssam07idaccion, ssam07idhallazgo', true);}
	if ($dbversion==8359){$sSQL=$objDB->sSQLCrearIndice('ssam07accionhallazgo', 'ssam07accionhallazgo_padre', 'ssam07idaccion');}
	if ($dbversion==8360){$sSQL="agregamodulo|5207|52|Hallazgos que aplican|1|2|3|4|5|6|8";}
	if ($dbversion==8361){$sSQL=$unad70."(5204,5208,'ssam08tipoauditoria','','ssam08idtipohallazgo','El dato esta incluido en Tipos de resultados de auditoria', '')";}
	if ($dbversion==8362){$sSQL="CREATE TABLE ssam08tipoauditoria (ssam08consec int NOT NULL, ssam08id int NOT NULL, ssam08nombre varchar(100) NULL, ssam08descripcion Text NULL, ssam08aplicadetalles int NOT NULL DEFAULT 0, ssam08reqanexo int NOT NULL DEFAULT 0, ssam08reqplan int NOT NULL DEFAULT 0, ssam08reqrealimentar int NOT NULL DEFAULT 0, ssam08color varchar(7) NULL, ssam08idtipohallazgo int NOT NULL DEFAULT 0, ssam08activo int NOT NULL DEFAULT 0, ssam08orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==8363){$sSQL="ALTER TABLE ssam08tipoauditoria ADD PRIMARY KEY(ssam08consec, ssam08id)";}
	if ($dbversion==8364){$sSQL="agregamodulo|5208|52|Tipos de resultados de auditoria|1|2|3|4|5|6";}
	if ($dbversion==8365){$sSQL=$u09."(5208, 1, 'Tipos de resultados de auditoria', 'ssamtipoauditoria.php', 2, 5208, 'S', '', '')";}
	if ($dbversion==8366){$sSQL="CREATE TABLE ssam09plan (ssam09consec int NOT NULL, ssam09idsistema int NOT NULL, ssam09idorigen int NOT NULL, ssam09idfuente int NOT NULL, ssam09id int NOT NULL DEFAULT 0, ssam09idproceso int NOT NULL DEFAULT 0, ssam09idprograma int NOT NULL DEFAULT 0, ssam09idzona int NOT NULL DEFAULT 0, ssam09idcentro int NOT NULL DEFAULT 0, ssam09tipoplan int NOT NULL DEFAULT 0, ssam09idformulador int NOT NULL DEFAULT 0, ssam09descripcion Text NULL, ssam09idorigen_1 int NOT NULL DEFAULT 0, ssam09idanexo_1 int NOT NULL DEFAULT 0, ssam09idorigen_2 int NOT NULL DEFAULT 0, ssam09idanexo_2 int NOT NULL DEFAULT 0, ssam09momento int NOT NULL DEFAULT 0, ssam09estado int NOT NULL DEFAULT 0, ssam09idrevisor int NOT NULL DEFAULT 0)";}
	if ($dbversion==8367){$sSQL="ALTER TABLE ssam09plan ADD PRIMARY KEY(ssam09id)";}
	if ($dbversion==8368){$sSQL=$objDB->sSQLCrearIndice('ssam09plan', 'ssam09plan_id', 'ssam09consec, ssam09idsistema, ssam09idorigen, ssam09idfuente', true);}
	if ($dbversion==8369){$sSQL="agregamodulo|5209|52|Planes|1|2|3|4|5|6|8";}
	if ($dbversion==8370){$sSQL=$u09."(5209, 1, 'Planes', 'ssamplanes.php', 3704, 5209, 'S', '', '')";}
	if ($dbversion==8371){$sSQL="CREATE TABLE ssam10planhallazgos (ssam10idplan int NOT NULL, ssam10consec int NOT NULL, ssam10id int NOT NULL DEFAULT 0, ssam10idhallazgo int NOT NULL DEFAULT 0, ssam10fechahallazgo int NOT NULL DEFAULT 0, ssam10descripcion Text NULL, ssam10descripcioncausa Text NULL, ssam10idorigen int NOT NULL DEFAULT 0, ssam10idanexo int NOT NULL DEFAULT 0, ssam10estado int NOT NULL DEFAULT 0)";}
	if ($dbversion==8372){$sSQL="ALTER TABLE ssam10planhallazgos ADD PRIMARY KEY(ssam10id)";}
	if ($dbversion==8373){$sSQL=$objDB->sSQLCrearIndice('ssam10planhallazgos', 'ssam10planhallazgos_id', 'ssam10idplan, ssam10consec', true);}
	if ($dbversion==8374){$sSQL=$objDB->sSQLCrearIndice('ssam10planhallazgos', 'ssam10planhallazgos_padre', 'ssam10idplan');}
	if ($dbversion==8375){$sSQL="agregamodulo|5210|52|Hallazgos|1|2|3|4|5|6|8";}
	if ($dbversion==8376){$sSQL=$unad70."(5210,5211,'ssam11plancorreccion','','ssam11idhallazgo','El dato esta incluido en Corrección del hallazgo', '')";}
	if ($dbversion==8377){$sSQL="CREATE TABLE ssam11plancorreccion (ssam11idplan int NOT NULL, ssam11idhallazgo int NOT NULL, ssam11consec int NOT NULL, ssam11id int NOT NULL DEFAULT 0, ssam11descripcion Text NULL, ssam11fecha int NOT NULL DEFAULT 0, ssam11idorigen int NOT NULL DEFAULT 0, ssam11idanexo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8378){$sSQL="ALTER TABLE ssam11plancorreccion ADD PRIMARY KEY(ssam11id)";}
	if ($dbversion==8379){$sSQL=$objDB->sSQLCrearIndice('ssam11plancorreccion', 'ssam11plancorreccion_id', 'ssam11idplan, ssam11idhallazgo, ssam11consec', true);}
	if ($dbversion==8380){$sSQL=$objDB->sSQLCrearIndice('ssam11plancorreccion', 'ssam11plancorreccion_padre', 'ssam11idplan');}
	if ($dbversion==8381){$sSQL="agregamodulo|5211|52|Corrección del hallazgo|1|2|3|4|5|6|8";}
	if ($dbversion==8382){$sSQL=$unad70."(5206,5212,'ssam12planacciones','','ssam12idtipoaccion','El dato esta incluido en Acciones', '')";}
	if ($dbversion==8383){$sSQL="CREATE TABLE ssam12planacciones (ssam12idplan int NOT NULL, ssam12idtipoaccion int NOT NULL, ssam12consec int NOT NULL, ssam12id int NOT NULL DEFAULT 0, ssam12fechaini int NOT NULL DEFAULT 0, ssam12fechafin int NOT NULL DEFAULT 0, ssam12idrecurso int NOT NULL DEFAULT 0, ssam12detalle Text NULL, ssam12producto varchar(250) NULL, ssam12aceptada int NOT NULL DEFAULT 0, ssam12estado int NOT NULL DEFAULT 0)";}
	if ($dbversion==8384){$sSQL="ALTER TABLE ssam12planacciones ADD PRIMARY KEY(ssam12id)";}
	if ($dbversion==8385){$sSQL=$objDB->sSQLCrearIndice('ssam12planacciones', 'ssam12planacciones_id', 'ssam12idplan, ssam12idtipoaccion, ssam12consec', true);}
	if ($dbversion==8386){$sSQL=$objDB->sSQLCrearIndice('ssam12planacciones', 'ssam12planacciones_padre', 'ssam12idplan');}
	if ($dbversion==8387){$sSQL="agregamodulo|5212|52|Acciones|1|2|3|4|5|6|8";}
	if ($dbversion==8388){$sSQL=$unad70."(5210,5213,'ssam13accionhallazgos','','ssam13idhallazgo','El dato esta incluido en Hallazgos relacionados', '')";}
	if ($dbversion==8389){$sSQL="CREATE TABLE ssam13accionhallazgos (ssam13idaccion int NOT NULL, ssam13idhallazgo int NOT NULL, ssam13id int NOT NULL DEFAULT 0)";}
	if ($dbversion==8390){$sSQL="ALTER TABLE ssam13accionhallazgos ADD PRIMARY KEY(ssam13id)";}
	if ($dbversion==8391){$sSQL=$objDB->sSQLCrearIndice('ssam13accionhallazgos', 'ssam13accionhallazgos_id', 'ssam13idaccion, ssam13idhallazgo', true);}
	if ($dbversion==8392){$sSQL=$objDB->sSQLCrearIndice('ssam13accionhallazgos', 'ssam13accionhallazgos_padre', 'ssam13idaccion');}
	if ($dbversion==8393){$sSQL="agregamodulo|5213|52|Hallazgos relacionados|1|2|3|4|5|6|8";}
	if ($dbversion==8394){$sSQL="CREATE TABLE ssam14accionresponsable (ssam14idaccion int NOT NULL, ssam14idresponsable int NOT NULL, ssam14id int NOT NULL DEFAULT 0, ssam14aceptado int NOT NULL DEFAULT 0, ssam14motivo Text NULL, ssam14fechaacepta int NOT NULL DEFAULT 0, ssam14porcavance Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==8395){$sSQL="ALTER TABLE ssam14accionresponsable ADD PRIMARY KEY(ssam14id)";}
	if ($dbversion==8396){$sSQL=$objDB->sSQLCrearIndice('ssam14accionresponsable', 'ssam14accionresponsable_id', 'ssam14idaccion, ssam14idresponsable', true);}
	if ($dbversion==8397){$sSQL=$objDB->sSQLCrearIndice('ssam14accionresponsable', 'ssam14accionresponsable_padre', 'ssam14idaccion');}
	if ($dbversion==8398){$sSQL="agregamodulo|5214|52|Responsables|1|2|3|4|5|6|8";}
	if ($dbversion==8399){$sSQL="CREATE TABLE ssam15planrevision (ssam15idplan int NOT NULL, ssam15idhallazgo int NOT NULL, ssam15idaccion int NOT NULL, ssam15consec int NOT NULL, ssam15id int NOT NULL DEFAULT 0, ssam15detalle Text NULL, ssam15estadorevisa int NOT NULL DEFAULT 0, ssam15fecha int NOT NULL DEFAULT 0)";}	
	if ($dbversion==8400){$sSQL="ALTER TABLE ssam15planrevision ADD PRIMARY KEY(ssam15id)";}
	}
if (($dbversion>8400)&&($dbversion<8501)){
	if ($dbversion==8401){$sSQL=$objDB->sSQLCrearIndice('ssam15planrevision', 'ssam15planrevision_id', 'ssam15idplan, ssam15idhallazgo, ssam15idaccion, ssam15consec', true);}
	if ($dbversion==8402){$sSQL=$objDB->sSQLCrearIndice('ssam15planrevision', 'ssam15planrevision_padre', 'ssam15idplan');}
	if ($dbversion==8403){$sSQL="agregamodulo|5215|52|Revisiones del plan|1|2|3|4|5|6|8";}
	if ($dbversion==8404){$sSQL=$u09."(5215, 1, 'Revisiones del plan', 'ssamrevision.php', 3704, 5215, 'S', '', '')";}
	if ($dbversion==8405){$sSQL="agregamodulo|5244|52|Mis acciones|1|2|3|4|5|6|8";}
	if ($dbversion==8406){$sSQL=$u09."(5244, 1, 'Mis acciones', 'ssammisacciones.php', 3704, 5244, 'S', '', '')";}
	if ($dbversion==8407){$sSQL="CREATE TABLE ssam16registroavance (ssam16idaccion int NOT NULL, ssam16consec int NOT NULL, ssam16id int NOT NULL DEFAULT 0, ssam16detalle Text NULL, ssam16fecha int NOT NULL DEFAULT 0, ssam16porcavance int NOT NULL DEFAULT 0, ssam16idorigen int NOT NULL DEFAULT 0, ssam16idanexo int NOT NULL DEFAULT 0, ssam16idusuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==8408){$sSQL="ALTER TABLE ssam16registroavance ADD PRIMARY KEY(ssam16id)";}
	if ($dbversion==8409){$sSQL=$objDB->sSQLCrearIndice('ssam16registroavance', 'ssam16registroavance_id', 'ssam16idaccion, ssam16consec', true);}
	if ($dbversion==8410){$sSQL=$objDB->sSQLCrearIndice('ssam16registroavance', 'ssam16registroavance_padre', 'ssam16idaccion');}
	if ($dbversion==8411){$sSQL="agregamodulo|5216|52|Registros de avance|1|2|3|4|5|6|8";}
	if ($dbversion==8412){$sSQL="CREATE TABLE ssam40aplicables (ssam40id int NOT NULL, ssam40nombre varchar(50) NULL)";}
	if ($dbversion==8413){$sSQL="ALTER TABLE ssam40aplicables ADD PRIMARY KEY(ssam40id)";}
	if ($dbversion==8414){$sSQL="INSERT INTO ssam40aplicables (ssam40id, ssam40nombre) VALUES (1, 'Procesos'), (2, 'Zonas'), (3, 'Centros'), (4, 'Programas')";}
	if ($dbversion==8415){$sSQL="CREATE TABLE ssam41momentoplan (ssam41id int NOT NULL, ssam41nombre varchar(50) NULL)";}
	if ($dbversion==8416){$sSQL="ALTER TABLE ssam41momentoplan ADD PRIMARY KEY(ssam41id)";}
	if ($dbversion==8417){$sSQL="INSERT INTO ssam41momentoplan (ssam41id, ssam41nombre) VALUES (1, 'Descripción'), (2, 'Hallazgos'), (3, 'Acciones'), (4, 'Concertación de Acciones'), (5, 'Confirmar envio')";}
	if ($dbversion==8418){$sSQL="CREATE TABLE ssam42estadoplan (ssam42id int NOT NULL, ssam42nombre varchar(50) NULL)";}
	if ($dbversion==8419){$sSQL="ALTER TABLE ssam42estadoplan ADD PRIMARY KEY(ssam42id)";}
	if ($dbversion==8420){$sSQL="INSERT INTO ssam42estadoplan (ssam42id, ssam42nombre) VALUES (0, 'En formulación'), (1, 'En revisión'), (5, 'Aprobado'), (7, 'En ajuste'), (9, 'Finalizado')";}
	if ($dbversion==8421){$sSQL="CREATE TABLE ssam43estadoaccion (ssam43id int NOT NULL, ssam43nombre varchar(50) NULL)";}
	if ($dbversion==8422){$sSQL="ALTER TABLE ssam43estadoaccion ADD PRIMARY KEY(ssam43id)";}
	if ($dbversion==8423){$sSQL="INSERT INTO ssam43estadoaccion (ssam43id, ssam43nombre) VALUES (0, 'No iniciada'), (1, 'En curso'), (7, 'Vencida'), (9, 'Cerrada')";}
	if ($dbversion==8424){$sSQL="CREATE TABLE ssam44estadorevision (ssam44id int NOT NULL, ssam44nombre varchar(50) NULL)";}
	if ($dbversion==8425){$sSQL="ALTER TABLE ssam44estadorevision ADD PRIMARY KEY(ssam44id)";}
	if ($dbversion==8426){$sSQL="INSERT INTO ssam44estadorevision (ssam44id, ssam44nombre) VALUES (0, 'En revisión'), (5, 'Aprobada'), (7, 'En ajuste')";}

	if ($dbversion==8427){$sSQL=$unad70."(2,2752,'grad52fichaegresado','','grad52idprograma','El dato esta incluido en Ficha egresado', '')";}
	if ($dbversion==8428){$sSQL=$unad70."(3,2752,'grad52fichaegresado','','grad52idpei','El dato esta incluido en Ficha egresado', '')";}
	if ($dbversion==8429){$sSQL="CREATE TABLE grad52fichaegresado (grad52idtercero int NOT NULL, grad52idescuela int NOT NULL, grad52idprograma int NOT NULL, grad52idpei int NOT NULL, grad52id int NOT NULL DEFAULT 0, grad52momento int NOT NULL DEFAULT 0, grad52fechacrea int NOT NULL DEFAULT 0, grad52horacrea int NOT NULL DEFAULT 0, grad52mincrea int NOT NULL DEFAULT 0, grad52fechadiligencia int NOT NULL DEFAULT 0, grad52horadiligencia int NOT NULL DEFAULT 0, grad52mindiligencia int NOT NULL DEFAULT 0, grad52fechafinaliza int NOT NULL DEFAULT 0, grad52horafinaliza int NOT NULL DEFAULT 0, grad52minfinaliza int NOT NULL DEFAULT 0, grad52pers_estadocivil int NOT NULL DEFAULT 0, grad52pers_numhijos int NOT NULL DEFAULT 0, grad52pers_vivienda int NOT NULL DEFAULT 0, grad52pers_educpadre int NOT NULL DEFAULT 0, grad52pers_ocuppadre int NOT NULL DEFAULT 0, grad52pers_educmadre int NOT NULL DEFAULT 0, grad52pers_ocupmadre int NOT NULL DEFAULT 0, grad52pers_etnia int NOT NULL DEFAULT 0, grad52pers_grupoblacion int NOT NULL DEFAULT 0, grad52pers_limitaciones int NOT NULL DEFAULT 0, grad52pers_desempeno int NOT NULL DEFAULT 0, grad52hist_edadbach int NOT NULL DEFAULT 0, grad52hist_tiempoeducsupe int NOT NULL DEFAULT 0, grad52hist_razoneducsupe int NOT NULL DEFAULT 0, grad52hist_tematica1 Text NULL, grad52hist_selecarrera int NOT NULL DEFAULT 0, grad52hist_fuenfinapropio int NOT NULL DEFAULT 0, grad52hist_fuenfinapadres int NOT NULL DEFAULT 0, grad52hist_fuenfinafamilia int NOT NULL DEFAULT 0, grad52hist_fuenfinabeca int NOT NULL DEFAULT 0, grad52hist_fuenfinacredito int NOT NULL DEFAULT 0, grad52hist_fuenfinaotro int NOT NULL DEFAULT 0, grad52hist_entiinstitu int NOT NULL DEFAULT 0, grad52hist_entiicetex int NOT NULL DEFAULT 0, grad52hist_entigobnacion int NOT NULL DEFAULT 0, grad52hist_entigobdistrito int NOT NULL DEFAULT 0, grad52hist_entiempresa int NOT NULL DEFAULT 0, grad52hist_entiotra int NOT NULL DEFAULT 0, grad52hist_credicetex int NOT NULL DEFAULT 0, grad52hist_credentipub int NOT NULL DEFAULT 0, grad52hist_credentifin int NOT NULL DEFAULT 0, grad52hist_credinstitu int NOT NULL DEFAULT 0, grad52hist_credfundac int NOT NULL DEFAULT 0, grad52hist_credentiotra int NOT NULL DEFAULT 0, grad52comp_colebilingue int NOT NULL DEFAULT 0, grad52comp_compidioma int NOT NULL DEFAULT 0, grad52comp_idiomaingles int NOT NULL DEFAULT 0, grad52comp_hablaingles int NOT NULL DEFAULT 0, grad52comp_escuingles int NOT NULL DEFAULT 0, grad52comp_escringles int NOT NULL DEFAULT 0, grad52comp_idiomafrances int NOT NULL DEFAULT 0, grad52comp_hablafrances int NOT NULL DEFAULT 0, grad52comp_escufrances int NOT NULL DEFAULT 0, grad52comp_escrfrances int NOT NULL DEFAULT 0, grad52comp_idiomaitaliano int NOT NULL DEFAULT 0, grad52comp_hablaitaliano int NOT NULL DEFAULT 0, grad52comp_escuitaliano int NOT NULL DEFAULT 0, grad52comp_escritaliano int NOT NULL DEFAULT 0, grad52comp_idiomaportu int NOT NULL DEFAULT 0, grad52comp_hablaportu int NOT NULL DEFAULT 0, grad52comp_escuportu int NOT NULL DEFAULT 0, grad52comp_escrportu int NOT NULL DEFAULT 0, grad52comp_idiomamandarin int NOT NULL DEFAULT 0, grad52comp_hablamand int NOT NULL DEFAULT 0, grad52comp_escumand int NOT NULL DEFAULT 0, grad52comp_escrmand int NOT NULL DEFAULT 0, grad52comp_idiomaaleman int NOT NULL DEFAULT 0, grad52comp_hablaaleman int NOT NULL DEFAULT 0, grad52comp_escualeman int NOT NULL DEFAULT 0, grad52comp_escraleman int NOT NULL DEFAULT 0, grad52comp_idiomajapones int NOT NULL DEFAULT 0, grad52comp_hablajapo int NOT NULL DEFAULT 0, grad52comp_escujapo int NOT NULL DEFAULT 0, grad52comp_escrjapo int NOT NULL DEFAULT 0, grad52comp_idiomaarabe int NOT NULL DEFAULT 0, grad52comp_hablaarabe int NOT NULL DEFAULT 0, grad52comp_escuarabe int NOT NULL DEFAULT 0, grad52comp_escrarabe int NOT NULL DEFAULT 0, grad52comp_escritos int NOT NULL DEFAULT 0, grad52comp_oralidad int NOT NULL DEFAULT 0, grad52comp_persuasion int NOT NULL DEFAULT 0, grad52comp_noverbal int NOT NULL DEFAULT 0, grad52comp_diferencias int NOT NULL DEFAULT 0, grad52comp_informatica int NOT NULL DEFAULT 0, grad52comp_actualizacion int NOT NULL DEFAULT 0, grad52comp_creativo int NOT NULL DEFAULT 0, grad52comp_analisis int NOT NULL DEFAULT 0, grad52comp_investiga int NOT NULL DEFAULT 0, grad52comp_soluciones int NOT NULL DEFAULT 0, grad52comp_problemas int NOT NULL DEFAULT 0, grad52comp_abstraccion int NOT NULL DEFAULT 0, grad52comp_comprension int NOT NULL DEFAULT 0, grad52comp_convivencia int NOT NULL DEFAULT 0, grad52comp_decisiones int NOT NULL DEFAULT 0, grad52plan_planvtecnica int NOT NULL DEFAULT 0, grad52plan_planvtecnol int NOT NULL DEFAULT 0, grad52plan_planvuniver int NOT NULL DEFAULT 0, grad52plan_planvposgcol int NOT NULL DEFAULT 0, grad52plan_planvposgext int NOT NULL DEFAULT 0, grad52plan_planvtrabcol int NOT NULL DEFAULT 0, grad52plan_planvtrabext int NOT NULL DEFAULT 0, grad52plan_planvempresa int NOT NULL DEFAULT 0, grad52plan_planvotro int NOT NULL DEFAULT 0, grad52labo_ocupacion int NOT NULL DEFAULT 0, grad52labo_actividad int NOT NULL DEFAULT 0, grad52labo_diligtrabajo int NOT NULL DEFAULT 0, grad52labo_constrabajo int NOT NULL DEFAULT 0, grad52labo_motivodilig int NOT NULL DEFAULT 0, grad52labo_disponiblesem int NOT NULL DEFAULT 0, grad52labo_actividad_es int NOT NULL DEFAULT 0, grad52labo_primeremp int NOT NULL DEFAULT 0, grad52labo_canalemp int NOT NULL DEFAULT 0, grad52labo_vinculaemp int NOT NULL DEFAULT 0, grad52labo_ocupaact int NOT NULL DEFAULT 0, grad52labo_acteconom int NOT NULL DEFAULT 0, grad52labo_relacarrera int NOT NULL DEFAULT 0, grad52labo_ingresolab int NOT NULL DEFAULT 0, grad52labo_horassem int NOT NULL DEFAULT 0, grad52labo_actiempresa int NOT NULL DEFAULT 0, grad52labo_estudiolabora int NOT NULL DEFAULT 0, grad52labo_cpprimeremp int NOT NULL DEFAULT 0, grad52labo_cpcuentapr int NOT NULL DEFAULT 0, grad52labo_cpformatrab int NOT NULL DEFAULT 0, grad52labo_activeconom int NOT NULL DEFAULT 0, grad52labo_ingresomes int NOT NULL DEFAULT 0, grad52labo_crearempresa int NOT NULL DEFAULT 0, grad52labo_dificcrearemp int NOT NULL DEFAULT 0, grad52labo_dificcrearcual varchar(200) NULL, grad52labo_socprimeremp int NOT NULL DEFAULT 0, grad52labo_soccuentapr int NOT NULL DEFAULT 0, grad52labo_socacteco int NOT NULL DEFAULT 0, grad52labo_socingmes int NOT NULL DEFAULT 0, grad52labo_conocarrera int NOT NULL DEFAULT 0, grad52labo_crecpersonal int NOT NULL DEFAULT 0, grad52labo_satiempleo int NOT NULL DEFAULT 0, grad52labo_niveltrabajo int NOT NULL DEFAULT 0, grad52labo_horasadic int NOT NULL DEFAULT 0, grad52labo_otrotrabajo int NOT NULL DEFAULT 0, grad52labo_mejoring int NOT NULL DEFAULT 0, grad52labo_trabantes int NOT NULL DEFAULT 0, grad52labo_mesesbusca int NOT NULL DEFAULT 0, grad52labo_facilempleo int NOT NULL DEFAULT 0, grad52labo_dificempleo int NOT NULL DEFAULT 0, grad52labo_canalempefec int NOT NULL DEFAULT 0, grad52iden_nuevoest int NOT NULL DEFAULT 0, grad52iden_razonvolver int NOT NULL DEFAULT 0, grad52iden_otrosrazon varchar(200) NULL, grad52iden_razonnovolver int NOT NULL DEFAULT 0, grad52iden_otrosrazonno varchar(200) NULL, grad52iden_futuroest int NOT NULL DEFAULT 0, grad52iden_otrosest int NOT NULL DEFAULT 0, grad52iden_recomienda int NOT NULL DEFAULT 0, grad52iden_correspmision int NOT NULL DEFAULT 0, grad52sati_relinterpers int NOT NULL DEFAULT 0, grad52sati_formacadem int NOT NULL DEFAULT 0, grad52sati_fundteorica int NOT NULL DEFAULT 0, grad52sati_disptiempo int NOT NULL DEFAULT 0, grad52sati_procaprend int NOT NULL DEFAULT 0, grad52sati_trabcampo int NOT NULL DEFAULT 0, grad52sati_apoyoest int NOT NULL DEFAULT 0, grad52sati_intercambio int NOT NULL DEFAULT 0, grad52sati_pracempresa int NOT NULL DEFAULT 0, grad52sati_oporempleo int NOT NULL DEFAULT 0, grad52sati_investigacion int NOT NULL DEFAULT 0, grad52sati_seminarios int NOT NULL DEFAULT 0, grad52sati_asismedica int NOT NULL DEFAULT 0, grad52sati_asisespiritual int NOT NULL DEFAULT 0, grad52sati_agiladmin int NOT NULL DEFAULT 0, grad52sati_atencionadm int NOT NULL DEFAULT 0, grad52sati_aulasvirt int NOT NULL DEFAULT 0, grad52sati_laboratorio int NOT NULL DEFAULT 0, grad52sati_espacioest int NOT NULL DEFAULT 0, grad52sati_ayuaudiov int NOT NULL DEFAULT 0, grad52sati_aulasinform int NOT NULL DEFAULT 0, grad52sati_escedeporte int NOT NULL DEFAULT 0, grad52sati_escecultural int NOT NULL DEFAULT 0, grad52sati_biblioteca int NOT NULL DEFAULT 0, grad52sati_medicomunica int NOT NULL DEFAULT 0, grad52eval_rpta1 int NOT NULL DEFAULT 0, grad52eval_rpta2 int NOT NULL DEFAULT 0, grad52eval_rpta3 int NOT NULL DEFAULT 0, grad52eval_rpta4 int NOT NULL DEFAULT 0, grad52eval_rpta5 int NOT NULL DEFAULT 0, grad52eval_rpta6 int NOT NULL DEFAULT 0, grad52eval_rpta7 int NOT NULL DEFAULT 0, grad52eval_rpta8 int NOT NULL DEFAULT 0, grad52eval_rpta9 int NOT NULL DEFAULT 0, grad52eval_rpta10 int NOT NULL DEFAULT 0, grad52eval_rpta11 int NOT NULL DEFAULT 0, grad52eval_rpta12 int NOT NULL DEFAULT 0, grad52eval_rpta13 int NOT NULL DEFAULT 0, grad52eval_rpta14 int NOT NULL DEFAULT 0, grad52eval_rpta15 int NOT NULL DEFAULT 0, grad52eval_rpta16 int NOT NULL DEFAULT 0, grad52eval_rpta17 int NOT NULL DEFAULT 0, grad52eval_rpta18 int NOT NULL DEFAULT 0, grad52eval_rpta19 int NOT NULL DEFAULT 0, grad52eval_rpta20 int NOT NULL DEFAULT 0, grad52estado int NOT NULL DEFAULT 0)";}
	if ($dbversion==8430){$sSQL="ALTER TABLE grad52fichaegresado ADD PRIMARY KEY(grad52id)";}
	if ($dbversion==8431){$sSQL=$objDB->sSQLCrearIndice('grad52fichaegresado', 'grad52fichaegresado_id', 'grad52idtercero, grad52idescuela, grad52idprograma, grad52idpei', true);}
	if ($dbversion==8432){$sSQL="agregamodulo|2752|27|Ficha egresado|1|2|3|4|5|6|8";}
	if ($dbversion==8433){$sSQL=$u09."(2752, 1, 'Ficha egresado', 'gradfichaegresado.php', 2201, 2752, 'S', '', '')";}
	
	if ($dbversion==8434){$sSQL="CREATE TABLE gedo29trasportador (gedo29consec int NOT NULL, gedo29id int NOT NULL DEFAULT 0, gedo29activo int NOT NULL DEFAULT 0, gedo29orden int NOT NULL DEFAULT 0, gedo29nombre varchar(50) NULL)";}
	if ($dbversion==8435){$sSQL="ALTER TABLE gedo29trasportador ADD PRIMARY KEY(gedo29id)";}
	if ($dbversion==8436){$sSQL=$objDB->sSQLCrearIndice('gedo29trasportador', 'gedo29trasportador_id', 'gedo29consec', true);}
	if ($dbversion==8437){$sSQL="agregamodulo|2629|26|Transportadores|1|2|3|4|5|6";}
	if ($dbversion==8438){$sSQL=$u09."(2629, 1, 'Transportadores', 'gedotransporte.php', 2, 2629, 'S', '', '')";}
	if ($dbversion==8439){$sSQL="add_campos|core71homolsolicitud|core71nivelprograma int NOT NULL DEFAULT 0";}
	if ($dbversion==8440){$sSQL="agregamodulo|3042|30|Tiempo de respuesta em devoluciones|1|5|6";}
	if ($dbversion==8441){$sSQL=$u09."(3042, 1, 'Tiempo de respuesta em devoluciones', 'saiurpttiempotram.php', 11, 3042, 'S', '', '')";}
	if ($dbversion==8442){$sSQL="add_campos|empr04emprendimiento|empr04workshops int NOT NULL DEFAULT -1";}
	if ($dbversion==8443){$sSQL="add_campos|unad95entorno|unad95idoficina int NOT NULL DEFAULT 0|unad95idunidad int NOT NULL DEFAULT 0";}
	if ($dbversion==8444){$sSQL="add_campos|unae31oficina|unae31idadmincorresp int NOT NULL DEFAULT 0";}
	if ($dbversion==8445){$sSQL="add_campos|gedo25salidas|gedo25poseedor int NOT NULL DEFAULT 0";}
	if ($dbversion==8446){$sSQL="add_campos|unae31oficina|unae31equivalenteth int NOT NULL DEFAULT 0";}
	if ($dbversion==8447){$sSQL="add_campos|core09programa|core09codsiho int NOT NULL DEFAULT 0";}
	if ($dbversion==8448){$sSQL="UPDATE core09programa SET core09codsiho=core09codigo";}
	//8449 queda libre
	if ($dbversion==8450){$sSQL="add_campos|core12escuela|core12codigotrd varchar(20) NULL|core12admincorrespondencia int NOT NULL DEFAULT 0";}

	if ($dbversion==8451){$sSQL="DROP TABLE cttc30balancefinan";}
	if ($dbversion==8452){$sSQL="CREATE TABLE cttc31balancefinan (cttc31idproceso int NOT NULL, cttc31vigencia int NOT NULL, cttc31mes int NOT NULL, cttc31id int NOT NULL DEFAULT 0, cttc31vrcontratado Decimal(15,2) NULL DEFAULT 0, cttc31pagoanticipo Decimal(15,2) NULL DEFAULT 0, cttc31numotrosi int NOT NULL DEFAULT 0, cttc31mes_vradicion Decimal(15,2) NULL DEFAULT 0, cttc31vradiciones Decimal(15,2) NULL DEFAULT 0, cttc31total_vrcontratado Decimal(15,2) NULL DEFAULT 0, cttc31mes_vrejecutado Decimal(15,2) NULL DEFAULT 0, cttc31total_vrejecutado Decimal(15,2) NULL DEFAULT 0, cttc31mes_vrpagado Decimal(15,2) NULL DEFAULT 0, cttc31total_vrpagado Decimal(15,2) NULL DEFAULT 0, cttc31fechareporte int NOT NULL DEFAULT 0, cttc31estado int NOT NULL DEFAULT 0)";}
	if ($dbversion==8453){$sSQL="ALTER TABLE cttc31balancefinan ADD PRIMARY KEY(cttc31id)";}
	if ($dbversion==8454){$sSQL=$objDB->sSQLCrearIndice('cttc31balancefinan', 'cttc31balancefinan_id', 'cttc31idproceso, cttc31vigencia, cttc31mes', true);}
	if ($dbversion==8455){$sSQL=$objDB->sSQLCrearIndice('cttc31balancefinan', 'cttc31balancefinan_padre', 'cttc31idproceso');}
	if ($dbversion==8456){$sSQL="agregamodulo|4131|41|Proceso cttc - Balance financiero|1|4|5";}
	if ($dbversion==8457){$sSQL="add_campos|gedo25salidas|gedo25destino_zona int NOT NULL DEFAULT 0|gedo25destino_centro int NOT NULL DEFAULT 0|gedo25destino_pais varchar(3) NULL|gedo25destino_depto varchar(5) NULL|gedo25destino_ciudad varchar(8) NULL|gedo25destino_direccion varchar(100) NULL";}

	if ($dbversion==8458){$sSQL="add_campos|cttc02tipoproceso|cttc02prefijo varchar(20) NULL";}
	
	if ($dbversion==8459){$sSQL="CREATE TABLE docu04subtemas (docu04idtema int NOT NULL, docu04consec int NOT NULL, docu04id int NOT NULL DEFAULT 0, docu04nombre varchar(50) NULL, docu04descripcion Text NULL, docu04activo int NOT NULL DEFAULT 0, docu04orden int NOT NULL DEFAULT 0, docu04tipotabla int NOT NULL DEFAULT 0, docu04tipodato int NOT NULL DEFAULT 0)";}
	if ($dbversion==8460){$sSQL="ALTER TABLE docu04subtemas ADD PRIMARY KEY(docu04id)";}
	if ($dbversion==8461){$sSQL=$objDB->sSQLCrearIndice('docu04subtemas', 'docu04subtemas_id', 'docu04idtema, docu04consec', true);}
	if ($dbversion==8462){$sSQL=$objDB->sSQLCrearIndice('docu04subtemas', 'docu04subtemas_padre', 'docu04idtema');}
	if ($dbversion==8463){$sSQL="agregamodulo|9904|99|Subtemas|1|2|3|4|5|6|8";}
	
	if ($dbversion==8464){$sSQL="add_campos|unae26unidadesfun|unae26codigotrd varchar(20) NULL|unae26admincorrespondencia int NOT NULL DEFAULT 0";}

	if ($dbversion==8465){$sSQL=$u08."(504, 'Solicitudes de pago', 'gm.php?id=504', 'Solicitudes de pago', 'Payment requests', 'Pedidos de pagamento')";}
	if ($dbversion==8466){$sSQL="CREATE TABLE ppto35solpago (ppto35idtiposol int NOT NULL, ppto35vigencia int NOT NULL, ppto35consec int NOT NULL, ppto35id int NOT NULL DEFAULT 0, ppto35fechasol int NOT NULL DEFAULT 0, ppto35categoria int NOT NULL DEFAULT 0, ppto35estado int NOT NULL DEFAULT 0, ppto35descripcion Text NULL, ppto35unidadfuncional int NOT NULL DEFAULT 0, ppto35beneficiario int NOT NULL DEFAULT 0, ppto35valor int NOT NULL DEFAULT 0, ppto35fechavence int NOT NULL DEFAULT 0, ppto35idzona int NOT NULL DEFAULT 0, ppto35idcentro int NOT NULL DEFAULT 0, ppto35usuariosol int NOT NULL DEFAULT 0, ppto35idcdp int NOT NULL DEFAULT 0, ppto35idrp int NOT NULL DEFAULT 0, ppto35idop int NOT NULL DEFAULT 0, ppto35idegreso int NOT NULL DEFAULT 0, ppto35fechapago int NOT NULL DEFAULT 0)";}
	if ($dbversion==8467){$sSQL="ALTER TABLE ppto35solpago ADD PRIMARY KEY(ppto35id)";}
	if ($dbversion==8468){$sSQL=$objDB->sSQLCrearIndice('ppto35solpago', 'ppto35solpago_id', 'ppto35idtiposol, ppto35vigencia, ppto35consec', true);}
	if ($dbversion==8469){$sSQL="agregamodulo|535|5|Solicitudes de pago|1|2|3|4|5|6";}
	if ($dbversion==8470){$sSQL=$u09."(535, 1, 'Solicitudes de pago', 'pptosolpago.php', 504, 535, 'S', '', '')";}
	if ($dbversion==8471){$sSQL="CREATE TABLE ppto36solpagorubro (ppto36idsolicitud int NOT NULL, ppto36idcuenta int NOT NULL, ppto36idrecurso int NOT NULL, ppto36idcpc int NOT NULL, ppto36id int NOT NULL DEFAULT 0, ppto36valor Decimal(15,2) NULL DEFAULT 0, ppto36valor2 Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==8472){$sSQL="ALTER TABLE ppto36solpagorubro ADD PRIMARY KEY(ppto36id)";}
	if ($dbversion==8473){$sSQL=$objDB->sSQLCrearIndice('ppto36solpagorubro', 'ppto36solpagorubro_id', 'ppto36idsolicitud, ppto36idcuenta, ppto36idrecurso, ppto36idcpc', true);}
	if ($dbversion==8474){$sSQL=$objDB->sSQLCrearIndice('ppto36solpagorubro', 'ppto36solpagorubro_padre', 'ppto36idsolicitud');}
	if ($dbversion==8475){$sSQL="agregamodulo|536|5|Solicitudes de pago - Rubros|1|2|3|4|5|6";}
	if ($dbversion==8476){$sSQL="CREATE TABLE ppto37solpagoanexo (ppto37idsolicitud int NOT NULL, ppto37consec int NOT NULL, ppto37id int NOT NULL DEFAULT 0, ppto37nombre varchar(200) NULL, ppto37idorigen int NOT NULL DEFAULT 0, ppto37idarchivo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8477){$sSQL="ALTER TABLE ppto37solpagoanexo ADD PRIMARY KEY(ppto37id)";}
	if ($dbversion==8478){$sSQL=$objDB->sSQLCrearIndice('ppto37solpagoanexo', 'ppto37solpagoanexo_id', 'ppto37idsolicitud, ppto37consec', true);}
	if ($dbversion==8479){$sSQL=$objDB->sSQLCrearIndice('ppto37solpagoanexo', 'ppto37solpagoanexo_padre', 'ppto37idsolicitud');}
	if ($dbversion==8480){$sSQL="agregamodulo|537|5|Solicitudes de pago - Anexos|1|2|3|4|5|6";}
	if ($dbversion==8481){$sSQL="CREATE TABLE ppto38solpagocambio (ppto38idsolicitud int NOT NULL, ppto38consec int NOT NULL, ppto38id int NOT NULL DEFAULT 0, ppto38estadoorigen int NOT NULL DEFAULT 0, ppto38estadofin int NOT NULL DEFAULT 0, ppto38idusuario int NOT NULL DEFAULT 0, ppto38fecha int NOT NULL DEFAULT 0, ppto38hora int NOT NULL DEFAULT 0, ppto38minuto int NOT NULL DEFAULT 0, ppto38detalle Text NULL)";}
	if ($dbversion==8482){$sSQL="ALTER TABLE ppto38solpagocambio ADD PRIMARY KEY(ppto38id)";}
	if ($dbversion==8483){$sSQL=$objDB->sSQLCrearIndice('ppto38solpagocambio', 'ppto38solpagocambio_id', 'ppto38idsolicitud, ppto38consec', true);}
	if ($dbversion==8484){$sSQL=$objDB->sSQLCrearIndice('ppto38solpagocambio', 'ppto38solpagocambio_padre', 'ppto38idsolicitud');}
	if ($dbversion==8485){$sSQL="agregamodulo|539|5|Servicios públicos|1|2|3|4|5|6";}
	if ($dbversion==8486){$sSQL=$u09."(539, 1, 'Servicios públicos', 'pptosolpagosp.php', 504, 539, 'S', '', '')";}
	if ($dbversion==8488){$sSQL="agregamodulo|540|5|Cuentas factura - cobro|1|2|3|4|5|6";}
	if ($dbversion==8489){$sSQL=$u09."(540, 1, 'Cuentas factura - cobro', 'pptosolpagofc.php', 504, 540, 'S', '', '')";}
	if ($dbversion==8490){$sSQL="CREATE TABLE ppto41solpagocat (ppto41tiposolpago int NOT NULL, ppto41consec int NOT NULL, ppto41id int NOT NULL DEFAULT 0, ppto41activa int NOT NULL DEFAULT 0, ppto41orden int NOT NULL DEFAULT 0, ppto41nombre varchar(200) NULL)";}
	if ($dbversion==8491){$sSQL="ALTER TABLE ppto41solpagocat ADD PRIMARY KEY(ppto41id)";}
	if ($dbversion==8492){$sSQL=$objDB->sSQLCrearIndice('ppto41solpagocat', 'ppto41solpagocat_id', 'ppto41tiposolpago, ppto41consec', true);}
	if ($dbversion==8493){$sSQL="agregamodulo|541|5|Categorias para solicitudes de pago|1|2|3|4|5|6";}
	if ($dbversion==8494){$sSQL=$u09."(541, 1, 'Categorias para solicitudes de pago', 'pptosolpagocat.php', 2, 541, 'S', '', '')";}
	if ($dbversion==8495){$sSQL="CREATE TABLE ppto81estadosolpago (ppto81id int NOT NULL, ppto81nombre varchar(100) NULL)";}
	if ($dbversion==8496){$sSQL="ALTER TABLE ppto81estadosolpago ADD PRIMARY KEY(ppto81id)";}
	if ($dbversion==8497){$sSQL="INSERT INTO ppto81estadosolpago (ppto81id, ppto81nombre) VALUES (0, 'Borrador'), (1, 'Devuelta'), (7, 'Radicada'), (11, 'Registrada'), (12, 'Para pago'), (15, 'Pagada'), (99, 'Anulada')";}
	if ($dbversion==8498){$sSQL="CREATE TABLE ppto82tiposolpago (ppto82id int NOT NULL, ppto82nombre varchar(100) NULL)";}
	if ($dbversion==8499){$sSQL="ALTER TABLE ppto82tiposolpago ADD PRIMARY KEY(ppto82id)";}
	if ($dbversion==8500){$sSQL="INSERT INTO ppto82tiposolpago (ppto82id, ppto82nombre) VALUES (1, 'Servicios Públicos'), (2, 'Cuenta Factura - Cobro')";}
}
if (($dbversion>8500)&&($dbversion<8601)){
	if ($dbversion==8501){$sSQL="add_campos|corf48pruebasaber|corf48comp_lectura_des int NOT NULL DEFAULT 0|corf48comp_comun_des int NOT NULL DEFAULT 0|corf48comp_compciu_des int NOT NULL DEFAULT 0|corf48comp_razona_des int NOT NULL DEFAULT 0|corf48comp_ingles_des varchar(2) NULL";}
	if ($dbversion==8502){$sSQL="agregamodulo|2|-12|Mantenimiento DB|1";}
	if ($dbversion==8503){$sSQL="agregamodulo|3|-12|Usuarios activos|1";}
	if ($dbversion==8504){$sSQL="add_campos|docu04subtemas|docu04prefijos varchar(250) NULL|docu04valores varchar(250) NULL|docu04unidad varchar(10) NULL|docu04valorporc varchar(250) NULL|docu04unidadporc varchar(10) NULL|docu04sufijo varchar(10) NULL|docu04propiedad varchar(250) NULL";}
	if ($dbversion==8505){$sSQL="drop_campos|docu03temas|docu03datos|docu03prefijos|docu03tipotabla|docu03tipodato";}
	if ($dbversion==8506){$sSQL="add_campos|moni08cursosrap|moni08peso Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==8507){$sSQL="add_campos|moni07cursoscompgen|moni07peso Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==8508){$sSQL="agregamodulo|3655|36|Tablero de seguimiento de metas|1|5|6";}
	if ($dbversion==8509){$sSQL=$u09."(3655, 1, 'Tablero de seguimiento de metas', 'plansegmeta.php', 2201, 3655, 'S', '', '')";}
	
	if ($dbversion==8510){$sSQL="add_campos|sine10inscripcion|sine10recibofecha int NOT NULL DEFAULT 0|sine10recibohora int NOT NULL DEFAULT 0|sine10recibomin int NOT NULL DEFAULT 0|sine10idfactura int NOT NULL DEFAULT 0|sine10idproducto int NOT NULL DEFAULT 0|sine10idlistaprecio int NOT NULL DEFAULT 0";}
	if ($dbversion==8511){$sSQL="add_campos|core09programa|core09idproductoinscrip int NOT NULL DEFAULT 0";}
	if ($dbversion==8512){$sSQL="add_campos|docu04subtemas|docu04principal int NOT NULL DEFAULT 0";}
	if ($dbversion==8513){$sSQL="add_campos|ofer53preoferta|ofer53idprograma int NOT NULL DEFAULT 0|ofer53idversionprog int NOT NULL DEFAULT 0";}

	if ($dbversion==8514){$sSQL="add_campos|gafi16proveedores|gafi16imp_consumo int NOT NULL DEFAULT 0|gafi16residenciafiscal varchar(3) NULL";}
	if ($dbversion==8515){$sSQL="add_campos|ppto35solpago|ppto35idprestador int NOT NULL DEFAULT 0|ppto35valor2 int NOT NULL DEFAULT 0";}
	if ($dbversion==8516){$sSQL="CREATE TABLE ppto42solpagodet (ppto42idsolicitud int NOT NULL, ppto42idcategoria int NOT NULL, ppto42consec int NOT NULL, ppto42id int NOT NULL DEFAULT 0, ppto42idprestador int NOT NULL DEFAULT 0, ppto42valor Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==8517){$sSQL="ALTER TABLE ppto42solpagodet ADD PRIMARY KEY(ppto42id)";}
	if ($dbversion==8518){$sSQL=$objDB->sSQLCrearIndice('ppto42solpagodet', 'ppto42solpagodet_id', 'ppto42idsolicitud, ppto42idcategoria, ppto42consec', true);}
	if ($dbversion==8519){$sSQL=$objDB->sSQLCrearIndice('ppto42solpagodet', 'ppto42solpagodet_padre', 'ppto42idsolicitud');}
	if ($dbversion==8520){$sSQL="agregamodulo|542|5|Solicitudes pago - Detallado|1|2|3|4|5";}

	if ($dbversion==8521){$sSQL="ALTER TABLE docu04subtemas MODIFY COLUMN docu04valores Text NULL";}
	if ($dbversion==8522){$sSQL="ALTER TABLE docu04subtemas MODIFY COLUMN docu04prefijos Text NULL";}
	if ($dbversion==8523){$sSQL="agregamodulo|4627|46|Proveedores {Rpt}|1|5|6";}
	if ($dbversion==8524){$sSQL=$u09."(4627, 1, 'Proveedores', 'gafirptproveedores.php', 11, 4627, 'S', '', '')";}
	if ($dbversion==8525){$sSQL="add_campos|gafi68tipodocprov|gafi68instrucciones Text NULL";}

	if ($dbversion==8526){$sSQL="CREATE TABLE gafi65tamanoemp (gafi65consec int NOT NULL, gafi65id int NOT NULL DEFAULT 0, gafi65activo int NOT NULL DEFAULT 0, gafi65orden int NOT NULL DEFAULT 0, gafi65nombre varchar(100) NULL, gafi65mipyme int NOT NULL DEFAULT 0)";}
	if ($dbversion==8527){$sSQL="ALTER TABLE gafi65tamanoemp ADD PRIMARY KEY(gafi65id)";}
	if ($dbversion==8528){$sSQL=$objDB->sSQLCrearIndice('gafi65tamanoemp', 'gafi65tamanoemp_id', 'gafi65consec', true);}
	if ($dbversion==8529){$sSQL="agregamodulo|4665|46|Tamaño de las empresas|1|2|3|4|5|6|8";}
	if ($dbversion==8530){$sSQL=$u09."(4665, 1, 'Tamaño de las empresas', 'gafitamemp.php', 2, 4665, 'S', '', '')";}

	if ($dbversion==8531){$sSQL="CREATE TABLE plan455tablero (plan55vigencia int NOT NULL, plan55idescuela int NOT NULL, plan55idprograma int NOT NULL, plan55idzona int NOT NULL, plan55idcentro int NOT NULL, plan55idciclo int NOT NULL, plan55idbloque int NOT NULL, plan55id int NOT NULL DEFAULT 0, plan55conc_nuevos int NOT NULL DEFAULT 0, plan55conc_antiguos int NOT NULL DEFAULT 0, plan55ejec_nuevos int NOT NULL DEFAULT 0, plan55ejec_antiguos int NOT NULL DEFAULT 0, plan55procesar int NOT NULL DEFAULT 0)";}
	if ($dbversion==8532){$sSQL="ALTER TABLE plan455tablero ADD PRIMARY KEY(plan55id)";}
	if ($dbversion==8533){$sSQL=$objDB->sSQLCrearIndice('plan455tablero', 'plan455tablero_id', 'plan55vigencia, plan55idescuela, plan55idprograma, plan55idzona, plan55idcentro, plan55idciclo, plan55idbloque', true);}
	if ($dbversion==8534){$sSQL=$objDB->sSQLCrearIndice('plan455tablero', 'plan455tablero_vigencia', 'plan55vigencia');}
	if ($dbversion==8535){$sSQL=$objDB->sSQLCrearIndice('plan455tablero', 'plan455tablero_escuela', 'plan55idescuela');}
	if ($dbversion==8536){$sSQL=$objDB->sSQLCrearIndice('plan455tablero', 'plan455tablero_programa', 'plan55idprograma');}
	if ($dbversion==8537){$sSQL=$objDB->sSQLCrearIndice('plan455tablero', 'plan455tablero_zona', 'plan55idzona');}
	if ($dbversion==8538){$sSQL=$objDB->sSQLCrearIndice('plan455tablero', 'plan455tablero_centro', 'plan55idcentro');}

	if ($dbversion==8539){$sSQL="add_campos|core01estprograma|core01promediocursos int NOT NULL DEFAULT 0|core01promedionota Decimal(15,2) NULL DEFAULT 0| core01promediofecha int NOT NULL DEFAULT 0";}
	if ($dbversion==8540){$sSQL="INSERT INTO gafi65tamanoemp (gafi65consec, gafi65id, gafi65activo, gafi65orden, gafi65nombre, gafi65mipyme) VALUES (0, 0, 0, 0, '{No Aplica}', 0)";}
	if ($dbversion==8541){$sSQL="add_campos|gafi16proveedores|gafi16tamanoemp int NOT NULL DEFAULT 0|gafi16conestable int NOT NULL DEFAULT 0";}

	if ($dbversion==8542){$sSQL="drop_campos|gedo15expdoc|gedo15md1_lectura|gedo15md1_comunicacion|gedo15md1_competencias|gedo15md1_razonamiento|gedo15md1_ingles";}
	if ($dbversion==8543){$sSQL="add_campos|gedo15expdoc|gedo15md1_prueba1 int NOT NULL DEFAULT 0|gedo15md1_prueba2 int NOT NULL DEFAULT 0|gedo15md1_prueba3 int NOT NULL DEFAULT 0|gedo15md1_prueba4 int NOT NULL DEFAULT 0|gedo15md1_prueba5 int NOT NULL DEFAULT 0|gedo15md1_prueba6 int NOT NULL DEFAULT 0|gedo15md1_prueba7 int NOT NULL DEFAULT 0|gedo15md1_prueba8 int NOT NULL DEFAULT 0|gedo15md1_prueba9 int NOT NULL DEFAULT 0|gedo15md1_prueba10 int NOT NULL DEFAULT 0";}
	if ($dbversion==8544){$sSQL="CREATE TABLE gafi28mediocontacto (gafi28idtercero int NOT NULL, gafi28consec int NOT NULL, gafi28id int NOT NULL DEFAULT 0, gafi28pais varchar(3) NULL, gafi28medio int NOT NULL DEFAULT 0, gafi28tel_indicativo varchar(4) NULL, gafi28tel_codarea varchar(5) NULL, gafi28tel_numero varchar(20) NULL, gafi28tel_extension varchar(10) NULL, gafi28tel_nombre varchar(100) NULL, gafi28tel_llamada int NOT NULL DEFAULT 0, gafi28tel_whatsapp int NOT NULL DEFAULT 0, gafi28tel_sms int NOT NULL DEFAULT 0, gafi28tel_horaini int NOT NULL DEFAULT 0, gafi28tel_minini int NOT NULL DEFAULT 0, gafi28tel_horafin int NOT NULL DEFAULT 0, gafi28tel_minfin int NOT NULL DEFAULT 0, gafi28tel_sabado int NOT NULL DEFAULT 0, gafi28correo varchar(100) NULL, gafi28indicaciones Text NULL, gafi28fechareg int NOT NULL DEFAULT 0, gafi28idusuario int NOT NULL DEFAULT 0, gafi28fecharetiro int NOT NULL DEFAULT 0)";}
	if ($dbversion==8545){$sSQL="ALTER TABLE gafi28mediocontacto ADD PRIMARY KEY(gafi28id)";}
	if ($dbversion==8546){$sSQL=$objDB->sSQLCrearIndice('gafi28mediocontacto', 'gafi28mediocontacto_id', 'gafi28idtercero, gafi28consec', true);}
	if ($dbversion==8547){$sSQL="agregamodulo|4628|46|Medios de contacto|1|2|3|4|5|6|8";}
	if ($dbversion==8548){$sSQL=$u09."(4628, 1, 'Medios de contacto', 'gafimediocontacto.php', 4602, 4628, 'S', '', '')";}

	if ($dbversion==8549){$sSQL="add_campos|gafi16proveedores|gafi16codigopostal varchar(20) NULL|gafi16fechainscrip int NOT NULL DEFAULT 0";}
	// Campos de periodo
	if ($dbversion==8551){$sSQL="add_campos|ofer05agenda|ofer05duracion int NOT NULL DEFAULT 0";}
	if ($dbversion==8552){$sSQL="add_campos|exte02per_aca|exte02fechaactividadfinalred int NOT NULL DEFAULT 0|exte02fechacierrered int NOT NULL DEFAULT 0";}

	if ($dbversion==8553){$sSQL="agregamodulo|3900|39|Panel de compras|1|1707";}

	if ($dbversion==8554){$sSQL="ALTER TABLE cttc04tipovalores CHANGE cttc04smmlv_base cttc04smmlv_base DECIMAL(15,2) NOT NULL";}
	if ($dbversion==8555){$sSQL="ALTER TABLE cttc04tipovalores CHANGE cttc04smmlv_tope cttc04smmlv_tope DECIMAL(15,2) NOT NULL";}
	if ($dbversion==8556){$sSQL="INSERT INTO cttc11tareas (cttc11idevento, cttc11consec, cttc11id, cttc11nombre, 
		cttc11activo, cttc11anexo, cttc11observaciones, cttc11aprobacion, cttc11version, 
		cttc11tipodocumento, cttc11derivada, cttc11ideventorev, cttc11orden) VALUES 
		(65, 5, 69, 'ACTA DE CONTRATACIÓN F4-4-16', 1, 1, 0, 0, 0, 1, 0, 65, 5)";}
	if ($dbversion==8557){$sSQL="add_campos|ppto14documento|ppto15cdp_origen int NOT NULL DEFAULT 0|ppto15cdp_idresolucion int NOT NULL DEFAULT 0|ppto15cdp_idprocesopago int NOT NULL DEFAULT 0";}

	if ($dbversion==8558){$sSQL="INSERT INTO core50convenios (core50consec, core50id, core50estado, core50tipoconvenio, core50nombre) VALUES (0, 0, 'S', 1, '{Efectivo}')";}
	if ($dbversion==8559){$sSQL="add_campos|core16actamatricula|core16procfactura int NOT NULL DEFAULT 0|core16errorfactura int NOT NULL DEFAULT 0";}

	if ($dbversion==8560){$sSQL="CREATE TABLE unad11terceros_md (unad11tipodoc varchar(2) NOT NULL, unad11doc varchar(20) NOT NULL, unad11id int NOT NULL DEFAULT 0, unad11razonsocial varchar(100) NULL, unad11idtablero int NOT NULL DEFAULT 0)";}
	if ($dbversion==8561){$sSQL="ALTER TABLE unad11terceros_md ADD PRIMARY KEY(unad11id)";}
	if ($dbversion==8562){$sSQL=$objDB->sSQLCrearIndice('unad11terceros_md', 'unad11terceros_md_id', 'unad11tipodoc, unad11doc', true);}
	if ($dbversion==8563){$sSQL="INSERT INTO unad11terceros_md (unad11tipodoc, unad11doc, unad11id, unad11razonsocial, unad11idtablero) SELECT unad11tipodoc, unad11doc, unad11id, unad11razonsocial, unad11idtablero FROM unad11terceros";}
	
	if ($dbversion==8564){$sSQL="add_campos|cttc02tipoproceso|cttc02modalidad int NOT NULL DEFAULT 0|cttc02clasecont int NOT NULL DEFAULT 0";}
	if ($dbversion==8565){$sSQL="CREATE TABLE cttc68modalidad (cttc68consec int NOT NULL, cttc68id int NOT NULL DEFAULT 0, cttc68activo int NOT NULL DEFAULT 0, cttc68orden int NOT NULL DEFAULT 0, cttc68nombre varchar(200) NULL)";}
	if ($dbversion==8566){$sSQL="ALTER TABLE cttc68modalidad ADD PRIMARY KEY(cttc68id)";}
	if ($dbversion==8567){$sSQL=$objDB->sSQLCrearIndice('cttc68modalidad', 'cttc68modalidad_id', 'cttc68consec', true);}
	if ($dbversion==8568){$sSQL="agregamodulo|4168|41|Modalidades|1|2|3|4|5|6|8";}
	if ($dbversion==8569){$sSQL=$u09."(4168, 1, 'Modalidades', 'cttcmodalidad.php', 2, 4168, 'S', '', '')";}
	if ($dbversion==8570){$sSQL="CREATE TABLE cttc69clasecont (cttc69consec int NOT NULL, cttc69id int NOT NULL DEFAULT 0, cttc689ctivo int NOT NULL DEFAULT 0, cttc69orden int NOT NULL DEFAULT 0, cttc69nombre varchar(200) NULL)";}
	if ($dbversion==8571){$sSQL="ALTER TABLE cttc69clasecont ADD PRIMARY KEY(cttc69id)";}
	if ($dbversion==8572){$sSQL=$objDB->sSQLCrearIndice('cttc69clasecont', 'cttc69clasecont_id', 'cttc69consec', true);}
	if ($dbversion==8573){$sSQL="agregamodulo|4169|41|Clases de contratos|1|2|3|4|5|6|8";}
	if ($dbversion==8574){$sSQL=$u09."(4169, 1, 'Clases de contratos', 'cttcclasecont.php', 2, 4169, 'S', '', '')";}

	if ($dbversion==8575){$sSQL="ALTER TABLE fact62pasarela MODIFY COLUMN fact62tpv_clave varchar(120) NULL";}
	if ($dbversion==8576){$sSQL="add_campos|fact62pasarela|fact62tiporecaudo int NOT NULL DEFAULT 0|fact62cuenta int NOT NULL DEFAULT 0|fact62formapago int NOT NULL DEFAULT 0|fact62visa int NOT NULL DEFAULT 0|fact62mastercard int NOT NULL DEFAULT 0|fact62amex int NOT NULL DEFAULT 0|fact62paypal int NOT NULL DEFAULT 0|fact62cashapp int NOT NULL DEFAULT 0";}
	if ($dbversion==8577){$sSQL="add_campos|fact07recaudo|fact07pasarela_id int NOT NULL DEFAULT 0|fact07pasarela_fechaini int NOT NULL DEFAULT 0|fact07pasarela_horaini int NOT NULL DEFAULT 0|fact07pasarela_minini int NOT NULL DEFAULT 0|fact07pasarela_fechaconfirma int NOT NULL DEFAULT 0|fact07pasarela_horaconfirma int NOT NULL DEFAULT 0|fact07pasarela_minconfirma int NOT NULL DEFAULT 0|fact07pasarela_usuarioconfirma int NOT NULL DEFAULT 0";}

	if ($dbversion==8578){$sSQL="DELETE FROM ceca14estadorecal";}
	if ($dbversion==8579){$sSQL="INSERT INTO ceca14estadorecal (ceca14id, ceca14nombre) VALUES (0, 'En solicitud'), (1, 'Radicada'), (5, 'Avalada'), (7, 'Aprobada'), (8, 'Negada'), (9, 'Rechazada'), (11, 'Anulada')";}
	if ($dbversion==8580){$sSQL="add_campos|core39unidadproductora|unad39orden int NOT NULL DEFAULT 0|unad39sigla varchar(20) NULL|unad39gestestudiantes int NOT NULL DEFAULT 0";}
	if ($dbversion==8581){$sSQL="add_campos|fact07recaudo|fact07pasarela_ref varchar(120) NULL";}
	if ($dbversion==8582){$sSQL="add_campos|core16actamatricula|core16idceadlaboratorios int NOT NULL DEFAULT 0|core16numcreddisci int NULL DEFAULT 0";}

	if ($dbversion==8583){$sSQL="CREATE TABLE aure00params (aure00id int NOT NULL, aure00tipoterminal int NOT NULL DEFAULT 0, aure00pais varchar(3) NULL)";}
	if ($dbversion==8584){$sSQL="ALTER TABLE aure00params ADD PRIMARY KEY(aure00id)";}
	if ($dbversion==8585){$sSQL="agregamodulo|295|2|Parametros|1|3";}
	if ($dbversion==8586){$sSQL=$u09."(295, 1, 'Parametros', 'aureparams.php', 2, 295, 'S', '', '')";}
	if ($dbversion==8587){$sSQL="INSERT INTO aure00params (aure00id, aure00tipoterminal, aure00pais) VALUES (1, 0, '057')";}
	
	if ($dbversion==8588){$sSQL="CREATE TABLE moni15actrap (moni15idescuela int NOT NULL, moni15idplanest int NOT NULL, moni15idperiodo int NOT NULL, moni15idrap int NOT NULL, moni15id int NOT NULL DEFAULT 0, moni15estado int NOT NULL DEFAULT 0, moni15fechaprocesa int NOT NULL DEFAULT 0, moni15idusuaroprocesa int NOT NULL DEFAULT 0)";}
	if ($dbversion==8589){$sSQL="ALTER TABLE moni15actrap ADD PRIMARY KEY(moni15id)";}
	if ($dbversion==8590){$sSQL=$objDB->sSQLCrearIndice('moni15actrap', 'moni15actrap_id', 'moni15idescuela, moni15idplanest, moni15idperiodo, moni15idrap', true);}
	if ($dbversion==8591){$sSQL="agregamodulo|5115|51|Actividades del RAP|1|2|3|4|5|6|8";}
	if ($dbversion==8592){$sSQL=$u09."(5115, 1, 'Actividades del RAP', 'moniactrap.php', 1, 5115, 'S', 'idPrograma', '')";}
	if ($dbversion==8593){$sSQL="CREATE TABLE moni16actrapcurso (moni16idactrap int NOT NULL, moni16idplanest int NOT NULL, moni16idrap int NOT NULL, moni16idperiodo int NOT NULL, moni16idcurso int NOT NULL, moni16idactividad int NOT NULL, moni16id int NOT NULL DEFAULT 0, moni16peso Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==8594){$sSQL="ALTER TABLE moni16actrapcurso ADD PRIMARY KEY(moni16id)";}
	if ($dbversion==8595){$sSQL=$objDB->sSQLCrearIndice('moni16actrapcurso', 'moni16actrapcurso_id', 'moni16idactrap, moni16idplanest, moni16idrap, moni16idperiodo, moni16idcurso, moni16idactividad', true);}
	if ($dbversion==8596){$sSQL=$objDB->sSQLCrearIndice('moni16actrapcurso', 'moni16actrapcurso_padre', 'moni16idactrap');}
	if ($dbversion==8597){$sSQL="agregamodulo|5116|51|Actividades del RAP por curso|1|2|3|4|5|6|8";}

	if ($dbversion==8598){$sSQL=$u08."(2906, 'Aspirantes', 'gm.php?id=2906', 'Aspirantes', 'Applicants', 'Candidatos'), (2907, 'Inducciones', 'gm.php?id=2907', 'Inducciones', 'Inductions', 'Induções')";}
	if ($dbversion==8599){$sSQL="CREATE TABLE even49enccategoria (even49consec int NOT NULL, even49id int NOT NULL DEFAULT 0, even49nombre varchar(250) NULL, even49activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8600){$sSQL="ALTER TABLE even49enccategoria ADD PRIMARY KEY(even49id)";}
	}
if (($dbversion>8600)&&($dbversion<8701)){
	if ($dbversion==8601){$sSQL=$objDB->sSQLCrearIndice('even49enccategoria', 'even49enccategoria_id', 'even49consec', true);}
	if ($dbversion==8602){$sSQL="agregamodulo|1949|19|Preguntas - Categoria|1|2|3|4|5|6|8";}
	if ($dbversion==8603){$sSQL=$u09."(1949, 1, 'Preguntas - Categoria', 'evenenccategoria.php', 2, 1949, 'S', '', '')";}
	if ($dbversion==8604){$sSQL="CREATE TABLE even48enctemas (even48idcategoria int NOT NULL, even48consec int NOT NULL, even48id int NOT NULL DEFAULT 0, even48activo int NOT NULL DEFAULT 0, even48nombre varchar(250) NULL)";}
	if ($dbversion==8605){$sSQL="ALTER TABLE even48enctemas ADD PRIMARY KEY(even48id)";}
	if ($dbversion==8606){$sSQL=$objDB->sSQLCrearIndice('even48enctemas', 'even48enctemas_id', 'even48idcategoria, even48consec', true);}
	if ($dbversion==8607){$sSQL=$objDB->sSQLCrearIndice('even48enctemas', 'even48enctemas_padre', 'even48idcategoria');}
	if ($dbversion==8608){$sSQL="agregamodulo|1948|19|Temas|1|2|3|4|5|6|8";}
	if ($dbversion==8609){$sSQL="CREATE TABLE moni12rapcursoact (moni12idplan int NOT NULL, moni12idrap int NOT NULL, moni12idcurso int NOT NULL, moni12idperiodo int NOT NULL, moni12idact int NOT NULL, moni12id int NOT NULL DEFAULT 0, moni12peso Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==8610){$sSQL="ALTER TABLE moni12rapcursoact ADD PRIMARY KEY(moni12id)";}
	if ($dbversion==8611){$sSQL=$objDB->sSQLCrearIndice('moni12rapcursoact', 'moni12rapcursoact_id', 'moni12idplan, moni12idrap, moni12idcurso, moni12idperiodo, moni12idact', true);}
	if ($dbversion==8612){$sSQL=$objDB->sSQLCrearIndice('moni12rapcursoact', 'moni12rapcursoact_padre', 'moni12idplan');}

	if ($dbversion==8613){$sSQL="mod_quitar|5107";}
	if ($dbversion==8614){$sSQL="DROP TABLE moni07cursoscompgen";}
	if ($dbversion==8615){$sSQL="add_campos|moni08cursosrap|moni08tipocomp int NOT NULL DEFAULT 0";}

	if ($dbversion==8616){$sSQL="CREATE TABLE visa15inscripcioninducc (visa15idperiodo int NOT NULL, visa15idtercero int NOT NULL, visa15id int NOT NULL DEFAULT 0, visa15idprograma int NOT NULL DEFAULT 0, visa15idzona int NOT NULL DEFAULT 0, visa15idcentro int NOT NULL DEFAULT 0, visa15idconsejero int NOT NULL DEFAULT 0, visa15nuevo int NOT NULL DEFAULT 0, visa15fecha int NOT NULL DEFAULT 0)";}
	if ($dbversion==8617){$sSQL="ALTER TABLE visa15inscripcioninducc ADD PRIMARY KEY(visa15id)";}
	if ($dbversion==8618){$sSQL=$objDB->sSQLCrearIndice('visa15inscripcioninducc', 'visa15inscripcioninducc_id', 'visa15idperiodo, visa15idtercero', true);}
	if ($dbversion==8619){$sSQL="agregamodulo|5015|29|Inscripción Inducción|1|2|3|4|5|6|8";}
	if ($dbversion==8620){$sSQL=$u09."(5015, 1, 'Inscripción Inducción', 'visaeinscripinduc.php', 2907, 5015, 'S', '', '')";}
	if ($dbversion==8621){$sSQL="CREATE TABLE visa16jornadainduccion (visa16idperiodo int NOT NULL, visa16consec int NOT NULL, visa16id int NOT NULL DEFAULT 0, visa16idtipoinduccion int NOT NULL DEFAULT 0, visa16estado int NOT NULL DEFAULT 0, visa16alcance int NOT NULL DEFAULT 0, visa16idzona int NOT NULL DEFAULT 0, visa16idcentro int NOT NULL DEFAULT 0, visa16fechaini int NOT NULL DEFAULT 0, visa16horaini int NOT NULL DEFAULT 0, visa16minutoini int NOT NULL DEFAULT 0, visa16horafin int NOT NULL DEFAULT 0, visa16minutofin int NOT NULL DEFAULT 0, visa16modalidad int NOT NULL DEFAULT 0, visa16gestionacupos int NOT NULL DEFAULT 0, visa16cantcupos int NOT NULL DEFAULT 0, visa16numinscritos int NOT NULL DEFAULT 0, visa16numasistentes int NOT NULL DEFAULT 0, visa16idconsejero int NOT NULL DEFAULT 0, visa16detalle Text NULL, visa16enlaceconexion varchar(250) NULL, visa16anula_detalle Text NULL, visa16anula_fecha int NOT NULL DEFAULT 0, visa16anula_idusuario int NOT NULL DEFAULT 0, visa16idusuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==8622){$sSQL="ALTER TABLE visa16jornadainduccion ADD PRIMARY KEY(visa16id)";}
	if ($dbversion==8623){$sSQL=$objDB->sSQLCrearIndice('visa16jornadainduccion', 'visa16jornadainduccion_id', 'visa16idperiodo, visa16consec', true);}
	if ($dbversion==8624){$sSQL="agregamodulo|5016|29|Jornadas de Inducción|1|2|3|4|5|6|8";}
	if ($dbversion==8625){$sSQL=$u09."(5016, 1, 'Jornadas de Inducción', 'visaejornadainduc.php', 2907, 5016, 'S', '', '')";}
	if ($dbversion==8626){$sSQL="CREATE TABLE visa17asistenciainducc (visa17idjornada int NOT NULL, visa17numcupo int NOT NULL, visa17id int NOT NULL DEFAULT 0, visa17idtercero int NOT NULL DEFAULT 0, visa17condicion int NOT NULL DEFAULT 0)";}
	if ($dbversion==8627){$sSQL="ALTER TABLE visa17asistenciainducc ADD PRIMARY KEY(visa17id)";}
	if ($dbversion==8628){$sSQL=$objDB->sSQLCrearIndice('visa17asistenciainducc', 'visa17asistenciainducc_id', 'visa17idjornada, visa17numcupo', true);}
	if ($dbversion==8629){$sSQL=$objDB->sSQLCrearIndice('visa17asistenciainducc', 'visa17asistenciainducc_padre', 'visa17idjornada');}
	if ($dbversion==8630){$sSQL="agregamodulo|5017|29|Jornada - Asistencia|1|2|3|4|5|6|8";}
	if ($dbversion==8631){$sSQL="CREATE TABLE visa52tipoinduccion (visa52consec int NOT NULL, visa52id int NOT NULL DEFAULT 0, visa52orden int NOT NULL DEFAULT 0, visa52activo int NOT NULL DEFAULT 0, visa52nombre varchar(200) NULL)";}
	if ($dbversion==8632){$sSQL="ALTER TABLE visa52tipoinduccion ADD PRIMARY KEY(visa52id)";}
	if ($dbversion==8633){$sSQL=$objDB->sSQLCrearIndice('visa52tipoinduccion', 'visa52tipoinduccion_id', 'visa52consec', true);}
	if ($dbversion==8634){$sSQL="agregamodulo|5052|29|Tipo de Inducción|1|2|3|4|5|6|8";}
	if ($dbversion==8635){$sSQL=$u09."(5052, 1, 'Tipo de Inducción', 'visaetipoinduccion.php', 2, 5052, 'S', '', '')";}
	if ($dbversion==8636){$sSQL="CREATE TABLE visa78estadojornada (visa78id int NOT NULL, visa78nombre varchar(50) NULL)";}
	if ($dbversion==8637){$sSQL="ALTER TABLE visa78estadojornada ADD PRIMARY KEY(visa78id)";}
	if ($dbversion==8638){$sSQL="add_campos|core14areaconocimiento|core14aplica int NOT NULL DEFAULT 0";}
	if ($dbversion==8639){$sSQL="add_campos|core13tiporegistroprog|core13nombredoc varchar(250) DEFAULT ''|core13aplicadoc int NOT NULL DEFAULT 0";}
	if ($dbversion==8640){$sSQL="UPDATE core13tiporegistroprog SET core13nombre='Electivo Formación Complementaria' WHERE core13id=4";}
	if ($dbversion==8641){$sSQL="UPDATE core13tiporegistroprog SET core13nombredoc='Obligatorio', core13aplicadoc=1 WHERE core13id=1";}
	if ($dbversion==8642){$sSQL="UPDATE core13tiporegistroprog SET core13nombredoc='Electivo', core13aplicadoc=1 WHERE core13id=2";}

	if ($dbversion==8643){$sSQL="add_campos|moni15actrap|moni15tipocomp int NOT NULL DEFAULT 0";}	
	if ($dbversion==8644){$sSQL="INSERT INTO ppto82tiposolpago (ppto82id, ppto82nombre) VALUES (3, 'Resoluciones'), (4, 'Viáticos')";}

	if ($dbversion==8645){$sSQL="ALTER TABLE moni01competencias DROP INDEX moni01competencias_id";}
	if ($dbversion==8646){$sSQL=$objDB->sSQLCrearIndice('moni01competencias', 'moni01competencias_id', 'moni01idplan, moni01consec', true);}	

	if ($dbversion==8647){$sSQL="agregamodulo|2794|27|Personas|1";}
	if ($dbversion==8648){$sSQL=$u09."(2794, 1, 'Personas', 'unadpersonas.php', 1, 2794, 'S', '', '')";}
	if ($dbversion==8649){$sSQL="agregamodulo|2737|27|Anular opcion de grado|1|2|3|4";}
	if ($dbversion==8650){$sSQL=$u09."(2737, 1, 'Anular opcion de grado', 'gradanulaopcion.php', 2203, 2737, 'S', '', '')";}
	if ($dbversion==8651){$sSQL=$u04."(1801, 17, 'S')";}
	if ($dbversion==8652){$sSQL="UPDATE corf61estadoinscmooc SET corf61nombre='Pago Certificado'  WHERE corf61id=7";}

	if ($dbversion==8653){$sSQL=$u01."(53, 'CONVENIOS', 'Sistema de Gestión de Convenios', 'S', 'S', 1, 0, 0)";}
	if ($dbversion==8654){$sSQL="UPDATE unad02modulos SET unad02idsistema=53 WHERE unad02id IN (2250, 2251, 2252)";}
	if ($dbversion==8655){$sSQL="mod_quitar|4124";}
	if ($dbversion==8656){$sSQL="UPDATE unad09modulomenu SET unad09grupo=4102 WHERE unad09idmodulo=2250 AND unad09consec=1";}

	if ($dbversion==8657){$sSQL="add_campos|plan43metaversion|plan43tipo int NOT NULL DEFAULT 0|plan43bloque1 int NOT NULL DEFAULT 1|plan43bloque2 int NOT NULL DEFAULT 1|plan43bloque3 int NOT NULL DEFAULT 1|plan43bloque4 int NOT NULL DEFAULT 1|plan43bloque5 int NOT NULL DEFAULT 1";}

	if ($dbversion==8658){$sSQL=$u04."(3912, 10, 'S')";}
	if ($dbversion==8659){$sSQL="CREATE TABLE even50encfactores (even50consec int NOT NULL, even50id int NOT NULL DEFAULT 0, even50nombre varchar(250) NULL, even50activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8660){$sSQL="ALTER TABLE even50encfactores ADD PRIMARY KEY(even50consec)";}
	if ($dbversion==8661){$sSQL="agregamodulo|1950|19|Factores|1|2|3|4|5|6";}
	if ($dbversion==8662){$sSQL=$u09."(1950, 1, 'Factores', 'evenfactores.php', 2, 1950, 'S', '', '')";}
	if ($dbversion==8663){$sSQL="CREATE TABLE even51enccaracteristicas (even51idfactor int NOT NULL, even51consec int NOT NULL, even51id int NOT NULL DEFAULT 0, even51nombre varchar(250) NULL, even51activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8664){$sSQL="ALTER TABLE even51enccaracteristicas ADD PRIMARY KEY(even51idfactor, even51consec)";}
	if ($dbversion==8665){$sSQL="agregamodulo|1951|19|Caracteristicas|1|2|3|4|5|6";}
	if ($dbversion==8666){$sSQL=$u09."(1951, 1, 'Caracteristicas', 'evencaracteristicas.php', 0, 1951, 'S', '', '')";}
	if ($dbversion==8667){$sSQL="CREATE TABLE even52encaspectos (even52idcaracteristica int NOT NULL, even52consec int NOT NULL, even52id int NOT NULL DEFAULT 0, even52nombre Text NULL, even52activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8668){$sSQL="ALTER TABLE even52encaspectos ADD PRIMARY KEY(even52idcaracteristica, even52consec)";}
	if ($dbversion==8669){$sSQL="add_campos|grad52fichaegresado|grad52idcohorte int NOT NULL DEFAULT 0";}

	if ($dbversion==8671){$sSQL="INSERT INTO unad18pais (unad18codigo, unad18nombre, unad18sufijo) VALUES
	('002', 'Canadá', 'ca')
	, ('003', 'Puerto Rico', 'PR')
	, ('007', 'Rusia', 'RU')
	, ('020', 'Egipto', 'EG')
	, ('027', 'Sudáfrica', 'ZA')
	, ('030', 'Grecia', 'GR')
	, ('031', 'Países Bajos', 'NL')
	, ('032', 'Bélgica', 'BE')
	, ('033', 'Francia', 'fr')
	, ('034', 'España', 'es')
	, ('035', 'Italia', 'it')
	, ('036', 'Hungría', 'HU')
	, ('040', 'Rumanía', 'RO')
	, ('041', 'Suiza', 'CH')
	, ('043', 'Austria', 'AT')
	, ('044', 'Reino Unido', 'GB')
	, ('045', 'Dinamarca', 'DK')
	, ('046', 'Suecia', 'SE')
	, ('047', 'Noruega', 'NO')
	, ('048', 'Polonia', 'PL')
	, ('049', 'Alemania', 'DE')
	, ('051', 'Perú', 'pe')
	, ('053', 'Cuba', 'CU')
	, ('054', 'Argentina', 'AR')
	, ('055', 'Brasil', 'br')
	, ('056', 'Chile', 'cl')
	, ('058', 'Venezuela', 've')
	, ('060', 'Malasia', 'MY')
	, ('061', 'Australia', 'AU')
	, ('062', 'Indonesia', 'ID')
	, ('063', 'Filipinas', 'PH')
	, ('064', 'Nueva Zelanda', 'NZ')
	, ('065', 'Singapur', 'SG')
	, ('066', 'Tailandia', 'TH')
	, ('081', 'Japón', 'JP')
	, ('082', 'Corea del Sur', 'KR')
	, ('084', 'Vietnam', 'VN')
	, ('086', 'China', 'CN')
	, ('090', 'Turquía', 'TR')
	, ('091', 'India', 'IN')
	, ('092', 'Pakistán', 'PK')
	, ('093', 'Afganistán', 'AF')
	, ('094', 'Sri lanka', 'LK')
	, ('095', 'Birmania', 'MM')
	, ('098', 'Irán', 'IR')
	, ('211', 'República de Sudán del Sur', 'SS')
	, ('212', 'Marruecos', 'MA')
	, ('213', 'Argelia', 'DZ')
	, ('216', 'Tunez', 'TN')
	, ('218', 'Libia', 'LY')";}
	if ($dbversion==8672){$sSQL="INSERT INTO unad18pais (unad18codigo, unad18nombre, unad18sufijo) VALUES
	('220', 'Gambia', 'GM')
	, ('221', 'Senegal', 'SN')
	, ('222', 'Mauritania', 'MR')
	, ('223', 'Mali', 'ML')
	, ('224', 'Guinea', 'GN')
	, ('225', 'Costa de Marfil', 'CI')
	, ('226', 'Burkina Faso', 'BF')
	, ('227', 'Niger', 'NE')
	, ('228', 'Togo', 'TG')
	, ('229', 'Benín', 'BJ')
	, ('230', 'Mauricio', 'MU')
	, ('231', 'Liberia', 'LR')
	, ('232', 'Sierra Leona', 'SL')
	, ('233', 'Ghana', 'GH')
	, ('234', 'Nigeria', 'NG')
	, ('235', 'Chad', 'TD')
	, ('236', 'República Centroafricana', 'CF')
	, ('237', 'Camerún', 'CM')
	, ('238', 'Cabo Verde', 'CV')
	, ('239', 'Santo Tomé y Príncipe', 'ST')
	, ('240', 'Guinea Ecuatorial', 'GQ')
	, ('241', 'Gabón', 'GA')
	, ('242', 'República del Congo', 'CG')
	, ('243', 'República Democrática del Congo', 'CD')
	, ('244', 'Angola', 'AO')
	, ('245', 'Guinea-Bissau', 'GW')
	, ('246', 'Territorio Británico del Océano Índico', 'IO')
	, ('248', 'Seychelles', 'SC')
	, ('249', 'Sudán', 'SD')
	, ('250', 'Ruanda', 'RW')
	, ('251', 'Etiopía', 'ET')
	, ('252', 'Somalia', 'SO')
	, ('253', 'Yibuti', 'DJ')
	, ('254', 'Kenia', 'KE')
	, ('255', 'Tanzania', 'TZ')
	, ('256', 'Uganda', 'UG')
	, ('257', 'Burundi', 'BI')
	, ('258', 'Mozambique', 'MZ')
	, ('260', 'Zambia', 'ZM')
	, ('261', 'Madagascar', 'MG')
	, ('262', 'Reunión', 'RE')
	, ('263', 'Zimbabue', 'ZW')
	, ('264', 'Namibia', 'NA')
	, ('265', 'Malawi', 'MW')
	, ('266', 'Lesoto', 'LS')
	, ('267', 'Botsuana', 'BW')
	, ('268', 'Swazilandia', 'SZ')
	, ('269', 'Comoras', 'KM')
	, ('290', 'Santa Elena', 'SH')
	, ('291', 'Eritrea', 'ER')";}
	if ($dbversion==8673){$sSQL="INSERT INTO unad18pais (unad18codigo, unad18nombre, unad18sufijo) VALUES
	('297', 'Aruba', 'AW')
	, ('298', 'Islas Feroe', 'FO')
	, ('299', 'Groenlandia', 'GL')
	, ('350', 'Gibraltar', 'GI')
	, ('351', 'Portugal', 'PT')
	, ('352', 'Luxemburgo', 'LU')
	, ('353', 'Irlanda', 'IE')
	, ('354', 'Islandia', 'IS')
	, ('355', 'Albania', 'AL')
	, ('356', 'Malta', 'MT')
	, ('357', 'Chipre', 'CY')
	, ('358', 'Finlandia', 'FI')
	, ('359', 'Bulgaria', 'BG')
	, ('370', 'Lituania', 'LT')
	, ('371', 'Letonia', 'LV')
	, ('372', 'Estonia', 'EE')
	, ('373', 'Moldavia', 'MD')
	, ('374', 'Armenia', 'AM')
	, ('375', 'Bielorrusia', 'BY')
	, ('376', 'Andorra', 'AD')
	, ('377', 'Mónaco', 'MC')
	, ('378', 'San Marino', 'SM')
	, ('380', 'Ucrania', 'UA')
	, ('381', 'Serbia', 'RS')
	, ('382', 'Montenegro', 'ME')
	, ('385', 'Croacia', 'HR')
	, ('386', 'Eslovenia', 'SI')
	, ('387', 'Bosnia y Herzegovina', 'BA')
	, ('389', 'Maced nia', 'MK')
	, ('420', 'República Checa', 'CZ')
	, ('421', 'Eslovaquia', 'SK')
	, ('423', 'Liechtenstein', 'LI')
	, ('500', 'Islas Malvinas', 'FK')
	, ('501', 'Belice', 'BZ')
	, ('502', 'Guatemala', 'GT')
	, ('503', 'El Salvador', 'SV')
	, ('504', 'Honduras', 'HN')
	, ('505', 'Nicaragua', 'NI')
	, ('506', 'Costa Rica', 'CR')
	, ('507', 'Panamá', 'pa')
	, ('508', 'San Pedro y Miquelón', 'PM')
	, ('509', 'Haití', 'HT')
	, ('52', 'México', 'mx')
	, ('590', 'San Bartolomé', 'BL')
	, ('591', 'Bolivia', 'BO')
	, ('592', 'Guyana', 'GY')
	, ('593', 'Ecuador', 'ec')
	, ('594', 'Guayana Francesa', 'GF')
	, ('595', 'Paraguay', 'PY')
	, ('596', 'Martinica', 'MQ')";}
	if ($dbversion==8674){$sSQL="INSERT INTO unad18pais (unad18codigo, unad18nombre, unad18sufijo) VALUES
	('597', 'Surinám', 'SR')
	, ('598', 'Uruguay', 'UY')
	, ('670', 'Timor Oriental', 'TL')
	, ('672', 'Antártida', 'AQ')
	, ('673', 'Brunéi', 'BN')
	, ('674', 'Nauru', 'NR')
	, ('675', 'Papúa Nueva Guinea', 'PG')
	, ('676', 'Tonga', 'TO')
	, ('677', 'Islas Salomón', 'SB')
	, ('678', 'Vanuatu', 'VU')
	, ('679', 'Fiyi', 'FJ')
	, ('680', 'Palau', 'PW')
	, ('681', 'Wallis y Futuna', 'WF')
	, ('682', 'Islas Cook', 'CK')
	, ('683', 'Niue', 'NU')
	, ('685', 'Samoa', 'WS')
	, ('686', 'Kiribati', 'KI')
	, ('687', 'Nueva Caledonia', 'NC')
	, ('688', 'Tuvalu', 'TV')
	, ('689', 'Polinesia Francesa', 'PF')
	, ('690', 'Tokelau', 'TK')
	, ('691', 'Micronesia', 'FM')
	, ('692', 'Islas Marshall', 'MH')
	, ('850', 'Corea del Norte', 'KP')
	, ('852', 'Hong kong', 'HK')
	, ('853', 'Macao', 'MO')
	, ('855', 'Camboya', 'KH')
	, ('856', 'Laos', 'LA')
	, ('870', 'Islas Pitcairn', 'PN')
	, ('880', 'Bangladesh', 'BD')
	, ('886', 'Taiwán', 'TW')
	, ('960', 'Islas Maldivas', 'MV')
	, ('961', 'Líbano', 'LB')
	, ('962', 'Jordania', 'JO')
	, ('963', 'Siria', 'SY')
	, ('964', 'Irak', 'IQ')
	, ('965', 'Kuwait', 'KW')
	, ('966', 'Arabia Saudita', 'SA')
	, ('967', 'Yemen', 'YE')
	, ('968', 'Omán', 'OM')
	, ('970', 'Palestina', 'PS')
	, ('971', 'Emiratos Árabes Unidos', 'AE')
	, ('972', 'Israel', 'IL')
	, ('973', 'Bahrein', 'BH')
	, ('974', 'Qatar', 'QA')
	, ('975', 'Bhután', 'BT')
	, ('976', 'Mongolia', 'MN')
	, ('977', 'Nepal', 'NP')
	, ('992', 'Tayikistán', 'TJ')
	, ('993', 'Turkmenistán', 'TM')";}
	if ($dbversion==8675){$sSQL="INSERT INTO unad18pais (unad18codigo, unad18nombre, unad18sufijo) VALUES
	('994', 'Azerbaiyán', 'AZ')
	, ('995', 'Georgia', 'GE')
	, ('996', 'Kirguistán', 'KG')
	, ('998', 'Uzbekistán', 'UZ')";}
	if ($dbversion==8676){$sSQL="add_campos|unad45tipodoc|unad45integracion int NOT NULL DEFAULT 0";}

	if ($dbversion==8677){$sSQL="UPDATE cttc11tareas SET cttc11consec=51 WHERE cttc11id=58";}
	if ($dbversion==8678){$sSQL="UPDATE cttc11tareas SET cttc11consec=7 WHERE cttc11id=65";}
	if ($dbversion==8679){$sSQL="UPDATE cttc11tareas SET cttc11consec=11 WHERE cttc11id=67";}
	if ($dbversion==8680){$sSQL="UPDATE cttc11tareas SET cttc11consec=2 WHERE cttc11id=71";}
	if ($dbversion==8681){$sSQL="INSERT INTO cttc11tareas (cttc11idevento, cttc11consec, cttc11id, cttc11nombre, 
		cttc11activo, cttc11anexo, cttc11observaciones, cttc11aprobacion, cttc11version, 
		cttc11tipodocumento, cttc11derivada, cttc11ideventorev, cttc11orden) VALUES 
		(5, 5, 8, 'DOCUMENTO DE INTENCIÓN DE DONACIÓN EMITIDO POR EL PROMITENTE DONADOR', 1, 1, 0, 0, 0, 1, 0, 5, 5)
		,(5, 11, 14, 'VERIFICACION Y APROBACION POR PARTE DE LA GERENCIA DE  INFRAESTRUCTURA TECNOLÓGICA (Solamente para la recepción de nuevos predios).', 1, 1, 0, 0, 0, 1, 0, 5, 11)
		,(5, 16, 16, 'COMITÉ FINANCIERO (APROBACIÓN DE COMPRA DE BIENES INMUEBLES, ARRENDAMIENTO DE NUEVOS INMUEBLES Y/O INCREMENTO EN EL CANON DE ARRENDAMIENTO)', 1, 1, 0, 0, 0, 1, 0, 5, 16)
		,(5, 18, 18, 'PROCESO DE SELECCION ARRENDAMIENTOS', 1, 1, 0, 0, 0, 1, 0, 5, 18)
		,(20, 6, 27, 'OFICIO DE INVITACIÓN A PARTICIPAR PARA INVITACIÓN DIRECTA  Y PÚBLICA (FORMATO F-4-4-9)', 1, 1, 0, 0, 0, 1, 0, 20, 6)
		,(20, 41, 59, 'EVALUACIÓN TÉCNICA DEFINITIVA', 1, 1, 0, 0, 0, 1, 0, 20, 41)
		,(20, 42, 60, 'EVALUACIÓN FINANCIERA DEFINITIVA', 1, 1, 0, 0, 0, 1, 0, 20, 42)
		,(20, 43, 61, 'EVALUACIÓN JURÍDICA DEFINITIVA', 1, 1, 0, 0, 0, 1, 0, 20, 43)
		,(65, 1, 63, 'OFICIO INVITACIÓN CÓMITE DE CONTRATACIÓN', 1, 1, 0, 0, 0, 1, 0, 65, 1)
		,(65, 9, 66, 'NOTIFICACIÓN PERSONAL DE LA RESOLUCIÓN DE ADJUDICACIÓN', 1, 1, 0, 0, 0, 1, 0, 65, 9)
		,(70, 2, 71, 'RESOLUCIÓN DE ACEPTACIÓN DE DONACIÓN', 1, 1, 0, 0, 0, 1, 0, 70, 2)
		,(70, 10, 79, 'FIRMA DEL ORDENADOR DEL GASTO (RECURSO UNAD)', 1, 1, 0, 0, 0, 1, 0, 70, 10)
		,(150, 56, 175, 'TRASPASO DE VEHICULO', 1, 1, 0, 0, 0, 1, 0, 150, 56)
		,(205, 60, 260, 'Radicacion ultimo pago en Tesoreria', 1, 1, 0, 0, 0, 1, 0, 205, 60)
		,(205, 70, 270, 'CIERRE DEL EXPEDIENTE EN SECOP II ', 1, 1, 0, 0, 0, 1, 0, 205, 70)";}

	if ($dbversion==8682){$sSQL="DROP TABLE even49enccategoria";}
	if ($dbversion==8683){$sSQL="CREATE TABLE even49enccategoria (even49idfactor int NOT NULL, even49idcaracteristica int NOT NULL, even49idaspecto int NOT NULL, even49consec int NOT NULL, even49id int NOT NULL DEFAULT 0, even49nombre varchar(250) NULL, even49activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8684){$sSQL="ALTER TABLE even49enccategoria ADD PRIMARY KEY(even49id)";}
	if ($dbversion==8685){$sSQL=$objDB->sSQLCrearIndice('even49enccategoria', 'even49enccategoria_id', 'even49idfactor, even49idcaracteristica, even49idaspecto, even49consec', true);}

	if ($dbversion==8686){$sSQL=$u04."(2206, 1701, 'S'), (2206, 1707, 'S'), (2206, 1709, 'S'), (2206, 2112, 'S')";}
	if ($dbversion==8687){$sSQL="add_campos|moni15actrap|moni15estprocesados int NOT NULL DEFAULT 0|moni15estfaltantes int NOT NULL DEFAULT 0|moni15puntajepromedio Decimal(15,2) NULL DEFAULT 0";}
	
	if ($dbversion==8688){$sSQL="agregamodulo|5117|51|Resultado del aprendizaje del programa por programa|1|2|3|4|5|6";}
	if ($dbversion==8689){$sSQL=$u09."(5117, 1, 'Resultado del aprendizaje por programa', 'moniraprograma.php', 11, 5117, 'S', '', '')";}

	if ($dbversion==8690){$sSQL="drop_campos|moni08cursosrap|moni08tipocomp";}
	if ($dbversion==8691){$sSQL="drop_campos|moni15actrap|moni15tipocomp";}
	if ($dbversion==8692){$sSQL="CREATE TABLE moni07cursoscompgen (moni07idplan int NOT NULL, moni07idcurso int NOT NULL, moni08idcompetencia int NOT NULL, moni08id int NOT NULL DEFAULT 0, moni08nivel int NOT NULL DEFAULT 0)";}
	if ($dbversion==8693){$sSQL="ALTER TABLE moni07cursoscompgen ADD PRIMARY KEY(moni08id)";}
	if ($dbversion==8694){$sSQL=$objDB->sSQLCrearIndice('moni07cursoscompgen', 'moni07cursoscompgen_id', 'moni07idplan, moni07idcurso, moni08idcompetencia', true);}
	if ($dbversion==8695){$sSQL=$objDB->sSQLCrearIndice('moni07cursoscompgen', 'moni07cursoscompgen_padre', 'moni07idplan');}
	if ($dbversion==8696){$sSQL="agregamodulo|5107|51|Cursos por Competencias genericas|1|2|3|4|5|6|8";}	

	if ($dbversion==8697){$sSQL="CREATE TABLE moni18actcursocompgen (moni18idactrap int NOT NULL, moni18idplanest int NOT NULL, moni18idperiodo int NOT NULL, moni18idcurso int NOT NULL, moni18idcompetencia int NOT NULL, moni18id int NOT NULL DEFAULT 0, moni18nivel int NOT NULL DEFAULT 0)";}
	if ($dbversion==8698){$sSQL="ALTER TABLE moni18actcursocompgen ADD PRIMARY KEY(moni18id)";}
	if ($dbversion==8699){$sSQL=$objDB->sSQLCrearIndice('moni18actcursocompgen', 'moni18actcursocompgen_id', 'moni18idactrap, moni18idplanest, moni18idperiodo, moni18idcurso, moni18idcompetencia', true);}
	if ($dbversion==8700){$sSQL=$objDB->sSQLCrearIndice('moni18actcursocompgen', 'moni18actcursocompgen_padre', 'moni18idactrap');}
	}
if (($dbversion>8700)&&($dbversion<8801)){
	if ($dbversion==8701){$sSQL="agregamodulo|5118|51|Cursos Competencia generica|1|2|3|4|5|6|8";}
	if ($dbversion==8702){$sSQL="agregamodulo|543|5|Detallado de documentos|1|5|6";}
	if ($dbversion==8703){$sSQL=$u09."(543, 1, 'Detallado de documentos', 'pptorptdetalledoc.php', 11, 543, 'S', '', '')";}

	if ($dbversion==8704){$sSQL="agregamodulo|4132|41|Gestión de procesos contractuales|1|2|3|5|6|10|12|1717";}
	if ($dbversion==8705){$sSQL=$u09."(4132, 1, 'Gestión de procesos contractuales', 'cttcprocgest.php', 4101, 4132, 'S', '', '')";}
	if ($dbversion==8706){$sSQL="agregamodulo|4133|41|Gestión de documentos complementarios|1|3|5|6|10|12|1717";}
	if ($dbversion==8707){$sSQL=$u09."(4133, 1, 'Gestión de documentos complementarios', 'cttcprocdoccomp.php', 4101, 4133, 'S', '', '')";}
	if ($dbversion==8708){$sSQL="agregamodulo|4134|41|Aprobación de documentos|1|3|5|6|10|12|1717";}
	if ($dbversion==8709){$sSQL=$u09."(4134, 1, 'Aprobación de documentos', 'cttcprocaprob.php', 4101, 4134, 'S', '', '')";}
	if ($dbversion==8710){$sSQL="add_campos|comp12procesocompra|comp12ejec_disp Decimal(15,2) NULL DEFAULT 0|comp12ejec_contrato Decimal(15,2) NULL DEFAULT 0|comp12ejec_pago Decimal(15,2) NULL DEFAULT 0";}

	if ($dbversion==8711){$sSQL="CREATE TABLE moni11rapestudiante (moni11idplan int NOT NULL, moni11idrap int NOT NULL, moni11idestudiante int NOT NULL, moni11id int NOT NULL DEFAULT 0, moni11puntajemax Decimal(15,2) NULL DEFAULT 0, moni11puntajecalificado Decimal(15,2) NULL DEFAULT 0, moni11puntajeobtenido Decimal(15,2) NULL DEFAULT 0, moni11porc_avance Decimal(15,2) NULL DEFAULT 0, moni11cohorte_inicial int NOT NULL DEFAULT 0)";}
	if ($dbversion==8712){$sSQL="ALTER TABLE moni11rapestudiante ADD PRIMARY KEY(moni11id)";}
	if ($dbversion==8713){$sSQL=$objDB->sSQLCrearIndice('moni11rapestudiante', 'moni11rapestudiante_id', 'moni11idplan, moni11idrap, moni11idestudiante', true);}
	if ($dbversion==8714){$sSQL="agregamodulo|5111|51|Resultado del aprendizaje por estudiante|1|2|3|4|5|6|8";}
	if ($dbversion==8715){$sSQL=$u09."(5111, 1, 'Resultado del aprendizaje del programa por estudiante', 'monirapestudiante.php', 11, 5111, 'S', '', '')";}
	if ($dbversion==8716){$sSQL="agregamodulo|12285|22|Comparativo de resultados|1|2|3|4|5|6";}
	if ($dbversion==8717){$sSQL=$u09."(12285, 1, 'Comparativo de resultados', 'corepruebacomp.php', 2205, 12285, 'S', '', '')";}
	if ($dbversion==8718){$sSQL="INSERT INTO cttc74estadoagenda (cttc74id, cttc74nombre) VALUES (8, 'No aplica')";}

	if ($dbversion==8719){$sSQL="agregamodulo|5119|51|Ruta del desempeño academico [5102]|1|2|3|4|5|6|8";}
	if ($dbversion==8720){$sSQL=$u09."(5119, 1, 'Ruta del desempeño academico', 'monirutaacad.php', 1, 5119, 'S', '', '')";}
	if ($dbversion==8721){$sSQL="agregamodulo|2164|21|Validar Simulador Tanda Periodo|1";}
	if ($dbversion==8722){$sSQL=$u09."(2164, 1, 'Validar Simulador Tanda Periodo', 'simulvalperiodo.php', 1501, 2164, 'S', '', '')";}

	if ($dbversion==8723){$sSQL="add_campos|aure00params|aure00ws_url varchar(250) NULL|aure00perfil_direccion int NOT NULL DEFAULT 1|aure00perfil_telefono int NOT NULL DEFAULT 1|aure00perfil_mediocont int NOT NULL DEFAULT 1|aure00perfil_grupos int NOT NULL DEFAULT 1";}
	if ($dbversion==8724){$sSQL="add_campos|cttc11tareas|cttc11momento int NOT NULL DEFAULT 0";}
	if ($dbversion==8725){$sSQL="UPDATE cttc11tareas SET cttc11momento=1 WHERE cttc11idevento IN (135, 150)";}
	if ($dbversion==8726){$sSQL="UPDATE cttc11tareas SET cttc11momento=2 WHERE cttc11idevento IN (205)";}

	if ($dbversion==8727){$sSQL=$u03."(21, 'Consulta Via WebService'), (22, 'Guardado Via WebService'), (23, 'Modifica Via WebService')";}
	if ($dbversion==8728){$sSQL=$u04."(101, 21, 'S'),(105, 21, 'S'),(107, 21, 'S'),(111, 21, 'S'),(123, 21, 'S'),(124, 21, 'S'),(140, 21, 'S'),(146, 21, 'S'),(1752, 21, 'S'),(1707, 21, 'S'),(2202, 21, 'S'),(2206, 21, 'S'),(2209, 21, 'S'),(2212, 21, 'S'),(2216, 21, 'S'),(2219, 21, 'S')";}
	if ($dbversion==8729){$sSQL=$u04."(4132, 1707, 'S'),(4133, 1707, 'S'),(4134, 1707, 'S')";}
	if ($dbversion==8730){$sSQL="add_campos|cttc09procnota|cttc09idagenda int NOT NULL DEFAULT 0|cttc09idtarea int NOT NULL DEFAULT 0|cttc09envio_fecha int NOT NULL DEFAULT 0|cttc09envio_hora int NOT NULL DEFAULT 0|cttc09envio_min int NOT NULL DEFAULT 0";}

	if ($dbversion==8731){$sSQL="agregamodulo|2754|27|Postulados por zona|1|2|3|4|5|6|8";}
	if ($dbversion==8732){$sSQL=$u09."(2754, 1, 'Postulados por zona', 'gradpostuladozona.php', 2201, 2754, 'S', '', '')";}
	if ($dbversion==8733){$sSQL="agregamodulo|2755|27|Postulados por centro|1|2|3|4|5|6|8";}
	if ($dbversion==8734){$sSQL=$u09."(2755, 1, 'Postulados por centro', 'gradpostuladocentro.php', 2201, 2755, 'S', '', '')";}
	if ($dbversion==8735){$sSQL="add_campos|unad95entorno|unad95doblefactor int NOT NULL DEFAULT 0";}

	if ($dbversion==8736){$sSQL="DROP TABLE corg14estudioshomol";}
	if ($dbversion==8737){$sSQL="DROP TABLE corg15estudiocont";}

	if ($dbversion==8738){$sSQL="CREATE TABLE corg14estudiohomol (corg14idies int NOT NULL, corg14idiesprog int NOT NULL, corg14consec int NOT NULL, corg14id int NOT NULL DEFAULT 0, corg14estado int NOT NULL DEFAULT 0, corg14idescuela int NOT NULL DEFAULT 0, corg14idprograma int NOT NULL DEFAULT 0, corg14idplan int NOT NULL DEFAULT 0, corg14idresponsable int NOT NULL DEFAULT 0, corg14fechaaprobado int NOT NULL DEFAULT 0, corg14concepto Text NULL)";}
	if ($dbversion==8739){$sSQL="ALTER TABLE corg14estudiohomol ADD PRIMARY KEY(corg14id)";}
	if ($dbversion==8740){$sSQL=$objDB->sSQLCrearIndice('corg14estudiohomol', 'corg14estudiohomol_id', 'corg14idies, corg14idiesprog, corg14consec', true);}
	if ($dbversion==8741){$sSQL="CREATE TABLE corg15estudiohomcont (corg15idestudio int NOT NULL, corg15idcontenido int NOT NULL, corg15id int NOT NULL DEFAULT 0, corg15notaminima int NOT NULL DEFAULT 0, corg15anotacion varchar(200) NULL)";}
	if ($dbversion==8742){$sSQL="ALTER TABLE corg15estudiohomcont ADD PRIMARY KEY(corg15id)";}
	if ($dbversion==8743){$sSQL=$objDB->sSQLCrearIndice('corg15estudiohomcont', 'corg15estudiohomcont_id', 'corg15idestudio, corg15idcontenido', true);}
	if ($dbversion==8744){$sSQL=$objDB->sSQLCrearIndice('corg15estudiohomcont', 'corg15estudiohomcont_padre', 'corg15idestudio');}

	if ($dbversion==8745){$sSQL=$u01."(12, 'COMUNICIONES', 'Sistema de Gestión de Mensajes Masivos', 'N', 'S', 1, 0, 0)";}

	if ($dbversion==8746){$sSQL="CREATE TABLE masi03listas (masi03consec int NOT NULL, masi03id int NOT NULL DEFAULT 0, masi03nombre varchar(200) NULL, masi03formaarmado int NOT NULL DEFAULT 0, masi03idproceso int NOT NULL DEFAULT 0, masi03publica int NOT NULL DEFAULT 0, masi03detalle Text NULL)";}
	if ($dbversion==8747){$sSQL="ALTER TABLE masi03listas ADD PRIMARY KEY(masi03id)";}
	if ($dbversion==8748){$sSQL=$objDB->sSQLCrearIndice('masi03listas', 'masi03listas_id', 'masi03consec', true);}
	if ($dbversion==8749){$sSQL="agregamodulo|1203|12|Listas de correo|1|2|3|4|5|6|8";}
	if ($dbversion==8750){$sSQL=$u09."(1203, 1, 'Listas de correo', 'unadlistacorreo.php', 1, 1203, 'S', '', '')";}
	if ($dbversion==8751){$sSQL="CREATE TABLE masi04listapartic (masi04idlista int NOT NULL, masi04idtercero int NOT NULL, masi04id int NOT NULL DEFAULT 0, masi04fechareg int NOT NULL DEFAULT 0, masi04fecharet int NOT NULL DEFAULT 0, masi04envio_generales int NOT NULL DEFAULT 0)";}
	if ($dbversion==8752){$sSQL="ALTER TABLE masi04listapartic ADD PRIMARY KEY(masi04id)";}
	if ($dbversion==8753){$sSQL=$objDB->sSQLCrearIndice('masi04listapartic', 'masi04listapartic_id', 'masi04idlista, masi04idtercero', true);}
	if ($dbversion==8754){$sSQL=$objDB->sSQLCrearIndice('masi04listapartic', 'masi04listapartic_padre', 'masi04idlista');}
	if ($dbversion==8755){$sSQL="agregamodulo|1204|12|Listas - participantes|1|2|3|4|5|6|8";}
	if ($dbversion==8756){$sSQL="CREATE TABLE masi09firma (masi09consec int NOT NULL, masi09id int NOT NULL DEFAULT 0, masi09activa int NOT NULL DEFAULT 0, masi09nombre varchar(200) NULL, masi09cuerpo Text NULL, masi09unidadfuncional int NOT NULL DEFAULT 0, masi09idescuela int NOT NULL DEFAULT 0, masi09idprograma int NOT NULL DEFAULT 0)";}
	if ($dbversion==8757){$sSQL="ALTER TABLE masi09firma ADD PRIMARY KEY(masi09id)";}
	if ($dbversion==8758){$sSQL=$objDB->sSQLCrearIndice('masi09firma', 'masi09firma_id', 'masi09consec', true);}
	if ($dbversion==8759){$sSQL="agregamodulo|1209|12|Firmas|1|2|3|4|5|6|8";}
	if ($dbversion==8760){$sSQL=$u09."(1209, 1, 'Firmas', 'unadmasivo.php', 2, 1209, 'S', '', '')";}
	if ($dbversion==8761){$sSQL="CREATE TABLE masi71formaarma (masi71id int NOT NULL, masi71nombre varchar(100) NULL)";}
	if ($dbversion==8762){$sSQL="ALTER TABLE masi71formaarma ADD PRIMARY KEY(masi71id)";}
	if ($dbversion==8763){$sSQL="CREATE TABLE masi72proceso (masi72id int NOT NULL, masi72nombre varchar(100) NULL, masi72aplicalistas int NOT NULL DEFAULT 0)";}
	if ($dbversion==8764){$sSQL="ALTER TABLE masi72proceso ADD PRIMARY KEY(masi72id)";}
	if ($dbversion==8765){$sSQL="CREATE TABLE masi73estadomensaje (masi73id int NOT NULL, masi73nombre varchar(100) NULL)";}
	if ($dbversion==8766){$sSQL="ALTER TABLE masi73estadomensaje ADD PRIMARY KEY(masi73id)";}
	if ($dbversion==8767){$sSQL="INSERT INTO masi71formaarma (masi71id, masi71nombre) VALUES (0, 'Manual'), (1, 'Por proceso')";}
	if ($dbversion==8768){$sSQL="INSERT INTO masi72proceso (masi72id, masi72nombre, masi72aplicalistas) VALUES (0, 'Ninguno', 0), 
	(2, 'Funcionarios', 1), (3, 'Contratistas', 1), 
	(11, 'Aspirantes', 1), (12, 'Estudiantes', 1),  (13, 'Estudiantes ausentes', 0), (17, 'Egresados', 1), 
	(2209, 'Estudiantes del programa', 0), (12229, 'Convocados', 0),  
	(2306, 'Acompañamiento académico', 0), (2307, 'Seguimiento académico', 1), (2741, 'Postulados a grados', 0)";}
	if ($dbversion==8769){$sSQL="INSERT INTO masi73estadomensaje (masi73id, masi73nombre) VALUES (0, 'Borrador'), (3, 'Completo'), (7, 'Enviado')";}
	if ($dbversion==8770){$sSQL=$u08."(1201, 'Mensajes', 'gm.php?id=1201', 'Mensajes', 'Messages', 'Mensagems')";}

	if ($dbversion==8771){$sSQL="add_campos|unad95entorno|unad95doblefactor_limexcl int NOT NULL DEFAULT 0";}
	if ($dbversion==8772){$sSQL="add_campos|unad95entorno|unad95doblefactor_motivoexcl varchar(250) NULL";}

	if ($dbversion==8773){$sSQL="add_campos|cttc09procnota|cttc09consec_evento int NOT NULL DEFAULT 0";}
	if ($dbversion==8774){$sSQL="add_campos|cttc08procagenda|cttc08numrevisiones int NOT NULL DEFAULT 0";}
	if ($dbversion==8775){$sSQL="add_campos|corf60inscripcion|corf60aplicacertificado int NOT NULL DEFAULT 0";}

	if ($dbversion==8776){$sSQL=$u04."(1753, 17, 'S'), (1753, 1707, 'S')";}
	if ($dbversion==8777){$sSQL="ALTER TABLE moni01competencias MODIFY COLUMN moni01descripcion Text NULL";}
	if ($dbversion==8778){$sSQL="ALTER TABLE moni05resultadoprograma DROP INDEX moni05resultadoprograma_id";}
	if ($dbversion==8779){$sSQL=$objDB->sSQLCrearIndice('moni05resultadoprograma', 'moni05resultadoprograma_id', 'moni05idplan, moni05consec', true);}	

	if ($dbversion==8780){$sSQL="INSERT INTO visa78estadojornada (visa78id, visa78nombre) VALUES (0, 'Borrador'), (3, 'Programada'), (7, 'Ejecutada'), (9, 'Cancelada')";}
	if ($dbversion==8781){$sSQL="add_campos|unad45tipodoc|unad45equivgrados int NOT NULL DEFAULT 0";}
	if ($dbversion==8782){$sSQL="UPDATE unad45tipodoc SET unad45equivgrados=28 WHERE unad45id=1";}
	if ($dbversion==8783){$sSQL="UPDATE unad45tipodoc SET unad45equivgrados=29 WHERE unad45id=2";}
	if ($dbversion==8784){$sSQL="UPDATE unad45tipodoc SET unad45equivgrados=30 WHERE unad45id=5";}
	if ($dbversion==8785){$sSQL="INSERT INTO core38opciongrado (core38id, core38nombre, core38avancenecesario, core38vigente, core38nivelacademico, core38vaaproyecto) VALUES (9, 'Creditos academicos de programa de nivel universitario', 90.00, 'S', 3, 1)";}
	
	if ($dbversion==8786){$sSQL="CREATE TABLE aure05rol (aure05id int NOT NULL, aure05nombre varchar(250) NULL)";}
	if ($dbversion==8787){$sSQL="ALTER TABLE aure05rol ADD PRIMARY KEY(aure05id)";}
	if ($dbversion==8788){$sSQL="INSERT INTO aure05rol (aure05id, aure05nombre) VALUES (0, 'Ninguno'), (1, 'Funcionario'), (11, 'Estudiante'), (13, 'Egresado')";}
	if ($dbversion==8789){$sSQL="add_campos|corf60inscripcion|corf60rolunad int NOT NULL DEFAULT 0";}

	if ($dbversion==8790){$sSQL="CREATE TABLE aurf12tester (aurf12idcomponente int NOT NULL, aurf12idtercero int NOT NULL, aurf12id int NOT NULL DEFAULT 0, aurf12activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8791){$sSQL="ALTER TABLE aurf12tester ADD PRIMARY KEY(aurf12id)";}
	if ($dbversion==8792){$sSQL=$objDB->sSQLCrearIndice('aurf12tester', 'aurf12tester_id', 'aurf12idcomponente, aurf12idtercero', true);}
	if ($dbversion==8793){$sSQL="agregamodulo|4812|2|Pruebas nuevas funcionalidades|1|2|3|4|5|6|8";}
	if ($dbversion==8794){$sSQL=$u09."(4812, 1, 'Pruebas nuevas funcionalidades', 'auretester.php', 1, 4812, 'S', '', '')";}

	if ($dbversion==8795){$sSQL="agregamodulo|4730|22|Totalizado Inscripciones MOOC|1|5|6";}
	if ($dbversion==8796){$sSQL=$u09."(4730, 1, 'Totalizado Inscripciones MOOC', 'corerptmooc.php', 11, 4730, 'S', '', '')";}
	//8797 - queda libre.
	if ($dbversion==8798){$sSQL="add_campos|core38opciongrado|core38aplicapromediomin int NOT NULL DEFAULT 0|core38promediominimo Decimal(15,2) NULL DEFAULT 0|core38intentosmaximos int NOT NULL DEFAULT 0";}

	if ($dbversion==8799){$sSQL="agregamodulo|31|2|Inicio de sesion via Web Service|1|21";}
	if ($dbversion==8800){$sSQL="add_campos|unae43tokenws|unad43idmodulo int NOT NULL DEFAULT 0";}
	}
if (($dbversion>8800)&&($dbversion<8901)){
	if ($dbversion==8801){$sSQL="add_campos|cttc05oficina|cttc05notificacion_copia int NOT NULL DEFAULT 0|cttc05notificacion_correo varchar(100) NULL";}
	if ($dbversion==8802){$sSQL="add_campos|cttc02tipoproceso|cttc02notificar int NOT NULL DEFAULT 0|cttc02notificar_correo varchar(100) NULL";}
	if ($dbversion==8803){$sSQL="add_campos|ppto14documento|ppto14docorigen int NOT NULL DEFAULT 0|ppto14docarchivo int NOT NULL DEFAULT 0|ppto14docfechacierre int NOT NULL DEFAULT 0";}

	if ($dbversion==8804){$sSQL=$u09."(5120, 1, 'Usuarios', 'unadusuarios.php', 1, 5120, 'S', '', '')";}
	if ($dbversion==8805){$sSQL="agregamodulo|5120|51|Usuarios {107}|1";}

	if ($dbversion==8806){$sSQL="agregamodulo|1953|19|Revision solicitud encuesta GCMO|1|2|3|4|5|6|8";}
	if ($dbversion==8807){$sSQL=$u09."(1953, 1, 'Revision solicitud encuesta GCMO', 'evenencgcmo.php', 1902, 1953, 'S', '', '')";}
	if ($dbversion==8808){$sSQL="agregamodulo|1954|19|Revision solicitud encuesta SG|1|2|3|4|5|6|8";}
	if ($dbversion==8809){$sSQL=$u09."(1954, 1, 'Revision solicitud encuesta SG', 'evenencsg.php', 1902, 1954, 'S', '', '')";}	

	if ($dbversion==8810){$sSQL="add_campos|saiu03temasol|saiu03ordenfav int NOT NULL DEFAULT 0";}
	if ($dbversion==8811){$sSQL=$u04."(3073, 1707, 'S')";}

	if ($dbversion==8812){$sSQL="CREATE TABLE moni21compxrap (moni21idplan int NOT NULL, moni21idcompetencia int NOT NULL, moni21idrap int NOT NULL, moni21id int NOT NULL DEFAULT 0)";}
	if ($dbversion==8813){$sSQL="ALTER TABLE moni21compxrap ADD PRIMARY KEY(moni21id)";}
	if ($dbversion==8814){$sSQL=$objDB->sSQLCrearIndice('moni21compxrap', 'moni21compxrap_id', 'moni21idplan, moni21idcompetencia, moni21idrap', true);}
	if ($dbversion==8815){$sSQL=$objDB->sSQLCrearIndice('moni21compxrap', 'moni21compxrap_padre', 'moni21idplan');}
	if ($dbversion==8816){$sSQL="agregamodulo|5121|51|Competencias por RAP|1|2|3|4|5|6|8";}

	if ($dbversion==8817){$sSQL="agregamodulo|4135|41|Consolidado de contratos|1|5|6|12";}
	if ($dbversion==8818){$sSQL=$u09."(4135, 1, 'Consolidado de contratos', 'cttcrptconsolidado.php', 11, 4135, 'S', '', '')";}
	if ($dbversion==8819){$sSQL="ALTER TABLE moni05resultadoprograma DROP COLUMN moni05idcompetencia";}

	if ($dbversion==8820){$sSQL="INSERT INTO core65clasehomologa (core65id, core65nombre, core65visible, core65edmedia, core65edsuperior) VALUES 
	(7, 'Prueba de reconocimiento de Saberes', 1, 1, 0), 
	(8, 'Certificado de aprobación', 1, 1, 0)";}
	if ($dbversion==8821){$sSQL="agregamodulo|4924|49|Prueba de Reconocimiento de Saberes|1|2|3|4|5|6|17|1707";}
	if ($dbversion==8822){$sSQL=$u09."(4924, 1, 'Prueba de Reconocimiento de Saberes', 'sinereccompetencia.php', 2204, 4924, 'S', '', '')";}

	if ($dbversion==8823){$sSQL="add_campos|core71homolsolicitud|core71vs_idciclo int NOT NULL DEFAULT 0";}
	if ($dbversion==8824){$sSQL="INSERT INTO core66tipohomologa (core66consec, core66id, core66clase, core66activa, core66general, core66titulo, core66detalle, core66instruccionesalumno) VALUES 
	(-7, -7, 0, 0, 0, 'Prueba de Reconocimiento de Saberes y Competencias', 'Prueba de Reconocimiento de Saberes y Competencias', ''),
	(-8, -8, 0, 0, 0, 'Certificado de aprobación', 'Certificado de aprobación', '')";}
	if ($dbversion==8825){$sSQL="INSERT INTO core13tiporegistroprog (core13id, core13nombre, core13fijo, core13orden, core13sigla, core13nombredoc, core13aplicadoc) VALUES (13, 'Opción Grado Tec - Cred. Nivel Universitario', 0, 14, 'OGT', '', 0)";}

	if ($dbversion==8826){$sSQL="agregamodulo|20|20|Chatbot 360|1|1707";}

	if ($dbversion==8827){$sSQL="UPDATE core65clasehomologa  SET core65edmedia=0 WHERE core65id=4";}
	if ($dbversion==8828){$sSQL="agregamodulo|4925|49|Certificado de aprobación|1|2|3|4|5|6|17|1707";}
	if ($dbversion==8829){$sSQL=$u09."(4925, 1, 'Certificado de aprobación', 'sinecertaproba.php', 2204, 4925, 'S', '', '')";}
	if ($dbversion==8830){$sSQL="add_campos|core73homolcurso|core73nivelcurso int NOT NULL DEFAULT 0";}

	// 8831  A 8833 QUEDAN LIBRES
	if ($dbversion==8834){$sSQL="agregamodulo|3074|30|Redes de servicio|1|2|3|4|5|6|8|12";}
	if ($dbversion==8835){$sSQL=$u09."(3074, 1, 'Redes de servicio', 'sairedservicio.php', 1, 3074, 'S', '', '')";}
	if ($dbversion==8836){$sSQL="CREATE TABLE saiu75redequipo (saiu75idred int NOT NULL, saiu75idzona int NOT NULL, saiu75idcentro int NOT NULL, saiu75id int NOT NULL DEFAULT 0, saiu75idequipo int NOT NULL DEFAULT 0)";}
	if ($dbversion==8837){$sSQL="ALTER TABLE saiu75redequipo ADD PRIMARY KEY(saiu75id)";}
	if ($dbversion==8838){$sSQL=$objDB->sSQLCrearIndice('saiu75redequipo', 'saiu75redequipo_id', 'saiu75idred, saiu75idzona, saiu75idcentro', true);}
	if ($dbversion==8839){$sSQL=$objDB->sSQLCrearIndice('saiu75redequipo', 'saiu75redequipo_padre', 'saiu75idred');}
	if ($dbversion==8840){$sSQL="agregamodulo|3075|30|Red de servicio - equipos|1|2|3|4";}
	if ($dbversion==8841){$sSQL="add_campos|saiu03temasol|saiu03reddeservicio int NOT NULL DEFAULT 0";}

	if ($dbversion==8842){$sSQL="CREATE TABLE cttc36cttcprefijo (cttc36consec int NOT NULL, cttc36id int NOT NULL DEFAULT 0, cttc36activo int NOT NULL DEFAULT 0, cttc36nombre varchar(100) NULL, cttc36sigla varchar(20) NULL, cttc36formaarmado int NOT NULL DEFAULT 0)";}
	if ($dbversion==8843){$sSQL="ALTER TABLE cttc36cttcprefijo ADD PRIMARY KEY(cttc36id)";}
	if ($dbversion==8844){$sSQL=$objDB->sSQLCrearIndice('cttc36cttcprefijo', 'cttc36cttcprefijo_id', 'cttc36consec', true);}
	if ($dbversion==8845){$sSQL="agregamodulo|4136|41|Prefijos para minutas|1|2|3|4|5|6|8";}
	if ($dbversion==8846){$sSQL="CREATE TABLE cttc37minutas (cttc37vigencia int NOT NULL, cttc37prefijo int NOT NULL, cttc37consec int NOT NULL, cttc37version int NOT NULL, cttc37id int NOT NULL DEFAULT 0, cttc37fechaminuta int NOT NULL DEFAULT 0, cttc37estado int NOT NULL DEFAULT 0, cttc37etiqueta varchar(30) NULL, cttc37idproceso int NOT NULL DEFAULT 0, cttc37idcontratista int NOT NULL DEFAULT 0, cttc37idjuridico int NOT NULL DEFAULT 0, cttc37idaprueba int NOT NULL DEFAULT 0, cttc37idanula int NOT NULL DEFAULT 0, cttc37motivoanula Text NULL)";}
	if ($dbversion==8847){$sSQL="ALTER TABLE cttc37minutas ADD PRIMARY KEY(cttc37id)";}
	if ($dbversion==8848){$sSQL=$objDB->sSQLCrearIndice('cttc37minutas', 'cttc37minutas_id', 'cttc37vigencia, cttc37prefijo, cttc37consec, cttc37version', true);}
	if ($dbversion==8849){$sSQL="CREATE TABLE cttc84estadominuta (cttc84id int NOT NULL, cttc84nombre varchar(50) NULL)";}
	if ($dbversion==8850){$sSQL="ALTER TABLE cttc84estadominuta ADD PRIMARY KEY(cttc84id)";}
	if ($dbversion==8851){$sSQL=$u09."(4136, 1, 'Prefijos para minutas', 'cttcprefijos.php', 2, 4136, 'S', '', '')";}
	if ($dbversion==8852){$sSQL="agregamodulo|4137|41|Minutas|1|2|3|4|5|6|8";}
	if ($dbversion==8853){$sSQL=$u09."(4137, 1, 'Minutas', 'cttcminutas.php', 4101, 4137, 'S', '', '')";}
	if ($dbversion==8854){$sSQL="INSERT INTO cttc84estadominuta (cttc84id, cttc84nombre) VALUES (0, 'En elaboración'), (5, 'En Aprobación'), (7, 'Aprobada'), (9, 'Anulada'), (11, 'En firmas'), (17, 'Finalizada')";}
	if ($dbversion==8855){$sSQL=$unad70."(4136, 4137, 'cttc37minutas', 'cttc37id', 'cttc37prefijo', 'El dato esta incluido en Minutas', '')";}

	if ($dbversion==8856){$sSQL="INSERT INTO core35estadoenpde (core35id, core35nombre, core35orden, core35tono, core35sigla, core35sigla2) VALUES (16, 'Verificación de Certificados de Estudio', 6, 'FF6600', 'VCES', 'VCES'), (21, 'Validado', 12, '000033', 'VALI', 'VALI')";}
	if ($dbversion==8857){$sSQL="add_campos|core01estprograma|core01cipas_1sem int NOT NULL DEFAULT 0";}
	if ($dbversion==8858){$sSQL="add_campos|core01estprograma|core01resultado_1sem int NOT NULL DEFAULT 0";}

	if ($dbversion==8859){$sSQL="DROP TABLE saiu74reddeservicio";}
	if ($dbversion==8860){$sSQL="CREATE TABLE saiu74reddeservicio (saiu74consec int NOT NULL, saiu74idescuela int NOT NULL, saiu74id int NOT NULL DEFAULT 0, saiu74activa int NOT NULL DEFAULT 0, saiu74nombre varchar(100) NULL, saiu74idunidad int NOT NULL DEFAULT 0, saiu74idadministrador int NOT NULL DEFAULT 0)";}
	if ($dbversion==8861){$sSQL="ALTER TABLE saiu74reddeservicio ADD PRIMARY KEY(saiu74id)";}
	if ($dbversion==8862){$sSQL=$objDB->sSQLCrearIndice('saiu74reddeservicio', 'saiu74reddeservicio_id', 'saiu74consec, saiu74idescuela', true);}


	
	//if ($dbversion==8654){$sSQL=$u08."(5101, 'Monitoreo', 'gm.php?id=5101', 'Monitoreo', 'Monitoring', 'Monitoramento')";}
	}
if (($dbversion>8900)&&($dbversion<9001)){
	}
if (false) {
	if ($dbversion==8999){$sSQL=$u04."(3646, 10, 'S')";}
	//if ($dbversion==6781){$sSQL=$u09."(12280, 1, 'Cupos preoferta', 'corepreofcupos.php', 2206, 12280, 'S', '', '')";}
	//(3220, 'Conceptos para nómina', ''), (3221, 'Provisiones de nómina', '')
	//if ($dbversion==6604){$sSQL="INSERT INTO nico11momento (nico11id, nico11nombre, nico11ayuda) VALUES (3201, 'Liquidación Nomina', '')";}
	//, cttc11activo, cttc11anexo, cttc11observaciones, cttc11aprobacion, cttc11version
	//if ($dbversion==5330){$sSQL="agregamodulo|4071|40|CPC|1|2|3|4|5|6";}
	//if ($dbversion==5331){$sSQL=$u09."(4071, 1, 'CPC', 'heracpc.php', 1, 4071, 'S', '', '')";}
	//if ($dbversion==5334){$sSQL="agregamodulo|4072|40|Unidades de medida|1|2|3|4|5|6";}
	//if ($dbversion==5335){$sSQL=$u09."(4072, 1, 'Unidades de medida', 'heraunidadmedida.php', 2, 4072, 'S', '', '')";}
	if ($dbversion==8201) {$sSQL ="INSERT INTO ofes09estadorec (ofes09id, ofes09nombre) VALUES (0, 'Borrador'), (3, 'Devuelto'), (7, 'En firme')";}	
	// unae26unidadesfun
	// 2711 Proyectos de grado -- Consultar datos de otros usuarios 
	// 2282 Homologaciones por convenio - Abrir - 
	// 2200 Panel SAI - Consultar datos de otros usuarios.
	//if ($dbversion==5999){$sSQL=$u04."(2711, 12, 'S'), (2282, 17, 'S'), (2200, 12, 'S')";}
	/*
	if ($dbversion==4888){$sSQL="INSERT INTO corf09novedadtipo (corf09id, corf09nombre) VALUES (7, 'Aplazamiento Extemporaneo')";}
	if ($dbversion==4690){$sSQL="DROP VIEW unad11personas";}
	if ($dbversion==4690){$sSQL="CREATE VIEW unad11personas AS SELECT unad11tipodoc, unad11doc, unad11id, unad11pais, unad11usuario, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, unad11genero, unad11fechanace, unad11rh, unad11ecivil, unad11razonsocial, unad11direccion, unad11nacionalidad, unad11deptoorigen, unad11ciudadorigen, unad11deptodoc, unad11ciudaddoc, unad11idmoodle, unad11idcampus, unad11correoinstitucional, unad11idzona, unad11idcead, unad11idescuela, unad11idprograma, unad11presentacion, unad11necesidadesp, unad11idioma, unad11autenticador, unad11fechaclave, unad11debeactualizarclave, unad11formaclave
	FROM unad11terceros";}
	*/
}
	
	//if ($dbversion==3099){$sSQL="INSERT INTO unae16cronaccion (unae16id, unae16accion) VALUES (000, 'xxx')";}
	//if ($dbversion==494){$sSQL=$u03."(1702, 'Ofertar Curso'), (1703, 'Cancelar Oferta'), (1704, 'Carga Masiva de Oferta')";}
	//if ($dbversion==510){$sSQL=$u04."(1716, 1711, 'S'), (1716, 1712, 'S'), (1716, 1713, 'S')";}
	//$u22="INSERT INTO unad22combos (unad22idmodulo, unad22consec, unad22codopcion, unad22nombre, unad22orden, unad22activa) VALUES ";
	echo '<li>[ ' . $dbversion . ' ] - ' . $sSQL . '</li>';
	switch (substr($sSQL,0,10)){
		case 'versionado':
			$sper=explode("|",$sSQL);
			$stemp="UPDATE unad01sistema SET unad01mayor=".$sper[2].", unad01menor=".$sper[3].", unad01correccion=".$sper[4]." WHERE unad01id=".$sper[1];
			$result=$objDB->ejecutasql($stemp);
		break;
		case 'agregamodu':
			$sper=explode("|",$sSQL);
			$stemp="INSERT INTO unad02modulos (unad02id, unad02nombre, unad02idsistema) VALUES (".$sper[1].", '".$sper[3]."', ".$sper[2].")";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			for ($k=4;$k<count($sper);$k++){
				$stemp=$u04."(".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objDB->ejecutasql($stemp);
				echo " .";
				$stemp=$u06."(1, ".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objDB->ejecutasql($stemp);
				echo ".";
				}
			break;
		case "crearmodul":
			$sper=explode("|",$sSQL);
			$stemp="INSERT INTO unad02modulos (unad02id, unad02nombre, unad02idsistema) VALUES (".$sper[1].", '".$sper[3]."', ".$sper[2].")";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			for ($k=4;$k<count($sper);$k++){
				$stemp=$u04."(".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objDB->ejecutasql($stemp);
				echo " .";
				}
			break;
		case "modulogrup":
			$sper=explode("|",$sSQL);
			for ($k=3;$k<count($sper);$k++){
				$stemp=$u06."(".$sper[2].", ".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objDB->ejecutasql($stemp);
				echo ".";
				}
			break;
		case 'add_campos':
			$aCampos=explode('|',$sSQL);
			$sTabla= $aCampos[1];
			$iCampos = count($aCampos);
			for ($k=2;$k<$iCampos;$k++){
				$sTemp = 'ALTER TABLE ' . $sTabla . ' ADD ' . $aCampos[$k];
				$result=$objDB->ejecutasql($sTemp);
				if ($result == false) {
					echo '<li> -- Error ejecutando <font color="#FF0000"><b>'.$sTemp.'</b></font> <b>' . $objDB->serror . '</b></li>';
					$error++;
					$suspende=1;
				}
			}
			break;
		case 'drop_campo':
			$aCampos=explode('|',$sSQL);
			$sTabla= $aCampos[1];
			$iCampos = count($aCampos);
			for ($k=2;$k<$iCampos;$k++){
				$sTemp = 'ALTER TABLE ' . $sTabla . ' DROP COLUMN ' . $aCampos[$k];
				$result=$objDB->ejecutasql($sTemp);
				if ($result == false) {
					echo '<li> -- Error ejecutando <font color="#FF0000"><b>'.$sTemp.'</b></font> <b>' . $objDB->serror . '</b></li>';
					$error++;
					$suspende=1;
				}
			}
			break;	
		case 'DROP TABLE':
			$nomtabla=substr($sSQL,11);
			if ($objDB->bexistetabla($nomtabla)){
				$result=$objDB->ejecutasql($sSQL);
			} else {
				echo '<br> -- La tabla <b>'.$nomtabla.'</b> no existe.';
			}
			break;
		case "mod_cod_ca":
			$sper=explode("|",$sSQL);
			$stemp="UPDATE unad02modulos SET unad02id=".$sper[2]." WHERE unad02id=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="UPDATE unad04modulopermisos SET unad04idmodulo=".$sper[2]." WHERE unad04idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="UPDATE unad06perfilmodpermiso SET unad06idmodulo=".$sper[2]." WHERE unad06idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="UPDATE unad09modulomenu SET unad09idmodulo=".$sper[2]." WHERE unad09idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			break;
		case "mod_quitar":
			$sper=explode("|",$sSQL);
			$stemp="DELETE FROM unad02modulos WHERE unad02id=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="DELETE FROM unad04modulopermisos WHERE unad04idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="DELETE FROM unad06perfilmodpermiso WHERE unad06idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="DELETE FROM unad09modulomenu WHERE unad09idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			break;
		case '':
			break;
		default:
		$bHayError=false;
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$bHayError=true;
			//Si viene un DROP INDEX no hay error.
			if (strpos($sSQL, 'DROP INDEX')>0){$bHayError=false;}
			if (strpos($sSQL, 'DROP PRIMARY KEY')>0){$bHayError=false;}
			}
		if ($bHayError){
			//$sError = '<li>[ ' . $dbversion . ' ] <font color="#FF0000"><b>Error </b>'.$objDB->serror.'</font></li>';
			echo '<li> -- <font color="#FF0000">'.$objDB->serror.'</font></li>';
			$error++;
			$suspende=1;
			}
		}//fin del switch
	$sSQL="UPDATE unad00config SET unad00valor=".($dbversion+1)." WHERE unad00codigo='dbversion';";
	$result=$objDB->ejecutasql($sSQL);
	$dbversion++;
	$procesos++;
	if ($procesos>14){
		$suspende=1;
		break;
		}
	}//termina de ejecutar sentencia por sentenca.
echo '</ul>';
if ($sError) {
	echo $sError . '<br>';
}
$objDB->CerrarConexion();
?>
<br>Base de Datos Actualizada <?php echo $dbversion; ?>;
<?php if($suspende==1){?><br>
<form id="form1" name="form1" method="post" action="">
El Proceso A&uacute;n No Ha Concluido
<?php
if (false){//$notablas
?>
<input name="notablas" type="hidden" id="notablas" value="1" />
<?php
	}
?>
<div class="salto5px"></div>
<input class="btn-success" type="submit" name="Submit" value="Continuar" />
</form>
<?php
if ($error==0){
?>
<script language="javascript">
function recargar(){
	form1.submit();
	}
setInterval ("recargar();", 1000); 
</script>
<?php 
}//fin de si no hay errores...
}
piedepagina();
