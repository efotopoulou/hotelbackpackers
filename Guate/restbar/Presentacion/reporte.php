<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_reporte.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_caja.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_turno.php');

class getreporte{

function getdatos($id_caja,$mensaje){
$reporte=new reporte();
$caja=new caja();
$turno=new turno();
try{
$totalcomida=$caja->total_comida($id_caja);
$totalbebida=$caja->total_bebida($id_caja);
//$totalTickets=$caja->total_tickets_old($id_caja);
$fechaHoraApertura=$reporte->get_fechaHoraApertura($id_caja);
$fechaHoraCierre=$reporte->get_fechaHoraCierre($id_caja);
$categorias=$reporte->get_categories();
$movimientos=$reporte->get_resumen($id_caja);
  $totales["entrada"]=0;
  $totales["salida"]=0;
  if ((sizeof($movimientos))>0){
	  for($i=0;$i<count($movimientos);$i++) {
	   $categorias[$movimientos[$i]->categoria][$movimientos[$i]->tipo]=$movimientos[$i]->suma;
	   $totales[$movimientos[$i]->tipo]+=$movimientos[$i]->suma;
	   }
	   $totales["entrada"]+=$totalcomida+$totalbebida;
	   
  }
  
   $categorias["Adicion Bar"]["entrada"]=$totalbebida;
   $categorias["Adicion Restaurante"]["entrada"]=$totalcomida;
  	
$reportmov=$reporte->get_reporte($id_caja);
if ((sizeof($reportmov))>0){
	  for($i=0;$i<count($reportmov);$i++) {
	  $ReportDetails[$i]=array("id_categoria"=>$reportmov[$i]->id_categoria,"date"=>$reportmov[$i]->date,"time"=>$reportmov[$i]->time,"descripcion"=>$reportmov[$i]->descripcion,"entrada"=>$reportmov[$i]->entrada,"salida"=>$reportmov[$i]->salida,"categoria"=>$reportmov[$i]->categoria);
	  }
 }	
$reporttik=$reporte->get_tiquets($id_caja);
if ((sizeof($reporttik))>0){
	  for($i=0;$i<count($reporttik);$i++) {
	  $ReportTikDetails[$i]=array("fecha"=>$reporttik[$i]->fecha,"time"=>$reporttik[$i]->time,"idComanda"=>$reporttik[$i]->idComanda,"total"=>$reporttik[$i]->total);
	  }
 }	
$ventaturno=$turno->venta_turno_bar ($id_caja);
if ((sizeof($ventaturno))>0){
	  for($i=0;$i<count($ventaturno);$i++) {
	  $VentaTurno[$i]=array("numBebida"=>$ventaturno[$i]->numBebida,"nombre"=>$ventaturno[$i]->nombre,"suma"=>$ventaturno[$i]->suma,"precio"=>$ventaturno[$i]->precio);
	  }
 }	

}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }
 
$response["TotalEntradas"]=$totales["entrada"];
$response["TotalSalidas"]=$totales["salida"];
$response["HoraApertura"]=$fechaHoraApertura;
$response["HoraCierre"]=$fechaHoraCierre;
$response["Info"]=$categorias;
$response["ReportDetails"]=$ReportDetails;
$response["Tiquets"]=$ReportTikDetails;
$response["VentaTurno"]=$VentaTurno;
return ($response);
}

function getdatosExcel($id_caja,$mensaje){
$reporte=new reporte();
$caja=new caja();
$turno=new turno();

try{
//$totalTickets=$caja->total_tickets_old($id_caja);
//$totalmovimientos=$caja->total_mov_old($id_caja);
//$entrytot = $totalmovimientos->entrada;
//$exittot = $totalmovimientos->salida;
$totalcomida=$caja->total_comida($id_caja);
$totalbebida=$caja->total_bebida($id_caja);
$fechaHoraApertura=$reporte->get_fechaHoraApertura($id_caja);
$fechaHoraCierre=$reporte->get_fechaHoraCierre($id_caja);
$categorias=$reporte->get_categories();
$movimientos=$reporte->get_resumen($id_caja);
  if ((sizeof($movimientos))>0){
	  for($i=0;$i<count($movimientos);$i++) {
	   $categorias[$movimientos[$i]->categoria][$movimientos[$i]->tipo]=$movimientos[$i]->suma;
	   }
	  }
  
   $categorias["Adicion Bar"]["entrada"]=$totalbebida;	
   $categorias["Adicion Restaurante"]["entrada"]=$totalcomida;	
   	
$reportmov=$reporte->get_reporte($id_caja);
$ReportDetails = array();
if ((sizeof($reportmov))>0){
	  for($i=0;$i<count($reportmov);$i++) {
	  $ReportDetails[$reportmov[$i]->categoria][]=array("date"=>$reportmov[$i]->date,"time"=>$reportmov[$i]->time,"descripcion"=>$reportmov[$i]->descripcion,"entrada"=>$reportmov[$i]->entrada,"salida"=>$reportmov[$i]->salida);
     }
 }	
$reporttik=$reporte->get_tiquets($id_caja);
if ((sizeof($reporttik))>0){
	  for($i=0;$i<count($reporttik);$i++) {
	  $ReportTikDetails["Adicion Bar Restaurante"][]=array("fecha"=>$reporttik[$i]->fecha,"time"=>$reporttik[$i]->time,"idComanda"=>$reporttik[$i]->idComanda,"total"=>$reporttik[$i]->total);
	  }
 }
 
$ventaturno=$turno->venta_turno_bar ($id_caja);
if ((sizeof($ventaturno))>0){
	  for($i=0;$i<count($ventaturno);$i++) {
	  $VentaTurno[$i]=array("numBebida"=>$ventaturno[$i]->numBebida,"nombre"=>$ventaturno[$i]->nombre,"suma"=>$ventaturno[$i]->suma,"precio"=>$ventaturno[$i]->precio);
	  }
 }	
 	
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }

//$response["TotalEntradas"]=$entrytot + $totalTickets;
//if ($exittot) $response["TotalSalidas"]=$exittot;
//else $response["TotalSalidas"]=0;
$response["HoraApertura"]=$fechaHoraApertura;
$response["HoraCierre"]=$fechaHoraCierre;
$response["Info"]=$categorias;
$response["ReportDetails"]=$ReportDetails;
$response["Tiquets"]=$ReportTikDetails;
$response["VentaTurno"]=$VentaTurno;
return ($response);
}

}
?>	