<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 
- Los datos de conexion deben pasarse como parametros al momento de iniciar la clase, esos parametros pueden venir del archivo de configuracion o de una base de datos
*/
function clsdb_warning_handler($errno, $errstr, $errfile, $errline, $errcontext){
	throw new Exception($errstr);
	}
class clsdbadmin{
	var $bconectada=false;
	var $bxajax=false;
	var $dbclave='';
	var $dblink=NULL;
	var $dbmodelo='M';
	var $dbnombre='';
	var $dbservidor='';
	var $dbusuario='';
	var $idsistema=0;//aqui poner el sistema en el que se esta trabajando
	var $serror='';

	function xajax(){
		$this->bxajax=true;
		$this->bconectada=false;
		}
	function conectar(){
	$res=false;
	switch ($this->dbmodelo){
		case 'O':
		if(!($this->dblink=odbc_connect($this->dbservidor,$this->dbusuario,$this->dbclave))){
			$this->serror='Error conectando al servidor de datos ODBC {'.odbc_error().'}';
			}else{
			if (!$this->bxajax){$this->bconectada=true;}
			$res=true;
			}
		break;
		default:
		$bConecta=false;
		set_error_handler("clsdb_warning_handler", E_ALL);
		if (!function_exists('mysql_connect')){
			echo 'MySql no esta disponible.. <!-- '.$this->dbservidor.'  -->';
			die();
			}
		try{
			$bConecta=$this->dblink=mysql_connect($this->dbservidor, $this->dbusuario, $this->dbclave);
			}catch(Exception $e){
			$this->serror='Error conectando al servidor de datos MySql {'.$e->getMessage().'}';
			}		
		if($bConecta){
			if(!(mysql_select_db($this->dbnombre,$this->dblink))){
				$this->serror='Error seleccionando la base de datos';
				}else{
				if (!$this->bxajax){$this->bconectada=true;}
				$res=true;
				}
			}
		}
	return $res;
	}
	function ejecutasql($sentencia){
		$res=false;
		$this->serror='';
		if (!$this->bconectada){$this->conectar();}
		if ($this->serror==''){
			switch ($this->dbmodelo){
				case 'O':
				$res=odbc_exec($this->dblink, $sentencia);
				if ($res==false){$this->serror=odbc_error($this->dblink);}
				break;
				default:
				//echo $sentencia;
				$res=mysql_query($sentencia, $this->dblink);
				if ($res==false){$this->serror=mysql_error($this->dblink);}
				}
			}
		return $res;
		}
	function nf($tabla){
		$res=0;
		if ($tabla!=false){
			switch ($this->dbmodelo){
				case 'O':
				$res=odbc_num_rows($tabla);
				break;
				default:
				$res=mysql_num_rows($tabla);
				}
			}
		return $res;
		}
	function sf($tabla){
		$res=false;
		if ($tabla!=false){
			switch ($this->dbmodelo){
				case 'O':
				$res=odbc_fetch_array($tabla);
				break;
				default:
				$res=mysql_fetch_array($tabla);
				}
			}
		return $res;
		}
//funciones de trabajo sobre la estructura de la base de datos
	function bexistetabla($tablanombre){
	$res=false;
	switch ($this->dbmodelo){
		case 'O':
		break;
		default:
		$sql='SHOW TABLES LIKE "'.$tablanombre.'"';
		$tabla=$this->ejecutasql($sql);
		if ($this->nf($tabla)>0){$res=true;}
		}
	return $res;
	}
//funciones que trabajan sobre campos
	function scomparafecha($scampo,$scomparacion,$sfecha){
		$res='';
		switch ($this->dbmodelo){
			case 'M':
			$res='(STR_TO_DATE('.$scampo.',"%d/%m/%Y")'.$scomparacion.'STR_TO_DATE("'.$sfecha.'","%d/%m/%Y"))';
			}
		return $res;
		}
	function stextoafecha($sfecha){
		$res='';
		switch ($this->dbmodelo){
			case 'M':
			$res='STR_TO_DATE("'.$sfecha.'","%d/%m/%Y")';
			}
		return $res;
		}
	function scampoafecha($scampo){
		$res='';
		switch ($this->dbmodelo){
			case 'M':
			$res='STR_TO_DATE('.$scampo.',"%d/%m/%Y")';
			}
		return $res;
		}

	function __construct($servidor, $usuario, $clave, $db, $modelo='M'){
		$this->dbclave=$clave;
		$this->dbnombre=$db;
		$this->dbmodelo=$modelo;
		$this->dbservidor=$servidor;
		$this->dbusuario=$usuario;
		if ($servidor==''){
			$this->serror='No se ha definido el servidor de base de datos';
			}
		}	
	}
?>