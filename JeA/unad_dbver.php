<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Inicia Lunes, 5 de junio de 2023
--- Esta página se encarga de mantener actualizado los script de las bases de datos.
*/
error_reporting(E_ALL);
set_time_limit(0);
require './app.php';
if (isset($APP->dbhost)==0){
	echo 'No se ha definido el servidor de base de datos';
	die();
	}
require $APP->rutacomun.'libs/clsdbadmin.php';
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->dbmodelo) == 0){
	$APP->dbmodelo = 'M';
}
$versionejecutable=6491;
$procesos=0;
$suspende=0;
$error=0;
echo 'Iniciando proceso de revision de la base de datos [DB : '.$APP->dbname.']';
$sSQL=$objDB->sSQLListaTablas('unad00config');
$result=$objDB->ejecutasql($sSQL);
$cant=$objDB->nf($result);
if ($cant<1){
	echo '<br>Debe ejecutar el script inicial';
	die();		
}else{
	$sSQL="SELECT unad00valor FROM unad00config WHERE unad00codigo='dbversion';";
	$result=$objDB->ejecutasql($sSQL);
	$row=$objDB->sf($result);
	$dbversion=$row['unad00valor'];
	$bbloquea=false;
	if ($dbversion<6000){$bbloquea=true;}
	if ($dbversion>7000){$bbloquea=true;}
	if ($bbloquea){
		echo '<br>Debe ejecutar el script que corresponda a la version {'.$dbversion.'}...';
		die();		
	}
}
echo "<br>Version Actual de la base de datos ".$dbversion;
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
if (($dbversion>6000)&&($dbversion<6101)){
	if ($dbversion==6001){$sSQL="CREATE TABLE empr38estadomomento (empr38id int NOT NULL, empr38nombre varchar(50) NULL)";}
	if ($dbversion==6002){$sSQL="ALTER TABLE empr38estadomomento ADD PRIMARY KEY(empr38id)";}
	if ($dbversion==6003){$sSQL="INSERT INTO empr38estadomomento (empr38id, empr38nombre) VALUES (0, 'Borrador'), (1, 'Devuelto'), (3, 'Radicado'), (7, 'Aprobado')";}
	if ($dbversion==6004){$sSQL="add_campos|prac11rutacpcurso|prag11tiporegistro int NOT NULL DEFAULT 0";}
	if ($dbversion==6005){$sSQL="add_campos|empr04emprendimiento|empr04et3_mision Text NULL|empr04et3_vision Text NULL|empr04et3_objetivos Text NULL|empr04et3_presupuesto Text NULL|empr04et1_11_estado int NOT NULL DEFAULT 0|empr04et1_12_estado int NOT NULL DEFAULT 0|empr04et1_13_estado int NOT NULL DEFAULT 0|empr04et1_14_estado int NOT NULL DEFAULT 0|empr04et2_21_estado int NOT NULL DEFAULT 0|empr04et2_22_estado int NOT NULL DEFAULT 0|empr04et2_23_estado int NOT NULL DEFAULT 0|empr04et2_24_estado int NOT NULL DEFAULT 0|empr04et2_25_estado int NOT NULL DEFAULT 0|empr04et3_31_estado int NOT NULL DEFAULT 0|empr04et3_32_estado int NOT NULL DEFAULT 0|empr04et3_33_estado int NOT NULL DEFAULT 0|empr04et3_34_estado int NOT NULL DEFAULT 0|empr04et3_35_estado int NOT NULL DEFAULT 0|empr04et3_36_estado int NOT NULL DEFAULT 0|empr04et3_37_estado int NOT NULL DEFAULT 0|empr04et3_38_estado int NOT NULL DEFAULT 0";}
	if ($dbversion==6006){$sSQL="CREATE TABLE empr06empanexo (empr06idemprend int NOT NULL, empr06idmomento int NOT NULL, empr06consec int NOT NULL, empr06id int NOT NULL DEFAULT 0, empr06estado int NOT NULL DEFAULT 0, empr06titulo varchar(100) NULL, empr06idorigen int NOT NULL DEFAULT 0, empr06idarchivo int NOT NULL DEFAULT 0, empr06idusuario int NOT NULL DEFAULT 0, empr06fecha int NOT NULL DEFAULT 0, empr06idaprueba int NOT NULL DEFAULT 0, empr06fechaaprueba int NOT NULL DEFAULT 0)";}
	if ($dbversion==6007){$sSQL="ALTER TABLE empr06empanexo ADD PRIMARY KEY(empr06id)";}
	if ($dbversion==6008){$sSQL=$objDB->sSQLCrearIndice('empr06empanexo', 'empr06empanexo_id', 'empr06idemprend, empr06idmomento, empr06consec', true);}
	if ($dbversion==6009){$sSQL=$objDB->sSQLCrearIndice('empr06empanexo', 'empr06empanexo_padre', 'empr06idemprend');}
	if ($dbversion==6010){$sSQL="agregamodulo|4506|45|Emprendimiento - Anexos|1|2|3|4|5|6|8";}
	if ($dbversion==6011){$sSQL="CREATE TABLE empr07empnota (empr07idemprend int NOT NULL, empr07idmomento int NOT NULL, empr07consec int NOT NULL, empr07id int NOT NULL DEFAULT 0, empr07anotacion Text NULL, empr07idusuario int NOT NULL DEFAULT 0, empr07fecha int NOT NULL DEFAULT 0, empr07hora int NOT NULL DEFAULT 0, empr07minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==6012){$sSQL="ALTER TABLE empr07empnota ADD PRIMARY KEY(empr07id)";}
	if ($dbversion==6013){$sSQL=$objDB->sSQLCrearIndice('empr07empnota', 'empr07empnota_id', 'empr07idemprend, empr07idmomento, empr07consec', true);}
	if ($dbversion==6014){$sSQL=$objDB->sSQLCrearIndice('empr07empnota', 'empr07empnota_padre', 'empr07idemprend');}
	if ($dbversion==6015){$sSQL="agregamodulo|4507|45|Emprendimiento - Anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==6016){$sSQL="add_campos|core01estprograma|core01nummat_abandono int NOT NULL DEFAULT -1";}
	if ($dbversion==6017){$sSQL="add_campos|core16actamatricula|core16abandono int NOT NULL DEFAULT -1";}
	if ($dbversion==6018){$sSQL=$objDB->sSQLCrearIndice('core16actamatricula', 'core16actamatricula_pei', 'core16idestprog');}
	if ($dbversion==6019){$sSQL=$objDB->sSQLCrearIndice('core16actamatricula', 'core16actamatricula_abandono', 'core16abandono');}
	if ($dbversion==6020){$sSQL=$objDB->sSQLCrearIndice('core01estprograma', 'core01estprograma_abandono', 'core01nummat_abandono');}
	if ($dbversion==6021){$sSQL="add_campos|empr37momento|empr37ayuda Text NULL";}
	if ($dbversion==6022){$sSQL="agregamodulo|4537|45|Momentos|1|3";}
	if ($dbversion==6023){$sSQL=$u09."(4537, 1, 'Momentos', 'emprmomento.php', 2, 4537, 'S', '', '')";}
	if ($dbversion==6024){$sSQL="CREATE TABLE prac12rutaestudiante (prac12idpei int NOT NULL, prac12id int NOT NULL DEFAULT 0, prac12idestudiante int NOT NULL DEFAULT 0, prac12idprograma int NOT NULL DEFAULT 0, prac12idplan int NOT NULL DEFAULT 0, prac12numcursos int NOT NULL DEFAULT 0, prac12numaprobados int NOT NULL DEFAULT 0, prac12porcavance Text NULL, prac12fechaproc int NOT NULL DEFAULT 0, prac12c1_codigo int NOT NULL DEFAULT 0, prac12c1_estado int NOT NULL DEFAULT 0, prac12c2_codigo int NOT NULL DEFAULT 0, prac12c2_estado int NOT NULL DEFAULT 0, prac12c3_codigo int NOT NULL DEFAULT 0, prac12c3_estado int NOT NULL DEFAULT 0, prac12c4_codigo int NOT NULL DEFAULT 0, prac12c4_estado int NOT NULL DEFAULT 0, prac12c5_codigo int NOT NULL DEFAULT 0, prac12c5_estado int NOT NULL DEFAULT 0, prac12c6_codigo int NOT NULL DEFAULT 0, prac12c6_estado int NOT NULL DEFAULT 0, prac12c7_codigo int NOT NULL DEFAULT 0, prac12c7_estado int NOT NULL DEFAULT 0, prac12c8_codigo int NOT NULL DEFAULT 0, prac12c8_estado int NOT NULL DEFAULT 0, prac12c9_codigo int NOT NULL DEFAULT 0, prac12c9_estado int NOT NULL DEFAULT 0, prac12c10_codigo int NOT NULL DEFAULT 0, prac12c10_estado int NOT NULL DEFAULT 0, prac12c11_codigo int NOT NULL DEFAULT 0, prac12c11_estado int NOT NULL DEFAULT 0, prac12c12_codigo int NOT NULL DEFAULT 0, prac12c12_estado int NOT NULL DEFAULT 0, prac12c13_codigo int NOT NULL DEFAULT 0, prac12c13_estado int NOT NULL DEFAULT 0, prac12c14_codigo int NOT NULL DEFAULT 0, prac12c14_estado int NOT NULL DEFAULT 0, prac12c15_codigo int NOT NULL DEFAULT 0, prac12c15_estado int NOT NULL DEFAULT 0, prac12c16_codigo int NOT NULL DEFAULT 0, prac12c16_estado int NOT NULL DEFAULT 0, prac12c17_codigo int NOT NULL DEFAULT 0, prac12c17_estado int NOT NULL DEFAULT 0, prac12c18_codigo int NOT NULL DEFAULT 0, prac12c18_estado int NOT NULL DEFAULT 0, prac12c19_codigo int NOT NULL DEFAULT 0, prac12c19_estado int NOT NULL DEFAULT 0, prac12c20_codigo int NOT NULL DEFAULT 0, prac12c20_estado int NOT NULL DEFAULT 0, prac12c21_codigo int NOT NULL DEFAULT 0, prac12c21_estado int NOT NULL DEFAULT 0, prac12c22_codigo int NOT NULL DEFAULT 0, prac12c22_estado int NOT NULL DEFAULT 0, prac12c23_codigo int NOT NULL DEFAULT 0, prac12c23_estado int NOT NULL DEFAULT 0, prac12c24_codigo int NOT NULL DEFAULT 0, prac12c24_estado int NOT NULL DEFAULT 0, prac12c25_codigo int NOT NULL DEFAULT 0, prac12c25_estado int NOT NULL DEFAULT 0, prac12c26_codigo int NOT NULL DEFAULT 0, prac12c26_estado int NOT NULL DEFAULT 0, prac12c27_codigo int NOT NULL DEFAULT 0, prac12c27_estado int NOT NULL DEFAULT 0, prac12c28_codigo int NOT NULL DEFAULT 0, prac12c28_estado int NOT NULL DEFAULT 0, prac12c29_codigo int NOT NULL DEFAULT 0, prac12c29_estado int NOT NULL DEFAULT 0, prac12c30_codigo int NOT NULL DEFAULT 0, prac12c30_estado int NOT NULL DEFAULT 0)";}
	if ($dbversion==6025){$sSQL="ALTER TABLE prac12rutaestudiante ADD PRIMARY KEY(prac12id)";}
	if ($dbversion==6026){$sSQL=$objDB->sSQLCrearIndice('prac12rutaestudiante', 'prac12rutaestudiante_id', 'prac12idpei', true);}
	if ($dbversion==6027){$sSQL="agregamodulo|3112|31|Ruta Componente Practico Ind.|1|3|5|6|12|1707";}
	if ($dbversion==6028){$sSQL=$u09."(3112, 1, 'Ruta Componente Práctico Individual', 'pracrutaindividual.php', 2704, 3112, 'S', '', '')";}
	if ($dbversion==6029){$sSQL="add_campos|empr04emprendimiento|empr04idzona int NOT NULL DEFAULT 0|empr04idcentro int NOT NULL DEFAULT 0|empr04idescuela int NOT NULL DEFAULT 0|empr04idprograma int NOT NULL DEFAULT 0";}
	if ($dbversion==6030){$sSQL="CREATE TABLE repo28sabanaoferta (repo28idcurso int NOT NULL, repo28agno int NOT NULL DEFAULT 0, repo28id int NOT NULL DEFAULT 0, repo28idescuela int NOT NULL DEFAULT 0, repo28idprograma int NOT NULL DEFAULT 0, repo28tipocurso int NOT NULL DEFAULT 0, repo28rcp int NOT NULL DEFAULT 0, repo28p1_oferta int NOT NULL DEFAULT 0, repo28p1_tipoeval int NOT NULL DEFAULT 0, repo28p1_tutores int NOT NULL DEFAULT 0, repo28p1_est int NOT NULL DEFAULT 0, repo28p1_ceros int NOT NULL DEFAULT 0, repo28p1_aprob int NOT NULL DEFAULT 0, repo28p1_prom Decimal(15,2) NULL DEFAULT 0, repo28p2_oferta int NOT NULL DEFAULT 0, repo28p2_tipoeval int NOT NULL DEFAULT 0, repo28p2_tutores int NOT NULL DEFAULT 0, repo28p2_est int NOT NULL DEFAULT 0, repo28p2_ceros int NOT NULL DEFAULT 0, repo28p2_aprob int NOT NULL DEFAULT 0, repo28p2_prom Decimal(15,2) NULL DEFAULT 0, repo28p3_oferta int NOT NULL DEFAULT 0, repo28p3_tipoeval int NOT NULL DEFAULT 0, repo28p3_tutores int NOT NULL DEFAULT 0, repo28p3_est int NOT NULL DEFAULT 0, repo28p3_ceros int NOT NULL DEFAULT 0, repo28p3_aprob int NOT NULL DEFAULT 0, repo28p3_prom Decimal(15,2) NULL DEFAULT 0, repo28p4_oferta int NOT NULL DEFAULT 0, repo28p4_tipoeval int NOT NULL DEFAULT 0, repo28p4_tutores int NOT NULL DEFAULT 0, repo28p4_est int NOT NULL DEFAULT 0, repo28p4_ceros int NOT NULL DEFAULT 0, repo28p4_aprob int NOT NULL DEFAULT 0, repo28p4_prom Decimal(15,2) NULL DEFAULT 0, repo28p5_oferta int NOT NULL DEFAULT 0, repo28p5_tipoeval int NOT NULL DEFAULT 0, repo28p5_tutores int NOT NULL DEFAULT 0, repo28p5_est int NOT NULL DEFAULT 0, repo28p5_ceros int NOT NULL DEFAULT 0, repo28p5_aprob int NOT NULL DEFAULT 0, repo28p5_prom Decimal(15,2) NULL DEFAULT 0, repo28numofertas int NOT NULL DEFAULT 0, repo28fechaproceso int NOT NULL DEFAULT 0)";}
	if ($dbversion==6031){$sSQL="ALTER TABLE repo28sabanaoferta ADD PRIMARY KEY(repo28id)";}
	if ($dbversion==6032){$sSQL=$objDB->sSQLCrearIndice('repo28sabanaoferta', 'repo28sabanaoferta_id', 'repo28idcurso, repo28agno', true);}
	if ($dbversion==6033){$sSQL="agregamodulo|2828|28|Sabana de oferta|1|5|6";}
	if ($dbversion==6034){$sSQL=$u09."(2828, 1, 'Sabana de oferta', 'rptsabanaoferta.php', 1701, 2809, 'S', '', '')";}
	if ($dbversion==6035){$sSQL="CREATE TABLE repo29agno (repo29agno int NOT NULL, repo29id int NOT NULL DEFAULT 0, repo29idciclo1 int NOT NULL DEFAULT 0, repo29idperiodo1 int NOT NULL DEFAULT 0, repo29idperiodo2 int NOT NULL DEFAULT 0, repo29idperiodo3 int NOT NULL DEFAULT 0, repo29idciclo2 int NOT NULL DEFAULT 0, repo29idperiodo4 int NOT NULL DEFAULT 0, repo29idperiodo5 int NOT NULL DEFAULT 0)";}
	if ($dbversion==6036){$sSQL="ALTER TABLE repo29agno ADD PRIMARY KEY(repo29id)";}
	if ($dbversion==6037){$sSQL=$objDB->sSQLCrearIndice('repo29agno', 'repo29agno_id', 'repo29agno', true);}
	if ($dbversion==6038){$sSQL="agregamodulo|2829|28|Configurar años|1|2|3|4";}
	if ($dbversion==6039){$sSQL=$u09."(2829, 1, 'Configurar años', 'datosagnos.php', 2, 2829, 'S', '', '')";}
	if ($dbversion==6040){$sSQL="add_campos|ofer08oferta|ofer08c2res_cancela int NOT NULL DEFAULT 0|ofer08c2res_aplaza int NOT NULL DEFAULT 0|ofer08c2res_inasiste int NOT NULL DEFAULT 0|ofer08c2res_abandono int NOT NULL DEFAULT 0|ofer08c2res_reproba int NOT NULL DEFAULT 0|ofer08c2res_aproba int NOT NULL DEFAULT 0";}
	if ($dbversion==6041){$sSQL="add_campos|repo28sabanaoferta|repo28p1_aplaza int NOT NULL DEFAULT 0|repo28p2_aplaza int NOT NULL DEFAULT 0|repo28p3_aplaza int NOT NULL DEFAULT 0|repo28p4_aplaza int NOT NULL DEFAULT 0|repo28p5_aplaza int NOT NULL DEFAULT 0|repo28p1_cancela int NOT NULL DEFAULT 0|repo28p2_cancela int NOT NULL DEFAULT 0|repo28p3_cancela int NOT NULL DEFAULT 0|repo28p4_cancela int NOT NULL DEFAULT 0|repo28p5_cancela int NOT NULL DEFAULT 0";}
	if ($dbversion==6042){$sSQL=$objDB->sSQLCrearIndice('prac12rutaestudiante', 'prac12rutaestudiante_idplan', 'prac12idplan');}
	if ($dbversion==6043){$sSQL=$objDB->sSQLCrearIndice('prac12rutaestudiante', 'prac12rutaestudiante_estudiante', 'prac12idestudiante');}
	if ($dbversion==6044){$sSQL="add_campos|core09programa|core09idcondicion int NOT NULL DEFAULT 0";}
	if ($dbversion==6045){$sSQL="CREATE TABLE corf17progcondicion (corf17id int NOT NULL, corf17nombre varchar(50) NULL)";}
	if ($dbversion==6046){$sSQL="ALTER TABLE corf17progcondicion ADD PRIMARY KEY(corf17id)";}
	if ($dbversion==6047){$sSQL="INSERT INTO corf17progcondicion (corf17id, corf17nombre) VALUES (0, 'Sin definir'), (1, 'En diseño'), (11, 'Oferta nueva'), (21, 'Oferta consolidada'), (31, 'Oferta en transición'), (51, 'Oferta cerrada'), (71, 'Oferta SINEP'), (72, 'Oferta SINEC'), (81, 'Convenio SINEP'), (82, 'Convenio SINEC'), (99, 'Convenio')";}
	if ($dbversion==6048){$sSQL="add_campos|unad11terceros|unad11estado int NOT NULL DEFAULT 0";}
	if ($dbversion==6049){$sSQL="agregamodulo|4304|1|Bloqueo de acceso|1|2|3|5|6";}
	if ($dbversion==6050){$sSQL="CREATE TABLE unaf05bloqueos (unaf05idtercero int NOT NULL, unaf05consec int NOT NULL, unaf05id int NOT NULL DEFAULT 0, unaf05estado int NOT NULL DEFAULT 0, unaf05motivo Text NULL, unaf05idusuario int NOT NULL DEFAULT 0, unaf05fecha int NOT NULL DEFAULT 0, unaf05hora int NOT NULL DEFAULT 0, unaf05minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==6051){$sSQL="ALTER TABLE unaf05bloqueos ADD PRIMARY KEY(unaf05id)";}
	if ($dbversion==6052){$sSQL=$objDB->sSQLCrearIndice('unaf05bloqueos', 'unaf05bloqueos_id', 'unaf05idtercero, unaf05consec', true);}
	if ($dbversion==6053){$sSQL=$objDB->sSQLCrearIndice('unaf05bloqueos', 'unaf05bloqueos_padre', 'unaf05idtercero');}
	if ($dbversion==6054){$sSQL=$u09."(4304, 1, 'Bloqueo de acceso', 'unadbloqueoacceso.php', 201, 4304, 'S', '', '')";}
	if ($dbversion==6055){$sSQL="add_campos|aure60ddtablas|aure60idmodulo int NOT NULL DEFAULT 0";}
	if ($dbversion==6056){$sSQL="agregamodulo|170|1|Bloqueos al eliminar|1|2|3|4|5|6";}
	if ($dbversion==6057){$sSQL="CREATE TABLE ofes08recursos (ofes08consec int NOT NULL, ofes08id int NOT NULL DEFAULT 0, ofes08activo int NOT NULL DEFAULT 0, ofes08tipo int NOT NULL DEFAULT 0, ofes08nombre varchar(250) NULL)";}
	if ($dbversion==6058){$sSQL="ALTER TABLE ofes08recursos ADD PRIMARY KEY(ofes08id)";}
	if ($dbversion==6059){$sSQL=$objDB->sSQLCrearIndice('ofes08recursos', 'ofes08recursos_id', 'ofes08consec', true);}
	if ($dbversion==6060){$sSQL="agregamodulo|1808|17|Recursos [Cursos]|1|2|3|4|5|6|8";}
	if ($dbversion==6061){$sSQL=$u09."(1808, 1, 'Recursos', 'oferrecursos.php', 3, 1808, 'S', '', '')";}
	if ($dbversion==6062){$sSQL="CREATE TABLE ofes09estadorec (ofes09id int NOT NULL, ofes09nombre varchar(50) NULL)";}
	if ($dbversion==6063){$sSQL="ALTER TABLE ofes09estadorec ADD PRIMARY KEY(ofes09id)";}
	if ($dbversion==6064){$sSQL="CREATE TABLE ofes07cursorecurso (ofes07idcurso int NOT NULL, ofes07idperiodo int NOT NULL, ofes07idrecurso int NOT NULL, ofes07consec int NOT NULL, ofes07id int NOT NULL DEFAULT 0, ofes07estado int NOT NULL DEFAULT 0, ofes07idusuario int NOT NULL DEFAULT 0, ofes07fechacarga int NOT NULL DEFAULT 0, ofes07fechavence int NOT NULL DEFAULT 0, ofes07idvalida int NOT NULL DEFAULT 0, ofes07fechavalida int NOT NULL DEFAULT 0, ofes07detalle Text NULL, ofes07idtiporecurso int NOT NULL DEFAULT 0)";}
	if ($dbversion==6065){$sSQL="ALTER TABLE ofes07cursorecurso ADD PRIMARY KEY(ofes07id)";}
	if ($dbversion==6066){$sSQL=$objDB->sSQLCrearIndice('ofes07cursorecurso', 'ofes07cursorecurso_id', 'ofes07idcurso, ofes07idperiodo, ofes07idrecurso, ofes07consec', true);}
	if ($dbversion==6067){$sSQL="agregamodulo|1807|17|Recursos por curso|1|2|3|4|5|6|8";}
	if ($dbversion==6068){$sSQL="add_campos|plan14metaprod|plan14idorigen int NOT NULL DEFAULT 0|plan14idarchivo int NOT NULL DEFAULT 0";}
	if ($dbversion==6069){$sSQL="add_campos|ofer52cohortes|ofer52acredita int NOT NULL DEFAULT 1|ofer52certifica int NOT NULL DEFAULT 1";}
	if ($dbversion==6070){$sql="INSERT INTO grad02tipocohorte (grad02id, grad02nombre) VALUES (11, 'SINEP')";}
	if ($dbversion==6071){$sSQL="DROP TABLE grad03devoluciones";}
	if ($dbversion==6072){$sSQL="DELETE FROM unad70bloqueoelimina WHERE unad70idtablabloquea IN (2702, 2703)";}
	if ($dbversion==6073){$sSQL="mod_quitar|2702";}
	if ($dbversion==6074){$sSQL="mod_quitar|2703";}
	if ($dbversion==6075){$sSQL="DROP TABLE grad10tipoproyecto";}
	if ($dbversion==6076){$sSQL="add_campos|grad02tipocohorte|grad02bachiller int NOT NULL DEFAULT 0";}
	if ($dbversion==6077){$sSQL="UPDATE grad02tipocohorte SET grad02bachiller=1 WHERE grad02id=11";}
	if ($dbversion==6078){$sSQL=$unad70."(2602,2740,'grad03tipodocgrad','grad03id','grad03idtipodocumento','El dato esta incluido en Documentos para postulacion', '')";}
	if ($dbversion==6079){$sSQL="CREATE TABLE grad03tipodocgrad (grad03idnivel int NOT NULL, grad03consec int NOT NULL, grad03id int NOT NULL DEFAULT 0, grad03vigente int NOT NULL DEFAULT 0, grad03opcional int NOT NULL DEFAULT 0, grad03orden int NOT NULL DEFAULT 0, grad03visible int NOT NULL DEFAULT 0, grad03nombre varchar(50) NULL, grad03proveedor int NOT NULL DEFAULT 0, grad03idtipodocumento int NOT NULL DEFAULT 0)";}
	if ($dbversion==6080){$sSQL="ALTER TABLE grad03tipodocgrad ADD PRIMARY KEY(grad03id)";}
	if ($dbversion==6081){$sSQL=$objDB->sSQLCrearIndice('grad03tipodocgrad', 'grad03tipodocgrad_id', 'grad03idnivel, grad03consec', true);}
	if ($dbversion==6082){$sSQL="agregamodulo|2703|27|Documentos para postulación|1|2|3|4|5|6|8";}
	if ($dbversion==6083){$sSQL=$u09."(2703, 1, 'Documentos para postulación', 'graddocpostula.php', 2, 2703, 'S', '', '')";}
	if ($dbversion==6084){$sSQL="CREATE TABLE grad10nivelgrado (grad10id int NOT NULL, grad10nombre varchar(50) NULL)";}
	if ($dbversion==6085){$sSQL="ALTER TABLE grad10nivelgrado ADD PRIMARY KEY(grad10id)";}
	if ($dbversion==6086){$sSQL="INSERT INTO grad10nivelgrado(grad10id, grad10nombre) VALUES (0,'{Sin definir}'), (1,'Bachillerato'), (2,'Tecnología'), (3,'Profesional'), (4,'PostGrado')";}
	if ($dbversion==6087){$sSQL="CREATE TABLE grad41postulaciones (grad41consec int NOT NULL DEFAULT 0, grad41id int NOT NULL DEFAULT 0, grad41idtercero int NOT NULL DEFAULT 0, grad41idprograma int NOT NULL DEFAULT 0, grad41idpei int NOT NULL DEFAULT 0, grad41idfecha int NOT NULL DEFAULT 0, grad41idcohorte int NOT NULL DEFAULT 0, grad41idestado int NOT NULL DEFAULT 0, grad41fechaaprueba int NOT NULL DEFAULT 0, grad41idrecibopago int NOT NULL DEFAULT 0, grad41agnopresenta int NOT NULL DEFAULT 0, grad41codigoicfes varchar(50) NULL)";}
	if ($dbversion==6088){$sSQL="ALTER TABLE grad41postulaciones ADD PRIMARY KEY(grad41id)";}
	if ($dbversion==6089){$sSQL="agregamodulo|2741|27|Postulados|1|2|3|4|5|6|8";}
	if ($dbversion==6090){$sSQL=$u09."(2741, 1, 'Postulados', 'gradpostulado.php', 2201, 2741, 'S', '', '')";}
	if ($dbversion==6091){$sSQL=$unad70."(2703,2742,'grad42docgrado','grad42id','grad42iddocumento','El dato esta incluido en Anexos', '')";}
	if ($dbversion==6092){$sSQL="CREATE TABLE grad42docgrado (grad42idpostulacion int NOT NULL, grad42iddocumento int NOT NULL, grad42id int NOT NULL DEFAULT 0, grad42idorigen int NOT NULL DEFAULT 0, grad42idarchivo int NOT NULL DEFAULT 0, grad42fechadoc int NOT NULL DEFAULT 0, grad42idaprueba int NOT NULL DEFAULT 0, grad42fechaaprueba int NOT NULL DEFAULT 0)";}
	if ($dbversion==6093){$sSQL="ALTER TABLE grad42docgrado ADD PRIMARY KEY(grad42id)";}
	if ($dbversion==6094){$sSQL=$objDB->sSQLCrearIndice('grad42docgrado', 'grad42docgrado_id', 'grad42idpostulacion, grad42iddocumento', true);}
	if ($dbversion==6095){$sSQL=$objDB->sSQLCrearIndice('grad42docgrado', 'grad42docgrado_padre', 'grad42idpostulacion');}
	if ($dbversion==6096){$sSQL="agregamodulo|2742|27|Postulados - Anexos|1|2|3|4|5|6|8";}
	if ($dbversion==6097){$sSQL="CREATE TABLE grad43anotacion (grad43idpostulacion int NOT NULL, grad43consec int NOT NULL, grad43id int NOT NULL DEFAULT 0, grad43anotacion Text NULL, grad43idusuario int NOT NULL DEFAULT 0, grad43fecha int NOT NULL DEFAULT 0, grad43hora int NOT NULL DEFAULT 0, grad43minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==6098){$sSQL="ALTER TABLE grad43anotacion ADD PRIMARY KEY(grad43id)";}
	if ($dbversion==6099){$sSQL=$objDB->sSQLCrearIndice('grad43anotacion', 'grad43anotacion_id', 'grad43idpostulacion, grad43consec', true);}
	if ($dbversion==6100){$sSQL=$objDB->sSQLCrearIndice('grad43anotacion', 'grad43anotacion_padre', 'grad43idpostulacion');}
}
if (($dbversion>6100)&&($dbversion<6201)){
	if ($dbversion==6101){$sSQL="agregamodulo|2743|27|Postulados - Anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==6102){$sSQL="CREATE TABLE grad44cambioest (grad44idpostulacion int NOT NULL, grad44consec int NOT NULL, grad44id int NOT NULL DEFAULT 0, grad44idestadoorigen int NOT NULL DEFAULT 0, grad44idestadofin int NOT NULL DEFAULT 0, grad44nota Text NULL, grad44idusuario int NOT NULL DEFAULT 0, grad44fecha int NOT NULL DEFAULT 0)";}
	if ($dbversion==6103){$sSQL="ALTER TABLE grad44cambioest ADD PRIMARY KEY(grad44id)";}
	if ($dbversion==6104){$sSQL=$objDB->sSQLCrearIndice('grad44cambioest', 'grad44cambioest_id', 'grad44idpostulacion, grad44consec', true);}
	if ($dbversion==6105){$sSQL=$objDB->sSQLCrearIndice('grad44cambioest', 'grad44cambioest_padre', 'grad44idpostulacion');}
	if ($dbversion==6106){$sSQL="CREATE TABLE grad45estadosolgrad (grad45id int NOT NULL, grad45nombre varchar(50) NULL)";}
	if ($dbversion==6107){$sSQL="ALTER TABLE grad45estadosolgrad ADD PRIMARY KEY(grad45id)";}
	if ($dbversion==6108){$sSQL="INSERT INTO grad45estadosolgrad(grad45id, grad45nombre) VALUES (0,'Borrador'), (3,'Devuelta'), (5,'Radicada'), (8,'Abandonada'), (11,'Para Recibo'), (21,'Verificación Final'), (31,'Aprobado para Grado'), (41,'Diploma entregado')";}
	if ($dbversion==6109){$sSQL="add_campos|plan01unidadgest|plan01idconvenio int NOT NULL DEFAULT 0";}
	if ($dbversion==6110){$sSQL=$objDB->sSQLCrearIndice('fact02factura', 'fact02factura_numres', 'fact02numresolucion');}
	if ($dbversion==6111){$sSQL=$objDB->sSQLCrearIndice('fact02factura', 'fact02factura_proceso', 'fact02proceso');}
	if ($dbversion==6112){$sSQL=$objDB->sSQLCrearIndice('fact02factura', 'fact02factura_fecha', 'fact02fecha');}
	if ($dbversion==6113){$sSQL="add_campos|core50convenios|core50manejacosto int NOT NULL DEFAULT 0";}
	if ($dbversion==6114){$sSQL=$objDB->sSQLEliminarIndice('core48programazona', 'core48programazona_id');}
	if ($dbversion==6115){$sSQL=$objDB->sSQLCrearIndice('core48programazona', 'core48programazona_id', 'core48idprograma, core48idplanest, core48idzona, core48idcentro, core48fechainicial');}
	if ($dbversion==6116){$sSQL="add_campos|core01estprograma|core01programa_resol int NOT NULL DEFAULT 0";}
	//6117 a 6121 quedan libres.
	if ($dbversion==6122){$sSQL="add_campos|grad41postulaciones|grad41cerem_modalidad int NOT NULL DEFAULT 0|grad41cerem_centro int NOT NULL DEFAULT 0|grad41libro_num int NOT NULL DEFAULT 0|grad41libro_folio int NOT NULL DEFAULT 0|grad41libro_acta int NOT NULL DEFAULT 0";}
	if ($dbversion==6123){$sSQL="ALTER TABLE comp02solprod CHANGE comp02cantaprob comp02cantaprob Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==6124){$sSQL="add_campos|comp72estadosol|comp72orden int NOT NULL DEFAULT 0|comp72ordenrpt int NOT NULL DEFAULT 0";}
	if ($dbversion==6125){$sSQL="DELETE FROM comp72estadosol";}
	if ($dbversion==6126){$sSQL="INSERT INTO comp72estadosol (comp72id, comp72nombre, comp72orden, comp72ordenrpt) VALUES (0, 'Borrador', 0, 0), (3, 'Devuelta', 0, 0), (7, 'Radicada', 7, 11), (11, 'Aceptada', 11, 1), (12, 'Aplazada', 7, 11), (99, 'Descartada', 11, 2)";}
	if ($dbversion==6127){$sSQL=$objDB->sSQLCrearIndice('comp01solicitud', 'comp01solicitud_estado', 'comp01estado');}
	if ($dbversion==6128){$sSQL=$objDB->sSQLCrearIndice('comp01solicitud', 'comp01solicitud_solicita', 'comp01idunidadsol');}
	if ($dbversion==6129){$sSQL=$objDB->sSQLCrearIndice('comp01solicitud', 'comp01solicitud_compra', 'comp01idunidadfunc');}
	if ($dbversion==6130){$sSQL="add_campos|core01estprograma|core01estrat_blear int NOT NULL DEFAULT 0|core01estrat_cipas int NOT NULL DEFAULT 0";}
	if ($dbversion==6131){$sSQL="add_campos|olab08oferta|olab08procesaanalitica int NOT NULL DEFAULT 0";}
	if ($dbversion==6132){$sSQL="add_campos|cipa01oferta|cipa01procesaanalitica int NOT NULL DEFAULT 0";}
	if ($dbversion==6133){$sSQL="add_campos|comp06aceptacion|comp06vigenciappt int NOT NULL DEFAULT 0";}
	if ($dbversion==6134){$sSQL="add_campos|ofes02ediciones|ofes02identificador varchar(20) NULL DEFAULT ''";}
	if ($dbversion==6135){$sSQL="UPDATE ofes02ediciones SET ofes02identificador=ofes02numedicion";}
	if ($dbversion==6136){$sSQL="add_campos|comp06aceptacion|comp06idconsolida int NOT NULL DEFAULT 0";}
	if ($dbversion==6137){$sSQL="CREATE TABLE comp10consolida (comp10consec int NOT NULL, comp10id int NOT NULL DEFAULT 0, comp10estado int NOT NULL DEFAULT 0, comp10vigenciappt int NOT NULL DEFAULT 0, comp10detalle Text NULL, comp10idunidadfunc int NOT NULL DEFAULT 0, comp10idfuncionario int NOT NULL DEFAULT 0, comp10fecha int NOT NULL DEFAULT 0)";}
	if ($dbversion==6138){$sSQL="ALTER TABLE comp10consolida ADD PRIMARY KEY(comp10id)";}
	if ($dbversion==6139){$sSQL=$objDB->sSQLCrearIndice('comp10consolida', 'comp10consolida_id', 'comp10consec', true);}
	if ($dbversion==6140){$sSQL="agregamodulo|3910|39|Consolidación de necesidades|1|2|3|4|5|6|8";}
	if ($dbversion==6141){$sSQL=$u09."(3910, 1, 'Consolidación de necesidades', 'compconsolida.php', 701, 3910, 'S', '', '')";}
	if ($dbversion==6142){$sSQL=$objDB->sSQLCrearIndice('comp06aceptacion', 'comp06aceptacion_padre', 'comp06idconsolida');}
	//6143 a 6146 queda libre.
	if ($dbversion==6147){$sSQL="add_campos|plan15metaactividad|plan15idunidadresp int NOT NULL DEFAULT 0|plan15idzona int NOT NULL DEFAULT 0|plan15idcentro int NOT NULL DEFAULT 0|plan15idescuela int NOT NULL DEFAULT 0|plan15idprograma int NOT NULL DEFAULT 0";}
	if ($dbversion==6148){$sSQL=$objDB->sSQLCrearIndice('plan15metaactividad', 'plan15metaactividad_unidad', 'plan15idunidadresp');}
	if ($dbversion==6149){$sSQL=$objDB->sSQLCrearIndice('plan15metaactividad', 'plan15metaactividad_zona', 'plan15idzona');}
	if ($dbversion==6150){$sSQL=$objDB->sSQLCrearIndice('plan15metaactividad', 'plan15metaactividad_centro', 'plan15idcentro');}
	if ($dbversion==6151){$sSQL=$objDB->sSQLCrearIndice('plan15metaactividad', 'plan15metaactividad_escuela', 'plan15idescuela');}
	if ($dbversion==6152){$sSQL=$objDB->sSQLCrearIndice('plan15metaactividad', 'plan15metaactividad_programa', 'plan15idprograma');}
	if ($dbversion==6153){$sSQL="CREATE TABLE cara46causaproteccion (cara46consec int NOT NULL, cara46id int NOT NULL DEFAULT 0, cara46activa int NOT NULL DEFAULT 0, cara46orden int NOT NULL DEFAULT 0, cara46nombre varchar(50) NULL, cara46tipo int NOT NULL DEFAULT 0)";}
	if ($dbversion==6154){$sSQL="ALTER TABLE cara46causaproteccion ADD PRIMARY KEY(cara46id)";}
	if ($dbversion==6155){$sSQL=$objDB->sSQLCrearIndice('cara46causaproteccion', 'cara46causaproteccion_id', 'cara46consec', true);}
	if ($dbversion==6156){$sSQL="agregamodulo|2346|23|Causales de protección|1|2|3|4|5|6|8";}
	if ($dbversion==6157){$sSQL=$u09."(2346, 1, 'Causales de protección', 'caracausaprotec.php', 3, 2346, 'S', '', '')";}
	if ($dbversion==6158){$sSQL=$unad70."(2346,2347,'cara47sujetoprotegido','cara47id','cara47idcausa','El dato esta incluido en Sujetos de proteccion especial', '')";}
	if ($dbversion==6159){$sSQL="CREATE TABLE cara47sujetoprotegido (cara47idtercero int NOT NULL, cara47idcausa int NOT NULL, cara47consec int NOT NULL, cara47id int NOT NULL DEFAULT 0, cara47estado int NOT NULL DEFAULT 0, cara47fecharadicado int NOT NULL DEFAULT 0, cara47fechaini int NOT NULL DEFAULT 0, cara47fechafin int NOT NULL DEFAULT 0, cara47idorigen int NOT NULL DEFAULT 0, cara47numpersonasacargo int NOT NULL DEFAULT 0, cara47numdehijos int NOT NULL DEFAULT 0, cara47idorigencert int NOT NULL DEFAULT 0, cara47idarchivocert int NOT NULL DEFAULT 0, cara47idusuario int NOT NULL DEFAULT 0, cara47fecha int NOT NULL DEFAULT 0)";}
	if ($dbversion==6160){$sSQL="ALTER TABLE cara47sujetoprotegido ADD PRIMARY KEY(cara47id)";}
	if ($dbversion==6161){$sSQL=$objDB->sSQLCrearIndice('cara47sujetoprotegido', 'cara47sujetoprotegido_id', 'cara47idtercero, cara47idcausa, cara47consec', true);}
	if ($dbversion==6162){$sSQL="agregamodulo|2347|23|Sujetos de protección esp.|1|2|3|4|5|6|8";}
	if ($dbversion==6163){$sSQL=$u09."(2347, 1, 'Sujetos de protección especial', 'carasujetoprotegido.php', 2301, 2347, 'S', '', '')";}
	if ($dbversion==6164){$sSQL="CREATE TABLE cara48tipoproteccion (cara48id int NOT NULL, cara48nombre varchar(50) NULL, cara48version int NOT NULL DEFAULT 0)";}
	if ($dbversion==6165){$sSQL="ALTER TABLE cara48tipoproteccion ADD PRIMARY KEY(cara48id)";}
	if ($dbversion==6166){$sSQL="INSERT INTO cara48tipoproteccion(cara48id, cara48nombre, cara48version) VALUES (0,'No aplica',2023), (1,'Niños y Niñas',2023), (2,'Adolecentes',2023), (3,'Mujer cabeza de familia',2023), (5,'Madre Cabeza de Hogar',2023), (7,'LGTBIQ+',2023), (11,'Discapacidades',2023), (21,'Adulto Mayor',2023), (31,'Desplazamiento por Violencia',2023), (32,'Pobreza Extrema',2023)";}
	if ($dbversion==6167){$sSQL="add_campos|core77ies|core77departamento varchar(5) NULL DEFAULT ''|core77ciudad varchar(8) NULL DEFAULT ''|core77enfuncionamiento int NOT NULL DEFAULT 1|core77naturaleza int NOT NULL DEFAULT 0";}
	if ($dbversion==6168){$sSQL="add_campos|core78iesprograma|core78nivelformacion int NOT NULL DEFAULT 0|core78pais varchar(5) NULL DEFAULT ''|core78departamento varchar(5) NULL DEFAULT ''|core78ciudad varchar(8) NULL DEFAULT ''|core78metodologia int NOT NULL DEFAULT 0";}
	if ($dbversion==6169){$sSQL="agregamodulo|4204|42|Calcular costos por curso|1|5|6";}
	if ($dbversion==6170){$sSQL=$u09."(4204, 1, 'Calcular costos por curso', 'costcalccurso.php', 7, 4204, 'S', '', '')";}
	//6171 Queda libre
	if ($dbversion==6172){$sSQL=$objDB->sSQLCrearIndice('plan16metaprodcierre', 'plan16metaprodcierre_gestor', 'plan16idgestor');}
	if ($dbversion==6173){$sSQL=$objDB->sSQLCrearIndice('plan16metaprodcierre', 'plan16metaprodcierre_estado', 'plan16idestado');}
	if ($dbversion==6174){$sSQL=$objDB->sSQLCrearIndice('plan16metaprodcierre', 'plan16metaprodcierre_fechalim', 'plan16fechalimite');}
	if ($dbversion==6175){$sSQL="agregamodulo|2735|27|Cambio de actores|1|3|5|6|1707";}
	if ($dbversion==6176){$sSQL=$u09."(2735, 1, 'Cambio de actores', 'gradcambioactor.php', 2203, 2735, 'S', '', '')";}
	if ($dbversion==6177){$sSQL="CREATE TABLE grad36cambioactor (grad36idproyecto int NOT NULL, grad36idrol int NOT NULL, grad36consec int NOT NULL, grad36id int NOT NULL DEFAULT 0, grad36idsaliente int NOT NULL DEFAULT 0, grad36identrante int NOT NULL DEFAULT 0, grad36detalle varchar(200) NULL, grad36idusuario int NOT NULL DEFAULT 0, grad36fechacambio int NOT NULL DEFAULT 0)";}
	if ($dbversion==6178){$sSQL="ALTER TABLE grad36cambioactor ADD PRIMARY KEY(grad36id)";}
	if ($dbversion==6179){$sSQL=$objDB->sSQLCrearIndice('grad36cambioactor', 'grad36cambioactor_id', 'grad36idproyecto, grad36idrol, grad36consec', true);}
	if ($dbversion==6180){$sSQL=$objDB->sSQLCrearIndice('grad36cambioactor', 'grad36cambioactor_padre', 'grad36idproyecto');}
	if ($dbversion==6181){$sSQL="INSERT INTO grad16estadoproy(grad16id, grad16nombre) VALUE (85, 'Desistido'), (90, 'Anulado')";}
	if ($dbversion==6182){$sSQL="add_campos|plan16metaprodcierre|plan16aud_fecharep int NOT NULL DEFAULT 0|plan16aud_extempo int NOT NULL DEFAULT 0|plan16aud_castigo int NOT NULL DEFAULT 0";}
	if ($dbversion==6183){$sSQL="CREATE TABLE plan23verificacion (plan23idunidadresp int NOT NULL, plan23idcierre int NOT NULL, plan23consec int NOT NULL, plan23id int NOT NULL DEFAULT 0, plan23estado int NOT NULL DEFAULT 0, plan23fecha int NOT NULL DEFAULT 0, plan23formamuestra int NOT NULL DEFAULT 0, plan23idusuario int NOT NULL DEFAULT 0, plan23pend_metas int NOT NULL DEFAULT 0, plan23pend_produc int NOT NULL DEFAULT 0, plan23pend_actividad int NOT NULL DEFAULT 0, plan23num_act int NOT NULL DEFAULT 0, plan23num_act_atiempo int NOT NULL DEFAULT 0, plan23num_act_ext int NOT NULL DEFAULT 0, plan23num_act_devuelta int NOT NULL DEFAULT 0, plan23muestra int NOT NULL DEFAULT 0, plan23historico int NOT NULL DEFAULT 0, plan23idrevisor int NOT NULL DEFAULT 0, plan23fechadevuelto int NOT NULL DEFAULT 0, plan23fechaabandono int NOT NULL DEFAULT 0, plan23fechaaprobado int NOT NULL DEFAULT 0, plan23ind_entrega Decimal(15,2) NULL DEFAULT 0, plan23ind_error Decimal(15,2) NULL DEFAULT 0, plan23ind_eficiencia Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==6184){$sSQL="ALTER TABLE plan23verificacion ADD PRIMARY KEY(plan23id)";}
	if ($dbversion==6185){$sSQL=$objDB->sSQLCrearIndice('plan23verificacion', 'plan23verificacion_id', 'plan23idunidadresp, plan23idcierre, plan23consec', true);}
	if ($dbversion==6186){$sSQL="agregamodulo|3623|36|Verificación de cierres|1|2|3|4|5|6|12|14|1707";}
	if ($dbversion==6187){$sSQL=$u09."(3623, 1, 'Verificación de cierres', 'planverifica.php', 7, 3623, 'S', '', '')";}
	if ($dbversion==6188){$sSQL=$objDB->sSQLCrearIndice('plan23verificacion', 'plan23verificacion_estado', 'plan23estado');}
	if ($dbversion==6189){$sSQL="CREATE TABLE plan24veractiv (plan24idverifica int NOT NULL, plan24idactivcierre int NOT NULL, plan24id int NOT NULL DEFAULT 0, plan24idencargado int NOT NULL DEFAULT 0, plan24estado int NOT NULL DEFAULT 0, plan24idmetaprev int NOT NULL DEFAULT 0, plan24idproducto int NOT NULL DEFAULT 0, plan24idactividad int NOT NULL DEFAULT 0, plan24gravedaddevol int NOT NULL DEFAULT 0)";}
	if ($dbversion==6190){$sSQL="ALTER TABLE plan24veractiv ADD PRIMARY KEY(plan24id)";}
	if ($dbversion==6191){$sSQL=$objDB->sSQLCrearIndice('plan24veractiv', 'plan24veractiv_id', 'plan24idverifica, plan24idactivcierre', true);}
	if ($dbversion==6192){$sSQL=$objDB->sSQLCrearIndice('plan24veractiv', 'plan24veractiv_padre', 'plan24idverifica');}
	if ($dbversion==6193){$sSQL="agregamodulo|3624|36|Verificación cierre - Activ.|1|2|3|4|5|6";}
	if ($dbversion==6194){$sSQL="CREATE TABLE plan25verresultado (plan25idverifica int NOT NULL, plan25idactivcierre int NOT NULL, plan25consec int NOT NULL, plan25id int NOT NULL DEFAULT 0, plan25nota Text NULL, plan25usuario int NOT NULL DEFAULT 0, plan25fecha int NOT NULL DEFAULT 0, plan25hora int NOT NULL DEFAULT 0, plan25minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==6195){$sSQL="ALTER TABLE plan25verresultado ADD PRIMARY KEY(plan25id)";}
	if ($dbversion==6196){$sSQL=$objDB->sSQLCrearIndice('plan25verresultado', 'plan25verresultado_id', 'plan25idverifica, plan25idactivcierre, plan25consec', true);}
	if ($dbversion==6197){$sSQL=$objDB->sSQLCrearIndice('plan25verresultado', 'plan25verresultado_padre', 'plan25idverifica');}
	if ($dbversion==6198){$sSQL="agregamodulo|3625|36|Verificación cierre-Anotacion|1|2|3|4|5|6";}
	if ($dbversion==6199){$sSQL="CREATE TABLE plan77estadoverifica (plan77id int NOT NULL, plan77nombre varchar(50) NULL)";}
	if ($dbversion==6200){$sSQL="ALTER TABLE plan77estadoverifica ADD PRIMARY KEY(plan77id)";}
	}
if (($dbversion>6200)&&($dbversion<6301)){
	if ($dbversion==6201){$sSQL="INSERT INTO plan77estadoverifica(plan77id, plan77nombre) VALUES (0,'Borrador'), (7,'Cerrado'), (8,'Devuelto'), (10,'Correcciones Aplicadas'), (13,'Abandonado'), (17,'Aprobado')";}
	if ($dbversion==6202){$sSQL="CREATE TABLE corf42contenidos (corf42idiesprog int NOT NULL, corf42consec int NOT NULL, corf42id int NOT NULL DEFAULT 0, corf42codcurso varchar(50) NULL, corf42nombre varchar(200) NULL, corf42notaaproba Decimal(15,2) NULL DEFAULT 0, corf42notamaxima Decimal(15,2) NULL DEFAULT 0, corf42dborigenca int NOT NULL DEFAULT 0, corf42idca int NOT NULL DEFAULT 0)";}
	if ($dbversion==6203){$sSQL="ALTER TABLE corf42contenidos ADD PRIMARY KEY(corf42id)";}
	if ($dbversion==6204){$sSQL=$objDB->sSQLCrearIndice('corf42contenidos', 'corf42contenidos_id', 'corf42idiesprog, corf42consec', true);}
	if ($dbversion==6205){$sSQL=$objDB->sSQLCrearIndice('corf42contenidos', 'corf42contenidos_padre', 'corf42idiesprog');}
	if ($dbversion==6206){$sSQL="add_campos|unae26unidadesfun|unae26idadministrador int NOT NULL DEFAULT 0";}
	if ($dbversion==6207){$sSQL="agregamodulo|3678|36|Unidades funcionales [226]|1";}
	if ($dbversion==6208){$sSQL=$u09."(3678, 1, 'Unidades funcionales', 'unadunidadf.php', 1, 3678, 'S', '', '')";}
	if ($dbversion==6209){$sSQL="add_campos|ppto08soldisponib|ppt08idmetaprev int NOT NULL DEFAULT 0|ppt08idescuela int NOT NULL DEFAULT 0|ppt08idprograma int NOT NULL DEFAULT 0|ppt08idzona int NOT NULL DEFAULT 0|ppt08idcentro int NOT NULL DEFAULT 0";}
	if ($dbversion==6210){$sSQL="agregamodulo|2744|27|Cohortes - Centros ceremonia|1|2|3|4|5|6|8";}
	if ($dbversion==6211){$sSQL="add_campos|grad41postulaciones|grad41idescuela int NOT NULL DEFAULT 0|grad41idzona int NOT NULL DEFAULT 0|grad41lugardiploma int NOT NULL DEFAULT 0|grad41enc_tipo int NOT NULL DEFAULT 0|grad41enc_idregistro int NOT NULL DEFAULT 0";}
	if ($dbversion==6212){$sSQL="CREATE TABLE gafi00config (gafi00id int NOT NULL, gafi00perfil_unidad_jefe int NOT NULL DEFAULT 0, gafi00perfil_unidad_admin int NOT NULL DEFAULT 0, gafi00perfil_unidad_fractal int NOT NULL DEFAULT 0)";}
	if ($dbversion==6213){$sSQL="ALTER TABLE gafi00config ADD PRIMARY KEY(gafi00id)";}
	if ($dbversion==6214){$sSQL="agregamodulo|4600|46|Parámetros|1|3";}
	if ($dbversion==6215){$sSQL=$u09."(4600, 1, 'Parámetros', 'gafiparams.php', 2, 4600, 'S', '', '')";}
	if ($dbversion==6216){$sSQL="INSERT INTO gafi00config (gafi00id, gafi00perfil_unidad_jefe, gafi00perfil_unidad_admin, gafi00perfil_unidad_fractal) VALUES (1, 0, 0, 0)";}
	if ($dbversion==6217){$sSQL="agregamodulo|3626|36|Revisión por zona|1|6|12|1707|1710";}
	if ($dbversion==6218){$sSQL=$u09."(3626, 1, 'Revisión por zona', 'planrevzona.php', 3601, 3626, 'S', '', '')";}
	if ($dbversion==6219){$sSQL="agregamodulo|3627|36|Revisión por escuela|1|6|12|1707|1701";}
	if ($dbversion==6220){$sSQL=$u09."(3627, 1, 'Revisión por escuela', 'planrevescuela.php', 3601, 3627, 'S', '', '')";}
	if ($dbversion==6221){$sSQL="CREATE TABLE grad46cohortecentro (grad46idcohorte int NOT NULL, grad46idcentro int NOT NULL, grad46id int NOT NULL DEFAULT 0, grad46vigente int NOT NULL DEFAULT 0, grad46detalle Text NULL)";}
	if ($dbversion==6222){$sSQL="ALTER TABLE grad46cohortecentro ADD PRIMARY KEY(grad46id)";}
	if ($dbversion==6223){$sSQL=$objDB->sSQLCrearIndice('grad46cohortecentro', 'grad46cohortecentro_id', 'grad46idcohorte, grad46idcentro', true);}
	if ($dbversion==6224){$sSQL=$objDB->sSQLCrearIndice('grad46cohortecentro', 'grad46cohortecentro_padre', 'grad46idcohorte');}
	if ($dbversion==6225){$sSQL="agregamodulo|2746|27|Cohortes - Centros ceremonia|2|3|4|5|6";}
	if ($dbversion==6226){$sSQL="DROP TABLE grad44cohortecentro";}
	if ($dbversion==6227){$sSQL="add_campos|prac12rutaestudiante|prac12bloqueado int NOT NULL DEFAULT 0";}
	if ($dbversion==6228){$sSQL="add_campos|core65clasehomologa|core65visible int NOT NULL DEFAULT 0";}
	if ($dbversion==6229){$sSQL="UPDATE core65clasehomologa SET core65visible=1 WHERE core65id<>2";}
	if ($dbversion==6230){$sSQL="DROP TABLE comp11consolprod";}
	if ($dbversion==6231){$sSQL="CREATE TABLE comp11consolprod (comp11idconsolida int NOT NULL, comp11idproducto int NOT NULL, comp11zona int NOT NULL, comp11centro int NOT NULL, comp11escuela int NOT NULL, comp11programa int NOT NULL, comp11id int NOT NULL DEFAULT 0, comp11cantidad Decimal(15,2) NULL DEFAULT 0, comp11vigenciappt int NOT NULL DEFAULT 0)";}
	if ($dbversion==6232){$sSQL="ALTER TABLE comp11consolprod ADD PRIMARY KEY(comp11id)";}
	if ($dbversion==6233){$sSQL=$objDB->sSQLCrearIndice('comp11consolprod', 'comp11consolprod_id', 'comp11idconsolida, comp11idproducto, comp11zona, comp11centro, comp11escuela, comp11programa', true);}
	if ($dbversion==6234){$sSQL=$objDB->sSQLCrearIndice('comp11consolprod', 'comp11consolprod_padre', 'comp11idconsolida');}
	if ($dbversion==6235){$sSQL=$objDB->sSQLCrearIndice('comp01solicitud', 'comp01solicitud_acepta', 'comp01idacepta');}
	if ($dbversion==6236){$sSQL="add_campos|hera71cpc|hera71idgrupo int NOT NULL DEFAULT 0";}
	if ($dbversion==6237){$sSQL="agregamodulo|4371|40|CPC|1|2|3|4|5|6|8";}
	if ($dbversion==6238){$sSQL=$u09."(4371, 1, 'CPC', 'heracpc.php', 1, 4371, 'S', '', '')";}
	if ($dbversion==6239){$sSQL="agregamodulo|4372|40|Unidades de medida|1|2|3|4|5|6|8";}
	if ($dbversion==6240){$sSQL=$u09."(4372, 1, 'Unidades de medida', 'heraunidadmedida.php', 2, 4372, 'S', '', '')";}
	if ($dbversion==6241){$sSQL="CREATE TABLE herb73cpcgrupo (herb73version int NOT NULL, herb73grupo varchar(4) NOT NULL, herb73id int NOT NULL DEFAULT 0, herb73nombre varchar(250) NULL)";}
	if ($dbversion==6242){$sSQL="ALTER TABLE herb73cpcgrupo ADD PRIMARY KEY(herb73id)";}
	if ($dbversion==6243){$sSQL=$objDB->sSQLCrearIndice('herb73cpcgrupo', 'herb73cpcgrupo_id', 'herb73version, herb73grupo', true);}
	if ($dbversion==6244){$sSQL="agregamodulo|4373|40|Grupos CPC|1|2|3|4|5|6|8";}
	if ($dbversion==6245){$sSQL=$u09."(4373, 1, 'Grupos CPC', 'heracpcgrupo.php', 2, 4373, 'S', '', '')";}
	if ($dbversion==6246){$sSQL="INSERT INTO herb73cpcgrupo (herb73version, herb73grupo, herb73id, herb73nombre) VALUES (0, '', 0, '{Ninguno}')";}
	if ($dbversion==6247){$sSQL="agregamodulo|3912|39|CPC [4371]|1";}
	if ($dbversion==6248){$sSQL=$u09."(3912, 1, 'CPC', 'heracpc.php', 1, 3912, 'S', '', '')";}
	if ($dbversion==6249){$sSQL="add_campos|fact14producto|fact14idcpcgrupo int NOT NULL DEFAULT 0|fact14idcpc int NOT NULL DEFAULT 0";}
	if ($dbversion==6250){$sSQL="CREATE TABLE herb70cpcversion (herb70id int NOT NULL, herb70nombre varchar(50) NULL, herb70vigente int NOT NULL DEFAULT 0)";}
	if ($dbversion==6251){$sSQL="ALTER TABLE herb70cpcversion ADD PRIMARY KEY(herb70id)";}
	if ($dbversion==6252){$sSQL="INSERT INTO herb70cpcversion (herb70id, herb70nombre, herb70vigente) VALUES (3, '2.1', 1)";}
	if ($dbversion==6253){$sSQL=$unad70."(714,3902,'comp02solprod','comp02id','comp02idproducto','El producto esta incluido en solicitudes de compra', '')";}
	if ($dbversion==6254){$sSQL=$objDB->sSQLCrearIndice('fact14producto', 'fact14producto_tipoprod', 'fact14tipoproducto');}
	if ($dbversion==6255){$sSQL=$objDB->sSQLCrearIndice('fact14producto', 'fact14producto_categaria', 'fact14idcategoria');}
	if ($dbversion==6256){$sSQL="CREATE TABLE cttc11tareas (cttc11idevento int NOT NULL, cttc11consec int NOT NULL, cttc11id int NOT NULL DEFAULT 0, cttc11nombre varchar(250) NULL, cttc11activo int NOT NULL DEFAULT 0, cttc11anexo int NOT NULL DEFAULT 0, cttc11observaciones int NOT NULL DEFAULT 0, cttc11aprobacion int NOT NULL DEFAULT 0, cttc11version int NOT NULL DEFAULT 0)";}
	if ($dbversion==6257){$sSQL="ALTER TABLE cttc11tareas ADD PRIMARY KEY(cttc11id)";}
	if ($dbversion==6258){$sSQL=$objDB->sSQLCrearIndice('cttc11tareas', 'cttc11tareas_id', 'cttc11idevento, cttc11consec', true);}
	if ($dbversion==6259){$sSQL=$objDB->sSQLCrearIndice('cttc11tareas', 'cttc11tareas_padre', 'cttc11idevento');}
	if ($dbversion==6260){$sSQL="CREATE TABLE cttc10tipotareas (cttc10idtipoproceso int NOT NULL, cttc10ideventoproc int NOT NULL, cttc10idtarea int NOT NULL, cttc10id int NULL DEFAULT 0, cttc10activo int NULL DEFAULT 0)";}
	if ($dbversion==6261){$sSQL="ALTER TABLE cttc10tipotareas ADD PRIMARY KEY(cttc10id)";}
	if ($dbversion==6262){$sSQL="ALTER TABLE cttc10tipotareas ADD UNIQUE INDEX cttc10tipotareas_id(cttc10idtipoproceso, cttc10ideventoproc, cttc10idtarea)";}
	if ($dbversion==6263){$sSQL="ALTER TABLE cttc10tipotareas ADD INDEX cttc10tipotareas_padre(cttc10idtipoproceso)";}
	if ($dbversion==6264){$sSQL="agregamodulo|4110|41|Tareas|1|2|3|4|5|6|8";}
	if ($dbversion==6265){$sSQL="add_campos|unad10vigencia|unad10financiera int NOT NULL DEFAULT 0|unad10moneda int NOT NULL DEFAULT 0|unad10vruvt Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==6266){$sSQL="CREATE TABLE nico05doccontable (nico05agno int NOT NULL, nico05tipodoc int NOT NULL, 
	nico05consec int NOT NULL, nico05id int NULL DEFAULT 0, nico05fecha varchar(10) NULL, nico05concepto varchar(250), 
	nico05detalle Text NULL, nico05cerrado varchar(1) NULL, nico05idusuario int NULL DEFAULT 0, nico05idsede int NULL DEFAULT 0, 
	nico05idsistema int NULL DEFAULT 0, nico05anulado varchar(1) NULL, nico05nombrecheque varchar(100) NULL, 
	nico05valortotal Decimal(15,2) NULL DEFAULT 0, nico05idproceso int NULL DEFAULT 0, nico05idref int NULL DEFAULT 0,
	nico05momento int NULL DEFAULT 0, nico05idia int NULL DEFAULT 0, nico05idrevisa int NULL DEFAULT 0, 
	nico05fecharevisa int NULL DEFAULT 0, nico05minutorevisa int NULL DEFAULT 0, nico05idpadre int NULL DEFAULT 0, 
	nico05bloqueado varchar(1) NULL DEFAULT 'N')";}
	if ($dbversion==6267){$sSQL="ALTER TABLE nico05doccontable ADD PRIMARY KEY(nico05id)";}
    if ($dbversion==6268){$sSQL="ALTER TABLE nico05doccontable ADD UNIQUE INDEX nico05doccontable_id (nico05agno, nico05tipodoc, nico05consec)";}
	if ($dbversion==6269){$sSQL="ALTER TABLE nico05doccontable ADD INDEX nico05doccontable_idia(nico05idia)";}
	if ($dbversion==6270){$sSQL="ALTER TABLE nico05doccontable ADD INDEX nico05doccontable_agno(nico05agno, nico05idia, nico05cerrado, nico05anulado)";}
	if ($dbversion==6271){$sSQL="agregamodulo|1105|11|Documentos contables|1|2|3|4|5|6|7|8|9|16|17|1707";}
	if ($dbversion==6272){$sSQL=$u09."(1105, 1, 'Documentos contables', 'nicodocumento.php', 1101, 1105, 'S', '', '')";}
	if ($dbversion==6273){$sSQL="add_campos|ppto08soldisponib|ppt08idsolcompra int NOT NULL DEFAULT 0";}
	if ($dbversion==6274){$sSQL="CREATE TABLE gafi14monedas (gafi14consec int NOT NULL, gafi14id int NOT NULL DEFAULT 0, gafi14nombre varchar(100) NULL, gafi14sigla varchar(10) NULL, gafi14valorvariable varchar(1) NULL, gafi14vrenpesos Decimal(15,10) NULL DEFAULT 0)";}
	if ($dbversion==6275){$sSQL="ALTER TABLE gafi14monedas ADD PRIMARY KEY(gafi14id)";}
	if ($dbversion==6276){$sSQL=$objDB->sSQLCrearIndice('gafi14monedas', 'gafi14monedas_id', 'gafi14consec', true);}
	if ($dbversion==6277){$sSQL="agregamodulo|4614|46|Monedas|1|2|3|4|5|6|8";}
	if ($dbversion==6278){$sSQL=$u09."(4614, 1, 'Monedas', 'gafmoneda.php', 2, 4614, 'S', '', '')";}
	if ($dbversion==6279){$sSQL="CREATE TABLE gafi15monedatasas (gafi15idmoneda int NOT NULL, gafi15fecha int NOT NULL, gafi15id int NOT NULL DEFAULT 0, gafi15tasa Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==6280){$sSQL="ALTER TABLE gafi15monedatasas ADD PRIMARY KEY(gafi15id)";}
	if ($dbversion==6281){$sSQL=$objDB->sSQLCrearIndice('gafi15monedatasas', 'gafi15monedatasas_id', 'gafi15idmoneda, gafi15fecha', true);}
	if ($dbversion==6282){$sSQL=$objDB->sSQLCrearIndice('gafi15monedatasas', 'gafi15monedatasas_padre', 'gafi15idmoneda');}
	if ($dbversion==6283){$sSQL="agregamodulo|4615|46|Monedas - Tasas de cambio|1|2|3|4|5|6|8";}
	if ($dbversion==6284){$sSQL=$u09."(4615, 1, 'Tasas de cambio', 'chemonedatasa.php', 6, 4615, 'S', '', '')";}
	if ($dbversion==6285){$sSQL="INSERT INTO gafi14monedas (gafi14consec, gafi14id, gafi14nombre, gafi14sigla, gafi14valorvariable, gafi14vrenpesos) VALUES (0, 0, 'Peso Colombiano', 'COP', 'N', 1)";}
	if ($dbversion==6286){$sSQL="agregamodulo|3914|39|Necesidades consolidadas|1|5|6";}
	if ($dbversion==6287){$sSQL=$u09."(3914, 1, 'Necesidades consolidadas', 'comprptconsolidado.php', 11, 3914, 'S', '', '')";}
	if ($dbversion==6288){$sSQL=$objDB->sSQLCrearIndice('ofer18agendaoferta', 'ofer18agendaoferta_periodo', 'ofer18per_aca');}
	if ($dbversion==6289){$sSQL="UPDATE saiu46tipotramite SET saiu46nombre='Saldos a Aplicar' WHERE saiu46id=1";}
	if ($dbversion==6290){$sSQL=$u08."(2903, 'Exógena', 'gm.php?id=2903', 'Exógena', 'Exogenous', 'Exógena')";}
	if ($dbversion==6291){$sSQL="CREATE TABLE gthu51hojadevida (gthu51itercero int NOT NULL, gthu51id int NULL DEFAULT 0, gthu51ubicacionestante int NULL DEFAULT 0, gthu51ubicacioncaja int NULL DEFAULT 0, gthu51condicion int NULL DEFAULT 0, gthu51idperfilactual int NULL DEFAULT 0, gthu51numrespension int NULL DEFAULT 0, gthu51fecharecepcion varchar(10) NULL, gthu51discapacitado varchar(1) NULL, gthu51cabezadefamilia varchar(1) NULL, gthu51fechamod varchar(10) NULL, gthu51observacion Text NULL, gthu51tipopersona varchar(1) NULL, gthu51idreplegal int NULL DEFAULT 0, gthu51tiporegimen varchar(3) NULL, gthu51naturaleza varchar(1) NULL, gthu51primaespecial varchar(1) NULL DEFAULT 'N')";}
	if ($dbversion==6292){$sSQL="ALTER TABLE gthu51hojadevida ADD PRIMARY KEY(gthu51id)";}
	if ($dbversion==6293){$sSQL=$objDB->sSQLCrearIndice('gthu51hojadevida', 'gthu51hojadevida_id', 'gthu51itercero', true);}
	if ($dbversion==6294){$sSQL="CREATE TABLE gthu52hvcondicion (gthu52id int NOT NULL, gthu52nombre varchar(50) NULL)";}
	if ($dbversion==6295){$sSQL="ALTER TABLE gthu52hvcondicion ADD PRIMARY KEY(gthu52id)";}
	if ($dbversion==6296){$sSQL="INSERT INTO gthu52hvcondicion (gthu52id, gthu52nombre) VALUES (0, 'Aspirante'), (1, 'Funcionario'), (2, 'Contratista'), (3, 'Funcionario Provisional'), (8, 'Docente de carrera'), (9, 'Docente ocasional'), (100, 'Pensionado')";}
	if ($dbversion==6297){$sSQL="CREATE TABLE gthu54hvlaboral (gthu54idtercero int NOT NULL, gthu54ifechaingreso int NOT NULL, gthu54id int NULL DEFAULT 0, gthu54funcionario int NULL DEFAULT 0, gthu54idempleador int NULL DEFAULT 0, gthu54idcargo int NULL DEFAULT 0, gthu54iddepedencia int NULL DEFAULT 0, gthu54fechaini varchar(10) NULL, gthu54fechafin varchar(10) NULL, gthu54dborigen int NULL DEFAULT 0, gthu54idarchivo int NULL DEFAULT 0, gthu54idliquidacion int NULL DEFAULT 0, gthu54detcargo Text NULL, gthu54detdependencia Text NULL, gthu54idgrupoliquida int NULL DEFAULT 0, gthu54tipocontrato int NULL DEFAULT 0, gthu54asginabasica Decimal(15,2) NULL DEFAULT 0, gthu54fechavacaciones varchar(10) NULL DEFAULT '00/00/0000', gthu54cicloliquida int NULL DEFAULT 0, gthu54idnovingreso int NULL DEFAULT 0, gthu54idnovsalida int NULL DEFAULT 0, gthu54idnivelriezgo int NULL DEFAULT 0, gthu54vrprimatec Decimal(15,2) NULL DEFAULT 0, gthu54fechabonianual int NULL DEFAULT 0, gthu54fechainiasigna int NULL DEFAULT 0, gthu54condicion int NULL DEFAULT 0, gthu54idescuela int NULL DEFAULT 0, gthu54ifechafin int NULL DEFAULT 0)";}
	if ($dbversion==6298){$sSQL="ALTER TABLE gthu54hvlaboral ADD PRIMARY KEY(gthu54id)";}
	if ($dbversion==6299){$sSQL=$objDB->sSQLCrearIndice('gthu54hvlaboral', 'gthu54hvlaboral_id', 'gthu54idtercero, gthu54ifechaingreso', true);}
	if ($dbversion==6300){$sSQL=$objDB->sSQLCrearIndice('gthu54hvlaboral', 'gthu54hvlaboral_padre', 'gthu54idtercero');}
}
if (($dbversion>6300)&&($dbversion<6401)){
	if ($dbversion==6301){$sSQL="CREATE TABLE gthv01cargo (gthv01codcargo int NOT NULL, gthv01id int NULL DEFAULT 0, gthv01nombre varchar(50) NULL, gthv01activo varchar(1) NULL, gthv01nivel varchar(20) NULL, gthv01grado varchar(20) NULL, gthv01porturnos varchar(1) NULL, gthv01campamento varchar(1) NULL, gthv01idcargojefe int NULL DEFAULT 0, gthv01idnivel int NULL DEFAULT 0, gthv01coddafp varchar(10) NULL DEFAULT '', gthv01id_th int NULL DEFAULT 0)";}
	if ($dbversion==6302){$sSQL="ALTER TABLE gthv01cargo ADD PRIMARY KEY(gthv01codcargo)";}
	if ($dbversion==6303){$sSQL="agregamodulo|3251|32|Hoja de vida|1|2|3|4|5|6|8";}
	if ($dbversion==6304){$sSQL=$u09."(3251, 1, 'HV - Datos generales', 'gthuhojavida.php', 3202, 3251, 'S', '', '')";}
	if ($dbversion==6305){$sSQL="agregamodulo|3254|32|HV - Experiencia laboral|1|2|3|4|5|6|8";}
	if ($dbversion==6306){$sSQL="agregamodulo|4201|32|Cargos|1|2|3|4|5|6|8";}
	if ($dbversion==6307){$sSQL=$u09."(4201, 1, 'Cargos', 'gthucargo.php', 2, 4201, 'S', '', '')";}
	if ($dbversion==6308){$sSQL=$u08."(3202, 'Personal', 'gm.php?id=3202', 'Personal', 'Workforce', 'Pessoal')";}
	if ($dbversion==6309){$sSQL="INSERT INTO gthv01cargo (gthv01codcargo, gthv01id, gthv01nombre, gthv01activo, gthv01nivel, gthv01grado, gthv01porturnos, gthv01campamento, gthv01idcargojefe, gthv01idnivel, gthv01coddafp, gthv01id_th) VALUES (0, 0, '{Ninguno}', 'N', '', '', 'N', 'N', 0, 0, '', 0)";}
	if ($dbversion==6310){$sSQL=$objDB->sSQLCrearIndice('gthu54hvlaboral', 'gthu54hvlaboral_escuela', 'gthu54idescuela');}
	if ($dbversion==6311){$sSQL=$objDB->sSQLCrearIndice('gthu54hvlaboral', 'gthu54hvlaboral_fechafin', 'gthu54ifechafin');}
	if ($dbversion==6312){$sSQL="CREATE TABLE cttc01expedientes (cttc01consec int NOT NULL, cttc01id int NOT NULL DEFAULT 0, cttc01titulo varchar(250) NULL, cttc01detalle Text NULL, cttc01fecha int NOT NULL DEFAULT 0, cttc01estado int NOT NULL DEFAULT 0, cttc01idsede int NOT NULL DEFAULT 0, cttc01idoficina int NOT NULL DEFAULT 0, cttc01idunidadfuncional int NOT NULL DEFAULT 0, cttc01idfractal int NOT NULL DEFAULT 0, cttc01idmacropoyecto int NOT NULL DEFAULT 0, cttc01idconvenio int NOT NULL DEFAULT 0, cttc01idsolicitante int NOT NULL DEFAULT 0)";}
	if ($dbversion==6313){$sSQL="ALTER TABLE cttc01expedientes ADD PRIMARY KEY(cttc01id)";}
	if ($dbversion==6314){$sSQL=$objDB->sSQLCrearIndice('cttc01expedientes', 'cttc01expedientes_id', 'cttc01consec', true);}
	if ($dbversion==6315){$sSQL="agregamodulo|4101|41|Expedientes|1|2|3|4|5|6|8";}
	if ($dbversion==6316){$sSQL=$u09."(4101, 1, 'Expedientes', 'cttcexpediente.php', 4101, 4101, 'S', '', '')";}
	if ($dbversion==6317){$sSQL=$u01."(41, 'CONTRATACION', 'Sistema de Contratación', 'N', 'S', 1, 0, 0)";}
	if ($dbversion==6318){$sSQL=$u08."(4101, 'Contratación', 'gm.php?id=4101', 'Contratación', 'Contracts', 'contratação')";}
	if ($dbversion==6319){$sSQL="CREATE TABLE cttc02tipoproceso (cttc02consec int NOT NULL, cttc02id int NOT NULL DEFAULT 0, cttc02activo int NOT NULL DEFAULT 0, cttc02orden int NOT NULL DEFAULT 0, cttc02nombre varchar(100) NULL)";}
	if ($dbversion==6320){$sSQL="ALTER TABLE cttc02tipoproceso ADD PRIMARY KEY(cttc02id)";}
	if ($dbversion==6321){$sSQL=$objDB->sSQLCrearIndice('cttc02tipoproceso', 'cttc02tipoproceso_id', 'cttc02consec', true);}
	if ($dbversion==6322){$sSQL="agregamodulo|4102|41|Tipos de contratos|1|2|3|4|5|6|8";}
	if ($dbversion==6323){$sSQL=$u09."(4102, 1, 'Tipos de contratos', 'cttctipoproceso.php', 2, 4102, 'S', '', '')";}
	if ($dbversion==6324){$sSQL="CREATE TABLE cttc03tipoprocevento (cttc03idtipoproceso int NOT NULL, cttc03idevento int NOT NULL, cttc03id int NOT NULL DEFAULT 0, cttc03momento int NOT NULL DEFAULT 0, cttc03orden int NOT NULL DEFAULT 0, cttc03forma int NOT NULL DEFAULT 0, cttc03concomitante int NOT NULL DEFAULT 0, cttc03obligatoria int NOT NULL DEFAULT 0, cttc03temporiza int NOT NULL DEFAULT 0, cttc03dias int NOT NULL DEFAULT 0)";}
	if ($dbversion==6325){$sSQL="ALTER TABLE cttc03tipoprocevento ADD PRIMARY KEY(cttc03id)";}
	if ($dbversion==6326){$sSQL=$objDB->sSQLCrearIndice('cttc03tipoprocevento', 'cttc03tipoprocevento_id', 'cttc03idtipoproceso, cttc03idevento', true);}
	if ($dbversion==6327){$sSQL=$objDB->sSQLCrearIndice('cttc03tipoprocevento', 'cttc03tipoprocevento_padre', 'cttc03idtipoproceso');}
	if ($dbversion==6328){$sSQL="agregamodulo|4103|41|Tipo proceso - Eventos|1|2|3|4|5|6|8";}
	if ($dbversion==6329){$sSQL="CREATE TABLE cttc04tipovalores (cttc04idtipoproceso int NOT NULL, cttc04idvigencia int NOT NULL, cttc04id int NOT NULL DEFAULT 0, cttc04forma int NOT NULL DEFAULT 0, cttc04dvr_base Decimal(15,2) NULL DEFAULT 0, cttc04dvr_tope Decimal(15,2) NULL DEFAULT 0, cttc04smmlv_base Decimal(15,2) NULL DEFAULT 0, cttc04smmlv_tope Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==6330){$sSQL="ALTER TABLE cttc04tipovalores ADD PRIMARY KEY(cttc04id)";}
	if ($dbversion==6331){$sSQL=$objDB->sSQLCrearIndice('cttc04tipovalores', 'cttc04tipovalores_id', 'cttc04idtipoproceso, cttc04idvigencia', true);}
	if ($dbversion==6332){$sSQL=$objDB->sSQLCrearIndice('cttc04tipovalores', 'cttc04tipovalores_padre', 'cttc04idtipoproceso');}
	if ($dbversion==6333){$sSQL="agregamodulo|4104|41|Tipo proceso - Rangos|1|2|3|4|5|6|8";}
	if ($dbversion==6334){$sSQL="CREATE TABLE cttc05oficina (cttc05consec int NOT NULL, cttc05id int NOT NULL DEFAULT 0, cttc05activa int NOT NULL DEFAULT 0, cttc05nombre varchar(100) NULL, cttc05idjefe int NOT NULL DEFAULT 0, cttc05idcoordinador int NOT NULL DEFAULT 0, cttc05idunidadfun int NOT NULL DEFAULT 0)";}
	if ($dbversion==6335){$sSQL="ALTER TABLE cttc05oficina ADD PRIMARY KEY(cttc05id)";}
	if ($dbversion==6336){$sSQL=$objDB->sSQLCrearIndice('cttc05oficina', 'cttc05oficina_id', 'cttc05consec', true);}
	if ($dbversion==6337){$sSQL="agregamodulo|4105|41|Oficinas gestoras|1|2|3|4|5|6|8";}
	if ($dbversion==6338){$sSQL=$u09."(4105, 1, 'Oficinas gestoras', 'cttcoficinagest.php', 1, 4105, 'S', '', '')";}
	if ($dbversion==6339){$sSQL="CREATE TABLE cttc06unidades (cttc06idoficina int NOT NULL, cttc06consec int NOT NULL, cttc06id int NOT NULL DEFAULT 0, cttc06nombre varchar(100) NULL, cttc06idgrupotrabajo int NOT NULL DEFAULT 0, cttc06revisadocs int NOT NULL DEFAULT 0, cttc06cargadocs int NOT NULL DEFAULT 0)";}
	if ($dbversion==6340){$sSQL="ALTER TABLE cttc06unidades ADD PRIMARY KEY(cttc06id)";}
	if ($dbversion==6341){$sSQL=$objDB->sSQLCrearIndice('cttc06unidades', 'cttc06unidades_id', 'cttc06idoficina, cttc06consec', true);}
	if ($dbversion==6342){$sSQL=$objDB->sSQLCrearIndice('cttc06unidades', 'cttc06unidades_padre', 'cttc06idoficina');}
	if ($dbversion==6343){$sSQL="agregamodulo|4106|41|Oficinas gestoras - Unidades|1|2|3|4|5|6|8";}
	if ($dbversion==6344){$sSQL="CREATE TABLE cttc07proceso (cttc07consec int NOT NULL, cttc07id int NOT NULL DEFAULT 0, cttc07idtipoproceso int NOT NULL DEFAULT 0, cttc07idvigencia int NOT NULL DEFAULT 0, cttc07idexpediente int NOT NULL DEFAULT 0, cttc07ideventoactual int NOT NULL DEFAULT 0, cttc07objeto Text NULL, cttc07vrestudio Decimal(15,2) NULL DEFAULT 0, cttc07vrdisponibilidades Decimal(15,2) NULL DEFAULT 0, cttc07vrcontrato Decimal(15,2) NULL DEFAULT 0, cttc07vrordenpago Decimal(15,2) NULL DEFAULT 0, cttc07vrpago Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==6345){$sSQL="ALTER TABLE cttc07proceso ADD PRIMARY KEY(cttc07id)";}
	if ($dbversion==6346){$sSQL=$objDB->sSQLCrearIndice('cttc07proceso', 'cttc07proceso_id', 'cttc07consec', true);}
	if ($dbversion==6347){$sSQL="agregamodulo|4107|41|Procesos|1|2|3|4|5|6|8";}
	if ($dbversion==6348){$sSQL=$u09."(4107, 1, 'Procesos', 'cttcproceso.php', 4101, 4107, 'S', '', '')";}
	if ($dbversion==6349){$sSQL="CREATE TABLE cttc08procagenda (cttc08idproceso int NOT NULL, cttc08idevento int NOT NULL, cttc08serie int NOT NULL, cttc08id int NOT NULL DEFAULT 0, cttc08orden int NOT NULL DEFAULT 0, cttc08estado int NOT NULL DEFAULT 0, cttc08fechaini int NOT NULL DEFAULT 0, cttc08fechafin int NOT NULL DEFAULT 0, cttc08fechaejecuta int NOT NULL DEFAULT 0, cttc08alerta int NOT NULL DEFAULT 0)";}
	if ($dbversion==6350){$sSQL="ALTER TABLE cttc08procagenda ADD PRIMARY KEY(cttc08id)";}
	if ($dbversion==6351){$sSQL=$objDB->sSQLCrearIndice('cttc08procagenda', 'cttc08procagenda_id', 'cttc08idproceso, cttc08idevento, cttc08serie', true);}
	if ($dbversion==6352){$sSQL=$objDB->sSQLCrearIndice('cttc08procagenda', 'cttc08procagenda_padre', 'cttc08idproceso');}
	if ($dbversion==6353){$sSQL="agregamodulo|4108|41|Procesos - Agenda|1|2|3|4|5|6|8";}
	if ($dbversion==6354){$sSQL="CREATE TABLE cttc09procnota (cttc09idproceso int NOT NULL, cttc09consec int NOT NULL, cttc09id int NOT NULL DEFAULT 0, cttc09ideventoagenda int NOT NULL DEFAULT 0, cttc09visible int NOT NULL DEFAULT 0, cttc09nota Text NULL, cttc09idusuario int NOT NULL DEFAULT 0, cttc09fecha int NOT NULL DEFAULT 0, cttc09hora int NOT NULL DEFAULT 0, cttc09minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==6355){$sSQL="ALTER TABLE cttc09procnota ADD PRIMARY KEY(cttc09id)";}
	if ($dbversion==6356){$sSQL=$objDB->sSQLCrearIndice('cttc09procnota', 'cttc09procnota_id', 'cttc09idproceso, cttc09consec', true);}
	if ($dbversion==6357){$sSQL=$objDB->sSQLCrearIndice('cttc09procnota', 'cttc09procnota_padre', 'cttc09idproceso');}
	if ($dbversion==6358){$sSQL="agregamodulo|4109|41|Procesos - Anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==6359){$sSQL="CREATE TABLE cttc71estadoexp (cttc71id int NOT NULL, cttc71nombre varchar(50) NULL)";}
	if ($dbversion==6360){$sSQL="ALTER TABLE cttc71estadoexp ADD PRIMARY KEY(cttc71id)";}
	if ($dbversion==6361){$sSQL="INSERT INTO cttc71estadoexp (cttc71id, cttc71nombre) VALUES (0, 'Borrador'), (7, 'En ejecución'), (9, 'Abandonado')";}
	if ($dbversion==6362){$sSQL="CREATE TABLE cttc72momentocttc (cttc72id int NOT NULL, cttc72nombre varchar(50) NULL)";}
	if ($dbversion==6363){$sSQL="ALTER TABLE cttc72momentocttc ADD PRIMARY KEY(cttc72id)";}
	if ($dbversion==6364){$sSQL="INSERT INTO cttc72momentocttc (cttc72id, cttc72nombre) VALUES (0, 'Precontractual'), (1, 'Contractual'), (2, 'PostContractual')";}
	if ($dbversion==6365){$sSQL="CREATE TABLE cttc73eventocttc (cttc73id int NOT NULL, cttc73nombre varchar(100) NULL)";}
	if ($dbversion==6366){$sSQL="ALTER TABLE cttc73eventocttc ADD PRIMARY KEY(cttc73id)";}
	if ($dbversion==6367){$sSQL="CREATE TABLE cttc74estadoagenda (cttc74id int NOT NULL, cttc74nombre varchar(50) NULL)";}
	if ($dbversion==6368){$sSQL="ALTER TABLE cttc74estadoagenda ADD PRIMARY KEY(cttc74id)";}
	if ($dbversion==6369){$sSQL="INSERT INTO cttc74estadoagenda (cttc74id, cttc74nombre) VALUES (0, 'En cola'), (1, 'En proceso'), (7, 'Completo'), (9, 'Descartado')";}
	if ($dbversion==6370){$sSQL="INSERT INTO cttc73eventocttc (cttc73id, cttc73nombre) VALUES (0, '{Ninguno}'), (5, 'NECESIDAD'), (10, 'APROBACION INICIAL'), (15, 'EXPEDICION CDP'), (20, 'APERTURA INVITACIÓN Y PUBLICACIÓN DE LOS TÉRMINOS DE REFERNCIA '), (25, 'RESPUESTA Y ACLARACIONES A LAS OBSERVACIONES DE LOS TÉRMINOS DE REFERENCIA, ADENDAS'), (30, 'RECEPCIÓN DE OFERTAS Y CIERRE DE LA INVITACIÓN'), (35, 'SOLICITUD DE ACLARACIONES A LAS OFERTAS'), (40, 'EVALUACIONES A LAS OFERTAS'), (45, 'RESPUESTA A LAS ACLARACIONES  DE LAS OFERTAS'), (50, 'PUBLICACIÓN DEL INFORME DE EVALUACIÓN PRELIMINAR'), (55, 'RESPUESTA A LAS OBSERVACIONES DE LAS OFERTAS'), (60, 'PUBLICACIÓN INFORME DE EVALUACIÓN DE PROPUESTAS DEFINITIVO'), (65, 'ADJUDICACIÓN'), (67, 'REGISTRO DE DOCUMENTOS PREVIOS DEL CONTRATISTA'), (70, 'ELABORACIÓN MINUTA DEL CONTRATO'), (75, 'RP CONTRATO'), (80, 'REGISTRO DE  GARANTÍAS (POLIZAS)'), (85, 'LEGALIZACIÓN DEL CONTRATO'), (90, 'FIRMA DEL CONTRATO'), (92, 'OFICIO PLAZO DE EJECUCIÓN'), (95, 'DESIGNACION DE SUPERVISOR'), (120, 'PUBLICACION DEL CONTRATO'), (137, 'ACTA DE INICIO'), (150, 'ACTAS DE EJECUCION'), (180, 'ACTA DE TERMINACION'), (205, 'ACTA DE LIQUIDACIÓN'), (210, 'REGISTRO FINAL SECOP')";}
	if ($dbversion==6371){$sSQL=$u01."(11, 'CONTABILIDAD', 'Sistema de Gestión de Contabilidad', 'N', 'S', 1, 0, 0)";}
	if ($dbversion==6372){$sSQL="CREATE TABLE nico04bloqueos (nico04idarbol int NOT NULL, nico04dia int NOT NULL, nico04bloqueado int NOT NULL DEFAULT 0, nico04agno int NOT NULL DEFAULT 0, nico04mes int NOT NULL DEFAULT 0, nico04orden int NOT NULL DEFAULT 0, nico04diasem int NOT NULL DEFAULT 0)";}
	if ($dbversion==6373){$sSQL="ALTER TABLE nico04bloqueos ADD PRIMARY KEY(nico04idarbol, nico04dia)";}
	if ($dbversion==6374){$sSQL="agregamodulo|1104|11|Bloqueo de fechas|1|2|3";}
    if ($dbversion==6375){$sSQL=$u09."(1104,1,'Bloqueo de fechas','nicbloqueo.php', 1, 1604, 'S', '', '')";}
	if ($dbversion==6376){$sSQL=$objDB->sSQLCrearIndice('nico04bloqueos', 'nico04bloqueos_agno', 'nico04agno');}
	if ($dbversion==6377){$sSQL=$objDB->sSQLCrearIndice('nico04bloqueos', 'nico04bloqueos_orden', 'nico04orden');}
	if ($dbversion==6378){$sSQL="CREATE TABLE nico29cierre (nico29vigencia int NOT NULL, nico29fechacierre int NOT NULL, nico29id int NULL DEFAULT 0, nico29cerrado varchar(1) NULL, nico29idmetodo int NULL DEFAULT 0, nico29idnico int NULL DEFAULT 0, nico29detalle Text NULL, nico29idusuario int NULL DEFAULT 0)";}
	if ($dbversion==6379){$sSQL="ALTER TABLE nico29cierre ADD PRIMARY KEY(nico29id)";}
	if ($dbversion==6380){$sSQL=$objDB->sSQLCrearIndice('nico29cierre', 'nico29cierre_id', 'nico29vigencia, nico29fechacierre', true);}
	if ($dbversion==6381){$sSQL="agregamodulo|1129|11|Cierres|1|2|3|4|5|6|8|17";}
	if ($dbversion==6382){$sSQL=$u09."(1129, 1, 'Cierres', 'niccierre.php', 1101, 1629, 'S', '', '')";}
	if ($dbversion==6383){$sSQL="CREATE TABLE cttc75intervinientes (cttc75id int NOT NULL, cttc75nombre varchar(50) NULL)";}
	if ($dbversion==6384){$sSQL="ALTER TABLE cttc75intervinientes ADD PRIMARY KEY(cttc75id)";}
	if ($dbversion==6385){$sSQL="INSERT INTO cttc75intervinientes (cttc75id, cttc75nombre) VALUES (0, 'Sin definir'), (1, 'Unidad Gestora'), (5, 'Presupuesto'), (10, 'Contratación'), (15, 'Supervisor'), (20, 'Contratista')";}
	if ($dbversion==6386){$sSQL=$u08."(1101, 'Contabilidad', 'gm.php?id=1101', 'Contabilidad', 'Accounting', 'Contabilidade'), (1102, 'Tributarios', 'gm.php?p=1102' ,'Tributarios', 'Tax', 'Tributarios'), (1100, 'Libros', 'gm.php?p=1100', 'Libros', 'Books', 'Livros')";}
	if ($dbversion==6387){$sSQL="agregamodulo|4176|41|Fechas hábiles|1|2|3";}
	if ($dbversion==6388){$sSQL=$u09."(4176, 1, 'Fechas hábiles', 'cttcfechahabil.php', 2, 4176, 'S', 'Business dates', 'Datas comerciais')";}
	if ($dbversion==6389){$sSQL="CREATE TABLE inve01bodega (inve01consec int NOT NULL, inve01id int NULL DEFAULT 0, inve01nombre varchar(250) NULL, inve01activa varchar(1) NULL)";}
    if ($dbversion==6390){$sSQL="ALTER TABLE inve01bodega ADD PRIMARY KEY(inve01consec)";}
	if ($dbversion==6391){$sSQL="ALTER TABLE inve01bodega ADD inve01centrocosto int NULL DEFAULT 0, ADD inve01idprefijofact int NULL DEFAULT 0";}
	if ($dbversion==6392){$sSQL="ALTER TABLE inve01bodega ADD inve01idgrupo int NULL DEFAULT 0";}
	if ($dbversion==6393){$sSQL="ALTER TABLE inve01bodega ADD inve01idalmacenista int NULL DEFAULT 0";}
	if ($dbversion==6394){$sSQL="INSERT INTO inve01bodega (inve01consec, inve01id, inve01activa, inve01idgrupo, inve01nombre, inve01centrocosto, inve01idprefijofact, inve01idalmacenista) VALUES (0, 0, 'N', 0, '{Ninguna}', 0, 0, 0)";}
	if ($dbversion==6395){$sSQL="agregamodulo|4001|40|Bodegas|1|2|3|4";}
    if ($dbversion==6396){$sSQL=$u09."(4001,1,'Bodegas','invebodegas.php', 1, 4001, 'S', '', '')";}
	if ($dbversion==6397){$sSQL="add_campos|cttc02tipoproceso|cttc02idsecop varchar(20) NULL DEFAULT ''";}
	if ($dbversion==6398){$sSQL="INSERT INTO core65clasehomologa (core65id, core65nombre, core65visible) VALUES (13, 'Reconocimiento de Creditos a Egresados', 1)";}
	if ($dbversion==6399){$sSQL="CREATE TABLE ctts01tipocontrato (ctts01id varchar(20) NOT NULL, ctts01nombre varchar(100) NULL, ctts01orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==6400){$sSQL="ALTER TABLE ctts01tipocontrato ADD PRIMARY KEY(ctts01id)";}
	}
if (($dbversion>6400)&&($dbversion<6501)){
	if ($dbversion==6401){$sSQL="INSERT INTO ctts01tipocontrato (ctts01id, ctts01nombre, ctts01orden) VALUES ('CCE-01','Solicitud de información a los Proveedores',101), ('CCE-02','Licitación pública',102), ('CCE-03','Concurso de méritos con precalificación (descontinuado)',103), ('CCE-04','Concurso de méritos abierto (descontinuado)',104), ('CCE-05','Contratación directa (con ofertas) ',105), ('CCE-06','Selección abreviada menor cuantía',106), ('CCE-07','Selección abreviada subasta inversa',107), ('CCE-10','Mínima cuantía',110), ('CCE-11||01','Contratación régimen especial - Selección de comisionista',111), ('CCE-11||02','Contratación régimen especial - Enajenación de bienes para intermediarios idóneos',112), ('CCE-11||03','Contratación régimen especial - Régimen especial',113), ('CCE-11||04','Contratación régimen especial - Banco multilateral y organismos multilaterales',114), ('CCE-15||01','Contratación régimen especial (con ofertas)  - Selección de comisionista',151), ('CCE-15||02','Contratación régimen especial (con ofertas)  - Enajenación de bienes para intermediarios idóneos',152), ('CCE-15||03','Contratación régimen especial (con ofertas)  - Régimen especial',153), ('CCE-15||04','Contratación régimen especial (con ofertas)  - Banco multilateral y organismos multilaterales',154), ('CCE-16','Contratación directa.',160), ('CCE-17','Licitación pública (Obra pública)',170), ('CCE-18','Selección Abreviada de Menor Cuantia sin Manifestacion de Interés',180), ('CCE-19','Concurso de méritos con precalificación',190), ('CCE-20','Concurso de méritos abierto',200), ('CCE-99','Seléccion abreviada - acuerdo marco',990)";}
	if ($dbversion==6402){$sSQL="agregamodulo|4111|41|Tareas - (Configuración)|1|3";}
	if ($dbversion==6403){$sSQL="CREATE TABLE comp12procesocompra (comp12vigenciappt int NOT NULL, comp12consec int NOT NULL, comp12id int NOT NULL DEFAULT 0, comp12estado int NOT NULL DEFAULT 0, comp12idunidadfunc int NOT NULL DEFAULT 0, comp12descripcion Text NULL, comp12mesini int NOT NULL DEFAULT 0, comp12mesofertas int NOT NULL DEFAULT 0, comp12tc_agnos int NOT NULL DEFAULT 0, comp12tc_meses int NOT NULL DEFAULT 0, comp12tc_dias int NOT NULL DEFAULT 0, comp12modalidadsel int NOT NULL DEFAULT 0, comp12fuenterecursos int NOT NULL DEFAULT 0, comp12moneda int NOT NULL DEFAULT 0, comp12valortotal Decimal(15,2) NULL DEFAULT 0, comp12valorvigactual Decimal(15,2) NULL DEFAULT 0, comp12vigfutura int NOT NULL DEFAULT 0, comp12valorvigfutura Decimal(15,2) NULL DEFAULT 0, comp12estadovigfutura int NOT NULL DEFAULT 0, comp12idoficinacontrata int NOT NULL DEFAULT 0, comp12idfuncionario int NOT NULL DEFAULT 0, comp12fecha int NOT NULL DEFAULT 0)";}
	if ($dbversion==6404){$sSQL="ALTER TABLE comp12procesocompra ADD PRIMARY KEY(comp12id)";}
	if ($dbversion==6405){$sSQL=$objDB->sSQLCrearIndice('comp12procesocompra', 'comp12procesocompra_id', 'comp12vigenciappt, comp12consec', true);}
	// 6406 - 6408 quedan libres
	if ($dbversion==6409){$sSQL="CREATE TABLE comp13procesoprod (comp13idproceso int NOT NULL, comp13idproducto int NOT NULL, comp13zona int NOT NULL, comp13centro int NOT NULL, comp13escuela int NOT NULL, comp13programa int NOT NULL, comp13id int NOT NULL DEFAULT 0, comp13cantidad Decimal(15,2) NULL DEFAULT 0, comp13moneda int NOT NULL DEFAULT 0, comp13vrunitario Decimal(15,2) NULL DEFAULT 0, comp13porciva int NOT NULL DEFAULT 0, comp13vriva Decimal(15,2) NULL DEFAULT 0, comp13subtotal Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==6410){$sSQL="ALTER TABLE comp13procesoprod ADD PRIMARY KEY(comp13id)";}
	if ($dbversion==6411){$sSQL=$objDB->sSQLCrearIndice('comp13procesoprod', 'comp13procesoprod_id', 'comp13idproceso, comp13idproducto, comp13zona, comp13centro, comp13escuela, comp13programa', true);}
	if ($dbversion==6412){$sSQL=$objDB->sSQLCrearIndice('comp13procesoprod', 'comp13procesoprod_padre', 'comp13idproceso');}
	if ($dbversion==6413){$sSQL="agregamodulo|3913|39|PC - Productos incluidos|1|2|3|4|5|6|8";}
	if ($dbversion==6414){$sSQL="CREATE TABLE comp15procesoestudio (comp15idproceso int NOT NULL, comp15consec int NOT NULL, comp15id int NOT NULL DEFAULT 0, comp15idoferente int NOT NULL DEFAULT 0, comp15fechacot int NOT NULL DEFAULT 0, comp15valortotal Decimal(15,2) NULL DEFAULT 0, comp15fechavence int NOT NULL DEFAULT 0, comp15idorigencot int NOT NULL DEFAULT 0, comp15idcotiza int NOT NULL DEFAULT 0, comp15detalle Text NULL)";}
	if ($dbversion==6415){$sSQL="ALTER TABLE comp15procesoestudio ADD PRIMARY KEY(comp15id)";}
	if ($dbversion==6416){$sSQL=$objDB->sSQLCrearIndice('comp15procesoestudio', 'comp15procesoestudio_id', 'comp15idproceso, comp15consec', true);}
	if ($dbversion==6417){$sSQL=$objDB->sSQLCrearIndice('comp15procesoestudio', 'comp15procesoestudio_padre', 'comp15idproceso');}
	if ($dbversion==6418){$sSQL="agregamodulo|3915|39|PC - Estudio de mercado|1|2|3|4|5|6|8";}
	if ($dbversion==6419){$sSQL="mod_quitar|3912";}
	if ($dbversion==6420){$sSQL="mod_quitar|3913";}
	if ($dbversion==6421){$sSQL="agregamodulo|3912|39|Proceso de compra|1|2|3|4|5|6|8";}
	if ($dbversion==6422){$sSQL=$u09."(3912, 1, 'Proceso de compra', 'comproceso.php', 3901, 3912, 'S', '', '')";}
	if ($dbversion==6423){$sSQL=$unad70."(714,3912,'comp13procesoprod','comp13id','comp13idproducto','El dato esta incluido en Productos incluidos', '')";}
	if ($dbversion==6424){$sSQL="agregamodulo|3913|39|PC - Productos incluidos|1|2|3|4|5|6|8";}
	if ($dbversion==6425){$sSQL="agregamodulo|3972|39|CPC [4371]|1";}
	if ($dbversion==6426){$sSQL=$u09."(3972, 1, 'CPC', 'heracpc.php', 1, 3972, 'S', '', '')";}
	if ($dbversion==6427){$sSQL="CREATE TABLE gafi69grupoactiv (gafi69consec int NOT NULL, gafi69id int NOT NULL DEFAULT 0, gafi69orden int NOT NULL DEFAULT 0, gafi69vigente int NOT NULL DEFAULT 0, gafi69nombre varchar(100) NULL)";}
	if ($dbversion==6428){$sSQL="ALTER TABLE gafi69grupoactiv ADD PRIMARY KEY(gafi69id)";}
	if ($dbversion==6429){$sSQL=$objDB->sSQLCrearIndice('gafi69grupoactiv', 'gafi69grupoactiv_id', 'gafi69consec', true);}
	if ($dbversion==6430){$sSQL="agregamodulo|4669|46|Grupos de actividades|1|2|3|4|5|6|8";}
	if ($dbversion==6431){$sSQL=$u09."(4669, 1, 'Grupos de actividades', 'gafigrupoactiv.php', 2, 4669, 'S', '', '')";}
	if ($dbversion==6432){$sSQL=$unad70."(4669,4670,'gafi70actividadeco','gafi70id','gafi70grupo','El dato esta incluido en Actividades económicas', '')";}
	if ($dbversion==6433){$sSQL="CREATE TABLE gafi70actividadeco (gafi70codigo int NOT NULL, gafi70id int NOT NULL DEFAULT 0, gafi70grupo int NOT NULL DEFAULT 0, gafi70orden int NOT NULL DEFAULT 0, gafi70activa int NOT NULL DEFAULT 0, gafi70nombre varchar(250) NULL)";}
	if ($dbversion==6434){$sSQL="ALTER TABLE gafi70actividadeco ADD PRIMARY KEY(gafi70id)";}
	if ($dbversion==6435){$sSQL=$objDB->sSQLCrearIndice('gafi70actividadeco', 'gafi70actividadeco_id', 'gafi70codigo', true);}
	if ($dbversion==6436){$sSQL="agregamodulo|4670|46|Actividades económicas|1|2|3|4|5|6|8";}
	if ($dbversion==6437){$sSQL=$u09."(4670, 1, 'Actividades económicas', 'gafiactividadeco.php', 2, 4670, 'S', '', '')";}
	if ($dbversion==6438){$sSQL="INSERT gafi69grupoactiv (gafi69consec, gafi69id, gafi69orden, gafi69vigente, gafi69nombre) VALUES (0, 0, 999999, 0, '{Ninguno}')";}
	if ($dbversion==6439){$sSQL="INSERT gafi70actividadeco (gafi70codigo, gafi70id, gafi70grupo, gafi70orden, gafi70activa, gafi70nombre) VALUES (0, 0, 0, 0, 0, '{Ninguna}')";}
	if ($dbversion==6440){$sSQL="add_campos|comp11consolprod|comp11cantproceso Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==6441){$sSQL="add_campos|comp15procesoestudio|comp15moneda int NOT NULL DEFAULT 0|comp15vrpropuesta Decimal(15,2) NULL DEFAULT 0|comp15vriva Decimal(15,2) NULL DEFAULT 0|comp15vrtotal Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==6442){$sSQL="add_campos|gafi14monedas|gafi14orden int NOT NULL DEFAULT 0|gafi14activa int NOT NULL DEFAULT 0";}
	if ($dbversion==6443){$sSQL="add_campos|core01estprograma|core01idproggrado int NOT NULL DEFAULT 0|core01tran_idorigen int NOT NULL DEFAULT 0|core01tran_fechamatini int NOT NULL DEFAULT 0|core01tran_nummat int NOT NULL DEFAULT 0|core01tran_numcredaprob int NOT NULL DEFAULT 0|core01cp_idorigen int NOT NULL DEFAULT 0";}
	if ($dbversion==6444){$sSQL="CREATE TABLE visa01tiporeporte (visa01consec int NOT NULL, visa01id int NOT NULL DEFAULT 0, visa01activo int NOT NULL DEFAULT 0, visa01visiblebenef int NOT NULL DEFAULT 0, visa01nombre varchar(200) NULL, visa01descripcion Text NULL, visa01formaadmision int NOT NULL DEFAULT 0, visa01clase int NOT NULL DEFAULT 0, visa01idconvenio int NOT NULL DEFAULT 0, visa01peridicidad int NOT NULL DEFAULT 0, visa01fechaini int NOT NULL DEFAULT 0, visa01fechafin int NOT NULL DEFAULT 0)";}
	if ($dbversion==6445){$sSQL="ALTER TABLE visa01tiporeporte ADD PRIMARY KEY(visa01id)";}
	if ($dbversion==6446){$sSQL=$objDB->sSQLCrearIndice('visa01tiporeporte', 'visa01tiporeporte_id', 'visa01consec', true);}
	if ($dbversion==6447){$sSQL="agregamodulo|5001|29|Tipos de reportes exogenos|1|2|3|4|5|6|8";}
	if ($dbversion==6448){$sSQL=$u09."(5001, 1, 'Tipos de reportes', 'visaetiporeporte.php', 2, 5001, 'S', '', '')";}
	if ($dbversion==6449){$sSQL="CREATE TABLE visa02beneficiario (visa02idtiporeporte int NOT NULL, visa02idtercero int NOT NULL, visa02idpei int NOT NULL, visa02id int NOT NULL DEFAULT 0, visa02estado int NOT NULL DEFAULT 0, visa02idprograma int NOT NULL DEFAULT 0, visa02idpeitransicion int NOT NULL DEFAULT 0, visa02idprogramatran int NOT NULL DEFAULT 0, visa02idcicloini int NOT NULL DEFAULT 0, visa02ciclo1 int NOT NULL DEFAULT 0, visa02ciclo2 int NOT NULL DEFAULT 0, visa02ciclo3 int NOT NULL DEFAULT 0, visa02ciclo4 int NOT NULL DEFAULT 0, visa02ciclo5 int NOT NULL DEFAULT 0, visa02ciclo6 int NOT NULL DEFAULT 0, visa02ciclo7 int NOT NULL DEFAULT 0, visa02ciclo8 int NOT NULL DEFAULT 0, visa02ciclo9 int NOT NULL DEFAULT 0, visa02ciclo10 int NOT NULL DEFAULT 0, visa02ciclo11 int NOT NULL DEFAULT 0, visa02ciclo12 int NOT NULL DEFAULT 0, visa02ciclo13 int NOT NULL DEFAULT 0, visa02ciclo14 int NOT NULL DEFAULT 0, visa02ciclo15 int NOT NULL DEFAULT 0, visa02ciclo16 int NOT NULL DEFAULT 0, visa02ciclo17 int NOT NULL DEFAULT 0, visa02ciclo18 int NOT NULL DEFAULT 0, visa02ciclo19 int NOT NULL DEFAULT 0, visa02ciclo20 int NOT NULL DEFAULT 0, visa02reporte1 int NOT NULL DEFAULT 0, visa02reporte2 int NOT NULL DEFAULT 0, visa02reporte3 int NOT NULL DEFAULT 0, visa02reporte4 int NOT NULL DEFAULT 0, visa02reporte5 int NOT NULL DEFAULT 0, visa02reporte6 int NOT NULL DEFAULT 0, visa02reporte7 int NOT NULL DEFAULT 0, visa02reporte8 int NOT NULL DEFAULT 0, visa02reporte9 int NOT NULL DEFAULT 0, visa02reporte10 int NOT NULL DEFAULT 0, visa02reporte11 int NOT NULL DEFAULT 0, visa02reporte12 int NOT NULL DEFAULT 0, visa02reporte13 int NOT NULL DEFAULT 0, visa02reporte14 int NOT NULL DEFAULT 0, visa02reporte15 int NOT NULL DEFAULT 0, visa02reporte16 int NOT NULL DEFAULT 0, visa02reporte17 int NOT NULL DEFAULT 0, visa02reporte18 int NOT NULL DEFAULT 0, visa02reporte19 int NOT NULL DEFAULT 0, visa02reporte20 int NOT NULL DEFAULT 0, visa02pais varchar(3) NULL, visa02depto varchar(5) NULL, visa02ciudad varchar(8) NULL)";}
	if ($dbversion==6450){$sSQL="ALTER TABLE visa02beneficiario ADD PRIMARY KEY(visa02id)";}
	if ($dbversion==6451){$sSQL=$objDB->sSQLCrearIndice('visa02beneficiario', 'visa02beneficiario_id', 'visa02idtiporeporte, visa02idtercero, visa02idpei', true);}
	if ($dbversion==6452){$sSQL="agregamodulo|5002|29|Beneficiarios|1|2|3|4|5|6|8";}
	if ($dbversion==6453){$sSQL=$u09."(5002, 1, 'Beneficiarios', 'visaebeneficiarios.php', 2903, 5002, 'S', '', '')";}
	if ($dbversion==6454){$sSQL="CREATE TABLE visa03denefnota (visa03idbenef int NOT NULL, visa03consec int NOT NULL, visa03id int NOT NULL DEFAULT 0, visa03anotacion Text NULL, visa03idusuario int NOT NULL DEFAULT 0, visa03fecha int NOT NULL DEFAULT 0)";}
	if ($dbversion==6455){$sSQL="ALTER TABLE visa03denefnota ADD PRIMARY KEY(visa03id)";}
	if ($dbversion==6456){$sSQL=$objDB->sSQLCrearIndice('visa03denefnota', 'visa03denefnota_id', 'visa03idbenef, visa03consec', true);}
	if ($dbversion==6457){$sSQL=$objDB->sSQLCrearIndice('visa03denefnota', 'visa03denefnota_padre', 'visa03idbenef');}
	if ($dbversion==6458){$sSQL="agregamodulo|5003|29|Beneficiarios - Anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==6459){$sSQL="CREATE TABLE visa04envio (visa04idtiporeporte int NOT NULL, visa04consec int NOT NULL, visa04id int NOT NULL DEFAULT 0, visa04idciclo int NOT NULL DEFAULT 0, visa04formaarmado int NOT NULL DEFAULT 0, visa04estado int NOT NULL DEFAULT 0, visa04fechareporte int NOT NULL DEFAULT 0, visa04detalle Text NULL, visa04idusuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==6460){$sSQL="ALTER TABLE visa04envio ADD PRIMARY KEY(visa04id)";}
	if ($dbversion==6461){$sSQL=$objDB->sSQLCrearIndice('visa04envio', 'visa04envio_id', 'visa04idtiporeporte, visa04consec', true);}
	if ($dbversion==6462){$sSQL="agregamodulo|5004|29|Exogena - Envios|1|2|3|4|5|6|8";}
	if ($dbversion==6463){$sSQL=$u09."(5004, 1, 'Envios', 'visaeenvios.php', 2903, 5004, 'S', '', '')";}
	if ($dbversion==6464){$sSQL=$unad70."(140,5005,'visa05envioperiodo','visa05id','visa05idperiodo','El periodo se ha usado para enviar información exógena', '')";}
	if ($dbversion==6465){$sSQL="CREATE TABLE visa05envioperiodo (visa05idenvio int NOT NULL, visa05idperiodo int NOT NULL, visa05id int NOT NULL DEFAULT 0, visa05activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==6466){$sSQL="ALTER TABLE visa05envioperiodo ADD PRIMARY KEY(visa05id)";}
	if ($dbversion==6467){$sSQL=$objDB->sSQLCrearIndice('visa05envioperiodo', 'visa05envioperiodo_id', 'visa05idenvio, visa05idperiodo', true);}
	if ($dbversion==6468){$sSQL=$objDB->sSQLCrearIndice('visa05envioperiodo', 'visa05envioperiodo_padre', 'visa05idenvio');}
	if ($dbversion==6469){$sSQL="agregamodulo|5005|29|Exogena - Envios - Periodos|1|2|3|4|5|6|8";}
	if ($dbversion==6470){$sSQL=$unad70."(5002,5006,'visa06enviobenef','visa06id','visa06idbenef','El beneficiarios ha sido reportado', '')";}
	if ($dbversion==6471){$sSQL="CREATE TABLE visa06enviobenef (visa06idenvio int NOT NULL, visa06idbenef int NOT NULL, visa06id int NOT NULL DEFAULT 0, visa06idtercero int NOT NULL DEFAULT 0, visa06idpei int NOT NULL DEFAULT 0, visa06idmatricula int NOT NULL DEFAULT 0, visa06incluido int NOT NULL DEFAULT 0, visa06edad int NOT NULL DEFAULT 0, visa06pais varchar(3) NULL, visa06depto varchar(5) NULL, visa06ciudad varchar(8) NULL, visa06numcursos int NOT NULL DEFAULT 0, visa06numcred int NOT NULL DEFAULT 0, visa06promediociclo Decimal(15,2) NULL DEFAULT 0, visa06promedioacum Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==6472){$sSQL="ALTER TABLE visa06enviobenef ADD PRIMARY KEY(visa06id)";}
	if ($dbversion==6473){$sSQL=$objDB->sSQLCrearIndice('visa06enviobenef', 'visa06enviobenef_id', 'visa06idenvio, visa06idbenef', true);}
	if ($dbversion==6474){$sSQL=$objDB->sSQLCrearIndice('visa06enviobenef', 'visa06enviobenef_padre', 'visa06idenvio');}
	if ($dbversion==6475){$sSQL="agregamodulo|5006|29|Exogena - Envio - Beneficiario|1|2|3|4|5|6|8";}
	if ($dbversion==6476){$sSQL="CREATE TABLE visa71clasereporte (visa71id int NOT NULL, visa71nombre varchar(50) NULL)";}
	if ($dbversion==6477){$sSQL="ALTER TABLE visa71clasereporte ADD PRIMARY KEY(visa71id)";}
	if ($dbversion==6478){$sSQL="CREATE TABLE visa72formaadmrep (visa72id int NOT NULL, visa72nombre varchar(50) NULL)";}
	if ($dbversion==6479){$sSQL="ALTER TABLE visa72formaadmrep ADD PRIMARY KEY(visa72id)";}
	if ($dbversion==6480){$sSQL="CREATE TABLE visa73estadoben (visa73id int NOT NULL, visa73nombre varchar(50) NULL)";}
	if ($dbversion==6481){$sSQL="ALTER TABLE visa73estadoben ADD PRIMARY KEY(visa73id)";}
	if ($dbversion==6482){$sSQL="CREATE TABLE visa74visibilidad (visa74id int NOT NULL, visa74nombre varchar(50) NULL)";}
	if ($dbversion==6483){$sSQL="ALTER TABLE visa74visibilidad ADD PRIMARY KEY(visa74id)";}
	if ($dbversion==6484){$sSQL="CREATE TABLE visa75estadoenvio (visa75id int NOT NULL, visa75nombre varchar(50) NULL)";}
	if ($dbversion==6485){$sSQL="ALTER TABLE visa75estadoenvio ADD PRIMARY KEY(visa75id)";}
	if ($dbversion==6486){$sSQL="INSERT INTO visa71clasereporte (visa71id, visa71nombre) VALUES (0,'General'), (11,'Jovenes en acción')";}
	if ($dbversion==6487){$sSQL="INSERT INTO visa72formaadmrep (visa72id, visa72nombre) VALUES (0,'Por postulación'), (1,'por selección'), (2,'Lista precargada'), (9,'General')";}
	if ($dbversion==6488){$sSQL="INSERT INTO visa73estadoben (visa73id, visa73nombre) VALUES (0,'Postulado'), (1,'Aprobado'), (3,'Inactivo'), (5,'Activo'), (6,'Aplazado'), (8,'Expulsado'), (9,'Finalizado')";}
	if ($dbversion==6489){$sSQL="INSERT INTO visa74visibilidad (visa74id, visa74nombre) VALUES (0,'Desde la postulación'), (1,'Desde que sea aprobado'), (3,'Desde que este activo'), (9,'No visible')";}
	if ($dbversion==6490){$sSQL="INSERT INTO visa75estadoenvio (visa75id, visa75nombre) VALUES (0,'Borrador'), (3,'En revisión'), (7,'Cerrado')";}
	}
if (($dbversion>6500)&&($dbversion<6601)){
	//, cttc11activo, cttc11anexo, cttc11observaciones, cttc11aprobacion, cttc11version
	if ($dbversion==6560){$sSQL="INSERT INTO cttc11tareas (cttc11idevento, cttc11consec, cttc11id, cttc11nombre, cttc11activo, cttc11anexo, cttc11observaciones, cttc11aprobacion, cttc11version) VALUES 
	(5, 1, 1, 'NECESIDADES (ESTUDIOS PREVIOS)', 1, 1, 1, 1, 1), 
	(10, 1, 2, 'Termino de referencia', 1, 1, 1, 1, 1), 
	(3, 'EXPEDICION CDP'), 
	(4, 'APERTURA INVITACIÓN Y PUBLICACIÓN DE LOS TÉRMINOS DE REFERNCIA '), 
	(5, 'RESPUESTA Y ACLARACIONES A LAS OBSERVACIONES DE LOS TÉRMINOS DE REFERENCIA, ADENDAS'), 
	(6, 'RECEPCIÓN DE OFERTAS Y CIERRE DE LA INVITACIÓN'), 
	(7, 'SOLICITUD DE ACLARACIONES A LAS OFERTAS'), 
	(8, 'PROCESO DE EVALUACIONES A LAS OFERTAS'), 
	(9, 'RESPUESTA A LAS ACLARACIONES  DE LAS OFERTAS'), 
	(10, 'PUBLICACIÓN DEL INFORME DE EVALUACIÓN PRELIMINAR'), 
	(11, 'RESPUESTA A LAS OBSERVACIONES DE LAS OFERTAS'), 
	(12, 'PUBLICACIÓN INFORME DE EVALUACIÓN DEFINITIVO Y ADJUDICACIÓN'), 
	(13, 'ADJUDICACIÓN'), 
	(14, 'REGISTRO DE DOCUMENTOS PREVIOS DEL CONTRATISTA'), 
	(15, 'REALIZAR MINUTA DEL CONTRATO '), 
	(16, 'REMITIR EL CONTRATO PARA LA FIRMA DEL ORDENADOR DEL GASTO '), 
	(17, 'Solicitar el CRP respectivo'), 
	(18, 'SOLICITUD DE  GARANTÍAS (POLIZAS)'), 
	(19, 'Solicitar al contratista y supervisor del contrato, la gestión correspondiente a afiliación a ARL frente a Talento Humano (cuando así aplique).'), 
	(20, 'SOLICITAR SUSCRIPCIÓN DEL CONTRATO POR PARTE DEL CONTRATISTA'), 
	(21, 'OFICIO PLAZO DE EJECUCIÓN'), 
	(22, 'Realizar designación de supervisión del contrato'), 
	(23, 'Publicar en SECOP II y anexar al expediente reporte de la publicación, reuniendo todos los documentos para legalización del contrato'), 
	(24, 'ACTA DE INICIO'), 
	(25, 'ENTREGA PARCIAL DEL CONTRATO'), 
	(26, 'RADICACIÓN DE FACTURAS A TESORERÍA  Y CARGUE DE DOCUMENTOS DE PAGO EN SECOP II'), 
	(27, 'OTROSI'), 
	(28, 'SOLICITUD DE POLIZAS'), 
	(29, 'APROBACION DE POLIZAS'), 
	(30, 'CEDER CONTRATO'), 
	(31, 'SUSPENDER CONTRATO'), 
	(32, 'REANUDAR CONTRATO'), 
	(33, 'TERMINAR CONTRATO'), 
	(34, 'ACTA DE LIQUIDACION'), 
	(35, 'REVISIÓN Y REALIZACIÓN DE LAS OBSERVACIONES DE ACTA DE LIQUIDACIÓN POR SECRETARÍA GENERAL AL DOCUMENTO REMITIDO POR LA UNIDAD GESTORA'), 
	(36, 'APROBACIÓN DE ACTA DE LIQUIDACIÓN POS PARTE DE LA COORDINACIÓN JURÍDICA UNA VEZ SE SUBSANEN LAS OBSERVACIONES'), 
	(37, 'REMISIÓN DE ACTA DE LIQUIDACIÓN PARA FIRMA DEL ORDENADOR DEL GASTO'), 
	(38, 'REMISIÓN DE ACTA DE LIQUIDACIÓN FIRMADA POR LAS PARTES A LA UNIDAD GESTORA PARA LA RADICACIÓN EN TEORERÍA'), 
	(39, 'CARGUE DE ULTIMO PAGO EN SECOP II'), 
	(40, 'LIQUIDAR COSTOS POR ACTIVIDAD')";}
	/*
	fact14idcpcgrupo int NOT NULL DEFAULT 0, fact14idcpc int NOT NULL DEFAULT 0
	*/
	//if ($dbversion==5330){$sSQL="agregamodulo|4071|40|CPC|1|2|3|4|5|6";}
	//if ($dbversion==5331){$sSQL=$u09."(4071, 1, 'CPC', 'heracpc.php', 1, 4071, 'S', '', '')";}
	//if ($dbversion==5334){$sSQL="agregamodulo|4072|40|Unidades de medida|1|2|3|4|5|6";}
	//if ($dbversion==5335){$sSQL=$u09."(4072, 1, 'Unidades de medida', 'heraunidadmedida.php', 2, 4072, 'S', '', '')";}
	if ($dbversion==6201) {$sSQL ="INSERT INTO ofes09estadorec (ofes09id, ofes09nombre) VALUES (0, 'Borrador'), (3, 'Devuelto'), (7, 'En firme')";}	
	}
if (($dbversion>6500)&&($dbversion<6601)){
	}
if (($dbversion>6600)&&($dbversion<6701)){
	}
if (($dbversion>6700)&&($dbversion<6801)){
	}
if (($dbversion>6800)&&($dbversion<6901)){
	}
if (($dbversion>6900)&&($dbversion<7001)){
	// unae26unidadesfun
	// 2711 Proyectos de grado -- Consultar datos de otros usuarios 
	// 2282 Homologaciones por convenio - Abrir - 
	// 2200 Panel SAI - Consultar datos de otros usuarios.
	//if ($dbversion==5999){$sSQL=$u04."(2711, 12, 'S'), (2282, 17, 'S'), (2200, 12, 'S')";}
	}
if (($dbversion>7000)&&($dbversion<7101)){
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
	echo '<br>[' . $dbversion . '] '.$sSQL;
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
					echo '<br> -- Error ejecutando <font color="#FF0000"><b>'.$sTemp.'</b></font> <b>' . $objDB->serror . '</b>';
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
			echo '<br>Error <font color="#FF0000"><b>'.$objDB->serror.'</b></font>';
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
<input type="submit" name="Submit" value="Continuar" />
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
