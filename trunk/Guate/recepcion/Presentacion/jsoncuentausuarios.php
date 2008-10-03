<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
//require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_cocina.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$usuario = $_POST['usuario'];
$idusuario = $_POST['idusuario'];
$year = $_POST['year'];
$month = $_POST['month'];
$idComanda = $_POST['idComanda'];


$caja=new caja();
$mensaje = new MensajeJSON();

try{
if ($usuario){
$usuarios=$caja->get_usuarios();
if ((sizeof($usuarios))>0){
	  for($i=0;$i<count($usuarios);$i++) {
	  $UsuariosInfo[$i]=array("Id_usuario"=>$usuarios[$i]->Id_usuario,"nombre"=>$usuarios[$i]->nombre);
	  }
 }	
}else if($idComanda && $idusuario){
$iduser = substr($idusuario, 1);
$a=$caja->cobrar_ticket($idComanda);	
if ($a==false) $mensaje->setMensaje("La comanda esta ya esta cobrada y facturada!");
$response = loadtickets($caja,$idusuario,$month,$year);	
$totalTickets=$caja->total_cuenta($iduser,$month,$year);
}else if($idusuario){	
$iduser = substr($idusuario, 1);
$response = loadtickets($caja,$idusuario,$month,$year);	
$totalTickets=$caja->total_cuenta($iduser,$month,$year);
}
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }

$response["TotalTickets"]=$totalTickets;
$response["UsuariosInfo"]=$UsuariosInfo;

$mensaje->setDatos($response);
echo($mensaje->encode());
?>	
<?php
function loadtickets($caja,$idusuario,$month,$year){
$iduser = substr($idusuario, 1);
$tikets=$caja->get_usuarios_comandas($iduser,$month,$year);
if ((sizeof($tikets))>0){
	  for($i=0;$i<count($tikets);$i++) {
	  $TicketsInfo[$i]=array("idComanda"=>$tikets[$i]->idComanda,"numComanda"=>$tikets[$i]->numComanda,"estado"=>$tikets[$i]->estado,"fechaHora"=>$tikets[$i]->fechaHora,"total"=>$tikets[$i]->total,"clientType"=>$tikets[$i]->clientType,"nombre"=>$tikets[$i]->nombre);
	  }
 }	
 $response["TicketsInfo"]=$TicketsInfo;
 return($response);	
}


?>
