<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_caja.php');
//require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_cocina.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$fondo = $_POST['fondo'];
$efectivoCerrar = $_POST['efectivo'];
$totalTipo =  $_POST['tipo'];
$dinero =  $_POST['dinero'];
$description =  $_POST['description'];
$categoria =  $_POST['categoria'];
$idencargado =  $_POST['idencargado'];
$idComanda = $_POST['idComanda'];
$comandasAnuladas = $_POST['comandasAnuladas'];
$movimientosAnulados = $_POST['movimientosAnulados'];
$idComandafacturada = $_POST['idComandafacturada']; 
$idComDetail = $_POST['idComDetail'];
$numcomanda = $_POST['numcomanda'];
//parametros para informar el control de stock 
$idproducto = $_POST['idproducto']; 



$caja=new caja();
$mensaje = new MensajeJSON();

try{
if ($fondo){
$open=$caja->open_caja($fondo);
}else if ($efectivoCerrar){
$closeornot=$caja->are_tiquets_cobrados();	
    if ((sizeof($closeornot))>0) $mensaje->setMensaje("No puedes cerrar la caja.Hay comandas que no son cobradas!");
    else{
     $close=$caja->close_caja($efectivoCerrar);
     $cocina = new cocina();
     $cocina->delete_pedidos();
     $mensaje->setMensaje("La caja esta cerrada!");
     $closecaja=true;
     include("/common/Presentacion/backup.php");
     }
}else if ($totalTipo){
$newmov=$caja->insert_movimiento($totalTipo,$dinero,$description,$categoria,$idencargado);
}else if($idComanda){
$a=$caja->cobrar_ticket($idComanda);	
if ($a==false) $mensaje->setMensaje("La comanda esta ya esta cobrada y facturada!");
}else if ($idComDetail){
  if ($numcomanda=="") $pedidos=$caja->get_pedido_bar($idComDetail);
  else $pedidos=$caja->get_pedido($idComDetail);	

if ((sizeof($pedidos))>0){
	  for($i=0;$i<count($pedidos);$i++) {
	  $pedidosInfo[$i]=array("idPlatillo"=>$pedidos[$i]->idPlatillo,"cantidad"=>$pedidos[$i]->cantidad,"nombre"=>$pedidos[$i]->nombre,"precio"=>$pedidos[$i]->precio);
	  }
 }	
}
else if($comandasAnuladas){
  $idComandaAnuladaList = split( ",",$comandasAnuladas);
  foreach ($idComandaAnuladaList as $value){
  $caja->anular_ticket($value);	
  }
}else if($movimientosAnulados){
 $idMovimientoAnuladaList = split( ",",$movimientosAnulados);
   foreach ($idMovimientoAnuladaList as $value){
   	$mov=substr($value,1);
   	$caja->anular_movimiento($mov);
   }
}else if($idComandafacturada){
$caja->facturar_ticket($idComandafacturada);		
}
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }
$id_caja=$caja->get_id_caja ();
$movimientos=$caja->load_movimientos_old($id_caja);
 if ((sizeof($movimientos))>0){
	  for($i=0;$i<count($movimientos);$i++) {
	  $movimientoInfo[$i]=array("idmovimiento"=>$movimientos[$i]->id_movimiento,"fechaHora"=>$movimientos[$i]->fechaHora,"tipo"=>$movimientos[$i]->tipo,"dinero"=>$movimientos[$i]->dinero,"descripcion"=>$movimientos[$i]->descripcion,"categoria"=>$movimientos[$i]->categoria,"encargado"=>$movimientos[$i]->encargado);
	  }
 }	
$tikets=$caja->ld_tickets_old($id_caja);
if ((sizeof($tikets))>0){
	  for($i=0;$i<count($tikets);$i++) {
	  $TicketsInfo[$i]=array("idComanda"=>$tikets[$i]->idComanda,"numComanda"=>$tikets[$i]->numComanda,"estado"=>$tikets[$i]->estado,"fechaHora"=>$tikets[$i]->fechaHora,"total"=>$tikets[$i]->total,"efectivo"=>$tikets[$i]->efectivo,"tipoCliente"=>$tikets[$i]->tipoCliente,"nombre"=>$tikets[$i]->nombre,"free"=>$tikets[$i]->free);
	  }
 }	

$totalmovimientos=$caja->total_mov_old($id_caja);	
$entrytot = $totalmovimientos->entrada;
$exittot = $totalmovimientos->salida;
$ventaR = $totalmovimientos->ventaR;
//$entrytot=$caja->get_entrada();
//$exittot=$caja->get_salida();
$totalTickets=$caja->total_tickets();
$response["TotalTickets"]=$totalTickets;
$response["TotalEntradas"]=$entrytot;
$response["TotalSalidas"]=$exittot;
$response["VentaR"]=$ventaR;
$response["MovimientosInfo"]=$movimientoInfo;
$response["TicketsInfo"]=$TicketsInfo;
$response["pedidosInfo"]=$pedidosInfo;
$response["closecaja"]=$closecaja;

$mensaje->setDatos($response);
echo($mensaje->encode());
?>	

