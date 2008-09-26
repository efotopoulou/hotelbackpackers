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
 
/*  $fam=$fam->get_families();
  for($i=0;$i<count($fam);$i++) {
  	$name = $fam[$i]->name;
   $bebidas->get_bebidas($name);
   $bebidasName=$bebidas->get_pla($name);
   $bebidaid=$bebidas->get_plaid($name);
   $bebidapreciosN=$bebidas->get_plaPrecioNormal($name);
   $bebidapreciosL=$bebidas->get_plaPrecioLimitado($name);
   $bebidasInfo["color"][$name]=$fam[$i]->color;
   for($j=0;$j<count($bebidasName);$j++) {
	$bebidasInfo["platillos"][$j]=array("nombre"=>$bebidasName[$j],"idBebida"=>$bebidaid[$j],"precioNormal"=>$bebidapreciosN[$j],"precioLimitado"=>$bebidapreciosL[$j]);
   }
  }*/
  $mensaje->setDatos($bebidasInfo);
}catch (SQLException $e){
$mensaje->setError("Error de la BBDD");
}
echo($mensaje->encode());
?>