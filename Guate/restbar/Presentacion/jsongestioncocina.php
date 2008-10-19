<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_cocina.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$eliminarpedidoid =  $_POST['eliminarpedido'];
$recuperarpedido =  $_POST['recuperarpedido'];

$cocina = new cocina();
$mensaje = new MensajeJSON();

try{
if ($eliminarpedidoid) $cocina->eliminar_pedido($eliminarpedidoid);
else if($recuperarpedido) $cocina->recuperar_pedido();
$response = loadpedidos($cocina);
}catch (SQLException $e){
	$aux = $e ->getNativeError();
      if (stripos($e ->getNativeError(),"uplicate") != 0){
      $mensaje->setMensaje("Un platillo con el mismo ID ja existe en la Base de Datos!Por favor elige otro ID para el platillo que quiere anadir!");
      }else $mensaje->setMensaje("Error Desconocido: $aux!");
 }
$mensaje->setDatos($response);
echo($mensaje->encode());
?>

<?php
function loadpedidos($cocina){
$pedidos=$cocina->select_pedidos();
if ((sizeof($pedidos))>0){
	  for($i=0;$i<count($pedidos);$i++) {
	   $PedidosInfo[$i]=array("idCocina"=>$pedidos[$i]->idCocina,"numComanda"=>$pedidos[$i]->numComanda,"idPlatillo"=>$pedidos[$i]->idPlatillo,"nombre"=>$pedidos[$i]->nombre,"cantidad"=>$pedidos[$i]->cantidad,"hora"=>$pedidos[$i]->hora);
	  }
 }	
$response["PedidosInfo"]=$PedidosInfo;
return($response);
}
?>
