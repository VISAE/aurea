-- Inicio campos sexo
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01sexoversion INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01sexov1identidadgen INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01sexov1orientasexo INT(11) NULL DEFAULT 0;
-- fin campos sexo

-- inicio campos saber11
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01saber11version INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01saber11v1agno INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01saber11v1numreg VARCHAR(50) NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01saber11v1puntglobal INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01saber11v1puntleccritica INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01saber11v1puntmatematicas INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01saber11v1puntsociales INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01saber11v1puntciencias INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01saber11v1puntingles INT(11) NULL DEFAULT 0;
-- fin campos saber11

-- inicio campos bienestar
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienversion INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2altoren VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2atletismo VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2baloncesto VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2futbol VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2gimnasia VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2natacion VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2voleibol VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2tenis VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2paralimpico VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2otrodeporte VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2otrodeportedetalle VARCHAR(200) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2activdanza VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2activmusica VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2activteatro VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2activartes VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2activliteratura VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2activculturalotra VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2activculturalotradetalle VARCHAR(200) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2evenfestfolc VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2evenexpoarte VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2evenhistarte VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2evengalfoto VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2evenliteratura VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2eventeatro VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2evencine VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2evenculturalotro VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2evenculturalotrodetalle VARCHAR(200) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprendimiento VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2empresa VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenrecursos VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenconocim VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenplan VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenejecutar VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenfortconocim VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenidentproblema VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenotro VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenotrodetalle VARCHAR(200) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenmarketing VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenplannegocios VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprenideas VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2emprencreacion VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2saludfacteconom VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2saludpreocupacion VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2saludconsumosust VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2saludinsomnio VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2saludclimalab VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2saludalimenta VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2saludemocion VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2saludestado VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2saludmedita VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimedusexual VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimcultciudad VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimrelpareja VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimrelinterp VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimdinamicafam VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimautoestima VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2creciminclusion VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2creciminteliemoc VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimcultural VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimartistico VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimdeporte VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimambiente VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2crecimhabsocio VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienbasura VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienreutiliza VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienluces VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienfrutaverd VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienenchufa VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambiengrifo VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienbicicleta VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambientranspub VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienducha VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambiencaminata VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambiensiembra VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienconferencia VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienrecicla VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienotraactiv VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienotraactivdetalle VARCHAR(200) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienreforest VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienmovilidad VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienclimatico VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienecofemin VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienbiodiver VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienecologia VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambieneconomia VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienrecnatura VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienreciclaje VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienmascota VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambiencartohum VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienespiritu VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambiencarga VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienotroenfoq VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01bienv2ambienotroenfoqdetalle VARCHAR(200) NOT NULL DEFAULT '';
-- fin campos bienestar

-- inicio campos aspectos familiares
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01fam_madrecabeza VARCHAR(1) NOT NULL DEFAULT '';
-- inicio campos aspectos familiares

-- inicio campos aspectos académicos
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01acadhatenidorecesos VARCHAR(1) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01acadrazonreceso INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01acadrazonrecesodetalle VARCHAR(200) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01campus_usocorreounad INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01campus_usocorreounadno INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01campus_usocorreounadnodetalle VARCHAR(200) NOT NULL DEFAULT '';
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01campus_medioactivunad INT(11) NULL DEFAULT 0;
ALTER TABLE unadsys.cara01encuesta ADD COLUMN cara01campus_medioactivunaddetalle VARCHAR(200) NOT NULL DEFAULT '';
-- inicio campos aspectos académicos