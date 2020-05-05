<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.6 Friday, October 11, 2019
*/
class SFTP_Servidor{
private $connection;
private $sftp;
public $sError='';
public function ConenctarConKey($username, $publicKey, $privateKey, $passphrase=NULL){
	$this->sError='';
	if (!@ssh2_auth_pubkey_file($this->connection, $username, $publicKey, $privateKey, $passphrase)){
		$this->sError='No fue posible conectarse con las llaves aportadas {'.$publicKey.' - '.$privateKey.'}.';
		}
	if ($this->sError==''){
		$this->sftp=@ssh2_sftp($this->connection);
		if (!$this->sftp){
			$this->sError='No fue posible inicializar el servicio SFTP.';
			}
		}
	}
public function CrearDirectorio($sRuta){
	$this->sError='';
	if (!$this->DirectorioExiste($sRuta)){
		$sftp=$this->sftp;
		if (!ssh2_sftp_mkdir($sftp, $sRuta, 0777)){
			$this->sError='No fue posible crear el directorio: '.$sRuta;
			}
		}
	}
public function deleteFile($remote_file){
	$sftp = $this->sftp;
	unlink('ssh2.sftp://'.$sftp.$remote_file);
	}
public function DirectorioExiste($sRuta){
	$bExiste=false;
	$sftp=$this->sftp;
	$isdir=is_dir('ssh2.sftp://'.$sftp.$sRuta);
    if ($isdir==true){
		$bExiste=true;
		}else{
		//$isfile=file_exists('ssh2.sftp://'.$sftp.$sRuta);
		//if ($isfile==true){
			//}
		}
	/*
	$dir='ssh2.sftp://'.$sftp.$sRuta; 
	$handle=opendir($dir);
	while (false!==($file=readdir($handle))){
		if ($file=='.'){
			$bExiste=true;
			break;
			}
		}
	*/
	return $bExiste;
	}
public function login($username, $password){
	$this->sError='';
	if (!@ssh2_auth_password($this->connection, $username, $password)){
		$this->sError='No es posible autenticarse con el usuario '.$username.'';
		}
	if ($this->sError==''){
		$this->sftp=@ssh2_sftp($this->connection);
		if (!$this->sftp){
			$this->sError='No fue inicializar el susbsistema SFTP.';
			}
		}
	}
public function receiveFile($remote_file, $local_file){
	$this->sError='';
	$sftp = $this->sftp;
	$stream=@fopen('ssh2.sftp://'.$sftp.$remote_file, 'rb');
	if (!$stream){
		$this->sError='No fue posible abrir el archivo '.$remote_file;
		}
	if ($this->sError==''){
		$contents=fread($stream, filesize('ssh2.sftp://'.$sftp.$remote_file));
		file_put_contents($local_file, $contents);
		@fclose($stream);
		}
	}
public function scanFilesystem($remote_file){
	$this->sError='';
	$sftp=$this->sftp;
	$dir='ssh2.sftp://'.$sftp.$remote_file; 
	$tempArray=array();
	$handle=opendir($dir);
	// List all the files
	while (false!==($file=readdir($handle))) {
		if (substr($file, 0, 1)!='.'){
			if(is_dir($file)){
				//                $tempArray[$file] = $this->scanFilesystem("$dir/$file");
				} else {
				$tempArray[]=$file;
				}
			}
		}
	closedir($handle);
	return $tempArray;
	}
public function SubirDatos($datos, $remote_file){
	$this->sError='';
	$sftp=$this->sftp;
	$stream = @fopen('ssh2.sftp://'.$sftp.$remote_file, 'w');
	if (!$stream){
		$this->sError='No fue posible leer el archivo: '.$remote_file;
		}
	if ($this->sError==''){
		if (@fwrite($stream, $datos)===false){
			$this->sError='No fue posible enviar el archivo';
			}
		@fclose($stream);
		}
	}
public function TraerArchivo($remote_file){
	$this->sError='';
	$sftp=$this->sftp;
	$contents=NULL;
	$iTamano=0;
	$stream=@fopen('ssh2.sftp://'.$sftp.$remote_file, 'rb');
	if (!$stream){
		$this->sError='No fue posible abrir el archivo '.$remote_file;
		}
	if ($this->sError==''){
		$iTamano=filesize('ssh2.sftp://'.$sftp.$remote_file);
		//$contents=fread($stream, $iTamano);
		$contents=stream_get_contents($stream);
		//$contents=@file_get_contents($stream);
		@fclose($stream);
		}
	return array($contents, $iTamano);
	}
public function uploadFile($local_file, $remote_file){
	$this->sError='';
	$sftp=$this->sftp;
	$stream = @fopen('ssh2.sftp://'.$sftp.$remote_file, 'w');
	if (!$stream){
		$this->sError='No fue posible leer el archivo: '.$remote_file;
		}
	if ($this->sError==''){
		$data_to_send = @file_get_contents($local_file);
		if ($data_to_send === false){
			$this->sError='No se puede abrir el archivo: '.$local_file;
			}else{
			if (@fwrite($stream, $data_to_send) === false){
				$this->sError='No fue posible enviar el archivo: '.$local_file;
				}
			}
		@fclose($stream);
		}
	}
public function __construct($host, $port=22, $bConectar=true){
	if ($bConectar){
		$this->connection=@ssh2_connect($host, $port);
		if (!$this->connection){
			$this->sError='No fue posible conectar con '.$host.' puerto '.$port;
			}
		}
	}
}
?>