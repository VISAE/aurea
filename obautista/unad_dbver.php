<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Inicia Jueves, 25 de agosto de 2025
--- Esta página se encarga de mantener actualizado los script de las bases de datos.
*/
$err_level = error_reporting(E_ALL);
error_reporting($err_level);
ini_set("display_errors", 1);
//ini_set("error_log", "/var/www/aurea_www/campus/panel/log.htm"); // unad florida
ini_set("error_log", "/var/www/panel/log.php"); // campus colombia
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
$versionejecutable = 9934;
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
	if ($dbversion<9000){$bbloquea=true;}
	if ($dbversion>10000){$bbloquea=true;}
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
	$u01b="INSERT INTO unad01sistema (unad01id, unad01nombre, unad01descripcion, unad01publico, unad01instalado, unad01mayor, unad01menor, unad01correccion, unad01ruta, unad01orden) VALUES ";
	$u03="INSERT INTO unad03permisos (unad03id, unad03nombre) VALUES ";
	$u04="INSERT INTO unad04modulopermisos (unad04idmodulo, unad04idpermiso, unad04vigente) VALUES ";
	$u05="INSERT INTO unad05perfiles (unad05id, unad05nombre) VALUES ";
	$u06="INSERT INTO unad06perfilmodpermiso (unad06idperfil, unad06idmodulo, unad06idpermiso, unad06vigente) VALUES ";
	$u08="INSERT INTO unad08grupomenu (unad08id, unad08nombre, unad08pagina, unad08titulo, unad08nombre_en, unad08nombre_pt) VALUES ";
	$u09="INSERT INTO unad09modulomenu (unad09idmodulo, unad09consec, unad09nombre, unad09pagina, unad09grupo, unad09orden, unad09movil, unad09nombre_en, unad09nombre_pt) VALUES ";
	$u22="INSERT INTO unad22combos (unad22idmodulo, unad22consec, unad22codopcion, unad22nombre, unad22orden, unad22activa) VALUES ";
	$u60='INSERT INTO unad60preferencias (unad60idmodulo, unad60codigo, unad60nombre, unad60tipo) VALUES ';
	$unad70='INSERT INTO unad70bloqueoelimina (unad70idtabla, unad70idtablabloquea, unad70origennomtabla, unad70origenidtabla, unad70origencamporev, unad70mensaje, unad70etiqueta) VALUES ';
	$u96="INSERT INTO unad96estado (unad96idmodulo, unad96id, unad96nombre, unad96etiqueta) VALUES ";
	}
