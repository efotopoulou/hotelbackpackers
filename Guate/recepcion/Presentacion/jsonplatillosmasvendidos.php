<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_estadisticas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$fechaInicio =  $_POST['inicio'];
$fechaFin =  $_POST['fin'];

$estadisticas = new estadisticas();
$mensaje = new MensajeJSON();

try{
$output = $estadisticas ->topPlatillos($fechaInicio,$fechaFin,$mensaje);
$mensaje->setDatos($output);	
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }
 echo($mensaje->encode());
?>

