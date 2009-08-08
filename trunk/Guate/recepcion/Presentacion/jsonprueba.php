<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_users.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_log.php');


$mensaje = new MensajeJSON();
try {
	$users=new class_users();
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;

//mysql_select_db($database) or die("Error conecting to db.");
//CALCULAR EL COUNT!!!!!

$count=$users->getCountUsers();

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
if($start <0) $start = 0; 

$usuarios = $users->getUsers($sidx, $sord, $start, $limit);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$responce->rows = $usuarios;
echo json_encode($responce);
}catch (Exception $e){
	$log= new log();
	$aux = $e ->getNativeError();
	$log ->guardarErrorRecepcion("Error Desconocido: $aux!!!!".$e->getMessage(), 5);
	echo ("Error Desconocido: $aux!!!!".$e->getMessage());
}
?>
