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
$versionejecutable=5244;
$procesos=0;
$suspende=0;
$error=0;
echo 'Iniciando proceso de revision de la base de datos [DB : '.$APP->dbname.']';
$sSQL='SHOW TABLES LIKE "unad00config";';
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
	if ($dbversion==5067){$sql="INSERT INTO unad46tipoperiodo (unad46id, unad46nombre) VALUES (11, 'SUA - Cursos MOOC')";}
	if ($dbversion==5068){$sSQL="CREATE TABLE ofes01preoferta (ofes01idperiodo int NOT NULL, ofes01idcurso int NOT NULL, ofes01id int NULL DEFAULT 0, ofes01estado int NULL DEFAULT 0, ofes01fechainialista int NULL DEFAULT 0, ofes01duracionmax int NULL DEFAULT 0, ofes01idmodalidad int NULL DEFAULT 0, ofes01idtarifa int NULL DEFAULT 0, ofes01iddirector int NULL DEFAULT 0, ofes01idgestor int NULL DEFAULT 0)";}
	if ($dbversion==5069){$sSQL="ALTER TABLE ofes01preoferta ADD PRIMARY KEY(ofes01id)";}
	if ($dbversion==5070){$sSQL=$objDB->sSQLCrearIndice('ofes01preoferta', 'ofes01preoferta_id', 'ofes01idperiodo, ofes01idcurso', true);}
	if ($dbversion==5071){$sSQL="agregamodulo|1801|17|Preoferta SUA|1|2|3|4|5|6|8";}
	if ($dbversion==5072){$sSQL=$u09."(1801, 1, 'Preoferta SUA', 'oferpreofertasua.php', 1704, 1801, 'S', '', '')";}
	if ($dbversion==5073){$sSQL="CREATE TABLE ofes02ediciones (ofes02idpreoferta int NOT NULL, ofes02numedicion int NOT NULL, ofes02id int NULL DEFAULT 0, ofes02fechainialmat int NULL DEFAULT 0, ofes02fechafinalmat int NULL DEFAULT 0, ofes02idnav int NULL DEFAULT 0, ofes02idmoodle int NULL DEFAULT 0, ofes02nombrecurso varchar(20) NULL, ofes02idoferta int NULL DEFAULT 0)";}
	if ($dbversion==5074){$sSQL="ALTER TABLE ofes02ediciones ADD PRIMARY KEY(ofes02id)";}
	if ($dbversion==5075){$sSQL=$objDB->sSQLCrearIndice('ofes02ediciones', 'ofes02ediciones_id', 'ofes02idpreoferta, ofes02numedicion', true);}
	if ($dbversion==5076){$sSQL=$objDB->sSQLCrearIndice('ofes02ediciones', 'ofes02ediciones_padre', 'ofes02idpreoferta');}
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
	if ($dbversion==5176){$sSQL="CREATE TABLE ppto06cuentas (ppto06tipo int NOT NULL, ppto06vigencia int NOT NULL, ppto06codigo varchar(50) NOT NULL, ppto06derivada int NOT NULL, ppto06recurso int NOT NULL, ppto06id int NULL DEFAULT 0, ppto06nombre varchar(250) NULL, ppto06movimiento int NULL DEFAULT 0, ppto06existe int NULL DEFAULT 0, ppto06nivel int NULL DEFAULT 0, ppto06idpadre int NULL DEFAULT 0, ppto06idctacgr int NULL DEFAULT 0, ppto06idctasnies int NULL DEFAULT 0, ppto06idfuente int NULL DEFAULT 0, ppto06iduso int NULL DEFAULT 0, ppto06situafondos int NULL DEFAULT 0, ppto06total_aforo Decimal(15,2) NULL DEFAULT 0, ppto06tota_adicion Decimal(15,2) NULL DEFAULT 0, ppto06total_reduccion Decimal(15,2) NULL DEFAULT 0, ppto06total_aplaza Decimal(15,2) NULL DEFAULT 0, ppto06total_creditos Decimal(15,2) NULL DEFAULT 0, ppto06total_contracred Decimal(15,2) NULL DEFAULT 0, ppto06total_recaudo Decimal(15,2) NULL DEFAULT 0, ppto06total_rec_sinfon Decimal(15,2) NULL DEFAULT 0, ppto06total_devolucion Decimal(15,2) NULL DEFAULT 0, ppto06total_dev_sinfon Decimal(15,2) NULL DEFAULT 0, ppto06total_dispon Decimal(15,2) NULL DEFAULT 0, ppto06total_dispon_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_registros Decimal(15,2) NULL DEFAULT 0, ppto06total_reg_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_obligacion Decimal(15,2) NULL DEFAULT 0, ppto06total_oblig_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_pagos Decimal(15,2) NULL DEFAULT 0, ppto06total_reserva Decimal(15,2) NULL DEFAULT 0, ppto06total_res_reg_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_res_oblig Decimal(15,2) NULL DEFAULT 0, ppto06total_res_oblig_anul Decimal(15,2) NULL DEFAULT 0, ppto06total_cxp Decimal(15,2) NULL DEFAULT 0, ppto06total_cxp_pago Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==5177){$sSQL="ALTER TABLE ppto06cuentas ADD PRIMARY KEY(ppto06id)";}
	if ($dbversion==5178){$sSQL=$objDB->sSQLCrearIndice('ppto06cuentas', 'ppto06cuentas_id', 'ppto06tipo, ppto06vigencia, ppto06codigo, ppto06derivada, ppto06recurso', true);}
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
	}
if (($dbversion>5300)&&($dbversion<5401)){
	}
if (($dbversion>5400)&&($dbversion<5501)){
	}
if (($dbversion>5500)&&($dbversion<5601)){
	}
if (($dbversion>5600)&&($dbversion<5701)){
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
	echo "<br>".$sSQL;
	switch (substr($sSQL,0,10)){
		case "versionado":
			$sper=explode("|",$sSQL);
			$stemp="UPDATE unad01sistema SET unad01mayor=".$sper[2].", unad01menor=".$sper[3].", unad01correccion=".$sper[4]." WHERE unad01id=".$sper[1];
			$result=$objDB->ejecutasql($stemp);
		break;
		case "agregamodu":
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
		case "DROP TABLE":
			$nomtabla=substr($sSQL,11);
			$sSQLb='SHOW TABLES LIKE "'.$nomtabla.'"';
			$result=$objDB->ejecutasql($sSQLb);
			if ($objDB->nf($result)==0){
				echo '<br>La tabla '.$nomtabla.' no existe.';
				}else{
				$result=$objDB->ejecutasql($sSQL);
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
		case "":
			break;
		default:
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			echo '<br><font color="#FF0000"><b>Error </b>'.$objDB->serror.'</font>';
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