<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_stock.php');


$oiko = $_GET['family'];
$mensaje = new MensajeJSON();

try {
 
$stock=new stock();
$stockreception = $stock->get_stockreception();
 for($i=0;$i<count($stockreception);$i++) {
 	$bebidasInfo["platillos"][$i]=array("nombre"=>$stockreception[$i]->nombre,"idBebida"=>$stockreception[$i]->idBebida,"precioNormal"=>$stockreception[$i]->precioNormal,"precioLimitado"=>$stockreception[$i]->precioLimitado);
 }
  $mensaje->setDatos($bebidasInfo);
}catch (SQLException $e){
$mensaje->setError("Error de la BBDD");
}
echo($mensaje->encode());
?>