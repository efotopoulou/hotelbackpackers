<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$name =  $_POST['name'];

$caja=new caja();
$mensaje = new MensajeJSON();

try{
$TicketsInfo = ld_tiket_old($caja, $name,$mensaje);

}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }
$response["TicketsInfo"]=$TicketsInfo;
$mensaje->setDatos($response);
echo($mensaje->encode());
?>	
<?php
function ld_tiket_old($caja, $name, $mensaje){
$tikets = $caja->ld_ticket_old($name,$mensaje);
   //if ((sizeof($tikets))==0) $mensaje->setMensaje("tickets=0");
	
   if ((sizeof($tikets))>0){
	  for($i=0;$i<count($tikets);$i++) {
	  $TicketsInfo[$i]=array("idComanda"=>$tikets[$i]->idComanda,"numComanda"=>$tikets[$i]->numComanda,"estado"=>$tikets[$i]->estado,"fechaHora"=>$tikets[$i]->fechaHora,"total"=>$tikets[$i]->total,"efectivo"=>$tikets[$i]->efectivo,"tipoCliente"=>$tikets[$i]->tipoCliente,"nombre"=>$tikets[$i]->nombre,"free"=>$tikets[$i]->free);
	  }
	  return ($TicketsInfo);
   }		
}
?>
