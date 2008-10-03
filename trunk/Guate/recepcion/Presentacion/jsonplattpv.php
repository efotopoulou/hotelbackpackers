<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_platillos.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_familia.php');


$oiko = $_POST['family'];
$mensaje = new MensajeJSON();

try {
  $fam=new class_familia();
  $platillos=new platillos();

  $fam=$fam->get_families();
  for($i=0;$i<count($fam);$i++) {
  	$name = $fam[$i]->name;
   $platillos->get_platillos($name);
   $platos=$platillos->get_pla($name);
   $platosid=$platillos->get_plaid($name);
   $platospreciosN=$platillos->get_plaPrecioNormal($name);
   $platospreciosL=$platillos->get_plaPrecioLimitado($name);
   $platosInfo["color"][$name]=$fam[$i]->color;
   for($j=0;$j<count($platos);$j++) {
	$platosInfo["familias"][$name][$j]=array("nombre"=>$platos[$j],"idPlatillo"=>$platosid[$j],"precioNormal"=>$platospreciosN[$j],"precioLimitado"=>$platospreciosL[$j]);
   }
  }
  $mensaje->setDatos($platosInfo);
}catch (SQLException $e){
$mensaje->setError("Error de la BBDD");
}
echo($mensaje->encode());
?>