while ($dbversion<$versionejecutable){
$sSQL='';
if (($dbversion>9000)&&($dbversion<9101)){
	if ($dbversion==9001){$sSQL="agregamodulo|5018|29|Jornada - Escuela|1|2|3|4|5|6";}
	if ($dbversion==9002){$sSQL="UPDATE unad02modulos SET unad02idsistema=8 WHERE unad02id IN (708,709,712)";}
	if ($dbversion==9003){$sSQL="CREATE TABLE teso05bancoconfig (teso05idbanco int NOT NULL, teso05digitoscta int NOT NULL, teso05id int NOT NULL DEFAULT 0, teso05tipocuenta varchar(1) NULL)";}
	if ($dbversion==9004){$sSQL="ALTER TABLE teso05bancoconfig ADD PRIMARY KEY(teso05id)";}
	if ($dbversion==9005){$sSQL=$objDB->sSQLCrearIndice('teso05bancoconfig', 'teso05bancoconfig_id', 'teso05idbanco, teso05digitoscta', true);}
	if ($dbversion==9006){$sSQL=$objDB->sSQLCrearIndice('teso05bancoconfig', 'teso05bancoconfig_padre', 'teso05idbanco');}
	if ($dbversion==9007){$sSQL="agregamodulo|815|7|Bancos - Configuracion de cuentas|1|2|3|4|5|6";}
	if ($dbversion==9008){$sSQL="add_campos|unae02bannerimg|unae02fechainicio int NOT NULL DEFAULT 0|unae02fechafinal int NOT NULL DEFAULT 0|unae02alcance int NOT NULL DEFAULT 0";}

	if ($dbversion==9009){$sSQL="CREATE TABLE unaf22bannerperfil (unaf22idbanner int NOT NULL, unaf22idperfil int NOT NULL, unaf22id int NOT NULL DEFAULT 0, unaf22vigente int NOT NULL DEFAULT 0)";}
	if ($dbversion==9010){$sSQL="ALTER TABLE unaf22bannerperfil ADD PRIMARY KEY(unaf22idbanner, unaf22idperfil)";}
	if ($dbversion==9011){$sSQL="agregamodulo|4322|1|Imagen banner - perfil|1|2|3|4|5";}

	if ($dbversion==9012){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s1', 'unaf17p1');}
	if ($dbversion==9013){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s2', 'unaf17p2');}
	if ($dbversion==9014){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s3', 'unaf17p3');}
	if ($dbversion==9015){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s4', 'unaf17p4');}
	if ($dbversion==9016){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s5', 'unaf17p5');}
	if ($dbversion==9017){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s6', 'unaf17p6');}
	if ($dbversion==9018){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s7', 'unaf17p7');}

	if ($dbversion==9019){$sSQL="CREATE TABLE gafi29viaticovalor (gafi29consec int NOT NULL, gafi29id int NOT NULL DEFAULT 0, gafi29titulo varchar(250) NULL, gafi29fecha int NOT NULL DEFAULT 0, gafi29destino int NOT NULL DEFAULT 0)";}
	if ($dbversion==9020){$sSQL="ALTER TABLE gafi29viaticovalor ADD PRIMARY KEY(gafi29id)";}
	if ($dbversion==9021){$sSQL=$objDB->sSQLCrearIndice('gafi29viaticovalor', 'gafi29viaticovalor_id', 'gafi29consec', true);}
	if ($dbversion==9022){$sSQL="agregamodulo|4629|46|Escalas de viaticos|1|2|3|4|5|6";}
	if ($dbversion==9023){$sSQL=$u09."(4629, 1, 'Escalas de viaticos', 'gafiescalaviatico.php', 1, 4629, 'S', '', '')";}
	if ($dbversion==9024){$sSQL=$unad70."(2061,4630,'gafi30viaticovrrango','gafi30id','gafi30moneda','El dato esta incluido en Escalas viatico - rangos', '')";}
	if ($dbversion==9025){$sSQL="CREATE TABLE gafi30viaticovrrango (gafi30idviaticovr int NOT NULL, gafi30consec int NOT NULL, gafi30id int NOT NULL DEFAULT 0, gafi30ingdesde Decimal(15,2) NULL DEFAULT 0, gafi30inghasta Decimal(15,2) NULL DEFAULT 0, gafi30moneda int NOT NULL DEFAULT 0, gafi30vrdiario Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9026){$sSQL="ALTER TABLE gafi30viaticovrrango ADD PRIMARY KEY(gafi30id)";}
	if ($dbversion==9027){$sSQL=$objDB->sSQLCrearIndice('gafi30viaticovrrango', 'gafi30viaticovrrango_id', 'gafi30idviaticovr, gafi30consec', true);}
	if ($dbversion==9028){$sSQL=$objDB->sSQLCrearIndice('gafi30viaticovrrango', 'gafi30viaticovrrango_padre', 'gafi30idviaticovr');}
	if ($dbversion==9029){$sSQL="agregamodulo|4630|46|Escalas viatico - rangos|1|2|3|4|5|6";}
	if ($dbversion==9030){$sSQL="CREATE TABLE gafi31destino (gafi31consec int NOT NULL, gafi31id int NOT NULL DEFAULT 0, gafi31activo int NOT NULL DEFAULT 0, gafi31orden int NOT NULL DEFAULT 0, gafi31nombre varchar(250) NULL)";}
	if ($dbversion==9031){$sSQL="ALTER TABLE gafi31destino ADD PRIMARY KEY(gafi31id)";}
	if ($dbversion==9032){$sSQL=$objDB->sSQLCrearIndice('gafi31destino', 'gafi31destino_id', 'gafi31consec', true);}
	if ($dbversion==9033){$sSQL="agregamodulo|4631|46|Destinos para viaticos|1|2|3|4|5|6";}
	if ($dbversion==9034){$sSQL=$u09."(4631, 1, 'Destinos para viaticos', 'gafidestinoviatico.php', 2, 4631, 'S', '', '')";}

	if ($dbversion==9035){$sSQL="add_campos|gafi02desplazamiento|gafi02idsolic_ingbase Decimal(15,2) NULL DEFAULT 0|gafi02idsolic_gastosrep Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==9036){$sSQL="mod_quitar|761";}
	if ($dbversion==9037){$sSQL="DROP TABLE fact61monedas";}
	if ($dbversion==9038){$sSQL="add_campos|gthu54hvlaboral|gthu54asig_gastosrep Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==9039){$sSQL="add_campos|gafi01tipodesplaza|gafi01tipodesplaza int NOT NULL DEFAULT 0";}
	if ($dbversion==9040){$sSQL="INSERT INTO corf09novedadtipo (corf09id, corf09nombre) VALUES (8, 'Solicitud de reingreso')";}
	if ($dbversion==9041){$sSQL="add_campos|unad24sede|unad24condicion int NOT NULL DEFAULT 0";}
	if ($dbversion==9042){$sSQL="CREATE TABLE unaf23prediocondicion (unaf23id int NOT NULL, unaf23nombre varchar(200) NULL)";}
	if ($dbversion==9043){$sSQL="ALTER TABLE unaf23prediocondicion ADD PRIMARY KEY(unaf23id)";}
	if ($dbversion==9044){$sSQL="INSERT INTO unaf23prediocondicion (unaf23id, unaf23nombre) VALUES (0, 'Propia'), (3, 'Arriendo'), (5, 'Comodato'), (9, 'Externo')";}

	// 2025-10-02 Grupos de investigación
	if ($dbversion==9045){$sSQL=$u01."(54, 'INVESTIGACION', 'Sistema de Gestion de Investigacion', 'N', 'S', 1, 0, 0)";}
	if ($dbversion==9046){$sSQL="CREATE TABLE rese50grupoinvestigacion (rese50consec int NOT NULL, rese50id int NOT NULL DEFAULT 0, rese50orden int NOT NULL DEFAULT 0, rese50activo int NOT NULL DEFAULT 0, rese50nombre varchar(200) NULL)";}
	if ($dbversion==9047){$sSQL="ALTER TABLE rese50grupoinvestigacion ADD PRIMARY KEY(rese50id)";}
	if ($dbversion==9048){$sSQL=$objDB->sSQLCrearIndice('rese50grupoinvestigacion', 'rese50grupoinvestigacion_id', 'rese50consec', true);}
	if ($dbversion==9049){$sSQL="agregamodulo|5450|54|Grupos de investigacion|1|2|3|4|5|6";}
	if ($dbversion==9050){$sSQL=$u09."(5450, 1, 'Grupos de investigacion', 'resegruposinvest.php', 2, 5450, 'S', '', '')";}

	// 2025-10-02 Campos para trabajos de grado
	if ($dbversion==9051){$sSQL="add_campos|grad11proyecto|grad11t_5 int NOT NULL DEFAULT 0|grad11cvlac varchar(25) NULL|grad11orcid varchar(25) NULL|grad11pertenecegrupoinv int NOT NULL DEFAULT 0|grad11idgrupoinvestigacion int NOT NULL DEFAULT 0|grad11iddirectorgaiipu int NOT NULL DEFAULT 0|grad11fechadirectorgaiipu int NOT NULL DEFAULT 0";}	
	if ($dbversion==9052){$sSQL="CREATE TABLE grad39historialcambios (grad39idproyecto int NOT NULL, grad39idtipocambio int NOT NULL, grad39consec int NOT NULL, grad39id int NOT NULL DEFAULT 0, grad39detalle varchar(200) NULL, grad39idusuariosolicita int NOT NULL DEFAULT 0, grad39fechasolicita int NOT NULL DEFAULT 0, grad39usuarioaprueba int NOT NULL DEFAULT 0, grad39fechacambio int NOT NULL DEFAULT 0, grad39estado int NOT NULL DEFAULT 0, grad39titulo_origen varchar(200) NULL, grad39titulo_destino varchar(200) NULL, grad39linkrepositorio_origen varchar(250) NULL, grad39linkrepositorio_destino varchar(250) NULL, grad39susfecha_origen int NOT NULL DEFAULT 0, grad39susfecha_destino int NOT NULL DEFAULT 0, grad39susthora_origen int NOT NULL DEFAULT 0, grad39sustmin_origen int NOT NULL DEFAULT 0, grad39susthora_destino int NOT NULL DEFAULT 0, grad39sustmin_destino int NOT NULL DEFAULT 0, grad39sustlugar_origen varchar(200) NULL, grad39sustlugar_destino varchar(200) NULL, grad39sustmedio_origen varchar(200) NULL, grad39sustmedio_destino varchar(200) NULL)";}
	if ($dbversion==9053){$sSQL="ALTER TABLE grad39historialcambios ADD PRIMARY KEY(grad39id)";}
	if ($dbversion==9054){$sSQL=$objDB->sSQLCrearIndice('grad39historialcambios', 'grad39historialcambios_id', 'grad39idproyecto, grad39idtipocambio, grad39consec', true);}
	if ($dbversion==9055){$sSQL=$objDB->sSQLCrearIndice('grad39historialcambios', 'grad39historialcambios_padre', 'grad39idproyecto');}
	if ($dbversion==9056){$sSQL="agregamodulo|2739|27|Historial de cambios|1|2|3|4|5|6";}
	if ($dbversion==9057){$sSQL="add_campos|grad24proyectoanexo|grad24fechaaprobado2 int NOT NULL DEFAULT 0|grad24idaprobado2 int NOT NULL DEFAULT 0";}
	if ($dbversion==9058){$sSQL="add_campos|grad25tipoanexoproyecto|grad25tipoproyaplica int NOT NULL DEFAULT 0";}
	if ($dbversion==9059){$sSQL=$u04."(4602, 10, 'S'), (4602, 12, 'S'), (4602, 1707, 'S')";}

	if ($dbversion==9060){$sSQL=$u08."(4611, 'Financiero', 'gm.php?id=4611', 'Financiero', 'Financial', 'Financiero')";}
	if ($dbversion==9061){$sSQL="agregamodulo|3166|31|Solicitud de desplazamiento|1";}
	if ($dbversion==9062){$sSQL=$u09."(3166, 1, 'Solicitud de desplazamiento', 'gafsoldesplaza.php', 4611, 4602, 'S', '', '')";}

	if ($dbversion==9063){$sSQL="agregamodulo|4632|46|Gestión de desplazamiento|1|3|4|5|6|10|12|1707";}
	if ($dbversion==9064){$sSQL=$u09."(4632, 1, 'Gestión de desplazamiento', 'gafgestdesplaza.php', 4601, 4632, 'S', '', '')";}
	if ($dbversion==9065){$sSQL="add_campos|gafi03desptrayecto|gafi03mediotransporte int NOT NULL DEFAULT 0|gafi03entregadopor int NOT NULL DEFAULT 0|gafi03cont_idaprueba int NOT NULL DEFAULT 0|gafi03cont_idcuenta int NOT NULL DEFAULT 0|gafi03cont_fechaent int NOT NULL DEFAULT 0";}

	if ($dbversion==9066){$sSQL="CREATE TABLE gafi06otrosconceptos (gafi06idsolicitud int NOT NULL, gafi06idconcepto int NOT NULL, gafi06id int NOT NULL DEFAULT 0, gafi06detalle Text NULL, gafi06vrsolicitado Decimal(15,2) NULL DEFAULT 0, gafi06estado int NOT NULL DEFAULT 0, gafi06vraprobado Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9067){$sSQL="ALTER TABLE gafi06otrosconceptos ADD PRIMARY KEY(gafi06id)";}
	if ($dbversion==9068){$sSQL=$objDB->sSQLCrearIndice('gafi06otrosconceptos', 'gafi06otrosconceptos_id', 'gafi06idsolicitud, gafi06idconcepto', true);}
	if ($dbversion==9069){$sSQL=$objDB->sSQLCrearIndice('gafi06otrosconceptos', 'gafi06otrosconceptos_padre', 'gafi06idsolicitud');}
	if ($dbversion==9070){$sSQL="agregamodulo|4606|46|Solicitud desp - otros conceptos|1|2|3|4|5|6|8";}
	if ($dbversion==9071){$sSQL="add_campos|gafi05solcambioest|gafi05idusuario int NOT NULL DEFAULT 0";}

	if ($dbversion==9072){$sSQL="add_campos|unad18pais|unad18destino int NOT NULL DEFAULT 0";}
	if ($dbversion==9073){$sSQL="add_campos|cara01encuesta|cara01fichaciudad int NOT NULL DEFAULT 0";}
	if ($dbversion==9074){$sSQL="INSERT INTO cara07bloqueeval (cara07id, cara07nombre) VALUES (8, 'Competencias Ciudadanas')";}

	if ($dbversion==9075){$sSQL="CREATE TABLE gafi63festivos (gafi63consec int NOT NULL, gafi63id int NOT NULL DEFAULT 0, gafi63nombre varchar(250) NULL, gafi63tipofestivo int NOT NULL DEFAULT 0, gafi63mes int NOT NULL DEFAULT 0, gafi63dia int NOT NULL DEFAULT 0, gafi63desplazamiento int NOT NULL DEFAULT 0, gafi63agnoini int NOT NULL DEFAULT 0, gafi63agnofin int NOT NULL DEFAULT 0)";}
	if ($dbversion==9076){$sSQL="ALTER TABLE gafi63festivos ADD PRIMARY KEY(gafi63id)";}
	if ($dbversion==9077){$sSQL=$objDB->sSQLCrearIndice('gafi63festivos', 'gafi63festivos_id', 'gafi63consec', true);}
	if ($dbversion==9078){$sSQL="agregamodulo|4663|46|Festivos|1|2|3|4|5|6|8";}
	if ($dbversion==9079){$sSQL=$u09."(4663, 1, 'Festivos', 'gafifestivo.php', 2, 4663, 'S', '', '')";}
	if ($dbversion==9080){$sSQL="CREATE TABLE gafi64diashabiles (gafi64vigencia int NOT NULL, gafi64numerodia int NOT NULL, gafi64diasemana int NOT NULL DEFAULT 0, gafi64orden int NOT NULL DEFAULT 0, gafi64idfestivo int NOT NULL DEFAULT 0, gafi64habil int NOT NULL DEFAULT 0, gafi64ordenhabil int NOT NULL DEFAULT 0, bvigencia int NOT NULL DEFAULT 0)";}
	if ($dbversion==9081){$sSQL="ALTER TABLE gafi64diashabiles ADD PRIMARY KEY(gafi64vigencia, gafi64numerodia)";}
	if ($dbversion==9082){$sSQL="agregamodulo|4664|46|Dias habiles|1|2|3|4|5";}
	if ($dbversion==9083){$sSQL=$u09."(4664, 1, 'Dias hábiles', 'gafihabiles.php', 2, 4664, 'S', '', '')";}
	if ($dbversion==9084){$sSQL="add_campos|cara01encuesta|cara01nivelciudad int NOT NULL DEFAULT 0";}
	if ($dbversion==9085){$sSQL="add_campos|fact02factura|fact02cufe Text NULL|fact02urlfe Text NULL|fact02idfactorigen int NOT NULL DEFAULT 0|fact02idformanota int NOT NULL DEFAULT 0";}

	if ($dbversion==9086){$sSQL="add_campos|gafi02desplazamiento|gafi02idgestor int NOT NULL DEFAULT 0";}
	if ($dbversion==9087){$sSQL="add_campos|unad10vigencia|unad10pascua int NOT NULL DEFAULT 0";}
	if ($dbversion==9088){$sSQL="INSERT INTO gafi63festivos (gafi63consec, gafi63id, gafi63nombre, gafi63tipofestivo, gafi63mes, gafi63dia, gafi63desplazamiento, gafi63agnoini, gafi63agnofin) VALUES (0, 0, '{Ninguno}', 0, 0, 0, 0, 0, 0)";}

	if ($dbversion==9089){$sSQL="agregamodulo|2175|21|Acceso a Taller de Artes Visuales|1|1707";}
	if ($dbversion==9090){$sSQL=$u09."(2175, 1, 'Acceso a Taller de Artes Visuales', 'tav.php', 2106, 2175, 'S', '', '')";}
	if ($dbversion==9091){$sSQL="add_campos|grad41postulaciones|grad41idliderprograma int NOT NULL DEFAULT 0|grad41fechaverificacion int NOT NULL DEFAULT 0";}
	if ($dbversion==9092){$sSQL="mod_quitar|3701";}
	if ($dbversion==9093){$sSQL="mod_quitar|3702";}
	if ($dbversion==9094){$sSQL="mod_quitar|3703";}
	if ($dbversion==9095){$sSQL="DROP TABLE gcmo01indicador";}
	if ($dbversion==9096){$sSQL="DROP TABLE gcmo02periodo";}
	if ($dbversion==9097){$sSQL="DROP TABLE gcmo03reporte";}

	if ($dbversion==9098){$sSQL="CREATE TABLE gcmo01proceso (gcmo01codigo varchar(10) NOT NULL, gcmo01id int NOT NULL DEFAULT 0, gcmo01publico int NOT NULL DEFAULT 0, gcmo01nombre varchar(100) NULL, gcmo01tipo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9098){$sSQL="CREATE TABLE gcmo01proceso (gcmo01codigo varchar(10) NOT NULL, gcmo01id int NOT NULL DEFAULT 0, gcmo01publico int NOT NULL DEFAULT 0, gcmo01nombre varchar(100) NULL, gcmo01tipo int NOT NULL DEFAULT 0, gcmo01categoria int NOT NULL DEFAULT 0, gcmo01sistema int NOT NULL DEFAULT 0)";}
	if ($dbversion==9099){$sSQL="ALTER TABLE gcmo01proceso ADD PRIMARY KEY(gcmo01id)";}
	if ($dbversion==9100){$sSQL=$objDB->sSQLCrearIndice('gcmo01proceso', 'gcmo01proceso_id', 'gcmo01codigo', true);}
}
if (($dbversion>9100)&&($dbversion<9201)){
	if ($dbversion==9101){$sSQL="agregamodulo|3701|37|Procesos|1|2|3|4|5|6";}
	if ($dbversion==9102){$sSQL=$u09."(3701, 1, 'Procesos', 'gcmoproceso.php', 1, 3701, 'S', '', '')";}

	if ($dbversion==9103){$sSQL="CREATE TABLE gcmo02sistema (gcmo02codigo varchar(10) NOT NULL, gcmo02id int NOT NULL DEFAULT 0, gcmo02vigente int NOT NULL DEFAULT 0, gcmo02nombre varchar(100) NULL)";}
	if ($dbversion==9104){$sSQL="ALTER TABLE gcmo02sistema ADD PRIMARY KEY(gcmo02id)";}
	if ($dbversion==9105){$sSQL=$objDB->sSQLCrearIndice('gcmo02sistema', 'gcmo02sistema_id', 'gcmo02codigo', true);}
	if ($dbversion==9106){$sSQL="agregamodulo|3702|37|Sistema|1|2|3|4|5|6";}
	if ($dbversion==9107){$sSQL=$u09."(3702, 1, 'Sistema', 'gcmosistema.php', 2, 3702, 'S', '', '')";}
	// 9108 - 9111 Quedan libres
	if ($dbversion==9112){$sSQL="agregamodulo|3703|37|Indicadores|1|2|3|4|5|6";}
	if ($dbversion==9113){$sSQL="add_campos|cara08pregunta|cara08retroalimenta Text NULL";}

	if ($dbversion==9114){$sSQL="CREATE TABLE gcmo70unidadmedida (gcmo70consec int NOT NULL, gcmo70id int NOT NULL DEFAULT 0, gcmo70orden int NOT NULL DEFAULT 0, gcmo70activo int NOT NULL DEFAULT 0, gcmo70nombre varchar(100) NULL)";}
	if ($dbversion==9115){$sSQL="ALTER TABLE gcmo70unidadmedida ADD PRIMARY KEY(gcmo70id)";}
	if ($dbversion==9116){$sSQL=$objDB->sSQLCrearIndice('gcmo70unidadmedida', 'gcmo70unidadmedida_id', 'gcmo70consec', true);}
	if ($dbversion==9117){$sSQL="agregamodulo|3770|37|Unidades de medida|1|2|3|4|5|6";}
	if ($dbversion==9118){$sSQL=$u09."(3770, 1, 'Unidades de medida', 'gcmounidadmedia.php', 2, 3770, 'S', '', '')";}
	if ($dbversion==9119){$sSQL="CREATE TABLE gcmo71periodicidad (gcmo71id int NOT NULL, gcmo71nombre varchar(50) NULL)";}
	if ($dbversion==9120){$sSQL="ALTER TABLE gcmo71periodicidad ADD PRIMARY KEY(gcmo71id)";}
	if ($dbversion==9121){$sSQL="INSERT INTO gcmo71periodicidad (gcmo71id, gcmo71nombre) VALUES (1, 'Mensual'), (2, 'Bimensual'), (3, 'Trimestral'), (4, 'Cuatrimestral'), (6, 'Semestral - Por Ciclo'), (12, 'Anual - Vigencia'), (13, 'Por bloque de periodos'), (14, 'Por periodo académico'),  (24, 'Bianual'),  (48, 'Cuatrienal')";}

	if ($dbversion==9122){$sSQL="add_campos|saiu00config|saiu00estadoservicio int NOT NULL DEFAULT 1|saiu00mensajeestado Text NULL|saiu00correocopia varchar(50) NULL";}
	if ($dbversion==9123){$sSQL="INSERT INTO saiu46tipotramite (saiu46id, saiu46nombre) VALUES (702, 'Solicitud de facturas')";}

	if ($dbversion==9124){$sSQL="agregamodulo|889|8|Solicitudes de factura [3047]|1";}
	if ($dbversion==9125){$sSQL=$u09."(889, 1, 'Solicitudes de factura', 'saisolfactura.php', 3001, 891, 'S', '', '')";}
	if ($dbversion==9126){$sSQL="CREATE TABLE cara70tipoacompana (cara70id int NOT NULL, cara70nombre varchar(100) NULL, cara70version int NOT NULL DEFAULT 0)";}
	if ($dbversion==9127){$sSQL="ALTER TABLE cara70tipoacompana ADD PRIMARY KEY(cara70id)";}
	if ($dbversion==9128){$sSQL="INSERT INTO cara70tipoacompana (cara70id, cara70nombre, cara70version) VALUES 
		(0, '{Ninguno}', 0),
		(1, 'Inicial', 1),
		(2, 'Intermedia', 1),
		(3, 'Final', 1),
		(11, 'Administrativos', 2),
		(12, 'Metodología Unadista', 2),
		(13, 'Vida Académica y Vida Universitaria', 2),
		(14, 'Diversidad e inclusión', 2),
		(15, 'Atención Psicosocial', 2),
		(16, 'Egreso y Fidelización', 2)";}

	if ($dbversion==9129){$sSQL="CREATE TABLE unaf24botonera (unaf24consec int NOT NULL, unaf24id int NOT NULL DEFAULT 0, unaf24nombre varchar(100) NULL, unaf24activo int NOT NULL DEFAULT 0, unaf24orden int NOT NULL DEFAULT 0, unaf24descripcion varchar(200) NULL, unaf24urldestino varchar(100) NULL, unaf24idlogo int NOT NULL DEFAULT 0, unaf24idorigen int NOT NULL DEFAULT 0)";}
	if ($dbversion==9130){$sSQL="ALTER TABLE unaf24botonera ADD PRIMARY KEY(unaf24consec)";}
	if ($dbversion==9131){$sSQL="agregamodulo|4324|1|Botonera|1|2|3|4|5|6";}
	if ($dbversion==9132){$sSQL=$u09."(4324, 1, 'Botonera', 'unadbotonera.php', 1501, 4324, 'S', '', '')";}

	if ($dbversion==9133){$sSQL="INSERT INTO grad45estadosolgrad (grad45id, grad45nombre) VALUES (9, 'Anulada')";}

	if ($dbversion==9134){$sSQL="DROP TABLE unad11terceros_md";}
	if ($dbversion==9135){$sSQL="CREATE TABLE unad11terceros_md (unad11id int NOT NULL, unad11doc_pais varchar(3) NULL, unad11doc_depto varchar(5) NULL, unad11doc_ciudad varchar(8) NULL, unad11doc_fecha int NOT NULL DEFAULT 0, unad11reside_pais varchar(3) NULL, unad11reside_depto varchar(5) NULL, unad11reside_ciudad varchar(8) NULL, unad11reside_direccion varchar(100) NULL, unad11foto_idorigen int NOT NULL DEFAULT 0, unad11foto_idarchivo int NOT NULL DEFAULT 0, unad11idavatar int NOT NULL DEFAULT 0, unad11notifica_aplica int NOT NULL DEFAULT 0, unad11notifica_periodicidad int NOT NULL DEFAULT 0, unad11notifica_ultimavez int NOT NULL DEFAULT 0)";}
	if ($dbversion==9136){$sSQL="ALTER TABLE unad11terceros_md ADD PRIMARY KEY(unad11id)";}

	if ($dbversion==9137){$sSQL="CREATE TABLE grad40trabajogradoalterno (grad40consec int NOT NULL, grad40id int NOT NULL DEFAULT 0, grad40tipoproy int NOT NULL DEFAULT 0, grad40estado int NOT NULL DEFAULT 0, grad40titulo varchar(200) NULL, grad40idregistro int NOT NULL DEFAULT 0, grad40fecharegistro int NOT NULL DEFAULT 0, grad40detalle Text NULL, grad40idaprueba int NOT NULL DEFAULT 0, grad40fechaaprueba int NOT NULL DEFAULT 0, grad40notaplantrabajo int NOT NULL DEFAULT 0, grad40desemempresa int NOT NULL DEFAULT 0, grad40notadocumento int NOT NULL DEFAULT 0, grad40obraartistica int NOT NULL DEFAULT 0, grad40notasustenta int NOT NULL DEFAULT 0, grad40notafinal int NOT NULL DEFAULT 0, grad40idproyecto int NOT NULL DEFAULT 0, grad40idestudiante int NOT NULL DEFAULT 0, grad40idpei int NOT NULL DEFAULT 0)";}
	if ($dbversion==9138){$sSQL="ALTER TABLE grad40trabajogradoalterno ADD PRIMARY KEY(grad40id)";}
	if ($dbversion==9139){$sSQL=$objDB->sSQLCrearIndice('grad40trabajogradoalterno', 'grad40trabajogradoalterno_id', 'grad40consec', true);}
	if ($dbversion==9140){$sSQL="agregamodulo|2740|27|Trabajo de grado alterno|1|2|3|4|5|6";}
	if ($dbversion==9141){$sSQL=$u09."(2740, 1, 'Trabajo de grado alterno', 'gradproygradoalterno.php', 1, 2740, 'S', '', '')";}
	if ($dbversion==9142){$sSQL="add_campos|cara23acompanamento|cara23idmatricula int NOT NULL DEFAULT 0|cara23idpei int NOT NULL DEFAULT 0";}

	if ($dbversion==9143){$sSQL="agregamodulo|4194|41|Calculadora de fechas|1";}
	if ($dbversion==9144){$sSQL=$u09."(4194, 1, 'Calculadora de fechas', 'calcularfechas.php', 0, 4100, 'S', '', '')";}
	if ($dbversion==9145){$sSQL="add_campos|unad11terceros|unad11contab_idaprueba int NOT NULL DEFAULT 0|unad11contab_fechaaprueba int NOT NULL DEFAULT 0";}
	if ($dbversion==9146){$sSQL=$objDB->sSQLCrearIndice('unad11terceros', 'unad11terceros_contable', 'unad11contab_idaprueba');}

	if ($dbversion==9147){$sSQL="agregamodulo|791|7|Perfiles [105]|1";}
	if ($dbversion==9148){$sSQL=$u09."(791, 1, 'Perfiles', 'unadperfil.php', 2, 791, 'S', '', '')";}
	if ($dbversion==9149){$sSQL="agregamodulo|792|7|Usuarios [107]|1";}
	if ($dbversion==9150){$sSQL=$u09."(792, 1, 'Usuarios', 'unadusuarios.php', 1, 792, 'S', '', '')";}
	if ($dbversion==9151){$sSQL="agregamodulo|793|7|Terceros [111]|1";}
	if ($dbversion==9152){$sSQL=$u09."(793, 1, 'Terceros', 'unadterceros.php', 1, 793, 'S', '', '')";}
	if ($dbversion==9153){$sSQL="agregamodulo|794|7|Equipos de trabajo [1527 -1528]|1";}
	if ($dbversion==9154){$sSQL=$u09."(794, 1, 'Equipos de trabajo', 'misequipos.php', 1, 794, 'S', '', '')";}

	if ($dbversion==9155){$sSQL="agregamodulo|5369|53|Solicitudes de facturas [3047]|1";}
	if ($dbversion==9156){$sSQL=$u09."(5369, 1, 'Solicitudes de facturas [3047]', 'saisolfactura.php', 3001, 5369, 'S', '', '')";}
	if ($dbversion==9157){$sSQL="add_campos|unad11terceros|unad11contab_rutfecha int NOT NULL DEFAULT 0|unad11contab_rutidorigen int NOT NULL DEFAULT 0|unad11contab_rutidarchivo int NOT NULL DEFAULT 0";}

	if ($dbversion==9158){$sSQL="DROP TABLE corf63progconv";}
	if ($dbversion==9159){$sSQL="CREATE TABLE corf63progconv (corf63idescuela int NOT NULL, corf63idprograma int NOT NULL, corf63idperiodo int NOT NULL, corf63idplanest int NOT NULL, corf63consec int NOT NULL, corf63id int NOT NULL DEFAULT 0, corf63estado int NOT NULL DEFAULT 0, corf63numcupos int NOT NULL DEFAULT 0, corf63num_cupolista int NOT NULL DEFAULT 0, corf63forma_evaluar int NOT NULL DEFAULT 0, corf63puntaje_prueba int NOT NULL DEFAULT 0, corf63puntaje_entrevista int NOT NULL DEFAULT 0, corf63fecha_apertura int NOT NULL DEFAULT 0, corf63fecha_liminscrip int NOT NULL DEFAULT 0, corf63fecha_limrevdoc int NOT NULL DEFAULT 0, corf63fecha_pagos int NOT NULL DEFAULT 0, corf63fecha_examenes int NOT NULL DEFAULT 0, corf63fecha_seleccion int NOT NULL DEFAULT 0, corf63fecha_ratificacion int NOT NULL DEFAULT 0, corf63fecha_cierra int NOT NULL DEFAULT 0, corf63presentacion Text NULL, corf63idnav int NOT NULL DEFAULT 0, corf63idmoodle int NOT NULL DEFAULT 0, corf63total_insc int NOT NULL DEFAULT 0, corf63total_autoriza int NOT NULL DEFAULT 0, corf63total_presentaex int NOT NULL DEFAULT 0, corf63total_aprobados int NOT NULL DEFAULT 0, corf63total_admitidos int NOT NULL DEFAULT 0, corf63controlaadmision int NOT NULL DEFAULT 0, corf63porzonas int NOT NULL DEFAULT 0)";}
	if ($dbversion==9160){$sSQL="ALTER TABLE corf63progconv ADD PRIMARY KEY(corf63id)";}
	if ($dbversion==9161){$sSQL=$objDB->sSQLCrearIndice('corf63progconv', 'corf63progconv_id', 'corf63idescuela, corf63idprograma, corf63idperiodo, corf63idplanest, corf63consec', true);}
	if ($dbversion==9162){$sSQL="agregamodulo|2176|21|Acceso a Ecosistema Jur&iacute;dico|1|1707";}
	if ($dbversion==9163){$sSQL=$u09."(2176, 1, 'Acceso a Ecosistema Jur&iacute;dico', 'ecoecjp.php', 2106, 2176, 'S', '', '')";}

	if ($dbversion==9164){$sSQL="add_campos|aure69versionado|aure69proveedor int NOT NULL DEFAULT 0|aure69servicio int NOT NULL DEFAULT 0|aure69datos int NOT NULL DEFAULT 0";}
	if ($dbversion==9165){$sSQL="add_campos|corg19progdocumentos|corf19idorigen int NOT NULL DEFAULT 0|corf19idformato int NOT NULL DEFAULT 0|corf19instrucciones Text NULL";}
	if ($dbversion==9166){$sSQL="add_campos|sine10inscripcion|sine10recibo_ref varchar(50) NULL";}
	if ($dbversion==9167){$sSQL="add_campos|even02evento|even02pais varchar(3) NULL|even02depto varchar(5) NULL|even02ciudad varchar(8) NULL|even02modalidadvirt int NOT NULL DEFAULT 0|even02insfechainivirt varchar(10) NULL|even02insfechafinvirt varchar(10) NULL|even02idnav int NOT NULL DEFAULT 0|even02identornovirtual int NOT NULL DEFAULT 0|even02idunidadfuncionalorg int NOT NULL DEFAULT 0|even02idadministrador int NOT NULL DEFAULT 0|even02idprodpresencial_prev int NOT NULL DEFAULT 0|even02idprodpresencial_ord int NOT NULL DEFAULT 0|even02idprodpresencial_ext int NOT NULL DEFAULT 0|even02idprodvirtual_prev int NOT NULL DEFAULT 0|even02idprodvirtual_ord int NOT NULL DEFAULT 0|even02idprodvirtual_ext int NOT NULL DEFAULT 0|even02desplazamientos int NOT NULL DEFAULT 0|even02controlaasistencia int NOT NULL DEFAULT 0";}
	if ($dbversion==9168){$sSQL="CREATE TABLE even54eventoescenario (even54idevento int NOT NULL, even54consec int NOT NULL, even54id int NOT NULL DEFAULT 0, even54nombre varchar(250) NULL, even54numcupos int NOT NULL DEFAULT 0, even54permitevirtual int NOT NULL DEFAULT 0)";}
	if ($dbversion==9169){$sSQL="ALTER TABLE even54eventoescenario ADD PRIMARY KEY(even54id)";}
	if ($dbversion==9170){$sSQL=$objDB->sSQLCrearIndice('even54eventoescenario', 'even54eventoescenario_id', 'even54idevento, even54consec', true);}
	if ($dbversion==9171){$sSQL=$objDB->sSQLCrearIndice('even54eventoescenario', 'even54eventoescenario_padre', 'even54idevento');}
	if ($dbversion==9172){$sSQL="agregamodulo|1954|19|Escenarios|1|2|3|4|5|6|8";}
	if ($dbversion==9173){$sSQL="CREATE TABLE even53eventojornada (even53idevento int NOT NULL, even53consec int NOT NULL, even53id int NOT NULL DEFAULT 0, even53idescenario int NOT NULL DEFAULT 0, even53nombre varchar(250) NULL, even53fecha int NOT NULL DEFAULT 0, even53horaini int NOT NULL DEFAULT 0, even53minini int NOT NULL DEFAULT 0, even53horafin int NOT NULL DEFAULT 0, even53minfin int NOT NULL DEFAULT 0, even53tematica Text NULL, even53permitevirtual int NOT NULL DEFAULT 0, even53urlvirtual Text NULL)";}
	if ($dbversion==9174){$sSQL="ALTER TABLE even53eventojornada ADD PRIMARY KEY(even53id)";}
	if ($dbversion==9175){$sSQL=$objDB->sSQLCrearIndice('even53eventojornada', 'even53eventojornada_id', 'even53idevento, even53consec', true);}
	if ($dbversion==9176){$sSQL=$objDB->sSQLCrearIndice('even53eventojornada', 'even53eventojornada_padre', 'even53idevento');}
	if ($dbversion==9177){$sSQL="agregamodulo|1953|19|Jornadas|1|2|3|4|5|6|8";}
	if ($dbversion==9178){$sSQL="CREATE TABLE even55eventocomite (even55idevento int NOT NULL, even55consec int NOT NULL, even55id int NOT NULL DEFAULT 0, even55nombre varchar(150) NULL)";}
	if ($dbversion==9179){$sSQL="ALTER TABLE even55eventocomite ADD PRIMARY KEY(even55id)";}
	if ($dbversion==9180){$sSQL=$objDB->sSQLCrearIndice('even55eventocomite', 'even55eventocomite_id', 'even55idevento, even55consec', true);}
	if ($dbversion==9181){$sSQL=$objDB->sSQLCrearIndice('even55eventocomite', 'even55eventocomite_padre', 'even55idevento');}
	if ($dbversion==9182){$sSQL="agregamodulo|1955|19|Evento - Comité|1|2|3|4|5|6|8";}
	if ($dbversion==9183){$sSQL="CREATE TABLE even56eventocomiteparticipa (even56idevento int NOT NULL, even56idcomite int NOT NULL, even56idparticipante int NOT NULL, even56id int NOT NULL DEFAULT 0, even56idzona int NOT NULL DEFAULT 0, even56idcentro int NOT NULL DEFAULT 0, even56idescuela int NOT NULL DEFAULT 0, even56idprograma int NOT NULL DEFAULT 0)";}
	if ($dbversion==9184){$sSQL="ALTER TABLE even56eventocomiteparticipa ADD PRIMARY KEY(even56id)";}
	if ($dbversion==9185){$sSQL=$objDB->sSQLCrearIndice('even56eventocomiteparticipa', 'even56eventocomiteparticipa_id', 'even56idevento, even56idcomite, even56idparticipante', true);}
	if ($dbversion==9186){$sSQL=$objDB->sSQLCrearIndice('even56eventocomiteparticipa', 'even56eventocomiteparticipa_padre', 'even56idevento');}
	if ($dbversion==9187){$sSQL="agregamodulo|1956|19|Evento - Organizadores|1|2|3|4|5|6|8";}
	if ($dbversion==9188){$sSQL="CREATE TABLE even57eventootrosproductos (even57idevento int NOT NULL, even57idproducto int NOT NULL, even57id int NOT NULL DEFAULT 0, even57fechafin int NOT NULL DEFAULT 0, even57activo int NOT NULL DEFAULT 0, even57descripcion Text NULL)";}
	if ($dbversion==9189){$sSQL="ALTER TABLE even57eventootrosproductos ADD PRIMARY KEY(even57id)";}
	if ($dbversion==9190){$sSQL=$objDB->sSQLCrearIndice('even57eventootrosproductos', 'even57eventootrosproductos_id', 'even57idevento, even57idproducto', true);}
	if ($dbversion==9191){$sSQL=$objDB->sSQLCrearIndice('even57eventootrosproductos', 'even57eventootrosproductos_padre', 'even57idevento');}
	if ($dbversion==9192){$sSQL="agregamodulo|1957|19|Otros productos|1|2|3|4|5|6|8";}
	if ($dbversion==9193){$sSQL="add_campos|even04eventoparticipante|even04relacionunad int NOT NULL DEFAULT 0|even04estamentounad int NOT NULL DEFAULT 0";}
	if ($dbversion==9194){$sSQL="CREATE TABLE even58relacionunad (even58id int NOT NULL, even58nombre varchar(50) NULL, even58funcionario int NOT NULL DEFAULT 0, even58estudiante int NOT NULL DEFAULT 0, even58egresado int NOT NULL DEFAULT 0)";}
	if ($dbversion==9195){$sSQL="ALTER TABLE even58relacionunad ADD PRIMARY KEY(even58id)";}
	if ($dbversion==9196){$sSQL="CREATE TABLE even59estamentounad (even59id int NOT NULL, even59nombre varchar(50) NULL)";}
	if ($dbversion==9197){$sSQL="ALTER TABLE even59estamentounad ADD PRIMARY KEY(even59id)";}
	if ($dbversion==9198){$sSQL="CREATE TABLE even60eventoasistencia (even60idevento int NOT NULL, even60idjornada int NOT NULL, even60idtercero int NOT NULL, even60id int NOT NULL DEFAULT 0, even60estadoasiste int NOT NULL DEFAULT 0)";}
	if ($dbversion==9199){$sSQL="ALTER TABLE even60eventoasistencia ADD PRIMARY KEY(even60id)";}
	if ($dbversion==9200){$sSQL=$objDB->sSQLCrearIndice('even60eventoasistencia', 'even60eventoasistencia_id', 'even60idevento, even60idjornada, even60idtercero', true);}
}
if (($dbversion>9200)&&($dbversion<9301)){
	if ($dbversion==9201){$sSQL=$objDB->sSQLCrearIndice('even60eventoasistencia', 'even60eventoasistencia_padre', 'even60idevento');}
	if ($dbversion==9202){$sSQL="agregamodulo|1960|19|Asistencias por jornada|1|2|3|4|5|6|8";}
	//9203 - 9206 quedan libres
	if ($dbversion==9207){$sSQL=$u09."(3703, 1, 'Indicadores', 'gcmoindicador.php', 3701, 3703, 'S', '', '')";}
	if ($dbversion==9208){$sSQL="CREATE TABLE gcmo22fuentedatos (gcmo22id int NOT NULL, gcmo22nombre varchar(100) NULL)";}
	if ($dbversion==9209){$sSQL="ALTER TABLE gcmo22fuentedatos ADD PRIMARY KEY(gcmo22id)";}
	if ($dbversion==9210){$sSQL="INSERT INTO gcmo22fuentedatos (gcmo22id, gcmo22nombre) VALUES (0, 'Sistema Integrado de Información'), (99, 'Digitados')";}

	if ($dbversion==9211){$sSQL="CREATE TABLE gcmo20variable (gcmo20consec int NOT NULL, gcmo20id int NOT NULL DEFAULT 0, gcmo20activa int NOT NULL DEFAULT 0, gcmo20publicar int NOT NULL DEFAULT 0, gcmo20nombre varchar(250) NULL, gcmo20fuente int NOT NULL DEFAULT 0, gcmo20periodicidad int NOT NULL DEFAULT 0, gcmo20unidadacargo int NOT NULL DEFAULT 0, gcmo20dig_agno int NOT NULL DEFAULT 0, gcmo20dig_cohorte int NOT NULL DEFAULT 0, gcmo20dig_bloque int NOT NULL DEFAULT 0, gcmo20dig_zona int NOT NULL DEFAULT 0, gcmo20dig_centro int NOT NULL DEFAULT 0, gcmo20dig_unidadf int NOT NULL DEFAULT 0, gcmo20dig_escuela int NOT NULL DEFAULT 0, gcmo20dig_programa int NOT NULL DEFAULT 0, gcmo20dig_estamento int NOT NULL DEFAULT 0)";}
	if ($dbversion==9212){$sSQL="ALTER TABLE gcmo20variable ADD PRIMARY KEY(gcmo20id)";}
	if ($dbversion==9213){$sSQL=$objDB->sSQLCrearIndice('gcmo20variable', 'gcmo20variable_id', 'gcmo20consec', true);}
	if ($dbversion==9214){$sSQL="agregamodulo|3720|37|Variables|1|2|3|4|5|6|8";}
	if ($dbversion==9215){$sSQL=$u09."(3720, 1, 'Variables', 'gcmovariables.php', 3701, 3720, 'S', '', '')";}
	if ($dbversion==9216){$sSQL="add_campos|fact14producto|fact14uso_compras int NOT NULL DEFAULT 0";}

	if ($dbversion==9217){$sSQL="add_campos|olab60simulador|olab60perfiladmin int NOT NULL DEFAULT 0";}
	if ($dbversion==9218){$sSQL="CREATE TABLE core47admitidos (core47idtercero int NOT NULL, core47idsnies int NOT NULL, core47id int NOT NULL DEFAULT 0, core47idplandeestudios int NOT NULL DEFAULT 0, core47prefijo varchar(20) NULL, core47consec int NOT NULL DEFAULT 0, core47idescuela int NOT NULL DEFAULT 0, core47idprograma int NOT NULL DEFAULT 0, core47idzona int NOT NULL DEFAULT 0, core47idcentro int NOT NULL DEFAULT 0, core47formaadmision int NOT NULL DEFAULT 0, core47agno int NOT NULL DEFAULT 0, core47cicloinicial int NOT NULL DEFAULT 0, core47fechaadmision int NOT NULL DEFAULT 0, core47periodoadmision int NOT NULL DEFAULT 0, core47idtransicion int NOT NULL DEFAULT 0, core47trans_idsnies int NOT NULL DEFAULT 0, core47trans_idplan int NOT NULL DEFAULT 0, core47perm_ciclo1 int NOT NULL DEFAULT 0, core47perm_ciclo2 int NOT NULL DEFAULT 0, core47perm_ciclo3 int NOT NULL DEFAULT 0, core47perm_ciclo4 int NOT NULL DEFAULT 0, core47perm_ciclo5 int NOT NULL DEFAULT 0, core47perm_ciclo6 int NOT NULL DEFAULT 0, core47perm_ciclo7 int NOT NULL DEFAULT 0, core47perm_ciclo8 int NOT NULL DEFAULT 0, core47perm_ciclo9 int NOT NULL DEFAULT 0, core47perm_ciclo10 int NOT NULL DEFAULT 0, core47perm_ciclo11 int NOT NULL DEFAULT 0, core47perm_ciclo12 int NOT NULL DEFAULT 0, core47perm_ciclo13 int NOT NULL DEFAULT 0, core47perm_ciclo14 int NOT NULL DEFAULT 0, core47perm_ciclo15 int NOT NULL DEFAULT 0, core47perm_ciclo16 int NOT NULL DEFAULT 0, core47perm_ciclo17 int NOT NULL DEFAULT 0, core47perm_ciclo18 int NOT NULL DEFAULT 0, core47perm_ciclo19 int NOT NULL DEFAULT 0, core47perm_ciclo20 int NOT NULL DEFAULT 0, core47perm_estado int NOT NULL DEFAULT 0, core47sem_proyectados int NOT NULL DEFAULT 0, core47sem_relativo int NOT NULL DEFAULT 0, core47sem_total int NOT NULL DEFAULT 0)";}
	if ($dbversion==9219){$sSQL="ALTER TABLE core47admitidos ADD PRIMARY KEY(core47id)";}
	if ($dbversion==9220){$sSQL=$objDB->sSQLCrearIndice('core47admitidos', 'core47admitidos_id', 'core47idtercero, core47idsnies', true);}
	if ($dbversion==9221){$sSQL="agregamodulo|2247|22|Admisiones|1|3|5|6|10|21|1701";}
	if ($dbversion==9222){$sSQL=$u09."(2247, 1, 'Admisiones', 'coreadmision.php', 2200, 2247, 'S', '', '')";}
	if ($dbversion==9223){$sSQL="add_campos|core01estprograma|core01idadmision int NOT NULL DEFAULT 0";}
	if ($dbversion==9224){$sSQL="add_campos|core47admitidos|core47idestado int NOT NULL DEFAULT 0";}

	if ($dbversion==9225){$sSQL="add_campos|sine10inscripcion|sine10recibo_estado int NOT NULL DEFAULT 0";}
	if ($dbversion==9226){$sSQL="DROP TABLE sine11estadoinscrip";}
	if ($dbversion==9227){$sSQL="mod_quitar|4911";}
	if ($dbversion==9228){$sSQL="CREATE TABLE sine11inscripcambiaestado (sine11idinscripcion int NOT NULL, sine11consec int NOT NULL, sine11id int NOT NULL DEFAULT 0, sine11estadoorigen int NOT NULL DEFAULT 0, sine11estadodestino int NOT NULL DEFAULT 0, sine11idusuario int NOT NULL DEFAULT 0, sine11detalle Text NULL, sine11fecha int NOT NULL DEFAULT 0, sine11hora int NOT NULL DEFAULT 0, sine11min int NOT NULL DEFAULT 0)";}
	if ($dbversion==9229){$sSQL="ALTER TABLE sine11inscripcambiaestado ADD PRIMARY KEY(sine11id)";}
	if ($dbversion==9230){$sSQL=$objDB->sSQLCrearIndice('sine11inscripcambiaestado', 'sine11inscripcambiaestado_id', 'sine11idinscripcion, sine11consec', true);}
	if ($dbversion==9231){$sSQL=$objDB->sSQLCrearIndice('sine11inscripcambiaestado', 'sine11inscripcambiaestado_padre', 'sine11idinscripcion');}
	if ($dbversion==9232){$sSQL="agregamodulo|4911|49|Cambios de estado|1|2|3|4|5|6|8";}

	if ($dbversion==9233){$sSQL="CREATE TABLE cara60competenciasead (cara60idtercero int NOT NULL, cara60fecha int NOT NULL, cara60id int NOT NULL DEFAULT 0, cara60estado int NOT NULL DEFAULT 0, cara60preg1 int NOT NULL DEFAULT 0, cara60preg2 int NOT NULL DEFAULT 0, cara60preg3 int NOT NULL DEFAULT 0, cara60preg4 int NOT NULL DEFAULT 0, cara60preg5 int NOT NULL DEFAULT 0, cara60preg6 int NOT NULL DEFAULT 0, cara60preg7 int NOT NULL DEFAULT 0, cara60preg8 int NOT NULL DEFAULT 0, cara60preg9 int NOT NULL DEFAULT 0, cara60preg10 int NOT NULL DEFAULT 0, cara60preg11 int NOT NULL DEFAULT 0, cara60preg12 int NOT NULL DEFAULT 0, cara60preg13 int NOT NULL DEFAULT 0, cara60preg14 int NOT NULL DEFAULT 0, cara60preg15 Text NULL, cara60preg15idtexto int NOT NULL DEFAULT 0, cara60puntosmax int NOT NULL DEFAULT 0, cara60puntaje int NOT NULL DEFAULT 0)";}
	if ($dbversion==9234){$sSQL="ALTER TABLE cara60competenciasead ADD PRIMARY KEY(cara60id)";}
	if ($dbversion==9235){$sSQL=$objDB->sSQLCrearIndice('cara60competenciasead', 'cara60competenciasead_id', 'cara60idtercero, cara60fecha', true);}
	if ($dbversion==9236){$sSQL="agregamodulo|2360|23|Prueba de competencias básicas|1|2|3|4|5|6";}
	if ($dbversion==9237){$sSQL=$u09."(2360, 1, 'Prueba de competencias básicas', 'carapruebabase.php', 2301, 2360, 'S', '', '')";}
	if ($dbversion==9238){$sSQL="CREATE TABLE cara61compbasepreg (core61consec int NOT NULL, core61id int NOT NULL DEFAULT 0, core61activo int NOT NULL DEFAULT 0, core61orden int NOT NULL DEFAULT 0, core61pregunta varchar(250) NULL, core61usos int NOT NULL DEFAULT 0)";}
	if ($dbversion==9239){$sSQL="ALTER TABLE cara61compbasepreg ADD PRIMARY KEY(core61id)";}
	if ($dbversion==9240){$sSQL=$objDB->sSQLCrearIndice('cara61compbasepreg', 'cara61compbasepreg_id', 'core61consec', true);}
	if ($dbversion==9241){$sSQL="agregamodulo|2361|23|Competencias base - preguntas|1|2|3|4|5|6|8";}
	if ($dbversion==9242){$sSQL=$u09."(2361, 1, 'Competencias base - preguntas', 'carapruebapreg.php', 2, 2361, 'S', '', '')";}
	if ($dbversion==9243){$sSQL=$u04."(111, 6, 'S'), (111, 14, 'S')";}

	if ($dbversion==9244){$sSQL="add_campos|even53eventojornada|even53horaasistmin int NOT NULL DEFAULT 0|even53minasistmin int NOT NULL DEFAULT 0|even53horaasistmax int NOT NULL DEFAULT 0|even53minasistmax int NOT NULL DEFAULT 0";}
	if ($dbversion==9245){$sSQL="add_campos|even04eventoparticipante|even04idrol int NOT NULL DEFAULT 0|even04grupo int NOT NULL DEFAULT 0";}
	if ($dbversion==9246){$sSQL="add_campos|even02evento|even02codava int NOT NULL DEFAULT 0";}	

	if ($dbversion==9247){$sSQL="add_campos|even02evento|even02mostrarorganizador int NOT NULL DEFAULT 0";}
	if ($dbversion==9248){$sSQL="add_campos|even60eventoasistencia|even60horareg int NOT NULL DEFAULT 0|even60minreg int NOT NULL DEFAULT 0|even60idsesion int NOT NULL DEFAULT 0";}

	if ($dbversion==9249){$sSQL="agregamodulo|4813|1|Usuarios Themis|1|2|3|4|5|6";}
	if ($dbversion==9250){$sSQL=$u09."(4813, 1, 'Usuarios Themis', 'aurethemis.php', 6, 4813, 'S', '', '')";}
	if ($dbversion==9251){$sSQL="add_campos|even60eventoasistencia|even60fechareg int NOT NULL DEFAULT 0";}

	if ($dbversion==9252){$sSQL="CREATE TABLE saiu45rpttotal (saiu45modulo int NOT NULL, saiu45contenedor int NOT NULL, saiu45vigencia int NOT NULL, saiu45mes int NOT NULL, saiu45ciclo int NOT NULL, saiu45bloque int NOT NULL, saiu45escuela int NOT NULL, saiu45programa int NOT NULL, saiu45zona int NOT NULL, saiu45centro int NOT NULL, saiu45tipoconsejeria int NOT NULL, saiu45tipoacad int NOT NULL, saiu45estado int NOT NULL, saiu45estudiante int NOT NULL, saiu45cantidad int NOT NULL DEFAULT 0)";}
	if ($dbversion==9253){$sSQL="ALTER TABLE saiu45rpttotal ADD PRIMARY KEY(saiu45modulo, saiu45contenedor, saiu45vigencia, saiu45mes, saiu45ciclo, saiu45bloque, saiu45escuela, saiu45programa, saiu45zona, saiu45centro, saiu45tipoconsejeria, saiu45tipoacad, saiu45estado, saiu45estudiante)";}
	if ($dbversion==9254){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_estado', 'saiu45estado');}
	if ($dbversion==9255){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_estudiante', 'saiu45estudiante');}
	if ($dbversion==9256){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_modulo', 'saiu45modulo');}
	if ($dbversion==9257){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_vigencia', 'saiu45vigencia');}
	if ($dbversion==9258){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_mes', 'saiu45mes');}
	if ($dbversion==9259){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_ciclo', 'saiu45ciclo');}
	if ($dbversion==9260){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_bloque', 'saiu45bloque');}
	if ($dbversion==9261){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_escuela', 'saiu45escuela');}
	if ($dbversion==9262){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_programa', 'saiu45programa');}
	if ($dbversion==9263){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_zona', 'saiu45zona');}
	if ($dbversion==9264){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_centro', 'saiu45centro');}
	if ($dbversion==9265){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_tipocons', 'saiu45tipoconsejeria');}
	if ($dbversion==9266){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_tipoacad', 'saiu45tipoacad');}

	if ($dbversion==9267){$sSQL="add_campos|comp12procesocompra|comp12forma int NOT NULL DEFAULT 0";}
	if ($dbversion==9268){$sSQL="CREATE TABLE comp26proceso (comp26idproceso int NOT NULL, comp26idunspsc int NOT NULL, comp26id int NOT NULL DEFAULT 0, comp26cantidad Decimal(15,2) NULL DEFAULT 0, comp26moneda int NOT NULL DEFAULT 0, comp26vrunitario Decimal(15,2) NULL DEFAULT 0, comp26porciva int NOT NULL DEFAULT 0, comp26vriva Decimal(15,2) NULL DEFAULT 0, comp26subtotal Decimal(15,2) NULL DEFAULT 0, comp26detalle Text NULL)";}
	if ($dbversion==9269){$sSQL="ALTER TABLE comp26proceso ADD PRIMARY KEY(comp26id)";}
	if ($dbversion==9270){$sSQL=$objDB->sSQLCrearIndice('comp26proceso', 'comp26proceso_id', 'comp26idproceso, comp26idunspsc', true);}
	if ($dbversion==9271){$sSQL=$objDB->sSQLCrearIndice('comp26proceso', 'comp26proceso_padre', 'comp26idproceso');}
	if ($dbversion==9272){$sSQL="agregamodulo|3926|39|PC - UNSPSC|1|2|3|4|5|6|8";}

	if ($dbversion==9273){$sSQL="DROP TABLE teso33planpago";}
	if ($dbversion==9274){$sSQL="DROP TABLE teso34planpagodet";}
	if ($dbversion==9275){$sSQL="CREATE TABLE teso33planpago (teso33vigencia int NOT NULL, teso33consec int NOT NULL, teso33version int NOT NULL, teso33id int NOT NULL DEFAULT 0, teso33estado int NOT NULL DEFAULT 0, teso33origen int NOT NULL DEFAULT 0, teso33idrp int NOT NULL DEFAULT 0, teso33idprocesocctc int NOT NULL DEFAULT 0, teso33idminuta int NOT NULL DEFAULT 0, teso33idresol int NOT NULL DEFAULT 0, teso33idcdp int NOT NULL DEFAULT 0, teso33idbenef int NOT NULL DEFAULT 0, teso33forma int NOT NULL DEFAULT 0, teso33valor Decimal(15,2) NULL DEFAULT 0, teso33vrejec Decimal(15,2) NULL DEFAULT 0, teso33vrpago Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9276){$sSQL="ALTER TABLE teso33planpago ADD PRIMARY KEY(teso33id)";}
	if ($dbversion==9277){$sSQL=$objDB->sSQLCrearIndice('teso33planpago', 'teso33planpago_id', 'teso33vigencia, teso33consec, teso33version', true);}
	if ($dbversion==9278){$sSQL="CREATE TABLE teso34planpagodet (teso34idplanpago int NOT NULL, teso34numero int NOT NULL, teso34id int NOT NULL DEFAULT 0, teso34fechapac int NOT NULL DEFAULT 0, teso34estado int NOT NULL DEFAULT 0, teso34anticipo int NOT NULL DEFAULT 0, teso34porcentaje Decimal(15,2) NULL DEFAULT 0, teso34valorbase Decimal(15,2) NULL DEFAULT 0, teso34valoriva Decimal(15,2) NULL DEFAULT 0, teso34valortotal Decimal(15,2) NULL DEFAULT 0, teso34vrejec Decimal(15,2) NULL DEFAULT 0, teso34fecharadicado int NOT NULL DEFAULT 0, teso34fechaautoriza int NOT NULL DEFAULT 0, teso34vrordenpago Decimal(15,2) NULL DEFAULT 0, teso34vrpago Decimal(15,2) NULL DEFAULT 0, teso34idorden int NOT NULL DEFAULT 0, teso34idegreso int NOT NULL DEFAULT 0)";}
	if ($dbversion==9279){$sSQL="ALTER TABLE teso34planpagodet ADD PRIMARY KEY(teso34id)";}
	if ($dbversion==9280){$sSQL=$objDB->sSQLCrearIndice('teso34planpagodet', 'teso34planpagodet_id', 'teso34idplanpago, teso34numero', true);}
	if ($dbversion==9281){$sSQL=$objDB->sSQLCrearIndice('teso34planpagodet', 'teso34planpagodet_padre', 'teso34idplanpago');}
	if ($dbversion==9282){$sSQL="add_campos|unad19depto|unad19matricula int NOT NULL DEFAULT 1|unad19sigla varchar(5) NULL";}
	if ($dbversion==9283){$sSQL=$u04."(2360, 17, 'S'), (2360, 1707, 'S')";}
	if ($dbversion==9284){$sSQL="add_campos|core00params|core00admisionpruebabase int NOT NULL DEFAULT 0|core00admisionxdepto int NOT NULL DEFAULT 0|core00admision_nodepto_es Text NULL|core00admision_nodepto_en Text NULL";}

	if ($dbversion==9285){$sSQL="CREATE TABLE unad96estado (unad96idmodulo int NOT NULL, unad96id int NOT NULL, unad96nombre varchar(50) NULL, unad96etiqueta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9286){$sSQL="ALTER TABLE unad96estado ADD PRIMARY KEY(unad96idmodulo, unad96id)";}
	if ($dbversion==9287){$sSQL="CREATE TABLE unad97etiquetas (unad97idmodulo int NOT NULL, unad97idetiqueta int NOT NULL, unad97idioma varchar(2) NOT NULL, unad97valor Text NULL)";}
	if ($dbversion==9288){$sSQL="ALTER TABLE unad97etiquetas ADD PRIMARY KEY(unad97idmodulo, unad97idetiqueta, unad97idioma)";}
	if ($dbversion==9289){$sSQL="agregamodulo|297|2|Etiquetas|1|2|3|4|5|6";}
	if ($dbversion==9290){$sSQL=$u09."(297, 1, 'Etiquetas', 'aureaetiquetas.php', 1511, 297, 'S', '', '')";}
	if ($dbversion==9291){$sSQL="CREATE TABLE corg33idiomas (corg33consec int NOT NULL, corg33id int NOT NULL DEFAULT 0, corg33activa int NOT NULL DEFAULT 0, corg33orden int NOT NULL DEFAULT 0, corg33nombre varchar(50) NULL, corg33aplicacertificados int NOT NULL DEFAULT 0)";}
	if ($dbversion==9292){$sSQL="ALTER TABLE corg33idiomas ADD PRIMARY KEY(corg33id)";}
	if ($dbversion==9293){$sSQL=$objDB->sSQLCrearIndice('corg33idiomas', 'corg33idiomas_id', 'corg33consec', true);}
	if ($dbversion==9294){$sSQL="agregamodulo|4733|22|Idiomas|1|2|3|4|5|6|8";}
	if ($dbversion==9295){$sSQL=$u09."(4733, 1, 'Idiomas', 'coreidiomas.php', 3, 4733, 'S', '', '')";}
	if ($dbversion==9296){$sSQL="CREATE TABLE corg34pruebaidioma (corg34consec int NOT NULL, corg34id int NOT NULL DEFAULT 0, corg34idioma int NOT NULL DEFAULT 0, corg34tipo int NOT NULL DEFAULT 0, corg34activa int NOT NULL DEFAULT 0, corg34nombre varchar(250) NULL, corg34idnav int NOT NULL DEFAULT 0, corg34idaula int NOT NULL DEFAULT 0, corg34codcurso varchar(20) NULL, corg34ext_url varchar(250) NULL, corg34idproducto int NOT NULL DEFAULT 0, corg34idadministrador int NOT NULL DEFAULT 0)";}
	if ($dbversion==9297){$sSQL="ALTER TABLE corg34pruebaidioma ADD PRIMARY KEY(corg34id)";}
	if ($dbversion==9298){$sSQL=$objDB->sSQLCrearIndice('corg34pruebaidioma', 'corg34pruebaidioma_id', 'corg34consec', true);}
	if ($dbversion==9299){$sSQL="agregamodulo|4734|22|Pruebas de idioma|1|2|3|4|5|6|8";}
	if ($dbversion==9300){$sSQL=$u09."(4734, 1, 'Pruebas de idioma', 'corepruebasidioma.php', 2207, 4734, 'S', '', '')";}
}
if (($dbversion>9300)&&($dbversion<9401)){
	if ($dbversion==9301){$sSQL="CREATE TABLE corg35certidioma (corg35idtercero int NOT NULL, corg35ididioma int NOT NULL, corg35consec int NOT NULL, corg35id int NOT NULL DEFAULT 0, corg35estado int NOT NULL DEFAULT 0, corg35idprueba int NOT NULL DEFAULT 0, corg35emisor varchar(250) NULL, corg35idnav int NOT NULL DEFAULT 0, corg35idaula int NOT NULL DEFAULT 0, corg35grupo varchar(20) NULL, corg35fechaapertura int NOT NULL DEFAULT 0, corg35fechacierre int NOT NULL DEFAULT 0, corg35origencert int NOT NULL DEFAULT 0, corg35anexocert int NOT NULL DEFAULT 0, corg35fechacertificado int NOT NULL DEFAULT 0, corg35fechavencimiento int NOT NULL DEFAULT 0, corg35puntaje int NOT NULL DEFAULT 0, corg35resultado int NOT NULL DEFAULT 0, corg35idusuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==9302){$sSQL="ALTER TABLE corg35certidioma ADD PRIMARY KEY(corg35id)";}
	if ($dbversion==9303){$sSQL=$objDB->sSQLCrearIndice('corg35certidioma', 'corg35certidioma_id', 'corg35idtercero, corg35ididioma, corg35consec', true);}
	if ($dbversion==9304){$sSQL="agregamodulo|4735|22|Certificados de idioma|1|2|3|4|5|6|8";}
	if ($dbversion==9305){$sSQL=$u09."(4735, 1, 'Certificados de idioma', 'corecertificadoidioma.php', 2207, 4735, 'S', '', '')";}
	if ($dbversion==9306){$sSQL="CREATE TABLE corg36certidiomanota (corg36idprueba int NOT NULL, corg36consec int NOT NULL, corg36id int NOT NULL DEFAULT 0, corg36publica int NOT NULL DEFAULT 0, corg36nota Text NULL, corg36fecha int NOT NULL DEFAULT 0, corg36hora int NOT NULL DEFAULT 0, corg36minuto int NOT NULL DEFAULT 0, corg36idusuario int NOT NULL DEFAULT 0, corg36idrespuesta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9307){$sSQL="ALTER TABLE corg36certidiomanota ADD PRIMARY KEY(corg36id)";}
	if ($dbversion==9308){$sSQL=$objDB->sSQLCrearIndice('corg36certidiomanota', 'corg36certidiomanota_id', 'corg36idprueba, corg36consec', true);}
	if ($dbversion==9309){$sSQL=$objDB->sSQLCrearIndice('corg36certidiomanota', 'corg36certidiomanota_padre', 'corg36idprueba');}
	if ($dbversion==9310){$sSQL="agregamodulo|4736|22|Certificados idioma-anotacion|1|2|3|4|5|6|8";}
	if ($dbversion==9311){$sSQL=$u96."(4735, 0, 'Borrador', 100), (4735, 1, 'Radicado', 101), 
		(4735, 3, 'Solicitado', 103), (4735, 5, 'Pagado', 105), 
		(4735, 7, 'Habilitado', 107), (4735, 9, 'Presentado', 109), 
		(4735, 17, 'Finalizado', 117), (4735, 19, 'No aceptado', 119)";}
	if ($dbversion==9312){$sSQL=$u08."(2207, 'Idiomas', 'gm.php?id=2207', 'Idiomas', 'Languages', 'L&iacute;nguas')";}

	if ($dbversion==9313){$sSQL="INSERT INTO corg33idiomas (corg33consec, corg33id, corg33activa, corg33orden, corg33nombre, corg33aplicacertificados) VALUES (0, 0, 0, 0, '{Ninguno}', 0)";}
	if ($dbversion==9314){$sSQL="INSERT INTO corg34pruebaidioma (corg34consec, corg34id, corg34idioma, corg34tipo, corg34activa, corg34nombre, corg34idnav, corg34idaula, corg34codcurso, corg34ext_url, corg34idproducto, corg34idadministrador) VALUES (0, 0, 0, 0, 0, '{Ninguna}', 0, 0, '', '', 0, 0)";}

	if ($dbversion==9315){$sSQL="mod_quitar|147";}
	//if ($dbversion==9316){$sSQL=$u96."(4735, 0, 'Borrador', 101), (4735, 1, 'Radicado', 102), (4735, 3, 'Solicitado', 103), (4735, 5, 'Pagado', 104), (4735, 7, 'Habilitado', 105), (4735, 9, 'Presentado', 106), (4735, 17, 'Finalizado', 107), (4735, 19, 'No aceptado', 108)";}
	if ($dbversion==9317){$sSQL="CREATE TABLE corg73nivelidioma (corg73id int NOT NULL, corg73nombre varchar(50) NULL)";}
	if ($dbversion==9318){$sSQL="ALTER TABLE corg73nivelidioma ADD PRIMARY KEY(corg73id)";}
	if ($dbversion==9319){$sSQL="INSERT INTO corg73nivelidioma (corg73id, corg73nombre) VALUES (0, '-'), (11, 'A1'), (16, 'A2'), (21, 'B1'), (23, 'B1+'), (26, 'B2'), (31, 'C1')";}

	if ($dbversion==9320){$sSQL="CREATE TABLE sine26rubrica (sine26consec int NOT NULL, sine26id int NOT NULL DEFAULT 0, sine26vigente int NOT NULL DEFAULT 0, sine26titulo varchar(100) NULL, sine26pesototal int NOT NULL DEFAULT 0, sine26puntajemax int NOT NULL DEFAULT 0)";}
	if ($dbversion==9321){$sSQL="ALTER TABLE sine26rubrica ADD PRIMARY KEY(sine26id)";}
	if ($dbversion==9322){$sSQL=$objDB->sSQLCrearIndice('sine26rubrica', 'sine26rubrica_id', 'sine26consec', true);}
	if ($dbversion==9323){$sSQL="agregamodulo|4926|22|Admisiones - Rubricas|1|2|3|4|5|6|8";}
	if ($dbversion==9324){$sSQL=$u09."(4926, 1, 'Admisiones - Rubricas', 'sinerubrica.php', 3, 4926, 'S', '', '')";}
	if ($dbversion==9325){$sSQL="CREATE TABLE sine27rubpreg (sine27idrubrica int NOT NULL, sine27consec int NOT NULL, sine27id int NOT NULL DEFAULT 0, sine27orden int NOT NULL DEFAULT 0, sine27peso int NOT NULL DEFAULT 0, sine27activa int NOT NULL DEFAULT 0, sine27titulo varchar(100) NULL, sine27detalle Text NULL)";}
	if ($dbversion==9326){$sSQL="ALTER TABLE sine27rubpreg ADD PRIMARY KEY(sine27id)";}
	if ($dbversion==9327){$sSQL=$objDB->sSQLCrearIndice('sine27rubpreg', 'sine27rubpreg_id', 'sine27idrubrica, sine27consec', true);}
	if ($dbversion==9328){$sSQL=$objDB->sSQLCrearIndice('sine27rubpreg', 'sine27rubpreg_padre', 'sine27idrubrica');}
	if ($dbversion==9329){$sSQL="agregamodulo|4927|22|Preguntas|1|2|3|4|5|6|8";}

	if ($dbversion==9330){$sSQL=$u04."(1757, 12, 'S'), (1757, 1701, 'S'), (1757, 1707, 'S')";}
	if ($dbversion==9331){$sSQL="add_campos|unad11terceros_md|unad11zipcode varchar(20) NULL";}
	if ($dbversion==9332){$sSQL="UPDATE unad09modulomenu SET unad09pagina='unadperiodo.php' WHERE unad09idmodulo=146";}
	if ($dbversion==9333){$sSQL="UPDATE unad02modulos SET unad02idsistema=22 WHERE unad02id=140";}
	if ($dbversion==9334){$sSQL="UPDATE unad09modulomenu SET unad09grupo=2200 WHERE unad09idmodulo=2209";}
	if ($dbversion==9335){$sSQL="UPDATE unad09modulomenu SET unad09nombre='Planes de estudio', unad09pagina='coreplanest.php' WHERE unad09idmodulo=2210";}
	if ($dbversion==9336){$sSQL="add_campos|core26espejos|core26idescuela int NOT NULL";}
	if ($dbversion==9337){$sSQL="ALTER TABLE core26espejos DROP INDEX core26espejos_id";}
	if ($dbversion==9338){$sSQL="ALTER TABLE core26espejos ADD UNIQUE INDEX core26espejos_id(core26idzona, core26idescuela, core26idtipoespejo, core26idtercero)";}
	if ($dbversion==9339){$sSQL=$u04."(2209, 12, 'S'), (2209, 14, 'S'), (2209, 1707, 'S')";}
	if ($dbversion==9340){$sSQL=$u04."(1756, 12, 'S'), (1756, 111, 'S'), (1756, 1701, 'S'), (1756, 1707, 'S')";}
	// 13 de Enero de 2026
	if ($dbversion==9341){$sSQL="add_campos|grad25tipoanexoproyecto|grad25nivelaplica int NOT NULL DEFAULT 0";}
	if ($dbversion==9342){$sSQL="agregamodulo|2232|27|Asignación de director GAIIPU|1|2|3|4|5|6";}
	if ($dbversion==9343){$sSQL=$u09."(2232, 1, 'Asignación de director GAIIPU', 'coreactadirectorgaiipu.php', 2203, 2232, 'S', '', '')";}
	if ($dbversion==9344){$sSQL="agregamodulo|2762|27|Gestion Académica Doctorado|1|2|3|4|5|6|8";}
	if ($dbversion==9345){$sSQL=$u09."(2762, 1, 'Gestión Académica Doctorado', 'gradproydoctorado.php', 2203, 2762, 'S', '', '')";}
	if ($dbversion==9346){$sSQL="INSERT INTO grad16estadoproy (grad16id, grad16nombre) VALUES (-15, 'Iniciado'), (-10, 'En progreso'), (-5, 'GAIIPU Terminado')";}

	if ($dbversion==9347){$sSQL="CREATE TABLE cart08recaudoaplica (cart08idrecaudo int NOT NULL, cart08idfactura int NOT NULL, cart08id int NOT NULL DEFAULT 0, cart08valoraplica Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9348){$sSQL="ALTER TABLE cart08recaudoaplica ADD PRIMARY KEY(cart08id)";}
	if ($dbversion==9349){$sSQL=$objDB->sSQLCrearIndice('cart08recaudoaplica', 'cart08recaudoaplica_id', 'cart08idrecaudo, cart08idfactura', true);}
	if ($dbversion==9350){$sSQL="add_campos|unae26unidadesfun|unae26compras_estricta int NOT NULL DEFAULT 0";}
	if ($dbversion==9351){$sSQL="add_campos|saiu60estadotramite|saiu60idetiqueta int NOT NULL DEFAULT 0";}
	if ($dbversion==9352){$sSQL="add_campos|unad21estadocivil|unad21etiqueta int NOT NULL DEFAULT 0";}
	if ($dbversion==9353){$sSQL=$u04."(3621, 10, 'S'), (3621, 17, 'S')";}
	if ($dbversion==9354){$sSQL="add_campos|unad10vigencia|unad10pptvigencia int NOT NULL DEFAULT 0";}

	if ($dbversion==9355){$sSQL="DROP TABLE cttc37minutas";}
	if ($dbversion==9356){$sSQL="CREATE TABLE cttc37minutas (cttc37vigencia int NOT NULL, cttc37prefijo int NOT NULL, cttc37consec int NOT NULL, cttc37version int NOT NULL, cttc37id int NOT NULL DEFAULT 0, cttc37tipominuta int NOT NULL DEFAULT 0, cttc37fechaminuta int NOT NULL DEFAULT 0, cttc37estado int NOT NULL DEFAULT 0, cttc37etiqueta varchar(30) NULL, cttc37idproceso int NOT NULL DEFAULT 0, cttc37idcontratista int NOT NULL DEFAULT 0, cttc37idjuridico int NOT NULL DEFAULT 0, cttc37objeto Text NULL, cttc37idaprueba int NOT NULL DEFAULT 0, cttc37idanula int NOT NULL DEFAULT 0, cttc37motivoanula Text NULL, cttc37fechainicio int NOT NULL DEFAULT 0, cttc37fechatermina_ant int NOT NULL DEFAULT 0, cttc37fechatermina_nueva int NOT NULL DEFAULT 0, cttc37diasduracion int NOT NULL DEFAULT 0, cttc37vranterior Decimal(15,2) NULL DEFAULT 0, cttc37vrminuta Decimal(15,2) NULL DEFAULT 0, cttc37vrfinal Decimal(15,2) NULL DEFAULT 0, cttc37idcdp int NOT NULL DEFAULT 0, cttc37idplanpago int NOT NULL DEFAULT 0, cttc37idrp int NOT NULL DEFAULT 0)";}
	if ($dbversion==9357){$sSQL="ALTER TABLE cttc37minutas ADD PRIMARY KEY(cttc37id)";}
	if ($dbversion==9358){$sSQL=$objDB->sSQLCrearIndice('cttc37minutas', 'cttc37minutas_id', 'cttc37vigencia, cttc37prefijo, cttc37consec, cttc37version', true);}
	if ($dbversion==9359){$sSQL="CREATE TABLE cttc86tipominuta (cttc86id int NOT NULL, cttc86nombre varchar(50) NULL, cttc86sumatiempo int NOT NULL DEFAULT 0, cttc86sumadinero int NOT NULL DEFAULT 0, cttc86etiqueta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9360){$sSQL="ALTER TABLE cttc86tipominuta ADD PRIMARY KEY(cttc86id)";}
	if ($dbversion==9361){$sSQL="INSERT INTO cttc86tipominuta (cttc86id, cttc86nombre, cttc86sumatiempo, cttc86sumadinero, cttc86etiqueta) VALUES 
		(-1, 'Resolución Interna', 0, 0, 99), 
		(0, 'Contrato base', 0, 0, 100), 
		(1, 'Adición en valor', 0, 1, 101), 
		(2, 'Adición en tiempo', 1, 0, 102), 
		(11, 'Adición en valor y tiempo', 1, 1, 111), 
		(21, 'Otro sí que no adiciona tiempo ni valor', 0, 0, 121)";}
	if ($dbversion==9362){$sSQL="add_campos|grad41postulaciones|grad41prog_idzona int NOT NULL DEFAULT 0|grad41prog_idcentro int NOT NULL DEFAULT 0";}
	if ($dbversion==9363){$sSQL="add_campos|grad11proyecto|grad11t_85 int NOT NULL DEFAULT 0|grad11t_90 int NOT NULL DEFAULT 0";}

	if ($dbversion==9364){$sSQL="add_campos|cttc37minutas|cttc37numobliga int NOT NULL DEFAULT 0|cttc37avance Decimal(15,2) NULL DEFAULT 0|cttc37pago_porc Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==9365){$sSQL="CREATE TABLE cttc41obligaciones (cttc41idminuta int NOT NULL, cttc41idminorigen int NOT NULL, cttc41numero int NOT NULL, cttc41version int NOT NULL, cttc41id int NOT NULL DEFAULT 0, cttc41descripcion Text NULL, cttc41tipoobligacion int NOT NULL DEFAULT 0, cttc41tipoentregable int NOT NULL DEFAULT 0, cttc41cantidad Decimal(15,2) NULL DEFAULT 0, cttc41fechainclusion int NOT NULL DEFAULT 0, cttc41fecharetiro int NOT NULL DEFAULT 0, cttc41peso int NOT NULL DEFAULT 0, cttc41peso_porc Decimal(15,2) NULL DEFAULT 0, cttc41avance Decimal(15,2) NULL DEFAULT 0, cttc41aporte Decimal(15,2) NULL DEFAULT 0, cttc41estimado1 Decimal(15,2) NULL DEFAULT 0, cttc41estimado2 Decimal(15,2) NULL DEFAULT 0, cttc41estimado3 Decimal(15,2) NULL DEFAULT 0, cttc41estimado4 Decimal(15,2) NULL DEFAULT 0, cttc41estimado5 Decimal(15,2) NULL DEFAULT 0, cttc41estimado6 Decimal(15,2) NULL DEFAULT 0, cttc41estimado7 Decimal(15,2) NULL DEFAULT 0, cttc41estimado8 Decimal(15,2) NULL DEFAULT 0, cttc41estimado9 Decimal(15,2) NULL DEFAULT 0, cttc41estimado10 Decimal(15,2) NULL DEFAULT 0, cttc41estimado11 Decimal(15,2) NULL DEFAULT 0, cttc41estimado12 Decimal(15,2) NULL DEFAULT 0, cttc41estimado13 Decimal(15,2) NULL DEFAULT 0, cttc41estimado14 Decimal(15,2) NULL DEFAULT 0, cttc41estimado15 Decimal(15,2) NULL DEFAULT 0, cttc41reportado1 Decimal(15,2) NULL DEFAULT 0, cttc41reportado2 Decimal(15,2) NULL DEFAULT 0, cttc41reportado3 Decimal(15,2) NULL DEFAULT 0, cttc41reportado4 Decimal(15,2) NULL DEFAULT 0, cttc41reportado5 Decimal(15,2) NULL DEFAULT 0, cttc41reportado6 Decimal(15,2) NULL DEFAULT 0, cttc41reportado7 Decimal(15,2) NULL DEFAULT 0, cttc41reportado8 Decimal(15,2) NULL DEFAULT 0, cttc41reportado9 Decimal(15,2) NULL DEFAULT 0, cttc41reportado10 Decimal(15,2) NULL DEFAULT 0, cttc41reportado11 Decimal(15,2) NULL DEFAULT 0, cttc41reportado12 Decimal(15,2) NULL DEFAULT 0, cttc41reportado13 Decimal(15,2) NULL DEFAULT 0, cttc41reportado14 Decimal(15,2) NULL DEFAULT 0, cttc41reportado15 Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9366){$sSQL="ALTER TABLE cttc41obligaciones ADD PRIMARY KEY(cttc41idminuta, cttc41idminorigen, cttc41numero, cttc41version)";}
	if ($dbversion==9367){$sSQL=$objDB->sSQLCrearIndice('cttc41obligaciones', 'cttc41obligaciones_padre', 'cttc41idminuta');}
	if ($dbversion==9368){$sSQL="agregamodulo|4141|41|Minutas - Obligaciones|1|2|3|4|5|6";}

	if ($dbversion==9369){$sSQL="UPDATE saiu60estadotramite SET saiu60nombre='Oficio remisorio y Formato Resumen de Recaudos' WHERE saiu60id=6";}
	if ($dbversion==9370){$sSQL="CREATE TABLE olab77consolidadocp (olab77idmatricula int NOT NULL, olab77id int NOT NULL DEFAULT 0, olab77estado int NOT NULL DEFAULT 0, olab77proc1_curso int NOT NULL DEFAULT 0, olab77proc1_insc int NOT NULL DEFAULT 0, olab77proc1_sesiones int NOT NULL DEFAULT 0, olab77proc1_asistencias int NOT NULL DEFAULT 0, olab77proc2_curso int NOT NULL DEFAULT 0, olab77proc2_insc int NOT NULL DEFAULT 0, olab77proc2_sesiones int NOT NULL DEFAULT 0, olab77proc2_asistencias int NOT NULL DEFAULT 0, olab77proc3_curso int NOT NULL DEFAULT 0, olab77proc3_insc int NOT NULL DEFAULT 0, olab77proc3_sesiones int NOT NULL DEFAULT 0, olab77proc3_asistencias int NOT NULL DEFAULT 0, olab77proc4_curso int NOT NULL DEFAULT 0, olab77proc4_insc int NOT NULL DEFAULT 0, olab77proc4_sesiones int NOT NULL DEFAULT 0, olab77proc4_asistencias int NOT NULL DEFAULT 0, olab77proc5_curso int NOT NULL DEFAULT 0, olab77proc5_insc int NOT NULL DEFAULT 0, olab77proc5_sesiones int NOT NULL DEFAULT 0, olab77proc5_asistencias int NOT NULL DEFAULT 0, olab77proc6_curso int NOT NULL DEFAULT 0, olab77proc6_insc int NOT NULL DEFAULT 0, olab77proc6_sesiones int NOT NULL DEFAULT 0, olab77proc6_asistencias int NOT NULL DEFAULT 0, olab77proc7_curso int NOT NULL DEFAULT 0, olab77proc7_insc int NOT NULL DEFAULT 0, olab77proc7_sesiones int NOT NULL DEFAULT 0, olab77proc7_asistencias int NOT NULL DEFAULT 0, olab77proc8_curso int NOT NULL DEFAULT 0, olab77proc8_insc int NOT NULL DEFAULT 0, olab77proc8_sesiones int NOT NULL DEFAULT 0, olab77proc8_asistencias int NOT NULL DEFAULT 0)";}
	if ($dbversion==9371){$sSQL="ALTER TABLE olab77consolidadocp ADD PRIMARY KEY(olab77idmatricula)";}
	if ($dbversion==9372){$sSQL="agregamodulo|2177|21|Consolidado de comp práctico|1|5|6";}
	if ($dbversion==9373){$sSQL=$u09."(2177, 1, 'Consolidado componente práctico', 'olabconsolidadocp.php', 11, 2177, 'S', '', '')";}
	//9374 -9376 quedan libres.

	if ($dbversion==9377){$sSQL="agregamodulo|2650|26|Resoluciones|1|2|3|4|5|6";}
	if ($dbversion==9378){$sSQL=$u09."(2650, 1, 'Resoluciones', 'gedoresoluciones.php', 2601, 2650, 'S', '', '')";}
	if ($dbversion==9379){$sSQL=$u96."(2650, 0, 'Borrador', 100), 
		(2650, 3, 'Solicitada', 103), 
		(2650, 5, 'Radicada', 105), 
		(2650, 7, 'Completa', 107), 
		(2650, 8, 'No aceptada', 108), 
		(2650, 9, 'Anulada', 109)";}
	
	if ($dbversion==9380){$sSQL="DROP TABLE gedo50resoluciones";}
	if ($dbversion==9381){$sSQL="CREATE TABLE gedo50resoluciones (gedo50vigencia int NOT NULL, gedo50numsol int NOT NULL, gedo50id int NOT NULL DEFAULT 0, gedo50origen_proceso int NOT NULL DEFAULT 0, gedo50origen_comp varchar(20) NULL, gedo50origen_id int NOT NULL DEFAULT 0, gedo50estado int NOT NULL DEFAULT 0, gedo50unidad int NOT NULL DEFAULT 0, gedo50escuela int NOT NULL DEFAULT 0, gedo50zona int NOT NULL DEFAULT 0, gedo50centro int NOT NULL DEFAULT 0, gedo50asunto Text NULL, gedo50fechasolicitada int NOT NULL DEFAULT 0, gedo50salida_id int NOT NULL DEFAULT 0, gedo50salida_fecha int NOT NULL DEFAULT 0, gedo50salida_numero int NOT NULL DEFAULT 0, gedo50beneficiario_id int NOT NULL DEFAULT 0, gedo50beneficiario_vr Decimal(15,2) NULL DEFAULT 0, gedo50beneficiario_4x1000 Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9382){$sSQL="ALTER TABLE gedo50resoluciones ADD PRIMARY KEY(gedo50id)";}
	if ($dbversion==9383){$sSQL=$objDB->sSQLCrearIndice('gedo50resoluciones', 'gedo50resoluciones_id', 'gedo50vigencia, gedo50numsol', true);}

	if ($dbversion==9384){$sSQL="CREATE TABLE unaf25seccional (unaf25codigo int NOT NULL, unaf25id int NOT NULL DEFAULT 0, unaf25sigla varchar(20) NULL, unaf25activa int NOT NULL DEFAULT 0, unaf25nombre varchar(250) NULL, unaf25dominio varchar(250) NULL, unaf25rutacampus varchar(250) NULL, unaf25rutaws varchar(250) NULL, unaf25llave varchar(50) NULL, unaf25director int NOT NULL DEFAULT 0, unaf25administrador int NOT NULL DEFAULT 0)";}
	if ($dbversion==9385){$sSQL="ALTER TABLE unaf25seccional ADD PRIMARY KEY(unaf25id)";}
	if ($dbversion==9386){$sSQL=$objDB->sSQLCrearIndice('unaf25seccional', 'unaf25seccional_id', 'unaf25codigo', true);}
	if ($dbversion==9387){$sSQL="agregamodulo|4325|1|Seccionales|1|2|3|4|5|6|8";}
	if ($dbversion==9388){$sSQL=$u09."(4325, 1, 'Seccionales', 'unadseccional.php', 3, 4325, 'S', '', '')";}

	if ($dbversion==9389){$sSQL=$objDB->sSQLCrearIndice('core01estprograma', 'core01estprograma_continuidad', 'core01contestado');}
	if ($dbversion==9390){$sSQL=$objDB->sSQLCrearIndice('core01estprograma', 'core01estprograma_importa', 'core01idimporta');}
	//24 - feb - 2026
	if ($dbversion==9391){$sSQL="DROP TABLE gcmo03indicador";}
	if ($dbversion==9392){$sSQL="CREATE TABLE gcmo03indicador (gcmo03idproceso int NOT NULL, gcmo03codigo varchar(20) NOT NULL, gcmo03version int NOT NULL, gcmo03id int NOT NULL DEFAULT 0, gcmo03activo int NOT NULL DEFAULT 0, gcmo03nombre varchar(200) NULL, gcmo03tipo int NOT NULL DEFAULT 0, gcmo03numvariables int NOT NULL DEFAULT 0, gcmo03proposito Text NULL, gcmo03aporte Text NULL, gcmo03estructura int NOT NULL DEFAULT 0, gcmo03fuente int NOT NULL DEFAULT 0, gcmo03var1_id int NOT NULL DEFAULT 0, gcmo03var1_nombre varchar(250) NULL, gcmo03var2_id int NOT NULL DEFAULT 0, gcmo03var2_nombre varchar(250) NULL, gcmo03interpretacion Text NULL, gcmo03unidadmedida int NOT NULL DEFAULT 0, gcmo03tendencia int NOT NULL DEFAULT 0, gcmo03periodicidad int NOT NULL DEFAULT 0, gcmo03periodicidad_analisis int NOT NULL DEFAULT 0, gcmo03unidresponsable int NOT NULL DEFAULT 0, gcmo03gruporesponsable int NOT NULL DEFAULT 0, gcmo03idesponsable int NOT NULL DEFAULT 0, gcmo03fechacreacion int NOT NULL DEFAULT 0, gcmo03fechacierre int NOT NULL DEFAULT 0, gcmo03nivelreporte int NOT NULL DEFAULT 0, gcmo03dig_agno int NOT NULL DEFAULT 0, gcmo03dig_cohorte int NOT NULL DEFAULT 0, gcmo03dig_bloque int NOT NULL DEFAULT 0, gcmo03dig_zona int NOT NULL DEFAULT 0, gcmo03dig_centro int NOT NULL DEFAULT 0, gcmo03dig_unidadf int NOT NULL DEFAULT 0, gcmo03dig_escuela int NOT NULL DEFAULT 0, gcmo03dig_programa int NOT NULL DEFAULT 0, gcmo03dig_personal int NOT NULL DEFAULT 0)";}
	if ($dbversion==9393){$sSQL="ALTER TABLE gcmo03indicador ADD PRIMARY KEY(gcmo03id)";}
	if ($dbversion==9394){$sSQL=$objDB->sSQLCrearIndice('gcmo03indicador', 'gcmo03indicador_id', 'gcmo03idproceso, gcmo03codigo, gcmo03version', true);}
	//25 - feb - 2026
	if ($dbversion==9395){$sSQL="add_campos|even02evento|even02idorigeninforme int NOT NULL DEFAULT 0|even02idinforme int NOT NULL DEFAULT 0";}
	if ($dbversion==9396){$sSQL="add_campos|even53eventojornada|even53idorigen int NOT NULL DEFAULT 0|even53idplanificacion int NOT NULL DEFAULT 0|even53publicaplanifica int NOT NULL DEFAULT 0";}
	if ($dbversion==9397){$sSQL=$u96."(4137, 0, 'En elaboración', 100), (4137, 5, 'En Aprobación', 105), (4137, 7, 'Aprobada', 107), (4137, 9, 'Anulada', 109), (4137, 11, 'En firmas', 111), (4137, 17, 'Finalizada', 117)";}
	if ($dbversion==9398){$sSQL="DROP TABLE cttc84estadominuta";}
	if ($dbversion==9399){$sSQL="CREATE TABLE visa34convtipo (visa34consec int NOT NULL, visa34id int NOT NULL DEFAULT 0, visa34nombre varchar(100) NULL, visa34rolestudiante int NOT NULL DEFAULT 0, visa34roladministrativo int NOT NULL DEFAULT 0, visa34rolacademico int NOT NULL DEFAULT 0, visa34rolaspirante int NOT NULL DEFAULT 0, visa34rolegresado int NOT NULL DEFAULT 0, visa34rolexterno int NOT NULL DEFAULT 0, visa34grupotipologia int NOT NULL DEFAULT 0, visa34activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9400){$sSQL="ALTER TABLE visa34convtipo ADD PRIMARY KEY(visa34id)";}
	}
if (($dbversion>9400)&&($dbversion<9501)){
	if ($dbversion==9401){$sSQL=$objDB->sSQLCrearIndice('visa34convtipo', 'visa34convtipo_id', 'visa34consec', true);}
	if ($dbversion==9402){$sSQL="agregamodulo|2934|29|Tipo de convocatoria|1|2|3|4|5|6|8";}
	if ($dbversion==9403){$sSQL=$u09."(2934, 1, 'Tipo de convocatoria', 'visaeconvtipo.php', 2908, 2934, 'S', '', '')";}
	if ($dbversion==9404){$sSQL=$u08."(2908, 'Convocatorias', 'gm.php?id=2908', 'Convocatorias', 'Convocation', 'Editais')";}
	if ($dbversion==9405){$sSQL="CREATE TABLE visa35convocatoria (visa35consec int NOT NULL, visa35id int NOT NULL DEFAULT 0, visa35idtipo int NOT NULL DEFAULT 0, visa35nombre varchar(250) NULL, visa35idzona int NOT NULL DEFAULT 0, visa35idcentro int NOT NULL DEFAULT 0, visa35idescuela int NOT NULL DEFAULT 0, visa35idprograma int NOT NULL DEFAULT 0, visa35estado int NOT NULL DEFAULT 0, visa35numcupos int NOT NULL DEFAULT 0, visa35fecha_apertura int NOT NULL DEFAULT 0, visa35fecha_liminscrip int NOT NULL DEFAULT 0, visa35fecha_limrevdoc int NOT NULL DEFAULT 0, visa35fecha_examenes int NOT NULL DEFAULT 0, visa35fecha_seleccion int NOT NULL DEFAULT 0, visa35fecha_ratificacion int NOT NULL DEFAULT 0, visa35fecha_cierra int NOT NULL DEFAULT 0, visa35presentacion Text NULL, visa35total_inscritos int NOT NULL DEFAULT 0, visa35total_autorizados int NOT NULL DEFAULT 0, visa35total_presentaex int NOT NULL DEFAULT 0, visa35total_aprobados int NOT NULL DEFAULT 0, visa35total_admitidos int NOT NULL DEFAULT 0, visa35idconvenio int NOT NULL DEFAULT 0, visa35idresolucion int NOT NULL DEFAULT 0)";}
	if ($dbversion==9406){$sSQL="ALTER TABLE visa35convocatoria ADD PRIMARY KEY(visa35id)";}
	if ($dbversion==9407){$sSQL=$objDB->sSQLCrearIndice('visa35convocatoria', 'visa35convocatoria_id', 'visa35consec', true);}
	if ($dbversion==9408){$sSQL="agregamodulo|2935|29|Convocatorias|1|2|3|4|5|6";}
	if ($dbversion==9409){$sSQL=$u09."(2935, 1, 'Convocatorias', 'visaeconvocatoria.php', 2908, 2935, 'S', '', '')";}
	//26 - feb - 2026
	if ($dbversion==9410){$sSQL="CREATE TABLE gedo00config (gedo00id int NOT NULL, gedo00idexpacad int NOT NULL DEFAULT 0, gedo00idtiporesol int NOT NULL DEFAULT 0)";}
	if ($dbversion==9411){$sSQL="ALTER TABLE gedo00config ADD PRIMARY KEY(gedo00id)";}
	if ($dbversion==9412){$sSQL="agregamodulo|2600|26|Parametros GD|1|3";}
	if ($dbversion==9413){$sSQL=$u09."(2600, 1, 'Parametros', 'gedoparams.php', 2, 2600, 'S', '', '')";}
	if ($dbversion==9414){$sSQL="INSERT INTO gedo00config (gedo00id, gedo00idexpacad, gedo00idtiporesol) VALUES (1, 0, 0)";}
	//27 - feb - 2026
	if ($dbversion==9415){$sSQL="add_campos|visa34convtipo|visa34aplicazona int NOT NULL DEFAULT 0|visa34aplicacentro int NOT NULL DEFAULT 0|visa34aplicaescuela int NOT NULL DEFAULT 0|visa34aplicaprograma int NOT NULL DEFAULT 0";}
	if ($dbversion==9416){$sSQL="CREATE TABLE visa42convanexo (visa42idtipo int NOT NULL, visa42consec int NOT NULL, visa42id int NOT NULL DEFAULT 0, visa42titulo varchar(50) NULL, visa42descripcion varchar(250) NULL, visa42activo int NOT NULL DEFAULT 0, visa42orden int NOT NULL DEFAULT 0, visa42obligatorio int NOT NULL DEFAULT 0, visa42tipodocumento int NOT NULL DEFAULT 0)";}
	if ($dbversion==9417){$sSQL="ALTER TABLE visa42convanexo ADD PRIMARY KEY(visa42id)";}
	if ($dbversion==9418){$sSQL=$objDB->sSQLCrearIndice('visa42convanexo', 'visa42convanexo_id', 'visa42idtipo, visa42consec', true);}
	if ($dbversion==9419){$sSQL=$objDB->sSQLCrearIndice('visa42convanexo', 'visa42convanexo_padre', 'visa42idtipo');}
	if ($dbversion==9420){$sSQL="agregamodulo|2942|29|Tipo convocatoria - Anexos|1|2|3|4|5|6|8";}
	if ($dbversion==9421){$sSQL="CREATE TABLE visa46grupotipologia (visa46consec int NOT NULL, visa46id int NOT NULL DEFAULT 0, visa46nombre varchar(50) NULL)";}
	if ($dbversion==9422){$sSQL="ALTER TABLE visa46grupotipologia ADD PRIMARY KEY(visa46id)";}
	if ($dbversion==9423){$sSQL=$objDB->sSQLCrearIndice('visa46grupotipologia', 'visa46grupotipologia_id', 'visa46consec', true);}
	if ($dbversion==9424){$sSQL="agregamodulo|2946|29|Grupos de tipologias|1|2|3|4|5|6|8";}
	if ($dbversion==9425){$sSQL=$u09."(2946, 1, 'Grupos de tipologias', 'visaegrupotipologia.php', 2, 2946, 'S', '', '')";}
	if ($dbversion==9426){$sSQL="CREATE TABLE visa36convtipologia (visa36idgrupo int NOT NULL, visa36consec int NOT NULL, visa36id int NOT NULL DEFAULT 0, visa36nombre varchar(50) NULL, visa36activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9427){$sSQL="ALTER TABLE visa36convtipologia ADD PRIMARY KEY(visa36id)";}
	if ($dbversion==9428){$sSQL=$objDB->sSQLCrearIndice('visa36convtipologia', 'visa36convtipologia_id', 'visa36idgrupo, visa36consec', true);}
	if ($dbversion==9429){$sSQL="agregamodulo|2936|29|Tipologias de convocatoria|1|2|3|4|5|6|8";}
	if ($dbversion==9430){$sSQL=$u09."(2936, 1, 'Tipologias de convocatoria', 'visaetipologia.php', 2908, 2936, 'S', '', '')";}
	// 9431 - 9434 - Quedan libres
	if ($dbversion==9435){$sSQL="agregamodulo|2937|29|Subtipologias de convocatorias|1|2|3|4|5|6|8";}
	if ($dbversion==9436){$sSQL="add_campos|visa35convocatoria|visa35gruponivel int NOT NULL DEFAULT 0|visa35nivelforma int NOT NULL DEFAULT 0|visa35idproducto int NOT NULL DEFAULT 0";}
	if ($dbversion==9437){$sSQL="CREATE TABLE visa38convpruebas (visa38idtipo int NOT NULL, visa38consec int NOT NULL, visa38id int NOT NULL DEFAULT 0, visa38nombre varchar(50) NULL, visa38tipoprueba int NOT NULL DEFAULT 0, visa38puntajemaximo int NOT NULL DEFAULT 0, visa38puntajeaproba int NOT NULL DEFAULT 0, visa38activo int NOT NULL DEFAULT 0, visa38idnav int NOT NULL DEFAULT 0, visa38idmoodle int NOT NULL DEFAULT 0)";}
	if ($dbversion==9438){$sSQL="ALTER TABLE visa38convpruebas ADD PRIMARY KEY(visa38id)";}
	if ($dbversion==9439){$sSQL=$objDB->sSQLCrearIndice('visa38convpruebas', 'visa38convpruebas_id', 'visa38idtipo, visa38consec', true);}
	if ($dbversion==9440){$sSQL="agregamodulo|2938|29|Pruebas de convocatoria|1|2|3|4|5|6|8";}
	if ($dbversion==9441){$sSQL=$u09."(2938, 1, 'Pruebas de convocatoria', 'visaepruebas.php', 2908, 2938, 'S', '', '')";}
	if ($dbversion==9442){$sSQL="CREATE TABLE visa40inscripcion (visa40idconvocatoria int NOT NULL, visa40idtercero int NOT NULL, visa40id int NOT NULL DEFAULT 0, visa40estado int NOT NULL DEFAULT 0, visa40idperiodo int NOT NULL DEFAULT 0, visa40idescuela int NOT NULL DEFAULT 0, visa40idprograma int NOT NULL DEFAULT 0, visa40idzona int NOT NULL DEFAULT 0, visa40idcentro int NOT NULL DEFAULT 0, visa40fechainsc int NOT NULL DEFAULT 0, visa40fechaadmision int NOT NULL DEFAULT 0, visa40numcupo int NOT NULL DEFAULT 0, visa40idtipologia int NOT NULL DEFAULT 0, visa40idsubtipo int NOT NULL DEFAULT 0, visa40idminuta int NOT NULL DEFAULT 0, visa40idresolucion int NOT NULL DEFAULT 0)";}
	if ($dbversion==9443){$sSQL="ALTER TABLE visa40inscripcion ADD PRIMARY KEY(visa40id)";}
	if ($dbversion==9444){$sSQL=$objDB->sSQLCrearIndice('visa40inscripcion', 'visa40inscripcion_id', 'visa40idconvocatoria, visa40idtercero', true);}
	if ($dbversion==9445){$sSQL="agregamodulo|2940|29|Inscripcion convocatoria|1|2|3|4|5|6|8";}
	if ($dbversion==9446){$sSQL=$u09."(2940, 1, 'Inscripcion convocatoria', 'visaeinscripcion.php', 2908, 2940, 'S', '', '')";}
	if ($dbversion==9447){$sSQL="CREATE TABLE visa43inscripdocs (visa43idinscripcion int NOT NULL, visa43iddocumento int NOT NULL, visa43id int NOT NULL DEFAULT 0, visa43idorigen int NOT NULL DEFAULT 0, visa43idarchivo int NOT NULL DEFAULT 0, visa43fechaaprob int NOT NULL DEFAULT 0, visa43usuarioaprueba int NOT NULL DEFAULT 0)";}
	if ($dbversion==9448){$sSQL="ALTER TABLE visa43inscripdocs ADD PRIMARY KEY(visa43id)";}
	if ($dbversion==9449){$sSQL=$objDB->sSQLCrearIndice('visa43inscripdocs', 'visa43inscripdocs_id', 'visa43idinscripcion, visa43iddocumento', true);}
	if ($dbversion==9450){$sSQL=$objDB->sSQLCrearIndice('visa43inscripdocs', 'visa43inscripdocs_padre', 'visa43idinscripcion');}
	if ($dbversion==9451){$sSQL="agregamodulo|2943|29|Anexos|1|2|3|4|5|6|8";}
	if ($dbversion==9452){$sSQL="CREATE TABLE visa44anotaciones (visa44idinscripcion int NOT NULL, visa44consec int NOT NULL, visa44id int NOT NULL DEFAULT 0, visa44alcance int NOT NULL DEFAULT 0, visa44nota Text NULL, visa44usuario int NOT NULL DEFAULT 0, visa44fecha int NOT NULL DEFAULT 0, visa44hora int NOT NULL DEFAULT 0, visa44minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==9453){$sSQL="ALTER TABLE visa44anotaciones ADD PRIMARY KEY(visa44id)";}
	if ($dbversion==9454){$sSQL=$objDB->sSQLCrearIndice('visa44anotaciones', 'visa44anotaciones_id', 'visa44idinscripcion, visa44consec', true);}
	if ($dbversion==9455){$sSQL=$objDB->sSQLCrearIndice('visa44anotaciones', 'visa44anotaciones_padre', 'visa44idinscripcion');}
	if ($dbversion==9456){$sSQL="agregamodulo|2944|29|Anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==9457){$sSQL="CREATE TABLE visa45convpruebares (visa45idinscripcion int NOT NULL, visa45idprueba int NOT NULL, visa45id int NOT NULL DEFAULT 0, visa45puntaje int NOT NULL DEFAULT 0)";}
	if ($dbversion==9458){$sSQL="ALTER TABLE visa45convpruebares ADD PRIMARY KEY(visa45id)";}
	if ($dbversion==9459){$sSQL=$objDB->sSQLCrearIndice('visa45convpruebares', 'visa45convpruebares_id', 'visa45idinscripcion, visa45idprueba', true);}
	if ($dbversion==9460){$sSQL=$objDB->sSQLCrearIndice('visa45convpruebares', 'visa45convpruebares_padre', 'visa45idinscripcion');}
	if ($dbversion==9461){$sSQL="agregamodulo|2945|29|Resultados pruebas|1|2|3|4|5|6|8";}
	if ($dbversion==9462){$sSQL=$u09."(2945, 1, 'Resultados pruebas', 'visaepruebasres.php', 2908, 2945, 'S', '', '')";}
	// 2 de Marzo de 2026
	if ($dbversion==9463){$sSQL="DROP TABLE visa37convsubtipo";}
	if ($dbversion==9464){$sSQL="CREATE TABLE visa37convsubtipo (visa37idtipologia int NOT NULL, visa37consec int NOT NULL, visa37id int NOT NULL DEFAULT 0, visa37nombre varchar(50) NULL, visa37activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9465){$sSQL="ALTER TABLE visa37convsubtipo ADD PRIMARY KEY(visa37id)";}
	if ($dbversion==9466){$sSQL=$objDB->sSQLCrearIndice('visa37convsubtipo', 'visa37convsubtipo_id', 'visa37idtipologia, visa37consec', true);}
	if ($dbversion==9467){$sSQL=$objDB->sSQLCrearIndice('visa37convsubtipo', 'visa37convsubtipo_padre', 'visa37idtipologia');}

	if ($dbversion==9468){$sSQL="CREATE TABLE repo22permsem (repo22idcicloacad int NOT NULL, repo22idescuela int NOT NULL, repo22idsnies int NOT NULL, repo22idprograma int NOT NULL, repo22idzona int NOT NULL, repo22idcentro int NOT NULL, repo22cicloorigen int NOT NULL, repo22matricula int NOT NULL DEFAULT 0, repo22cambioprog int NOT NULL DEFAULT 0, repo22egresando int NOT NULL DEFAULT 0, repo22graduado int NOT NULL DEFAULT 0, repo22permanece int NOT NULL DEFAULT 0, repo22ausente int NOT NULL DEFAULT 0, repo22retirado int NOT NULL DEFAULT 0)";}
	if ($dbversion==9469){$sSQL="ALTER TABLE repo22permsem ADD PRIMARY KEY(repo22idcicloacad, repo22idescuela, repo22idsnies, repo22idprograma, repo22idzona, repo22idcentro, repo22cicloorigen)";}
	//3 - mar - 2026
	if ($dbversion==9470){$sSQL="agregamodulo|3721|37|Histórico de variables|1|2|3|4|5|6|8";}
	if ($dbversion==9471){$sSQL=$u09."(3721, 1, 'Histórico de variables', 'sigthistoricovar.php', 3701, 3721, 'S', '', '')";}

	if ($dbversion==9472){$sSQL="CREATE TABLE cart15recaudomasivo (cart15consec int NOT NULL, cart15id int NOT NULL DEFAULT 0, cart15vigencia int NOT NULL DEFAULT 0, cart15idtiporecaudo int NOT NULL DEFAULT 0, cart15fecha int NOT NULL DEFAULT 0, cart15cerrado int NOT NULL DEFAULT 0, cart15idzona int NOT NULL DEFAULT 0, cart15idsede int NOT NULL DEFAULT 0, cart15concepto Text NULL, cart15idcuenta int NOT NULL DEFAULT 0, cart15idformarec int NOT NULL DEFAULT 0, cart15idrecini int NOT NULL DEFAULT 0, cart15idrecfin int NOT NULL DEFAULT 0, cart15idusuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==9473){$sSQL="ALTER TABLE cart15recaudomasivo ADD PRIMARY KEY(cart15id)";}
	if ($dbversion==9474){$sSQL=$objDB->sSQLCrearIndice('cart15recaudomasivo', 'cart15recaudomasivo_id', 'cart15consec', true);}
	if ($dbversion==9475){$sSQL="agregamodulo|915|9|Recaudos masivos|1|2|3|4|5|6|8";}
	if ($dbversion==9476){$sSQL=$u09."(915, 1, 'Recaudos masivos', 'cartrecmasivo.php', 702, 915, 'S', '', '')";}
	if ($dbversion==9477){$sSQL="CREATE TABLE cart16recaudoitems (cart16idrecmasivo int NOT NULL, cart16consec int NOT NULL, cart16id int NOT NULL DEFAULT 0, cart16idtercero int NOT NULL DEFAULT 0, cart16vrrecaudo Decimal(15,2) NULL DEFAULT 0, cart16detalle Text NULL, cart16idrecaudo int NOT NULL DEFAULT 0, cart16consecrec int NOT NULL DEFAULT 0, cart16iditem int NOT NULL DEFAULT 0, cart16aplicado int NOT NULL DEFAULT 0, cart16idrecaplica int NOT NULL DEFAULT 0, cart16idformapago int NOT NULL DEFAULT 0)";}
	if ($dbversion==9478){$sSQL="ALTER TABLE cart16recaudoitems ADD PRIMARY KEY(cart16id)";}
	if ($dbversion==9479){$sSQL=$objDB->sSQLCrearIndice('cart16recaudoitems', 'cart16recaudoitems_id', 'cart16idrecmasivo, cart16consec', true);}
	if ($dbversion==9480){$sSQL=$objDB->sSQLCrearIndice('cart16recaudoitems', 'cart16recaudoitems_padre', 'cart16idrecmasivo');}
	if ($dbversion==9481){$sSQL="agregamodulo|916|9|Recaudo - items|1|2|3|4|5|6|8";}
	//4 - mar - 2026
	// 9482 - 9485 - Queda libre 
	if ($dbversion==9486){$sSQL="agregamodulo|1570|30|Equipos de trabajo - Perfiles|1|2|3|4|5|6";}

	if ($dbversion==9487){$sSQL="CREATE TABLE even67eventocategorias (even67idevento int NOT NULL, even67idcategoria int NOT NULL, even67id int NOT NULL DEFAULT 0, even67registromax int NOT NULL DEFAULT 0, even67gestionagrupo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9488){$sSQL="ALTER TABLE even67eventocategorias ADD PRIMARY KEY(even67id)";}
	if ($dbversion==9489){$sSQL=$objDB->sSQLCrearIndice('even67eventocategorias', 'even67eventocategorias_id', 'even67idevento, even67idcategoria', true);}
	if ($dbversion==9490){$sSQL=$objDB->sSQLCrearIndice('even67eventocategorias', 'even67eventocategorias_padre', 'even67idevento');}
	if ($dbversion==9491){$sSQL="agregamodulo|1967|19|Categorias|1|2|3|4|5|6|8";}
	if ($dbversion==9492){$sSQL="CREATE TABLE even66eventogrupos (even66idevento int NOT NULL, even66idcategoria int NOT NULL, even66idgrupo int NOT NULL, even66idsubgrupo int NOT NULL, even66id int NOT NULL DEFAULT 0, even66gestionatamano int NOT NULL DEFAULT 0, even66cantmin int NOT NULL DEFAULT 0, even66cantmax int NOT NULL DEFAULT 0)";}
	if ($dbversion==9493){$sSQL="ALTER TABLE even66eventogrupos ADD PRIMARY KEY(even66id)";}
	if ($dbversion==9494){$sSQL=$objDB->sSQLCrearIndice('even66eventogrupos', 'even66eventogrupos_id', 'even66idevento, even66idcategoria, even66idgrupo, even66idsubgrupo', true);}
	if ($dbversion==9495){$sSQL=$objDB->sSQLCrearIndice('even66eventogrupos', 'even66eventogrupos_padre', 'even66idevento');}
	if ($dbversion==9496){$sSQL="agregamodulo|1966|19|Grupos|1|2|3|4|5|6|8";}
	if ($dbversion==9497){$sSQL="CREATE TABLE even63grupoeventos (even63consec int NOT NULL, even63id int NOT NULL DEFAULT 0, even63idcategoria int NOT NULL DEFAULT 0, even63nombre varchar(100) NULL, even63activo int NOT NULL DEFAULT 0, even63orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==9498){$sSQL="ALTER TABLE even63grupoeventos ADD PRIMARY KEY(even63id)";}
	if ($dbversion==9499){$sSQL=$objDB->sSQLCrearIndice('even63grupoeventos', 'even63grupoeventos_id', 'even63consec', true);}
	if ($dbversion==9500){$sSQL="agregamodulo|1963|19|Grupos para eventos|1|2|3|4|5|6|8";}
}
if (($dbversion>9500)&&($dbversion<9601)){
	if ($dbversion==9501){$sSQL=$u09."(1963, 1, 'Grupos para eventos', 'evengrupos.php', 0, 1963, 'S', '', '')";}
	if ($dbversion==9502){$sSQL="CREATE TABLE even64subgrupos (even64idgrupo int NOT NULL, even64consec int NOT NULL, even64id int NOT NULL DEFAULT 0, even64nombre varchar(100) NULL, even64activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9503){$sSQL="ALTER TABLE even64subgrupos ADD PRIMARY KEY(even64id)";}
	if ($dbversion==9504){$sSQL=$objDB->sSQLCrearIndice('even64subgrupos', 'even64subgrupos_id', 'even64idgrupo, even64consec', true);}
	if ($dbversion==9505){$sSQL=$objDB->sSQLCrearIndice('even64subgrupos', 'even64subgrupos_padre', 'even64idgrupo');}
	if ($dbversion==9506){$sSQL="agregamodulo|1964|19|Subgrupos|1|2|3|4|5|6|8";}
	if ($dbversion==9507){$sSQL="CREATE TABLE even65categoriagrupo (even65consec int NOT NULL, even65id int NOT NULL DEFAULT 0, even65nombre varchar(100) NULL, even65activo int NOT NULL DEFAULT 0, even65porequipo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9508){$sSQL="ALTER TABLE even65categoriagrupo ADD PRIMARY KEY(even65id)";}
	if ($dbversion==9509){$sSQL=$objDB->sSQLCrearIndice('even65categoriagrupo', 'even65categoriagrupo_id', 'even65consec', true);}
	if ($dbversion==9510){$sSQL="agregamodulo|1965|19|Categorias para grupos|1|2|3|4|5|6|8";}
	if ($dbversion==9511){$sSQL=$u09."(1965, 1, 'Categorias para grupos', 'evencatgrupos.php', 0, 1965, 'S', '', '')";}
	if ($dbversion==9512) {$sSQL="UPDATE unad02modulos SET unad02nombre='Confirmación de recaudos - [3047]', unad02idsistema=9 WHERE unad02id=890";}

	if ($dbversion==9513){$sSQL="DROP TABLE bita30equipoperfil";}
	if ($dbversion==9514){$sSQL="CREATE TABLE bita70equipoperfil (bita70idequipotrab int NOT NULL, bita70idperfil int NOT NULL, bita70id int NOT NULL DEFAULT 0, bita70activa int NOT NULL DEFAULT 0)";}
	if ($dbversion==9515){$sSQL="ALTER TABLE bita70equipoperfil ADD PRIMARY KEY(bita70id)";}
	if ($dbversion==9516){$sSQL=$objDB->sSQLCrearIndice('bita70equipoperfil', 'bita70equipoperfil_id', 'bita70idequipotrab, bita70idperfil', true);}
	if ($dbversion==9517){$sSQL=$objDB->sSQLCrearIndice('bita70equipoperfil', 'bita70equipoperfil_padre', 'bita70idequipotrab');}
	// 6 - mar - 2026 
	if ($dbversion==9518){$sSQL=$objDB->sSQLCrearIndice('core16actamatricula', 'core16actamatricula_procesado', 'core16procesado');}
	if ($dbversion==9519){$sSQL=$objDB->sSQLCrearIndice('core16actamatricula', 'core16actamatricula_errmat', 'core16errormatricula');}
	if ($dbversion==9520){$sSQL=$objDB->sSQLCrearIndice('core16actamatricula', 'core16actamatricula_periodo', 'core16peraca');}
	if ($dbversion==9521){$sSQL=$objDB->sSQLCrearIndice('core16actamatricula', 'core16actamatricula_cron', 'core16procesado, core16errormatricula, core16tipomatricula');}
	if ($dbversion==9522){$sSQL=$objDB->sSQLCrearIndice('ofer08oferta', 'ofer08oferta_director', 'ofer08idacomanamento');}

	if ($dbversion==9523){$sSQL="CREATE TABLE gcmo24rangovariables (gcmo24consec int NOT NULL, gcmo24id int NOT NULL DEFAULT 0, gcmo24nombre varchar(100) NULL, gcmo24activo int NOT NULL DEFAULT 0, gcmo24orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==9524){$sSQL="ALTER TABLE gcmo24rangovariables ADD PRIMARY KEY(gcmo24id)";}
	if ($dbversion==9525){$sSQL=$objDB->sSQLCrearIndice('gcmo24rangovariables', 'gcmo24rangovariables_id', 'gcmo24consec', true);}
	if ($dbversion==9526){$sSQL="agregamodulo|3724|37|Rangos para variables|1|2|3|4|5|6|8";}
	if ($dbversion==9527){$sSQL=$u09."(3724, 1, 'Rangos para variables', 'gcmorangovariables.php', 2, 3724, 'S', '', '')";}
	if ($dbversion==9528){$sSQL="CREATE TABLE gcmo25rangovaritems (gcmo25idrango int NOT NULL, gcmo25consec int NOT NULL, gcmo25id int NOT NULL DEFAULT 0, gcmo25nombre varchar(100) NULL, gcmo25activo int NOT NULL DEFAULT 0, gcmo25orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==9529){$sSQL="ALTER TABLE gcmo25rangovaritems ADD PRIMARY KEY(gcmo25id)";}
	if ($dbversion==9530){$sSQL=$objDB->sSQLCrearIndice('gcmo25rangovaritems', 'gcmo25rangovaritems_id', 'gcmo25idrango, gcmo25consec', true);}
	if ($dbversion==9531){$sSQL=$objDB->sSQLCrearIndice('gcmo25rangovaritems', 'gcmo25rangovaritems_padre', 'gcmo25idrango');}
	if ($dbversion==9532){$sSQL="agregamodulo|3725|37|Rango para variables-items|1|2|3|4|5|6|8";}
	if ($dbversion==9533){$sSQL="CREATE TABLE gcmo26rangoetario (gcmo26consec int NOT NULL, gcmo26id int NOT NULL DEFAULT 0, gcmo26nombre varchar(100) NULL, gcmo26activo int NOT NULL DEFAULT 0, gcmo26orden int NOT NULL DEFAULT 0)";}
	if ($dbversion==9534){$sSQL="ALTER TABLE gcmo26rangoetario ADD PRIMARY KEY(gcmo26id)";}
	if ($dbversion==9535){$sSQL=$objDB->sSQLCrearIndice('gcmo26rangoetario', 'gcmo26rangoetario_id', 'gcmo26consec', true);}
	if ($dbversion==9536){$sSQL="agregamodulo|3726|37|Rangos etarios|1|2|3|4|5|6|8";}
	if ($dbversion==9537){$sSQL=$u09."(3726, 1, 'Rangos etarios', 'gcmorangoetario.php', 2, 3726, 'S', '', '')";}
	if ($dbversion==9538){$sSQL="CREATE TABLE gcmo27rangoetarioitems (gcmo27idrango int NOT NULL, gcmo27consec int NOT NULL, gcmo27id int NOT NULL DEFAULT 0, gcmo27nombre varchar(100) NULL, gcmo27activo int NOT NULL DEFAULT 0, gcmo27orden int NOT NULL DEFAULT 0, gcmo27edadminima int NOT NULL DEFAULT 0, gcmo27edadmaxima int NOT NULL DEFAULT 0)";}
	if ($dbversion==9539){$sSQL="ALTER TABLE gcmo27rangoetarioitems ADD PRIMARY KEY(gcmo27id)";}
	if ($dbversion==9540){$sSQL=$objDB->sSQLCrearIndice('gcmo27rangoetarioitems', 'gcmo27rangoetarioitems_id', 'gcmo27idrango, gcmo27consec', true);}
	if ($dbversion==9541){$sSQL=$objDB->sSQLCrearIndice('gcmo27rangoetarioitems', 'gcmo27rangoetarioitems_padre', 'gcmo27idrango');}
	if ($dbversion==9542){$sSQL="agregamodulo|3727|37|Rangos etarios - items|1|2|3|4|5|6|8";}

	if ($dbversion==9543){$sSQL="ALTER TABLE olab77consolidadocp DROP PRIMARY KEY";}
	if ($dbversion==9544){$sSQL="ALTER TABLE olab77consolidadocp ADD PRIMARY KEY(olab77id)";}
	if ($dbversion==9545){$sSQL=$objDB->sSQLCrearIndice('olab77consolidadocp', 'olab77consolidadocp_id', 'olab77idmatricula', true);}
	// 9 - mar - 2026
	if ($dbversion==9546){$sSQL="add_campos|grad25tipoanexoproyecto|grad25idprograma int NOT NULL DEFAULT 0";}	
	// 12 -mar - 2026
	if ($dbversion==9547){$sSQL="add_campos|cttc37minutas|cttc37idbanco int NOT NULL DEFAULT 0|cttc37idcuentabanco int NOT NULL DEFAULT 0";}
	if ($dbversion==9548){$sSQL="add_campos|teso34planpagodet|teso34porcamoritzar Decimal(15,2) NULL DEFAULT 0|teso34valoramortizar Decimal(15,2) NULL DEFAULT 0|teso34idbanco int NOT NULL DEFAULT 0|teso34idcuentabanco int NOT NULL DEFAULT 0|teso34numpago int NOT NULL DEFAULT 0|teso34agno int NOT NULL DEFAULT 0|teso34mes int NOT NULL DEFAULT 0";}
	if ($dbversion==9549){$sSQL=$objDB->sSQLCrearIndice('teso34planpagodet', 'teso34planpagodet_agno', 'teso34agno');}
	if ($dbversion==9550){$sSQL=$objDB->sSQLCrearIndice('teso34planpagodet', 'teso34planpagodet_mes', 'teso34mes');}
	
	if ($dbversion==9551){$sSQL="CREATE TABLE even61equipos (even61idevento int NOT NULL, even61consec int NOT NULL, even61id int NOT NULL DEFAULT 0, even61idlider int NOT NULL DEFAULT 0, even61nombre varchar(100) NULL, even61idcategoria int NOT NULL DEFAULT 0, even61idgrupo int NOT NULL DEFAULT 0, even61idsubgrupo int NOT NULL DEFAULT 0, even61estado int NOT NULL DEFAULT 0, even61numinvitados int NOT NULL DEFAULT 0, even61numparticipantes int NOT NULL DEFAULT 0, even61fechaconforma int NOT NULL DEFAULT 0, even61fecharatifica int NOT NULL DEFAULT 0)";}
	if ($dbversion==9552){$sSQL="ALTER TABLE even61equipos ADD PRIMARY KEY(even61id)";}
	if ($dbversion==9553){$sSQL=$objDB->sSQLCrearIndice('even61equipos', 'even61equipos_id', 'even61idevento, even61consec', true);}
	if ($dbversion==9554){$sSQL="agregamodulo|1961|19|Equipos|1|2|3|4|5|6|8";}
	if ($dbversion==9555){$sSQL="CREATE TABLE even62miembroequipo (even62idequipo int NOT NULL, even62idtercero int NOT NULL, even62id int NOT NULL DEFAULT 0, even62idevento int NOT NULL DEFAULT 0, even62fechainvita int NOT NULL DEFAULT 0, even62fechaacepta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9556){$sSQL="ALTER TABLE even62miembroequipo ADD PRIMARY KEY(even62idequipo, even62idtercero)";}
	if ($dbversion==9557){$sSQL=$objDB->sSQLCrearIndice('even62miembroequipo', 'even62miembroequipo_padre', 'even62idequipo');}
	if ($dbversion==9558){$sSQL="agregamodulo|1962|19|Miembros|1|2|3|4|5|6";}
	// 13 de mar - 2026
	if ($dbversion==9559){$sSQL="add_campos|inve01bodega|inve01zona int NOT NULL DEFAULT 0|inve01centro int NOT NULL DEFAULT 0";}
	if ($dbversion==9560){$sSQL="add_campos|inve13almacenistas|inve13fechafinal int NOT NULL DEFAULT 0| inve13activo int NOT NULL DEFAULT 1|inve13ifechaini int NOT NULL DEFAULT 0";}
	if ($dbversion==9561){$sSQL="add_campos|nico02centrocosto|nico02orden int NOT NULL DEFAULT 0|nico02unidadfunc int NOT NULL DEFAULT 0|nico02escuela int NOT NULL DEFAULT 0|nico02programa int NOT NULL DEFAULT 0|nico02zona int NOT NULL DEFAULT 0|nico02centro int NOT NULL DEFAULT 0";}
	if ($dbversion==9562){$sSQL=$u96."(2935,0,'Borrador',100),(2935,1,'Abierta',101),
	(2935,5,'Verificación de Requisitos',105),(2935,11,'Legalización',111),
	(2935,15,'Pruebas de Ingreso',115),(2935,21,'Selección de Aspirantes',121),
	(2935,25,'Admisión',125),(2935,31,'Cerrada',131),
	(2940,0,'Borrador',100),(2940,1,'Preinscripción',101),
	(2940,3,'Devolución de documentos',103),(2940,5,'Inscrito',105),
	(2940,7,'Admitido',107),(2940,8,'Lista de espera',108),
	(2940,9,'No admitido',109),(2940,11,'Desistido',111),
	(2940,17,'Cupo confirmado',117)";}
	//16 de mar - 2026
	if ($dbversion==9563){$sSQL="CREATE TABLE corg37pruebarango (corg37idprueba int NOT NULL, corg37consec int NOT NULL, corg37id int NOT NULL DEFAULT 0, corg37base int NOT NULL DEFAULT 0, corg37tope int NOT NULL DEFAULT 0, corg37nivel int NOT NULL DEFAULT 0)";}
	if ($dbversion==9564){$sSQL="ALTER TABLE corg37pruebarango ADD PRIMARY KEY(corg37id)";}
	if ($dbversion==9565){$sSQL=$objDB->sSQLCrearIndice('corg37pruebarango', 'corg37pruebarango_id', 'corg37idprueba, corg37consec', true);}
	if ($dbversion==9566){$sSQL=$objDB->sSQLCrearIndice('corg37pruebarango', 'corg37pruebarango_padre', 'corg37idprueba');}
	if ($dbversion==9567){$sSQL="agregamodulo|4737|22|Prueba idioma - rango|1|2|3|4|5|6|8";}
	if ($dbversion==9568){$sSQL="add_campos|corg35certidioma|corg35fecharadicado int NOT NULL DEFAULT 0";}
	//	$u96="INSERT INTO unad96estado (unad96idmodulo, unad96id, unad96nombre, unad96etiqueta) VALUES ";
	if ($dbversion==9569){$sSQL="UPDATE unad96estado SET unad96nombre='En proceso de pago' WHERE unad96idmodulo=4735 AND unad96id=5";}
	if ($dbversion==9570){$sSQL="ALTER TABLE visa37convsubtipo CHANGE visa37nombre visa37nombre VARCHAR(100)";}
	//17 de mar - 2026
	if ($dbversion==9571){$sSQL="add_campos|even61equipos|even61idorigen int NOT NULL DEFAULT 0|even61idbanner int NOT NULL DEFAULT 0|even62emp_1 varchar(100) NULL|even61emp_2 varchar(250) NULL|even61emp_3 int NOT NULL DEFAULT 0|even61emp_4 int NOT NULL DEFAULT 0|even61emp_5 int NOT NULL DEFAULT 0|even61emp_6 varchar(200) NULL";}
	if ($dbversion==9572){$sSQL="add_campos|even62miembroequipo|even62idorigen int NOT NULL DEFAULT 0|even62idcertmedico int NOT NULL DEFAULT 0|even62preg_1 int NOT NULL DEFAULT 0|even62preg_2 int NOT NULL DEFAULT 0|even62preg_3 varchar(200) NULL";}

	if ($dbversion==9573){$sSQL="add_campos|comp01solicitud|comp01fecharadicado int NOT NULL DEFAULT 0";}
	if ($dbversion==9574){$sSQL="UPDATE comp01solicitud SET comp01fecharadicado=comp01fecha WHERE comp01estado IN (7, 11)";}
	//18 - mar - 2026
	if ($dbversion==9575){$sSQL=$u04."(4735, 17, 'S'), (4735, 1707, 'S')";}
	if ($dbversion==9576){$sSQL=$u09."(1961, 1, 'Equipos', 'evenequipos.php', 1901, 1961, 'S', '', '')";}
	//19 - mar - 2026
	if ($dbversion==9577){$sSQL="add_campos|corg35certidioma|corg35idproducto int NOT NULL DEFAULT 0|corg35vigfactura int NOT NULL DEFAULT 0|corg35idfactura int NOT NULL DEFAULT 0|corg35vrpagado int NOT NULL DEFAULT 0";}
	if ($dbversion==9578){$sSQL="add_campos|core01estprograma|core01bloqueado int NOT NULL DEFAULT 0";}
	if ($dbversion==9579){$sSQL="DROP TABLE masi73estadomensaje";}
	if ($dbversion==9580){$sSQL=$u96."(1205, 0, 'Borrador', 100), (1205, 3, 'Completo', 103), (1205, 7, 'Enviado', 107)";}
	if ($dbversion==9581){$sSQL="CREATE TABLE masi73tiponoti (masi73id int NOT NULL, masi73nombre varchar(100) NULL, masi73etiqueta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9582){$sSQL="ALTER TABLE masi73tiponoti ADD PRIMARY KEY(masi73id)";}
	if ($dbversion==9583){$sSQL="INSERT INTO masi73tiponoti (masi73id, masi73nombre, masi73etiqueta) VALUES (0, 'Específico', 0), (11, 'General', 11), (21, 'Recurrente', 21)";}

	if ($dbversion==9584){$sSQL="agregamodulo|1205|12|Mensajes masivos|1|2|3|4|5|6|12|17";}
	if ($dbversion==9585){$sSQL=$u09."(1205, 1, 'Mensajes masivos', 'unadmasivo.php', 1201, 1205, 'S', '', '')";}
	if ($dbversion==9586){$sSQL="agregamodulo|1206|12|Mensajes masivos - Poblacion|1|2|3|4|5|6";}
	if ($dbversion==9587){$sSQL="agregamodulo|1207|12|Mensajes masivos - Anexo|1|2|3|4|5|6";}
	if ($dbversion==9588){$sSQL="agregamodulo|1208|12|Mensajes masivos - Destinatario|1|2|3|4|5|6";}
	//20 - mar - 2025
	if ($dbversion==9589){$sSQL="UPDATE unad09modulomenu SET unad09pagina='unadmasfirmas.php' WHERE unad09idmodulo=1209 AND unad09consec=1";}
	if ($dbversion==9590){$sSQL="agregamodulo|1968|19|Equipos por evento|1|2|3|4|5|6";}
	if ($dbversion==9591){$sSQL=$u09."(1968, 1, 'Equipos por evento', 'evenrptequipos.php', 11, 1968, 'S', '', '')";}
	//21 -mar - 2025
	if ($dbversion==9592){$sSQL="add_campos|cart01productos|cart01clase int NOT NULL DEFAULT 0";}
	if ($dbversion==9593){$sSQL="CREATE TABLE cart71tipoproductoacad (cart71id int NOT NULL, cart71nombre varchar(100) NULL, cart71etiqueta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9594){$sSQL="ALTER TABLE cart71tipoproductoacad ADD PRIMARY KEY(cart71id)";}
	if ($dbversion==9595){$sSQL="INSERT INTO cart71tipoproductoacad (cart71id, cart71nombre, cart71etiqueta) VALUES (0, '{Ninguno}', 100), (1, 'Creditos', 101), (2, 'Situaciones académicas - Homologaciones', 102), (3, 'Habilitaciones y Supletorios', 103), (5, 'Matricula por ciclo', 105), (6, 'Cursos MOOC', 106), (11, 'Admisiones', 111), (21, 'Eventos', 121), (31, 'Idiomas', 131), (41, 'Certificaciones', 141), (71, 'Grados', 171)";}
	if ($dbversion==9595){$sSQL="UPDATE cart01productos SET cart01clase=1 WHERE cart01cursos=1";}
	//26 - mar - 2026
	if ($dbversion==9596){$sSQL="ALTER TABLE even62miembroequipo MODIFY even62preg_3 int NOT NULL DEFAULT 0";}
	if ($dbversion==9597){$sSQL="add_campos|even62miembroequipo|even62preg_4 int NOT NULL DEFAULT 0|even62preg_5 int NOT NULL DEFAULT 0";}
	if ($dbversion==9598){$sSQL="add_campos|even61equipos|even61idorigen int NOT NULL DEFAULT 0|even61idbanner int NOT NULL DEFAULT 0|even61idorigenarc1 int NOT NULL DEFAULT 0|even61idarchivo1 int NOT NULL DEFAULT 0|even61idorigenarc2 int NOT NULL DEFAULT 0|even61idarchivo2 int NOT NULL DEFAULT 0";}

	if ($dbversion==9599){$sSQL="agregamodulo|2763|27|Reporte de postulados|1|5|6";}
	if ($dbversion==9600){$sSQL=$u09."(2763, 1, 'Postulados', 'gradrptpostulados.php', 11, 2763, 'S', '', '')";}
	}
if (($dbversion>9600)&&($dbversion<9701)){
	if ($dbversion==9601){$sSQL=$objDB->sSQLCrearIndice('olab20notificahorario', 'olab20notificahorario_cupo', 'olab20idcupo');}

	// 27 de marzo de 2026
	if ($dbversion==9602){$sSQL="CREATE TABLE masi00config (masi00id int NOT NULL, masi00idformato int NOT NULL DEFAULT 0, masi00formaenvios int NOT NULL DEFAULT 0)";}
	if ($dbversion==9603){$sSQL="ALTER TABLE masi00config ADD PRIMARY KEY(masi00id)";}
	if ($dbversion==9604){$sSQL="agregamodulo|1200|12|Parametros|1|3";}
	if ($dbversion==9605){$sSQL=$u09."(1200, 1, 'Parametros', 'masiparams.php', 2, 1200, 'S', '', '')";}
	if ($dbversion==9606){$sSQL="CREATE TABLE masi10formato (masi10consec int NOT NULL, masi10id int NOT NULL DEFAULT 0, masi1oactivo int NOT NULL DEFAULT 0, masi10titulo varchar(100) NULL, masi10encabezado Text NULL, masi10divcuerpo Text NULL, masi10divcodigocorreo Text NULL, masi10divcodigoconfirma Text NULL, masi10divcodigorecupera Text NULL, masi10divfirma Text NULL, masi10piedepagina Text NULL)";}
	if ($dbversion==9607){$sSQL="ALTER TABLE masi10formato ADD PRIMARY KEY(masi10id)";}
	if ($dbversion==9608){$sSQL=$objDB->sSQLCrearIndice('masi10formato', 'masi10formato_id', 'masi10consec', true);}
	if ($dbversion==9609){$sSQL="agregamodulo|1210|12|Formatos de correo|1|2|3|4|5|6";}
	if ($dbversion==9610){$sSQL=$u09."(1210, 1, 'Formatos de correo', 'masiformatocorreo.php', 2, 1210, 'S', '', '')";}
	if ($dbversion==9611){$sSQL="INSERT INTO masi00config (masi00id, masi00idformato, masi00formaenvios) VALUES (1, 0, 0)";}
	
	//28 mar de 2026
	if ($dbversion==9612){$sSQL="CREATE TABLE grad38xml (grad38idcohorte int NOT NULL, grad38consec int NOT NULL, grad38id int NOT NULL DEFAULT 0, grad38contador int NOT NULL DEFAULT 0, grad38graduandos int NOT NULL DEFAULT 0, grad38anulado int NOT NULL DEFAULT 0)";}
	if ($dbversion==9613){$sSQL="ALTER TABLE grad38xml ADD PRIMARY KEY(grad38id)";}
	if ($dbversion==9614){$sSQL=$objDB->sSQLCrearIndice('grad38xml', 'grad38xml_id', 'grad38idcohorte, grad38consec', true);}
	if ($dbversion==9615){$sSQL=$objDB->sSQLCrearIndice('grad38xml', 'grad38xml_padre', 'grad38idcohorte');}
	if ($dbversion==9616){$sSQL="agregamodulo|2738|27|Postulados - Envios xml|1|2|3|4|5|6";}
	if ($dbversion==9617){$sSQL="add_campos|grad41postulaciones|grad41idxml int NOT NULL DEFAULT 0|grad41val_aprob int NOT NULL DEFAULT 0|grad41val_est Text NULL|grad41val_lider Text NULL|grad41val_grad Text NULL";}
	//29 de marzo de 2026
	if ($dbversion==9618){$sSQL="add_campos|grad38xml|grad38errores int NOT NULL DEFAULT 0";}
	//30  de marzo de 2026
	if ($dbversion==9619){$sSQL=$u04."(2236, 12, 'S')";}
	//31 de marzo de 2026
	if ($dbversion==9620){$sSQL=$u04."(2740, 12, 'S')";}
	if ($dbversion==9621){$sSQL="add_campos|core38opciongrado|core38numeroopcion int NOT NULL DEFAULT 0|core38idescuela int NOT NULL DEFAULT 0";}
	if ($dbversion==9622){$sSQL="UPDATE core38opciongrado SET core38numeroopcion=core38id, core38idescuela=0";}
	if ($dbversion==9623){$sSQL=$objDB->sSQLCrearIndice('core38opciongrado', 'core38opciongrado_id', 'core38numeroopcion, core38idescuela', true);}
	if ($dbversion==9624){$sSQL="agregamodulo|2705|27|Opciones de grado|1|2|3|4|5|6";}
	if ($dbversion==9625){$sSQL=$u09."(2705, 1, 'Opciones de grado', 'gradopcion.php', 2, 2705, 'S', '', '')";}
	if ($dbversion==9626){$sSQL="add_campos|grad40trabajogradoalterno|grad40idescuela int NOT NULL DEFAULT 0|grad40idprograma int NOT NULL DEFAULT 0|grad40idzona int NOT NULL DEFAULT 0|grad40idcentro int NOT NULL DEFAULT 0|grad40origen int NOT NULL DEFAULT 0|grad40enlacesoporte varchar(250) NULL";}
	if ($dbversion==9627){$sSQL="ALTER TABLE grad40trabajogradoalterno DROP INDEX grad40trabajogradoalterno_id";}
	if ($dbversion==9628){$sSQL=$objDB->sSQLCrearIndice('grad40trabajogradoalterno', 'grad40trabajogradoalterno_id', 'grad40idpei', true);}
	if ($dbversion==9629){$sSQL="add_campos|core38opciongrado|core38historicos int NOT NULL DEFAULT 0|core38titulocomplementario int NOT NULL DEFAULT 0";}
	// 1 de Abril de 2026
	if ($dbversion==9630){$sSQL="CREATE TABLE core49gruponivelforma (core49id int NOT NULL, core49nombre varchar(50) NULL, core49etiqueta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9631){$sSQL="ALTER TABLE core49gruponivelforma ADD PRIMARY KEY(core49id)";}
	if ($dbversion==9632){$sSQL="INSERT INTO core49gruponivelforma (core49id, core49nombre, core49etiqueta) VALUES (0, '{Ninguno}', 100), (1, 'Básica', 101), (2, 'Continuada', 102), (3, 'Profesional', 103), (4, 'PostGrado', 104)";}
	if ($dbversion==9633){$sSQL="add_campos|core38opciongrado|core38cred_tipocurso int NOT NULL DEFAULT 0|core38cred_cantidad int NOT NULL DEFAULT 0|core38proy_titulo int NOT NULL DEFAULT 1";}
	//Tenemos que actualizar la core38opciongrado para que no de problema
	if ($dbversion==9634){$sSQL="UPDATE core38opciongrado SET core38cred_tipocurso=9, core38cred_cantidad=10 WHERE core38id=5";}
	if ($dbversion==9635){$sSQL="UPDATE core38opciongrado SET core38cred_tipocurso=10, core38cred_cantidad=10 WHERE core38id=4";}
	if ($dbversion==9636){$sSQL="UPDATE core38opciongrado SET core38cred_tipocurso=11, core38cred_cantidad=3 WHERE core38id=15";}
	if ($dbversion==9637){$sSQL="UPDATE core38opciongrado SET core38cred_tipocurso=12, core38cred_cantidad=6 WHERE core38id=16";}
	if ($dbversion==9638){$sSQL="UPDATE core38opciongrado SET core38cred_tipocurso=13, core38cred_cantidad=8 WHERE core38id=9";}
	if ($dbversion==9639){$sSQL="add_campos|core13tiporegistroprog|core13opciongradoclase int NOT NULL DEFAULT 0";}
	if ($dbversion==9640){$sSQL="UPDATE core13tiporegistroprog SET core13opciongradoclase=1 WHERE core13id IN (10,11)";}
	if ($dbversion==9641){$sSQL="UPDATE core13tiporegistroprog SET core13opciongradoclase=2 WHERE core13id IN (9,12,13)";}
	if ($dbversion==9642){$sSQL=$u04."(2740, 14, 'S')";} //Acceder a datos reservados en grados.
	if ($dbversion==9643){$sSQL="ALTER TABLE grad40trabajogradoalterno CHANGE grad40titulo grad40titulo VARCHAR(500)";}
	// 4 de Abril de 2026
	if ($dbversion==9644){$sSQL="add_campos|grad01cohortes|grad01idlibrodiploma int NOT NULL DEFAULT 0|grad01idlibroactas int NOT NULL DEFAULT 0";}
	if ($dbversion==9645){$sSQL="add_campos|grad45estadosolgrad|grad45etiqueta int NOT NULL DEFAULT 0";}
	if ($dbversion==9646){$sSQL="UPDATE grad45estadosolgrad SET grad45etiqueta=(grad45id+100)";}
	if ($dbversion==9647){$sSQL="INSERT INTO grad45estadosolgrad (grad45id, grad45nombre, grad45etiqueta) VALUES (36, 'Terminada', 136)";}

	if ($dbversion==9648){$sSQL="CREATE TABLE grad56cohorteresol (grad56idcohorte int NOT NULL, grad56idcentro int NOT NULL, grad56idescuela int NOT NULL, grad56id int NOT NULL DEFAULT 0, grad56idacta int NOT NULL DEFAULT 0, grad56numacta int NOT NULL DEFAULT 0, grad56fechaacta int NOT NULL DEFAULT 0, grad56cantidad int NOT NULL DEFAULT 0)";}
	if ($dbversion==9649){$sSQL="ALTER TABLE grad56cohorteresol ADD PRIMARY KEY(grad56id)";}
	if ($dbversion==9650){$sSQL=$objDB->sSQLCrearIndice('grad56cohorteresol', 'grad56cohorteresol_id', 'grad56idcohorte, grad56idcentro, grad56idescuela', true);}
	if ($dbversion==9651){$sSQL=$objDB->sSQLCrearIndice('grad56cohorteresol', 'grad56cohorteresol_padre', 'grad56idcohorte');}
	if ($dbversion==9652){$sSQL="agregamodulo|2756|27|Cohortes - Resoluciones|1|2|3|4|5|6";}

	// 6 de Abril de 2026
	if ($dbversion==9653){$sSQL="add_campos|cart16recaudoitems|cart16idrecexistente int NOT NULL DEFAULT 0";}
	// 7 de abril de 2026
	if ($dbversion==9654){$sSQL="agregamodulo|921|9|Ingresos diarios|1|2|3|4|5|6";}
	if ($dbversion==9655){$sSQL=$u09."(921, 1, 'Ingresos diarios', 'cartrptingdiario.php', 11, 921, 'S', '', '')";}
	if ($dbversion==9656){$sSQL="INSERT INTO gedo50resoluciones (gedo50vigencia, gedo50numsol, gedo50id, gedo50origen_proceso, gedo50origen_comp, gedo50origen_id, gedo50estado, gedo50unidad, gedo50escuela, gedo50zona, gedo50centro, gedo50asunto, gedo50fechasolicitada, gedo50salida_id, gedo50salida_fecha, gedo50salida_numero, gedo50beneficiario_id, gedo50beneficiario_vr, gedo50beneficiario_4x1000) VALUES (0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0)";}
	//9 de Abril de 2026
	if ($dbversion==9657){$sSQL="UPDATE comp73estadoestudmer SET comp73nombre='Seleccionada' WHERE comp73id=7";}
	if ($dbversion==9658){$sSQL="add_campos|cttc07proceso|cttc07e1_justificacion Text NULL|cttc07e1_idinvitacion int NOT NULL DEFAULT 0|cttc07e2_contrato_minuta int NOT NULL DEFAULT 0|cttc07e2_fechaactainicio int NOT NULL DEFAULT 0|cttc07e2_fechainicio int NOT NULL DEFAULT 0|cttc07e2_fechafinal int NOT NULL DEFAULT 0|cttc07e2_fechaterminacion int NOT NULL DEFAULT 0|cttc07e2_diasduracion_ini int NOT NULL DEFAULT 0|cttc07e2_diasduracion_total int NOT NULL DEFAULT 0|cttc07e2_vranticipo Decimal(15,2) NULL DEFAULT 0|cttc07e2_vranticipopagado Decimal(15,2) NULL DEFAULT 0|cttc07e2_vranticipolegalizado Decimal(15,2) NULL DEFAULT 0|cttc07e2_porcejecutado Decimal(15,2) NULL DEFAULT 0|cttc07e2_valorejecutado Decimal(15,2) NULL DEFAULT 0|cttc07e2_valornoejecutado Decimal(15,2) NULL DEFAULT 0|cttc07e2_porcpagado Decimal(15,2) NULL DEFAULT 0|cttc07e2_valorpagado Decimal(15,2) NULL DEFAULT 0|cttc07e2_valorreintegrado Decimal(15,2) NULL DEFAULT 0|cttc07e3termina_estado int NOT NULL DEFAULT 0|cttc07e3termina_abogado int NOT NULL DEFAULT 0|cttc07e3termina_fecha int NOT NULL DEFAULT 0|cttc07e3secop_codigo varchar(250) NULL|cttc07e3secop_link Text NULL|cttc07func_jur_fecha int NOT NULL DEFAULT 0|cttc07func_jur_hora int NOT NULL DEFAULT 0|cttc07func_jur_minuto int NOT NULL DEFAULT 0";}
	
	//10 de Abril de 2026
	if ($dbversion==9659){$sSQL="agregamodulo|2771|27|Paises|1|3|5|6";}
	if ($dbversion==9660){$sSQL=$u09."(2771, 1, 'Paises', 'gradpais.php', 2, 2771, 'S', '', '')";}
	if ($dbversion==9661){$sSQL="agregamodulo|2772|27|Departamentos|1|3|5|6";}
	if ($dbversion==9662){$sSQL=$u09."(2772, 1, 'Departamentos', 'graddepto.php', 2, 2772, 'S', '', '')";}
	if ($dbversion==9663){$sSQL="agregamodulo|2773|27|Ciudades|1|3|5|6";}
	if ($dbversion==9664){$sSQL=$u09."(2773, 1, 'Ciudades', 'gradciudad.php', 2, 2773, 'S', '', '')";}

	if ($dbversion==9665){$sSQL="add_campos|unad18pais|unad18codgrados varchar(3) NULL";}
	if ($dbversion==9666){$sSQL="add_campos|unad19depto|unad19codgrados varchar(5) NULL";}
	if ($dbversion==9667){$sSQL="add_campos|unad20ciudad|unad20codgrados varchar(8) NULL";}

	if ($dbversion==9668){$sSQL="agregamodulo|2757|27|Otras postulaciones|1|2|3|4|5|6";}
	if ($dbversion==9669){$sSQL=$u09."(2757, 1, 'Otras postulaciones', 'gradpostuladootros.php', 2701, 2757, 'S', '', '')";}
	// 17 de Abril de 2026
	if ($dbversion==9670){$sSQL="add_campos|grad03tipodocgrad|grad03aplicaotraspost int NOT NULL DEFAULT 1";}
	// 20 de Abril de 2026 
	if ($dbversion==9671){$sSQL="add_campos|cttc76aportantes|cttc76actorautorizado int NOT NULL DEFAULT 0|cttc76mensajebloqueo Text NULL";}
	if ($dbversion==9672){$sSQL="add_campos|core01estprograma|core01grado_tipo int NOT NULL DEFAULT 1";}
	// 21 de Abril de 2026
	if ($dbversion==9673){$sSQL="CREATE TABLE visa11aspirante (visa11consec int NOT NULL, visa11id int NOT NULL DEFAULT 0, visa11idtercero int NOT NULL DEFAULT 0, visa11fechainicioreg int NOT NULL DEFAULT 0, visa11estado int NOT NULL DEFAULT 0, visa11idcanalreg int NOT NULL DEFAULT 0, visa11idzona int NOT NULL DEFAULT 0, visa11idcentro int NOT NULL DEFAULT 0, visa11titulobachiller int NOT NULL DEFAULT 0, visa11titulouniversitario int NOT NULL DEFAULT 0, visa11egresado_unad int NOT NULL DEFAULT 0, visa11egresado_programa int NOT NULL DEFAULT 0, visa11egresado_fecha int NOT NULL DEFAULT 0, visa11estudiante int NOT NULL DEFAULT 0, visa11reingreso int NOT NULL DEFAULT 0, visa11reingreso_programa int NOT NULL DEFAULT 0, visa11cursovocacional int NOT NULL DEFAULT 0, visa11curso_fechaing int NOT NULL DEFAULT 0, visa11curso_fechafin int NOT NULL DEFAULT 0, visa11idconsejero int NOT NULL DEFAULT 0, visa11idasesor int NOT NULL DEFAULT 0, visa11interesprevio int NOT NULL DEFAULT 0, visa11int_idunidad int NOT NULL DEFAULT 0, visa11int_idescuela int NOT NULL DEFAULT 0, visa11int_idprograma int NOT NULL DEFAULT 0, visa11idunidad int NOT NULL DEFAULT 0, visa11idescuela int NOT NULL DEFAULT 0, visa11idprograma int NOT NULL DEFAULT 0, visa11idcursomooc int NOT NULL DEFAULT 0, visa11interesadohomol int NOT NULL DEFAULT 0, visa11homol_idconvenio int NOT NULL DEFAULT 0, visa11volvercontactar int NOT NULL DEFAULT 0, visa11fechaproxcontacto int NOT NULL DEFAULT 0, visa11indicacionescont Text NULL, visa11generarecibo int NOT NULL DEFAULT 0, visa11fechalimiterecibo int NOT NULL DEFAULT 0, visa11idperiodorecibo int NOT NULL DEFAULT 0, visa11fechacierre int NOT NULL DEFAULT 0, visa11idresultado int NOT NULL DEFAULT 0, visa11idcausadesiste int NOT NULL DEFAULT 0, visa11idplanestudio int NOT NULL DEFAULT 0, visa11prog_admision int NOT NULL DEFAULT 0)";}
	if ($dbversion==9674){$sSQL="ALTER TABLE visa11aspirante ADD PRIMARY KEY(visa11id)";}
	if ($dbversion==9675){$sSQL=$objDB->sSQLCrearIndice('visa11aspirante', 'visa11aspirante_id', 'visa11consec', true);}
	if ($dbversion==9676){$sSQL="agregamodulo|5011|29|Aspirantes|1|2|3|4|5|6|8";}
	if ($dbversion==9677){$sSQL=$u09."(5011, 1, 'Aspirantes', 'visaeaspirante.php', 2906, 5011, 'S', '', '')";}
	if ($dbversion==9678){$sSQL="CREATE TABLE visa12anotacion (visa12idatencion int NOT NULL, visa12consec int NOT NULL, visa12id int NOT NULL DEFAULT 0, visa12formacontacto int NOT NULL DEFAULT 0, visa12anotacion Text NULL, visa12fecha int NOT NULL DEFAULT 0, visa12hora int NOT NULL DEFAULT 0, visa12minuto int NOT NULL DEFAULT 0, visa12idusuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==9679){$sSQL="ALTER TABLE visa12anotacion ADD PRIMARY KEY(visa12id)";}
	if ($dbversion==9680){$sSQL=$objDB->sSQLCrearIndice('visa12anotacion', 'visa12anotacion_id', 'visa12idatencion, visa12consec', true);}
	if ($dbversion==9681){$sSQL=$objDB->sSQLCrearIndice('visa12anotacion', 'visa12anotacion_padre', 'visa12idatencion');}
	if ($dbversion==9682){$sSQL="agregamodulo|5012|29|Aspirantes - Anotacion|1|2|3|4|5|6|8";}
	if ($dbversion==9683){$sSQL="CREATE TABLE visa13otrosprogramas (visa13idatencion int NOT NULL, visa13idprograma int NOT NULL, visa13id int NOT NULL DEFAULT 0, visa13detalle Text NULL, visa13mantieneinteres int NOT NULL DEFAULT 0)";}
	if ($dbversion==9684){$sSQL="ALTER TABLE visa13otrosprogramas ADD PRIMARY KEY(visa13id)";}
	if ($dbversion==9685){$sSQL=$objDB->sSQLCrearIndice('visa13otrosprogramas', 'visa13otrosprogramas_id', 'visa13idatencion, visa13idprograma', true);}
	if ($dbversion==9686){$sSQL=$objDB->sSQLCrearIndice('visa13otrosprogramas', 'visa13otrosprogramas_padre', 'visa13idatencion');}
	if ($dbversion==9687){$sSQL="agregamodulo|5013|29|Aspirantes - Otros programas|1|2|3|4|5|6|8";}
	if ($dbversion==9688){$sSQL="CREATE TABLE visa14cambioestado (visa14idatencion int NOT NULL, visa14consec int NOT NULL, visa14id int NOT NULL DEFAULT 0, visa14idestadoorigen int NOT NULL DEFAULT 0, visa14idestadofin int NOT NULL DEFAULT 0, visa14detalle varchar(250) NULL, visa14fecha int NOT NULL DEFAULT 0, visa14hora int NOT NULL DEFAULT 0, visa14minuto int NOT NULL DEFAULT 0, visa14idtercero int NOT NULL DEFAULT 0)";}
	if ($dbversion==9689){$sSQL="ALTER TABLE visa14cambioestado ADD PRIMARY KEY(visa14id)";}
	if ($dbversion==9690){$sSQL=$objDB->sSQLCrearIndice('visa14cambioestado', 'visa14cambioestado_id', 'visa14idatencion, visa14consec', true);}
	if ($dbversion==9691){$sSQL=$objDB->sSQLCrearIndice('visa14cambioestado', 'visa14cambioestado_padre', 'visa14idatencion');}

	if ($dbversion==9692){$sSQL="add_campos|cart15recaudomasivo|cart15totalrec Decimal(15,2) NULL DEFAULT 0|cart15totalgen Decimal(15,2) NULL DEFAULT 0|cart1recaudostotal int NOT NULL DEFAULT 0|cart15generadostotal int NOT NULL DEFAULT 0";}
	if ($dbversion==9693){$sSQL="agregamodulo|2758|27|Descarga de Resoluciones|1|5|1708|1710";}
	if ($dbversion==9694){$sSQL=$u09."(2758, 1, 'Descarga de Resoluciones', 'gradrptresol.php', 11, 2758, 'S', '', '')";}

	// 23 de Abril de 2026
	if ($dbversion==9695){$sSQL=$u08."(4103, 'Invitaciones', 'gm.php?id=4103', 'Invitaciones', 'Invitations', 'Convites'), (4104, 'Ejecución', 'gm.php?id=4104', 'Ejecución', 'Execution', 'Execução')";}
	if ($dbversion==9696){$sSQL="CREATE TABLE fact40rptfact (fact40vigencia int NOT NULL DEFAULT 0, fact40fechaini int NOT NULL DEFAULT 0, fact40fechafin int NOT NULL DEFAULT 0)";}
	if ($dbversion==9697){$sSQL="agregamodulo|740|7|Reporte de facturación|1|5|6";}
	if ($dbversion==9698){$sSQL=$u09."(740, 1, 'Reporte de facturación', 'factrptfact.php', 11, 740, 'S', '', '')";}

	// 24 de Abril de 2026
	//9699 - 9700 quedan libres
	}
if (($dbversion>9700)&&($dbversion<9801)){
	if ($dbversion==9701){$sSQL="agregamodulo|4138|41|Invitaciones|1|2|3|4|5|6";}
	if ($dbversion==9702){$sSQL=$u09."(4138, 1, 'Invitaciones', 'cttcinvitaciones.php', 4103, 4138, 'S', '', '')";}
	//9703 A 9707 quedan libres
	if ($dbversion==9708){$sSQL="CREATE TABLE cttc42invitaciondoc (cttc42idinvitacion int NOT NULL, cttc42idoferente int NOT NULL, cttc42idrequisito int NOT NULL, cttc42consec int NOT NULL, cttc42id int NOT NULL DEFAULT 0, cttc42tituloanexo varchar(250) NULL, cttc42origenanexo int NOT NULL DEFAULT 0, cttc42archivo int NOT NULL DEFAULT 0, cttc42fechadoc int NOT NULL DEFAULT 0, cttc42fechavence int NOT NULL DEFAULT 0, cttc42publico int NOT NULL DEFAULT 0)";}
	if ($dbversion==9709){$sSQL="ALTER TABLE cttc42invitaciondoc ADD PRIMARY KEY(cttc42id)";}
	if ($dbversion==9710){$sSQL=$objDB->sSQLCrearIndice('cttc42invitaciondoc', 'cttc42invitaciondoc_id', 'cttc42idinvitacion, cttc42idoferente, cttc42idrequisito, cttc42consec', true);}
	if ($dbversion==9711){$sSQL=$objDB->sSQLCrearIndice('cttc42invitaciondoc', 'cttc42invitaciondoc_padre', 'cttc42idinvitacion');}
	if ($dbversion==9712){$sSQL="agregamodulo|4142|41|Invitaciones - Documentos|1|2|3|4|5|6|8";}
	if ($dbversion==9713){$sSQL="CREATE TABLE cttc43invitacionobserva (cttc43idinvitacion int NOT NULL, cttc43idoferente int NOT NULL, cttc43consec int NOT NULL, cttc43id int NOT NULL DEFAULT 0, cttc43tipo int NOT NULL DEFAULT 0, cttc43estado int NOT NULL DEFAULT 0, cttc43observacion Text NULL, cttc43fecha int NOT NULL DEFAULT 0, cttc43hora int NOT NULL DEFAULT 0, cttc43minuto int NOT NULL DEFAULT 0, cttc43origenanexo int NOT NULL DEFAULT 0, cttc43archivoanexo int NOT NULL DEFAULT 0, cttc43idusuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==9714){$sSQL="ALTER TABLE cttc43invitacionobserva ADD PRIMARY KEY(cttc43id)";}
	if ($dbversion==9715){$sSQL=$objDB->sSQLCrearIndice('cttc43invitacionobserva', 'cttc43invitacionobserva_id', 'cttc43idinvitacion, cttc43idoferente, cttc43consec', true);}
	if ($dbversion==9716){$sSQL=$objDB->sSQLCrearIndice('cttc43invitacionobserva', 'cttc43invitacionobserva_padre', 'cttc43idinvitacion');}
	if ($dbversion==9717){$sSQL="agregamodulo|4143|41|Invitaciones - Observaciones|1|2|3|4|5|6|8";}
	// 9718 QUEDA LIBRE
	if ($dbversion==9719){$sSQL="CREATE TABLE cttc44invitacionrpta (cttc44idinvitacion int NOT NULL, cttc44idobservacion int NOT NULL, cttc44numrpta int NOT NULL, cttc44id int NOT NULL DEFAULT 0, cttc44respuesta Text NULL, cttc44fecha int NOT NULL DEFAULT 0, cttc44hora int NOT NULL DEFAULT 0, cttc44min int NOT NULL DEFAULT 0, cttc44idusuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==9720){$sSQL="ALTER TABLE cttc44invitacionrpta ADD PRIMARY KEY(cttc44id)";}
	if ($dbversion==9721){$sSQL=$objDB->sSQLCrearIndice('cttc44invitacionrpta', 'cttc44invitacionrpta_id', 'cttc44idinvitacion, cttc44idobservacion, cttc44numrpta', true);}
	if ($dbversion==9722){$sSQL=$objDB->sSQLCrearIndice('cttc44invitacionrpta', 'cttc44invitacionrpta_padre', 'cttc44idinvitacion');}
	if ($dbversion==9723){$sSQL="agregamodulo|4144|41|Invitacion - Observacion - Respuesta|1|2|3|4|5|6|8";}
	if ($dbversion==9724){$sSQL="agregamodulo|4139|41|Invitaciones - Oferentes|1|2|3|4|5|6|8";}
	// 25 de Abril de 2026
	if ($dbversion==9725){$sSQL="CREATE TABLE grad59proyajustes (grad59idproyecto int NOT NULL, grad59tipoajuste int NOT NULL, grad59consec int NOT NULL DEFAULT 0, grad59id int NOT NULL DEFAULT 0, grad59titulo varchar(200) NULL, grad59linkrepo varchar(250) NULL, grad59fecha int NOT NULL DEFAULT 0, grad59usuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==9726){$sSQL="ALTER TABLE grad59proyajustes ADD PRIMARY KEY(grad59id)";}
	if ($dbversion==9727){$sSQL=$objDB->sSQLCrearIndice('grad59proyajustes', 'grad59proyajustes_id', 'grad59idproyecto, grad59tipoajuste', true);}
	if ($dbversion==9728){$sSQL=$objDB->sSQLCrearIndice('grad59proyajustes', 'grad59proyajustes_padre', 'grad59idproyecto');}
	if ($dbversion==9729){$sSQL="agregamodulo|2759|27|Proyectos de grado - ajustes|1|2|3|4|5|6";}
	// 27 de Abril de 2026
	if ($dbversion==9730){$sSQL=$objDB->sSQLCrearIndice('core47admitidos', 'core47admitidos_tercero', 'core47idtercero');}
	if ($dbversion==9731){$sSQL=$objDB->sSQLCrearIndice('core47admitidos', 'core47admitidos_zona', 'core47idzona');}
	if ($dbversion==9732){$sSQL=$objDB->sSQLCrearIndice('core47admitidos', 'core47admitidos_centro', 'core47idcentro');}
	if ($dbversion==9733){$sSQL=$objDB->sSQLCrearIndice('core47admitidos', 'core47admitidos_escuela', 'core47idescuela');}
	if ($dbversion==9734){$sSQL=$objDB->sSQLCrearIndice('core47admitidos', 'core47admitidos_programa', 'core47idprograma');}
	if ($dbversion==9735){$sSQL=$objDB->sSQLCrearIndice('unad11terceros ', 'unad11terceros_autocom', 'unad11tipodoc, unad11doc, unad11razonsocial');}

	if ($dbversion==9736){$sSQL="agregamodulo|917|9|Recaudo - Beneficiarios|1|2|3|4|5|6";}
	//9737 - 9742 quedan libres
	// 28 de Abril de 2026
	if ($dbversion==9744){$sSQL=$u04."(2741, 21, 'S')";}
	// 9745 y 9746 quedan libres
	if ($dbversion==9747){$sSQL="add_campos|cttc02tipoproceso|cttc02tipoinvita int NOT NULL DEFAULT 0";}

	if ($dbversion==9748){$sSQL="DROP TABLE cttc45tipoinvitacion";}
	if ($dbversion==9749){$sSQL="mod_quitar|4145";}

	if ($dbversion==9750){$sSQL="CREATE TABLE grad64otrasent (grad64consec int NOT NULL, grad64id int NOT NULL DEFAULT 0, grad64activo int NOT NULL DEFAULT 0, grad64orden int NOT NULL DEFAULT 0, grad64nombre varchar(200) NULL, grad64formato int NOT NULL DEFAULT 0)";}
	if ($dbversion==9751){$sSQL="ALTER TABLE grad64otrasent ADD PRIMARY KEY(grad64id)";}
	if ($dbversion==9752){$sSQL=$objDB->sSQLCrearIndice('grad64otrasent', 'grad64otrasent_id', 'grad64consec', true);}
	if ($dbversion==9753){$sSQL="agregamodulo|2764|27|Otras entidades|1|2|3|4|5|6";}
	if ($dbversion==9754){$sSQL=$u09."(2764, 1, 'Otras entidades', 'gradotraent.php', 2, 2764, 'S', '', '')";}
	if ($dbversion==9755){$sSQL="CREATE TABLE grad65otrasentprog (grad65idotraent int NOT NULL, grad65idescuela int NOT NULL, grad65idprograma int NOT NULL, grad65id int NOT NULL DEFAULT 0, grad65activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9756){$sSQL="ALTER TABLE grad65otrasentprog ADD PRIMARY KEY(grad65id)";}
	if ($dbversion==9757){$sSQL=$objDB->sSQLCrearIndice('grad65otrasentprog', 'grad65otrasentprog_id', 'grad65idotraent, grad65idescuela, grad65idprograma', true);}
	if ($dbversion==9758){$sSQL=$objDB->sSQLCrearIndice('grad65otrasentprog', 'grad65otrasentprog_padre', 'grad65idotraent');}
	if ($dbversion==9759){$sSQL="agregamodulo|2765|27|Otras entidades - programa|1|2|3|4|5|6";}
	if ($dbversion==9760){$sSQL="agregamodulo|2766|27|Informe a otras entidades|1|5|6";}
	if ($dbversion==9761){$sSQL=$u09."(2766, 1, 'Informe a otras entidades', 'gradrptotras.php', 11, 2766, 'S', '', '')";}
	if ($dbversion==9762){$sSQL=$u04."(2701, 21, 'S')";}
	//29 de Abril de 2026
	if ($dbversion==9763){$sSQL="agregamodulo|2331|23|Identificar dato anonimizado|1|";}
	if ($dbversion==9764){$sSQL=$u09."(2331, 1, 'Identificar dato anonimizado', 'caraidentificar.php', 7, 2331, 'S', '', '')";}
	//30 de Abril de 2026
	if ($dbversion==9765){$sSQL="agregamodulo|2178|21|Acceso a Richmond|1|1707";}
	if ($dbversion==9766){$sSQL=$u09."(2178, 1, 'Acceso a Richmond', 'richmond.php', 2106, 2178, 'S', '', '')";}

	// 04 de Mayo de 2026
	//9767 queda libre
	//if ($dbversion==9767){$sSQL=$u08."(901, 'Cartera', 'gm.php?id=901', 'Cartera', 'Debt', 'Cobrança')";}
	if ($dbversion==9768){$sSQL="CREATE TABLE cart22conciliacion (cart22consec int NOT NULL, cart22id int NOT NULL DEFAULT 0, cart22idtercero int NOT NULL DEFAULT 0, cart22fechabase int NOT NULL DEFAULT 0, cart22fechafin int NOT NULL DEFAULT 0, cart22estado int NOT NULL DEFAULT 0, cart22detalle Text NULL, cart22idusuario int NOT NULL DEFAULT 0, cart22fecha int NOT NULL DEFAULT 0, cart22hora int NOT NULL DEFAULT 0, cart22minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==9769){$sSQL="ALTER TABLE cart22conciliacion ADD PRIMARY KEY(cart22id)";}
	if ($dbversion==9770){$sSQL=$objDB->sSQLCrearIndice('cart22conciliacion', 'cart22conciliacion_id', 'cart22consec', true);}
	if ($dbversion==9771){$sSQL="agregamodulo|922|9|Conciliación académica|1|2|3|4|5|6|8";}
	if ($dbversion==9772){$sSQL=$u09."(922, 1, 'Conciliación académica', 'cartconciliaacad.php', 702, 922, 'S', '', '')";}
	if ($dbversion==9773){$sSQL="agregamodulo|923|9|Conciliacion acad - Items|1|2|3|4|5|6|8";}
	if ($dbversion==9774){$sSQL="CREATE TABLE cart24conciliaresumen (cart24idconciliacion int NOT NULL, cart24idproductoacad int NOT NULL, cart24id int NOT NULL DEFAULT 0, cart24cantcausada int NOT NULL DEFAULT 0, cart24cantcobrada int NOT NULL DEFAULT 0)";}
	if ($dbversion==9775){$sSQL="ALTER TABLE cart24conciliaresumen ADD PRIMARY KEY(cart24id)";}
	if ($dbversion==9776){$sSQL=$objDB->sSQLCrearIndice('cart24conciliaresumen', 'cart24conciliaresumen_id', 'cart24idconciliacion, cart24idproductoacad', true);}
	if ($dbversion==9777){$sSQL=$objDB->sSQLCrearIndice('cart24conciliaresumen', 'cart24conciliaresumen_padre', 'cart24idconciliacion');}
	if ($dbversion==9778){$sSQL="agregamodulo|924|9|Conciliacion acad - Resumen|1|5|6";}
	if ($dbversion==9779){$sSQL="CREATE TABLE cart25concilianota (cart25idconciliacion int NOT NULL, cart25consec int NOT NULL, cart25id int NOT NULL DEFAULT 0, cart25nota Text NULL, cart25origenanexo int NOT NULL DEFAULT 0, cart25idanexo int NOT NULL DEFAULT 0, cart25idusuario int NOT NULL DEFAULT 0, cart25fecha int NOT NULL DEFAULT 0, cart25hora int NOT NULL DEFAULT 0, cart25minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==9780){$sSQL="ALTER TABLE cart25concilianota ADD PRIMARY KEY(cart25id)";}
	if ($dbversion==9781){$sSQL=$objDB->sSQLCrearIndice('cart25concilianota', 'cart25concilianota_id', 'cart25idconciliacion, cart25consec', true);}
	if ($dbversion==9782){$sSQL=$objDB->sSQLCrearIndice('cart25concilianota', 'cart25concilianota_padre', 'cart25idconciliacion');}
	if ($dbversion==9783){$sSQL="agregamodulo|925|9|Conciliacion acad - anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==9784){$sSQL=$unad70."(901,140,'unad40curso','unad40id','unad40idproductoacad','El producto esta relacionado a un curso', '')";}
	if ($dbversion==9785){$sSQL=$unad70."(901,923,'unad40curso','unad40id','unad40idprod_habilita','El producto esta relacionado a un curso', '')";}
	if ($dbversion==9786){$sSQL=$unad70."(901,924,'unad40curso','unad40id','unad40idprod_supletorio','El producto esta relacionado a un curso', '')";}
	if ($dbversion==9787){$sSQL=$unad70."(901,925,'unad40curso','unad40id','unad40idprod_homologa','El producto esta relacionado a un curso', '')";}
	if ($dbversion==9788){$sSQL=$unad70."(901,926,'unad40curso','unad40id','unad40idprod_suficiencia','El producto esta relacionado a un curso', '')";}
	if ($dbversion==9789){$sSQL="add_campos|unad40curso|unad40idprod_habilita int NOT NULL DEFAULT 0|unad40idprod_supletorio int NOT NULL DEFAULT 0|unad40idprod_homologa int NOT NULL DEFAULT 0|unad40idprod_suficiencia int NOT NULL DEFAULT 0";}
	if ($dbversion==9790){$sSQL="agregamodulo|926|9|Cursos (Cartera)|1|3|5|6";}
	if ($dbversion==9791){$sSQL=$u09."(926, 1, 'Cursos', 'cartcurso.php', 1, 926, 'S', '', '')";}
	if ($dbversion==9792){$sSQL=$u96."(1, 0, 'Borrador', 100), (1, 7, 'Cerrado', 107), (1, 9, 'Anulado', 109), 
		(2, 0, 'Borrador', 100), (2, 7, 'Cerrada', 107), (2, 9, 'Anulada', 109)";}
	if ($dbversion==9793){$sSQL="add_campos|cttc68modalidad|cttc68tipoinvitacion int NOT NULL DEFAULT 0";}


	if ($dbversion==9794){$sSQL="DROP TABLE cttc38invitaciones";}
	if ($dbversion==9795){$sSQL="CREATE TABLE cttc38invitaciones (cttc38vigencia int NOT NULL, cttc38idtipo int NOT NULL, cttc38consec int NOT NULL, cttc38id int NOT NULL DEFAULT 0, cttc38idproceso int NOT NULL DEFAULT 0, cttc38estado int NOT NULL DEFAULT 0, cttc38instrucciones Text NULL, cttc38apertura_fecha int NOT NULL DEFAULT 0, cttc38pubpliegos_fecha int NOT NULL DEFAULT 0, cttc38visitatecnica int NOT NULL DEFAULT 0, cttc38visitatecnica_fecha int NOT NULL DEFAULT 0, cttc38observatr_cant int NOT NULL DEFAULT 0, cttc38observatr_fecha int NOT NULL DEFAULT 0, cttc38observatr_hora int NOT NULL DEFAULT 0, cttc38observatr_min int NOT NULL DEFAULT 0, cttc38observatr_rta_fecha int NOT NULL DEFAULT 0, cttc38cierre_fecha int NOT NULL DEFAULT 0, cttc38cierre_hora int NOT NULL DEFAULT 0, cttc38cierre_min int NOT NULL DEFAULT 0, cttc38evaluacion_fecha int NOT NULL DEFAULT 0, cttc38aclaraciones_cant int NOT NULL DEFAULT 0, cttc38aclaraciones_fecha int NOT NULL DEFAULT 0, cttc38aclaraciones_hora int NOT NULL DEFAULT 0, cttc38aclaraciones_min int NOT NULL DEFAULT 0, cttc38aclaraciones_rta_fecha int NOT NULL DEFAULT 0, cttc38aclaraciones_rta_hora int NOT NULL DEFAULT 0, cttc38aclaraciones_rta_min int NOT NULL DEFAULT 0, cttc38infprel_fecha int NOT NULL DEFAULT 0, cttc38infprel_hora int NOT NULL DEFAULT 0, cttc38infprel_min int NOT NULL DEFAULT 0, cttc38infprel_obs_cant int NOT NULL DEFAULT 0, cttc38infprel_obs_fecha int NOT NULL DEFAULT 0, cttc38infprel_obs_hora int NOT NULL DEFAULT 0, cttc38infprel_obs_min int NOT NULL DEFAULT 0, cttc38infprel_obs_rpta_fecha int NOT NULL DEFAULT 0, cttc38informedef_fecha int NOT NULL DEFAULT 0, cttc38adjudicacion_fecha int NOT NULL DEFAULT 0, cttc38idajudicado int NOT NULL DEFAULT 0)";}
	if ($dbversion==9796){$sSQL="ALTER TABLE cttc38invitaciones ADD PRIMARY KEY(cttc38id)";}
	if ($dbversion==9797){$sSQL=$objDB->sSQLCrearIndice('cttc38invitaciones', 'cttc38invitaciones_id', 'cttc38vigencia, cttc38idtipo, cttc38consec', true);}
	if ($dbversion==9798){$sSQL="INSERT INTO cttc38invitaciones (cttc38vigencia, cttc38idtipo, cttc38consec, cttc38id, cttc38idproceso, cttc38estado, cttc38instrucciones) VALUES (0, 0, 0, 0, 0, 0, '')";}
	if ($dbversion==9799){$sSQL="add_campos|exte02per_aca|exte02idperiodoadmision int NOT NULL DEFAULT 0";}
	if ($dbversion==9800){$sSQL="add_campos|core12escuela|core12idadminauxiliar int NOT NULL DEFAULT 0";}
	}
if (($dbversion>9800)&&($dbversion<9901)){
	// 11 de mayo de 2026
	if ($dbversion==9801){$sSQL=$u01b."(55, 'INVIL', 'Sistema de Gestion de Idiomas', 'S', 'S', 1, 0, 0, '../invil/', 10)";}
	if ($dbversion==9802){$sSQL="UPDATE unad02modulos SET unad02idsistema=55 WHERE unad02id IN (4733, 4734, 4737, 4738, 4735, 4736, 4738)";}
	if ($dbversion==9803){$sSQL="add_campos|corg34pruebaidioma|corg34idsoporte int NOT NULL DEFAULT 0|corg34nivelacceso int NOT NULL DEFAULT 0|corg34presentacion Text NULL";}
	if ($dbversion==9804){$sSQL="CREATE TABLE corg38pruebapoblacion (corg38idprueba int NOT NULL, corg38idgrupo int NOT NULL, corg38id int NOT NULL DEFAULT 0, corg38vigente int NOT NULL DEFAULT 0)";}
	if ($dbversion==9805){$sSQL="ALTER TABLE corg38pruebapoblacion ADD PRIMARY KEY(corg38id)";}
	if ($dbversion==9806){$sSQL=$objDB->sSQLCrearIndice('corg38pruebapoblacion', 'corg38pruebapoblacion_id', 'corg38idprueba, corg38idgrupo', true);}
	if ($dbversion==9807){$sSQL=$objDB->sSQLCrearIndice('corg38pruebapoblacion', 'corg38pruebapoblacion_padre', 'corg38idprueba');}
	if ($dbversion==9808){$sSQL="agregamodulo|4738|55|Prueba idioma - poblacion|1|2|3|4|5|6";}

	if ($dbversion==9809){$sSQL="CREATE TABLE idio01certificadoresidioma (idio01consec int NOT NULL, idio01id int NOT NULL DEFAULT 0, idio01activa int NOT NULL DEFAULT 0, idio01nombre varchar(250) NULL)";}
	if ($dbversion==9810){$sSQL="ALTER TABLE idio01certificadoresidioma ADD PRIMARY KEY(idio01id)";}
	if ($dbversion==9811){$sSQL=$objDB->sSQLCrearIndice('idio01certificadoresidioma', 'idio01certificadoresidioma_id', 'idio01consec', true);}
	if ($dbversion==9812){$sSQL="agregamodulo|5501|55|Instituciones certificadores|1|2|3|4|5|6|8";}
	if ($dbversion==9813){$sSQL=$u09."(5501, 1, 'Instituciones certificadores', 'idioinstcertifica.php', 1, 5501, 'S', '', '')";}

	if ($dbversion==9814){$sSQL="add_campos|corg35certidioma|corg35vigpedido int NOT NULL DEFAULT 0|corg35idpedido int NOT NULL DEFAULT 0|corg35idcertificador int NOT NULL DEFAULT 0|corg35hab_num int NOT NULL DEFAULT 0|corg35hab_lectura int NOT NULL DEFAULT 0|corg35hab_escucha int NOT NULL DEFAULT 0|corg35hab_escritura int NOT NULL DEFAULT 0|corg35hab_habla int NOT NULL DEFAULT 0";}
	// 13 de Mayo de 2026
	if ($dbversion==9815){$sSQL="CREATE TABLE idio00params (idio00id int NOT NULL, idio00idescuela int NOT NULL DEFAULT 0, idio00idprograma int NOT NULL DEFAULT 0, idio00idioma int NOT NULL DEFAULT 0, idio00nivelfuncionario int NOT NULL DEFAULT 0, idio00niveldocente int NOT NULL DEFAULT 0)";}
	if ($dbversion==9816){$sSQL="ALTER TABLE idio00params ADD PRIMARY KEY(idio00id)";}
	if ($dbversion==9817){$sSQL="INSERT INTO idio00params (idio00id, idio00idescuela, idio00idprograma, idio00idioma, idio00nivelfuncionario, idio00niveldocente) VALUES (1, 0, 0, 0, 0, 0)";}
	if ($dbversion==9818){$sSQL="agregamodulo|5500|55|Parametros|1|3|1707";}
	if ($dbversion==9819){$sSQL=$u09."(5500, 1, 'Parametros', 'idioparams.php', 2, 5500, 'S', '', '')";}
	if ($dbversion==9820){$sSQL=$unad70."(4733,5502,'idio02historialidioma','','idio02idioma','Ya existe Historial de usuarios del idioma', '')";}
	// 9821 - 9823 quedan libres
	if ($dbversion==9824){$sSQL="agregamodulo|5502|55|Historial de idioma|1|5|6";}
	if ($dbversion==9825){$sSQL=$u09."(5502, 1, 'Historial de idioma', 'rpthistorialidioma.php', 11, 5502, 'S', '', '')";}
	if ($dbversion==9826){$sSQL="DROP TABLE cttc85estadoinvita";}

	if ($dbversion==9827){$sSQL="CREATE TABLE cttc85estadoinvita (cttc85id int NOT NULL, cttc85nombre varchar(100) NULL, cttc85publica int NOT NULL DEFAULT 0, cttc85directa int NOT NULL DEFAULT 0, cttc85etiqueta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9828){$sSQL="ALTER TABLE cttc85estadoinvita ADD PRIMARY KEY(cttc85id)";}
	if ($dbversion==9829){$sSQL="INSERT INTO cttc85estadoinvita (cttc85id, cttc85nombre, cttc85publica, cttc85directa, cttc85etiqueta) VALUES 
	(0, 'En elaboración', 1, 1, 100), 
	(1, 'Apertura de la invitación', 1, 0, 101), 
	(2, 'Invitación a presentar propuestas', 0, 1, 102), 
	(5, 'Publicación de términos de referencia', 1, 0, 105), 
	(7, 'Visita técnica', 1, 0, 107), 
	(9, 'Observaciones a los términos de referencia', 1, 0, 109), 
	(10, 'Recepción de solicitudes de aclaraciones', 0, 1, 110), 
	(11, 'Respuesta a observaciones y publicación de adendas', 1, 0, 111), 
	(12, 'Respuesta y aclaraciones a los términos de referencia y adendas', 0, 1, 112), 
	(21, 'Recepción de ofertas y cierre de la invitación', 1, 1, 121), 
	(23, 'Proceso de evaluación de las ofertas', 1, 0, 123), 
	(25, 'Solicitud de aclaraciones a las ofertas', 1, 1, 125), 
	(27, 'Respuesta a las aclaraciones de las ofertas', 1, 1, 127), 
	(31, 'Publicación del informe de evaluación preliminar', 1, 0, 131), 
	(33, 'Observaciones al informe de evaluación preliminar y consulta de ofertas', 1, 1, 133), 
	(35, 'Respuesta a observaciones del informe de evaluación', 1, 0, 135), 
	(36, 'Respuesta a observaciones', 0, 1, 136), 
	(37, 'Publicación del informe de evaluación definitivo', 1, 1, 137), 
	(41, 'Adjudicada', 1, 1, 141), 
	(51, 'Desierta', 1, 1, 151), 
	(91, 'Terminación anticipada', 1, 1, 191)";}
	
	//14 de Mayo de 2026
	if ($dbversion==9830){$sSQL="DROP TABLE cttc39Invitacionofer";}
	if ($dbversion==9831){$sSQL="CREATE TABLE cttc39Invitacionofer (cttc39idinvitacion int NOT NULL, cttc39idinvitado int NOT NULL, cttc39id int NOT NULL DEFAULT 0, cttc39estado int NOT NULL DEFAULT 0, cttc39fecharadica int NOT NULL DEFAULT 0, cttc39horaradica int NOT NULL DEFAULT 0, cttc39minradica int NOT NULL DEFAULT 0, cttc39vrtotalpropuesta Decimal(15,2) NULL DEFAULT 0, cttc39usuariorado int NOT NULL DEFAULT 0)";}
	if ($dbversion==9832){$sSQL="ALTER TABLE cttc39Invitacionofer ADD PRIMARY KEY(cttc39id)";}
	if ($dbversion==9833){$sSQL=$objDB->sSQLCrearIndice('cttc39Invitacionofer', 'cttc39Invitacionofer_id', 'cttc39idinvitacion, cttc39idinvitado', true);}
	if ($dbversion==9834){$sSQL=$objDB->sSQLCrearIndice('cttc39Invitacionofer', 'cttc39Invitacionofer_padre', 'cttc39idinvitacion');}

	if ($dbversion==9835){$sSQL=$u96."(4139, 0, 'Invitado', 100), 
	(4139, 1, 'Postulándose', 101), 
	(4139, 7, 'Radicado oferta', 107), 
	(4139, 9, 'Desiste', 109), 
	(4139, 10, 'No habilitado', 110), 
	(4139, 15, 'Habilitado', 115), 
	(4139, 17, 'Adjudicado', 117), 
	(4139, 19, 'No adjudicado', 119)";}
	if ($dbversion==9836){$sSQL="add_campos|olab65simulexp|olab65identificador2 varchar(20) NULL";}
	if ($dbversion==9837){$sSQL="add_campos|core38opciongrado|core38cred_cursounico int NOT NULL DEFAULT 0";}

	//15 de mayo de 2026
	if ($dbversion==9838){$sSQL="CREATE TABLE grad06graduado (grad06idgraduado int NOT NULL, grad06id int NOT NULL DEFAULT 0, grad06fechaprimergrado int NOT NULL DEFAULT 0, grad06agnoprimergrado int NOT NULL DEFAULT 0, grad06grados_bach int NOT NULL DEFAULT 0, grad06grados_tecno int NOT NULL DEFAULT 0, grad06grados_prof int NOT NULL DEFAULT 0, grad06grados_esp int NOT NULL DEFAULT 0, grad06grados_maestria int NOT NULL DEFAULT 0, grad06grados_doctorado int NOT NULL DEFAULT 0, grad06fechaactualizadatos int NOT NULL DEFAULT 0, grad06idescuela int NOT NULL DEFAULT 0, grad06idsnies int NOT NULL DEFAULT 0, grad06zona int NOT NULL DEFAULT 0, grad06centro int NOT NULL DEFAULT 0)";}
	if ($dbversion==9839){$sSQL="ALTER TABLE grad06graduado ADD PRIMARY KEY(grad06id)";}
	if ($dbversion==9840){$sSQL=$objDB->sSQLCrearIndice('grad06graduado', 'grad06graduado_id', 'grad06idgraduado', true);}
	//21  de mayo de 2026
	if ($dbversion==9841){$sSQL="CREATE TABLE corg39indicesrpgbase (corg39idcohorte int NOT NULL DEFAULT 0, corg39idcohortefin int NOT NULL DEFAULT 0, corg39idcentro int NOT NULL DEFAULT 0, corg39idsnies int NOT NULL DEFAULT 0, corg39id int NOT NULL DEFAULT 0, corg39distancia int NOT NULL DEFAULT 0, corg39indicador int NOT NULL DEFAULT 0, corg39nivelforma int NOT NULL DEFAULT 0, corg39idzona int NOT NULL DEFAULT 0, corg39idescuela int NOT NULL DEFAULT 0, corg39numadmitidos int NOT NULL DEFAULT 0, corg39numpermanecen int NOT NULL DEFAULT 0, corg39numgraduados int NOT NULL DEFAULT 0, corg39fechadato int NOT NULL DEFAULT 0)";}
	if ($dbversion==9842){$sSQL="ALTER TABLE corg39indicesrpgbase ADD PRIMARY KEY(corg39id)";}
	if ($dbversion==9843){$sSQL="agregamodulo|4739|22|Indices RPG|1|5|6|1701|1707|1710";}
	if ($dbversion==9844){$sSQL=$u09."(4739, 1, 'Indices RPG', 'coreindicesrpg.php', 11, 4739, 'S', '', '')";}
	// 22 de mayo de 2026
	if ($dbversion==9845){$sSQL=$u08."(2208, 'Admisiones', 'gm.php?id=2208', 'Admisiones', 'Admissions', 'Editais')";}
	if ($dbversion==9846){$sSQL="agregamodulo|2834|28|Avance de admitidos por cohorte|1|5|6|1701|1710";}
	if ($dbversion==9847){$sSQL=$u09."(2834, 1, 'Avance de admitidos por cohorte', 'rptavanceadmxcohorte.php', 2208, 2834, 'S', '', '')";}
	if ($dbversion==9848){$sSQL="add_campos|core22nivelprograma|core22rpg int NOT NULL DEFAULT 0";}
	if ($dbversion==9849){$sSQL="UPDATE core22nivelprograma SET core22rpg=1 WHERE core22id IN (2,3,4,5,6)";}
	if ($dbversion==9850){$sSQL="add_campos|core36estadocont|core36gruporpg int NOT NULL DEFAULT 0";}
	if ($dbversion==9851){$sSQL="UPDATE core36estadocont SET core36gruporpg=1 WHERE core36id IN (10,41,42,43,44,45,51,52,53,54,55,80)";}
	if ($dbversion==9852){$sSQL="UPDATE core36estadocont SET core36gruporpg=7 WHERE core36id IN (90)";}
	if ($dbversion==9853){$sSQL="INSERT INTO unae16cronaccion(unae16id, unae16accion) VALUES (2408, 'Estadistica de cursos')";}
	// 25 de mayo de 2026 
	// -- Se agrega el permiso de administrar terceros para poder actualizar datos de razon social de proveedores.
	if ($dbversion==9854){$sSQL=$u04."(111, 10, 'S')";}
	// 26 de Mayo de 2026
	if ($dbversion==9855){$sSQL="INSERT INTO cttc76aportantes(cttc76consec, cttc76id, cttc76nombre, cttc76forma, cttc76idtercero, cttc76idunidad, cttc76idequipotrab, cttc76actorautorizado, cttc76mensajebloqueo) VALUES 
	(11, 11, 'Oferente - Representante legal', 9, 0, 0, 0, 0, ''), 
	(12, 12, 'Oferente - Revisor fiscal', 9, 0, 0, 0, 0, '')";}
	//27 de mayo de 2026 
	if ($dbversion==9856){$sSQL=$u96."(4143, 0, 'En elaboración', 100), 
	(4143, 3, 'Radicada', 103), 
	(4143, 7, 'Respondida', 107)";}
	if ($dbversion==9857){$sSQL="add_campos|grad11proyecto|grad11categoria int NOT NULL DEFAULT 0";}
	//28 de mayo de 2025
	if ($dbversion==9858){$sSQL="agregamodulo|743|7|Recibos|1|2|3|4|5|6|8";}
	if ($dbversion==9859){$sSQL=$u09."(743, 1, 'Recibos', 'factrecibos.php', 701, 743, 'S', '', '')";}
	if ($dbversion==9860){$sSQL="agregamodulo|744|7|Recibo detalle|1|2|3|4|5|6|8";}
	if ($dbversion==9861){$sSQL="agregamodulo|745|7|Convenios - Contratos|1|2|3|4|5|6|8";}
	// 29 de mayo de 2026
	if ($dbversion==9862){$sSQL=$u04."(3073, 10, 'S')";}
	if ($dbversion==9863){$sSQL="CREATE TABLE unad11busca (unad11tipodoc varchar(2) NOT NULL, unad11doc varchar(20) NOT NULL, unad11id int NOT NULL DEFAULT 0, unad11busqueda varchar(200) NULL)";}
	if ($dbversion==9864){$sSQL="ALTER TABLE unad11busca ADD PRIMARY KEY(unad11id)";}
	if ($dbversion==9865){$sSQL=$objDB->sSQLCrearIndice('unad11busca', 'unad11_id', 'unad11tipodoc, unad11doc', true);}
	if ($dbversion==9866){$sSQL="ALTER TABLE unad11busca ADD FULLTEXT INDEX unad11_busca(unad11busqueda)";}
	// 4 de Junio de 2026
	if ($dbversion==9867){$sSQL="CREATE TABLE core87reqgradoadicionales (core87idprograma int NOT NULL, core87consec int NOT NULL, core87id int NOT NULL DEFAULT 0, core87vigente int NOT NULL DEFAULT 0, core87nombre varchar(100) NULL, core87formacomprueba int NOT NULL DEFAULT 0, core87momentorecordar int NOT NULL DEFAULT 0, core87anexo int NOT NULL DEFAULT 0, core87productocobrar int NOT NULL DEFAULT 0, core87aplicanav int NOT NULL DEFAULT 0, core87nav_id int NOT NULL DEFAULT 0, core87nav_idava int NOT NULL DEFAULT 0, core87nav_codigoava varchar(20) NULL, core87idioma_id int NOT NULL DEFAULT 0, core87idioma_nivel int NOT NULL DEFAULT 0, core87curso_id int NOT NULL DEFAULT 0, core87curso_notaminima Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9868){$sSQL="ALTER TABLE core87reqgradoadicionales ADD PRIMARY KEY(core87id)";}
	if ($dbversion==9869){$sSQL=$objDB->sSQLCrearIndice('core87reqgradoadicionales', 'core87reqgradoadicionales_id', 'core87idprograma, core87consec', true);}
	if ($dbversion==9870){$sSQL=$objDB->sSQLCrearIndice('core87reqgradoadicionales', 'core87reqgradoadicionales_padre', 'core87idprograma');}
	if ($dbversion==9871){$sSQL="agregamodulo|2287|22|Programas-Req grado adicional|1|2|3|4|5|6|8";}
	if ($dbversion==9872){$sSQL=$objDB->sSQLCrearIndice('core47admitidos', 'core47admitidos_snies', 'core47idsnies');}

	if ($dbversion==9873){$sSQL="DROP TABLE idio02historialidioma";}
	if ($dbversion==9874){$sSQL="CREATE TABLE idio02historialidioma (idio02idtercero int NOT NULL, idio02idioma int NOT NULL, idio02rutaqualifica int NOT NULL, idio02id int NOT NULL DEFAULT 0, idio02fechacert int NOT NULL DEFAULT 0, idio02nivel int NOT NULL DEFAULT 0, idio02grupo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9875){$sSQL="ALTER TABLE idio02historialidioma ADD PRIMARY KEY(idio02id)";}
	if ($dbversion==9876){$sSQL=$objDB->sSQLCrearIndice('idio02historialidioma', 'idio02historialidioma_id', 'idio02idtercero, idio02idioma, idio02rutaqualifica', true);}
	if ($dbversion==9877){$sSQL="CREATE TABLE idio03grupointeres (idio03consec int NOT NULL, idio03id int NOT NULL DEFAULT 0, idio03activo int NOT NULL DEFAULT 0, idio03orden int NOT NULL DEFAULT 0, idio03nombre varchar(250) NULL, idio03exigible int NOT NULL DEFAULT 0, idio03grupopob int NOT NULL DEFAULT 0, idio03nivelobjetivo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9878){$sSQL="ALTER TABLE idio03grupointeres ADD PRIMARY KEY(idio03id)";}
	if ($dbversion==9879){$sSQL=$objDB->sSQLCrearIndice('idio03grupointeres', 'idio03grupointeres_id', 'idio03consec', true);}
	if ($dbversion==9880){$sSQL="agregamodulo|5503|55|Grupos de interes|1|2|3|4|5|6|8";}
	if ($dbversion==9881){$sSQL=$u09."(5503, 1, 'Grupos de interes', 'idiogrupointeres.php', 2, 5503, 'S', '', '')";}
	if ($dbversion==9882){$sSQL="CREATE TABLE idio04grupointerescargo (idio04idgrupo int NOT NULL, idio04idcargo int NOT NULL, idio04id int NOT NULL DEFAULT 0, idio04activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9883){$sSQL="ALTER TABLE idio04grupointerescargo ADD PRIMARY KEY(idio04id)";}
	if ($dbversion==9884){$sSQL=$objDB->sSQLCrearIndice('idio04grupointerescargo', 'idio04grupointerescargo_id', 'idio04idgrupo, idio04idcargo', true);}
	if ($dbversion==9885){$sSQL=$objDB->sSQLCrearIndice('idio04grupointerescargo', 'idio04grupointerescargo_padre', 'idio04idgrupo');}
	if ($dbversion==9886){$sSQL="agregamodulo|5504|55|Grupos de interes - cargos|1|2|3|4|5|6|8";}
	if ($dbversion==9887){$sSQL="CREATE TABLE idio05grupointconfig (idio05idgrupo int NOT NULL, idio05idnivel int NOT NULL, idio05id int NOT NULL DEFAULT 0, idio05avance int NOT NULL DEFAULT 0)";}
	if ($dbversion==9888){$sSQL="ALTER TABLE idio05grupointconfig ADD PRIMARY KEY(idio05id)";}
	if ($dbversion==9889){$sSQL=$objDB->sSQLCrearIndice('idio05grupointconfig', 'idio05grupointconfig_id', 'idio05idgrupo, idio05idnivel', true);}
	if ($dbversion==9890){$sSQL=$objDB->sSQLCrearIndice('idio05grupointconfig', 'idio05grupointconfig_padre', 'idio05idgrupo');}
	if ($dbversion==9891){$sSQL="agregamodulo|5505|55|Grupos de interes - configuracion|1|2|3|4|5|6|8";}
	// 5 de junio de 2026
	if ($dbversion==9892){$sSQL=$objDB->sSQLEliminarIndice('unae25dblog', 'unae25dblog_id');}
	if ($dbversion==9893){$sSQL=$objDB->sSQLCrearIndice('unae25dblog', 'unae25dblog_id', 'unae25fechaini, unae25tipouso', true);}
	if ($dbversion==9894){$sSQL="ALTER TABLE unae25dblog CHANGE unae25fechafin unae25fechafin int NOT NULL DEFAULT 0";}
	// 8 de junio de 2026
	if ($dbversion==9895){$sSQL="CREATE TABLE unaf27equivalentes (unaf27idtercero int NOT NULL, unaf27idseccional int NOT NULL, unaf27id int NOT NULL DEFAULT 0, unaf27secc_id int NOT NULL DEFAULT 0, unaf27secc_tipodoc varchar(2) NULL, unaf27secc_doc varchar(20) NULL, unaf27secc_correo varchar(100) NULL, unaf27forma int NOT NULL DEFAULT 0, unaf27fechacrea int NOT NULL DEFAULT 0, bdoc varchar(50) NULL, bnombre varchar(50) NULL, bseccional int NOT NULL DEFAULT 0)";}
	if ($dbversion==9896){$sSQL="ALTER TABLE unaf27equivalentes ADD PRIMARY KEY(unaf27id)";}
	if ($dbversion==9897){$sSQL=$objDB->sSQLCrearIndice('unaf27equivalentes', 'unaf27equivalentes_id', 'unaf27idtercero, unaf27idseccional', true);}
	if ($dbversion==9898){$sSQL="agregamodulo|4327|1|Usuarios equivalentes|1|2|3|4|5|6|1707";}
	if ($dbversion==9899){$sSQL=$u09."(4327, 1, 'Usuarios equivalentes', 'unadseccequivale.php', 1, 4327, 'S', '', '')";}
	// 11 de junio de 2026
	if ($dbversion==9900){$sSQL="CREATE TABLE teso20cuentanits (teso20idcuentabanco int NOT NULL, teso20consec int NOT NULL, teso20id int NOT NULL DEFAULT 0, teso20activo int NOT NULL DEFAULT 0, teso20nit varchar(20) NULL, teso20fechaini int NOT NULL DEFAULT 0, teso20fechafin int NOT NULL DEFAULT 0)";}
}
if (($dbversion>9900)&&($dbversion<10001)){
	if ($dbversion==9901){$sSQL="ALTER TABLE teso20cuentanits ADD PRIMARY KEY(teso20id)";}
	if ($dbversion==9902){$sSQL=$objDB->sSQLCrearIndice('teso20cuentanits', 'teso20cuentanits_id', 'teso20idcuentabanco, teso20consec', true);}
	if ($dbversion==9903){$sSQL=$objDB->sSQLCrearIndice('teso20cuentanits', 'teso20cuentanits_padre', 'teso20idcuentabanco');}
	if ($dbversion==9904){$sSQL="agregamodulo|820|8|Nits autorizados|1|2|3|4|5|6|8";}
	if ($dbversion==9905){$sSQL="add_campos|fact08cuenta|fact08idcategoria int NOT NULL DEFAULT 0";}
	//12 de junio de 2026
	if ($dbversion==9906){$sSQL=$unad70."(146,4740,'corg40articulacion','corg40id','corg40idperiodo','El dato esta incluido en Articulacion', '')";}
	if ($dbversion==9907){$sSQL="CREATE TABLE corg40articulacion (corg40idperiodo int NOT NULL, corg40id int NOT NULL DEFAULT 0, corg40idequivalente int NOT NULL DEFAULT 0, corg40numest int NOT NULL DEFAULT 0)";}
	if ($dbversion==9908){$sSQL="ALTER TABLE corg40articulacion ADD PRIMARY KEY(corg40id)";}
	if ($dbversion==9909){$sSQL=$objDB->sSQLCrearIndice('corg40articulacion', 'corg40articulacion_id', 'corg40idperiodo', true);}
	if ($dbversion==9910){$sSQL="agregamodulo|4740|22|Articulación|1|2|3|4|5|6";}
	if ($dbversion==9911){$sSQL=$u09."(4740, 1, 'Articulación', 'corearticulacion.php', 2200, 4740, 'S', '', '')";}
	//16 de junio de 2026
	if ($dbversion==9912){$sSQL="CREATE TABLE unaf28seccionaltipodoc (unaf28idseccional int NOT NULL, unaf28tipodoc varchar(2) NOT NULL, unaf28id int NOT NULL DEFAULT 0, unaf28tipoequivale varchar(2) NULL, unaf28forma int NOT NULL DEFAULT 0)";}
	if ($dbversion==9913){$sSQL="ALTER TABLE unaf28seccionaltipodoc ADD PRIMARY KEY(unaf28idseccional, unaf28tipodoc)";}
	if ($dbversion==9914){$sSQL=$objDB->sSQLCrearIndice('unaf28seccionaltipodoc', 'unaf28seccionaltipodoc_padre', 'unaf28idseccional');}
	if ($dbversion==9915){$sSQL="agregamodulo|4328|1|Seccionales - tipo documento|1|2|3|4|5|6";}
	if ($dbversion==9916){$sSQL="add_campos|unad40curso|unad40idseccional int NOT NULL DEFAULT 0";}

	if ($dbversion==9917){$sSQL="drop_campo|fact08cuenta|fact08idcategoria";}
	if ($dbversion==9918){$sSQL="add_campos|fact08cuenta|fact08pagos int NOT NULL DEFAULT 0|fact08recaudo int NOT NULL DEFAULT 0|fact08cajamenor int NOT NULL DEFAULT 0|fact08convenio int NOT NULL DEFAULT 0";}
	//17 de junio de 2026
	if ($dbversion==9919){$sSQL="CREATE TABLE cart22recaudolote (cart22idrecmasivo int NOT NULL, cart22consec int NOT NULL, cart22id int NOT NULL DEFAULT 0, cart22item Text NULL, cart22procesado int NOT NULL DEFAULT 0, cart22fechaprocesado int NOT NULL DEFAULT 0)";}
	if ($dbversion==9920){$sSQL="ALTER TABLE cart22recaudolote ADD PRIMARY KEY(cart22id)";}
	if ($dbversion==9921){$sSQL=$objDB->sSQLCrearIndice('cart22recaudolote', 'cart22recaudolote_id', 'cart22idrecmasivo, cart22consec', true);}
	if ($dbversion==9922){$sSQL=$objDB->sSQLCrearIndice('cart22recaudolote', 'cart22recaudolote_padre', 'cart22idrecmasivo');}
	if ($dbversion==9923){$sSQL="agregamodulo|922|9|Lote|1|2|3|4|5|6|8";}
	if ($dbversion==9924){$sSQL="add_campos|cart15recaudomasivo|cart15numlineas int NOT NULL DEFAULT 0|cart15numlineasprocesa int NOT NULL DEFAULT 0";}
	//19 de junio de 2026
	if ($dbversion==9925){$sSQL=$objDB->sSQLCrearIndice('corf60inscripcion', 'corf60inscripcion_tercero', 'corf60idtercero');}
	if ($dbversion==9926){$sSQL="add_campos|cart22recaudolote|cart22hora int NOT NULL DEFAULT 0|cart22min int NOT NULL DEFAULT 0";}
	if ($dbversion==9927){$sSQL="add_campos|cart16recaudoitems|cart16numref varchar(50) NULL";}
	// 20 de junio de 2026
	if ($dbversion==9928){$sSQL="add_campos|unae43tokenws|unad43uso varchar(100) NULL";}
	// 22 de junio de 2026
	if ($dbversion==9929){$sSQL="add_campos|gcmo20variable|gcmo20dig_rangoet int NOT NULL DEFAULT 0|gcmo20dig_variable int NOT NULL DEFAULT 0|gcmo20fuente_modulo int NOT NULL DEFAULT 0";}
	if ($dbversion==9930){$sSQL="add_campos|unad02modulos|unad02bancovariables int NOT NULL DEFAULT 0";}
	if ($dbversion==9931){$sSQL="add_campos|core09programa|core01procesaadmisiones int NOT NULL DEFAULT 0";}
	if ($dbversion==9932){$sSQL="INSERT INTO unae16cronaccion(unae16id, unae16accion) VALUES (111, 'Terceros'), (2247, 'Procesar admisiones')";}
	// 23 de Junio de 2026
	if ($dbversion==9933){$sSQL="ALTER TABLE grad11proyecto CHANGE grad11titulo grad11titulo VARCHAR(300)";}
	}
if (false) {
	if ($dbversion==99999){$sSQL="";}
	if ($dbversion==9999){$sSQL=$u04."(3646, 10, 'S')";}
	//if ($dbversion==6781){$sSQL=$u09."(12280, 1, 'Cupos preoferta', 'corepreofcupos.php', 2206, 12280, 'S', '', '')";}
	//(3220, 'Conceptos para nómina', ''), (3221, 'Provisiones de nómina', '')
	//if ($dbversion==6604){$sSQL="INSERT INTO nico11momento (nico11id, nico11nombre, nico11ayuda) VALUES (3201, 'Liquidación Nomina', '')";}
	//, cttc11activo, cttc11anexo, cttc11observaciones, cttc11aprobacion, cttc11version
	//if ($dbversion==5330){$sSQL="agregamodulo|4071|40|CPC|1|2|3|4|5|6";}
	//if ($dbversion==5331){$sSQL=$u09."(4071, 1, 'CPC', 'heracpc.php', 1, 4071, 'S', '', '')";}
	//if ($dbversion==5334){$sSQL="agregamodulo|4072|40|Unidades de medida|1|2|3|4|5|6";}
	//if ($dbversion==5335){$sSQL=$u09."(4072, 1, 'Unidades de medida', 'heraunidadmedida.php', 2, 4072, 'S', '', '')";}
	if ($dbversion==9201) {$sSQL ="INSERT INTO ofes09estadorec (ofes09id, ofes09nombre) VALUES (0, 'Borrador'), (3, 'Devuelto'), (7, 'En firme')";}	
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
