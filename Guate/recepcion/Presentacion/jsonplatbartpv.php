<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_bebidas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_familiabar.php');


$oiko = $_GET['family'];
$mensaje = new MensajeJSON();

try {
 if ($oiko){
  $bebidas=new bebidas();
  $bebidas->get_bebidas($oiko);
  $bebidasName=$bebidas->get_pla($oiko);
  $bebidaid=$bebidas->get_plaid($oiko);
  $bebidapreciosN=$bebidas->get_plaPrecioNormal($oiko);
  $bebidapreciosL=$bebidas->get_plaPrecioLimitado($oiko);

  for($i=0;$i<count($platos);$i++) {
	$bebidasInfo[$i]=array("nombre"=>$bebidasName[$i],"idBebida"=>$bebidaid[$i],"precioNormal"=>$bebidapreciosN[$i],"precioLimitado"=>$bebidapreciosL[$i]);
  }
 }else{
  $fam=new class_familia();
  $bebidas=new bebidas();

  $fam=$fam->get_families();
  for($i=0;$i<count($fam);$i++) {
  	$name = $fam[$i]->name;
   $bebidas->get_bebidas($name);
   $bebidasName=$bebidas->get_pla($name);
   $bebidaid=$bebidas->get_plaid($name);
   $bebidapreciosN=$bebidas->get_plaPrecioNormal($name);
   $bebidapreciosL=$bebidas->get_plaPrecioLimitado($name);
   $bebidasInfo["color"][$name]=$fam[$i]->color;
   for($j=0;$j<count($bebidasName);$j++) {
	$bebidasInfo["familias"][$name][$j]=array("nombre"=>$bebidasName[$j],"idBebida"=>$bebidaid[$j],"precioNormal"=>$bebidapreciosN[$j],"precioLimitado"=>$bebidapreciosL[$j]);
   }
  }
 }
  $mensaje->setDatos($bebidasInfo);
}catch (SQLException $e){
$mensaje->setError("Error de la BBDD");
}
echo($mensaje->encode());
?>