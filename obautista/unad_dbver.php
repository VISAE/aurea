<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Inicia Jueves, 19 de diciembre de 2019
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
$versionejecutable=5588;
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
	if ($dbversion<5000){$bbloquea=true;}
	if ($dbversion>6000){$bbloquea=true;}
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
if (($dbversion>5000)&&($dbversion<5101)){
	if ($dbversion==5001){$sSQL="mod_quitar|12227";}
	if ($dbversion==5002){$sSQL="DROP TABLE corf25plantransicion";}
	if ($dbversion==5003){$sSQL="DROP TABLE corf26plandetalle";}
	if ($dbversion==5004){$sSQL="DROP TABLE corf27plannota";}
	if ($dbversion==5005){$sSQL="CREATE TABLE ceca41pazysalvo (ceca41consec int NOT NULL, ceca41id int NULL DEFAULT 0, ceca41estado int NULL DEFAULT 0, ceca41idtercero int NULL DEFAULT 0, ceca41fecha int NULL DEFAULT 0, ceca41hora int NULL DEFAULT 0, ceca41minuto int NULL DEFAULT 0, ceca41codigo varchar(50) NULL)";}
	if ($dbversion==5006){$sSQL="ALTER TABLE ceca41pazysalvo ADD PRIMARY KEY(ceca41id)";}
	if ($dbversion==5007){$sSQL=$objDB->sSQLCrearIndice('ceca41pazysalvo', 'ceca41pazysalvo_id', 'ceca41consec', true);}
	if ($dbversion==5008){$sSQL="agregamodulo|2441|24|Paz y salvos|1|2|3|4|5|6|8";}
	if ($dbversion==5009){$sSQL=$u09."(2441, 1, 'Paz y salvos', 'cecapazysalvo.php', 1, 2441, 'S', '', '')";}
	if ($dbversion==5010){$sSQL="CREATE TABLE ceca42pysperiodo (ceca42idpazysalvo int NOT NULL, ceca42idperiodo int NOT NULL, ceca42id int NULL DEFAULT 0, ceca42vigente int NULL DEFAULT 0)";}
	if ($dbversion==5011){$sSQL="ALTER TABLE ceca42pysperiodo ADD PRIMARY KEY(ceca42id)";}
	if ($dbversion==5012){$sSQL=$objDB->sSQLCrearIndice('ceca42pysperiodo', 'ceca42pysperiodo_id', 'ceca42idpazysalvo, ceca42idperiodo', true);}
	if ($dbversion==5013){$sSQL=$objDB->sSQLCrearIndice('ceca42pysperiodo', 'ceca42pysperiodo_padre', 'ceca42idpazysalvo');}
	if ($dbversion==5014){$sSQL="agregamodulo|2442|24|Paz y salvos - Periodos|1|2|3|4|5|6|8";}
	if ($dbversion==5015){$sSQL="CREATE TABLE ceca43pysnota (ceca43idpazysalvo int NOT NULL, ceca43consec int NOT NULL, ceca43id int NULL DEFAULT 0, ceca43estado int NULL DEFAULT 0, ceca43detalle Text NULL, ceca43idusuario int NULL DEFAULT 0, ceca43fecha int NULL DEFAULT 0, ceca43hora int NULL DEFAULT 0, ceca43minuto int NULL DEFAULT 0)";}
	if ($dbversion==5016){$sSQL="ALTER TABLE ceca43pysnota ADD PRIMARY KEY(ceca43id)";}
	if ($dbversion==5017){$sSQL=$objDB->sSQLCrearIndice('ceca43pysnota', 'ceca43pysnota_id', 'ceca43idpazysalvo, ceca43consec', true);}
	if ($dbversion==5018){$sSQL=$objDB->sSQLCrearIndice('ceca43pysnota', 'ceca43pysnota_padre', 'ceca43idpazysalvo');}
	if ($dbversion==5019){$sSQL="agregamodulo|2443|24|Paz y salvos - Anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==5020){$sSQL="agregamodulo|2440|24|Mis paz y salvos|1|1707";}
	if ($dbversion==5021){$sSQL=$u09."(2440, 1, 'Mis paz y salvos', 'mipazysalvo.php', 1, 2440, 'S', '', '')";}
	if ($dbversion==5022){$sSQL="CREATE TABLE saiu68categoria (saiu68consec int NOT NULL, saiu68id int NULL DEFAULT 0, saiu68activa int NULL DEFAULT 0, saiu68orden int NULL DEFAULT 0, saiu68publica int NULL DEFAULT 0, saiu68nombre varchar(100) NULL)";}
	if ($dbversion==5023){$sSQL="ALTER TABLE saiu68categoria ADD PRIMARY KEY(saiu68id)";}
	if ($dbversion==5024){$sSQL=$objDB->sSQLCrearIndice('saiu68categoria', 'saiu68categoria_id', 'saiu68consec', true);}
	if ($dbversion==5025){$sSQL="agregamodulo|3068|30|Categorias PQRS|1|2|3|4|5|6";}
	if ($dbversion==5026){$sSQL=$u09."(3068, 1, 'Categorias PQRS', 'saiucategoriapqr.php', 2, 3068, 'S', '', '')";}
	if ($dbversion==5027){$sSQL="DROP TABLE unae24historialcambdoc";}
	if ($dbversion==5028){$sSQL="CREATE TABLE unae40historialcambdoc (unae40idtercero int NOT NULL, unae40consec int NOT NULL, unae40id int NULL DEFAULT 0, unae40tipodocorigen varchar(3) NULL, unae40docorigen varchar(20) NULL, unae40or_nombre1 varchar(50) NULL, unae40or_nombre2 varchar(50) NULL, unae40or_apellido1 varchar(50) NULL, unae40or_apellido2 varchar(50) NULL, unae40or_sexo varchar(1) NULL, unae40or_fechanac varchar(10) NULL, unae40or_fechadoc varchar(10) NULL, unae40tipodocdestino varchar(3) NULL, unae40docdestino varchar(20) NULL, unae40des_nombre1 varchar(50) NULL, unae40des_nombre2 varchar(50) NULL, unae40des_apellido1 varchar(50) NULL, unae40des_apellido2 varchar(50) NULL, unae40des_sexo varchar(1) NULL, unae40des_fechanac varchar(10) NULL, unae40des_fechadoc varchar(10) NULL, unae40idsolicita int NULL DEFAULT 0, unae40fechasol int NULL DEFAULT 0, unae40horasol int NULL DEFAULT 0, unae40minsol int NULL DEFAULT 0, unae40idorigen int NULL DEFAULT 0, unae40idarchivo int NULL DEFAULT 0, unae40estado int NULL DEFAULT 0, unae40detalle Text NULL, unae40idaprueba int NULL DEFAULT 0, unae40fechaapr int NULL DEFAULT 0, unae40horaaprueba int NULL DEFAULT 0, unae40minaprueba int NULL DEFAULT 0, unae40tiempod int NULL DEFAULT 0, unae40tiempoh int NULL DEFAULT 0)";}
	if ($dbversion==5029){$sSQL="ALTER TABLE unae40historialcambdoc ADD PRIMARY KEY(unae40id)";}
	if ($dbversion==5030){$sSQL=$objDB->sSQLCrearIndice('unae40historialcambdoc', 'unae40historialcambdoc_id', 'unae40idtercero, unae40consec', true);}
	if ($dbversion==5031){$sSQL="ALTER TABLE bita27equipotrabajo ADD bita27propietario int NULL DEFAULT 0, ADD bita27activo int NULL DEFAULT 1";}
	if ($dbversion==5032){$sSQL="CREATE TABLE corf54programaopcion (corf54idprograma int NOT NULL, corf54idasociado int NOT NULL, corf54id int NULL DEFAULT 0, corf54vigente int NULL DEFAULT 0)";}
	if ($dbversion==5033){$sSQL="ALTER TABLE corf54programaopcion ADD PRIMARY KEY(corf54id)";}
	if ($dbversion==5034){$sSQL=$objDB->sSQLCrearIndice('corf54programaopcion', 'corf54programaopcion_id', 'corf54idprograma, corf54idasociado', true);}
	if ($dbversion==5035){$sSQL=$objDB->sSQLCrearIndice('corf54programaopcion', 'corf54programaopcion_padre', 'corf54idprograma');}
	if ($dbversion==5036){$sSQL="agregamodulo|12254|22|Programa - Postgrado opc grado|2|3|4|5|6";}
	if ($dbversion==5037){$sSQL="CREATE TABLE corf57comiteescuela (corf57idescuela int NOT NULL, corf57idtipocomite int NOT NULL, corf57zona int NOT NULL, corf57id int NULL DEFAULT 0, corf57idcoordinador int NULL DEFAULT 0, corf57correo varchar(50) NULL)";}
	if ($dbversion==5038){$sSQL="ALTER TABLE corf57comiteescuela ADD PRIMARY KEY(corf57id)";}
	if ($dbversion==5039){$sSQL=$objDB->sSQLCrearIndice('corf57comiteescuela', 'corf57comiteescuela_id', 'corf57idescuela, corf57idtipocomite, corf57zona', true);}
	if ($dbversion==5040){$sSQL=$objDB->sSQLCrearIndice('corf57comiteescuela', 'corf57comiteescuela_padre', 'corf57idescuela');}
	if ($dbversion==5041){$sSQL="agregamodulo|12257|22|Escuelas - Comites|1|2|3|4|5|6";}
	if ($dbversion==5042){$sSQL="CREATE TABLE corf55comites (corf55consec int NOT NULL, corf55id int NULL DEFAULT 0, corf55orden int NULL DEFAULT 0, corf55vigente int NULL DEFAULT 0, corf55nombre varchar(100) NULL, cirf55idperfil int NULL DEFAULT 0, corf55aplazamientos int NULL DEFAULT 0)";}
	if ($dbversion==5043){$sSQL="ALTER TABLE corf55comites ADD PRIMARY KEY(corf55id)";}
	if ($dbversion==5044){$sSQL=$objDB->sSQLCrearIndice('corf55comites', 'corf55comites_id', 'corf55consec', true);}
	if ($dbversion==5045){$sSQL="agregamodulo|12255|22|Escuela - Comites - Tipos|1|2|3|4|5|6|8";}
	if ($dbversion==5046){$sSQL=$u09."(12255, 1, 'Tipos de comites', 'coretipocomite.php', 3, 12255, 'S', '', '')";}
	if ($dbversion==5047){$sSQL="CREATE TABLE corf56rolcomite (corf56consec int NOT NULL, corf56id int NULL DEFAULT 0, corf56orden int NULL DEFAULT 0, corf56vigente int NULL DEFAULT 0, corf56nombre varchar(50) NULL)";}
	if ($dbversion==5048){$sSQL="ALTER TABLE corf56rolcomite ADD PRIMARY KEY(corf56id)";}
	if ($dbversion==5049){$sSQL=$objDB->sSQLCrearIndice('corf56rolcomite', 'corf56rolcomite_id', 'corf56consec', true);}
	if ($dbversion==5050){$sSQL="agregamodulo|12256|22|Escuela - Comites - Roles|1|2|3|4|5|6|8";}
	if ($dbversion==5051){$sSQL=$u09."(12256, 1, 'Roles en los comites', 'corerolcomite.php', 3, 12256, 'S', '', '')";}
	if ($dbversion==5052){$sSQL="agregamodulo|12258|22|Comites de escuela|1|2|3|4|5|6";}
	if ($dbversion==5053){$sSQL=$u09."(12258, 1, 'Comites de escuela', 'corecomites.php', 1, 2221, 'S', '', '')";}
	if ($dbversion==5054){$sSQL="CREATE TABLE corf58integrantes (corf59idcomiteesc int NOT NULL, corf59consec int NOT NULL, corf59id int NULL DEFAULT 0, corf59idtercero int NULL DEFAULT 0, corf59idrol int NULL DEFAULT 0, corf59activo int NULL DEFAULT 0, corf59fechaingreso int NULL DEFAULT 0, corf59fechasalida int NULL DEFAULT 0)";}
	if ($dbversion==5055){$sSQL="ALTER TABLE corf58integrantes ADD PRIMARY KEY(corf59id)";}
	if ($dbversion==5056){$sSQL=$objDB->sSQLCrearIndice('corf58integrantes', 'corf58integrantes_id', 'corf59idcomiteesc, corf59consec', true);}
	if ($dbversion==5057){$sSQL=$objDB->sSQLCrearIndice('corf58integrantes', 'corf58integrantes_padre', 'corf59idcomiteesc');}
	if ($dbversion==5058){$sSQL="agregamodulo|12259|22|Comite escuela - Integrantes|1|2|3|4|5|6|8";}
	if ($dbversion==5059){$sSQL="ALTER TABLE unad40curso ADD unad40numofertas int NULL DEFAULT 0, ADD unad40numofertaespecial int NULL DEFAULT 0";}
	if ($dbversion==5060){$sSQL="ALTER TABLE core00params ADD core00idperfiladmincomite int NULL DEFAULT 0";}
	if ($dbversion==5061){$sSQL="INSERT INTO corf10estadonovedad (corf10id, corf10nombre) VALUES (6, 'Desistida por términos')";}
	if ($dbversion==5062){$sSQL=$u04."(2266, 16, 'S')";}
	if ($dbversion==5063){$sSQL="ALTER TABLE corf57comiteescuela ADD corf57idcadena int NOT NULL DEFAULT 0, ADD corf57idprograma int NOT NULL DEFAULT 0";}
	if ($dbversion==5064){$sSQL=$objDB->sSQLEliminarIndice('corf57comiteescuela', 'corf57comiteescuela_id');}
	if ($dbversion==5065){$sSQL=$objDB->sSQLCrearIndice('corf57comiteescuela', 'corf57comiteescuela_id', 'corf57idescuela, corf57idtipocomite, corf57zona, corf57idcadena, corf57idprograma', true);}
	if ($dbversion==5066){$sSQL="INSERT INTO core44cadenaforma (core44idescuela, core44consec, core44id, core44nombre, core44idlider ) VALUES (0, 0, 0, 'Ninguna', 0);";}
	if ($dbversion==5067){$sSQL="INSERT INTO unad46tipoperiodo (unad46id, unad46nombre) VALUES (11, 'SUA - Cursos MOOC')";}
	if ($dbversion==5068){$sSQL="CREATE TABLE ofes01preoferta (ofes01idperiodo int NOT NULL, ofes01idcurso int NOT NULL, ofes01id int NULL DEFAULT 0, ofes01estado int NULL DEFAULT 0, ofes01fechainialista int NULL DEFAULT 0, ofes01duracionmax int NULL DEFAULT 0, ofes01idmodalidad int NULL DEFAULT 0, ofes01idtarifa int NULL DEFAULT 0, ofes01iddirector int NULL DEFAULT 0, ofes01idgestor int NULL DEFAULT 0)";}
	if ($dbversion==5069){$sSQL="ALTER TABLE ofes01preoferta ADD PRIMARY KEY(ofes01id)";}
	if ($dbversion==5070){$sSQL=$objDB->sSQLCrearIndice('ofes01preoferta', 'ofes01preoferta_id', 'ofes01idperiodo, ofes01idcurso', true);}
	if ($dbversion==5071){$sSQL="agregamodulo|1801|17|Preoferta SUA|1|2|3|4|5|6|8";}
	if ($dbversion==5072){$sSQL=$u09."(1801, 1, 'Preoferta SUA', 'oferpreofertasua.php', 1704, 1801, 'S', '', '')";}
	// 5073 a 5076 Quedan libres.
	if ($dbversion==5078){$sSQL="agregamodulo|1802|17|Preoferta SUA - Ediciones|1|2|3|4|5|6|8";}
	if ($dbversion==5079){$sSQL="INSERT INTO cart01productos (cart01id, cart01nombre, cart01cursos ) VALUES (101, 'SUA - Certificado de superación', 1), (102, 'SUA - Certificado de Acreditación de Conocimiento', 1), (103, 'SUA - Certificado de Competencias', 1)";}
	if ($dbversion==5080){$sSQL="ALTER TABLE core01estprograma CHANGE core01contaprob8 core01idgradopostgrado INT NULL DEFAULT 0";}
	if ($dbversion==5081){$sSQL="CREATE TABLE saiu69formarecaudo (saiu69id int NOT NULL, saiu69nombre varchar(50) NULL, saiu69derivada int NULL DEFAULT 0)";}
	if ($dbversion==5082){$sSQL="ALTER TABLE saiu69formarecaudo ADD PRIMARY KEY(saiu69id)";}
	if ($dbversion==5083){$sSQL="INSERT INTO saiu69formarecaudo (saiu69id, saiu69nombre, saiu69derivada) VALUES (0, '{Sin definir}', 0), (1, 'Consiganción', 712), (2, 'Transferencia', 712), (3, 'Convenio', 911), (99, 'Otro', 0)";}
	if ($dbversion==5084){$sSQL="CREATE TABLE cart11entidadconv (cart11consec int NOT NULL, cart11id int NULL DEFAULT 0, cart11vigente int NULL DEFAULT 0, cart11publica int NULL DEFAULT 0, cart11idtercero int NULL DEFAULT 0, cart11idbanco int NULL DEFAULT 0, cart11idcuenta int NULL DEFAULT 0)";}
	if ($dbversion==5085){$sSQL="ALTER TABLE cart11entidadconv ADD PRIMARY KEY(cart11id)";}
	if ($dbversion==5086){$sSQL=$objDB->sSQLCrearIndice('cart11entidadconv', 'cart11entidadconv_id', 'cart11consec', true);}
	if ($dbversion==5087){$sSQL="agregamodulo|911|9|Entidades convenio|1|2|3|4|5|6|8";}
	if ($dbversion==5088){$sSQL=$u09."(911, 1, 'Entidades convenio', 'cartentconvenio.php', 2, 911, 'S', '', '')";}
	if ($dbversion==5089){$sSQL="INSERT INTO core66tipohomologa (core66consec, core66id, core66clase, core66activa, core66general, core66titulo, core66detalle, core66instruccionesalumno) VALUES (-2, -2, 0, 0, 0, 'Importación', 'Corresponde a históricos', '')";}
	// --- se libera la 5090  a la 5094
	if ($dbversion==5095){$sSQL="ALTER TABLE aure61ddcampos CHANGE aure61nombre aure61nombre VARCHAR(40) NULL DEFAULT '';";}
	if ($dbversion==5096){$sSQL="ALTER TABLE core01estprograma CHANGE core01contaprob9 core01sem_proyectados INT NULL DEFAULT 0";}
	if ($dbversion==5097){$sSQL="ALTER TABLE core01estprograma CHANGE core01contaprob10 core01avancecreditos INT NULL DEFAULT 0";}
	if ($dbversion==5098){$sSQL="ALTER TABLE core01estprograma CHANGE core01contaprob11 core01sem_total INT NULL DEFAULT 0";}
	if ($dbversion==5099){$sSQL="ALTER TABLE core01estprograma CHANGE core01contaprob12 core01oportunidad INT NULL DEFAULT 0";}
	if ($dbversion==5100){$sSQL="agregamodulo|2823|28|Graduación oportuna|1|5|6";}
	}
if (($dbversion>5100)&&($dbversion<5201)){
	if ($dbversion==5101){$sSQL=$u09."(2823, 1, 'Graduación oportuna', 'rptgradoportuno.php', 2802, 2823, 'S', '', '')";}
	if ($dbversion==5102){$sSQL="ALTER TABLE core27tipoespejo ADD core27idlider int NULL DEFAULT 0";}
	if ($dbversion==5103){$sSQL=$u01."(5, 'PRESUPUESTO', 'Sistema de Gestión de Presupuesto', 'N', 'S', 1, 0, 0)";}
	if ($dbversion==5104){$sSQL="CREATE TABLE ppto01recurso (ppto01consec int NOT NULL, ppto01id int NULL DEFAULT 0, ppto01nombre varchar(50) NULL, ppto01activo int NULL DEFAULT 0, ppto01idcgr_rec int NULL DEFAULT 0, ppto01idsnies_rec int NULL DEFAULT 0)";}
	if ($dbversion==5105){$sSQL="ALTER TABLE ppto01recurso ADD PRIMARY KEY(ppto01id)";}
	if ($dbversion==5106){$sSQL=$objDB->sSQLCrearIndice('ppto01recurso', 'ppto01recurso_id', 'ppto01consec', true);}
	if ($dbversion==5107){$sSQL="agregamodulo|501|5|Recursos prespuestales|1|2|3|4|5|6|8";}
	if ($dbversion==5108){$sSQL=$u09."(501, 1, 'Recursos prespuestales', 'pptorecurso.php', 2, 501, 'S', '', '')";}
	if ($dbversion==5109){$sSQL="CREATE TABLE ppto02familiappto (ppto02consec int NOT NULL, ppto02id int NULL DEFAULT 0, ppto02vigente int NULL DEFAULT 0, ppto02nombre varchar(50) NULL)";}
	if ($dbversion==5110){$sSQL="ALTER TABLE ppto02familiappto ADD PRIMARY KEY(ppto02id)";}
	if ($dbversion==5111){$sSQL=$objDB->sSQLCrearIndice('ppto02familiappto', 'ppto02familiappto_id', 'ppto02consec', true);}
	if ($dbversion==5112){$sSQL="agregamodulo|502|5|Familias presupuestales|1|2|3|4|5|6|8";}
	if ($dbversion==5113){$sSQL=$u09."(502, 1, 'Familias presupuestales', 'pptofamilia.php', 2, 502, 'S', '', '')";}
	if ($dbversion==5114){$sSQL=$unad70."(502,503,'ppto03vigencia','','ppto03idfamilia','El dato esta incluido en Vigencias presupuestales', '')";}
	if ($dbversion==5115){$sSQL="CREATE TABLE ppto03vigencia (ppto03idfamilia int NOT NULL, ppto03codigo varchar(20) NOT NULL, ppto03id int NULL DEFAULT 0, ppto03fechainicio int NULL DEFAULT 0, ppto03fechacierre int NULL DEFAULT 0, ppto03fecharezago int NULL DEFAULT 0)";}
	if ($dbversion==5116){$sSQL="ALTER TABLE ppto03vigencia ADD PRIMARY KEY(ppto03id)";}
	if ($dbversion==5117){$sSQL=$objDB->sSQLCrearIndice('ppto03vigencia', 'ppto03vigencia_id', 'ppto03idfamilia, ppto03codigo', true);}
	if ($dbversion==5118){$sSQL="agregamodulo|503|5|Vigencias presupuestales|1|2|3|4|5|6|8";}
	if ($dbversion==5119){$sSQL=$u09."(503, 1, 'Vigencias presupuestales', 'pptovigencia.php', 2, 503, 'S', '', '')";}
	if ($dbversion==5120){$sSQL="CREATE TABLE ppto04vigrecurso (ppto04idvigencia int NOT NULL, ppto04idrecurso int NOT NULL, ppto04id int NULL DEFAULT 0, ppto04activo int NULL DEFAULT 0)";}
	if ($dbversion==5121){$sSQL="ALTER TABLE ppto04vigrecurso ADD PRIMARY KEY(ppto04id)";}
	if ($dbversion==5122){$sSQL=$objDB->sSQLCrearIndice('ppto04vigrecurso', 'ppto04vigrecurso_id', 'ppto04idvigencia, ppto04idrecurso', true);}
	if ($dbversion==5123){$sSQL=$objDB->sSQLCrearIndice('ppto04vigrecurso', 'ppto04vigrecurso_padre', 'ppto04idvigencia');}
	if ($dbversion==5124){$sSQL="agregamodulo|504|5|Vigencias - Recursos|1|2|3|4|5|6|8";}
	if ($dbversion==5125){$sSQL="CREATE TABLE ppto05vigcierre (ppto05idvigencia int NOT NULL, ppto05fecha int NOT NULL, ppto05id int NULL DEFAULT 0, ppto05idresponsable int NULL DEFAULT 0, ppto05momento int NULL DEFAULT 0, ppto05numdias int NULL DEFAULT 0, ppto05fecharesumen int NULL DEFAULT 0)";}
	if ($dbversion==5126){$sSQL="ALTER TABLE ppto05vigcierre ADD PRIMARY KEY(ppto05id)";}
	if ($dbversion==5127){$sSQL=$objDB->sSQLCrearIndice('ppto05vigcierre', 'ppto05vigcierre_id', 'ppto05idvigencia, ppto05fecha', true);}
	if ($dbversion==5128){$sSQL=$objDB->sSQLCrearIndice('ppto05vigcierre', 'ppto05vigcierre_padre', 'ppto05idvigencia');}
	if ($dbversion==5129){$sSQL="agregamodulo|505|5|Vigencias - Cierres|1|2|3|4|5|6|8";}
	if ($dbversion==5130){$sSQL="CREATE TABLE ppto97momentos (ppto97id int NOT NULL, ppto97momento varchar(50) NULL, ppto97id_lg varchar(10) NULL)";}
	if ($dbversion==5131){$sSQL="ALTER TABLE ppto97momentos ADD PRIMARY KEY(ppto97id)";}
	if ($dbversion==5132){$sSQL="INSERT INTO ppto97momentos (ppto97id, ppto97momento, ppto97id_lg) VALUES (0, 'Saldos Iniciales', '597_0'), (3, 'Vigencia', '597_3'), (9, 'Rezago', '597_9')";}
	if ($dbversion==5133){$sSQL="agregamodulo|591|5|Perfiles|1";}
	if ($dbversion==5134){$sSQL=$u09."(591, 1, 'Perfiles', 'unadperfil.php', 2, 591, 'S', '', '')";}
	if ($dbversion==5135){$sSQL="agregamodulo|592|5|Usuarios|1";}
	if ($dbversion==5136){$sSQL=$u09."(592, 1, 'Usuarios', 'unadusuarios.php', 1, 592, 'S', '', '')";}
	if ($dbversion==5137){$sSQL="agregamodulo|593|5|Personas|1";}
	if ($dbversion==5138){$sSQL=$u09."(593, 1, 'Personas', 'unadpersonas.php', 1, 593, 'S', '', '')";}
	if ($dbversion==5139){$sSQL="CREATE TABLE dato01ppt_rec_cgr (dato01codigo varchar(20) NOT NULL, dato01id int NULL DEFAULT 0, dato01nombre varchar(100) NULL)";}
	if ($dbversion==5140){$sSQL="ALTER TABLE dato01ppt_rec_cgr ADD PRIMARY KEY(dato01id)";}
	if ($dbversion==5141){$sSQL=$objDB->sSQLCrearIndice('dato01ppt_rec_cgr', 'dato01ppt_rec_cgr_id', 'dato01codigo', true);}
	if ($dbversion==5142){$sSQL="agregamodulo|401|5|CGR Recursos|1|2|3|4|5|6|8";}
	if ($dbversion==5143){$sSQL=$u09."(401, 1, 'CGR Recursos', 'cgrrecurso.php', 3, 401, 'S', '', '')";}
	if ($dbversion==5144){$sSQL="CREATE TABLE dato02ppt_cta_cgr (dato02codigo varchar(30) NOT NULL, dato02id int NULL DEFAULT 0, dato02nombre varchar(100) NULL, dato02activa int NULL DEFAULT 0)";}
	if ($dbversion==5145){$sSQL="ALTER TABLE dato02ppt_cta_cgr ADD PRIMARY KEY(dato02id)";}
	if ($dbversion==5146){$sSQL=$objDB->sSQLCrearIndice('dato02ppt_cta_cgr', 'dato02ppt_cta_cgr_id', 'dato02codigo', true);}
	if ($dbversion==5147){$sSQL="agregamodulo|402|5|CGR Codigos CUIPO|1|2|3|4|5|6|8";}
	if ($dbversion==5148){$sSQL=$u09."(402, 1, 'CGR Codigos CUIPO', 'cgrcuipo.php', 3, 402, 'S', '', '')";}
	if ($dbversion==5149){$sSQL="agregamodulo|2991|29|Perfiles|1";}
	if ($dbversion==5150){$sSQL=$u09."(2991, 1, 'Perfiles', 'unadperfil.php', 2, 2991, 'S', '', '')";}
	if ($dbversion==5151){$sSQL="agregamodulo|2992|29|Usuarios|1";}
	if ($dbversion==5152){$sSQL=$u09."(2992, 1, 'Usuarios', 'unadusuarios.php', 1, 2992, 'S', '', '')";}
	if ($dbversion==5153){$sSQL="agregamodulo|2993|29|Personas|1";}
	if ($dbversion==5154){$sSQL=$u09."(2993, 1, 'Personas', 'unadpersonas.php', 1, 2993, 'S', '', '')";}
	if ($dbversion==5155){$sSQL="ALTER TABLE unad11terceros ADD unad01autoriza_tel int NULL DEFAULT -1, ADD unad01autoriza_bol int NULL DEFAULT -1, ADD unad01oir_ruv int NULL DEFAULT -1";}
	if ($dbversion==5156){$sSQL="ALTER TABLE ceca26tipoacompana ADD ceca26vencedias int NULL DEFAULT 0, ADD ceca26vencehoras int NULL DEFAULT 0";}
	if ($dbversion==5157){$sSQL="CREATE TABLE ceca46subtipoacomp (ceca46idtipo int NOT NULL, ceca46consec int NOT NULL, ceca46id int NULL DEFAULT 0, ceca46orden int NULL DEFAULT 0, ceca46nombre varchar(50) NULL)";}
	if ($dbversion==5158){$sSQL="ALTER TABLE ceca46subtipoacomp ADD PRIMARY KEY(ceca46id)";}
	if ($dbversion==5159){$sSQL=$objDB->sSQLCrearIndice('ceca46subtipoacomp', 'ceca46subtipoacomp_id', 'ceca46idtipo, ceca46consec', true);}
	if ($dbversion==5160){$sSQL=$objDB->sSQLCrearIndice('ceca46subtipoacomp', 'ceca46subtipoacomp_padre', 'ceca46idtipo');}
	if ($dbversion==5161){$sSQL="agregamodulo|2446|24|Subtipos de acompañamiento|2|3|4|5|6|8";}
	if ($dbversion==5162){$sSQL="INSERT INTO ceca46subtipoacomp (ceca46idtipo, ceca46consec, ceca46id, ceca46orden, ceca46nombre) VALUES (0, 0, 0, 0, '{Ninguno}')";}
	if ($dbversion==5163){$sSQL="agregamodulo|2921|29|Acompañamiento a aspirantes|1";}
	if ($dbversion==5164){$sSQL=$u09."(2921, 1, 'Acompañamiento a aspirantes y egresados', 'saiacompanaest.php', 3000, 2921, 'S', '', '')";}
	if ($dbversion==5165){$sSQL="ALTER TABLE core01estprograma CHANGE core01gradotituloopcion core01gradotituloopcion VARCHAR(500) NULL DEFAULT ''";}
	if ($dbversion==5166){$sSQL="CREATE TABLE dato03ppt_fuente_cgr (dato03codigo varchar(20) NOT NULL, dato03id int NULL DEFAULT 0, dato03nombre varchar(100) NULL)";}
	if ($dbversion==5167){$sSQL="ALTER TABLE dato03ppt_fuente_cgr ADD PRIMARY KEY(dato03id)";}
	if ($dbversion==5168){$sSQL=$objDB->sSQLCrearIndice('dato03ppt_fuente_cgr', 'dato03ppt_fuente_cgr_id', 'dato03codigo', true);}
	if ($dbversion==5169){$sSQL="agregamodulo|403|5|CGR Fuentes|1|2|3|4|5|6|8";}
	if ($dbversion==5170){$sSQL=$u09."(403, 1, 'CGR Fuentes', 'cgrfuente.php', 3, 403, 'S', '', '')";}
	if ($dbversion==5171){$sSQL="CREATE TABLE dato04ppt_uso_cgr (dato04codigo varchar(20) NOT NULL, dato04id int NULL DEFAULT 0, dato04nombre varchar(100) NULL)";}
	if ($dbversion==5172){$sSQL="ALTER TABLE dato04ppt_uso_cgr ADD PRIMARY KEY(dato04id)";}
	if ($dbversion==5173){$sSQL=$objDB->sSQLCrearIndice('dato04ppt_uso_cgr', 'dato04ppt_uso_cgr_id', 'dato04codigo', true);}
	if ($dbversion==5174){$sSQL="agregamodulo|404|5|CGR Usos|1|2|3|4|5|6|8";}
	if ($dbversion==5175){$sSQL=$u09."(404, 1, 'CGR Usos', 'cgrusos.php', 3, 404, 'S', '', '')";}
	//5176 A 5178 quedan libres
	if ($dbversion==5179){$sSQL="agregamodulo|506|5|Cuentas presupuestales|1|2|3|4|5|6|8";}
	if ($dbversion==5180){$sSQL=$u09."(506, 1, 'Cuentas presupuestales', 'pptocuenta.php', 1, 506, 'S', '', '')";}
	if ($dbversion==5181){$sSQL=$unad70."(403,571,'ppto71fuente','ppto71id','ppto71idcgr_fuente','El dato esta incluido en Fuentes presupuestales', '')";}
	if ($dbversion==5182){$sSQL="CREATE TABLE ppto71fuente (ppto71consec int NOT NULL, ppto71id int NULL DEFAULT 0, ppto71nombre varchar(50) NULL, ppto71activo int NULL DEFAULT 0, ppto71idcgr_fuente int NULL DEFAULT 0)";}
	if ($dbversion==5183){$sSQL="ALTER TABLE ppto71fuente ADD PRIMARY KEY(ppto71id)";}
	if ($dbversion==5184){$sSQL=$objDB->sSQLCrearIndice('ppto71fuente', 'ppto71fuente_id', 'ppto71consec', true);}
	if ($dbversion==5185){$sSQL="agregamodulo|571|5|Fuentes|1|2|3|4|5|6|8";}
	if ($dbversion==5186){$sSQL=$u09."(571, 1, 'Fuentes', 'pptofuente.php', 2, 571, 'S', '', '')";}
	if ($dbversion==5187){$sSQL=$unad70."(404,572,'ppto72usos','ppto72id','ppto72idcgr_usos','El dato esta incluido en Usos presupuestales', '')";}
	if ($dbversion==5188){$sSQL="CREATE TABLE ppto72usos (ppto72consec int NOT NULL, ppto72id int NULL DEFAULT 0, ppto72nombre varchar(50) NULL, ppto72activo int NULL DEFAULT 0, ppto72idcgr_usos int NULL DEFAULT 0)";}
	if ($dbversion==5189){$sSQL="ALTER TABLE ppto72usos ADD PRIMARY KEY(ppto72id)";}
	if ($dbversion==5190){$sSQL=$objDB->sSQLCrearIndice('ppto72usos', 'ppto72usos_id', 'ppto72consec', true);}
	if ($dbversion==5191){$sSQL="agregamodulo|572|5|Usos|1|2|3|4|5|6|8";}
	if ($dbversion==5192){$sSQL=$u09."(572, 1, 'Usos', 'pptouso.php', 2, 572, 'S', '', '')";}
	if ($dbversion==5193){$sSQL="CREATE TABLE ppto73arbolconfig (ppto73idarbol int NOT NULL, ppto73vigencia int NOT NULL, ppto73id int NULL DEFAULT 0, ppto73separador varchar(1) NULL, ppto73anchofijo varchar(1) NULL, ppto73nivel1 int NULL DEFAULT 0, ppto73nivel2 int NULL DEFAULT 0, ppto73nivel3 int NULL DEFAULT 0, ppto73nivel4 int NULL DEFAULT 0, ppto73nivel5 int NULL DEFAULT 0, ppto73nivel6 int NULL DEFAULT 0, ppto73nivel7 int NULL DEFAULT 0, ppto73nivel8 int NULL DEFAULT 0)";}
	if ($dbversion==5194){$sSQL="ALTER TABLE ppto73arbolconfig ADD PRIMARY KEY(ppto73id)";}
	if ($dbversion==5195){$sSQL=$objDB->sSQLCrearIndice('ppto73arbolconfig', 'ppto73arbolconfig_id', 'ppto73idarbol, ppto73vigencia', true);}
	if ($dbversion==5196){$sSQL="agregamodulo|573|5|Configurar arboles|1|2|3|4|5|6|8";}
	if ($dbversion==5197){$sSQL=$u09."(573, 1, 'Configurar arboles', 'unadarbolconfig.php', 2, 573, 'S', '', '')";}
	if ($dbversion==5198){$sSQL="CREATE TABLE ppto74arboles (ppto74id int NOT NULL, ppto74nombre varchar(100) NULL)";}
	if ($dbversion==5199){$sSQL="ALTER TABLE ppto74arboles ADD PRIMARY KEY(ppto74id)";}
	if ($dbversion==5200){$sSQL="INSERT INTO ppto74arboles (ppto74id, ppto74nombre) VALUES (0, 'Contable'), (1, 'Rentas'), (2, 'Gastos')";}
	}
if (($dbversion>5200)&&($dbversion<5301)){
	if ($dbversion==5201){$sSQL="CREATE TABLE ppto75separador (ppto75codigo varchar(1) NOT NULL, ppto75nombre varchar(20) NULL)";}
	if ($dbversion==5202){$sSQL="ALTER TABLE ppto75separador ADD PRIMARY KEY(ppto75codigo)";}
	if ($dbversion==5203){$sSQL="INSERT INTO ppto75separador (ppto75codigo, ppto75nombre) VALUES ('.','Punto'), (',','Coma'), ('-','Barra'), ('_','Barra al piso')";}
	if ($dbversion==5204){$sSQL="agregamodulo|12249|22|Reporte de tutores|1|5|6";}
	if ($dbversion==5205){$sSQL=$u09."(12249, 1, 'Reporte de tutores', 'corerpttutores.php', 11, 12249, 'S', '', '')";}
	if ($dbversion==5206){$sSQL="ALTER TABLE core09programa ADD core09publicarpei int NULL DEFAULT 0";}
	if ($dbversion==5207){$sSQL="agregamodulo|3072|30|Aprobar cambio de datos básico|1|2|3|4|5|6|8";}
	if ($dbversion==5208){$sSQL=$u09."(3072, 1, 'Aprobar cambio de datos básicos', 'saiaprobbas.php', 3000, 3072, 'S', '', '')";}
	if ($dbversion==5209){$sSQL="agregamodulo|3400|34|Panel Biblioteca|1";}
	if ($dbversion==5210){$sSQL="agregamodulo|12220|22|Verificacion de planes de estudio|1|2|3|4|5|6";}
	if ($dbversion==5211){$sSQL=$u09."(12220, 1, 'Verificacion de planes de estudio', 'corerptverifica.php', 11, 12220, 'S', '', '')";}
	if ($dbversion==5212){$sSQL="ALTER TABLE ofer08oferta ADD ofer08numestnuevos int NULL DEFAULT 0, ADD ofer08cargath int NULL DEFAULT 0";}
	if ($dbversion==5213){$sSQL="CREATE TABLE gcmo01indicador (gcmo01consec int NOT NULL, gcmo01id int NULL DEFAULT 0, gcmo01nombre varchar(100) NULL, gcmo01publico int NULL DEFAULT 0)";}
	if ($dbversion==5214){$sSQL="ALTER TABLE gcmo01indicador ADD PRIMARY KEY(gcmo01id)";}
	if ($dbversion==5215){$sSQL=$objDB->sSQLCrearIndice('gcmo01indicador', 'gcmo01indicador_id', 'gcmo01consec', true);}
	if ($dbversion==5216){$sSQL="agregamodulo|3701|37|Indicadores|1|2|3|4|5|6|8";}
	if ($dbversion==5217){$sSQL=$u09."(3701, 1, 'Indicadores', 'gcmoindicador.php', 1, 3701, 'S', '', '')";}
	if ($dbversion==5218){$sSQL=$u01."(37, 'SIG', 'Gestión de Procesos del SIG', 'S', 'S', 1, 0, 0)";}
	if ($dbversion==5219){$sSQL=$u08."(3701, 'Indicadores', 'gm.php?id=3701', 'Indicadores', 'Indicadores', 'Indicadores')";}
	if ($dbversion==5220){$sSQL="CREATE TABLE gcmo02periodo (gcmo02idindicador int NOT NULL, gcmo02consec int NOT NULL, gcmo02id int NULL DEFAULT 0, gcmo02nombre varchar(20) NULL, gcmo02fechainicial int NULL DEFAULT 0, gcmo02fechafinal int NULL DEFAULT 0, gcmo02idciclo int NULL DEFAULT 0, gcmo02idperiodo int NULL DEFAULT 0, gcmo02estado int NULL DEFAULT 0, gcmo02fechacargue int NULL DEFAULT 0)";}
	if ($dbversion==5221){$sSQL="ALTER TABLE gcmo02periodo ADD PRIMARY KEY(gcmo02id)";}
	if ($dbversion==5222){$sSQL=$objDB->sSQLCrearIndice('gcmo02periodo', 'gcmo02periodo_id', 'gcmo02idindicador, gcmo02consec', true);}
	if ($dbversion==5223){$sSQL=$objDB->sSQLCrearIndice('gcmo02periodo', 'gcmo02periodo_padre', 'gcmo02idindicador');}
	if ($dbversion==5224){$sSQL="agregamodulo|3702|37|Indicadores - Periodos reporte|1|2|3|4|5|6|8";}
	if ($dbversion==5225){$sSQL="CREATE TABLE gcmo03reporte (gcmo03idindperiodo int NOT NULL, gcmo03consec int NULL DEFAULT 0, gcmo03id int NULL DEFAULT 0, gcmo03idindicador int NULL DEFAULT 0, gcmo03idescuela int NULL DEFAULT 0, gcmo03idprograma int NULL DEFAULT 0, gcmo03idzona int NULL DEFAULT 0, gcmo03idcentro int NULL DEFAULT 0, gcmo03curso int NULL DEFAULT 0, gcmo03estado int NULL DEFAULT 0, gcmo03idpropietario int NULL DEFAULT 0, gcmo03idanalista int NULL DEFAULT 0, gcmo03vrmeta Decimal(15,2) NULL DEFAULT 0, gcmo03vrindicador Decimal(15,2) NULL DEFAULT 0, gcmo03cumplimiento Decimal(15,2) NULL DEFAULT 0, gcmo03analisis Text NULL, gcmo3vrproxmeta Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==5226){$sSQL="ALTER TABLE gcmo03reporte ADD PRIMARY KEY(gcmo03id)";}
	if ($dbversion==5227){$sSQL=$objDB->sSQLCrearIndice('gcmo03reporte', 'gcmo03reporte_id', 'gcmo03idindperiodo', true);}
	if ($dbversion==5228){$sSQL="agregamodulo|3703|37|Análisis de indicadores|1|2|3|4|5|6|8";}
	if ($dbversion==5229){$sSQL=$u09."(3703, 1, 'Análisis de indicadores', 'gcmogestindicador.php', 1, 3703, 'S', '', '')";}
	if ($dbversion==5230){$sSQL="CREATE TABLE gcmo91estadoperiodo (gcmo91id int NOT NULL, gcmo91nombre varchar(50) NULL)";}
	if ($dbversion==5231){$sSQL="ALTER TABLE gcmo91estadoperiodo ADD PRIMARY KEY(gcmo91id)";}
	if ($dbversion==5232){$sSQL="INSERT INTO gcmo91estadoperiodo (gcmo91id, gcmo91nombre) VALUES (0, 'Borrador'), (1, 'En recolección'), (3, 'En Análisis'), (7, 'Publicado')";}
	if ($dbversion==5233){$sSQL="CREATE TABLE gcmo92estadoreporte (gcmo92id int NOT NULL, gcmo92nombre varchar(50) NULL)";}
	if ($dbversion==5234){$sSQL="ALTER TABLE gcmo92estadoreporte ADD PRIMARY KEY(gcmo92id)";}
	if ($dbversion==5235){$sSQL="INSERT INTO gcmo92estadoreporte (gcmo92id, gcmo92nombre) VALUES (0, 'En proyección'), (1, 'En recolección'), (3, 'En análisis'), (5, 'Para aprobar'), (7, 'Aprobado')";}
	if ($dbversion==5236){$sSQL="UPDATE core02estadoprograma SET core02nombre='Suspendido' WHERE core02id=5";}
	if ($dbversion==5237){$sSQL="UPDATE core02estadoprograma SET core02nombre='Expulsado' WHERE core02id=6";}
	if ($dbversion==5238){$sSQL="INSERT INTO saiu60estadotramite (saiu60id, saiu60nombre) VALUES (4, 'En validación de datos')";}
	if ($dbversion==5239){$sSQL="CREATE TABLE saiu70responsabletrami (saiu70idtipotramite int NOT NULL, saiu70numpaso int NOT NULL, saiu70idzona int NOT NULL, saiu70idcentro int NOT NULL, saiu70idescuela int NOT NULL, saiu70idprograma int NOT NULL, saiu70id int NULL DEFAULT 0, saiu70activo int NULL DEFAULT 0, saiu70idunidad int NULL DEFAULT 0, saiu70idgrupotrabajo int NULL DEFAULT 0, saiu70idresponsable int NULL DEFAULT 0)";}
	if ($dbversion==5240){$sSQL="ALTER TABLE saiu70responsabletrami ADD PRIMARY KEY(saiu70id)";}
	if ($dbversion==5241){$sSQL=$objDB->sSQLCrearIndice('saiu70responsabletrami', 'saiu70responsabletrami_id', 'saiu70idtipotramite, saiu70numpaso, saiu70idzona, saiu70idcentro, saiu70idescuela, saiu70idprograma', true);}
	if ($dbversion==5242){$sSQL="agregamodulo|3070|30|Responsables de tramites|1|2|3|4|5|6|8";}
	if ($dbversion==5243){$sSQL=$u09."(3070, 1, 'Responsables de tramites', 'saiuresptramite.php', 2, 3070, 'S', '', '')";}
	//5244 queda libre
	if ($dbversion==5245){$sSQL="ALTER TABLE aure69versionado ADD aure69campus int NULL DEFAULT 0";}
	if ($dbversion==5246){$sSQL="INSERT INTO core70homolestado (core70id, core70nombre) VALUES (21, 'En alistamiento de prueba'), (25, 'Alistamiento terminado'), (30, 'Programada'), (35, 'En presentación'), (40, 'En evaluación'), (97, 'Inasistente'), (98, 'Perdida'), (99, 'Negada')";}
	if ($dbversion==5247){$sSQL="ALTER TABLE core79tipohomolcursodest ADD core79numopcion int NOT NULL DEFAULT 1";}
	if ($dbversion==5248){$sSQL=$objDB->sSQLEliminarIndice('core79tipohomolcursodest', 'core79tipohomolcursodest_id');}
	if ($dbversion==5249){$sSQL=$objDB->sSQLCrearIndice('core79tipohomolcursodest', 'core79tipohomolcursodest_id', 'core79idtipohomol, core79idplanest, core79idcurso, core79numopcion', true);}
	if ($dbversion==5250){$sSQL="ALTER TABLE unad11terceros ADD unad01autoriza_mat int NULL DEFAULT -1, ADD unad01autoriza_bien int NULL DEFAULT -1";}
	if ($dbversion==5251){$sSQL=$objDB->sSQLCrearIndice('core16actamatricula', 'core16actamatricula_estado', 'core16estado');}
	if ($dbversion==5252){$sSQL="INSERT INTO core30estadomatricula (core30id, core30nombre) VALUES (99, 'Anulada')";}
	if ($dbversion==5253){$sSQL="CREATE TABLE aure74tipoencuesta (aure74id int NOT NULL, aure74nombre varchar(50) NULL, aure74id_lg varchar(10) NULL)";}
	if ($dbversion==5254){$sSQL="ALTER TABLE aure74tipoencuesta ADD PRIMARY KEY(aure74id)";}
	if ($dbversion==5255){$sSQL="INSERT INTO aure74tipoencuesta (aure74id, aure74nombre, aure74id_lg) VALUES (1, 'Satisfacción del Servicio SAU', '274_01'), (2, 'Medición de la Deserción', '274_02')";}
	if ($dbversion==5256){$sSQL="agregamodulo|2824|28|Deserción|1|5|6";}
	if ($dbversion==5257){$sSQL=$u09."(2824, 1, 'Deserción', 'rptdesercion.php', 2802, 2824, 'S', '', '')";}
	if ($dbversion==5258){$sSQL="ALTER TABLE core01estprograma CHANGE core01contaprob13 core01desc_cont_encuesta INT NULL DEFAULT 0";}
	if ($dbversion==5259){$sSQL="ALTER TABLE core01estprograma CHANGE core01contaprob14 core01desc_id_encuesta INT NULL DEFAULT 0";}
	//5260 - Queda libre
	if ($dbversion==5261){$sSQL=$objDB->sSQLCrearIndice('usab12historial', 'usab12historial_periodo', 'usab12periodo');}
	if ($dbversion==5262){$sSQL="CREATE TABLE cipa01oferta (cipa01periodo int NOT NULL, cipa01consec int NOT NULL, cipa01id int NOT NULL DEFAULT 0, cipa01nombre varchar(200) NULL, cipa01alcance int NOT NULL DEFAULT 0, cipa01clase int NOT NULL DEFAULT 0, cipa01estado int NOT NULL DEFAULT 0, cipa01escuela int NOT NULL DEFAULT 0, cipa01programa int NOT NULL DEFAULT 0, cipa01zona int NOT NULL DEFAULT 0, cipa01centro int NOT NULL DEFAULT 0, cipa01idcurso int NOT NULL DEFAULT 0, cipa01tematica Text NULL, cipa01est_proyectados int NOT NULL DEFAULT 0, cipa01est_asistentes int NOT NULL DEFAULT 0, cipa01iddocente int NOT NULL DEFAULT 0, cipa01iddocente2 int NOT NULL DEFAULT 0, cipa01iddocente3 int NOT NULL DEFAULT 0, cipa01idmonitor int NOT NULL DEFAULT 0, cipa01idsupervisor int NOT NULL DEFAULT 0, cipa01num_valoraciones int NOT NULL DEFAULT 0, cipa01valoracion Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==5263){$sSQL="ALTER TABLE cipa01oferta ADD PRIMARY KEY(cipa01id)";}
	if ($dbversion==5264){$sSQL=$objDB->sSQLCrearIndice('cipa01oferta', 'cipa01oferta_id', 'cipa01periodo, cipa01consec', true);}
	if ($dbversion==5265){$sSQL="agregamodulo|3801|19|CIPAS - Oferta|1|2|3|4|5|6|8|12|17|1707";}
	if ($dbversion==5266){$sSQL=$u09."(3801, 1, 'Oferta de CIPAS', 'cipasoferta.php', 3801, 3801, 'S', '', '')";}
	if ($dbversion==5267){$sSQL="CREATE TABLE cipa02jornada (cipa02idoferta int NOT NULL, cipa02consec int NOT NULL, cipa02id int NOT NULL DEFAULT 0, cipa02forma int NOT NULL DEFAULT 0, cipa02lugar Text NULL, cipa02link Text NULL, cipa02fecha int NOT NULL DEFAULT 0, cipa02horaini int NOT NULL DEFAULT 0, cipa02minini int NOT NULL DEFAULT 0, cipa02horafin int NOT NULL DEFAULT 0, cipa02minfin int NOT NULL DEFAULT 0, cipa02numinscritos int NOT NULL DEFAULT 0, cipa02numparticipantes int NOT NULL DEFAULT 0)";}
	if ($dbversion==5268){$sSQL="ALTER TABLE cipa02jornada ADD PRIMARY KEY(cipa02id)";}
	if ($dbversion==5269){$sSQL=$objDB->sSQLCrearIndice('cipa02jornada', 'cipa02jornada_id', 'cipa02idoferta, cipa02consec', true);}
	if ($dbversion==5270){$sSQL=$objDB->sSQLCrearIndice('cipa02jornada', 'cipa02jornada_padre', 'cipa02idoferta');}
	if ($dbversion==5271){$sSQL="agregamodulo|3802|19|CIPAS - Oferta - Jornadas|1|2|3|4|5|6|8";}
	if ($dbversion==5272){$sSQL="agregamodulo|3803|19|CIPAS - Oferta - Cupos|1|2|3|4|5|6|8";}
	if ($dbversion==5273){$sSQL="CREATE TABLE cipa11estado (cipa11id int NOT NULL, cipa11nombre varchar(50) NULL)";}
	if ($dbversion==5274){$sSQL="ALTER TABLE cipa11estado ADD PRIMARY KEY(cipa11id)";}
	if ($dbversion==5275){$sSQL="INSERT INTO cipa11estado (cipa11id, cipa11nombre) VALUES (0, 'Borrador'), (1, 'Ofertado'), (7, 'Terminado'), (8, 'Cancelado'), (9, 'Nulo')";}
	if ($dbversion==5276){$sSQL="CREATE TABLE cipa12estadosesion (cipa12id int NOT NULL, cipa12nombre varchar(50) NULL)";}
	if ($dbversion==5277){$sSQL="ALTER TABLE cipa12estadosesion ADD PRIMARY KEY(cipa12id)";}
	if ($dbversion==5278){$sSQL="INSERT INTO cipa12estadosesion (cipa12id, cipa12nombre) VALUES (0, 'Programada'), (3, 'Confirmada'), (5, 'En desarrollo'), (7, 'Finalizada'), (8, 'Cancelada')";}
	if ($dbversion==5279){$sSQL="CREATE TABLE cipa13alcance (cipa13id int NOT NULL, cipa13nombre varchar(50) NULL)";}
	if ($dbversion==5280){$sSQL="ALTER TABLE cipa13alcance ADD PRIMARY KEY(cipa13id)";}
	if ($dbversion==5281){$sSQL="INSERT INTO cipa13alcance (cipa13id, cipa13nombre) VALUES (0, 'Nacional'), (1, 'Zonal'), (2, 'Centro'), (11, 'Escuela'), (12, 'Programa'), (21, 'Escuela - Zona'), (22, 'Programa - Zona'), (31, 'Por curso'), (32, 'Por curso - Zona'), (33, 'Por Curso - Tutor'), (34, 'Por Curso - Centro'), (41, 'Escuela - Centro'), (42, 'Programa - Centro')";}
	if ($dbversion==5282){$sSQL="CREATE TABLE cipa14clasecipas (cipa14consec int NOT NULL, cipa14id int NOT NULL DEFAULT 0, cipa14orden int NOT NULL DEFAULT 0, cipa14activo int NOT NULL DEFAULT 0, cipa14nombre varchar(50) NULL)";}
	if ($dbversion==5283){$sSQL="ALTER TABLE cipa14clasecipas ADD PRIMARY KEY(cipa14id)";}
	if ($dbversion==5284){$sSQL=$objDB->sSQLCrearIndice('cipa14clasecipas', 'cipa14clasecipas_id', 'cipa14consec', true);}
	if ($dbversion==5285){$sSQL="agregamodulo|3814|19|Clases de CIPAS|1|2|3|4|5|6|8";}
	if ($dbversion==5286){$sSQL=$u09."(3814, 1, 'Clases de CIPAS', 'cipatipo.php', 2, 3814, 'S', '', '')";}
	if ($dbversion==5287){$sSQL=$u08."(3801, 'CIPAS', 'gm.php?id=3801', 'CIPAS', 'CIPAS', 'CIPAS')";}
	if ($dbversion==5288){$sSQL="add_campos|cipa01oferta|cipa01fechatermina int NOT NULL DEFAULT 0";}
	if ($dbversion==5289){$sSQL="add_campos|cara15factordeserta|cara15vaaencuesta int NOT NULL DEFAULT 0|cara15ordenencuesta int NOT NULL DEFAULT 0|cara15imagenencuesta varchar(20) NULL|cara15textoencuesta varchar(100) NULL";}
	if ($dbversion==5290){$sql="INSERT INTO cara22gruposfactores (cara22id, cara22nombre) VALUES (5, 'Económicos')";}
	if ($dbversion==5291){$sSQL="ALTER TABLE core01estprograma CHANGE core01contaprob15 core01desc_fecharesp INT NULL DEFAULT 0";}
	if ($dbversion==5292){$sSQL="ALTER TABLE core01estprograma CHANGE core01contaprob16 core01desc_continua INT NULL DEFAULT -1";}
	if ($dbversion==5293){$sSQL="add_campos|cipa01oferta|cipa01proximafecha int NOT NULL DEFAULT 0";}
	if ($dbversion==5294){$sSQL=$objDB->sSQLCrearIndice('cipa01oferta', 'cipa01oferta_estado', 'cipa01estado');}
	if ($dbversion==5295){$sSQL=$objDB->sSQLCrearIndice('cipa01oferta', 'cipa01oferta_termina', 'cipa01fechatermina');}
	if ($dbversion==5296){$sSQL=$objDB->sSQLCrearIndice('cipa01oferta', 'cipa01oferta_alcance', 'cipa01alcance');}
	if ($dbversion==5297){$sSQL="CREATE TABLE aure75grupocorreo (aure75consec int NOT NULL, aure75id int NOT NULL DEFAULT 0, aure75activo int NOT NULL DEFAULT 0, aure75orden int NOT NULL DEFAULT 0, aure75nombre varchar(50) NULL)";}
	if ($dbversion==5298){$sSQL="ALTER TABLE aure75grupocorreo ADD PRIMARY KEY(aure75id)";}
	if ($dbversion==5299){$sSQL=$objDB->sSQLCrearIndice('aure75grupocorreo', 'aure75grupocorreo_id', 'aure75consec', true);}
	if ($dbversion==5300){$sSQL="agregamodulo|275|1|Grupos de correos|1|2|3|4|5|6|8";}
	}
if (($dbversion>5300)&&($dbversion<5401)){
	if ($dbversion==5301){$sSQL=$u09."(275, 1, 'Grupos de correos', 'auregrupocorreo.php', 3, 275, 'S', '', '')";}
	if ($dbversion==5302){$sSQL="CREATE TABLE aure76grupodominio (aure76idgrupo int NOT NULL, aure76dominio varchar(50) NOT NULL, aure76id int NOT NULL DEFAULT 0, aure76activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==5303){$sSQL="ALTER TABLE aure76grupodominio ADD PRIMARY KEY(aure76id)";}
	if ($dbversion==5304){$sSQL=$objDB->sSQLCrearIndice('aure76grupodominio', 'aure76grupodominio_id', 'aure76idgrupo, aure76dominio', true);}
	if ($dbversion==5305){$sSQL=$objDB->sSQLCrearIndice('aure76grupodominio', 'aure76grupodominio_padre', 'aure76idgrupo');}
	if ($dbversion==5306){$sSQL="agregamodulo|276|1|Grupos de correos - Dominios|1|2|3|4|5|6|8";}
	if ($dbversion==5307){$sSQL="INSERT INTO aure75grupocorreo (aure75consec, aure75id, aure75activo, aure75orden, aure75nombre) VALUES (-1, -1, 0, 0, '{Pendiente}'), (0, 0, 0, 0, '{Ninguno}')";}
	if ($dbversion==5308){$sSQL="add_campos|unad69smtp|unad69idgrupo int NOT NULL DEFAULT 0";}
	if ($dbversion==5309){$sSQL="add_campos|unad11terceros|unad11idgrupocorreo int NOT NULL DEFAULT -1";}
	if ($dbversion==5310){$sSQL="agregamodulo|3815|20|CIPAS - Campus Virtual|1";}
	if ($dbversion==5311){$sSQL="add_campos|cipa02jornada|cipa02tematica Text NULL";}
	if ($dbversion==5312){$sSQL="CREATE TABLE aure72preinscripcion (aure72correo varchar(50) NULL, aure72id int NOT NULL DEFAULT 0, aure72pais varchar(3) NULL, aure72idestado int NOT NULL DEFAULT 0, aure72idtercero int NOT NULL DEFAULT 0, aure72tipodoc varchar(2) NOT NULL, aure72doc varchar(20) NOT NULL, aure72fecha int NOT NULL, aure72ip varchar(20) NULL, aure72llaveacceso varchar(20) NULL, aure72hora int NOT NULL DEFAULT 0, aure72minuto int NOT NULL DEFAULT 0, aure72punto varchar(100) NULL)";}
	if ($dbversion==5313){$sSQL="ALTER TABLE aure72preinscripcion ADD PRIMARY KEY(aure72id)";}
	if ($dbversion==5314){$sSQL=$objDB->sSQLCrearIndice('aure72preinscripcion', 'aure72preinscripcion_id', 'aure72correo', true);}
	if ($dbversion==5315){$sSQL="agregamodulo|272|1|Registro de usuarios|1|3|5|6";}
	if ($dbversion==5316){$sSQL=$u09."(272, 1, 'Registro de usuarios', 'unadregistro.php', 1501, 272, 'S', '', '')";}
	if ($dbversion==5317){$sSQL=$objDB->sSQLCrearIndice('aure72preinscripcion', 'aure72preinscripcion_documento', 'aure72tipodoc, aure72doc');}
	if ($dbversion==5318){$sSQL="CREATE TABLE ppto12procesos (ppto12id int NOT NULL, ppto12nombre varchar(50) NULL)";}
	if ($dbversion==5319){$sSQL="ALTER TABLE ppto12procesos ADD PRIMARY KEY(ppto12id)";}
	if ($dbversion==5320){$sSQL="INSERT INTO ppto12procesos (ppto12id, ppto12nombre) VALUES (1, 'Apropiación Inicial Rentas'), (3, 'Causación del ingreso'), (4, 'Adiciones (Rentas)'), (5, 'Reducciones (Rentas)'), (6, 'Traslados (Rentas)'), (7, 'Recaudo'), (8, 'Anulación de recaudo'), (10, 'Apropiación Inicial Gastos'), (11, 'Adiciones (Gastos)'), (12, 'Reducciones (Gastos)'), (13, 'Traslados (Gastos)'), (14, 'Creditos'), (15, 'Contracreditos'), (21, 'Solicitud de CDP'), (22, 'CDP'), (23, 'Cancelación de CDP'), (31, 'Registro Presupuestal'), (32, 'Cancelación de Registro'), (41, 'Orde de pago'), (42, 'Cancelación de Orde de pago'), (43, 'Pago'), (44, 'Anulación del Pago'), (51, 'Consitución de Reserva'), (52, 'Consitución de CxP'), (61, 'Orde de pago de reserva'), (62, 'Anulación de reserva'), (71, 'Orden de pago de reserva'), (72, 'Pago de CxP')";}
	if ($dbversion==5321){$sSQL="CREATE TABLE ppto13tipodocumento (ppto13consec int NOT NULL, ppto13id int NOT NULL DEFAULT 0, ppto13orden int NOT NULL DEFAULT 0, ppto13nombre varchar(50) NULL, ppto13proceso int NOT NULL DEFAULT 0)";}
	if ($dbversion==5322){$sSQL="ALTER TABLE ppto13tipodocumento ADD PRIMARY KEY(ppto13id)";}
	if ($dbversion==5323){$sSQL=$objDB->sSQLCrearIndice('ppto13tipodocumento', 'ppto13tipodocumento_id', 'ppto13consec', true);}
	if ($dbversion==5324){$sSQL="agregamodulo|513|5|Tipos de documentos|1|2|3|4|5|6|8";}
	if ($dbversion==5325){$sSQL=$u09."(513, 1, 'Tipos de documentos', 'pptotipodoc.php', 2, 513, 'S', '', '')";}
	if ($dbversion==5326){$sSQL=$u08."(501, 'Rentas', 'gm.php?id=501', 'Rentas', 'Rents', 'Rendas'), (502, 'Gastos', 'gm.php?id=502', 'Gastos', 'Expenses', 'Despesas')";}
	if ($dbversion==5327){$sSQL=$u01."(40, 'INVENTARIOS', 'Sistema de Gestión de Inventarios', 'N', 'S', 1, 0, 0)";}
	if ($dbversion==5328){$sSQL="CREATE TABLE hera71cpc (hera71version int NOT NULL, hera71codigo varchar(20) NOT NULL, hera71id int NOT NULL DEFAULT 0, hera71movimiento int NOT NULL DEFAULT 0, hera71frecuente int NOT NULL DEFAULT 0, hera71unidadmedidad int NOT NULL DEFAULT 0, hera71nombre Text NULL)";}
	if ($dbversion==5329){$sSQL="ALTER TABLE hera71cpc ADD PRIMARY KEY(hera71version, hera71codigo)";}
	if ($dbversion==5330){$sSQL="agregamodulo|4071|40|CPC|1|2|3|4|5|6";}
	if ($dbversion==5331){$sSQL=$u09."(4071, 1, 'CPC', 'heracpc.php', 1, 4071, 'S', '', '')";}
	if ($dbversion==5332){$sSQL="CREATE TABLE hera72unidadmedida (hera72consec int NOT NULL, hera72id int NOT NULL DEFAULT 0, hera72orden int NOT NULL DEFAULT 0, hera72sigla varchar(10) NULL, hera72nombre varchar(50) NULL)";}
	if ($dbversion==5333){$sSQL="ALTER TABLE hera72unidadmedida ADD PRIMARY KEY(hera72consec)";}
	if ($dbversion==5334){$sSQL="agregamodulo|4072|40|Unidades de medida|1|2|3|4|5|6";}
	if ($dbversion==5335){$sSQL=$u09."(4072, 1, 'Unidades de medida', 'heraunidadmedida.php', 2, 4072, 'S', '', '')";}
	if ($dbversion==5336){$sSQL=$unad70."(4072,4071,'hera71cpc','hera71id','hera71unidadmedidad','El dato esta incluido en CPC', '')";}
	if ($dbversion==5337){$sSQL="INSERT INTO hera72unidadmedida (hera72consec, hera72id, hera72orden, hera72sigla, hera72nombre) VALUES (0, 0, 0, '{NA}', '{No Aplica}')";}
	if ($dbversion==5338){$sSQL="DROP TABLE ppto06cuentas";}
	if ($dbversion==5339){$sSQL="CREATE TABLE ppto06cuentas (ppto06tipo int NOT NULL, ppto06vigencia int NOT NULL, ppto06codigo varchar(50) NOT NULL, ppto06derivada int NOT NULL, ppto06recurso int NOT NULL, ppto06idcpc int NOT NULL, ppto06id int NOT NULL DEFAULT 0, ppto06nombre varchar(250) NULL, ppto06movimiento int NOT NULL DEFAULT 0, ppto06existe int NOT NULL DEFAULT 0, ppto06nivel int NOT NULL DEFAULT 0, ppto06idpadre int NOT NULL DEFAULT 0, ppto06idctacgr int NOT NULL DEFAULT 0, ppto06idctasnies int NOT NULL DEFAULT 0, ppto06idfuente int NOT NULL DEFAULT 0, ppto06iduso int NOT NULL DEFAULT 0, ppto06situafondos int NOT NULL DEFAULT 0, ppto06total_aforo Decimal(15,2) NULL DEFAULT 0, ppto06tota_adicion Decimal(15,2) NULL DEFAULT 0, ppto06total_reduccion Decimal(15,2) NULL DEFAULT 0, ppto06total_aplaza Decimal(15,2) NULL DEFAULT 0, ppto06total_creditos Decimal(15,2) NULL DEFAULT 0, ppto06total_contracred Decimal(15,2) NULL DEFAULT 0, ppto06total_recaudo Decimal(15,2) NULL DEFAULT 0, ppto06total_rec_sinfon Decimal(15,2) NULL DEFAULT 0, ppto06total_devolucion Decimal(15,2) NULL DEFAULT 0, ppto06total_dev_sinfon Decimal(15,2) NULL DEFAULT 0, ppto06total_dispon Decimal(15,2) NULL DEFAULT 0, ppto06total_dispon_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_registros Decimal(15,2) NULL DEFAULT 0, ppto06total_reg_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_obligacion Decimal(15,2) NULL DEFAULT 0, ppto06total_oblig_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_pagos Decimal(15,2) NULL DEFAULT 0, ppto06total_reserva Decimal(15,2) NULL DEFAULT 0, ppto06total_res_reg_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_res_oblig Decimal(15,2) NULL DEFAULT 0, ppto06total_res_oblig_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_cxp Decimal(15,2) NULL DEFAULT 0, ppto06total_cxp_pago Decimal(15,2) NULL DEFAULT 0, ppto06cpc int NOT NULL DEFAULT 0, ppto06descripcion Text NULL, ppto06vistacodigo varchar(60) NULL)";}
	if ($dbversion==5340){$sSQL="ALTER TABLE ppto06cuentas ADD PRIMARY KEY(ppto06id)";}
	if ($dbversion==5341){$sSQL=$objDB->sSQLCrearIndice('ppto06cuentas', 'ppto06cuentas_id', 'ppto06tipo, ppto06vigencia, ppto06codigo, ppto06derivada, ppto06recurso, ppto06idcpc', true);}
	if ($dbversion==5342){$sSQL="INSERT INTO hera71cpc (hera71version, hera71codigo, hera71id, hera71movimiento, hera71frecuente, hera71unidadmedidad, hera71nombre) VALUES (0, '', 0, 0, 0, 0, '{Ninguno}')";}
	if ($dbversion==5343){$sSQL="CREATE TABLE ppto14documento (ppto14vigencia int NOT NULL, ppto14tipodoc int NOT NULL, ppto14consec int NOT NULL, ppto14id int NOT NULL DEFAULT 0, ppto14tipoppto int NOT NULL DEFAULT 0, ppto14idclase int NOT NULL DEFAULT 0, ppto14estado int NOT NULL DEFAULT 0, ppto14fecha int NOT NULL DEFAULT 0, ppto14ordendia int NOT NULL DEFAULT 0, ppto14idbeneficiario int NOT NULL DEFAULT 0, ppto14detalle Text NULL, ppto14idprevio int NOT NULL DEFAULT 0, ppto14idproceso int NOT NULL DEFAULT 0, ppto14idconvenio int NOT NULL DEFAULT 0, ppto14idsistema int NOT NULL DEFAULT 0, ppto14cont_vigencia int NOT NULL DEFAULT 0, ppto14cont_id int NOT NULL DEFAULT 0, ppto14tes_proceso int NOT NULL DEFAULT 0, ppto14tes_id int NOT NULL DEFAULT 0, ppto14idusuario int NOT NULL DEFAULT 0, ppto14fechadocumento int NOT NULL DEFAULT 0, ppto14idactoadmin int NOT NULL DEFAULT 0)";}
	if ($dbversion==5344){$sSQL="ALTER TABLE ppto14documento ADD PRIMARY KEY(ppto14id)";}
	if ($dbversion==5345){$sSQL=$objDB->sSQLCrearIndice('ppto14documento', 'ppto14documento_id', 'ppto14vigencia, ppto14tipodoc, ppto14consec', true);}
	if ($dbversion==5346){$sSQL="agregamodulo|514|5|Apropiación Gasto|1|2|3|4|5|6|8|17";}
	if ($dbversion==5347){$sSQL=$u09."(514, 1, 'Apropiación de Gastos', 'pptoapropiagasto.php', 502, 514, 'S', '', '')";}
	if ($dbversion==5348){$sSQL="agregamodulo|515|5|Apropiación Gasto - Movimiento|1|2|3|4|5|6|8";}
	if ($dbversion==5349){$sSQL="CREATE TABLE ppto08soldisponib (ppto08vigencia int NOT NULL, ppto08consec int NOT NULL, ppto08id int NOT NULL DEFAULT 0, ppto08fecha int NOT NULL DEFAULT 0, ppto08estado int NOT NULL DEFAULT 0, ppto08idsede int NOT NULL DEFAULT 0, ppto08idoficina int NOT NULL DEFAULT 0, ppto08idunidadfuncional int NOT NULL DEFAULT 0, ppto08idfractal int NOT NULL DEFAULT 0, ppto08idconvenio int NOT NULL DEFAULT 0, ppto08idsolicitante int NOT NULL DEFAULT 0, ppto08objeto Text NULL, ppto08actividades Text NULL, ppto08vrtotal Decimal(15,2) NULL DEFAULT 0, ppto08fecharadicacion int NOT NULL DEFAULT 0, ppto08asignada int NOT NULL DEFAULT 0, ppto08fechaavalppto int NOT NULL DEFAULT 0, ppto08idjefeunidad int NOT NULL DEFAULT 0, ppto08fechaavaloficina int NOT NULL DEFAULT 0, ppto08firma_mesa int NOT NULL DEFAULT 0, ppto08firma_plane int NOT NULL DEFAULT 0, ppto08firma_macro int NOT NULL DEFAULT 0, ppto08firma_infra int NOT NULL DEFAULT 0, ppto08idavalmesatecnica int NOT NULL DEFAULT 0, ppto08fechaaval_mesa int NOT NULL DEFAULT 0, ppto08idavalplaneacion int NOT NULL DEFAULT 0, ppto08fechaaval_plan int NOT NULL DEFAULT 0, ppto08idmacropoyecto int NOT NULL DEFAULT 0, ppto08idavalmacroproyecto int NOT NULL DEFAULT 0, ppto08fechaaval_macro int NOT NULL DEFAULT 0, ppto08idavalinfraestructura int NOT NULL DEFAULT 0, ppto08fechaaval_infra int NOT NULL DEFAULT 0, ppto08aval_comite int NOT NULL DEFAULT 0, ppto08fechaaval_comite int NOT NULL DEFAULT 0, ppto08fechaaprobacion int NOT NULL DEFAULT 0, ppto08iddisponibilidad int NOT NULL DEFAULT 0)";}
	if ($dbversion==5350){$sSQL="ALTER TABLE ppto08soldisponib ADD PRIMARY KEY(ppto08id)";}
	if ($dbversion==5351){$sSQL=$objDB->sSQLCrearIndice('ppto08soldisponib', 'ppto08soldisponib_id', 'ppto08vigencia, ppto08consec', true);}
	if ($dbversion==5352){$sSQL="agregamodulo|508|5|Solicitud disponibilidad|1|2|3|4|5|6|8|17|1707";}
	if ($dbversion==5353){$sSQL=$u09."(508, 1, 'Solicitudes de disponibilidad presupuestal', 'pptosoldispon.php', 503, 515, 'S', '', '')";}
	if ($dbversion==5354){$sSQL="CREATE TABLE ppto09solrubrodesc (ppto09idsolicitud int NOT NULL, ppto09idrubro int NOT NULL, ppto09idrecurso int NOT NULL, ppto09idcpc int NOT NULL, ppto09id int NOT NULL DEFAULT 0, ppto09vrsolicitado Decimal(15,2) NULL DEFAULT 0, ppto09vrdisponibilidad Decimal(15,2) NULL DEFAULT 0, ppto09idrecursoorigen int NOT NULL DEFAULT 0, ppto09idmov int NOT NULL DEFAULT 0)";}
	if ($dbversion==5355){$sSQL="ALTER TABLE ppto09solrubrodesc ADD PRIMARY KEY(ppto09id)";}
	if ($dbversion==5356){$sSQL=$objDB->sSQLCrearIndice('ppto09solrubrodesc', 'ppto09solrubrodesc_id', 'ppto09idsolicitud, ppto09idrubro, ppto09idrecurso, ppto09idcpc', true);}
	if ($dbversion==5357){$sSQL=$objDB->sSQLCrearIndice('ppto09solrubrodesc', 'ppto09solrubrodesc_padre', 'ppto09idsolicitud');}
	if ($dbversion==5358){$sSQL="agregamodulo|509|5|Solicitud disp - Cuentas|1|2|3|4|5|6|8";}
	if ($dbversion==5359){$sSQL="CREATE TABLE ppto11solanotaciones (ppto11idsolicitud int NOT NULL, ppto11consec int NOT NULL, ppto11id int NOT NULL DEFAULT 0, ppto11nota Text NULL, ppto11usuario int NOT NULL DEFAULT 0, ppto11fecha int NOT NULL DEFAULT 0, ppto11hora int NOT NULL DEFAULT 0, ppto11minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==5360){$sSQL="ALTER TABLE ppto11solanotaciones ADD PRIMARY KEY(ppto11id)";}
	if ($dbversion==5361){$sSQL=$objDB->sSQLCrearIndice('ppto11solanotaciones', 'ppto11solanotaciones_id', 'ppto11idsolicitud, ppto11consec', true);}
	if ($dbversion==5362){$sSQL=$objDB->sSQLCrearIndice('ppto11solanotaciones', 'ppto11solanotaciones_padre', 'ppto11idsolicitud');}
	if ($dbversion==5363){$sSQL="agregamodulo|511|5|Solicitud disp - Anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==5364){$sSQL="CREATE TABLE ppto10estadosol (ppto10id int NOT NULL, ppto10nombre varchar(50) NULL)";}
	if ($dbversion==5365){$sSQL="ALTER TABLE ppto10estadosol ADD PRIMARY KEY(ppto10id)";}
	if ($dbversion==5366){$sSQL="INSERT INTO ppto10estadosol (ppto10id, ppto10nombre) VALUES (0, 'Borrador'), (1, 'Devuelta'), (7, 'Aval de presupuesto'), (11, 'Aval de la oficina'), (21, 'Aprobaciones'), (25, 'Rechazada Planeación'), (26, 'Rechazada Mesa Técnica'), (27, 'Rechazada Infraestructura'), (28, 'Rechazada convenio'), (31, 'En comité de sostenibilidad'), (32, 'Para ajustes'), (33, 'Aplazada'), (35, 'Rechazada'), (41, 'Para CDP'), (42, 'CDP Expedido')";}
	if ($dbversion==5367){$sSQL="agregamodulo|516|5|Apropiación Rentas|1|2|3|4|5|6|8|17";}
	if ($dbversion==5368){$sSQL=$u09."(516, 1, 'Apropiación de Rentas', 'pptoapropiarenta.php', 501, 516, 'S', '', '')";}
	if ($dbversion==5369){$sSQL="agregamodulo|517|5|Apropiación Renta - Movimiento|1|2|3|4|5|6|8";}
	if ($dbversion==5370){$sSQL="DROP TABLE ofes02ediciones";}
	if ($dbversion==5371){$sSQL="CREATE TABLE ofes02ediciones (ofes02idpreoferta int NOT NULL, ofes02numedicion int NOT NULL, ofes02id int NOT NULL DEFAULT 0, ofes02estado int NOT NULL DEFAULT 0, ofes02fechainialmat int NOT NULL DEFAULT 0, ofes02fechafinalmat int NOT NULL DEFAULT 0, ofes02idnav int NOT NULL DEFAULT 0, ofes02idmoodle int NOT NULL DEFAULT 0, ofes02nombrecurso varchar(20) NULL, ofes02idoferta int NOT NULL DEFAULT 0, ofes02num_inscritos int NOT NULL DEFAULT 0, ofes02num_certificados int NOT NULL DEFAULT 0, ofes02num_abandonos int NOT NULL DEFAULT 0)";}
	if ($dbversion==5372){$sSQL="ALTER TABLE ofes02ediciones ADD PRIMARY KEY(ofes02id)";}
	if ($dbversion==5373){$sSQL=$objDB->sSQLCrearIndice('ofes02ediciones', 'ofes02ediciones_id', 'ofes02idpreoferta, ofes02numedicion', true);}
	if ($dbversion==5374){$sSQL=$objDB->sSQLCrearIndice('ofes02ediciones', 'ofes02ediciones_padre', 'ofes02idpreoferta');}
	if ($dbversion==5375){$sSQL="agregamodulo|1802|17|Ediciones|1|2|3|4|5|6|8";}
	if ($dbversion==5376){$sSQL="CREATE TABLE ofes03estadoedicion (ofes03id int NOT NULL, ofes03nombre varchar(20) NULL)";}
	if ($dbversion==5377){$sSQL="ALTER TABLE ofes03estadoedicion ADD PRIMARY KEY(ofes03id)";}
	if ($dbversion==5378){$sSQL="INSERT INTO ofes03estadoedicion (ofes03id, ofes03nombre) VALUES (0, 'Proyectada'), (3, 'En alistamiento'), (5, 'Para matricula'), (7, 'Operativa'), (9, 'Cerrada')";}
	if ($dbversion==5379){$sSQL="CREATE TABLE corf60inscripcion (corf60idtercero int NOT NULL, corf60idperiodo int NOT NULL, corf60curso int NOT NULL, corf60numedicion int NOT NULL, corf60id int NOT NULL DEFAULT 0, corf60estado int NOT NULL DEFAULT 0, corf60idedicion int NOT NULL DEFAULT 0, corf60fechainscripicion int NOT NULL DEFAULT 0, corf60fechavence int NOT NULL DEFAULT 0, corf60fechatermina int NOT NULL DEFAULT 0, corf60idconvenio int NOT NULL DEFAULT 0, corf60estadopago int NOT NULL DEFAULT 0, corf60idfactura int NOT NULL DEFAULT 0, corf60idproducto int NOT NULL DEFAULT 0, corf60valor Decimal(15,2) NULL DEFAULT 0, corf60valorconvenio Decimal(15,2) NULL DEFAULT 0, corf60valordescuento Decimal(15,2) NULL DEFAULT 0, corf60calificacion int NOT NULL DEFAULT 0, corf60nivelformacion int NOT NULL DEFAULT 0, corf60tipocertificado int NOT NULL DEFAULT 0, corf60numcertificado int NOT NULL DEFAULT 0, corf60codigoverifica varchar(20) NULL, corf60ultimoingreso int NOT NULL DEFAULT 0, corf60numsaltos int NOT NULL DEFAULT 0, corf60numdias int NOT NULL DEFAULT 0)";}
	if ($dbversion==5380){$sSQL="ALTER TABLE corf60inscripcion ADD PRIMARY KEY(corf60id)";}
	if ($dbversion==5381){$sSQL=$objDB->sSQLCrearIndice('corf60inscripcion', 'corf60inscripcion_id', 'corf60idtercero, corf60idperiodo, corf60curso, corf60numedicion', true);}
	if ($dbversion==5382){$sSQL="agregamodulo|12260|22|Inscripciones MOOC|1|2|3|4|5|6|8";}
	if ($dbversion==5383){$sSQL=$u09."(12260, 1, 'Inscripciones MOOC', 'coreinscripciones.php', 2201, 12260, 'S', '', '')";}
	if ($dbversion==5384){$sSQL="CREATE TABLE corf61estadoinscmooc (corf61id int NOT NULL, corf61nombre varchar(20) NULL)";}
	if ($dbversion==5385){$sSQL="ALTER TABLE corf61estadoinscmooc ADD PRIMARY KEY(corf61id)";}
	if ($dbversion==5386){$sSQL="INSERT INTO corf61estadoinscmooc (corf61id, corf61nombre) VALUES (0, 'Inscrito'), (3, 'En desarrollo'), (5, 'Terminado'), (6, 'Genera Recibo'), (7, 'Certificado'), (9, 'Abandonado')";}
	if ($dbversion==5387){$sSQL="CREATE TABLE corf62estadopagomooc (corf62id int NOT NULL, corf62nombre varchar(20) NULL)";}
	if ($dbversion==5388){$sSQL="ALTER TABLE corf62estadopagomooc ADD PRIMARY KEY(corf62id)";}
	if ($dbversion==5389){$sSQL="INSERT INTO corf62estadopagomooc (corf62id, corf62nombre) VALUES (0, 'Gratuito'), (5, 'Pago por convenio'), (6, 'Genero recibo'), (7, 'Pago confirmado')";}
	if ($dbversion==5390){$sSQL="add_campos|unad11terceros|unad11identidad_verifica int NOT NULL DEFAULT 0|unad11identidad_fecha int NOT NULL DEFAULT 0";}
	if ($dbversion==5391){$sSQL="agregamodulo|12263|22|Importar Históricos|1|3|5|6";}
	if ($dbversion==5392){$sSQL=$u09."(12263, 1, 'Importar Históricos', 'corehistoricos.php', 1501, 2202, 'S', '', '')";}
	if ($dbversion==5393){$sSQL="add_campos|unad40curso|unad40info_presenta Text NULL|unad40info_video varchar(250) NULL|unad40semestre int NOT NULL DEFAULT 0|unad40info_pres_url varchar(250) NULL";}
	if ($dbversion==5394){$sSQL="agregamodulo|1804|17|Oferta Abierta|1|2|3|4|5|6|8";}
	if ($dbversion==5395){$sSQL=$u09."(1804, 1, 'Oferta Abierta', 'oferofertaabierta.php', 1704, 1804, 'S', '', '')";}
	if ($dbversion==5396){$sSQL="agregamodulo|1805|17|Oferta Abierta - Ediciones|1|2|3|4|5|6|8";}
	if ($dbversion==5397){$sSQL="add_campos|cipa01oferta|cipa01horatermina int NOT NULL DEFAULT 0|cipa01mintermina int NOT NULL DEFAULT 0";}
	if ($dbversion==5398){$sSQL="add_campos|ofes01preoferta|ofes01tipooferta int NOT NULL DEFAULT 0";}
	if ($dbversion==5399){$sSQL="INSERT INTO unad46tipoperiodo (unad46id, unad46nombre) VALUES (12, 'Matricula Abierta')";}
	if ($dbversion==5400){$sSQL="add_campos|unad40curso|unad40matriculaabierta int NOT NULL DEFAULT 0";}
	}
if (($dbversion>5400)&&($dbversion<5501)){
	if ($dbversion==5401){$sSQL="add_campos|ofes01preoferta|ofes01puntajeaprueba int NOT NULL DEFAULT 300|ofes01idcertificado int NOT NULL DEFAULT 0";}
	if ($dbversion==5402){$sSQL="DROP TABLE corf42contenidos";}
	if ($dbversion==5403){$sSQL="DROP TABLE corf43estudiohomol";}
	if ($dbversion==5404){$sSQL="CREATE TABLE ppto76grupocuenta (ppto76consec int NOT NULL, ppto76id int NOT NULL DEFAULT 0, ppto76tipocuenta int NOT NULL DEFAULT 0, ppto76activa int NOT NULL DEFAULT 0, ppto76orden int NOT NULL DEFAULT 0, ppto76nombre varchar(250) NULL)";}
	if ($dbversion==5405){$sSQL="ALTER TABLE ppto76grupocuenta ADD PRIMARY KEY(ppto76id)";}
	if ($dbversion==5406){$sSQL=$objDB->sSQLCrearIndice('ppto76grupocuenta', 'ppto76grupocuenta_id', 'ppto76consec', true);}
	if ($dbversion==5407){$sSQL="agregamodulo|576|5|Grupos de cuentas|1|2|3|4|5|6|8";}
	if ($dbversion==5408){$sSQL=$u09."(576, 1, 'Grupos de cuentas', 'pptogrupocuenta.php', 2, 576, 'S', '', '')";}
	if ($dbversion==5409){$sSQL="add_campos|ppto06cuentas|ppto06grupocuenta int NOT NULL DEFAULT 0";}
	if ($dbversion==5410){$sSQL="INSERT INTO ppto76grupocuenta (ppto76consec, ppto76id, ppto76tipocuenta, ppto76activa, ppto76orden, ppto76nombre) VALUES (0, 0, 0, 0, 0, '{Ninguno}')";}
	if ($dbversion==5411){$sSQL="add_campos|ppto08soldisponib|ppto08firma_comite int NOT NULL DEFAULT 0|ppto08cargo_solicitante int NOT NULL DEFAULT 0|ppto08cargo_jefeofi int NOT NULL DEFAULT 0|ppto08idencargado int NOT NULL DEFAULT 0";}
	if ($dbversion==5412){$sSQL="CREATE TABLE ppto16cambioestado (ppto16idsolicitud int NOT NULL, ppto16consec int NOT NULL, ppto16id int NOT NULL DEFAULT 0, ppto16fecha int NOT NULL DEFAULT 0, ppto16hora int NOT NULL DEFAULT 0, ppto16minuto int NOT NULL DEFAULT 0, ppto16idestadoorigen int NOT NULL DEFAULT 0, ppto16estadodestino int NOT NULL DEFAULT 0, ppto16idusuario int NOT NULL DEFAULT 0, ppto16detalle Text NULL)";}
	if ($dbversion==5413){$sSQL="ALTER TABLE ppto16cambioestado ADD PRIMARY KEY(ppto16id)";}
	if ($dbversion==5414){$sSQL=$objDB->sSQLCrearIndice('ppto16cambioestado', 'ppto16cambioestado_id', 'ppto16idsolicitud, ppto16consec', true);}
	if ($dbversion==5415){$sSQL=$objDB->sSQLCrearIndice('ppto16cambioestado', 'ppto16cambioestado_padre', 'ppto16idsolicitud');}
	if ($dbversion==5416){$sSQL=$u03." (15, 'Aprobar')";}
	if ($dbversion==5417){$sSQL=$u04." (508, 14, 'S'), (508, 15, 'S')";}
	if ($dbversion==5418){$sSQL="agregamodulo|551|5|Aval mesa técnica|1|3|5|6|1707";}
	if ($dbversion==5419){$sSQL=$u09."(551, 1, 'Aval mesa técnica', 'pptoavalmt.php', 503, 551, 'S', '', '')";}
	if ($dbversion==5420){$sSQL="agregamodulo|552|5|Aval planeación|1|3|5|6|1707";}
	if ($dbversion==5421){$sSQL=$u09."(552, 1, 'Aval planeación', 'pptoavalplan.php', 503, 552, 'S', '', '')";}
	if ($dbversion==5422){$sSQL="agregamodulo|553|5|Aval macroproyecto|1|3|5|6|1707";}
	if ($dbversion==5423){$sSQL=$u09."(553, 1, 'Aval macroproyecto', 'pptoavalmp.php', 503, 553, 'S', '', '')";}
	if ($dbversion==5424){$sSQL="agregamodulo|554|5|Aval infraestructura|1|3|5|6|1707";}
	if ($dbversion==5425){$sSQL=$u09."(554, 1, 'Aval infraestructura', 'pptoavalinfra.php', 503, 554, 'S', '', '')";}
	if ($dbversion==5426){$sSQL="agregamodulo|555|5|Aval comité financiero|1|3|5|6|1707";}
	if ($dbversion==5427){$sSQL=$u09."(555, 1, 'Aval comité financiero', 'pptoavalcf.php', 503, 555, 'S', '', '')";}
	if ($dbversion==5428){$sSQL=$u08."(503, 'Solicitud CDP', 'gm.php?id=503', 'Solicitud de CDP', 'CDP Request', 'Pedido de CDP')";}
	if ($dbversion==5429){$sSQL="add_campos|corf60inscripcion|corf60tipooferta int NOT NULL DEFAULT 0";}
	if ($dbversion==5430){$sSQL="CREATE TABLE ofes06tipoanexo (ofes06consec int NOT NULL, ofes06id int NOT NULL DEFAULT 0, ofes06activo int NOT NULL DEFAULT 0, ofes06orden int NOT NULL DEFAULT 0, ofes06nombre varchar(50) NULL, ofes06exten int NOT NULL DEFAULT 0)";}
	if ($dbversion==5431){$sSQL="ALTER TABLE ofes06tipoanexo ADD PRIMARY KEY(ofes06id)";}
	if ($dbversion==5432){$sSQL=$objDB->sSQLCrearIndice('ofes06tipoanexo', 'ofes06tipoanexo_id', 'ofes06consec', true);}
	if ($dbversion==5433){$sSQL="agregamodulo|1806|17|Tipos de anexos|1|2|3|4|5|6|8";}
	if ($dbversion==5434){$sSQL=$u09."(1806, 1, 'Tipos de anexos', 'ofertipoanexocurso.php', 3, 1805, 'S', '', '')";}
	if ($dbversion==5435){$sSQL="add_campos|ofer44cursoanexo|ofer44idtipoarchivo int NOT NULL DEFAULT 0|ofer44idperiodo int NOT NULL DEFAULT 0|ofer44idactividad int NOT NULL DEFAULT 0|ofer44url varchar(250) NULL|ofer44idaprueba int NOT NULL DEFAULT 0|ofer44fechaaprueba int NOT NULL DEFAULT 0";}
	if ($dbversion==5436){$sSQL="INSERT INTO ofes06tipoanexo (ofes06consec, ofes06id, ofes06activo, ofes06orden, ofes06nombre, ofes06exten) VALUES (0, 0, 0, 0, '{Ninguno}', 0)";}
	if ($dbversion==5437){$sSQL=$objDB->sSQLCrearIndice('ofer44cursoanexo', 'ofer44cursoanexo_periodo', 'ofer44idperiodo');}
	//5438 -- 5444 --
	if ($dbversion==5445){$sSQL="add_campos|ppto08soldisponib|ppto08idexpediente int NOT NULL DEFAULT 0";}
	if ($dbversion==5446){$sSQL="CREATE TABLE unae41grupopoblacion (unae41consec int NOT NULL, unae41id int NOT NULL DEFAULT 0, unae41activa int NOT NULL DEFAULT 0, unae41orden int NOT NULL DEFAULT 0, unae41titulo varchar(200) NULL, unae41origen int NOT NULL DEFAULT 0)";}
	if ($dbversion==5447){$sSQL="ALTER TABLE unae41grupopoblacion ADD PRIMARY KEY(unae41id)";}
	if ($dbversion==5448){$sSQL=$objDB->sSQLCrearIndice('unae41grupopoblacion', 'unae41grupopoblacion_id', 'unae41consec', true);}
	if ($dbversion==5449){$sSQL="agregamodulo|241|1|Grupos de población|1|2|3|4|5|6|8";}
	if ($dbversion==5450){$sSQL=$u09."(241, 1, 'Grupos de población', 'unadgrupopersona.php', 1, 241, 'S', '', '')";}
	if ($dbversion==5451){$sSQL="CREATE TABLE unae42grupotercero (unae42idgrupo int NOT NULL, unae42idtercero int NOT NULL, unae42fechaini int NOT NULL, unae42id int NOT NULL DEFAULT 0, unae42fechafin int NOT NULL DEFAULT 0, unae43origendato int NOT NULL DEFAULT 0, unae42fechareg int NOT NULL DEFAULT 0)";}
	if ($dbversion==5452){$sSQL="ALTER TABLE unae42grupotercero ADD PRIMARY KEY(unae42id)";}
	if ($dbversion==5453){$sSQL=$objDB->sSQLCrearIndice('unae42grupotercero', 'unae42grupotercero_id', 'unae42idgrupo, unae42idtercero, unae42fechaini', true);}
	if ($dbversion==5454){$sSQL=$objDB->sSQLCrearIndice('unae42grupotercero', 'unae42grupotercero_padre', 'unae42idgrupo');}
	if ($dbversion==5455){$sSQL="agregamodulo|242|1|Grupos de pob - Integrantes|1|2|3|4|5|6|8";}
	if ($dbversion==5456){$sSQL="INSERT INTO unae16cronaccion (unae16id, unae16accion) VALUES (2315, 'CORE - Encuestas de Deserción')";}
	if ($dbversion==5457){$sSQL="INSERT INTO unae41grupopoblacion (unae41consec, unae41id, unae41activa, unae41orden, unae41titulo, unae41origen) VALUES (0, 0, 0, 0, '{Ninguno}', 0), (1, 1, 1, 1, 'Funcionarios de planta', 1), (2, 2, 1, 2, 'Contratistas', 1), (3, 3, 1, 3, 'Lideres de programa', 7), (4, 4, 1, 4, 'Directores de curso', 7), (5, 5, 1, 5, 'Tutores', 7), (8, 8, 1, 8, 'Docentes de carrera', 1), (9, 9, 1, 9, 'Docentes ocasionales', 1), (10, 10, 1, 10, 'Aspirantes', 7), (11, 11, 1, 11, 'Estudiantes activos', 7), (17, 17, 1, 17, 'Egresados', 7), (99, 99, 1, 99, 'E-Monitores', 2)";}
	if ($dbversion==5458){$sSQL="add_campos|corf60inscripcion|corf60refpago VARCHAR(50) NULL DEFAULT ''|corf60fechacobro int NOT NULL DEFAULT 0";}
	if ($dbversion==5459){$sSQL="CREATE TABLE unae43tokenws (unae43consec int NOT NULL, unae43id int NOT NULL DEFAULT 0, unae43idtercero int NOT NULL DEFAULT 0, unae43fechaing int NOT NULL DEFAULT 0, unae43fecharet int NOT NULL DEFAULT 0, unae43token varchar(20) NULL)";}
	if ($dbversion==5460){$sSQL="ALTER TABLE unae43tokenws ADD PRIMARY KEY(unae43id)";}
	if ($dbversion==5461){$sSQL=$objDB->sSQLCrearIndice('unae43tokenws', 'unae43tokenws_id', 'unae43consec', true);}
	if ($dbversion==5462){$sSQL="agregamodulo|243|1|Llaves WebServices |1|2|3|4|5|6|8";}
	if ($dbversion==5463){$sSQL=$u09."(243, 1, 'Llaves para servicios web', 'unadtokenws.php', 6, 243, 'S', '', '')";}
	//5464  a 5467 quedan libres.
	if ($dbversion==5468){$sSQL="agregamodulo|246|1|Llaves WebServices - Servicio|1|2|3|4|5|6|8";}
	//5469 A 5471 quedan libres
	if ($dbversion==5472){$sSQL=$objDB->sSQLEliminarIndice('cart05listaprod', 'cart05listaprod_id');}
	if ($dbversion==5473){$sSQL="add_campos|cart05listaprod|cart05idpoblacion int NOT NULL DEFAULT 0";}
	if ($dbversion==5474){$sSQL=$objDB->sSQLCrearIndice('cart05listaprod', 'cart05listaprod_id', 'cart05idlista, cart05idproducto, cart05idpoblacion', true);}
	if ($dbversion==5475){$sSQL="agregamodulo|912|9|Precios aplicables|1|5|6";}
	if ($dbversion==5476){$sSQL=$u09."(912, 1, 'Precios aplicables', 'cartrptprecios.php', 11, 912, 'S', '', '')";}
	if ($dbversion==5477){$sSQL="add_campos|core66tipohomologa|core66codacuerto_rcont int NOT NULL DEFAULT 0";}
	if ($dbversion==5478){$sSQL=$objDB->sSQLCrearIndice('core71homolsolicitud', 'core71homolsolicitud_tipo', 'core71idtipohomol');}
	if ($dbversion==5479){$sSQL="add_campos|unad24sede|unad24id_th int NOT NULL DEFAULT 0";}
	if ($dbversion==5480){$sSQL="add_campos|core12escuela|core12id_th int NOT NULL DEFAULT 0";}
	if ($dbversion==5481){$sSQL="DROP TABLE unae46tokenserv";}
	if ($dbversion==5482){$sSQL="DROP TABLE unae45servicioweb";}
	if ($dbversion==5483){$sSQL="CREATE TABLE unae46tokenorigen (unae46idtoken int NOT NULL, unae46consec int NOT NULL, unae46id int NOT NULL DEFAULT 0, unae46tipo int NOT NULL DEFAULT 0, unae46direccion varchar(100) NULL)";}
	if ($dbversion==5484){$sSQL="ALTER TABLE unae46tokenorigen ADD PRIMARY KEY(unae46id)";}
	if ($dbversion==5485){$sSQL=$objDB->sSQLCrearIndice('unae46tokenorigen', 'unae46tokenorigen_id', 'unae46idtoken, unae46consec', true);}
	if ($dbversion==5486){$sSQL=$objDB->sSQLCrearIndice('unae46tokenorigen', 'unae46tokenorigen_padre', 'unae46idtoken');}
	if ($dbversion==5487){$sSQL="add_campos|unae43tokenws|unae43porubicacion int NOT NULL DEFAULT 0";}
	if ($dbversion==5488){$sSQL="ALTER TABLE unae43tokenws MODIFY unae43token VARCHAR(50) DEFAULT ''";}
	if ($dbversion==5489){$sSQL="agregamodulo|2825|28|Personal Activo {Rpt}|1|5|6";}
	if ($dbversion==5490){$sSQL=$u09."(2825, 1, 'Personal Activo', 'rptpersonalactivo.php', 2805, 2825, 'S', '', '')";}
	if ($dbversion==5491){$sSQL=$u08."(2805, 'Personal', 'gm.php?id=2805', 'Personal', 'Staff', 'Perssoal')";}
	if ($dbversion==5492){$sSQL="agregamodulo|4301|1|Correos Enviados {Rpt}|1|5|6";}
	if ($dbversion==5493){$sSQL=$u09."(4301, 1, 'Correos Enviados', 'unadrptcorreos.php', 11, 4301, 'S', '', '')";}
	if ($dbversion==5494){$sSQL="DELETE FROM core08tipomatricula";}
	if ($dbversion==5495){$sSQL="INSERT INTO core08tipomatricula (core08id, core08nombre) VALUES (0, 'Campus'), (1, 'Guias Fisicas'), (2, 'Matricula sin pago'), (3, 'Situaciones Académicas'), (5, 'Guia INPEC')";}
	if ($dbversion==5496){$sSQL=$objDB->sSQLCrearIndice('core16actamatricula', 'core16actamatricula_programa', 'core16idprograma');}
	if ($dbversion==5497){$sSQL=$objDB->sSQLCrearIndice('core16actamatricula', 'core16actamatricula_centro', 'core16idcead');}
	if ($dbversion==5498){$sSQL="add_campos|ceca18consolidadocalif|ceca18rcont75 Decimal(15,1) NULL DEFAULT 0|ceca18rcont25 Decimal(15,1) NULL DEFAULT 0|ceca18rcontcomp int NOT NULL DEFAULT 0";}
	if ($dbversion==5499){$sSQL="agregamodulo|2445|24|Critica a Notas Reportadas|1|5|6";}
	if ($dbversion==5500){$sSQL=$u09."(2445, 1, 'Critica a Notas Reportadas', 'cecarptcritica.php', 6, 2445, 'S', '', '')";}
	}
if (($dbversion>5500)&&($dbversion<5601)){
	if ($dbversion==5501){$sSQL=$objDB->sSQLCrearIndice('ceca18consolidadocalif', 'ceca18consolidadocalif_periodo', 'ceca18idperiodo');}
	if ($dbversion==5502){$sSQL=$objDB->sSQLCrearIndice('ceca18consolidadocalif', 'ceca18consolidadocalif_compcampus', 'cara18comparafinal');}
	if ($dbversion==5503){$sSQL=$objDB->sSQLCrearIndice('ceca18consolidadocalif', 'ceca18consolidadocalif_comprcont', 'ceca18rcontcomp');}
	if ($dbversion==5504){$sSQL=$objDB->sSQLCrearIndice('ceca18consolidadocalif', 'ceca18consolidadocalif_curso', 'ceca18idcurso');}
	if ($dbversion==5505){$sSQL=$objDB->sSQLCrearIndice('ceca18consolidadocalif', 'ceca18consolidadocalif_tercero', 'ceca18idtercero');}
	if ($dbversion==5506){$sSQL="CREATE TABLE corf64convestado (corf64id int NOT NULL, corf64nombre varchar(50) NULL)";}
	if ($dbversion==5507){$sSQL="ALTER TABLE corf64convestado ADD PRIMARY KEY(corf64id)";}
	if ($dbversion==5508){$sSQL="INSERT INTO corf64convestado (corf64id, corf64nombre) VALUES (0, 'Borrador'), (1, 'Abierta'), (5, 'Verificación de Requisitos'), (11, 'Legalización'), (15, 'Pruebas de Ingreso'), (21, 'Selección de Aspirantes'), (25, 'Admisión'), (31, 'Cerrada')";}
	if ($dbversion==5509){$sSQL="add_campos|core09programa|core09info_detalle Text NULL|core09info_presenta varchar(250) NULL|core09info_video varchar(250) NULL|core09info_requisitios Text NULL|core09puntaje_aprob int NOT NULL DEFAULT 0|core09info_tyc Text NULL|core09info_infopractica Text NULL|core09admision_plan int NOT NULL DEFAULT 0";}
	if ($dbversion==5510){$sSQL="CREATE TABLE corf63progconv (corf63idprograma int NOT NULL, corf63consec int NOT NULL, corf63id int NOT NULL DEFAULT 0, corf63idperiodo int NOT NULL DEFAULT 0, corf63idplanest int NOT NULL DEFAULT 0, corf63estado int NOT NULL DEFAULT 0, corf63numcupos int NOT NULL DEFAULT 0, corf63num_cupolista int NOT NULL DEFAULT 0, corf63forma_evaluar int NOT NULL DEFAULT 0, corf63puntaje_prueba int NOT NULL DEFAULT 0, corf63puntaje_entrevista int NOT NULL DEFAULT 0, corf63fecha_apertura int NOT NULL DEFAULT 0, corf63fecha_liminscrip int NOT NULL DEFAULT 0, corf63fecha_limrevdoc int NOT NULL DEFAULT 0, corf63fecha_pagos int NOT NULL DEFAULT 0, corf63fecha_examenes int NOT NULL DEFAULT 0, corf63fecha_seleccion int NOT NULL DEFAULT 0, corf63fecha_ratificacion int NOT NULL DEFAULT 0, corf63fecha_cierra int NOT NULL DEFAULT 0, corf63presentacion Text NULL, corf63idnav int NOT NULL DEFAULT 0, corf63idmoodle int NOT NULL DEFAULT 0, corf63total_insc int NOT NULL DEFAULT 0, corf63total_autoriza int NOT NULL DEFAULT 0, corf63total_presentaex int NOT NULL DEFAULT 0, corf63total_aprobados int NOT NULL DEFAULT 0, corf63total_admitidos int NOT NULL DEFAULT 0)";}
	if ($dbversion==5511){$sSQL="ALTER TABLE corf63progconv ADD PRIMARY KEY(corf63id)";}
	if ($dbversion==5512){$sSQL=$objDB->sSQLCrearIndice('corf63progconv', 'corf63progconv_id', 'corf63idprograma, corf63consec', true);}
	if ($dbversion==5513){$sSQL=$objDB->sSQLCrearIndice('corf63progconv', 'corf63progconv_padre', 'corf63idprograma');}
	if ($dbversion==5514){$sSQL="agregamodulo|12262|22|Programas - Convocatoria adm.|2|3|4|5|6|8";}
	if ($dbversion==5515){$sSQL="CREATE TABLE corf81proyecmat (corf81idperiodo int NOT NULL, corf81idcentro int NOT NULL, corf81idprograma int NOT NULL, corf81idplan int NOT NULL, corf81idcurso int NOT NULL, corf81fecha int NOT NULL, corf81id int NOT NULL DEFAULT 0, corf81idzona int NOT NULL DEFAULT 0, corf81idescuela int NOT NULL DEFAULT 0, corf81historico int NOT NULL DEFAULT 0, corf81numest_nuevos int NOT NULL DEFAULT 0, corf81numest_antiguos int NOT NULL DEFAULT 0, corf81cred_pregrado int NOT NULL DEFAULT 0, corf81cred_dispregrado int NOT NULL DEFAULT 0, corf81cred_florida int NOT NULL DEFAULT 0, corf81cred_esp int NOT NULL DEFAULT 0, corf81cred_maestria int NOT NULL DEFAULT 0, corf81cred_cont int NOT NULL DEFAULT 0)";}
	if ($dbversion==5516){$sSQL="ALTER TABLE corf81proyecmat ADD PRIMARY KEY(corf81id)";}
	if ($dbversion==5517){$sSQL=$objDB->sSQLCrearIndice('corf81proyecmat', 'corf81proyecmat_id', 'corf81idperiodo, corf81idcentro, corf81idprograma, corf81idplan, corf81idcurso, corf81fecha', true);}
	if ($dbversion==5518){$sSQL="agregamodulo|12281|22|Proyección de matricula|1|2|3|4|5|6|8";}
	if ($dbversion==5519){$sSQL=$u09."(12281, 1, 'Proyección de matricula', 'coreproyeccion.php', 2201, 12281, 'S', '', '')";}
	if ($dbversion==5520){$sSQL="agregamodulo|4302|10|Inicios de sesion fallidos|1|5|6";}
	if ($dbversion==5521){$sSQL=$u09."(4302, 1, 'Inicios de sesion fallidos', 'seglogfallido.php', 6, 4302, 'S', '', '')";}
	if ($dbversion==5522){$sSQL="CREATE TABLE repo26datadesercion (repo26idpei int NOT NULL, repo26idcohorteing int NOT NULL, repo26idcohorterev int NOT NULL, repo26id int NOT NULL DEFAULT 0, repo26cant int NOT NULL DEFAULT 0, repo26edad int NOT NULL DEFAULT 0, repo26sexo varchar(1) NULL, repo26estadopei int NOT NULL DEFAULT 0, repo26idescuela int NOT NULL DEFAULT 0, repo26idprograma int NOT NULL DEFAULT 0, repo26idplan int NOT NULL DEFAULT 0, repo26idzona int NOT NULL DEFAULT 0, repo26idcentro int NOT NULL DEFAULT 0, repo26causadeserta int NOT NULL DEFAULT 0, repo26idcara int NOT NULL DEFAULT 0, repo26tipocara int NOT NULL DEFAULT 0, repo26grupocara int NOT NULL DEFAULT 0, repo26psico int NOT NULL DEFAULT 0, repo26comp_dig int NOT NULL DEFAULT 0, repo26comp_lec int NOT NULL DEFAULT 0, repo26comp_razon int NOT NULL DEFAULT 0, repo26comp_ingles int NOT NULL DEFAULT 0, repo26comp_bio int NOT NULL DEFAULT 0, repo26comp_fis int NOT NULL DEFAULT 0, repo26comp_quim int NOT NULL DEFAULT 0)";}
	if ($dbversion==5523){$sSQL="ALTER TABLE repo26datadesercion ADD PRIMARY KEY(repo26id)";}
	if ($dbversion==5524){$sSQL=$objDB->sSQLCrearIndice('repo26datadesercion', 'repo26datadesercion_id', 'repo26idpei, repo26idcohorteing, repo26idcohorterev', true);}
	if ($dbversion==5525){$sSQL="agregamodulo|2826|28|Analisis de deserción|1|5|6|12";}
	if ($dbversion==5526){$sSQL=$u09."(2826, 1, 'Analisis de deserción', 'rptanaliticadesercion.php', 2801, 2826, 'S', '', '')";}
	if ($dbversion==5527){$sSQL=$objDB->sSQLCrearIndice('repo26datadesercion', 'repo26datadesercion_escuela', 'repo26idescuela');}
	if ($dbversion==5528){$sSQL=$objDB->sSQLCrearIndice('repo26datadesercion', 'repo26datadesercion_programa', 'repo26idprograma');}
	if ($dbversion==5529){$sSQL=$objDB->sSQLCrearIndice('repo26datadesercion', 'repo26datadesercion_zona', 'repo26idzona');}
	if ($dbversion==5530){$sSQL=$objDB->sSQLCrearIndice('repo26datadesercion', 'repo26datadesercion_centro', 'repo26idcentro');}
	if ($dbversion==5531){$sSQL="CREATE TABLE aure51bitacora (aure51idproyecto int NOT NULL, aure51consec int NOT NULL, aure51idpadre int NOT NULL, aure51orden int NOT NULL, aure51id int NOT NULL DEFAULT 0, aure51estado int NOT NULL DEFAULT 0, aure51fecha int NOT NULL DEFAULT 0, aure51horaini int NOT NULL DEFAULT 0, aure51minini int NOT NULL DEFAULT 0, aure51horafin int NOT NULL DEFAULT 0, aure51minfin int NOT NULL DEFAULT 0, aure51idsistema int NOT NULL DEFAULT 0, aure51actividad varchar(250) NULL, aure51lugar varchar(250) NULL, aure51detalleactiv Text NULL, aure51objetivo Text NULL, aure51resultado Text NULL, aure51idresponsable int NOT NULL DEFAULT 0, aure51tiporesultado int NOT NULL DEFAULT 0)";}
	if ($dbversion==5532){$sSQL="ALTER TABLE aure51bitacora ADD PRIMARY KEY(aure51id)";}
	if ($dbversion==5533){$sSQL=$objDB->sSQLCrearIndice('aure51bitacora', 'aure51bitacora_id', 'aure51idproyecto, aure51consec, aure51idpadre, aure51orden', true);}
	if ($dbversion==5534){$sSQL="CREATE TABLE aure52bitaparticipa (aure52idbitacora int NOT NULL, aure52idtercero int NOT NULL, aure52id int NOT NULL DEFAULT 0, aure52activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==5535){$sSQL="ALTER TABLE aure52bitaparticipa ADD PRIMARY KEY(aure52id)";}
	if ($dbversion==5536){$sSQL=$objDB->sSQLCrearIndice('aure52bitaparticipa', 'aure52bitaparticipa_id', 'aure52idbitacora, aure52idtercero', true);}
	if ($dbversion==5537){$sSQL=$objDB->sSQLCrearIndice('aure52bitaparticipa', 'aure52bitaparticipa_padre', 'aure52idbitacora');}
	if ($dbversion==5538){$sSQL="agregamodulo|252|2|Bitacora Des - Participantes|1|2|3|4|5|6|8";}
	if ($dbversion==5539){$sSQL="CREATE TABLE aure57riesgobitacora (aure57idbitacora int NOT NULL, aure57idriesgo int NOT NULL, aure57id int NOT NULL DEFAULT 0, aure57nivelriesgo int NOT NULL DEFAULT 0)";}
	if ($dbversion==5540){$sSQL="ALTER TABLE aure57riesgobitacora ADD PRIMARY KEY(aure57id)";}
	if ($dbversion==5541){$sSQL=$objDB->sSQLCrearIndice('aure57riesgobitacora', 'aure57riesgobitacora_id', 'aure57idbitacora, aure57idriesgo', true);}
	if ($dbversion==5542){$sSQL=$objDB->sSQLCrearIndice('aure57riesgobitacora', 'aure57riesgobitacora_padre', 'aure57idbitacora');}
	if ($dbversion==5543){$sSQL="agregamodulo|257|2|Bitacora Des - Riesgos|1|2|3|4|5|6|8";}
	if ($dbversion==5544){$sSQL="CREATE TABLE aure58anexos (aure58idbitacora int NOT NULL, aure58consec int NOT NULL, aure58id int NOT NULL DEFAULT 0, aure58titulo varchar(100) NULL, aure58idorigen int NOT NULL DEFAULT 0, aure58idarchivo int NOT NULL DEFAULT 0, aure58idusuario int NOT NULL DEFAULT 0, aure58fecha int NOT NULL DEFAULT 0)";}
	if ($dbversion==5545){$sSQL="ALTER TABLE aure58anexos ADD PRIMARY KEY(aure58id)";}
	if ($dbversion==5546){$sSQL=$objDB->sSQLCrearIndice('aure58anexos', 'aure58anexos_id', 'aure58idbitacora, aure58consec', true);}
	if ($dbversion==5547){$sSQL=$objDB->sSQLCrearIndice('aure58anexos', 'aure58anexos_padre', 'aure58idbitacora');}
	if ($dbversion==5548){$sSQL="agregamodulo|253|2|Bitacora Des - Anexos|1|2|3|4|5|6|8";}
	if ($dbversion==5549){$sSQL="CREATE TABLE aure80historiaus (aure80idbitacora int NOT NULL, aure80consec int NOT NULL, aure80id int NOT NULL DEFAULT 0, aure80momento int NOT NULL DEFAULT 0, aure80infousuario Text NULL, aure80prioridad int NOT NULL DEFAULT 0, aure80semanaest int NOT NULL DEFAULT 0, aure80diasest int NOT NULL DEFAULT 0, aure80iteracionasig int NOT NULL DEFAULT 0, aure80infotecnica Text NULL, aure80observaciones Text NULL)";}
	if ($dbversion==5550){$sSQL="ALTER TABLE aure80historiaus ADD PRIMARY KEY(aure80id)";}
	if ($dbversion==5551){$sSQL=$objDB->sSQLCrearIndice('aure80historiaus', 'aure80historiaus_id', 'aure80idbitacora, aure80consec', true);}
	if ($dbversion==5552){$sSQL=$objDB->sSQLCrearIndice('aure80historiaus', 'aure80historiaus_padre', 'aure80idbitacora');}
	if ($dbversion==5553){$sSQL="agregamodulo|280|2|Bitacora Des - Hist usuario|1|2|3|4|5|6|8";}
	if ($dbversion==5554){$sSQL="CREATE TABLE aure81tareaing (aure81idbitacora int NOT NULL, aure81consec int NOT NULL, aure81id int NOT NULL DEFAULT 0, aure81idbithistoria int NOT NULL DEFAULT 0, aure81idtipotarea int NOT NULL DEFAULT 0, aure81semanasest int NOT NULL DEFAULT 0, aure81diasest int NOT NULL DEFAULT 0, aure81fechainicio int NOT NULL DEFAULT 0, aure81avance int NOT NULL DEFAULT 0, aure81fechafinal int NOT NULL DEFAULT 0, aure81descripcion Text NULL)";}
	if ($dbversion==5555){$sSQL="ALTER TABLE aure81tareaing ADD PRIMARY KEY(aure81id)";}
	if ($dbversion==5556){$sSQL=$objDB->sSQLCrearIndice('aure81tareaing', 'aure81tareaing_id', 'aure81idbitacora, aure81consec', true);}
	if ($dbversion==5557){$sSQL=$objDB->sSQLCrearIndice('aure81tareaing', 'aure81tareaing_padre', 'aure81idbitacora');}
	if ($dbversion==5558){$sSQL="agregamodulo|281|2|Bitacora Des -Tarea ingenieria|1|2|3|4|5|6|8";}
	if ($dbversion==5559){$sSQL="CREATE TABLE aure82pruebaac (aure82idbitacora int NOT NULL, aure82consec int NOT NULL, aure82id int NOT NULL DEFAULT 0, aure82condiciones Text NULL, aure82pasos Text NULL, aure82asignaperfil varchar(1) NULL, aure82manuales varchar(1) NULL, aure82capacitacion varchar(1) NULL, aure82evaluacion varchar(1) NULL, aure82resultadoesp Text NULL, aure82idtester int NOT NULL DEFAULT 0)";}
	if ($dbversion==5560){$sSQL="ALTER TABLE aure82pruebaac ADD PRIMARY KEY(aure82id)";}
	if ($dbversion==5561){$sSQL=$objDB->sSQLCrearIndice('aure82pruebaac', 'aure82pruebaac_id', 'aure82idbitacora, aure82consec', true);}
	if ($dbversion==5562){$sSQL=$objDB->sSQLCrearIndice('aure82pruebaac', 'aure82pruebaac_padre', 'aure82idbitacora');}
	if ($dbversion==5563){$sSQL="agregamodulo|282|2|Bitacora Des - Prueba acepta.|1|2|3|4|5|6|8";}
	if ($dbversion==5564){$sSQL="CREATE TABLE aure83tarjetacrc (aure83idbitacora int NOT NULL, aure83consec int NOT NULL, aure83id int NOT NULL DEFAULT 0, aure83idbithistoria int NOT NULL DEFAULT 0, aure83idtarea int NOT NULL DEFAULT 0, aure83vigente varchar(1) NULL, aure83nombreclase varchar(200) NULL, aure83responsabilidades Text NULL, aure83colaboradores Text NULL, aure83idtabla int NOT NULL DEFAULT 0)";}
	if ($dbversion==5565){$sSQL="ALTER TABLE aure83tarjetacrc ADD PRIMARY KEY(aure83id)";}
	if ($dbversion==5566){$sSQL=$objDB->sSQLCrearIndice('aure83tarjetacrc', 'aure83tarjetacrc_id', 'aure83idbitacora, aure83consec', true);}
	if ($dbversion==5567){$sSQL=$objDB->sSQLCrearIndice('aure83tarjetacrc', 'aure83tarjetacrc_padre', 'aure83idbitacora');}
	if ($dbversion==5568){$sSQL="agregamodulo|283|2|Bitacora Des - Tarjetas CRC|1|2|3|4|5|6|8";}
	if ($dbversion==5569){$sSQL="CREATE TABLE aure54tipotarea (aure54id int NOT NULL, aure54nombre varchar(50) NULL)";}
	if ($dbversion==5570){$sSQL="ALTER TABLE aure54tipotarea ADD PRIMARY KEY(aure54id)";}
	if ($dbversion==5571){$sSQL="CREATE TABLE aure55tiporiesgos (aure55consec int NOT NULL, aure55id int NOT NULL DEFAULT 0, aure55activo varchar(1) NULL, aure55obligatorio varchar(1) NULL, aure55nombre varchar(50) NULL)";}
	if ($dbversion==5572){$sSQL="ALTER TABLE aure55tiporiesgos ADD PRIMARY KEY(aure55id)";}
	if ($dbversion==5573){$sSQL=$objDB->sSQLCrearIndice('aure55tiporiesgos', 'aure55tiporiesgos_id', 'aure55consec', true);}
	if ($dbversion==5574){$sSQL="agregamodulo|255|2|Tipos de riesgos|1|2|3|4|5|6|8";}
	if ($dbversion==5575){$sSQL=$u09."(255, 1, 'Tipos de riesgos', 'aureatiporiesgos.php', 2, 255, 'S', '', '')";}
	if ($dbversion==5576){$sSQL="CREATE TABLE aure56riesgos (aure56consec int NOT NULL, aure56idtiporiesg int NOT NULL, aure56id int NOT NULL DEFAULT 0, aure56nombre varchar(100) NULL, aure56activo int NOT NULL DEFAULT 0, aure56consecuencias Text NULL, aure56planencasofalla Text NULL)";}
	if ($dbversion==5577){$sSQL="ALTER TABLE aure56riesgos ADD PRIMARY KEY(aure56id)";}
	if ($dbversion==5578){$sSQL=$objDB->sSQLCrearIndice('aure56riesgos', 'aure56riesgos_id', 'aure56consec, aure56idtiporiesg', true);}
	if ($dbversion==5579){$sSQL="agregamodulo|256|2|Riesgos|1|2|3|4|5|6|8";}
	if ($dbversion==5580){$sSQL=$u09."(256, 1, 'Riesgos', 'aureariesgo.php', 1, 256, 'S', '', '')";}
	if ($dbversion==5581){$sSQL="CREATE TABLE aure53hmomento (aure53id int NOT NULL, aure53nombre varchar(100) NULL)";}
	if ($dbversion==5582){$sSQL="ALTER TABLE aure53hmomento ADD PRIMARY KEY(aure53id)";}
	if ($dbversion==5583){$sSQL="CREATE TABLE aure59tiporesult (aure59id int NOT NULL, aure59nombre varchar(100) NULL)";}
	if ($dbversion==5584){$sSQL="ALTER TABLE aure59tiporesult ADD PRIMARY KEY(aure59id)";}
	if ($dbversion==5585){$sSQL="INSERT INTO aure53hmomento (aure53id, aure53nombre) VALUES (1, 'Inicio'), (2, 'Desarrollo'), (3, 'Pruebas'), (4, 'Adición'), (5, 'Cambios de diseño'), (6, 'Entrega'), (7, 'Documentación')";}
	if ($dbversion==5586){$sSQL="INSERT INTO aure59tiporesult (aure59id, aure59nombre) VALUES (0, 'Sin definir'), (1, 'Historia de usuario'), (2, 'Tarea de ingeniería'), (3, 'Pruebas de aceptación'), (4, 'Tarjeta CRC'), (9, 'Otro')";}
	if ($dbversion==5587){$sSQL="INSERT INTO aure54tipotarea (aure54id, aure54nombre) VALUES (0, 'Sin definir'), (1, 'Desarrollo'), (3, 'Corrección'), (7, 'Mejora'), (9, 'Otros')";}
	}
if (($dbversion>5600)&&($dbversion<5701)){
	//if ($dbversion==6480){$sSQL="add_campos|tabla|campo int NOT NULL DEFAULT 0";}
	}
if (($dbversion>5700)&&($dbversion<5801)){
	}
if (($dbversion>5800)&&($dbversion<5901)){
	}
if (($dbversion>5900)&&($dbversion<6001)){
}
if (($dbversion>6000)&&($dbversion<6101)){
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
?>