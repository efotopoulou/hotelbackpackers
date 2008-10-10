<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_stock.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$recarga = $_POST['recarga'];
$idbebida = $_POST['idbebida'];
$stockbar =  $_POST['stockbar'];
$stockrestaurante =  $_POST['stockrestaurante'];
$unidadventa = $_POST['unidadventa'];
$addornew = $_POST['aux'];
$categoria =  $_POST['categoria'];
$idencargado =  $_POST['idencargado'];
$idComanda = $_POST['idComanda']; 
$idComDetail = $_POST['idComDetail']; 
$ventaturno = $_POST['ventaturno']; 
$recuperarventa = $_POST['recuperarventa']; 

$stock=new stock();
$mensaje = new MensajeJSON();

try{
if($recarga){
$response = loadstock($stock);	
}else if($idbebida){
$id = substr($idbebida, 1);
$stock->add_stock($addornew,$id,$stockbar,$stockrestaurante,$unidadventa);	
$response = loadstock($stock);	
}else if($ventaturno){
$rvc=$stock->recuperar_venta_caja();
$response["recuperarVentas"]=$rvc;

$ventas=$stock->venta_turno();
  if ((sizeof($ventas))>0){
	  for($i=0;$i<count($ventas);$i++) {
	  $ventasInfo[$i]=array("numBebida"=>$ventas[$i]->numBebida,"nombre"=>$ventas[$i]->nombre,"suma"=>$ventas[$i]->suma, "clientType"=>$ventas[$i]->clientType);
	  }
   }
}else if($recuperarventa){
$stock->recuperar_venta();	
$response = loadstock($stock);	
}	
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }

$response["ventasInfo"]=$ventasInfo;

$mensaje->setDatos($response);
echo($mensaje->encode());
?>	
<?php
function loadstock($stock){
	$stockbar = $stock->get_stock();
    if ((sizeof($stockbar))>0){
	  for($i=0;$i<count($stockbar);$i++) {
	  $stockInfo[$i]=array("idBebida"=>$stockbar[$i]->idBebida,"numBebida"=>$stockbar[$i]->numBebida,"familia"=>$stockbar[$i]->familia,"nombre"=>$stockbar[$i]->nombre,"stockrestaurante"=>$stockbar[$i]->stockrestaurante,"unidadventa"=>$stockbar[$i]->unidadventa);
	  }
	  $response["stockInfo"]=$stockInfo;
      return($response);
    }	
 }


?>
