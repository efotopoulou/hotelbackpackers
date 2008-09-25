<?php
include($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_session.php');	
require_once ($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_log.php');
ini_set('display_errors','1');

function gestor_excepciones($excepcion) {
  echo "Excepción no capturada: " , $excepcion->getMessage(), "\n";
error_log($excepcion->getMessage()."\r\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/hotel/log/error.log');
}

set_exception_handler('gestor_excepciones');




ob_start();
$sesion = new session();
ob_end_clean();

$page='';

if($_GET!=null){
	$p_req=$_GET['page'];
	
	if($sesion->is_allowed_rest($p_req))
		$page='Presentacion/'.$p_req.'.php';
	else
		$page='/hotel/view.php?page=login'; 	
}

include($page);
?>