<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_session.php');	
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_log.php');
ini_set('display_errors','1');

function gestor_excepciones($excepcion) {
  echo "Excepción no capturada: " , $excepcion->getMessage(), "\n";
error_log($excepcion->getMessage()."\r\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/hotel/log/error.log');
}

set_exception_handler('gestor_excepciones');




ob_start();
$sesion = new session();
ob_end_clean();

$page='login';

if($_GET!=null){
	$p_req=$_GET['page'];
	
	if($sesion->is_allowed($p_req))
		$page=$p_req;	
}

include('Presentacion/'.$page.'.php');
?>