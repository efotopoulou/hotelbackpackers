<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$fin =  $_POST['fin'];
$id_caja =  $_POST['id_caja'];
$idcajaparam =  $_POST['idcaja'];
$inicio =  $_POST['inicio'];
$fondo = $_POST['fondo'];

$caja=new caja();
$mensaje = new MensajeJSON();

try{
if ($inicio){
$CajasInfo = findcaja($inicio,$fin,$caja);
}else if($idcajaparam){
$CajasInfo = findonecaja($idcajaparam,$caja);
$TicketsInfo = ld_tikets_old($idcajaparam,$caja);
$movimientoInfo = ld_movimientos_old($idcajaparam,$caja);
$totalTickets=$caja->total_tickets_old($idcajaparam);
$totalmovimientos=$caja->total_mov_old($idcajaparam);
$entrytot = $totalmovimientos->entrada;
$exittot = $totalmovimientos->salida;
$fondo=$caja->get_fondo_caja_old($idcajaparam);	
}else if ($id_caja){
//echo($id_caja);
$TicketsInfo = ld_tikets_old($id_caja,$caja);
$movimientoInfo = ld_movimientos_old($id_caja,$caja);
$totalTickets=$caja->total_tickets_old($id_caja);
$totalmovimientos=$caja->total_mov_old($id_caja);
$entrytot = $totalmovimientos->entrada;
$exittot = $totalmovimientos->salida;
$fondo=$caja->get_fondo_caja_old($id_caja);	
}
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }

$response["TotalTickets"]=$totalTickets;
$response["fondo"]=$fondo;
$response["TotalEntradas"]=$entrytot;
$response["TotalSalidas"]=$exittot;
$response["MovimientosInfo"]=$movimientoInfo;
$response["TicketsInfo"]=$TicketsInfo;
$response["CajasInfo"]=$CajasInfo;

$mensaje->setDatos($response);
echo($mensaje->encode());
?>	
<?php
function  findcaja($inicio,$fin,$caja){
$cajasF=$caja->find_caja($inicio,$fin);
if ((sizeof($cajasF))>0){
	for($i=0;$i<count($cajasF);$i++) {
	$CajasInfo[$i]=array("id_caja"=>$cajasF[$i]->id_caja,"fechaHoraApertura"=>$cajasF[$i]->fechaHoraApertura,"fechaHoraCierre"=>$cajasF[$i]->fechaHoraCierre,"fondoInicial"=>$cajasF[$i]->fondoInicial,"EfectivoCerrar"=>$cajasF[$i]->EfectivoCerrar);
	}
	return ($CajasInfo);
}
}
function  findonecaja($idcaja,$caja){
$cajasF=$caja->find_one_caja($idcaja);
if ((sizeof($cajasF))>0){
	for($i=0;$i<count($cajasF);$i++) {
	$CajasInfo[$i]=array("id_caja"=>$cajasF[$i]->id_caja,"fechaHoraApertura"=>$cajasF[$i]->fechaHoraApertura,"fechaHoraCierre"=>$cajasF[$i]->fechaHoraCierre,"fondoInicial"=>$cajasF[$i]->fondoInicial,"EfectivoCerrar"=>$cajasF[$i]->EfectivoCerrar);
	}
	return ($CajasInfo);
}
}
function ld_tikets_old($id_caja,$caja){
$tikets = $caja->ld_tickets_old($id_caja);	
   if ((sizeof($tikets))>0){
	  for($i=0;$i<count($tikets);$i++) {
	  $TicketsInfo[$i]=array("idComanda"=>$tikets[$i]->idComanda,"numComanda"=>$tikets[$i]->numComanda,"estado"=>$tikets[$i]->estado,"fechaHora"=>$tikets[$i]->fechaHora,"total"=>$tikets[$i]->total,"efectivo"=>$tikets[$i]->efectivo,"tipoCliente"=>$tikets[$i]->tipoCliente,"nombre"=>$tikets[$i]->nombre,"free"=>$tikets[$i]->free);
	  }
	  return ($TicketsInfo);
   }		
}
function ld_movimientos_old($id_caja,$caja){
$movimientos = $caja->load_movimientos_old($id_caja);	
   if ((sizeof($movimientos))>0){
	  for($i=0;$i<count($movimientos);$i++) {
	  $movimientoInfo[$i]=array("fechaHora"=>$movimientos[$i]->fechaHora,"tipo"=>$movimientos[$i]->tipo,"dinero"=>$movimientos[$i]->dinero,"descripcion"=>$movimientos[$i]->descripcion,"categoria"=>$movimientos[$i]->categoria,"encargado"=>$movimientos[$i]->encargado);
	  }
	  return ($movimientoInfo);
   }		
}
?>
