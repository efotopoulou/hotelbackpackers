<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dbackup.php');


class backup{

	private $folder;

	function __construct(){
		$folder = $_SERVER['DOCUMENT_ROOT'] . '/hotel/backup/';
	}
	
	function hacer_backup(){
		$datos=new Dbackup();
		
		$file= $_SERVER['DOCUMENT_ROOT'] . '/hotel/backup/prueba.sql';
		echo $file;
		echo $datos->make_backup($file,"usuario");
		
	}

}
?>
