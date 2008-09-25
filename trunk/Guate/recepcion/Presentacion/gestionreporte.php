<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Presentacion/reporte.php');

$idcaja =  $_POST['idcaja'];

$mensaje = new MensajeJSON();
if ($idcaja){
$reporte = new getreporte();
$response=$reporte->getdatos($idcaja,$mensaje);	
}else{
$caja=new caja();
$id_caja=$caja->get_id_caja ();

$reporte = new getreporte();
$response=$reporte->getdatos($id_caja,$mensaje);
}
$mensaje->setDatos($response);
echo($mensaje->encode());

?>