<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_bebidas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_familiabebida.php');


$oiko = $_POST['family'];
$mensaje = new MensajeJSON();

try {
  $fam=new class_familia();
  $bebidas=new bebidas();

  $fam=$fam->get_families();
  for($i=0;$i<count($fam);$i++) {
  	$name = $fam[$i]->name;
   $beb=$bebidas->get_bebidas($name);
   
   $bebidasInfo["color"][$name]=$fam[$i]->color;
   for($j=0;$j<count($beb);$j++) {
	$bebidasInfo["familias"][$name][$j]=array("nombre"=>$beb[$j]->nombre,"idPlatillo"=>$beb[$j]->idBebida,"precioNormal"=>$beb[$j]->precioNormal,"precioLimitado"=>$beb[$j]->precioLimitado);
   }
  }
  $mensaje->setDatos($bebidasInfo);
}catch (SQLException $e){
$mensaje->setError("Error de la BBDD");
}
echo($mensaje->encode());
?>