<?php
include($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_session.php');	
require_once ($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_log.php');
ini_set('display_errors','1');

function error_handler($errno, $errstr, $errfile, $errline) { 
  if (4096 == $errno) throw new Exception($errstr); 
  return false; 
} 

function gestor_excepciones($excepcion) {
  echo "Excepción no capturada: " , $excepcion->getMessage(), "\n";
	$log = new log();
	$log->guardarErrorHotel($excepcion, 2);
}

set_error_handler('error_handler');
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
//echo ("hola edwin".$p_req."holaedwin");
try{
	include('Presentacion/'.$page.'.php');
}
catch (Exception $excepcion) {
 	 		$texto=$excepcion->getMessage().$excepcion->getFile()."Line:".$excepcion->getLine().$excepcion->getTraceAsString();
 	 		$log = new log();
 	 		$log->guardarErrorHotel($texto,2);
	 		//throw $excepcion;
		}
?>